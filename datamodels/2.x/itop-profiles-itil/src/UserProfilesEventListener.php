<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\ItilProfiles;

use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;
use Exception;
use IssueLog;
use LogChannels;

define('POWER_USER_PORTAL_PROFILE_NAME', 'Portal power user');

/**
 * Class UserProfilesEventListener
 *
 * @package Combodo\iTop\Core\EventListener
 * @since 3.1 N°5324 - Avoid to have users with non-standalone power portal profile only
 *
 */
class UserProfilesEventListener implements iEventServiceSetup
{
	const USERPROFILE_REPAIR_ITOP_PARAM_NAME = 'security.single_profile_completion';
	private $bIsRepairmentEnabled = false;

	//map: non standalone profile name => repairing profile id
	private $aNonStandaloneProfilesMap = [];

	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		$this->Init();

		if (false === $this->bIsRepairmentEnabled){
			return;
		}

		$aEventSource = [\User::class, \UserExternal::class, \UserInternal::class];
		EventService::RegisterListener(
			EVENT_DB_BEFORE_WRITE,
			[$this, 'OnUserEdition'],
			$aEventSource
		);

		EventService::RegisterListener(
			EVENT_DB_BEFORE_WRITE,
			[ $this, 'OnUserProfileEdition' ],
			[ \URP_UserProfile::class ],
			[],
			null
		);

		EventService::RegisterListener(
			EVENT_DB_CHECK_TO_DELETE,
			[ $this, 'OnUserProfileLinkDeletion' ],
			[ \URP_UserProfile::class ],
			[],
			null
		);
	}

	public function IsRepairmentEnabled() : bool
	{
		return $this->bIsRepairmentEnabled;
	}


	public function OnUserEdition(EventData $oEventData): void {
		/** @var \User $oObject */
		$oUser = $oEventData->Get('object');

		try {
			$this->ValidateThenRepairOrWarn($oUser);
		} catch (Exception $e) {
			IssueLog::Error('Exception occurred on RepairProfiles', LogChannels::DM_CRUD, [
				'user_class' => get_class($oUser),
				'user_id' => $oUser->GetKey(),
				'exception_message' => $e->getMessage(),
				'exception_stacktrace' => $e->getTraceAsString(),
			]);
			if ($e instanceof \CoreCannotSaveObjectException){
				throw $e;
			}
		}
	}

	public function OnUserProfileEdition(EventData $oEventData): void {
		$oURP_UserProfile = $oEventData->Get('object');

		try {
			$iUserId = $oURP_UserProfile->Get('userid');
			$oUser = \MetaModel::GetReentranceObjectByChildClass(\User::class, $iUserId);
			if (false !== $oUser){
				//user edition: handled by other event
				return;
			}

			$oUser = \MetaModel::GetObject(\User::class, $iUserId);
			$aChanges = $oURP_UserProfile->ListChanges();
			if (array_key_exists('userid', $aChanges)) {
				$iUserId = $oURP_UserProfile->GetOriginal('userid');
				$oPreviousUser = \MetaModel::GetObject(\User::class, $iUserId);

				$oProfileLinkSet = $oPreviousUser->Get('profile_list');
				$oProfileLinkSet->Rewind();
				$iCount = 0;
				$sSingleProfileName = null;
				while ($oCurrentURP_UserProfile = $oProfileLinkSet->Fetch()) {
					if ($oCurrentURP_UserProfile->Get('userid') !== $oCurrentURP_UserProfile->GetOriginal('userid')) {
						$sRemovedProfileId = $oCurrentURP_UserProfile->GetOriginal('profileid');
						continue;
					}

					$iCount++;
					if ($iCount  > 1){
						//more than one profile: no repairment needed
						return;
					}
					$sSingleProfileName = $oCurrentURP_UserProfile->Get('profile');
				}
				$this->RepairProfileChangesOrWarn($oPreviousUser, $sSingleProfileName, $oURP_UserProfile, $sRemovedProfileId);
			} else if (array_key_exists('profileid', $aChanges)){
				$oCurrentUserProfileSet = $oUser->Get('profile_list');
				if ($oCurrentUserProfileSet->Count() === 1){
					$oProfile = $oCurrentUserProfileSet->Fetch();

					$this->RepairProfileChangesOrWarn($oUser, $oProfile->Get('profile'), $oURP_UserProfile, $oProfile->GetOriginal("profileid"));
				}
			}
		} catch (Exception $e) {
			IssueLog::Error('OnUserProfileEdition Exception', LogChannels::DM_CRUD, [
				'user_id' => $iUserId,
				'lnk_id' => $oURP_UserProfile->GetKey(),
				'exception_message' => $e->getMessage(),
				'exception_stacktrace' => $e->getTraceAsString(),
			]);
			if ($e instanceof \CoreCannotSaveObjectException){
				throw $e;
			}
		}
	}

	public function OnUserProfileLinkDeletion(EventData $oEventData): void {
		$oURP_UserProfile = $oEventData->Get('object');

		try {
			$iUserId = $oURP_UserProfile->Get('userid');
			$oUser = \MetaModel::GetReentranceObjectByChildClass(\User::class, $iUserId);
			if (false !== $oUser){
				//user edition: handled by other event
				return;
			}

			$oUser = \MetaModel::GetObject(\User::class, $iUserId);

			/** @var \DeletionPlan $oDeletionPlan */
			$oDeletionPlan = $oEventData->Get('deletion_plan');
			$aDeletedURP_UserProfiles = [];
			if (! is_null($oDeletionPlan)){
				$aListDeletes = $oDeletionPlan->ListDeletes();
				if (array_key_exists(\URP_UserProfile::class, $aListDeletes)) {
					foreach ($aListDeletes[\URP_UserProfile::class] as $iId => $aDeletes) {
						$aDeletedURP_UserProfiles []= $iId;
					}
				}
			}

			$oProfileLinkSet = $oUser->Get('profile_list');
			$oProfileLinkSet->Rewind();
			$sSingleProfileName = null;
			$iCount = 0;
			while ($oCurrentURP_UserProfile = $oProfileLinkSet->Fetch()) {
				if (in_array($oCurrentURP_UserProfile->GetKey(), $aDeletedURP_UserProfiles)) {
					continue;
				}
				$iCount++;
				if ($iCount  > 1){
					//more than one profile: no repairment needed
					return;
				}
				$sSingleProfileName = $oCurrentURP_UserProfile->Get('profile');
			}

			$this->RepairProfileChangesOrWarn($oUser, $sSingleProfileName, $oURP_UserProfile, $oURP_UserProfile->Get('profileid'), true);
		} catch (Exception $e) {
			IssueLog::Error('OnUserProfileLinkDeletion Exception', LogChannels::DM_CRUD, [
				'user_id' => $iUserId,
				'profile_id' => $oURP_UserProfile->Get('profileid'),
				'exception_message' => $e->getMessage(),
				'exception_stacktrace' => $e->getTraceAsString(),
			]);
		}
	}


	/**
	 * @param $aPortalDispatcherData: passed only for testing purpose
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function Init($aPortalDispatcherData=null) : void {
		if (is_null($aPortalDispatcherData)){
			$aPortalDispatcherData = \PortalDispatcherData::GetData();
		}

		$aNonStandaloneProfiles = \utils::GetConfig()->Get(self::USERPROFILE_REPAIR_ITOP_PARAM_NAME, null);

		//When there are several customized portals on an itop, choosing a specific profile means choosing which portal user will access
		//In that case, itop administrator has to specify it via itop configuration. we dont use default profiles repairment otherwise
		if (is_null($aNonStandaloneProfiles)){
			if (count($aPortalDispatcherData) > 2){
				$this->bIsRepairmentEnabled = false;
				return;
			}

			$aPortalNames = array_keys($aPortalDispatcherData);
			sort($aPortalNames);
			if ($aPortalNames !== ['backoffice', 'itop-portal']){
				$this->bIsRepairmentEnabled = false;
				return;
			}
		}

		if (is_null($aNonStandaloneProfiles)){
			//default configuration in the case there are no customized portals
			$aNonStandaloneProfiles = [ POWER_USER_PORTAL_PROFILE_NAME => PORTAL_PROFILE_NAME ];
		}

		if (! is_array($aNonStandaloneProfiles)){
			\IssueLog::Error(sprintf("%s is badly configured. it should be an array.", self::USERPROFILE_REPAIR_ITOP_PARAM_NAME), null, [self::USERPROFILE_REPAIR_ITOP_PARAM_NAME => $aNonStandaloneProfiles]);
			$this->bIsRepairmentEnabled = false;
			return;
		}

		if (empty($aNonStandaloneProfiles)){
			//Feature specifically disabled in itop configuration
			$this->bIsRepairmentEnabled = false;
			return;
		}


		$this->FetchRepairingProfileIds($aNonStandaloneProfiles);
	}

	public function FetchRepairingProfileIds(array $aNonStandaloneProfiles) : void {
		$aProfiles = [];
		try {
			$aProfilesToSearch = array_unique(array_values($aNonStandaloneProfiles));
			if(($iIndex = array_search(null, $aProfilesToSearch)) !== false) {
				unset($aProfilesToSearch[$iIndex]);
			}

			if (1 === count($aProfilesToSearch)){
				$sInCondition = sprintf('"%s"', array_pop($aProfilesToSearch));
			} else {
				$sInCondition = sprintf('"%s"', implode('","', $aProfilesToSearch));
			}

			$sOql = "SELECT URP_Profiles WHERE name IN ($sInCondition)";
			$oSearch = \DBSearch::FromOQL($sOql);
			$oSearch->AllowAllData();
			$oSet = new \DBObjectSet($oSearch);
			while(($oProfile = $oSet->Fetch()) != null) {
				$sProfileName = $oProfile->Get('name');
				$aProfiles[$sProfileName] = $oProfile->GetKey();
			}

			$this->aNonStandaloneProfilesMap = [];
			foreach ($aNonStandaloneProfiles as $sNonStandaloneProfileName => $sRepairProfileName) {
				if (is_null($sRepairProfileName)) {
					$this->aNonStandaloneProfilesMap[$sNonStandaloneProfileName] = null;
					continue;
				}

				if (! array_key_exists($sRepairProfileName, $aProfiles)) {
					throw new \Exception(sprintf("%s is badly configured. profile $sRepairProfileName does not exist.", self::USERPROFILE_REPAIR_ITOP_PARAM_NAME));
				}

				$this->aNonStandaloneProfilesMap[$sNonStandaloneProfileName] = [ 'name' => $sRepairProfileName, 'id' => $aProfiles[$sRepairProfileName]];
			}

			$this->bIsRepairmentEnabled = true;
		} catch (\Exception $e) {
			IssueLog::Error('Exception when searching user portal profile', LogChannels::DM_CRUD, [
				'exception_message' => $e->getMessage(),
				'exception_stacktrace' => $e->getTraceAsString(),
				'aProfiles' => $aProfiles,
				'aNonStandaloneProfiles' => $aNonStandaloneProfiles,
			]);
			$this->bIsRepairmentEnabled = false;
		}
	}

	public function ValidateThenRepairOrWarn(\User $oUser) : void
	{
		$oCurrentUserProfileSet = $oUser->Get('profile_list');
		if ($oCurrentUserProfileSet->Count() === 1){
			$oProfile = $oCurrentUserProfileSet->Fetch();

			$this->RepairUserChangesOrWarn($oUser, $oProfile->Get('profile'));
		}
	}

	public function RepairUserChangesOrWarn(\User $oUser, string $sSingleProfileName) : void {
		if (array_key_exists($sSingleProfileName, $this->aNonStandaloneProfilesMap)) {
			$aRepairingProfileInfo = $this->aNonStandaloneProfilesMap[$sSingleProfileName];
			if (is_null($aRepairingProfileInfo)){
				//Notify current user via session messages that there will be an issue
				//Without preventing from commiting
				//$oUser::SetSessionMessage(get_class($oUser), $oUser->GetKey(), 1, $sMessage, 'WARNING', 1);
				$sMessage = \Dict::Format("Class:User/NonStandaloneProfileWarning", $sSingleProfileName, $oUser->Get('friendlyname'));
				throw new \CoreCannotSaveObjectException(array('issues' => [$sMessage], 'class' => get_class($oUser), 'id' => $oUser->GetKey()));
			} else {
				//Completing profiles profiles by adding repairing one : by default portal user to a power portal user
				$oUserProfile = new \URP_UserProfile();
				$oUserProfile->Set('profileid', $aRepairingProfileInfo['id']);
				$oCurrentUserProfileSet = $oUser->Get('profile_list');
				$oCurrentUserProfileSet->AddItem($oUserProfile);
				$oUser->Set('profile_list', $oCurrentUserProfileSet);
				$sMessage = \Dict::Format("Class:User/NonStandaloneProfileWarning-ReparationMessage", $sSingleProfileName, $oUser->Get('friendlyname'), $aRepairingProfileInfo['name']);
				$oUser::SetSessionMessage(get_class($oUser), $oUser->GetKey(), 1, $sMessage, 'WARNING', 1);
			}
		}
	}

	public function RepairProfileChangesOrWarn(\User $oUser, ?string $sSingleProfileName, \URP_UserProfile $oURP_UserProfile, string $sRemovedProfileId, $bIsRemoval=false) : void {
		if (is_null($sSingleProfileName)){
			return;
		}

		if (array_key_exists($sSingleProfileName, $this->aNonStandaloneProfilesMap)) {
			$aRepairingProfileInfo = $this->aNonStandaloneProfilesMap[$sSingleProfileName];
			if (is_null($aRepairingProfileInfo)
				|| ($aRepairingProfileInfo['id'] === $sRemovedProfileId) //cannot repair by readding same remove profile as it will raise uniqueness rule
			){
				//Notify current user via session messages that there will be an issue
				//Without preventing from commiting
				//$oURP_UserProfile::SetSessionMessage(get_class($oURP_UserProfile), $oURP_UserProfile->GetKey(), 1, $sMessage, 'WARNING', 1);
				$sMessage = \Dict::Format("Class:User/NonStandaloneProfileWarning", $sSingleProfileName, $oUser->Get('friendlyname'));
				if ($bIsRemoval){
					$oURP_UserProfile->AddDeleteIssue($sMessage);
				} else {
					throw new \CoreCannotSaveObjectException(array('issues' => [$sMessage], 'class' => get_class($oURP_UserProfile), 'id' => $oURP_UserProfile->GetKey()));
				}
			} else {
				//Completing profiles profiles by adding repairing one : by default portal user to a power portal user
				$oUserProfile = new \URP_UserProfile();
				$oUserProfile->Set('profileid', $aRepairingProfileInfo['id']);
				$oCurrentUserProfileSet = $oUser->Get('profile_list');
				$oCurrentUserProfileSet->AddItem($oUserProfile);
				$oUser->Set('profile_list', $oCurrentUserProfileSet);
				$oUser->DBWrite();

				$sMessage = \Dict::Format("Class:User/NonStandaloneProfileWarning-ReparationMessage", $sSingleProfileName, $oUser->Get('friendlyname'), $aRepairingProfileInfo['name']);
				$oURP_UserProfile::SetSessionMessage(get_class($oURP_UserProfile), $oURP_UserProfile->GetKey(), 1, $sMessage, 'WARNING', 1);
			}
		}
	}
}

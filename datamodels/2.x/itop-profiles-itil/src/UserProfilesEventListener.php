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
	const USERPROFILE_REPAIR_ITOP_PARAM_NAME = 'poweruserportal-repair-profile';
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

		$callback = [$this, 'OnUserProfileLinkChange'];
		$aEventSource = [\User::class, \UserExternal::class, \UserInternal::class];

		EventService::RegisterListener(
			EVENT_DB_BEFORE_WRITE,
			$callback,
			$aEventSource
		);

		EventService::RegisterListener(
			EVENT_DB_LINKS_CHANGED,
			$callback,
			$aEventSource
		);
	}

	public function IsRepairmentEnabled() : bool
	{
		return $this->bIsRepairmentEnabled;
	}

	public function OnUserProfileLinkChange(EventData $oEventData): void {
		/** @var \User $oObject */
		$oUser = $oEventData->Get('object');

		try {
			$this->RepairProfiles($oUser);
		} catch (Exception $e) {
			IssueLog::Error('Exception occurred on RepairProfiles', LogChannels::DM_CRUD, [
				'user_class' => get_class($oUser),
				'user_id' => $oUser->GetKey(),
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

		$aNonStandaloneProfiles = \utils::GetConfig()->GetModuleSetting('itop-profiles-itil', self::USERPROFILE_REPAIR_ITOP_PARAM_NAME, null);

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

		try {
			$this->FetchRepairingProfileIds($aNonStandaloneProfiles);
		} catch (\Exception $e) {
			IssueLog::Error('Exception when searching user portal profile', LogChannels::DM_CRUD, [
				'exception_message' => $e->getMessage(),
				'exception_stacktrace' => $e->getTraceAsString(),
			]);
			$this->bIsRepairmentEnabled = false;
			return;
		}

		$this->bIsRepairmentEnabled = true;
	}

	public function FetchRepairingProfileIds(array $aNonStandaloneProfiles) : void {
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
		$aProfiles = [];
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

			if (!array_key_exists($sRepairProfileName, $aProfiles)) {
				throw new \Exception(sprintf("%s is badly configured. profile $sRepairProfileName does not exist.", self::USERPROFILE_REPAIR_ITOP_PARAM_NAME));
			}

			$this->aNonStandaloneProfilesMap[$sNonStandaloneProfileName] = $aProfiles[$sRepairProfileName];
		}
	}

	public function RepairProfiles(?\User $oUser) : void
	{
		if (!is_null($oUser))
		{
			$oCurrentUserProfileSet = $oUser->Get('profile_list');
			if ($oCurrentUserProfileSet->Count() === 1){
				$oProfile = $oCurrentUserProfileSet->Fetch();
				$sSingleProfileName = $oProfile->Get('profile');

				if (array_key_exists($sSingleProfileName, $this->aNonStandaloneProfilesMap)) {
					$sRepairingProfileId = $this->aNonStandaloneProfilesMap[$sSingleProfileName];
					if (is_null($sRepairingProfileId)){
						//Notify current user via session messages that there will be an issue
						//Without preventing from commiting
					} else {
						//Completing profiles profiles by adding repairing one : by default portal user to a power portal user
						$oUserProfile = new \URP_UserProfile();
						$oUserProfile->Set('profileid', $sRepairingProfileId);
						$oCurrentUserProfileSet->AddItem($oUserProfile);
						$oUser->Set('profile_list', $oCurrentUserProfileSet);
					}
				}
			}
		}
	}

}

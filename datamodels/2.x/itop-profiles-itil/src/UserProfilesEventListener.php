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
	private $oUserPortalProfile;
	private $bIsRepairmentEnabled = false;

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

		$sRepairmentProfile = \utils::GetConfig()->GetModuleSetting('itop-profiles-itil', 'poweruserportal-repair-profile', null);

		if (is_null($sRepairmentProfile) && sizeof($aPortalDispatcherData) > 2){
			//when there are further portals we dont want to force a specific portal by repairing the associated profiles to a user
			$this->bIsRepairmentEnabled = false;
			return;
		}

		if (is_null($sRepairmentProfile)){
			$sRepairmentProfile = PORTAL_PROFILE_NAME;
		}

		try {
			$sOQL = sprintf("SELECT URP_Profiles WHERE name = '%s'", $sRepairmentProfile);
			$oSearch = \DBSearch::FromOQL($sOQL);
			$oSearch->AllowAllData();
			$oSet = new \DBObjectSet($oSearch);
			if ($oSet->Count() !== 1) {
				//user portal profile does not exist
				//current iTop is customized enough to disable repairment
				$this->bIsRepairmentEnabled = false;
				return;
			}

			$this->oUserPortalProfile = $oSet->Fetch();
			if (is_null($this->oUserPortalProfile)){
				//may be not required. preventive code to disable repairment
				$this->bIsRepairmentEnabled = false;
				return;
			}
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

	public function RepairProfiles(\User $oUser) : void
	{
		if (!is_null($oUser))
		{
			$oCurrentUserProfileSet = $oUser->Get('profile_list');
			if ($oCurrentUserProfileSet->Count() === 1){
				$oProfile = $oCurrentUserProfileSet->Fetch();

				if (POWER_USER_PORTAL_PROFILE_NAME === $oProfile->Get('profile')){
					//add portal user
					// power portal user is not a standalone profile (it will break console UI)
					$oUserProfile = new \URP_UserProfile();
					$oUserProfile->Set('profileid', $this->oUserPortalProfile->GetKey());
					$oCurrentUserProfileSet->AddItem($oUserProfile);
					$oUser->Set('profile_list', $oCurrentUserProfileSet);
				}
			}
		}
	}

}

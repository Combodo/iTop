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
	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
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

	public function OnUserProfileLinkChange(EventData $oEventData): void {
		/** @var \User $oObject */
		$oUser = $oEventData->Get('object');

		try {
			self::RepairProfiles($oUser);
		} catch (Exception $oException) {
			IssueLog::Error('Exception occurred on OnUserProfileLinkChange', LogChannels::DM_CRUD, [
				'user_class' => get_class($oUser),
				'user_id' => $oUser->GetKey(),
				'exception_message' => $oException->getMessage(),
				'exception_stacktrace' => $oException->getTraceAsString(),
			]);
		}
	}

	public static function RepairProfiles(\User $oUser) : void
	{
		if (!is_null($oUser))
		{
			$oCurrentUserProfileSet = $oUser->Get('profile_list');
			if ($oCurrentUserProfileSet->Count() === 1){
				$oProfile = $oCurrentUserProfileSet->Fetch();

				if (POWER_USER_PORTAL_PROFILE_NAME === $oProfile->Get('profile')){
					//add portal user
					// power portal user is not a standalone profile (it will break console UI)
					$sOQL = sprintf("SELECT URP_Profiles WHERE name = '%s'", PORTAL_PROFILE_NAME);
					$oSearch = \DBSearch::FromOQL($sOQL);
					$oSearch->AllowAllData();
					$oSet = new \DBObjectSet($oSearch);
					if ($oSet->Count() !==1){
						return;
					}

					$oUserPortalProfile = $oSet->Fetch();
					$oUserProfile = new \URP_UserProfile();
					$oUserProfile->Set('profileid', $oUserPortalProfile->GetKey());
					$oCurrentUserProfileSet->AddItem($oUserProfile);
					$oUser->Set('profile_list', $oCurrentUserProfileSet);
				}
			}
		}
	}

}

<?php
namespace Combodo\iTop\Service\Notification;


use Contact;
use Trigger;

class NotificationsService {
	/**
	 * @param \Trigger $oTrigger
	 * @param \ActionNotification $oActionNotification
	 * @param \Contact $oRecipient
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function RegisterSubscription(Trigger $oTrigger, \ActionNotification $oActionNotification, Contact $oRecipient): void {
		// Check if the user is already subscribed to the action notification
		$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchLnkByTriggerContactAndAction($oTrigger->GetKey(), $oRecipient->GetKey(), $oActionNotification->GetKey());
		if ($oSubscribedActionsNotificationsSet->Count() === 0) {
			// Create a new subscription
			$oSubscribedActionsNotifications = new \lnkActionNotificationToContact();
			$oSubscribedActionsNotifications->Set('action_id', $oActionNotification->GetKey());
			$oSubscribedActionsNotifications->Set('contact_id', $oRecipient->GetKey());
			$oSubscribedActionsNotifications->Set('trigger_id', $oTrigger->GetKey());
			$oSubscribedActionsNotifications->Set('subscribed', true);
			$oSubscribedActionsNotifications->DBInsertNoReload();
		}
		else {
			while ($oSubscribedActionsNotifications = $oSubscribedActionsNotificationsSet->Fetch()) {
				// Update the subscription
				$oSubscribedActionsNotifications->Set('subscribed', true);
				$oSubscribedActionsNotifications->DBUpdate();
			}
		}
	}

	/**
	 * @param \Trigger $oTrigger
	 * @param \ActionNotification $oActionNotification
	 * @param \Contact $oRecipient
	 *
	 * @return bool|int|mixed|\ormLinkSet|string|void|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function IsSubscribed(Trigger $oTrigger, \ActionNotification $oActionNotification, Contact $oRecipient): bool{
		// Check if the trigger subscription policy is 'force_all_channels'
		if($oTrigger->Get('subscription_policy') === 'force_all_channels'){
			return true;
		}
		// Check if the user is already subscribed to the action notification
		$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchLnkByTriggerContactAndAction($oTrigger->GetKey(), $oRecipient->GetKey(), $oActionNotification->GetKey());
		if ($oSubscribedActionsNotificationsSet->Count() === 0) {
			return false;
		}
		// Return the subscribed status
		while ($oSubscribedActionsNotifications = $oSubscribedActionsNotificationsSet->Fetch()) {
			return $oSubscribedActionsNotifications->Get('subscribed');
		}
	}

}
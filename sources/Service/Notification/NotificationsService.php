<?php
namespace Combodo\iTop\Service\Notification;


use ActionNotification;
use Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy;
use Contact;
use lnkActionNotificationToContact;
use Trigger;

/**
 * Class NotificationsService
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Service\Notification
 * @since 3.2.0
 */
class NotificationsService {
	protected static ?NotificationsService $oSingleton = null;

	/**
	 * @api
	 * @return static The singleton instance of the notifications service
	 */
	public static function GetInstance(): static
	{
		if (null === static::$oSingleton) {
			static::$oSingleton = new static();
		}

		return static::$oSingleton;
	}

	/**********************/
	/* Non-static methods */
	/**********************/

	/**
	 * Singleton pattern, can't use the constructor. Use {@see \Combodo\iTop\Service\Notification\NotificationsService::GetInstance()} instead.
	 *
	 * @return void
	 */
	protected function __construct() {
		// Don't do anything, we don't want to be initialized
	}

	/**
	 * Register that $oRecipient was a recipient for the $oTrigger / $oActionNotification tuple at least one time
	 *
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
	public function RegisterSubscription(Trigger $oTrigger, ActionNotification $oActionNotification, Contact $oRecipient): void
	{
		// Check if the user is already subscribed to the action notification
		$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByTriggerContactAndAction($oTrigger->GetKey(), $oActionNotification->GetKey(), $oRecipient->GetKey());
		if ($oSubscribedActionsNotificationsSet->Count() === 0) {
			// Create a new subscription
			$oSubscribedActionsNotifications = new lnkActionNotificationToContact();
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
	 * @return bool
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function IsSubscribed(Trigger $oTrigger, ActionNotification $oActionNotification, Contact $oRecipient): bool
	{
		// Check if the trigger subscription policy is 'force_all_channels'
		if ($oTrigger->Get('subscription_policy') === SubscriptionPolicy::ForceAllChannels->value) {
			return true;
		}
		// Check if the user is already subscribed to the action notification
		$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByTriggerContactAndAction($oTrigger->GetKey(), $oActionNotification->GetKey(), $oRecipient->GetKey());
		if ($oSubscribedActionsNotificationsSet->Count() === 0) {
			return true;
		}

		// Return the subscribed status
		$oSubscribedActionsNotifications = $oSubscribedActionsNotificationsSet->Fetch();
		return $oSubscribedActionsNotifications->Get('subscribed');
	}

}
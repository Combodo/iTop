<?php

namespace Combodo\iTop\Service\Notification;

use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Expression;
use UserRights;

/**
 * Class NotificationsRepository
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Service\Notification
 * @since 3.2.0
 */
class NotificationsRepository
{
	/** @var NotificationsRepository|null Singleton */
	protected static ?NotificationsRepository $oSingleton = null;

	/**
	 * @api
	 * @return static The singleton instance of the notifications repository
	 */
	public static function GetInstance(): static
	{
		if (is_null(self::$oSingleton)) {
			self::$oSingleton = new static();
		}

		return self::$oSingleton;
	}

	/**********************/
	/* Non-static methods */
	/**********************/

	/**
	 * Singleton pattern, can't use the constructor. Use {@see \Combodo\iTop\Service\Notification\NotificationsRepository::GetInstance()} instead.
	 *
	 * @return void
	 */
	protected function __construct()
	{
		// Don't do anything, we don't want to be initialized
	}

	/**
	 * @param int $iContactId ID of the contact to retrieve notifications for
	 * @param array $aNotificationIds Optional IDs of the notifications to retrieve, if omitted all notifications will be retrieved
	 *
	 * @return \DBObjectSet Set of notifications for $iContactId, optionally limited to $aNotificationIds
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function SearchNotificationsByContact(int $iContactId, array $aNotificationIds = []): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForNotificationsByContact($iContactId, $aNotificationIds);
		return new DBObjectSet($oSearch);
	}

	/**
	 * @param int $iContactId ID of the contact to retrieve unread notifications for
	 * @param array $aNotificationIds Optional IDs of the unread notifications to retrieve, if omitted all unread notifications will be retrieved
	 *
	 * @return \DBObjectSet Set of unread notifications for $iContactId, optionally limited to $aNotificationIds
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function SearchNotificationsToMarkAsReadByContact(int $iContactId, array $aNotificationIds = []): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForNotificationsByContact($iContactId, $aNotificationIds);
		$oSearch->AddCondition("read", "no");

		return new DBObjectSet($oSearch);
	}

	/**
	 * @param int $iContactId ID of the contact to retrieve read notifications for
	 * @param array $aNotificationIds Optional IDs of the read notifications to retrieve, if omitted all read notifications will be retrieved
	 *
	 * @return \DBObjectSet Set of read notifications for $iContactId, optionally limited to $aNotificationIds
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function SearchNotificationsToMarkAsUnreadByContact(int $iContactId, array $aNotificationIds = []): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForNotificationsByContact($iContactId, $aNotificationIds);
		$oSearch->AddCondition("read", "yes");

		return new DBObjectSet($oSearch);
	}

	/**
	 * @param int $iContactId ID of the contact to retrieve read notifications for
	 * @param array $aNotificationIds Optional IDs of the notifications to retrieve, if omitted all notifications will be retrieved
	 *
	 * @return \DBObjectSet Set of notifications for $iContactId, optionally limited to $aNotificationIds
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function SearchNotificationsToDeleteByContact(int $iContactId, array $aNotificationIds = []): DBObjectSet
	{
		return $this->SearchNotificationsByContact($iContactId, $aNotificationIds);
	}

	/**
	 * @param int $iContactId ID of the contact to retrieve notifications for
	 * @param array $aNotificationIds Optional IDs of the notifications to retrieve, if omitted all notifications will be retrieved
	 *
	 * @internal
	 * @return \DBSearch
	 * @throws \OQLException
	 */
	protected function PrepareSearchForNotificationsByContact(int $iContactId, array $aNotificationIds = []): DBSearch
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT EventNotificationNewsroom WHERE contact_id = :contact_id");
		$aParams = [
			"contact_id" => $iContactId,
		];

		if (count($aNotificationIds) > 0) {
			$oSearch->AddConditionExpression(Expression::FromOQL("{$oSearch->GetClassAlias()}.id IN (:notification_ids)"));
			$aParams["notification_ids"] = $aNotificationIds;
		}

		$oSearch->SetInternalParams($aParams);

		return $oSearch;
	}

	/**
	 * Search for subscriptions by contact ID.
	 *
	 * @param int $iContactId The ID of the contact.
	 *
	 * @return DBObjectSet The result set of subscriptions associated with the contact.
	 */
	public function SearchSubscriptionsByContact(int $iContactId): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id");
		$oSearch->SetInternalParams([
			"contact_id" => $iContactId
		]);

		return new DBObjectSet($oSearch);
	}

	/**
	 * Searches for subscriptions by trigger, contact, and action; no matter it subscription status.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iActionId The ID of the action.
	 * @param int|null $iContactId The ID of the contact. If null, current user will be used.
	 *
	 * @return DBObjectSet The set of subscriptions matching the given trigger, contact, and action.
	 */
	public function SearchSubscriptionsByTriggerContactAndAction(int $iTriggerId, int $iActionId, int|null $iContactId = null): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForSubscriptionsByTriggerContactAndAction($iTriggerId, $iActionId, $iContactId);
		return new DBObjectSet($oSearch);
	}

	/**
	 * Searches for subscriptions by trigger, contact, and action; where contact is subscribed.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iActionId The ID of the action.
	 * @param int|null $iContactId The ID of the contact. If null, current user will be used.
	 *
	 * @return DBObjectSet The set of subscriptions matching the given trigger, contact, and action.
	 */
	public function SearchSubscribedSubscriptionsByTriggerContactAndAction(int $iTriggerId, int $iActionId, int|null $iContactId = null): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForSubscriptionsByTriggerContactAndAction($iTriggerId, $iActionId, $iContactId);
		$oSearch->AddCondition("subscribed", "1");
		return new DBObjectSet($oSearch);
	}

	/**
	 * Searches for subscriptions by trigger, contact, and action; where contact is unsubscribed.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iActionId The ID of the action.
	 * @param int|null $iContactId The ID of the contact. If null, current user will be used.
	 *
	 * @return DBObjectSet The set of subscriptions matching the given trigger, contact, and action.
	 */
	public function SearchUnsubscribedSubscriptionsByTriggerContactAndAction(int $iTriggerId, int $iActionId, int $iContactId = null): DBObjectSet
	{
		$oSearch = $this->PrepareSearchForSubscriptionsByTriggerContactAndAction($iTriggerId, $iActionId, $iContactId);
		$oSearch->AddCondition("subscribed", "0");
		return new DBObjectSet($oSearch);
	}

	/**
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iActionId The ID of the action.
	 * @param int|null $iContactId The ID of the contact. If null, current user will be used.
	 *
	 * @internal
	 * @return \DBSearch Search for subscriptions given tuple of $iTriggerId, $iActionId and $iContactId
	 * @throws \OQLException
	 */
	protected function PrepareSearchForSubscriptionsByTriggerContactAndAction(int $iTriggerId, int $iActionId, int|null $iContactId = null): DBSearch
	{
		if (null === $iContactId) {
			$iContactId = UserRights::GetContactId();
		}

		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id");
		$oSearch->SetInternalParams([
			"contact_id" => $iContactId,
			"trigger_id" => $iTriggerId,
			"action_id" => $iActionId,
		]);

		return $oSearch;
	}

	/**
	 * Search for subscriptions based on trigger, contact, and subscription type.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iContactId The ID of the contact.
	 * @param string $sSubscription The subscription type.
	 *
	 * @return DBObjectSet A set of subscription objects matching the given parameters.
	 */
	public function SearchSubscriptionsByTriggerContactAndSubscription(int $iTriggerId, int $iContactId, string $sSubscription): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription");
		$oSearch->SetInternalParams([
			"trigger_id" => $iTriggerId,
			"contact_id" => $iContactId,
			"subscription" => $sSubscription]
		);

		return new DBObjectSet($oSearch);
	}


	/**
	 * Search for subscriptions based on trigger, contact, subscription type, and final class.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iContactId The ID of the contact.
	 * @param int $sSubscription The subscription type.
	 * @param string $sFinalclass The final class of the action notification.
	 *
	 * @return DBObjectSet A set of subscription objects matching the given parameters.
	 */
	public function SearchSubscriptionsByTriggerContactSubscriptionAndFinalclass(int $iTriggerId, int $iContactId, int $sSubscription, string $sFinalclass): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk JOIN ActionNotification AS an ON lnk.action_id = an.id WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription AND an.finalclass = :finalclass");
		$oSearch->SetInternalParams([
			"trigger_id" => $iTriggerId,
			"contact_id" => $iContactId,
			"subscription" => $sSubscription,
			"finalclass" => $sFinalclass
		]);

		return new DBObjectSet($oSearch);
	}

	public function GetSearchOQLContactUnsubscribedByTriggerAndAction(): DBObjectSearch
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT Contact AS c JOIN lnkActionNotificationToContact AS lnk ON lnk.contact_id = c.id  WHERE lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id AND lnk.subscribed = '0'");
		return $oSearch;
	}
}
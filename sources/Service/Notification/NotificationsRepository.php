<?php

namespace Combodo\iTop\Service\Notification;

use DBObjectSearch;
use DBObjectSet;

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
	 * Search for subscriptions by contact ID.
	 *
	 * @param int $iContactId The ID of the contact.
	 *
	 * @return DBObjectSet The result set of subscriptions associated with the contact.
	 */
	public function SearchSubscriptionByContact(int $iContactId): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id");
		$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

		return $oSet;
	}

	/**
	 * Searches for a subscription by trigger, contact, and action.
	 *
	 * @param int $iTriggerId The ID of the trigger.
	 * @param int $iContactId The ID of the contact.
	 * @param int $iActionID The ID of the action.
	 *
	 * @return DBObjectSet The set of subscriptions matching the given trigger, contact, and action.
	 */
	public function SearchSubscriptionByTriggerContactAndAction(int $iTriggerId, int $iContactId, int $iActionID): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id");
		$oSet = new DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'action_id' => $iActionID));

		return $oSet;
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
	public function SearchSubscriptionByTriggerContactAndSubscription(int $iTriggerId, int $iContactId, string $sSubscription): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription");
		$oSet = new DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'subscription' => $sSubscription));

		return $oSet;
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
	public function SearchSubscriptionByTriggerContactSubscriptionAndFinalclass(int $iTriggerId, int $iContactId, int $sSubscription, string $sFinalclass): DBObjectSet
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk JOIN ActionNotification AS an ON lnk.action_id = an.id WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription AND an.finalclass = :finalclass");
		$oSet = new DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'subscription' => $sSubscription, 'finalclass' => $sFinalclass));

		return $oSet;
	}

	public function GetSearchOQLContactUnsubscribedByTriggerAndAction(): DBObjectSearch
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT Contact AS c JOIN lnkActionNotificationToContact AS lnk ON lnk.contact_id = c.id  WHERE lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id AND lnk.subscribed = '0'");
		return $oSearch;
	}
}
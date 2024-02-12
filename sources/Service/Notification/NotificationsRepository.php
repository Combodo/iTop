<?php

namespace Combodo\iTop\Service\Notification;

use DBObjectSearch;

class NotificationsRepository
{
	/** @var NotificationsRepository|null Singleton */
	static private ?NotificationsRepository $oSingletonInstance = null;

	/**
	 * GetInstance.
	 *
	 * @return NotificationsRepository
	 */
	public static function GetInstance(): NotificationsRepository
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new NotificationsRepository();
		}

		return self::$oSingletonInstance;
	}

	/**
	 * Constructor.
	 *
	 */
	private function __construct()
	{
	}
	
	public function SearchLnkByContact(int $iContactId): \DBObjectSet {
		$oSearch = DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id");
		$oSet = new \DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

		return $oSet;
	}
	public function SearchLnkByTriggerContactAndAction(int $iTriggerId, int $iContactId, int $iActionID) {
		$oSearch = \DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id");
		$oSet = new \DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'action_id' => $iActionID));

		return $oSet;
	}

	public function SearchLnkByTriggerContactAndSubscription(int $iTriggerId, int $iContactId, string $sSubscription): \DBObjectSet {
		$oSearch = \DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription");
		$oSet = new \DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'subscription' => $sSubscription));

		return $oSet;
	}

	
	public function SearchLnkByTriggerContactSubscriptionAndFinalclass(int $iTriggerId, int $iContactId, int $sSubscription, string $sFinalclass): \DBObjectSet
	{
		$oSearch = \DBObjectSearch::FromOQL("SELECT lnkActionNotificationToContact AS lnk JOIN ActionNotification AS an ON lnk.action_id = an.id WHERE lnk.contact_id = :contact_id AND lnk.trigger_id = :trigger_id AND lnk.subscribed = :subscription AND an.finalclass = :finalclass");
		$oSet = new \DBObjectSet($oSearch, array(), array('trigger_id' => $iTriggerId, 'contact_id' => $iContactId, 'subscription' => $sSubscription, 'finalclass' => $sFinalclass));

		return $oSet;
	}
	public function GetSearchOQLContactUnsubscribedByTriggerAndAction(): \DBObjectSearch{
		$oSearch = \DBObjectSearch::FromOQL("SELECT Contact AS c JOIN lnkActionNotificationToContact AS lnk ON lnk.contact_id = c.id  WHERE lnk.trigger_id = :trigger_id AND lnk.action_id = :action_id AND lnk.subscribed = '0'");
		return $oSearch;
	}
}
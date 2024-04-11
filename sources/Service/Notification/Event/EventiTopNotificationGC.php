<?php

namespace  Combodo\iTop\Service\Notification\Event;

use DBObjectSearch;
use DBObjectSet;
use Exception;
use ExceptionLog;
use iBackgroundProcess;
use MetaModel;

class EventiTopNotificationGC implements iBackgroundProcess
{

	public function Process($iUnixTimeLimit)
	{
		try {
			$iDeletionTime = (int) MetaModel::GetConfig()->Get('notifications.itop.read_notification_retention');
			$oDBObjectSearch = DBObjectSearch::FromOQL("SELECT EventiTopNotification WHERE read='yes' AND read_date < DATE_SUB(NOW(), INTERVAL :deletion_time DAY)", ['deletion_time' => $iDeletionTime]);
			$oEventiTopNotificationSet = new DBObjectSet($oDBObjectSearch);
			while($oEventiTopNotification = $oEventiTopNotificationSet->Fetch()){
				$oEventiTopNotification->DBDelete();
			}
		}
		catch (Exception $e) {
			ExceptionLog::LogException($e);
			return false;
		}
		return true;
	}

	public function GetPeriodicity()
	{
		return 24*3600; // Every day
	}
}
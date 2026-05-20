<?php

namespace Combodo\iTop\Service\Notification\Event;

use Action;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\WebPage\WebPage;
use EventNotificationNewsroom;
use MetaModel;
use ormDocument;
use utils;

/**
 * Class EventNotificationNewsroomService
 *
 * Service to create EventNotificationNewsroom objects from various sources.
 *
 * @package Combodo\iTop\Service\Notification\Event
 * @since 3.2.0
 * @api
*/
class EventNotificationNewsroomService
{
	/**
	 * @param \Action $oAction
	 * @param int $iContactId
	 * @param int $iTriggerId
	 * @param string $sMessage
	 * @param string $sTitle
	 * @param string $sUrl
	 * @param int $iObjectId
	 * @param string|null $sObjectClass
	 * @param string|null $sDate
	 *
	 * @return \EventNotificationNewsroom
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public static function MakeEventFromAction(Action $oAction, int $iContactId, int $iTriggerId, string $sMessage, string $sTitle, string $sUrl, int $iObjectId, ?string $sObjectClass, string|null $sDate = null): EventNotificationNewsroom
	{

		$oEvent = new EventNotificationNewsroom();
		$oEvent->Set('title', $sTitle);
		$oEvent->Set('message', $sMessage);
		// Compute icon
		// - First check if one is defined on the action
		if (false === $oAction->Get('icon')->IsEmpty()) {
			$oIcon = $oAction->Get('icon');
		}
		// - Then, check if the action is for a DM object and if its class has an icon
		elseif ($iObjectId > 0 && utils::IsNotNullOrEmptyString(MetaModel::GetClassIcon($sObjectClass, false))) {
			$oIcon = MetaModel::GetAttributeDef(EventNotificationNewsroom::class, 'icon')->MakeRealValue(MetaModel::GetClassIcon($sObjectClass, false), $oEvent);
		}
		// - Otherwise, fallback on the compact logo of the application
		else {
			$oIcon = MetaModel::GetAttributeDef(EventNotificationNewsroom::class, 'icon')->MakeRealValue(Branding::GetCompactMainLogoAbsoluteUrl(), $oEvent);
		}
		$oEvent->Set('icon', $oIcon);

		$oEvent->Set('priority', $oAction->Get('priority'));
		$oEvent->Set('contact_id', $iContactId);
		$oEvent->Set('trigger_id', $iTriggerId);
		$oEvent->Set('action_id', $oAction->GetKey());
		$oEvent->Set('object_id', $iObjectId);
		$oEvent->Set('url', $sUrl);
		if ($sDate !== null) {
			$oEvent->Set('date', $sDate);
		} else {
			$oEvent->SetCurrentDate('date');
		}

		return $oEvent;
	}

	/**
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 * @param string $sId
	 * @param int $iContactId
	 *
	 * @return bool Returns true if the download has been launched, false otherwise (e.g. if the event doesn't exist or doesn't belong to the current user)
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public static function DownloadIcon(WebPage $oPage, string $sId, int $iContactId): bool
	{
		$oEvent = MetaModel::GetObject(EventNotificationNewsroom::class, $sId, false, true);
		if (($oEvent !== null) && ($oEvent->Get('contact_id') === $iContactId)) {
			ormDocument::DownloadDocument(
				$oPage,
				EventNotificationNewsroom::class,
				$sId,
				'icon',
				ormDocument::ENUM_CONTENT_DISPOSITION_INLINE,
				bAllowAllData: true
			);
			return true;
		}

		return false;
	}
}

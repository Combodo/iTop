<?php namespace  Combodo\iTop\Service\Notification\Event;



use Action;
use Combodo\iTop\Application\Branding;
use EventiTopNotification;
use MetaModel;
use utils;
/**
 * Class EventiTopNotificationService
 *
 * Service to create EventiTopNotification objects from various sources.
 *
 * @package Combodo\iTop\Service\Notification\Event
 * @since 3.2.0
 * @api
*/
class EventiTopNotificationService {
	/**
	 * @param \Action $oAction
	 * @param int $iContactId
	 * @param int $iTriggerId
	 * @param string $sMessage
	 * @param string $sTitle
	 * @param string $sUrl
	 * @param int $iObjectId
	 * @param string $sObjectClass
	 * @param string|null $sDate
	 *
	 * @return \EventiTopNotification
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public static function MakeEventFromAction(Action $oAction, int $iContactId, int $iTriggerId, string $sMessage, string $sTitle, string $sUrl, int $iObjectId, string $sObjectClass, string|null $sDate = null): EventiTopNotification
	{
		
		$oEvent = new EventiTopNotification();
		$oEvent->Set('title', $sTitle);
		$oEvent->Set('message', $sMessage);
		// Compute icon
		// - First check if one is defined on the action
		if (false === $oAction->Get('icon')->IsEmpty()) {
			$oIcon = $oAction->Get('icon');
		}
		// - Then, check if the action is for a DM object and if its class has an icon
		elseif ($iObjectId > 0 && utils::IsNotNullOrEmptyString(MetaModel::GetClassIcon($sObjectClass, false))) {
			$oIcon = MetaModel::GetAttributeDef(EventiTopNotification::class, 'icon')->MakeRealValue(MetaModel::GetClassIcon($sObjectClass, false), $oEvent);
		}
		// - Otherwise, fallback on the compact logo of the application
		else {
			$oIcon = MetaModel::GetAttributeDef(EventiTopNotification::class, 'icon')->MakeRealValue(Branding::GetCompactMainLogoAbsoluteUrl(), $oEvent);
		}
		$oEvent->Set('icon', $oIcon);

		$oEvent->Set('priority', $oAction->Get('priority'));
		$oEvent->Set('contact_id', $iContactId);
		$oEvent->Set('trigger_id', $iTriggerId);
		$oEvent->Set('action_id', $oAction->GetKey());
		$oEvent->Set('object_id', $iObjectId);
		$oEvent->Set('url', $sUrl);
		if($sDate !== null){
			$oEvent->Set('date', $sDate);
		} else {
			$oEvent->SetCurrentDate('date');
		}
		
		return $oEvent;
	}
}

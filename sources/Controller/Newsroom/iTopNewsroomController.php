<?php

namespace Combodo\iTop\Controller\Newsroom;

use ArchivedObjectException;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectSummary;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\JsonPPage;
use Combodo\iTop\Service\Router\Router;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use DisplayBlock;
use JSPopupMenuItem;
use MetaModel;
use SecurityException;
use URLPopupMenuItem;
use UserRights;
use utils;


/**
 *  Class iTopNewsroomController
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Controller\Newsroom
 * @since 3.2.0
 */
class iTopNewsroomController extends Controller
{
	public const ROUTE_NAMESPACE = 'itopnewsroom';

	/**
	 * @return iTopWebPage
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function OperationViewAll()
	{
		$oPage = new iTopWebPage(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Title'));
		// Add title block
		$oTitleBlock = TitleUIBlockFactory::MakeNeutral(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Title'));
		$oPage->AddUiBlock($oTitleBlock);
		// Search for all notifications for the current user
		$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification');
		$oSearch->AddCondition('contact_id', UserRights::GetContactId(), '=');
		$oSet = new DBObjectSet($oSearch, array('read' => true, 'date' => true), array());
		
		// Add main content block
		$oContentBlock = new UIContentBlock(null, ['ibo-notifications--view-all--container']);
		$oPage->AddUiBlock($oContentBlock);
		
		$sCSRFToken = utils::GetNewTransactionId();
		while ($oEvent = $oSet->Fetch()) {
			$iEventId = $oEvent->GetKey();
			// Prepare object summary block
			$sReadColor = $oEvent->Get('read') === 'no' ? 'ibo-notifications--view-all--item--unread' : 'ibo-notifications--view-all--item--read';
			$sReadLabel = $oEvent->Get('read') === 'no' ? Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Unread:Label') : Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Read:Label');
			$oEventBlock = new ObjectSummary($oEvent);
			$oEventBlock->SetCSSColorClass($sReadColor);
			$oEventBlock->SetSubTitle($sReadLabel);
			$oEventBlock->SetClassLabel('');
			$oImage = $oEvent->Get('icon');
			if (!$oImage->IsEmpty()) {
				$sIconUrl = $oImage->GetDisplayURL(get_class($oEvent), $iEventId, 'icon');
				$oEventBlock->SetIcon($sIconUrl, Panel::ENUM_ICON_COVER_METHOD_COVER,true);
			}
			
			// Prepare Event actions
			$oMarkAsReadPopoverMenu = new PopoverMenu();
			$oMarkAsUnreadPopoverMenu = new PopoverMenu();
			
			// Common actions
			$sDeleteUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.delete_event', ['event_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oDeleteButton = new JSPopupMenuItem(
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label',
				Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label'),
				<<<JS
let	oSelf = this;

$.ajax({
	url: '{$sDeleteUrl}',
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			$(oSelf).parents('.ibo-object-summary').remove();
			CombodoToast.OpenSuccessToast(data.message);
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS,
				'_blank'
			);
			$oViewButton = new URLPopupMenuItem(
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label',
				Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label'),
				Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.view_event', ['event_id' => $oEvent->GetKey()]),
				'_blank'
			);
			
			// Mark as read action
			$oMarkAsReadButton = ButtonUIBlockFactory::MakeForAlternativeSecondaryAction(
				Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label'),
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label',
			);
			
			// Mark as read action
			$oMarkAsReadPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oViewButton))->SetContainer(PopoverMenu::ENUM_CONTAINER_PARENT);
			$oMarkAsReadPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oDeleteButton))->SetContainer(PopoverMenu::ENUM_CONTAINER_PARENT);

			// Mark as unread action
			$oMarkAsUnreadPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oViewButton))->SetContainer(PopoverMenu::ENUM_CONTAINER_PARENT);
			$oMarkAsUnreadPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oDeleteButton))->SetContainer(PopoverMenu::ENUM_CONTAINER_PARENT);


			// Mark as unread action
			$sMarkAsReadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_as_read', ['event_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oMarkAsReadButton->SetOnClickJsCode(
				<<<JS
				let oSelf = this;
				$.ajax({
					url: '{$sMarkAsReadUrl}',
					type: 'POST',
					success: function(data) {
						if (data.status === 'success') {
							$(oSelf).parent('.ibo-button-group').addClass('ibo-is-hidden');
							$(oSelf).parent('.ibo-button-group').siblings('.ibo-button-group').removeClass('ibo-is-hidden');
							$(oSelf).parents('.ibo-object-summary').removeClass('ibo-notifications--view-all--item--unread').addClass('ibo-notifications--view-all--item--read');
							CombodoToast.OpenSuccessToast(data.message);
						}
						else {
							CombodoToast.OpenErrorToast(data.message);
						}
					}
				});
JS
			);
			
			$oMarkAsReadButtonGroup = ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu($oMarkAsReadButton, $oMarkAsReadPopoverMenu);

			$oMarkAsUnreadButton = ButtonUIBlockFactory::MakeForAlternativeSecondaryAction(
				Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label'),
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label'
			);
			$sMarkAsUnreadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_as_unread', ['event_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oMarkAsUnreadButton->SetOnClickJsCode(
				<<<JS
				let oSelf = this;
				$.ajax({
					url: '{$sMarkAsUnreadUrl}',
					type: 'POST',
					success: function(data) {
						if (data.status === 'success') {
							$(oSelf).parent('.ibo-button-group').addClass('ibo-is-hidden');
							$(oSelf).parent('.ibo-button-group').siblings('.ibo-button-group').removeClass('ibo-is-hidden');
							$(oSelf).parents('.ibo-object-summary').removeClass('ibo-notifications--view-all--item--read').addClass('ibo-notifications--view-all--item--unread');
							CombodoToast.OpenSuccessToast(data.message);
						}
						else {
							CombodoToast.OpenErrorToast(data.message);
						}
					}
				});
JS
			);

			$oMarkAsUnreadButtonGroup = ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu($oMarkAsUnreadButton, $oMarkAsUnreadPopoverMenu);
			
			// Add actions to the object summary block and remove old button
			$oOldButtonId = $oEventBlock->GetActions()->GetId();
			$oEventBlock->RemoveSubBlock($oOldButtonId);
			$oEventBlock->SetToolBlocks([$oMarkAsReadButtonGroup, $oMarkAsUnreadButtonGroup]);
			$oActionsBlock = new UIContentBlock();
			$oActionsBlock->AddSubBlock($oMarkAsReadButtonGroup);
			$oActionsBlock->AddSubBlock($oMarkAsUnreadButtonGroup);
			$oEventBlock->SetActions($oActionsBlock);
			
			// Display the right button depending on the read status
			if($oEvent->Get('read') === 'no'){
				$oMarkAsUnreadButtonGroup->SetCSSClasses(['ibo-is-hidden']);
			}
			else{
				$oMarkAsReadButtonGroup->SetCSSClasses(['ibo-is-hidden']);
			}
			
			$oContentBlock->AddSubBlock($oEventBlock);
		}

		return $oPage;
	}

	/**
	 * @return JsonPPage
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OperationFetchUnreadMessages()
	{
		$sCallback = utils::ReadParam('callback', '');
		$oPage = new JsonPPage($sCallback);

		$aMessages = [];
		$iContactId = UserRights::GetContactId();

		if (utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while ($oMessage = $oSet->Fetch()) {
				$sTitle = $oMessage->Get('title');
				$sMessage = $oMessage->Get('message');
				$sText = <<<HTML
**$sTitle**


$sMessage
HTML;

				$sIcon = $oMessage->Get('icon') !== null ?
					$oMessage->Get('icon')->GetDisplayURL('EventiTopNotification', $oMessage->GetKey(), 'icon') :
					Branding::GetCompactMainLogoAbsoluteUrl();
				$aMessages[] = array(
					'id'         => $oMessage->GetKey(),
					'text'       => $sText,
					'url'        => Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.view_event', ['event_id' => $oMessage->GetKey()]),
					'target'     => '_self',
					'start_date' => $oMessage->Get('date'),
					'priority'   => $oMessage->Get('priority'),
					'image'      => $sIcon,
				);
			}

		}
		$oPage->SetData($aMessages);
		$oPage->SetOutputDataOnly(true);

		return $oPage;
	}

	/**
	 * @return int
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function OperationMarkAllAsReadMessages()
	{
		$iCount = 0;
		$iContactId = UserRights::GetContactId();


		if (utils::IsNotNullOrEmptyString($iContactId)) {
			$oSearch = DBObjectSearch::FromOQL('SELECT EventiTopNotification WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while ($oEvent = $oSet->Fetch()) {
				$oEvent->Set('read', 'yes');
				$oEvent->SetCurrentDate('read_date');
				$oEvent->DBWrite();
				$iCount++;
			}
		}

		return $iCount;
	}

	/**
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function OperationViewEvent()
	{
		$sEventId = utils::ReadParam('event_id', 0);
		if ($sEventId > 0) {
			try {
				$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
				if ($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()) {
					$oEvent->Set('read', 'yes');
					$oEvent->SetCurrentDate('read_date');
					$oEvent->DBWrite();
					$sUrl = $oEvent->Get('url');
					header("Location: $sUrl");
				}
			}
			catch (ArchivedObjectException|CoreException $e) {
				$this->DisplayPageNotFound();
			}
		}
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 * @throws \SecurityException
	 */
	public function OperationMarkAsUnread(): JsonPage
	{
		$oPage = new JsonPage();
		$sCSRFToken = utils::ReadParam('token', '', 'raw_data');
		if(utils::IsTransactionValid($sCSRFToken, false) === false){
			throw new SecurityException('Invalid CSRF token');
		}
		$sEventId = utils::ReadParam('event_id', 0);
		$aReturnData = [
			'status' => 'error',
			'message' => 'Invalid event'
		];
		if ($sEventId > 0) {
			try {
				$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
				if ($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()) {
					$oEvent->Set('read', 'no');
					$oEvent->DBWrite();
					$aReturnData['status'] = 'success';
					$aReturnData['message'] = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Success:Message');
				}
			}
			catch (ArchivedObjectException|CoreException $e) {
			$aReturnData['message'] = $e->getMessage();
			}
		}
		$oPage->SetData($aReturnData);
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 * @throws \SecurityException
	 */
	public function OperationMarkAsRead(): JsonPage
	{
		$oPage = new JsonPage();
		$sCSRFToken = utils::ReadParam('token', '', 'raw_data');
		if(utils::IsTransactionValid($sCSRFToken, false) === false){
			throw new SecurityException('Invalid CSRF token');
		}
		$sEventId = utils::ReadParam('event_id', 0);
		$aReturnData = [
			'status' => 'error',
			'message' => 'Invalid event'
		];
		if ($sEventId > 0) {
			try {
				$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
				if ($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()) {
					$oEvent->Set('read', 'yes');
					$oEvent->SetCurrentDate('read_date');
					$oEvent->DBWrite();
					$aReturnData['status'] = 'success';
					$aReturnData['message'] = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Success:Message');
				}
			}
			catch (ArchivedObjectException|CoreException $e) {
				$aReturnData['message'] = $e->getMessage();
			}
		}
		$oPage->SetData($aReturnData);
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 * @throws \SecurityException
	 */
	public function OperationDeleteEvent(): JsonPage
	{
		$oPage = new JsonPage();
		$sCSRFToken = utils::ReadParam('token', '', 'raw_data');
		if(utils::IsTransactionValid($sCSRFToken, false) === false){
			throw new SecurityException('Invalid CSRF token');
		}
		$sEventId = utils::ReadParam('event_id', 0);
		$aReturnData = [
			'status' => 'error',
			'message' => 'Invalid event'
		];
		if ($sEventId > 0) {
			try {
				$oEvent = MetaModel::GetObject('EventiTopNotification', $sEventId);
				if ($oEvent !== null && $oEvent->Get('contact_id') === UserRights::GetContactId()) {
					$oEvent->DBDelete();
					$aReturnData['status'] = 'success';
					$aReturnData['message'] = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Success:Message');
				}
			}
			catch (ArchivedObjectException|CoreException $e) {
				$aReturnData['message'] = $e->getMessage();
			}
		}
		$oPage->SetData($aReturnData);
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}
}
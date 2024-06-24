<?php

namespace Combodo\iTop\Controller\Newsroom;

use ArchivedObjectException;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Toggler;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectSummary;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\JsonPPage;
use Combodo\iTop\Controller\Notifications\NotificationsCenterController;
use Combodo\iTop\Service\Notification\NotificationsRepository;
use Combodo\iTop\Service\Router\Router;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use JSPopupMenuItem;
use MetaModel;
use SecurityException;
use URLPopupMenuItem;
use UserRights;
use utils;
use appUserPreferences;


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
		$oPage->LinkScriptFromAppRoot('js/pages/backoffice/itop-newsroom.view-all.js');
		// Add title block
		// Make bulk actions block
		$oBulkActionsBlock = PanelUIBlockFactory::MakeForInformation(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Title'));
		$oBulkActionsBlock->AddSubTitleBlock(new Html(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:SubTitle')));
		$sPictureUrl = UserRights::GetUserPictureAbsUrl();
		$oBulkActionsBlock->SetIcon($sPictureUrl,Panel::ENUM_ICON_COVER_METHOD_CONTAIN, true);

		$oNotificationsCenterButton = ButtonUIBlockFactory::MakeIconLink(
			'fas fa-cogs',
			Dict::S('UI:NotificationsCenter:Panel:Title'),
			Router::GetInstance()->GenerateUrl(NotificationsCenterController::ROUTE_NAMESPACE.'.display_page'),
		);
		$oBulkActionsBlock->SetToolBlocks([$oNotificationsCenterButton]);
		$oToolbar = ToolbarUIBlockFactory::MakeStandard();
		$oToolbar->AddCSSClass('ibo-notifications--view-all--toolbar');
		$oAllModeButtonsContainer = new UIContentBlock('ibo-notifications--view-all--all-mode-buttons', ['ibo-notifications--view-all--bulk-buttons', 'ibo-notifications--view-all--all-mode-buttons']);
		// Create CSRF token we'll use in this page
		$sCSRFToken = utils::GetNewTransactionId();
		// Make button to mark all as read
		$sMarkMultipleAsReadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_multiple_as_read', ['token' => $sCSRFToken]);
		$sMarkMultipleAsUnreadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_multiple_as_unread', ['token' => $sCSRFToken]);
		$sDeleteMultipleUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.delete_multiple', ['token' => $sCSRFToken]);

		$oMarkAllAsReadButton = ButtonUIBlockFactory::MakeForSecondaryAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsRead:Label'
		);
		$oMarkAllAsReadButton->SetIconClass('far fa-envelope-open')
			->AddCSSClass('ibo-notifications--view-all--read-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToMarkAsRead = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"].ibo-notifications--view-all--item--unread');
let aNotificationIds = [];
oNotificationToMarkAsRead.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
$.ajax({
	url: '{$sMarkMultipleAsReadUrl}',
	data: {
		notification_ids: aNotificationIds
	},
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			let MarkAsReadButton = oNotificationToMarkAsRead.find('.ibo-button-group:not(.ibo-is-hidden)');
			let MarkAsUnreadButton = oNotificationToMarkAsRead.find('.ibo-button-group.ibo-is-hidden');
			MarkAsReadButton.addClass('ibo-is-hidden');
			MarkAsUnreadButton.removeClass('ibo-is-hidden');
			oNotificationToMarkAsRead.removeClass('ibo-notifications--view-all--item--unread').addClass('ibo-notifications--view-all--item--read');
			CombodoToast.OpenSuccessToast(data.message);
			$('.ibo-notifications--view-all--container').trigger('itop.notification.read');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS
		);
		
		// Make button to mark all as unread
		$oMarkAllAsUnreadButton = ButtonUIBlockFactory::MakeForSecondaryAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAllAsUnread:Label',
		);
		$oMarkAllAsUnreadButton->SetIconClass('far fa-envelope')
			->AddCSSClass('ibo-notifications--view-all--unread-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToMarkAsUnread = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"].ibo-notifications--view-all--item--read');
let aNotificationIds = [];
oNotificationToMarkAsUnread.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
$.ajax({
	url: '{$sMarkMultipleAsUnreadUrl}',
	data: {
		notification_ids: aNotificationIds
	},
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			let MarkAsUnreadButton  = oNotificationToMarkAsUnread.find('.ibo-button-group:not(.ibo-is-hidden)');
			let MarkAsReadButton = oNotificationToMarkAsUnread.find('.ibo-button-group.ibo-is-hidden');

			MarkAsReadButton.removeClass('ibo-is-hidden');
			MarkAsUnreadButton.addClass('ibo-is-hidden');
			oNotificationToMarkAsUnread.removeClass('ibo-notifications--view-all--item--read').addClass('ibo-notifications--view-all--item--unread');
			CombodoToast.OpenSuccessToast(data.message);
						$('.ibo-notifications--view-all--container').trigger('itop.notification.unread');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS
		);

		// Make button to delete all
		$oDeleteAllButton = ButtonUIBlockFactory::MakeForDestructiveAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Label'
		);
		$oDeleteAllButtonConfirmTitle = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Title');
		$oDeleteAllButtonConfirmMessage = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteAll:Confirmation:Message');
		$oDeleteAllButton->SetActionType(Button::ENUM_ACTION_TYPE_ALTERNATIVE);
		$oDeleteAllButton->SetIconClass('fas fa-trash-alt')
			->AddCSSClass('ibo-notifications--view-all--delete-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToDelete = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"]');
let aNotificationIds = [];
oNotificationToDelete.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
CombodoModal.OpenConfirmationModal({
    title: '$oDeleteAllButtonConfirmTitle',
    content: '$oDeleteAllButtonConfirmMessage',
    callback_on_confirm: function() {
		$.ajax({
			url: '{$sDeleteMultipleUrl}',
			data: {
				notification_ids: aNotificationIds
			},
			type: 'POST',
			success: function(data) {
				if (data.status === 'success') {
					oNotificationToDelete.remove();
					CombodoToast.OpenSuccessToast(data.message);
					$('.ibo-notifications--view-all--container').trigger('itop.notification.deleted');
				}
				else {
					CombodoToast.OpenErrorToast(data.message);
				}
			}
		});
	},
    buttons: {
        confirm: {
            text: '$oDeleteAllButtonConfirmTitle',
            classes: ['ibo-is-danger']
        }
    },
    do_not_show_again_pref_key: 'notifications-center.delete-all-confirmation-modal.do-not-show-again',
}, []);

JS
		);
		// Add "all" buttons to their container
		$oAllModeButtonsContainer->AddSubBlock($oMarkAllAsReadButton);
		$oAllModeButtonsContainer->AddSubBlock($oMarkAllAsUnreadButton);
		$oAllModeButtonsContainer->AddSubBlock($oDeleteAllButton);
		$oToolbar->AddSubBlock($oAllModeButtonsContainer);
		
		$oSelectedModelButtonsContainer = new UIContentBlock('ibo-notifications--view-all--selected-mode-buttons', ['ibo-is-hidden', 'ibo-notifications--view-all--bulk-buttons', 'ibo-notifications--view-all--selected-mode-buttons']);
		// Make button mark all selected as read
		$oMarkSelectedAsReadButton = ButtonUIBlockFactory::MakeForSecondaryAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsRead:Label'
		);
		$oMarkSelectedAsReadButton->SetIconClass('far fa-envelope-open')
			->AddCSSClass('ibo-notifications--view-all--read-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToMarkAsRead = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"].ibo-notifications--view-all--item--unread.ibo-is-selected');
let aNotificationIds = [];
oNotificationToMarkAsRead.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
$.ajax({
	url: '{$sMarkMultipleAsReadUrl}',
	data: {
		notification_ids: aNotificationIds
	},
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			oNotificationToMarkAsRead.removeClass('ibo-notifications--view-all--item--unread').addClass('ibo-notifications--view-all--item--read');
			CombodoToast.OpenSuccessToast(data.message);
			$('.ibo-notifications--view-all--container').trigger('itop.notification.read');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS
		);

		// Make button mark all selected as unread
		$oMarkSelectedAsUnreadButton = ButtonUIBlockFactory::MakeForSecondaryAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkSelectedAsUnread:Label'
		);
		$oMarkSelectedAsUnreadButton->SetIconClass('far fa-envelope')
			->AddCSSClass('ibo-notifications--view-all--unread-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToMarkAsUnread = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"].ibo-notifications--view-all--item--read.ibo-is-selected');
let aNotificationIds = [];
oNotificationToMarkAsUnread.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
$.ajax({
	url: '{$sMarkMultipleAsUnreadUrl}',
	data: {
		notification_ids: aNotificationIds
	},
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			oNotificationToMarkAsUnread.removeClass('ibo-notifications--view-all--item--read').addClass('ibo-notifications--view-all--item--unread');
			CombodoToast.OpenSuccessToast(data.message);
			$('.ibo-notifications--view-all--container').trigger('itop.notification.unread');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS
		);

		// Make button delete all selected
		$oDeleteSelectedButton = ButtonUIBlockFactory::MakeForDestructiveAction(
			Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label'),
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label',
			'UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Label'
		);
		$oDeleteSelectedButtonConfirmTitle = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Title');
		$oDeleteSelectedButtonConfirmMessage = Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:DeleteSelected:Confirmation:Message');
		$oDeleteSelectedButton->SetActionType(Button::ENUM_ACTION_TYPE_ALTERNATIVE);
		$oDeleteSelectedButton->SetIconClass('fas fa-trash-alt')
			->AddCSSClass('ibo-notifications--view-all--delete-action')
			->SetOnClickJsCode(
			<<<JS
let oSelf = this;
let oNotificationToDelete = $('.ibo-notifications--view-all--container [data-role="ibo-object-summary"].ibo-is-selected');
let aNotificationIds = [];
oNotificationToDelete.each(function(){
	aNotificationIds.push($(this).attr('data-object-id'));
});
CombodoModal.OpenConfirmationModal({
	title: '$oDeleteSelectedButtonConfirmTitle',
	content: '$oDeleteSelectedButtonConfirmMessage',
	callback_on_confirm: function() {
		$.ajax({
			url: '{$sDeleteMultipleUrl}',
			data: {
				notification_ids: aNotificationIds
			},
			type: 'POST',
			success: function(data) {
				if (data.status === 'success') {
					oNotificationToDelete.remove();
					CombodoToast.OpenSuccessToast(data.message);
					$('.ibo-notifications--view-all--container').trigger('itop.notification.deleted');
				}
				else {
					CombodoToast.OpenErrorToast(data.message);
				}
			}
		});
	},
	buttons: {
		confirm: {
			text: '$oDeleteSelectedButtonConfirmTitle',
			classes: ['ibo-is-danger']
		}
	},
	do_not_show_again_pref_key: 'notifications-center.delete-all-confirmation-modal.do-not-show-again',
}, []);
JS
);

		// Add "selected" buttons to their container
		$oSelectedModelButtonsContainer->AddSubBlock($oMarkSelectedAsReadButton);
		$oSelectedModelButtonsContainer->AddSubBlock($oMarkSelectedAsUnreadButton);
		$oSelectedModelButtonsContainer->AddSubBlock($oDeleteSelectedButton);
		
		$oToolbar->AddSubBlock($oSelectedModelButtonsContainer);
		
		// Make toggler to switch between "all" and "selected" mode
		$oTogglerContentBlock = new UIContentBlock('ibo-notifications--view-all--toggler', ['ibo-notifications--view-all--toggler']);
		$oToggler = new Toggler();
		$oInputWithLabel = InputUIBlockFactory::MakeInputWithLabel('slider', Dict::S('UI:Newsroom:iTopNotification:SelectMode:Label'), $oToggler);
		$oTogglerContentBlock->AddSubBlock($oInputWithLabel);
		$oToolbar->AddSubBlock($oTogglerContentBlock);
		
		$oBulkActionsBlock->AddSubBlock($oToolbar);
		$oPage->AddUiBlock($oBulkActionsBlock);
		
		// Search for all notifications for the current user
		$oSearch = DBObjectSearch::FromOQL('SELECT EventNotificationNewsroom');
		$oSearch->AddCondition('contact_id', UserRights::GetContactId(), '=');
		$oSet = new DBObjectSet($oSearch, array('read' => true, 'date' => false), array());
		
		// Add main content block
		$oMainContentBlock = new UIContentBlock(null, ['ibo-notifications--view-all--container']);
		$oPage->AddUiBlock($oMainContentBlock);
		
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
			
			
			// Common actions
			$sDeleteUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.delete_event', ['notification_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oDeleteButton = ButtonUIBlockFactory::MakeForAlternativeDestructiveAction(
				'',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label'
			);
			$oDeleteButton->SetOnClickJsCode(
				<<<JS
let	oSelf = this;

$.ajax({
	url: '{$sDeleteUrl}',
	type: 'POST',
	success: function(data) {
		if (data.status === 'success') {
			$(oSelf).parents('.ibo-object-summary').remove();
			CombodoToast.OpenSuccessToast(data.message);
			$('.ibo-notifications--view-all--container').trigger('itop.notification.deleted');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	}
});
JS
			)
				->SetIconClass('fas fa-trash-alt')
				->SetTooltip(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:Delete:Label'));

			$oViewButton = ButtonUIBlockFactory::MakeIconLink('fas fa-external-link-alt',
				Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:ViewObject:Label'),
				Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.view_event', ['event_id' => $iEventId]),
				'_blank'
			);
			
			// Mark as read action
			$oMarkAsReadButton = ButtonUIBlockFactory::MakeForAlternativeSecondaryAction(
				'',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label'
			);
			$oMarkAsReadButton->AddCSSClass('ibo-notifications--view-all--read-action')
				->SetIconClass('far fa-envelope-open')
				->SetTooltip(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsRead:Label'));

			// Mark as unread action
			$sMarkAsReadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_as_read', ['notification_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oMarkAsReadButton->SetOnClickJsCode(
				<<<JS
				let oSelf = this;
				$.ajax({
					url: '{$sMarkAsReadUrl}',
					type: 'POST',
					success: function(data) {
						if (data.status === 'success') {
							$(oSelf).parents('.ibo-object-summary').removeClass('ibo-notifications--view-all--item--unread').addClass('ibo-notifications--view-all--item--read');
							CombodoToast.OpenSuccessToast(data.message);
							$('.ibo-notifications--view-all--container').trigger('itop.notification.read');
						}
						else {
							CombodoToast.OpenErrorToast(data.message);
						}
					}
				});
JS
			);
			
			$oMarkAsUnreadButton = ButtonUIBlockFactory::MakeForAlternativeSecondaryAction(
				'',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label',
				'UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label'
			);
			$oMarkAsUnreadButton->AddCSSClass('ibo-notifications--view-all--unread-action')
			->SetIconClass('far fa-envelope')
			->SetTooltip(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Action:MarkAsUnread:Label'));
			$sMarkAsUnreadUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.mark_as_unread', ['notification_id' => $oEvent->GetKey(), 'token' => $sCSRFToken]);
			$oMarkAsUnreadButton->SetOnClickJsCode(
				<<<JS
				let oSelf = this;
				$.ajax({
					url: '{$sMarkAsUnreadUrl}',
					type: 'POST',
					success: function(data) {
						if (data.status === 'success') {
							$(oSelf).parents('.ibo-object-summary').removeClass('ibo-notifications--view-all--item--read').addClass('ibo-notifications--view-all--item--unread');
							CombodoToast.OpenSuccessToast(data.message);
							$('.ibo-notifications--view-all--container').trigger('itop.notification.unread');
						}
						else {
							CombodoToast.OpenErrorToast(data.message);
						}
					}
				});
JS
			);
			
			// Add actions to the object summary block and remove old button
			$oOldButtonId = $oEventBlock->GetActions()->GetId();
			$oEventBlock->RemoveSubBlock($oOldButtonId);
			$oEventBlock->SetToolBlocks([$oMarkAsUnreadButton, $oMarkAsReadButton, $oViewButton, $oDeleteButton]);
			$oActionsBlock = new UIContentBlock();
			$oActionsBlock->AddSubBlock($oMarkAsUnreadButton);
			$oActionsBlock->AddSubBlock($oMarkAsReadButton);
			$oActionsBlock->AddSubBlock($oViewButton);
			$oActionsBlock->AddSubBlock($oDeleteButton);
			$oEventBlock->SetActions($oActionsBlock);
			
			
			$oMainContentBlock->AddSubBlock($oEventBlock);
		}
		
		// Add empty content block
		$oEmptyContentBlock = new UIContentBlock('ibo-notifications--view-all--empty', ['ibo-notifications--view-all--empty', 'ibo-svg-illustration--container']);
		$oEmptyContentBlock->AddSubBlock(new Html(file_get_contents(APPROOT.'/images/illustrations/undraw_social_serenity.svg')));
		$oEmptyContentBlock->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S('UI:Newsroom:iTopNotification:ViewAllPage:Empty:Title')));
		$oPage->AddUiBlock($oEmptyContentBlock);
		
		// Hide empty content block if there are notifications
		if($oSet->Count() === 0){
			$oMainContentBlock->AddCSSClass('ibo-is-hidden');
		}
		else {
			$oEmptyContentBlock->AddCSSClass('ibo-is-hidden');
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
			$oSearch = DBObjectSearch::FromOQL('SELECT EventNotificationNewsroom WHERE contact_id = :contact_id AND read = "no"');
			$oSet = new DBObjectSet($oSearch, array(), array('contact_id' => $iContactId));

			while ($oMessage = $oSet->Fetch()) {
				$sTitle = $oMessage->Get('title');
				$sMessage = $oMessage->Get('message');
				$sText = <<<HTML
**$sTitle**


$sMessage
HTML;

				$sIcon = $oMessage->Get('icon') !== null ?
					$oMessage->Get('icon')->GetDisplayURL('EventNotificationNewsroom', $oMessage->GetKey(), 'icon') :
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
			$oSearch = DBObjectSearch::FromOQL('SELECT EventNotificationNewsroom WHERE contact_id = :contact_id AND read = "no"');
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
				$oEvent = MetaModel::GetObject('EventNotificationNewsroom', $sEventId);
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
	 */
	public function OperationMarkAsUnread(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnSingleNotification('mark_as_unread'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationMarkAsRead(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnSingleNotification('mark_as_read'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationDeleteEvent(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnSingleNotification('delete'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationMarkMultipleAsRead(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnMultipleNotifications('mark_as_read'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationMarkMultipleAsUnread(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnMultipleNotifications('mark_as_unread'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationDeleteMultiple(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetData($this->PerformActionOnMultipleNotifications('delete'));
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}

	/**
	 * @param string $sAction
	 *
	 * @return string[]
	 * @throws \SecurityException
	 */
	protected function PerformActionOnSingleNotification(string $sAction): array
	{
		$iNotificationId = utils::ReadParam('notification_id', 0, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);
		return $this->PerformAction($sAction, [$iNotificationId]);
	}

	/**
	 * @param string $sAction
	 *
	 * @return string[]
	 * @throws \SecurityException
	 */
	protected function PerformActionOnMultipleNotifications(string $sAction): array
	{
		$aNotificationIds = utils::ReadParam('notification_ids', []);
		return $this->PerformAction($sAction, $aNotificationIds);
	}

	/**
	 * @param string $sAction
	 * @param array $aNotificationIds
	 *
	 * @return string[]
	 * @throws \SecurityException
	 */
	protected function PerformAction(string $sAction, array $aNotificationIds): array
	{
		$sCSRFToken = utils::ReadParam('token', '', false, 'raw_data');
		if(utils::IsTransactionValid($sCSRFToken, false) === false){
			throw new SecurityException('Invalid CSRF token');
		}

		$sActionAsCamelCase = utils::ToCamelCase($sAction);
		$aReturnData = [
			'status' => 'error',
			'message' => 'Invalid notification(s)'
		];

		// Check action type
		if (false === in_array($sAction, ['mark_as_read', 'mark_as_unread', 'delete'])) {
			$aReturnData['message'] = Dict::S("UI:Newsroom:iTopNotification:ViewAllPage:Action:InvalidAction:Message");
			return $aReturnData;
		}

		// No ID passed to the API
		if (count($aNotificationIds) === 0) {
			$aReturnData['message'] = Dict::S("UI:Newsroom:iTopNotification:ViewAllPage:Action:$sActionAsCamelCase:NoEvent:Message");
			return $aReturnData;
		}

		try {
			$sRepositoryMethodName = "SearchNotificationsTo{$sActionAsCamelCase}ByContact";
			$oSet = NotificationsRepository::GetInstance()->$sRepositoryMethodName(UserRights::GetContactId(), $aNotificationIds);

			// No notification found
			$iCount = $oSet->Count();
			if($iCount === 0) {
				$aReturnData['message'] = Dict::S("UI:Newsroom:iTopNotification:ViewAllPage:Action:$sActionAsCamelCase:NoEvent:Message");
				return $aReturnData;
			}

			while ($oEvent = $oSet->Fetch()) {
				if ($sAction === 'mark_as_read') {
					$oEvent->Set('read', 'yes');
					$oEvent->SetCurrentDate('read_date');
					$oEvent->DBWrite();
				} elseif ($sAction === 'mark_as_unread') {
					$oEvent->Set('read', 'no');
					$oEvent->DBWrite();
				} elseif ($sAction === 'delete') {
					$oEvent->DBDelete();
				}
			}

			$aReturnData['status'] = 'success';
			if ($iCount === 1) {
				$aReturnData['message'] = Dict::S("UI:Newsroom:iTopNotification:ViewAllPage:Action:{$sActionAsCamelCase}:Success:Message");
			} else {
				$aReturnData['message'] = Dict::Format("UI:Newsroom:iTopNotification:ViewAllPage:Action:{$sActionAsCamelCase}Multiple:Success:Message", $iCount);
			}
		} catch (Exception $oException) {
			$aReturnData['message'] = $oException->getMessage();
		}

		return $aReturnData;
	}
}
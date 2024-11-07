<?php
namespace Combodo\iTop\Controller\Notifications;

use ActionNotification;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\Set;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\SetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Controller\Newsroom\iTopNewsroomController;
use Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Service\Notification\NotificationsRepository;
use Combodo\iTop\Service\Router\Router;
use Dict;
use Exception;
use MetaModel;
use utils;
use UserRights;
use appUserPreferences;


/**
*  Class NotificationsCenterController
*
* @author Stephen Abello <stephen.abello@combodo.com>
* @package Combodo\iTop\Controller\Notifications
* @since 3.2.0
*/
class NotificationsCenterController extends Controller
{
	public const ROUTE_NAMESPACE = 'notificationscenter';

	public function CheckPostedCSRF(){
		$sToken = utils::ReadParam('token', '', true, 'raw_data');
		return utils::IsTransactionValid($sToken, false);
	}
	
	/**
	 * Displays a table containing all ActionNotifications that current user is likely to receive and allows to unsubscribe from them.
	 *
	 * @return iTopWebPage
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function OperationDisplayPage()
	{
		$oPage = new iTopWebPage(Dict::S('UI:NotificationsCenter:Page:Title'));
		// Create a panel that will contain the table
		$oNotificationsPanel = new Panel(Dict::S('UI:NotificationsCenter:Panel:Title'), array(), 'grey', 'ibo-notifications-center');
		$oNotificationsPanel->AddCSSClass('ibo-datatable-panel');

		$oNotificationsPanel->AddSubTitleBlock(new Html(Dict::S('UI:NotificationsCenter:Panel:SubTitle')));
		$sPictureUrl = UserRights::GetUserPictureAbsUrl();
		$oNotificationsPanel->SetIcon($sPictureUrl,Panel::ENUM_ICON_COVER_METHOD_CONTAIN, true);

		$oAllNewsPageButton = ButtonUIBlockFactory::MakeIconLink(
			'fas fa-bell',
			Dict::S('UI:NotificationsCenter:Panel:Toolbar:ViewAllNews:Title'),
			Router::GetInstance()->GenerateUrl(iTopNewsroomController::ROUTE_NAMESPACE.'.view_all'),
		);
		$oNotificationsPanel->SetToolBlocks([$oAllNewsPageButton]);

		$oNotificationsCenterTableColumns = [
			'trigger'  => array('label' => MetaModel::GetName('Trigger')),
			'trigger_class' => array('label' => MetaModel::GetAttributeDef('Trigger', 'finalclass')->GetLabel()),
			'complement' => array('label' => MetaModel::GetAttributeDef('Trigger', 'complement')->GetLabel()),
			'channels' => array('label' => Dict::S('UI:NotificationsCenter:Panel:Table:Channels')),
		];

		// Get all subscribed/unsubscribed actions notifications for the current user
		$oLnkNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByContact(\UserRights::GetContactId());
		$oActionsNotificationsByTrigger = [];
		$aSubscribedActionsNotificationsByTrigger = [];
		while ($oLnkActionsNotifications = $oLnkNotificationsSet->Fetch()) {
			$oSubscribedActionNotification = MetaModel::GetObject(ActionNotification::class, $oLnkActionsNotifications->Get('action_id'));
			$oTrigger = MetaModel::GetObject('Trigger', $oLnkActionsNotifications->Get('trigger_id'));
			$iTriggerId = $oTrigger->GetKey();
			// Create an new array for the trigger if it doesn't exist
			if (!isset($oActionsNotificationsByTrigger[$iTriggerId])) {
				$oActionsNotificationsByTrigger[$iTriggerId] = [];
				$aSubscribedActionsNotificationsByTrigger[$iTriggerId] = [];
			}
			// Add the action notification to the list of actions notifications for the trigger
			$oActionsNotificationsByTrigger[$iTriggerId][] = $oSubscribedActionNotification;
			// Add the subscribed status to the list of subscribed actions notifications for the trigger
			$aSubscribedActionsNotificationsByTrigger[$iTriggerId][$oSubscribedActionNotification->GetKey()] = $oLnkActionsNotifications->Get('subscribed') || $oTrigger->Get('subscription_policy') === SubscriptionPolicy::ForceAllChannels->value;
		}

		// Build table rows
		$sSubscribeUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.subscribe_to_channel', [], true);
		$sUnsubscribeUrl = Router::GetInstance()->GenerateUrl(self::ROUTE_NAMESPACE.'.unsubscribe_from_channel', [], true);
		$aInputSetOptions = [];
		$aTableRows = [];
		foreach ($oActionsNotificationsByTrigger as $iTriggerId => $aActionsNotifications) {
			$oTrigger = MetaModel::GetObject('Trigger', $iTriggerId, false);
			if ($oTrigger === null) {
				continue;
			}
			$sTriggerSubscriptionPolicy = $oTrigger->Get('subscription_policy');
			$aChannels = [];
			$aSetValues = [];
			// Create a channel for each action notification class and add it to the channels array
			foreach ($aActionsNotifications as $oActionNotification) {
				$sNotificationClass = get_class($oActionNotification);
				// Create a new channel if it doesn't exist for the current action notification class
				if (!array_key_exists($sNotificationClass, $aChannels)) {
					$aChannels[$sNotificationClass] = [
						'friendlyname'         => MetaModel::GetName($sNotificationClass),
						'has_additional_field' => true,
						'additional_field' => '',
						'value'         => $oTrigger->GetKey().'|'.$sNotificationClass,
						'has_image' => true,
						'picture_url' => 'url("'.MetaModel::GetClassIcon($sNotificationClass, false).'")',
						'subscribed'    => $aSubscribedActionsNotificationsByTrigger[$iTriggerId][$oActionNotification->GetKey()] === true,
						'status' => [$oActionNotification->Get('status') => 1],
						'class' => 'ibo-is-not-medallion',
						'total' => 1,
						'total_subscribed' => $aSubscribedActionsNotificationsByTrigger[$iTriggerId][$oActionNotification->GetKey()] === true ? 1 : 0,
						'mixed' => false,
					];
				} else {
					// Check if all actions from the same type are subscribed or not
					if (($aSubscribedActionsNotificationsByTrigger[$iTriggerId][$oActionNotification->GetKey()] === true) !== $aChannels[$sNotificationClass]['subscribed']) {
						$aChannels[$sNotificationClass]['subscribed'] = 'mixed';
					}
					$aChannels[$sNotificationClass]['total']++;
					$aChannels[$sNotificationClass]['total_subscribed'] += $aSubscribedActionsNotificationsByTrigger[$iTriggerId][$oActionNotification->GetKey()] === true ? 1 : 0;
					// Count the number of actions with the same status
					if (isset($aChannels[$sNotificationClass]['status'][$oActionNotification->Get('status')])) {
						$aChannels[$sNotificationClass]['status'][$oActionNotification->Get('status')]++;
					} else {
						$aChannels[$sNotificationClass]['status'][$oActionNotification->Get('status')] = 1;
					}
				}
			}
			foreach ($aChannels as $sNotificationClass => $aChannel) {
				// Define if all actions from the same type are subscribed or not
				if ($aChannel['subscribed'] === 'mixed') {
					$aSetValues[] = $aChannel['value'];
					$aChannels[$sNotificationClass]['mixed'] = true;
					$aChannels[$sNotificationClass]['mixed_value'] = Dict::Format('UI:NotificationsCenter:Channel:OutOf:Text', $aChannel['total_subscribed'], $aChannel['total']);
				} else if ($aChannel['subscribed'] === true) {
					$aSetValues[] = $aChannel['value'];
				}
				
				// Explode status array into a readable string
				$aChannels[$sNotificationClass]['additional_field'] = implode(', ', array_map(function($iCount, $sStatus) use($sNotificationClass) {
					return $iCount.' '. MetaModel::GetStateLabel($sNotificationClass, $sStatus);
				}, $aChannel['status'], array_keys($aChannel['status'])));
			}
			// Create a input set for the channels
			$oChannelSet = SetUIBlockFactory::MakeForSimple('ibochannel'.$oTrigger->GetKey(), array_values($aChannels), 'friendlyname', 'value', ['friendlyname', 'additional_field'], null, 'additional_field');
			$oChannelSet->SetName('channel-'.$oTrigger->GetKey());
			$oChannelSet->SetInitialValue(json_encode($aSetValues));
			$oChannelSet->SetValue(json_encode($aSetValues));
			$oChannelSet->SetOptionsTemplate('application/object/set/option_renderer.html.twig');
			$oChannelSet->SetItemsTemplate('application/preferences/notification-center/item_renderer.html.twig');
			// Disable the input set if the subscription policy is 'force_all_channels'
			if ($sTriggerSubscriptionPolicy === SubscriptionPolicy::ForceAllChannels->value) {
				$oChannelSet->SetIsDisabled(true);
			}
			// Add a CSRF Token
			$sCSRFToken = utils::GetNewTransactionId();
			$oChannelSet->SetOnItemAddJs(
				<<<JS
let oSelf = this;

// Send subscribe request
$.ajax({
	url: '{$sSubscribeUrl}',
	type: 'POST',
	data: {
		channel: value,
		token: '{$sCSRFToken}',
	},
	dataType: 'json',
	success: function (data) {
		if (data.status === 'success') {
			// Display success message
						oSelf.refreshItems();

			CombodoToast.OpenSuccessToast(data.message);
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	},
	error: function (jqXHR, textStatus, errorThrown) {
		CombodoToast.OpenErrorToast(data.message);
	}
});
JS
			);
			// Set the minimum number of channels to 1 if the subscription policy is {@see SubscriptionPolicy::ForceAtLeastOneChannel}
			if($sTriggerSubscriptionPolicy === SubscriptionPolicy::ForceAtLeastOneChannel->value)
			{
				$oChannelSet->SetMinItems(1);
			}
			$oChannelSet->SetOnItemRemoveJs(
				<<<JS
let oSelf = this;
// Send unsubscribe request
$.ajax({
	url: '{$sUnsubscribeUrl}',
	type: 'POST',
	data: {
		channel: value,
		token: '{$sCSRFToken}',
	},
	dataType: 'json',
	success: function (data) {
		if (data.status === 'success') {
			// Display success message
			CombodoToast.OpenSuccessToast(data.message);
			// Remove item from set
			oSelf.options[value]['mixed'] = false;
			oSelf.clearCache();

			$('#channel$iTriggerId').find('option[value="' + value + '"]').remove();
			$('#channel$iTriggerId').trigger('change');
		}
		else {
			CombodoToast.OpenErrorToast(data.message);
		}
	},
	error: function (jqXHR, textStatus, errorThrown) {
		CombodoToast.OpenErrorToast(data.message);
	}
});
JS
			);
			// Use a renderer to display the input set in a table row
			$oBlockRenderer = new BlockRenderer($oChannelSet);

			$aTableRows[] = [
				'trigger'  => $oTrigger->Get('description'),
				'trigger_class' => MetaModel::GetName($oTrigger->Get('finalclass')),
				'complement' => $oTrigger->Get('complement'),
				'channels' => $oBlockRenderer->RenderHtml(),
				'js'       => $oBlockRenderer->RenderJsInline($oChannelSet::ENUM_JS_TYPE_ON_READY),
			];
		}
		$oNotificationsCenterTable = DataTableUIBlockFactory::MakeForStaticData('', $oNotificationsCenterTableColumns, $aTableRows, 'ibo-notifications-center--datatable', ['surround_with_panel' => true]);
		$oNotificationsPanel->AddSubBlock($oNotificationsCenterTable);

		// Add input js on each page draw so when it's refreshed we keep js interactivity
		foreach ($aTableRows as $aAtt) {
			$sJS = $aAtt['js'];
			$oPage->add_ready_script(
				<<<JS
$('#ibo-notifications-center--datatable').on('init.dt draw.dt', function(){
	$sJS
	CombodoTooltip.InitAllNonInstantiatedTooltips($(this));
});
JS
			);
		}

		// Add Set JS files to the page as we used a renderer ourselves, they are not added automatically by the page
		foreach (Set::DEFAULT_JS_FILES_REL_PATH as $sJsFile) {
			$oPage->LinkScriptFromAppRoot($sJsFile);
		}

		$oPage->AddSubBlock($oNotificationsPanel);

		return $oPage;
	}

	/**
	 * @return \JsonPage
	 * @throws \Exception
	 */
	function OperationUnsubscribeFromChannel(): \JsonPage
	{
		// Get the CSRF token from the request and check if it's valid
		if (!$this->CheckPostedCSRF()) {
			throw new \Exception('Invalid token');
		}
		
		// Get the channel from the request
		$sChannel = utils::ReadParam('channel', '', true, 'raw_data');
		$aChannel = explode('|', $sChannel);

		$oPage = new \JsonPage();
		$aReturnData = [];
		try {
			if (count($aChannel) !== 2) {
				throw new \Exception('Invalid channel');
			}
			[$iTriggerId, $sFinalclass] = $aChannel;
			$oTrigger = MetaModel::GetObject('Trigger', $iTriggerId, false);
			if ($oTrigger === null) {
				throw new \Exception('Invalid trigger');
			}
			// Check the trigger subscription policy
			if ($oTrigger->Get('subscription_policy') === SubscriptionPolicy::ForceAllChannels->value) {
				throw new \Exception('You are not allowed to unsubscribe from this channel');
			}
			
			// Check if we are subscribed to at least 1 channel
			$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByTriggerContactSubscriptionAndFinalclass($iTriggerId, \UserRights::GetContactId(), '1', $sFinalclass);
			if ($oSubscribedActionsNotificationsSet->Count() === 0) {
				throw new \Exception('You are not subscribed to any channel');
			}
			// Check the trigger subscription policy and if we are subscribed to at least 1 channel if necessary
			if($oTrigger->Get('subscription_policy') === SubscriptionPolicy::ForceAtLeastOneChannel->value) {
				$oTotalSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByTriggerContactAndSubscription($iTriggerId, \UserRights::GetContactId(), '1');
				if (($oTotalSubscribedActionsNotificationsSet->Count() - $oSubscribedActionsNotificationsSet->Count()) === 0) {
					throw new \Exception('You can\'t unsubscribe from this channel, you must be subscribed to at least one channel');
				}
			}
			// Unsubscribe from all channels
			while ($oSubscribedActionsNotifications = $oSubscribedActionsNotificationsSet->Fetch()) {
				$oSubscribedActionsNotifications->Set('subscribed', false);
				$oSubscribedActionsNotifications->DBUpdate();
			}
			$aReturnData = [
				'status'  => 'success',
				'message' => Dict::S('UI:NotificationsCenter:Unsubscribe:Success'),
			];
		}
		catch (Exception $e) {
			// Return an error message if an exception is thrown
			$aReturnData = [
				'status'  => 'error',
				'message' => $e->getMessage(),
			];
		}
		$oPage->SetData($aReturnData);
		$oPage->SetOutputDataOnly(true);

		return $oPage;
	}

	/**
	 * @return \JsonPage
	 * @throws \Exception
	 */
	function OperationSubscribeToChannel(): \JsonPage
	{
		// Get the CSRF token from the request and check if it's valid
		if (!$this->CheckPostedCSRF()) {
			throw new \Exception('Invalid token');
		}
		
		// Get the channel from the request
		$sChannel = utils::ReadParam('channel', '', true, 'raw_data');
		$aChannel = explode('|', $sChannel);
		
		$oPage = new \JsonPage();
		$aReturnData = [];
		try {
			if (count($aChannel) !== 2) {
				throw new \Exception('Invalid channel');
			}
			[$iTriggerId, $sFinalclass] = $aChannel;
			$oTrigger = MetaModel::GetObject('Trigger', $iTriggerId, false);
			if ($oTrigger === null) {
				throw new \Exception('Invalid trigger');
			}
			$oSubscribedActionsNotificationsSet = NotificationsRepository::GetInstance()->SearchSubscriptionsByTriggerContactSubscriptionAndFinalclass($iTriggerId, \UserRights::GetContactId(), '0', $sFinalclass);
			if ($oSubscribedActionsNotificationsSet->Count() === 0) {
				throw new \Exception('You are not subscribed to any channel');
			}
			// Subscribe to all channels
			while ($oSubscribedActionsNotifications = $oSubscribedActionsNotificationsSet->Fetch()) {
				$oSubscribedActionsNotifications->Set('subscribed', true);
				$oSubscribedActionsNotifications->DBUpdate();
			}
			$aReturnData = [
				'status'  => 'success',
				'message' => Dict::S('UI:NotificationsCenter:Subscribe:Success'),
			];
		}
		catch (Exception $e) {
			// Return an error message if an exception is thrown
			$aReturnData = [
				'status'  => 'error',
				'message' => $e->getMessage(),
			];
		}
		$oPage->SetData($aReturnData);
		$oPage->SetOutputDataOnly(true);

		return $oPage;
	}
}
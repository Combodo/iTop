<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Dashlet\DashletFactory;
use Combodo\iTop\Application\Dashlet\Service\DashletService;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout as DashboardLayoutUIBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\ServiceLocator\ServiceLocator;

/**
 * Class RuntimeDashboard
 */
class RuntimeDashboard extends Dashboard
{
	/** @var string $sDefinitionFile */
	private $sDefinitionFile = '';
	/** @var null $sReloadURL */
	private $sReloadURL = null;
	/** @var bool $bCustomized */
	protected $bCustomized;

	/**
	 * @inheritDoc
	 */
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->oMetaModel = new ModelReflectionRuntime();
		$this->oDashletFactory->SetModelReflectionRuntime($this->oMetaModel);
		$this->bCustomized = false;
	}

	/**
	 * @return bool
	 * @since 2.7.0
	 */
	public function GetCustomFlag()
	{
		return $this->bCustomized;
	}

	/**
	 * @param bool $bCustomized
	 *
	 * @since 2.7.0
	 */
	public function SetCustomFlag($bCustomized)
	{
		$this->bCustomized = $bCustomized;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	protected function SetFormParams($oForm, $aExtraParams = [])
	{
		$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', ['operation' => 'update_dashlet_property', 'extra_params' => $aExtraParams]);
	}

	/**
	 * @param string $sXml
	 *
	 * @return bool
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
	public function PersistDashboard(string $sXml): bool
	{
		$oUDSearch = new DBObjectSearch('UserDashboard');
		$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oUDSearch->AddCondition('menu_code', $this->sId, '=');
		$oUDSet = new DBObjectSet($oUDSearch);
		$bIsNew = false;
		if ($oUDSet->Count() > 0) {
			// Assuming there is at most one couple {user, menu}!
			$oUserDashboard = $oUDSet->Fetch();
			$oUserDashboard->Set('contents', $sXml);
		} else {
			// No such customized dashboard for the current user, let's create a new record
			$oUserDashboard = new UserDashboard();
			$oUserDashboard->Set('user_id', UserRights::GetUserId());
			$oUserDashboard->Set('menu_code', $this->sId);
			$oUserDashboard->Set('contents', $sXml);
			$bIsNew = true;
		}
		utils::PushArchiveMode(false);
		$oUserDashboard->DBWrite();
		utils::PopArchiveMode();

		return $bIsNew;
	}

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function Revert()
	{
		$oUDSearch = new DBObjectSearch('UserDashboard');
		$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oUDSearch->AddCondition('menu_code', $this->sId, '=');
		$oUDSet = new DBObjectSet($oUDSearch);
		if ($oUDSet->Count() > 0) {
			// Assuming there is at most one couple {user, menu}!
			$oUserDashboard = $oUDSet->Fetch();
			utils::PushArchiveMode(false);
			$oUserDashboard->DBDelete();
			utils::PopArchiveMode();
		}
	}

	/**
	 * @param string $sDashboardFile file name relative to the current module folder
	 * @param string $sDashBoardId code of the dashboard either menu_id or <class>__<attcode>
	 *
	 * @return null|RuntimeDashboard
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetDashboard($sDashboardFile, $sDashBoardId)
	{
		$bCustomized = false;

		$sDashboardFileSanitized = utils::RealPath($sDashboardFile, APPROOT);
		if (false === $sDashboardFileSanitized) {
			throw new SecurityException('Invalid dashboard file !');
		}

		if (!appUserPreferences::GetPref('display_original_dashboard_'.$sDashBoardId, false)) {
			// Search for an eventual user defined dashboard
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $sDashBoardId, '=');
			$oUDSet = new DBObjectSet($oUDSearch);
			if ($oUDSet->Count() > 0) {
				// Assuming there is at most one couple {user, menu}!
				$oUserDashboard = $oUDSet->Fetch();
				$sDashboardDefinition = $oUserDashboard->Get('contents');
				$bCustomized = true;
			} else {
				$sDashboardDefinition = @file_get_contents($sDashboardFileSanitized);
			}
		} else {
			$sDashboardDefinition = @file_get_contents($sDashboardFileSanitized);
		}

		if ($sDashboardDefinition !== false) {
			$oDashboard = new RuntimeDashboard($sDashBoardId);
			$oDashboard->FromXml($sDashboardDefinition);
			$oDashboard->SetCustomFlag($bCustomized);
			$oDashboard->SetDefinitionFile($sDashboardFileSanitized);
		} else {
			$oDashboard = null;
		}

		return $oDashboard;
	}

	/**
	 * @param string $sDashboardFile file name relative to the current module folder
	 * @param string $sDashBoardId code of the dashboard either menu_id or <class>__<attcode>
	 *
	 * @return null|RuntimeDashboard
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetDashboardToEdit($sDashboardFile, $sDashBoardId)
	{
		$bCustomized = false;

		$sDashboardFileSanitized = utils::RealPath(APPROOT.$sDashboardFile, APPROOT);
		if (false === $sDashboardFileSanitized) {
			throw new SecurityException('Invalid dashboard file !');
		}

		// Search for an eventual user defined dashboard
		$oUDSearch = new DBObjectSearch('UserDashboard');
		$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oUDSearch->AddCondition('menu_code', $sDashBoardId, '=');
		$oUDSet = new DBObjectSet($oUDSearch);
		if ($oUDSet->Count() > 0) {
			// Assuming there is at most one couple {user, menu}!
			$oUserDashboard = $oUDSet->Fetch();
			$sDashboardDefinition = $oUserDashboard->Get('contents');
			$bCustomized = true;
		} else {
			$sDashboardDefinition = @file_get_contents($sDashboardFileSanitized);
		}

		if ($sDashboardDefinition !== false) {
			$oDashboard = new RuntimeDashboard($sDashBoardId);
			$oDashboard->FromXml($sDashboardDefinition);
			$oDashboard->SetCustomFlag($bCustomized);
			$oDashboard->SetDefinitionFile($sDashboardFileSanitized);
		} else {
			$oDashboard = null;
		}

		return $oDashboard;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [], $bCanEdit = true)
	{
		if (!isset($aExtraParams['query_params']) && isset($aExtraParams['this->class'])) {
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aRenderParams = ['query_params' => $oObj->ToArgsForQuery()];
		} else {
			$aRenderParams = $aExtraParams;
		}

		$oDashboard = parent::Render($oPage, $bEditMode, $aRenderParams);

		if (isset($aExtraParams['query_params']['this->object()'])) {
			/** @var \DBObject $oObj */
			$oObj = $aExtraParams['query_params']['this->object()'];
			$aAjaxParams = ['this->class' => get_class($oObj), 'this->id' => $oObj->GetKey()];
			if (isset($aExtraParams['from_dashboard_page'])) {
				$aAjaxParams['from_dashboard_page'] = $aExtraParams['from_dashboard_page'];
			}
		} else {
			$aAjaxParams = $aExtraParams;
		}
		if (!$bEditMode && !$oPage->IsPrintableVersion()) {
			$sId = $this->GetId();
			$sDivId = utils::Sanitize($sId, '', 'element_identifier');
			if ($this->GetAutoReload()) {
				$iReloadInterval = 1000 * $this->GetAutoReloadInterval();

				$oPage->add_script(
					<<<JS
				if (typeof(AutoReloadDashboardId$sDivId) !== 'undefined')
				{
					clearInterval(AutoReloadDashboardId$sDivId);
					delete AutoReloadDashboardId$sDivId;
				}
			
				AutoReloadDashboardId$sDivId = setInterval("ReloadDashboard$sDivId();", $iReloadInterval);

				function ReloadDashboard$sDivId()
				{
					// Do not reload when a dialog box is active
					if (!($('.ui-dialog:visible').length > 0) && $('.ibo-dashboard#$sDivId').is(':visible'))
					{
						updateDashboard$sDivId();
					}
				}
JS
				);
			} else {
				$oPage->add_script(
					<<<EOF
				if (typeof(AutoReloadDashboardId$sDivId) !== 'undefined')
				{
					clearInterval(AutoReloadDashboardId$sDivId);
					delete AutoReloadDashboardId$sDivId;
				}
EOF
				);
			}

			if ($bCanEdit) {
				$this->RenderSelector($oPage, $oDashboard, $aAjaxParams);
				$this->RenderEditionTools($oPage, $oDashboard, $aAjaxParams);
			}
		}
	}

	/**
	 * @param WebPage $oPage
	 * @param \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout $oDashboard
	 * @param bool $bFromDashboardPage
	 * @param array $aAjaxParams
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function RenderSelector(WebPage $oPage, DashboardLayoutUIBlock $oDashboard, $aAjaxParams = [])
	{
		if (!$this->HasCustomDashboard()) {
			return;
		}
		$sId = $this->GetId();
		$sDivId = utils::Sanitize($sId, '', 'element_identifier');
		$sExtraParams = json_encode($aAjaxParams);

		$sSwitchToStandard = Dict::S('UI:Toggle:SwitchToStandardDashboard');
		$sSwitchToCustom = Dict::S('UI:Toggle:SwitchToCustomDashboard');
		$bStandardSelected = appUserPreferences::GetPref('display_original_dashboard_'.$sId, false);

		$sSelectorHtml = '<div id="ibo-dashboard-selector'.$sDivId.'" class="ibo-dashboard--selector" data-tooltip-content="'.($bStandardSelected ? $sSwitchToCustom : $sSwitchToStandard).'">';
		$sSelectorHtml .= '<label class="ibo-dashboard--switch"><input type="checkbox" onchange="ToggleDashboardSelector'.$sDivId.'();" '.($bStandardSelected ? '' : 'checked').'><span class="ibo-dashboard--slider"></span></label></input></label>';
		$sSelectorHtml .= '</div>';

		$sFile = addslashes($this->GetDefinitionFile());
		$sReloadURL = json_encode($this->GetReloadURL());

		$bFromDashboardPage = isset($aAjaxParams['from_dashboard_page']) ? isset($aAjaxParams['from_dashboard_page']) : false;
		if ($bFromDashboardPage) {
			if ($oPage instanceof iTopWebPage) {
				$oToolbar = $oPage->GetTopBarLayout()->GetToolbar();
				$oToolbar->AddHtml($sSelectorHtml);
			}
		} else {
			$oToolbar = $oDashboard->GetToolbar();
			$oToolbar->AddHtml($sSelectorHtml);
		}

		$oPage->add_script(
			<<<JS
			function ToggleDashboardSelector$sDivId()
			{
			    var dashboard = $('.ibo-dashboard#$sDivId')
				dashboard.block();
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
				   { operation: 'toggle_dashboard', dashboard_id: '$sId', file: '$sFile', extra_params: $sExtraParams, reload_url: '$sReloadURL' },
				   function(data) {
					 dashboard.html(data);
					 dashboard.unblock();
					 if ($('#ibo-dashboard-selector$sDivId input').prop("checked")) {
					 	$('#ibo-dashboard-selector$sDivId').attr('data-tooltip-content', '$sSwitchToStandard');
					 } else {
					    $('#ibo-dashboard-selector$sDivId').attr('data-tooltip-content', '$sSwitchToCustom');
					 }
					 CombodoTooltip.InitAllNonInstantiatedTooltips($('#ibo-dashboard-selector$sDivId').parent(), true);
					}
				 );
			}
JS
		);
	}

	/**
	 * @return bool
	 */
	protected function HasCustomDashboard()
	{
		try {
			// Search for an eventual user defined dashboard
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $this->GetId(), '=');
			$oUDSet = new DBObjectSet($oUDSearch);

			return ($oUDSet->Count() > 0);
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \Exception
	 */
	protected function RenderEditionTools(WebPage $oPage, DashboardLayoutUIBlock $oDashboard, $aExtraParams)
	{
		$oPage->LinkScriptFromAppRoot('node_modules/blueimp-file-upload/js/jquery.iframe-transport.js');
		$oPage->LinkScriptFromAppRoot('node_modules/blueimp-file-upload/js/jquery.fileupload.js');
		$sId = utils::Sanitize($this->GetId(), '', 'element_identifier');

		$sMenuTogglerId = "ibo-dashboard-menu-toggler-{$sId}";
		$sActionEditId = "ibo-dashboard-menu-edit-{$sId}";
		$sPopoverMenuId = "ibo-dashboard-menu-popover-{$sId}";
		$sName = 'UI:Dashboard:Actions';

		$bFromDashboardPage = isset($aExtraParams['from_dashboard_page']) ? isset($aExtraParams['from_dashboard_page']) : false;
		if ($bFromDashboardPage) {
			if (!($oPage instanceof iTopWebPage)) {
				// TODO 3.0 change the menu
				return;
			}
			$oToolbar = $oPage->GetTopBarLayout()->GetToolbar();
		} else {
			$oToolbar = $oDashboard->GetToolbar();
		}

		// TODO 3.3 Check if we need different action for custom dashboard creation / edition
		$oActionEditButton = ButtonUIBlockFactory::MakeIconAction(
			'fas fa-pen',
			$this->HasCustomDashboard() ? Dict::S('UI:Dashboard:EditCustom') : Dict::S('UI:Dashboard:CreateCustom'),
			$sActionEditId,
			'',
			false,
			$sActionEditId
		)
			->AddCSSClass('ibo-top-bar--toolbar-dashboard-edit-button')
			->AddCSSClass('ibo-action-button');

		$oToolbar->AddSubBlock($oActionEditButton);

		$oActionButton = ButtonUIBlockFactory::MakeIconAction('fas fa-ellipsis-v', Dict::S($sName), $sName, '', false, $sMenuTogglerId)
			->AddCSSClass('ibo-top-bar--toolbar-dashboard-menu-toggler')
			->AddCSSClass('ibo-action-button');

		$oToolbar->AddSubBlock($oActionButton);
		$aActions = [];
		$sFile = addslashes(utils::LocalPath($this->sDefinitionFile));
		$sJSExtraParams = json_encode($aExtraParams);
		if ($this->HasCustomDashboard()) {
			//			$oEdit = new JSPopupMenuItem('UI:Dashboard:Edit', Dict::S('UI:Dashboard:EditCustom'), "return EditDashboard('{$this->sId}', '$sFile', $sJSExtraParams)");
			//			$aActions[$oEdit->GetUID()] = $oEdit->GetMenuItem();
			$oRevert = new JSPopupMenuItem(
				'UI:Dashboard:RevertConfirm',
				Dict::S('UI:Dashboard:DeleteCustom'),
				"if (confirm('".addslashes(Dict::S('UI:Dashboard:RevertConfirm'))."')) return RevertDashboard('{$this->sId}', $sJSExtraParams); else return false"
			);
			$aActions[$oRevert->GetUID()] = $oRevert->GetMenuItem();
		} else {
			//			$oEdit = new JSPopupMenuItem('UI:Dashboard:Edit', Dict::S('UI:Dashboard:CreateCustom'), "return EditDashboard('{$this->sId}', '$sFile', $sJSExtraParams)");
			//			$aActions[$oEdit->GetUID()] = $oEdit->GetMenuItem();
		}

		utils::GetPopupMenuItems($oPage, iPopupMenuExtension::MENU_DASHBOARD_ACTIONS, $this, $aActions);

		$oActionsMenu = $oPage->GetPopoverMenu($sPopoverMenuId, $aActions)
			->SetTogglerJSSelector("#$sMenuTogglerId")
			->SetContainer(PopoverMenu::ENUM_CONTAINER_BODY);

		$oToolbar->AddSubBlock($oActionButton)
			->AddSubBlock($oActionsMenu);

		$sReloadURL = json_encode($this->GetReloadURL());
		$oPage->add_script(
			<<<EOF
function EditDashboard(sId, sDashboardFile, aExtraParams)
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'dashboard_editor', id: sId, file: sDashboardFile, extra_params: aExtraParams, reload_url: '$sReloadURL'},
		function(data)
		{
			$('body').append(data);
		}
	);
	return false;
}
function RevertDashboard(sId, aExtraParams)
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'revert_dashboard', dashboard_id: sId, extra_params: aExtraParams, reload_url: '$sReloadURL'},
		function(data)
		{
			location.reload();
		}
	);
	return false;
}
EOF
		);
	}

	/**
	 * @inheritDoc
	 */
	public function RenderProperties($oPage, $aExtraParams = [])
	{
		parent::RenderProperties($oPage, $aExtraParams);

		$oPage->add_ready_script(
			<<<EOF
	$('#select_layout input').on('click', function() {
		var sLayoutClass = $(this).val();
		$('.itop-dashboard').runtimedashboard('option', {layout_class: sLayoutClass});
	} );
	$('#row_attr_dashboard_title').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: false, 'do_apply': function() {
			var sTitle = $('#attr_dashboard_title').val();
			$('.itop-dashboard').runtimedashboard('option', {title: sTitle});
			return true;
		}
	});
	$('#row_attr_auto_reload').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: true, 'do_apply': function() {
			var bAutoReload = $('#attr_auto_reload').is(':checked');
			$('.itop-dashboard').runtimedashboard('option', {auto_reload: bAutoReload});
			return true;
		}
	});
	$('#row_attr_auto_reload_sec').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: true, 'do_apply': function() {
			var iAutoReloadSec = $('#attr_auto_reload_sec').val();
			$('.itop-dashboard').runtimedashboard('option', {auto_reload_sec: iAutoReloadSec});
			return true;
		}
	});
EOF
		);
	}

	/**
	 * @param string|null $sOQL
	 *
	 * @return \DesignerForm
	 * @throws \DictExceptionMissingString
	 * @throws \ReflectionException
	 */
	public static function GetDashletCreationForm($sOQL = null)
	{
		/** @var DashletService $oDashletService */
		$oDashletService = MetaModel::GetService('DashletService');
		$oAppContext = new ApplicationContext();
		$sContextMenuId = $oAppContext->GetCurrentValue('menu', null);

		$oForm = new DesignerForm();

		// Get the list of all 'dashboard' menus in which we can insert a dashlet
		$aAllMenus = ApplicationMenu::ReflectionMenuNodes();
		$sRootMenuId = ApplicationMenu::GetRootMenuId($sContextMenuId);
		$aAllowedDashboards = [];
		$sDefaultDashboard = null;

		// Store the parent menus for acces check
		$aParentMenus = [];
		foreach ($aAllMenus as $idx => $aMenu) {
			/** @var MenuNode $oMenu */
			$oMenu = $aMenu['node'];
			if (count(ApplicationMenu::GetChildren($oMenu->GetIndex())) > 0) {
				$aParentMenus[$oMenu->GetMenuId()] = $aMenu;
			}
		}

		foreach ($aAllMenus as $idx => $aMenu) {
			$oMenu = $aMenu['node'];
			if ($oMenu instanceof DashboardMenuNode) {
				// Get the root parent for access check
				$sParentId = $aMenu['parent'];
				$aParentMenu = $aParentMenus[$sParentId];
				while (isset($aParentMenus[$aParentMenu['parent']])) {
					// grand parent exists
					$sParentId = $aParentMenu['parent'];
					$aParentMenu = $aParentMenus[$sParentId];
				}
				/** @var \MenuNode $oParentMenu */
				$oParentMenu = $aParentMenu['node'];
				if ($oMenu->IsEnabled() && $oParentMenu->IsEnabled()) {
					$sMenuLabel = $oMenu->GetTitle();
					$sParentLabel = Dict::S('Menu:'.$sParentId);
					if ($sParentLabel != $sMenuLabel) {
						$aAllowedDashboards[$oMenu->GetMenuId()] = $sParentLabel.' - '.$sMenuLabel;
					} else {
						$aAllowedDashboards[$oMenu->GetMenuId()] = $sMenuLabel;
					}
					if (empty($sDefaultDashboard) && ($sRootMenuId == ApplicationMenu::GetRootMenuId($oMenu->GetMenuId()))) {
						$sDefaultDashboard = $oMenu->GetMenuId();
					}
				}
			}
		}
		asort($aAllowedDashboards);

		$oField = new DesignerComboField('menu_id', Dict::S('UI:DashletCreation:Dashboard'), $sDefaultDashboard);
		$oField->SetAllowedValues($aAllowedDashboards);
		$oField->SetMandatory(true);
		$oForm->AddField($oField);

		// Get the list of possible dashlets that support a creation from
		// an OQL
		$aDashlets = $oDashletService->GetAvailableDashlets('can_create_by_oql');

		$oSelectorField = new DesignerFormSelectorField('dashlet_class', Dict::S('UI:DashletCreation:DashletType'), '');
		$oForm->AddField($oSelectorField);
		foreach ($aDashlets as $sDashletClass => $aDashletInfo) {
			$oSubForm = new DesignerForm();
			$oMetaModel = new ModelReflectionRuntime();
			/** @var \Dashlet $oDashlet */
			$oDashlet = DashletFactory::GetInstance()->CreateDashlet($sDashletClass, 0);
			$oDashlet->GetPropertiesFieldsFromOQL($oSubForm, $sOQL);

			$oSelectorField->AddSubForm($oSubForm, $aDashletInfo['label'], $aDashletInfo['class']);
		}
		$oField = new DesignerBooleanField('open_editor', Dict::S('UI:DashletCreation:EditNow'), true);
		$oForm->AddField($oField);

		return $oForm;
	}

	/**
	 * @param WebPage $oPage
	 * @param $sOQL
	 *
	 * @throws \DictExceptionMissingString
	 * @throws \ReflectionException
	 */
	public static function GetDashletCreationDlgFromOQL($oPage, $sOQL)
	{
		$oPage->add('<div id="dashlet_creation_dlg">');

		$oForm = self::GetDashletCreationForm($sOQL);

		$oForm->Render($oPage);
		$oPage->add('</div>');

		$sDialogTitle = Dict::S('UI:DashletCreation:Title');
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');

		$oPage->add_ready_script(
			<<<JS
$('#dashlet_creation_dlg').dialog({
	width: 600,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sCancelButtonLabel", 
	  click: function() {
		$(this).dialog( "close" ); $(this).remove();
		} ,
		'class': 'ibo-button ibo-is-alternative ibo-is-neutral action cancel'
	},
	{ text: "$sOkButtonLabel", 
	  click: function() {
			var oForm = $(this).find('form');
			var sFormId = oForm.attr('id');
			var oParams = null;
			var aErrors = ValidateForm(sFormId, false);
			if (aErrors.length == 0)
			{
				oParams = ReadFormParams(sFormId);
			}
			oParams.operation = 'add_dashlet';
			var me = $(this);
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
				me.dialog( "close" );
				me.remove();
				$('body').append(data);
			});
		},
		'class': 'ibo-button ibo-is-regular ibo-is-primary action' }
	],
	close: function() { $(this).remove(); }
});
JS
		);
	}

	/**
	 * @return string
	 */
	public function GetDefinitionFile()
	{
		return $this->sDefinitionFile;
	}

	/**
	 * @param string $sDashboardFileRelative can also be an absolute path (compatibility with old URL)
	 *
	 * @return string full path to the Dashboard file
	 * @throws \SecurityException if path isn't under approot
	 * @uses utils::RealPath()
	 * @since 2.7.8 3.0.3 3.1.0 N°4449 remove FPD
	 */
	public static function GetDashboardFileFromRelativePath($sDashboardFileRelative)
	{
		if (utils::RealPath($sDashboardFileRelative, APPROOT)) {
			// compatibility with old URL containing absolute path !
			return $sDashboardFileRelative;
		}

		$sDashboardFile = APPROOT.$sDashboardFileRelative;
		if (false === utils::RealPath($sDashboardFile, APPROOT)) {
			throw new SecurityException('Invalid dashboard file !');
		}

		return $sDashboardFile;
	}

	/**
	 * @param string $sDefinitionFile
	 */
	public function SetDefinitionFile($sDefinitionFile)
	{
		$this->sDefinitionFile = $sDefinitionFile;
	}

	/**
	 * @return string|null
	 */
	public function GetReloadURL()
	{
		return $this->sReloadURL;
	}

	/**
	 * @param string $sReloadURL
	 */
	public function SetReloadURL($sReloadURL)
	{
		$this->sReloadURL = $sReloadURL;
	}

	/**
	 * @inheritDoc
	 */
	protected function PrepareDashletForRendering(Dashlet $oDashlet, $aCoordinates, $aExtraParams = [])
	{
		$sDashletIdOrig = $oDashlet->GetID();
		$sDashboardSanitizedId = $this->GetSanitizedId();
		$sDashletIdNew = static::GetDashletUniqueId($this->GetCustomFlag(), $sDashboardSanitizedId, $aCoordinates[1], $aCoordinates[0], $sDashletIdOrig);
		$oDashlet->SetID($sDashletIdNew);
		$this->UpdateDashletUserPrefs($oDashlet, $sDashletIdOrig, $aExtraParams);
	}

	/**
	 * Migrate dashlet specific prefs to new format
	 *      Before 2.7.0 we were using the same for dashboard menu or dashboard attributes, standard or custom :
	 *          <alias>-<class>|Dashlet<idx_dashlet>
	 *      Since 2.7.0 it is the following, with a "CUSTOM_" prefix if necessary :
	 *          * dashboard menu : <dashboard_id>_IDrow<row_idx>-col<col_idx>-<dashlet_idx>
	 *          * dashboard attribute : <class>__<attcode>_IDrow<row_idx>-col<col_idx>-<dashlet_idx>
	 *
	 * @param \Dashlet $oDashlet
	 * @param string $sDashletIdOrig
	 *
	 * @param array $aExtraParams
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @since 2.7.0 N°2735
	 */
	private function UpdateDashletUserPrefs(Dashlet $oDashlet, $sDashletIdOrig, array $aExtraParams)
	{
		$bIsDashletWithListPref = ($oDashlet instanceof DashletObjectList);
		if (!$bIsDashletWithListPref) {
			return;
		}
		/** @var \DashletObjectList $oDashlet */

		$bDashletIdInNewFormat = ($sDashletIdOrig === $oDashlet->GetID());
		if ($bDashletIdInNewFormat) {
			return;
		}

		$sNewPrefKey = $this->GetDashletObjectListAppUserPreferencesPrefix($oDashlet, $aExtraParams, $oDashlet->GetID());
		$sPrefValueForNewKey = appUserPreferences::GetPref($sNewPrefKey, null);
		$bHasPrefInNewFormat = ($sPrefValueForNewKey !== null);
		if ($bHasPrefInNewFormat) {
			return;
		}

		$sOldPrefKey = $this->GetDashletObjectListAppUserPreferencesPrefix($oDashlet, $aExtraParams, $sDashletIdOrig);
		$sPrefValueForOldKey = appUserPreferences::GetPref($sOldPrefKey, null);
		$bHasPrefInOldFormat = ($sPrefValueForOldKey !== null);
		if (!$bHasPrefInOldFormat) {
			return;
		}

		appUserPreferences::SetPref($sNewPrefKey, $sPrefValueForOldKey);
		appUserPreferences::UnsetPref($sOldPrefKey);
	}

	/**
	 * @param \DashletObjectList $oDashlet
	 * @param array $aExtraParams
	 * @param string $sDashletId
	 *
	 * @return string
	 * @since 2.7.0
	 */
	private function GetDashletObjectListAppUserPreferencesPrefix(DashletObjectList $oDashlet, $aExtraParams, $sDashletId)
	{
		$sDataTableId = Dashlet::APP_USER_PREFERENCES_PREFIX.$sDashletId;
		$aClassAliases = [];
		try {
			$oFilter = $oDashlet->GetDBSearch($aExtraParams);
			$aClassAliases = $oFilter->GetSelectedClasses();
		} catch (Exception $e) {
			//on error, return default value
			return null;
		}

		return DataTableSettings::GetAppUserPreferenceKey($aClassAliases, $sDataTableId);
	}
}

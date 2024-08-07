<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\DownloadPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\NiceWebPage;
use Combodo\iTop\Application\WebPage\PDFPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Controller\AjaxRenderController;
use Combodo\iTop\Controller\Base\Layout\ActivityPanelController;
use Combodo\iTop\Controller\Base\Layout\ObjectController;
use Combodo\iTop\Controller\PreferencesController;
use Combodo\iTop\Controller\WelcomePopupController;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleFormRenderer;
use Combodo\iTop\Service\Router\Router;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectManager;

require_once('../approot.inc.php');

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/user.preferences.class.inc.php');

	IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);
	$oKPI = new ExecutionKPI();
	$oKPI->ComputeAndReport('Data model loaded');

	$oKPI = new ExecutionKPI();

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	$sRoute = utils::ReadParam('route', '', false, utils::ENUM_SANITIZATION_FILTER_ROUTE);
	$operation = utils::ReadParam('operation', '', false, utils::ENUM_SANITIZATION_FILTER_OPERATION);

	// Only allow export functions to portal users
	switch ($operation) {
		case 'export_build_portal':
		case 'export_cancel':
		case 'export_download':
		case 'cke_img_upload':
		case 'cke_upload_and_browse':
		case 'cke_browse':
			$sRequestedPortalId = null; // Allowed for all users
			break;

		default:
			$sRequestedPortalId = 'backoffice'; // Allowed only for console users
			break;
	}
	LoginWebPage::DoLoginEx($sRequestedPortalId, false);

	// check if header contains X-Combodo-Ajax for POST request (CSRF protection for ajax calls)
	// check must be performed after DoLoginEx to be logged in and to be able to check the token (based on the transaction id)
	if (!isset($_SERVER['HTTP_X_COMBODO_AJAX']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
		$sTransactionId = utils::ReadPostedParam("transaction_id");
		if (!utils::IsTransactionValid($sTransactionId, false)) { // if a form is submitted without header but contains a token... should be exceptional
			$sReferer = $_SERVER['HTTP_REFERER'];
			$sErrorMsg = 'Unauthorized access. Please see https://www.itophub.io/wiki/page?id=3_2_0:release:developer#checking_for_the_presence_of_specific_header_in_the_post_to_enhance_protection_against_csrf_attacks';
			IssueLog::Error("Unprotected ajax call : $sErrorMsg", LogChannels::SECURITY, ['referer' => $sReferer]);
			header('HTTP/1.1 401 Unauthorized');
			die($sErrorMsg);
		}
	}

	$oKPI->ComputeAndReport('User login');

	// N°2780 Fix ContextTag for console
	// Some operations are also used in the portal though
	switch ($operation) {
		case 'export_build_portal':
		case 'export_download':
			// Do nothing: used in portal (export.js in portal-base)
			break;

		default:
			$oTag = new ContextTag(ContextTag::TAG_CONSOLE);
	}

	// First check if we can redirect the route to a dedicated controller
	$sRoute = utils::ReadParam('route', '', false, utils::ENUM_SANITIZATION_FILTER_ROUTE);
	$oRouter = Router::GetInstance();
	if ($operation === '' && $oRouter->CanDispatchRoute($sRoute)) {
		$mResponse = $oRouter->DispatchRoute($sRoute);

		// If response isn't a WebPage, it is most likely that the output already occured, stop the script.
		// Note that this is done here and not directly in the Router::DispatchRoute() so custom endpoint can handle null responses their own way.
		if (false === ($mResponse instanceof WebPage)) {
			die();
		}

		// Response is a WebPage, let's handle it like legacy operations
		$oPage = $mResponse;
	}
	// Otherwise, use legacy operation
	else {
		// Default for most operations, but can be switch to a JsonPage, DownloadPage or else if the operation requires it
		$oPage = new AjaxPage("");

		$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
		$sEncoding = utils::ReadParam('encoding', 'serialize');
		$sClass = utils::ReadParam('class', 'MissingAjaxParam', false, 'class');
		$sStyle = utils::ReadParam('style', 'list');


		$oAjaxRenderController = new AjaxRenderController();

		switch ($operation) {
			case 'search_and_refresh':
				$oPage = new JsonPage();
				// Feeds dataTables directly
				$oPage->SetOutputDataOnly(true);
				$aResult = AjaxRenderController::SearchAndRefresh($sFilter);
				$oPage->SetData($aResult);
				break;

			case 'search':
				$oPage = new JsonPage();
				// Feeds dataTables directly
				$oPage->SetOutputDataOnly(true);
				$aResult = AjaxRenderController::Search($sEncoding, $sFilter);
				$oPage->SetData($aResult);
				break;

			case 'refreshDashletCount':
				$oPage->SetContentType('application/json');
				$aResult = AjaxRenderController::RefreshDashletCount($sFilter);
				$oPage->add(json_encode($aResult));
				break;

			case 'refreshDashletList':
				$oPage->SetContentType('application/json');
				$aResult = AjaxRenderController::RefreshDashletList($sStyle, $sFilter);
				$oPage->add(json_encode($aResult));
				break;

			case 'refreshDashletSummary':
				$oPage->SetContentType('text/html');
				$sExtraParams = utils::ReadParam('extra_params', '', false, 'raw_data');
				$aExtraParams = json_decode($sExtraParams, true);
				$aQueryParams = [];
				if (isset($aExtraParams['query_params'])) {
					$aQueryParams = $aExtraParams['query_params'];
				}
				$oFilter = DBObjectSearch::FromOQL($sFilter, $aQueryParams);
				$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
				$oSet = new CMDBObjectSet($oFilter, [], $aExtraParams);
				$oBlock = new displayblock($oFilter, 'summary', false, [], $oSet);
				$oBlock->RenderContent($oPage, $aExtraParams);
				break;

			case 'datatable_save_settings':
				$oPage->SetContentType('text/plain');
				$bRet = AjaxRenderController::DatatableSaveSettings();
				$oPage->add($bRet ? 'Ok' : 'KO');
				break;

			case 'datatable_reset_settings':
				$oPage->SetContentType('text/plain');
				$bRet = AjaxRenderController::DatatableResetSettings();
				$oPage->add($bRet ? 'Ok' : 'KO');
				break;

			// ui.searchformforeignkeys
			case 'ShowModalSearchForeignKeys':
				$oPage->SetContentType('text/html');
				$iInputId = utils::ReadParam('iInputId', '');
				$sTitle = utils::ReadParam('sTitle', '', false, 'raw_data');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$oWidget = new UISearchFormForeignKeys($sTargetClass, $iInputId);
				$oWidget->ShowModalSearchForeignKeys($oPage, $sTitle);
				break;

			// ui.searchformforeignkeys
			case 'GetFullListForeignKeysFromSelection':
				$oPage->SetContentType('application/json');
				$oWidget = new UISearchFormForeignKeys($sClass);
				$oFullSetFilter = new DBObjectSearch($sClass);
				$oWidget->GetFullListForeignKeysFromSelection($oPage, $oFullSetFilter);
				break;

			// ui.searchformforeignkeys
			case 'ListResultsSearchForeignKeys':
				$oPage->SetContentType('text/html');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
				$oWidget = new UISearchFormForeignKeys($sTargetClass, $iInputId);
				$oWidget->ListResultsSearchForeignKeys($oPage, $sRemoteClass);
				break;

			// ui.linkswidget
			case 'addObjects':
				$oPage->SetContentType('text/html');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$sSuffix = utils::ReadParam('sSuffix', '');
				$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				if (!empty($sJson)) {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				} else {
					// Search form: no current object
					$oObj = null;
				}
				$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
				$oAppContext = new ApplicationContext();
				$aPrefillFormParam = array(
					'user'       => Session::Get("auth_user"),
					'context'    => $oAppContext->GetAsHash(),
					'att_code'   => $sAttCode,
					'origin'     => 'console',
					'source_obj' => $oObj
				);
				$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
				/** @var \DBObject $oObj */
				$oWidget->GetObjectPickerDialog($oPage, $oObj, $sJson, $aAlreadyLinked, $aPrefillFormParam);
				break;

			// ui.linkswidget
			case 'searchObjectsToAdd':
				$oPage->SetContentType('text/html');
				$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$sSuffix = utils::ReadParam('sSuffix', '');
				$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
				$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
				$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
				$oWidget->SearchObjectsToAdd($oPage, $sRemoteClass, $aAlreadyLinked);
				break;

			//ui.linksdirectwidget
			case 'createObject':
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('class', '', false, 'class');
				$sRealClass = utils::ReadParam('real_class', '', false, 'class');
				$sAttCode = utils::ReadParam('att_code', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$oPage->SetContentType('text/html');
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				if (!empty($sJson)) {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				}
				$oObj = $oWizardHelper->GetTargetObject();
				$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
				$oWidget->GetObjectCreationDlg($oPage, $sRealClass, $oObj);
				break;

			// ui.linksdirectwidget
			case 'getLinksetRow':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);
				$sClass = utils::ReadParam('class', '', false, 'class');
				$sRealClass = utils::ReadParam('real_class', '', false, 'class');
				$sAttCode = utils::ReadParam('att_code', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$iTempId = utils::ReadParam('tempId', '');
				$aValues = utils::ReadParam('values', array(), false, 'raw_data');
				$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
				$oPage->SetData($oWidget->GetFormRow($oPage, $sRealClass, $aValues, -$iTempId));
				break;

			// ui.linksdirectwidget
			case 'selectObjectsToAdd':
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('class', '', false, 'class');
				$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				/** @var \DBObject $oObj */
				$oObj = null;
				if ($sJson != '') {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				}
				$sRealClass = utils::ReadParam('real_class', '', false, 'class');
				$sAttCode = utils::ReadParam('att_code', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$iCurrObjectId = utils::ReadParam('iObjId', 0);
				$oPage->SetContentType('text/html');
				$oAppContext = new ApplicationContext();
				$aPrefillFormParam = array(
					'user'       => Session::Get('auth_user'),
					'context'    => $oAppContext->GetAsHash(),
					'att_code'   => $sAttCode,
					'origin'     => 'console',
					'source_obj' => $oObj,
				);
				$aPrefillFormParam['dest_class'] = ($oObj === null ? '' : $oObj->Get($sAttCode)->GetClass());
				$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
				$oWidget->GetObjectsSelectionDlg($oPage, $oObj, $aAlreadyLinked, $aPrefillFormParam);
				break;

			// ui.linksdirectwidget
			case 'searchObjectsToAdd2':
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('class', '', false, 'class');
				$sRealClass = utils::ReadParam('real_class', '', false, 'class');
				$sAttCode = utils::ReadParam('att_code', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				$oObj = null;
				if ($sJson != '') {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				}
				$oAppContext = new ApplicationContext();
				$aPrefillFormParam = array(
					'user'       => Session::Get('auth_user'),
					'context'    => $oAppContext->GetAsHash(),
					'att_code'   => $sAttCode,
					'origin'     => 'console',
					'source_obj' => $oObj,
				);
				$aPrefillFormParam['dest_class'] = ($oObj === null ? '' : $oObj->Get($sAttCode)->GetClass());
				$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
				$oWidget->SearchObjectsToAdd($oPage, $sRealClass, $aAlreadyLinked, $oObj, $aPrefillFormParam);
				break;

			// ui.linksdirectwidget
			case 'doAddObjects2':
				$oPage->SetContentType('text/html');
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('class', '', false, 'class');
				$sRealClass = utils::ReadParam('real_class', '', false, 'class');
				$sAttCode = utils::ReadParam('att_code', '');
				$iInputId = utils::ReadParam('iInputId', '');
				$iCurrObjectId = utils::ReadParam('iObjId', 0);
				$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
				if ($sFilter != '') {
					$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
				} else {
					$oLinksetDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$valuesDef = $oLinksetDef->GetValuesDef();
					if ($valuesDef === null) {
						$oFullSetFilter = new DBObjectSearch($oLinksetDef->GetLinkedClass());
					} else {
						if (!$valuesDef instanceof ValueSetObjects) {
							throw new Exception('Error: only ValueSetObjects are supported for "allowed_values" in AttributeLinkedSet ('.$this->sClass.'/'.$this->sAttCode.').');
						}
						$oFullSetFilter = DBObjectSearch::FromOQL($valuesDef->GetFilterExpression());
					}
				}
				$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iInputId);
				$oFullSetFilter->SetShowObsoleteData(utils::ShowObsoleteData());
				$oWidget->DoAddObjects($oPage, $oFullSetFilter);
				break;

			////////////////////////////////////////////////////////////

			// ui.extkeywidget
			case 'searchObjectsToSelect':
				$oPage->SetContentType('text/html');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
				$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
				if (!empty($sJson)) {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				} else {
					// Search form: no current object
					$oObj = null;
				}
				$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode);
				$oWidget->SearchObjectsToSelect($oPage, $sFilter, $sRemoteClass, $oObj);
				break;

			// ui.extkeywidget: autocomplete
			case 'ac_extkey':
				$oPage->SetContentType('text/plain');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				$sContains = utils::ReadParam('q', '', false, 'raw_data');
				$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
				$sOutputFormat = utils::ReadParam('sOutputFormat', UIExtKeyWidget::ENUM_OUTPUT_FORMAT_CSV, false, 'raw_data');
				if ($sContains != '') {
					if (!empty($sJson)) {
						$oWizardHelper = WizardHelper::FromJSON($sJson);
						$oObj = $oWizardHelper->GetTargetObject();
					} else {
						// Search form: no current object
						$oObj = null;
					}
					$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, '', $bSearchMode);
					$oWidget->AutoComplete($oPage, $sFilter, $oObj, $sContains, $sOutputFormat);
				}
				break;

			// ui.extkeywidget
			case 'objectSearchForm':
				$oPage->SetContentType('text/html');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$sFilter = utils::ReadParam('sFilter', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
				$iInputId = utils::ReadParam('iInputId', '');
				$sTitle = utils::ReadParam('sTitle', '', false, 'raw_data');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
				$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, $bSearchMode, $sFilter);
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				if (!empty($sJson)) {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				} else {
					// Search form: no current object
					$oObj = null;
				}
				$oWidget->GetSearchDialog($oPage, $sTitle, $oObj);
				break;

			// ui.extkeywidget
			case 'objectCreationForm':
				$oPage->SetContentType('text/html');
				// Retrieving parameters
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
	            $bTargetClassSelected = utils::ReadParam('bTargetClassSelected', '', false, 'raw_data');
	            // Building form, if target class has child classes we ask the user for the desired leaf class, unless we've already done just that
				$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, false);
	            if(!$bTargetClassSelected && MetaModel::HasChildrenClasses($sTargetClass)){
					$oWidget->GetClassSelectionForm($oPage);
				} else {
					$aPrefillFormParam = array();
					if (!empty($sJson)) {
						$oWizardHelper = WizardHelper::FromJSON($sJson);
						$oObj = $oWizardHelper->GetTargetObject();
						$oAppContext = new ApplicationContext();
						$aPrefillFormParam = array(
							'user'       => Session::Get('auth_user'),
							'context'    => $oAppContext->GetAsHash(),
							'att_code'   => $sAttCode,
							'source_obj' => $oObj,
							'origin'     => 'console'
						);
					} else {
						// Search form: no current object
						$oObj = null;
					}
					$oWidget->GetObjectCreationForm($oPage, $oObj, $aPrefillFormParam);
				}
				break;

			// ui.extkeywidget
			case 'doCreateObject':
				$oPage->SetContentType('application/json');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$sFormPrefix = utils::ReadParam('sFormPrefix', '');
				$sAttCode = utils::ReadParam('sAttCode', '');
				$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, $sAttCode, false);
				$aResult = $oWidget->DoCreateObject($oPage);
				echo json_encode($aResult);
				break;

			// ui.extkeywidget
			case 'getObjectName':
				$oPage->SetContentType('application/json');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$iInputId = utils::ReadParam('iInputId', '');
				$iObjectId = utils::ReadParam('iObjectId', '');
				$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
				$sFormAttCode = utils::ReadParam('sFormAttCode', null);
				$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId, '', $bSearchMode);
				$sName = $oWidget->GetObjectName($iObjectId, $sFormAttCode);
				echo json_encode(array('name' => $sName));
				break;

			// ui.extkeywidget
			case 'displayHierarchy':
				$oPage->SetContentType('text/html');
				$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
				$sInputId = utils::ReadParam('sInputId', '');
				$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
				$sJson = utils::ReadParam('json', '', false, 'raw_data');
				$currValue = utils::ReadParam('value', '');
				$bSearchMode = (utils::ReadParam('bSearchMode', 'false') == 'true');
				if (!empty($sJson)) {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();
				} else {
					// Search form: no current object
					$oObj = null;
				}
				$oWidget = new UIExtKeyWidget($sTargetClass, $sInputId, '', $bSearchMode);
				$oWidget->DisplayHierarchy($oPage, $sFilter, $currValue, $oObj);
				break;

			////////////////////////////////////////////////////

			// ui.linkswidget
			case 'doAddObjects':
				$oPage->SetContentType('text/html');
				AjaxRenderController::DoAddObjects($oPage, $sClass, $sFilter);
				break;

			// ui.linkswidget
			case 'doAddIndirectLinks':
				$oPage = new JsonPage();
				AjaxRenderController::DoAddIndirectLinks($oPage, $sClass, $sFilter);
				break;
			////////////////////////////////////////////////////////////
			/// WizardHelper : see the corresponding PHP class, and JS class

			case 'wizard_helper_preview':
				$oPage->SetContentType('text/html');
				$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
				$oWizardHelper = WizardHelper::FromJSON($sJson);
				$oObj = $oWizardHelper->GetTargetObject();
				$oObj->DisplayBareProperties($oPage);
				break;

			case 'wizard_helper':
				$oPage->SetContentType('text/html');
				$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
				$oWizardHelper = WizardHelper::FromJSON($sJson);
				/** @var \DBObject $oObj */
				$oObj = $oWizardHelper->GetTargetObject();
				$sClass = $oWizardHelper->GetTargetClass();
				foreach ($oWizardHelper->GetFieldsForDefaultValue() as $sAttCode) {
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$defaultValue = $oAttDef->GetDefaultValue($oObj);
					$oWizardHelper->SetDefaultValue($sAttCode, $defaultValue);
					$oObj->Set($sAttCode, $defaultValue);
				}
				$sFormPrefix = $oWizardHelper->GetFormPrefix();
				$aExpectedAttributes = ($oWizardHelper->GetStimulus() === null) ? array() : $oObj->GetTransitionAttributes($oWizardHelper->GetStimulus(), $oWizardHelper->GetInitialState());
				foreach ($oWizardHelper->GetFieldsForAllowedValues() as $sAttCode) {
					$sId = $oWizardHelper->GetIdForField($sAttCode);
					if ($sId != '') {
						if (array_key_exists($sAttCode, $aExpectedAttributes)) {
							$iFlags = $aExpectedAttributes[$sAttCode];
						} elseif ($oObj->IsNew()) {
							$iFlags = $oObj->GetInitialStateAttributeFlags($sAttCode);
						} else {
							$iFlags = $oObj->GetAttributeFlags($sAttCode);
						}
						if ($iFlags & OPT_ATT_READONLY) {
							$sHTMLValue = "<span id=\"field_{$sId}\">".$oObj->GetAsHTML($sAttCode);
							$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
						} else {
							// It may happen that the field we'd like to update does not
							// exist in the form. For example, if the field should be hidden/read-only
							// in the current state of the object
							$value = $oObj->Get($sAttCode);
							$displayValue = $oObj->GetEditValue($sAttCode);
							$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
							if (!$oAttDef->IsWritable() || ($oWizardHelper->GetReturnNotEditableFields())) {
								// Even non-writable fields (like AttributeExternal) can be refreshed
								$sHTMLValue = "<div id=\"field_{$sId}\" class=\"field_value_container\"><div class=\"attribute-edit\" data-attcode=\"$sAttCode\">".$oObj->GetAsHTML($sAttCode)."</div></div>";
							} else {
								$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value,
									$displayValue, $sId, '', $iFlags, array('this' => $oObj, 'formPrefix' => $sFormPrefix), false);
								// Make sure that we immediately validate the field when we reload it
								$oPage->add_ready_script("$('#$sId').trigger('validate');");
							}
							$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
						}
					}
				}
			$oWizardHelper->AddJsForUpdateFields($oPage);
				break;

			case 'obj_creation_form':
				$oPage->SetContentType('text/html');
				$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
				$oWizardHelper = WizardHelper::FromJSON($sJson);
				$oObj = $oWizardHelper->GetTargetObject();
				$sClass = $oWizardHelper->GetTargetClass();
				$sTargetState = utils::ReadParam('target_state', '');
				$iTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
				$oObj->Set(MetaModel::GetStateAttributeCode($sClass), $sTargetState);
				cmdbAbstractObject::DisplayCreationForm($oPage, $sClass, $oObj, array(), array('action' => utils::GetAbsoluteUrlAppRoot().'pages/UI.php', 'transaction_id' => $iTransactionId));
				break;

			// DisplayBlock
			case 'ajax':
				$oPage->SetContentType('text/html');
				if ($sFilter != "") {
					$sExtraParams = stripslashes(utils::ReadParam('extra_params', '', false, 'raw_data'));
					$aExtraParams = array();
					if (!empty($sExtraParams)) {
						$aExtraParams = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
					}
					// Restore the app context from the ExtraParams
					$oAppContext = new ApplicationContext(false); // false => don't read the context yet !
					$aContext = array();
					foreach ($oAppContext->GetNames() as $sName) {
						$sParamName = 'c['.$sName.']';
						if (isset($aExtraParams[$sParamName])) {
							$aContext[$sName] = $aExtraParams[$sParamName];
						}
					}
					$_REQUEST['c'] = $aContext;
					if ($sEncoding == 'oql') {
						$oFilter = DBSearch::FromOQL($sFilter);
					} else {
						try {
							$oFilter = DBSearch::unserialize($sFilter);
						}
						catch (CoreException $e) {
							$sFilter = utils::HtmlEntities($sFilter);
							$oPage->p("Invalid query (invalid filter) : <code>$sFilter</code>");
							IssueLog::Error("ajax.render operation='ajax', invalid DBSearch filter param : $sFilter");
							break;
						}
					}
					$oDisplayBlock = new DisplayBlock($oFilter, $sStyle, false);
					$aExtraParams['display_limit'] = true;
					$oDisplayBlock->RenderContent($oPage, $aExtraParams);
				} else {
					$oPage->p("Invalid query (empty filter).");
				}
				break;

			case 'displayCSVHistory':
				$oPage->SetContentType('text/html');
				$bShowAll = (utils::ReadParam('showall', 'false') == 'true');
				BulkChange::DisplayImportHistory($oPage, true, $bShowAll);
				break;

			case 'details':
				$oPage->SetContentType('text/html');
				$key = utils::ReadParam('id', 0);
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition('id', $key, '=');
				$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
				$oDisplayBlock->RenderContent($oPage);
				break;

			case 'pie_chart':
				$oPage->SetContentType('application/json');
				$sGroupBy = utils::ReadParam('group_by', '');
				if ($sFilter != '') {
					if ($sEncoding == 'oql') {
						$oFilter = DBSearch::FromOQL($sFilter);
					} else {
						$oFilter = DBSearch::unserialize($sFilter);
					}
					$oDisplayBlock = new DisplayBlock($oFilter, 'pie_chart_ajax', false);
					$oDisplayBlock->RenderContent($oPage, array('group_by' => $sGroupBy));
				} else {

					$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
				}
				break;

			case 'chart':
				$iRefresh = utils::ReadParam('refresh', '-1', false, 'int');
				if ($iRefresh != -1) {
					$oPage->SetContentType('application/json');
					$aParams = utils::ReadParam('params', array(), false, 'raw_data');
					if ($sFilter != '') {
						$oFilter = DBObjectSearch::FromOQL($sFilter);
						$oDisplayBlock = new DisplayBlock($oFilter, 'chart_ajax', false);
						$oBlock = $oDisplayBlock->GetRenderContent($oPage, $aParams, $aParams['currentId']);
						$sChartType = isset($aParams['chart_type']) ? $aParams['chart_type'] : 'pie';
						switch ($sChartType) {
							case 'bars':
								$aResult['type'] = 'bars';
								//$aResult['JSNames'] = str_replace('"','\'',$oBlock->sJSNames);
								$aResult['Json'] = str_replace('"', '\'', $oBlock->sJson);
								$aResult['JSURLs'] = str_replace('"', '\'', $oBlock->sJSURLs);
								$aResult['js'] = 'charts['.$iRefresh.'].load({json: '.str_replace('"', '\'', $oBlock->sJson).
									',keys: { x: \'label\', value:  [\'value\']'.
									'},onclick: function (d) {  var aURLs = $.parseJSON('.str_replace('"', '\'', $oBlock->sJSURLs).'); window.location.href= aURLs[d.index]; }})';
								break;

							case 'pie':
								$aResult['type'] = 'pie';
								$aResult['JSColumns'] = str_replace('"', '\'', $oBlock->sJSColumns);
								$aResult['JSNames'] = str_replace('"', '\'', $oBlock->sJSNames);
								//$aResult['JSNames'] = json_decode($oBlock->sJSNames);
								$aResult['JSURLs'] = str_replace('"', '\'', $oBlock->sJSURLs);
								$aResult['js'] = 'charts['.$iRefresh.'].load({columns: '.str_replace('"', '\'', $oBlock->sJSColumns).
									',names: '.str_replace('"', '\'', $oBlock->sJSNames).
									',onclick: function (d) {  var aURLs = $.parseJSON('.str_replace('"', '\'', $oBlock->sJSURLs).'); window.location.href= aURLs[d.index]; }})';
								break;
						}
					} else {
						$aResult = [];
					}

					$oPage->add(json_encode($aResult));
				} else {
					// Workaround for IE8 + IIS + HTTPS
					// See TRAC #363, fix described here: http://forums.codecharge.com/posts.php?post_id=97771
					$oPage->add_header("Cache-Control: cache, must-revalidate");
					$oPage->add_header("Pragma: public");
					$oPage->add_header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");

					$aParams = utils::ReadParam('params', array(), false, 'raw_data');
					if ($sFilter != '') {
						$oFilter = DBSearch::unserialize($sFilter);
						$oDisplayBlock = new DisplayBlock($oFilter, 'chart_ajax', false);
						$oDisplayBlock->RenderContent($oPage, $aParams);
					} else {

						$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
					}
				}
				break;

			case 'modal_details':
				$oPage->SetContentType('text/html');
				$key = utils::ReadParam('id', 0);
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition('id', $key, '=');
				$oPage->Add("<p style=\"width:100%; margin-top:-5px;padding:3px; background-color:#33f; color:#fff;\">Object Details</p>\n");
				$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
				$oDisplayBlock->RenderContent($oPage);
				$oPage->Add("<input type=\"button\" class=\"jqmClose\" value=\" Close \" />\n");
				break;

			case 'link':
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('sclass', 'logInfra', false, 'class');
				$sAttCode = utils::ReadParam('attCode', 'name');
				//$sOrg = utils::ReadParam('org_id', '');
				$sName = utils::ReadParam('q', '');
				$iMaxCount = utils::ReadParam('max', 30);
				$iCount = 0;
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition($sAttCode, $sName, 'Begins with');
				//$oFilter->AddCondition('org_id', $sOrg, '=');
				$oSet = new CMDBObjectSet($oFilter, array($sAttCode => true));
				while (($iCount < $iMaxCount) && ($oObj = $oSet->fetch())) {
					$oPage->add($oObj->GetAsHTML($sAttCode)."|".$oObj->GetKey()."\n");
					$iCount++;
				}
				break;

			case 'combo_options':
				$oPage->SetContentType('text/html');
				$oFilter = DBSearch::FromOQL($sFilter);
				$oSet = new CMDBObjectSet($oFilter);
				while ($oObj = $oSet->fetch()) {
					$oPage->add('<option title="Here is more information..." value="'.$oObj->GetKey().'">'.$oObj->GetName().'</option>');
				}
				break;

			case 'display_document':
				$id = utils::ReadParam('id', '');
				$sField = utils::ReadParam('field', '');
				if (!empty($sClass) && ($sClass != 'InlineImage') && !empty($id) && !empty($sField)) {
					$oPage = new DownloadPage('');
					// X-Frame http header : set in page constructor, but we need to allow frame integration for this specific page
					// so we're resetting its value ! (see N°3416)
					$oPage->add_xframe_options('');
					$iCacheSec = (int)utils::ReadParam('cache', 0);
					$oPage->set_cache($iCacheSec);

					// N°4129 - Prevent XSS attacks & other script executions
					if (utils::GetConfig()->Get('security.disable_inline_documents_sandbox') === false) {
						$oPage->add_header('Content-Security-Policy: sandbox;');
					}

					ormDocument::DownloadDocument($oPage, $sClass, $id, $sField, 'inline');
				}
				break;

			case 'search_form':
				$oPage->SetContentType('text/html');
				$sClass = utils::ReadParam('className', '', false, 'class');
				$sRootClass = utils::ReadParam('baseClass', '', false, 'class');
				$currentId = utils::ReadParam('currentId', '');
				$sTableId = utils::ReadParam('_table_id_', null, false, 'raw_data');
				$sAction = utils::ReadParam('action', '');
				$sSelectionMode = utils::ReadParam('selection_mode', null, false, 'raw_data');
				$sResultListOuterSelector = utils::ReadParam('result_list_outer_selector', null, false, 'raw_data');
				$scssCount = utils::ReadParam('css_count', null, false, 'raw_data');
				$sTableInnerId = utils::ReadParam('table_inner_id', $sTableId, false, 'raw_data');

				$oFilter = new DBObjectSearch($sClass);
				$oSet = new CMDBObjectSet($oFilter);
				$sHtml = cmdbAbstractObject::GetSearchForm($oPage, $oSet, array(
					'currentId'                  => $currentId,
					'baseClass'                  => $sRootClass,
					'action'                     => $sAction,
					'table_id'                   => $sTableId,
					'selection_mode'             => $sSelectionMode,
					'result_list_outer_selector' => $sResultListOuterSelector,
					'cssCount'                   => $scssCount,
					'table_inner_id'             => $sTableInnerId
				));
				$oPage->add($sHtml);
				break;

			case 'set_pref':
				$sCode = utils::ReadPostedParam('code', '', 'raw_data');
				$sValue = utils::ReadPostedParam('value', '', 'raw_data');
				appUserPreferences::SetPref($sCode, $sValue);
				break;

			case 'erase_all_pref':
				// Can be useful in case a user got some corrupted prefs...
				appUserPreferences::ClearPreferences();
				break;

			case 'on_form_cancel':
				// Called when a creation/modification form is cancelled by the end-user
				// Let's take this opportunity to inform the plug-ins so that they can perform some cleanup
				$iTransactionId = utils::ReadParam('transaction_id', 0, false, 'transaction_id');
				$sTempId = utils::GetUploadTempId($iTransactionId);
				InlineImage::OnFormCancel($sTempId);
				/** @var \iApplicationUIExtension $oExtensionInstance */
				foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance) {
					$oExtensionInstance->OnFormCancel($sTempId);
				}
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');
				$sToken = utils::ReadParam('token', 0, false, 'raw_data');
				if (($sObjClass != '') && ($iObjKey != 0) && ($sToken != '')) {
					$bReleaseLock = iTopOwnershipLock::ReleaseLock($sObjClass, $iObjKey, $sToken);
				}

				// Invalidate temporary objects
				TemporaryObjectManager::GetInstance()->CancelAllTemporaryObjects($iTransactionId);

				IssueLog::Trace('on_form_cancel', $sObjClass, array(
					'$iObjKey'        => $iObjKey,
					'$sTransactionId' => $iTransactionId,
					'$sTempId'        => $sTempId,
					'$sToken'         => $sToken,
					'$sUser'          => UserRights::GetUser(),
					'HTTP_REFERER'    => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI'     => @$_SERVER['REQUEST_URI'],
				));

				break;

			case 'dashboard':
				$oPage->SetContentType('text/html');
				$id = (int)utils::ReadParam('id', 0);
				$sAttCode = utils::ReadParam('attcode', '');
				/** @var \cmdbAbstractObject $oObj */
				$oObj = MetaModel::GetObject($sClass, $id);
				// - Add graphs dependencies
				WebResourcesHelper::EnableC3JSToWebPage($oPage);
				$oObj->DisplayDashboard($oPage, $sAttCode);
				break;

			case 'export_dashboard':
				$oPage = new DownloadPage('');
				$sDashboardId = utils::ReadParam('id', '', false, 'raw_data');
				$sDashboardFileRelative = utils::ReadParam('file', '', false, 'raw_data');

				$sDashboardFile = RuntimeDashboard::GetDashboardFileFromRelativePath($sDashboardFileRelative);

				$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFile, $sDashboardId);
				if (!is_null($oDashboard)) {
					$oPage->TrashUnexpectedOutput();
					$oPage->SetContentType('text/xml');
					$oPage->SetContentDisposition('attachment', 'dashboard_'.$oDashboard->GetTitle().'.xml');
					$oPage->add($oDashboard->ToXml());
				}
				break;

			case 'import_dashboard':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);

				$sTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
				if (!utils::IsTransactionValid($sTransactionId, true)) {
					throw new SecurityException('ajax.render.php import_dashboard : invalid transaction_id');
				}
				$sDashboardId = utils::ReadParam('id', '', false, 'raw_data');
				$sDashboardFileRelative = utils::ReadParam('file', '', false, 'raw_data');

				$sDashboardFile = RuntimeDashboard::GetDashboardFileFromRelativePath($sDashboardFileRelative);

				$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFile, $sDashboardId);
				$aResult = array('error' => '');
				if (!is_null($oDashboard)) {
					try {
						$oDoc = utils::ReadPostedDocument('dashboard_upload_file');
						$oDashboard->FromXml($oDoc->GetData());
						$oDashboard->Save();
					}
					catch (DOMException $e) {
						$aResult = array('error' => Dict::S('UI:Error:InvalidDashboardFile'));
					}
					catch (Exception $e) {
						$aResult = array('error' => $e->getMessage());
					}
				} else {
					$aResult['error'] = 'Dashboard id="'.$sDashboardId.'" not found.';
				}
				$oPage->SetData($aResult);
				break;

			case 'toggle_dashboard':
				$oPage->SetContentType('text/html');
				$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');

				$bStandardSelected = appUserPreferences::GetPref('display_original_dashboard_'.$sDashboardId, false);
				appUserPreferences::UnsetPref('display_original_dashboard_'.$sDashboardId);
				appUserPreferences::SetPref('display_original_dashboard_'.$sDashboardId, !$bStandardSelected);

				$aExtraParams = utils::ReadParam('extra_params', array(), false, 'raw_data');
				$sDashboardFile = utils::ReadParam('file', '', false, 'raw_data');
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFile, $sDashboardId);
				$aResult = array('error' => '');
				if (!is_null($oDashboard)) {
					if (!empty($sReloadURL)) {
						$oDashboard->SetReloadURL($sReloadURL);
					}
					$oDashboard->Render($oPage, false, $aExtraParams);
				}
				break;

			case 'reload_dashboard':
				$oPage->SetContentType('text/html');
				$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
				$aExtraParams = utils::ReadParam('extra_params', array(), false, 'raw_data');
				$sDashboardFile = utils::ReadParam('file', '', false, 'raw_data');
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFile, $sDashboardId);
				$aResult = array('error' => '');
				if (!is_null($oDashboard)) {
					if (!empty($sReloadURL)) {
						$oDashboard->SetReloadURL($sReloadURL);
					}
					$oDashboard->Render($oPage, false, $aExtraParams);
				}
				break;

			case 'save_dashboard':
				$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'context_param');

				$aExtraParams = utils::ReadParam('extra_params', array(), false, 'raw_data');
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				appUserPreferences::SetPref('display_original_dashboard_'.$sDashboardId, false);
				$sJSExtraParams = json_encode($aExtraParams);
				$aParams = array();
				$aParams['layout_class'] = utils::ReadParam('layout_class', '');
				$aParams['title'] = utils::ReadParam('title', '', false, 'raw_data');
				$aParams['auto_reload'] = utils::ReadParam('auto_reload', false);
				$aParams['auto_reload_sec'] = utils::ReadParam('auto_reload_sec', 300);
				$aParams['cells'] = utils::ReadParam('cells', array(), false, 'raw_data');

				$oDashboard = new RuntimeDashboard($sDashboardId);
				$oDashboard->FromParams($aParams);
				$bIsNew = $oDashboard->Save();

				$sDashboardFile = addslashes(utils::ReadParam('file', '', false, 'string'));
				$sDashboardDivId = preg_replace('/[^a-zA-Z0-9_]/', '', $sDashboardId);
				$sOperation = 'reload_dashboard';
				if ($bIsNew) {
					// Trigger a reload of the current page since the dashboard just changed
					$oPage->add_script(
						<<<JS
			window.location.reload();
JS
					);
				} else {
					$oPage->add_script(
						<<<JS
			$('.ibo-dashboard#{$sDashboardDivId}').block();
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			   { operation: 'reload_dashboard', dashboard_id: '{$sDashboardId}', file: '{$sDashboardFile}', extra_params: {$sJSExtraParams}, reload_url: '{$sReloadURL}'},
			   function(data){
				 $('.ibo-dashboard#{$sDashboardDivId}').html(data);
				 $('.ibo-dashboard#{$sDashboardDivId}').unblock();
				}
			 );
JS
					);
				}
				break;

			case 'revert_dashboard':
				$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				appUserPreferences::UnsetPref('display_original_dashboard_'.$sDashboardId);
				$oDashboard = new RuntimeDashboard($sDashboardId);
				$oDashboard->Revert();
				$sFile = addslashes($oDashboard->GetDefinitionFile());
				$sDivId = utils::Sanitize($sDashboardId, '', 'element_identifier');
				// trigger a reload of the current page since the dashboard just changed
				$oPage->add_script(
					<<<EOF
			$('.ibo-dashboard#$sDivId').block();
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			   { operation: 'reload_dashboard', dashboard_id: '$sDashboardId', file: '$sFile', reload_url: '$sReloadURL'},
			   function(data){
				 $('.ibo-dashboard#$sDivId').html(data);
				 $('.ibo-dashboard#$sDivId').unblock();
				}
			 );
EOF
				);
				break;

			case 'render_dashboard':
				$sDashboardId = utils::ReadParam('dashboard_id', '', false, 'raw_data');
				$aExtraParams = utils::ReadParam('extra_params', array(), false, 'raw_data');
				$aParams = array();
				$aParams['layout_class'] = utils::ReadParam('layout_class', '');
				$aParams['title'] = utils::ReadParam('title', '', false, 'raw_data');
				$aParams['cells'] = utils::ReadParam('cells', array(), false, 'raw_data');
				$aParams['auto_reload'] = utils::ReadParam('auto_reload', false);
				$aParams['auto_reload_sec'] = utils::ReadParam('auto_reload_sec', 300);
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				$oDashboard = new RuntimeDashboard($sDashboardId);
				$oDashboard->FromParams($aParams);
				$oDashboard->SetReloadURL($sReloadURL);
				$oDashboard->Render($oPage, true /* bEditMode */, $aExtraParams);
				break;

			case 'dashboard_editor':
				$sId = utils::ReadParam('id', '', false, 'context_param');
				$aExtraParams = utils::ReadParam('extra_params', array(), false, 'raw_data');
				$aExtraParams['dashboard_div_id'] = utils::Sanitize($sId, '', 'element_identifier');
				$sDashboardFile = utils::ReadParam('file', '', false, 'string');
				$sReloadURL = utils::ReadParam('reload_url', '', false, utils::ENUM_SANITIZATION_FILTER_URL);
				$oDashboard = RuntimeDashboard::GetDashboardToEdit($sDashboardFile, $sId);
				if (!is_null($oDashboard)) {
					if (!empty($sReloadURL)) {
						$oDashboard->SetReloadURL($sReloadURL);
					}
					$oDashboard->RenderEditor($oPage, $aExtraParams);
				}
				break;

			case 'new_dashlet_id':
				$sDashboardDivId = utils::ReadParam("dashboardid");
				$bIsCustomized = true; // Only called at runtime when customizing a dashboard
				$iRow = utils::ReadParam("iRow", 0, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);
				$iCol = utils::ReadParam("iCol", 0, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);
				$sDashletIdOrig = utils::ReadParam("dashletid", '', false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
				$sFinalDashletId = Dashboard::GetDashletUniqueId($bIsCustomized, $sDashboardDivId, $iRow, $iCol, $sDashletIdOrig);
				$oPage = new AjaxPage('');
				$oPage->SetOutputDataOnly(true);
				$oPage->add($sFinalDashletId);
				break;

			case 'new_dashlet':
				require_once(APPROOT.'application/forms.class.inc.php');
				require_once(APPROOT.'application/dashlet.class.inc.php');
				$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
				$sDashletId = utils::ReadParam('dashlet_id', '', false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
				if (is_subclass_of($sDashletClass, 'Dashlet')) {
					$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);
					$offset = $oPage->start_capture();
					$oDashlet->DoRender($oPage, true /* bEditMode */, false /* bEnclosingDiv */);
					$sHtml = addslashes($oPage->end_capture($offset));
					$sHtml = str_replace("\n", '', $sHtml);
					$sHtml = str_replace("\r", '', $sHtml);
					$oPage->add_script("$('#dashlet_$sDashletId').html('$sHtml');"); // in ajax web page add_script has the same effect as add_ready_script
					// but is executed BEFORE all 'ready_scripts'
					$oForm = $oDashlet->GetForm(); // Rebuild the form since the values/content changed
					$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', array('operation' => 'update_dashlet_property'));
					$sHtml = addslashes($oForm->RenderAsPropertySheet($oPage, true /* bReturnHtml */, '.itop-dashboard'));
					$sHtml = str_replace("\n", '', $sHtml);
					$sHtml = str_replace("\r", '', $sHtml);
					$oPage->add_script("$('#dashlet_properties_$sDashletId').html('$sHtml')"); // in ajax web page add_script has the same effect as add_ready_script																	   // but is executed BEFORE all 'ready_scripts'
				}
				break;

			case 'update_dashlet_property':
				require_once(APPROOT.'application/forms.class.inc.php');
				require_once(APPROOT.'application/dashlet.class.inc.php');
				$aExtraParams = utils::ReadParam('extra_params', array(), false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
				$aParams = utils::ReadParam('params', [], false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA); // raw_data because we need different filter depending on the options
				$sDashletClass = utils::Sanitize($aParams['attr_dashlet_class'], DashletUnknown::class, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS); // Dashlet PHP class or DashletUnknown if impl isn't present in the installed extensions
				$sDashletType = utils::Sanitize($aParams['attr_dashlet_type'], '', utils::ENUM_SANITIZATION_FILTER_PHP_CLASS); // original Dashlet PHP class, could be non-existing on the iTop instance (XML definition loading)
				$sDashletId = utils::Sanitize($aParams['attr_dashlet_id'], '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
				$aUpdatedProperties = utils::Sanitize($aParams['updated'], [], utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER); // Code of the changed properties as an array: 'attr_xxx', 'attr_xxy' etc
				$aPreviousValues = utils::Sanitize($aParams['previous_values'], [], utils::ENUM_SANITIZATION_FILTER_RAW_DATA); // hash array: 'attr_xxx' => 'old_value' - no sanitization as values will be handled in the Dashlet object

				if (is_subclass_of($sDashletClass, 'Dashlet')) {
					/** @var \Dashlet $oDashlet */
					$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);
					$oDashlet->SetDashletType($sDashletType);
					$oForm = $oDashlet->GetForm();
					$aValues = $oForm->ReadParams(); // hash array: 'xxx' => 'new_value'

					$aCurrentValues = $aValues;
					$aUpdatedDecoded = array();
					foreach ($aUpdatedProperties as $sProp) {
						$sDecodedProp = str_replace('attr_', '', $sProp); // Remove the attr_ prefix
						// Set the previous value
						if (isset($aPreviousValues[$sProp]) && $aPreviousValues[$sProp] != '') {
							$aCurrentValues[$sDecodedProp] = $aPreviousValues[$sProp];
						} else {
							if (gettype($aCurrentValues[$sDecodedProp]) == "array") {
								$aCurrentValues[$sDecodedProp] = [];
							} else {
								$aCurrentValues[$sDecodedProp] = '';
							}
						}
						$aUpdatedDecoded[] = $sDecodedProp;
					}

				$oDashlet->FromParams($aCurrentValues);
				$sPrevClass = get_class($oDashlet);
				$oDashlet = $oDashlet->Update($aValues, $aUpdatedDecoded);
				$sNewClass = get_class($oDashlet);
				if ($sNewClass != $sPrevClass) {
					$oPage->add_ready_script("$('#dashlet_$sDashletId').dashlet('option', {dashlet_class: '$sNewClass'});");
				}
				if ($oDashlet->IsRedrawNeeded()) {
					$oBlock = $oDashlet->DoRender($oPage, true, false, $aExtraParams);
					$sHtml = ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oBlock);
					$sHtml= json_encode($sHtml);
					$oPage->add_script("$('#dashlet_$sDashletId').html({$sHtml});");
				}
				if ($oDashlet->IsFormRedrawNeeded()) {
					$oForm = $oDashlet->GetForm(); // Rebuild the form since the values/content changed
					$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', array('operation' => 'update_dashlet_property', 'extra_params' => $aExtraParams));
					$sHtml = $oForm->RenderAsPropertySheet($oPage, true, '.itop-dashboard');
					$sHtml= json_encode($sHtml);
					$oPage->add_script("$('#dashlet_properties_$sDashletId').html({$sHtml});");
				}
			}
			break;

			case 'dashlet_creation_dlg':
				$sOQL = utils::ReadParam('oql', '', false, 'raw_data');
				RuntimeDashboard::GetDashletCreationDlgFromOQL($oPage, $sOQL);
				break;

			case 'add_dashlet':
				$oForm = RuntimeDashboard::GetDashletCreationForm();
				$aValues = $oForm->ReadParams();

				$sDashletClass = $aValues['dashlet_class'];
				$sMenuId = $aValues['menu_id'];

				if (is_subclass_of($sDashletClass, 'Dashlet')) {
					$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), 0);
					$oDashlet->FromParams($aValues);

					ApplicationMenu::LoadAdditionalMenus();
					$index = ApplicationMenu::GetMenuIndexById($sMenuId);
					$oMenu = ApplicationMenu::GetMenuNode($index);
					$oMenu->AddDashlet($oDashlet);
					// navigate to the dashboard page
					if ($aValues['open_editor']) {
						$oPage->add_ready_script("window.location.href='".addslashes(utils::GetAbsoluteUrlAppRoot().'pages/UI.php?c[menu]='.urlencode($sMenuId))."&edit=1';"); // reloads the page, doing a GET even if we arrived via a POST
					}
				}
				break;

			case 'shortcut_list_dlg':
				$sOQL = utils::ReadParam('oql', '', false, 'raw_data');
				$sTableSettings = utils::ReadParam('table_settings', '', false, 'raw_data');
				ShortcutOQL::GetCreationDlgFromOQL($oPage, $sOQL, $sTableSettings);
				break;

			case 'shortcut_list_create':
				$oForm = ShortcutOQL::GetCreationForm();
				$aValues = $oForm->ReadParams();

				$oAppContext = new ApplicationContext();
				$aContext = $oAppContext->GetAsHash();
				$sContext = serialize($aContext);

				// Create shortcut
				/** @var ShortcutOQL $oShortcut */
				$oShortcut = MetaModel::NewObject("ShortcutOQL");
				$oShortcut->Set('user_id', UserRights::GetUserId());
				$oShortcut->Set("context", $sContext);
				$oShortcut->Set("name", $aValues['name']);
				$oShortcut->Set("oql", $aValues['oql']);
				$iAutoReload = (int)$aValues['auto_reload_sec'];
				if (($aValues['auto_reload']) && ($iAutoReload > 0)) {
					$oShortcut->Set("auto_reload_sec", max(MetaModel::GetConfig()->Get('min_reload_interval'), $iAutoReload));
					$oShortcut->Set("auto_reload", 'custom');
				}
				utils::PushArchiveMode(false);
				$iId = $oShortcut->DBInsertNoReload();
				utils::PopArchiveMode();

				$oShortcut->CloneTableSettings($aValues['table_settings']);

				// Add shortcut to current menu
				// - Init. app. menu
				ApplicationMenu::LoadAdditionalMenus();

				// - Find newly created shortcut
				$aNewShortcutNode = null;
				$sMenuGroupId = 'MyShortcuts';
				$sMenuGroupIdx = ApplicationMenu::GetMenuIndexById($sMenuGroupId);
				if (0 <= $sMenuGroupIdx) {
					$sNewShortcutId = $sMenuGroupId.'_'.$oShortcut->GetKey();
					$aShortcutsNodes = ApplicationMenu::GetSubMenuNodes($sMenuGroupIdx);
					foreach ($aShortcutsNodes as $aShortcutNode) {
						if ($sNewShortcutId === $aShortcutNode['sId']) {
							$aNewShortcutNode = $aShortcutNode;
							break;
						}
					}
				}

				// - If shortcut found, insert it in the navigation menu
				if (!empty($aNewShortcutNode)) {
					$sHtml = TwigHelper::RenderTemplate(
						TwigHelper::GetTwigEnvironment(TwigHelper::ENUM_TEMPLATES_BASE_PATH_BACKOFFICE),
						['aMenuNode' => $aNewShortcutNode],
						'base/layouts/navigation-menu/menu-node'
					);

				$MenuNameEscaped = utils::HtmlEntities($aValues['name']);
				// Important: Mind the back ticks to avoid line breaks to break the JS
				$oPage->add_script(<<<JS
$('body').trigger('add_shortcut_node.navigation_menu.itop', {
	parent_menu_node_id: '{$sMenuGroupId}',
	new_menu_node_html_rendering: `{$sHtml}`,
	new_menu_name: `{$MenuNameEscaped}`
});
JS
					);
				}
				break;

			case 'shortcut_rename_dlg':
				$oSearch = new DBObjectSearch('Shortcut');
				$aShortcuts = utils::ReadMultipleSelection($oSearch);
				$iShortcut = $aShortcuts[0];
				$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);
				$oShortcut->StartRenameDialog($oPage);
				break;

			case 'shortcut_rename_go':
				$iShortcut = utils::ReadParam('id', 0);
				$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);

				$sName = utils::ReadParam('attr_name', '', false, 'raw_data');
				if (strlen($sName) > 0) {
					$oShortcut->Set('name', $sName);
					utils::PushArchiveMode(false);
					$oShortcut->DBUpdate();
					utils::PopArchiveMode();
					$oPage->add_ready_script('window.location.reload();');
				}

				break;

			case 'shortcut_delete_go':
				$oSearch = new DBObjectSearch('Shortcut');
				$oSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
				$aShortcuts = utils::ReadMultipleSelection($oSearch);
				foreach ($aShortcuts as $iShortcut) {
					$oShortcut = MetaModel::GetObject('Shortcut', $iShortcut);
					utils::PushArchiveMode(false);
					$oShortcut->DBDelete();
					utils::PopArchiveMode();
					$oPage->add_ready_script('window.location.reload();');
				}
				break;

			case 'about_box':
				AjaxRenderController::DisplayAboutBox($oPage);
				break;

			case 'full_text_search':
				$aFullTextNeedles = utils::ReadParam('needles', array(), false, 'raw_data');
				$sFullText = trim(implode(' ', $aFullTextNeedles));
				$sClassName = utils::ReadParam('classname', '');
				$iCount = utils::ReadParam('count', 0);
				$iCurrentPos = utils::ReadParam('position', 0);
				$iTune = utils::ReadParam('tune', 0);
				if (empty($sFullText)) {
					$oPage->p(Dict::S('UI:Search:NoSearch'));
					break;
				}

				// Search in full text mode in all the classes
				$aMatches = array();

				// Build the ordered list of classes to search into
				//
				if (empty($sClassName)) {
					$aSearchClasses = MetaModel::GetClasses('searchable');
				} else {
					// Search is limited to a given class and its subclasses
					$aSearchClasses = MetaModel::EnumChildClasses($sClassName, ENUM_CHILD_CLASSES_ALL);
				}
				// Skip abstract classes, since we search in all the child classes anyway
				foreach ($aSearchClasses as $idx => $sClass) {
					if (MetaModel::IsAbstract($sClass)) {
						unset($aSearchClasses[$idx]);
					}
				}

				$sMaxChunkDuration = MetaModel::GetConfig()->Get('full_text_chunk_duration');
				$aAccelerators = MetaModel::GetConfig()->Get('full_text_accelerators');

				foreach (array_reverse($aAccelerators) as $sClass => $aRestriction) {
					$bSkip = false;
					$iPos = array_search($sClass, $aSearchClasses);
					if ($iPos !== false) {
						unset($aSearchClasses[$iPos]);
					} else {
						$bSkip = true;
					}
					$bSkip |= array_key_exists('skip', $aRestriction) ? $aRestriction['skip'] : false;
					if (!in_array($sClass, $aSearchClasses)) {
						if ($sClass == $sClassName) {
							// Class explicitely requested, do NOT skip it
							// beware: there may not be a 'query' defined for a skipped class !
							$bSkip = false;
						}
					}
					if (!$bSkip) {
						// NOT skipped, add the class to the list of classes to search into
						if (array_key_exists('query', $aRestriction)) {
							array_unshift($aSearchClasses, $aRestriction['query']);
						} else {
							// No accelerator query
							array_unshift($aSearchClasses, $sClassName);
						}
					}
				}

				$aSearchClasses = array_values($aSearchClasses); // renumbers the array starting from zero, removing the missing indexes
				$fStarted = microtime(true);
				$iFoundInThisRound = 0;
				for ($iPos = $iCurrentPos; $iPos < count($aSearchClasses); $iPos++) {
					if ($iFoundInThisRound && (microtime(true) - $fStarted >= $sMaxChunkDuration)) {
						break;
					}

					$sClassSpec = $aSearchClasses[$iPos];
					if (substr($sClassSpec, 0, 7) == 'SELECT ') {
						$oFilter = DBObjectSearch::FromOQL($sClassSpec);
						$sClassName = $oFilter->GetClass();
						$sNeedleFormat = isset($aAccelerators[$sClassName]['needle']) ? $aAccelerators[$sClassName]['needle'] : '%$needle$%';
						$sNeedle = str_replace('$needle$', $sFullText, $sNeedleFormat);
						$aParams = array('needle' => $sNeedle);
					} else {
						$sClassName = $sClassSpec;
						$oFilter = new DBObjectSearch($sClassName);
						$aParams = array();

						foreach ($aFullTextNeedles as $sSearchText) {
							$oFilter->AddCondition_FullText($sSearchText);
						}
					}
					$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
					// Skip abstract classes
					if (MetaModel::IsAbstract($sClassName)) {
						continue;
					}

					if ($iTune > 0) {
						$fStartedClass = microtime(true);
					}
					$oSet = new DBObjectSet($oFilter, array(), $aParams);
					if (array_key_exists($sClassName, $aAccelerators) && array_key_exists('attributes', $aAccelerators[$sClassName])) {
						$oSet->OptimizeColumnLoad(array($oFilter->GetClassAlias() => $aAccelerators[$sClassName]['attributes']));
					}

					$sFullTextJS = addslashes($sFullText);
					$bEnableEnlarge = array_key_exists($sClassName, $aAccelerators) && array_key_exists('query', $aAccelerators[$sClassName]);
					if (array_key_exists($sClassName, $aAccelerators) && array_key_exists('enable_enlarge', $aAccelerators[$sClassName])) {
						$bEnableEnlarge &= $aAccelerators[$sClassName]['enable_enlarge'];
					}
					$sEnlargeTheSearch =
						<<<EOF
			$('.search-class-$sClassName button').prop('disabled', true);

			$('.search-class-$sClassName h2').append('&nbsp;<img id="indicator" src="' + GetAbsoluteUrlAppRoot() + 'images/indicator.gif">');
			var oParams = {operation: 'full_text_search_enlarge', class: '$sClassName', text: '$sFullTextJS'};
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
				$('.search-class-$sClassName').html(data);
			});
EOF;


					$sEnlargeButton = '';
					if ($bEnableEnlarge) {
						$sEnlargeButton = "&nbsp;<button onclick=\"".utils::EscapeHtml($sEnlargeTheSearch)."\">".Dict::S('UI:Search:Enlarge')."</button>";
					}
					if ($oSet->Count() > 0) {
						$aLeafs = array();
						while ($oObj = $oSet->Fetch()) {
							if (get_class($oObj) == $sClassName) {
								$aLeafs[] = $oObj->GetKey();
								$iFoundInThisRound++;
							}
						}
						$oLeafsFilter = new DBObjectSearch($sClassName);
						if (count($aLeafs) > 0) {
							$iCount += count($aLeafs);
							$oPage->add("<div class=\"search-class-result search-class-$sClassName\">\n");
							$oPage->add("<div class=\"page_header\">\n");
							if (array_key_exists($sClassName, $aAccelerators)) {
								$oPage->add('<h2 class="ibo-global-search--result--title">'.MetaModel::GetClassIcon($sClassName).Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aLeafs), Metamodel::GetName($sClassName)).$sEnlargeButton."</h2>\n");
							} else {
								$oPage->add('<h2 class="ibo-global-search--result--title">'.MetaModel::GetClassIcon($sClassName).Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aLeafs), Metamodel::GetName($sClassName))."</h2>\n");
							}
							$oPage->add("</div>\n");
							$oLeafsFilter->AddCondition('id', $aLeafs, 'IN');
							$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
							$sBlockId = 'global_search_'.$sClassName;
							$oPage->add('<div id="'.$sBlockId.'">');
							$oBlock->RenderContent($oPage, array('table_id' => $sBlockId, 'currentId' => $sBlockId));
							$oPage->add("</div>\n");
							$oPage->add("</div>\n");
							$oPage->p('&nbsp;'); // Some space ?
						}
					} else {
						if (array_key_exists($sClassName, $aAccelerators)) {
							$oPage->add("<div class=\"search-class-result search-class-$sClassName\">\n");
							$oPage->add("<div class=\"page_header\">\n");
							$oPage->add('<h2 class="ibo-global-search--result--title">'.MetaModel::GetClassIcon($sClassName).Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', 0, Metamodel::GetName($sClassName)).$sEnlargeButton."</h2>\n");
							$oPage->add("</div>\n");
							$oPage->add("</div>\n");
							$oPage->p('&nbsp;'); // Some space ?
						}
					}
					if ($iTune > 0) {
						$fDurationClass = microtime(true) - $fStartedClass;
						$oPage->add_script("oTimeStatistics.$sClassName = $fDurationClass;");
					}
				}
				if ($iPos < count($aSearchClasses)) {
					$sJSNeedle = json_encode($aFullTextNeedles);
					$oPage->add_ready_script(
						<<<EOF
				var oParams = {operation: 'full_text_search', position: $iPos, needles: $sJSNeedle, count: $iCount, tune: $iTune};
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
					$('#full_text_results').append(data);
				});
EOF
					);
				} else {
					// We're done
					$oPage->add_ready_script(
						<<<EOF
$('#full_text_indicator').hide();
$('#full_text_progress,#full_text_progress_placeholder').hide(500);
EOF
					);

					if ($iTune > 0) {
						$oPage->add_ready_script(
							<<<EOF
				var sRes = '<h4>Search statistics (tune = 1)</h4><table>';
				sRes += '<thead><tr><th>Class</th><th>Time</th></tr></thead>';
				sRes += '<tbody>';
				var fTotal = 0;
				for (var sClass in oTimeStatistics)
				{
					fTotal = fTotal + oTimeStatistics[sClass];
					fRounded = Math.round(oTimeStatistics[sClass] * 1000) / 1000;
					sRes += '<tr><td>' + sClass + '</td><td>' + fRounded + '</td></tr>';
				}
				
				fRoundedTotal = Math.round(fTotal * 1000) / 1000;
				sRes += '<tr><td><b>Total</b></td><td><b>' + fRoundedTotal + '</b></td></tr>';
				sRes += '</tbody>';
				sRes += '</table>';
				$('#full_text_results').append(sRes);
EOF
						);
					}

					if ($iCount == 0) {
						$sFullTextSummary = addslashes(Dict::S('UI:Search:NoObjectFound'));
						$oPage->add_ready_script("$('#full_text_results').append('<div id=\"no_object_found\">$sFullTextSummary</div>');");
					}
				}
				break;

			case 'full_text_search_enlarge':
				$sFullText = trim(utils::ReadParam('text', '', false, 'raw_data'));
				$sClass = trim(utils::ReadParam('class', ''));
				$iTune = utils::ReadParam('tune', 0);

				if (preg_match('/^"(.*)"$/', $sFullText, $aMatches)) {
					// The text is surrounded by double-quotes, remove the quotes and treat it as one single expression
					$aFullTextNeedles = array($aMatches[1]);
				} else {
					// Split the text on the blanks and treat this as a search for <word1> AND <word2> AND <word3>
					$aFullTextNeedles = explode(' ', $sFullText);
				}

				$oFilter = new DBObjectSearch($sClass);
				foreach ($aFullTextNeedles as $sSearchText) {
					$oFilter->AddCondition_FullText($sSearchText);
				}
				$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
				$oSet = new DBObjectSet($oFilter);
				$oPage->add("<div class=\"page_header\">\n");
				$oPage->add("<h2>".MetaModel::GetClassIcon($sClass)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', $oSet->Count(), Metamodel::GetName($sClass))."</h2>\n");
				$oPage->add("</div>\n");
				if ($oSet->Count() > 0) {
					$aLeafs = array();
					while ($oObj = $oSet->Fetch()) {
						if (get_class($oObj) == $sClass) {
							$aLeafs[] = $oObj->GetKey();
						}
					}
					$oLeafsFilter = new DBObjectSearch($sClass);
					if (count($aLeafs) > 0) {
						$oLeafsFilter->AddCondition('id', $aLeafs, 'IN');
						$oBlock = new DisplayBlock($oLeafsFilter, 'list', false);
						$sBlockId = 'global_search_'.$sClass;
						$oPage->add('<div id="'.$sBlockId.'">');
						$oBlock->RenderContent($oPage, array('table_id' => $sBlockId, 'currentId' => $sBlockId));
						$oPage->add('</div>');
						$oPage->P('&nbsp;'); // Some space ?
						// Hide "no object found"
						$oPage->add_ready_script('$("#no_object_found").hide();');
					}
				}
				$oPage->add_ready_script(
					<<<EOF
$('#full_text_indicator').hide();
$('#full_text_progress,#full_text_progress_placeholder').hide(500);
EOF
				);
				break;

			case 'xlsx_export_dialog':
				DeprecatedCallsLog::NotifyDeprecatedPhpEndpoint('Use "export_*" operations instead of "'.$operation.'"');
				$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
				$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
				$oPage->SetContentType('text/html');
				$oPage->add(
					<<<EOF
<style>
 .ui-progressbar {
	position: relative;
}
.progress-label {
	position: absolute;
	left: 50%;
	top: 1px;
	font-size: 11pt;
}
.download-form button {
	display:block;
	margin-left: auto;
	margin-right: auto;
	margin-top: 2em;
}
.ui-progressbar-value {
	background: url(../setup/orange-progress.gif);
}
.progress-bar {
	height: 20px;
}
.statistics > div {
	padding-left: 16px;
	cursor: pointer;
	font-size: 10pt;
	background: url({$sAppRootUrl}images/minus.gif) 0 2px no-repeat;
}				
.statistics > div.closed {
	padding-left: 16px;
	background: url({$sAppRootUrl}images/plus.gif) 0 2px no-repeat;
}
				
.statistics .closed .stats-data {
	display: none;
}
.stats-data td {
	padding-right: 5px;
}
</style>				
EOF
				);
				$oPage->add('<div id="XlsxExportDlg">');
				$oPage->add('<div class="export-options">');
				$oPage->add('<p><input type="checkbox" id="export-advanced-mode"/>&nbsp;<label for="export-advanced-mode">'.Dict::S('UI:CSVImport:AdvancedMode').'</label></p>');
				$oPage->add('<p style="font-size:10pt;margin-left:2em;margin-top:-0.5em;padding-bottom:1em;">'.Dict::S('UI:CSVImport:AdvancedMode+').'</p>');
				$oPage->add('<p><input type="checkbox" id="export-auto-download" checked="checked"/>&nbsp;<label for="export-auto-download">'.Dict::S('ExcelExport:AutoDownload').'</label></p>');
				$oPage->add('</div>');
				$oPage->add('<div class="progress"><p class="status-message">'.Dict::S('ExcelExport:PreparingExport').'</p><div class="progress-bar"><div class="progress-label"></div></div></div>');
				$oPage->add('<div class="statistics"><div class="stats-toggle closed">'.Dict::S('ExcelExport:Statistics').'<div class="stats-data"></div></div></div>');
				$oPage->add('</div>');
				$aLabels = array(
					'dialog_title'    => Dict::S('ExcelExporter:ExportDialogTitle'),
					'cancel_button'   => Dict::S('UI:Button:Cancel'),
					'export_button'   => Dict::S('ExcelExporter:ExportButton'),
					'download_button' => Dict::Format('ExcelExporter:DownloadButton', 'export.xlsx'), //TODO: better name for the file (based on the class of the filter??)
				);
				$sJSLabels = json_encode($aLabels);
				$sFilter = addslashes($sFilter);
				$sJSPageUrl = addslashes($sAppRootUrl.'pages/ajax.render.php');
				$oPage->add_ready_script("$('#XlsxExportDlg').xlsxexporter({filter: '$sFilter', labels: $sJSLabels, ajax_page_url: '$sJSPageUrl'});");
				break;

			case 'xlsx_start':
				DeprecatedCallsLog::NotifyDeprecatedPhpEndpoint('Use "export_*" operations instead of "'.$operation.'"');
				$sFilter = utils::ReadParam('filter', '', false, 'raw_data');
				$bAdvanced = (utils::ReadParam('advanced', 'false') == 'true');
				$oSearch = DBObjectSearch::unserialize($sFilter);
				$oExcelExporter = new ExcelExporter();
				$oExcelExporter->SetObjectList($oSearch);
				//$oExcelExporter->SetChunkSize(10); //Only for testing
				$oExcelExporter->SetAdvancedMode($bAdvanced);
				$sToken = $oExcelExporter->SaveState();
				$oPage->add(json_encode(array('status' => 'ok', 'token' => $sToken)));
				break;

			case 'xlsx_run':
				DeprecatedCallsLog::NotifyDeprecatedPhpEndpoint('Use "export_*" operations instead of "'.$operation.'"');
				$sMemoryLimit = MetaModel::GetConfig()->Get('xlsx_exporter_memory_limit');
				if (utils::SetMinMemoryLimit($sMemoryLimit) === false) {
					IssueLog::Warning("XSLX export : cannot set memory_limit to {$sMemoryLimit}");
				}
				ini_set('max_execution_time', max(300, ini_get('max_execution_time'))); // At least 5 minutes

				$sToken = utils::ReadParam('token', '', false, 'raw_data');
				$oExcelExporter = new ExcelExporter($sToken);
				$aStatus = $oExcelExporter->Run();
				$aResults = array('status' => $aStatus['code'], 'percentage' => $aStatus['percentage'], 'message' => $aStatus['message']);
				if ($aStatus['code'] == 'done') {
					$aResults['statistics'] = $oExcelExporter->GetStatistics('html');
				}
				$oPage->add(json_encode($aResults));
				break;

			case 'xlsx_download':
				DeprecatedCallsLog::NotifyDeprecatedPhpEndpoint('Use "export_*" operations instead of "'.$operation.'"');
				$oPage = new DownloadPage('');
				$sToken = utils::ReadParam('token', '', false, 'raw_data');
				$oPage->SetContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				$oPage->SetContentDisposition('attachment', 'export.xlsx');
				$sFileContent = ExcelExporter::GetExcelFileFromToken($sToken);
				$oPage->add($sFileContent);
				ExcelExporter::CleanupFromToken($sToken);
				break;

			case 'xlsx_abort':
				DeprecatedCallsLog::NotifyDeprecatedPhpEndpoint('Use "export_*" operations instead of "'.$operation.'"');
				// Stop & cleanup an export...
				$sToken = utils::ReadParam('token', '', false, 'raw_data');
				ExcelExporter::CleanupFromToken($sToken);
				break;

			case 'relation_pdf':
			case 'relation_attachment':
				require_once(APPROOT.'core/simplegraph.class.inc.php');
				require_once(APPROOT.'core/relationgraph.class.inc.php');
				require_once(APPROOT.'core/displayablegraph.class.inc.php');
				$sRelation = utils::ReadParam('relation', 'impacts');
				$sDirection = utils::ReadParam('direction', 'down');

				$iGroupingThreshold = utils::ReadParam('g', 5, false, 'integer');
				$sPageFormat = utils::ReadParam('p', 'A4');
				$sPageOrientation = utils::ReadParam('o', 'L');
				$sTitle = utils::ReadParam('title', '', false, 'raw_data');
				$sPositions = utils::ReadParam('positions', null, false, 'raw_data');
				$aExcludedClasses = utils::ReadParam('excluded_classes', array(), false, 'raw_data');
				$bIncludeList = (bool)utils::ReadParam('include_list', false);
				$sComments = utils::ReadParam('comments', '', false, 'raw_data');
				$aContexts = utils::ReadParam('contexts', array(), false, 'raw_data');
				$sContextKey = utils::ReadParam('context_key', '', false, 'raw_data');
				$aPositions = null;
				if ($sPositions != null) {
					$aPositions = json_decode($sPositions, true);
				}

				// Get the list of source objects
				$aSources = utils::ReadParam('sources', array(), false, 'raw_data');
				$aSourceObjects = array();
				foreach ($aSources as $sClass => $aIDs) {
					$oSearch = new DBObjectSearch($sClass);
					$oSearch->AddCondition('id', $aIDs, 'IN');
					$oSet = new DBObjectSet($oSearch);
					while ($oObj = $oSet->Fetch()) {
						$aSourceObjects[] = $oObj;
					}
				}
				$sSourceClass = '*';
				if (count($aSourceObjects) == 1) {
					$sSourceClass = get_class($aSourceObjects[0]);
				}

				// Get the list of excluded objects
				$aExcluded = utils::ReadParam('excluded', array(), false, 'raw_data');
				$aExcludedObjects = array();
				foreach ($aExcluded as $sClass => $aIDs) {
					$oSearch = new DBObjectSearch($sClass);
					$oSearch->AddCondition('id', $aIDs, 'IN');
					$oSet = new DBObjectSet($oSearch);
					while ($oObj = $oSet->Fetch()) {
						$aExcludedObjects[] = $oObj;
					}
				}

				$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth');
				if ($sDirection == 'up') {
					$oRelGraph = MetaModel::GetRelatedObjectsUp($sRelation, $aSourceObjects, $iMaxRecursionDepth, true, $aContexts);
				} else {
					$oRelGraph = MetaModel::GetRelatedObjectsDown($sRelation, $aSourceObjects, $iMaxRecursionDepth, true, $aExcludedObjects, $aContexts);
				}

				// Remove excluded classes from the graph
				if (count($aExcludedClasses) > 0) {
					$oIterator = new RelationTypeIterator($oRelGraph, 'Node');
					foreach ($oIterator as $oNode) {
						$oObj = $oNode->GetProperty('object');
						if ($oObj && in_array(get_class($oObj), $aExcludedClasses)) {
							$oRelGraph->FilterNode($oNode);
						}
					}
				}

				$oPage = new PDFPage($sTitle, $sPageFormat, $sPageOrientation);
				$oPage->SetContentDisposition('attachment', $sTitle.'.pdf');

				$oGraph = DisplayableGraph::FromRelationGraph($oRelGraph, $iGroupingThreshold, ($sDirection == 'down'), true);
				$oGraph->InitFromGraphviz();
				if ($aPositions != null) {
					$oGraph->UpdatePositions($aPositions);
				}

				$aGroups = array();
				$oIterator = new RelationTypeIterator($oGraph, 'Node');
				foreach ($oIterator as $oNode) {
					if ($oNode instanceof DisplayableGroupNode) {
						$aGroups[$oNode->GetProperty('group_index')] = $oNode->GetObjects();
					}
				}
				// First page is the graph
				$oGraph->RenderAsPDF($oPage, $sComments, $sContextKey);

				if ($bIncludeList) {
					// Then the lists of objects (one table per finalclass)
					$aResults = array();
					$oIterator = new RelationTypeIterator($oRelGraph, 'Node');
					foreach ($oIterator as $oNode) {
						$oObj = $oNode->GetProperty('object'); // Some nodes (Redundancy Nodes and Group) do not contain an object
						if ($oObj) {
							$sObjClass = get_class($oObj);
							if (!array_key_exists($sObjClass, $aResults)) {
								$aResults[$sObjClass] = array();
							}
							$aResults[$sObjClass][] = $oObj;
						}
					}

					$oPage->get_tcpdf()->AddPage();
					$oPage->get_tcpdf()->SetFontSize(10); // Reset the font size to its default
					$oPage->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S('UI:RelationshipList')));
					$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
					foreach ($aResults as $sListClass => $aObjects) {
						set_time_limit($iLoopTimeLimit * count($aObjects));
						$oSet = CMDBObjectSet::FromArray($sListClass, $aObjects);
						$oSet->SetShowObsoleteData(utils::ShowObsoleteData());
						/* cf N°3928 - Polishing: Impact analysis - remove icons in pdf list
						 $sIconUrl = MetaModel::GetClassIcon($sListClass, false);
						$sIconUrl = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-'.utils::GetCurrentEnvironment().'/', $sIconUrl);
						$oTitle = new Html("<img src=\"$sIconUrl\" style=\"vertical-align:middle;width: 24px; height: 24px;\"/> ".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', $oSet->Count(), Metamodel::GetName($sListClass)));*/
						$oTitle = new Html(Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', $oSet->Count(), Metamodel::GetName($sListClass)));
						$oPage->AddSubBlock(TitleUIBlockFactory::MakeStandard($oTitle, 2));
						$oPage->AddSubBlock(cmdbAbstractObject::GetDataTableFromDBObjectSet($oSet, array('table_id' => $sSourceClass.'_'.$sRelation.'_'.$sDirection.'_'.$sListClass)));
					}

					// Then the content of the groups (one table per group)
					if (count($aGroups) > 0) {
						$oPage->get_tcpdf()->AddPage();
						$oPage->AddSubBlock(TitleUIBlockFactory::MakeNeutral(Dict::S('UI:RelationGroups')));
						foreach ($aGroups as $idx => $aObjects) {
							set_time_limit($iLoopTimeLimit * count($aObjects));
							$sListClass = get_class(current($aObjects));
							$oSet = CMDBObjectSet::FromArray($sListClass, $aObjects);
							$sIconUrl = MetaModel::GetClassIcon($sListClass, false);
							$sIconUrl = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-'.utils::GetCurrentEnvironment().'/', $sIconUrl);
							$oTitle = new Html("<img src=\"$sIconUrl\" style=\"vertical-align:middle;width: 24px; height: 24px;\"/> ".Dict::Format('UI:RelationGroupNumber_N', (1 + $idx)), Metamodel::GetName($sListClass));
							$oPage->AddSubBlock(TitleUIBlockFactory::MakeStandard($oTitle, 2));
							$oPage->AddSubBlock(cmdbAbstractObject::GetDataTableFromDBObjectSet($oSet));

						}
					}
				}
				if ($operation == 'relation_attachment') {
					$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
					$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');

					// Save the generated PDF as an attachment
					$sPDF = $oPage->get_pdf();
					$oPage = new JsonPage();
					$oPage->SetOutputDataOnly(true);
					$oAttachment = MetaModel::NewObject('Attachment');
					$oAttachment->Set('item_class', $sObjClass);
					$oAttachment->Set('item_id', $iObjKey);
					$oDoc = new ormDocument($sPDF, 'application/pdf', $sTitle.'.pdf');
					$oAttachment->Set('contents', $oDoc);
					$iAttachmentId = $oAttachment->DBInsert();
					$aRet = array(
						'status' => 'ok',
						'att_id' => $iAttachmentId,
					);
					$oPage->SetData($aRet);
				}
				break;

			case 'relation_json':
				require_once(APPROOT.'core/simplegraph.class.inc.php');
				require_once(APPROOT.'core/relationgraph.class.inc.php');
				require_once(APPROOT.'core/displayablegraph.class.inc.php');
				$sRelation = utils::ReadParam('relation', 'impacts');
				$sDirection = utils::ReadParam('direction', 'down');
				$iGroupingThreshold = utils::ReadParam('g', 5);
				$sPositions = utils::ReadParam('positions', null, false, 'raw_data');
				$aExcludedClasses = utils::ReadParam('excluded_classes', array(), false, 'raw_data');
				$aContexts = utils::ReadParam('contexts', array(), false, 'raw_data');
				$sContextKey = utils::ReadParam('context_key', array(), false, 'raw_data');
				$aPositions = null;
				if ($sPositions != null) {
					$aPositions = json_decode($sPositions, true);
				}

				// Get the list of source objects
				$aSources = utils::ReadParam('sources', array(), false, 'raw_data');
				$aSourceObjects = array();
				foreach ($aSources as $sClass => $aIDs) {
					$oSearch = new DBObjectSearch($sClass);
					$oSearch->AddCondition('id', $aIDs, 'IN');
					$oSet = new DBObjectSet($oSearch);
					while ($oObj = $oSet->Fetch()) {
						$aSourceObjects[] = $oObj;
					}
				}

				// Get the list of excluded objects
				$aExcluded = utils::ReadParam('excluded', array(), false, 'raw_data');
				$aExcludedObjects = array();
				foreach ($aExcluded as $sClass => $aIDs) {
					$oSearch = new DBObjectSearch($sClass);
					$oSearch->AddCondition('id', $aIDs, 'IN');
					$oSet = new DBObjectSet($oSearch);
					while ($oObj = $oSet->Fetch()) {
						$aExcludedObjects[] = $oObj;
					}
				}

				// Compute the graph
				$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth');
				if ($sDirection == 'up') {
					$oRelGraph = MetaModel::GetRelatedObjectsUp($sRelation, $aSourceObjects, $iMaxRecursionDepth, true, $aContexts);
				} else {
					$oRelGraph = MetaModel::GetRelatedObjectsDown($sRelation, $aSourceObjects, $iMaxRecursionDepth, true, $aExcludedObjects, $aContexts);
				}

				// Remove excluded classes from the graph
				if (count($aExcludedClasses) > 0) {
					$oIterator = new RelationTypeIterator($oRelGraph, 'Node');
					foreach ($oIterator as $oNode) {
						$oObj = $oNode->GetProperty('object');
						if ($oObj && in_array(get_class($oObj), $aExcludedClasses)) {
							$oRelGraph->FilterNode($oNode);
						}
					}
				}

				$oGraph = DisplayableGraph::FromRelationGraph($oRelGraph, $iGroupingThreshold, ($sDirection == 'down'));
				$oGraph->InitFromGraphviz();
				if ($aPositions != null) {
					$oGraph->UpdatePositions($aPositions);
				}
				$oPage->add($oGraph->GetAsJSON($sContextKey));
				$oPage->SetContentType('application/json');
				break;

			case 'relation_groups':
				$aGroups = utils::ReadParam('groups');
				$iBlock = 1; // Zero is not a valid blockid
				foreach ($aGroups as $idx => $aDefinition) {
					$sListClass = $aDefinition['class'];
					$oSearch = new DBObjectSearch($sListClass);
					$oSearch->AddCondition('id', $aDefinition['keys'], 'IN');
					$oSearch->SetShowObsoleteData(utils::ShowObsoleteData());
					$oPage->AddUiBlock(TitleUIBlockFactory::MakeNeutral(Dict::Format('UI:RelationGroupNumber_N', (1 + $idx)), 1, "relation_group_$idx"));
					$oBlock = new DisplayBlock($oSearch, 'list');
					$oBlock->Display($oPage, 'group_'.$iBlock++, array(
						'surround_with_panel' => true,
						'panel_class'         => $sListClass,
						'panel_title'         => Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aDefinition['keys']), Metamodel::GetName($sListClass)),
						'panel_icon'          => MetaModel::GetClassIcon($sListClass, false),
					));
				}
				break;

			case 'relation_lists':
				$aLists = utils::ReadParam('lists');
				$iBlock = 1; // Zero is not a valid blockid
				foreach ($aLists as $sListClass => $aKeys) {
					$oSearch = new DBObjectSearch($sListClass);
					$oSearch->AddCondition('id', $aKeys, 'IN');
					$oSearch->SetShowObsoleteData(utils::ShowObsoleteData());
					$oBlock = new DisplayBlock($oSearch, 'list');
					$oBlock->Display($oPage, 'list_'.$iBlock++, array(
						'table_id'            => 'ImpactAnalysis_'.$sListClass,
						'surround_with_panel' => true,
						'panel_class'         => $sListClass,
						'panel_title'         => Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aKeys), Metamodel::GetName($sListClass)),
						'panel_icon'          => MetaModel::GetClassIcon($sListClass, false),
					));
				}
				break;

			case 'ticket_impact':
				require_once(APPROOT.'core/simplegraph.class.inc.php');
				require_once(APPROOT.'core/relationgraph.class.inc.php');
				require_once(APPROOT.'core/displayablegraph.class.inc.php');

				$sRelation = utils::ReadParam('relation', 'impacts');
				$sDirection = utils::ReadParam('direction', 'down');
				$iGroupingThreshold = utils::ReadParam('g', 5);
				$sClass = utils::ReadParam('class', '', false, 'class');
				$sAttCode = utils::ReadParam('attcode', 'functionalcis_list');
				$sImpactAttCode = utils::ReadParam('impact_attcode', 'impact_code');
				$sImpactAttCodeValue = utils::ReadParam('impact_attcode_value', 'manual');
				$iId = (int)utils::ReadParam('id', 0, false, 'integer');

				WebResourcesHelper::EnableSimpleGraphInWebPage($oPage);

				// Get the list of source objects
				$oTicket = MetaModel::GetObject($sClass, $iId);
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
				$oExtKeyToRemote = MetaModel::GetAttributeDef($oAttDef->GetLinkedClass(), $sExtKeyToRemote);
				$sRemoteClass = $oExtKeyToRemote->GetTargetClass();
				$oSet = $oTicket->Get($sAttCode);
				$aSourceObjects = array();
				$aExcludedObjects = array();
				while ($oLnk = $oSet->Fetch()) {
					if ($oLnk->Get($sImpactAttCode) == 'manual') {
						$aSourceObjects[] = MetaModel::GetObject($sRemoteClass, $oLnk->Get($sExtKeyToRemote));
					}
					if ($oLnk->Get($sImpactAttCode) == 'not_impacted') {
						$aExcludedObjects[] = MetaModel::GetObject($sRemoteClass, $oLnk->Get($sExtKeyToRemote));
					}
				}

				// Compute the graph
				$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth');
				if ($sDirection == 'up') {
					$oRelGraph = MetaModel::GetRelatedObjectsUp($sRelation, $aSourceObjects, $iMaxRecursionDepth);
				} else {
					$oRelGraph = MetaModel::GetRelatedObjectsDown($sRelation, $aSourceObjects, $iMaxRecursionDepth, $aExcludedObjects);
				}

				$aResults = $oRelGraph->GetObjectsByClass();
				$oGraph = DisplayableGraph::FromRelationGraph($oRelGraph, $iGroupingThreshold, ($sDirection == 'down'));

				$sContextKey = 'itop-tickets/relation_context/'.$sClass.'/'.$sRelation.'/'.$sDirection;
				$oAppContext = new ApplicationContext();
				$oPage->AddSubBlock($oGraph->DisplayFilterBox($oPage, $aResults));
				$oGraph->DisplayGraph($oPage, $sRelation, $oAppContext, $aExcludedObjects, $sClass, $iId, $sContextKey, array('this' => $oTicket));
				break;

			case 'export_build':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);
				$oAjaxRenderController->ExportBuild($oPage, false);
				break;

			case 'export_build_portal':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);
				$oAjaxRenderController->ExportBuild($oPage, true);
				break;

			case 'export_download':
				$token = utils::ReadParam('token', null);
				if ($token !== null) {
					$oExporter = BulkExport::FindExporterFromToken($token);
					if ($oExporter) {
						$sMimeType = $oExporter->GetMimeType();
						if (substr($sMimeType, 0, 5) == 'text/') {
							$sMimeType .= ';charset='.strtolower($oExporter->GetCharacterSet());
						}
						$oPage = new DownloadPage('');
						$oPage->SetContentType($sMimeType);
						$oPage->SetContentDisposition('attachment', $oExporter->GetDownloadFileName());
						$oPage->add(file_get_contents($oExporter->GetTmpFilePath()));
					}
				}
				break;

			case 'export_cancel':
				$token = utils::ReadParam('token', null);
				if ($token !== null) {
					$oExporter = BulkExport::FindExporterFromToken($token);
					if ($oExporter) {
						$oExporter->Cleanup();
					}
				}
				$aResult = array('code' => 'error', 'percentage' => 100, 'message' => Dict::S('Core:BulkExport:ExportCancelledByUser'));
				$oPage->add(json_encode($aResult));
				break;

			case 'check_lock_state':
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');
				$aLockData = iTopOwnershipLock::IsLocked($sObjClass, $iObjKey);

				$aResult = [
					'locked'  => $aLockData['locked'],
					'message' => '',
				];

				// If lock taken by someone else, tell by who
				if (true === $aLockData['locked']) {
					// Either the contact friendlyname if the user has a contact, otherwise its login
					$sOwner = ($aLockData['owner']->Get('contactid') > 0) ? $aLockData['owner']->Get('contactid_friendlyname') : $aLockData['owner']->GetRawName();
					$aResult['message'] = Dict::Format('UI:CurrentObjectIsSoftLockedBy_User', $sOwner);
				}

				$oPage->SetContentType('application/json');
				$oPage->add(json_encode($aResult));
				break;

			// Important: Only from the backoffice AND logged in
			case 'acquire_lock':
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');

				$aResult = iTopOwnershipLock::AcquireLock($sObjClass, $iObjKey);
				if (false === $aResult['success']) {
					$aLockData = iTopOwnershipLock::IsLocked($sObjClass, $iObjKey);
					// If lock taken by someone else, tell by who
					if (true === $aLockData['locked']) {
						// Either the contact friendlyname if the user has a contact, otherwise its login
						$sOwner = ($aLockData['owner']->Get('contactid') > 0) ? $aLockData['owner']->Get('contactid_friendlyname') : $aLockData['owner']->GetRawName();
						$aResult['message'] = Dict::Format('UI:CurrentObjectIsSoftLockedBy_User', $sOwner);
					}
				}

				$oPage->SetContentType('application/json');
				$oPage->add(json_encode($aResult));
				break;

			case 'extend_lock':
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');
				$sToken = utils::ReadParam('token', 0, false, 'raw_data');

				$aResult = iTopOwnershipLock::ExtendLock($sObjClass, $iObjKey, $sToken);
				if (!$aResult['status']) {
					if ($aResult['operation'] == 'lost') {
						$sName = $aResult['owner']->GetName();
						if ($aResult['owner']->Get('contactid') != 0) {
							$sName .= ' ('.$aResult['owner']->Get('contactid_friendlyname').')';
						}
						$aResult['message'] = Dict::Format('UI:CurrentObjectIsLockedBy_User', $sName);
						$aResult['popup_message'] = Dict::Format('UI:CurrentObjectIsLockedBy_User_Explanation', $sName);
					} else {
						if ($aResult['operation'] == 'expired') {
							$aResult['message'] = Dict::S('UI:CurrentObjectLockExpired');
							$aResult['popup_message'] = Dict::S('UI:CurrentObjectLockExpired_Explanation');
						}
					}
				}

				$oPage->SetContentType('application/json');
				$oPage->add(json_encode($aResult));
				break;

			case 'release_lock':
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjKey = (int)utils::ReadParam('obj_key', 0, false, 'integer');
				$sToken = utils::ReadParam('token', 0, false, 'raw_data');

				$bReleased = iTopOwnershipLock::ReleaseLock($sObjClass, $iObjKey, $sToken);
				$aResult = [
					'success' => $bReleased,
				];

				$oPage->SetContentType('application/json');
				$oPage->add(json_encode($aResult));
				break;

			case 'watchdog':
				$oPage->add('ok'); // Better for debugging...
				break;

			case 'cke_img_upload':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);

				// Image uploaded via CKEditor
				$aResult = array(
					'uploaded' => 0,
					'fileName' => '',
					'url'      => '',
					'icon'     => '',
					'msg'      => '',
					'att_id'   => 0,
					'preview'  => 'false',
				);

				$sObjClass = stripslashes(utils::ReadParam('obj_class', '', false, 'class'));
				$sTempId = utils::ReadParam('temp_id', '', false, 'transaction_id');
				if (empty($sObjClass)) {
					$aResult['error'] = "Missing argument 'obj_class'";
				} elseif (empty($sTempId)) {
					$aResult['error'] = "Missing argument 'temp_id'";
				} else {
					try {
						$oDoc = utils::ReadPostedDocument('upload');
						if (InlineImage::IsImage($oDoc->GetMimeType())) {
							$aDimensions = null;
							$oDoc = InlineImage::ResizeImageToFit($oDoc, $aDimensions);
							/** @var InlineImage $oAttachment */
							$oAttachment = MetaModel::NewObject('InlineImage');
							$oAttachment->Set('expire', time() + MetaModel::GetConfig()->Get('draft_attachments_lifetime'));
							$oAttachment->Set('temp_id', $sTempId);
							$oAttachment->Set('item_class', $sObjClass);
							$oAttachment->SetDefaultOrgId();
							$oAttachment->Set('contents', $oDoc);
							$oAttachment->Set('secret', sprintf('%06x', mt_rand(0, 0xFFFFFF))); // something not easy to guess
							$iAttId = $oAttachment->DBInsert();

							$aResult['uploaded'] = 1;
							$aResult['msg'] = utils::EscapeHtml($oDoc->GetFileName());
							$aResult['fileName'] = $oDoc->GetFileName();
							$aResult['url'] = utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL.$iAttId.'&s='.$oAttachment->Get('secret');
							if (is_array($aDimensions)) {
								$aResult['width'] = $aDimensions['width'];
								$aResult['height'] = $aDimensions['height'];
							}

							IssueLog::Trace('InlineImage created', LogChannels::INLINE_IMAGE, array(
								'$operation'   => $operation,
								'$aResult'     => $aResult,
								'secret'       => $oAttachment->Get('secret'),
								'temp_id'      => $sTempId,
								'item_class'   => $sObjClass,
								'user'         => UserRights::GetUser(),
								'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
								'REQUEST_URI'  => @$_SERVER['REQUEST_URI'],
							));
						} else {
							$aResult['error'] = $oDoc->GetFileName().' is not a valid image format.';
						}
					}
					catch (FileUploadException $e) {
						$aResult['error'] = $e->GetMessage();
					}
				}
				$oPage->SetData($aResult);
				break;

			/** @noinspection PhpMissingBreakStatementInspection cke_upload_and_browse and cke_browse are chained */
			case 'cke_upload_and_browse':
				$sTempId = utils::ReadParam('temp_id', '', false, 'transaction_id');
				$sObjClass = utils::ReadParam('obj_class', '', false, 'class');
				try {
					$oDoc = utils::ReadPostedDocument('upload');
					$sDocMimeType = $oDoc->GetMimeType();
					if (!InlineImage::IsImage($sDocMimeType)) {
						LogErrorMessage('CKE : error when uploading image in ajax.render.php, not an image',
							array(
								'operation'   => 'cke_upload_and_browse',
								'class'       => $sObjClass,
								'ImgMimeType' => $sDocMimeType,
							));
					} else {
						$aDimensions = null;
						$oDoc = InlineImage::ResizeImageToFit($oDoc, $aDimensions);
						/** @var InlineImage $oAttachment */
						$oAttachment = MetaModel::NewObject('InlineImage');
						$oAttachment->Set('expire', time() + MetaModel::GetConfig()->Get('draft_attachments_lifetime'));
						$oAttachment->Set('temp_id', $sTempId);
						$oAttachment->Set('item_class', $sObjClass);
						$oAttachment->SetDefaultOrgId();
						$oAttachment->Set('contents', $oDoc);
						$oAttachment->Set('secret', sprintf('%06x', mt_rand(0, 0xFFFFFF))); // something not easy to guess
						$iAttId = $oAttachment->DBInsert();

						IssueLog::Trace('InlineImage created', LogChannels::INLINE_IMAGE, array(
							'$operation'   => $operation,
							'secret'       => $oAttachment->Get('secret'),
							'temp_id'      => $sTempId,
							'item_class'   => $sObjClass,
							'user'         => UserRights::GetUser(),
							'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
							'REQUEST_URI'  => @$_SERVER['REQUEST_URI'],
						));
					}

				}
				catch (FileUploadException $e) {
					LogErrorMessage('CKE : error when uploading image in ajax.render.php, exception occured',
						array(
							'operation'    => 'cke_upload_and_browse',
							'class'        => $sObjClass,
							'exceptionMsg' => $e,
						));
				}
			// Fall though !! => browse

			case 'cke_browse':
				$oPage = new NiceWebPage(Dict::S('UI:BrowseInlineImages'));
				$oPage->LinkStylesheetFromAppRoot('node_modules/magnific-popup/dist/magnific-popup.css');
				$oPage->LinkScriptFromAppRoot('node_modules/magnific-popup/dist/jquery.magnific-popup.min.js');
				$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
				$sImgUrl = $sAppRootUrl.INLINEIMAGE_DOWNLOAD_URL;

				/** @noinspection SuspiciousAssignmentsInspection cke_upload_and_browse and cke_browse are chained */
				$sTempId = utils::ReadParam('temp_id', '', false, 'transaction_id');
				$sClass = utils::ReadParam('obj_class', '', false, 'class');
				$iObjectId = utils::ReadParam('obj_key', 0, false, 'integer');
				$sCKEditorFuncNum = utils::ReadParam('CKEditorFuncNum', '');

				if (empty($sTempId)) {
					throw new SecurityException('Cannot access endpoint with empty temp_id parameter');
				}
				if (false === privUITransaction::IsTransactionValid($sTempId, false)) {
					throw new SecurityException('Access rejected');
				}
				if (false === MetaModel::IsValidClass($sClass)) {
					throw new CoreUnexpectedValue('Invalid object');
				}
				if ($iObjectId > 0) {
					// searching for object in the DB with a count query
					// using DBSearch so that user rights are applied !
					$oSearch = new DBObjectSearch($sClass);
					$oSearch->AddCondition(MetaModel::DBGetKey($sClass), $iObjectId, '=');
					$oSet = new CMDBObjectSet($oSearch);
					if (false === $oSet->CountExceeds(0)) {
						throw new SecurityException(Dict::S('UI:ObjectDoesNotExist'));
					}
				}

				$sPostUrl = $sAppRootUrl.'pages/ajax.render.php?CKEditorFuncNum='.$sCKEditorFuncNum;

				$oPage->add_style(
					<<<EOF
body {
	overflow: auto;
}
EOF
				);
				$sMaxUpload = InlineImage::GetMaxUpload();
				$sUploadLegend = Dict::S('UI:UploadInlineImageLegend');
				$sUploadLabel = Dict::S('UI:SelectInlineImageToUpload');
				$sAvailableImagesLegend = Dict::S('UI:AvailableInlineImagesLegend');
				$sInsertBtnLabel = Dict::S('UI:Button:Insert');
				$sNoInlineImage = Dict::S('UI:NoInlineImage');
				$oPage->add(
					<<<EOF
<div>
	<fieldset>
		<legend>$sUploadLegend</legend>
		<form method="post" id="upload_form" action="$sPostUrl" enctype="multipart/form-data">
			<input type="hidden" name="operation" value="cke_upload_and_browse">
			<input type="hidden" name="temp_id" value="$sTempId">
			<input type="hidden" name="obj_class" value="$sClass">
			<input type="hidden" name="obj_key" value="$iObjectId">
			$sUploadLabel <input id="upload_button" type="file" name="upload"> <span id="upload_status"> $sMaxUpload</span>
		</form>
	</fieldset>
</div>
EOF
				);

				$oPage->add_script(
					<<<EOF
        // Helper function to get parameters from the query string.
        function getUrlParam( paramName ) {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );
		
            return ( match && match.length > 1 ) ? match[1] : null;
        }
        // Simulate user action of selecting a file to be returned to CKEditor.
        function returnFileUrl(iAttId, sAltText, sSecret) {

            var funcNum = getUrlParam( 'CKEditorFuncNum' );
            var fileUrl = '$sImgUrl'+iAttId+'&s='+sSecret;
            window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl, function() {
                // Get the reference to a dialog window.
                var dialog = this.getDialog();
                // Check if this is the Image Properties dialog window.
                if ( dialog.getName() == 'image' ) {
                    // Get the reference to a text field that stores the "alt" attribute.
                    var element = dialog.getContentElement( 'info', 'txtAlt' );
                    // Assign the new value.
                    if ( element )
                        element.setValue(sAltText);
                }
                // Return "false" to stop further execution. In such case CKEditor will ignore the second argument ("fileUrl")
                // and the "onSelect" function assigned to the button that called the file manager (if defined).
                // return false;
            } );
            window.close();
        }
EOF
				);
				$oPage->add_ready_script(
					<<<EOF
$('#upload_button').on('change', function() {
	$('#upload_status').html('<img src="{$sAppRootUrl}images/indicator.gif">'); 
	$('#upload_form').submit();
	$(this).prop('disabled', true);
});
$('.img-picker').magnificPopup({type: 'image', closeOnContentClick: true });
EOF
				);
				$sOQL = "SELECT InlineImage WHERE ((temp_id = :temp_id) OR (item_class = :obj_class AND item_id = :obj_id))";
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array('temp_id' => $sTempId, 'obj_class' => $sClass, 'obj_id' => $iObjectId));
				$oPage->add("<div><fieldset><legend>$sAvailableImagesLegend</legend>");

				if ($oSet->Count() == 0) {
					$oPage->add("<p style=\"text-align:center\">$sNoInlineImage</p>");
				} else {
					while ($oAttachment = $oSet->Fetch()) {
						$oDoc = $oAttachment->Get('contents');
						if ($oDoc->GetMainMimeType() == 'image') {
							$sDocName = addslashes(utils::EscapeHtml($oDoc->GetFileName()));
							$iAttId = $oAttachment->GetKey();
							$sSecret = $oAttachment->Get('secret');
							$oPage->add("<div style=\"float:left;margin:1em;text-align:center;\"><img class=\"img-picker\" style=\"max-width:300px;cursor:zoom-in\" href=\"{$sImgUrl}{$iAttId}&s={$sSecret}\" alt=\"$sDocName\" title=\"$sDocName\" src=\"{$sImgUrl}{$iAttId}&s={$sSecret}\"><br/><button onclick=\"returnFileUrl($iAttId, '$sDocName', '$sSecret')\">$sInsertBtnLabel</button></div>");
						}
					}
				}
				$oPage->add("</fieldset></div>");
				break;

			/**
			 * @internal
			 * @deprecated 3.2.0 N°7552 Use object.search_for_mentions route instead
			 */
			case 'cke_mentions':
				$oController = new ObjectController();
				$oPage = $oController->OperationSearchForMentions();
				break;

			case 'custom_fields_update':
				$oPage = new JsonPage();
				$sAttCode = utils::ReadParam('attcode', '');
				$aRequestedFields = utils::ReadParam('requested_fields', array());
				$sRequestedFieldsFormPath = utils::ReadParam('form_path', '');
				$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');

				$aResult = array();

				try {
					$oWizardHelper = WizardHelper::FromJSON($sJson);
					$oObj = $oWizardHelper->GetTargetObject();

					/** @var \ormCustomFieldsValue $oOrmCustomFieldValue */
					$oOrmCustomFieldValue = $oObj->Get($sAttCode);
					$oForm = $oOrmCustomFieldValue->GetForm();
					$oSubForm = $oForm->FindSubForm($sRequestedFieldsFormPath);
					$oRenderer = new ConsoleFormRenderer($oSubForm);
					$aRenderRes = $oRenderer->Render($aRequestedFields);

					$aResult['form']['updated_fields'] = $aRenderRes;
				}
				catch (Exception $e) {
					$aResult['error'] = $e->getMessage();
				}
				$oPage->SetData($aResult);
				$oPage->SetOutputDataOnly(true);
				break;

			//--------------------------------
			// Preferences
			//--------------------------------
			/** @internal */
			case 'preferences.set_user_picture':
				$oPage = new JsonPage();
				try {
					$oController = new PreferencesController();
					$aResult = $oController->SetUserPicture();
					$aResult['success'] = true;
				}
				catch (Exception $oException) {
					$aResult = [
						'success'       => false,
						'error_message' => $oException->getMessage(),
					];
				}
				$oPage->SetData($aResult);
				break;

			//--------------------------------
			// Activity panel
			//--------------------------------
			/** @internal */
			case 'activity_panel.save_state':
				$oPage = new JsonPage();
				try {
					$oController = new ActivityPanelController();
					$oController->SaveState();
					$aResult = [
						'success' => true,
					];
				}
				catch (Exception $oException) {
					$aResult = [
						'success'       => false,
						'error_message' => $oException->getMessage(),
					];
				}
				$oPage->SetData($aResult);
				break;

			/** @internal */
			case 'activity_panel.add_caselog_entries':
				$oPage = new JsonPage();
				try {
					$oController = new ActivityPanelController();
					$aResult = $oController->AddCaseLogsEntries();
				}
				catch (Exception $oException) {
					$aResult = [
						'success'       => false,
						'error_message' => $oException->getMessage(),
					];
				}
				$oPage->SetData($aResult);
				break;

			/** @internal */
			case 'activity_panel.load_more_entries':
				$oPage = new JsonPage();
				try {
					$oController = new ActivityPanelController();
					$aResult = $oController->LoadMoreEntries();
				}
				catch (Exception $oException) {
					$aResult = [
						'success'       => false,
						'error_message' => $oException->getMessage(),
					];
				}
				$oPage->SetData($aResult);
				break;

			//--------------------------------
			// Navigation menu
			//--------------------------------
			case 'get_menus_count':
				$oPage = new JsonPage();
				$oPage->SetOutputDataOnly(true);
				$oAjaxRenderController->GetMenusCount($oPage);
				break;

			//--------------------------------
			// Object
			//--------------------------------
			/** @internal */
			case 'object.modify':
				$oController = new ObjectController();
				$oPage = $oController->OperationModify();
				break;

			//--------------------------------
			// WelcomePopupMenu
			//--------------------------------
			case 'welcome_popup.acknowledge_message':
				$oPage = new JsonPage();
				try {
					$oController = new WelcomePopupController();
					$oController->AcknowledgeMessage();
					$aResult = ['success' => true];
				}
				catch (Exception $oException) {
					$aResult = [
						'success'       => false,
						'error_message' => $oException->getMessage(),
					];
				}
				$oPage->SetData($aResult);
				break;

			default:
				$oPage->p("Invalid query.");
		}
	}
	$oKPI->ComputeAndReport('Data fetch and format');
	$oPage->output();
} catch (Exception $e) {
	// note: transform to cope with XSS attacks
	echo utils::EscapeHtml($e->GetMessage());
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}

function LogErrorMessage($sMsgPrefix, $aContextInfo) {
	$sCurrentUserLogin = UserRights::GetUser();
	$sContextInfo = urldecode(http_build_query($aContextInfo, '', ', '));
	$sErrorMessage = "$sMsgPrefix - User='$sCurrentUserLogin', $sContextInfo";
	IssueLog::Error($sErrorMessage);
}


<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashboard\Controller;

use appUserPreferences;
use Combodo\iTop\Application\Dashlet\DashletFactory;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletWrapper;
use Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboFormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\DownloadPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\PropertyType\Serializer\XMLSerializer;
use Combodo\iTop\Service\ServiceLocator\ServiceLocator;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use DOMException;
use Exception;
use IssueLog;
use MetaModel;
use ModelReflectionRuntime;
use RuntimeDashboard;
use SecurityException;
use UserRights;
use utils;

class DashboardController extends Controller
{
	public const ROUTE_NAMESPACE = 'dashboard';

	private FormBlockService $oFormBlockService;
	private XMLSerializer $oXMLSerializer;

	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [], array $aThemes = ['application/forms/itop_console_layout.html.twig', 'application/forms/wip_form_demonstrator.html.twig'])
	{
		parent::__construct($sViewPath, $sModuleName, $aAdditionalPaths, $aThemes);
		$this->oFormBlockService = MetaModel::GetService('FormBlockService');
		$this->oXMLSerializer = MetaModel::GetService('XMLSerializer');
	}

	public function OperationGetDashlet()
	{
		// TODO 3.3 Do we want to use a readparam here or SF internal mechanism ?
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		$sDashletId = utils::ReadParam('dashlet_id', '', false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
		// TODO 3.3 Check if raw data is the right call
		$sValues = utils::ReadParam('values', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aValues = !empty($sValues) ? json_decode($sValues, true) : [];

		$oPage = new AjaxPage('');

		if (is_a($sDashletClass, 'Dashlet', true)) {
			// TODO 3.3 Make a real unique id if none is provided
			$sDashletId = !empty($sDashletId) ? $sDashletId : uniqid();

			$oDashlet = DashletFactory::GetInstance()->CreateDashlet($sDashletClass, $sDashletId);

			if (!empty($aValues)) {
				$oDashlet->FromModelData($aValues);
			} else {
				$aValues = $oDashlet->GetModelData();
			}

			// TODO 3.3 Removing bEditMode for dashlet rendering fixes id having an "_edit" issues, but is it the right solution ?
			$oDashletBlock = $oDashlet->DoRender($oPage, false /* bEditMode */, false /* bEnclosingDiv */);

			if ($oDashletBlock instanceof iUIBlock) {
				// Wrap the dashlet
				// TODO 3.3 Re-normalize Dashlet's values instead of using user input
				$oDashletWrapper = new DashletWrapper($oDashletBlock, $sDashletClass, $oDashlet->GetID(), $aValues);
				$oPage->AddUiBlock($oDashletWrapper);
			}
		}

		return $oPage;
	}

	public function OperationGetDashletForm()
	{
		// TODO 3.3 Do we want to use a readparam here or SF internal mechanism ?
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		$sValues = utils::ReadParam('values', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aValues = !empty($sValues) ? json_decode($sValues, true) : [];

		$oPage = new AjaxPage('');

		$oForm = TurboFormUIBlockFactory::MakeForDashletConfiguration($sDashletClass, $aValues);
		$oButtonContainer = UIContentBlockUIBlockFactory::MakeStandard(null, ['ibo-dashlet-panel--form-container--buttons']);
		$oButtonContainer->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Cancel', 'dashboard_cancel'));
		$oButtonContainer->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Confirm', 'dashboard_submit', 'dashboard_submit', true));
		$oForm->AddSubBlock($oButtonContainer);
		$oPage->AddUiBlock($oForm);

		return $oPage;
	}

	public function OperationSave()
	{
		$sViewData = utils::ReadPostedParam('values', '', utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aViewData = !empty($sViewData) ? json_decode($sViewData, true, 20) : [];

		try {
			// Get the form block from the service (and the compiler)
			$oRequest = $this->getRequest();
			$oFormBlock = $this->oFormBlockService->GetFormBlockById('DashboardGrid', 'Dashboard');
			$oBuilder = $this->oFormFactoryBuilderService->GetFormBuilder($oFormBlock, $aViewData);
			$oForm = $oBuilder->getForm();
			$oForm->handleRequest($oRequest);
			// We are in the submit action, so we submit the form with the provided values
			$oForm->submit($aViewData);

			// TODO 3.3 Validate the form, it requires CSRF + stripping extra fields
			// See $oForm->getErrors(true) to get all errors
			if ($oForm->isSubmitted() && (true || $oForm->isValid())) {
				// Save XML
				$aModelData = $oForm->getData();
				$oDashboard = new RuntimeDashboard($aModelData['id']);
				$oDomNode = $oDashboard->CreateEmptyDashboard();
				$this->oXMLSerializer->Serialize($aModelData, $oDomNode, 'DashboardGrid', 'Dashboard');
				$sXml = $oDomNode->ownerDocument->saveXML();
				$oDashboard->PersistDashboard($sXml);
				$sStatus = 'ok';
				$sMessage = 'Dashboard saved';
			} else {
				$sStatus = 'error';
				$aFormErrors = $oForm->getErrors(true, true);
				$sMessage = $aFormErrors->__toString();
			}
		} catch (Exception $e) {
			IssueLog::Exception($e->getMessage(), $e);
			$sStatus = 'error';
			$sMessage = $e->getMessage();
		}

		$oPage = new JsonPage();
		$oPage->SetData([
			'status'  => $sStatus,
			'message' => $sMessage,
		]);
		$oPage->SetOutputDataOnly(true);

		return $oPage;
	}

	public function OperationExport()
	{
		$oPage = new DownloadPage('');
		$sDashboardId = utils::ReadParam('id', '', false, 'raw_data');
		$sDashboardFile = APPROOT.utils::ReadParam('file', '', false, 'raw_data');

		$sDashboardFileSanitized = utils::RealPath($sDashboardFile, APPROOT);
		if (false === $sDashboardFileSanitized) {
			throw new SecurityException('Invalid dashboard file !');
		}

		if (!appUserPreferences::GetPref('display_original_dashboard_'.$sDashboardId, false)) {
			// Search for an eventual user defined dashboard
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $sDashboardId, '=');
			$oUDSet = new DBObjectSet($oUDSearch);
			if ($oUDSet->Count() > 0) {
				// Assuming there is at most one couple {user, menu}!
				$oUserDashboard = $oUDSet->Fetch();
				$sDashboardDefinition = $oUserDashboard->Get('contents');
			} else {
				$sDashboardDefinition = @file_get_contents($sDashboardFileSanitized);
			}
		} else {
			$sDashboardDefinition = @file_get_contents($sDashboardFileSanitized);
		}

		$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFile, $sDashboardId);
		if (!is_null($oDashboard)) {
			$oPage->TrashUnexpectedOutput();
			$oPage->SetContentType('text/xml');
			$oPage->SetContentDisposition('attachment', 'dashboard_'.$oDashboard->GetTitle().'.xml');

			$oPage->add($sDashboardDefinition);
		}

		return $oPage;
	}

	public function OperationImport()
	{
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
		$aResult = ['error' => ''];
		if (!is_null($oDashboard)) {
			try {
				$oDoc = utils::ReadPostedDocument('dashboard_upload_file');
				$oDashboard->FromXml($oDoc->GetData());
				$oDashboard->PersistDashboard($oDoc->GetData());
			} catch (DOMException $e) {
				$aResult = ['error' => Dict::S('UI:Error:InvalidDashboardFile')];
			} catch (Exception $e) {
				$aResult = ['error' => $e->getMessage()];
			}
		} else {
			$aResult['error'] = 'Dashboard id="'.$sDashboardId.'" not found.';
		}
		$oPage->SetData($aResult);

		return $oPage;
	}
}

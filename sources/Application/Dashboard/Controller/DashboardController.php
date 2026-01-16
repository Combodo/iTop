<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashboard\Controller;

use Combodo\iTop\Application\Dashlet\DashletFactory;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletWrapper;
use Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboFormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\PropertyType\Serializer\XMLSerializer;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Exception;
use IssueLog;
use ModelReflectionRuntime;
use RuntimeDashboard;
use utils;

class DashboardController extends Controller
{
	public const ROUTE_NAMESPACE = 'dashboard';

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

			// TODO 3.3 This is not the place to register this service, do better please
			ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());
			if (!empty($aValues)) {
				$oDashlet->FromDenormalizedParams($aValues);
			} else {
				$aValues = $oDashlet->GetDenormalizedProperties();
			}

			$oDashletBlock = $oDashlet->DoRender($oPage, true /* bEditMode */, false /* bEnclosingDiv */);

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
		$sValues = utils::ReadParam('values', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aValues = !empty($sValues) ? json_decode($sValues, true, 20) : [];
		$sStatus = 'error';
		$sMessage = 'Unknown error';

		try {
			// Get the form block from the service (and the compiler)
			$oRequest = $this->getRequest();
			$oFormBlock = FormBlockService::GetInstance()->GetFormBlockById('DashboardGrid', 'Dashboard');
			$oBuilder = $this->GetFormBuilder($oFormBlock, $aValues);
			$oForm = $oBuilder->getForm();
			$oForm->handleRequest($oRequest);
			// We are in the submit action, so we submit the form with the provided values
			$oForm->submit($aValues);

			// TODO 3.3 Validate the form, it requires CSRF + stripping extra fields
			// See $oForm->getErrors(true) to get all errors
			if ($oForm->isSubmitted() && (true || $oForm->isValid())) {
				// Save XML
				$oDashboard = new RuntimeDashboard($aValues['id']);
				$oDomNode = $oDashboard->CreateEmptyDashboard();
				XMLSerializer::GetInstance()->Serialize($aValues, $oDomNode, 'DashboardGrid', 'Dashboard');
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
			'status' => $sStatus,
			'message' => $sMessage,
		]);
		$oPage->SetOutputDataOnly(true);
		return $oPage;
	}
}

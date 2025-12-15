<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\ItopSdkFormDemonstrator\Helper\ItopSdkFormDemonstratorLog;
use Exception;
use IssueLog;

class FormsController extends Controller
{
	public const ROUTE_NAMESPACE = 'forms';

	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [])
	{
		$sModuleName = 'core';
		$sViewPath = APPROOT.'templates/application/forms';
		parent::__construct($sViewPath, $sModuleName, $aAdditionalPaths);
	}

	public function OperationDashletConfiguration()
	{
		try {
			$oRequest = $this->getRequest();
			$sDashletId = $oRequest->query->get('dashlet_code');

			// Get the form block from the service (and the compiler)
			$oFormBlock = FormBlockService::GetInstance()->GetFormBlockById($sDashletId, 'Dashlet');
			$oBuilder = $this->GetFormBuilder($oFormBlock, []);
			$oForm = $oBuilder->getForm();
			$oForm->handleRequest($oRequest);

			if ($oForm->isSubmitted()) {
				if ($oForm->isValid()) {
					IssueLog::Info('form is valid');
				}

				// Compute blocks to redraw
				$this->HandleFormSubmitted($oFormBlock, $oForm);

				return;
			}

			//			$this->DisplayPage([
			//				'form' => $oForm->createView(),
			//				'sAction' => utils::GetAbsoluteUrlAppRoot().'pages/UI.php?route=forms.dashlet_configuration&dashlet_code='.urlencode($sDashletId),
			//			], 'itop_form');

		} catch (Exception $e) {
			ItopSdkFormDemonstratorLog::Exception($e->getMessage(), $e);
			$this->DisplayPage([
				'sControllerError' => $e->getMessage(),
			], 'itop_error_update', Controller::ENUM_PAGE_TYPE_TURBO_FORM_AJAX);

			return;
		}
	}
}

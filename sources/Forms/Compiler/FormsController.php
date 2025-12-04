<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Compiler;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\Forms\FormType\FormTypeHelper;
use Combodo\iTop\ItopSdkFormDemonstrator\Helper\ItopSdkFormDemonstratorLog;
use Exception;
use IssueLog;

class FormsController extends Controller
{
	public const ROUTE_NAMESPACE = 'forms';

	public function OperationDisplayForm()
	{
		try {
			// Get the form block from the service (and the compiler)
			$oFormBlock = FormBlockService::GetInstance()->GetFormBlockById('VerySimpleForm');
			$oBuilder = $this->GetFormBuilder($oFormBlock, []);
			$oForm = $oBuilder->getForm();
			$oForm->handleRequest($this->GetRequest());

			if ($oForm->isSubmitted()) {
				if ($oForm->isValid()) {
					IssueLog::Info('form is valid');
				}

				// Retrieve form data
				$aData = $oForm->getData();

				// Compute blocks to redraw
				$aBlocksToRedraw = FormTypeHelper::ComputeBlocksToRedraw($oFormBlock, $oForm, $aData['_turbo_trigger']);

				// Display turbo response
				$this->DisplayTurboAjaxPage($aBlocksToRedraw);

				return;
			}

			$this->DisplayPage([
				'form' => $oForm->createView(),
			], 'BasicForm');

		} catch (Exception $e) {
			ItopSdkFormDemonstratorLog::Exception($e->getMessage(), $e);
			$this->DisplayPage([
				'sError' => $e->getMessage(),
			], 'BasicForm');

			return;
		}
	}
}

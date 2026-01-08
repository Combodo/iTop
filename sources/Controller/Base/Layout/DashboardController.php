<?php

namespace Combodo\iTop\Controller\Base\Layout;

use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletWrapper;
use Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboFormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Controller\AbstractController;
use ModelReflectionRuntime;
use utils;

class DashboardController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'dashboard';

	public function OperationNewDashlet()
	{
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		$sDashletId = utils::ReadParam('dashlet_id', '', false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
		$oPage = new AjaxPage('');

		if (is_subclass_of($sDashletClass, 'Dashlet')) {
			// TODO 3.3 Make a real unique id if none is provided
			$sDashletId = !empty($sDashletId) ? $sDashletId : uniqid();

			$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);
			//$offset = $oPage->start_capture();
			$oDashletBlock = $oDashlet->DoRender($oPage, true /* bEditMode */, false /* bEnclosingDiv */);
			//$sHtml = addslashes($oPage->end_capture($offset));

			if ($oDashletBlock instanceof iUIBlock) {
				// Wrap the dashlet
				$oDashletWrapper = new DashletWrapper($oDashletBlock, $oDashlet->GetID(), $sDashletClass);
				$oPage->AddUiBlock($oDashletWrapper);
			}
		}

		return $oPage;
	}

	public function OperationGetDashletForm()
	{
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		$oPage = new AjaxPage('');

		$oUIBlock = TurboFormUIBlockFactory::MakeForDashletConfiguration($sDashletClass);
		$oUIBlock->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Confirm', 'dashboard_submit', 'dashboard_submit', true));
		$oUIBlock->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Cancel', 'dashboard_cancel'));
		$oPage->AddUiBlock($oUIBlock);

		return $oPage;
	}
}

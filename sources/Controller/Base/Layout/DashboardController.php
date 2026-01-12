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

	public function OperationGetDashlet()
	{
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		$sDashletId = utils::ReadParam('dashlet_id', '', false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
		// TODO 3.3 Check if raw data is the right call
		$sValues = utils::ReadParam('values', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aValues = !empty($sValues) ? json_decode($sValues, true) : [];

		$oPage = new AjaxPage('');

		if (is_subclass_of($sDashletClass, 'Dashlet')) {
			// TODO 3.3 Make a real unique id if none is provided
			$sDashletId = !empty($sDashletId) ? $sDashletId : uniqid();

			$oDashlet = new $sDashletClass(new ModelReflectionRuntime(), $sDashletId);

			// TODO 3.3 I'd like to update dashlet from frontend's normalized data
			// $oDashlet->FromNormalizedParams($aValues);

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
		$sDashletClass = utils::ReadParam('dashlet_class', '', false, utils::ENUM_SANITIZATION_FILTER_PHP_CLASS);
		// TODO 3.3 Do we want to use a readparam here or SF internal mechanism ?
		$sValues = utils::ReadParam('values', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aValues = !empty($sValues) ? json_decode($sValues, true) : [];

		$oPage = new AjaxPage('');

		$oUIBlock = TurboFormUIBlockFactory::MakeForDashletConfiguration($sDashletClass, $aValues);
		$oUIBlock->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction('Confirm', 'dashboard_submit', 'dashboard_submit', true));
		$oUIBlock->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction('Cancel', 'dashboard_cancel'));
		$oPage->AddUiBlock($oUIBlock);

		return $oPage;
	}
}

<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Dashboard\Layout;

use Combodo\iTop\Application\Dashlet\Service\DashletService;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardGrid;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout as DashboardLayoutUIBlock;

class DashboardLayoutGrid extends \DashboardLayout
{
	public function Render($oPage, $aDashlets, $bEditMode = false, array $aExtraParams = [])
	{
		$oDashboardLayout = new DashboardLayoutUIBlock($aExtraParams['dashboard_div_id']);

		$oDashboardGrid = new DashboardGrid();
		$oDashboardLayout->SetGrid($oDashboardGrid);
		foreach ($aDashlets as $aPosDashlet) {
			/** @var \Dashlet $oDashlet */
			$oDashlet = $aPosDashlet['dashlet'];
			if ($oDashlet::IsVisible()) {
				$sDashletId = $oDashlet->GetID();
				$sDashletClass = $oDashlet->GetDashletType();
				$aDashletDenormalizedProperties = $oDashlet->GetDenormalizedProperties();
				$aDashletsInfo = DashletService::GetInstance()->GetDashletDefinition($sDashletClass);

				// Also set minimal height/width
				$iPositionX = $aPosDashlet['position_x'];
				$iPositionY = $aPosDashlet['position_y'];
				$iWidth = max($aPosDashlet['width'], array_key_exists('min_width', $aDashletsInfo) ? $aDashletsInfo['min_width'] : 1);
				$iHeight = max($aPosDashlet['height'], array_key_exists('min_height', $aDashletsInfo) ? $aDashletsInfo['min_height'] : 1);
				$oDashboardGrid->AddDashlet($oDashlet->DoRender($oPage, $bEditMode, true /* bEnclosingDiv */, $aExtraParams), $sDashletId, $sDashletClass, $aDashletDenormalizedProperties, $iPositionX, $iPositionY, $iWidth, $iHeight);
			}
		}

		return $oDashboardLayout;
	}

	/**
	 * @inheritDoc
	 */
	public function GetDashletCoordinates($iCellIdx)
	{
		$iColNumber = $iCellIdx % 12;
		$iRowNumber = (int)floor($iCellIdx / 12);

		return [$iColNumber, $iRowNumber];
	}
}

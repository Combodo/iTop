<?php

// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
use Combodo\iTop\Application\Dashlet\Service\DashletService;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardColumn;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout as DashboardLayoutUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardRow;
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Dashboard presentation
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
abstract class DashboardLayout
{
	abstract public function Render($oPage, $aDashlets, $bEditMode = false, array $aExtraParams = []);

	/**
	 * @param int $iCellIdx
	 *
	 * @return array Containing 2 scalars: Col number and row number (starting from 0)
	 * @since 2.7.0
	 */
	abstract public function GetDashletCoordinates($iCellIdx);

	public static function GetInfo()
	{
		return [
			'label'       => '',
			'icon'        => '',
			'description' => '',
		];
	}
}

abstract class DashboardLayoutMultiCol extends DashboardLayout
{
	protected $iNbCols;

	public function __construct()
	{
		$this->iNbCols = 1;
	}

	protected function TrimCell($aDashlets)
	{
		$aKeys = array_reverse(array_keys($aDashlets));
		$idx = 0;
		$bNoVisibleFound = true;
		while ($idx < count($aKeys) && $bNoVisibleFound) {
			/** @var \Dashlet $oDashlet */
			$oDashlet = $aDashlets[$aKeys[$idx]];
			if ($oDashlet::IsVisible()) {
				$bNoVisibleFound = false;
			} else {
				unset($aDashlets[$aKeys[$idx]]);
			}
			$idx++;
		}

		return $aDashlets;
	}

	protected function TrimCellsArray($aCells)
	{
		foreach ($aCells as $key => $aDashlets) {
			$aCells[$key] = $this->TrimCell($aDashlets);
		}
		$aKeys = array_reverse(array_keys($aCells));
		$idx = 0;
		$bNoVisibleFound = true;
		while ($idx < count($aKeys) && $bNoVisibleFound) {
			$aDashlets = $aCells[$aKeys[$idx]];
			if (count($aDashlets) > 0) {
				$bNoVisibleFound = false;
			} else {
				unset($aCells[$aKeys[$idx]]);
			}
			$idx++;
		}

		return $aCells;

	}

	/**
	 * @param WebPage $oPage
	 * @param $aCells
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 */
	public function Render($oPage, $aCells, $bEditMode = false, $aExtraParams = [])
	{
		/** @var DashletService $oDashletService */
		$oDashletService = MetaModel::GetService('DashletService');
		// Trim the list of cells to remove the invisible/empty ones at the end of the array
		$aCells = $this->TrimCellsArray($aCells);

		$oDashboardLayout = new DashboardLayoutUIBlock($aExtraParams['dashboard_div_id']);

		$iCellIdx = 0;
		$iNbRows = ceil(count($aCells) / $this->iNbCols);

		// GRID LAYOUT: Global positioning
		$iGridCurrentX = 0;
		$iGridCurrentY = 0;
		$iGridColWidth = (int)(12 / $this->iNbCols);

		//Js given by each dashlet to reload
		$sJSReload = "";
		$oDashboardGrid = new \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardGrid();
		$oDashboardLayout->SetGrid($oDashboardGrid);
		for ($iRows = 0; $iRows < $iNbRows; $iRows++) {
			$oDashboardRow = new DashboardRow();

			// GRID LAYOUT: Store the maximum column Y in this row
			$iGridMaxColY = -1;

			for ($iCols = 0; $iCols < $this->iNbCols; $iCols++) {
				$oDashboardColumn = new DashboardColumn($bEditMode);
				$oDashboardColumn->SetCellIndex($iCellIdx);

				// GRID LAYOUT: Column positioning
				$iGridCurrentColX = 0;
				$iGridCurrentColY = 0;
				$iGridMaxHeightDashlet = -1;

				if (array_key_exists($iCellIdx, $aCells)) {
					$aDashlets = $aCells[$iCellIdx];
					if (count($aDashlets) > 0) {
						/** @var \Dashlet $oDashlet */
						foreach ($aDashlets as $oDashlet) {
							if ($oDashlet::IsVisible()) {
								$sDashletId = $oDashlet->GetID();
								$sDashletClass = $oDashlet->GetDashletType();
								$aDashletDenormalizedProperties = $oDashlet->GetModelData();
								$aDashletsInfo = $oDashletService->GetDashletDefinition($sDashletClass);

								// GRID LAYOUT: Set position relative to grid
								$iPositionX = $iGridCurrentX + $iGridCurrentColX;
								$iPositionY = $iGridCurrentY + $iGridCurrentColY;
								$iWidth = array_key_exists('preferred_width', $aDashletsInfo) ? $aDashletsInfo['preferred_width'] : 1;
								// GRID LAYOUT: Limit dashlet width to fit column width
								if ($iWidth > $iGridColWidth) {
									$iWidth = $iGridColWidth;
								}
								$iHeight = array_key_exists('preferred_height', $aDashletsInfo) ? $aDashletsInfo['preferred_height'] : 1;
								// GRID LAYOUT: Store max height of dashlets in this current column
								if ($iHeight > $iGridMaxHeightDashlet) {
									$iGridMaxHeightDashlet = $iHeight;
								}
								// GRID LAYOUT: Ensure that dashlet fits in the current row of the column
								if ($iGridCurrentColX + $iWidth > $iGridColWidth) {
									$iPositionX = $iGridCurrentX;
									$iPositionY++;
								}

								$oDashboardGrid->AddDashlet($oDashlet->DoRender($oPage, $bEditMode, true /* bEnclosingDiv */, $aExtraParams), $sDashletId, $sDashletClass, $aDashletDenormalizedProperties, $iPositionX, $iPositionY, $iWidth, $iHeight);

								// GRID LAYOUT: Update column cursor
								$iGridCurrentColX += $iWidth;
								if ($iGridCurrentColX >= $iGridColWidth) {
									$iGridCurrentColX = 0;
									$iGridCurrentColY += $iGridMaxHeightDashlet;
									$iGridMaxHeightDashlet = -1;
								}

								//$oDashboardColumn->AddUIBlock($oDashlet->DoRender($oPage, $bEditMode, true /* bEnclosingDiv */, $aExtraParams));
							}
						}
					} else {
						$oDashboardColumn->AddUIBlock(new Html('&nbsp;'));
					}
				} else {
					$oDashboardColumn->AddUIBlock(new Html('&nbsp;'));
				}
				$iCellIdx++;

				// GRID LAYOUT: Store max y in this current row
				if ($iGridCurrentColY > $iGridMaxColY) {
					$iGridMaxColY = $iGridCurrentColY;
				}

				// GRID LAYOUT: Next column
				$iGridCurrentX += $iGridColWidth;

			}

			// GRID LAYOUT: Next Row
			$iGridCurrentY += ($iGridMaxColY + 1);
			$iGridCurrentX = 0;

			$sJSReload .= $oDashboardRow->GetJSRefreshCallback()." ";
		}

		// TODO 3.3 We can probably do better with the new dashboard
		$oPage->add_script("function updateDashboard".$aExtraParams['dashboard_div_id']."(){".$sJSReload."}");

		if ($bEditMode) { // Add one row for extensibility
			$oDashboardRow = new DashboardRow();
			$oDashboardLayout->AddDashboardRow($oDashboardRow);

			for ($iCols = 0; $iCols < $this->iNbCols; $iCols++) {
				$oDashboardColumn = new DashboardColumn($bEditMode, true);
				$oDashboardRow->AddDashboardColumn($oDashboardColumn);
				$oDashboardColumn->AddUIBlock(new Html('&nbsp;'));
			}
		}

		return $oDashboardLayout;
	}

	/**
	 * @inheritDoc
	 */
	public function GetDashletCoordinates($iCellIdx)
	{
		$iColNumber = (int)$iCellIdx % $this->iNbCols;
		$iRowNumber = (int)floor($iCellIdx / $this->iNbCols);

		return [$iColNumber, $iRowNumber];
	}
}

class DashboardLayoutOneCol extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 1;
	}

	public static function GetInfo()
	{
		return [
			'label'       => 'One Column',
			'icon'        => 'images/layout_1col.png',
			'description' => '',
		];
	}
}

class DashboardLayoutTwoCols extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 2;
	}

	public static function GetInfo()
	{
		return [
			'label'       => 'Two Columns',
			'icon'        => 'images/layout_2col.png',
			'description' => '',
		];
	}
}

class DashboardLayoutThreeCols extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 3;
	}

	public static function GetInfo()
	{
		return [
			'label'       => 'Two Columns',
			'icon'        => 'images/layout_3col.png',
			'description' => '',
		];
	}
}

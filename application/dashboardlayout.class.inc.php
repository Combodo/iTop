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
	abstract public function Render($oPage, $aDashlets, $bEditMode = false);

	/**
	 * @param int $iCellIdx
	 *
	 * @return array Containing 2 scalars: Col number and row number (starting from 0)
	 * @since 2.7.0
	 */
	abstract public function GetDashletCoordinates($iCellIdx);
	
	public static function GetInfo()
	{
		return array(
			'label' => '',
			'icon' => '',
			'description' => '',
		);
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
		while($idx < count($aKeys) && $bNoVisibleFound)
		{
			/** @var \Dashlet $oDashlet */
			$oDashlet = $aDashlets[$aKeys[$idx]];
			if ($oDashlet::IsVisible())
			{
				$bNoVisibleFound = false;
			}
			else
			{
				unset($aDashlets[$aKeys[$idx]]);
			}
			$idx++;
		}
		return $aDashlets;
	}
	
	protected function TrimCellsArray($aCells)
	{
		foreach($aCells as $key => $aDashlets)
		{
			$aCells[$key] = $this->TrimCell($aDashlets);
		}
		$aKeys = array_reverse(array_keys($aCells));
		$idx = 0;
		$bNoVisibleFound = true;
		while($idx < count($aKeys) && $bNoVisibleFound)
		{
			$aDashlets = $aCells[$aKeys[$idx]];
			if (count($aDashlets) > 0)
			{
				$bNoVisibleFound = false;
			}
			else
			{
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
	public function Render($oPage, $aCells, $bEditMode = false, $aExtraParams = array())
	{
		// Trim the list of cells to remove the invisible/empty ones at the end of the array
		$aCells = $this->TrimCellsArray($aCells);

		$oDashboardLayout = new DashboardLayoutUIBlock();
		//$oPage->AddUiBlock($oDashboardLayout);

		$iCellIdx = 0;
		$iNbRows = ceil(count($aCells) / $this->iNbCols);

		//Js given by each dashlet to reload
		$sJSReload = "";

		for ($iRows = 0; $iRows < $iNbRows; $iRows++) {
			$oDashboardRow = new DashboardRow();
			$oDashboardLayout->AddDashboardRow($oDashboardRow);

			for ($iCols = 0; $iCols < $this->iNbCols; $iCols++) {
				$oDashboardColumn = new DashboardColumn($bEditMode);
				$oDashboardColumn->SetCellIndex($iCellIdx);
				$oDashboardRow->AddDashboardColumn($oDashboardColumn);

				if (array_key_exists($iCellIdx, $aCells)) {
					$aDashlets = $aCells[$iCellIdx];
					if (count($aDashlets) > 0) {
						/** @var \Dashlet $oDashlet */
						foreach ($aDashlets as $oDashlet) {
							if ($oDashlet::IsVisible()) {
								$oDashboardColumn->AddUIBlock($oDashlet->DoRender($oPage, $bEditMode, true /* bEnclosingDiv */, $aExtraParams));
							}
						}
					} else {
						$oDashboardColumn->AddUIBlock(new Html('&nbsp;'));
					}
				} else {
					$oDashboardColumn->AddUIBlock(new Html('&nbsp;'));
				}
				$iCellIdx++;
			}
			$sJSReload .= $oDashboardRow->GetJSRefreshCallback()." ";
		}

		$oPage->add_script("function updateDashboard".$aExtraParams['dashboard_div_id']."(){".$sJSReload."}");

		if ($bEditMode) // Add one row for extensibility
		{
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
		$iColNumber = (int) $iCellIdx % $this->iNbCols;
		$iRowNumber = (int) floor($iCellIdx / $this->iNbCols);

		return array($iColNumber, $iRowNumber);
	}
}

class DashboardLayoutOneCol extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 1;
	}
	static public function GetInfo()
	{
		return array(
			'label' => 'One Column',
			'icon' => 'images/layout_1col.png',
			'description' => '',
		);
	}
}

class DashboardLayoutTwoCols extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 2;
	}
	static public function GetInfo()
	{
		return array(
			'label' => 'Two Columns',
			'icon' =>  'images/layout_2col.png',
			'description' => '',
		);
	}
}

class DashboardLayoutThreeCols extends DashboardLayoutMultiCol
{
	public function __construct()
	{
		parent::__construct();
		$this->iNbCols = 3;
	}
	static public function GetInfo()
	{
		return array(
			'label' => 'Two Columns',
			'icon' =>  'images/layout_3col.png',
			'description' => '',
		);
	}
}
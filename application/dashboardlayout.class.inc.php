<?php
// Copyright (C) 2010-2012 Combodo SARL
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

/**
 * Dashboard presentation
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */ 


abstract class DashboardLayout
{
	public function __construct()
	{
		
	}
	
	abstract public function Render($oPage, $aDashlets, $bEditMode = false);
	
	static public function GetInfo()
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
			$oDashlet = $aDashlets[$aKeys[$idx]];
			if ($oDashlet->IsVisible())
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
	
	public function Render($oPage, $aCells, $bEditMode = false, $aExtraParams = array())
	{
		// Trim the list of cells to remove the invisible/empty ones at the end of the array
		$aCells = $this->TrimCellsArray($aCells);
		
		$oPage->add('<table style="width:100%"><tbody>');
		$iCellIdx = 0;
		$fColSize = 100 / $this->iNbCols;
		$sStyle = $bEditMode ? 'border: 1px #ccc dashed; width:'.$fColSize.'%;' : 'width: '.$fColSize.'%;';
		$sClass = $bEditMode ? 'layout_cell edit_mode' : 'dashboard';
		$iNbRows = ceil(count($aCells) / $this->iNbCols);
		for($iRows = 0; $iRows < $iNbRows; $iRows++)
		{
			$oPage->add('<tr>');
			for($iCols = 0; $iCols < $this->iNbCols; $iCols++)
			{
				$sCellClass = ($iRows == $iNbRows-1) ? $sClass.' layout_last_used_rank' : $sClass;
				$oPage->add("<td style=\"$sStyle\" class=\"$sCellClass\" data-dashboard-cell-index=\"$iCellIdx\">");
				if (array_key_exists($iCellIdx, $aCells))
				{
					$aDashlets = $aCells[$iCellIdx];
					if (count($aDashlets) > 0)
					{
						foreach($aDashlets as $oDashlet)
						{
							if ($oDashlet->IsVisible())
							{
								$oDashlet->DoRender($oPage, $bEditMode, true /* bEnclosingDiv */, $aExtraParams);
							}
						}
					}
					else
					{
						$oPage->add('&nbsp;');
					}
				}
				else
				{
					$oPage->add('&nbsp;');
				}
				$oPage->add('</td>');
				$iCellIdx++;
			}
			$oPage->add('</tr>');
		}
		if ($bEditMode) // Add one row for extensibility
		{
			$sStyle = 'style="border: 1px #ccc dashed; width:'.$fColSize.'%;" class="layout_cell edit_mode layout_extension" data-dashboard-cell-index="'.$iCellIdx.'"';
			$oPage->add('<tr>');
			for($iCols = 0; $iCols < $this->iNbCols; $iCols++)
			{
				$oPage->add("<td $sStyle>");
				$oPage->add('&nbsp;');
				$oPage->add('</td>');
			}
			$oPage->add('</tr>');
		}
		$oPage->add('</tbody></table>');
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
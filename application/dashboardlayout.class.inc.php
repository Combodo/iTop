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
	abstract public function Render($oPage, $aDashlets, $bEditMode = false);
	
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

	/**
	 *  N°2634 : we must have a unique id per dashlet !
	 * To avoid collision with other dashlets with the same ID we prefix it with row/cell id
	 * Collisions typically happen with extensions.
	 *
	 * @param boolean $bIsCustomized
	 * @param string $sDashboardDivId
	 * @param int $iRow
	 * @param int $iCell
	 * @param string $sDashletIdOrig
	 *
	 * @return string
	 *
	 * @since 2.7.0 N°2735
	 */
	public static function GetDashletUniqueId($bIsCustomized, $sDashboardDivId, $iRow, $iCell, $sDashletIdOrig)
	{
		if(strpos($sDashletIdOrig, 'IDrow') !== false)
		{
			return $sDashletIdOrig;
		}

		$sDashletId = $sDashboardDivId."_IDrow$iRow-col$iCell-$sDashletIdOrig";
		if ($bIsCustomized)
		{
			$sDashletId = 'CUSTOM_'.$sDashletId;
		}

		return $sDashletId;
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
	 * @param \WebPage $oPage
	 * @param $aCells
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 */
	public function Render($oPage, $aCells, $bEditMode = false, $aExtraParams = array())
	{
		// Trim the list of cells to remove the invisible/empty ones at the end of the array
		$aCells = $this->TrimCellsArray($aCells);

		$oPage->add('<table style="width:100%;table-layout:fixed;"><tbody>');
		$iCellIdx = 0;
		$fColSize = 100 / $this->iNbCols;
		$sStyle = $bEditMode ? 'border: 1px #ccc dashed; width:'.$fColSize.'%;' : 'width: '.$fColSize.'%;';
		$sClass = $bEditMode ? 'layout_cell edit_mode' : 'dashboard';
		$iNbRows = ceil(count($aCells) / $this->iNbCols);

		for($iRows = 0; $iRows < $iNbRows; $iRows++)
		{
			$oPage->add("<tr data-dashboard-row-index=\"$iRows\">");
			for($iCols = 0; $iCols < $this->iNbCols; $iCols++)
			{
				$sCellClass = ($iRows == $iNbRows-1) ? $sClass.' layout_last_used_rank' : $sClass;
				$oPage->add("<td style=\"$sStyle\" class=\"$sCellClass\" data-dashboard-cell-index=\"$iCellIdx\">");
				if (array_key_exists($iCellIdx, $aCells))
				{
					$aDashlets = $aCells[$iCellIdx];
					if (count($aDashlets) > 0)
					{
						/** @var \Dashlet $oDashlet */
						foreach($aDashlets as $oDashlet)
						{
							if ($oDashlet::IsVisible())
							{
								$sDashletIdOrig = $oDashlet->GetID();
								$sDashboardDivId = $aExtraParams['dashboard_div_id'];
								$bIsCustomized = (array_key_exists('bCustomized', $aExtraParams) && ((bool)$aExtraParams['bCustomized']) === true);
								$sDashletId = self::GetDashletUniqueId($bIsCustomized, $sDashboardDivId, $iRows, $iCols, $sDashletIdOrig);
								$oDashlet->SetID($sDashletId);
								$this->UpdateDashletsUserPrefs($oDashlet, $sDashletIdOrig, $aExtraParams);
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
			$oPage->add("<tr data-dashboard-row-index=\"$iRows\">");
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

	/**
	 * Migrate dashlet specific prefs to new format
	 *      Before 2.7.0 we were using the same for dashboard menu or dashboard attributes, standard or custom :
	 *          <alias>-<class>|Dashlet<idx_dashlet>
	 *      Since 2.7.0 it is the following, with a "CUSTOM_" prefix if necessary :
	 *          * dashboard menu : <dashboard_id>_IDrow<row_idx>-col<col_idx>-<dashlet_idx>
	 *          * dashboard attribute : <class>__<attcode>_IDrow<row_idx>-col<col_idx>-<dashlet_idx>
	 *
	 * @param \Dashlet $oDashlet
	 * @param string $sDashletIdOrig
	 *
	 * @param array $aExtraParams
	 *
	 * @since 2.7.0 N°2735
	 */
	private function UpdateDashletsUserPrefs(\Dashlet $oDashlet, $sDashletIdOrig, array $aExtraParams)
	{
		$bIsDashletWithListPref = ($oDashlet instanceof  DashletObjectList);
		if (!$bIsDashletWithListPref)
		{
			return;
		}
		/** @var \DashletObjectList $oDashlet */

		$bDashletIdInNewFormat = ($sDashletIdOrig === $oDashlet->GetID());
		if ($bDashletIdInNewFormat)
		{
			return;
		}

		$sNewPrefKey = $this->GetDashletAppUserPrefPrefix($oDashlet, $aExtraParams, $oDashlet->GetID());
		$sPrefValueForNewKey = appUserPreferences::GetPref($sNewPrefKey, null);
		$bHasPrefInNewFormat = ($sPrefValueForNewKey !== null);
		if ($bHasPrefInNewFormat)
		{
			return;
		}

		$sOldPrefKey = $this->GetDashletAppUserPrefPrefix($oDashlet, $aExtraParams, $sDashletIdOrig);
		$sPrefValueForOldKey = appUserPreferences::GetPref($sOldPrefKey, null);
		$bHasPrefInOldFormat = ($sPrefValueForOldKey !== null);
		if (!$bHasPrefInOldFormat)
		{
			return;
		}

		appUserPreferences::SetPref($sNewPrefKey, $sPrefValueForOldKey);
		appUserPreferences::UnsetPref($sOldPrefKey);
	}

	private function GetDashletAppUserPrefPrefix(\DashletObjectList $oDashlet, array $aExtraParams, $sDashletId)
	{
		$sDataTableId = DashletObjectList::APPUSERPREFERENCE_TABLE_PREFIX.$sDashletId;
		$oFilter = $oDashlet->GetDBSearch($aExtraParams);
		$aClassAliases = $oFilter->GetSelectedClasses();
		return DataTableSettings::GetAppUserPreferenceKey($aClassAliases, $sDataTableId);
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
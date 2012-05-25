<?php
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
		$sStyle = $bEditMode ? 'style="border: 1px #ccc dashed; width:'.$fColSize.'%;" class="layout_cell edit_mode"' : 'style="width: '.$fColSize.'%;" class="dashboard"';
		$iNbRows = ceil(count($aCells) / $this->iNbCols);
		for($iRows = 0; $iRows < $iNbRows; $iRows++)
		{
			$oPage->add('<tr>');
			for($iCols = 0; $iCols < $this->iNbCols; $iCols++)
			{
				$oPage->add("<td $sStyle>");
				if (array_key_exists($iCellIdx, $aCells))
				{
					$aDashlets = $aCells[$iCellIdx];
					if (count($aDashlets) > 0)
					{
						foreach($aDashlets as $oDashlet)
						{
							if ($oDashlet->IsVisible())
							{
								$oDashlet->DoRender($oPage, $bEditMode, $aExtraParams);
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
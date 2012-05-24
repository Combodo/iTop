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
	
	public function Render($oPage, $aDashlets, $bEditMode = false, $aExtraParams = array())
	{
		// Trim the list of dashlets to remove the invisible ones at the end of the array
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
		
		$oPage->add('<table style="width:100%"><tbody>');
		$iDashletIdx = 0;
		$fColSize = 100 / $this->iNbCols;
		$sStyle = $bEditMode ? 'style="border: 1px #ccc dashed; width:'.$fColSize.'%;" class="layout_cell edit_mode"' : 'style="width: '.$fColSize.'%;  "';
		$iNbRows = ceil(count($aDashlets) / $this->iNbCols);
		for($iRows = 0; $iRows < $iNbRows; $iRows++)
		{
			$oPage->add('<tr>');
			for($iCols = 0; $iCols < $this->iNbCols; $iCols++)
			{
				$oPage->add("<td $sStyle>");
				if (array_key_exists($iDashletIdx, $aDashlets))
				{
					$oDashlet = $aDashlets[$iDashletIdx];
					if ($oDashlet->IsVisible())
					{
						$oDashlet->DoRender($oPage, $bEditMode, $aExtraParams);
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
				$iDashletIdx++;
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
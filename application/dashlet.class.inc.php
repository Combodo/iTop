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

require_once(APPROOT.'application/forms.class.inc.php');

/**
 * Base class for all 'dashlets' (i.e. widgets to be inserted into a dashboard)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
abstract class Dashlet
{
	protected $oModelReflection;
	protected $sId;
	protected $bRedrawNeeded;
	protected $bFormRedrawNeeded;
	protected $aProperties; // array of {property => value}
	protected $aCSSClasses;
	
	public function __construct(ModelReflection $oModelReflection, $sId)
	{
		$this->oModelReflection = $oModelReflection;
		$this->sId = $sId;
		$this->bRedrawNeeded = true; // By default: redraw each time a property changes
		$this->bFormRedrawNeeded = false; // By default: no need to redraw the form (independent fields)
		$this->aProperties = array(); // By default: there is no property
		$this->aCSSClasses = array('dashlet');
	}

	// Assuming that a property has the type of its default value, set in the constructor
	//
	public function Str2Prop($sProperty, $sValue)
	{
		$refValue = $this->aProperties[$sProperty];
		$sRefType = gettype($refValue);
		if ($sRefType == 'boolean')
		{
			$ret = ($sValue == 'true');
		}
		elseif ($sRefType == 'array')
		{
			$ret = explode(',', $sValue);
		}
		else
		{
			$ret = $sValue;
			settype($ret, $sRefType);
		}
		return $ret;
	}

	public function Prop2Str($value)
	{
		$sType = gettype($value);
		if ($sType == 'boolean')
		{
			$sRet = $value ? 'true' : 'false';
		}
		elseif ($sType == 'array')
		{
			$sRet = implode(',', $value);
		}
		else
		{
			$sRet = (string) $value;
		}
		return $sRet;
	}

	public function FromDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$this->oDOMNode = $oDOMNode->getElementsByTagName($sProperty)->item(0);
			if ($this->oDOMNode != null)
			{
				$newvalue = $this->Str2Prop($sProperty, $this->oDOMNode->textContent);
				$this->aProperties[$sProperty] = $newvalue;
			}
		}
	}

	public function ToDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$sXmlValue = $this->Prop2Str($value);
			$oPropNode = $oDOMNode->ownerDocument->createElement($sProperty, $sXmlValue);
			$oDOMNode->appendChild($oPropNode);
		}
	}
	
	public function FromXml($sXml)
	{
		$oDomDoc = new DOMDocument('1.0', 'UTF-8');
		$oDomDoc->loadXml($sXml);
		$this->FromDOMNode($oDomDoc->firstChild);
	}
	
	public function FromParams($aParams)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			if (array_key_exists($sProperty, $aParams))
			{
				$this->aProperties[$sProperty] = $aParams[$sProperty];
			}
		}
	}
	
	public function DoRender($oPage, $bEditMode = false, $bEnclosingDiv = true, $aExtraParams = array())
	{
		$sCSSClasses = implode(' ', $this->aCSSClasses);
		$sId = $this->GetID();
		if ($bEnclosingDiv)
		{
			if ($bEditMode)
			{
				$oPage->add('<div class="'.$sCSSClasses.'" id="dashlet_'.$sId.'">');
			}
			else
			{
				$oPage->add('<div class="'.$sCSSClasses.'">');
			}
		}
		
		try
		{
			$this->Render($oPage, $bEditMode, $aExtraParams);
		}
		catch(UnknownClassOqlException $e)
		{
			// Maybe the class is part of a non-installed module, fail silently
			// Except in Edit mode
			if ($bEditMode)
			{
				$oPage->add('<div class="dashlet-content">');
				$oPage->add('<h2>'.$e->GetUserFriendlyDescription().'</h2>');
				$oPage->add('</div>');
			}
		}
		catch(OqlException $e)
		{
			$oPage->add('<div class="dashlet-content">');
			$oPage->p($e->GetUserFriendlyDescription());
			$oPage->add('</div>');
		}
		catch(Exception $e)
		{
			$oPage->add('<div class="dashlet-content">');
			$oPage->p($e->getMessage());
			$oPage->add('</div>');
		}
		
		if ($bEnclosingDiv)
		{
			$oPage->add('</div>');
		}
		
		if ($bEditMode)
		{
			$sClass = get_class($this);
			$oPage->add_ready_script(
<<<EOF
$('#dashlet_$sId').dashlet({dashlet_id: '$sId', dashlet_class: '$sClass'});
EOF
			);
		}
	}
	
	public function SetID($sId)
	{
		$this->sId = $sId;
	}
	
	public function GetID()
	{
		return $this->sId;
	}
	
	abstract public function Render($oPage, $bEditMode = false, $aExtraParams = array());
		
	abstract public function GetPropertiesFields(DesignerForm $oForm);
	
	public function ToXml(DOMNode $oContainerNode)
	{
		
	}

	public function Update($aValues, $aUpdatedFields)
	{
		foreach($aUpdatedFields as $sProp)
		{
			if (array_key_exists($sProp, $this->aProperties))
			{
				$this->aProperties[$sProp] = $aValues[$sProp];
			}
		}
		return $this;
	}
	

	public function IsRedrawNeeded()
	{
		return $this->bRedrawNeeded;
	}
	
	public function IsFormRedrawNeeded()
	{
		return $this->bFormRedrawNeeded;
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => '',
			'icon' => '',
			'description' => '',
		);
	}
	
	public function GetForm()
	{
		$oForm = new DesignerForm();
		$oForm->SetPrefix("dashlet_". $this->GetID());
		$oForm->SetParamsContainer('params');
		
		$this->GetPropertiesFields($oForm);
		
		$oDashletClassField = new DesignerHiddenField('dashlet_class', '', get_class($this));
		$oForm->AddField($oDashletClassField);
		
		$oDashletIdField = new DesignerHiddenField('dashlet_id', '', $this->GetID());
		$oForm->AddField($oDashletIdField);
		
		return $oForm;
	}
	
	static public function IsVisible()
	{
		return true;
	}
	
	static public function CanCreateFromOQL()
	{
		return false;
	}
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		// Default: do nothing since it's not supported
	}
}

class DashletEmptyCell extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('&nbsp;');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Empty Cell',
			'icon' => 'images/dashlet-text.png',
			'description' => 'Empty Cell Dashlet Placeholder',
		);
	}
	
	static public function IsVisible()
	{
		return false;
	}
}

class DashletPlainText extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['text'] = Dict::S('UI:DashletPlainText:Prop-Text:Default');
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sText = htmlentities($this->aProperties['text'], ENT_QUOTES, 'UTF-8');

		$sId = 'plaintext_'.($bEditMode? 'edit_' : '').$this->sId;
		$oPage->add('<div id='.$sId.'" class="dashlet-content">'.$sText.'</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerLongTextField('text', Dict::S('UI:DashletPlainText:Prop-Text'), $this->aProperties['text']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletPlainText:Label'),
			'icon' => 'images/dashlet-text.png',
			'description' => Dict::S('UI:DashletPlainText:Description'),
		);
	}
}

class DashletObjectList extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['menu'] = false;
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sShowMenu = $this->aProperties['menu'] ? '1' : '0';

		$oPage->add('<div class="dashlet-content">');
		$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
		if ($sHtmlTitle != '')
		{
			$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery);
		$oBlock = new DisplayBlock($oFilter, 'list');
		$aExtraParams = array(
			'menu' => $sShowMenu,
			'table_id' => 'Dashlet'.$this->sId,
		);
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
	}

	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletObjectList:Label'),
			'icon' => 'images/dashlet-list.png',
			'description' => Dict::S('UI:DashletObjectList:Description'),
		);
	}
	
	static public function CanCreateFromOQL()
	{
		return true;
	}
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
	}
}

abstract class DashletGroupBy extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['style'] = 'table';
	}

	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$sStyle = $this->aProperties['style'];

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further 
		$oFilter = DBObjectSearch::FromOQL($sQuery);

		$sClass = $oFilter->GetClass();
		$sClassAlias = $oFilter->GetClassAlias();

		// Check groupby... it can be wrong at this stage
		if (preg_match('/^(.*):(.*)$/', $sGroupBy, $aMatches))
		{
			$sAttCode = $aMatches[1];
			$sFunction = $aMatches[2];
		}
		else
		{
			$sAttCode = $sGroupBy;
			$sFunction = null;
		}
		if (!$this->oModelReflection->IsValidAttCode($sClass, $sAttCode))
		{
			$oPage->add('<p>'.Dict::S('UI:DashletGroupBy:MissingGroupBy').'</p>');
		}
		else
		{
			$sAttLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
			if (!is_null($sFunction))
			{
				$sFunction = $aMatches[2];
				switch($sFunction)
				{
				case 'hour':
					$sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Hour', $sAttLabel);
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%H')"; // 0 -> 23
					break;

				case 'month':
					$sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Month', $sAttLabel);
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%Y-%m')"; // yyyy-mm
					break;

				case 'day_of_week':
					$sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfWeek', $sAttLabel);
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%w')";
					break;

				case 'day_of_month':
					$sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfMonth', $sAttLabel);
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%Y-%m-%d')"; // mm-dd
					break;

				default:
					$sGroupByLabel = 'Unknown group by function '.$sFunction;
					$sGroupByExpr = $sClassAlias.'.'.$sAttCode;
				}
			}
			else
			{
				$sGroupByExpr = $sClassAlias.'.'.$sAttCode;
				$sGroupByLabel = $sAttLabel;
			}

			switch($sStyle)
			{
			case 'bars':
				$sType = 'open_flash_chart';
				$aExtraParams = array(
					'chart_type' => 'bars',
					'chart_title' => $sTitle,
					'group_by' => $sGroupByExpr,
					'group_by_label' => $sGroupByLabel,
				);
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'pie':
				$sType = 'open_flash_chart';
				$aExtraParams = array(
					'chart_type' => 'pie',
					'chart_title' => $sTitle,
					'group_by' => $sGroupByExpr,
					'group_by_label' => $sGroupByLabel,
				);
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'table':
			default:
				$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
				$sType = 'count';
				$aExtraParams = array(
					'group_by' => $sGroupByExpr,
					'group_by_label' => $sGroupByLabel,
				);
				break;
			}
	
			$oPage->add('<div style="text-align:center" class="dashlet-content">');
			if ($sHtmlTitle != '')
			{
				$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
			}
			$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
			$oBlock = new DisplayBlock($oFilter, $sType);
			$oBlock->Display($oPage, $sBlockId, $aExtraParams);
			$oPage->add('</div>');
		}
	}

	protected function GetGroupByOptions($sOql)
	{
		$oSearch = DBObjectSearch::FromOQL($sOql);
		$sClass = $oSearch->GetClass();
		$aGroupBy = array();
		foreach($this->oModelReflection->ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->IsScalar()) continue; // skip link sets
			if ($oAttDef instanceof AttributeFriendlyName) continue;
			if ($oAttDef instanceof AttributeExternalField) continue;

			$sLabel = $oAttDef->GetLabel();
			$aGroupBy[$sAttCode] = $sLabel;

			if ($oAttDef instanceof AttributeDateTime)
			{
				$aGroupBy[$sAttCode.':hour'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Hour', $sLabel);
				$aGroupBy[$sAttCode.':month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-Month', $sLabel);
				$aGroupBy[$sAttCode.':day_of_week'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek', $sLabel);
				$aGroupBy[$sAttCode.':day_of_month'] = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth', $sLabel);
			}
		}
		asort($aGroupBy);
		return $aGroupBy;
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		try
		{
			// Group by field: build the list of possible values (attribute codes + ...)
			$aGroupBy = $this->GetGroupByOptions($this->aProperties['query']);
	
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		}
		catch(Exception $e)
		{
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);

		$aStyles = array(
			'pie' => Dict::S('UI:DashletGroupByPie:Label'),
			'bars' => Dict::S('UI:DashletGroupByBars:Label'),
			'table' => Dict::S('UI:DashletGroupByTable:Label'),
		);
		
		$oField = new DesignerComboField('style', Dict::S('UI:DashletGroupBy:Prop-Style'), $this->aProperties['style']);
		$oField->SetMandatory();
		$oField->SetAllowedValues($aStyles);
		$oForm->AddField($oField);
	}
	
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields))
		{
			try
			{
				$sCurrQuery = $aValues['query'];
				$oCurrSearch = DBObjectSearch::FromOQL($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();
	
				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = DBObjectSearch::FromOQL($sPrevQuery);
				$sPrevClass = $oPrevSearch->GetClass();
	
				if ($sCurrClass != $sPrevClass)
				{
					$this->bFormRedrawNeeded = true;
					// wrong but not necessary - unset($aUpdatedFields['group_by']);
					$this->aProperties['group_by'] = '';
				}
			}
			catch(Exception $e)
			{
				$this->bFormRedrawNeeded = true;
			}
		}
		$oDashlet = parent::Update($aValues, $aUpdatedFields);
		
		if (in_array('style', $aUpdatedFields))
		{
			switch($aValues['style'])
			{
				// Style changed, mutate to the specified type of chart
				case 'pie':
				$oDashlet = new DashletGroupByPie($this->sId);
				break;
					
				case 'bars':
				$oDashlet = new DashletGroupByBars($this->sId);
				break;
					
				case 'table':
				$oDashlet = new DashletGroupByTable($this->sId);
				break;
			}
			$oDashlet->FromParams($aValues);
			$oDashlet->bRedrawNeeded = true;
			$oDashlet->bFormRedrawNeeded = true;
		}
		return $oDashlet;
	}

	static public function GetInfo()
	{
		// Note: no need to translate, should never be visible to the end-user!
		return array(
			'label' => 'Objects grouped by...',
			'icon' => 'images/dashlet-object-grouped.png',
			'description' => 'Grouped objects dashlet (abstract)',
		);
	}
	
	static public function CanCreateFromOQL()
	{
		return true;
	}
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oForm->AddField($oField);
		
		if (!is_null($sOQL))
		{
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
			$aGroupBy = $this->GetGroupByOptions($sOQL);
			$oField->SetAllowedValues($aGroupBy);
		}
		else
		{
			// Creating a form for reading parameters!
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
		}
		$oField->SetMandatory();

		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('style', '', $this->aProperties['style']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}
}

class DashletGroupByPie extends DashletGroupBy
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'pie';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByPie:Label'),
			'icon' => 'images/dashlet-pie-chart.png',
			'description' => Dict::S('UI:DashletGroupByPie:Description'),
		);
	}
}


class DashletGroupByBars extends DashletGroupBy
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'bars';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByBars:Label'),
			'icon' => 'images/dashlet-bar-chart.png',
			'description' => Dict::S('UI:DashletGroupByBars:Description'),
		);
	}
}

class DashletGroupByTable extends DashletGroupBy
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['style'] = 'table';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletGroupByTable:Label'),
			'description' => Dict::S('UI:DashletGroupByTable:Description'),
			'icon' => 'images/dashlet-groupby-table.png',
		);
	}
}


class DashletHeaderStatic extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderStatic:Prop-Title:Default');
		$sIcon = $this->oModelReflection->GetClassIcon('Contact', false);
		$sIcon = str_replace(utils::GetAbsoluteUrlModulesRoot(), '', $sIcon);
		$this->aProperties['icon'] = $sIcon;
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];

		$sIconPath = utils::GetAbsoluteUrlModulesRoot().$sIcon;

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.$sIconPath.'">');
		$oPage->add('<h1>'.Dict::S($sTitle).'</h1>');

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderStatic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);
		
		$oField = new DesignerIconSelectionField('icon', Dict::S('UI:DashletHeaderStatic:Prop-Icon'), $this->aProperties['icon']);
		$aAllIcons = self::FindIcons(APPROOT.'env-'.utils::GetCurrentEnvironment());
		ksort($aAllIcons);
		$aValues = array();
		foreach($aAllIcons as $sFilePath)
		{
			$aValues[] = array('value' => $sFilePath, 'label' => basename($sFilePath), 'icon' => utils::GetAbsoluteUrlModulesRoot().$sFilePath);
		}
		$oField->SetAllowedValues($aValues);
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletHeaderStatic:Label'),
			'icon' => 'images/dashlet-header.png',
			'description' => Dict::S('UI:DashletHeaderStatic:Description'),
		);
	}
	
	static public function FindIcons($sBaseDir, $sDir = '')
	{
		$aResult = array();
		// Populate automatically the list of icon files
		if ($hDir = @opendir($sBaseDir.'/'.$sDir))
		{
			while (($sFile = readdir($hDir)) !== false)
			{
				$aMatches = array();
				if (($sFile != '.') && ($sFile != '..') && ($sFile != 'lifecycle') && is_dir($sBaseDir.'/'.$sDir.'/'.$sFile))
				{
					$sDirSubPath = ($sDir == '') ? $sFile : $sDir.'/'.$sFile;
					$aResult = array_merge($aResult, self::FindIcons($sBaseDir, $sDirSubPath));
				}
				if (preg_match("/\.(png|jpg|jpeg|gif)$/i", $sFile, $aMatches)) // png, jp(e)g and gif are considered valid
				{
					$aResult[$sFile.'_'.$sDir] = $sDir.'/'.$sFile;
				}
			}
			closedir($hDir);
		}
		return $aResult;
	}
}


class DashletHeaderDynamic extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderDynamic:Prop-Title:Default');
		$sIcon = $this->oModelReflection->GetClassIcon('Contact', false);
		$sIcon = str_replace(utils::GetAbsoluteUrlModulesRoot(), '', $sIcon);
		$this->aProperties['icon'] = $sIcon;
		$this->aProperties['subtitle'] = Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle:Default');
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['values'] = array('active', 'inactive');
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$aValues = $this->aProperties['values'];

		$oFilter = DBObjectSearch::FromOQL($sQuery);
		$sClass = $oFilter->GetClass();

		$sIconPath = utils::GetAbsoluteUrlModulesRoot().$sIcon;

		if ($this->oModelReflection->IsValidAttCode($sClass, $sGroupBy))
		{
			if (count($aValues) == 0)
			{
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $sGroupBy);
				if (is_array($aAllowed))
				{
					$aValues = array_keys($aAllowed);
				}
			}
		}
		if (count($aValues) > 0)
		{
			// Stats grouped by <group_by>
			$sCSV = implode(',', $aValues);
			$aExtraParams = array(
				'title[block]' => $sTitle,
				'label[block]' => $sSubtitle,
				'status[block]' => $sGroupBy,
				'status_codes[block]' => $sCSV,
				'context_filter' => 1,
			);
		}
		else
		{
			// Simple stats
			$aExtraParams = array(
				'title[block]' => $sTitle,
				'label[block]' => $sSubtitle,
				'context_filter' => 1,
			);
		}

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.$sIconPath.'">');

		$oBlock = new DisplayBlock($oFilter, 'summary');
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderDynamic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerIconSelectionField('icon', Dict::S('UI:DashletHeaderDynamic:Prop-Icon'), $this->aProperties['icon']);
		$aAllIcons = DashletHeaderStatic::FindIcons(APPROOT.'env-'.utils::GetCurrentEnvironment());
		ksort($aAllIcons);
		$aValues = array();
		foreach($aAllIcons as $sFilePath)
		{
			$aValues[] = array('value' => $sFilePath, 'label' => basename($sFilePath), 'icon' => utils::GetAbsoluteUrlModulesRoot().$sFilePath);
		}
		$oField->SetAllowedValues($aValues);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('subtitle', Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle'), $this->aProperties['subtitle']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('query', Dict::S('UI:DashletHeaderDynamic:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		try
		{
			// Group by field: build the list of possible values (attribute codes + ...)
			$oSearch = DBObjectSearch::FromOQL($this->aProperties['query']);
			$sClass = $oSearch->GetClass();
			$aGroupBy = array();
			foreach($this->oModelReflection->ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if (!$oAttDef instanceof AttributeEnum && (!$oAttDef instanceof AttributeFinalClass || !$this->oModelReflection->HasChildrenClasses($sClass))) continue;
				$sLabel = $oAttDef->GetLabel();
				$aGroupBy[$sAttCode] = $sLabel;
			}
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		}
		catch(Exception $e)
		{
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);

		$oField = new DesignerComboField('values', Dict::S('UI:DashletHeaderDynamic:Prop-Values'), $this->aProperties['values']);
		$oField->MultipleSelection(true);
		if (isset($sClass) && $this->oModelReflection->IsValidAttCode($sClass, $this->aProperties['group_by']))
		{
			$aValues = $this->oModelReflection->GetAllowedValues_att($sClass, $this->aProperties['group_by']);
			$oField->SetAllowedValues($aValues);
		}
		else
		{
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);
	}
	
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields))
		{
			try
			{
				$sCurrQuery = $aValues['query'];
				$oCurrSearch = DBObjectSearch::FromOQL($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();
	
				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = DBObjectSearch::FromOQL($sPrevQuery);
				$sPrevClass = $oPrevSearch->GetClass();
	
				if ($sCurrClass != $sPrevClass)
				{
					$this->bFormRedrawNeeded = true;
					// wrong but not necessary - unset($aUpdatedFields['group_by']);
					$this->aProperties['group_by'] = '';
					$this->aProperties['values'] = array();
				}
			}
			catch(Exception $e)
			{
				$this->bFormRedrawNeeded = true;
			}
		}
		if (in_array('group_by', $aUpdatedFields))
		{
			$this->bFormRedrawNeeded = true;
			$this->aProperties['values'] = array();
		}
		return parent::Update($aValues, $aUpdatedFields);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletHeaderDynamic:Label'),
			'icon' => 'images/dashlet-header-stats.png',
			'description' => Dict::S('UI:DashletHeaderDynamic:Description'),
		);
	}
}


class DashletBadge extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['class'] = 'Contact';
		$this->aCSSClasses[] = 'dashlet-inline';
		$this->aCSSClasses[] = 'dashlet-badge';
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$oPage->add('<div class="dashlet-content">');

		$oFilter = new DBObjectSearch($sClass);
		$oBlock = new DisplayBlock($oFilter, 'actions');
		$aExtraParams = array(
			'context_filter' => 1,
		);
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);

		$oPage->add('</div>');
		if ($bEditMode)
		{
			// Since the container div is not rendered the same way in edit mode, add the 'inline' style to it
			$oPage->add_ready_script("$('#dashlet_".$this->sId."').addClass('dashlet-inline');");
		}
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{

		$oClassesSet = new ValueSetEnumClasses('bizmodel', array());
		$aClasses = $oClassesSet->GetValues(array());
		
		$aLinkClasses = array();
	
		foreach($this->oModelReflection->GetClasses('bizmodel') as $sClass)
		{	
			foreach($this->oModelReflection->ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef instanceof AttributeLinkedSetIndirect)
				{
					$aLinkClasses[$oAttDef->GetLinkedClass()] = true;
				}
			}
		}
			
		
		$oField = new DesignerIconSelectionField('class', Dict::S('UI:DashletBadge:Prop-Class'), $this->aProperties['class']);
		ksort($aClasses);
		$aValues = array();
		foreach($aClasses as $sClass => $sClass)
		{
			if (!array_key_exists($sClass, $aLinkClasses))
			{
				$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
				$sIconFilePath = str_replace(utils::GetAbsoluteUrlAppRoot(), APPROOT, $sIconUrl);
				if (($sIconUrl == '') || !file_exists($sIconFilePath))
				{
					// The icon does not exist, leet's use a transparent one of the same size.
					$sIconUrl = utils::GetAbsoluteUrlAppRoot().'images/transparent_32_32.png';
				}
				$aValues[] = array('value' => $sClass, 'label' => $this->oModelReflection->GetName($sClass), 'icon' => $sIconUrl);
			}
		}
		$oField->SetAllowedValues($aValues);
		
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletBadge:Label'),
			'icon' => 'images/dashlet-badge.png',
			'description' => Dict::S('UI:DashletBadge:Description'),
		);
	}
}
?>

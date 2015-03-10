<?php
// Copyright (C) 2012-2013 Combodo SARL
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
		if (gettype($sValue) == $sRefType)
		{
			// Do not change anything in that case!
			$ret = $sValue;
		}
		elseif ($sRefType == 'boolean')
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

	protected function OnUpdate()
	{
	}

	public function FromDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$oPropNode = $oDOMNode->getElementsByTagName($sProperty)->item(0);
			if ($oPropNode != null)
			{
				$this->aProperties[$sProperty] = $this->PropertyFromDOMNode($oPropNode, $sProperty);
			}
		}
		$this->OnUpdate();
	}

	public function ToDOMNode($oDOMNode)
	{
		foreach ($this->aProperties as $sProperty => $value)
		{
			$oPropNode = $oDOMNode->ownerDocument->createElement($sProperty);
			$oDOMNode->appendChild($oPropNode);
			$this->PropertyToDOMNode($oPropNode, $sProperty, $value);
		}
	}


	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		$res = $this->Str2Prop($sProperty, $oDOMNode->textContent);
		return $res;
	}

	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		$sXmlValue = $this->Prop2Str($value);
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($sXmlValue);
		$oDOMNode->appendChild($oTextNode);
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
		$this->OnUpdate();
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
		else
		{
			foreach ($this->aCSSClasses as $sCSSClass)
			{
				$oPage->add_ready_script("$('#dashlet_".$sId."').addClass('$sCSSClass');");
			}
		}
		
		try
		{
			if (get_class($this->oModelReflection) == 'ModelReflectionRuntime')
			{
				$this->Render($oPage, $bEditMode, $aExtraParams);
			}
			else
			{
				$this->RenderNoData($oPage, $bEditMode, $aExtraParams);
			}
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

	/* Rendering without the real data */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$this->Render($oPage, $bEditMode, $aExtraParams);
	}
		
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
				$this->aProperties[$sProp] = $this->Str2Prop($sProp, $aValues[$sProp]);
			}
		}
		$this->OnUpdate();
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
		$sText = str_replace(array("\r\n", "\n", "\r"), "<br/>", $sText);

		$sId = 'plaintext_'.($bEditMode? 'edit_' : '').$this->sId;
		$oPage->add('<div id="'.$sId.'" class="dashlet-content">'.$sText.'</div>');
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

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$bShowMenu = $this->aProperties['menu'];

		$oPage->add('<div class="dashlet-content">');
		$sHtmlTitle = htmlentities($this->oModelReflection->DictString($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
		if ($sHtmlTitle != '')
		{
			$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		}
		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();
		$oPage->add('<div id="block_fake_'.$this->sId.'" class="display_block">');
		$oPage->p(Dict::S('UI:NoObjectToDisplay'));
		if ($bShowMenu)
		{
			$oPage->p('<a>'.Dict::Format('UI:ClickToCreateNew', $this->oModelReflection->GetName($sClass)).'</a>');
		}
		$oPage->add('</div>');
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

	protected $sGroupByLabel = null;
	protected $sGroupByExpr = null;
	protected $sGroupByAttCode = null;
	protected $sFunction = null;

	/**
	 * Compute Grouping	
	 */
	public function OnUpdate()
	{
		$this->sGroupByExpr = null;
		$this->sGroupByLabel = null;
		$this->sGroupByAttCode = null;
		$this->sFunction = null;

		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$sStyle = $this->aProperties['style'];

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further
		try
		{
			$oQuery = $this->oModelReflection->GetQuery($sQuery);
			$sClass = $oQuery->GetClass();
			$sClassAlias = $oQuery->GetClassAlias();
		}
		catch(Exception $e)
		{
			// Invalid query, let the user edit the dashlet/dashboard anyhow
			$sClass = '';
			$sClassAlias = '';
		}
		// Check groupby... it can be wrong at this stage
		if (preg_match('/^(.*):(.*)$/', $sGroupBy, $aMatches))
		{
			$this->sGroupByAttCode = $aMatches[1];
			$this->sFunction = $aMatches[2];
		}
		else
		{
			$this->sGroupByAttCode = $sGroupBy;
			$this->sFunction = null;
		}
		if ($this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode))
		{
			$sAttLabel = $this->oModelReflection->GetLabel($sClass, $this->sGroupByAttCode);
			if (!is_null($this->sFunction))
			{
				switch($this->sFunction)
				{
				case 'hour':
					$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Hour', $sAttLabel);
					$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%H')"; // 0 -> 23
					break;

				case 'month':
					$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Month', $sAttLabel);
					$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m')"; // yyyy-mm
					break;

				case 'day_of_week':
					$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfWeek', $sAttLabel);
					$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%w')";
					break;

				case 'day_of_month':
					$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfMonth', $sAttLabel);
					$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m-%d')"; // mm-dd
					break;

				default:
					$this->sGroupByLabel = 'Unknown group by function '.$this->sFunction;
					$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				}
			}
			else
			{
				$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				$this->sGroupByLabel = $sAttLabel;
			}
		}
		else
		{
			$this->sGroupByAttCode = null;
		}
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

		if (!$this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode))
		{
			$oPage->add('<p>'.Dict::S('UI:DashletGroupBy:MissingGroupBy').'</p>');
		}
		else
		{
			switch($sStyle)
			{
			case 'bars':
				$sType = 'open_flash_chart';
				$aExtraParams = array(
					'chart_type' => 'bars',
					'chart_title' => $sTitle,
					'group_by' => $this->sGroupByExpr,
					'group_by_label' => $this->sGroupByLabel,
				);
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'pie':
				$sType = 'open_flash_chart';
				$aExtraParams = array(
					'chart_type' => 'pie',
					'chart_title' => $sTitle,
					'group_by' => $this->sGroupByExpr,
					'group_by_label' => $this->sGroupByLabel,
				);
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'table':
			default:
				$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
				$sType = 'count';
				$aExtraParams = array(
					'group_by' => $this->sGroupByExpr,
					'group_by_label' => $this->sGroupByLabel,
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

	protected function MakeSimulatedData()
	{
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		$aDisplayValues = array();
		if ($this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode))
		{
			$aAttributeTypes = $this->oModelReflection->ListAttributes($sClass);
			$sAttributeType = $aAttributeTypes[$this->sGroupByAttCode];
			if (is_subclass_of($sAttributeType, 'AttributeDateTime') || $sAttributeType == 'AttributeDateTime')
			{
				// Note: an alternative to this somewhat hardcoded way of doing things would be to implement...
				//$oExpr = Expression::FromOQL($this->sGroupByExpr);
				//$aTranslationData = array($oQuery->GetClassAlias() => array($this->sGroupByAttCode => new ScalarExpression(date('Y-m-d H:i:s', $iTime))));
				//$sRawValue = CMDBSource::QueryToScalar('SELECT '.$oExpr->Translate($aTranslationData)->Render());
				//$sValueLabel = $oExpr->MakeValueLabel(oFilter, $sRawValue, $sRawValue);
				// Anyhow, this requires :
				// - an update to the prototype of MakeValueLabel() so that it takes ModelReflection parameters
				// - propose clever date/times samples

				$aValues = array();
				switch($this->sFunction)
				{
				case 'hour':
					$aValues = array(8, 9, 15, 18);
					break;

				case 'month':
					$aValues = array('2013 '.Dict::S('Month-11'), '2013 '.Dict::S('Month-12'), '2014 '.Dict::S('Month-01'), '2014 '.Dict::S('Month-02'), '2014 '.Dict::S('Month-03'));
					break;

				case 'day_of_week':
					$aValues = array(Dict::S('DayOfWeek-Monday'), Dict::S('DayOfWeek-Wednesday'), Dict::S('DayOfWeek-Thursday'), Dict::S('DayOfWeek-Friday'));
					break;

				case 'day_of_month':
					$aValues = array(Dict::S('Month-03'). ' 30', Dict::S('Month-03'). ' 31', Dict::S('Month-04'). ' 01', Dict::S('Month-04'). ' 02', Dict::S('Month-04'). ' 03');
					break;
				}
				foreach ($aValues as $sValue)
				{
					$aDisplayValues[] = array('label' => $sValue, 'count' => (int)rand(1, 15));
				}
			}
			elseif (is_subclass_of($sAttributeType, 'AttributeEnum') || $sAttributeType == 'AttributeEnum')
			{
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $this->sGroupByAttCode);
				if ($aAllowed) // null for non enums
				{
					foreach ($aAllowed as $sValue => $sValueLabel)
					{
						$iCount = (int) rand(2, 100);
						$aDisplayValues[] = array(
							'label' => $sValueLabel,
							'count' => $iCount
						);
					}
				}
			}
			else
			{
				$aDisplayValues[] = array('label' => 'a', 'count' => 123);
				$aDisplayValues[] = array('label' => 'b', 'count' => 321);
				$aDisplayValues[] = array('label' => 'c', 'count' => 456);
			}
		}
		return $aDisplayValues;
	}

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div class="dashlet-content">');
		$oPage->add('error!');
		$oPage->add('</div>');
	}

	protected function GetGroupByOptions($sOql)
	{
		$oQuery = $this->oModelReflection->GetQuery($sOql);
		$sClass = $oQuery->GetClass();
		$aGroupBy = array();
		foreach($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType)
		{
			if ($sAttType == 'AttributeLinkedSet') continue;
			if (is_subclass_of($sAttType, 'AttributeLinkedSet')) continue;
			if ($sAttType == 'AttributeFriendlyName') continue;
			if (is_subclass_of($sAttType, 'AttributeFriendlyName')) continue;
			if ($sAttType == 'AttributeExternalField') continue;
			if (is_subclass_of($sAttType, 'AttributeExternalField')) continue;

			$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
			$aGroupBy[$sAttCode] = $sLabel;

			if (is_subclass_of($sAttType, 'AttributeDateTime') || $sAttType == 'AttributeDateTime')
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
				$oCurrSearch = $this->oModelReflection->GetQuery($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();
	
				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = $this->oModelReflection->GetQuery($sPrevQuery);
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
				$oDashlet = new DashletGroupByPie($this->oModelReflection, $this->sId);
				break;
					
				case 'bars':
				$oDashlet = new DashletGroupByBars($this->oModelReflection, $this->sId);
				break;
					
				case 'table':
				$oDashlet = new DashletGroupByTable($this->oModelReflection, $this->sId);
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

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];

		$aDisplayValues = $this->MakeSimulatedData();

		require_once(APPROOT.'/pages/php-ofc-library/open-flash-chart.php');
		$oChart = new open_flash_chart();
	
		$aGroupBy = array();
		$aLabels = array();
		foreach($aDisplayValues as $iRow => $aDisplayData)
		{
			$aLabels[$iRow] = $aDisplayData['label'];
			$aGroupBy[$iRow] = (int) $aDisplayData['count'];
		}

		$oChartElement = new pie();
		$oChartElement->set_start_angle( 35 );
		$oChartElement->set_animate( true );
		$oChartElement->set_tooltip( '#label# - #val# (#percent#)' );
		$oChartElement->set_colours( array('#FF8A00', '#909980', '#2C2B33', '#CCC08D', '#596664') );
	
		$aData = array();
		foreach($aGroupBy as $iRow => $iCount)
		{
			$sFlashLabel = html_entity_decode($aLabels[$iRow], ENT_QUOTES, 'UTF-8');
			$PieValue = new pie_value($iCount, $sFlashLabel);
			$aData[] = $PieValue;
		}
	
		$oChartElement->set_values($aData);
		$oChart->x_axis = null;

		if (!empty($sTitle))
		{
			// The title has been given in an url, and urlencoded...
			// and urlencode transforms utf-8 into something similar to ISO-8859-1
			// Example: é (C3A9 becomes %E9)
			// As a consequence, json_encode (called within open-flash-chart.php)
			// was returning 'null' and the graph was not displayed at all
			// To make sure that the graph is displayed AND to get a correct title
			// (at least for european characters) let's transform back into utf-8 !
			$sTitle = iconv("ISO-8859-1", "UTF-8//IGNORE", $sTitle);
		
			// If the title is a dictionnary entry, fetch it
			$sTitle = $this->oModelReflection->DictString($sTitle);
		
			$oTitle = new title($sTitle);
			$oChart->set_title($oTitle);
			$oTitle->set_style("{font-size: 16px; font-family: Tahoma; font-weight: bold; text-align: center;}");
		}
		$oChart->set_bg_colour('#FFFFFF');
		$oChart->add_element($oChartElement);

		$sData = $oChart->toPrettyString();
		$sData = json_encode($sData);
		$oPage->add_script(
<<< EOF
function ofc_get_data_dashlet_{$this->sId}()
{
	return $sData;
}
EOF
		);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add("<div id=\"dashlet_chart_{$this->sId}\">If the chart does not display, <a href=\"http://get.adobe.com/flash/\" target=\"_blank\">install Flash</a></div>\n");
		$oPage->add('</div>');

//		$oPage->add_script("function ofc_resize(left, width, top, height) { /* do nothing special */ }");
		$oPage->add_ready_script(
<<<EOF
swfobject.embedSWF(	"../images/open-flash-chart.swf", 
	"dashlet_chart_{$this->sId}", 
	"100%", "300","9.0.0",
	"expressInstall.swf",
	{"get-data":"ofc_get_data_dashlet_{$this->sId}", "id":"dashlet_chart_{$this->sId}"}, 
	{'wmode': 'transparent'}
);
EOF
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

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];

		$aDisplayValues = $this->MakeSimulatedData();

		require_once(APPROOT.'/pages/php-ofc-library/open-flash-chart.php');
		$oChart = new open_flash_chart();
	
		$aGroupBy = array();
		$aLabels = array();
		foreach($aDisplayValues as $iRow => $aDisplayData)
		{
			$aLabels[$iRow] = $aDisplayData['label'];
			$aGroupBy[$iRow] = (int) $aDisplayData['count'];
		}

		$oChartElement = new bar_glass();

		$aData = array();
		$aChartLabels = array();
		$maxValue = 0;
		foreach($aGroupBy as $iRow => $iCount)
		{
			$oBarValue = new bar_value($iCount);
			$aData[] = $oBarValue;
			if ($iCount > $maxValue) $maxValue = $iCount;
			$aChartLabels[] = html_entity_decode($aLabels[$iRow], ENT_QUOTES, 'UTF-8');
		}
		$oYAxis = new y_axis();
		$aMagicValues = array(1,2,5,10);
		$iMultiplier = 1;
		$index = 0;
		$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
		while($maxValue > $iTop)
		{
			$index++;
			$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
			if (($index % count($aMagicValues)) == 0)
			{
				$iMultiplier = $iMultiplier * 10;
			}
		}
		//echo "oYAxis->set_range(0, $iTop, $iMultiplier);\n";
		$oYAxis->set_range(0, $iTop, $iMultiplier);
		$oChart->set_y_axis( $oYAxis );
	
		$oChartElement->set_values( $aData );
		$oXAxis = new x_axis();
		$oXLabels = new x_axis_labels();
		// set them vertical
		$oXLabels->set_vertical();
		// set the label text
		$oXLabels->set_labels($aChartLabels);
		// Add the X Axis Labels to the X Axis
		$oXAxis->set_labels( $oXLabels );
		$oChart->set_x_axis( $oXAxis );

		if (!empty($sTitle))
		{
			// The title has been given in an url, and urlencoded...
			// and urlencode transforms utf-8 into something similar to ISO-8859-1
			// Example: é (C3A9 becomes %E9)
			// As a consequence, json_encode (called within open-flash-chart.php)
			// was returning 'null' and the graph was not displayed at all
			// To make sure that the graph is displayed AND to get a correct title
			// (at least for european characters) let's transform back into utf-8 !
			$sTitle = iconv("ISO-8859-1", "UTF-8//IGNORE", $sTitle);
		
			// If the title is a dictionnary entry, fetch it
			$sTitle = $this->oModelReflection->DictString($sTitle);
		
			$oTitle = new title($sTitle);
			$oChart->set_title($oTitle);
			$oTitle->set_style("{font-size: 16px; font-family: Tahoma; font-weight: bold; text-align: center;}");
		}
		$oChart->set_bg_colour('#FFFFFF');
		$oChart->add_element($oChartElement);

		$sData = $oChart->toPrettyString();
		$sData = json_encode($sData);
		$oPage->add_script(
<<< EOF
function ofc_get_data_dashlet_{$this->sId}()
{
	return $sData;
}
EOF
		);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add("<div id=\"dashlet_chart_{$this->sId}\">If the chart does not display, <a href=\"http://get.adobe.com/flash/\" target=\"_blank\">install Flash</a></div>\n");
		$oPage->add('</div>');

//		$oPage->add_script("function ofc_resize(left, width, top, height) { /* do nothing special */ }");
		$oPage->add_ready_script(
<<<EOF
swfobject.embedSWF(	"../images/open-flash-chart.swf", 
	"dashlet_chart_{$this->sId}", 
	"100%", "300","9.0.0",
	"expressInstall.swf",
	{"get-data":"ofc_get_data_dashlet_{$this->sId}", "id":"dashlet_chart_{$this->sId}"}, 
	{'wmode': 'transparent'}
);
EOF
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

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];

		$aDisplayValues = $this->MakeSimulatedData();
		$iTotal = 0;
		foreach($aDisplayValues as $iRow => $aDisplayData)
		{
			$iTotal += $aDisplayData['count'];
		}

		$oPage->add('<div class="dashlet-content">');

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$oPage->add('<div id="'.$sBlockId.'" class="display_block">');
		$oPage->add('<p>'.Dict::Format('UI:Pagination:HeaderNoSelection', $iTotal).'</p>');
		$oPage->add('<table class="listResults">');
		$oPage->add('<thead>');
		$oPage->add('<tr>');
		$oPage->add('<th class="header" title="">'.$this->sGroupByLabel.'</th>');
		$oPage->add('<th class="header" title="'.Dict::S('UI:GroupBy:Count+').'">'.Dict::S('UI:GroupBy:Count').'</th>');
		$oPage->add('</tr>');
		$oPage->add('</thead>');
		$oPage->add('<tbody>');
		foreach($aDisplayValues as $aDisplayData)
		{
			$oPage->add('<tr class="even">');
			$oPage->add('<td class=""><span title="Active">'.$aDisplayData['label'].'</span></td>');
			$oPage->add('<td class=""><a>'.$aDisplayData['count'].'</a></td>');
			$oPage->add('</tr>');
		}
		$oPage->add('</tbody>');
		$oPage->add('</table>');
		$oPage->add('</div>');

		$oPage->add('</div>');
	}
}


class DashletHeaderStatic extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderStatic:Prop-Title:Default');
		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$this->aProperties['icon'] = $oIconSelect->GetDefaultValue('Contact');
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.$sIconPath.'">');
		$oPage->add('<h1>'.$this->oModelReflection->DictString($sTitle).'</h1>');

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderStatic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);
		
		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderStatic:Prop-Icon'), $this->aProperties['icon']);
		$oForm->AddField($oField);
	}
	
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			return $oIconField->ValueFromDOMNode($oDOMNode);
		}
		else
		{
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			$oIconField->ValueToDOMNode($oDOMNode, $value);
		}
		else
		{
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
	}

	static public function GetInfo()
	{
		return array(
			'label' => Dict::S('UI:DashletHeaderStatic:Label'),
			'icon' => 'images/dashlet-header.png',
			'description' => Dict::S('UI:DashletHeaderStatic:Description'),
		);
	}
}


class DashletHeaderDynamic extends Dashlet
{
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderDynamic:Prop-Title:Default');
		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$this->aProperties['icon'] = $oIconSelect->GetDefaultValue('Contact');
		$this->aProperties['subtitle'] = Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle:Default');
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['values'] = array('active', 'inactive');
	}

	protected function GetValues()
	{
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$aValues = $this->aProperties['values'];

		if (empty($aValues))
		{
			$aValues = array();
		}

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

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
		return $aValues;
	}

	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$aValues = $this->GetValues();
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

		$oFilter = DBObjectSearch::FromOQL($sQuery);
		$oBlock = new DisplayBlock($oFilter, 'summary');
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$aValues = $this->aProperties['values'];

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = $oIconSelect->MakeFileUrl($sIcon);

		$oPage->add('<div class="dashlet-content">');
		$oPage->add('<div class="main_header">');

		$oPage->add('<img src="'.$sIconPath.'">');

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$iTotal = 0;
		$aValues = $this->GetValues();
		if (count($aValues) > 0)
		{
			// Stats grouped by <group_by>
		}
		else
		{
			// Simple stats
		}

		$oPage->add('<div class="display_block" id="'.$sBlockId.'">');
		$oPage->add('<div class="summary-details">');
		$oPage->add('<table><tbody>');
		$oPage->add('<tr>');
		foreach ($aValues as $sValue)
		{
			$sValueLabel = $this->oModelReflection->GetValueLabel($sClass, $sGroupBy, $sValue);
   		$oPage->add('	<th>'.$sValueLabel.'</th>');
   	}
		$oPage->add('</tr>');
		$oPage->add('<tr>');
		foreach ($aValues as $sValue)
		{
			$iCount = (int) rand(2, 100);
			$iTotal += $iCount;
			$oPage->add('	<td>'.$iCount.'</td>');
		}
		$oPage->add('</tr>');
		$oPage->add('</tbody></table>');
		$oPage->add('</div>');

		$sTitle = $this->oModelReflection->DictString($sTitle);
		$sSubtitle = $this->oModelReflection->DictFormat($sSubtitle, $iTotal);
//		$sSubtitle = "original: $sSubtitle, S:".$this->oModelReflection->DictString($sSubtitle).", Format: '".$this->oModelReflection->DictFormat($sSubtitle, $iTotal)."'";

		$oPage->add('<h1>'.$sTitle.'</h1>');
		$oPage->add('<a class="summary">'.$sSubtitle.'</a>');
		$oPage->add('</div>');

		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderDynamic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderDynamic:Prop-Icon'), $this->aProperties['icon']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('subtitle', Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle'), $this->aProperties['subtitle']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('query', Dict::S('UI:DashletHeaderDynamic:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oForm->AddField($oField);

		try
		{
			// Group by field: build the list of possible values (attribute codes + ...)
			$oQuery = $this->oModelReflection->GetQuery($this->aProperties['query']);
			$sClass = $oQuery->GetClass();
			$aGroupBy = array();
			foreach($this->oModelReflection->ListAttributes($sClass, 'AttributeEnum,AttributeFinalClass') as $sAttCode => $sAttType)
			{
				if (is_subclass_of($sAttType, 'AttributeFinalClass') || ($sAttType == 'AttributeFinalClass'))
				{
					if (!$this->oModelReflection->HasChildrenClasses($sClass)) continue;
				}
				$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
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
				$oCurrSearch = $this->oModelReflection->GetQuery($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();
	
				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = $this->oModelReflection->GetQuery($sPrevQuery);
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
	
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			return $oIconField->ValueFromDOMNode($oDOMNode);
		}
		else
		{
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		if ($sProperty == 'icon')
		{
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			$oIconField->ValueToDOMNode($oDOMNode, $value);
		}
		else
		{
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
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
	}

	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
		$sClassLabel = $this->oModelReflection->GetName($sClass);

		$oPage->add('<div class="dashlet-content">');

		$oPage->add('<div id="block_fake_'.$this->sId.'" class="display_block">');
		$oPage->add('<p>');
		$oPage->add('   <a class="actions"><img src="'.$sIconUrl.'" style="vertical-align:middle;float;left;margin-right:10px;border:0;">'.$sClassLabel.': 947</a>');
		$oPage->add('</p>');
		$oPage->add('<p>');
		$oPage->add('   <a>'.Dict::Format('UI:ClickToCreateNew', $sClassLabel).'</a>');
		$oPage->add('   <br/>');
		$oPage->add('   <a>'.Dict::Format('UI:SearchFor_Class', $sClassLabel).'</a>');
		$oPage->add('</p>');
		$oPage->add('</div>');

		$oPage->add('</div>');
	}

	static protected $aClassList = null;

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		if (is_null(self::$aClassList))
		{
			// Cache the ordered list of classes (ordered on the label)
			// (has a significant impact when editing a page with lots of badges)
			//
			$aClasses = array();
			foreach($this->oModelReflection->GetClasses('bizmodel', true /*exclude links*/) as $sClass)
			{	
				$aClasses[$sClass] = $this->oModelReflection->GetName($sClass);
			}
			asort($aClasses);

			self::$aClassList = array();
			foreach($aClasses as $sClass => $sLabel)
			{
				$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
				$sIconFilePath = str_replace(utils::GetAbsoluteUrlAppRoot(), APPROOT, $sIconUrl);
				if ($sIconUrl == '')
				{
					// The icon does not exist, let's use a transparent one of the same size.
					$sIconUrl = utils::GetAbsoluteUrlAppRoot().'images/transparent_32_32.png';
				}
				self::$aClassList[] = array('value' => $sClass, 'label' => $sLabel, 'icon' => $sIconUrl);
			}
		}

		$oField = new DesignerIconSelectionField('class', Dict::S('UI:DashletBadge:Prop-Class'), $this->aProperties['class']);
		$oField->SetAllowedValues(self::$aClassList);
		
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

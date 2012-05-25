<?php
// Copyright (C) 2012 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

require_once(APPROOT.'application/forms.class.inc.php');

/**
 * Base class for all 'dashlets' (i.e. widgets to be inserted into a dashboard)
 *
 */
abstract class Dashlet
{
	protected $sId;
	protected $bRedrawNeeded;
	protected $bFormRedrawNeeded;
	protected $aProperties; // array of {property => value}
	protected $aCSSClasses;
	
	public function __construct($sId)
	{
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
		else
		{
			$ret = $sValue;
			settype($ret, $sRefType);
		}
		return $ret;
	}

	public function Prop2Str($value)
	{
		if (gettype($value) == 'boolean')
		{
			$sRet = $value ? 'true' : 'false';
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
	
	public function DoRender($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sCSSClasses = implode(' ', $this->aCSSClasses);
		if ($bEditMode)
		{
			$sId = $this->GetID();
			$oPage->add('<div class="'.$sCSSClasses.'" id="dashlet_'.$sId.'">');
		}
		else
		{
			$oPage->add('<div class="'.$sCSSClasses.'">');
		}
		$this->Render($oPage, $bEditMode, $aExtraParams);
		$oPage->add('</div>');
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
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL)
	{
		// Default: do nothing since it's not supported
	}
}

class DashletEmptyCell extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
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

class DashletHelloWorld extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['text'] = 'Hello World';
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div style="text-align:center; line-height:5em" class="dashlet-content"><span>'.$this->aProperties['text'].'</span></div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('text', 'Text', $this->aProperties['text']);
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Hello World',
			'icon' => 'images/dashlet-text.png',
			'description' => 'Hello World test Dashlet',
		);
	}
}

class DashletObjectList extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['title'] = 'Hardcoded list of "my requests"';
		$this->aProperties['query'] = 'SELECT UserRequest AS i WHERE i.caller_id = :current_contact_id AND status NOT IN ("closed", "resolved")';
		$this->aProperties['menu'] = false;
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sShowMenu = $this->aProperties['menu'] ? '1' : '0';


		$oPage->add('<div style="text-align:center" class="dashlet-content">');
		// C'est quoi ce paramètre "menu" ?
		$sXML = '<itopblock BlockClass="DisplayBlock" type="list" asynchronous="false" encoding="text/oql" parameters="menu:'.$sShowMenu.'">'.$sQuery.'</itopblock>';
		$aParams = array();
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock = DisplayBlock::FromTemplate($sXML);
		$oBlock->Display($oPage, $sBlockId, $aParams);

		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', 'Title', $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', 'Query', $this->aProperties['query']);
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', 'Menu', $this->aProperties['menu']);
		$oForm->AddField($oField);
	}

	static public function GetInfo()
	{
		return array(
			'label' => 'Object list',
			'icon' => 'images/dashlet-object-list.png',
			'description' => 'Object list dashlet',
		);
	}
	
	static public function CanCreateFromOQL()
	{
		return true;
	}
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL)
	{
		$oField = new DesignerTextField('title', 'Title', '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', 'Query', $sOQL);
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', 'Menu', $this->aProperties['menu']);
		$oForm->AddField($oField);
	}
}

abstract class DashletGroupBy extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['title'] = 'Hardcoded list of Contacts grouped by location';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'location_name';
		$this->aProperties['style'] = 'table';
	}

	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$sStyle = $this->aProperties['style'];

		if ($sQuery == '')
		{
			$oPage->add('<p>Please enter a valid OQL query</p>');
		}
		elseif ($sGroupBy == '')
		{
			$oPage->add('<p>Please select the field on which the objects will be grouped together</p>');
		}
		else
		{
			$oFilter = DBObjectSearch::FromOQL($sQuery);
			$sClassAlias = $oFilter->GetClassAlias();

			if (preg_match('/^(.*):(.*)$/', $sGroupBy, $aMatches))
			{
				$sAttCode = $aMatches[1];
				$sFunction = $aMatches[2];

				switch($sFunction)
				{
				case 'hour':
					$sGroupByLabel = 'Hour of '.$sAttCode. ' (0-23)';
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%H')"; // 0 -> 31
					break;

				case 'month':
					$sGroupByLabel = 'Month of '.$sAttCode. ' (1 - 12)';
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%m')"; // 0 -> 31
					break;

				case 'day_of_week':
					$sGroupByLabel = 'Day of week for '.$sAttCode. ' (sunday to saturday)';
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%w')";
					break;

				case 'day_of_month':
					$sGroupByLabel = 'Day of month for'.$sAttCode;
					$sGroupByExpr = "DATE_FORMAT($sClassAlias.$sAttCode, '%e')"; // 0 -> 31
					break;

				default:
					$sGroupByLabel = 'Unknown group by function '.$sFunction;
					$sGroupByExpr = $sClassAlias.'.'.$sAttCode;
				}
			}
			else
			{
				$sAttCode = $sGroupBy;

				$sGroupByExpr = $sClassAlias.'.'.$sAttCode;
				$sGroupByLabel = MetaModel::GetLabel($oFilter->GetClass(), $sAttCode);
			}

			switch($sStyle)
			{
			case 'bars':
				$sXML = '<itopblock BlockClass="DisplayBlock" type="open_flash_chart" parameters="chart_type:bars;chart_title:'.$sGroupByLabel.';group_by:'.$sGroupByExpr.';group_by_label:'.$sGroupByLabel.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
				$sHtmlTitle = ''; // done in the itop block
				break;
			case 'pie':
				$sXML = '<itopblock BlockClass="DisplayBlock" type="open_flash_chart" parameters="chart_type:pie;chart_title:'.$sGroupByLabel.';group_by:'.$sGroupByExpr.';group_by_label:'.$sGroupByLabel.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
				$sHtmlTitle = ''; // done in the itop block
				break;
			case 'table':
			default:
				$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
				$sXML = '<itopblock BlockClass="DisplayBlock" type="count" parameters="group_by:'.$sGroupByExpr.';group_by_label:'.$sGroupByLabel.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
				break;
			}
	
			$oPage->add('<div style="text-align:center" class="dashlet-content">');
			if ($sHtmlTitle != '')
			{
				$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
			}
			$aParams = array();
			$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
			$oBlock = DisplayBlock::FromTemplate($sXML);
			$oBlock->Display($oPage, $sBlockId, $aParams);
			$oPage->add('</div>');

			// TEST Group By as SQL!
			//$oSearch = DBObjectSearch::FromOQL($this->aProperties['query']);
			//$sSql = MetaModel::MakeSelectQuery($oSearch);
			//$sHtmlSql = htmlentities($sSql, ENT_QUOTES, 'UTF-8');
			//$oPage->p($sHtmlSql);
		}
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', 'Title', $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', 'Query', $this->aProperties['query']);
		$oForm->AddField($oField);

		// Group by field: build the list of possible values (attribute codes + ...)
		$oSearch = DBObjectSearch::FromOQL($this->aProperties['query']);
		$sClass = $oSearch->GetClass();
		$aGroupBy = array();
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->IsScalar()) continue; // skip link sets
			if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) continue; // skip external keys
			$aGroupBy[$sAttCode] = $oAttDef->GetLabel();

			if ($oAttDef instanceof AttributeDateTime)
			{
				$aGroupBy[$sAttCode.':hour'] = $oAttDef->GetLabel().' (hour)';
				$aGroupBy[$sAttCode.':month'] = $oAttDef->GetLabel().' (month)';
				$aGroupBy[$sAttCode.':day_of_week'] = $oAttDef->GetLabel().' (day of week)';
				$aGroupBy[$sAttCode.':day_of_month'] = $oAttDef->GetLabel().' (day of month)';
			}
		}

		

		$oField = new DesignerComboField('group_by', 'Group by', $this->aProperties['group_by']);
		$oField->SetAllowedValues($aGroupBy);
		$oForm->AddField($oField);


		$aStyles = array(
			'pie' => 'Pie chart',
			'bars' => 'Bar chart',
			'table' => 'Table',
		);
		
		$oField = new DesignerComboField('style', 'Style', $this->aProperties['style']);
		$oField->SetAllowedValues($aStyles);
		$oForm->AddField($oField);
	}
	
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields))
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
		return array(
			'label' => 'Objects grouped by...',
			'icon' => 'images/dashlet-object-grouped.png',
			'description' => 'Grouped objects dashlet',
		);
	}
	
	static public function CanCreateFromOQL()
	{
		return true;
	}
	
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL)
	{
		$oField = new DesignerTextField('title', 'Title', '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', 'Query', $sOQL);
		$oForm->AddField($oField);

		// Group by field: build the list of possible values (attribute codes + ...)
		$oSearch = DBObjectSearch::FromOQL($this->aProperties['query']);
		$sClass = $oSearch->GetClass();
		$aGroupBy = array();
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->IsScalar()) continue; // skip link sets
			if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) continue; // skip external keys
			$aGroupBy[$sAttCode] = $oAttDef->GetLabel();

			if ($oAttDef instanceof AttributeDateTime)
			{
				//date_format(start_date, '%d')
				$aGroupBy['date_of_'.$sAttCode] = 'Day of '.$oAttDef->GetLabel();
			}

		}

		$oField = new DesignerComboField('group_by', 'Group by', $this->aProperties['group_by']);
		$oField->SetAllowedValues($aGroupBy);
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('style', '', $this->aProperties['style']);
		$oForm->AddField($oField);
	}
}

class DashletGroupByPie extends DashletGroupBy
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['style'] = 'pie';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Pie Chart',
			'icon' => 'images/dashlet-pie-chart.png',
			'description' => 'Pie Chart',
		);
	}
}

class DashletGroupByBars extends DashletGroupBy
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['style'] = 'bars';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Bar Chart',
			'icon' => 'images/dashlet-bar-chart.png',
			'description' => 'Bar Chart',
		);
	}
}

class DashletGroupByTable extends DashletGroupBy
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['style'] = 'table';
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Group By (table)',
			'icon' => 'images/dashlet-group-by-table.png',
			'description' => 'List (Grouped by a field)',
		);
	}
}


class DashletHeader extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['title'] = 'Hardcoded header of contacts';
		$this->aProperties['subtitle'] = 'Contacts';
		$this->aProperties['class'] = 'Contact';
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sTitle = $this->aProperties['title'];
		$sSubtitle = $this->aProperties['subtitle'];
		$sClass = $this->aProperties['class'];

		$sTitleReady = str_replace(':', '_', $sTitle);
		$sSubtitleReady = str_replace(':', '_', $sSubtitle);

		$sStatusAttCode = MetaModel::GetStateAttributeCode($sClass);
		if (($sStatusAttCode == '') && MetaModel::IsValidAttCode($sClass, 'status'))
		{
			// Based on an enum
			$sStatusAttCode = 'status';
			$aStates = array_keys(MetaModel::GetAllowedValues_att($sClass, $sStatusAttCode));
		}
		else
		{
			// Based on a state variable
			$aStates = array_keys(MetaModel::EnumStates($sClass));
		}
		
		if ($sStatusAttCode == '')
		{
			// Simple stats
			$sXML = '<itopblock BlockClass="DisplayBlock" type="summary" asynchronous="false" encoding="text/oql" parameters="title[block]:'.$sTitleReady.';context_filter:1;label[block]:'.$sSubtitleReady.'">SELECT '.$sClass.'</itopblock>';
		}
		else
		{
			// Stats grouped by "status"

			$sStatusList = implode(',', $aStates);
			//$oPage->p('State: '.$sStatusAttCode.' states='.$sStatusList);
			$sXML = '<itopblock BlockClass="DisplayBlock" type="summary" asynchronous="false" encoding="text/oql" parameters="title[block]:'.$sTitleReady.';context_filter:1;label[block]:'.$sSubtitleReady.';status[block]:status;status_codes[block]:'.$sStatusList.'">SELECT '.$sClass.'</itopblock>';
		}

		$oPage->add('<div style="text-align:center" class="dashlet-content">');
		$oPage->add('<div class="main_header">');
		$aParams = array();
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock = DisplayBlock::FromTemplate($sXML);
		$oBlock->Display($oPage, $sBlockId, $aParams);
		$oPage->add('</div>');
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', 'Title', $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('subtitle', 'Subtitle', $this->aProperties['subtitle']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('class', 'Class', $this->aProperties['class']);
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Header with stats',
			'icon' => 'images/dashlet-header-stats.png',
			'description' => 'Header with stats (grouped by...)',
		);
	}
}


class DashletBadge extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['class'] = 'Contact';
		$this->aCSSClasses[] = 'dashlet-inline';
		$this->aCSSClasses[] = 'dashlet-badge';
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$oPage->add('<div style="text-align:center" class="dashlet-content">');
		$sXml = "<itopblock BlockClass=\"DisplayBlock\" type=\"actions\" asynchronous=\"false\" encoding=\"text/oql\" parameters=\"context_filter:1\">SELECT $sClass</itopblock>";
		$oBlock = DisplayBlock::FromTemplate($sXml);
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->Display($oPage, $sBlockId, $aExtraParams);
		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('class', 'Class', $this->aProperties['class']);
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Badge',
			'icon' => 'images/dashlet-badge.png',
			'description' => 'Object Icon with new/search',
		);
	}
}


class DashletProto extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->aProperties['class'] = 'Foo';
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$sClass = $this->aProperties['class'];

		$oFilter = DBObjectSearch::FromOQL('SELECT FunctionalCI AS fci');
		$sGroupBy1 = 'status';
		$sGroupBy2 = 'org_id_friendlyname';
		$sHtmlTitle = "Hardcoded on $sGroupBy1 and $sGroupBy2...";

		$sAlias = $oFilter->GetClassAlias();

		$oGroupByExp1 = new FieldExpression($sGroupBy1, $sAlias);
		$sGroupByLabel1 = MetaModel::GetLabel($oFilter->GetClass(), $sGroupBy1);
		
		$oGroupByExp2 = new FieldExpression($sGroupBy2, $sAlias);
		$sGroupByLabel2 = MetaModel::GetLabel($oFilter->GetClass(), $sGroupBy2);
		
		$aGroupBy = array();
		$aGroupBy['grouped_by_1'] = $oGroupByExp1;
		$aGroupBy['grouped_by_2'] = $oGroupByExp2;
		$sSql = MetaModel::MakeGroupByQuery($oFilter, array(), $aGroupBy);
		$aRes = CMDBSource::QueryToArray($sSql);
		
		$iTotalCount = 0;
		$aData = array();
		$oAppContext = new ApplicationContext();
		$sParams = $oAppContext->GetForLink();
		foreach ($aRes as $aRow)
		{
			$iCount = $aRow['_itop_count_'];
			$iTotalCount += $iCount;

			$sValue1 = $aRow['grouped_by_1'];
			$sValue2 = $aRow['grouped_by_2'];

			// Build the search for this subset
			$oSubsetSearch = clone $oFilter;
			$oCondition = new BinaryExpression($oGroupByExp1, '=', new ScalarExpression($sValue1));
			$oSubsetSearch->AddConditionExpression($oCondition);
			$oCondition = new BinaryExpression($oGroupByExp2, '=', new ScalarExpression($sValue2));
			$oSubsetSearch->AddConditionExpression($oCondition);
		
			$sFilter = urlencode($oSubsetSearch->serialize());
			$aData[] = array (
				'group1' => htmlentities($sValue1, ENT_QUOTES, 'UTF-8'),
				'group2' => htmlentities($sValue2, ENT_QUOTES, 'UTF-8'),
				'value' => "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter\">$iCount</a>"
			); // TO DO: add the context information
		}
		$aAttribs =array(
			'group1' => array('label' => $sGroupByLabel1, 'description' => ''),
			'group2' => array('label' => $sGroupByLabel2, 'description' => ''),
			'value' => array('label'=> Dict::S('UI:GroupBy:Count'), 'description' => Dict::S('UI:GroupBy:Count+'))
		);


		$oPage->add('<div style="text-align:center" class="dashlet-content">');

		$oPage->add('<h1>'.$sHtmlTitle.'</h1>');
		$oPage->p(Dict::Format('UI:Pagination:HeaderNoSelection', $iTotalCount));
		$oPage->table($aAttribs, $aData);

		$oPage->add('</div>');
	}

	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('class', 'Class', $this->aProperties['class']);
		$oForm->AddField($oField);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Test3D',
			'icon' => 'images/xxxxxx.png',
			'description' => 'Group by on two dimensions',
		);
	}
}

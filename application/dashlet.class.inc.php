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
	
	public function __construct($sId)
	{
		$this->sId = $sId;
		$this->bRedrawNeeded = true; // By default: redraw each time a property changes
		$this->bFormRedrawNeeded = false; // By default: no need to redraw the form (independent fields)
		$this->aProperties = array(); // By default: there is no property
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
		if ($bEditMode)
		{
			$sId = $this->GetID();
			$oPage->add('<div class="dashlet" id="dashlet_'.$sId.'">');
		}
		else
		{
			$oPage->add('<div class="dashlet">');
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


class DashletFakeBarChart extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div style="text-align:center" class="dashlet-content"><div>Fake Bar Chart</div><divp><img src="../images/fake-bar-chart.png"/></div></div>');
	}
	
	public function GetPropertiesFields(DesignerForm $oForm, $oDashlet = null)
	{
	}
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('fake_bar_chart', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
	}

	static public function GetInfo()
	{
		return array(
			'label' => 'Bar Chart',
			'icon' => 'images/dashlet-bar-chart.png',
			'description' => 'Fake Bar Chart (for testing)',
		);
	}
}


class DashletFakePieChart extends Dashlet
{
	public function __construct($sId)
	{
		parent::__construct($sId);
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div style="text-align:center" class="dashlet-content"><div>Fake Pie Chart</div><div><img src="../images/fake-pie-chart.png"/></div></div>');
	}
	
	public function GetPropertiesFields(DesignerForm $oForm, $oDashlet = null)
	{
	}
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('fake_pie_chart', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => 'Pie Chart',
			'icon' => 'images/dashlet-pie-chart.png',
			'description' => 'Fake Pie Chart (for testing)',
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

		$oField = new DesignerTextField('query', 'Query', $this->aProperties['query']);
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
}

class DashletGroupBy extends Dashlet
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
			switch($sStyle)
			{
			case 'bars':
				$sXML = '<itopblock BlockClass="DisplayBlock" type="open_flash_chart" parameters="chart_type:bars;chart_title:'.$sTitle.';group_by:'.$sGroupBy.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
				$sHtmlTitle = ''; // done in the itop block
				break;
			case 'pie':
				$sXML = '<itopblock BlockClass="DisplayBlock" type="open_flash_chart" parameters="chart_type:pie;chart_title:'.$sTitle.';group_by:'.$sGroupBy.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
				$sHtmlTitle = ''; // done in the itop block
				break;
			case 'table':
			default:
				$sHtmlTitle = htmlentities(Dict::S($sTitle), ENT_QUOTES, 'UTF-8'); // done in the itop block
				$sXML = '<itopblock BlockClass="DisplayBlock" type="count" parameters="group_by:'.$sGroupBy.'" asynchronous="false" encoding="text/oql">'.$sQuery.'</itopblock>';
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

		$oField = new DesignerTextField('query', 'Query', $this->aProperties['query']);
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

		parent::Update($aValues, $aUpdatedFields);
	}

	static public function GetInfo()
	{
		return array(
			'label' => 'Objects grouped by...',
			'icon' => 'images/dashlet-object-grouped.png',
			'description' => 'Grouped objects dashlet',
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

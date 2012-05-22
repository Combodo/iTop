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
	
	
	public function __construct($sId)
	{
		$this->sId = $sId;
		$this->bRedrawNeeded = true; // By default: redraw each time a property changes
		$this->bFormRedrawNeeded = false; // By default: no need to redraw the form (independent fields)
	}
	
	public function FromDOMNode($oDOMNode)
	{
		
	}
	
	public function FromXml($sXml)
	{
		
	}
	
	public function FromParams($aParams)
	{
		
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
	
	public function GetForm($oPage, $bReturnHTML = false)
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
	protected $sText;
	
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->sText = 'Hello World';
	}
	
	public function FromDOMNode($oDOMNode)
	{
		//$this->sText = 'Hello World!';
	}
	
	public function FromXml($sXml)
	{
		//$this->sText = 'Hello World!';
	}
	
	public function FromParams($aParams)
	{
		$this->sText = $aParams['text'];
	}
	
	public function Update($aValues, $aUpdatedFields)
	{
		foreach($aUpdatedFields as $sProp)
		{
			switch($sProp)
			{
				case 'text':
				$this->sText = $aValues['text'];
				break;
			}
		}
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<div style="text-align:center; line-height:5em" class="dashlet-content"><span>'.$this->sText.'</span></div>');
	}
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('text', 'Text', $this->sText);
		$oForm->AddField($oField);
	}
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('hello_world', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
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
	
	public function FromDOMNode($oDOMNode)
	{
		
	}
	
	public function FromXml($sXml)
	{
		
	}
	
	public function FromParams($aParams)
	{
		
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
	
	public function FromDOMNode($oDOMNode)
	{
		
	}
	
	public function FromXml($sXml)
	{
		
	}
	
	public function FromParams($aParams)
	{
		
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
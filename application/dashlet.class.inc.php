<?php
abstract class Dashlet
{
	protected $sId;
	
	public function __construct($sId)
	{
		$this->sId = $sId;
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
	
	public function RenderProperties($oPage)
	{
		$sId = $this->GetID();
		$sClass = get_class($this);
		$oPage->add('<div class="dashlet_properties" id="dashlet_properties_'.$sId.'" style="display:none">');
		$oPage->add("<p>Properties for $sClass / $sId</p>");
		$oPage->add('</div>');
	}
	
	
	public function ToXml(DOMNode $oContainerNode)
	{
		
	}
	
	public function GetForm()
	{
		
	}
	
	public function OnFieldUpdate($aParams, $sUpdatedFieldCode)
	{
		
	}
	
	static public function GetInfo()
	{
		return array(
			'label' => '',
			'icon' => '',
			'description' => '',
		);
	}
}

class DashletHelloWorld extends Dashlet
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
		$oPage->add('<div style="text-align:center; line-height:5em" class="dashlet-content"><span>Hello World!</span></div>');
	}
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('hello_world', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
	}
	
	public function GetForm()
	{
		
	}
	
	public function OnFieldUpdate($aParams, $sUpdatedFieldCode)
	{
		return array(
			'status_ok' => true,
			'redraw' => false,
			'fields' => array(),
		);
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
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('fake_bar_chart', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
	}
	
	public function GetForm()
	{
		
	}
	
	public function OnFieldUpdate($aParams, $sUpdatedFieldCode)
	{
		return array(
			'status_ok' => true,
			'redraw' => false,
			'fields' => array(),
		);
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
	
	public function ToXml(DOMNode $oContainerNode)
	{
		$oNewNodeNode = $oContainerNode->ownerDocument->createElement('fake_pie_chart', 'test');
		$oContainerNode->appendChild($oNewNodeNode);
	}
	
	public function GetForm()
	{
		
	}
	
	public function OnFieldUpdate($aParams, $sUpdatedFieldCode)
	{
		return array(
			'status_ok' => true,
			'redraw' => false,
			'fields' => array(),
		);
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
<?php
require_once(APPROOT.'application/dashboardlayout.class.inc.php');
require_once(APPROOT.'application/dashlet.class.inc.php');

abstract class Dashboard
{
	protected $sTitle;
	protected $sLayoutClass;
	protected $aWidgetsData;
	protected $oDOMNode;
	protected $sId;
	
	public function __construct($sId)
	{
		$this->sLayoutClass = null;
		$this->aDashlets = array();
		$this->oDOMNode = null;
		$this->sId = $sId;
	}
	
	public function FromXml($sXml)
	{
		$oDoc = new DOMDocument();
		$oDoc->loadXML($sXml);
		$this->oDOMNode = $oDoc->getElementsByTagName('dashboard')->item(0);
		
		$oLayoutNode = $this->oDOMNode->getElementsByTagName('layout')->item(0);
		$this->sLayoutClass = $oLayoutNode->textContent;
		
		$oTitleNode = $this->oDOMNode->getElementsByTagName('title')->item(0);
		$this->sTitle = $oTitleNode->textContent;
		
		$oDashletsNode = $this->oDOMNode->getElementsByTagName('dashlets')->item(0);
		$oDashletList = $oDashletsNode->getElementsByTagName('dashlet');
		foreach($oDashletList as $oDomNode)
		{
			$sDashletClass = $oDomNode->getAttribute('xsi:type');
			$oNewDashlet = new $sDashletClass;
			$oNewDashlet->FromDOMNode($oDomNode);
			$this->aDashlets[] = $oNewDashlet;
		}
	}
	
	public function FromParams($aParams)
	{
		
	}
	
	public function Save()
	{
		
	}
	
	public function GetLayout()
	{
		return $this->sLayoutClass;
	}
	
	public function SetLayout($sLayoutClass)
	{
		$this->sLayoutClass = $sLayoutClass;
	}
	
	public function GetTitle()
	{
		return $this->sTitle;
	}
	
	public function SetTitle($sTitle)
	{
		$this->sTitle = $sTitle;
	}
	
	public function AddDashlet()
	{
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		$oPage->add('<h1>'.$this->sTitle.'</h1>');
		$oLayout = new $this->sLayoutClass;
		$oLayout->Render($oPage, $this->aDashlets, $bEditMode, $aExtraParams);
	}
	
	public function RenderProperties($oPage)
	{
		// menu to pick a layout and edit other properties of the dashboard
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">Dashboard Properties</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();
		
		$oPage->add('<div style="text-align:center">Layout:</div>');
		$oPage->add('<div id="select_layout" style="text-align:center">');
		foreach( get_declared_classes() as $sLayoutClass)
		{
			if (is_subclass_of($sLayoutClass, 'DashboardLayout'))
			{
				$oReflection = new ReflectionClass($sLayoutClass);
				if (!$oReflection->isAbstract())
				{
					$aInfo = $sLayoutClass::GetInfo();
					$oPage->add('<input type="radio" name="layout_class" id="layout_'.$sLayoutClass.'"><label for="layout_'.$sLayoutClass.'"><img src="'.$sUrl.$aInfo['icon'].'" /></label>'); // title="" on either the img or the label does nothing !
				}
			}
		}
		$oPage->add('</div>');
		
		$oPage->add('</div>');
		$oPage->add_ready_script("$('#select_layout').buttonset();");
	}
	
	public function RenderDashletsSelection($oPage)
	{
		// Toolbox/palette to drag and drop dashlets
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">Available Dashlets</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();

		$oPage->add('<div id="select_dashlet" style="text-align:center">');
		foreach( get_declared_classes() as $sDashletClass)
		{
			if (is_subclass_of($sDashletClass, 'Dashlet'))
			{
				$oReflection = new ReflectionClass($sDashletClass);
				if (!$oReflection->isAbstract())
				{
					$aInfo = $sDashletClass::GetInfo();
					$oPage->add('<span class="dashlet_icon ui-widget-content ui-corner-all" id="dashlet_'.$sDashletClass.'" title="'.$aInfo['label'].'" style="width:34px; height:34px; display:inline-block; margin:2px;"><img src="'.$sUrl.$aInfo['icon'].'" /></span>');
				}
			}
		}
		$oPage->add('</div>');

		$oPage->add('</div>');
		$oPage->add_ready_script("$('.dashlet_icon').draggable({helper: 'clone', appendTo: 'body', zIndex: 10000, revert:'invalid'});");
		$oPage->add_ready_script("$('.layout_cell').droppable({accept:'.dashlet_icon', hoverClass:'dragHover'});");
	}
	
	public function RenderDashletsProperties($oPage)
	{
		// Toolbox/palette to edit the properties of each dashlet
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">Dashlet Properties</div>');

		$oPage->add('<div id="dashlet_properties" style="text-align:center">');
		$oPage->p('Not yet implemented');
		$oPage->add('</div>');

		$oPage->add('</div>');
	}
}

class RuntimeDashboard extends Dashboard
{
	public function Save()
	{
		
	}
	
	public function Render($oPage, $bEditMode = false, $aExtraParams = array())
	{
		parent::Render($oPage, $bEditMode, $aExtraParams);
		if (!$bEditMode)
		{
			$sEditBtn = addslashes('<div style="display: inline-block; height: 55px; width:200px;vertical-align:center;line-height:60px;text-align:left;"><button onclick="EditDashboard(\''.$this->sId.'\');">Edit This Page</button></div>');
			$oPage->add_ready_script("$('#top-bar').prepend('$sEditBtn');");
			$oPage->add_script(
<<<EOF
function EditDashboard(sId)
{
	console.log('Ici');
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'dashboard_editor', id: sId},
		function(data)
		{
			$('body').append(data);
		}
	);
	return false;
}
EOF
			);
		}
	}
	
	public function RenderEditor($oPage)
	{
		$oPage->add('<div id="dashboard_editor">');
		$oPage->add('<div class="ui-layout-center">');
		$this->Render($oPage, true);
		$oPage->add('</div>');
		$oPage->add('<div class="ui-layout-east">');
		$this->RenderProperties($oPage);
		$this->RenderDashletsSelection($oPage);
		$this->RenderDashletsProperties($oPage);
		$oPage->add('</div>');
		$oPage->add('</div>');
		$sDialogTitle = 'Dashboard Editor';
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		$oPage->add_ready_script(
<<<EOF
$('#dashboard_editor').dialog({
	height: $('body').height() - 50,
	width: $('body').width() - 50,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sOkButtonLabel", click: function() {
		$(this).dialog( "close" ); $(this).remove();
	} },
	{ text: "$sCancelButtonLabel", click: function() { $(this).dialog( "close" ); $(this).remove(); } },
	],
	close: function() { $(this).remove(); }
});
EOF
		);
		$oPage->add_ready_script("$('#dashboard_editor').layout();");
	}
}
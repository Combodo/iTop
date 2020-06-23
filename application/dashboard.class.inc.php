<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

require_once(APPROOT.'application/dashboardlayout.class.inc.php');
require_once(APPROOT.'application/dashlet.class.inc.php');
require_once(APPROOT.'core/modelreflection.class.inc.php');

/**
 *
 * A user editable dashboard page
 *
 */
abstract class Dashboard
{
	/** @var string $sTitle*/
	protected $sTitle;
	/** @var bool $bAutoReload */
	protected $bAutoReload;
	/** @var float|int $iAutoReloadSec */
	protected $iAutoReloadSec;
	/** @var string $sLayoutClass */
	protected $sLayoutClass;
	/** @var array $aWidgetsData */
	protected $aWidgetsData;
	/** @var \DOMNode|null $oDOMNode */
	protected $oDOMNode;
	/** @var string $sId */
	protected $sId;
	/** @var array $aCells */
	protected $aCells;
	/** @var \ModelReflection $oMetaModel */
	protected $oMetaModel;

	/**
	 * Dashboard constructor.
	 *
	 * @param string $sId
	 */
	public function __construct($sId)
	{
		$this->sTitle = '';
		$this->sLayoutClass = 'DashboardLayoutOneCol';
		$this->bAutoReload = false;
		$this->iAutoReloadSec = MetaModel::GetConfig()->GetStandardReloadInterval();
		$this->aCells = array();
		$this->oDOMNode = null;
		$this->sId = $sId;
	}

	/**
	 * @param string $sXml
	 *
	 * @throws \Exception
	 */
	public function FromXml($sXml)
	{
		$this->aCells = array(); // reset the content of the dashboard
		set_error_handler(array('Dashboard', 'ErrorHandler'));
		$oDoc = new DOMDocument();
		$oDoc->loadXML($sXml);
		restore_error_handler();
		$this->FromDOMDocument($oDoc);
	}

	/**
	 * @param \DOMDocument $oDoc
	 */
	public function FromDOMDocument(DOMDocument $oDoc)
	{
		$this->oDOMNode = $oDoc->getElementsByTagName('dashboard')->item(0);
		
		if ($oLayoutNode = $this->oDOMNode->getElementsByTagName('layout')->item(0))
		{
			$this->sLayoutClass = $oLayoutNode->textContent;
		}
		else
		{
			$this->sLayoutClass = 'DashboardLayoutOneCol';
		}
		
		if ($oTitleNode = $this->oDOMNode->getElementsByTagName('title')->item(0))
		{
			$this->sTitle = $oTitleNode->textContent;
		}
		else
		{
			$this->sTitle = '';
		}
		
		$this->bAutoReload = false;
		$this->iAutoReloadSec = MetaModel::GetConfig()->GetStandardReloadInterval();
		if ($oAutoReloadNode = $this->oDOMNode->getElementsByTagName('auto_reload')->item(0))
		{
			if ($oAutoReloadEnabled = $oAutoReloadNode->getElementsByTagName('enabled')->item(0))
			{
				$this->bAutoReload = ($oAutoReloadEnabled->textContent == 'true');
			}
			if ($oAutoReloadInterval = $oAutoReloadNode->getElementsByTagName('interval')->item(0))
			{
				$this->iAutoReloadSec = max(MetaModel::GetConfig()->Get('min_reload_interval'), (int)$oAutoReloadInterval->textContent);
			}
		}

		if ($oCellsNode = $this->oDOMNode->getElementsByTagName('cells')->item(0))
		{
			$oCellsList = $oCellsNode->getElementsByTagName('cell');
			$aCellOrder = array();
			$iCellRank = 0;
			/** @var \DOMElement $oCellNode */
			foreach($oCellsList as $oCellNode)
			{
				$oCellRank = $oCellNode->getElementsByTagName('rank')->item(0);
				if ($oCellRank)
				{
					$iCellRank = (float)$oCellRank->textContent;
				}
				$oDashletsNode = $oCellNode->getElementsByTagName('dashlets')->item(0);
				{
					$oDashletList = $oDashletsNode->getElementsByTagName('dashlet');
					$iRank = 0;
					$aDashletOrder = array();
					/** @var \DOMElement $oDomNode */
					foreach($oDashletList as $oDomNode)
					{
						$oRank =  $oDomNode->getElementsByTagName('rank')->item(0);
						if ($oRank)
						{
							$iRank = (float)$oRank->textContent;
						}

						$oNewDashlet = $this->InitDashletFromDOMNode($oDomNode);
						$aDashletOrder[] = array('rank' => $iRank, 'dashlet' => $oNewDashlet);
					}
					usort($aDashletOrder, array(get_class($this), 'SortOnRank'));
					$aDashletList = array();
					foreach($aDashletOrder as $aItem)
					{
						$aDashletList[] = $aItem['dashlet'];
					}
					$aCellOrder[] = array('rank' => $iCellRank, 'dashlets' => $aDashletList);
				}
			}
			usort($aCellOrder, array(get_class($this), 'SortOnRank'));
			foreach($aCellOrder as $aItem)
			{
				$this->aCells[] = $aItem['dashlets'];
			}
		}
		else
		{
			$this->aCells = array();
		}
	}

	/**
	 * @param \DOMElement $oDomNode
	 *
	 * @return mixed
	 */
	protected function InitDashletFromDOMNode($oDomNode)
    {
        $sId = $oDomNode->getAttribute('id');

	    $sDashletType = $oDomNode->getAttribute('xsi:type');

        // Test if dashlet can be instantiated, otherwise (uninstalled, broken, ...) we display a placeholder
	    $sClass = static::GetDashletClassFromType($sDashletType);
	    /** @var \Dashlet $oNewDashlet */
	    $oNewDashlet = new $sClass($this->oMetaModel, $sId);
        $oNewDashlet->SetDashletType($sDashletType);
        $oNewDashlet->FromDOMNode($oDomNode);

        return $oNewDashlet;
    }

	/**
	 * @param array $aItem1
	 * @param array $aItem2
	 *
	 * @return int
	 */
	public static function SortOnRank($aItem1, $aItem2)
	{
		return ($aItem1['rank'] > $aItem2['rank']) ? +1 : -1;
	}

	/**
	 * Error handler to turn XML loading warnings into exceptions
	 *
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 *
	 * @return bool
	 * @throws \DOMException
	 */
	public static function ErrorHandler($errno, $errstr, $errfile, $errline)
	{
		if ($errno == E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0))
		{
			throw new DOMException($errstr);
		}
		else
		{
			return false;
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function ToXml()
	{
		$oDoc = new DOMDocument();
		$oDoc->formatOutput = true; // indent (must be loaded with option LIBXML_NOBLANKS)
		$oDoc->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect

		$oMainNode = $oDoc->createElement('dashboard');
		$oMainNode->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		$oDoc->appendChild($oMainNode);
		
		$this->ToDOMNode($oMainNode);

		$sXml = $oDoc->saveXML();
		return $sXml;
	}

	/**
	 * @param \DOMElement $oDefinition
	 */
	public function ToDOMNode($oDefinition)
	{
		/** @var \DOMDocument $oDoc */
		$oDoc = $oDefinition->ownerDocument;

		$oNode = $oDoc->createElement('layout', $this->sLayoutClass);
		$oDefinition->appendChild($oNode);

		$oNode = $oDoc->createElement('title', $this->sTitle);
		$oDefinition->appendChild($oNode);

		$oAutoReloadNode = $oDoc->createElement('auto_reload');
		$oDefinition->appendChild($oAutoReloadNode);
		$oNode = $oDoc->createElement('enabled', $this->bAutoReload ? 'true' : 'false');
		$oAutoReloadNode->appendChild($oNode);
		$oNode = $oDoc->createElement('interval', $this->iAutoReloadSec);
		$oAutoReloadNode->appendChild($oNode);

		$oCellsNode = $oDoc->createElement('cells');
		$oDefinition->appendChild($oCellsNode);
		
		$iCellRank = 0;
		foreach ($this->aCells as $aCell)
		{
			$oCellNode = $oDoc->createElement('cell');
			$oCellNode->setAttribute('id', $iCellRank);
			$oCellsNode->appendChild($oCellNode);
			$oCellRank = $oDoc->createElement('rank', $iCellRank);
			$oCellNode->appendChild($oCellRank);
			$iCellRank++;
						
			$iDashletRank = 0;
			$oDashletsNode = $oDoc->createElement('dashlets');
			$oCellNode->appendChild($oDashletsNode);
			/** @var \Dashlet $oDashlet */
			foreach ($aCell as $oDashlet)
			{
				$oNode = $oDoc->createElement('dashlet');
				$oDashletsNode->appendChild($oNode);
				$oNode->setAttribute('id', $oDashlet->GetID());
				$oNode->setAttribute('xsi:type', $oDashlet->GetDashletType());
				$oDashletRank = $oDoc->createElement('rank', $iDashletRank);
				$oNode->appendChild($oDashletRank);
				$iDashletRank++;
				$oDashlet->ToDOMNode($oNode);
			}
		}
	}

	/**
	 * @param array $aParams
	 */
	public function FromParams($aParams)
	{
		$this->sLayoutClass = $aParams['layout_class'];
		$this->sTitle = $aParams['title'];
		$this->bAutoReload = $aParams['auto_reload'] == 'true';
		$this->iAutoReloadSec = max(MetaModel::GetConfig()->Get('min_reload_interval'), (int) $aParams['auto_reload_sec']);
		
		foreach($aParams['cells'] as $aCell)
		{
			$aCellDashlets = array();
			foreach($aCell as $aDashletParams)
			{
				$sDashletClass = $aDashletParams['dashlet_class'];
				$sId = $aDashletParams['dashlet_id'];
				/** @var \Dashlet $oNewDashlet */
				$oNewDashlet = new $sDashletClass($this->oMetaModel, $sId);
				if (isset($aDashletParams['dashlet_type']))
				{
					$oNewDashlet->SetDashletType($aDashletParams['dashlet_type']);
				}
				$oForm = $oNewDashlet->GetForm();
				$oForm->SetParamsContainer($sId);
				$oForm->SetPrefix('');
				$aValues = $oForm->ReadParams();
				$oNewDashlet->FromParams($aValues);
				$aCellDashlets[] = $oNewDashlet;
			}
			$this->aCells[] = $aCellDashlets;
		}
		
	}

	public function Save()
	{
		
	}

	/**
	 * @return mixed
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * Return a sanitize ID for usages in XML/HTML attributes
	 *
	 * @return string
	 * @since 2.7.0
	 */
	public function GetSanitizedId()
	{
		return utils::Sanitize($this->GetId(), '', 'element_identifier');
	}

	/**
	 * @return string
	 */
	public function GetLayout()
	{
		return $this->sLayoutClass;
	}

	/**
	 * @param string $sLayoutClass
	 */
	public function SetLayout($sLayoutClass)
	{
		$this->sLayoutClass = $sLayoutClass;
	}

	/**
	 * @return string
	 */
	public function GetTitle()
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 */
	public function SetTitle($sTitle)
	{
		$this->sTitle = $sTitle;
	}

	/**
	 * @return bool
	 */
	public function GetAutoReload()
	{
		return $this->bAutoReload;
	}

	/**
	 * @param bool $bAutoReload
	 */
	public function SetAutoReload($bAutoReload)
	{
		$this->bAutoReload = $bAutoReload;
	}

	/**
	 * @return float|int
	 */
	public function GetAutoReloadInterval()
	{
		return $this->iAutoReloadSec;
	}

	/**
	 * @param bool $iAutoReloadSec
	 */
	public function SetAutoReloadInterval($iAutoReloadSec)
	{
		$this->iAutoReloadSec = max(MetaModel::GetConfig()->Get('min_reload_interval'), (int)$iAutoReloadSec);
	}

	/**
	 * @param \Dashlet $oDashlet
	 */
	public function AddDashlet($oDashlet)
	{
		$sId = $this->GetNewDashletId();
		$oDashlet->SetId($sId);
		$this->aCells[] = array($oDashlet);
	}

	/**
	 * @param \WebPage $oPage *
	 * @param array $aExtraParams
	 *
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function RenderProperties($oPage, $aExtraParams = array())
	{
		// menu to pick a layout and edit other properties of the dashboard
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">'.Dict::S('UI:DashboardEdit:Properties').'</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();

		$oPage->add('<div style="text-align:center">'.Dict::S('UI:DashboardEdit:Layout').'</div>');
		$oPage->add('<div id="select_layout" style="text-align:center">');
		foreach( get_declared_classes() as $sLayoutClass)
		{
			if (is_subclass_of($sLayoutClass, 'DashboardLayout'))
			{
				$oReflection = new ReflectionClass($sLayoutClass);
				if (!$oReflection->isAbstract())
				{
					$aCallSpec = array($sLayoutClass, 'GetInfo');
					$aInfo = call_user_func($aCallSpec);
					$sChecked = ($this->sLayoutClass == $sLayoutClass) ? 'checked' : '';
					$oPage->add('<input type="radio" name="layout_class" '.$sChecked.' value="'.$sLayoutClass.'" id="layout_'.$sLayoutClass.'"><label for="layout_'.$sLayoutClass.'"><img src="'.$sUrl.$aInfo['icon'].'" /></label>'); // title="" on either the img or the label does nothing !
				}
			}
		}
		$oPage->add('</div>');

		$oForm = new DesignerForm();

		$oField = new DesignerHiddenField('dashboard_id', '', $this->sId);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('dashboard_title', Dict::S('UI:DashboardEdit:DashboardTitle'), $this->sTitle);
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('auto_reload', Dict::S('UI:DashboardEdit:AutoReload'), $this->bAutoReload);
		$oForm->AddField($oField);

		$oField = new DesignerIntegerField('auto_reload_sec', Dict::S('UI:DashboardEdit:AutoReloadSec'), $this->iAutoReloadSec);
		$oField->SetBoundaries(MetaModel::GetConfig()->Get('min_reload_interval'), null); // no upper limit
		$oForm->AddField($oField);


		$this->SetFormParams($oForm, $aExtraParams);
		$oForm->RenderAsPropertySheet($oPage, false, '.itop-dashboard');

		$oPage->add('</div>');

		$sRateTitle = addslashes(Dict::Format('UI:DashboardEdit:AutoReloadSec+', MetaModel::GetConfig()->Get('min_reload_interval')));
		$oPage->add_ready_script(
<<<EOF
	// Note: the title gets deleted by the validation mechanism
	$("#attr_auto_reload_sec").tooltip({items: 'input', content: '$sRateTitle'});
	$("#attr_auto_reload_sec").prop('disabled', !$('#attr_auto_reload').is(':checked'));
	
	$('#attr_auto_reload').change( function(ev) {
		$("#attr_auto_reload_sec").prop('disabled', !$(this).is(':checked'));
	} );

	$('#select_layout').buttonset();
	$('#select_dashlet').droppable({
		accept: '.dashlet',
		drop: function(event, ui) {
			$( this ).find( ".placeholder" ).remove();
			var oDashlet = ui.draggable.data('itopDashlet');
			oDashlet._remove_dashlet();
		},
	});

	$('#event_bus').bind('dashlet-selected', function(event, data){
		var sDashletId = data.dashlet_id;
		var sPropId = 'dashlet_properties_'+sDashletId;
		$('.dashlet_properties').each(function() {
			var sId = $(this).attr('id');
			var bShow = (sId == sPropId);
			if (bShow)
			{
				$(this).show();
			}
			else
			{
				$(this).hide();
			}
		});
	});
EOF
		);
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 * @param bool $bCanEdit
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array(), $bCanEdit = true)
	{
		if (!array_key_exists('dashboard_div_id', $aExtraParams))
		{
			$aExtraParams['dashboard_div_id'] = utils::Sanitize($this->GetId(), '', 'element_identifier');
		}

		$oPage->add('<div class="dashboard-title-line"><div class="dashboard-title">'.htmlentities(Dict::S($this->sTitle), ENT_QUOTES, 'UTF-8', false).'</div></div>');

		/** @var \DashboardLayoutMultiCol $oLayout */
		$oLayout = new $this->sLayoutClass();

		foreach($this->aCells as $iCellIdx => $aDashlets)
		{
			foreach($aDashlets as $oDashlet)
			{
				$aDashletCoordinates = $oLayout->GetDashletCoordinates($iCellIdx);
				$this->PrepareDashletForRendering($oDashlet, $aDashletCoordinates, $aExtraParams);
			}
		}

		$oLayout->Render($oPage, $this->aCells, $bEditMode, $aExtraParams);
		if (!$bEditMode)
		{
			$oPage->add_linked_script('../js/dashlet.js');
			$oPage->add_linked_script('../js/dashboard.js');
		}
	}

	/**
	 * @param \WebPage $oPage
	 *
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function RenderDashletsSelection(WebPage $oPage)
	{
		// Toolbox/palette to drag and drop dashlets
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">'.Dict::S('UI:DashboardEdit:Dashlets').'</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();

		$oPage->add('<div id="select_dashlet" style="text-align:center; max-height:120px; overflow-y:auto;">');
		$aAvailableDashlets = $this->GetAvailableDashlets();
		foreach($aAvailableDashlets as $sDashletClass => $aInfo)
		{
			$oPage->add('<span dashlet_class="'.$sDashletClass.'" class="dashlet_icon ui-widget-content ui-corner-all" id="dashlet_'.$sDashletClass.'" title="'.$aInfo['label'].'" style="width:34px; height:34px; display:inline-block; margin:2px;"><img src="'.$sUrl.$aInfo['icon'].'" /></span>');
		}
		$oPage->add('</div>');

		$oPage->add('</div>');
		$oPage->add_ready_script("$('.dashlet_icon').draggable({helper: 'clone', appendTo: 'body', zIndex: 10000, revert:'invalid'});");
	}

	/**
	 * @param \WebPage $oPage
	 * @param array $aExtraParams
	 */
	public function RenderDashletsProperties(WebPage $oPage, $aExtraParams = array())
	{
		// Toolbox/palette to edit the properties of each dashlet
		$oPage->add('<div class="ui-widget-content ui-corner-all"><div class="ui-widget-header ui-corner-all" style="text-align:center; padding: 2px;">'.Dict::S('UI:DashboardEdit:DashletProperties').'</div>');

		/** @var \DashboardLayoutMultiCol $oLayout */
		$oLayout = new $this->sLayoutClass();

		$oPage->add('<div id="dashlet_properties" style="text-align:center">');
		foreach($this->aCells as $iCellIdx => $aCell)
		{
			/** @var \Dashlet $oDashlet */
			foreach($aCell as $oDashlet)
			{
				if ($oDashlet->IsVisible())
				{
					$oPage->add('<div class="dashlet_properties" id="dashlet_properties_'.$oDashlet->GetID().'" style="display:none">');
					$oForm = $oDashlet->GetForm();
					$this->SetFormParams($oForm, $aExtraParams);
					$oForm->RenderAsPropertySheet($oPage, false, '.itop-dashboard');
					$oPage->add('</div>');
				}
			}
		}
		$oPage->add('</div>');

		$oPage->add('</div>');
	}

	/**
	 * Return an array of dashlets available for selection.
	 *
	 * @return array
	 * @throws \ReflectionException
	 */
	protected function GetAvailableDashlets()
	{
		$aDashlets = array();

		foreach( get_declared_classes() as $sDashletClass)
		{
			// DashletUnknown is not among the selection as it is just a fallback for dashlets that can't instanciated.
			if ( is_subclass_of($sDashletClass, 'Dashlet') && !in_array($sDashletClass, array('DashletUnknown', 'DashletProxy')) )
			{
				$oReflection = new ReflectionClass($sDashletClass);
				if (!$oReflection->isAbstract())
				{
					$aCallSpec = array($sDashletClass, 'IsVisible');
					$bVisible = call_user_func($aCallSpec);
					if ($bVisible)
					{
						$aCallSpec = array($sDashletClass, 'GetInfo');
						$aInfo = call_user_func($aCallSpec);
						$aDashlets[$sDashletClass] = $aInfo;
					}
				}
			}
		}

		return $aDashlets;
	}

	/**
	 * @return int|mixed
	 */
	protected function GetNewDashletId()
	{
		$iNewId = 0;
		foreach($this->aCells as $aDashlets)
		{
			/** @var \Dashlet $oDashlet */
			foreach($aDashlets as $oDashlet)
			{
				$iNewId = max($iNewId, (int)$oDashlet->GetID());
			}
		}
		return $iNewId + 1;
	}

	/**
	 * Prepare dashlet for rendering (eg. change its ID or another processing).
	 * Meant to be overloaded.
	 *
	 * @param \Dashlet $oDashlet
	 * @param array $aCoordinates
	 * @param array $aExtraParams
	 *
	 * @return void
	 */
	abstract protected function PrepareDashletForRendering(Dashlet $oDashlet, $aCoordinates, $aExtraParams = array());

    /**
     * @param \DesignerForm $oForm
     * @param array $aExtraParams
     *
     * @return mixed
     */
	abstract protected function SetFormParams($oForm, $aExtraParams = array());

	/**
	 * @param string $sType
	 * @param \ModelFactory|null $oFactory
	 *
	 * @return string
	 */
	public static function GetDashletClassFromType($sType, $oFactory = null)
	{
		if (is_subclass_of($sType, 'Dashlet'))
		{
			return $sType;
		}
		return 'DashletUnknown';
	}

	/**
	 *  N°2634: we must have a unique id per dashlet!
	 * To avoid collision with other dashlets with the same ID we prefix it with row/cell id
	 * Collisions typically happen with extensions.
	 *
	 * @param boolean $bIsCustomized
	 * @param string $sDashboardDivId
	 * @param int $iRow
	 * @param int $iCol
	 * @param string $sDashletOrigId
	 *
	 * @return string
	 *
	 * @since 2.7.0 N°2735
	 */
	public static function GetDashletUniqueId($bIsCustomized, $sDashboardDivId, $iRow, $iCol, $sDashletOrigId)
	{
		if(strpos($sDashletOrigId, '_ID_row') !== false)
		{
			return $sDashletOrigId;
		}

		$sDashletId = $sDashboardDivId."_ID_row".$iRow."_col".$iCol."_".$sDashletOrigId;
		if ($bIsCustomized)
		{
			$sDashletId = 'CUSTOM_'.$sDashletId;
		}

		return $sDashletId;
	}
}

/**
 * Class RuntimeDashboard
 */
class RuntimeDashboard extends Dashboard
{
	/** @var string $sDefinitionFile */
	private $sDefinitionFile = '';
	/** @var null $sReloadURL */
	private $sReloadURL = null;
	/** @var bool $bCustomized */
	protected $bCustomized;

	/**
	 * @inheritDoc
	 */
	public function __construct($sId)
	{
		parent::__construct($sId);
		$this->oMetaModel = new ModelReflectionRuntime();
		$this->bCustomized = false;
	}

	/**
	 * @return bool
	 * @since 2.7.0
	 */
	public function GetCustomFlag()
	{
		return $this->bCustomized;
	}

	/**
	 * @param bool $bCustomized
	 * @since 2.7.0
	 */
	public function SetCustomFlag($bCustomized)
	{
		$this->bCustomized = $bCustomized;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	protected function SetFormParams($oForm, $aExtraParams = array())
	{
		$oForm->SetSubmitParams(utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php', array('operation' => 'update_dashlet_property', 'extra_params' => $aExtraParams));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function Save()
	{
		$sXml = $this->ToXml();
		$oUDSearch = new DBObjectSearch('UserDashboard');
		$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oUDSearch->AddCondition('menu_code', $this->sId, '=');
		$oUDSet = new DBObjectSet($oUDSearch);
		if ($oUDSet->Count() > 0)
		{
			// Assuming there is at most one couple {user, menu}!
			$oUserDashboard = $oUDSet->Fetch();
			$oUserDashboard->Set('contents', $sXml);
		}
		else
		{
			// No such customized dashboard for the current user, let's create a new record
			$oUserDashboard = new UserDashboard();
			$oUserDashboard->Set('user_id', UserRights::GetUserId());
			$oUserDashboard->Set('menu_code', $this->sId);
			$oUserDashboard->Set('contents', $sXml);
		}
		utils::PushArchiveMode(false);
		$oUserDashboard->DBWrite();
		utils::PopArchiveMode();
	}

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function Revert()
	{
		$oUDSearch = new DBObjectSearch('UserDashboard');
		$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oUDSearch->AddCondition('menu_code', $this->sId, '=');
		$oUDSet = new DBObjectSet($oUDSearch);
		if ($oUDSet->Count() > 0)
		{
			// Assuming there is at most one couple {user, menu}!
			$oUserDashboard = $oUDSet->Fetch();
			utils::PushArchiveMode(false);
			$oUserDashboard->DBDelete();
			utils::PopArchiveMode();
		}
	}

	/**
	 * @param string $sDashboardFile file name relative to the current module folder
	 * @param string $sDashBoardId code of the dashboard either menu_id or <class>__<attcode>
	 *
	 * @return null|RuntimeDashboard
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetDashboard($sDashboardFile, $sDashBoardId)
	{
		$bCustomized = false;

		if (!appUserPreferences::GetPref('display_original_dashboard_'.$sDashBoardId, false))
		{
			// Search for an eventual user defined dashboard
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $sDashBoardId, '=');
			$oUDSet = new DBObjectSet($oUDSearch);
			if ($oUDSet->Count() > 0)
			{
				// Assuming there is at most one couple {user, menu}!
				$oUserDashboard = $oUDSet->Fetch();
				$sDashboardDefinition = $oUserDashboard->Get('contents');
				$bCustomized = true;
			}
			else
			{
				$sDashboardDefinition = @file_get_contents($sDashboardFile);
			}
		}
		else
		{
			$sDashboardDefinition = @file_get_contents($sDashboardFile);
		}

		if ($sDashboardDefinition !== false)
		{
			$oDashboard = new RuntimeDashboard($sDashBoardId);
			$oDashboard->FromXml($sDashboardDefinition);
			$oDashboard->SetCustomFlag($bCustomized);
			$oDashboard->SetDefinitionFile($sDashboardFile);
		}
		else
		{
			$oDashboard = null;
		}
		return $oDashboard;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = array(), $bCanEdit = true)
	{
		if (!isset($aExtraParams['query_params']) && isset($aExtraParams['this->class']))
		{
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aRenderParams = array('query_params' => $oObj->ToArgsForQuery());
		}
		else
		{
			$aRenderParams = $aExtraParams;
		}

		parent::Render($oPage, $bEditMode, $aRenderParams);

		if (isset($aExtraParams['query_params']['this->object()']))
		{
			/** @var \DBObject $oObj */
			$oObj = $aExtraParams['query_params']['this->object()'];
			$aAjaxParams = array('this->class' => get_class($oObj), 'this->id' => $oObj->GetKey());
		}
		else
		{
			$aAjaxParams = $aExtraParams;
		}
		if (!$bEditMode && !$oPage->IsPrintableVersion())
		{
			$sId = $this->GetId();
			$sDivId = utils::Sanitize($sId, '', 'element_identifier');
			if ($this->GetAutoReload())
			{
				$sFile = addslashes($this->GetDefinitionFile());
				$sExtraParams = json_encode($aAjaxParams);
				$iReloadInterval = 1000 * $this->GetAutoReloadInterval();
				$sReloadURL = $this->GetReloadURL();
				$oPage->add_script(
<<<EOF
				if (typeof(AutoReloadDashboardId$sDivId) !== 'undefined')
				{
					clearInterval(AutoReloadDashboardId$sDivId);
					delete AutoReloadDashboardId$sDivId;
				}
			
				AutoReloadDashboardId$sDivId = setInterval("ReloadDashboard$sDivId();", $iReloadInterval);

				function ReloadDashboard$sDivId()
				{
					// Do not reload when a dialog box is active
					if (!($('.ui-dialog:visible').length > 0) && $('.dashboard_contents#$sDivId').is(':visible'))
					{
						$('.dashboard_contents#$sDivId').block();
						$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
						   { operation: 'reload_dashboard', dashboard_id: '$sId', file: '$sFile', extra_params: $sExtraParams, reload_url: '$sReloadURL'},
						   function(data){
							 $('.dashboard_contents#$sDivId').html(data);
							 $('.dashboard_contents#$sDivId').unblock();
							}
						 );
					}
				}
EOF
				);
			}
			else
			{
				$oPage->add_script(
<<<EOF
				if (typeof(AutoReloadDashboardId$sDivId) !== 'undefined')
				{
					clearInterval(AutoReloadDashboardId$sDivId);
					delete AutoReloadDashboardId$sDivId;
				}
EOF
				);
			}

			if ($bCanEdit)
			{
				$this->RenderSelector($oPage, $aAjaxParams);
				$this->RenderEditionTools($oPage, $aAjaxParams);
			}
		}
	}

	/**
	 * @param \iTopWebPage $oPage
	 * @param array $aAjaxParams
	 */
	protected function RenderSelector($oPage, $aAjaxParams = array())
	{
		$sId = $this->GetId();
		$sDivId = utils::Sanitize($sId, '', 'element_identifier');
		$sExtraParams = json_encode($aAjaxParams);

		$sSelectorHtml = '<div class="dashboard-selector">';
		if ($this->HasCustomDashboard())
		{
			$bStandardSelected = appUserPreferences::GetPref('display_original_dashboard_'.$sId, false);
			$sStandard = Dict::S('UI:Toggle:StandardDashboard');
			$sSelectorHtml .= '<div class="selector-label">'.$sStandard.'</div>';
			$sSelectorHtml .= '<label class="switch"><input type="checkbox" onchange="ToggleDashboardSelector'.$sDivId.'();" '.($bStandardSelected ? '' : 'checked').'><span class="slider round"></span></label></input></label>';
			$sCustom = Dict::S('UI:Toggle:CustomDashboard');
			$sSelectorHtml .= '<div class="selector-label">'.$sCustom.'</div>';

		}
		$sSelectorHtml .= '</div>';
		$sSelectorHtml = addslashes($sSelectorHtml);
		$sFile = addslashes($this->GetDefinitionFile());
		$sReloadURL = $this->GetReloadURL();

		$oPage->add_ready_script(
<<<EOF
	$('.dashboard-title').after('$sSelectorHtml');
EOF
		);

		$oPage->add_script(
<<<EOF
			function ToggleDashboardSelector$sDivId()
			{
				$('.dashboard_contents#$sDivId').block();
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
				   { operation: 'toggle_dashboard', dashboard_id: '$sId', file: '$sFile', extra_params: $sExtraParams, reload_url: '$sReloadURL' },
				   function(data) {
					 $('.dashboard_contents#$sDivId').html(data);
					 $('.dashboard_contents#$sDivId').unblock();
					}
				 );
			}
EOF
		);
	}

	/**
	 * @return bool
	 */
	protected function HasCustomDashboard()
	{
		try
		{
			// Search for an eventual user defined dashboard
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $this->GetId(), '=');
			$oUDSet = new DBObjectSet($oUDSearch);

			return ($oUDSet->Count() > 0);
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	/**
	 * @param \WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \Exception
	 */
	protected function RenderEditionTools(WebPage $oPage, $aExtraParams)
	{
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.iframe-transport.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.fileupload.js');
		$sEditMenu = "<div id=\"DashboardMenu\"><ul><li><i class=\"top-right-icon icon-additional-arrow fas fa-pencil-alt\"></i><ul>";
	
		$aActions = array();
		$sFile = addslashes($this->sDefinitionFile);
		$sJSExtraParams = json_encode($aExtraParams);
		$bCanEdit = true;
		if ($this->HasCustomDashboard())
		{
			$bCanEdit = !appUserPreferences::GetPref('display_original_dashboard_'.$this->GetId(), false);
		}
		if ($bCanEdit)
		{
			$oEdit = new JSPopupMenuItem('UI:Dashboard:Edit', Dict::S('UI:Dashboard:Edit'), "return EditDashboard('{$this->sId}', '$sFile', $sJSExtraParams)");
			$aActions[$oEdit->GetUID()] = $oEdit->GetMenuItem();
		}

		if ($this->bCustomized)
		{
			$oRevert = new JSPopupMenuItem('UI:Dashboard:RevertConfirm', Dict::S('UI:Dashboard:Revert'),
											"if (confirm('".addslashes(Dict::S('UI:Dashboard:RevertConfirm'))."')) return RevertDashboard('{$this->sId}', $sJSExtraParams); else return false");
			$aActions[$oRevert->GetUID()] = $oRevert->GetMenuItem();
		}
		utils::GetPopupMenuItems($oPage, iPopupMenuExtension::MENU_DASHBOARD_ACTIONS, $this, $aActions);
		$sEditMenu .= $oPage->RenderPopupMenuItems($aActions);
		$sEditMenu = addslashes($sEditMenu);
		$sReloadURL = $this->GetReloadURL();
		$oPage->add_ready_script(
<<<EOF
	$('.dashboard-title').after('$sEditMenu');
	$('#DashboardMenu>ul').popupmenu();
	
EOF
		);
		$oPage->add_script(
<<<EOF
function EditDashboard(sId, sDashboardFile, aExtraParams)
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'dashboard_editor', id: sId, file: sDashboardFile, extra_params: aExtraParams, reload_url: '$sReloadURL'},
		function(data)
		{
			$('body').append(data);
		}
	);
	return false;
}
function RevertDashboard(sId, aExtraParams)
{
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'revert_dashboard', dashboard_id: sId, extra_params: aExtraParams, reload_url: '$sReloadURL'},
		function(data)
		{
			location.reload();
		}
	);
	return false;
}
EOF
		);
	}

	/**
	 * @inheritDoc
	 */
	public function RenderProperties($oPage, $aExtraParams = array())
	{
		parent::RenderProperties($oPage, $aExtraParams);

		$oPage->add_ready_script(
<<<EOF
	$('#select_layout input').click( function() {
		var sLayoutClass = $(this).val();
		$('.itop-dashboard').runtimedashboard('option', {layout_class: sLayoutClass});
	} );
	$('#row_attr_dashboard_title').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: false, 'do_apply': function() {
			var sTitle = $('#attr_dashboard_title').val();
			$('.itop-dashboard').runtimedashboard('option', {title: sTitle});
			return true;
		}
	});
	$('#row_attr_auto_reload').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: true, 'do_apply': function() {
			var bAutoReload = $('#attr_auto_reload').is(':checked');
			$('.itop-dashboard').runtimedashboard('option', {auto_reload: bAutoReload});
			return true;
		}
	});
	$('#row_attr_auto_reload_sec').property_field('option', {parent_selector: '.itop-dashboard', auto_apply: true, 'do_apply': function() {
			var iAutoReloadSec = $('#attr_auto_reload_sec').val();
			$('.itop-dashboard').runtimedashboard('option', {auto_reload_sec: iAutoReloadSec});
			return true;
		}
	});
EOF
		);
	}


	/**
	 * @param \WebPage $oPage
	 *
	 * @param array $aExtraParams
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function RenderEditor($oPage, $aExtraParams = array())
	{
		if (isset($aExtraParams['this->class']))
		{
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aRenderParams = array('query_params' => $oObj->ToArgsForQuery());
		}
		else
		{
			$aRenderParams = $aExtraParams;
		}
		$aRenderParams['dashboard_div_id'] = $aExtraParams['dashboard_div_id'];
		$sJSExtraParams = json_encode($aExtraParams);
		$oPage->add('<div id="dashboard_editor">');
		$oPage->add('<div class="ui-layout-center">');
		$this->SetCustomFlag(true);
		$this->Render($oPage, true, $aRenderParams);
		$oPage->add('</div>');
		$oPage->add('<div class="ui-layout-east">');
		$this->RenderProperties($oPage, $aExtraParams);
		$this->RenderDashletsSelection($oPage);
		$this->RenderDashletsProperties($oPage, $aExtraParams);
		$oPage->add('</div>');
		$oPage->add('<div id="event_bus"/>'); // For exchanging messages between the panes, same as in the designer
		$oPage->add('</div>');
		
		$sDialogTitle = Dict::S('UI:DashboardEdit:Title');
		$sOkButtonLabel = Dict::S('UI:Button:Save');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		
		$sId = addslashes($this->sId);
		$sLayoutClass = addslashes($this->sLayoutClass);
		$sAutoReload = $this->bAutoReload ? 'true' : 'false';
		$sAutoReloadSec = (string) $this->iAutoReloadSec;
		$sTitle = addslashes($this->sTitle);
		$sFile = addslashes($this->GetDefinitionFile());
		$sUrl = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php';
		$sReloadURL = $this->GetReloadURL();

		$sExitConfirmationMessage = addslashes(Dict::S('UI:NavigateAwayConfirmationMessage'));
		$sCancelConfirmationMessage = addslashes(Dict::S('UI:CancelConfirmationMessage'));
		$sAutoApplyConfirmationMessage = addslashes(Dict::S('UI:AutoApplyConfirmationMessage'));
		
		$oPage->add_ready_script(
<<<JS
window.bLeavingOnUserAction = false;

$('#dashboard_editor').dialog({
	height: $('body').height() - 50,
	width: $('body').width() - 50,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sOkButtonLabel", click: function() {
		var oDashboard = $('.itop-dashboard').data('itopRuntimedashboard');
		if (oDashboard.is_dirty())
		{
			if (!confirm('$sAutoApplyConfirmationMessage'))
			{
				return;
			}
			else
			{
				oDashboard.apply_changes();
			}
		}
		window.bLeavingOnUserAction = true;
		oDashboard.save($(this));
	} },
	{ text: "$sCancelButtonLabel", click: function() {
		var oDashboard = $('.itop-dashboard').data('itopRuntimedashboard');
		if (oDashboard.is_modified())
		{
			if (!confirm('$sCancelConfirmationMessage'))
			{
				return;
			}
		}
		window.bLeavingOnUserAction = true;
		$(this).dialog( "close" );
		$(this).remove();
	} },
	],
	close: function() { $(this).remove(); }
});

$('#dashboard_editor .ui-layout-center').runtimedashboard({
	dashboard_id: '$sId', 
	layout_class: '$sLayoutClass', 
	title: '$sTitle',
	auto_reload: $sAutoReload, 
	auto_reload_sec: $sAutoReloadSec,
	submit_to: '$sUrl', 
	submit_parameters: {operation: 'save_dashboard', file: '$sFile', extra_params: $sJSExtraParams, reload_url: '$sReloadURL'},
	render_to: '$sUrl', 
	render_parameters: {operation: 'render_dashboard', file: '$sFile', extra_params: $sJSExtraParams, reload_url: '$sReloadURL'},
	new_dashlet_parameters: {operation: 'new_dashlet'}
});

dashboard_prop_size = GetUserPreference('dashboard_prop_size', 350);
$('#dashboard_editor').layout({
	east: {
		minSize: 200,
		size: dashboard_prop_size,
		togglerLength_open: 0,
		togglerLength_closed: 0, 
		onresize_end: function(name, elt, state, options, layout)
		{
			if (state.isSliding == false)
			{
				SetUserPreference('dashboard_prop_size', state.size, true);
			}
		},
	}
});

window.onbeforeunload = function() {
	if (!window.bLeavingOnUserAction)
	{
		var oDashboard = $('.itop-dashboard').data('itopRuntimedashboard');
		if (oDashboard)
		{
			if (oDashboard.is_dirty())
			{
				return '$sExitConfirmationMessage';
			}	
			if (oDashboard.is_modified())
			{
				return '$sExitConfirmationMessage';
			}
		}	
	}
	// return nothing ! safer for IE
};
JS
		);
		$oPage->add_ready_script("");
	}

	/**
	 * @param string|null $sOQL
	 *
	 * @return \DesignerForm
	 * @throws \DictExceptionMissingString
	 * @throws \ReflectionException
	 */
	public static function GetDashletCreationForm($sOQL = null)
	{
		$oAppContext = new ApplicationContext();
		$sContextMenuId = $oAppContext->GetCurrentValue('menu', null);

		$oForm = new DesignerForm();
	
		// Get the list of all 'dashboard' menus in which we can insert a dashlet
		$aAllMenus = ApplicationMenu::ReflectionMenuNodes();
		$sRootMenuId = ApplicationMenu::GetRootMenuId($sContextMenuId);
		$aAllowedDashboards = array();
		$sDefaultDashboard = null;

		// Store the parent menus for acces check
        $aParentMenus = array();
        foreach($aAllMenus as $idx => $aMenu)
        {
            /** @var MenuNode $oMenu */
            $oMenu = $aMenu['node'];
            if (count(ApplicationMenu::GetChildren($oMenu->GetIndex())) > 0)
            {
                $aParentMenus[$oMenu->GetMenuId()] = $aMenu;
            }
        }

        foreach($aAllMenus as $idx => $aMenu)
		{
			$oMenu = $aMenu['node'];
            if ($oMenu instanceof DashboardMenuNode)
            {
                // Get the root parent for access check
                $sParentId = $aMenu['parent'];
                $aParentMenu = $aParentMenus[$sParentId];
                while (isset($aParentMenus[$aParentMenu['parent']]))
                {
                    // grand parent exists
                    $sParentId = $aParentMenu['parent'];
                    $aParentMenu = $aParentMenus[$sParentId];
                }
	            /** @var \MenuNode $oParentMenu */
	            $oParentMenu = $aParentMenu['node'];
                if ($oMenu->IsEnabled() && $oParentMenu->IsEnabled())
                {
                    $sMenuLabel = $oMenu->GetTitle();
                    $sParentLabel = Dict::S('Menu:'.$sParentId);
                    if ($sParentLabel != $sMenuLabel)
                    {
                        $aAllowedDashboards[$oMenu->GetMenuId()] = $sParentLabel.' - '.$sMenuLabel;
                    }
                    else
                    {
                        $aAllowedDashboards[$oMenu->GetMenuId()] = $sMenuLabel;
                    }
                    if (empty($sDefaultDashboard) && ($sRootMenuId == ApplicationMenu::GetRootMenuId($oMenu->GetMenuId())))
                    {
                        $sDefaultDashboard = $oMenu->GetMenuId();
                    }
                }
            }
		}
		asort($aAllowedDashboards);
		
		$oField = new DesignerComboField('menu_id', Dict::S('UI:DashletCreation:Dashboard'), $sDefaultDashboard);
		$oField->SetAllowedValues($aAllowedDashboards);
		$oField->SetMandatory(true);
		$oForm->AddField($oField);
				
		// Get the list of possible dashlets that support a creation from
		// an OQL
		$aDashlets = array();
		foreach(get_declared_classes() as $sDashletClass)
		{
			if (is_subclass_of($sDashletClass, 'Dashlet'))
			{
				$oReflection = new ReflectionClass($sDashletClass);
				if (!$oReflection->isAbstract())
				{
					$aCallSpec = array($sDashletClass, 'CanCreateFromOQL');
					$bShorcutMode = call_user_func($aCallSpec);
					if ($bShorcutMode)
					{
						$aCallSpec = array($sDashletClass, 'GetInfo');
						$aInfo = call_user_func($aCallSpec);
						$aDashlets[$sDashletClass] = array('label' => $aInfo['label'], 'class' => $sDashletClass, 'icon' => $aInfo['icon']);
					}
				}
			}
		}
		
		$oSelectorField = new DesignerFormSelectorField('dashlet_class', Dict::S('UI:DashletCreation:DashletType'), '');
		$oForm->AddField($oSelectorField);
		foreach($aDashlets as $sDashletClass => $aDashletInfo)
		{
			$oSubForm = new DesignerForm();
			$oMetaModel = new ModelReflectionRuntime();
			/** @var \Dashlet $oDashlet */
			$oDashlet = new $sDashletClass($oMetaModel, 0);
			$oDashlet->GetPropertiesFieldsFromOQL($oSubForm, $sOQL);
			
			$oSelectorField->AddSubForm($oSubForm, $aDashletInfo['label'], $aDashletInfo['class']);
		}
		$oField = new DesignerBooleanField('open_editor', Dict::S('UI:DashletCreation:EditNow'), true);
		$oForm->AddField($oField);
		
		return $oForm;
	}

	/**
	 * @param \WebPage $oPage
	 * @param $sOQL
	 *
	 * @throws \DictExceptionMissingString
	 * @throws \ReflectionException
	 */
	public static function GetDashletCreationDlgFromOQL($oPage, $sOQL)
	{
		$oPage->add('<div id="dashlet_creation_dlg">');

		$oForm = self::GetDashletCreationForm($sOQL);

		$oForm->Render($oPage);
		$oPage->add('</div>');
		
		$sDialogTitle = Dict::S('UI:DashletCreation:Title');
		$sOkButtonLabel = Dict::S('UI:Button:Ok');
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');
		
		$oPage->add_ready_script(
<<<JS
$('#dashlet_creation_dlg').dialog({
	width: 600,
	modal: true,
	title: '$sDialogTitle',
	buttons: [
	{ text: "$sOkButtonLabel", click: function() {
		var oForm = $(this).find('form');
		var sFormId = oForm.attr('id');
		var oParams = null;
		var aErrors = ValidateForm(sFormId, false);
		if (aErrors.length == 0)
		{
			oParams = ReadFormParams(sFormId);
		}
		oParams.operation = 'add_dashlet';
		var me = $(this);
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data) {
			me.dialog( "close" );
			me.remove();
			$('body').append(data);
		});
	} },
	{ text: "$sCancelButtonLabel", click: function() {
		$(this).dialog( "close" ); $(this).remove();
	} },
	],
	close: function() { $(this).remove(); }
});
JS
		);
	}

	/**
	 * @return string
	 */
	public function GetDefinitionFile()
	{
		return $this->sDefinitionFile;
	}

	/**
	 * @param string $sDefinitionFile
	 */
	public function SetDefinitionFile($sDefinitionFile)
	{
		$this->sDefinitionFile = $sDefinitionFile;
	}

	/**
	 * @return string|null
	 */
	public function GetReloadURL()
	{
		return $this->sReloadURL;
	}

	/**
	 * @param string $sReloadURL
	 */
	public function SetReloadURL($sReloadURL)
	{
		$this->sReloadURL = $sReloadURL;
	}

	/**
	 * @inheritDoc
	 */
	protected function PrepareDashletForRendering(Dashlet $oDashlet, $aCoordinates, $aExtraParams = array())
	{
		$sDashletIdOrig = $oDashlet->GetID();
		$sDashboardSanitizedId = $this->GetSanitizedId();
		$sDashletIdNew = static::GetDashletUniqueId($this->GetCustomFlag(), $sDashboardSanitizedId, $aCoordinates[1], $aCoordinates[0], $sDashletIdOrig);
		$oDashlet->SetID($sDashletIdNew);
		$this->UpdateDashletUserPrefs($oDashlet, $sDashletIdOrig, $aExtraParams);
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
	private function UpdateDashletUserPrefs(Dashlet $oDashlet, $sDashletIdOrig, array $aExtraParams)
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

		$sNewPrefKey = $this->GetDashletObjectListAppUserPreferencesPrefix($oDashlet, $aExtraParams, $oDashlet->GetID());
		$sPrefValueForNewKey = appUserPreferences::GetPref($sNewPrefKey, null);
		$bHasPrefInNewFormat = ($sPrefValueForNewKey !== null);
		if ($bHasPrefInNewFormat)
		{
			return;
		}

		$sOldPrefKey = $this->GetDashletObjectListAppUserPreferencesPrefix($oDashlet, $aExtraParams, $sDashletIdOrig);
		$sPrefValueForOldKey = appUserPreferences::GetPref($sOldPrefKey, null);
		$bHasPrefInOldFormat = ($sPrefValueForOldKey !== null);
		if (!$bHasPrefInOldFormat)
		{
			return;
		}

		appUserPreferences::SetPref($sNewPrefKey, $sPrefValueForOldKey);
		appUserPreferences::UnsetPref($sOldPrefKey);
	}

	/**
	 * @param \DashletObjectList $oDashlet
	 * @param array $aExtraParams
	 * @param string $sDashletId
	 *
	 * @return string
	 * @since 2.7.0
	 */
	private function GetDashletObjectListAppUserPreferencesPrefix(DashletObjectList $oDashlet, $aExtraParams, $sDashletId)
	{
		$sDataTableId = Dashlet::APPUSERPREFERENCES_PREFIX.$sDashletId;
		$aClassAliases = array();
		try{
			$oFilter = $oDashlet->GetDBSearch($aExtraParams);
			$aClassAliases = $oFilter->GetSelectedClasses();
		}
		catch (Exception $e)
		{
			//on error, return default value
			return null;
		}
		return DataTableSettings::GetAppUserPreferenceKey($aClassAliases, $sDataTableId);
	}
}

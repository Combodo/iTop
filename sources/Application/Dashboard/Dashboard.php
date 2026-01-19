<?php

namespace Combodo\iTop\Application\Dashboard;

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Dashboard\Layout\DashboardLayoutGrid;
use Combodo\iTop\Application\Dashlet\Dashlet;
use Combodo\iTop\Application\Dashlet\DashletFactory;
use Combodo\iTop\Application\Dashlet\Service\DashletService;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\DesignDocument;
use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\PropertyTypeDesign;
use DashboardLayout;
use DesignerBooleanField;
use DesignerForm;
use DesignerHiddenField;
use DesignerIntegerField;
use DesignerTextField;
use Dict;
use DOMException;
use InvalidParameterException;
use MetaModel;
use ReflectionClass;
use utils;

/**
 *
 * A user editable dashboard page
 *
 */
abstract class Dashboard
{
	/** @var string $sTitle */
	protected $sTitle;
	/** @var bool $bAutoReload */
	protected $bAutoReload;
	/** @var float|int $iAutoReloadSec */
	protected $iAutoReloadSec;
	/** @var string $sLayoutClass */
	protected $sLayoutClass;
	/** @var array $aWidgetsData */
	protected $aWidgetsData;
	/** @var DesignElement|null $oDOMNode */
	protected $oDOMNode;
	/** @var string $sId */
	protected $sId;
	/** @var array $aCells */
	protected $aCells;
	/** @var \ModelReflection $oMetaModel */
	protected $oMetaModel;

	/** @var array Array of dashlets with position */
	protected array $aGridDashlets = [];

	protected $oDashletFactory;

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
		$this->aCells = [];
		$this->oDOMNode = null;
		$this->sId = $sId;
		$this->oDashletFactory = DashletFactory::GetInstance();
	}

	/**
	 * @param string $sXml
	 *
	 * @throws \Exception
	 */
	public function FromXml($sXml)
	{
		$this->aCells = []; // reset the content of the dashboard
		set_error_handler(['Dashboard', 'ErrorHandler']);
		$oDoc = new PropertyTypeDesign();
		$oDoc->loadXML($sXml);
		restore_error_handler();
		$this->FromDOMDocument($oDoc);
	}

	/**
	 * @param \DOMDocument $oDoc
	 */
	public function FromDOMDocument(DesignDocument $oDoc)
	{
		$this->oDOMNode = $oDoc->getElementsByTagName('dashboard')->item(0);

		if ($this->oDOMNode->getElementsByTagName('cells')->count() === 0) {
			$this->FromDOMDocumentV2($oDoc);
			return;
		}

		if ($oLayoutNode = $this->oDOMNode->getElementsByTagName('layout')->item(0)) {
			$this->sLayoutClass = $oLayoutNode->textContent;
		} else {
			$this->sLayoutClass = 'DashboardLayoutOneCol';
		}

		if ($oTitleNode = $this->oDOMNode->getElementsByTagName('title')->item(0)) {
			$this->sTitle = $oTitleNode->textContent;
		} else {
			$this->sTitle = '';
		}

		$this->bAutoReload = false;
		$this->iAutoReloadSec = MetaModel::GetConfig()->GetStandardReloadInterval();
		if ($oAutoReloadNode = $this->oDOMNode->getElementsByTagName('auto_reload')->item(0)) {
			if ($oAutoReloadEnabled = $oAutoReloadNode->getElementsByTagName('enabled')->item(0)) {
				$this->bAutoReload = ($oAutoReloadEnabled->textContent == 'true');
			}
			if ($oAutoReloadInterval = $oAutoReloadNode->getElementsByTagName('interval')->item(0)) {
				$this->iAutoReloadSec = max(MetaModel::GetConfig()->Get('min_reload_interval'), (int)$oAutoReloadInterval->textContent);
			}
		}

		if ($oCellsNode = $this->oDOMNode->getElementsByTagName('cells')->item(0)) {
			$oCellsList = $oCellsNode->getElementsByTagName('cell');
			$aCellOrder = [];
			$iCellRank = 0;
			/** @var \DOMElement $oCellNode */
			foreach ($oCellsList as $oCellNode) {
				$oCellRank = $oCellNode->getElementsByTagName('rank')->item(0);
				if ($oCellRank) {
					$iCellRank = (float)$oCellRank->textContent;
				}
				$oDashletsNode = $oCellNode->getElementsByTagName('dashlets')->item(0);
				{
					$oDashletList = $oDashletsNode->getElementsByTagName('dashlet');
					$iRank = 0;
					$aDashletOrder = [];
					/** @var DesignElement $oDomNode */
					foreach ($oDashletList as $oDomNode) {
						$oRank = $oDomNode->getElementsByTagName('rank')->item(0);
						if ($oRank) {
							$iRank = (float)$oRank->textContent;
						}

						$oNewDashlet = $this->InitDashletFromDOMNode($oDomNode);
						$aDashletOrder[] = ['rank' => $iRank, 'dashlet' => $oNewDashlet];
					}
					usort($aDashletOrder, [get_class($this), 'SortOnRank']);
					$aDashletList = [];
					foreach ($aDashletOrder as $aItem) {
						$aDashletList[] = $aItem['dashlet'];
					}
					$aCellOrder[] = ['rank' => $iCellRank, 'dashlets' => $aDashletList];
				}
			}
			usort($aCellOrder, [get_class($this), 'SortOnRank']);
			foreach ($aCellOrder as $aItem) {
				$this->aCells[] = $aItem['dashlets'];
			}
		} else {
			$this->aCells = [];
		}
	}

	/**
	 * @param \DOMDocument $oDoc
	 */
	public function FromDOMDocumentV2(DesignDocument $oDoc)
	{
		$this->oDOMNode = $oDoc->getElementsByTagName('dashboard')->item(0);

		$this->sLayoutClass = DashboardLayoutGrid::class;
		$this->sTitle = $this->oDOMNode->GetChildText('title', '');

		$iRefresh = intval($this->oDOMNode->GetChildText('refresh', '0'));

		$this->bAutoReload = $iRefresh > 0;
		$this->iAutoReloadSec = $iRefresh;

		$oDashletsNode = $this->oDOMNode->GetUniqueElement('pos_dashlets');
		$oDashletList = $oDashletsNode->getElementsByTagName('pos_dashlet');
		foreach ($oDashletList as $oPosDashletNode) {
			$aGridDashlet = [];
			$aGridDashlet['position_x'] = intval($oPosDashletNode->GetChildText('position_x', '0'));
			$aGridDashlet['position_y'] = intval($oPosDashletNode->GetChildText('position_y', '0'));
			$aGridDashlet['width'] = intval($oPosDashletNode->GetChildText('width', '2'));
			$aGridDashlet['height'] = intval($oPosDashletNode->GetChildText('height', '1'));
			$oDashletNode = $oPosDashletNode->GetUniqueElement('dashlet');
			$sId = $oPosDashletNode->getAttribute('id');
			$oDashlet = $this->InitDashletFromDOMNode($oDashletNode);
			$oDashlet->SetID($sId);
			$aGridDashlet['dashlet'] = $oDashlet;
			$this->aGridDashlets[] = $aGridDashlet;
		}
	}

	/**
	 * @param DesignElement $oDomNode
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
		$oNewDashlet = $this->oDashletFactory->CreateDashlet($sClass, $sId);
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
		if ($errno == E_WARNING && (substr_count($errstr, "DOMDocument::loadXML()") > 0)) {
			throw new DOMException($errstr);
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function ToXml()
	{
		$oMainNode = $this->CreateEmptyDashboard();

		$this->ToDOMNode($oMainNode);

		$sXml = $oMainNode->ownerDocument->saveXML();

		return $sXml;
	}

	/**
	 * @return DesignElement
	 * @throws \DOMException
	 */
	public function CreateEmptyDashboard(): DesignElement
	{
		$oDoc = new DesignDocument();
		$oDoc->formatOutput = true; // indent (must be loaded with option LIBXML_NOBLANKS)
		$oDoc->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect

		/** @var DesignElement $oMainNode */
		$oMainNode = $oDoc->createElement('dashboard');
		$oMainNode->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$oDoc->appendChild($oMainNode);

		return $oMainNode;
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
		foreach ($this->aCells as $aCell) {
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
			foreach ($aCell as $oDashlet) {
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
		if (!is_subclass_of($this->sLayoutClass, DashboardLayout::class)) {
			throw new InvalidParameterException('Invalid parameter layout_class "'.$aParams['layout_class'].'"');
		}
		$this->sTitle = $aParams['title'];
		$this->bAutoReload = $aParams['auto_reload'] == 'true';
		$this->iAutoReloadSec = max(MetaModel::GetConfig()->Get('min_reload_interval'), (int)$aParams['auto_reload_sec']);

		foreach ($aParams['cells'] as $aCell) {
			$aCellDashlets = [];
			foreach ($aCell as $aDashletParams) {
				$sDashletClass = $aDashletParams['dashlet_class'];
				$sId = $aDashletParams['dashlet_id'];
				/** @var \Dashlet $oNewDashlet */
				$oNewDashlet = $this->oDashletFactory->CreateDashlet($sDashletClass, $sId);
				if (isset($aDashletParams['dashlet_type'])) {
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

	public function PersistDashboard(string $sXml): bool
	{
		return true;
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
		$this->aCells[] = [$oDashlet];
	}

	/**
	 * @param WebPage $oPage *
	 * @param array $aExtraParams
	 *
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function RenderProperties($oPage, $aExtraParams = [])
	{
		// menu to pick a layout and edit other properties of the dashboard
		$oPage->add('<div class="ui-widget-content ui-corner-all ibo-dashboard-editor--properties"><div class="ui-widget-header ui-corner-all ibo-dashboard-editor--properties-title">'.Dict::S('UI:DashboardEdit:Properties').'</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();

		$oPage->add('<div class="ibo-dashboard-editor--properties-subtitle" data-role="ibo-dashboard-editor--properties-subtitle">'.Dict::S('UI:DashboardEdit:Layout').'</div>');
		$oPage->add('<div id="select_layout" class="ibo-dashboard-editor--layout-list" data-role="ibo-dashboard-editor--layout-list">');
		foreach (get_declared_classes() as $sLayoutClass) {
			if (is_subclass_of($sLayoutClass, 'DashboardLayout')) {
				$oReflection = new ReflectionClass($sLayoutClass);
				if (!$oReflection->isAbstract()) {
					$aCallSpec = [$sLayoutClass, 'GetInfo'];
					$aInfo = call_user_func($aCallSpec);
					$sChecked = ($this->sLayoutClass == $sLayoutClass) ? 'checked' : '';
					$oPage->add('<input type="radio" name="layout_class" '.$sChecked.' value="'.$sLayoutClass.'" id="layout_'.$sLayoutClass.'"><label for="layout_'.$sLayoutClass.'"><img src="'.$sUrl.$aInfo['icon'].'" class="ibo-dashboard--properties--icon" data-role="ibo-dashboard--properties--icon"/></label>'); // title="" on either the img or the label does nothing !
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
	$("#attr_auto_reload_sec").attr('data-tooltip-content', '$sRateTitle');
	CombodoTooltip.InitTooltipFromMarkup($("#attr_auto_reload_sec"));
	$("#attr_auto_reload_sec").prop('disabled', !$('#attr_auto_reload').is(':checked'));
	
	$('#attr_auto_reload').on('change', function(ev) {
		$("#attr_auto_reload_sec").prop('disabled', !$(this).is(':checked'));
	} );

	$('#select_layout').controlgroup();
	$('#select_dashlet').droppable({
		accept: '.dashlet',
		drop: function(event, ui) {
			$( this ).find( ".placeholder" ).remove();
			var oDashlet = ui.draggable.data('itopDashlet');
			oDashlet._remove_dashlet();
		},
	});

	$('#event_bus').on('dashlet-selected', function(event, data){
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
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 * @param bool $bCanEdit
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [], $bCanEdit = true)
	{
		$aExtraParams['dashboard_div_id'] = utils::Sanitize($aExtraParams['dashboard_div_id'] ?? null, $this->GetId(), utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);

		/** @var \DashboardLayout $oLayout */
		$oLayout = new $this->sLayoutClass();

		foreach ($this->aCells as $iCellIdx => $aDashlets) {
			foreach ($aDashlets as $oDashlet) {
				$aDashletCoordinates = $oLayout->GetDashletCoordinates($iCellIdx);
				$this->PrepareDashletForRendering($oDashlet, $aDashletCoordinates, $aExtraParams);
			}
		}

		if (count($this->aCells) > 0) {
			$aDashlets = $this->aCells;
		} else {
			$aDashlets = $this->aGridDashlets;
		}

		$oDashboard = $oLayout->Render($oPage, $aDashlets, $bEditMode, $aExtraParams);
		$oPage->AddUiBlock($oDashboard);

		$bFromDasboardPage = isset($aExtraParams['from_dashboard_page']) ? isset($aExtraParams['from_dashboard_page']) : false;

		if ($bFromDasboardPage) {
			$sTitleForHTML = utils::HtmlEntities(Dict::S($this->sTitle));
			$sHtml = "<div class=\"ibo-top-bar--toolbar-dashboard-title\" title=\"{$sTitleForHTML}\">{$sTitleForHTML}</div>";
			if ($oPage instanceof iTopWebPage) {
				$oTopBar = $oPage->GetTopBarLayout();
				$oToolbar = ToolbarUIBlockFactory::MakeStandard();
				$oTopBar->SetToolbar($oToolbar);

				$oToolbar->AddHtml($sHtml);
			} else {
				$oPage->add_script(
					<<<JS
$(".ibo-top-bar--toolbar-dashboard-title").html("$sTitleForHTML").attr("title",  $('<div>').html("$sTitleForHTML").text());
JS
				);
			}
		} else {
			$oDashboard->SetTitle(Dict::S($this->sTitle));
		}

		if (!$bEditMode) {
			$oPage->LinkScriptFromAppRoot('js/dashlet.js');
			$oPage->LinkScriptFromAppRoot('js/dashboard.js');
		}

		return $oDashboard;
	}

	/**
	 * @param WebPage $oPage
	 *
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function RenderDashletsSelection(WebPage $oPage)
	{
		// Toolbox/palette to drag and drop dashlets
		$oPage->add('<div class="ui-widget-content ui-corner-all ibo-dashboard--available-dashlets"><div class="ui-widget-header ui-corner-all ibo-dashboard--available-dashlet--title">'.Dict::S('UI:DashboardEdit:Dashlets').'</div>');
		$sUrl = utils::GetAbsoluteUrlAppRoot();

		$oPage->add('<div id="select_dashlet" class="ibo-dashboard--available-dashlets--list" data-role="ibo-dashboard--available-dashlets--list">');
		$aAvailableDashlets = $this->GetAvailableDashlets();
		foreach ($aAvailableDashlets as $sDashletClass => $aInfo) {
			$oPage->add('<span dashlet_class="'.$sDashletClass.'" class="ibo-dashboard-editor--available-dashlet-icon dashlet_icon ui-widget-content ui-corner-all" data-role="ibo-dashboard-editor--available-dashlet-icon" id="dashlet_'.$sDashletClass.'" data-tooltip-content="'.$aInfo['label'].'"  title="'.$aInfo['label'].'"><img src="'.$sUrl.$aInfo['icon'].'" /></span>');
		}
		$oPage->add('</div>');

		$oPage->add('</div>');
		$oPage->add_ready_script("$('.dashlet_icon').draggable({cursor: 'move', helper: 'clone', appendTo: 'body', zIndex: 10000, revert:'invalid'});");
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 */
	public function RenderDashletsProperties(WebPage $oPage, $aExtraParams = [])
	{
		// Toolbox/palette to edit the properties of each dashlet
		$oPage->add('<div class="ui-widget-content ui-corner-all ibo-dashlet--properties"><div class="ui-widget-header ui-corner-all ibo-dashlet--properties--title">'.Dict::S('UI:DashboardEdit:DashletProperties').'</div>');

		/** @var \DashboardLayoutMultiCol $oLayout */
		$oLayout = new $this->sLayoutClass();

		$oPage->add('<div id="dashlet_properties">');
		foreach ($this->aCells as $iCellIdx => $aCell) {
			/** @var \Dashlet $oDashlet */
			foreach ($aCell as $oDashlet) {
				if ($oDashlet->IsVisible()) {
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
	 * @throws \Combodo\iTop\Application\Dashlet\DashletException
	 * @throws \DOMFormatException
	 */
	protected function GetAvailableDashlets(): array
	{
		return DashletService::GetInstance()->GetAvailableDashlets();
	}

	/**
	 * @return int|mixed
	 */
	protected function GetNewDashletId()
	{
		$iNewId = 0;
		foreach ($this->aCells as $aDashlets) {
			/** @var \Dashlet $oDashlet */
			foreach ($aDashlets as $oDashlet) {
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
	abstract protected function PrepareDashletForRendering(Dashlet $oDashlet, $aCoordinates, $aExtraParams = []);

	/**
	 * @param \DesignerForm $oForm
	 * @param array $aExtraParams
	 *
	 * @return mixed
	 */
	abstract protected function SetFormParams($oForm, $aExtraParams = []);

	/**
	 * @param string $sType
	 * @param \ModelFactory|null $oFactory
	 *
	 * @return string
	 */
	public static function GetDashletClassFromType($sType, $oFactory = null)
	{
		if (is_subclass_of($sType, 'Dashlet')) {
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
		if (strpos($sDashletOrigId, '_ID_row') !== false) {
			return $sDashletOrigId;
		}

		$sDashletId = $sDashboardDivId."_ID_row".$iRow."_col".$iCol."_".$sDashletOrigId;
		if ($bIsCustomized) {
			$sDashletId = 'CUSTOM_'.$sDashletId;
		}

		return $sDashletId;
	}
}

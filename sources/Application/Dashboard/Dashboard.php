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
use Combodo\iTop\PropertyType\Serializer\XMLSerializer;
use DashboardLayout;
use Dict;
use DOMException;
use InvalidParameterException;
use MetaModel;
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

	private XMLSerializer $oXMLSerializer;

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
		$this->oXMLSerializer  = MetaModel::GetService('XMLSerializer');
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
	 * @param string $sXml
	 *
	 * @throws \Exception
	 */
	public function ToXML(): string
	{
		$oDomNode = $this->CreateEmptyDashboard();
		$aModelData = $this->ToModelData();
		$this->oXMLSerializer->Serialize($aModelData, $oDomNode, 'DashboardGrid', 'Dashboard');

		return $oDomNode->ownerDocument->saveXML();
	}

	/**
	 * @param \DOMDocument $oDoc
	 */
	public function FromDOMDocument(DesignDocument $oDoc)
	{
		$this->oDOMNode = $oDoc->getElementsByTagName('dashboard')->item(0);

		if ($this->oDOMNode->getElementsByTagName('cells')->count() === 0) {
			$this->FromDOMDocumentV2($this->oDOMNode);
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
					usort($aDashletOrder, [$this, 'SortOnRank']);
					$aDashletList = [];
					foreach ($aDashletOrder as $aItem) {
						$aDashletList[] = $aItem['dashlet'];
					}
					$aCellOrder[] = ['rank' => $iCellRank, 'dashlets' => $aDashletList];
				}
			}
			usort($aCellOrder, [$this, 'SortOnRank']);
			foreach ($aCellOrder as $aItem) {
				$this->aCells[] = $aItem['dashlets'];
			}
		} else {
			$this->aCells = [];
		}
	}

	/**
	 * @param \DOMDocument $oDOMNode
	 */
	public function FromDOMDocumentV2(DesignElement $oDOMNode)
	{
		$aDashboardValues = $this->oXMLSerializer->Deserialize($oDOMNode, 'DashboardGrid', 'Dashboard');
		$this->FromModelData($aDashboardValues);
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
	public function SortOnRank($aItem1, $aItem2)
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
	 * @param WebPage $oPage
	 * @param bool $bEditMode
	 * @param array $aExtraParams
	 * @param bool $bCanEdit
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardLayout
	 * @throws \Exception
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
		$oDashboard->SetFile(utils::LocalPath($this->GetDefinitionFile()));

		$oPage->AddUiBlock($oDashboard);

		$bFromDashboardPage = isset($aExtraParams['from_dashboard_page']) ? isset($aExtraParams['from_dashboard_page']) : false;

		if ($bFromDashboardPage) {
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

	public function FromModelData(mixed $aDashboardValues)
	{
		$this->sLayoutClass = DashboardLayoutGrid::class;
		$this->sTitle = $aDashboardValues['title'] ?? '';
		$iRefresh = $aDashboardValues['refresh'] ?? 0;
		$this->bAutoReload = $iRefresh > 0;
		$this->iAutoReloadSec = $iRefresh;

		foreach ($aDashboardValues['pos_dashlets'] as $sId => $aPosDashlet) {
			$aGridDashlet = [];
			$aGridDashlet['position_x'] = $aPosDashlet['position_x'] ?? 0;
			$aGridDashlet['position_y'] = $aPosDashlet['position_y'] ?? 0;
			$aGridDashlet['width'] = $aPosDashlet['width'] ?? 2;
			$aGridDashlet['height'] = $aPosDashlet['height'] ?? 1;
			$aDashlet = $aPosDashlet['dashlet'];
			$sType = $aDashlet['type'];
			$oDashlet = DashletFactory::GetInstance()->CreateDashlet($sType, $sId);
			$oDashlet->FromModelData($aDashlet['properties']);
			$aGridDashlet['dashlet'] = $oDashlet;
			$this->aGridDashlets[] = $aGridDashlet;
		}
	}

	public function ToModelData(): array
	{
		$aModelData = [];
		$aModelData['id'] = $this->sId;
		$aModelData['layout'] = $this->sLayoutClass;
		$aModelData['title'] = $this->sTitle;
		$aModelData['refresh'] = $this->bAutoReload ? $this->iAutoReloadSec : 0;

		foreach ($this->aGridDashlets as $aGridDashlet) {
			$aPosDashlet = [];
			$aPosDashlet['position_x'] = $aGridDashlet['position_x'] ?? 0;
			$aPosDashlet['position_y'] = $aGridDashlet['position_y'] ?? 0;
			$aPosDashlet['width'] = $aGridDashlet['width'] ?? 2;
			$aPosDashlet['height'] = $aGridDashlet['height'] ?? 1;
			/** @var Dashlet $oDashlet */
			$oDashlet = $aGridDashlet['dashlet'];
			$aDashlet = [];
			$aDashlet['type'] = $oDashlet->GetDashletType();
			$sId = $oDashlet->GetID();
			$aDashlet['id'] = $sId;
			$aDashlet['properties'] = $oDashlet->ToModelData();
			$aPosDashlet['dashlet'] = $aDashlet;

			$aModelData['pos_dashlets'][$sId] = $aPosDashlet;
		}

		return $aModelData;
	}
}

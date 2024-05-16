<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Portal\Brick;

use Exception;
use DOMFormatException;
use DBSearch;
use MetaModel;
use Combodo\iTop\DesignElement;

class ManageBrick extends PortalBrick
{
	/** @var string ENUM_ACTION_VIEW */
	const ENUM_ACTION_VIEW = 'view';
	/** @var string ENUM_ACTION_EDIT */
	const ENUM_ACTION_EDIT = 'edit';

	/** @var string ENUM_TILE_MODE_TEXT */
	const ENUM_TILE_MODE_TEXT = 'text';
	/** @var string ENUM_TILE_MODE_BADGE */
	const ENUM_TILE_MODE_BADGE = 'badge';
	/** @var string ENUM_TILE_MODE_PIE */
	const ENUM_TILE_MODE_PIE = 'pie-chart';
	/** @var string ENUM_TILE_MODE_BAR */
	const ENUM_TILE_MODE_BAR = 'bar-chart';
	/** @var string ENUM_TILE_MODE_TOP */
	const ENUM_TILE_MODE_TOP = 'top-list';

	/** @var string ENUM_DISPLAY_MODE_LIST */
	const ENUM_DISPLAY_MODE_LIST = 'list';
	/** @var string ENUM_DISPLAY_MODE_PIE */
	const ENUM_DISPLAY_MODE_PIE = 'pie-chart';
	/** @var string ENUM_DISPLAY_MODE_BAR */
	const ENUM_DISPLAY_MODE_BAR = 'bar-chart';

	/** @var string ENUM_PAGE_TEMPLATE_PATH_TABLE */
	const ENUM_PAGE_TEMPLATE_PATH_TABLE = 'itop-portal-base/portal/templates/bricks/manage/layout-table.html.twig';
	/** @var string ENUM_PAGE_TEMPLATE_PATH_CHART */
	const ENUM_PAGE_TEMPLATE_PATH_CHART = 'itop-portal-base/portal/templates/bricks/manage/layout-chart.html.twig';

	// Overloaded constants
	const DEFAULT_DECORATION_CLASS_HOME = 'fas fa-pen-square';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fas fa-pen-square fa-2x';
	const DEFAULT_PAGE_TEMPLATE_PATH = self::ENUM_PAGE_TEMPLATE_PATH_TABLE;
	const DEFAULT_DATA_LOADING = self::ENUM_DATA_LOADING_LAZY;
	const DEFAULT_TILE_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/manage/tile-default.html.twig';
	const DEFAULT_TILE_CONTROLLER_ACTION = 'Combodo\\iTop\\Portal\\Controller\\ManageBrickController::TileAction';

	/** @var string DEFAULT_OQL */
	const DEFAULT_OQL = '';
	/** @var string DEFAULT_OPENING_MODE */
	const DEFAULT_OPENING_MODE = self::ENUM_ACTION_EDIT;
	/** @var int DEFAULT_LIST_LENGTH */
	const DEFAULT_LIST_LENGTH = 20;
	/** @var string DEFAULT_ZLIST_FIELDS */
	const DEFAULT_ZLIST_FIELDS = 'list';
	/** @var bool DEFAULT_SHOW_TAB_COUNTS */
	const DEFAULT_SHOW_TAB_COUNTS = false;
	/** @var string DEFAULT_DISPLAY_MODE */
	const DEFAULT_DISPLAY_MODE = self::ENUM_DISPLAY_MODE_LIST;
	/** @var string DEFAULT_TILE_MODE */
	const DEFAULT_TILE_MODE = self::ENUM_TILE_MODE_TEXT;
	/** @var int DEFAULT_GROUP_LIMIT */
	const DEFAULT_GROUP_LIMIT = 0;
	/** @var bool DEFAULT_GROUP_SHOW_OTHERS */
	const DEFAULT_GROUP_SHOW_OTHERS = true;

	/** @var array $aDisplayModes */
	static $aDisplayModes = array(
		self::ENUM_DISPLAY_MODE_LIST,
		self::ENUM_DISPLAY_MODE_PIE,
		self::ENUM_DISPLAY_MODE_BAR,
	);
	/** @var array $aTileModes */
	public static $aTileModes = array(
		self::ENUM_TILE_MODE_TEXT,
		self::ENUM_TILE_MODE_BADGE,
		self::ENUM_TILE_MODE_PIE,
		self::ENUM_TILE_MODE_BAR,
		self::ENUM_TILE_MODE_TOP,
	);
	/** @var array $aPresentationData */
	public static $aPresentationData = array(
		self::ENUM_TILE_MODE_BADGE => array(
			'decorationCssClass' => 'fas fa-id-card fa-2x',
			'tileTemplate' => 'itop-portal-base/portal/templates/bricks/manage/tile-badge.html.twig',
			'layoutTemplate' => self::ENUM_PAGE_TEMPLATE_PATH_TABLE,
			'layoutDisplayMode' => self::ENUM_DISPLAY_MODE_LIST,
			'need_details' => true,
		),
		self::ENUM_TILE_MODE_TOP => array(
			'decorationCssClass' => 'fas fa-signal fa-rotate-270 fa-2x',
			'tileTemplate' => 'itop-portal-base/portal/templates/bricks/manage/tile-top-list.html.twig',
			'layoutTemplate' => self::ENUM_PAGE_TEMPLATE_PATH_TABLE,
			'layoutDisplayMode' => self::ENUM_DISPLAY_MODE_LIST,
			'need_details' => true,
		),
		self::ENUM_TILE_MODE_PIE => array(
			'decorationCssClass' => 'fas fa-chart-pie fa-2x',
			'tileTemplate' => 'itop-portal-base/portal/templates/bricks/manage/tile-chart.html.twig',
			'layoutTemplate' => self::ENUM_PAGE_TEMPLATE_PATH_CHART,
			'layoutDisplayMode' => self::ENUM_DISPLAY_MODE_PIE,
			'need_details' => false,
		),
		self::ENUM_TILE_MODE_BAR => array(
			'decorationCssClass' => 'fas fa-chart-bar fa-2x',
			'tileTemplate' => 'itop-portal-base/portal/templates/bricks/manage/tile-chart.html.twig',
			'layoutTemplate' => self::ENUM_PAGE_TEMPLATE_PATH_CHART,
			'layoutDisplayMode' => self::ENUM_DISPLAY_MODE_BAR,
			'need_details' => false,
		),
		self::ENUM_TILE_MODE_TEXT => array(
			'decorationCssClass' => 'fas fa-pen-square fa-2x',
			'tileTemplate' => self::DEFAULT_TILE_TEMPLATE_PATH,
			'layoutTemplate' => self::ENUM_PAGE_TEMPLATE_PATH_TABLE,
			'layoutDisplayMode' => self::ENUM_DISPLAY_MODE_LIST,
			'need_details' => true,
		),
	);

	// Overloaded variables
	public static $sRouteName = 'p_manage_brick';

	/** @var string $sOql */
	protected $sOql;
	/** @var string $sOpeningMode */
	protected $sOpeningMode;
	/** @var array $aGrouping */
	protected $aGrouping;
	/** @var array $aFields */
	protected $aFields;
	/** @var array $aExportFields */
	protected $aExportFields;
	/** @var bool $bShowTabCounts */
	protected $bShowTabCounts;
	/** @var array $aAvailableDisplayModes */
	protected $aAvailableDisplayModes = array();
	/** @var string $sDefaultDisplayMode */
	protected $sDefaultDisplayMode;
	/** @var string $sTileMode */
	protected $sTileMode;
	/** @var int $iGroupLimit */
	protected $iGroupLimit;
	/** @var bool $bGroupShowOthers */
	protected $bGroupShowOthers;
	/** @var int $iDefaultListLength */
	protected $iDefaultListLength;

	/**
	 * Returns true if the $sDisplayMode need objects details for rendering.
	 *
	 * @param string $sDisplayMode
	 *
	 * @return bool
	 */
	static public function AreDetailsNeededForDisplayMode($sDisplayMode)
	{
		$bNeedDetails = false;
		foreach (static::$aPresentationData as $aData)
		{
			if ($aData['layoutDisplayMode'] === $sDisplayMode)
			{
				$bNeedDetails = $aData['need_details'];
				break;
			}
		}

		return $bNeedDetails;
	}

	/**
	 * Returns the page template path for the $sDisplayMode
	 *
	 * @param string $sDisplayMode
	 *
	 * @return string
	 */
	static public function GetPageTemplateFromDisplayMode($sDisplayMode)
	{
		$sTemplate = static::DEFAULT_PAGE_TEMPLATE_PATH;
		foreach (static::$aPresentationData as $aData)
		{
			if ($aData['layoutDisplayMode'] === $sDisplayMode)
			{
				$sTemplate = $aData['layoutTemplate'];
				break;
			}
		}

		return $sTemplate;
	}

	/**
	 * ManageBrick constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->sOql = static::DEFAULT_OQL;
		$this->sOpeningMode = static::DEFAULT_OPENING_MODE;
		$this->aGrouping = array();
		$this->aFields = array();
		$this->aExportFields = array();
		$this->bShowTabCounts = static::DEFAULT_SHOW_TAB_COUNTS;
		$this->sDefaultDisplayMode = static::DEFAULT_DISPLAY_MODE;

		$this->sTileMode = static::DEFAULT_TILE_MODE;
		$this->iGroupLimit = static::DEFAULT_GROUP_LIMIT;
		$this->bGroupShowOthers = static::DEFAULT_GROUP_SHOW_OTHERS;
		$this->iDefaultListLength = static::DEFAULT_LIST_LENGTH;

		// This is hardcoded for now, we might allow area grouping on another attribute in the future
		$this->AddGrouping('areas', array('attribute' => 'finalclass'));
	}

	/**
	 * Returns the brick oql
	 *
	 * @return string
	 */
	public function GetOql()
	{
		return $this->sOql;
	}

	/**
	 * Returns the brick's objects opening mode (edit or view)
	 *
	 * @return string
	 */
	public function GetOpeningMode()
	{
		return $this->sOpeningMode;
	}

	/**
	 * Returns the brick grouping
	 *
	 * @return array
	 */
	public function GetGrouping()
	{
		return $this->aGrouping;
	}

	/**
	 * Returns the brick fields to display in the table
	 *
	 * @return array
	 */
	public function GetFields()
	{
		return $this->aFields;
	}

	/**
	 * Returns the brick fields to export
	 *
	 * @return array
	 */
	public function GetExportFields()
	{
		return $this->aExportFields;
	}

	/**
	 * Returns if the brick should display objects count on tabs
	 *
	 * @return bool
	 */
	public function GetShowTabCounts()
	{
		return $this->bShowTabCounts;
	}

	/**
	 * Returns the brick default display mode
	 *
	 * @return string
	 */
	public function GetDefaultDisplayMode()
	{
		return $this->sDefaultDisplayMode;
	}

	/**
	 * Sets the default display mode of the brick
	 *
	 * @param string $sDefaultDisplayMode
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetDefaultDisplayMode($sDefaultDisplayMode)
	{
		$this->sDefaultDisplayMode = $sDefaultDisplayMode;

		return $this;
	}

	/**
	 * Returns the tile mode (display)
	 *
	 * @return string
	 */
	public function GetTileMode()
	{
		return $this->sTileMode;
	}

	public function GetDecorationCssClass()
	{
		return static::$aPresentationData[$this->sTileMode]['decorationCssClass'];
	}
	/**
	 * Sets the tile mode (display)
	 *
	 * @param string $sTileMode
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetTileMode($sTileMode)
	{
		$this->sTileMode = $sTileMode;

		return $this;
	}

	/**
	 * @param string $sTileMode
	 *
	 * @return string[] parameters for specified type, default parameters if type is invalid
	 */
	public function GetPresentationDataForTileMode($sTileMode)
	{
		if (isset(static::$aPresentationData[$sTileMode]))
		{
			return static::$aPresentationData[$sTileMode];
		}

		return static::$aPresentationData[static::DEFAULT_TILE_MODE];
	}

	/**
	 * @return mixed
	 */
	public function GetGroupLimit()
	{
		return $this->iGroupLimit;
	}

	/**
	 * @return mixed
	 */
	public function ShowGroupOthers()
	{
		return $this->bGroupShowOthers;
	}

	/**
	 * Sets the oql of the brick
	 *
	 * @param string $sOql
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetOql($sOql)
	{
		$this->sOql = $sOql;

		return $this;
	}

	/**
	 * Sets the brick's objects opening mode
	 *
	 * @param string $sOpeningMode
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetOpeningMode($sOpeningMode)
	{
		$this->sOpeningMode = $sOpeningMode;

		return $this;
	}

	/**
	 * Sets the grouping of the brick
	 *
	 * @param array $aGrouping
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetGrouping($aGrouping)
	{
		$this->aGrouping = $aGrouping;

		return $this;
	}

	/**
	 * Sets the fields of the brick
	 *
	 * @param array $aFields
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetFields($aFields)
	{
		$this->aFields = $aFields;

		return $this;
	}

	/**
	 * Sets if the brick should display objects count on tab
	 *
	 * @param bool $bShowTabCounts
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function SetShowTabCounts($bShowTabCounts)
	{
		$this->bShowTabCounts = $bShowTabCounts;

		return $this;
	}

	/**
	 * Returns the default lists length to display
	 * 
	 * @return int
	 */
	public function GetDefaultListLength()
	{
		return $this->iDefaultListLength;
	}

	/**
	 * Sets the default lists length to display
	 * 
	 * @param int $iDefaultListLength
	 * 
	 * @return $this
	 */
	public function SetDefaultListLength($iDefaultListLength) {
		$this->iDefaultListLength = $iDefaultListLength;
		return $this;
	}
	
	/**
	 * Adds a grouping.
	 *
	 * Grouping "tabs" must be of form array("attribute" => value)
	 *
	 * @param string $sName (Must be "tabs" or -Not implemented yet, implicit grouping on y axis-)
	 * @param array  $aGrouping
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function AddGrouping($sName, $aGrouping)
	{
		$this->aGrouping[$sName] = $aGrouping;

		// Sorting
		if (!$this->IsGroupingByDistinctValues($sName))
		{
			usort($this->aGrouping[$sName]['groups'], function ($a, $b) {
				if ($a['rank'] === $b['rank']) {
					return 0;
				}

				return $a['rank'] > $b['rank'] ? 1 : -1;
			});
		}

		return $this;
	}

	/**
	 * Removes a grouping by its name
	 *
	 * @param string $sName
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function RemoveGrouping($sName)
	{
		if (isset($this->aGrouping[$sName]))
		{
			unset($this->aGrouping[$sName]);
		}

		return $this;
	}

	/**
	 * Adds a field to display from its attribute_code.
	 *
	 * @param string $sAttCode
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function AddField($sAttCode)
	{
		if (!in_array($sAttCode, $this->aFields))
		{
			$this->aFields[] = $sAttCode;
		}

		return $this;
	}

	/**
	 * Removes a field
	 *
	 * @param string $sAttCode
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function RemoveField($sAttCode)
	{
		if (isset($this->aFields[$sAttCode]))
		{
			unset($this->aFields[$sAttCode]);
		}

		return $this;
	}

	public function AddExportField($sAttCode)
	{
		if (!in_array($sAttCode, $this->aExportFields))
		{
			$this->aExportFields[] = $sAttCode;
		}

		return $this;
	}

	public function RemoveExportField($sAttCode)
	{
		if (isset($this->aExportFields[$sAttCode]))
		{
			unset($this->aExportFields[$sAttCode]);
		}

		return $this;
	}

	/**
	 * Returns if the brick has grouping tabs or not.
	 *
	 * @return boolean
	 */
	public function HasGroupingTabs()
	{
		return (isset($this->aGrouping['tabs']) && !empty($this->aGrouping['tabs']));
	}

	/**
	 * Returns the grouping tabs properties if exists, else returns false.
	 *
	 * @return mixed false if there is no grouping named 'tabs', otherwise the array
	 */
	public function GetGroupingTabs()
	{
		return (isset($this->aGrouping['tabs'])) ? $this->aGrouping['tabs'] : false;
	}

	/**
	 * Returns if the brick has grouping areas or not.
	 *
	 * @return boolean
	 */
	public function HasGroupingAreas()
	{
		return (isset($this->aGrouping['areas']) && !empty($this->aGrouping['areas']));
	}

	/**
	 * Returns the grouping areas properties if exists, else returns false.
	 *
	 * @return mixed false if there is no grouping named 'areas', otherwise the array
	 */
	public function GetGroupingAreas()
	{
		return (isset($this->aGrouping['areas'])) ? $this->aGrouping['areas'] : false;
	}

	/**
	 * @param string $sModeId
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 *
	 * @throws \Exception
	 */
	public function AddAvailableDisplayMode($sModeId)
	{
		if (!in_array($sModeId, static::$aDisplayModes))
		{
			throw new Exception('ManageBrick: Display mode "'.$sModeId.'" must be one of the allowed display modes ('.implode(', ',
					static::$aDisplayModes).')');
		}

		$this->aAvailableDisplayModes[] = $sModeId;

		return $this;
	}

	/**
	 * Removes $sModeId from the list of availables display modes
	 *
	 * @param string $sModeId
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 */
	public function RemoveAvailableDisplayMode($sModeId)
	{
		if (isset($this->aAvailableDisplayModes[$sModeId]))
		{
			unset($this->aAvailableDisplayModes[$sModeId]);
		}

		return $this;
	}

	/**
	 * Returns the available display modes for the brick (page, not tile)
	 *
	 * @return string[]
	 */
	public function GetAvailablesDisplayModes()
	{
		return $this->aAvailableDisplayModes;
	}

	/**
	 * Returns true is the groupings $sGroupingName properties exists and is of the form attribute => attribute_code.
	 * This is supposed to be called by the IsGroupingTabsByDistinctValues / IsGroupingAreasByDistinctValues function.
	 *
	 * @param string $sGroupingName
	 *
	 * @return boolean
	 */
	public function IsGroupingByDistinctValues($sGroupingName)
	{
		return (isset($this->aGrouping[$sGroupingName]) && isset($this->aGrouping[$sGroupingName]['attribute']) && $this->aGrouping[$sGroupingName]['attribute'] !== '');
	}

	/**
	 * Returns true is the groupings tabs properties exists and is of the form attribute => attribute_code.
	 * This is mostly used to know if the tabs are grouped by attribute distinct values or by meta-groups (eg : status
	 * in ('accepted', 'opened')).
	 *
	 * @return boolean
	 */
	public function IsGroupingTabsByDistinctValues()
	{
		return $this->IsGroupingByDistinctValues('tabs');
	}

	/**
	 * Returns true is the groupings areas properties exists and is of the form attribute => attribute_code.
	 * This is mostly used to know if the areas are grouped by attribute distinct values or by meta-groups (eg :
	 * finalclass in ('Server', 'Router')).
	 *
	 * @return boolean
	 */
	public function IsGroupingAreasByDistinctValues()
	{
		return $this->IsGroupingByDistinctValues('areas');
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return \Combodo\iTop\Portal\Brick\ManageBrick
	 *
	 * @throws \Exception
	 * @throws \DOMFormatException
	 * @throws \OQLException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);
		$bUseListFieldsForExport = false;

		// Checking specific elements
		/** @var \Combodo\iTop\DesignElement $oBrickSubNode */
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'class':
					$sClass = $oBrickSubNode->GetText();
					if ($sClass === '')
					{
						throw new DOMFormatException('ManageBrick: class tag is empty. Must contain Classname', null,
							null, $oBrickSubNode);
					}

					$this->SetOql('SELECT '.$sClass);
					break;

				case 'oql':
					$sOql = $oBrickSubNode->GetText();
					if ($sOql === '')
					{
						throw new DOMFormatException('ManageBrick: oql tag is empty. Must contain OQL statement', null,
							null, $oBrickSubNode);
					}

					$this->SetOql($sOql);
					break;

				case 'opening_mode':
					$sOpeningMode = $oBrickSubNode->GetText(static::DEFAULT_OPENING_MODE);
					if (!in_array($sOpeningMode, array(static::ENUM_ACTION_VIEW, static::ENUM_ACTION_EDIT)))
					{
						throw new DOMFormatException('ManageBrick: opening_mode tag value must be edit|view ("'.$sOpeningMode.'" given)',
							null, null, $oBrickSubNode);
					}

					$this->SetOpeningMode($sOpeningMode);
					break;

				case 'display_modes':
					/** @var \Combodo\iTop\DesignElement $oDisplayNode */
					foreach ($oBrickSubNode->GetNodes('./*') as $oDisplayNode)
					{
						switch ($oDisplayNode->nodeName)
						{
							case 'availables';
								/** @var \Combodo\iTop\DesignElement $oModeNode */
								foreach ($oDisplayNode->GetNodes('*') as $oModeNode)
								{
									if (!$oModeNode->hasAttribute('id'))
									{
										throw new DOMFormatException('ManageBrick: Display mode must have a unique ID attribute',
											null, null, $oModeNode);
									}

									$sModeId = $oModeNode->getAttribute('id');
									if (!in_array($sModeId, static::$aDisplayModes))
									{
										throw new DOMFormatException('ManageBrick: Display mode has an invalid value. Expected '.implode('/',
												static::$aDisplayModes.', "'.$sModeId.'" given.'),
											null, null, $oModeNode);
									}

									$this->AddAvailableDisplayMode($sModeId);
								}
								break;

							case 'default':
								$this->SetDefaultDisplayMode($oDisplayNode->GetText(static::DEFAULT_DISPLAY_MODE));
								break;

							case 'tile';
								$this->SetTileMode($oDisplayNode->GetText(static::DEFAULT_TILE_MODE));

								$aTileParametersForType = $this->GetPresentationDataForTileMode($this->sTileMode);
								$this->SetTileTemplatePath($aTileParametersForType['tileTemplate']);
								$this->SetPageTemplatePath($aTileParametersForType['layoutTemplate']);
								break;
						}
					}
					break;

				case 'fields':
					/** @var \Combodo\iTop\DesignElement $oFieldNode */
					foreach ($oBrickSubNode->GetNodes('./field') as $oFieldNode)
					{
						if (!$oFieldNode->hasAttribute('id'))
						{
							throw new DOMFormatException('ManageBrick : Field must have a unique ID attribute', null,
								null, $oFieldNode);
						}
						$this->AddField($oFieldNode->getAttribute('id'));
					}
					break;

				case 'export':
					/** @var \Combodo\iTop\DesignElement $oExportNode */
					foreach ($oBrickSubNode->GetNodes('./*') as $oExportNode)
					{
						switch ($oExportNode->nodeName)
						{
							case 'fields':
								/** @var \Combodo\iTop\DesignElement $oFieldNode */
								foreach ($oExportNode->GetNodes('./field') as $oFieldNode)
								{
									if (!$oFieldNode->hasAttribute('id'))
									{
										throw new DOMFormatException('ManageBrick : Field must have a unique ID attribute',
											null,
											null, $oFieldNode);
									}
									$this->AddExportField($oFieldNode->getAttribute('id'));
								}
								break;

							case 'export_default_fields':
								$bUseListFieldsForExport = (strtolower($oExportNode->GetText()) === 'true' ? true : false);
								break;
						}

					}
					break;
				case 'default_list_length':
					$iNodeDefaultListLength = (int)$oBrickSubNode->GetText(static::DEFAULT_LIST_LENGTH);
					if(!in_array($iNodeDefaultListLength, array(10, 20, 50, -1),true))
					{
						throw new DOMFormatException(
							'ManageBrick : Default list length must be contained in list length options. Expected -1/10/20/50, '.$iNodeDefaultListLength.' given.',
							null,
							null, $oBrickSubNode
						);
					}
					$this->SetDefaultListLength($iNodeDefaultListLength);
					break;
				case 'grouping':
					// Tabs grouping
					/** @var \Combodo\iTop\DesignElement $oGroupingNode */
					foreach ($oBrickSubNode->GetNodes('./tabs/*') as $oGroupingNode)
					{
						switch ($oGroupingNode->nodeName)
						{
							case 'show_tab_counts';
								$bShowTabCounts = ($oGroupingNode->GetText(static::DEFAULT_SHOW_TAB_COUNTS) === 'true') ? true : false;
								$this->SetShowTabCounts($bShowTabCounts);
								break;
							case 'attribute':
								$sAttribute = $oGroupingNode->GetText();
								if ($sAttribute !== '')
								{
									$this->AddGrouping('tabs', array('attribute' => $sAttribute));
								}
								break;
							case 'limit':
								$iLimit = $oGroupingNode->GetText();
								if (is_numeric($iLimit))
								{
									$this->iGroupLimit = $iLimit;
								}
								break;
							case 'show_others':
								$this->bGroupShowOthers = ($oGroupingNode->GetText() === 'true') ? true : false;
								break;
							case 'groups':
								$aGroups = array();
								/** @var \Combodo\iTop\DesignElement $oGroupNode */
								foreach ($oGroupingNode->GetNodes('./group') as $oGroupNode)
								{
									if (!$oGroupNode->hasAttribute('id'))
									{
										throw new DOMFormatException('ManageBrick : Group must have a unique ID attribute',
											null, null, $oGroupNode);
									}
									$sGroupId = $oGroupNode->getAttribute('id');

									$aGroup = array();
									$aGroup['id'] = $sGroupId; // We don't put the group id as the $aGroups key because the array will be sorted later in AddGrouping, which replace array keys by integer ordered keys
									/** @var \Combodo\iTop\DesignElement $oGroupProperty */
									foreach ($oGroupNode->GetNodes('*') as $oGroupProperty)
									{
										switch ($oGroupProperty->nodeName)
										{
											case 'rank':
												$aGroup[$oGroupProperty->nodeName] = (int)$oGroupProperty->GetText(0);
												break;
											case 'title':
											case 'description':
											case 'condition':
												$aGroup[$oGroupProperty->nodeName] = $oGroupProperty->GetText();
												break;
										}
									}

									// Checking constitancy
									if (!isset($aGroup['title']) || $aGroup['title'] === '')
									{
										throw new DOMFormatException('ManageBrick : Group must have a title tag and it must not be empty',
											null, null, $oGroupNode);
									}
									if (!isset($aGroup['condition']) || $aGroup['condition'] === '')
									{
										throw new DOMFormatException('ManageBrick : Group must have a condition tag and it must not be empty',
											null, null, $oGroupNode);
									}
									$aGroups[] = $aGroup;
								}
								$this->AddGrouping('tabs', array('groups' => $aGroups));
								break;
						}
					}
					break;
			}
		}

		// Checking if has an oql
		if ($this->GetOql() === '')
		{
			throw new DOMFormatException('ManageBrick: must have a valid <class|oql> tag', null, null, $oMDElement);
		}

		// Checking that the brick has at least a display mode
		if (count($this->GetAvailablesDisplayModes()) === 0)
		{
			$this->AddAvailableDisplayMode(static::DEFAULT_DISPLAY_MODE);
		}
		// Checking that default display mode in among the availables
		if (!in_array($this->sDefaultDisplayMode, $this->aAvailableDisplayModes))
		{
			throw new DOMFormatException('ManageBrick: Default display mode "'.$this->sDefaultDisplayMode.'" must be one of the available display modes ('.implode(', ',
					$this->aAvailableDisplayModes).')', null, null, $oMDElement);
		}
		// Checking that tile mode in among the availables
		if (!in_array($this->sTileMode, static::$aTileModes))
		{
			throw new DOMFormatException('ManageBrick: Tile mode "'.$this->sTileMode.'" must be one of the allowed tile modes ('.implode(', ',
					static::$aTileModes).')', null, null, $oMDElement);
		}

		// Checking if specified fields, if not we put those from the details zlist
		if (empty($this->aFields))
		{
			$sClass = DBSearch::FromOQL($this->GetOql());
			$aFields = MetaModel::FlattenZList(MetaModel::GetZListItems($sClass->GetClass(),
				static::DEFAULT_ZLIST_FIELDS));
			$this->SetFields($aFields);
		}

		// Default Export Fields
		if ($bUseListFieldsForExport)
		{
			foreach ($this->GetFields() as $sAttCode)
			{
				$this->AddExportField($sAttCode);
			}
		}

		// Checking the navigation icon
		$sDecorationClassNavigationMenu = $this->GetDecorationClassNavigationMenu();
		if (empty($sDecorationClassNavigationMenu) && isset(static::$aPresentationData[$this->sTileMode]))
		{
			/** @var string $sDecorationClassNavigationMenu */
			$sDecorationClassNavigationMenu = static::$aPresentationData[$this->sTileMode]['decorationCssClass'];
			if (!empty($sDecorationClassNavigationMenu))
			{
				$this->SetDecorationClassNavigationMenu($sDecorationClassNavigationMenu);
			}
		}

		$sTitle = $this->GetTitleHome();
		if (empty($sTitle))
		{
			$sOql = $this->GetOql();
			$oSearch = DBSearch::FromOQL($sOql);
			$sClassName = MetaModel::GetName($oSearch->GetClass());
			$this->SetTitleHome($sClassName);
			$this->SetTitleNavigationMenu($sClassName);
			$this->SetTitle($sClassName);
		}

		return $this;
	}
}

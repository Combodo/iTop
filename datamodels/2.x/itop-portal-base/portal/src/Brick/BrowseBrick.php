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

use DOMFormatException;
use Combodo\iTop\DesignElement;

/**
 * Description of BrowseBrick
 *
 * @package Combodo\iTop\Portal\Brick
 * @since 2.3.0
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BrowseBrick extends PortalBrick
{
	/** @var string ENUM_BROWSE_MODE_LIST */
	const ENUM_BROWSE_MODE_LIST = 'list';
	/** @var string ENUM_BROWSE_MODE_TREE */
	const ENUM_BROWSE_MODE_TREE = 'tree';
	/** @var string ENUM_BROWSE_MODE_MOSAIC */
	const ENUM_BROWSE_MODE_MOSAIC = 'mosaic';

	/** @var string ENUM_ACTION_VIEW */
	const ENUM_ACTION_VIEW = 'view';
	/** @var string ENUM_ACTION_EDIT */
	const ENUM_ACTION_EDIT = 'edit';
	/** @var string ENUM_ACTION_DRILLDOWN */
	const ENUM_ACTION_DRILLDOWN = 'drilldown';
	/** @var string ENUM_ACTION_CREATE_FROM_THIS */
	const ENUM_ACTION_CREATE_FROM_THIS = 'create_from_this';

	/** @var string ENUM_ACTION_ICON_CLASS_VIEW */
	const ENUM_ACTION_ICON_CLASS_VIEW = 'glyphicon glyphicon-list-alt';
	/** @var string ENUM_ACTION_ICON_CLASS_EDIT */
	const ENUM_ACTION_ICON_CLASS_EDIT = 'glyphicon glyphicon-pencil';
	/** @var string ENUM_ACTION_ICON_CLASS_DRILLDOWN */
	const ENUM_ACTION_ICON_CLASS_DRILLDOWN = 'glyphicon glyphicon-menu-down';
	/** @var string ENUM_ACTION_ICON_CLASS_CREATE_FROM_THIS */
	const ENUM_ACTION_ICON_CLASS_CREATE_FROM_THIS = 'glyphicon glyphicon-edit';

	/** @var string ENUM_FACTORY_TYPE_METHOD */
	const ENUM_FACTORY_TYPE_METHOD = 'method';
	/** @var string ENUM_FACTORY_TYPE_CLASS */
	const ENUM_FACTORY_TYPE_CLASS = 'class';

	// Overloaded constants
	const DEFAULT_DECORATION_CLASS_HOME = 'fas fa-map';
	const DEFAULT_DECORATION_CLASS_NAVIGATION_MENU = 'fas fa-map fa-2x';
	const DEFAULT_DATA_LOADING = self::ENUM_DATA_LOADING_FULL;

	/** @var string DEFAULT_LEVEL_NAME_ATT */
	const DEFAULT_LEVEL_NAME_ATT = 'name';
	/** @var string DEFAULT_BROWSE_MODE */
	const DEFAULT_BROWSE_MODE = self::ENUM_BROWSE_MODE_LIST;
	/** @var string DEFAULT_ACTION */
	const DEFAULT_ACTION = self::ENUM_ACTION_DRILLDOWN;
	/** @var string DEFAULT_ACTION_OPENING_TARGET */
	const DEFAULT_ACTION_OPENING_TARGET = self::ENUM_OPENING_TARGET_MODAL;
	/** @var int DEFAULT_LIST_LENGTH */
	const DEFAULT_LIST_LENGTH = 20;

	// Overloaded variables
	public static $sRouteName = 'p_browse_brick';

	/** @var array $aBrowseModes */
	public static $aBrowseModes = array(
		self::ENUM_BROWSE_MODE_LIST,
		self::ENUM_BROWSE_MODE_TREE,
		self::ENUM_BROWSE_MODE_MOSAIC,
	);

	/** @var array $aLevels */
	protected $aLevels;
	/** @var array $aAvailablesBrowseModes */
	protected $aAvailablesBrowseModes;
	/** @var string $sDefaultBrowseMode */
	protected $sDefaultBrowseMode;
	/** @var int $iDefaultListLength */
	protected $iDefaultListLength;

	/**
	 * BrowseBrick constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->aLevels = array();
		$this->aAvailablesBrowseModes = array();
		$this->sDefaultBrowseMode = static::DEFAULT_BROWSE_MODE;
		$this->iDefaultListLength = static::DEFAULT_LIST_LENGTH;
	}

	/**
	 *  Compare function to sort actions by their rank attribute
	 * 
	 * @param array $aAction1
	 * @param array $aAction2
	 *
	 * @return int
	 */
	public static function CompareActionsByRank($aAction1, $aAction2)
	{
		$bIsAction1RankSet = array_key_exists('rank', $aAction1);
		$bIsAction2RankSet = array_key_exists('rank', $aAction2);

		if($bIsAction1RankSet && $bIsAction2RankSet)
		{
			//If a1 == a2 return 0, if a1 > a2 return 1 else return -1 
			return ($aAction1['rank'] === $aAction2['rank'] ? $aAction1['default_rank'] - $aAction2['default_rank'] : $aAction1['rank'] - $aAction2['rank']);
		}
		else
		{
			//If a1 == a2 == null return 0, if a2 is null and not a1 return 1 else return -1
			return ($bIsAction1RankSet === $bIsAction2RankSet ? $aAction1['default_rank'] - $aAction2['default_rank'] : ($bIsAction1RankSet ? 1 : -1));
		}
	}

	/**
	 * Returns the brick levels
	 *
	 * @return array
	 */
	public function GetLevels()
	{
		return $this->aLevels;
	}

	/**
	 * Returns the brick available browse modes
	 *
	 * @return array
	 */
	public function GetAvailablesBrowseModes()
	{
		return $this->aAvailablesBrowseModes;
	}

	/**
	 * Returns the brick default browse mode
	 *
	 * @return string
	 */
	public function GetDefaultBrowseMode()
	{
		return $this->sDefaultBrowseMode;
	}

	/**
	 * Sets the levels of the brick
	 *
	 * @param array $aLevels
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function SetLevels($aLevels)
	{
		$this->aLevels = $aLevels;

		return $this;
	}

	/**
	 * Sets the available browse modes of the brick
	 *
	 * @param array $aAvailablesBrowseModes
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function SetAvailablesBrowseModes($aAvailablesBrowseModes)
	{
		$this->aAvailablesBrowseModes = $aAvailablesBrowseModes;

		return $this;
	}

	/**
	 * Sets the default browse mode of the brick
	 *
	 * @param string $sDefaultBrowseMode
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function SetDefaultBrowseMode($sDefaultBrowseMode)
	{
		$this->sDefaultBrowseMode = $sDefaultBrowseMode;

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
	 * Returns true if the brick has levels
	 *
	 * @return boolean
	 */
	public function HasLevels()
	{
		return !empty($this->aLevels);
	}

	/**
	 * Adds $aLevel to the list of levels for that brick
	 *
	 * @param array $aLevel
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function AddLevel($aLevel)
	{
		$this->aLevels[] = $aLevel;

		return $this;
	}

	/**
	 * Removes $aLevel from the list of levels browse modes
	 *
	 * @param string $sLevel
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function RemoveLevels($sLevel)
	{
		if (isset($this->aLevels[$sLevel]))
		{
			unset($this->aLevels[$sLevel]);
		}

		return $this;
	}

	/**
	 * Adds $sModeId to the list of availables browse modes for that brick
	 *
	 * @param string $sModeId
	 * @param array  $aData Hash array containing 'template' => TEMPLATE_PATH
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function AddAvailableBrowseMode($sModeId, $aData = array())
	{
		$this->aAvailablesBrowseModes[$sModeId] = $aData;

		return $this;
	}

	/**
	 * Removes $sModeId from the list of availables browse modes
	 *
	 * @param string $sModeId
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 */
	public function RemoveAvailableBrowseMode($sModeId)
	{
		if (isset($this->aAvailablesBrowseModes[$sModeId]))
		{
			unset($this->aAvailablesBrowseModes[$sModeId]);
		}

		return $this;
	}

	/**
	 * Load the brick's data from the xml passed as a ModuleDesignElement.
	 * This is used to set all the brick attributes at once.
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return \Combodo\iTop\Portal\Brick\BrowseBrick
	 *
	 * @throws \DOMFormatException
	 */
	public function LoadFromXml(DesignElement $oMDElement)
	{
		parent::LoadFromXml($oMDElement);

		// Checking specific elements
		/** @var \Combodo\iTop\DesignElement $oBrickSubNode */
		foreach ($oMDElement->GetNodes('./*') as $oBrickSubNode)
		{
			switch ($oBrickSubNode->nodeName)
			{
				case 'levels':
					/** @var \Combodo\iTop\DesignElement $oLevelNode */
					foreach ($oBrickSubNode->GetNodes('*') as $oLevelNode)
					{
						if ($oLevelNode->nodeName === 'level')
						{
							$this->AddLevel($this->LoadLevelFromXml($oLevelNode));
						}
					}
					break;
				case 'browse_modes':
					/** @var \Combodo\iTop\DesignElement $oBrowseModeNode */
					foreach ($oBrickSubNode->GetNodes('*') as $oBrowseModeNode)
					{
						switch ($oBrowseModeNode->nodeName)
						{
							case 'availables':
								/** @var \Combodo\iTop\DesignElement $oModeNode */
								foreach ($oBrowseModeNode->GetNodes('*') as $oModeNode)
								{
									if (!$oModeNode->hasAttribute('id'))
									{
										throw new DOMFormatException('BrowseBrick: Browse mode must have a unique ID attribute', null,
											null, $oModeNode);
									}

									$sModeId = $oModeNode->getAttribute('id');
									$aModeData = array();

									// Checking if the browse mode has a specific template
									$oTemplateNode = $oModeNode->GetOptionalElement('template');
									if (($oTemplateNode !== null) && ($oTemplateNode->GetText() !== null))
									{
										$sTemplatePath = $oTemplateNode->GetText();
									}
									else
									{
										$sTemplatePath = 'itop-portal-base/portal/templates/bricks/browse/mode_'.$sModeId.'.html.twig';
									}
									$aModeData['template'] = $sTemplatePath;

									$this->AddAvailableBrowseMode($sModeId, $aModeData);
								}
								break;
							case 'default':
								$this->SetDefaultBrowseMode($oBrowseModeNode->GetText(static::DEFAULT_BROWSE_MODE));
								break;
						}
					}
					break;
				case 'default_list_length':
					$iNodeDefaultListLength = (int)$oBrickSubNode->GetText(static::DEFAULT_LIST_LENGTH);
					if(!in_array($iNodeDefaultListLength, array(10, 20, 50, -1),true))
					{
						throw new DOMFormatException(
							'BrowseBrick: Default list length must be contained in list length options. Expected -1/10/20/50, '.$iNodeDefaultListLength.' given.',
							null,
							null, $oBrickSubNode
						);
					}
					$this->SetDefaultListLength($iNodeDefaultListLength);
					break;
			}
		}

		// Checking that the brick has at least a browse mode
		if (count($this->GetAvailablesBrowseModes()) === 0)
		{
			throw new DOMFormatException('BrowseBrick : Must have at least one browse mode', null, null, $oMDElement);
		}
		// Checking that default browse mode in among the available
		if (!in_array($this->sDefaultBrowseMode, array_keys($this->aAvailablesBrowseModes)))
		{
			throw new DOMFormatException('BrowseBrick : Default browse mode "'.$this->sDefaultBrowseMode.'" must be one of the available browse modes ('.implode(', ',
					$this->aAvailablesBrowseModes).')', null, null, $oMDElement);
		}
		// Checking that the brick has at least a level
		if (count($this->GetLevels()) === 0)
		{
			throw new DOMFormatException('BrowseBrick : Must have at least one level', null, null, $oMDElement);
		}

		return $this;
	}

	/**
	 * Parses the ModuleDesignElement to recursively load levels
	 *
	 * @param \Combodo\iTop\DesignElement $oMDElement
	 *
	 * @return array
	 *
	 * @throws \DOMFormatException
	 */
	protected function LoadLevelFromXml(DesignElement $oMDElement)
	{
		$aLevel = array(
			'parent_att' => null,
			'tooltip_att' => null,
			'description_att' => null,
			'image_att' => null,
			'title' => null,
			'name_att' => static::DEFAULT_LEVEL_NAME_ATT,
			'fields' => array(),
			'actions' => array('default' => array('type' => static::DEFAULT_ACTION, 'rules' => array())),
		);

		// Getting level ID
		if ($oMDElement->hasAttribute('id') && $oMDElement->getAttribute('id') !== '')
		{
			$aLevel['id'] = $oMDElement->getAttribute('id');
		}
		else
		{
			throw new DOMFormatException('BrowseBrick : level tag without "id" attribute. It must have one and it must not be empty', null,
				null, $oMDElement);
		}
		// Getting level properties
		/** @var \Combodo\iTop\DesignElement $oLevelPropertyNode */
		foreach ($oMDElement->GetNodes('*') as $oLevelPropertyNode)
		{
			switch ($oLevelPropertyNode->nodeName)
			{
				case 'class':
					$sClass = $oLevelPropertyNode->GetText();
					if ($sClass === '')
					{
						throw new DOMFormatException('BrowseBrick : class tag is empty. Must contain Classname', null, null,
							$oLevelPropertyNode);
					}

					$aLevel['oql'] = 'SELECT '.$sClass;
					break;

				case 'oql':
					$sOql = $oLevelPropertyNode->GetText();
					if ($sOql === '')
					{
						throw new DOMFormatException('BrowseBrick : oql tag is empty. Must contain OQL statement', null, null,
							$oLevelPropertyNode);
					}

					$aLevel['oql'] = $sOql;
					break;

				case 'parent_att':
				case 'tooltip_att':
				case 'description_att':
				case 'image_att':
				case 'title':
					$aLevel[$oLevelPropertyNode->nodeName] = $oLevelPropertyNode->GetText(null);
					break;

				case 'name_att':
					$aLevel[$oLevelPropertyNode->nodeName] = $oLevelPropertyNode->GetText(static::DEFAULT_LEVEL_NAME_ATT);
					break;

				case 'fields':
					$sTagName = $oLevelPropertyNode->nodeName;

					if ($oLevelPropertyNode->hasChildNodes())
					{
						$aLevel[$sTagName] = array();
						/** @var \Combodo\iTop\DesignElement $oFieldNode */
						foreach ($oLevelPropertyNode->GetNodes('*') as $oFieldNode)
						{
							if ($oFieldNode->hasAttribute('id') && $oFieldNode->getAttribute('id') !== '')
							{
								$aLevel[$sTagName][$oFieldNode->getAttribute('id')] = array('hidden' => false);
							}
							else
							{
								throw new DOMFormatException('BrowseBrick :  '.$sTagName.'/* tag must have an "id" attribute and it must not be empty',
									null, null, $oFieldNode);
							}

							$oFieldSubNode = $oFieldNode->GetOptionalElement('hidden');
							if ($oFieldSubNode !== null)
							{
								$aLevel[$sTagName][$oFieldNode->getAttribute('id')]['hidden'] = ($oFieldSubNode->GetText() === 'true') ? true : false;
							}
						}
					}
					break;

				case 'actions':
					$sTagName = $oLevelPropertyNode->nodeName;

					if ($oLevelPropertyNode->hasChildNodes())
					{
						$aLevel[$sTagName] = array();
						$iActionDefaultRank = 0;
						/** @var \Combodo\iTop\DesignElement $oActionNode */
						foreach ($oLevelPropertyNode->GetNodes('*') as $oActionNode)
						{
							if ($oActionNode->hasAttribute('id') && $oActionNode->getAttribute('id') !== '')
							{
								$aTmpAction = array(
									'type' => null,
									'rules' => array(),
								);

								// Action type
								$aTmpAction['type'] = ($oActionNode->hasAttribute('xsi:type') && $oActionNode->getAttribute('xsi:type') !== '') ? $oActionNode->getAttribute('xsi:type') : static::DEFAULT_ACTION;
								// Action destination class
								if ($aTmpAction['type'] === static::ENUM_ACTION_CREATE_FROM_THIS)
								{
									if ($oActionNode->GetOptionalElement('factory_method') !== null)
									{
										$aTmpAction['factory'] = array(
											'type' => static::ENUM_FACTORY_TYPE_METHOD,
											'value' => $oActionNode->GetOptionalElement('factory_method')->GetText(),
										);
									}
									else
									{
										$aTmpAction['factory'] = array(
											'type' => static::ENUM_FACTORY_TYPE_CLASS,
											'value' => $oActionNode->GetUniqueElement('class')->GetText(),
										);
									}
								}
								// Action title
								$oActionTitleNode = $oActionNode->GetOptionalElement('title');
								if ($oActionTitleNode !== null)
								{
									$aTmpAction['title'] = $oActionTitleNode->GetText();
								}
								// Action icon class
								$oActionIconClassNode = $oActionNode->GetOptionalElement('icon_class');
								if ($oActionIconClassNode !== null)
								{
									$aTmpAction['icon_class'] = $oActionIconClassNode->GetText();
								}
								// Action opening target
								$oActionOpeningTargetNode = $oActionNode->GetOptionalElement('opening_target');
								if ($oActionOpeningTargetNode !== null)
								{
									$aTmpAction['opening_target'] = $oActionOpeningTargetNode->GetText(static::DEFAULT_ACTION_OPENING_TARGET);
								}
								else
								{
									$aTmpAction['opening_target'] = static::DEFAULT_ACTION_OPENING_TARGET;
								}
								// - Checking that opening target is among authorized modes
								if (!in_array($aTmpAction['opening_target'], static::$aOpeningTargets))
								{
									throw new DOMFormatException('BrowseBrick :  '.$sTagName.'/action/opening_target has a wrong value. "'.$aTmpAction['opening_target'].'" given, '.implode('|',
											static::$aOpeningTargets).' expected.', null, null, $oActionOpeningTargetNode);
								}
								$oActionRankNode = $oActionNode->GetOptionalElement('rank');
								if ($oActionRankNode !== null)
								{
									$aTmpAction['rank'] = (int)$oActionRankNode->GetText();
								}
								// Action rules
								/** @var \Combodo\iTop\DesignElement $oRuleNode */
								foreach ($oActionNode->GetNodes('./rules/rule') as $oRuleNode)
								{
									if ($oRuleNode->hasAttribute('id') && $oRuleNode->getAttribute('id') !== '')
									{
										$aTmpAction['rules'][] = $oRuleNode->getAttribute('id');
									}
									else
									{
										throw new DOMFormatException('BrowseBrick :  '.$sTagName.'/rules/rule tag must have an "id" attribute and it must not be empty',
											null, null, $oRuleNode);
									}
								}
								$aTmpAction['default_rank'] = $iActionDefaultRank++;
								$aLevel[$sTagName][$oActionNode->getAttribute('id')] = $aTmpAction;
							}
							else
							{
								throw new DOMFormatException('BrowseBrick :  '.$sTagName.'/* tag must have an "id" attribute and it must not be empty',
									null, null, $oActionNode);
							}
						}
						uasort($aLevel[$sTagName], [$this, 'CompareActionsByRank']);
					}
					break;

				case 'levels':
					foreach ($oLevelPropertyNode->GetNodes('*') as $oSubLevelNode)
					{
						if ($oSubLevelNode->nodeName === 'level')
						{
							$aLevel['levels'][] = $this->LoadLevelFromXml($oSubLevelNode);
						}
					}

					break;
			}
		}

		// Checking if level has an oql
		if (!isset($aLevel['oql']) || $aLevel['oql'] === '')
		{
			throw new DOMFormatException('BrowseBrick : must have a valid <class|oql> tag', null, null, $oMDElement);
		}

		return $aLevel;
	}

}

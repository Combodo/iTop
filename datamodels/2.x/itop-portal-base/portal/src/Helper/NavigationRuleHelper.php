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

namespace Combodo\iTop\Portal\Helper;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\BrowseBrick;
use Combodo\iTop\Portal\Brick\ManageBrick;
use DBObject;
use DBObjectSet;
use DBSearch;
use DOMFormatException;
use DOMNodeList;
use Exception;
use ModuleDesign;
use Symfony\Component\Routing\RouterInterface;
use utils;

/**
 * Class NavigationRuleHelper ⛵
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.7.0
 * @package Combodo\iTop\Portal\Helper
 */
class NavigationRuleHelper
{
	// Available point of origin for the navigation
	const ENUM_ORIGIN_PAGE = 'default';
	const ENUM_ORIGIN_MODAL = 'modal';

	// Available rule categories (of rule types)
	/** @var string ENUM_RULE_CAT_CLOSE (eg. close modal/window) */
	const ENUM_RULE_CAT_CLOSE = 'close';
	/** @var string ENUM_RULE_CAT_REDIRECT (eg. go-to-homepage, go-to-object, go-to-brick, ...) */
	const ENUM_RULE_CAT_REDIRECT = 'redirect';

	// Available rule types
	/** @var string ENUM_RULE_CLOSE */
	const ENUM_RULE_CLOSE = 'close';
	/** @var string ENUM_RULE_GO_TO_HOMEPAGE */
	const ENUM_RULE_GO_TO_HOMEPAGE = 'go-to-homepage';
	/** @var string ENUM_RULE_GO_TO_OBJECT */
	const ENUM_RULE_GO_TO_OBJECT = 'go-to-object';
	/** @var string ENUM_RULE_GO_TO_BRICK */
	const ENUM_RULE_GO_TO_BRICK = 'go-to-brick';
	/** @var string ENUM_RULE_GO_TO_MANAGE_BRICK */
	const ENUM_RULE_GO_TO_MANAGE_BRICK = 'go-to-manage-brick';
	/** @var string ENUM_RULE_GO_TO_BROWSE_BRICK */
	const ENUM_RULE_GO_TO_BROWSE_BRICK = 'go-to-browse-brick';
	// - Defaults
	/** @var string DEFAULT_RULE_SUBMIT_PAGE */
	const DEFAULT_RULE_SUBMIT_PAGE = self::ENUM_RULE_GO_TO_OBJECT;
	/** @var string DEFAULT_RULE_SUBMIT_MODAL */
	const DEFAULT_RULE_SUBMIT_MODAL = self::ENUM_RULE_CLOSE;
	/** @var string DEFAULT_RULE_CANCEL_PAGE */
	const DEFAULT_RULE_CANCEL_PAGE = self::ENUM_RULE_CLOSE;
	/** @var string DEFAULT_RULE_CANCEL_MODAL */
	const DEFAULT_RULE_CANCEL_MODAL = self::ENUM_RULE_CLOSE;

	// Rule go-to-object properties
	/** @var string DEFAULT_RULE_GO_TO_OBJECT_PROP_MODE */
	const DEFAULT_RULE_GO_TO_OBJECT_PROP_MODE = ObjectFormHandlerHelper::ENUM_MODE_VIEW;

	/** @var string ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_MODAL */
	const ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_MODAL = 'modal';
	/** @var string ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_PAGE */
	const ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_PAGE = 'page';
	/** @var string ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_CURRENT */
	const ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_CURRENT = 'current';
	/** @var string DEFAULT_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET */
	const DEFAULT_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET = self::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_MODAL;

	// Rule go-to-brick properties
	// TODO

	/** @var array $aRules */
	protected $aRules;
	/** @var \Symfony\Component\Routing\RouterInterface */
	private $oRouter;
	/** @var \Combodo\iTop\Portal\Brick\BrickCollection */
	private $oBrickCollection;
	/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper */
	private $oScopeValidator;

	/**
	 * NavigationRuleHelper constructor.
	 *
	 * @param \ModuleDesign $oModuleDesign
	 * @param \Symfony\Component\Routing\RouterInterface $oRouter
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeValidator
	 *
	 * @throws \DOMFormatException
	 */
	public function __construct(
		ModuleDesign $oModuleDesign, RouterInterface $oRouter, BrickCollection $oBrickCollection, ScopeValidatorHelper $oScopeValidator
	) {
		$this->aRules = array();
		$this->oRouter = $oRouter;
		$this->oBrickCollection = $oBrickCollection;

		$this->Init($oModuleDesign->GetNodes('/module_design/navigation_rules/navigation_rule'));
		$this->oScopeValidator = $oScopeValidator;
	}

	/**
	 * Initializes the NavigationRuleHelper by caching rules in memory.
	 *
	 * @param \DOMNodeList $oNodes
	 *
	 * @throws \Exception
	 * @throws \DOMFormatException
	 */
	public function Init(DOMNodeList $oNodes)
	{
		$this->aRules = array();

		// Iterating over the navigation_rule nodes
		/** @var \Combodo\iTop\DesignElement $oRuleNode */
		foreach ($oNodes as $oRuleNode)
		{
			// Checking node name
			if ($oRuleNode->nodeName !== 'navigation_rule')
			{
				continue;
			}

			// Retrieving mandatory attributes
			// - ID
			$sRuleId = $oRuleNode->getAttribute('id');
			if ($sRuleId === '')
			{
				throw new DOMFormatException('Rule tag must have an id attribute.', null, null, $oRuleNode);
			}
			// - Type
			$sRuleType = $oRuleNode->getAttribute('xsi:type');
			if (($sRuleType === '') || !in_array($sRuleType, static::GetAllowedTypes()))
			{
				throw new DOMFormatException('Navigation rule tag must have a valid xsi:type, "'.$sRuleType.'" given, expected '.implode('|',
						static::GetAllowedTypes()), null, null, $oRuleNode);
			}

			// Load rule from XML
			$sRuleLoadingFunction = 'Load'.utils::ToCamelCase($sRuleType).'RuleFromXML';
			$this->aRules[$sRuleId] = $this->$sRuleLoadingFunction($oRuleNode);
		}
	}

	//--------------------
	// Enumeration helpers
	//--------------------

	/**
	 * Return an array of the allowed point of origin for the navigation
	 *
	 * @return array
	 */
	public static function GetAllowedOrigins()
	{
		return array(
			static::ENUM_ORIGIN_PAGE,
			static::ENUM_ORIGIN_MODAL,
		);
	}

	/**
	 * Return an array of allowed navigation rule types (those in <navigation_rule xsi:type"XXX" />)
	 *
	 * @return array
	 */
	public static function GetAllowedTypes()
	{
		return array(
			static::ENUM_RULE_CLOSE,
			static::ENUM_RULE_GO_TO_HOMEPAGE,
			static::ENUM_RULE_GO_TO_OBJECT,
			static::ENUM_RULE_GO_TO_BRICK,
			static::ENUM_RULE_GO_TO_BROWSE_BRICK,
			static::ENUM_RULE_GO_TO_MANAGE_BRICK,
		);
	}

	/**
	 * Return the definition of the rule identified by its ID, as a hash array
	 *
	 * @param string $sId
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetRuleDefinition($sId)
	{
		if (!array_key_exists($sId, $this->aRules))
		{
			throw new Exception('NavigationRuleHelper: Could not find "'.$sId.'" in the rules list');
		}

		return $this->aRules[$sId];
	}

	/**
	 * Returns a hash array of ID => rule definition
	 *
	 * @return array
	 */
	public function GetRulesDefinitions()
	{
		return $this->aRules;
	}

	//-------------------------
	// Default rules definition
	//-------------------------

	/**
	 * Return the default definition of a rule based on the $sType (close, go-to-homepage, go-to-brick, ...)
	 *
	 * @param string $sRuleType
	 *
	 * @return array
	 */
	public function GetDefaultRuleDefinitionFromType($sRuleType)
	{
		$sRuleFunctionName = 'GetDefault'.utils::ToCamelCase($sRuleType).'RuleDefinition';
		return $this->$sRuleFunctionName();
	}

	/**
	 * Return the default definition of the "Close" rule
	 *
	 * @return array
	 */
	public function GetDefaultCloseRuleDefinition()
	{
		return array(
			'category' => static::ENUM_RULE_CAT_CLOSE,
			'type' => static::ENUM_RULE_CLOSE,
		);
	}

	/**
	 * Return the default definition of the "Go to homepage" rule
	 *
	 * @return array
	 */
	public function GetDefaultGoToHomepageRuleDefinition()
	{
		return array(
			'category' => static::ENUM_RULE_CAT_REDIRECT,
			'type' => static::ENUM_RULE_GO_TO_HOMEPAGE,
		);
	}

	/**
	 * Return the default definition of the "Go to object" rule
	 *
	 * @return array
	 */
	public function GetDefaultGoToObjectRuleDefinition()
	{
		return array(
			'category' => static::ENUM_RULE_CAT_REDIRECT,
			'type' => static::ENUM_RULE_GO_TO_OBJECT,
			'properties' => array(
				'mode' => static::DEFAULT_RULE_GO_TO_OBJECT_PROP_MODE,
				'opening_target' => static::DEFAULT_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET,
				'oql' => null,
			),
		);
	}

	/**
	 * Return the default definition of the "Go to brick" rule
	 *
	 * @return array
	 */
	public function GetDefaultGoToBrickRuleDefinition()
	{
		return array(
			'category' => static::ENUM_RULE_CAT_REDIRECT,
			'type' => static::ENUM_RULE_GO_TO_BRICK,
			'properties' => array(
				'route' => array(
					'id' => null,
					'params' => array(),
				),
			),
		);
	}

	//----------------------------
	// Rules definition XML loader
	//----------------------------

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 */
	protected function LoadCloseRuleFromXML(DesignElement $oRuleNode)
	{
		// No special configuration needed
		return $this->GetDefaultCloseRuleDefinition();
	}

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 */
	protected function LoadGoToHomepageRuleFromXML(DesignElement $oRuleNode)
	{
		// No special configuration needed
		return $this->GetDefaultGoToHomepageRuleDefinition();
	}

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 * @throws \DOMFormatException
	 */
	protected function LoadGoToObjectRuleFromXML(DesignElement $oRuleNode)
	{
		$sRuleId = $oRuleNode->getAttribute('id');
		// Default values
		$aRule = $this->GetDefaultGoToObjectRuleDefinition();

		$aAllowedOpeningTarget = array(
			static::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_CURRENT,
			static::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_MODAL,
			static::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_PAGE,
		);

		/** @var \Combodo\iTop\DesignElement $oPropNode */
		foreach($oRuleNode->GetNodes('*') as $oPropNode)
		{
			switch($oPropNode->nodeName)
			{
				case 'mode':
					$sMode = $oPropNode->GetText();
					if(!in_array($sMode, ObjectFormHandlerHelper::GetAllowedModes()))
					{
						throw new DOMFormatException('mode tag of navigation_rule "'.$sRuleId.'" must be valid. Expected '.implode('|', ObjectFormHandlerHelper::GetAllowedModes()).', "'.$sMode.'" given.', null, null, $oRuleNode);
					}
					$aRule['properties']['mode'] = $sMode;
					break;

				case 'opening_target':
					$sOpeningTarget = $oPropNode->GetText();
					if(!in_array($sOpeningTarget, $aAllowedOpeningTarget))
					{
						throw new DOMFormatException('opening_target tag of navigation_rule "'.$sRuleId.'" must be valid. Expected '.implode('|', $aAllowedOpeningTarget).', "'.$sOpeningTarget.'" given.', null, null, $oRuleNode);
					}
					$aRule['properties']['opening_target'] = $sOpeningTarget;
					break;

				case 'oql':
					$sOQL = $oPropNode->GetText();
					if(empty($sOQL))
					{
						throw new DOMFormatException('oql tag of navigation_rule "'.$sRuleId.'" can not be empty.');
					}
					$aRule['properties']['oql'] = $sOQL;
					break;
			}
		}

		return $aRule;
	}

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 * @throws \DOMFormatException
	 */
	protected function LoadGoToBrickRuleFromXML(DesignElement $oRuleNode)
	{
		$sRuleId = $oRuleNode->getAttribute('id');
		// Default values
		$aRule = $this->GetDefaultGoToBrickRuleDefinition();

		/** @var \Combodo\iTop\DesignElement $oPropNode */
		foreach($oRuleNode->GetNodes('*') as $oPropNode)
		{
			switch($oPropNode->nodeName)
			{
				case 'route':
					/** @var array $aRouteProperties Route ID and parameters */
					$aRouteProperties = array();
					/** @var DesignElement $oRoutePropNode */
					foreach($oPropNode->GetNodes('*') as $oRoutePropNode)
					{
						switch($oRoutePropNode->nodeName)
						{
							case 'id':
								$aRouteProperties['id'] = $oRoutePropNode->GetText();
								break;

							case 'params':
								/** @var DesignElement $oRouteParamNode */
								foreach($oRoutePropNode->GetNodes('*') as $oRouteParamNode)
								{
									$sRouteParamId = $oRouteParamNode->getAttribute('id');
									$sRouteParamValue = $oRouteParamNode->GetText();
									if(empty($sRouteParamId) || empty($sRouteParamValue))
									{
										throw new DOMFormatException('param tag of navigation_rule "'.$sRuleId.'" must have a valid ID and value.', null, null, $oRuleNode);
									}

									$aRouteProperties['params'][$sRouteParamId] = $sRouteParamValue;
								}
								break;
						}
					}

					// Consistency check
					if(empty($aRouteProperties['id']))
					{
						throw new DOMFormatException('navigation_rule "'.$sRuleId.'" must have a valid ID', null, null, $oRuleNode);
					}

					$aRule['properties']['route'] = $aRouteProperties;
					break;
			}
		}

		return $aRule;
	}

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * Load definition of a "go-to-manage-brick" rule from XML.
	 * This is a shortcut to a classic "go-to-brick" rule.
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 */
	protected function LoadGoToManageBrickRuleFromXML(DesignElement $oRuleNode)
	{
		// Default values
		$aRule = $this->GetDefaultGoToBrickRuleDefinition();
		$aRule['properties']['route']['id'] = 'p_manage_brick_display_as';
		$aRule['properties']['route']['params']['sDisplayMode'] = ManageBrick::DEFAULT_DISPLAY_MODE;

		// Rule parameters to automatically map to the route parameters
		$aParamsMapping = array(
			'id' => 'sBrickId',
			'display_mode' => 'sDisplayMode',
			'grouping_tab' => 'sGroupingTab',
			'filter' => 'sSearchValue',
		);

		/** @var \Combodo\iTop\DesignElement $oPropNode */
		foreach($oRuleNode->GetNodes('*') as $oPropNode)
		{
			$sRouteParamId = (array_key_exists($oPropNode->nodeName, $aParamsMapping)) ? $aParamsMapping[$oPropNode->nodeName] : $oPropNode->nodeName;
			$aRule['properties']['route']['params'][$sRouteParamId] = $oPropNode->GetText();
		}

		return $aRule;
	}

	/**
	 * @noinspection PhpUnused Called dynamically by static::Init()
	 *
	 * Load definition of a "go-to-browse-brick" rule from XML.
	 * This is a shortcut to a classic "go-to-brick" rule.
	 *
	 * @param \Combodo\iTop\DesignElement $oRuleNode
	 *
	 * @return array
	 */
	protected function LoadGoToBrowseBrickRuleFromXML(DesignElement $oRuleNode)
	{
		// Default values
		$aRule = $this->GetDefaultGoToBrickRuleDefinition();
		$aRule['properties']['route']['id'] = 'p_browse_brick_mode';
		$aRule['properties']['route']['params']['sBrowseMode'] = BrowseBrick::DEFAULT_BROWSE_MODE;

		// Rule parameters to automatically map to the route parameters
		$aParamsMapping = array(
			'id' => 'sBrickId',
			'browse_mode' => 'sBrowseMode',
			'filter' => 'sSearchValue',
		);

		/** @var \Combodo\iTop\DesignElement $oPropNode */
		foreach($oRuleNode->GetNodes('*') as $oPropNode)
		{
			$sRouteParamId = (array_key_exists($oPropNode->nodeName, $aParamsMapping)) ? $aParamsMapping[$oPropNode->nodeName] : $oPropNode->nodeName;
			$aRule['properties']['route']['params'][$sRouteParamId] = $oPropNode->GetText();
		}

		return $aRule;
	}

	//------------------------
	// Business logic function
	//------------------------

	/**
	 * Returns a hash array containing the target URL and if it should be opened in a modal for each type of callbacks (submit and cancel)
	 *
	 * eg :
	 * array(
	 *     'submit' => array(
	 *          'type' => 'redirect',
	 *          'url' => 'http://localhost/',
	 *          'modal' => false,
	 *     'cancel' => array(
	 *          'type' => 'close',
	 *          'url' => null,
	 *          'modal' => false,
	 *      )
	 * );
	 *
	 * @param array $aFormProperties
	 * @param \DBObject $oCurrentObject
	 * @param boolean $bIsCurrentFormInModal
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function PrepareRulesForForm(array $aFormProperties, DBObject $oCurrentObject, $bIsCurrentFormInModal = false)
	{
		// Default values
		$aResults = array(
			'submit' => array(
				'category' => static::ENUM_RULE_CAT_REDIRECT,
				'url' => null,
				'modal' => false,
			),
			'cancel' => array(
				'category' => static::ENUM_RULE_CAT_CLOSE,
				'url' => null,
				'modal' => false,
			),
		);

		// Get form's navigation rules
		$aFormNavRules = (isset($aFormProperties['properties']['navigation_rules'])) ? $aFormProperties['properties']['navigation_rules'] : array('submit' => null, 'cancel' => null);

		// Check from which origin the rule will be called
		$sRuleCallOrigin = ($bIsCurrentFormInModal) ? 'modal' : 'default';

		foreach(array_keys($aResults) as $sButtonCode)
		{
			// Retrieve rule definition
			// - Default behavior when no rule specified
			if(empty($aFormNavRules[$sButtonCode][$sRuleCallOrigin]))
			{
				switch($sButtonCode)
				{
					case 'submit':
						$sDefaultRuleType = ($bIsCurrentFormInModal) ? static::DEFAULT_RULE_SUBMIT_MODAL : static::DEFAULT_RULE_SUBMIT_PAGE;
						break;

					case 'cancel':
						$sDefaultRuleType = ($bIsCurrentFormInModal) ? static::DEFAULT_RULE_CANCEL_MODAL : static::DEFAULT_RULE_CANCEL_PAGE;
						break;
				}
				$aRuleDef = $this->GetDefaultRuleDefinitionFromType($sDefaultRuleType);
			}
			// - Specified rule
			else
			{
				$sRuleId = $aFormNavRules[$sButtonCode][$sRuleCallOrigin];
				$aRuleDef = $this->GetRuleDefinition($sRuleId);
			}

			// Set category
			$aResults[$sButtonCode]['category'] = $aRuleDef['category'];

			// Set properties regarding the type
			switch($aRuleDef['type'])
			{
				case static::ENUM_RULE_GO_TO_HOMEPAGE:
					$aResults[$sButtonCode]['url'] = $this->oRouter->generate('p_home');
					break;

				case static::ENUM_RULE_GO_TO_OBJECT:
					// Target opening mode to modal if specified or should be as current form
					if( ($aRuleDef['properties']['opening_target'] === static::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_MODAL)
						||	(($aRuleDef['properties']['opening_target'] === static::ENUM_RULE_GO_TO_OBJECT_PROP_OPENING_TARGET_CURRENT) && ($bIsCurrentFormInModal === true))
					)
					{
						$aResults[$sButtonCode]['modal'] = true;
					}

					// Target URL
					// - Find object
					if(empty($aRuleDef['properties']['oql']))
					{
						$oTargetObject = $oCurrentObject;
					}
					else
					{
						$oSearch = DBSearch::FromOQL($aRuleDef['properties']['oql']);
						$oSet = new DBObjectSet($oSearch, array(), array('this' => $oCurrentObject));
						$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array()));
						$oTargetObject = $oSet->Fetch();
					}
					// - Build URL
					$aResults[$sButtonCode]['url'] = $this->oRouter->generate('p_object_'.$aRuleDef['properties']['mode'], array('sObjectClass' => get_class($oTargetObject), 'sObjectId' => $oTargetObject->GetKey()));
					break;

				case static::ENUM_RULE_GO_TO_BRICK:
					// Build URL
					$aRouteProperties = $aRuleDef['properties']['route'];
					$aResults[$sButtonCode]['url'] = $this->oRouter->generate($aRouteProperties['id'], $aRouteProperties['params']);
					break;

				case static::ENUM_RULE_CLOSE:
				default:
					// Don't set the URL
					break;
			}
		}

		return $aResults;
	}
}

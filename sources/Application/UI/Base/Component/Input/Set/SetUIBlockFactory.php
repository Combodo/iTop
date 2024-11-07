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

namespace Combodo\iTop\Application\UI\Base\Component\Input\Set;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\AjaxDataProvider;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\AjaxDataProviderForOQL;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\SimpleDataProvider;

/**
 * Class SetUIBlockFactory
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set
 */
class SetUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UISet';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Set::class;

	/**
	 * MakeForSimple.
	 *
	 * Create a simple set base on a static array of options.
	 * Options array must contain label, value and search string for each option.
	 * Keys for each entry must be provided but can be the same.
	 * If a group field is provided, options will be grouped according to this setting.
	 *
	 * @param string $sId Block identifier
	 * @param array $aOptions Array containing options
	 * @param string $sLabelFields Field used for label rendering
	 * @param string $sValueField Field used for option value
	 * @param array $aSearchFields Fields used for searching
	 * @param string|null $sGroupField Field used for grouping
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Set\Set
	 */
	public static function MakeForSimple(string $sId, array $aOptions, string $sLabelFields, string $sValueField, array $aSearchFields, ?string $sGroupField = null, ?string $sTooltipField = null): Set
	{
		// Create set ui block
		$oSetUIBlock = new Set($sId);

		// Simple data provider
		$oDataProvider = new SimpleDataProvider($aOptions);
		$oDataProvider
			->SetDataLabelField($sLabelFields)
			->SetDataValueField($sValueField)
			->SetDataSearchFields($aSearchFields)
			->SetTooltipField($sTooltipField ?? $sLabelFields);
		if ($sGroupField != null) {
			$oDataProvider->SetGroupField($sGroupField);
		}
		$oSetUIBlock->SetDataProvider($oDataProvider);

		return $oSetUIBlock;
	}

	/**
	 * MakeForAjax.
	 *
	 * Create a dynamic set base on options provided by ajax call.
	 * Options array must contain label, value and search string for each option.
	 * Keys for each entry must be provided but can be the same.
	 * If a group field is provided, options will be grouped according to this setting.
	 *
	 * @param string $sId Block identifier
	 * @param string $sAjaxRoute Ajax route @see \Combodo\iTop\Service\Router\Router
	 * @param array $aAjaxRouteParams Url query parameters
	 * @param string $sLabelFields Field used for label
	 * @param string $sValueField Field used for value
	 * @param array $aSearchFields Fields used for search
	 * @param string|null $sGroupField Field used for grouping
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Set\Set
	 */
	public static function MakeForAjax(string $sId, string $sAjaxRoute, array $aAjaxRouteParams, string $sLabelFields, string $sValueField, array $aSearchFields, ?string $sGroupField = null): Set
	{
		// Create set ui block
		$oSetUIBlock = new Set($sId);

		// Ajax data provider
		$oDataProvider = new AjaxDataProvider($sAjaxRoute, $aAjaxRouteParams);
		$oDataProvider
			->SetDataLabelField($sLabelFields)
			->SetDataValueField($sValueField)
			->SetDataSearchFields($aSearchFields)
			->SetTooltipField($sLabelFields);
		if ($sGroupField != null) {
			$oDataProvider->SetGroupField($sGroupField);
		}
		$oSetUIBlock->SetDataProvider($oDataProvider);

		return $oSetUIBlock;
	}

	/**
	 * MakeForOQL.
	 *
	 * Create a oql set base on options provided by OQL call.
	 * Options array must contain label, value and search string for each option.
	 * Keys for each entry must be provided but can be the same.
	 * If a group field is provided, options will be grouped according to this setting.
	 * Default fields are loaded but you can request more.
	 *
	 * @param string $sId Block identifier
	 * @param string $sObjectClass Object class
	 * @param string $sOql OQL to query objects
	 * @param string|null $sWizardHelperJsVarName Wizard helper name
	 * @param array $aFieldsToLoad Additional fields to load on objects
	 * @param string|null $sGroupField Field used for grouping
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Set\Set
	 */
	public static function MakeForOQL(string $sId, string $sObjectClass, string $sOql, string $sWizardHelperJsVarName = null, array $aFieldsToLoad = [], ?string $sGroupField = null): Set
	{
		// Create set ui block
		$oSetUIBlock = new Set($sId);

		// Renderers
		$oSetUIBlock->SetOptionsTemplate('application/object/set/option_renderer.html.twig');
		$oSetUIBlock->SetItemsTemplate('application/object/set/item_renderer.html.twig');

		// OQL data provider
		$oDataProvider = new AjaxDataProviderForOQL($sObjectClass, $sOql, $sWizardHelperJsVarName, $aFieldsToLoad);
		if ($sGroupField != null) {
			$oDataProvider->SetGroupField($sGroupField);
		}
		$oDataProvider->SetTooltipField('full_description');
		$oSetUIBlock->SetDataProvider($oDataProvider);

		return $oSetUIBlock;
	}
}
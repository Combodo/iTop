<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Component\Navigation;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use DBObjectSearch;
use DBObjectSet;
use utils;

/**
 * Class PanelUIBlockFactory
 *
 * @package UIBlockExtensibilityAPI
 * @api
 * @since 3.1.0
 *
 * @link <itop_url>/test/VisualTest/Backoffice/RenderAllUiBlocks.php#title-panels to see live examples
 */
class NavigationUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UINavigation';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Navigation::class;

	/**
	 * Make a basis Panel component
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeStandard($oObject, string $sBasketFilter, string $sBasketClass, array $aList = [], string $sBackUrl = '', $sPostedFieldsForBackUrl = "")
	{
		if (utils::IsNotNullOrEmptyString($sBasketFilter) && count($aList) === 0) {
			$oBasketFilter = DBObjectSearch::FromOQL($sBasketFilter);
			$oSet = new DBObjectSet($oBasketFilter);
			$aList = $oSet->GetColumnAsArray('id', false);
			if (utils::IsNullOrEmptyString($sBasketClass)) {
				$sBasketClass = $oBasketFilter->GetClass();
			}
		}
		if (utils::IsNullOrEmptyString($sBasketClass)) {
			$oBasketFilter = DBObjectSearch::FromOQL($sBasketFilter);
			$sBasketClass = $oBasketFilter->GetClass();
		}
		if (count($aList) === 0) {
			return null;
		}

		$iIdx = array_search($oObject->GetKey(), $aList);
		$oNavigationBlock = new Navigation($sBasketClass, $iIdx, $aList, $sBasketFilter, $sBackUrl, $sPostedFieldsForBackUrl);

		return $oNavigationBlock;
	}

}
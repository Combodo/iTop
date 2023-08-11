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

namespace Combodo\iTop\Application\UI\Base\Component\Basket;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use DBObjectSearch;
use DBObjectSet;
use utils;

/**
 * Class BasketUIBlockFactory
 *
 * @api
 * @package UIBlockExtensibilityAPI
 * @since 3.1.1
 *
 * @link <itop_url>/test/VisualTest/Backoffice/RenderAllUiBlocks.php#title-panels to see live examples
 */
class BasketUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIBasket';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Basket::class;

	/**
	 * Make a basis Panel component
	 *
	 * @api
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Basket
	 */
	public static function MakeStandard($oObject, string $sFilter, string $sClass, array $aList = [], string $sBackUrl = '', $sPostedFieldsForBackUrl = "")
	{
		if (utils::IsNotNullOrEmptyString($sFilter) && count($aList) === 0) {
			$oBasketFilter = DBObjectSearch::FromOQL($sFilter);
			$oSet = new DBObjectSet($oBasketFilter);
			$aList = $oSet->GetColumnAsArray('id', false);
			if (utils::IsNullOrEmptyString($sClass)) {
				$sClass = $oBasketFilter->GetClass();
			}
		}
		if (utils::IsNullOrEmptyString($sClass)) {
			$oBasketFilter = DBObjectSearch::FromOQL($sFilter);
			$sClass = $oBasketFilter->GetClass();
		}
		if (count($aList) === 0) {
			return null;
		}

		$iIdx = array_search($oObject->GetKey(), $aList);
		$oNavigationBlock = new Basket($sClass, $iIdx, $aList, $sFilter, $sBackUrl, $sPostedFieldsForBackUrl);

		return $oNavigationBlock;
	}

}
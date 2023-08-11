<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Layout\PageContent;


use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Basket\BasketUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelFactory;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectFactory;
use DBObject;

/**
 * Class PageContentFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\PageContent
 * @since 3.0.0
 */
class PageContentFactory
{
	/**
	 * Make a standard empty PageContent layout for backoffice pages.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent
	 */
	public static function MakeStandardEmpty()
	{
		return new PageContent();
	}

	/**
	 * Make a standard object details page with the form in the middle and the logs / activity in the side panel
	 *
	 * @see cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 *
	 * @param \DBObject $oObject
	 * @param string $sMode Mode the object is being displayed (view, edit, create, ...), default is view.
	 *
	 * since 3.1.1 params for navigation in basket
	 * @param string $sBasketFilter filter to find list of objects in basket
	 * @param array $aBasketList list of id of objects in basket
	 * @param string $sBackUrl url to go back to list of ojects in basket
	 * @param string $sPostedFieldsForBackUrl fields to post for come back to main page
	 *
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentWithSideContent
	 * @throws \CoreException
	 */
	public static function MakeForObjectDetails(DBObject $oObject, string $sMode = cmdbAbstractObject::DEFAULT_DISPLAY_MODE, $sBasketFilter = null, $aBasketList = [], $sBackUrl = null, $sPostedFieldsForBackUrl = "")
	{
		$oLayout = new PageContentWithSideContent();


		if ($sBasketFilter != null) {
			$oNavigationBlock = BasketUIBlockFactory::MakeStandard($oObject, $sBasketFilter, $aBasketList, $sBackUrl, $sPostedFieldsForBackUrl);
			if ($oNavigationBlock != null) {
				$oLayout->AddSubBlock($oNavigationBlock);
			}
		}

		// Add object activity layout
		$oActivityPanel = ActivityPanelFactory::MakeForObjectDetails($oObject, $sMode);
		$oLayout->AddSideBlock($oActivityPanel);

		return $oLayout;
	}
}
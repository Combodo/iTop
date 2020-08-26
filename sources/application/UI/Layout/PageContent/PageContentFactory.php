<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Layout\PageContent;


use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityPanel;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityPanelFactory;
use DBObject;

/**
 * Class PageContentFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\PageContent
 * @since 2.8.0
 */
class PageContentFactory
{
	/**
	 * Make a standard empty PageContent layout for backoffice pages.
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\PageContent\PageContent
	 */
	public static function MakeStandardEmpty()
	{
		return new PageContent();
	}

	/**
	 * Make a standard object details page with the form in the middle and the logs / activity in the side panel
	 *
	 * @param \DBObject $oObject
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\PageContent\PageContentWithSideContent
	 * @throws \CoreException
	 */
	public static function MakeForObjectDetails(DBObject $oObject)
	{
		$oLayout = new PageContentWithSideContent();

		// Add object details layout
		// TODO

		// Add object activity layout
		$oActivityPanel = ActivityPanelFactory::MakeForObjectDetails($oObject);
		$oLayout->AddSideBlock($oActivityPanel);

		return $oLayout;
	}
}
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

namespace Combodo\iTop\Application\UI\Component\Panel;

/**
 * Class PanelFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Panel
 * @since 2.8.0
 */
class PanelFactory
{
	/**
	 * Make a basis Panel component
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeNeutral($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_NEUTRAL);

		return $oPanel;
	}

	/**
	 * Make a Panel component for informational messages
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeForInformation($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_INFORMATION);

		return $oPanel;
	}

	/**
	 * Make a Panel component for successful messages
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeForSuccess($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_SUCCESS);

		return $oPanel;
	}

	/**
	 * Make a Panel component for warning messages
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeForWarning($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_WARNING);

		return $oPanel;
	}

	/**
	 * Make a Panel component for danger messages
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeForDanger($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_DANGER);

		return $oPanel;
	}

	/**
	 * Make a Panel component for failure messages
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeForFailure($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_FAILURE);

		return $oPanel;
	}

	/**
	 * Make a Panel component with primary color scheme
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeWithBrandingPrimaryColor($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_PRIMARY);

		return $oPanel;
	}

	/**
	 * Make a Panel component with secondary color scheme
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Panel\Panel
	 */
	public static function MakeWithBrandingSecondaryColor($sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_SECONDARY);

		return $oPanel;
	}
}
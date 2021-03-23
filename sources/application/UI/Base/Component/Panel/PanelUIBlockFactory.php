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

namespace Combodo\iTop\Application\UI\Base\Component\Panel;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class PanelUIBlockFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Panel
 * @since 3.0.0
 *
 * @link <itop_url>/test/VisualTest/Backoffice/RenderAllUiBlocks.php#title-panels to see live examples
 */
class PanelUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIPanel';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Panel::class;

	/**
	 * Make a basis Panel component
	 *
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeNeutral(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForInformation(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForSuccess(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForWarning(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForDanger(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForFailure(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeWithBrandingPrimaryColor(string $sTitle)
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeWithBrandingSecondaryColor(string $sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColor(Panel::ENUM_COLOR_SECONDARY);

		return $oPanel;
	}

	/**
	 * Make a Panel component with the specific $sClass color scheme
	 *
	 * @param string $sClass Class of the object the panel is for
	 * @param string $sTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForClass(string $sClass, string $sTitle)
	{
		$oPanel = new Panel($sTitle);
		$oPanel->SetColorFromClass($sClass);

		return $oPanel;
	}
}
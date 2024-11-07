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

namespace Combodo\iTop\Application\UI\Base\Component\Panel;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class PanelUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package UIBlockAPI
 * @api
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
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeNeutral(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_NEUTRAL);

		return $oPanel;
	}

	/**
	 * Make a Panel component for informational messages
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForInformation(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_INFORMATION);

		return $oPanel;
	}

	/**
	 * Make a Panel component for successful messages
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForSuccess(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_SUCCESS);

		return $oPanel;
	}

	/**
	 * Make a Panel component for warning messages
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForWarning(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_WARNING);

		return $oPanel;
	}

	/**
	 * Make a Panel component for danger messages
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForDanger(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_DANGER);

		return $oPanel;
	}

	/**
	 * Make a Panel component for failure messages
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForFailure(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_FAILURE);

		return $oPanel;
	}

	/**
	 * Make a Panel component with primary color scheme
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeWithBrandingPrimaryColor(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_PRIMARY);

		return $oPanel;
	}

	/**
	 * Make a Panel component with secondary color scheme
	 *
	 * @api
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeWithBrandingSecondaryColor(string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromColorSemantic(Panel::ENUM_COLOR_SCHEME_SECONDARY);

		return $oPanel;
	}

	/**
	 * Make a Panel component with the specific $sClass color scheme
	 *
	 * @api
	 * @param string $sClass Class of the object the panel is for
	 * @param string $sTitle
	 * @param string|null $sSubTitle
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Panel\Panel
	 */
	public static function MakeForClass(string $sClass, string $sTitle, string $sSubTitle = null)
	{
		$oPanel = new Panel($sTitle);
		if (!is_null($sSubTitle)) {
			$oPanel->SetSubTitle($sSubTitle);
		}
		$oPanel->SetColorFromClass($sClass);

		return $oPanel;
	}
}
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

namespace Combodo\iTop\Application\UI\Base\Component\Alert;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class AlertUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package UIBlockAPI
 * @since 3.0.0
 * @api
 *
 * @link <itop_url>/test/VisualTest/Backoffice/RenderAllUiBlocks.php#title-alerts to see live examples
 */
class AlertUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIAlert';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Alert::class;

	/**
	 * Make a basis Alert component
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeNeutral(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_NEUTRAL, $sId);
	}

	/**
	 * Make an Alert component for informational messages
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeForInformation(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_INFORMATION, $sId);

	}

	/**
	 * Make an Alert component for successful messages
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeForSuccess(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_SUCCESS, $sId);
	}

	/**
	 * Make an Alert component for warning messages
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeForWarning(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_WARNING, $sId);
	}

	/**
	 * Make an Alert component for danger messages
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeForDanger(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_DANGER, $sId);
	}

	/**
	 * Make an Alert component for failure messages
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeForFailure(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_FAILURE, $sId);
	}

	/**
	 * Make an Alert component with primary color scheme
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeWithBrandingPrimaryColor(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_PRIMARY, $sId);
	}

	/**
	 * Make an Alert component with secondary color scheme
	 *
	 * @api
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeWithBrandingSecondaryColor(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SCHEME_SECONDARY, $sId);
	}
}
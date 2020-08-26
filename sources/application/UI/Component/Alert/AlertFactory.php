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

namespace Combodo\iTop\Application\UI\Component\Alert;

/**
 * Class AlertFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Alert
 * @since 2.8.0
 */
class AlertFactory
{
	/**
	 * Make a basis Alert component
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeNeutral(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_NEUTRAL);
	}

	/**
	 * Make an Alert component for informational messages
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeForInformation(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_INFORMATION);
	}

	/**
	 * Make an Alert component for successful messages
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeForSuccess(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SUCCESS);
	}

	/**
	 * Make an Alert component for warning messages
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeForWarning(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_WARNING);
	}

	/**
	 * Make an Alert component for danger messages
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeForDanger(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_DANGER);
	}

	/**
	 * Make an Alert component for failure messages
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeForFailure(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_FAILURE);
	}

	/**
	 * Make an Alert component with primary color scheme
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeWithBrandingPrimaryColor(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_PRIMARY);
	}

	/**
	 * Make an Alert component with secondary color scheme
	 *
	 * @param string $sTitle
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Alert\Alert
	 */
	public static function MakeWithBrandingSecondaryColor(string $sTitle, string $sContent)
	{
		return new Alert($sTitle, $sContent, Alert::ENUM_COLOR_SECONDARY);
	}
}
<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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

use Symfony\Component\DependencyInjection\Container;

/**
 * Adds a dark theme to the user portal.
 *
 * The dark stylesheet is:
 *  - always applied when the current user's back-office theme is a dark one (module setting "dark_themes", "darkmoon" by default),
 *  - otherwise applied only when the browser / OS prefers a dark color scheme (CSS media query), unless the module setting "follow_os" is false.
 *
 * The stylesheets are compiled from the SCSS files of the css/ folder and versioned next to them.
 *
 * @since 3.4.0
 */
class PortalDarkThemeUIExtension extends AbstractPortalUIExtension
{
	/** @var string */
	public const MODULE_CODE = 'itop-portal-dark-theme';

	/** @var string Dark stylesheet always applied */
	public const ENUM_MODE_DARK = 'dark';
	/** @var string Dark stylesheet applied only when the browser / OS prefers a dark color scheme */
	public const ENUM_MODE_AUTO = 'auto';
	/** @var string No dark stylesheet */
	public const ENUM_MODE_LIGHT = 'light';

	/**
	 * @var array<string, string> Stylesheet (relative to the module folder) for each mode
	 */
	protected const STYLESHEETS = [
		self::ENUM_MODE_DARK => 'css/portal-dark-theme.css',
		self::ENUM_MODE_AUTO => 'css/portal-dark-theme-auto.css',
	];

	/**
	 * @inheritDoc
	 * @since 3.4.0
	 */
	public function GetCSSFiles(Container $oContainer)
	{
		$sMode = static::GetCurrentUserMode();
		if (!array_key_exists($sMode, static::STYLESHEETS)) {
			return [];
		}

		return [utils::GetAbsoluteUrlModulesRoot().static::MODULE_CODE.'/'.static::STYLESHEETS[$sMode]];
	}

	/**
	 * @return string One of the ENUM_MODE_* constants, for the current user and the module settings
	 * @since 3.4.0
	 */
	public static function GetCurrentUserMode(): string
	{
		$aDarkThemes = MetaModel::GetModuleSetting(static::MODULE_CODE, 'dark_themes', ['darkmoon']);
		$bFollowOs = (bool) MetaModel::GetModuleSetting(static::MODULE_CODE, 'follow_os', true);

		return static::ComputeMode(ThemeHandler::GetCurrentUserThemeId(), (array) $aDarkThemes, $bFollowOs);
	}

	/**
	 * @param string|null $sBackofficeThemeId Back-office theme of the user
	 * @param string[] $aDarkThemeIds Back-office themes considered as dark
	 * @param bool $bFollowOs Whether to follow the browser / OS color scheme when the user's theme is not a dark one
	 *
	 * @return string One of the ENUM_MODE_* constants
	 * @since 3.4.0
	 */
	public static function ComputeMode(?string $sBackofficeThemeId, array $aDarkThemeIds, bool $bFollowOs): string
	{
		if (!is_null($sBackofficeThemeId) && in_array($sBackofficeThemeId, $aDarkThemeIds, true)) {
			return static::ENUM_MODE_DARK;
		}

		return $bFollowOs ? static::ENUM_MODE_AUTO : static::ENUM_MODE_LIGHT;
	}
}

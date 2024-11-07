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

namespace Combodo\iTop\Application;

use MetaModel;
use utils;

/**
 * Class Branding
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application
 * @since 3.0.0
 */
class Branding
{
	/** @var string Full main logo, used everywhere when there is no need for a special one */
	public const ENUM_LOGO_TYPE_MAIN_LOGO_FULL = 'main_logo_full';
	/** @var string Compact main logo, used in the collapsed menu of the backoffice */
	public const ENUM_LOGO_TYPE_MAIN_LOGO_COMPACT = 'main_logo_compact';
	/** @var string Logo used in the end-users portal */
	public const ENUM_LOGO_TYPE_PORTAL_LOGO = 'portal_logo';
	/** @var string Logo used in the login pages */
	public const ENUM_LOGO_TYPE_LOGIN_LOGO = 'login_logo';
	/**
	 * @var string Logo used in most pages (backoffice, ...)
	 * @since 3.2.0 N°3363
	 */
	public const ENUM_LOGO_TYPE_MAIN_FAVICON = 'main_favicon';
	/**
	 * @var string Logo used in the end-users portals
	 * @since 3.2.0 N°3363
	 */
	public const ENUM_LOGO_TYPE_PORTAL_FAVICON = 'portal_favicon';
	/**
	 * @var string Logo used in the login page
	 * @since 3.2.0 N°3363
	 */
	public const ENUM_LOGO_TYPE_LOGIN_FAVICON = 'login_favicon';
	/** @var string Default logo */
	public const DEFAULT_LOGO_TYPE = self::ENUM_LOGO_TYPE_MAIN_LOGO_FULL;

	/** @var \string[][] Relative paths to the logos from the current environment folder */
	public static $aLogoPaths = [
		self::ENUM_LOGO_TYPE_MAIN_LOGO_FULL    => [
			'default' => 'images/itop-logo.png',
		],
		self::ENUM_LOGO_TYPE_MAIN_LOGO_COMPACT => [
			'default' => 'images/itop-logo-square.png',
		],
		self::ENUM_LOGO_TYPE_PORTAL_LOGO       => [
			'default' => 'images/logo-itop-dark-bg.svg',
		],
		self::ENUM_LOGO_TYPE_LOGIN_LOGO        => [
			'default' => 'images/itop-logo-external.png',
		],
		self::ENUM_LOGO_TYPE_MAIN_FAVICON      => [
			'default' => 'images/favicon.ico',
		],
		self::ENUM_LOGO_TYPE_PORTAL_FAVICON    => [
			'default' => 'images/favicon.ico',
		],
		self::ENUM_LOGO_TYPE_LOGIN_FAVICON     => [
			'default' => 'images/favicon.ico',
		],
	];

	/**
	 * Return url or path of logo defined by $sType
	 *
	 * @param string $sType
	 * @param string $sAppPath
	 * @param ?string $sModulePath
	 *
	 * @return string
	 */
	protected static function GetLogoPath(string $sType, string $sAppPath, ?string $sModulePath = null): string
	{
		$sDefaultLogoPath = static::$aLogoPaths[$sType]['default'];
		$sWorkingPath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/';
		$aThemeParameters = json_decode(@file_get_contents($sWorkingPath.'branding/logos.json'), true);
		if (isset($aThemeParameters[$sType])) {
			$sCustomLogoPath = $aThemeParameters[$sType];
			if (file_exists($sWorkingPath.$sCustomLogoPath)) {
				return ($sModulePath ?? $sAppPath).$sCustomLogoPath;
			}
		}

		return $sAppPath.$sDefaultLogoPath;
	}

	/**
	 * Return the absolute URL for the type of logo defined by $sType
	 *
	 * @param string $sType Type of the logo to return
	 * @see static::ENUM_LOGO_TYPE_XXX
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetLogoAbsoluteUrl($sType = self::DEFAULT_LOGO_TYPE)
	{
		return self::GetLogoPath($sType,  utils::GetAbsoluteUrlAppRoot(), utils::GetAbsoluteUrlModulesRoot()).'?t='.utils::GetCacheBusterTimestamp();
	}

	/**
	 * Return the relative path for the type of logo defined by $sType
	 *
	 * @param string $sType Type of the logo to return
	 * @see static::ENUM_LOGO_TYPE_XXX
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetLogoRelativePath($sType = self::DEFAULT_LOGO_TYPE)
	{
		$sPath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/';

		return self::GetLogoPath($sType, APPROOT, $sPath);
	}

	/**
	 * Return the absolute URL for the full main logo
	 *
	 * @return string
	 * @throws \Exception<
	 */
	public static function GetFullMainLogoAbsoluteUrl()
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_MAIN_LOGO_FULL);
	}

	/**
	 * Return the absolute URL for the compact main logo
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetCompactMainLogoAbsoluteUrl()
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_MAIN_LOGO_COMPACT);
	}

	/**
	 * Return the absolute URL for the portal logo
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetPortalLogoAbsoluteUrl()
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_PORTAL_LOGO);
	}

	/**
	 * Return the absolute URL for the login logo
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetLoginLogoAbsoluteUrl()
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_LOGIN_LOGO);
	}

	/**
	 * Return the absolute URL for thefavicon
	 *
	 * @return string
	 * @throws \Exception
	 * @since 3.2.0 N°3363
	 */
	public static function GetMainFavIconAbsoluteUrl(): string
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_MAIN_FAVICON);
	}

	/**
	 * Return the absolute URL for thefavicon
	 *
	 * @return string
	 * @throws \Exception
	 * @since 3.2.0 N°3363
	 */
	public static function GetPortalFavIconAbsoluteUrl(): string
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_PORTAL_FAVICON);
	}

	/**
	 * Return the absolute URL for thefavicon
	 *
	 * @return string
	 * @throws \Exception
	 * @since 3.2.0 N°3363
	 */
	public static function GetLoginFavIconAbsoluteUrl(): string
	{
		return static::GetLogoAbsoluteUrl(static::ENUM_LOGO_TYPE_LOGIN_FAVICON);
	}
}
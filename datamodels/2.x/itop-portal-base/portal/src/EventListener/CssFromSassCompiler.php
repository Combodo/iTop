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

namespace Combodo\iTop\Portal\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use utils;

/**
 * Class CssFromSassCompiler
 *
 * This class is mostly here for developers comfort. SCSS files are already compiled when Symfony is creating its cache, but we need them to re-compile
 * when tuning the SCSS files during development. To do so, just by pass the HTTP cache by hitting Ctrl + F5
 *
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 */
class CssFromSassCompiler
{
	/** @var array $aCombodoPortalInstanceConf */
	private $aCombodoPortalInstanceConf;

	/**
	 * CssFromSassCompiler constructor.
	 *
	 * @param array $aCombodoPortalInstanceConf
	 */
	public function __construct($aCombodoPortalInstanceConf)
	{
		$this->aCombodoPortalInstanceConf = $aCombodoPortalInstanceConf;
	}

	/**
	 * @param RequestEvent $oRequestEvent
	 */
	public function onKernelRequest(RequestEvent $oRequestEvent)
	{
		// Force compilation need only when by-passing cache to limit server load.
		if (isset($_SERVER['HTTP_CACHE_CONTROL']) && ($_SERVER['HTTP_CACHE_CONTROL'] !== 'no-cache')) {
			return;
		}

		$aImportPaths = array($_ENV['COMBODO_PORTAL_BASE_ABSOLUTE_PATH'].'css/');
		foreach ($this->aCombodoPortalInstanceConf['properties']['themes'] as $sKey => $value) {
			if (!is_array($value))
			{
				utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$value, $aImportPaths);
			}
			else
			{
				foreach ($value as $sSubValue)
				{
					utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$sSubValue, $aImportPaths);
				}
			}
		}
	}
}
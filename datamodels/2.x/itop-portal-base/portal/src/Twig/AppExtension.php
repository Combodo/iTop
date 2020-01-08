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

namespace Combodo\iTop\Portal\Twig;

use Twig\Extension\AbstractExtension;

use Twig_SimpleFilter;
use Twig_SimpleFunction;
use utils;
use Dict;
use MetaModel;

/**
 * Class AppExtension
 *
 * @package Combodo\iTop\Portal\Twig
 * @since   2.7.0
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 */
class AppExtension extends AbstractExtension
{
	/**
	 * @return array|\Twig\TwigFilter[]|\Twig_SimpleFilter[]
	 */
	public function getFilters()
	{
		$filters = array();
		// Filter to translate a string via the Dict::S function
		// Usage in twig: {{ 'String:ToTranslate'|dict_s }}
		$filters[] = new Twig_SimpleFilter('dict_s',
			function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
				return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
			}
		);

		// Filter to format a string via the Dict::Format function
		// Usage in twig: {{ 'String:ToTranslate'|dict_format() }}
		$filters[] = new Twig_SimpleFilter('dict_format',
			function ($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null) {
				return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
			}
		);

		// Filter to enable base64 encode/decode
		// Usage in twig: {{ 'String to encode'|base64_encode }}
		$filters[] = new Twig_SimpleFilter('base64_encode', 'base64_encode');
		$filters[] = new Twig_SimpleFilter('base64_decode', 'base64_decode');

		// Filter to enable json decode  (encode already exists)
		// Usage in twig: {{ aSomeArray|json_decode }}
		$filters[] = new Twig_SimpleFilter('json_decode', function ($sJsonString, $bAssoc = false) {
			return json_decode($sJsonString, $bAssoc);
		}
		);

		// Filter to add itopversion to an url
		$filters[] = new Twig_SimpleFilter('add_itop_version', function ($sUrl) {
			if (strpos($sUrl, '?') === false)
			{
				$sUrl = $sUrl."?itopversion=".ITOP_VERSION;
			}
			else
			{
				$sUrl = $sUrl."&itopversion=".ITOP_VERSION;
			}

			return $sUrl;
		});

		// Filter to add a module's version to an url
		$filters[] = new Twig_SimpleFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);

			if (strpos($sUrl, '?') === false)
			{
				$sUrl = $sUrl."?moduleversion=".$sModuleVersion;
			}
			else
			{
				$sUrl = $sUrl."&moduleversion=".$sModuleVersion;
			}

			return $sUrl;
		});


		return $filters;
	}

	/**
	 * @return array|\Twig\TwigFunction[]|\Twig_SimpleFunction[]
	 */
	public function getFunctions()
	{
		$functions = array();

		// Function to check our current environment
		// Usage in twig:   {% if is_development_environment() %}
		$functions[] = new Twig_SimpleFunction('is_development_environment', function () {
			return utils::IsDevelopmentEnvironment();
		});

		// Function to get configuration parameter
		// Usage in twig: {{ get_config_parameter('foo') }}
		$functions[] = new Twig_SimpleFunction('get_config_parameter', function ($sParamName) {
			$oConfig = MetaModel::GetConfig();

			return $oConfig->Get($sParamName);
		});

		return $functions;
	}


}
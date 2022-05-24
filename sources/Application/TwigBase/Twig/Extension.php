<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;


use AttributeDate;
use AttributeDateTime;
use AttributeText;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Renderer\BlockRenderer;
use Dict;
use Exception;
use MetaModel;
use Twig_Environment;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use utils;

class Extension
{
	/**
	 * Registers Twig extensions such as filters or functions.
	 * It allows us to access some stuff directly in twig.
	 *
	 * @param \Twig_Environment $oTwigEnv
	 */
	public static function RegisterTwigExtensions(Twig_Environment &$oTwigEnv): void
	{
		$aFilters = static::GetFilters();
		foreach ($aFilters as $oFilter) {
			$oTwigEnv->addFilter($oFilter);
		}

		$aFunctions = static::GetFunctions();
		foreach ($aFunctions as $oFunction) {
			$oTwigEnv->addFunction($oFunction);
		}
	}

	/**
	 * @used-by \Combodo\iTop\Portal\Twig\AppExtension
	 * @return Twig_SimpleFilter[] Custom TWIG filters used in iTop
	 * @since 3.1.0
	 */
	public static function GetFilters()
	{
		$aFilters = [];

		// Filter to translate a string via the Dict::S function
		// Usage in twig: {{ 'String:ToTranslate'|dict_s }}
		$aFilters[] = new Twig_SimpleFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
			return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
		});

		// Filter to format a string via the Dict::Format function
		// Usage in twig: {{ 'String:ToTranslate'|dict_format() }}
		$aFilters[] = new Twig_SimpleFilter('dict_format', function ($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null) {
			return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
		});

		// Filter to format output
		// For example a DateTime is converted to user format
		// Usage in twig: {{ '2022-05-13 12:00:00'|date_format }}
		$aFilters[] = new Twig_SimpleFilter('date_format', function ($sDate) {
			try
			{
				if (preg_match('@^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$@', trim($sDate)))
				{
					return AttributeDateTime::GetFormat()->Format($sDate);
				}
				if (preg_match('@^\d\d\d\d-\d\d-\d\d$@', trim($sDate)))
				{
					return AttributeDate::GetFormat()->Format($sDate);
				}
			}
			catch (Exception $e)
			{
			}

			return $sDate;
		});

		// Filter to format output
		// For example a file size is converted to human readable format
		// Usage in twig: {{ '4096'|size_format }}
		$aFilters[] = new Twig_SimpleFilter('size_format', function ($sSize) {
			return utils::BytesToFriendlyFormat($sSize);
		});

		// Filter to enable base64 encode/decode
		// Usage in twig: {{ 'String to encode'|base64_encode }}
		$aFilters[] = new Twig_SimpleFilter('base64_encode', 'base64_encode');
		$aFilters[] = new Twig_SimpleFilter('base64_decode', 'base64_decode');

		// Filter to enable json decode  (encode already exists)
		// Usage in twig: {{ aSomeArray|json_decode }}
		$aFilters[] = new Twig_SimpleFilter('json_decode', function ($sJsonString, $bAssoc = false) {
			return json_decode($sJsonString, $bAssoc);
		});

		/**
		 * Filter to sanitize a text
		 * Usage in twig: {{ 'variable_name:to-sanitize'|sanitize(constant('utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME')) }}
		 *
		 * @uses \utils::Sanitize()
		 * @since 3.0.0
		 */
		$aFilters[] = new Twig_SimpleFilter('sanitize', function (string $sString, string $sFilter) {
				return utils::Sanitize($sString, '', $sFilter);
			});

		/**
		 * Filter to transform the wiki syntax ONLY into HTML.
		 *
		 * @uses \AttributeText::RenderWikiHtml()
		 * @since 3.0.0
		 */
		$aFilters[] = new Twig_SimpleFilter('render_wiki_to_html', function ($sString) {
			return AttributeText::RenderWikiHtml($sString, true /* Important, otherwise hyperlinks will be tranformed as well */);
		});

		// Filter to add a parameter at the end of the URL to force cache invalidation after an upgrade.
		// Previously we put the iTop version but now it's the last setup/toolkit timestamp to avoid cache issues when building several times the same version during tests
		//
		// Note: This could be renamed "add_cache_buster" instead.
		$aFilters[] = new Twig_SimpleFilter('add_itop_version', function ($sUrl) {
			$sUrl = utils::AddParameterToUrl($sUrl, 't', utils::GetCacheBusterTimestamp());

			return $sUrl;
		});

		// Filter to add a module's version to an url
		$aFilters[] = new Twig_SimpleFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);
			$sUrl = utils::AddParameterToUrl($sUrl, 'moduleversion', $sModuleVersion);

			return $sUrl;
		});

		// var_export can be used for example to transform a PHP boolean to 'true' or 'false' strings
		// @see https://www.php.net/manual/fr/function.var-export.php
		$aFilters[] = new Twig_SimpleFilter('var_export', 'var_export');

		$aFilters[] = new Twig_SimpleFilter('filter', function ($array, $arrow) {
			if ($arrow == 'system') {
				return json_encode($array);
			}

			return twig_array_filter($array, $arrow);
		});

		return $aFilters;
	}

	/**
	 * @used-by \Combodo\iTop\Portal\Twig\AppExtension
	 * @return \Twig_SimpleFunction[] Custom TWIG function used in iTop
	 * @since 3.1.0
	 */
	public static function GetFunctions()
	{
		$aFunctions = [];

		// Function to check our current environment
		// Usage in twig: {% if is_development_environment() %}
		$aFunctions[] = new Twig_SimpleFunction('is_development_environment', function () {
			return utils::IsDevelopmentEnvironment();
		});

		// Function to get configuration parameter
		// Usage in twig: {{ get_config_parameter('foo') }}
		$aFunctions[] = new Twig_SimpleFunction('get_config_parameter', function ($sParamName) {
			$oConfig = MetaModel::GetConfig();

			return $oConfig->Get($sParamName);
		});

		/**
		 * Function to get a module setting
		 * Usage in twig: {{ get_module_setting(<MODULE_CODE>, <PROPERTY_CODE> [, <DEFAULT_VALUE>]) }}
		 *
		 * @uses Config::GetModuleSetting()
		 * @since 3.0.0
		 */
		$aFunctions[] = new Twig_SimpleFunction('get_module_setting',
			function (string $sModuleCode, string $sPropertyCode, $defaultValue = null) {
				$oConfig = MetaModel::GetConfig();

				return $oConfig->GetModuleSetting($sModuleCode, $sPropertyCode, $defaultValue);
		});

		// Function to get iTop's app root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/)
		// Usage in twig: {{ get_absolute_url_app_root() }}
		/** @since 3.0.0 */
		$aFunctions[] = new Twig_SimpleFunction('get_absolute_url_app_root', function () {
			return utils::GetAbsoluteUrlAppRoot();
		});

		// Function to get iTop's modules root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/env-zzz/)
		// Usage in twig: {{ get_absolute_url_modules_root() }}
		/** @since 3.0.0 */
		$aFunctions[] = new Twig_SimpleFunction('get_absolute_url_modules_root', function () {
			return utils::GetAbsoluteUrlModulesRoot();
		});

		// Function to render a UI block (HTML, inline CSS, inline JS) and its sub blocks directly in the TWIG
		// Usage in twig: {{ render_block(oBlock) }}
		/** @since 3.0.0 */
		$aFunctions[] = new Twig_SimpleFunction('render_block',
			function(iUIBlock $oBlock, $aContextParams = []){
				$oRenderer = new BlockRenderer($oBlock, $aContextParams);
				return $oRenderer->RenderHtml();
			},
			['is_safe' => ['html']]
		);

		return $aFunctions;
	}
}

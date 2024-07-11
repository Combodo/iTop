<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;


use ApplicationMenu;
use AttributeDate;
use AttributeDateTime;
use AttributeText;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Renderer\BlockRenderer;
use Dict;
use Exception;
use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Source;
use Twig\TwigFilter;
use Twig\TwigFunction;
use utils;

class Extension
{
	/**
	 * Registers Twig extensions such as filters or functions.
	 * It allows us to access some stuff directly in twig.
	 *
	 * @param Environment $oTwigEnv
	 */
	public static function RegisterTwigExtensions(Environment &$oTwigEnv): void
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
	 * @return TwigFilter[] Custom TWIG filters used in iTop
	 * @since 3.1.0
	 */
	public static function GetFilters()
	{
		$aFilters = [];

		// Filter to translate a string via the Dict::S function
		// Usage in twig: {{ 'String:ToTranslate'|dict_s }}
		$aFilters[] = new TwigFilter('dict_s', function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
			return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
		});

		// Filter to format a string via the Dict::Format function
		// Usage in twig: {{ 'String:ToTranslate'|dict_format() }}
		$aFilters[] = new TwigFilter('dict_format', function ($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null) {
			return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
		});

		// Filter to format output
		// For example a DateTime is converted to user format
		// Usage in twig: {{ '2022-05-13 12:00:00'|date_format }}
		$aFilters[] = new TwigFilter('date_format', function ($sDate) {
			try {
				if (preg_match('@^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$@', trim($sDate))) {
					return AttributeDateTime::GetFormat()->Format($sDate);
				}
				if (preg_match('@^\d\d\d\d-\d\d-\d\d$@', trim($sDate))) {
					return AttributeDate::GetFormat()->Format($sDate);
				}
			}
			catch (Exception $e) {
			}

			return $sDate;
		});

		// Filter to format output
		// For example a file size is converted to human readable format
		// Usage in twig: {{ '4096'|size_format }}
		$aFilters[] = new TwigFilter('size_format', function ($sSize) {
			return utils::BytesToFriendlyFormat($sSize);
		});

		// Filter to enable base64 encode/decode
		// Usage in twig: {{ 'String to encode'|base64_encode }}
		$aFilters[] = new TwigFilter('base64_encode', 'base64_encode');
		$aFilters[] = new TwigFilter('base64_decode', 'base64_decode');

		// Filter to enable json decode  (encode already exists)
		// Usage in twig: {{ aSomeArray|json_decode }}
		$aFilters[] = new TwigFilter('json_decode', function ($sJsonString, $bAssoc = false) {
			return json_decode($sJsonString, $bAssoc);
		});

		/**
		 * Filter to sanitize a text
		 * Usage in twig: {{ 'variable_name:to-sanitize'|sanitize(constant('utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME')) }}
		 *
		 * @uses \utils::Sanitize()
		 * @since 3.0.0
		 */
		$aFilters[] = new TwigFilter('sanitize', function (string $sString, string $sFilter) {
			return utils::Sanitize($sString, '', $sFilter);
		});

		/**
		 * Filter to transform the wiki syntax ONLY into HTML.
		 *
		 * @uses \AttributeText::RenderWikiHtml()
		 * @since 3.0.0
		 */
		$aFilters[] = new TwigFilter('render_wiki_to_html', function ($sString) {
			return AttributeText::RenderWikiHtml($sString, true /* Important, otherwise hyperlinks will be tranformed as well */);
		});

		// Filter to add a parameter at the end of the URL to force cache invalidation after an upgrade.
		// Previously we put the iTop version but now it's the last setup/toolkit timestamp to avoid cache issues when building several times the same version during tests
		//
		// Note: This could be renamed "add_cache_buster" instead.
		$aFilters[] = new TwigFilter('add_itop_version', function ($sUrl) {
			$sUrl = utils::AddParameterToUrl($sUrl, 't', utils::GetCacheBusterTimestamp());

			return $sUrl;
		});

		// Filter to add a module's version to an url
		$aFilters[] = new TwigFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);
			$sUrl = utils::AddParameterToUrl($sUrl, 'moduleversion', $sModuleVersion);

			return $sUrl;
		});

		// var_export can be used for example to transform a PHP boolean to 'true' or 'false' strings
		// @see https://www.php.net/manual/fr/function.var-export.php
		$aFilters[] = new TwigFilter('var_export', 'var_export');

		// @since 3.1.0 N°4867 "Twig content not allowed" error when use the extkey widget search icon in the user portal
		// Overwrite native twig filter: disable use of 'system' filter
		$aFilters[] = new TwigFilter('filter', function (Environment $oTwigEnv, $array, $arrow) {
			if ($arrow == 'system') {
				return json_encode($array);
			}

			return twig_array_filter($oTwigEnv, $array, $arrow);
		}, ['needs_environment' => true]);

		return $aFilters;
	}

	/**
	 * @used-by \Combodo\iTop\Portal\Twig\AppExtension
	 * @return \TwigFunction[] Custom TWIG function used in iTop
	 * @since 3.1.0
	 */
	public static function GetFunctions()
	{
		$aFunctions = [];

		// Function to check our current environment
		// Usage in twig: {% if is_development_environment() %}
		$aFunctions[] = new TwigFunction('is_development_environment', function () {
			return utils::IsDevelopmentEnvironment();
		});

		// Function to get iTop's app root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/)
		// Usage in twig: {{ get_absolute_url_app_root() }}
		/** @since 3.0.0 */
		$aFunctions[] = new TwigFunction('get_absolute_url_app_root', function () {
			return utils::GetAbsoluteUrlAppRoot();
		});

		// Function to get iTop's modules root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/env-zzz/)
		// Usage in twig: {{ get_absolute_url_modules_root() }}
		/** @since 3.0.0 */
		$aFunctions[] = new TwigFunction('get_absolute_url_modules_root', function () {
			return utils::GetAbsoluteUrlModulesRoot();
		});

		// Function to check if current user can access to the given menu
		// Usage in twig: {% if is_backoffice_menu_enabled('DataModelMenu') %}
		/** @since 3.2.0 */
		$aFunctions[] = new TwigFunction('is_backoffice_menu_enabled', function ($sMenuId) {
			return ApplicationMenu::IsMenuIdEnabled($sMenuId);
		});

		// Function to render a UI block (HTML, inline CSS, inline JS) and its sub blocks directly in the TWIG
		// Usage in twig: {{ render_block(oBlock) }}
		/** @since 3.0.0 */
		$aFunctions[] = new TwigFunction('render_block',
			function (iUIBlock $oBlock, $aContextParams = []) {
				$oRenderer = new BlockRenderer($oBlock, $aContextParams);

				return $oRenderer->RenderHtml();
			},
			['is_safe' => ['html']]
		);


		/** @since 3.2.0 */
		$aFunctions[] = new TwigFunction('source_abs', function (Environment $oEnv, $sUrlAbsName) {
			// Extract the source path from the absolute url and replace it with approot
			$sAppRootAbsName = str_replace(utils::GetAbsoluteUrlAppRoot(), APPROOT, $sUrlAbsName);
			$oLoader = $oEnv->getLoader();
			// Check if the file is in any of the twig paths
			if($oLoader instanceof  FilesystemLoader) {
				$aPaths = $oLoader->getPaths();
				foreach ($aPaths as $sPath) {
					$sTwigPathRelativeName = substr($sAppRootAbsName, strlen($sPath) + 1);
					// If we find our path in the absolute url and the file actually exist, return it
					if (str_contains($sAppRootAbsName, $sPath) && $oLoader->exists($sTwigPathRelativeName)) {
						return $oLoader->getSourceContext($sTwigPathRelativeName)->getCode();
					}
				}
			}
			// Otherwise return empty content
			$oEmptySource = new Source('', $sUrlAbsName, '');
			return $oEmptySource->getCode();
		}, 
		['needs_environment' => true,
		 'is_safe' => ['all']]
		);

		return $aFunctions;
	}
}

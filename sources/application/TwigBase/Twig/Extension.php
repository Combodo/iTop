<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;


use AttributeDateTime;
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
	public static function RegisterTwigExtensions(Twig_Environment &$oTwigEnv)
	{
		// Filter to translate a string via the Dict::S function
		// Usage in twig: {{ 'String:ToTranslate'|dict_s }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_s',
				function ($sStringCode, $sDefault = null, $bUserLanguageOnly = false) {
					return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
				})
		);

		// Filter to format a string via the Dict::Format function
		// Usage in twig: {{ 'String:ToTranslate'|dict_format() }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('dict_format',
				function ($sStringCode, $sParam01 = null, $sParam02 = null, $sParam03 = null, $sParam04 = null) {
					return Dict::Format($sStringCode, $sParam01, $sParam02, $sParam03, $sParam04);
				})
		);

		// Filter to format output
		// example a DateTime is converted to user format
		// Usage in twig: {{ 'String:ToFormat'|output_format }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('date_format',
				function ($sDate) {
					try
					{
						if (preg_match('@^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$@', trim($sDate)))
						{
							return AttributeDateTime::GetFormat()->Format($sDate);
						}
					}
					catch (Exception $e)
					{
					}

					return $sDate;
				})
		);


		// Filter to format output
		// example a DateTime is converted to user format
		// Usage in twig: {{ 'String:ToFormat'|output_format }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('size_format',
				function ($sSize) {
					return utils::BytesToFriendlyFormat($sSize);
				})
		);

		// Filter to enable base64 encode/decode
		// Usage in twig: {{ 'String to encode'|base64_encode }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('base64_encode', 'base64_encode'));
		$oTwigEnv->addFilter(new Twig_SimpleFilter('base64_decode', 'base64_decode'));

		// Filter to enable json decode  (encode already exists)
		// Usage in twig: {{ aSomeArray|json_decode }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('json_decode', function ($sJsonString, $bAssoc = false) {
				return json_decode($sJsonString, $bAssoc);
			})
		);

		// Filter to sanitize an XML / HTML identifier
		// Usage in twig: {{ 'identifier:to-sanitize'|sanitize_identifier }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('sanitize_identifier', function ($sString) {
				return utils::Sanitize($sString, '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
			})
		);

		// Filter to add a parameter at the end of the URL to force cache invalidation after an upgrade.
		// Previously we put the iTop version but now it's the last setup/toolkit timestamp to avoid cache issues when building several times the same version during tests
		//
		// Note: This could be rename "add_cache_buster" instead.
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_itop_version', function ($sUrl) {
			if (strpos($sUrl, '?') === false)
			{
				$sUrl = $sUrl."?t=".utils::GetCacheBusterTimestamp();
			}
			else
			{
				$sUrl = $sUrl."&t=".utils::GetCacheBusterTimestamp();
			}

			return $sUrl;
		}));

		// Filter to add a module's version to an url
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);

			if (strpos($sUrl, '?') === false) {
				$sUrl = $sUrl."?moduleversion=".$sModuleVersion;
			} else {
				$sUrl = $sUrl."&moduleversion=".$sModuleVersion;
			}

			return $sUrl;
		}));

		// Filter to sanitize a string (escape ')
		// Usage in twig: {{ 'string'|escape_quotes }}
		$oTwigEnv->addFilter(new Twig_SimpleFilter('escape_for_js_string', function ($sString) {
				return str_replace(["'", "\n"], ["\\'", " "], $sString);
			})
		);


		// Function to check our current environment
		// Usage in twig:   {% if is_development_environment() %}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('is_development_environment', function () {
			return utils::IsDevelopmentEnvironment();
		}));

		// Function to get configuration parameter
		// Usage in twig: {{ get_config_parameter('foo') }}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_config_parameter', function ($sParamName) {
			$oConfig = MetaModel::GetConfig();

			return $oConfig->Get($sParamName);
		}));

		// Function to get iTop's app root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/)
		// Usage in twig: {{ get_absolute_url_app_root() }}
		/** @since 3.0.0 */
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_absolute_url_app_root', function () {
			return utils::GetAbsoluteUrlAppRoot();
		}));

		// Function to get iTop's modules root absolute URL (eg. https://aaa.bbb.ccc/xxx/yyy/env-zzz/)
		// Usage in twig: {{ get_absolute_url_modules_root() }}
		/** @since 3.0.0 */
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_absolute_url_modules_root', function () {
			return utils::GetAbsoluteUrlModulesRoot();
		}));

		// Function to render a UI block (HTML, inline CSS, inline JS) and its sub blocks directly in the TWIG
		// Usage in twig: {{ render_block(oBlock) }}
		/** @since 3.0.0 */
		$oTwigEnv->addFunction(new Twig_SimpleFunction('render_block', function(iUIBlock $oBlock, $aContextParams = []){
			return BlockRenderer::RenderBlockTemplates($oBlock, $aContextParams);
		}, ['is_safe' => ['html']]));
	}

}

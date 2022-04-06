<?php

namespace Combodo\iTop;

use AttributeDate;
use AttributeDateTime;
use Dict;
use Exception;
use MetaModel;
use Twig_Environment;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use utils;

/**
 * Class TwigExtension
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop
 * @deprecated 3.1.0 N°4034
 */
class TwigExtension
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
						if (preg_match('@^\d\d\d\d-\d\d-\d\d$@', trim($sDate)))
						{
							return AttributeDate::GetFormat()->Format($sDate);
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

		// Filter to add itopversion to an url
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_itop_version', function ($sUrl) {
			$sUrl = utils::AddParameterToUrl($sUrl, 'itopversion', ITOP_VERSION);
			return $sUrl;
		}));

		// Filter to add a module's version to an url
		$oTwigEnv->addFilter(new Twig_SimpleFilter('add_module_version', function ($sUrl, $sModuleName) {
			$sModuleVersion = utils::GetCompiledModuleVersion($sModuleName);
			$sUrl = utils::AddParameterToUrl($sUrl, 'moduleversion', $sModuleVersion);

			return $sUrl;
		}));

		// Function to check our current environment
		// Usage in twig:   {% if is_development_environment() %}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('is_development_environment', function()
		{
			return utils::IsDevelopmentEnvironment();
		}));

		// Function to get configuration parameter
		// Usage in twig: {{ get_config_parameter('foo') }}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_config_parameter', function($sParamName)
		{
			$oConfig = MetaModel::GetConfig();
			return $oConfig->Get($sParamName);
		}));

		// Function to get a module setting
		// Usage in twig: {{ get_module_setting(<MODULE_CODE>, <PROPERTY_CODE> [, <DEFAULT_VALUE>]) }}
		// since 3.0.0, but see N°4034 for upcoming evolutions in the 3.1
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_module_setting', function (string $sModuleCode, string $sPropertyCode, $defaultValue = null) {
			$oConfig = MetaModel::GetConfig();
			return $oConfig->GetModuleSetting($sModuleCode, $sPropertyCode, $defaultValue);
		}));

		// Function to get the URL of a static page in a module
		// Usage in twig: {{ get_static_page_module_url('itop-my-module', 'path-to-my-page') }}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_static_page_module_url', function($sModuleName, $sPage)
		{
			return utils::GetAbsoluteUrlModulesRoot().$sModuleName.'/'.$sPage;
		}));

		// Function to get the URL of a php page in a module
		// Usage in twig: {{ get_page_module_url('itop-my-module', 'path-to-my-my-page.php') }}
		$oTwigEnv->addFunction(new Twig_SimpleFunction('get_page_module_url', function($sModuleName, $sPage)
		{
			return utils::GetAbsoluteUrlModulePage($sModuleName, $sPage);
		}));
	}

}

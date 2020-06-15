<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;

use IssueLog;
use Twig\Environment;
use Twig_Environment;
use Twig_Error;
use Twig_Loader_Filesystem;
use utils;
use WebPage;


class TwigHelper
{
	public static function GetTwigEnvironment($sViewPath)
	{
		$oLoader = new Twig_Loader_Filesystem($sViewPath);
		$oTwig = new Twig_Environment($oLoader);
		Extension::RegisterTwigExtensions($oTwig);
		$sLocalPath = utils::LocalPath($sViewPath);
		$sLocalPath = str_replace('env-'.utils::GetCurrentEnvironment(), 'twig', $sLocalPath);
		$sCachePath = utils::GetCachePath().$sLocalPath;
		$oTwig->setCache($sCachePath);

		return $oTwig;
	}

	/**
	 * Display the twig page based on the name or the operation onto the page specified with SetPage().
	 * Use this method if you have to insert HTML into an existing page.
	 *
	 * @param \WebPage $oPage
	 * @param string $sViewPath Absolute path of the templates folder
	 * @param string $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 * @param array $aParams Params used by the twig template
	 * @param string $sDefaultType default type of the template ('html', 'xml', ...)
	 *
	 * @throws \Exception
	 * @api
	 */
	public static function RenderIntoPage(WebPage $oPage, $sViewPath, $sTemplateName, $aParams = array(), $sDefaultType = 'html')
	{
		$oTwig = self::GetTwigEnvironment($sViewPath);
		$oPage->add(self::RenderTemplate($oTwig, $aParams, $sTemplateName, $sDefaultType));
		$oPage->add_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'js'));
		$oPage->add_ready_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'ready.js'));
	}

	/**
	 * @param \Twig\Environment $oTwig
	 * @param $aParams
	 * @param $sName
	 * @param $sTemplateFileExtension
	 *
	 * @return string
	 */
	private static function RenderTemplate(Environment $oTwig, $aParams, $sName, $sTemplateFileExtension)
	{
		try
		{
			return $oTwig->render($sName.'.'.$sTemplateFileExtension.'.twig', $aParams);
		}
		catch (Twig_Error $e)
		{
			if (!utils::StartsWith($e->getMessage(), 'Unable to find template'))
			{
				IssueLog::Error($e->getMessage());
			}
			else
			{
				IssueLog::Debug($e->getMessage());
			}
		}

		return '';
	}
}

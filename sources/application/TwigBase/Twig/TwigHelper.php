<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;

use Twig\Environment;
use Twig_Environment;
use Twig_Loader_Filesystem;
use WebPage;


class TwigHelper
{
	public static function GetTwigEnvironment($sViewPath)
	{
		$oLoader = new Twig_Loader_Filesystem($sViewPath);
		$oTwig = new Twig_Environment($oLoader);
		Extension::RegisterTwigExtensions($oTwig);

		return $oTwig;
	}

	/**
	 * Display the twig page based on the name or the operation onto the page specified with SetPage().
	 * Use this method if you have to insert HTML into an existing page.
	 *
	 * @api
	 *
	 * @param array $aParams Params used by the twig template
	 * @param string $sTemplateName Name of the twig template, ie MyTemplate for MyTemplate.html.twig
	 *
	 * @throws \Exception
	 */
	public static function RenderIntoPage(WebPage $oPage, $sViewPath, $sTemplateName, $aParams = array())
	{
		$oTwig = self::GetTwigEnvironment($sViewPath);
		$oPage->add(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'html'));
		$oPage->add_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'js'));
		$oPage->add_ready_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'ready.js'));
	}

	/**
	 * @param $aParams
	 * @param $sName
	 * @param $sTemplateFileExtension
	 *
	 * @return string
	 * @throws \Exception
	 */
	private static function RenderTemplate(Environment $oTwig, $aParams, $sName, $sTemplateFileExtension)
	{
		try
		{
			return $oTwig->render($sName.'.'.$sTemplateFileExtension.'.twig', $aParams);
		}
		catch (Twig_Error $e)
		{
			// Ignore errors
			if (!utils::StartsWith($e->getMessage(), 'Unable to find template'))
			{
				IssueLog::Error($e->getMessage());
			}
		}

		return '';
	}
}

<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;

use Combodo\iTop\Application\TwigBase\UI\UIBlockExtension;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Renderer\BlockRenderer;
use Dict;
use IssueLog;
use Twig\Environment;
use Twig\Error\Error;
use Twig_Environment;
use Twig_Loader_Filesystem;
use utils;
use WebPage;


/**
 * Class TwigHelper
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\TwigBase\Twig
 * @since 2.7.0
 */
class TwigHelper
{
	/**
	 * @var string ENUM_FILE_TYPE_HTML
	 * @since 3.0.0
	 */
	public const ENUM_FILE_TYPE_HTML = 'html';
	/**
	 * @var string ENUM_FILE_TYPE_JS
	 * @since 3.0.0
	 */
	public const ENUM_FILE_TYPE_JS = 'js';
	/**
	 * @var string ENUM_FILE_TYPE_CSS
	 * @since 3.0.0
	 */
	public const ENUM_FILE_TYPE_CSS = 'css';
	/**
	 * @var string ENUM_FILE_TYPE_SVG
	 * @since 3.0.0
	 */
	public const ENUM_FILE_TYPE_SVG = 'svg';

	/**
	 * @var string DEFAULT_FILE_TYPE
	 * @since 3.0.0
	 */
	public const DEFAULT_FILE_TYPE = self::ENUM_FILE_TYPE_HTML;

	/**
	 * Return a TWIG environment instance looking for templates under $sViewPath.
	 * This is not a singleton as we might want to use several instances with different base path.
	 *
	 * @param string $sViewPath
	 * @param array $aAdditionalPaths
	 *
	 * @return \Twig_Environment
	 * @throws \Twig\Error\LoaderError
	 */
	public static function GetTwigEnvironment($sViewPath, $aAdditionalPaths = array())
	{
		$oLoader = new Twig_Loader_Filesystem($sViewPath);
		foreach ($aAdditionalPaths as $sAdditionalPath) {
			$oLoader->addPath($sAdditionalPath);
		}

		$oTwig = new Twig_Environment($oLoader);
		Extension::RegisterTwigExtensions($oTwig);
		if (!utils::IsDevelopmentEnvironment()) {
			// Disable the cache in development environment
			$sLocalPath = utils::LocalPath($sViewPath);
			$sLocalPath = str_replace('env-'.utils::GetCurrentEnvironment(), 'twig', $sLocalPath);
			$sCachePath = utils::GetCachePath().'twig/'.$sLocalPath;
			$oTwig->setCache($sCachePath);
		}

		$oTwig->addExtension(new UIBlockExtension());

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
	public static function RenderIntoPage(WebPage $oPage, $sViewPath, $sTemplateName, $aParams = array(), $sDefaultType = self::DEFAULT_FILE_TYPE)
	{
		$oTwig = self::GetTwigEnvironment($sViewPath);
		$oPage->add(self::RenderTemplate($oTwig, $aParams, $sTemplateName, $sDefaultType));
		$oPage->add_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'js'));
		$oPage->add_ready_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'ready.js'));
	}

	/**
	 * @param \Twig\Environment $oTwig
	 * @param array $aParams
	 * @param string $sName
	 * @param string $sTemplateFileExtension
	 * @param bool $bLogMissingFile
	 *
	 * @return string
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 */
	public static function RenderTemplate(Environment $oTwig, array $aParams, string $sName, string $sTemplateFileExtension = self::DEFAULT_FILE_TYPE, bool $bLogMissingFile = true): string
	{
		try {
			return $oTwig->render($sName.'.'.$sTemplateFileExtension.'.twig', $aParams);
		} catch (Error $e) {
			$sPath = '';
			if ($e->getSourceContext()) {
				$sPath = utils::LocalPath($e->getSourceContext()->getPath()).' ('.$e->getLine().') - ';
			}
			$sMessage = $sPath.$e->getMessage();

			if (strpos($e->getMessage(), 'Unable to find template') === false) {
				IssueLog::Error($sMessage);

				return static::GenerateEndUserError(Dict::S('UI:Error:TemplateRendering'), $sMessage);
			}

			if ($bLogMissingFile) {
				IssueLog::Debug($sMessage);
			}
		}

		return '';
	}

	/**
	 * @param string $sTitle
	 * @param string $sMessage
	 *
	 * @return string error panel markup
	 *
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @since 3.0.0 N°3522 method creation
	 */
	protected static function GenerateEndUserError(string $sTitle, string $sMessage): string
	{
		$oAlert = AlertUIBlockFactory::MakeForFailure($sTitle, $sMessage)
			->SetIsClosable(false)
			->SetIsCollapsible(false); // not rendering JS so...

		return BlockRenderer::RenderBlockTemplates($oAlert);
	}
}

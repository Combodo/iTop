<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\TwigBase\Twig;

use Combodo\iTop\Application\TwigBase\UI\UIBlockExtension;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;
use CoreTemplateException;
use ExecutionKPI;
use IssueLog;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;
use utils;


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
	 * @var string Base path for the backoffice templates
	 * @since 3.0.0
	 */
	public const ENUM_TEMPLATES_BASE_PATH_BACKOFFICE = APPROOT.'templates/';

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
	 * @return Environment
	 * @throws \Twig\Error\LoaderError
	 */
	public static function GetTwigEnvironment($sViewPath, $aAdditionalPaths = array())
	{
		$oLoader = new FilesystemLoader($sViewPath);
		foreach ($aAdditionalPaths as $sAdditionalPath) {
			$oLoader->addPath($sAdditionalPath);
		}

		$oTwig = new Environment($oLoader);
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
	 * @param WebPage $oPage
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
		$oTwig->addGlobal('UIBlockParent', [$oPage]);
		$oTwig->addGlobal('oPage', $oPage);
		$oPage->add(self::RenderTemplate($oTwig, $aParams, $sTemplateName, $sDefaultType));
		$oPage->add_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'js'));
		$oPage->add_ready_script(self::RenderTemplate($oTwig, $aParams, $sTemplateName, 'ready.js'));
	}

	/**
	 * Render the TWIG template directly in $oBlock
	 *
	 * @param WebPage $oPage
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oBlock
	 * @param string $sViewPath
	 * @param string $sTemplateName
	 * @param array $aParams
	 * @param string $sDefaultType
	 *
	 * @throws \CoreTemplateException
	 * @throws \Twig\Error\LoaderError
	 * @since 3.0.0
	 */
	public static function RenderIntoBlock(WebPage $oPage, UIBlock $oBlock, $sViewPath, $sTemplateName, $aParams = array(), $sDefaultType = self::DEFAULT_FILE_TYPE)
	{
		$oTwig = self::GetTwigEnvironment($sViewPath);
		$oTwig->addGlobal('UIBlockParent', [$oBlock]);
		$oTwig->addGlobal('oPage', $oPage);
		$oBlock->AddSubBlock(new Html(self::RenderTemplate($oTwig, $aParams, $sTemplateName, $sDefaultType)));
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
	 * @throws \CoreTemplateException
	 */
	public static function RenderTemplate(Environment $oTwig, array $aParams, string $sName, string $sTemplateFileExtension = self::DEFAULT_FILE_TYPE, bool $bLogMissingFile = true): string
	{
		try {
			$oKPI = new ExecutionKPI();
			$sFileName = $sName.'.'.$sTemplateFileExtension.'.twig';
			$sResult = $oTwig->render($sFileName, $aParams);
			$oKPI->ComputeStats('Render TWIG', $sFileName);

			return $sResult;
		}
		catch (Error $oTwigException) {
			$oTwigPreviousException = $oTwigException->getPrevious();
			if (!is_null(($oTwigPreviousException)) && ($oTwigPreviousException instanceof CoreTemplateException)) {
				// handles recursive calls : if we're here, an exception was already raised in a child template !
				throw $oTwigPreviousException;
			}

			$sPath = '';
			if ($oTwigException->getSourceContext()) {
				$sPath = utils::LocalPath($oTwigException->getSourceContext()->getPath()).' ('.$oTwigException->getLine().') - ';
			}

			if (strpos($oTwigException->getMessage(), 'Unable to find template') === false) {
				if (utils::IsXmlHttpRequest()) {
					// Ajax : just return the error message as part of the DOM
					$oAlert = AlertUIBlockFactory::MakeForFailure($sPath, $oTwigException->getMessage())
						->SetIsClosable(false)
						->SetIsCollapsible(false); // not rendering JS so...

					IssueLog::Error('Error occurred on TWIG rendering', null, [
						'twig_path' => $sPath,
						'twig_exception_message' => $oTwigException->getMessage(),
					]);

					return BlockRenderer::RenderBlockTemplates($oAlert);
				} else {
					// this will trigger error page, and will log to error.log !
					throw new CoreTemplateException($oTwigException, $sPath);
				}
			}

			if ($bLogMissingFile) {
				$sLogMessageMissingFile = "Twig : missing file '$sPath' : ".$oTwigException->getMessage();
				IssueLog::Debug($sLogMessageMissingFile);
			}
		}

		return '';
	}
}

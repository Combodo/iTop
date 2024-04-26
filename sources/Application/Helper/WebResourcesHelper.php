<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\RenderingOutput;
use Exception;
use utils;

/**
 * Class WebResourcesHelper
 *
 * This class aims at easing the import of web resources (external files, snippets) when necessary (opposite of imported them on all pages)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\Helper
 * @since 3.0.0 N°3685
 */
class WebResourcesHelper
{
	//---------------------------------
	// Fonts
	//---------------------------------

	/**
	 * Preload necessary fonts to display them as soon as possible when CSS rules are interpreted
	 *
	 * @return string[]
	 *
	 * @throws \Exception
	 */
	public static function GetPreloadedFonts(): array
	{
		return [
			['font' => utils::GetAbsoluteUrlAppRoot().'css/font-combodo/combodo-webfont.woff2?v=2.1', 'type' => 'woff2'],
			['font' => utils::GetAbsoluteUrlAppRoot().'css/font-awesome/webfonts/fa-solid-900.woff2', 'type' => 'woff2'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-400-normal.woff', 'type' => 'woff'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-500-normal.woff', 'type' => 'woff'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-600-normal.woff', 'type' => 'woff'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-700-normal.woff', 'type' => 'woff'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-400-italic.woff', 'type' => 'woff'],
			['font' => utils::GetAbsoluteUrlAppRoot().'node_modules/@fontsource/raleway/files/raleway-all-500-italic.woff', 'type' => 'woff'],
		];
	}
	
	//---------------------------------
	// CKEditor
	//---------------------------------

	/**
	 * ConfigureCKEditorForWebPageComponent.
	 *
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 * @param string|null $sComponentId
	 * @param string $sComponentValue
	 * @param bool $bWithMentions
	 * @param array $aConfiguration
	 *
	 * @return void
	 */
	public static function ConfigureCKEditorForWebPageComponent(WebPage $oPage, string $sComponentId = null, string $sComponentValue = '', bool $bWithMentions = false, array $aConfiguration = []): void
	{
		// link CKEditor JS files
		foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
			try{
				$oPage->LinkScriptFromAppRoot($sFile);
			}
			catch(Exception $e){
				\ExceptionLog::LogException($e);
			}
		}

		// if an id is provided, we enable CKEditor on the element
		if($sComponentId !== null){

			// default configuration
			$aDefaultConfig = [];
			try{
				$aDefaultConfig = CKEditorHelper::GetCkeditorPref($bWithMentions, $sComponentValue);
				$aDefaultConfig = array_merge($aDefaultConfig, $aConfiguration);
			}
			catch(Exception $e){
				\ExceptionLog::LogException($e);
			}

			// add CKEditor initialization script
			$sConfigJS = json_encode($aDefaultConfig);
			$oPage->add_ready_script("CombodoCKEditorHandler.CreateInstance('#$sComponentId', $sConfigJS)");

			// mentions template
			if($bWithMentions){
				$oPage->add(self::GetMentionsTemplate($sComponentId));
			}
		}

	}

	/**
	 * ConfigureCKEditorForRenderingOutputComponent.
	 *
	 * @param \Combodo\iTop\Renderer\RenderingOutput $oOutput
	 * @param string|null $sComponentId
	 * @param string $sComponentValue
	 * @param bool $bWithMentions
	 * @param bool $bAddJSFiles
	 * @param array $aConfiguration
	 *
	 * @return void
	 */
	public static function ConfigureCKEditorForRenderingOutputComponent(RenderingOutput $oOutput, string $sComponentId = null, string $sComponentValue = '', bool $bWithMentions = false, bool $bAddJSFiles = true, array $aConfiguration = []): void
	{
		// link CKEditor JS files
		if($bAddJSFiles){
			foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
				try{
					$oOutput->AddJsFile($sFile);
				}
				catch(Exception $e){
					\ExceptionLog::LogException($e);
				}
			}
		}

		$aDefaultConfig = [];
		try{
			$aDefaultConfig = CKEditorHelper::GetCkeditorPref($bWithMentions, $sComponentValue);
			$aDefaultConfig = array_merge($aDefaultConfig, $aConfiguration);
		}
		catch(Exception $e){
			\ExceptionLog::LogException($e);
		}

		// add CKEditor initialization script
		$sConfigJS = json_encode($aDefaultConfig);
		$oOutput->AddJs("CombodoCKEditorHandler.CreateInstance('#$sComponentId', $sConfigJS)");

		// mentions template
		if($bWithMentions){
			$oOutput->add(self::GetMentionsTemplate($sComponentId));
		}
	}

	/**
	 * Add necessary files (JS) to be able to use CKEditor in the page
	 *
	 * @param WebPage $oPage
	 *
	 * @throws Exception
	 */
	public static function EnableCKEditorToWebPage(WebPage &$oPage): void
	{
		foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
			$oPage->LinkScriptFromAppRoot($sFile);
		}
	}

	/**
	 * GetMentionsTemplate.
	 *
	 * @param string $sComponentId
	 *
	 * @return string
	 */
	public static function GetMentionsTemplate(string $sComponentId): string
	{
		// twig environment
		$oTwig = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH);

		// mention template
		$sMentionsTemplate = $oTwig->render('application/object/set/option_renderer.html.twig');

		return <<<HTML
<template id="{$sComponentId}_items_template">
$sMentionsTemplate
</template>
HTML;
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for CKEditor
	 */
	public static function GetJSFilesRelPathsForCKEditor(): array
	{
		// all js file needed by ckeditor
		$aJSRelPaths = [
			'js/ckeditor/build/ckeditor.js',
			'js/highlight/highlight.js',
			'js/ckeditor.handler.js',
			'js/ckeditor.feeds.js'
		];

		// add CKEditor translations resource
		$sUserLanguage = \Dict::GetUserLanguage();
		$sLanguage = strtolower(explode(' ', $sUserLanguage)[0]);
		$sCountry = strtolower(explode(' ', $sUserLanguage)[1]);

		// add corresponding ckeditor language file
		$sLanguageFileRelPath = 'js/ckeditor/build/translations/' . $sLanguage . '-' . $sCountry . '.js';
		if(file_exists(APPROOT . $sLanguageFileRelPath)){
			$aJSRelPaths[] = $sLanguageFileRelPath;
		}
		else {
			$sLanguageFileRelPath = 'js/ckeditor/build/translations/' . $sLanguage . '.js';
			if(file_exists(APPROOT . $sLanguageFileRelPath)){
				$aJSRelPaths[] = $sLanguageFileRelPath;
			}
		}

		return $aJSRelPaths;
	}

	//---------------------------------
	// D3/C3.js
	//---------------------------------

	/**
	 * Add necessary files (JS/CSS) to be able to use d3/c3.js in the page
	 *
	 * @param WebPage $oPage
	 *
	 * @throws Exception
	 */
	public static function EnableC3JSToWebPage(WebPage &$oPage): void
	{
		foreach (static::GetCSSFilesRelPathsForC3JS() as $sFile) {
			$oPage->LinkStylesheetFromAppRoot($sFile);
		}

		foreach (static::GetJSFilesRelPathsForC3JS() as $sFile) {
			$oPage->LinkScriptFromAppRoot($sFile);
		}
	}

	/**
	 * @return string[] Relative URLs to the CSS files necessary for d3/c3.js
	 */
	public static function GetCSSFilesRelPathsForC3JS(): array
	{
		return [
			'node_modules/c3/c3.min.css',
		];
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for d3/c3.js
	 */
	public static function GetJSFilesRelPathsForC3JS(): array
	{
		return [
			'node_modules/d3/d3.min.js',
			'node_modules/c3/c3.min.js',
		];
	}

	//---------------------------------
	// SimpleGraph
	//---------------------------------

	/**
	 * Add necessary files (JS/CSS) to be able to use simple_graph in the page
	 *
	 * @param WebPage $oPage
	 *
	 * @throws Exception
	 */
	public static function EnableSimpleGraphInWebPage(WebPage &$oPage): void
	{
		$oPage->LinkScriptFromAppRoot('js/raphael-min.js');
		$oPage->LinkScriptFromAppRoot('js/fraphael.js');
		$oPage->LinkStylesheetFromAppRoot('node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.css');
		$oPage->LinkScriptFromAppRoot('node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.js');
		$oPage->LinkScriptFromAppRoot('js/jquery.positionBy.js');
		$oPage->LinkScriptFromAppRoot('js/jquery.popupmenu.js');
		$oPage->LinkScriptFromAppRoot('js/jquery.mousewheel.js');
		$oPage->LinkScriptFromAppRoot('js/simple_graph.js');
	}
}
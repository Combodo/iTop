<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\WebPage\WebPage;
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
	 * Add necessary files (JS) to be able to use CKEditor in the page
	 *
	 * @param WebPage $oPage
	 *
	 * @throws Exception
	 */
	public static function EnableCKEditorToWebPage(WebPage &$oPage): void
	{
		//when ckeditor is loaded in ajax,  CKEDITOR_BASEPATH  is not well defined (this constant is used to load additional js)
		$oPage->add_script("if (! window.CKEDITOR_BASEPATH) { var CKEDITOR_BASEPATH = '".utils::GetAbsoluteUrlAppRoot()."node_modules/ckeditor5-itop-build/';}");
		foreach (CKEditorHelper::GetJSFilesRelPathsForCKEditor() as $sFile) {
			$oPage->LinkScriptFromAppRoot($sFile);
		}
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for CKEditor
	 */
	public static function GetJSFilesRelPathsForCKEditor(): array
	{
		return CKEditorHelper::GetJSFilesRelPathsForCKEditor();
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
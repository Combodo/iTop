<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use WebPage;
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
	// CKEditor
	//---------------------------------

	/**
	 * Add necessary files (JS) to be able to use CKEditor in the page
	 *
	 * @param \WebPage $oPage
	 *
	 * @throws \Exception
	 */
	public static function EnableCKEditorToWebPage(WebPage &$oPage): void
	{
		foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().$sFile);
		}
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for CKEditor
	 */
	public static function GetJSFilesRelPathsForCKEditor(): array
	{
		return [
			'js/ckeditor/ckeditor.js',
			'js/ckeditor/adapters/jquery.js',
			'js/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js',
			'js/ckeditor.on-init.js',
		];
	}

	//---------------------------------
	// D3/C3.js
	//---------------------------------

	/**
	 * Add necessary files (JS/CSS) to be able to use d3/c3.js in the page
	 *
	 * @param \WebPage $oPage
	 *
	 * @throws \Exception
	 */
	public static function EnableC3JSToWebPage(WebPage &$oPage): void
	{
		foreach (static::GetCSSFilesRelPathsForC3JS() as $sFile) {
			$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().$sFile);
		}

		foreach (static::GetJSFilesRelPathsForC3JS() as $sFile) {
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().$sFile);
		}
	}

	/**
	 * @return string[] Relative URLs to the CSS files necessary for d3/c3.js
	 */
	public static function GetCSSFilesRelPathsForC3JS(): array
	{
		return [
			'css/c3.min.css',
		];
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for d3/c3.js
	 */
	public static function GetJSFilesRelPathsForC3JS(): array
	{
		return [
			'js/d3.js',
			'js/c3.js',
		];
	}

	//---------------------------------
	// SimpleGraph
	//---------------------------------

	/**
	 * Add necessary files (JS/CSS) to be able to use simple_graph in the page
	 *
	 * @param \WebPage $oPage
	 *
	 * @throws \Exception
	 */
	public static function EnableSimpleGraphInWebPage(WebPage &$oPage): void
	{
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/raphael-min.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/fraphael.js');
		$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/jquery.contextMenu.css');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.contextMenu.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.positionBy.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.popupmenu.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.mousewheel.js');
		$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/simple_graph.js');
	}
}
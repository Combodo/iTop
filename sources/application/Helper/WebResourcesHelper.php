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
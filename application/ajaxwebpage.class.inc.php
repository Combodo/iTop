<?php
/**
 * @deprecated  3.0.0 will be removed in 3.1.0 - moved to sources/Application/WebPage/AjaxPage.php, now loadable using autoloader
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 */

// cannot notify depreciation for now as this is still load in autoloader
//DeprecatedCallsLog::NotifyDeprecatedFile('moved to sources/Application/WebPage/AjaxPage.php, now loadable using autoloader');
use Combodo\iTop\Application\WebPage\AjaxPage;

/**
 * Class ajax_page
 *
 * @deprecated 3.0.0 will be removed in 3.1.0 - moved to AjaxPage
 */
class ajax_page extends AjaxPage
{
	function __construct($s_title)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('ajax_page is deprecated. Please use AjaxPage instead');
		parent::__construct($s_title);
	}
}


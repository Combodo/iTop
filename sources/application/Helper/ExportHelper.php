<?php

namespace Combodo\iTop\Application\Helper;
use Dict;
use utils;

/**
 * Class
 * ExportHelper
 *
 * @internal
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.7.9 3.0.4 3.1.1 3.2.0
 * @package Combodo\iTop\Application\Helper
 */
class ExportHelper
{
	public static function GetAlertForExcelMaliciousInjection()
	{
        $sWikiUrl =  'https://www.itophub.io/wiki/page?id='.utils::GetItopVersionWikiSyntax().'%3Auser%3Alists#excel_export';
		return '<div class="message_warning">' . Dict::Format('UI:Bulk:Export:MaliciousInjection:Alert:Message', $sWikiUrl) . '</div>';
	}
}
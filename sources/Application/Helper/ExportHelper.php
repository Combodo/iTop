<?php

namespace Combodo\iTop\Application\Helper;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
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
		$oAlert = AlertUIBlockFactory::MakeForWarning(Dict::S('UI:Bulk:Export:MaliciousInjection:Alert:Title'), Dict::Format('UI:Bulk:Export:MaliciousInjection:Alert:Message', $sWikiUrl), 'ibo-excel-malicious-injection-alert');
		$oAlert->EnableSaveCollapsibleState(true)
			->SetIsClosable(false);
		return $oAlert;
	}
}
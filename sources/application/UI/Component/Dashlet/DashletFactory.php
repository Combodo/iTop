<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Dashlet;


class DashletFactory
{
	public static function MakeForDashletBadge(string $sClassIconUrl, string $sHyperlink, string $iCount, string $sClassLabel, string $sCreateActionUrl = '', string $sCreateActionLabel = '')
	{
		return new DashletBadge($sClassIconUrl, $sHyperlink, $iCount, $sClassLabel, $sCreateActionUrl, $sCreateActionLabel);
	}

	public static function MakeForDashletHeaderStatic(string $sTitle, string $sIconUrl)
	{
		return new DashletHeaderStatic(null, $sTitle, $sIconUrl);
	}

	public static function MakeForDashletText(string $sId, string $sText)
	{
		return new DashletHeaderStatic($sId, '', '', $sText);
	}

}
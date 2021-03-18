<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


class DashletFactory
{
	public static function MakeForDashletBadge(
		string $sClassIconUrl, string $sHyperlink, string $iCount, string $sClassLabel, ?string $sCreateActionUrl = '',
		?string $sCreateActionLabel = '', array $aRefreshParams = []
	)
	{
		return new DashletBadge($sClassIconUrl, $sHyperlink, $iCount, $sClassLabel, $sCreateActionUrl, $sCreateActionLabel, $aRefreshParams);
	}

	public static function MakeForDashletHeaderStatic(string $sTitle, string $sIconUrl)
	{
		return new DashletHeaderStatic(null, $sTitle, $sIconUrl);
	}

	public static function MakeForDashletPlainText(string $sText, string $sId = null): DashletPlainText
	{
		return new DashletPlainText($sText, $sId);
	}

}
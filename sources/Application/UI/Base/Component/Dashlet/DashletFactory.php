<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


/**
 * Class DashletFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Dashlet
 * @since 3.0.0
 * @internal
 */
class DashletFactory
{
	public static function MakeForDashletBadge(string $sClassIconUrl, string $sHyperlink, string $iCount, string $sClassLabel, ?string $sCreateActionUrl = '', ?string $sCreateActionLabel = '', array $aRefreshParams = []): DashletBadge
	{
		return new DashletBadge($sClassIconUrl, $sHyperlink, $iCount, $sClassLabel, $sCreateActionUrl, $sCreateActionLabel, $aRefreshParams);
	}

	public static function MakeForDashletHeaderStatic(string $sTitle, string $sIconUrl, string $sId = null): DashletHeaderStatic
	{
		return new DashletHeaderStatic($sTitle, $sIconUrl, $sId);
	}

	public static function MakeForDashletPlainText(string $sText, string $sId = null): DashletPlainText
	{
		return new DashletPlainText($sText, $sId);
	}

}
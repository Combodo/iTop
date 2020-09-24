<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Title;


class TitleFactory
{

	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title($sTitle, 1, $sId);
	}

	public static function MakeForObjectDetails(string $sClassName, string $sObjectName, string $sIconHtml, ?string $sId = null)
	{
		$oTitle = new TitleForObjectDetails($sClassName, $sObjectName, $sId);
		$oTitle->SetIcon($sIconHtml);

		return $oTitle;
	}
}
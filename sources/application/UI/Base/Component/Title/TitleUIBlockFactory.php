<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Title;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class TitleUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UITitle';
	public const UI_BLOCK_CLASS_NAME = Title::class;

	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title($sTitle, 1, $sId);
	}

	public static function MakeForPageWithIcon(
		string $sTitle, string $sIconUrl, string $sIconCoverMethod = Title::DEFAULT_ICON_COVER_METHOD, bool $bIsMedallion = true,
		?string $sId = null
	)
	{
		$oTitle = new Title($sTitle, 1, $sId);
		$oTitle->SetIcon($sIconUrl, $sIconCoverMethod, $bIsMedallion);

		return $oTitle;
	}

	public static function MakeNeutral(string $sTitle, int $iLevel = 1, ?string $sId = null)
	{
		return new Title($sTitle, $iLevel, $sId);
	}
}
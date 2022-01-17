<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Title;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Text\Text;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class TitleUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Title
 * @since 3.0.0
 * @internal
 */
class TitleUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITitle';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Title::class;

	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title(new Text($sTitle), 1, $sId);
	}

	public static function MakeForPageWithIcon(
		string $sTitle, string $sIconUrl, string $sIconCoverMethod = Title::DEFAULT_ICON_COVER_METHOD, bool $bIsMedallion = true,
		?string $sId = null
	)
	{
		$oTitle = new Title(new Text($sTitle), 1, $sId);
		$oTitle->SetIcon($sIconUrl, $sIconCoverMethod, $bIsMedallion);

		return $oTitle;
	}

	public static function MakeNeutral(string $sTitle, int $iLevel = 1, ?string $sId = null)
	{
		return new Title(new Text($sTitle), $iLevel, $sId);
	}

	public static function MakeStandard(UIBlock $oTitle, int $iLevel = 1, ?string $sId = null)
	{
		return new Title($oTitle, $iLevel, $sId);
	}
}
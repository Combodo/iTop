<?php

namespace Combodo\iTop\Application\UI\Base\Component\Badge;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class BadgeUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIBadge';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Badge::class;

	public static function MakeNeutral(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_NEUTRAL, $sTooltip, $sId);
	}
	public static function MakeGrey(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_BLUE_GREY, $sTooltip, $sId);
	}

	public static function MakeRed(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_RED, $sTooltip, $sId);
	}
	public static function MakeCyan(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_CYAN, $sTooltip, $sId);
	}

	public static function MakeGreen(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_GREEN, $sTooltip, $sId);
	}

	public static function MakeOrange(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_ORANGE, $sTooltip, $sId);
	}
}

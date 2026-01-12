<?php

namespace Combodo\iTop\Application\UI\Base\Component\Badge;
use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Dict;
use utils;

class BadgeUIBlockFactory  extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIBadge';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Badge::class;


	public static function MakeNeutral(string $sLabel, string $sTooltip = '', ?string $sId = null)
	{
		return new Badge($sLabel, Badge::ENUM_COLOR_SCHEME_NEUTRAL,$sTooltip, $sId);
	}
}
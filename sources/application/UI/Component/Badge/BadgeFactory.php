<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Badge;


use Combodo\iTop\Application\UI\Helper\UIHelper;

class BadgeFactory
{

	public static function MakeForState(string $sClass, string $sStateCode)
	{
		$sColor = UIHelper::GetColorFromStatus($sClass, $sStateCode);
		return new Badge($sColor);
	}

}
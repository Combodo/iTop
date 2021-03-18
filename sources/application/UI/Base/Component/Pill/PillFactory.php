<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Pill;


use Combodo\iTop\Application\UI\Helper\UIHelper;

/**
 * Class PillFactory
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Component\Pill
 */
class PillFactory
{
	/**
	 * @param string $sClass
	 * @param string $sStateCode
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Pill\Pill
	 */
	public static function MakeForState(string $sClass, string $sStateCode)
	{
		$sColor = UIHelper::GetColorFromStatus($sClass, $sStateCode);

		return new Pill($sColor);
	}

}
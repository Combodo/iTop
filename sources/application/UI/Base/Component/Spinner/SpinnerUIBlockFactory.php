<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Spinner;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class SpinnerUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UISpinner';
	public const UI_BLOCK_CLASS_NAME = Spinner::class;

	public static function MakeStandard(?string $sId = null)
	{
		return new Spinner($sId);
	}
}
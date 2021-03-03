<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class SelectOptionUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UISelectOption';
	public const UI_BLOCK_CLASS_NAME = SelectOption::class;

	public static function MakeForSelectOption(string $sValue, string $sLabel, bool $bSelected, ?string $sId = null): SelectOption
	{
		$oInput = new SelectOption($sId);

		$oInput->SetValue($sValue)
			->SetLabel($sLabel)
			->SetSelected($bSelected)
			->SetDisabled(false);

		return $oInput;
	}
}
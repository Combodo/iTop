<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class SelectOptionUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class SelectOptionUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UISelectOption';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = SelectOption::class;

	/**
	 * @api
	 * @param string $sValue
	 * @param string $sLabel
	 * @param bool $bSelected
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOption
	 */
	public static function MakeForSelectOption(string $sValue, string $sLabel, bool $bSelected, ?string $sId = null)
	{
		$oInput = new SelectOption($sId);

		$oInput->SetValue($sValue)
			->SetLabel($sLabel)
			->SetSelected($bSelected)
			->SetDisabled(false);

		return $oInput;
	}
}
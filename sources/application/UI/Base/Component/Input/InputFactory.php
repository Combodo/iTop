<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\Component\Field\Field;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\Select;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOption;

class InputFactory
{

	public static function MakeForHidden(string $sName, string $sValue, ?string $sId = null): Input
	{
		$oInput = new Input($sId);

		$oInput->SetType(Input::INPUT_HIDDEN)
			->SetName($sName)
			->SetValue($sValue);

		return $oInput;
	}

	/**
	 * @see Field component that is better adapter when dealing with a standard iTop form
	 *
	 * @param string $sLabel
	 * @param string $sInputName
	 * @param string|null $sInputValue
	 * @param string|null $sInputId
	 * @param string $sInputType
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\InputWithLabel
	 */
	public static function MakeForInputWithLabel(
		string $sLabel, string $sInputName, ?string $sInputValue = null,
		?string $sInputId = null, string $sInputType = 'type'
	): InputWithLabel
	{
		$oInput = new Input($sInputId);
		$oInput->SetType($sInputType);
		$oInput->SetValue($sInputValue);

		return static::MakeInputWithLabel($sInputName, $sLabel, $oInput, $sInputId);
	}

	/**
	 * If you need to have a real field with a label, you might use a {@link Field} component instead
	 *
	 * @param string $sName
	 * @param string $sLabel
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\InputWithLabel
	 */
	public static function MakeForSelectWithLabel(string $sName, string $sLabel, ?string $sId = null): InputWithLabel
	{
		$oInput = new Select($sId);

		return static::MakeInputWithLabel($sName, $sLabel, $oInput, $sId);
	}

	private static function MakeInputWithLabel(string $sName, string $sLabel, Input $oInput, ?string $sId = null)
	{
		$oInput->SetName($sName);

		if (is_null($sId)) {
			$sId = $oInput->GetId();
		}

		return new InputWithLabel($sLabel, $oInput, $sId);
	}

	public static function MakeForSelect(string $sName, ?string $sId = null): Select
	{
		$oInput = new Select($sId);
		$oInput->SetName($sName);

		return $oInput;
	}

	public static function MakeForSelectOption(string $sValue, string $sLabel, bool $bSelected, ?string $sId = null): SelectOption
	{
		$oInput = new SelectOption($sId);

		$oInput->SetValue($sValue)
			->SetLabel($sLabel)
			->SetSelected($bSelected);

		return $oInput;
	}

}
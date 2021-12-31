<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\Field;

/**
 * Class InputUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 * @since 3.0.0
 * @internal
 */
class InputUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIInput';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Input::class;

	public static function MakeForHidden(string $sName, string $sValue, ?string $sId = null)
	{
		$oInput = new Input($sId);

		$oInput->SetType(Input::INPUT_HIDDEN)
			->SetName($sName)
			->SetValue($sValue);

		return $oInput;
	}

	public static function MakeStandard(string $sType, string $sName, string $sValue, ?string $sId = null)
	{
		$oInput = new Input($sId);

		$oInput->SetType($sType)
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
	)
	{
		$oInput = new Input($sInputId);
		$oInput->SetType($sInputType);
		$oInput->SetValue($sInputValue);

		return static::MakeInputWithLabel($sInputName, $sLabel, $oInput, $sInputId);
	}

	private static function MakeInputWithLabel(string $sName, string $sLabel, Input $oInput, ?string $sId = null)
	{
		$oInput->SetName($sName);

		if (is_null($sId)) {
			$sId = $oInput->GetId();
		}

		return new InputWithLabel($sLabel, $oInput, $sId);
	}
}
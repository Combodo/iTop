<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\Field;

/**
 * Class InputUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class InputUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIInput';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Input::class;

	/**
	 * @api
	 * @param string $sName
	 * @param string $sValue
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Input
	 */
	public static function MakeForHidden(string $sName, string $sValue, ?string $sId = null)
	{
		$oInput = new Input($sId);

		$oInput->SetType(Input::INPUT_HIDDEN)
			->SetName($sName)
			->SetValue($sValue);

		return $oInput;
	}

	/**
	 * @api
	 * @param string $sType
	 * @param string $sName
	 * @param string $sValue
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Input
	 */
	public static function MakeStandard(string $sType, string $sName, string $sValue, ?string $sId = null)
	{
		$oInput = new Input($sId);

		$oInput->SetType($sType)
			->SetName($sName)
			->SetValue($sValue);

		return $oInput;
	}

	/**
	 * @api
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

	/**
	 * @api
	 * @param string $sName
	 * @param string $sLabel
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\Input $oInput
	 * @param string|null $sId
	 * @since 3.2.0 method is now public
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\InputWithLabel
	 */
	public static function MakeInputWithLabel(string $sName, string $sLabel, Input $oInput, ?string $sId = null)
	{
		$oInput->SetName($sName);

		if (is_null($sId)) {
			$sId = $oInput->GetId();
		}

		return new InputWithLabel($sLabel, $oInput, $sId);
	}
}
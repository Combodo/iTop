<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\Select;

class SelectUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UISelect';
	public const UI_BLOCK_CLASS_NAME = Select::class;

	/**
	 * @param string $sName
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\Select\Select
	 */
	public static function MakeForSelect(string $sName, ?string $sId = null): Select
	{
		$oInput = new Select($sId);
		$oInput->SetName($sName);

		return $oInput;
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
		$oInput->SetName($sName);

		if (is_null($sId)) {
			$sId = $oInput->GetId();
		}

		return new InputWithLabel($sLabel, $oInput, $sId);
	}

}
<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel\Dashlet;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Dict;

/**
 * A block to manage an aggregation function list
 *
 * @package Combodo\iTop\Forms\Block\DataModel\Dashlet
 * @since 3.3.0
 */
class AggregateFunctionFormBlock extends ChoiceFormBlock
{
	public const INPUT_CLASS_NAME = 'class';

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
	}

	/**
	 * @param \Combodo\iTop\Forms\Register\OptionsRegister $oOptionsRegister
	 *
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 * @throws \Combodo\iTop\Forms\Register\RegisterException
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		$sClass = strval($this->GetInputValue(self::INPUT_CLASS_NAME));
		$aFunctionAttributes = AttributeChoiceFormBlock::ListAttributeCodesByCategory($sClass, 'numeric');
		$aFunctions = $this->GetAllowedFunctions($aFunctionAttributes);

		$oOptionsRegister->SetOption('choices', $aFunctions);
	}

	/**
	 * @param array $aFunctionAttributes
	 *
	 * @return array
	 */
	protected function GetAllowedFunctions(array $aFunctionAttributes): array
	{
		$aFunctions = [];

		$aFunctions[Dict::S('UI:GroupBy:count')] = 'count';

		if (!empty($aFunctionAttributes)) {
			$aFunctions[Dict::S('UI:GroupBy:sum')] = 'sum';
			$aFunctions[Dict::S('UI:GroupBy:avg')] = 'avg';
			$aFunctions[Dict::S('UI:GroupBy:min')] = 'min';
			$aFunctions[Dict::S('UI:GroupBy:max')] = 'max';
		}

		return $aFunctions;
	}

}

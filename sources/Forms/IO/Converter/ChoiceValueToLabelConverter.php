<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;

/**
 * Convert a choice value to its label.
 *
 * @package Combodo\iTop\Forms\IO\Converter
 * @since 3.3.0
 */
class ChoiceValueToLabelConverter extends AbstractConverter
{
	private ChoiceFormBlock $oChoiceBlock;

	public function __construct(ChoiceFormBlock $oChoiceBlock)
	{
		$this->oChoiceBlock = $oChoiceBlock;
	}

	/** @inheritdoc */
	public function Convert(mixed $oData): ?StringIOFormat
	{
		if (is_null($oData) || is_array($oData)) {
			return null;
		}

		$aOptions = array_flip($this->oChoiceBlock->GetOption('choices'));
		if (!array_key_exists($oData, $aOptions) || is_null($aOptions[$oData])) {
			return null;
		}

		return new StringIOFormat($aOptions[$oData]);
	}
}

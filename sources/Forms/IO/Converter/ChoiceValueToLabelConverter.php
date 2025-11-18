<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\RawFormat;

/**
 *
 */
class ChoiceValueToLabelConverter extends AbstractConverter
{
	private ChoiceFormBlock $oChoiceBlock;

	public function __construct(ChoiceFormBlock $oChoiceBlock)
	{
		$this->oChoiceBlock = $oChoiceBlock;
	}

	/** @inheritdoc */
	public function Convert(mixed $oData): ?RawFormat
	{
		if (is_null($oData) || is_array($oData)) {
			return null;
		}

		$aOptions = array_flip($this->oChoiceBlock->GetOption('choices'));

		return new RawFormat($aOptions[$oData]);
	}
}

<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;

/**
 * OQL expression to class converter.
 */
class StringToBooleanConverter extends AbstractConverter
{
	/** @inheritdoc  */
	public function Convert(mixed $oData): ?BooleanIOFormat
	{
		return new BooleanIOFormat($oData);
	}
}

<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;

/**
 * String to attribute converter.
 */
class StringToAttributeConverter extends AbstractConverter
{
	/** @inheritdoc */
	public function Convert(mixed $oData): ?AttributeIOFormat
	{
		if ($oData === null) {
			return null;
		}

		return new AttributeIOFormat($oData);
	}
}
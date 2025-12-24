<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\IO\Format\IntegerIOFormat;

/**
 * Count elements in a collection.
 *
 * @package Combodo\iTop\Forms\IO\Converter
 * @since 3.3.0
 */
class CollectionToCountConverter extends AbstractConverter
{
	/** @inheritdoc */
	public function Convert(mixed $oData): ?IntegerIOFormat
	{
		if ($oData === null) {
			return null;
		}

		return new IntegerIOFormat(count($oData));
	}
}

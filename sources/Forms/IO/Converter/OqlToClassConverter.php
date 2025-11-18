<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use MetaModel;

/**
 * OQL expression to class converter.
 */
class OqlToClassConverter extends AbstractConverter
{
	/** @inheritdoc
	 * @throws \Combodo\iTop\Forms\IO\FormBlockIOException
	 */
	public function Convert(mixed $oData): ?ClassIOFormat
	{
		if ($oData === null) {
			return null;
		}

		// Extract OQL information
		preg_match('/SELECT\s+(\w+)/', $oData, $aMatches);

		// Selected class
		if (isset($aMatches[1])) {
			$sSelectedClass = $aMatches[1];
			if (!MetaModel::IsValidClass($sSelectedClass)) {
				throw new FormBlockIOException('Class '.json_encode($sSelectedClass).' not found');
			}

			return new ClassIOFormat($aMatches[1]);
		} else {
			throw new FormBlockIOException('Incorrect OQL sentence '.json_encode($oData));
		}

	}
}

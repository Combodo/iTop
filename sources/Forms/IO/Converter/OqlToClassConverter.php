<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

use Combodo\iTop\DependencyInjection\DIService;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\FormBlockIOException;

/**
 * OQL expression to class converter.
 */
class OqlToClassConverter extends AbstractConverter
{
	/** @inheritdoc
	 * @throws \Combodo\iTop\Forms\IO\FormBlockIOException
	 * @throws \Combodo\iTop\DependencyInjection\DIException
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
			/** @var \ModelReflection $oModelReflection */
			$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
			if (!$oModelReflection->IsValidClass($sSelectedClass)) {
				throw new FormBlockIOException('Class '.json_encode($sSelectedClass).' not found');
			}

			return new ClassIOFormat($aMatches[1]);
		} else {
			throw new FormBlockIOException('Incorrect OQL sentence '.json_encode($oData));
		}

	}
}

<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Converter;

use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;

/**
 * OQL expression to class converter.
 */
class OqlToClassName extends AbstractOutputConverter
{
	/** @inheritdoc  */
	public function Convert(mixed $oData): ?ClassIOFormat
	{
		if($oData === null)
			return null;
		// extract selected class
		preg_match('/SELECT\s+(\w+)/', $oData, $aMatches);
		return new ClassIOFormat($aMatches[1]) ?? null;
	}
}
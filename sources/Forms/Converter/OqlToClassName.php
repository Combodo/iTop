<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Converter;

class OqlToClassName extends AbstractConverter
{

	public function Convert(mixed $oData): ?string
	{
		if($oData === null)
			return null;
		// extract selected class
		preg_match('/SELECT\s+(\w+)/', $oData, $aMatches);
		return $aMatches[1] ?? null;
	}
}
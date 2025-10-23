<?php

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
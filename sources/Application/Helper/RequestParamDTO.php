<?php

namespace Combodo\iTop\Application\Helper;

use utils;

class RequestParamDTO
{
	public static function Create(string $sName, string $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_PARAMETER, bool $bIsMandatory = false, mixed $oDefaultValue = null): RequestParamDTO
	{
		return new RequestParamDTO($sName, $sSanitizationFilter, $bIsMandatory, $oDefaultValue);
	}

	public function __construct(public readonly string $sName, public readonly string $sSanitizationFilter, public readonly bool $bIsMandatory = false, public readonly mixed $oDefaultValue = null)
	{

	}
}

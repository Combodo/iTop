<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Http;

use utils;

/**
 * Class
 * RequestParamDTO
 *
 * @since 3.2.4, 3.3.1, 3.4.0
 * @package Combodo\iTop\Application\Http
 *
 */
class RequestParamDTO
{
	public static function Create(string $sName, bool $bIsPostParam, string $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_PARAMETER, bool $bIsMandatory = false, mixed $oDefaultValue = null): RequestParamDTO
	{
		return new RequestParamDTO($sName, $bIsPostParam, $sSanitizationFilter, $bIsMandatory, $oDefaultValue);
	}

	public static function Post(string $sName, string $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_PARAMETER, bool $bIsMandatory = false, mixed $oDefaultValue = null): RequestParamDTO
	{
		return new RequestParamDTO($sName, true, $sSanitizationFilter, $bIsMandatory, $oDefaultValue);
	}

	public static function Get(string $sName, string $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_PARAMETER, bool $bIsMandatory = false, mixed $oDefaultValue = null): RequestParamDTO
	{
		return new RequestParamDTO($sName, false, $sSanitizationFilter, $bIsMandatory, $oDefaultValue);
	}

	public function __construct(public readonly string $sName, public readonly bool $bIsPostParam, public readonly string $sSanitizationFilter, public readonly bool $bIsMandatory = false, public readonly mixed $oDefaultValue = null)
	{

	}
}

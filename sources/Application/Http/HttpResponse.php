<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Http;

use Dict;

/**
 * Class
 * HttpResponse
 *
 * @since 3.2.4, 3.3.1, 3.4.0
 * @package Combodo\iTop\Application\Http
 *
 */
class HttpResponse
{
	public static function SendJsonResponseAndExit(int $iHttpCode, array $aResponse): void
	{
		http_response_code($iHttpCode);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($aResponse);
		exit;
	}

	public static function SendTextPlainResponseAndExit(int $iHttpCode, string $sResponse): void
	{
		http_response_code($iHttpCode);
		header('Content-Type: text/plain; charset=utf-8');
		echo $sResponse;
		exit;
	}

	public static function SendTextPlainFatalResponseAndExit(): void
	{
		self::SendTextPlainResponseAndExit(500, Dict::S('UI:PageTitle:FatalError'));
	}

	public static function SendTextPlainBadRequestResponseAndExit(): void
	{
		self::SendTextPlainResponseAndExit(400, Dict::S('Error:HTTP:400'));
	}

	public static function SendTextPlainUnauthorizedResponseAndExit(): void
	{
		self::SendTextPlainResponseAndExit(401, Dict::S('Error:HTTP:401'));
	}

}

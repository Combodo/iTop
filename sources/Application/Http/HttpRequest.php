<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Http;

use IssueLog;
use LogChannels;
use SecurityException;
use utils;

/**
 * Class
 * HttpRequest
 *
 * @since 3.2.4, 3.3.1, 3.4.0
 * @package Combodo\iTop\Application\Http
 *
 */
class HttpRequest
{
	/**
	 * check if header contains X-Combodo-Ajax for POST request (CSRF protection for ajax calls)
	 * check must be performed after DoLoginEx to be logged in and to be able to check the token (based on the transaction id)
	 *
	 * @param array $aBypassMethods list of methods that can bypass the check (e.g. GET)
	 *
	 * @throws SecurityException
	 */
	public static function CheckAjaxEndpoint(string...$aBypassMethods): void
	{
		if (!isset($_SERVER['HTTP_X_COMBODO_AJAX']) && !in_array($_SERVER['REQUEST_METHOD'], $aBypassMethods)) {
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);
			if (!utils::IsTransactionValid($sTransactionId, false)) { // if a form is submitted without header but contains a token... should be exceptional
				$sErrorMsg = 'Unauthorized access. Please see https://www.itophub.io/wiki/page?id=3_2_0:release:developer#checking_for_the_presence_of_specific_header_in_the_post_to_enhance_protection_against_csrf_attacks';
				IssueLog::Error("Unprotected ajax call : $sErrorMsg", LogChannels::SECURITY, [
					'request_method' => $_SERVER['REQUEST_METHOD'],
					'request_uri'    => $_SERVER['REQUEST_URI'],
				]);
				IssueLog::Debug("Unprotected ajax call : $sErrorMsg", LogChannels::SECURITY, [
					'http_referer'   => $_SERVER['HTTP_REFERER'] ?? 'unknown',
					'transaction_id' => $sTransactionId,
				]);
				throw new SecurityException($sErrorMsg);
			}
		}
	}

	/**
	 * Reads parameters and returns an array of values. If a mandatory parameter is missing, it will be logged and return a 400 error.
	 *
	 * @param RequestParamDTO ...$aParam
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public static function HandleRequestParam(RequestParamDTO...$aParam): array
	{
		$aResult = [];

		foreach ($aParam as $oParam) {

			$mValue = $oParam->bIsPostParam ?
				utils::ReadPostedParam($oParam->sName, $oParam->oDefaultValue, $oParam->sSanitizationFilter) :
				utils::ReadParam($oParam->sName, $oParam->oDefaultValue, $oParam->sSanitizationFilter);

			if ($oParam->bIsMandatory && is_null($mValue)) {
				http_response_code(400);
				IssueLog::Error('Bad request: Missing mandatory parameter', LogChannels::WEB_REQUEST, [
					'request_method' => $_SERVER['REQUEST_METHOD'],
					'request_uri'    => $_SERVER['REQUEST_URI'],
				]);
				IssueLog::Debug('Bad request: Missing mandatory parameter', LogChannels::WEB_REQUEST, [
					'parameter_type' => $oParam->bIsPostParam ? 'POST' : 'GET',
					'parameter'      => $oParam->sName,
				]);
				throw new HttpBadRequestException('Bad request: Missing mandatory parameter', [
					'request_method' => $_SERVER['REQUEST_METHOD'],
					'request_uri'    => $_SERVER['REQUEST_URI'],
					'parameter_type' => $oParam->bIsPostParam ? 'POST' : 'GET',
					'parameter'      => $oParam->sName,
				]);
			}
			$aResult[] = $mValue;
		}

		return $aResult;
	}
}

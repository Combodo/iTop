<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use IssueLog;
use LogChannels;
use utils;

/**
 * Class
 * AjaxHelper
 *
 * @since 3.2.4, 3.3.1, 3.4.0
 * @package Combodo\iTop\Application\Helper
 */
class AjaxHelper
{
	/**
	 * check if header contains X-Combodo-Ajax for POST request (CSRF protection for ajax calls)
	 * check must be performed after DoLoginEx to be logged in and to be able to check the token (based on the transaction id)
	 */
	public static function RejectUnauthorizedAjaxRequest(): void
	{
		if (!isset($_SERVER['HTTP_X_COMBODO_AJAX']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);
			if (!utils::IsTransactionValid($sTransactionId, false)) { // if a form is submitted without header but contains a token... should be exceptional
				$sErrorMsg = 'Unauthorized access. Please see https://www.itophub.io/wiki/page?id=3_2_0:release:developer#checking_for_the_presence_of_specific_header_in_the_post_to_enhance_protection_against_csrf_attacks';
				IssueLog::Error("Unprotected ajax call : $sErrorMsg", LogChannels::SECURITY, [
					'request_method' => $_SERVER['REQUEST_METHOD'],
					'request_uri'    => $_SERVER['REQUEST_URI'],
					'http_referer'   => $_SERVER['HTTP_REFERER'] ?? 'unknown',
					'transaction_id' => $sTransactionId,
				]);
				header('HTTP/1.1 401 Unauthorized');
				die($sErrorMsg);
			}
		}
	}
}

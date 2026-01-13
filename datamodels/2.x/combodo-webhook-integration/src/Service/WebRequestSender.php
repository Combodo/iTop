<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Service;

use Combodo\iTop\Core\WebRequest;
use Combodo\iTop\Core\WebResponse;
use ContextTag;
use Exception;
use IssueLog;
use MetaModel;
use SendWebRequest;
use utils;

/**
 * Class WebRequestSender
 *
 * @package Combodo\iTop\Service
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class WebRequestSender
{
	/** @var int Request sent successfully */
	const ENUM_SEND_STATE_OK = 0;
	/** @var int Request still pending */
	const ENUM_SEND_STATE_PENDING = 1;
	/** @var int Request could not be sent due to an error */
	const ENUM_SEND_STATE_ERROR = 2;

	/** @var string Request to be sent synchronously = immediately, blocking the current script execution while sending (eg. network wait) */
	const ENUM_SEND_MODE_SYNC = 'sync';
	/** @var string Request to be sent asynchronously = by the CRON job to avoid blocking script execution */
	const ENUM_SEND_MODE_ASYNC = 'async';

	/** @var string \ContextTag placed around the ResponseHandler call */
	const CONTEXT_TAG_RESPONSE_HANDLER = 'Combodo\iTop\Service\WebRequestSender:ResponseHandler';

	/** @var string */
	const DEFAULT_SEND_MODE = self::ENUM_SEND_MODE_SYNC;
	/** @var int */
	const DEFAULT_CONNECTION_TIMEOUT_IN_SECONDS = 5;

	/** @var null|\Combodo\iTop\Service\WebRequestSender $oInstance */
	public static $oInstance = null;
	private static bool $mockDoPostRequest = false;

	public static function SetMockDoPostRequest(bool $mockDoPostRequest): void
	{
		static::$mockDoPostRequest = $mockDoPostRequest;
	}
	/**
	 * Return the singleton instance for this class
	 *
	 * @return \Combodo\iTop\Service\WebRequestSender
	 */
	public static function GetInstance()
	{
		if(static::$oInstance === null)
		{
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	/**
	 * Send the $oRequest synchronously or asynchronously depending on the $bForcedSendMode parameter and the 'prefer_asynchronous' module parameter.
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest        The web request to send
	 * @param array                         $aIssues         Array of errors that occurred during sending
	 * @param null|\EventNotification       $oLog
	 * @param null|string                   $sForcedSendMode If null, will check module parameter, otherwise force mode using static::ENUM_SEND_MODE_SYNC or static::ENUM_SEND_MODE_ASYNC
	 *
	 * @return array An array containing the 'sender_status' (static::ENUM_SEND_STATE_XXX) and optionally 'response' a WebResponse object.
	 */
	public function Send(WebRequest $oRequest, &$aIssues, $oLog = null, $sForcedSendMode = null)
	{
		if($sForcedSendMode === static::ENUM_SEND_MODE_SYNC)
		{
			$aResult = $this->SendSynchronously($oRequest, $aIssues, $oLog);
		}
		elseif($sForcedSendMode === static::ENUM_SEND_MODE_ASYNC)
		{
			$aResult = $this->SendAsynchronously($oRequest, $aIssues, $oLog);
		}
		elseif(MetaModel::GetModuleSetting('combodo-webhook-integration', 'prefer_asynchronous', (static::DEFAULT_SEND_MODE === static::ENUM_SEND_MODE_ASYNC)))
		{
			$aResult = $this->SendAsynchronously($oRequest, $aIssues, $oLog);
		}
		else
		{
			$aResult = $this->SendSynchronously($oRequest, $aIssues, $oLog);
		}

		return $aResult;
	}

	/**
	 * Sends the $oRequest immediately, then returns the sender status code and the response object
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest WebRequest to send
	 * @param array                         $aIssues  Issue messages
	 * @param \EventNotification            $oLog
	 *
	 * @return array
	 */
	public function SendSynchronously(WebRequest $oRequest, &$aIssues, $oLog = null)
	{
		try
		{
			// ============================
			//  Inyectar opciones de proxy
			// ============================
			$oConfig    = MetaModel::GetConfig();
			$aProxyConf = $oConfig->GetModuleSetting('combodo-webhook-integration', 'proxy', null);

			if (is_array($aProxyConf) && !empty($aProxyConf['host'])) {
				// Recuperar opciones actuales del request (si no hay, iniciamos array vacío)
				$aCurlOptions = $oRequest->GetOptions();
				if (!is_array($aCurlOptions)) {
					$aCurlOptions = array();
				}

				// Host:puerto del proxy
				$aCurlOptions[CURLOPT_PROXY]     = $aProxyConf['host'];
				$aCurlOptions[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;

				// Auth opcional si el proxy la requiere
				if (!empty($aProxyConf['user'])) {
					$sAuth = $aProxyConf['user'];
					if (!empty($aProxyConf['password'])) {
						$sAuth .= ':'.$aProxyConf['password'];
					}
					$aCurlOptions[CURLOPT_PROXYUSERPWD] = $sAuth;
				}

				// Guardar las opciones de vuelta en el request
				$oRequest->SetOptions($aCurlOptions);
			}
			// ============================

			$aResponseHeaders = array();
			$sResponse = $this->DoPostRequest($oRequest->GetURL(), array(), null, $aResponseHeaders, $oRequest->GetOptions());

			$oResponse = new WebResponse();
			$oResponse->SetHeaders($aResponseHeaders)
				->SetBody($sResponse);

			// Log response
			if($oLog !== null)
			{
				$oLog->Set('response', $sResponse);
				$oLog->DBUpdate();
			}

			// Handle response
			if ($oRequest->HasResponseHandler()) {
				$oCtx = new ContextTag(static::CONTEXT_TAG_RESPONSE_HANDLER);
				call_user_func($oRequest->GetResponseHandlerName(), $oResponse, $oRequest->GetResponseHandlerParams());
				// Manual var unset to force context tag to be removed, otherwise it will be removed when the current method ends
				unset($oCtx);
			}

			return array(
				'sender_status' => static::ENUM_SEND_STATE_OK,
				'response' => $oResponse,
			);
		}
		catch(Exception $oException)
		{
			$sErrorMessage = 'Error while sending request to webhook: '.$oException->getMessage();
			IssueLog::Error($sErrorMessage);
			$aIssues[] = $sErrorMessage;

			return array(
				'sender_status' => static::ENUM_SEND_STATE_ERROR,
				'response' => null,
			);
		}
	}


	/**
	 * Add the $oRequest to the queue in order to be send later
	 *
	 * @param \Combodo\iTop\Core\WebRequest $oRequest WebRequest to add in the queue
	 * @param array                         $aIssues Issue messages
	 * @param null|\EventNotification       $oLog
	 *
	 * @return array
	 */
	public function SendAsynchronously(WebRequest $oRequest, &$aIssues, $oLog = null)
	{
		try
		{
			SendWebRequest::AddToQueue($oRequest, $oLog);
		}
		catch(Exception $oException)
		{
			$sErrorMessage = 'Exception thrown when trying to add request to queue: '.$oException->getMessage();
			IssueLog::Error($sErrorMessage);
			$aIssues[] = $sErrorMessage;

			return array(
				'sender_status' => static::ENUM_SEND_STATE_ERROR,
				'response' => null,
			);
		}

		return array(
			'sender_status' => static::ENUM_SEND_STATE_PENDING,
			'response' => null,
		);
	}

	private function DoPostRequest($sUrl, $aData, $sOptionnalHeaders = null, &$aResponseHeaders = null, $aCurlOptions = array())
	{
		if (static::$mockDoPostRequest) {
			return '';
		} else {
			return utils::DoPostRequest($sUrl, $aData, $sOptionnalHeaders, $aResponseHeaders, $aCurlOptions);
		}
	}
}
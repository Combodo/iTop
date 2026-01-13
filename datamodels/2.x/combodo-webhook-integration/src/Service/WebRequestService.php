<?php

namespace Combodo\iTop\Service;

use Combodo\iTop\Oauth2Client\Service\Oauth2Service;
use RemoteOauthConnection;

class WebRequestService {
	private static WebRequestService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): WebRequestService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	/**
	 * Test purpose only
	 */
	final public static function SetInstance(WebRequestService $oInstance)
	{
		self::$oInstance = $oInstance;
	}

	/**
	 * Test purpose only
	 */
	final public static function ResetInstance()
	{
		self::$oInstance = new static();
	}

	public function ObfuscateRawHeader(string $sRawHeaders) : string {
		// Obfuscate authorization header
		$aReplaceRegexps = [
			'/^Authorization:(.+)$/m' => 'Authorization: ******',
			'/^Auth-Token:(.+)$/m' => 'Auth-Token: ******',
		];

		$sObfuscatedRawHeaders = $sRawHeaders;
		foreach($aReplaceRegexps as $sRegExp => $sAuthHeaderObfuscated){
			$sObfuscatedRawHeaders = preg_replace($sRegExp, $sAuthHeaderObfuscated, $sObfuscatedRawHeaders);
		}

		return $sObfuscatedRawHeaders;
	}

	public function EnrichHeader(RemoteOauthConnection $oRemoteOauthConnection, &$aHeaders) : void {
		/** @var \OAuth2Client $oOauth2Client */
		$oOauth2Client = \MetaModel::GetObject(\Oauth2Client::class, $oRemoteOauthConnection->Get('oauth2client_id'));

		Oauth2Service::GetInstance()->InitByOauth2Client($oOauth2Client);
		$sAccessToken = Oauth2Service::GetInstance()->GetAccessToken();
		$aHeaders[] = sprintf("Authorization: %s %s",
			$oOauth2Client->Get('token_type'), $sAccessToken
		);
	}
}

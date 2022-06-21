<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use CoreException;
use DBObjectSet;
use DBSearch;
use Dict;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessTokenInterface;
use MetaModel;

class OAuthClientProviderFactory
{
	/**
	 * @return mixed
	 * @throws \CoreException
	 */
	public static function getProviderForSMTP()
	{
		$oRemoteAuthentOAuth = self::GetRemoteAuthentOAuthForSMTP();

		$sProviderVendor = $oRemoteAuthentOAuth->Get('provider');
		$sProviderClass = self::getProviderClass($sProviderVendor);
		$aProviderVendorParams = [
			'clientId'     => $oRemoteAuthentOAuth->Get('client_id'),
			'clientSecret' => $oRemoteAuthentOAuth->Get('client_secret'),
			'redirectUri'  => $sProviderClass::GetRedirectUri(),
			'scope'        => $sProviderClass::GetRequiredSMTPScope(),
		];
		$aAccessTokenParams = [
			"access_token"  => $oRemoteAuthentOAuth->Get('token'),
			"refresh_token" => $oRemoteAuthentOAuth->Get('refresh_token'),
			'scope'         => $sProviderClass::GetRequiredSMTPScope(),
		];
		$aCollaborators = [
			'httpClient' => new Client(['verify' => false]),
		];

		return new $sProviderClass($aProviderVendorParams, $aCollaborators, $aAccessTokenParams);
	}

	/**
	 * @return \DBObject|null
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function GetRemoteAuthentOAuthForSMTP()
	{
		$sUsername = MetaModel::GetConfig()->Get('email_transport_smtp.username');
		$oSet = new DBObjectSet(DBSearch::FromOQL('SELECT RemoteAuthentOAuth WHERE name=:username', ['username' => $sUsername]));
		if ($oSet->Count() != 1) {
			throw new CoreException(Dict::Format('itop-remote-authent-oauth:MissingRemoteAuthentOAuth', $sUsername));
		}
		return $oSet->Fetch();
	}

	/**
	 * @param $sProviderVendor
	 * @param $sClientId
	 * @param $sClientSecret
	 * @param $sScope
	 * @param $aAdditional
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	public static function getVendorProvider($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional)
	{
		$sRedirectUrl = OAuthClientProviderAbstract::GetRedirectUri();
		$sProviderClass = self::getProviderClass($sProviderVendor);
		$aCollaborators = [
			'httpClient' => new Client(['verify' => false]),
		];

		return new $sProviderClass(array_merge(['clientId' => $sClientId, 'clientSecret' => $sClientSecret, 'redirectUri' => $sRedirectUrl, 'scope' => $sScope], $aAdditional), $aCollaborators);
	}

	public static function getVendorProviderForAccessUrl($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional)
	{
		$oProvider = static::getVendorProvider($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional);

		return $oProvider->GetVendorProvider()->getAuthorizationUrl([
			'scope' => [
				$sScope,
			],
		]);
	}

	/**
	 * @param \Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract $oProvider
	 * @param $sCode
	 *
	 * @return AccessTokenInterface
	 * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
	 */
	public static function getAccessTokenFromCode($oProvider, $sCode)
	{
		return $oProvider->GetVendorProvider()->getAccessToken('authorization_code', ['code' => $sCode, 'scope' => $oProvider->GetScope()]);
	}

	public static function getConfFromRedirectUrl($sProviderVendor, $sClientId, $sClientSecret, $sRedirectUrlQuery)
	{
		$sRedirectUrl = OAuthClientProviderAbstract::GetRedirectUri();
		$sProviderClass = self::getProviderClass($sProviderVendor);
		$aQuery = [];
		parse_str($sRedirectUrlQuery, $aQuery);
		$sCode = $aQuery['code'];
		$oProvider = new $sProviderClass(['clientId' => $sClientId, 'clientSecret' => $sClientSecret, 'redirectUri' => $sRedirectUrl]);

		return $sProviderClass::getConfFromAccessToken($oProvider->GetVendorProvider()->getAccessToken('authorization_code', ['code' => $sCode]), $sClientId, $sClientSecret);
	}

	/**
	 * @param $sProviderVendor
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public static function getProviderClass($sProviderVendor): string
	{
		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProviderVendor;
		if (!class_exists($sProviderClass)) {
			throw new CoreException(dict::Format('UI:Error:SMTP:UnknownVendor', $sProviderVendor));
		}

		return $sProviderClass;
	}

}
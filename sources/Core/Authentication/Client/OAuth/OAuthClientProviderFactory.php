<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessTokenInterface;
use MetaModel;

class OAuthClientProviderFactory {
	public static function getProviderForSMTP()
	{
		$sProviderVendor = MetaModel::GetConfig()->Get('email_transport_smtp.oauth.provider'); // email_transport_smtp.oauth.provider
		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProviderVendor;
		$aProviderVendorParams = [
			'clientId'     => MetaModel::GetConfig()->Get('email_transport_smtp.oauth.client_id'),  // email_transport_smtp.oauth.client_id
			'clientSecret' => MetaModel::GetConfig()->Get('email_transport_smtp.oauth.client_secret'),// email_transport_smtp.oauth.client_secret
			'redirectUri'  => $sProviderClass::GetRedirectUri(),
			'scope' => $sProviderClass::GetRequiredSMTPScope()
		];
		$aAccessTokenParams = [
			"access_token"  => MetaModel::GetConfig()->Get('email_transport_smtp.oauth.access_token'), // email_transport_smtp.oauth.access_token
			"refresh_token" => MetaModel::GetConfig()->Get('email_transport_smtp.oauth.refresh_token'), // email_transport_smtp.oauth.refresh_token
			'scope' => $sProviderClass::GetRequiredSMTPScope()
		];
		$aCollaborators = [
			'httpClient' => new Client(['verify' => false]),
		];
		
		return new $sProviderClass($aProviderVendorParams, $aCollaborators, $aAccessTokenParams);
	}
	public static function getVendorProvider($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional){
		$sRedirectUrl = OAuthClientProviderAbstract::GetRedirectUri();
		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProviderVendor;
		$aCollaborators = [
			'httpClient' => new Client(['verify' => false]),
		];

		return new $sProviderClass(array_merge(['clientId' => $sClientId, 'clientSecret' => $sClientSecret, 'redirectUri' => $sRedirectUrl, 'scope' => $sScope], $aAdditional), $aCollaborators);
	}
	
	public static function getVendorProviderForAccessUrl($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional){
		$oProvider = static::getVendorProvider($sProviderVendor, $sClientId, $sClientSecret, $sScope, $aAdditional);
		return $oProvider->GetVendorProvider()->getAuthorizationUrl([
			'scope' => [
				$sScope
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
		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProviderVendor;
		$aQuery = [];
		parse_str($sRedirectUrlQuery, $aQuery);
		$sCode = $aQuery['code'];
		$oProvider = new $sProviderClass(['clientId' => $sClientId, 'clientSecret' => $sClientSecret, 'redirectUri' => $sRedirectUrl]);
		return $sProviderClass::getConfFromAccessToken($oProvider->GetVendorProvider()->getAccessToken('authorization_code', ['code' => $sCode]), $sClientId, $sClientSecret);
	}

}
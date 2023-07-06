<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use CoreException;
use DBObject;
use DBObjectSet;
use DBSearch;
use Dict;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessTokenInterface;
use MetaModel;
use OAuthClient;
use utils;

class OAuthClientProviderFactory
{
	/**
	 * @return mixed
	 * @throws \CoreException
	 */
	public static function GetProviderForSMTP()
	{
		$oOAuthClient = self::GetOAuthClientForSMTP();

		return self::GetClientProvider($oOAuthClient);
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
	public static function GetOAuthClientForSMTP()
	{
		$sUsername = MetaModel::GetConfig()->Get('email_transport_smtp.username');
		$oSet = new DBObjectSet(DBSearch::FromOQL("SELECT OAuthClient WHERE name=:username", ['username' => $sUsername]));
		if ($oSet->Count() < 1) {
			throw new CoreException(Dict::Format('itop-oauth-client:MissingOAuthClient', $sUsername));
		}
		while ($oOAuthClient = $oSet->Fetch()) {
			if ($oOAuthClient->Get('used_for_smtp') == 'yes') {
				return $oOAuthClient;
			}
		}
		throw new CoreException(Dict::Format('itop-oauth-client:MissingOAuthClient', $sUsername));
	}

	/**
	 * @param \OAuthClient $oOAuthClient
	 *
	 * @return mixed
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public static function GetAuthorizationUrl(OAuthClient $oOAuthClient)
	{
		$oProvider = self::GetClientProvider($oOAuthClient);
		return $oProvider->GetVendorProvider()->getAuthorizationUrl([
			'scope' => [
				$oProvider->GetScope(),
			],
		]);
	}

	/**
	 * @param \OAuthClient $oOAuthClient
	 * @param $sCode
	 *
	 * @return AccessTokenInterface
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
	 */
	public static function GetAccessTokenFromCode(OAuthClient $oOAuthClient, $sCode)
	{
		$oProvider = self::GetClientProvider($oOAuthClient);
		return $oProvider->GetVendorProvider()->getAccessToken('authorization_code', ['code' => $sCode, 'scope' => $oProvider->GetScope()]);
	}

	/**
	 * @param $sProviderVendor
	 *
	 * @return string
	 * @throws \CoreException
	 */
	protected static function GetProviderClass($sProviderVendor): string
	{
		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProviderVendor;
		if (!class_exists($sProviderClass)) {
			throw new CoreException(Dict::Format('UI:Error:SMTP:UnknownVendor', $sProviderVendor));
		}

		return $sProviderClass;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function GetRedirectUri(): string
	{
		return utils::GetAbsoluteUrlAppRoot().'pages/oauth.landing.php';
	}

	/**
	 * @param \DBObject $oOAuthClient
	 *
	 * @return OAuthClientProviderAbstract
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public static function GetClientProvider(DBObject $oOAuthClient)
	{
		$sProviderVendor = $oOAuthClient->Get('provider');
		$sProviderClass = self::GetProviderClass($sProviderVendor);
		$aCollaborators = [
			'httpClient' => new Client(['verify' => false]),
		];

		return new $sProviderClass($oOAuthClient, $aCollaborators);
	}

}
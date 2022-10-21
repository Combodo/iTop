<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use OAuthClient;

abstract class OAuthClientProviderAbstract implements IOAuthClientProvider
{
	/** @var string */
	static protected $sVendorName = '';

	/** @var \League\OAuth2\Client\Provider\GenericProvider */
	protected $oVendorProvider;

	/** @var OAuthClient */
	protected $oOauthClient;

	public function __construct($oOauthClient)
	{
		$this->oOauthClient = $oOauthClient;
	}

	/**
	 * @return \League\OAuth2\Client\Provider\GenericProvider
	 */
	public function GetVendorProvider()
	{
		return $this->oVendorProvider;
	}

	/**
	 * @param \League\OAuth2\Client\Provider\GenericProvider $oVendorProvider
	 */
	public function SetVendorProvider(GenericProvider $oVendorProvider)
	{
		$this->oVendorProvider = $oVendorProvider;
	}

	/**
	 * @return \League\OAuth2\Client\Token\AccessToken
	 */
	public function GetAccessToken(): AccessToken
	{
		return $this->oOauthClient->GetAccessToken();
	}

	/**
	 * @param \League\OAuth2\Client\Token\AccessToken $oAccessToken
	 */
	public function SetAccessToken(AccessToken $oAccessToken)
	{
		$this->oOauthClient->SetAccessToken($oAccessToken);
	}

	/**
	 * @return mixed
	 */
	public function GetScope()
	{
		return $this->oOauthClient->GetScope();
	}

	/**
	 * @return string
	 */
	public static function GetVendorName()
	{
		return self::$sVendorName;
	}

}
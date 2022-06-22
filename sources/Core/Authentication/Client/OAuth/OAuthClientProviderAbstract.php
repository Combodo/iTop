<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use utils;

abstract class OAuthClientProviderAbstract implements IOAuthClientProvider
{
	/** @var string */
	static protected $sVendorName = '';
	/** @var array */
	static protected $sVendorColors = ['', '', '', ''];
	/** @var string */
	static protected $sVendorIcon = '';
	static protected $sRedirectUri = '';
	static protected $sRequiredSMTPScope = '';
	static protected $sRequiredIMAPScope = '';
	static protected $sRequiredPOPScope = '';
	/** @var \League\OAuth2\Client\Provider\GenericProvider */
	protected $oVendorProvider;
	/** @var \League\OAuth2\Client\Token\AccessToken */
	protected $oAccessToken;

	protected $sScope;


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
		return $this->oAccessToken;
	}

	/**
	 * @param \League\OAuth2\Client\Token\AccessToken $oAccessToken
	 */
	public function SetAccessToken(AccessToken $oAccessToken)
	{
		$this->oAccessToken = $oAccessToken;
	}

	/**
	 * @return string
	 */
	public static function GetVendorName(): string
	{
		return static::$sVendorName;
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public static function InitizalizeRedirectUri()
	{
		static::$sRedirectUri = utils::GetAbsoluteUrlAppRoot().'pages/oauth.landing.php';
	}

	/**
	 * @return string
	 */
	public static function GetRedirectUri(): string
	{
		if (static::$sRedirectUri === '') {
			static::InitizalizeRedirectUri();
		}

		return static::$sRedirectUri;
	}

	/**
	 * @return string
	 */
	public static function GetRequiredSMTPScope(): string
	{
		return static::$sRequiredSMTPScope;
	}

	/**
	 * @return string
	 */
	public static function GetRequiredIMAPScope(): string
	{
		return static::$sRequiredIMAPScope;
	}

	/**
	 * @return string
	 */
	public static function GetRequiredPOPScope(): string
	{
		return static::$sRequiredPOPScope;
	}

	/**
	 * @return mixed
	 */
	public function GetScope()
	{
		return $this->sScope;
	}

	/**
	 * @param mixed $sScope
	 */
	public function SetScope($sScope)
	{
		$this->sScope = $sScope;
	}

}
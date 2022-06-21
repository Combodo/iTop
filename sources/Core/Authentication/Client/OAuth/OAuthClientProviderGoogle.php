<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;

class OAuthClientProviderGoogle extends OAuthClientProviderAbstract
{
	/** @var string */
	static protected $sVendorName = 'Google';
	/** @var array */
	static protected $sVendorColors = ['#DB4437', '#F4B400', '#0F9D58', '#4285F4'];
	/** @var string */
	static protected $sVendorIcon = '../images/icons/icons8-google.svg';

	/** @var \League\OAuth2\Client\Token\AccessToken */
	protected $oAccessToken;
	static protected $sRequiredSMTPScope = 'https://mail.google.com/';
	static protected $sRequiredIMAPScope = 'https://mail.google.com/';
	static protected $sRequiredPOPScope = 'https://mail.google.com/';

	public function __construct($aVendorProvider, array $collaborators = [], array $aAccessTokenParams = [])
	{
		$this->oVendorProvider = new Google(array_merge(['prompt' => 'consent', 'accessType' => 'offline'], $aVendorProvider), $collaborators);

		if (!empty($aAccessTokenParams)) {
			$this->oAccessToken = new AccessToken([
				"access_token"  => $aAccessTokenParams["access_token"],
				"expires_in"    => -1,
				"refresh_token" => $aAccessTokenParams["refresh_token"],
				"token_type"    => "Bearer",
			]);
		}

		if (isset($aVendorProvider['scope'])) {
			$this->SetScope($aVendorProvider['scope']);
		}
	}
}
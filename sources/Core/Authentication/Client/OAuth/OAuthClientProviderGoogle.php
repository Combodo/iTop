<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use League\OAuth2\Client\Provider\Google;

class OAuthClientProviderGoogle extends OAuthClientProviderAbstract
{
//	/** @var string */
//	static protected $sVendorName = 'Google';
//	/** @var array */
//	static protected $sVendorColors = ['#DB4437', '#F4B400', '#0F9D58', '#4285F4'];
//	/** @var string */
//	static protected $sVendorIcon = '../images/icons/icons8-google.svg';
//
//	static protected $sRequiredSMTPScope = 'https://mail.google.com/';
//	static protected $sRequiredIMAPScope = 'https://mail.google.com/';
//	static protected $sRequiredPOPScope = 'https://mail.google.com/';

	public function __construct($oOAuthClient, array $collaborators = [])
	{
		parent::__construct($oOAuthClient);
		$aOptions = [
			'prompt'       => 'consent',
			'accessType'   => 'offline',
			'clientId'     => $oOAuthClient->Get('client_id'),
			'clientSecret' => $oOAuthClient->Get('client_secret'),
			'redirectUri'  => $oOAuthClient->Get('redirect_url'),
			'scope'        => $oOAuthClient->GetScope(),

		];
		$this->oVendorProvider = new Google($aOptions, $collaborators);

	}
}
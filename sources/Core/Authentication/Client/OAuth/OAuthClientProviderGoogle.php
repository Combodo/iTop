<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use League\OAuth2\Client\Provider\Google;

class OAuthClientProviderGoogle extends OAuthClientProviderAbstract
{
	/** @var string */
	static protected $sVendorName = 'Google';

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
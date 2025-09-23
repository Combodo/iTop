<?php

namespace Combodo\iTop\Core\Authentication\Client\OAuth;

use TheNetworg\OAuth2\Client\Provider\Azure;

class OAuthClientProviderAzure extends OAuthClientProviderAbstract
{
	/** @var string */
	static protected $sVendorName = 'Azure';

	public function __construct($oOAuthClient, array $collaborators = [])
	{
		parent::__construct($oOAuthClient);

		$aOptions = [
			'prompt'                 => 'consent',
			'scope'                  => 'offline_access',
			'defaultEndPointVersion' => Azure::ENDPOINT_VERSION_2_0,
			'clientId'               => $oOAuthClient->Get('client_id'),
			'clientSecret'           => $oOAuthClient->Get('client_secret'),
			'redirectUri'            => $oOAuthClient->Get('redirect_url'),
            'tenant'                 => $oOAuthClient->Get('tenant'),
		];

		$this->oVendorProvider = new Azure($aOptions, $collaborators);
	}
}

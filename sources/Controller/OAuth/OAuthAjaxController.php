<?php

namespace Combodo\iTop\Controller\OAuth;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use utils;

class OAuthAjaxController extends Controller
{
	public function OperationGetAuthorizationUrl()
	{
		$aResult = ['status' => 'success', 'data' => []];
		$sProvider = utils::ReadParam('provider', '', false, 'raw');
		$sClientId = utils::ReadParam('client_id', '', false, 'raw');
		$sClientSecret = utils::ReadParam('client_secret', '', false, 'raw');
		$sScope = utils::ReadParam('scope', '', false, 'raw');
		$sAdditional = utils::ReadParam('additional', '', false, 'raw');
		$aAdditional = [];
		parse_str($sAdditional, $aAdditional);
		$sAuthorizationUrl = OAuthClientProviderFactory::getVendorProviderForAccessUrl($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
		$aResult['data']['authorization_url'] = $sAuthorizationUrl;

		$this->DisplayJSONPage($aResult);
	}
}
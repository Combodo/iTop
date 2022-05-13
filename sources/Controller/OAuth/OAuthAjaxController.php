<?php

namespace Combodo\iTop\Controller\OAuth;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;
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

	public function OperationGetDisplayAuthenticationResults()
	{
		$aResult = ['status' => 'success', 'data' => []];
		$sProvider = utils::ReadParam('provider', '', false, 'raw');
		$sRedirectUrl = utils::ReadParam('redirect_url', '', false, 'raw');
		$sClientId = utils::ReadParam('client_id', '', false, 'raw');
		$sClientSecret = utils::ReadParam('client_secret', '', false, 'raw');
		$sScope = utils::ReadParam('scope', '', false, 'raw');
		$sAdditional = utils::ReadParam('additional', '', false, 'raw');

		$sRedirectUrlQuery = parse_url($sRedirectUrl)['query'];
		// TODO: Needs to handle mail to ticket part too
		$aOAuthResultDisplayClasses = ['\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientResultDisplayConf'];
		$aAdditional = [];
		parse_str($sAdditional, $aAdditional);

		$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProvider;
		$sRedirectUrl = OAuthClientProviderAbstract::GetRedirectUri();

		$aQuery = [];
		parse_str($sRedirectUrlQuery, $aQuery);
		$sCode = $aQuery['code'];
		$oProvider = OAuthClientProviderFactory::getVendorProvider($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
		$oAccessToken = OAuthClientProviderFactory::getAccessTokenFromCode($oProvider, $sCode);

		foreach ($aOAuthResultDisplayClasses as $sOAuthClass) {
			$aResult['data'][] = $sOAuthClass::GetResultDisplayScript($sClientId, $sClientSecret, $sProvider, $oAccessToken);
		}

		$this->DisplayJSONPage($aResult);
	}
}
<?php

use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;

require_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');

$oPage = new JsonPage();
$oPage->SetOutputDataOnly(true);
$aResult = ['status' => 'success', 'data' => []];
try {
	$operation = utils::ReadParam('operation', '');
	
	switch ($operation) {
		case 'get_authorization_url':
			$sProvider = utils::ReadParam('provider', '', false, 'raw');
			$sClientId = utils::ReadParam('client_id', '', false, 'raw');
			$sClientSecret = utils::ReadParam('client_secret', '', false, 'raw');
			$sScope = utils::ReadParam('scope', '', false, 'raw');
			$sAdditional = utils::ReadParam('additional', '', false, 'raw');
			$aAdditional = [];
			parse_str($sAdditional, $aAdditional);
			$sAuthorizationUrl = OAuthClientProviderFactory::getVendorProviderForAccessUrl($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
			$aResult['data']['authorization_url'] = $sAuthorizationUrl;
			break;
		case 'get_display_authentication_results':
			$sProvider = utils::ReadParam('provider', '', false, 'raw');
			$sRedirectUrl = utils::ReadParam('redirect_url', '', false, 'raw');
			$sClientId = utils::ReadParam('client_id', '', false, 'raw');
			$sClientSecret = utils::ReadParam('client_secret', '', false, 'raw');
			$sScope = utils::ReadParam('scope', '', false, 'raw');
			$sAdditional = utils::ReadParam('additional', '', false, 'raw');

			$sRedirectUrlQuery = parse_url($sRedirectUrl)['query'];
			$aOAuthResultDisplayClasses = utils::GetClassesForInterface('Combodo\iTop\Core\Authentication\Client\OAuth\IOAuthClientResultDisplay', '', array('[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]'));
			$aAdditional = [];
			parse_str($sAdditional, $aAdditional);

			$sProviderClass = "\Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProvider".$sProvider;
			$sRedirectUrl = OAuthClientProviderAbstract::GetRedirectUri();
			
			$aQuery = [];
			parse_str($sRedirectUrlQuery, $aQuery);
			$sCode = $aQuery['code'];
			$oProvider = OAuthClientProviderFactory::getVendorProvider($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
			$oAccessToken = OAuthClientProviderFactory::getAccessTokenFromCode($oProvider, $sCode);

			foreach($aOAuthResultDisplayClasses as $sOAuthClass) {
				$aResult['data'][] = $sOAuthClass::GetResultDisplayScript($sClientId, $sClientSecret, $sProvider, $oAccessToken);
			}
	}
}
catch(Exception $e){
	$aResult['status'] = 'error';
	IssueLog::Error($e->getMessage());
}
$oPage->SetData($aResult);
$oPage->output();
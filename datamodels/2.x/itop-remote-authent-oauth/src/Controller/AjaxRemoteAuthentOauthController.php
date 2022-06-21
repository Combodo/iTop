<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\RemoteAuthentOAuth\Controller;

use cmdbAbstractObject;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use Dict;
use IssueLog;
use MetaModel;
use utils;

class AjaxRemoteAuthentOauthController extends Controller
{
	const LOG_CHANNEL = 'OAuth';

	public function OperationGetOAuthAuthorizationUrl()
	{
		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("GetAuthorizationUrl for $sClass::$sId", self::LOG_CHANNEL);

		$oObject = MetaModel::GetObject($sClass, $sId);

		$aResult = ['status' => 'success', 'data' => []];
		$sProvider = $oObject->Get('provider');
		$sClientId = $oObject->Get('client_id');
		$sClientSecret = $oObject->Get('client_secret');
		$sScope = $oObject->Get('scope');
		$aAdditional = [];
		$sAuthorizationUrl = OAuthClientProviderFactory::getVendorProviderForAccessUrl($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
		$aResult['data']['authorization_url'] = $sAuthorizationUrl;

		$this->DisplayJSONPage($aResult);

	}

	public function OperationGetDisplayAuthenticationResults()
	{
		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("GetDisplayAuthenticationResults for $sClass::$sId", self::LOG_CHANNEL);

		$oObject = MetaModel::GetObject($sClass, $sId);
		$bIsCreation = empty($oObject->Get('token'));

		$sProvider = $oObject->Get('provider');
		$sClientId = $oObject->Get('client_id');
		$sClientSecret = $oObject->Get('client_secret');
		$sScope = $oObject->Get('scope');
		$aAdditional = [];

		$sRedirectUrl = utils::ReadParam('redirect_url', '', false, 'raw');

		$sRedirectUrlQuery = parse_url($sRedirectUrl)['query'];

		$aQuery = [];
		parse_str($sRedirectUrlQuery, $aQuery);
		$sCode = $aQuery['code'];
		$oProvider = OAuthClientProviderFactory::getVendorProvider($sProvider, $sClientId, $sClientSecret, $sScope, $aAdditional);
		$oAccessToken = OAuthClientProviderFactory::getAccessTokenFromCode($oProvider, $sCode);

		$oObject->Set('token', $oAccessToken->getToken());
		$oObject->Set('refresh_token', $oAccessToken->getRefreshToken());
		$oObject->DBUpdate();

		cmdbAbstractObject::SetSessionMessage(
			$sClass,
			$sId,
			"$sClass:$sId:TokenCreated",
			$bIsCreation ? Dict::S('itop-remote-authent-oauth:Message:TokenCreated') : Dict::S('itop-remote-authent-oauth:Message:TokenRecreated'),
			'ok',
			1,
			true
		);

		$aResult = ['status' => 'success'];
		$aResult['data'] = utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$sId";

		$this->DisplayJSONPage($aResult);
	}

}
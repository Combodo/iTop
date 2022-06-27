<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Controller;

use cmdbAbstractObject;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use Dict;
use IssueLog;
use MetaModel;
use utils;

class AjaxOauthClientController extends Controller
{
	const LOG_CHANNEL = 'OAuth';

	public function OperationGetOAuthAuthorizationUrl()
	{
		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("GetAuthorizationUrl for $sClass::$sId", self::LOG_CHANNEL);

		/** @var \OAuthClient $oOAuthClient */
		$oOAuthClient = MetaModel::GetObject($sClass, $sId);

		$aResult = ['status' => 'success', 'data' => []];

		$sAuthorizationUrl = OAuthClientProviderFactory::GetAuthorizationUrl($oOAuthClient);
		$aResult['data']['authorization_url'] = $sAuthorizationUrl;

		$this->DisplayJSONPage($aResult);
	}

	public function OperationGetDisplayAuthenticationResults()
	{
		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("GetDisplayAuthenticationResults for $sClass::$sId", self::LOG_CHANNEL);

		/** @var \OAuthClient $oOAuthClient */
		$oOAuthClient = MetaModel::GetObject($sClass, $sId);
		$bIsCreation = empty($oOAuthClient->Get('token'));

		$sRedirectUrl = utils::ReadParam('redirect_url', '', false, 'raw');

		$sRedirectUrlQuery = parse_url($sRedirectUrl)['query'];

		$aQuery = [];
		parse_str($sRedirectUrlQuery, $aQuery);
		$sCode = $aQuery['code'];
		$oAccessToken = OAuthClientProviderFactory::GetAccessTokenFromCode($oOAuthClient, $sCode);

		$oOAuthClient->SetAccessToken($oAccessToken);

		cmdbAbstractObject::SetSessionMessage(
			$sClass,
			$sId,
			"$sClass:$sId:TokenCreated",
			$bIsCreation ? Dict::S('itop-oauth-client:Message:TokenCreated') : Dict::S('itop-oauth-client:Message:TokenRecreated'),
			'ok',
			1,
			true
		);

		$aResult = ['status' => 'success'];
		$aResult['data'] = utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$sId";

		$this->DisplayJSONPage($aResult);
	}

}
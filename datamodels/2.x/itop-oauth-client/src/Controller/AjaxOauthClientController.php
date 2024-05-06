<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Controller;

use cmdbAbstractObject;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use Dict;
use Exception;
use IssueLog;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use MetaModel;
use utils;
use Combodo\iTop\Application\WebPage\WebPage;

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

        try {
            $sAuthorizationUrl = OAuthClientProviderFactory::GetAuthorizationUrl($oOAuthClient);
            $aResult['data']['authorization_url'] = $sAuthorizationUrl;
        } catch (Exception $oException) {
            $aResult['status'] = 'error';
            $aResult['error_description'] = $oException->getMessage();
        }

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

		$aResult = [];
		$aResult['status'] = 'error';
		$aURL = parse_url($sRedirectUrl);
		$aQuery = [];
		if (isset($aURL['query'])) {
			$sRedirectUrlQuery = $aURL['query'];
			parse_str($sRedirectUrlQuery, $aQuery);
			if (isset($aQuery['error'])) {
				$aResult['status'] = 'error';
				if (isset($aQuery['error_description'])) {
					$aResult['error_description'] = $aQuery['error_description'];
				}
			}
			if (isset($aQuery['code'])) {
				$sCode = $aQuery['code'];
				try {
					$oAccessToken = OAuthClientProviderFactory::GetAccessTokenFromCode($oOAuthClient, $sCode);
					$oOAuthClient->SetAccessToken($oAccessToken);
					$aResult['status'] = 'success';
				}
				catch (IdentityProviderException $e) {
					$aResult['status'] = 'error';
					$aResult['error_description'] = $e->getMessage();
				}
			}
		} else {
			$aResult['status'] = 'error';
			$aResult['error_description'] = 'Redirect URL Format not recognized';
		}

		switch ($aResult['status']) {
			case 'success':
				cmdbAbstractObject::SetSessionMessage(
					$sClass,
					$sId,
					"$sClass:$sId:TokenCreated",
					$bIsCreation ? Dict::S('itop-oauth-client:Message:TokenCreated') : Dict::S('itop-oauth-client:Message:TokenRecreated'),
					WebPage::ENUM_SESSION_MESSAGE_SEVERITY_OK,
					1,
					true
				);
				if ($bIsCreation) {
					IssueLog::Info("Token created for $sClass:$sId");
				} else {
					IssueLog::Info("Token recreated for $sClass:$sId");
				}
				break;

			case 'error':
				cmdbAbstractObject::SetSessionMessage(
					$sClass,
					$sId,
					"$sClass:$sId:TokenError",
					$aResult['error_description'] ?? Dict::S('itop-oauth-client:Message:TokenError'),
					WebPage::ENUM_SESSION_MESSAGE_SEVERITY_ERROR,
					1,
					true
				);
				IssueLog::Error("Token creation failed for $sClass:$sId", null, empty($aQuery) ? $aResult : $aQuery);
				break;
		}

		$aResult['data'] = utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$sId";

		$this->DisplayJSONPage($aResult);
	}

}
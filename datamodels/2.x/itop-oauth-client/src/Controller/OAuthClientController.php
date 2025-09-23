<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use IssueLog;
use MetaModel;
use utils;

class OAuthClientController extends Controller
{
	const LOG_CHANNEL = 'OAuth';

	public function OperationCreateMailbox()
	{
		$aParams = [];

		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("CreateMailbox for $sClass::$sId", self::LOG_CHANNEL);

		$oOAuthClient = MetaModel::GetObject($sClass, $sId);
		$sLogin = $oOAuthClient->Get('name');
		$sDefaultServer = $oOAuthClient->GetDefaultMailServer();
		$sDefaultPort = $oOAuthClient->GetDefaultMailServerPort();

		$aParams['sURL'] = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class=MailInboxOAuth'.
			'&default[mailbox]=INBOX'.
			'&default[server]='.$sDefaultServer.
			'&default[port]='.$sDefaultPort.
			'&default[oauth_client_id]='.$sId.
			'&default[login]='.$sLogin;

		$this->DisplayPage($aParams);
	}
}
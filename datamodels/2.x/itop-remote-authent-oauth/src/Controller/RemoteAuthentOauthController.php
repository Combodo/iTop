<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\RemoteAuthentOAuth\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use IssueLog;
use MetaModel;
use utils;

class RemoteAuthentOauthController extends Controller
{
	const LOG_CHANNEL = 'OAuth';

	public function OperationCreateMailbox()
	{
		$aParams = [];

		$sClass = utils::ReadParam('class');
		$sId = utils::ReadParam('id');

		IssueLog::Debug("CreateMailbox for $sClass::$sId", self::LOG_CHANNEL);

		$oRemoteAuthentOAuth = MetaModel::GetObject($sClass, $sId);
		$sLogin = $oRemoteAuthentOAuth->Get('name');
		$sDefaultServer = $oRemoteAuthentOAuth->GetDefaultMailServer();
		$sDefaultPort = $oRemoteAuthentOAuth->GetDefaultMailServerPort();

		$aParams['sURL'] = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class=MailInboxOAuth'.
			'&default[protocol]=imap'.
			'&default[mailbox]=INBOX'.
			'&default[server]='.$sDefaultServer.
			'&default[port]='.$sDefaultPort.
			'&default[remote_authent_oauth_id]='.$sId.
			'&default[login]='.$sLogin;

		$this->DisplayPage($aParams);
	}
}
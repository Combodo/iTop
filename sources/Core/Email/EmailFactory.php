<?php

namespace Combodo\iTop\Core\Email;

use EMail;
use EMailLaminas;
use EmailSwiftMailer;
use utils;

class EmailFactory
{
	public static function GetMailer(EMail $oEMail)
	{
		$sTransport = utils::GetConfig()->Get('email_transport');
		if ($sTransport == 'SMTP_OAuth') {
			return EMailLaminas::GetMailer($oEMail);
		}

		return EmailSwiftMailer::GetMailer($oEMail);
	}
}
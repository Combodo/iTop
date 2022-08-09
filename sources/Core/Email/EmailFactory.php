<?php

namespace Combodo\iTop\Core\Email;

use EMailLaminas;
use EmailSwiftMailer;
use utils;

class EmailFactory
{
	public static function GetMailer()
	{
		$sTransport = utils::GetConfig()->Get('email_transport');
		if ($sTransport == 'SMTP_OAuth') {
			return EMailLaminas::GetMailer();
		}

		return EmailSwiftMailer::GetMailer();
	}
}
<?php

namespace Combodo\iTop\Core\Email;

class EmailFactory
{
	public static function GetMailer()
	{
		return EMailSymfony::GetMailer();
	}
}
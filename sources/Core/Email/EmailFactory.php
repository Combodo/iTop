<?php

namespace Combodo\iTop\Core\Email;

use Combodo\iTop\Core\Email\EMailLaminas;

class EmailFactory
{
	public static function GetMailer()
	{
		return EMailLaminas::GetMailer();
	}
}
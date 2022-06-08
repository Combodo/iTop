<?php

namespace Combodo\iTop\Core\Email;

use EMailLaminas;

class EmailFactory
{
	public static function GetMailer()
	{
		return EMailLaminas::GetMailer();
	}
}
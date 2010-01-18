<?php

/**
 * Send an mail (for notification, testing,... purposes)
 * #@# TODO - replace by a more sophisticated mean (and update the prototype) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class EMail
{
	protected $m_sBody;
	protected $m_sSubject;
	protected $m_sTo;
	protected $m_aHeaders; // array of key=>value

	public function __construct()
	{
		$this->m_sTo = '';
		$this->m_sSubject = '';
		$this->m_sBody = '';
		$this->m_aHeaders = array();
	}


	// Errors management : not that simple because we need that function to be
	// executed in the background, while making sure that any issue would be reported clearly
	protected $m_aMailErrors; //array of strings explaining the issues

	public function mail_error_handler($errno, $errstr, $errfile, $errline)
	{
		$sCleanMessage= str_replace("mail() [<a href='function.mail'>function.mail</a>]: ", "", $errstr);
		$this->m_aMailErrors[] = $sCleanMessage;
	}


	// returns a list of issues if any
	public function Send()
	{
		$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		foreach ($this->m_aHeaders as $sKey => $sValue)
		{
			$sHeaders .= "$sKey: $sValue\r\n";
		}

		// Under Windows (not yet proven for Linux/PHP) mail may issue a warning
		// that I could not mask (tried error_reporting(), etc.)
		$this->m_aMailErrors = array();
		set_error_handler(array($this, 'mail_error_handler'));
		$bRes = mail
		(
			$this->m_sTo,
			$this->m_sSubject,
			$this->m_sBody,
			$sHeaders
		);
		restore_error_handler();
		if (!$bRes && empty($this->m_aMailErrors))
		{
			$this->m_aMailErrors[] = 'Unknown reason';
		}
		return $this->m_aMailErrors;
	}

	protected function AddToHeader($sKey, $sValue)
	{
		if (strlen($sValue) > 0)
		{
			$this->m_aHeaders[$sKey] = $sValue;
		}
	}

	public function SetBody($sBody)
	{
		$this->m_sBody = $sBody;
	}

	public function SetSubject($aSubject)
	{
		$this->m_sSubject = $aSubject;
	}

	public function SetRecipientTO($sAddress)
	{
		$this->m_sTo = $sAddress;
	}

	public function SetRecipientCC($sAddress)
	{
		$this->AddToHeader('Cc', $sAddress);
	}

	public function SetRecipientBCC($sAddress)
	{
		$this->AddToHeader('Bcc', $sAddress);
	}

	public function SetRecipientFrom($sAddress)
	{
		$this->AddToHeader('From', $sAddress);

		// This is required on Windows because otherwise I would get the error
		// "sendmail_from" not set in php.ini" even if it is correctly working
		// (apparently, once it worked the SMTP server won't claim anymore for it)
		ini_set("sendmail_from", $sAddress);
	}

	public function SetRecipientReplyTo($sAddress)
	{
		$this->AddToHeader('Reply-To', $sAddress);
	}

}

?>

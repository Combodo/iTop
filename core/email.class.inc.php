<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Send an mail (for notification, testing,... purposes)
 * #@# TODO - replace by a more sophisticated mean (and update the prototype) 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

define ('EMAIL_SEND_OK', 0);
define ('EMAIL_SEND_PENDING', 1);
define ('EMAIL_SEND_ERROR', 2);


class EMail
{
	protected $m_sBody;
	protected $m_sSubject;
	protected $m_sTo;
	protected $m_aHeaders; // array of key=>value

	public function __construct($sTo = '', $sSubject = '', $sBody = '', $aHeaders = array())
	{
		$this->m_sTo = $sTo;
		$this->m_sSubject = $sSubject;
		$this->m_sBody = $sBody;
		$this->m_aHeaders = $aHeaders;
	}

	// Errors management : not that simple because we need that function to be
	// executed in the background, while making sure that any issue would be reported clearly
	protected $m_aMailErrors; //array of strings explaining the issues

	public function mail_error_handler($errno, $errstr, $errfile, $errline)
	{
		$sCleanMessage= str_replace("mail() [<a href='function.mail'>function.mail</a>]: ", "", $errstr);
		$this->m_aMailErrors[] = $sCleanMessage;
	}

  	protected function SendAsynchronous(&$aIssues, $oLog = null)
	{
		try
		{
			AsyncSendEmail::AddToQueue($this->m_sTo, $this->m_sSubject, $this->m_sBody, $this->m_aHeaders, $oLog);
		}
		catch(Exception $e)
		{
			$aIssues = array($e->GetMessage());
			return EMAIL_SEND_ERROR;
		}
		$aIssues = array();
		return EMAIL_SEND_PENDING;
	}

	protected function SendSynchronous(&$aIssues, $oLog = null)
	{
		$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
		// ! the case is important for MS-Outlook
		$sHeaders .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
		$sHeaders .= 'Content-Transfer-Encoding: 8bit' . "\r\n";
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
		if (count($this->m_aMailErrors) > 0)
		{
			$aIssues = $this->m_aMailErrors;
			return EMAIL_SEND_ERROR;
		}
		else
		{
			$aIssues = array();
			return EMAIL_SEND_OK;
		}
	}

	public function Send(&$aIssues, $bForceSynchronous = false, $oLog = null)
	{	
		if ($bForceSynchronous)
		{
			return $this->SendSynchronous($aIssues, $oLog);
		}
		else
		{
			$bConfigASYNC = MetaModel::GetConfig()->Get('email_asynchronous');
			if ($bConfigASYNC)
			{
				return $this->SendAsynchronous($aIssues, $oLog);
			}
			else
			{
				return $this->SendSynchronous($aIssues, $oLog);
			}
		}
	}

	protected function AddToHeader($sKey, $sValue)
	{
		if (strlen($sValue) > 0)
		{
			$this->m_aHeaders[$sKey] = $sValue;
		}
	}
	
	public function SetReferences($sReferences)
	{
		$this->AddToHeader('References', $sReferences);
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
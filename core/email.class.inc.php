<?php
// Copyright (C) 2010-2022 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Send an email (abstraction for synchronous/asynchronous modes)
 *
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Core\Email\EmailFactory;
use Combodo\iTop\Core\Email\iEMail;

Swift_Preferences::getInstance()->setCharset('UTF-8');


define ('EMAIL_SEND_OK', 0);
define ('EMAIL_SEND_PENDING', 1);
define ('EMAIL_SEND_ERROR', 2);

class EMail implements iEMail
{
	protected $oMailer;

	// Serialization formats
	const ORIGINAL_FORMAT = 1; // Original format, consisting in serializing the whole object, inculding the Swift Mailer's object.
	// Did not work with attachements since their binary representation cannot be stored as a valid UTF-8 string
	const FORMAT_V2 = 2; // New format, only the raw data are serialized (base64 encoded if needed)

	public function __construct()
	{
		$this->oMailer = EmailFactory::GetMailer();
	}

	/**
	 * Custom serialization method
	 * No longer use the brute force "serialize" method since
	 * 1) It does not work with binary attachments (since they cannot be stored in a UTF-8 text field)
	 * 2) The size tends to be quite big (sometimes ten times the size of the email)
	 */
	public function SerializeV2()
	{
		return $this->oMailer->SerializeV2();
	}

	/**
	 * Custom de-serialization method
	 *
	 * @param string $sSerializedMessage The serialized representation of the message
	 *
	 * @return \Email
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Symfony\Component\CssSelector\Exception\SyntaxErrorException
	 */
	static public function UnSerializeV2($sSerializedMessage)
	{
		return EmailFactory::GetMailer()::UnSerializeV2($sSerializedMessage);
	}

	public function Send(&$aIssues, $bForceSynchronous = false, $oLog = null)
	{
		return $this->oMailer->Send($aIssues, $bForceSynchronous, $oLog);
	}

	public function AddToHeader($sKey, $sValue)
	{
		$this->oMailer->AddToHeader($sKey, $sValue);
	}

	public function SetMessageId($sId)
	{
		$this->oMailer->SetMessageId($sId);
	}

	public function SetReferences($sReferences)
	{
		$this->oMailer->SetReferences($sReferences);
	}

	/**
	 * Set the "In-Reply-To" header to allow emails to group as a conversation in modern mail clients (GMail, Outlook 2016+, ...)
	 *
	 * @link https://en.wikipedia.org/wiki/Email#Header_fields
	 *
	 * @param string $sMessageId
	 *
	 * @since 3.0.1 N°4849
	 */
	public function SetInReplyTo(string $sMessageId)
	{
		$this->AddToHeader('In-Reply-To', $sMessageId);
	}

	public function SetBody($sBody, $sMimeType = 'text/html', $sCustomStyles = null)
	{
		$this->oMailer->SetBody($sBody, $sMimeType, $sCustomStyles);
	}

	public function AddPart($sText, $sMimeType = 'text/html')
	{
		$this->oMailer->AddPart($sText, $sMimeType);
	}

	public function AddAttachment($data, $sFileName, $sMimeType)
	{
		$this->oMailer->AddAttachment($data, $sFileName, $sMimeType);
	}

	public function SetSubject($sSubject)
	{
		$this->oMailer->SetSubject($sSubject);
	}

	public function GetSubject()
	{
		return $this->oMailer->GetSubject();
	}

	public function SetRecipientTO($sAddress)
	{
		$this->oMailer->SetRecipientTO($sAddress);
	}

	public function GetRecipientTO($bAsString = false)
	{
		return $this->oMailer->GetRecipientTO($bAsString);
	}

	public function SetRecipientCC($sAddress)
	{
		$this->oMailer->SetRecipientCC($sAddress);
	}

	public function SetRecipientBCC($sAddress)
	{
		$this->oMailer->SetRecipientBCC($sAddress);
	}

	public function SetRecipientFrom($sAddress, $sLabel = '')
	{
		$this->oMailer->SetRecipientFrom($sAddress, $sLabel);
	}

	public function SetRecipientReplyTo($sAddress, $sLabel = '')
	{
		$this->oMailer->SetRecipientReplyTo($sAddress);
	}

}
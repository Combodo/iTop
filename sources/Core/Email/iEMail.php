<?php

namespace Combodo\iTop\Core\Email;

interface iEMail
{
	public function SerializeV2();

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
	static public function UnSerializeV2($sSerializedMessage);

	public function Send(&$aIssues, $bForceSynchronous = false, $oLog = null);

	public function AddToHeader($sKey, $sValue);

	public function SetMessageId($sId);

	public function SetReferences($sReferences);

	public function SetBody($sBody, $sMimeType = 'text/html', $sCustomStyles = null);

	public function AddPart($sText, $sMimeType = 'text/html');

	public function AddAttachment($data, $sFileName, $sMimeType);

	public function SetSubject($sSubject);

	public function GetSubject();

	public function SetRecipientTO($sAddress);

	public function GetRecipientTO($bAsString = false);

	public function SetRecipientCC($sAddress);

	public function SetRecipientBCC($sAddress);

	public function SetRecipientFrom($sAddress, $sLabel = '');

	public function SetRecipientReplyTo($sAddress, $sLabel = '');

}
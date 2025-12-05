<?php

namespace Combodo\iTop\Core\Email\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;

/**
* Transport that uses PHP's mail() function to send emails
*/
class SymfonyPHPMailTransport extends AbstractTransport
{
	/**
	 * @param Email $oRawEmail
	 *
	 * @return string
	 */
	public function prepareTo(Email $oRawEmail): string
	{
		$oHeaders = $oRawEmail->getHeaders();

		return $oHeaders->get('To')->getBodyAsString();
	}

	/**
	 * @param Email $oRawEmail
	 *
	 * @return string
	 */
	private function prepareSubject(Email $oRawEmail): string
	{
		$oHeaders = $oRawEmail->getHeaders();
		return $oHeaders->get('Subject')->getBodyAsString();
	}

	/**
	 * @param Email $oRawEmail
	 *
	 * @return string
	 */
	public function prepareBody(Email $oRawEmail): string
	{
		return $oRawEmail->getBody() ? $oRawEmail->getBody()->bodyToString() : '';
	}

	/**
	 * @param Email $oRawEmail
	 *
	 * @return string
	 */
	public function prepareHeaders(Email $oRawEmail): string
	{
		$oHeaders = $oRawEmail->getPreparedHeaders();
		// Render all headers except "To" (mail() has a dedicated argument for that), including body headers
		$sHeaders = '';
		foreach ($oHeaders->all() as $header) {
			if (strtolower($header->getName()) !== 'to' && strtolower($header->getName()) !== 'subject') {
				$sHeaders .= $header->toString()."\r\n";
			}
		}

		$oBodyHeader = $oRawEmail->getBody()->getPreparedHeaders();
		foreach ($oBodyHeader->all() as $header) {
			$sHeaders .= $header->toString()."\r\n";
		}

		// Remove trailing line break
		$sHeaders = rtrim($sHeaders, "\r\n");
		return $sHeaders;
	}

	protected function doSend(SentMessage $message): void
	{
		$oRawEmail = $message->getOriginalMessage();

		if (!$oRawEmail instanceof Email) {
			throw new \LogicException('SymfonyPHPMailTransport only supports Email instances.');
		}

		$sTo = $this->prepareTo($oRawEmail);
		$sSubject = $this->prepareSubject($oRawEmail);
		$sBody = $this->prepareBody($oRawEmail);
		$sHeaders = $this->prepareHeaders($oRawEmail);

		$success = mail($sTo, $sSubject, $sBody, $sHeaders);

		if (!$success) {
			throw new \RuntimeException('The mail() function failed to send the message. Check server mail configuration.');
		}
	}

	public function __toString(): string
	{
		return 'phpmail://default';
	}
}

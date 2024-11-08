<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use EMail;
use utils;

class EMailTest extends ItopTestCase {

	/**
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @covers Email::SetBody()
	 * @covers Email::Send()
	 */
	public function testCheckHeadersOnSendEmail(): void
	{
		$oConfig = utils::GetConfig();
		$sCurrentEmailTransport = $oConfig->Get('email_transport');
		$sCurrentEmailAsync = $oConfig->Get('email_asynchronous');
		
		// Set our email transport to file, so we can read it after
		$oConfig->Set('email_transport', 'LogFile');
		$oConfig->Set('email_asynchronous', false);

		$oEmail = new Email();
		$oEmail->SetRecipientTO('email@email.com');
		$oEmail->SetRecipientFrom('email2@email2.com');
		$oEmail->SetSubject('dummy subject');
		$oEmail->SetBody('dummy body');
		
		// Send the mail and check if there's any issue
		$aIssues = [];
		$oEmail->Send($aIssues);
		$this->assertEmpty($aIssues);
		
		// Check if our charset is correctly set
		// We know this file may be used by other future test, but as we can't configure output filename, it is what it is
		$sEmailContent = file_get_contents(APPROOT.'log/mail.log');
		$this->assertStringContainsString('charset="UTF-8"', $sEmailContent);
		
		// Set our previous email transport value back, so it doesn't affect other tests
		$oConfig->Set('email_transport', $sCurrentEmailTransport);
		$oConfig->Set('email_asynchronous', $sCurrentEmailAsync);
	}

	/**
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @covers Email::SetBody()
	 * @covers Email::Send()
	 */
	public function testCheckPartsHeadersOnSendEmailWithAttachment(): void
	{
		$oConfig = utils::GetConfig();
		$sCurrentEmailTransport = $oConfig->Get('email_transport');
		$sCurrentEmailAsync = $oConfig->Get('email_asynchronous');

		// Set our email transport to file, so we can read it after
		$oConfig->Set('email_transport', 'LogFile');
		$oConfig->Set('email_asynchronous', false);

		$oEmail = new Email();
		$oEmail->SetRecipientTO('email@email.com');
		$oEmail->SetRecipientFrom('email2@email2.com');
		$oEmail->SetSubject('dummy subject');
		$oEmail->SetBody('dummy body', 'text/plain');
		$oEmail->AddAttachment('Dummy attachment', 'attachment.txt', 'text/plain');

		// Send the mail and check if there's any issue
		$aIssues = [];
		$oEmail->Send($aIssues);
		$this->assertEmpty($aIssues);

		// Check if our charset is correctly set
		// We know this file may be used by other future test, but as we can't configure output filename, it is what it is
		$sEmailContent = file_get_contents(APPROOT.'log/mail.log');
		$this->assertStringContainsString('Content-Type: text/plain; charset=UTF-8', $sEmailContent);

		// Set our previous email transport value back, so it doesn't affect other tests
		$oConfig->Set('email_transport', $sCurrentEmailTransport);
		$oConfig->Set('email_asynchronous', $sCurrentEmailAsync);
	}
}
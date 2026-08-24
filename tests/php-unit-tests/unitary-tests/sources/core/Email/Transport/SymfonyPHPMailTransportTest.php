<?php

use Combodo\iTop\Core\Email\Transport\SymfonyPHPMailTransport;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\Mime\Email;

class SymfonyPHPMailTransportTest extends ItopTestCase
{
	public function testPrepareMustNotThrowErrorWhenToHeaderIsMissing(): void
	{
		$oEmail = new Email()
			->from('sender@example.com')
			->cc('cc1@example.com', 'cc2@example.com')
			->text('Body');

		$oTransport = new SymfonyPHPMailTransport();

		$oTransport->prepareTo($oEmail);
		$this->assertTrue(true); // if no error is thrown, the test passes

	}
}

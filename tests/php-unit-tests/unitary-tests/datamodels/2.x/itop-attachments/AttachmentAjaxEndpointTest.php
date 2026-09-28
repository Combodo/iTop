<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopAttachment;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class AttachmentAjaxEndpointTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;
	private const AUTHENTICATION_PASSWORD = 'tagada-Secret,007';

	protected function setUp(): void
	{
		parent::setUp();

		$this->BackupConfiguration();
		$this->AddLoginModeAndSaveConfiguration('url');
	}

	/**
	 * @dataProvider AjaxEndpointAccessProvider
	 */
	public function testAjaxEndpointAccess(string $sProfile, int $iExpectedHttpCode): void
	{
		$sLogin = 'user-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles[$sProfile], self::AUTHENTICATION_PASSWORD);

		$iHttpCode = $this->CallAttachmentEndpointAs($sLogin);

		$this->assertSame($iExpectedHttpCode, $iHttpCode);
	}

	public function AjaxEndpointAccessProvider(): array
	{
		return [
			'console user' => ['Service Desk Agent', 200],
			'portal user' => ['Portal user', 302], // redirect to portal
		];
	}

	private function CallAttachmentEndpointAs(string $sLogin): int
	{
		$this->CallItopUri(
			'env-production/itop-attachments/ajax.itop-attachment.php?operation=add&'.http_build_query([
				'auth_user' => $sLogin,
				'auth_pwd' => self::AUTHENTICATION_PASSWORD,
			]),
			[],
			[
				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
				CURLOPT_POST => 0,
			],
			true
		);

		return $this->aLastCurlGetInfo['http_code'];
	}
}

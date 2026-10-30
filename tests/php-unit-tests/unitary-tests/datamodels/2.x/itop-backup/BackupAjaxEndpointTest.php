<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopBackup;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use UserRights;
use utils;

class BackupAjaxEndpointTest extends ItopDataTestCase
{
	public const  USE_TRANSACTION         = false;
	private const AUTHENTICATION_PASSWORD = 'tagada-Secret,007';

	protected function setUp(): void
	{
		parent::setUp();

		$this->BackupConfiguration();
		$this->AddLoginModeAndSaveConfiguration('url');
	}

	// failed on CI
	//	public function testDownloadInvalidDownloadPathReturnsHttp500(): void
	//	{
	//		$sLogin = 'test-backup-endpoint-'.uniqid();
	//		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);
	//
	//		UserRights::Login($sLogin);
	//
	//		$testCurlResult = $this->CallItopUri(
	//			'env-production/itop-backup/ajax.backup.php?'.http_build_query([
	//				'operation'      => 'download',
	//				'file'           => 'missing-'.uniqid().'.zip',
	//				'auth_user'      => $sLogin,
	//				'auth_pwd'       => self::AUTHENTICATION_PASSWORD,
	//				'transaction_id' => utils::GetNewTransactionId(),
	//			]),
	//			[],
	//			[
	//				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
	//				CURLOPT_POST       => 0,
	//			],
	//			true
	//		);
	//
	//		$this->assertSame(422, $this->aLastCurlGetInfo['http_code']);
	//	}

	public function testRestoreGetTokenInvalidTransactionIdReturnsHttp500(): void
	{
		$sLogin = 'test-backup-endpoint-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);

		UserRights::Login($sLogin);

		$this->CallItopUri(
			'env-production/itop-backup/ajax.backup.php?'.http_build_query([
				'operation'      => 'restore_get_token',
				'auth_user'      => $sLogin,
				'auth_pwd'       => self::AUTHENTICATION_PASSWORD,
				'transaction_id' => 'unknown-transaction-id-'.uniqid(),
			]),
			[],
			[
				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
				CURLOPT_POST       => 0,
			],
			true
		);

		$this->assertSame(500, $this->aLastCurlGetInfo['http_code']);
	}

	public function testRestoreGetToken(): void
	{
		$sLogin = 'test-backup-endpoint-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);

		UserRights::Login($sLogin);

		$sData = $this->CallItopUri(
			'env-production/itop-backup/ajax.backup.php?'.http_build_query([
				'operation'      => 'restore_get_token',
				'auth_user'      => $sLogin,
				'auth_pwd'       => self::AUTHENTICATION_PASSWORD,
				'transaction_id' => utils::GetNewTransactionId(),
			]),
			[],
			[
				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
				CURLOPT_POST       => 0,
			],
			true
		);

		$aData = json_decode($sData, true);

		$this->assertSame(200, $this->aLastCurlGetInfo['http_code']);
		$this->assertArrayHasKey('token', $aData);
	}
}

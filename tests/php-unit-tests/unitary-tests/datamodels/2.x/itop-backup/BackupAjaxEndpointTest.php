<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopBackup;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
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

	public function testDownloadInvalidDownloadPathReturnsHttp500(): void
	{
		$sLogin = 'test-backup-endpoint-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);

		UserRights::Login($sLogin);

		$sData = $this->CallItopUri(
			'env-production/itop-backup/ajax.backup.php?'.http_build_query([
				'operation'      => 'download',
				'file'           => 'missing-'.uniqid().'.zip',
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

		$this->assertSame(422, $this->aLastCurlGetInfo['http_code'], __METHOD__.':'.$sData);
	}

	public function testRestoreGetTokenInvalidTransactionIdReturnsHttp500(): void
	{
		$sLogin = 'test-backup-endpoint-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);

		UserRights::Login($sLogin);

		$sData = $this->CallItopUri(
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

		$this->assertSame(500, $this->aLastCurlGetInfo['http_code'], __METHOD__.':'.$sData);
	}

	public function testRestoreGetToken(): void
	{
		$sLogin = 'test-backup-endpoint-'.uniqid();
		$this->CreateUser($sLogin, self::$aURP_Profiles['Administrator'], self::AUTHENTICATION_PASSWORD);

		UserRights::Login($sLogin);

		$sBackupDirectory = APPROOT.'data/backups/';
		if (!is_dir($sBackupDirectory)) {
			$this->assertTrue(mkdir($sBackupDirectory, 0770, true) || is_dir($sBackupDirectory));
		}
		$sBackupFile = 'backup-'.uniqid().'.zip';
		$sBackupPath = $sBackupDirectory.$sBackupFile;
		$sTokenFile = null;
		$oArchive = new \ZipArchive();
		$bArchiveOpen = false;

		try {
			$bArchiveOpen = $oArchive->open($sBackupPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
			$this->assertTrue($bArchiveOpen);
			$this->assertTrue($oArchive->addFromString('itop-dump.sql', '-- Test backup'));
			$sConfigContents = file_get_contents(MetaModel::GetConfig()->GetLoadedFile());
			$this->assertNotFalse($sConfigContents);
			$this->assertTrue($oArchive->addFromString('config-itop.php', $sConfigContents));
			$this->assertTrue($oArchive->close());
			$bArchiveOpen = false;

			$sData = $this->CallItopUri(
				'env-production/itop-backup/ajax.backup.php?'.http_build_query([
					'operation'      => 'restore_get_token',
					'auth_user'      => $sLogin,
					'auth_pwd'       => self::AUTHENTICATION_PASSWORD,
					'file'           => $sBackupFile,
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
			$this->assertSame(200, $this->aLastCurlGetInfo['http_code'], __METHOD__.':'.$sData);
			$this->assertArrayHasKey('token', $aData);

			$sTokenFile = APPROOT.'data/restore.'.$aData['token'].'.tok';
		}
		finally {
			if ($bArchiveOpen) {
				$oArchive->close();
			}
			if (is_file($sBackupPath)) {
				unlink($sBackupPath);
			}
			if (($sTokenFile !== null) && is_file($sTokenFile)) {
				unlink($sTokenFile);
			}
		}
	}
}

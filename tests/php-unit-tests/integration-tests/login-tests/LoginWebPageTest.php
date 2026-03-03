<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;

class LoginWebPageTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

	public const PASSWORD = 'a209320P!ù;ralùqpi,pàcqi"nr';

	public function setUp(): void
	{
		parent::setUp();
		$this->BackupConfiguration();
		$sFolderPath = APPROOT.'env-production/extension-with-execution-policy';
		if (file_exists($sFolderPath)) {
			throw new Exception("Folder $sFolderPath already exists, please remove it before running the test");
		}
		mkdir($sFolderPath);
		$this->RecurseCopy(__DIR__.'/extension-with-execution-policy', $sFolderPath);

		$sFolderPath = APPROOT.'env-production/extension-without-execution-policy';
		if (file_exists($sFolderPath)) {
			throw new Exception("Folder $sFolderPath already exists, please remove it before running the test");
		}
		mkdir($sFolderPath);
		$this->RecurseCopy(__DIR__.'/extension-without-execution-policy', $sFolderPath);
	}
	public function tearDown(): void
	{
		parent::tearDown();
		$sFolderPath = APPROOT.'env-production/extension-with-execution-policy';
		if (file_exists($sFolderPath)) {
			$this->RecurseRmdir($sFolderPath);
		} else {
			throw new Exception("Folder $sFolderPath does not exist, it should have been created in setUp");
		}
		$sFolderPath = APPROOT.'env-production/extension-without-execution-policy';
		if (file_exists($sFolderPath)) {
			$this->RecurseRmdir($sFolderPath);
		} else {
			throw new Exception("Folder $sFolderPath does not exist, it should have been created in setUp");
		}
	}

	protected function GivenConfigFileAllowedLoginTypes($aAllowedLoginTypes): void
	{
		@chmod(MetaModel::GetConfig()->GetLoadedFile(), 0770);
		MetaModel::GetConfig()->SetAllowedLoginTypes($aAllowedLoginTypes);
		MetaModel::GetConfig()->WriteToFile();
		@chmod(MetaModel::GetConfig()->GetLoadedFile(), 0444);
	}

	/**
	 *
	 * @throws \Exception
	 */
	public function testInExecutionPolicyFile()
	{
		// generate random login
		$sUserLogin = 'user-'.date('YmdHis');
		$this->CreateUser($sUserLogin, self::$aURP_Profiles['Administrator'], self::PASSWORD);
		$this->GivenConfigFileAllowedLoginTypes(explode('|', 'form'));

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/CheckAnything.php",
			[
				'auth_user' => $sUserLogin,
				'auth_pwd' => self::PASSWORD,
			],
			[],
			true
		);

		$this->assertStringNotContainsString('<title>iTop login</title>', $sPageContent); // in execution policy file (in the module), login should not be proposed, file handle its own policy
	}

	public function testNotInExecutionPolicyFileWithForceLoginConf()
	{
		MetaModel::GetConfig()->Set('security.force_login_when_no_execution_policy', true);

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/AnotherFile.php",
		);

		$this->assertStringContainsString('<title>iTop login</title>', $sPageContent); // if itop is configured to force login when no execution policy, then login should be proposed since file is not in execution policy file
	}

	public function testNotInExecutionPolicyFileWithoutForceLoginConf()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/AnotherFile.php",
		);

		$this->assertStringNotContainsString('<title>iTop login</title>', $sPageContent); // by default (until N°9343) if no execution policy is defined, login is not forced
	}

}

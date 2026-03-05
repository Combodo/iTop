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
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/FileInExecutionPolicy.php",
			[],
			[],
			true
		);

		$this->assertStringNotContainsString('<title>iTop login</title>', $sPageContent, 'File listed in execution policy file (in the module), login should not be requested by exec, file handle its own policy');
	}

	public function testUserCanAccessAnyFile()
	{
		// generate random login
		$sUserLogin = 'user-'.date('YmdHis');
		$this->CreateUser($sUserLogin, self::$aURP_Profiles['Service Desk Agent'], self::PASSWORD);
		$this->GivenConfigFileAllowedLoginTypes(explode('|', 'form'));

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/FileNotInExecutionPolicy.php",
			[
				'auth_user' => $sUserLogin,
				'auth_pwd' => self::PASSWORD,
			],
			[],
			true
		);

		$this->assertStringContainsString('Yo', $sPageContent, 'Logged in user should access any file via exec.php even if the page isn\'t listed in execution policy');
	}

	public function testNoPolicyFileWithForceLoginConf()
	{
		MetaModel::GetConfig()->Set('security.force_login_when_no_authentication_policy', true);

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/FileNotInExecutionPolicy.php",
		);

		$this->assertStringContainsString('<title>iTop login</title>', $sPageContent, 'if itop is configured to force login when no execution policy, then login should be required even if there is no policy file');
	}

	public function testNoPolicyFileWithDefaultConfiguration()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-without-execution-policy&exec_page=src/Controller/File.php",
			[],
			[],
			true
		);

		$this->assertStringContainsString('Yo', $sPageContent, 'by default (until N°9343) if no execution policy is defined, not logged in persons should access pages');
	}

	public function testNotInExecutionPolicy()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/FileNotInExecutionPolicy.php",
			[],
			[],
			true
		);

		$this->assertStringContainsString('<title>iTop login</title>', $sPageContent, 'Since an execution policy is defined and file isn\'t listed in it, login should be required');
	}

	/**
	 * @dataProvider InExecutionPolicyFileWithAdminRequiredProvider
	 *
	 * @throws \Exception
	 */
	public function testInExecutionPolicyFileWithAdminRequired($iProfileId, $bShouldSeeForbiddenAdminPage)
	{
		// generate random login
		$sUserLogin = 'user-'.date('YmdHis');
		$this->CreateUser($sUserLogin, $iProfileId, self::PASSWORD);
		$this->GivenConfigFileAllowedLoginTypes(explode('|', 'form'));

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-execution-policy&exec_page=src/Controller/FileInExecutionPolicyAndAdminRequired.php",
			[
				'auth_user' => $sUserLogin,
				'auth_pwd' => self::PASSWORD,
			],
			[],
			true
		);
		$bShouldSeeForbiddenAdminPage ?
			$this->assertStringNotContainsString('<title>Access restricted to people having administrator privileges</title>', $sPageContent, 'Should prevent non admin user to access this page') : // in execution policy file (in the module), login should not be required, file handle its own policy
			$this->assertStringContainsString('Yo !', $sPageContent, 'Should execute the file and see its content since user has admin profile');

	}

	public function InExecutionPolicyFileWithAdminRequiredProvider()
	{
		return [
			'Administrator profile' => [
				self::$aURP_Profiles['Administrator'],
				'Should see forbidden admin page' => false,
			],
			'ReadOnly profile' => [
				self::$aURP_Profiles['Service Desk Agent'],
				'Should see forbidden admin page' => true,
			],
		];
	}
}

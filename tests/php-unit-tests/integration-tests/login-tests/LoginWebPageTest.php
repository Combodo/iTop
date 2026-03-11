<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use Exception;
use MetaModel;

class LoginWebPageTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

	public const PASSWORD = 'a209320P!ù;ralùqpi,pàcqi"nr';

	public function setUp(): void
	{
		parent::setUp();
		$sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
		$this->oConfig = new Config($sConfigPath);

		$this->BackupConfiguration();
		$sFolderPath = APPROOT.'env-production/extension-with-delegated-authentication-endpoints-list';
		if (file_exists($sFolderPath)) {
			throw new Exception("Folder $sFolderPath already exists, please remove it before running the test");
		}
		mkdir($sFolderPath);
		$this->RecurseCopy(__DIR__.'/extension-with-delegated-authentication-endpoints-list', $sFolderPath);

		$sFolderPath = APPROOT.'env-production/extension-without-delegated-authentication-endpoints-list';
		if (file_exists($sFolderPath)) {
			throw new Exception("Folder $sFolderPath already exists, please remove it before running the test");
		}
		mkdir($sFolderPath);
		$this->RecurseCopy(__DIR__.'/extension-without-delegated-authentication-endpoints-list', $sFolderPath);
	}
	public function tearDown(): void
	{
		parent::tearDown();
		$sFolderPath = APPROOT.'env-production/extension-with-delegated-authentication-endpoints-list';
		if (file_exists($sFolderPath)) {
			$this->RecurseRmdir($sFolderPath);
		} else {
			throw new Exception("Folder $sFolderPath does not exist, it should have been created in setUp");
		}
		$sFolderPath = APPROOT.'env-production/extension-without-delegated-authentication-endpoints-list';
		if (file_exists($sFolderPath)) {
			$this->RecurseRmdir($sFolderPath);
		} else {
			throw new Exception("Folder $sFolderPath does not exist, it should have been created in setUp");
		}
	}

	protected function GivenConfigFileAllowedLoginTypes($aAllowedLoginTypes): void
	{
		@chmod($this->oConfig->GetLoadedFile(), 0770);
		$this->oConfig->SetAllowedLoginTypes($aAllowedLoginTypes);
		$this->oConfig->WriteToFile($this->oConfig->GetLoadedFile());
		@chmod($this->oConfig->GetLoadedFile(), 0444);
	}

	/**
	 *
	 * @throws \Exception
	 */
	public function testInDelegatedAuthenticationEndpoints()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-delegated-authentication-endpoints-list&exec_page=src/Controller/FileInDelegatedAuthenticationEndpointsList.php",
			[],
			[],
			true
		);

		$this->assertStringNotContainsString('<title>iTop login</title>', $sPageContent, 'File listed in delegated authentication endpoints list (in the module), login should not be requested by exec.');
	}

	public function testUserCanAccessAnyFile()
	{
		// generate random login
		$sUserLogin = 'user-'.date('YmdHis');
		$this->CreateUser($sUserLogin, self::$aURP_Profiles['Service Desk Agent'], self::PASSWORD);
		$this->GivenConfigFileAllowedLoginTypes(explode('|', 'form'));

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-delegated-authentication-endpoints-list&exec_page=src/Controller/FileNotInDelegatedAuthenticationEndpointsList.php",
			[
				'auth_user' => $sUserLogin,
				'auth_pwd' => self::PASSWORD,
			],
			[],
			true
		);

		$this->assertStringContainsString('Yo', $sPageContent, 'Logged in user should access any file via exec.php even if the page isn\'t listed in delegated authentication endpoints list');
	}

	public function testWithoutDelegatedAuthenticationEndpointsListWithForceLoginConf()
	{
		@chmod($this->oConfig->GetLoadedFile(), 0770);
		$this->oConfig->Set('security.force_login_when_no_delegated_authentication_endpoints_list', true);
		$this->oConfig->WriteToFile();
		@chmod($this->oConfig->GetLoadedFile(), 0444);
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-without-delegated-authentication-endpoints-list&exec_page=src/Controller/File.php",
		);

		$this->assertStringContainsString('<title>iTop login</title>', $sPageContent, 'if itop is configured to force login when no there is no delegated authentication endpoints list, then login should be required.');
	}

	public function testWithoutDelegatedAuthenticationEndpointsListWithDefaultConfiguration()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-without-delegated-authentication-endpoints-list&exec_page=src/Controller/File.php",
			[],
			[],
			true
		);

		$this->assertStringContainsString('Yo', $sPageContent, 'by default (until N°9343) if no delegated authentication endpoints list is defined, not logged in persons should access pages');
	}

	public function testNotInDelegatedAuthenticationEndpointsList()
	{
		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-delegated-authentication-endpoints-list&exec_page=src/Controller/FileNotInDelegatedAuthenticationEndpointsList.php",
			[],
			[],
			true
		);

		$this->assertStringContainsString('<title>iTop login</title>', $sPageContent, 'Since an delegated authentication endpoints list is defined and file isn\'t listed in it, login should be required');
	}

	/**
	 * @dataProvider InDelegatedAuthenticationEndpointsWithAdminRequiredProvider
	 *
	 * @throws \Exception
	 */
	public function testInDelegatedAuthenticationEndpointsWithAdminRequired($iProfileId, $bShouldSeeForbiddenAdminPage)
	{
		// generate random login
		$sUserLogin = 'user-'.date('YmdHis');
		$this->CreateUser($sUserLogin, $iProfileId, self::PASSWORD);
		$this->GivenConfigFileAllowedLoginTypes(explode('|', 'form'));

		$sPageContent = $this->CallItopUri(
			"pages/exec.php?exec_module=extension-with-delegated-authentication-endpoints-list&exec_page=src/Controller/FileInDelegatedAuthenticationEndpointsListAndAdminRequired.php",
			[
				'auth_user' => $sUserLogin,
				'auth_pwd' => self::PASSWORD,
			],
			[],
			true
		);
		$bShouldSeeForbiddenAdminPage ?
			$this->assertStringContainsString('Access restricted to people having administrator privileges', $sPageContent, 'Should prevent non admin user to access this page') : // in delegated authentication endpoints list (in the module), login should not be required
			$this->assertStringContainsString('Yo !', $sPageContent, 'Should execute the file and see its content since user has admin profile');

	}

	public function InDelegatedAuthenticationEndpointsWithAdminRequiredProvider()
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

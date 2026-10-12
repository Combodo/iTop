<?php

namespace Combodo\iTop\Test\UnitTest\Pages;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Dict;
use UserLocal;
use UserRequest;

class AjaxRenderTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;
	public const AUTHENTICATION_PASSWORD = "tagada-Secret,007";

	private static string $sLogin;
	private static int $iTicketId;

	protected function setUp(): void
	{
		parent::setUp();
		$this->BackupConfiguration();
		$this->oiTopConfig->Set('log_level_min', 'Error');
		$this->oiTopConfig->Set('login_debug', true);

		$this->CreateTestOrganization();

		// Add URL authentication mode
		$this->AddLoginModeAndSaveConfiguration('url');

		// Create ticket
		$description = date('dmY H:i:s');
		$oTicket = $this->createObject('UserRequest', [
			'org_id' => $this->getTestOrgId(),
			"title" => "Houston, got a problem",
			"description" => $description,
		]);
		self::$iTicketId = $oTicket->GetKey();
	}

	// Test that if a user with the right permissions tries to acquire the lock on a ticket, it succeeds and returns the correct success message
	public function testAcquireLockSuccess(): void
	{
		$sOutput = $this->CreateSupportAgentUserAndAcquireLock();
		$this->assertStringContainsString('"success":true', $sOutput);
	}

	// Test that if a user tries to acquire the lock on an object that does not exist, it fails and logs the correct error message
	public function testAcquireLockFailsIfObjectDoesNotExist(): void
	{
		// Create a user with Support Agent Profile
		$this->CreateUserWithProfile(self::$aURP_Profiles['Support Agent']);

		// Try to acquire the lock on a non-existent object
		$sOutput = $this->AcquireLockAsUser(self::$sLogin, 99999999);

		// The output should indicate a fatal error because we hide the existence of the object when it does not exist or is not accessible by the user
		$this->assertEquals(Dict::S('UI:PageTitle:FatalError'), $sOutput);

		// Check that the error log contains the expected error message about the object not existing
		$sLastErrorLogLines = $this->GetErrorLogLastLines(APPROOT.'log/error.log', 10);
		$this->assertStringContainsString(Dict::S('UI:ObjectDoesNotExist'), $sLastErrorLogLines);
	}

	// Test that if a user tries to acquire the lock on an object for which they don't have modification rights, it fails and logs the correct error message
	public function testAcquireLockFailsIfUserHasNoModifyRights(): void
	{
		// Create a user with a profile without modification rights on UserRequest
		$this->CreateUserWithProfile(self::$aURP_Profiles['Configuration Manager']);

		// Try to acquire the lock on the ticket
		$sOutput = $this->AcquireLockAsUser(self::$sLogin, self::$iTicketId);

		// The output should indicate a fatal error because we hide the existence of the object when it does not exist or is not accessible by the user
		$this->assertEquals(Dict::S('UI:PageTitle:FatalError'), $sOutput);

		// The user should not have the rights to acquire the lock, and an error should be logged
		$sLastErrorLogLines = $this->GetErrorLogLastLines(APPROOT.'log/error.log', 10);
		$this->assertStringContainsString(Dict::S('UI:ObjectDoesNotExist'), $sLastErrorLogLines);
	}

	// Test that if a user tries to acquire the lock on an object that belongs to another organization, it fails and logs the correct error message
	public function testAcquireLockFailsIfObjectInOtherOrg(): void
	{
		// Create an organization and a ticket in this organization
		$iOtherOrgId = $this->createObject('Organization', ['name' => 'OtherOrg'])->GetKey();
		$oTicket = $this->createObject('UserRequest', [
			'org_id' => $iOtherOrgId,
			'title' => 'Ticket autre org',
			'description' => 'Test',
		]);

		// Create a user who only has access to the main test organization
		$oUser = $this->CreateUserWithProfile(self::$aURP_Profiles['Support Agent']);
		$oAllowedOrgList = $oUser->Get('allowed_org_list');
		$oUserOrg = \MetaModel::NewObject('URP_UserOrg', ['allowed_org_id' => $this->getTestOrgId()]);
		$oAllowedOrgList->AddItem($oUserOrg);
		$oUser->Set('allowed_org_list', $oAllowedOrgList);
		$oUser->DBWrite();

		// Try to acquire the lock on the ticket of the other organization
		$sOutput = $this->AcquireLockAsUser(self::$sLogin, $oTicket->GetKey());

		// The output should indicate a fatal error because we hide the existence of the object when it does not exist or is not accessible by the user
		$this->assertEquals(Dict::S('UI:PageTitle:FatalError'), $sOutput);

		// The user should not have access to the ticket of the other organization, so an error should be logged
		$sLastErrorLogLines = $this->GetErrorLogLastLines(APPROOT.'log/error.log', 10);
		$this->assertStringContainsString(Dict::S('UI:ObjectDoesNotExist'), $sLastErrorLogLines);
	}

	// Test that if a user has already acquired the lock on an object, another user cannot acquire it and gets the correct error message
	public function testAcquireLockFailsIfAlreadyLockedByAnotherUser(): void
	{
		// First, acquire the lock with a user (User A)
		$this->CreateSupportAgentUserAndAcquireLock();
		$sUserALogin = self::$sLogin;

		// Create a second user (User B) who tries to acquire the lock
		$sOutput = $this->CreateSupportAgentUserAndAcquireLock();

		// The second user should not be able to acquire the lock, and the output should contain the correct error message indicating that the object is already locked by User A
		$this->assertStringContainsString('"success":false', $sOutput);
		$this->assertStringContainsString('"message":"'.Dict::Format('UI:CurrentObjectIsSoftLockedBy_User', $sUserALogin).'"', $sOutput);
	}

	// Helper method to create a user with Support Agent profile and acquire the lock on the ticket
	private function CreateSupportAgentUserAndAcquireLock(): string
	{
		// Create a user with Support Agent Profile
		$this->CreateUserWithProfile(self::$aURP_Profiles['Support Agent']);

		return $this->AcquireLockAsUser(self::$sLogin, self::$iTicketId);
	}

	// Helper method to create a user with a specific profile
	private function CreateUserWithProfile(int $iProfileId): UserLocal
	{
		self::$sLogin = uniqid('AjaxRenderTest');
		return $this->CreateContactlessUser(self::$sLogin, $iProfileId, self::AUTHENTICATION_PASSWORD);
	}

	// Helper method to acquire the lock on a ticket as a specific user
	private function AcquireLockAsUser(string $sLogin, int $iTicketId): string
	{
		$aGetFields = [
			'operation' => 'acquire_lock',
			'auth_user' => $sLogin,
			'auth_pwd'  => self::AUTHENTICATION_PASSWORD,
			'obj_class' => UserRequest::class,
			'obj_key'   => $iTicketId,
		];

		return $this->CallItopUri(
			"pages/ajax.render.php?".http_build_query($aGetFields),
			[],
			[
				CURLOPT_HTTPHEADER => ['X-Combodo-Ajax:1'],
				CURLOPT_POST       => 0,
			]
		);
	}

	// Returns the last lines of the error log containing only errors (Error level)
	private function GetErrorLogLastLines(string $sErrorLogPath, int $iLineNumbers = 1): string
	{
		if (!file_exists($sErrorLogPath)) {
			return '';
		}

		$aLines = file($sErrorLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Keep only lines containing '| Error |'
		$aErrorLines = array_filter($aLines, function ($line) {
			return preg_match('/\|\s*Error\s*\|/', $line);
		});

		// Return the last requested lines
		return implode("\n", array_slice($aErrorLines, -$iLineNumbers));
	}
}

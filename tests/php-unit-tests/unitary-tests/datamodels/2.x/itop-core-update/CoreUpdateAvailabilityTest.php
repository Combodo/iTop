<?php

declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Module\ItopCoreUpdate;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class CoreUpdateAvailabilityTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

	private const AUTHENTICATION_PASSWORD = 'test-password-123';

	protected function setUp(): void
	{
		parent::setUp();

		$this->BackupConfiguration();
		$this->AddLoginModeAndSaveConfiguration('url');
	}

	public function testFunctionalAdministratorCanAccessUpgradeWhenEnabled(): void
	{
		$this->SetUpgradeEnabled(true);

		$sMenuPage = $this->CallAsFunctionalAdministrator();
		$this->assertSame(200, $this->GetLastHttpStatus());
		$this->assertStringContainsString('data-menu-id="iTopUpdate"', $sMenuPage);

		$this->CallAsFunctionalAdministrator('core_update.select_update_file', ['maintenance' => 'true']);
		$this->assertSame(200, $this->GetLastHttpStatus());
	}

	public function testUpgradeMenuIsHiddenWhenDisabled(): void
	{
		$this->SetUpgradeEnabled(false);

		$sMenuPage = $this->CallAsFunctionalAdministrator();

		$this->assertSame(200, $this->GetLastHttpStatus());
		$this->assertStringNotContainsString('data-menu-id="iTopUpdate"', $sMenuPage);
	}

	public function testDirectUpgradePageAccessIsDeniedWhenDisabled(): void
	{
		$this->SetUpgradeEnabled(false);

		$this->CallAsFunctionalAdministrator('core_update.select_update_file', ['maintenance' => 'true']);

		$this->assertSame(403, $this->GetLastHttpStatus());
	}

	public function testUpgradeAjaxEndpointIsDeniedWhenDisabled(): void
	{
		$this->SetUpgradeEnabled(false);

		$this->CallAsFunctionalAdministrator('core_update_ajax.can_update_core');

		$this->assertSame(403, $this->GetLastHttpStatus());
	}

	private function SetUpgradeEnabled(bool $bEnabled): void
	{
		// Expected config parameter; it is not currently declared by itop-core-update.
		$this->oiTopConfig->Set('itop-core-update.enabled', $bEnabled);
		$this->SaveItopConfFile();
	}

	private function CallAsFunctionalAdministrator(?string $sRoute = null, array $aParams = []): string
	{
		$sLogin = 'core-update-admin-'.bin2hex(random_bytes(6));
		$this->CreateContactlessUser(
			$sLogin,
			self::$aURP_Profiles['Administrator'],
			self::AUTHENTICATION_PASSWORD
		);

		$aQuery = $aParams;
		if (null !== $sRoute) {
			$aQuery['route'] = $sRoute;
		}
		$aQuery['auth_user'] = $sLogin;
		$aQuery['auth_pwd'] = self::AUTHENTICATION_PASSWORD;
		$sUri = 'pages/UI.php';
		if ([] !== $aQuery) {
			$sUri .= '?'.http_build_query($aQuery);
		}

		return $this->CallItopUri($sUri, [], [CURLOPT_POST => 0]);
	}

	private function GetLastHttpStatus(): int
	{
		$this->assertNotNull($this->aLastCurlGetInfo);
		$this->assertArrayHasKey('http_code', $this->aLastCurlGetInfo);

		return $this->aLastCurlGetInfo['http_code'];
	}
}

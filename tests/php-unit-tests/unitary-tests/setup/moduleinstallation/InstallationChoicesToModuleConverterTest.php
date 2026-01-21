<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class InstallationChoicesToModuleConverterTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('/setup/moduleinstallation/InstallationChoicesToModuleConverter.php');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		ModuleDiscovery::ResetCache();
	}

	//integration test
	public function testGetModulesWithXmlInstallationFile_UsualCustomerPackagesWithNonITIL()
	{
		$aSearchDirs = $this->GivenModuleDiscoveryInit();

		$aInstalledModules = InstallationChoicesToModuleConverter::GetInstance()->GetModules(
			$this->GivenNonItilChoices(),
			$aSearchDirs,
			__DIR__.'/ressources/installation.xml'
		);

		$aExpected = [
			'authent-cas/3.3.0',
			'authent-external/3.3.0',
			'authent-ldap/3.3.0',
			'authent-local/3.3.0',
			'combodo-backoffice-darkmoon-theme/3.3.0',
			'combodo-backoffice-fullmoon-high-contrast-theme/3.3.0',
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme/3.3.0',
			'combodo-backoffice-fullmoon-tritanopia-theme/3.3.0',
			'itop-attachments/3.3.0',
			'itop-backup/3.3.0',
			'itop-config/3.3.0',
			'itop-files-information/3.3.0',
			'itop-portal-base/3.3.0',
			'itop-portal/3.3.0',
			'itop-profiles-itil/3.3.0',
			'itop-sla-computation/3.3.0',
			'itop-structure/3.3.0',
			'itop-themes-compat/3.3.0',
			'itop-tickets/3.3.0',
			'itop-welcome-itil/3.3.0',
			'combodo-db-tools/3.3.0',
			'itop-config-mgmt/3.3.0',
			'itop-core-update/3.3.0',
			'itop-datacenter-mgmt/3.3.0',
			'itop-endusers-devices/3.3.0',
			'itop-faq-light/3.3.0',
			'itop-hub-connector/3.3.0',
			'itop-knownerror-mgmt/3.3.0',
			'itop-oauth-client/3.3.0',
			'itop-request-mgmt/3.3.0',
			'itop-service-mgmt/3.3.0',
			'itop-storage-mgmt/3.3.0',
			'itop-virtualization-mgmt/3.3.0',
			'itop-bridge-cmdb-services/3.3.0',
			'itop-bridge-cmdb-ticket/3.3.0',
			'itop-bridge-datacenter-mgmt-services/3.3.0',
			'itop-bridge-endusers-devices-services/3.3.0',
			'itop-bridge-storage-mgmt-services/3.3.0',
			'itop-bridge-virtualization-mgmt-services/3.3.0',
			'itop-bridge-virtualization-storage/3.3.0',
			'itop-change-mgmt/3.3.0',
		];
		$this->assertEquals($aExpected, $aInstalledModules);
	}

	//integration test
	public function testGetModulesWithXmlInstallationFile_UsualCustomerPackagesWithITIL()
	{
		$aSearchDirs = $this->GivenModuleDiscoveryInit();

		$aInstalledModules = InstallationChoicesToModuleConverter::GetInstance()->GetModules(
			$this->GivenItilChoices(),
			$aSearchDirs,
			__DIR__.'/ressources/installation.xml'
		);

		$aExpected = [
			'authent-cas/3.3.0',
			'authent-external/3.3.0',
			'authent-ldap/3.3.0',
			'authent-local/3.3.0',
			'combodo-backoffice-darkmoon-theme/3.3.0',
			'combodo-backoffice-fullmoon-high-contrast-theme/3.3.0',
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme/3.3.0',
			'combodo-backoffice-fullmoon-tritanopia-theme/3.3.0',
			'itop-attachments/3.3.0',
			'itop-backup/3.3.0',
			'itop-config/3.3.0',
			'itop-files-information/3.3.0',
			'itop-portal-base/3.3.0',
			'itop-portal/3.3.0',
			'itop-profiles-itil/3.3.0',
			'itop-sla-computation/3.3.0',
			'itop-structure/3.3.0',
			'itop-themes-compat/3.3.0',
			'itop-tickets/3.3.0',
			'itop-welcome-itil/3.3.0',
			'combodo-db-tools/3.3.0',
			'itop-config-mgmt/3.3.0',
			'itop-core-update/3.3.0',
			'itop-datacenter-mgmt/3.3.0',
			'itop-endusers-devices/3.3.0',
			'itop-hub-connector/3.3.0',
			'itop-incident-mgmt-itil/3.3.0',
			'itop-oauth-client/3.3.0',
			'itop-request-mgmt-itil/3.3.0',
			'itop-service-mgmt/3.3.0',
			'itop-storage-mgmt/3.3.0',
			'itop-virtualization-mgmt/3.3.0',
			'itop-bridge-cmdb-services/3.3.0',
			'itop-bridge-cmdb-ticket/3.3.0',
			'itop-bridge-datacenter-mgmt-services/3.3.0',
			'itop-bridge-endusers-devices-services/3.3.0',
			'itop-bridge-storage-mgmt-services/3.3.0',
			'itop-bridge-virtualization-mgmt-services/3.3.0',
			'itop-bridge-virtualization-storage/3.3.0',
			'itop-change-mgmt-itil/3.3.0',
			'itop-full-itil/3.3.0',
		];
		$this->assertEquals($aExpected, $aInstalledModules);
	}

	//integration test
	public function testGetModulesWithXmlInstallationFile_LegacyPackages()
	{
		$aSearchDirs = $this->GivenModuleDiscoveryInit();

		//no choices means all default ones...
		$aNoInstallationChoices = [];

		$aInstalledModules = InstallationChoicesToModuleConverter::GetInstance()->GetModules(
			$aNoInstallationChoices,
			$aSearchDirs
		);

		$aExpected = [
			'authent-cas/3.3.0',
			'authent-external/3.3.0',
			'authent-ldap/3.3.0',
			'authent-local/3.3.0',
			'combodo-backoffice-darkmoon-theme/3.3.0',
			'combodo-backoffice-fullmoon-high-contrast-theme/3.3.0',
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme/3.3.0',
			'combodo-backoffice-fullmoon-tritanopia-theme/3.3.0',
			'itop-backup/3.3.0',
			'itop-config/3.3.0',
			'itop-files-information/3.3.0',
			'itop-portal-base/3.3.0',
			'itop-profiles-itil/3.3.0',
			'itop-sla-computation/3.3.0',
			'itop-structure/3.3.0',
			'itop-welcome-itil/3.3.0',
		];
		$this->assertEquals($aExpected, $aInstalledModules);
	}

	public function testIsDefaultModule_RootModuleShouldNeverBeDefault()
	{
		$sModuleId = ROOT_MODULE;
		$aModuleInfo = ['category' => 'authentication', 'visible' => false];
		$this->assertFalse($this->CallIsDefault($sModuleId, $aModuleInfo));
	}

	public function testIsDefaultModule_AutoselectShouldNeverBeDefault()
	{
		$sModuleId = 'autoselect_module';
		$aModuleInfo = ['category' => 'authentication', 'visible' => false, 'auto_select' => true];
		$this->assertFalse($this->CallIsDefault($sModuleId, $aModuleInfo));
	}

	public function testIsDefaultModule_AuthenticationModuleShouldBeDefault()
	{
		$sModuleId = 'authentication_module';
		$aModuleInfo = ['category' => 'authentication', 'visible' => true];
		$this->assertTrue($this->CallIsDefault($sModuleId, $aModuleInfo));
	}

	public function testIsDefaultModule_HiddenModuleShouldBeDefault()
	{
		$sModuleId = 'hidden_module';
		$aModuleInfo = ['category' => 'business', 'visible' => false];
		$this->assertTrue($this->CallIsDefault($sModuleId, $aModuleInfo));
	}

	public function testIsDefaultModule_NonModuleDefaultCase()
	{
		$sModuleId = 'any_module';
		$aModuleInfo = ['category' => 'business', 'visible' => true];
		$this->assertFalse($this->CallIsDefault($sModuleId, $aModuleInfo));
	}

	private function CallIsDefault($sModuleId, $aModuleInfo): bool
	{
		return $this->InvokeNonPublicMethod(InstallationChoicesToModuleConverter::class, 'IsDefaultModule', InstallationChoicesToModuleConverter::GetInstance(), [$sModuleId, $aModuleInfo]);
	}

	public function testIsAutoSelectedModule_RootModuleShouldNeverBeAutoSelect()
	{
		$sModuleId = ROOT_MODULE;
		$aModuleInfo = ['auto_select' => true];
		$this->assertFalse($this->CallIsAutoSelectedModule([], $sModuleId, $aModuleInfo));
	}

	public function testIsAutoSelectedModule_NoAutoselectByDefault()
	{
		$sModuleId = 'autoselect_module';
		$aModuleInfo = [];
		$this->assertFalse($this->CallIsAutoSelectedModule([], $sModuleId, $aModuleInfo));
	}

	/**
		* @return void
	 * cf DependencyExpression dedicated tests
	 */
	public function testIsAutoSelectedModule_UseInstalledModulesForComputation()
	{
		$sModuleId = "any_module";
		$aModuleInfo = ['auto_select' => 'SetupInfo::ModuleIsSelected("a") && SetupInfo::ModuleIsSelected("b")'];
		$aInstalledModules = ['a' => true, 'b' => true];
		$this->assertTrue($this->CallIsAutoSelectedModule($aInstalledModules, $sModuleId, $aModuleInfo));
	}

	private function CallIsAutoSelectedModule($aInstalledModules, $sModuleId, $aModuleInfo): bool
	{
		return $this->InvokeNonPublicMethod(InstallationChoicesToModuleConverter::class, 'IsAutoSelectedModule', InstallationChoicesToModuleConverter::GetInstance(), [$aInstalledModules, $sModuleId, $aModuleInfo]);
	}

	public function testProcessInstallationChoices_Default()
	{
		$aRes = [];
		$aInstallationDescription = $this->GivenInstallationChoiceDescription();

		$this->CallGetModuleNamesFromInstallationChoices([], $aInstallationDescription, $aRes);

		$aExpected = [
			'combodo-backoffice-darkmoon-theme' => true,
			'combodo-backoffice-fullmoon-high-contrast-theme' => true,
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme' => true,
			'combodo-backoffice-fullmoon-tritanopia-theme' => true,
			'itop-attachments' => true,
			'itop-backup' => true,
			'itop-config' => true,
			'itop-files-information' => true,
			'itop-profiles-itil' => true,
			'itop-structure' => true,
			'itop-themes-compat' => true,
			'itop-tickets' => true,
			'itop-welcome-itil' => true,
			'combodo-db-tools' => true,
			'itop-config-mgmt' => true,
			'itop-core-update' => true,
			'itop-hub-connector' => true,
			'itop-oauth-client' => true,
			'combodo-password-expiration' => true,
			'combodo-webhook-integration' => true,
			'combodo-my-account-user-info' => true,
			'authent-token' => true,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testProcessInstallationChoices_NonItilChoices()
	{
		$aRes = [];
		$aInstallationDescription = $this->GivenInstallationChoiceDescription();

		$this->CallGetModuleNamesFromInstallationChoices($this->GivenNonItilChoices(), $aInstallationDescription, $aRes);

		$aExpected = [
			'combodo-backoffice-darkmoon-theme' => true,
			'combodo-backoffice-fullmoon-high-contrast-theme' => true,
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme' => true,
			'combodo-backoffice-fullmoon-tritanopia-theme' => true,
			'itop-attachments' => true,
			'itop-backup' => true,
			'itop-config' => true,
			'itop-files-information' => true,
			'itop-profiles-itil' => true,
			'itop-structure' => true,
			'itop-themes-compat' => true,
			'itop-tickets' => true,
			'itop-welcome-itil' => true,
			'combodo-db-tools' => true,
			'itop-config-mgmt' => true,
			'itop-core-update' => true,
			'itop-hub-connector' => true,
			'itop-oauth-client' => true,
			'combodo-password-expiration' => true,
			'combodo-webhook-integration' => true,
			'combodo-my-account-user-info' => true,
			'authent-token' => true,
			'itop-datacenter-mgmt' => true,
			'itop-endusers-devices' => true,
			'itop-storage-mgmt' => true,
			'itop-virtualization-mgmt' => true,
			'itop-service-mgmt' => true,
			'itop-request-mgmt' => true,
			'itop-portal' => true,
			'itop-portal-base' => true,
			'itop-change-mgmt' => true,
			'itop-faq-light' => true,
			'itop-knownerror-mgmt' => true,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	public function testProcessInstallationChoices_ItilChoices()
	{
		$aRes = [];
		$aInstallationDescription = $this->GivenInstallationChoiceDescription();

		$this->CallGetModuleNamesFromInstallationChoices($this->GivenItilChoices(), $aInstallationDescription, $aRes);

		$aExpected = [
			'combodo-backoffice-darkmoon-theme' => true,
			'combodo-backoffice-fullmoon-high-contrast-theme' => true,
			'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme' => true,
			'combodo-backoffice-fullmoon-tritanopia-theme' => true,
			'itop-attachments' => true,
			'itop-backup' => true,
			'itop-config' => true,
			'itop-files-information' => true,
			'itop-profiles-itil' => true,
			'itop-structure' => true,
			'itop-themes-compat' => true,
			'itop-tickets' => true,
			'itop-welcome-itil' => true,
			'combodo-db-tools' => true,
			'itop-config-mgmt' => true,
			'itop-core-update' => true,
			'itop-hub-connector' => true,
			'itop-oauth-client' => true,
			'combodo-password-expiration' => true,
			'combodo-webhook-integration' => true,
			'combodo-my-account-user-info' => true,
			'authent-token' => true,
			'itop-datacenter-mgmt' => true,
			'itop-endusers-devices' => true,
			'itop-storage-mgmt' => true,
			'itop-virtualization-mgmt' => true,
			'itop-service-mgmt' => true,
			'itop-portal' => true,
			'itop-portal-base' => true,
			'itop-request-mgmt-itil' => true,
			'itop-incident-mgmt-itil' => true,
			'itop-change-mgmt-itil' => true,
		];
		$this->assertEquals($aExpected, $aRes);
	}

	private function CallGetModuleNamesFromInstallationChoices(array $aInstallationChoices, array $aInstallationDescription, array &$aModuleNames)
	{
		$this->InvokeNonPublicMethod(
			InstallationChoicesToModuleConverter::class,
			'GetModuleNamesFromInstallationChoices',
			InstallationChoicesToModuleConverter::GetInstance(),
			[$aInstallationChoices, $aInstallationDescription, &$aModuleNames]
		);
	}

	private function GivenInstallationChoiceDescription(): array
	{
		$oXMLParameters = new XMLParameters(__DIR__."/ressources/installation.xml");
		return $oXMLParameters->Get('steps', []);
	}

	private function GivenAllModules(): array
	{
		return json_decode(file_get_contents(__DIR__.'/ressources/available_modules.json'), true);
	}

	private function GivenNonItilChoices(): array
	{
		return [
			'itop-config-mgmt-core',
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-simple-ticket',
			'itop-ticket-mgmt-simple-ticket-enhanced-portal',
			'itop-change-mgmt-simple',
			'itop-kown-error-mgmt',
		];
	}

	private function GivenItilChoices(): array
	{
		return [
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-itil',
			'itop-ticket-mgmt-itil-user-request',
			'itop-ticket-mgmt-itil-incident',
			'itop-ticket-mgmt-itil-enhanced-portal',
			'itop-change-mgmt-itil',
			'itop-config-mgmt-core',

		];
	}

	private function GivenModuleDiscoveryInit(): array
	{
		$aSearchDirs = [APPROOT.'datamodels/2.x'];
		$this->SetNonPublicStaticProperty(ModuleDiscovery::class, 'm_aSearchDirs', $aSearchDirs);
		$aAllModules = $this->GivenAllModules();
		$this->SetNonPublicStaticProperty(ModuleDiscovery::class, 'm_aModules', $aAllModules);
		return $aSearchDirs;
	}
}

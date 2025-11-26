<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Setup\ModuleDependency\Module;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class ModuleTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/moduledependency/module.class.inc.php');
	}

	public function testModuleInit()
	{
		$oModule = new Module("itop-config-mgmt/2.4.0");
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleName());
		$this->assertEquals("2.4.0", $oModule->GetVersion());
		$this->assertEquals("itop-config-mgmt/2.4.0", $oModule->GetModuleId());
	}

	public function testModuleInit_NoVersion()
	{
		$oModule = new Module("itop-config-mgmt");
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleName());
		$this->assertEquals("1.0.0", $oModule->GetVersion());
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleId());
	}

	public function testSetDependencies_ComplexExpressionsParsing()
	{
		$oModule = new Module("itop-bridge-datacenter-mgmt-services");
		$oModule->SetDependencies([
			'itop-config-mgmt/>2.7.1',
			'itop-service-mgmt/=2.7.1 || itop-service-mgmt-provider/<=2.7.1',
			'itop-datacenter-mgmt/3.1.0 || true && false',
		]);
		$this->assertEquals(
			['itop-config-mgmt', 'itop-service-mgmt', 'itop-service-mgmt-provider', 'itop-datacenter-mgmt' ],
			$oModule->GetUnresolvedDependencyModuleNames()
		);
	}

	public function testIsResolved_Unresolved()
	{
		$oModule = new Module("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);
		$this->assertEquals(['itop-config-mgmt', 'itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames(), "all dependencies are unresolved");
		$this->assertFalse($oModule->IsResolved());

		$oModule->UpdateModuleResolutionState([], []);
		$this->assertFalse($oModule->IsResolved(), "all dependencies are still unresolved");
	}

	public function testIsResolved_PartialResolution()
	{
		$oModule = new Module("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);

		$oModule->UpdateModuleResolutionState(['itop-config-mgmt' => '2.7.1'], ['itop-config-mgmt' => true]);
		$this->assertFalse($oModule->IsResolved(), "some dependencies are still unresolved");
		$this->assertEquals(['itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames(), 'one dependency is remaining');
	}

	public function testIsResolved_OK()
	{
		$oModule = new Module("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);

		$oModule->UpdateModuleResolutionState(['itop-config-mgmt' => '2.7.1', 'itop-tickets' => '2.7.0'], ['itop-config-mgmt' => true, 'itop-tickets' => true]);
		$this->assertTrue($oModule->IsResolved());
		$this->assertEquals([], $oModule->GetUnresolvedDependencyModuleNames());
	}
}

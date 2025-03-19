<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class ModuleTest extends ItopTestCase
{
	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testModuleInit()
	{
		$oModule = new \iTopCoreModule("itop-config-mgmt/2.4.0");
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleName());
		$this->assertEquals("2.4.0", $oModule->GetVersion());
		$this->assertEquals("itop-config-mgmt/2.4.0", $oModule->GetModuleId());
	}

	public function testModuleInit_NoVersion()
	{
		$oModule = new \iTopCoreModule("itop-config-mgmt");
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleName());
		$this->assertEquals("1.0.0", $oModule->GetVersion());
		$this->assertEquals("itop-config-mgmt", $oModule->GetModuleId());
	}

	public function testIsResolved_Unresolved()
	{
		$oModule = new \iTopCoreModule("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);
		$this->assertEquals(['itop-config-mgmt', 'itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames());

		$this->assertEquals(false, $oModule->IsModuleResolved([],[]));
		$this->assertEquals(['itop-config-mgmt', 'itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames());
		$this->assertEquals(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0'], array_keys($oModule->aOngoingDependencies));
	}

	public function testSetDependencies()
	{
		$oModule = new \iTopCoreModule("itop-bridge-datacenter-mgmt-services");
		$oModule->SetDependencies([
			'itop-config-mgmt/2.7.1',
			'itop-service-mgmt/2.7.1 || itop-service-mgmt-provider/2.7.1',
			'itop-datacenter-mgmt/3.1.0',
		]);
		$this->assertEquals(['itop-config-mgmt', 'itop-service-mgmt', 'itop-service-mgmt-provider', 'itop-datacenter-mgmt' ],
			$oModule->GetUnresolvedDependencyModuleNames());

		$this->assertEquals(false, $oModule->IsModuleResolved([],[]));
		$this->assertEquals(['itop-config-mgmt', 'itop-service-mgmt', 'itop-service-mgmt-provider', 'itop-datacenter-mgmt'],
			$oModule->GetUnresolvedDependencyModuleNames());
		$this->assertEquals(['itop-config-mgmt/2.7.1', 'itop-service-mgmt/2.7.1 || itop-service-mgmt-provider/2.7.1', 'itop-datacenter-mgmt/3.1.0'],
			array_keys($oModule->aOngoingDependencies));
	}

	public function testIsResolved_PartialResolution()
	{
		$oModule = new \iTopCoreModule("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);
		$this->assertEquals(['itop-config-mgmt', 'itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames());

		$this->assertEquals(false, $oModule->IsModuleResolved(['itop-config-mgmt' => '2.7.1'],['itop-config-mgmt'=>true]));
		$this->assertEquals(['itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames());
		$this->assertEquals(['itop-tickets/2.7.0'], array_keys($oModule->aOngoingDependencies));
	}

	public function testIsResolved_OK()
	{
		$oModule = new \iTopCoreModule("itop-bridge-cmdb-ticket");
		$oModule->SetDependencies(['itop-config-mgmt/2.7.1', 'itop-tickets/2.7.0']);
		$this->assertEquals(['itop-config-mgmt', 'itop-tickets'], $oModule->GetUnresolvedDependencyModuleNames());

		$this->assertEquals(true, $oModule->IsModuleResolved(['itop-config-mgmt' => '2.7.1', 'itop-tickets' => '2.7.0'],['itop-config-mgmt'=>true, 'itop-tickets' => true]));
		$this->assertEquals([], $oModule->GetUnresolvedDependencyModuleNames());
		$this->assertEquals([], array_keys($oModule->aOngoingDependencies));
	}
}
<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use MissingDependencyException;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	public function testOrderModulesByDependenciesNewComputation_RealExample()
	{
		$aModules = json_decode(file_get_contents(__DIR__.'/ressources/module_deps.json'), true);

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, null);

		$aExpected = json_decode(file_get_contents(__DIR__.'/ressources/expected_ordered_module_ids.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
	}
}

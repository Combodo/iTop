<?php

namespace Service\Startup;

use Combodo\iTop\Service\Startup\StartupService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use CoreException;

class StartupServiceTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('application/utils.inc.php');
	}

	public function testSetItopEnvironmentUsesDefaultWhenEnvironmentIsNull(): void
	{
		$bAllowCache = true;
		$sEnv = StartupService::SetItopEnvironment(null, $bAllowCache);
		$this->assertEquals(ITOP_DEFAULT_ENV, $sEnv);
		$this->assertTrue($bAllowCache);
	}

	public function testSetItopEnvironmentWithValidEnvironment(): void
	{
		$bAllowCache = true;
		$sEnv = StartupService::SetItopEnvironment('test', $bAllowCache);
		$this->assertEquals('test', $sEnv);
		$this->assertFalse($bAllowCache);
	}

	public function testSetItopEnvironmentThrowsForBuildEnvironment()
	{
		$bAllowCache = true;
		$this->expectException(CoreException::class);
		$this->expectExceptionMessage("Switching to environment 'test-build' is not allowed since it is a build environment");
		StartupService::SetItopEnvironment('test-build', $bAllowCache);
	}

	public function testIsBuildEnvironment()
	{
		$this->assertTrue(StartupService::IsBuildEnvironment('test-build'));
		$this->assertFalse(StartupService::IsBuildEnvironment('test'));
		$this->assertFalse(StartupService::IsBuildEnvironment(null));
	}
}

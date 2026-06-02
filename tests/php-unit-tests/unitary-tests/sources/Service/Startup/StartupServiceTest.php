<?php

namespace Service\Startup;

use Combodo\iTop\Service\Startup\StartupService;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use CoreException;

class StartupServiceTest extends ItopTestCase
{
	private StartupService $oStartupService;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('application/utils.inc.php');
		$this->oStartupService = new StartupService();
	}

	public function testSetItopEnvironmentUsesDefaultWhenEnvironmentIsNull(): void
	{
		$bAllowCache = true;
		$sEnv = $this->oStartupService->SetItopEnvironment(null, $bAllowCache);
		$this->assertEquals(ITOP_DEFAULT_ENV, $sEnv);
		$this->assertTrue($bAllowCache);
	}

	public function testSetItopEnvironmentWithValidEnvironment(): void
	{
		$bAllowCache = true;
		$sEnv =  $this->oStartupService->SetItopEnvironment('test', $bAllowCache);
		$this->assertEquals('test', $sEnv);
		$this->assertFalse($bAllowCache);
	}

	public function testSetItopEnvironmentThrowsForBuildEnvironment()
	{
		$bAllowCache = true;
		$this->expectException(CoreException::class);
		$this->expectExceptionMessage("Switching to environment 'test-build' is not allowed since it is a build environment");
		$this->oStartupService->SetItopEnvironment('test-build', $bAllowCache);
	}

	public function testIsBuildEnvironment()
	{
		$this->assertTrue($this->oStartupService->IsBuildEnvironment('test-build'));
		$this->assertFalse($this->oStartupService->IsBuildEnvironment('test'));
		$this->assertFalse($this->oStartupService->IsBuildEnvironment(null));
	}
}

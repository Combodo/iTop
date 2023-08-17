<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use RuntimeDashboard;
use SecurityException;


/**
 * We need the metamodel started as this is a dependency of {@link RuntimeDashboard}
 *
 * @since 2.7.8 3.0.3 3.1.0 N°4449 Test Full Path Disclosure in Dashboard
 */
class RuntimeDashboardTest extends ItopDataTestCase
{
	const DEFAULT_WELCOME_DASHBOARD_PATH = 'env-production/itop-welcome-itil/welcomemenupage_dashboard.xml';
	const SYSTEM_FILE_PATH = '../../system-file';

	/** @noinspection PhpUnhandledExceptionInspection */
	public function testGetDashboard()
	{
		$sDashboardFileOk = APPROOT.self::DEFAULT_WELCOME_DASHBOARD_PATH;
		$sDashboardId = uniqid(mt_rand(), TRUE);
		$oDashboard = RuntimeDashboard::GetDashboard($sDashboardFileOk, $sDashboardId);
		$this->assertNotNull($oDashboard);

		$this->expectException(SecurityException::class);
		$sDashboardFileSuspect = APPROOT.self::SYSTEM_FILE_PATH;;
		RuntimeDashboard::GetDashboard($sDashboardFileSuspect, $sDashboardId);
	}

	/** @noinspection PhpUnhandledExceptionInspection */
	public function testGetDefinitionFileRelative()
	{
		$sFullDashboardPath = RuntimeDashboard::GetDashboardFileFromRelativePath(self::DEFAULT_WELCOME_DASHBOARD_PATH);
		$this->assertSame(APPROOT.self::DEFAULT_WELCOME_DASHBOARD_PATH, $sFullDashboardPath);

		$this->expectException(SecurityException::class);
		RuntimeDashboard::GetDashboardFileFromRelativePath(self::SYSTEM_FILE_PATH);
	}
}

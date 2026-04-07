<?php

/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use utils;

class LoginExternalTest extends ItopDataTestCase
{
	private $oConfig;
	private $sOriginalExtAuthVariable;

	protected function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'application/loginexternal.class.inc.php';
		$this->oConfig = utils::GetConfig();
		$this->sOriginalExtAuthVariable = $this->oConfig->Get('ext_auth_variable');
	}

	protected function tearDown(): void
	{
		$this->oConfig->Set('ext_auth_variable', $this->sOriginalExtAuthVariable, 'unit_test');
		parent::tearDown();
	}

	private function CallGetAuthUser()
	{
		$oLoginExternal = new \LoginExternal();
		$oMethod = new \ReflectionMethod(\LoginExternal::class, 'GetAuthUser');
		$oMethod->setAccessible(true);
		return $oMethod->invoke($oLoginExternal);
	}

	public function testGetAuthUserFromServerVariable()
	{
		$_SERVER['REMOTE_USER'] = 'alice';
		$this->oConfig->Set('ext_auth_variable', '$_SERVER[\'REMOTE_USER\']', 'unit_test');

		$this->assertSame('alice', $this->CallGetAuthUser());
	}

	public function testGetAuthUserFromCookie()
	{
		$_COOKIE['auth_user'] = 'bob';
		$this->oConfig->Set('ext_auth_variable', '$_COOKIE[\'auth_user\']', 'unit_test');

		$this->assertSame('bob', $this->CallGetAuthUser());
	}

	public function testGetAuthUserFromRequest()
	{
		$_REQUEST['auth_user'] = 'carol';
		$this->oConfig->Set('ext_auth_variable', '$_REQUEST[\'auth_user\']', 'unit_test');

		$this->assertSame('carol', $this->CallGetAuthUser());
	}

	public function testInvalidExpressionReturnsFalse()
	{
		$this->oConfig->Set('ext_auth_variable', '$_SERVER[\'HTTP_X_CMD\']) ? print(\'x\') : false; //', 'unit_test');

		$this->assertFalse($this->CallGetAuthUser());
	}

	public function testGetAuthUserFromHeaderWithoutAllowlist()
	{
		if (!function_exists('getallheaders')) {
			$this->markTestSkipped('getallheaders() not available');
		}
		$_SERVER['HTTP_X_REMOTE_USER'] = 'CN=header-test';
		$this->oConfig->Set('ext_auth_variable', 'getallheaders()[\'X-Remote-User\']', 'unit_test');

		$this->assertSame('CN=header-test', $this->CallGetAuthUser());
	}
}

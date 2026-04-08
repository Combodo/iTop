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
		$this->oConfig->SetExternalAuthenticationVariable($this->sOriginalExtAuthVariable);
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
		$this->oConfig->SetExternalAuthenticationVariable('$_SERVER[\'REMOTE_USER\']');

		$this->assertSame('alice', $this->CallGetAuthUser());
	}

	public function testGetAuthUserFromCookie()
	{
		$_COOKIE['auth_user'] = 'bob';
		$this->oConfig->SetExternalAuthenticationVariable('$_COOKIE[\'auth_user\']');

		$this->assertSame('bob', $this->CallGetAuthUser());
	}

	public function testGetAuthUserFromRequest()
	{
		$_REQUEST['auth_user'] = 'carol';
		$this->oConfig->SetExternalAuthenticationVariable('$_REQUEST[\'auth_user\']');

		$this->assertSame('carol', $this->CallGetAuthUser());
	}

	public function testInvalidExpressionReturnsFalse()
	{
		$this->oConfig->SetExternalAuthenticationVariable('$_SERVER[\'HTTP_X_CMD\']) ? print(\'x\') : false; //');

		$this->assertFalse($this->CallGetAuthUser());
	}

	public function testGetAuthUserFromHeaderWithoutAllowlist()
	{
		if (!function_exists('getallheaders')) {
			$this->markTestSkipped('getallheaders() not available');
		}
		$_SERVER['HTTP_X_REMOTE_USER'] = 'CN=header-test';
		$this->oConfig->SetExternalAuthenticationVariable('getallheaders()[\'X-Remote-User\']');

		$this->assertSame('CN=header-test', $this->CallGetAuthUser());
	}
}

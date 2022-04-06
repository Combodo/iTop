<?php
/**
 * Copyright (C) 2010-2021 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ConfigPlaceholdersResolver;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ConfigPlaceholdersResolverTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
		require_once (APPROOT.'core/config.class.inc.php');
	}
	/**
	 * @dataProvider providerResolve
	 */
	public function testResolve($aEnv, $aServer, $sValue, $sExpected, $sExpectedExceptionClass = null)
	{
		if ($sExpectedExceptionClass)
		{
			$this->expectException($sExpectedExceptionClass);
		}

		$oConfigPlaceholdersResolver = new ConfigPlaceholdersResolver($aEnv, $aServer);
		$sResult = $oConfigPlaceholdersResolver->Resolve($sValue);

		$this->assertEquals($sExpected, $sResult);
	}

	public function providerResolve()
	{
		$stdObj = (object) array('%env(HTTP_PORT)?:8080%', '%server(toto)?:8080%', '%foo(toto)?:8080%');

		return array(
			'basic behaviour' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array(),
				'sValue'  => '%env(HTTP_PORT)%',
				'sExpected' => '443',
			),

			'disabled if no ITOP_CONFIG_PLACEHOLDERS' => array(
				'aEnv'    => array('HTTP_PORT' => '443'),
				'aServer' => array(),
				'sValue'  => '%env(HTTP_PORT)%',
				'sExpected' => '%env(HTTP_PORT)%',
			),

			'basic with default not used' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array(),
				'sValue'  => '%env(HTTP_PORT)?:foo%',
				'sExpected' => '443',
			),

			'basic with default used' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, ),
				'aServer' => array(),
				'sValue'  => '%env(HTTP_PORT)?:foo%',
				'sExpected' => 'foo',
			),

			'basic with default used and empty' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, ),
				'aServer' => array(),
				'sValue'  => '%env(HTTP_PORT)?:%',
				'sExpected' => '',
			),

			'mixed with static' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => 'http://localhost:%env(HTTP_PORT)?:8080%/',
				'sExpected' => 'http://localhost:443/',
			),

			'multiple occurrences' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array('SERVER_NAME' => 'localhost'),
				'sValue'  => 'http://%server(SERVER_NAME)%:%env(HTTP_PORT)%/',
				'sExpected' => 'http://localhost:443/',
			),

			'array as source' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => array('http://localhost:%env(HTTP_PORT)?:8080%/', '%foo(HTTP_PORT)?:8080%', '%server(toto)?:8080%'),
				'sExpected' => array('http://localhost:443/', '%foo(HTTP_PORT)?:8080%', 'tutu'),
			),

			'invalid source' => array(
				'aEnv' => array('toto' => 'tutu'),
				'aServer'    => array('HTTP_PORT' => '443'),
				'sValue'  => '%foo(HTTP_PORT)?:8080%',
				'sExpected' => '%foo(HTTP_PORT)?:8080%',
			),

			'ignored source' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => $stdObj,
				'sExpected' => $stdObj,
			),

			'env matching port' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'HTTP_PORT' => '443'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => '%env(HTTP_PORT)?:8080%',
				'sExpected' => '443',
			),
			'env no matching port with default ' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'foo' => 'bar'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => '%env(HTTP_PORT)?:8080%',
				'sExpected' => '8080',
			),
			'env no matching port' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'foo' => 'bar'),
				'aServer' => array('toto' => 'tutu'),
				'sValue'  => '%env(HTTP_PORT)%',
				'sExpected' => null,
				'sExpectedExceptionClass' => 'ConfigException',
			),

			'server matching port' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'toto' => 'tutu'),
				'aServer' => array('HTTP_PORT' => '443'),
				'sValue'  => '%server(HTTP_PORT)?:8080%',
				'sExpected' => '443',
			),
			'server no matching port with default ' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'toto' => 'tutu'),
				'aServer' => array('foo' => 'bar'),
				'sValue'  => '%server(HTTP_PORT)?:8080%',
				'sExpected' => '8080',
			),
			'server no matching port' => array(
				'aEnv'    => array('ITOP_CONFIG_PLACEHOLDERS' => 1, 'toto' => 'tutu'),
				'aServer' => array('foo' => 'bar'),
				'sValue'  => '%server(HTTP_PORT)%',
				'sExpected' => null,
				'sExpectedExceptionClass' => 'ConfigException',
			),
		);
	}
}

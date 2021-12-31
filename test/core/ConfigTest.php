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
use Config;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ConfigTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
		require_once (APPROOT.'core/config.class.inc.php');
	}

	/**
	 *
	 * @dataProvider ProviderPreserveVarOnWriteToFile
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 *
	 */
	public function testPreserveVarOnWriteToFile($sConfigFile, $sExpectedContains, $aChanges)
	{
		$sTmpFile = tempnam(sys_get_temp_dir(), "target");

		$oConfig = new Config($sConfigFile);

		foreach ($aChanges as $key => $val) {
			$oConfig->Set($key, $val);
		}

		$oConfig->WriteToFile($sTmpFile);

		$this->assertFileExists($sTmpFile);
		$sFileContent = file_get_contents($sTmpFile);

		$this->assertContains($sExpectedContains, $sFileContent, "File content doesn't contain : ".$sExpectedContains);
	}

	public function ProviderPreserveVarOnWriteToFile()
	{
		return array(
			'preserve var' => array(
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => array(),
			),
			'preserve joker' => array(
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://%server(SERVER_NAME)?:localhost%/itop/iTop/'",
				'aChanges' => array(),
			),

			'preserve set same value' => array(
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => array('app_root_url' => 'http://localhost/itop/iTop/'),
			),

			'overwrite var' => array(
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => array('app_root_url' => 'foo'),
			),
			'overwrite joker' => array(
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => array('app_root_url' => 'foo'),
			),
		);
	}
}

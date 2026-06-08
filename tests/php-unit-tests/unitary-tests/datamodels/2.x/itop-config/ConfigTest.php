<?php

/**
 * Copyright (C) 2010-2024 Combodo SAS
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

namespace Combodo\iTop\Test\UnitTest\Module\iTopConfig;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use http\Encoding\Stream\Inflate;

class ConfigTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/config.class.inc.php');
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

		$this->assertStringContainsString($sExpectedContains, $sFileContent, "File content doesn't contain : ".$sExpectedContains);
	}

	public function ProviderPreserveVarOnWriteToFile()
	{
		return [
			'preserve var' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => [],
			],
			'preserve joker' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://%server(SERVER_NAME)?:localhost%/itop/iTop/'",
				'aChanges' => [],
			],
			'preserve set same value' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => ['app_root_url' => 'http://localhost/itop/iTop/'],
			],

			'overwrite var' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => ['app_root_url' => 'foo'],
			],
			'overwrite joker' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => ['app_root_url' => 'foo'],
			],
		];
	}

	private function GetModuleSettingSection(string $sFilePath): string
	{
		preg_match('/\$MyModuleSettings[\w\W]*\/\*\*/m', file_get_contents($sFilePath), $aMatches);
		return preg_replace(['/[	]+/', '/[ ]+/'], [' ', ' '], $aMatches[0]);
	}

	public static function ConfEvaluationIsTheSameWithPreviousAndCurrentAlgoProvider() {
		return [
			'comments in module settings' => ['config-with-comments.php'],
			'nominal case' => ['config-without-comments.php'],
		];
	}

	/**
	 * @dataProvider ConfEvaluationIsTheSameWithPreviousAndCurrentAlgoProvider
	 */
	public function ConfEvaluationIsTheSameWithPreviousAndCurrentAlgo($sFile, $sExpectedContentFile)
	{
		$sTmpFile = $this->GetTemporaryFilePath();
		$sConfigFile = __DIR__."/ConfigTest/$sFile";
		$oConfig = new Config($sConfigFile, true, false, false);
		$oConfig->WriteToFile($sTmpFile);

		$sExpected = file_get_contents(__DIR__."/ConfigTest/$sExpectedContentFile");
		$sExpected = preg_replace('|\?\>\n|', '?>', $sExpected);

		$this->assertEquals($sExpected, file_get_contents($sTmpFile));
	}

	public function testConfEvaluationIsTheSameWithPreviousAndCurrentAlgo()
	{
		$sFile = 'config-without-comments.php';
		$this->ConfEvaluationIsTheSameWithPreviousAndCurrentAlgo($sFile, $sFile);
	}

	public function testConfEvaluationIsTheSameWithPreviousAndCurrentAlgoEvenWithCommentsInMopduleSettings()
	{
		$this->ConfEvaluationIsTheSameWithPreviousAndCurrentAlgo('config-without-comments.php', 'config-with-comments-afterevaluatonwithoutcomments.php');
	}

	public function testConfSavePreserveCommentsInModuleSettings()
	{
		$sTmpFile = $this->GetTemporaryFilePath();
		$sConfigFile = __DIR__.'/ConfigTest/config-with-comments.php';
		$oConfig = new Config($sConfigFile, true, true);
		$oConfig->WriteToFile($sTmpFile);

		$sExpected = file_get_contents($sConfigFile);
		$sExpected = preg_replace('|\?\>\n|', '?>', $sExpected);
		$sExpected = preg_replace('|.*COMMENT NOT PRESERVED HERE.*|', '', $sExpected);

		$this->assertEquals($sExpected, file_get_contents($sTmpFile));
	}
}

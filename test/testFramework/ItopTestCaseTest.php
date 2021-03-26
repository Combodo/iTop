<?php
/*
 * Copyright (C) 2013-2021 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PHPUnit\Framework\TestCase;

class ItopTestCaseTest extends ItopTestCase
{

	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @dataProvider SetupItopEnvProvider
	 */
	public function testSetupItopEnv($sITopEnv, $sModuleRoot, $sExpectedException)
	{
		if (null !== $sModuleRoot) {
			define('MODULESROOT', $sModuleRoot);
		}

		if (null !== $sExpectedException) {
			$this->expectExceptionMessageRegExp($sExpectedException);
		}


		$oItopTestCase = new ItopTestCase();
		$oItopTestCase->SetupItopEnv($sITopEnv);

		if (null !== $sITopEnv) {
			$this->assertDirectoryExists(APPROOT."/env-test-$sITopEnv");
		} else {
			$this->assertDirectoryNotExists(APPROOT."/env-test-$sITopEnv");
		}

		if (null !== $sITopEnv && file_exists(__DIR__."/../testUtils/conf/targets/{$sITopEnv}/delta.xml")) {
			$this->assertFileEquals(
				__DIR__."/../testUtils/conf/targets/{$sITopEnv}/delta.xml",
				__DIR__."/../../data/test-{$sITopEnv}.delta.xml"
			);
		}
	}

	public function SetupItopEnvProvider()
	{
		return [
			'no env' => [
				'sITopEnv' => null,
				'sModuleRoot' => null,
				'sExpectedException' => null,
			],
			'not found env' => [
				'sITopEnv' => 'foo',
				'sModuleRoot' => null,
				'sExpectedException' => "/iTop env 'foo' not found/",
			],
			'MODULESROOT is defined' => [
				'sITopEnv' => null,
				'sModuleRoot' => 'foo',
				'sExpectedException' => "/setupItopEnv must be called before the MetaModel startup!/",
			],
			'fromProduction env' => [
				'sITopEnv' => 'fromProduction',
				'sModuleRoot' => null,
				'sExpectedException' => null,
			],
			'no env copy' => [
				'sITopEnv' => 'noEnvCopy',
				'sModuleRoot' => null,
				'sExpectedException' => null,
			],
			'env withTestFoo' => [
				'sITopEnv' => 'withTestFoo',
				'sModuleRoot' => null,
				'sExpectedException' => null,
			],
			'env withDeltaXml' => [
				'sITopEnv' => 'withDeltaXml',
				'sModuleRoot' => null,
				'sExpectedException' => null,
			],
		];
	}

	public function testSetupItopEnvProductedEnv()
	{
		$sEnv = 'withDeltaXml';
		$sRealEnv = ItopTestCase::TEST_ITOP_ENV_PREFIX.$sEnv;

		$oItopTestCase = new \Combodo\iTop\Test\UnitTest\ItopDataTestCase();
		$oItopTestCase->SetupItopEnv($sEnv);
		$oItopTestCase->EmulateApplicationStartup();
		require_once(APPROOT.'application/utils.inc.php');

		$this->assertSame($sRealEnv, MetaModel::GetEnvironment());

		Dict::SetDefaultLanguage('EN US');

		$this->assertSame('I am translated', Dict::S('module-test-foo-keyword'));
	}
}
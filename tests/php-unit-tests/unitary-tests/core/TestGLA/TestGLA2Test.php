<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DBObjectSet;
use DBSearch;
use MetaModel;

/**
 * @runTestsInSeparateProcesseszzz
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class TestGLA2Test extends ItopCustomDatamodelTestCase
{
	/**
	 * @inheritDoc
	 */
	public function GetDatamodelDeltaAbsPath(): string
	{
		return APPROOT.'tests/php-unit-tests/unitary-tests/core/TestGLA/TestGLA2Test.delta.xml';
	}

	public function testFoo()
	{
		static::assertFalse(MetaModel::IsValidAttCode('Person', 'non_existing_attribute2'));
		static::assertTrue(MetaModel::IsValidAttCode('Person', 'tested_attribute2'));
	}
}

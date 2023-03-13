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
 * Tests of the DBSearch class.
 * <ul>
 * <li>MakeGroupByQuery</li>
 * </ul>
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class TestGLATest extends ItopCustomDatamodelTestCase
{
	/**
	 * @inheritDoc
	 */
	public function GetDatamodelDeltaAbsPath(): string
	{
		return APPROOT.'tests/php-unit-tests/unitary-tests/core/TestGLATest.delta.xml';
	}

	public function testFoo()
	{
		static::assertFalse(MetaModel::IsValidAttCode('Person', 'non_existing_attribute'));
		static::assertTrue(MetaModel::IsValidAttCode('Person', 'tested_attribute'));
	}
}

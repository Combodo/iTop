<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;


/**
 * Class DBSearchUpdateRealiasingMapTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBSearchUpdateRealiasingMapTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');
	}

	/**
	 * @dataProvider UpdateRealiasingMapProvider
	 * @param $aRealiasingMap
	 * @param $aAliasTranslation
	 * @param $aExpectedRealiasingMap
	 */
	public function testUpdateRealiasingMap($aRealiasingMap, $aAliasTranslation, $aExpectedRealiasingMap)
	{
		$oObject = new DBObjectSearch('Organization');
		$aArgs = [&$aRealiasingMap, $aAliasTranslation];
		$this->InvokeNonPublicMethod(DBObjectSearch::class, 'UpdateRealiasingMap', $oObject, $aArgs);
		$this->assertEquals($aExpectedRealiasingMap, $aRealiasingMap);
	}

	public function UpdateRealiasingMapProvider()
	{
		return [
			'empty' => [
				'OriginalMap' => null,
				'AliasTranslation' => [],
				'ExpectedMap' => null
			],
			'Add 1 alias' => [
				'OriginalMap' => [],
				'AliasTranslation' => ['a' => ['*' => 'b']],
				'ExpectedMap' => ['a' => ['b']]
			],
			'Add 2 aliases' => [
				'OriginalMap' => [],
				'AliasTranslation' => ['a' => ['*' => 'b'], 'c' => ['*' => 'd']],
				'ExpectedMap' => ['a' => ['b'], 'c' => ['d']]
			],
			'Append 1 alias' => [
				'OriginalMap' => ['a' => ['b']],
				'AliasTranslation' => ['c' => ['*' => 'd']],
				'ExpectedMap' => ['a' => ['b'], 'c' => ['d']]
			],
			'Merge 1 alias' => [
				'OriginalMap' => ['a' => ['b']],
				'AliasTranslation' => ['a' => ['*' => 'd']],
				'ExpectedMap' => ['a' => ['b', 'd']]
			],
			'Merge same alias' => [
				'OriginalMap' => ['a' => ['b']],
				'AliasTranslation' => ['a' => ['*' => 'b']],
				'ExpectedMap' => ['a' => ['b']]
			],
			'Transitivity a->b + b->f = a->f' => [
				'OriginalMap' => ['a' => ['b', 'd'], 'c' => ['e']],
				'AliasTranslation' => ['b' => ['*' => 'f']],
				'ExpectedMap' => ['a' => ['f', 'd'], 'c' => ['e']]
			],
		];
	}
}

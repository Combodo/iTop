<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ValueSetDefinition;
use ValueSetEnum;


class ValueSetDefinitionTest extends ItopTestCase
{
	/**
	 * @param string $sValueSetDefClass
	 * @param array $aInputArgs
	 * @param array $aExpectedData
	 *
	 * @return void
	 * @dataProvider GetValuesProvider
	 * @covers \ValueSetDefinition::GetValues
	 */
	public function testGetValues(string $sValueSetDefClass, array $aInputArgs, array $aExpectedData)
	{
		$oValueSetDef = new $sValueSetDefClass(... $aInputArgs);
		$aTestedData = $oValueSetDef->GetValues([]);

		// Check that both arrays have the values *sorted* in the same order
		$aExpectedValues = array_values($aExpectedData);
		$aTestedValues = array_values($aTestedData);
		$this->assertEquals($aExpectedValues, $aTestedValues, 'Values are not ordered as expected');
	}

	public function GetValuesProvider(): array
	{
		return [
			'ValueSetEnum: Preserved order' => [
				ValueSetEnum::class,
				['new,assigned,resolved'],
				['new' => 'new', 'assigned' => 'assigned', 'resolved' => 'resolved'],
			],
			'ValueSetEnum: Preserved order' => [
				ValueSetEnum::class,
				['new,assigned,resolved'],
				['new' => 'new', 'assigned' => 'assigned', 'resolved' => 'resolved'],
			],
			'ValueSetEnum: Reorder alphabetically' => [
				ValueSetEnum::class,
				['new,assigned,resolved', true],
				['assigned' => 'assigned', 'new' => 'new', 'resolved' => 'resolved'],
			],
		];
	}
}
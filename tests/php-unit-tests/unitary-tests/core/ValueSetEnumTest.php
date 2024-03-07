<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\Core\ValueSetEnum\ABCEnum;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ValueSetEnum;


/**
 * Class ValueSetEnumTest
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Test\UnitTest\Core
 * @coves \ValueSetEnum
 */
class ValueSetEnumTest extends ItopTestCase
{
	public static function setupBeforeClass(): void
	{
		require_once __DIR__ . "/ValueSetEnum/ABCEnum.php";
	}

	/**
	 * @dataProvider LoadValuesProvider
	 *
	 * @param mixed $input
	 * @param array $aExpectedValues
	 * @param bool $bIsInputBackedEnum
	 *
	 * @return void
	 */
	public function testLoadValues(mixed $input, array $aExpectedValues, bool $bIsInputBackedEnum = false): void
	{
		if ($bIsInputBackedEnum) {
			$input = $input::cases();
		}
		$oValueSetEnum = new ValueSetEnum($input);
		$aTestedValues = $oValueSetEnum->GetValues([]);

		$this->assertEquals($aExpectedValues, $aTestedValues, "Values should be the same and ordered the same way");
	}

	public function LoadValuesProvider(): array
	{
		return [
			"CSV list, trimmed values, already ordered" => [
				"a,b,c",
				[
					"a" => "a",
					"b" => "b",
					"c" => "c",
				],
			],
			"CSV list, values to trim, already ordered" => [
				"a,  b ,c  ",
				[
					"a" => "a",
					"b" => "b",
					"c" => "c",
				],
			],
			"Array, already ordered" => [
				["a", "b", "c"],
				[
					0 => "a",
					1 => "b",
					2 => "c",
				],
			],
			"Backed-Enum" => [
				ABCEnum::class,
				[
					0 => "a",
					1 => "b",
					2 => "c",
				],
				true,   // Is the input value a backed enum?
			],
			"Invalid int value" => [
				123,
				[],
			]
		];
	}
}
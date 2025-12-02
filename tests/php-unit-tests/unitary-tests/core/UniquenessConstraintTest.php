<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use CoreUnexpectedValue;
use MetaModel;

/**
 * Class UniquenessConstraintTest
 *
 * @since 2.6.0 N°659 uniqueness constraint
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class UniquenessConstraintTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/metamodel.class.php');
	}

	/**
	 * @covers       MetaModel::CheckUniquenessRuleValidity
	 * @dataProvider uniquenessRuleValidityCheckProvider
	 *
	 * @param bool $bIsRuleShouldBeValid
	 * @param bool $bIsRuleOverride
	 * @param array $aRuleProperties
	 */
	public function testUniquenessRuleValidityCheck($bIsRuleShouldBeValid, $bIsRuleOverride, $aRuleProperties)
	{
		$bRuleValidResult = true;
		try {
			MetaModel::CheckUniquenessRuleValidity($aRuleProperties, $bIsRuleOverride);
		} catch (CoreUnexpectedValue $e) {
			$bRuleValidResult = false;
		}

		$this->assertEquals($bIsRuleShouldBeValid, $bRuleValidResult, "Validity test returned $bRuleValidResult");
	}

	public function uniquenessRuleValidityCheckProvider()
	{
		return [
			'simplest rule' => [true, false, ['attributes' => ['name']]],
			'with all properties' => [
				true,
				false,
				[
					'attributes' => ['name'],
					'filter' => 'name != \'\'',
					'disabled' => false,
					'is_blocking' => true,
				],
			],
			'only disabled key without ancestor' => [
				false,
				false,
				[
					'disabled' => true,
				],
			],
			'only disabled key with ancestor' => [
				true,
				true,
				[
					'disabled' => true,
				],
			],
		];
	}
}

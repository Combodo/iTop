<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * Class UniquenessConstraintTest
 *
 * @since 2.6 N°659 uniqueness constraint
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class UniquenessConstraintTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'/core/metamodel.class.php');
		require_once(APPROOT.'/core/coreexception.class.inc.php');
	}

	/**
	 * @covers       MetaModel::CheckUniquenessRuleValidity
	 * @dataProvider testUniquenessRuleValidityCheckProvider
	 *
	 * @param bool $bIsRuleShouldBeValid
	 * @param bool $bIsRuleOverride
	 * @param array $aRuleProperties
	 */
	public function testUniquenessRuleValidityCheck($bIsRuleShouldBeValid, $bIsRuleOverride, $aRuleProperties)
	{
		$bRuleValidResult = true;
		try
		{
			\MetaModel::CheckUniquenessRuleValidity($aRuleProperties, $bIsRuleOverride);
		}
		catch (\CoreUnexpectedValue $e)
		{
			$bRuleValidResult = false;
		}

		$this->assertEquals($bIsRuleShouldBeValid, $bRuleValidResult, "Validity test returned $bRuleValidResult");
	}

	public function uniquenessRuleValidityCheckProvider()
	{
		return array(
			'simplest rule' => array(true, false, array('attributes' => array('name'))),
			'with all properties' => array(
				true,
				false,
				array(
					'attributes' => array('name'),
					'filter' => 'name != \'\'',
					'disabled' => false,
					'is_blocking' => true,
				),
			),
			'only disabled key without ancestor' => array(
				false,
				false,
				array(
					'disabled' => true,
				),
			),
			'only disabled key with ancestor' => array(
				true,
				true,
				array(
					'disabled' => true,
				),
			),
		);
	}
}
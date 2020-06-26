<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Expression;

class ExpressionTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	/**
	 * @dataProvider ListParametersProvider
	 * @param $sOQL
	 * @param $aExpected
	 *
	 * @throws \OQLException
	 */
	public function testListParameters($sOQL, $aExpected)
	{
		$oExpression = Expression::FromOQL($sOQL);
		$aParameters = $oExpression->ListParameters();
		$aResult = array();
		foreach ($aParameters as $oVarExpr)
		{
			/** var \VariableExpression $oVarExpr */
			$aResult[] = $oVarExpr->RenderExpression();
		}
		$this->debug($aResult);
		$this->assertSame(array_diff($aExpected, $aResult), array_diff($aResult, $aExpected));
	}

	public function ListParametersProvider()
	{
		return array(
			array('1', array()),
			array(':id = 2', array(':id')),
			array('expiration_date < DATE_SUB(NOW(), INTERVAL :expiration_days DAY)', array(':expiration_days')),
			array('id IN (SELECT Organization WHERE :id = 2)', array(':id')),
			array('id IN (:id, 2)', array(':id')),
			array("B.name LIKE :name", array(':name')),
			array("name REGEXP :regexp", array(':regexp')),
			array(" t.agent_id = :current_contact_id", array(':current_contact_id')),
			array("INET_ATON(dev.managementip) > INET_ATON('10.22.32.224') AND INET_ATON(:ip) < INET_ATON('10.22.32.255')", array(':ip')),
		);
	}
}

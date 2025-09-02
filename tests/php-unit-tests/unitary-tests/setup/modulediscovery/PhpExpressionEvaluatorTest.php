<?php

namespace Combodo\iTop\Test\UnitTest\Setup\ModuleDiscovery;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use evaluation\expression\PhpExpressionEvaluator;

class PhpExpressionEvaluatorTest extends ItopDataTestCase{
	public function testEvaluateExpression(){
		$res = PhpExpressionEvaluator::GetInstance()->ParseAndEvaluateExpression('false');
		$this->assertEquals(false, $res);
	}
}
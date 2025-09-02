<?php

namespace evaluation\expression;

use PhpParser\Node\Expr\BinaryOp\Greater;

class GreaterEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return Greater::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left > $right;
	}
}
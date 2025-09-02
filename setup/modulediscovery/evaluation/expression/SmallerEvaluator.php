<?php

namespace evaluation\expression;

use PhpParser\Node\Expr\BinaryOp\Smaller;

class SmallerEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return Smaller::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left < $right;
	}
}
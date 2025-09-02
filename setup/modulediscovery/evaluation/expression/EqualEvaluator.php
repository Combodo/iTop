<?php

namespace evaluation\expression;

use PhpParser\Node\Expr\BinaryOp\Equal;

class EqualEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return Equal::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left == $right;
	}
}
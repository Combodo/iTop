<?php

namespace evaluation\expression;

use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class BooleanOrEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return BooleanOr::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left || $right;
	}
}
<?php

namespace evaluation\expression;

use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;

class BitwiseAndEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return BitwiseAnd::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left & $right;
	}
}
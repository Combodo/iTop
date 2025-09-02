<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class BooleanAndEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return BooleanAnd::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left && $right;
	}
}
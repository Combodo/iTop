<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;

class SmallerOrEqualEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return SmallerOrEqual::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left <= $right;
	}
}
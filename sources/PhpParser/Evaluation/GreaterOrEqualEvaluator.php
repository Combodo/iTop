<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;

class GreaterOrEqualEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return GreaterOrEqual::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left >= $right;
	}
}
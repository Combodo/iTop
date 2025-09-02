<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\NotEqual;

class NotEqualEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return NotEqual::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left != $right;
	}
}
<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\BitwiseOr;

class BitwiseOrEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return BitwiseOr::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left | $right;
	}
}
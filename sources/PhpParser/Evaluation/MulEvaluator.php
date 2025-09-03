<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr\BinaryOp\Mul;

class MulEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Mul::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left * $right;
	}
}
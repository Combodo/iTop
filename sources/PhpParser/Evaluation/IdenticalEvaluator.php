<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr\BinaryOp\Identical;

class IdenticalEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Identical::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left === $right;
	}
}
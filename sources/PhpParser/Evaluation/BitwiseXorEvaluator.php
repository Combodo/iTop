<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr\BinaryOp\BitwiseXor;

class BitwiseXorEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): ?string {
		return BitwiseXor::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left ^ $right;
	}
}
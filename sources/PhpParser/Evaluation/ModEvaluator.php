<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr\BinaryOp\Mod;

class ModEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Mod::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		return $left % $right;
	}
}
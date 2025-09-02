<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr\BinaryOp\Concat;

class ConcatEvaluator extends BinaryOpEvaluator {
	public function GetHandledExpressionType(): string {
		return Concat::class;
	}

	function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed
	{
		if (is_null($left) && is_null($right)){
			return null;
		}

		if (is_null($left)){
			return $right;
		}

		if (is_null($right)){
			return $left;
		}

		return "$left" . "$right";
	}
}
<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;

class ArrayDimFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return ArrayDimFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var ArrayDimFetch $oExpr */

		$var = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->var);
		if (is_null($var)){
			return null;
		}

		$dim = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->dim);
		if (is_null($var)){
			return $dim;
		}

		return $var[$dim] ?? null;
	}
}
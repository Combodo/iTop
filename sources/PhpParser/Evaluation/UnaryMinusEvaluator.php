<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\UnaryMinus;

class UnaryMinusEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return UnaryMinus::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var UnaryMinus $oExpr */

		return - PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
	}
}
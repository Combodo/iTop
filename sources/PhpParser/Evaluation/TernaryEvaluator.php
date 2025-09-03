<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Ternary;

class TernaryEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Ternary::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var Ternary $oExpr */

		$cond = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->cond);

		if ($cond){
			return PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->if);
		}

		return PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->else);
	}
}
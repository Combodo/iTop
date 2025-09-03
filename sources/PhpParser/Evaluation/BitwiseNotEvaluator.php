<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BitwiseNot;

class BitwiseNotEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return BitwiseNot::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var BitwiseNot $oExpr */

		return ~ PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
	}
}
<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\UnaryPlus;

class UnaryPlusEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return UnaryPlus::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var UnaryPlus $oExpr */

		return + PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
	}
}
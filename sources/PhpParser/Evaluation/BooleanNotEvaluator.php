<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BooleanNot;

class BooleanNotEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return BooleanNot::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var BooleanNot $oExpr */

		return ! PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
	}
}
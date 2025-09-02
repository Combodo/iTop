<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\UnaryMinus;

class UnaryMinusEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return UnaryMinus::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var UnaryMinus $oExpr */

		return - PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
	}
}
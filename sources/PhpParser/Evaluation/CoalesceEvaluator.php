<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Coalesce;

class CoalesceEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Coalesce::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var Coalesce $oExpr */

		$oLeftEval = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->left);
		if (! is_null($oLeftEval)) {
			return $oLeftEval;
		}

		return PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->right);
	}
}
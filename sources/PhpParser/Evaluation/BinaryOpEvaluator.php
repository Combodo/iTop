<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;

abstract class BinaryOpEvaluator extends AbstractExprEvaluator {
	abstract function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed;

	public function Evaluate(Expr $oExpr): mixed {
		/** @var BinaryOp $oExpr */

		return $this->EvaluateBinaryOperation(
			PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->left),
			PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->right));
	}
}
<?php

namespace evaluation\expression;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;

abstract class BinaryOpEvaluator implements iExprEvaluator {
	abstract function EvaluateBinaryOperation(mixed $left, mixed $right) : mixed;

	public function Evaluate(Expr $oExpr): mixed {
		/** @var BinaryOp $oExpr */

		return $this->EvaluateBinaryOperation(
			PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->left),
			PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->right));
	}
}
<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Isset_;

class IssetEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Isset_::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var Isset_ $oExpr */

		foreach ($oExpr->vars as $oVar){
			$var = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oVar);
			if (! isset($var)){
				return false;
			}
		}

		return true;
	}
}
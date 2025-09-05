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
			try{
				$var = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oVar);
				if (is_null($var)){
					return false;
				}
			} catch (\Throwable $t) {
				return false;
			}
		}

		return true;
	}
}
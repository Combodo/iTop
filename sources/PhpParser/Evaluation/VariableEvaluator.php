<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

class VariableEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return Variable::class;
	}
	public function Evaluate(Expr $oExpr): mixed {
		/** @var Variable $oExpr */
		$sName = $oExpr->name;

		if (array_key_exists($sName, get_defined_vars())) {
			return $$sName;
		}

		if (array_key_exists($sName, $GLOBALS)) {
			global $$sName;
			return $$sName;
		}

		return null;
	}

}
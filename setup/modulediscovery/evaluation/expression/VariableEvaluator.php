<?php

namespace evaluation\expression;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Variable;

class VariableEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return Variable::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var Variable $oExpr */
		if (is_null($oExpr->name)){
			return null;
		}

		if (! isset($oExpr->name)) {
			return null;
		}

		$sVarname=$oExpr->name;

		$bResult = null;
		@eval('$bResult = $'.$sVarname.';');

		return $bResult;

	}
}
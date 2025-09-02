<?php

namespace evaluation\expression;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;

class ConstFetchEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return ConstFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var ConstFetch $oExpr */
		if (defined($oExpr->name)){
			return constant($oExpr->name);
		}

		return null;
	}
}
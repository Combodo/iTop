<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;

class ConstFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
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
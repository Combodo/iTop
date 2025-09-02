<?php

namespace evaluation\expression;

use PhpParser\Node\Expr;

interface iExprEvaluator {
	public function GetHandledExpressionType(): string;

	public function Evaluate(Expr $oExpr) : mixed;
}

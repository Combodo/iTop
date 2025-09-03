<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;

interface iExprEvaluator {
	public function GetHandledExpressionType(): ?string;
	public function GetHandledExpressionTypes(): ?array ;
	public function Evaluate(Expr $oExpr) : mixed;
}

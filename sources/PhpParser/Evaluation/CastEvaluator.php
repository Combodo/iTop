<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\StaticPropertyFetch;

class CastEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return null;
	}

	public function GetHandledExpressionTypes(): ?array {
		return [
			Cast\Array_::class,
			Cast\Bool_::class,
			Cast\Double::class,
			Cast\Int_::class,
			Cast\Object_::class,
			Cast\String_::class,
		];
	}

	public function Evaluate(Expr $oExpr): mixed {
		$oSubExpr = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->expr);
		switch (get_class($oExpr)){
			case Cast\Array_::class:
				return (array) $oSubExpr;

			case Cast\Bool_::class:
				return (bool) $oSubExpr;

			case Cast\Double::class:
				/** @var Cast\Double $oExpr */
				switch ($oExpr->getAttribute("kind")){
					case Cast\Double::KIND_DOUBLE:
						return (double) $oSubExpr;

					case Cast\Double::KIND_FLOAT:
					case Cast\Double::KIND_REAL:
						return (float) $oSubExpr;
				}

				break;

			case Cast\Int_::class:
				return (int) $oSubExpr;

			case Cast\Object_::class:
				return (object) $oSubExpr;

			case Cast\String_::class:
				return (string) $oSubExpr;
		}

		return null;
	}
}
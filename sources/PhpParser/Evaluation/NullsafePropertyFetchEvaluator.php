<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use ReflectionClass;

class NullsafePropertyFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return NullsafePropertyFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var NullsafePropertyFetch $oExpr */

		$oVar = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->var);
		if (is_null($oVar)) {
			return null;
		}

		$sName = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		$oReflectionClass = new ReflectionClass(get_class($oVar));

		$oProperties = $oReflectionClass->getProperties();
		if (array_key_exists($sName, $oProperties)){
			$oProperty = $oProperties[$sName];
			if ($oProperty->isPublic()){
				return $oProperty->getValue($oVar);
			}
		}

		return null;
	}
}
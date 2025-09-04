<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use ReflectionClass;

class PropertyFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return null;
	}

	public function GetHandledExpressionTypes(): ?array {
		return [PropertyFetch::class, Expr\NullsafePropertyFetch::class];
	}

	public function Evaluate(Expr $oExpr): mixed {
		$oVar = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->var);
		if (is_null($oVar)) {
			return null;
		}

		if ($oExpr->name instanceof Identifier){
			$sName = $oExpr->name->name;
		} else {
			$sName = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		}

		$oReflectionClass = new ReflectionClass(get_class($oVar));
		try{
			$oProperty = $oReflectionClass->getProperty($sName);
			if ($oProperty->isPublic()){
				return $oProperty->getValue($oVar);
			}
		} catch (\ReflectionException $t) {}

		return null;
	}
}
<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\PropertyFetch;
use ReflectionClass;

class PropertyFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return PropertyFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var PropertyFetch $oExpr */

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

			return null;
		}

		$aArgs=[];

		$oMethods = $oReflectionClass->getMethods();
		if (array_key_exists($sName, $oMethods)){
			$oMethod = $oMethods[$sName];
			if ($oMethod->isPublic()){
				return $oMethod->invokeArgs(null, $aArgs);
			}

			return null;
		}
	}
}
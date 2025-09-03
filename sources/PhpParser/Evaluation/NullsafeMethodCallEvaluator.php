<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\NullsafeMethodCall;
use ReflectionClass;

class NullsafeMethodCallEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return NullsafeMethodCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var NullsafeMethodCall $oExpr */

		$oVar = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->var);
		if (is_null($oVar)) {
			return null;
		}

		$aArgs = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->args);
		$sName = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		$oReflectionClass = new ReflectionClass(get_class($oVar));

		$oMethods = $oReflectionClass->getMethods();
		if (array_key_exists($sName, $oMethods)){
			$oMethods = $oMethods[$sName];
			if ($oMethods->isPublic()){
				return $oMethods->invokeArgs($oVar, $aArgs);
			}
		}

		return null;
	}
}
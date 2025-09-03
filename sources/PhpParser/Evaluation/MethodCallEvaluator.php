<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use ReflectionClass;

class MethodCallEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return MethodCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var MethodCall $oExpr */

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
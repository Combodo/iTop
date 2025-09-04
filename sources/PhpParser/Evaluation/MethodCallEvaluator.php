<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use ReflectionClass;

class MethodCallEvaluator  extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return null;
	}

	public function GetHandledExpressionTypes(): ?array {
		return [MethodCall::class, Expr\NullsafeMethodCall::class];
	}

	public function Evaluate(Expr $oExpr): mixed {
		$oVar = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->var);
		if (is_null($oVar)) {
			return null;
		}

		$aArgs=[];
		foreach ($oExpr->args as $arg){
			/** @var \PhpParser\Node\Arg $arg */
			$aArgs[]=$arg->value->value;
		}

		if ($oExpr->name instanceof Identifier){
			$sName = $oExpr->name->name;
		} else {
			$sName = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		}

		$oReflectionClass = new ReflectionClass(get_class($oVar));
		try{
			$oMethod = $oReflectionClass->getMethod($sName);
			if ($oMethod->isPublic()){
				return $oMethod->invokeArgs($oVar, $aArgs);
			}
		} catch (\ReflectionException $t) {}

		return null;
	}
}
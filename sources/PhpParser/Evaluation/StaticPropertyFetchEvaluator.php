<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Identifier;

class StaticPropertyFetchEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return StaticPropertyFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var StaticPropertyFetch $oExpr */

		$sClassName = $oExpr->class->name;
		if ($oExpr->name instanceof Identifier){
			$sProperty = $oExpr->name->name;
		} else {
			$sProperty = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		}

		if (class_exists($sClassName)){
			$class = new \ReflectionClass($sClassName);
			if (array_key_exists($sProperty, $class->getStaticProperties())) {
				$oReflectionProperty = $class->getProperty($sProperty);
				if ($oReflectionProperty->isPublic()){
					return $class->getStaticPropertyValue($sProperty);
				}
			}
		}

		return null;
	}
}
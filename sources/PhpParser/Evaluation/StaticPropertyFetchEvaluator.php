<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticPropertyFetch;

class StaticPropertyFetchEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return StaticPropertyFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var StaticPropertyFetch $oExpr */

		$sClassName = $oExpr->class->name;
		$sProperty = $oExpr->name->name;

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
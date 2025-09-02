<?php

namespace evaluation\expression;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;

class ClassConstFetchEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return ClassConstFetch::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var ClassConstFetch $oExpr */

		$sClassName = $oExpr->class->name;
		$sProperty = $oExpr->name->name;

		if (class_exists($sClassName)){
			$class = new \ReflectionClass($sClassName);
			if (array_key_exists($sProperty, $class->getConstants())) {
				$oReflectionConstant = $class->getReflectionConstant($sProperty);
				if ($oReflectionConstant->isPublic()){
					return $class->getConstant($sProperty);
				}
			}
		}

		if ('class' === $sProperty){
			return $sClassName;
		}

		return null;
	}
}
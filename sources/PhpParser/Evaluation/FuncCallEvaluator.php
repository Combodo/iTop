<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileReaderException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use ReflectionFunction;

class FuncCallEvaluator extends AbstractExprEvaluator {
	public function GetHandledExpressionType(): ?string {
		return FuncCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var FuncCall $oExpr */

		$sFunction = $oExpr->name->name;
		$aWhiteList = ["function_exists", "class_exists", "method_exists"];
		if (! in_array($sFunction, $aWhiteList)){
			throw new ModuleFileReaderException("FuncCall $sFunction not supported");
		}

		$aArgs=[];
		foreach ($oExpr->args as $arg){
			/** @var \PhpParser\Node\Arg $arg */
			$aArgs[]=$arg->value->value;
		}

		$oReflectionFunction = new ReflectionFunction($sFunction);
		return $oReflectionFunction->invoke(...$aArgs);
	}
}
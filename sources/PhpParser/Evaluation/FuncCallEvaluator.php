<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileReaderException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use ReflectionFunction;

class FuncCallEvaluator extends AbstractExprEvaluator {
	public const WHITELIST=[
		"function_exists",
		"class_exists",
		"method_exists"
	];

	public function GetHandledExpressionType(): ?string {
		return FuncCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var FuncCall $oExpr */

		if ($oExpr->name instanceof Name){
			$sFunction = $oExpr->name->name;
		} else {
			$sFunction = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		}
		if (! in_array($sFunction, self::WHITELIST)){
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
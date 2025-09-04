<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileReaderException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;

class StaticCallEvaluator extends AbstractExprEvaluator {
	public const WHITELIST=[
		"SetupInfo::ModuleIsSelected",
		"utils::GetItopVersionWikiSyntax"
	];

	public function GetHandledExpressionType(): ?string {
		return StaticCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var StaticCall $oExpr */

		$sClassName = $oExpr->class->name;
		if ($oExpr->name instanceof Identifier){
			$sMethodName = $oExpr->name->name;
		} else {
			$sMethodName = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oExpr->name);
		}

		$sStaticCallDescription = "$sClassName::$sMethodName";
		if (! in_array($sStaticCallDescription, self::WHITELIST)){
			throw new ModuleFileReaderException("StaticCall $sStaticCallDescription not supported");
		}

		$aArgs=[];
		foreach ($oExpr->args as $arg){
			/** @var \PhpParser\Node\Arg $arg */
			$aArgs[]=$arg->value->value;
		}

		$class = new \ReflectionClass($sClassName);
		$method = $class->getMethod($sMethodName);
		if (! $method->isPublic()){
			throw new ModuleFileReaderException("StaticCall $sStaticCallDescription not public");
		}

		return $method->invokeArgs(null, $aArgs);
	}
}
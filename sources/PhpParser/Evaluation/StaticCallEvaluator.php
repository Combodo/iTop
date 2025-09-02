<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use ModuleFileReaderException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use ReflectionFunction;

class StaticCallEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return StaticCall::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var StaticCall $oExpr */

		$sClassName = $oExpr->class->name;
		$sMethodName = $oExpr->name->name;

		$aWhiteList = ["SetupInfo::ModuleIsSelected", "utils::GetItopVersionWikiSyntax"];
		$sStaticCallDescription = "$sClassName::$sMethodName";
		if (! in_array($sStaticCallDescription, $aWhiteList)){
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
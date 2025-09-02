<?php

namespace Combodo\iTop\PhpParser\Evaluation;;

use ModuleFileReaderException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;

class ArrayEvaluator implements iExprEvaluator {
	public function GetHandledExpressionType(): string {
		return Array_::class;
	}

	public function Evaluate(Expr $oExpr): mixed {
		/** @var Array_ $oExpr */
		$iIndex=0;

		$aModuleInformation=[];
		/** @var \PhpParser\Node\Expr\ArrayItem $oValue */
		foreach ($oExpr->items as $oArrayItem){
			if ($oArrayItem->key instanceof Int_||$oArrayItem->key instanceof String_||$oArrayItem->key instanceof ConstFetch) {
				//dictionnary
				$sKey = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oArrayItem->key);
				if (is_null($sKey)){
					continue;
				}
			} else {
				//array
				$sKey = $iIndex++;
			}

			try {
				$oValue = $oArrayItem->value;
				$oEvaluatuedValue = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oValue);
				$aModuleInformation[$sKey]=$oEvaluatuedValue;
			} catch(ModuleFileReaderException $e){
				//required to support legacy below dump dependency
				//'dependencies' => ['itop-config-mgmt/2.0.0'||'itop-structure/3.0.0']
				continue;
			}
		}

		return $aModuleInformation;
	}
}
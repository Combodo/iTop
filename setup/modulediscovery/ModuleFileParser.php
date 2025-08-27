<?php

use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Assign;

class ModuleFileParser {
	private static ModuleFileParser $oInstance;

	protected function __construct() {
	}

	final public static function GetInstance(): ModuleFileParser {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?ModuleFileParser $oInstance): void {
		static::$oInstance = $oInstance;
	}

	/**
	 * @param string $sPhpContent
	 *
	 * @return \PhpParser\Node\Stmt[]|null
	 */
	public function ParsePhpCode(string $sPhpContent): ?array
	{
		$oParser = (new ParserFactory())->createForNewestSupportedVersion();
		return $oParser->parse($sPhpContent);
	}

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Expr\Assign $oAssignation
	 *
	 * @return array|null
	 * @throws \ModuleFileReaderException
	 */
	public function GetModuleInformationFromAddModuleCall(string $sModuleFilePath, \PhpParser\Node\Stmt\Expression $oExpression) : ?array
	{
		/** @var Assign $oAssignation */
		$oAssignation = $oExpression->expr;
		if (false === ($oAssignation instanceof PhpParser\Node\Expr\StaticCall)) {
			return null;
		}

		/** @var PhpParser\Node\Expr\StaticCall $oAssignation */

		if ("SetupWebPage" !== $oAssignation?->class?->name) {
			return null;
		}

		if ("AddModule" !== $oAssignation?->name?->name) {
			return null;
		}

		$aArgs = $oAssignation?->args;
		if (count($aArgs) != 3) {
			throw new ModuleFileReaderException("Not enough parameters when calling SetupWebPage::AddModule", 0, null, $sModuleFilePath);
		}

		$oModuleId = $aArgs[1];
		if (false === ($oModuleId instanceof PhpParser\Node\Arg)) {
			throw new ModuleFileReaderException("2nd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleId), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleId */
		if (false === ($oModuleId->value instanceof PhpParser\Node\Scalar\String_)) {
			throw new ModuleFileReaderException("2nd parameter to SetupWebPage::AddModule not a string: " . get_class($oModuleId->value), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Scalar\String_ $sModuleIdStringObj */
		$sModuleIdStringObj = $oModuleId->value;
		$sModuleId = $sModuleIdStringObj->value;

		$oModuleConfigInfo = $aArgs[2];
		if (false === ($oModuleConfigInfo instanceof PhpParser\Node\Arg)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleConfigInfo), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleConfigInfo */
		if (false === ($oModuleConfigInfo->value instanceof PhpParser\Node\Expr\Array_)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		$aModuleConfig=[];
		$this->FillModuleInformationFromArray($oModuleConfigInfo->value, $aModuleConfig);

		if (! is_array($aModuleConfig)){
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}
		return [
			$sModuleFilePath,
			$sModuleId,
			$aModuleConfig,
		];
	}

	public function FillModuleInformationFromArray(PhpParser\Node\Expr\Array_ $oArray, array &$aModuleInformation) : void
	{
		$iIndex=0;
		/** @var \PhpParser\Node\Expr\ArrayItem $oValue */
		foreach ($oArray->items as $oArrayItem){
			if ($oArrayItem->key instanceof PhpParser\Node\Scalar\String_) {
				//dictionnary
				$sKey = $oArrayItem->key->value;
			} else if ($oArrayItem->key instanceof \PhpParser\Node\Expr\ConstFetch) {
				$sKey = $this->EvaluateConstantExpression($oArrayItem->key);
				if (is_null($sKey)){
					continue;
				}
			}else {
				$sKey = $iIndex++;
			}

			$oValue = $oArrayItem->value;

			if ($oValue instanceof PhpParser\Node\Expr\Array_) {
				$aSubConfig=[];
				$this->FillModuleInformationFromArray($oValue, $aSubConfig);
				$aModuleInformation[$sKey]=$aSubConfig;
			}

			if ($oValue instanceof PhpParser\Node\Scalar\String_||$oValue instanceof PhpParser\Node\Scalar\Int_) {
				$aModuleInformation[$sKey]=$oValue->value;
				continue;
			}

			if ($oValue instanceof \PhpParser\Node\Expr\ConstFetch) {
				$oEvaluatedConstant = $this->EvaluateConstantExpression($oValue);
				$aModuleInformation[$sKey]= $oEvaluatedConstant;
			}
		}
	}

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Stmt\If_ $oNode
	 *
	 * @return array|null
	 * @throws \ModuleFileReaderException
	 */
	public function GetModuleInformationFromIf(string $sModuleFilePath, \PhpParser\Node\Stmt\If_ $oNode) : ?array
	{
		$bCondition = $this->EvaluateExpression($oNode->cond);
		if ($bCondition) {
			foreach ($oNode->stmts as $oSubNode) {
				if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleConfig = $this->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oSubNode);
					if (!is_null($aModuleConfig)) {
						return $aModuleConfig;
					}
				}
			}
			return null;
		}

		if (! is_null($oNode->elseifs)) {
			foreach ($oNode->elseifs as $oElseIfSubNode) {
				/** @var \PhpParser\Node\Stmt\ElseIf_ $oElseIfSubNode */
				$bCondition = $this->EvaluateExpression($oElseIfSubNode->cond);
				if ($bCondition) {
					$aModuleConfig = $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oElseIfSubNode->stmts);
					if (!is_null($aModuleConfig)) {
						return $aModuleConfig;
					}
					break;
				}
			}
		}

		if (! is_null($oNode->else)) {
			$aModuleConfig = $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oNode->else->stmts);

			return $aModuleConfig;
		}

		return null;
	}

	public function GetModuleConfigurationFromStatement(string $sModuleFilePath, array $aStmts) : ?array
	{
		foreach ($aStmts as $oSubNode) {
			if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
				$aModuleConfig = $this->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oSubNode);
				if (!is_null($aModuleConfig)) {
					return $aModuleConfig;
				}
			}
		}

		return null;
	}

	//TODO replace eval
	public function EvaluateConstantExpression(\PhpParser\Node\Expr\ArrayItem|\PhpParser\Node\Expr\ConstFetch $oValue) : mixed
	{
		$bResult = false;
		try{
			@eval('$bResult = '.$oValue->name.';');
		} catch (Throwable $t) {
			throw new ModuleFileReaderException("Eval of ' . $oValue->name . ' caused an error: ".$t->getMessage());
		}

		return $bResult;
	}

	private function GetMixedValueForBooleanOperatorEvaluation(\PhpParser\Node\Expr $oExpr) : string
	{
		if ($oExpr instanceof \PhpParser\Node\Scalar\Int_ || $oExpr instanceof \PhpParser\Node\Scalar\Float_){
			return "" . $oExpr->value;
		}

		return $this->EvaluateExpression($oExpr) ? "true" : "false";
	}

	/**
	 * @param string $sBooleanExpr
	 *
	 * @return bool
	 * @throws ModuleFileReaderException
	 */
	private function UnprotectedComputeBooleanExpression(string $sBooleanExpr) : bool
	{
		$bResult = false;
		try{
			@eval('$bResult = '.$sBooleanExpr.';');
		} catch (Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sBooleanExpr' caused an error: ".$t->getMessage());
		}

		return $bResult;
	}

	/**
	 * @param string $sBooleanExpr
	 * @param bool $bSafe: when true, evaluation relies on unsafe eval() call
	 *
	 * @return bool
	 * @throws ModuleFileReaderException
	 */
	public function EvaluateBooleanExpression(string $sBooleanExpr, $bSafe=true) : bool
	{
		if (! $bSafe){
			return 	$this->UnprotectedComputeBooleanExpression($sBooleanExpr);
		}

		$sPhpContent = <<<PHP
<?php
$sBooleanExpr;
PHP;
		try{
			$aNodes = $this->ParsePhpCode($sPhpContent);
			$oExpr = $aNodes[0];
			return $this->EvaluateExpression($oExpr->expr);
		} catch (Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sBooleanExpr' caused an error:".$t->getMessage());
		}
	}

	private function EvaluateExpression(\PhpParser\Node\Expr $oCondExpression) : bool
	{
		if ($oCondExpression instanceof \PhpParser\Node\Expr\BinaryOp){
			$sExpr = $this->GetMixedValueForBooleanOperatorEvaluation($oCondExpression->left)
				. " "
				. $oCondExpression->getOperatorSigil()
				. " "
				. $this->GetMixedValueForBooleanOperatorEvaluation($oCondExpression->right);
			return $this->EvaluateBooleanExpression($sExpr, false);
		}

		if ($oCondExpression instanceof \PhpParser\Node\Expr\BooleanNot){
			return ! $this->EvaluateExpression($oCondExpression->expr);
		}

		if ($oCondExpression instanceof \PhpParser\Node\Expr\FuncCall){
			return $this->EvaluateCallFunction($oCondExpression);
		}

		if ($oCondExpression instanceof \PhpParser\Node\Expr\StaticCall){
			return $this->EvaluateStaticCallFunction($oCondExpression);
		}

		if ($oCondExpression instanceof \PhpParser\Node\Expr\ConstFetch){
			return $this->EvaluateConstantExpression($oCondExpression);
		}

		return true;
	}

	private function EvaluateCallFunction(\PhpParser\Node\Expr\FuncCall $oFunct) : bool
	{
		$sFunction = $oFunct->name->name;
		$aWhiteList = ["function_exists", "class_exists", "method_exists"];
		if (! in_array($sFunction, $aWhiteList)){
			throw new ModuleFileReaderException("FuncCall $sFunction not supported");
		}

		$aArgs=[];
		foreach ($oFunct->args as $arg){
			/** @var \PhpParser\Node\Arg $arg */
			$aArgs[]=$arg->value->value;
		}

		$oReflectionFunction = new ReflectionFunction($sFunction);
		return (bool)$oReflectionFunction->invoke(...$aArgs);
	}

	/**
	 * @param \PhpParser\Node\Expr\StaticCall $oStaticCall
	 *
	 * @return bool
	 * @throws \ModuleFileReaderException
	 * @throws \ReflectionException
	 */
	private function EvaluateStaticCallFunction(\PhpParser\Node\Expr\StaticCall $oStaticCall) : bool
	{
		$sClassName = $oStaticCall->class->name;
		$sMethodName = $oStaticCall->name->name;
		$aWhiteList = ["SetupInfo::ModuleIsSelected"];
		$sStaticCallDescription = "$sClassName::$sMethodName";
		if (! in_array($sStaticCallDescription, $aWhiteList)){
			throw new ModuleFileReaderException("StaticCall $sStaticCallDescription not supported");
		}

		$aArgs=[];
		foreach ($oStaticCall->args as $arg){
			/** @var \PhpParser\Node\Arg $arg */
			$aArgs[]=$arg->value->value;
		}

		$class = new \ReflectionClass($sClassName);
		$method = $class->getMethod($sMethodName);
		if (! $method->isPublic()){
			throw new ModuleFileReaderException("StaticCall $sStaticCallDescription not public");
		}

		return (bool) $method->invokeArgs(null, $aArgs);
	}
}
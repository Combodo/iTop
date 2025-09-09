<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileParser;
use ModuleFileReaderException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Expr;

class PhpExpressionEvaluator {
	const FUNC_CALL_WHITELIST=[
		"function_exists",
		"class_exists",
		"method_exists"
	];

	const STATIC_CALLWHITELIST=[
		"SetupInfo::ModuleIsSelected",
		"utils::GetItopVersionWikiSyntax"
	];

	private static PhpExpressionEvaluator $oInstance;

	protected function __construct() {
	}

	final public static function GetInstance(): PhpExpressionEvaluator {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?PhpExpressionEvaluator $oInstance): void {
		static::$oInstance = $oInstance;
	}

	public function EvaluateExpression(Expr $oExpression) : mixed
	{
		$oConstExprEvaluator = new ConstExprEvaluator();
		$oConstExprEvaluator->setFunctionsWhitelist(self::FUNC_CALL_WHITELIST);
		$oConstExprEvaluator->setStaticcallsWhitelist(self::STATIC_CALLWHITELIST);
		return $oConstExprEvaluator->evaluateDirectly($oExpression);
	}

	/**
	 * @param string $sBooleanExpr
	 *
	 * @return bool
	 * @throws \ModuleFileReaderException
	 */
	public function ParseAndEvaluateBooleanExpression(string $sBooleanExpr) : bool
	{
		return $this->ParseAndEvaluateExpression($sBooleanExpr);
	}

	public function ParseAndEvaluateExpression(string $sExpr) : mixed
	{
		$sPhpContent = <<<PHP
<?php
$sExpr;
PHP;
		try{
			$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPhpContent);
			$oExpr = $aNodes[0];
			return $this->EvaluateExpression($oExpr->expr);
		} catch (\Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sExpr' caused an error:".$t->getMessage());
		}
	}
}
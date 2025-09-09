<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileParser;
use ModuleFileReaderException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Expr;
use PhpParser\ParserFactory;

/**
 * Used at runtime/setup time
 */
class PhpExpressionEvaluator {
	private static PhpExpressionEvaluator $oInstance;

	/** @var ConstExprEvaluator $oConstExprEvaluator */
	private $oConstExprEvaluator;

	protected function __construct() {
		$this->oConstExprEvaluator = new ConstExprEvaluator();
	}

	public function SetFunctionsWhitelist(array $functionsWhiteList): void {
		$this->oConstExprEvaluator->setFunctionsWhitelist($functionsWhiteList);
	}

	public function SetStaticCallsWhitelist(array $staticCallsWhitelist): void {
		$this->oConstExprEvaluator->setStaticcallsWhitelist($staticCallsWhitelist);
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
		return $this->oConstExprEvaluator->evaluateDirectly($oExpression);
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
			$oParser = (new ParserFactory())->createForNewestSupportedVersion();
			$aNodes = $oParser->parse($sPhpContent);
			$oExpr = $aNodes[0];
			return $this->EvaluateExpression($oExpr->expr);
		} catch (\Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sExpr' caused an error:".$t->getMessage());
		}
	}
}
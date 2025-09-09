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

	/** @var ConstExprEvaluator $oConstExprEvaluator */
	private $oConstExprEvaluator;

	public function __construct(array $functionsWhiteList=[], array $staticCallsWhitelist=[]) {
		$this->oConstExprEvaluator = new ConstExprEvaluator();
		$this->oConstExprEvaluator->setStaticcallsWhitelist($staticCallsWhitelist);
		$this->oConstExprEvaluator->setFunctionsWhitelist($functionsWhiteList);
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
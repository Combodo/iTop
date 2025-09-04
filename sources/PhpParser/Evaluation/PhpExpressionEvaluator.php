<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use ModuleFileParser;
use ModuleFileReaderException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node\Expr;

class PhpExpressionEvaluator {
	private static PhpExpressionEvaluator $oInstance;

	/** @var iExprEvaluator[] $aPhpParserEvaluators */
	private static array $aPhpParserEvaluators;

	protected function __construct() {
	}

	final public static function GetInstance(): PhpExpressionEvaluator {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
			static::$aPhpParserEvaluators=[];

			foreach (glob(__DIR__ . "/**Evaluator.php") as $sFile){
				require_once $sFile;
				require_once $sFile;
				$sNamespace = 'Combodo\\iTop\PhpParser\\Evaluation\\';
				$sClass = $sNamespace. str_replace(".php", "", basename($sFile));
				$oReflectionClass = new \ReflectionClass($sClass);
				if ($oReflectionClass->isInstantiable()
					&& $oReflectionClass->implementsInterface(iExprEvaluator::class)){
					$oClass = new $sClass;

					if (! is_null($oClass->GetHandledExpressionType())){
						static::RegisterEvaluator($oClass, $oClass->GetHandledExpressionType());
					}
					if (! is_null($oClass->GetHandledExpressionTypes())) {
						foreach ($oClass->GetHandledExpressionTypes() as $sHandledExpressionType){
							static::RegisterEvaluator($oClass, $sHandledExpressionType);
						}
					}
				}
			}
		}

		return static::$oInstance;
	}

	private static function RegisterEvaluator(iExprEvaluator $oClass, string $sHandledExpressionType)
	{
		if (array_key_exists($sHandledExpressionType, static::$aPhpParserEvaluators)){
			throw new \CoreException("Another Evaluator class already deals with $sHandledExpressionType");
		}
		static::$aPhpParserEvaluators[$sHandledExpressionType] = $oClass;
	}

	final public static function SetInstance(?PhpExpressionEvaluator $oInstance): void {
		static::$oInstance = $oInstance;
	}

	public function EvaluateExpression(Expr $oExpression, int $iMode=self::LIB_AND_FALLBACK) : mixed
	{
		if ($iMode==self::ITOP_ALGO){
			return $this->EvaluateExpressionLocally($oExpression);
		}

		if ($iMode==self::LIB_ONLY){
			$oConstExprEvaluator = new ConstExprEvaluator();
		} else {
			$oConstExprEvaluator = new ConstExprEvaluator([$this, "EvaluateExpressionLocally"]);
		}

		$oConstExprEvaluator->setFunctionsWhitelist(FuncCallEvaluator::WHITELIST);
		$oConstExprEvaluator->setStaticcallsWhitelist(StaticCallEvaluator::WHITELIST);
		return $oConstExprEvaluator->evaluateDirectly($oExpression);
	}

	public function EvaluateExpressionLocally(Expr $oExpression) : mixed
	{
		$sClass = get_class($oExpression);
		$oPhpParserEvaluator = static::$aPhpParserEvaluators[$sClass] ?? null;
		if (is_null($oPhpParserEvaluator)){
			return $oExpression->value;
		}

		return $oPhpParserEvaluator->Evaluate($oExpression);
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

	const LIB_AND_FALLBACK=1;
	const LIB_ONLY=2;
	const ITOP_ALGO=3;
	public function ParseAndEvaluateExpression(string $sExpr, int $iMode=self::LIB_AND_FALLBACK) : mixed
	{
		$sPhpContent = <<<PHP
<?php
$sExpr;
PHP;
		try{
			$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode($sPhpContent);
			$oExpr = $aNodes[0];
			return $this->EvaluateExpression($oExpr->expr, $iMode);
		} catch (\Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sExpr' caused an error:".$t->getMessage());
		}
	}
}
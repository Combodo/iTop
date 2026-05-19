<?php

namespace Combodo\iTop\PhpParser\Evaluation;

use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileParser;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReaderException;
use PhpParser\Comment;
use PhpParser\ConstExprEvaluator;
use PhpParser\ExprEvaluator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\ParserFactory;

/**
 * Used at runtime/setup time
 */
class PhpExpressionEvaluator
{
	/** @var ConstExprEvaluator $oConstExprEvaluator */
	private $oConstExprEvaluator;

	public function __construct(array $functionsWhiteList = [], array $staticCallsWhitelist = [])
	{
		$this->oConstExprEvaluator = new ExprEvaluator();
		$this->oConstExprEvaluator->setStaticcallsWhitelist($staticCallsWhitelist);
		$this->oConstExprEvaluator->setFunctionsWhitelist($functionsWhiteList);
	}

	public function EvaluateExpression(Expr $oExpression): mixed
	{
		return $this->oConstExprEvaluator->evaluateDirectly($oExpression);
	}

	/**
	 * @param string $sBooleanExpr
	 *
	 * @return bool
	 * @throws ModuleFileReaderException
	 */
	public function ParseAndEvaluateBooleanExpression(string $sBooleanExpr): bool
	{
		return $this->ParseAndEvaluateExpression($sBooleanExpr);
	}

	public function ParseAndEvaluateExpression(string $sExpr): mixed
	{
		$sPhpContent = <<<PHP
<?php
$sExpr;
PHP;
		try {
			$oParser = (new ParserFactory())->createForNewestSupportedVersion();
			$aNodes = $oParser->parse($sPhpContent);
			$oExpr = $aNodes[0];
			return $this->EvaluateExpression($oExpr->expr);
		} catch (\Throwable $t) {
			throw new ModuleFileReaderException("Eval of '$sExpr' caused an error:".$t->getMessage());
		}
	}

	public function GetArrayWithComments(Array_ $oArray): array
	{
		$aRes = [];
		$i=0;
		foreach ($oArray->items as $oItem) {
			/** @var \PhpParser\Node\ArrayItem $oItem **/
			if(is_null($oItem->key)){
				$sKey = $i;
				$i++;
			} else {
				$sKey = $this->EvaluateExpression($oItem->key);
			}
			foreach ($oItem->getComments() as $oComment) {
				/** @var \PhpParser\Comment $oComment */
				$aRes[] = 'StartPhpParserComment'.$oComment->getText().'EndPhpParserComment';
			}
			if ($oItem->value instanceof Array_) {
				$aRes[$sKey] = $this->GetArrayWithComments($oItem->value);
			} elseif ($oItem->value instanceof Comment) {
				$aRes[$sKey] = $oItem->value;
			} else {
				$aRes[$sKey] = $this->EvaluateExpression($oItem->value);
			}
		}
		return $aRes;
	}
}

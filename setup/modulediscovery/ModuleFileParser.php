<?php

use evaluation\expression\PhpExpressionEvaluator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\ParserFactory;

require_once __DIR__ . "/evaluation/expression/PhpExpressionEvaluator.php";
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

		$sModuleId = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oModuleId->value);

		$oModuleConfigInfo = $aArgs[2];
		if (false === ($oModuleConfigInfo instanceof PhpParser\Node\Arg)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleConfigInfo), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleConfigInfo */
		if (false === ($oModuleConfigInfo->value instanceof PhpParser\Node\Expr\Array_)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		$aModuleConfig = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oModuleConfigInfo->value);

		if (! is_array($aModuleConfig)){
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		return [
			$sModuleFilePath,
			$sModuleId,
			$aModuleConfig,
		];
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
		$bCondition = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oNode->cond);
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
				$bCondition = PhpExpressionEvaluator::GetInstance()->EvaluateExpression($oElseIfSubNode->cond);
				if ($bCondition) {
					return $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oElseIfSubNode->stmts);
				}
			}
		}

		if (! is_null($oNode->else)) {
			return $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oNode->else->stmts);
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
}
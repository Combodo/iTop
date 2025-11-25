<?php

namespace Combodo\iTop\Setup\ModuleDependency;

require_once(APPROOT.'/setup/runtimeenv.class.inc.php');
use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;
use ModuleFileReaderException;
use RunTimeEnvironment;

/**
 * Class that handles a module dependency
 * Dependency expression example : (moduleA/123 || moduleB>456)
 */
class DependencyExpression
{
	private static PhpExpressionEvaluator $oPhpExpressionEvaluator;

	private string $sDependencyExpression;
	private bool $bValid = true;
	private bool $bResolved = false;

	/**
	 * @var array<string, bool> $aRemainingModuleNamesToResolve
	 */
	private array $aRemainingModuleNamesToResolve;

	/**
	 * @var array<string, array> $aParamsPerModuleId
	 */
	private array $aParamsPerModuleId;

	public function __construct(string $sDependencyExpression)
	{
		$this->sDependencyExpression = $sDependencyExpression;
		$this->aParamsPerModuleId = [];
		$this->aRemainingModuleNamesToResolve = [];

		if (preg_match_all('/([^\(\)&| ]+)/', $sDependencyExpression, $aMatches)) {
			foreach ($aMatches as $aMatch) {
				foreach ($aMatch as $sModuleId) {
					if (! array_key_exists($sModuleId, $this->aParamsPerModuleId)) {
						// $sModuleId in the dependency string is made of a <name>/<optional_operator><version>
						// where the operator is < <= = > >= (by default >=)
						$aModuleMatches = [];
						if (preg_match('|^([^/]+)/(<?>?=?)([^><=]+)$|', $sModuleId, $aModuleMatches)) {
							$sModuleName = $aModuleMatches[1];
							$this->aRemainingModuleNamesToResolve[$sModuleName] = true;
							$sOperator = $aModuleMatches[2];
							if ($sOperator == '') {
								$sOperator = '>=';
							}
							$sExpectedVersion = $aModuleMatches[3];
							$this->aParamsPerModuleId[$sModuleId] = [$sModuleName, $sOperator, $sExpectedVersion];
						}
					}
				}
			}
		} else {
			$this->bValid = false;
		}
	}

	private static function GetPhpExpressionEvaluator(): PhpExpressionEvaluator
	{
		if (!isset(static::$oPhpExpressionEvaluator)) {
			static::$oPhpExpressionEvaluator = new PhpExpressionEvaluator([], RunTimeEnvironment::STATIC_CALL_AUTOSELECT_WHITELIST);
		}

		return static::$oPhpExpressionEvaluator;
	}

	/**
	 * Return module names potentially required by current dependency
	 * @return array
	 */
	public function GetRemainingModuleNamesToResolve(): array
	{
		return array_keys($this->aRemainingModuleNamesToResolve);
	}

	public function IsResolved(): bool
	{
		return $this->bResolved;
	}

	/**
	 * Check if dependency is resolved with current list of module versions
	 * @param array $aModuleVersions: versions by module names dict
	 * @param array $aSelectedModules: modules names dict
	 *
	 * @return void
	 */
	public function UpdateModuleResolutionState(array $aModuleVersions, array $aSelectedModules): void
	{
		if (!$this->bValid) {
			return;
		}

		$aReplacements = [];
		foreach ($this->aParamsPerModuleId as $sModuleId => list($sModuleName, $sOperator, $sExpectedVersion)) {
			if (array_key_exists($sModuleName, $aModuleVersions)) {
				// module is present, check the version
				$sCurrentVersion = $aModuleVersions[$sModuleName];
				if (version_compare($sCurrentVersion, $sExpectedVersion, $sOperator)) {
					if (array_key_exists($sModuleName, $this->aRemainingModuleNamesToResolve)) {
						unset($this->aRemainingModuleNamesToResolve[$sModuleName]);
					}
					$aReplacements[$sModuleId] = '(true)'; // Add parentheses to protect against invalid condition causing
					// a function call that results in a runtime fatal error
				} else {
					$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
					// a function call that results in a runtime fatal error
				}
			} else {
				// module is not present
				$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
				// a function call that results in a runtime fatal error
			}
		}

		foreach ($this->aRemainingModuleNamesToResolve as $sModuleName => $c) {
			if (array_key_exists($sModuleName, $aSelectedModules)) {
				// This module is actually a prerequisite
				if (!array_key_exists($sModuleName, $aModuleVersions)) {
					return;
				}
			}
		}

		$bResult = false;
		$sBooleanExpr = str_replace(array_keys($aReplacements), array_values($aReplacements), $this->sDependencyExpression);
		try {
			$bResult = self::GetPhpExpressionEvaluator()->ParseAndEvaluateBooleanExpression($sBooleanExpr);
		} catch (ModuleFileReaderException $e) {
			//logged already
			echo "Failed to parse the boolean Expression = '$sBooleanExpr'<br/>";
		}

		$this->bResolved = $bResult;
	}

	public function IsValid(): bool
	{
		return $this->bValid;
	}
}

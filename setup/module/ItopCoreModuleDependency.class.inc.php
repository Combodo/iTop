<?php

use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;

/**
 * Class that handles a module dependency
 */
class iTopCoreModuleDependency {
	private static PhpExpressionEvaluator $oPhpExpressionEvaluator;

	private array $aPotentialPrerequisites;
	private array $aParamsPerModuleId;
	private string $sDepString;
	private bool $bAlwaysUnresolved=false;

	public function __construct(string $sDepString)
	{
		$this->sDepString = $sDepString;
		$this->aParamsPerModuleId = [];
		$this->aPotentialPrerequisites = [];

		if (preg_match_all('/([^\(\)&| ]+)/', $sDepString, $aMatches))
		{
			foreach($aMatches as $aMatch)
			{
				foreach($aMatch as $sModuleId)
				{
					if (! array_key_exists($sModuleId, $this->aParamsPerModuleId)) {
						// $sModuleId in the dependency string is made of a <name>/<optional_operator><version>
						// where the operator is < <= = > >= (by default >=)
						$aModuleMatches = array();
						if (preg_match('|^([^/]+)/(<?>?=?)([^><=]+)$|', $sModuleId, $aModuleMatches)) {
							$sModuleName = $aModuleMatches[1];
							$this->aPotentialPrerequisites[$sModuleName] = true;
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
			$this->bAlwaysUnresolved=true;
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
	public function GetPotentialPrerequisiteModuleNames() : array
	{
		return array_keys($this->aPotentialPrerequisites);
	}

	/**
	 * Check if dependency is resolved with current list of module versions
	 * @param array $aModuleVersions: versions by module names dict
	 * @param array $aSelectedModules: modules names dict
	 *
	 * @return bool
	 */
	public function IsDependencyResolved(array $aModuleVersions, array $aSelectedModules) : bool
	{
		if ($this->bAlwaysUnresolved){
			return false;
		}

		$aReplacements=[];
		foreach ($this->aParamsPerModuleId as $sModuleId => list($sModuleName, $sOperator, $sExpectedVersion)){
			if (array_key_exists($sModuleName, $aModuleVersions))
			{
				// module is present, check the version
				$sCurrentVersion = $aModuleVersions[$sModuleName];
				if (version_compare($sCurrentVersion, $sExpectedVersion, $sOperator))
				{
					if (array_key_exists($sModuleName, $this->aPotentialPrerequisites)) {
						unset($this->aPotentialPrerequisites[$sModuleName]);
					}
					$aReplacements[$sModuleId] = '(true)'; // Add parentheses to protect against invalid condition causing
					// a function call that results in a runtime fatal error
				}
				else
				{
					$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
					// a function call that results in a runtime fatal error
				}
			}
			else
			{
				// module is not present
				$aReplacements[$sModuleId] = '(false)'; // Add parentheses to protect against invalid condition causing
				// a function call that results in a runtime fatal error
			}
		}

		foreach ($this->aPotentialPrerequisites as $sModuleName)
		{
			if (array_key_exists($sModuleName, $aSelectedModules))
			{
				// This module is actually a prerequisite
				if (!array_key_exists($sModuleName, $aModuleVersions))
				{
					return false;
				}
			}
		}

		$bResult=false;
		$sBooleanExpr = str_replace(array_keys($aReplacements), array_values($aReplacements), $this->sDepString);
		try{
			$bResult = self::GetPhpExpressionEvaluator()->ParseAndEvaluateBooleanExpression($sBooleanExpr);
		} catch(ModuleFileReaderException $e){
			//logged already
			echo "Failed to parse the boolean Expression = '$sBooleanExpr'<br/>";
		}
		return $bResult;
	}
}
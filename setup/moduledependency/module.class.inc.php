<?php

namespace Combodo\iTop\Setup\ModuleDependency;

require_once(__DIR__.'/moduledependency.class.inc.php');
use ModuleDiscovery;

/**
 * Class that handles a modules and all its dependencies
 */
class Module
{
	private string $sModuleId;
	private string $sModuleName;
	private string $sVersion;

	private array $aInitialDependencyExpressions;
	public array $aRemainingDependenciesToResolve;

	public function __construct(string $sModuleId)
	{
		$this->sModuleId = $sModuleId;
		list($this->sModuleName, $this->sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
	}

	public function IsDependencyExpressionResolved(string $sDependencyExpression) : bool
	{
	    return ! array_key_exists($sDependencyExpression, $this->aRemainingDependenciesToResolve);
	}

	public function GetDependencyResolutionFeedback() : array
    {
        $aDepsWithIcons = [];

        foreach ($this->aInitialDependencyExpressions as $sIndex => $sDependencyExpression) {
            if ($this->IsDependencyExpressionResolved($sDependencyExpression)) {
                $aDepsWithIcons[$sIndex] = '✅ '.$sDependencyExpression;
            } else {
                $aDepsWithIcons[$sIndex] = '❌ '.$sDependencyExpression;
            }
        }
		return $aDepsWithIcons;
    }

	/**
	 * @return string
	 */
	public function GetModuleName()
	{
		return $this->sModuleName;
	}

	/**
	 * @return string
	 */
	public function GetVersion()
	{
		return $this->sVersion;
	}

	/**
	 * @return string
	 */
	public function GetModuleId()
	{
		return $this->sModuleId;
	}

	/**
	 * @param array $aAllDependencyExpressions: list of dependencies (string)
	 *
	 * @return void
	 */
	public function SetDependencies(array $aAllDependencyExpressions): void
	{
		$this->aInitialDependencyExpressions = $aAllDependencyExpressions;
		$this->aRemainingDependenciesToResolve = [];

		foreach ($aAllDependencyExpressions as $sDependencyExpression) {
			$this->aRemainingDependenciesToResolve[$sDependencyExpression] = new ModuleDependency($sDependencyExpression);
		}
	}

	/**
	 * Check if module dependencies are resolved with current list of module versions
	 * @param array $aModuleVersions : versions by module names dict
	 * @param array $aSelectedModules : modules names dict
	 *
	 * @return bool
	 */
	public function UpdateModuleResolutionState(array $aModuleVersions, array $aSelectedModules): bool
	{
		$aNextDependencies = [];
		$bDependenciesSolved = true;
		foreach ($this->aRemainingDependenciesToResolve as $sDependencyExpression => $oModuleDependency) {
			/** @var ModuleDependency $oModuleDependency*/
			if (!$oModuleDependency->UpdateModuleResolutionState($aModuleVersions, $aSelectedModules)) {
				$aNextDependencies[$sDependencyExpression] = $oModuleDependency;
				$bDependenciesSolved = false;
			}
		}

		$this->aRemainingDependenciesToResolve = $aNextDependencies;

		return $bDependenciesSolved;
	}

	/**
	 * @return array: list of unique module names
	 */
	public function GetUnresolvedDependencyModuleNames(): array
	{
		$aRes = [];
		foreach ($this->aRemainingDependenciesToResolve as $sDependencyExpression => $oModuleDependency) {
			/** @var ModuleDependency $oModuleDependency */
			$aRes = array_merge($aRes, $oModuleDependency->GetRemainingModuleNamesToResolve());
		}

		return array_unique($aRes);
	}
}

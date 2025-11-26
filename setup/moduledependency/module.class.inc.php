<?php

namespace Combodo\iTop\Setup\ModuleDependency;

require_once(__DIR__.'/dependencyexpression.class.inc.php');
use ModuleDiscovery;

/**
 * Class that handles a modules and all its dependencies
 */
class Module
{
	private string $sModuleId;
	private string $sModuleName;
	private string $sVersion;

	/**
	* @var array<string> $aInitialDependencyExpressions
	 */
	private array $aInitialDependencyExpressions;

	/**
	* @var array<string, DependencyExpression> $aRemainingDependenciesToResolve
	 */
	public array $aRemainingDependenciesToResolve;

	public function __construct(string $sModuleId)
	{
		$this->sModuleId = $sModuleId;
		list($this->sModuleName, $this->sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
	}

	public function IsDependencyExpressionResolved(string $sDependencyExpression): bool
	{
		return ! array_key_exists($sDependencyExpression, $this->aRemainingDependenciesToResolve);
	}

	public function GetDependencyResolutionFeedback(): array
	{
		$aDepsWithIcons = [];

		foreach ($this->aInitialDependencyExpressions as $sDependencyExpression) {
			if (! $this->IsDependencyExpressionResolved($sDependencyExpression)) {
				$aDepsWithIcons[] = '❌ '.$sDependencyExpression;
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
			$this->aRemainingDependenciesToResolve[$sDependencyExpression] = new DependencyExpression($sDependencyExpression);
		}
	}

	public function IsResolved(): bool
	{
		return (0 === count($this->aRemainingDependenciesToResolve));
	}

	/**
	 * Check if module dependencies are resolved with current list of module versions
	 * @param array $aModuleVersions : versions by module names dict
	 * @param array $aSelectedModules : modules names dict
	 *
	 * @return void
	 */
	public function UpdateModuleResolutionState(array $aModuleVersions, array $aSelectedModules): void
	{
		$aNextDependencies = [];

		foreach ($this->aRemainingDependenciesToResolve as $sDependencyExpression => $oModuleDependency) {
			/** @var DependencyExpression $oModuleDependency*/
			$oModuleDependency->UpdateModuleResolutionState($aModuleVersions, $aSelectedModules);
			if (!$oModuleDependency->IsResolved()) {
				$aNextDependencies[$sDependencyExpression] = $oModuleDependency;
			}
		}

		$this->aRemainingDependenciesToResolve = $aNextDependencies;
	}

	/**
	 * @return array: list of unique module names
	 */
	public function GetUnresolvedDependencyModuleNames(): array
	{
		$aRes = [];
		foreach ($this->aRemainingDependenciesToResolve as $sDependencyExpression => $oModuleDependency) {
			/** @var DependencyExpression $oModuleDependency */
			$aRes = array_merge($aRes, $oModuleDependency->GetRemainingModuleNamesToResolve());
		}

		return array_unique($aRes);
	}
}

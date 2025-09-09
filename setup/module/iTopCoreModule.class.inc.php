<?php

require_once(__DIR__ . '/ItopCoreModuleDependency.class.inc.php');

/**
 * Class that handles a modules and all its dependencies
 */
class iTopCoreModule {
	private string $sModuleId;
	private string $sModuleName;
	private string $sVersion;

	public array $aAllDependencies;
	public array $aOngoingDependencies;

	public function __construct(string $sModuleId)
	{
		$this->sModuleId = $sModuleId;
		list($this->sModuleName, $this->sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
		if (strlen($this->sVersion) == 0) {
			// No version number found, assume 1.0.0
			$this->sVersion = '1.0.0';
		}
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
	 * @param array $aAllDependencies: list of dependencies (string)
	 *
	 * @return void
	 */
	public function SetDependencies(array $aAllDependencies): void
	{
		$this->aAllDependencies = $aAllDependencies;
		$this->aOngoingDependencies = [];

		foreach ($aAllDependencies as $sDepString){
			$this->aOngoingDependencies[$sDepString]= new iTopCoreModuleDependency($sDepString);
		}
	}

	/**
	 * Check if module dependencies are resolved with current list of module versions
	 * @param array $aModuleVersions : versions by module names dict
	 * @param array $aSelectedModules : modules names dict
	 *
	 * @return bool
	 */
	public function IsModuleResolved(array $aModuleVersions, array $aSelectedModules) : bool
	{
		$aNextDependencies=[];
		$bDependenciesSolved = true;
		foreach($this->aOngoingDependencies as $sDepId => $oModuleDependency)
		{
			/** @var iTopCoreModuleDependency $oModuleDependency*/
			if (!$oModuleDependency->IsDependencyResolved($aModuleVersions, $aSelectedModules))
			{
				$aNextDependencies[$sDepId]=$oModuleDependency;
				$bDependenciesSolved = false;
			}
		}

		$this->aOngoingDependencies=$aNextDependencies;

		return $bDependenciesSolved;
	}

	/**
	 * @return array: list of unique module names
	 */
	public function GetUnresolvedDependencyModuleNames(): array
	{
		$aRes=[];
		foreach($this->aOngoingDependencies as $sDepId => $oModuleDependency) {
			/** @var iTopCoreModuleDependency $oModuleDependency */
			$aRes = array_merge($aRes, $oModuleDependency->GetPotentialPrerequisiteModuleNames());
		}

		return array_unique($aRes);
	}
}
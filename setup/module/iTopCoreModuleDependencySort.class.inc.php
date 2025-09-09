<?php

require_once(__DIR__ . '/iTopCoreModule.class.inc.php');

/**
 * Class that sorts module dependencies
 */
class iTopCoreModuleDependencySort {
	private static iTopCoreModuleDependencySort $oInstance;

	protected function __construct() {
	}

	final public static function GetInstance(): iTopCoreModuleDependencySort {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?iTopCoreModuleDependencySort $oInstance): void {
		static::$oInstance = $oInstance;
	}

	/**
	 * This method is key as it sorts modules by their dependencies (topological sort).
	 * Modules with less dependencies are first.
	 * When module A depends from module B with same amount of dependencies, moduleB is first.
	 * This order can deal with
	 *      - cyclic dependencies
	 *      - further versions of same module (name)
	 *
	 * @param array $aUnresolvedDependencyModules: dict of Module objects by moduleId key
	 *
	 * @return void
	 */
	public function SortModulesByCountOfDepencenciesDescending(array &$aUnresolvedDependencyModules) : void
	{
		$aCountDepsByModuleId=[];
		$aDependsOnModuleName=[];

		foreach($aUnresolvedDependencyModules as $sModuleId => $oModule) {
			/** @var iTopCoreModule $oModule */
			$aDependsOnModuleName[$oModule->GetModuleName()]=[];
		}

		foreach ($aUnresolvedDependencyModules as $sModuleId => $oModule) {
			$iInDegreeCounter = 0;
			/** @var iTopCoreModule $oModule */
			$aUnresolvedDependencyModuleNames = $oModule->GetUnresolvedDependencyModuleNames();
			foreach ($aUnresolvedDependencyModuleNames as $sModuleName) {
				if (array_key_exists($sModuleName, $aDependsOnModuleName)) {
					$aDependsOnModuleName[$sModuleName][] = $sModuleId;
					$iInDegreeCounter++;
				}
			}
			//include all modules
			$iInDegreeCounterIncludingOutsideModules = count($oModule->GetUnresolvedDependencyModuleNames());
			$aCountDepsByModuleId[$sModuleId] = [$iInDegreeCounter, $iInDegreeCounterIncludingOutsideModules, $sModuleId];
		}

		$aRes=[];
		while(count($aUnresolvedDependencyModules)>0) {
			asort($aCountDepsByModuleId);

			uasort($aCountDepsByModuleId, function (array $aDeps1, array $aDeps2){
				//compare $iInDegreeCounter
				$res  = $aDeps1[0] - $aDeps2[0];
				if ($res != 0){
					return $res;
				}

				//compare $iInDegreeCounterIncludingOutsideModules
				$res = $aDeps1[1] - $aDeps2[1];
				if ($res != 0){
					return $res;
				}

				//alphabetical order at least
				return strcmp($aDeps1[2], $aDeps2[2]);
			});

			$bOneLoopAtLeast=false;
			foreach ($aCountDepsByModuleId as $sModuleId => $iInDegreeCounter){
				$oModule=$aUnresolvedDependencyModules[$sModuleId];

				if ($bOneLoopAtLeast && $iInDegreeCounter>0){
					break;
				}

				unset($aUnresolvedDependencyModules[$sModuleId]);
				unset($aCountDepsByModuleId[$sModuleId]);

				$aRes[$sModuleId]=$oModule;

				//when 2 versions of the same module (name) below array has been removed already
				if (array_key_exists($oModule->GetModuleName(), $aDependsOnModuleName)) {
					foreach ($aDependsOnModuleName[$oModule->GetModuleName()] as $sModuleId2) {
						if (! array_key_exists($sModuleId2, $aCountDepsByModuleId)){
							continue;
						}
						$aDepCount = $aCountDepsByModuleId[$sModuleId2];
						$iInDegreeCounter = $aDepCount[0] - 1;
						$iInDegreeCounterIncludingOutsideModules = $aDepCount[1];
						$aCountDepsByModuleId[$sModuleId2] = [$iInDegreeCounter, $iInDegreeCounterIncludingOutsideModules, $sModuleId2];
					}

					unset($aDependsOnModuleName[$oModule->GetModuleName()]);
				}

				$bOneLoopAtLeast=true;
			}
		}

		$aUnresolvedDependencyModules=$aRes;
	}

	/**
	 * Arrange an list of modules, based on their (inter) dependencies
	 * @param array $aModules The list of modules to process: 'id' => $aModuleInfo
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 * @param int $iLoopCount: used to count loop count for testing purpose (see if algo is optimized)
	 * @return array
	 * @throws \MissingDependencyException
	 */
	public function OrderModulesByDependencies($aModules, $bAbortOnMissingDependency = false, $aModulesToLoad = null, ?int &$iLoopCount=0)
	{
		$iLoopCount=0;

		// Order the modules to take into account their inter-dependencies
		$aUnresolvedDependencyModules = [];
		$aSelectedModules = [];
		foreach($aModules as $sModuleId => $aModule)
		{
			$oModule = new iTopCoreModule($sModuleId);
			$sModuleName = $oModule->GetModuleName();
			if (is_null($aModulesToLoad) || in_array($sModuleName, $aModulesToLoad))
			{
				$oModule->SetDependencies($aModule['dependencies']);
				$aUnresolvedDependencyModules[$sModuleId]=$oModule;
				$aSelectedModules[$sModuleName] = true;
			}
		}
		self::SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		$aOrderedModules = [];
		$aModuleVersions=[];
		$iPreviousLoopDepencyCount=-1;
		$iNextLoopCount=count($aUnresolvedDependencyModules);
		while(($iNextLoopCount!=$iPreviousLoopDepencyCount) //stop loop when no new dependency is resolved
			&& ($iNextLoopCount > 0) //still remaining dependencies
		)
		{
			$iLoopCount++;
			$iPreviousLoopDepencyCount=$iNextLoopCount;
			foreach($aUnresolvedDependencyModules as $sModuleId => $oModule)
			{
				/** @var iTopCoreModule $oModule */
				if ($oModule->IsModuleResolved($aModuleVersions, $aSelectedModules)){
					$aOrderedModules[] = $sModuleId;
					$aModuleVersions[$oModule->GetModuleName()] = $oModule->GetVersion();
					unset($aUnresolvedDependencyModules[$sModuleId]);
				}
			}

			$iNextLoopCount=count($aUnresolvedDependencyModules);
			self::SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
		}

		if ($bAbortOnMissingDependency && count($aUnresolvedDependencyModules) > 0)
		{
			self::SortModulesByCountOfDepencenciesDescending($aUnresolvedDependencyModules);
			$aModulesInfo = [];
			$aModuleDeps = [];
			/** @var iTopCoreModule $oModule */
			foreach($aUnresolvedDependencyModules as $sModuleId => $oModule)
			{
				$aModule = $aModules[$sModuleId];
				$aDepsWithIcons = [];
				foreach($oModule->aAllDependencies as $sIndex => $sDepId)
				{
					if (array_key_exists($sDepId, $oModule->aOngoingDependencies))
					{
						$aDepsWithIcons[$sIndex] = '❌ ' .  $sDepId;
					} else
					{
						$aDepsWithIcons[$sIndex] = '✅ ' . $sDepId;
					}
				}
				$aModuleDeps[] = "{$aModule['label']} (id: $sModuleId) depends on: ".implode(' + ', $aDepsWithIcons);
				$aModulesInfo[$sModuleId] = array('module' => $aModule, 'dependencies' => $aDepsWithIcons);
			}
			$sMessage = "The following modules have unmet dependencies:\n".implode(",\n", $aModuleDeps);
			$oException = new MissingDependencyException($sMessage);
			$oException->aModulesInfo = $aModulesInfo;
			throw $oException;
		}
		// Return the ordered list, so that the dependencies are met...
		$aResult = array();
		foreach($aOrderedModules as $sModuleId)
		{
			$aResult[$sModuleId] = $aModules[$sModuleId];
		}
		return $aResult;
	}
}
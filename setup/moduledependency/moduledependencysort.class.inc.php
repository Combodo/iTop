<?php

namespace Combodo\iTop\Setup\ModuleDependency;

require_once(__DIR__.'/module.class.inc.php');

use MissingDependencyException;

/**
 * Class that sorts module dependencies
 */
class ModuleDependencySort {
	private static ModuleDependencySort $oInstance;

	protected function __construct() {
	}

	final public static function GetInstance(): ModuleDependencySort {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?ModuleDependencySort $oInstance): void {
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
	public static function SortModulesByCountOfDepencenciesDescending(array &$aUnresolvedDependencyModules) : void
	{
		$aCountDepsByModuleId=[];
		$aDependsOnModuleName=[];

		foreach($aUnresolvedDependencyModules as $sModuleId => $oModule) {
			/** @var Module $oModule */
			$aDependsOnModuleName[$oModule->GetModuleName()]=[];
		}

		foreach ($aUnresolvedDependencyModules as $sModuleId => $oModule) {
			$iInDegreeCounter = 0;
			/** @var Module $oModule */
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
}
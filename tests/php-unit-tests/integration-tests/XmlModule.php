<?php

namespace Combodo\iTop\Test\UnitTest;

class XmlModule {
	public string $sModuleName;
	public array $aDependencyModulesNames=[];
	public array $aAllDependencyModulesNames=[];
	public array $aXMlMetaInfosByModuleNames=[];

	public function __construct(string $sModuleName)
	{
		$this->sModuleName = $sModuleName;
	}

	public function AddDependency(string $sXmlMetaInfoUID, array $aDefiningModuleNames, array $aModules)
	{
		$aRemainingModules=[];
		foreach ($aDefiningModuleNames as $sDefiningModuleName) {
			if ($sDefiningModuleName === $this->sModuleName) {
				continue;
			}

			if ($sDefiningModuleName === "core" || $sDefiningModuleName === "application") {
				continue;
			}

			$aRemainingModules[]=$sDefiningModuleName;
		}

		if (count($aRemainingModules)==0){
			return;
		}

		/*$aLog = ['itop-bridge-datacenter-mgmt-services', 'itop-datacenter-mgmt'];
		if (in_array($sDefiningModuleName, $aLog) && in_array($this->sModuleName, $aLog)){
			echo $this->sModuleName . " => $sDefiningModuleName === " . $sXmlMetaInfoUID . "\n";
		}*/

		$sKey=implode(' || ', $aRemainingModules);
		if (! array_key_exists($sKey, $this->aXMlMetaInfosByModuleNames)){
			$this->aXMlMetaInfosByModuleNames[$sKey]=[$sXmlMetaInfoUID];
		} else {
			if (! in_array($sXmlMetaInfoUID, $this->aXMlMetaInfosByModuleNames[$sKey])){
				$this->aXMlMetaInfosByModuleNames[$sKey][]=$sXmlMetaInfoUID;
			}
		}

		if (! array_key_exists($sKey, $this->aDependencyModulesNames)){
			$aCurrentModules=[];
			foreach ($aRemainingModules as $sDefiningModuleName) {
				/** @var XmlModule $oXmlModule */
				$oXmlModule = $aModules[$sDefiningModuleName];
				$aCurrentModules[]=$oXmlModule;
			}
			$this->aDependencyModulesNames[$sKey]=$aCurrentModules;
		}
	}

	public function Depends(string $sModuleName) : bool
	{
		return array_key_exists($sModuleName, $this->aDependencyModulesNames) || array_key_exists($sModuleName, $this->aAllDependencyModulesNames);
	}

	public function __toString(): string
	{
		return sprintf("%s (%s)", $this->sModuleName, implode(' & ', array_keys($this->aDependencyModulesNames)));
	}

	public function CompleteModuleDependencies(array $aAllModules) : void
	{
		foreach ($this->aDependencyModulesNames as $sDirectDependency => $oXmlModules){
			/** @var \Combodo\iTop\Test\UnitTest\XmlModule $oDirectDepXmlModule */
			$oDirectDepXmlModule = $aAllModules[$sDirectDependency] ?? null;
			if (! is_null($oDirectDepXmlModule)) {
				foreach ($oDirectDepXmlModule->aDependencyModulesNames as $sDirectDependency2 => $oXmlModules2) {
					if (!array_key_exists($sDirectDependency2, $this->aDependencyModulesNames) && !in_array($sDirectDependency2, $this->aAllDependencyModulesNames)) {
						$this->aAllDependencyModulesNames[] = $sDirectDependency2;
					}
				}
			}
		}
	}


}
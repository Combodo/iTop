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

	public function AddDependency(string $sXmlMetaInfoUID, string $sDefiningModuleName, array $aModules)
	{
		if ($sDefiningModuleName===$this->sModuleName){
			return;
		}

		/*$aLog = ['itop-bridge-datacenter-mgmt-services', 'itop-datacenter-mgmt'];
		if (in_array($sDefiningModuleName, $aLog) && in_array($this->sModuleName, $aLog)){
			echo $this->sModuleName . " => $sDefiningModuleName === " . $sXmlMetaInfoUID . "\n";
		}*/

		if (! array_key_exists($sDefiningModuleName, $this->aXMlMetaInfosByModuleNames)){
			$this->aXMlMetaInfosByModuleNames[$sDefiningModuleName]=[$sXmlMetaInfoUID];
		} else {
			if (! in_array($sXmlMetaInfoUID, $this->aXMlMetaInfosByModuleNames[$sDefiningModuleName])){
				$this->aXMlMetaInfosByModuleNames[$sDefiningModuleName][]=$sXmlMetaInfoUID;
			}
		}

		if (! array_key_exists($sDefiningModuleName, $this->aDependencyModulesNames)){
			/** @var XmlModule $oXmlModule */
			$oXmlModule = $aModules[$sDefiningModuleName];
			$this->aDependencyModulesNames[$sDefiningModuleName]=$oXmlModule;
		}
	}

	public function Depends(string $sModuleName) : bool
	{
		return array_key_exists($sModuleName, $this->aDependencyModulesNames) || array_key_exists($sModuleName, $this->aXMlMetaInfosByModuleNames);
	}

	public function __toString(): string
	{
		return sprintf("%s (%s)", $this->sModuleName, implode('|', array_keys($this->aDependencyModulesNames)));
	}

	public function CompleteModuleDependencies(array $aAllModules) : void
	{
		foreach ($this->aDependencyModulesNames as $sDirectDependency => $oXmlMod){
			/** @var \Combodo\iTop\Test\UnitTest\XmlModule $oDirectDepXmlModule */
			$oDirectDepXmlModule = $aAllModules[$sDirectDependency];
			foreach ($oDirectDepXmlModule->aDependencyModulesNames as $sDirectDependency2 => $oXmlMod2){
				if (! array_key_exists($sDirectDependency2, $this->aDependencyModulesNames) && ! in_array($sDirectDependency2, $this->aAllDependencyModulesNames)){
					$this->aAllDependencyModulesNames[]=$sDirectDependency2;
				}
			}
		}
	}


}
<?php

class iTopExtensionsMapFake extends iTopExtensionsMap
{
	public function __construct($sFromEnvironment = 'production', $aExtraDirs = [])
	{
		$this->aExtensions = [];
		$this->aExtensionsByCode = [];
		$this->aScannedDirs = [];
	}

	public static function createFromArray($aExtensions)
	{
		$oMap = new static();
		foreach ($aExtensions as $sCode => $aExtension) {
			$oExtension = new iTopExtension();
			$oExtension->sCode = $sCode;
			$oExtension->bInstalled = $aExtension['installed'];
			$oExtension->aModules = $aExtension['modules'] ?? [];
			$oExtension->bCanBeUninstalled = $aExtension['uninstallable'] ?? null;
			$oExtension->sVersion = $aExtension['version'] ?? '1.0.0';
			$oExtension->aModuleInfo = [];
			$oMap->AddExtension($oExtension);
		}
		return $oMap;
	}
}

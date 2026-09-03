<?php

class iTopExtensionsMapFake extends iTopExtensionsMap
{
	public $aInstalledExtensionsInfo = false;
	public array $aInstalledExtensions;
	public $aExtensions;

	public function __construct($sFromEnvironment = 'production', $aExtraDirs = [])
	{
		$this->aExtensions = [];
		$this->aExtensionsByCode = [];
		$this->aScannedDirs = [];
	}

	public static function createFromArray($aExtensions): static
	{
		$oMap = new static();

		foreach ($aExtensions as $sCode => $aExtension) {
			$oExtension = new iTopExtension();
			$oExtension->sCode = $sCode;
			$oExtension->sLabel = $sCode;
			$oExtension->bInstalled = $aExtension['installed'];
			$oExtension->aModules = $aExtension['modules'] ?? [];
			$oExtension->bCanBeUninstalled = $aExtension['uninstallable'] ?? null;
			$oExtension->sVersion = $aExtension['version'] ?? '1.0.0';
			$oExtension->sSource = $aExtension['source'] ?? iTopExtension::SOURCE_MANUAL;
			$oExtension->aModuleInfo = $aExtension['module_info'] ?? [];
			$oExtension->aMissingDependencies = $aExtension['missing_dependencies'] ?? [];
			$oMap->AddExtension($oExtension);
		}
		return $oMap;
	}

	public function AddExtension(iTopExtension $oNewExtension)
	{
		parent::AddExtension($oNewExtension);
	}

	protected function FetchExtensionInfoFromDatabase(Config $oConfig): array|false
	{

		return $this->aInstalledExtensionsInfo;
	}

	public function AddInstalledExtensionInfo(string $sCode, string $sVersion = '1.0.0', string $sSource = iTopExtension::SOURCE_MANUAL, string $sUninstallable = 'yes'): void
	{
		if ($this->aInstalledExtensionsInfo === false) {
			$this->aInstalledExtensionsInfo = [];
		}
		$this->aInstalledExtensionsInfo[] = [
			'code' => $sCode,
			'label' => $sCode,
			'version' => $sVersion,
			'source' => $sSource,
			'uninstallable' => $sUninstallable,
		];
	}
}

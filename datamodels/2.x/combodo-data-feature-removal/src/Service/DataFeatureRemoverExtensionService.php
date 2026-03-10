<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use iTopExtension;
use iTopExtensionsMap;
use MetaModel;

class DataFeatureRemoverExtensionService
{
	private static DataFeatureRemoverExtensionService $oInstance;

	private array $aSelectedExtensions = [];
	private array $aItopExtensions = [];
	private array $aIncludingExtensionsByModuleName = [];

	protected function __construct()
	{
	}

	final public static function GetInstance(): DataFeatureRemoverExtensionService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new DataFeatureRemoverExtensionService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?DataFeatureRemoverExtensionService $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	/**
	 * @param string $sModuleName
	 *
	 * @return array
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function GetIncludingExtensions(string $sModuleName): array
	{
		if (count($this->aIncludingExtensionsByModuleName) === 0) {
			foreach ($this->ReadiTopExtensions() as $oExtension) {
				$aModuleNames = $oExtension->aModules;
				if (is_array($aModuleNames) && count($aModuleNames) > 0) {
					foreach ($aModuleNames as $sModule) {
						$aExtensions = $this->aIncludingExtensionsByModuleName[$sModule] ?? [];
						$aExtensions[] = $oExtension->sLabel.'/'.$oExtension->sVersion;
						$this->aIncludingExtensionsByModuleName[$sModule] = $aExtensions;
					}
				}
			}
		}

		return $this->aIncludingExtensionsByModuleName[$sModuleName] ?? [];
	}

	/**
	 * @return iTopExtension[]
	 */
	public function ReadItopExtensions(): array
	{
		if (count($this->aItopExtensions) === 0) {
			$oExtensionsMap = new iTopExtensionsMap();
			$oExtensionsMap->LoadInstalledExtensionsFromDatabase(MetaModel::GetConfig());
			$this->aItopExtensions = $oExtensionsMap->GetAllExtensionsToDisplayInSetup(true);

			uasort($this->aItopExtensions, function (iTopExtension $oiTopExtension1, iTopExtension $oiTopExtension2) {
				return strcmp($oiTopExtension1->sLabel, $oiTopExtension2->sLabel);
			});
		}

		return $this->aItopExtensions;
	}
}

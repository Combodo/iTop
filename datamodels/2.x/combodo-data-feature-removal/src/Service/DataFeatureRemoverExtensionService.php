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
	private ?iTopExtensionsMap $oMap = null;
	private array $aItopExtensions = [];
	private array $aIncludingExtensionsByModuleName = [];

	public function __construct()
	{
	}

	/**
	 * @param string $sModuleName
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetIncludingExtensions(string $sModuleName): array
	{
		if (count($this->aIncludingExtensionsByModuleName) === 0) {
			foreach ($this->ReadItopExtensions() as $oExtension) {
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
	 * @return \iTopExtensionsMap
	 * @throws \Exception
	 */
	public function GetExtensionMap(): iTopExtensionsMap
	{
		if (is_null($this->oMap)) {
			$this->oMap = iTopExtensionsMap::GetExtensionsMap();
			$this->oMap->LoadInstalledExtensionsFromDatabase(MetaModel::GetConfig());
		}
		return $this->oMap;
	}

	/**
	 * @return iTopExtension[]
	 * @throws \Exception
	 */
	public function ReadItopExtensions(): array
	{
		if (count($this->aItopExtensions) === 0) {
			$this->aItopExtensions = $this->GetExtensionMap()->GetAllExtensionsToDisplayInSetup(true);

			uasort($this->aItopExtensions, function (iTopExtension $oiTopExtension1, iTopExtension $oiTopExtension2) {
				return strcmp($oiTopExtension1->sLabel, $oiTopExtension2->sLabel);
			});
		}

		return $this->aItopExtensions;
	}
}

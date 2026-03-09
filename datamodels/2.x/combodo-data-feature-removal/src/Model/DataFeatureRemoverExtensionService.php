<?php

namespace Combodo\iTop\DataFeatureRemoval\Model;

use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use DataFeatureRemoverExtension;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use iTopExtension;
use iTopExtensionsMap;
use MetaModel;

class DataFeatureRemoverExtensionService
{
	private static DataFeatureRemoverExtensionService $oInstance;

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
	 * @param array $aSelectedExtensionsForCheck
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function SaveExtensions(array $aSelectedExtensionsForCheck): void
	{
		$this->ReadItopExtensions();

		$oSearch = DBObjectSearch::FromOQL('SELECT DataFeatureRemoverExtension', []);
		$oSearch->AllowAllData();
		$oSet = new DBObjectSet($oSearch);

		while (null != ($oObj = $oSet->Fetch())) {
			$oObj->DBDelete();
		}

		foreach ($aSelectedExtensionsForCheck as $i => $sCode) {
			$oObj = new DataFeatureRemoverExtension();
			$oObj->Set('extension_code', $sCode);
			/** @var iTopExtension $oExtension */
			$oExtension = $this->aItopExtensions[$sCode];
			$oObj->Set('module_names', json_encode($oExtension->aModules));
			$oObj->Set('label', $oExtension->sLabel);
			$oObj->Set('version', $oExtension->sVersion);
			$oObj->DBWrite();
		}
	}

	private array $aSelectedExtensions = [];
	private array $aItopExtensions = [];
	private array $aIncludingExtensionsByModuleName = [];

	/**
	 * @return array
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function ReadAuditedExtensions(): array
	{
		if (count($this->aSelectedExtensions) == 0) {
			try {
				$oSearch = DBObjectSearch::FromOQL('SELECT DataFeatureRemoverExtension', []);
				$oSearch->AllowAllData();
				$oSet = new DBObjectSet($oSearch);

				while (null != ($oObj = $oSet->Fetch())) {
					$sCode = $oObj->Get('extension_code');
					$sLabel = $oObj->Get('label');
					$sVersion = $oObj->Get('version');

					$sModuleNames = $oObj->Get('module_names');
					$aModuleNames = json_decode($sModuleNames, true);
					if (is_array($aModuleNames) && count($aModuleNames) > 0) {
						foreach ($aModuleNames as $sModuleName) {
							$aExtensions = $this->aIncludingExtensionsByModuleName[$sModuleName] ?? [];
							$aExtensions[] = "$sLabel / $sVersion";
							$this->aIncludingExtensionsByModuleName[$sModuleName] = $aExtensions;

						}
					}
					$this->aSelectedExtensions[$sCode] = $oObj;
				}
			} catch (Exception $e) {
				throw new DataFeatureRemovalException(__FUNCTION__.' failed', 0, $e);
			}
		}

		\IssueLog::Debug(__METHOD__, null, ['aSelectedExtensionsForCheck' => $this->aSelectedExtensions]);
		\IssueLog::Debug(__METHOD__, null, ['aIncludingExtensionsByModuleName' => $this->aIncludingExtensionsByModuleName]);

		return $this->aSelectedExtensions;
	}

	/**
	 * @param string $sModuleName
	 *
	 * @return array
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function GetIncludingExtensions(string $sModuleName): array
	{
		$this->ReadAuditedExtensions();
		return $this->aIncludingExtensionsByModuleName[$sModuleName] ?? [];
	}

	/**
	 * @return iTopExtension[]
	 */
	public function ReadItopExtensions(): array
	{
		if (count($this->aItopExtensions) == 0) {
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

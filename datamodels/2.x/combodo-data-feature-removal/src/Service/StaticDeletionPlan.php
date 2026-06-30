<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use AttributeExternalKey;
use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanEntity;
use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem;
use MetaModel;

class StaticDeletionPlan
{
	/** @var array<DeletionPlanEntity> */
	private array $aDeletionPlan = [];

	/**
	 * Get a summary of the deletion plan computed for the classes.
	 * The result is used for display
	 *
	 * @param array|null $aClasses
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity>
	 * @throws \CoreException
	 */
	public function GetCleanupSummary(?array $aClasses): array
	{
		$aSummary = [];
		$aDeletionPlan = $this->GetStaticDeletionPlan($aClasses ?? []);

		foreach ($aDeletionPlan as $sClass => $oDeletionPlanEntity) {
			if ($oDeletionPlanEntity->TotalCount() === 0) {
				continue;
			}
			$oDeletionPlanEntity->FilterUpdatesByDeletes();
			$oDataCleanupSummary = new DataCleanupSummaryEntity($sClass);
			$oDataCleanupSummary->iUpdateCount = $oDeletionPlanEntity->oUpdate->Count();
			$oDataCleanupSummary->iDeleteCount = $oDeletionPlanEntity->oDelete->Count();
			$oDataCleanupSummary->iIssueCount = $oDeletionPlanEntity->oIssue->Count();

			$aSummary[$sClass] = $oDataCleanupSummary;
		}

		return $aSummary;
	}

	public function GetTempTableDefinitions(array $aClasses): array
	{
		$aTempTables = [];
		$aDeletionPlan = $this->GetStaticDeletionPlan($aClasses ?? []);

		foreach ($aDeletionPlan as $sClass => $oDeletionPlanEntity) {
			$sTempTableName = DeletionPlanEntity::GetTempTableName($sClass);
			$aTempTables[$sTempTableName]['name'] = $sTempTableName;
			$aTempTables[$sTempTableName]['queries'] = array_unique(array_merge($aTempTables[$sTempTableName]['queries'] ?? [], $oDeletionPlanEntity->aQueriesForTempTable));
			$aTempTables[$sTempTableName]['depends_on']  = array_unique(array_merge($aTempTables[$sTempTableName]['depends_on'] ?? [], $oDeletionPlanEntity->aDependsOnTempTable));
		}

		usort($aTempTables, function ($a, $b) use ($aTempTables) {
			if (empty($a['depends_on']) && empty($b['depends_on'])) {
				// Both initial classes
				return 0;
			}
			return $this->CompareEntries($a, $b, $aTempTables);
		});

		$aTableDefinitions = [];

		foreach ($aTempTables as $aTempTable) {
			if (count($aTempTable['queries']) > 0) {
				$TempTableSelect = implode("\nUNION\n", $aTempTable['queries']);
				$sTempTableName = $aTempTable['name'];
				$aTableDefinitions[$sTempTableName] = [
					"DROP TEMPORARY TABLE IF EXISTS `$sTempTableName`",
					"CREATE TEMPORARY TABLE `$sTempTableName` ($TempTableSelect)",
				];
			}
		}

		return $aTableDefinitions;
	}

	/**
	 * tells if name1 depends on name2
	 * @param string $sName1
	 * @param string $sName2
	 * @param array $aAllTables
	 *
	 * @return bool
	 */
	private function DependsOn(string $sName1, string $sName2, array $aAllTables): bool
	{
		$aDependsOn = $aAllTables[$sName1]['depends_on'] ?? [];
		if (in_array($sName2, $aDependsOn)) {
			return true;
		}
		// Search on step further
		foreach ($aAllTables[$sName1]['depends_on'] as $sParent) {
			if ($this->DependsOn($sParent, $sName2, $aAllTables)) {
				return true;
			}
		}
		return false;
	}

	public function CompareEntries(array $aTable1, array $aTable2, array $aAllTables): int
	{
		return $this->DependsOn($aTable1['name'], $aTable2['name'], $aAllTables) ? 1 : -1;
	}

	/**
	 * @param array $aClasses Classes to clean entirely
	 *
	 * @return array ['class' => DeletionPlanEntity];
	 *
	 * @throws \CoreException
	 */
	public function GetStaticDeletionPlan(array $aClasses): array
	{
		foreach ($aClasses as $sClass) {
			$oDeletionPlanItem = $this->GetInitialClassDeletionPlan($sClass);
			$oDeletionPlanEntity = new DeletionPlanEntity($sClass);
			$oDeletionPlanEntity->oDelete->Merge($oDeletionPlanItem);
			$oDeletionPlanEntity->AddQueryForTempTable($this->GetQueryForInitialClass($sClass));
			$this->aDeletionPlan[$sClass] = $oDeletionPlanEntity;

			$this->DeletionPlanForReferencingClasses($sClass);
		}

		return $this->aDeletionPlan;
	}

	private function DeletionPlanForReferencingClasses(string $sClass): void
	{
		$sIdsToRemove = implode(', ', $this->aDeletionPlan[$sClass]->oDelete->aIds);
		$aReferencingMe = MetaModel::EnumReferencingClasses($sClass);
		foreach ($aReferencingMe as $sRemoteClass => $aExtKeys) {
			if (!isset($this->aDeletionPlan[$sRemoteClass])) {
				$this->aDeletionPlan[$sRemoteClass] = new DeletionPlanEntity($sRemoteClass);
			}
			$oDeletionPlanEntity = $this->aDeletionPlan[$sRemoteClass];
			/** @var \AttributeExternalKey $oExtKeyAttDef */
			foreach ($aExtKeys as $sExtKeyAttCode => $oExtKeyAttDef) {
				// skip if this external key is behind an external field
				if (!$oExtKeyAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) {
					continue;
				}

				if ($oExtKeyAttDef->IsNullAllowed()) {
					// update
					$oUpdateItem = $this->UpdateExtKeyNullable($sRemoteClass, $sExtKeyAttCode, $sIdsToRemove);
					$oDeletionPlanEntity->oUpdate->Merge($oUpdateItem);
				} else {
					// delete
					$aRemoteIdsToRemove = $this->GetRemoteIdsForExtKey($sRemoteClass, $sExtKeyAttCode, $sIdsToRemove);

					$iDeletePropagationOption = $oExtKeyAttDef->GetDeletionPropagationOption();
					if ($iDeletePropagationOption == DEL_MANUAL) {
						// Issue, do not recurse
						$oDeletionPlanItem = new DeletionPlanItem($aRemoteIdsToRemove);
						$oDeletionPlanEntity->oIssue->Merge($oDeletionPlanItem);
						continue;
					}

					if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
						// update hierarchical keys due to row cleanup in the same table
						$sTargetIdsToRemove = implode(',', $this->aDeletionPlan[$sRemoteClass]->oDelete->aIds);
						$oUpdateItem = $this->UpdateHierarchicalExtKey($sRemoteClass, $sExtKeyAttCode, $sTargetIdsToRemove);
						$oDeletionPlanEntity->oUpdate->Merge($oUpdateItem);

						// Delete current entry an recurse !
					}

					// Delete entries in Remote Class
					[$sQueryForTempTable, $sDependsOnTempTable] = $this->GetQueryForExtKey($sRemoteClass, $oExtKeyAttDef);
					$oDeletionPlanEntity->AddQueryForTempTable($sQueryForTempTable, $sDependsOnTempTable);
					$oDeletionPlanEntity->oDelete->Merge(new DeletionPlanItem($aRemoteIdsToRemove));
					// Infinite loops do not occurs due to the datamodel structure
					$this->DeletionPlanForReferencingClasses($sRemoteClass);
				}
			}
		}
	}

	/**
	 * @param string $sRemoteClass
	 * @param string $sExtKeyAttCode
	 * @param string $sIdsToRemoveInTargetClass
	 *
	 * @return \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem
	 * @throws \CoreException
	 */
	public function UpdateExtKeyNullable(string $sRemoteClass, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): DeletionPlanItem
	{
		$aIds = $this->GetRemoteIdsForExtKey($sRemoteClass, $sExtKeyAttCode, $sIdsToRemoveInTargetClass);

		return new DeletionPlanItem($aIds);
	}

	/**
	 * @param string $sRemoteClass
	 * @param string $sExtKeyAttCode
	 * @param string $sIdsToRemoveInTargetClass
	 *
	 * @return \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public function UpdateHierarchicalExtKey(string $sRemoteClass, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): DeletionPlanItem
	{
		[$sDBTable, $sDBKey, $sDBField] = $this->GetDBInfo($sRemoteClass, $sExtKeyAttCode);

		$sSQL = <<<SQL
SELECT `$sDBKey`
FROM `$sDBTable` AS `updated`
INNER JOIN `$sDBTable` AS `removed` ON `updated`.`$sDBField` = `removed`.`$sDBField`
WHERE `removed`.`$sDBKey` IN ($sIdsToRemoveInTargetClass)
SQL;
		$aIds = CMDBSource::QueryToCol($sSQL, $sDBKey);

		return new DeletionPlanItem($aIds);
	}

	/**
	 * @param string $sRemoteClass
	 * @param string $sExtKeyAttCode
	 * @param string $sIdsToRemoveInTargetClass
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public function GetRemoteIdsForExtKey(string $sRemoteClass, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): array
	{
		if (\utils::IsNullOrEmptyString($sIdsToRemoveInTargetClass)) {
			return [];
		}
		[$sDBTable, $sDBKey, $sDBField] = $this->GetDBInfo($sRemoteClass, $sExtKeyAttCode);
		$sSQL = "SELECT `$sDBKey` AS id FROM `$sDBTable` WHERE `$sDBField` IN ($sIdsToRemoveInTargetClass)";

		return CMDBSource::QueryToCol($sSQL, 'id');
	}

	/**
	 * @param string $sClass
	 *
	 * @return \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public function GetInitialClassDeletionPlan(string $sClass): DeletionPlanItem
	{
		[$sDBTable, $sDBKey] = $this->GetDBInfo($sClass);

		$sSQL = "SELECT `$sDBKey` FROM `$sDBTable`";
		$aIds = CMDBSource::QueryToCol($sSQL, $sDBKey);

		return new DeletionPlanItem($aIds);
	}

	/**
	 * Get database table for an attcode
	 *
	 * @param string $sClass
	 * @param string $sExtKeyAttCode
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetDBInfo(string $sClass, ?string $sExtKeyAttCode = null): array
	{
		if (!is_null($sExtKeyAttCode)) {
			$sOriginClass = MetaModel::GetAttributeOrigin($sClass, $sExtKeyAttCode);

			$sDBTable = MetaModel::DBGetTable($sOriginClass);
			$sDBKey = MetaModel::DBGetKey($sOriginClass);

			$oAttDef = MetaModel::GetAttributeDef($sOriginClass, $sExtKeyAttCode);
			// External key is on a single DB column
			$sDBField = array_keys($oAttDef->GetSQLColumns())[0];

			return [$sDBTable, $sDBKey, $sDBField];
		}

		$sDBTable = MetaModel::DBGetTable($sClass);
		$sDBKey = MetaModel::DBGetKey($sClass);

		return [$sDBTable, $sDBKey];
	}

	private function GetQueryForInitialClass(mixed $sClass): string
	{
		[$sDBTable, $sDBKey] = $this->GetDBInfo($sClass);

		return "SELECT `$sDBKey` AS id FROM `$sDBTable`";
	}

	private function GetQueryForExtKey(string $sClass, AttributeExternalKey $oExtKeyAttDef)
	{
		$sExtKeyAttCode = $oExtKeyAttDef->GetCode();
		[$sDBTable, $sDBKey, $sDBField] = $this->GetDBInfo($sClass, $sExtKeyAttCode);

		$sTargetClass = $oExtKeyAttDef->GetTargetClass();
		$sTempTable = DeletionPlanEntity::GetTempTableName($sTargetClass);

		$sQuery = <<<SQL
SELECT `$sDBTable`.`$sDBKey` AS id FROM `$sDBTable`
INNER JOIN `$sTempTable` ON `$sTempTable`.`id` = `$sDBTable`.`$sDBField`
SQL;

		return [$sQuery, $sTempTable];
	}
}

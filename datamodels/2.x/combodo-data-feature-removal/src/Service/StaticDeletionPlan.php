<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

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
			$oDeletionPlanEntity = new DeletionPlanEntity();
			$oDeletionPlanEntity->oDelete->Merge($oDeletionPlanItem);
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
				$this->aDeletionPlan[$sRemoteClass] =  new DeletionPlanEntity();
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
						$oDeletionPlanItem = new DeletionPlanItem(aIds: $aRemoteIdsToRemove);
						$oDeletionPlanEntity->oIssue->Merge($oDeletionPlanItem);
						continue;
					}

					if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
						// update hierarchical keys due to row cleanup in the same table
						$sIdsToRemove = implode(',', $this->aDeletionPlan[$sRemoteClass]->oDelete->aIds);
						$oUpdateItem = $this->UpdateHierarchicalExtKey($sRemoteClass, $sExtKeyAttCode, $sIdsToRemove);
						$oDeletionPlanEntity->oUpdate->Merge($oUpdateItem);
						// do not recurse
						continue;
					}

					// Delete entries in Remote Class
					if (count($aRemoteIdsToRemove) !== 0) {
						$oDeletionPlanEntity->oDelete->Merge(new DeletionPlanItem($aRemoteIdsToRemove));
						$this->DeletionPlanForReferencingClasses($sRemoteClass);
					}
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
		[$sDBTable, $sDBField, $sDBKey] = $this->GetDBInfoForAttcode($sRemoteClass, $sExtKeyAttCode);

		$sSQL = <<<SQL
SELECT `$sDBKey`
FROM `$sDBTable` AS `updated`
INNER JOIN `$sDBTable` AS `removed` ON `updated`.`$sDBField` = `removed`.`$sDBKey`
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
		[$sDBTable, $sDBField, $sDBKey] = $this->GetDBInfoForAttcode($sRemoteClass, $sExtKeyAttCode);
		$sSQL = "SELECT `$sDBKey` FROM `$sDBTable` WHERE `$sDBField` IN ($sIdsToRemoveInTargetClass)";

		return CMDBSource::QueryToCol($sSQL, $sDBKey);
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
		$sTable = MetaModel::DBGetTable($sClass);
		$sDBKey = MetaModel::DBGetKey($sClass);
		$sSQL = "SELECT `$sDBKey` FROM `$sTable`";
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
	public function GetDBInfoForAttcode(string $sClass, string $sExtKeyAttCode): array
	{
		$sOriginClass = MetaModel::GetAttributeOrigin($sClass, $sExtKeyAttCode);
		$sDBTable = MetaModel::DBGetTable($sOriginClass);
		$oAttDef = MetaModel::GetAttributeDef($sOriginClass, $sExtKeyAttCode);
		// External key is on a single DB column
		$sDBField = array_keys($oAttDef->GetSQLColumns())[0];
		$sDBKey = MetaModel::DBGetKey($sClass);
		return [$sDBTable, $sDBField, $sDBKey];
	}

}

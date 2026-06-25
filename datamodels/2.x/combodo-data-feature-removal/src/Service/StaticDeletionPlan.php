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
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function GetCleanupSummary(?array $aClasses): array
	{
		$aSummary = [];
		$aDeletionPlan = $this->GetStaticDeletionPlan($aClasses ?? []);

		foreach ($aDeletionPlan as $sClass => $oDeletionPlanEntity) {
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
			$sRemoteTable = MetaModel::DBGetTable($sRemoteClass);
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
					$oUpdateItem = $this->UpdateExtKeyNullable($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);
					$oDeletionPlanEntity->oUpdate->Merge($oUpdateItem);
				} else {
					// delete
					$aRemoteIdsToRemove = $this->GetRemoteIdsForExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);

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
						$oUpdateItem = $this->UpdateHierarchicalExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);
						$oDeletionPlanEntity->oUpdate->Merge($oUpdateItem);
						// do not recurse
						continue;
					}

					// Delete entries in Remote Class
					if (count($aRemoteIdsToRemove) !== 0) {
						$sRemoteIdsToDelete = implode(',', $aRemoteIdsToRemove);
						$sSQL = "DELETE FROM $sRemoteTable WHERE id IN ($sRemoteIdsToDelete)";
						$oDeletionPlanEntity->oDelete->Merge(new DeletionPlanItem([$sSQL], $aRemoteIdsToRemove));

						$this->DeletionPlanForReferencingClasses($sRemoteClass);
					}
				}
			}
		}
	}

	/**
	 * @param string $sRemoteTable
	 * @param string $sExtKeyAttCode
	 * @param string $sIdsToRemoveInTargetClass
	 *
	 * @return \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem
	 */
	public function UpdateExtKeyNullable(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): DeletionPlanItem
	{
		$aIds = $this->GetRemoteIdsForExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemoveInTargetClass);

		$sUpdateSQL = <<<SQL
UPDATE $sRemoteTable SET updated.$sExtKeyAttCode = 0
FROM $sRemoteTable AS updated
WHERE updated.$sExtKeyAttCode IN ($sIdsToRemoveInTargetClass)
SQL;

		return new DeletionPlanItem([$sExtKeyAttCode => $sUpdateSQL], $aIds);
	}

	public function UpdateHierarchicalExtKey(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): DeletionPlanItem
	{
		$sUpdateSQL = <<<SQL
UPDATE $sRemoteTable SET updated.$sExtKeyAttCode = removed.$sExtKeyAttCode
FROM $sRemoteTable AS updated
INNER JOIN $sRemoteTable AS removed ON updated.$sExtKeyAttCode = removed.id
WHERE removed.id IN ($sIdsToRemoveInTargetClass)
SQL;

		$sSQL = <<<SQL
SELECT id
FROM $sRemoteTable AS updated
INNER JOIN $sRemoteTable AS removed ON updated.$sExtKeyAttCode = removed.id
WHERE removed.id IN ($sIdsToRemoveInTargetClass)
SQL;
		$aIds = CMDBSource::QueryToCol($sSQL, 'id');

		return new DeletionPlanItem([$sExtKeyAttCode => $sUpdateSQL], $aIds);
	}

	public function GetRemoteIdsForExtKey(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): array
	{
		if (\utils::IsNullOrEmptyString($sIdsToRemoveInTargetClass)) {
			return [];
		}
		$sSQL = "SELECT id FROM $sRemoteTable WHERE $sExtKeyAttCode IN ($sIdsToRemoveInTargetClass)";

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
		$sTable = MetaModel::DBGetTable($sClass);
		$sSQL = "SELECT id FROM $sTable";
		$aIds = CMDBSource::QueryToCol($sSQL, 'id');
		$sDeleteSQL = "DELETE FROM $sTable";

		return new DeletionPlanItem([$sDeleteSQL], $aIds);
	}

}

<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBSource;
use MetaModel;

class StaticDeletionPlan
{
	private array $aDeletionPlan = [];

	/**
	 * @param array $aClasses Classes to clean entirely
	 *
	 * @return array ['class' => [
	 *      'delete' => [ids],
	 *      'delete_sql' => string,
	 *      'update_extkey_nullable' => [ids],
	 *      'update_extkey_nullable_sql' => [sSQL],
	 *      'update_hierarchical' => [ids],
	 *      'update_hierarchical_sql' => [sSQL],
	 *      'issue'  => [id],
	 *  ]];
	 *
	 * @throws \CoreException
	 */
	public function GetStaticDeletionPlan(array $aClasses): array
	{
		foreach ($aClasses as $sClass) {
			[$sDeleteSQL, $aIds] = $this->GetInitialClassDeletionPlan($sClass);
			$this->aDeletionPlan[$sClass] = [
				'delete' => $aIds,
				'delete_sql' => $sDeleteSQL,
			];

			$this->DeletionPlanForReferencingClasses($sClass);
		}

		return $this->aDeletionPlan;
	}

	private function DeletionPlanForReferencingClasses(string $sClass): void
	{
		$sIdsToRemove = implode(', ', $this->aDeletionPlan[$sClass]['delete']);
		$aReferencingMe = MetaModel::EnumReferencingClasses($sClass);
		foreach ($aReferencingMe as $sRemoteClass => $aExtKeys) {
			$sRemoteTable = MetaModel::DBGetTable($sRemoteClass);
			/** @var \AttributeExternalKey $oExtKeyAttDef */
			foreach ($aExtKeys as $sExtKeyAttCode => $oExtKeyAttDef) {
				// skip if this external key is behind an external field
				if (!$oExtKeyAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) {
					continue;
				}

				if ($oExtKeyAttDef->IsNullAllowed()) {
					// update
					[$sUpdateSQL, $aIds] = $this->UpdateExtKeyNullable($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);
					$this->aDeletionPlan[$sRemoteClass]['update_extkey_nullable_sql'][$sExtKeyAttCode] = $sUpdateSQL;
					$this->aDeletionPlan[$sRemoteClass]['update_extkey_nullable'] = array_unique(array_merge($this->aDeletionPlan[$sRemoteClass]['update_extkey_nullable'] ?? [], $aIds));
				} else {
					// delete
					$aRemoteIdsToRemove = $this->GetRemoteIdsForExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);

					$iDeletePropagationOption = $oExtKeyAttDef->GetDeletionPropagationOption();
					if ($iDeletePropagationOption == DEL_MANUAL) {
						// Issue, do not recurse
						if (count($aRemoteIdsToRemove) > 0) {
							$this->aDeletionPlan[$sRemoteClass]['issue'] = array_unique(array_merge($this->aDeletionPlan[$sRemoteClass]['issue'] ?? [], $aRemoteIdsToRemove));
						}
						continue;
					}

					if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
						// update hierarchical keys due to row cleanup in the same table
						$sIdsToRemove = implode(',', $this->aDeletionPlan[$sRemoteClass]['delete']);
						[$sUpdateSQL, $aIds] = $this->UpdateHierarchicalExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemove);
						$this->aDeletionPlan[$sRemoteClass]['update_hierarchical_sql'][$sExtKeyAttCode] = $sUpdateSQL;
						$this->aDeletionPlan[$sRemoteClass]['update_hierarchical'] = array_unique(array_merge($this->aDeletionPlan[$sRemoteClass]['update_hierarchical'] ?? [], $aIds));
						// do not recurse
						continue;
					}

					// Delete entries in Remote Class
					$this->aDeletionPlan[$sRemoteClass]['delete'] = array_unique(array_merge($this->aDeletionPlan[$sRemoteClass]['delete'] ?? [], $aRemoteIdsToRemove));
					$sRemoteIdsToDelete = implode(',', $aRemoteIdsToRemove);
					$this->aDeletionPlan[$sRemoteClass]['delete_sql'] = "DELETE FROM $sRemoteTable WHERE id IN ($sRemoteIdsToDelete)";

					$this->DeletionPlanForReferencingClasses($sRemoteClass);
				}
			}
		}
	}

	/**
	 * @param string $sRemoteTable
	 * @param string $sExtKeyAttCode
	 * @param string $sIdsToRemoveInTargetClass
	 *
	 * @return array
	 */
	public function UpdateExtKeyNullable(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): array
	{
		$aIds = $this->GetRemoteIdsForExtKey($sRemoteTable, $sExtKeyAttCode, $sIdsToRemoveInTargetClass);

		$sUpdateSQL = <<<SQL
UPDATE $sRemoteTable SET updated.$sExtKeyAttCode = 0
FROM $sRemoteTable AS updated
WHERE updated.$sExtKeyAttCode IN ($sIdsToRemoveInTargetClass)
SQL;

		return [$sUpdateSQL, $aIds];
	}

	public function UpdateHierarchicalExtKey(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): array
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

		return [$sUpdateSQL, $aIds];
	}

	public function GetRemoteIdsForExtKey(string $sRemoteTable, string $sExtKeyAttCode, string $sIdsToRemoveInTargetClass): array
	{
		$sSQL = "SELECT id FROM $sRemoteTable WHERE $sExtKeyAttCode IN ($sIdsToRemoveInTargetClass)";

		return CMDBSource::QueryToCol($sSQL, 'id');
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public function GetInitialClassDeletionPlan(string $sClass): array
	{
		$sTable = MetaModel::DBGetTable($sClass);
		$sSQL = "SELECT id FROM $sTable";
		$aIds = CMDBSource::QueryToCol($sSQL, 'id');
		$sDeleteSQL = "DELETE FROM $sTable";

		return [$sDeleteSQL, $aIds];
	}

}

<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBObjectSet;
use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use DBObject;
use DBObjectSearch;
use DeletionPlan;
use MetaModel;
use utils;

class DeletionPlanService
{
	private array $aVisited = [];

	/**
	 * Get a summary of the deletion plan computed for the classes.
	 * The result is used for display
	 *
	 * @param array|null $aClasses
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function GetDeletionPlanSummary(?array $aClasses): array
	{
		$aSummary = [];
		if (is_null($aClasses)) {
			return $aSummary;
		}

		$oDeletionPlan = $this->GetDeletionPlan($aClasses);

		foreach ($oDeletionPlan->ListUpdates() as $sClass => $aUpdates) {
			$oDeletionPlanSummaryEntity = new DeletionPlanSummaryEntity($sClass);
			$oDeletionPlanSummaryEntity->iUpdateCount = count($aUpdates);
			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		foreach ($oDeletionPlan->ListDeletes() as $sClass => $aDeletes) {
			$oDeletionPlanSummaryEntity = $aSummary[$sClass] ?? new DeletionPlanSummaryEntity($sClass);
			$oDeletionPlanSummaryEntity->iDeleteCount = count($aDeletes);

			$aDelete = array_shift($aDeletes);
			$oDeletionPlanSummaryEntity->iMode = $aDelete['mode'];
			$oDeletionPlanSummaryEntity->sIssue = $aDelete['issue'] ?? null;

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		return $aSummary;
	}

	/**
	 * @param string $sClass
	 *
	 * @return \DBObject[]
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	private function GetAllObjects(string $sClass): array
	{
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AllowAllData();
		$oSet = new \DBObjectSet($oFilter);
		return $oSet->ToArray();
	}

	private function GetNextObjectToDelete(array $aClasses): ?DBObject
	{
		foreach ($aClasses as $sClass) {
			$oFilter = new DBObjectSearch($sClass);
			$oFilter->AllowAllData();
			$oSet = new \DBObjectSet($oFilter);
			$oObject = $oSet->Fetch();
			if (! is_null($oObject)) {
				return $oObject;
			}
		}

		return null;
	}

	private function Update(DBObject $oToUpdate, string $sAttCode, $value)
	{
		$oToUpdate->Set($sAttCode, $value);
		$oToUpdate->DBUpdate();
	}

	private function Delete(string $sClass, string $sId)
	{
		try {
			CMDBSource::Query('START TRANSACTION');
			// Delete any existing change tracking about the current object
			$oFilter = new DBObjectSearch('CMDBChangeOp');
			$oFilter->AddCondition('objclass', $sClass, '=');
			$oFilter->AddCondition('objkey', $sId, '=');
			MetaModel::PurgeData($oFilter);

			// Delete the entry
			$aClassesToRemove = array_merge(MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL), MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false));
			foreach ($aClassesToRemove as $sParentClass) {
				$oFilter = DBObjectSearch::FromOQL_AllData("SELECT $sParentClass WHERE id=:id");
				$sQuery = $oFilter->MakeDeleteQuery(['id' => $sId]);
				CMDBSource::DeleteFrom($sQuery);
			}

			CMDBSource::Query('COMMIT');
		} catch (\Exception $e) {
			\IssueLog::Exception(__METHOD__.': Cleanup failed', $e);
			CMDBSource::Query('ROLLBACK');
			throw $e;
		}
	}

	/**
	* @param array $aClasses
	* @param int $iMaxExecutionTime
	* @param int $iMaxMemoryPercent
	* @return void
	* @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function ExecuteDeletionPlan(array $aClasses, int $iMaxExecutionTime = 30, int $iMaxMemoryPercent = 80): void
	{
		$oObject = $this->GetNextObjectToDelete($aClasses);
		if (is_null($oObject)) {
			return;
		}

		$iMaxTime = time() + $iMaxExecutionTime;
		$this->RecursiveDeletion($oObject, $iMaxTime, $iMaxMemoryPercent);
	}

	public function IsVisited(DBObject $oObject): bool
	{
		$sClass = get_class($oObject);
		$sId = $oObject->GetKey();
		$sKey = "{$sClass}_{$sId}";

		$bRes = $this->aVisited[$sKey] ?? false;
		$this->aVisited[$sKey] = true;
		return $bRes;
	}

	private function RecursiveDeletion(DBObject $oObjectToClean, int $iMaxTime, int $iMaxMemoryPercent): void
	{
		if (utils::ShouldStopExecution($iMaxTime, $iMaxMemoryPercent)) {
			return;
		}

		$sClass = get_class($oObjectToClean);

		$aReferencingMe = MetaModel::EnumReferencingClasses($sClass);
		foreach ($aReferencingMe as $sRemoteClass => $aExtKeys) {
			/** @var \AttributeExternalKey $oExtKeyAttDef */
			foreach ($aExtKeys as $sExtKeyAttCode => $oExtKeyAttDef) {
				// skip if this external key is behind an external field
				if (!$oExtKeyAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) {
					continue;
				}

				$oSearch = new DBObjectSearch($sRemoteClass);
				$oSearch->AddCondition($sExtKeyAttCode, $oObjectToClean->GetKey(), '=');
				$oSearch->AllowAllData();
				$oSet = new CMDBObjectSet($oSearch);
				$oSet->OptimizeColumnLoad([$sRemoteClass => ['id', $oExtKeyAttDef->GetCode()]]);
				/** @var DBObject $oDependentObj */
				while ($oDependentObj = $oSet->Fetch()) {
					$iDeletePropagationOption = $oExtKeyAttDef->GetDeletionPropagationOption();
					if ($iDeletePropagationOption == DEL_MANUAL) {
						throw new DataFeatureRemovalException("DEL_MANUAL object");
					}

					if ($oExtKeyAttDef->IsNullAllowed()) {
						// Optional external key, list to reset
						if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
							// Move the child up one level i.e. set the same parent as the current object
							$iParentId = $oObjectToClean->Get($oExtKeyAttDef->GetCode());
							$this->Update($oDependentObj, $oExtKeyAttDef->GetCode(), $iParentId);
						} else {
							$this->Update($oDependentObj, $oExtKeyAttDef->GetCode(), 0);
						}
					} else {
						if ($this->IsVisited($oDependentObj)) {
							continue;
						}
						$this->RecursiveDeletion($oDependentObj, $iMaxTime, $iMaxMemoryPercent);
					}
				}
			}
		}

		$this->Delete($sClass, $oObjectToClean->GetKey());
	}

	/**
	 * Get a deletion plan for all the objects of the classes
	 *
	 * @param array $aClasses array of class names to clean
	 *
	 * @return \DeletionPlan
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function GetDeletionPlan(array $aClasses): DeletionPlan
	{
		$oDeletionPlan = new DeletionPlan();
		foreach ($aClasses as $sClass) {
			$aObjects = $this->GetAllObjects($sClass);
			foreach ($aObjects as $oObject) {
				$oObject->CheckToDelete($oDeletionPlan);
			}
		}

		return $oDeletionPlan;
	}
}

<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBObjectSet;
use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\ExecutionLimits;
use DBObject;
use DBObjectSearch;
use DeletionPlan;
use MetaModel;
use utils;

class DeletionPlanService
{
	private array $aVisited = [];
	private iObjectService $oObjectService;
	private ExecutionLimits $oExecutionLimits;

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
		return $this->ExecuteDeletionPlan($aClasses ?? [], oObjectService: new ObjectServiceSummary());
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
			while ($oObject = $oSet->Fetch()) {
				if (!$this->IsVisited($oObject)) {
					return $oObject;
				}
			}
		}

		return null;
	}

	/**
	 * @param array $aClasses
	 * @param int $iMaxExecutionTime
	 * @param int $iMaxMemoryPercent
	 * @param \Combodo\iTop\DataFeatureRemoval\Service\iObjectService|null $oObjectService
	 *
	 * @return array execution summary
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ExecuteDeletionPlan(array $aClasses, int $iMaxExecutionTime = 30, int $iMaxMemoryPercent = 80, ?iObjectService $oObjectService = null): array
	{
		$this->oObjectService = $oObjectService ?? new ObjectService();

		$this->aVisited = [];

		while ($oObject = $this->GetNextObjectToDelete($aClasses)) {
			$iMaxTime = time() + $iMaxExecutionTime;
			$this->oExecutionLimits = new ExecutionLimits($iMaxTime, $iMaxMemoryPercent);
			if ($this->RecursiveDeletion($oObject) === false) {
				// Timeout, stop here
				break;
			}
		}
		return $this->oObjectService->GetSummary();

	}

	private function MarkObjectAsVisited(DBObject $oObject): void
	{
		$sClass = get_class($oObject);
		$sId = $oObject->GetKey();
		$sKey = "$sClass-$sId";
		$this->aVisited[$sKey] = true;
	}

	private function IsVisited(DBObject $oObject): bool
	{
		$sClass = get_class($oObject);
		$sId = $oObject->GetKey();
		$sKey = "$sClass-$sId";

		$bRes = $this->aVisited[$sKey] ?? false;
		\IssueLog::Info('Checking if object is visited', null, [$sKey, $bRes]);
		return $bRes;
	}

	/**
	 *
	 * @param \DBObject $oObjectToClean
	 *
	 * @return bool true if deletion is complete, false in case of timeout or memory limit reached
	 *
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	private function RecursiveDeletion(DBObject $oObjectToClean): bool
	{
		if ($this->oExecutionLimits->ShouldStopExecution()) {
			return false;
		}

		$this->MarkObjectAsVisited($oObjectToClean);
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
				$oSet->OptimizeColumnLoad([$sRemoteClass => [$oExtKeyAttDef->GetCode()]]);
				/** @var DBObject $oDependentObj */
				while ($oDependentObj = $oSet->Fetch()) {
					$iDeletePropagationOption = $oExtKeyAttDef->GetDeletionPropagationOption();
					if ($iDeletePropagationOption == DEL_MANUAL) {
						throw new DataFeatureRemovalException("Deletion Plan cannot be executed due to issues");
					}

					if ($oExtKeyAttDef->IsNullAllowed()) {
						// Optional external key, list to reset
						if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
							// Move the child up one level i.e. set the same parent as the current object
							$iParentId = $oObjectToClean->Get($oExtKeyAttDef->GetCode());
							$this->oObjectService->Update($oDependentObj, $oExtKeyAttDef->GetCode(), $iParentId);
						} else {
							$this->oObjectService->Update($oDependentObj, $oExtKeyAttDef->GetCode(), 0);
						}
					} else {
						// Propagate deletion only if not visited
						if ($this->IsVisited($oDependentObj)) {
							continue;
						}
						$this->RecursiveDeletion($oDependentObj);
					}
				}
			}
		}

		$this->oObjectService->Delete($sClass, $oObjectToClean->GetKey());

		return true;
	}
}

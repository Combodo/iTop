<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBObjectSet;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalLog;
use Combodo\iTop\Service\Limits\ExecutionLimits;
use DBObject;
use DBObjectSearch;
use MetaModel;

class DataCleanupService
{
	private array $aVisited = [];
	private iObjectService $oObjectService;
	private ExecutionLimits $oExecutionLimits;

	public function __construct(int $iMaxExecutionTime = 30, int $iMaxMemoryPercent = 80)
	{
		DataFeatureRemovalLog::Enable();
		$this->oExecutionLimits = new ExecutionLimits($iMaxExecutionTime, $iMaxMemoryPercent);
	}

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
		return $this->ExecuteCleanup($aClasses ?? [], oObjectService: new ObjectServiceSummary());
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
	 * @param \Combodo\iTop\DataFeatureRemoval\Service\iObjectService|null $oObjectService
	 *
	 * @return array execution summary
	 * @throws \ArchivedObjectException
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ExecuteCleanup(array $aClasses, ?iObjectService $oObjectService = null): array
	{
		$this->oObjectService = $oObjectService ?? new ObjectService();

		$this->aVisited = [];

		while ($oObject = $this->GetNextObjectToDelete($aClasses)) {
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
		DataFeatureRemovalLog::Debug('Checking if object is visited', null, [$sKey, $bRes]);
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

					if ($oExtKeyAttDef->IsNullAllowed()) {
						// Optional external key, list to reset
						if (($iDeletePropagationOption == DEL_MOVEUP) && ($oExtKeyAttDef->IsHierarchicalKey())) {
							// Move the child up one level i.e. set the same parent as the current object
							$iParentId = $oObjectToClean->Get($oExtKeyAttDef->GetCode());
							$this->oObjectService->Update($oDependentObj, $oExtKeyAttDef->GetCode(), $iParentId);
						} else {
							$this->oObjectService->Update($oDependentObj, $oExtKeyAttDef->GetCode(), 0);
						}
						if ($this->oExecutionLimits->ShouldStopExecution()) {
							return false;
						}
					} else {
						// Mandatory external key
						if ($iDeletePropagationOption == DEL_MANUAL) {
							// Cannot be deleted automatically, must be handled manually
							$this->oObjectService->SetIssue(get_class($oDependentObj));
							continue;
						}
						// Propagate deletion only if not visited
						if ($this->IsVisited($oDependentObj)) {
							continue;
						}
						if (!$this->RecursiveDeletion($oDependentObj)) {
							// Timeout
							return false;
						}
					}

				}
			}
		}

		$this->oObjectService->Delete($sClass, $oObjectToClean->GetKey());

		if ($this->oExecutionLimits->ShouldStopExecution()) {
			return false;
		}

		return true;
	}
}

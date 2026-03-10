<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use DBObjectSearch;
use DeletionPlan;
use MetaModel;

class DeletionPlanService
{
	private static DeletionPlanService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): DeletionPlanService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new DeletionPlanService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?DeletionPlanService $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	/**
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

	/**
	 * @param array $aClasses
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ExecuteDeletionPlan(array $aClasses): array
	{
		$oDeletionPlan = $this->GetDeletionPlan($aClasses);

		if (count($oDeletionPlan->GetIssues()) > 0) {
			throw new DataFeatureRemovalException("Deletion Plan cannot be executed due to issues");
		}

		$aSummary = [];
		foreach ($oDeletionPlan->ListUpdates() as $sClass => $aToUpdate) {
			$oDeletionPlanSummaryEntity = $aSummary[$sClass] ?? new DeletionPlanSummaryEntity($sClass);

			foreach ($aToUpdate as $aData) {
				$oToUpdate = $aData['to_reset'];
				/** @var \DBObject $oToUpdate */
				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef) {
					$oToUpdate->Set($sRemoteExtKey, 0);
					$oToUpdate->DBUpdate();
					$oDeletionPlanSummaryEntity->iUpdateCount++;
				}
			}

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		foreach ($oDeletionPlan->ListDeletes() as $sClass => $aDeletes) {
			$oDeletionPlanSummaryEntity = $aSummary[$sClass] ?? new DeletionPlanSummaryEntity($sClass);

			foreach ($aDeletes as $sId => $aDelete) {
				// Delete any existing change tracking about the current object
				$oFilter = new DBObjectSearch('CMDBChangeOp');
				$oFilter->AddCondition('objclass', $sClass, '=');
				$oFilter->AddCondition('objkey', $sId, '=');
				MetaModel::PurgeData($oFilter);

				// Delete the entry
				$aClassesToRemove = array_merge(MetaModel::EnumChildClasses($sClass, ENUM_PARENT_CLASSES_ALL), MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false));
				foreach ($aClassesToRemove as $sParentClass) {
					$oFilter = DBObjectSearch::FromOQL_AllData("SELECT $sParentClass WHERE id=:id");
					$sQuery = $oFilter->MakeDeleteQuery(['id' => $sId]);
					CMDBSource::DeleteFrom($sQuery);
				}

				$oDeletionPlanSummaryEntity->iDeleteCount++;
			}

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		return $aSummary;
	}

	/**
	 * @param array $aClasses
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

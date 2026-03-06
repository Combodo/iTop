<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use DeletionPlan;
use Hoa\Math\Test\Unit\Issue;

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
	 * @param array $sClasses
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 */
	public function GetDeletionPlanSummary(array $aClasses): array
	{
		$aSummary = [];

		$oDeletionPlan = new DeletionPlan();
		foreach ($aClasses as $sClass) {
			$aObjects = $this->GetAllObjects($sClass);
			foreach ($aObjects as $oObject) {
				$oObject->CheckToDelete($oDeletionPlan);
			}
		}

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
	 * @return \DBObject[]
	 */
	private function GetAllObjects(string $sClass): array
	{
		$oFilter = new \DBObjectSearch($sClass);
		$oFilter->AllowAllData();
		$oSet = new \DBObjectSet($oFilter);
		return $oSet->ToArray();
	}

	/**
	 * @param array $sClasses
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 */
	public function ExecuteDeletionPlan(array $aClasses)
	{
		$oDeletionPlan = new DeletionPlan();
		foreach ($aClasses as $sClass) {
			$aObjects = $this->GetAllObjects($sClass);
			foreach ($aObjects as $oObject) {
				$oObject->CheckToDelete($oDeletionPlan);
			}
		}

		return $this->DoDelete($oDeletionPlan);

	}

	/**
	 * @param DeletionPlan $oDeletionPlan
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 */
	private function DoDelete(DeletionPlan $oDeletionPlan)
	{
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
				$oFilter = \DBObjectSearch::FromOQL_AllData("SELECT $sClass WHERE id=:id");
				$sQuery = $oFilter->MakeDeleteQuery(['id' => $sId]);
				\CMDBSource::DeleteFrom($sQuery);
				\IssueLog::Info(__METHOD__, null, [$sQuery]);

				$oDeletionPlanSummaryEntity->iDeleteCount++;
			}

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		return $aSummary;
	}
}

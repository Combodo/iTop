<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;

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

		$oDeletionPlan = new \DeletionPlan();
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
			$oDeletionPlanSummaryEntity->sIssue = $aDelete['issue'];

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
}

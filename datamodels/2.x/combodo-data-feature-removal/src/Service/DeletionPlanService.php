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

	public int $iExecutionCount = 0;

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

	/**
	 * @since 3.3.0
	 * @param array $aClasses
	 * @param int $iUnixTimeLimit : max execution time in seconds since Epoch before stopping deletion. by default: no limit (ie remove all without stop)
	 * @param int $iMaxExecutionCount : max execution count before stopping deletion. by default: no limit (ie remove all without stop)
	 *
	 * @return array<\Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity>
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ExecuteDeletionPlan(array $aClasses, int $iUnixTimeLimit = 0, int $iMaxExecutionCount=0): array
	{
		$oDeletionPlan = $this->GetDeletionPlan($aClasses);

		if (count($oDeletionPlan->GetIssues()) > 0) {
			throw new DataFeatureRemovalException("Deletion Plan cannot be executed due to issues");
		}

		$this->iExecutionCount=0;

		$aSummary = [];
		foreach ($oDeletionPlan->ListUpdates() as $sClass => $aToUpdate) {
			$oDeletionPlanSummaryEntity = $aSummary[$sClass] ?? new DeletionPlanSummaryEntity($sClass);

			foreach ($aToUpdate as $aData) {
				if ($this->IsTimeLimitExceeded($iUnixTimeLimit, $iMaxExecutionCount)) {
					$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
					return $aSummary;
				}

				$oToUpdate = $aData['to_reset'];
				/** @var \DBObject $oToUpdate */
				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef) {
					$oToUpdate->Set($sRemoteExtKey, $aData['values'][$sRemoteExtKey]);
				}
				$oToUpdate->DBUpdate();
				$oDeletionPlanSummaryEntity->iUpdateCount++;
			}

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		foreach ($oDeletionPlan->ListDeletes() as $sClass => $aDeletes) {
			$oDeletionPlanSummaryEntity = $aSummary[$sClass] ?? new DeletionPlanSummaryEntity($sClass);

			foreach ($aDeletes as $sId => $aDelete) {
				if ($this->IsTimeLimitExceeded($iUnixTimeLimit, $iMaxExecutionCount)) {
					$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
					return $aSummary;
				}

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
					\IssueLog::Exception(__METHOD__.": Cleanup failed", $e);
					CMDBSource::Query('ROLLBACK');
					throw $e;
				}
				$oDeletionPlanSummaryEntity->iDeleteCount++;
			}

			$aSummary[$sClass] = $oDeletionPlanSummaryEntity;
		}

		return $aSummary;
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

	public function IsTimeLimitExceeded(int $iUnixTimeLimit, int $iMaxExecutionCount): bool
	{
		throw new \Exception("");
		$this->iExecutionCount++;
		echo json_encode([$this->iExecutionCount, $iMaxExecutionCount]);
		if ($iMaxExecutionCount === $this->iExecutionCount){
			return false;
		}

		if ($iUnixTimeLimit === 0) {
			return false;
		}

		return (time() <= $iUnixTimeLimit);
	}
}

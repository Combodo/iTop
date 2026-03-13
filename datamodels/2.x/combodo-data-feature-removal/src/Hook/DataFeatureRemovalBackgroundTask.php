<?php

use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalConfig;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Service\BackgroundOperationService;
use Combodo\iTop\DataFeatureRemoval\Service\DeletionPlanService;

class DataFeatureRemovalBackgroundTask implements iBackgroundProcess
{
	/**
	 * @inheritDoc
	 */
	public function GetPeriodicity()
	{
		return DataFeatureRemovalConfig::GetInstance()->Get('cron_periodicity_in_s', 10);
	}

	/**
	 * @inheritDoc
	 */
	public function Process($iUnixTimeLimit)
	{
		while ($oBackgroundOperation = BackgroundOperationService::GetInstance()->GetNext()) {
			$aClasses = BackgroundOperationService::GetInstance()->GetClasses($oBackgroundOperation);
			$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses, $iUnixTimeLimit);
			IssueLog::Info(__METHOD__, null, $aRes);

			IssueLog::Info(__METHOD__, null, [
				'$iUnixTimeLimit' => $iUnixTimeLimit,
				'time' => time(),
				'timeout reached' => DataFeatureRemovalHelper::IsTimeLimitExceeded($iUnixTimeLimit),
			]);

			if (DataFeatureRemovalHelper::IsTimeLimitExceeded($iUnixTimeLimit)) {
				//timeout reached
				return;
			}

			//execution finished before timeout: nothing left to remove
			$oBackgroundOperation->DBDelete();
		}

	}
}

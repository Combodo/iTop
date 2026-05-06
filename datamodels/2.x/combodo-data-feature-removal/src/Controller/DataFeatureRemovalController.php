<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Controller;

require_once APPROOT.'setup/feature_removal/SetupAudit.php';
require_once APPROOT.'setup/feature_removal/DryRemovalRuntimeEnvironment.php';

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalConfig;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Service\DataCleanupService;
use Combodo\iTop\DataFeatureRemoval\Service\DataFeatureRemoverExtensionService;
use Combodo\iTop\Setup\FeatureRemoval\DryRemovalRuntimeEnvironment;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use SetupUtils;
use utils;

class DataFeatureRemovalController extends Controller
{
	private array $aSelectedExtensionsForCheck = [];
	private array $aCountClassesToCleanup = [];
	private array $aAnalysisDataTable = [];
	private int $iCount = 0;

	public function OperationMain($sErrorMessage = null): void
	{
		$aParams = [];

		$this->ReadRemovedExtensions();
		$this->AddAnalyzeParams();
		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aExtensions'] = $this->GetExtensionsTable();
		$aParams['aAnalysisDataTable'] = $this->aAnalysisDataTable;
		$aParams['aClasses'] = array_keys($this->aCountClassesToCleanup);
		$aParams['DataFeatureRemovalErrorMessage'] = $sErrorMessage;
		$aParams['bHasData'] = $this->iCount > 0;
		$aParams['sSetupUrl'] = utils::GetAbsoluteUrlAppRoot().'setup';
		$aParams['iCount'] = $this->iCount;

		$this->AddLinkedStylesheet(utils::GetAbsoluteUrlModulesRoot().DataFeatureRemovalHelper::MODULE_NAME.'/assets/css/DataFeatureRemoval.css');
		$this->AddLinkedScript(utils::GetAbsoluteUrlModulesRoot().DataFeatureRemovalHelper::MODULE_NAME.'/assets/js/DataFeatureRemoval.js');
		$this->DisplayPage($aParams);
	}

	public function AddAnalyzeParams(): void
	{
		$aData = [];
		$aColumns = [];
		$this->iCount = 0;
		foreach ($this->aCountClassesToCleanup as $sClass => $iCount) {
			$sModuleName = MetaModel::GetModuleName($sClass);
			$aExtensions = DataFeatureRemoverExtensionService::GetInstance()->GetIncludingExtensions($sModuleName);
			$sExtensions = implode(' ', $aExtensions);
			$aColumns = ['ClassName','FeatureName','Module','Occurrence'];
			$aData[] = [$sClass,$sExtensions,$sModuleName,$iCount];
			$this->iCount += $iCount;
		}

		$this->aAnalysisDataTable =  $this->GetTableData('Analysis', $aColumns, $aData);
	}

	public function OperationAnalyze(): void
	{
		$this->ReadRemovedExtensions();

		$this->m_sOperation = 'Main';

		try {
			if (count($this->aSelectedExtensionsForCheck) > 0) {
				$this->Analyze();
			}
			$this->OperationMain();
		} catch (Exception $e) {
			IssueLog::Error(__METHOD__, null, ['stack' => $e->getTraceAsString(), 'exception' => $e->getMessage()]);
			$this->OperationMain($e->getMessage());
		}
	}

	private function Analyze(): void
	{
		$sSourceEnv = MetaModel::GetEnvironment();
		$oDryRemovalRuntimeEnvironment = new DryRemovalRuntimeEnvironment($sSourceEnv, $this->aSelectedExtensionsForCheck);
		$oDryRemovalRuntimeEnvironment->CompileFrom($sSourceEnv);

		$oSetupAudit = new SetupAudit($sSourceEnv);
		$aGetRemovedClasses = $oSetupAudit->RunDataAudit();
		IssueLog::Debug(__METHOD__, null, ['aGetRemovedClasses' => $aGetRemovedClasses]);
		$this->aCountClassesToCleanup = $aGetRemovedClasses;
	}

	public function OperationAnalysisResult(): void
	{
		$aParams = [];

		if (SetupUtils::IsSessionSetupTokenValid()) {
			//from setup wizard/mtp
			SetupUtils::EraseSetupToken();
		} else {
			//from same module
			$this->ValidateTransactionId();
		}

		$sSourceEnv = MetaModel::GetEnvironment();
		$oSetupAudit = new SetupAudit($sSourceEnv);
		$aGetRemovedClasses = array_keys($oSetupAudit->RunDataAudit());
		IssueLog::Debug(__METHOD__, null, ['aGetRemovedClasses' => $aGetRemovedClasses]);

		$oDataCleanupService = new DataCleanupService();
		$aDeletionPlanSummaryEntities = $oDataCleanupService->GetCleanupSummary($aGetRemovedClasses);
		$aColumns = ['Class', 'DeleteCount' , 'UpdateCount', 'IssueCount'];
		$aRows = [];
		$iQueryCount = 0;
		$bHasIssues = false;
		foreach ($aDeletionPlanSummaryEntities as $oDeletionPlanSummaryEntity) {
			$aRows[] = [
				$oDeletionPlanSummaryEntity->sClass,
				$oDeletionPlanSummaryEntity->iDeleteCount,
				$oDeletionPlanSummaryEntity->iUpdateCount,
				$oDeletionPlanSummaryEntity->iIssueCount,
			];
			$bHasIssues |= ($oDeletionPlanSummaryEntity->iIssueCount !== 0);
			$iQueryCount += $oDeletionPlanSummaryEntity->iDeleteCount;
			$iQueryCount += $oDeletionPlanSummaryEntity->iUpdateCount;
		}

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aDeletionPlanSummary'] = $this->GetTableData('Extensions', $aColumns, $aRows);
		$aParams['aClasses'] = $aGetRemovedClasses;
		$aParams['iQueryCount'] = $iQueryCount;
		$aParams['bDeletionPossible'] = !$bHasIssues;

		$this->DisplayPage($aParams);
	}

	public function OperationDeletionPlan(): void
	{
		$aParams = [];
		$this->ValidateTransactionId();

		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$oDataCleanupService = new DataCleanupService();
		$aDeletionPlanSummaryEntities = $oDataCleanupService->GetCleanupSummary($aClasses);
		$aColumns = ['Class', 'DeleteCount' , 'UpdateCount', 'IssueCount'];
		$aRows = [];
		$iQueryCount = 0;
		$bHasIssues = false;
		foreach ($aDeletionPlanSummaryEntities as $oDeletionPlanSummaryEntity) {
			$aRows[] = [
				$oDeletionPlanSummaryEntity->sClass,
				$oDeletionPlanSummaryEntity->iDeleteCount,
				$oDeletionPlanSummaryEntity->iUpdateCount,
				$oDeletionPlanSummaryEntity->iIssueCount,
			];
			$bHasIssues |= ($oDeletionPlanSummaryEntity->iIssueCount !== 0);
			$iQueryCount += $oDeletionPlanSummaryEntity->iDeleteCount;
			$iQueryCount += $oDeletionPlanSummaryEntity->iUpdateCount;
		}

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aDeletionPlanSummary'] = $this->GetTableData('Extensions', $aColumns, $aRows);
		$aParams['aClasses'] = $aClasses;
		$aParams['iQueryCount'] = $iQueryCount;
		$aParams['bDeletionPossible'] = !$bHasIssues;

		$this->DisplayPage($aParams);
	}

	public function OperationDoDeletion(): void
	{
		$aParams = [];
		$this->ValidateTransactionId();

		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$oDataCleanupService = new DataCleanupService();
		$aDeletionExecutionSummary = $oDataCleanupService->ExecuteCleanup($aClasses);
		$aColumns = ['Class', 'DeletedCount' , 'UpdatedCount'];
		$aRows = [];
		foreach ($aDeletionExecutionSummary as $oDeletionExecutionSummaryEntity) {
			$aRows[] = [
				$oDeletionExecutionSummaryEntity->sClass,
				$oDeletionExecutionSummaryEntity->iDeleteCount,
				$oDeletionExecutionSummaryEntity->iUpdateCount,
			];
		}

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aDeletionExecutionSummary'] = $this->GetTableData('Extensions', $aColumns, $aRows);

		$this->DisplayPage($aParams);
	}

	/**
	 * Get installed extensions from disk
	 *
	 * @return array structure for twig datatable
	 */
	private function GetExtensionsTable(): array
	{
		$aExtensions = [];
		$aColumns = ['', 'Version', 'Name', 'Code'];

		foreach (DataFeatureRemoverExtensionService::GetInstance()->ReadItopExtensions() as $sCode => $oExtension) {
			/** @var \iTopExtension $oExtension */

			$sChecked = '';
			$sDisabledHtml = '';
			if ($oExtension->bRemovedFromDisk) {
				$sDisabledHtml = 'disabled=""';
				$sChecked = 'checked';
			} elseif (in_array($sCode, $this->aSelectedExtensionsForCheck)) {
				$sChecked = 'checked';
			}

			$sLabel = $oExtension->sLabel;
			$sVersion = $oExtension->sVersion;
			$sIdEnable = "aExtensions[$sCode][enable]";

			$aExtensions[] = [
				<<<HTML
<input type="checkbox" $sDisabledHtml class="extension_check" $sChecked id="$sIdEnable" name="$sIdEnable"/>
HTML,
				$sVersion,
				$sLabel,
				$sCode,
			];
		}

		return $this->GetTableData('Extensions', $aColumns, $aExtensions);
	}

	private function GetTableData(string $sTableName, array $aColumns, array $aData): array
	{
		if (empty($aData)) {
			return [
				'Type' => 'Table',
				'Columns' => [['label' => '']],
				'Data' => [[ Dict::S('DataFeatureRemoval:Table:Empty')]],
			];
		}

		$aNewColumns = [];
		foreach ($aColumns as $sColumn) {
			$aNewColumns[] = ['label' => Dict::S("DataFeatureRemoval:Table:$sTableName:$sColumn", Dict::S("DataFeatureRemoval:Column:$sColumn", $sColumn))];
		}
		$aColumns = $aNewColumns;

		return [
			'Type' => 'Table',
			'Columns' => $aColumns,
			'Data' => $aData,
		];
	}

	/**
	 * @return void
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	private function ValidateTransactionId(): void
	{
		if (empty($_POST)) {
			return;
		}

		$sTransactionId = utils::ReadPostedParam('transaction_id', null, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);
		IssueLog::Debug(__FUNCTION__.": Transaction [$sTransactionId]");
		if (empty($sTransactionId) || !utils::IsTransactionValid($sTransactionId, false)) {
			throw new DataFeatureRemovalException(Dict::S("iTopUpdate:Error:InvalidToken"));
		}
	}

	/**
	 * @return void
	 */
	public function ReadRemovedExtensions(): void
	{
		if (count($this->aSelectedExtensionsForCheck) > 0) {
			return;
		}

		$aSelectedExtensionsFromUI = utils::ReadPostedParam('aExtensions', []);
		foreach ($aSelectedExtensionsFromUI as $sCode => $aData) {
			$sValue = $aData['enable'] ?? 'off';
			if (($sValue) === 'on') {
				$this->aSelectedExtensionsForCheck[] = $sCode;
			}
		}

		// Add source removed to check
		foreach (DataFeatureRemoverExtensionService::GetInstance()->ReadItopExtensions() as $sCode => $oExtension) {
			if ($oExtension->bRemovedFromDisk) {
				$this->aSelectedExtensionsForCheck[] = $sCode;
			}
		}
	}
}

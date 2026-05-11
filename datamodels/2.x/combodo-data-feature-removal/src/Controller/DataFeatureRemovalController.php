<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Controller;

require_once APPROOT.'setup/feature_removal/SetupAudit.php';
require_once APPROOT.'setup/feature_removal/DryRemovalRuntimeEnvironment.php';

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalLog;
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
	private array $aRemovedExtensionsForCheck = [];
	private array $aCountClassesToCleanup = [];
	private array $aAnalysisDataTable = [];
	private array $aDeletionExecutionSummary = [];

	private int $iCount = 0;

	public function OperationMain($sErrorMessage = null): void
	{
		$aParams = [];

		$this->ReadRemovedExtensions();
		$this->AddAnalyzeParams();
		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aExtensions'] = $this->GetExtensionsTableToSelect();
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
			if (count($this->aRemovedExtensionsForCheck) > 0) {
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
		$this->Compile($this->aRemovedExtensionsForCheck);
		$sSourceEnv = MetaModel::GetEnvironment();
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
			//$this->ValidateTransactionId();
		}

		// Display changed extensions
		$aAddedExtensions = utils::ReadPostedParam('aAddedExtensions', []);
		$aRemovedExtensions = utils::ReadPostedParam('aRemovedExtensions', []);

		IssueLog::Info(__METHOD__.' Extensions given in parameter', null, ['aAddedExtensions' => $aAddedExtensions, 'aRemovedExtensions' => $aRemovedExtensions]);

		$this->Compile(array_keys($aRemovedExtensions), false);

		$sSourceEnv = MetaModel::GetEnvironment();
		$oSetupAudit = new SetupAudit($sSourceEnv);
		$aGetRemovedClasses = array_keys($oSetupAudit->RunDataAudit());
		IssueLog::Debug(__METHOD__, null, ['aGetRemovedClasses' => $aGetRemovedClasses]);

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aClasses'] = $aGetRemovedClasses;

		$aParams['aAddedExtensions'] = $aAddedExtensions;
		$aParams['aRemovedExtensions'] = $aRemovedExtensions;
		$aParams['aExtensions'] = $this->GetExtensionsTableDiff($aAddedExtensions, $aRemovedExtensions);

		[$aParams['aDeletionPlanSummary'], $aParams['iQueryCount'], $aParams['bDeletionPossible']] = $this->GetDeletionPlanSummaryTable($aGetRemovedClasses);
		[$aParams['aDeletionExecutionSummary'], $aParams['bHasDeletionExecution']] = $this->GetExecutionSummaryTable();
		$aParams['bDeletionNeeded'] = ($aParams['iQueryCount'] > 0);

		$this->DisplayPage($aParams, 'AnalysisResult');
	}

	private function Compile(array $aRemovedExtensions, bool $bForceCompilation = true): void
	{
		$sSourceEnv = MetaModel::GetEnvironment();
		$sBuildDir = APPROOT."/env-$sSourceEnv-build";
		$bIsDirEmpty = count(scandir($sBuildDir)) === 2;

		if ($bIsDirEmpty || $bForceCompilation) {
			$oRuntimeEnvironment = new DryRemovalRuntimeEnvironment($sSourceEnv, $aRemovedExtensions);
			DataFeatureRemovalLog::Info(
				__METHOD__,
				null,
				['sSourceEnv' => $sSourceEnv, 'sBuildDir' => $sBuildDir, 'bIsDirEmpty' => $bIsDirEmpty, glob("$sBuildDir/*")]
			);
			$oRuntimeEnvironment->CompileFrom($sSourceEnv);
		}
	}

	private function GetExecutionSummaryTable(): array
	{
		$sName = 'ExcutionSummary';

		$aTableData = [];
		if (count($this->aDeletionExecutionSummary) === 0) {
			return [$aTableData, false];
		}

		$aColumns = ['Class', 'Total Deleted Count' , 'Total Updated Count', 'Deleted Count' , 'Updated Count'];
		$aRows = [];
		foreach ($this->aDeletionExecutionSummary as $sClass => $oDeletionPlanSummaryEntity) {
			$aRows[] = [
				$sClass,
				$oDeletionPlanSummaryEntity->iTotalDeletedCount,
				$oDeletionPlanSummaryEntity->iTotalUpdatedCount,
				$oDeletionPlanSummaryEntity->iDeletedCount,
				$oDeletionPlanSummaryEntity->iUpdatedCount,
			];
		}

		$aTableData = $this->GetTableData($sName, $aColumns, $aRows);

		return [$aTableData, true];

	}

	private function GetDeletionPlanSummaryTable(array $aRemovedClasses): array
	{
		$sName = 'DeletionPlanSummary';
		$oDataCleanupService = new DataCleanupService();
		$aDeletionPlanSummaryEntities = $oDataCleanupService->GetCleanupSummary($aRemovedClasses);
		$aColumns = ['Class', 'Delete Count' , 'Update Count', 'Issue Count'];
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
		return [$this->GetTableData($sName, $aColumns, $aRows), $iQueryCount, !$bHasIssues];
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
		$this->ValidateTransactionId();

		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$oDataCleanupService = new DataCleanupService();
		$this->aDeletionExecutionSummary = $oDataCleanupService->ExecuteCleanup($aClasses, $this->aDeletionExecutionSummary);

		$this->OperationAnalysisResult();
	}

	private function GetExtensionsTableDiff(array $aAddedExtensions, array $aRemovedExtensions): array
	{
		$aExtensions = [];
		$aColumns = ['', 'Name', 'code', 'Badge' ];

		foreach ($aAddedExtensions as $sAddedExtensionCode => $sAddedExtensionLabel) {
			$aExtensions[] = [
				<<<HTML
<input type="checkbox" disabled class="extension_check" checked/>
HTML,
				$sAddedExtensionLabel,
				$sAddedExtensionCode,
				Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeInstalled'),
			];
		}
		foreach ($aRemovedExtensions as $sAddedExtensionCode => $sAddedExtensionLabel) {
			$aExtensions[] = [
				<<<HTML
<input type="checkbox" disabled class="extension_check"/>
HTML,
				$sAddedExtensionLabel,
				$sAddedExtensionCode,
				Dict::S('UI:Layout:ExtensionsDetails:BadgeToBeUninstalled'),
			];
		}

		return $this->GetTableData('Extensions', $aColumns, $aExtensions);
	}

	/**
	 * Get installed extensions from disk
	 *
	 * @return array structure for twig datatable
	 */
	private function GetExtensionsTableToSelect(): array
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
			} elseif (in_array($sCode, $this->aRemovedExtensionsForCheck)) {
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
		if (count($this->aRemovedExtensionsForCheck) > 0) {
			return;
		}

		$aSelectedExtensionsFromUI = utils::ReadPostedParam('aExtensions', []);
		foreach ($aSelectedExtensionsFromUI as $sCode => $aData) {
			$sValue = $aData['enable'] ?? 'off';
			if (($sValue) === 'on') {
				$this->aRemovedExtensionsForCheck[] = $sCode;
			}
		}

		// Add source removed to check
		foreach (DataFeatureRemoverExtensionService::GetInstance()->ReadItopExtensions() as $sCode => $oExtension) {
			if ($oExtension->bRemovedFromDisk) {
				$this->aRemovedExtensionsForCheck[] = $sCode;
			}
		}
	}
}

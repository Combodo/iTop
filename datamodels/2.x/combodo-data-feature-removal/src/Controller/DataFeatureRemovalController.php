<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Controller;

require_once APPROOT.'setup/feature_removal/SetupAudit.php';
require_once APPROOT.'setup/feature_removal/DryRemovalRuntimeEnvironment.php';

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\AuthentToken\Helper\TokenAuthLog;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Model\DataFeatureRemoverAuditRuleService;
use Combodo\iTop\DataFeatureRemoval\Model\DataFeatureRemoverExtensionService;
use Combodo\iTop\DataFeatureRemoval\Service\DeletionPlanService;
use Combodo\iTop\Setup\FeatureRemoval\DryRemovalRuntimeEnvironment;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use utils;

class DataFeatureRemovalController extends Controller
{
	private array $aSelectedExtensionsForCheck = [];

	public function OperationMain($sErrorMessage = null): void
	{
		$aParams = [];

		$this->AddLinkedStylesheet(utils::GetAbsoluteUrlModulesRoot().DataFeatureRemovalHelper::MODULE_NAME.'/assets/css/DataFeatureRemoval.css');
		$this->AddLinkedScript(utils::GetAbsoluteUrlModulesRoot().DataFeatureRemovalHelper::MODULE_NAME.'/assets/js/DataFeatureRemoval.js');

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$this->AddFeatureParams($aParams);
		$this->AddAnalyzeParams($aParams);
		$aParams['DataFeatureRemovalErrorMessage'] = $sErrorMessage;
		$aParams['bHasData'] = count($aParams['aClasses']) > 0;
		$this->DisplayPage($aParams);
	}

	public function AddFeatureParams(array &$aParams): void
	{
		$aParams['aExtensions'] = $this->GetExtensionsTable();
		$aParams['sModule'] = DataFeatureRemovalHelper::MODULE_NAME;
	}

	public function AddAnalyzeParams(array &$aParams): void
	{
		$iTotalCount = 0;
		$aData = [];
		$aColumns = [];
		$aClasses = [];
		foreach (DataFeatureRemoverAuditRuleService::GetInstance()->ReadCheckRules() as $oRule) {
			$sContent = $oRule->Get('class_name');
			$aClasses[] = $sContent;
			$sModuleName = MetaModel::GetModuleName($sContent);
			$aExtensions = DataFeatureRemoverExtensionService::GetInstance()->GetIncludingExtensions($sModuleName);
			$sExtensions = implode(' ', $aExtensions);
			$sTypeName = $oRule->Get('rule_name');
			$sTypeDesc = \Dict::S("DataFeatureRemoval:Table:Analysis:RemovalType:$sTypeName");
			$iCount = $oRule->Get('count');
			$iTotalCount += $iCount;
			$aColumns = ['ClassName', 'RemovalType','FeatureName','Occurrence'];
			$aData[] = [
				<<<HTML
<label>$sContent</label>
HTML,
				<<<HTML
<label>$sTypeDesc</label>
HTML,
				<<<HTML
<label title="$sModuleName">$sExtensions</label>
HTML,
				<<<HTML
<label>$iCount</label>
HTML,
			];
		}

		$aParams['aCheckRules'] =  $this->GetTableData('Analysis', $aColumns, $aData);
		$aParams['rule_count'] = $iTotalCount;
		$aParams['aClasses'] = $aClasses;
	}

	public function OperationAnalyze(): void
	{
		$this->ValidateTransactionId();
		$aSelectedExtensionsFromUI = utils::ReadPostedParam('aExtensions', []);
		$this->aSelectedExtensionsForCheck = [];
		foreach ($aSelectedExtensionsFromUI as $sCode => $aData) {
			$sValue = $aData['enable'] ?? 'off';
			if (($sValue) === 'on') {
				$this->aSelectedExtensionsForCheck[] = $sCode;
			}
		}

		$this->m_sOperation = 'Main';

		try {
			$this->Analyze();
			$this->OperationMain();
		} catch (Exception $e) {
			IssueLog::Error(__METHOD__, null, ['stack' => $e->getTraceAsString(), 'exception' => $e->getMessage()]);
			$this->OperationMain($e->getMessage());
		}
	}

	private function GetExtensionsTable(): array
	{
		$aExtensions = [];
		$aColumns = ['', 'Version', 'Name', 'Code'];
		$this->aSelectedExtensionsForCheck = DataFeatureRemoverExtensionService::GetInstance()->ReadAuditedExtensions();

		foreach (DataFeatureRemoverExtensionService::GetInstance()->ReadItopExtensions() as $sCode => $oExtension) {
			/** @var \iTopExtension $oExtension */

			$sChecked = "checked";
			$sDisabledHtml = '';
			if ($oExtension->bRemovedFromDisk) {
				$sDisabledHtml = 'disabled=""';
			} elseif (! array_key_exists($sCode, $this->aSelectedExtensionsForCheck)) {
				$sChecked = "";
			}

			$sLabel = $oExtension->sLabel;
			$sVersion = $oExtension->sVersion;
			$sIdEnable = "aExtensions[$sCode][enable]";

			$aExtensions[] = [
				<<<HTML
<input type="checkbox" $sDisabledHtml class="extension_check" $sChecked id="$sIdEnable" name="$sIdEnable"/>
HTML,
				<<<HTML
<label>$sVersion</label>
HTML,
				<<<HTML
<label for="$sIdEnable">$sLabel</label>
HTML,
				<<<HTML
<label for="$sIdEnable">$sCode</label>
HTML,
			];
		}

		return $this->GetTableData('Extensions', $aColumns, $aExtensions);

	}

	public function GetTableData(string $sTableName, array $aColumns, array $aData): array
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

	private function Analyze(): void
	{
		DataFeatureRemoverExtensionService::GetInstance()->SaveExtensions($this->aSelectedExtensionsForCheck);

		$sSourceEnvt = \MetaModel::GetEnvironment();
		$oDryRemovalRuntimeEnvironment = new DryRemovalRuntimeEnvironment();
		$oDryRemovalRuntimeEnvironment->Prepare($sSourceEnvt, $this->aSelectedExtensionsForCheck);
		$oDryRemovalRuntimeEnvironment->CompileFrom($sSourceEnvt);

		$oSetupAudit = new SetupAudit($sSourceEnvt, DryRemovalRuntimeEnvironment::DRY_REMOVAL_AUDIT_ENV);
		$this->Save($oSetupAudit->GetIssues());
	}

	private function Save(array $aGetRemovedClasses): void
	{
		IssueLog::Debug(__METHOD__, null, ['aGetRemovedClasses' => $aGetRemovedClasses]);

		DataFeatureRemoverAuditRuleService::GetInstance()->SaveChecks($aGetRemovedClasses);
	}

	public function OperationDeletionPlan(): void
	{
		$aParams = [];
		$this->ValidateTransactionId();

		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$aDeletionPlanSummaryEntities = DeletionPlanService::GetInstance()->GetDeletionPlanSummary($aClasses);
		$aColumns = ['Class', 'DeleteCount' , 'UpdateCount', 'Issue'];
		$aRows = [];
		foreach ($aDeletionPlanSummaryEntities as $oDeletionPlanSummaryEntity) {
			$aRows[] = [
				$oDeletionPlanSummaryEntity->sClass,
				$oDeletionPlanSummaryEntity->iDeleteCount,
				$oDeletionPlanSummaryEntity->iUpdateCount,
				$oDeletionPlanSummaryEntity->sIssue ?? '',
			];
		}

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['aDeletionPlanSummary'] = $this->GetTableData('Extensions', $aColumns, $aRows);
		$aParams['aClasses'] = $aClasses;

		$this->DisplayPage($aParams);
	}

	public function OperationDoDeletion(): void
	{
		$aParams = [];
		$this->ValidateTransactionId();

		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$aDeletionExecutionSummary = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses);
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
	 * @return void
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function ValidateTransactionId(): void
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
}

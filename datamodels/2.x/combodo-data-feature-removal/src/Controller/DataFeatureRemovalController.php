<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Controller;

require_once APPROOT.'setup/feature_removal/SetupAudit.php';

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalLog;
use Combodo\iTop\DataFeatureRemoval\Service\DataCleanupService;
use Combodo\iTop\DataFeatureRemoval\Service\DataFeatureRemoverExtensionService;
use Combodo\iTop\DataFeatureRemoval\Service\StaticDeletionPlan;
use Combodo\iTop\Service\Session\SessionParameters;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use ContextTag;
use CoreException;
use Dict;
use Exception;
use MetaModel;
use MFCompiler;
use RunTimeEnvironment;
use SetupUtils;
use utils;

class DataFeatureRemovalController extends Controller
{
	private ?array $aExtensionsToCheck = null;
	private bool $bForcedUninstallation = false;
	private array $aCountClassesToCleanup = [];
	private array $aAnalysisDataTable = [];
	private array $aDeletionExecutionSummary = [];

	private int $iCount = 0;
	private int $iColumnCount = 2;
	private RunTimeEnvironment $oRuntimeEnvironment;

	public function OperationMain($sErrorMessage = null): void
	{
		$aParams = [];

		SetupUtils::EraseSetupToken();
		(new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME))->Erase();

		$this->AddAnalyzeParams();
		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['iColumnCount'] = $this->iColumnCount;
		$aParams['aAvailableExtensions'] = $this->SplitArrayIntoColumns($this->GetAvailableExtensions(), $this->iColumnCount);
		$aParams['aAnalysisDataTable'] = $this->aAnalysisDataTable;
		$aParams['aClasses'] = array_keys($this->aCountClassesToCleanup);
		$aParams['DataFeatureRemovalErrorMessage'] = $sErrorMessage;
		$aParams['bHasData'] = $this->iCount > 0;
		$aParams['sSetupUrl'] = utils::GetAbsoluteUrlAppRoot().'setup';
		$aParams['iCount'] = $this->iCount;

		Session::Set('bForceCompilation', true);
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

	public function OperationAnalysisResult(): void
	{
		$aParams = [];

		//from setup wizard/mtp
		if (!SetupUtils::IsSessionSetupTokenValid()) {
			//from same module
			$this->ValidateTransactionId();
		}

		// Display changed extensions
		$aHiddenInputNames = [
			'selected_extensions' => '[]',
			'selected_modules' => '[]',
			'display_choices' => '',
			'added_extensions' => '[]',
			'removed_extensions' => '[]',
			'extensions_not_uninstallable' => '[]',
			'copy_setup_files' => 1,
			'force-uninstall' => '',
			'use_symbolic_links' => MFCompiler::UseSymbolicLinks() ? 'on' : '',
			'return_application' => '',
			'target_env' => ITOP_DEFAULT_ENV,
		];

		$aHiddenInputs = [];
		foreach ($aHiddenInputNames as $sInputName => $defaultValue) {
			$aHiddenInputs[$sInputName] = utils::ReadPostedParam($sInputName, $defaultValue, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		}
		$aParams['aHiddenInputs'] = $aHiddenInputs;

		$aAddedExtensions = json_decode($aHiddenInputs['added_extensions'], true);

		$aRemovedExtensions = json_decode($aHiddenInputs['removed_extensions'], true);
		if ("[]" === $aHiddenInputs['selected_modules']) {
			//it does not come from setup
			// we get extensions from 1st screen uiblocks
			$this->ReadExtensionsDiff();
			$aHiddenInputs['force-uninstall'] = $this->bForcedUninstallation ? 'on' : '';
			$aAddedExtensions = $this->aExtensionsToCheck['to_be_installed'];
			$aHiddenInputs['added_extensions'] = $this->ConvertIntoSetupFormat($aAddedExtensions);

			$aRemovedExtensions = $this->aExtensionsToCheck['to_be_removed'];
			$aHiddenInputs['removed_extensions'] = $this->ConvertIntoSetupFormat($aRemovedExtensions);
		}

		$aParams['aAddedExtensions'] = $aAddedExtensions;
		$aParams['aRemovedExtensions'] = $aRemovedExtensions;

		DataFeatureRemovalLog::Debug(__METHOD__.' Extensions given in parameter', null, [
			'added_extensions' => $aAddedExtensions,
			'removed_extensions' => $aRemovedExtensions]);

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['iColumnCount'] = $this->iColumnCount;
		$aParams['aAvailableExtensions'] = $this->SplitArrayIntoColumns($this->GetExtensionsDiff($aAddedExtensions, $aRemovedExtensions), $this->iColumnCount);

		//to make setup redirection work, we need to pass complex data structures to setup wizards (ie extension/module lists)
		$sSourceEnv = MetaModel::GetEnvironment();
		$this->oRuntimeEnvironment = new RunTimeEnvironment($sSourceEnv, false);

		if ('[]' === $aHiddenInputs['selected_modules']) {
			$oConfig = MetaModel::GetConfig();
			$aSelectedExtensions = DataFeatureRemoverExtensionService::GetInstance()->GetExtensionMap()->GetSelectedExtensions($oConfig, array_keys($aAddedExtensions), array_keys($aRemovedExtensions));
			$aHiddenInputs['selected_extensions'] = $this->ConvertIntoSetupFormat($aSelectedExtensions);

			$aSelectedModules = []; // keep it to compile method
		} else {
			$aSelectedExtensions = json_decode($aHiddenInputs['selected_extensions'], true);
			$aSelectedModules = json_decode($aHiddenInputs['selected_modules'], true);
		}

		try {
			$this->Compile($aSelectedExtensions, array_keys($aRemovedExtensions), $aSelectedModules);
			$aHiddenInputs['selected_modules'] = $this->ConvertIntoSetupFormat($aSelectedModules);
		} catch (CoreException $e) {
			$aParams['DataFeatureRemovalErrorMessage'] = $e->getHtmlDesc();
			$this->DisplayPage($aParams, 'AnalysisResult');
			return;
		} catch (Exception $e) {
			$aParams['DataFeatureRemovalErrorMessage'] = $e->getMessage();
			$this->DisplayPage($aParams, 'AnalysisResult');
			return;
		}

		$oSetupAudit = new SetupAudit($sSourceEnv);
		$aGetRemovedClasses = array_keys($oSetupAudit->RunDataAudit());
		DataFeatureRemovalLog::Debug(__METHOD__, null, ['aGetRemovedClasses' => $aGetRemovedClasses]);

		$aParams['aClasses'] = $aGetRemovedClasses;

		new ContextTag(ContextTag::TAG_SETUP);
		$aParams['sLaunchSetupUrl'] = utils::GetAbsoluteUrlAppRoot().'setup/wizard.php';
		$aParams['aSetupParams'] = [
			"_class" => "WizStepLandingBeforeAudit",
			"operation" => "next",
		];

		foreach ($aHiddenInputs as $sInputName => $sInputValue) {
			$aParams['aSetupParams'][$sInputName] = $sInputValue;
		}

		[$aParams['aDeletionPlanSummary'], $aParams['iQueryCount'], $aParams['bDeletionPossible']] = $this->GetDeletionPlanSummaryTable($aGetRemovedClasses);
		[$aParams['aDeletionExecutionSummary'], $aParams['bHasDeletionExecution']] = $this->GetExecutionSummaryTable();
		$aParams['bDeletionNeeded'] = ($aParams['iQueryCount'] > 0);
		Session::Set('aDeletionExecutionSummary', serialize($this->aDeletionExecutionSummary));

		if (!$aParams['bDeletionNeeded']) {
			// Erase session setup parameters
			SetupUtils::CreateSetupToken();
		}

		$this->DisplayPage($aParams, 'AnalysisResult');
	}

	private function ConvertIntoSetupFormat(array $aData): string
	{
		$aNewData = [];
		foreach ($aData as $k => $sVal) {
			$aNewData[] = sprintf('"%s":"%s"', $k, $sVal);
		}

		return "{".implode(',', $aNewData)."}";
	}

	/**
	 * @param array $aSelectedExtensions
	 * @param array $aRemovedExtensions
	 * @param array $aSelectedModules
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	private function Compile(array $aSelectedExtensions, array $aRemovedExtensions, array &$aSelectedModules): void
	{
		$sSourceEnv = MetaModel::GetEnvironment();
		$sBuildDir = APPROOT."/env-$sSourceEnv-build";
		if (! is_dir($sBuildDir)) {
			SetupUtils::builddir($sBuildDir);
		}
		$bIsDirEmpty = count(scandir($sBuildDir)) === 2;
		$bForceCompilation = Session::Get('bForceCompilation', false);

		$oConfig = MetaModel::GetConfig();
		if ($bIsDirEmpty || $bForceCompilation) {
			Session::Unset('bForceCompilation');
			$this->oRuntimeEnvironment->CopySetupFiles();
			if (count($aSelectedModules) === 0) {
				$aSelectedModules = $this->oRuntimeEnvironment->GetModulesToLoadFromChoices($oConfig, $aSelectedExtensions);
			}
			DataFeatureRemovalLog::Debug(
				__METHOD__,
				null,
				['sSourceEnv' => $sSourceEnv, 'sBuildDir' => $sBuildDir, 'bIsDirEmpty' => $bIsDirEmpty, glob("$sBuildDir/*")]
			);
			$this->oRuntimeEnvironment->DoCompile($aSelectedExtensions, $aRemovedExtensions, $aSelectedModules, MFCompiler::CanUseSymbolicLinks());
		} else {
			if (count($aSelectedModules) === 0) {
				$aSelectedModules = $this->oRuntimeEnvironment->GetModulesToLoadFromChoices($oConfig, $aSelectedExtensions);
			}
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
		/** @var DataCleanupSummaryEntity $oSummary */
		foreach ($this->aDeletionExecutionSummary as $sClass => $oSummary) {
			$aRows[] = [
				$sClass,
				$oSummary->iTotalDeleteCount,
				$oSummary->iTotalUpdateCount,
				$oSummary->iDeleteCount,
				$oSummary->iUpdateCount,
			];
		}

		$aTableData = $this->GetTableData($sName, $aColumns, $aRows);

		return [$aTableData, true];

	}

	private function GetDeletionPlanSummaryTable(array $aRemovedClasses): array
	{
		$sName = 'DeletionPlanSummary';
		$oDataCleanupService = new StaticDeletionPlan();
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

	public function OperationDoDeletion(): void
	{
		$this->ValidateTransactionId();

		$this->aDeletionExecutionSummary = unserialize(Session::Get('aDeletionExecutionSummary'));
		Session::Unset('aDeletionExecutionSummary');
		$aClasses = utils::ReadPostedParam('classes', null, utils::ENUM_SANITIZATION_FILTER_CLASS);

		$oDataCleanupService = new DataCleanupService();
		$aDeletionExecutionSummary = $oDataCleanupService->ExecuteCleanup($aClasses);
		foreach ($aDeletionExecutionSummary as $sClass => $oExecutionSummary) {
			if (!array_key_exists($sClass, $this->aDeletionExecutionSummary)) {
				$this->aDeletionExecutionSummary[$sClass] = new DataCleanupSummaryEntity($sClass);
			}
			$oSummary = $this->aDeletionExecutionSummary[$sClass];
			$oSummary->iDeleteCount = $oExecutionSummary->iDeleteCount;
			$oSummary->iUpdateCount = $oExecutionSummary->iUpdateCount;
			$oSummary->iTotalDeleteCount += $oExecutionSummary->iDeleteCount;
			$oSummary->iTotalUpdateCount += $oExecutionSummary->iUpdateCount;
		}

		$this->OperationAnalysisResult();
	}

	private function GetAvailableExtensions(bool $bIncludePackageExtensions = false): array
	{
		$aExtensionsData = [];
		if ($bIncludePackageExtensions) {
			$aExtensionsRef = DataFeatureRemoverExtensionService::GetInstance()->GetExtensionMap()->GetAllExtensionsWithPreviouslyInstalled();
		} else {
			$aExtensionsRef = DataFeatureRemoverExtensionService::GetInstance()->ReadItopExtensions();
		}

		foreach ($aExtensionsRef as $oExtension) {
			/** @var \iTopExtension $oExtension */
			$aExtensionsData[$oExtension->sCode] = [
				'version' => $oExtension->sVersion,
				'label' => $oExtension->sLabel,
				'code' => $oExtension->sCode,
				'description' => $oExtension->sDescription,
				'source' => $oExtension->GetExtensionSourceLabel(),
				'installed' => $oExtension->bInstalled,
				'extra_flags' => [
					'uninstallable' => $oExtension->CanBeUninstalled(),
					'remote' => $oExtension->IsRemote(),
					'missing' => $oExtension->bRemovedFromDisk,
					'dependency_issue' => (!$oExtension->bInstalled && $oExtension->HasDependencyIssue()),
				],

			];
		}

		return $aExtensionsData;
	}

	private function GetExtensionsDiff(array $aAddedExtensions, array $aRemovedExtensions): array
	{
		$aExtensions = [];
		foreach ($this->GetAvailableExtensions(true) as $sCode => $aExtension) {
			$aExtension['extra_flags']['disabled'] = true;
			if (isset($aAddedExtensions[$sCode])) {
				$aExtension['extra_flags']['selected'] = true;
				$aExtensions[$sCode] = $aExtension;
			} elseif (isset($aRemovedExtensions[$sCode])) {
				$aExtension['extra_flags']['selected'] = false;
				$aExtensions[$sCode] = $aExtension;
			}
		}

		return $aExtensions;
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
		DataFeatureRemovalLog::Debug(__FUNCTION__.": Transaction [$sTransactionId]");
		if (empty($sTransactionId) || !utils::IsTransactionValid($sTransactionId, false)) {
			throw new DataFeatureRemovalException(Dict::S("iTopUpdate:Error:InvalidToken"));
		}
	}

	/**
	 * Read extensions selected from posted parameters
	 * @return int Number of extensions to be added or removed
	 */
	public function ReadExtensionsDiff(): int
	{
		if (!is_null($this->aExtensionsToCheck)) {
			return count($this->aExtensionsToCheck['to_be_installed']) + count($this->aExtensionsToCheck['to_be_removed']);
		}

		$aAvailableExtensions = $this->GetAvailableExtensions();
		$aSelectedExtensionsFromUI = utils::ReadPostedParam('aSelectedExtensions', []);
		$this->aExtensionsToCheck = [
			'to_be_installed' => [],
			'to_be_removed' => [],
		];
		foreach ($aAvailableExtensions as $sCode => &$aExtensionData) {
			if (!isset($aSelectedExtensionsFromUI[$sCode])) {
				continue;
			}

			if ($aExtensionData['installed'] && $aSelectedExtensionsFromUI[$sCode] !== 'on') {
				$aExtensionData['extra_flags']['selected'] = false;
				$sLabel = $aAvailableExtensions[$sCode]['label'];
				$this->aExtensionsToCheck['to_be_removed'][$sCode] = $sLabel;
				if (! $this->bForcedUninstallation && $aExtensionData['extra_flags']['uninstallable']) {
					$this->bForcedUninstallation = true;
				}
			} elseif (!$aExtensionData['installed'] && $aSelectedExtensionsFromUI[$sCode] === 'on') {
				$aExtensionData['extra_flags']['selected'] = true;
				$sLabel = $aAvailableExtensions[$sCode]['label'];
				$this->aExtensionsToCheck['to_be_installed'][$sCode] = $sLabel;
			}
		}
		return count($this->aExtensionsToCheck['to_be_installed']) + count($this->aExtensionsToCheck['to_be_removed']);
	}

	/**
	 * Divide an array into several sub arrays, distributing elements so that every sub array has an equal amount of elements
	 * @param mixed[] $aInput
	 * @param int $iColNumber
	 *
	 * @return array[]
	 */
	private function SplitArrayIntoColumns(array $aInput, int $iColNumber)
	{
		$aOutput = array_fill(0, $iColNumber, []);
		$iIndex = 0;
		foreach ($aInput as $mItem) {
			//Split extensions in $iColNumber columns
			$aOutput[$iIndex % $this->iColumnCount][] = $mItem;
			$iIndex++;
		}
		return $aOutput;
	}
}

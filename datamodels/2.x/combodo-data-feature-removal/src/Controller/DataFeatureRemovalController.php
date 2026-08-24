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
		$oParameters = new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME);
		$oParameters->Erase();
		Session::Unset('aDeletionExecutionSummary');
		Session::Set('bForceCompilation', true);
		$oParameters->SetParameter('return_application', 'DataFeatureRemoval');

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
		$aSetupParameterNames = [
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

		$oParameters = new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME);
		foreach ($aSetupParameterNames as $sInputName => $defaultValue) {
			$oParameters->SetParameter($sInputName, $oParameters->GetParameter($sInputName, $defaultValue));
		}

		$aAddedExtensions = json_decode($oParameters->GetParameter('added_extensions', '[]'), true);
		$aRemovedExtensions = json_decode($oParameters->GetParameter('removed_extensions', '[]'), true);
		if ('[]' === $oParameters->GetParameter('selected_modules', '[]')) {
			//it does not come from setup
			// we get extensions from 1st screen uiblocks
			$this->ReadExtensionsDiff();
			$oParameters->SetParameter('force-uninstall', $this->bForcedUninstallation ? 'on' : '');
			$aAddedExtensions = $this->aExtensionsToCheck['to_be_installed'];
			$oParameters->SetParameter('added_extensions', $this->ConvertIntoSetupFormat($aAddedExtensions));

			$aRemovedExtensions = $this->aExtensionsToCheck['to_be_removed'];
			$oParameters->SetParameter('removed_extensions', $this->ConvertIntoSetupFormat($aRemovedExtensions));

			$aExtensionsNotUninstallable = $this->aExtensionsToCheck['extensions_not_uninstallable'];
			$oParameters->SetParameter('extensions_not_uninstallable', $this->ConvertIntoSetupFormat($aExtensionsNotUninstallable));
		}

		DataFeatureRemovalLog::Debug(__METHOD__.' Extensions given in parameter', null, [
			'added_extensions' => $aAddedExtensions,
			'removed_extensions' => $aRemovedExtensions]);

		$aParams['sTransactionId'] = utils::GetNewTransactionId();
		$aParams['iColumnCount'] = $this->iColumnCount;
		$aAvailableExtensions = $this->GetExtensionsDiff($aAddedExtensions, $aRemovedExtensions);
		$aParams['aAvailableExtensionsCount'] = count($aAvailableExtensions);
		$aParams['aAvailableExtensions'] = $this->SplitArrayIntoColumns($aAvailableExtensions, $this->iColumnCount);
		$aParams['sAjaxURL'] = utils::GetAbsoluteUrlModulePage(DataFeatureRemovalHelper::MODULE_NAME, 'index.php');

		$this->DisplayPage($aParams, 'AnalysisResult');
	}

	private function ConvertIntoSetupFormat(array $aData): string
	{
		$aNewData = [];
		foreach ($aData as $sCode => $sLabel) {
			$aNewData[] = sprintf('"%s":"%s"', $sCode, $sLabel);
		}

		return "{".implode(',', $aNewData)."}";
	}

	/**
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function OperationAjaxCompile(): void
	{
		$aParams = [];
		//to make setup redirection work, we need to pass complex data structures to setup wizards (ie extension/module lists)
		$sSourceEnv = MetaModel::GetEnvironment();
		$oRuntimeEnvironment = new RunTimeEnvironment($sSourceEnv, false);

		$oParameters = new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME);
		$aSelectedModules = json_decode($oParameters->GetParameter('selected_modules', '[]'), true);
		$aSelectedExtensions = json_decode($oParameters->GetParameter('selected_extensions', '[]'), true);
		$aAddedExtensions = json_decode($oParameters->GetParameter('added_extensions', '[]'), true);
		$aRemovedExtensions = json_decode($oParameters->GetParameter('removed_extensions', '[]'), true);

		DataFeatureRemovalLog::Debug(__METHOD__, null, ['removed_extensions' => $aRemovedExtensions]);
		DataFeatureRemovalLog::Debug(__METHOD__, null, ['selected_modules' => $aSelectedModules]);
		DataFeatureRemovalLog::Debug(__METHOD__, null, ['added_extensions' => $aAddedExtensions]);
		try {
			$this->ValidateTransactionId();

			$oConfig = MetaModel::GetConfig();
			if (count($aSelectedModules) === 0) {
				$aSelectedExtensions = DataFeatureRemoverExtensionService::GetInstance()->GetExtensionMap()->GetSelectedExtensions($oConfig, array_keys($aAddedExtensions), array_keys($aRemovedExtensions));
				$oParameters->SetParameter('selected_extensions', $this->ConvertIntoSetupFormat($aSelectedExtensions));
			}

			$sBuildDir = APPROOT."/env-$sSourceEnv-build";
			if (! is_dir($sBuildDir)) {
				SetupUtils::builddir($sBuildDir);
			}
			$bIsDirEmpty = count(scandir($sBuildDir)) === 2;
			$bForceCompilation = Session::Get('bForceCompilation', false);
			if ($bIsDirEmpty || $bForceCompilation) {

				$oRuntimeEnvironment->CopySetupFiles();
				if (count($aSelectedModules) === 0) {
					$oExtensionsMap = \iTopExtensionsMap::GetExtensionsMap($oRuntimeEnvironment->GetBuildEnv());
					// Removed modules are stored as static for FindModules()
					$oExtensionsMap->DeclareExtensionAsRemoved($aRemovedExtensions);
					$aSelectedModules = $oRuntimeEnvironment->GetModulesToLoadFromChoices($oConfig, $aSelectedExtensions);
				}

				DataFeatureRemovalLog::Debug(
					__METHOD__,
					null,
					['sSourceEnv' => $sSourceEnv, 'sBuildDir' => $sBuildDir, 'bIsDirEmpty' => $bIsDirEmpty, glob("$sBuildDir/*")]
				);
				$oRuntimeEnvironment->DoCompile($aSelectedExtensions, $aRemovedExtensions, $aSelectedModules, MFCompiler::CanUseSymbolicLinks());
				Session::Unset('bForceCompilation');
			} else {
				if (count($aSelectedModules) === 0) {
					$aSelectedModules = $oRuntimeEnvironment->GetModulesToLoadFromChoices($oConfig, $aSelectedExtensions);
				}
			}
		} catch (CoreException $e) {
			$aParams['error_message'] = $e->getHtmlDesc();
		} catch (Exception $e) {
			$aParams['error_message'] = $e->getMessage();
		}

		$aParams['success_message'] = Dict::S('DataFeatureRemoval:Compile:Success');
		$aParams['transaction_id'] = utils::GetNewTransactionId();

		$oParameters->SetParameter('selected_modules', json_encode($aSelectedModules));
		$this->DisplayJSONPage($aParams);
	}

	public function OperationAjaxRunAudit(): void
	{
		$oParameters = new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME);
		$aParams = [];
		$aPageParams = [];
		try {
			$this->ValidateTransactionId();

			$sSourceEnv = MetaModel::GetEnvironment();
			$oSetupAudit = new SetupAudit($sSourceEnv);
			$aRemovedClasses = array_keys($oSetupAudit->RunDataAudit());
			$oParameters->SetParameter('classes', $aRemovedClasses);

			new ContextTag(ContextTag::TAG_SETUP);
			$aPageParams['sLaunchSetupUrl'] = utils::GetAbsoluteUrlAppRoot().'setup/wizard.php';
			$aPageParams['aSetupParams'] = [
				"_class" => "WizStepLandingBeforeAudit",
				"operation" => "next",
			];
			$aPageParams['sTransactionId'] = utils::GetNewTransactionId();

			$aDeletionPlanSummaryEntities = $this->GetDeletionPlanSummaryEntities($aRemovedClasses);
			[$aPageParams['aDeletionPlanSummary'], $aPageParams['iQueryCount'], $aPageParams['bDeletionPossible']] = $this->GetDeletionPlanSummaryTable($aDeletionPlanSummaryEntities);
			[$aPageParams['aDeletionExecutionSummary'], $aPageParams['bHasDeletionExecution']] = $this->GetExecutionSummaryTable();
			$aPageParams['bDeletionNeeded'] = ($aPageParams['iQueryCount'] > 0);

			if (!$aPageParams['bDeletionNeeded']) {
				// Erase session setup parameters
				SetupUtils::CreateSetupToken();
			}

			$this->DisplayAjaxPage($aPageParams, 'AjaxRunAudit');
			return;
		} catch (CoreException $e) {
			$aParams['error_message'] = $e->getHtmlDesc();
		} catch (Exception $e) {
			$aParams['error_message'] = $e->getMessage();
		}

		$this->DisplayJSONPage($aParams);
	}

	private function GetExecutionSummaryTable(): array
	{
		$sName = 'ExecutionSummary';

		$this->aDeletionExecutionSummary = unserialize(Session::Get('aDeletionExecutionSummary') ?? serialize([]));

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

	private function GetDeletionPlanSummaryEntities(array $aRemovedClasses): array
	{
		$oDataCleanupService = new StaticDeletionPlan();
		return $oDataCleanupService->GetCleanupSummary($aRemovedClasses);
	}

	private function GetDeletionPlanSummaryTable(array $aDeletionPlanSummaryEntities): array
	{
		$sName = 'DeletionPlanSummary';
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

		$this->aDeletionExecutionSummary = unserialize(Session::Get('aDeletionExecutionSummary') ?? serialize([]));
		Session::Unset('aDeletionExecutionSummary');
		$oParameters = new SessionParameters(SetupUtils::SESSION_PARAMETERS_NAME);
		$aClasses = $oParameters->GetParameter('classes', []);

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

		Session::Set('aDeletionExecutionSummary', serialize($this->aDeletionExecutionSummary));
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
					'dependency_issue' => $oExtension->HasDependencyIssue(),
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
	 */
	public function ReadExtensionsDiff(): void
	{
		if (!is_null($this->aExtensionsToCheck)) {
			return;
		}

		$aAvailableExtensions = $this->GetAvailableExtensions();
		$aSelectedExtensionsFromUI = utils::ReadPostedParam('aSelectedExtensions', []);
		$this->aExtensionsToCheck = [
			'to_be_installed' => [],
			'to_be_removed' => [],
			'extensions_not_uninstallable' => [],
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
				if (false === $aExtensionData['extra_flags']['uninstallable'] || true === $aExtensionData['extra_flags']['remote']) {
					$this->aExtensionsToCheck['extensions_not_uninstallable'][] = $sCode;
				}
			} elseif (!$aExtensionData['installed'] && $aSelectedExtensionsFromUI[$sCode] === 'on') {
				$aExtensionData['extra_flags']['selected'] = true;
				$sLabel = $aAvailableExtensions[$sCode]['label'];
				$this->aExtensionsToCheck['to_be_installed'][$sCode] = $sLabel;
			}
		}
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

<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/xmldataloader.class.inc.php');
require_once APPROOT.'setup/feature_removal/SetupAudit.php';

require_once(APPROOT.'setup/sequencers/StepSequencer.php');
require_once(APPROOT.'setup/sequencers/ApplicationInstallSequencer.php');

use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;

class DataAuditSequencer extends ApplicationInstallSequencer
{
	public const DATA_AUDIT_FAILED = 100;

	protected function GetTempEnv()
	{
		$sTargetEnv = $this->GetTargetEnv();
		return 'dry-'.$sTargetEnv;
	}

	protected function GetTargetDir()
	{
		$sTargetEnv = $this->GetTempEnv();
		return 'env-'.$sTargetEnv;
	}

	/**
	 * Executes the next step of the installation and reports about the progress
	 * and the next step to perform
	 *
	 * @param string $sStep The identifier of the step to execute
	 * @param string|null $sInstallComment
	 *
	 * @return array (status => , message => , percentage-completed => , next-step => , next-step-label => )
	 */
	public function ExecuteStep($sStep = '', $sInstallComment = null)
	{
		try {
			$fStart = microtime(true);
			SetupLog::Info("##### STEP {$sStep} start");
			switch ($sStep) {
				case '':
					$this->DoLogParameters('data-audit-', 'Data Audit');

					$aResult = [
						'status' => self::OK,
						'message' => '',
						'percentage-completed' => 20,
						'next-step' => 'compile',
						'next-step-label' => 'Compiling the data model',
					];

					break;

				case 'compile':
					$aSelectedModules = $this->oParams->Get('selected_modules');
					$sSourceDir = $this->oParams->Get('source_dir', 'datamodels/latest');
					$sExtensionDir = $this->oParams->Get('extensions_dir', 'extensions');
					$aRemovedExtensionCodes = $this->oParams->Get('removed_extensions', []);

					$this->DoCompile(
						$aRemovedExtensionCodes,
						$aSelectedModules,
						$sSourceDir,
						$sExtensionDir,
						false,
						false
					);

					$aResult = [
						'status' => self::OK,
						'message' => '',
						'next-step' => 'write-config',
						'next-step-label' => 'Writing audit config',
						'percentage-completed' => 40,
					];
					break;
				case 'write-config':
					$this->DoWriteConfig();
					$aResult = [
						'status' => self::OK,
						'message' => '',
						'next-step' => 'setup-audit',
						'next-step-label' => 'Checking data consistency with the new data model',
						'percentage-completed' => 60,
					];
					break;
				case 'setup-audit':
					$this->DoSetupAudit();
					$aResult = [
						'status' => self::OK,
						'message' => '',
						'next-step' => 'cleanup',
						'next-step-label' => 'Temporary folders cleanup',
						'percentage-completed' => 80,
					];
					break;
				case 'cleanup' :
					$this->DoCleanup();
					$aResult = [
						'status' => self::OK,
						'message' => '',
						'next-step' => '',
						'next-step-label' => 'Completed',
						'percentage-completed' => 100,
					];
					break;
				default:
					$aResult = [
						'status' => self::ERROR,
						'message' => '',
						'next-step' => '',
						'next-step-label' => "Unknown setup step '$sStep'.",
						'percentage-completed' => 100,
					];
					break;
			}
		} catch (Exception $e) {
			$aResult = [
				'status' => self::ERROR,
				'message' => $e->getMessage(),
				'next-step' => '',
				'next-step-label' => '',
				'percentage-completed' => 100,
				'error_code' => $e->getCode(),
			];

			$this->ReportException($e);
		} finally {
			$fDuration = round(microtime(true) - $fStart, 2);
			SetupLog::Info("##### STEP {$sStep} duration: {$fDuration}s");
		}

		return $aResult;
	}

	protected function DoWriteConfig()
	{
		$sConfigFilePath = utils::GetConfigFilePath($this->GetTargetEnv());
		if (is_file($sConfigFilePath)) {
			$oConfig = new Config($sConfigFilePath);

			$sTempConfigFileName = utils::GetConfigFilePath($this->GetTempEnv());
			$sConfigDir = dirname($sTempConfigFileName);
			@mkdir($sConfigDir);
			@chmod($sConfigDir, 0770); // RWX for owner and group, nothing for others

			return $oConfig->WriteToFile($sTempConfigFileName);
		}
		return false;
	}

	protected function DoSetupAudit()
	{
		/**
		 * @since 3.2.0 move the ContextTag init at the very beginning of the method
		 * @noinspection PhpUnusedLocalVariableInspection
		 */
		$oContextTag = new ContextTag(ContextTag::TAG_SETUP);

		$sTargetEnvironment = $this->GetTempEnv();
		$sPreviousEnvironment = $this->GetTargetEnv();

		$oSetupAudit = new SetupAudit($sPreviousEnvironment);

		//Make sure the MetaModel is started before analysing for issues
		$sConfFile = utils::GetConfigFilePath($sPreviousEnvironment);
		MetaModel::Startup($sConfFile, false /* $bModelOnly */, false /* $bAllowCache */, false /* $bTraceSourceFiles */, $sPreviousEnvironment);
		$oSetupAudit->GetIssues(true);
		$iCount = $oSetupAudit->GetDataToCleanupCount();

		if ($iCount > 0) {
			throw new Exception("$iCount elements require data adjustments or cleanup in the backoffice prior to upgrading iTop", static::DATA_AUDIT_FAILED);
		}
	}

	protected function DoCleanup()
	{
		$sDestination = APPROOT.$this->GetTargetDir();
		SetupUtils::tidydir($sDestination);
		SetupUtils::rmdir_safe($sDestination);
	}
}

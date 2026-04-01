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

class DataAuditSequencer extends ApplicationInstallSequencer
{
	public const DATA_AUDIT_FAILED = 100;

	protected function GetTempEnv()
	{
		$sTargetEnv = $this->GetTargetEnv();

		return $sTargetEnv.'-build';
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
			/**
			 * @since 3.2.0 move the ContextTag init at the very beginning of the method
			 * @noinspection PhpUnusedLocalVariableInspection
			 */
			$oContextTag = new ContextTag(ContextTag::TAG_SETUP);
			$fStart = microtime(true);
			SetupLog::Info("##### STEP {$sStep} start");
			switch ($sStep) {
				case '':
					return $this->GetNextStep('copy', 'Copying data model files', 5);

				case 'copy':
					$this->oRunTimeEnvironment->CopySetupFiles();
					return $this->GetNextStep('compile', 'Compiling the data model', 20, 'Copying...');

				case 'compile':
					$aSelectedModules = $this->oParams->Get('selected_modules');
					$sSourceDir = $this->oParams->Get('source_dir', 'datamodels/latest');
					$sExtensionDir = $this->oParams->Get('extensions_dir', 'extensions');
					$aMiscOptions = $this->oParams->Get('options', []);
					$aRemovedExtensionCodes = $this->oParams->Get('removed_extensions', []);
					$bUseSymbolicLinks = $aMiscOptions['symlinks'] ?? false;
					$sMessage = $bUseSymbolicLinks ? '' : 'Using symbolic links instead of copying data model files (for developers only!)';
					$this->oRunTimeEnvironment->DoCompile(
						$aRemovedExtensionCodes,
						$aSelectedModules,
						$sSourceDir,
						$sExtensionDir,
						$bUseSymbolicLinks
					);
					return $this->GetNextStep('setup-audit', 'Checking data consistency with the new data model', 70, $sMessage);

				case 'setup-audit':
					$this->oRunTimeEnvironment->DataToCleanupAudit();
					return $this->GetNextStep('', 'Completed', 100);

				default:
					return $this->GetNextStep('', "Unknown setup step '$sStep'.", 100, '', self::ERROR);
			}
		}
		catch (Exception $e) {
			$this->ReportException($e);
			$aResult = $this->GetNextStep('', '', 100, $e->getMessage(), self::ERROR);
			$aResult['error_code'] = $e->getCode();
			return $aResult;
		}
		finally {
			$fDuration = round(microtime(true) - $fStart, 2);
			SetupLog::Info("##### STEP {$sStep} duration: {$fDuration}s");
		}
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
}

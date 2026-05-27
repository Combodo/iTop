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
require_once(APPROOT.'setup/backup.class.inc.php');

require_once(APPROOT.'setup/sequencers/StepSequencer.php');
require_once(APPROOT.'setup/SetupDBBackup.php');

/**
 * The base class for the installation process.
 * The installation process is split into a sequence of unitary steps
 * for performance reasons (i.e; timeout, memory usage) and also in order
 * to provide some feedback about the progress of the installation.
 *
 * This class can be used for a step by step interactive installation
 * while displaying a progress bar, or in an unattended manner
 * (for example from the command line), to run all the steps
 * in one go.
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class ApplicationInstallSequencer extends StepSequencer
{
	protected const LABELS = [
		'log-parameters' => 'Log parameters',
		'backup' => 'Performing a backup of the database',
		'migrate-before' => 'Migrate data before database upgrade',
		'db-schema' => 'Updating database schema',
		'migrate-after' => 'Migrate data after database upgrade',
		'after-db-create' => 'Load data after database create',
		'load-data' => 'Loading data',
		'create-config' => 'Creating the configuration File',
		'commit' => 'Finalize',
	];
	protected const SUCCESS_LABELS = [
		'log-parameters' => 'Parameters logged',
		'backup' => 'Database backup completed',
		'migrate-before' => 'Pre-upgrade data migration completed',
		'db-schema' => 'Database schema updated',
		'migrate-after' => 'Post-upgrade data migration completed',
		'after-db-create' => 'Post-creation data loaded',
		'load-data' => 'Data loaded',
		'create-config' => 'Configuration file created',
	];

	/**
	 * @inherit
	 */
	public function ExecuteStep($sStep = '', $sInstallComment = null): array
	{
		$fStart = microtime(true);
		try {
			/**
			 * @since 3.2.0 move the ContextTag init at the very beginning of the method
			 * @noinspection PhpUnusedLocalVariableInspection
			 */
			$oContextTag = new ContextTag(ContextTag::TAG_SETUP);
			SetupLog::Info("##### STEP {$sStep} start");
			$this->oRunTimeEnvironment->EnterReadOnlyMode($this->GetConfig());
			switch ($sStep) {
				case '':
					return $this->ComputeNextStep($sStep);

				case 'log-parameters':
					if (array_key_exists($sStep, $this->oParams->Get('optional_steps', []))) {
						$this->DoLogParameters();
					}
					return $this->ComputeNextStep($sStep);

				case 'backup':
					if (array_key_exists($sStep, $this->oParams->Get('optional_steps', []))) {
						$aBackupOptions = $this->oParams->Get('optional_steps')['backup'];
						// __DB__-%Y-%m-%d
						$sDestination = $aBackupOptions['destination'];
						$sSourceConfigFile = $aBackupOptions['configuration_file'];
						$sMySQLBinDir = $this->oParams->Get('mysql_bindir', null);
						$this->oRunTimeEnvironment->Backup($this->GetConfig(), $sDestination, $sSourceConfigFile, $sMySQLBinDir);
					}

					return $this->ComputeNextStep($sStep);

				case 'migrate-before':
					$this->oRunTimeEnvironment->EnterMaintenanceMode($this->GetConfig());
					if (array_key_exists($sStep, $this->oParams->Get('optional_steps', []))) {
						$this->oRunTimeEnvironment->MigrateDataBeforeUpdateStructure($this->oParams->Get('mode'), $this->GetConfig());
					}
					return $this->ComputeNextStep($sStep);

				case 'db-schema':
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$this->DoUpdateDBSchema($this->GetConfig(), $aSelectedModules);
					return $this->ComputeNextStep($sStep);

				case 'migrate-after':
					if (array_key_exists($sStep, $this->oParams->Get('optional_steps', []))) {
						$this->oRunTimeEnvironment->MigrateDataAfterUpdateStructure($this->oParams->Get('mode'), $this->GetConfig());
					}
					return $this->ComputeNextStep($sStep);

				case 'after-db-create':
					$aAdminParams = $this->oParams->Get('admin_account');
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$sMode = $this->oParams->Get('mode');
					$this->oRunTimeEnvironment->AfterDBCreate($this->GetConfig(), $sMode, $aSelectedModules, $aAdminParams);
					return $this->ComputeNextStep($sStep);

				case 'load-data':
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$bSampleData = ($this->oParams->Get('sample_data', 0) == 1);
					$this->oRunTimeEnvironment->DoLoadData($this->GetConfig(), $bSampleData, $aSelectedModules);
					return $this->ComputeNextStep($sStep);

				case 'create-config':
					$sDataModelVersion = $this->oParams->Get('datamodel_version', '0.0.0');
					$aSelectedModuleCodes = $this->oParams->Get('selected_modules', []);
					$aSelectedExtensionCodes = $this->oParams->Get('selected_extensions', []);
					$this->oRunTimeEnvironment->DoCreateConfig(
						$this->GetConfig(),
						$sDataModelVersion,
						$aSelectedModuleCodes,
						$aSelectedExtensionCodes,
						$sInstallComment,
						$this->sSourceDesc
					);
					return $this->ComputeNextStep($sStep);

				case 'commit':
					$this->oRunTimeEnvironment->Commit();
					$this->oRunTimeEnvironment->ExitReadOnlyMode();
					$this->oRunTimeEnvironment->ExitMaintenanceMode();
					return $this->GetNextStep('', 'Completed', 100);

				default:
					return $this->GetNextStep('', "Unknown setup step '$sStep'.", 100, '', '', self::ERROR);
			}
		} catch (Exception $e) {
			SetupLog::Exception("$sStep failed", $e);
			$aResult = $this->GetNextStep('', '', 100, $e->getMessage(), '', self::ERROR);
			$aResult['error_code'] = $e->getCode();
			return $aResult;
		} finally {
			$fDuration = round(microtime(true) - $fStart, 2);
			SetupLog::Info("##### STEP {$sStep} duration: {$fDuration}s");
		}
	}

	/**
	 * @param \Config $oConfig
	 * @param $aSelectedModules
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function DoUpdateDBSchema(Config $oConfig, $aSelectedModules)
	{
		$sTargetEnvironment = $this->oRunTimeEnvironment->GetBuildEnv();
		$aParamValues = $this->oParams->GetParamForConfigArray();

		SetupLog::Info("Update Database Schema for environment '$sTargetEnvironment'.");
		$sMode = $aParamValues['mode'];

		$this->oRunTimeEnvironment->UpdateDBSchema($oConfig, $sMode, $aSelectedModules);

		$this->oRunTimeEnvironment->SetDbUUID();

		SetupLog::Info("Database Schema Successfully Updated for environment '$sTargetEnvironment'.");
	}

	public function GetStepNames(): array
	{
		$aStepNames = [ ''];
		foreach (['log-parameters', 'backup', 'migrate-before'] as $sStepName) {
			if (array_key_exists($sStepName, $this->oParams->Get('optional_steps', []))) {
				$aStepNames [] = $sStepName;
			}
		}
		$aStepNames [] = 'db-schema';
		if (array_key_exists('migrate-after', $this->oParams->Get('optional_steps', []))) {
			$aStepNames [] = 'migrate-after';
		}

		$aOthers = [
			'after-db-create',
			'load-data',
			'create-config',
			'commit',
		];
		$aStepNames = array_merge($aStepNames, $aOthers);
		return $aStepNames;
	}
}

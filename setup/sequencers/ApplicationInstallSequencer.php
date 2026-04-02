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
	/**
	 * @inherit
	 */
	public function ExecuteStep($sStep = '', $sInstallComment = null): array
	{
		try {
			/**
			 * @since 3.2.0 move the ContextTag init at the very beginning of the method
			 * @noinspection PhpUnusedLocalVariableInspection
			 */
			$oContextTag = new ContextTag(ContextTag::TAG_SETUP);
			$fStart = microtime(true);
			SetupLog::Info("##### STEP {$sStep} start");
			$this->EnterReadOnlyMode();
			switch ($sStep) {
				case '':
					return $this->GetNextStep('log-parameters', 'Log parameters', 0);

				case 'log-parameters':
					if (array_key_exists('log-parameters', $this->oParams->Get('optional_steps', []))) {
						$this->DoLogParameters();
					}

					return $this->GetNextStep('backup', 'Performing a backup of the database', 20);

				case 'backup':
					if (array_key_exists('backup', $this->oParams->Get('optional_steps', []))) {

						$aBackupOptions = $this->oParams->Get('optional_steps')['backup'];
						// __DB__-%Y-%m-%d
						$sDestination = $aBackupOptions['destination'];
						$sSourceConfigFile = $aBackupOptions['configuration_file'];
						$sMySQLBinDir = $this->oParams->Get('mysql_bindir', null);
						$this->oRunTimeEnvironment->Backup($this->oConfig, $sDestination, $sSourceConfigFile, $sMySQLBinDir);
					}

					return $this->GetNextStep('migrate-before', 'Migrate data before database upgrade', 30);

				case 'migrate-before':
					if (array_key_exists('migrate-before', $this->oParams->Get('optional_steps', []))) {
						$this->oRunTimeEnvironment->MigrateDataBeforeUpdateStructure($this->oParams->Get('mode'), $this->GetConfig());
					}
					return $this->GetNextStep('db-schema', 'Updating database schema', 40);

				case 'db-schema':
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$this->DoUpdateDBSchema($this->GetConfig(), $aSelectedModules);

					return $this->GetNextStep('migrate-after', 'Migrate data after database upgrade', 50);

				case 'migrate-after':
					if (array_key_exists('migrate-after', $this->oParams->Get('optional_steps', []))) {
						$this->oRunTimeEnvironment->MigrateDataAfterUpdateStructure($this->oParams->Get('mode'), $this->GetConfig());
					}
					return $this->GetNextStep('after-db-create', 'Load data after database create', 60);

				case 'after-db-create':
					$aAdminParams = $this->oParams->Get('admin_account');
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$sMode = $this->oParams->Get('mode');

					$this->oRunTimeEnvironment->AfterDBCreate($this->GetConfig(), $sMode, $aSelectedModules, $aAdminParams);

					return $this->GetNextStep('load-data', 'Loading data', 70);

				case 'load-data':
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$bSampleData = ($this->oParams->Get('sample_data', 0) == 1);

					$this->oRunTimeEnvironment->DoLoadData($this->GetConfig(),$bSampleData, $aSelectedModules);

					return $this->GetNextStep('create-config', 'Creating the configuration File', 80, 'All data loaded');

				case 'create-config':
					$sDataModelVersion = $this->oParams->Get('datamodel_version', '0.0.0');
					$aSelectedModuleCodes = $this->oParams->Get('selected_modules', []);
					$aSelectedExtensionCodes = $this->oParams->Get('selected_extensions', []);

					$this->oRunTimeEnvironment->DoCreateConfig($this->GetConfig(),
						$sDataModelVersion,
						$aSelectedModuleCodes,
						$aSelectedExtensionCodes,
						$sInstallComment
					);

					return $this->GetNextStep('commit', 'Finalize', 95);

				case 'commit':
					$this->oRunTimeEnvironment->Commit();
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
				$this->ExitReadOnlyMode();
				$fDuration = round(microtime(true) - $fStart, 2);
				SetupLog::Info("##### STEP {$sStep} duration: {$fDuration}s");
			}

	}

	protected function EnterReadOnlyMode()
	{
		if ($this->oRunTimeEnvironment->GetFinalEnv() != 'production') {
			return;
		}

		if (SetupUtils::IsInReadOnlyMode()) {
			return;
		}

		SetupUtils::EnterReadOnlyMode($this->GetConfig());
	}

	protected function ExitReadOnlyMode()
	{
		if ($this->oRunTimeEnvironment->GetFinalEnv() != 'production') {
			return;
		}

		if (!SetupUtils::IsInReadOnlyMode()) {
			return;
		}

		SetupUtils::ExitReadOnlyMode();
	}

	/**
	 * @param $aSelectedModules
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	protected function DoUpdateDBSchema(Config $oConfig, $aSelectedModules)
	{
		$sTargetEnvironment = $this->oRunTimeEnvironment->GetBuildEnv();
		$aParamValues = $this->oParams->GetParamForConfigArray();

		SetupLog::Info("Update Database Schema for environment '$sTargetEnvironment'.");
		$sMode = $aParamValues['mode'];

		$this->oRunTimeEnvironment->UpdateDBSchema($oConfig, $sMode, $aSelectedModules);

		// Set a DBProperty with a unique ID to identify this instance of iTop
		$sUUID = DBProperty::GetProperty('database_uuid', '');
		if ($sUUID === '') {
			$sUUID = utils::CreateUUID('database');
			DBProperty::SetProperty('database_uuid', $sUUID, 'Installation/upgrade of '.ITOP_APPLICATION, 'Unique ID of this '.ITOP_APPLICATION.' Database');
		}

		SetupLog::Info("Database Schema Successfully Updated for environment '$sTargetEnvironment'.");
	}
}

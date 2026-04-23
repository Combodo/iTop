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

abstract class AbstractWizStepInstall extends WizardStep
{
	/**
	 * Prepare the parameters to execute the installation asynchronously
	 * @return array A big hash array that can be converted to XML or JSON with all the needed parameters
	 */
	protected function BuildConfig()
	{
		$sMode = $this->oWizard->GetParameter('install_mode', 'install');
		$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
		$aSelectedExtensions = json_decode($this->oWizard->GetParameter('selected_extensions'), true);
		$sBackupDestination = '';
		$sPreviousConfigurationFile = '';
		$sDBName = $this->oWizard->GetParameter('db_name');
		if ($sMode == 'upgrade') {
			$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir', '');
			if (!empty($sPreviousVersionDir)) {
				$aPreviousInstance = SetupUtils::GetPreviousInstance($sPreviousVersionDir);
				if ($aPreviousInstance['found']) {
					$sPreviousConfigurationFile = $aPreviousInstance['configuration_file'];
				}
			}

			if ($this->oWizard->GetParameter('db_backup', false)) {
				$sBackupDestination = $this->oWizard->GetParameter('db_backup_path', '');
			}
		} else {

			$sDBNewName = $this->oWizard->GetParameter('db_new_name', '');
			if ($sDBNewName != '') {
				$sDBName = $sDBNewName; // Database will be created
			}
		}

		$sSourceDir = $this->oWizard->GetParameter('source_dir');
		if (($sMode == 'upgrade') && ($this->oWizard->GetParameter('upgrade_type') == 'keep-previous')) {
			$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir');
			//$aCopies[] = ['source' => $sSourceDir, 'destination' => 'modules']; // Source is an absolute path, destination is relative to APPROOT
			//$aCopies[] = ['source' => $sPreviousVersionDir.'/portal', 'destination' => 'portal']; // Source is an absolute path, destination is relative to APPROOT
			$sSourceDir = APPROOT.'modules';
		}

		$aInstallParams =  [
			'mode' => $sMode,
			'optional_steps' => [
				'log-parameters' => true,
				'migrate-before' => true,
				'migrate-after' => true,
				// 'backup' => see below
			],
			'source_dir' => str_replace(APPROOT, '', $sSourceDir),
			'datamodel_version' => $this->oWizard->GetParameter('datamodel_version'), //TODO: let the installer compute this automatically...
			'previous_configuration_file' => $sPreviousConfigurationFile,
			'extensions_dir' => 'extensions',
			'target_env' => 'production',
			'workspace_dir' => '',
			'database' =>  [
				'server' => $this->oWizard->GetParameter('db_server'),
				'user' => $this->oWizard->GetParameter('db_user'),
				'pwd' => $this->oWizard->GetParameter('db_pwd'),
				'name' => $sDBName,
				'db_tls_enabled' => $this->oWizard->GetParameter('db_tls_enabled'),
				'db_tls_ca' => $this->oWizard->GetParameter('db_tls_ca'),
				'prefix' => $this->oWizard->GetParameter('db_prefix'),
			],
			'url' => $this->oWizard->GetParameter('application_url'),
			'graphviz_path' => $this->oWizard->GetParameter('graphviz_path'),
			'admin_account' =>  [
				'user' => $this->oWizard->GetParameter('admin_user'),
				'pwd' => $this->oWizard->GetParameter('admin_pwd'),
				'language' => $this->oWizard->GetParameter('admin_language'),
			],
			'language' => $this->oWizard->GetParameter('default_language'),
			'selected_modules' =>  $aSelectedModules,
			'selected_extensions' =>  $aSelectedExtensions,
			'sample_data' => $this->oWizard->GetParameter('sample_data', '') === 'yes',
			'old_addon' => $this->oWizard->GetParameter('old_addon', false), // whether or not to use the "old" userrights profile addon
			'options' => json_decode($this->oWizard->GetParameter('misc_options', '[]'), true),
			'mysql_bindir' => $this->oWizard->GetParameter('mysql_bindir'),
			'use-symbolic-links' => $this->oWizard->GetParameter('use-symbolic-links', MFCompiler::UseSymbolicLinks()),
		];

		if ($sBackupDestination != '') {
			$aInstallParams['optional_steps']['backup'] =  [
				'destination' => $sBackupDestination,
				'configuration_file' => $sPreviousConfigurationFile,
			];
		}

		return $aInstallParams;
	}

}

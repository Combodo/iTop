<?php

abstract class AbstractWizStepInstall extends WizardStep {
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
		$aCopies = [];
		if (($sMode == 'upgrade') && ($this->oWizard->GetParameter('upgrade_type') == 'keep-previous')) {
			$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir');
			$aCopies[] = ['source' => $sSourceDir, 'destination' => 'modules']; // Source is an absolute path, destination is relative to APPROOT
			$aCopies[] = ['source' => $sPreviousVersionDir.'/portal', 'destination' => 'portal']; // Source is an absolute path, destination is relative to APPROOT
			$sSourceDir = APPROOT.'modules';
		}

		$aInstallParams =  [
			'mode' => $sMode,
			'preinstall' =>  [
				'copies' => $aCopies,
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
			'sample_data' => ($this->oWizard->GetParameter('sample_data', '') == 'yes') ? true : false ,
			'old_addon' => $this->oWizard->GetParameter('old_addon', false), // whether or not to use the "old" userrights profile addon
			'options' => json_decode($this->oWizard->GetParameter('misc_options', '[]'), true),
			'mysql_bindir' => $this->oWizard->GetParameter('mysql_bindir'),
		];

		if ($sBackupDestination != '') {
			$aInstallParams['preinstall']['backup'] =  [
				'destination' => $sBackupDestination,
				'configuration_file' => $sPreviousConfigurationFile,
			];
		}

		return $aInstallParams;
	}

}
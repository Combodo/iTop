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
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Second step of the iTop Installation Wizard: Install or Upgrade
 */
class WizStepInstallOrUpgrade extends WizardStep
{
	public function GetTitle()
	{
		return 'Install or Upgrade choice';
	}

	public function GetPossibleSteps()
	{
		return [WizStepDetectedInfo::class, WizStepLicense::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		$sNextStep = '';
		$sInstallMode = utils::ReadParam('install_mode');

		$this->oWizard->SaveParameter('previous_version_dir', '');
		$this->oWizard->SaveParameter('db_server', '');
		$this->oWizard->SaveParameter('db_user', '');
		$this->oWizard->SaveParameter('db_pwd', '');
		$this->oWizard->SaveParameter('db_name', '');
		$this->oWizard->SaveParameter('db_prefix', '');
		$this->oWizard->SaveParameter('db_tls_enabled', false);
		$this->oWizard->SaveParameter('db_tls_ca', '');

		if ($sInstallMode == 'install') {
			$this->oWizard->SetParameter('install_mode', 'install');
			$sFullSourceDir = SetupUtils::GetLatestDataModelDir();
			$this->oWizard->SetParameter('source_dir', $sFullSourceDir);
			$this->oWizard->SetParameter('datamodel_version', SetupUtils::GetDataModelVersion($sFullSourceDir));
			$sNextStep = WizStepLicense::class;
		} else {
			$this->oWizard->SetParameter('install_mode', 'upgrade');
			$sNextStep = WizStepDetectedInfo::class;

		}
		return new WizardState($sNextStep);
	}

	public function Display(WebPage $oPage)
	{
		$sInstallMode = $this->oWizard->GetParameter('install_mode', '');
		$sDBServer = $this->oWizard->GetParameter('db_server', '');
		$sDBUser = $this->oWizard->GetParameter('db_user', '');
		$sDBPwd = $this->oWizard->GetParameter('db_pwd', '');
		$sDBName = $this->oWizard->GetParameter('db_name', '');
		$sDBPrefix = $this->oWizard->GetParameter('db_prefix', '');
		$sTlsEnabled = $this->oWizard->GetParameter('db_tls_enabled', false);
		$sTlsCA = $this->oWizard->GetParameter('db_tls_ca', '');
		$sPreviousVersionDir = '';
		if ($sInstallMode == '') {
			$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
			if ($aPreviousInstance['found']) {
				$sInstallMode = 'upgrade';
				$sDBServer = $aPreviousInstance['db_server'];
				$sDBUser = $aPreviousInstance['db_user'];
				$sDBPwd = $aPreviousInstance['db_pwd'];
				$sDBName = $aPreviousInstance['db_name'];
				$sDBPrefix = $aPreviousInstance['db_prefix'];
				$sTlsEnabled = $aPreviousInstance['db_tls_enabled'];
				$sTlsCA = $aPreviousInstance['db_tls_ca'];
				$this->oWizard->SaveParameter('graphviz_path', $aPreviousInstance['graphviz_path']);
				$sPreviousVersionDir = APPROOT;
			} else {
				$sInstallMode = 'install';
			}
		}
		$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir', $sPreviousVersionDir);

		$sUpgradeInfoStyle = '';
		if ($sInstallMode == 'install') {
			$sUpgradeInfoStyle = ' style="display: none;" ';
		}
		$oPage->add('<div class="setup-content-title">What do you want to do?</div>');
		$sChecked = ($sInstallMode == 'install') ? ' checked ' : '';
		$oPage->p('<input id="radio_install" type="radio" name="install_mode" value="install" '.$sChecked.'/><label for="radio_install">&nbsp;Install a new '.ITOP_APPLICATION.'</label>');
		$sChecked = ($sInstallMode == 'upgrade') ? ' checked ' : '';
		$sDisabled = (($sInstallMode == 'install') && (empty($sPreviousVersionDir))) ? ' disabled' : '';
		$oPage->p('<input id="radio_update" type="radio" name="install_mode" value="upgrade" '.$sChecked.$sDisabled.'/><label for="radio_update">&nbsp;Upgrade an existing '.ITOP_APPLICATION.' instance</label>');

		$sUpgradeDir = utils::HtmlEntities($sPreviousVersionDir);
		$oPage->add(
			<<<HTML
<div id="upgrade_info"'.$sUpgradeInfoStyle.'>
		<div class="setup-disk-location--input--container">Location on the disk:<input id="previous_version_dir_display" type="text" value="$sUpgradeDir" class="ibo-input" disabled>
		<input type="hidden" name="previous_version_dir" value="$sUpgradeDir"></div>
HTML
		);

		SetupUtils::DisplayDBParameters(
			$oPage,
			false,
			$sDBServer,
			$sDBUser,
			$sDBPwd,
			$sDBName,
			$sDBPrefix,
			$sTlsEnabled,
			$sTlsCA,
			null
		);

		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('</div>');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		//$oPage->add('</fieldset>');
		$oPage->add_ready_script(
			<<<JS
	$("#radio_update").on('change', function() { if (this.checked ) { $('#upgrade_info').show(); WizardUpdateButtons(); } else { $('#upgrade_info').hide(); } });
	$("#radio_install").on('change', function() { if (this.checked ) { $('#upgrade_info').hide(); WizardUpdateButtons(); } else { $('#upgrade_info').show(); } });
JS
		);
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch ($sCode) {
			case 'check_path':
				$sPreviousVersionDir = $aParameters['previous_version_dir'];
				$aPreviousInstance = SetupUtils::GetPreviousInstance($sPreviousVersionDir);
				if ($aPreviousInstance['found']) {
					$sDBServer = utils::EscapeHtml($aPreviousInstance['db_server']);
					$sDBUser = utils::EscapeHtml($aPreviousInstance['db_user']);
					$sDBPwd = utils::EscapeHtml($aPreviousInstance['db_pwd']);
					$sDBName = utils::EscapeHtml($aPreviousInstance['db_name']);
					$sDBPrefix = utils::EscapeHtml($aPreviousInstance['db_prefix']);
					$oPage->add_ready_script(
						<<<EOF
	$("#db_server").val('$sDBServer');
	$("#db_user").val('$sDBUser');
	$("#db_pwd").val('$sDBPwd');
	$("#db_name").val('$sDBName');
	$("#db_prefix").val('$sDBPrefix');
	$("#db_pwd").trigger('change'); // Forces check of the DB connection
EOF
					);
				}
				break;

			case 'check_db':
				SetupUtils::AsyncCheckDB($oPage, $aParameters);
				break;

			case 'check_backup':
				$sDBBackupPath = $aParameters['db_backup_path'];
				$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
				if ($fFreeSpace !== false) {
					$sMessage = utils::EscapeHtml(SetupUtils::HumanReadableSize($fFreeSpace).' free in '.dirname($sDBBackupPath));
					$oPage->add_ready_script(
						<<<EOF
	$("#backup_info").html('$sMessage');
EOF
					);
				} else {
					$oPage->add_ready_script(
						<<<EOF
	$("#backup_info").html('');
EOF
					);
				}
				break;
		}
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return
			<<<EOF
		if ($("#radio_install").prop("checked"))
		{
			ValidateField("db_name", false);
			ValidateField("db_new_name", false);
			ValidateField("db_prefix", false);
			return true;
		}
		else
		{
			var bRet = ($("#wiz_form").data("db_connection") !== "error");
			bRet = ValidateField("db_name", true) && bRet;
			bRet = ValidateField("db_new_name", true) && bRet;
			bRet = ValidateField("db_prefix", true) && bRet;
	
			return bRet;
		}
EOF
		;
	}

}

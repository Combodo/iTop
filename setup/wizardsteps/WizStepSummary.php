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
 * Summary of the installation tasks
 */
class WizStepSummary extends AbstractWizStepInstall
{
	public function GetTitle()
	{
		$sMode = $this->oWizard->GetParameter('mode', 'install');
		if ($sMode == 'install') {
			return 'Ready to install';

		} else {
			return 'Ready to upgrade';
		}
	}

	public function GetPossibleSteps()
	{
		return [WizStepInstall::class];
	}

	/**
	 * Returns the label for the " Next >> " button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return 'Install';
	}

	public function CanMoveForward()
	{
		if ($this->CheckDependencies()) {
			return true;
		} else {
			return false;
		}
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		$this->oWizard->SaveParameter('db_backup', false);
		$this->oWizard->SaveParameter('db_backup_path', '');
		return new WizardState(WizStepInstall::class);
	}

	public function Display(WebPage $oPage)
	{

		$aInstallParams = $this->BuildConfig();

		$sMode = $aInstallParams['mode'];

		$sDestination = ITOP_APPLICATION.(($sMode == 'install') ? ' version '.ITOP_VERSION.' is about to be installed ' : ' is about to be upgraded ');
		$sDBDescription = ' <b>existing</b> database <b>'.$aInstallParams['database']['name'].'</b>';
		if (($sMode == 'install') && ($this->oWizard->GetParameter('create_db') == 'yes')) {
			$sDBDescription = ' <b>new</b> database <b>'.$aInstallParams['database']['name'].'</b>';
		}
		$sDestination .= 'into the '.$sDBDescription.' on the server <b>'.$aInstallParams['database']['server'].'</b>.';
		$oPage->add('<h2>'.$sDestination.'</h2>');

		$oPage->add('<fieldset id="summary"><legend>Installation Parameters</legend>');
		$oPage->add('<div id="params_summary">');

		$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Extensions to be installed">Extensions to be installed</a>');
		$aExtensionsAdded = json_decode($this->oWizard->GetParameter('extensions_added'), true);

		if (count($aExtensionsAdded) > 0) {
			$sExtensionsAdded = '<ul>';
			foreach ($aExtensionsAdded as $sExtensionCode => $sLabel) {
				$sExtensionsAdded .= '<li>'.$sLabel.'</li>';
			}
			$sExtensionsAdded .= '</ul>';
		} else {
			$sExtensionsAdded = '<ul><li>No extension added.</li></ul>';
		}
		$oPage->add($sExtensionsAdded);
		$oPage->add('</div>');
		$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Extensions to be uninstalled">Extensions to be uninstalled</a>');

		$aExtensionsRemoved = json_decode($this->oWizard->GetParameter('removed_extensions'), true) ?? [];
		$aExtensionsNotUninstallable = json_decode($this->oWizard->GetParameter('extensions_not_uninstallable'));
		if (count($aExtensionsRemoved) > 0) {
			$sExtensionsRemoved = '<ul>';
			foreach ($aExtensionsRemoved as $sExtensionCode => $sLabel) {
				if (in_array($sExtensionCode, $aExtensionsNotUninstallable)) {
					$sExtensionsRemoved .= '<li>'.$sLabel.' (forced uninstallation)</li>';
				} else {
					$sExtensionsRemoved .= '<li>'.$sLabel.'</li>';
				}
			}
			$sExtensionsRemoved .= '</ul>';
		} else {
			$sExtensionsRemoved = '<ul><li>No extension removed.</li></ul>';
		}
		$oPage->add($sExtensionsRemoved);
		$oPage->add('</div>');

		$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Database Parameters">Database Parameters</a><ul>');
		$oPage->add('<li>Server Name: '.$aInstallParams['database']['server'].'</li>');
		$oPage->add('<li>DB User Name: '.$aInstallParams['database']['user'].'</li>');
		$oPage->add('<li>DB user password: ***</li>');
		if (($sMode == 'install') && ($this->oWizard->GetParameter('create_db') == 'yes')) {
			$oPage->add('<li>Database Name: '.$aInstallParams['database']['name'].' (will be created)</li>');
		} else {
			$oPage->add('<li>Database Name: '.$aInstallParams['database']['name'].'</li>');
		}
		if ($aInstallParams['database']['prefix'] != '') {
			$oPage->add('<li>Prefix for the '.ITOP_APPLICATION.' tables: '.$aInstallParams['database']['prefix'].'</li>');
		} else {
			$oPage->add('<li>Prefix for the '.ITOP_APPLICATION.' tables: none</li>');
		}
		$oPage->add('</ul></div>');

		$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Data Model Configuration">Data Model Configuration</a>');
		$oPage->add($this->oWizard->GetParameter('display_choices'));
		$oPage->add('</div>');

		$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Other Parameters">Other Parameters</a><ul>');
		if ($sMode == 'install') {
			$oPage->add('<li>Default language: '.$aInstallParams['language'].'</li>');
		}

		$oPage->add('<li>URL to access the application: '.$aInstallParams['url'].'</li>');
		$oPage->add('<li>Graphviz\' dot path: '.$aInstallParams['graphviz_path'].'</li>');
		if ($aInstallParams['sample_data']) {
			$oPage->add('<li>Sample data will be loaded into the database.</li>');
		}
		if ($aInstallParams['old_addon']) {
			$oPage->add('<li>Compatibility mode: Using the version 1.2 of the UserRightsProfiles add-on.</li>');
		}
		$oPage->add('</ul></div>');

		if ($sMode == 'install') {
			$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title" aria-label="Administrator Account">Administrator Account</a><ul>');
			$oPage->add('<li>Login: '.$aInstallParams['admin_account']['user'].'</li>');
			$oPage->add('<li>Password: '.$aInstallParams['admin_account']['pwd'].'</li>');
			$oPage->add('<li>Language: '.$aInstallParams['admin_account']['language'].'</li>');
			$oPage->add('</ul></div>');
		}

		$aMiscOptions = $aInstallParams['options'];
		if (count($aMiscOptions) > 0) {
			$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title">Miscellaneous Options</a><ul>');
			foreach ($aMiscOptions as $sKey => $sValue) {
				$oPage->add('<li>'.$sKey.': '.$sValue.'</li>');
			}
			$oPage->add('</ul></div>');

		}

		if (isset($aMiscOptions['generate_config'])) {
			$oDoc = new DOMDocument('1.0', 'UTF-8');
			$oDoc->preserveWhiteSpace = false;
			$oDoc->formatOutput = true;
			$oParams = new PHPParameters();
			$oParams->LoadFromHash($aInstallParams);
			$oParams->ToXML($oDoc, null, 'installation');
			$sXML = $oDoc->saveXML();
			$oPage->add('<div class="closed"><a class="title ibo-setup-summary-title">XML Config file</a><ul><pre>');
			$oPage->add(utils::EscapeHtml($sXML));
			$oPage->add('</pre></ul></div>');
		}

		$oPage->add('</div>'); // params_summary
		$oPage->add('</fieldset>');

		if (!$this->CheckDependencies()) {
			$oPage->error($this->sDependencyIssue);
		}

		if ($sMode !== 'install') {
			$bDBBackup = $this->oWizard->GetParameter('db_backup', false);
			$sDefaultBackupPath = utils::GetDataPath().'backups/manual/setup-'.date('Y-m-d_H_i');
			$sDBBackupPath = $this->oWizard->GetParameter('db_backup_path', $sDefaultBackupPath);
			$sMySQLBinDir = $this->oWizard->GetParameter('mysql_bindir', null);
			$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
			if ($aPreviousInstance['found']) {
				$sMySQLBinDir = $aPreviousInstance['mysql_bindir'];
				$this->oWizard->SaveParameter('mysql_bindir', $aPreviousInstance['mysql_bindir']);
			}
			$aBackupChecks = SetupUtils::CheckBackupPrerequisites($sDBBackupPath, $sMySQLBinDir);
			$bCanBackup = true;
			$sMySQLDumpMessage = '';
			foreach ($aBackupChecks as $oCheck) {
				switch ($oCheck->iSeverity) {
					case CheckResult::ERROR:
						$bCanBackup = false;
						$sMySQLDumpMessage .= '<div class="message message-error"><span class="message-title">Error:</span>'.$oCheck->sLabel.'</div>';
						break;
					case CheckResult::TRACE:
						SetupLog::Ok($oCheck->sLabel);
						break;
					default:
						$sMySQLDumpMessage .= '<div class="message message-valid"><span class="message-title">Success:</span>'.$oCheck->sLabel.'</div>';
						break;
				}
			}
			$sChecked = ($bCanBackup && $bDBBackup) ? ' checked ' : '';
			$sDisabled = $bCanBackup ? '' : ' disabled ';
			$oPage->add('<br/>');
			$oPage->add('<input id="db_backup" type="checkbox" name="db_backup" '.$sChecked.$sDisabled.' value="1"/><label for="db_backup">Backup the '.ITOP_APPLICATION.' database before upgrading</label>');
			$oPage->add('<div class="setup-backup--input--container">Save the backup to:<input id="db_backup_path" class="ibo-input" type="text" name="db_backup_path" '.$sDisabled.'value="'.utils::EscapeHtml($sDBBackupPath).'"/></div>');
			$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
			$sMessage = '';
			if ($fFreeSpace !== false) {
				$sMessage .= SetupUtils::HumanReadableSize($fFreeSpace).' free in '.dirname($sDBBackupPath);
			}
			$oPage->add($sMySQLDumpMessage.'<span id="backup_info" style="font-size:small;color:#696969;">'.$sMessage.'</span>');

		}

		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');

		$oPage->add_ready_script(
			<<<JS
	$("#db_backup_path").on('change keyup', function() { WizardAsyncAction('check_backup', { db_backup_path: $('#db_backup_path').val() }); });
	$("#params_summary div").addClass('closed');
	$("#params_summary .title").on('click', function() { $(this).parent().toggleClass('closed'); } );
JS
		);
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return true;';
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveBackward()
	{
		return 'return true;';
	}

}

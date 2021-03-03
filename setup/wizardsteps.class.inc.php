<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

/**
 * All the steps of the iTop installation wizard
 *
 * Steps order (can be retrieved using \WizardController::DumpStructure) :
 *
 * WizStepWelcome
 * WizStepInstallOrUpgrade
 *    +             +
 *    |             |
 *    v             +----->
 * WizStepLicense          WizStepDetectedInfo
 * WizStepDBParams           +              +
 * WizStepAdminAccount       |              |
 * WizStepMiscParams         v              +------>
 *    +                    WizStepLicense2 +--> WizStepUpgradeMiscParams
 *    |                                            +
 *    +--->    <-----------------------------------+
 * WizStepModulesChoice
 * WizStepSummary
 * WizStepDone
 */

require_once(APPROOT.'setup/setuputils.class.inc.php');
require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/applicationinstaller.class.inc.php');
require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'core/mutex.class.inc.php');
require_once(APPROOT.'setup/extensionsmap.class.inc.php');

/**
 * First step of the iTop Installation Wizard: Welcome screen, requirements
 */
class WizStepWelcome extends WizardStep
{
	protected $bCanMoveForward;

	public function GetTitle()
	{
		return 'Welcome to '.ITOP_APPLICATION.' version '.ITOP_VERSION;
	}

	/**
	 * Returns the label for the " Next >> " button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return ' Continue >> ';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepInstallOrUpgrade');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sUID = SetupUtils::CreateSetupToken();
		$this->oWizard->SetParameter('authent', $sUID);
		return array('class' => 'WizStepInstallOrUpgrade', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		// Store the misc_options for the future...
		$aMiscOptions = utils::ReadParam('option', array(), false, 'raw_data');
		$sMiscOptions = $this->oWizard->GetParameter('misc_options', json_encode($aMiscOptions));
		$this->oWizard->SetParameter('misc_options', $sMiscOptions);

		$oPage->add("<!--[if lt IE 11]><div id=\"old_ie\"></div><![endif]-->");
		$oPage->add_ready_script(
<<<EOF
		if ($('#old_ie').length > 0)
		{
			alert("Internet Explorer version 10 or older is NOT supported! (Check that IE is not running in compatibility mode)");
		}
EOF
		);
		$oPage->add('<h1>'.ITOP_APPLICATION.' Installation Wizard</h1>');
		$aResults = SetupUtils::CheckPhpAndExtensions();
		$this->bCanMoveForward = true;
		$aInfo = array();
		$aWarnings = array();
		$aErrors = array();
		foreach($aResults as $oCheckResult)
		{
			switch($oCheckResult->iSeverity)
			{
				case CheckResult::ERROR:
				$aErrors[] = $oCheckResult->sLabel;
				$this->bCanMoveForward = false;
				break;

				case CheckResult::WARNING:
				$aWarnings[] = $oCheckResult->sLabel;
				break;

				case CheckResult::INFO:
				$aInfo[] = $oCheckResult->sLabel;
				break;
			}
		}
		$sStyle = 'style="display:none;max-height:196px;overflow:auto;"';
		$sToggleButtons = '<button type="button" id="show_details" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#hide_details\').toggle();">Show details</button><button type="button" id="hide_details" style="display:none;" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#show_details\').toggle();">Hide details</button>';
		if (count($aErrors)> 0)
		{
			$sStyle = 'style="max-height:196px;overflow:auto;"';
			$sTitle = count($aErrors).' Error(s), '.count($aWarnings).' Warning(s).';
			$sH2Class = 'text-error';
		}
		else if (count($aWarnings)> 0)
		{
			$sTitle = count($aWarnings).' Warning(s) '.$sToggleButtons;
			$sH2Class = 'text-warning';
		}
		else
		{
			$sTitle = 'Ok. '.$sToggleButtons;
			$sH2Class = 'text-valid';
		}
		$oPage->add(
<<<HTML
		<h2 class="message">Prerequisites validation: <span class="$sH2Class">$sTitle</span></h2>
		<div id="details" $sStyle>
HTML
		);
		foreach($aErrors as $sText)
		{
			$oPage->error($sText);
		}
		foreach($aWarnings as $sText)
		{
			$oPage->warning($sText);
		}
		foreach($aInfo as $sText)
		{
			$oPage->ok($sText);
		}
		$oPage->add('</div>');
		if (!$this->bCanMoveForward)
		{
			$oPage->p('Sorry, the installation cannot continue. Please fix the errors and reload this page to launch the installation again.');
			$oPage->p('<button type="button" onclick="window.location.reload()">Reload</button>');
		}
	}

	public function CanMoveForward()
	{
		return $this->bCanMoveForward;
	}
}

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
		return array('WizStepDetectedInfo', 'WizStepLicense');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sNextStep = '';
		$sInstallMode = utils::ReadParam('install_mode');

		$this->oWizard->SaveParameter('previous_version_dir', '');
		$this->oWizard->SaveParameter('db_server', '');
		$this->oWizard->SaveParameter('db_user', '');
		$this->oWizard->SaveParameter('db_pwd', '');
		$this->oWizard->SaveParameter('db_name', '');
		$this->oWizard->SaveParameter('db_prefix', '');
		$this->oWizard->SaveParameter('db_backup', false);
		$this->oWizard->SaveParameter('db_backup_path', '');
		$this->oWizard->SaveParameter('db_tls_enabled', false);
		$this->oWizard->SaveParameter('db_tls_ca', '');

		if ($sInstallMode == 'install')
		{
			$this->oWizard->SetParameter('install_mode', 'install');
			$sFullSourceDir = SetupUtils::GetLatestDataModelDir();
			$this->oWizard->SetParameter('source_dir', $sFullSourceDir);
			$this->oWizard->SetParameter('datamodel_version', SetupUtils::GetDataModelVersion($sFullSourceDir));
			$sNextStep = 'WizStepLicense';
		}
		else
		{
			$this->oWizard->SetParameter('install_mode', 'upgrade');
			$sNextStep = 'WizStepDetectedInfo';

		}
		return array('class' => $sNextStep, 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$sInstallMode = $this->oWizard->GetParameter('install_mode', '');
		$sDBServer = $this->oWizard->GetParameter('db_server', '');
		$sDBUser = $this->oWizard->GetParameter('db_user', '');
		$sDBPwd = $this->oWizard->GetParameter('db_pwd', '');
		$sDBName = $this->oWizard->GetParameter('db_name', '');
		$sDBPrefix = $this->oWizard->GetParameter('db_prefix', '');
		$bDBBackup = $this->oWizard->GetParameter('db_backup', false);
		$sDBBackupPath = $this->oWizard->GetParameter('db_backup_path', '');
		$sTlsEnabled = $this->oWizard->GetParameter('db_tls_enabled', false);
		$sTlsCA = $this->oWizard->GetParameter('db_tls_ca', '');
		$sMySQLBinDir = $this->oWizard->GetParameter('mysql_bindir', null);
		$sPreviousVersionDir = '';
		if ($sInstallMode == '')
		{
			$sDBBackupPath = APPROOT.strftime('data/backups/manual/setup-%Y-%m-%d_%H_%M');
			$bDBBackup = true;
			$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
			if ($aPreviousInstance['found'])
			{
				$sInstallMode = 'upgrade';
				$sDBServer = $aPreviousInstance['db_server'];
				$sDBUser = $aPreviousInstance['db_user'];
				$sDBPwd = $aPreviousInstance['db_pwd'];
				$sDBName = $aPreviousInstance['db_name'];
				$sDBPrefix = $aPreviousInstance['db_prefix'];
				$sTlsEnabled = $aPreviousInstance['db_tls_enabled'];
				$sTlsCA = $aPreviousInstance['db_tls_ca'];
				$this->oWizard->SaveParameter('graphviz_path', $aPreviousInstance['graphviz_path']);
				$sMySQLBinDir = $aPreviousInstance['mysql_bindir'];
				$this->oWizard->SaveParameter('mysql_bindir', $aPreviousInstance['mysql_bindir']);
				$sPreviousVersionDir = APPROOT;
			}
			else
			{
				$sInstallMode = 'install';
			}
		}
		$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir', $sPreviousVersionDir);

		$sUpgradeInfoStyle = '';
		if ($sInstallMode == 'install')
		{
			$sUpgradeInfoStyle = ' style="display: none;" ';
		}
		$oPage->add('<h2>What do you want to do?</h2>');
		$sChecked = ($sInstallMode == 'install') ? ' checked ' : '';
        $oPage->p('<input id="radio_install" type="radio" name="install_mode" value="install" '.$sChecked.'/><label for="radio_install">&nbsp;Install a new '.ITOP_APPLICATION.'</label>');
		$sChecked = ($sInstallMode == 'upgrade') ? ' checked ' : '';
		$sDisabled = (($sInstallMode == 'install') && (empty($sPreviousVersionDir))) ? ' disabled' : '';
        $oPage->p('<input id="radio_update" type="radio" name="install_mode" value="upgrade" '.$sChecked.$sDisabled.'/><label for="radio_update">&nbsp;Upgrade an existing '.ITOP_APPLICATION.' instance</label>');

        $sUpgradeDir = utils::HtmlEntities($sPreviousVersionDir);
		$oPage->add(<<<HTML
<table id="upgrade_info"'.$sUpgradeInfoStyle.'>
	<tr>
		<td>Location on the disk:</td>
		<td><input id="previous_version_dir_display" type="text" value="$sUpgradeDir" style="width: 98%;" disabled>
		<input type="hidden" name="previous_version_dir" value="$sUpgradeDir"></td>
	</tr>
HTML
		);

		SetupUtils::DisplayDBParameters($oPage, false, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix,
			$sTlsEnabled, $sTlsCA, null);

		$aBackupChecks = SetupUtils::CheckBackupPrerequisites($sDBBackupPath, $sMySQLBinDir);
		$bCanBackup = true;
		$sMySQLDumpMessage = '';
		foreach($aBackupChecks as $oCheck)
		{
			if ($oCheck->iSeverity == CheckResult::ERROR)
			{
				$bCanBackup = false;
				$sMySQLDumpMessage .= '<div class="message message-error"><span class="message-title">Error:</span>'.$oCheck->sLabel.'</div>';
			}
			else
			{
				$sMySQLDumpMessage .= '<div class="message message-valid"><span class="message-title">Success:</span>'.$oCheck->sLabel.'</div>';
			}
		}
		$sChecked = ($bCanBackup && $bDBBackup) ? ' checked ' : '';
		$sDisabled = $bCanBackup ? '' : ' disabled ';
        $oPage->add('<tr><td colspan="2"><input id="db_backup" type="checkbox" name="db_backup" '.$sChecked.$sDisabled.' value="1"/><label for="db_backup">&nbsp;Backup the '.ITOP_APPLICATION.' database before upgrading</label></td></tr>');
		$oPage->add('<tr><td style="width: 8rem; text-align: right;">Save the backup to:</td><td><input id="db_backup_path" type="text" name="db_backup_path" 
'.$sDisabled.'value="'
			.htmlentities($sDBBackupPath, ENT_QUOTES, 'UTF-8').'" style="width: 98%;"/></td></tr>');
		$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
		$sMessage = '';
		if ($fFreeSpace !== false)
		{
			$sMessage .= SetupUtils::HumanReadableSize($fFreeSpace).' free in '.dirname($sDBBackupPath);
		}
		$oPage->add('<tr><td colspan="2">');
		$oPage->add($sMySQLDumpMessage.'<br/><span id="backup_info" style="font-size:small;color:#696969;">'.$sMessage.'</span></td></tr>');
		$oPage->add('</table>');
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		//$oPage->add('</fieldset>');
		$oPage->add_ready_script(
<<<JS
	$("#radio_update").bind('change', function() { if (this.checked ) { $('#upgrade_info').show(); WizardUpdateButtons(); } else { $('#upgrade_info').hide(); } });
	$("#radio_install").bind('change', function() { if (this.checked ) { $('#upgrade_info').hide(); WizardUpdateButtons(); } else { $('#upgrade_info').show(); } });
	$("#db_backup_path").bind('change keyup', function() { WizardAsyncAction('check_backup', { db_backup_path: $('#db_backup_path').val() }); });
JS
		);
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch($sCode)
		{
			case 'check_path':
			$sPreviousVersionDir = $aParameters['previous_version_dir'];
			$aPreviousInstance = SetupUtils::GetPreviousInstance($sPreviousVersionDir);
			if ($aPreviousInstance['found'])
			{
				$sDBServer = htmlentities($aPreviousInstance['db_server'], ENT_QUOTES, 'UTF-8');
				$sDBUser = htmlentities($aPreviousInstance['db_user'], ENT_QUOTES, 'UTF-8');
				$sDBPwd = htmlentities($aPreviousInstance['db_pwd'], ENT_QUOTES, 'UTF-8');
				$sDBName = htmlentities($aPreviousInstance['db_name'], ENT_QUOTES, 'UTF-8');
				$sDBPrefix = htmlentities($aPreviousInstance['db_prefix'], ENT_QUOTES, 'UTF-8');
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
			SetupUtils:: AsyncCheckDB($oPage, $aParameters);
			break;

			case 'check_backup':
			$sDBBackupPath = $aParameters['db_backup_path'];
			$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
			if ($fFreeSpace !== false)
			{
				$sMessage = htmlentities(SetupUtils::HumanReadableSize($fFreeSpace).' free in '.dirname($sDBBackupPath), ENT_QUOTES, 'UTF-8');
				$oPage->add_ready_script(
<<<EOF
	$("#backup_info").html('$sMessage');
EOF
				);
			}
			else
			{
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

/**
 * Upgrade information
 */
class WizStepDetectedInfo extends WizardStep
{
	protected $bCanMoveForward;

	public function GetTitle()
	{
		return 'Upgrade Information';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeMiscParams', 'WizStepLicense2');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sUpgradeType = utils::ReadParam('upgrade_type');

		$this->oWizard->SetParameter('mode', 'upgrade');
		$this->oWizard->SetParameter('upgrade_type', $sUpgradeType);
		$bDisplayLicense = $this->oWizard->GetParameter('display_license');

		switch ($sUpgradeType)
		{
			case 'keep-previous':
			$sSourceDir = utils::ReadParam('relative_source_dir', '', false, 'raw_data');
			$this->oWizard->SetParameter('source_dir', $this->oWizard->GetParameter('previous_version_dir').'/'.$sSourceDir);
			$this->oWizard->SetParameter('datamodel_version', utils::ReadParam('datamodel_previous_version', '', false, 'raw_data'));
			break;

			case 'use-compatible':
			$sDataModelPath = utils::ReadParam('datamodel_path', '', false, 'raw_data');
			$this->oWizard->SetParameter('source_dir', $sDataModelPath);
			$this->oWizard->SaveParameter('datamodel_version', '');
			break;

			default:
			// Do nothing, maybe the user pressed the Back button
		}
		if ($bDisplayLicense)
		{
			$aRet = array('class' => 'WizStepLicense2', 'state' => '');
		}
		else
		{
			$aRet = array('class' => 'WizStepUpgradeMiscParams', 'state' => '');
		}
		return $aRet;
	}

	/**
	 * @param \WebPage $oPage
	 *
	 * @throws \Exception
	 */
	public function Display(\WebPage $oPage)
	{
		$oPage->add_style(
<<<EOF
#changes_summary {
	max-height: 200px;
	overflow: auto;
}
#changes_summary div {
	width:100;
	margin-top:0;
	padding-top: 0.5em;
	padding-left: 0;
}
#changes_summary div ul {
	margin-left:0;
	padding-left: 20px;
}
#changes_summary div.closed ul {
	display:none;
}
#changes_summary div li {
	list-style: none;
	width: 100;
	margin-left:0;
	padding-left: 0em;
}
.title {
	padding-left: 20px;
	font-weight: bold;
	cursor: pointer;
	background: url(../images/minus.gif) 2px 2px no-repeat;
}
#changes_summary div.closed .title {
	background: url(../images/plus.gif) 2px 2px no-repeat;
}
EOF
		);
		$this->bCanMoveForward = true;
		$bDisplayLicense = true;
		$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir', '');
		$aInstalledInfo = SetupUtils::GetApplicationVersion($this->oWizard);

		if ($aInstalledInfo === false)
		{
			throw(new Exception('No previous version of '.ITOP_APPLICATION.' found in the supplied database. The upgrade cannot continue.'));
		}
		else if (strcasecmp($aInstalledInfo['product_name'], ITOP_APPLICATION) != 0)
		{
			$oPage->p("<b>Warning: The installed products seem different. Are you sure that you want to upgrade {$aInstalledInfo['product_name']} with ".ITOP_APPLICATION."?</b>");
		}

		$sInstalledVersion = $aInstalledInfo['product_version'];
		$sInstalledDataModelVersion = $aInstalledInfo['datamodel_version'];

		$oPage->add("<h2>Information about the upgrade from version $sInstalledVersion to ".ITOP_VERSION_FULL."</h2>");

		if ($sInstalledVersion == ITOP_VERSION_FULL)
		{
			// Reinstalling the same version let's skip the license agreement...
			$bDisplayLicense = false;
		}
		$this->oWizard->SetParameter('license', $bDisplayLicense); // Remember for later

		if ($sInstalledDataModelVersion == '$ITOP_VERSION$.$WCREV$')
		{
			// Special case for upgrading some  development versions (temporary)
			$sCompatibleDMDir = SetupUtils::GetLatestDataModelDir();
			$sInstalledDataModelVersion = SetupUtils::GetDataModelVersion($sCompatibleDMDir);
		}
		else
		{
			$sCompatibleDMDir = SetupUtils::GetCompatibleDataModelDir($sInstalledDataModelVersion);
		}

		if ($sCompatibleDMDir === false)
		{
			// No compatible version exists... cannot upgrade. Either it is too old, or too new (downgrade !)
			$this->bCanMoveForward = false;
			$oPage->p("The current version of ".ITOP_APPLICATION." (".ITOP_VERSION_FULL.") does not seem to be compatible with the installed version ($sInstalledVersion).");
			$oPage->p("The upgrade cannot continue, sorry.");
		}
		else
		{
			$sUpgradeDMVersion = SetupUtils::GetDataModelVersion($sCompatibleDMDir);
			$sPreviousSourceDir = isset($aInstalledInfo['source_dir']) ? $aInstalledInfo['source_dir'] : 'modules';
			$aChanges = false;
			if (is_dir($sPreviousVersionDir))
			{
				// Check if the previous version is a "genuine" one or not...
				$aChanges = SetupUtils::CheckVersion($sInstalledDataModelVersion, $sPreviousVersionDir.'/'.$sPreviousSourceDir);
			}
			if (($aChanges !== false) && ( (count($aChanges['added']) > 0) || (count($aChanges['removed']) > 0) || (count($aChanges['modified']) > 0)) )
			{
				// Some changes were detected, prompt the user to keep or discard them
				$oPage->p("<img src=\"../images/error.png\"/>&nbsp;Some modifications were detected between the ".ITOP_APPLICATION." version in '$sPreviousVersionDir' and a genuine $sInstalledVersion version.");
				$oPage->p("What do you want to do?");

				$aWritableDirs = array('modules', 'portal');
				$aErrors = SetupUtils::CheckWritableDirs($aWritableDirs);
				$sChecked = ($this->oWizard->GetParameter('upgrade_type') == 'keep-previous') ? ' checked ' : '';
				$sDisabled = (count($aErrors) > 0) ? ' disabled ' : '';

                $oPage->p('<input id="radio_upgrade_keep" type="radio" name="upgrade_type" value="keep-previous" '.$sChecked.$sDisabled.'/><label for="radio_upgrade_keep">&nbsp;Preserve the modifications of the installed version (the dasboards inside '.ITOP_APPLICATION.' may not be editable).</label>');
				$oPage->add('<input type="hidden" name="datamodel_previous_version" value="'.htmlentities($sInstalledDataModelVersion, ENT_QUOTES, 'UTF-8').'">');

				$oPage->add('<input type="hidden" name="relative_source_dir" value="'.htmlentities($sPreviousSourceDir, ENT_QUOTES, 'UTF-8').'">');

				if (count($aErrors) > 0)
				{
					$oPage->p("Cannot copy the installed version due to the following access rights issue(s):");
					foreach($aErrors as $sDir => $oCheckResult)
					{
						$oPage->p('<img src="../images/error.png"/>&nbsp;'.$oCheckResult->sLabel);
					}
				}

				$sChecked = ($this->oWizard->GetParameter('upgrade_type') == 'use-compatible') ? ' checked ' : '';

                $oPage->p('<input id="radio_upgrade_convert" type="radio" name="upgrade_type" value="use-compatible" '.$sChecked.'/><label for="radio_upgrade_convert">&nbsp;Discard the modifications, use a standard '.$sUpgradeDMVersion.' data model.</label>');

				$oPage->add('<input type="hidden" name="datamodel_path" value="'.htmlentities($sCompatibleDMDir, ENT_QUOTES, 'UTF-8').'">');
				$oPage->add('<input type="hidden" name="datamodel_version" value="'.htmlentities($sUpgradeDMVersion, ENT_QUOTES, 'UTF-8').'">');

				$oPage->add('<div id="changes_summary"><div class="closed"><span class="title">Details of the modifications</span><div>');
				if (count($aChanges['added']) > 0)
				{
					$oPage->add('<ul>New files added:');
					foreach($aChanges['added'] as $sFilePath => $void)
					{
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				if (count($aChanges['removed']) > 0)
				{
					$oPage->add('<ul>Deleted files:');
					foreach($aChanges['removed'] as $sFilePath => $void)
					{
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				if (count($aChanges['modified']) > 0)
				{
					$oPage->add('<ul>Modified files:');
					foreach($aChanges['modified'] as $sFilePath => $void)
					{
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				$oPage->add('</div></div></div>');
			}
			else
			{
				// No changes detected... or no way to tell because of the lack of a manifest or previous source dir
				// Use the "compatible" datamodel as-is.
				$sCompatibleDMDirToDisplay = utils::HtmlEntities($sCompatibleDMDir);
				$sUpgradeDMVersionToDisplay = utils::HtmlEntities($sUpgradeDMVersion); 
				$oPage->add(
<<<HTML
<div class="message message-valid">The datamodel will be upgraded from version $sInstalledDataModelVersion to version $sUpgradeDMVersion.</div>
<input type="hidden" name="upgrade_type" value="use-compatible">
<input type="hidden" name="datamodel_path" value="$sCompatibleDMDirToDisplay">
<input type="hidden" name="datamodel_version" value="$sUpgradeDMVersionToDisplay">
HTML
				);

			}

			$oPage->add_ready_script(
<<<EOF
	$("#changes_summary .title").click(function() { $(this).parent().toggleClass('closed'); } );
	$('input[name=upgrade_type]').bind('click change', function() { WizardUpdateButtons(); });
EOF
			);

			$oMutex = new iTopMutex(
				'cron'.$this->oWizard->GetParameter('db_name', '').$this->oWizard->GetParameter('db_prefix', ''),
				$this->oWizard->GetParameter('db_server', ''),
				$this->oWizard->GetParameter('db_user', ''),
				$this->oWizard->GetParameter('db_pwd', ''),
				$this->oWizard->GetParameter('db_tls_enabled', ''),
				$this->oWizard->GetParameter('db_tls_ca', '')
			);
			if ($oMutex->IsLocked())
			{
				$oPage->add(<<<HTML
<div class="message">An iTop cron process is being executed on the target database. iTop cron process will be stopped during the setup execution.</div>
HTML
				);
			}
		}
	}

	public function CanMoveForward()
	{
		return $this->bCanMoveForward;
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return
<<<EOF
	if ($("#radio_upgrade_keep").length == 0) return true;
	
	bRet = ($('input[name=upgrade_type]:checked').length > 0);
	return bRet;
EOF
		;
	}
}

/**
 * License acceptation screen
 */
class WizStepLicense extends WizardStep
{
	public function GetTitle()
	{
		return 'License Agreement';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepDBParams');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('accept_license', 'no');
		return array('class' => 'WizStepDBParams', 'state' => '');
	}

    /**
     * @param WebPage $oPage
     */
    public function Display(WebPage $oPage)
    {
        $aLicenses = SetupUtils::GetLicenses();
		$oPage->add_style(
<<<EOF
fieldset ul{
	max-height: 18em;
	overflow: auto;
}
EOF
		);

		$oPage->add('<h2>Licenses agreements for the components of '.ITOP_APPLICATION.'</h2>');
		$oPage->add_style('div a.no-arrow { background:transparent; padding-left:0;}');
		$oPage->add_style('.toggle { cursor:pointer; text-decoration:underline; color:#1C94C4; }');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Components of '.ITOP_APPLICATION.'</legend>');
		$oPage->add('<ul>');
        $index = 0;
        foreach ($aLicenses as $oLicense)
		{
			$oPage->add('<li><b>'.$oLicense->product.'</b>, &copy; '.$oLicense->author.' is licensed under the <b>'.$oLicense->license_type.' license</b>. (<span class="toggle" id="toggle_'.$index.'">Details</span>)');
			$oPage->add('<div id="license_'.$index.'" class="license_text" style="display:none;overflow:auto;max-height:10em;font-size:small;border:1px #696969 solid;margin-bottom:1em; margin-top:0.5em;padding:0.5em;">'.$oLicense->text.'</div>');
			$oPage->add_ready_script('$(".license_text a").attr("target", "_blank").addClass("no-arrow");');
			$oPage->add_ready_script('$("#toggle_'.$index.'").click( function() { $("#license_'.$index.'").toggle(); } );');
            $index++;
		}
		$oPage->add('</ul>');
		$oPage->add('</fieldset>');
        $sChecked = ($this->oWizard->GetParameter('accept_license', 'no') == 'yes') ? ' checked ' : '';
        $oPage->p('<input type="checkbox" name="accept_license" id="accept" value="yes" '.$sChecked.'><label for="accept">&nbsp;I accept the terms of the licenses of the '.count($aLicenses).' components mentioned above.</label>');
		$oPage->add_ready_script('$("#accept").bind("click change", function() { WizardUpdateButtons(); });');
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return ($("#accept").prop("checked"));';
	}


}

/**
 * License acceptation screen (when upgrading)
 */
class WizStepLicense2 extends WizStepLicense
{
	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeMiscParams');
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepUpgradeMiscParams', 'state' => '');
	}
}

/**
 * Database Connection parameters screen
 */
class WizStepDBParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Database Configuration';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepAdminAccount');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('db_server', '');
		$this->oWizard->SaveParameter('db_user', '');
		$this->oWizard->SaveParameter('db_pwd', '');
		$this->oWizard->SaveParameter('db_name', '');
		$this->oWizard->SaveParameter('db_prefix', '');
		$this->oWizard->SaveParameter('new_db_name', '');
		$this->oWizard->SaveParameter('create_db', '');
		$this->oWizard->SaveParameter('db_new_name', '');
		$this->oWizard->SaveParameter('db_tls_enabled', false);
		$this->oWizard->SaveParameter('db_tls_ca', '');

		return array('class' => 'WizStepAdminAccount', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->add('<h2>Configuration of the database connection:</h2>');
		$sDBServer = $this->oWizard->GetParameter('db_server', '');
		$sDBUser = $this->oWizard->GetParameter('db_user', '');
		$sDBPwd = $this->oWizard->GetParameter('db_pwd', '');
		$sDBName = $this->oWizard->GetParameter('db_name', '');
		$sDBPrefix = $this->oWizard->GetParameter('db_prefix', '');
		$sTlsEnabled = $this->oWizard->GetParameter('db_tls_enabled', '');
		$sTlsCA = $this->oWizard->GetParameter('db_tls_ca', '');
		$sNewDBName = $this->oWizard->GetParameter('db_new_name', false);

		$oPage->add('<table>');
		SetupUtils::DisplayDBParameters($oPage, true, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTlsEnabled,
			$sTlsCA, $sNewDBName);
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$oPage->add('</table>');
		$sCreateDB = $this->oWizard->GetParameter('create_db', 'yes');
		if ($sCreateDB == 'no')
		{
			$oPage->add_ready_script('$("#existing_db").prop("checked", true);');
		}
		else
		{
			$oPage->add_ready_script('$("#create_db").prop("checked", true);');
		}
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch($sCode)
		{
			case 'check_db':
			SetupUtils:: AsyncCheckDB($oPage, $aParameters);
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
	if ($("#wiz_form").data("db_connection") === "error") return false;

	var bRet = true;
	bRet = ValidateField("db_name", true) && bRet;
	bRet = ValidateField("db_new_name", true) && bRet;
	bRet = ValidateField("db_prefix", true) && bRet;

	return bRet;
EOF
		;
	}
}

/**
 * Administrator Account definition screen
 */
class WizStepAdminAccount extends WizardStep
{
	public function GetTitle()
	{
		return 'Administrator Account';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepMiscParams');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('admin_user', '');
		$this->oWizard->SaveParameter('admin_pwd', '');
		$this->oWizard->SaveParameter('confirm_pwd', '');
		$this->oWizard->SaveParameter('admin_language', 'EN US');
		return array('class' => 'WizStepMiscParams', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$sAdminUser = $this->oWizard->GetParameter('admin_user', 'admin');
		$sAdminPwd = $this->oWizard->GetParameter('admin_pwd', '');
		$sConfirmPwd = $this->oWizard->GetParameter('confirm_pwd', '');
		$sAdminLanguage = $this->oWizard->GetParameter('admin_language', 'EN US');
		$oPage->add('<h2>Definition of the Administrator Account</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Administrator Account</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>Login: </td><td><input id="admin_user" name="admin_user" type="text" size="25" maxlength="64" value="'.htmlentities($sAdminUser, ENT_QUOTES, 'UTF-8').'"><span id="v_admin_user"/></td><tr>');
		$oPage->add('<tr><td>Password: </td><td><input id="admin_pwd" autocomplete="off" name="admin_pwd" type="password" size="25" maxlength="64" value="'.htmlentities($sAdminPwd, ENT_QUOTES, 'UTF-8').'"><span id="v_admin_pwd"/></td><tr>');
		$oPage->add('<tr><td>Confirm password: </td><td><input id="confirm_pwd" autocomplete="off" name="confirm_pwd" type="password" size="25" maxlength="64" value="'.htmlentities($sConfirmPwd, ENT_QUOTES, 'UTF-8').'"></td><tr>');
		$sSourceDir = APPROOT.'dictionaries/';
		$aLanguages = SetupUtils::GetAvailableLanguages($sSourceDir);
		$oPage->add('<tr><td>Language: </td><td>');
		$oPage->add(SetupUtils::GetLanguageSelect($sSourceDir, 'admin_language', $sAdminLanguage));
		$oPage->add('</td></tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add_ready_script(
<<<EOF
		$('#admin_user').bind('change keyup', function() { WizardUpdateButtons(); } );
		$('#admin_pwd').bind('change keyup', function() { WizardUpdateButtons(); } );
		$('#confirm_pwd').bind('change keyup', function() { WizardUpdateButtons(); } );
EOF
		);
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return
<<<EOF
	bRet = ($('#admin_user').val() != '');
	if (!bRet)
	{
		$("#v_admin_user").html('<img src="../images/validation_error.png" title="This field cannot be empty"/>');
	}
	else
	{
		$("#v_admin_user").html('');
	}
	
	bPasswordsMatch = ($('#admin_pwd').val() == $('#confirm_pwd').val());
	if (!bPasswordsMatch)
	{
		$('#v_admin_pwd').html('<img src="../images/validation_error.png" title="Retyped password do not match"/>');
	}
	else
	{
		$('#v_admin_pwd').html('');
	}
	bRet = bPasswordsMatch && bRet;
	
	return bRet;
EOF
		;
	}}

/**
 * Miscellaneous Parameters (URL, Sample Data)
 */
class WizStepMiscParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Miscellaneous Parameters';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('default_language', '');
		$this->oWizard->SaveParameter('application_url', '');
		$this->oWizard->SaveParameter('graphviz_path', '');
		$this->oWizard->SaveParameter('sample_data', 'yes');
		return array('class' => 'WizStepModulesChoice', 'state' => 'start_install');
	}

	public function Display(WebPage $oPage)
	{
		$sDefaultLanguage = $this->oWizard->GetParameter('default_language', $this->oWizard->GetParameter('admin_language'));
		$sApplicationURL = $this->oWizard->GetParameter('application_url', utils::GetDefaultUrlAppRoot(true));
		$sDefaultGraphvizPath = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'C:\\Program Files\\Graphviz\\bin\\dot.exe' : '/usr/bin/dot';
		$sGraphvizPath = $this->oWizard->GetParameter('graphviz_path', $sDefaultGraphvizPath);
		$sSampleData = $this->oWizard->GetParameter('sample_data', 'yes');
		$oPage->add('<h2>Additional parameters</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Default Language</legend>');
		$oPage->add('<table>');
		$sSourceDir = APPROOT.'dictionaries/';
		$aLanguages = SetupUtils::GetAvailableLanguages($sSourceDir);
		$oPage->add('<tr><td>Default Language: </td><td>');
		$oPage->add(SetupUtils::GetLanguageSelect($sSourceDir, 'default_language', $sDefaultLanguage));
		$oPage->add('</td></tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Application URL</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>URL: </td><td><input id="application_url" name="application_url" type="text" size="35" maxlength="1024" value="'.htmlentities($sApplicationURL, ENT_QUOTES, 'UTF-8').'" style="width: 100%;box-sizing: border-box;"><span id="v_application_url"/></td><tr>');
		$oPage->add('<tr><td colspan="2"><div class="message message-warning">Change the value above if the end-users will be accessing the application by another path due to a specific configuration of the web server.</div></td><tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Path to Graphviz\' dot application</legend>');
		$oPage->add('<table style="width: 100%;">');
		$oPage->add('<tr><td>Path: </td><td><input id="graphviz_path" name="graphviz_path" type="text" size="35" maxlength="1024" value="'.htmlentities($sGraphvizPath, ENT_QUOTES, 'UTF-8').'" style="width: 100%;box-sizing: border-box;"><span id="v_graphviz_path"/></td><tr>');
		$oPage->add('<tr><td colspan="2"><a href="http://www.graphviz.org" target="_blank">Graphviz</a> is required to display the impact analysis graph (i.e. impacts / depends on).</td><tr>');
		$oPage->add('<tr><td colspan="2"><span id="graphviz_status"></span></td><tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Sample Data</legend>');
        $sChecked = ($sSampleData == 'yes') ? 'checked ' : '';
        $oPage->p('<input id="sample_data_yes" name="sample_data" type="radio" value="yes" '.$sChecked.'><label for="sample_data_yes">&nbsp;I am installing a <b>demo or test</b> instance, populate the database with some demo data.');
        $sChecked = ($sSampleData == 'no') ? 'checked ' : '';
        $oPage->p('<input id="sample_data_no" name="sample_data" type="radio" value="no" '.$sChecked.'><label for="sample_data_no">&nbsp;I am installing a <b>production</b> instance, create an empty database to start from.');
		$oPage->add('</fieldset>');
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$oPage->add_ready_script(
<<<EOF
		$('#application_url').bind('change keyup', function() { WizardUpdateButtons(); } );
		$('#graphviz_path').bind('change keyup init', function() { WizardUpdateButtons();  WizardAsyncAction('check_graphviz', { graphviz_path: $('#graphviz_path').val(), authent: $('#authent_token').val()}); } ).trigger('init');
		$('#btn_next').click(function() {
			bRet = true;
			if ($(this).attr('data-graphviz') != 'ok')
			{
				bRet = confirm('The impact analysis will not be displayed properly. Are you sure you want to continue?');
			}
			return bRet;
		});
EOF
		);
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch($sCode)
		{
			case 'check_graphviz':
			$sGraphvizPath = $aParameters['graphviz_path'];
			$oCheck = SetupUtils::CheckGraphviz($sGraphvizPath);
			$sMessage = json_encode($oCheck->sLabel);
			switch($oCheck->iSeverity)
			{
				case CheckResult::INFO:
				$sStatus = 'ok';
				$sInfoExplanation = (json_encode($oCheck->sLabel) !== false) ? $oCheck->sLabel : 'Graphviz\' dot found';
				$sMessage = json_encode('<div class="message message-valid">'.$sInfoExplanation.'</div>');

				break;

				default:
				case CheckResult::ERROR:
				case CheckResult::WARNING:
				$sStatus = 'ko';
				$sErrorExplanation = (json_encode($oCheck->sLabel) !== false) ? $oCheck->sLabel : 'Could not find Graphviz\' dot';
				$sMessage = json_encode('<div class="message message-error">'.$sErrorExplanation.'</div>');

			}
			$oPage->add_ready_script(
<<<EOF
	$("#graphviz_status").html($sMessage);
	$('#btn_next').attr('data-graphviz', '$sStatus');
EOF
			);
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
	bRet = ($('#application_url').val() != '');
	if (!bRet)
	{
		$("#v_application_url").html('<img src="../images/validation_error.png" title="This field cannot be empty"/>');
	}
	else
	{
		$("#v_application_url").html('');
	}
	bGraphviz = ($('#graphviz_path').val() != '');
	if (!bGraphviz)
	{
		// Does not prevent to move forward
		$("#v_graphviz_path").html('<img src="../images/validation_error.png" title="Impact analysis will not display properly"/>');
	}
	else
	{
		$("#v_graphviz_path").html('');
	}
	return bRet;
EOF
		;
	}
}

/**
 * Miscellaneous Parameters (URL...) in case of upgrade
 */
class WizStepUpgradeMiscParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Miscellaneous Parameters';
	}

	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}

	public function ProcessParams($bMoveForward = true)
	{
		$this->oWizard->SaveParameter('application_url', '');
		$this->oWizard->SaveParameter('graphviz_path', '');
		return array('class' => 'WizStepModulesChoice', 'state' => 'start_upgrade');
	}

	public function Display(WebPage $oPage)
	{
		$sApplicationURL = $this->oWizard->GetParameter('application_url', utils::GetAbsoluteUrlAppRoot(true)); //Preserve existing configuration.
		$sDefaultGraphvizPath = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'C:\\Program Files\\Graphviz\\bin\\dot.exe' : '/usr/bin/dot';
		$sGraphvizPath = $this->oWizard->GetParameter('graphviz_path', $sDefaultGraphvizPath);
		$oPage->add('<h2>Additional parameters</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Application URL</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>URL: </td><td><input id="application_url" name="application_url" type="text" size="35" maxlength="1024" value="'.htmlentities($sApplicationURL, ENT_QUOTES, 'UTF-8').'" style="width: 100%;box-sizing: border-box;"><span id="v_application_url"/></td><tr>');
		$oPage->add('<tr><td colspan="2"><div class="message message-warning">Change the value above if the end-users will be accessing the application by another path due to a specific configuration of the web server.</div></td><tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Path to Graphviz\' dot application</legend>');
		$oPage->add('<table style="width: 100%;">');
		$oPage->add('<tr><td>Path: </td><td><input id="graphviz_path" name="graphviz_path" type="text" size="35" maxlength="1024" value="'.htmlentities($sGraphvizPath, ENT_QUOTES, 'UTF-8').'" style="width: 100%;box-sizing: border-box;"><span id="v_graphviz_path"/></td><tr>');
		$oPage->add('<tr><td colspan="2"><a href="http://www.graphviz.org" target="_blank">Graphviz</a> is required to display the impact analysis graph (i.e. impacts / depends on).</td><tr>');
		$oPage->add('<tr><td colspan="2"><span id="graphviz_status"></span></td><tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$oPage->add_ready_script(
<<<EOF
		$('#application_url').bind('change keyup', function() { WizardUpdateButtons(); } );
		$('#graphviz_path').bind('change keyup init', function() { WizardUpdateButtons();  WizardAsyncAction('check_graphviz', { graphviz_path: $('#graphviz_path').val(), authent: $('#authent_token').val() }); } ).trigger('init');
		$('#btn_next').click(function() {
			bRet = true;
			if ($(this).attr('data-graphviz') != 'ok')
			{
				bRet = confirm('The impact analysis will not be displayed properly. Are you sure you want to continue?');
			}
			return bRet;
		});
EOF
		);
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch($sCode)
		{
			case 'check_graphviz':
			$sGraphvizPath = $aParameters['graphviz_path'];
			$oCheck = SetupUtils::CheckGraphviz($sGraphvizPath);
			$sMessage = json_encode($oCheck->sLabel);
			switch($oCheck->iSeverity)
			{
				case CheckResult::INFO:
				$sStatus = 'ok';
				$sInfoExplanation = (json_encode($oCheck->sLabel) !== false) ? $oCheck->sLabel : 'Graphviz\' dot found';
				$sMessage = json_encode('<div class="message message-valid">'.$sInfoExplanation.'</div>');

				break;

				default:
				case CheckResult::ERROR:
				case CheckResult::WARNING:
				$sStatus = 'ko';
				$sErrorExplanation = (json_encode($oCheck->sLabel) !== false) ? $oCheck->sLabel : 'Could not find Graphviz\' dot';
				$sMessage = json_encode('<div class="message message-error">'.$sErrorExplanation.'</div>');

			}
			$oPage->add_ready_script(
<<<EOF
	$("#graphviz_status").html($sMessage);
	$('#btn_next').attr('data-graphviz', '$sStatus');
EOF
			);
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
	bRet = ($('#application_url').val() != '');
	if (!bRet)
	{
		$("#v_application_url").html('<img src="../images/validation_error.png" title="This field cannot be empty"/>');
	}
	else
	{
		$("#v_application_url").html('');
	}
	bGraphviz = ($('#graphviz_path').val() != '');
	if (!bGraphviz)
	{
		// Does not prevent to move forward
		$("#v_graphviz_path").html('<img src="../images/validation_error.png" title="Impact analysis will not display properly"/>');
	}
	else
	{
		$("#v_graphviz_path").html('');
	}
	return bRet;
EOF
		;
	}
}
/**
 * Choice of the modules to be installed
 */
class WizStepModulesChoice extends WizardStep
{
	static protected $SEP = '_';
	protected $bUpgrade = false;

	/**
	 *
	 * @var iTopExtensionsMap
	 */
	protected $oExtensionsMap;

	/**
	 * Whether we were able to load the choices from the database or not
	 * @var bool
	 */
	protected $bChoicesFromDatabase;

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		parent::__construct($oWizard, $sCurrentState);
		$this->bChoicesFromDatabase = false;
		$this->oExtensionsMap = new iTopExtensionsMap();
		$sPreviousSourceDir = $this->oWizard->GetParameter('previous_version_dir', '');
		$sConfigPath = null;
		if (($sPreviousSourceDir !== '') && is_readable($sPreviousSourceDir.'/conf/production/config-itop.php'))
		{
			$sConfigPath = $sPreviousSourceDir.'/conf/production/config-itop.php';
		}
		else if (is_readable(utils::GetConfigFilePath('production')))
		{
			$sConfigPath = utils::GetConfigFilePath('production');
		}

		// only called if the config file exists : we are updating a previous installation !
		// WARNING : we can't load this config directly, as it might be from another directory with a different approot_url (N°2684)
		if ($sConfigPath !== null)
		{
			$oConfig = new Config($sConfigPath);
			$this->bChoicesFromDatabase = $this->oExtensionsMap->LoadChoicesFromDatabase($oConfig);
		}
	}

	public function GetTitle()
	{
		$aStepInfo = $this->GetStepInfo();
		$sTitle = isset($aStepInfo['title']) ? $aStepInfo['title'] : 'Modules selection';
		return $sTitle;
	}

	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice', 'WizStepSummary');
	}

	public function ProcessParams($bMoveForward = true)
	{
		// Accumulates the selected modules:
		$index = $this->GetStepIndex();

		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aSelectedChoices = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		$aSelected = utils::ReadParam('choice', array());
		$aSelectedChoices[$index] = $aSelected;
		$this->oWizard->SetParameter('selected_components', json_encode($aSelectedChoices));

		if ($this->GetStepInfo($index) == null)
		{
			throw new Exception('Internal error: invalid step "'.$index.'" for the choice of modules.');
		}
		else if ($bMoveForward)
		{
			if ($this->GetStepInfo(1 + $index) != null)
			{
				return array('class' => 'WizStepModulesChoice', 'state' => (1+$index));
			}
			else
			{
				// Exiting this step of the wizard, let's convert the selection into a list of modules
				$aModules = array();
				$aExtensions = array();
				$sDisplayChoices = '<ul>';
				for($i = 0; $i <= $index; $i++)
				{
					$aStepInfo = $this->GetStepInfo($i);
					$sDisplayChoices .= $this->GetSelectedModules($aStepInfo, $aSelectedChoices[$i], $aModules, '', '', $aExtensions);
				}
				$sDisplayChoices .= '</ul>';
				if (class_exists('CreateITILProfilesInstaller'))
				{
					$this->oWizard->SetParameter('old_addon', true);
				}
				$this->oWizard->SetParameter('selected_modules', json_encode(array_keys($aModules)));
				$this->oWizard->SetParameter('selected_extensions', json_encode($aExtensions));
				$this->oWizard->SetParameter('display_choices', $sDisplayChoices);
				return array('class' => 'WizStepSummary', 'state' => '');
			}

		}
	}

	public function Display(WebPage $oPage)
	{
		$this->DisplayStep($oPage);
	}

	/**
	 * @param \SetupPage $oPage
	 *
	 * @throws \Exception
	 */
	protected function DisplayStep($oPage)
	{
		// Sanity check (not stopper, to let developers go further...)
		try
		{
			SetupUtils::AnalyzeInstallation($this->oWizard, true);
		}
		catch(MissingDependencyException $e)
		{
			$oPage->warning($e->getMessage());
		}

		$this->bUpgrade = ($this->oWizard->GetParameter('install_mode') != 'install');
		$aStepInfo = $this->GetStepInfo();
		$oPage->add_style("div.choice { margin: 0.5em;}");
		$oPage->add_style("div.choice a { text-decoration:none; font-weight: bold; color: #1C94C4 }");
		$oPage->add_style("div.description { margin-left: 2em; }");
		$oPage->add_style(".choice-disabled { color: #999; }");

		$aModules = SetupUtils::AnalyzeInstallation($this->oWizard);
		$sManualInstallError = SetupUtils::CheckManualInstallDirEmpty($aModules,
			$this->oWizard->GetParameter('extensions_dir', 'extensions'));
		if ($sManualInstallError !== '')
		{
			$oPage->warning($sManualInstallError);
		}

		$oPage->add('<table class="module-selection-banner"><tr>');
		$sBannerPath = isset($aStepInfo['banner']) ? $aStepInfo['banner'] : '';
		if (!empty($sBannerPath))
		{
			if (substr($sBannerPath, 0, 1) == '/')
			{
				// absolute path, means relative to APPROOT
				$sBannerUrl = utils::GetDefaultUrlAppRoot(true).$sBannerPath;
			}
			else
			{
				// relative path: i.e. relative to the directory containing the XML file
				$sFullPath = dirname($this->GetSourceFilePath()).'/'.$sBannerPath;
				$sRealPath = realpath($sFullPath);
				$sBannerUrl = utils::GetDefaultUrlAppRoot(true).str_replace(realpath(APPROOT), '', $sRealPath);
			}
			$oPage->add('<td><img src="'.$sBannerUrl.'"/><td>');
		}
		$sDescription = isset($aStepInfo['description']) ? $aStepInfo['description'] : '';
		$oPage->add('<td>'.$sDescription.'<td>');
		$oPage->add('</tr></table>');

		// Build the default choices
		$aDefaults = $this->GetDefaults($aStepInfo, $aModules);
		$index = $this->GetStepIndex();

		// retrieve the saved selection
		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aParameters = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		if (!isset($aParameters[$index]))
		{
			$aParameters[$index] = $aDefaults;
		}
		$aSelectedComponents = $aParameters[$index];

		$oPage->add('<div class="module-selection-body">');
		$this->DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $aDefaults);
		$oPage->add('</div>');

		$oPage->add_script(
<<<EOF
function CheckChoice(sChoiceId)
{
	var oElement = $('#'+sChoiceId);
	var bChecked = oElement.prop('checked');
	var sId = sChoiceId.replace('choice', '');
	if ((oElement.attr('type') == 'radio') && bChecked)
	{
		// Only the radio that is clicked is notified, let's warn the other radio buttons
		sName = oElement.attr('name');
		$('input[name="'+sName+'"]').each(function() {
			var sRadioId = $(this).attr('id');
			if ((sRadioId != sChoiceId) && (sRadioId != undefined))
			{
				CheckChoice(sRadioId);
			}
		});
	}
	
	$('#sub_choices'+sId).each(function() {
		if (!bChecked)
		{
			$(this).addClass('choice-disabled');
		}
		else
		{
			$(this).removeClass('choice-disabled');
		}
		
		$('input', this).each(function() {
			if (bChecked)
			{
				if ($(this).attr('data-disabled') != 'disabled')
				{
					// Only non-mandatory fields can be enabled
					$(this).prop('disabled', false);
				}
			}
			else
			{
				$(this).prop('disabled', true);
				$(this).prop('checked', false);
			}
		});
	});
}
EOF
		);
		$oPage->add_ready_script(
<<<EOF
		$('.wiz-choice').bind('change', function() { CheckChoice($(this).attr('id')); } );
		$('.wiz-choice').trigger('change');
EOF
		);
	}

	protected function GetDefaults($aInfo, $aModules, $sParentId = '')
	{
		$aDefaults = array();
		if (!$this->bChoicesFromDatabase)
		{
			$this->GuessDefaultsFromModules($aInfo, $aDefaults, $aModules, $sParentId);
		}
		else
		{
			$this->GetDefaultsFromDatabase($aInfo, $aDefaults, $sParentId);
		}
		return $aDefaults;
	}

	protected function GetDefaultsFromDatabase($aInfo, &$aDefaults, $sParentId)
	{
		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : array();
		foreach($aOptions as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($this->bUpgrade)
			{
				if ($this->oExtensionsMap->IsMarkedAsChosen($aChoice['extension_code']))
				{
					$aDefaults[$sChoiceId] = $sChoiceId;
				}
			}
			else if (isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceId] = $sChoiceId;
			}
			// Recurse for sub_options (if any)
			if (isset($aChoice['sub_options']))
			{
				$this->GetDefaultsFromDatabase($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : array();
		$sChoiceName = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			if ($this->bUpgrade)
			{
				if ($this->oExtensionsMap->IsMarkedAsChosen($aChoice['extension_code']))
				{
					$aDefaults[$sChoiceName] = $sChoiceId;
				}
			}
			else if (isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceName] = $sChoiceId;
			}
			// Recurse for sub_options (if any)
			if (isset($aChoice['sub_options']))
			{
				$this->GetDefaultsFromDatabase($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
		}
	}

	/**
	 * Try to guess the user choices based on the current list of installed modules...
	 * @param array $aInfo
	 * @param array $aDefaults
	 * @param array $aModules
	 * @param string $sParentId
	 * @return array
	 */
	protected function GuessDefaultsFromModules($aInfo, &$aDefaults, $aModules, $sParentId = '')
	{
		$aRetScore = array();
		$aScores = array();

		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : array();
		foreach($aOptions as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aScores[$sChoiceId] = array();
			if (!$this->bUpgrade && isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceId] = $sChoiceId;
			}
			if ($this->bUpgrade)
			{
				// In upgrade mode, the defaults are the installed modules
				foreach($aChoice['modules'] as $sModuleId)
				{
					if ($aModules[$sModuleId]['version_db'] != '')
					{
						// A module corresponding to this choice is installed
						$aScores[$sChoiceId][$sModuleId] = true;
					}
				}
				// Used for migration from 1.3.x or before
				// Accept that the new version can have one new module than the previous version
				// The option is still selected
				$iSelected = count($aScores[$sChoiceId]);
				$iNeeded = count($aChoice['modules']);
				if (($iSelected > 0) && (($iNeeded - $iSelected) < 2))
				{
					// All the modules are installed, this choice is selected
					$aDefaults[$sChoiceId] = $sChoiceId;
				}
				$aRetScore = array_merge($aRetScore, $aScores[$sChoiceId]);
			}

			if (isset($aChoice['sub_options']))
			{
				$aScores[$sChoiceId] = array_merge($aScores[$sChoiceId], $this->GuessDefaultsFromModules($aChoice['sub_options'], $aDefaults, $sChoiceId));
			}
			$index++;
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : array();
		$sChoiceName = null;
		$sChoiceIdNone = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aScores[$sChoiceId] = array();
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId;
			}
			if (!$this->bUpgrade && isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceName] = $sChoiceId;
			}
			if (isset($aChoice['sub_options']))
			{
				// By default (i.e. install-mode), sub options can only be checked if the parent option is checked by default
				if ($this->bUpgrade || (isset($aChoice['default']) && $aChoice['default']))
				{
					$aScores[$sChoiceId] = $this->GuessDefaultsFromModules($aChoice['sub_options'], $aDefaults, $aModules, $sChoiceId);
				}
			}
			$index++;
		}

		$iMaxScore = 0;
		if ($this->bUpgrade && (count($aAlternatives) > 0))
		{
			// The installed choices have precedence over the 'default' choices
			// In case several choices share the same base modules, let's weight the alternative choices
			// based on their number of installed modules
			$sChoiceName = null;

			foreach($aAlternatives as $index => $aChoice)
			{
				$sChoiceId = $sParentId.self::$SEP.$index;
				if ($sChoiceName == null)
				{
					$sChoiceName = $sChoiceId;
				}
				if (array_key_exists('modules', $aChoice))
				{
					foreach($aChoice['modules'] as $sModuleId)
					{
						if ($aModules[$sModuleId]['version_db'] != '')
						{
							// A module corresponding to this choice is installed, increase the score of this choice
							if (!isset($aScores[$sChoiceId])) $aScores[$sChoiceId] = array();
							$aScores[$sChoiceId][$sModuleId] = true;
							$iMaxScore = max($iMaxScore, count($aScores[$sChoiceId]));
						}
					}
					//if (count($aScores[$sChoiceId]) == count($aChoice['modules']))
					//{
					//	$iScore += 100; // Bonus for the parent when a choice is complete
					//}
					$aRetScore = array_merge($aRetScore, $aScores[$sChoiceId]);
				}
				$iMaxScore = max($iMaxScore, isset($aScores[$sChoiceId]) ? count($aScores[$sChoiceId]) : 0);
			}
		}
		if ($iMaxScore > 0)
		{
			$aNumericScores = array();
			foreach($aScores as $sChoiceId => $aModules)
			{
				$aNumericScores[$sChoiceId] = count($aModules);
			}
			// The choice with the bigger score wins !
			asort($aNumericScores, SORT_NUMERIC);
			$aKeys = array_keys($aNumericScores);
			$sBetterChoiceId = array_pop($aKeys);
			$aDefaults[$sChoiceName] = $sBetterChoiceId;
		}
		// echo "Scores: <pre>".print_r($aScores, true)."</pre><br/>";
		// echo "Defaults: <pre>".print_r($aDefaults, true)."</pre><br/>";
		return $aRetScore;
	}

	/**
	 * Converts the list of selected "choices" into a list of "modules": take into account the selected and the mandatory modules
	 *
	 * @param array $aInfo Info about the "choice" array('options' => array(...), 'alternatives' => array(...))
	 * @param array $aSelectedChoices List of selected choices array('name' => 'selected_value_id')
	 * @param array $aModules Return parameter: List of selected modules array('module_id' => true)
	 * @param string $sParentId Used for recursion
	 *
	 * @return string A text representation of what will be installed
	 */
	protected function GetSelectedModules($aInfo, $aSelectedChoices, &$aModules, $sParentId = '', $sDisplayChoices = '', &$aSelectedExtensions = null)
	{
		if ($sParentId == '')
		{
			// Check once (before recursing) that the hidden modules are selected
			foreach(SetupUtils::AnalyzeInstallation($this->oWizard) as $sModuleId => $aModule)
			{
				if (($sModuleId != ROOT_MODULE) && !isset($aModules[$sModuleId]))
				{
					if (($aModule['category'] == 'authentication') || (!$aModule['visible'] && !isset($aModule['auto_select'])))
					{
						$aModules[$sModuleId] = true;
						$sDisplayChoices .= '<li><i>'.$aModule['label'].' (hidden)</i></li>';
					}
				}
			}
		}
		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : array();
		foreach($aOptions as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aModuleInfo = [];
			// Get the extension corresponding to the choice
			foreach ($this->oExtensionsMap->GetAllExtensions() as $sExtensionVersion => $oExtension) {
				if (utils::StartsWith($sExtensionVersion, $aChoice['extension_code'].'/')) {
					$aModuleInfo = $oExtension->aModuleInfo;
					break;
				}
			}
			if ((isset($aChoice['mandatory']) && $aChoice['mandatory']) ||
				(isset($aSelectedChoices[$sChoiceId]) && ($aSelectedChoices[$sChoiceId] == $sChoiceId))) {
				$sDisplayChoices .= '<li>'.$aChoice['title'].'</li>';
				if (isset($aChoice['modules'])) {
					foreach ($aChoice['modules'] as $sModuleId) {
						$bSelected = true;
						if (isset($aModuleInfo[$sModuleId])) {
							// Test if module has 'auto_select'
							$aInfo = $aModuleInfo[$sModuleId];
							if (isset($aInfo['auto_select'])) {
								// Check the module selection
								try {
									$bSelected = false;
									SetupInfo::SetSelectedModules($aModules);
									eval('$bSelected = ('.$aInfo['auto_select'].');');
								}
								catch (Exception $e) {
									$bSelected = false;
								}
							}
						}
						if ($bSelected) {
							$aModules[$sModuleId] = true; // store the Id of the selected module
							SetupInfo::SetSelectedModules($aModules);
						}
					}
				}
				$sChoiceType = isset($aChoice['type']) ? $aChoice['type'] : 'wizard_option';
				if ($aSelectedExtensions !== null) {
					$aSelectedExtensions[] = $aChoice['extension_code'];
				}
				// Recurse only for selected choices
				if (isset($aChoice['sub_options'])) {
					$sDisplayChoices .= '<ul>';
					$sDisplayChoices = $this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId, $sDisplayChoices, $aSelectedExtensions);
					$sDisplayChoices .= '</ul>';
				}
				$sDisplayChoices .= '</li>';
			}
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : array();
		$sChoiceName = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId;
			}
			if ( (isset($aChoice['mandatory']) && $aChoice['mandatory']) ||
				 (isset($aSelectedChoices[$sChoiceName]) && ($aSelectedChoices[$sChoiceName] == $sChoiceId)) )
			{
				$sDisplayChoices .= '<li>'.$aChoice['title'].'</li>';
				if ($aSelectedExtensions !== null)
				{
					$aSelectedExtensions[] = $aChoice['extension_code'];
				}
				if (isset($aChoice['modules']))
				{
					foreach($aChoice['modules'] as $sModuleId)
					{
						$aModules[$sModuleId] = true; // store the Id of the selected module
					}
				}
				// Recurse only for selected choices
				if (isset($aChoice['sub_options']))
				{
					$sDisplayChoices .= '<ul>';
					$sDisplayChoices = $this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId, $sDisplayChoices, $aSelectedExtensions);
					$sDisplayChoices .= '</ul>';
				}
				$sDisplayChoices .= '</li>';
			}
		}
		if ($sParentId == '')
		{
			// Last pass (after all the user's choices are turned into "selected" modules):
			// Process 'auto_select' modules for modules that are not already selected
			$aAvailableModules = SetupUtils::AnalyzeInstallation($this->oWizard);
			do
			{
				// Loop while new modules are added...
				$bModuleAdded = false;
				foreach($aAvailableModules as $sModuleId => $aModule)
				{
					if (($sModuleId != ROOT_MODULE) && !array_key_exists($sModuleId, $aModules) && isset($aModule['auto_select']))
					{
						try
						{
							$bSelected = false;
							SetupInfo::SetSelectedModules($aModules);
							eval('$bSelected = ('.$aModule['auto_select'].');');
						}
						catch(Exception $e)
						{
							$sDisplayChoices .= '<li><b>Warning: auto_select failed with exception ('.$e->getMessage().') for module "'.$sModuleId.'"</b></li>';
							$bSelected = false;
						}
						if ($bSelected)
						{
							$aModules[$sModuleId] = true; // store the Id of the selected module
							$sDisplayChoices .= '<li>'.$aModule['label'].' (auto_select)</li>';
							$bModuleAdded  = true;
						}
					}
				}
			}
			while($bModuleAdded);
		}

		return $sDisplayChoices;
	}

	protected function GetStepIndex()
	{
		switch($this->sCurrentState)
		{
			case 'start_install':
			case 'start_upgrade':
			$index = 0;
			break;

			default:
			$index = (integer)$this->sCurrentState;
		}
		return $index;
	}
	protected function GetStepInfo($idx = null)
	{
		$aStepInfo = null;
		if ($idx === null)
		{
			$index = $this->GetStepIndex();
		}
		else
		{
			$index = $idx;
		}

		$aSteps = array();
		$this->oWizard->SetParameter('additional_extensions_modules', json_encode(array())); // Default value, no additional extensions

		if (@file_exists($this->GetSourceFilePath()))
		{
			// Found an "installation.xml" file, let's us tis definition for the wizard
			$aParams = new XMLParameters($this->GetSourceFilePath());
			$aSteps = $aParams->Get('steps', array());

			// Additional step for the "extensions"
			$aStepDefinition = array(
					'title' => 'Extensions',
					'description' => '<h2>Select additional extensions to install. You can launch the installation again to install new extensions, but you cannot remove already installed extensions.</h2>',
					'banner' => '/images/extension.png',
					'options' => array()
			);

			foreach($this->oExtensionsMap->GetAllExtensions() as $oExtension)
			{
				if (($oExtension->sSource !== iTopExtension::SOURCE_WIZARD) && ($oExtension->bVisible) && (count($oExtension->aMissingDependencies) == 0))
				{
					$aStepDefinition['options'][] = array(
							'extension_code' => $oExtension->sCode,
							'title' => $oExtension->sLabel,
							'description' => $oExtension->sDescription,
							'more_info' => $oExtension->sMoreInfoUrl,
							'default' => true, // by default offer to install all modules
							'modules' => $oExtension->aModules,
							'mandatory' => $oExtension->bMandatory || ($oExtension->sSource === iTopExtension::SOURCE_REMOTE),
							'source_label' => $this->GetExtensionSourceLabel($oExtension->sSource),
					);
				}
			}
			// Display this step of the wizard only if there is something to display
			if (count($aStepDefinition['options']) !== 0)
			{
				$aSteps[] = $aStepDefinition;
				$this->oWizard->SetParameter('additional_extensions_modules', json_encode($aStepDefinition['options']));
			}
		}
		else
		{
			// No wizard configuration provided, build a standard one with just one big list
			$aStepDefinition = array(
					'title' => 'Modules Selection',
					'description' => '<h2>Select the modules to install. You can launch the installation again to install new modules, but you cannot remove already installed modules.</h2>',
					'banner' => '/images/modules.png',
					'options' => array()
			);
			foreach($this->oExtensionsMap->GetAllExtensions() as $oExtension)
			{
				if (($oExtension->bVisible) && (count($oExtension->aMissingDependencies) == 0))
				{
					$aStepDefinition['options'][] = array(
							'extension_code' => $oExtension->sCode,
							'title' => $oExtension->sLabel,
							'description' => $oExtension->sDescription,
							'more_info' => $oExtension->sMoreInfoUrl,
							'default' => true, // by default offer to install all modules
							'modules' => $oExtension->aModules,
							'mandatory' => $oExtension->bMandatory ||  ($oExtension->sSource !== iTopExtension::SOURCE_REMOTE),
							'source_label' => $this->GetExtensionSourceLabel($oExtension->sSource),
					);
				}
			}
			$aSteps[] = $aStepDefinition;
		}

		if (array_key_exists($index, $aSteps))
		{
			$aStepInfo = $aSteps[$index];
		}

		return $aStepInfo;
	}

	protected function GetExtensionSourceLabel($sSource)
	{
		switch($sSource)
		{
			case iTopExtension::SOURCE_MANUAL:
			$sResult = 'Extension';
			break;

			case iTopExtension::SOURCE_REMOTE:
			$sResult = (ITOP_APPLICATION == 'iTop') ? 'iTop-Hub' : 'ITSM-Designer';
			break;

			default:
			$sResult = '';
		}
		if ($sResult == '')
		{
			return '';
		}
		return '<span style="display:inline-block;font-size:8pt;padding:3px;border-radius:4px;color:#fff;background-color:#1c94c4;margin-left:0.5em;margin-right:0.5em">'.$sResult.'</span>';
	}

	protected function DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $aDefaults, $sParentId = '', $bAllDisabled = false)
	{
		$aOptions = isset($aStepInfo['options']) ? $aStepInfo['options'] : array();
		$aAlternatives = isset($aStepInfo['alternatives']) ? $aStepInfo['alternatives'] : array();
		$index = 0;

		$sAllDisabled = '';
		if ($bAllDisabled)
		{
			$sAllDisabled = 'disabled data-disabled="disabled" ';
		}

		foreach($aOptions as $index => $aChoice)
		{
			$sAttributes = '';
			$sChoiceId = $sParentId.self::$SEP.$index;
			$sDataId = 'data-id="'.htmlentities($aChoice['extension_code'], ENT_QUOTES, 'UTF-8').'"';
			$sId = htmlentities($aChoice['extension_code'], ENT_QUOTES, 'UTF-8');
			$bIsDefault = array_key_exists($sChoiceId, $aDefaults);
			$bSelected = isset($aSelectedComponents[$sChoiceId]) && ($aSelectedComponents[$sChoiceId] == $sChoiceId);
			$bMandatory = (isset($aChoice['mandatory']) && $aChoice['mandatory']) || ($this->bUpgrade && $bIsDefault);
			$bDisabled = false;
			if ($bMandatory)
			{
				$oPage->add('<div class="choice" '.$sDataId.'><input id="'.$sId.'" checked disabled data-disabled="disabled" type="checkbox"'.$sAttributes.'/><input type="hidden" name="choice['.$sChoiceId.']" value="'.$sChoiceId.'">&nbsp;');
				$bDisabled = true;
			}
			else if ($bSelected)
			{
				$oPage->add('<div class="choice" '.$sDataId.'><input class="wiz-choice" '.$sAllDisabled.'id="'.$sId.'" name="choice['.$sChoiceId.']" type="checkbox" checked value="'.$sChoiceId.'"/>&nbsp;');
			}
			else
			{
				$oPage->add('<div class="choice" '.$sDataId.'><input class="wiz-choice" '.$sAllDisabled.'id="'.$sId.'" name="choice['.$sChoiceId.']" type="checkbox" value="'.$sChoiceId.'"/>&nbsp;');
			}
			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceId, $bDisabled);
			$oPage->add('</div>');
			$index++;
		}
		$sChoiceName = null;
		$sDisabled = '';
		$bDisabled = false;
		$sChoiceIdNone = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			$bIsDefault = array_key_exists($sChoiceName, $aDefaults) && ($aDefaults[$sChoiceName] == $sChoiceId);
			$bMandatory = (isset($aChoice['mandatory']) && $aChoice['mandatory']) || ($this->bUpgrade && $bIsDefault);
			if ($bMandatory || $bAllDisabled)
			{
				// One choice is mandatory, all alternatives are disabled
				$sDisabled = ' disabled data-disabled="disabled"';
				$bDisabled = true;
			}
			if ( (!isset($aChoice['sub_options']) || (count($aChoice['sub_options']) == 0)) && (!isset($aChoice['modules']) || (count($aChoice['modules']) == 0)) )
			{
				$sChoiceIdNone = $sChoiceId; // the "None" / empty choice
			}
		}

		if (!array_key_exists($sChoiceName, $aDefaults) || ($aDefaults[$sChoiceName] == $sChoiceIdNone))
		{
			// The "none" choice does not disable the selection !!
			$sDisabled = '';
			$bDisabled = false;
		}

		foreach($aAlternatives as $index => $aChoice)
		{
			$sAttributes = '';
			$sChoiceId = $sParentId.self::$SEP.$index;
			$sDataId = 'data-id="'.htmlentities($aChoice['extension_code'], ENT_QUOTES, 'UTF-8').'"';
			$sId = htmlentities($aChoice['extension_code'], ENT_QUOTES, 'UTF-8');

			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			$bIsDefault = array_key_exists($sChoiceName, $aDefaults) && ($aDefaults[$sChoiceName] == $sChoiceId);
			$bSelected = isset($aSelectedComponents[$sChoiceName]) && ($aSelectedComponents[$sChoiceName] == $sChoiceId);
			if ( !isset($aSelectedComponents[$sChoiceName]) && ($sChoiceIdNone != null))
			{
				// No choice selected, select the "None" option
				$bSelected = ($sChoiceId == $sChoiceIdNone);
			}
			$bMandatory = (isset($aChoice['mandatory']) && $aChoice['mandatory']) || ($this->bUpgrade && $bIsDefault);

			if ($bSelected)
			{
				$sAttributes = ' checked ';
			}
			$sHidden = '';
			if ($bMandatory && $bDisabled)
			{
				$sAttributes = ' checked ';
				$sHidden = '<input type="hidden" name="choice['.$sChoiceName.']" value="'.$sChoiceId.'"/>';
			}
			$oPage->add('<div class="choice" '.$sDataId.'><input class="wiz-choice" id="'.$sId.'" name="choice['.$sChoiceName.']" type="radio"'.$sAttributes.' value="'.$sChoiceId.'"'.$sDisabled.'/>'.$sHidden.'&nbsp;');
			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceId, $bDisabled && !$bSelected);
			$oPage->add('</div>');
			$index++;
		}
	}

	protected function DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceId, $bDisabled = false)
	{
		$sMoreInfo = (isset($aChoice['more_info']) && ($aChoice['more_info'] != '')) ? '<a target="_blank" href="'.$aChoice['more_info'].'">More information</a>' : '';
		$sSourceLabel = isset($aChoice['source_label']) ? $aChoice['source_label'] : '';
		$sId = htmlentities($aChoice['extension_code'], ENT_QUOTES, 'UTF-8');
		$oPage->add('<label for="'.$sId.'"><b>'.htmlentities($aChoice['title'], ENT_QUOTES, 'UTF-8').'</b>'.$sSourceLabel.'</label> '.$sMoreInfo);
		$sDescription = isset($aChoice['description']) ? htmlentities($aChoice['description'], ENT_QUOTES, 'UTF-8') : '';
		$oPage->add('<div class="description">'.$sDescription.'<span id="sub_choices'.$sId.'">');
		if (isset($aChoice['sub_options']))
		{
			$this->DisplayOptions($oPage, $aChoice['sub_options'], $aSelectedComponents, $aDefaults, $sChoiceId, $bDisabled);
		}
		$oPage->add('</span></div>');
	}

	protected function GetSourceFilePath()
	{
		$sSourceDir = $this->oWizard->GetParameter('source_dir');
		return $sSourceDir.'/installation.xml';
	}

}

/**
 * Summary of the installation tasks
 */
class WizStepSummary extends WizardStep
{
	protected $bDependencyCheck = null;
	protected $sDependencyIssue = null;

	protected function CheckDependencies()
	{
		if (is_null($this->bDependencyCheck))
		{
			$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
			$this->bDependencyCheck = true;
			try
			{
				SetupUtils::AnalyzeInstallation($this->oWizard, true, $aSelectedModules);
			}
			catch(MissingDependencyException $e)
			{
				$this->bDependencyCheck = false;
				$this->sDependencyIssue = $e->getMessage();
			}
		}
		return $this->bDependencyCheck;
	}

	public function GetTitle()
	{
		$sMode = $this->oWizard->GetParameter('mode', 'install');
		if ($sMode == 'install')
		{
			return 'Ready to install';

		}
		else
		{
			return 'Ready to upgrade';
		}
	}

	public function GetPossibleSteps()
	{
		return array('WizStepDone');
	}

	/**
	 * Returns the label for the " Next >> " button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return ' Install ! ';
	}

	public function CanMoveForward()
	{
		if ($this->CheckDependencies())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepDone', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->add_style(
			<<<CSS
#params_summary {
	height: 200px;
	overflow: auto;
}
#params_summary div {
	width:100%;
	margin-top:0;
	padding-top: 0.5em;
	padding-left: 0;
}
#params_summary div ul {
	margin-left:0;
	padding-left: 20px;
}
#params_summary div.closed ul {
	display:none;
}
#params_summary div li {
	list-style: none;
	width: 100%;
	margin-left:0;
	padding-left: 0em;
}
.title {
	padding-left: 20px;
	font-weight: bold;
	cursor: pointer;
	background: url(../images/minus.gif) 2px 2px no-repeat;
}
#params_summary div.closed .title {
	background: url(../images/plus.gif) 2px 2px no-repeat;
}
#progress_content {
	height: 200px;
	overflow: auto;
	text-align: center;
}
#installation_progress {
	display: none;
}
CSS
		);

		$aInstallParams = $this->BuildConfig();

		$sMode = $aInstallParams['mode'];

		$sDestination = ITOP_APPLICATION.(($sMode == 'install') ? ' version '.ITOP_VERSION.' is about to be installed ' : ' is about to be upgraded ');
		$sDBDescription = ' <b>existing</b> database <b>'.$aInstallParams['database']['name'].'</b>';
		if (($sMode == 'install') && ($this->oWizard->GetParameter('create_db') == 'yes'))
		{
			$sDBDescription = ' <b>new</b> database <b>'.$aInstallParams['database']['name'].'</b>';
		}
		$sDestination .= 'into the '.$sDBDescription.' on the server <b>'.$aInstallParams['database']['server'].'</b>.';
		$oPage->add('<h2>'.$sDestination.'</h2>');

		$oPage->add('<fieldset id="summary"><legend>Installation Parameters</legend>');
		$oPage->add('<div id="params_summary">');
		$oPage->add('<div class="closed"><span class="title">Database Parameters</span><ul>');
		$oPage->add('<li>Server Name: '.$aInstallParams['database']['server'].'</li>');
		$oPage->add('<li>DB User Name: '.$aInstallParams['database']['user'].'</li>');
		$oPage->add('<li>DB user password: '.$aInstallParams['database']['pwd'].'</li>');
		if (($sMode == 'install') && ($this->oWizard->GetParameter('create_db') == 'yes'))
		{
			$oPage->add('<li>Database Name: '.$aInstallParams['database']['name'].' (will be created)</li>');
		}
		else
		{
			$oPage->add('<li>Database Name: '.$aInstallParams['database']['name'].'</li>');
		}
		if ($aInstallParams['database']['prefix'] != '')
		{
			$oPage->add('<li>Prefix for the '.ITOP_APPLICATION.' tables: '.$aInstallParams['database']['prefix'].'</li>');
		}
		else
		{
			$oPage->add('<li>Prefix for the '.ITOP_APPLICATION.' tables: none</li>');
		}
		$oPage->add('</ul></div>');

		$oPage->add('<div><span class="title">Data Model Configuration</span>');
		$oPage->add($this->oWizard->GetParameter('display_choices'));
		$oPage->add('</div>');

		$oPage->add('<div class="closed"><span class="title">Other Parameters</span><ul>');
		if ($sMode == 'install')
		{
			$oPage->add('<li>Default language: '.$aInstallParams['language'].'</li>');
		}

		$oPage->add('<li>URL to access the application: '.$aInstallParams['url'].'</li>');
		$oPage->add('<li>Graphviz\' dot path: '.$aInstallParams['graphviz_path'].'</li>');
		if ($aInstallParams['sample_data'] == 'yes')
		{
			$oPage->add('<li>Sample data will be loaded into the database.</li>');
		}
		if ($aInstallParams['old_addon'])
		{
			$oPage->add('<li>Compatibility mode: Using the version 1.2 of the UserRightsProfiles add-on.</li>');
		}
		$oPage->add('</ul></div>');

		if ($sMode == 'install')
		{
			$oPage->add('<div class="closed"><span class="title">Admininistrator Account</span><ul>');
			$oPage->add('<li>Login: '.$aInstallParams['admin_account']['user'].'</li>');
			$oPage->add('<li>Password: '.$aInstallParams['admin_account']['pwd'].'</li>');
			$oPage->add('<li>Language: '.$aInstallParams['admin_account']['language'].'</li>');
			$oPage->add('</ul></div>');
		}

		$aMiscOptions = $aInstallParams['options'];
		if (count($aMiscOptions) > 0)
		{
			$oPage->add('<div class="closed"><span class="title">Miscellaneous Options</span><ul>');
			foreach($aMiscOptions as $sKey => $sValue)
			{
				$oPage->add('<li>'.$sKey.': '.$sValue.'</li>');
			}
			$oPage->add('</ul></div>');

		}

		$aSelectedModules = $aInstallParams['selected_modules'];

		if (isset($aMiscOptions['generate_config']))
		{
			$oDoc = new DOMDocument('1.0', 'UTF-8');
			$oDoc->preserveWhiteSpace = false;
			$oDoc->formatOutput = true;
			$oParams = new PHPParameters();
			$oParams->LoadFromHash($aInstallParams);
			$oParams->ToXML($oDoc, null, 'installation');
			$sXML = $oDoc->saveXML();
			$oPage->add('<div class="closed"><span class="title">XML Config file</span><ul><pre>');
			$oPage->add(htmlentities($sXML, ENT_QUOTES, 'UTF-8'));
			$oPage->add('</pre></ul></div>');
		}

		$oPage->add('</div>'); // params_summary
		$oPage->add('</fieldset>');

		$oPage->add('<fieldset id="installation_progress"><legend>Progress of the installation</legend>');
		$oPage->add('<div id="progress_content">');
		$oPage->add_linked_script('../setup/jquery.progression.js');
		$oPage->add('<p class="center"><span id="setup_msg">Ready to start...</span></p><div style="display:block;margin-left: auto; margin-right:auto;" id="progress">0%</div>');
		$oPage->add('</div>'); // progress_content
		$oPage->add('</fieldset>');

		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.htmlentities($sJSONData, ENT_QUOTES, 'UTF-8').'"/>');

		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');

		if (!$this->CheckDependencies())
		{
			$oPage->error($this->sDependencyIssue);
		}

		$oPage->add_ready_script(
			<<<JS
	$("#params_summary div").addClass('closed');
	$("#params_summary .title").click(function() { $(this).parent().toggleClass('closed'); } );
	$("#btn_next").bind("click.install", function(event) {
			$('#summary').hide();
			$('#installation_progress').show();
			$(this).prop('disabled', true);	 event.preventDefault(); ExecuteStep("");
	});
	$("#wiz_form").data("installation_status", "not started")
JS
		);
	}

	/**
	 * Prepare the parameters to execute the installation asynchronously
	 * @return Hash A big hash array that can be converted to XML or JSON with all the needed parameters
	 */
	protected function BuildConfig()
	{
		$sMode = $this->oWizard->GetParameter('install_mode', 'install');
		$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
		$aSelectedExtensions = json_decode($this->oWizard->GetParameter('selected_extensions'), true);
		$sBackupDestination = '';
		$sPreviousConfigurationFile = '';
		$sDBName = $this->oWizard->GetParameter('db_name');
		if ($sMode == 'upgrade')
		{
			$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir', '');
			if (!empty($sPreviousVersionDir))
			{
				$aPreviousInstance = SetupUtils::GetPreviousInstance($sPreviousVersionDir);
				if ($aPreviousInstance['found'])
				{
					$sPreviousConfigurationFile = $aPreviousInstance['configuration_file'];
				}
			}

			if ($this->oWizard->GetParameter('db_backup', false))
			{
				$sBackupDestination = $this->oWizard->GetParameter('db_backup_path', '');
			}
		}
		else
		{

			$sDBNewName = $this->oWizard->GetParameter('db_new_name', '');
			if ($sDBNewName != '')
			{
				$sDBName = $sDBNewName; // Database will be created
			}
		}

		$sSourceDir = $this->oWizard->GetParameter('source_dir');
		$aCopies = array();
		if (($sMode == 'upgrade') && ($this->oWizard->GetParameter('upgrade_type') == 'keep-previous'))
		{
			$sPreviousVersionDir = $this->oWizard->GetParameter('previous_version_dir');
			$aCopies[] = array('source' => $sSourceDir, 'destination' => 'modules'); // Source is an absolute path, destination is relative to APPROOT
			$aCopies[] = array('source' => $sPreviousVersionDir.'/portal', 'destination' => 'portal'); // Source is an absolute path, destination is relative to APPROOT
			$sSourceDir = APPROOT.'modules';
		}

		$aInstallParams = array (
			'mode' => $sMode,
			'preinstall' => array (
				'copies' => $aCopies,
				// 'backup' => see below
			),
			'source_dir' => str_replace(APPROOT, '', $sSourceDir),
			'datamodel_version' => $this->oWizard->GetParameter('datamodel_version'), //TODO: let the installer compute this automatically...
			'previous_configuration_file' => $sPreviousConfigurationFile,
			'extensions_dir' => 'extensions',
			'target_env' => 'production',
			'workspace_dir' => '',
			'database' => array (
				'server' => $this->oWizard->GetParameter('db_server'),
				'user' => $this->oWizard->GetParameter('db_user'),
				'pwd' => $this->oWizard->GetParameter('db_pwd'),
				'name' => $sDBName,
				'db_tls_enabled' => $this->oWizard->GetParameter('db_tls_enabled'),
				'db_tls_ca' => $this->oWizard->GetParameter('db_tls_ca'),
				'prefix' => $this->oWizard->GetParameter('db_prefix'),
			),
			'url' => $this->oWizard->GetParameter('application_url'),
			'graphviz_path' => $this->oWizard->GetParameter('graphviz_path'),
			'admin_account' => array (
				'user' => $this->oWizard->GetParameter('admin_user'),
				'pwd' => $this->oWizard->GetParameter('admin_pwd'),
				'language' => $this->oWizard->GetParameter('admin_language'),
			),
			'language' => $this->oWizard->GetParameter('default_language'),
			'selected_modules' =>  $aSelectedModules,
			'selected_extensions' =>  $aSelectedExtensions,
			'sample_data' => ($this->oWizard->GetParameter('sample_data', '') == 'yes') ? true : false ,
			'old_addon' => $this->oWizard->GetParameter('old_addon', false), // whether or not to use the "old" userrights profile addon
			'options' => json_decode($this->oWizard->GetParameter('misc_options', '[]'), true),
			'mysql_bindir' => $this->oWizard->GetParameter('mysql_bindir'),
		);

		if ($sBackupDestination != '')
		{
			$aInstallParams['preinstall']['backup'] = array (
				'destination' => $sBackupDestination,
				'configuration_file' => $sPreviousConfigurationFile,
			);
		}

		return $aInstallParams;
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		$oParameters = new PHPParameters();
		$sStep = $aParameters['installer_step'];
		$sJSONParameters = $aParameters['installer_config'];
		$oParameters->LoadFromHash(json_decode($sJSONParameters, true /* bAssoc */));
		$oInstaller = new ApplicationInstaller($oParameters);
		$aRes = $oInstaller->ExecuteStep($sStep);
		if (($aRes['status'] != ApplicationInstaller::ERROR) && ($aRes['next-step'] != ''))
		{
			// Tell the web page to move the progress bar and to launch the next step
			$sMessage = addslashes(htmlentities($aRes['next-step-label'], ENT_QUOTES, 'UTF-8'));
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "running");
	WizardUpdateButtons();
	$('#setup_msg').html('$sMessage');
	$('#progress').progression( {Current:{$aRes['percentage-completed']}, Maximum: 100} );
	
	//$("#percentage").html('{$aRes['percentage-completed']} % completed<br/>{$aRes['next-step-label']}');
	ExecuteStep('{$aRes['next-step']}');
EOF
			);
		}
		else if ($aRes['status'] != ApplicationInstaller::ERROR)
		{
			// Installation complete, move to the next step of the wizard
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "completed");
	$('#progress').progression( {Current:100, Maximum: 100} );
	WizardUpdateButtons();
	$("#btn_next").unbind("click.install");
	$("#btn_next").click();
EOF
			);
		}
		else
		{
			$sMessage = addslashes(htmlentities($aRes['message'], ENT_QUOTES, 'UTF-8'));
			$sMessage = str_replace("\n", '<br>', $sMessage);
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "error");
	WizardUpdateButtons();
	$('#setup_msg').html('$sMessage');
EOF
			);
		}
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return (($("#wiz_form").data("installation_status") === "not started") || ($("#wiz_form").data("installation_status") === "completed"));';
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveBackward()
	{
		return 'var sStatus = $("#wiz_form").data("installation_status"); return ((sStatus === "not started") || (sStatus === "error"));';
	}

}

/**
 * Summary of the installation tasks
 */
class WizStepDone extends WizardStep
{
	public function GetTitle()
	{
		return 'Done';
	}

	public function GetPossibleSteps()
	{
		return array();
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => '', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		// Check if there are some manual steps required:
		$aManualSteps = array();
		$aAvailableModules = SetupUtils::AnalyzeInstallation($this->oWizard);

		$sRootUrl = utils::GetAbsoluteUrlAppRoot(true);
		$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
		foreach($aSelectedModules as $sModuleId)
		{
			if (!empty($aAvailableModules[$sModuleId]['doc.manual_setup']))
			{
				$aManualSteps[$aAvailableModules[$sModuleId]['label']] = $sRootUrl.$aAvailableModules[$sModuleId]['doc.manual_setup'];
			}
		}
		if (count($aManualSteps) > 0)
		{
			$oPage->add("<h2>Manual operations required</h2>");
			$oPage->p("In order to complete the installation, the following manual operations are required:");
			foreach($aManualSteps as $sModuleLabel => $sUrl)
			{
				$oPage->p("<a href=\"$sUrl\" target=\"_blank\">Manual instructions for $sModuleLabel</a>");
			}
			$oPage->add("<h2>Congratulations for installing ".ITOP_APPLICATION."</h2>");
		}
		else
		{
			$oPage->add("<h2>Congratulations for installing ".ITOP_APPLICATION."</h2>");
			$oPage->ok("The installation completed successfully.");
		}

		if (($this->oWizard->GetParameter('mode', '') == 'upgrade') && $this->oWizard->GetParameter('db_backup', false) && $this->oWizard->GetParameter('authent', false))
		{
			$sBackupDestination = $this->oWizard->GetParameter('db_backup_path', '');
			if (file_exists($sBackupDestination.'.tar.gz'))
			{
				// To mitigate security risks: pass only the filename without the extension, the download will add the extension itself
				$oPage->p('Your backup is ready');
				$oPage->p('<a style="background:transparent;" href="'.utils::GetAbsoluteUrlAppRoot(true).'setup/ajax.dataloader.php?operation=async_action&step_class=WizStepDone&params[backup]='.urlencode($sBackupDestination).'&authent='.$this->oWizard->GetParameter('authent','').'" target="_blank"><img src="../images/tar.png" style="border:0;vertical-align:middle;">&nbsp;Download '.basename($sBackupDestination).'</a>');
			}
			else
			{
				$oPage->p('<img src="../images/error.png"/>&nbsp;Warning: Backup creation failed !');
			}
		}

		// Form goes here.. No back button since the job is done !
		$oPage->add('<table id="placeholder" style="width:600px;border:0;padding:0;"><tr>');
		$oPage->add("<td><a style=\"background:transparent;padding:0;\" title=\"Subscribe to Combodo Newsletter.\" href=\"https://www.combodo.com/newsletter-subscription?var_mode=recalcul\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-register.gif\"/></td></a>");
		$oPage->add("<td><a style=\"background:transparent;padding:0;\" title=\"Get Professional Support from Combodo\" href=\"https://support.combodo.com\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-support.gif\"/></td></a>");
		$oPage->add("<td><a style=\"background:transparent;padding:0;\" title=\"Get Professional Training from Combodo\" href=\"http://www.combodo.com/training\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-training.gif\"/></td></a>");
		$oPage->add('</tr></table>');

		$oConfig = new Config(utils::GetConfigFilePath());
		// Load the data model only, in order to load env-production/core/main.php to get the XML parameters (needed by GetModuleSettings below)
		// But main.php may also contain classes (defined without any module), and thus requiring the full data model
		// to be loaded to prevent "class not found" errors...
		$oProductionEnv = new RunTimeEnvironment('production');
		$oProductionEnv->InitDataModel($oConfig, true);
		$sIframeUrl = $oConfig->GetModuleSetting('itop-hub-connector', 'setup_url', '');

		if ($sIframeUrl != '')
		{
			$oPage->add('<iframe id="fresh_content" style="border:0; width:100%; display:none;" src="'.$sIframeUrl.'"></iframe>');

			$oPage->add_script("window.addEventListener('message', function(event) {
				if (event.data === 'itophub_load_completed')
				{
					$('#fresh_content').height($('#placeholder').height());
					$('#placeholder').hide();
					$('#fresh_content').show();
				}
				}, false);
			");
		}

		$sForm = '<form method="post" action="'.$this->oWizard->GetParameter('application_url').'pages/UI.php">';
		$sForm .= '<input type="hidden" name="auth_user" value="'.htmlentities($this->oWizard->GetParameter('admin_user'), ENT_QUOTES, 'UTF-8').'">';
		$sForm .= '<input type="hidden" name="auth_pwd" value="'.htmlentities($this->oWizard->GetParameter('admin_pwd'), ENT_QUOTES, 'UTF-8').'">';
		$sForm .= "<p style=\"text-align:center;width:100%\"><button id=\"enter_itop\" type=\"submit\">Enter ".ITOP_APPLICATION."</button></p>";
		$sForm .= '</form>';
		$sPHPVersion = phpversion();
		$sMySQLVersion = SetupUtils::GetMySQLVersion(
			$this->oWizard->GetParameter('db_server'),
			$this->oWizard->GetParameter('db_user'),
			$this->oWizard->GetParameter('db_pwd'),
			$this->oWizard->GetParameter('db_tls_enabled'),
			$this->oWizard->GetParameter('db_tls_ca')
		);
		$aParameters = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		$sCompactWizChoices = array();
		foreach($aParameters as $iStep => $aChoices)
		{
			$aShortChoices = array();
			foreach($aChoices as $sChoiceCode)
			{
				$sShortCode = str_replace('_', '', $sChoiceCode);
				$aShortChoices[] = $sShortCode;
			}
			$sCompactWizChoices[] = implode(' ',$aShortChoices);
		}
		$sInstallMode = 'i';
		if ($this->oWizard->GetParameter('install_mode', 'install') == 'upgrade')
		{
			if (!$this->oWizard->GetParameter('license'))
			{
				// When the version does not change we don't ask for the licence again
				$sInstallMode = 'r';
			}
			else
			{
				// An actual upgrade
				$sInstallMode = 'u';
			}

		}
		$aUrlParams = array(
			'p' => ITOP_APPLICATION,
			'v' => ITOP_VERSION,
			'php' => $sPHPVersion,
			'mysql' => $sMySQLVersion,
			'os' => PHP_OS,
			's' => ($this->oWizard->GetParameter('sample_data', '') == 'yes') ? 1 : 0 ,
			'l' => $this->oWizard->GetParameter('default_language'),
			'i' => $sInstallMode,
			'w' => json_encode($sCompactWizChoices),
		);
		$aSafeParams = array();
		foreach($aUrlParams as $sCode => $sValue)
		{
			$aSafeParams[] = $sCode.'='.urlencode($sValue);
		}
		$sImgUrl = 'http://www.combodo.com/stats/?'.implode('&', $aSafeParams);

		$aAdditionalModules = array();
		foreach(json_decode($this->oWizard->GetParameter('additional_extensions_modules'), true) as $idx => $aModuleInfo)
		{
			if (in_array('_'.$idx, $aParameters[count($aParameters)-1]))
			{
				// Extensions "choices" can now have more than one module
				foreach($aModuleInfo['modules'] as $sModuleName)
				{
					$aAdditionalModules[] = $sModuleName;
				}
			}
		}
		$idx = 0;
		$aReportedModules = array();
		while($idx < count($aAdditionalModules) && (strlen($sImgUrl.'&m='.urlencode(implode(' ', $aReportedModules))) < 2000)) // reasonable limit for the URL: 2000 chars
		{
			$aReportedModules[] = $aAdditionalModules[$idx];
			$idx++;
		}
		$sImgUrl .= '&m='.urlencode(implode(' ', $aReportedModules));

		$oPage->add('<img style="border:0" src="'.$sImgUrl.'"/>');
		$sForm = addslashes($sForm);
		$oPage->add_ready_script("$('#wiz_form').after('$sForm');");
	}

	public function CanMoveForward()
	{
		return false;
	}
	public function CanMoveBackward()
	{
		return false;
	}

	/**
	 * Tells whether this step of the wizard requires that the configuration file be writable
	 * @return bool True if the wizard will possibly need to modify the configuration at some point
	 */
	public function RequiresWritableConfig()
	{
		return false; //This step executes once the config was written and secured
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		// For security reasons: add the extension now so that this action can be used to read *only* .tar.gz files from the disk...
		$sBackupFile = $aParameters['backup'].'.tar.gz';
		if (file_exists($sBackupFile))
		{
			// Make sure there is NO output at all before our content, otherwise the document will be corrupted
			$sPreviousContent = ob_get_clean();
			$oPage->SetContentType('application/gzip');
			$oPage->SetContentDisposition('attachment', basename($sBackupFile));
			$oPage->add(file_get_contents($sBackupFile));
		}
	}
}

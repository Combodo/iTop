<?php
// Copyright (C) 2012 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * All the steps of the iTop installation wizard
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html GPL
 */

require_once(APPROOT.'setup/setuputils.class.inc.php');
require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/applicationinstaller.class.inc.php');
require_once(APPROOT.'setup/parameters.class.inc.php');

/**
 * First step of the iTop Installation Wizard: Welcome screen
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
		return array('class' => 'WizStepInstallOrUpgrade', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		// Store the misc_options for the future...
		$aMiscOptions = utils::ReadParam('option', array(), false, 'raw_data');
		$sMiscOptions = $this->oWizard->GetParameter('misc_options', json_encode($aMiscOptions));
		$this->oWizard->SetParameter('misc_options', $sMiscOptions);
		
		$oPage->add('<h1>iTop Installation Wizard</h1>');
		$aResults = SetupUtils::CheckPHPVersion($oPage);
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
			$sImage = "stop-mid.png";
			$sTitle = count($aErrors).' Error(s), '.count($aWarnings).' Warning(s).';
		}
		else if (count($aWarnings)> 0)
		{
			$sTitle = count($aWarnings).' Warning(s) '.$sToggleButtons;
			$sImage = "messagebox_warning-mid.png";
		}
		else
		{
			$sTitle = 'Ok. '.$sToggleButtons;
			$sImage = "clean-mid.png";
		}
		$oPage->add('<h2>Prerequisites validation: ');
		$oPage->add("<img style=\"vertical-align:middle;\" src=\"../images/$sImage\"> ");
		$oPage->add($sTitle);
		$oPage->add('</h2>');
		$oPage->add('<div id="details" '.$sStyle.'>');
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

		$this->oWizard->SaveParameter('source_dir', '');
		$this->oWizard->SaveParameter('db_server', '');
		$this->oWizard->SaveParameter('db_user', '');
		$this->oWizard->SaveParameter('db_pwd', '');
		$this->oWizard->SaveParameter('db_name', '');
		$this->oWizard->SaveParameter('db_prefix', '');
		$this->oWizard->SaveParameter('db_backup', false);
		$this->oWizard->SaveParameter('db_backup_path', '');
		
		if ($sInstallMode == 'install')
		{
			$this->oWizard->SetParameter('install_mode', 'install');
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
		$sSourceDir = $this->oWizard->GetParameter('source_dir', '');
		$sDBServer = $this->oWizard->GetParameter('db_server', '');
		$sDBUser = $this->oWizard->GetParameter('db_user', '');
		$sDBPwd = $this->oWizard->GetParameter('db_pwd', '');
		$sDBName = $this->oWizard->GetParameter('db_name', '');
		$sDBPrefix = $this->oWizard->GetParameter('db_prefix', '');
		$bDBBackup = $this->oWizard->GetParameter('db_backup', false);
		$sDBBackupPath = $this->oWizard->GetParameter('db_backup_path', '');
		if ($sInstallMode == '')
		{
			$sDBBackupPath = APPROOT.'data/'.ITOP_APPLICATION.strftime('-backup-%Y-%m-%d.zip');
			$bDBBackup = true;
			$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
			if ($aPreviousInstance['found'])
			{
				$sInstallMode = 'upgrade';
				$sSourceDir = APPROOT;
				$sDBServer = $aPreviousInstance['db_server'];
				$sDBUser = $aPreviousInstance['db_user'];
				$sDBPwd = $aPreviousInstance['db_pwd'];
				$sDBName = $aPreviousInstance['db_name'];
				$sDBPrefix = $aPreviousInstance['db_prefix'];
				//TODO: check if we can run the backup
				$sStyle = '';
			}
			else
			{
				$sInstallMode = 'install';
			}
		}
		
		$sUpgradeInfoStyle = '';
		if ($sInstallMode == 'install')
		{
			$sUpgradeInfoStyle = ' style="display: none;" ';
		}
		$oPage->add('<h2>What do you want to do?</h2>');
		$sChecked = ($sInstallMode == 'install') ? ' checked ' : '';
		$oPage->p('<input id="radio_install" type="radio" name="install_mode" value="install"'.$sChecked.'/><label for="radio_install">&nbsp;Install a new '.ITOP_APPLICATION.'</label>');
		$sChecked = ($sInstallMode == 'upgrade') ? ' checked ' : '';
		$oPage->p('<input id="radio_update" type="radio" name="install_mode" value="upgrade"'.$sChecked.'/><label for="radio_update">&nbsp;Upgrade an existing '.ITOP_APPLICATION.' instance</label>');
		//$oPage->add('<fieldset  id="upgrade_info"'.$sUpgradeInfoStyle.'>');
		//$oPage->add('<legend>Information about the previous instance:</legend>');
		$oPage->add('<table id="upgrade_info"'.$sUpgradeInfoStyle.'>');
		$oPage->add('<tr><td>Location on the disk:</td><td><input id="source_dir" type="text" name="source_dir" value="'.htmlentities($sSourceDir, ENT_QUOTES, 'UTF-8').'" size="25"/></td></tr>');
		SetupUtils::DisplayDBParameters($oPage, false, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix);

		$sChecked = ($bDBBackup == 'install') ? ' checked ' : '';
		$oPage->add('<tr><td style="text-align:right"><input id="db_backup" type="checkbox" name="db_backup"'.$sChecked.' value="1"/></td><td>Backup the '.ITOP_APPLICATION.' database before upgrading</td></tr>');
		$oPage->add('<tr><td>Save the backup to:</td><td><input id="db_backup_path" type="text" name="db_backup_path" value="'.htmlentities($sDBBackupPath, ENT_QUOTES, 'UTF-8').'" size="25"/></td></tr>');
		$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
		$sMessage = '';
		if ($fFreeSpace !== false)
		{
			$sMessage = SetupUtils::HumanReadableSize($fFreeSpace).' free in '.dirname($sDBBackupPath);
		}
		$oPage->add('<tr><td>&nbsp;</td>');
		$oPage->add('<td><span id="backup_info" style="font-size:small;color:#696969;">'.$sMessage.'</span></td></tr>');
		$oPage->add('</table>');
		//$oPage->add('</fieldset>');
		$oPage->add_ready_script(
<<<EOF
	$("#radio_update").bind('change', function() { if (this.checked ) { $('#upgrade_info').show(); WizardUpdateButtons(); } else { $('#upgrade_info').hide(); } });
	$("#radio_install").bind('change', function() { if (this.checked ) { $('#upgrade_info').hide(); WizardUpdateButtons(); } else { $('#upgrade_info').show(); } });
	$("#source_dir").bind('change keyup', function() { WizardAsyncAction('check_path', { source_dir: $('#source_dir').val() }); });
	$("#db_backup_path").bind('change keyup', function() { WizardAsyncAction('check_backup', { db_backup_path: $('#db_backup_path').val() }); });
EOF
		);
	}
	
	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch($sCode)
		{
			case 'check_path':
			$sSourceDir = $aParameters['source_dir'];
			$aPreviousInstance = SetupUtils::GetPreviousInstance($sSourceDir);
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
		if ($("#radio_install").attr("checked") == "checked")
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
	public function GetTitle()
	{
		return 'Detected Info';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeKeep', 'WizStepUpgradeAuto', 'WizStepLicense2');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepLicense2', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Info about the detected version');
	}
}

/**
 * Keep or Upgrade choice
 */
class WizStepUpgradeKeep extends WizardStep
{
	public function GetTitle()
	{
		return 'Keep or Upgrade';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepModulesChoice', 'state' => 'start_upgrade');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Keep or Upgrade the data model');
	}
}

/**
 * Automatic Upgrade info
 */
class WizStepUpgradeAuto extends WizardStep
{
	public function GetTitle()
	{
		return 'Upgrade Information';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepModulesChoice', 'state' => 'start_upgrade');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Automatic Upgrade information');
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
	
	public function Display(WebPage $oPage)
	{
		$aLicenses = array();
		foreach (glob(APPROOT.'setup/licenses/*.xml') as $sFile)
		{
    		$oXml = simplexml_load_file($sFile);
    		foreach($oXml->license as $oLicense)
    		{
    			$aLicenses[] = $oLicense;
    		}
		}
		
		$oPage->add('<h2>Licenses agreements for the components of '.ITOP_APPLICATION.'</h2>');
		$oPage->add_style('div a.no-arrow { background:transparent; padding-left:0;}');
		$oPage->add_style('.toggle { cursor:pointer; text-decoration:underline; color:#1C94C4; }');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Components of '.ITOP_APPLICATION.'</legend>');
		$oPage->add('<ul>');
		foreach($aLicenses as $index => $oLicense)
		{
			$oPage->add('<li><b>'.$oLicense->product.'</b>, licensed by '.$oLicense->author.' under the <b>'.$oLicense->license_type.' license</b>. (<span class="toggle" id="toggle_'.$index.'">Details</span>)');
			$oPage->add('<div id="license_'.$index.'" class="license_text" style="display:none;overflow:auto;max-height:10em;font-size:small;border:1px #696969 solid;margin-bottom:1em; margin-top:0.5em;padding:0.5em;">'.$oLicense->text.'<div>');
			$oPage->add_ready_script('$(".license_text a").attr("target", "_blank").addClass("no-arrow");');
			$oPage->add_ready_script('$("#toggle_'.$index.'").click( function() { $("#license_'.$index.'").toggle(); } );');
		}
		$oPage->add('</ul>');
		$oPage->add('</fieldset>');
		$sChecked = ($this->oWizard->GetParameter('accept_license', 'no') == 'yes') ? ' checked ' : ''; 
		$oPage->p('<input type="checkbox" name="accept_license" id="accept" value="yes"'.$sChecked.'><label for="accept">&nbsp;I accept the terms of the licenses of the '.count($aLicenses).' components mentioned above.</label>');
		$oPage->add_ready_script('$("#accept").bind("click change", function() { WizardUpdateButtons(); });');
	}
	
	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return ($("#accept").attr("checked") === "checked");';
	}
	
}

/**
 * License acceptation screen (when upgrading)
 */
class WizStepLicense2 extends WizStepLicense
{
	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeKeep', 'WizStepUpgradeAuto');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepUpgradeAuto', 'state' => '');
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
		$sNewDBName = $this->oWizard->GetParameter('db_new_name', false);
		
		$oPage->add('<table>');
		SetupUtils::DisplayDBParameters($oPage, true, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sNewDBName);
		$oPage->add('</table>');
		$sCreateDB = $this->oWizard->GetParameter('create_db', 'no');
		if ($sCreateDB == 'no')
		{
			$oPage->add_ready_script('$("#existing_db").attr("checked", "checked");');
		}
		else
		{
			$oPage->add_ready_script('$("#create_db").attr("checked", "checked");');
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
		$sAdminUser = $this->oWizard->GetParameter('admin_user', '');
		$sAdminPwd = $this->oWizard->GetParameter('admin_pwd', '');
		$sConfirmPwd = $this->oWizard->GetParameter('confirm_pwd', '');
		$sAdminLanguage = $this->oWizard->GetParameter('admin_language', 'EN US');
		$oPage->add('<h2>Definition of the Administrator Account</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Administrator Account</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>Login: </td><td><input id="admin_user" name="admin_user" type="text" size="25" maxlength="64" value="'.htmlentities($sAdminUser, ENT_QUOTES, 'UTF-8').'"></td><tr>');
		$oPage->add('<tr><td>Password: </td><td><input id="admin_pwd" name="admin_pwd" type="password" size="25" maxlength="64" value="'.htmlentities($sAdminPwd, ENT_QUOTES, 'UTF-8').'"></td><tr>');
		$oPage->add('<tr><td>Confirm password: </td><td><input id="confirm_pwd" name="confirm_pwd" type="password" size="25" maxlength="64" value="'.htmlentities($sConfirmPwd, ENT_QUOTES, 'UTF-8').'"></td><tr>');
		$sSourceDir = APPROOT.'dictionaries/';
		$aLanguages = SetupUtils::GetAvailableLanguages($sSourceDir);
		$oPage->add('<tr><td>Language: </td><td>');
		$oPage->add(SetupUtils::GetLanguageSelect($sSourceDir, 'admin_language', $sAdminLanguage));		
		$oPage->add('</td></tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
	}
}

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
		$this->oWizard->SaveParameter('sample_data', 'yes');
		return array('class' => 'WizStepModulesChoice', 'state' => 'start_install');
	}
	
	public function Display(WebPage $oPage)
	{
		$sDefaultLanguage = $this->oWizard->GetParameter('default_language', $this->oWizard->GetParameter('admin_language'));
		$sApplicationURL = $this->oWizard->GetParameter('application_url', utils::GetDefaultUrlAppRoot());
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
		$oPage->add('<tr><td>URL: </td><td><input id="application_url" name="application_url" type="text" size="35" maxlength="1024" value="'.htmlentities($sApplicationURL, ENT_QUOTES, 'UTF-8').'"></td><tr>');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Sample Data</legend>');
		$oPage->add('<table>');
		$sChecked = ($sSampleData == 'yes') ? ' checked ' : '';
		$oPage->p('<input id="sample_data_yes" name="sample_data" type="radio" value="yes"'.$sChecked.'><label for="sample_data_yes">&nbsp;I am installing a <b>demo or test</b> instance, populate the database with some demo data.');
		$sChecked = ($sSampleData == 'no') ? ' checked ' : '';
		$oPage->p('<input id="sample_data_no" name="sample_data" type="radio" value="no"'.$sChecked.'><label for="sample_data_no">&nbsp;I am installing a <b>production</b> instance, create an empty database to start from.');
		$oPage->add('</table>');
		$oPage->add('</fieldset>');
	}
}

/**
 * Choice of the modules to be installed
 */
class WizStepModulesChoice extends WizardStep
{
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
		// Accumualtes the selected modules:
		$index = $this->GetStepIndex();
		
		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aSelectedChoices = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		$aSelected = utils::ReadParam('choice', array());
		$aSelectedChoices[$index] = $aSelected;
		$this->oWizard->SetParameter('selected_components', json_encode($aSelectedChoices, JSON_FORCE_OBJECT));
		
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
				for($i = 0; $i <= $index; $i++)
				{
					$aStepInfo = $this->GetStepInfo($i);
					$this->GetSelectedModules($aStepInfo, $aSelectedChoices[$i], $aModules);
				}
				$this->oWizard->SetParameter('selected_modules', json_encode(array_keys($aModules)));
				return array('class' => 'WizStepSummary', 'state' => '');
			}
			
		}
	}
	
	public function Display(WebPage $oPage)
	{
		$this->DisplayStep($oPage);
	}
	
	protected function DisplayStep($oPage)
	{
		$aStepInfo = $this->GetStepInfo();
		$oPage->add_style("div.choice { margin: 0.5em;}");
		$oPage->add_style("div.description { margin-left: 2em; }");
		$oPage->add('<table><tr>');
		$sBannerPath = isset($aStepInfo['banner']) ? $aStepInfo['banner'] : '';
		if (!empty($sBannerPath))
		{
			if (substr($sBannerPath, 0, 1) == '/')
			{
				// absolute path, means relative to APPROOT
				$sBannerUrl = utils::GetDefaultUrlAppRoot().$sBannerPath;
			}
			else
			{
				// relative path: i.e. relative to the directory containing the XML file
				$sFullPath = dirname($this->GetSourceFilePath()).'/'.$sBannerPath;
				$sRealPath = realpath($sFullPath);
				$sBannerUrl = utils::GetDefaultUrlAppRoot().str_replace(APPROOT, '', $sRealPath);
			}
			$oPage->add('<td><img src="'.$sBannerUrl.'"/><td>');
		}
		$sDescription = isset($aStepInfo['description']) ? $aStepInfo['description'] : '';
		$oPage->add('<td>'.$sDescription.'<td>');
		$oPage->add('</tr></table>');
		
		// Build the default choices
		$aDefaults = array();
		$this->GetDefaults($aStepInfo, $aDefaults);
		$index = $this->GetStepIndex();
		
		// retrieve the saved selection
		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aParameters = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		if (!isset($aParameters[$index]))
		{
			$aParameters[$index] = $aDefaults;
		}
		$aSelectedComponents = $aParameters[$index];

		$oPage->add('<div style="max-height:250px;overflow:auto;border:#ccc 1px solid;">');
		$this->DisplayOptions($oPage, $aStepInfo, $aSelectedComponents);
		$oPage->add('</div>');
	}
	
	protected function GetDefaults($aInfo, &$aDefaults, $sParentId = '')
	{
		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : array();
		foreach($aOptions as $index => $aChoice)
		{
			$sChoiceId = $sParentId.'_'.$index;
			if (isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceId] = $sChoiceId;
			}
			if (isset($aChoice['sub_options']))
			{
				$this->GetDefaults($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
			$index++;
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : array();
		$sChoiceName = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.'_'.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId;
			}
			if (isset($aChoice['default']) && $aChoice['default'])
			{
				$aDefaults[$sChoiceName] = $sChoiceId;
			}
			if (isset($aChoice['sub_options']))
			{
				$this->GetDefaults($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
			$index++;
		}
	} 
	
	/**
	 * Converts the list of selected "choices" into a list of "modules": take into account the selected and the mandatory modules
	 * @param hash $aInfo Info about the "choice" array('options' => array(...), 'alternatives' => array(...))
	 * @param hash $aSelectedChoices List of selected choices array('name' => 'selected_value_id')
	 * @param hash $aModules Return parameter: List of selected modules array('module_id' => true)
	 * @param string $sParentId Used for recursion
	 * @return void
	 */
	protected function GetSelectedModules($aInfo, $aSelectedChoices, &$aModules, $sParentId = '')
	{
		if ($sParentId == '')
		{
			// Check once (before recursing) that the hidden modules are selected
			foreach($this->AnalyzeInstallation() as $sModuleId => $aModule)
			{
				if ($sModuleId != ROOT_MODULE)
				{
					if (($aModule['category'] == 'authentication') || (!$aModule['visible']))
					{
						$aModules[$sModuleId] = true;
					}
				}				
			}
			
		}
		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : array();
		foreach($aOptions as $index => $aChoice)
		{
			$sChoiceId = $sParentId.'_'.$index;
			if ( (isset($aChoice['mandatory']) && $aChoice['mandatory']) || 
				 (isset($aSelectedChoices[$sChoiceId]) && ($aSelectedChoices[$sChoiceId] == $sChoiceId)) )
			{
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
					$this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId);
				}
			}
			$index++;
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : array();
		$sChoiceName = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sChoiceId = $sParentId.'_'.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId;
			}
			if ( (isset($aChoice['mandatory']) && $aChoice['mandatory']) || 
				 (isset($aSelectedChoices[$sChoiceName]) && ($aSelectedChoices[$sChoiceName] == $sChoiceId)) )
			{
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
					$this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId);
				}
			}
			$index++;
		}
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
		if (@file_exists($this->GetSourceFilePath()))
		{
			$aParams = new XMLParameters($this->GetSourceFilePath());
			$aSteps = $aParams->Get('steps', array());
			if (array_key_exists($index, $aSteps))
			{
				$aStepInfo = $aSteps[$index];
			}
		}
		else if ($idx == 0)
		{
			// No wizard configuration provided, build a standard one:
			$aAvailableModules = $this->AnalyzeInstallation();
			$aStepInfo = array(
				'title' => 'Modules Selection',
				'description' => '<h2>Select the modules to install. You can launch the installation again to install new modules, but you cannot remove already installed modules.</h2>',
				'banner' => '/images/modules.png',
				'options' => array(),
			);
			
			try
			{
				$sDefaultAppPath = 	utils::GetDefaultUrlAppRoot();		
			}
			catch(Exception $e)
			{
				$sDefaultAppPath = '..';
			}
		
			foreach($aAvailableModules as $sModuleId => $aModule)
			{
				if ($sModuleId == ROOT_MODULE) continue; // Convention: the version number of the application is stored as a module named ROOT_MODULE
		
				$sModuleLabel = $aModule['label'];
				$sModuleHelp = $aModule['doc.more_information'];
				$sMoreInfo = (!empty($aModule['doc.more_information'])) ? "<a href=\"$sDefaultAppPath{$aModule['doc.more_information']}\" target=\"_blank\">more info</a>": '';
				if (($aModule['category'] != 'authentication') && ($aModule['visible']))
				{
					$aStepInfo['options'][$index] = array(
						'title' => $sModuleLabel,
						'description' => '',
						'more_info' => $sMoreInfo,
						'default' => true, // by default offer to install all modules
						'modules' => array($sModuleId),
					);
					
					switch($aModule['install']['flag'])
					{
						case MODULE_ACTION_MANDATORY:
						$aStepInfo['options'][$index]['mandatory']  = true;
						break;
						
					}
					$index++;
				}
			}
		}
		return $aStepInfo;
	}
	
	protected function AnalyzeInstallation()
	{
		require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
		$oConfig = new Config();
		
		$aParamValues = array(
			'db_server' => $this->oWizard->GetParameter('db_server', ''),
			'db_user' => $this->oWizard->GetParameter('db_user', ''),
			'db_pwd' => $this->oWizard->GetParameter('db_server', ''),
			'db_name' => $this->oWizard->GetParameter('db_name', ''),
			'db_prefix' => $this->oWizard->GetParameter('db_prefix', ''),
			'source_dir' => APPROOT.'datamodel',
		);
		$oConfig->UpdateFromParams($aParamValues, 'datamodel');

		$oProductionEnv = new RunTimeEnvironment();
		$oConfig = new Config();
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, 'datamodel');

		return $aAvailableModules;
	}
		
	protected function DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $sParentId = '')
	{
		$aOptions = isset($aStepInfo['options']) ? $aStepInfo['options'] : array();
		$aAlternatives = isset($aStepInfo['alternatives']) ? $aStepInfo['alternatives'] : array();
		$index = 0;
		
		foreach($aOptions as $index => $aChoice)
		{
			$sAttributes = '';
			$sChoiceId = $sParentId.'_'.$index;
			if (isset($aChoice['mandatory']) && $aChoice['mandatory'])
			{
				$oPage->add('<div class="choice"><input id="choice'.$sChoiceId.'" checked disabled type="checkbox"'.$sAttributes.'/><input type="hidden" name="choice['.$sChoiceId.']" value="'.$sChoiceId.'">&nbsp;');
			}
			else if (isset($aSelectedComponents[$sChoiceId]) && ($aSelectedComponents[$sChoiceId] == $sChoiceId))
			{
				$oPage->add('<div class="choice"><input id="choice'.$sChoiceId.'" name="choice['.$sChoiceId.']" type="checkbox" checked value="'.$sChoiceId.'"/>&nbsp;');
			}
			else
			{
				$oPage->add('<div class="choice"><input id="choice'.$sChoiceId.'" name="choice['.$sChoiceId.']" type="checkbox" value="'.$sChoiceId.'"/>&nbsp;');
			}
			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $sChoiceId);
			$oPage->add('</div>');
			$index++;
		}
		$sChoiceName = null;
		foreach($aAlternatives as $index => $aChoice)
		{
			$sAttributes = '';
			$sChoiceId = $sParentId.'_'.$index;
			if ($sChoiceName == null)
			{
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			
			if (isset($aSelectedComponents[$sChoiceName]) && ($aSelectedComponents[$sChoiceName] == $sChoiceId))
			{
				$sAttributes = ' checked ';
			}
			$oPage->add('<div class="choice"><input id="choice'.$sChoiceId.'" name="choice['.$sChoiceName.']" type="radio"'.$sAttributes.' value="'.$sChoiceId.'"/>&nbsp;');
			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $sChoiceId);
			$oPage->add('</div>');
			$index++;
		}
	}
	
	protected function DisplayChoice($oPage, $aChoice, $aSelectedComponents, $sChoiceId)
	{
		$oPage->add('<label for="choice'.$sChoiceId.'"><b>'.htmlentities($aChoice['title'], ENT_QUOTES, 'UTF-8').'</b></label>');
		$sDescription = isset($aChoice['description']) ? htmlentities($aChoice['description'], ENT_QUOTES, 'UTF-8') : '';
		$oPage->add('<div class="description">'.$sDescription);
		if (isset($aChoice['sub_options']))
		{
			$this->DisplayOptions($oPage, $aChoice['sub_options'], $aSelectedComponents, $sChoiceId);
		}
		$oPage->add('</div>');
	}
	
	protected function GetSourceFilePath()
	{
		return APPROOT.'datamodel/installation.xml';
	}
	
}

/**
 * Summary of the installation tasks
 */
class WizStepSummary extends WizardStep
{
	public function GetTitle()
	{
		return 'Installation summary';
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
		
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepDone', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->add('<ul>Selected modules:');
		$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'));
		sort($aSelectedModules);
		foreach($aSelectedModules as $sModuleId)
		{
			$oPage->add('<li>'.$sModuleId.'</li>');
			
		}
		$oPage->add('</ul>');
		$oPage->add('<div id="percentage"> Ready to Start...<br/></div>');
		$oPage->add_ready_script(
<<<EOF
	$("#btn_next").bind("click.install", function(event){ $(this).attr("disabled", "disabled"); event.preventDefault(); ExecuteStep(""); });
	$("#wiz_form").data("installation_status", "not started")
EOF
		);
		
		// Prepare the parameters to execute the installation asynchronously
		
		$sMode = $this->oWizard->GetParameter('mode', 'install');
		$sBackupDestination = '';
		$sConfigurationFile = '';
		$sDBName = $this->oWizard->GetParameter('db_name');
		if ($sMode == 'upgrade')
		{
			if ($this->oWizard->GetParameter('db_backup', false))
			{
				$sSourceDir = $this->oWizard->GetParameter('source_dir', '');
				if (!empty($sSourceDir))
				{
					$aPreviousInstance = SetupUtils::GetPreviousInstance($sSourceDir);
					if ($aPreviousInstance['found'])
					{
						$sConfigurationFile = $aPreviousInstance['configuration_file'];
					}
				}
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
		
		$aInstallParams = array (
		  'mode' => $sMode,
		  'preinstall' => array (
		    'copies' => array (
//		      0 => array (
//		        'source' => '',
//		        'destination' => '',
//		      ),
		    ),
		  ),
		  'source_dir' => 'datamodel',
		  'target_env' => 'production',
		  'workspace_dir' => '',
		  'database' => array (
		    'server' => $this->oWizard->GetParameter('db_server'),
		    'user' => $this->oWizard->GetParameter('db_user'),
		    'pwd' => $this->oWizard->GetParameter('db_pwd'),
		    'name' => $sDBName,
		    'prefix' => $this->oWizard->GetParameter('db_prefix'),
		  ),
		  'url' => $this->oWizard->GetParameter('application_url'),
		  'admin_account' => array (
		    'user' => $this->oWizard->GetParameter('admin_user'),
		    'pwd' => $this->oWizard->GetParameter('admin_pwd'),
		    'language' => $this->oWizard->GetParameter('admin_language'),
		  ),
		  'language' => $this->oWizard->GetParameter('default_language'),
		  'selected_modules' =>  $aSelectedModules,
		  'sample_data' => ($this->oWizard->GetParameter('sample_data', '') == 'yes') ? true : false ,
		  'options' => json_decode($this->oWizard->GetParameter('misc_options')),
		);

		if ($sBackupDestination != '')
		{
			$aInstallParams['backup'] = array (
			      'destination' => $sBackupDestination,
			      'configuration_file' => $sConfigurationFile,
			);
		}
		
		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.htmlentities($sJSONData, ENT_QUOTES, 'UTF-8').'"/>');
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
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "running");
	WizardUpdateButtons();
	$("#percentage").html('{$aRes['percentage-completed']} % completed<br/>{$aRes['next-step-label']}');
	ExecuteStep('{$aRes['next-step']}');
EOF
		);
		}
		else if ($aRes['next-step'] == '')
		{
			// Installation complete, move to the next step of the wizard
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "completed");
	WizardUpdateButtons();
EOF
			);
		}
		else
		{
			$oPage->add_ready_script(
<<<EOF
	$("#wiz_form").data("installation_status", "error");
	WizardUpdateButtons();
	$("#percentage").html('<b>Error</b><br/>{$aRes['message']}');
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
		return 'return ($("#wiz_form").data("installation_status") === "not started");';
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
		$oPage->p('Installation Completed.');
	}
	
	public function CanMoveForward()
	{
		return false;
	}
	public function CanMoveBackward()
	{
		return false;
	}
}

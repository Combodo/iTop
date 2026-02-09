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
		return ['WizStepUpgradeMiscParams', 'WizStepLicense2'];
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sUpgradeType = utils::ReadParam('upgrade_type');

		$this->oWizard->SetParameter('mode', 'upgrade');
		$this->oWizard->SetParameter('upgrade_type', $sUpgradeType);
		$bDisplayLicense = $this->oWizard->GetParameter('display_license');

		switch ($sUpgradeType) {
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
		if ($bDisplayLicense) {
			$aRet = ['class' => 'WizStepLicense2', 'state' => ''];
		} else {
			$aRet = ['class' => 'WizStepUpgradeMiscParams', 'state' => ''];
		}
		return $aRet;
	}

	/**
	 * @param WebPage $oPage
	 *
	 * @throws Exception
	 */
	public function Display(WebPage $oPage)
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

		if ($aInstalledInfo === false) {
			throw(new Exception('No previous version of '.ITOP_APPLICATION.' found in the supplied database. The upgrade cannot continue.'));
		} elseif (strcasecmp($aInstalledInfo['product_name'], ITOP_APPLICATION) != 0) {
			$oPage->p("<b>Warning: The installed products seem different. Are you sure that you want to upgrade {$aInstalledInfo['product_name']} with ".ITOP_APPLICATION."?</b>");
		}

		$sInstalledVersion = $aInstalledInfo['product_version'];
		$sInstalledDataModelVersion = $aInstalledInfo['datamodel_version'];

		$oPage->add("<h2>Information about the upgrade from version $sInstalledVersion to ".ITOP_VERSION_FULL."</h2>");

		if ($sInstalledVersion == ITOP_VERSION_FULL) {
			// Reinstalling the same version let's skip the license agreement...
			$bDisplayLicense = false;
		}
		$this->oWizard->SetParameter('license', $bDisplayLicense); // Remember for later

		$sCompatibleDMDir = SetupUtils::GetLatestDataModelDir();
		if ($sCompatibleDMDir === false) {
			// No compatible version exists... cannot upgrade. Either it is too old, or too new (downgrade !)
			$this->bCanMoveForward = false;
			$oPage->p("No datamodel directory found.");
		} else {
			$sUpgradeDMVersion = SetupUtils::GetDataModelVersion($sCompatibleDMDir);
			$sPreviousSourceDir = isset($aInstalledInfo['source_dir']) ? $aInstalledInfo['source_dir'] : 'modules';
			$aChanges = false;
			if (is_dir($sPreviousVersionDir)) {
				// Check if the previous version is a "genuine" one or not...
				$aChanges = SetupUtils::CheckVersion($sInstalledDataModelVersion, $sPreviousVersionDir.'/'.$sPreviousSourceDir);
			}
			if (($aChanges !== false) && ((count($aChanges['added']) > 0) || (count($aChanges['removed']) > 0) || (count($aChanges['modified']) > 0))) {
				// Some changes were detected, prompt the user to keep or discard them
				$oPage->p("<img src=\"../images/error.png\"/>&nbsp;Some modifications were detected between the ".ITOP_APPLICATION." version in '$sPreviousVersionDir' and a genuine $sInstalledVersion version.");
				$oPage->p("What do you want to do?");

				$aWritableDirs = ['modules', 'portal'];
				$aErrors = SetupUtils::CheckWritableDirs($aWritableDirs);
				$sChecked = ($this->oWizard->GetParameter('upgrade_type') == 'keep-previous') ? ' checked ' : '';
				$sDisabled = (count($aErrors) > 0) ? ' disabled ' : '';

				$oPage->p('<input id="radio_upgrade_keep" type="radio" name="upgrade_type" value="keep-previous" '.$sChecked.$sDisabled.'/><label for="radio_upgrade_keep">&nbsp;Preserve the modifications of the installed version (the dashboards inside '.ITOP_APPLICATION.' may not be editable).</label>');
				$oPage->add('<input type="hidden" name="datamodel_previous_version" value="'.utils::EscapeHtml($sInstalledDataModelVersion).'">');

				$oPage->add('<input type="hidden" name="relative_source_dir" value="'.utils::EscapeHtml($sPreviousSourceDir).'">');

				if (count($aErrors) > 0) {
					$oPage->p("Cannot copy the installed version due to the following access rights issue(s):");
					foreach ($aErrors as $sDir => $oCheckResult) {
						$oPage->p('<img src="../images/error.png"/>&nbsp;'.$oCheckResult->sLabel);
					}
				}

				$sChecked = ($this->oWizard->GetParameter('upgrade_type') == 'use-compatible') ? ' checked ' : '';

				$oPage->p('<input id="radio_upgrade_convert" type="radio" name="upgrade_type" value="use-compatible" '.$sChecked.'/><label for="radio_upgrade_convert">&nbsp;Discard the modifications, use a standard '.$sUpgradeDMVersion.' data model.</label>');

				$oPage->add('<input type="hidden" name="datamodel_path" value="'.utils::EscapeHtml($sCompatibleDMDir).'">');
				$oPage->add('<input type="hidden" name="datamodel_version" value="'.utils::EscapeHtml($sUpgradeDMVersion).'">');

				$oPage->add('<div id="changes_summary"><div class="closed"><span class="title">Details of the modifications</span><div>');
				if (count($aChanges['added']) > 0) {
					$oPage->add('<ul>New files added:');
					foreach ($aChanges['added'] as $sFilePath => $void) {
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				if (count($aChanges['removed']) > 0) {
					$oPage->add('<ul>Deleted files:');
					foreach ($aChanges['removed'] as $sFilePath => $void) {
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				if (count($aChanges['modified']) > 0) {
					$oPage->add('<ul>Modified files:');
					foreach ($aChanges['modified'] as $sFilePath => $void) {
						$oPage->add('<li>'.$sFilePath.'</li>');
					}
					$oPage->add('</ul>');
				}
				$oPage->add('</div></div></div>');
			} else {
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
	$("#changes_summary .title").on('click', function() { $(this).parent().toggleClass('closed'); } );
	$('input[name=upgrade_type]').on('click change', function() { WizardUpdateButtons(); });
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
			if ($oMutex->IsLocked()) {
				$oPage->add('<div class="message">'.ITOP_APPLICATION.' cron process is being executed on the target database. '.ITOP_APPLICATION.' cron process will be stopped during the setup execution.</div>');
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

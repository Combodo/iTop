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
class WizStepDone extends WizardStep
{
	public function GetTitle()
	{
		return 'Done';
	}

	public function GetPossibleSteps()
	{
		return [];
	}

	public function ProcessParams($bMoveForward = true)
	{
		return ['class' => '', 'state' => ''];
	}

	public function Display(WebPage $oPage)
	{
		// Check if there are some manual steps required:
		$aManualSteps = [];
		$aAvailableModules = SetupUtils::AnalyzeInstallation($this->oWizard);

		$sRootUrl = utils::GetAbsoluteUrlAppRoot(true);
		$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
		foreach ($aSelectedModules as $sModuleId) {
			if (!empty($aAvailableModules[$sModuleId]['doc.manual_setup'])) {
				$sUrl = $aAvailableModules[$sModuleId]['doc.manual_setup'];
				$sManualStepUrl = utils::IsURL($sUrl) ? $sUrl : $sRootUrl.$sUrl;
				$aManualSteps[$aAvailableModules[$sModuleId]['label']] = $sManualStepUrl;
			}
		}
		$oPage->add('<div class="ibo-is-html-content">');
		if (count($aManualSteps) > 0) {
			$oPage->add("<h2>Manual operations required</h2>");
			$oPage->p("In order to complete the installation, the following manual operations are required:");
			foreach ($aManualSteps as $sModuleLabel => $sUrl) {
				$oPage->p("<a href=\"$sUrl\" target=\"_blank\">Manual instructions for $sModuleLabel</a>");
			}
			$oPage->add("<h2>Congratulations for installing ".ITOP_APPLICATION."</h2>");
		} else {
			$oPage->add("<h2>Congratulations for installing ".ITOP_APPLICATION."</h2>");
			$oPage->ok("The installation completed successfully.");
		}

		$bHasBackup = false;
		if (($this->oWizard->GetParameter('mode', '') == 'upgrade') && $this->oWizard->GetParameter('db_backup', false) && $this->oWizard->GetParameter('authent', false)) {
			$sBackupDestination = $this->oWizard->GetParameter('db_backup_path', '');
			if (file_exists($sBackupDestination.'.tar.gz')) {
				$bHasBackup = true;
				// To mitigate security risks: pass only the filename without the extension, the download will add the extension itself
				$oPage->p('Your backup is ready');
				$oPage->p('<a style="background:transparent;" href="'.utils::GetAbsoluteUrlAppRoot(true).'setup/ajax.dataloader.php?operation=async_action&step_class=WizStepDone&params[backup]='.urlencode($sBackupDestination).'&authent='.$this->oWizard->GetParameter('authent', '').'" target="_blank"><img src="../images/icons/icons8-archive-folder.svg" style="border:0;vertical-align:middle;">&nbsp;Download '.basename($sBackupDestination).'</a>');
			} else {
				$oPage->p('<img src="../images/error.png"/>&nbsp;Warning: Backup creation failed !');
			}
		}

		// Form goes here.. No back button since the job is done !
		$oPage->add('<div id="placeholder" class="setup-end-placeholder">');
		$oPage->add("<div><a class=\"ibo-svg-illustration--container\" title=\"Subscribe to Combodo Newsletter.\" href=\"https://www.combodo.com/newsletter-subscription?var_mode=recalcul\" target=\"_blank\">".file_get_contents(APPROOT.'images/illustrations/undraw_newsletter.svg')." Register now</a></div>");
		$oPage->add("<div><a class=\"ibo-svg-illustration--container\"  title=\"Get Professional Support from Combodo\" href=\"https://support.combodo.com\" target=\"_blank\">".file_get_contents(APPROOT.'images/illustrations/undraw_active_support.svg')."Get professional support</a></div>");
		$oPage->add("<div><a class=\"ibo-svg-illustration--container\" title=\"Get Professional Training from Combodo\" href=\"http://www.combodo.com/training\" target=\"_blank\">".file_get_contents(APPROOT.'images/illustrations/undraw_education.svg')."Get professional training</a></div>");
		$oPage->add('</div>');

		$oPage->add('</div>');

		$oConfig = new Config(utils::GetConfigFilePath());
		$aParamValues = $this->oWizard->GetParamForConfigArray();
		$oConfig->UpdateFromParams($aParamValues);
		// Load the data model only, in order to load env-production/core/main.php to get the XML parameters (needed by GetModuleSettings below)
		// But main.php may also contain classes (defined without any module), and thus requiring the full data model
		// to be loaded to prevent "class not found" errors...
		$oProductionEnv = new RunTimeEnvironment('production');
		$oProductionEnv->InitDataModel($oConfig, true);
		$sIframeUrl = $oConfig->GetModuleSetting('itop-hub-connector', 'setup_url', '');

		$sSetupTokenFile = APPROOT.'data/.setup';
		$sSetupToken = bin2hex(random_bytes(12));
		file_put_contents($sSetupTokenFile, $sSetupToken);
		$sIframeUrl .= "&setup_token=$sSetupToken";

		if ($sIframeUrl != '') {
			$oPage->add('<iframe id="fresh_content" frameborder="0" scrolling="auto" src="'.$sIframeUrl.'"></iframe>');

			$oPage->add_script("
			window.addEventListener('message', function(event) {
				if (event.data === 'itophub_load_completed')
				{
					$('#placeholder').hide();
					$('#fresh_content').show();
				}
				}, false);
			");
		}

		$sForm = '<div class="ibo-setup--wizard--buttons-container" style="text-align:center"><form method="post" class="ibo-setup--enter-itop" action="'.$this->oWizard->GetParameter('application_url').'pages/UI.php">';
		$sForm .= '<input type="hidden" name="auth_user" value="'.utils::EscapeHtml($this->oWizard->GetParameter('admin_user')).'">';
		$sForm .= '<input type="hidden" name="auth_pwd" value="'.utils::EscapeHtml($this->oWizard->GetParameter('admin_pwd')).'">';
		$sForm .= "<button id=\"enter_itop\" class=\"ibo-button ibo-is-regular ibo-is-primary\" type=\"submit\">Enter ".ITOP_APPLICATION."</button></div>";
		$sForm .= '</form>';

		$sForm = addslashes($sForm);
		$oPage->add_ready_script("$('#wiz_form').append('$sForm');");
		// avoid leaving in a dirty state
		SetupUtils::ExitMaintenanceMode(false);
		SetupUtils::ExitReadOnlyMode(false);

		if (false === $bHasBackup) {
			SetupUtils::EraseSetupToken();
		}
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
		SetupUtils::EraseSetupToken();
		// For security reasons: add the extension now so that this action can be used to read *only* .tar.gz files from the disk...
		$sBackupFile = $aParameters['backup'].'.tar.gz';
		if (file_exists($sBackupFile)) {
			// Make sure there is NO output at all before our content, otherwise the document will be corrupted
			$sPreviousContent = ob_get_clean();
			$oPage->SetContentType('application/gzip');
			$oPage->SetContentDisposition('attachment', basename($sBackupFile));
			$oPage->add(file_get_contents($sBackupFile));
		}
	}
}

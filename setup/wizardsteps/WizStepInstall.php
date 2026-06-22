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

require_once(APPROOT.'setup/sequencers/ApplicationInstallSequencer.php');

class WizStepInstall extends AbstractWizStepInstall
{
	public const SequencerClass = ApplicationInstallSequencer::class;

	public function GetTitle()
	{
		return 'Building iTop';
	}

	public function GetPossibleSteps()
	{
		return [WizStepDone::class];
	}

	/**
	 * Returns the label for the " Next >> " button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return 'Continue';
	}

	public function CanMoveForward()
	{
		if ($this->CheckDependencies()) {
			return true;
		} else {
			return false;
		}
	}

	public function CanMoveBackward()
	{
		$sApplication = $this->oWizard->GetParameter('return_application', '');

		return $sApplication === '';
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		return new WizardState(WizStepDone::class);
	}

	protected function AddProgressBar(WebPage $oPage, string $sTitle = 'Progress of the operations')
	{
		$oPage->add('<fieldset id="installation_progress"><legend>'.$sTitle.'</legend>');
		$oPage->add('<div id="progress_content">');
		$oPage->LinkScriptFromAppRoot('setup/jquery.progression.js');
		$oPage->add('<p class="center"><span id="setup_msg">Ready to start...</span></p><div style="display:block;margin-left: auto; margin-right:auto;" id="progress">0%</div>');
		$oPage->add('</div>'); // progress_content
		$oPage->add('</fieldset>');
		$oPage->add("<div class=\"message message-error ibo-is-html-content\" style=\"display:none;\" id=\"setup_error\"></div>");
	}

	protected function AddPrevStepSuccessMessage(WebPage $oPage, string $sPrevStepSuccessMessage): void
	{
		if ($sPrevStepSuccessMessage === '') {
			return;
		}

		$sPrevStepSuccessMessage = addslashes(utils::EscapeHtml($sPrevStepSuccessMessage));
		$oPage->add_ready_script(
			<<<EOF
	$('<div class="message message-valid ibo-is-html-content">').html('$sPrevStepSuccessMessage').appendTo("#progress_content");
EOF
		);
	}

	public function Display(SetupPage $oPage): void
	{
		$aInstallParams = $this->BuildConfig();
		$this->AddProgressBar($oPage, 'Progress of the installation');

		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.utils::EscapeHtml($sJSONData).'"/>');
		if (!$this->CheckDependencies()) {
			$oPage->error($this->sDependencyIssue);
			$oPage->add_ready_script(<<<JS
	$("#wiz_form").data("installation_status", "error");
	document.getElementById("setup_msg").innerText = "Unmet dependencies";
JS);
			return;
		}

		$oPage->add_ready_script(<<<JS
	$("#wiz_form").data("installation_status", "not started");
	ExecuteStep("");
JS);
	}

	public function PostFormDisplay(SetupPage $oPage)
	{
		[$sButtonLabel, $sButtonUrl] = $this->GetBackButtonInfo();
		if ($sButtonUrl !== '') {
			$sButtonUrl = utils::HtmlEntities($sButtonUrl);
			$oPage->add_ready_script(
				<<<JS
$('.ibo-setup--wizard--buttons-container tr td:nth-child(1)').after('<td style="text-align:center;"><button id="return-button" class="ibo-button ibo-is-alternative ibo-is-neutral ibo-is-hidden" type="button" onclick="window.location.href=\'$sButtonUrl\'"><span class="ibo-button--label">$sButtonLabel</span></button></td>');
JS
			);
		}
	}

	/**
	 * @throws \Exception
	 */
	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		$oParameters = new PHPParameters();
		$sStep = $aParameters['installer_step'];
		$sJSONParameters = $aParameters['installer_config'];
		$oParameters->LoadFromHash(json_decode($sJSONParameters, true /* bAssoc */));
		/** @var StepSequencer $oInstaller */
		$oInstaller = new (static::SequencerClass)($oParameters);
		$aRes = $oInstaller->ExecuteStep($sStep);
		if (($aRes['status'] !== StepSequencer::ERROR) && ($aRes['next-step'] != '')) {
			// Tell the web page to move the progress bar and to launch the next step
			$sMessage = addslashes(utils::EscapeHtml($aRes['next-step-label']));
			$oPage->add_ready_script(
				<<<JS
				$("#wiz_form").data("installation_status", "running");
				WizardUpdateButtons();
				$('#setup_msg').html('$sMessage');
				$('#progress').progression( {Current:{$aRes['percentage-completed']}, Maximum: 100} );
				
				//$("#percentage").html('{$aRes['percentage-completed']} % completed<br/>{$aRes['next-step-label']}');
				ExecuteStep('{$aRes['next-step']}');
JS
			);
			static::AddPrevStepSuccessMessage($oPage, $aRes['prev-step-success-message']);
		} elseif ($aRes['status'] !== StepSequencer::ERROR) {
			// Installation complete, move to the next step of the wizard
			$oPage->add_ready_script(
				<<<JS
				$('#progress').progression( {Current:100, Maximum: 100} );
				$("#wiz_form").data("installation_status", "completed");
				$("#btn_next").trigger('click');
JS
			);
			static::AddPrevStepSuccessMessage($oPage, $aRes['prev-step-success-message']);
		} else {
			//Error case
			$sMessage = addslashes(utils::EscapeHtml($aRes['message']));
			$sMessage = str_replace("\n", '<br>', $sMessage);
			$oPage->add_ready_script(
				<<<JS
				$("#wiz_form").data("installation_status", "error");
				$("#progress .progress").addClass('progress-error');
				WizardUpdateButtons();
				$('#setup_error').html('$sMessage').show();
JS
			);
			$this->AddProgressErrorScript($oPage, $aRes);
		}
	}

	protected function AddProgressErrorScript($oPage, $aRes)
	{
		$oPage->add_ready_script(
			<<<JS
    if ($('#return-button').length > 0) {
		$('#return-button').removeClass('ibo-is-hidden');
    }
JS
		);
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return  $("#wiz_form").data("installation_status") === "completed";';
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

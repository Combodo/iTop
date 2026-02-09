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

class WizStepInstall extends AbstractWizStepInstall
{
	public const SequencerClass = ApplicationInstallSequencer::class;

	public function GetTitle()
	{
		return 'Building iTop';
	}

	public function GetPossibleSteps()
	{
		return ['WizStepDone'];
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

	public function ProcessParams($bMoveForward = true)
	{
		return ['class' => 'WizStepDone', 'state' => ''];
	}

	protected function AddProgressBar(WebPage $oPage)
	{
		$oPage->add('<fieldset id="installation_progress"><legend>Progress of the installation</legend>');
		$oPage->add('<div id="progress_content">');
		$oPage->LinkScriptFromAppRoot('setup/jquery.progression.js');
		$oPage->add('<p class="center"><span id="setup_msg">Ready to start...</span></p><div style="display:block;margin-left: auto; margin-right:auto;" id="progress">0%</div>');
		$oPage->add('</div>'); // progress_content
		$oPage->add('</fieldset>');
		$oPage->add("<div class=\"message message-error ibo-is-html-content\" style=\"display:none;\" id=\"setup_error\"></div>");

	}

	public function Display(WebPage $oPage)
	{

		$aInstallParams = $this->BuildConfig();
		$this->AddProgressBar($oPage);

		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.utils::EscapeHtml($sJSONData).'"/>');

		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		if (!$this->CheckDependencies()) {
			$oPage->error($this->sDependencyIssue);
		}

		$oPage->add_ready_script(
			<<<JS
	$("#wiz_form").data("installation_status", "not started");
	ExecuteStep("");
JS
		);
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
		$oInstaller = new (static::SequencerClass)($oParameters);
		$aRes = $oInstaller->ExecuteStep($sStep);
		if (($aRes['status'] != $oInstaller::ERROR) && ($aRes['next-step'] != '')) {
			// Tell the web page to move the progress bar and to launch the next step
			$sMessage = addslashes(utils::EscapeHtml($aRes['next-step-label']));
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
		} elseif ($aRes['status'] != $oInstaller::ERROR) {
			// Installation complete, move to the next step of the wizard
			$oPage->add_ready_script(
				<<<EOF
	$("#wiz_form").data("installation_status", "completed");
	$('#progress').progression( {Current:100, Maximum: 100} );
	WizardUpdateButtons();
	$("#btn_next").off("click.install");
	$("#btn_next").trigger('click');
EOF
			);
		} else {
			//Error case
			$sMessage = addslashes(utils::EscapeHtml($aRes['message']));
			$sMessage = str_replace("\n", '<br>', $sMessage);
			$oPage->add_ready_script(
				<<<EOF
	$("#wiz_form").data("installation_status", "error");
	WizardUpdateButtons();
	$('#setup_error').html('$sMessage').show();
EOF
			);
			$this->AddProgressErrorScript($oPage, $aRes);
		}
	}

	protected function AddProgressErrorScript($oPage, $aRes)
	{

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

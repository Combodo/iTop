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

class WizStepAudit extends AbstractWizStepBuild
{
	public function GetTitle()
	{
		return 'Checking upgrade';

	}

	public function GetPossibleSteps()
	{
		return ['WizStepSummary'];
	}

	public function GetNextButtonLabel()
	{
		return 'Next';
	}

	public function CanMoveForward()
	{
		return true;

	}

	public function ProcessParams($bMoveForward = true)
	{
		return ['class' => 'WizStepSummary', 'state' => ''];
	}

	public function Display(WebPage $oPage)
	{
		$oPage->add('<h2>Progress bar</h2>');
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		$oParameters = new PHPParameters();
		$sStep = $aParameters['installer_step'];
		$sJSONParameters = $aParameters['installer_config'];
		$oParameters->LoadFromHash(json_decode($sJSONParameters, true /* bAssoc */));
		$oInstaller = new ApplicationInstaller($oParameters);
		$aRes = $oInstaller->ExecuteStep($sStep);
		if (($aRes['status'] != ApplicationInstaller::ERROR) && ($aRes['next-step'] != '')) {
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
		} elseif ($aRes['status'] != ApplicationInstaller::ERROR) {
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
			$sMessage = addslashes(utils::EscapeHtml($aRes['message']));
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
		return 'return true;';
		return 'return (($("#wiz_form").data("installation_status") === "not started") || ($("#wiz_form").data("installation_status") === "completed"));';
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveBackward()
	{
		return 'return true;';
		return 'var sStatus = $("#wiz_form").data("installation_status"); return ((sStatus === "not started") || (sStatus === "error"));';
	}
}

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

require_once(APPROOT.'setup/sequencers/DataAuditSequencer.php');

/**
 * @since 3.3.0
 */
class WizStepDataAudit extends WizStepInstall
{
	public const SequencerClass = DataAuditSequencer::class;

	public function GetTitle()
	{
		return 'Checking compatibility';

	}

	public function GetPossibleSteps()
	{
		return [WizStepSummary::class];
	}

	public function GetNextButtonLabel()
	{
		return 'Next';
	}

	public function CanMoveForward()
	{
		if ($this->CheckDependencies()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Tells whether this step/state allows to go back or not
	 * @return boolean True if the '<< Back' button should be displayed
	 */
	public function CanMoveBackward()
	{
		return false;
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		return new WizardState(WizStepSummary::class);
	}

	public function Display(SetupPage $oPage): void
	{

		$aInstallParams = $this->BuildConfig();

		$this->AddProgressBar($oPage, 'Progress of the verification');

		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.utils::EscapeHtml($sJSONData).'"/>');
		if (!$this->CheckDependencies()) {
			$oPage->error($this->sDependencyIssue);
			$oPage->add_ready_script(<<<JS
	$("#wiz_form").data("installation_status", "error");
	document.getElementById("setup_msg").innerText = "Unmet dependencies";
JS);
		} else {
			$oPage->add_ready_script(<<<JS
	$("#wiz_form").data("installation_status", "not started");
	ExecuteStep("");
JS);
		}
	}

	/**
	 * Add extra form to go to the feature removal backend page
	 * @param \SetupPage $oPage
	 *
	 * @return void
	 */
	public function PostFormDisplay(SetupPage $oPage)
	{
		$sEnvironment = $this->oWizard->GetParameter('target_env', ITOP_DEFAULT_ENV);
		$sApplicationUrl = utils::GetAbsoluteUrlModulePage('combodo-data-feature-removal', 'index.php', ['switch_env' => $sEnvironment], $sEnvironment);

		$aParams = [
			'selected_modules' => '[]',
			'selected_extensions' => '[]',
			'display_choices' => '',
			'added_extensions' => '[]',
			'removed_extensions' => '[]',
			'extensions_not_uninstallable' => '[]',
			'copy_setup_files' => 1,
			'return_button_label' => '',
			'target_env' => ITOP_DEFAULT_ENV,
			'force-uninstall' => "",
			'use_symbolic_links' => "",
		];
		$aHiddenInputs = '';
		foreach ($aParams as $sParamName => $defaultValue) {
			$sElements = utils::HtmlEntities($this->oWizard->GetParameter($sParamName, $defaultValue));
			$sParamName = utils::HtmlEntities($sParamName);
			$aHiddenInputs .= <<<INPUT
	<input type="hidden" name="$sParamName" value="$sElements"/>
INPUT;
		}
		$oPage->add(
			<<<HTML
<form id="data-feature-removal" class="ibo-setup--wizard ibo-is-hidden" method="post" action="$sApplicationUrl">
	<input type="hidden" name="operation" value="AnalysisResult"/>
	$aHiddenInputs
</form>
HTML
		);

		$sButtonLabel = $this->oWizard->GetParameter('return_button_label', '');
		if ($sButtonLabel !== '') {
			$sButtonLabel = utils::HtmlEntities($sButtonLabel);
			$sButtonUrl = utils::GetAbsoluteUrlModulePage('itsm-designer-connector', 'launch.php');
			$sButtonUrl = utils::HtmlEntities($sButtonUrl);
			$oPage->add_ready_script(
				<<<JS
	$('.ibo-setup--wizard--buttons-container tr td:nth-child(1)').after('<td style="text-align:center;"><button id="return-button" class="ibo-button ibo-is-alternative ibo-is-neutral ibo-is-hidden" type="button" onclick="window.location.href=\'$sButtonUrl\'"><span class="ibo-button--label">$sButtonLabel</span></button></td>');
JS
			);
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

		if (isset($aRes['error_code']) && $aRes['error_code'] === DataAuditSequencer::DATA_AUDIT_FAILED) {
			$oPage->add_ready_script(
				<<<JS
	$('.ibo-setup--wizard--buttons-container tr td:nth-child(2)').after('<td style="text-align:center;"><button class="ibo-button ibo-is-alternative ibo-is-neutral" type="submit" name="operation" value="next" id="ignore_and_continue"><span class="ibo-button--label">Ignore and continue</span></button></td>');
	$('#ignore_and_continue').on('click', function() {
		return confirm("If you skip the cleanup you won't be able to run the process later. You'll have to migrate or delete unconsistent data manually.");
	});
	$('.ibo-setup--wizard--buttons-container tr td:nth-child(3)').after('<td style="text-align:center;"><span id="submit-wait" class="ibo-spinner ibo-is-inline ibo-is-hidden ibo-spinner ibo-block" data-role="ibo-spinner"><i class="ibo-spinner--icon fas fa-sync-alt fa-spin" aria-hidden="true"></i></span>&nbsp;<button id="goto-data-feature-removal" class="default ibo-button ibo-is-regular ibo-is-primary" type="button"><span class="ibo-button--label">Cleanup my data</span></button></td>');
	$('#goto-data-feature-removal').on("click", function() {
		$('#goto-data-feature-removal').prop('disabled', true);
		$('#submit-wait').removeClass("ibo-is-hidden");
		$('#data-feature-removal').submit();
	});
	
	$("#wiz_form").data("installation_status", "cleanup_needed");
	$('#btn_next').hide();
JS
			);
		}

	}

	public function JSCanMoveForward()
	{
		return 'return  ["completed", "cleanup_needed"].indexOf($("#wiz_form").data("installation_status")) !== -1;';
	}

	public function JSCanMoveBackward()
	{
		if ($this->oWizard->GetParameter('return_button_label', '') !== '') {
			return 'return false;';
		}

		return 'return  ["not started", "error", "cleanup_needed"].indexOf($("#wiz_form").data("installation_status")) !== -1;';
	}
}

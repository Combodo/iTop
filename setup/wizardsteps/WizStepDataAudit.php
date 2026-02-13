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

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true)
	{
		return ['class' => WizStepSummary::class, 'state' => ''];
	}

	public function CanComeBack()
	{
		return false;
	}

	public function Display(WebPage $oPage)
	{

		$aInstallParams = $this->BuildConfig();

		$this->AddProgressBar($oPage);

		$sJSONData = json_encode($aInstallParams);
		$oPage->add('<input type="hidden" id="installer_parameters" value="'.utils::EscapeHtml($sJSONData).'"/>');

		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$sApplicationUrl = $this->oWizard->GetParameter('application_url').'pages/exec.php?exec_module=combodo-data-feature-removal&exec_page=index.php';
		$oPage->add('<input type="hidden" id="application_url" value="'.$sApplicationUrl.'"/>');

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

	protected function AddProgressErrorScript($oPage, $aRes)
	{
		if (isset($aRes['error_code']) && $aRes['error_code'] === DataAuditSequencer::DATA_AUDIT_FAILED) {

			$oPage->add_ready_script(
				<<<EOF
	$('.ibo-setup--wizard--buttons-container tr td:nth-child(2)').before('<td style="text-align:center;"><button class="ibo-button ibo-is-alternative ibo-is-neutral" type="submit" name="operation" value="next"><span class="ibo-button--label">Ignore and continue</span></button></td>');
	
	$('.ibo-setup--wizard--buttons-container tr td:nth-child(2)').after('<td style="text-align:center;"><a href="'+$('#application_url').val()+'"><button class="default ibo-button ibo-is-regular ibo-is-primary" type="button"><span class="ibo-button--label">Go to backoffice</span></button></a></td>');

	$("#wiz_form").data("installation_status", "cleanup_needed");
	$('#btn_next').hide();
EOF
			);
		}

	}

	public function JSCanMoveForward()
	{
		return 'return  ["completed", "cleanup_needed"].indexOf($("#wiz_form").data("installation_status")) !== -1;';
	}

	public function JSCanMoveBackward()
	{
		return 'return  ["not started", "error", "cleanup_needed"].indexOf($("#wiz_form").data("installation_status")) !== -1;';
	}
}

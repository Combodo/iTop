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
 * @since 3.3.0
 */
class WizStepDataAudit extends WizStepInstall
{
	const SequencerClass = DataAuditSequencer::class;

	public function GetTitle()
	{
		return 'Checking compatibility';

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
		if ($this->CheckDependencies()) {
			return true;
		} else {
			return false;
		}

	}

	public function ProcessParams($bMoveForward = true)
	{
		return ['class' => 'WizStepSummary', 'state' => ''];
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
}

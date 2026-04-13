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
 * Miscellaneous Parameters (URL...) in case of upgrade
 */
class WizStepUpgradeMiscParams extends AbstractWizStepMiscParams
{
	public function GetTitle()
	{
		return 'Miscellaneous Parameters';
	}

	public function GetPossibleSteps()
	{
		return [WizStepModulesChoice::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		$this->oWizard->SaveParameter('application_url', '');
		$this->oWizard->SaveParameter('graphviz_path', '');
		$this->oWizard->SaveParameter('use-symbolic-links', false);
		$this->oWizard->SaveParameter('force-uninstall', false);
		return new WizardState(WizStepModulesChoice::class, 'start_upgrade');
	}

	public function Display(WebPage $oPage)
	{
		$sApplicationURL = $this->oWizard->GetParameter('application_url', utils::GetAbsoluteUrlAppRoot(true)); //Preserve existing configuration (except for the str_replace based joker $SERVER_NAME$ which is lost)
		$sDefaultGraphvizPath = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'C:\\Program Files\\Graphviz\\bin\\dot.exe' : '/usr/bin/dot';
		$sGraphvizPath = $this->oWizard->GetParameter('graphviz_path', $sDefaultGraphvizPath);
		$oPage->add('<h2>Additional parameters</h2>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Application URL</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>URL: </td><td><input id="application_url" class="ibo-input" name="application_url" type="text" size="35" maxlength="1024" value="'.utils::EscapeHtml($sApplicationURL).'" style="width: 100%;box-sizing: border-box;"><span id="v_application_url"/></td><tr>');
		$oPage->add('</table>');
		$oPage->add('<div class="message message-warning">Change the value above if the end-users will be accessing the application by another path due to a specific configuration of the web server.</div>');
		$oPage->add('</fieldset>');
		$oPage->add('<fieldset>');
		$oPage->add('<legend>Path to Graphviz\' dot application</legend>');
		$oPage->add('<table>');
		$oPage->add('<tr><td>Path: </td><td><input id="graphviz_path" class="ibo-input" name="graphviz_path" type="text" size="35" maxlength="1024" value="'.utils::EscapeHtml($sGraphvizPath).'" style="width: 100%;box-sizing: border-box;"><span id="v_graphviz_path"/></td>');
		$oPage->add('<td><i class="fas fa-question-circle setup-input--hint--icon" data-tooltip-content="Graphviz is required to display the impact analysis graph (i.e. impacts / depends on)."></i></td><tr>');
		$oPage->add('</table>');
		$oPage->add('<span id="graphviz_status"></span>');
		$oPage->add('</fieldset>');
		$sAuthentToken = $this->oWizard->GetParameter('authent', '');
		$oPage->add('<input type="hidden" id="authent_token" value="'.$sAuthentToken.'"/>');
		$oPage->add_ready_script(
			<<<EOF
		$('#application_url').on('change keyup', function() { WizardUpdateButtons(); } );
		$('#graphviz_path').on('change keyup init', function() { WizardUpdateButtons();  WizardAsyncAction('check_graphviz', { graphviz_path: $('#graphviz_path').val(), authent: $('#authent_token').val() }); } ).trigger('init');
		$('#btn_next').on('click', function() {
			bRet = true;
			if ($(this).attr('data-graphviz') != 'ok')
			{
				bRet = confirm('The impact analysis will not be displayed properly. Are you sure you want to continue?');
			}
			return bRet;
		});
EOF
		);

		$this->AddUseSymlinksFlagOption($oPage);
		$this->AddForceUninstallFlagOption($oPage);
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		switch ($sCode) {
			case 'check_graphviz':
				$sGraphvizPath = $aParameters['graphviz_path'];
				$aCheck = SetupUtils::CheckGraphviz($sGraphvizPath);

				// N°2214 logging TRACE results
				$aTraceCheck = CheckResult::FilterCheckResultArray($aCheck, [CheckResult::TRACE]);
				foreach ($aTraceCheck as $oTraceCheck) {
					SetupLog::Ok($oTraceCheck->sLabel);
				}

				$aNonTraceCheck = array_diff($aCheck, $aTraceCheck);
				foreach ($aNonTraceCheck as $oCheck) {
					switch ($oCheck->iSeverity) {
						case CheckResult::INFO:
							$sStatus = 'ok';
							$sInfoExplanation = $oCheck->sLabel;
							$sMessage = json_encode('<div class="message message-valid">'.$sInfoExplanation.'</div>');
							break;

						default:
						case CheckResult::ERROR:
						case CheckResult::WARNING:
							$sStatus = 'ko';
							$sErrorExplanation = $oCheck->sLabel;
							$sMessage = json_encode('<div class="message message-error">'.$sErrorExplanation.'</div>');
							break;
					}
					$oPage->add_ready_script(
						<<<JS
	$("#graphviz_status").html($sMessage);
	$('#btn_next').attr('data-graphviz', '$sStatus');
JS
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

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
 * First step of the iTop Installation Wizard: Welcome screen, requirements
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
		return 'Continue';
	}

	public function GetPossibleSteps()
	{
		return ['WizStepInstallOrUpgrade'];
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sUID = SetupUtils::CreateSetupToken();
		$this->oWizard->SetParameter('authent', $sUID);
		return ['class' => 'WizStepInstallOrUpgrade', 'state' => ''];
	}

	public function Display(WebPage $oPage)
	{
		// Store the misc_options for the future...
		$aMiscOptions = utils::ReadParam('option', [], false, 'raw_data');
		$sMiscOptions = $this->oWizard->GetParameter('misc_options', json_encode($aMiscOptions));
		$this->oWizard->SetParameter('misc_options', $sMiscOptions);

		$oPage->add("<!--[if lt IE 11]><div id=\"old_ie\"></div><![endif]-->");
		$oPage->add_ready_script(
			<<<EOF
		if ($('#old_ie').length > 0)
		{
			alert("Internet Explorer version 10 or older is NOT supported! (Check that IE is not running in compatibility mode)");
		}
EOF
		);
		$oPage->add('<h1>'.ITOP_APPLICATION.' Installation Wizard</h1>');
		$aResults = SetupUtils::CheckPhpAndExtensions();
		$this->bCanMoveForward = true;
		$aInfo = [];
		$aWarnings = [];
		$aErrors = [];
		foreach ($aResults as $oCheckResult) {
			switch ($oCheckResult->iSeverity) {
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

				case CheckResult::TRACE:
					SetupLog::Ok($oCheckResult->sLabel);
					break;
			}
		}
		$sStyle = 'style="display:none;overflow:auto;"';
		$sToggleButtons = '<button type="button" id="show_details" class="ibo-button ibo-is-alternative ibo-is-neutral" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#hide_details\').toggle();"><span class="ibo-button--icon fa fa-caret-down"></span><span class="ibo-button--label">Show details</span></button><button type="button" id="hide_details" class="ibo-button ibo-is-alternative ibo-is-neutral" style="display:none;" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#show_details\').toggle();"><span class="ibo-button--icon fa fa-caret-up"></span><span class="ibo-button--label">Hide details</span></button>';
		if (count($aErrors) > 0) {
			$sStyle = 'overflow:auto;"';
			$sTitle = count($aErrors).' Error(s), '.count($aWarnings).' Warning(s).';
			$sH2Class = 'text-error';
		} elseif (count($aWarnings) > 0) {
			$sTitle = count($aWarnings).' Warning(s) '.$sToggleButtons;
			$sH2Class = 'text-warning';
		} else {
			$sTitle = 'Ok. '.$sToggleButtons;
			$sH2Class = 'text-valid';
		}
		$oPage->add(
			<<<HTML
		<h2 class="message">Prerequisites validation: <span class="$sH2Class">$sTitle</span></h2>
		<div id="details" $sStyle>
HTML
		);
		foreach ($aErrors as $sText) {
			$oPage->error($sText);
		}
		foreach ($aWarnings as $sText) {
			$oPage->warning($sText);
		}
		foreach ($aInfo as $sText) {
			$oPage->ok($sText);
		}
		$oPage->add('</div>');
		if (!$this->bCanMoveForward) {
			$oPage->p('Sorry, the installation cannot continue. Please fix the errors and reload this page to launch the installation again.');
			$oPage->p('<button type="button" onclick="window.location.reload()">Reload</button>');
		}
		$oPage->add_ready_script('CheckDirectoryConfFilesPermissions("'.utils::GetItopVersionWikiSyntax().'")');
	}

	public function CanMoveForward()
	{
		return $this->bCanMoveForward;
	}
}

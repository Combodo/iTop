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

/**
 * First step of the iTop Installation Wizard: Welcome screen, requirements
 */
class WizStepWelcome extends WizardStep
{
	protected $bCanMoveForward;
	private array $aInfo;
	private array $aWarnings;
	private array $aErrors;

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		parent::__construct($oWizard, $sCurrentState);
		$this->CheckInstallation();
	}

	public function GetTitle()
	{
		return 'Welcome to '.ITOP_APPLICATION.' version '.ITOP_VERSION;
	}

	/**
	 * Returns the label for the "Next >>" button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return 'Continue';
	}

	public function GetPossibleSteps()
	{
		return [WizStepDetectedInfo::class, WizStepLicense::class];
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		if ($this->oWizard->GetParameter('mode', 'install') === 'install') {

			return new WizardState(WizStepLicense::class);
		}

		return new WizardState(WizStepDetectedInfo::class);
	}

	public function Display(SetupPage $oPage): void
	{
		$this->oWizard->EraseParameters();
		$this->oWizard->SetWizardSteps([]);

		$aPreviousInstance = SetupUtils::GetPreviousInstance(APPROOT);
		if ($aPreviousInstance['found']) {
			$this->oWizard->SetParameter('mode', 'upgrade');
			$this->oWizard->SetParameter('db_server', $aPreviousInstance['db_server']);
			$this->oWizard->SetParameter('db_user', $aPreviousInstance['db_user']);
			$this->oWizard->SetParameter('db_pwd', $aPreviousInstance['db_pwd']);
			$this->oWizard->SetParameter('db_name', $aPreviousInstance['db_name']);
			$this->oWizard->SetParameter('db_prefix', $aPreviousInstance['db_prefix']);
			$this->oWizard->SetParameter('db_tls_enabled', $aPreviousInstance['db_tls_enabled']);
			$this->oWizard->SetParameter('db_tls_ca', $aPreviousInstance['db_tls_ca'] ?? '');
			$this->oWizard->SetParameter('graphviz_path', $aPreviousInstance['graphviz_path']);
		} else {
			$this->oWizard->SetParameter('mode', 'install');
			$sFullSourceDir = SetupUtils::GetLatestDataModelDir();
			$this->oWizard->SetParameter('source_dir', $sFullSourceDir);
			$this->oWizard->SetParameter('datamodel_version', SetupUtils::GetDataModelVersion($sFullSourceDir));
		}
		$this->oWizard->SetParameter('previous_version_dir', APPROOT);

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
		$sStyle = 'style="display:none;overflow:auto;"';
		$sToggleButtons = '<button type="button" id="show_details" class="ibo-button ibo-is-alternative ibo-is-neutral" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#hide_details\').toggle();"><span class="ibo-button--icon fa fa-caret-down"></span><span class="ibo-button--label">Show details</span></button><button type="button" id="hide_details" class="ibo-button ibo-is-alternative ibo-is-neutral" style="display:none;" onclick="$(\'#details\').toggle(); $(this).toggle(); $(\'#show_details\').toggle();"><span class="ibo-button--icon fa fa-caret-up"></span><span class="ibo-button--label">Hide details</span></button>';
		if (count($this->aErrors) > 0) {
			$sStyle = 'style="overflow:auto;"';
			$sTitle = count($this->aErrors).' Error(s), '.count($this->aWarnings).' Warning(s).';
			$sH2Class = 'text-error';
		} elseif (count($this->aWarnings) > 0) {
			$sTitle = count($this->aWarnings).' Warning(s) '.$sToggleButtons;
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
		foreach ($this->aErrors as $sText) {
			$oPage->error($sText);
		}
		foreach ($this->aWarnings as $sText) {
			$oPage->warning($sText);
		}
		foreach ($this->aInfo as $sText) {
			$oPage->ok($sText);
		}
		$oPage->add('</div>');
		if (!$this->bCanMoveForward) {
			$oPage->p('Sorry, the installation cannot continue. Please fix the errors and reload this page to launch the installation again.');
			$oPage->p('<button type="button" onclick="window.location.reload()">Reload</button>');
		}
		$oPage->add_ready_script('CheckDirectoryConfFilesPermissions("'.utils::GetItopVersionWikiSyntax().'")');
	}

	/**
	 * Add post display stuff to the setup screen
	 * @param \SetupPage $oPage
	 *
	 * @return void
	 */
	public function PostFormDisplay(SetupPage $oPage)
	{
		if ($this->bCanMoveForward) {
			$sBuildConfigFile = APPCONF.ITOP_DEFAULT_ENV.'/'.ITOP_CONFIG_FILE;
			if (file_exists($sBuildConfigFile)) {
				$oPage->add(
					<<<HTML
					<form id="fast_setup" method="post">
						<input type="hidden" name="_class" value="WizStepLandingBeforeAudit"/>
						<input type="hidden" name="operation" value="next"/>
						<input type="hidden" name="skip_wizard" value="1"/>
					</form>
HTML
				);

				if ($this->DisplaySetupShortcutButton()) {
					$oPage->add_ready_script(
						<<<JS
$('.ibo-setup--wizard--buttons-container tr td:nth-child(1)').before('<td style="text-align:center;"><button class="ibo-button ibo-is-alternative ibo-is-neutral" form="fast_setup"><span class="ibo-button--label">Keep current choices</span></button></td>');
JS
					);
				}
			}
		}
	}

	public function DisplaySetupShortcutButton(): bool
	{
		if ('install' === $this->oWizard->GetParameter('mode', 'install')) {
			//fresh install
			return false;
		}

		$oConfig = utils::GetConfig();
		$res = ModuleInstallationRepository::GetInstance()->GetApplicationVersion($oConfig);
		if (false === $res) {
			return false;
		}

		$sProductName = $res['product_name'] ?? null;
		$sProductVersion = $res['product_version'] ?? null;
		if (is_null($sProductName) || is_null($sProductVersion)) {
			\SetupLog::Error(__METHOD__.": cannot fetch itop version", null, $res);
			return false;
		}

		if (ITOP_VERSION_FULL !== $sProductVersion) {
			return false;
		}

		return (ITOP_APPLICATION === $sProductName);
	}

	public function CanMoveForward()
	{
		return $this->bCanMoveForward;
	}

	/**
	 */
	public function CheckInstallation(): void
	{
		$aResults = SetupUtils::CheckPhpAndExtensions();
		$this->bCanMoveForward = true;
		$this->aInfo = [];
		$this->aWarnings = [];
		$this->aErrors = [];
		foreach ($aResults as $oCheckResult) {
			switch ($oCheckResult->iSeverity) {
				case CheckResult::ERROR:
					$this->aErrors[] = $oCheckResult->sLabel;
					$this->bCanMoveForward = false;
					break;

				case CheckResult::WARNING:
					$this->aWarnings[] = $oCheckResult->sLabel;
					break;

				case CheckResult::INFO:
					$this->aInfo[] = $oCheckResult->sLabel;
					break;

				case CheckResult::TRACE:
					SetupLog::Ok($oCheckResult->sLabel);
					break;
			}
		}
	}
}

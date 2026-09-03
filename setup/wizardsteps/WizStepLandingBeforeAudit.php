<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizStepLandingBeforeAudit extends WizStepModulesChoice
{
	private RunTimeEnvironment $oRuntimeEnv;

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		$this->oRuntimeEnv = new RunTimeEnvironment($oWizard->GetParameter('target_env', ITOP_DEFAULT_ENV));
		$sBuildConfigFile = APPCONF.$this->oRuntimeEnv->GetBuildEnv().'/'.ITOP_CONFIG_FILE;
		$this->oConfig = new Config($sBuildConfigFile);

		$oWizard->SetParameter('previous_version_dir', APPROOT);
		$oWizard->SetParameter('mode', 'upgrade');
		$oWizard->SetParameter('source_dir', APPROOT.$this->oConfig->Get('source_dir'));
		$oWizard->SetParameter('graphviz_path', $this->oConfig->Get('graphviz_path'));
		$oWizard->SetParameter('application_url', $this->oConfig->Get('app_root_url'));
		$oWizard->SetParameter('datamodel_version', ITOP_CORE_VERSION);
		$oWizard->SetParameter('upgrade_type', 'use-compatible');

		$oWizard->SetParameter('db_server', $this->oConfig->Get('db_host'));
		$oWizard->SetParameter('db_user', $this->oConfig->Get('db_user'));
		$oWizard->SetParameter('db_pwd', $this->oConfig->Get('db_pwd'));
		$oWizard->SetParameter('db_name', $this->oConfig->Get('db_name'));
		$oWizard->SetParameter('db_prefix', $this->oConfig->Get('db_subname'));
		$oWizard->SetParameter('db_tls_enabled', $this->oConfig->Get('db_tls.enabled'));
		$oWizard->SetParameter('db_tls_ca', $this->oConfig->Get('db_tls.ca') ?? '');
		$oWizard->SetParameter('display_choices', '');

		$oWizard->SaveParameter('skip_wizard', false);

		// should be done at the end
		parent::__construct($oWizard, $sCurrentState, false);
	}

	/**
	 * @inheritDoc
	 */
	public function Display(SetupPage $oPage): void
	{
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		$bSkipWizard = $this->oWizard->GetParameter('skip_wizard', false);
		if ($bSkipWizard) {
			$oRuntimeEnv = new RunTimeEnvironment();
			$sBuildConfigFile = APPCONF.$oRuntimeEnv->GetBuildEnv().'/'.ITOP_CONFIG_FILE;
			$oConfig = new Config($sBuildConfigFile);
			$oExtensionMap = iTopExtensionsMap::GetExtensionsMap($oRuntimeEnv->GetBuildEnv());
			$aExtensionsFromDatabase = $oExtensionMap->GetChoicesFromDatabase($oConfig);
			$this->oWizard->SetParameter('selected_extensions', json_encode($aExtensionsFromDatabase));
			$adModulesFromDatabase = ModuleInstallationRepository::GetInstance()->ReadComputeInstalledModules($oConfig);
			$this->oWizard->SetParameter('selected_modules', json_encode(array_keys($adModulesFromDatabase)));
		} else {
			$this->oWizard->SavePostedParameter('copy_setup_files', '1');
		}

		$aWizardSteps = $this->oWizard->GetParameter('_steps', null);
		if (is_null($aWizardSteps)) {
			$aWizardSteps = $this->GetWizardSteps();
			if ($bSkipWizard) {
				$this->oWizard->SetParameter('_steps', $aWizardSteps);
				$this->oWizard->SetWizardSteps($aWizardSteps);
			}

			// Component selection in previous screens
			if ($this->oWizard->GetParameter('selected_components', '[]') === '[]') {
				$aSelectedComponents = $this->GetSelectedComponents($this->aSteps, $this->oWizard->GetParameter('selected_extensions', '[]'));
				$this->oWizard->SetParameter('selected_components', json_encode($aSelectedComponents));
			} else {
				$aSelectedComponents = json_decode($this->oWizard->GetParameter('selected_components'), true);
			}

			$bForceUninstall = (bool)$this->oWizard->GetParameter('force-uninstall', false);
			if ($bSkipWizard && !$bForceUninstall) {
				$oLatestWizardState = $this->oWizard->GetLatestWizardStateFromStepClass(WizStepModulesChoice::class);
				// Check if the current choices allow going to the data audit step
				$aOptions = $this->oExtensionsMap->GetAllExtensionsOptionInfo();
				foreach ($aOptions as $index => $aChoice) {
					$sChoiceId = self::$SEP.$index;
					$aFlags = $this->ComputeChoiceFlags($aChoice, $sChoiceId, $aSelectedComponents[$oLatestWizardState->GetState()], false, false);
					if (!static::CanMoveForwardFromChoiceFlags($aFlags) || $aFlags['missing']) {
						// If an extension is missing, its uninstallation will be forced. We need to show it to the user
						// Pop the latest step from the stack, since we are going back to it
						$this->oWizard->PopStep();

						// If the user has selected incompatible modules, we need to go back to the extension choices step, which is the last module choices step in the wizard
						return $oLatestWizardState;
					}
				}
			}
		} else {
			$this->GetWizardSteps();
			$aSelectedComponents = $this->GetSelectedComponents($this->aSteps, $this->oWizard->GetParameter('selected_extensions', '[]'));
		}

		// Save the choices for the summary step
		$sDisplayChoices = '<ul>';
		$aModules = [];
		$aExtensions = [];
		$i = 0;
		foreach ($this->aSteps as $aStepInfo) {
			$sDisplayChoices .= $this->GetSelectedModules($aStepInfo, $aSelectedComponents[$i], $aModules, '', '', $aExtensions);
			$i++;
		}
		$sDisplayChoices .= '</ul>';
		$this->oWizard->SetParameter('display_choices', $sDisplayChoices);
		$this->oWizard->SetParameter('selected_modules', json_encode(array_keys($aModules)));

		return new WizardState(WizStepDataAudit::class);
	}

	/**
	 * @inheritDoc
	 */
	public function GetTitle(): string
	{
		return 'Before checking compatibility';
	}

	public function GetPossibleSteps()
	{
		return [WizStepDataAudit::class, WizStepModulesChoice::class];
	}

	public function GetNextButtonLabel()
	{
		return 'Next';
	}

	public function CanMoveBackward()
	{
		return false;
	}
}

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
use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReader;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReaderException;

/**
 * Choice of the modules to be installed
 */
class WizStepModulesChoice extends WizardStep
{
	protected static string $SEP = '_';
	protected bool $bUpgrade = false;
	protected bool $bCanMoveForward = true;
	protected ?Config $oConfig = null;

	/**
	 *
	 * @var iTopExtensionsMap
	 */
	protected iTopExtensionsMap $oExtensionsMap;

	private ?array $aSteps = null;

	protected PhpExpressionEvaluator $oPhpExpressionEvaluator;

	/**
	 * Whether we were able to load the choices from the database or not
	 * @var bool
	 */
	protected bool $bChoicesFromDatabase;

	private array $aAnalyzeInstallationModules = [];
	private ?MissingDependencyException $oMissingDependencyException = null;

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		parent::__construct($oWizard, $sCurrentState);
		$this->bChoicesFromDatabase = false;
		$this->oExtensionsMap = new iTopExtensionsMap();
		$sPreviousSourceDir = $this->oWizard->GetParameter('previous_version_dir', '');
		$sConfigPath = null;
		if (($sPreviousSourceDir !== '') && is_readable($sPreviousSourceDir.'/conf/production/config-itop.php')) {
			$sConfigPath = $sPreviousSourceDir.'/conf/production/config-itop.php';
		} elseif (is_readable(utils::GetConfigFilePath(ITOP_DEFAULT_ENV))) {
			$sConfigPath = utils::GetConfigFilePath(ITOP_DEFAULT_ENV);
		}

		// only called if the config file exists : we are updating a previous installation !
		// WARNING : we can't load this config directly, as it might be from another directory with a different approot_url (N°2684)
		if ($sConfigPath !== null) {
			$this->oConfig = new Config($sConfigPath);

			$aParamValues = $oWizard->GetParamForConfigArray();
			$this->oConfig->UpdateFromParams($aParamValues);

			$this->oExtensionsMap->LoadChoicesFromDatabase($this->oConfig);
			$this->bChoicesFromDatabase = true;
		}

		// Sanity check (not stopper, to let developers go further...)
		try {
			$this->aAnalyzeInstallationModules = SetupUtils::AnalyzeInstallation($this->oWizard, true);
		} catch (MissingDependencyException $e) {
			$this->oMissingDependencyException = $e;
			$this->aAnalyzeInstallationModules = SetupUtils::AnalyzeInstallation($this->oWizard);
		}
	}

	public function GetTitle(): string
	{
		$aStepInfo = $this->GetStepInfo();

		return $aStepInfo['title'] ?? 'Modules selection';
	}

	public function GetPossibleSteps()
	{
		return [WizStepModulesChoice::class, WizStepDataAudit::class];
	}

	public function GetAddedAndRemovedExtensions($aSelectedExtensions)
	{
		$aExtensionsAdded = [];
		$aExtensionsRemoved = [];
		$aExtensionsNotUninstallable = [];
		foreach ($this->oExtensionsMap->GetAllExtensionsWithPreviouslyInstalled() as $oExtension) {
			/* @var \iTopExtension $oExtension */
			$bSelected = in_array($oExtension->sCode, $aSelectedExtensions);
			if ($oExtension->bInstalled && !$bSelected) {
				$aExtensionsRemoved[$oExtension->sCode] = $oExtension->sLabel;
				if (!$oExtension->CanBeUninstalled() || $oExtension->sSource === iTopExtension::SOURCE_REMOTE) {
					$aExtensionsNotUninstallable[$oExtension->sCode] = true;
				}
			} elseif (!$oExtension->bInstalled && $bSelected) {
				$aExtensionsAdded[$oExtension->sCode] = $oExtension->sLabel;
			}
		}

		return [$aExtensionsAdded, $aExtensionsRemoved, $aExtensionsNotUninstallable];
	}

	public function IsDataAuditEnabled(): bool
	{
		$sPath = APPROOT.'env-production';
		if (!is_dir($sPath)) {
			SetupLog::Info("Reinstallation of an iTop from a backup (No env-production found). Setup data audit disabled");

			return false;
		}
		return true;
	}

	public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState
	{
		// Accumulates the selected modules:
		$index = $this->GetStepIndex();

		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aSelectedChoices = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		$aSelected = utils::ReadParam('choice', []);
		$aSelectedChoices[$index] = $aSelected;
		$this->oWizard->SetParameter('selected_components', json_encode($aSelectedChoices));

		if ($this->GetStepInfo($index) == null) {
			throw new Exception('Internal error: invalid step "'.$index.'" for the choice of modules.');
		} elseif ($bMoveForward) {
			if ($this->GetStepInfo(1 + $index) != null) {
				return new WizardState(WizStepModulesChoice::class, (string)($index + 1));
			} else {
				// Exiting this step of the wizard, let's convert the selection into a list of modules
				$aModules = [];
				$aExtensions = [];
				$sDisplayChoices = '<ul>';
				for ($i = 0; $i <= $index; $i++) {
					$aStepInfo = $this->GetStepInfo($i);
					$sDisplayChoices .= $this->GetSelectedModules($aStepInfo, $aSelectedChoices[$i], $aModules, '', '', $aExtensions);
				}
				$sDisplayChoices .= '</ul>';
				if (class_exists('CreateITILProfilesInstaller')) {
					$this->oWizard->SetParameter('old_addon', true);
				}

				[$aExtensionsAdded, $aExtensionsRemoved, $aExtensionsNotUninstallable] = $this->GetAddedAndRemovedExtensions($aExtensions);
				$this->oWizard->SetParameter('selected_modules', json_encode(array_keys($aModules)));
				$this->oWizard->SetParameter('selected_extensions', json_encode($aExtensions));
				$this->oWizard->SetParameter('display_choices', $sDisplayChoices);
				$this->oWizard->SetParameter('extensions_added', json_encode($aExtensionsAdded));
				$this->oWizard->SetParameter('removed_extensions', json_encode($aExtensionsRemoved));
				$this->oWizard->SetParameter('extensions_not_uninstallable', json_encode(array_keys($aExtensionsNotUninstallable)));

				return new WizardState(WizStepDataAudit::class);
			}

		}
		//Unused when going backward
		return new WizardState(WizStepModulesChoice::class, (string)($index - 1));
	}

	public function Display(SetupPage $oPage): void
	{
		$this->DisplayStep($oPage);
	}

	/**
	 * @param \SetupPage $oPage
	 *
	 * @throws \Exception
	 */
	protected function DisplayStep($oPage)
	{
		// Sanity check (not stopper, to let developers go further...)
		if (! is_null($this->oMissingDependencyException)) {
			$oPage->warning($this->oMissingDependencyException->getHtmlDesc(), $this->oMissingDependencyException->getMessage());
		}

		$this->bUpgrade = ($this->oWizard->GetParameter('install_mode') != 'install');
		$aStepInfo = $this->GetStepInfo();
		$oPage->add_style("div.choice { margin: 0.5em;}");
		$oPage->add_style("div.choice a { text-decoration:none; font-weight: bold; color: #1C94C4 }");
		$oPage->add_style("div.description { margin-left: 2em; }");
		$oPage->add_style("input.unremovable { accent-color: orangered;}");

		$sManualInstallError = SetupUtils::CheckManualInstallDirEmpty(
			$this->aAnalyzeInstallationModules,
			$this->oWizard->GetParameter('extensions_dir', 'extensions')
		);
		if ($sManualInstallError !== '') {
			$oPage->warning($sManualInstallError);
		}

		$oPage->add('<div class="module-selection-banner">');
		$sBannerPath = isset($aStepInfo['banner']) ? $aStepInfo['banner'] : '';
		if (!empty($sBannerPath)) {
			if (substr($sBannerPath, 0, 1) == '/') {
				// absolute path, means relative to APPROOT
				$sBannerUrl = utils::GetDefaultUrlAppRoot(true).$sBannerPath;
			} else {
				// relative path: i.e. relative to the directory containing the XML file
				$sFullPath = dirname($this->GetSourceFilePath()).'/'.$sBannerPath;
				$sRealPath = realpath($sFullPath);
				$sBannerUrl = utils::GetDefaultUrlAppRoot(true).str_replace(realpath(APPROOT), '', $sRealPath);
			}
			$oPage->add('<img src="'.$sBannerUrl.'"/>');
		}
		$sDescription = $aStepInfo['description'] ?? '';
		$oPage->add('<span>'.$sDescription.'</span>');
		$oPage->add('</div>');

		// Build the default choices
		$aDefaults = $this->GetDefaults($aStepInfo, $this->aAnalyzeInstallationModules);
		$index = $this->GetStepIndex();

		// retrieve the saved selection
		// use json_encode:decode to store a hash array: step_id => array(input_name => selected_input_id)
		$aParameters = json_decode($this->oWizard->GetParameter('selected_components', '{}'), true);
		if (!isset($aParameters[$index])) {
			$aParameters[$index] = $aDefaults;
		}
		$aSelectedComponents = $aParameters[$index];

		$oPage->add('<div class="module-selection-body">');
		$this->DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $aDefaults);
		$oPage->add('</div>');

		$oPage->add_script(
			<<<EOF
function CheckChoice(sChoiceId)
{
	var oElement = $('#'+sChoiceId);
	var bChecked = oElement.prop('checked');
	var sId = sChoiceId.replace('choice', '');
	if ((oElement.attr('type') == 'radio') && bChecked)
	{
		// Only the radio that is clicked is notified, let's warn the other radio buttons
		sName = oElement.attr('name');
		$('input[name="'+sName+'"]').each(function() {
			var sRadioId = $(this).attr('id');
			if ((sRadioId != sChoiceId) && (sRadioId != undefined))
			{
				CheckChoice(sRadioId);
			}
		});
	}
	
	$('#sub_choices'+sId).each(function() {
		if (!bChecked)
		{
			$(this).addClass('choice-disabled');
		}
		else
		{
			$(this).removeClass('choice-disabled');
		}
		
		$('input', this).each(function() {
			if (bChecked)
			{
				if ($(this).attr('data-disabled') != 'disabled')
				{
					// Only non-mandatory fields can be enabled
					$(this).prop('disabled', false);
				}
			}
			else
			{
				$(this).prop('disabled', true);
				$(this).prop('checked', false);
			}
		});
	});
}
EOF
		);
		$oPage->add_ready_script(
			<<<EOF
		$('.wiz-choice').on('change', function() { CheckChoice($(this).attr('id')); } );
		$('.wiz-choice').trigger('change');
EOF
		);
	}

	protected function GetDefaults($aInfo, $aModules, $sParentId = '')
	{
		$aDefaults = [];
		if (!$this->bChoicesFromDatabase) {
			$this->GuessDefaultsFromModules($aInfo, $aDefaults, $aModules, $sParentId);
		} else {
			$this->GetDefaultsFromDatabase($aInfo, $aDefaults, $sParentId);
		}
		return $aDefaults;
	}

	protected function GetDefaultsFromDatabase($aInfo, &$aDefaults, $sParentId)
	{
		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : [];
		foreach ($aOptions as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($this->bUpgrade) {
				if ($this->oExtensionsMap->IsMarkedAsChosen($aChoice['extension_code'])) {
					$aDefaults[$sChoiceId] = $sChoiceId;
				}
			} elseif (isset($aChoice['default']) && $aChoice['default']) {
				$aDefaults[$sChoiceId] = $sChoiceId;
			}
			// Recurse for sub_options (if any)
			if (isset($aChoice['sub_options'])) {
				$this->GetDefaultsFromDatabase($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
		}

		$aAlternatives = $aInfo['alternatives'] ?? [];
		$sChoiceName = null;
		foreach ($aAlternatives as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null) {
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			if ($this->bUpgrade) {
				if ($this->oExtensionsMap->IsMarkedAsChosen($aChoice['extension_code'])) {
					$aDefaults[$sChoiceName] = $sChoiceId;
				}
			} elseif (isset($aChoice['default']) && $aChoice['default']) {
				$aDefaults[$sChoiceName] = $sChoiceId;
			}
			// Recurse for sub_options (if any)
			if (isset($aChoice['sub_options'])) {
				$this->GetDefaultsFromDatabase($aChoice['sub_options'], $aDefaults, $sChoiceId);
			}
		}
	}

	/**
	 * Try to guess the user choices based on the current list of installed modules...
	 * @param array $aInfo
	 * @param array $aDefaults
	 * @param array $aModules
	 * @param string $sParentId
	 * @return array
	 */
	protected function GuessDefaultsFromModules($aInfo, &$aDefaults, $aModules, $sParentId = '')
	{
		$aRetScore = [];
		$aScores = [];

		$aOptions = isset($aInfo['options']) ? $aInfo['options'] : [];
		foreach ($aOptions as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aScores[$sChoiceId] = [];
			if (!$this->bUpgrade && isset($aChoice['default']) && $aChoice['default']) {
				$aDefaults[$sChoiceId] = $sChoiceId;
			}
			if ($this->bUpgrade) {
				// In upgrade mode, the defaults are the installed modules
				foreach ($aChoice['modules'] as $sModuleId) {
					if ($aModules[$sModuleId]['installed_version'] != '') {
						// A module corresponding to this choice is installed
						$aScores[$sChoiceId][$sModuleId] = true;
					}
				}
				// Used for migration from 1.3.x or before
				// Accept that the new version can have one new module than the previous version
				// The option is still selected
				$iSelected = count($aScores[$sChoiceId]);
				$iNeeded = count($aChoice['modules']);
				if (($iSelected > 0) && (($iNeeded - $iSelected) < 2)) {
					// All the modules are installed, this choice is selected
					$aDefaults[$sChoiceId] = $sChoiceId;
				}
				$aRetScore = array_merge($aRetScore, $aScores[$sChoiceId]);
			}

			if (isset($aChoice['sub_options'])) {
				$aScores[$sChoiceId] = array_merge($aScores[$sChoiceId], $this->GuessDefaultsFromModules($aChoice['sub_options'], $aDefaults, $aModules, $sChoiceId));
			}
		}

		$aAlternatives = isset($aInfo['alternatives']) ? $aInfo['alternatives'] : [];
		$sChoiceName = null;
		foreach ($aAlternatives as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aScores[$sChoiceId] = [];
			if ($sChoiceName == null) {
				$sChoiceName = $sChoiceId;
			}
			if (!$this->bUpgrade && isset($aChoice['default']) && $aChoice['default']) {
				$aDefaults[$sChoiceName] = $sChoiceId;
			}
			if (isset($aChoice['sub_options'])) {
				// By default (i.e. install-mode), sub options can only be checked if the parent option is checked by default
				if ($this->bUpgrade || (isset($aChoice['default']) && $aChoice['default'])) {
					$aScores[$sChoiceId] = $this->GuessDefaultsFromModules($aChoice['sub_options'], $aDefaults, $aModules, $sChoiceId);
				}
			}
		}

		$iMaxScore = 0;
		if ($this->bUpgrade && (count($aAlternatives) > 0)) {
			// The installed choices have precedence over the 'default' choices
			// In case several choices share the same base modules, let's weight the alternative choices
			// based on their number of installed modules
			$sChoiceName = null;

			foreach ($aAlternatives as $index => $aChoice) {
				$sChoiceId = $sParentId.self::$SEP.$index;
				if ($sChoiceName == null) {
					$sChoiceName = $sChoiceId;
				}
				if (array_key_exists('modules', $aChoice)) {
					foreach ($aChoice['modules'] as $sModuleId) {
						if ($aModules[$sModuleId]['installed_version'] != '') {
							// A module corresponding to this choice is installed, increase the score of this choice
							if (!isset($aScores[$sChoiceId])) {
								$aScores[$sChoiceId] = [];
							}
							$aScores[$sChoiceId][$sModuleId] = true;
							$iMaxScore = max($iMaxScore, count($aScores[$sChoiceId]));
						}
					}
					//if (count($aScores[$sChoiceId]) == count($aChoice['modules']))
					//{
					//	$iScore += 100; // Bonus for the parent when a choice is complete
					//}
					$aRetScore = array_merge($aRetScore, $aScores[$sChoiceId]);
				}
				$iMaxScore = max($iMaxScore, isset($aScores[$sChoiceId]) ? count($aScores[$sChoiceId]) : 0);
			}
		}
		if ($iMaxScore > 0) {
			$aNumericScores = [];
			foreach ($aScores as $sChoiceId => $aModules) {
				$aNumericScores[$sChoiceId] = count($aModules);
			}
			// The choice with the bigger score wins !
			asort($aNumericScores, SORT_NUMERIC);
			$aKeys = array_keys($aNumericScores);
			$sBetterChoiceId = array_pop($aKeys);
			$aDefaults[$sChoiceName] = $sBetterChoiceId;
		}
		// echo "Scores: <pre>".print_r($aScores, true)."</pre><br/>";
		// echo "Defaults: <pre>".print_r($aDefaults, true)."</pre><br/>";
		return $aRetScore;
	}

	private function GetPhpExpressionEvaluator(): PhpExpressionEvaluator
	{
		if (!isset($this->oPhpExpressionEvaluator)) {
			$this->oPhpExpressionEvaluator = new PhpExpressionEvaluator([], ModuleFileReader::STATIC_CALL_AUTOSELECT_WHITELIST);
		}

		return $this->oPhpExpressionEvaluator;
	}

	/**
	 * Converts the list of selected "choices" into a list of "modules": take into account the selected and the mandatory modules
	 *
	 * @param array $aInfo Info about the "choice" array('options' => array(...), 'alternatives' => array(...))
	 * @param array $aSelectedChoices List of selected choices array('name' => 'selected_value_id')
	 * @param array $aModules Return parameter: List of selected modules array('module_id' => true)
	 * @param string $sParentId Used for recursion
	 *
	 * @return string A text representation of what will be installed
	 */
	public function GetSelectedModules($aInfo, $aSelectedChoices, &$aModules, $sParentId = '', $sDisplayChoices = '', &$aSelectedExtensions = null)
	{
		if ($sParentId == '') {
			// Check once (before recursing) that the hidden modules are selected
			foreach ($this->aAnalyzeInstallationModules as $sModuleId => $aModule) {
				if (($sModuleId != ROOT_MODULE) && !isset($aModules[$sModuleId])) {
					if (($aModule['category'] == 'authentication') || (!$aModule['visible'] && !isset($aModule['auto_select']))) {
						$aModules[$sModuleId] = true;
						$sDisplayChoices .= '<li><i>'.$aModule['label'].' (hidden)</i></li>';
					}
				}
			}
		}
		$aOptions = $aInfo['options'] ?? [];
		foreach ($aOptions as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aModuleInfo = [];
			// Get the extension corresponding to the choice
			foreach ($this->oExtensionsMap->GetAllExtensions() as $sExtensionVersion => $oExtension) {
				if (utils::StartsWith($sExtensionVersion, $aChoice['extension_code'].'/')) {
					$aModuleInfo = $oExtension->aModuleInfo;
					break;
				}
			}
			if ((isset($aChoice['mandatory']) && $aChoice['mandatory']) ||
				(isset($aSelectedChoices[$sChoiceId]) && ($aSelectedChoices[$sChoiceId] == $sChoiceId))) {
				$sDisplayChoices .= '<li>'.$aChoice['title'].'</li>';
				if (isset($aChoice['modules'])) {
					if (count($aChoice['modules']) === 0) {
						throw new Exception('Extension '.$aChoice['extension_code'].' does not have any module associated');
					}
					foreach ($aChoice['modules'] as $sModuleId) {
						$bSelected = true;
						if (isset($aModuleInfo[$sModuleId])) {
							/** @var array $aCurrentModuleInfo */
							$aCurrentModuleInfo = $aModuleInfo[$sModuleId];
							if (isset($aCurrentModuleInfo['auto_select'])) {
								// Check the module selection
								try {
									SetupInfo::SetSelectedModules($aModules);
									$bSelected = $this->GetPhpExpressionEvaluator()->ParseAndEvaluateBooleanExpression($aCurrentModuleInfo['auto_select']);
								} catch (ModuleFileReaderException $e) {
									//logged already
									$bSelected = false;
								}
							}
						}
						if ($bSelected) {
							$aModules[$sModuleId] = true; // store the Id of the selected module
							SetupInfo::SetSelectedModules($aModules);
						}
					}
				}
				if ($aSelectedExtensions !== null) {
					$aSelectedExtensions[] = $aChoice['extension_code'];
				}
				// Recurse only for selected choices
				if (isset($aChoice['sub_options'])) {
					$sDisplayChoices .= '<ul>';
					$sDisplayChoices = $this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId, $sDisplayChoices, $aSelectedExtensions);
					$sDisplayChoices .= '</ul>';
				}
				$sDisplayChoices .= '</li>';
			}
		}

		$aAlternatives = $aInfo['alternatives'] ?? [];
		$sChoiceName = null;
		foreach ($aAlternatives as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null) {
				$sChoiceName = $sChoiceId;
			}
			if ((isset($aChoice['mandatory']) && $aChoice['mandatory']) ||
				(isset($aSelectedChoices[$sChoiceName]) && ($aSelectedChoices[$sChoiceName] == $sChoiceId))) {
				$sDisplayChoices .= '<li>'.$aChoice['title'].'</li>';
				if ($aSelectedExtensions !== null) {
					$aSelectedExtensions[] = $aChoice['extension_code'];
				}
				if (isset($aChoice['modules'])) {
					foreach ($aChoice['modules'] as $sModuleId) {
						$aModules[$sModuleId] = true; // store the Id of the selected module
					}
				}
				// Recurse only for selected choices
				if (isset($aChoice['sub_options'])) {
					$sDisplayChoices .= '<ul>';
					$sDisplayChoices = $this->GetSelectedModules($aChoice['sub_options'], $aSelectedChoices, $aModules, $sChoiceId, $sDisplayChoices, $aSelectedExtensions);
					$sDisplayChoices .= '</ul>';
				}
				$sDisplayChoices .= '</li>';
			}
		}
		if ($sParentId == '') {
			// Last pass (after all the user's choices are turned into "selected" modules):
			// Process 'auto_select' modules for modules that are not already selected
			do {
				// Loop while new modules are added...
				$bModuleAdded = false;
				foreach ($this->aAnalyzeInstallationModules as $sModuleId => $aModule) {
					if (($sModuleId != ROOT_MODULE) && !array_key_exists($sModuleId, $aModules) && isset($aModule['auto_select'])) {
						try {
							SetupInfo::SetSelectedModules($aModules);
							$bSelected = $this->GetPhpExpressionEvaluator()->ParseAndEvaluateBooleanExpression($aModule['auto_select']);
							if ($bSelected) {
								$aModules[$sModuleId] = true; // store the Id of the selected module
								$sDisplayChoices .= '<li>'.$aModule['label'].' (auto_select)</li>';
								$bModuleAdded  = true;
							}
						} catch (ModuleFileReaderException $e) {
							//logged already
							$sDisplayChoices .= '<li><b>Warning: auto_select failed with exception ('.$e->getMessage().') for module "'.$sModuleId.'"</b></li>';
						}
					}
				}
			} while ($bModuleAdded);
		}

		return $sDisplayChoices;
	}

	protected function GetStepIndex()
	{
		switch ($this->sCurrentState) {
			case 'start_install':
			case 'start_upgrade':
				$index = 0;
				break;

			default:
				$index = (int)$this->sCurrentState;
		}
		return $index;
	}

	protected function GetStepInfo($idx = null)
	{
		$index = $idx ?? $this->GetStepIndex();
		$bRemoteExtensionsShouldBeMandatory = !$this->oWizard->GetParameter('force-uninstall', false);
		if (is_null($this->aSteps)) {
			$this->oWizard->SetParameter('additional_extensions_modules', json_encode([])); // Default value, no additional extensions

			if (@file_exists($this->GetSourceFilePath())) {
				// Found an "installation.xml" file, let's use this definition for the wizard
				$aParams = new XMLParameters($this->GetSourceFilePath());
				$this->aSteps = $aParams->Get('steps', []);

				if ($index + 1 >= count($this->aSteps)) {
					//make sure we also cache next step as well
					$aOptions = $this->oExtensionsMap->GetAllExtensionsOptionInfo($bRemoteExtensionsShouldBeMandatory);
					// Display this step of the wizard only if there is something to display
					if (count($aOptions) > 0) {
						$this->aSteps[] = [
							'title'       => 'Extensions',
							'description' => '<h2>Select additional extensions to install. You can launch the installation again to install new extensions or remove installed ones.</h2>',
							'banner'      => '/images/icons/icons8-puzzle.svg',
							'options'     => $aOptions,
						];
						$this->oWizard->SetParameter('additional_extensions_modules', json_encode($aOptions));
					}
				}
			} else {
				//legacy product (1.x/no installation.xml)
				$aOptions = $this->oExtensionsMap->GetAllExtensionsOptionInfo($bRemoteExtensionsShouldBeMandatory);

				// No wizard configuration provided, build a standard one with just one big list. All items are mandatory, only works when there are no conflicted modules.
				$this->aSteps = [
					[
					'title'       => 'Modules Selection',
					'description' => '<h2>Select the modules to install. You can launch the installation again to install new modules, but you cannot remove already installed modules.</h2>',
					'banner'      => '/images/icons/icons8-apps-tab.svg',
					'options'     => $aOptions,
					],
				];
			}
		}
		return $this->aSteps[$index] ?? null;
	}

	public function ComputeChoiceFlags(array $aChoice, string $sChoiceId, array $aSelectedComponents, bool $bAllDisabled, bool $bDisableUninstallCheck, bool $bUpgradeMode)
	{
		$oITopExtension = $this->oExtensionsMap->GetFromExtensionCode($aChoice['extension_code']);
		//If the extension is missing from disk, it won't exist in the ExtensionsMap, thus returning null
		$bCanBeUninstalled = isset($aChoice['uninstallable']) ? $aChoice['uninstallable'] === true || $aChoice['uninstallable'] === 'yes' : $oITopExtension->CanBeUninstalled();
		$bSelected = isset($aSelectedComponents[$sChoiceId]) && ($aSelectedComponents[$sChoiceId] === $sChoiceId);
		$bMissingFromDisk = isset($aChoice['missing']) && $aChoice['missing'] === true;
		$bMandatory = (isset($aChoice['mandatory']) && $aChoice['mandatory']);
		$bInstalled = $bMissingFromDisk || $oITopExtension->bInstalled;

		$bChecked = $bSelected;
		$bDisabled = false;
		if ($bMissingFromDisk) {
			$bDisabled = true;
			$bChecked = false;
		} elseif ($bMandatory) {
			$bDisabled = true;
			$bChecked = true;
		} elseif ($bInstalled && !$bCanBeUninstalled && !$bDisableUninstallCheck) {
			$bChecked = true;
			$bDisabled = true;
		}

		if ($bAllDisabled) {
			$bDisabled = true;
		}

		if (isset($aChoice['sub_options'])) {
			$aOptions = $aChoice['sub_options']['options'] ?? [];
			foreach ($aOptions as $index => $aSubChoice) {
				$sSubChoiceId = $sChoiceId.self::$SEP.$index;
				$aSubFlags = $this->ComputeChoiceFlags($aSubChoice, $sSubChoiceId, $aSelectedComponents, $bAllDisabled, $bDisableUninstallCheck, $bUpgradeMode);
				if ($aSubFlags['checked']) {
					$bChecked = true;
					if ($aSubFlags['disabled']) {
						//If some sub options are enabled and cannot be disabled, this choice should also cannot be disabled since it would disable all its sub options
						$bDisabled = true;
					}
				}

			}
		}

		return [
			'uninstallable' => $bCanBeUninstalled,
			'missing' => $bMissingFromDisk,
			'installed' => $bInstalled,
			'disabled' => $bDisabled,
			'checked' => $bChecked,
		];
	}

	public function DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $aDefaults, $sParentId = '', $bAllDisabled = false)
	{
		$aOptions = $aStepInfo['options'] ?? [];
		$aAlternatives = $aStepInfo['alternatives'] ?? [];

		$bDisableUninstallCheck = (bool)$this->oWizard->GetParameter('force-uninstall', false);

		foreach ($aOptions as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$aFlags = $this->ComputeChoiceFlags($aChoice, $sChoiceId, $aSelectedComponents, $bAllDisabled, $bDisableUninstallCheck, $this->bUpgrade);

			if ($aFlags['disabled'] && !$aFlags['checked'] && !$aFlags['uninstallable'] && !$bDisableUninstallCheck) {
				$this->bCanMoveForward = false;//Disable "Next"
			}

			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceId, $sChoiceId, $aFlags);
		}

		$sChoiceName = null;
		$bDisabled = false;
		$sChoiceIdNone = null;
		foreach ($aAlternatives as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			if ($sChoiceName == null) {
				$sChoiceName = $sChoiceId; // All radios share the same name
			}
			//Defaults contains previous installation choices during upgrade
			$bIsDefault = array_key_exists($sChoiceName, $aDefaults) && ($aDefaults[$sChoiceName] === $sChoiceId);
			$bMandatory = (isset($aChoice['mandatory']) && $aChoice['mandatory']) || ($this->bUpgrade && $bIsDefault);
			if ($bMandatory || $bAllDisabled) {
				// One choice is mandatory, all alternatives are disabled
				$bDisabled = true;
			}
			if ((!isset($aChoice['sub_options']) || (count($aChoice['sub_options']) === 0)) && (!isset($aChoice['modules']) || (count($aChoice['modules']) === 0))) {
				//If there is no modules in the choice AND it has no sub choices, it is an empty choice.
				$sChoiceIdNone = $sChoiceId; // the "None" / empty choice
			}
		}

		if (!array_key_exists($sChoiceName, $aDefaults) || ($aDefaults[$sChoiceName] === $sChoiceIdNone)) {
			// The "none" choice does not disable the selection !!
			$bDisabled = false;
		}

		foreach ($aAlternatives as $index => $aChoice) {
			$sChoiceId = $sParentId.self::$SEP.$index;
			$bSelected = isset($aSelectedComponents[$sChoiceName]) && ($aSelectedComponents[$sChoiceName] === $sChoiceId);
			if (!isset($aSelectedComponents[$sChoiceName]) && ($sChoiceIdNone !== null)) {
				// No choice selected, select the "None" option
				$bSelected = ($sChoiceId === $sChoiceIdNone);
			}

			$aFlags = $this->ComputeChoiceFlags($aChoice, $sChoiceId, $aSelectedComponents, $bAllDisabled, $bDisableUninstallCheck, $this->bUpgrade);
			//ComputeChoiceFlags does not completely compute alternative flags
			$aFlags['disabled'] = $bDisabled;
			$aFlags['checked'] = $bSelected;
			$this->DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceName, $sChoiceId, $aFlags, 'radio');
		}
	}

	protected function DisplayChoice($oPage, $aChoice, $aSelectedComponents, $aDefaults, $sChoiceName, $sChoiceId, $aFlags, $sInputType = 'checkbox')
	{
		$sMoreInfo = (isset($aChoice['more_info']) && ($aChoice['more_info'] != '')) ? '
			<a class="setup--wizard-choice--more-info" target="_blank" href="'.$aChoice['more_info'].'">
				<i class="setup-extension--icon fas fa-external-link-alt" title="More information"></i>
			</a>' : '';
		$sDescription = isset($aChoice['description']) ? utils::EscapeHtml($aChoice['description']) : '';
		$sId = utils::EscapeHtml($aChoice['extension_code']);
		$sDataId = 'data-id="'.utils::EscapeHtml($aChoice['extension_code']).'"';
		$sDisabled = $aFlags['disabled'] ? ' disabled data-disabled="disabled"' : '';
		$sChecked = $aFlags['checked'] ? ' checked ' : '';
		$sHiddenInput = $aFlags['disabled'] && $aFlags['checked'] ? '<input type="hidden" name="choice['.$sChoiceName.']" value="'.$sChoiceId.'"/>' : '';

		$sTooltip = '';
		if ($aFlags['missing']) {
			$sTooltip .= '<div class="ibo-badge ibo-block ibo-is-red" title="The local extension folder has been removed from the disk. This will force the uninstallation of this extension." >source removed</div>';
		}
		if ($aFlags['installed']) {
			$sTooltip .= '<div class="ibo-badge ibo-block checked ibo-is-green" title="This extension is part of the current installation." >installed</div>';

			$sTooltip .= '<div class="ibo-badge ibo-block unchecked ibo-is-red" title="This extension will be uninstalled during the setup." >to be uninstalled</div>';
		} else {
			$sTooltip .= '<div class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div>';
			$sTooltip .= '<div class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>';
		}
		if (!$aFlags['uninstallable']) {
			$sTooltip .= '<div class="ibo-badge ibo-block ibo-is-orange" title="Once this extension has been installed, it should not be uninstalled." >cannot be uninstalled</div>';
		}

		$sMetadata = '';
		if (isset($aChoice['version']) && isset($aChoice['source_label'])) {
			$sMetadata = '<span>v'.$aChoice['version'].'</span><span>'.$aChoice['source_label'].'</span>';
		}
		$sChoiceDisabled = $aFlags['disabled'] && !$aFlags['checked'] ? 'choice-disabled' : '';

		$oPage->add('
			<div class="ibo-extension-details ibo-content-block ibo-block '.$sChoiceDisabled.'" '.$sDataId.'>
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="'.$sId.'" name="choice['.$sChoiceName.']" type="'.$sInputType.'" value="'.$sChoiceId.'" '.$sDisabled.$sChecked.'/>
					'.$sHiddenInput.'
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="'.$sId.'"><b>'.utils::EscapeHtml($aChoice['title']).'</b></label>
						'.$sMoreInfo.'
						'.$sTooltip.'
					</div>
					<div class="ibo-extension-details--information--metadata">
						'.$sMetadata.'
					</div>
					<div class="ibo-extension-details--information--description">
						'.$sDescription.'
		');
		$bSubOptionsDisabled = $aFlags['disabled'] && (!$aFlags['installed'] || $sInputType === 'checkbox');
		if (isset($aChoice['sub_options'])) {
			$oPage->add('<div id="sub_choices'.$sId.'">');
			$this->DisplayOptions($oPage, $aChoice['sub_options'], $aSelectedComponents, $aDefaults, $sChoiceId, $bSubOptionsDisabled);
			$oPage->add('</div>');
		}
		$oPage->add('
					</div>
				</div>
			</div>
		');
	}

	protected function GetSourceFilePath()
	{
		$sSourceDir = $this->oWizard->GetParameter('source_dir');
		return $sSourceDir.'/installation.xml';
	}

	public function CanMoveForward()
	{
		return true;
	}

	public function JSCanMoveForward()
	{

		return $this->bCanMoveForward ? 'return true;' : 'return false;';
	}

	public function GetNextButtonLabel()
	{
		if (!$this->bCanMoveForward) {
			return 'Non-uninstallable extension missing';
		}

		if ($this->GetStepInfo(1 + $this->GetStepIndex()) === null && $this->IsDataAuditEnabled()) {
			return 'Check compatibility';
		}

		return 'Next';
	}

}

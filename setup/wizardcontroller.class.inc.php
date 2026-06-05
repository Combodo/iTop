<?php

// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

require_once(APPROOT.'setup/setuputils.class.inc.php');
require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/applicationinstaller.class.inc.php');
require_once(APPROOT.'core/mutex.class.inc.php');
require_once(APPROOT.'setup/extensionsmap.class.inc.php');

/**
 * Engine for displaying the various pages of a "wizard"
 * Each "step" of the wizard must be implemented as
 * separate class derived from WizardStep. each 'step' can also have its own
 * internal 'state' for developing complex wizards.
 * The WizardController provides the "<< Back" feature by storing a stack
 * of the previous screens. The WizardController also maintains from page
 * to page a list of "parameters" to be dispayed/edited by each of the steps.
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizardController
{
	protected $aWizardSteps;
	protected $sInitialStepClass;
	protected $sInitialState;
	protected $aParameters;

	/**
	 * Initiailization of the wizard controller
	 * @param string $sInitialStepClass Class of the initial step/page of the wizard
	 * @param string $sInitialState Initial state of the initial page (if this class manages states)
	 */
	public function __construct($sInitialStepClass, $sInitialState = '')
	{
		$this->sInitialStepClass = $sInitialStepClass;
		$this->sInitialState = $sInitialState;
		$this->aParameters = [];
		$this->aWizardSteps = [];
	}

	/**
	 * Pushes information about the current step onto the stack
	 * @param array $aStepInfo Array('class' => , 'state' => )
	 */
	protected function PushStep($aStepInfo)
	{
		array_push($this->aWizardSteps, $aStepInfo);
	}

	/**
	 * Removes information about the previous step from the stack
	 * @return array{'class': string, 'state': string}
	 */
	protected function PopStep()
	{
		return array_pop($this->aWizardSteps);
	}

	/**
	 * Reads a "persistent" parameter from the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value of the parameter in case it was not set
	 */
	public function GetParameter($sParamCode, $defaultValue = '')
	{
		if (array_key_exists($sParamCode, $this->aParameters)) {
			return $this->aParameters[$sParamCode];
		}

		return $defaultValue;
	}

	/**
	 * @return array Allow to update config using {@see Config::UpdateFromParams()}
	 *
	 * @since 3.1.0 N°2013
	 */
	public function GetParamForConfigArray(): array
	{
		/** @noinspection PhpUnnecessaryLocalVariableInspection */
		$aParamValues = [
			'db_server'      => $this->GetParameter('db_server', ''),
			'db_user'        => $this->GetParameter('db_user', ''),
			'db_pwd'         => $this->GetParameter('db_pwd', ''),
			'db_name'        => $this->GetParameter('db_name', ''),
			'db_prefix'      => $this->GetParameter('db_prefix', ''),
			'db_tls_enabled' => $this->GetParameter('db_tls_enabled', false),
			'db_tls_ca'      => $this->GetParameter('db_tls_ca', ''),
		];

		return $aParamValues;
	}

	/**
	 * Stores a "persistent" parameter in the wizard's context
	 *
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $value The value to store
	 */
	public function SetParameter($sParamCode, $value)
	{
		$this->aParameters[$sParamCode] = $value;
	}

	/**
	 * Stores the value of the page's parameter in a "persistent" parameter in the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value for the parameter
	 * @param string $sSanitizationFilter A 'sanitization' fitler. Default is 'raw_data', which means no filtering
	 */
	public function SaveParameter($sParamCode, $defaultValue, $sSanitizationFilter = 'raw_data')
	{
		$value = utils::ReadParam($sParamCode, $defaultValue, false, $sSanitizationFilter);
		$this->aParameters[$sParamCode] = $value;
	}

	/**
	 * Starts the wizard by displaying it in its initial state
	 */
	public function Start()
	{
		$sCurrentStepClass = $this->sInitialStepClass;
		$oStep = $this->GetWizardStep($sCurrentStepClass, $this->sInitialState);
		$this->DisplayStep($oStep);
	}
	/**
	 * Progress towards the next step of the wizard
	 * @throws Exception
	 */
	protected function Next()
	{
		$sCurrentStepClass = utils::ReadParam('_class', $this->sInitialStepClass);
		$sCurrentState = utils::ReadParam('_state', $this->sInitialState);
		$oStep = $this->GetWizardStep($sCurrentStepClass, $sCurrentState);
		if ($oStep->ValidateParams()) {
			$aPossibleSteps = $oStep->GetPossibleSteps();
			if ($oStep->CanMoveBackward()) {
				$this->PushStep(['class' => $sCurrentStepClass, 'state' => $sCurrentState]);
			}
			$oWizardState = $oStep->UpdateWizardStateAndGetNextStep(true); // true => moving forward
			if (in_array($oWizardState->GetNextStep(), $aPossibleSteps)) {
				$oNextStep = $this->GetWizardStep($oWizardState->GetNextStep(), $oWizardState->GetState());
				$this->DisplayStep($oNextStep);
			} else {
				throw new Exception("Internal error: Unexpected next step '{$oWizardState->GetNextStep()}'. The possible next steps are: ".implode(', ', $aPossibleSteps));
			}
		} else {
			$this->DisplayStep($oStep);
		}
	}

	/**
	 * Move one step back
	 */
	protected function Back()
	{
		// let the current step save its parameters
		$sCurrentStepClass = utils::ReadParam('_class', $this->sInitialStepClass);
		$sCurrentState = utils::ReadParam('_state', $this->sInitialState);
		$oStep = $this->GetWizardStep($sCurrentStepClass, $sCurrentState);
		$oStep->UpdateWizardStateAndGetNextStep(false); // false => Moving backwards

		// Display the previous step
		$aCurrentStepInfo = $this->PopStep();
		$oStep = $this->GetWizardStep($aCurrentStepInfo['class'], $aCurrentStepInfo['state']);
		$this->DisplayStep($oStep);
	}

	/**
	 * Displays the specified 'step' of the wizard
	 *
	 * @param WizardStep $oStep The 'step' to display
	 *
	 * @throws \Exception
	 */
	protected function DisplayStep(WizardStep $oStep): void
	{
		SetupLog::Info("=== Setup screen: ".$oStep->GetTitle().' ('.get_class($oStep).')');
		$oPage = new SetupPage($oStep->GetTitle());
		if ($oStep->RequiresWritableConfig()) {
			$sConfigFile = utils::GetConfigFilePath(ITOP_DEFAULT_ENV);
			if (file_exists($sConfigFile)) {
				// The configuration file already exists
				if (!is_writable($sConfigFile)) {
					SetupUtils::ExitReadOnlyMode(false); // Reset readonly mode in case of problem
					SetupUtils::EraseSetupToken();
					$sRelativePath = utils::GetConfigFilePathRelative(ITOP_DEFAULT_ENV);
					$oP = new SetupPage('Installation Cannot Continue');
					$oP->add("<h2>Fatal error</h2>\n");
					$oP->error("<b>Error:</b> the configuration file '".$sRelativePath."' already exists and cannot be overwritten.");
					$oP->p("The wizard cannot modify the configuration file for you. If you want to upgrade ".ITOP_APPLICATION.", make sure that the file '<b>".$sRelativePath."</b>' can be modified by the web server.");

					$sButtonsHtml = <<<HTML
<button type="button" class="ibo-button ibo-is-regular ibo-is-primary" onclick="window.location.reload()">Reload</button>
HTML;
					$oP->p($sButtonsHtml);

					$oP->output();
					// Prevent token creation
					exit;
				}
			}
		}
		$oPage->LinkScriptFromAppRoot('setup/setup.js');
		$oPage->add('<form id="wiz_form" class="ibo-setup--wizard" method="post">');
		$oPage->add('<div class="ibo-setup--wizard--content">');
		$oStep->Display($oPage);
		$oPage->add('</div>');
		$oPage->add_script("function CanMoveForward()\n{\n".$oStep->JSCanMoveForward()."\n}\n");
		$oPage->add_script("function CanMoveBackward()\n{\n".$oStep->JSCanMoveBackward()."\n}\n");

		// Add the back / next buttons and the hidden form
		// to store the parameters
		$oPage->add('<input type="hidden" id="_class" name="_class" value="'.get_class($oStep).'"/>');
		$oPage->add('<input type="hidden" id="_state" name="_state" value="'.$oStep->GetState().'"/>');
		foreach ($this->aParameters as $sCode => $value) {
			$oPage->add('<input type="hidden" name="_params['.$sCode.']" value="'.utils::EscapeHtml($value).'"/>');
		}

		$oPage->add('<input type="hidden" name="_steps" value="'.utils::EscapeHtml(json_encode($this->aWizardSteps)).'"/>');
		$oPage->add('<table style="width:100%;" class="ibo-setup--wizard--buttons-container"><tr>');
		if (count($this->aWizardSteps) > 0) {
			if ($oStep->CanMoveBackward()) {
				$oPage->add('<td style="text-align: left"><button id="btn_back" class="ibo-button ibo-is-alternative ibo-is-neutral" type="submit" name="operation" value="back"><span class="ibo-button--label">Back</span></button></td>');
			} else {
				$oPage->add('<td style="text-align: left"><button id="btn_back" class="ibo-button ibo-is-alternative ibo-is-neutral ibo-is-hidden" type="submit" name="operation" value="back"><span class="ibo-button--label">Back</span></button></td>');
			}
		}
		if ($oStep->CanMoveForward()) {
			$oPage->add('<td style="text-align:right;"><button id="btn_next" class="default ibo-button ibo-is-regular ibo-is-primary" type="submit" name="operation" value="next"><span class="ibo-button--label">'.utils::EscapeHtml($oStep->GetNextButtonLabel()).'</span></button></td>');
		}
		$oPage->add('</tr></table>');
		$oPage->add("</form>");
		$oStep->PostFormDisplay($oPage);
		$oPage->add('<div id="async_action" style="display:none;overflow:auto;max-height:100px;color:#F00;font-size:small;"></div>'); // The div may become visible in case of error

		// Hack to have the "Next >>" button, be the default button, since the first submit button in the form is the default one
		$oPage->add_ready_script(
			<<<EOF

$('form').each(function () {
	var thisform = $(this);
		thisform.prepend(thisform.find('button.default').clone().removeAttr('id').prop('disabled', false).css({
		position: 'absolute',
		left: '-999px',
		top: '-999px',
		height: 0,
		width: 0
	}));
});
$('#btn_back').on('click', function() { $('#wiz_form').data('back', true); });

$('#wiz_form').on('submit', function() {
	if ($(this).data('back'))
	{
		return CanMoveBackward();
	}
	else
	{
		return CanMoveForward();
	} 
});

$('#wiz_form').data('back', false);
WizardUpdateButtons();

EOF
		);
		$oPage->output();
	}
	/**
	 * Make the wizard run: Start, Next or Back depending WizardUpdateButtons();
on the page's parameters
	 */
	public function Run()
	{
		/**
		 * @since 3.2.0 Add the ContextTag init
		 * @noinspection PhpUnusedLocalVariableInspection
		 */
		$oContextTag = new ContextTag(ContextTag::TAG_SETUP);

		$sOperation = utils::ReadParam('operation');
		$this->aParameters = utils::ReadParam('_params', [], false, 'raw_data');
		$this->SetWizardSteps(json_decode(utils::ReadParam('_steps', '[]', false, 'raw_data'), true));

		switch ($sOperation) {
			case 'next':
				$this->Next();
				break;

			case 'back':
				$this->Back();
				break;

			default:
				$this->Start();
		}
	}

	/**
	 * Provides information about the structure/workflow of the wizard by listing
	 * the possible list of 'steps' and their dependencies
	 * @param string $sStep Name of the class to start from (used for recursion)
	 * @param array $aAllSteps List of steps (used for recursion)
	 */
	public function DumpStructure($sStep = '', $aAllSteps = null)
	{
		if ($aAllSteps == null) {
			$aAllSteps = [];
		}
		if ($sStep == '') {
			$sStep = $this->sInitialStepClass;
		}

		$oStep = $this->GetWizardStep($sStep);
		$aAllSteps[$sStep] = $oStep->GetPossibleSteps();
		foreach ($aAllSteps[$sStep] as $sNextStep) {
			if (!array_key_exists($sNextStep, $aAllSteps)) {
				$aAllSteps = $this->DumpStructure($sNextStep, $aAllSteps);
			}
		}

		return $aAllSteps;
	}

	/**
	 * Dump the wizard's structure as a string suitable to produce a chart
	 * using graphviz's "dot" program
	 * @return string The 'dot' formatted output
	 */
	public function DumpStructureAsDot()
	{
		$aAllSteps = $this->DumpStructure();
		$sOutput = "digraph finite_state_machine {\n";
		//$sOutput .= "\trankdir=LR;";
		$sOutput .= "\tsize=\"10,12\"\n";

		$aDeadEnds = [$this->sInitialStepClass];
		foreach ($aAllSteps as $sStep => $aNextSteps) {
			if (count($aNextSteps) == 0) {
				$aDeadEnds[] = $sStep;
			}
		}
		$sOutput .= "\tnode [shape = doublecircle]; ".implode(' ', $aDeadEnds).";\n";
		$sOutput .= "\tnode [shape = box];\n";
		foreach ($aAllSteps as $sStep => $aNextSteps) {
			$oStep = $this->GetWizardStep($sStep);
			$sOutput .= "\t$sStep [ label = \"".$oStep->GetTitle()."\"];\n";
			if (count($aNextSteps) > 0) {
				foreach ($aNextSteps as $sNextStep) {
					$sOutput .= "\t$sStep -> $sNextStep;\n";
				}
			}
		}
		$sOutput .= "}\n";
		return $sOutput;
	}

	public function SetWizardSteps(array $aWizardSteps): void
	{
		$this->aWizardSteps = $aWizardSteps;
	}

	/**
	 * @param string $sCurrentStepClass
	 * @param string $sCurrentState
	 *
	 * @return \WizardStep
	 * @throws \Exception
	 */
	private function GetWizardStep(string $sCurrentStepClass, string $sCurrentState = ''): WizardStep
	{
		if (!is_subclass_of($sCurrentStepClass, WizardStep::class)) {
			throw new Exception('Unknown step '.$sCurrentStepClass);
		}
		return new $sCurrentStepClass($this, $sCurrentState);
	}
}

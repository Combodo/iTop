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
 * All the steps of the iTop installation wizard
 *
 * Steps order (can be retrieved using \WizardController::DumpStructure) :
 *
 * WizStepWelcome
 * WizStepInstallOrUpgrade
 *    +             +
 *    |             |
 *    v             +----->
 * WizStepLicense          WizStepDetectedInfo
 * WizStepDBParams           +              +
 * WizStepAdminAccount       |              |
 * WizStepInstallMiscParams  v              +------>
 *    +                    WizStepLicense2 +--> WizStepUpgradeMiscParams
 *    |                                            +
 *    +--->    <-----------------------------------+
 * WizStepModulesChoice
 * WizStepSummary
 * WizStepDone
 */

use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Abstract class to build "steps" for the wizard controller
 * If a step needs to maintain an internal "state" (for complex steps)
 * then it's up to the derived class to implement the behavior based on
 * the internal 'sCurrentState' variable.
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
abstract class WizardStep
{
	/**
	 * A reference to the WizardController
	 * @var WizardController
	 */
	protected $oWizard;
	/**
	 * Current 'state' of the wizard step. Simple 'steps' can ignore it
	 * @var string
	 */
	protected $sCurrentState;

	protected $bDependencyCheck = null;
	protected $sDependencyIssue = null;

	/**
	 * Add post display stuff to the setup screen
	 * @param \SetupPage $oPage
	 *
	 * @return void
	 */
	public function PostFormDisplay(SetupPage $oPage)
	{
	}

	protected function CheckDependencies()
	{
		if (is_null($this->bDependencyCheck)) {
			$aSelectedModules = json_decode($this->oWizard->GetParameter('selected_modules'), true);
			$this->bDependencyCheck = true;
			try {
				SetupUtils::AnalyzeInstallation($this->oWizard, true, $aSelectedModules);
			} catch (MissingDependencyException $e) {
				$this->bDependencyCheck = false;
				$this->sDependencyIssue = $e->getHtmlDesc();
			}
		}
		return $this->bDependencyCheck;
	}

	public function __construct(WizardController $oWizard, $sCurrentState)
	{
		$this->oWizard = $oWizard;
		$this->sCurrentState = $sCurrentState;
	}

	public function GetState()
	{
		return $this->sCurrentState;
	}

	/**
	 * Displays the wizard page for the current class/state
	 * The page can contain any number of "<input/>" fields, but no "<form>...</form>" tag
	 * The name of the input fields (and their id if one is supplied) MUST NOT start with "_"
	 * (this is reserved for the wizard's own parameters)
	 * @return void
	 */
	abstract public function Display(WebPage $oPage);

	/**
	 * Processes the page's parameters and (if moving forward) returns the next step/state to be displayed
	 * @param bool $bMoveForward True if the wizard is moving forward 'Next >>' button pressed, false otherwise
	 * @return hash array('class' => $sNextClass, 'state' => $sNextState)
	 */
	abstract public function UpdateWizardStateAndGetNextStep($bMoveForward = true): WizardState;

	/**
	 * Returns the list of possible steps from this step forward
	 * @return array Array of strings (step classes)
	 */
	abstract public function GetPossibleSteps();

	/**
	 * Returns title of the current step
	 * @return string The title of the wizard page for the current step
	 */
	abstract public function GetTitle();

	/**
	 * Tells whether the parameters are Ok to move forward
	 * @return boolean True to move forward, false to stey on the same step
	 */
	public function ValidateParams()
	{
		return true;
	}

	/**
	 * Tells whether this step/state is the last one of the wizard (dead-end)
	 * @return boolean True if the 'Next >>' button should be displayed
	 */
	public function CanMoveForward()
	{
		return true;
	}

	/**
	 * Tells whether the user will come back to this step/state if he click on "Back"
	 * @return boolean True if the 'Back' button should display this step
	 */
	public function CanComeBack()
	{
		return true;
	}

	/**
	 * Tells whether the "Next" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveForward()
	{
		return 'return true;';
	}

	/**
	 * Returns the label for the " Next >> " button
	 * @return string The label for the button
	 */
	public function GetNextButtonLabel()
	{
		return 'Next';
	}

	/**
	 * Tells whether this step/state allows to go back or not
	 * @return boolean True if the '<< Back' button should be displayed
	 */
	public function CanMoveBackward()
	{
		return true;
	}

	/**
	 * Tells whether the "Back" button should be enabled interactively
	 * @return string A piece of javascript code returning either true or false
	 */
	public function JSCanMoveBackward()
	{
		return 'return true;';
	}

	/**
	 * Tells whether this step of the wizard requires that the configuration file be writable
	 * @return bool True if the wizard will possibly need to modify the configuration at some point
	 */
	public function RequiresWritableConfig()
	{
		return true;
	}

	/**
	 * Overload this function to implement asynchronous action(s) (AJAX)
	 * @param string $sCode The code of the action (if several actions need to be distinguished)
	 * @param hash $aParameters The action's parameters name => value
	 */
	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
	}
}

/*
 * Example of a simple Setup Wizard with some parameters to store
 * the installation mode (install | upgrade) and a simple asynchronous
 * (AJAX) action.
 *
 * The setup wizard is executed by the following code:
 *
 * $oWizard = new WizardController('Step1');
 * $oWizard->Run();
 *
class Step1 extends WizardStep
{
	public function GetTitle()
	{
		return 'Welcome';
	}

	public function GetPossibleSteps()
	{
		return array(Step2::class, Step2bis::class);
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sNextStep = '';
		$sInstallMode = utils::ReadParam('install_mode');
		if ($sInstallMode == 'install')
		{
			$this->oWizard->SetParameter('install_mode', 'install');
			$sNextStep = Step2::class;
		}
		else
		{
			$this->oWizard->SetParameter('install_mode', 'upgrade');
			$sNextStep = Step2bis::class;

		}
		return array('class' => $sNextStep, 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->p('This is Step 1!');
		$sInstallMode = $this->oWizard->GetParameter('install_mode', 'install');
		$sChecked = ($sInstallMode == 'install') ? ' checked ' : '';
		$oPage->p('<input type="radio" name="install_mode" value="install"'.$sChecked.'/> Install');
		$sChecked = ($sInstallMode == 'upgrade') ? ' checked ' : '';
		$oPage->p('<input type="radio" name="install_mode" value="upgrade"'.$sChecked.'/> Upgrade');
	}
}

class Step2 extends WizardStep
{
	public function GetTitle()
	{
		return 'Installation Parameters';
	}

	public function GetPossibleSteps()
	{
		return array(Step3::class);
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => Step3::class, 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->p('This is Step 2! (Installation)');
	}
}

class Step2bis extends WizardStep
{
	public function GetTitle()
	{
		return 'Upgrade Parameters';
	}

	public function GetPossibleSteps()
	{
		return array(Step2ter::class);
	}

	public function ProcessParams($bMoveForward = true)
	{
		$sUpgradeInfo = utils::ReadParam('upgrade_info');
		$this->oWizard->SetParameter('upgrade_info', $sUpgradeInfo);
		$sAdditionalUpgradeInfo = utils::ReadParam('additional_upgrade_info');
		$this->oWizard->SetParameter('additional_upgrade_info', $sAdditionalUpgradeInfo);
		return array('class' => Step2ter::class, 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->p('This is Step 2bis! (Upgrade)');
		$sUpgradeInfo = $this->oWizard->GetParameter('upgrade_info', '');
		$oPage->p('Type your name here: <input type="text" id="upgrade_info" name="upgrade_info" value="'.$sUpgradeInfo.'" size="20"/><span id="v_upgrade_info"></span>');
		$sAdditionalUpgradeInfo = $this->oWizard->GetParameter('additional_upgrade_info', '');
		$oPage->p('The installer replies: <input type="text" name="additional_upgrade_info" value="'.$sAdditionalUpgradeInfo.'" size="20"/>');

		$oPage->add_ready_script("$('#upgrade_info').change(function() {
			$('#v_upgrade_info').html('<img src=\"../images/indicator.gif\"/>');
			WizardAsyncAction('', { upgrade_info: $('#upgrade_info').val() }); });");
	}

	public function AsyncAction(WebPage $oPage, $sCode, $aParameters)
	{
		usleep(300000); // 300 ms
		$sName = $aParameters['upgrade_info'];
		$sReply = addslashes("Hello ".$sName);

		$oPage->add_ready_script(
<<<EOF
	$("#v_upgrade_info").html('');
	$("input[name=additional_upgrade_info]").val("$sReply");
EOF
		);
	}
}

class Step2ter extends WizardStep
{
	public function GetTitle()
	{
		return 'Additional Upgrade Info';
	}

	public function GetPossibleSteps()
	{
		return array(Step3::class);
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => Step3::class, 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->p('This is Step 2ter! (Upgrade)');
	}
}

class Step3 extends WizardStep
{
	public function GetTitle()
	{
		return 'Installation Complete';
	}

	public function GetPossibleSteps()
	{
		return array();
	}

	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => '', 'state' => '');
	}

	public function Display(WebPage $oPage)
	{
		$oPage->p('This is the FINAL Step');
	}

	public function CanMoveForward()
	{
		return  false;
	}
}

End of the example */

<?php
// Copyright (C) 2010-2012 Combodo SARL
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

/**
 * Engine for displaying the various pages of a "wizard"
 * Each "step" of the wizard must be implemented as 
 * separate class derived from WizardStep. each 'step' can also have its own
 * internal 'state' for developing complex wizards.
 * The WizardController provides the "<< Back" feature by storing a stack
 * of the previous screens. The WizardController also maintains from page
 * to page a list of "parameters" to be dispayed/edited by each of the steps.
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class WizardController
{
	protected $aSteps;
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
		$this->aParameters = array();
		$this->aSteps = array();
	}
	
	/**
	 * Pushes information about the current step onto the stack
	 * @param hash $aStepInfo Array('class' => , 'state' => )
	 */
	protected function PushStep($aStepInfo)
	{
		array_push($this->aSteps, $aStepInfo);
	}
	
	/**
	 * Removes information about the previous step from the stack
	 * @return hash Array('class' => , 'state' => )
	 */
	protected function PopStep()
	{
		return array_pop($this->aSteps);
	}
	
	/**
	 * Reads a "persistent" parameter from the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value of the parameter in case it was not set
	 */
	public function GetParameter($sParamCode, $defaultValue = '')
	{
		if (array_key_exists($sParamCode, $this->aParameters))
		{
			return $this->aParameters[$sParamCode];
		}
		return $defaultValue;
	}

	/**
	 * Stores a "persistent" parameter in the wizard's context
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
	protected function Start()
	{
		$sCurrentStepClass = $this->sInitialStepClass;
		$oStep = new $sCurrentStepClass($this, $this->sInitialState);
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
		$oStep = new $sCurrentStepClass($this, $sCurrentState);
		if ($oStep->ValidateParams($sCurrentState))
		{
			$this->PushStep(array('class' => $sCurrentStepClass, 'state' => $sCurrentState));
			$aPossibleSteps = $oStep->GetPossibleSteps();
			$aNextStepInfo = $oStep->ProcessParams(true); // true => moving forward
			if (in_array($aNextStepInfo['class'], $aPossibleSteps))
			{
				$oNextStep = new $aNextStepInfo['class']($this, $aNextStepInfo['state']);
				$this->DisplayStep($oNextStep);
			}
			else
			{
				throw new Exception("Internal error: Unexpected next step '{$aNextStepInfo['class']}'. The possible next steps are: ".implode(', ', $aPossibleSteps));
			}
		}
		else
		{
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
		$oStep = new $sCurrentStepClass($this, $sCurrentState);
		$aNextStepInfo = $oStep->ProcessParams(false); // false => Moving backwards
		
		// Display the previous step
		$aCurrentStepInfo = $this->PopStep();
		$oStep = new $aCurrentStepInfo['class']($this, $aCurrentStepInfo['state']);
		$this->DisplayStep($oStep);
	}
	
	/**
	 * Displays the specified 'step' of the wizard
	 * @param WizardStep $oStep The 'step' to display
	 */
	protected function DisplayStep(WizardStep $oStep)
	{
		$oPage = new SetupPage($oStep->GetTitle());
		if ($oStep->RequiresWritableConfig())
		{
			$sConfigFile = utils::GetConfigFilePath();
			if (file_exists($sConfigFile))
			{
				// The configuration file already exists
				if (!is_writable($sConfigFile))
				{
					$oP = new SetupPage('Installation Cannot Continue');
					$oP->add("<h2>Fatal error</h2>\n");
					$oP->error("<b>Error:</b> the configuration file '".$sConfigFile."' already exists and cannot be overwritten.");
					$oP->p("The wizard cannot modify the configuration file for you. If you want to upgrade ".ITOP_APPLICATION.", make sure that the file '<b>".realpath($sConfigFile)."</b>' can be modified by the web server.");
					$oP->p('<button type="button" onclick="window.location.reload()">Reload</button>');
					$oP->output();
					return;
				}
			}			
		}
		$oPage->add_linked_script('../setup/setup.js');
		$oPage->add_script("function CanMoveForward()\n{\n".$oStep->JSCanMoveForward()."\n}\n");
		$oPage->add_script("function CanMoveBackward()\n{\n".$oStep->JSCanMoveBackward()."\n}\n");
		$oPage->add('<form id="wiz_form" method="post">');
		$oStep->Display($oPage);
		
		// Add the back / next buttons and the hidden form
		// to store the parameters
		$oPage->add('<input type="hidden" id="_class" name="_class" value="'.get_class($oStep).'"/>');
		$oPage->add('<input type="hidden" id="_state" name="_state" value="'.$oStep->GetState().'"/>');
		foreach($this->aParameters as $sCode => $value)
		{
			$oPage->add('<input type="hidden" name="_params['.$sCode.']" value="'.htmlentities($value, ENT_QUOTES, 'UTF-8').'"/>');
		}

		$oPage->add('<input type="hidden" name="_steps" value="'.htmlentities(json_encode($this->aSteps), ENT_QUOTES, 'UTF-8').'"/>');
		$oPage->add('<table style="width:100%;"><tr>');
		if ((count($this->aSteps) > 0) && ($oStep->CanMoveBackward()))
		{
			$oPage->add('<td style="text-align: left"><button id="btn_back" type="submit" name="operation" value="back"> &lt;&lt; Back </button></td>');
		}
		if ($oStep->CanMoveForward())
		{
			$oPage->add('<td style="text-align:right;"><button id="btn_next" class="default" type="submit" name="operation" value="next">'.htmlentities($oStep->GetNextButtonLabel(), ENT_QUOTES, 'UTF-8').'</button></td>');
		}
		$oPage->add('</tr></table>');
		$oPage->add("</form>");
		$oPage->add('<div id="async_action" style="display:none;overflow:auto;max-height:100px;color:#F00;font-size:small;"></div>'); // The div may become visible in case of error

		// Hack to have the "Next >>" button, be the default button, since the first submit button in the form is the default one
		$oPage->add_ready_script(
<<<EOF

$('form').each(function () {
	var thisform = $(this);
		thisform.prepend(thisform.find('button.default').clone().removeAttr('id').removeAttr('disabled').css({
		position: 'absolute',
		left: '-999px',
		top: '-999px',
		height: 0,
		width: 0
	}));
});
$('#btn_back').click(function() { $('#wiz_form').data('back', true); });

$('#wiz_form').submit(function() {
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
		$sOperation = utils::ReadParam('operation');
		$this->aParameters = utils::ReadParam('_params', array(), false, 'raw_data');
		$this->aSteps  = json_decode(utils::ReadParam('_steps', '[]', false, 'raw_data'), true /* bAssoc */);
		
		switch($sOperation)
		{
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
	 * @param hash $aAllSteps List of steps (used for recursion)
	 */
	public function DumpStructure($sStep = '', $aAllSteps = null)
	{
		if ($aAllSteps == null) $aAllSteps = array();
		if ($sStep == '') $sStep = $this->sInitialStepClass;
		
		$oStep = new $sStep($this, '');
		$aAllSteps[$sStep] = $oStep->GetPossibleSteps();
		foreach($aAllSteps[$sStep] as $sNextStep)
		{
			if (!array_key_exists($sNextStep, $aAllSteps))
			{
				$aAllSteps = $this->DumpStructure($sNextStep , $aAllSteps);
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
		
		$aDeadEnds = array($this->sInitialStepClass);
		foreach($aAllSteps as $sStep => $aNextSteps)
		{
			if (count($aNextSteps) == 0)
			{
				$aDeadEnds[] = $sStep;
			}
		}
		$sOutput .= "\tnode [shape = doublecircle]; ".implode(' ', $aDeadEnds).";\n";
		$sOutput .= "\tnode [shape = box];\n";
		foreach($aAllSteps as $sStep => $aNextSteps)
		{
			$oStep = new $sStep($this, '');
			$sOutput .= "\t$sStep [ label = \"".$oStep->GetTitle()."\"];\n";
			if (count($aNextSteps) > 0)
			{
				foreach($aNextSteps as $sNextStep)
				{
					$sOutput .= "\t$sStep -> $sNextStep;\n";
				}
			}
		}
		$sOutput .= "}\n";
		return $sOutput;
	}
}

/**
 * Abstract class to build "steps" for the wizard controller
 * If a step needs to maintain an internal "state" (for complex steps)
 * then it's up to the derived class to implement the behavior based on
 * the internal 'sCurrentState' variable.
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	abstract public function ProcessParams($bMoveForward = true);

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
		return ' Next >> ';
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
		return array('Step2', 'Step2bis');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		$sNextStep = '';
		$sInstallMode = utils::ReadParam('install_mode');
		if ($sInstallMode == 'install')
		{
			$this->oWizard->SetParameter('install_mode', 'install');
			$sNextStep = 'Step2';
		}
		else
		{
			$this->oWizard->SetParameter('install_mode', 'upgrade');
			$sNextStep = 'Step2bis';
			
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
		return array('Step3');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'Step3', 'state' => '');
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
		return array('Step2ter');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		$sUpgradeInfo = utils::ReadParam('upgrade_info');
		$this->oWizard->SetParameter('upgrade_info', $sUpgradeInfo);
		$sAdditionalUpgradeInfo = utils::ReadParam('additional_upgrade_info');
		$this->oWizard->SetParameter('additional_upgrade_info', $sAdditionalUpgradeInfo);
		return array('class' => 'Step2ter', 'state' => '');
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
		return array('Step3');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'Step3', 'state' => '');
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
<?php
// Copyright (C) 2012 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * All the steps of the iTop installation wizard
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html GPL
 */

require_once(APPROOT.'setup/setuputils.class.inc.php');

/**
 * First step of the iTop Installation Wizard: Welcome screen
 */
class WizStepWelcome extends WizardStep
{
	protected $bCanMoveForward;
	
	public function GetTitle()
	{
		return 'Welcome';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepInstallOrUpgrade');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepInstallOrUpgrade', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('First step of the installation: check of the prerequisites');
		$aResults = SetupUtils::CheckPHPVersion($oPage);
		$this->bCanMoveForward = true;
		foreach($aResults as $oCheckResult)
		{
			switch($oCheckResult->iSeverity)
			{
				case CheckResult::ERROR:
				$this->bCanMoveForward = false;
				$oPage->error($oCheckResult->sLabel);
				break;
				
				case CheckResult::WARNING:
				$oPage->warning($oCheckResult->sLabel);
				break;
				
				case CheckResult::INFO:
				$oPage->ok($oCheckResult->sLabel);
				break;
			}
		}
	}
	
	public function CanMoveForward()
	{
		return $this->bCanMoveForward;
	}
}

/**
 * Second step of the iTop Installation Wizard: Install or Upgrade
 */
class WizStepInstallOrUpgrade extends WizardStep
{
	public function GetTitle()
	{
		return 'Install or Upgrade choice';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepDetectedInfo', 'WizStepLicense');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		$sNextStep = '';
		$sInstallMode = utils::ReadParam('install_mode');
		if ($sInstallMode == 'install')
		{
			$this->oWizard->SetParameter('install_mode', 'install');
			$sNextStep = 'WizStepLicense';
		}
		else
		{
			$this->oWizard->SetParameter('install_mode', 'upgrade');
			$sNextStep = 'WizStepDetectedInfo';
			
		}
		return array('class' => $sNextStep, 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('What do you want to do?');
		$sInstallMode = $this->oWizard->GetParameter('install_mode', 'install');
		$sChecked = ($sInstallMode == 'install') ? ' checked ' : '';
		$oPage->p('<input type="radio" name="install_mode" value="install"'.$sChecked.'/> Install a new iTop');
		$sChecked = ($sInstallMode == 'upgrade') ? ' checked ' : '';
		$oPage->p('<input type="radio" name="install_mode" value="upgrade"'.$sChecked.'/> Upgrade an existing iTop');
	}
}

/**
 * Upgrade information
 */
class WizStepDetectedInfo extends WizardStep
{
	public function GetTitle()
	{
		return 'Detected Info';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeKeep', 'WizStepUpgradeAuto', 'WizStepLicense2');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepUpgradeAuto', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Info about the detected version');
	}
}

/**
 * Keep or Upgrade choice
 */
class WizStepUpgradeKeep extends WizardStep
{
	public function GetTitle()
	{
		return 'Keep or Upgrade';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepModulesChoice', 'state' => 'start_upgrade');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Keep or Upgrade the data model');
	}
}

/**
 * Automatic Upgrade info
 */
class WizStepUpgradeAuto extends WizardStep
{
	public function GetTitle()
	{
		return 'Upgrade Information';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}
	
	public function ProcessParams($bMoveForward = true)
	{

		return array('class' => 'WizStepModulesChoice', 'state' => 'start_upgrade');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Automatic Upgrade information');
	}
}
/**
 * License acceptation screen
 */
class WizStepLicense extends WizardStep
{
	public function GetTitle()
	{
		return 'License Agreement';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepDBParams');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepDBParams', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Do you accept ALL the licenses?');
	}
}

/**
 * License acceptation screen (when upgrading)
 */
class WizStepLicense2 extends WizStepLicense
{
	public function GetPossibleSteps()
	{
		return array('WizStepUpgradeKeep', 'WizStepUpgradeAuto');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepUpgradeAuto', 'state' => '');
	}
}

/**
 * Database Connection parameters screen
 */
class WizStepDBParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Database Configuration';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepAdminAccount');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepAdminAccount', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Please enter the DB parameters');
	}
}

/**
 * Administrator Account definition screen
 */
class WizStepAdminAccount extends WizardStep
{
	public function GetTitle()
	{
		return 'Administrator Account';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepMiscParams');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepMiscParams', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Please enter Admin Account name/pwd');
	}
}

/**
 * Miscellaneous Parameters (URL, Sample Data)
 */
class WizStepMiscParams extends WizardStep
{
	public function GetTitle()
	{
		return 'Miscellaneous Parameters';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepModulesChoice');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepModulesChoice', 'state' => 'start_install');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Additional Parameters (URl, Sample Data)');
	}
}

/**
 * Choice of the modules to be installed
 */
class WizStepModulesChoice extends WizardStep
{
	public function GetTitle()
	{
		return 'Modules Selection';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepSummary');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepSummary', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Select the modules to install/upgrade.');
	}
}

/**
 * Summary of the installation tasks
 */
class WizStepSummary extends WizardStep
{
	public function GetTitle()
	{
		return 'Installation summary';
	}
	
	public function GetPossibleSteps()
	{
		return array('WizStepDone');
	}
	
	public function ProcessParams($bMoveForward = true)
	{
		return array('class' => 'WizStepDone', 'state' => '');
	}
	
	public function Display(WebPage $oPage)
	{
		$oPage->p('Summary of the installation.');
	}
}

/**
 * Summary of the installation tasks
 */
class WizStepDone extends WizardStep
{
	public function GetTitle()
	{
		return 'Done';
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
		$oPage->p('Installation Completed.');
	}
	
	public function CanMoveForward()
	{
		return false;
	}
}

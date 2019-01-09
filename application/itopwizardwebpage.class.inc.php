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
 * Class iTopWizardWebPage
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('itopwebpage.class.inc.php');
/**
 * Web page to display a wizard in the iTop framework
 */
class iTopWizardWebPage extends iTopWebPage
{
	var $m_iCurrentStep;
	var $m_aSteps;
    public function __construct($sTitle, $currentOrganization, $iCurrentStep, $aSteps)
    {
    	parent::__construct($sTitle." - step $iCurrentStep of ".count($aSteps)." - ".$aSteps[$iCurrentStep - 1], $currentOrganization);
		$this->m_iCurrentStep = $iCurrentStep;
		$this->m_aSteps = $aSteps;
    }
    
    public function output()
    {
    	$aSteps = array();
    	$iIndex = 0;
    	foreach($this->m_aSteps as $sStepTitle)
    	{
    		$iIndex++;
    		$sStyle = ($iIndex == $this->m_iCurrentStep) ? 'wizActiveStep' : 'wizStep';
    		$aSteps[] = "<div class=\"$sStyle\"><span>$sStepTitle</span></div>";
    	}
    	$sWizardHeader = "<div class=\"wizHeader\"><h1>".htmlentities($this->s_title, ENT_QUOTES, 'UTF-8')."</h1>\n".implode("<div class=\"wizSeparator\"><img align=\"bottom\" src=\"../images/wizArrow.gif\"></div>", $aSteps)."<br style=\"clear:both;\"/></div>\n";
    	$this->s_content = "$sWizardHeader<div class=\"wizContainer\">".$this->s_content."</div>";
    	parent::output();
	}
}
?>

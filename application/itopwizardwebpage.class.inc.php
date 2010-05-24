<?php
// Copyright (C) 2010 Combodo SARL
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
 * Class iTopWizardWebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
    	$sWizardHeader = "<div class=\"wizHeader\"><h1>{$this->s_title}</h1>\n".implode("<div class=\"wizSeparator\"><img align=\"bottom\" src=\"../images/wizArrow.gif\"></div>", $aSteps)."<br style=\"clear:both;\"/></div>\n";
    	$this->s_content = "$sWizardHeader<div class=\"wizContainer\">".$this->s_content."</div>";
    	parent::output();
	}
}
?>

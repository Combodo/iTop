<?php
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
    	$sWizardHeader = "<div class=\"wizHeader\"><h1>{$this->s_title}</h1>\n".implode("<div class=\"wizSeparator\"><img align=\"bottom\" src=\"/images/wizArrow.gif\"></div>", $aSteps)."<br style=\"clear:both;\"/></div>\n";
    	$this->s_content = "$sWizardHeader<div class=\"wizContainer\">".$this->s_content."</div>";
    	parent::output();
	}
}
?>

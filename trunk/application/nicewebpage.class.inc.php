<?php
require_once("../application/webpage.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class nice_web_page extends web_page
{
	var $m_aReadyScripts;
	
    public function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->m_aReadyScripts = array();
		$this->add_linked_script("../js/jquery.latest.js");
		$this->add_linked_script("../js/jquery.history_remote.pack.js");
		//$this->add_linked_script("../js/ui.resizable.js");
		$this->add_linked_script("../js/ui.tabs.js");
		$this->add_linked_script("../js/hovertip.js");
		$this->add_linked_script("../js/jqModal.js");
		$this->add_linked_stylesheet("../css/light-grey.css");
		$this->add_linked_stylesheet("../js/themes/light/light.tabs.css");
		//$this->add_linked_stylesheet("../css/jquery.tabs-ie.css", "lte IE 7");
		$this->add_linked_stylesheet("../css/jqModal.css");
		$this->add_ready_script('    window.setTimeout(hovertipInit, 1);');
    }
	
	public function small_p($sText)
	{
		$this->add("<p style=\"font-size:smaller\">$sText</p>\n");
	}	

	// By Rom, used by CSVImport and Advanced search
	public function MakeClassesSelect($sName, $sDefaultValue, $iWidthPx, $iActionCode = null)
	{
		// $aTopLevelClasses = array('bizService', 'bizContact', 'logInfra', 'bizDocument');
		// These are classes wich root class is cmdbAbstractObject ! 
		$this->add("<select id=\"select_$sName\" name=\"$sName\">");
		foreach(MetaModel::GetClasses('bizmodel') as $sClassName)
		{
			if (is_null($iActionCode) || UserRights::IsActionAllowed($sClassName, $iActionCode))
			{
				$sSelected = ($sClassName == $sDefaultValue) ? " SELECTED" : "";
				$this->add("<option style=\"width: ".$iWidthPx." px;\" value=\"$sClassName\"$sSelected>$sClassName - ".MetaModel::GetClassDescription($sClassName)."</option>");
			}
		}
		$this->add("</select>");
	}

	// By Rom, used by Advanced search
	public function add_select($aChoices, $sName, $sDefaultValue, $iWidthPx)
	{
		$this->add("<select id=\"select_$sName\" name=\"$sName\">");
		foreach($aChoices as $sKey => $sValue)
		{
			$sSelected = ($sKey == $sDefaultValue) ? " SELECTED" : "";
			$this->add("<option style=\"width: ".$iWidthPx." px;\" value=\"$sKey\"$sSelected>$sValue</option>");
		}
		$this->add("</select>");
	}
	
	public function add_ready_script($sScript)
	{
		$this->m_aReadyScripts[] = $sScript;
	}
	
		/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
	 */
    public function output()
    {
        if (count($this->m_aReadyScripts)>0)
        {
			$this->add_script("\$(document).ready(function() {\n".implode("\n", $this->m_aReadyScripts)."\n});");
		}
		parent::output();
	}
}

?>

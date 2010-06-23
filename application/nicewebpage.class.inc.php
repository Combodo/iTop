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
 * Class NiceWebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once("../application/webpage.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class NiceWebPage extends WebPage
{
	var $m_aReadyScripts;
	
    public function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->m_aReadyScripts = array();
		$this->add_linked_script("../js/jquery-1.4.2.min.js");
		$this->add_linked_script("../js/jquery.history_remote.pack.js");
		$this->add_linked_stylesheet('../css/ui-lightness/jquery-ui-1.8.2.custom.css');
		$this->add_linked_script('../js/jquery-ui-1.8.2.custom.min.js');
		//$this->add_linked_script("../js/ui.resizable.js");
//		$this->add_linked_script("../js/ui.tabs.js");
		$this->add_linked_script("../js/hovertip.js");
//		$this->add_linked_script("../js/jqModal.js");
		$this->add_linked_stylesheet("../css/light-grey.css");
//		$this->add_linked_stylesheet("../js/themes/light/light.tabs.css");
		//$this->add_linked_stylesheet("../css/jquery.tabs-ie.css", "lte IE 7");
//		$this->add_linked_stylesheet("../css/jqModal.css");
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
		$aValidClasses = array();
		foreach(MetaModel::GetClasses('bizmodel') as $sClassName)
		{
			if (is_null($iActionCode) || UserRights::IsActionAllowed($sClassName, $iActionCode))
			{
				$sSelected = ($sClassName == $sDefaultValue) ? " SELECTED" : "";
				$sDescription = MetaModel::GetClassDescription($sClassName);
				$sDisplayName = MetaModel::GetName($sClassName);
				$aValidClasses[$sDisplayName] = "<option style=\"width: ".$iWidthPx." px;\" title=\"$sDescription\" value=\"$sClassName\"$sSelected>$sDisplayName</option>";
			}
		}
		ksort($aValidClasses);
		$this->add(implode("\n", $aValidClasses));
		
		$this->add("</select>");
	}

	// By Rom, used by Advanced search
	public function add_select($aChoices, $sName, $sDefaultValue, $iWidthPx)
	{
		$this->add("<select id=\"select_$sName\" name=\"$sName\">");
		foreach($aChoices as $sKey => $sValue)
		{
			$sSelected = ($sKey == $sDefaultValue) ? " SELECTED" : "";
			$this->add("<option style=\"width: ".$iWidthPx." px;\" value=\"".htmlspecialchars($sKey)."\"$sSelected>".htmlentities($sValue, ENT_QUOTES, 'UTF-8')."</option>");
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

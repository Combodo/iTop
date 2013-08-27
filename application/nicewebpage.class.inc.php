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
 * Class NiceWebPage
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/webpage.class.inc.php");
/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class NiceWebPage extends WebPage
{
	var $m_aReadyScripts;
	var $m_sRootUrl;
	
    public function __construct($s_title)
    {
        parent::__construct($s_title);
		$this->m_aReadyScripts = array();
		$this->add_linked_script("../js/jquery-1.10.0.min.js");
		$this->add_linked_script("../js/jquery-migrate-1.2.1.min.js"); // Needed since many other plugins still rely on oldies like $.browser
		$this->add_linked_stylesheet('../css/ui-lightness/jquery-ui-1.10.3.custom.min.css');
		$this->add_linked_script('../js/jquery-ui-1.10.3.custom.min.js');
		$this->add_linked_script("../js/hovertip.js");
		// table sorting
		$this->add_linked_script("../js/jquery.tablesorter.js");
		$this->add_linked_script("../js/jquery.tablesorter.pager.js");
		$this->add_linked_script("../js/jquery.tablehover.js");
		$this->add_linked_script('../js/field_sorter.js');
		$this->add_linked_script('../js/datatable.js');
		$this->add_linked_script("../js/jquery.positionBy.js");
		$this->add_linked_script("../js/jquery.popupmenu.js");
		$this->add_ready_script(
<<< EOF
	//add new widget called TruncatedList to properly display truncated lists when they are sorted
	$.tablesorter.addWidget({ 
		// give the widget a id 
		id: "truncatedList", 
		// format is called when the on init and when a sorting has finished 
		format: function(table)
		{ 
			// Check if there is a "truncated" line
			this.truncatedList = false;  
			if ($("tr td.truncated",table).length > 0)
			{
				this.truncatedList = true;
			}
			if (this.truncatedList)
			{
				$("tr td",table).removeClass('truncated');
				$("tr:last td",table).addClass('truncated');
			}
		} 
	});
	
	$.tablesorter.addWidget({ 
		// give the widget a id 
		id: "myZebra", 
		// format is called when the on init and when a sorting has finished 
		format: function(table)
		{
			// Replace the 'red even' lines by 'red_even' since most browser do not support 2 classes selector in CSS, etc..
			$("tbody tr:even",table).addClass('even');
			$("tbody tr.red:even",table).removeClass('red').removeClass('even').addClass('red_even');
			$("tbody tr.orange:even",table).removeClass('orange').removeClass('even').addClass('orange_even');
			$("tbody tr.green:even",table).removeClass('green').removeClass('even').addClass('green_even');
			// In case we sort again the table, we need to remove the added 'even' classes on odd rows
			$("tbody tr:odd",table).removeClass('even');
			$("tbody tr.red_even:odd",table).removeClass('even').removeClass('red_even').addClass('red');
			$("tbody tr.orange_even:odd",table).removeClass('even').removeClass('orange_even').addClass('orange');
			$("tbody tr.green_even:odd",table).removeClass('even').removeClass('green_even').addClass('green');
		} 
	});
	$("table.listResults").tableHover(); // hover tables
EOF
		);
		$this->add_linked_stylesheet("../css/light-grey.css");

		$this->m_sRootUrl = $this->GetAbsoluteUrlAppRoot();
     	$sAbsURLAppRoot = addslashes($this->m_sRootUrl);
    	$sAbsURLModulesRoot = addslashes($this->GetAbsoluteUrlModulesRoot());
    	$sEnvironment = addslashes(utils::GetCurrentEnvironment());

		$sAppContext = addslashes($this->GetApplicationContext());

		$this->add_script(
<<<EOF
function GetAbsoluteUrlAppRoot()
{
	return '$sAbsURLAppRoot';
}

function GetAbsoluteUrlModulesRoot()
{
	return '$sAbsURLModulesRoot';
}

function GetAbsoluteUrlModulePage(sModule, sPage, aArguments)
{
	// aArguments is optional, it default to an empty hash
	aArguments = typeof aArguments !== 'undefined' ? aArguments : {};

	var sUrl = '$sAbsURLAppRoot'+'pages/exec.php?exec_module='+sModule+'&exec_page='+sPage+'&exec_env='+'$sEnvironment';
	for (var sArgName in aArguments)
	{
		if (aArguments.hasOwnProperty(sArgName))
		{
			sUrl = sUrl + '&'+sArgName+'='+aArguments[sArgname];
		}
	}
	return sUrl;
}

function AddAppContext(sURL)
{
	var sContext = '$sAppContext';
	if (sContext.length > 0)
	{
		if (sURL.indexOf('?') == -1)
		{
			return sURL+'?'+sContext;
		}				
		return sURL+'&'+sContext;
	}
	return sURL;
}
EOF
		);
	}
	
    public function SetRootUrl($sRootUrl)
    {
    	$this->m_sRootUrl = $sRootUrl;
    }
    
	public function small_p($sText)
	{
		$this->add("<p style=\"font-size:smaller\">$sText</p>\n");
	}

	public function GetAbsoluteUrlAppRoot()
	{
		return utils::GetAbsoluteUrlAppRoot();
	}

	public function GetAbsoluteUrlModulesRoot()
	{
		return utils::GetAbsoluteUrlModulesRoot();
	}

	function GetApplicationContext()
	{
		$oAppContext = new ApplicationContext();
		return $oAppContext->GetForLink();
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
		//$this->set_base($this->m_sRootUrl.'pages/');
        if (count($this->m_aReadyScripts)>0)
        {
			$this->add_script("\$(document).ready(function() {\n".implode("\n", $this->m_aReadyScripts)."\n});");
		}
		parent::output();
	}
}

?>

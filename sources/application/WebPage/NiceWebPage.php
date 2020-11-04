<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class NiceWebPage extends WebPage
{
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/nicewebpage/layout';
	var $m_aReadyScripts;
	var $m_sRootUrl;

	public function __construct($s_title, $bPrintable = false)
	{
		parent::__construct($s_title, $bPrintable);
		$this->m_aReadyScripts = array();
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.min.js');
		if (utils::IsDevelopmentEnvironment()) // Needed since many other plugins still rely on oldies like $.browser
		{
			$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery-migrate.dev.js');
		} else {
			$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery-migrate.prod.min.js');
		}
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery-ui.custom.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/utils.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/hovertip.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/table-selectable-lines.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/field_sorter.js');
		//TODO deprecated in 3.0.0
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/datatable.js');
		// table sorting
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.tablesorter.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.tablesorter.pager.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.tablehover.js');
	    //TODO end deprecated in 3.0.0
	    // Datatables added in 3.0.0
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/jquery.dataTables.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/dataTables.bootstrap.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/dataTables.fixedHeader.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/dataTables.responsive.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/dataTables.scroller.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'lib/datatables/js/dataTables.select.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/dataTables.settings.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/dataTables.pipeline.js');
		/*$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/dataTables.bootstrap.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/fixedHeader.bootstrap.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/responsive.bootstrap.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/scroller.bootstrap.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/select.bootstrap.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/datatables/css/select.dataTables.min.css');*/

		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.positionBy.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.popupmenu.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/searchformforeignkeys.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/latinise/latinise.min.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_handler.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_handler_history.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_raw.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_string.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_external_field.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_numeric.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_enum.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_tag_set.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_external_key.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_hierarchical_key.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_date_abstract.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_date.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/search/search_form_criteria_date_time.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/clipboard.min.js');
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/clipboardwidget.js');

	    $this->add_dict_entries('UI:Combo');
	    $this->LoadTheme();

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
			$this->add("<option style=\"width: ".$iWidthPx." px;\" value=\"".htmlspecialchars($sKey)."\"$sSelected>".htmlentities($sValue,
					ENT_QUOTES, self::PAGES_CHARSET)."</option>");
		}
		$this->add("</select>");
	}
	
	public function add_ready_script($sScript)
	{
		if (!empty(trim($sScript))) {
			$this->m_aReadyScripts[] = $sScript;
		}
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


	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	protected function LoadTheme()
	{
		// TODO 3.0.0: Remove light-grey when development of Full Moon is done.
		// TODO 3.0.0: Reuse theming mechanism for Full Moon
		$sCssThemeUrl = ThemeHandler::GetCurrentThemeUrl();
		$this->add_linked_stylesheet($sCssThemeUrl);

		$sCssRelPath = utils::GetCSSFromSASS(
			'css/backoffice/main.scss',
			array(
				APPROOT.'css/backoffice/',
			)
		);
		$this->add_saas($sCssRelPath);
	}
}

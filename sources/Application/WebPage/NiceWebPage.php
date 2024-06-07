<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\WebPage;

use ApplicationContext;
use ExecutionKPI;
use MetaModel;
use ThemeHandler;
use UserRights;
use utils;

/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class NiceWebPage extends WebPage
{
	/** @inheritDoc */
	protected const COMPATIBILITY_MOVED_LINKED_SCRIPTS_REL_PATH = [
		// - DisplayableGraph, impact analysis
		'js/jquery.positionBy.js',
		'js/jquery.popupmenu.js',
		// - SearchForm
		'js/search/search_form_handler.js',
		'js/search/search_form_handler_history.js',
		'js/search/search_form_criteria.js',
		'js/search/search_form_criteria_raw.js',
		'js/search/search_form_criteria_string.js',
		'js/search/search_form_criteria_external_field.js',
		'js/search/search_form_criteria_numeric.js',
		'js/search/search_form_criteria_enum.js',
		'js/search/search_form_criteria_tag_set.js',
		'js/search/search_form_criteria_external_key.js',
		'js/search/search_form_criteria_hierarchical_key.js',
		'js/search/search_form_criteria_date_abstract.js',
		'js/search/search_form_criteria_date.js',
		'js/search/search_form_criteria_date_time.js',
		// - DataTable UIBlock
		'js/field_sorter.js',
		'js/table-selectable-lines.js',
		// - Not used internally or by extensions yet
		'js/clipboard.min.js', // 3.2.0 N°5261 moved to NPM
		'js/clipboardwidget.js',
		// - SearchForm
		'js/searchformforeignkeys.js',
	];
	/** @inheritDoc */
	protected const COMPATIBILITY_DEPRECATED_LINKED_SCRIPTS_REL_PATH = [
		/** @deprecated 3.0.0 Not used in the backoffice since the introduction of the new tooltip lib. */
		'js/hovertip.js',
		/** @deprecated 3.0.0 N°2737 - Migrate table to DataTables plugin to be iso with the end-users portal, will be removed in 3.x */
		'js/datatable.js',
		'js/jquery.tablesorter.js',
		'js/jquery.tablesorter.pager.js',
		'js/jquery.tablehover.js',
	];

	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/nicewebpage/layout';

	var $m_sRootUrl;

	public function __construct($s_title, $bPrintable = false)
	{
		$oKpi = new ExecutionKPI();
		$this->m_sRootUrl = $this->GetAbsoluteUrlAppRoot();
		parent::__construct($s_title, $bPrintable);

		$this->LoadTheme();
		$oKpi->ComputeStats(get_class($this).' creation', 'NiceWebPage');
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeScripts(): void
	{
		parent::InitializeScripts();

		$sAbsURLAppRoot = addslashes($this->m_sRootUrl);
		$sAbsURLModulesRoot = addslashes($this->GetAbsoluteUrlModulesRoot());
		$sEnvironment = addslashes(utils::GetCurrentEnvironment());
		$sAppContext = addslashes($this->GetApplicationContext());

		$this->add_script(
			<<<JS
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
			sUrl = sUrl + '&'+sArgName+'='+aArguments[sArgName];
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
JS
		);
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeLinkedScripts(): void
	{
		parent::InitializeLinkedScripts();

		// Used throughout the app.
		$this->LinkScriptFromAppRoot('node_modules/jquery/dist/jquery.min.js');
		$this->LinkScriptFromAppRoot('js/ajax_hook.js');
		$this->LinkScriptFromAppRoot('js/jquery.blockUI.js');
		if (utils::IsDevelopmentEnvironment()) // Needed since many other plugins still rely on oldies like $.browser
		{
			$this->LinkScriptFromAppRoot('js/jquery-migrate.dev-params.js');
			$this->LinkScriptFromAppRoot('node_modules/jquery-migrate/dist/jquery-migrate.js');
		} else {
			$this->LinkScriptFromAppRoot('node_modules/jquery-migrate/dist/jquery-migrate.min.js');
		}
		$this->LinkScriptFromAppRoot('node_modules/jquery-ui-dist/jquery-ui.min.js');

		// Used throughout the app.
		$this->LinkScriptFromAppRoot('js/utils.js');
		$this->LinkScriptFromAppRoot('js/latinise/latinise.min.js');
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeDictEntries(): void
	{
		parent::InitializeDictEntries();

		$this->add_dict_entries('UI:Combo');
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
			$this->add("<option style=\"width: ".$iWidthPx." px;\" value=\"".htmlspecialchars($sKey)."\"$sSelected>".utils::EscapeHtml($sValue)."</option>");
		}
		$this->add("</select>");
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
		$this->LinkStylesheetFromURI($sCssThemeUrl);
	}

	protected function GetReadyScriptsStartedTrigger(): ?string
	{
		return <<<JS
CombodoJsActivity.AddOngoingScript();
JS;
	}

	protected function GetReadyScriptsFinishedTrigger(): ?string
	{
		return <<<JS
CombodoJsActivity.RemoveOngoingScript();
JS;
	}
}

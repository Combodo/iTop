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
 * Web page used for displaying the login form
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."setup/modulediscovery.class.inc.php");

define('INSTALL_LOG_FILE', APPROOT.'/setup.log');

define ('MODULE_ACTION_OPTIONAL', 1);
define ('MODULE_ACTION_MANDATORY', 2);
define ('MODULE_ACTION_IMPOSSIBLE', 3);
define ('ROOT_MODULE', '_Root_'); // Convention to store IN MEMORY the name/version of the root module i.e. application

date_default_timezone_set('Europe/Paris');
class SetupPage extends NiceWebPage
{
    public function __construct($sTitle)
    {
        parent::__construct($sTitle);
   		$this->add_linked_script("../js/jquery.blockUI.js");
   		$this->add_linked_script("../setup/setup.js");
   		$this->add_linked_script("../setup/setup_environment.js");
        $this->add_style("
body {
	background-color: #eee;
	margin: 0;
	padding: 0;
	font-size: 10pt;
	overflow-y: auto;
}
#header {
	width: 600px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 50px;
	padding: 20px;
	background: #f6f6f1;
	height: 54px;
	border-top: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}
#header img {
	border: 0;
	vertical-align: middle;
	margin-right: 20px;
}
#header h1 {
	vertical-align: middle;
	height: 54px;
	noline-height: 54px;
	margin: 0;
}
#setup {
	width: 600px;
	margin-left: auto;
	margin-right: auto;
	padding: 20px;
	background-color: #fff;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
}
.center {
	text-align: center;
}

h1 {
	color: #1C94C4;
	font-size: 16pt;
}
h2 {
	color: #000;
	font-size: 14pt;
}
h3 {
	color: #1C94C4;
	font-size: 12pt;
	font-weight: bold;
}
.next {
	width: 100%;
	text-align: right;
}
.v-spacer {
	padding-top: 1em;
}
button {
	margin-top: 1em;
	padding-left: 1em;
	padding-right: 1em;
}
p.info {
	padding-left: 50px;
	background: url(../images/info-mid.png) no-repeat left -5px;
	min-height: 48px;
}
p.ok {
	padding-left: 50px;
	background: url(../images/clean-mid.png) no-repeat left -8px;
	min-height: 48px;
}
p.warning {
	padding-left: 50px;
	background: url(../images/messagebox_warning-mid.png) no-repeat left -5px;
	min-height: 48px;
}
p.error {
	padding-left: 50px;
	background: url(../images/stop-mid.png) no-repeat left -5px;
	min-height: 48px;
}
td.label {
	text-align: left;
}
label.read-only {
	color: #666;
	cursor: text;
}
td.input {
	text-align: left;
}
table.formTable {
	border: 0;
	cellpadding: 2px;
	cellspacing: 0;
}
.wizlabel, .wizinput {
	color: #000;
	font-size: 10pt;
}
.wizhelp {
	color: #333;
	font-size: 8pt;
}
#progress { 
    border:1px solid #000000; 
    width: 180px; 
    height: 20px; 
    line-height: 20px; 
    text-align: center;
    margin: 5px;
}
h3.clickable {
	background: url(../images/plus.gif) no-repeat left;
	padding-left:16px;
	cursor: hand;	
}
h3.clickable.open {
	background: url(../images/minus.gif) no-repeat left;
	padding-left:16px;
	cursor: hand;	
}
		");
	}
	public function info($sText)
	{
		$this->add("<p class=\"info\">$sText</p>\n");
		$this->log_info($sText);
	}
	
	public function ok($sText)
	{
		$this->add("<p class=\"ok\">$sText</p>\n");
		$this->log_ok($sText);
	}
	
	public function warning($sText)
	{
		$this->add("<p class=\"warning\">$sText</p>\n");
		$this->log_warning($sText);
	}
	
	public function error($sText)
	{
		$this->add("<p class=\"error\">$sText</p>\n");
		$this->log_error($sText);
	}
	
	public function form($aData)
	{
		$this->add("<table class=\"formTable\">\n");
		foreach($aData as $aRow)
		{
			$this->add("<tr>\n");
			if (isset($aRow['label']) && isset($aRow['input']) && isset($aRow['help']))
			{
				$this->add("<td class=\"wizlabel\">{$aRow['label']}</td>\n");
				$this->add("<td class=\"wizinput\">{$aRow['input']}</td>\n");
				$this->add("<td class=\"wizhelp\">{$aRow['help']}</td>\n");
			}
			else if (isset($aRow['label']) && isset($aRow['help']))
			{
				$this->add("<td colspan=\"2\" class=\"wizlabel\">{$aRow['label']}</td>\n");
				$this->add("<td class=\"wizhelp\">{$aRow['help']}</td>\n");
			}
			else if (isset($aRow['label']) && isset($aRow['input']))
			{
				$this->add("<td class=\"wizlabel\">{$aRow['label']}</td>\n");
				$this->add("<td colspan=\"2\" class=\"wizinput\">{$aRow['input']}</td>\n");
			}
			else if (isset($aRow['label']))
			{
				$this->add("<td colspan=\"3\" class=\"wizlabel\">{$aRow['label']}</td>\n");
			}
			$this->add("</tr>\n");
		}
		$this->add("</table>\n");
	}
	
	public function collapsible($sId, $sTitle, $aItems, $bOpen = true)
	{
		$this->add("<h3 class=\"clickable open\" id=\"{$sId}\">$sTitle</h3>");
		$this->p('<ul id="'.$sId.'_list">');
		foreach($aItems as $sItem)
		{
			$this->p("<li>$sItem</li>\n");
		}		
		$this->p('</ul>');
		$this->add_ready_script("$('#{$sId}').click( function() { $(this).toggleClass('open'); $('#{$sId}_list').toggle();} );\n");
		if (!$bOpen)
		{
			$this->add_ready_script("$('#{$sId}').toggleClass('open'); $('#{$sId}_list').toggle();\n");
		}	
	}
	
	public function output()
	{
		$this->s_content = "<div id=\"header\"><h1><a href=\"http://www.combodo.com/itop\" target=\"_blank\"><img title=\"iTop by Combodo\" src=\"../images/itop-logo.png\"></a>&nbsp;".htmlentities($this->s_title, ENT_QUOTES, 'UTF-8')."</h1>\n</div><div id=\"setup\">{$this->s_content}\n</div>\n";
		return parent::output();
	}
	
	public static function log_error($sText)
	{
		self::log("Error - ".$sText);
	}

	public static function log_warning($sText)
	{
		self::log("Warning - ".$sText);
	}

	public static function log_info($sText)
	{
		self::log("Info - ".$sText);
	}

	public static function log_ok($sText)
	{
		self::log("Ok - ".$sText);
	}

	public static function log($sText)
	{
		$hLogFile = @fopen(INSTALL_LOG_FILE, 'a');
		if ($hLogFile !== false)
		{
			$sDate = date('Y-m-d H:i:s');
			fwrite($hLogFile, "$sDate - $sText\n");
			fclose($hLogFile);
		}
	}
} // End of class


/**
 * Helper function to initialize a configuration from the page arguments
 */
function UpdateConfigSettings(&$oConfig, $aParamValues, $sModulesDir = null)
{
	if (isset($aParamValues['application_path']))
	{
		$oConfig->Set('app_root_url', $aParamValues['application_path']);
	}
	if (isset($aParamValues['mode']) && isset($aParamValues['language']))
	{
		if (($aParamValues['mode'] == 'install') ||  $oConfig->GetDefaultLanguage() == '')
		{
			$oConfig->SetDefaultLanguage($aParamValues['language']);
		}
	}
	if (isset($aParamValues['db_server']))
	{
		$oConfig->SetDBHost($aParamValues['db_server']);
		$oConfig->SetDBUser($aParamValues['db_user']);
		$oConfig->SetDBPwd($aParamValues['db_pwd']);
		$sDBName = $aParamValues['db_name'];
		if ($sDBName == '')
		{
			$sDBName = $aParamValues['new_db_name'];
		}
		$oConfig->SetDBName($sDBName);
		$oConfig->SetDBSubname($aParamValues['db_prefix']);
	}

	if (!is_null($sModulesDir))
	{
		if (isset($aParamValues['selected_modules']))
		{
			$aSelectedModules = explode(',', $aParamValues['selected_modules']);
		}
		else
		{
			$aSelectedModules = null;
		}

		// Initialize the arrays below with default values for the application...
		$oEmptyConfig = new Config('dummy_file', false); // Do NOT load any config file, just set the default values
		$aAddOns = $oEmptyConfig->GetAddOns();
		$aAppModules = $oEmptyConfig->GetAppModules();
		$aDataModels = $oEmptyConfig->GetDataModels();
		$aWebServiceCategories = $oEmptyConfig->GetWebServiceCategories();
		$aDictionaries = $oEmptyConfig->GetDictionaries();
		// Merge the values with the ones provided by the modules
		// Make sure when don't load the same file twice...

		$aModules = ModuleDiscovery::GetAvailableModules(APPROOT, $sModulesDir);
		foreach($aModules as $sModuleId => $aModuleInfo)
		{
			list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
			if (is_null($aSelectedModules) || in_array($sModuleName, $aSelectedModules))
			{
				if (isset($aModuleInfo['datamodel']))
				{
					$aDataModels = array_unique(array_merge($aDataModels, $aModuleInfo['datamodel']));
				}
				if (isset($aModuleInfo['webservice']))
				{
					$aWebServiceCategories = array_unique(array_merge($aWebServiceCategories, $aModuleInfo['webservice']));
				}
				if (isset($aModuleInfo['dictionary']))
				{
					$aDictionaries = array_unique(array_merge($aDictionaries, $aModuleInfo['dictionary']));
				}
				if (isset($aModuleInfo['settings']))
				{
					foreach($aModuleInfo['settings'] as $sProperty => $value)
					{
						list($sName, $sVersion) = ModuleDiscovery::GetModuleName($sModuleId);
						$oConfig->SetModuleSetting($sName, $sProperty, $value);
					}
				}
				if (isset($aModuleInfo['installer']))
				{
					$sModuleInstallerClass = $aModuleInfo['installer'];
					if (!class_exists($sModuleInstallerClass))
					{
						throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not a PHP class - Module: ".$aModuleInfo['label']);
					}
					if (!is_subclass_of($sModuleInstallerClass, 'ModuleInstallerAPI'))
					{
						throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not derived from 'ModuleInstallerAPI' - Module: ".$aModuleInfo['label']);
					}
					$aCallSpec = array($sModuleInstallerClass, 'BeforeWritingConfig');
					$oConfig = call_user_func_array($aCallSpec, array($oConfig));
				}
			}
		}
		$oConfig->SetAddOns($aAddOns);
		$oConfig->SetAppModules($aAppModules);
		$oConfig->SetDataModels($aDataModels);
		$oConfig->SetWebServiceCategories($aWebServiceCategories);
		$oConfig->SetDictionaries($aDictionaries);
	}
}



/**
 * Helper function to initialize the ORM and load the data model
 * from the given file
 * @param $oConfig object The configuration (volatile, not necessarily already on disk)
 * @param $bModelOnly boolean Whether or not to allow loading a data model with no corresponding DB 
 * @return none
 */    
function InitDataModel($oConfig, $bModelOnly = true, $bUseCache = false)
{
	require_once(APPROOT.'/core/log.class.inc.php');
	require_once(APPROOT.'/core/kpi.class.inc.php');
	require_once(APPROOT.'/core/coreexception.class.inc.php');
	require_once(APPROOT.'/core/dict.class.inc.php');
	require_once(APPROOT.'/core/attributedef.class.inc.php');
	require_once(APPROOT.'/core/filterdef.class.inc.php');
	require_once(APPROOT.'/core/stimulus.class.inc.php');
	require_once(APPROOT.'/core/MyHelpers.class.inc.php');
	require_once(APPROOT.'/core/expression.class.inc.php');
	require_once(APPROOT.'/core/cmdbsource.class.inc.php');
	require_once(APPROOT.'/core/sqlquery.class.inc.php');
	require_once(APPROOT.'/core/dbobject.class.php');
	require_once(APPROOT.'/core/dbobjectsearch.class.php');
	require_once(APPROOT.'/core/dbobjectset.class.php');
	require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
	require_once(APPROOT.'/core/userrights.class.inc.php');
	require_once(APPROOT.'/setup/moduleinstallation.class.inc.php');

	$sConfigFile = $oConfig->GetLoadedFile();
	if (strlen($sConfigFile) > 0)
	{
		SetupPage::log_info("MetaModel::Startup from $sConfigFile (ModelOnly = $bModelOnly)");
	}
	else
	{
		SetupPage::log_info("MetaModel::Startup (ModelOnly = $bModelOnly)");
	}

	if (!$bUseCache)
	{
		// Reset the cache for the first use !
		MetaModel::ResetCache($oConfig);
	}

	MetaModel::Startup($oConfig, $bModelOnly, $bUseCache);
}

/**
 * Analyzes the current installation and the possibilities
 * 
 * @param $oConfig Config Defines the target environment (DB)
 * @return hash Array with the following format:
 * array =>
 *     'iTop' => array(
 *         'version_db' => ... (could be empty in case of a fresh install)
 *         'version_code => ...
 *     )
 *     <module_name> => array(
 *         'version_db' => ...  
 *         'version_code' => ...  
 *         'install' => array(
 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
 *             'message' => ...  
 *         )   
 *         'uninstall' => array(
 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
 *             'message' => ...  
 *         )   
 *         'label' => ...  
 *         'dependencies' => array(<module1>, <module2>, ...)  
 *         'visible' => true | false
 *     )
 * )
 */     
function AnalyzeInstallation($oConfig, $sModulesRelativePath)
{
	$aRes = array(
		ROOT_MODULE => array(
			'version_db' => '',
			'name_db' => '',
			'version_code' => ITOP_VERSION.'.'.ITOP_REVISION,
			'name_code' => ITOP_APPLICATION,
		)
	);

	$aModules = ModuleDiscovery::GetAvailableModules(APPROOT, $sModulesRelativePath);
	foreach($aModules as $sModuleId => $aModuleInfo)
	{
		list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);

		$sModuleAppVersion = $aModuleInfo['itop_version'];
		$aModuleInfo['version_db'] = '';
		$aModuleInfo['version_code'] = $sModuleVersion;

		if (!in_array($sModuleAppVersion, array('1.0.0', '1.0.1', '1.0.2')))
		{
			// This module is NOT compatible with the current version
      		$aModuleInfo['install'] = array(
      			'flag' => MODULE_ACTION_IMPOSSIBLE,
      			'message' => 'the module is not compatible with the current version of the application'
      		);
		}
      elseif ($aModuleInfo['mandatory'])
      {
			$aModuleInfo['install'] = array(
      			'flag' => MODULE_ACTION_MANDATORY,
      			'message' => 'the module is part of the application'
      		);
		}
		else
		{
			$aModuleInfo['install'] = array(
      			'flag' => MODULE_ACTION_OPTIONAL,
      			'message' => ''
      		);
		}
		$aRes[$sModuleName] = $aModuleInfo;
	}

  	try
  	{
		CMDBSource::Init($oConfig->GetDBHost(), $oConfig->GetDBUser(), $oConfig->GetDBPwd(), $oConfig->GetDBName());
		$aSelectInstall = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->GetDBSubname()."priv_module_install");
	}
	catch (MySQLException $e)
	{
		// No database or eroneous information
		$aSelectInstall = array();
	}

	// Build the list of installed module (get the latest installation)
	//
	$aInstallByModule = array(); // array of <module> => array ('installed' => timestamp, 'version' => <version>)
	foreach ($aSelectInstall as $aInstall)
	{
		//$aInstall['comment']; // unsused
		$iInstalled = strtotime($aInstall['installed']);
		$sModuleName = $aInstall['name'];
		$sModuleVersion = $aInstall['version'];

		if ($aInstall['parent_id'] == 0)
		{
			$sModuleName = ROOT_MODULE;
		}

      	if (array_key_exists($sModuleName, $aInstallByModule))
      	{
	      	if ($iInstalled < $aInstallByModule[$sModuleName]['installed'])
	      	{
	      		continue;
	      	}
		}

		if ($aInstall['parent_id'] == 0)
		{
			$aRes[$sModuleName]['version_db'] = $sModuleVersion;
			$aRes[$sModuleName]['name_db'] = $aInstall['name'];
		}

		$aInstallByModule[$sModuleName]['installed'] = $iInstalled;
		$aInstallByModule[$sModuleName]['version'] = $sModuleVersion;
   }

	// Adjust the list of proposed modules
	//
   foreach ($aInstallByModule as $sModuleName => $aModuleDB)
   {
   		if ($sModuleName == ROOT_MODULE) continue; // Skip the main module
   		
		if (!array_key_exists($sModuleName, $aRes))
		{
			// A module was installed, it is not proposed in the new build... skip 
			continue;
		}
		$aRes[$sModuleName]['version_db'] = $aModuleDB['version'];

		if ($aRes[$sModuleName]['install']['flag'] == MODULE_ACTION_MANDATORY)
		{
			$aRes[$sModuleName]['uninstall'] = array(
				'flag' => MODULE_ACTION_IMPOSSIBLE,
				'message' => 'the module is part of the application'
			);
		}
		else
		{
			$aRes[$sModuleName]['uninstall'] = array(
				'flag' => MODULE_ACTION_OPTIONAL,
				'message' => ''
			);
		}
	}

	return $aRes;
}

/**
 * Helper function to create the database structure
 * @return boolean true on success, false otherwise
 */
function CreateDatabaseStructure(Config $oConfig, $sMode)
{
	if (strlen($oConfig->GetDBSubname()) > 0)
	{
		SetupPage::log_info("Creating the structure in '".$oConfig->GetDBName()."' (table names prefixed by '".$oConfig->GetDBSubname()."').");
	}
	else
	{
		SetupPage::log_info("Creating the structure in '".$oConfig->GetDBSubname()."'.");
	}

	//MetaModel::CheckDefinitions();
	if ($sMode == 'install')
	{
		if (!MetaModel::DBExists(/* bMustBeComplete */ false))
		{
			MetaModel::DBCreate();
			SetupPage::log_ok("Database structure successfully created.");
		}
		else
		{
			if (strlen($oConfig->GetDBSubname()) > 0)
			{
				throw new Exception("Error: found iTop tables into the database '".$oConfig->GetDBName()."' (prefix: '".$oConfig->GetDBSubname()."'). Please, try selecting another database instance or specify another prefix to prevent conflicting table names.");
			}
			else
			{
				throw new Exception("Error: found iTop tables into the database '".$oConfig->GetDBName()."'. Please, try selecting another database instance or specify a prefix to prevent conflicting table names.");
			}
		}
	}
	else
	{
		if (MetaModel::DBExists(/* bMustBeComplete */ false))
		{
			MetaModel::DBCreate();
			SetupPage::log_ok("Database structure successfully updated.");

			// Check (and update only if it seems needed) the hierarchical keys
			ob_start();
			MetaModel::CheckHKeys(false /* bDiagnosticsOnly */, true /* bVerbose*/, true /* bForceUpdate */); // Since in 1.2-beta the detection was buggy, let's force the rebuilding of HKeys
			$sFeedback = ob_get_clean();
			SetupPage::log_ok("Hierchical keys rebuilt: $sFeedback");

			// Check (and fix) data sync configuration
			ob_start();
			MetaModel::CheckDataSources(false /*$bDiagnostics*/, true/*$bVerbose*/);
			$sFeedback = ob_get_clean();
			SetupPage::log_ok("Data sources checked: $sFeedback");
		}
		else
		{
			if (strlen($oConfig->GetDBSubname()) > 0)
			{
				throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->GetDBName()."' (prefix: '".$oConfig->GetDBSubname()."'). Please, try selecting another database instance.");
			}
			else
			{
				throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->GetDBName()."'. Please, try selecting another database instance.");
			}
		}
	}
	return true;
}

function RecordInstallation(Config $oConfig, $aSelectedModules, $sModulesRelativePath)
{
	// Record main installation
	$oInstallRec = new ModuleInstallation();
	$oInstallRec->Set('name', ITOP_APPLICATION);
	$oInstallRec->Set('version', ITOP_VERSION.'.'.ITOP_REVISION);
	$oInstallRec->Set('comment', "Done by the setup program\nBuilt on ".ITOP_BUILD_DATE);
	$oInstallRec->Set('parent_id', 0); // root module
	$iMainItopRecord = $oInstallRec->DBInsertNoReload();

	// Record installed modules
	//
	$aAvailableModules = AnalyzeInstallation($oConfig, $sModulesRelativePath);
	foreach($aSelectedModules as $sModuleId)
	{
		$aModuleData = $aAvailableModules[$sModuleId];
		$sName = $sModuleId;
		$sVersion = $aModuleData['version_code'];
		$aComments = array();
		$aComments[] = 'Done by the setup program';
		if ($aModuleData['mandatory'])
		{
			$aComments[] = 'Mandatory';
		}
		else
		{
			$aComments[] = 'Optional';
		}
		if ($aModuleData['visible'])
		{
			$aComments[] = 'Visible (during the setup)';
		}
		else
		{
			$aComments[] = 'Hidden (selected automatically)';
		}
		foreach ($aModuleData['dependencies'] as $sDependOn)
		{
			$aComments[] = "Depends on module: $sDependOn";
		}
		$sComment = implode("\n", $aComments);

		$oInstallRec = new ModuleInstallation();
		$oInstallRec->Set('name', $sName);
		$oInstallRec->Set('version', $sVersion);
		$oInstallRec->Set('comment', $sComment);
		$oInstallRec->Set('parent_id', $iMainItopRecord);
		$oInstallRec->DBInsertNoReload();
	}
	// Database is created, installation has been tracked into it
	return true;	
}

?>

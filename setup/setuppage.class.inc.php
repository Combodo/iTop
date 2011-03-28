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
define('INSTALL_LOG_FILE', APPROOT.'/setup.log');
date_default_timezone_set('Europe/Paris');
class SetupWebPage extends NiceWebPage
{
    public function __construct($sTitle)
    {
        parent::__construct($sTitle);
   		$this->add_linked_script("../js/jquery.blockUI.js");
   		$this->add_linked_script("./setup.js");
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
	
	public function output()
	{
		$this->s_content = "<div id=\"header\"><h1><a href=\"http://www.combodo.com/itop\" target=\"_blank\"><img title=\"iTop by Combodo\" src=\"../images/itop-logo.png\"></a>&nbsp;{$this->s_title}</h1>\n</div><div id=\"setup\">{$this->s_content}\n</div>\n";
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

	static $m_aModuleArgs = array(
		'label' => 'One line description shown during the interactive setup',
		'dependencies' => 'array of module ids',
		'mandatory' => 'boolean',
		'visible' => 'boolean',
		'datamodel' =>  'array of data model files',
		'dictionary' => 'array of dictionary files',
		'data.struct' => 'array of structural data files',
		'data.sample' => 'array of sample data files',
		'doc.manual_setup' => 'url',
		'doc.more_information' => 'url',
	);
	
	static $m_aModules = array();
	
	// All the entries below are list of file paths relative to the module directory
	static $m_aFilesList = array('datamodel', 'webservice', 'dictionary', 'data.struct', 'data.sample');

	static $m_sModulePath = null;
	public function SetModulePath($sModulePath)
	{
		self::$m_sModulePath = $sModulePath;
	}

	public static function AddModule($sFilePath, $sId, $aArgs)
	{
		if (!array_key_exists('itop_version', $aArgs))
		{
			// Assume 1.0.2
			$aArgs['itop_version'] = '1.0.2';
		}
		foreach (self::$m_aModuleArgs as $sArgName => $sArgDesc)
		{
			if (!array_key_exists($sArgName, $aArgs))
			{
				throw new Exception("Module '$sId': missing argument '$sArgName'");
		   }
		}

		self::$m_aModules[$sId] = $aArgs;

		foreach(self::$m_aFilesList as $sAttribute)
		{
			if (isset(self::$m_aModules[$sId][$sAttribute]))
			{
				// All the items below are list of files, that are relative to the current file
				// being loaded, let's update their path to store path relative to the application directory
				foreach(self::$m_aModules[$sId][$sAttribute] as $idx => $sRelativePath)
				{
				self::$m_aModules[$sId][$sAttribute][$idx] = self::$m_sModulePath.'/'.$sRelativePath;
				}
			}
		}
	}
	public function GetModules()
	{
		// Order the modules to take into account their inter-dependencies
		$aDependencies = array();
		foreach(self::$m_aModules as $sId => $aModule)
		{
			$aDependencies[$sId] = $aModule['dependencies'];
		}
		$aOrderedModules = array();
		$iLoopCount = 1;
		while(($iLoopCount < count(self::$m_aModules)) && (count($aDependencies) > 0) )
		{
			foreach($aDependencies as $sId => $aRemainingDeps)
			{
				$bDependenciesSolved = true;
				foreach($aRemainingDeps as $sDepId)
				{
					if (!in_array($sDepId, $aOrderedModules))
					{
						$bDependenciesSolved = false;
					}
				}
				if ($bDependenciesSolved)
				{
					$aOrderedModules[] = $sId;
					unset($aDependencies[$sId]);
				}
			}
			$iLoopCount++;
		}
		if (count($aDependencies) >0)
		{
			$sHtml = "<ul><b>Warning: the following modules have unmet dependencies, and have been ignored:</b>\n";			
			foreach($aDependencies as $sId => $aDeps)
			{
				$aModule = self::$m_aModules[$sId];
				$sHtml.= "<li>{$aModule['label']} (id: $sId), depends on: ".implode(', ', $aDeps)."</li>";
			}
			$sHtml .= "</ul>\n";
			$this->warning($sHtml);
		}
		// Return the ordered list, so that the dependencies are met...
		$aResult = array();
		foreach($aOrderedModules as $sId)
		{
			$aResult[$sId] = self::$m_aModules[$sId];
		}
		return $aResult;
	}

} // End of class
?>

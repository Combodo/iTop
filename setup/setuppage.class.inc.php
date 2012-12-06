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
 * Web page used for the setup
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
require_once(APPROOT."setup/modulediscovery.class.inc.php");
require_once(APPROOT."setup/runtimeenv.class.inc.php");

define('INSTALL_LOG_FILE', APPROOT.'/log/setup.log');

date_default_timezone_set('Europe/Paris');
class SetupPage extends NiceWebPage
{
    public function __construct($sTitle)
    {
        parent::__construct($sTitle);
   		$this->add_linked_script("../js/jquery.blockUI.js");
   		$this->add_linked_script("../setup/setup.js");
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

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */	 	
	public function GetAbsoluteUrlAppRoot()
	{
		return '../';
	}

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */	 	
	public function GetAbsoluteUrlModulesRoot()
	{
		return $this->GetAbsoluteUrlAppRoot().utils::GetCurrentEnvironment();
	}

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */	 	
	function GetApplicationContext()
	{
		return '';
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

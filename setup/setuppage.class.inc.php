<?php
require_once("../application/nicewebpage.class.inc.php");
define('INSTALL_LOG_FILE', '../setup.log');

/**
 * Web page used for displaying the login form
 */
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
#setup {
	width: 600px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 50px;
	padding: 20px;
	background-color: #fff;
	border: 1px solid #000;
}
.center {
	text-align: center;
}

h1 {
	color: #83b217;
	font-size: 16pt;
}
h2 {
	color: #000;
	font-size: 14pt;
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
	height: 48px;
}
p.ok {
	padding-left: 50px;
	background: url(../images/clean-mid.png) no-repeat left -8px;
	height: 48px;
}
p.warning {
	padding-left: 50px;
	background: url(../images/messagebox_warning-mid.png) no-repeat left -5px;
	height: 48px;
}
p.error {
	padding-left: 50px;
	background: url(../images/stop-mid.png) no-repeat left -5px;
	height: 48px;
}
td.label {
	text-align: left;
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
		$this->log("Info - ".$sText);
	}
	
	public function ok($sText)
	{
		$this->add("<p class=\"ok\">$sText</p>\n");
		$this->log("Ok - ".$sText);
	}
	
	public function warning($sText)
	{
		$this->add("<p class=\"warning\">$sText</p>\n");
		$this->log("Warning - ".$sText);
	}
	
	public function error($sText)
	{
		$this->add("<p class=\"error\">$sText</p>\n");
		$this->log("Error - ".$sText);
	}
	
	public function form($aData)
	{
		$this->add("<table class=\"formTable\">\n");
		foreach($aData as $aRow)
		{
			$this->add("<tr>\n");
			if (isset($aRow['label']) && isset($aRow['label']) && isset($aRow['help']))
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
		$this->s_content = "<div id=\"setup\">{$this->s_content}\n</div>\n";
		return parent::output();
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
?>

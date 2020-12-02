<?php
require_once(APPROOT."/application/user.preferences.class.inc.php");
class HubConnectorPage extends NiceWebPage
{
    public function __construct($sTitle)
    {
	    parent::__construct($sTitle);

	    $this->no_cache();
	    $this->add_xframe_options();

	    $sImagesDir = utils::GetAbsoluteUrlAppRoot().'images';
	    $sModuleImagesDir = utils::GetAbsoluteUrlModulesRoot().'itop-hub-connector/images';

	    $sUserPrefs = appUserPreferences::GetAsJSON();
	    $this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/utils.js');
	    $this->add_script(
		    <<<EOF
		var oUserPreferences = $sUserPrefs;
EOF
        );
        $this->add_style(
<<<EOF
body {
    background-color: #FFFFFF;
    color: rgba(0, 0, 0, 0.87);
    font-family: 'Open Sans', 'Helvetica Neue', Arial, Helvetica, sans-serif;
    font-size: 14px;
    line-height: 1.4285em;
    overflow: auto;
}
.centered_box {
    background: none repeat scroll 0 0 #333333;
    border-color: #000000;
    border-style: solid ;
    border-width: 1px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 100px;
    padding: 20px;
    width: 600px;
}
h2 {
    font-size: 1.71428571rem;
}
#wiz_buttons > button, #wiz_buttons > form{
	margin: 20px;
}
#launcher {
	display: inline-block;
}
.header_message {
	color: black;
	margin-top: 10px;
}
.checkup_info {
	background: url("$sImagesDir/info-mini.png") no-repeat left;
	padding-left: 2em;
}
.checkup_error {
	background: url("$sImagesDir/validation_error.png") no-repeat left;
	padding-left: 2em;
}
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
	background: url("$sModuleImagesDir/ui-bg_flat_35_555555_40x100.png") repeat-x scroll 50% 50% #555555;
	border: 1px solid #555555;
	color: #EEEEEE;
	font-weight: bold;
}
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover,
 .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-focus .ui-state-focus {
	background: url("$sModuleImagesDir/ui-bg_flat_33_F58400_40x100.png") repeat-x scroll 50% 50% #F58400;
	border: 1px solid #F58400;
	color: #EEEEEE;
	font-weight: bold;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
	border-bottom-right-radius: 0;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
	border-bottom-left-radius: 0;
}
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
	border-top-right-radius: 0;
}
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
	border-top-left-radius: 0;
}
.ui-widget {
	font-family: Verdana,Arial,sans-serif;
	font-size: 1.1em;
}
.ui-button, .ui-button:link, .ui-button:visited, .ui-button:hover, .ui-button:active {
	text-decoration: none;
}
.ui-button {
	cursor: pointer;
	display: inline-block;
	line-height: normal;
	margin-right: 0.1em;
	overflow: visible;
	padding: 0;
	position: relative;
	text-align: center;
	vertical-align: middle;
}
#db_backup_path {
	width: 99%;
}
div#integrity_issues {
	background-color: #FFFFFF;
}
div#integrity_issues .query {
	font-size: smaller;
}
            
EOF
            );
    }
}

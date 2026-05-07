<?php

use Combodo\iTop\Application\WebPage\NiceWebPage;

require_once(dirname(__DIR__, 6).'/approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'setup/setuputils.class.inc.php');

$aParams = [
	"exec_module" => "combodo-data-feature-removal",
	"exec_page" => "index.php",
	'exec_env' => 'production',
];

new ContextTag(ContextTag::TAG_SETUP);
$sToken = SetupUtils::CreateSetupToken();

$aPostParams = [
	"auth_user" => 'admin',
	"auth_pwd" => 'admin',
	'login_mode' => 'form',
	'operation' => 'AnalysisResult',
	'aRemovedExtensions[itop-container-mgmt]' => 'Containerization',
	'setup_token' => $sToken,
];

$sHiddenPostedInput = "";
foreach ($aPostParams as $sKey => $sVal) {
	$sHiddenPostedInput .= <<<INPUT
	<input type="hidden" name="$sKey" value="$sVal">
INPUT;
}

$sRedirectURL = utils::GetAbsoluteUrlModulePage('combodo-data-feature-removal', 'index.php');

$sDiv = <<<DIV
<div id="_test" style="display: none;">
	<form id="_form" action="$sRedirectURL" method="post">
	$sHiddenPostedInput
	</form>
</div>
DIV;

$sReadyJs = <<<JS
$("#_form").trigger("submit");
JS;

$oP = new NiceWebPage("TEST");
$oP->add($sDiv);
$oP->add_ready_script($sReadyJs);
$oP->output();

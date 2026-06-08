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

function GetLastestInstallFile(): ?string
{
	$aFiles = glob(APPROOT.'/log/install-*.xml');
	rsort($aFiles);
	$iLatestCtime = 0;
	$sLastFilePath = null;
	foreach ($aFiles as $sFilePath) {
		if (is_file($sFilePath)) {
			$iCurrentCtime = filemtime($sFilePath);
			if ($iCurrentCtime > $iLatestCtime) {
				$iLatestCtime = $iCurrentCtime;
				$sLastFilePath = $sFilePath;
			}
		}
	}

	return $sLastFilePath;
}

$aRemovedExtensions = [
	'itop-container-mgmt' => 'Containerization',
];

$sPath = GetLastestInstallFile();
if (is_null($sPath)) {
	throw new Exception("$sPath no installation XM. Launch a setup....");
}
$aParams = new XMLParameters($sPath);
$aSelectedModules = array_filter($aParams->Get('selected_modules', []), static function ($element) {
	global $aRemovedExtensions;
	return ! array_key_exists($element, $aRemovedExtensions);
});

$aSelectedExtensions = array_filter($aParams->Get('selected_extensions', []), static function ($element) {
	global $aRemovedExtensions;
	return ! array_key_exists($element, $aRemovedExtensions);
});

$aPostParams = [
	"auth_user" => 'admin',
	"auth_pwd" => 'admin',
	'login_mode' => 'form',
	'operation' => 'AnalysisResult',
	'authent' => $sToken,
	'selected_modules' => utils::HtmlEntities(json_encode($aSelectedModules)),
	'selected_extensions' => utils::HtmlEntities(json_encode($aSelectedExtensions)),
	'removed_extensions' => utils::HtmlEntities(json_encode($aRemovedExtensions)),
	'force-uninstall' => "on",
	'use_symbolic_links' => "",
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

$oP = new NiceWebPage("Simulate Audit From Setup");
$oP->add($sDiv);
$oP->add_ready_script($sReadyJs);
$oP->output();

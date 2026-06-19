<?php

use Combodo\iTop\Application\WebPage\NiceWebPage;

require_once(dirname(__DIR__, 6).'/approot.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'setup/setuputils.class.inc.php');

new ContextTag(ContextTag::TAG_SETUP);
$sToken = SetupUtils::CreateSetupToken();

function GetLastestInstallFile(): ?string
{
	$aFiles = glob(APPROOT.'/log/install-*.xml');
	if ($aFiles === false) {
		return null;
	}
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

$sPath = GetLastestInstallFile();
if (is_null($sPath)) {
	throw new Exception("$sPath no installation XML. Launch a setup....");
}
$aParams = new XMLParameters($sPath);
$aSelectedModules = $aParams->Get('selected_modules', []);
$aSelectedExtensions = $aParams->Get('selected_extensions', []);

$sAddedExtensions = utils::ReadParam('added_extensions', '', false, 'raw');
$aAddedExtensions = [];
if (mb_strlen($sAddedExtensions) > 0) {
	$aAddedExtensions = explode(',', $sAddedExtensions);
}
$oExtensionMap = iTopExtensionsMap::GetExtensionsMap();
foreach ($aAddedExtensions as $iIndex => $sExtensionCode) {
	if (mb_strlen($sExtensionCode) <= 0) {
		unset($aAddedExtensions[$iIndex]);
		continue;
	}
	$oExtension = $oExtensionMap->GetFromExtensionCode($sExtensionCode);
	$aSelectedExtensions[] = $oExtension->sCode;
	foreach ($oExtension->aModules as $sModuleCode) {
		if (!in_array($sModuleCode, $aSelectedModules)) {
			$aSelectedModules[] = $sModuleCode;
		}
	}
}

$sRemovedExtensions = utils::ReadParam('removed_modules', '', false, 'raw');
$aRemovedExtensionsAndModules = [];
if (mb_strlen($sRemovedExtensions) > 0) {
	$aRemovedExtensionsAndModules = explode(',', $sRemovedExtensions);
}

$aSelectedModules = array_filter($aSelectedModules, fn ($element) => !in_array($element, $aRemovedExtensionsAndModules));
$aSelectedExtensions = array_filter($aSelectedExtensions, fn ($element) => !in_array($element, $aRemovedExtensionsAndModules));
$aRemovedExtensionsAndModules = array_filter($aRemovedExtensionsAndModules, fn ($element) => !is_null($oExtensionMap->GetFromExtensionCode($element)));

$aRemovedExtensions = array_combine($aRemovedExtensionsAndModules, $aRemovedExtensionsAndModules);
$aAddedExtensions = array_combine($aAddedExtensions, $aAddedExtensions);

$aPostParams = [
	'auth_user' => 'admin',
	'auth_pwd' => 'admin',
	'login_mode' => 'form',
	'operation' => 'AnalysisResult',
	'authent' => $sToken,
	'selected_modules' => utils::HtmlEntities(json_encode($aSelectedModules)),
	'selected_extensions' => utils::HtmlEntities(json_encode($aSelectedExtensions)),
	'removed_extensions' => utils::HtmlEntities(json_encode($aRemovedExtensions)),
	'added_extensions' => utils::HtmlEntities(json_encode($aAddedExtensions)),
	'force-uninstall' => 'on',
	'use_symbolic_links' => '',
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

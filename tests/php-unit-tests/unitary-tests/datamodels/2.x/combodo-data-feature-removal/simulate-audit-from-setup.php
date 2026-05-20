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

$aSelectedModules = [
	'authent-cas',
	'authent-external',
	'authent-ldap',
	'authent-local',
	'combodo-backoffice-darkmoon-theme',
	'combodo-backoffice-fullmoon-high-contrast-theme',
	'combodo-backoffice-fullmoon-protanopia-deuteranopia-theme',
	'combodo-backoffice-fullmoon-tritanopia-theme',
	'combodo-data-feature-removal',
	'itop-backup',
	'itop-config',
	'itop-files-information',
	'itop-portal-base',
	'itop-profiles-itil',
	'itop-sla-computation',
	'itop-structure',
	'itop-welcome-itil',
	'itop-config-mgmt',
	'itop-attachments',
	'itop-tickets',
	'combodo-db-tools',
	'itop-core-update',
	'itop-hub-connector',
	'itop-oauth-client',
	'itop-themes-compat',
	'combodo-my-account',
	'combodo-my-account-user-info',
	'combodo-oauth2-client',
	'itop-attribute-class-set',
	'itop-attribute-encrypted-password',
	'itop-ui-copypaste',
	'itop-datacenter-mgmt',
	'itop-endusers-devices',
	'itop-storage-mgmt',
	'itop-virtualization-mgmt',
	'itop-bridge-cmdb-ticket',
	'itop-bridge-virtualization-storage',
	'itop-service-mgmt',
	'itop-bridge-cmdb-services',
	'itop-bridge-datacenter-mgmt-services',
	'itop-bridge-endusers-devices-services',
	'itop-bridge-storage-mgmt-services',
	'itop-bridge-virtualization-mgmt-services',
	'itop-request-mgmt',
	'itop-portal',
	'itop-change-mgmt',
	'itop-faq-light',
	'itop-knownerror-mgmt',
	'itop-problem-mgmt',
	'itop-system-information',
	'itop-log-mgmt',
];

$aSelectedExtensions = [
	'itop-config-mgmt-core',
	'itop-config-mgmt-datacenter',
	'itop-config-mgmt-end-user',
	'itop-config-mgmt-storage',
	'itop-config-mgmt-virtualization',
	'itop-service-mgmt-enterprise',
	'itop-ticket-mgmt-simple-ticket',
	'itop-ticket-mgmt-simple-ticket-enhanced-portal',
	'itop-change-mgmt-simple',
	'itop-kown-error-mgmt',
	'itop-problem-mgmt',
	'itop-system-information',
	'itop-log-mgmt',
];

$aRemovedExtensions = ['itop-container-mgmt' => 'Containerization'];

$aPostParams = [
	"auth_user" => 'admin',
	"auth_pwd" => 'admin',
	'login_mode' => 'form',
	'operation' => 'AnalysisResult',
	'setup_token' => $sToken,
	'selected_modules' => utils::HtmlEntities(json_encode($aSelectedModules)),
	'selected_extensions' => utils::HtmlEntities(json_encode($aSelectedExtensions)),
	'removed_extensions' => utils::HtmlEntities(json_encode($aRemovedExtensions)),
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

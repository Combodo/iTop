<?php
require_once('../application/application.class.inc.php');
require_once('../application/nicewebpage.class.inc.php');

require_once('../application/startup.inc.php');

function ReadParam($sName, $defaultValue = "")
{
	return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
}

$oPage = new nice_web_page("Asynchronous versus asynchronous DisplayBlocks");
$oPage->no_cache();
$oPage->add("<h1>Asynchronous versus asynchronous DisplayBlocks</h1>\n");

$oContext = new UserContext();
$operation = ReadParam('operation', '');
$sClassName = ReadParam('class', 'bizContact');
$sOrganizationCode = ReadParam('org', 'ITOP');

$oPage->p("[Synchronous] Count of all $sClassName objects for organization '$sOrganizationCode'");
$oFilter = $oContext->NewFilter($sClassName);
$oFilter ->AddCondition('organization', $sOrganizationCode, '=');
$oBlock = new DisplayBlock($oFilter, 'count', false);
$oBlock->Display($oPage, "block1");

$oPage->p("[Asynchronous] All $sClassName objects for organization '$sOrganizationCode'");
$oFilter = $oContext->NewFilter($sClassName);
$oFilter ->AddCondition('organization', $sOrganizationCode, '=');
$oBlock = new DisplayBlock($oFilter, 'list', true);
$oBlock->Display($oPage, "block2");

$oPage->p("[Asynchronous] Details of all $sClassName objects for organization '$sOrganizationCode'");
$oFilter = $oContext->NewFilter($sClassName);
$oFilter ->AddCondition('organization', $sOrganizationCode, '=');
$oBlock = new DisplayBlock($oFilter, 'details', true);
$oBlock->Display($oPage, "block3");

$oPage->output();
?>

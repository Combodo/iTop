<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

require_once ('../approot.inc.php');
require_once (APPROOT.'/application/application.inc.php');
require_once (APPROOT.'/application/itopwebpage.class.inc.php');
require_once (APPROOT.'setup/extensionsmap.class.inc.php');

require_once (APPROOT.'/application/startup.inc.php');

require_once (APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

$oAppContext = new ApplicationContext();

$oPage = new iTopWebPage(Dict::S('iTopHub:InstalledExtensions'));
$oPage->SetBreadCrumbEntry('ui-hub-myextensions', Dict::S('Menu:iTopHub:MyExtensions'), Dict::S('Menu:iTopHub:MyExtensions+'), '', utils::GetAbsoluteUrlAppRoot().'images/wrench.png');

function DisplayExtensionInfo(Webpage $oPage, iTopExtension $oExtension)
{
	$oPage->add('<li>');
	if ($oExtension->sInstalledVersion == '')
	{
		$oPage->add('<b>'.$oExtension->sLabel.'</b> '.Dict::Format('UI:About:Extension_Version', $oExtension->sVersion).' <span class="extension-source">'.Dict::S('iTopHub:ExtensionNotInstalled').'</span>');
	}
	else
	{
		$oPage->add('<b>'.$oExtension->sLabel.'</b> '.Dict::Format('UI:About:Extension_Version', $oExtension->sInstalledVersion));
	}
	$oPage->add('<p style="margin-top: 0.25em;">'.$oExtension->sDescription.'</p>');
	$oPage->add('</li>');
}

// Main program
try
{
	$oExtensionsMap = new iTopExtensionsMap();
	$oExtensionsMap->LoadChoicesFromDatabase(MetaModel::GetConfig());
	
	$oPage->add('<h1>'.Dict::S('iTopHub:InstalledExtensions').'</h1>');
	
	$oPage->add('<fieldset>');
	$oPage->add('<legend>'.Dict::S('iTopHub:ExtensionCategory:Remote').'</legend>');
	$oPage->p(Dict::S('iTopHub:ExtensionCategory:Remote+'));
	$oPage->add('<ul style="margin: 0;">');
	$iCount = 0;
	foreach ($oExtensionsMap->GetAllExtensions() as $oExtension)
	{
		if ($oExtension->sSource == iTopExtension::SOURCE_REMOTE)
		{
			$iCount++ ;
			DisplayExtensionInfo($oPage, $oExtension);
		}
	}
	$oPage->add('</ul>');
	if ($iCount == 0)
	{
		$oPage->p(Dict::S('iTopHub:NoExtensionInThisCategory'));
	}
	$oPage->add('</fieldset>');
	$sUrl = utils::GetAbsoluteUrlModulePage('itop-hub-connector', 'launch.php', array('target' => 'browse_extensions'));
	$oPage->add('<p style="text-align:center;"><button onclick="window.location.href=\''.$sUrl.'\'">'.Dict::S('iTopHub:GetMoreExtensions').'</button></p>');

	// Display the section about "manually deployed" extensions, only if there are some already
	$iCount = 0;
	foreach ($oExtensionsMap->GetAllExtensions() as $oExtension)
	{
		if ($oExtension->sSource == iTopExtension::SOURCE_MANUAL)
		{
			$iCount++ ;
		}
	}
	
	if ($iCount > 0)
	{
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('iTopHub:ExtensionCategory:Manual').'</legend>');
		$oPage->p(Dict::Format('iTopHub:ExtensionCategory:Manual+', '<span title="'.(APPROOT.'extensions').'" id="extension-dir-path">"extensions"</span>'));
		$oPage->add('<ul style="margin: 0;">');
		$iCount = 0;
		foreach ($oExtensionsMap->GetAllExtensions() as $oExtension)
		{
			if ($oExtension->sSource == iTopExtension::SOURCE_MANUAL)
			{
				DisplayExtensionInfo($oPage, $oExtension);
			}
		}
		$oPage->add('</ul>');
	}
	
	$oPage->add('</fieldset>');
	$sExtensionsDirTooltip = json_encode(APPROOT.'extensions');
	$oPage->add_style(
<<<EOF
#extension-dir-path {
	display: inline-block;
	border-bottom: 1px #999 dashed;
	cursor: help;
}
EOF
	);
}
catch (Exception $e)
{
	$oPage->p('<b>'.Dict::Format('UI:Error_Details', $e->getMessage()).'</b>');
}

$oPage->output();

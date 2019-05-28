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
 *
 *
 */

// Loading file
use Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration\Basic;
use Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration\Forms;
use Combodo\iTop\Portal\DependencyInjection\SilexCompatBootstrap\PortalXmlConfiguration\Lists;

// Note: ModuleDesign service is not available yet as this script is processed before service generation,
// that's why we have to instantiate it manually.
require_once APPROOT . 'core/moduledesign.class.inc.php';
$moduleDesign = new \ModuleDesign(PORTAL_ID);

// TODO: The following code needs to be refactored to more independent and atomic services.

//append into %combodo.portal.instance.conf%
$basicCompat = new Basic($moduleDesign);
$basicCompat->process($container);

//create %combodo.portal.instance.conf%
$formsCompat = new Forms($moduleDesign);
$formsCompat->process($container);

//append into %combodo.portal.instance.conf%
$listesCompat = new Lists($moduleDesign);
$listesCompat->process($container);

// - Generating CSS files
$aImportPaths = array(COMBODO_PORTAL_BASE_ABSOLUTE_PATH.'css/');
$aPortalConf = $container->getParameter('combodo.portal.instance.conf');
foreach ($aPortalConf['properties']['themes'] as $key => $value)
{
	if (!is_array($value))
	{
		$aPortalConf['properties']['themes'][$key] = COMBODO_ABSOLUTE_URL.utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$value, $aImportPaths);
	}
	else
	{
		$aValues = array();
		foreach ($value as $sSubvalue)
		{
			$aValues[] = COMBODO_ABSOLUTE_URL.utils::GetCSSFromSASS('env-'.utils::GetCurrentEnvironment().'/'.$sSubvalue, $aImportPaths);
		}
		$aPortalConf['properties']['themes'][$key] = $aValues;
	}
}
$container->setParameter('combodo.portal.instance.conf', $aPortalConf);

//TODO: The following needs to be refactored
//session messages
$aAllMessages = array();
if ((array_key_exists('obj_messages', $_SESSION)) && (!empty($_SESSION['obj_messages'])))
{
	foreach ($_SESSION['obj_messages'] as $sMessageKey => $aMessageObjectData)
	{
		$aObjectMessages = array();
		$aRanks = array();
		foreach ($aMessageObjectData as $sMessageId => $aMessageData)
		{
			$sMsgClass = 'alert alert-';
			switch ($aMessageData['severity'])
			{
				case 'info':
					$sMsgClass .= 'info';
					break;
				case 'error':
					$sMsgClass .= 'danger';
					break;
				case 'ok':
				default:
					$sMsgClass .= 'success';
					break;
			}
			$aObjectMessages[] = array('cssClass' => $sMsgClass, 'message' => $aMessageData['message']);
			$aRanks[] = $aMessageData['rank'];
		}
		unset($_SESSION['obj_messages'][$sMessageKey]);
		array_multisort($aRanks, $aObjectMessages);
		foreach ($aObjectMessages as $aObjectMessage)
		{
			$aAllMessages[] = $aObjectMessage;
		}
	}
}
$container->setParameter('combodo.current_user.session_messages', $aAllMessages);
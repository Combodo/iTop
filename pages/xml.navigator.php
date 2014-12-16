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
 * Specific page to build the XML data describing the "relation" around a given seed object
 * This XML is desgined to be consumed by the Flash Navigator object (see ../navigator folder)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');

/**
 * Fills the given XML node with te details of the specified object
 */ 
function AddNodeDetails(&$oNode, $oObj)
{
	$aZlist = MetaModel::GetZListItems(get_class($oObj), 'list');
	$aLabels = array();
	$index = 0;
	foreach($aZlist as $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
		$aLabels[] = $oAttDef->GetLabel();
		if (!$oAttDef->IsLinkSet())
		{
			$oNode->SetAttribute('att_'.$index, $oObj->GetAsHTML($sAttCode));
		}
		$index++;
	}
	$oNode->SetAttribute('zlist', implode(',', $aLabels));
}

$G_aCachedObjects = array();

/**
 * Get the related objects through the given relation, output in XML
 * @param DBObject $oObj The current object
 * @param string $sRelation The name of the relation to search with
 */
function GetRelatedObjectsAsXml(DBObject $oObj, $sRelationName, &$oLinks, &$oXmlDoc, &$oXmlNode, $iDepth = 0, $aExcludedClasses)
{
	global $G_aCachedObjects;
	$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth', 20);
	$aResults = array();
	$bAddLinks = false;

	if ($iDepth > ($iMaxRecursionDepth - 1)) return;

	$sIdxKey = get_class($oObj).':'.$oObj->GetKey();
	if (!array_key_exists($sIdxKey, $G_aCachedObjects))
	{
		$oObj->GetRelatedObjects($sRelationName, 1 /* iMaxDepth */, $aResults);
		$G_aCachedObjects[$sIdxKey] = true;
	}
	else
	{
		return;
		//$aResults = $G_aCachedObjects[$sIdxKey];
	}
	
	foreach($aResults as $sRelatedClass => $aObjects)
	{
		foreach($aObjects as $id => $oTargetObj)
		{
			if (is_object($oTargetObj))
			{
				if (in_array(get_class($oTargetObj), $aExcludedClasses))
				{
					GetRelatedObjectsAsXml($oTargetObj, $sRelationName, $oLinks, $oXmlDoc, $oXmlNode, $iDepth+1, $aExcludedClasses);
				}
				else
				{
					$oLinkingNode =   $oXmlDoc->CreateElement('link');
					$oLinkingNode->SetAttribute('relation', $sRelationName);
					$oLinkingNode->SetAttribute('arrow', 1); // Such relations have a direction, display an arrow
					$oLinkedNode = $oXmlDoc->CreateElement('node');
					$oLinkedNode->SetAttribute('id', $oTargetObj->GetKey());
					$oLinkedNode->SetAttribute('obj_class', get_class($oTargetObj));
					$oLinkedNode->SetAttribute('obj_class_name', htmlspecialchars(MetaModel::GetName(get_class($oTargetObj))));
					$oLinkedNode->SetAttribute('name', htmlspecialchars($oTargetObj->GetRawName())); // htmlentities is too much for XML
					$oLinkedNode->SetAttribute('icon', BuildIconPath($oTargetObj->GetIcon(false /* No IMG tag */)));
					AddNodeDetails($oLinkedNode, $oTargetObj);
					$oSubLinks = $oXmlDoc->CreateElement('links');
					// Recurse
					GetRelatedObjectsAsXml($oTargetObj, $sRelationName, $oSubLinks, $oXmlDoc, $oLinkedNode, $iDepth+1, $aExcludedClasses);
					$oLinkingNode->AppendChild($oLinkedNode);
					$oLinks->AppendChild($oLinkingNode);
					$bAddLinks = true;
				}
			}
		}
	}
	if ($bAddLinks)
	{
		$oXmlNode->AppendChild($oLinks);
	}
}

function BuildIconPath($sIconPath)
{
	return $sIconPath;
}

require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
// For developping the Navigator from within Flash
//session_start();
//$_SESSION['auth_user'] = 'admin';
//UserRights::Login($_SESSION['auth_user']); // Set the user's language
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oPage = new ajax_page("");
$oPage->no_cache();

$sClass = utils::ReadParam('class', 'Contact', false, 'class');
$id = utils::ReadParam('id', 1);
$sRelation = utils::ReadParam('relation', 'impacts');
$aValidRelations = MetaModel::EnumRelations();
$sFormat = utils::ReadParam('format', 'xml');
$sExcludedClasses = utils::ReadParam('exclude', '', false, 'raw_data');
$aExcludedClasses = explode(',', $sExcludedClasses);


if (!in_array($sRelation, $aValidRelations))
{
	// Not a valid relation, use the default one instead
	$sRelation = 'impacts';
}

try
{
	if ($id != 0)
	{
		switch($sFormat)
		{
			case 'html':
			$oPage->SetContentType('text/html');	
			$oObj = MetaModel::GetObject($sClass, $id, true /* object must exist */);
			$aResults = array();
			$iMaxRecursionDepth = MetaModel::GetConfig()->Get('relations_max_depth', 20);
			$oObj->GetRelatedObjects($sRelation, $iMaxRecursionDepth /* iMaxDepth */, $aResults);

			$iBlock = 1; // Zero is not a valid blockid
			foreach($aResults as $sClass => $aObjects)
			{
				$oSet = CMDBObjectSet::FromArray($sClass, $aObjects);
				$oPage->add("<h1>".MetaModel::GetRelationDescription($sRelation).' '.$oObj->GetName()."</h1>\n");
				$oPage->add("<div class=\"page_header\">\n");
				$oPage->add("<h2>".MetaModel::GetClassIcon($sClass)."&nbsp;<span class=\"hilite\">".Dict::Format('UI:Search:Count_ObjectsOf_Class_Found', count($aObjects), Metamodel::GetName($sClass))."</h2>\n");
				$oPage->add("</div>\n");
				$oBlock = DisplayBlock::FromObjectSet($oSet, 'list');
				$oBlock->Display($oPage, $iBlock++);
				$oPage->P('&nbsp;'); // Some space ?				
			}			
			break;
			
			case 'xml':
			default:
			$oPage->SetContentType('text/xml');				
			$oObj = MetaModel::GetObject($sClass, $id, true /* object must exist */);
			// Build the root XML part
			$oXmlDoc = new DOMDocument( '1.0', 'UTF-8' );
			$oXmlRoot = $oXmlDoc->CreateElement('root');
			$oXmlNode = $oXmlDoc->CreateElement('node');
			$oXmlNode->SetAttribute('id', $oObj->GetKey());
			$oXmlNode->SetAttribute('obj_class', get_class($oObj));
			$oXmlNode->SetAttribute('obj_class_name', htmlspecialchars(MetaModel::GetName(get_class($oObj))));
			$oXmlNode->SetAttribute('name',  htmlspecialchars($oObj->GetRawName()));
			$oXmlNode->SetAttribute('icon', BuildIconPath($oObj->GetIcon(false /* No IMG tag */))); // Hard coded for the moment
			AddNodeDetails($oXmlNode, $oObj);
			
			$oLinks = $oXmlDoc->CreateElement("links");
		
			$oXmlRoot->SetAttribute('position', 'left');
			$oXmlRoot->SetAttribute('title', MetaModel::GetRelationDescription($sRelation).' '. htmlspecialchars($oObj->GetRawName()));
			GetRelatedObjectsAsXml($oObj, $sRelation, $oLinks, $oXmlDoc, $oXmlNode, 0, $aExcludedClasses);
			
			$oXmlRoot->AppendChild($oXmlNode);
			$oXmlDoc->AppendChild($oXmlRoot);
			$oPage->add($oXmlDoc->SaveXML());
			break;
		}
	}
	$oPage->output();
}
catch(Exception $e)
{
	echo "Error: ".$e->getMessage();
}
?>
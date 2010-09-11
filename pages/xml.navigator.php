<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Specific page to build the XML data describing the "relation" around a given seed object
 * This XML is desgined to be consumed by the Flash Navigator object (see ../navigator folder)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/ajaxwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');

define('MAX_RECURSION_DEPTH', 20);

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

/**
 * Get the related objects through the given relation, output in XML
 * @param DBObject $oObj The current object
 * @param string $sRelation The name of the relation to search with
 */
function GetRelatedObjectsAsXml(DBObject $oObj, $sRelationName, &$oLinks, &$oXmlDoc, &$oXmlNode)
{
	$aResults = array();
	$oObj->GetRelatedObjects($sRelationName, 1 /* iMaxDepth */, $aResults);
	static $iDepth = 0;
	$iDepth++;
	if ($iDepth > MAX_RECURSION_DEPTH) return;
	
	foreach($aResults as $sRelatedClass => $aObjects)
	{
		foreach($aObjects as $id => $oTargetObj)
		{
			if (is_object($oTargetObj))
			{
				$oLinkingNode =   $oXmlDoc->CreateElement('link');
				$oLinkingNode->SetAttribute('relation', $sRelationName);
				$oLinkingNode->SetAttribute('arrow', 1); // Such relations have a direction, display an arrow
				$oLinkedNode = $oXmlDoc->CreateElement('node');
				$oLinkedNode->SetAttribute('id', $oTargetObj->GetKey());
				$oLinkedNode->SetAttribute('obj_class', get_class($oTargetObj));
				$oLinkedNode->SetAttribute('obj_class_name', MetaModel::GetName(get_class($oTargetObj)));
				$oLinkedNode->SetAttribute('name', $oTargetObj->GetName());
				$oLinkedNode->SetAttribute('icon', BuildIconPath($oTargetObj->GetIcon(false /* No IMG tag */)));
				AddNodeDetails($oLinkedNode, $oTargetObj);
				$oSubLinks = $oXmlDoc->CreateElement('links');
				// Recurse
				GetRelatedObjectsAsXml($oTargetObj, $sRelationName, $oSubLinks, $oXmlDoc, $oLinkedNode);
				$oLinkingNode->AppendChild($oLinkedNode);
				$oLinks->AppendChild($oLinkingNode);
			}
		}
	}
	if (count($aResults) > 0)
	{
		$oXmlNode->AppendChild($oLinks);
	}
}

function BuildIconPath($sIconPath)
{
	$sFullURL = utils::GetAbsoluteURL(false, false);
	$iLastSlashPos = strrpos($sFullURL, '/');
	$sFullURLPath = substr($sFullURL, 0, 1 + $iLastSlashPos);
	return $sFullURLPath.$sIconPath;
}

require_once('../application/startup.inc.php');
require_once('../application/loginwebpage.class.inc.php');
// For developping the Navigator from within Flash
//session_start();
//$_SESSION['auth_user'] = 'admin';
//UserRights::Login($_SESSION['auth_user']); // Set the user's language
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oPage = new ajax_page("");
$oPage->no_cache();

$sClass = utils::ReadParam('class', 'Contact');
$id = utils::ReadParam('id', 1);
$sRelation = utils::ReadParam('relation', 'impacts');
$aValidRelations = MetaModel::EnumRelations();
$sFormat = utils::ReadParam('format', 'xml');

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
			$oObj = MetaModel::GetObject($sClass, $id, true /* object must exist */);
			$aResults = array();
			$oObj->GetRelatedObjects($sRelation, MAX_RECURSION_DEPTH /* iMaxDepth */, $aResults);

			$iBlock = 0;
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
			$oObj = MetaModel::GetObject($sClass, $id, true /* object must exist */);
			// Build the root XML part
			$oXmlDoc = new DOMDocument( '1.0', 'UTF-8' );
			$oXmlRoot = $oXmlDoc->CreateElement('root');
			$oXmlNode = $oXmlDoc->CreateElement('node');
			$oXmlNode->SetAttribute('id', $oObj->GetKey());
			$oXmlNode->SetAttribute('obj_class', get_class($oObj));
			$oXmlNode->SetAttribute('obj_class_name', MetaModel::GetName(get_class($oObj)));
			$oXmlNode->SetAttribute('name', $oObj->GetName());
			$oXmlNode->SetAttribute('icon', BuildIconPath($oObj->GetIcon(false /* No IMG tag */))); // Hard coded for the moment
			AddNodeDetails($oXmlNode, $oObj);
			
			$oLinks = $oXmlDoc->CreateElement("links");
		
			$oXmlRoot->SetAttribute('position', 'left');
			$oXmlRoot->SetAttribute('title', MetaModel::GetRelationDescription($sRelation).' '.$oObj->GetName());
			GetRelatedObjectsAsXml($oObj, $sRelation, $oLinks, $oXmlDoc, $oXmlNode);
			
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

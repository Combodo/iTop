<?php
require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/ajaxwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');

/**
 * Determines the most appropriate icon (among the ones supported by the navigator)
 * for the given object
 * @param $oObj DBObject
 * @return string The name of the icon
 */    
function GetIcon(DBObject $oObj)
{
	switch(get_class($oObj))
	{
		case 'bizSoftware':
		$sIcon = 'application';
		break;

		case 'bizDatabase':
		$sIcon = 'database';
		break;

		case 'bizBusinessProcess':
		$sIcon = 'business_process';
		break;

		case 'bizContract':
		$sIcon = 'contract';
		break;
		
		case 'bizChangeTicket':
		$sIcon = 'change';
		break;
		
		case 'bizServiceCall':
		case 'bizIncidentTicket':
		$sIcon = 'incident';
		break;
		
		case 'bizServer':
		$sIcon = 'server';
		break;
		
		case 'bizPC':
		if ($oObj->Get('type') == 'desktop')
		{
			$sIcon = 'desktop PC';
		}
		else
		{
			$sIcon = 'laptop';
		}
		break;
		
		case 'bizNetworkDevice':
		$sIcon = 'network_device';
		break;

		case 'bizInterface':
		$sIcon = 'interface';
		break;
		
		case 'bizPerson':
		case 'bizTeam':
		$sIcon = 'contact';
		break;
		
		default:
		$sIcon = 'application';
	}
	return $sIcon;
}

/**
 * Fills the given XML node with te details of the specified object
 */ 
function AddNodeDetails(&$oNode, $oObj)
{
	$aZlist = MetaModel::GetZListItems(get_class($oObj), 'details');
	$aLabels = array();
	$index = 0;
	foreach($aZlist as $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($oObj), $sAttCode);
		$aLabels[] = $oAttDef->GetLabel();
		$oNode->SetAttribute('att_'.$index, $oObj->Get($sAttCode));
		$index++;
	}
	$oNode->SetAttribute('zlist', implode(',', $aLabels));
}

/**
 * Get the neighbours i.e. objects linked to the current object
 * @param DBObject $oObj The current object
 */
function GetNeighbours(DBObject $oObj, &$oLinks, &$oXmlDoc, &$oXmlNode)
{
	$oContext = new UserContext();
	$aRefs = MetaModel::EnumReferencingClasses(get_class($oObj));
	$aExtKeys = MetaModel::EnumLinkingClasses(get_class($oObj));
	if ((count($aRefs) != 0) || (count($aExtKeys) != 0))
	{
		foreach ($aRefs as $sRemoteClass => $aRemoteKeys)
		{
			foreach ($aRemoteKeys as $sExtKeyAttCode => $oExtKeyAttDef)
			{
				$oFilter = $oContext->NewFilter($sRemoteClass);
				$oFilter->AddCondition($sExtKeyAttCode, $oObj->GetKey());
				$oSet = new DBObjectSet($oFilter);
				if ($oSet->Count() > 0)
				{
					if (substr($sRemoteClass,0,3) == 'lnk')
					{
						// Special processing for "links":
						// Find out the first field which is an external key to another object,
						// and display this object as the one linked to the root object
						$sTargetClass = '';
						$sTargetAttCode = '';
						foreach(MetaModel::ListAttributeDefs($sRemoteClass) as $sAttCode=>$oAttDef)
						{
							if (($sAttCode != $sExtKeyAttCode) && ($oAttDef->IsExternalKey()) )
							{
								$sTargetClass = $oAttDef->GetTargetClass();
								$sTargetAttCode = $sAttCode;
								break;								
							}
						}
						if ($sTargetClass != '')
						{
							while( $oRelatedObj = $oSet->Fetch())
							{
								$oTargetObj = $oContext->GetObject($sTargetClass, $oRelatedObj->Get($sTargetAttCode));
								if (is_object($oTargetObj))
								{
									$oLinkingNode =   $oXmlDoc->CreateElement('link');
									$oLinkedNode = $oXmlDoc->CreateElement('node');
									$oLinkedNode->SetAttribute('id', $oTargetObj->GetKey());
									$oLinkedNode->SetAttribute('obj_class', get_class($oTargetObj));
									$oLinkedNode->SetAttribute('name', $oTargetObj->GetName());
									$oLinkedNode->SetAttribute('icon', BuildIconPath($oTargetObj->GetIcon()) );
									AddNodeDetails($oLinkedNode, $oTargetObj);
									$oLinkingNode->AppendChild($oLinkedNode);
									$oLinks->AppendChild($oLinkingNode);
								}
							}
						}
					}
					else
					{
						while( $oRelatedObj = $oSet->Fetch())
						{
							$oLinkingNode =   $oXmlDoc->CreateElement('link');
							$oLinkedNode = $oXmlDoc->CreateElement('node');
							$oLinkedNode->SetAttribute('id', $oRelatedObj->GetKey());
							$oLinkedNode->SetAttribute('obj_class', get_class($oRelatedObj));
							$oLinkedNode->SetAttribute('name', $oRelatedObj->GetName());
							$oLinkedNode->SetAttribute('icon', BuildIconPath($oRelatedObj->GetIcon()));
							AddNodeDetails($oLinkedNode, $oRelatedObj);
							$oLinkingNode->AppendChild($oLinkedNode);
							$oLinks->AppendChild($oLinkingNode);
						}
					}
				}
			}
			foreach ($aExtKeys as $sLinkClass => $aRemoteClasses)
			{
				foreach($aRemoteClasses as $sExtKeyAttCode => $sRemoteClass)
				{
					// Special case to exclude such "silos" classes that will be linked
					// to almost all the objects of a chart and thus would make the chart
					// un-readable if all the links are displayed...
					if (($sLinkClass == get_class($oObj)) &&
						($sRemoteClass != 'Location') &&
					    ($sRemoteClass != 'Organization') )
					{
						$oRelatedObj = $oContext->GetObject($sRemoteClass, $oObj->Get($sExtKeyAttCode));
						$oLinkingNode =   $oXmlDoc->CreateElement('link');
						$oLinkedNode = $oXmlDoc->CreateElement('node');
						$oLinkedNode->SetAttribute('id', $oRelatedObj->GetKey());
						$oLinkedNode->SetAttribute('obj_class', get_class($oRelatedObj));
						$oLinkedNode->SetAttribute('name', $oRelatedObj->GetName());
						$oLinkedNode->SetAttribute('icon', BuildIconPath($oRelatedObj->GetIcon()));
						AddNodeDetails($oLinkedNode, $oRelatedObj);
						$oLinkingNode->AppendChild($oLinkedNode);
						$oLinks->AppendChild($oLinkingNode);
					}
				}
			}
		}
		$oXmlNode->AppendChild($oLinks);
	}
}

/**
 * Get the related objects through the given relation
 * @param DBObject $oObj The current object
 * @param string $sRelation The name of the relation to search with
 */
function GetRelatedObjects(DBObject $oObj, $sRelationName, &$oLinks, &$oXmlDoc, &$oXmlNode)
{
	$oContext = new UserContext();
	$aResults = array();
	$oObj->GetRelatedObjects($sRelationName, 1 /* iMaxDepth */, $aResults);
	
	foreach($aResults as $sRelatedClass => $aObjects)
	{
		foreach($aObjects as $id => $oTargetObj)
		{
			if (is_object($oTargetObj))
			{
				$oLinkingNode =   $oXmlDoc->CreateElement('link');
				$oLinkingNode->SetAttribute('relation', $sRelationName);
				$oLinkingNode->SetAttribute('arrow', 1); // Such relations have a direction, display an arrow
				$oLinkingNode->SetAttribute('debug', 142); // Such relations have a direction, display an arrow
				$oLinkedNode = $oXmlDoc->CreateElement('node');
				$oLinkedNode->SetAttribute('id', $oTargetObj->GetKey());
				$oLinkedNode->SetAttribute('obj_class', get_class($oTargetObj));
				$oLinkedNode->SetAttribute('name', $oTargetObj->GetName());
				$oLinkedNode->SetAttribute('icon', BuildIconPath($oTargetObj->GetIcon()));
				AddNodeDetails($oLinkedNode, $oTargetObj);
				$oSubLinks = $oXmlDoc->CreateElement('links');
				GetRelatedObjects($oTargetObj, $sRelationName, $oSubLinks, $oXmlDoc, $oLinkedNode);
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
// For developping the Navigator
session_start();
$_SESSION['auth_user'] = 'admin';
$_SESSION['auth_pwd'] = 'admin2';
UserRights::Login($_SESSION['auth_user'], $_SESSION['auth_pwd']); // Set the user's language
//LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oPage = new ajax_page("");
$oPage->no_cache();

$oContext = new UserContext();
$sClass = utils::ReadParam('class', 'Contact');
$id = utils::ReadParam('id', 1);
$sRelation = utils::ReadParam('relation', 'impact');
$aValidRelations = MetaModel::EnumRelations();

if (!in_array($sRelation, $aValidRelations))
{
	// Not a valid relation, use the default one instead
	$sRelation = 'neighbours';
}

if ($id != 0)
{
	$oObj = $oContext->GetObject($sClass, $id);
	// Build the root XML part
	$oXmlDoc = new DOMDocument( '1.0', 'UTF-8' );
	$oXmlRoot = $oXmlDoc->CreateElement('root');
	$oXmlNode = $oXmlDoc->CreateElement('node');
	$oXmlNode->SetAttribute('id', $oObj->GetKey());
	$oXmlNode->SetAttribute('obj_class', get_class($oObj));
	$oXmlNode->SetAttribute('name', $oObj->GetName());
	$oXmlNode->SetAttribute('icon', BuildIconPath($oObj->GetIcon())); // Hard coded for the moment
	AddNodeDetails($oXmlNode, $oObj);
	
	$oLinks = $oXmlDoc->CreateElement("links");
	switch($sRelation)
	{
		case 'neighbours':
		// Now search for all the neighboor objects and append them
		$oXmlRoot->SetAttribute('title', 'Neighbours of '.$oObj->GetName());
		GetNeighbours($oObj, $oLinks, $oXmlDoc, $oXmlNode);
		$oXmlRoot->SetAttribute('position', 'center');
		break;
		
		default:
		$oXmlRoot->SetAttribute('position', 'left');
		$oXmlRoot->SetAttribute('title', MetaModel::GetRelationDescription($sRelation).' '.$oObj->GetName());
		GetRelatedObjects($oObj, $sRelation, $oLinks, $oXmlDoc, $oXmlNode);
	}
	
	$oXmlRoot->AppendChild($oXmlNode);
	$oXmlDoc->AppendChild($oXmlRoot);
	$oPage->add($oXmlDoc->SaveXML());
}
$oPage->output();
?>

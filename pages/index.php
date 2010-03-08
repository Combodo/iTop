<?php
require_once('../application/application.inc.php');
require_once('../application/nicewebpage.class.inc.php');
require_once('../application/dialogstack.class.inc.php');

require_once('../application/startup.inc.php');

$oPage = new NiceWebPage("The very first iTop page");
$oPage->no_cache();



MetaModel::CheckDefinitions();
// new API - MetaModel::DBCheckFormat();
// not necessary, and time consuming!
// MetaModel::DBCheckIntegrity();


// Comment by Rom: MetaModel::GetSubclasses("logRealObject") retourne la totale
//                 utiliser IsRootClass pour savoir si une classe obtenue est une classe feuille ou non
$aTopLevelClasses = array('bizService', 'bizLocation', 'bizContact', 'logInfra', 'bizDocument', 'bizObject');

function ReadParam($sName, $defaultValue = "")
{
	return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
}

function DisplaySelectOrg($oPage, $sCurrentOrganization, $iContext)
{
	global $oContext;
	
	//$oSearchFilter = new CMDBSearchFilter("bizOrganization");
	$oSearchFilter = $oContext->NewFilter("bizOrganization");
	$oPage->p($oSearchFilter->serialize());
	$oSet = new CMDBObjectSet($oSearchFilter);
	if ($oSet->Count() == 0)
	{
		$oPage->add("<div style=\"border:1px solid #97a5b0; margin-top:0.5em;\">\n");
		$oPage->add("<div style=\"padding:0.25em;background-color:#f0f0f0;text-align:center\">\n");
		$oPage->p("No organization found.\n");
		$oPage->p($oSearchFilter->__DescribeHTML());
		$oPage->add("</div>\n");
		$oPage->add("</div>\n");
	}
	else
	{
		$oCurrentOrganization = null;
		$oPage->add("<div style=\"border:1px solid #97a5b0; margin-top:0.5em;\">\n");
		$oPage->add("<div style=\"padding:0.25em;background-color:#f0f0f0;text-align:center\">\n");
		$oPage->add("<form method=\"get\"\">\n");
		$oPage->add("Select the context:\n");
		$oPage->add("<select name=\"ctx\">\n");
		$oPage->add("<option value=\"1\"".($iContext == 1 ? "selected" : "").">See everything (no context)</option>\n");
		$oPage->add("<option value=\"2\"".($iContext == 2 ? "selected" : "").">See only the iTop organization</option>\n");
		$oPage->add("<option value=\"3\"".($iContext == 3 ? "selected" : "").">See only organizations which name contains 'o', and contact in France (tel. contains +33)</option>\n");
		$oPage->add("</select>\n");
		$oPage->p("");
		$oPage->add("Select an organization: \n");
		$oPage->add("<select name=\"org\"\">\n");
		while($oOrg = $oSet->Fetch())
		{
			if ($sCurrentOrganization == $oOrg->GetKey())
			{
				$oCurrentOrganization = $oOrg;
				$sSelected = " selected";
			}
			else
			{
				$sSelected = "";
			}
			$oPage->add("<option value=\"".$oOrg->GetKey()."\"$sSelected>".$oOrg->Get('name')."</option>\n");
		}
		$oPage->add("</select>\n");
		$oPage->add("<input type=\"submit\" value=\" Search \">\n");
		$oPage->add("</form>\n");
		if ($oCurrentOrganization != null)
		{
			$oCurrentOrganization->DisplayDetails($oPage);
		} 
		$oPage->add("</div>\n");
		$oPage->add("</div>\n");
	}
}

function DisplayDetails(WebPage $oPage, $sClassName, $sKey)
{
	global $oContext;
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
    $oPage->p("Details of ".MetaModel::GetName($sClassName)." - $sKey");

	$oObj->DisplayDetails($oPage);
	
	// Modified by rom
	$aLinks = array();
	$aLinks[] = "<a href=\"?operation=changeslog&class=$sClassName&key=$sKey\">View changes log</a>";
	$aLinks[] = "<a href=\"?operation=edit&class=$sClassName&key=$sKey\">Edit this object</a>";
	$aLinks[] = "<a href=\"?operation=delete&class=$sClassName&key=$sKey\">Delete this object (no confirmation!)</a>";
	// By rom
	foreach (MetaModel::EnumLinkingClasses($sClassName) as $sLinkClass => $aRemoteClasses)
	{
		foreach($aRemoteClasses as $sExtKeyAttCode => $sRemoteClass)
		{
			// #@# quick and dirty: guess the extkey attcode from link to current class
			// later, this information should be part of the biz model
			$sExtKeyToMe = "";
			foreach(MetaModel::ListAttributeDefs($sLinkClass) as $sAttCode=>$oAttDef)
			{
				if ($oAttDef->IsExternalKey() && $oAttDef->GetTargetClass() == $sClassName)
				{
					$sExtKeyToMe = $sAttCode;
					break;
				}
			}
			if (empty($sExtKeyToMe))
			{
				$oPage->p("Houston... could not find the external key for $sClassName in $sLinkClass");
			}
			else
			{
				$oFilter = new CMDBSearchFilter($sRemoteClass); // just a dummy empty one for edition

				$sButton = "<div>\n";
				$sButton .= "<form action=\"./advanced_search.php\" method=\"post\">\n";
				$aOnOKArgs = array("operation"=>"addlinks", "linkclass"=>$sLinkClass, "extkeytome"=>$sExtKeyToMe, "extkeytopartner"=>$sExtKeyAttCode);
				$sButton .= dialogstack::RenderEditableField("Add links with $sRemoteClass", "filter", $oFilter->serialize(), true, "", $aOnOKArgs);
				$sButton .= "</form>\n";
				$sButton .= "</div>\n";
				$aLinks[] = $sButton;
			}
		}
	}
	$sLinks = implode("&nbsp;/&nbsp;", $aLinks);
	$oPage->p($sLinks);
}

// By Rom
function DisplayChangesLog(WebPage $oPage, $sClassName, $sKey)
{
	global $oContext;
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
    $oPage->p("Changes log for ".MetaModel::GetName($sClassName)." - $sKey");

	$oObj->DisplayChangesLog($oPage);
	
	$oPage->p("<a href=\"?operation=details&class=$sClassName&key=$sKey\">View details</a>");
	$oPage->p("<a href=\"?operation=edit&class=$sClassName&key=$sKey\">Edit this object</a>");
	$oPage->p("<a href=\"?operation=delete&class=$sClassName&key=$sKey\">Delete this object (no confirmation!)</a>");
}

function DumpObjects(WebPage $oPage, $sClassName, CMDBSearchFilter $oSearchFilter = null)
{
	global $oContext;

	if ($oSearchFilter == null)
	{
		//$oSearchFilter = new CMDBSearchFilter($sClassName);
		$oSearchFilter = $oContext->NewFilter($sClassName);
	}
	$aAttribs = array();
	foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
	{
		$aAttribs['key'] = array('label' => 'key', 'description' => 'Primary Key');
		$aAttribs[$sAttCode] = array('label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
	}
	$oObjectSet = new CMDBObjectSet($oSearchFilter);
	
	$aValues = array();
	while ($oObj = $oObjectSet->Fetch())
	{
		$aRow['key'] = "<a href=\"./index.php?operation=details&class=$sClassName&key=".$oObj->GetKey()."\">".$oObj->GetKey()."</a>";
		foreach($oObj->GetAttributesList($sClassName) as $sAttCode)
		{
			$aRow[$sAttCode] = $oObj->GetAsHTML($sAttCode);
		}
		$aValues[] = $aRow;
	}
	$oPage->table($aAttribs, $aValues);
}

function DisplayEditForm(WebPage $oPage, $sClassName, $sKey)
{
	global $oContext;
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
	if ($oObj == null)
	{
    	$oPage->p("You are not allowed to edit this object.");
		return;
	}
    $oPage->p("Edition of ".MetaModel::GetName($sClassName)." - $sKey\n");
	
	$aDetails = array();
    $oPage->add("<form method=\"post\">\n");
	foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
	{
		if (!$oAttDef->IsExternalField())
		{
			if ($oAttDef->IsExternalKey())
			{
				//External key, display a combo
				$sTargetClass = $oAttDef->GetTargetClass();
				//$oFilter = new CMDBSearchFilter($sTargetClass);
				$oFilter = $oContext->NewFilter($sTargetClass);
				$oSet = new CMDBObjectSet($oFilter);
				$sValue = "<select name=\"attr[$sAttCode]\">\n";
				while($oTargetObj = $oSet->Fetch())
				{
					if ($oObj->Get($sAttCode) == $oTargetObj->GetKey())
					{
						$sSelected = " selected";
					}
					else
					{
						$sSelected = "";
					}
					$sValue .= "<option value=\"".$oTargetObj->GetKey()."\"$sSelected>".$oTargetObj->Get('name')."</option>\n";
				}
				$sValue .= "</select>\n";
			}
			else
			{
				$sValue = "<input size=\"50\" name=\"attr[$sAttCode]\" value=\"".($oObj->Get($sAttCode))."\">";
			}
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sValue);
		}
	}
	$oPage->details($aDetails);
    $oPage->add("<input type=\"hidden\" name=\"key\" value=\"$sKey\">\n");
    $oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClassName\">\n");
    $oPage->add("<input type=\"hidden\" name=\"operation\" value=\"update\">\n");
    $oPage->add("<input type=\"submit\" value=\" Update \">\n");
    $oPage->add("<form method=\"post\">\n");
}

function DisplayCreationForm(WebPage $oPage, $sClassName)
{
	global $oContext;
    $oPage->p("New $sClassName\n");
	
	$aDetails = array();
    $oPage->add("<form method=\"post\">\n");
	foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
	{
		if (!$oAttDef->IsExternalField())
		{
			if ($oAttDef->IsExternalKey())
			{
				//External key, display a combo
				$sTargetClass = $oAttDef->GetTargetClass();
				//$oFilter = new CMDBSearchFilter($sTargetClass);
				$oFilter = $oContext->NewFilter($sTargetClass);
				$oSet = new CMDBObjectSet($oFilter);
				$sValue = "<select name=\"attr[$sAttCode]\">\n";
				while($oTargetObj = $oSet->Fetch())
				{
					$sValue .= "<option value=\"".$oTargetObj->GetKey()."\">".$oTargetObj->Get('name')."</option>\n";
				}
				$sValue .= "</select>\n";
			}
			else
			{
				$sValue = "<input size=\"50\" name=\"attr[$sAttCode]\" value=\"".$oAttDef->GetDefaultValue()."\">";
			}
			$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sValue);
		}
	}
	$oPage->details($aDetails);
    $oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClassName\">\n");
    $oPage->add("<input type=\"hidden\" name=\"operation\" value=\"create\">\n");
    $oPage->add("<input type=\"submit\" value=\" Create \">\n");
    $oPage->add("<form method=\"post\">\n");
}

function UpdateObject(WebPage $oPage, $sClassName, $sKey, $aAttributes)
{
	global $oContext;
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
	if ($oObj == null)
	{
    	$oPage->p("You are not allowed to edit this object.");
		return;
	}
    $oPage->p("Update of $sClassName - $sKey");

	foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
	{
		if (isset($aAttributes[$sAttCode]))
		{
			$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
		}
	}
	if ($oObj->CheckToUpdate())
	{
		// By rom
		// $oObj->DBUpdate();
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by somebody");
		$iChangeId = $oMyChange->DBInsert();
		$oObj->DBUpdateTracked($oMyChange);

		$oPage->p(MetaModel::GetName($sClassName)." updated\n");
	}
	else
	{
		$oPage->p("<strong>Error: object can not be updated!</strong>\n");
		$oObj->DBRevert(); // restore default values!
	}
	// By Rom
	// $oObj->DisplayDetails($oPage);
	// replaced by...	
	DisplayDetails($oPage, $sClassName, $sKey);
	$oPage->p("<a href=\"\">Return to main page</a>");
}

function DeleteObject(WebPage $oPage, $sClassName, $sKey)
{
	global $oContext;
	$sClassLabel = MetaModel::GetName($sClassName);
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
	if ($oObj == null)
	{
    	$oPage->p("You are not allowed to delete this object.");
		return;
	}
    $oPage->p("Deletion of $sClassLabel - $sKey");

	if ($oObj->CheckToDelete())
	{
		// By Rom
		//$oObj->DBDelete();
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by somebody");
		$iChangeId = $oMyChange->DBInsert();
		$oObj->DBDeleteTracked($oMyChange);

		$oPage->p("$sClassLabel deleted\n");
	}
	else
	{
		$oPage->p("<strong>Error: object can not be deleted!</strong>\n");
		// By Rom
		DisplayDetails($oPage, $sClassName, $sKey);
	}
	$oPage->p("<a href=\"\">Return to main page</a>");
}

function CreateObject(WebPage $oPage, $sClassName, $aAttributes)
{
    $oObj = MetaModel::NewObject($sClassName);
    $sClassLabel = MetaModel::GetName(get_class($oObj));
    $oPage->p("Creation of $sClassLabel object.");

	foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode=>$oAttDef)
	{
		if (isset($aAttributes[$sAttCode]))
		{
			$oObj->Set($sAttCode, $aAttributes[$sAttCode]);
		}
	}
	list($bRes, $aIssues) = $oObj->CheckToInsert();
	if ($bRes)
	{
		// By rom
		// $oObj->DBInsert();
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by somebody");
		$iChangeId = $oMyChange->DBInsert();
		$oObj->DBInsertTracked($oMyChange);

		$oPage->p($sClassLabel." created\n");

		// By Rom
		// $oObj->DisplayDetails($oPage);
		// replaced by...	
		DisplayDetails($oPage, get_class($oObj), $oObj->GetKey());
	}
	else
	{
		$oPage->p("<strong>Error: object can not be created!</strong>\n");
		$oPage->add("<ul>Issues:");
		foreach($aIssues as $sErrorMsg)
		{
			$oPage->add("<li>$sErrorMsg</li>");
		}
		$oPage->add("</ul>");
	}
	$oPage->p("<a href=\"\">Return to main page</a>");
}

// By Rom
function AddLinks($oPage, $sClassName, $sKey, $sLinkClass, $sExtKeyToMe, $sExtKeyToPartner, $sFilter)
{
	global $oContext;
	$sClassLabel = MetaModel::GetName($sClassName);
    //$oObj = MetaModel::GetObject($sClassName, $sKey);
    $oObj = $oContext->GetObject($sClassName, $sKey);
	if ($oObj == null)
	{
    	$oPage->p("You are not allowed to modify (create links on) this object.");
		return;
	}
    $oPage->p("Creating links for $sClassLabel - $sKey");

	$oFilter = CMDBSearchFilter::unserialize($sFilter);
	$oPage->p("Linking to ".$oFilter->__DescribeHTML()); 

	$oObjSet = new CMDBObjectSet($oFilter);
	if ($oObjSet->Count() != 0)
	{
		while($oPartnerObj = $oObjSet->Fetch())
		{
			$oNewLink = MetaModel::NewObject($sLinkClass);
			$oNewLink->Set($sExtKeyToMe, $sKey);
			$oNewLink->Set($sExtKeyToPartner, $oPartnerObj->GetKey());
			list($bRes, $aIssues) = $oNewLink->CheckToInsert();
			if ($bRes)
			{
				$oMyChange = MetaModel::NewObject("CMDBChange");
				$oMyChange->Set("date", time());
				$oMyChange->Set("userinfo", "Made by somebody");
				$iChangeId = $oMyChange->DBInsert();
				$oNewLink->DBInsertTracked($oMyChange);
		
				$oPage->p(MetaModel::GetName($sLinkClass)." created\n");
			}
			else
			{
				$oPage->p("<strong>Error: link can not be created!</strong>\n");
				$oPage->add("<ul>Issues:");
				foreach($aIssues as $sErrorMsg)
				{
					$oPage->add("<li>$sErrorMsg</li>");
				}
				$oPage->add("</ul>");
			}
		}
	}
	else
	{}

}


///////////////////////////////////////////////////////////////////////////////////////////////////
//
//  M a i n   P r o g r a m
//
///////////////////////////////////////////////////////////////////////////////////////////////////

$operation = ReadParam('operation', '');
$iContext = ReadParam('ctx', 1);

$oContext = new UserContext();

switch($iContext)
{
	case 2: // See only the organization 'ITOP'
	$oContext->AddCondition('bizOrganization', 'pkey', 'ITOP', '=');
	$oContext->AddCondition('logRealObject', 'organization', 'ITOP', '=');
	break;
	
	case 3: // See only the organization containing 'o' and contacts containing +33
	$oContext->AddCondition('Organization', 'name', 'o', 'Contains');
	//$oContext->AddCondition('Object', 'orgname', 'o', 'Contains');
	$oContext->AddCondition('Contact', 'phone', '+33', 'Contains');
	break;
	
	case 1: // No limitation
	default:
	// nothing to do
}

dialogstack::DeclareCaller("Main navigation menu");

switch($operation)
{
    case 'details':
        $sClass = ReadParam('class');
        $sKey = ReadParam('key');
        DisplayDetails($oPage, $sClass, $sKey);
    break;

	// By rom
    case 'changeslog':
        $sClass = ReadParam('class');
        $sKey = ReadParam('key');
        DisplayChangesLog($oPage, $sClass, $sKey);
    break;

    case 'edit':
        $sClass = ReadParam('class');
        $sKey = ReadParam('key');
        DisplayEditForm($oPage, $sClass, $sKey);
    break;
	
    case 'update':
        $sClass = ReadParam('class');
        $sKey = ReadParam('key');
        $aAttributes = ReadParam('attr', array());
        UpdateObject($oPage, $sClass, $sKey, $aAttributes);
    break;
	
    case 'new':
        $sClass = ReadParam('class');
        DisplayCreationForm($oPage, $sClass);
    break;

    case 'create':
        $sClass = ReadParam('class');
        $aAttributes = ReadParam('attr', array());
        CreateObject($oPage, $sClass, $aAttributes);
    break;
	
    case 'delete':
        $sClass = ReadParam('class');
        $sKey = ReadParam('key');
        DeleteObject($oPage, $sClass, $sKey);
    break;

	case 'addlinks':
		$sClass = ReadParam('class');
        $sKey = ReadParam('key');
		$sLinkClass = ReadParam('linkclass');
		$sExtKeyToMe = ReadParam('extkeytome');
		$sExtKeyToPartner = ReadParam('extkeytopartner');
		$sFilter = ReadParam('filter');
		AddLinks($oPage, $sClass, $sKey, $sLinkClass, $sExtKeyToMe, $sExtKeyToPartner, $sFilter);
	break;
	
    default:
	$sCurrentOrganization = ReadParam('org', '');
	$sActiveTab = ReadParam('classname', '');
	DisplaySelectOrg($oPage, $sCurrentOrganization, $iContext);
	if ($sCurrentOrganization != "")
	{
		$oPage->add("<div id=\"classesTabs\" class=\"light\">\n");
		$oPage->add("<ul>\n");
		$index = 1;
		$iActiveTabIndex = 1; // By default the first tab is the active one
		foreach( $aTopLevelClasses as $sClassName)
		{
			if ($sClassName == $sActiveTab)
			{
				$iActiveTabIndex = $index;
			}
			$oPage->add("\t<li><a href=\"#tab_$sClassName\">$sClassName</a></li>\n");
			$index++;
		}
		$oPage->add("</ul>\n");
		foreach( $aTopLevelClasses as $sClassName)
		{
			$sClassLabel = MetaModel::GetName($sClassName);
			$oPage->add("<div id=\"tab_$sClassName\">");
			if (count(MetaModel::GetSubclasses($sClassName)) > 0)
			{
				$sActiveSubclass = ReadParam('subclassname', '');
				foreach(MetaModel::GetSubclasses($sClassName) as $sSubclassName)
				{
					$sSubclassLabel = MetaModel::GetName($sSubclassName);
					//$oSearchFilter = new CMDBSearchFilter($sSubclassName);
					$oSearchFilter = $oContext->NewFilter($sSubclassName);
					$oSearchFilter ->AddCondition('org_id', $sCurrentOrganization, '=');

					$oPage->add("<div style=\"border:1px solid #97a5b0; margin-top:0.5em;\">\n");
					$oPage->add("<div style=\"padding:0.25em;background-color:#f0f0f0\">\n");
					$oPage->p("<strong>$sSubclassLabel</strong> - ".MetaModel::GetClassDescription($sSubclassName));
					$oPage->add("<form method=\"get\">\n");
					$oPage->add("<input type=\"hidden\" name=\"classname\" value=\"$sClassName\">\n");
					$oPage->add("<input type=\"hidden\" name=\"subclassname\" value=\"$sSubclassName\">\n");
					$oPage->add("<input type=\"hidden\" name=\"ctx\" value=\"$iContext\">\n");
					$oPage->add("<input type=\"hidden\" name=\"org\" value=\"$sCurrentOrganization\">\n");
					foreach( MetaModel::GetClassFilterDefs($sSubclassName) as $sFilterCode=>$oFilterDef)
					{
						$sFilterValue = "";
						if (($sActiveTab == $sClassName) && ($sActiveSubclass == $sSubclassName))
						{
							$sFilterValue = ReadParam($sFilterCode, '');
							if (!empty($sFilterValue))
							{
								$oSearchFilter->AddCondition($sFilterCode, $sFilterValue, 'Contains');
							}
						}
						$oPage->add($oFilterDef->GetLabel().": <input name=\"$sFilterCode\" value=\"$sFilterValue\"/>&nbsp;\n");
					}
					$oPage->add("<input type=\"submit\" value=\"Search\">\n");
					$oPage->add("</form>\n");
					$oPage->add("</div>\n");

					$oSet = new CMDBObjectSet($oSearchFilter);
					$iMatchesCount = $oSet->Count();
					if ($iMatchesCount == 0)
					{
						$oPage->p("No $sSubclassLabel matches these criteria.");
						$oPage->small_p("(".$oSearchFilter->__DescribeHTML().")");
					}
					else
					{
						$oPage->p("$iMatchesCount item(s) found.");
						cmdbAbstractObject::DisplaySet($oPage, $oSet);
					}
					$oPage->p("<a href=\"?operation=new&class=$sSubclassName\">Create a new $sSubclassLabel</a>\n");
					$oPage->add("</div>\n");
				}
			}
			else
			{
				// No subclasses, list the form directly
				//$oSearchFilter = new CMDBSearchFilter($sClassName);
				$oSearchFilter = $oContext->NewFilter($sClassName);
				$oSearchFilter ->AddCondition('org_id', $sCurrentOrganization, '=');

				$oPage->add("<div style=\"border:1px solid #97a5b0; margin-top:0.5em;\">\n");
				$oPage->add("<div style=\"padding:0.25em;background-color:#f0f0f0\">\n");
				$oPage->p("<strong>$sClassLabel</strong> - ".MetaModel::GetClassDescription($sClassName));
				$oPage->add("<form method=\"get\">\n");
				$oPage->add("<input type=\"hidden\" name=\"classname\" value=\"$sClassName\">\n");
				$oPage->add("<input type=\"hidden\" name=\"org\" value=\"$sCurrentOrganization\">\n");
				$oPage->add("<input type=\"hidden\" name=\"ctx\" value=\"$iContext\">\n");
				foreach( MetaModel::GetClassFilterDefs($sClassName) as $sFilterCode=>$oFilterDef)
				{
					$sFilterValue = "";
					if ($sActiveTab == $sClassName)
					{
						$sFilterValue = ReadParam($sFilterCode, '');
						if (!empty($sFilterValue))
						{
							$oSearchFilter->AddCondition($sFilterCode, $sFilterValue, 'Contains');
						}
					}
					$oPage->add($oFilterDef->GetLabel().": <input name=\"$sFilterCode\" value=\"$sFilterValue\"/>&nbsp;\n");
				}
				$oPage->add("<input type=\"submit\" value=\"Search\">\n");
				$oPage->add("</form>\n");
				$oPage->add("</div>\n");
				$oPage->add("<div style=\"padding:0.25em;background-color:#fff\">\n");
				$oSet = new CMDBObjectSet($oSearchFilter);
				$iMatchesCount = $oSet->Count();
				if ($iMatchesCount == 0)
				{
					$oPage->p("No $sClassLabel matches these criteria.");
					$oPage->small_p("(".$oSearchFilter->__DescribeHTML().")");
				}
				else
				{
					$oPage->p("$iMatchesCount item(s) found.");
					cmdbAbstractObject::DisplaySet($oPage, $oSet);
					$oPage->small_p("(".$oSearchFilter->__DescribeHTML().")");
				}
				$oPage->p("<a href=\"?operation=new&ctx=$iContext&class=$sClassName\">Create a new $sClassLabel</a>\n");
				$oPage->add("</div>\n");
				$oPage->add("</div>\n");
			}
			$oPage->add("</div>\n");
		}
		$oPage->add("</div>\n");
		$oPage->add_script('$(function() {$("#classesTabs > ul").tabs( '.$iActiveTabIndex.', { fxFade: true, fxSpeed: \'fast\' } );});');
	}
}
$oPage->output();
?>

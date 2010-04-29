<?php

// By Rom


require_once('../application/nicewebpage.class.inc.php');
require_once('../application/dialogstack.class.inc.php');

require_once('../application/startup.inc.php');

// #@# not used, but... require_once('../classes/usercontext.class.inc.php');
$oPage = new NiceWebPage("ITop finder");
$oPage->no_cache();


MetaModel::CheckDefinitions();
// new API - MetaModel::DBCheckFormat();
// not necessary, and time consuming!
// MetaModel::DBCheckIntegrity();


function ReadParam($sName, $defaultValue = "")
{
	return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
}



function Page1_AskClass($oPage)
{
    $oPage->add("<form method=\"post\" action=\"\">\n");
	//$oPage->add("<input type=\"hidden\" name=\"tnut\" value=\"blah\">");
	$oPage->p("Please select the type of object that you want to look for:");
    $oPage->MakeClassesSelect("class", "", 50, UR_ACTION_READ);
    $oPage->add("<input type=\"submit\" name=\"userconfig\" value=\"Configure filters\">\n");
	$oPage->add("</form>\n");
}


function Page2_ConfigFilters($oPage, $oFilter)
{
	$sClass = $oFilter->GetClass();

	$oPage->p("Objects of class <em>$sClass</em>");
    $oPage->add("<form method=\"post\" action=\"\">\n");
	$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n"); 

	// Full text input
	//
	$oPage->add("<div>\n");
	$oPage->add("Full text: ");
	$sFullText = "";
	foreach($oFilter->GetCriteria_FullText() as $sFullText)
	{
		// #@# Known limitation: do not consider other full text conditions...
		continue;
	}
	
	$oPage->add("<input type=\"text\" name=\"flt_fulltext\" value=\"$sFullText\">\n"); 
	$oPage->add("</div>\n");

	// Attribute-related criteria
	//
	foreach (MetaModel::GetClassFilterDefs($sClass) as $sFltCode => $oFltDef)
	{
		// Set its current values
		$sOpCode = "__none__";
		$sValue = "";
		foreach($oFilter->GetCriteria() as $aCritInfo)
		{
			if ($aCritInfo["filtercode"] == "id")
			{
				// ???
			}
			elseif ($aCritInfo["filtercode"] == $sFltCode)
			{
				$sOpCode = $aCritInfo["opcode"];
				$sValue = $aCritInfo["value"];
				break;
			}
		}

		$oPage->add("<div>\n");
		//$oPage->add($oFltDef->GetType()." (".$oFltDef->GetTypeDesc().")");
		$oPage->add(" ".$oFltDef->GetLabel()." ");

		$aOperators = array_merge(array("__none__" => ""), $oFltDef->GetOperators());
		$oPage->add_select($aOperators, "flt_ops[$sFltCode]", $sOpCode, 100);
		$oPage->add("\n");

		$oPage->add("<input type=\"text\" name=\"flt_values[$sFltCode]\" value=\"$sValue\">\n"); 
		$oPage->add("</div>\n");
	}

	// Ext key criteria
	//
	foreach (MetaModel::EnumReferencedClasses($sClass) as $sExtKeyAttCode => $sRemoteClass)
	{
		// Set its current values
		$oSubFilter = $oFilter->GetCriteria_PointingTo($sExtKeyAttCode);
		if (!$oSubFilter)
		{
			$oSubFilter = new CMDBSearchFilter($sRemoteClass);
		}

		$oPage->add("<div>\n");
		$oAtt = MetaModel::GetAttributeDef($oFilter->GetClass(), $sExtKeyAttCode);
		$oPage->add($oAtt->GetLabel()." having ({$oSubFilter->DescribeConditions()})");
		//$oPage->add("having $oFilter->DescribeConditionPointTo($sExtKeyAttCode));
		$oPage->add("\n");

		$oPage->add(dialogstack::RenderEditableField("Edit...", "flt_pointto[$sExtKeyAttCode]", $oSubFilter->serialize(), true));
		$oPage->add("</div>\n");
	}

	// Ext key criteria, the other way
	//
	foreach (MetaModel::EnumReferencingClasses($sClass, true) as $sRemoteClass => $aRemoteKeys)
	{
		foreach ($aRemoteKeys as $sExtKeyAttCode => $oExtKeyAttDef)
		{
			// Set its current values
			$oSubFilter = $oFilter->GetCriteria_ReferencedBy($sRemoteClass, $sExtKeyAttCode);
			if (!$oSubFilter)
			{
				$oSubFilter = new CMDBSearchFilter($sRemoteClass);
			}

			$oPage->add("<div>\n");
			//$oPage->add($oFilter->DescribeConditionRefBy($sRemoteClass, $sExtKeyAttCode));
			$oAtt = MetaModel::GetAttributeDef($sRemoteClass, $sExtKeyAttCode);
			$oPage->add("being ".$oAtt->GetLabel()." for ".$sRemoteClass."(e)s in ({$oSubFilter->DescribeConditions()})");
			$oPage->add("\n");
	
			$oPage->add(dialogstack::RenderEditableField("Edit...", "flt_refedby[$sRemoteClass][$sExtKeyAttCode]", $oSubFilter->serialize(), true));
			$oPage->add("</div>\n");
		}
	}

	// Ext key criteria -> link objects
	//
	foreach (MetaModel::EnumLinkingClasses($sClass) as $sLinkClass => $aRemoteClasses)
	{
		foreach($aRemoteClasses as $sExtKeyAttCode => $sRemoteClass)
		{
			// Set its current values
			//$oSubFilter = $oFilter->GetCriteria_PointingTo($sExtKeyAttCode);
			$oSubFilter = null;
			if (!$oSubFilter)
			{
				$oSubFilter = new CMDBSearchFilter($sRemoteClass);
			}
			$oPage->add("<div>\n");
			//$oPage->add(" ".MetaModel::GetName($sRemoteClass)." ");
			$oPage->add(" Linked to '".MetaModel::GetLinkLabel($sLinkClass, $sExtKeyAttCode)."' by ");
			$oSubFilter = new CMDBSearchFilter($sRemoteClass);
			$oPage->add($oSubFilter->__DescribeHTML());
			$oPage->add("\n");

			$oPage->add(dialogstack::RenderEditableField("Edit...", "flt_linkedwith[$sRemoteClass][$sExtKeyAttCode]", $oSubFilter->serialize(), true));
			$oPage->add("</div>\n");
		}
	}

    $oPage->add("<input type=\"submit\" name=\"makeit\" value=\"Search\">\n");
	$oPage->add("</form>\n");
}

function MakeFilterFromArgs()
{
	$sClass = ReadParam("class");
	$sFilterFullText = ReadParam("flt_fulltext", "");
	$aFilterOps = ReadParam("flt_ops", array());
	$aFilterValues = ReadParam("flt_values", array());
	$aPointTo = ReadParam("flt_pointto", array());
	$aRefedBy = ReadParam("flt_refedby", array());
	$aLinkedWith = ReadParam("flt_linkedwith", array());

	$oFilter = new CMDBSearchFilter($sClass);

	if (!empty($sFilterFullText))
	{
		$oFilter->AddCondition_FullText($sFilterFullText);
	}

	foreach($aFilterOps as $sFltCode=>$sOpCode)
	{
		if ($sOpCode == "__none__") continue;
		$oFilter->AddCondition($sFltCode, $aFilterValues[$sFltCode], $sOpCode);
	}

	foreach($aPointTo as $sExtKeyAttCode=>$sFilterShortcut)
	{
		$oSubFilter = CMDBSearchFilter::unserialize($sFilterShortcut);
		$oFilter->AddCondition_PointingTo($oSubFilter, $sExtKeyAttCode);
	}

	foreach($aRefedBy as $sForeignClass=>$aExtKeys)
	{
		foreach($aExtKeys as $sForeignExtKey=>$sFilterShortcut)
		{
			//MyHelpers::var_dump_html("$sForeignClass / $sForeignExtKey / $sFilterShortcut");
			$oSubFilter = CMDBSearchFilter::unserialize($sFilterShortcut);
			//MyHelpers::var_dump_html($oSubFilter);
			$oFilter->AddCondition_ReferencedBy($oSubFilter, $sForeignExtKey);
		}
	}

//	$oFilter->AddCondition_LinkedTo(DBObjectSearch $oLinkFilter, $sExtKeyAttCodeToMe, $sExtKeyAttCodeTarget, DBObjectSearch $oFilterTarget);

	return $oFilter;
}

function Page3_ViewResults($oPage, $oFilter)
{
	// Output results in various forms...
	//
	if ($oFilter->IsAny())
	{
		$oPage->p("You are considering the ENTIRE set of objects...");
	}
	else
	{
		$oPage->p($oFilter->__DescribeHTML());
		
		$oSet = new CMDBObjectSet($oFilter);
		$oPage->p("Found ".$oSet->Count()." items");
	
		$sFilterPhrase = urlencode($oFilter->serialize());
		$oPage->p("<a href=\"/pages/index.php?operation=search&filter=$sFilterPhrase\">See detailed results</a>"); 
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//
//  M a i n   P r o g r a m
//
///////////////////////////////////////////////////////////////////////////////////////////////////


$oPage->p("<h1>Advanced search</h1>");


// Page 1 - Ask class
//
// Page 2 - Class is given, enum existing filters/possible links
//
// Page 3 - Interpret user choices, create a filter and render its string representation
//

//MyHelpers::arg_dump_html();
//MyHelpers::var_dump_html($_SESSION);

if (ReadParam('userconfig', false))
{
	$sTodo = 'userconfig';
}
if (ReadParam('makeit', false))
{
	$sTodo = 'makeit';
}
else
{
	if (dialogstack::IsDialogStartup())
	{
		$sInit = dialogstack::StartDialog();
		$oFilter = CMDBSearchFilter::unserialize($sInit);
		$sTodo = 'userconfig';
	}
	else
	{
		$sClass = ReadParam('class', '');
		if (empty($sClass))
		{
			$sTodo = 'selectclass';
		}
		else
		{
			$oFilter = MakeFilterFromArgs();
			$sTodo = 'userconfig';
		}
	}
}

switch ($sTodo)
{
case "selectclass":
	Page1_AskClass($oPage);
	break;

case "userconfig":
	dialogstack::DeclareCaller("Define filter for ".$oFilter->GetClass());
	$oPage->add(implode(" / ", dialogstack::GetCurrentStack()));
	Page2_ConfigFilters($oPage, $oFilter);
	break;

case "makeit":
	$oFilter = MakeFilterFromArgs();
	Page3_ViewResults($oPage, $oFilter);
	$oPage->add(dialogstack::RenderEndDialogForm(DLGSTACK_OK, "Use filter", $oFilter->serialize()));
	$oPage->add(dialogstack::RenderEndDialogForm(DLGSTACK_CANCEL, "Annuler"));
	break;

default:
	trigger_error("Wrong value for arg <em>todo</em> ($sTodo)", E_USER_ERROR);
}

$oPage->output();

?>

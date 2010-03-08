<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);

$oPage = new iTopWebPage("iTop - Bulk import", $currentOrganization);

define ('EXTKEY_SEP', '::::');
define ('EXTKEY_LABELSEP', ' -> ');

///////////////////////////////////////////////////////////////////////////////
// External key/field naming conventions (sharing the naming space with std attributes
///////////////////////////////////////////////////////////////////////////////

function IsExtKeyField($sColDesc)
{
	return ($iPos = strpos($sColDesc, EXTKEY_SEP));
}

function GetExtKeyFieldCodes($sColDesc)
{
	$iPos = strpos($sColDesc, EXTKEY_SEP);
	return array(
		substr($sColDesc, 0, $iPos),
		substr($sColDesc, $iPos + strlen(EXTKEY_SEP))
	);
}

function MakeExtFieldLabel($sClass, $sExtKeyAttCode, $sForeignAttCode)
{
	$oExtKeyAtt = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
	if ($sForeignAttCode == 'id')
	{
		$sForeignAttLabel = 'id';
	}
	else
	{
		$oForeignAtt = MetaModel::GetAttributeDef($oExtKeyAtt->GetTargetClass(), $sForeignAttCode);
		$sForeignAttLabel = $oForeignAtt->GetLabel();
	}
	
	return $oExtKeyAtt->GetLabel().EXTKEY_LABELSEP.$sForeignAttLabel;
}

function MakeExtFieldSelectValue($sAttCode, $sExtAttCode)
{
	return $sAttCode.EXTKEY_SEP.$sExtAttCode;
}

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


function ShowTableForm($oPage, $oCSVParser, $sClass)
{
	$aData = $oCSVParser->ToArray(1, null, 3);
	$aColToRow = array();
	foreach($aData as $aRow)
	{
		foreach ($aRow as $sFieldId=>$sValue)
		{
			$aColToRow[$sFieldId][] = $sValue;
		}
	}

	$aFields = array();
	foreach($oCSVParser->ListFields() as $iFieldIndex=>$sFieldName)
	{
		$sFieldName = trim($sFieldName);

		$aOptions = array();
		$aOptions['id'] = array(
			'LabelHtml' => "Private key",
			'LabelRef' => "Private key",
			'IsReconcKey' => false,
			'Tip' => '',
		);

		$sFoundAttCode = ""; // quick and dirty way to remind if a match has been found and suggest a reconciliation key if possible
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAtt)
		{
			if ($oAtt->IsExternalField()) continue;

			$bIsThatField = (strcasecmp($sFieldName, $oAtt->GetLabel()) == 0);
			$sFoundAttCode = (MetaModel::IsValidFilterCode($sClass, $sAttCode) && $bIsThatField) ? $sAttCode : $sFoundAttCode; 

			if ($oAtt->IsExternalKey())
			{
				// An external key might be loaded by
				// the pkey or a reconciliation key
				//
				$aOptions[MakeExtFieldSelectValue($sAttCode, 'id')] = array(
					'LabelHtml' => "<em>".$oAtt->GetLabel()."</em> (id)",
					'LabelRef' => $oAtt->GetLabel(),
					'IsReconcKey' => MetaModel::IsReconcKey($sClass, $sAttCode),
					'Tip' => '',
				);

				$sRemoteClass = $oAtt->GetTargetClass();
				foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sExtAttCode)
				{
					$sValue = MakeExtFieldSelectValue($sAttCode, $sExtAttCode);

					// Create two entries:
					// - generic syntax (ext key label -> remote field label)
					// - if an ext field exists that corresponds to it, allow its label
					$sLabel1 = MakeExtFieldLabel($sClass, $sAttCode, $sExtAttCode);

					$bFoundTwin = false;
					foreach (MetaModel::GetExternalFields($sClass, $sAttCode) as $oExtFieldAtt)
					{
						if ($oExtFieldAtt->GetExtAttCode() == $sExtAttCode)
						{
							$aOptions[$sValue] = array(
								'LabelHtml' => htmlentities($oExtFieldAtt->GetLabel()),
								'LabelRef' => $oExtFieldAtt->GetLabel(),
								'IsReconcKey' => false,
								'Tip' => "equivalent to '".htmlentities($sLabel1)."'",
							);
							$bFoundTwin = true;
							$sLabel2 = $oExtFieldAtt->GetLabel();
							break;
						}
					}

					$aOptions[$sValue] = array(
						'LabelHtml' => htmlentities($sLabel1),
						'LabelRef' => $sLabel1,
						'IsReconcKey' => false,
						'Tip' => $bFoundTwin ? "equivalent to '".htmlentities($sLabel2)."'" : "",
					);
				}
			}
			else
			{
				$aOptions[$sAttCode] = array(
					'LabelHtml' => htmlentities($oAtt->GetLabel()),
					'LabelRef' => $oAtt->GetLabel(),
					'IsReconcKey' => MetaModel::IsReconcKey($sClass, $sAttCode),
					'Tip' => '',
				);
			}
		}

		// Find the best match
		$iMin = strlen($sFieldName);
		$sBestValue = null;
		foreach ($aOptions as $sValue => $aData)
		{
			$iDist = levenshtein(strtolower($sFieldName), strtolower($aData['LabelRef']));
			if (($iDist != -1) && ($iDist < $iMin))
			{
				$iMin = $iDist;
				$sBestValue = $sValue;
			}
		}

		$sSelField = "<select name=\"fmap[field$iFieldIndex]\">";
		foreach ($aOptions as $sValue => $aData)
		{
			$sStyle = '';
			$sComment = '';
			$sSELECTED = '';
			if ($sValue == $sBestValue)
			{
				$sSELECTED = ' SELECTED';
				if ($iMin > 0)
				{
					$sStyle = " style=\"background-color: #ffdddd;\"";
					$sComment = '- suggested';
				}
			}

			$sIsRecondKey = $aData['IsReconcKey'] ? " [rk!]" : "";
			$sSelField .= "<option value=\"$sValue\" title=\"".$aData['Tip']."\"$sStyle$sSELECTED>".$aData['LabelHtml']."$sComment$sIsRecondKey</option>\n";
		}
		$sSelField .= "</select>";
		$aFields["field$iFieldIndex"]["label"] = $sSelField; 

		$sCHECKED = ($sFieldName == "id" || MetaModel::IsReconcKey($sClass, $sFoundAttCode)) ? " CHECKED" : "";
		$aFields["field$iFieldIndex"]["label"] .= "<input type=\"checkbox\" name=\"iskey[field$iFieldIndex]\" value=\"yes\" $sCHECKED>";

		if (array_key_exists($iFieldIndex, $aColToRow))
		{
			$aFields["field$iFieldIndex"]["value"] = $aColToRow[$iFieldIndex];
		}
		else
		{
			// Houston... 		
		}
	}
	$oPage->details($aFields);
}


function ProcessData($oPage, $sClass, $oCSVParser, $aFieldMap, $aIsReconcKey, CMDBChange $oChange = null)
{
	// Note: $oChange can be null, in which case the aim is to check what would be done

	// Setup field mapping: sort out between values and other specific columns
	//
	$aReconcilKeys = array();
	$aAttList = array();
	$aExtKeys = array();
	foreach($aFieldMap as $sFieldId=>$sColDesc)
	{
		$iFieldId = (int) substr($sFieldId, strlen("field"));

		if (array_key_exists($sFieldId, $aIsReconcKey))
		{
			// This column will be used as a reconciliation key

			if (IsExtKeyField($sColDesc))
			{
				list($sAttCode, $sExtReconcKeyAttCode) = GetExtKeyFieldCodes($sColDesc);
			}
			else
			{
				$sAttCode = $sColDesc;
			}
			$aReconcilKeys[$sAttCode] = $iFieldId;
		}

		if ($sColDesc == "id")
		{
			$aAttList['id'] = $iFieldId;
		}
		elseif ($sColDesc == "__none__")
		{
			// Skip !
		}
		elseif (IsExtKeyField($sColDesc))
		{
			// This field is value to search on, to find a value for an external key
			list($sExtKeyAttCode, $sExtReconcKeyAttCode) = GetExtKeyFieldCodes($sColDesc);
			if ($sExtReconcKeyAttCode == 'id')
			{
				$aAttList[$sExtKeyAttCode] = $iFieldId;
			}
			$aExtKeys[$sExtKeyAttCode][$sExtReconcKeyAttCode] = $iFieldId;
		}
		else
		{
			// $sColDesc is an attribute code
			$aAttList[$sColDesc] = $iFieldId;
		}
	}

	// Setup result presentation
	//
	$aDisplayConfig = array();
	$aDisplayConfig["__RECONCILIATION__"] = array("label"=>"Reconciliation", "description"=>"");
	$aDisplayConfig["__STATUS__"] = array("label"=>"Import status", "description"=>"");
	if (array_key_exists('id', $aAttList))
	{
		$sPKeyCol = 'col'.$aAttList['id'];
		$aDisplayConfig[$sPKeyCol] = array("label"=>"<strong>id</strong>", "description"=>"");
	}
	foreach($aReconcilKeys as $sAttCode => $iCol)
	{
		if ($sAttCode == 'id') continue;

		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
		$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
	}
	foreach($aExtKeys as $sAttCode=>$aKeyConfig)
	{
		$oExtKeyAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLabel = $oExtKeyAtt->GetLabel();
		$aDisplayConfig[$sAttCode] = array("label"=>"$sLabel", "description"=>"");
		foreach ($aKeyConfig as $sForeignAttCode => $iCol)
		{
			// The foreign attribute is one of our reconciliation key
			
			$sLabel = MakeExtFieldLabel($sClass, $sAttCode, $sForeignAttCode);
			$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
		}
	}
	foreach ($aAttList as $sAttCode => $iCol)
	{
		if ($sAttCode != 'id')
		{
			$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
			$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
		}
	}

	// Compute the results
	//
	$aData = $oCSVParser->ToArray();

	$oBulk = new BulkChange(
		$sClass,
		$aData,
		$aAttList,
		array_keys($aReconcilKeys),
		$aExtKeys
	);
	$aRes = $oBulk->Process($oChange);
	$aResultDisp = array(); // to be displayed
	foreach($aRes as $iRow => $aRowData)
	{
		$aRowDisp = array();
		$aRowDisp["__RECONCILIATION__"] = $aRowData["__RECONCILIATION__"];
		$aRowDisp["__STATUS__"] = $aRowData["__STATUS__"]->GetDescription(true);
		foreach($aRowData as $sKey => $value)
		{
			if ($sKey == '__RECONCILIATION__') continue;
			if ($sKey == '__STATUS__') continue;
			switch (get_class($value))
			{
				case 'CellChangeSpec_Unchanged':
					$sClass = '';
					break;
				case 'CellChangeSpec_Modify':
					$sClass = 'csvimport_ok';
					break;
				case 'CellChangeSpec_Init':
					$sClass = 'csvimport_init';
					break;
				case 'CellChangeSpec_Issue':
					$sClass = 'csvimport_error';
					break;

				case 'CellChangeSpec_Void':
				default:
					$sClass = '';
			}
			if (empty($sClass))
			{
				$aRowDisp[$sKey] = $value->GetDescription(true);
			}
			else
			{
				$aRowDisp[$sKey] = "<div class=\"$sClass\">".$value->GetDescription(true)."</div>";
			}
		}
		$aResultDisp[$iRow] = $aRowDisp;
	}
	$oPage->table($aDisplayConfig, $aResultDisp);
}

///////////////////////////////////////////////////////////////////////////////
// Wizard entry points
///////////////////////////////////////////////////////////////////////////////

function Do_Welcome($oPage, $sClass)
{
	$sWiztep = "1_welcome";
	$oPage->p("<h1>Bulk load from CSV data / step 1</h1>");

	// Reload values (in case we are reaching this page from the next one
	$sCSVData = utils::ReadPostedParam('csvdata');
	$sSep = utils::ReadPostedParam('separator', ',');
	$sTQualif = utils::ReadPostedParam('textqualifier', '"');

	$aSeparators = array(',' => ', (coma)', ';' => ';', ';' => ';', '|' => '|', '#' => '#', '@' => '@', ':' => ':');
	$aTextQualifiers = array('"' => '"', "'" => "'", '`' => '`', '/' => '/');

	$oPage->add("<form method=\"post\" action=\"\">");
	$oPage->MakeClassesSelect("class", $sClass, 50, UR_ACTION_BULK_MODIFY);
	$oPage->add("<br/>");
	$oPage->add("<textarea rows=\"25\" cols=\"100\" name=\"csvdata\" wrap=\"soft\">".htmlentities($sCSVData)."</textarea>");
	$oPage->add("<br/>");
	$oPage->add("Separator: ");
	$oPage->add_select($aSeparators, 'separator', $sSep, 50);
	$oPage->add("<br/>");
	$oPage->add("Text qualifier: ");
	$oPage->add_select($aTextQualifiers, 'textqualifier', $sTQualif, 50);
	$oPage->add("<br/>");
	$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
	$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Next\"><br/>\n");
	$oPage->add("</form>");

	// As a help to the end-user, let's display the list of possible fields
	// for a class, that can be copied/pasted into the CSV area.
	$sCurrentList = "";
	$aHeadersList = array();
	foreach(MetaModel::GetClasses('bizmodel') as $sClassName)
	{
		$aList = MetaModel::GetZListItems($sClassName, 'details');
		$aHeader = array();
		// $aHeader[] = MetaModel::GetKeyLabel($sClassName);
		$aHeader[] = 'id'; // Should be what's coded on the line above... but there is a bug
		foreach($aList as $sAttCode)
		{
			$aHeader[] = MetaModel::GetLabel($sClassName, $sAttCode);
		}
		
		$sAttributes = implode(",", $aHeader);
		$aHeadersList[$sClassName] = $sAttributes; 
		
		if($sClassName == $sClass)
		{
			// this class is currently selected
			$sCurrentList = $sAttributes;
		}
	}
	// Store all the values in a variable client-side
	$aScript = array();
	foreach($aHeadersList as $sClassName => $sAttributes)
	{
		$aScript[] = "'$sClassName':'$sAttributes'";
	}
	$oPage->add("<script>
	var oAttributes = {".implode(',', $aScript)."}; 
	function DisplayFields(className)
	{
		$('#fields').val(oAttributes[className]);
	}
	</script>\n");
	
	$oPage->add_ready_script("$('#select_class').change( function() {DisplayFields(this.value);} );");
	$oPage->add("<br/>");
	$oPage->add("Fields for this object<br/><textarea readonly id=fields rows=\"3\" cols=\"100\" wrap=\"soft\">$sCurrentList</textarea>");

}

function Do_Format($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 2</h1>");
	$sWiztep = "2_format";

	$sCSVData = utils::ReadPostedParam('csvdata');
	$sSep = utils::ReadPostedParam('separator');
	$sTQualif = utils::ReadPostedParam('textqualifier');
	$oCSVParser = new CSVParser($sCSVData, $sSep, $sTQualif); 
	$iSkip = 1;

	// No data ?
	$aData = $oCSVParser->ToArray();
	$iTarget = count($aData);
	if ($iTarget == 0)
	{
	   $oPage->p("Empty data set..., please provide some data!");
		$oPage->add("<button onClick=\"window.history.back();\">Back</button>\n");
		return;
	}

	// Expected format - to be improved
	$oPage->p("Separator: '<strong>$sSep</strong>'");
	$oPage->p("Text qualifier: '<strong>$sTQualif</strong>'");
	$oPage->p("The first line will be skipped (considered as being the list of fields)");

	$oPage->p("Target: $iTarget rows");

	$oPage->add("<form method=\"post\" action=\"\">");
	ShowTableForm($oPage, $oCSVParser, $sClass);
	$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
	$oPage->add("<input type=\"hidden\" name=\"csvdata\" value=\"".htmlentities($sCSVData)."\">");
	$oPage->add("<input type=\"hidden\" name=\"separator\" value=\"".htmlentities($sSep)."\">");
	$oPage->add("<input type=\"hidden\" name=\"textqualifier\" value=\"".htmlentities($sTQualif)."\">");
	$oPage->add("<input type=\"hidden\" name=\"skiplines\" value=\"$iSkip\">");

	$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
	$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
	$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Next\">");
	$oPage->add("</form>");
}

function DoProcessOrVerify($oPage, $sClass, CMDBChange $oChange = null)
{
	$sCSVData = utils::ReadPostedParam('csvdata'); 
	$sSep = utils::ReadPostedParam('separator');
	$sTQualif = utils::ReadPostedParam('textqualifier');
	$iSkip = utils::ReadPostedParam('skiplines'); 
	$aFieldMap = utils::ReadPostedParam('fmap');
	$aIsReconcKey = utils::ReadPostedParam('iskey');

	if (empty($aIsReconcKey))
	{
		$oPage->p("Error: no reconciliation key has been specified. Please specify which field(s) will be used to identify the object");

		$oPage->add("<button onClick=\"window.history.back();\">Back</button>\n");
		$oPage->add("<button disabled>Next</button>\n");
		return;
	}

	$oCSVParser = new CSVParser($sCSVData, $sSep, $sTQualif);
	$aData = $oCSVParser->ToArray($iSkip, null);
	$iTarget = count($aData);

	$oPage->p("<h2>Goal summary</h2>");
	$oPage->p("Target: $iTarget rows");

	$aSampleData = $oCSVParser->ToArray($iSkip, array_keys($aFieldMap), 5);

	$aDisplayConfig = array();
	$aExtKeys = array();
	foreach ($aFieldMap as $sFieldId=>$sColDesc)
	{
		if (array_key_exists($sFieldId, $aIsReconcKey))
		{
			$sReconcKey = " <br/><span title=\"the value found in this column will be used as a search condition for the reconciliation\" style=\"background-color: #aaaa00; color: #dddddd;\">[key]</span>";
		}
		else
		{
			$sReconcKey = "";
		}

		if ($sColDesc == "id")
		{
			$aDisplayConfig[$sFieldId] = array("label"=>"Private key $sReconcKey", "description"=>"");
		}
		elseif ($sColDesc == "__none__")
		{
			// Skip !
		}
		else if (MetaModel::IsValidAttCode($sClass, $sColDesc))
		{
			$sAttCode = $sColDesc;
			$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
			$aDisplayConfig[$sFieldId] = array("label"=>"$sLabel$sReconcKey", "description"=>"");
			if (MetaModel::IsValidKeyAttCode($sClass, $sAttCode))
			{
				$aExtKeys[] = $sAttCode;
			}
		}
		elseif (IsExtKeyField($sColDesc))
		{
			list($sExtKeyAttCode, $sForeignAttCode) = GetExtKeyFieldCodes($sColDesc);
			$sLabel = MakeExtFieldLabel($sClass, $sExtKeyAttCode, $sForeignAttCode);
			$aDisplayConfig[$sFieldId] = array("label"=>"$sLabel$sReconcKey", "description"=>"");
			$aExtKeys[] = $sExtKeyAttCode;
		}
		else
		{
			// ???
			$aDisplayConfig[$sFieldId] = array("label"=>"-?-?-$sColDesc-?-?-", "description"=>"");
		}
	}
	
	$oPage->table($aDisplayConfig, $aSampleData);

	if ($oChange)
	{
		$oPage->p("<h2>Processing...</h2>");
	}
	else
	{
		$oPage->p("<h2>Column consistency</h2>");
		$aMissingKeys = array();
		foreach (MetaModel::GetExternalKeys($sClass) as $sExtKeyAttCode => $oExtKey)
		{
			if (!in_array($sExtKeyAttCode, $aExtKeys) && !$oExtKey->IsNullAllowed())
			{
				$aMissingKeys[$sExtKeyAttCode] = $oExtKey;
			}
		}
		if (count($aMissingKeys) > 0)
		{
			$oPage->p("Warning: the objects could not be created, due to some missing mandatory external keys in the field list: ");
			$oPage->add("<ul>");
			foreach($aMissingKeys as $sAttCode => $oAttDef)
			{
				$oPage->add("<li>".$oAttDef->GetLabel()."</li>");
			}
			$oPage->add("</ul>");
		}
		else
		{
			$oPage->p("ok - required external keys (if any) have been found in the field list");
		}
		$oPage->p("Note: the procedure will fail if any line has not the same number of columns as the first line");

		$oPage->p("<h2>Check...</h2>");
	}
	ProcessData($oPage, $sClass, $oCSVParser, $aFieldMap, $aIsReconcKey, $oChange);

	$oPage->add("<form method=\"post\" action=\"\">");
	$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
	$oPage->add("<input type=\"hidden\" name=\"csvdata\" value=\"".htmlentities($sCSVData)."\">");
	$oPage->add("<input type=\"hidden\" name=\"separator\" value=\"".htmlentities($sSep)."\">");
	$oPage->add("<input type=\"hidden\" name=\"textqualifier\" value=\"".htmlentities($sTQualif)."\">");
	$oPage->add("<input type=\"hidden\" name=\"skiplines\" value=\"$iSkip\">");
	$oPage->add_input_hidden("fmap", $aFieldMap);
	$oPage->add_input_hidden("iskey", $aIsReconcKey);

	return true;
}

function Do_Verify($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 3</h1>");
	$sWiztep = "3_verify";

	if (DoProcessOrVerify($oPage, $sClass, null))
	{
		// FORM started by DoProcessOrVerify...
		$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
		$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
		$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Next\">");
		$oPage->add("</form>");
	}
}

function Do_Execute($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 4</h1>");
	$sWiztep = "4_execute";

	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	$iUser = UserRights::GetContactId();
	if ($iUser != null)
	{
		// Ok, that's dirty, I admit :-)
		$oUser = MetaModel::GetObject('bizContact', $iUser);
		$sUser = $oUser->GetName();
		$oMyChange->Set("userinfo", "CSV Import, by ".$sUser);
	}
	else
	{
		$oMyChange->Set("userinfo", "CSV Import");
	}
	$iChangeId = $oMyChange->DBInsert();
	
	if (DoProcessOrVerify($oPage, $sClass, $oMyChange))
	{
		// FORM started by DoProcessOrVerify...
		$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
		$oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
		$oPage->add("</form>");
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////
//
//  M a i n   P r o g r a m
//
///////////////////////////////////////////////////////////////////////////////////////////////////

$sFromWiztep = utils::ReadPostedParam('fromwiztep', '');
$sClass = utils::ReadPostedParam('class', '');
$sTodo = utils::ReadPostedParam('todo', ''); 

switch($sFromWiztep)
{
	case '':
		Do_Welcome($oPage, $sClass);
        break;

    case '1_welcome':
		if ($sTodo == "Next")	Do_Format($oPage, $sClass);
		else					trigger_error("Wrong argument todo='$sTodo'", E_USER_ERROR);
		break;

    case '2_format':
		if ($sTodo == "Next")	Do_Verify($oPage, $sClass);
		else					Do_Welcome($oPage, $sClass);
		break;

    case '3_verify':
		if ($sTodo == "Next")	Do_Execute($oPage, $sClass);
		else					Do_Format($oPage, $sClass);
		break;

    case '4_execute':
		if ($sTodo == "Next")	trigger_error("Wrong argument todo='$sTodo'", E_USER_ERROR);
		else					Do_Verify($oPage, $sClass);
		break;

    default:
    	trigger_error("Wrong argument fromwiztep='$sFromWiztep'", E_USER_ERROR);
}

$oPage->output();
?>

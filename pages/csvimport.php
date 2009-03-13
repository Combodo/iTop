<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);

$oPage = new iTopWebPage("iTop - Bulk import", $currentOrganization);

define ('EXTKEY_SEP', '::::');
define ('EXTKEY_LABELSEP', ' -> ');

class CSVParser
{
	private $m_sCSVData;
	private $m_sSep;
	private $m_iSkip;

	public function __construct($sTxt)
	{
		$this->m_sCSVData = $sTxt;
	}

	public function SetSeparator($sSep)
	{
		$this->m_sSep = $sSep;
	}
	public function GetSeparator()
	{
		return $this->m_sSep;
	}

	public function SetSkipLines($iSkip)
	{
		$this->m_iSkip = $iSkip;
	}
	public function GetSkipLines()
	{
		return $this->m_iSkip;
	}

	public function GuessSeparator()
	{
		// Note: skip the first line anyway
	
		$aKnownSeps = array(';', ',', "\t"); // Use double quote for special chars!!!
		$aStatsBySeparator = array();
		foreach ($aKnownSeps as $sSep)
		{
			$aStatsBySeparator[$sSep] = array();
		}
	
		foreach(split("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			$aLineCharsCount = count_chars($sLine, 0);
			foreach ($aKnownSeps as $sSep)
			{
				$aStatsBySeparator[$sSep][] = $aLineCharsCount[ord($sSep)];
			}
		}
	
		// Default to ','
		$this->SetSeparator(",");

		foreach ($aKnownSeps as $sSep)
		{
			// Note: this function is NOT available :-( 
			// stats_variance($aStatsBySeparator[$sSep]);
			$iMin = min($aStatsBySeparator[$sSep]);
			$iMax = max($aStatsBySeparator[$sSep]);
			if (($iMin == $iMax) && ($iMax > 0))
			{
				$this->SetSeparator($sSep);
				break;
			}
		}
		return $this->GetSeparator();
	}

	public function GuessSkipLines()
	{
		// Take the FIRST -valuable- LINE ONLY
		// If there is a number, then for sure this is not a header line
		// Otherwise, we may consider that there is one line to skip
		foreach(split("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			foreach (split($this->m_sSep, $sLine) as $value)
			{
				if (is_numeric($value))
				{
					$this->SetSkipLines(0);
					return 0;
				}
			}
			$this->SetSkipLines(1);
			return 1;
		}
	}

	function ToArray($aFieldMap, $iMax = 0)
	{
		// $aFieldMap is an array of col_index=>col_name
		// $iMax is a limit
		$aRes = array();
	
		$iCount = 0;
		$iSkipped = 0;
		foreach(split("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
	
			if ($iSkipped < $this->m_iSkip)
			{
				$iSkipped++;
				continue;
			}
	
			foreach (split($this->m_sSep, $sLine) as $iCol=>$sValue)
			{
				if (is_array($aFieldMap)) $sColRef = $aFieldMap[$iCol];
				else                      $sColRef = "field$iCol";
				$aRes[$iCount][$sColRef] = $sValue;
			}
	
			$iCount++;
			if (($iMax > 0) && ($iCount >= $iMax)) break;
		}
		return $aRes;
	}

	public function ListFields()
	{
		// Take the first valuable line
		foreach(split("\n", $this->m_sCSVData) as $sLine)
		{
			$sLine = trim($sLine);
			if (substr($sLine, 0, 1) == '#') continue;
			if (empty($sLine)) continue;
			// We've got the first valuable line, that's it!
			break;
		}

		$aRet = array();
		foreach (split($this->m_sSep, $sLine) as $iCol=>$value)
		{
			if ($this->m_iSkip == 0)
			{
				// No header to help us
				$sLabel = "field $iCol";
			}
			else
			{
				$sLabel = "$value";
			}
			$aRet[] = $sLabel;
		}
		return $aRet;
	}
}


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
	$oForeignAtt = MetaModel::GetAttributeDef($oExtKeyAtt->GetTargetClass(), $sForeignAttCode);
	
	return $oExtKeyAtt->GetLabel().EXTKEY_LABELSEP.$oForeignAtt->GetLabel();
}

function MakeExtFieldSelectValue($sAttCode, $sExtAttCode)
{
	return $sAttCode.EXTKEY_SEP.$sExtAttCode;
}

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


function ShowTableForm($oPage, $oCSVParser, $sClass)
{
	$aData = $oCSVParser->ToArray(null, 3);
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
		$sSelField = "<select name=\"fmap[field$iFieldIndex]\">";
		$sSelField .= "<option value=\"__none__\">None (ignore)</option>";
		$sSELECTED = (strcasecmp($sFieldName, "pkey") == 0) ? " SELECTED" : "";
		$sSelField .= "<option value=\"pkey\"$sSELECTED>Private Key</option>";
		$sFoundAttCode = ""; // quick and dirty way to remind if a match has been found and suggest a reconciliation key if possible
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAtt)
		{
			if ($oAtt->IsExternalField()) continue;

			$bIsThatField = (strcasecmp($sFieldName, $oAtt->GetLabel()) == 0);
			//$sIsReconcKey = (MetaModel::IsValidFilterCode($sClass, $sAttCode)) ? " [key]" : "";
			$sIsReconcKey = MetaModel::IsReconcKey($sClass, $sAttCode) ? " [rk!]" : "";
			$sFoundAttCode = (MetaModel::IsValidFilterCode($sClass, $sAttCode) && $bIsThatField) ? $sAttCode : $sFoundAttCode; 
			$sSELECTED = $bIsThatField ? " SELECTED" : "";

			if ($oAtt->IsExternalKey())
			{
				// An external key might be loaded by
				// the pkey or a reconciliation key
				//
				$sSelField .= "<option value=\"$sAttCode\"$sSELECTED><em>".$oAtt->GetLabel()."</em> (pkey)$sIsReconcKey</option>";

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
							$sLabel2 = $oExtFieldAtt->GetLabel();

							$bIsThatField = (strcasecmp($sFieldName, $sLabel2) == 0);
							$sSELECTED = $bIsThatField ? " SELECTED" : "";
							$sTITLE = " title=\"equivalent to '".htmlentities($sLabel1)."'\"";
							$sSelField .= "<option value=\"$sValue\"$sTITLE $sSELECTED>".htmlentities($sLabel2)."</option>";
							$bFoundTwin = true;
							break;
						}
					}

					$bIsThatField = (strcasecmp($sFieldName, $sLabel1) == 0);
					$sSELECTED = $bIsThatField ? " SELECTED" : "";
					$sTITLE = $bFoundTwin ? " title=\"equivalent to '".htmlentities($sLabel2)."'\"" : "";
					$sSelField .= "<option value=\"$sValue\"$sTITLE $sSELECTED>".htmlentities($sLabel1)."</option>";
				}
			}
			else
			{
				$sSelField .= "<option value=\"$sAttCode\"$sSELECTED>".$oAtt->GetLabel()."$sIsReconcKey</option>";
			}
		}
		$sSelField .= "</select>";
		
		$sCHECKED = ($sFieldName == "pkey" || MetaModel::IsReconcKey($sClass, $sFoundAttCode)) ? " CHECKED" : "";
		$sSelField .= "&nbsp;<input type=\"checkbox\" name=\"iskey[field$iFieldIndex]\" value=\"yes\" $sCHECKED>";

		$aFields["field$iFieldIndex"]["label"] = $sSelField;
		$aFields["field$iFieldIndex"]["value"] = $aColToRow["field$iFieldIndex"];
	}
	$oPage->details($aFields);
}

function PrepareObject(&$oTargetObj, $aRowData, $aAttList, $aExtKeys, &$aWarnings, &$aErrors)
{
	$aResults = array();
	$aWarnings = array();
	$aErrors = array();

	// External keys reconciliation
	//
	foreach($aExtKeys as $sAttCode=>$aKeyConfig)
	{
		$oExtKey = MetaModel::GetAttributeDef(get_class($oTargetObj), $sAttCode);
		$oReconFilter = new CMDBSearchFilter($oExtKey->GetTargetClass());
		foreach ($aKeyConfig as $iCol => $sForeignAttCode)
		{
			// The foreign attribute is one of our reconciliation key
			$sFieldId = MakeExtFieldSelectValue($sAttCode, $sForeignAttCode);
			$oReconFilter->AddCondition($sForeignAttCode, $aRowData[$sFieldId], '=');
			$aResults["col$iCol"]= "<div class=\"csvimport_extreconkey\">".$aRowData[$sFieldId]."</div>";
		}
		$oExtObjects = new CMDBObjectSet($oReconFilter);
		switch($oExtObjects->Count())
		{
		case 0:
			$aErrors[$sAttCode] = "Object not found";
			$aResults[$sAttCode]= "<div class=\"csvimport_error\">".$aErrors[$sAttCode]."</div>";
			break;
		case 1:
			// Do change the external key attribute
			$oForeignObj = $oExtObjects->Fetch();
			$oTargetObj->Set($sAttCode, $oForeignObj->GetKey());

			// Report it
			if (array_key_exists($sAttCode, $oTargetObj->ListChanges()))
			{
				$aResults[$sAttCode]= "<div class=\"csvimport_ok\">".$oForeignObj->GetHyperLink()."</div>";
			}
			else
			{
				$aResults[$sAttCode]= "<div class=\"\">".$oForeignObj->GetHyperLink()."</div>";
			}
			break;
		default:
			$aErrors[$sAttCode] = "Found ".$oExtObjects->Count()." matches";
			$aResults[$sAttCode]= "<div class=\"csvimport_error\">".$aErrors[$sAttCode]."</div>";
		}
	}	

	// Set the object attributes
	//
	foreach ($aAttList as $iCol => $sAttCode)
	{
		$oTargetObj->Set($sAttCode, $aRowData[$sAttCode]);
	}

	// Reporting on fields
	//
	$aChangedFields = $oTargetObj->ListChanges();
	foreach ($aAttList as $iCol => $sAttCode)
	{
		// By default... nothing happens
		$sClass = "";
		$sMoreInfo = "";

		// Override if the attribute has changed
		if (array_key_exists($sAttCode, $aChangedFields))
		{
			$sClass = "csvimport_ok";
		}

		// Override if a warning is found
		if (isset($aWarnings[$sAttCode]))
		{
			$sClass = "csvimport_warning";
			$sMoreInfo .= ", ".$aWarnings[$sAttCode];
		}

		// Override if an error is found
		if (isset($aErrors[$sAttCode]))
		{
			$sClass = "csvimport_error";
			$sMoreInfo = ", ".$aErrors[$sAttCode];
		}

		$aResults["col$iCol"]= "<div class=\"$sClass\">".$aRowData[$sAttCode].$sMoreInfo."</div>";
	}

	// Checks
	//
	if (!$oTargetObj->CheckConsistency())
	{
		$aErrors["GLOBAL"] = "Attributes not consistent with each others";
	}
	return $aResults;
}


function CreateObject(&$aResult, $iRow, $sClass, $aRowData, $aAttList, $aExtKeys, CMDBChange $oChange = null)
{
	$oTargetObj = MetaModel::NewObject($sClass);
	$aResult[$iRow] = PrepareObject($oTargetObj, $aRowData, $aAttList, $aExtKeys, $aWarnings, $aErrors);

	if (count($aErrors) > 0)
	{
		$sErrors = implode(', ', $aErrors);
		$aResult[$iRow]["__STATUS__"] = "Unexpected attribute value(s)";
		return;
	}

	// Check that any external key will have a value proposed
	// Could be said once for all rows !!!
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAtt)
	{
		if (!$oAtt->IsExternalKey()) continue;
		//if (!in_array($sAttCode, $aAttList))
		//{
		//	$aResult[$iRow]["__STATUS__"] = "Could not be created - Missing external key (".$oAtt->GetLabel().")";
		//	return;
		//}
	}

	// Optionaly record the results
	//
	if ($oChange)
	{
		$newID = $oTargetObj->DBInsertTracked($oChange);
		$aResult[$iRow]["__STATUS__"] = "Created: ".$oTargetObj->GetHyperLink($newID);
	}
	else
	{
		$aResult[$iRow]["__STATUS__"] = "Create";
	}

}

function UpdateObject(&$aResult, $iRow, $oTargetObj, $aRowData, $aAttList, $aExtKeys, CMDBChange $oChange = null)
{
	$aResult[$iRow] = PrepareObject($oTargetObj, $aRowData, $aAttList, $aExtKeys, $aWarnings, $aErrors);

	// Reporting
	//
	if (count($aErrors) > 0)
	{
		$sErrors = implode(', ', $aErrors);
		$aResult[$iRow]["__STATUS__"] = "Unexpected attribute value(s)";
		return;
	}

	$aChangedFields = $oTargetObj->ListChanges();
	if (count($aChangedFields) > 0)
	{
		$sVerb = $oChange ? "Updated" : "Update";
		$aResult[$iRow]["__STATUS__"] = "$sVerb ".count($aChangedFields)." cols";

		// Optionaly record the results
		//
		if ($oChange)
		{
			$oTargetObj->DBUpdateTracked($oChange);
		}
	}
	else
	{
		$aResult[$iRow]["__STATUS__"] = "No change";
	}
}

function ProcessData($oPage, $sClass, $oCSVParser, $aFieldMap, $aIsReconcKey, CMDBChange $oChange = null)
{
	// Note: $oChange can be null, in which case the aim is to check what would be done

	// Setup field mapping: sort out between values and other specific columns
	//
	$iPKeyId = null;
	$aReconcilKeys = array();
	$aAttList = array();
	$aExtKeys = array();
	foreach($aFieldMap as $sFieldId=>$sColDesc)
	{
		$iFieldId = (int) substr($sFieldId, strlen("field"));
		if ($sColDesc == "pkey")
		{
			// Skip !
			$iPKeyId = $iFieldId;
		}
		elseif ($sColDesc == "__none__")
		{
			// Skip !
		}
		elseif (IsExtKeyField($sColDesc))
		{
			list($sExtKeyAttCode, $sExtReconcKeyAttCode) = GetExtKeyFieldCodes($sColDesc);
			$aExtKeys[$sExtKeyAttCode][$iFieldId] = $sExtReconcKeyAttCode;
		}
		elseif (array_key_exists($sFieldId, $aIsReconcKey))
		{
			$aReconcilKeys[$iFieldId] = $sColDesc;
			$aAttList[$iFieldId] = $sColDesc; // A reconciliation key is also a field
		}
		else
		{
			// $sColDesc is an attribute code
			//
			$aAttList[$iFieldId] = $sColDesc;
		}
	}

	// Setup result presentation
	//
	$aDisplayConfig = array();
	$aDisplayConfig["__RECONCILIATION__"] = array("label"=>"Reconciliation", "description"=>"");
	$aDisplayConfig["__STATUS__"] = array("label"=>"Status", "description"=>"");
	if (isset($iPKeyId))
	{
		$aDisplayConfig["col$iPKeyId"] = array("label"=>"<strong>pkey</strong>", "description"=>"");
	}
	foreach($aReconcilKeys as $iCol=>$sAttCode)
	{
		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
		$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
	}
	foreach($aExtKeys as $sAttCode=>$aKeyConfig)
	{
		$oExtKeyAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLabel = $oExtKeyAtt->GetLabel();
		$aDisplayConfig[$sAttCode] = array("label"=>"$sLabel", "description"=>"");
		foreach ($aKeyConfig as $iCol => $sForeignAttCode)
		{
			// The foreign attribute is one of our reconciliation key
			
			$sLabel = MakeExtFieldLabel($sClass, $sAttCode, $sForeignAttCode);
			$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
		}
	}
	foreach ($aAttList as $iCol => $sAttCode)
	{
		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
		$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
	}

	// Compute the results
	//
	$aResult = array();
	foreach($oCSVParser->ToArray(array_values($aFieldMap)) as $iRow => $aRowData)
	{
		$oReconciliationFilter = new CMDBSearchFilter($sClass);
		if (isset($iPKeyId))
		{
			$oReconciliationFilter->AddCondition("pkey", $aRowData["pkey"], '=');
		}
		foreach($aReconcilKeys as $iCol=>$sAttCode)
		{
			$sSearchAttCode = $aFieldMap['field'.$iCol];
			$oReconciliationFilter->AddCondition($sSearchAttCode, $aRowData[$sSearchAttCode], '=');
		}
		$oReconciliationSet = new CMDBObjectSet($oReconciliationFilter);
		switch($oReconciliationSet->Count())
		{
		case 0:
			CreateObject($aResult, $iRow, $sClass, $aRowData, $aAttList, $aExtKeys, $oChange);
			// $aResult[$iRow]["__STATUS__"]=> set in CreateObject
			$aResult[$iRow]["__RECONCILIATION__"] = "Object not found";
			break;
		case 1:
			$oTargetObj = $oReconciliationSet->Fetch();
			UpdateObject($aResult, $iRow, $oTargetObj, $aRowData, $aAttList, $aExtKeys, $oChange);
			$aResult[$iRow]["__RECONCILIATION__"] = "Found a ".$oTargetObj->GetHyperLink("match");
			// $aResult[$iRow]["__STATUS__"]=> set in UpdateObject
			break;
		default:
			foreach ($aAttList as $iCol => $sAttCode)
			{
				$aResult[$iRow]["col$iCol"]= $aRowData[$sAttCode];
			}
			$aResult[$iRow]["__RECONCILIATION__"] = "Found ".$oReconciliationSet->Count()." matches";
			$aResult[$iRow]["__STATUS__"]= "skipped";
		}

		// Whatever happened, do report the reconciliation values
		if (isset($iPKeyId))
		{
			$aResult[$iRow]["col$iPKeyId"] = "<div class=\"csvimport_reconkey\">".$aRowData["pkey"]."</div>";
		}
		foreach($aReconcilKeys as $iCol=>$sAttCode)
		{
			$aResult[$iRow]["col$iCol"] = "<div class=\"csvimport_reconkey\">".$aRowData[$sAttCode]."</div>";
		}
	}
	$oPage->table($aDisplayConfig, $aResult);
}

///////////////////////////////////////////////////////////////////////////////
// Wizard entry points
///////////////////////////////////////////////////////////////////////////////

function Do_Welcome($oPage, $sClass)
{
	$sWiztep = "1_welcome";
	$oPage->p("<h1>Bulk load from CSV data / step 1</h1>");

	$sCSVData = utils::ReadPostedParam('csvdata');

    $oPage->add("<form method=\"post\" action=\"\">");
    $oPage->MakeClassesSelect("class", $sClass, 50);
    //$oPage->Add("<input type=\"text\" size=\"40\" name=\"initialsituation\" value=\"\">");
    $oPage->add("</br>");
    $oPage->add("<textarea rows=\"25\" cols=\"80\" name=\"csvdata\" wrap=\"soft\">$sCSVData</textarea>");
    $oPage->add("</br>");
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
		$aHeader[] = 'pkey'; // Should be what's coded on the line above... but there is a bug
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
	
    $oPage->add("Fields for this object: <textarea readonly id=fields rows=\"3\" cols=\"60\" wrap=\"soft\">$sCurrentList</textarea>");

}

function Do_Format($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 2</h1>");
	$sWiztep = "2_format";

	$sCSVData = utils::ReadPostedParam('csvdata');
	$oCSVParser = new CSVParser($sCSVData); 
	$sSep = $oCSVParser->GuessSeparator();
	$iSkip = $oCSVParser->GuessSkipLines();

	// No data ?
	$aData = $oCSVParser->ToArray(null);
	$iTarget = count($aData);
	if ($iTarget == 0)
	{
	    $oPage->add("Empty data set...");
	    $oPage->add("<form method=\"post\" action=\"\">");
	    $oPage->add("<input type=\"hidden\" name=\"csvdata\" value=\"$sCSVData\">");
	    $oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
	    $oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
		$oPage->add("</form>");
	}

	// Guess the format :
	$oPage->p("Guessed separator: '<strong>$sSep</strong>' (ASCII=".ord($sSep).")");
	$oPage->p("Guessed # of lines to skip: $iSkip");

	$oPage->p("Target: $iTarget rows");

    $oPage->Add("<form method=\"post\" action=\"\">");
	ShowTableForm($oPage, $oCSVParser, $sClass);
    $oPage->Add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
    $oPage->Add("<input type=\"hidden\" name=\"csvdata\" value=\"$sCSVData\">");
    $oPage->Add("<input type=\"hidden\" name=\"separator\" value=\"$sSep\">");
    $oPage->Add("<input type=\"hidden\" name=\"skiplines\" value=\"$iSkip\">");

    $oPage->Add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
    $oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
    $oPage->Add("<input type=\"submit\" name=\"todo\" value=\"Next\">");
	$oPage->Add("</form>");
}

function DoProcessOrVerify($oPage, $sClass, CMDBChange $oChange = null)
{
	$sCSVData = utils::ReadPostedParam('csvdata'); 
	$sSep = utils::ReadPostedParam('separator');
	$iSkip = utils::ReadPostedParam('skiplines'); 
	$aFieldMap = utils::ReadPostedParam('fmap');
	$aIsReconcKey = utils::ReadPostedParam('iskey');

	$oCSVParser = new CSVParser($sCSVData);
	$oCSVParser->SetSeparator($sSep);
	$oCSVParser->SetSkipLines($iSkip);
	$aData = $oCSVParser->ToArray(null);
	$iTarget = count($aData);

	$oPage->p("<h2>Goal summary</h2>");
	$oPage->p("Target: $iTarget rows");

	$aSampleData = $oCSVParser->ToArray(null, 5);
	$aDisplayConfig = array();
	foreach ($aFieldMap as $sFieldId=>$sColDesc)
	{
		if (array_key_exists($sFieldId, $aIsReconcKey))
		{
			$sReconcKey = " [search]";
		}
		else
		{
			$sReconcKey = "";
		}

		if ($sColDesc == "pkey")
		{
			$aDisplayConfig[$sFieldId] = array("label"=>"Private key $sReconcKey", "description"=>"blah pkey");
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
		}
		elseif (IsExtKeyField($sColDesc))
		{
			list($sExtKeyAttCode, $sForeignAttCode) = GetExtKeyFieldCodes($sColDesc);
			$aDisplayConfig[$sFieldId] = array("label"=>MakeExtFieldLabel($sClass, $sExtKeyAttCode, $sForeignAttCode), "description"=>"");
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
		$oPage->p("<h2>Check...</h2>");
	}
	ProcessData($oPage, $sClass, $oCSVParser, $aFieldMap, $aIsReconcKey, $oChange);

    $oPage->add("<form method=\"post\" action=\"\">");
    $oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">");
    $oPage->add("<input type=\"hidden\" name=\"csvdata\" value=\"$sCSVData\">");
    $oPage->add("<input type=\"hidden\" name=\"separator\" value=\"$sSep\">");
    $oPage->add("<input type=\"hidden\" name=\"skiplines\" value=\"$iSkip\">");
	$oPage->add_input_hidden("fmap", $aFieldMap);
	$oPage->add_input_hidden("iskey", $aIsReconcKey);
}

function Do_Verify($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 3</h1>");
	$sWiztep = "3_verify";

	DoProcessOrVerify($oPage, $sClass, null);
	// FORM started by DoProcessOrVerify...

	$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
    $oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
    $oPage->add("<input type=\"submit\" name=\"todo\" value=\"Next\">");
	$oPage->add("</form>");
}

function Do_Execute($oPage, $sClass)
{
	$oPage->p("<h1>Bulk load from CSV data / step 4</h1>");
	$sWiztep = "4_execute";

	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	$oMyChange->Set("userinfo", "CSV Import");
	$iChangeId = $oMyChange->DBInsert();
	
	DoProcessOrVerify($oPage, $sClass, $oMyChange);

	// FORM started by DoProcessOrVerify...
	$oPage->add("<input type=\"hidden\" name=\"fromwiztep\" value=\"$sWiztep\">");
    $oPage->add("<input type=\"submit\" name=\"todo\" value=\"Back\">");
	$oPage->add("</form>");
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

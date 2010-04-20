<?php

//
// ITop consultant home page
//    tool box
//    object model analysis
//    DB integrity check and repair
//



function sexyclass($sClass, $sBaseArgs)
{
	return "Class <a href=\"?$sBaseArgs&todo=showclass&class=$sClass\">$sClass</a>";
}

function sexyclasslist($aClasses, $sBaseArgs)
{
	if (count($aClasses) == 0) return "";

	$aRes = array();
	foreach($aClasses as $sClass)
	{
		$aRes[] = sexyclass($sClass, $sBaseArgs);
	}
	return ("'".implode("', '", $aRes)."'");
}


function ShowClass($sClass, $sBaseArgs)
{
	if (!MetaModel::IsValidClass($sClass))
	{
		echo "Invalid class, expecting a value in {".sexyclasslist(MetaModel::GetClasses(), $sBaseArgs)."}<br/>\n";
		return;
	}
// en recursif jusque "":	MetaModel::GetParentPersistentClass($sClass)

	$aProps["Root class"] = MetaModel::GetRootClass($sClass);
	$aProps["Parent classes"] = sexyclasslist(MetaModel::EnumParentClasses($sClass), $sBaseArgs);
	$aProps["Child classes"] = sexyclasslist(MetaModel::EnumChildClasses($sClass), $sBaseArgs);
	$aProps["Subclasses (children + pure PHP)"] = sexyclasslist(MetaModel::GetSubclasses($sClass), $sBaseArgs);

	$aProps["Description"] = MetaModel::GetClassDescription($sClass);
	$aProps["Autoincrement id?"] = MetaModel::IsAutoIncrementKey($sClass);
	$aProps["Key label"] = MetaModel::GetKeyLabel($sClass);
	$aProps["Name attribute"] = MetaModel::GetNameAttributeCode($sClass);
	$aProps["Reconciliation keys"] = implode(", ", MetaModel::GetReconcKeys($sClass));
	$aProps["DB key column"] = MetaModel::DBGetKey($sClass);
	$aProps["DB class column"] = MetaModel::DBGetClassField($sClass);
	$aProps["Is standalone?"] = MetaModel::IsStandaloneClass($sClass);

	foreach (MetaModel::ListAttributeDefs($sClass) as $oAttDef)
	{
		$aAttProps = array();
		$aAttProps["Direct field"] = $oAttDef->IsDirectField(); 
		$aAttProps["External key"] = $oAttDef->IsExternalKey(); 
		$aAttProps["External field"] = $oAttDef->IsExternalField(); 
		$aAttProps["Link set"] = $oAttDef->IsLinkSet(); 
		$aAttProps["Code"] = $oAttDef->GetCode(); 
		$aAttProps["Label"] = $oAttDef->GetLabel(); 
		$aAttProps["Description"] = $oAttDef->GetDescription();

		$oValDef = $oAttDef->GetValuesDef();
		if (is_object($oValDef))
		{
			//$aAttProps["Allowed values"] = $oValDef->Describe();
			$aAttProps["Allowed values"] = "... object of class ".get_class($oValDef);
		}
		else
		{
			$aAttProps["Allowed values"] = "";
		}

		// MetaModel::IsAttributeInZList($sClass, $sListCode, $sAttCodeOrFltCode)
	}

//	$aProps["Description"] = MetaModel::DBGetTable($sClass, $sAttCode = null)

	$aAttributes = array();
	foreach (MetaModel::GetClassFilterDefs($sClass) as $oFilterDef)
	{
		$aAttProps = array();
		$aAttProps["Label"] = $oFilterDef->GetLabel();
		$aOpDescs = array();
		foreach ($oFilterDef->GetOperators() as $sOpCode => $sOpDescription)
		{
			$sIsTheLooser = ($sOpCode == $oFilterDef->GetLooseOperator()) ? " (loose search)" : "";
			$aOpDescs[] = "$sOpCode ($sOpDescription)$sIsTheLooser";
		}
		$aAttProps["Operators"] = implode(" / ", $aOpDescs);
		$aAttributes[] = $aAttProps; 
	}
	$aProps["Filters"] = MyHelpers::make_table_from_assoc_array($aAttributes);

	foreach ($aProps as $sKey => $sDesc)
	{
		echo "<h4>$sKey</h4>\n";
		echo "<p>$sDesc</p>\n";
	}
}


function ShowBizModel($sBaseArgs)
{
	echo "<ul>\n";
	foreach(MetaModel::GetClasses() as $sClass)
	{
		echo "<li>".sexyclass($sClass, $sBaseArgs)."</li>\n";
	}
	echo "</ul>\n";
}


function ShowZLists($sBaseArgs)
{
	$aData = array();

	// 1 row per class, header made after the first row keys
	//
	foreach(MetaModel::GetClasses() as $sClass)
	{
		$aRow = array();
		$aRow["_"] = $sClass;
		foreach (MetaModel::EnumZLists() as $sListCode)
		{
			$aRow[$sListCode] = implode(", ", MetaModel::GetZListItems($sClass, $sListCode));
		}
		$aData[] = $aRow;
	}
	echo MyHelpers::make_table_from_assoc_array($aData);
}


function ShowDatabaseInfo()
{
	$aTables = array();
	foreach (CMDBSource::EnumTables() as $sTable)
	{
		$aTableData = array();
		$aTableData["Name"] = $sTable;

		$aTableDesc = CMDBSource::GetTableInfo($sTable);
		$aTableData["Fields"] = MyHelpers::make_table_from_assoc_array($aTableDesc["Fields"]);
		
		$aTables[$sTable] = $aTableData;
	}
	echo MyHelpers::make_table_from_assoc_array($aTables);
}

function CreateDB()
{
	$sRes = "<p>Creating the DB...</p>\n";
	if (MetaModel::DBExists(false))
	{
		$sRes .= "<p>It appears that the DB already exists (at least one table).</p>\n";
	}
	else
	{
		MetaModel::DBCreate();
		$sRes .= "<p>Done!</p>\n";
	}
	return $sRes;
}

function DebugQuery($sConfigFile)
{
	$sQuery = ReadParam("oql");
	if (empty($sQuery))
	{
		$sQueryTemplate = "SELECT Foo AS f JOIN Dummy AS D ON d.spirit = f.id WHERE f.age * d.height > TO_DAYS(NOW()) OR d.alive";
	}
	else
	{
		$sQueryTemplate = $sQuery;
	} 
	echo "<form>\n";
	echo "<input type=\"hidden\" name=\"todo\" value=\"debugquery\">\n";
	echo "<input type=\"hidden\" name=\"config\" value=\"$sConfigFile\">\n";
	echo "<textarea name=\"oql\" rows=\"10\" cols=\"120\" name=\"csvdata\" wrap=\"soft\">$sQueryTemplate</textarea>\n";
	echo "<input type=\"submit\" name=\"foo\">\n";
	echo "</form>\n";

	if (empty($sQuery)) return;

	echo "<h1>Testing query</h1>\n";
	echo "<p>$sQuery</p>\n";
	
	echo "<h1>Follow up the query build</h1>\n";
	MetaModel::StartDebugQuery();
	$oFlt = DBObjectSearch::FromOQL($sQuery);	
	echo "<p>To OQL: ".$oFlt->ToOQL()."</p>";
	$sSQL = MetaModel::MakeSelectQuery($oFlt);
	MetaModel::StopDebugQuery();
	
	echo "<h1>Explain</h1>\n";
	echo "<table border=\"1\">\n";
	foreach (CMDBSource::ExplainQuery($sSQL) as $aRow)
	{
		echo "   <tr>\n";
		echo "      <td>".implode('</td><td>', $aRow)."</td>\n";
		echo "   </tr>\n";
	}
	echo "</table>\n";
	
	echo "<h1>Results</h1>\n";
	$oSet = new CMDBObjectSet($oFlt);
	echo $oSet; // __toString()
}

function DumpDatabase()
{
	$aData = MetaModel::DBDump();
	foreach ($aData as $sTable => $aRows)
	{
		echo "<h1>".htmlentities($sTable)."</h1>\n";

		if (count($aRows) == 0)
		{
			echo "<p>no data</p>\n";
		}
		else
		{
			echo "<p>".count($aRows)." row(s)</p>\n";
		// Table header
			echo "<table border=\"1\">\n";
			echo "<tr>\n";
			foreach (reset($aRows) as $key => $value)
			{
				echo "<th>".htmlentities($key)."</th>";
			}
			echo "</tr>\n";
	
			// Table body
			foreach ($aRows as $aRow)
			{
				echo "<tr>\n";
				foreach ($aRow as $key => $value)
				{
					echo "<td>".htmlentities($value)."</td>";
				}
				echo "</tr>\n";
			}
	
			echo "</table>\n";
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////
// Helper functions
/////////////////////////////////////////////////////////////////////////////////////

function printMenu($sConfigFile)
{
	$sClassCount = count(MetaModel::GetClasses());
	$bHasDB = MetaModel::DBExists(false); // no need to be complete to consider that something already exists
	$sUrl = "?config=".urlencode($sConfigFile);

	echo "<div style=\"background-color:eeeeee; padding:10px;\">\n";
	
	echo "<h2>phpMyORM integration sandbox</h2>\n";
	echo "<h4>Target database: $sConfigFile</h4>\n";
	echo "<p>$sClassCount classes referenced in the model</p>\n";
	echo "<ul>";
	echo "   <li><a href=\"$sUrl&todo=checkdictionary&categories=bizmodel&outputfilter=NotInDictionary\">Dictionary - missing entries (EN US)</a></li>";
	echo "   <li><a href=\"$sUrl&todo=dictionarystats\">Dictionary - statistics by language</a></li>";
	echo "   <li><a href=\"$sUrl&todo=checkmodel\">Biz model consistency</a></li>";
	echo "   <li><a href=\"$sUrl&todo=showzlists\">Show ZLists</a></li>";
	echo "   <li><a href=\"$sUrl&todo=showbizmodel\">Browse business model</a></li>";
	if ($bHasDB)
	{
		echo "   <li><a href=\"$sUrl&todo=checkmodeltodb\">Concordance between Biz model and DB format</a></li>";
		echo "   <li><a href=\"$sUrl&todo=checkdb\">DB integrity check</a></li>";
		echo "   <li><a href=\"$sUrl&todo=userrightssetup\">Setup userrights (init DB)</a></li>";
		echo "   <li><a href=\"$sUrl&todo=checkall\">Check business model, DB format and data integrity</a></li>";
		echo "   <li><a href=\"$sUrl&todo=showtables\">Show Tables</a></li>";
		echo "   <li><a href=\"$sUrl&todo=debugquery\">Test an OQL query (debug)</a></li>";
		echo "   <li><a href=\"$sUrl&todo=dumpdb\">Dump database</a></li>";
//		echo "   <li>".htmlentities($sUrl)."&amp;<b>todo=execsql</b>&amp;<b>sql=xxx</b>, to execute a specific sql request</li>";
	}
	else
	{
		echo "   <li><a href=\"$sUrl&todo=createdb\">Create the DB</a></li>";
	}
	echo "</ul>";
	echo "</div>\n";
}


function printConfigList()
{
	echo "<h2>phpMyORM integration sandbox</h2>\n";
	echo "<h4>Configuration sumary</h4>\n";

	$sBasePath = '..';

	$aConfigs = array();
	foreach(scandir($sBasePath) as $sFile)
	{
		if (preg_match('/^config-.+\\.php$/', $sFile)) $aConfigs[] = $sFile;
	}
	
	$aConfigDetails = array();
	foreach ($aConfigs as $sConfigFile)
	{
		$sRealPath = $sBasePath.'/'.$sConfigFile;
	
		$oConfig = new Config($sRealPath);
	
		$sAppModules = implode(', ', $oConfig->GetAppModules());
		$sDataModels = implode(', ', $oConfig->GetDataModels());
		$sAddons = implode(', ', $oConfig->GetAddons());
	
		$sDBSubname = (strlen($oConfig->GetDBSubname()) > 0) ? '('.$oConfig->GetDBSubname().')' : ''; 
	
		$sUrl = "?config=".urlencode($sRealPath);
		$sHLink = "<a href=\"$sUrl\">Manage <b>$sConfigFile</b></a></br>\n";
	
		$aConfigDetails[] = array('Config'=>$sHLink, 'Application'=>$sAppModules, 'Data models'=>$sDataModels, 'Addons'=>$sAddons, 'Database'=>$oConfig->GetDBHost().'/'.$oConfig->GetDBName().$sDBSubname.' as '.$oConfig->GetDBUser());
	}
	echo MyHelpers::make_table_from_assoc_array($aConfigDetails);
}


function ReadParam($sName, $defaultValue = "")
{
	return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
}

function ReadMandatoryParam($sName)
{
	$value = ReadParam($sName, null);
	if (is_null($value))
	{
		echo "<p>Missing mandatory argument <b>$sName</b></p>";
		exit;
	}
	return $value;
}

function DisplayDBFormatIssues($aErrors, $aSugFix, $sRepairUrl = "", $sSQLStatementArgName = "")
{
	$aSQLFixes = array(); // each and every SQL repair statement
	if (count($aErrors) > 0)
	{
		echo "<div style=\"width:100%;padding:10px;background:#FFAAAA;display:;\">";
		echo "<h1>Wrong Database format</h1>\n";
		echo "<p>The current database is not consistent with the given business model. Please investigate.</p>\n";
		foreach ($aErrors as $sClass => $aTarget)
		{
			echo "<p>Wrong declaration (or DB format ?) for class <b>$sClass</b></p>\n";
			echo "<ul class=\"treeview\">\n";
			$i = 0;
			foreach ($aTarget as $sTarget => $aMessages)
			{
				echo "<p>Wrong declaration for attribute <b>$sTarget</b></p>\n";
				$sMsg = implode(' AND ', $aMessages);
				if (!empty($sRepairUrl))
				{
					$aSQLFixes = array_merge($aSQLFixes, $aSugFix[$sClass][$sTarget]);
					$sSQLFixes = implode('; ', $aSugFix[$sClass][$sTarget]);
					$sUrl = "$sRepairUrl&$sSQLStatementArgName=".urlencode($sSQLFixes);
					echo "<li>$sMsg (<a href=\"$sUrl\" title=\"".htmlentities($sSQLFixes)."\" target=\"_blank\">fix it now!</a>)</li>\n";
				}
				else
				{
					echo "<li>$sMsg (".htmlentities($sSQLFixes).")</li>\n";
				}
				$i++;
			}
			echo "</ul>\n";
		}
		if (count($aSQLFixes) > 1)
		{
			MetaModel::DBShowApplyForm($sRepairUrl, $sSQLStatementArgName, $aSQLFixes);
		}
		echo "<p>Aborting...</p>\n";
		echo "</div>\n";
		exit;
	}
}



/////////////////////////////////////////////////////////////////////////////////////////////////
//
//	M a i n   P r o g r a m
//
/////////////////////////////////////////////////////////////////////////////////////////////////

require_once('../core/cmdbobject.class.inc.php');

$sConfigFile = ReadParam("config", '');
if (empty($sConfigFile))
{
	printConfigList();
	exit;
}

MetaModel::Startup($sConfigFile, true); // allow missing DB


$sBaseArgs = "config=".urlencode($sConfigFile);

$sTodo = ReadParam("todo", "");
if ($sTodo == 'execsql')
{
	$sSql = ReadMandatoryParam("sql");
	$aSql = explode("##SEP##", $sSql);

	$sConfirm = ReadParam("confirm");
	if (empty($sConfirm) || ($sConfirm != "Yes"))
	{
		echo "<form method=\"post\" action=\"?$sBaseArgs\">\n";
		echo "<input type=\"hidden\" name=\"todo\" value=\"execsql\">\n";
		echo "<input type=\"hidden\" name=\"sql\" value=\"".htmlentities($sSql)."\">\n";
		if (count($aSql) == 1)
		{
			echo "Do you confirm that you want to execute this command: <b>".htmlentities($aSql[0])."</b> ?</br>\n";
		}
		else
		{
			$sAllQueries = "<li>".implode("</li>\n<li>", $aSql)."</li>\n";
			echo "Please confirm that you want to execute these commands: <ul style=\"font-size: smaller;\">".$sAllQueries."</ul>\n";
		}
		echo "<input type=\"submit\" name=\"confirm\" value=\"Yes\">\n";
		echo "</form>\n";
	}
	else
	{
		foreach ($aSql as $sOneSingleSql)
		{
			echo "Executing command: <b>$sOneSingleSql</b></br>\n";
			CMDBSource::Query($sOneSingleSql);
			echo "... done!</br>\n";
		}
	}
}
else
{
	$sBaseUrl = "?$sBaseArgs&todo=execsql";
	switch ($sTodo)
	{
		case "createdb":
			// do NOT print the menu, because it will change...
			break;

		default:
			printMenu($sConfigFile);
	}
	switch ($sTodo)
	{
		case "showtables":
			ShowDatabaseInfo();
			break;
		case "showbizmodel":
			ShowBizModel($sBaseArgs);
			break;
		case "showclass":
			$sClass = ReadMandatoryParam("class");
			ShowClass($sClass, $sBaseArgs);
			break;
		case "showzlists":
			ShowZLists($sBaseArgs);
			break;
		case "debugquery":
			DebugQuery($sConfigFile);
			break;
		case "createdb":
			$sRes = CreateDB();
			// As the menu depends on the existence of the DB, we have to do display it right after the job is done
			printMenu($sConfigFile);
			echo $sRes;
			break;
		case "dictionarystats":
			echo "Dictionary: statistics by language<br/>\n";
			foreach (Dict::GetLanguages() as $sLanguageCode => $aLanguageData)
			{
				list($aMissing, $aUnexpected, $aNotTranslated, $aOK) = Dict::MakeStats($sLanguageCode, 'EN US');
				echo "<p>Stats for language: $sLanguageCode</p>\n"; 
				echo "<ul><li>Missing:".count($aMissing)."</li><li>Unexpected:".count($aUnexpected)."</li><li>NotTranslated:".count($aNotTranslated)."</li><li>OK:".count($aOK)."</li></ul>\n";
			}
			break;
		case "checkdictionary":
			$sCategories = ReadMandatoryParam("categories");
			$sOutputFilter = ReadParam("outputfilter", '');
			echo "Dictionary: missing entries (categories: $sCategories, output: '$sOutputFilter')</br>\n";
			echo "<pre>\n";
			echo MetaModel::MakeDictionaryTemplate($sCategories, $sOutputFilter);
			echo "</pre>\n";
			break;
		case "checkmodel":
			echo "Check definitions...</br>\n";
			MetaModel::CheckDefinitions();
			echo "done...</br>\n";
			break;
		case "checkmodeltodb":
			echo "Check DB format...</br>\n";
			list($aErrors, $aSugFix) = MetaModel::DBCheckFormat();
			DisplayDBFormatIssues($aErrors, $aSugFix, $sBaseUrl, $sSQLStatementArgName = "sql");
			echo "done...</br>\n";
			break;
		case "checkdb":
			echo "Check DB integrity...</br>\n";
			MetaModel::DBCheckIntegrity($sBaseUrl, "sql");
			echo "done...</br>\n";
			break;
		case "dumpdb":
			echo "Dump DB data...</br>\n";
			DumpDatabase();
			echo "done...</br>\n";
			break;
		case "userrightssetup":
			echo "Setup user rights module (init DB)...</br>\n";
			UserRights::Setup();
			echo "done...</br>\n";
			break;
		case "checkall":
			echo "Check definitions...</br>\n";
			MetaModel::CheckDefinitions();
			echo "done...</br>\n";
			echo "Check DB format...</br>\n";
			list($aErrors, $aSugFix) = MetaModel::DBCheckFormat();
			DisplayDBFormatIssues($aErrors, $aSugFix, $sBaseUrl, $sSQLStatementArgName = "sql");
			echo "done...</br>\n";
			echo "Check DB integrity...</br>\n";
			MetaModel::DBCheckIntegrity($sBaseUrl, "sql");
			echo "done...</br>\n";
			break;
	}
}


?>

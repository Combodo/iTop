<?php

/**
 * Import web service 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

//
// Known limitations
// - output still in html, because the errors are not displayed in xml
// - could not set the external keys by the mean of a reconciliation (the exact key must be given)
// - reconciliation is made on the first column
// - no option to force 'always create' or 'never create'
//
// Known issues
// - ALMOST impossible to troubleshoot when an externl key has a wrong value
// - no character escaping in the xml output (yes !?!?!)
// - not outputing xml when a wrong input is given (class, attribute names)
// - for a bizIncidentTicket you may use the name as the reconciliation key,
//   but that attribute is in fact recomputed by the application! An error should be raised somewhere
//

require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/csvpage.class.inc.php');
require_once('../application/xmlpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
//$iActiveNodeId = utils::ReadParam('menu', -1);
//$currentOrganization = utils::ReadParam('org_id', '');

// Main program

//$oP = new XMLPage("iTop - Bulk import");
$oP = new web_page("iTop - Bulk import");
try
{
	$sClass = utils::ReadParam('class', '');
	$sSep = utils::ReadParam('separator', ';');
	$sCSVData = utils::ReadPostedParam('csvdata');

	$oCSVParser = new CSVParser($sCSVData); 
	$oCSVParser->SetSeparator($sSep);
	$oCSVParser->SetSkipLines(1);

	// Limitation: as the attribute list is in the first line, we can not match external key by an third-party attribute
	$sRawFieldList = $oCSVParser->ListFields();
	$aAttList = array();
	foreach($sRawFieldList as $iField => $sFieldName)
	{
		$aAttList[$sFieldName] = $iField;
	}
	$aExtKeys = array();

	// Limitation: the reconciliation key is the first attribute
	$aReconcilKeys = array($sRawFieldList[0]);

//	print_r($oCSVParser->ListFields());
//	print_r($oCSVParser->ToArray($oCSVParser->ListFields()));

	$aData = $oCSVParser->ToArray();
	$oBulk = new BulkChange(
		$sClass,
		$aData,
		$aAttList,
		$aReconcilKeys,
		$aExtKeys
	);

	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	if (UserRights::GetUser() != UserRights::GetRealUser())
	{
		$sUserString = UserRights::GetRealUser()." on behalf of ".UserRights::GetUser();
	}
	else
	{
		$sUserString = UserRights::GetUser();
	}
	$oMyChange->Set("userinfo", $sUserString.' (bulk load by web service)');
	$iChangeId = $oMyChange->DBInsert();

	$aRes = $oBulk->Process($oMyChange);

	// Setup result presentation
	//
	$aDisplayConfig = array();
	$aDisplayConfig["__RECONCILIATION__"] = array("label"=>"Reconciliation", "description"=>"");
	$aDisplayConfig["__STATUS__"] = array("label"=>"Status", "description"=>"");
	if (isset($iPKeyId))
	{
		$aDisplayConfig["col$iPKeyId"] = array("label"=>"<strong>pkey</strong>", "description"=>"");
	}
	foreach($aReconcilKeys as $iCol => $sAttCode)
	{
		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
		$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
	}
	foreach ($aAttList as $sAttCode => $iCol)
	{
		$sLabel = MetaModel::GetAttributeDef($sClass, $sAttCode)->GetLabel();
		$aDisplayConfig["col$iCol"] = array("label"=>"$sLabel", "description"=>"");
	}


	$aResultDisp = array(); // to be displayed
	foreach($aRes as $iRow => $aRowData)
	{
		$aRowDisp = array();
		$aRowDisp["__RECONCILIATION__"] = $aRowData["__RECONCILIATION__"];
		$aRowDisp["__STATUS__"] = $aRowData["__STATUS__"]->GetDescription();
		foreach($aRowData as $sKey => $value)
		{
			if ($sKey == '__RECONCILIATION__') continue;
			if ($sKey == '__STATUS__') continue;
			
			switch (get_class($value))
			{
				case 'CellChangeSpec_Void':
					$sClass = '';
					break;
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
			}
			if (empty($sClass))
			{
				$aRowDisp[$sKey] = $value->GetDescription();
			}
			else
			{
				$aRowDisp[$sKey] = "<div class=\"$sClass\">".$value->GetDescription()."</div>";
			}
		}
		$aResultDisp[$iRow] = $aRowDisp;
	}
	$oP->table($aDisplayConfig, $aResultDisp);

}
catch(Exception $e)
{
	$oP->add('<error>'.((string)$e).'</error>');		
}

$oP->output();
?>

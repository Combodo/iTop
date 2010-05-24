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
 * Import web service 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//
// Known limitations
// - output still in html, because the errors are not displayed in xml
// - reconciliation is made on the first column
// - no option to force 'always create' or 'never create'
// - text qualifier hardcoded to "
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

class WebServiceException extends Exception
{
}

LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oContext = new UserContext();
$oAppContext = new ApplicationContext();
//$iActiveNodeId = utils::ReadParam('menu', -1);
//$currentOrganization = utils::ReadParam('org_id', '');

// Main program

//$oP = new XMLPage("iTop - Bulk import");
$oP = new WebPage("iTop - Bulk import");
$oP->add('<warning>This is a prototype, I repeat: PRO-TO-TYPE, therefore it suffers bugs and limitations, documented in the code. Next step: specify...</warning>');		
try
{
	$sClass = utils::ReadParam('class', '');
	$sSep = utils::ReadParam('separator', ';');
	$sCSVData = utils::ReadPostedParam('csvdata');

	$oCSVParser = new CSVParser($sCSVData, $sSep, $sDelimiter = '"'); 

	// Limitation: as the attribute list is in the first line, we can not match external key by a third-party attribute
	$sRawFieldList = $oCSVParser->ListFields();
	$aAttList = array();
	$aExtKeys = array();
	foreach($sRawFieldList as $iFieldId => $sFieldName)
	{
		if (!MetaModel::IsValidAttCode($sClass, $sFieldName))
		{
			throw new WebServiceException("Unknown attribute '$sFieldName' (class: '$sClass')");
		}
		$oAtt = MetaModel::GetAttributeDef($sClass, $sFieldName);
		if ($oAtt->IsExternalKey())
		{
			$aExtKeys[$sFieldName]['id'] = $iFieldId;
			$aAttList[$sFieldName] = $iFieldId;
		}
		elseif ($oAtt->IsExternalField())
		{
			$sExtKeyAttCode = $oAtt->GetKeyAttCode();
			$sRemoteAttCode = $oAtt->GetExtAttCode();
			$aExtKeys[$sExtKeyAttCode][$sRemoteAttCode] = $iFieldId;
		}
		else
		{
			$aAttList[$sFieldName] = $iFieldId;
		}
	}

	// Limitation: the reconciliation key is the first attribute
	$aReconcilKeys = array($sRawFieldList[0]);

	$aData = $oCSVParser->ToArray();
	$oBulk = new BulkChange(
		$sClass,
		$aData,
		$aAttList,
		$aExtKeys,
		$aReconcilKeys
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
		$aDisplayConfig["col$iPKeyId"] = array("label"=>"<strong>id</strong>", "description"=>"");
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
				case 'CellStatus_Void':
					$sClass = '';
					break;
				case 'CellStatus_Modify':
					$sClass = 'csvimport_ok';
					break;
				case 'CellStatus_Issue':
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

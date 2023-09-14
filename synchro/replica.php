<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Core\CMDBChange\CMDBChangeOrigin;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin();

$sOperation = utils::ReadParam('operation', 'menu');
$oAppContext = new ApplicationContext();

$oP = new iTopWebPage("iTop - Synchro Replicas");

// Main program
$sOperation = utils::ReadParam('operation', 'details');

/**
 * @param \DBObject|null $oReplica
 * @param $this
 *
 * @return \SynchroLog
 * @throws \ArchivedObjectException
 * @throws \CoreCannotSaveObjectException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \CoreWarning
 * @throws \MySQLException
 * @throws \OQLException
 * @throws \SynchroExceptionNotStarted
 */
function Synchro($oReplica): SynchroLog
{
	$oDataSource = MetaModel::GetObject('SynchroDataSource', $oReplica->Get('sync_source_id'));

	$oStatLog = new SynchroLog();
	$oStatLog->Set('sync_source_id', $oDataSource->GetKey());
	$oStatLog->Set('start_date', time());
	$oStatLog->Set('status', 'running');
	$oStatLog->AddTrace('Manual synchro');

	// Get the list of SQL columns
	$aAttCodesExpected = array();
	$aAttCodesToReconcile = array();
	$aAttCodesToUpdate = array();
	$sSelectAtt = 'SELECT SynchroAttribute WHERE sync_source_id = :source_id AND (update = 1 OR reconcile = 1)';
	$oSetAtt = new DBObjectSet(DBObjectSearch::FromOQL($sSelectAtt), array() /* order by*/, array('source_id' => $oDataSource->GetKey()) /* aArgs */);
	while ($oSyncAtt = $oSetAtt->Fetch()) {
		if ($oSyncAtt->Get('update')) {
			$aAttCodesToUpdate[$oSyncAtt->Get('attcode')] = $oSyncAtt;
		}
		if ($oSyncAtt->Get('reconcile')) {
			$aAttCodesToReconcile[$oSyncAtt->Get('attcode')] = $oSyncAtt;
		}
		$aAttCodesExpected[$oSyncAtt->Get('attcode')] = $oSyncAtt;
	}

	// Get the list of attributes, determine reconciliation keys and update targets
	//
	if ($oDataSource->Get('reconciliation_policy') == 'use_attributes') {
		$aReconciliationKeys = $aAttCodesToReconcile;
	} elseif ($oDataSource->Get('reconciliation_policy') == 'use_primary_key') {
		// Override the settings made at the attribute level !
		$aReconciliationKeys = array('primary_key' => null);
	}

	if (count($aAttCodesToUpdate) == 0) {
		$oStatLog->AddTrace('No attribute to update');
		throw new SynchroExceptionNotStarted('There is no attribute to update');
	}
	if (count($aReconciliationKeys) == 0) {
		$oStatLog->AddTrace('No attribute for reconciliation');
		throw new SynchroExceptionNotStarted('No attribute for reconciliation');
	}


	$aAttributesToUpdate = array();
	foreach ($aAttCodesToUpdate as $sAttCode => $oSyncAtt) {
		$oAttDef = MetaModel::GetAttributeDef($oDataSource->GetTargetClass(), $sAttCode);
		if ($oAttDef->IsWritable()) {
			$aAttributesToUpdate[$sAttCode] = $oSyncAtt;
		}
	}
	// Create a change used for logging all the modifications/creations happening during the synchro
	$oChange = MetaModel::NewObject('CMDBChange');
	$oChange->Set('date', time());
	$sUserString = CMDBChange::GetCurrentUserName();
	$oChange->Set('userinfo', $sUserString.' '.Dict::S('Core:SyncDataExchangeComment'));
	$oChange->Set('origin', CMDBChangeOrigin::SYNCHRO_DATA_SOURCE);
	$oChange->DBInsert();
	CMDBObject::SetCurrentChange($oChange);

	$oReplica->InitExtendedData($oDataSource);

	$oReplica->Synchro($oDataSource, $aReconciliationKeys, $aAttributesToUpdate, $oChange, $oStatLog);
	$oReplica->DBUpdate();

	return $oStatLog;
}

try {
	switch ($sOperation) {
		case 'details':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
			$oReplica->DisplayDetails($oP);
			break;

		case 'oql':
			$sOQL = utils::ReadParam('oql', null, false, 'raw_data');
			if ($sOQL == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'oql'));
			}
			$oFilter = DBObjectSearch::FromOQL($sOQL);
			$oBlock1 = new DisplayBlock($oFilter, 'search', false, array('menu' => false, 'table_id' => '1'));
			$oBlock1->Display($oP, 0);
			$oP->add('<p class="page-header">'.MetaModel::GetClassIcon('SynchroReplica').Dict::S('Core:SynchroReplica:ListOfReplicas').'</p>');
			$iSourceId = utils::ReadParam('datasource', null);
			if ($iSourceId != null) {
				$oSource = MetaModel::GetObject('SynchroDataSource', $iSourceId);
				$oP->p(Dict::Format('Core:SynchroReplica:BackToDataSource', $oSource->GetHyperlink()).'</a>');
			}
			$oBlock = new DisplayBlock($oFilter, 'list', false, array('menu' => false));
			$oBlock->Display($oP, 1);
			break;

		case 'delete':
		case 'select_for_deletion':
			// Redirect to the page that implements bulk delete
			$sDelete = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?'.$_SERVER['QUERY_STRING'];
			header("Location: $sDelete");
			break;

		case 'unlinksynchro':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
			$oReplica->Set('dest_id', '');
			$oReplica->Set('status', 'new');
			$oReplica->DBWrite();

			$oStatLog = Synchro($oReplica);
			$oP->add(implode('<br>', $oStatLog->GetTraces()));

			$oReplica->DisplayDetails($oP);
			break;

		case 'unlink':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
			$oReplica->Set('dest_id', '');
			$oReplica->Set('status', 'new');
			$oReplica->DBWrite();

			$oReplica->DisplayDetails($oP);
			break;

		case 'synchro':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
			$oStatLog = Synchro($oReplica);
			break;
	}
}
catch(CoreException $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getHtmlDesc());
}
catch(Exception $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getMessage());
}

$oP->output();

<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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

use Combodo\iTop\Application\Helper\BulkHelper;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;

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
try {
	switch ($sOperation) {
		case 'details':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oReplica->DisplayDetails($oP);
			break;

		case 'oql':
			$iSourceId = utils::ReadParam('datasource', null);
			if ($iSourceId != null) {
				$oSource = MetaModel::GetObject(SynchroDataSource::class, $iSourceId);
				$oBackButton = ButtonUIBlockFactory::MakeLinkNeutral(ApplicationContext::MakeObjectUrl(SynchroDataSource::class, $iSourceId), Dict::Format('Core:SynchroReplica:BackToDataSource', $oSource->GetName()), 'fas fa-chevron-left');
				$oP->AddUiBlock($oBackButton);
				$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage(Dict::Format('Core:SynchroReplica:ListOfReplicas', $oSource->GetName())));
			}

			$sOQL = utils::ReadParam('oql', null, false, 'raw_data');
			if ($sOQL == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'oql'));
			}
			$oFilter = DBObjectSearch::FromOQL($sOQL);
			$oBlock1 = new DisplayBlock($oFilter, 'search', false, ['menu' => true, 'table_id' => '1']);
			$oBlock1->Display($oP, 0);
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
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oReplica->UnLink();

			$oStatLog = $oReplica->ReSynchro();
			$oP->add(implode('<br>', $oStatLog->GetTraces()));

			$oReplica->DisplayDetails($oP);
			break;

		case 'unlink':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oReplica->UnLink();

			$oReplica->DisplayDetails($oP);
			break;

		case 'synchro':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oStatLog = $oReplica->ReSynchro();
			$oReplica->DisplayDetails($oP);
			break;

		case 'allowdelete':
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oStatLog = $oReplica->Set('status_dest_creator', 1);
			$oReplica->DisplayDetails($oP);
			break;

		case 'denydelete': // Select the list of objects to be modified (bulk modify)
			$iId = utils::ReadParam('id', null);
			if ($iId == null) {
				throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
			}
			$oReplica = MetaModel::GetObject(SynchroReplica::class, $iId);
			$oStatLog = $oReplica->Set('status_dest_creator', 0);
			$oReplica->DisplayDetails($oP);
			break;

		case 'select_for_unlink_all': // Select the list of objects to be modified (bulk modify)
			BulkHelper::OperationSelectForModifyAll($oP, 'UI:UnlinkAllTabTitle', 'UI:UnlinkAllPageTitle', 'form_for_unlink_all');
			break;

		case 'select_for_unlinksynchro_all': // Select the list of objects to be modified (bulk modify)
			BulkHelper::OperationSelectForModifyAll($oP, 'UI:UnlinkSynchroAllTabTitle', 'UI:UnlinkSynchroAllPageTitle', 'form_for_unlinksynchro_all');
			break;

		case 'select_for_synchro_all': // Select the list of objects to be modified (bulk modify)
			BulkHelper::OperationSelectForModifyAll($oP, 'UI:SynchroAllTabTitle', 'UI:SynchroAllPageTitle', 'form_for_synchro_all');
			break;

		case 'select_for_allowdelete_all': // Select the list of objects to be modified (bulk modify)
			BulkHelper::OperationSelectForModifyAll($oP, 'UI:AllowDeleteAllTabTitle', 'UI:AllowDeleteAllPageTitle', 'form_for_allowdelete_all');
			break;

		case 'select_for_denydelete_all': // Select the list of objects to be modified (bulk modify)
			BulkHelper::OperationSelectForModifyAll($oP, 'UI:DenyDeleteAllTabTitle', 'UI:DenyDeleteAllPageTitle', 'form_for_denydelete_all');
			break;
	}
} catch (CoreException $e) {
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getHtmlDesc());
} catch (Exception $e) {
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getMessage());
}

$oP->output();

<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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
try
{
	switch($sOperation)
	{
		case 'details':
		$iId = utils::ReadParam('id', null);
		if ($iId == null)
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'id'));
		}
		$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
		$oReplica->DisplayDetails($oP);
		break;
		
		case 'oql':
		$sOQL = utils::ReadParam('oql', null, false, 'raw_data');
		if ($sOQL == null)
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'oql'));
		}
		$oFilter = DBObjectSearch::FromOQL($sOQL);
			$oBlock1 = new DisplayBlock($oFilter, 'search', false, array('menu' => false, 'table_id' => '1'));
		$oBlock1->Display($oP, 0);
		$oP->add('<p class="page-header">'.MetaModel::GetClassIcon('SynchroReplica').Dict::S('Core:SynchroReplica:ListOfReplicas').'</p>');
		$iSourceId = utils::ReadParam('datasource', null);
		if ($iSourceId != null)
		{
			$oSource = MetaModel::GetObject('SynchroDataSource', $iSourceId);
			$oP->p(Dict::Format('Core:SynchroReplica:BackToDataSource', $oSource->GetHyperlink()).'</a>');
		}
		$oBlock = new DisplayBlock($oFilter, 'list', false, array('menu'=>false));
		$oBlock->Display($oP, 1);
		break;

		case 'delete':
		case 'select_for_deletion':
		// Redirect to the page that implements bulk delete
		$sDelete = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?'.$_SERVER['QUERY_STRING'];
		header("Location: $sDelete");
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

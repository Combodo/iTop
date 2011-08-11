<?php
// Copyright (C) 2011 Combodo SARL
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
 * Display and search synchro replicas
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed, admins only here !

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
		$oP->add('<p class="page-header">'.MetaModel::GetClassIcon('SynchroReplica').Dict::S('Core:SynchroReplica:ListOfReplicas').'</p>');
		$iSourceId = utils::ReadParam('datasource', null);
		if ($iSourceId != null)
		{
			$oSource = MetaModel::GetObject('SynchroDataSource', $iSourceId);
			$oP->p(Dict::Format('Core:SynchroReplica:BackToDataSource', $oSource->GetHyperlink()).'</a>');
		}
		$oFilter = DBObjectSearch::FromOQL($sOQL);
		$oBlock = new DisplayBlock($oFilter, 'list', false, array('menu'=>false));
		$oBlock->Display($oP, 1);
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
?>

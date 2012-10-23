<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Import web service 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Known limitations
// - reconciliation is made on the first column
//
// Known issues
// - ALMOST impossible to troubleshoot when an external key has a wrong value
// - no character escaping in the xml output (yes !?!?!)
// - not outputing xml when a wrong input is given (class, attribute names)
//

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');


function UsageAndExit($oP)
{
	global $aPageParams;
	$bModeCLI = utils::IsModeCLI();

	if ($bModeCLI)
	{
		$oP->p("USAGE:\n");
		$oP->p("php -q synchro_exec.php --auth_user=<login> --auth_pwd=<password> --data_sources=<comma_separated_list_of_data_sources> [max_chunk_size=<limit the count of replica loaded in a single pass>]\n");		
	}
	else
	{
		$oP->p("The parameter 'data_sources' is mandatory, and must contain a comma separated list of data sources\n");		
	}
	$oP->output();
	exit -2;
}

function ReadMandatoryParam($oP, $sParam, $sSanitizationFilter = 'parameter')
{
	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */, $sSanitizationFilter);
	if (is_null($sValue))
	{
		$oP->p("ERROR: Missing argument '$sParam'\n");
		UsageAndExit($oP);
	}
	return trim($sValue);
}

/////////////////////////////////
// Main program

if (utils::IsModeCLI())
{
	$oP = new CLIPage(Dict::S("TitleSynchroExecution"));
}
else
{
	$oP = new WebPage(Dict::S("TitleSynchroExecution"));
}

try
{
	utils::UseParamFile();
}
catch(Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit -2;
}

if (utils::IsModeCLI())
{
	// Next steps:
	//   specific arguments: 'csvfile'
	//   
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user', 'raw_data');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd', 'raw_data');
	$sDataSourcesList = ReadMandatoryParam($oP, 'data_sources', 'raw_data'); // May contain commas
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		$oP->p("Access restricted or wrong credentials ('$sAuthUser')");
		$oP->output();
		exit -1;
	}
}
else
{
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed

	$sDataSourcesList = utils::ReadParam('data_sources', null, true, 'raw_data');
	
	if ($sDataSourcesList == null)
	{
		UsageAndExit($oP);
	}
}

$bSimulate = (utils::ReadParam('simulate', '0', true) == '1');


foreach(explode(',', $sDataSourcesList) as $iSDS)
{
	$oSynchroDataSource = MetaModel::GetObject('SynchroDataSource', $iSDS, false);
	if ($oSynchroDataSource == null)
	{
		$oP->p("ERROR: The data source (id=$iSDS) does not exist. Exiting...");
		$oP->output();
		exit -3;
	}
	else
	{
		if ($bSimulate)
		{
			CMDBSource::Query('START TRANSACTION');
		}
		try
		{
			$oSynchroExec = new SynchroExecution($oSynchroDataSource);
			$oStatLog = $oSynchroExec->Process();
			if ($bSimulate)
			{
				CMDBSource::Query('ROLLBACK');
			}
			foreach ($oStatLog->GetTraces() as $sMessage)
			{
				$oP->p('#'.$sMessage);
			}
			if ($oStatLog->Get('status') == 'error')
			{
				$oP->p("ERROR: ".$oStatLog->Get('last_error'));
			}
			$oP->p("Replicas: ".$oStatLog->Get('stats_nb_replica_total'));
			$oP->p("Replicas touched since last synchro: ".$oStatLog->Get('stats_nb_replica_seen'));
			$oP->p("Objects deleted: ".$oStatLog->Get('stats_nb_obj_deleted'));
			$oP->p("Objects deletion errors: ".$oStatLog->Get('stats_nb_obj_deleted_errors'));
			$oP->p("Objects obsoleted: ".$oStatLog->Get('stats_nb_obj_obsoleted'));
			$oP->p("Objects obsolescence errors: ".$oStatLog->Get('stats_nb_obj_obsoleted_errors'));
			$oP->p("Objects created: ".$oStatLog->Get('stats_nb_obj_created')." (".$oStatLog->Get('stats_nb_obj_created_warnings')." warnings)");
			$oP->p("Objects creation errors: ".$oStatLog->Get('stats_nb_obj_created_errors'));
			$oP->p("Objects updated: ".$oStatLog->Get('stats_nb_obj_updated')." (".$oStatLog->Get('stats_nb_obj_updated_warnings')." warnings)");
			$oP->p("Objects update errors: ".$oStatLog->Get('stats_nb_obj_updated_errors'));
			$oP->p("Objects reconciled (updated): ".$oStatLog->Get('stats_nb_obj_new_updated')." (".$oStatLog->Get('stats_nb_obj_new_updated_warnings')." warnings)");
			$oP->p("Objects reconciled (unchanged): ".$oStatLog->Get('stats_nb_obj_new_unchanged')." (".$oStatLog->Get('stats_nb_obj_new_updated_warnings')." warnings)");
			$oP->p("Objects reconciliation errors: ".$oStatLog->Get('stats_nb_replica_reconciled_errors'));
			$oP->p("Replica disappeared, no action taken: ".$oStatLog->Get('stats_nb_replica_disappeared_no_action'));
		}
		catch(Exception $e)
		{
			$oP->add($e->getMessage());		
			if ($bSimulate)
			{
				CMDBSource::Query('ROLLBACK');
			}
		}
	}
}

$oP->output();
?>

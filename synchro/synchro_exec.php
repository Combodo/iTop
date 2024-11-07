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

//
// Known limitations
// - reconciliation is made on the first column
//
// Known issues
// - ALMOST impossible to troubleshoot when an external key has a wrong value
// - no character escaping in the xml output (yes !?!?!)
// - not outputing xml when a wrong input is given (class, attribute names)
//

use Combodo\iTop\Application\WebPage\CLIPage;
use Combodo\iTop\Application\WebPage\WebPage;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');


function UsageAndExit($oP)
{
	global $aPageParams;
	$bModeCLI = utils::IsModeCLI();

	if ($bModeCLI)
	{
		$oP->p("USAGE:\n");
		$oP->p("php -q synchro_exec.php --auth_user=<login> --auth_pwd=<password> --data_sources=<comma_separated_list_of_data_sources> [--max_chunk_size=<limit the count of replica loaded in a single pass>] [--simulate=<If set to 1, then the synchro will not be executed, but the expected report will be produced>]\n");
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
	SetupUtils::CheckPhpAndExtensionsForCli($oP, -2);
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
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user', 'raw_data');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd', 'raw_data');
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
else {
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	//N°6022 - Make synchro scripts work by http via token authentication with SYNCHRO scopes
	$oCtx = new ContextTag(ContextTag::TAG_SYNCHRO);
	LoginWebPage::ResetSession(true);
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
	if ($iRet !== LoginWebPage::EXIT_CODE_OK) {
		switch ($iRet) {
			case LoginWebPage::EXIT_CODE_MISSINGLOGIN:
				$oP->p("Missing parameter 'auth_user'");
				break;

			case LoginWebPage::EXIT_CODE_MISSINGPASSWORD:
				$oP->p("Missing parameter 'auth_pwd'");
				break;

			case LoginWebPage::EXIT_CODE_WRONGCREDENTIALS:
				$oP->p('Invalid login');
				break;

			case LoginWebPage::EXIT_CODE_PORTALUSERNOTAUTHORIZED:
				$oP->p('Portal user is not allowed');
				break;

			case LoginWebPage::EXIT_CODE_NOTAUTHORIZED:
				$oP->p('This user is not authorized to use the web services. (The profile REST Services User is required to access the REST web services)');
				break;

			default:
				$oP->p("Unknown authentication error (retCode=$iRet)");
		}
		$oP->output();
		exit - 1;
	}

}

$bSimulate = (utils::ReadParam('simulate', '0', true) == '1');
$sDataSourcesList = ReadMandatoryParam($oP, 'data_sources', 'raw_data'); // May contain commas

if ($sDataSourcesList == null) {
	UsageAndExit($oP);
}

foreach(explode(',', $sDataSourcesList) as $iSDS)
{
	$oSynchroDataSource = MetaModel::GetObject('SynchroDataSource', $iSDS, false);
	if ($oSynchroDataSource == null)
	{
		$oP->p("ERROR: The data source (id=".utils::HtmlEntities($iSDS).") does not exist. Exiting...");
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
			$oP->p("Working on ".utils::HtmlEntities($oSynchroDataSource->Get('name'))." (id=".utils::HtmlEntities($iSDS).")...");
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

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
// - reconciliation is made on the first column
//
// Known issues
// - ALMOST impossible to troubleshoot when an externl key has a wrong value
// - no character escaping in the xml output (yes !?!?!)
// - not outputing xml when a wrong input is given (class, attribute names)
//

require_once('../approot.inc.php');
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
		$oP->p("php -q synchro_exec.php auth_user=<login> auth_pwd=<password> data_sources=<comma_separated_list_of_data_sources>\n");		
	}
	else
	{
		$oP->p("The parameter 'data_sources' is mandatory, and must contain a comma separated list of data sources\n");		
	}
	$oP->output();
	exit -2;
}

function ReadMandatoryParam($oP, $sParam)
{
	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */);
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

	// Next steps:
	//   specific arguments: 'csvfile'
	//   
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd');
	$sDataSourcesList = ReadMandatoryParam($oP, 'data_sources');
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

	$oP = new WebPage(Dict::S("TitleSynchroExecution"));
	$sDataSourcesList = utils::ReadParam('data_sources', null);
	if ($sDataSourcesList == null)
	{
		UsageAndExit($oP);
	}
}


try
{
	//////////////////////////////////////////////////
	//
	// Security
	//
	if (!UserRights::IsAdministrator())
	{
		throw new SecurityException(Dict::Format('UI:Error:ActionNotAllowed', $sClass));
	}
	
	foreach(explode(',', $sDataSourcesList) as $iSDS)
	{
		$oSynchroDataSource = MetaModel::GetObject('SynchroDataSource', $iSDS, false);
		if ($oSynchroDataSource == null)
		{
			$oP->p("The data source (id=$iSDS) does not exist. Exiting...");
			$oP->output();
			exit -3;
		}
		else
		{
			$aResults = array();
			$oSynchroDataSource->Synchronize($aResults);
		}
	}
}
catch(SecurityException $e)
{
	$oP->add($e->getMessage());		
}
catch(Exception $e)
{
	$oP->add((string)$e);		
}

$oP->output();
?>

<?php
// Copyright (C) 2014 Combodo SARL
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

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/webpage.class.inc.php');
require_once(APPROOT.'application/csvpage.class.inc.php');
require_once(APPROOT.'application/clipage.class.inc.php');
require_once(APPROOT.'application/ajaxwebpage.class.inc.php');

require_once(APPROOT.'core/log.class.inc.php');

require_once(APPROOT.'application/startup.inc.php');

class MyDBBackup extends DBBackup
{
	protected function LogInfo($sMsg)
	{
		$this->oPage->p($sMsg);
	}

	protected function LogError($sMsg)
	{
		$this->oPage->p('Error: '.$sMsg);
		ToolsLog::Error($sMsg);
	}

	protected $oPage;
	public function __construct($oPage)
	{
		$this->oPage = $oPage;
		parent::__construct();
	}
}


/**
 * Checks if a parameter (possibly empty) was specified when calling this page
 */
function CheckParam($sParamName)
{
	global $argv;
	
	if (isset($_REQUEST[$sParamName])) return true; // HTTP parameter either GET or POST
	if (!is_array($argv)) return false;
	foreach($argv as $sArg)
	{
		if ($sArg == '--'.$sParamName) return true; // Empty command line parameter, long unix style
		if ($sArg == $sParamName) return true; // Empty command line parameter, Windows style
		if ($sArg == '-'.$sParamName) return true; // Empty command line parameter, short unix style
		if (preg_match('/^--'.$sParamName.'=(.*)$/', $sArg, $aMatches)) return true; // Command parameter with a value
	}
	return false;
}

function Usage($oP)
{
	$oP->p('Perform a backup of the iTop database by running mysqldump');
	$oP->p('Parameters:');
	if (utils::IsModeCLI())
	{
		$oP->p('auth_user: login, must be administrator');
		$oP->p('auth_pwd: ...');
	}
	$oP->p('backup_file [optional]: name of the file to store the backup into. Follows the PHP strftime format spec. The following placeholders are available: __HOST__, __DB__, __SUBNAME__');
	$oP->p('simulate [optional]: set to check the name of the file that would be created');
	$oP->p('mysql_bindir [optional]: specify the path for mysqldump');

	if (utils::IsModeCLI())
	{
		$oP->p('Example: php -q backup.php --auth_user=admin --auth_pwd=myPassw0rd');
		$oP->p('Known limitation: the current directory must be the directory of backup.php');
	}
	else
	{
		$oP->p('Example: .../backup.php?backup_file=/tmp/backup.__DB__-__SUBNAME__.%Y-%m');
	}
}

function ExitError($oP, $sMessage)
{
	ToolsLog::Error($sMessage);
	$oP->p($sMessage);
	$oP->output();
	exit;
}


function ReadMandatoryParam($oP, $sParam)
{
	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */, 'raw_data');
	if (is_null($sValue))
	{
		ExitError($oP, "ERROR: Missing argument '$sParam'");
	}
	return trim($sValue);
}


/////////////////////////////////
// Main program

set_time_limit(0);

if (utils::IsModeCLI())
{
	$oP = new CLIPage("iTop - Database Backup");
}
else
{
	$oP = new WebPage("iTop - Database Backup");
}

try
{
	utils::UseParamFile();
}
catch(Exception $e)
{
	ExitError($oP, $e->GetMessage());
}

if (utils::IsModeCLI())
{
	$oP->p(date('Y-m-d H:i:s')." - running backup utility");
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd');
	$bDownloadBackup = false;
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		ExitError($oP, "Access restricted or wrong credentials ('$sAuthUser')");
	}
}
else
{
	require_once(APPROOT.'application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	$bDownloadBackup = utils::ReadParam('download', false);
}

if (!UserRights::IsAdministrator())
{
	ExitError($oP, "Access restricted to administors");
}

if (CheckParam('?') || CheckParam('h') || CheckParam('help'))
{
	Usage($oP);
	$oP->output();
	exit;
}


$sDefaultBackupFileName = SetupUtils::GetTmpDir().'/'."__DB__-%Y-%m-%d";
$sBackupFile =  utils::ReadParam('backup_file', $sDefaultBackupFileName, true, 'raw_data');

// Interpret strftime specifications (like %Y) and database placeholders
$oBackup = new MyDBBackup($oP);
$oBackup->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
$sBackupFile = $oBackup->MakeName($sBackupFile);
$sZipArchiveFile = $sBackupFile.'.zip';

$bSimulate = utils::ReadParam('simulate', false, true);
$res = false;
if ($bSimulate)
{
	$oP->p("Simulate: would create file '$sZipArchiveFile'");
}
elseif (MetaModel::GetConfig()->Get('demo_mode'))
{
	$oP->p("Sorry, iTop is in demonstration mode: the feature is disabled");
}
else
{
	$oBackup->CreateZip($sZipArchiveFile);
}
if ($res && $bDownloadBackup)
{
	$oBackup->DownloadBackup($sZipArchiveFile);
}
$oP->output();
?>

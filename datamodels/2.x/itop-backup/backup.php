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

use Combodo\iTop\Application\WebPage\Page;

if (!defined('APPROOT'))
{
	if (file_exists(__DIR__.'/../../approot.inc.php'))
	{
		require_once __DIR__.'/../../approot.inc.php';   // When in env-xxxx folder
	}
	else
	{
		require_once __DIR__.'/../../../approot.inc.php';   // When in datamodels/x.x folder
	}
}
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'core/log.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');

class MyDBBackup extends DBBackup
{
	/** @var Page used to send log */
	protected $oPage;

	protected function LogInfo($sMsg)
	{
		$this->oPage->p($sMsg);
	}

	protected function LogError($sMsg)
	{
		$this->oPage->p('Error: '.$sMsg);
		ToolsLog::Error($sMsg);
	}

	public function __construct($oPage)
	{
		$this->oPage = $oPage;
		parent::__construct();
	}
}

function GetOperationName() {
	return "iTop - Database Backup";
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
	$oP->p('backup_file [optional]: name of the file to store the backup into. Follows the PHP strftime() format spec (https://www.php.net/manual/fr/function.strftime.php). The following placeholders are available: __HOST__, __DB__, __SUBNAME__');
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

/**
 * @param Page $oP
 *
 * @throws \DictExceptionUnknownLanguage
 * @throws \OQLException
 */
function ExecuteMainOperation($oP){

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

	$bSimulate = utils::ReadParam('simulate', false, true);
	$res = false;
	if ($bSimulate)
	{
		$oP->p("Simulate: would create file '$sBackupFile'");
	}
	elseif (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->p("Sorry, iTop is in demonstration mode: the feature is disabled");
	}
	else
	{
		$oBackup->CreateCompressedBackup($sBackupFile);
	}
	if ($res && $bDownloadBackup)
	{
		$oBackup->DownloadBackup($sBackupFile);
	}
}

require_once(__DIR__.'/common.cli-execution.php');

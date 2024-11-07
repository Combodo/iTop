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
require_once(__DIR__.'/dbrestore.class.inc.php');

/**
 * @since 3.0.0 N°4227 new class to handle iTop restore manually via a CLI command
 */
class MyCliRestore extends DBRestore
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

function Usage($oP)
{
	$oP->p('Restore an iTop from a backup file');
	$oP->p('Parameters:');
	if (utils::IsModeCLI())
	{
		$oP->p('auth_user: login, must be administrator');
		$oP->p('auth_pwd: ...');
	}
	$oP->p('backup_file [optional]: name of the file to store the backup into. Follows the PHP strftime() (https://www.php.net/manual/fr/function.strftime.php) format spec. The following placeholders are available: __HOST__, __DB__, __SUBNAME__');
	$oP->p('mysql_bindir [optional]: specify the path for mysql executable');

	if (utils::IsModeCLI())
	{
		$oP->p('Example: php -q restore.php --auth_user=admin --auth_pwd=myPassw0rd --backup_file=/tmp/backup.zip');
		$oP->p('Known limitation: the current directory must be the directory of backup.php');
	}
	else
	{
		$oP->p('Example: .../restore.php?backup_file=/tmp/backup.zip');
	}
}

function GetOperationName() {
	return "iTop - iTop Restore";
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
		$oP->p(date('Y-m-d H:i:s')." - running restore utility");
		$sAuthUser = ReadMandatoryParam($oP, 'auth_user');
		$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd');
		$sBackupFile = ReadMandatoryParam($oP, 'backup_file');
		if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
		{
			UserRights::Login($sAuthUser); // Login & set the user's language
		}
		else
		{
			ExitError($oP, "Access restricted or wrong credentials ('$sAuthUser')");
		}

		if (!is_file($sBackupFile) || !is_readable($sBackupFile)){
			ExitError($oP, "Cannot access backup file ('$sBackupFile')");
		}
	}
	else
	{
		require_once(APPROOT.'application/loginwebpage.class.inc.php');
		LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	}

	if (!UserRights::IsAdministrator())
	{
		ExitError($oP, "Access restricted to administrators");
	}

	if (CheckParam('?') || CheckParam('h') || CheckParam('help'))
	{
		Usage($oP);
		$oP->output();
		exit;
	}

	// Interpret strftime() specifications (like %Y) and database placeholders
	$oRestore = new MyCliRestore($oP);
	$oRestore->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));

	if (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->p("Sorry, iTop is in demonstration mode: the feature is disabled");
	}
	else
	{
		$sEnvironment = utils::ReadParam('environment', 'production', false, 'raw_data');
		$oRestore->RestoreFromCompressedBackup($sBackupFile, $sEnvironment);
	}
}

require_once(__DIR__.'/common.cli-execution.php');

<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
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

use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\WebPage;

if (!defined('APPROOT')) require_once(__DIR__.'/../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'core/mutex.class.inc.php');


/**
 * @param WebPage $oPage
 * @param string $sHtmlErrorMessage the whole HTML error, cinluding div/p/...
 * @param int|string $exitCode
 *
 * @uses \die() https://www.php.net/manual/fr/function.die.php
 *
 * @since 2.6.5 2.7.1 N°2989
 */
function DisplayErrorAndDie($oPage, $sHtmlErrorMessage, $exitCode = null)
{
	$oPage->add($sHtmlErrorMessage);
	$oPage->output();

	die($exitCode);
}






$sOperation = utils::ReadParam('operation', '');

$oPage = new AjaxPage('');
$oPage->SetContentType('text/html');



/**
 * Check security
 */
switch ($sOperation)
{
	/**
	 * Can't use normal check methods (DoLogin for ex) as the datamodel can't be loaded here
	 * So we're only using a token generated in the restore_token operation
	 */
	case 'restore_exec':
		IssueLog::Enable(APPROOT.'log/error.log');
		if (utils::GetConfig()->Get('demo_mode')) {
			DisplayErrorAndDie($oPage,
				'<div data-error-stimulus="Error">Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the feature is disabled.</div>');
		}

		$sToken = utils::ReadParam('token', '', false, 'raw_data');
		$sBasePath = APPROOT.'/data/';
		$sTokenFile = $sBasePath.'restore.'.$sToken.'.tok';
		$tokenRealPath = utils::RealPath($sTokenFile, $sBasePath);
		if (($tokenRealPath === false) || (!is_file($tokenRealPath))) {
			IssueLog::Error("ajax.backup.php operation=$sOperation ERROR = inexisting token $sToken");
			$sEscapedToken = utils::HtmlEntities($sToken);
			DisplayErrorAndDie($oPage, "<p>Error: missing token file: '$sEscapedToken'</p>");
		}

		break;

	default:
		require_once(APPROOT.'/application/startup.inc.php');
		require_once(APPROOT.'/application/loginwebpage.class.inc.php');

		LoginWebPage::DoLogin();

		$sTransactionId = utils::ReadParam('transaction_id', '', true, 'transaction_id');
		// the consumer page is not reloaded after download, we need to keep the transaction_id
		$bRemoveTransactionId = ($sOperation !== 'download');
		if (!utils::IsTransactionValid($sTransactionId, $bRemoveTransactionId))
		{
			$sEscapedOperation = utils::HtmlEntities($sOperation);
			DisplayErrorAndDie($oPage, "<div data-error-stimulus=\"Error\">Error: invalid Transaction ID. The operation '$sEscapedOperation' was NOT performed!</div>");
		}

		ApplicationMenu::CheckMenuIdEnabled('BackupStatus');

		if (utils::GetConfig()->Get('demo_mode'))
		{
			DisplayErrorAndDie($oPage, '<div data-error-stimulus="Error">Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the feature is disabled.</div>');
		}
		break;
}


/**
 * Backup from an interactive session
 */
try
{
	switch ($sOperation)
	{
		case 'backup':
			try
			{
				set_time_limit(0);
				$oBB = new BackupExec(APPROOT.'data/backups/manual/', 0 /*iRetentionCount*/);
				$sRes = $oBB->Process(time() + 36000); // 10 hours to complete should be sufficient!
			}
			catch (Exception $e)
			{
				$oPage->p('Error: '.$e->getMessage());
				IssueLog::Error($sOperation.' - '.$e->getMessage());
			}

			$oPage->output();
			break;

		/*
		 * Fix a specific token :
		 *  We can't load the MetaModel because in DBRestore, after restore is done we're launching a compile !
		 *  So as LoginWebPage::DoLogin needs a loaded DataModel, we can't use it
		 *  Also, we can't use \utils::IsTransactionValid as it uses \MetaModel::GetConfig
		 *  As a result we're setting a token file to make sure the restore is called by an authenticated user with the correct rights !
		 */
		case 'restore_get_token':
			$oPage = new JsonPage();
			$oPage->SetOutputDataOnly(true);

			$sEnvironment = utils::ReadParam('environment', 'production', false, 'raw_data');
			$oRestoreMutex = new iTopMutex('restore.'.$sEnvironment);
			if ($oRestoreMutex->IsLocked())
			{
				DisplayErrorAndDie($oPage, '<p>'.Dict::S('bkp-restore-running').'</p>');
			}

			$sFile = utils::ReadParam('file', '', false, 'raw_data');
			$sToken = str_replace(' ', '', (string)microtime()).$sTransactionId;
			$sTokenFile = APPROOT.'/data/restore.'.$sToken.'.tok';
			file_put_contents($sTokenFile, $sFile);

			$oPage->SetData(['token' => $sToken]);
			$oPage->output();
			break;

		/*
		 * We can't call \LoginWebPage::DoLogin because DBRestore will do a compile after restoring the DB
		 * Authentication is checked with a token file (see $sOperation='restore_get_token')
		 */
		case 'restore_exec':
			require_once(APPROOT."setup/runtimeenv.class.inc.php");
			require_once(APPROOT.'/application/utils.inc.php');
			require_once(APPROOT.'/setup/backup.class.inc.php');
			require_once(__DIR__.'/dbrestore.class.inc.php');

			$sEnvironment = utils::ReadParam('environment', 'production', false, 'raw_data');
			try
			{
				set_time_limit(0);

				// Get the file and destroy the token (single usage)
				$sFile = file_get_contents($tokenRealPath);

				// Loading config file : we don't have the MetaModel but we have the current env !
				$sConfigFilePath = utils::GetConfigFilePath($sEnvironment);
				$oItopConfig = new Config($sConfigFilePath, true);
				$sMySQLBinDir = $oItopConfig->GetModuleSetting('itop-backup', 'mysql_bindir', '');

				$oDBRS = new DBRestore($oItopConfig);
				$oDBRS->SetMySQLBinDir($sMySQLBinDir);

				$sBackupDir = APPROOT.'data/backups/';
				$sBackupFile = $sBackupDir.$sFile;
				$sRes = $oDBRS->RestoreFromCompressedBackup($sBackupFile, $sEnvironment);

				IssueLog::Info('Backup Restore - Done, releasing the LOCK');
			}
			catch (Exception $e)
			{
				$oPage->p('Error: '.$e->getMessage());
				IssueLog::Error($sOperation.' - '.$e->getMessage());
			}
			finally
			{
				unlink($tokenRealPath);
			}

			$oPage->output();
			break;

		case 'download':
			while (ob_get_level() > 0) {
				ob_end_clean();
			}
			$sFile = utils::ReadParam('file', '', false, 'raw_data');
			$oBackup = new DBBackupScheduled();
			$sBackupDir = APPROOT.'data/backups/';
			$sBackupFilePath = utils::RealPath($sBackupDir.$sFile, $sBackupDir);
			if ($sBackupFilePath === false)
			{
				throw new CoreUnexpectedValue('Invalid file path');
			}
			$oBackup->DownloadBackup($sBackupFilePath);
			break;
	}
}
catch (Exception $e)
{
	IssueLog::Error($sOperation.' - '.$e->getMessage());
}


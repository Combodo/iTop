<?php

// Copyright (C) 2024 Combodo SAS
//
// This file is part of iTop.
//
// iTop is free software; you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// iTop is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Handles various ajax requests - called through pages/exec.php
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\HubConnector\Controller\HubController;

require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'core/log.class.inc.php');
IssueLog::Enable(APPROOT.'log/error.log');

require_once(__DIR__.'/src/Controller/HubController.php');

try {
	SetupUtils::ExitMaintenanceMode(false); // Reset maintenance mode in case of problem

	utils::PushArchiveMode(false);

	ini_set('max_execution_time', max(3600, ini_get('max_execution_time'))); // Under Windows SQL/backup operations are part of the PHP timeout and require extra time
	ini_set('display_errors', 1); // Make sure that fatal errors remain visible from the end-user

	// Most of the ajax calls are done without the MetaModel being loaded
	// Therefore, the language must be passed as an argument,
	// and the dictionnaries be loaded here
	$sLanguage = utils::ReadParam('language', '');
	if ($sLanguage != '') {
		foreach (glob(APPROOT.'env-production/dictionaries/*.dict.php') as $sFilePath) {
			require_once($sFilePath);
		}

		$aLanguages = Dict::GetLanguages();
		if (array_key_exists($sLanguage, $aLanguages)) {
			Dict::SetUserLanguage($sLanguage);
		}
	}
	$sOperation = utils::ReadParam('operation', '');
	switch ($sOperation) {
		case 'check_before_backup':
			require_once(APPROOT.'/application/startup.inc.php');
			require_once(APPROOT.'/application/loginwebpage.class.inc.php');
			LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

			$sDBBackupPath = utils::GetDataPath().'backups/manual';
			$aChecks = SetupUtils::CheckBackupPrerequisites($sDBBackupPath);
			$bFailed = false;
			foreach ($aChecks as $oCheckResult) {
				if ($oCheckResult->iSeverity == CheckResult::ERROR) {
					$bFailed = true;
					HubController::GetInstance()->ReportError($oCheckResult->sLabel, -2);
				}
			}
			if (!$bFailed) {
				// Continue the checks
				$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
				if ($fFreeSpace !== false) {
					$sMessage = Dict::Format('iTopHub:BackupFreeDiskSpaceIn', SetupUtils::HumanReadableSize($fFreeSpace), dirname($sDBBackupPath));
					HubController::GetInstance()->ReportSuccess($sMessage);
				} else {
					HubController::GetInstance()->ReportError(Dict::S('iTopHub:FailedToCheckFreeDiskSpace'), -1);
				}
			}
			break;

		case 'do_backup':
			HubController::GetInstance()->LaunchBackup();
			break;

		case 'compile':
			HubController::GetInstance()->LaunchCompile();
			break;

		case 'move_to_production':
			HubController::GetInstance()->LaunchDeploy();
			break;

		default:
			HubController::GetInstance()->ReportError("Invalid operation: '$sOperation'", -1);
	}
} catch (Exception $e) {
	SetupLog::Error(get_class($e).': '.Dict::S('iTopHub:ConfigurationSafelyReverted')."\n".$e->getMessage());
	SetupLog::Error('Debug trace: '.$e->getTraceAsString());

	utils::PopArchiveMode();

	HubController::GetInstance()->ReportError($e->getMessage(), $e->getCode());
}

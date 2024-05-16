<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license	http://opensource.org/licenses/AGPL-3.0
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
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('EN US', 'English', 'English', array(
	'iTopUpdate:UI:PageTitle' => 'Application Upgrade',
    'itop-core-update:UI:SelectUpdateFile' => 'Application Upgrade',
    'itop-core-update:UI:ConfirmUpdate' => 'Confirm Application Upgrade',
    'itop-core-update:UI:UpdateCoreFiles' => 'Application Upgrading',
	'iTopUpdate:UI:MaintenanceModeActive' => 'The application is currently under maintenance in read-only mode. You have to run a setup to return in normal mode.',
	'itop-core-update:UI:UpdateDone' => 'Application Upgrade',

	'itop-core-update/Operation:SelectUpdateFile/Title' => 'Application Upgrade',
	'itop-core-update/Operation:ConfirmUpdate/Title' => 'Confirm Application Upgrade',
	'itop-core-update/Operation:UpdateCoreFiles/Title' => 'Application Upgrading',
	'itop-core-update/Operation:UpdateDone/Title' => 'Application Upgrade Done',

	'iTopUpdate:UI:SelectUpdateFile' => 'Select an upgrade file to upload',
	'iTopUpdate:UI:CheckUpdate' => 'Verify upgrade file',
	'iTopUpdate:UI:ConfirmInstallFile' => 'You are about to install %1$s',
	'iTopUpdate:UI:DoUpdate' => 'Upgrade',
	'iTopUpdate:UI:CurrentVersion' => 'Current installed version',
	'iTopUpdate:UI:NewVersion' => 'Newly installed version',
    'iTopUpdate:UI:Back' => 'Back',
    'iTopUpdate:UI:Cancel' => 'Cancel',
    'iTopUpdate:UI:Continue' => 'Continue',
	'iTopUpdate:UI:RunSetup' => 'Run Setup',
    'iTopUpdate:UI:WithDBBackup' => 'Database backup',
    'iTopUpdate:UI:WithFilesBackup' => 'Application files backup',
    'iTopUpdate:UI:WithoutBackup' => 'No backup is planned',
    'iTopUpdate:UI:Backup' => 'Backup generated before update',
	'iTopUpdate:UI:DoFilesArchive' => 'Archive application files',
	'iTopUpdate:UI:UploadArchive' => 'Select a package to upload',
	'iTopUpdate:UI:ServerFile' => 'Path of a package already on the server',
	'iTopUpdate:UI:WarningReadOnlyDuringUpdate' => 'During the upgrade, the application will be read-only.',

    'iTopUpdate:UI:Status' => 'Status',
    'iTopUpdate:UI:Action' => 'Update',
    'iTopUpdate:UI:Setup' => ITOP_APPLICATION_SHORT.' Setup',
    'iTopUpdate:UI:History' => 'Versions History',
    'iTopUpdate:UI:Progress' => 'Progress of the upgrade',

    'iTopUpdate:UI:DoBackup:Label' => 'Backup files and database',
    'iTopUpdate:UI:DoBackup:Warning' => 'Backup is not recommended due to limited available disk space',

    'iTopUpdate:UI:DiskFreeSpace' => 'Disk free space',
    'iTopUpdate:UI:ItopDiskSpace' => ITOP_APPLICATION_SHORT.' disk space',
    'iTopUpdate:UI:DBDiskSpace' => 'Database disk space',
	'iTopUpdate:UI:FileUploadMaxSize' => 'File upload max size',

	'iTopUpdate:UI:PostMaxSize' => 'PHP ini value post_max_size: %1$s',
	'iTopUpdate:UI:UploadMaxFileSize' => 'PHP ini value upload_max_filesize: %1$s',

    'iTopUpdate:UI:CanCoreUpdate:Loading' => 'Checking files',
    'iTopUpdate:UI:CanCoreUpdate:Error' => 'Checking files failed (%1$s)',
    'iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist' => 'Checking files failed (File not exist %1$s)',
    'iTopUpdate:UI:CanCoreUpdate:Failed' => 'Checking files failed',
    'iTopUpdate:UI:CanCoreUpdate:Yes' => 'Application can be updated',
	'iTopUpdate:UI:CanCoreUpdate:No' => 'Application cannot be updated: %1$s',
	'iTopUpdate:UI:CanCoreUpdate:Warning' => 'Warning: application update can fail: %1$s',
	'iTopUpdate:UI:CannotUpdateUseSetup' => '<b>Some modified files were detected</b>, a partial update cannot be executed.</br>Follow the <a target="_blank" href="%2$s"> procedure</a> in order to manually upgrade your iTop. You must use the <a href="%1$s">setup</a> to update the application.',
	'iTopUpdate:UI:CheckInProgress'=>'Please wait during integrity check',



	'iTopUpdate:UI:SetupLaunch' => 'Launch '.ITOP_APPLICATION_SHORT.' Setup',
	'iTopUpdate:UI:SetupLaunchConfirm' => 'This will launch '.ITOP_APPLICATION_SHORT.' setup, are you sure?',

	// Setup Messages
    'iTopUpdate:UI:SetupMessage:Ready' => 'Ready to start',
	'iTopUpdate:UI:SetupMessage:EnterMaintenance' => 'Entering maintenance mode',
	'iTopUpdate:UI:SetupMessage:Backup' => 'Database backup',
	'iTopUpdate:UI:SetupMessage:FilesArchive' => 'Archive application files',
    'iTopUpdate:UI:SetupMessage:CopyFiles' => 'Copy new version files',
	'iTopUpdate:UI:SetupMessage:CheckCompile' => 'Check application upgrade',
	'iTopUpdate:UI:SetupMessage:Compile' => 'Upgrade application',
	'iTopUpdate:UI:SetupMessage:UpdateDatabase' => 'Upgrade database',
	'iTopUpdate:UI:SetupMessage:ExitMaintenance' => 'Exiting maintenance mode',
    'iTopUpdate:UI:SetupMessage:UpdateDone' => 'Upgrade completed',

	// Errors
	'iTopUpdate:Error:MissingFunction' => 'Impossible to start upgrade, missing function',
	'iTopUpdate:Error:MissingFile' => 'Missing file: %1$s',
	'iTopUpdate:Error:CorruptedFile' => 'File %1$s is corrupted',
    'iTopUpdate:Error:BadFileFormat' => 'Upgrade file is not a zip file',
    'iTopUpdate:Error:BadFileContent' => 'Upgrade file is not an application archive',
    'iTopUpdate:Error:BadItopProduct' => 'Upgrade file is not compatible with your application',
	'iTopUpdate:Error:Copy' => 'Error, cannot copy \'%1$s\' to \'%2$s\'',
    'iTopUpdate:Error:FileNotFound' => 'File not found',
    'iTopUpdate:Error:NoFile' => 'No file provided',
	'iTopUpdate:Error:InvalidToken' => 'Invalid token',
	'iTopUpdate:Error:UpdateFailed' => 'Upgrade failed ',
	'iTopUpdate:Error:FileUploadMaxSizeTooSmall' => 'The upload max size seems too small for update. Please change the PHP configuration.',

	'iTopUpdate:UI:RestoreArchive' => 'You can restore your application from the archive \'%1$s\'',
	'iTopUpdate:UI:RestoreBackup' => 'You can restore the database from \'%1$s\'',
	'iTopUpdate:UI:UpdateDone' => 'Upgrade successful',
	'Menu:iTopUpdate' => 'Application upgrade',
	'Menu:iTopUpdate+' => 'Application upgrade',

    // Missing itop entries
    'Class:ModuleInstallation/Attribute:installed' => 'Installed on',
    'Class:ModuleInstallation/Attribute:name' => 'Name',
    'Class:ModuleInstallation/Attribute:version' => 'Version',
    'Class:ModuleInstallation/Attribute:comment' => 'Comment',
));



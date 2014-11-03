<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 Combodo
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(

	'bkp-backup-running' => 'A backup is running. Please wait...',
	'bkp-restore-running' => 'A restore is running. Please wait...',

	'Menu:BackupStatus' => 'Scheduled Backups',
	'bkp-status-title' => 'Scheduled Backups',
	'bkp-status-checks' => 'Settings and checks',
	'bkp-mysqldump-ok' => 'mysqldump is present: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump could not be found: %1$s - Please make sure it is installed and in the path, or edit the configuration file to tune mysql_bindir.',
	'bkp-mysqldump-issue' => 'mysqldump could not be executed (retcode=%1$d): Please make sure it is installed and in the path, or edit the configuration file to tune mysql_bindir',
	'bkp-missing-dir' => 'The target directory %1$s count not be found',
	'bkp-free-disk-space' => '<b>%1$s free</b> in %2$s',
	'bkp-dir-not-writeable' => '%1$s is not writeable',
	'bkp-wrong-format-spec' => 'The current specification to format the file names is wrong (%1$s). A default specification will apply: %2$s',
	'bkp-name-sample' => 'Backup files are named depending on DB identifiers, date and time. Example: %1$s',
	'bkp-week-days' => 'Backups will occur <b>every %1$s at %2$s</b>',
	'bkp-retention' => 'At most <b>%1$d backup files will be kept</b> in the target directory.',
	'bkp-next-to-delete' => 'Will be deleted when the next backup occurs (see the setting "retention_count")',
	'bkp-table-file' => 'File', 
	'bkp-table-file+' => 'Only files having the extension .zip are considered as being backup files',
	'bkp-table-size' => 'Size',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Actions',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Scheduled backups',
	'bkp-status-backups-manual' => 'Manual backups',
	'bkp-status-backups-none' => 'No backup yet',
	'bkp-next-backup' => 'The next backup will occur on <b>%1$s</b> (%2$s) at %3$s',
	'bkp-button-backup-now' => 'Backup now!',
	'bkp-button-restore-now' => 'Restore!',
	'bkp-confirm-backup' => 'Please confirm that you do request the backup to occur right now.',
	'bkp-confirm-restore' => 'Please confirm that you do want to restore the backup %1$s.',
	'bkp-wait-backup' => 'Please wait for the backup to complete...',
	'bkp-wait-restore' => 'Please wait for the restore to complete...',
	'bkp-success-restore' => 'Restore successfully completed.',
));
?>

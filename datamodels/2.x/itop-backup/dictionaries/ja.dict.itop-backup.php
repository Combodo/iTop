<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('JA JP', 'Japanese', '日本語', [
	'Menu:BackupStatus' => 'Backups~~',
	'bkp-backup-running' => 'A backup is running. Please wait...~~',
	'bkp-button-backup-now' => 'Backup now!~~',
	'bkp-button-restore-now' => 'Restore!~~',
	'bkp-confirm-backup' => 'Please confirm that you do request the backup to occur right now.~~',
	'bkp-confirm-restore' => 'Please confirm that you do want to restore the backup %1$s.~~',
	'bkp-dir-not-writeable' => '%1$s is not writeable~~',
	'bkp-free-disk-space' => '<b>%1$s free</b> in <code>%2$s</code>~~',
	'bkp-missing-dir' => 'The target directory <code>%1$s</code> could not be found~~',
	'bkp-mysqldump-issue' => 'mysqldump could not be executed (retcode=%1$d): Please make sure it is installed and in the path, or edit the configuration file to tune mysql_bindir~~',
	'bkp-mysqldump-notfound' => 'mysqldump could not be found: %1$s - Please make sure it is installed and in the path, or edit the configuration file to tune mysql_bindir.~~',
	'bkp-mysqldump-ok' => 'mysqldump is present: %1$s~~',
	'bkp-name-sample' => 'Backup files are named depending on DB identifiers, date and time. Example: %1$s~~',
	'bkp-next-backup' => 'The next backup will occur on <b>%1$s</b> (%2$s) at %3$s.~~',
	'bkp-next-backup-unknown' => 'The next backup is <b>not scheduled</b> yet.~~',
	'bkp-next-to-delete' => 'Will be deleted when the next backup occurs (see the setting "retention_count")~~',
	'bkp-restore-running' => 'A restore is running. Please wait...~~',
	'bkp-retention' => 'At most <b>%1$d backup files will be kept</b> in the target directory.~~',
	'bkp-status-backups-auto' => 'Scheduled backups~~',
	'bkp-status-backups-manual' => 'Manual backups~~',
	'bkp-status-backups-none' => 'No backup yet~~',
	'bkp-status-checks' => 'Settings and checks~~',
	'bkp-status-title' => 'Backups~~',
	'bkp-success-restore' => 'Restore successfully completed.~~',
	'bkp-table-actions' => 'Actions~~',
	'bkp-table-actions+' => '~~',
	'bkp-table-file' => 'File~~',
	'bkp-table-file+' => 'Only files having the extension .zip are considered as being backup files~~',
	'bkp-table-size' => 'Size~~',
	'bkp-table-size+' => '~~',
	'bkp-wait-backup' => 'Please wait for the backup to complete...~~',
	'bkp-wait-restore' => 'Please wait for the restore to complete...~~',
	'bkp-week-days' => 'Backups will occur <b>every %1$s at %2$s</b>~~',
	'bkp-wrong-format-spec' => 'The current specification to format the file names is wrong (%1$s). A default specification will apply: %2$s~~',
]);

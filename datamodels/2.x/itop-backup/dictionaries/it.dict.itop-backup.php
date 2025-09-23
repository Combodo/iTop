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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'bkp-backup-running' => 'Un backup è in corso. Attendere prego...',
	'bkp-restore-running' => 'Un ripristino è in corso. Attendere prego...',
	'Menu:BackupStatus' => 'Backup programmati',
	'bkp-status-title' => 'Backup Programmati',
	'bkp-status-checks' => 'Impostazioni e controlli',
	'bkp-mysqldump-ok' => 'mysqldump è presente: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump non trovato: %1$s - Assicurarsi che sia installato e nel percorso, o modificare il file di configurazione per regolare mysql_bindir.',
	'bkp-mysqldump-issue' => 'mysqldump non può essere eseguito (codice di ritorno=%1$d): Assicurarsi che sia installato e nel percorso, o modificare il file di configurazione per regolare mysql_bindir',
	'bkp-missing-dir' => 'La directory di destinazione <code>%1$s</code> non è stata trovata',
	'bkp-free-disk-space' => '<b>%1$s libero</b> in <code>%2$s</code>',
	'bkp-dir-not-writeable' => '%1$s non è scrivibile',
	'bkp-wrong-format-spec' => 'La specifica attuale per formattare i nomi dei file è sbagliata (%1$s). Verrà applicata una specifica predefinita: %2$s',
	'bkp-name-sample' => 'I file di backup sono denominati in base agli identificatori del DB, data e ora. Esempio: %1$s',
	'bkp-week-days' => 'I backup avverranno <b>ogni %1$s alle %2$s</b>',
	'bkp-retention' => 'Al massimo <b>%1$d file di backup saranno mantenuti</b> nella directory di destinazione.',
	'bkp-next-to-delete' => 'Sarà cancellato al prossimo avvenimento del backup (vedi impostazione "retention_count")',
	'bkp-table-file' => 'File',
	'bkp-table-file+' => 'Solo i file con estensione .zip sono considerati file di backup',
	'bkp-table-size' => 'Dimensione',
	'bkp-table-size+' => '~~',
	'bkp-table-actions' => 'Azioni',
	'bkp-table-actions+' => '~~',
	'bkp-status-backups-auto' => 'Backup programmati',
	'bkp-status-backups-manual' => 'Backup manuali',
	'bkp-status-backups-none' => 'Nessun backup finora',
	'bkp-next-backup' => 'Il prossimo backup avverrà il <b>%1$s</b> (%2$s) alle %3$s',
	'bkp-next-backup-unknown' => 'Il prossimo backup <b>non è ancora programmato</b>.',
	'bkp-button-backup-now' => 'Esegui backup ora!',
	'bkp-button-restore-now' => 'Ripristina!',
	'bkp-confirm-backup' => 'Confermare di voler eseguire il backup ora.',
	'bkp-confirm-restore' => 'Confermare di voler ripristinare il backup %1$s.',
	'bkp-wait-backup' => 'Attendere il completamento del backup...',
	'bkp-wait-restore' => 'Attendere il completamento del ripristino...',
	'bkp-success-restore' => 'Ripristino completato con successo.',
));

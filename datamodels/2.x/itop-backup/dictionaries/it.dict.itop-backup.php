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
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Menu:BackupStatus' => 'Backup programmati',
	'bkp-backup-running' => 'Un backup è in corso. Attendere prego...',
	'bkp-button-backup-now' => 'Esegui backup ora!',
	'bkp-button-restore-now' => 'Ripristina!',
	'bkp-confirm-backup' => 'Confermare di voler eseguire il backup ora.',
	'bkp-confirm-restore' => 'Confermare di voler ripristinare il backup %1$s.',
	'bkp-dir-not-writeable' => '%1$s non è scrivibile',
	'bkp-free-disk-space' => '<b>%1$s libero</b> in <code>%2$s</code>',
	'bkp-missing-dir' => 'La directory di destinazione <code>%1$s</code> non è stata trovata',
	'bkp-mysqldump-issue' => 'mysqldump non può essere eseguito (codice di ritorno=%1$d): Assicurarsi che sia installato e nel percorso, o modificare il file di configurazione per regolare mysql_bindir',
	'bkp-mysqldump-notfound' => 'mysqldump non trovato: %1$s - Assicurarsi che sia installato e nel percorso, o modificare il file di configurazione per regolare mysql_bindir.',
	'bkp-mysqldump-ok' => 'mysqldump è presente: %1$s',
	'bkp-name-sample' => 'I file di backup sono denominati in base agli identificatori del DB, data e ora. Esempio: %1$s',
	'bkp-next-backup' => 'Il prossimo backup avverrà il <b>%1$s</b> (%2$s) alle %3$s',
	'bkp-next-backup-unknown' => 'Il prossimo backup <b>non è ancora programmato</b>.',
	'bkp-next-to-delete' => 'Sarà cancellato al prossimo avvenimento del backup (vedi impostazione "retention_count")',
	'bkp-restore-running' => 'Un ripristino è in corso. Attendere prego...',
	'bkp-retention' => 'Al massimo <b>%1$d file di backup saranno mantenuti</b> nella directory di destinazione.',
	'bkp-status-backups-auto' => 'Backup programmati',
	'bkp-status-backups-manual' => 'Backup manuali',
	'bkp-status-backups-none' => 'Nessun backup finora',
	'bkp-status-checks' => 'Impostazioni e controlli',
	'bkp-status-title' => 'Backup Programmati',
	'bkp-success-restore' => 'Ripristino completato con successo.',
	'bkp-table-actions' => 'Azioni',
	'bkp-table-actions+' => '~~',
	'bkp-table-file' => 'File',
	'bkp-table-file+' => 'Solo i file con estensione .zip sono considerati file di backup',
	'bkp-table-size' => 'Dimensione',
	'bkp-table-size+' => '~~',
	'bkp-wait-backup' => 'Attendere il completamento del backup...',
	'bkp-wait-restore' => 'Attendere il completamento del ripristino...',
	'bkp-week-days' => 'I backup avverranno <b>ogni %1$s alle %2$s</b>',
	'bkp-wrong-format-spec' => 'La specifica attuale per formattare i nomi dei file è sbagliata (%1$s). Verrà applicata una specifica predefinita: %2$s',
]);

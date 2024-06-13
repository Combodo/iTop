<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author ITOMIG GmbH <martin.raenker@itomig.de>
 *
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Menu:BackupStatus' => 'Geplante Backups',
	'bkp-backup-running' => 'Backup wird durchgeführt. Bitte warten ...',
	'bkp-button-backup-now' => 'Starte Backup',
	'bkp-button-restore-now' => 'Wiederherstellen!',
	'bkp-confirm-backup' => 'Bitte bestätigen Sie, dass Sie jetzt ein Backup erstellen wollen.',
	'bkp-confirm-restore' => 'Bitte bestätigen Sie, dass Sie mit Backup %1$s eine Wiederherstellung durchführen wollen.',
	'bkp-dir-not-writeable' => '%1$s ist nicht beschreibbar',
	'bkp-free-disk-space' => '<b>%1$s frei</b> in <code>%2$s</code>',
	'bkp-missing-dir' => 'Zielverzeichniss <code>%1$s</code> nicht gefunden',
	'bkp-mysqldump-issue' => 'mysqldump konnte nicht ausgeführt werden (retcode=%1$d): Stellen Sie sicher, dass das Programm installiert und im angegebenen Pfad verfügbar ist, oder editieren Sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-mysqldump-notfound' => 'mysqldump wurde nicht gefunden: %1$s - Stellen Sie sicher, dass das Programm installiert und im angegebenen Pfad verfügbar ist, oder editieren Sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-mysqldump-ok' => 'mysqldump ist vorhanden: %1$s',
	'bkp-name-sample' => 'Backup-Dateien werden abhängig von Datum, Zeit und Datenbank-Identifier erstellt. Beispiel: %1$s',
	'bkp-next-backup' => 'Das nächste Backup wird am <b>%1$s</b> (%2$s) um %3$s durchgeführt',
	'bkp-next-backup-unknown' => 'Das nächste Backup ist <b>noch nicht geplant</b>.',
	'bkp-next-to-delete' => 'Wird gelöscht, wenn das nächste Backup angelegt wird (unter Einstellungen "Menge vorhalten")',
	'bkp-restore-running' => 'Wiederherstellung läuft. Bitte warten ...',
	'bkp-retention' => 'Mindestens <b>%1$d Backups werden im Zielverzeichnis vorgehalten</b>',
	'bkp-status-backups-auto' => 'Geplante Backups',
	'bkp-status-backups-manual' => 'Manuelle Backups',
	'bkp-status-backups-none' => 'Kein Backup vorhanden',
	'bkp-status-checks' => 'Einstellungen und Prüfungen',
	'bkp-status-title' => 'Geplante Backups',
	'bkp-success-restore' => 'Wiederherstellung erfolgreich.',
	'bkp-table-actions' => 'Aktionen',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Datei',
	'bkp-table-file+' => 'Nur Dateien mit der Endung .zip werden als Backup-Dateien berücksichtigt.',
	'bkp-table-size' => 'Größe',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Bitte warten, bis das Backup abgeschlossen ist ...',
	'bkp-wait-restore' => 'Bitte warten, bis die Wiederherstellung abgeschlossen ist ...',
	'bkp-week-days' => 'Backups werden <b>jeden %1$s um %2$s durchgeführt</b>',
	'bkp-wrong-format-spec' => 'Die verwendete Definition zur Formatierung von Dateinamen ist nicht korrekt (%1$s). Die Standard-Definition %2$s wird verwendet',
]);

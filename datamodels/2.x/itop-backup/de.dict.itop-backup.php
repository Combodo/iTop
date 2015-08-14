<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 Combodo
 * @license     http://opensource.org/licenses/AGPL-3.0
 
  * @author      Robert Jaehne <robert.jaehne@itomig.de>
  * @author		  David M. Gümbel <david.guembel@itomig.de>
 
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(

	'bkp-backup-running' => 'Backup wird durchgeführt. Bitte warten ...',
	'bkp-restore-running' => 'Wiederherstellung läuft. Bitte warten ...',

	'Menu:BackupStatus' => 'Geplante Backups',
	'bkp-status-title' => 'Geplante Backups',
	'bkp-status-checks' => 'Einstellungen und Prüfungen',
	'bkp-mysqldump-ok' => 'mysqldump ist vorhanden: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump wurde nicht gefunden: %1$s - Stellen sie sicher, das er eingespielt und im Pfad verfügbar ist oder editieren sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-mysqldump-issue' => 'mysqldump konnte nicht eingespielt werden (retcode=%1$d): Stellen sie sicher, das es installiert und im Pfad verfügbar ist oder editieren sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-missing-dir' => 'Zielverzeichniss %1$s nicht gefunden',
	'bkp-free-disk-space' => '<b>%1$s frei</b> in %2$s',
	'bkp-dir-not-writeable' => '%1$s ist nicht schreibbar',
	'bkp-wrong-format-spec' => 'Die verwendete Definition zur Formatierung von Dateinamen ist nicht korrekt (%1$s). Die Standard-Definition %2$s wird verwendet',
	'bkp-name-sample' => 'Backup-Dateien werden abhängig von Datum, Zeit und Datenbank-Identifier erstellt. Beispiel: %1$s',
	'bkp-week-days' => 'Backups werden <b>jeden %1$s um %2$s durchgeführt</b>',
	'bkp-retention' => 'Mindestens <b>%1$d Backups werden im Zielverzeichniss vorgehalten</b>',
	'bkp-next-to-delete' => 'Wird gelöscht, wenn das nächste Backup angelegt wird (unter Einstellungen "Menge vorhalten")',
	'bkp-table-file' => 'Datei', 
	'bkp-table-file+' => 'Nur Dateien mit der Endung .zip werden als Backup-Dateien berücksichtigt.',
	'bkp-table-size' => 'Grösse',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Aktionen',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Geplante Backups',
	'bkp-status-backups-manual' => 'Manuelle Backups',
	'bkp-status-backups-none' => 'Kein Backup vorhanden',
	'bkp-next-backup' => 'Das nächste Backup wird am <b>%1$s</b> (%2$s) um %3$s durchgeführt',
	'bkp-button-backup-now' => 'Starte Backup',
	'bkp-button-restore-now' => 'Wiederherstellen!',
	'bkp-confirm-backup' => 'Bitte bestätigen sie, dass sie jetzt ein Backup erstellen wollen now.',
	'bkp-confirm-restore' => 'Bitte bestätigen sie, dass sie mit Backup %1$s eine Wiederherstellung durchführen wollen.',
	'bkp-wait-backup' => 'Bitte warten, bis das Backup abgeschlossen ist ...',
	'bkp-wait-restore' => 'Bitte warten, bis die Wiederherstellung abgeschlossen ist ...',
	'bkp-success-restore' => 'Wiederherstellung erfolgreich.',
));

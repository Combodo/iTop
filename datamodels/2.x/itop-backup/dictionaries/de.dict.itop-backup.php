<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2021 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(

	'bkp-backup-running' => 'Backup wird durchgeführt. Bitte warten ...',
	'bkp-restore-running' => 'Wiederherstellung läuft. Bitte warten ...',

	'Menu:BackupStatus' => 'Geplante Backups',
	'bkp-status-title' => 'Geplante Backups',
	'bkp-status-checks' => 'Einstellungen und Prüfungen',
	'bkp-mysqldump-ok' => 'mysqldump ist vorhanden: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump wurde nicht gefunden: %1$s - Stellen Sie sicher, dass das Programm installiert und im angegebenen Pfad verfügbar ist, oder editieren Sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-mysqldump-issue' => 'mysqldump konnte nicht ausgeführt werden (retcode=%1$d): Stellen Sie sicher, dass das Programm installiert und im angegebenen Pfad verfügbar ist, oder editieren Sie die Konfigurationsdatei um das MySQL bindir anzupassen.',
	'bkp-missing-dir' => 'Zielverzeichniss <code>%1$s</code> nicht gefunden',
	'bkp-free-disk-space' => '<b>%1$s frei</b> in <code>%2$s</code>',
	'bkp-dir-not-writeable' => '%1$s ist nicht beschreibbar',
	'bkp-wrong-format-spec' => 'Die verwendete Definition zur Formatierung von Dateinamen ist nicht korrekt (%1$s). Die Standard-Definition %2$s wird verwendet',
	'bkp-name-sample' => 'Backup-Dateien werden abhängig von Datum, Zeit und Datenbank-Identifier erstellt. Beispiel: %1$s',
	'bkp-week-days' => 'Backups werden <b>jeden %1$s um %2$s durchgeführt</b>',
	'bkp-retention' => 'Mindestens <b>%1$d Backups werden im Zielverzeichnis vorgehalten</b>',
	'bkp-next-to-delete' => 'Wird gelöscht, wenn das nächste Backup angelegt wird (unter Einstellungen "Menge vorhalten")',
	'bkp-table-file' => 'Datei',
	'bkp-table-file+' => 'Nur Dateien mit der Endung .zip werden als Backup-Dateien berücksichtigt.',
	'bkp-table-size' => 'Größe',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Aktionen',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Geplante Backups',
	'bkp-status-backups-manual' => 'Manuelle Backups',
	'bkp-status-backups-none' => 'Kein Backup vorhanden',
	'bkp-next-backup' => 'Das nächste Backup wird am <b>%1$s</b> (%2$s) um %3$s durchgeführt',
	'bkp-next-backup-unknown' => 'Das nächste Backup ist <b>noch nicht geplant</b>.',
	'bkp-button-backup-now' => 'Starte Backup',
	'bkp-button-restore-now' => 'Wiederherstellen!',
	'bkp-confirm-backup' => 'Bitte bestätigen Sie, dass Sie jetzt ein Backup erstellen wollen.',
	'bkp-confirm-restore' => 'Bitte bestätigen Sie, dass Sie mit Backup %1$s eine Wiederherstellung durchführen wollen.',
	'bkp-wait-backup' => 'Bitte warten, bis das Backup abgeschlossen ist ...',
	'bkp-wait-restore' => 'Bitte warten, bis die Wiederherstellung abgeschlossen ist ...',
	'bkp-success-restore' => 'Wiederherstellung erfolgreich.',
));

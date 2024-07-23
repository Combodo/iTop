<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */

/**
 * @author Thomas Casteleyn <thomas.casteleyn@super-visions.com>
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Menu:BackupStatus' => 'Geplande backups',
	'bkp-backup-running' => 'Er wordt een backup gemaakt. Even geduld...',
	'bkp-button-backup-now' => 'Maak nu een backup',
	'bkp-button-restore-now' => 'Herstel',
	'bkp-confirm-backup' => 'Bevestig dat de backup nu gemaakt mag worden.',
	'bkp-confirm-restore' => 'Bevestig dat je deze backup wil herstellen: %1$s.',
	'bkp-dir-not-writeable' => 'Geen schrijfrechten op %1$s',
	'bkp-free-disk-space' => '<b>%1$s vrij</b> in <code>%2$s</code>',
	'bkp-missing-dir' => 'De doelmap <code>%1$s</code> is niet toegankelijk.',
	'bkp-mysqldump-issue' => 'mysqldump kon niet worden uitgevoerd (retcode=%1$d): Zorg dat dit geïnstalleerd is in het juiste pad of pas de configuratie aan ("mysql_bindir")',
	'bkp-mysqldump-notfound' => 'mysqldump is onvindbaar: %1$s - Zorg dat dit geïnstalleerd is in het juiste pad of pas de configuratie aan ("mysql_bindir")',
	'bkp-mysqldump-ok' => 'mysqldump is geïnstalleerd: %1$s',
	'bkp-name-sample' => 'Backupbestanden krijgen een naam gebaseerd op de identificatiegegevens van het databaseschema, datum en tijd. Voorbeeld: %1$s',
	'bkp-next-backup' => 'De volgende backup wordt gemaakt op <b>%1$s</b> (%2$s) om %3$s',
	'bkp-next-backup-unknown' => 'De volgende backup is nog <b>niet gepland</b>.',
	'bkp-next-to-delete' => 'Zal verwijderd worden bij de volgende backuptaak (volgens de instelling "retention_count")',
	'bkp-restore-running' => 'Er wordt een herstel uitgevoerd. Even geduld...',
	'bkp-retention' => 'Maximaal <b>%1$d backup-bestanden blijven bewaard</b> in de doelmap.',
	'bkp-status-backups-auto' => 'Geplande backups',
	'bkp-status-backups-manual' => 'Manuele backups',
	'bkp-status-backups-none' => 'Nog geen backups beschikbaar',
	'bkp-status-checks' => 'Instellingen en controles',
	'bkp-status-title' => 'Geplande backups',
	'bkp-success-restore' => 'Herstel is succesvol voltooid.',
	'bkp-table-actions' => 'Acties',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Bestand',
	'bkp-table-file+' => 'Enkel .ZIP-bestanden worden herkend als backupbestanden.',
	'bkp-table-size' => 'Grootte',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Wacht tot de backup gemaakt is...',
	'bkp-wait-restore' => 'Wacht tot de backup hersteld is...',
	'bkp-week-days' => 'Backups gebeuren <b>elke %1$s om %2$s</b>',
	'bkp-wrong-format-spec' => 'Het huidige formaat voor bestandsnamen is ongeldig (%1$s). Een standaardformaat wordt toegepast: %2$s',
]);

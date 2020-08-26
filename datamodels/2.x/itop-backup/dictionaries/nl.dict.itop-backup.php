<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2019 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * @author Hipska (2019)
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(

	'bkp-backup-running' => 'Er wordt een backup gemaakt. Even geduld...',
	'bkp-restore-running' => 'Er wordt een herstel uitgevoerd. Even geduld...',

	'Menu:BackupStatus' => 'Geplande backups',
	'bkp-status-title' => 'Geplande backups',
	'bkp-status-checks' => 'Instellingen en controles',
	'bkp-mysqldump-ok' => 'mysqldump is geïnstalleerd: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump is onvindbaar: %1$s - Zorg dat dit geïnstalleerd is in het juiste pad of pas de configuratie aan ("mysql_bindir")',
	'bkp-mysqldump-issue' => 'mysqldump kon niet worden uitgevoerd (retcode=%1$d): Zorg dat dit geïnstalleerd is in het juiste pad of pas de configuratie aan ("mysql_bindir")',
	'bkp-missing-dir' => 'De doelmap %1$s is niet toegankelijk.',
	'bkp-free-disk-space' => '<b>%1$s vrij</b> in %2$s',
	'bkp-dir-not-writeable' => 'Geen schrijfrechten op %1$s',
	'bkp-wrong-format-spec' => 'Het huidige formaat voor bestandsnamen is ongeldig (%1$s). Een standaardformaat wordt toegepast: %2$s',
	'bkp-name-sample' => 'Backupbestanden krijgen een naam gebaseerd op de identificatiegegevens van het databaseschema, datum en tijd. Voorbeeld: %1$s',
	'bkp-week-days' => 'Backups gebeuren <b>elke %1$s om %2$s</b>',
	'bkp-retention' => 'Maximaal <b>%1$d backup-bestanden blijven bewaard</b> in de doelmap.',
	'bkp-next-to-delete' => 'Zal verwijderd worden bij de volgende backuptaak (volgens de instelling "retention_count")',
	'bkp-table-file' => 'Bestand',
	'bkp-table-file+' => 'Enkel .ZIP-bestanden worden herkend als backupbestanden.',
	'bkp-table-size' => 'Grootte',
	'bkp-table-size+' => '',
	'bkp-table-actions' => 'Acties',
	'bkp-table-actions+' => '',
	'bkp-status-backups-auto' => 'Geplande backups',
	'bkp-status-backups-manual' => 'Manuele backups',
	'bkp-status-backups-none' => 'Nog geen backups beschikbaar',
	'bkp-next-backup' => 'De volgende backup wordt gemaakt op <b>%1$s</b> (%2$s) om %3$s',
	'bkp-button-backup-now' => 'Maak nu een backup',
	'bkp-button-restore-now' => 'Herstel',
	'bkp-confirm-backup' => 'Bevestig dat de backup nu gemaakt mag worden.',
	'bkp-confirm-restore' => 'Bevestig dat je deze backup wil herstellen: %1$s.',
	'bkp-wait-backup' => 'Wacht tot de backup gemaakt is...',
	'bkp-wait-restore' => 'Wacht tot de backup hersteld is...',
	'bkp-success-restore' => 'Herstel is succesvol voltooid.',
));

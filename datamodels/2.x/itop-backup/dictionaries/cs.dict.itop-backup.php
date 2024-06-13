<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author Daniel Rokos <daniel.rokos@itopportal.cz>
 *
 */
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Menu:BackupStatus' => 'Plánované zálohování',
	'bkp-backup-running' => 'Probíhá záloha. Vyčkejte prosím...',
	'bkp-button-backup-now' => 'Zálohovat nyní!',
	'bkp-button-restore-now' => 'Obnovit ze zálohy!',
	'bkp-confirm-backup' => 'Potvrďte prosím, že chcete provést zálohu nyní.',
	'bkp-confirm-restore' => 'Potvrďte prosím, že chcete obnovit ze zálohy %1$s.',
	'bkp-dir-not-writeable' => 'Nemohu zapisovat do adresáře %1$s',
	'bkp-free-disk-space' => '<b>%1$s volných</b> na <code>%2$s</code>',
	'bkp-missing-dir' => 'Cílová složka <code>%1$s</code> nebyla nalezena',
	'bkp-mysqldump-issue' => 'mysqldump nemohl být spuštěn (retcode=%1$d): Ujistěte se prosím, že je nainstalován a v proměnné PATH, nebo upravte konfigurační soubor (položka mysql_bindir).',
	'bkp-mysqldump-notfound' => 'mysqldump nemohl být nalezen: %1$s - Ujistěte se prosím, že je nainstalován a v proměnné PATH, nebo upravte konfigurační soubor (položka mysql_bindir).',
	'bkp-mysqldump-ok' => 'mysqldump nalezen: %1$s',
	'bkp-name-sample' => 'Soubory zálohy jsou pojmenovány dle DB, data a času. Příklad: %1$s',
	'bkp-next-backup' => 'Další záloha bude provedena dne <b>%1$s</b> (%2$s) v %3$s',
	'bkp-next-backup-unknown' => 'The next backup is <b>not scheduled</b> yet.~~',
	'bkp-next-to-delete' => 'Bude odstraněna při další záloze (nastavení "retention_count")',
	'bkp-restore-running' => 'Probíhá obnova ze zálohy. Vyčkejte prosím...',
	'bkp-retention' => 'V cílové složce <b>bude uchováno maximálně %1$d souborů záloh</b>.',
	'bkp-status-backups-auto' => 'Automatické zálohy',
	'bkp-status-backups-manual' => 'Manuální zálohy',
	'bkp-status-backups-none' => 'Žádné zálohy',
	'bkp-status-checks' => 'Nastavení testy',
	'bkp-status-title' => 'Plánované zálohování',
	'bkp-success-restore' => 'Obnova úspěšně dokončena.',
	'bkp-table-actions' => 'Akce',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Soubor',
	'bkp-table-file+' => 'Pouze soubory s příponou .zip jsou považovány za soubory zálohy.',
	'bkp-table-size' => 'Velikost',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Vyčkejte prosím na dokončení zálohy...',
	'bkp-wait-restore' => 'Vyčkejte prosím na dokončení obnovy',
	'bkp-week-days' => 'Záloha bude provedena <b>vždy v %1$s v %2$s</b>',
	'bkp-wrong-format-spec' => 'Současná specifikace názvu souboru nemůže být použita (%1$s). Bude nastavena výchozí: %2$s',
]);

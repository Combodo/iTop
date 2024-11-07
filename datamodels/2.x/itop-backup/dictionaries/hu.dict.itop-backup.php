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
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Menu:BackupStatus' => 'Biztonsági mentés',
	'bkp-backup-running' => 'A mentés fut. Kérem várjon...',
	'bkp-button-backup-now' => 'Mentés most!',
	'bkp-button-restore-now' => 'Visszaállítás!',
	'bkp-confirm-backup' => 'Erősítse meg, hogy a biztonsági mentést most kéri.',
	'bkp-confirm-restore' => 'Kérjük, erősítse meg, hogy vissza szeretné állítani a %1$s biztonsági mentést.',
	'bkp-dir-not-writeable' => '%1$s nem írható',
	'bkp-free-disk-space' => '<b>%1$s szabad</b> a <code>%2$s</code> -ből',
	'bkp-missing-dir' => 'A <code>%1$s</code> célkönyvtár nem található',
	'bkp-mysqldump-issue' => 'mysqldump nem hajtható végre (retcode=%1$d): Győződjön meg róla, hogy telepítve van és szerepel az elérési útvonalban, vagy szerkessze a konfigurációs fájlt a mysql_bindir beállításához.',
	'bkp-mysqldump-notfound' => 'mysqldump nem található: %1$s - Győződjön meg róla, hogy telepítve van és szerepel az elérési útvonalban, vagy szerkessze a konfigurációs fájlt a mysql_bindir beállításához..',
	'bkp-mysqldump-ok' => 'mysqldump megvan: %1$s',
	'bkp-name-sample' => 'A mentési fájlok neve a DB azonosítóktól, a dátumtól és az időponttól függ. Példa: %1$s',
	'bkp-next-backup' => 'A következő mentés <b>%1$s</b> (%2$s) fog lefutni %3$s -kor',
	'bkp-next-backup-unknown' => 'A következő mentés még <b>nincs ütemezve</b>',
	'bkp-next-to-delete' => 'Törölve lesz a következő mentés alkalmával (lásd a "retention_count" beállítást)',
	'bkp-restore-running' => 'A visszaállítás fut. Kérem várjon...',
	'bkp-retention' => 'Legfeljebb <b>%1$d biztonsági mentés lesz megőrizve</b> a célkönyvtárban.',
	'bkp-status-backups-auto' => 'Automatikus biztonsági mentés',
	'bkp-status-backups-manual' => 'Manuális biztonsági mentés',
	'bkp-status-backups-none' => 'Még nincs biztonsági mentés',
	'bkp-status-checks' => 'Beállítás és ellenőrzés',
	'bkp-status-title' => 'Adatbázis biztonsági mentés',
	'bkp-success-restore' => 'A visszaállítás sikerült.',
	'bkp-table-actions' => 'Műveletek',
	'bkp-table-actions+' => '~~',
	'bkp-table-file' => 'Fájl',
	'bkp-table-file+' => 'Csak a .zip kiterjesztésű fájlokat tekintjük biztonsági mentésnek.',
	'bkp-table-size' => 'Méret',
	'bkp-table-size+' => '~~',
	'bkp-wait-backup' => 'Várjon a mentés befejezéséig...',
	'bkp-wait-restore' => 'Várjon a visszaállítás befejezéséig...',
	'bkp-week-days' => 'Biztonsági mentés lesz végrehajtva <b>minden %1$s  %2$s -kor</b>',
	'bkp-wrong-format-spec' => 'A fájlnevek formázására vonatkozó jelenlegi specifikáció helytelen (%1$s). Alapértelmezett specifikáció lesz érvényben: %2$s',
]);

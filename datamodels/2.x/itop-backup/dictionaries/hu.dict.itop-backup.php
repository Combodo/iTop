<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(

	'bkp-backup-running' => 'A backup fut. Kérem várjon...',
	'bkp-restore-running' => 'A visszaállítás fut. Kérem várjon...',

	'Menu:BackupStatus' => 'Ütemezett Backup',
	'bkp-status-title' => 'Scheduled Backups~~',
	'bkp-status-checks' => 'Beállítás és ellenőrzés',
	'bkp-mysqldump-ok' => 'mysqldump megvan: %1$s',
	'bkp-mysqldump-notfound' => 'mysqldump nem található: %1$s - Győződjön meg róla, hogy telepítve van és szerepel az elérési útvonalban, vagy szerkessze a konfigurációs fájlt a mysql_bindir beállításához..',
	'bkp-mysqldump-issue' => 'mysqldump nem hajtható végre (retcode=%1$d): Győződjön meg róla, hogy telepítve van és szerepel az elérési útvonalban, vagy szerkessze a konfigurációs fájlt a mysql_bindir beállításához.',
	'bkp-missing-dir' => 'A <code>%1$s</code> célkönyvtár nem található',
	'bkp-free-disk-space' => '<b>%1$s szabad</b> a <code>%2$s</code> -ből',
	'bkp-dir-not-writeable' => '%1$s nem írható',
	'bkp-wrong-format-spec' => 'A fájlnevek formázására vonatkozó jelenlegi specifikáció helytelen (%1$s). Alapértelmezett specifikáció lesz érvényben: %2$s',
	'bkp-name-sample' => 'A backup fájlok neve a DB azonosítóktól, a dátumtól és az időponttól függ. Példa: %1$s~~',
	'bkp-week-days' => 'Backup lesz végrehajtva <b>minden %1$s  %2$s -kor</b>',
	'bkp-retention' => 'Legfeljebb <b>%1$d backup fájl lesz megőrizve</b> a célkönyvtárban.',
	'bkp-next-to-delete' => 'Törölve lesz a következő backup alkalmával (lásd a "retention_count" beállítást)',
	'bkp-table-file' => 'Fájl',
	'bkp-table-file+' => 'Csak a .zip kiterjesztésű fájlokat tekintjük biztonsági mentésnek.',
	'bkp-table-size' => 'Méret',
	'bkp-table-size+' => '~~',
	'bkp-table-actions' => 'Műveletek',
	'bkp-table-actions+' => '~~',
	'bkp-status-backups-auto' => 'Ütemezett backup',
	'bkp-status-backups-manual' => 'Manuális backup',
	'bkp-status-backups-none' => 'Még nincs backup',
	'bkp-next-backup' => 'A következő backup <b>%1$s</b> (%2$s) fog lefutni at %3$s -kor',
	'bkp-next-backup-unknown' => 'A következő backup <b>nincs ütemezve</b> még.',
	'bkp-button-backup-now' => 'Backup most!',
	'bkp-button-restore-now' => 'Visszaállítás!',
	'bkp-confirm-backup' => 'Erősítse meg, hogy a biztonsági mentést most kéri.',
	'bkp-confirm-restore' => 'Kérjük, erősítse meg, hogy vissza szeretné állítani a %1$s backup-ot.',
	'bkp-wait-backup' => 'Várjon a backup befejezéséig...',
	'bkp-wait-restore' => 'Várjon a visszaállítás befejezéséig...',
	'bkp-success-restore' => 'A visszaállítás sikerült.',
));

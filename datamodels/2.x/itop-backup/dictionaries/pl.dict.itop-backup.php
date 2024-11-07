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
Dict::Add('PL PL', 'Polish', 'Polski', [
	'Menu:BackupStatus' => 'Kopie zapasowe',
	'bkp-backup-running' => 'Kopia zapasowa jest uruchomiona. Proszę czekać...',
	'bkp-button-backup-now' => 'Utwórz kopię teraz!',
	'bkp-button-restore-now' => 'Przywróć!',
	'bkp-confirm-backup' => 'Potwierdź, że chcesz teraz wykonać kopię zapasową.',
	'bkp-confirm-restore' => 'Potwierdź, że chcesz przywrócić kopię zapasową %1$s.',
	'bkp-dir-not-writeable' => '%1$s jest niezapisywalny',
	'bkp-free-disk-space' => '<b>%1$s wolne</b> w %2$s',
	'bkp-missing-dir' => 'The target directory %1$s nie został znaleziony',
	'bkp-mysqldump-issue' => 'mysqldump nie mógł zostać wykonany (retcode=%1$d): Upewnij się, że jest zainstalowany i znajduje się w ścieżce, lub edytuj plik konfiguracyjny, aby ustawić mysql_bindir',
	'bkp-mysqldump-notfound' => 'mysqldump nie znaleziony: %1$s - Upewnij się, że jest zainstalowany i znajduje się w ścieżce, lub edytuj plik konfiguracyjny, aby ustawić mysql_bindir.',
	'bkp-mysqldump-ok' => 'mysqldump jest obecny: %1$s',
	'bkp-name-sample' => 'Pliki kopii zapasowych są nazywane w zależności od identyfikatorów bazy danych, daty i godziny. Przykład: %1$s',
	'bkp-next-backup' => 'Następna kopia zapasowa zostanie utworzona <b>%1$s</b> (%2$s) w %3$s',
	'bkp-next-backup-unknown' => 'Następna kopia zapasowa <b>nie jest zaplanowana</b>.',
	'bkp-next-to-delete' => 'Zostanie usunięty po wykonaniu następnej kopii zapasowej (patrz ustawienie "retention_count")',
	'bkp-restore-running' => 'Trwa przywracanie. Proszę czekać...',
	'bkp-retention' => 'Co najwyżej <b>%1$d plików kopii zapasowych będzie przechowywanych</b> w katalogu docelowym.',
	'bkp-status-backups-auto' => 'Zaplanowane kopie zapasowe',
	'bkp-status-backups-manual' => 'Ręczne kopie zapasowe',
	'bkp-status-backups-none' => 'Nie ma jeszcze kopii zapasowej',
	'bkp-status-checks' => 'Ustawienia i kontrole',
	'bkp-status-title' => 'Kopie zapasowe',
	'bkp-success-restore' => 'Przywracanie zakończone pomyślnie.',
	'bkp-table-actions' => 'Działania',
	'bkp-table-actions+' => '',
	'bkp-table-file' => 'Plik',
	'bkp-table-file+' => 'Tylko pliki z rozszerzeniem .zip są traktowane jako pliki kopii zapasowych',
	'bkp-table-size' => 'Rozmiar',
	'bkp-table-size+' => '',
	'bkp-wait-backup' => 'Poczekaj na zakończenie tworzenia kopii zapasowej...',
	'bkp-wait-restore' => 'Poczekaj na zakończenie przywracania...',
	'bkp-week-days' => 'Kopie zapasowe będą wykonywane <b>co %1$s w %2$s</b>',
	'bkp-wrong-format-spec' => 'Bieżąca specyfikacja formatowania nazw plików jest nieprawidłowa (%1$s). Obowiązuje specyfikacja domyślna: %2$s',
]);

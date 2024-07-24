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
	'Menu:ConfigEditor' => 'Konfiguracja ogólna',
	'config-apply' => 'Zastosuj',
	'config-apply-title' => 'Zastosuj (Ctrl+S)',
	'config-cancel' => 'Reset',
	'config-confirm-cancel' => 'Twoje zmiany zostaną utracone.',
	'config-current-line' => 'Edycja linii: %1$s',
	'config-edit-intro' => 'Zachowaj ostrożność podczas edycji pliku konfiguracyjnego.',
	'config-edit-title' => 'Edycja pliku konfiguracyjnego',
	'config-error-file-changed' => 'Błąd: Plik konfiguracyjny został zmieniony od czasu jego otwarcia i nie można go zapisać. Odśwież i ponownie zastosuj zmiany.',
	'config-error-transaction' => 'Błąd: nieprawidłowy identyfikator transakcji. Konfiguracja <b>NIE</b> została zmodyfikowana.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interaktywna edycja konfiguracji została wyłączona. Zobacz <code>\'config_editor\' => \'disabled\'</code> w pliku konfiguracyjnym.',
	'config-no-change' => 'Brak zmian: plik pozostał niezmieniony.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' jest w <b>trybie demonstracyjnym</b>: nie można edytować pliku konfiguracyjnego.',
	'config-parse-error' => 'Linia %2$d: %1$s.<br/>Plik NIE został zaktualizowany.',
	'config-reverted' => 'Konfiguracja została przywrócona.',
	'config-saved' => 'Zapisano pomyślnie.',
	'config-saved-warning-db-password' => 'Zapisano pomyślnie, ale kopia zapasowa nie działa z powodu nieobsługiwanych znaków w haśle bazy danych.',
]);

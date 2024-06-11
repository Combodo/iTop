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
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Brak zmian: plik pozostał niezmieniony.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Linia %2$d: %1$s.<br/>Plik NIE został zaktualizowany.',
	'config-reverted' => 'Konfiguracja została przywrócona.',
	'config-saved' => 'Zapisano pomyślnie.',
	'config-saved-warning-db-password' => 'Zapisano pomyślnie, ale kopia zapasowa nie działa z powodu nieobsługiwanych znaków w haśle bazy danych.',
]);

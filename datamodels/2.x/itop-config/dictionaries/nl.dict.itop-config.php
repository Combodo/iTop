<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 *
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Menu:ConfigEditor' => 'Configuratie',
	'config-apply' => 'Opslaan',
	'config-apply-title' => 'Opslaan (Ctrl+S)',
	'config-cancel' => 'Herbegin',
	'config-confirm-cancel' => 'Je wijzigingen zullen verloren gaan.',
	'config-current-line' => 'Huidige regel: %1$s',
	'config-edit-intro' => 'Wees uiterst voorzichtig bij het aanpassen van de configuratie.',
	'config-edit-title' => 'Aanpassen configuratie',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Er zijn geen wijzigingen vastgesteld.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Regel %2$d: %1$s.<br/>Het bestand werd <b>NIET</b> opgeslagen.',
	'config-reverted' => 'De wijzigingen zijn ongedaan gemaakt.',
	'config-saved' => 'Wijzigingen zijn opgeslagen.',
	'config-saved-warning-db-password' => 'Wijzigingen zijn opgeslagen, maar de backup zal niet werken doordat het databasewachtwoord karakters bevat die niet ondersteund zijn.',
]);

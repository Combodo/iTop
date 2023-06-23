<?php
/**
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Menu:ConfigEditor' => 'Konfigurace',
	'config-edit-title' => 'Editor konfiguračního souboru',
	'config-edit-intro' => 'Při úpravách konfiguračního souboru buďte velice opatrní. Nesprávné nastavení může vést k nedostupnosti '.ITOP_APPLICATION_SHORT,
	'config-apply' => 'Použít',
	'config-apply-title' => 'Použít (Ctrl+S)',
	'config-cancel' => 'Zrušit',
	'config-saved' => 'Successfully recorded.~~',
	'config-confirm-cancel' => 'Vaše úpravy nebudou uloženy.',
	'config-no-change' => 'Soubor nebyl změněn.',
	'config-reverted' => 'The configuration has been reverted.~~',
	'config-parse-error' => 'Řádek %2$d: %1$s.<br/>Soubor nebyl uložen.',
	'config-current-line' => 'Řádek: %1$s',
	'config-saved-warning-db-password' => 'Successfully recorded, but the backup won\'t work due to unsupported characters in the database password.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
));

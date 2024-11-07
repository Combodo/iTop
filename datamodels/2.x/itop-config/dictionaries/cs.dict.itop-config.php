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
	'Menu:ConfigEditor' => 'Konfigurace',
	'config-apply' => 'Použít',
	'config-apply-title' => 'Použít (Ctrl+S)',
	'config-cancel' => 'Zrušit',
	'config-confirm-cancel' => 'Vaše úpravy nebudou uloženy.',
	'config-current-line' => 'Řádek: %1$s',
	'config-edit-intro' => 'Při úpravách konfiguračního souboru buďte velice opatrní. Nesprávné nastavení může vést k nedostupnosti '.ITOP_APPLICATION_SHORT,
	'config-edit-title' => 'Editor konfiguračního souboru',
	'config-error-file-changed' => 'Chyba: Konfigurační soubor se od doby, kdy jste jej otevřeli, změnil a nelze jej uložit. Obnovte a znovu použijte změny.',
	'config-error-transaction' => 'Chyba: Neplatné ID transakce. Konfigurace <b>nebyla</b> změněna.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Soubor nebyl změněn.',
	'config-not-allowed-in-demo' => 'Omlouvám se, '.ITOP_APPLICATION_SHORT.' je v <b>předváděcí režimu</b>: Konfigurace nemůže být editována',
	'config-parse-error' => 'Řádek %2$d: %1$s.<br/>Soubor nebyl uložen.',
	'config-reverted' => 'Konfigurace byla vrácena.',
	'config-saved' => 'Úspěšně zaznamenáno.',
	'config-saved-warning-db-password' => 'Successfully recorded, but the backup won\'t work due to unsupported characters in the database password.~~',
]);

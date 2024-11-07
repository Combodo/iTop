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
	'Menu:ConfigEditor' => 'Konfiguráció szerkesztő',
	'config-apply' => 'Alkalmaz',
	'config-apply-title' => 'Alkalmaz (Ctrl+S)',
	'config-cancel' => 'Visszaállítás',
	'config-confirm-cancel' => 'A változtatások elvesznek.',
	'config-current-line' => 'Szerkesztett sor: %1$s',
	'config-edit-intro' => 'Legyen nagyon óvatos a konfiguráció szerkesztésénél!',
	'config-edit-title' => 'Konfigurációs fájl szerkesztő',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Nincs változtatás: a fájl változatlan maradt.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => '%2$d sor: %1$s.<br/>A fájl NEM frissült',
	'config-reverted' => 'A konfiguráció vissza lett állítva.',
	'config-saved' => 'Sikeresen elmentve.',
	'config-saved-warning-db-password' => 'Sikeresen elmentve, de a biztonsági mentés nem fog működni az adatbázis jelszavában szereplő nem támogatott karakterek miatt.',
]);

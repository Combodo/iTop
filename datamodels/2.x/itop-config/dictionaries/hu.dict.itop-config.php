<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
	'Menu:ConfigEditor' => 'Konfiguráció szerkesztő',
	'config-edit-title' => 'Konfigurációs fájl szerkesztő',
	'config-edit-intro' => 'Legyen nagyon óvatos a konfiguráció szerkesztésénél!',
	'config-apply' => 'Alkalmaz',
	'config-apply-title' => 'Alkalmaz (Ctrl+S)',
	'config-cancel' => 'Visszaállítás',
	'config-saved' => 'Sikeresen elmentve.',
	'config-confirm-cancel' => 'A változtatások elvesznek.',
	'config-no-change' => 'Nincs változtatás: a fájl változatlan maradt.',
	'config-reverted' => 'A konfiguráció vissza lett állítva.',
	'config-parse-error' => '%2$d sor: %1$s.<br/>A fájl NEM frissült',
	'config-current-line' => 'Szerkesztett sor: %1$s',
	'config-saved-warning-db-password' => 'Sikeresen elmentve, de a biztonsági mentés nem fog működni az adatbázis jelszavában szereplő nem támogatott karakterek miatt.',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
));

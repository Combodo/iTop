<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ConfigEditor' => 'Configuratie',
	'config-edit-title' => 'Aanpassen configuratie',
	'config-edit-intro' => 'Wees uiterst voorzichtig bij het aanpassen van de configuratie.',
	'config-apply' => 'Opslaan',
	'config-apply-title' => 'Opslaan (Ctrl+S)',
	'config-cancel' => 'Herbegin',
	'config-saved' => 'Wijzigingen zijn opgeslagen.',
	'config-confirm-cancel' => 'Je wijzigingen zullen verloren gaan.',
	'config-no-change' => 'Er zijn geen wijzigingen vastgesteld.',
	'config-reverted' => 'De wijzigingen zijn ongedaan gemaakt.',
	'config-parse-error' => 'Regel %2$d: %1$s.<br/>Het bestand werd <b>NIET</b> opgeslagen.',
	'config-current-line' => 'Huidige regel: %1$s',
	'config-saved-warning-db-password' => 'Wijzigingen zijn opgeslagen, maar de backup zal niet werken doordat het databasewachtwoord karakters bevat die niet ondersteund zijn.',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
));

<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('EN US', 'English', 'English', array(

	'Menu:ConfigFileEditor' => 'Plain text editor',
	'config-edit-title' => 'Configuration File Editor',
	'config-edit-intro' => 'Be very cautious when editing the configuration file.',
	'config-apply' => 'Apply',
	'config-apply-title' => 'Apply (Ctrl+S)',
	'config-cancel' => 'Reset',
	'config-saved' => 'Successfully recorded.',
	'config-confirm-cancel' => 'Your changes will be lost.',
	'config-no-change' => 'No change: the file has been left unchanged.',
	'config-reverted' => 'The configuration has been reverted.',
	'config-parse-error' => 'Line %2$d: %1$s.<br/>The file has NOT been updated.',
	'config-current-line' => 'Editing line: %1$s',
	'config-saved-warning-db-password' => 'Successfully recorded, but the backup won\'t work due to unsupported characters in the database password.',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT." interactive edition of the configuration as been disabled. See <code>'config_editor' => 'disabled'</code> in the configuration file.",
));

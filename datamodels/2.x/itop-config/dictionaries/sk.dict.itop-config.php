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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', [
	'Menu:ConfigEditor' => 'General configuration~~',
	'config-apply' => 'Apply~~',
	'config-apply-title' => 'Apply (Ctrl+S)~~',
	'config-cancel' => 'Reset~~',
	'config-confirm-cancel' => 'Your changes will be lost.~~',
	'config-current-line' => 'Editing line: %1$s~~',
	'config-edit-intro' => 'Be very cautious when editing the configuration file.~~',
	'config-edit-title' => 'Configuration File Editor~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'No change: the file has been left unchanged.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Line %2$d: %1$s.<br/>The file has NOT been updated.~~',
	'config-reverted' => 'The configuration has been reverted.~~',
	'config-saved' => 'Successfully recorded.~~',
	'config-saved-warning-db-password' => 'Successfully recorded, but the backup won\'t work due to unsupported characters in the database password.~~',
]);

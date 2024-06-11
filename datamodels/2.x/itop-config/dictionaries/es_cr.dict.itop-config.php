<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 *
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Menu:ConfigEditor' => 'Configuración',
	'config-apply' => 'Aplicar',
	'config-apply-title' => 'Aplicar (Ctrl+S)',
	'config-cancel' => 'Restablecer',
	'config-confirm-cancel' => 'Sus cambiso se perderán.',
	'config-current-line' => 'Editando línea: %1$s',
	'config-edit-intro' => 'Sea muy cuidadoso cuando edite el archivo de configuración. En particular, sólo los elementos superiores (ejem.: the global configuration y modules settings) deberian ser editados.',
	'config-edit-title' => 'Editor de Archivo de Configuración',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Sin cambio: el archivo permanece sin cambios.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Línea %2$d: %1$s.<br/>El archivo NO ha sido actualizado.',
	'config-reverted' => 'La configuración ha sido revertida.',
	'config-saved' => 'Exitosamente registrado.',
	'config-saved-warning-db-password' => 'Registrado correctamente, pero el respaldo NO funcionará debido a caracteres no admitidos en la contraseña de la base de datos.',
]);

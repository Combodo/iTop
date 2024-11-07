<?php
/**
 * Spanish Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 * @notas       Utilizar codificación UTF-8 para mostrar acentos y otros caracteres especiales 
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Menu:ConfigEditor' => 'Configuración',
	'config-apply' => 'Aplicar',
	'config-apply-title' => 'Aplicar (Ctrl+S)',
	'config-cancel' => 'Restablecer',
	'config-confirm-cancel' => 'Sus cambios se perderán.',
	'config-current-line' => 'Editando línea: %1$s',
	'config-edit-intro' => 'Sea muy cuidadoso cuando edite el archivo de configuración. En particular, sólo los elementos superiores (ejem.: the global configuration y modules settings) deberian ser editados.',
	'config-edit-title' => 'Editor de Archivo de Configuración',
	'config-error-file-changed' => 'Error: el archivo de configuración ha cambiado desde que lo abrió y no se puede guardar. Actualice y aplique sus cambios nuevamente.',
	'config-error-transaction' => 'Error: ID de transacción no válido. La configuración <b>NO</b> fue modificada.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' edición interactiva de la configuración como deshabilitada. Consulte <code>\'config_editor\' => \'disabled\'</code> en el archivo de configuración.',
	'config-no-change' => 'Sin cambio: el archivo permanece sin cambios.',
	'config-not-allowed-in-demo' => 'Lo sentimos, '.ITOP_APPLICATION_SHORT.' está en <b>modo de demostración</b>: el archivo de configuración no se puede editar.',
	'config-parse-error' => 'Línea %2$d: %1$s.<br/>El archivo NO ha sido actualizado.',
	'config-reverted' => 'La configuración ha sido revertida.',
	'config-saved' => 'Exitosamente registrado.',
	'config-saved-warning-db-password' => 'Registrado correctamente, pero el respaldo NO funcionará debido a caracteres no admitidos en la contraseña de la base de datos.',
]);

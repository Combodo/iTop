<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(

	'Menu:ConfigEditor' => 'Configuración',
	'config-edit-title' => 'Editor de Archivo de Configuración',
	'config-edit-intro' => 'Sea muy cuidadoso cuando edite el archivo de configuración. En particular, sólo los elementos superiores (ejem.: the global configuration y modules settings) deberian ser editados.',
	'config-apply' => 'Aplicar',
	'config-apply-title' => 'Aplicar (Ctrl+S)',
	'config-cancel' => 'Restablecer',
	'config-saved' => 'Exitosamente registrado.',
	'config-confirm-cancel' => 'Sus cambiso se perderán.',
	'config-no-change' => 'Sin cambio: el archivo permanece sin cambios.',
	'config-reverted' => 'La configuración ha sido revertida.',
	'config-parse-error' => 'Línea %2$d: %1$s.<br/>El archivo NO ha sido actualizado.',
	'config-current-line' => 'Editando línea: %1$s',
	'config-saved-warning-db-password' => 'Successfully recorded, but the backup won\'t work due to unsupported characters in the database password.~~',
));

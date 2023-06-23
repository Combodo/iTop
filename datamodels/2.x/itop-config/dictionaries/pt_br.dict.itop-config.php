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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:ConfigEditor' => 'Configurações',
	'config-edit-title' => 'Editor do arquivo de configuração',
	'config-edit-intro' => 'Tenha cuidado ao editar o arquivo de configuração',
	'config-apply' => 'Aplicar',
	'config-apply-title' => 'Aplicar (Ctrl+S)',
	'config-cancel' => 'Descartar alterações',
	'config-saved' => 'Gravado com sucesso',
	'config-confirm-cancel' => 'Suas alterações serão perdidas',
	'config-no-change' => 'Sem alteração: nenhuma alteração realizada no arquivo',
	'config-reverted' => 'A configuração foi restaurada',
	'config-parse-error' => 'Linha %2$d: %1$s.<br/>O arquivo não foi atualizado',
	'config-current-line' => 'Editando linha: %1$s',
	'config-saved-warning-db-password' => 'Salvo com sucesso, mas o backup não vai funcionar devido aos caracteres não suportados na senha do banco de dados',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
));

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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Menu:ConfigEditor' => 'Configurações',
	'config-apply' => 'Aplicar',
	'config-apply-title' => 'Aplicar (Ctrl+S)',
	'config-cancel' => 'Descartar alterações',
	'config-confirm-cancel' => 'Suas alterações serão perdidas',
	'config-current-line' => 'Editando linha: %1$s',
	'config-edit-intro' => 'Tenha cuidado ao editar o arquivo de configuração',
	'config-edit-title' => 'Editor do arquivo de configuração',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Sem alteração: nenhuma alteração realizada no arquivo',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Linha %2$d: %1$s.<br/>O arquivo não foi atualizado',
	'config-reverted' => 'A configuração foi restaurada',
	'config-saved' => 'Gravado com sucesso',
	'config-saved-warning-db-password' => 'Salvo com sucesso, mas o backup não vai funcionar devido aos caracteres não suportados na senha do banco de dados',
]);

<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Menu:ConfigEditor' => 'Основные настройки',
	'config-apply' => 'Применить',
	'config-apply-title' => 'Применить (Ctrl+S)',
	'config-cancel' => 'Сбросить',
	'config-confirm-cancel' => 'Ваши изменения будут утеряны.',
	'config-current-line' => 'Редактируемая строка: %1$s',
	'config-edit-intro' => 'Будьте очень осторожны при редактировании файла конфигурации. В частности, отредактированы могут быть только глобальная конфигурация и настройки модулей.',
	'config-edit-title' => 'Редактор файла конфигурации',
	'config-error-file-changed' => 'Error: The Configuration file has changed since you opened it and cannot be saved. Refresh and apply your changes again.~~',
	'config-error-transaction' => 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.~~',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' interactive edition of the configuration as been disabled. See <code>\'config_editor\' => \'disabled\'</code> in the configuration file.~~',
	'config-no-change' => 'Изменений нет: файл не был изменён.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demonstration mode</b>: the configuration file cannot be edited.~~',
	'config-parse-error' => 'Строка %2$d: %1$s.<br/>Файл не был обновлен.',
	'config-reverted' => 'Изменения были сброшены.',
	'config-saved' => 'Изменения успешно сохранены.',
	'config-saved-warning-db-password' => 'Изменения успешно сохранены, но резервная копия не будет работать из-за неподдерживаемых символов в пароле базы данных.',
]);

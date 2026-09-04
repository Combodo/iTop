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
	'Menu:ConfigFileEditor' => 'Текстовый редактор',
	'itop-config/Operation:Edit/Title' => 'Редактор файла конфигурации',
	'config-edit-intro' => 'Будьте очень осторожны при редактировании файла конфигурации. В частности, отредактированы могут быть только глобальная конфигурация и настройки модулей.',
	'config-apply' => 'Применить',
	'config-apply-title' => 'Применить (Ctrl+S)',
	'config-cancel' => 'Сбросить',
	'config-saved' => 'Изменения успешно сохранены.',
	'config-confirm-cancel' => 'Ваши изменения будут утеряны.',
	'config-no-change' => 'Изменений нет: файл не был изменён.',
	'config-reverted' => 'Изменения были сброшены.',
	'config-parse-error' => 'Строка %2$d: %1$s.<br/>Файл не был обновлен.',
	'config-current-line' => 'Редактируемая строка: %1$s',
	'config-saved-warning-db-password' => 'Изменения успешно сохранены, но резервная копия не будет работать из-за неподдерживаемых символов в пароле базы данных.',
	'config-error-transaction' => 'Ошибка: недопустимый ID транзакции. Конфигурация <b>НЕ</b> была изменена.',
	'config-error-file-changed' => 'Ошибка: файл конфигурации изменился с момента открытия, сохранение невозможно. Обновите страницу и примените изменения заново.',
	'config-not-allowed-in-demo' => 'Извините, '.ITOP_APPLICATION_SHORT.' работает в <b>демонстрационном режиме</b>: файл конфигурации нельзя редактировать.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.': интерактивное редактирование конфигурации отключено. См. <code>\'config_editor\' => \'disabled\'</code> в файле конфигурации.',
]);

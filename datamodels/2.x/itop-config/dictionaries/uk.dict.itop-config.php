<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:ConfigFileEditor' => 'Текстовий редактор',
	'config-edit-title' => 'Редактор файлу конфігурації',
	'config-edit-intro' => 'Будьте дуже обережні при редагуванні файлу конфігурації. Зокрема, відредаговані можуть бути тільки глобальна конфігурація та налаштування модулів.',
	'Menu:ConfigEditor' => 'Основні налаштування',
	'config-apply' => 'Застосувати',
	'config-apply-title' => 'Застосувати (Ctrl+S)',
	'config-cancel' => 'Скинути',
	'config-saved' => 'Зміни успішно збережені.',
	'config-confirm-cancel' => 'Ваші зміни будуть втрачені.',
	'config-no-change' => 'Змін немає: файл не був змінений.',
	'config-reverted' => 'Зміни були скинуті.',
	'config-parse-error' => 'Рядок %2$d: %1$s.<br/>Файл не був оновлено.',
	'config-current-line' => 'Редагований рядок: %1$s',
	'config-saved-warning-db-password' => 'Зміни успішно збережені, але резервна копія не буде працювати через непідтримувані символи в паролі бази даних.',
	'config-error-transaction' => 'Помилка: невірний ID транзакції. Конфігурацію <b>НЕ</b> було змінено.',
	'config-error-file-changed' => 'Помилка: Файл конфігурації було змінено з моменту його відкриття і його неможливо зберегти. Оновіть сторінку та застосуйте зміни знову.',
	'config-not-allowed-in-demo' => 'Вибачте, '.ITOP_APPLICATION_SHORT.' працює в <b>демонстраційному режимі</b>: файл конфігурації не може бути відредагований.',
	'config-interactive-not-allowed' => ITOP_APPLICATION_SHORT.' інтерактивне редагування конфігурації було вимкнено. Див. <code>\'config_editor\' => \'disabled\'</code> у файлі конфігурації.',
));

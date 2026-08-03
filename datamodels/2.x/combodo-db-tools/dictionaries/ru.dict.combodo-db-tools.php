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
	'Menu:DBToolsMenu' => 'Инструменты БД',
	'DBTools:Class' => 'Класс',
	'DBTools:Title' => 'Инструменты обслуживания базы данных',
	'DBTools:ErrorsFound' => 'Найденные ошибки',
	'DBTools:Indication' => 'Важно: после исправления ошибок в базе данных нужно будет запустить анализ заново, так как появятся новые несоответствия',
	'DBTools:Disclaimer' => 'ВНИМАНИЕ: СДЕЛАЙТЕ РЕЗЕРВНУЮ КОПИЮ БАЗЫ ДАННЫХ ПЕРЕД ЗАПУСКОМ ИСПРАВЛЕНИЙ',
	'DBTools:Error' => 'Ошибка',
	'DBTools:Count' => 'Количество',
	'DBTools:SQLquery' => 'SQL-запрос',
	'DBTools:FixitSQLquery' => 'SQL-запрос для исправления базы данных (указание)',
	'DBTools:SQLresult' => 'Результат SQL',
	'DBTools:NoError' => 'База данных в порядке',
	'DBTools:HideIds' => 'Список ошибок',
	'DBTools:ShowIds' => 'Подробный вид',
	'DBTools:ShowReport' => 'Отчёт',
	'DBTools:IntegrityCheck' => 'Проверка целостности',
	'DBTools:FetchCheck' => 'Проверка выборки (долго)',
	'DBTools:SelectAnalysisType' => 'Выберите тип анализа',
	'DBTools:Analyze' => 'Анализировать',
	'DBTools:Details' => 'Показать подробности',
	'DBTools:ShowAll' => 'Показать все ошибки',
	'DBTools:Inconsistencies' => 'Несоответствия базы данных',
	'DBTools:DetailedErrorTitle' => 'Ошибок (%2$s) в классе %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'Список ограничен %1$s ошибками',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Сиротская запись в `%1$s`, она должна иметь свой аналог в таблице `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Недопустимый внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Отсутствует внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Недопустимое значение для %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Некоторые учетные записи пользователей не имеют профилей',
	'DBAnalyzer-Integrity-HKInvalid' => 'Повреждён иерархический ключ `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Ошибка количества выборки в `%1$s`: получено записей %2$d / посчитано %3$d',
	'DBAnalyzer-Integrity-FinalClass' => 'Поле `%2$s`.`%1$s` должно иметь то же значение, что и `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Поле `%2$s`.`%1$s` должно содержать допустимый класс',
]);

// Database Info
Dict::Add('RU RU', 'Russian', 'Русский', [
	'DBTools:DatabaseInfo' => 'Информация о базе данных',
	'DBTools:Base' => 'База',
	'DBTools:Size' => 'Размер',
]);

// Lost attachments
Dict::Add('RU RU', 'Russian', 'Русский', [
	'DBTools:LostAttachments' => 'Потерянные вложения',
	'DBTools:LostAttachments:Disclaimer' => 'Здесь вы можете найти потерянные или ошибочно перемещённые вложения в вашей базе данных. Это не инструмент восстановления данных, он не восстанавливает удаленные данные.',
	'DBTools:LostAttachments:Button:Analyze' => 'Анализировать',
	'DBTools:LostAttachments:Button:Restore' => 'Восстановить',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Это действие не может быть отменено. Пожалуйста, подтвердите, что вы хотите восстановить выбранные файлы.',
	'DBTools:LostAttachments:Button:Busy' => 'Пожалуйста, подождите...',
	'DBTools:LostAttachments:Step:Analyze' => 'Для начала просканируйте базу данных на наличие потерянных вложений.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Результат анализа:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Отлично! Похоже, все в порядке.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Некоторые вложения (%1$d), похоже, находятся не в том месте. Просмотрите следующий список и отметьте те, которые вы хотите переместить.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Файл',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Текущее местоположение',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Переместить в...',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Результат восстановления:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d вложения были восстановлены.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Хранится в качестве "InlineImage"',
	'DBTools:LostAttachments:History' => 'Вложение "%1$s" восстановлено с помощью инструментов обслуживания БД',
]);

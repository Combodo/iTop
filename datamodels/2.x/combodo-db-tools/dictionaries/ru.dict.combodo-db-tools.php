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
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-HKInvalid' => 'Broken hierarchical key `%1$s`~~',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Недопустимый внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Недопустимое значение для %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Отсутствует внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Сиротская запись в `%1$s`, она должна иметь свой аналог в таблице `%2$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contain a valid class~~',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Некоторые учетные записи пользователей не имеют профилей',
	'DBTools:Analyze' => 'Анализировать',
	'DBTools:Base' => 'База',
	'DBTools:Class' => 'Класс',
	'DBTools:Count' => 'Количество',
	'DBTools:DatabaseInfo' => 'Информация о базе данных',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors~~',
	'DBTools:DetailedErrorTitle' => '%2$s error(s) in class %1$s: %3$s~~',
	'DBTools:Details' => 'Показать подробности',
	'DBTools:Disclaimer' => 'DISCLAIMER: BACKUP YOUR DATABASE BEFORE RUNNING THE FIXES~~',
	'DBTools:Error' => 'Ошибка',
	'DBTools:ErrorsFound' => 'Найденные ошибки',
	'DBTools:FetchCheck' => 'Fetch Check (long)~~',
	'DBTools:FixitSQLquery' => 'SQL-запрос для исправления базы данных (указание)',
	'DBTools:HideIds' => 'Список ошибок',
	'DBTools:Inconsistencies' => 'Несоответствия базы данных',
	'DBTools:Indication' => 'Important: after fixing errors in the database you\'ll have to run the analysis again as new inconsistencies will be generated~~',
	'DBTools:IntegrityCheck' => 'Проверка целостности',
	'DBTools:LostAttachments' => 'Потерянные вложения',
	'DBTools:LostAttachments:Button:Analyze' => 'Анализировать',
	'DBTools:LostAttachments:Button:Busy' => 'Пожалуйста, подождите...',
	'DBTools:LostAttachments:Button:Restore' => 'Восстановить',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Это действие не может быть отменено. Пожалуйста, подтвердите, что вы хотите восстановить выбранные файлы.',
	'DBTools:LostAttachments:Disclaimer' => 'Здесь вы можете найти потерянные или ошибочно перемещённые вложения в вашей базе данных. Это не инструмент восстановления данных, он не восстанавливает удаленные данные.',
	'DBTools:LostAttachments:History' => 'Вложение "%1$s" восстановлено с помощью инструментов обслуживания БД',
	'DBTools:LostAttachments:Step:Analyze' => 'Для начала просканируйте базу данных на наличие потерянных вложений.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Результат анализа:',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Текущее местоположение',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Файл',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Переместить в...',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Отлично! Похоже, все в порядке.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Некоторые вложения (%1$d), похоже, находятся не в том месте. Просмотрите следующий список и отметьте те, которые вы хотите переместить.',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Результат восстановления:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d вложения были восстановлены.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Хранится в качестве "InlineImage"',
	'DBTools:NoError' => 'База данных в порядке',
	'DBTools:SQLquery' => 'SQL-запрос',
	'DBTools:SQLresult' => 'Результат SQL',
	'DBTools:SelectAnalysisType' => 'Select analysis type~~',
	'DBTools:ShowAll' => 'Показать все ошибки',
	'DBTools:ShowIds' => 'Подробный вид',
	'DBTools:ShowReport' => 'Отчёт',
	'DBTools:Size' => 'Размер',
	'DBTools:Title' => 'Инструменты обслуживания базы данных',
	'Menu:DBToolsMenu' => 'Инструменты БД',
]);

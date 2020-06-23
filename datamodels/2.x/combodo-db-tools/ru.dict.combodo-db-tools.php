<?php
/** Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
// Database inconsistencies
Dict::Add('RU RU', 'Russian', 'Русский', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Инструменты БД',
	'DBTools:Class' => 'Класс',
	'DBTools:Title' => 'Инструменты обслуживания базы данных',
	'DBTools:ErrorsFound' => 'Найденные ошибки',
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
	'DBTools:FetchCheck' => 'Fetch Check (long)~~',

	'DBTools:Analyze' => 'Анализировать',
	'DBTools:Details' => 'Показать подробности',
	'DBTools:ShowAll' => 'Показать все ошибки',

	'DBTools:Inconsistencies' => 'Несоответствия базы данных',

	'DBAnalyzer-Integrity-OrphanRecord' => 'Сиротская запись в `%1$s`, она должна иметь свой аналог в таблице `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Недопустимый внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Отсутствует внешний ключ %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Недопустимое значение для %1$s (столбец: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Некоторые учетные записи пользователей не имеют профилей',
	'DBAnalyzer-Fetch-Count-Error' => 'Fetch count error in `%1$s`, %2$d entries fetched / %3$d counted~~',
	'DBAnalyzer-Integrity-FinalClass' => 'Field `%2$s`.`%1$s` must have the same value as `%3$s`.`%1$s`~~',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Field `%2$s`.`%1$s` must contains a valid class~~',
));

// Database Info
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'DBTools:DatabaseInfo' => 'Информация о базе данных',
	'DBTools:Base' => 'База',
	'DBTools:Size' => 'Размер',
));

// Lost attachments
Dict::Add('RU RU', 'Russian', 'Русский', array(
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
	'DBTools:LostAttachments:History' => 'Вложение "%1$s" восстановлено с помощью инструментов обслуживания БД'
));

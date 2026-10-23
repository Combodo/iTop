<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:DBToolsMenu' => 'Інструменти БД',
	'DBTools:Class' => 'Клас',
	'DBTools:Title' => 'Інструменти обслуговування бази даних',
	'DBTools:ErrorsFound' => 'Знайдені помилки',
	'DBTools:Indication' => 'Важливо: після виправлення помилок у базі даних вам потрібно буде знову запустити аналіз, оскільки будуть створені нові невідповідності',
	'DBTools:Disclaimer' => 'ЗАСТЕРЕЖЕННЯ: ЗРОБІТЬ РЕЗЕРВНУ КОПІЮ БАЗИ ДАНИХ ПЕРЕД ЗАПУСКОМ ВИПРАВЛЕНЬ',
	'DBTools:Error' => 'Помилка',
	'DBTools:Count' => 'Кількість',
	'DBTools:SQLquery' => 'SQL-запит',
	'DBTools:FixitSQLquery' => 'SQL-запит для виправлення бази даних (вказівка)',
	'DBTools:SQLresult' => 'Результат SQL',
	'DBTools:NoError' => 'База даних у порядку',
	'DBTools:HideIds' => 'Список помилок',
	'DBTools:ShowIds' => 'Детальний вигляд',
	'DBTools:ShowReport' => 'Звіт',
	'DBTools:IntegrityCheck' => 'Перевірка цілісності',
	'DBTools:FetchCheck' => 'Перевірка вибірки (тривала)',
	'DBTools:SelectAnalysisType' => 'Виберіть тип аналізу',
	'DBTools:Analyze' => 'Аналізувати',
	'DBTools:Details' => 'Показати подробиці',
	'DBTools:ShowAll' => 'Показати всі помилки',
	'DBTools:Inconsistencies' => 'Невідповідності бази даних',
	'DBTools:DetailedErrorTitle' => '%2$s помилок у класі %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'Список обмежено %1$s помилками',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Сирітський запис у `%1$s`, він повинен мати свій аналог у таблиці `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Недійсний зовнішній ключ %1$s (стовпець: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Відсутній зовнішній ключ %1$s (стовпець: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Недійсне значення для %1$s (стовпець: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Деякі облікові записи користувачів не мають профілів',
	'DBAnalyzer-Integrity-HKInvalid' => 'Пошкоджений ієрархічний ключ `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Помилка підрахунку вибірки в `%1$s`, %2$d записів вибрано / %3$d підраховано',
	'DBAnalyzer-Integrity-FinalClass' => 'Поле `%2$s`.`%1$s` повинно мати те саме значення, що й `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Поле `%2$s`.`%1$s` повинно містити дійсний клас',
));

// Database Info
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'DBTools:DatabaseInfo' => 'Інформація про базу даних',
	'DBTools:Base' => 'База',
	'DBTools:Size' => 'Розмір',
));

// Lost attachments
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'DBTools:LostAttachments' => 'Втрачені вкладення',
	'DBTools:LostAttachments:Disclaimer' => 'Тут ви можете знайти втрачені або помилково переміщені вкладення у вашій базі даних. Це не інструмент відновлення даних, він не відновлює видалені дані.',
	'DBTools:LostAttachments:Button:Analyze' => 'Аналізувати',
	'DBTools:LostAttachments:Button:Restore' => 'Відновити',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Цю дію не можна скасувати. Будь ласка, підтвердіть, що ви хочете відновити вибрані файли.',
	'DBTools:LostAttachments:Button:Busy' => 'Будь ласка, зачекайте...',
	'DBTools:LostAttachments:Step:Analyze' => 'Для початку проскануйте базу даних на наявність втрачених вкладень.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Результат аналізу:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Чудово! Схоже, все в порядку.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Деякі вкладення (%1$d), схоже, знаходяться не в тому місці. Перегляньте наступний список і відмітьте ті, які ви хочете перемістити.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Файл',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Поточне розташування',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Перемістити в...',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Результат відновлення:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d вкладень було відновлено.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Зберігається як "InlineImage"',
	'DBTools:LostAttachments:History' => 'Вкладення "%1$s" відновлено за допомогою інструментів обслуговування БД'
));

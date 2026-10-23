<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Page:DefaultTitle' => 'Користувацький портал %1$s',
	'Page:PleaseWait' => 'Будь ласка, зачекайте...',
	'Page:Home' => 'Головна сторінка',
	'Page:GoPortalHome' => 'Головна сторінка',
	'Page:GoPreviousPage' => 'Попередня сторінка',
	'Page:ReloadPage' => 'Перезавантажити сторінку',
	'Portal:Button:Submit' => 'Застосувати',
	'Portal:Button:Apply' => 'Оновити',
	'Portal:Button:Cancel' => 'Скасувати',
	'Portal:Button:Close' => 'Закрити',
	'Portal:Button:Add' => 'Додати',
	'Portal:Button:Remove' => 'Видалити',
	'Portal:Button:Delete' => 'Видалити',
	'Portal:EnvironmentBanner:Title' => 'Ви знаходитесь в режимі <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Повернутися в режим PRODUCTION',
	'Error:HTTP:400' => 'Некоректний запит',
	'Error:HTTP:401' => 'Помилка аутентифікації',
	'Error:HTTP:404' => 'Сторінка не знайдена',
	'Error:HTTP:500' => 'Ой! Сталася помилка.',
	'Error:HTTP:GetHelp' => 'Будь ласка, зв\'яжіться з вашим адміністратором %1$s, якщо проблема зберігається.',
	'Error:XHR:Fail' => 'Не вдалося завантажити дані. Будь ласка, зв\'яжіться з вашим адміністратором %1$s.',
	'Portal:ErrorUserLoggedOut' => 'Ви вийшли з системи. Виконайте вхід, щоб продовжити роботу.',
	'Portal:Datatables:Language:Processing' => 'Будь ласка, зачекайте...',
	'Portal:Datatables:Language:Search' => 'Фільтр :',
	'Portal:Datatables:Language:LengthMenu' => 'Показувати _MENU_ елементів на сторінці',
	'Portal:Datatables:Language:ZeroRecords' => 'Немає записів',
	'Portal:Datatables:Language:Info' => 'Сторінка _PAGE_ із _PAGES_',
	'Portal:Datatables:Language:InfoEmpty' => 'Немає інформації',
	'Portal:Datatables:Language:InfoFiltered' => 'Відфільтровано з _MAX_ елементів',
	'Portal:Datatables:Language:EmptyTable' => 'Немає даних у цій таблиці',
	'Portal:Datatables:Language:DisplayLength:All' => 'Всі',
	'Portal:Datatables:Language:Paginate:First' => 'Перша',
	'Portal:Datatables:Language:Paginate:Previous' => 'Попередня',
	'Portal:Datatables:Language:Paginate:Next' => 'Наступна',
	'Portal:Datatables:Language:Paginate:Last' => 'Остання',
	'Portal:Datatables:Language:Sort:Ascending' => 'Увімкнути сортування за зростанням',
	'Portal:Datatables:Language:Sort:Descending' => 'Увімкнути сортування за спаданням',
	'Portal:Autocomplete:NoResult' => 'Немає даних',
	'Portal:Attachments:DropZone:Message' => 'Перетягніть файл для додавання вкладення',
	'Portal:File:None' => 'Немає файлу',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Відкрити</a> / <a href="%4$s" class="file_download_link">Завантажити</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'uk',
));

// Object form
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Portal:Form:Caselog:Entry:Close:Tooltip' => 'Закрити цей запис',
	'Portal:Form:Close:Warning' => 'Ви дійсно хочете закрити цю форму? Введені дані можуть бути втрачені.',
	'Portal:Error:ObjectCannotBeCreated' => 'Помилка: об\'єкт не може бути створений. Перевірте пов\'язані об\'єкти та вкладення перед повторною відправкою цієї форми.',
	'Portal:Error:ObjectCannotBeUpdated' => 'Помилка: об\'єкт не може бути оновлений. Перевірте пов\'язані об\'єкти та вкладення перед повторною відправкою цієї форми.',
));

// UserProfile brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:UserProfile:Name' => 'Профіль користувача',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Мій профіль',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Вийти',
	'Brick:Portal:UserProfile:Password:Title' => 'Пароль',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Введіть новий пароль',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Підтвердіть новий пароль',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Будь ласка, зв\'яжіться з вашим адміністратором %1$s для зміни пароля.',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Не вдалося змінити пароль, будь ласка, зв\'яжіться з вашим адміністратором %1$s.',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Персональна інформація',
	'Brick:Portal:UserProfile:Photo:Title' => 'Фотографія',
));

// AggregatePageBrick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Панель управління',
));

// BrowseBrick brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:Browse:Name' => 'Перегляд елементів',
	'Brick:Portal:Browse:Mode:List' => 'Список',
	'Brick:Portal:Browse:Mode:Tree' => 'Дерево',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Плитки',
	'Brick:Portal:Browse:Action:Drilldown' => 'Деталізація',
	'Brick:Portal:Browse:Action:View' => 'Детально',
	'Brick:Portal:Browse:Action:Edit' => 'Змінити',
	'Brick:Portal:Browse:Action:Create' => 'Створити',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Новий %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => 'Розгорнути всі',
	'Brick:Portal:Browse:Tree:CollapseAll' => 'Згорнути всі',
	'Brick:Portal:Browse:Filter:NoData' => 'Немає елементів',
));

// ManageBrick brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:Manage:Name' => 'Управління елементами',
	'Brick:Portal:Manage:Table:NoData' => 'Немає елементів',
	'Brick:Portal:Manage:Table:ItemActions' => 'Дії',
	'Brick:Portal:Manage:DisplayMode:list' => 'Список',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Кругова діаграма',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Стовпчаста діаграма',
	'Brick:Portal:Manage:Others' => 'Інші',
	'Brick:Portal:Manage:All' => 'Всі',
	'Brick:Portal:Manage:Group' => 'Група',
	'Brick:Portal:Manage:fct:count' => 'Всього',
	'Brick:Portal:Manage:fct:sum' => 'Сума',
	'Brick:Portal:Manage:fct:avg' => 'Середнє',
	'Brick:Portal:Manage:fct:min' => 'Мінімум',
	'Brick:Portal:Manage:fct:max' => 'Максимум',
));

// ObjectBrick brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:Object:Name' => 'Об\'єкт',
	'Brick:Portal:Object:Form:Create:Title' => 'Створити %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Оновлення %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Будь ласка, вкажіть наступну інформацію:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Збережено',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s збережено',
	'Brick:Portal:Object:Search:Regular:Title' => 'Вибрати %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Вибрати %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Скопіювати посилання на об\'єкт',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Посилання скопійовано'
));

// CreateBrick brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:Create:Name' => 'Швидке створення',
	'Brick:Portal:Create:ChooseType' => 'Будь ласка, виберіть тип',
));

// Filter brick
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Brick:Portal:Filter:Name' => 'Фільтр',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'наприклад, підключити wi-fi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Шукати',
));

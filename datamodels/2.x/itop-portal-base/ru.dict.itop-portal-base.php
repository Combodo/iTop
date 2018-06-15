<?php

/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 *
 */

// Portal
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Page:DefaultTitle' => 'Пользовательский портал %1$s',
    'Page:PleaseWait' => 'Пожалуйста, подождите...',
    'Page:Home' => 'Домашняя страница',
    'Page:GoPortalHome' => 'Домашняя страница',
    'Page:GoPreviousPage' => 'Предыдущяя страница',
    'Page:ReloadPage' => 'Reload page~~',
    'Portal:Button:Submit' => 'Применить',
    'Portal:Button:Apply' => 'Update~~',
    'Portal:Button:Cancel' => 'Отменить',
    'Portal:Button:Close' => 'Закрыть',
    'Portal:Button:Add' => 'Добавить',
    'Portal:Button:Remove' => 'Удалить',
    'Portal:Button:Delete' => 'Удалить',
    'Error:HTTP:401' => 'Authentication~~',
    'Error:HTTP:404' => 'Страница не найдена',
    'Error:HTTP:500' => 'Упс! Произошла ошибка.',
    'Error:XHR:Fail' => 'Не удалось загрузить данные. Пожалуйста, свяжитесь с вашим администратором %1$s.',
    'Error:HTTP:GetHelp' => 'Пожалуйста, свяжитесь с вашим администратором %1$s, если проблема сохраняется.',
    'Portal:ErrorUserLoggedOut' => 'You are logged out and need to log in again in order to continue.~~',
    'Portal:Datatables:Language:Processing' => 'Пожалуйста, подождите...',
    'Portal:Datatables:Language:Search' => 'Фильтр :',
    'Portal:Datatables:Language:LengthMenu' => 'Показывать _MENU_ элементов на странице',
    'Portal:Datatables:Language:ZeroRecords' => 'Нет записей',
    'Portal:Datatables:Language:Info' => 'Страница _PAGE_ из _PAGES_',
    'Portal:Datatables:Language:InfoEmpty' => 'Нет информации',
    'Portal:Datatables:Language:InfoFiltered' => 'Отфильтровано из _MAX_ элементов',
    'Portal:Datatables:Language:EmptyTable' => 'Нет данных в этой таблице',
    'Portal:Datatables:Language:DisplayLength:All' => 'Все',
    'Portal:Datatables:Language:Paginate:First' => 'Первая',
    'Portal:Datatables:Language:Paginate:Previous' => 'Предыдущая',
    'Portal:Datatables:Language:Paginate:Next' => 'Следующая',
    'Portal:Datatables:Language:Paginate:Last' => 'Последняя',
    'Portal:Datatables:Language:Sort:Ascending' => 'Включить сортировку по возрастанию',
    'Portal:Datatables:Language:Sort:Descending' => 'Включить сортировку по убыванию',
    'Portal:Autocomplete:NoResult' => 'Нет данных',
    'Portal:Attachments:DropZone:Message' => 'Перетащите файл для добавления вложения',
));

// UserProfile brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:UserProfile:Name' => 'Профиль пользователя',
    'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => 'Мой профиль',
    'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => 'Выйти',
    'Brick:Portal:UserProfile:Password:Title' => 'Пароль',
    'Brick:Portal:UserProfile:Password:ChoosePassword' => 'Введите новый пароль',
    'Brick:Portal:UserProfile:Password:ConfirmPassword' => 'Подтвердите новый пароль',
    'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => 'Пожалуйста, свяжитесь с вашим администратором %1$s для изменения пароля.',
    'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => 'Не удалось изменить пароль, пожалуйста, свяжитесь с вашим администратором %1$s.',
    'Brick:Portal:UserProfile:PersonalInformations:Title' => 'Персональная информация',
    'Brick:Portal:UserProfile:Photo:Title' => 'Фотография',
));

// BrowseBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:Browse:Name' => 'Просмотр элементов',
    'Brick:Portal:Browse:Mode:List' => 'Список',
    'Brick:Portal:Browse:Mode:Tree' => 'Дерево',
    'Brick:Portal:Browse:Mode:Mosaic' => 'Mosaic~~',
    'Brick:Portal:Browse:Action:Drilldown' => 'Детализация',
    'Brick:Portal:Browse:Action:View' => 'Подробно',
    'Brick:Portal:Browse:Action:Edit' => 'Изменить',
    'Brick:Portal:Browse:Action:Create' => 'Создать',
    'Brick:Portal:Browse:Action:CreateObjectFromThis' => 'Новый %1$s',
    'Brick:Portal:Browse:Tree:ExpandAll' => 'Развернуть все',
    'Brick:Portal:Browse:Tree:CollapseAll' => 'Свернуть все',
    'Brick:Portal:Browse:Filter:NoData' => 'Нет элементов',
));

// ManageBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:Manage:Name' => 'Управление элементами',
    'Brick:Portal:Manage:Table:NoData' => 'Нет элементов',
    'Brick:Portal:Manage:Table:ItemActions' => 'Actions~~',
    'Brick:Portal:Manage:DisplayMode:list' => 'List~~',
    'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Pie Chart~~',
    'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Bar Chart',
    'Brick:Portal:Manage:Others' => 'Others~~',
    'Brick:Portal:Manage:All' => 'All~~',
    'Brick:Portal:Manage:Group' => 'Group~~',
    'Brick:Portal:Manage:fct:count' => 'Total~~',
    'Brick:Portal:Manage:fct:sum' => 'Sum~~',
    'Brick:Portal:Manage:fct:avg' => 'Average~~',
    'Brick:Portal:Manage:fct:min' => 'Min~~',
    'Brick:Portal:Manage:fct:max' => 'Max~~',
));

// ObjectBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:Object:Name' => 'Object',
    'Brick:Portal:Object:Form:Create:Title' => 'Создать %1$s',
    'Brick:Portal:Object:Form:Edit:Title' => 'Обновление %2$s (%1$s)',
    'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
    'Brick:Portal:Object:Form:Stimulus:Title' => 'Пожалуйста, укажите следующую информацию:',
    'Brick:Portal:Object:Form:Message:Saved' => 'Сохранено',
    'Brick:Portal:Object:Search:Regular:Title' => 'Выбрать %1$s (%2$s)',
    'Brick:Portal:Object:Search:Hierarchy:Title' => 'Выбрать %1$s (%2$s)',
));

// CreateBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:Create:Name' => 'Быстрое создание',
    'Brick:Portal:Create:ChooseType' => 'Please, choose a type~~',
));

// Filter brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
    'Brick:Portal:Filter:Name' => 'Prefilter a brick~~',
    'Brick:Portal:Filter:SearchInput:Placeholder' => 'eg. connect wifi~~',
    'Brick:Portal:Filter:SearchInput:Submit' => 'Search~~',
));

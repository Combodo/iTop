<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
// Portal
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Page:DefaultTitle' => 'Пользовательский портал %1$s',
	'Page:PleaseWait' => 'Пожалуйста, подождите...',
	'Page:Home' => 'Домашняя страница',
	'Page:GoPortalHome' => 'Домашняя страница',
	'Page:GoPreviousPage' => 'Предыдущяя страница',
	'Page:ReloadPage' => 'Перезагрузить страницу',
	'Portal:Button:Submit' => 'Применить',
	'Portal:Button:Apply' => 'Обновить',
	'Portal:Button:Cancel' => 'Отменить',
	'Portal:Button:Close' => 'Закрыть',
	'Portal:Button:Add' => 'Добавить',
	'Portal:Button:Remove' => 'Удалить',
	'Portal:Button:Delete' => 'Удалить',
	'Portal:EnvironmentBanner:Title' => 'Вы находитесь в режиме <strong>%1$s</strong>',
	'Portal:EnvironmentBanner:GoToProduction' => 'Вернуться в режим PRODUCTION',
	'Error:HTTP:400' => 'Некорректный запрос',
	'Error:HTTP:401' => 'Ошибка аутентификации',
	'Error:HTTP:404' => 'Страница не найдена',
	'Error:HTTP:500' => 'Упс! Произошла ошибка.',
	'Error:HTTP:GetHelp' => 'Пожалуйста, свяжитесь с вашим администратором %1$s, если проблема сохраняется.',
	'Error:XHR:Fail' => 'Не удалось загрузить данные. Пожалуйста, свяжитесь с вашим администратором %1$s.',
	'Portal:ErrorUserLoggedOut' => 'Вы вышли из системы. Выполните вход, чтобы продолжить работу.',
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
	'Portal:File:None' => 'Нет файла',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Открыть</a> / <a href="%4$s" class="file_download_link">Скачать</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'ru', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Вы действительно хотите закрыть эту форму? Введённые данные могут быть утеряны.',
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

// AggregatePageBrick
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Дашборд',
));

// BrowseBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Brick:Portal:Browse:Name' => 'Просмотр элементов',
	'Brick:Portal:Browse:Mode:List' => 'Список',
	'Brick:Portal:Browse:Mode:Tree' => 'Дерево',
	'Brick:Portal:Browse:Mode:Mosaic' => 'Плитки',
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
	'Brick:Portal:Manage:Table:ItemActions' => 'Действия',
	'Brick:Portal:Manage:DisplayMode:list' => 'Список',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => 'Круговая диаграмма',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => 'Столбчатая диаграмма',
	'Brick:Portal:Manage:Others' => 'Другие',
	'Brick:Portal:Manage:All' => 'Все',
	'Brick:Portal:Manage:Group' => 'Группа',
	'Brick:Portal:Manage:fct:count' => 'Всего',
	'Brick:Portal:Manage:fct:sum' => 'Сумма',
	'Brick:Portal:Manage:fct:avg' => 'Среднее',
	'Brick:Portal:Manage:fct:min' => 'Минимум',
	'Brick:Portal:Manage:fct:max' => 'Максимум',
));

// ObjectBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Brick:Portal:Object:Name' => 'Object',
	'Brick:Portal:Object:Form:Create:Title' => 'Создать %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => 'Обновление %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Пожалуйста, укажите следующую информацию:',
	'Brick:Portal:Object:Form:Message:Saved' => 'Сохранено',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '%1$s сохранено',
	'Brick:Portal:Object:Search:Regular:Title' => 'Выбрать %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => 'Выбрать %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s',
	'Brick:Portal:Object:Copy:Tooltip' => 'Скопировать ссылку на объект',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Ссылка скопирована'
));

// CreateBrick brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Brick:Portal:Create:Name' => 'Быстрое создание',
	'Brick:Portal:Create:ChooseType' => 'Пожалуйста, выберите тип',
));

// Filter brick
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Brick:Portal:Filter:Name' => 'Фильтр',
	'Brick:Portal:Filter:SearchInput:Placeholder' => 'например, подключить wi-fi',
	'Brick:Portal:Filter:SearchInput:Submit' => 'Искать',
));

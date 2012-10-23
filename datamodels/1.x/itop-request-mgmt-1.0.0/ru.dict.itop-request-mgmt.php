<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Localized data
 *
 * @author      Vladimir Shilov <shilow@ukr.net>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => 'Техподдержка',
	'Menu:UserRequest:Overview' => 'Обзор',
	'Menu:UserRequest:Overview+' => 'Обзор',
	'Menu:NewUserRequest' => 'Новый пользовательский запрос',
	'Menu:NewUserRequest+' => 'Создать новый тикет пользовательского запроса',
	'Menu:SearchUserRequests' => 'Поиск пользовательских запросов',
	'Menu:SearchUserRequests+' => 'Поиск тикетов пользовательских запросов',
	'Menu:UserRequest:Shortcuts' => 'Ярлыки',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Запросы назначенные на меня',
	'Menu:UserRequest:MyRequests+' => 'Запросы назначенные на меня (как агент)',
	'Menu:UserRequest:EscalatedRequests' => 'Эскалированные запросы',
	'Menu:UserRequest:EscalatedRequests+' => 'Эскалированные запросы',
	'Menu:UserRequest:OpenRequests' => 'Все открытые запросы',
	'Menu:UserRequest:OpenRequests+' => 'Все открытые запросы',
));

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//
// Class: UserRequest
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:UserRequest' => 'Пользовательский запрос',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Тип запроса',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Информация',
	'Class:UserRequest/Attribute:request_type/Value:information+' => 'Информация',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Номер',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => 'Номер',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Запрос сервиса',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'Запрос сервиса',
	'Class:UserRequest/Attribute:freeze_reason' => 'Причина заморозки',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Назначить',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Переназначить',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Пометить как решённое',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Закрыть',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Пометить как замороженное',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));

?>

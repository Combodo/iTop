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

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Relation:impacts/Description' => 'Elements impacted by',
	'Relation:impacts/VerbUp' => 'Impact...',
	'Relation:impacts/VerbDown' => 'Elements impacted by...',
	'Relation:depends on/Description' => 'Elements this element depends on',
	'Relation:depends on/VerbUp' => 'Depends on...',
	'Relation:depends on/VerbDown' => 'Impacts...',
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

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

//
// Class: Organization
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Organization' => 'Организация',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Название',
	'Class:Organization/Attribute:name+' => 'Общее название',
	'Class:Organization/Attribute:code' => 'Код',
	'Class:Organization/Attribute:code+' => 'Код организации (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Активный',
	'Class:Organization/Attribute:status/Value:active+' => 'Активный',
	'Class:Organization/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Organization/Attribute:parent_id' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_id+' => 'Вышестоящая организация',
	'Class:Organization/Attribute:parent_name' => 'Название вышестоящей',
	'Class:Organization/Attribute:parent_name+' => 'Название вышестоящей организации',
));


//
// Class: Location
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Location' => 'Расположение',
	'Class:Location+' => 'Любой типа расположения: регион, страна, область, город, площадка, здание, этаж, кoom, стойка,...',
	'Class:Location/Attribute:name' => 'Название',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Статус',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Активный',
	'Class:Location/Attribute:status/Value:active+' => 'Активный',
	'Class:Location/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:Location/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Location/Attribute:org_id' => 'Владелец организации',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Название владельца организации',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Адрес',
	'Class:Location/Attribute:address+' => 'Почтовый адрес',
	'Class:Location/Attribute:postal_code' => 'Индекс',
	'Class:Location/Attribute:postal_code+' => 'Почтовый индекс',
	'Class:Location/Attribute:city' => 'Город',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Страна',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Вышестоящее расположение',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Название вышестоящего',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Контакты',
	'Class:Location/Attribute:contact_list+' => 'Контакты расположенные в этом месте',
	'Class:Location/Attribute:infra_list' => 'Инфраструктура',
	'Class:Location/Attribute:infra_list+' => 'КЕ расположенные в этом месте',
));
//
// Class: Group
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Group' => 'Группа',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Название',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Сатус',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Реализация',
	'Class:Group/Attribute:status/Value:implementation+' => 'Реализация',
	'Class:Group/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:Group/Attribute:status/Value:production' => 'Производство',
	'Class:Group/Attribute:status/Value:production+' => 'Производство',
	'Class:Group/Attribute:org_id' => 'Организация',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Название',
	'Class:Group/Attribute:owner_name+' => 'Общее название',
	'Class:Group/Attribute:description' => 'Описание',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Тип',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Вышестоящая группа',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Название',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Связанные КЕ',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkGroupToCI' => 'Группа / КЕ',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Группа',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Название',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'КЕ',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Название',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'Статус КЕ',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Причина',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Class: Contact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Contact' => 'Контакт',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Название',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Статус',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Активный',
	'Class:Contact/Attribute:status/Value:active+' => 'Активный',
	'Class:Contact/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Contact/Attribute:org_id' => 'Организация',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Организация',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Телефон',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Расположение',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Расположение',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'КЕ-ы',
	'Class:Contact/Attribute:ci_list+' => 'КЕ связанные с контактом',
	'Class:Contact/Attribute:contract_list' => 'Договора',
	'Class:Contact/Attribute:contract_list+' => 'Договора связанные с контактом',
	'Class:Contact/Attribute:service_list' => 'Сервисы',
	'Class:Contact/Attribute:service_list+' => 'Сервисы связанные с контактом',
	'Class:Contact/Attribute:ticket_list' => 'Ticketы',
	'Class:Contact/Attribute:ticket_list+' => 'Ticketы связанные с контактом',
	'Class:Contact/Attribute:team_list' => 'Команды',
	'Class:Contact/Attribute:team_list+' => 'Команды этого контакта',
	'Class:Contact/Attribute:finalclass' => 'Тип',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Person' => 'Человек',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Имя',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'ID Сотрудника',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Team' => 'Команда',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Члены',
	'Class:Team/Attribute:member_list+' => 'Контакты входящие в команду',
));

//
// Class: lnkTeamToContact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkTeamToContact' => 'Члены команды',
	'Class:lnkTeamToContact+' => 'Члены команды',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Команда',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Член',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Расположение',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Телефон',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Роль',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Document' => 'Документ',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Название',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Организация',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => 'Название организации',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => 'Описание',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Тип',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Договор',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Карта сети',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Презентация',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Обучение',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'White Paper',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Рабочие инструкции',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Статус',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Черновик',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Опубликованный',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'КЕ',
	'Class:Document/Attribute:ci_list+' => 'КЕ относящиеся к этому документу',
	'Class:Document/Attribute:contract_list' => 'Договора',
	'Class:Document/Attribute:contract_list+' => 'Договора относящиеся к этому документу',
	'Class:Document/Attribute:service_list' => 'Сервисы',
	'Class:Document/Attribute:service_list+' => 'Сервисы относящиеся к этому документу',
	'Class:Document/Attribute:ticket_list' => 'Ticketы',
	'Class:Document/Attribute:ticket_list+' => 'Ticketы относящиеся к этому документу',
	'Class:Document:PreviewTab' => 'Предпросмотр',
));

//
// Class: WebDoc
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WebDoc' => 'Web документ',
	'Class:WebDoc+' => 'Документ доступный на другом web-сервере',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Note' => 'Заметка',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Текст',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FileDoc' => 'Документ (файл)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Содержимое',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Licence' => 'Лицензия',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Поставщик',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'Владелец',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => 'Название',
	'Class:Licence/Attribute:org_name+' => 'Общее название',
	'Class:Licence/Attribute:product' => 'Продукт',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Название',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Начальная дата',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Конечная дата',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Ключ',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Сфера',
	'Class:Licence/Attribute:scope+' => 'Сфера применения',
	'Class:Licence/Attribute:usage_limit' => 'Ограничение использования',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Использование',
	'Class:Licence/Attribute:usage_list+' => 'Экземпляры Приложений использующие эту лицензию',
));


//
// Class: Subnet
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Subnet' => 'Подсеть',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Name',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Организация-владелец',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Описание',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP маска',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Patch' => 'Патч',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Название',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Описание',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Область применения',
	'Class:Patch/Attribute:target_sw+' => 'Целевое ПО (ОС или приложеиние)',
	'Class:Patch/Attribute:version' => 'Версия',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Тир',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Приложение',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'ОС',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Безопастность',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Сервис Пак',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Устройства',
	'Class:Patch/Attribute:ci_list+' => 'Устройства на которые установлен патч',
));

//
// Class: Software
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Software' => 'Програмное оеспечение',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Название',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Описание',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Установки',
	'Class:Software/Attribute:instance_list+' => 'Экземпляры этогоПО',
	'Class:Software/Attribute:finalclass' => 'Тип',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Application' => 'Приложение',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Название',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Описание',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Установки',
	'Class:Application/Attribute:instance_list+' => 'Экземпляры этого приложения',
));

//
// Class: DBServer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DBServer' => 'База данных',
	'Class:DBServer+' => 'Сервер базы данных SW',
	'Class:DBServer/Attribute:instance_list' => 'Установки',
	'Class:DBServer/Attribute:instance_list+' => 'Экземпляры этой базы данных',
));

//
// Class: lnkPatchToCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkPatchToCI' => 'Использование патчей',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Патч',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Патч',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'КЕ',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'КЕ',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'Статус КЕ',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FunctionalCI' => 'Функционал КЕ',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Название',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Статус',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Производственный',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Организация-владелец',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Организация-владелец',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Критичность для бизнеса',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Высокая',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Низкая',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Средняя',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'Контакты',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Контакты для этой КЕ',
	'Class:FunctionalCI/Attribute:document_list' => 'Документы',
	'Class:FunctionalCI/Attribute:document_list+' => 'Документы для этой КЕ',
	'Class:FunctionalCI/Attribute:solution_list' => 'Программные решения',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Программные решения использующие эту КЕ',
	'Class:FunctionalCI/Attribute:contract_list' => 'Договора',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Договора поддерживающие эту КЕ',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Ticketы',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Ticketы связанные с этой КЕ',
	'Class:FunctionalCI/Attribute:finalclass' => 'Тип',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SoftwareInstance' => 'Экземпляры ПО',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Устройство',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Устройство',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Лицензия',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Лицензия',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'ПО',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Версия',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Описание',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ApplicationInstance' => 'Экземпляры приложений',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => 'ПО',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => 'Название',
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DBServerInstance' => 'Экземпляры серверов баз данных',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => 'ПО',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'Название',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Базы данных',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Источники баз данных',
));


//
// Class: DatabaseInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DatabaseInstance' => 'Экземпляры баз данных',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Сервер базы данных',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Версия базы данных',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Описание',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ApplicationSolution' => 'Программные решения',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Описание',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'КЕ',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'КЕ составляющие решение',
	'Class:ApplicationSolution/Attribute:process_list' => 'Бизнес-процессы',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Бизнес-процессы использующие решение',
));

//
// Class: BusinessProcess
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:BusinessProcess' => 'Бизнес-процессы',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Описание',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Програмные решения',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Используемые програмные решения',
));

//
// Class: ConnectableCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ConnectableCI' => 'Подключаемые КЕ',
	'Class:ConnectableCI+' => 'Физические КЕ',
	'Class:ConnectableCI/Attribute:brand' => 'Производитель',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Модель',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Серийный номер',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Справочник активов',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkInterface' => 'Сетевой интерфейс',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'Устройство',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Устройство',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Логически тип',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Резерв',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Логический',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Порт',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Первичный',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Вторичный',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Физический тип',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'Адрес IP',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'Маска IP',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'Адрес MAC',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Скорость',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Дуплекс',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Неизвестно',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Подключен к',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Подключенный интерфейс',
	'Class:NetworkInterface/Attribute:connected_name' => 'Подключен к',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Подключенное устройство',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'Устройство',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Тип линка',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Device' => 'Устройство',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Сетевой интерфейс',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PC' => 'ПК',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'ЦПУ',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'ОЗУ',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Жёсткий диск',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'Семейство ОС',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'Версия ОС',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Приложения',
	'Class:PC/Attribute:application_list+' => 'Приложения установленные на этом ПК',
	'Class:PC/Attribute:patch_list' => 'Патчи',
	'Class:PC/Attribute:patch_list+' => 'Патчи установленные на этом ПК',
));

//
// Class: MobileCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:MobileCI' => 'Мбильные КЕ',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:MobilePhone' => 'Мобильный телефон',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Номер телефона',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Аппаратный PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:InfrastructureCI' => 'Инфраструктура КЕ',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Описание',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Расположение',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Расположение',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Расположение подробно',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'IP управление',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Шлюз по-умолчанию',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkDevice' => 'Сетевое устройство',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Тип',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Firewall',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Хаб',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Load Balancer',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Маршрутизатор',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Коммутатор',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'Версия IOS',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'ОЗУ',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'Чтение SNMP',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'Запись SNMP',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Server' => 'Сервер',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'ЦПК',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'ОЗУ',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Жёсткий диск',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'Семейство ОС',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'Версия ОС',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Приложения',
	'Class:Server/Attribute:application_list+' => 'Приложения установленные на этом сервере',
	'Class:Server/Attribute:patch_list' => 'Патчи',
	'Class:Server/Attribute:patch_list+' => 'Патчи установленные на этом сервере',
));

//
// Class: Printer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Printer' => 'Принтер',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Тип',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Принтер',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Технология',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Чернильный',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Лазерный',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkCIToDoc' => 'Документ/КЕ',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'КЕ',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'КЕ',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'Статус КЕ',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Документ',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Документ',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Тип документа',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Статус документа',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkCIToContact' => 'КЕ/Контакт',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'КЕ',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'КЕ',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'Статус КЕ',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Контакт',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Контакт',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'E-mail контакта',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Роль',
	'Class:lnkCIToContact/Attribute:role+' => 'Роль контакта в отношении КЕ',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSolutionToCI' => 'КЕ/Решение',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Програмное решение',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Програмное решение',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'КЕ',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'КЕ',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'Статус КЕ',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Утилита',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Утилита КЕ в решении',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkProcessToSolution' => 'Бизнес-процесс/Решение',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Програмное решение',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Програмное решение',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Процесс',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Процесс',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Причина',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Более подробная информация о связи между процессом и решением',
));



//
// Class extensions
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
'Class:Subnet/Tab:IPUsage' => 'Использование IP',
'Class:Subnet/Tab:IPUsage-explain' => 'Интерфейсв имеющие IP в диапазоне с: <em>%1$s</em> по <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'Свободные IP',
'Class:Subnet/Tab:FreeIPs-count' => 'Свободные IP: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Перечень 10 свободных IP адресов',
));

//
// Application Menu
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
'Menu:Catalogs' => 'Каталоги',
'Menu:Catalogs+' => 'Типы данных',
'Menu:Audit' => 'Аудит',
'Menu:Audit+' => 'Аудит',
'Menu:Organization' => 'Организации',
'Menu:Organization+' => 'Все организации',
'Menu:Application' => 'Приложения',
'Menu:Application+' => 'Все приложения',
'Menu:DBServer' => 'Серверы баз данных',
'Menu:DBServer+' => 'Серверы баз данных',
'Menu:Audit' => 'Аудит',
'Menu:ConfigManagement' => 'Управление конфигурациями',
'Menu:ConfigManagement+' => 'Управление конфигурациями',
'Menu:ConfigManagementOverview' => 'Обзор',
'Menu:ConfigManagementOverview+' => 'Обзор',
'Menu:Contact' => 'Контакты',
'Menu:Contact+' => 'Контакты',
'Menu:Person' => 'Лица',
'Menu:Person+' => 'Все лица',
'Menu:Team' => 'Команды',
'Menu:Team+' => 'Все команды',
'Menu:Document' => 'Документы',
'Menu:Document+' => 'Все документы',
'Menu:Location' => 'Расположения',
'Menu:Location+' => 'Все расположения',
'Menu:ConfigManagementCI' => 'Конфигурационные единицы',
'Menu:ConfigManagementCI+' => 'Конфигурационные единицы',
'Menu:BusinessProcess' => 'Бизнес-процессы',
'Menu:BusinessProcess+' => 'Все бизнес-процессы',
'Menu:ApplicationSolution' => 'Програмные решения',
'Menu:ApplicationSolution+' => 'Все програмные решения',
'Menu:ConfigManagementSoftware' => 'Управление приложениями',
'Menu:Licence' => 'Лицензии',
'Menu:Licence+' => 'Все лицензии',
'Menu:Patch' => 'Патчи',
'Menu:Patch+' => 'Все патчи',
'Menu:ApplicationInstance' => 'Установленное ПО',
'Menu:ApplicationInstance+' => 'Приложения и сервера БД',
'Menu:ConfigManagementHardware' => 'Управление инфраструктурой',
'Menu:Subnet' => 'Подсети',
'Menu:Subnet+' => 'Все подсети',
'Menu:NetworkDevice' => 'Сетевые устройства',
'Menu:NetworkDevice+' => 'Все сетевые устройства',
'Menu:Server' => 'Серверы',
'Menu:Server+' => 'Все серверы',
'Menu:Printer' => 'Принтеры',
'Menu:Printer+' => 'Все принтеры',
'Menu:MobilePhone' => 'Мобильные телефоны',
'Menu:MobilePhone+' => 'Все мобильные телефоны',
'Menu:PC' => 'Персональные компьютеры',
'Menu:PC+' => 'Все ПК',
'Menu:NewContact' => 'Новый контакт',
'Menu:NewContact+' => 'Новый контакт',
'Menu:SearchContacts' => 'Поиск контактов',
'Menu:SearchContacts+' => 'Поиск контактов',
'Menu:NewCI' => 'Новый КЕ',
'Menu:NewCI+' => 'Новый КЕ',
'Menu:SearchCIs' => 'Поиск КЕ',
'Menu:SearchCIs+' => 'Поиск КЕ',
'Menu:ConfigManagement:Devices' => 'Устройства',
'Menu:ConfigManagement:AllDevices' => 'Количество устройств: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'ПО и приложения',
'Menu:ConfigManagement:Misc' => 'Разное',
'Menu:Group' => 'Группы КЕ',
'Menu:Group+' => 'Группы КЕ',
'Menu:ConfigManagement:Shortcuts' => 'Ярлыки',
'Menu:ConfigManagement:AllContacts' => 'Все контакты: %1$d',

));
?>

<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Benjamin Planque <benjamin.planque@combodo.com>
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Organization' => 'Організація',
	'Class:Organization+' => 'Організація',
	'Class:Organization/Attribute:name' => 'Назва',
	'Class:Organization/Attribute:name+' => 'Назва',
	'Class:Organization/Attribute:code' => 'Код',
	'Class:Organization/Attribute:code+' => 'Код в реєстрі організацій або інший ідентифікатор',
	'Class:Organization/Attribute:status' => 'Статус',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Активний',
	'Class:Organization/Attribute:status/Value:active+' => 'Активний',
	'Class:Organization/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Неактивний',
	'Class:Organization/Attribute:parent_id' => 'Вищестояща',
	'Class:Organization/Attribute:parent_id+' => 'Вищестояща організація',
	'Class:Organization/Attribute:parent_name' => 'Вищестояща',
	'Class:Organization/Attribute:parent_name+' => 'Вищестояща організація',
	'Class:Organization/Attribute:deliverymodel_id' => 'Модель сервісів',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Модель надання сервісів',
	'Class:Organization/Attribute:deliverymodel_name' => 'Модель сервісів',
	'Class:Organization/Attribute:deliverymodel_name+' => 'Модель надання сервісів',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Вищестояща',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Вищестояща організація',
	'Class:Organization/Attribute:overview' => 'Огляд',
	'Organization:Overview:FunctionalCIs' => 'Конфігураційні одиниці цієї організації',
	'Organization:Overview:FunctionalCIs:subtitle' => 'за типом',
	'Organization:Overview:Users' => 'Користувачі '.ITOP_APPLICATION_SHORT.' цієї організації',
));

//
// Class: Location
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Location' => 'Розташування',
	'Class:Location+' => 'Типи розташування: Регіон, Країна, Місто, Сайт, Будівля, Поверх, Кімната, Стійка і т.п.',
	'Class:Location/Attribute:name' => 'Назва',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Статус',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Активний',
	'Class:Location/Attribute:status/Value:active+' => 'Активний',
	'Class:Location/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:Location/Attribute:status/Value:inactive+' => 'Неактивний',
	'Class:Location/Attribute:org_id' => 'Організація',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Організація',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Адреса',
	'Class:Location/Attribute:address+' => 'Поштова адреса',
	'Class:Location/Attribute:postal_code' => 'Індекс',
	'Class:Location/Attribute:postal_code+' => 'Поштовий індекс',
	'Class:Location/Attribute:city' => 'Місто',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Країна',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Пристрої',
	'Class:Location/Attribute:physicaldevice_list+' => 'Пристрої в цьому розташуванні',
	'Class:Location/Attribute:person_list' => 'Контакти',
	'Class:Location/Attribute:person_list+' => 'Контакти в цьому розташуванні',
));

//
// Class: Contact
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Contact' => 'Контакт',
	'Class:Contact+' => 'Контакт',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Contact/Attribute:name' => 'Назва',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Статус',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Активний',
	'Class:Contact/Attribute:status/Value:active+' => 'Активний',
	'Class:Contact/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Неактивний',
	'Class:Contact/Attribute:org_id' => 'Організація',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Організація',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Телефон',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Сповіщати',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'ні',
	'Class:Contact/Attribute:notify/Value:no+' => 'ні',
	'Class:Contact/Attribute:notify/Value:yes' => 'так',
	'Class:Contact/Attribute:notify/Value:yes+' => 'так',
	'Class:Contact/Attribute:function' => 'Функція',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'КО',
	'Class:Contact/Attribute:cis_list+' => 'Пов\'язані конфігураційні одиниці',
	'Class:Contact/Attribute:finalclass' => 'Тип контакту',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Person' => 'Персона',
	'Class:Person+' => 'Персона',
	'Class:Person/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Person/Attribute:name' => 'Прізвище',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Ім\'я',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Номер співробітника',
	'Class:Person/Attribute:employee_number+' => 'Табельний номер співробітника або т.п.',
	'Class:Person/Attribute:mobile_phone' => 'Мобільний телефон',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Розташування',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Розташування',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Керівник',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Керівник',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Команди',
	'Class:Person/Attribute:team_list+' => 'Команди за участі персони',
	'Class:Person/Attribute:tickets_list' => 'Тікети',
	'Class:Person/Attribute:tickets_list+' => 'Пов\'язані тікети',
	'Class:Person/Attribute:user_list' => 'Користувачі',
	'Class:Person/Attribute:user_list+' => 'Всі користувачі, пов\'язані з цією персоною',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Керівник',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Фотографія',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Номер співробітника повинен бути унікальним в організації',
	'Class:Person/UniquenessRule:employee_number' => 'В організації \'$this->org_name$\' вже є персона з таким номером співробітника',
	'Class:Person/UniquenessRule:name+' => 'Ім\'я співробітника повинно бути унікальним всередині організації',
	'Class:Person/UniquenessRule:name' => 'В організації \'$this->org_name$\' вже є персона з таким іменем',
	'Class:Person/Error:ChangingOrgDenied' => 'Неможливо перемістити цю персону в організацію \'%1$s\', оскільки це порушить її доступ до Користувацького порталу, адже пов\'язаний користувач не має дозволу на цю організацію',
));

//
// Class: Team
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Team' => 'Команда',
	'Class:Team+' => 'Команда',
	'Class:Team/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Team/Attribute:persons_list' => 'Учасники',
	'Class:Team/Attribute:persons_list+' => 'Учасники команди',
	'Class:Team/Attribute:tickets_list' => 'Тікети',
	'Class:Team/Attribute:tickets_list+' => 'Всі тікети, призначені на команду',
));

//
// Class: Document
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Document' => 'Документ',
	'Class:Document+' => 'Документ',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Document/Attribute:name' => 'Назва',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Організація',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Організація',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Тип документа',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Тип документа',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Версія',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Опис',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Статус',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Чернетка',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Опублікований',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'КО',
	'Class:Document/Attribute:cis_list+' => 'Пов\'язані конфігураційні одиниці',
	'Class:Document/Attribute:finalclass' => 'Тип',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DocumentFile' => 'Файл',
	'Class:DocumentFile+' => 'Файл',
	'Class:DocumentFile/Attribute:file' => 'Файл',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DocumentNote' => 'Замітка',
	'Class:DocumentNote+' => 'Замітка',
	'Class:DocumentNote/Attribute:text' => 'Замітка',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DocumentWeb' => 'Веб-документ',
	'Class:DocumentWeb+' => 'Веб-документ',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Typology' => 'Типологія',
	'Class:Typology+' => 'Типологія',
	'Class:Typology/Attribute:name' => 'Назва',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Тип',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DocumentType' => 'Тип документа',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ContactType' => 'Тип контакту',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkPersonToTeam' => 'Зв\'язок Персона/Команда',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s~~',
	'Class:lnkPersonToTeam/Name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Команда',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Команда',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Персона',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Персона',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Роль',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Роль',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:DataAdministration' => 'Адміністрування даних',
	'Menu:DataAdministration+' => 'Адміністрування даних',
	'Menu:Catalogs' => 'Каталоги',
	'Menu:Catalogs+' => 'Каталоги',
	'Menu:Audit' => 'Аудит',
	'Menu:Audit+' => 'Аудит',
	'Menu:CSVImport' => 'Імпорт CSV',
	'Menu:CSVImport+' => 'Масове створення або оновлення об\'єктів',
	'Menu:Organization' => 'Організації',
	'Menu:Organization+' => 'Всі організації',
	'Menu:ConfigManagement' => 'Управління конфігураціями',
	'Menu:ConfigManagement+' => 'Управління конфігураціями',
	'Menu:ConfigManagementCI' => 'Конфігураційні одиниці',
	'Menu:ConfigManagementCI+' => 'Конфігураційні одиниці',
	'Menu:ConfigManagementOverview' => 'Огляд',
	'Menu:ConfigManagementOverview+' => 'Огляд',
	'Menu:Contact' => 'Контакти',
	'Menu:Contact+' => 'Контакти',
	'Menu:Contact:Count' => '%1$d Контактів',
	'Menu:Person' => 'Персони',
	'Menu:Person+' => 'Всі персони',
	'Menu:Team' => 'Команди',
	'Menu:Team+' => 'Всі команди',
	'Menu:Document' => 'Документи',
	'Menu:Document+' => 'Всі документи',
	'Menu:Location' => 'Розташування',
	'Menu:Location+' => 'Всі розташування',
	'Menu:NewContact' => 'Новий контакт',
	'Menu:NewContact+' => 'Новий контакт',
	'Menu:SearchContacts' => 'Пошук контактів',
	'Menu:SearchContacts+' => 'Пошук контактів',
	'Menu:ConfigManagement:Shortcuts' => 'Ярлики',
	'Menu:ConfigManagement:AllContacts' => 'Всі контакти: %1$d',
	'Menu:Typology' => 'Типологія',
	'Menu:Typology+' => 'Типологія',
	'UI_WelcomeMenu_AllConfigItems' => 'Всі конфігураційні одиниці',
	'Menu:ConfigManagement:Typology' => 'Налаштування типології',
));

// Add translation for Fieldsets

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Person:info' => 'Основна інформація',
	'User:info' => 'Основна інформація',
	'User:profiles' => 'Профілі (мінімум один)',
	'Person:personal_info' => 'Особиста інформація',
	'Person:notifiy' => 'Сповіщення',
));

// Themes
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'theme:fullmoon' => 'Повний місяць',
	'theme:test-red' => 'Тестовий екземпляр (Червоний)',
));

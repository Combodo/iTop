<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
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
	'Class:Organization+' => 'Организация',
	'Class:Organization/Attribute:name' => 'Название',
	'Class:Organization/Attribute:name+' => 'Название',
	'Class:Organization/Attribute:code' => 'Код',
	'Class:Organization/Attribute:code+' => 'Код в реестре организаций или другой идентификатор',
	'Class:Organization/Attribute:status' => 'Статус',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Активный',
	'Class:Organization/Attribute:status/Value:active+' => 'Активный',
	'Class:Organization/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Organization/Attribute:parent_id' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_id+' => 'Вышестоящая организация',
	'Class:Organization/Attribute:parent_name' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_name+' => 'Вышестоящая организация',
	'Class:Organization/Attribute:deliverymodel_id' => 'Модель услуг',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Модель предоставления услуг',
	'Class:Organization/Attribute:deliverymodel_name' => 'Модель услуг',
	'Class:Organization/Attribute:deliverymodel_name+' => 'Модель предоставления услуг',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Вышестоящая организация',
	'Class:Organization/Attribute:overview' => 'Обзор',
	'Organization:Overview:FunctionalCIs' => 'Конфигурационные единицы этой организации',
	'Organization:Overview:FunctionalCIs:subtitle' => 'по типу',
	'Organization:Overview:Users' => 'Пользователи iTop этой организации',
));

//
// Class: Location
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Location' => 'Расположение',
	'Class:Location+' => 'Типы расположения: Регион, Страна, Город, Сайт, Здание, Этаж, Комната, Стойка и т.п.',
	'Class:Location/Attribute:name' => 'Название',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Статус',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Активный',
	'Class:Location/Attribute:status/Value:active+' => 'Активный',
	'Class:Location/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:Location/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Location/Attribute:org_id' => 'Организация',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Организация',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Адрес',
	'Class:Location/Attribute:address+' => 'Почтовый адрес',
	'Class:Location/Attribute:postal_code' => 'Индекс',
	'Class:Location/Attribute:postal_code+' => 'Почтовый индекс',
	'Class:Location/Attribute:city' => 'Город',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Страна',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Устройства',
	'Class:Location/Attribute:physicaldevice_list+' => 'Устройства в этом расположении',
	'Class:Location/Attribute:person_list' => 'Контакты',
	'Class:Location/Attribute:person_list+' => 'Контакты в этом расположении',
));

//
// Class: Contact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Contact' => 'Контакт',
	'Class:Contact+' => 'Контакт',
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
	'Class:Contact/Attribute:notify' => 'Уведомлять',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'нет',
	'Class:Contact/Attribute:notify/Value:no+' => 'нет',
	'Class:Contact/Attribute:notify/Value:yes' => 'да',
	'Class:Contact/Attribute:notify/Value:yes+' => 'да',
	'Class:Contact/Attribute:function' => 'Функция',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'КЕ',
	'Class:Contact/Attribute:cis_list+' => 'Связанные конфигурационные единицы',
	'Class:Contact/Attribute:finalclass' => 'Тип контакта',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Person' => 'Персона',
	'Class:Person+' => 'Персона',
	'Class:Person/Attribute:name' => 'Фамилия',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Имя',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Номер сотрудника',
	'Class:Person/Attribute:employee_number+' => 'Табельный номер сотрудника или т.п.',
	'Class:Person/Attribute:mobile_phone' => 'Мобильный телефон',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Расположение',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Расположение',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Руководитель',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Руководитель',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Команды',
	'Class:Person/Attribute:team_list+' => 'Команды с участием персоны',
	'Class:Person/Attribute:tickets_list' => 'Тикеты',
	'Class:Person/Attribute:tickets_list+' => 'Связанные тикеты',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Руководитель',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Фотография',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Номер сотрудника должен быть уникальным в организации',
	'Class:Person/UniquenessRule:employee_number' => 'В организации \'$this->org_name$\' уже есть персона с таким номером сотрудника',
	'Class:Person/UniquenessRule:name+' => 'Имя сотрудника должно быть уникальным внутри организации',
	'Class:Person/UniquenessRule:name' => 'В организации \'$this->org_name$\' уже есть персона с таким именем',
));

//
// Class: Team
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Team' => 'Команда',
	'Class:Team+' => 'Команда',
	'Class:Team/Attribute:persons_list' => 'Участники',
	'Class:Team/Attribute:persons_list+' => 'Участники команды',
	'Class:Team/Attribute:tickets_list' => 'Тикеты',
	'Class:Team/Attribute:tickets_list+' => 'Все тикеты, назначенные на команду',
));

//
// Class: Document
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Document' => 'Документ',
	'Class:Document+' => 'Документ',
	'Class:Document/Attribute:name' => 'Название',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Организация',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Организация',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Тип документа',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Тип документа',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Версия',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Описание',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Статус',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Черновик',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Опубликованный',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'КЕ',
	'Class:Document/Attribute:cis_list+' => 'Связанные конфигурационные единицы',
	'Class:Document/Attribute:finalclass' => 'Тип',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentFile' => 'Файл',
	'Class:DocumentFile+' => 'Файл',
	'Class:DocumentFile/Attribute:file' => 'Файл',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentNote' => 'Заметка',
	'Class:DocumentNote+' => 'Заметка',
	'Class:DocumentNote/Attribute:text' => 'Заметка',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentWeb' => 'Веб-документ',
	'Class:DocumentWeb+' => 'Веб-документ',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Typology' => 'Типология',
	'Class:Typology+' => 'Типология',
	'Class:Typology/Attribute:name' => 'Название',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Тип',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentType' => 'Тип документа',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ContactType' => 'Тип контакта',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkPersonToTeam' => 'Связь Персона/Команда',
	'Class:lnkPersonToTeam+' => '',
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

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:DataAdministration' => 'Администрирование данных',
	'Menu:DataAdministration+' => 'Администрирование данных',
	'Menu:Catalogs' => 'Каталоги',
	'Menu:Catalogs+' => 'Каталоги',
	'Menu:Audit' => 'Аудит',
	'Menu:Audit+' => 'Аудит',
	'Menu:CSVImport' => 'Импорт CSV',
	'Menu:CSVImport+' => 'Массовое создание или обновление объектов',
	'Menu:Organization' => 'Организации',
	'Menu:Organization+' => 'Все организации',
	'Menu:ConfigManagement' => 'Управление конфигурациями',
	'Menu:ConfigManagement+' => 'Управление конфигурациями',
	'Menu:ConfigManagementCI' => 'Конфигурационные единицы',
	'Menu:ConfigManagementCI+' => 'Конфигурационные единицы',
	'Menu:ConfigManagementOverview' => 'Обзор',
	'Menu:ConfigManagementOverview+' => 'Обзор',
	'Menu:Contact' => 'Контакты',
	'Menu:Contact+' => 'Контакты',
	'Menu:Contact:Count' => '%1$d Контактов',
	'Menu:Person' => 'Персоны',
	'Menu:Person+' => 'Все персоны',
	'Menu:Team' => 'Команды',
	'Menu:Team+' => 'Все команды',
	'Menu:Document' => 'Документы',
	'Menu:Document+' => 'Все документы',
	'Menu:Location' => 'Расположения',
	'Menu:Location+' => 'Все расположения',
	'Menu:NewContact' => 'Новый контакт',
	'Menu:NewContact+' => 'Новый контакт',
	'Menu:SearchContacts' => 'Поиск контактов',
	'Menu:SearchContacts+' => 'Поиск контактов',
	'Menu:ConfigManagement:Shortcuts' => 'Ярлыки',
	'Menu:ConfigManagement:AllContacts' => 'Все контакты: %1$d',
	'Menu:Typology' => 'Типология',
	'Menu:Typology+' => 'Типология',
	'UI_WelcomeMenu_AllConfigItems' => 'Все конфигурационные единицы',
	'Menu:ConfigManagement:Typology' => 'Настройка типологии',
));

// Add translation for Fieldsets

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Person:info' => 'Основная информация',
	'UserLocal:info' => 'Основная информация',
	'Person:personal_info' => 'Личная информация',
	'Person:notifiy' => 'Уведомления',
));

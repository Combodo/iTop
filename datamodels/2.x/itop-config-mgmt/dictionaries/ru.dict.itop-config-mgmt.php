<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
//////////////////////////////////////////////////////////////////////
// Relations for iTop version >= 2.2.0
//////////////////////////////////////////////////////////////////////
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Relation:impacts/Description' => 'Элементы, на которые влияет',
	'Relation:impacts/DownStream' => 'Влияет на...',
	'Relation:impacts/DownStream+' => 'Элементы, на которые влияет',
	'Relation:impacts/UpStream' => 'Зависит от...',
	'Relation:impacts/UpStream+' => 'Элементы, от которых зависит',
	// Legacy entries
	'Relation:depends on/Description' => 'Элементы, от которых зависит',
	'Relation:depends on/DownStream' => 'Зависит от...',
	'Relation:depends on/UpStream' => 'Влияет на...',
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
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

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
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

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
	'Class:Document/Attribute:contracts_list' => 'Договоры',
	'Class:Document/Attribute:contracts_list+' => 'Связанные договоры',
	'Class:Document/Attribute:services_list' => 'Услуги',
	'Class:Document/Attribute:services_list+' => 'Связанные услуги',
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
// Class: FunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FunctionalCI' => 'Функциональные КЕ',
	'Class:FunctionalCI+' => 'Функциональные КЕ',
	'Class:FunctionalCI/Attribute:name' => 'Название',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Описание',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Организация',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Название организации',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Название организации-владельца',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Критичность',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Высокая',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'Высокий',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Низкая',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'Низкий',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Средняя',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'Средний',
	'Class:FunctionalCI/Attribute:move2production' => 'Дата ввода в эксплуатацию',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Контакты',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Связанные контакты',
	'Class:FunctionalCI/Attribute:documents_list' => 'Документы',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Связанные документы',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Прикладные решения',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Связанные прикладные решения',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Договоры',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Связанные договоры',
	'Class:FunctionalCI/Attribute:services_list' => 'Услуги',
	'Class:FunctionalCI/Attribute:services_list+' => 'Связанные услуги',
	'Class:FunctionalCI/Attribute:softwares_list' => 'ПО',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Связанное программное обеспечение',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Тикеты',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Связанные тикеты',
	'Class:FunctionalCI/Attribute:finalclass' => 'Тип',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Активные тикеты',
));

//
// Class: PhysicalDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PhysicalDevice' => 'Физические устройства',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Серийный номер',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Расположение',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Расположение',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Статус',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Эксплуатация',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Резерв',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'Резерв',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Бренд',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Бренд',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Модель',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Модель',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Номер актива',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Дата приобретения',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Окончание гарантии',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Rack' => 'Стойка',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Высота (U)',
	'Class:Rack/Attribute:nb_u+' => 'Количество юнитов',
	'Class:Rack/Attribute:device_list' => 'Устройства',
	'Class:Rack/Attribute:device_list+' => 'Устройства в стойке',
	'Class:Rack/Attribute:enclosure_list' => 'Крейты',
	'Class:Rack/Attribute:enclosure_list+' => 'Крейты в стойке',
));

//
// Class: TelephonyCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TelephonyCI' => 'Телефония',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Абонентский номер',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Phone' => 'Телефон',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:MobilePhone' => 'Мобильный телефон',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Аппаратный PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:IPPhone' => 'IP-телефон',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Tablet' => 'Планшет',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ConnectableCI' => 'Подключаемые КЕ',
	'Class:ConnectableCI+' => 'Подключаемые КЕ',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Сетевые устройства',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Связанные сетевые устройства',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Сетевые интерфейсы',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Сетевые интерфейсы',
));

//
// Class: DatacenterDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DatacenterDevice' => 'Устройства дата-центра',
	'Class:DatacenterDevice+' => 'Устройства дата-центра',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Стойка',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Стойка',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Крейт',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => 'Крейт (шасси)',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Крейт',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Высота (U)',
	'Class:DatacenterDevice/Attribute:nb_u+' => 'Количество занимаемых юнитов',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP-адрес управления',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Источник питания А',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Источник питания А',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Источник питания Б',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Источник питания Б',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Оптические интерфейсы',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Оптические интерфейсы (Fiber Channel)',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN устройства',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Устройства сети хранения данных (Storage Area Network)',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Резервирование',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Устройство в работе, если по крайней мере один источник питания (А или Б) в работе',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Устройство в работе, если все источники питания в работе',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Устройство в работе, если по крайней мере %1$s %% источников питания в работе',
));

//
// Class: NetworkDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkDevice' => 'Сетевое устройство',
	'Class:NetworkDevice+' => 'Сетевое устройство',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Тип устройства',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Тип устройства',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Устройства',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Подключенные устройства',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Версия IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Версия IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'ОЗУ',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Server' => 'Сервер',
	'Class:Server+' => 'Сервер',
	'Class:Server/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:Server/Attribute:osfamily_id+' => 'Семейство операционной системы',
	'Class:Server/Attribute:osfamily_name' => 'Семейство ОС',
	'Class:Server/Attribute:osfamily_name+' => 'Семейство операционной системы',
	'Class:Server/Attribute:osversion_id' => 'Версия ОС',
	'Class:Server/Attribute:osversion_id+' => 'Версия операционной системы',
	'Class:Server/Attribute:osversion_name' => 'Версия ОС',
	'Class:Server/Attribute:osversion_name+' => 'Версия операционной системы',
	'Class:Server/Attribute:oslicence_id' => 'Лицензия ОС',
	'Class:Server/Attribute:oslicence_id+' => 'Лицензия операционной системы',
	'Class:Server/Attribute:oslicence_name' => 'Лицензия ОС',
	'Class:Server/Attribute:oslicence_name+' => 'Лицензия операционной системы',
	'Class:Server/Attribute:cpu' => 'Процессор',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'ОЗУ',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Логические тома',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Подключенные логические тома',
));

//
// Class: StorageSystem
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:StorageSystem' => 'Система хранения',
	'Class:StorageSystem+' => 'Система хранения',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Логические тома',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Логические тома',
));

//
// Class: SANSwitch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SANSwitch' => 'SAN коммутатор',
	'Class:SANSwitch+' => 'SAN коммутатор',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Устройства',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Подключенные устройства',
));

//
// Class: TapeLibrary
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TapeLibrary' => 'Ленточная библиотека',
	'Class:TapeLibrary+' => 'Ленточная библиотека',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Ленты',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Ленты',
));

//
// Class: NAS
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NAS' => 'Сетевое хранилище',
	'Class:NAS+' => 'Сетевое хранилище',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Файловые системы',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Файловые системы',
));

//
// Class: PC
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PC' => 'Персональный компьютер',
	'Class:PC+' => 'Персональный компьютер',
	'Class:PC/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:PC/Attribute:osfamily_id+' => 'Семейство операционной системы',
	'Class:PC/Attribute:osfamily_name' => 'Семейство ОС',
	'Class:PC/Attribute:osfamily_name+' => 'Семейство операционной системы',
	'Class:PC/Attribute:osversion_id' => 'Версия ОС',
	'Class:PC/Attribute:osversion_id+' => 'Версия операционной системы',
	'Class:PC/Attribute:osversion_name' => 'Версия ОС',
	'Class:PC/Attribute:osversion_name+' => 'Версия операционной системы',
	'Class:PC/Attribute:cpu' => 'Процессор',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'ОЗУ',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Тип',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Настольный',
	'Class:PC/Attribute:type/Value:desktop+' => 'Настольный',
	'Class:PC/Attribute:type/Value:laptop' => 'Ноутбук',
	'Class:PC/Attribute:type/Value:laptop+' => 'Ноутбук',
));

//
// Class: Printer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Printer' => 'Принтер',
	'Class:Printer+' => 'Принтер',
));

//
// Class: PowerConnection
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PowerConnection' => 'Электропитание',
	'Class:PowerConnection+' => 'Подключения электропитания',
));

//
// Class: PowerSource
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PowerSource' => 'Источник электропитания',
	'Class:PowerSource+' => 'Источник электропитания',
	'Class:PowerSource/Attribute:pdus_list' => 'Распределители',
	'Class:PowerSource/Attribute:pdus_list+' => 'Распределители электропитания (PDU)',
));

//
// Class: PDU
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PDU' => 'Распределитель ЭП',
	'Class:PDU+' => 'Распределитель электропитания',
	'Class:PDU/Attribute:rack_id' => 'Стойка',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Стойка',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Источник электропитания',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Источник электропитания',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Peripheral' => 'Периферийное устройство',
	'Class:Peripheral+' => 'Периферийное устройство',
));

//
// Class: Enclosure
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Enclosure' => 'Крейт',
	'Class:Enclosure+' => 'Крейт, шасси и т.п.',
	'Class:Enclosure/Attribute:rack_id' => 'Стойка',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Стойка',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Высота (U)',
	'Class:Enclosure/Attribute:nb_u+' => 'Количество юнитов',
	'Class:Enclosure/Attribute:device_list' => 'Устройства',
	'Class:Enclosure/Attribute:device_list+' => 'Устройства в крейте',
));

//
// Class: ApplicationSolution
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ApplicationSolution' => 'Прикладное решение',
	'Class:ApplicationSolution+' => 'Прикладное решение',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'КЕ',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Конфигурационные единицы в составе прикладного решения',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Бизнес-процессы',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Бизнес-процессы, зависящие от прикладного решения',
	'Class:ApplicationSolution/Attribute:status' => 'Статус',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Активный',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Активный',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Анализ влияния: конфигурация резервирования',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Прикладное решение в работе, если все КЕ в работе',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Прикладное решение в работе, если по крайней мере %1$s КЕ в работе',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Прикладное решение в работе, если по крайней мере %1$s %% КЕ в работе',
));

//
// Class: BusinessProcess
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:BusinessProcess' => 'Бизнес-процесс',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Прикладные решения',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Прикладные решения, влияющие на бизнес-процесс',
	'Class:BusinessProcess/Attribute:status' => 'Статус',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Активный',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Активный',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Неактивный',
));

//
// Class: SoftwareInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SoftwareInstance' => 'Экземпляр ПО',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Система',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Система',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'ПО',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'ПО',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Лицензия ПО',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Лицензия ПО',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Путь',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Статус',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Активный',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Активный',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Неактивный',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Неактивный',
));

//
// Class: Middleware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Middleware' => 'Промежуточное ПО',
	'Class:Middleware+' => 'Промежуточное программое обеспечение',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Экземпляры промежуточного ПО',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Экземпляры этого промежуточного ПО',
));

//
// Class: DBServer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DBServer' => 'Сервер БД',
	'Class:DBServer+' => 'Сервер баз данных',
	'Class:DBServer/Attribute:dbschema_list' => 'Схемы БД',
	'Class:DBServer/Attribute:dbschema_list+' => 'Все схемы БД данного сервера',
));

//
// Class: WebServer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WebServer' => 'Веб-сервер',
	'Class:WebServer+' => 'Сервер веб-приложений',
	'Class:WebServer/Attribute:webapp_list' => 'Веб-приложения',
	'Class:WebServer/Attribute:webapp_list+' => 'Все веб-приложения на этом сервере',
));

//
// Class: PCSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PCSoftware' => 'ПО для ПК',
	'Class:PCSoftware+' => 'Программое обеспечение для ПК',
));

//
// Class: OtherSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OtherSoftware' => 'Другое ПО',
	'Class:OtherSoftware+' => 'Другое программное обеспечение',
));

//
// Class: MiddlewareInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:MiddlewareInstance' => 'Экземпляр промежуточного ПО',
	'Class:MiddlewareInstance+' => 'Экземпляр промежуточного ПО',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Промежуточное ПО',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Промежуточное ПО',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DatabaseSchema' => 'Схема базы данных',
	'Class:DatabaseSchema+' => 'Схема базы данных',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Сервер БД',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Сервер БД',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WebApplication' => 'Веб-приложение',
	'Class:WebApplication+' => 'Веб-приложение',
	'Class:WebApplication/Attribute:webserver_id' => 'Веб-сервер',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Веб-сервер',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualDevice' => 'Виртуальное устройство',
	'Class:VirtualDevice+' => 'Виртуальное устройство',
	'Class:VirtualDevice/Attribute:status' => 'Статус',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Эксплуатация',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Резерв',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Резерв',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Логические тома',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Логические тома, используемые этим устройством',
));

//
// Class: VirtualHost
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualHost' => 'Виртуальный хост',
	'Class:VirtualHost+' => 'Виртуальный хост',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Виртуальные машины',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Все виртуальные машины, размещенные на этом хосте',
));

//
// Class: Hypervisor
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Hypervisor' => 'Гипервизор',
	'Class:Hypervisor+' => 'Гипервизор',
	'Class:Hypervisor/Attribute:farm_id' => 'Ферма',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Ферма',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Сервер',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Сервер',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Farm' => 'Ферма',
	'Class:Farm+' => 'Ферма',
	'Class:Farm/Attribute:hypervisor_list' => 'Гипервизоры',
	'Class:Farm/Attribute:hypervisor_list+' => 'Гипервизоры в составе этой фермы',
	'Class:Farm/Attribute:redundancy' => 'Высокая доступность',
	'Class:Farm/Attribute:redundancy/disabled' => 'Ферма в работе, если все гипервизоры в работе',
	'Class:Farm/Attribute:redundancy/count' => 'Ферма в работе, если по крайней мере %1$s гипервизор(-ов) в работе',
	'Class:Farm/Attribute:redundancy/percent' => 'Ферма в работе, если по крайней мере %1$s %% гипервизоров в работе',
));

//
// Class: VirtualMachine
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualMachine' => 'Виртуальная машина',
	'Class:VirtualMachine+' => 'Виртуальная машина',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Виртуальный хост',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Виртуальный хост',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:VirtualMachine/Attribute:osfamily_id+' => 'Семейство операционной системы',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Семейство ОС',
	'Class:VirtualMachine/Attribute:osfamily_name+' => 'Семейство операционной системы',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Версия ОС',
	'Class:VirtualMachine/Attribute:osversion_id+' => 'Версия операционной системы',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Версия ОС',
	'Class:VirtualMachine/Attribute:osversion_name+' => 'Версия операционной системы',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Лицензия ОС',
	'Class:VirtualMachine/Attribute:oslicence_id+' => 'Лицензия операционной системы',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Лицензия ОС',
	'Class:VirtualMachine/Attribute:oslicence_name+' => 'Лицензия операционной системы',
	'Class:VirtualMachine/Attribute:cpu' => 'Процессор',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'ОЗУ',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP-адрес',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Сетевые интерфейсы',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Сетевые интерфейсы',
));

//
// Class: LogicalVolume
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:LogicalVolume' => 'Логический том',
	'Class:LogicalVolume+' => 'Логический том',
	'Class:LogicalVolume/Attribute:name' => 'Название',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Описание',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Уровень RAID',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Размер',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Система хранения',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Система хранения',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Серверы',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Серверы, использующие этот том',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Виртуальные устройства',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Виртуальные устройства, использующие этот том',
));

//
// Class: lnkServerToVolume
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkServerToVolume' => 'Связь Сервер/Том',
	'Class:lnkServerToVolume+' => 'Связь Сервер/Том',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Том',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Сервер',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Сервер',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Используемый размер',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkVirtualDeviceToVolume' => 'Связь Виртуальное устройство/Том',
	'Class:lnkVirtualDeviceToVolume+' => 'Связь Виртуальное устройство/Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Виртуальное устройство',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Виртуальное устройство',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Используемый размер',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSanToDatacenterDevice' => 'Связь SAN коммутатор/Устройство дата-центра',
	'Class:lnkSanToDatacenterDevice+' => 'Связь SAN коммутатор/Устройство дата-центра',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN коммутатор',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN коммутатор',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Устройство',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Устройство',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC порт SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'FC порт подкл. устр.',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Tape' => 'Лента',
	'Class:Tape+' => 'Лента',
	'Class:Tape/Attribute:name' => 'Название',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Описание',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Размер',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Ленточная библиотека',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Ленточная библиотека',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NASFileSystem' => 'Файловая система NAS',
	'Class:NASFileSystem+' => 'Файловая система NAS',
	'Class:NASFileSystem/Attribute:name' => 'Название',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Описание',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Уровень RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Размер',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'Сетевое хранилище',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Сетевое хранилище',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Software' => 'Программное обеспечение',
	'Class:Software+' => 'Программное обеспечение',
	'Class:Software/Attribute:name' => 'Название',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Вендор',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Версия',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Документы',
	'Class:Software/Attribute:documents_list+' => 'Все документы, связанные с этим ПО',
	'Class:Software/Attribute:type' => 'Тип',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Сервер БД',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Сервер БД',
	'Class:Software/Attribute:type/Value:Middleware' => 'Промежуточное ПО',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Промежуточное ПО',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Другое ПО',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Другое ПО',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'ПО для ПК',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'ПО для ПК',
	'Class:Software/Attribute:type/Value:WebServer' => 'Веб-сервер',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Веб-сервер',
	'Class:Software/Attribute:softwareinstance_list' => 'Экземпляры ПО',
	'Class:Software/Attribute:softwareinstance_list+' => 'Экземпляры ПО',
	'Class:Software/Attribute:softwarepatch_list' => 'Патчи ПО',
	'Class:Software/Attribute:softwarepatch_list+' => 'Патчи для этого ПО',
	'Class:Software/Attribute:softwarelicence_list' => 'Лицензии ПО',
	'Class:Software/Attribute:softwarelicence_list+' => 'Лицензии для этого ПО',
));

//
// Class: Patch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Patch' => 'Патч',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Название',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Документы',
	'Class:Patch/Attribute:documents_list+' => 'Все документы, связанные с этим патчем',
	'Class:Patch/Attribute:description' => 'Описание',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Тип',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSPatch' => 'Патч ОС',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Устройства',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Все системы, где установлен этот патч',
	'Class:OSPatch/Attribute:osversion_id' => 'Версия ОС',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Версия ОС',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SoftwarePatch' => 'Патч ПО',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'ПО',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'ПО',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Экземпляры ПО',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Экземпляры ПО, где установлен этот патч',
));

//
// Class: Licence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Licence' => 'Лицензия',
	'Class:Licence+' => 'Лицензия',
	'Class:Licence/Attribute:name' => 'Название',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Документы',
	'Class:Licence/Attribute:documents_list+' => 'Все документы, связанные с этой лицензией',
	'Class:Licence/Attribute:org_id' => 'Организация',
	'Class:Licence/Attribute:org_id+' => 'Организация',
	'Class:Licence/Attribute:organization_name' => 'Организация',
	'Class:Licence/Attribute:organization_name+' => 'Организация',
	'Class:Licence/Attribute:usage_limit' => 'Ограничения использования',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Описание',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Дата начала',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Дата окончания',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Ключ',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Бессрочная',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Нет',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'Нет',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Да',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'Да',
	'Class:Licence/Attribute:finalclass' => 'Тип',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSLicence' => 'Лицензия ОС',
	'Class:OSLicence+' => 'Лицензия ОС',
	'Class:OSLicence/Attribute:osversion_id' => 'Версия ОС',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Версия ОС',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Виртуальные машины',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Все виртуальные машины, где используется данная лицензия',
	'Class:OSLicence/Attribute:servers_list' => 'Серверы',
	'Class:OSLicence/Attribute:servers_list+' => 'Все серверы, где используется данная лицензия',
));

//
// Class: SoftwareLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SoftwareLicence' => 'Лицензия ПО',
	'Class:SoftwareLicence+' => 'Лицензия ПО',
	'Class:SoftwareLicence/Attribute:software_id' => 'ПО',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'ПО',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Экземпляры ПО',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Экземпляры ПО, где используется данная лицензия',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToLicence' => 'Связь Документ/Лицензия',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Лицензия',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Лицензия',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
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
// Class: OSVersion
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSVersion' => 'Версия ОС',
	'Class:OSVersion+' => 'Версия ОС',
	'Class:OSVersion/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Семейство ОС',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSFamily' => 'Семейство ОС',
	'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Brand' => 'Бренд',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Устройства',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Все устройства этого бренда',
	'Class:Brand/UniquenessRule:name+' => 'Название должно быть уникальным',
	'Class:Brand/UniquenessRule:name' => 'Этот бренд уже существует',
));

//
// Class: Model
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Model' => 'Модель',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Бренд',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Бренд',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Тип устройства',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Источник электропитания',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Источник электропитания',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Дисковый массив',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Дисковый массив',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Крейт',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Крейт',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP-телефон',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP-телефон',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Мобильный телефон',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Мобильный телефон',
	'Class:Model/Attribute:type/Value:NAS' => 'Сетевое хранилище',
	'Class:Model/Attribute:type/Value:NAS+' => 'Сетевое хранилище',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Сетевое устройство',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Сетевое устройство',
	'Class:Model/Attribute:type/Value:PC' => 'Персональный компьютер',
	'Class:Model/Attribute:type/Value:PC+' => 'Персональный компьютер',
	'Class:Model/Attribute:type/Value:PDU' => 'Устройство распределения электропитания',
	'Class:Model/Attribute:type/Value:PDU+' => 'Устройство распределения электропитания',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Периферийное устройство',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Периферийное устройство',
	'Class:Model/Attribute:type/Value:Printer' => 'Принтер',
	'Class:Model/Attribute:type/Value:Printer+' => 'Принтер',
	'Class:Model/Attribute:type/Value:Rack' => 'Стойка',
	'Class:Model/Attribute:type/Value:Rack+' => 'Стойка',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN коммутатор',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN коммутатор',
	'Class:Model/Attribute:type/Value:Server' => 'Сервер',
	'Class:Model/Attribute:type/Value:Server+' => 'Сервер',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Система хранения',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Система хранения',
	'Class:Model/Attribute:type/Value:Tablet' => 'Планшет',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Планшет',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Ленточная библиотека',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Ленточная библиотека',
	'Class:Model/Attribute:type/Value:Phone' => 'Телефон',
	'Class:Model/Attribute:type/Value:Phone+' => 'Телефон',
	'Class:Model/Attribute:physicaldevices_list' => 'Устройства',
	'Class:Model/Attribute:physicaldevices_list+' => 'Все устройства этой модели',
	'Class:Model/UniquenessRule:name_brand+' => 'Название должно быть уникальным внутри бренда',
	'Class:Model/UniquenessRule:name_brand' => 'эта модель уже существует для этого бренда',
));

//
// Class: NetworkDeviceType
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkDeviceType' => 'Тип сетевого устройства',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Устройства',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Все сетевые устройства этого типа',
));

//
// Class: IOSVersion
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:IOSVersion' => 'Версия IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Бренд',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Бренд',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToPatch' => 'Связь Документ/Патч',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Патч',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Патч',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Связь Экземпляр ПО/Патч ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Патч ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Патч ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Экземпляр ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Экземпляр ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Связь Функциональная КЕ/Патч ОС',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Патч ОС',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Патч ОС',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Функциональная КЕ',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToSoftware' => 'Связь Документ/ПО',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'ПО',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'ПО',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContactToFunctionalCI' => 'Связь Контакт/Функциональная КЕ',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Функциональная КЕ',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Контакт',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToFunctionalCI' => 'Связь Документ/Функциональная КЕ',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Функциональная КЕ',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Subnet' => 'Подсеть',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Описание',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Имя подсети',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Организация',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Организация',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP-адрес',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Маска подсети',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Описание',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Организация',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Организация',
	'Class:VLAN/Attribute:org_name+' => '',
	'Class:VLAN/Attribute:subnets_list' => 'Подсети',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Физические интерфейсы',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => 'Физические интерфейсы',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSubnetToVLAN' => 'Связь Подсеть/VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Подсеть',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP-адрес подсети',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Подсеть',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkInterface' => 'Сетевой интерфейс',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Название',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Тип',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:IPInterface' => 'IP интерфейс',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP-адрес',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC-адрес',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Комментарий',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP-шлюз',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP-маска',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Скорость',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PhysicalInterface' => 'Физический интерфейс',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Устройства',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Устройства',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Связь Физический интерфейс/VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Физический интерфейс',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Физический интерфейс',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Устройство',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Устройство',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:LogicalInterface' => 'Логический интерфейс',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Виртуальная машина',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Виртуальная машина',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FiberChannelInterface' => 'Оптический интерфейс',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Скорость',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Топология',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Устройство',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Устройство',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Связь Подключаемая КЕ/Сетевое устройство',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Сетевое устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Сетевое устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Подключенное устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Подключенное устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Порт сетев. устр.',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Порт подкл. устр.',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Тип подключения',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Связь Прикладное решение/Функциональная КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Функциональная КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Связь Прикладное решение/Бизнес-процесс',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Бизнес-процесс',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Бизнес-процесс',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
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
// Class: Group
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Group' => 'Группа',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Название',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Статус',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:Group/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:Group/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:Group/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:Group/Attribute:status/Value:production+' => 'Эксплуатация',
	'Class:Group/Attribute:org_id' => 'Организация',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Организация',
	'Class:Group/Attribute:owner_name+' => '',
	'Class:Group/Attribute:description' => 'Описание',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Тип',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Родительская группа',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Родительская группа',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'КЕ',
	'Class:Group/Attribute:ci_list+' => 'Связанные конфигурационные единицы',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Родительская группа',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkGroupToCI' => 'Связь Группа/КЕ',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Группа',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Группа',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'КЕ',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'КЕ',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Основание',
	'Class:lnkGroupToCI/Attribute:reason+' => 'Основание, причина и т.п.',
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
	'Menu:Application' => 'Приложения',
	'Menu:Application+' => 'Все приложения',
	'Menu:DBServer' => 'Серверы баз данных',
	'Menu:DBServer+' => 'Серверы баз данных',
	'Menu:ConfigManagement' => 'Управление конфигурациями',
	'Menu:ConfigManagement+' => 'Управление конфигурациями',
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
	'Menu:ConfigManagementCI' => 'Конфигурационные единицы',
	'Menu:ConfigManagementCI+' => 'Конфигурационные единицы',
	'Menu:BusinessProcess' => 'Бизнес-процессы',
	'Menu:BusinessProcess+' => 'Все бизнес-процессы',
	'Menu:ApplicationSolution' => 'Прикладные решения',
	'Menu:ApplicationSolution+' => 'Все прикладные решения',
	'Menu:ConfigManagementSoftware' => 'Управление программным обеспечением',
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
	'Menu:NewCI' => 'Новая КЕ',
	'Menu:NewCI+' => 'Новая КЕ',
	'Menu:SearchCIs' => 'Поиск КЕ',
	'Menu:SearchCIs+' => 'Поиск КЕ',
	'Menu:ConfigManagement:Devices' => 'Устройства',
	'Menu:ConfigManagement:AllDevices' => 'Все устройства',
	'Menu:ConfigManagement:virtualization' => 'Виртуализация',
	'Menu:ConfigManagement:EndUsers' => 'Пользовательские устройства',
	'Menu:ConfigManagement:SWAndApps' => 'Программное обеспечение и приложения',
	'Menu:ConfigManagement:Misc' => 'Разное',
	'Menu:Group' => 'Группы КЕ',
	'Menu:Group+' => 'Группы КЕ',
	'Menu:ConfigManagement:Shortcuts' => 'Ярлыки',
	'Menu:ConfigManagement:AllContacts' => 'Все контакты: %1$d',
	'Menu:Typology' => 'Типология',
	'Menu:Typology+' => 'Типология',
	'Menu:OSVersion' => 'Версия ОС',
	'Menu:OSVersion+' => 'Версия ОС',
	'Menu:Software' => 'Каталог ПО',
	'Menu:Software+' => 'Каталог ПО',
	'UI_WelcomeMenu_AllConfigItems' => 'Все конфигурационные единицы',
	'Menu:ConfigManagement:Typology' => 'Настройка типологии',

));


// Add translation for Fieldsets

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Server:baseinfo' => 'Основное',
	'Server:Date' => 'Даты',
	'Server:moreinfo' => 'Спецификация',
	'Server:otherinfo' => 'Дополнительно',
	'Server:power' => 'Электропитание',
	'Person:info' => 'Основная информация',
	'UserLocal:info' => 'Основная информация',
	'Person:personal_info' => 'Личная информация',
	'Person:notifiy' => 'Уведомления',
	'Class:Subnet/Tab:IPUsage' => 'Использование IP-адресов',
	'Class:Subnet/Tab:IPUsage-explain' => 'Интерфейсы с IP-адресом в диапазоне: <em>%1$s</em> - <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Свободные IP-адреса',
	'Class:Subnet/Tab:FreeIPs-count' => 'Свободных IP-адресов: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Вот выборка из 10 свободных IP-адресов',
	'Class:Document:PreviewTab' => 'Просмотр',
));

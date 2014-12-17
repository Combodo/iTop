<?php

/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 * 
 * @author   Vladimir Kunin <v.b.kunin@gmail.com>
 * @license   http://opensource.org/licenses/AGPL-3.0
 *
 * 
 * Инструкция по установке
 * 
 * Процесс установки заключается в замене имеющихся локализационных файлов полученными и последующем запуске процедуры обновления iTop для перекомпиляции кода.
 * 	1. Скопируйте с заменой два полученных файла из "itop-rus/dictionaries" в "путь/до/вашего/itop/dictionaries".
 * 	2. Скопируйте с заменой полученные файлы "itop-rus/datamodels/2.x/название-модуля/ru.dict.название-модуля.php" в "путь/до/вашего/itop/datamodels/2.x/название-модуля".
 *  3. Перейдите по адресу "http://адрес/вашего/itop/setup", при этом файл "путь/до/вашего/itop/conf/production/config-itop.php" должен быть доступен для записи.
 *  4. На второй странице установщика выберите "Upgrade an existing iTop instance" и следуйте дальнейшим инструкциям установщика.
 *
 * Ответы на вопросы по установке и использованию переводов, а также на любые другие вопросы по iTop всегда можно получить на сайте сообщества iTop по-русски http://community.itop-itsm.ru.
 *
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Relation:impacts/Description' => 'Элементы, на которые влияет',
	'Relation:impacts/VerbUp' => 'Влияние...',
	'Relation:impacts/VerbDown' => 'Элементы, на которые влияет...',
	'Relation:depends on/Description' => 'Элементы, от которых зависит',
	'Relation:depends on/VerbUp' => 'Зависимость...',
	'Relation:depends on/VerbDown' => 'Влияние...',
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
	'Class:Organization/Attribute:name+' => 'Название организации',
	'Class:Organization/Attribute:code' => 'Код',
	'Class:Organization/Attribute:code+' => 'Код в реестре организаций или другой идентификатор',
	'Class:Organization/Attribute:status' => 'Статус',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Активно',
	'Class:Organization/Attribute:status/Value:active+' => 'Активный',
	'Class:Organization/Attribute:status/Value:inactive' => 'Неактивно',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Organization/Attribute:parent_id' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_id+' => 'Вышестоящая организация',
	'Class:Organization/Attribute:parent_name' => 'Название вышестоящей',
	'Class:Organization/Attribute:parent_name+' => 'Название вышестоящей организации',
	'Class:Organization/Attribute:deliverymodel_id' => 'Модель услуг',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Название модели предоставления услуг',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Вышестоящая',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Вышестоящая организация',
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
	'Class:Location/Attribute:status/Value:active' => 'Активно',
	'Class:Location/Attribute:status/Value:active+' => 'Активный',
	'Class:Location/Attribute:status/Value:inactive' => 'Неактивно',
	'Class:Location/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Location/Attribute:org_id' => 'Организация',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Название организации',
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
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Название',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Статус',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Активно',
	'Class:Contact/Attribute:status/Value:active+' => 'Активный',
	'Class:Contact/Attribute:status/Value:inactive' => 'Неактивно',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Неактивный',
	'Class:Contact/Attribute:org_id' => 'Организация',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Название организации',
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
	'Class:Person' => 'Человек',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Фамилия',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Имя',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'ID сотрудника',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Мобильный телефон',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Расположение',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Название расположения',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Руководитель',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Имя руководителя',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Команды',
	'Class:Person/Attribute:team_list+' => 'Команды с участием человека',
	'Class:Person/Attribute:tickets_list' => 'Тикеты',
	'Class:Person/Attribute:tickets_list+' => 'Связанные тикеты',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Имя руководителя',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
));

//
// Class: Team
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Team' => 'Команда',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Участники',
	'Class:Team/Attribute:persons_list+' => 'Участники команды',
	'Class:Team/Attribute:tickets_list' => 'Тикеты',
	'Class:Team/Attribute:tickets_list+' => 'Связанные тикеты',
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
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Название организации',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Тип документа',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Название типа документа',
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
	'Class:Document/Attribute:finalclass' => 'Тип документа',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentFile' => 'Файл',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Файл',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentNote' => 'Заметка',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Заметка',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DocumentWeb' => 'Веб-документ',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FunctionalCI' => 'Функциональные КЕ',
	'Class:FunctionalCI+' => '',
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
	'Class:PhysicalDevice/Attribute:location_name' => 'Название расположения',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Статус',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Производство',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Производство',
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
	'Class:Rack/Attribute:nb_u' => 'Высота, U',
	'Class:Rack/Attribute:nb_u+' => '',
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
	'Class:ConnectableCI+' => 'Физический CI',
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
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Стойка',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Название стойки',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Крейт',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Название крейта',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Высота, U',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP-адрес управления',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Источник питания А',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Название источника питания А',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Источник питания Б',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Название источника питания Б',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Оптические интерфейсы',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Оптические интерфейсы (Fiber Channel)',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN устройства',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Устройства сети хранения данных (Storage Area Network)',
));

//
// Class: NetworkDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkDevice' => 'Сетевое устройство',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Тип устройства',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Имя типа устройства',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Устройства',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Связанные устройства',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Версия IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Имя версии IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'ОЗУ',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Server' => 'Сервер',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Имя семейства ОС',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Версия ОС',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Имя версии ОС',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Лицензия ОС',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Имя лицензии ОС',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'Процессор',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'ОЗУ',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Логические тома',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Логические тома',
));

//
// Class: StorageSystem
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:StorageSystem' => 'Система хранения',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Логические тома',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Логические тома',
));

//
// Class: SANSwitch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SANSwitch' => 'SAN коммутатор',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Устройства',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Связанные устройства',
));

//
// Class: TapeLibrary
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:TapeLibrary' => 'Ленточная библиотека',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Ленты',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Ленты',
));

//
// Class: NAS
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NAS' => 'Сетевое хранилище',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Файловые системы',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Файловые системы',
));

//
// Class: PC
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PC' => 'Персональный компьютер',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Имя семейства ОС',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Версия ОС',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Имя версии ОС',
	'Class:PC/Attribute:osversion_name+' => '',
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
	'Class:Printer+' => '',
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
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'Распределители',
	'Class:PowerSource/Attribute:pdus_list+' => 'Распределители электропитания (PDU)',
));

//
// Class: PDU
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PDU' => 'Распределитель',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Стойка',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Имя стойки',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Источник электропитания',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Имя источника электропитания',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Peripheral' => 'Периферийное устройство',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Enclosure' => 'Крейт',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Стойка',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Название стойки',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Высота, U',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Устройства',
	'Class:Enclosure/Attribute:device_list+' => 'Устройства в крейте',
));

//
// Class: ApplicationSolution
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ApplicationSolution' => 'Прикладное решение',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'КЕ',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Связанные конфигурационные единицы',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Бизнес-процессы',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Связанные бизнес-процессы',
	'Class:ApplicationSolution/Attribute:status' => 'Статус',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Активно',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Активный',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Неактивно',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Неактивный',
));

//
// Class: BusinessProcess
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:BusinessProcess' => 'Бизнес-процесс',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Прикладные решения',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Связанные прикладные решения',
	'Class:BusinessProcess/Attribute:status' => 'Статус',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Активно',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Активный',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Неактивно',
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
	'Class:SoftwareInstance/Attribute:system_name' => 'Имя системы',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'ПО',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Имя ПО',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Лицензия ПО',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Имя лицензии ПО',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Патч',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Статус',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Активно',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Активный',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Неактивно',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Неактивный',
));

//
// Class: Middleware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Middleware' => 'Промежуточное ПО',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Экземпляры промежуточного ПО',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Экземпляры промежуточного ПО',
));

//
// Class: DBServer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DBServer' => 'Сервер БД',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Схемы БД',
	'Class:DBServer/Attribute:dbschema_list+' => 'Все схемы БД для данного сервера',
));

//
// Class: WebServer
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WebServer' => 'Веб-сервер',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Веб-приложения',
	'Class:WebServer/Attribute:webapp_list+' => 'Все веб-приложения, имеющиеся на этом сервере',
));

//
// Class: PCSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:PCSoftware' => 'ПО для ПК',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OtherSoftware' => 'Другое ПО',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:MiddlewareInstance' => 'Экземпляр промежуточного ПО',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Промежуточное ПО',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Имя промежуточного ПО',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DatabaseSchema' => 'Схема базы данных',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Сервер БД',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Название сервера БД',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:WebApplication' => 'Веб-приложение',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Веб-сервер',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Имя веб-сервера',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualDevice' => 'Виртуальное устройство',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Статус',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'устаревшее',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Производство',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'производство',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Резерв',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Резерв',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Логические тома',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Логические тома',
));

//
// Class: VirtualHost
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualHost' => 'Виртуальный хост',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Виртуальные машины',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Виртуальные машины',
));

//
// Class: Hypervisor
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Hypervisor' => 'Гипервизор',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Ферма',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Имя фермы',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Сервер',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Имя сервера',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Farm' => 'Ферма',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Гипервизоры',
	'Class:Farm/Attribute:hypervisor_list+' => 'Гипервизоры',
));

//
// Class: VirtualMachine
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VirtualMachine' => 'Виртуальная машина',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Виртуальный хост',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Имя виртуального хоста',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Имя семейства ОС',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Версия ОС',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Имя версии ОС',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Лицензия ОС',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Имя лицензии ОС',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
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
	'Class:LogicalVolume+' => '',
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
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Имя системы хранения',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Серверы',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Серверы',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Виртуальные устройства',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Виртуальные устройства',
));

//
// Class: lnkServerToVolume
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkServerToVolume' => 'Сервер/Том',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Имя тома',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Сервер',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Имя сервера',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Используемый размер',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkVirtualDeviceToVolume' => 'Виртуальное устройство/Том',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Имя тома',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Виртуальное устройство',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Имя виртуального устройства',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Используемый размер',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSanToDatacenterDevice' => 'SAN коммутатор/Устройство дата-центра',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN коммутатор',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Имя SAN коммутатора',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Устройство',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Имя устройства',
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
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Название',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Описание',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Размер',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Ленточная библиотека',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Имя ленточной библиотеки',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NASFileSystem' => 'Файловая система NAS',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Имя',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Описание',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Уровень RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Размер',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'Сетевое хранилище',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Имя NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Software' => 'Программное обеспечение',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Название',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Вендор',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Версия',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Документы',
	'Class:Software/Attribute:documents_list+' => 'All the documents linked to this software',
	'Class:Software/Attribute:type' => 'Тип',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server',
	'Class:Software/Attribute:type/Value:Middleware' => 'Промежуточное ПО',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Другое ПО',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Other Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'ПО для ПК',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software',
	'Class:Software/Attribute:type/Value:WebServer' => 'Веб-сервер',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Веб-сервер',
	'Class:Software/Attribute:softwareinstance_list' => 'Экземпляры ПО',
	'Class:Software/Attribute:softwareinstance_list+' => 'Экземпляры ПО',
	'Class:Software/Attribute:softwarepatch_list' => 'Патчи ПО',
	'Class:Software/Attribute:softwarepatch_list+' => 'Патчи ПО',
	'Class:Software/Attribute:softwarelicence_list' => 'Лицензии ПО',
	'Class:Software/Attribute:softwarelicence_list+' => 'Лицензии ПО',
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
	'Class:Patch/Attribute:documents_list+' => 'All the documents linked to this patch',
	'Class:Patch/Attribute:description' => 'Описание',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Тип',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSPatch' => 'OS Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Устройства',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'All the systems where this patch is installed',
	'Class:OSPatch/Attribute:osversion_id' => 'Версия ОС',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Имя версии ОС',
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
	'Class:SoftwarePatch/Attribute:software_name' => 'Имя ПО',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Экземпляры ПО',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Экземпляры ПО',
));

//
// Class: Licence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Licence' => 'Лицензия',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Название',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Документы',
	'Class:Licence/Attribute:documents_list+' => 'All the documents linked to this licence',
	'Class:Licence/Attribute:org_id' => 'Организация',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Имя организации',
	'Class:Licence/Attribute:organization_name+' => 'Common name',
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
	'Class:Licence/Attribute:perpetual' => 'Perpetual',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'нет',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'нет',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'да',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'да',
	'Class:Licence/Attribute:finalclass' => 'Тип',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:OSLicence' => 'OS Licence',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Версия ОС',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Имя версии ОС',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Виртуальные машины',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'All the virtual machines where this licence is used',
	'Class:OSLicence/Attribute:servers_list' => 'Серверы',
	'Class:OSLicence/Attribute:servers_list+' => 'All the servers where this licence is used',
));

//
// Class: SoftwareLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SoftwareLicence' => 'Лицензия ПО',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'ПО',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Имя ПО',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Экземпляры ПО',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Экземпляры ПО',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToLicence' => 'Документ/Лицензия',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Лицензия',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Имя лицензии',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Документы',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Имя документа',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Typology' => 'Типология',
	'Class:Typology+' => '',
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
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Семейство ОС',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Имя семейства ОС',
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
	'Class:Brand/Attribute:physicaldevices_list+' => 'Устройства',
));

//
// Class: Model
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Model' => 'Модель',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Бренд',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Имя бренда',
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
	'Class:Model/Attribute:physicaldevices_list+' => 'Устройства',
));

//
// Class: NetworkDeviceType
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:NetworkDeviceType' => 'Тип сетевого устройства',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Устройства',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Сетевые устройства',
));

//
// Class: IOSVersion
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:IOSVersion' => 'Версия IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Бренд',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Имя бренда',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToPatch' => 'Документ/Патч',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Патч',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Имя патча',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Имя документа',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Экземпляр ПО/Патч ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Патч ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Имя патча ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Экземпляр ПО',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Имя экземпляра ПО',
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
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS patch name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Functionalci name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToSoftware' => 'Документ/ПО',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'ПО',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Имя ПО',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Имя документа',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContactToFunctionalCI' => 'Контакт/Функциональная КЕ',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Имя функциональной КЕ',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Имя контакта',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToFunctionalCI' => 'Документ/Функциональная КЕ',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Имя функциональной КЕ',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Имя документа',
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
	'Class:Subnet/Attribute:subnet_name' => 'Subnet name',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Организация',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Имя организации',
	'Class:Subnet/Attribute:org_name+' => 'Common name',
	'Class:Subnet/Attribute:ip' => 'IP-адрес',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Маска подсети',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Description',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organization',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Organization name',
	'Class:VLAN/Attribute:org_name+' => 'Common name',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physical network interfaces',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSubnetToVLAN' => 'Link Subnet / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnet name',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
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
	'Class:PhysicalInterface' => 'Сетевой интерфейс',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Устройства',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Имя устройства',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link PhysicalInterface / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Physical Interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Physical Interface Name',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Device',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Device name',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
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
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Имя виртуальной машины',
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
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Имя устройства',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Подключаемая КЕ/Сетевое устройство',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Сетевое устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Имя сетевого устройства',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Подключенное устройство',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Имя подключенного устройства',
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
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Прикладное решение/Функциональная КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Имя прикладного решения',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Функциональная КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Имя функциональной КЕ',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Прикладное решение/Бизнес-процесс',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Бизнес-процесс',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Имя бизнес-процесса',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Прикладное решение',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Имя прикладного решения',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkPersonToTeam' => 'Человек/Команда',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Команда',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Название команды',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Человек',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Имя',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Роль',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Название роли',
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
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementation',
	'Class:Group/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsolete',
	'Class:Group/Attribute:status/Value:production' => 'Производство',
	'Class:Group/Attribute:status/Value:production+' => 'Production',
	'Class:Group/Attribute:org_id' => 'Организация',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Название владельца',
	'Class:Group/Attribute:owner_name+' => 'Common name',
	'Class:Group/Attribute:description' => 'Описание',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Тип',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Родительская группа',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Название',
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
	'Class:lnkGroupToCI' => 'Группа/КЕ',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Группа',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Название группы',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'КЕ',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Название КЕ',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Основание',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Application Menu
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
'Menu:DataAdministration' => 'Административные данные',
'Menu:DataAdministration+' => 'Административные данные',
'Menu:Catalogs' => 'Каталоги',
'Menu:Catalogs+' => 'Каталоги',
'Menu:Audit' => 'Аудит',
'Menu:Audit+' => 'Аудит',
'Menu:CSVImport' => 'Импорт CSV',
'Menu:CSVImport+' => 'Пакетное создание или обновление',
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
'Menu:Contact:Count' => '%1$d contacts',
'Menu:Person' => 'Люди',
'Menu:Person+' => 'Все люди',
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
'Menu:NewContact' => 'Создать контакт',
'Menu:NewContact+' => 'Создать контакт',
'Menu:SearchContacts' => 'Найти контакт',
'Menu:SearchContacts+' => 'найти контакт',
'Menu:NewCI' => 'Создать КЕ',
'Menu:NewCI+' => 'Создать КЕ',
'Menu:SearchCIs' => 'Найти КЕ',
'Menu:SearchCIs+' => 'Найти КЕ',
'Menu:ConfigManagement:Devices' => 'Устройства',
'Menu:ConfigManagement:AllDevices' => 'Все устройства',
'Menu:ConfigManagement:virtualization' => 'Виртуализация',
'Menu:ConfigManagement:EndUsers' => 'Пользовательские устройства',
'Menu:ConfigManagement:SWAndApps' => 'Программное обеспечение и приложения',
'Menu:ConfigManagement:Misc' => 'Разное',
'Menu:Group' => 'Группы КЕ',
'Menu:Group+' => 'Группы КЕ',
'Menu:ConfigManagement:Shortcuts' => 'Ярлыки',
'Menu:ConfigManagement:AllContacts' => 'Все контакты',
'Menu:Typology' => 'Типология',
'Menu:Typology+' => 'Typology configuration',
'Menu:OSVersion' => 'Версия ОС',
'Menu:OSVersion+' => '',
'Menu:Software' => 'Каталог ПО',
'Menu:Software+' => 'Software catalog',
'UI_WelcomeMenu_AllConfigItems' => 'Все конфигурационные единицы',
'Menu:ConfigManagement:Typology' => 'Настройка типологии',

));


// Add translation for Fieldsets

Dict::Add('RU RU', 'Russian', 'Русский', array(
'Server:baseinfo' => 'Основное',
'Server:Date' => 'Даты',
'Server:moreinfo' => 'Спецификация',
'Server:otherinfo' => 'Дополнительно',
'Person:info' => 'Основное',
'Person:notifiy' => 'Уведомления',
'Class:Subnet/Tab:IPUsage' => 'IP Usage',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'Free IPs',
'Class:Subnet/Tab:FreeIPs-count' => 'Free IPs: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Here is an extract of 10 free IP addresses',
'Class:Document:PreviewTab' => 'Preview',
));
?>

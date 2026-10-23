<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Relation:impacts/Description' => 'Елементи, на які впливає',
	'Relation:impacts/DownStream' => 'Впливає на...',
	'Relation:impacts/DownStream+' => 'Елементи, на які впливає',
	'Relation:impacts/UpStream' => 'Залежить від...',
	'Relation:impacts/UpStream+' => 'Елементи, від яких залежить',
	'Relation:depends on/Description' => 'Елементи, від яких залежить',
	'Relation:depends on/DownStream' => 'Залежить від...',
	'Relation:depends on/UpStream' => 'Впливає на...',
	'Relation:impacts/LoadData' => 'Завантажити дані',
	'Relation:impacts/NoFilteredData' => 'будь ласка, виберіть об\'єкти та завантажте дані',
	'Relation:impacts/FilteredData' => 'Відфільтровані дані',
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
// Class: lnkContactToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContactToFunctionalCI' => 'Зв\'язок Контакт/Функціональна КО',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Функціональна КО',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Функціональна КО',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Контакт',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:FunctionalCI' => 'Функціональні КО',
	'Class:FunctionalCI+' => 'Функціональні конфігураційні одиниці',
	'Class:FunctionalCI/Attribute:name' => 'Назва',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Опис',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Організація',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Назва організації',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Назва організації-власника',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Критичність',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Висока',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'Високий',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Низька',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'Низький',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Середня',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'Середній',
	'Class:FunctionalCI/Attribute:move2production' => 'Дата введення в експлуатацію',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Контакти',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Пов\'язані контакти',
	'Class:FunctionalCI/Attribute:documents_list' => 'Документи',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Пов\'язані документи',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Прикладні рішення',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Пов\'язані прикладні рішення',
	'Class:FunctionalCI/Attribute:softwares_list' => 'ПЗ',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Пов\'язане програмне забезпечення',
	'Class:FunctionalCI/Attribute:finalclass' => 'Тип',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Активні тікети',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Активні тікети, які впливають на цю функціональну КО',
));

//
// Class: PhysicalDevice
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PhysicalDevice' => 'Фізичні пристрої',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Серійний номер',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Розташування',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Розташування',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Статус',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Експлуатація',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Експлуатація',
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
	'Class:PhysicalDevice/Attribute:asset_number' => 'Інвентарний номер',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Дата придбання',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Закінчення гарантії',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Rack' => 'Стійка',
	'Class:Rack+' => '',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s',
	'Class:Rack/Attribute:nb_u' => 'Висота (U)',
	'Class:Rack/Attribute:nb_u+' => 'Кількість юнітів',
	'Class:Rack/Attribute:device_list' => 'Пристрої',
	'Class:Rack/Attribute:device_list+' => 'Пристрої у стійці',
	'Class:Rack/Attribute:enclosure_list' => 'Корпуси',
	'Class:Rack/Attribute:enclosure_list+' => 'Корпуси у стійці',
));

//
// Class: TelephonyCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TelephonyCI' => 'Телефонія',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Абонентський номер',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Phone' => 'Телефон',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:MobilePhone' => 'Мобільний телефон',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Апаратний PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:IPPhone' => 'IP-телефон',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Tablet' => 'Планшет',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ConnectableCI' => 'Підключувані КО',
	'Class:ConnectableCI+' => 'Підключувані конфігураційні одиниці',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Мережеві пристрої',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Пов\'язані мережеві пристрої',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Мережеві інтерфейси',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Мережеві інтерфейси',
));

//
// Class: DatacenterDevice
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DatacenterDevice' => 'Пристрої дата-центру',
	'Class:DatacenterDevice+' => 'Пристрої дата-центру',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Стійка',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Стійка',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Корпус',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => 'Корпус (шасі)',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Корпус',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Висота (U)',
	'Class:DatacenterDevice/Attribute:nb_u+' => 'Кількість юнітів, що займаються',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP-адреса управління',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Джерело живлення А',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Джерело живлення А',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Джерело живлення Б',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Джерело живлення Б',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Оптичні інтерфейси',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Оптичні інтерфейси (Fiber Channel)',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN пристрої',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Пристрої мережі зберігання даних (Storage Area Network)',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Резервування',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Пристрій у роботі, якщо принаймні одне джерело живлення (А або Б) у роботі',
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Пристрій у роботі, якщо всі джерела живлення у роботі',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Пристрій у роботі, якщо принаймні %1$s %% джерел живлення у роботі',
));

//
// Class: NetworkDevice
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:NetworkDevice' => 'Мережевий пристрій',
	'Class:NetworkDevice+' => 'Мережевий пристрій',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Тип пристрою',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Тип пристрою',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Пристрої',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Підключені пристрої',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Версія IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Версія IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'ОЗП',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Server' => 'Сервер',
	'Class:Server+' => 'Сервер',
	'Class:Server/ComplementaryName' => '%1$s - %2$s',
	'Class:Server/Attribute:osfamily_id' => 'Сімейство ОС',
	'Class:Server/Attribute:osfamily_id+' => 'Сімейство операційної системи',
	'Class:Server/Attribute:osfamily_name' => 'Сімейство ОС',
	'Class:Server/Attribute:osfamily_name+' => 'Сімейство операційної системи',
	'Class:Server/Attribute:osversion_id' => 'Версія ОС',
	'Class:Server/Attribute:osversion_id+' => 'Версія операційної системи',
	'Class:Server/Attribute:osversion_name' => 'Версія ОС',
	'Class:Server/Attribute:osversion_name+' => 'Версія операційної системи',
	'Class:Server/Attribute:oslicence_id' => 'Ліцензія ОС',
	'Class:Server/Attribute:oslicence_id+' => 'Ліцензія операційної системи',
	'Class:Server/Attribute:oslicence_name' => 'Ліцензія ОС',
	'Class:Server/Attribute:oslicence_name+' => 'Ліцензія операційної системи',
	'Class:Server/Attribute:cpu' => 'Процесор',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'ОЗП',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Логічні томи',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Підключені логічні томи',
));

//
// Class: StorageSystem
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:StorageSystem' => 'Система зберігання',
	'Class:StorageSystem+' => 'Система зберігання',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Логічні томи',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Логічні томи',
));

//
// Class: SANSwitch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SANSwitch' => 'SAN комутатор',
	'Class:SANSwitch+' => 'SAN комутатор',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Пристрої',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Підключені пристрої',
));

//
// Class: TapeLibrary
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:TapeLibrary' => 'Стрічкова бібліотека',
	'Class:TapeLibrary+' => 'Стрічкова бібліотека',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Стрічки',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Стрічки',
));

//
// Class: NAS
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:NAS' => 'Мережеве сховище',
	'Class:NAS+' => 'Мережеве сховище',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Файлові системи',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Файлові системи',
));

//
// Class: PC
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PC' => 'Персональний комп\'ютер',
	'Class:PC+' => 'Персональний комп\'ютер',
	'Class:PC/ComplementaryName' => '%1$s - %2$s',
	'Class:PC/Attribute:osfamily_id' => 'Сімейство ОС',
	'Class:PC/Attribute:osfamily_id+' => 'Сімейство операційної системи',
	'Class:PC/Attribute:osfamily_name' => 'Сімейство ОС',
	'Class:PC/Attribute:osfamily_name+' => 'Сімейство операційної системи',
	'Class:PC/Attribute:osversion_id' => 'Версія ОС',
	'Class:PC/Attribute:osversion_id+' => 'Версія операційної системи',
	'Class:PC/Attribute:osversion_name' => 'Версія ОС',
	'Class:PC/Attribute:osversion_name+' => 'Версія операційної системи',
	'Class:PC/Attribute:cpu' => 'Процесор',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'ОЗП',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Тип',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Настільний',
	'Class:PC/Attribute:type/Value:desktop+' => 'Настільний',
	'Class:PC/Attribute:type/Value:laptop' => 'Ноутбук',
	'Class:PC/Attribute:type/Value:laptop+' => 'Ноутбук',
));

//
// Class: Printer
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Printer' => 'Принтер',
	'Class:Printer+' => 'Принтер',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: PowerConnection
//
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PowerConnection' => 'Електроживлення',
	'Class:PowerConnection+' => 'Підключення електроживлення',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: PowerSource
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PowerSource' => 'Джерело електроживлення',
	'Class:PowerSource+' => 'Джерело електроживлення',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s',
	'Class:PowerSource/Attribute:pdus_list' => 'Розподільники',
	'Class:PowerSource/Attribute:pdus_list+' => 'Розподільники електроживлення (PDU)',
));

//
// Class: PDU
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PDU' => 'Розподільник ЕЖ',
	'Class:PDU+' => 'Розподільник електроживлення',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s',
	'Class:PDU/Attribute:rack_id' => 'Стійка',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Стійка',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Джерело електроживлення',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Джерело електроживлення',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Peripheral' => 'Периферійний пристрій',
	'Class:Peripheral+' => 'Периферійний пристрій',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: Enclosure
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Enclosure' => 'Корпус',
	'Class:Enclosure+' => 'Корпус, шасі тощо',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s',
	'Class:Enclosure/Attribute:rack_id' => 'Стійка',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Стійка',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Висота (U)',
	'Class:Enclosure/Attribute:nb_u+' => 'Кількість юнітів',
	'Class:Enclosure/Attribute:device_list' => 'Пристрої',
	'Class:Enclosure/Attribute:device_list+' => 'Пристрої в корпусі',
));

//
// Class: ApplicationSolution
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ApplicationSolution' => 'Прикладне рішення',
	'Class:ApplicationSolution+' => 'Прикладне рішення',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'КО',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Конфігураційні одиниці у складі прикладного рішення',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Бізнес-процеси',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Бізнес-процеси, що залежать від прикладного рішення',
	'Class:ApplicationSolution/Attribute:status' => 'Статус',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Активний',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Активний',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Неактивний',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Аналіз впливу: конфігурація резервування',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Прикладне рішення у роботі, якщо всі КО у роботі',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Прикладне рішення у роботі, якщо принаймні %1$s КО у роботі',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Прикладне рішення у роботі, якщо принаймні %1$s %% КО у роботі',
));

//
// Class: BusinessProcess
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:BusinessProcess' => 'Бізнес-процес',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Прикладні рішення',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Прикладні рішення, що впливають на бізнес-процес',
	'Class:BusinessProcess/Attribute:status' => 'Статус',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Активний',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Активний',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Неактивний',
));

//
// Class: SoftwareInstance
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SoftwareInstance' => 'Екземпляр ПЗ',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Система',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Система',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'ПЗ',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'ПЗ',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Ліцензія ПЗ',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Ліцензія ПЗ',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Шлях',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Статус',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Активний',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Активний',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Неактивний',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Неактивний',
));

//
// Class: Middleware
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Middleware' => 'Проміжне ПЗ',
	'Class:Middleware+' => 'Проміжне програмне забезпечення',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Екземпляри проміжного ПЗ',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Екземпляри цього проміжного ПЗ',
));

//
// Class: DBServer
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DBServer' => 'Сервер БД',
	'Class:DBServer+' => 'Сервер баз даних',
	'Class:DBServer/Attribute:dbschema_list' => 'Схеми БД',
	'Class:DBServer/Attribute:dbschema_list+' => 'Всі схеми БД даного сервера',
));

//
// Class: WebServer
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:WebServer' => 'Веб-сервер',
	'Class:WebServer+' => 'Сервер веб-застосунків',
	'Class:WebServer/Attribute:webapp_list' => 'Веб-застосунки',
	'Class:WebServer/Attribute:webapp_list+' => 'Всі веб-застосунки на цьому сервері',
));

//
// Class: PCSoftware
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PCSoftware' => 'ПЗ для ПК',
	'Class:PCSoftware+' => 'Програмне забезпечення для ПК',
));

//
// Class: OtherSoftware
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OtherSoftware' => 'Інше ПЗ',
	'Class:OtherSoftware+' => 'Інше програмне забезпечення',
));

//
// Class: MiddlewareInstance
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:MiddlewareInstance' => 'Екземпляр проміжного ПЗ',
	'Class:MiddlewareInstance+' => 'Екземпляр проміжного ПЗ',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Проміжне ПЗ',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Проміжне ПЗ',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DatabaseSchema' => 'Схема бази даних',
	'Class:DatabaseSchema+' => 'Схема бази даних',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Сервер БД',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Сервер БД',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:WebApplication' => 'Веб-застосунок',
	'Class:WebApplication+' => 'Веб-застосунок',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:VirtualDevice' => 'Віртуальний пристрій',
	'Class:VirtualDevice+' => 'Віртуальний пристрій',
	'Class:VirtualDevice/Attribute:status' => 'Статус',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Експлуатація',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Експлуатація',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Резерв',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Резерв',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Логічні томи',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Логічні томи, що використовуються цим пристроєм',
));

//
// Class: VirtualHost
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:VirtualHost' => 'Віртуальний хост',
	'Class:VirtualHost+' => 'Віртуальний хост',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Віртуальні машини',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Всі віртуальні машини, розміщені на цьому хості',
));

//
// Class: Hypervisor
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Hypervisor' => 'Гіпервізор',
	'Class:Hypervisor+' => 'Гіпервізор',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Farm' => 'Ферма',
	'Class:Farm+' => 'Ферма',
	'Class:Farm/Attribute:hypervisor_list' => 'Гіпервізори',
	'Class:Farm/Attribute:hypervisor_list+' => 'Гіпервізори у складі цієї ферми',
	'Class:Farm/Attribute:redundancy' => 'Висока доступність',
	'Class:Farm/Attribute:redundancy/disabled' => 'Ферма у роботі, якщо всі гіпервізори у роботі',
	'Class:Farm/Attribute:redundancy/count' => 'Ферма у роботі, якщо принаймні %1$s гіпервізор(-ів) у роботі',
	'Class:Farm/Attribute:redundancy/percent' => 'Ферма у роботі, якщо принаймні %1$s %% гіпервізорів у роботі',
));

//
// Class: VirtualMachine
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:VirtualMachine' => 'Віртуальна машина',
	'Class:VirtualMachine+' => 'Віртуальна машина',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Віртуальний хост',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Віртуальний хост',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Сімейство ОС',
	'Class:VirtualMachine/Attribute:osfamily_id+' => 'Сімейство операційної системи',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Сімейство ОС',
	'Class:VirtualMachine/Attribute:osfamily_name+' => 'Сімейство операційної системи',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Версія ОС',
	'Class:VirtualMachine/Attribute:osversion_id+' => 'Версія операційної системи',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Версія ОС',
	'Class:VirtualMachine/Attribute:osversion_name+' => 'Версія операційної системи',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Ліцензія ОС',
	'Class:VirtualMachine/Attribute:oslicence_id+' => 'Ліцензія операційної системи',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Ліцензія ОС',
	'Class:VirtualMachine/Attribute:oslicence_name+' => 'Ліцензія операційної системи',
	'Class:VirtualMachine/Attribute:cpu' => 'Процесор',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'ОЗП',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP-адреса',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Мережеві інтерфейси',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Мережеві інтерфейси',
));

//
// Class: LogicalVolume
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:LogicalVolume' => 'Логічний том',
	'Class:LogicalVolume+' => 'Логічний том',
	'Class:LogicalVolume/Attribute:name' => 'Назва',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Опис',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Рівень RAID',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Розмір',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Система зберігання',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Система зберігання',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Сервери',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Сервери, що використовують цей том',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Віртуальні пристрої',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Віртуальні пристрої, що використовують цей том',
));

//
// Class: lnkServerToVolume
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkServerToVolume' => 'Зв\'язок Сервер/Том',
	'Class:lnkServerToVolume+' => 'Зв\'язок Сервер/Том',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Том',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Сервер',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Сервер',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Використовуваний розмір',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkVirtualDeviceToVolume' => 'Зв\'язок Віртуальний пристрій/Том',
	'Class:lnkVirtualDeviceToVolume+' => 'Зв\'язок Віртуальний пристрій/Том',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Том',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Віртуальний пристрій',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Віртуальний пристрій',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Використовуваний розмір',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkSanToDatacenterDevice' => 'Зв\'язок SAN комутатор/Пристрій дата-центру',
	'Class:lnkSanToDatacenterDevice+' => 'Зв\'язок SAN комутатор/Пристрій дата-центру',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN комутатор',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN комутатор',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Пристрій',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Пристрій',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC порт SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'FC порт підкл. пристр.',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Tape' => 'Стрічка',
	'Class:Tape+' => 'Стрічка',
	'Class:Tape/Attribute:name' => 'Назва',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Опис',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Розмір',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Стрічкова бібліотека',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Стрічкова бібліотека',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:NASFileSystem' => 'Файлова система NAS',
	'Class:NASFileSystem+' => 'Файлова система NAS',
	'Class:NASFileSystem/Attribute:name' => 'Назва',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Опис',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Рівень RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Розмір',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'Мережеве сховище',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Мережеве сховище',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Software' => 'Програмне забезпечення',
	'Class:Software+' => 'Програмне забезпечення',
	'Class:Software/ComplementaryName' => '%1$s - %2$s',
	'Class:Software/Attribute:name' => 'Назва',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Постачальник',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Версія',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Документи',
	'Class:Software/Attribute:documents_list+' => 'Всі документи, пов\'язані з цим ПЗ',
	'Class:Software/Attribute:type' => 'Тип',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Сервер БД',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Сервер БД',
	'Class:Software/Attribute:type/Value:Middleware' => 'Проміжне ПЗ',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Проміжне ПЗ',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Інше ПЗ',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Інше ПЗ',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'ПЗ для ПК',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'ПЗ для ПК',
	'Class:Software/Attribute:type/Value:WebServer' => 'Веб-сервер',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Веб-сервер',
	'Class:Software/Attribute:softwareinstance_list' => 'Екземпляри ПЗ',
	'Class:Software/Attribute:softwareinstance_list+' => 'Екземпляри ПЗ',
	'Class:Software/Attribute:softwarepatch_list' => 'Патчі ПЗ',
	'Class:Software/Attribute:softwarepatch_list+' => 'Патчі для цього ПЗ',
	'Class:Software/Attribute:softwarelicence_list' => 'Ліцензії ПЗ',
	'Class:Software/Attribute:softwarelicence_list+' => 'Ліцензії для цього ПЗ',
));

//
// Class: Patch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Patch' => 'Патч',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Назва',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Документи',
	'Class:Patch/Attribute:documents_list+' => 'Всі документи, пов\'язані з цим патчем',
	'Class:Patch/Attribute:description' => 'Опис',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Тип',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OSPatch' => 'Патч ОС',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Пристрої',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Всі системи, де встановлено цей патч',
	'Class:OSPatch/Attribute:osversion_id' => 'Версія ОС',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Версія ОС',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SoftwarePatch' => 'Патч ПЗ',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'ПЗ',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'ПЗ',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Екземпляри ПЗ',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Екземпляри ПЗ, де встановлено цей патч',
));

//
// Class: Licence
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Licence' => 'Ліцензія',
	'Class:Licence+' => 'Ліцензія',
	'Class:Licence/Attribute:name' => 'Назва',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Документи',
	'Class:Licence/Attribute:documents_list+' => 'Всі документи, пов\'язані з цією ліцензією',
	'Class:Licence/Attribute:org_id' => 'Організація',
	'Class:Licence/Attribute:org_id+' => 'Організація',
	'Class:Licence/Attribute:organization_name' => 'Організація',
	'Class:Licence/Attribute:organization_name+' => 'Організація',
	'Class:Licence/Attribute:usage_limit' => 'Обмеження використання',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Опис',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Дата початку',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Дата закінчення',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Ключ',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Безстрокова',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Ні',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'Ні',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Так',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'Так',
	'Class:Licence/Attribute:finalclass' => 'Тип',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OSLicence' => 'Ліцензія ОС',
	'Class:OSLicence+' => 'Ліцензія ОС',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:OSLicence/Attribute:osversion_id' => 'Версія ОС',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Версія ОС',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Віртуальні машини',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Всі віртуальні машини, де використовується дана ліцензія',
	'Class:OSLicence/Attribute:servers_list' => 'Сервери',
	'Class:OSLicence/Attribute:servers_list+' => 'Всі сервери, де використовується дана ліцензія',
));
//
// Class: SoftwareLicence
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SoftwareLicence' => 'Ліцензія ПЗ',
	'Class:SoftwareLicence+' => 'Ліцензія ПЗ',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:SoftwareLicence/Attribute:software_id' => 'ПЗ',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'ПЗ',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Екземпляри ПЗ',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Екземпляри ПЗ, де використовується дана ліцензія',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToLicence' => 'Зв\'язок Документ/Ліцензія',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Ліцензія',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Ліцензія',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: OSVersion
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OSVersion' => 'Версія ОС',
	'Class:OSVersion+' => 'Версія ОС',
	'Class:OSVersion/Attribute:osfamily_id' => 'Сімейство ОС',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Сімейство ОС',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:OSFamily' => 'Сімейство ОС',
	'Class:OSFamily+' => '',
));

//
// Class: Brand
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Brand' => 'Бренд',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:logo' => 'Логотип',
	'Class:Brand/Attribute:logo+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Пристрої',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Всі пристрої цього бренду',
	'Class:Brand/UniquenessRule:name+' => 'Назва повинна бути унікальною',
	'Class:Brand/UniquenessRule:name' => 'Цей бренд вже існує',
));

//
// Class: Model
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Model' => 'Модель',
	'Class:Model+' => '',
	'Class:Model/ComplementaryName' => '%1$s - %2$s',
	'Class:Model/Attribute:brand_id' => 'Бренд',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Бренд',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:picture' => 'Зображення',
	'Class:Model/Attribute:picture+' => '',
	'Class:Model/Attribute:type' => 'Тип пристрою',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Джерело електроживлення',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Джерело електроживлення',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Дисковий масив',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Дисковий масив',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Корпус',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Корпус',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP-телефон',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP-телефон',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Мобільний телефон',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Мобільний телефон',
	'Class:Model/Attribute:type/Value:NAS' => 'Мережеве сховище',
	'Class:Model/Attribute:type/Value:NAS+' => 'Мережеве сховище',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Мережевий пристрій',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Мережевий пристрій',
	'Class:Model/Attribute:type/Value:PC' => 'Персональний комп\'ютер',
	'Class:Model/Attribute:type/Value:PC+' => 'Персональний комп\'ютер',
	'Class:Model/Attribute:type/Value:PDU' => 'Пристрій розподілу електроживлення',
	'Class:Model/Attribute:type/Value:PDU+' => 'Пристрій розподілу електроживлення',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Периферійний пристрій',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Периферійний пристрій',
	'Class:Model/Attribute:type/Value:Printer' => 'Принтер',
	'Class:Model/Attribute:type/Value:Printer+' => 'Принтер',
	'Class:Model/Attribute:type/Value:Rack' => 'Стійка',
	'Class:Model/Attribute:type/Value:Rack+' => 'Стійка',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN комутатор',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN комутатор',
	'Class:Model/Attribute:type/Value:Server' => 'Сервер',
	'Class:Model/Attribute:type/Value:Server+' => 'Сервер',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Система зберігання',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Система зберігання',
	'Class:Model/Attribute:type/Value:Tablet' => 'Планшет',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Планшет',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Стрічкова бібліотека',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Стрічкова бібліотека',
	'Class:Model/Attribute:type/Value:Phone' => 'Телефон',
	'Class:Model/Attribute:type/Value:Phone+' => 'Телефон',
	'Class:Model/Attribute:physicaldevices_list' => 'Пристрої',
	'Class:Model/Attribute:physicaldevices_list+' => 'Всі пристрої цієї моделі',
	'Class:Model/UniquenessRule:name_brand+' => 'Назва повинна бути унікальною в межах бренду',
	'Class:Model/UniquenessRule:name_brand' => 'ця модель вже існує для цього бренду',
));

//
// Class: NetworkDeviceType
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:NetworkDeviceType' => 'Тип мережевого пристрою',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Пристрої',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Всі мережеві пристрої цього типу',
));

//
// Class: IOSVersion
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:IOSVersion' => 'Версія IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Бренд',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Бренд',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToPatch' => 'Зв\'язок Документ/Патч',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s',
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

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Зв\'язок Екземпляр ПЗ/Патч ПЗ',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Патч ПЗ',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Патч ПЗ',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Екземпляр ПЗ',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Екземпляр ПЗ',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Зв\'язок Функціональна КО/Патч ОС',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Патч ОС',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Патч ОС',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Функціональна КО',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Функціональна КО',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToSoftware' => 'Зв\'язок Документ/ПЗ',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'ПЗ',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'ПЗ',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Subnet' => 'Підмережа',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s/%2$s',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s',
	'Class:Subnet/Attribute:description' => 'Опис',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Ім\'я підмережі',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Організація',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Організація',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP-адреса',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Маска підмережі',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Опис',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Організація',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Організація',
	'Class:VLAN/Attribute:org_name+' => '',
	'Class:VLAN/Attribute:subnets_list' => 'Підмережі',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Фізичні інтерфейси',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => 'Фізичні інтерфейси',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkSubnetToVLAN' => 'Зв\'язок Підмережа/VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Підмережа',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP-адреса підмережі',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Підмережа',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:NetworkInterface' => 'Мережевий інтерфейс',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Назва',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Тип',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:IPInterface' => 'IP інтерфейс',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP-адреса',
	'Class:IPInterface/Attribute:ipaddress+' => '',
	'Class:IPInterface/Attribute:macaddress' => 'MAC-адреса',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Коментар',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP-шлюз',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP-маска',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Швидкість',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PhysicalInterface' => 'Фізичний інтерфейс',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Name' => '%2$s %1$s',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Пристрої',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Пристрої',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Зв\'язок Фізичний інтерфейс/VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Фізичний інтерфейс',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Фізичний інтерфейс',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Пристрій',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Пристрій',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Тег VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));
//
// Class: LogicalInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:LogicalInterface' => 'Логічний інтерфейс',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Віртуальна машина',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Віртуальна машина',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:FiberChannelInterface' => 'Оптичний інтерфейс',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Швидкість',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Топологія',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Пристрій',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Пристрій',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Зв\'язок Підключувана КО/Мережевий пристрій',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Мережевий пристрій',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Мережевий пристрій',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Підключений пристрій',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Підключений пристрій',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Порт мереж. пристр.',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Порт підкл. пристр.',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Тип підключення',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'вхідне з\'єднання',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'вихідне з\'єднання',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Зв\'язок Прикладне рішення/Функціональна КО',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Прикладне рішення',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Прикладне рішення',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Функціональна КО',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Функціональна КО',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Зв\'язок Прикладне рішення/Бізнес-процес',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Бізнес-процес',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Бізнес-процес',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Прикладне рішення',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Прикладне рішення',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: Group
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Group' => 'Група',
	'Class:Group+' => '',
	'Class:Group/ComplementaryName' => '%1$s - %2$s',
	'Class:Group/Attribute:name' => 'Назва',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Статус',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:Group/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:Group/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:Group/Attribute:status/Value:production' => 'Експлуатація',
	'Class:Group/Attribute:status/Value:production+' => 'Експлуатація',
	'Class:Group/Attribute:org_id' => 'Організація',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Організація',
	'Class:Group/Attribute:owner_name+' => '',
	'Class:Group/Attribute:description' => 'Опис',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Тип',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Батьківська група',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Батьківська група',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'КО',
	'Class:Group/Attribute:ci_list+' => 'Пов\'язані конфігураційні одиниці',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Батьківська група',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkGroupToCI' => 'Зв\'язок Група/КО',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Група',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Група',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'КО',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'КО',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Підстава',
	'Class:lnkGroupToCI/Attribute:reason+' => 'Підстава, причина тощо',
));

// Add translation for Fieldsets

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Server:baseinfo' => 'Основне',
	'Server:Date' => 'Дати',
	'Server:moreinfo' => 'Специфікація',
	'Server:otherinfo' => 'Додатково',
	'Server:power' => 'Електроживлення',
	'Class:Subnet/Tab:IPUsage' => 'Використання IP-адрес',
	'Class:Subnet/Tab:IPUsage+' => 'Які IP-адреси в цій підмережі використовуються або ні',
	'Class:Subnet/Tab:IPUsage-explain' => 'Інтерфейси з IP-адресою в діапазоні: <em>%1$s</em> - <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Вільні IP-адреси',
	'Class:Subnet/Tab:FreeIPs-count' => 'Вільних IP-адрес: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Ось вибірка з 10 вільних IP-адрес',
	'Class:Document:PreviewTab' => 'Попередній перегляд',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToFunctionalCI' => 'Зв\'язок Документ/Функціональна КО',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Функціональна КО',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Функціональна КО',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Application Menu
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:Application' => 'Застосунки',
	'Menu:Application+' => 'Всі застосунки',
	'Menu:DBServer' => 'Сервери баз даних',
	'Menu:DBServer+' => 'Сервери баз даних',
	'Menu:BusinessProcess' => 'Бізнес-процеси',
	'Menu:BusinessProcess+' => 'Всі бізнес-процеси',
	'Menu:ApplicationSolution' => 'Прикладні рішення',
	'Menu:ApplicationSolution+' => 'Всі прикладні рішення',
	'Menu:ConfigManagementSoftware' => 'Управління програмним забезпеченням',
	'Menu:Licence' => 'Ліцензії',
	'Menu:Licence+' => 'Всі ліцензії',
	'Menu:Patch' => 'Патчі',
	'Menu:Patch+' => 'Всі патчі',
	'Menu:ApplicationInstance' => 'Встановлене ПЗ',
	'Menu:ApplicationInstance+' => 'Застосунки та сервери БД',
	'Menu:ConfigManagementHardware' => 'Управління інфраструктурою',
	'Menu:Subnet' => 'Підмережі',
	'Menu:Subnet+' => 'Всі підмережі',
	'Menu:NetworkDevice' => 'Мережеві пристрої',
	'Menu:NetworkDevice+' => 'Всі мережеві пристрої',
	'Menu:Server' => 'Сервери',
	'Menu:Server+' => 'Всі сервери',
	'Menu:Printer' => 'Принтери',
	'Menu:Printer+' => 'Всі принтери',
	'Menu:MobilePhone' => 'Мобільні телефони',
	'Menu:MobilePhone+' => 'Всі мобільні телефони',
	'Menu:PC' => 'Персональні комп\'ютери',
	'Menu:PC+' => 'Всі ПК',
	'Menu:NewCI' => 'Нова КО',
	'Menu:NewCI+' => 'Нова КО',
	'Menu:SearchCIs' => 'Пошук КО',
	'Menu:SearchCIs+' => 'Пошук КО',
	'Menu:ConfigManagement:Devices' => 'Пристрої',
	'Menu:ConfigManagement:AllDevices' => 'Всі пристрої',
	'Menu:ConfigManagement:virtualization' => 'Віртуалізація',
	'Menu:ConfigManagement:EndUsers' => 'Користувацькі пристрої',
	'Menu:ConfigManagement:SWAndApps' => 'Програмне забезпечення та застосунки',
	'Menu:ConfigManagement:Misc' => 'Різне',
	'Menu:Group' => 'Групи КО',
	'Menu:Group+' => 'Групи КО',
	'Menu:OSVersion' => 'Версія ОС',
	'Menu:OSVersion+' => 'Версія ОС',
	'Menu:Software' => 'Каталог ПЗ',
	'Menu:Software+' => 'Каталог ПЗ',
));

//
// Class: PhysicalInterface
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:PhysicalInterface/Attribute:org_id' => 'ID організації',
	'Class:PhysicalInterface/Attribute:org_id+' => '',
	'Class:PhysicalInterface/Attribute:location_id' => 'ID розташування',
	'Class:PhysicalInterface/Attribute:location_id+' => '',
));

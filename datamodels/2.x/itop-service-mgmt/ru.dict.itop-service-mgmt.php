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

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+


Dict::Add('RU RU', 'Russian', 'Русский', array(
'Menu:ServiceManagement' => 'Управление услугами',
'Menu:ServiceManagement+' => 'Управление услугами',
'Menu:Service:Overview' => 'Обзор',
'Menu:Service:Overview+' => '',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Договоры по уровню услуг',
'UI-ServiceManagementMenu-ContractsByStatus' => 'Договоры по статусу',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Договоры, оканчивающиеся в течение 30-ти дней',

'Menu:ProviderContract' => 'Договоры с поставщиками',
'Menu:ProviderContract+' => 'Договоры с поставщиками',
'Menu:CustomerContract' => 'Договоры с заказчиками',
'Menu:CustomerContract+' => 'Договоры с заказчиками',
'Menu:ServiceSubcategory' => 'Подкатегории услуг',
'Menu:ServiceSubcategory+' => 'Подкатегории услуг',
'Menu:Service' => 'Услуги',
'Menu:Service+' => 'Услуги',
'Menu:ServiceElement' => 'Элементы услуг',
'Menu:ServiceElement+' => 'Элементы услуг',
'Menu:SLA' => 'SLA',
'Menu:SLA+' => 'Соглашения об уровне услуг',
'Menu:SLT' => 'SLT',
'Menu:SLT+' => 'Целевые показатели уровня услуг',
'Menu:DeliveryModel' => 'Модели предоставления услуг',
'Menu:DeliveryModel+' => 'Модели предоставления услуг (Delivery models)',
'Menu:ServiceFamily' => 'Пакеты услуг',
'Menu:ServiceFamily+' => 'Пакеты услуг',
'Menu:Procedure' => 'Каталог процедур',
'Menu:Procedure+' => 'Каталог процедур',



));

//
// Class: Organization
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name',

));


//
// Class: ContractType
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ContractType' => 'Тип договора',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Contract' => 'Договор',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Название',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Заказчик',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Имя заказчика',
	'Class:Contract/Attribute:organization_name+' => 'Common name',
	'Class:Contract/Attribute:contacts_list' => 'Контакты',
	'Class:Contract/Attribute:contacts_list+' => 'Связанные контакты',
	'Class:Contract/Attribute:documents_list' => 'Документы',
	'Class:Contract/Attribute:documents_list+' => 'Связанные документы',
	'Class:Contract/Attribute:description' => 'Описание',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Дата начала',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Дата окончания',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Стоимость',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Валюта стоимости',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Доллары',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Евро',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Тип договора',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Имя типа договора',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Частота платежей',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Единица стоимости',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Поставщик',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Имя поставщика',
	'Class:Contract/Attribute:provider_name+' => 'Common name',
	'Class:Contract/Attribute:status' => 'Статус',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementation',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:Contract/Attribute:status/Value:production' => 'Производство',
	'Class:Contract/Attribute:status/Value:production+' => 'production',
	'Class:Contract/Attribute:finalclass' => 'Тип договора',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CustomerContract' => 'Договор с заказчиком',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Услуги',
	'Class:CustomerContract/Attribute:services_list+' => 'Связанные услуги',
));

//
// Class: ProviderContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ProviderContract' => 'Договор с поставщиком',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'КЕ',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Связанные конфигурационные единицы',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Соглашение об уровне услуги (Service Level Agreement)',
	'Class:ProviderContract/Attribute:coverage' => 'Время работы',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Тип договора',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Имя типа договора',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContactToContract' => 'Связь Контакт/Договор',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Договор',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Имя договора',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Контактное лицо',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContractToDocument' => 'Связь Договор/Документ',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Договор',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Имя договора',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Документ',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Имя документа',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Связь Функциональная КЕ/Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Имя договора поставщика',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Имя КЕ',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ServiceFamily' => 'Пакет услуг',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Название',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Услуги',
	'Class:ServiceFamily/Attribute:services_list+' => 'Связанные услуги',
));

//
// Class: Service
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Service' => 'Услуга',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Название',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Поставщик',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Имя поставщика',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Пакет услуг',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Имя пакета услуг',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Описание',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Документы',
	'Class:Service/Attribute:documents_list+' => 'Связанные документы',
	'Class:Service/Attribute:contacts_list' => 'Контакты',
	'Class:Service/Attribute:contacts_list+' => 'Связанные контакты',
	'Class:Service/Attribute:status' => 'Статус',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:Service/Attribute:status/Value:implementation+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Производство',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Договоры с заказчиками',
	'Class:Service/Attribute:customercontracts_list+' => 'Договоры с заказчиками',
	'Class:Service/Attribute:providercontracts_list' => 'Договоры с поставщиками',
	'Class:Service/Attribute:providercontracts_list+' => 'Договоры с поставщиками',
	'Class:Service/Attribute:functionalcis_list' => 'Зависимость от КЕ',
	'Class:Service/Attribute:functionalcis_list+' => 'Зависимость услуги от конфигурационных единиц',
	'Class:Service/Attribute:servicesubcategories_list' => 'Подкатегории услуги',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Подкатегории услуги',
));

//
// Class: lnkDocumentToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDocumentToService' => 'Связь Документ/Услуга',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Услуга',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Имя услуги',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Имя документа',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkContactToService' => 'Связь Контакт/Услуга',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Услуга',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Имя услуги',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Контактное лицо',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ServiceSubcategory' => 'Подкатегория услуги',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Название',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Описание',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Услуга',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Услуга',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Тип запроса',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Инцидент',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Запрос на обслуживание',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:ServiceSubcategory/Attribute:status' => 'Статус',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementation',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Устаревшее',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Производство',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production',
));

//
// Class: SLA
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Название',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Описание',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Поставщик',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Имя поставщика',
	'Class:SLA/Attribute:organization_name+' => 'Common name',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => 'Целевой показатель уровня услуги (Service Level Target)',
	'Class:SLA/Attribute:customercontracts_list' => 'Договоры с заказчиками',
	'Class:SLA/Attribute:customercontracts_list+' => 'Договоры с заказчиками',
));

//
// Class: SLT
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Название',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Приоритет',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'Критический',
	'Class:SLT/Attribute:priority/Value:1+' => 'critical',
	'Class:SLT/Attribute:priority/Value:2' => 'Высокий',
	'Class:SLT/Attribute:priority/Value:2+' => 'high',
	'Class:SLT/Attribute:priority/Value:3' => 'Средний',
	'Class:SLT/Attribute:priority/Value:3+' => 'medium',
	'Class:SLT/Attribute:priority/Value:4' => 'Низкий',
	'Class:SLT/Attribute:priority/Value:4+' => 'low',
	'Class:SLT/Attribute:request_type' => 'Тип запроса',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Инцидент',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'service request',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:SLT/Attribute:metric' => 'Метрика',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Значение',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Единицы',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Часы',
	'Class:SLT/Attribute:unit/Value:hours+' => 'часов',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Минуты',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'минут',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSLAToSLT' => 'Связь SLA/SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Название SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Название SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkCustomerContractToService' => 'Связь Договор с заказчиком/Услуга',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Договор с заказчиком',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Контактное лицо клиента',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Услуга',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Имя услуги',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'Название SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkProviderContractToService' => 'Связь Договор с поставщиком/Услуга',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Услуга',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Имя услуги',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Договор с поставщиком',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Имя договора поставщика',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToService' => 'Связь Функциональная КЕ/Услуга',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Услуга',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Имя услуги',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Имя КЕ',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:DeliveryModel' => 'Модель предоставления услуг',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Название',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Организация',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Название организации',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Common name',
	'Class:DeliveryModel/Attribute:description' => 'Описание',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Контакты',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Связанные контакты',
	'Class:DeliveryModel/Attribute:customers_list' => 'Заказчики',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Связанные заказчик',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDeliveryModelToContact' => 'Связь Модель предоставления услуг/Контакт',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Модель предоставления услуг',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Название модели предоставления услуг',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Контакт',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Контактное лицо',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Роль',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Должность',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));


?>

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
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:ServiceManagement' => 'Управление услугами',
	'Menu:ServiceManagement+' => 'Управление услугами',
	'Menu:Service:Overview' => 'Обзор',
	'Menu:Service:Overview+' => 'Управление услугами - Обзор',
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
	'Menu:DeliveryModel+' => 'Модели предоставления услуг (Delivery Models)',
	'Menu:ServiceFamily' => 'Пакеты услуг',
	'Menu:ServiceFamily+' => 'Пакеты услуг',
	'Menu:Procedure' => 'Каталог процедур',
	'Menu:Procedure+' => 'Каталог процедур',
));

//
// Class: Organization
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Модель предоставления услуг',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Модель предоставления услуг (Delivery Model)',
	'Class:Organization/Attribute:deliverymodel_name' => 'Модель предоставления услуг',
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
	'Class:Contract/Attribute:organization_name' => 'Заказчик',
	'Class:Contract/Attribute:organization_name+' => '',
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
	'Class:Contract/Attribute:contracttype_name' => 'Тип договора',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Периодичность платежей',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Единица стоимости',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Поставщик',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Поставщик',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Статус',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:Contract/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:Contract/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:Contract/Attribute:status/Value:production+' => 'Эксплуатация',
	'Class:Contract/Attribute:finalclass' => 'Тип',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:CustomerContract' => 'Договор с заказчиком',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Услуги',
	'Class:CustomerContract/Attribute:services_list+' => 'Все услуги, предоставляемые по договору',
));

//
// Class: ProviderContract
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:ProviderContract' => 'Договор с поставщиком',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'КЕ',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Конфигурационные единицы, охватываемые договором',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Соглашение об уровне услуги (Service Level Agreement)',
	'Class:ProviderContract/Attribute:coverage' => 'Время обслуживания',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Тип договора',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Тип договора',
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
	'Class:lnkContactToContract/Attribute:contract_name' => 'Договор',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Контакт',
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
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Договор',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Документ',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Документ',
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
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'КЕ',
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
	'Class:ServiceFamily/Attribute:icon' => 'Иконка',
	'Class:ServiceFamily/Attribute:icon+' => 'Используется на клиентском портале',
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
	'Class:Service/Attribute:organization_name' => 'Поставщик',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Пакет услуг',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Пакет услуг',
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
	'Class:Service/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:Service/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:Service/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:Service/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:Service/Attribute:status/Value:production+' => 'Эксплуатация',
	'Class:Service/Attribute:icon' => 'Иконка',
	'Class:Service/Attribute:icon+' => 'Используется на клиентском портале',
	'Class:Service/Attribute:customercontracts_list' => 'Договоры с заказчиками',
	'Class:Service/Attribute:customercontracts_list+' => 'Договоры с заказчиками, по которым предоставляется услуга',
	'Class:Service/Attribute:providercontracts_list' => 'Договоры с поставщиками',
	'Class:Service/Attribute:providercontracts_list+' => 'Договоры с поставщиками, по которым поддерживается услуга',
	'Class:Service/Attribute:functionalcis_list' => 'Зависимость от КЕ',
	'Class:Service/Attribute:functionalcis_list+' => 'Конфигурационные единицы, которые используются для предоставления услуги',
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
	'Class:lnkDocumentToService/Attribute:service_name' => 'Услуга',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Документ',
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
	'Class:lnkContactToService/Attribute:service_name' => 'Услуга',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Контакт',
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
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'Инцидент',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Запрос на обслуживание',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'Запрос на обслуживание',
	'Class:ServiceSubcategory/Attribute:status' => 'Статус',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Внедрение',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'Внедрение',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Устаревший',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'Устаревший',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Эксплуатация',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'Эксплуатация',
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
	'Class:SLA/Attribute:organization_name' => 'Поставщик',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => 'Целевые показатели уровня услуги (Service Level Target)',
	'Class:SLA/Attribute:customercontracts_list' => 'Договоры с заказчиками',
	'Class:SLA/Attribute:customercontracts_list+' => 'Договоры с заказчиками, в которых используется SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists~~',
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
	'Class:SLT/Attribute:priority/Value:1+' => 'Критический',
	'Class:SLT/Attribute:priority/Value:2' => 'Высокий',
	'Class:SLT/Attribute:priority/Value:2+' => 'Высокий',
	'Class:SLT/Attribute:priority/Value:3' => 'Средний',
	'Class:SLT/Attribute:priority/Value:3+' => 'Средний',
	'Class:SLT/Attribute:priority/Value:4' => 'Низкий',
	'Class:SLT/Attribute:priority/Value:4+' => 'Низкий',
	'Class:SLT/Attribute:request_type' => 'Тип запроса',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Инцидент',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'Инцидент',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Запрос на обслуживание',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'Запрос на обслуживание',
	'Class:SLT/Attribute:metric' => 'Метрика',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'Time-To-Own - время до назначения агента (принятия в работу)',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'Time-To-Resolve - время до решения',
	'Class:SLT/Attribute:value' => 'Значение',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Единицы',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Часы',
	'Class:SLT/Attribute:unit/Value:hours+' => 'Часы',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Минуты',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'Минуты',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkSLAToSLT' => 'Связь SLA/SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Название SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Метрика SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Тип тикета',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Приоритет тикета',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Значение SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Единицы SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkCustomerContractToService' => 'Связь Договор с заказчиком/Услуга',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Договор с заказчиком',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Договор с заказчиком',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Услуга',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Услуга',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA',
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
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Услуга',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Договор с поставщиком',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Договор с поставщиком',
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
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Услуга',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'КЕ',
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
	'Class:DeliveryModel/Attribute:organization_name' => 'Организация',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Описание',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Контакты',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Контакты (команды и персоны), которые участвуют в предоставлении услуг по этой модели',
	'Class:DeliveryModel/Attribute:customers_list' => 'Заказчики',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Заказчики, которым предоставляются услуги по этой модели',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkDeliveryModelToContact' => 'Связь Модель предоставления услуг/Контакт',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Модель предоставления услуг',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Модель предоставления услуг',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Контакт',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Контакт',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Роль',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Роль',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

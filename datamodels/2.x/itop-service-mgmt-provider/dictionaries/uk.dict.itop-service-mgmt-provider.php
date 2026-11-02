<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Menu:ServiceManagement' => 'Управління сервісами',
	'Menu:ServiceManagement+' => 'Управління сервісами',
	'Menu:Service:Overview' => 'Огляд',
	'Menu:Service:Overview+' => 'Управління сервісами - Огляд',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Договори за рівнем сервісу',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Договори за статусом',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Договори, що закінчуються протягом 30-ти днів',
	'Menu:ProviderContract' => 'Договори з постачальниками',
	'Menu:ProviderContract+' => 'Договори з постачальниками',
	'Menu:CustomerContract' => 'Договори із замовниками',
	'Menu:CustomerContract+' => 'Договори із замовниками',
	'Menu:ServiceSubcategory' => 'Підкатегорії сервісів',
	'Menu:ServiceSubcategory+' => 'Підкатегорії сервісів',
	'Menu:Service' => 'Сервіси',
	'Menu:Service+' => 'Сервіси',
	'Menu:ServiceElement' => 'Елементи сервісів',
	'Menu:ServiceElement+' => 'Елементи сервісів',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => 'Угоди про рівень сервісів',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => 'Цільові показники рівня сервісів',
	'Menu:DeliveryModel' => 'Моделі надання сервісів',
	'Menu:DeliveryModel+' => 'Моделі надання сервісів (Delivery Models)',
	'Menu:ServiceFamily' => 'Пакети сервісів',
	'Menu:ServiceFamily+' => 'Пакети сервісів',
	'Contract:baseinfo' => 'Загальна інформація',
	'Contract:moreinfo' => 'Договірна інформація',
	'Contract:cost' => 'Інформація про вартість',
));

/*
	'UI:ServiceManagementMenu' => 'Gestion des Services',
	'UI:ServiceManagementMenu+' => 'Gestion des Services',
	'UI:ServiceManagementMenu:Title' => 'Résumé des services & contrats',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contrats par niveau de service',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contrats par état',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contrats se terminant dans moins de 30 jours',
*/

//
// Class: Organization
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Модель надання сервісів',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Модель надання сервісів (Delivery Model)',
	'Class:Organization/Attribute:deliverymodel_name' => 'Модель надання сервісів',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
));



//
// Class: ContractType
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ContractType' => 'Тип договору',
	'Class:ContractType+' => '',
));


//
// Class: Contract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Contract' => 'Договір',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Назва',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Замовник',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Замовник',
	'Class:Contract/Attribute:organization_name+' => '',
	'Class:Contract/Attribute:contacts_list' => 'Контакти',
	'Class:Contract/Attribute:contacts_list+' => 'Пов\'язані контакти',
	'Class:Contract/Attribute:documents_list' => 'Документи',
	'Class:Contract/Attribute:documents_list+' => 'Пов\'язані документи',
	'Class:Contract/Attribute:description' => 'Опис',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Дата початку',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Дата закінчення',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Вартість',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Валюта вартості',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Долари',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Євро',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Тип договору',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Тип договору',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Періодичність платежів',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Одиниця вартості',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Постачальник',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Постачальник',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Статус',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:Contract/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:Contract/Attribute:status/Value:production' => 'Експлуатація',
	'Class:Contract/Attribute:status/Value:production+' => 'Експлуатація',
	'Class:Contract/Attribute:finalclass' => 'Тип',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:CustomerContract' => 'Договір із замовником',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Сервіси',
	'Class:CustomerContract/Attribute:services_list+' => 'Всі сервіси, що надаються за договором',
	'Class:CustomerContract/Attribute:functionalcis_list' => 'КО',
	'Class:CustomerContract/Attribute:functionalcis_list+' => 'Конфігураційні одиниці, що охоплюються договором',
	'Class:CustomerContract/Attribute:providercontracts_list' => 'Договори з постачальниками',
	'Class:CustomerContract/Attribute:providercontracts_list+' => 'Договори з постачальниками, що використовуються для підтримки сервісів даного договору (Underpinning Contracts)',
));

//
// Class: ProviderContract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ProviderContract' => 'Договір з постачальником',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'КО',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Конфігураційні одиниці, що охоплюються договором',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Угода про рівень сервісу (Service Level Agreement)',
	'Class:ProviderContract/Attribute:coverage' => 'Час обслуговування',
	'Class:ProviderContract/Attribute:coverage+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContactToContract' => 'Зв\'язок Контакт/Договір',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Договір',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Договір',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Контакт',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContractToDocument' => 'Зв\'язок Договір/Документ',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s~~',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Договір',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Договір',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Документ',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Документ',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkProviderContractToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkProviderContractToFunctionalCI' => 'Зв\'язок Договір з постачальником/Функціональна КО',
	'Class:lnkProviderContractToFunctionalCI+' => '',
	'Class:lnkProviderContractToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkProviderContractToFunctionalCI/Attribute:providercontract_id' => 'Договір з постачальником',
	'Class:lnkProviderContractToFunctionalCI/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToFunctionalCI/Attribute:providercontract_name' => 'Договір з постачальником',
	'Class:lnkProviderContractToFunctionalCI/Attribute:providercontract_name+' => '',
	'Class:lnkProviderContractToFunctionalCI/Attribute:functionalci_id' => 'КО',
	'Class:lnkProviderContractToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkProviderContractToFunctionalCI/Attribute:functionalci_name' => 'КО',
	'Class:lnkProviderContractToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ServiceFamily' => 'Пакет сервісів',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Назва',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Іконка',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Сервіси',
	'Class:ServiceFamily/Attribute:services_list+' => 'Всі сервіси цього пакету',
));

//
// Class: Service
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:Service' => 'Сервіс',
	'Class:Service+' => '',
	'Class:Service/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Service/Attribute:name' => 'Назва',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Організація',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Організація',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:description' => 'Опис',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Пакет сервісів',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Пакет сервісів',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:documents_list' => 'Документи',
	'Class:Service/Attribute:documents_list+' => 'Всі документи, пов\'язані з сервісом',
	'Class:Service/Attribute:contacts_list' => 'Контакти',
	'Class:Service/Attribute:contacts_list+' => 'Всі контакти для цього сервісу',
	'Class:Service/Attribute:status' => 'Статус',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:Service/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:Service/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:Service/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:Service/Attribute:status/Value:production' => 'Експлуатація',
	'Class:Service/Attribute:status/Value:production+' => 'Експлуатація',
	'Class:Service/Attribute:icon' => 'Іконка',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Договори із замовниками',
	'Class:Service/Attribute:customercontracts_list+' => 'Всі договори із замовниками, що використовують цей сервіс',
	'Class:Service/Attribute:providercontracts_list' => 'Договори з постачальниками',
	'Class:Service/Attribute:providercontracts_list+' => 'Всі договори з постачальниками, що підтримують цей сервіс',
	'Class:Service/Attribute:functionalcis_list' => 'Залежить від КО',
	'Class:Service/Attribute:functionalcis_list+' => 'Всі конфігураційні одиниці, від яких залежить цей сервіс',
	'Class:Service/Attribute:servicesubcategories_list' => 'Підкатегорії сервісів',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Всі підкатегорії цього сервісу',
));

//
// Class: lnkDocumentToService
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDocumentToService' => 'Зв\'язок Документ/Сервіс',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Сервіс',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Сервіс',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Документ',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Документ',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContactToService' => 'Зв\'язок Контакт/Сервіс',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToService/Attribute:service_id' => 'Сервіс',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Сервіс',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Контакт',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Контакт',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:ServiceSubcategory' => 'Підкатегорія сервісу',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s~~',
	'Class:ServiceSubcategory/Attribute:name' => 'Назва',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Опис',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Сервіс',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Сервіс',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Тип запиту',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Інцидент',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'Інцидент',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Запит на обслуговування',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'Запит на обслуговування',
	'Class:ServiceSubcategory/Attribute:status' => 'Статус',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Впровадження',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'Впровадження',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Застарілий',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'Застарілий',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Експлуатація',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'Експлуатація',
));

//
// Class: SLA
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => 'Угода про рівень сервісів (Service Level Agreement)',
	'Class:SLA/Attribute:name' => 'Назва',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Опис',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Організація',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Організація',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => 'Всі цільові показники рівня сервісів (SLT)',
	'Class:SLA/Attribute:customercontracts_list' => 'Договори із замовниками',
	'Class:SLA/Attribute:customercontracts_list+' => 'Всі договори із замовниками, що використовують цей SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Не вдалося зберегти зв\'язок між сервісом %1$s та договором із замовником %2$s: SLA вже існує',
));

//
// Class: SLT
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Цільовий показник рівня сервісів (Service Level Target)',
	'Class:SLT/Attribute:name' => 'Назва',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Пріоритет',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'Критичний',
	'Class:SLT/Attribute:priority/Value:1+' => 'критичний',
	'Class:SLT/Attribute:priority/Value:2' => 'Високий',
	'Class:SLT/Attribute:priority/Value:2+' => 'високий',
	'Class:SLT/Attribute:priority/Value:3' => 'Середній',
	'Class:SLT/Attribute:priority/Value:3+' => 'середній',
	'Class:SLT/Attribute:priority/Value:4' => 'Низький',
	'Class:SLT/Attribute:priority/Value:4+' => 'низький',
	'Class:SLT/Attribute:request_type' => 'Тип запиту',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Інцидент',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'інцидент',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Запит на обслуговування',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'запит на обслуговування',
	'Class:SLT/Attribute:metric' => 'Метрика',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'Time-To-Own - час до призначення агента (прийняття в роботу)',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'Time-To-Resolve - час до вирішення',
	'Class:SLT/Attribute:value' => 'Значення',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Одиниці',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Години',
	'Class:SLT/Attribute:unit/Value:hours+' => 'Години',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Хвилини',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'Хвилини',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkSLAToSLT' => 'Зв\'язок SLA/SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s~~',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Назва SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Метрика SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Тип тікета',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Пріоритет тікета',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Значення SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Одиниці SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkCustomerContractToService' => 'Зв\'язок Договір із замовником/Сервіс',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Договір із замовником',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Договір із замовником',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Сервіс',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Сервіс',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkCustomerContractToProviderContract' => 'Зв\'язок Договір із замовником/Договір з постачальником',
	'Class:lnkCustomerContractToProviderContract+' => '',
	'Class:lnkCustomerContractToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id' => 'Договір із замовником',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name' => 'Договір із замовником',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id' => 'Договір з постачальником',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name' => 'Договір з постачальником',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name+' => '',
));

//
// Class: lnkCustomerContractToFunctionalCI
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkCustomerContractToFunctionalCI' => 'Зв\'язок Договір із замовником/Функціональна КО',
	'Class:lnkCustomerContractToFunctionalCI+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id' => 'Договір із замовником',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name' => 'Договір із замовником',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id' => 'КО',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name' => 'КО',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:DeliveryModel' => 'Модель надання сервісів',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Назва',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Організація',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Організація',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Опис',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Контакти',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Контакти (команди і персони), що беруть участь у наданні сервісів за цією моделлю',
	'Class:DeliveryModel/Attribute:customers_list' => 'Замовники',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Замовники, яким надаються сервіси за цією моделлю',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkDeliveryModelToContact' => 'Зв\'язок Модель надання сервісів/Контакт',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Модель надання сервісів',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Модель надання сервісів',
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

//
// Class: lnkContactToContract
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContactToContract/Attribute:customer_id' => 'ID замовника',
	'Class:lnkContactToContract/Attribute:customer_id+' => '',
	'Class:lnkContactToContract/Attribute:provider_id' => 'ID постачальника',
	'Class:lnkContactToContract/Attribute:provider_id+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkContractToDocument/Attribute:customer_id' => 'ID замовника',
	'Class:lnkContractToDocument/Attribute:customer_id+' => '',
	'Class:lnkContractToDocument/Attribute:provider_id' => 'ID постачальника',
	'Class:lnkContractToDocument/Attribute:provider_id+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:lnkCustomerContractToService/Attribute:provider_id' => 'ID постачальника',
	'Class:lnkCustomerContractToService/Attribute:provider_id+' => '',
));

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
 * @author      Robert Deng <denglx@gmail.com>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
'Menu:ServiceManagement' => '服务管理',
'Menu:ServiceManagement+' => '服务管理概览',
'Menu:Service:Overview' => '概览',
'Menu:Service:Overview+' => '',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => '按服务层级划分合同',
'UI-ServiceManagementMenu-ContractsByStatus' => '按状态划分合同',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '合同30天内终止',

'Menu:ProviderContract' => '供应商合同',
'Menu:ProviderContract+' => '供应商合同',
'Menu:CustomerContract' => '客户合同',
'Menu:CustomerContract+' => '客户合同',
'Menu:ServiceSubcategory' => '服务子级类目',
'Menu:ServiceSubcategory+' => '服务子级类目',
'Menu:Service' => '服务',
'Menu:Service+' => '服务',
'Menu:ServiceElement' => 'Sevice elements~~',
'Menu:ServiceElement+' => 'Sevice elements~~',
'Menu:SLA' => 'SLAs',
'Menu:SLA+' => '服务级别协议',
'Menu:SLT' => 'SLTs',
'Menu:SLT+' => '服务级别目标',
'Menu:DeliveryModel' => 'Delivery models~~',
'Menu:DeliveryModel+' => 'Delivery models~~',
'Menu:ServiceFamily' => 'Service families~~',
'Menu:ServiceFamily+' => 'Service families~~',
'Menu:Procedure' => 'Procedures catalog~~',
'Menu:Procedure+' => 'All procedures catalog~~',



));

//
// Class: Organization
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model~~',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name~~',

));


//
// Class: ContractType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ContractType' => 'Contract Type~~',
	'Class:ContractType+' => '~~',
));

//
// Class: Contract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Contract' => '合同',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => '名称',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Customer~~',
	'Class:Contract/Attribute:org_id+' => '~~',
	'Class:Contract/Attribute:organization_name' => 'Customer Name~~',
	'Class:Contract/Attribute:organization_name+' => 'Common name~~',
	'Class:Contract/Attribute:contacts_list' => 'Contacts~~',
	'Class:Contract/Attribute:contacts_list+' => 'All the contacts for this customer contract~~',
	'Class:Contract/Attribute:documents_list' => 'Documents~~',
	'Class:Contract/Attribute:documents_list+' => 'All the documents for this customer contract~~',
	'Class:Contract/Attribute:description' => '描述',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => '启始日期',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => '截止日期',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => '费用',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => '费用货币',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => '美元',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => '欧元',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Contract type~~',
	'Class:Contract/Attribute:contracttype_id+' => '~~',
	'Class:Contract/Attribute:contracttype_name' => 'Contract type Name~~',
	'Class:Contract/Attribute:contracttype_name+' => '~~',
	'Class:Contract/Attribute:billing_frequency' => '付款周期',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => '费用单位',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Provider~~',
	'Class:Contract/Attribute:provider_id+' => '~~',
	'Class:Contract/Attribute:provider_name' => 'Provider Name~~',
	'Class:Contract/Attribute:provider_name+' => 'Common name~~',
	'Class:Contract/Attribute:status' => 'Status~~',
	'Class:Contract/Attribute:status+' => '~~',
	'Class:Contract/Attribute:status/Value:implementation' => 'implementation~~',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Contract/Attribute:status/Value:obsolete' => 'obsolete~~',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:Contract/Attribute:status/Value:production' => 'production~~',
	'Class:Contract/Attribute:status/Value:production+' => 'production~~',
	'Class:Contract/Attribute:finalclass' => '类别',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CustomerContract' => '客户合同',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Services~~',
	'Class:CustomerContract/Attribute:services_list+' => 'All the services purchased for this contract~~',
));

//
// Class: ProviderContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ProviderContract' => '供应商合同',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs~~',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'All the configuration items covered by this provider contract~~',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => '服务级别协议',
	'Class:ProviderContract/Attribute:coverage' => '服务小时数',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Contract type~~',
	'Class:ProviderContract/Attribute:contracttype_id+' => '~~',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Contract type name~~',
	'Class:ProviderContract/Attribute:contracttype_name+' => '~~',
));

//
// Class: lnkContactToContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContactToContract' => 'Link Contact / Contract~~',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contract~~',
	'Class:lnkContactToContract/Attribute:contract_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Contract Name~~',
	'Class:lnkContactToContract/Attribute:contract_name+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contact~~',
	'Class:lnkContactToContract/Attribute:contact_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Contact Name~~',
	'Class:lnkContactToContract/Attribute:contact_name+' => '~~',
));

//
// Class: lnkContractToDocument
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContractToDocument' => 'Link Contract / Document~~',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contract~~',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Contract Name~~',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Document~~',
	'Class:lnkContractToDocument/Attribute:document_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Document Name~~',
	'Class:lnkContractToDocument/Attribute:document_name+' => '~~',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Link FunctionalCI / ProviderContract~~',
	'Class:lnkFunctionalCIToProviderContract+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Provider contract~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Provider contract Name~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI Name~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
));

//
// Class: ServiceFamily
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ServiceFamily' => 'Service Family~~',
	'Class:ServiceFamily+' => '~~',
	'Class:ServiceFamily/Attribute:name' => 'Name~~',
	'Class:ServiceFamily/Attribute:name+' => '~~',
	'Class:ServiceFamily/Attribute:icon' => 'Icon~~',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:ServiceFamily/Attribute:services_list' => 'Services~~',
	'Class:ServiceFamily/Attribute:services_list+' => 'All the services in this category~~',
));

//
// Class: Service
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Service' => '服务',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => '名称',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => '供应商',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Provider Name~~',
	'Class:Service/Attribute:organization_name+' => '~~',
	'Class:Service/Attribute:servicefamily_id' => 'Service Family~~',
	'Class:Service/Attribute:servicefamily_id+' => '~~',
	'Class:Service/Attribute:servicefamily_name' => 'Service Family Name~~',
	'Class:Service/Attribute:servicefamily_name+' => '~~',
	'Class:Service/Attribute:description' => '描述',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Documents~~',
	'Class:Service/Attribute:documents_list+' => 'All the documents linked to the service~~',
	'Class:Service/Attribute:contacts_list' => 'Contacts~~',
	'Class:Service/Attribute:contacts_list+' => 'All the contacts for this service~~',
	'Class:Service/Attribute:status' => '状态',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'implementation~~',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Service/Attribute:status/Value:obsolete' => '废弃',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => '生产',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icon~~',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:Service/Attribute:customercontracts_list' => 'Customer contracts~~',
	'Class:Service/Attribute:customercontracts_list+' => 'All the customer contracts that have purchased this service~~',
	'Class:Service/Attribute:providercontracts_list' => 'Provider contracts~~',
	'Class:Service/Attribute:providercontracts_list+' => 'All the provider contracts to support this service~~',
	'Class:Service/Attribute:functionalcis_list' => 'Depends on CIs~~',
	'Class:Service/Attribute:functionalcis_list+' => 'All the configuration items that are used to provide this service~~',
	'Class:Service/Attribute:servicesubcategories_list' => 'Service sub categories~~',
	'Class:Service/Attribute:servicesubcategories_list+' => 'All the sub categories for this service~~',
));

//
// Class: lnkDocumentToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToService' => 'Link Document / Service~~',
	'Class:lnkDocumentToService+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Service~~',
	'Class:lnkDocumentToService/Attribute:service_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkDocumentToService/Attribute:service_name+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToService/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Document Name~~',
	'Class:lnkDocumentToService/Attribute:document_name+' => '~~',
));

//
// Class: lnkContactToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContactToService' => 'Link Contact / Service~~',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Attribute:service_id' => 'Service~~',
	'Class:lnkContactToService/Attribute:service_id+' => '~~',
	'Class:lnkContactToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkContactToService/Attribute:service_name+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contact~~',
	'Class:lnkContactToService/Attribute:contact_id+' => '~~',
	'Class:lnkContactToService/Attribute:contact_name' => 'Contact Name~~',
	'Class:lnkContactToService/Attribute:contact_name+' => '~~',
));

//
// Class: ServiceSubcategory
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ServiceSubcategory' => '服务子级类目',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => '名称',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => '描述',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => '服务',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => '服务',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Request type~~',
	'Class:ServiceSubcategory/Attribute:request_type+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incident~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'service request~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:ServiceSubcategory/Attribute:status' => 'Status~~',
	'Class:ServiceSubcategory/Attribute:status+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementation~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'obsolete~~',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'production~~',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production~~',
));

//
// Class: SLA
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => '名称',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'description~~',
	'Class:SLA/Attribute:description+' => '~~',
	'Class:SLA/Attribute:org_id' => 'Provider~~',
	'Class:SLA/Attribute:org_id+' => '~~',
	'Class:SLA/Attribute:organization_name' => 'Provider Name~~',
	'Class:SLA/Attribute:organization_name+' => 'Common name~~',
	'Class:SLA/Attribute:slts_list' => 'SLTs~~',
	'Class:SLA/Attribute:slts_list+' => 'All the service level targets for this SLA~~',
	'Class:SLA/Attribute:customercontracts_list' => 'Customer contracts~~',
	'Class:SLA/Attribute:customercontracts_list+' => 'All the customer contracts using this SLA~~',
));

//
// Class: SLT
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => '名称',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Priority~~',
	'Class:SLT/Attribute:priority+' => '~~',
	'Class:SLT/Attribute:priority/Value:1' => 'critical~~',
	'Class:SLT/Attribute:priority/Value:1+' => 'critical~~',
	'Class:SLT/Attribute:priority/Value:2' => 'high~~',
	'Class:SLT/Attribute:priority/Value:2+' => 'high~~',
	'Class:SLT/Attribute:priority/Value:3' => 'medium~~',
	'Class:SLT/Attribute:priority/Value:3+' => 'medium~~',
	'Class:SLT/Attribute:priority/Value:4' => 'low~~',
	'Class:SLT/Attribute:priority/Value:4+' => 'low~~',
	'Class:SLT/Attribute:request_type' => 'Request type~~',
	'Class:SLT/Attribute:request_type+' => '~~',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incident~~',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'service request~~',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:SLT/Attribute:metric' => 'Metric',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO~~',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO~~',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR~~',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR~~',
	'Class:SLT/Attribute:value' => '值',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Unit~~',
	'Class:SLT/Attribute:unit+' => '~~',
	'Class:SLT/Attribute:unit/Value:hours' => 'hours~~',
	'Class:SLT/Attribute:unit/Value:hours+' => 'hours~~',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minutes~~',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minutes~~',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSLAToSLT' => 'Link SLA / SLT~~',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA~~',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA Name~~',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT~~',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT Name~~',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '~~',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkCustomerContractToService' => 'Link Customer Contract / Service~~',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Customer contract~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Customer contract Name~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service~~',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Name~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '~~',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkProviderContractToService' => 'Link Provider Contract / Service~~',
	'Class:lnkProviderContractToService+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Service~~',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Provider contract~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Provider contract Name~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '~~',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToService' => 'Link FunctionalCI / Service~~',
	'Class:lnkFunctionalCIToService+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'CI Name~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
));

//
// Class: DeliveryModel
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DeliveryModel' => 'Delivery Model~~',
	'Class:DeliveryModel+' => '~~',
	'Class:DeliveryModel/Attribute:name' => 'Name~~',
	'Class:DeliveryModel/Attribute:name+' => '~~',
	'Class:DeliveryModel/Attribute:org_id' => 'Organization~~',
	'Class:DeliveryModel/Attribute:org_id+' => '~~',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organization Name~~',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Common name~~',
	'Class:DeliveryModel/Attribute:description' => 'Description~~',
	'Class:DeliveryModel/Attribute:description+' => '~~',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contacts~~',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'All the contacts (Teams and Person) for this delivery model~~',
	'Class:DeliveryModel/Attribute:customers_list' => 'Customers~~',
	'Class:DeliveryModel/Attribute:customers_list+' => 'All the customers having this delivering model~~',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDeliveryModelToContact' => 'Link Delivery Model / Contact~~',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Delivery model~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Delivery model name~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contact~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Contact name~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Role~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Role name~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '~~',
));

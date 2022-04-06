<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @author	Erik Bøg <erik@boegmoeller.dk>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Menu:ServiceManagement' => 'Ydelses Management',
	'Menu:ServiceManagement+' => 'Ydelses Management oversigt',
	'Menu:Service:Overview' => 'Oversigt',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Kontrakt(er) efter Service Level',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Kontrakter efter status',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Kontrakt(er), der udløber om mindre end 30 dage',
	'Menu:ProviderContract' => 'Leverandør kontrakter',
	'Menu:ProviderContract+' => 'Leverandør kontrakter',
	'Menu:CustomerContract' => 'Kunde kontrakter',
	'Menu:CustomerContract+' => 'Kunde kontrakter',
	'Menu:ServiceSubcategory' => 'Ydelses underkategorier',
	'Menu:ServiceSubcategory+' => 'Ydelses underkategorier',
	'Menu:Service' => 'Ydelser',
	'Menu:Service+' => 'Ydelser',
	'Menu:ServiceElement' => 'Ydelses elementer',
	'Menu:ServiceElement+' => '',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Service Level Agreements',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Service Level Targets',
	'Menu:DeliveryModel' => 'Leverings model(ler)',
	'Menu:DeliveryModel+' => '',
	'Menu:ServiceFamily' => 'Ydelses familie(r)',
	'Menu:ServiceFamily+' => '',
	'Menu:Procedure' => 'Procedure katalog',
	'Menu:Procedure+' => '',
));

//
// Class: Organization
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Leverings model',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Leverings model navn',
));


//
// Class: ContractType
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ContractType' => 'Kontrakt type',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Contract' => 'Kontrakt',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Navn',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Kunde',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Kunde navn',
	'Class:Contract/Attribute:organization_name+' => '',
	'Class:Contract/Attribute:contacts_list' => 'Kontakt',
	'Class:Contract/Attribute:contacts_list+' => '',
	'Class:Contract/Attribute:documents_list' => 'Dokument',
	'Class:Contract/Attribute:documents_list+' => '',
	'Class:Contract/Attribute:description' => 'Beskrivelse',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Startdato',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Udløbsdato',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Omkostninger',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollar',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Kontrakt type',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Kontrakt type navn',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Afregnings frekvens',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Enhedsomkostning',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Leverandør',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Leverandør navn',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Status',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Implementering',
	'Class:Contract/Attribute:status/Value:implementation+' => '',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:Contract/Attribute:status/Value:obsolete+' => '',
	'Class:Contract/Attribute:status/Value:production' => 'Produktion',
	'Class:Contract/Attribute:status/Value:production+' => '',
	'Class:Contract/Attribute:finalclass' => 'Type',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CustomerContract' => 'Kunde kontrakt',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Ydelser',
	'Class:CustomerContract/Attribute:services_list+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ProviderContract' => 'Leverandør kontrakt',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Content Items',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Dækning',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Kontrakt type',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Kontrakt type navn',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkContactToContract' => 'Sammenhæng Kontakt/Kontrakt',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Kontrakt',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Kontrakt navn',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Kontakt navn',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkContractToDocument' => 'Sammenhæng Kontrakt/Dokument',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Kontrakt',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Kontrakt navn',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Dokument',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Dokument navn',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ServiceFamily' => 'Ydelses-familie',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Navn',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Icon~~',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Ydelser',
	'Class:ServiceFamily/Attribute:services_list+' => '',
));

//
// Class: Service
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Service' => 'Ydelse',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Navn',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Udbyder',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Leverandør navn',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Service familie',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Ydelses familie navn',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Beskrivelse',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Dokumenter',
	'Class:Service/Attribute:documents_list+' => '',
	'Class:Service/Attribute:contacts_list' => 'Kontakter',
	'Class:Service/Attribute:contacts_list+' => '',
	'Class:Service/Attribute:status' => 'Status',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Implementering',
	'Class:Service/Attribute:status/Value:implementation+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Produktion',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icon~~',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Kunde kontrakter',
	'Class:Service/Attribute:customercontracts_list+' => '',
	'Class:Service/Attribute:providercontracts_list' => 'Leverandør kontrakter',
	'Class:Service/Attribute:providercontracts_list+' => '',
	'Class:Service/Attribute:functionalcis_list' => 'Nødvendige CIs',
	'Class:Service/Attribute:functionalcis_list+' => '',
	'Class:Service/Attribute:servicesubcategories_list' => 'Ydelses subkategorier',
	'Class:Service/Attribute:servicesubcategories_list+' => '',
));

//
// Class: lnkDocumentToService
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDocumentToService' => 'Sammenhæng Dokument/Ydelse',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Ydelse',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Ydelses navn',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Dokument navn',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkContactToService' => 'Sammenhæng Kontakt/Ydelse',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Ydelse',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Ydelses navn',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Kontakt navn',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ServiceSubcategory' => 'Ydelse underkategorier',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Navn',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Beskrivelse',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Ydelse',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Ydelse',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Anmodnings type',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Service Anmodning',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '',
	'Class:ServiceSubcategory/Attribute:status' => 'Status',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Implementering',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Produktion',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => '',
));

//
// Class: SLA
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Navn',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Beskrivelse',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Leverandør',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Leverandør navn',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => 'Service Level Threshholds:',
	'Class:SLA/Attribute:customercontracts_list' => 'Kunde kontrakter',
	'Class:SLA/Attribute:customercontracts_list+' => '',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists~~',
));

//
// Class: SLT
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Service Level Threshholds',
	'Class:SLT/Attribute:name' => 'Navn',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Prioritet',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'Kritisk',
	'Class:SLT/Attribute:priority/Value:1+' => '',
	'Class:SLT/Attribute:priority/Value:2' => 'Høj',
	'Class:SLT/Attribute:priority/Value:2+' => '',
	'Class:SLT/Attribute:priority/Value:3' => 'Middel',
	'Class:SLT/Attribute:priority/Value:3+' => '',
	'Class:SLT/Attribute:priority/Value:4' => 'Lav',
	'Class:SLT/Attribute:priority/Value:4+' => '',
	'Class:SLT/Attribute:request_type' => 'Anmodnings type',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => '',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Service Anmodning',
	'Class:SLT/Attribute:request_type/Value:service_request+' => '',
	'Class:SLT/Attribute:metric' => 'Metrisk',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO (Time To Own)',
	'Class:SLT/Attribute:metric/Value:tto+' => '',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR (Time To Resolve)',
	'Class:SLT/Attribute:metric/Value:ttr+' => '',
	'Class:SLT/Attribute:value' => 'Værdi',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Enhed',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Timer',
	'Class:SLT/Attribute:unit/Value:hours+' => '',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Minutter',
	'Class:SLT/Attribute:unit/Value:minutes+' => '',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkSLAToSLT' => 'Sammenhæng SLA/SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA navn',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT navn',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Slt metric~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Slt request type~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Slt ticket priority~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Slt value~~',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Slt value unit~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '~~',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkCustomerContractToService' => 'Sammenhæng Kunde kontrakt/Ydelse',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Kunde kontrakt',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Kunde kontrakt navn',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Ydelse',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Ydelses navn',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA navn',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkProviderContractToService' => 'Sammenhæng Leverandør kontrakt/Ydelse',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Ydelse',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Ydelses navn',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Leverandør kontrakt',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Leverandør kontrakt navn',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DeliveryModel' => 'Leverings model',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Navn',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organisation',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organisations navn',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Beskrivelse',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Kontakt',
	'Class:DeliveryModel/Attribute:contacts_list+' => '',
	'Class:DeliveryModel/Attribute:customers_list' => 'Kunde',
	'Class:DeliveryModel/Attribute:customers_list+' => '',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDeliveryModelToContact' => 'Sammenhæng Leverings model/Kontakt',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Leverings model',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Leverings model navn',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kontakt',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Kontakt navn',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rolle',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Rolle navn',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

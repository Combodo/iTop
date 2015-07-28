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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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


Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ServiceManagement' => 'Service Management',
	'Menu:ServiceManagement+' => 'Service Management Overzicht',
	'Menu:Service:Overview' => 'Overzicht',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contracten per service level',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contracten met status',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contracten die in minder dan 30 dagen eindigen',
	'Menu:ProviderContract' => 'Leveranciers contracten',
	'Menu:ProviderContract+' => 'Leveranciers contracten',
	'Menu:CustomerContract' => 'Klant contracten',
	'Menu:CustomerContract+' => 'Klant contracten',
	'Menu:ServiceSubcategory' => 'Dienst subcategorieën',
	'Menu:ServiceSubcategory+' => 'Dienst subcategorieën',
	'Menu:Service' => 'Diensten',
	'Menu:Service+' => 'Diensten',
	'Menu:ServiceElement' => 'Dienst elementen',
	'Menu:ServiceElement+' => 'Dienst elementen',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Service Level Agreements',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Service Level Targets',
	'Menu:DeliveryModel' => 'Delivery models',
	'Menu:DeliveryModel+' => 'Delivery models',
	'Menu:ServiceFamily' => 'Dienst families',
	'Menu:ServiceFamily+' => 'Dienst families',
	'Menu:Procedure' => 'Proceduren catalog',
	'Menu:Procedure+' => 'Alle proceduren catalog',

));

//
// Class: Organization
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name',

));


//
// Class: ContractType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ContractType' => 'Contract Type',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ContractType' => 'Contract Type',
	'Class:ContractType+' => '',
	'Class:Contract' => 'Contract',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Naam',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Klant',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Klant Naam',
	'Class:Contract/Attribute:organization_name+' => 'Gemeenschappelijke naam',
	'Class:Contract/Attribute:contacts_list' => 'Contacten',
	'Class:Contract/Attribute:contacts_list+' => 'Alle contacten voor dit klant contract',
	'Class:Contract/Attribute:documents_list' => 'Documenten',
	'Class:Contract/Attribute:documents_list+' => 'Alle documenten voor dit klant contract',
	'Class:Contract/Attribute:description' => 'Omschrijving',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Start datum',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Eind datum',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Kosten',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollars',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euros',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Contract type',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Contract type Name',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Facturering frequentie',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Kost eenheid',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Leverancier',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Leverancier Naam',
	'Class:Contract/Attribute:provider_name+' => 'Gemeenschappelijke naam',
	'Class:Contract/Attribute:status' => 'Status',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'implementatie',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementatie',
	'Class:Contract/Attribute:status/Value:obsolete' => 'overbodig',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'overbodig',
	'Class:Contract/Attribute:status/Value:production' => 'productie',
	'Class:Contract/Attribute:status/Value:production+' => 'productie',
	'Class:Contract/Attribute:finalclass' => 'Type',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CustomerContract' => 'Klant Contract',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Diensten',
	'Class:CustomerContract/Attribute:services_list+' => 'Alle diensten die zijn aangeschaft voor dit contract',
));

//
// Class: ProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ProviderContract' => 'Leveranciers Contract',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Alle configuratie items die gedekt zijn door dit leveranciers contract',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Dienst uren',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Contract type',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Contract type name',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToContract' => 'Link Contact / Contract',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contract',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Contract Naam',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Contact Naam',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContractToDocument' => 'Link Contract / Document',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contract',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Contract Naam',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Document',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Document Naam',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
'Class:lnkFunctionalCIToProviderContract' => 'Link FunctionalCI / ProviderContract',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Provider contract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Provider contract Naam',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI Naam',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ServiceFamily' => 'Dienst Familie',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Naam',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Diensten',
	'Class:ServiceFamily/Attribute:services_list+' => 'Alle diensten in deze categorie',
));

//
// Class: Service
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Service' => 'Dienst',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Naam',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Leverancier',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Leverancier Naam',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Service Familie',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Service Familie Naam',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Omschrijving',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Documenten',
	'Class:Service/Attribute:documents_list+' => 'Alle documenten die gelinkt zijn aan deze dienst',
	'Class:Service/Attribute:contacts_list' => 'Contacten',
	'Class:Service/Attribute:contacts_list+' => 'Alle contacten voor deze dienst',
	'Class:Service/Attribute:status' => 'Status',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'implementatie',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementatie',
	'Class:Service/Attribute:status/Value:obsolete' => 'overbodig',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'productie',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Klant contracten',
	'Class:Service/Attribute:customercontracts_list+' => 'Alle klanten contracten die deze dienst hebben aangeschaft',
	'Class:Service/Attribute:providercontracts_list' => 'Leveranciers contracten',
	'Class:Service/Attribute:providercontracts_list+' => 'Alle leveranciers contracten die ondersteuning bieden voor deze dienst',
	'Class:Service/Attribute:functionalcis_list' => 'Afhankelijk van CIs',
	'Class:Service/Attribute:functionalcis_list+' => 'Alle configuratie items die gebruikt worden voor het beschikbaarheid van deze dienst',
	'Class:Service/Attribute:servicesubcategories_list' => 'Dienst subcategorieën',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Alle subcategorieën van deze dienst',
));

//
// Class: lnkDocumentToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToService' => 'Link Document / Dienst',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Dienst',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Dienst Naam',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Document Naam',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToService' => 'Link Contact / Dienst',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Dienst',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Dienst Naam',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Contact Naam',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ServiceSubcategory' => 'Dienst Subcategorie',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Naam',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Omschrijving',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Dienst',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Dienst naam',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Verzoek type',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'service request',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:ServiceSubcategory/Attribute:status' => 'Status',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementatie',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementatie',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'overbodig',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'overbodig',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'productie',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'productie',
));

//
// Class: SLA
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Naam',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'omschrijving',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Provider',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Leveranciers Naam',
	'Class:SLA/Attribute:organization_name+' => 'Gemeenschappelijke naam',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => 'Alle service level doelstellingen voor deze SLA',
	'Class:SLA/Attribute:customercontracts_list' => 'Klant contracten',
	'Class:SLA/Attribute:customercontracts_list+' => 'Alle klant contracten die gebruik maken van deze SLA',
));

//
// Class: SLT
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Naam',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Prioriteit',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'kritisch',
	'Class:SLT/Attribute:priority/Value:1+' => 'kritisch',
	'Class:SLT/Attribute:priority/Value:2' => 'hoog',
	'Class:SLT/Attribute:priority/Value:2+' => 'hoog',
	'Class:SLT/Attribute:priority/Value:3' => 'normaal',
	'Class:SLT/Attribute:priority/Value:3+' => 'normaal',
	'Class:SLT/Attribute:priority/Value:4' => 'laag',
	'Class:SLT/Attribute:priority/Value:4+' => 'laag',
	'Class:SLT/Attribute:request_type' => 'Verzoek type',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'service request',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:SLT/Attribute:metric' => 'Metric',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Waarde',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Eenheid',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'uren',
	'Class:SLT/Attribute:unit/Value:hours+' => 'uren',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minuten',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minuten',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSLAToSLT' => 'Link SLA / SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA Naam',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT Naam',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkCustomerContractToService' => 'Link Customer Contract / Dienst',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Klant contract',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Klant contract Naam',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Dienst',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Dienst Naam',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Naam',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkProviderContractToService' => 'Link Provider Contract / Dienst',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Dienst',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Dienst Name',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Leveranciers contract',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Leveranciers contract Naam',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToService' => 'Link FunctionalCI / Dienst',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Dienst',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Dienst Naam',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'CI Naam',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DeliveryModel' => 'Delivery Model',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Naam',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organisatie',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organisatie Naam',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Gemeenschappelijke naam',
	'Class:DeliveryModel/Attribute:description' => 'Omschrijving',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contacten',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Alle contacten (Teams en Personen) voor dit delivery model',
	'Class:DeliveryModel/Attribute:customers_list' => 'Klanten',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Alle klanten die gebruik maken van dit delivering model',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDeliveryModelToContact' => 'Link Delivery Model / Contact',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Delivery model naam',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Contact naam',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Rol naam',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));


?>

<?php
// Copyright (C) 2010-2019 Combodo SARL
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
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
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
	'Menu:ServiceManagement+' => 'Overzicht van Service Management',
	'Menu:Service:Overview' => 'Overzicht',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contracten per servicelevel',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contracten per status',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contracten die in minder dan 30 dagen verlopen',

	'Menu:ProviderContract' => 'Leverancierscontracten',
	'Menu:ProviderContract+' => 'Leverancierscontracten',
	'Menu:CustomerContract' => 'Klantencontracten',
	'Menu:CustomerContract+' => 'Klantencontracten',
	'Menu:ServiceSubcategory' => 'Subcategorieën services',
	'Menu:ServiceSubcategory+' => 'Subcategorieën services',
	'Menu:Service' => 'Services',
	'Menu:Service+' => 'Services',
	'Menu:ServiceElement' => 'Service-elementen',
	'Menu:ServiceElement+' => 'Service-elementen',
	'Menu:SLA' => 'SLA\'s',
	'Menu:SLA+' => 'Service Level Agreements',
	'Menu:SLT' => 'SLT\'s',
	'Menu:SLT+' => 'Service Level Targets',
	'Menu:DeliveryModel' => 'Leveringsmodellen',
	'Menu:DeliveryModel+' => 'Leveringsmodellen',
	'Menu:ServiceFamily' => 'Servicecategorieën',
	'Menu:ServiceFamily+' => 'Servicecategorieën',
	'Menu:Procedure' => 'Procedurecatalogus',
	'Menu:Procedure+' => 'Alle procedures in een catalogus',
));

//
// Class: Organization
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Leveringsmodel',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Naam leveringsmodel',
));


//
// Class: ContractType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ContractType' => 'Soort contract',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Contract' => 'Contract',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Naam',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Klant',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Naam klant',
	'Class:Contract/Attribute:organization_name+' => 'Naam van de klant',
	'Class:Contract/Attribute:contacts_list' => 'Contacten',
	'Class:Contract/Attribute:contacts_list+' => 'Alle contacten voor dit klantencontract',
	'Class:Contract/Attribute:documents_list' => 'Documenten',
	'Class:Contract/Attribute:documents_list+' => 'Alle documenten voor dit klantencontract',
	'Class:Contract/Attribute:description' => 'Omschrijving',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Startdatum',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Einddatum',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Kostprijs',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollar',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Soort contract',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Naam soort contract',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Frequentie facturatie',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Eenheid kostprijs',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Leverancier',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Naam leverancier',
	'Class:Contract/Attribute:provider_name+' => 'Naam van de leverancier',
	'Class:Contract/Attribute:status' => 'Status',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:Contract/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Buiten gebruik',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Buiten gebruik',
	'Class:Contract/Attribute:status/Value:production' => 'Productie',
	'Class:Contract/Attribute:status/Value:production+' => 'Productie',
	'Class:Contract/Attribute:finalclass' => 'Subklasse contract',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CustomerContract' => 'Klantencontract',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Services',
	'Class:CustomerContract/Attribute:services_list+' => 'Alle services die aangeschaft zijn voor dit contract',
));

//
// Class: ProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ProviderContract' => 'Leverancierscontract',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CI\'s',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Alle configuratie-items die gedekt zijn door dit leverancierscontract',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Werkuren',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Soort contract',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Naam soort contract',
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
	'Class:lnkContactToContract/Attribute:contract_name' => 'Naam contract',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Naam contact',
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
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Naam contract',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Document',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Naam document',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Link Functioneel CI / Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Naam leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ServiceFamily' => 'Servicecategorie',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Naam',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Pictogram',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Services',
	'Class:ServiceFamily/Attribute:services_list+' => 'Alle services in deze categorie',
));

//
// Class: Service
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Service' => 'Service',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Naam',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Leverancier',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Naam leverancier',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Servicecategorie',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Naam servicecategorie',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Omschrijving',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Documenten',
	'Class:Service/Attribute:documents_list+' => 'Alle documenten die gerelateerd zijn aan deze service',
	'Class:Service/Attribute:contacts_list' => 'Contacten',
	'Class:Service/Attribute:contacts_list+' => 'Alle contacten voor deze service',
	'Class:Service/Attribute:status' => 'Status',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:Service/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:Service/Attribute:status/Value:obsolete' => 'Buiten gebruik',
	'Class:Service/Attribute:status/Value:obsolete+' => 'Buiten gebruik',
	'Class:Service/Attribute:status/Value:production' => 'Productie',
	'Class:Service/Attribute:status/Value:production+' => 'Productie',
	'Class:Service/Attribute:icon' => 'Pictogram',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Klantencontracten',
	'Class:Service/Attribute:customercontracts_list+' => 'Alle klantencontracten die deze service hebben aangeschaft',
	'Class:Service/Attribute:providercontracts_list' => 'Leverancierscontracten',
	'Class:Service/Attribute:providercontracts_list+' => 'Alle leverancierscontracten die ondersteuning bieden voor deze service',
	'Class:Service/Attribute:functionalcis_list' => 'Afhankelijk van CI\'s',
	'Class:Service/Attribute:functionalcis_list+' => 'Alle configuratie-items die gebruikt worden voor het beschikbaarheid van deze service',
	'Class:Service/Attribute:servicesubcategories_list' => 'Subcategorieën service',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Alle subcategorieën van deze service',
));

//
// Class: lnkDocumentToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToService' => 'Link Document / Service',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Service',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Naam service',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Naam document',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToService' => 'Link Contact / Service',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Service',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Naam service',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Naam contact',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ServiceSubcategory' => 'Subcategorie service',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Naam',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Omschrijving',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Service',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Naam service',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Soort verzoek',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'Incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Serviceverzoek',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'Serviceverzoek',
	'Class:ServiceSubcategory/Attribute:status' => 'Status',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Buiten gebruik',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'Buiten gebruik',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Productie',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'Productie',
));

//
// Class: SLA
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Naam',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Omschrijving',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Provider',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Naam leverancier',
	'Class:SLA/Attribute:organization_name+' => 'Naam van de leverancier',
	'Class:SLA/Attribute:slts_list' => 'SLT\'s',
	'Class:SLA/Attribute:slts_list+' => 'Alle servicelevel-doelstellingen voor deze SLA',
	'Class:SLA/Attribute:customercontracts_list' => 'Klantencontracten',
	'Class:SLA/Attribute:customercontracts_list+' => 'Alle klantencontracten die gebruik maken van deze SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists~~',
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
	'Class:SLT/Attribute:priority/Value:1' => 'Kritisch',
	'Class:SLT/Attribute:priority/Value:1+' => 'Kritisch',
	'Class:SLT/Attribute:priority/Value:2' => 'Hoog',
	'Class:SLT/Attribute:priority/Value:2+' => 'Hoog',
	'Class:SLT/Attribute:priority/Value:3' => 'Normaal',
	'Class:SLT/Attribute:priority/Value:3+' => 'Normaal',
	'Class:SLT/Attribute:priority/Value:4' => 'Laag',
	'Class:SLT/Attribute:priority/Value:4+' => 'Laag',
	'Class:SLT/Attribute:request_type' => 'Soort verzoek',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'Incident',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Serviceverzoek',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'Serviceverzoek',
	'Class:SLT/Attribute:metric' => 'Maatstaf',
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
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Naam SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Naam SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Maatstaf SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Soort SLT-verzoek',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Prioriteit SLT-verzoek',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'SLT-waarde',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Eenheid SLT-waarde',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkCustomerContractToService' => 'Link Klantencontract / Service',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Klantencontract',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Naam klantencontract',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Naam service',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'Naam SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkProviderContractToService' => 'Link Leverancierscontract / Service',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Service',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Naam service',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Leverancierscontract',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Naam leverancierscontract',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToService' => 'Link Functioneel CI / Service',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Naam service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Naam CI',
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
	'Class:DeliveryModel/Attribute:organization_name' => 'Naam organisatie',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Naam van de organisatie',
	'Class:DeliveryModel/Attribute:description' => 'Omschrijving',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contacten',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Alle contacten (teams en personen) voor dit leveringsmodel',
	'Class:DeliveryModel/Attribute:customers_list' => 'Klanten',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Alle klanten die gebruik maken van dit leveringsmodel',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDeliveryModelToContact' => 'Link Leveringsmodel / Contact',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Leveringsmodel',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Naam leveringsmodel',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Naam contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Naam rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

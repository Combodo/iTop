<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2019 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Menu:ServiceManagement' => 'Manažment služieb',
	'Menu:ServiceManagement+' => '',
	'Menu:Service:Overview' => 'Prehľad',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'zmluvy podľa úrovne služby',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'zmluvy podla stavu',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'zmluvy končiace za menej ako 30 dní',

	'Menu:ProviderContract' => 'Poskytovateľské zmluvy',
	'Menu:ProviderContract+' => '',
	'Menu:CustomerContract' => 'Zákaznícke zmluvy',
	'Menu:CustomerContract+' => '',
	'Menu:ServiceSubcategory' => 'Subkategórie služieb',
	'Menu:ServiceSubcategory+' => '',
	'Menu:Service' => 'Služby',
	'Menu:Service+' => '',
	'Menu:ServiceElement' => 'Prvky služby',
	'Menu:ServiceElement+' => '',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => '',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => '',
	'Menu:DeliveryModel' => 'Typy dodávky',
	'Menu:DeliveryModel+' => '',
	'Menu:ServiceFamily' => 'Rodiny služieb',
	'Menu:ServiceFamily+' => '',
	'Menu:Procedure' => 'Katalóg procedúr',
	'Menu:Procedure+' => '',
));

//
// Class: Organization
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Model dodávky',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Názov modelu dodávky',
));


//
// Class: ContractType
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ContractType' => 'Typ zmluvy',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Contract' => 'zmluva',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Názov',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Zákazník',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Meno zákazníka',
	'Class:Contract/Attribute:organization_name+' => '',
	'Class:Contract/Attribute:contacts_list' => 'Kontakty',
	'Class:Contract/Attribute:contacts_list+' => '',
	'Class:Contract/Attribute:documents_list' => 'Zoznam dokumentov',
	'Class:Contract/Attribute:documents_list+' => '',
	'Class:Contract/Attribute:description' => 'Popis',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Dátum začiatku',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Dátum ukončenia',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Cena',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Mena ceny',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'USD',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'EUR',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Typ zmluvy',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Názov typu zmluvy',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Frekvencia faktúrovania',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Cenová jednotka',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Poskytovateľ',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Meno poskytovateľa',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Stav',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:Contract/Attribute:status/Value:implementation+' => '',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Vyradený',
	'Class:Contract/Attribute:status/Value:obsolete+' => '',
	'Class:Contract/Attribute:status/Value:production' => 'Produkcia',
	'Class:Contract/Attribute:status/Value:production+' => '',
	'Class:Contract/Attribute:finalclass' => 'Typ',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:CustomerContract' => 'Zákaznícka zmluva',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Služby',
	'Class:CustomerContract/Attribute:services_list+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ProviderContract' => 'Poskytovateľská zmluva',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'Zariadenia',
	'Class:ProviderContract/Attribute:functionalcis_list+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => '',
	'Class:ProviderContract/Attribute:coverage' => 'Časy pokrytia',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Typ zmluvy',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Názov typu zmluvy',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContactToContract' => 'väzba - Kontakt / zmluva',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'zmluva',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Názov zmluvy',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Názov kontaktu',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContractToDocument' => 'väzba - zmluva / Dokument',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'zmluva',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Názov zmluvy',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Dokument',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkFunctionalCIToProviderContract' => 'väzba - Komponent / Poskytovateľská zmluva',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Poskytovateľská zmluva',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Názov poskytovateľského zmluvy',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Zariadenie',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ServiceFamily' => 'Kategória služieb',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Názov',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Icon~~',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:ServiceFamily/Attribute:services_list' => 'Služby',
	'Class:ServiceFamily/Attribute:services_list+' => '',
));

//
// Class: Service
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Service' => 'Služby',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Názov',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Poskytovateľ',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Meno poskytovateľa',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Kategória služieb',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Názov rodiny služieb',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Popis',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Dokumenty',
	'Class:Service/Attribute:documents_list+' => '',
	'Class:Service/Attribute:contacts_list' => 'Kontakty',
	'Class:Service/Attribute:contacts_list+' => '',
	'Class:Service/Attribute:status' => 'Stav',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:Service/Attribute:status/Value:implementation+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Vyradená',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Produkcia',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icon~~',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:Service/Attribute:customercontracts_list' => 'Zákaznícke zmluvy',
	'Class:Service/Attribute:customercontracts_list+' => '',
	'Class:Service/Attribute:providercontracts_list' => 'Poskytovateľské zmluvy',
	'Class:Service/Attribute:providercontracts_list+' => '',
	'Class:Service/Attribute:functionalcis_list' => 'Zariadenia',
	'Class:Service/Attribute:functionalcis_list+' => '',
	'Class:Service/Attribute:servicesubcategories_list' => 'Podkategórie služieb',
	'Class:Service/Attribute:servicesubcategories_list+' => '',
));

//
// Class: lnkDocumentToService
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDocumentToService' => 'väzba - Dokument / Služba',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Služba',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContactToService' => 'väzba - Kontakt / Služba',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Služba',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Názov kontaktu',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ServiceSubcategory' => 'Podkategória služieb',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Názov',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Popis',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Služba',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Názov služby',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Typ požiadavky',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Požiadavka',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '',
	'Class:ServiceSubcategory/Attribute:status' => 'Stav',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Vyradená',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Produkcia',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => '',
));

//
// Class: SLA
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Názov',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Popis',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Poskytovateľ',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Názov organizácie poskytovateľa',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => '',
	'Class:SLA/Attribute:customercontracts_list' => 'Zákaznícke zmluvy',
	'Class:SLA/Attribute:customercontracts_list+' => '',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists~~',
));

//
// Class: SLT
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Názov',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Priorita',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'Kritická',
	'Class:SLT/Attribute:priority/Value:1+' => '',
	'Class:SLT/Attribute:priority/Value:2' => 'Vysoká',
	'Class:SLT/Attribute:priority/Value:2+' => '',
	'Class:SLT/Attribute:priority/Value:3' => 'Stredná',
	'Class:SLT/Attribute:priority/Value:3+' => '',
	'Class:SLT/Attribute:priority/Value:4' => 'Nízka',
	'Class:SLT/Attribute:priority/Value:4+' => '',
	'Class:SLT/Attribute:request_type' => 'Typ požiadavky',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => '',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Požiadavka',
	'Class:SLT/Attribute:request_type/Value:service_request+' => '',
	'Class:SLT/Attribute:metric' => 'Metrika',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => '',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => '',
	'Class:SLT/Attribute:value' => 'Hodnota',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Jednotka',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Hodiny',
	'Class:SLT/Attribute:unit/Value:hours+' => '',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Minúty',
	'Class:SLT/Attribute:unit/Value:minutes+' => '',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkSLAToSLT' => 'väzba - SLA / SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA Názov',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT Názov',
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

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkCustomerContractToService' => 'väzba - Zákaznícka zmluva / Služba',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Zákaznícka zmluva',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Názov zákazníckeho zmluvy',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Služba',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Názov',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkProviderContractToService' => 'väzba - Poskytovateľská zmluva / Služba',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Služba',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Poskytovateľská zmluva',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Názov poskytovateľského zmluvy',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkFunctionalCIToService' => 'väzba - Komponent / Služba',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Služba',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Zariadenie',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DeliveryModel' => 'Model dodávky',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Názov',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organizácia',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Názov organizácie',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Popis',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Kontakty',
	'Class:DeliveryModel/Attribute:contacts_list+' => '',
	'Class:DeliveryModel/Attribute:customers_list' => 'Zákazníci',
	'Class:DeliveryModel/Attribute:customers_list+' => '',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDeliveryModelToContact' => 'väzba - Model dodávky / Kontakt',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Model dodávky',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Názov typu dodávky',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kontakt',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Meno kontaktu',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rola',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Názov role',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

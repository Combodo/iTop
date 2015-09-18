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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
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


Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Menu:ServiceManagement' => 'Správa služeb',
    'Menu:ServiceManagement+' => 'Přehled správy služeb',
    'Menu:Service:Overview' => 'Přehled',
    'Menu:Service:Overview+' => '',
    'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Smlouvy podle úrovně služeb',
    'UI-ServiceManagementMenu-ContractsByStatus' => 'Smlouvy podle stavu',
    'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Smlouvy končící během 30 dní',

    'Menu:ProviderContract' => 'Smlouvy s poskytovateli',
    'Menu:ProviderContract+' => 'Smlouvy s poskytovateli',
    'Menu:CustomerContract' => 'Smlouvy se zákazníky',
    'Menu:CustomerContract+' => 'Smlouvy se zákazníky',
    'Menu:ServiceSubcategory' => 'Podkategorie služeb',
    'Menu:ServiceSubcategory+' => 'Podkategorie služeb',
    'Menu:Service' => 'Služby',
    'Menu:Service+' => 'Služby',
    'Menu:ServiceElement' => 'Prvky služby',
    'Menu:ServiceElement+' => 'Prvky služby',
    'Menu:SLA' => 'SLA - dohody o úrovních služeb',
    'Menu:SLA+' => 'Dohody o úrovních služeb',
    'Menu:SLT' => 'SLT - cíle úrovní služeb',
    'Menu:SLT+' => 'Cíle úrovní služeb',
    'Menu:DeliveryModel' => 'Modely poskytování služeb',
    'Menu:DeliveryModel+' => 'Modely poskytování služeb',
    'Menu:ServiceFamily' => 'Balíčky (kategorie) služeb',
    'Menu:ServiceFamily+' => 'Balíčky (kategorie) služeb',
    'Menu:Procedure' => 'Katalog postupů',
    'Menu:Procedure+' => 'Katalog všech postupů',
));

//
// Class: Organization
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Organization/Attribute:deliverymodel_id' => 'Model poskytování služeb',
    'Class:Organization/Attribute:deliverymodel_id+' => '',
    'Class:Organization/Attribute:deliverymodel_name' => 'Název modelu poskytování služeb',

));

//
// Class: ContractType
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ContractType' => 'Typ smlouvy',
    'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Contract' => 'Smlouva',
    'Class:Contract+' => '',
    'Class:Contract/Attribute:name' => 'Název',
    'Class:Contract/Attribute:name+' => '',
    'Class:Contract/Attribute:org_id' => 'Zákazník',
    'Class:Contract/Attribute:org_id+' => '',
    'Class:Contract/Attribute:organization_name' => 'Název zákazníka',
    'Class:Contract/Attribute:organization_name+' => '',
    'Class:Contract/Attribute:contacts_list' => 'Kontakty',
    'Class:Contract/Attribute:contacts_list+' => 'Všechny kontakty pro tuto smlouvu se zákazníkem',
    'Class:Contract/Attribute:documents_list' => 'Dokumenty',
    'Class:Contract/Attribute:documents_list+' => 'Všechny dokumenty pro tuto smlouvu se zákazníkem',
    'Class:Contract/Attribute:description' => 'Popis',
    'Class:Contract/Attribute:description+' => '',
    'Class:Contract/Attribute:start_date' => 'Datum zahájení',
    'Class:Contract/Attribute:start_date+' => '',
    'Class:Contract/Attribute:end_date' => 'Datum ukončení',
    'Class:Contract/Attribute:end_date+' => '',
    'Class:Contract/Attribute:cost' => 'Cena',
    'Class:Contract/Attribute:cost+' => '',
    'Class:Contract/Attribute:cost_currency' => 'Měna',
    'Class:Contract/Attribute:cost_currency+' => '',
    'Class:Contract/Attribute:cost_currency/Value:dollars' => 'USD',
    'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
    'Class:Contract/Attribute:cost_currency/Value:euros' => 'EUR',
    'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
    'Class:Contract/Attribute:contracttype_id' => 'Typ smlouvy',
    'Class:Contract/Attribute:contracttype_id+' => '',
    'Class:Contract/Attribute:contracttype_name' => 'Název typu smlouvy',
    'Class:Contract/Attribute:contracttype_name+' => '',
    'Class:Contract/Attribute:billing_frequency' => 'Frekvence plateb',
    'Class:Contract/Attribute:billing_frequency+' => '',
    'Class:Contract/Attribute:cost_unit' => 'Jednotkové náklady',
    'Class:Contract/Attribute:cost_unit+' => '',
    'Class:Contract/Attribute:provider_id' => 'Poskytovatel',
    'Class:Contract/Attribute:provider_id+' => '',
    'Class:Contract/Attribute:provider_name' => 'Název poskytovatele',
    'Class:Contract/Attribute:provider_name+' => '',
    'Class:Contract/Attribute:status' => 'Stav',
    'Class:Contract/Attribute:status+' => '',
    'Class:Contract/Attribute:status/Value:implementation' => 'implementace',
    'Class:Contract/Attribute:status/Value:implementation+' => '',
    'Class:Contract/Attribute:status/Value:obsolete' => 'zastaralý',
    'Class:Contract/Attribute:status/Value:obsolete+' => '',
    'Class:Contract/Attribute:status/Value:production' => 'v produkci',
    'Class:Contract/Attribute:status/Value:production+' => '',
    'Class:Contract/Attribute:finalclass' => 'Typ',
    'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CustomerContract' => 'Smlouva se zákazníkem',
    'Class:CustomerContract+' => '',
    'Class:CustomerContract/Attribute:services_list' => 'Služby',
    'Class:CustomerContract/Attribute:services_list+' => 'Všechny služby pod touto smlouvou',
));

//
// Class: ProviderContract
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ProviderContract' => 'Smlouva s poskytovatelem',
    'Class:ProviderContract+' => '',
    'Class:ProviderContract/Attribute:functionalcis_list' => 'Konfigurační položky',
    'Class:ProviderContract/Attribute:functionalcis_list+' => 'Všechny konfigurační položky pokryté touto smlouvou s poskytovatelem',
    'Class:ProviderContract/Attribute:sla' => 'SLA',
    'Class:ProviderContract/Attribute:sla+' => 'Dohoda o úrovni služeb',
    'Class:ProviderContract/Attribute:coverage' => 'Servisní hodiny',
    'Class:ProviderContract/Attribute:coverage+' => '',
    'Class:ProviderContract/Attribute:contracttype_id' => 'Typ smlouvy',
    'Class:ProviderContract/Attribute:contracttype_id+' => '',
    'Class:ProviderContract/Attribute:contracttype_name' => 'Název typu smlouvy',
    'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkContactToContract' => 'Spojení (Kontakt / Smlouva)',
    'Class:lnkContactToContract+' => '',
    'Class:lnkContactToContract/Attribute:contract_id' => 'Smlouva',
    'Class:lnkContactToContract/Attribute:contract_id+' => '',
    'Class:lnkContactToContract/Attribute:contract_name' => 'Název smlouvy',
    'Class:lnkContactToContract/Attribute:contract_name+' => '',
    'Class:lnkContactToContract/Attribute:contact_id' => 'Kontakt',
    'Class:lnkContactToContract/Attribute:contact_id+' => '',
    'Class:lnkContactToContract/Attribute:contact_name' => 'Název kontaktu',
    'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkContractToDocument' => 'Spojení (Smlouva / Dokument)',
    'Class:lnkContractToDocument+' => '',
    'Class:lnkContractToDocument/Attribute:contract_id' => 'Smlouva',
    'Class:lnkContractToDocument/Attribute:contract_id+' => '',
    'Class:lnkContractToDocument/Attribute:contract_name' => 'Název smlouvy',
    'Class:lnkContractToDocument/Attribute:contract_name+' => '',
    'Class:lnkContractToDocument/Attribute:document_id' => 'Dokument',
    'Class:lnkContractToDocument/Attribute:document_id+' => '',
    'Class:lnkContractToDocument/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkFunctionalCIToProviderContract' => 'Spojení (Funkční konfigurační položka / Smlouva s poskytovatelem)',
    'Class:lnkFunctionalCIToProviderContract+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Smlouva s poskytovatelem',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Název smlouvy s poskytovatelem',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Konfigurační položka',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Název konfigurační položky',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ServiceFamily' => 'Balíček služeb',
    'Class:ServiceFamily+' => '',
    'Class:ServiceFamily/Attribute:name' => 'Název',
    'Class:ServiceFamily/Attribute:name+' => '',
    'Class:ServiceFamily/Attribute:services_list' => 'Služby',
    'Class:ServiceFamily/Attribute:services_list+' => 'Všechny služby v této kategorii',
));

//
// Class: Service
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Service' => 'Služba',
    'Class:Service+' => '',
    'Class:Service/Attribute:name' => 'Název',
    'Class:Service/Attribute:name+' => '',
    'Class:Service/Attribute:org_id' => 'Poskytovatel',
    'Class:Service/Attribute:org_id+' => '',
    'Class:Service/Attribute:organization_name' => 'Název poskytovatele',
    'Class:Service/Attribute:organization_name+' => '',
    'Class:Service/Attribute:servicefamily_id' => 'Balíček služeb',
    'Class:Service/Attribute:servicefamily_id+' => '',
    'Class:Service/Attribute:servicefamily_name' => 'Název rodiny služeb',
    'Class:Service/Attribute:servicefamily_name+' => '',
    'Class:Service/Attribute:description' => 'Popis',
    'Class:Service/Attribute:description+' => '',
    'Class:Service/Attribute:documents_list' => 'Dokumenty',
    'Class:Service/Attribute:documents_list+' => 'Všechny dokumenty spojené s touto službou',
    'Class:Service/Attribute:contacts_list' => 'Kontakty',
    'Class:Service/Attribute:contacts_list+' => 'Všechny kontakty pro tuto službu',
    'Class:Service/Attribute:status' => 'Stav',
    'Class:Service/Attribute:status+' => '',
    'Class:Service/Attribute:status/Value:implementation' => 'implementace',
    'Class:Service/Attribute:status/Value:implementation+' => '',
    'Class:Service/Attribute:status/Value:obsolete' => 'zastaralá',
    'Class:Service/Attribute:status/Value:obsolete+' => '',
    'Class:Service/Attribute:status/Value:production' => 'v produkci',
    'Class:Service/Attribute:status/Value:production+' => '',
    'Class:Service/Attribute:customercontracts_list' => 'Smlouvy se zákazníky',
    'Class:Service/Attribute:customercontracts_list+' => 'Všechny smlouvy se zákazníky, kteří zakoupili tuto službu',
    'Class:Service/Attribute:providercontracts_list' => 'Smlouvy s poskytovateli',
    'Class:Service/Attribute:providercontracts_list+' => 'Všechny smlouvy s poskytovateli pro tuto službu',
    'Class:Service/Attribute:functionalcis_list' => 'Konfigurační položky',
    'Class:Service/Attribute:functionalcis_list+' => 'Všechny konfigurační položky využívané pro poskytování této služby',
    'Class:Service/Attribute:servicesubcategories_list' => 'Podkategorie služeb',
    'Class:Service/Attribute:servicesubcategories_list+' => 'Všechny podkategorie služeb pro tuto službu',
));

//
// Class: lnkDocumentToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToService' => 'Spojení (Dokument / Služba)',
    'Class:lnkDocumentToService+' => '',
    'Class:lnkDocumentToService/Attribute:service_id' => 'Služba',
    'Class:lnkDocumentToService/Attribute:service_id+' => '',
    'Class:lnkDocumentToService/Attribute:service_name' => 'Název služby',
    'Class:lnkDocumentToService/Attribute:service_name+' => '',
    'Class:lnkDocumentToService/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToService/Attribute:document_id+' => '',
    'Class:lnkDocumentToService/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkContactToService' => 'Spojení (Kontakt / Služba)',
    'Class:lnkContactToService+' => '',
    'Class:lnkContactToService/Attribute:service_id' => 'Služba',
    'Class:lnkContactToService/Attribute:service_id+' => '',
    'Class:lnkContactToService/Attribute:service_name' => 'Název služby',
    'Class:lnkContactToService/Attribute:service_name+' => '',
    'Class:lnkContactToService/Attribute:contact_id' => 'Kontakt',
    'Class:lnkContactToService/Attribute:contact_id+' => '',
    'Class:lnkContactToService/Attribute:contact_name' => 'Název kontaktu',
    'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ServiceSubcategory' => 'Podkategorie služeb',
    'Class:ServiceSubcategory+' => '',
    'Class:ServiceSubcategory/Attribute:name' => 'Název',
    'Class:ServiceSubcategory/Attribute:name+' => '',
    'Class:ServiceSubcategory/Attribute:description' => 'Popis',
    'Class:ServiceSubcategory/Attribute:description+' => '',
    'Class:ServiceSubcategory/Attribute:service_id' => 'Služba',
    'Class:ServiceSubcategory/Attribute:service_id+' => '',
    'Class:ServiceSubcategory/Attribute:service_name' => 'Název služby',
    'Class:ServiceSubcategory/Attribute:service_name+' => '',
    'Class:ServiceSubcategory/Attribute:request_type' => 'Typ požadavku',
    'Class:ServiceSubcategory/Attribute:request_type+' => '',
    'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incident',
    'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '',
    'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'požadavek na službu',
    'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '',
    'Class:ServiceSubcategory/Attribute:status' => 'Stav',
    'Class:ServiceSubcategory/Attribute:status+' => '',
    'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementace',
    'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '',
    'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'zastaralá',
    'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '',
    'Class:ServiceSubcategory/Attribute:status/Value:production' => 'v produkci',
    'Class:ServiceSubcategory/Attribute:status/Value:production+' => '',
));

//
// Class: SLA
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SLA' => 'SLA',
    'Class:SLA+' => 'Dohoda o úrovni služeb',
    'Class:SLA/Attribute:name' => 'Název',
    'Class:SLA/Attribute:name+' => '',
    'Class:SLA/Attribute:description' => 'Popis',
    'Class:SLA/Attribute:description+' => '',
    'Class:SLA/Attribute:org_id' => 'Poskytovatel',
    'Class:SLA/Attribute:org_id+' => '',
    'Class:SLA/Attribute:organization_name' => 'Název poskytovatele',
    'Class:SLA/Attribute:organization_name+' => '',
    'Class:SLA/Attribute:slts_list' => 'SLTs',
    'Class:SLA/Attribute:slts_list+' => 'Všechny cíle úrovně služeb pro tuto dohodu o úrovni služeb',
    'Class:SLA/Attribute:customercontracts_list' => 'Smlouvy se zákazníky',
    'Class:SLA/Attribute:customercontracts_list+' => 'Všechny smlouvy se zákazníky využívající tuto dohodu o úrovni služeb',
));

//
// Class: SLT
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SLT' => 'SLT',
    'Class:SLT+' => 'Cíl úrovně služeb',
    'Class:SLT/Attribute:name' => 'Název',
    'Class:SLT/Attribute:name+' => '',
    'Class:SLT/Attribute:priority' => 'Priorita',
    'Class:SLT/Attribute:priority+' => '',
    'Class:SLT/Attribute:priority/Value:1' => 'kritická',
    'Class:SLT/Attribute:priority/Value:1+' => '',
    'Class:SLT/Attribute:priority/Value:2' => 'vysoká',
    'Class:SLT/Attribute:priority/Value:2+' => '',
    'Class:SLT/Attribute:priority/Value:3' => 'střední',
    'Class:SLT/Attribute:priority/Value:3+' => '',
    'Class:SLT/Attribute:priority/Value:4' => 'nízká',
    'Class:SLT/Attribute:priority/Value:4+' => '',
    'Class:SLT/Attribute:request_type' => 'Typ požadavku',
    'Class:SLT/Attribute:request_type+' => '',
    'Class:SLT/Attribute:request_type/Value:incident' => 'incident',
    'Class:SLT/Attribute:request_type/Value:incident+' => '',
    'Class:SLT/Attribute:request_type/Value:service_request' => 'Požadavek na službu',
    'Class:SLT/Attribute:request_type/Value:service_request+' => '',
    'Class:SLT/Attribute:metric' => 'Metrika',
    'Class:SLT/Attribute:metric+' => '',
    'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
    'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
    'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
    'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
    'Class:SLT/Attribute:value' => 'Hodnota',
    'Class:SLT/Attribute:value+' => '',
    'Class:SLT/Attribute:unit' => 'Jednotka',
    'Class:SLT/Attribute:unit+' => '',
    'Class:SLT/Attribute:unit/Value:hours' => 'hodiny',
    'Class:SLT/Attribute:unit/Value:hours+' => '',
    'Class:SLT/Attribute:unit/Value:minutes' => 'minuty',
    'Class:SLT/Attribute:unit/Value:minutes+' => '',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkSLAToSLT' => 'Spojení (SLA / SLT)',
    'Class:lnkSLAToSLT+' => '',
    'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
    'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
    'Class:lnkSLAToSLT/Attribute:sla_name' => 'Název SLA',
    'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
    'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
    'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
    'Class:lnkSLAToSLT/Attribute:slt_name' => 'Název SLT',
    'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkCustomerContractToService' => 'Spojení (Smlouva se zákazníkem / Služba)',
    'Class:lnkCustomerContractToService+' => '',
    'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Smlouva se zákazníkem',
    'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
    'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Název smlouvy se zákazníkem',
    'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
    'Class:lnkCustomerContractToService/Attribute:service_id' => 'Služba',
    'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
    'Class:lnkCustomerContractToService/Attribute:service_name' => 'Název služby',
    'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
    'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
    'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
    'Class:lnkCustomerContractToService/Attribute:sla_name' => 'Název SLA',
    'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkProviderContractToService' => 'Spojení (Smlouva s poskytovatelem / Služba)',
    'Class:lnkProviderContractToService+' => '',
    'Class:lnkProviderContractToService/Attribute:service_id' => 'Služba',
    'Class:lnkProviderContractToService/Attribute:service_id+' => '',
    'Class:lnkProviderContractToService/Attribute:service_name' => 'Název služby',
    'Class:lnkProviderContractToService/Attribute:service_name+' => '',
    'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Smlouva s poskytovatelem',
    'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
    'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Název smlouvy s poskytovatelem',
    'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkFunctionalCIToService' => 'Spojení (Funkční konfigurační položka / Služba)',
    'Class:lnkFunctionalCIToService+' => '',
    'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Služba',
    'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
    'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Název služby',
    'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Konfigurační položka',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Název konfigurační položky',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:DeliveryModel' => 'Model poskytování služeb',
    'Class:DeliveryModel+' => '',
    'Class:DeliveryModel/Attribute:name' => 'Název',
    'Class:DeliveryModel/Attribute:name+' => '',
    'Class:DeliveryModel/Attribute:org_id' => 'Organizace',
    'Class:DeliveryModel/Attribute:org_id+' => '',
    'Class:DeliveryModel/Attribute:organization_name' => 'Název organizace',
    'Class:DeliveryModel/Attribute:organization_name+' => '',
    'Class:DeliveryModel/Attribute:description' => 'Popis',
    'Class:DeliveryModel/Attribute:description+' => '',
    'Class:DeliveryModel/Attribute:contacts_list' => 'Kontakty',
    'Class:DeliveryModel/Attribute:contacts_list+' => 'Všechny kontakty (Týmy a Osoby) pro tento model poskytování služeb',
    'Class:DeliveryModel/Attribute:customers_list' => 'Zákazníci',
    'Class:DeliveryModel/Attribute:customers_list+' => 'Všichni zákazníci využívající tento model poskytování služeb',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDeliveryModelToContact' => 'Spojení (Model poskytování služeb / Kontakt)',
    'Class:lnkDeliveryModelToContact+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Model poskytování služeb',
    'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Název modelu poskytování služeb',
    'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kontakt',
    'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Název kontaktu',
    'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Role',
    'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
    'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Název role',
    'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

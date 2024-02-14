<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ServiceManagement' => 'Gestione del Servizio',
	'Menu:ServiceManagement+' => 'Panoramica della Gestione del Servizio',
	'Menu:Service:Overview' => 'Panoramica',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratti per livello di servizio',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratti per stato',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratti che terminano in meno di 30 giorni',
	'Menu:ProviderContract' => 'Contratti con Provider',
	'Menu:ProviderContract+' => 'Contratti con Provider',
	'Menu:CustomerContract' => 'Contratti con Clienti',
	'Menu:CustomerContract+' => 'Contratti con Clienti',
	'Menu:ServiceSubcategory' => 'Sottocategorie di Servizio',
	'Menu:ServiceSubcategory+' => 'Sottocategorie di Servizio',
	'Menu:Service' => 'Servizi',
	'Menu:Service+' => 'Servizi',
	'Menu:ServiceElement' => 'Elementi di Servizio',
	'Menu:ServiceElement+' => 'Elementi di Servizio',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => 'Accordi di Livello di Servizio',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => 'Target di Livello di Servizio',
	'Menu:DeliveryModel' => 'Modelli di Delivery',
	'Menu:DeliveryModel+' => 'Modelli di Delivery',
	'Menu:ServiceFamily' => 'Famiglie di Servizi',
	'Menu:ServiceFamily+' => 'Famiglie di Servizi',
	'Contract:baseinfo' => 'Informazioni Generali',
	'Contract:moreinfo' => 'Informazioni Contrattuali',
	'Contract:cost' => 'Informazioni sui Costi',

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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Modello di Consegna',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nome del Modello di Consegna',
	'Class:Organization/Attribute:deliverymodel_name+' => '',

));



//
// Class: ContractType
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ContractType' => 'Tipo di Contratto',
	'Class:ContractType+' => '',
));


//
// Class: Contract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Contract' => 'Contratto',
	'Class:Contract+' => '~~',
	'Class:Contract/Attribute:name' => 'Nome',
	'Class:Contract/Attribute:name+' => '~~',
	'Class:Contract/Attribute:org_id' => 'Organizzazione',
	'Class:Contract/Attribute:org_id+' => '~~',
	'Class:Contract/Attribute:organization_name' => 'Nome dell\'organizzazione',
	'Class:Contract/Attribute:organization_name+' => 'Nome comune',
	'Class:Contract/Attribute:contacts_list' => 'Contatti',
	'Class:Contract/Attribute:contacts_list+' => 'Tutti i contatti per questo contratto cliente',
	'Class:Contract/Attribute:documents_list' => 'Documenti',
	'Class:Contract/Attribute:documents_list+' => 'Tutti i documenti per questo contratto cliente',
	'Class:Contract/Attribute:description' => 'Descrizione',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Data di inizio',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Data di fine',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Costo',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollari',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Tipo di contratto',
	'Class:Contract/Attribute:contracttype_id+' => '~~',
	'Class:Contract/Attribute:contracttype_name' => 'Nome del tipo di contratto',
	'Class:Contract/Attribute:contracttype_name+' => '~~',
	'Class:Contract/Attribute:billing_frequency' => 'Frequenza di fatturazione',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Costo unitario',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Provider',
	'Class:Contract/Attribute:provider_id+' => '~~',
	'Class:Contract/Attribute:provider_name' => 'Nome del provider',
	'Class:Contract/Attribute:provider_name+' => '~~',
	'Class:Contract/Attribute:status' => 'Stato',
	'Class:Contract/Attribute:status+' => '~~',
	'Class:Contract/Attribute:status/Value:implementation' => 'Implementazione',
	'Class:Contract/Attribute:status/Value:implementation+' => 'Implementazione',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Contract/Attribute:status/Value:production' => 'Produzione',
	'Class:Contract/Attribute:status/Value:production+' => 'Produzione',
	'Class:Contract/Attribute:finalclass' => 'Tipo',
	'Class:Contract/Attribute:finalclass+' => '',

));

//
// Class: CustomerContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CustomerContract' => 'Contratto con Cliente',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Servizi',
	'Class:CustomerContract/Attribute:services_list+' => 'Tutti i servizi acquistati per questo contratto',
	'Class:CustomerContract/Attribute:functionalcis_list' => 'CIs',
	'Class:CustomerContract/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione coperti da questo contratto',
	'Class:CustomerContract/Attribute:providercontracts_list' => 'Contratti con Provider',
	'Class:CustomerContract/Attribute:providercontracts_list+' => 'Tutti i contratti con i provider per la fornitura dei servizi di questo contratto (contratto sottostante)',

));

//
// Class: ProviderContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ProviderContract' => 'Contratto con Fornitore',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'Elementi di Configurazione',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione coperti da questo contratto',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Accordo di Livello di Servizio',
	'Class:ProviderContract/Attribute:coverage' => 'Copertura Oraria',
	'Class:ProviderContract/Attribute:coverage+' => 'Ore di Servizio',

));

//
// Class: lnkContactToContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContactToContract' => 'Collegamento Contatto / Contratto',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contratto',
	'Class:lnkContactToContract/Attribute:contract_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Nome Contratto',
	'Class:lnkContactToContract/Attribute:contract_name+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToContract/Attribute:contact_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Nome Contatto',
	'Class:lnkContactToContract/Attribute:contact_name+' => '~~',

));

//
// Class: lnkContractToDocument
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContractToDocument' => 'Collegamento Contratto / Documento',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Nome Contratto',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Nome Documento',
	'Class:lnkContractToDocument/Attribute:document_name+' => '~~',

));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Collegamento FunctionalCI / Contratto Fornitore',
	'Class:lnkFunctionalCIToProviderContract+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Contratto Fornitore',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Nome Contratto Fornitore',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',

));

//
// Class: ServiceFamily
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ServiceFamily' => 'Famiglia di Servizi',
	'Class:ServiceFamily+' => '~~',
	'Class:ServiceFamily/Attribute:name' => 'Nome',
	'Class:ServiceFamily/Attribute:name+' => '~~',
	'Class:ServiceFamily/Attribute:icon' => 'Icona',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:ServiceFamily/Attribute:services_list' => 'Servizi',
	'Class:ServiceFamily/Attribute:services_list+' => 'Tutti i servizi in questa categoria',

));

//
// Class: Service
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Service' => 'Servizio',
	'Class:Service+' => '',
	'Class:Service/ComplementaryName' => '%1$s - %2$s',
	'Class:Service/Attribute:name' => 'Nome',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Provider',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Nome del Provider',
	'Class:Service/Attribute:organization_name+' => '~~',
	'Class:Service/Attribute:description' => 'Descrizione',
	'Class:Service/Attribute:description+' => '~~',
	'Class:Service/Attribute:servicefamily_id' => 'Famiglia di Servizi',
	'Class:Service/Attribute:servicefamily_id+' => '~~',
	'Class:Service/Attribute:servicefamily_name' => 'Nome della Famiglia di Servizi',
	'Class:Service/Attribute:servicefamily_name+' => '~~',
	'Class:Service/Attribute:documents_list' => 'Documenti',
	'Class:Service/Attribute:documents_list+' => 'Tutti i documenti collegati al servizio',
	'Class:Service/Attribute:contacts_list' => 'Contatti',
	'Class:Service/Attribute:contacts_list+' => 'Tutti i contatti per questo servizio',
	'Class:Service/Attribute:status' => 'Stato',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'implementazione',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementazione',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Produzione',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icona',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:Service/Attribute:customercontracts_list' => 'Contratti con il cliente',
	'Class:Service/Attribute:customercontracts_list+' => 'Tutti i contratti con il cliente che hanno acquistato questo servizio',
	'Class:Service/Attribute:servicesubcategories_list' => 'Sottocategorie di servizio',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Tutte le sottocategorie per questo servizio',

));

//
// Class: lnkDocumentToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkDocumentToService' => 'Collegamento Documento / Servizio',
	'Class:lnkDocumentToService+' => '~~',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Servizio',
	'Class:lnkDocumentToService/Attribute:service_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkDocumentToService/Attribute:service_name+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Nome del Documento',
	'Class:lnkDocumentToService/Attribute:document_name+' => '~~',

));

//
// Class: lnkContactToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContactToService' => 'Collegamento Contatto / Servizio',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Name' => '%1$s / %2$s',
	'Class:lnkContactToService/Attribute:service_id' => 'Servizio',
	'Class:lnkContactToService/Attribute:service_id+' => '~~',
	'Class:lnkContactToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkContactToService/Attribute:service_name+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToService/Attribute:contact_id+' => '~~',
	'Class:lnkContactToService/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkContactToService/Attribute:contact_name+' => '~~',

));

//
// Class: ServiceSubcategory
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ServiceSubcategory' => 'Sottocategoria del Servizio',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s',
	'Class:ServiceSubcategory/Attribute:name' => 'Nome',
	'Class:ServiceSubcategory/Attribute:name+' => '~~',
	'Class:ServiceSubcategory/Attribute:description' => 'Descrizione',
	'Class:ServiceSubcategory/Attribute:description+' => '~~',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:service_id+' => '~~',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Nome del Servizio',
	'Class:ServiceSubcategory/Attribute:service_name+' => '~~',
	'Class:ServiceSubcategory/Attribute:status' => 'Stato',
	'Class:ServiceSubcategory/Attribute:status+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Implementazione',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Produzione',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:ServiceSubcategory/Attribute:request_type+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incidente',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Richiesta di Servizio',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '~~',
	'Class:ServiceSubcategory/Attribute:service_provider' => 'Provider del Servizio',
	'Class:ServiceSubcategory/Attribute:service_org_id' => 'Provider',

));

//
// Class: SLA
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '~~',
	'Class:SLA/Attribute:name' => 'Nome',
	'Class:SLA/Attribute:name+' => '~~',
	'Class:SLA/Attribute:description' => 'Descrizione',
	'Class:SLA/Attribute:description+' => '~~',
	'Class:SLA/Attribute:org_id' => 'Organizzazione',
	'Class:SLA/Attribute:org_id+' => '~~',
	'Class:SLA/Attribute:organization_name' => 'Nome Organizzazione',
	'Class:SLA/Attribute:organization_name+' => '~~',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => 'Tutti gli obiettivi di livello di servizio per questa SLA',
	'Class:SLA/Attribute:customercontracts_list' => 'Contratti con i clienti',
	'Class:SLA/Attribute:customercontracts_list+' => 'Tutti i contratti con i clienti che utilizzano questa SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Impossibile salvare il collegamento con il contratto del cliente %1$s e il servizio %2$s: SLA già esistente',

));

//
// Class: SLT
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '~~',
	'Class:SLT/Attribute:name' => 'Nome',
	'Class:SLT/Attribute:name+' => '~~',
	'Class:SLT/Attribute:priority' => 'Priorità',
	'Class:SLT/Attribute:priority+' => '~~',
	'Class:SLT/Attribute:priority/Value:1' => 'critica',
	'Class:SLT/Attribute:priority/Value:1+' => 'critica',
	'Class:SLT/Attribute:priority/Value:2' => 'alta',
	'Class:SLT/Attribute:priority/Value:2+' => 'alta',
	'Class:SLT/Attribute:priority/Value:3' => 'media',
	'Class:SLT/Attribute:priority/Value:3+' => 'media',
	'Class:SLT/Attribute:priority/Value:4' => 'bassa',
	'Class:SLT/Attribute:priority/Value:4+' => 'bassa',
	'Class:SLT/Attribute:request_type' => 'Tipo di richiesta',
	'Class:SLT/Attribute:request_type+' => '~~',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incidente',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incidente',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'richiesta di servizio',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'richiesta di servizio',
	'Class:SLT/Attribute:metric' => 'Metrica',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Valore',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Unità',
	'Class:SLT/Attribute:unit+' => '~~',
	'Class:SLT/Attribute:unit/Value:hours' => 'ore',
	'Class:SLT/Attribute:unit/Value:hours+' => 'ore',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minuti',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minuti',

));

//
// Class: lnkSLAToSLT
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkSLAToSLT' => 'Link SLA / SLT',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Nome SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Nome SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Metrica SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Tipo di richiesta SLT',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Priorità del ticket SLT',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Valore SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Unità di misura del valore SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '~~',

));

//
// Class: lnkCustomerContractToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkCustomerContractToService' => 'Collegamento Contratto Cliente / Servizio',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Contratto Cliente',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Nome Contratto Cliente',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Servizio',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Nome Servizio',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'Nome SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '~~',

));

//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkCustomerContractToProviderContract' => 'Collegamento Contratto Cliente / Contratto Provider',
	'Class:lnkCustomerContractToProviderContract+' => '~~',
	'Class:lnkCustomerContractToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id' => 'Contratto Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name' => 'Nome Contratto Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id' => 'Contratto Provider',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name' => 'Nome Contratto Provider',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name+' => '~~',

));

//
// Class: lnkCustomerContractToFunctionalCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkCustomerContractToFunctionalCI' => 'Collegamento Contratto Cliente / CI Funzionale',
	'Class:lnkCustomerContractToFunctionalCI+' => '~~',
	'Class:lnkCustomerContractToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id' => 'Contratto Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name' => 'Nome Contratto Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name+' => '~~',

));

//
// Class: DeliveryModel
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DeliveryModel' => 'Modello di Delivery',
	'Class:DeliveryModel+' => '~~',
	'Class:DeliveryModel/Attribute:name' => 'Nome',
	'Class:DeliveryModel/Attribute:name+' => '~~',
	'Class:DeliveryModel/Attribute:org_id' => 'Organizzazione',
	'Class:DeliveryModel/Attribute:org_id+' => '~~',
	'Class:DeliveryModel/Attribute:organization_name' => 'Nome dell\'organizzazione',
	'Class:DeliveryModel/Attribute:organization_name+' => '~~',
	'Class:DeliveryModel/Attribute:description' => 'Descrizione',
	'Class:DeliveryModel/Attribute:description+' => '~~',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contatti',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Tutti i contatti (team e persone) per questo modello di Consegna',
	'Class:DeliveryModel/Attribute:customers_list' => 'Clienti',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Tutti i clienti che hanno questo modello di Consegna',

));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkDeliveryModelToContact' => 'Collegamento Modello di Delivery / Contatto',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Modello di Delivery',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Nome del Modello di Delivery',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Ruolo',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Nome del Ruolo',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '~~',

));

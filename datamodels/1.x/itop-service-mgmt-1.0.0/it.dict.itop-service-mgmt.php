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


Dict::Add('IT IT', 'Italian' ,'Italiano' ,array(
'Menu:ServiceManagement' => 'Gestione del Servizio',
'Menu:ServiceManagement+' => 'Panoramica della Gestione del Servizio',
'Menu:Service:Overview' => 'Panoramica',
'Menu:Service:Overview+' => '',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratti per livello di servizio',
'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratti per stato',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratti che terminano in meno di 30 giorni',

'Menu:ServiceType' => 'Tipi di Servizio',
'Menu:ServiceType+' => 'Tipi di Servizio',
'Menu:ProviderContract' => 'Contratti con Provider',
'Menu:ProviderContract+' => 'Contratti con Provider',
'Menu:CustomerContract' => 'Contratti con Clienti',
'Menu:CustomerContract+' => 'Contratti con Clienti',
'Menu:ServiceSubcategory' => 'Sottocategorie di Servizio',
'Menu:ServiceSubcategory+' => 'Sottocategorie di Servizio',
'Menu:Service' => 'Servizi',
'Menu:Service+' => 'Servizi',
'Menu:SLA' => 'SLAs',
'Menu:SLA+' => 'Service Level Agreements',
'Menu:SLT' => 'SLTs',
'Menu:SLT+' => 'Service Level Targets',

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
// Class: Contract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Contract' => 'Contratto',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Nome',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:description' => 'Descrizione',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Data di inzio',
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
	'Class:Contract/Attribute:cost_unit' => 'Costo unitario',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Frequenza di fatturazione',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:contact_list' => 'Contatti',
	'Class:Contract/Attribute:contact_list+' => 'Contatti correlati al contratto',
	'Class:Contract/Attribute:document_list' => 'Documenti',
	'Class:Contract/Attribute:document_list+' => 'Documenti allegati al contratto',
	'Class:Contract/Attribute:ci_list' => 'CIs',
	'Class:Contract/Attribute:ci_list+' => 'CI supportate dal contratto',
	'Class:Contract/Attribute:finalclass' => 'Tipo',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:ProviderContract' => 'Contratto con Provider',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:provider_id' => 'Provider',
	'Class:ProviderContract/Attribute:provider_id+' => '',
	'Class:ProviderContract/Attribute:provider_name' => 'Nome del Provider',
	'Class:ProviderContract/Attribute:provider_name+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Ore di servizio',
	'Class:ProviderContract/Attribute:coverage+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('IT IT', 'Italian' ,'Italian', array(
	'Class:CustomerContract' => 'Contratto con cliente',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:org_id' => 'Cliente',
	'Class:CustomerContract/Attribute:org_id+' => '',
	'Class:CustomerContract/Attribute:org_name' => 'Nome Cliente',
	'Class:CustomerContract/Attribute:org_name+' => '',
	'Class:CustomerContract/Attribute:provider_id' => 'Provider',
	'Class:CustomerContract/Attribute:provider_id+' => '',
	'Class:CustomerContract/Attribute:provider_name' => 'Nome Provider',
	'Class:CustomerContract/Attribute:provider_name+' => '',
	'Class:CustomerContract/Attribute:support_team_id' => 'Team di supporto',
	'Class:CustomerContract/Attribute:support_team_id+' => '',
	'Class:CustomerContract/Attribute:support_team_name' => 'Team di supporto',
	'Class:CustomerContract/Attribute:support_team_name+' => '',
	'Class:CustomerContract/Attribute:provider_list' => 'Providers',
	'Class:CustomerContract/Attribute:provider_list+' => '',
	'Class:CustomerContract/Attribute:sla_list' => 'SLAs',
	'Class:CustomerContract/Attribute:sla_list+' => 'Lista delle SLA correlate con il contratto',
	'Class:CustomerContract/Attribute:provider_list' => 'Contratti di sostegno',
	'Class:CustomerContract/Attribute:sla_list+' => '',
));
//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('IT IT', 'Italian','Italiano', array(
	'Class:lnkCustomerContractToProviderContract' => 'lnkCustomerContractToProviderContract',
	'Class:lnkCustomerContractToProviderContract+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id' => 'Contratto con Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name' => 'Nome',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id' => 'Contratto con Provider',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name' => 'Nome',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla' => 'Provider SLA',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla+' => 'Service Level Agreement',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage' => 'Ore di Servizio',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage+' => '',
));


//
// Class: lnkContractToSLA
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContractToSLA' => 'Contratto/SLA',
	'Class:lnkContractToSLA+' => '',
	'Class:lnkContractToSLA/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToSLA/Attribute:contract_id+' => '',
	'Class:lnkContractToSLA/Attribute:contract_name' => 'Contratto',
	'Class:lnkContractToSLA/Attribute:contract_name+' => '',
	'Class:lnkContractToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_id+' => '',
	'Class:lnkContractToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_name+' => '',
	'Class:lnkContractToSLA/Attribute:coverage' => 'Ore di servizio',
	'Class:lnkContractToSLA/Attribute:coverage+' => '',
));

//
// Class: lnkContractToDoc
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContractToDoc' => 'Contratto/Doc',
	'Class:lnkContractToDoc+' => '',
	'Class:lnkContractToDoc/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToDoc/Attribute:contract_id+' => '',
	'Class:lnkContractToDoc/Attribute:contract_name' => 'Contratto',
	'Class:lnkContractToDoc/Attribute:contract_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDoc/Attribute:document_id+' => '',
	'Class:lnkContractToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkContractToDoc/Attribute:document_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_type' => 'Tipo di Documento',
	'Class:lnkContractToDoc/Attribute:document_type+' => '',
	'Class:lnkContractToDoc/Attribute:document_status' => 'Stato del documento',
	'Class:lnkContractToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkContractToContact
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkContractToContact' => 'Contratto/Contatto',
	'Class:lnkContractToContact+' => '',
	'Class:lnkContractToContact/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToContact/Attribute:contract_id+' => '',
	'Class:lnkContractToContact/Attribute:contract_name' => 'Contratto',
	'Class:lnkContractToContact/Attribute:contract_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkContractToContact/Attribute:contact_id+' => '',
	'Class:lnkContractToContact/Attribute:contact_name' => 'Contatto',
	'Class:lnkContractToContact/Attribute:contact_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_email' => 'Contatto email',
	'Class:lnkContractToContact/Attribute:contact_email+' => '',
	'Class:lnkContractToContact/Attribute:role' => 'Ruolo',
	'Class:lnkContractToContact/Attribute:role+' => '',
));

//
// Class: lnkContractToCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContractToCI' => 'Contratto/CI',
	'Class:lnkContractToCI+' => '',
	'Class:lnkContractToCI/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToCI/Attribute:contract_id+' => '',
	'Class:lnkContractToCI/Attribute:contract_name' => 'Contratto',
	'Class:lnkContractToCI/Attribute:contract_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_id' => 'CI',
	'Class:lnkContractToCI/Attribute:ci_id+' => '',
	'Class:lnkContractToCI/Attribute:ci_name' => 'CI',
	'Class:lnkContractToCI/Attribute:ci_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_status' => 'CI stato',
	'Class:lnkContractToCI/Attribute:ci_status+' => '',
));

//
// Class: Service
//

Dict::Add('IT IT', 'Italian','Italiano', array(
	'Class:Service' => 'Servizio',
	'Class:Service+' => '',
	'Class:Service/Attribute:org_id' => 'Provider',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:provider_name' => 'Provider',
	'Class:Service/Attribute:provider_name+' => '',
	'Class:Service/Attribute:name' => 'Nome',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:description' => 'Descrizione',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:type' => 'Tipo',
	'Class:Service/Attribute:type+' => '',
	'Class:Service/Attribute:type/Value:IncidentManagement' => 'Gestione Incidente',
	'Class:Service/Attribute:type/Value:IncidentManagement+' => 'Gestione Incidente',
	'Class:Service/Attribute:type/Value:RequestManagement' => 'Gestione Richieste',
	'Class:Service/Attribute:type/Value:RequestManagement+' => 'Gestione Richieste',
	'Class:Service/Attribute:status' => 'Stato',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:design' => 'Progettazione',
	'Class:Service/Attribute:status/Value:design+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Produzione',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:subcategory_list' => 'Sottocategorie di servizio',
	'Class:Service/Attribute:subcategory_list+' => '',
	'Class:Service/Attribute:sla_list' => 'SLAs',
	'Class:Service/Attribute:sla_list+' => '',
	'Class:Service/Attribute:document_list' => 'Documenti',
	'Class:Service/Attribute:document_list+' => 'Documenti allegati al servizio',
	'Class:Service/Attribute:contact_list' => 'Contatti',
	'Class:Service/Attribute:contact_list+' => 'Contatti che hanno un ruolo per questo servizio',
	'Class:Service/Tab:Related_Contracts' => 'Contratti correlati',
	'Class:Service/Tab:Related_Contracts+' => 'Contratti siglati per questo servizio',
));

//
// Class: ServiceSubcategory
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ServiceSubcategory' => 'Sottocategorie del servizio',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Nome',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Descrizione',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
));

//
// Class: SLA
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Name',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:service_id' => 'Servizio',
	'Class:SLA/Attribute:service_id+' => '',
	'Class:SLA/Attribute:service_name' => 'Servizio',
	'Class:SLA/Attribute:service_name+' => '',
	'Class:SLA/Attribute:slt_list' => 'SLTs',
	'Class:SLA/Attribute:slt_list+' => 'Lista delle soglie dei libelli di servizio',
));

//
// Class: SLT
//

Dict::Add('IT IT', 'Italian' ,'Italiano', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Nome',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:metric' => 'Metrica',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:TTO' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTO+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTR' => 'TTR',
	'Class:SLT/Attribute:metric/Value:TTR+' => 'TTR',
	'Class:SLT/Attribute:ticket_priority' => 'Priorità del ticket',
	'Class:SLT/Attribute:ticket_priority+' => '',
	'Class:SLT/Attribute:ticket_priority/Value:1' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:1+' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:2' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:2+' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:3' => '3',
	'Class:SLT/Attribute:ticket_priority/Value:3+' => '3',
	'Class:SLT/Attribute:value' => 'Valore',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:value_unit' => 'Unità',
	'Class:SLT/Attribute:value_unit+' => '',
	'Class:SLT/Attribute:value_unit/Value:days' => 'giorni',
	'Class:SLT/Attribute:value_unit/Value:days+' => 'giorni',
	'Class:SLT/Attribute:value_unit/Value:hours' => 'ore',
	'Class:SLT/Attribute:value_unit/Value:hours+' => 'ore',
	'Class:SLT/Attribute:value_unit/Value:minutes' => 'minuti',
	'Class:SLT/Attribute:value_unit/Value:minutes+' => 'minuti',
	'Class:SLT/Attribute:sla_list' => 'SLAs',
	'Class:SLT/Attribute:sla_list+' => 'SLAs che usano i SLT',
));

//
// Class: lnkSLTToSLA
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkSLTToSLA' => 'SLT/SLA',
	'Class:lnkSLTToSLA+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_id+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_id' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_id+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_name' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_metric' => 'Metrica',
	'Class:lnkSLTToSLA/Attribute:slt_metric+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority' => 'Priorità del ticket',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value' => 'Valore',
	'Class:lnkSLTToSLA/Attribute:slt_value+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit' => 'Unità',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkServiceToDoc
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkServiceToDoc' => 'Servizio/Doc',
	'Class:lnkServiceToDoc+' => '',
	'Class:lnkServiceToDoc/Attribute:service_id' => 'Servizio',
	'Class:lnkServiceToDoc/Attribute:service_id+' => '',
	'Class:lnkServiceToDoc/Attribute:service_name' => 'Servizio',
	'Class:lnkServiceToDoc/Attribute:service_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkServiceToDoc/Attribute:document_id+' => '',
	'Class:lnkServiceToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkServiceToDoc/Attribute:document_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_type' => 'Tipo documento',
	'Class:lnkServiceToDoc/Attribute:document_type+' => '',
	'Class:lnkServiceToDoc/Attribute:document_status' => 'Stato del documento',
	'Class:lnkServiceToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkServiceToContact
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkServiceToContact' => 'Service/Contatto',
	'Class:lnkServiceToContact+' => '',
	'Class:lnkServiceToContact/Attribute:service_id' => 'Servizio',
	'Class:lnkServiceToContact/Attribute:service_id+' => '',
	'Class:lnkServiceToContact/Attribute:service_name' => 'Servizio',
	'Class:lnkServiceToContact/Attribute:service_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkServiceToContact/Attribute:contact_id+' => '',
	'Class:lnkServiceToContact/Attribute:contact_name' => 'Contatto',
	'Class:lnkServiceToContact/Attribute:contact_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_email' => 'Email del contatto',
	'Class:lnkServiceToContact/Attribute:contact_email+' => '',
	'Class:lnkServiceToContact/Attribute:role' => 'Ruolo',
	'Class:lnkServiceToContact/Attribute:role+' => '',
));

//
// Class: lnkServiceToCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkServiceToCI' => 'Servizio/CI',
	'Class:lnkServiceToCI+' => '',
	'Class:lnkServiceToCI/Attribute:service_id' => 'Servizio',
	'Class:lnkServiceToCI/Attribute:service_id+' => '',
	'Class:lnkServiceToCI/Attribute:service_name' => 'Servizio',
	'Class:lnkServiceToCI/Attribute:service_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_id' => 'CI',
	'Class:lnkServiceToCI/Attribute:ci_id+' => '',
	'Class:lnkServiceToCI/Attribute:ci_name' => 'CI',
	'Class:lnkServiceToCI/Attribute:ci_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_status' => 'CI stato',
	'Class:lnkServiceToCI/Attribute:ci_status+' => '',
));


?>

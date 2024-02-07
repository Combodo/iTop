<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ServiceManagement' => 'Gestione del servizio',
	'Menu:ServiceManagement+' => 'Panoramica della gestione del servizio',
	'Menu:Service:Overview' => 'Panoramica',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratti per livello di servizio',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratti per stato',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratti che terminano in meno di 30 giorni',
	'Menu:ProviderContract' => 'Contratti con provider',
	'Menu:ProviderContract+' => 'Contratti con provider',
	'Menu:CustomerContract' => 'Contratti con clienti',
	'Menu:CustomerContract+' => 'Contratti con clienti',
	'Menu:ServiceSubcategory' => 'Sottocategorie di servizio',
	'Menu:ServiceSubcategory+' => 'Sottocategorie di servizio',
	'Menu:Service' => 'Servizi',
	'Menu:Service+' => 'Servizi',
	'Menu:ServiceElement' => 'Elementi del Servizio',
	'Menu:ServiceElement+' => 'Elementi del Servizio',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => 'Accordi di Livello di Servizio',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => 'Obiettivi di Livello di Servizio',
	'Menu:DeliveryModel' => 'Modelli di Consegna',
	'Menu:DeliveryModel+' => 'Modelli di Consegna',
	'Menu:ServiceFamily' => 'Famiglie di Servizi',
	'Menu:ServiceFamily+' => 'Famiglie di Servizi',
	'Menu:Procedure' => 'Catalogo delle Procedure',
	'Menu:Procedure+' => 'Tutti i cataloghi delle procedure',
	'Contract:baseinfo' => 'Informazioni Generali',
	'Contract:moreinfo' => 'Informazioni Contrattuali',
	'Contract:cost' => 'Informazioni sui Costi',
));

//
// Class: Organization
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Modello di Consegna',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nome del Modello di Consegna',
));


//
// Class: ContractType
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ContractType' => 'Tipo di Contratto',
	'Class:ContractType+' => '~~',
));

//
// Class: Contract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Contract' => 'Contratto',
	'Class:Contract/Attribute:name' => 'Nome',
	'Class:Contract/Attribute:org_id' => 'Cliente',
	'Class:Contract/Attribute:organization_name' => 'Nome del Cliente',
	'Class:Contract/Attribute:contacts_list' => 'Contatti',
	'Class:Contract/Attribute:contacts_list+' => 'Tutti i contatti per questo contratto cliente',
	'Class:Contract/Attribute:documents_list' => 'Documenti',
	'Class:Contract/Attribute:documents_list+' => 'Tutti i documenti per questo contratto cliente',
	'Class:Contract/Attribute:description' => 'Descrizione',
	'Class:Contract/Attribute:start_date' => 'Data di inizio',
	'Class:Contract/Attribute:end_date' => 'Data di fine',
	'Class:Contract/Attribute:cost' => 'Costo',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollari',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:contracttype_id' => 'Tipo di Contratto',
	'Class:Contract/Attribute:contracttype_name' => 'Nome Tipo di Contratto',
	'Class:Contract/Attribute:billing_frequency' => 'Frequenza di fatturazione',
	'Class:Contract/Attribute:cost_unit' => 'Costo unitario',
	'Class:Contract/Attribute:provider_id' => 'Fornitore',
	'Class:Contract/Attribute:provider_name' => 'Nome del Fornitore',
	'Class:Contract/Attribute:status' => 'Stato',
	'Class:Contract/Attribute:status/Value:implementation' => 'implementazione',
	'Class:Contract/Attribute:status/Value:obsolete' => 'obsoleto',
	'Class:Contract/Attribute:status/Value:production' => 'produzione',
	'Class:Contract/Attribute:finalclass' => 'Tipo',
));
//
// Class: CustomerContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CustomerContract' => 'Contratto con cliente',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Servizi',
	'Class:CustomerContract/Attribute:services_list+' => 'Tutti i servizi acquistati per questo contratto',
));

//
// Class: ProviderContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ProviderContract' => 'Contratto con Provider',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CI',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione coperti da questo contratto con il provider',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Accordo di Livello di Servizio',
	'Class:ProviderContract/Attribute:coverage' => 'Ore di servizio',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Tipo di Contratto',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Nome del Tipo di Contratto',

));

//
// Class: lnkContactToContract
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContactToContract' => 'Link Contact / Contract',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contratto',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Nome del Contratto',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Nome del Contatto',

));

//
// Class: lnkContractToDocument
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContractToDocument' => 'Link Contract / Document',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Nome del Contratto',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Nome del Documento',
));

//
// Class: ServiceFamily
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ServiceFamily' => 'Famiglia di Servizi',
	'Class:ServiceFamily/Attribute:name' => 'Nome',
	'Class:ServiceFamily/Attribute:icon' => 'Icona',
	'Class:ServiceFamily/Attribute:services_list' => 'Servizi',
	'Class:ServiceFamily/Attribute:services_list+' => 'Tutti i servizi in questa categoria',

));

//
// Class: Service
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Service' => 'Servizio',
	'Class:Service/ComplementaryName' => '%1$s - %2$s',
	'Class:Service/Attribute:name' => 'Nome',
	'Class:Service/Attribute:org_id' => 'Fornitore',
	'Class:Service/Attribute:organization_name' => 'Nome del Fornitore',
	'Class:Service/Attribute:servicefamily_id' => 'Famiglia di Servizi',
	'Class:Service/Attribute:servicefamily_name' => 'Nome della Famiglia di Servizi',
	'Class:Service/Attribute:description' => 'Descrizione',
	'Class:Service/Attribute:documents_list' => 'Documenti',
	'Class:Service/Attribute:documents_list+' => 'Tutti i documenti collegati al servizio',
	'Class:Service/Attribute:contacts_list' => 'Contatti',
	'Class:Service/Attribute:contacts_list+' => 'Tutti i contatti per questo servizio',
	'Class:Service/Attribute:status' => 'Stato',
	'Class:Service/Attribute:status/Value:implementation' => 'implementazione',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:production' => 'Produzione',
	'Class:Service/Attribute:icon' => 'Icona',
	'Class:Service/Attribute:customercontracts_list' => 'Contratti Cliente',
	'Class:Service/Attribute:customercontracts_list+' => 'Tutti i contratti cliente che hanno acquistato questo servizio',
	'Class:Service/Attribute:providercontracts_list' => 'Contratti Fornitore',
	'Class:Service/Attribute:providercontracts_list+' => 'Tutti i contratti fornitore per supportare questo servizio',
	'Class:Service/Attribute:functionalcis_list' => 'Dipende da CI',
	'Class:Service/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione utilizzati per fornire questo servizio',
	'Class:Service/Attribute:servicesubcategories_list' => 'Sotto-categorie di Servizio',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Tutte le sotto-categorie per questo servizio',
));

//
// Class: lnkDocumentToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkDocumentToService' => 'Link Document / Service',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Servizio',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Nome del Documento',
));

//
// Class: lnkContactToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContactToService' => 'Link Contact / Service',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Name' => '%1$s / %2$s',
	'Class:lnkContactToService/Attribute:service_id' => 'Servizio',
	'Class:lnkContactToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToService/Attribute:contact_name' => 'Nome del Contatto',
));

//
// Class: ServiceSubcategory
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ServiceSubcategory' => 'Sottocategorie del servizio',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s',
	'Class:ServiceSubcategory/Attribute:name' => 'Nome',
	'Class:ServiceSubcategory/Attribute:description' => 'Descrizione',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incidente',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'richiesta di servizio',
	'Class:ServiceSubcategory/Attribute:status' => 'Stato',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementazione',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'obsoleto',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'produzione',
));

//
// Class: SLA
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Nome',
	'Class:SLA/Attribute:description' => 'Descrizione',
	'Class:SLA/Attribute:org_id' => 'Fornitore',
	'Class:SLA/Attribute:organization_name' => 'Nome del Fornitore',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => 'Tutti gli obiettivi di livello di servizio per questo SLA',
	'Class:SLA/Attribute:customercontracts_list' => 'Contratti Cliente',
	'Class:SLA/Attribute:customercontracts_list+' => 'Tutti i contratti cliente che utilizzano questo SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Impossibile salvare il collegamento con il contratto cliente %1$s e il servizio %2$s: SLA già esistente',
));

//
// Class: SLT
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SLT' => 'SLT',
	'Class:SLT/Attribute:name' => 'Nome',
	'Class:SLT/Attribute:priority' => 'Priorità',
	'Class:SLT/Attribute:priority/Value:1' => 'critico',
	'Class:SLT/Attribute:priority/Value:2' => 'alto',
	'Class:SLT/Attribute:priority/Value:3' => 'medio',
	'Class:SLT/Attribute:priority/Value:4' => 'basso',
	'Class:SLT/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incidente',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'richiesta di servizio',
	'Class:SLT/Attribute:metric' => 'Metrica',
	'Class:SLT/Attribute:value' => 'Valore',
	'Class:SLT/Attribute:unit' => 'Unità',
	'Class:SLT/Attribute:unit/Value:hours' => 'ore',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minuti',
	'Class:SLT/Attribute:slas_list' => 'SLA',
	'Class:SLT/Attribute:slas_list+' => 'Tutti gli accordi di livello di servizio che utilizzano questo SLT',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkSLAToSLT' => 'Link SLA / SLT',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Nome SLA',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Nome SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Metrica SLT',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Tipo di richiesta SLT',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Priorità ticket SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Valore SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Unità di valore SLT',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkCustomerContractToService' => 'Link Customer Contract / Service',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Nome SLA',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Nome SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Metrica SLT',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Tipo di richiesta SLT',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Priorità ticket SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Valore SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Unità di valore SLT',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkProviderContractToService' => 'Link Provider Contract / Service',
	'Class:lnkProviderContractToService+' => '~~',
	'Class:lnkProviderContractToService/Name' => '%1$s / %2$s',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Servizio',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Contratto con Fornitore',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Nome del Contratto con Fornitore',
));

//
// Class: DeliveryModel
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DeliveryModel' => 'Modello di Consegna',
	'Class:DeliveryModel/Attribute:name' => 'Nome',
	'Class:DeliveryModel/Attribute:org_id' => 'Organizzazione',
	'Class:DeliveryModel/Attribute:organization_name' => 'Nome dell\'Organizzazione',
	'Class:DeliveryModel/Attribute:description' => 'Descrizione',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contatti',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Tutti i contatti (Team e Persone) per questo modello di consegna',
	'Class:DeliveryModel/Attribute:customers_list' => 'Clienti',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Tutti i clienti che utilizzano questo modello di consegna',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkDeliveryModelToContact' => 'Link Delivery Model / Contact',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Modello di Consegna',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Nome del Modello di Consegna',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Ruolo',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Nome del Ruolo',
));

<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:Contract' => 'Contratto',
	'Class:Contract/Attribute:billing_frequency' => 'Frequenza di fatturazione',
	'Class:Contract/Attribute:contacts_list' => 'Contatti',
	'Class:Contract/Attribute:contacts_list+' => 'Tutti i contatti per questo contratto cliente',
	'Class:Contract/Attribute:contracttype_id' => 'Tipo di Contratto',
	'Class:Contract/Attribute:contracttype_name' => 'Nome Tipo di Contratto',
	'Class:Contract/Attribute:cost' => 'Costo',
	'Class:Contract/Attribute:cost_currency' => 'Valuta',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollari',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:cost_unit' => 'Costo unitario',
	'Class:Contract/Attribute:description' => 'Descrizione',
	'Class:Contract/Attribute:documents_list' => 'Documenti',
	'Class:Contract/Attribute:documents_list+' => 'Tutti i documenti per questo contratto cliente',
	'Class:Contract/Attribute:end_date' => 'Data di fine',
	'Class:Contract/Attribute:finalclass' => 'Tipo',
	'Class:Contract/Attribute:name' => 'Nome',
	'Class:Contract/Attribute:org_id' => 'Cliente',
	'Class:Contract/Attribute:organization_name' => 'Nome del Cliente',
	'Class:Contract/Attribute:provider_id' => 'Fornitore',
	'Class:Contract/Attribute:provider_name' => 'Nome del Fornitore',
	'Class:Contract/Attribute:start_date' => 'Data di inizio',
	'Class:Contract/Attribute:status' => 'Stato',
	'Class:Contract/Attribute:status/Value:implementation' => 'implementazione',
	'Class:Contract/Attribute:status/Value:obsolete' => 'obsoleto',
	'Class:Contract/Attribute:status/Value:production' => 'produzione',
	'Class:ContractType' => 'Tipo di Contratto',
	'Class:ContractType+' => '~~',
	'Class:CustomerContract' => 'Contratto con cliente',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Servizi',
	'Class:CustomerContract/Attribute:services_list+' => 'Tutti i servizi acquistati per questo contratto',
	'Class:DeliveryModel' => 'Modello di Consegna',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contatti',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Tutti i contatti (Team e Persone) per questo modello di consegna',
	'Class:DeliveryModel/Attribute:customers_list' => 'Clienti',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Tutti i clienti che utilizzano questo modello di consegna',
	'Class:DeliveryModel/Attribute:description' => 'Descrizione',
	'Class:DeliveryModel/Attribute:name' => 'Nome',
	'Class:DeliveryModel/Attribute:org_id' => 'Organizzazione',
	'Class:DeliveryModel/Attribute:organization_name' => 'Nome dell\'Organizzazione',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modello di Consegna',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nome del Modello di Consegna',
	'Class:ProviderContract' => 'Contratto con Provider',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Tipo di Contratto',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Nome del Tipo di Contratto',
	'Class:ProviderContract/Attribute:coverage' => 'Ore di servizio',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CI',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione coperti da questo contratto con il provider',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Accordo di Livello di Servizio',
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:customercontracts_list' => 'Contratti Cliente',
	'Class:SLA/Attribute:customercontracts_list+' => 'Tutti i contratti cliente che utilizzano questo SLA',
	'Class:SLA/Attribute:description' => 'Descrizione',
	'Class:SLA/Attribute:name' => 'Nome',
	'Class:SLA/Attribute:org_id' => 'Fornitore',
	'Class:SLA/Attribute:organization_name' => 'Nome del Fornitore',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => 'Tutti gli obiettivi di livello di servizio per questo SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Impossibile salvare il collegamento con il contratto cliente %1$s e il servizio %2$s: SLA già esistente',
	'Class:SLT' => 'SLT',
	'Class:SLT/Attribute:metric' => 'Metrica',
	'Class:SLT/Attribute:name' => 'Nome',
	'Class:SLT/Attribute:priority' => 'Priorità',
	'Class:SLT/Attribute:priority/Value:1' => 'critico',
	'Class:SLT/Attribute:priority/Value:2' => 'alto',
	'Class:SLT/Attribute:priority/Value:3' => 'medio',
	'Class:SLT/Attribute:priority/Value:4' => 'basso',
	'Class:SLT/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incidente',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'richiesta di servizio',
	'Class:SLT/Attribute:slas_list' => 'SLA',
	'Class:SLT/Attribute:slas_list+' => 'Tutti gli accordi di livello di servizio che utilizzano questo SLT',
	'Class:SLT/Attribute:unit' => 'Unità',
	'Class:SLT/Attribute:unit/Value:hours' => 'ore',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minuti',
	'Class:SLT/Attribute:value' => 'Valore',
	'Class:Service' => 'Servizio',
	'Class:Service/Attribute:contacts_list' => 'Contatti',
	'Class:Service/Attribute:contacts_list+' => 'Tutti i contatti per questo servizio',
	'Class:Service/Attribute:customercontracts_list' => 'Contratti Cliente',
	'Class:Service/Attribute:customercontracts_list+' => 'Tutti i contratti cliente che hanno acquistato questo servizio',
	'Class:Service/Attribute:description' => 'Descrizione',
	'Class:Service/Attribute:documents_list' => 'Documenti',
	'Class:Service/Attribute:documents_list+' => 'Tutti i documenti collegati al servizio',
	'Class:Service/Attribute:functionalcis_list' => 'Dipende da CI',
	'Class:Service/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione utilizzati per fornire questo servizio',
	'Class:Service/Attribute:icon' => 'Icona',
	'Class:Service/Attribute:name' => 'Nome',
	'Class:Service/Attribute:org_id' => 'Fornitore',
	'Class:Service/Attribute:organization_name' => 'Nome del Fornitore',
	'Class:Service/Attribute:providercontracts_list' => 'Contratti Fornitore',
	'Class:Service/Attribute:providercontracts_list+' => 'Tutti i contratti fornitore per supportare questo servizio',
	'Class:Service/Attribute:servicefamily_id' => 'Famiglia di Servizi',
	'Class:Service/Attribute:servicefamily_name' => 'Nome della Famiglia di Servizi',
	'Class:Service/Attribute:servicesubcategories_list' => 'Sotto-categorie di Servizio',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Tutte le sotto-categorie per questo servizio',
	'Class:Service/Attribute:status' => 'Stato',
	'Class:Service/Attribute:status/Value:implementation' => 'implementazione',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:production' => 'Produzione',
	'Class:Service/ComplementaryName' => '%1$s - %2$s',
	'Class:ServiceFamily' => 'Famiglia di Servizi',
	'Class:ServiceFamily/Attribute:icon' => 'Icona',
	'Class:ServiceFamily/Attribute:name' => 'Nome',
	'Class:ServiceFamily/Attribute:services_list' => 'Servizi',
	'Class:ServiceFamily/Attribute:services_list+' => 'Tutti i servizi in questa categoria',
	'Class:ServiceSubcategory' => 'Sottocategorie del servizio',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Descrizione',
	'Class:ServiceSubcategory/Attribute:name' => 'Nome',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incidente',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'richiesta di servizio',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Servizio',
	'Class:ServiceSubcategory/Attribute:status' => 'Stato',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementazione',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'obsoleto',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'produzione',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s',
	'Class:lnkContactToContract' => 'Link Contact / Contract',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contratto',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Nome del Contratto',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s',
	'Class:lnkContactToService' => 'Link Contact / Service',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contatto',
	'Class:lnkContactToService/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkContactToService/Attribute:service_id' => 'Servizio',
	'Class:lnkContactToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkContactToService/Name' => '%1$s / %2$s',
	'Class:lnkContractToDocument' => 'Link Contract / Document',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contratto',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Nome del Contratto',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Nome del Documento',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToService' => 'Link Customer Contract / Service',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Customer contract~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Customer contract Name~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service~~',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Service Name~~',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Name~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s',
	'Class:lnkDeliveryModelToContact' => 'Link Delivery Model / Contact',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Nome del Contatto',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Modello di Consegna',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Nome del Modello di Consegna',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Ruolo',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Nome del Ruolo',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToService' => 'Link Document / Service',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Nome del Documento',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Servizio',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkProviderContractToService' => 'Link Provider Contract / Service',
	'Class:lnkProviderContractToService+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Contratto con Fornitore',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Nome del Contratto con Fornitore',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Servizio',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Nome del Servizio',
	'Class:lnkProviderContractToService/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT' => 'Link SLA / SLT',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Nome SLA',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Metrica SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Nome SLT',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Tipo di richiesta SLT',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Priorità ticket SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Valore SLT',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Unità di valore SLT',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s',
	'Contract:baseinfo' => 'Informazioni Generali',
	'Contract:cost' => 'Informazioni sui Costi',
	'Contract:moreinfo' => 'Informazioni Contrattuali',
	'Menu:CustomerContract' => 'Contratti con clienti',
	'Menu:CustomerContract+' => 'Contratti con clienti',
	'Menu:DeliveryModel' => 'Modelli di Consegna',
	'Menu:DeliveryModel+' => 'Modelli di Consegna',
	'Menu:Procedure' => 'Catalogo delle Procedure',
	'Menu:Procedure+' => 'Tutti i cataloghi delle procedure',
	'Menu:ProviderContract' => 'Contratti con provider',
	'Menu:ProviderContract+' => 'Contratti con provider',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => 'Accordi di Livello di Servizio',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => 'Obiettivi di Livello di Servizio',
	'Menu:Service' => 'Servizi',
	'Menu:Service+' => 'Servizi',
	'Menu:Service:Overview' => 'Panoramica',
	'Menu:Service:Overview+' => '',
	'Menu:ServiceElement' => 'Elementi del Servizio',
	'Menu:ServiceElement+' => 'Elementi del Servizio',
	'Menu:ServiceFamily' => 'Famiglie di Servizi',
	'Menu:ServiceFamily+' => 'Famiglie di Servizi',
	'Menu:ServiceManagement' => 'Gestione del servizio',
	'Menu:ServiceManagement+' => 'Panoramica della gestione del servizio',
	'Menu:ServiceSubcategory' => 'Sottocategorie di servizio',
	'Menu:ServiceSubcategory+' => 'Sottocategorie di servizio',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratti per livello di servizio',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratti per stato',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratti che terminano in meno di 30 giorni',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Contract+' => '~~',
	'Class:Contract/Attribute:name+' => '~~',
	'Class:Contract/Attribute:org_id+' => '~~',
	'Class:Contract/Attribute:organization_name+' => 'Common name~~',
	'Class:Contract/Attribute:description+' => '~~',
	'Class:Contract/Attribute:start_date+' => '~~',
	'Class:Contract/Attribute:end_date+' => '~~',
	'Class:Contract/Attribute:cost+' => '~~',
	'Class:Contract/Attribute:cost_currency+' => '~~',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '~~',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '~~',
	'Class:Contract/Attribute:contracttype_id+' => '~~',
	'Class:Contract/Attribute:contracttype_name+' => '~~',
	'Class:Contract/Attribute:billing_frequency+' => '~~',
	'Class:Contract/Attribute:cost_unit+' => '~~',
	'Class:Contract/Attribute:provider_id+' => '~~',
	'Class:Contract/Attribute:provider_name+' => 'Common name~~',
	'Class:Contract/Attribute:status+' => '~~',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:Contract/Attribute:status/Value:production+' => 'production~~',
	'Class:Contract/Attribute:finalclass+' => 'Name of the final class~~',
	'Class:ProviderContract+' => '~~',
	'Class:ProviderContract/Attribute:coverage+' => '~~',
	'Class:ProviderContract/Attribute:contracttype_id+' => '~~',
	'Class:ProviderContract/Attribute:contracttype_name+' => '~~',
	'Class:ProviderContract/Attribute:services_list' => 'Services~~',
	'Class:ProviderContract/Attribute:services_list+' => 'All the services purchased with this contract~~',
	'Class:lnkContactToContract/Attribute:contract_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_name+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_name+' => '~~',
	'Class:ServiceFamily+' => '~~',
	'Class:ServiceFamily/Attribute:name+' => '~~',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:Service+' => '~~',
	'Class:Service/Attribute:name+' => '~~',
	'Class:Service/Attribute:org_id+' => '~~',
	'Class:Service/Attribute:organization_name+' => '~~',
	'Class:Service/Attribute:servicefamily_id+' => '~~',
	'Class:Service/Attribute:servicefamily_name+' => '~~',
	'Class:Service/Attribute:description+' => '~~',
	'Class:Service/Attribute:status+' => '~~',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Service/Attribute:status/Value:obsolete+' => '~~',
	'Class:Service/Attribute:status/Value:production+' => '~~',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:lnkDocumentToService+' => '~~',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToService/Attribute:service_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_name+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_name+' => '~~',
	'Class:lnkContactToService/Attribute:service_id+' => '~~',
	'Class:lnkContactToService/Attribute:service_name+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id+' => '~~',
	'Class:lnkContactToService/Attribute:contact_name+' => '~~',
	'Class:ServiceSubcategory/Attribute:name+' => '~~',
	'Class:ServiceSubcategory/Attribute:description+' => '~~',
	'Class:ServiceSubcategory/Attribute:service_id+' => '~~',
	'Class:ServiceSubcategory/Attribute:service_name+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:ServiceSubcategory/Attribute:status+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production~~',
	'Class:SLA/Attribute:name+' => '~~',
	'Class:SLA/Attribute:description+' => '~~',
	'Class:SLA/Attribute:org_id+' => '~~',
	'Class:SLA/Attribute:organization_name+' => 'Common name~~',
	'Class:SLT+' => '~~',
	'Class:SLT/Attribute:name+' => '~~',
	'Class:SLT/Attribute:priority+' => '~~',
	'Class:SLT/Attribute:priority/Value:1+' => 'critical~~',
	'Class:SLT/Attribute:priority/Value:2+' => 'high~~',
	'Class:SLT/Attribute:priority/Value:3+' => 'medium~~',
	'Class:SLT/Attribute:priority/Value:4+' => 'low~~',
	'Class:SLT/Attribute:request_type+' => '~~',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:SLT/Attribute:metric+' => '~~',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO~~',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO~~',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR~~',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR~~',
	'Class:SLT/Attribute:value+' => '~~',
	'Class:SLT/Attribute:unit+' => '~~',
	'Class:SLT/Attribute:unit/Value:hours+' => 'hours~~',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minutes~~',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '~~',
	'Class:DeliveryModel+' => '~~',
	'Class:DeliveryModel/Attribute:name+' => '~~',
	'Class:DeliveryModel/Attribute:org_id+' => '~~',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Common name~~',
	'Class:DeliveryModel/Attribute:description+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '~~',
]);

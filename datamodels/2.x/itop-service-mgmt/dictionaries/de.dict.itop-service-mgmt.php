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
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2021 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:ServiceManagement' => 'Service Management',
	'Menu:ServiceManagement+' => 'Service Management-Übersicht',
	'Menu:Service:Overview' => 'Übersicht',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Verträge nach Service Level',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Verträge nach Status',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Verträge, die in weniger als 30 Tagen enden',
	'Menu:ProviderContract' => 'Provider-Verträge',
	'Menu:ProviderContract+' => 'Provider-Verträge',
	'Menu:CustomerContract' => 'Kundenverträge',
	'Menu:CustomerContract+' => 'Kundenverträge',
	'Menu:ServiceSubcategory' => 'Service-Unterkategorien',
	'Menu:ServiceSubcategory+' => 'Service-Unterkategorien',
	'Menu:Service' => 'Services',
	'Menu:Service+' => 'Services',
	'Menu:ServiceElement' => 'Service-Elemente',
	'Menu:ServiceElement+' => '',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Service Level Agreements',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Service Level Targets',
	'Menu:DeliveryModel' => 'Delivery-Modelle',
	'Menu:DeliveryModel+' => '',
	'Menu:ServiceFamily' => 'Service-Familien',
	'Menu:ServiceFamily+' => '',
	'Menu:Procedure' => 'Verfahrens-Katalog',
	'Menu:Procedure+' => '',
));

//
// Class: Organization
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery-Modell',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery-Modell-Name',
));


//
// Class: ContractType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ContractType' => 'Vertrags-Typ',
	'Class:ContractType+' => '',
));

//
// Class: Contract
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Contract' => 'Vertrag',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Name',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Kunde',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Kunden-Name',
	'Class:Contract/Attribute:organization_name+' => '',
	'Class:Contract/Attribute:contacts_list' => 'Kontakte',
	'Class:Contract/Attribute:contacts_list+' => '',
	'Class:Contract/Attribute:documents_list' => 'Dokumente',
	'Class:Contract/Attribute:documents_list+' => '',
	'Class:Contract/Attribute:description' => 'Beschreibung',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Anfangsdatum',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Ablaufdatum',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Kosten',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Währung',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollar',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euro',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Vertragstyp',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Vertragstyp-Name',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Abrechnungshäufigkeit',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Kosteneinheit',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Provider',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Provider-Name',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Status',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'Implementierung',
	'Class:Contract/Attribute:status/Value:implementation+' => '',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:Contract/Attribute:status/Value:obsolete+' => '',
	'Class:Contract/Attribute:status/Value:production' => 'Produktion',
	'Class:Contract/Attribute:status/Value:production+' => '',
	'Class:Contract/Attribute:finalclass' => 'Typ',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CustomerContract' => 'Kundenvertrag',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Services',
	'Class:CustomerContract/Attribute:services_list+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ProviderContract' => 'Provider-Vertrag',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Abdeckung',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Vertragstyp',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Vertragstyp-Name',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContactToContract' => 'Verknüpfung Kontakt/Vertrag',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Vertrag',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Vertrags-Name',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Kontakt-Name',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContractToDocument' => 'Verknüpfung Vertrag/Dokument',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Vertrag',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Vertrags-Name',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Dokument',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Dokument-Name',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ServiceFamily' => 'Service-Familie',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Name',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Icon',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Services',
	'Class:ServiceFamily/Attribute:services_list+' => '',
));

//
// Class: Service
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Service' => 'Service',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Name',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Anbieter',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Provider-Name',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Service-Familie',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Service-Familien-Name',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Beschreibung',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Dokumente',
	'Class:Service/Attribute:documents_list+' => '',
	'Class:Service/Attribute:contacts_list' => 'Kontakte',
	'Class:Service/Attribute:contacts_list+' => '',
	'Class:Service/Attribute:status' => 'Status',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Implementation',
	'Class:Service/Attribute:status/Value:implementation+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Produktion',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icon',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Kunden-Verträge',
	'Class:Service/Attribute:customercontracts_list+' => '',
	'Class:Service/Attribute:providercontracts_list' => 'Provider-Verträge',
	'Class:Service/Attribute:providercontracts_list+' => '',
	'Class:Service/Attribute:functionalcis_list' => 'Benötigte CIs',
	'Class:Service/Attribute:functionalcis_list+' => '',
	'Class:Service/Attribute:servicesubcategories_list' => 'Service-Subkategorien',
	'Class:Service/Attribute:servicesubcategories_list+' => '',
));

//
// Class: lnkDocumentToService
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDocumentToService' => 'Verknüpfung Dokument/Service',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Service',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Service-Name',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Dokument-Name',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContactToService' => 'Verknüpfung Kontakt/Service',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Service',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Service-Name',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Kontakt-name',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ServiceSubcategory' => 'Service-Unterkategorien',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Name',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Beschreibung',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Service',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Service',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Request-Typ',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Service-Request',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '',
	'Class:ServiceSubcategory/Attribute:status' => 'Status',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Implementierung',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Produktion',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => '',
));

//
// Class: SLA
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Name',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Beschreibung',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Provider',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Provider-Name',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => 'Service Level Targets:',
	'Class:SLA/Attribute:customercontracts_list' => 'Kunden-Verträge',
	'Class:SLA/Attribute:customercontracts_list+' => '',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Konnte den Link zwischen Kundevertrag %1$s und Service %2$s speichern: SLA existiert bereits',
));

//
// Class: SLT
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Name',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Priorität',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'Kritisch',
	'Class:SLT/Attribute:priority/Value:1+' => '',
	'Class:SLT/Attribute:priority/Value:2' => 'Hoch',
	'Class:SLT/Attribute:priority/Value:2+' => '',
	'Class:SLT/Attribute:priority/Value:3' => 'Mittel',
	'Class:SLT/Attribute:priority/Value:3+' => '',
	'Class:SLT/Attribute:priority/Value:4' => 'Niedrig',
	'Class:SLT/Attribute:priority/Value:4+' => '',
	'Class:SLT/Attribute:request_type' => 'Request-Typ',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => '',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Service-Request',
	'Class:SLT/Attribute:request_type/Value:service_request+' => '',
	'Class:SLT/Attribute:metric' => 'Metrik',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO (Time To Own)',
	'Class:SLT/Attribute:metric/Value:tto+' => '',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR (Time To Resolve)',
	'Class:SLT/Attribute:metric/Value:ttr+' => '',
	'Class:SLT/Attribute:value' => 'Wert',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Einheit',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'Stunden',
	'Class:SLT/Attribute:unit/Value:hours+' => '',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Minuten',
	'Class:SLT/Attribute:unit/Value:minutes+' => '',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkSLAToSLT' => 'Verknüpfung SLA/SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA-Name',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT-Name',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'SLT Metrik',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => 'Welche Zeit soll gemessen werden?',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'SLT Request Typ',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => 'Tickettyp für den das SLT gilt',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'SLT Ticketpriorität',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => 'Für Tickets welcher Priorität soll das SLT gelten?',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'SLT Wert',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => 'Zeit, nach der das SLT abläuft.',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'SLT Einheit',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkCustomerContractToService' => 'Verknüpfung Kunden-Vertrag/Service',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Kunden-Vertrag',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Kundenvertrags-Name',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Service-Name',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA-Name',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkProviderContractToService' => 'Verknüpfung Provider-Vertrag/Service',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Service',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Service-Name',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Provider-Vertrag',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Providervertrags-Name',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DeliveryModel' => 'Delivery-Modell',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Name',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organisation',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organizations-Name',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Beschreibung',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Kontakte',
	'Class:DeliveryModel/Attribute:contacts_list+' => '',
	'Class:DeliveryModel/Attribute:customers_list' => 'Kunden',
	'Class:DeliveryModel/Attribute:customers_list+' => '',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDeliveryModelToContact' => 'Verknüpfung Delivery-Modell/Kontakt',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Delivery-Modell',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Delivery-Modell-Name',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kontakt',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Kontakt-Name',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rolle',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Rollen-Name',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));

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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Class: ContractType
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ContractType' => 'Type de contrat',
	'Class:ContractType+' => '',
));

//
// Class: Organization
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Modèle de support',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nom modèle de support',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
));

//
// Class: Contract
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Contract' => 'Contrat',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Nom',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Client',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Nom client',
	'Class:Contract/Attribute:organization_name+' => 'Nom commun',
	'Class:Contract/Attribute:contacts_list' => 'Contacts',
	'Class:Contract/Attribute:contacts_list+' => '',
	'Class:Contract/Attribute:documents_list' => 'Documents',
	'Class:Contract/Attribute:documents_list+' => '',
	'Class:Contract/Attribute:description' => 'Description',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Date de début',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Date de fin',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Coût',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Monnaie',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollars',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euros',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Type de contrat',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Nom Type de contrat',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Périodicité de facturation',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Unité de coût',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Fournisseur',
	'Class:Contract/Attribute:provider_id+' => '',
	'Class:Contract/Attribute:provider_name' => 'Nom Fournisseur',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Statut',
	'Class:Contract/Attribute:status+' => '',
	'Class:Contract/Attribute:status/Value:implementation' => 'implémentation',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implémentation',
	'Class:Contract/Attribute:status/Value:obsolete' => 'obsolète',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolète',
	'Class:Contract/Attribute:status/Value:production' => 'production',
	'Class:Contract/Attribute:status/Value:production+' => 'production',
	'Class:Contract/Attribute:finalclass' => 'Type',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:CustomerContract' => 'Contrat client',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Services',
	'Class:CustomerContract/Attribute:services_list+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ProviderContract' => 'Contrat fournisseur',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => '',
	'Class:ProviderContract/Attribute:sla' => 'Niveau de service',
	'Class:ProviderContract/Attribute:sla+' => 'Accord de niveau de service (SLA)',
	'Class:ProviderContract/Attribute:coverage' => 'Couverture',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Type de contrat',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Nom type de contrat',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
));

//
// Class: lnkContactToContract
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkContactToContract' => 'Lien Contact / Contrat',
	'Class:lnkContactToContract+' => '',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contrat',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Nom contrat',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Nom contact',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
));

//
// Class: lnkContractToDocument
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkContractToDocument' => 'Lien Contract / Document',
	'Class:lnkContractToDocument+' => '',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contrat',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Nom contrat',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Document',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Nom document',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Lien CI / Contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Nom contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: ServiceFamily
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ServiceFamily' => 'Famille de service',
	'Class:ServiceFamily+' => '',
	'Class:ServiceFamily/Attribute:name' => 'Nom',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Services',
	'Class:ServiceFamily/Attribute:services_list+' => '',
));

//
// Class: Service
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Service' => 'Service',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Nom',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Fournisseur',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Nom fournisseur',
	'Class:Service/Attribute:organization_name+' => 'Nom commun',
	'Class:Service/Attribute:servicefamily_id' => 'Famille de service',
	'Class:Service/Attribute:servicefamily_id+' => '',
	'Class:Service/Attribute:servicefamily_name' => 'Nom Famille de service',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => 'Description',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Documents',
	'Class:Service/Attribute:documents_list+' => '',
	'Class:Service/Attribute:contacts_list' => 'Contacts',
	'Class:Service/Attribute:contacts_list+' => '',
	'Class:Service/Attribute:status' => 'Statut',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'implémentation',
	'Class:Service/Attribute:status/Value:implementation+' => 'implémentation',
	'Class:Service/Attribute:status/Value:obsolete' => 'obsolète',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'en production',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Contrats client',
	'Class:Service/Attribute:customercontracts_list+' => '',
	'Class:Service/Attribute:providercontracts_list' => 'Contrats fournisseur',
	'Class:Service/Attribute:providercontracts_list+' => '',
	'Class:Service/Attribute:functionalcis_list' => 'CIs',
	'Class:Service/Attribute:functionalcis_list+' => '',
	'Class:Service/Attribute:servicesubcategories_list' => 'Sous catégories de service',
	'Class:Service/Attribute:servicesubcategories_list+' => '',
));

//
// Class: lnkDocumentToService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToService' => 'Lien Document / Service',
	'Class:lnkDocumentToService+' => '',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Service',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Nom service',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Nom document',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
));

//
// Class: lnkContactToService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkContactToService' => 'Lien Contact / Service',
	'Class:lnkContactToService+' => '',
	'Class:lnkContactToService/Attribute:service_id' => 'Service',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Nom service',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Nom contact',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
));

//
// Class: ServiceSubcategory
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ServiceSubcategory' => 'Sous catégorie de service',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Nom',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Description',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Service',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Nom Service',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Type de requête',
	'Class:ServiceSubcategory/Attribute:request_type+' => '',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'demande de service',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'demande de service',
	'Class:ServiceSubcategory/Attribute:status' => 'Statut',
	'Class:ServiceSubcategory/Attribute:status+' => '',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implémentation',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implémentation',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'obsolète',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolète',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'production',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production',
));

//
// Class: SLA
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SLA' => 'Niveau de service',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Nom',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Description',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Fournisseur',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Nom fournisseur',
	'Class:SLA/Attribute:organization_name+' => 'Nom commun',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => '',
	'Class:SLA/Attribute:customercontracts_list' => 'Contrats clients',
	'Class:SLA/Attribute:customercontracts_list+' => '',
));

//
// Class: SLT
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Objectif de niveau de service (SLT)',
	'Class:SLT/Attribute:name' => 'Nom',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Priorité',
	'Class:SLT/Attribute:priority+' => '',
	'Class:SLT/Attribute:priority/Value:1' => 'critique',
	'Class:SLT/Attribute:priority/Value:1+' => 'critique',
	'Class:SLT/Attribute:priority/Value:2' => 'haute',
	'Class:SLT/Attribute:priority/Value:2+' => 'haute',
	'Class:SLT/Attribute:priority/Value:3' => 'moyenne',
	'Class:SLT/Attribute:priority/Value:3+' => 'moyenne',
	'Class:SLT/Attribute:priority/Value:4' => 'basse',
	'Class:SLT/Attribute:priority/Value:4+' => 'basse',
	'Class:SLT/Attribute:request_type' => 'Type de requête',
	'Class:SLT/Attribute:request_type+' => '',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'demande de service',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'demande de service',
	'Class:SLT/Attribute:metric' => 'Métrique',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Valeur',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Unité',
	'Class:SLT/Attribute:unit+' => '',
	'Class:SLT/Attribute:unit/Value:hours' => 'heures',
	'Class:SLT/Attribute:unit/Value:hours+' => 'heures',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minutes',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minutes',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkSLAToSLT' => 'Lien SLA / SLT',
	'Class:lnkSLAToSLT+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'Nom SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'Nom SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkCustomerContractToService' => 'Lien Contrat client / Service',
	'Class:lnkCustomerContractToService+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Contrat client',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Nom contrat client',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Nom service',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'Nom SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkProviderContractToService' => 'Lien Contrat fournisseur / Service',
	'Class:lnkProviderContractToService+' => '',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Service',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Nom service',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Contrat fournisseur',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Nom contrat fournisseur',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkFunctionalCIToService' => 'Lien CI / Service',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Nom service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: DeliveryModel
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DeliveryModel' => 'Modèle de support',
	'Class:DeliveryModel+' => '',
	'Class:DeliveryModel/Attribute:name' => 'Nom Modèle de support',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organisation',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Nom organisation',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Common name',
	'Class:DeliveryModel/Attribute:description' => 'Description',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contacts',
	'Class:DeliveryModel/Attribute:contacts_list+' => '',
	'Class:DeliveryModel/Attribute:customers_list' => 'Clients',
	'Class:DeliveryModel/Attribute:customers_list+' => '',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDeliveryModelToContact' => 'Lien Modèle de support / Contact',
	'Class:lnkDeliveryModelToContact+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Modèle de support',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Nom Modèle de support',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Nom Contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rôle',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Nom Rôle',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
));


Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:ServiceManagement' => 'Gestion des services',
	'Menu:ServiceManagement+' => '',
	'Menu:Service:Overview' => 'Vue d\'ensemble',
	'Menu:Service:Overview+' => 'Vue d\'ensemble de la Gestion des Services',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contrats, par niveau de service',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contrats, par état',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contrats se terminant dans moins d\'un mois',
	'Menu:ServiceFamily' => 'Familles de service',
	'Menu:ServiceFamily+' => 'Familles de service',
	'Menu:ProviderContract' => 'Contrats fournisseurs',
	'Menu:ProviderContract+' => 'Contrats fournisseurs',
	'Menu:CustomerContract' => 'Contrats clients',
	'Menu:CustomerContract+' => 'Contrats clients',
	'Menu:ServiceElement' => 'Eléments de service',
	'Menu:ServiceElement+' => 'Eléments de service',
	'Menu:ServiceSubcategory' => 'Sous catégories de service',
	'Menu:ServiceSubcategory+' => 'Sous catégories de service',
	'Menu:Service' => 'Services',
	'Menu:Service+' => 'Services',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Accords de niveau de service (SLA)',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Objectifs de niveau de service (SLT)',
	'Menu:DeliveryModel' => 'Modèles de support',
	'Menu:DeliveryModel+' => 'Modèles de support',
));
?>

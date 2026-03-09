<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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

//
// Menu, fieldsets, UI, messages translations
//
Dict::Add('EN US', 'English', 'English', [
	'Menu:ServiceManagement' => 'Service Management',
	'Menu:ServiceManagement+' => 'Service Management Overview',
	'Menu:Service:Overview' => 'Overview',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contracts by service level',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contracts by status',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contracts ending in less than 30 days',

	'Menu:ProviderContract' => 'Provider contracts',
	'Menu:ProviderContract+' => 'What is bought to external companies',
	'Menu:CustomerContract' => 'Customer contracts',
	'Menu:CustomerContract+' => 'Who is buying the services',
	'Menu:ServiceSubcategory' => 'Service subcategories',
	'Menu:ServiceSubcategory+' => 'Lowest level in service hierarchy',
	'Menu:Service' => 'Services',
	'Menu:Service+' => 'Second level in service hierarchy',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Service Level Agreements',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Service Level Targets',
	'Menu:DeliveryModel' => 'Delivery models',
	'Menu:DeliveryModel+' => 'Teams handling tickets',
	'Menu:ServiceFamily' => 'Service families',
	'Menu:ServiceFamily+' => 'Top level in service hierarchy',
	'Menu:ServiceCatalog' => 'Service catalog',
	'Menu:ServiceCatalog+' => 'Define the service elements of your offering',
	'UI-ServiceCatalogMenu-Title' => 'Service catalog',
	'UI-ServiceCatalogMenu-NotInPortal' => 'Not displayed in User Portal',
	'UI-ServiceCatalogMenu-OnlyProductionInPortal' => 'Only Service and Subcategory on production are visible in User Portal',
	'UI-ServiceCatalogMenu-UnusedService' => 'Services not used by any Customers',
	'UI-ServiceCatalogMenu-ServiceWithoutFamilyNotInPortal' => 'Services without Service Family are not visible in User Portal',
	'UI-ServiceCatalogMenu-SLTBySLA' => 'Count SLTs on each SLA',
	'UI-ServiceCatalogMenu-ContractByService' => 'Count Contracts using a Service',
	'UI-ServiceCatalogMenu-ContractBySLA' => 'Count Contracts using an SLA',

	'Contract:baseinfo' => 'General information',
	'Contract:moreinfo' => 'Contractual information',
	'Contract:cost'     => 'Cost information',
]);

//
// Class: Organization
//

Dict::Add('EN US', 'English', 'English', [
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
]);

//
// Class: ContractType
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContractType' => 'Contract Type',
	'Class:ContractType+' => 'Typology for categorizing Customer and Provider Contracts.',
]);

//
// Class: Contract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:Contract' => 'Contract',
	'Class:Contract+' => 'Abstract class to handle fields common to the different contract types.',
	'Class:Contract/Attribute:name' => 'Name',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Organization',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => 'Organization Name',
	'Class:Contract/Attribute:organization_name+' => 'Common name',
	'Class:Contract/Attribute:contacts_list' => 'Contacts',
	'Class:Contract/Attribute:contacts_list+' => 'All the contacts for this customer contract',
	'Class:Contract/Attribute:documents_list' => 'Documents',
	'Class:Contract/Attribute:documents_list+' => 'All the documents for this customer contract',
	'Class:Contract/Attribute:description' => 'Description',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Start date',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'End date',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Cost',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Cost Currency',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dollars',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euros',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Contract type',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => 'Contract type Name',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Billing frequency',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Cost unit',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Provider',
	'Class:Contract/Attribute:provider_id+' => 'Provider organization for this contract. Can be different from the provider of the associated services.',
	'Class:Contract/Attribute:provider_name' => 'Provider Name',
	'Class:Contract/Attribute:provider_name+' => '',
	'Class:Contract/Attribute:status' => 'Status',
	'Class:Contract/Attribute:status+' => 'The status is not computed based on start and end dates. It must be set manually.',
	'Class:Contract/Attribute:status/Value:implementation' => 'implementation',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementation',
	'Class:Contract/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:Contract/Attribute:status/Value:production' => 'production',
	'Class:Contract/Attribute:status/Value:production+' => 'production',
	'Class:Contract/Attribute:finalclass' => 'Contract sub-class',
	'Class:Contract/Attribute:finalclass+' => 'Name of the final class',
]);

//
// Class: CustomerContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:CustomerContract' => 'Customer Contract',
	'Class:CustomerContract+' => 'Agreement between a client and a provider for the delivery of services with an optional level of commitment (SLA, Coverage Window).',
	'Class:CustomerContract/Attribute:services_list' => 'Services',
	'Class:CustomerContract/Attribute:services_list+' => 'All the services purchased for this contract',
	'Class:CustomerContract/Attribute:functionalcis_list' => 'CIs',
	'Class:CustomerContract/Attribute:functionalcis_list+' => 'All the configuration items covered by this contract',
	'Class:CustomerContract/Attribute:providercontracts_list' => 'Provider contracts',
	'Class:CustomerContract/Attribute:providercontracts_list+' => 'All the provider contracts to deliver the services for this contract (underpinning contract)',
]);

//
// Class: ProviderContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ProviderContract' => 'Provider Contract',
	'Class:ProviderContract+' => 'Agreement between an external provider and an internal organization.',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CIs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'All the configuration items covered by this contract',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Service hours',
	'Class:ProviderContract/Attribute:coverage+' => 'Temporal coverage of the contract, e.g. 24x7, 9x5, etc.',
]);

//
// Class: lnkContactToContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContactToContract' => 'Link Contact / Contract',
	'Class:lnkContactToContract+' => 'Manages key contacts on each Customer or Provider Contract.',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contract',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Contract Name',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Contact Name',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
]);

//
// Class: lnkContractToDocument
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContractToDocument' => 'Link Contract / Document',
	'Class:lnkContractToDocument+' => 'Link used when a Document is applicable to a Contract.',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contract',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Contract Name',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Document',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Document Name',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
]);

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkFunctionalCIToProviderContract' => 'Link FunctionalCI / ProviderContract',
	'Class:lnkFunctionalCIToProviderContract+' => 'This link models the Functional CIs that are supported by an external company through a Provider Contract.',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Provider contract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Provider contract Name',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI Name',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
]);

//
// Class: ServiceFamily
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ServiceFamily' => 'Service Family',
	'Class:ServiceFamily+' => 'Top level of Service hierarchy. Required for Services to be proposed in User Portal',
	'Class:ServiceFamily/Attribute:name' => 'Name',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => 'Icon',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => 'Services',
	'Class:ServiceFamily/Attribute:services_list+' => 'All the services in this category',
]);

//
// Class: Service
//

Dict::Add('EN US', 'English', 'English', [
	'Class:Service' => 'Service',
	'Class:Service+' => 'A Service is delivered by an organization and subscribed to through a Contract Client. It must contain at least one Service Subcategory.',
	'Class:Service/ComplementaryName' => '%1$s - %2$s',
	'Class:Service/Attribute:name' => 'Name',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Provider',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Provider Name',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:description' => 'Description',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:servicefamily_id' => 'Service Family',
	'Class:Service/Attribute:servicefamily_id+' => 'Required for this service to be visible on User Portal',
	'Class:Service/Attribute:servicefamily_name' => 'Service Family Name',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:documents_list' => 'Documents',
	'Class:Service/Attribute:documents_list+' => 'All the documents linked to the service',
	'Class:Service/Attribute:contacts_list' => 'Contacts',
	'Class:Service/Attribute:contacts_list+' => 'All the contacts for this service',
	'Class:Service/Attribute:status' => 'Status',
	'Class:Service/Attribute:status+' => 'Service without status or obsolete are not visible on User Portal',
	'Class:Service/Attribute:status/Value:implementation' => 'implementation',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementation',
	'Class:Service/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'production',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Icon',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => 'Customer contracts',
	'Class:Service/Attribute:customercontracts_list+' => 'All the customer contracts that have purchased this service',
	'Class:Service/Attribute:servicesubcategories_list' => 'Service sub categories',
	'Class:Service/Attribute:servicesubcategories_list+' => 'All the sub categories for this service',
]);

//
// Class: lnkDocumentToService
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkDocumentToService' => 'Link Document / Service',
	'Class:lnkDocumentToService+' => 'Link used when a Document is applicable to a Service.',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Service',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Service Name',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Document Name',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
]);

//
// Class: lnkContactToService
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContactToService' => 'Link Contact / Service',
	'Class:lnkContactToService+' => 'Ideal for defining the Team to which Tickets created on the related Service will be assigned (automatically or manually).',
	'Class:lnkContactToService/Name' => '%1$s / %2$s',
	'Class:lnkContactToService/Attribute:service_id' => 'Service',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => 'Service Name',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => 'Contact Name',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
]);

//
// Class: ServiceSubcategory
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ServiceSubcategory' => 'Service Subcategory',
	'Class:ServiceSubcategory+' => 'Lowest level in Service hierarchy. User Request are usually associated to one Service Subcategory.',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s',
	'Class:ServiceSubcategory/Attribute:name' => 'Name',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Description',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Service',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Service Name',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:status' => 'Status',
	'Class:ServiceSubcategory/Attribute:status+' => 'Service subcategory without status or obsolete are not visible on User Portal',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'implementation',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementation',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'production',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Request type',
	'Class:ServiceSubcategory/Attribute:request_type+' => 'Define the type of Ticket (Incident or Service Request) that will be created when a Portal user requests this service subcategory.',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'service request',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:ServiceSubcategory/Attribute:service_provider' => 'Provider Name',
	'Class:ServiceSubcategory/Attribute:service_org_id' => 'Provider',
]);

//
// Class: SLA
//

Dict::Add('EN US', 'English', 'English', [
	'Class:SLA' => 'SLA',
	'Class:SLA+' => 'Service Level Agreement (SLA) applicable to a Service subscribed by a customer and measured using SLTs.',
	'Class:SLA/Attribute:name' => 'Name',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'description',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => 'Organization',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => 'Organization Name',
	'Class:SLA/Attribute:organization_name+' => '',
	'Class:SLA/Attribute:slts_list' => 'SLTs',
	'Class:SLA/Attribute:slts_list+' => 'All the service level targets for this SLA',
	'Class:SLA/Attribute:customercontracts_list' => 'Customer contracts',
	'Class:SLA/Attribute:customercontracts_list+' => 'All the customer contracts using this SLA',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists',
]);

//
// Class: SLT
//

Dict::Add('EN US', 'English', 'English', [
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Service Level Target under a Service Level Agreement (SLA). Defines a maximum time for a metric (TTO or TTR), a request type (Incident or Request) and a priority.',
	'Class:SLT/Attribute:name' => 'Name',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Priority',
	'Class:SLT/Attribute:priority+' => 'Ticket priority to which this SLT applies. Only tickets with this priority must comply with this SLT.',
	'Class:SLT/Attribute:priority/Value:1' => 'critical',
	'Class:SLT/Attribute:priority/Value:1+' => 'critical',
	'Class:SLT/Attribute:priority/Value:2' => 'high',
	'Class:SLT/Attribute:priority/Value:2+' => 'high',
	'Class:SLT/Attribute:priority/Value:3' => 'medium',
	'Class:SLT/Attribute:priority/Value:3+' => 'medium',
	'Class:SLT/Attribute:priority/Value:4' => 'low',
	'Class:SLT/Attribute:priority/Value:4+' => 'low',
	'Class:SLT/Attribute:request_type' => 'Request type',
	'Class:SLT/Attribute:request_type+' => 'Request type to which this SLT applies. Only tickets with this request type must comply with this SLT.',
	'Class:SLT/Attribute:request_type/Value:incident' => 'incident',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'service request',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request',
	'Class:SLT/Attribute:metric' => 'Metric',
	'Class:SLT/Attribute:metric+' => 'Delay type to which this SLT applies. TTO (Time To Own) or TTR (Time To Resolve).',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Value',
	'Class:SLT/Attribute:value+' => 'Delay value which must not be exceeded to be compliant with the target. The unit is defined in the "unit" attribute.',
	'Class:SLT/Attribute:unit' => 'Unit',
	'Class:SLT/Attribute:unit+' => 'Unit for the delay value.',
	'Class:SLT/Attribute:unit/Value:hours' => 'hours',
	'Class:SLT/Attribute:unit/Value:hours+' => 'hours',
	'Class:SLT/Attribute:unit/Value:minutes' => 'minutes',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minutes',
]);

//
// Class: lnkSLAToSLT
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkSLAToSLT' => 'Link SLA / SLT',
	'Class:lnkSLAToSLT+' => 'This link indicates that an SLT is included in the Service Level Agreement (SLA). An SLA usually contains several SLTs. An SLT can be reused as is by several SLAs (seldom).',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA Name',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT Name',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'SLT metric',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'SLT request type',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'SLT ticket priority',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'SLT value',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'SLT value unit',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
]);

//
// Class: lnkCustomerContractToService
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkCustomerContractToService' => 'Link Customer Contract / Service',
	'Class:lnkCustomerContractToService+' => 'A single line of a Customer Contract, specifying the Service provided and, for this service, the subscribed commitment levels (Service Level Agreement and Coverage Window).',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Customer contract',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Customer contract Name',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Service',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => 'All service subcategories linked to this service are also included by the contract.',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Service Name',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => 'Service Level Agreement applicable to this service for this customer contract.',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Name',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:provider_id' => 'Fournisseur',
	'Class:lnkCustomerContractToService/Attribute:provider_id+' => '',
]);

//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkCustomerContractToProviderContract' => 'Link Customer Contract / Provider Contract',
	'Class:lnkCustomerContractToProviderContract+' => 'This link models when a Provider Contract contributes to the delivery of a Customer Contract.',
	'Class:lnkCustomerContractToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id' => 'Customer contract',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name' => 'Customer contract Name',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id' => 'Provider contract',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name' => 'Provider contract Name',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name+' => '',
]);

//
// Class: lnkCustomerContractToFunctionalCI
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkCustomerContractToFunctionalCI' => 'Link Customer Contract / FunctionalCI',
	'Class:lnkCustomerContractToFunctionalCI+' => 'This link models the equipment (Functional CI) covered by a Customer Contract.',
	'Class:lnkCustomerContractToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id' => 'Customer contract',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name' => 'Customer contract Name',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name' => 'CI Name',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name+' => '',
]);

//
// Class: DeliveryModel
//

Dict::Add('EN US', 'English', 'English', [
	'Class:DeliveryModel' => 'Delivery Model',
	'Class:DeliveryModel+' => 'The Delivery Model specifies the Teams that can be assigned to Tickets; it must contain at least one Team in the Contacts tab.
Each client Organization must have a defined Delivery Model.',
	'Class:DeliveryModel/Attribute:name' => 'Name',
	'Class:DeliveryModel/Attribute:name+' => '',
	'Class:DeliveryModel/Attribute:org_id' => 'Organization',
	'Class:DeliveryModel/Attribute:org_id+' => '',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organization Name',
	'Class:DeliveryModel/Attribute:organization_name+' => '',
	'Class:DeliveryModel/Attribute:description' => 'Description',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contacts',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'There must be at least one team to enable Ticket assignment',
	'Class:DeliveryModel/Attribute:customers_list' => 'Customers',
	'Class:DeliveryModel/Attribute:customers_list+' => 'All the customers having this delivering model',
]);

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkDeliveryModelToContact' => 'Link DeliveryModel / Contact',
	'Class:lnkDeliveryModelToContact+' => 'This link specifies the role of a Team (more rarely a Person) within a Delivery Model.',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Delivery model name',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Contact name',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Role',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Role name',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
]);

//
// Class: lnkContactToContract
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContactToContract/Attribute:customer_id' => 'Customer id',
	'Class:lnkContactToContract/Attribute:customer_id+' => '',
	'Class:lnkContactToContract/Attribute:provider_id' => 'Provider id',
	'Class:lnkContactToContract/Attribute:provider_id+' => '',
]);

//
// Class: lnkContractToDocument
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContractToDocument/Attribute:customer_id' => 'Customer id',
	'Class:lnkContractToDocument/Attribute:customer_id+' => '',
	'Class:lnkContractToDocument/Attribute:provider_id' => 'Provider id',
	'Class:lnkContractToDocument/Attribute:provider_id+' => '',
]);

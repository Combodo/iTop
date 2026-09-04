<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Benjamin Planque <benjamin.planque@combodo.com>
 *
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:lnkFunctionalCIToProviderContract' => 'Link Functioneel CI / Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract+' => 'This link models the functional CIs that are supported by an external company through a provider contract.~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Naam leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToService' => 'Link Functioneel CI / Service',
	'Class:lnkFunctionalCIToService+' => 'This link models the functional CIs that are required to deliver a Service and whose malfunction would affect the quality of the Service.~~',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Naam service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leverancierscontracten',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Alle leverancierscontracten voor dit configuratie-item',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => 'Alle services die impact hebben op dit configuratie-item',
	'Class:Document/Attribute:contracts_list' => 'Contracten',
	'Class:Document/Attribute:contracts_list+' => 'Alle contracten gerelateerd aan dit document',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Alle services gerelateerd aan dit document.',
]);

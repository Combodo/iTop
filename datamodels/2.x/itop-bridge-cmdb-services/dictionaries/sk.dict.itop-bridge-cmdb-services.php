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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', [
	'Class:lnkFunctionalCIToProviderContract' => 'väzba - Komponent / Poskytovateľská zmluva',
	'Class:lnkFunctionalCIToProviderContract+' => 'This link models the functional CIs that are supported by an external company through a provider contract.~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Poskytovateľská zmluva',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Názov poskytovateľského zmluvy',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Zariadenie',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToService' => 'väzba - Komponent / Služba',
	'Class:lnkFunctionalCIToService+' => 'This link models the functional CIs that are required to deliver a Service and whose malfunction would affect the quality of the Service.~~',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Služba',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Názov služby',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Zariadenie',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Poskytovateľské zmluvy',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'All the provider contracts for this configuration item~~',
	'Class:FunctionalCI/Attribute:services_list' => 'Služby',
	'Class:FunctionalCI/Attribute:services_list+' => 'All the services impacted by this configuration item~~',
	'Class:Document/Attribute:contracts_list' => 'Zmluvy',
	'Class:Document/Attribute:contracts_list+' => 'All the contracts linked to this document~~',
	'Class:Document/Attribute:services_list' => 'Služby',
	'Class:Document/Attribute:services_list+' => 'All the services linked to this document~~',
]);

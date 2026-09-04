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
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Class:lnkFunctionalCIToProviderContract' => 'Spojení (Funkční konfigurační položka / Smlouva s poskytovatelem)',
	'Class:lnkFunctionalCIToProviderContract+' => 'This link models the functional CIs that are supported by an external company through a provider contract.~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Smlouva s poskytovatelem',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Název smlouvy s poskytovatelem',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Konfigurační položka',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Název konfigurační položky',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToService' => 'Spojení (Funkční konfigurační položka / Služba)',
	'Class:lnkFunctionalCIToService+' => 'This link models the functional CIs that are required to deliver a Service and whose malfunction would affect the quality of the Service.~~',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Služba',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Název služby',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Konfigurační položka',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Název konfigurační položky',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Smlouvy s poskytovateli',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'All the provider contracts for this configuration item~~',
	'Class:FunctionalCI/Attribute:services_list' => 'Služby',
	'Class:FunctionalCI/Attribute:services_list+' => 'All the services impacted by this configuration item~~',
	'Class:Document/Attribute:contracts_list' => 'Smlouvy',
	'Class:Document/Attribute:contracts_list+' => 'Všechny smlouvy spojené s tímto dokumentem',
	'Class:Document/Attribute:services_list' => 'Služby',
	'Class:Document/Attribute:services_list+' => 'Všechny služby spojené s tímto dokumentem',
]);

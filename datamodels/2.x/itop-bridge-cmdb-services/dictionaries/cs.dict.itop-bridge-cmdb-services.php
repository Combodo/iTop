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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Spojení (Funkční konfigurační položka / Smlouva s poskytovatelem)',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Smlouva s poskytovatelem',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Název smlouvy s poskytovatelem',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Konfigurační položka',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Název konfigurační položky',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:lnkFunctionalCIToService' => 'Spojení (Funkční konfigurační položka / Služba)',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Služba',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Název služby',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Konfigurační položka',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Název konfigurační položky',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Smlouvy s poskytovateli',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
	'Class:FunctionalCI/Attribute:services_list' => 'Služby',
	'Class:FunctionalCI/Attribute:services_list+' => '',
));

//
// Class: Document
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Document/Attribute:contracts_list' => 'Smlouvy',
	'Class:Document/Attribute:contracts_list+' => 'Všechny smlouvy spojené s tímto dokumentem',
	'Class:Document/Attribute:services_list' => 'Služby',
	'Class:Document/Attribute:services_list+' => 'Všechny služby spojené s tímto dokumentem',
));
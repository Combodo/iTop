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
Dict::Add('JA JP', 'Japanese', '日本語', [
	'Class:lnkFunctionalCIToProviderContract' => 'リンク 機能的CI/プロバイダー契約',
	'Class:lnkFunctionalCIToProviderContract+' => 'This link models the functional CIs that are supported by an external company through a provider contract.~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'プロバイダー契約',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'プロバイダー契約名',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI名',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToService' => 'リンク 機能的CI/サービス',
	'Class:lnkFunctionalCIToService+' => 'This link models the functional CIs that are required to deliver a Service and whose malfunction would affect the quality of the Service.~~',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'サービス',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'サービス名',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'CI名',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'プロバイダー契約',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'All the provider contracts for this configuration item~~',
	'Class:FunctionalCI/Attribute:services_list' => 'サービス',
	'Class:FunctionalCI/Attribute:services_list+' => 'All the services impacted by this configuration item~~',
	'Class:Document/Attribute:contracts_list' => '契約',
	'Class:Document/Attribute:contracts_list+' => 'All the contracts linked to this document~~',
	'Class:Document/Attribute:services_list' => 'サービス',
	'Class:Document/Attribute:services_list+' => 'All the services linked to this document~~',
]);

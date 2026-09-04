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
Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkFunctionalCIToProviderContract' => 'Lien CI fonctionnel / Contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract+' => 'Ce lien modélise les équipments (CI fonctionnel) qui sont supportés par une société externe à travers un Contrat fournisseur.',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Nom contrat fournisseur',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToService' => 'Lien CI fonctionnel / Service',
	'Class:lnkFunctionalCIToService+' => 'Ce lien modélise les équipments (CI fonctionnel) qui sont nécessaires pour délivrer un Service et dont le dysfonctionnement affecterait la qualité du Service en question.',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Nom service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contrats fournisseur',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'All the provider contracts for this configuration item~~',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => 'All the services impacted by this configuration item~~',
	'Class:Document/Attribute:contracts_list' => 'Contrats',
	'Class:Document/Attribute:contracts_list+' => 'Tous les contrats liés à ce document',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Tous les services liés à ce document',
]);

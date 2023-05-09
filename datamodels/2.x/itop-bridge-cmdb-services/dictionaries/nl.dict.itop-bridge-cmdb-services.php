<?php
// Copyright (C) 2010-2023 Combodo SARL
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
* @author       Benjamin Planque <benjamin.planque@combodo.com>
* @copyright   Copyright (C) 2010-2023 Combodo SARL
* @license     http://opensource.org/licenses/AGPL-3.0
*/
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Link Functioneel CI / Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Naam leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToService' => 'Link Functioneel CI / Service',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Naam service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leverancierscontracten',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Alle leverancierscontracten voor dit configuratie-item',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => 'Alle services die impact hebben op dit configuratie-item',
));

//
// Class: Document
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Document/Attribute:contracts_list' => 'Contracten',
	'Class:Document/Attribute:contracts_list+' => 'Alle contracten gerelateerd aan dit document',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Alle services gerelateerd aan dit document.',
));
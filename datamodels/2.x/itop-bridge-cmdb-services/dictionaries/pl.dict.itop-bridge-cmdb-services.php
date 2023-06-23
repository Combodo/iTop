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
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Połączenie Konfiguracja / Umowa z dostawcą',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Umowa z dostawcą',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Nazwa umowy z dostawcą',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkFunctionalCIToService' => 'Połączenie Konfiguracja / Usługa',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Usługa',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Nazwa usługi',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Umowy z dostawcami',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Wszystkie umowy dostawcy dla tej konfiguracji',
	'Class:FunctionalCI/Attribute:services_list' => 'Usługi',
	'Class:FunctionalCI/Attribute:services_list+' => 'Wszystkie usługi, na które ma wpływ tą konfigurację',
));

//
// Class: Document
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Document/Attribute:contracts_list' => 'Umowy',
	'Class:Document/Attribute:contracts_list+' => 'Wszystkie umowy powiązane z tym dokumentem',
	'Class:Document/Attribute:services_list' => 'Usługi',
	'Class:Document/Attribute:services_list+' => 'Wszystkie usługi powiązane z tym dokumentem',
));
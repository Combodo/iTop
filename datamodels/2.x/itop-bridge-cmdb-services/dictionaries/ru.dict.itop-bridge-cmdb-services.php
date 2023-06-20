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
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Связь Функциональная КЕ/Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Договор с поставщиком',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'КЕ',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:lnkFunctionalCIToService' => 'Связь Функциональная КЕ/Услуга',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Услуга',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Услуга',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'КЕ',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'КЕ',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Договоры',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Связанные договоры',
	'Class:FunctionalCI/Attribute:services_list' => 'Услуги',
	'Class:FunctionalCI/Attribute:services_list+' => 'Связанные услуги',
));

//
// Class: Document
//

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Document/Attribute:contracts_list' => 'Договоры',
	'Class:Document/Attribute:contracts_list+' => 'Связанные договоры',
	'Class:Document/Attribute:services_list' => 'Услуги',
	'Class:Document/Attribute:services_list+' => 'Связанные услуги',
));
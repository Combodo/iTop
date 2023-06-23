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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkFunctionalCIToProviderContract' => 'İşlevsel CI / Sağlayıcı Sözleşmesi bağla',
	'Class:lnkFunctionalCIToProviderContract+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Sağlayıcı Sözleşmesi',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Sağlayıcı Sözleşme Adı',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI Adı',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkFunctionalCIToService' => 'İşlevsel CI / servis bağla',
	'Class:lnkFunctionalCIToService+' => '~~',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Servis',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Servis Adı',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'CI Adı',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '~~',
));

//
// Class: FunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Tedarikçi Sözleşmeleri',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Bu yapılandırma öğesi için tüm tedarikçi sözleşmeleri',
	'Class:FunctionalCI/Attribute:services_list' => 'Hizmetler',
	'Class:FunctionalCI/Attribute:services_list+' => 'Bu yapılandırma öğesinden etkilenen tüm hizmetler',
));

//
// Class: Document
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Document/Attribute:contracts_list' => 'Contracts~~',
	'Class:Document/Attribute:contracts_list+' => 'All the contracts linked to this document~~',
	'Class:Document/Attribute:services_list' => 'Services~~',
	'Class:Document/Attribute:services_list+' => 'All the services linked to this document~~',
));
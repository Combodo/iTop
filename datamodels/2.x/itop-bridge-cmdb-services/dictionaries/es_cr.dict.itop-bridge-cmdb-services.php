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
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Relación EC Funcional y Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract+' => 'Relación EC Funcional y Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => 'Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => 'Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'EC',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => 'Elemento de Configuración',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Elemento de Configuración',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => 'Elemento de Configuración',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToService' => 'Relación EC Funcional y Servicio',
	'Class:lnkFunctionalCIToService+' => 'Relación EC Funcional y Servicio',
	'Class:lnkFunctionalCIToService/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Servicio',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => 'Servicio',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Servicio',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => 'Servicio',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'EC',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => 'Elemento de Configuración',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'EC',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => 'Elemento de Configuración',
));

//
// Class: FunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contratos',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Contratos',
	'Class:FunctionalCI/Attribute:services_list' => 'Servicios',
	'Class:FunctionalCI/Attribute:services_list+' => 'Servicios',
));

//
// Class: Document
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Document/Attribute:contracts_list' => 'Contratos',
	'Class:Document/Attribute:contracts_list+' => 'Contratos Referenciados con este Documento',
	'Class:Document/Attribute:services_list' => 'Servicios',
	'Class:Document/Attribute:services_list+' => 'Servicios Referenciados con este Documento',
));
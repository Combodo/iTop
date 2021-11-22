<?php
// Copyright (C) 2010-2021 Combodo SARL
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
* @copyright   Copyright (C) 2010-2021 Combodo SARL
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
// Class: lnkFunctionalCIToTicket
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToTicket' => 'Relación EC Funcional y Ticket',
	'Class:lnkFunctionalCIToTicket+' => 'Relación EC Funcional y Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Ref.',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => 'Ref.',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Título del Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'EC',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Agregado Manualmente',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Calculado',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'No impactado',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Relación EC Funcional y Contrato con Proveedor',
	'Class:lnkFunctionalCIToProviderContract+' => 'Relación EC Funcional y Contrato con Proveedor',
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
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Tickets relacionados con este EC',
));

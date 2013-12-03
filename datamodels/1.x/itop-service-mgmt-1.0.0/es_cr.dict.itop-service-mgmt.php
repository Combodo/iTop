<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+


Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
'Menu:ServiceManagement' => 'Gestión de Servicios',
'Menu:ServiceManagement+' => 'Visión General de Gestión de Servicios',
'Menu:Service:Overview' => 'Visión General',
'Menu:Service:Overview+' => '',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratos por Nivel de Servicio',
'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratos por Estado',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratos Finalizando en menos de 30 días',

'Menu:ServiceType' => 'Tipos de Servicios',
'Menu:ServiceType+' => 'Tipos de Servicios',
'Menu:ProviderContract' => 'Contratos del Proveedor',
'Menu:ProviderContract+' => 'Contratos del Proveedor',
'Menu:CustomerContract' => 'Contratos del Cliente',
'Menu:CustomerContract+' => 'Contratos del Cliente',
'Menu:ServiceSubcategory' => 'Subcategorías de Servicio',
'Menu:ServiceSubcategory+' => 'Subcategorías de Servicio',
'Menu:Service' => 'Servicios',
'Menu:Service+' => 'Servicios',
'Menu:SLA' => 'SLAs',
'Menu:SLA+' => 'Acuerdos de Nivel de Servicio',
'Menu:SLT' => 'SLTs',
'Menu:SLT+' => 'Destinatarios de Nivel de Servicio',

));


/*
	'UI:ServiceManagementMenu' => 'Gestion des Services',
	'UI:ServiceManagementMenu+' => 'Gestion des Services',
	'UI:ServiceManagementMenu:Title' => 'Résumé des services & contrats',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contrats par niveau de service',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contrats par état',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contrats se terminant dans moins de 30 jours',
*/


//
// Class: Contract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Contract' => 'Contrato',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Nombre',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:description' => 'Descripción',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Fecha de Inicio',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Fecha de finalización',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Costo',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Moneda del Costo',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Dólares',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => 'Dólares de E.U.A',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euros',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:cost_currency/Value:crcolones' => 'Colones',
	'Class:Contract/Attribute:cost_currency/Value:crcolones+' => 'Colones Costa Rica',
	'Class:Contract/Attribute:cost_unit' => 'Unidad de Costo',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Frecuencia de Facturación',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:contact_list' => 'Contactos',
	'Class:Contract/Attribute:contact_list+' => 'Contactos relacionados con el contrato',
	'Class:Contract/Attribute:document_list' => 'Documentos',
	'Class:Contract/Attribute:document_list+' => 'Documentos adjuntos al contrato',
	'Class:Contract/Attribute:ci_list' => 'I.C.s',
	'Class:Contract/Attribute:ci_list+' => 'I.C.s soportados por el contrato',
	'Class:Contract/Attribute:finalclass' => 'Clase',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ProviderContract' => 'Contrato del Proveedor',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:provider_id' => 'Proveedor',
	'Class:ProviderContract/Attribute:provider_id+' => '',
	'Class:ProviderContract/Attribute:provider_name' => 'Nombre del Proveedor',
	'Class:ProviderContract/Attribute:provider_name+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Acuerdo de Nivel de Servicio',
	'Class:ProviderContract/Attribute:coverage' => 'Cobertura',
	'Class:ProviderContract/Attribute:coverage+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CustomerContract' => 'Contrato del Cliente',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:org_id' => 'Cliente',
	'Class:CustomerContract/Attribute:org_id+' => '',
	'Class:CustomerContract/Attribute:org_name' => 'Nombre del Cliente',
	'Class:CustomerContract/Attribute:org_name+' => '',
	'Class:CustomerContract/Attribute:provider_id' => 'Proveedor',
	'Class:CustomerContract/Attribute:provider_id+' => '',
	'Class:CustomerContract/Attribute:provider_name' => 'Nombre del Proveedor',
	'Class:CustomerContract/Attribute:provider_name+' => '',
	'Class:CustomerContract/Attribute:support_team_id' => 'Equipo de Soporte',
	'Class:CustomerContract/Attribute:support_team_id+' => '',
	'Class:CustomerContract/Attribute:support_team_name' => 'Nombre del Equipo de Trabajo de Soporte',
	'Class:CustomerContract/Attribute:support_team_name+' => '',
	'Class:CustomerContract/Attribute:provider_list' => 'Proveedores',
	'Class:CustomerContract/Attribute:provider_list+' => '',
	'Class:CustomerContract/Attribute:sla_list' => 'SLAs',
	'Class:CustomerContract/Attribute:sla_list+' => 'Lista de SLAs relacionados con el contrato',
));

//
// Class: lnkContractToSLA
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContractToSLA' => 'Contrato/SLA',
	'Class:lnkContractToSLA+' => '',
	'Class:lnkContractToSLA/Attribute:contract_id' => 'Contrato',
	'Class:lnkContractToSLA/Attribute:contract_id+' => '',
	'Class:lnkContractToSLA/Attribute:contract_name' => 'Contrato',
	'Class:lnkContractToSLA/Attribute:contract_name+' => '',
	'Class:lnkContractToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_id+' => '',
	'Class:lnkContractToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_name+' => '',
	'Class:lnkContractToSLA/Attribute:coverage' => 'Cobertura',
	'Class:lnkContractToSLA/Attribute:coverage+' => '',
));

//
// Class: lnkContractToDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContractToDoc' => 'Contrato/Documentación',
	'Class:lnkContractToDoc+' => '',
	'Class:lnkContractToDoc/Attribute:contract_id' => 'Contrato',
	'Class:lnkContractToDoc/Attribute:contract_id+' => '',
	'Class:lnkContractToDoc/Attribute:contract_name' => 'Contrato',
	'Class:lnkContractToDoc/Attribute:contract_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDoc/Attribute:document_id+' => '',
	'Class:lnkContractToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkContractToDoc/Attribute:document_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_type' => 'Tipo de Documento',
	'Class:lnkContractToDoc/Attribute:document_type+' => '',
	'Class:lnkContractToDoc/Attribute:document_status' => 'Estado del Documento',
	'Class:lnkContractToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkContractToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContractToContact' => 'Contrato/Contacto',
	'Class:lnkContractToContact+' => '',
	'Class:lnkContractToContact/Attribute:contract_id' => 'Contrato',
	'Class:lnkContractToContact/Attribute:contract_id+' => '',
	'Class:lnkContractToContact/Attribute:contract_name' => 'Contrato',
	'Class:lnkContractToContact/Attribute:contract_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_id' => 'Contacto',
	'Class:lnkContractToContact/Attribute:contact_id+' => '',
	'Class:lnkContractToContact/Attribute:contact_name' => 'Contacto',
	'Class:lnkContractToContact/Attribute:contact_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_email' => 'Correo Electrónico del Contacto',
	'Class:lnkContractToContact/Attribute:contact_email+' => '',
	'Class:lnkContractToContact/Attribute:role' => 'Rol',
	'Class:lnkContractToContact/Attribute:role+' => '',
));

//
// Class: lnkContractToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContractToCI' => 'Contrato/I.C.s',
	'Class:lnkContractToCI+' => '',
	'Class:lnkContractToCI/Attribute:contract_id' => 'Contrato',
	'Class:lnkContractToCI/Attribute:contract_id+' => '',
	'Class:lnkContractToCI/Attribute:contract_name' => 'Contrato',
	'Class:lnkContractToCI/Attribute:contract_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_id' => 'I.C.s',
	'Class:lnkContractToCI/Attribute:ci_id+' => '',
	'Class:lnkContractToCI/Attribute:ci_name' => 'I.C.s',
	'Class:lnkContractToCI/Attribute:ci_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkContractToCI/Attribute:ci_status+' => '',
));

//
// Class: Service
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Service' => 'Servicio',
	'Class:Service+' => '',
	'Class:Service/Attribute:org_id' => 'Proveedor',
	'Class:Service/Attribute:org_id+' => 'Identificación del Proveedor',
	'Class:Service/Attribute:provider_name' => 'Proveedor',
	'Class:Service/Attribute:provider_name+' => 'Nombre del Proveedor',
	'Class:Service/Attribute:name' => 'Nombre',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:description' => 'Descripción',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:type' => 'Tipo',
	'Class:Service/Attribute:type+' => '',
	'Class:Service/Attribute:type/Value:IncidentManagement' => 'Gestión de Incidentes',
	'Class:Service/Attribute:type/Value:IncidentManagement+' => 'Gestión de Incidentes',
	'Class:Service/Attribute:type/Value:RequestManagement' => 'Gestión de Solicitudes',
	'Class:Service/Attribute:type/Value:RequestManagement+' => 'Gestión de Solicitudes',
	'Class:Service/Attribute:status' => 'Estado',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:design' => 'Diseño',
	'Class:Service/Attribute:status/Value:design+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Producción',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:subcategory_list' => 'Subcategorías de Servicio',
	'Class:Service/Attribute:subcategory_list+' => '',
	'Class:Service/Attribute:sla_list' => 'SLAs',
	'Class:Service/Attribute:sla_list+' => 'Lista de SLAs',
	'Class:Service/Attribute:document_list' => 'Documentos',
	'Class:Service/Attribute:document_list+' => 'Documentos adjuntos al servicio',
	'Class:Service/Attribute:contact_list' => 'Contactos',
	'Class:Service/Attribute:contact_list+' => 'Contactos que tienen participación en este servicio',
));

//
// Class: ServiceSubcategory
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ServiceSubcategory' => 'Subcategoría de Servicio',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Nombre',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Descripción',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:service_id+' => 'Identificación del Servicio',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:service_name+' => 'Nombre del Servicio',
));

//
// Class: SLA
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Nombre',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:service_id' => 'Servicio',
	'Class:SLA/Attribute:service_id+' => 'Identificación del Servicio',
	'Class:SLA/Attribute:service_name' => 'Servicio',
	'Class:SLA/Attribute:service_name+' => 'Nombre del Servicio',
	'Class:SLA/Attribute:slt_list' => 'SLTs',
	'Class:SLA/Attribute:slt_list+' => 'Lista de Umbrales de Nivel de Servicio (Tiempos de Respuesta)',
));

//
// Class: SLT
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Nombre',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:metric' => 'Métrica',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:TTO' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTO+' => 'Tiempo para Tomar el Control',
	'Class:SLT/Attribute:metric/Value:TTR' => 'TTR',
	'Class:SLT/Attribute:metric/Value:TTR+' => 'Tiempo de Respuesta',
	'Class:SLT/Attribute:ticket_priority' => 'Prioridad del Tiquete',
	'Class:SLT/Attribute:ticket_priority+' => '',
	'Class:SLT/Attribute:ticket_priority/Value:1' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:1+' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:2' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:2+' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:3' => '3',
	'Class:SLT/Attribute:ticket_priority/Value:3+' => '3',
	'Class:SLT/Attribute:value' => 'Valor',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:value_unit' => 'Unidad',
	'Class:SLT/Attribute:value_unit+' => '',
	'Class:SLT/Attribute:value_unit/Value:days' => 'días',
	'Class:SLT/Attribute:value_unit/Value:days+' => 'días',
	'Class:SLT/Attribute:value_unit/Value:hours' => 'horas',
	'Class:SLT/Attribute:value_unit/Value:hours+' => 'horas',
	'Class:SLT/Attribute:value_unit/Value:minutes' => 'minutos',
	'Class:SLT/Attribute:value_unit/Value:minutes+' => 'minutos',
	'Class:SLT/Attribute:sla_list' => 'SLAs',
	'Class:SLT/Attribute:sla_list+' => 'Acuerdos de Nivel de Servicio (SLAs) usando estos SLTs',
));

//
// Class: lnkSLTToSLA
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSLTToSLA' => 'SLT/SLA',
	'Class:lnkSLTToSLA+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_id+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_id' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_id+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_name' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_metric' => 'Métrica',
	'Class:lnkSLTToSLA/Attribute:slt_metric+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority' => 'Prioridad del Tiquete',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value' => 'Valor',
	'Class:lnkSLTToSLA/Attribute:slt_value+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit' => 'Unidad',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkServiceToDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkServiceToDoc' => 'Servicio/Documentación',
	'Class:lnkServiceToDoc+' => '',
	'Class:lnkServiceToDoc/Attribute:service_id' => 'Servicio',
	'Class:lnkServiceToDoc/Attribute:service_id+' => 'Identificación del Servicio',
	'Class:lnkServiceToDoc/Attribute:service_name' => 'Servicio',
	'Class:lnkServiceToDoc/Attribute:service_name+' => 'Nombre del Servicio',
	'Class:lnkServiceToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkServiceToDoc/Attribute:document_id+' => '',
	'Class:lnkServiceToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkServiceToDoc/Attribute:document_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_type' => 'Tipo de Documento',
	'Class:lnkServiceToDoc/Attribute:document_type+' => '',
	'Class:lnkServiceToDoc/Attribute:document_status' => 'Estado del Documento',
	'Class:lnkServiceToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkServiceToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkServiceToContact' => 'Service/Contact',
	'Class:lnkServiceToContact+' => '',
	'Class:lnkServiceToContact/Attribute:service_id' => 'Servicio',
	'Class:lnkServiceToContact/Attribute:service_id+' => 'Identificación del Servicio',
	'Class:lnkServiceToContact/Attribute:service_name' => 'Servicio',
	'Class:lnkServiceToContact/Attribute:service_name+' => 'Nombre del Servicio',
	'Class:lnkServiceToContact/Attribute:contact_id' => 'Contacto',
	'Class:lnkServiceToContact/Attribute:contact_id+' => 'Identificación del Contacto',
	'Class:lnkServiceToContact/Attribute:contact_name' => 'Contacto',
	'Class:lnkServiceToContact/Attribute:contact_name+' => 'Nombre del Contacto',
	'Class:lnkServiceToContact/Attribute:contact_email' => 'Correo Electrónico del Contacto',
	'Class:lnkServiceToContact/Attribute:contact_email+' => '',
	'Class:lnkServiceToContact/Attribute:role' => 'Rol',
	'Class:lnkServiceToContact/Attribute:role+' => '',
));

//
// Class: lnkServiceToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkServiceToCI' => 'Servicio/I.C.s',
	'Class:lnkServiceToCI+' => '',
	'Class:lnkServiceToCI/Attribute:service_id' => 'Servicio',
	'Class:lnkServiceToCI/Attribute:service_id+' => 'Identificador del Servicio',
	'Class:lnkServiceToCI/Attribute:service_name' => 'Servicio',
	'Class:lnkServiceToCI/Attribute:service_name+' => 'Nombre del Servicio',
	'Class:lnkServiceToCI/Attribute:ci_id' => 'I.C.s',
	'Class:lnkServiceToCI/Attribute:ci_id+' => 'Identificación de los I.C.s',
	'Class:lnkServiceToCI/Attribute:ci_name' => 'I.C.s',
	'Class:lnkServiceToCI/Attribute:ci_name+' => 'Nombre de los I.C.s',
	'Class:lnkServiceToCI/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkServiceToCI/Attribute:ci_status+' => '',
));


?>

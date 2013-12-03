<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com> 
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
'Menu:ServiceManagement' => 'Administración de Servicios',
'Menu:ServiceManagement+' => 'Administración de Servicios',
'Menu:Service:Overview' => 'Resumen de Servicios',
'Menu:Service:Overview+' => 'Resumen de Servicios',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contratos por Nivel de Servicio',
'UI-ServiceManagementMenu-ContractsByStatus' => 'Contratos por Estatus',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contratos Finalizando en menos de 30 días',

'Menu:ServiceType' => 'Tipos de Servicios',
'Menu:ServiceType+' => 'Tipos de Servicios',
'Menu:ProviderContract' => 'Contratos con Proveedores',
'Menu:ProviderContract+' => 'Contratos con Proveedores',
'Menu:CustomerContract' => 'Acuerdos con Clientes',
'Menu:CustomerContract+' => 'Acuerdos con Clientes',
'Menu:ServiceSubcategory' => 'Subcategorías de Servicio',
'Menu:ServiceSubcategory+' => 'Subcategorías de Servicio',
'Menu:Service' => 'Servicios',
'Menu:Service+' => 'Servicios',
'Menu:ServiceElement' => 'Elementos del Servicio',
'Menu:ServiceElement+' => 'Elementos del Servicio',
'Menu:SLA' => 'SLAs - Acuerdos de Nivel de Servicio',
'Menu:SLA+' => 'Acuerdos de Nivel de Servicio',
'Menu:SLT' => 'SLTs - Objetivos de Nivel de Servicio',
'Menu:SLT+' => 'Objetivos de Nivel de Servicio',
'Menu:DeliveryModel' => 'Modelos de Entrega',
'Menu:DeliveryModel+' => 'Modelos de Entrega',

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
// Class: Organization
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nombre del Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_name+' => 'Nombre del Modelo de Entrega',
));

//
// Class: ContractType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ContractType' => 'Tipo de Contrato',
	'Class:ContractType+' => 'Tipo de Contrato',
));


//
// Class: Contract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Contract' => 'Contrato',
	'Class:Contract+' => 'Contrato',
	'Class:Contract/Attribute:name' => 'Nombre',
	'Class:Contract/Attribute:name+' => 'Nombre del Contrato',
	'Class:Contract/Attribute:org_id' => 'Cliente',
	'Class:Contract/Attribute:org_id+' => 'Cliente',
	'Class:Contract/Attribute:organization_name' => 'Cliente',
	'Class:Contract/Attribute:organization_name+' => 'Cliente',
	'Class:Contract/Attribute:contacts_list' => 'Contactos',
	'Class:Contract/Attribute:contacts_list+' => 'Contactos',
	'Class:Contract/Attribute:documents_list' => 'Documentos',
	'Class:Contract/Attribute:documents_list+' => 'Documentos',
	'Class:Contract/Attribute:description' => 'Descripción',
	'Class:Contract/Attribute:description+' => 'Descripción',
	'Class:Contract/Attribute:start_date' => 'Fecha de Inicio',
	'Class:Contract/Attribute:start_date+' => 'Fecha de Incio',
	'Class:Contract/Attribute:end_date' => 'Fecha de Finalización',
	'Class:Contract/Attribute:end_date+' => 'Fecha de Finalización',
	'Class:Contract/Attribute:cost' => 'Costo',
	'Class:Contract/Attribute:cost+' => 'Costo',
	'Class:Contract/Attribute:cost_currency' => 'Moneda',
	'Class:Contract/Attribute:cost_currency+' => 'Moneda',
	'Class:Contract/Attribute:cost_currency/Value:Dolares' => 'Dólares',
	'Class:Contract/Attribute:cost_currency/Value:Dolares+' => 'Dólares de E.U.A',
	'Class:Contract/Attribute:cost_currency/Value:Pesos' => 'Pesos',
	'Class:Contract/Attribute:cost_currency/Value:Pesos+' => 'Pesos',
	'Class:Contract/Attribute:contracttype_id' => 'Tipo de Contrato',
	'Class:Contract/Attribute:contracttype_id+' => 'Tipo de Contrato',
	'Class:Contract/Attribute:contracttype_name' => 'Tipo de Contrato',
	'Class:Contract/Attribute:contracttype_name+' => 'Tipo de Contrato',
	'Class:Contract/Attribute:billing_frequency' => 'Frecuencia de Facturación',
	'Class:Contract/Attribute:billing_frequency+' => 'Frecuencia de Facturación',
	'Class:Contract/Attribute:cost_unit' => 'Unidad de Costo',
	'Class:Contract/Attribute:cost_unit+' => 'Unidad de Costo',
	'Class:Contract/Attribute:provider_id' => 'Proveedor',
	'Class:Contract/Attribute:provider_id+' => 'Proveedor',
	'Class:Contract/Attribute:provider_name' => 'Proveedor',
	'Class:Contract/Attribute:provider_name+' => 'Proveedor',
	'Class:Contract/Attribute:status' => 'Estatus',
	'Class:Contract/Attribute:status+' => 'Estatus',
	'Class:Contract/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:Contract/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Contract/Attribute:status/Value:production' => 'Productivo',
	'Class:Contract/Attribute:status/Value:production+' => 'Productivo',
	'Class:Contract/Attribute:finalclass' => 'Clase',
	'Class:Contract/Attribute:finalclass+' => 'Clase',
));

//
// Class: CustomerContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:CustomerContract' => 'Acuerdo con Cliente',
	'Class:CustomerContract+' => 'Acuerdo con Cliente',
	'Class:CustomerContract/Attribute:services_list' => 'Servicios',
	'Class:CustomerContract/Attribute:services_list+' => 'Servicios',
	'Class:CustomerContract/Attribute:functionalcis_list' => 'ECs',
	'Class:CustomerContract/Attribute:functionalcis_list+' => 'Elementos de Configuración',
	'Class:CustomerContract/Attribute:providercontracts_list' => 'Contratos con Proveedores',
	'Class:CustomerContract/Attribute:providercontracts_list+' => 'Contratos con Proveedores',
));

//
// Class: ProviderContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ProviderContract' => 'Contrato con Proveedor',
	'Class:ProviderContract+' => 'Contrato con Proveedor',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'ECs',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Elememtos de Configuración',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Acuerdo de Nivel de Servicio',
	'Class:ProviderContract/Attribute:coverage' => 'Horario de Servicio',
	'Class:ProviderContract/Attribute:coverage+' => 'Horario de Servicio',
));

//
// Class: lnkContactToContract
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContactToContract' => 'Relación Contacto y Contrato',
	'Class:lnkContactToContract+' => 'Relación Contacto y Contrato',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Contrato',
	'Class:lnkContactToContract/Attribute:contract_id+' => 'Contrato',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Contrato',
	'Class:lnkContactToContract/Attribute:contract_name+' => 'Contrato',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Contacto',
	'Class:lnkContactToContract/Attribute:contact_id+' => 'Contacto',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Contacto',
	'Class:lnkContactToContract/Attribute:contact_name+' => 'Contacto',
));

//
// Class: lnkContractToDocument
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContractToDocument' => 'Relación Contrato y Documento',
	'Class:lnkContractToDocument+' => 'Relación Contrato y Documento',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Contrato',
	'Class:lnkContractToDocument/Attribute:contract_id+' => 'Contrato',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Contrato',
	'Class:lnkContractToDocument/Attribute:contract_name+' => 'Contrato',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_id+' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Documento',
	'Class:lnkContractToDocument/Attribute:document_name+' => 'Documento',
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
// Class: ServiceFamily
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ServiceFamily' => 'Familia de Servicios',
	'Class:ServiceFamily+' => 'Familia de Servicios',
	'Class:ServiceFamily/Attribute:name' => 'Nombre',
	'Class:ServiceFamily/Attribute:name+' => 'Nombre de la Familia de Servicios',
	'Class:ServiceFamily/Attribute:services_list' => 'Servicios',
	'Class:ServiceFamily/Attribute:services_list+' => 'Servicios',
));

//
// Class: Service
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Service' => 'Servicio',
	'Class:Service+' => 'Servicio',
	'Class:Service/Attribute:name' => 'Nombre',
	'Class:Service/Attribute:name+' => 'Nombre del Servicio',
	'Class:Service/Attribute:org_id' => 'Proveedor',
	'Class:Service/Attribute:org_id+' => 'Proveedor',
	'Class:Service/Attribute:organization_name' => 'Proveedor',
	'Class:Service/Attribute:organization_name+' => 'Proveedor',
	'Class:Service/Attribute:description' => 'Descripción',
	'Class:Service/Attribute:description+' => 'Descripción',
	'Class:Service/Attribute:documents_list' => 'Documentos',
	'Class:Service/Attribute:documents_list+' => 'Documentos',
	'Class:Service/Attribute:contacts_list' => 'Contactos',
	'Class:Service/Attribute:contacts_list+' => 'Contactos',
	'Class:Service/Attribute:status' => 'Estatus',
	'Class:Service/Attribute:status+' => 'Estatus',
	'Class:Service/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:Service/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:Service/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Service/Attribute:status/Value:production' => 'Productivo',
	'Class:Service/Attribute:status/Value:production+' => 'Productivo',
	'Class:Service/Attribute:customercontracts_list' => 'Acuerdos con Clientes',
	'Class:Service/Attribute:customercontracts_list+' => 'Acuerdos con Clientes',
	'Class:Service/Attribute:servicesubcategories_list' => 'Subcategorias de Servicio',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Subcategorias de Servicio',
));

//
// Class: lnkDocumentToService
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDocumentToService' => 'Relación Documento y Servicio',
	'Class:lnkDocumentToService+' => 'Relación Documento y Servicio',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Servicio',
	'Class:lnkDocumentToService/Attribute:service_id+' => 'Servicio',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Servicio',
	'Class:lnkDocumentToService/Attribute:service_name+' => 'Servicio',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToService/Attribute:document_name+' => 'Documento',
));

//
// Class: lnkContactToService
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContactToService' => 'Relación Contacto y Servicio',
	'Class:lnkContactToService+' => 'Relación Contacto y Servicio',
	'Class:lnkContactToService/Attribute:service_id' => 'Servicio',
	'Class:lnkContactToService/Attribute:service_id+' => 'Servicio',
	'Class:lnkContactToService/Attribute:service_name' => 'Servicio',
	'Class:lnkContactToService/Attribute:service_name+' => 'Servicio',
	'Class:lnkContactToService/Attribute:contact_id' => 'Contacto',
	'Class:lnkContactToService/Attribute:contact_id+' => 'Contacto',
	'Class:lnkContactToService/Attribute:contact_name' => 'Contacto',
	'Class:lnkContactToService/Attribute:contact_name+' => 'Contacto',
));

//
// Class: ServiceSubcategory
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ServiceSubcategory' => 'Subcategoría',
	'Class:ServiceSubcategory+' => 'Subcategoría',
	'Class:ServiceSubcategory/Attribute:name' => 'Nombre',
	'Class:ServiceSubcategory/Attribute:name+' => 'Nombre de Subcategoría',
	'Class:ServiceSubcategory/Attribute:description' => 'Descripción',
	'Class:ServiceSubcategory/Attribute:description+' => 'Descripción',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:service_id+' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:service_name+' => 'Servicio',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Tipo de Reporte',
	'Class:ServiceSubcategory/Attribute:request_type+' => 'Tipo de Reporte',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incidente',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'Incidente',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Requerimiento de Servicio',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'Requerimiento de Servicio',
	'Class:ServiceSubcategory/Attribute:status' => 'Estatus',
	'Class:ServiceSubcategory/Attribute:status+' => 'Estatus',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Productivo',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'Productivo',
));

//
// Class: SLA
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SLA' => 'SLA - Acuerdo de Nivel de Servicio',
	'Class:SLA+' => 'SLA - Acuerdo de Nivel de Servicio',
	'Class:SLA/Attribute:name' => 'Nombre',
	'Class:SLA/Attribute:name+' => 'Nombre de SLA',
	'Class:SLA/Attribute:description' => 'Descripción',
	'Class:SLA/Attribute:description+' => 'Descripción',
	'Class:SLA/Attribute:org_id' => 'Proveedor',
	'Class:SLA/Attribute:org_id+' => 'Proveedor',
	'Class:SLA/Attribute:organization_name' => 'Proveedor',
	'Class:SLA/Attribute:organization_name+' => 'Proveedor',
	'Class:SLA/Attribute:slts_list' => 'SLTs - Objetivos de Nivel de Servicio',
	'Class:SLA/Attribute:slts_list+' => 'Objetivos de Nivel de Servicio',
	'Class:SLA/Attribute:customercontracts_list' => 'Acuerdos con Clientes',
	'Class:SLA/Attribute:customercontracts_list+' => 'Acuerdos con Clientes',
));

//
// Class: SLT
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SLT' => 'SLT - Objetivos de Nivel de Servicio',
	'Class:SLT+' => 'SLT - Objetivos de Nivel de Servicio',
	'Class:SLT/Attribute:name' => 'Nombre',
	'Class:SLT/Attribute:name+' => 'Nombre de SLT',
	'Class:SLT/Attribute:priority' => 'Prioridad',
	'Class:SLT/Attribute:priority+' => 'Prioridad',
	'Class:SLT/Attribute:priority/Value:1' => 'Crítico',
	'Class:SLT/Attribute:priority/Value:1+' => 'Crítico',
	'Class:SLT/Attribute:priority/Value:2' => 'Alto',
	'Class:SLT/Attribute:priority/Value:2+' => 'Alto',
	'Class:SLT/Attribute:priority/Value:3' => 'Medio',
	'Class:SLT/Attribute:priority/Value:3+' => 'Medio',
	'Class:SLT/Attribute:priority/Value:4' => 'Bajo',
	'Class:SLT/Attribute:priority/Value:4+' => 'Bajo',
	'Class:SLT/Attribute:request_type' => 'Tipo de Reporte',
	'Class:SLT/Attribute:request_type+' => 'Tipo de Reporte',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incidente',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'Incidente',
	'Class:SLT/Attribute:request_type/Value:servicerequest' => 'Requerimiento de Servicio',
	'Class:SLT/Attribute:request_type/Value:servicerequest+' => 'Requerimiento de Servicio',
	'Class:SLT/Attribute:metric' => 'Métrica',
	'Class:SLT/Attribute:metric+' => 'Métrica',
	'Class:SLT/Attribute:metric/Value:tto' => 'TDA - Tiempo de Asignación',
	'Class:SLT/Attribute:metric/Value:tto+' => 'Tiempo de Asignación',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TDS - Tiempo de Solución',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'Tiempo de Solución',
	'Class:SLT/Attribute:value' => 'Valor',
	'Class:SLT/Attribute:value+' => 'Valor',
	'Class:SLT/Attribute:unit' => 'Unidad',
	'Class:SLT/Attribute:unit+' => 'Unidad',
	'Class:SLT/Attribute:unit/Value:hours' => 'Horas',
	'Class:SLT/Attribute:unit/Value:hours+' => 'Horas',
	'Class:SLT/Attribute:unit/Value:minutes' => 'Minutos',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'Minutos',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSLAToSLT' => 'Relación SLA y SLT',
	'Class:lnkSLAToSLT+' => 'Relación SLA y SLT',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => 'SLT',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkCustomerContractToService' => 'Relación Acuerdo con Cliente y Servicio',
	'Class:lnkCustomerContractToService+' => 'Relación Acuerdo con Cliente y Servicio',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Servicio',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => 'Servicio',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Servicio',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => 'Servicio',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => 'SLA',
));

//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('ES CR', 'ESpanish', 'Español, Castellano', array(
	'Class:lnkCustomerContractToProviderContract' => 'Relación Acuerdo con Cliente y Contrato con Proveedor',
	'Class:lnkCustomerContractToProviderContract+' => 'Relación Acuerdo con Cliente y Contrato con Proveedor',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_id+' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:customercontract_name+' =>'Acuerdo con Cliente',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id' => 'Contrato con Proveedor',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_id+' => 'Contrato con Proveedor',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name' => 'Contrato con Proveedor',
	'Class:lnkCustomerContractToProviderContract/Attribute:providercontract_name+' => 'Contrato con Proveedor',
));

//
// Class: lnkCustomerContractToFunctionalCI
//

Dict::Add('ES CR', 'ESpanish', 'Español, Castellano', array(
	'Class:lnkCustomerContractToFunctionalCI' => 'Relación Acuerdo con Cliente y EC Funcional',
	'Class:lnkCustomerContractToFunctionalCI+' => 'Relación Acuerdo con Cliente y EC Funcional',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_id+' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:customercontract_name+' => 'Acuerdo con Cliente',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id' => 'EC',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_id+' => 'Elemento de Configuración',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name' => 'Elemento de Configuración',
	'Class:lnkCustomerContractToFunctionalCI/Attribute:functionalci_name+' => 'Elemento de Configuración',
));

//
// Class: DeliveryModel
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DeliveryModel' => 'Modelo de Entrega',
	'Class:DeliveryModel+' => 'Modelo de Entrega',
	'Class:DeliveryModel/Attribute:name' => 'Nombre',
	'Class:DeliveryModel/Attribute:name+' => 'Nombre de Modelo de Entrega',
	'Class:DeliveryModel/Attribute:org_id' => 'Organización',
	'Class:DeliveryModel/Attribute:org_id+' => 'Organización',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organización',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Organización',
	'Class:DeliveryModel/Attribute:description' => 'Descripción',
	'Class:DeliveryModel/Attribute:description+' => 'Descripción',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Contactos',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Contactos',
	'Class:DeliveryModel/Attribute:customers_list' => 'Clientes',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Clientes',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDeliveryModelToContact' => 'Relación Modelo de Entrega y Contacto',
	'Class:lnkDeliveryModelToContact+' => 'Relación Modelo de Entrega y Contacto',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Modelo de Entrega',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => 'Modelo de Entrega',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Modelo de Entrega',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => 'Modelo de Entrega',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Contacto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => 'Contacto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Contacto',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => 'Contacto',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => 'Rol',
));

?>

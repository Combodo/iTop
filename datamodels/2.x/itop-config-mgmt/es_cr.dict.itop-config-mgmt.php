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

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Relation:impacts/Description' => 'Elementos Impactados por',
	'Relation:impacts/VerbUp' => 'Impacto...',
	'Relation:impacts/VerbDown' => 'Elementos Impactados por...',
	'Relation:depends on/Description' => 'Elementos de los cuales depende',
	'Relation:depends on/VerbUp' => 'Depende de...',
	'Relation:depends on/VerbDown' => 'Impactos...',
));


// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//


//
// Class: Organization
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Organization' => 'Organización',
	'Class:Organization+' => 'Organización',
	'Class:Organization/Attribute:name' => 'Nombre',
	'Class:Organization/Attribute:name+' => 'Nombre de la Organización',
	'Class:Organization/Attribute:code' => 'Código',
	'Class:Organization/Attribute:code+' => 'Código de Organización  (RFC, DUNS, Siret, etc.)',
	'Class:Organization/Attribute:status' => 'Estatus',
	'Class:Organization/Attribute:status+' => 'Estatus',
	'Class:Organization/Attribute:status/Value:active' => 'Activo',
	'Class:Organization/Attribute:status/Value:active+' => 'Activo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Organization/Attribute:parent_id' => 'Padre',
	'Class:Organization/Attribute:parent_id+' => 'Organización Padre',
	'Class:Organization/Attribute:parent_name' => 'Organización Padre',
	'Class:Organization/Attribute:parent_name+' => 'Nombre de la Organización Padre',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_id+' => 'Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nombre del Modelo de Entrega',
	'Class:Organization/Attribute:deliverymodel_name+' => 'Nombre del Modelo de Entrega',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Organización Padre',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Organización Padre',
));

//
// Class: Location
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Location' => 'Localidad',
	'Class:Location+' => 'Cualquier Tipo de Localidad: Región, País, Ciudad, Sitio, Edificio, Piso, Cuarto, Rack,...',
	'Class:Location/Attribute:name' => 'Nombre',
	'Class:Location/Attribute:name+' => 'Nombre de Localidad',
	'Class:Location/Attribute:status' => 'Estatus',
	'Class:Location/Attribute:status+' => 'Estatus de Localidad',
	'Class:Location/Attribute:status/Value:active' => 'Activo',
	'Class:Location/Attribute:status/Value:active+' => 'Activo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Location/Attribute:org_id' => 'Organización',
	'Class:Location/Attribute:org_id+' => 'Organización',
	'Class:Location/Attribute:org_name' => 'Nombre de la Organización',
	'Class:Location/Attribute:org_name+' => 'Nombre de la Organización',
	'Class:Location/Attribute:address' => 'Dirección',
	'Class:Location/Attribute:address+' => 'Dirección Postal',
	'Class:Location/Attribute:postal_code' => 'Código Postal',
	'Class:Location/Attribute:postal_code+' => 'ZIP/Código Postal',
	'Class:Location/Attribute:city' => 'Ciudad',
	'Class:Location/Attribute:city+' => 'Ciudad',
	'Class:Location/Attribute:country' => 'País',
	'Class:Location/Attribute:country+' => 'País',
	'Class:Location/Attribute:physicaldevice_list' => 'Dispositivos',
	'Class:Location/Attribute:physicaldevice_list+' => 'Dispositivos',
	'Class:Location/Attribute:person_list' => 'Contactos',
	'Class:Location/Attribute:person_list+' => 'Contactos',
));

//
// Class: Contact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Contact' => 'Contacto',
	'Class:Contact+' => 'Contacto',
	'Class:Contact/Attribute:name' => 'Nombre',
	'Class:Contact/Attribute:name+' => 'Nombre del Contacto',
	'Class:Contact/Attribute:status' => 'Estatus',
	'Class:Contact/Attribute:status+' => 'Estatus',
	'Class:Contact/Attribute:status/Value:active' => 'Activo',
	'Class:Contact/Attribute:status/Value:active+' => 'Activo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Contact/Attribute:org_id' => 'Organización',
	'Class:Contact/Attribute:org_id+' => 'Organización',
	'Class:Contact/Attribute:org_name' => 'Organización',
	'Class:Contact/Attribute:org_name+' => 'Organización',
	'Class:Contact/Attribute:email' => 'Correo Electrónico',
	'Class:Contact/Attribute:email+' => 'Correo Electrónico',
	'Class:Contact/Attribute:phone' => 'Teléfono',
	'Class:Contact/Attribute:phone+' => 'Teléfono',
	'Class:Contact/Attribute:notify' => 'Notificación',
	'Class:Contact/Attribute:notify+' => 'Notificación',
	'Class:Contact/Attribute:notify/Value:no' => 'No',
	'Class:Contact/Attribute:notify/Value:no+' => 'No',
	'Class:Contact/Attribute:notify/Value:yes' => 'Si',
	'Class:Contact/Attribute:notify/Value:yes+' => 'Si',
	'Class:Contact/Attribute:function' => 'Función',
	'Class:Contact/Attribute:function+' => 'Función',
	'Class:Contact/Attribute:cis_list' => 'ECs',
	'Class:Contact/Attribute:cis_list+' => 'Elementos de Configuración relacionados con el contacto',
	'Class:Contact/Attribute:finalclass' => 'Clase',
	'Class:Contact/Attribute:finalclass+' => 'Clase',
));

//
// Class: Person
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Person' => 'Persona',
	'Class:Person+' => 'Persona',
	'Class:Person/Attribute:name' => 'Nombre',
	'Class:Person/Attribute:name+' => 'Nombre de la Persona',
	'Class:Person/Attribute:first_name' => 'Apellidos',
	'Class:Person/Attribute:first_name+' => 'Apellidos',
	'Class:Person/Attribute:employee_number' => 'Número de Empleado',
	'Class:Person/Attribute:employee_number+' => 'Número de Empleado',
	'Class:Person/Attribute:mobile_phone' => 'Móvil',
	'Class:Person/Attribute:mobile_phone+' => 'Móvil',
	'Class:Person/Attribute:location_id' => 'Localidad',
	'Class:Person/Attribute:location_id+' => 'Localidad',
	'Class:Person/Attribute:location_name' => 'Nombre de Localidad',
	'Class:Person/Attribute:location_name+' => 'Nombre de Localidad',
	'Class:Person/Attribute:manager_id' => 'Jefe',
	'Class:Person/Attribute:manager_id+' => 'Jefe',
	'Class:Person/Attribute:manager_name' => 'Nombre del Jefe',
	'Class:Person/Attribute:manager_name+' => 'Nombre del Jefe',
	'Class:Person/Attribute:team_list' => 'Grupos',
	'Class:Person/Attribute:team_list+' => 'Grupos',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'Tickets',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Nombre del Jefe',
	'Class:Person/Attribute:manager_id_friendlyname+' => 'Nombre del Jefe',
));

//
// Class: Team
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Team' => 'Grupo de Trabajo',
	'Class:Team+' => 'Grupo de Trabajo',
	'Class:Team/Attribute:persons_list' => 'Miembros',
	'Class:Team/Attribute:persons_list+' => 'Miembros',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => 'Tickets',
));

//
// Class: Document
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => 'Documento',
	'Class:Document/Attribute:name' => 'Nombre',
	'Class:Document/Attribute:name+' => 'Nombre del Documento',
	'Class:Document/Attribute:org_id' => 'Organización',
	'Class:Document/Attribute:org_id+' => 'Organización',
	'Class:Document/Attribute:org_name' => 'Nombre de la Organización',
	'Class:Document/Attribute:org_name+' => 'Nombre de la Organización',
	'Class:Document/Attribute:documenttype_id' => 'Tipo de Documento',
	'Class:Document/Attribute:documenttype_id+' => 'Tipo de Documento',
	'Class:Document/Attribute:documenttype_name' => 'Tipo de Documento',
	'Class:Document/Attribute:documenttype_name+' => 'Tipo de Documento',
	'Class:Document/Attribute:version' => 'Versión',
	'Class:Document/Attribute:version+' => 'Versión',
	'Class:Document/Attribute:description' => 'Descripción',
	'Class:Document/Attribute:description+' => 'Descripción',
	'Class:Document/Attribute:status' => 'Estatus',
	'Class:Document/Attribute:status+' => 'Estatus',
	'Class:Document/Attribute:status/Value:draft' => 'Borrador de Documento',
	'Class:Document/Attribute:status/Value:draft+' => 'Borrador de Documento',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:published' => 'Publicado',
	'Class:Document/Attribute:status/Value:published+' => 'Publicado',
	'Class:Document/Attribute:cis_list' => 'ECs',
	'Class:Document/Attribute:cis_list+' => 'Elementos de Configuración referenciados en este documento',
	'Class:Document/Attribute:contracts_list' => 'Contratos',
	'Class:Document/Attribute:contracts_list+' => 'Contratos Referenciados con este Documento',
	'Class:Document/Attribute:services_list' => 'Servicios',
	'Class:Document/Attribute:services_list+' => 'Servicios Referenciados con este Documento',
	'Class:Document/Attribute:finalclass' => 'Tipo de Documento',
	'Class:Document/Attribute:finalclass+' => 'Tipo de Documento',
));

//
// Class: DocumentFile
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DocumentFile' => 'Documento de Archivo',
	'Class:DocumentFileDoc+' => 'Documento de Archivo',
	'Class:DocumentFile/Attribute:file' => 'Archivo',
	'Class:DocumentFile/Attribute:file+' => 'Archivo',
));

//
// Class: DocumentNote
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DocumentNote' => 'Nota',
	'Class:DocumentNote+' => 'Nota',
	'Class:DocumentNote/Attribute:text' => 'Texto',
	'Class:DocumentNote/Attribute:text+' => 'Texto',
));

//
// Class: DocumentWeb
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DocumentWeb' => 'Documento Web',
	'Class:DocumentWeb+' => 'Documento disponible en otro servidor Web',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => 'URL de Internet',
));

//
// Class: FunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FunctionalCI' => 'EC Funcional',
	'Class:FunctionalCI+' => 'Elemento de Configuración Funcional',
	'Class:FunctionalCI/Attribute:name' => 'Nombre',
	'Class:FunctionalCI/Attribute:name+' => 'Nombre del Elemento de Configuración',
	'Class:FunctionalCI/Attribute:description' => 'Descripción',
	'Class:FunctionalCI/Attribute:description+' => 'Descripción',
	'Class:FunctionalCI/Attribute:org_id' => 'Organización',
	'Class:FunctionalCI/Attribute:org_id+' => 'Organización',
	'Class:FunctionalCI/Attribute:organization_name' => 'Nombre de Organización',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Nombre de Organización',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Criticidad para el Negocio',
	'Class:FunctionalCI/Attribute:business_criticity+' => 'Qué tan crítico es para el negocio este elemento',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Alto',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'Alto Grado de Importancia',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Bajo',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'Bajo Grado de Importancia',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Medio',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'Grado Medio de Importancia',
	'Class:FunctionalCI/Attribute:move2production' => 'Puesto en Producción',
	'Class:FunctionalCI/Attribute:move2production+' => 'Puesto en Producción',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contactos',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Contactos para este EC',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documentos',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Documentación para este EC',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Soluciones Aplicativa',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Soluciones Aplicativa',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contratos',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Contratos',
	'Class:FunctionalCI/Attribute:services_list' => 'Servicios',
	'Class:FunctionalCI/Attribute:services_list+' => 'Servicios',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Software',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Tickets relacionados con este EC',
	'Class:FunctionalCI/Attribute:finalclass' => 'Clase',
	'Class:FunctionalCI/Attribute:finalclass+' => 'Clase',
));

//
// Class: PhysicalDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PhysicalDevice' => 'Dispositivo Físico',
	'Class:PhysicalDevice+' => 'Dispositivo Físico',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Número de Serie',
	'Class:PhysicalDevice/Attribute:serialnumber+' => 'Número de Serie',
	'Class:PhysicalDevice/Attribute:location_id' => 'Localidad',
	'Class:PhysicalDevice/Attribute:location_id+' => 'Localidad',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nombre Localidad',
	'Class:PhysicalDevice/Attribute:location_name+' => 'Nombre Localidad',
	'Class:PhysicalDevice/Attribute:status' => 'Estatus',
	'Class:PhysicalDevice/Attribute:status+' => 'Estatus',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Productivo',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Productivo',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'En Inventario',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'En Imventario',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Marca',
	'Class:PhysicalDevice/Attribute:brand_id+' => 'Marca',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Nombre Marca',
	'Class:PhysicalDevice/Attribute:brand_name+' => 'Nombre Marca',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modelo',
	'Class:PhysicalDevice/Attribute:model_id+' => 'Modelo',
	'Class:PhysicalDevice/Attribute:model_name' => 'Nombre Modelo',
	'Class:PhysicalDevice/Attribute:model_name+' => 'Nombre Modelo',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Número Activo',
	'Class:PhysicalDevice/Attribute:asset_number+' => 'Número Activo',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Fecha de Compra',
	'Class:PhysicalDevice/Attribute:purchase_date+' => 'Fecha de Compra',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Vencimiento de Garantía',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => 'Vencimiento de Garantía',
));

//
// Class: Rack
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Unidades de Rack',
	'Class:Rack/Attribute:nb_u+' => 'Unidades de Rack',
	'Class:Rack/Attribute:device_list' => 'Dispositivos',
	'Class:Rack/Attribute:device_list+' => 'Dispositivos',
	'Class:Rack/Attribute:enclosure_list' => 'Enclosures',
	'Class:Rack/Attribute:enclosure_list+' => 'Enclosures',
));

//
// Class: TelephonyCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TelephonyCI' => 'EC Telefónico',
	'Class:TelephonyCI+' => 'EC Telefónico',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Número Telefónico',
	'Class:TelephonyCI/Attribute:phonenumber+' => 'Número Telefónico',
));

//
// Class: Phone
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Phone' => 'Teléfono',
	'Class:Phone+' => 'Teléfono',
));

//
// Class: MobilePhone
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:MobilePhone' => 'Teléfono Móvil',
	'Class:MobilePhone+' => 'Teléfono Móvil',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => 'IMEI',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => 'PIN',
));

//
// Class: IpPhone
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:IPPhone' => 'Teléfono IP',
	'Class:IPPhone+' => 'Teléfono IP',
));

//
// Class: Tablet
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Tablet' => 'Tableta',
	'Class:Tablet+' => 'Tableta',
));

//
// Class: ConnectableCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ConnectableCI' => 'EC Conectable',
	'Class:ConnectableCI+' => 'EC Físico',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Dispositivos de Red',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Dispositivos de Red',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interfases de Red',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Interfases de Red',
));

//
// Class: DatacenterDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DatacenterDevice' => 'Dispositivos de Centro de Datos',
	'Class:DatacenterDevice+' => 'Dispositivos de Centro de Datos',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Nombre Rack',
	'Class:DatacenterDevice/Attribute:rack_name+' => 'Nombre Rack',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Nombre Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => 'Nombre Enclosure',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Unidades de Rack',
	'Class:DatacenterDevice/Attribute:nb_u+' => 'Unidades de Rack',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP',
	'Class:DatacenterDevice/Attribute:managementip+' => 'IP',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Fuente de Poder A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => 'Fuente de Poder A',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Fuente de Poder A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => 'Fuente de Poder A',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Fuente de Poder B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => 'Fuente de Poder B',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Fuente de Poder B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => 'Fuente de Poder B',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Puertos de Fibra Óptica',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Puertos de Fibra Óptica',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'SANs',
));

//
// Class: NetworkDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NetworkDevice' => 'Dispositivo de Red',
	'Class:NetworkDevice+' => 'Dispositivo de Red',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Tipo de Red',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => 'Tipo de Red',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Tipo de Red',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => 'Tipo de Red',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Dispositivos',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Dispositivos',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Versión IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => 'Versión IOS',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Versión IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => 'Versión IOS',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => 'RAM',
));

//
// Class: Server
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Server' => 'Servidor',
	'Class:Server+' => 'Servidor',
	'Class:Server/Attribute:osfamily_id' => 'Familia de SO',
	'Class:Server/Attribute:osfamily_id+' => 'Familia de SO',
	'Class:Server/Attribute:osfamily_name' => 'Familia de SO',
	'Class:Server/Attribute:osfamily_name+' => 'Familia de SO',
	'Class:Server/Attribute:osversion_id' => 'Versión de SO',
	'Class:Server/Attribute:osversion_id+' => 'Versión de SO',
	'Class:Server/Attribute:osversion_name' => 'Versión de SO',
	'Class:Server/Attribute:osversion_name+' => 'Versión de SO',
	'Class:Server/Attribute:oslicence_id' => 'Licencia de SO',
	'Class:Server/Attribute:oslicence_id+' => 'Licencia de SO',
	'Class:Server/Attribute:oslicence_name' => 'Licencia de SO',
	'Class:Server/Attribute:oslicence_name+' => 'Licencia de SO',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => 'CPU',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => 'RAM',
	'Class:Server/Attribute:logicalvolumes_list' => 'Volumenes Lógicos',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Volumenes Lógicos',
));

//
// Class: StorageSystem
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:StorageSystem' => 'Sistema de Almacenamiento',
	'Class:StorageSystem+' => 'Sistema de Almacenamiento',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Volumenes Lógicos',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Volumenes Lógicos',
));

//
// Class: SANSwitch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SANSwitch' => 'Switch de SAN',
	'Class:SANSwitch+' => 'Switch de SAN',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Dispositivos',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Dispositivos',
));

//
// Class: TapeLibrary
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:TapeLibrary' => 'Libreria de Cintas',
	'Class:TapeLibrary+' => 'Libreria de Cintas',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Cintas',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Cintas',
));

//
// Class: NAS
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => 'NAS',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Filesystems',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Filesystems',
));

//
// Class: PC
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PC' => 'PC/Laptop',
	'Class:PC+' => 'PC/Laptop',
	'Class:PC/Attribute:osfamily_id' => 'Familia de SO',
	'Class:PC/Attribute:osfamily_id+' => 'Familia de SO',
	'Class:PC/Attribute:osfamily_name' => 'Familia de SO',
	'Class:PC/Attribute:osfamily_name+' => 'Familia de SO',
	'Class:PC/Attribute:osversion_id' => 'Versión de SO',
	'Class:PC/Attribute:osversion_id+' => 'Versión de SO',
	'Class:PC/Attribute:osversion_name' => 'Versión de SO',
	'Class:PC/Attribute:osversion_name+' => 'Versión de SO',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => 'Tipo de CPU',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => 'Memoria RAM',
	'Class:PC/Attribute:type' => 'Tipo',
	'Class:PC/Attribute:type+' => 'Tipo',
	'Class:PC/Attribute:type/Value:desktop' => 'Escritorio',
	'Class:PC/Attribute:type/Value:desktop+' => 'Escritorio',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'Laptop',
));

//
// Class: Printer
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Printer' => 'Impresora',
	'Class:Printer+' => 'Impresora',
));

//
// Class: PowerConnection
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PowerConnection' => 'Conexión Eléctrica',
	'Class:PowerConnection+' => 'Conexión Eléctrica',
));

//
// Class: PowerSource
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PowerSource' => 'Fuente de Poder',
	'Class:PowerSource+' => 'Fuente de Poder',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'PDUs',
));

//
// Class: PDU
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => 'PDU',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => 'Rack',
	'Class:PDU/Attribute:rack_name' => 'Nombre Rack',
	'Class:PDU/Attribute:rack_name+' => 'Nombre Rack',
	'Class:PDU/Attribute:powerstart_id' => 'Conector de Poder',
	'Class:PDU/Attribute:powerstart_id+' => 'Conector de Poder',
	'Class:PDU/Attribute:powerstart_name' => 'Conector de Poder',
	'Class:PDU/Attribute:powerstart_name+' => 'Conector de Poder',
));

//
// Class: Peripheral
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Peripheral' => 'Periférico',
	'Class:Peripheral+' => 'Periférico',
));

//
// Class: Enclosure
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Enclosure' => 'Enclosure',
	'Class:Enclosure+' => 'Enclosure',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => 'Rack',
	'Class:Enclosure/Attribute:rack_name' => 'Nombre Rack',
	'Class:Enclosure/Attribute:rack_name+' => 'Nombre Rack',
	'Class:Enclosure/Attribute:nb_u' => 'Unidades de Rack',
	'Class:Enclosure/Attribute:nb_u+' => 'Unidades de Rack',
	'Class:Enclosure/Attribute:device_list' => 'Dispositivos',
	'Class:Enclosure/Attribute:device_list+' => 'Dispositivos',
));

//
// Class: ApplicationSolution
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ApplicationSolution' => 'Solución Aplicativa',
	'Class:ApplicationSolution+' => 'Solución Aplicativa',
	'Class:ApplicationSolution/Attribute:contacts_list' => 'Contactos',
	'Class:ApplicationSolution/Attribute:contacts_list+' => 'Contactos',
	'Class:ApplicationSolution/Attribute:documents_list' => 'Documentos',
	'Class:ApplicationSolution/Attribute:documents_list+' => 'Documentos',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'ECs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'ECs',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Procesos de Negocio',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Procesos de Negocio',
	'Class:ApplicationSolution/Attribute:status' => 'Estatus',
	'Class:ApplicationSolution/Attribute:status+' => 'Estatus',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Activo',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Activo',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Inactivo',
));

//
// Class: BusinessProcess
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:BusinessProcess' => 'Proceso de Negocio',
	'Class:BusinessProcess+' => 'Proceso de Negocio',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Soluciones Aplicativas',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Soluciones Aplicativas',
	'Class:BusinessProcess/Attribute:status' => 'Estatus',    
	'Class:BusinessProcess/Attribute:status+' => 'Estatus',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Activo',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Activo',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Inactivo',
));

//
// Class: SoftwareInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SoftwareInstance' => 'Instalación de Software',
	'Class:SoftwareInstance+' => 'Instalación de Software',
	'Class:SoftwareInstance/Attribute:system_id' => 'Sistema',
	'Class:SoftwareInstance/Attribute:system_id+' => 'Sistema',
	'Class:SoftwareInstance/Attribute:system_name' => 'Sistema',
	'Class:SoftwareInstance/Attribute:system_name+' => 'Sistema',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => 'Software',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licencia de Software',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => 'Licencia de Software',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Licencia de Software',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => 'Licencia de Software',
	'Class:SoftwareInstance/Attribute:path' => 'Ruta',
	'Class:SoftwareInstance/Attribute:path+' => 'Ruta',
	'Class:SoftwareInstance/Attribute:status' => 'Estatus',
	'Class:SoftwareInstance/Attribute:status+' => 'Estatus',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Activo',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Activo',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Inactivo',
));

//
// Class: Middleware
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => 'Middleware',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Instalaciones de Middleware',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Instalaciones de Middleware',
));

//
// Class: DBserver
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DBServer' => 'Servidor de Base de Datos',
	'Class:DBServer+' => 'Servidor de Base de Datos',
	'Class:DBServer/Attribute:dbschema_list' => 'Esquema de BD',
	'Class:DBServer/Attribute:dbschema_list+' => 'Esquema de BD',
));

//
// Class: Webserver
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:WebServer' => 'Servidor Web',
	'Class:WebServer+' => 'Servidor Web',
	'Class:WebServer/Attribute:webapp_list' => 'Aplicaciones Web',
	'Class:WebServer/Attribute:webapp_list+' => 'Aplicaciones Web',
));

//
// Class: PCSoftware
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PCSoftware' => 'Software de PC',
	'Class:PCSoftware+' => 'Software de PC',
));

//
// Class: OtherSoftware
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:OtherSoftware' => 'Otro Software',
	'Class:OtherSoftware+' => 'Otro Software',
));

//
// Class: MiddlewareInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:MiddlewareInstance' => 'Instalación de Middleware',
	'Class:MiddlewareInstance+' => 'Instalación de Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => 'Middleware',
));

//
// Class: DatabaseSchema
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DatabaseSchema' => 'Esquema de Base de Datos',
	'Class:DatabaseSchema+' => 'Esquema de Base de Datos',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Servidor de Base de Datos',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => 'Servidor de Base de Datos',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Servidor de Base de Datos',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => 'Servidor de Base de Datos',
));

//
// Class: WebApplication
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:WebApplication' => 'Aplicación Web',
	'Class:WebApplication+' => 'Aplicación Web',
	'Class:WebApplication/Attribute:webserver_id' => 'Servidor Web',
	'Class:WebApplication/Attribute:webserver_id+' => 'Servidor Web',
	'Class:WebApplication/Attribute:webserver_name' => 'Servidor Web',
	'Class:WebApplication/Attribute:webserver_name+' => 'Servidor Web',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => 'URL de Uso',
));

//
// Class: VirtualDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:VirtualDevice' => 'Dispositivo Virtual',
	'Class:VirtualDevice+' => 'Dispositivo Virtual',
	'Class:VirtualDevice/Attribute:status' => 'Estatus',
	'Class:VirtualDevice/Attribute:status+' => 'Estatus',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Productivo',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Productivo',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'En inventario',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'En inventario',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Volumenes Lógicos',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Volumenes Lógicos',
));

//
// Class: VirtualHost
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:VirtualHost' => 'Host Virtual',
	'Class:VirtualHost+' => 'Host Virtual',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Máquinas Virtuales',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Máquinas Virtuales',
));

//
// Class: Hypervisor
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => 'Hypervisor',
	'Class:Hypervisor/Attribute:farm_id' => 'Granja',
	'Class:Hypervisor/Attribute:farm_id+' => 'Granja',
	'Class:Hypervisor/Attribute:farm_name' => 'Granja',
	'Class:Hypervisor/Attribute:farm_name+' => 'Granja',
	'Class:Hypervisor/Attribute:server_id' => 'Servidor',
	'Class:Hypervisor/Attribute:server_id+' => 'Servidor',
	'Class:Hypervisor/Attribute:server_name' => 'Servidor',
	'Class:Hypervisor/Attribute:server_name+' => 'Servidor',
));

//
// Class: Farm
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Farm' => 'Granja',
	'Class:Farm+' => 'Granja',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisores',
	'Class:Farm/Attribute:hypervisor_list+' => 'Hypervisores',
));

//
// Class: VirtualMachine
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:VirtualMachine' => 'Máquina Virtual',
	'Class:VirtualMachine+' => 'Máquina Virtual',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host Virtual',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => 'Host Virtual',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Host Virtual',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => 'Host Virtual',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Familia de SO',
	'Class:VirtualMachine/Attribute:osfamily_id+' => 'Familia de SO',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Familia de SO',
	'Class:VirtualMachine/Attribute:osfamily_name+' => 'Familia de SO',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Versión de SO',
	'Class:VirtualMachine/Attribute:osversion_id+' => 'Versión de SO',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Versión de SO',
	'Class:VirtualMachine/Attribute:osversion_name+' => 'Versión de SO',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licencia de SO',
	'Class:VirtualMachine/Attribute:oslicence_id+' => 'Licencia de SO',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Licencia de SO',
	'Class:VirtualMachine/Attribute:oslicence_name+' => 'Licencia de SO',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => 'CPU',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => 'RAM',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Interfases de Red',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Interfases de Red',
));

//
// Class: LogicalVolume
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:LogicalVolume' => 'Volumen Lógico',
	'Class:LogicalVolume+' => 'Volumen Lógico',
	'Class:LogicalVolume/Attribute:name' => 'Nombre',
	'Class:LogicalVolume/Attribute:name+' => 'Nombre del Volumen Lógico',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN',
	'Class:LogicalVolume/Attribute:lun_id+' => 'LUN',
	'Class:LogicalVolume/Attribute:description' => 'Descripción',
	'Class:LogicalVolume/Attribute:description+' => 'Descripción',
	'Class:LogicalVolume/Attribute:raid_level' => 'Nivel de RAID',
	'Class:LogicalVolume/Attribute:raid_level+' => 'Nivel de RAID',
	'Class:LogicalVolume/Attribute:size' => 'Tama&ntilde;o',
	'Class:LogicalVolume/Attribute:size+' => 'Tama&ntilde;o',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Sistema de Almacenamiento',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => 'Sistema de Almacenamiento',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Sistema de Almacenamiento',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => 'Sistema de Almacenamiento',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servidores',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Servidores',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Dispositivos Virtuales',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Dispositivos Virtuales',
));

//
// Class: lnkServerToVolume
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkServerToVolume' => 'Relación Servidor y Volumen',
	'Class:lnkServerToVolume+' => 'Relación Servidor y Volumen',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volumen',
	'Class:lnkServerToVolume/Attribute:volume_id+' => 'Volumen',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_name+' => 'Volumen',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:server_id+' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:server_name+' => 'Servidor',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Tama&ntilde;o Asignado',
	'Class:lnkServerToVolume/Attribute:size_used+' => 'Tama&ntilde;o Asignado',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkVirtualDeviceToVolume' => 'Relación Dispositivo Virtual y Volumen',
	'Class:lnkVirtualDeviceToVolume+' => 'Relación Dispositivo Virtual y Volumen',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volumen',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => 'Volumen',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volumen',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => 'Volumen',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Dispositivo Virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => 'Dispositivo Virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Dispositivo Virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => 'Dispositivo Virtual',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Tama&ntilde;o Asignado',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => 'Tama&ntilde;o Asignado',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSanToDatacenterDevice' => 'Relación SAN y Dispositivo del Centro de Datos',
	'Class:lnkSanToDatacenterDevice+' => 'Relación SAN y Dispositivo del Centro de Datos',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'Switch de SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => 'Switch de SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Switch de SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => 'Switch de SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => 'Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC en SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => 'FC en SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'FC en Dispositivo',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => 'FC en Dispositivo',
));

//
// Class: Tape
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Tape' => 'Cinta',
	'Class:Tape+' => 'Cinta',
	'Class:Tape/Attribute:name' => 'Nombre',
	'Class:Tape/Attribute:name+' => 'Nombre de la Cinta',
	'Class:Tape/Attribute:description' => 'Descriptción',
	'Class:Tape/Attribute:description+' => 'Descriptción',
	'Class:Tape/Attribute:size' => 'Tama&ntilde;o',
	'Class:Tape/Attribute:size+' => 'Tama&ntilde;o',
	'Class:Tape/Attribute:tapelibrary_id' => 'Liberia de Cintas',
	'Class:Tape/Attribute:tapelibrary_id+' => 'Liberia de Cintas',
	'Class:Tape/Attribute:tapelibrary_name' => 'Liberia de Cintas',
	'Class:Tape/Attribute:tapelibrary_name+' => 'Liberia de Cintas',
));

//
// Class: NasFileSystem
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NASFileSystem' => 'Filesysten en NAS',
	'Class:NASFileSystem+' => 'Filesysten en NAS',
	'Class:NASFileSystem/Attribute:name' => 'Nombre',
	'Class:NASFileSystem/Attribute:name+' => 'Nombre del Filesystem de Red',
	'Class:NASFileSystem/Attribute:description' => 'Descripción',
	'Class:NASFileSystem/Attribute:description+' => 'Descripción',
	'Class:NASFileSystem/Attribute:raid_level' => 'Nivel de RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => 'Nivel de RAID',
	'Class:NASFileSystem/Attribute:size' => 'Tama&ntilde;o',
	'Class:NASFileSystem/Attribute:size+' => 'Tama&ntilde;o',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => 'NAS',
));

//
// Class: Software
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Software' => 'Software',
	'Class:Software+' => 'Software',
	'Class:Software/Attribute:name' => 'Nombre',
	'Class:Software/Attribute:name+' => 'Nombre del Software',
	'Class:Software/Attribute:vendor' => 'Proveedor',
	'Class:Software/Attribute:vendor+' => 'Proveedor',
	'Class:Software/Attribute:version' => 'Versión',
	'Class:Software/Attribute:version+' => 'Versión',
	'Class:Software/Attribute:documents_list' => 'Documentos',
	'Class:Software/Attribute:documents_list+' => 'Documentos',
	'Class:Software/Attribute:type' => 'Tipo',
	'Class:Software/Attribute:type+' => 'Tipo',
	'Class:Software/Attribute:type/Value:DBServer' => 'Servidor de BD',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Servidor de BD',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Otro Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Otro Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'Software de PC',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'Software de PC',
	'Class:Software/Attribute:type/Value:WebServer' => 'Servidor Web',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Servidor Web',
	'Class:Software/Attribute:softwareinstance_list' => 'Instalaciones de Software',
	'Class:Software/Attribute:softwareinstance_list+' => 'Instalaciones de Software',
	'Class:Software/Attribute:softwarepatch_list' => 'Parches de Software',
	'Class:Software/Attribute:softwarepatch_list+' => 'Parches de Software',
	'Class:Software/Attribute:softwarelicence_list' => 'Licencias de Software',
	'Class:Software/Attribute:softwarelicence_list+' => 'Licencias de Software',
));

//
// Class: Patch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Patch' => 'Parche',
	'Class:Patch+' => 'Parche',
	'Class:Patch/Attribute:name' => 'Nombre',
	'Class:Patch/Attribute:name+' => 'Nombre del Parche',
	'Class:Patch/Attribute:documents_list' => 'Documentos',
	'Class:Patch/Attribute:documents_list+' => 'Documentos',
	'Class:Patch/Attribute:description' => 'Descripción',
	'Class:Patch/Attribute:description+' => 'Descripción',
	'Class:Patch/Attribute:finalclass' => 'Clase',
	'Class:Patch/Attribute:finalclass+' => 'Clase',
));

//
// Class: OSpatch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:OSPatch' => 'Parche de SO',
	'Class:OSPatch+' => 'Parche de SO',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Dispositivos',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Dispositivos',
	'Class:OSPatch/Attribute:osversion_id' => 'Versión de SO',
	'Class:OSPatch/Attribute:osversion_id+' => 'Versión de SO',
	'Class:OSPatch/Attribute:osversion_name' => 'Versión de SO',
	'Class:OSPatch/Attribute:osversion_name+' => 'Versión de SO',
));

//
// Class: SoftwarePatch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SoftwarePatch' => 'Parche de Software',
	'Class:SoftwarePatch+' => 'Parche de Software',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => 'Software',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software',
	'Class:SoftwarePatch/Attribute:software_name+' => 'Software',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instalaciones de Software',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Instalaciones de Software',
));

//
// Class: Licence
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Licence' => 'Licencia',
	'Class:Licence+' => 'Licencia',
	'Class:Licence/Attribute:name' => 'Nombre',
	'Class:Licence/Attribute:name+' => 'Nombre de la Licencia',
	'Class:Licence/Attribute:documents_list' => 'Documentos',
	'Class:Licence/Attribute:documents_list+' => 'Documentos',
	'Class:Licence/Attribute:org_id' => 'Compañía',
	'Class:Licence/Attribute:org_id+' => 'Compañía',
	'Class:Licence/Attribute:organization_name' => 'Compañía',
	'Class:Licence/Attribute:organization_name+' => 'Compañía',
	'Class:Licence/Attribute:usage_limit' => 'Límite de Uso',
	'Class:Licence/Attribute:usage_limit+' => 'Límite de Uso',
	'Class:Licence/Attribute:description' => 'Descripción',
	'Class:Licence/Attribute:description+' => 'Descripción',
	'Class:Licence/Attribute:start_date' => 'Fecha de Inicio',
	'Class:Licence/Attribute:start_date+' => 'Fecha de Inicio',
	'Class:Licence/Attribute:end_date' => 'Fecha de Fin',
	'Class:Licence/Attribute:end_date+' => 'Fecha de Fin',
	'Class:Licence/Attribute:licence_key' => 'Llave',
	'Class:Licence/Attribute:licence_key+' => 'Llave',
	'Class:Licence/Attribute:perpetual' => 'Perpetuidad',
	'Class:Licence/Attribute:perpetual+' => 'Licenciamiento Perpetuo',
	'Class:Licence/Attribute:perpetual/Value:no' => 'No',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'No',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Si',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'Si',
	'Class:Licence/Attribute:finalclass' => 'Clase',
	'Class:Licence/Attribute:finalclass+' => 'Clase',
));

//
// Class: OsLicence
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:OSLicence' => 'Licencia de SO',
	'Class:OSLicence+' => 'Licencia de SO',
	'Class:OSLicence/Attribute:osversion_id' => 'Versión de SO',
	'Class:OSLicence/Attribute:osversion_id+' => 'Versión de SO',
	'Class:OSLicence/Attribute:osversion_name' => 'Versión de SO',
	'Class:OSLicence/Attribute:osversion_name+' => 'Versión de SO',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Máquinas Virtuales',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Máquinas Virtuales',
	'Class:OSLicence/Attribute:servers_list' => 'Servidores',
	'Class:OSLicence/Attribute:servers_list+' => 'Servidores',
));

//
// Class: SoftwareLicence
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SoftwareLicence' => 'Licencia de Software',
	'Class:SoftwareLicence+' => 'Licencia de Software',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => 'Software',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software',
	'Class:SoftwareLicence/Attribute:software_name+' => 'Software',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instalaciones de Software',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Instalaciones de Software',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDocumentToLicence' => 'Relación Documento y Licencia',
	'Class:lnkDocumentToLicence+' => 'Relación Documento y Licencia',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licencia',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => 'Licencia',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Licencia',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => 'Licencia',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => 'Documento',
));

//
// Class: Typology
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Typology' => 'Tipología',
	'Class:Typology+' => 'Tipología',
	'Class:Typology/Attribute:name' => 'Nombre',
	'Class:Typology/Attribute:name+' => 'Nombre del Tipo',
	'Class:Typology/Attribute:finalclass' => 'Clase',
	'Class:Typology/Attribute:finalclass+' => 'Clase',
));

//
// Class: OSversion
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:OSVersion' => 'Versión de SO',
	'Class:OSVersion+' => 'Versión de SO',
	'Class:OSVersion/Attribute:osfamily_id' => 'Familia de SO',
	'Class:OSVersion/Attribute:osfamily_id+' => 'Familia de SO',
	'Class:OSVersion/Attribute:osfamily_name' => 'Familia de SO',
	'Class:OSVersion/Attribute:osfamily_name+' => 'Familia de SO',
));

//
// Class: OSfamily
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:OSFamily' => 'Familia de SO',
	'Class:OSFamily+' => 'Familia de SO',
));

//
// Class: DocumentType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DocumentType' => 'Tipo de Documento',
	'Class:DocumentType+' => 'Tipo de Documento',
));

//
// Class: ContactType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ContactType' => 'Tipo de Contacto',
	'Class:ContactType+' => 'Tipo de Contacto',
));

//
// Class: Brand
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Brand' => 'Marca',
	'Class:Brand+' => 'Marca',
	'Class:Brand/Attribute:physicaldevices_list' => 'Dispositivo Físico',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Dispositivo Físico',
));

//
// Class: Model
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Model' => 'Modelo',
	'Class:Model+' => 'Modelo',
	'Class:Model/Attribute:brand_id' => 'Marca',
	'Class:Model/Attribute:brand_id+' => 'Marca',
	'Class:Model/Attribute:brand_name' => 'Marca',
	'Class:Model/Attribute:brand_name+' => 'Marca',
	'Class:Model/Attribute:type' => 'Tipo de Dispositivo',
	'Class:Model/Attribute:type+' => 'Tipo de Dispositivo',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Fuente de Poder',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Fuente de Poder',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Arreglo de Discos',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Arreglo de Discos',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Teléfono IP',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'Teléfono IP',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Teléfono Móvil',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Teléfono Móvil',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Dispositivo de Red',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Dispositivo de Red',
	'Class:Model/Attribute:type/Value:PC' => 'PC/Laptop',
	'Class:Model/Attribute:type/Value:PC+' => 'PC/Laptor',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Periférico',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Periférico',
	'Class:Model/Attribute:type/Value:Printer' => 'Impresora',
	'Class:Model/Attribute:type/Value:Printer+' => 'Impresora',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'Switch de SAN',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'Switch de SAN',
	'Class:Model/Attribute:type/Value:Server' => 'Servidor',
	'Class:Model/Attribute:type/Value:Server+' => 'Servidor',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Sistema de Almacenamiento',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Sistema de Almacenamiento',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tableta',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tableta',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Librería de Cinta',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Librería de Cinta',
	'Class:Model/Attribute:type/Value:Phone' => 'Teléfono',
	'Class:Model/Attribute:type/Value:Phone+' => 'Teléfono',
	'Class:Model/Attribute:physicaldevices_list' => 'Dispositivo Físico',
	'Class:Model/Attribute:physicaldevices_list+' => 'Dispositivo Físico',
));

//
// Class: NetworkDeviceType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NetworkDeviceType' => 'Tipo Dispositivo de Red',
	'Class:NetworkDeviceType+' => 'Tipo de Dispositivo de Red',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Dispositivos de Red',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Dispositivos de Red',
));

//
// Class: IOSversion
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:IOSVersion' => 'Versión de IOS',
	'Class:IOSVersion+' => 'Versión de IOS',
	'Class:IOSVersion/Attribute:brand_id' => 'Marca',
	'Class:IOSVersion/Attribute:brand_id+' => 'Marca',
	'Class:IOSVersion/Attribute:brand_name' => 'Marca',
	'Class:IOSVersion/Attribute:brand_name+' => 'Marca',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDocumentToPatch' => 'Relación Documentos y Parche',
	'Class:lnkDocumentToPatch+' => 'Relación Documentos y Parche',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Parche',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => 'Parche',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Parche',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => 'Parche',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => 'Documento',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Relación Instalación de Software y Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => 'Relación Instalación de Software y Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => 'Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => 'Parche de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instalación de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => 'Instalación de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Instalación de Software',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => 'Instalación de Software',
));

//
// Class: lnkFunctionalCIToOSpatch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Relación EC Funcional y Parche de SO',
	'Class:lnkFunctionalCIToOSPatch+' => 'Relación EC Funcional y Parche de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Parche de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => 'Parche de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Parche de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => 'Parche de SO',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'EC Funcional',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => 'EC Funcional',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'EC Funcional',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => 'EC Funcional',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDocumentToSoftware' => 'Relación Documento y Software',
	'Class:lnkDocumentToSoftware+' => 'Relación Documento y Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => 'Documento',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContactToFunctionalCI' => 'Relación Contacto y  EC Funcional',
	'Class:lnkContactToFunctionalCI+' => 'Relación Contacto y  EC Funcional',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'EC Funcional',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => 'EC Funcional',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'EC Funcional',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => 'EC Funcional',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contacto',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => 'Contacto',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Contacto',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => 'Contacto',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkDocumentToFunctionalCI' => 'Relación Documento y EC Funcional',
	'Class:lnkDocumentToFunctionalCI+' => 'Relación Documento y EC Funcional',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'EC Funcional',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => 'EC Funcional',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'EC Funcional',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => 'EC Funcional',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => 'Documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Documento',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => 'Documento',
));

//
// Class: Subnet
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Subnet' => 'SubRed',
	'Class:Subnet+' => 'SubRed',
	'Class:Subnet/Attribute:description' => 'Descripción',
	'Class:Subnet/Attribute:description+' => 'Descripción',
	'Class:Subnet/Attribute:org_id' => 'Organización',
	'Class:Subnet/Attribute:org_id+' => 'Organización',
	'Class:Subnet/Attribute:org_name' => 'Organización',
	'Class:Subnet/Attribute:org_name+' => 'Organización',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => 'IP',
	'Class:Subnet/Attribute:ip_mask' => 'Máscara de Red',
	'Class:Subnet/Attribute:ip_mask+' => 'Máscara de Red',
	'Class:Subnet/Attribute:vlans_list' => 'vLANs',
	'Class:Subnet/Attribute:vlans_list+' => 'Virtual LANs',
));

//
// Class: VLAN
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:VLAN' => 'vLAN',
	'Class:VLAN+' => 'Red Virtual de Área Local',
	'Class:VLAN/Attribute:vlan_tag' => 'Etiqueta vLAN',
	'Class:VLAN/Attribute:vlan_tag+' => 'Etiqueta vLAN',
	'Class:VLAN/Attribute:description' => 'Descripción',
	'Class:VLAN/Attribute:description+' => 'Descripción',
	'Class:VLAN/Attribute:org_id' => 'Organización',
	'Class:VLAN/Attribute:org_id+' => 'Organización',
	'Class:VLAN/Attribute:org_name' => 'Nombre de la Organización',
	'Class:VLAN/Attribute:org_name+' => 'Nombre de la Organización',
	'Class:VLAN/Attribute:subnets_list' => 'Subredes',
	'Class:VLAN/Attribute:subnets_list+' => 'Subredes',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Interfases Físicas de Red',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => 'Interfases Físicas de Red',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSubnetToVLAN' => 'Relación Subred / vLAN',
	'Class:lnkSubnetToVLAN+' => 'Relación Subred / vLAN',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subred',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => 'Subred',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP de Subred',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => 'IP de Subred',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Nombre de Subred',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => 'Nombre de Subred',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'vLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => 'vLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Etiqueta vLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => 'Etiqueta vLAN',
));

//
// Class: NetworkInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NetworkInterface' => 'Interfaz de Red',
	'Class:NetworkInterface+' => 'Interfaz de Red',
	'Class:NetworkInterface/Attribute:name' => 'Nombre',
	'Class:NetworkInterface/Attribute:name+' => 'Nombre de la Interfaz de Red',
	'Class:NetworkInterface/Attribute:finalclass' => 'Clase',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Clase',
));

//
// Class: IPinterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:IPInterface' => 'Interfaz IP',
	'Class:IPInterface+' => 'Interfaz IP',
	'Class:IPInterface/Attribute:ipaddress' => 'Dirección IP',
	'Class:IPInterface/Attribute:ipaddress+' => 'Dirección IP',
	'Class:IPInterface/Attribute:macaddress' => 'Dirección MAC',
	'Class:IPInterface/Attribute:macaddress+' => 'Dirección MAC',
	'Class:IPInterface/Attribute:comment' => 'Comentario',
	'Class:IPInterface/Attribute:coment+' => 'Comentario',
	'Class:IPInterface/Attribute:ipgateway' => 'Gateway IP',
	'Class:IPInterface/Attribute:ipgateway+' => 'Gateway IP',
	'Class:IPInterface/Attribute:ipmask' => 'Máscara de Red',
	'Class:IPInterface/Attribute:ipmask+' => 'Máscara de Red',
	'Class:IPInterface/Attribute:speed' => 'Velocidad',
	'Class:IPInterface/Attribute:speed+' => 'Velocidad',
));

//
// Class: PhysicalInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PhysicalInterface' => 'Interfaz Física',
	'Class:PhysicalInterface+' => 'Interfaz Física',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => 'Dispositivo',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'vLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => 'vLANS',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Relación Interfaz Física / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => 'Relación Interfaz Física / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Interfaz Física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => 'Interfaz Física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Nombre Interfaz Física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => 'Nombre Interfaz Física',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => 'Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Nombre de Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => 'Nombre de Dispositivo',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'vLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => 'vLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Etiqueta VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => 'Etiqueta VLAN',
));

//
// Class: LogicalInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:LogicalInterface' => 'Interfaz Lógica',
	'Class:LogicalInterface+' => 'Interfaz Lógica',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Máquina Virtual',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => 'Máquina Virtual',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Máquina Virtual',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => 'Máquina Virtual',
));

//
// Class: FiberChannelInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FiberChannelInterface' => 'Intefaz de Fibra Óptica',
	'Class:FiberChannelInterface+' => 'Intefaz de Fibra Óptica',
	'Class:FiberChannelInterface/Attribute:speed' => 'Velocidad',
	'Class:FiberChannelInterface/Attribute:speed+' => 'Velocidad',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topología',
	'Class:FiberChannelInterface/Attribute:topology+' => 'Topología',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => 'WWN',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => 'Dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Dispositivo',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => 'Dispositivo',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Relación EC Conectable y Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice+' => 'Relación EC Conectable y Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => 'Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => 'Dispositivo de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Dispositivo Conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => 'Dispositivo Conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Dispositivo Conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => 'Dispositivo Conectado',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Puerto de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => 'Puerto de Red',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Puerto en Dispositivo',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => 'Puerto en Dispositivo',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Tipo de Conexión',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => 'Tipo de Conexión',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Down Link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'Down Link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Up Link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'Up Link',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Relación Solución Aplicativa y EC Funcional',
	'Class:lnkApplicationSolutionToFunctionalCI+' => 'Relación Solución Aplicativa y EC Funcional',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'EC Funcional',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => 'EC Funcional',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'EC Funcional',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => 'EC Funcional',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Relación Solución Aplicativa y Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess+' => 'Relación Solución Aplicativa y Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => 'Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => 'Proceso de Negocio',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Solución Aplicativa',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => 'Solución Aplicativa',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkPersonToTeam' => 'Relación Persona y Grupo',
	'Class:lnkPersonToTeam+' => 'Relación Persona y Grupo',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Grupo',
	'Class:lnkPersonToTeam/Attribute:team_id+' => 'Grupo',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Grupo',
	'Class:lnkPersonToTeam/Attribute:team_name+' => 'Grupo',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Persona',
	'Class:lnkPersonToTeam/Attribute:person_id+' => 'Persona',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Persona',
	'Class:lnkPersonToTeam/Attribute:person_name+' => 'Persona',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_id+' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_name+' => 'Rol',
));

//
// Class: Group
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Group' => 'Grupo',
	'Class:Group+' => 'Grupo',
	'Class:Group/Attribute:name' => 'Nombre',
	'Class:Group/Attribute:name+' => 'Nombre del Grupo',
	'Class:Group/Attribute:status' => 'Estatus',
	'Class:Group/Attribute:status+' => 'Estatus',
	'Class:Group/Attribute:status/Value:implementation' => 'No Productivo',
	'Class:Group/Attribute:status/Value:implementation+' => 'No Productivo',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsoletp',
	'Class:Group/Attribute:status/Value:production' => 'Productivo',
	'Class:Group/Attribute:status/Value:production+' => 'Productivo',
	'Class:Group/Attribute:org_id' => 'Compañía',
	'Class:Group/Attribute:org_id+' => 'Compañía',
	'Class:Group/Attribute:owner_name' => 'Compañía',
	'Class:Group/Attribute:owner_name+' => 'Compañía',
	'Class:Group/Attribute:description' => 'Descripción',
	'Class:Group/Attribute:description+' => 'Descripción',
	'Class:Group/Attribute:type' => 'Tipo',
	'Class:Group/Attribute:type+' => 'Tipo',
	'Class:Group/Attribute:parent_id' => 'Grupo Padre',
	'Class:Group/Attribute:parent_id+' => 'Grupo Padre',
	'Class:Group/Attribute:parent_name' => 'Grupo Padre',
	'Class:Group/Attribute:parent_name+' => 'Grupo Padre',
	'Class:Group/Attribute:ci_list' => 'ECs Relacionados',
	'Class:Group/Attribute:ci_list+' => 'ECs Relacionados',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Grupo Padre',
	'Class:Group/Attribute:parent_id_friendlyname+' => 'Grupo Padre',
));

//
// Class: lnkGroupToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkGroupToCI' => 'Relación Grupo y EC',
	'Class:lnkGroupToCI+' => 'Relación Grupo y EC',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_id+' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_name+' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'EC',
	'Class:lnkGroupToCI/Attribute:ci_id+' => 'EC',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Elemento de Configuración',
	'Class:lnkGroupToCI/Attribute:ci_name+' => 'Elemento de Configuración',
	'Class:lnkGroupToCI/Attribute:reason' => 'Motivo',
	'Class:lnkGroupToCI/Attribute:reason+' => 'Motivo',
));


//
// Application Menu
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
'Menu:DataAdministration' => 'Administración de Datos',
'Menu:DataAdministration+' => 'Administración de Datos',
'Menu:Catalogs' => 'Catálogos',
'Menu:Catalogs+' => 'Tipos de Datos',
'Menu:Audit' => 'Auditoría',
'Menu:Audit+' => 'Auditoría',
'Menu:CSVImport' => 'Importar CSV',
'Menu:CSVImport+' => 'Creación o Actualización Másiva',
'Menu:Organization' => 'Organizaciones',
'Menu:Organization+' => 'Organizaciones',
'Menu:Application' => 'Aplicaciones',
'Menu:Application+' => 'Aplicaciones/Programas',
'Menu:DBServer' => 'Servidores de Base de Datos',
'Menu:DBServer+' => 'Servidores de Base de Datos',
'Menu:Audit' => 'Auditoría',
'Menu:ConfigManagement' => 'Administración de la Configuración',
'Menu:ConfigManagement+' => 'Administración de la Configuración',
'Menu:ConfigManagementOverview' => 'Resumen de Infraestructura',
'Menu:ConfigManagementOverview+' => 'Resumen de Infraestructura',
'Menu:Contact:Count' => '%1$d Contactos',
'Menu:Contact' => 'Contactos',
'Menu:Contact+' => 'Contactos',
'Menu:Person' => 'Personas',
'Menu:Person+' => 'Personas',
'Menu:Team' => 'Grupos',
'Menu:Team+' => 'Grupos de Trabajo',
'Menu:Document' => 'Documentos',
'Menu:Document+' => 'Documentos',
'Menu:Location' => 'Localidades',
'Menu:Location+' => 'Localidades',
'Menu:ConfigManagementCI' => 'Elementos de Configuración',
'Menu:ConfigManagementCI+' => 'Elementos de Confirguración',
'Menu:BusinessProcess' => 'Proceso de Negocio',
'Menu:BusinessProcess+' => 'Proceso de Negocios',
'Menu:ApplicationSolution' => 'Solución Aplicativa',
'Menu:ApplicationSolution+' => 'Solución Aplicativa',
'Menu:ConfigManagementSoftware' => 'Administración de Aplicaciones',
'Menu:Licence' => 'Licencias',
'Menu:Licence+' => 'Licencias',
'Menu:Patch' => 'Parches',
'Menu:Patch+' => 'Parches',
'Menu:ApplicationInstance' => 'Software Instalado',
'Menu:ApplicationInstance+' => 'Aplicaciones y Servidores de Base de Datos',
'Menu:ConfigManagementHardware' => 'Administración de Infraestructura',
'Menu:Subnet' => 'SubRedes',
'Menu:Subnet+' => 'SubRedes',
'Menu:NetworkDevice' => 'Dispositivos de Red',
'Menu:NetworkDevice+' => 'Dispositivos de Red',
'Menu:Server' => 'Servidores',
'Menu:Server+' => 'Servidores',
'Menu:Printer' => 'Impresoras',
'Menu:Printer+' => 'Impresoras',
'Menu:MobilePhone' => 'Teléfonos Móviles',
'Menu:MobilePhone+' => 'Teléfonos Móviles',
'Menu:PC' => 'PCs y Laptops',
'Menu:PC+' => 'PCs y Laptops',
'Menu:NewContact' => 'Nuevo Contacto',
'Menu:NewContact+' => 'Nuevo Contacto',
'Menu:SearchContacts' => 'Búsqueda de Contactos',
'Menu:SearchContacts+' => 'Búsqueda de Contactos',
'Menu:NewCI' => 'Nuevo EC',
'Menu:NewCI+' => 'Nuevo EC',
'Menu:SearchCIs' => 'Búsqueda de ECs',
'Menu:SearchCIs+' => 'Búsqueda de ECs',
'Menu:ConfigManagement:Devices' => 'Dispositivos',
'Menu:ConfigManagement:AllDevices' => 'Infraestructura',
'Menu:ConfigManagement:virtualization' => 'Virtualización',
'Menu:ConfigManagement:EndUsers' => 'Dispositivos de Usuario Final',
'Menu:ConfigManagement:SWAndApps' => 'Software y Aplicaciones',
'Menu:ConfigManagement:Misc' => 'Misceláneo',
'Menu:Group' => 'Grupos de ECs',
'Menu:Group+' => 'Grupos de ECs',
'Menu:ConfigManagement:Shortcuts' => 'Acceso Rápido',
'Menu:ConfigManagement:AllContacts' => 'Contactos: %1$d',
'Menu:Typology' => 'Configuración de Tipos',
'Menu:Typology+' => 'Configuración de Tipos',
'Menu:OSversion' => 'Versiones de SO',
'Menu:OSversion+' => 'Versiones de SO',
'Menu:Software' => 'Catálogo de Software',
'Menu:Software+' => 'Catálogo de Software',
'UI_WelcomeMenu_AllConfigItems' => 'Resumen',
'Menu:ConfigManagement:Typology' => 'Configuración de Tipos',

));


// Add translation for Fieldsets

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
'Server:baseinfo' => 'Información General',
'Server:Date' => 'Fecha',
'Server:moreinfo' => 'Más Información',
'Server:otherinfo' => 'Otra Información',
'Person:info' => 'Información General',
'Person:notifiy' => 'Notificación',
'Class:Subnet/Tab:IPUsage' => 'Uso de IP',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfases con IP en el rango: <em>%1$s</em> a <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IPs Libres',
'Class:Subnet/Tab:FreeIPs-count' => 'IPs Libres: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Aquí está un extracto de 10 direcciones IP Libres',
'Class:Document:PreviewTab' => 'Vista Previa',
));
?>

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
 * Localized data
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com>
 */
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
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:Person' => 'Persona',
	'Class:Person+' => 'Persona',
	'Class:Person/Attribute:name' => 'Apellidos',
	'Class:Person/Attribute:name+' => 'Apellidos',
	'Class:Person/Attribute:first_name' => 'Nombre',
	'Class:Person/Attribute:first_name+' => 'Nombre de la Persona',
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
	'Class:Person/Attribute:picture' => 'Fotografía',
	'Class:Person/Attribute:picture+' => 'Fotografía',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization~~',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number~~',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization~~',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name~~',
));

//
// Class: Team
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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
	'Class:Document/Attribute:finalclass' => 'Tipo de Documento',
	'Class:Document/Attribute:finalclass+' => 'Tipo de Documento',
));

//
// Class: DocumentFile
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:DocumentFile' => 'Documento de Archivo',
	'Class:DocumentFile+' => '~~',
	'Class:DocumentFile/Attribute:file' => 'Archivo',
	'Class:DocumentFile/Attribute:file+' => 'Archivo',
));

//
// Class: DocumentNote
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:DocumentNote' => 'Nota',
	'Class:DocumentNote+' => 'Nota',
	'Class:DocumentNote/Attribute:text' => 'Texto',
	'Class:DocumentNote/Attribute:text+' => 'Texto',
));

//
// Class: DocumentWeb
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:DocumentWeb' => 'Documento Web',
	'Class:DocumentWeb+' => 'Documento disponible en otro servidor Web',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => 'URL de Internet',
));

//
// Class: Typology
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:Typology' => 'Tipología',
	'Class:Typology+' => 'Tipología',
	'Class:Typology/Attribute:name' => 'Nombre',
	'Class:Typology/Attribute:name+' => 'Nombre del Tipo',
	'Class:Typology/Attribute:finalclass' => 'Clase',
	'Class:Typology/Attribute:finalclass+' => 'Clase',
));

//
// Class: DocumentType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:DocumentType' => 'Tipo de Documento',
	'Class:DocumentType+' => 'Tipo de Documento',
));

//
// Class: ContactType
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Class:ContactType' => 'Tipo de Contacto',
	'Class:ContactType+' => 'Tipo de Contacto',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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
// Application Menu
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
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
	'Menu:ConfigManagement' => 'Administración de la Configuración',
	'Menu:ConfigManagement+' => 'Administración de la Configuración',
	'Menu:ConfigManagementCI' => 'Elementos de Configuración',
	'Menu:ConfigManagementCI+' => 'Elementos de Confirguración',
	'Menu:ConfigManagementOverview' => 'Resumen de Infraestructura',
	'Menu:ConfigManagementOverview+' => 'Resumen de Infraestructura',
	'Menu:Contact' => 'Contactos',
	'Menu:Contact+' => 'Contactos',
	'Menu:Contact:Count' => '%1$d Contactos',
	'Menu:Person' => 'Personas',
	'Menu:Person+' => 'Personas',
	'Menu:Team' => 'Grupos',
	'Menu:Team+' => 'Grupos de Trabajo',
	'Menu:Document' => 'Documentos',
	'Menu:Document+' => 'Documentos',
	'Menu:Location' => 'Localidades',
	'Menu:Location+' => 'Localidades',
	'Menu:NewContact' => 'Nuevo Contacto',
	'Menu:NewContact+' => 'Nuevo Contacto',
	'Menu:SearchContacts' => 'Búsqueda de Contactos',
	'Menu:SearchContacts+' => 'Búsqueda de Contactos',
	'Menu:ConfigManagement:Shortcuts' => 'Acceso Rápido',
	'Menu:ConfigManagement:AllContacts' => 'Contactos: %1$d',
	'Menu:Typology' => 'Configuración de Tipos',
	'Menu:Typology+' => 'Configuración de Tipos',
	'UI_WelcomeMenu_AllConfigItems' => 'Resumen',
	'Menu:ConfigManagement:Typology' => 'Configuración de Tipos',
));

// Add translation for Fieldsets

Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'Person:info' => 'Información General',
	'UserLocal:info' => 'Información General',
	'Person:personal_info' => 'Información Personal',
	'Person:notifiy' => 'Notificación',
));

// Themes
Dict::Add('ES CR', 'Spanish', 'Español, Castellaño', array(
	'theme:fullmoon' => 'Full moon~~',
	'theme:test-red' => 'Test instance (Red)~~',
));

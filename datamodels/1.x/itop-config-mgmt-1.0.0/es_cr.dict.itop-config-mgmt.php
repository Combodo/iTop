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

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Relation:impacts/Description' => 'Elementos impactados por',
	'Relation:impacts/VerbUp' => 'Impacto...',
	'Relation:impacts/VerbDown' => 'Elementos impactados por...',
	'Relation:depends on/Description' => 'Elementos de los cuales este elemento depende',
	'Relation:depends on/VerbUp' => 'Depende de...',
	'Relation:depends on/VerbDown' => 'Impacta...',
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
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Nombre',
	'Class:Organization/Attribute:name+' => 'Common name',
	'Class:Organization/Attribute:code' => 'Código',
	'Class:Organization/Attribute:code+' => 'Código de organización  (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Estado',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Activo',
	'Class:Organization/Attribute:status/Value:active+' => 'Activo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Organization/Attribute:parent_id' => 'Padre',
	'Class:Organization/Attribute:parent_id+' => 'Organización padre',
	'Class:Organization/Attribute:parent_name' => 'Nombre de padre',
	'Class:Organization/Attribute:parent_name+' => 'Nombre de la organización padre',
));


//
// Class: Location
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Location' => 'Ubicación',
	'Class:Location+' => 'Cualquier tipo de ubicación: Región, País, Ciudad, Sitio, Edificio, Piso, Cuarto, Rack,...',
	'Class:Location/Attribute:name' => 'Nombre',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Estado',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Activo',
	'Class:Location/Attribute:status/Value:active+' => 'Activo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Location/Attribute:org_id' => 'Organización propietaria',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nombre de la organización propietaria',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Dirección',
	'Class:Location/Attribute:address+' => 'Dirección postal',
	'Class:Location/Attribute:postal_code' => 'Código postal',
	'Class:Location/Attribute:postal_code+' => 'ZIP/Código postal',
	'Class:Location/Attribute:city' => 'Ciudad',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'País',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Ubicación Padre',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Nombre de padre',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Contactos',
	'Class:Location/Attribute:contact_list+' => 'Contactos localizables en ese sitio',
	'Class:Location/Attribute:infra_list' => 'Infraestructura',
	'Class:Location/Attribute:infra_list+' => 'Ítem Configurados (CI) ubicados en este sitio',
));
//
// Class: Group
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Group' => 'Grupo',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Nombre',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Estado',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementación',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementación',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:production' => 'Producción',
	'Class:Group/Attribute:status/Value:production+' => 'Producción',
	'Class:Group/Attribute:org_id' => 'Organización propietaria',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Nombre de la Organización propietaria',
	'Class:Group/Attribute:owner_name+' => 'Organización propietaria',
	'Class:Group/Attribute:description' => 'Descripción',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tipo',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Padre',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Grupo padre',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'I.C.s',
	'Class:Group/Attribute:ci_list+' => 'Ítems Configurados relacionados con el grupo',
));

//
// Class: lnkGroupToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkGroupToCI' => 'Grupo I.C',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grupo',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nombre',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'I.C',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nombre',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'Estato',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Razón',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

//
// Class: Contact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Contact' => 'Contacto',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nombre',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Estado',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Activo',
	'Class:Contact/Attribute:status/Value:active+' => 'Activo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactivo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inactivo',
	'Class:Contact/Attribute:org_id' => 'Organización',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organización',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Correo Electrónico',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Teléfono',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Ubicación',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Ubicación',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'I.C.s',
	'Class:Contact/Attribute:ci_list+' => 'Ítems Configurados relacionados con el contacto',
	'Class:Contact/Attribute:contract_list' => 'Contratos',
	'Class:Contact/Attribute:contract_list+' => 'Contractos relacionados con el contacto',
	'Class:Contact/Attribute:service_list' => 'Servicios',
	'Class:Contact/Attribute:service_list+' => 'Servicios relacionados con el contacto',
	'Class:Contact/Attribute:ticket_list' => 'Tiquetes',
	'Class:Contact/Attribute:ticket_list+' => 'Tiquetes relacionados con el contrato',
	'Class:Contact/Attribute:team_list' => 'Equipos',
	'Class:Contact/Attribute:team_list+' => 'Equipos a los que pertenece este contacto',
	'Class:Contact/Attribute:finalclass' => 'Clase',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Person' => 'Persona',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Nombre',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'Identificación de empleado',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Team' => 'Equipo',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Miembros',
	'Class:Team/Attribute:member_list+' => 'Contactos que son parte del equipo',
));

//
// Class: lnkTeamToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTeamToContact' => 'Miembros de Equipo',
	'Class:lnkTeamToContact+' => 'Miembros del equipo',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Equipo',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Miembro',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Ubicación',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Correo Electrónico',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Teléfono',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Rol',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nombre',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organización',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Nombre de la organización',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => 'Descripción',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Tipo',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Contrato',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Mapa de la Red',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Presentación',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Capacitación',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'Artículo de divulgación',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Instrucciones de trabajo',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Estado',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Borrador de documento',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publicado',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'I.C.s',
	'Class:Document/Attribute:ci_list+' => 'Ítems Configurados referenciados en este documento',
	'Class:Document/Attribute:contract_list' => 'Contratos',
	'Class:Document/Attribute:contract_list+' => 'Contratos referenciados en este documento',
	'Class:Document/Attribute:service_list' => 'Servicios',
	'Class:Document/Attribute:service_list+' => 'Servicios referenciados en este documento',
	'Class:Document/Attribute:ticket_list' => 'Tiquetes',
	'Class:Document/Attribute:ticket_list+' => 'Tiquetes referenciados en este documento',
	'Class:Document:PreviewTab' => 'Preview',
));

//
// Class: WebDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:WebDoc' => 'Documento Web',
	'Class:WebDoc+' => 'Documento disponible en otro servidor Web',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Note' => 'Nota',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Texto',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FileDoc' => 'Documento (archivo)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Contenido',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Licence' => 'Licencia',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Proveedor',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:product' => 'Producto',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Nombre',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Fecha de inicio',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Fecha de finalización',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Llave',
	'Class:Licence/Attribute:licence_key+' => 'Llave o cógido (hash) de la licencia',
	'Class:Licence/Attribute:scope' => 'Ámbito',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Límite de uso',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Uso',
	'Class:Licence/Attribute:usage_list+' => 'Instancias/Aplicaciones que estan usando esta licencia',
));

//
// Class: Subnet
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Subnet' => 'Sub-Red',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Nombre',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organización propietaria',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Descripción',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => 'Número IP',
	'Class:Subnet/Attribute:ip_mask' => 'Máscara IP',
	'Class:Subnet/Attribute:ip_mask+' => 'Máscara de la red IP',
));

//
// Class: Patch
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Patch' => 'Parche',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nombre',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Descripción',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Ámbito de la aplicación',
	'Class:Patch/Attribute:target_sw+' => 'Software destino (S.O. o aplicación)',
	'Class:Patch/Attribute:version' => 'Versión',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Tipo',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Aplicación',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'S.O',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Seguridad',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Paquete de Servicio',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Dispositivos',
	'Class:Patch/Attribute:ci_list+' => 'Dispositivos donde el parche esta instalado',
));

//
// Class: Software
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nombre',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Descripción',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Instalaciones',
	'Class:Software/Attribute:instance_list+' => 'Instancias de este software',
	'Class:Software/Attribute:finalclass' => 'Clase',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Application' => 'Aplicación',
	'Class:Application+' => 'Aplicación/Programa',
	'Class:Application/Attribute:name' => 'Nombre',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Descripción',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Instalaciones',
	'Class:Application/Attribute:instance_list+' => 'Instancias de esta aplicación',
));

//
// Class: DBServer
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DBServer' => 'Base de datos',
	'Class:DBServer+' => 'Software de Base de Datos',
	'Class:DBServer/Attribute:instance_list' => 'Instalaciones',
	'Class:DBServer/Attribute:instance_list+' => 'Instancia de este servidor de Base de Datos',
));

//
// Class: lnkPatchToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkPatchToCI' => 'Uso del parche',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Parche',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Parche',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'I.C.s',
	'Class:lnkPatchToCI/Attribute:ci_id+' => 'ID de los Ítems Configurados',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'I.C.s',
	'Class:lnkPatchToCI/Attribute:ci_name+' => 'Nombre de los I.C.s',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:FunctionalCI' => 'Ítem Configurado Funcional',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nombre',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Estado',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Implementación',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Producción',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organización propietaria',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Organización propietaria',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Criticidad para el negocio',
	'Class:FunctionalCI/Attribute:importance+' => 'Qué tan crítco es para el negocio este ítem',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Alto',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => 'Alto grado de importancia',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Bajo',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => 'Bajo grado de importancia',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Medio',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => 'Grado medio de importancia',
	'Class:FunctionalCI/Attribute:contact_list' => 'Contactos',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Contactos para este I.C.s',
	'Class:FunctionalCI/Attribute:document_list' => 'Documentos',
	'Class:FunctionalCI/Attribute:document_list+' => 'Documentación para este I.C.s',
	'Class:FunctionalCI/Attribute:solution_list' => 'Soluciones',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Soluciones que estan usando este I.C.s',
	'Class:FunctionalCI/Attribute:contract_list' => 'Contratos',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Contratos soportando este I.C.s',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Tiquetes',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Tiquetes relacionados con este I.C.s',
	'Class:FunctionalCI/Attribute:finalclass' => 'Clase',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:SoftwareInstance' => 'Instancia de Software',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Licencia',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Licencia',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Versión',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Descripción',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ApplicationInstance' => 'Instancia de aplicación',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
));

//
// Class: DBServerInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DBServerInstance' => 'Instancia de Servidor de BD',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Bases de Datos',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Fuentes de Bases de Datos',
));

//
// Class: DatabaseInstance
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:DatabaseInstance' => 'Instancia de Base de Datos',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Servidor de Base de Datos',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Versión de Base de Datos',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Descripción',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ApplicationSolution' => 'Soluciones',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Descripción',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'I.C.s',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'I.C.s que conforman esta solución',
	'Class:ApplicationSolution/Attribute:process_list' => 'Procesos de Negocios',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Procesos de negocios que dependen en la solución',
));

//
// Class: BusinessProcess
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:BusinessProcess' => 'Procesos de negocios',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Descripción',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Soluciones',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Soluciones en la que los procesos se apoyan',
));

//
// Class: ConnectableCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ConnectableCI' => 'I.C.s conectable',
	'Class:ConnectableCI+' => 'I.C.s físico',
	'Class:ConnectableCI/Attribute:brand' => 'Marca',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Modelo',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Número de Serie',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Placa de Referencia',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NetworkInterface' => 'Interfase de Red',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Tipo Lógico',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Respaldo',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Lógico',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Puerto',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primario',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secundario',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Tipo Físico',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'Dirección IP',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'Máscara IP',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'Dirección MAC',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Velocidad',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Desconocido',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Conectado a',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Interfase conectada',
	'Class:NetworkInterface/Attribute:connected_name' => 'Conectado a',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Dispositivo Conectado',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Tipo de Enlace',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => 'Enlace de Subida',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => 'Enlace de Bajada',
));

//
// Class: Device
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Device' => 'Dispositivo',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Interfases de Red',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => 'Tipo de CPU',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => 'Memoria RAM',
	'Class:PC/Attribute:hdd' => 'Disco Duro',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'Familia de S.O',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'Versión de S.O',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Aplicaciones',
	'Class:PC/Attribute:application_list+' => 'Aplicaciones/Programas instalados en este PC',
	'Class:PC/Attribute:patch_list' => 'Parches',
	'Class:PC/Attribute:patch_list+' => 'Parches instalados en este PC',
));

//
// Class: MobileCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:MobileCI' => 'I.C.s Móvil',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:MobilePhone' => 'Teléfono Celular',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Número de Teléfono',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN del Hardware',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:InfrastructureCI' => 'I.C.s de Infraestructura',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Descripción',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Ubicación',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Ubicación',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Detalles de la ubicación',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'IP de Administración',
	'Class:InfrastructureCI/Attribute:management_ip+' => 'Número IP para la Adminstración',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Pasarela por defecto',
	'Class:InfrastructureCI/Attribute:default_gateway+' => 'Pararela por defecto (Default Gateway)',
));

//
// Class: NetworkDevice
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NetworkDevice' => 'Dispositivo de Red',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Tipo',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'Acelerador de enlace WAN',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Corta Fuego',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Concentrador',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Balanceador de Carga',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Enrutador',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Switch',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'Versión de I.O.S',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP de Lectura',
	'Class:NetworkDevice/Attribute:snmp_read+' => 'Comunidad SNMP de lectura',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP de Escritura',
	'Class:NetworkDevice/Attribute:snmp_write+' => 'Comunidad SNMP de escritura',
));

//
// Class: Server
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Disco Duro',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'Familia de S.O',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'Versión de S.O',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Aplicaciones',
	'Class:Server/Attribute:application_list+' => 'Applications installed on this server',
	'Class:Server/Attribute:patch_list' => 'Parches',
	'Class:Server/Attribute:patch_list+' => 'Patches installed on this server',
));

//
// Class: Printer
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Printer' => 'Impresora',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Tipo',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Impresora',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Tecnología',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Chorro de Tinta',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkCIToDoc' => 'Doc/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'I.C.s',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'I.C.s',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Nombre del Documento',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Tipo de Documento',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Estado del Documento',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkCIToContact' => 'CI/Contact',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'I.C.s',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'I.C.s',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Contacto',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Contacto',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'Correo Electrónico del Contacto',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Rol',
	'Class:lnkCIToContact/Attribute:role+' => 'Rol del contacto con respecto al I.C.s',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkSolutionToCI' => 'I.C.s/Solución',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Soluciones',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Soluciones',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'I.C.s',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'I.C.s',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utilidad',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utilidad del I.C.s en la solución',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkProcessToSolution' => 'Procesos de Negocios/Solución',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Soluciones',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Soluciones',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Procesos',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Procesos',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Razón',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Más inforacióin del vínculo entre el proceso y la solución',
));



//
// Class extensions
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
'Class:Subnet/Tab:IPUsage' => 'Uso de Números IPs',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfases que tienen IP en el rango: <em>%1$s</em> hasta <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IPs libres',
'Class:Subnet/Tab:FreeIPs-count' => 'IPs Libres/sin asignar: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Aquí esta un extracto de las 10 direcciones IPs libres',
));

//
// Application Menu
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
'Menu:Catalogs' => 'Catálogos',
'Menu:Catalogs+' => 'Tipos de Datos',
'Menu:Audit' => 'Auditoría',
'Menu:Audit+' => 'Auditoría',
'Menu:Organization' => 'Organizaciones',
'Menu:Organization+' => 'Todas las Organizaciones',
'Menu:Application' => 'Aplicaciones',
'Menu:Application+' => 'Todas las Aplicaiones/Pogramas',
'Menu:DBServer' => 'Servidores de Base de Datos',
'Menu:DBServer+' => 'Servidores de Base de Datos',
'Menu:Audit' => 'Auditoría',
'Menu:ConfigManagement' => 'Gestión de Configuración',
'Menu:ConfigManagement+' => 'Gestión de Configuración',
'Menu:ConfigManagementOverview' => 'Visión General',
'Menu:ConfigManagementOverview+' => 'Visión General',
'Menu:Contact' => 'Contactos',
'Menu:Contact+' => 'Contactos',
'Menu:Person' => 'Personas',
'Menu:Person+' => 'Todas las Personas',
'Menu:Team' => 'Equipos',
'Menu:Team+' => 'Todos los Equipos de Trabajo',
'Menu:Document' => 'Documentos',
'Menu:Document+' => 'Todos los Documentos',
'Menu:Location' => 'Ubicaciones',
'Menu:Location+' => 'Todas las Ubicaciones',
'Menu:ConfigManagementCI' => 'I.C.s',
'Menu:ConfigManagementCI+' => 'Todos los I.C.s',
'Menu:BusinessProcess' => 'Procesos de Negocios',
'Menu:BusinessProcess+' => 'Todos los Procesos de Negocios',
'Menu:ApplicationSolution' => 'Soluciones',
'Menu:ApplicationSolution+' => 'Todas las Soluciones',
'Menu:ConfigManagementSoftware' => 'Gestión de Aplicaciones',
'Menu:Licence' => 'Licencias',
'Menu:Licence+' => 'Todas las Licencias',
'Menu:Patch' => 'Parches',
'Menu:Patch+' => 'Todos los parches',
'Menu:ApplicationInstance' => 'Software Instalado',
'Menu:ApplicationInstance+' => 'Aplicaciones y Servidores de Base de Datos',
'Menu:ConfigManagementHardware' => 'Infrastructure Management',
'Menu:Subnet' => 'Sub-Redes',
'Menu:Subnet+' => 'Todas las Sub-Redes',
'Menu:NetworkDevice' => 'Dispositivos de Red',
'Menu:NetworkDevice+' => 'Todos los Dispositivos de Red',
'Menu:Server' => 'Servidores',
'Menu:Server+' => 'Todos los Servidores',
'Menu:Printer' => 'Impresoras',
'Menu:Printer+' => 'Todas las Impresoras',
'Menu:MobilePhone' => 'Teléfonos Celulares',
'Menu:MobilePhone+' => 'Todos los Teléfonos Celulares',
'Menu:PC' => 'PCs (Computadores de Personales',
'Menu:PC+' => 'Todos los PCs (Computadores de Personales',
'Menu:Group' => 'Grupos de ICs',
'Menu:Group+' => 'Grupos de ICs',
'Menu:ConfigManagement:Shortcuts' => 'Atajos',
'Menu:ConfigManagement:AllContacts' => 'Todos los Contactos: %1$d',
));
?>

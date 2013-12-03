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


//
// Class: Ticket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => 'Ticket',
	'Class:Ticket/Attribute:ref' => 'Ref',
	'Class:Ticket/Attribute:ref+' => 'Ref',
	'Class:Ticket/Attribute:org_id' => 'Organización',
	'Class:Ticket/Attribute:org_id+' => 'Organización',
	'Class:Ticket/Attribute:org_name' => 'Organización',
	'Class:Ticket/Attribute:org_name+' => 'Organización',
	'Class:Ticket/Attribute:caller_id' => 'Reportado por',
	'Class:Ticket/Attribute:caller_id+' => 'Reportado por',
	'Class:Ticket/Attribute:caller_name' => 'Reportado por',
	'Class:Ticket/Attribute:caller_name+' => 'Reportado por',
	'Class:Ticket/Attribute:team_id' => 'Grupo',
	'Class:Ticket/Attribute:team_id+' => 'Grupo',
	'Class:Ticket/Attribute:team_name' => 'Grupo de Trabajo',
	'Class:Ticket/Attribute:team_name+' => 'Grupo de Trabajo',
	'Class:Ticket/Attribute:agent_id' => 'Analista',
	'Class:Ticket/Attribute:agent_id+' => 'Analista',
	'Class:Ticket/Attribute:agent_name' => 'Analista',
	'Class:Ticket/Attribute:agent_name+' => 'Analista',
	'Class:Ticket/Attribute:title' => 'Asunto',
	'Class:Ticket/Attribute:title+' => 'Asunto',
	'Class:Ticket/Attribute:description' => 'Descripción',
	'Class:Ticket/Attribute:description+' => 'Descripción',
	'Class:Ticket/Attribute:start_date' => 'Fecha de Inicio',
	'Class:Ticket/Attribute:start_date+' => 'Fecha de Inicio',
	'Class:Ticket/Attribute:end_date' => 'Fecha de Fin',
	'Class:Ticket/Attribute:end_date+' => 'Fecha de Fin',
	'Class:Ticket/Attribute:last_update' => 'Última Actualización',
	'Class:Ticket/Attribute:last_update+' => 'Última Actualización',
	'Class:Ticket/Attribute:close_date' => 'Fecha de Cierre',
	'Class:Ticket/Attribute:close_date+' => 'Fecha de Cierre',
	'Class:Ticket/Attribute:private_log' => 'Bitácora Privada',
	'Class:Ticket/Attribute:private_log+' => 'Bitácora Privada',
	'Class:Ticket/Attribute:contacts_list' => 'Contactos',
	'Class:Ticket/Attribute:contacts_list+' => 'Contactos',
	'Class:Ticket/Attribute:functionalcis_list' => 'ECs',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Elementos de Configuración',
	'Class:Ticket/Attribute:workorders_list' => 'Ordenes de Trabajo',
	'Class:Ticket/Attribute:workorders_list+' => 'Ordenes de Trabajo',
	'Class:Ticket/Attribute:finalclass' => 'Clase',
	'Class:Ticket/Attribute:finalclass+' => 'Clase',
));


//
// Class: lnkContactToTicket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContactToTicket' => 'Relación Contacto y Ticket',
	'Class:lnkContactToTicket+' => 'Relación Contacto y Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => 'Ref',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contacto',
	'Class:lnkContactToTicket/Attribute:contact_id+' => 'Contacto',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Correo Electrónico',
	'Class:lnkContactToTicket/Attribute:contact_email+' => 'Correo Electrónico',
	'Class:lnkContactToTicket/Attribute:role' => 'Rol',
	'Class:lnkContactToTicket/Attribute:role+' => 'Rol',
));

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
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'EC',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => 'Impacto',
));


//
// Class: WorkOrder
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:WorkOrder' => 'Orden de Trabajo',
	'Class:WorkOrder+' => 'Orden de Trabajo',
	'Class:WorkOrder/Attribute:name' => 'Nombre',
	'Class:WorkOrder/Attribute:name+' => 'Nombre de la Orden de Trabajo',
	'Class:WorkOrder/Attribute:status' => 'Estatus',
	'Class:WorkOrder/Attribute:status+' => 'Estatus',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Abierto',
	'Class:WorkOrder/Attribute:status/Value:open+' => 'Abierto',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Cerrado',
	'Class:WorkOrder/Attribute:status/Value:closed+' => 'Cerrado',
	'Class:WorkOrder/Attribute:description' => 'Descripción',
	'Class:WorkOrder/Attribute:description+' => 'Descripción',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Ref. Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => 'Ref. Ticket',
	'Class:WorkOrder/Attribute:team_id' => 'Grupo',
	'Class:WorkOrder/Attribute:team_id+' => 'Grupo',
	'Class:WorkOrder/Attribute:team_name' => 'Grupo de Trabajo',
	'Class:WorkOrder/Attribute:team_name+' => 'Grupo de Trabajo',
	'Class:WorkOrder/Attribute:agent_id' => 'Analista',
	'Class:WorkOrder/Attribute:agent_id+' => 'Analista',
	'Class:WorkOrder/Attribute:agent_email' => 'Correo Electrónico del Analista',
	'Class:WorkOrder/Attribute:agent_email+' => 'Correo Electrónico del Analista',
	'Class:WorkOrder/Attribute:start_date' => 'Fecha de Inicio',
	'Class:WorkOrder/Attribute:start_date+' => 'Fecha de Inicio',
	'Class:WorkOrder/Attribute:end_date' => 'Fecha de Fin',
	'Class:WorkOrder/Attribute:end_date+' => 'Fecha de Fin',
	'Class:WorkOrder/Attribute:log' => 'Bitácora',
	'Class:WorkOrder/Attribute:log+' => 'Bitácora',
	'Class:WorkOrder/Stimulus:ev_close' => 'Cerrar',
	'Class:WorkOrder/Stimulus:ev_close+' => 'Cerrar',
));


// Fieldset translation
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(

	'Ticket:baseinfo' => 'Información General',
	'Ticket:date' => 'Fechas',
	'Ticket:contact' => 'Contactos',
	'Ticket:moreinfo' => 'Más Información',
	'Ticket:relation' => 'Relaciones',
	'Ticket:log' => 'Comunicaciones',
	'Ticket:Type' => 'Clasificación',
	'Ticket:support' => 'Soporte',
	'Ticket:resolution' => 'Solución',
	'Ticket:SLA' => 'Reporte de SLA',
	'WorkOrder:Details' => 'Detalles',
	'WorkOrder:Moreinfo' => 'Más Información',

));







?>

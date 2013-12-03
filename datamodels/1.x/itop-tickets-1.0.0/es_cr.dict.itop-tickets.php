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


//
// Class: Ticket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Ticket' => 'Tiquete',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referencia',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:title' => 'Título',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:ticket_log' => 'Bitácora',
	'Class:Ticket/Attribute:ticket_log+' => '',
	'Class:Ticket/Attribute:start_date' => 'Fecha de Reporte',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:document_list' => 'Documentos',
	'Class:Ticket/Attribute:document_list+' => 'Documentos relacionados con el tiquete',
	'Class:Ticket/Attribute:ci_list' => 'I.C.s',
	'Class:Ticket/Attribute:ci_list+' => 'I.C.s afectados por el incidente',
	'Class:Ticket/Attribute:contact_list' => 'Contactos',
	'Class:Ticket/Attribute:contact_list+' => 'Equipos y personas envueltas',
	'Class:Ticket/Attribute:finalclass' => 'Clase',
	'Class:Ticket/Attribute:finalclass+' => '',
));

//
// Class: lnkTicketToDoc
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTicketToDoc' => 'Tiquete/Documentación',
	'Class:lnkTicketToDoc+' => '',
	'Class:lnkTicketToDoc/Attribute:ticket_id' => 'Tiquete',
	'Class:lnkTicketToDoc/Attribute:ticket_id+' => 'Identificación del Tiquete',
	'Class:lnkTicketToDoc/Attribute:ticket_ref' => '# de Tiquete',
	'Class:lnkTicketToDoc/Attribute:ticket_ref+' => 'Número de Tiquete',
	'Class:lnkTicketToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkTicketToDoc/Attribute:document_id+' => 'Identificación del Documento',
	'Class:lnkTicketToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkTicketToDoc/Attribute:document_name+' => 'Nombre del Documento',
));

//
// Class: lnkTicketToContact
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTicketToContact' => 'Tiquete/Contacto',
	'Class:lnkTicketToContact+' => '',
	'Class:lnkTicketToContact/Attribute:ticket_id' => 'Tiquete',
	'Class:lnkTicketToContact/Attribute:ticket_id+' => 'Identificación del Tiquete',
	'Class:lnkTicketToContact/Attribute:ticket_ref' => '# de Tiquete',
	'Class:lnkTicketToContact/Attribute:ticket_ref+' => 'Número de Tiquete',
	'Class:lnkTicketToContact/Attribute:contact_id' => 'Contacto',
	'Class:lnkTicketToContact/Attribute:contact_id+' => 'Identificación del Contacto',
	'Class:lnkTicketToContact/Attribute:contact_name' => 'Contacto',
	'Class:lnkTicketToContact/Attribute:contact_name+' => 'Nombre del Contacto',
	'Class:lnkTicketToContact/Attribute:contact_email' => 'Correo Electrónico',
	'Class:lnkTicketToContact/Attribute:contact_email+' => '',
	'Class:lnkTicketToContact/Attribute:role' => 'Rol',
	'Class:lnkTicketToContact/Attribute:role+' => '',
));

//
// Class: lnkTicketToCI
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkTicketToCI' => 'Tiquete/I.C.s',
	'Class:lnkTicketToCI+' => '',
	'Class:lnkTicketToCI/Attribute:ticket_id' => 'Tiquete',
	'Class:lnkTicketToCI/Attribute:ticket_id+' => 'Identificación del Tiquete',
	'Class:lnkTicketToCI/Attribute:ticket_ref' => '# de Tiquete',
	'Class:lnkTicketToCI/Attribute:ticket_ref+' => 'Número de Tiquete',
	'Class:lnkTicketToCI/Attribute:ci_id' => 'I.C.s',
	'Class:lnkTicketToCI/Attribute:ci_id+' => '',
	'Class:lnkTicketToCI/Attribute:ci_name' => 'I.C.s',
	'Class:lnkTicketToCI/Attribute:ci_name+' => '',
	'Class:lnkTicketToCI/Attribute:ci_status' => 'Estado de los I.C.s',
	'Class:lnkTicketToCI/Attribute:ci_status+' => '',
));

//
// Class: ResponseTicket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ResponseTicket' => 'Tiquete de Respuesta',
	'Class:ResponseTicket+' => '',
	'Class:ResponseTicket/Attribute:status' => 'Estado',
	'Class:ResponseTicket/Attribute:status+' => '',
	'Class:ResponseTicket/Attribute:status/Value:new' => 'Nuevo',
	'Class:ResponseTicket/Attribute:status/Value:new+' => 'Nuevamente Abierta',
	'Class:ResponseTicket/Attribute:status/Value:frozen' => 'Supendida',
	'Class:ResponseTicket/Attribute:status/Value:frozen+' => '',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto' => 'Escalación/T.P.A(TTO)',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto+' => '',
	'Class:ResponseTicket/Attribute:status/Value:assigned' => 'Asignada',
	'Class:ResponseTicket/Attribute:status/Value:assigned+' => '',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr' => 'Escalación/T.P.R(TTR)',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr+' => '',
	'Class:ResponseTicket/Attribute:status/Value:resolved' => 'Resuelto',
	'Class:ResponseTicket/Attribute:status/Value:resolved+' => '',
	'Class:ResponseTicket/Attribute:status/Value:closed' => 'Cerrado',
	'Class:ResponseTicket/Attribute:status/Value:closed+' => '',
	'Class:ResponseTicket/Attribute:caller_id' => 'Comunicador',
	'Class:ResponseTicket/Attribute:caller_id+' => '',
	'Class:ResponseTicket/Attribute:workgroup_name' => 'Grupo de Trabajo',
	'Class:ResponseTicket/Attribute:workgroup_name+' => '',
	'Class:ResponseTicket/Attribute:org_id' => 'Cliente',
	'Class:ResponseTicket/Attribute:org_id+' => '',
	'Class:ResponseTicket/Attribute:org_name' => 'Cliente',
	'Class:ResponseTicket/Attribute:org_name+' => '',
	'Class:ResponseTicket/Attribute:service_id' => 'Servicio',
	'Class:ResponseTicket/Attribute:service_id+' => 'Identificación del Servicio',
	'Class:ResponseTicket/Attribute:servicesubcategory_id' => 'Elemento de Servicio',
	'Class:ResponseTicket/Attribute:servicesubcategory_id+' => '',
	'Class:ResponseTicket/Attribute:product' => 'Producto',
	'Class:ResponseTicket/Attribute:product+' => '',
	'Class:ResponseTicket/Attribute:impact' => 'Impacto',
	'Class:ResponseTicket/Attribute:impact+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:1' => 'Un Departamento',
	'Class:ResponseTicket/Attribute:impact/Value:1+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:2' => 'Un Servicio',
	'Class:ResponseTicket/Attribute:impact/Value:2+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:3' => 'Una Persona',
	'Class:ResponseTicket/Attribute:impact/Value:3+' => '',
	//'Class:ResponseTicket/Attribute:impact/Value:4' => 'Una División',
	//'Class:ResponseTicket/Attribute:impact/Value:4+' => '',
	'Class:ResponseTicket/Attribute:urgency' => 'Urgencia',
	'Class:ResponseTicket/Attribute:urgency+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:1' => 'Alto',
	'Class:ResponseTicket/Attribute:urgency/Value:1+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:2' => 'Medio',
	'Class:ResponseTicket/Attribute:urgency/Value:2+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:3' => 'Bajo',
	'Class:ResponseTicket/Attribute:urgency/Value:3+' => '',
	'Class:ResponseTicket/Attribute:priority' => 'Priority',
	'Class:ResponseTicket/Attribute:priority+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:1' => 'Alto',
	'Class:ResponseTicket/Attribute:priority/Value:1+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:2' => 'Medio',
	'Class:ResponseTicket/Attribute:priority/Value:2+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:3' => 'Bajo',
	'Class:ResponseTicket/Attribute:priority/Value:3+' => '',
	'Class:ResponseTicket/Attribute:workgroup_id' => 'Grupo de Trabajo',
	'Class:ResponseTicket/Attribute:workgroup_id+' => 'Identificación de Grupo de Trabajo',
	'Class:ResponseTicket/Attribute:agent_id' => 'Agent',
	'Class:ResponseTicket/Attribute:agent_id+' => '',
	'Class:ResponseTicket/Attribute:agent_name' => 'Agent',
	'Class:ResponseTicket/Attribute:agent_name+' => '',
	'Class:ResponseTicket/Attribute:agent_email' => 'Agent email',
	'Class:ResponseTicket/Attribute:agent_email+' => '',
	'Class:ResponseTicket/Attribute:related_change_id' => 'Modificación Relacionada',
	'Class:ResponseTicket/Attribute:related_change_id+' => 'Identificación de Modificación Relacionada',
	'Class:ResponseTicket/Attribute:related_change_ref' => 'Modificación Relacionada',
	'Class:ResponseTicket/Attribute:related_change_ref+' => 'Referencia de Modificación Relacionada',
	'Class:ResponseTicket/Attribute:close_date' => 'Cerrado',
	'Class:ResponseTicket/Attribute:close_date+' => '',
	'Class:ResponseTicket/Attribute:last_update' => 'Última Actualización',
	'Class:ResponseTicket/Attribute:last_update+' => '',
	'Class:ResponseTicket/Attribute:assignment_date' => 'Asignada',
	'Class:ResponseTicket/Attribute:assignment_date+' => '',
	'Class:ResponseTicket/Attribute:escalation_deadline' => 'Plazo de Escalación',
	'Class:ResponseTicket/Attribute:escalation_deadline+' => 'Fecha Límite para Escalar',
	'Class:ResponseTicket/Attribute:closure_deadline' => 'Plazo de Cierre',
	'Class:ResponseTicket/Attribute:closure_deadline+' => 'Fecha Límite para Cierre',
	'Class:ResponseTicket/Attribute:resolution_code' => 'Código de Resolución',
	'Class:ResponseTicket/Attribute:resolution_code+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce' => 'No puede ser reproducido',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate' => 'Tiquete Duplicado',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed' => 'Arreglado',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant' => 'Irrelevante',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant+' => '',
	'Class:ResponseTicket/Attribute:solution' => 'Solución',
	'Class:ResponseTicket/Attribute:solution+' => '',
	'Class:ResponseTicket/Attribute:user_satisfaction' => 'Satisfacción del Usuario',
	'Class:ResponseTicket/Attribute:user_satisfaction+' => '',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1' => 'Muy Satisfecho',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1+' => '1',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2' => 'Bastante Satisfecho',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2+' => '2',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3' => 'Poco Descontento',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3+' => '3',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4' => 'Muy Descontento',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4+' => '4',
	'Class:ResponseTicket/Attribute:user_commment' => 'Comentario del Usuario',
	'Class:ResponseTicket/Attribute:user_commment+' => '',
	'Class:ResponseTicket/Stimulus:ev_assign' => 'Asignar',
	'Class:ResponseTicket/Stimulus:ev_assign+' => '',
	'Class:ResponseTicket/Stimulus:ev_reassign' => 'Re-asignar',
	'Class:ResponseTicket/Stimulus:ev_reassign+' => '',
	'Class:ResponseTicket/Stimulus:ev_timeout' => 'Tiempo Fuera del incidente',
	'Class:ResponseTicket/Stimulus:ev_timeout+' => '',
	'Class:ResponseTicket/Stimulus:ev_resolve' => 'Marcar como Resuelto',
	'Class:ResponseTicket/Stimulus:ev_resolve+' => '',
	'Class:ResponseTicket/Stimulus:ev_close' => 'Cerrar',
	'Class:ResponseTicket/Stimulus:ev_close+' => '',
));


?>

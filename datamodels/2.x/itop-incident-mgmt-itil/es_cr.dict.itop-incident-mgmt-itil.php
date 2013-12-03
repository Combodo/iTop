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

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:IncidentManagement' => 'Administración de Incidentes',
	'Menu:IncidentManagement+' => 'Administración de Incidentes',
	'Menu:Incident:Overview' => 'Resumen de Incidentes',
	'Menu:Incident:Overview+' => 'Resumen de Incidentes',
	'Menu:NewIncident' => 'Nuevo Incidente',
	'Menu:NewIncident+' => 'Crear Ticket de Incidente',
	'Menu:SearchIncidents' => 'Búsqueda por Incidentes',
	'Menu:SearchIncidents+' => 'Búsqueda por tickets de Incidente',
	'Menu:Incident:Shortcuts' => 'Accesos Rápidos',
	'Menu:Incident:Shortcuts+' => 'Accesos Rápidos',
	'Menu:Incident:MyIncidents' => 'Incidentes Asignados a Mí',
	'Menu:Incident:MyIncidents+' => 'Incidentes Asignados a Mí (como Analista)',
	'Menu:Incident:EscalatedIncidents' => 'Incidentes Escalados',
	'Menu:Incident:EscalatedIncidents+' => 'Incidentes Escalados',
	'Menu:Incident:OpenIncidents' => 'Incidentes Abiertos',
	'Menu:Incident:OpenIncidents+' => 'Incidentes Abiertos',
	'Menu:Incident:UnassignedIncidents' => 'Incidentes sin Asignación',
	'Menu:Incident:UnassignedIncidents+' => 'Incidentes sin Asignación',
	'Menu:Incident:HelpdeskIncidents' => 'Incidentes Asignados a un Nivel 2',
	'Menu:Incident:HelpdeskIncidents+' => 'Incidentes Asignados a un Nivel 2',
	'UI-IncidentManagementOverview-IncidentByPriority-last-14-days' => 'Incidentes por Prioridad de los Últimos 14 días',
	'UI-IncidentManagementOverview-Last-14-days' => 'Número de Incidentes de los Últimos 14 días',
	'UI-IncidentManagementOverview-OpenIncidentByStatus' => 'Incidentes Abiertos por Estatus',
	'UI-IncidentManagementOverview-OpenIncidentByAgent' => 'Incidentes Abiertos por Analista',
	'UI-IncidentManagementOverview-OpenIncidentByCustomer' => 'Incidentes Abiertos por Cliente',
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

//
// Class: Incident
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Incident' => 'Incidente',
	'Class:Incident+' => 'Incidente',
	'Class:Incident/Attribute:status' => 'Estatus',
	'Class:Incident/Attribute:status+' => 'Estatus',
	'Class:Incident/Attribute:status/Value:new' => 'Nuevo',
	'Class:Incident/Attribute:status/Value:new+' => 'Nuevo',
	'Class:Incident/Attribute:status/Value:escalated_tto' => 'Escalado por Tiempo de Asignación',
	'Class:Incident/Attribute:status/Value:escalated_tto+' => 'Escalado por Tiempo de Asignación',
	'Class:Incident/Attribute:status/Value:assigned' => 'Asignado',
	'Class:Incident/Attribute:status/Value:assigned+' => 'Asignado',
	'Class:Incident/Attribute:status/Value:escalated_ttr' => 'Escalado por Tiempo de Solución',
	'Class:Incident/Attribute:status/Value:escalated_ttr+' => 'Escalado por Tiempo de Solución',
	'Class:Incident/Attribute:status/Value:waiting_for_approval' => 'Esperando Aprobación',
	'Class:Incident/Attribute:status/Value:waiting_for_approval+' => 'Esperando Aprobación',
	'Class:Incident/Attribute:status/Value:pending' => 'Pendiente',
	'Class:Incident/Attribute:status/Value:pending+' => 'Pendiente',
	'Class:Incident/Attribute:status/Value:resolved' => 'Solucionado',
	'Class:Incident/Attribute:status/Value:resolved+' => 'Solucionado',
	'Class:Incident/Attribute:status/Value:closed' => 'Cerrado',
	'Class:Incident/Attribute:status/Value:closed+' => 'Cerrado',
	'Class:Incident/Attribute:impact' => 'Impacto',
	'Class:Incident/Attribute:impact+' => 'Impacto',
	'Class:Incident/Attribute:impact/Value:1' => 'Un Departmento',
	'Class:Incident/Attribute:impact/Value:1+' => 'Un Departmento',
	'Class:Incident/Attribute:impact/Value:2' => 'Un Servicio',
	'Class:Incident/Attribute:impact/Value:2+' => 'Un Servicio',
	'Class:Incident/Attribute:impact/Value:3' => 'Una Persona',
	'Class:Incident/Attribute:impact/Value:3+' => 'Una Persona',
	'Class:Incident/Attribute:priority' => 'Prioridad',
	'Class:Incident/Attribute:priority+' => 'Prioridad',
	'Class:Incident/Attribute:priority/Value:1' => 'Crítica',
	'Class:Incident/Attribute:priority/Value:1+' => 'Crítica',
	'Class:Incident/Attribute:priority/Value:2' => 'Alta',
	'Class:Incident/Attribute:priority/Value:2+' => 'Alta',
	'Class:Incident/Attribute:priority/Value:3' => 'Media',
	'Class:Incident/Attribute:priority/Value:3+' => 'Media',
	'Class:Incident/Attribute:priority/Value:4' => 'Baja',
	'Class:Incident/Attribute:priority/Value:4+' => 'Baja',
	'Class:Incident/Attribute:urgency' => 'Urgencia',
	'Class:Incident/Attribute:urgency+' => 'Urgencia',
	'Class:Incident/Attribute:urgency/Value:1' => 'Crítica',
	'Class:Incident/Attribute:urgency/Value:1+' => 'Critica',
	'Class:Incident/Attribute:urgency/Value:2' => 'Alta',
	'Class:Incident/Attribute:urgency/Value:2+' => 'Alta',
	'Class:Incident/Attribute:urgency/Value:3' => 'Media',
	'Class:Incident/Attribute:urgency/Value:3+' => 'Media',
	'Class:Incident/Attribute:urgency/Value:4' => 'Baja',
	'Class:Incident/Attribute:urgency/Value:4+' => 'Baja',
	'Class:Incident/Attribute:origin' => 'Origen',
	'Class:Incident/Attribute:origin+' => 'Origen',
	'Class:Incident/Attribute:origin/Value:mail' => 'Correo-e',
	'Class:Incident/Attribute:origin/Value:mail+' => 'Correo-e',
	'Class:Incident/Attribute:origin/Value:monitoring' => 'Monitoreo',
	'Class:Incident/Attribute:origin/Value:monitoring+' => 'Monitoreo',
	'Class:Incident/Attribute:origin/Value:phone' => 'Teléfono',
	'Class:Incident/Attribute:origin/Value:phone+' => 'Teléfono',
	'Class:Incident/Attribute:origin/Value:portal' => 'Portal',
	'Class:Incident/Attribute:origin/Value:portal+' => 'Portal',
	'Class:Incident/Attribute:service_id' => 'Servicio',
	'Class:Incident/Attribute:service_id+' => 'Servicio',
	'Class:Incident/Attribute:service_name' => 'Servicio',
	'Class:Incident/Attribute:service_name+' => 'Servicio',
	'Class:Incident/Attribute:servicesubcategory_id' => 'Subcategoría',
	'Class:Incident/Attribute:servicesubcategory_id+' => 'Subcategoría',
	'Class:Incident/Attribute:servicesubcategory_name' => 'Subcategoría',
	'Class:Incident/Attribute:servicesubcategory_name+' => 'Subcategoría de Servicio',
	'Class:Incident/Attribute:escalation_flag' => 'Bandera de Escalamiento',
	'Class:Incident/Attribute:escalation_flag+' => 'Bandera de Escalamiento',
	'Class:Incident/Attribute:escalation_flag/Value:no' => 'No',
	'Class:Incident/Attribute:escalation_flag/Value:no+' => 'No',
	'Class:Incident/Attribute:escalation_flag/Value:yes' => 'Si',
	'Class:Incident/Attribute:escalation_flag/Value:yes+' => 'Si',
	'Class:Incident/Attribute:escalation_reason' => 'Motivo de Escalamiento',
	'Class:Incident/Attribute:escalation_reason+' => 'Motivo de Escalamiento',
	'Class:Incident/Attribute:assignment_date' => 'Fecha de Asignación',
	'Class:Incident/Attribute:assignment_date+' => '',
	'Class:Incident/Attribute:resolution_date' => 'Fecha de Solución',
	'Class:Incident/Attribute:resolution_date+' => '',
	'Class:Incident/Attribute:last_pending_date' => 'Última Fecha de Espera',
	'Class:Incident/Attribute:last_pending_date+' => 'Última Fecha de Espera',
	'Class:Incident/Attribute:cumulatedpending' => 'Espera Acumulada',
	'Class:Incident/Attribute:cumulatedpending+' => 'Espera Acumulada',
	'Class:Incident/Attribute:tto' => 'TDA - Tiempo de Asignación',
	'Class:Incident/Attribute:tto+' => 'Tiempo de Asignación',
	'Class:Incident/Attribute:ttr' => 'TDS - Tiempo de Solución',
	'Class:Incident/Attribute:ttr+' => 'Tiempo de Solución',
	'Class:Incident/Attribute:tto_escalation_deadline' => 'Límite de Tiempo de Asignación',
	'Class:Incident/Attribute:tto_escalation_deadline+' => 'Límite de Tiempo de Asignación',
	'Class:Incident/Attribute:sla_tto_passed' => 'SLA de Tiempo de Asignación Cumplido',
	'Class:Incident/Attribute:sla_tto_passed+' => 'SLA de Tiempo de Asignación Cumplido',
	'Class:Incident/Attribute:sla_tto_over' => 'SLA de Tiempo de Asignación Excedído',
	'Class:Incident/Attribute:sla_tto_over+' => 'SLA de Tiempo de Asignación Excedído',
	'Class:Incident/Attribute:ttr_escalation_deadline' => 'Límite de Tiempo de Solución',
	'Class:Incident/Attribute:ttr_escalation_deadline+' => 'Límite de Tiempo de Solución',
	'Class:Incident/Attribute:sla_ttr_passed' => 'SLA de Tiempo de Solución Cumplido',
	'Class:Incident/Attribute:sla_ttr_passed+' => 'SLA de Tiempo de Solución Cumplido',
	'Class:Incident/Attribute:sla_ttr_over' => 'SLA de Tiempo de Solución Excedído',
	'Class:Incident/Attribute:sla_ttr_over+' => 'SLA de Tiempo de Solución Excedído',
	'Class:Incident/Attribute:time_spent' => 'Tiempo Utilizado',
	'Class:Incident/Attribute:time_spent+' => 'Tiempo Utilizado',
	'Class:Incident/Attribute:resolution_code' => 'Código de Solución',
	'Class:Incident/Attribute:resolution_code+' => 'Código de Solución',
	'Class:Incident/Attribute:resolution_code/Value:assistance' => 'Asistencia',
	'Class:Incident/Attribute:resolution_code/Value:assistance+' => 'Asistencia',
	'Class:Incident/Attribute:resolution_code/Value:bug fixed' => 'Falla Corregida',
	'Class:Incident/Attribute:resolution_code/Value:bug fixed+' => 'Falla Corregida',
	'Class:Incident/Attribute:resolution_code/Value:hardware repair' => 'Reparación de Hardware',
	'Class:Incident/Attribute:resolution_code/Value:hardware repair+' => 'Reparación de Hardware',
	'Class:Incident/Attribute:resolution_code/Value:other' => 'Otro',
	'Class:Incident/Attribute:resolution_code/Value:other+' => 'Otro',
	'Class:Incident/Attribute:resolution_code/Value:software patch' => 'Parche de Software',
	'Class:Incident/Attribute:resolution_code/Value:software patch+' => 'Parche de Software',
	'Class:Incident/Attribute:resolution_code/Value:system update' => 'Actualización de Sistema',
	'Class:Incident/Attribute:resolution_code/Value:system update+' => 'Actualización de Sistema',
	'Class:Incident/Attribute:resolution_code/Value:training' => 'Capacitación',
	'Class:Incident/Attribute:resolution_code/Value:training+' => 'Capacitación',
	'Class:Incident/Attribute:solution' => 'Solución',
	'Class:Incident/Attribute:solution+' => 'Solución',
	'Class:Incident/Attribute:pending_reason' => 'Motivo Pendiente',
	'Class:Incident/Attribute:pending_reason+' => 'Motivo Pendiente',
	'Class:Incident/Attribute:parent_incident_id' => 'Incidente Padre',
	'Class:Incident/Attribute:parent_incident_id+' => 'Incidente Padre',
	'Class:Incident/Attribute:parent_incident_ref' => 'Ref. Incidente Padre',
	'Class:Incident/Attribute:parent_incident_ref+' => 'Ref. Incidente Padre',
	'Class:Incident/Attribute:parent_change_id' => 'Cambio Padre',
	'Class:Incident/Attribute:parent_change_id+' => 'Cambio Padre',
	'Class:Incident/Attribute:parent_change_ref' => 'Ref. Cambio Padre',
	'Class:Incident/Attribute:parent_change_ref+' => 'Ref. Cambio Padre',
	'Class:Incident/Attribute:child_incidents_list' => 'Incidentes Hijos',
	'Class:Incident/Attribute:child_incidents_list+' => 'Incidentes Hijos',
	'Class:Incident/Attribute:related_request_list' => 'Requerimientos Relacionados',
	'Class:Incident/Attribute:related_request_list+' => 'Requerimientos Relacionados',
	'Class:Incident/Attribute:parent_problem_id' => 'Problema Padre',
	'Class:Incident/Attribute:parent_problem_id+' => 'Problema Padre',
	'Class:Incident/Attribute:public_log' => 'Bitácora Pública',
	'Class:Incident/Attribute:public_log+' => 'Bitácora Pública',
	'Class:Incident/Attribute:user_satisfaction' => 'Satisfacción del Usuario',
	'Class:Incident/Attribute:user_satisfaction+' => 'Satisfacción del Usuario',
	'Class:Incident/Attribute:user_satisfaction/Value:1' => 'Muy Satisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:1+' => 'Muy Satisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:2' => 'Satisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:2+' => 'Satisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:3' => 'Insatisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:3+' => 'Insatisfecha',
	'Class:Incident/Attribute:user_satisfaction/Value:4' => 'Muy Insatisfecho',
	'Class:Incident/Attribute:user_satisfaction/Value:4+' => 'Muy Insatisfecho',
	'Class:Incident/Attribute:user_comment' => 'Comentarios del Usuario',
	'Class:Incident/Attribute:user_comment+' => 'Comentarios del Usuario',
	'Class:Incident/Attribute:parent_incident_id_friendlyname' => 'parent_incident_id_friendlyname',
	'Class:Incident/Attribute:parent_incident_id_friendlyname+' => 'parent_incident_id_friendlyname',
	'Class:Incident/Stimulus:ev_assign' => 'Asignar',
	'Class:Incident/Stimulus:ev_assign+' => 'Asignar',
	'Class:Incident/Stimulus:ev_reassign' => 'Reasignar',
	'Class:Incident/Stimulus:ev_reassign+' => 'Reasignar',
	'Class:Incident/Stimulus:ev_pending' => 'Pendiente',
	'Class:Incident/Stimulus:ev_pending+' => 'Pendiente',
	'Class:Incident/Stimulus:ev_timeout' => 'Timeout',
	'Class:Incident/Stimulus:ev_timeout+' => 'Timeout',
	'Class:Incident/Stimulus:ev_autoresolve' => 'Solución Automática',
	'Class:Incident/Stimulus:ev_autoresolve+' => 'Solución Automática',
	'Class:Incident/Stimulus:ev_autoclose' => 'Cierre Automático',
	'Class:Incident/Stimulus:ev_autoclose+' => 'Cierre Automático',
	'Class:Incident/Stimulus:ev_resolve' => 'Marcar como Solucionado',
	'Class:Incident/Stimulus:ev_resolve+' => 'Marcar como Solucionado',
	'Class:Incident/Stimulus:ev_close' => 'Cerrar este Ticket',
	'Class:Incident/Stimulus:ev_close+' => 'Cerrar este Ticket',
	'Class:Incident/Stimulus:ev_reopen' => 'Reabrir',
	'Class:Incident/Stimulus:ev_reopen+' => 'Reabrir',
));


?>

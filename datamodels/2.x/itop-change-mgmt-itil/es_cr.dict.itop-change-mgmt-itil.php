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
	'Menu:ChangeManagement' => 'Administración de Cambios',
	'Menu:Change:Overview' => 'Resumen de Cambios',
	'Menu:Change:Overview+' => 'Resumen de Cambios',
	'Menu:NewChange' => 'Nuevo Cambio',
	'Menu:NewChange+' => 'Crear Ticket de Cambio',
	'Menu:SearchChanges' => 'Búsqueda de Cambios',
	'Menu:SearchChanges+' => 'Búsqueda de Tickets de Cambios',
	'Menu:Change:Shortcuts' => 'Accesos Rápidos',
	'Menu:Change:Shortcuts+' => 'Accesos Rápidos',
	'Menu:WaitingAcceptance' => 'Cambios Esperando ser Aceptados',
	'Menu:WaitingAcceptance+' => 'Cambios Esperando ser Aceptados',
	'Menu:WaitingApproval' => 'Cambios Esperando ser Aprobados',
	'Menu:WaitingApproval+' => 'Cambios Esperando ser Aprobados',
	'Menu:Changes' => 'Cambios Abiertos',
	'Menu:Changes+' => 'Cambios Abiertos',
	'Menu:MyChanges' => 'Cambios Asignados Mí',
	'Menu:MyChanges+' => 'Cambios Asignados a Mí (como Analista)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Cambios por Categoría de los últimos 7 días',
	'UI-ChangeManagementOverview-Last-7-days' => 'Número de Cambios de los últimos 7 días',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Cambios por Dominio de los últimos 7 días',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Cambios por Estatus de los últimos 7 días',
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
// Class: Change
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Change' => 'Cambio',
	'Class:Change+' => 'Cambio',
	'Class:Change/Attribute:status' => 'Estatus',
	'Class:Change/Attribute:status+' => 'Estatus',
	'Class:Change/Attribute:status/Value:new' => 'Nuevo',
	'Class:Change/Attribute:status/Value:new+' => 'Nuevo',
	'Class:Change/Attribute:status/Value:validated' => 'Validado',
	'Class:Change/Attribute:status/Value:validated+' => 'Validado',
	'Class:Change/Attribute:status/Value:rejected' => 'Rechazado',
	'Class:Change/Attribute:status/Value:rejected+' => 'Rechazado',
	'Class:Change/Attribute:status/Value:assigned' => 'Asignado',
	'Class:Change/Attribute:status/Value:assigned+' => 'Asignado',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Planeado',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => 'Planeado',
	'Class:Change/Attribute:status/Value:approved' => 'Aprobado',
	'Class:Change/Attribute:status/Value:approved+' => 'Aprobado',
	'Class:Change/Attribute:status/Value:notapproved' => 'No Aprobado',
	'Class:Change/Attribute:status/Value:notapproved+' => 'No Aprobado',
	'Class:Change/Attribute:status/Value:implemented' => 'Implementado',
	'Class:Change/Attribute:status/Value:implemented+' => 'Implementado',
	'Class:Change/Attribute:status/Value:monitored' => 'Monitoreado',
	'Class:Change/Attribute:status/Value:monitored+' => 'Monitoreado',
	'Class:Change/Attribute:status/Value:closed' => 'Cerrado',
	'Class:Change/Attribute:status/Value:closed+' => 'Cerrado',
	'Class:Change/Attribute:reason' => 'Motivo',
	'Class:Change/Attribute:reason+' => 'Motivo',
	'Class:Change/Attribute:requestor_id' => 'Solicitante',
	'Class:Change/Attribute:requestor_id+' => 'Solicitante',
	'Class:Change/Attribute:requestor_email' => 'Correo Electrónico del Solicitante',
	'Class:Change/Attribute:requestor_email+' => 'Correo Electrónico del Solicitante',
	'Class:Change/Attribute:creation_date' => 'Fecha de Creación',
	'Class:Change/Attribute:creation_date+' => 'Fecha de Creación',
	'Class:Change/Attribute:impact' => 'Impacto',
	'Class:Change/Attribute:impact+' => 'Impacto',
	'Class:Change/Attribute:supervisor_group_id' => 'Supervisor de Grupo de Trabajo',
	'Class:Change/Attribute:supervisor_group_id+' => 'Supervisor de Grupo de Trabajo',
	'Class:Change/Attribute:supervisor_group_name' => 'Supervisor de Grupo de Trabajo',
	'Class:Change/Attribute:supervisor_group_name+' => 'Supervisor de Grupo de Trabajo',
	'Class:Change/Attribute:supervisor_id' => 'Supervisor',
	'Class:Change/Attribute:supervisor_id+' => 'Supervisor',
	'Class:Change/Attribute:supervisor_email' => 'Correo Electrónico del Supervisor',
	'Class:Change/Attribute:supervisor_email+' => 'Correo Electrónico del Supervisor',
	'Class:Change/Attribute:manager_group_id' => 'Gerente del Grupo de Trabajo',
	'Class:Change/Attribute:manager_group_id+' => 'Gerente del Grupo de Trabajo',
	'Class:Change/Attribute:manager_group_name' => 'Gerente del Grupo de Trabajo',
	'Class:Change/Attribute:manager_group_name+' => 'Gerente del Grupo de Trabajo',
	'Class:Change/Attribute:manager_id' => 'Gerente',
	'Class:Change/Attribute:manager_id+' => 'Gerente',
	'Class:Change/Attribute:manager_email' => 'Correo Electrónico del Gerente',
	'Class:Change/Attribute:manager_email+' => 'Correo Electrónico del Gerente',
	'Class:Change/Attribute:outage' => 'Falla',
	'Class:Change/Attribute:outage+' => 'Falla',
	'Class:Change/Attribute:outage/Value:no' => 'No',
	'Class:Change/Attribute:outage/Value:no+' => 'No',
	'Class:Change/Attribute:outage/Value:yes' => 'Si',
	'Class:Change/Attribute:outage/Value:yes+' => 'Si',
	'Class:Change/Attribute:fallback' => 'Plan en caso de Falla',
	'Class:Change/Attribute:fallback+' => 'Plan en caso de Falla',
	'Class:Change/Attribute:parent_id' => 'Cambio Padre',
	'Class:Change/Attribute:parent_id+' => 'Cambio Padre',
	'Class:Change/Attribute:parent_name' => 'Ref. Cambio Padre',
	'Class:Change/Attribute:parent_name+' => 'Ref. Cambio Padre',
	'Class:Change/Attribute:related_request_list' => 'Requerimientos Relacionados',
	'Class:Change/Attribute:related_request_list+' => 'Requerimientos Relacionados',
	'Class:Change/Attribute:related_problems_list' => 'Problemas Relacionados',
	'Class:Change/Attribute:related_problems_list+' => 'Problemas Relacionados',
	'Class:Change/Attribute:related_incident_list' => 'Incidentes Relacionados',
	'Class:Change/Attribute:related_incident_list+' => 'Incidentes Relacionados',
	'Class:Change/Attribute:child_changes_list' => 'Cambios Hijo',
	'Class:Change/Attribute:child_changes_list+' => 'Cambios Hijo',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nombre del Padre',
	'Class:Change/Attribute:parent_id_friendlyname+' => 'Nombre del Padre',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Tipo de Cambio',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => 'Tipo de Cambio',
	'Class:Change/Stimulus:ev_validate' => 'Validar',
	'Class:Change/Stimulus:ev_validate+' => 'Validar',
	'Class:Change/Stimulus:ev_reject' => 'Rechazar',
	'Class:Change/Stimulus:ev_reject+' => 'Rechazar',
	'Class:Change/Stimulus:ev_assign' => 'Asignar',
	'Class:Change/Stimulus:ev_assign+' => 'Asignar',
	'Class:Change/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:Change/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:Change/Stimulus:ev_plan' => 'Planificar',
	'Class:Change/Stimulus:ev_plan+' => 'Planificar',
	'Class:Change/Stimulus:ev_approve' => 'Aprobar',
	'Class:Change/Stimulus:ev_approve+' => 'Aprobar',
	'Class:Change/Stimulus:ev_replan' => 'Replanificar',
	'Class:Change/Stimulus:ev_replan+' => 'Replanificar',
	'Class:Change/Stimulus:ev_notapprove' => 'Rechazar',
	'Class:Change/Stimulus:ev_notapprove+' => 'Rechazar',
	'Class:Change/Stimulus:ev_implement' => 'Implementar',
	'Class:Change/Stimulus:ev_implement+' => 'Implementar',
	'Class:Change/Stimulus:ev_monitor' => 'Monitorear',
	'Class:Change/Stimulus:ev_monitor+' => 'Monitorear',
	'Class:Change/Stimulus:ev_finish' => 'Finalizar',
	'Class:Change/Stimulus:ev_finish+' => 'Finalizar',
));

//
// Class: RoutineChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:RoutineChange' => 'Cambio Rutinario',
	'Class:RoutineChange+' => 'Cambio Rutinario',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Validar',
	'Class:RoutineChange/Stimulus:ev_validate+' => 'Validar',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Rechazar',
	'Class:RoutineChange/Stimulus:ev_reject+' => 'Rechazar',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Asignar',
	'Class:RoutineChange/Stimulus:ev_assign+' => 'Asignar',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:RoutineChange/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Planificar',
	'Class:RoutineChange/Stimulus:ev_plan+' => 'Planificar',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Aprobar',
	'Class:RoutineChange/Stimulus:ev_approve+' => 'Aprobar',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Replanificar',
	'Class:RoutineChange/Stimulus:ev_replan+' => 'Replanificar',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'No Aprobar',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => 'No Aprobar',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Implementar',
	'Class:RoutineChange/Stimulus:ev_implement+' => 'Implementar',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Monitorear',
	'Class:RoutineChange/Stimulus:ev_monitor+' => 'Monitorear',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:RoutineChange/Stimulus:ev_finish+' => 'Finalizar',
));

//
// Class: ApprovedChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:ApprovedChange' => 'Cambios Aprobados',
	'Class:ApprovedChange+' => 'Cambios Aprobados',
	'Class:ApprovedChange/Attribute:approval_date' => 'Fecha de Aprobación',
	'Class:ApprovedChange/Attribute:approval_date+' => 'Fecha de Aprobación',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Comentario de Aprobación',
	'Class:ApprovedChange/Attribute:approval_comment+' => 'Comentario de Aprobación',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Validar',
	'Class:ApprovedChange/Stimulus:ev_validate+' => 'Validar',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Rechazar',
	'Class:ApprovedChange/Stimulus:ev_reject+' => 'Rechazar',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Asignar',
	'Class:ApprovedChange/Stimulus:ev_assign+' => 'Asignar',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Planear',
	'Class:ApprovedChange/Stimulus:ev_plan+' => 'Planear',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Aprobar',
	'Class:ApprovedChange/Stimulus:ev_approve+' => 'Aprobar',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Replanificar',
	'Class:ApprovedChange/Stimulus:ev_replan+' => 'Replanificar',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'No Aprobado',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => 'No Aprobado',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Implementar',
	'Class:ApprovedChange/Stimulus:ev_implement+' => 'Implementar',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Monitorear',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => 'Monitorear',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:ApprovedChange/Stimulus:ev_finish+' => 'Finalizar',
));

//
// Class: NormalChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:NormalChange' => 'Cambio Normal',
	'Class:NormalChange+' => 'Cambio Normal',
	'Class:NormalChange/Attribute:acceptance_date' => 'Fecha de Aceptación',
	'Class:NormalChange/Attribute:acceptance_date+' => 'Fecha de Aceptación',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Comentario de Aceptación',
	'Class:NormalChange/Attribute:acceptance_comment+' => 'Comentario de Aceptación',
	'Class:NormalChange/Stimulus:ev_validate' => 'Validar',
	'Class:NormalChange/Stimulus:ev_validate+' => 'Validar',
	'Class:NormalChange/Stimulus:ev_reject' => 'Rechazar',
	'Class:NormalChange/Stimulus:ev_reject+' => 'Rechazar',
	'Class:NormalChange/Stimulus:ev_assign' => 'Asignar',
	'Class:NormalChange/Stimulus:ev_assign+' => 'Asignar',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:NormalChange/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:NormalChange/Stimulus:ev_plan' => 'Planear',
	'Class:NormalChange/Stimulus:ev_plan+' => 'Planear',
	'Class:NormalChange/Stimulus:ev_approve' => 'Aprobar',
	'Class:NormalChange/Stimulus:ev_approve+' => 'Aprobar',
	'Class:NormalChange/Stimulus:ev_replan' => 'Replanificar',
	'Class:NormalChange/Stimulus:ev_replan+' => 'Replanificar',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'No Aprobar',
	'Class:NormalChange/Stimulus:ev_notapprove+' => 'No Aprobar',
	'Class:NormalChange/Stimulus:ev_implement' => 'Implementar',
	'Class:NormalChange/Stimulus:ev_implement+' => 'Implementar',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Monitorear',
	'Class:NormalChange/Stimulus:ev_monitor+' => 'Monitorear',
	'Class:NormalChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:NormalChange/Stimulus:ev_finish+' => 'Finalizar',
));

//
// Class: EmergencyChange
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:EmergencyChange' => 'Cambio de Emergencia',
	'Class:EmergencyChange+' => 'Cambio de Emergencia',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Validar',
	'Class:EmergencyChange/Stimulus:ev_validate+' => 'Validar',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Rechazar',
	'Class:EmergencyChange/Stimulus:ev_reject+' => 'Rechazar',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Asignar',
	'Class:EmergencyChange/Stimulus:ev_assign+' => 'Asignar',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Planear',
	'Class:EmergencyChange/Stimulus:ev_plan+' => 'Planear',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Aprobar',
	'Class:EmergencyChange/Stimulus:ev_approve+' => 'Aprovr',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Replanificar',
	'Class:EmergencyChange/Stimulus:ev_replan+' => 'Replanificar',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'No Aprobado',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => 'No Aprobado',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Implementar',
	'Class:EmergencyChange/Stimulus:ev_implement+' => 'Implementar',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Monitorear',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => 'Monitorear',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:EmergencyChange/Stimulus:ev_finish+' => 'Finalizar',
));

?>

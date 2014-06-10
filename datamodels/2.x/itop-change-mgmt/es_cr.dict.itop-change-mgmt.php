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
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Cambios por Categoría de los Últimos 7 días',
	'UI-ChangeManagementOverview-Last-7-days' => 'Número de Cambios de los Últimos 7 días',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Cambios por Dominio de los Últimos 7 días',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Cambios por Estatus de los Últimos 7 días',
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
	'Class:Change/Attribute:status/Value:assigned' => 'Asignado',
	'Class:Change/Attribute:status/Value:assigned+' => 'Asignado',
	'Class:Change/Attribute:status/Value:planned' => 'Planeado',
	'Class:Change/Attribute:status/Value:planned+' => 'Planeado',
	'Class:Change/Attribute:status/Value:rejected' => 'Rechazado',
	'Class:Change/Attribute:status/Value:rejected+' => 'Rechazado',
	'Class:Change/Attribute:status/Value:approved' => 'Aprobado',
	'Class:Change/Attribute:status/Value:approved+' => 'Aprobado',
	'Class:Change/Attribute:status/Value:closed' => 'Cerrado',
	'Class:Change/Attribute:status/Value:closed+' => 'Cerrado',
	'Class:Change/Attribute:category' => 'Categoría',
	'Class:Change/Attribute:category+' => 'Categoría',
	'Class:Change/Attribute:category/Value:application' => 'Aplicación',
	'Class:Change/Attribute:category/Value:application+' => 'Aplicación',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
	'Class:Change/Attribute:category/Value:hardware+' => 'Hardware',
	'Class:Change/Attribute:category/Value:network' => 'Red',
	'Class:Change/Attribute:category/Value:network+' => 'Red',
	'Class:Change/Attribute:category/Value:other' => 'Otro',
	'Class:Change/Attribute:category/Value:other+' => 'Otro',
	'Class:Change/Attribute:category/Value:software' => 'Software',
	'Class:Change/Attribute:category/Value:software+' => 'Software',
	'Class:Change/Attribute:category/Value:system' => 'Sistema',
	'Class:Change/Attribute:category/Value:system+' => 'Sistema',
	'Class:Change/Attribute:reject_reason' => 'Motivo de Rechazo',
	'Class:Change/Attribute:reject_reason+' => 'Motivo de Rechazo',
	'Class:Change/Attribute:changemanager_id' => 'Administrador de Cambios',
	'Class:Change/Attribute:changemanager_id+' => 'Administrador de Cambios',
	'Class:Change/Attribute:changemanager_email' => 'Correo Electrónico del Administrador de Cambios',
	'Class:Change/Attribute:changemanager_email+' => 'Correo Electrónico del Administrador de Cambios',
	'Class:Change/Attribute:parent_id' => 'Cambio Padre',
	'Class:Change/Attribute:parent_id+' => 'Cambio Padre',
	'Class:Change/Attribute:parent_name' => 'Ref. Cambio Padre',
	'Class:Change/Attribute:parent_name+' => 'Ref. Cambio Padre',
	'Class:Change/Attribute:creation_date' => 'Fecha de Creación',
	'Class:Change/Attribute:creation_date+' => 'Fecha de Creación',
	'Class:Change/Attribute:approval_date' => 'Fecha de Aprobación',
	'Class:Change/Attribute:approval_date+' => 'Fecha de Aprobación',
	'Class:Change/Attribute:fallback_plan' => 'Plan en caso de Falla',
	'Class:Change/Attribute:fallback_plan+' => 'Plan en caso de Falla',
	'Class:Change/Attribute:related_request_list' => 'Requerimientos Relacionados',
	'Class:Change/Attribute:related_request_list+' => 'Requerimientos Relacionados',
	'Class:Change/Attribute:related_incident_list' => 'Incidentes Relacionados',
	'Class:Change/Attribute:related_incident_list+' => 'Incidentes Relacionados',
	'Class:Change/Attribute:related_problems_list' => 'Problemas Relacionados',
	'Class:Change/Attribute:related_problems_list+' => 'Problemas Relacionados',
	'Class:Change/Attribute:child_changes_list' => 'Cambios Hijo',
	'Class:Change/Attribute:child_changes_list+' => 'Cambios Hijo',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nombre del Cambio Padre',
	'Class:Change/Attribute:parent_id_friendlyname+' => 'Nombre del Cambio Padre',
	'Class:Change/Stimulus:ev_assign' => 'Asignar',
	'Class:Change/Stimulus:ev_assign+' => 'Asignar',
	'Class:Change/Stimulus:ev_plan' => 'Planificar',
	'Class:Change/Stimulus:ev_plan+' => 'Planificar',
	'Class:Change/Stimulus:ev_reject' => 'Rechazar',
	'Class:Change/Stimulus:ev_reject+' => 'Rechazar',
	'Class:Change/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:Change/Stimulus:ev_reopen+' => 'Re-abrir',
	'Class:Change/Stimulus:ev_approve' => 'Aprobar',
	'Class:Change/Stimulus:ev_approve+' => 'Aprobar',
	'Class:Change/Stimulus:ev_finish' => 'Finalizar',
	'Class:Change/Stimulus:ev_finish+' => 'Finalizar',
	'Class:Change/Attribute:outage' => 'Falla',
	'Class:Change/Attribute:outage+' => 'Falla',
	'Class:Change/Attribute:outage/Value:no' => 'No',
	'Class:Change/Attribute:outage/Value:no+' => 'No',
	'Class:Change/Attribute:outage/Value:yes' => 'Si',
	'Class:Change/Attribute:outage/Value:yes+' => 'Si',
));

?>

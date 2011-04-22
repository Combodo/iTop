<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ChangeManagement' => 'Gestione dei cambi',
	'Menu:Change:Overview' => 'Panoramica',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nuovo cambio',
	'Menu:NewChange+' => 'Crea un ticket per un nuovo cambio',
	'Menu:SearchChanges' => 'Cerca per cambi',
	'Menu:SearchChanges+' => 'Cerca i cambi per tickets',
	'Menu:Change:Shortcuts' => 'Scorciatoie',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Modifiche in attesa di accettazione',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Modifiche in attesa di approvazione',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Modifiche aperte',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Modifiche assegnate a me',
	'Menu:MyChanges+' => 'Modifiche assegnato a me (come Agent)',
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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Change' => 'Cambio',
	'Class:Change+' => '',
	'Class:Change/Attribute:start_date' => 'Avvio previsto',
	'Class:Change/Attribute:start_date+' => '',
	'Class:Change/Attribute:status' => 'Stato',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nuovo',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Convalidato',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rifiutato',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Assegnato',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Pianificato e programmato',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Approvato',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Non approvato',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => 'Implementato',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => 'Monitorato',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Chiuso',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Motivo',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => 'Richiedente',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => 'Richiedente',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:org_id' => 'Cliente',
	'Class:Change/Attribute:org_id+' => '',
	'Class:Change/Attribute:org_name' => 'Cliente',
	'Class:Change/Attribute:org_name+' => '',
	'Class:Change/Attribute:workgroup_id' => 'Gruppo di lavoro',
	'Class:Change/Attribute:workgroup_id+' => '',
	'Class:Change/Attribute:workgroup_name' => 'Gruppo di lavoro',
	'Class:Change/Attribute:workgroup_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Creato',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:last_update' => 'Ultimo aggiornamento',
	'Class:Change/Attribute:last_update+' => '',
	'Class:Change/Attribute:end_date' => 'Data di fine',
	'Class:Change/Attribute:end_date+' => '',
	'Class:Change/Attribute:close_date' => 'Chiuso',
	'Class:Change/Attribute:close_date+' => '',
	'Class:Change/Attribute:impact' => 'Impatto',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:agent_id' => 'Agente',
	'Class:Change/Attribute:agent_id+' => '',
	'Class:Change/Attribute:agent_name' => 'Agente',
	'Class:Change/Attribute:agent_name+' => '',
	'Class:Change/Attribute:agent_email' => 'Agente',
	'Class:Change/Attribute:agent_email+' => '',
	'Class:Change/Attribute:supervisor_group_id' => 'Supervisor team',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => 'Supervisor team',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => 'Supervisor',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => 'Supervisor',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'Manager team',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'Manager team',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'Manager',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'Manager',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => 'Interruzione',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Si',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'No',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:change_request' => 'Richiesta',
	'Class:Change/Attribute:change_request+' => '',
	'Class:Change/Attribute:fallback' => 'Piano alternativo',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Stimulus:ev_validate' => 'Convalidare',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Rifiutare',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Assegnare',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Riaprire',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Pianificare',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Approvare',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Ripianificare',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => 'Rifiutare',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => 'Implementare',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => 'Monitorare',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Finire',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('IT IT', 'Italian', 'Italiani', array(
	'Class:RoutineChange' => 'Routine di cambi',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Attribute:status/Value:new' => 'Nuovo',
	'Class:RoutineChange/Attribute:status/Value:new+' => '',
	'Class:RoutineChange/Attribute:status/Value:assigned' => 'Assegnato',
	'Class:RoutineChange/Attribute:status/Value:assigned+' => '',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled' => 'Pianificato e programmato',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:RoutineChange/Attribute:status/Value:approved' => 'Approvato',
	'Class:RoutineChange/Attribute:status/Value:approved+' => '',
	'Class:RoutineChange/Attribute:status/Value:implemented' => 'Implementato',
	'Class:RoutineChange/Attribute:status/Value:implemented+' => '',
	'Class:RoutineChange/Attribute:status/Value:monitored' => 'Monitorato',
	'Class:RoutineChange/Attribute:status/Value:monitored+' => '',
	'Class:RoutineChange/Attribute:status/Value:closed' => 'Chiuso',
	'Class:RoutineChange/Attribute:status/Value:closed+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Convalidare',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Assegnare',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Riaprire',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Pianificare',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Ripianificare',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Implementare',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Monitorare',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Finire',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
));

//
// Class: ApprovedChange
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ApprovedChange' => 'Cambi approvati',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => 'Data di approvazione',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Commento di approvazione',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Convalidare',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Rifiutare',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Assegnare',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Riaprire',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Pianificare',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Approvare',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Ripianificare',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Rifiutare l\'approvazione',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Implementare',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Monitorare',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Finire',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));
//
// Class: NormalChange
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:NormalChange' => 'Cambi normali',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:status/Value:new' => 'Nuovo',
	'Class:NormalChange/Attribute:status/Value:new+' => '',
	'Class:NormalChange/Attribute:status/Value:validated' => 'Convalidato',
	'Class:NormalChange/Attribute:status/Value:validated+' => '',
	'Class:NormalChange/Attribute:status/Value:rejected' => 'Rifiutato',
	'Class:NormalChange/Attribute:status/Value:rejected+' => '',
	'Class:NormalChange/Attribute:status/Value:assigned' => 'Assegnato',
	'Class:NormalChange/Attribute:status/Value:assigned+' => '',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled' => 'Pianificato e programmato',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:NormalChange/Attribute:status/Value:approved' => 'Approvato',
	'Class:NormalChange/Attribute:status/Value:approved+' => '',
	'Class:NormalChange/Attribute:status/Value:notapproved' => 'Non approvato',
	'Class:NormalChange/Attribute:status/Value:notapproved+' => '',
	'Class:NormalChange/Attribute:status/Value:implemented' => 'Implementato',
	'Class:NormalChange/Attribute:status/Value:implemented+' => '',
	'Class:NormalChange/Attribute:status/Value:monitored' => 'Monitorato',
	'Class:NormalChange/Attribute:status/Value:monitored+' => '',
	'Class:NormalChange/Attribute:status/Value:closed' => 'Chiuso',
	'Class:NormalChange/Attribute:status/Value:closed+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => 'Data di approvazione',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Commento di approvazione',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => 'Convalidare',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => 'Rifiutare',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => 'Assegnare',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Riaprire',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => 'Pianificare',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => 'Approvare',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => 'Ripianificare',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Rifiutare l\'approvazione',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => 'Implementare',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Monitorare',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => 'Finire',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EmergencyChange' => 'Cambi di emergenza',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Attribute:status/Value:new' => 'Nuovo',
	'Class:EmergencyChange/Attribute:status/Value:new+' => '',
	'Class:EmergencyChange/Attribute:status/Value:validated' => 'Convalidato',
	'Class:EmergencyChange/Attribute:status/Value:validated+' => '',
	'Class:EmergencyChange/Attribute:status/Value:rejected' => 'Rifiutato',
	'Class:EmergencyChange/Attribute:status/Value:rejected+' => '',
	'Class:EmergencyChange/Attribute:status/Value:assigned' => 'Asseganto',
	'Class:EmergencyChange/Attribute:status/Value:assigned+' => '',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled' => 'Pianificato e programmato',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:EmergencyChange/Attribute:status/Value:approved' => 'Approvato',
	'Class:EmergencyChange/Attribute:status/Value:approved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:notapproved' => 'Non approvato',
	'Class:EmergencyChange/Attribute:status/Value:notapproved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:implemented' => 'Implementato',
	'Class:EmergencyChange/Attribute:status/Value:implemented+' => '',
	'Class:EmergencyChange/Attribute:status/Value:monitored' => 'Monitorato',
	'Class:EmergencyChange/Attribute:status/Value:monitored+' => '',
	'Class:EmergencyChange/Attribute:status/Value:closed' => 'Chiuso',
	'Class:EmergencyChange/Attribute:status/Value:closed+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Convalidare',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Rifiutare',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Assegnare',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Riaprire',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Pianificare',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Approavre',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Riaprire',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Rifiutare l\'approvazione',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Implementare',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Monitorare',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Finire',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

?>

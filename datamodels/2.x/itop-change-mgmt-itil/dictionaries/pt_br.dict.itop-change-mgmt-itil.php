<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:ChangeManagement' => 'Gerenciamento de Mudanças',
	'Menu:Change:Overview' => 'Visão geral',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nova mudança',
	'Menu:NewChange+' => 'Criar uma nova mudança',
	'Menu:SearchChanges' => 'Pesquisar por mudanças',
	'Menu:SearchChanges+' => 'Pesquisar por solicitações de mudança',
	'Menu:Change:Shortcuts' => 'Atalhos',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Mudanças aguardando aceitação',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Mudanças aguardando aprovação',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Mudanças abertas',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Mudanças atribuídas a mim',
	'Menu:MyChanges+' => 'Mudanças atribuídas a mim (como Agente)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Mudanças por categoria nos últimos 7 dias',
	'UI-ChangeManagementOverview-Last-7-days' => 'Número de mudanças nos últimos 7 dias',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Mudanças por domínio nos últimos 7 dias',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Mudanças por domínio nos últimos 7 dias',
	'Tickets:Related:OpenChanges' => 'Mudanças abertas',
	'Tickets:Related:RecentChanges' => 'Mudanças recentes (72h)',
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

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Change' => 'Mudança',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nova',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Validada',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejeitada',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Atribuída',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Planejada e agendada',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Aprovada',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Não aprovada',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => 'Implementada',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => 'Monitorada',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Fechada',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Motivo da rejeição',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => 'Solicitante',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => 'E-mail do solicitante',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:creation_date' => 'Data de criação',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:impact' => 'Impacto',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:supervisor_group_id' => 'Supervisor da equipe',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => 'Nome do supervisor da equipe',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => 'Supervisor',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => 'E-mail do supervisor',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'Gerente da equipe',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'Nome do gerente da equipe',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'Gerente',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'Gerente',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => 'Outage',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Não',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Sim',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:fallback' => 'Plano de contingência',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Attribute:parent_id' => 'Submudanças',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Ref. Mudança pai',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_request_list' => 'Solicitações relacionadas',
	'Class:Change/Attribute:related_request_list+' => 'Todas as solicitações associadas à esta mudança',
	'Class:Change/Attribute:related_problems_list' => 'Problemas relacionados',
	'Class:Change/Attribute:related_problems_list+' => 'Todos os problemas associadas à esta mudança',
	'Class:Change/Attribute:related_incident_list' => 'Incidentes relacionados',
	'Class:Change/Attribute:related_incident_list+' => 'Todos os incidentes associadas à esta mudança',
	'Class:Change/Attribute:child_changes_list' => 'Submudanças',
	'Class:Change/Attribute:child_changes_list+' => 'Todas as submudanças associadas à esta mudança',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nome amigável da mudança pai',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Tipo de mudança',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => '',
	'Class:Change/Stimulus:ev_validate' => 'Validar',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Rejeitar',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Atribuir',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planejar',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Aprovar',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Re-planejar',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => 'Rejeitar',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => 'Implementar',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => 'Monitorar',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Finalizar',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:RoutineChange' => 'Mudança de rotina',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Validar',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Rejeitar',
	'Class:RoutineChange/Stimulus:ev_reject+' => '',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Atribuir',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Planejar',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Aprovar',
	'Class:RoutineChange/Stimulus:ev_approve+' => '',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Re-planejar',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'Não aprovar',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => '',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Implementar',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Monitorar',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
));

//
// Class: ApprovedChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ApprovedChange' => 'Mudanças aprovadas',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => 'Data de aprovação',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Comentário da aprovação',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Validar',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Rejeitar',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Atribuir',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Planejar',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Aprovar',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Re-planejar',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Não aprovar',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Implementar',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Monitorar',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));

//
// Class: NormalChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:NormalChange' => 'Mudança normal',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => 'Data de aceitação',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Comentário da aceitação',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => 'Validar',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => 'Rejeitar',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => 'Atribuir',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => 'Planejar',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => 'Aprovar',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => 'Re-planejar',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Não aprovar',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => 'Implementar',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Monitorar',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EmergencyChange' => 'Mudança emergencial',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Validar',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Rejeitar',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Atribuir',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Planejar',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Aprovar',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Re-planejar',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Não aprovar',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Implementar',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Monitorar',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Finalizar',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

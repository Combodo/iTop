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
	'Menu:ChangeManagement' => 'Gerenciamento de mudanças',
	'Menu:Change:Overview' => 'Visão geral',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nova mudança',
	'Menu:NewChange+' => 'Criar uma nova mudança',
	'Menu:SearchChanges' => 'Pesquisar por mudanças',
	'Menu:SearchChanges+' => 'Pesquisar por solicitações de mudanças',
	'Menu:Change:Shortcuts' => 'Atalhos',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Mudanças aguardando aceitação',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Mudanças aguardando aprovação',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Mudanças abertas',
	'Menu:Changes+' => 'Todas as mudanças abertas',
	'Menu:MyChanges' => 'Mudanças atribuídas a mim',
	'Menu:MyChanges+' => 'Mudanças atribuídas a mim (como Agente)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Mudanças por categoria nos últimos 7 dias',
	'UI-ChangeManagementOverview-Last-7-days' => 'Número de mudanças nos últimos 7 dias',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Mudanças por domínio nos últimos 7 dias',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Mudanças por status nos últimos 7 dias',
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
	'Class:Change/Attribute:status/Value:assigned' => 'Atribuída',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Planejada',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejeitada',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Aprovada',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Fechada',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Categoria',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Aplicação',
	'Class:Change/Attribute:category/Value:application+' => '',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
	'Class:Change/Attribute:category/Value:hardware+' => '',
	'Class:Change/Attribute:category/Value:network' => 'Rede',
	'Class:Change/Attribute:category/Value:network+' => '',
	'Class:Change/Attribute:category/Value:other' => 'Outro',
	'Class:Change/Attribute:category/Value:other+' => '',
	'Class:Change/Attribute:category/Value:software' => 'Software',
	'Class:Change/Attribute:category/Value:software+' => '',
	'Class:Change/Attribute:category/Value:system' => 'Sistema',
	'Class:Change/Attribute:category/Value:system+' => '',
	'Class:Change/Attribute:reject_reason' => 'Motivo da rejeição',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Gerente da mudança',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'E-mail do gerente da mudança',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Mudança pai',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Ref. Mudança pai',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Data de criação',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Data de aprovação',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Plano de contingência',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Solicitações relacionadas',
	'Class:Change/Attribute:related_request_list+' => 'Todas as solicitações de usuários associadas à esta mudança',
	'Class:Change/Attribute:related_incident_list' => 'Incidentes relacionados',
	'Class:Change/Attribute:related_incident_list+' => 'Todos os incidentes associados à esta mudança',
	'Class:Change/Attribute:related_problems_list' => 'Problemas relacionados',
	'Class:Change/Attribute:related_problems_list+' => 'Todos os problemas associados à esta mudança',
	'Class:Change/Attribute:child_changes_list' => 'Mudanças filhas',
	'Class:Change/Attribute:child_changes_list+' => 'Todas as submudanças associadas à esta mudança',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nome amigável da mudança pai',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Atribuir',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planejar',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Rejeitar',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Aprovar',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Fechar',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Interromper',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Não',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Sim',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));

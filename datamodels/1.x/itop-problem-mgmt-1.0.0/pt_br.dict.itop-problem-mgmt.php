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
 * @author	Marco Tulio <mtulio@opensolucoes.com.br>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Problem' => 'Problema',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Novo',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Atribuído',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Resolvido',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Fechado',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Cliente',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:service_id' => 'Serviço',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Categoria Serviço',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:product' => 'Produto',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impacto',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Uma pessoa',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Um Serviço',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Um Departamento',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgência',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Baixa',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Baixa',
	'Class:Problem/Attribute:urgency/Value:2' => 'Média',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Média',
	'Class:Problem/Attribute:urgency/Value:3' => 'Alta',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Alta',
	'Class:Problem/Attribute:priority' => 'Prioridade',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Baixa',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Média',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Alta',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'Grupo trabalho',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:agent_id' => 'Agente',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Mudança relacionada',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:close_date' => 'Data fechamento',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Última atualização',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Data da Atribuição',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Data da resolução',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Erros conhecidos',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Atribuir',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Re-atribuir',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Resolver',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Fechar',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Menu:ProblemManagement' => 'Gerenciamento Problemas',
	'Menu:ProblemManagement+' => 'Gerenciamento Problemas',
	'Menu:Problem:Overview' => 'Visão geral',
	'Menu:Problem:Overview+' => 'Visão geral',
	'Menu:NewProblem' => 'Novo Problema',
	'Menu:NewProblem+' => 'Novo Problema',
	'Menu:SearchProblems' => 'Pesquisa para Problemas',
	'Menu:SearchProblems+' => 'Pesquisa para Problemas',
	'Menu:Problem:Shortcuts' => 'Atalhos',
	'Menu:Problem:MyProblems' => 'Meus Problemas',
	'Menu:Problem:MyProblems+' => 'Meus Problemas',
	'Menu:Problem:OpenProblems' => 'Todos Problemas abertos',
	'Menu:Problem:OpenProblems+' => 'Todos Problemas abertos',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemas por Serviço',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemas por Serviço',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemas por Prioridade',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemas por Prioridade',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Problemas não atribuídos',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Problemas não atribuídos',
	'UI:ProblemMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Problemas',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Painel de Gerenciamento de Problemas',
	'Class:Problem/Attribute:org_name' => 'Nome',
	'Class:Problem/Attribute:org_name+' => 'Nome comum',
	'Class:Problem/Attribute:service_name' => 'Nome',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Nome',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Nome',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_name' => 'Nome agente',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'Email Agente',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
));
?>

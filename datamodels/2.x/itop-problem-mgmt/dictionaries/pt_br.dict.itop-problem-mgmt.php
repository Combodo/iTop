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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:ProblemManagement' => 'Gerenciamento de problemas',
	'Menu:ProblemManagement+' => 'Lista de gerenciamento de problemas',
	'Menu:Problem:Overview' => 'Visão geral',
	'Menu:Problem:Overview+' => '',
	'Menu:NewProblem' => 'Novo problema',
	'Menu:NewProblem+' => '',
	'Menu:SearchProblems' => 'Pesquisar por problemas',
	'Menu:SearchProblems+' => '',
	'Menu:Problem:Shortcuts' => 'Atalhos',
	'Menu:Problem:MyProblems' => 'Meus problemas',
	'Menu:Problem:MyProblems+' => '',
	'Menu:Problem:OpenProblems' => 'Todos os problemas abertos',
	'Menu:Problem:OpenProblems+' => '',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemas por serviço',
	'UI-ProblemManagementOverview-ProblemByService+' => '',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemas por prioridade',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Problemas não atribuídos',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '',
	'UI:ProblemMgmtMenuOverview:Title' => 'Painel de gerenciamento de problemas',
	'UI:ProblemMgmtMenuOverview:Title+' => '',

));
//
// Class: Problem
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Problem' => 'Problema',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Novo',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Atribuído',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Solucionado',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Fechado',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Serviço',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Nome do serviço',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Subcategoria do serviço',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Subcategoria do serviço',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Produto',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impacto',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Um departamento',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Um serviço',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Uma pessoa',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgência',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Crítica',
	'Class:Problem/Attribute:urgency/Value:1+' => '',
	'Class:Problem/Attribute:urgency/Value:2' => 'Alta',
	'Class:Problem/Attribute:urgency/Value:2+' => '',
	'Class:Problem/Attribute:urgency/Value:3' => 'Média',
	'Class:Problem/Attribute:urgency/Value:3+' => '',
	'Class:Problem/Attribute:urgency/Value:4' => 'Baixa',
	'Class:Problem/Attribute:urgency/Value:4+' => '',
	'Class:Problem/Attribute:priority' => 'Prioridade',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Crítica',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Alta',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Média',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:priority/Value:4' => 'Baixa',
	'Class:Problem/Attribute:priority/Value:4+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Mudança relacionada',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref. Mudança relacionada',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Data de atribuição',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Data de solução',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Erros conhecidos',
	'Class:Problem/Attribute:knownerrors_list+' => 'Todos os erros conhecidos associados a este problema',
	'Class:Problem/Attribute:related_request_list' => 'Solicitações relacionadas',
	'Class:Problem/Attribute:related_request_list+' => 'Todas as solicitações associadas a este problema',
	'Class:Problem/Attribute:related_incident_list' => 'Incidentes relacionados',
	'Class:Problem/Attribute:related_incident_list+' => 'Todos os incidentes associados a este problema',
	'Class:Problem/Stimulus:ev_assign' => 'Atribuir',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Reatribuir',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Resolver',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Fechar',
	'Class:Problem/Stimulus:ev_close+' => '',
));

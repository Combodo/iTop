<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Ticket' => 'Solicitação',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Ref',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organização',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Nome organização',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Solicitante',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Nome solicitante',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Equipe',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Nome equipe',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Agente',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Nome agente',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Título',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Descrição',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Data início',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Data final',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Última atualização',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Data fechamento',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Log privado',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Contatos',
	'Class:Ticket/Attribute:contacts_list+' => 'Todos os contatos vinculados a essa solicitação',
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Todos os itens de configuração afetados por essa solicitação',
	'Class:Ticket/Attribute:workorders_list' => 'Ordens de serviço',
	'Class:Ticket/Attribute:workorders_list+' => 'Todos as Ordens de Serviço para essa solicitação',
	'Class:Ticket/Attribute:finalclass' => 'Tipo',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Status operacional',
	'Class:Ticket/Attribute:operational_status+' => 'Computado após o status detalhado',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Em curso',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Trabalho em andamento',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Resolvido',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Fechado',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => 'Análise de Impacto',
));


//
// Class: lnkContactToTicket
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkContactToTicket' => 'Link Contato / Solicitação',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Solicitação',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contato',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Email contato',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Regra',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Papel',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Adicionado manualmente',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Computado',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Não notificar',
));

//
// Class: WorkOrder
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:WorkOrder' => 'Ordem de serviço',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Nome',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Estado',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Aberto',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Fechado',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Descrição',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Solicitante',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Ref solicitante',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Equipe',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Nome equipe',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Agente',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Email agente',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Data início',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Data final',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Fechar',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Ticket:baseinfo'                                                => 'Informações gerais',
	'Ticket:date'                                                    => 'Datas',
	'Ticket:contact'                                                 => 'Contatos',
	'Ticket:moreinfo'                                               => 'Mais informações',
	'Ticket:relation'                                               => 'Relações',
	'Ticket:log'                                                    => 'Comunicação',
	'Ticket:Type'                                                   => 'Qualificação',
	'Ticket:support'                                                => 'Suporte',
	'Ticket:resolution'                                             => 'Resolução',
	'Ticket:SLA'                                                    => 'Relatório SLA',
	'WorkOrder:Details'                                             => 'Detalhes',
	'WorkOrder:Moreinfo'                                            => 'Mais informações',
	'Tickets:ResolvedFrom'                                          => 'Resolvido automaticamente de %1$s',
	'Class:cmdbAbstractObject/Method:Set'                           => 'Set',
	'Class:cmdbAbstractObject/Method:Set+'                          => 'Defina um campo com um valor estático',
	'Class:cmdbAbstractObject/Method:Set/Param:1'                   => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:Set/Param:1+'                  => 'O campo para definir, no objeto atual',
	'Class:cmdbAbstractObject/Method:Set/Param:2'                   => 'Valor',
	'Class:cmdbAbstractObject/Method:Set/Param:2+'                  => 'O valor para definir',
	'Class:cmdbAbstractObject/Method:SetCurrentDate'                => 'SetCurrentDate',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+'               => 'Defina um campo com a data e hora atual',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1'        => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+'       => 'O campo para definir, no objeto atual',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull'          => 'SetCurrentDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+'         => 'Set an empty field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser'                => 'SetCurrentUser',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+'               => 'Defina um campo com o usuário atualmente logado',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1'        => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+'       => 'O campo para definir, no objeto atual. Se o campo for uma string, o nome amigável será usado, caso contrário, o identificador será usado. Esse nome amigável é o nome da pessoa, se houver alguma anexada ao usuário, caso contrário, é o login.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson'              => 'SetCurrentPerson',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+'             => 'Defina um campo com a pessoa atualmente logada (a \\"pessoa\\" anexada ao \\"usuário\\").',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1'      => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+'     => 'O campo para definir, no objeto atual. Se o campo for uma string, o nome amigável será usado, caso contrário, o identificador será usado. ',
	'Class:cmdbAbstractObject/Method:SetElapsedTime'                => 'SetElapsedTime',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+'               => 'Defina um campo com o tempo (segundos) decorrido desde a data dada por outro campo',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1'        => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+'       => 'O campo para definir, no objeto atual',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2'        => 'Campo de Referência',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+'       => 'O campo do qual obter a data de referência',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3'        => 'Horário de trabalho',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+'       => 'Deixe em branco para confiar no esquema padrão de horas de trabalho, ou configure para \\"DefaultWorkingTimeComputer\\" para forçar um esquema de 24x7',
	'Class:cmdbAbstractObject/Method:SetIfNull'                     => 'SetIfNull~~',
	'Class:cmdbAbstractObject/Method:SetIfNull+'                    => 'Set a field only if it is empty, with a static value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1'             => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+'            => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2'              => 'Value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+'             => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:AddValue'                       => 'AddValue~~',
	'Class:cmdbAbstractObject/Method:AddValue+'                      => 'Add a fixed value to a field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1'               => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+'              => 'The field to modify, in the current object~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2'               => 'Value~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+'              => 'Decimal value which will be added, can be negative~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate'                => 'SetComputedDate~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate+'               => 'Set a field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+'       => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2'        => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+'       => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3'        => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+'       => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull'          => 'SetComputedDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+'         => 'Set non empty field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2'  => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3'  => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:Reset'                          => 'Reset',
	'Class:cmdbAbstractObject/Method:Reset+'                         => 'Redefinir um campo para seu valor padrão',
	'Class:cmdbAbstractObject/Method:Reset/Param:1'                  => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+'                 => 'O campo para redefinir, no objeto atual',
	'Class:cmdbAbstractObject/Method:Copy'                           => 'Cop',
	'Class:cmdbAbstractObject/Method:Copy+'                          => 'Copie o valor de um campo para outro campo',
	'Class:cmdbAbstractObject/Method:Copy/Param:1'                   => 'Campo alvo',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+'                  => 'O campo para definir, no objeto atual',
	'Class:cmdbAbstractObject/Method:Copy/Param:2'                   => 'Campo de origem',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+'                  => 'O campo para obter o valor de, no objeto atual',
	'Class:cmdbAbstractObject/Method:ApplyStimulus'                  => 'ApplyStimulus',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+'                 => 'Aplique o estímulo especificado ao objeto atual',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1'          => 'Código de estímulo',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+'         => 'Um código de estímulo válido para a classe atual',
	'Class:ResponseTicketTTO/Interface:iMetricComputer'              => 'Time To Own',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+'             => 'Objetivo baseado em um SLT do tipo TTO',
	'Class:ResponseTicketTTR/Interface:iMetricComputer'              => 'Time To Resolve',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+'             => 'Objetivo baseado em um SLT do tipo TTR',
));

//
// Class: Document
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Document/Attribute:contracts_list' => 'Contratos',
	'Class:Document/Attribute:contracts_list+' => 'Todos os contratos vinculados com esse documento',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Todos os serviços vinculados a esse documento',
));
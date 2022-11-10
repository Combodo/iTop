<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:RequestManagement' => 'Gerenciamento de Solicitações',
	'Menu:RequestManagement+' => 'Gerenciamento de Solicitações',
	'Menu:RequestManagementProvider' => 'Solicitações a provedores',
	'Menu:RequestManagementProvider+' => 'Solicitações a provedores',
	'Menu:UserRequest:Provider' => 'Solicitações abertas transferidas a provedores',
	'Menu:UserRequest:Provider+' => 'Solicitações abertas transferidas a provedores',
	'Menu:UserRequest:Overview' => 'Visão geral',
	'Menu:UserRequest:Overview+' => 'Visão geral',
	'Menu:NewUserRequest' => 'Nova solicitação',
	'Menu:NewUserRequest+' => 'Criar uma nova solicitação',
	'Menu:SearchUserRequests' => 'Pesquisar por solicitações',
	'Menu:SearchUserRequests+' => 'Pesquisar por solicitações',
	'Menu:UserRequest:Shortcuts' => 'Atalhos',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Solicitações atribuídas a mim',
	'Menu:UserRequest:MyRequests+' => 'Solicitações atribuídas a mim (como Agente)',
	'Menu:UserRequest:MySupportRequests' => 'Minhas solicitações de suporte',
	'Menu:UserRequest:MySupportRequests+' => 'Minhas solicitações de suporte',
	'Menu:UserRequest:EscalatedRequests' => 'Solicitações escalonadas',
	'Menu:UserRequest:EscalatedRequests+' => 'Solicitações escalonadas',
	'Menu:UserRequest:OpenRequests' => 'Todas as solicitações abertas',
	'Menu:UserRequest:OpenRequests+' => '',
	'UI:WelcomeMenu:MyAssignedCalls' => 'Solicitações atribuídas a mim',
	'UI-RequestManagementOverview-RequestByType-last-14-days' => 'Solicitações dos últimos 14 dias (por tipo)',
	'UI-RequestManagementOverview-Last-14-days' => 'Solicitações dos últimos 14 dias (por dia)',
	'UI-RequestManagementOverview-OpenRequestByStatus' => 'Solicitações abertas por status',
	'UI-RequestManagementOverview-OpenRequestByAgent' => 'Solicitações abertas por agente',
	'UI-RequestManagementOverview-OpenRequestByType' => 'Solicitações abertas por tipo',
	'UI-RequestManagementOverview-OpenRequestByCustomer' => 'Solicitações abertas por organização',
	'Class:UserRequest:KnownErrorList' => 'Erros conhecidos',
	'Menu:UserRequest:MyWorkOrders' => 'Ordens de serviço atribuídas a mim',
	'Menu:UserRequest:MyWorkOrders+' => 'Todas as ordens de serviço atribuídas a mim',
	'Class:Problem:KnownProblemList' => 'Problemas conhecidos',
	'Tickets:Related:OpenIncidents' => 'Incidentes abertos',
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
// Class: UserRequest
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:UserRequest' => 'Solicitação de Usuário',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:status' => 'Status',
	'Class:UserRequest/Attribute:status+' => '',
	'Class:UserRequest/Attribute:status/Value:new' => 'Novo',
	'Class:UserRequest/Attribute:status/Value:new+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_tto' => 'TTO escalonado',
	'Class:UserRequest/Attribute:status/Value:escalated_tto+' => '',
	'Class:UserRequest/Attribute:status/Value:assigned' => 'Atribuído',
	'Class:UserRequest/Attribute:status/Value:assigned+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr' => 'TTR escalonado',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr+' => '',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval' => 'Aguardando aprovação',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval+' => '',
	'Class:UserRequest/Attribute:status/Value:approved' => 'Aprovado',
	'Class:UserRequest/Attribute:status/Value:approved+' => '',
	'Class:UserRequest/Attribute:status/Value:rejected' => 'Rejeitado',
	'Class:UserRequest/Attribute:status/Value:rejected+' => '',
	'Class:UserRequest/Attribute:status/Value:pending' => 'Pendente',
	'Class:UserRequest/Attribute:status/Value:pending+' => '',
	'Class:UserRequest/Attribute:status/Value:resolved' => 'Resolvido',
	'Class:UserRequest/Attribute:status/Value:resolved+' => '',
	'Class:UserRequest/Attribute:status/Value:closed' => 'Fechado',
	'Class:UserRequest/Attribute:status/Value:closed+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Tipo de solicitação',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:incident' => 'Incidente',
	'Class:UserRequest/Attribute:request_type/Value:incident+' => '',
	'Class:UserRequest/Attribute:request_type/Value:service_request' => 'Solicitação de serviço',
	'Class:UserRequest/Attribute:request_type/Value:service_request+' => '',
	'Class:UserRequest/Attribute:impact' => 'Impacto',
	'Class:UserRequest/Attribute:impact+' => '',
	'Class:UserRequest/Attribute:impact/Value:1' => 'Um departamento',
	'Class:UserRequest/Attribute:impact/Value:1+' => '',
	'Class:UserRequest/Attribute:impact/Value:2' => 'Um serviço',
	'Class:UserRequest/Attribute:impact/Value:2+' => '',
	'Class:UserRequest/Attribute:impact/Value:3' => 'Uma pessoa',
	'Class:UserRequest/Attribute:impact/Value:3+' => '',
	'Class:UserRequest/Attribute:priority' => 'Prioridade',
	'Class:UserRequest/Attribute:priority+' => '',
	'Class:UserRequest/Attribute:priority/Value:1' => 'Crítica',
	'Class:UserRequest/Attribute:priority/Value:1+' => '',
	'Class:UserRequest/Attribute:priority/Value:2' => 'Alta',
	'Class:UserRequest/Attribute:priority/Value:2+' => '',
	'Class:UserRequest/Attribute:priority/Value:3' => 'Média',
	'Class:UserRequest/Attribute:priority/Value:3+' => '',
	'Class:UserRequest/Attribute:priority/Value:4' => 'Baixa',
	'Class:UserRequest/Attribute:priority/Value:4+' => '',
	'Class:UserRequest/Attribute:urgency' => 'Urgência',
	'Class:UserRequest/Attribute:urgency+' => '',
	'Class:UserRequest/Attribute:urgency/Value:1' => 'Crítica',
	'Class:UserRequest/Attribute:urgency/Value:1+' => '',
	'Class:UserRequest/Attribute:urgency/Value:2' => 'Alta',
	'Class:UserRequest/Attribute:urgency/Value:2+' => '',
	'Class:UserRequest/Attribute:urgency/Value:3' => 'Média',
	'Class:UserRequest/Attribute:urgency/Value:3+' => '',
	'Class:UserRequest/Attribute:urgency/Value:4' => 'Baixa',
	'Class:UserRequest/Attribute:urgency/Value:4+' => '',
	'Class:UserRequest/Attribute:origin' => 'Origem',
	'Class:UserRequest/Attribute:origin+' => '',
	'Class:UserRequest/Attribute:origin/Value:mail' => 'E-mail',
	'Class:UserRequest/Attribute:origin/Value:mail+' => '',
	'Class:UserRequest/Attribute:origin/Value:monitoring' => 'Monitoramento',
	'Class:UserRequest/Attribute:origin/Value:monitoring+' => '',
	'Class:UserRequest/Attribute:origin/Value:phone' => 'Telefone',
	'Class:UserRequest/Attribute:origin/Value:phone+' => '',
	'Class:UserRequest/Attribute:origin/Value:portal' => 'Portal do usuário',
	'Class:UserRequest/Attribute:origin/Value:portal+' => '',
	'Class:UserRequest/Attribute:approver_id' => 'Aprovador',
	'Class:UserRequest/Attribute:approver_id+' => '',
	'Class:UserRequest/Attribute:approver_email' => 'E-mail do aprovador',
	'Class:UserRequest/Attribute:approver_email+' => '',
	'Class:UserRequest/Attribute:service_id' => 'Serviço',
	'Class:UserRequest/Attribute:service_id+' => '',
	'Class:UserRequest/Attribute:service_name' => 'Nome do serviço',
	'Class:UserRequest/Attribute:service_name+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_id' => 'Subcategoria de serviço',
	'Class:UserRequest/Attribute:servicesubcategory_id+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_name' => 'Nome da subcategoria de serviço',
	'Class:UserRequest/Attribute:servicesubcategory_name+' => '',
	'Class:UserRequest/Attribute:escalation_flag' => 'Flag de escalonamento',
	'Class:UserRequest/Attribute:escalation_flag+' => '',
	'Class:UserRequest/Attribute:escalation_flag/Value:no' => 'Não',
	'Class:UserRequest/Attribute:escalation_flag/Value:no+' => '',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes' => 'Sim',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes+' => '',
	'Class:UserRequest/Attribute:escalation_reason' => 'Motivo do escalonamento',
	'Class:UserRequest/Attribute:escalation_reason+' => '',
	'Class:UserRequest/Attribute:assignment_date' => 'Data de atribuição',
	'Class:UserRequest/Attribute:assignment_date+' => '',
	'Class:UserRequest/Attribute:resolution_date' => 'Data de solução',
	'Class:UserRequest/Attribute:resolution_date+' => '',
	'Class:UserRequest/Attribute:last_pending_date' => 'Última data pendente',
	'Class:UserRequest/Attribute:last_pending_date+' => '',
	'Class:UserRequest/Attribute:cumulatedpending' => 'Pendências acumuladas',
	'Class:UserRequest/Attribute:cumulatedpending+' => '',
	'Class:UserRequest/Attribute:tto' => 'TTO',
	'Class:UserRequest/Attribute:tto+' => 'Tempo para atribuição (Time To Own)',
	'Class:UserRequest/Attribute:ttr' => 'TTR',
	'Class:UserRequest/Attribute:ttr+' => 'Tempo para solução (Time To Resolution)',
	'Class:UserRequest/Attribute:tto_escalation_deadline' => 'Prazo determinado de atribuição (TTO)',
	'Class:UserRequest/Attribute:tto_escalation_deadline+' => 'Prazo determinado de Tempo para atribuição (TTO)',
	'Class:UserRequest/Attribute:sla_tto_passed' => 'SLA TTO superado',
	'Class:UserRequest/Attribute:sla_tto_passed+' => 'Tempo para atribuição (TTO) do Acordo de Nível de Serviço (SLA) superado',
	'Class:UserRequest/Attribute:sla_tto_over' => 'SLA TTO ultrapassado',
	'Class:UserRequest/Attribute:sla_tto_over+' => 'Tempo para atribuição (TTO) do Acordo de Nível de Serviço (SLA) ultrapassado',
	'Class:UserRequest/Attribute:ttr_escalation_deadline' => 'Prazo determinado de solução (TTR)',
	'Class:UserRequest/Attribute:ttr_escalation_deadline+' => 'Prazo determinado de Tempo para solução (TTR)',
	'Class:UserRequest/Attribute:sla_ttr_passed' => 'SLA TTR superado',
	'Class:UserRequest/Attribute:sla_ttr_passed+' => 'Tempo para solução (TTR) do Acordo de Nível de Serviço (SLA) superado',
	'Class:UserRequest/Attribute:sla_ttr_over' => 'SLA TTR ultrapassado',
	'Class:UserRequest/Attribute:sla_ttr_over+' => 'Tempo para solução (TTR) do Acordo de Nível de Serviço (SLA) ultrapassado',
	'Class:UserRequest/Attribute:time_spent' => 'Tempo de solução',
	'Class:UserRequest/Attribute:time_spent+' => '',
	'Class:UserRequest/Attribute:resolution_code' => 'Código da solução',
	'Class:UserRequest/Attribute:resolution_code+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance' => 'Assistência',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed' => 'Correção de bug',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair' => 'Reparação de hardware',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:other' => 'Outros',
	'Class:UserRequest/Attribute:resolution_code/Value:other+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch' => 'Atualização de software',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:system update' => 'Atualização de sistema',
	'Class:UserRequest/Attribute:resolution_code/Value:system update+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:training' => 'Treinamento',
	'Class:UserRequest/Attribute:resolution_code/Value:training+' => '',
	'Class:UserRequest/Attribute:solution' => 'Solução',
	'Class:UserRequest/Attribute:solution+' => '',
	'Class:UserRequest/Attribute:pending_reason' => 'Motivo da pendência',
	'Class:UserRequest/Attribute:pending_reason+' => '',
	'Class:UserRequest/Attribute:parent_request_id' => 'Solicitação pai',
	'Class:UserRequest/Attribute:parent_request_id+' => '',
	'Class:UserRequest/Attribute:parent_request_ref' => 'Ref. Solicitação pai',
	'Class:UserRequest/Attribute:parent_request_ref+' => '',
	'Class:UserRequest/Attribute:parent_problem_id' => 'Problema pai',
	'Class:UserRequest/Attribute:parent_problem_id+' => '',
	'Class:UserRequest/Attribute:parent_problem_ref' => 'Ref. Problema pai',
	'Class:UserRequest/Attribute:parent_problem_ref+' => '',
	'Class:UserRequest/Attribute:parent_change_id' => 'Mudança pai',
	'Class:UserRequest/Attribute:parent_change_id+' => '',
	'Class:UserRequest/Attribute:parent_change_ref' => 'Ref. Mudança pai',
	'Class:UserRequest/Attribute:parent_change_ref+' => '',
	'Class:UserRequest/Attribute:related_request_list' => 'Subsolicitações',
	'Class:UserRequest/Attribute:related_request_list+' => 'Todas as solicitações associadas à esta solicitação pai',
	'Class:UserRequest/Attribute:public_log' => 'Log público',
	'Class:UserRequest/Attribute:public_log+' => '',
	'Class:UserRequest/Attribute:user_satisfaction' => 'Satisfação do usuário',
	'Class:UserRequest/Attribute:user_satisfaction+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1' => 'Muito satisfeito',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2' => 'Satisfeito',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3' => 'Insatisfeito',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4' => 'Muito insatisfeito',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4+' => '',
	'Class:UserRequest/Attribute:user_comment' => 'Comentário do usuário',
	'Class:UserRequest/Attribute:user_comment+' => '',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname' => 'parent_request_id_friendlyname',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Atribuir',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Reatribuir',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_approve' => 'Aprovar',
	'Class:UserRequest/Stimulus:ev_approve+' => '',
	'Class:UserRequest/Stimulus:ev_reject' => 'Rejeitar',
	'Class:UserRequest/Stimulus:ev_reject+' => '',
	'Class:UserRequest/Stimulus:ev_pending' => 'Pendente',
	'Class:UserRequest/Stimulus:ev_pending+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'Timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_autoresolve' => 'Solucionado automaticamente',
	'Class:UserRequest/Stimulus:ev_autoresolve+' => '',
	'Class:UserRequest/Stimulus:ev_autoclose' => 'Fechado automaticamente',
	'Class:UserRequest/Stimulus:ev_autoclose+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Marcar como solucionado',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Fechar esta solicitação',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_reopen' => 'Re-abrir',
	'Class:UserRequest/Stimulus:ev_reopen+' => '',
	'Class:UserRequest/Stimulus:ev_wait_for_approval' => 'Aguardar por aprovação',
	'Class:UserRequest/Stimulus:ev_wait_for_approval+' => '',
	'Class:UserRequest/Error:CannotAssignParentRequestIdToSelf' => 'Não é possível atribuir a solicitação pai a própria solicitação',
));


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Portal:TitleDetailsFor_Request' => 'Detalhes da solicitação',
	'Portal:ButtonUpdate' => 'Atualizado',
	'Portal:ButtonClose' => 'Fechado',
	'Portal:ButtonReopen' => 'Re-aberto',
	'Portal:ShowServices' => 'Catálogo dos serviços',
	'Portal:SelectRequestType' => 'Selecione um tipo de solicitação',
	'Portal:SelectServiceElementFrom_Service' => 'Selecione um serviço para %1$s',
	'Portal:ListServices' => 'Lista dos serviços',
	'Portal:TitleDetailsFor_Service' => 'Detalhes dos serviços',
	'Portal:Button:CreateRequestFromService' => 'Criar uma solicitação para esse serviço',
	'Portal:ListOpenRequests' => 'Lista de solicitações abertas',
	'Portal:UserRequest:MoreInfo' => 'Mais informações',
	'Portal:Details-Service-Element' => 'Elementos do Serviço',
	'Portal:NoClosedTicket' => 'Nenhuma solicitação fechada',
	'Portal:NoService' => '',
	'Portal:ListOpenProblems' => 'Problemas em andamento',
	'Portal:ShowProblem' => 'Problemas',
	'Portal:ShowFaqs' => 'FAQ',
	'Portal:NoOpenProblem' => 'Nenhum problema aberto',
	'Portal:SelectLanguage' => 'Alterar idioma',
	'Portal:LanguageChangedTo_Lang' => 'Idioma alterado para',
	'Portal:ChooseYourFavoriteLanguage' => 'Escolha seu idioma favorito',

	'Class:UserRequest/Method:ResolveChildTickets' => 'ResolveChildTickets',
	'Class:UserRequest/Method:ResolveChildTickets+' => 'Conecte a solução a pedidos filhos (ev_autoresolve) e alinhe as seguintes características da requisição: serviço, equipe, agente, info de solução',
));


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Organization:Overview:UserRequests' => 'Solicitações de usuários desta organização',
	'Organization:Overview:MyUserRequests' => 'Minhas solicitações de usuário para esta organização',
	'Organization:Overview:Tickets' => 'Solicitações desta organização',
));

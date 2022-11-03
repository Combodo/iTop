<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//
//
// Class: AuditCategory
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AuditCategory' => 'Categoria de Auditoria',
	'Class:AuditCategory+' => 'Uma seção dentro da auditoria',
	'Class:AuditCategory/Attribute:name' => 'Nome',
	'Class:AuditCategory/Attribute:name+' => 'Nome curto para esta categoria',
	'Class:AuditCategory/Attribute:description' => 'Descrição',
	'Class:AuditCategory/Attribute:description+' => 'Longa descrição para esta categoria de auditoria',
	'Class:AuditCategory/Attribute:definition_set' => 'Definir Regra',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expressão OQL que define o conjunto de objetos para auditoria',
	'Class:AuditCategory/Attribute:rules_list' => 'Regras de Auditoria',
	'Class:AuditCategory/Attribute:rules_list+' => 'Regra de auditoria para essa categoria',
));

//
// Class: AuditRule
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AuditRule' => 'Regra de Auditoria',
	'Class:AuditRule+' => 'Uma regra para verificar se uma determinada categoria de Auditoria',
	'Class:AuditRule/Attribute:name' => 'Nome',
	'Class:AuditRule/Attribute:name+' => 'Nome curto para esta regra',
	'Class:AuditRule/Attribute:description' => 'Descrição',
	'Class:AuditRule/Attribute:description+' => 'Descrição longa para essa regra',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Classe da tag',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe do objeto',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Código do campo',
	'Class:AuditRule/Attribute:query' => 'Executar consulta',
	'Class:AuditRule/Attribute:query+' => 'Executar a expressão OQL',
	'Class:AuditRule/Attribute:valid_flag' => 'Objetos válidos?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Verdadeiro se a regra retornar o objeto válido, falso caso contrário',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Verdadeiro',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => '',
	'Class:AuditRule/Attribute:category_id' => 'Categoria',
	'Class:AuditRule/Attribute:category_id+' => 'A categoria para esta regra',
	'Class:AuditRule/Attribute:category_name' => 'Categoria',
	'Class:AuditRule/Attribute:category_name+' => 'Nome da categoria para essa regra',
));

//
// Class: QueryOQL
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Query' => 'Consulta',
	'Class:Query+' => 'Uma consulta é um conjunto de dados definido de uma forma dinâmica',
	'Class:Query/Attribute:name' => 'Nome',
	'Class:Query/Attribute:name+' => 'Identificação da consulta',
	'Class:Query/Attribute:description' => 'Descrição',
	'Class:Query/Attribute:description+' => 'Descrição longa para a consulta (finalidade, uso, etc.)',
	'Class:Query/Attribute:is_template' => 'Template para campos OQL',
	'Class:Query/Attribute:is_template+' => 'Utilizável como origem para o Destinatário OQL em Notificações',
	'Class:Query/Attribute:is_template/Value:yes' => 'Sim',
	'Class:Query/Attribute:is_template/Value:no' => 'Não',
	'Class:QueryOQL/Attribute:fields' => 'Campos',
	'Class:QueryOQL/Attribute:fields+' => 'Lista separada por vírgulas de atributos (ou alias.attribute) para exportar',
	'Class:QueryOQL' => 'Consulta OQL',
	'Class:QueryOQL+' => 'Uma consulta baseada no Object Query Language (OQL)',
	'Class:QueryOQL/Attribute:oql' => 'Expressão',
	'Class:QueryOQL/Attribute:oql+' => 'Expressão Object Query Language (OQL)',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:User' => 'Usuário',
	'Class:User+' => '',
	'Class:User/Attribute:finalclass' => 'Tipo de conta',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Pessoa',
	'Class:User/Attribute:contactid+' => '',
	'Class:User/Attribute:org_id' => 'Organização',
	'Class:User/Attribute:org_id+' => 'Organização à qual esse usuário pertence',
	'Class:User/Attribute:last_name' => 'Sobrenome',
	'Class:User/Attribute:last_name+' => 'Último nome do usuário correspondente',
	'Class:User/Attribute:first_name' => 'Primeiro nome',
	'Class:User/Attribute:first_name+' => 'Primeiro nome do usuário correspondente',
	'Class:User/Attribute:email' => 'E-mail',
	'Class:User/Attribute:email+' => 'Endereço de e-mail do usuário correspondente',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Login de acesso ao '.ITOP_APPLICATION_SHORT,
	'Class:User/Attribute:language' => 'Idioma',
	'Class:User/Attribute:language+' => 'Idioma do usuário correspondente',
	'Class:User/Attribute:language/Value:EN US' => 'Inglês',
	'Class:User/Attribute:language/Value:EN US+' => 'Inglês (E.U.A.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Francês',
	'Class:User/Attribute:language/Value:FR FR+' => 'Francês (França)',
	'Class:User/Attribute:profile_list' => 'Perfil',
	'Class:User/Attribute:profile_list+' => 'Permissões de acesso para esse usuário',
	'Class:User/Attribute:allowed_org_list' => 'Organizações permitidas',
	'Class:User/Attribute:allowed_org_list+' => 'O usuário tem permissão de ver as informações para a(s) organização(ões) abaixo. Se nenhuma organização for especificada, não há restrição',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'Se a conta de usuário está habilitada ou desabilitada',
	'Class:User/Attribute:status/Value:enabled' => 'Ativa',
	'Class:User/Attribute:status/Value:disabled' => 'Desativada',

	'Class:User/Error:LoginMustBeUnique' => 'Login é único - "%1s" já está ativo',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Pelo menos um perfil deve ser atribuído a esse usuário',
	'Class:User/Error:ProfileNotAllowed' => 'O perfil "%1$s" não pode ser adicionado, ele negará o acesso ao backoffice',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Alterar o status da conta não é permitido para o seu próprio usuário',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'As organizações permitidas devem conter apenas usuários pertencentes a organização',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'A lista atual de perfis não fornece permissões de acesso suficientes (os usuários não são mais modificáveis)',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Pelo menos uma organização deve ser atribuída a esse usuário',
	'Class:User/Error:OrganizationNotAllowed' => 'Organização não permitida',
	'Class:User/Error:UserOrganizationNotAllowed' => 'A conta de usuário não pertence às suas organizações permitidas',
	'Class:User/Error:PersonIsMandatory' => 'O contato é obrigatório',
	'Class:UserInternal' => 'Usuário Interno',
	'Class:UserInternal+' => 'Usuário definido dentro do '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Profiles' => 'Perfil',
	'Class:URP_Profiles+' => 'Perfil do Usuário',
	'Class:URP_Profiles/Attribute:name' => 'Nome',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Descrição',
	'Class:URP_Profiles/Attribute:description+' => 'Uma descrição curta',
	'Class:URP_Profiles/Attribute:user_list' => 'Usuários',
	'Class:URP_Profiles/Attribute:user_list+' => 'Pessoas que possuem esse perfil',
));

//
// Class: URP_Dimensions
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Dimensions' => 'Dimensão',
	'Class:URP_Dimensions+' => 'Dimensão de aplicação (definição de silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nome',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Descrição',
	'Class:URP_Dimensions/Attribute:description+' => 'Uma descrição curta',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'Nome da classe ou tipo de dado (unidade de mapeamento)',
));

//
// Class: URP_UserProfile
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserProfile' => 'Perfil de Usuário',
	'Class:URP_UserProfile+' => 'Perfil de Usuário',
	'Class:URP_UserProfile/Name' => 'Link entre %1$s e %2$s',
	'Class:URP_UserProfile/Attribute:userid' => 'Usuário',
	'Class:URP_UserProfile/Attribute:userid+' => 'Conta de usuário',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => '',
	'Class:URP_UserProfile/Attribute:profileid' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_UserProfile/Attribute:profile' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nome do perfil',
	'Class:URP_UserProfile/Attribute:reason' => 'Função',
	'Class:URP_UserProfile/Attribute:reason+' => 'Explicação por que esta pessoa deve ter essa função',
));

//
// Class: URP_UserOrg
//


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserOrg' => 'Organização do usuário',
	'Class:URP_UserOrg+' => 'Organizações permitidas',
	'Class:URP_UserOrg/Name' => 'Link entre %1$s e %2$s',
	'Class:URP_UserOrg/Attribute:userid' => 'Usuário',
	'Class:URP_UserOrg/Attribute:userid+' => 'Conta de usuário',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:reason' => 'Função',
	'Class:URP_UserOrg/Attribute:reason+' => 'Explicação por que essa pessoa tem permissão para ver os dados pertencentes com essa organização',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ProfileProjection' => 'Mapeamentos de Perfil',
	'Class:URP_ProfileProjection+' => '',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensão',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Dimensão de aplicação',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensão',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Dimensão de aplicação',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nome do perfil',
	'Class:URP_ProfileProjection/Attribute:value' => 'Valor da expressão',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Expressão OQL (usando $user) | constante | | +código de atributo',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Código de atributo alvo (opcional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimensão',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'Dimensão aplicação',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimensão',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'Dimensão aplicação',
	'Class:URP_ClassProjection/Attribute:class' => 'Classe',
	'Class:URP_ClassProjection/Attribute:class+' => 'Classe alvo',
	'Class:URP_ClassProjection/Attribute:value' => 'Expressão de valor',
	'Class:URP_ClassProjection/Attribute:value+' => 'Expressão OQL (usando $ user) | constante | | + código de atributo',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Atributo',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Código de atributo alvo (opcional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'Permissões de classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_ActionGrant/Attribute:profile' => 'Perfil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Perfil utilizado',
	'Class:URP_ActionGrant/Attribute:class' => 'Classe',
	'Class:URP_ActionGrant/Attribute:class+' => 'Classe alvo',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permissões',
	'Class:URP_ActionGrant/Attribute:permission+' => 'Permitido ou não permitido?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Sim',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Não',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '',
	'Class:URP_ActionGrant/Attribute:action' => 'Ação',
	'Class:URP_ActionGrant/Attribute:action+' => 'Operações a realizar em determinada classe',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'Permissões de estímulo do ciclo de vida do objeto',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Perfil utilizado',
	'Class:URP_StimulusGrant/Attribute:class' => 'Classe',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Classe alvo',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permissão',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Permitido ou não permitido?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Sim',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => '',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Não',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => '',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Estímulo',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Código do estímulo',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'Permissões a nível de atributos',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Concessão de permissão',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'Concessão de permissão',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Atributo',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Código do atributo',
));

//
// Class: UserDashboard
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:UserDashboard' => 'Painel do usuário',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Usuário',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Código do menu',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Conteúdo',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'BooleanLabel:yes' => 'Sim',
	'BooleanLabel:no' => 'Não',
	'UI:Login:Title' => 'Login no '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenu' => 'Página inicial do '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>O '.ITOP_APPLICATION_SHORT.' é um Portal Operacional de TI de código aberto completo.</p>
<ul>Ele inclui:
<li>Um CMDB (Configuration Management Database) completo para documentar e gerenciar o inventário de TI.</li>
<li>Um módulo de Gerenciamento de Incidentes para rastrear e comunicar todos os incidentes que ocorrem na TI.</li>
<li>Um módulo de Gerenciamento de Mudanças para planejar e acompanhar as mudanças no ambiente de TI.</li>
<li>Um banco de dados de Erros conhecidos para acelerar a solução de incidentes.</li>
<li>Um módulo de interrupção para documentar todas as interrupções planejadas e notificar os contatos apropriados.</li>
<li>Painéis para obter rapidamente uma visão geral de sua TI.</li>
</ul>
<p>Todos os módulos podem ser configurados, passo a passo, independentemente uns dos outros.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>O '.ITOP_APPLICATION_SHORT.' é orientado para o provedor de serviços, ele permite que os especialistas de TI gerenciem facilmente vários clientes ou organizações.
<ul>O '.ITOP_APPLICATION_SHORT.' oferece um conjunto rico em recursos de processos de negócios que:
<li>Melhora a eficácia do gerenciamento de TI</li> 
<li>Impulsiona o desempenho das operações de TI</li> 
<li>Melhora a satisfação do cliente e fornece aos executivos insights sobre o desempenho dos negócios.</li>
</ul>
</p>
<p>O '.ITOP_APPLICATION_SHORT.' está totalmente aberto para ser integrado à sua infraestrutura de gerenciamento de TI atual.</p>
<p>
<ul>Adotar esta nova geração de portal operacional de TI ajudará você a:
<li>Gerenciar melhor um ambiente de TI cada vez mais complexo.</li>
<li>Implementar processos ITIL no seu próprio ritmo.</li>
<li>Gerenciar o ativo mais importante de sua TI: Documentação.</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Parabéns, você desembarcou no '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>Esta versão apresenta um novo design de backoffice moderno e acessível.</div>

<div>Nós mantivemos as funções principais do '.ITOP_APPLICATION.' que você gostou e as modernizou para fazer você amá-las.
Esperamos que você goste desta versão tanto quanto gostamos de imaginá-la e criá-la.</div>

<div>Personalize as preferências de seu '.ITOP_APPLICATION.' para uma experiência personalizada.</div>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Solicitações abertas: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Minhas solicitações',
	'UI:WelcomeMenu:OpenIncidents' => 'Incidentes abertos: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Itens de Configuração: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidentes atribuídos a mim',
	'UI:AllOrganizations' => ' Todas as organizações ',
	'UI:YourSearch' => 'Sua pesquisa',
	'UI:LoggedAsMessage' => 'Autenticado como %1$s (%2$s)',
	'UI:LoggedAsMessage+Admin' => 'Autenticado como %1$s (%2$s, Administrador)',
	'UI:Button:Logoff' => 'Sair',
	'UI:Button:GlobalSearch' => 'Pesquisar',
	'UI:Button:Search' => ' Pesquisar ',
	'UI:Button:Clear' => ' Limpar ',
	'UI:Button:SearchInHierarchy' => 'Pesquisar na hierarquia',
	'UI:Button:Query' => ' Consultar ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Salvar',
	'UI:Button:SaveAnd' => 'Salvar e %1$s',
	'UI:Button:Cancel' => 'Cancelar',
	'UI:Button:Close' => 'Fechar',
	'UI:Button:Apply' => 'Salvar',
	'UI:Button:Send' => 'Enviar',
	'UI:Button:SendAnd' => 'Enviar e %1$s',
	'UI:Button:Back' => ' << Voltar ',
	'UI:Button:Restart' => ' |<< Reiniciar ',
	'UI:Button:Next' => ' Próximo >> ',
	'UI:Button:Finish' => ' Finalizar ',
	'UI:Button:DoImport' => ' Executar importação ! ',
	'UI:Button:Done' => ' Concluir ',
	'UI:Button:SimulateImport' => ' Simular a importação ',
	'UI:Button:Test' => 'Testar!',
	'UI:Button:Evaluate' => ' Avaliar ',
	'UI:Button:Evaluate:Title' => ' Avaliar (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Adicionar... ',
	'UI:Button:BrowseObjects' => ' Navegar... ',
	'UI:Button:Add' => ' Adicionar ',
	'UI:Button:AddToList' => ' << Adicionar ',
	'UI:Button:RemoveFromList' => ' Excluir >> ',
	'UI:Button:FilterList' => ' Filtrar... ',
	'UI:Button:Create' => ' Criar ',
	'UI:Button:Delete' => ' Excluir ! ',
	'UI:Button:Rename' => ' Renomear... ',
	'UI:Button:ChangePassword' => ' Alterar senha ',
	'UI:Button:ResetPassword' => ' Redefinir senha ',
	'UI:Button:Insert' => 'Inserir',
	'UI:Button:More' => 'Mais',
	'UI:Button:Less' => 'Menos',
	'UI:Button:Wait' => 'Por favor, aguarde enquanto atualiza os campos',
	'UI:Treeview:CollapseAll' => 'Recolher todos',
	'UI:Treeview:ExpandAll' => 'Expandir todos',
	'UI:UserPref:DoNotShowAgain' => 'Não exibir novamente',
	'UI:InputFile:NoFileSelected' => 'Nenhum arquivo selecionado',
	'UI:InputFile:SelectFile' => 'Selecione um arquivo',

	'UI:SearchToggle' => 'Pesquisar',
	'UI:ClickToCreateNew' => 'Criar um(a) %1$s',
	'UI:SearchFor_Class' => 'Pesquisar por objeto(s) de %1$s',
	'UI:NoObjectToDisplay' => 'Nenhum objeto encontrado',
	'UI:Error:SaveFailed' => 'O objeto não pode ser salvo:',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parâmetro Object_id é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parâmetro Target_attr é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parâmetro Group_by é obrigatório. Verifique a definição do modelo de exibição',
	'UI:Error:InvalidGroupByFields' => 'Lista inválida dos campos para agrupar por: "%1$s"',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erro: o estilo não suportada do bloco: "%1$s"',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Definição de ligação incorreta: a classe de objetos para gerenciar: %1$s não foi encontrado como uma chave externa na classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objeto: %1$s:%2$d não encontrado',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Erro: Referência circular nas dependências entre os campos, verifique o modelo de dados',
	'UI:Error:UploadedFileTooBig' => 'O arquivo a ser carregado é muito grande (Tamanho máximo permitido é de %1$s). Para modificar esse limite, contate o administrador do '.ITOP_APPLICATION_SHORT.' (Verifique a configuração do PHP para upload_max_filesize e post_max_size no servidor)',
	'UI:Error:UploadedFileTruncated.' => 'Arquivo enviado foi truncado!',
	'UI:Error:NoTmpDir' => 'Diretório temporário não está definido',
	'UI:Error:CannotWriteToTmp_Dir' => 'Não foi possível gravar o arquivo temporário para o disco. upload_tmp_dir = "%1$s"',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload parou por extensão. (Nome do arquivo original = "%1$s")',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Arquivo carregado falhou, causa desconhecida. (Código do erro = "%1$s")',

	'UI:Error:1ParametersMissing' => 'Erro: o parâmetro a seguir deve ser especificado para esta operação: %1$s',
	'UI:Error:2ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s e %2$s',
	'UI:Error:3ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s, %2$s e %3$s',
	'UI:Error:4ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s, %2$s, %3$s e %4$s',
	'UI:Error:IncorrectOQLQuery_Message' => 'Erro: consulta OQL incorreta: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Ocorreu um erro ao executar a consulta: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Erro: o objeto já foi atualizado',
	'UI:Error:ObjectCannotBeUpdated' => 'Erro: objeto não pode ser atualizado',
	'UI:Error:ObjectsAlreadyDeleted' => 'Erro: objetos já foram apagados',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Você não tem permissão de executar exclusão em massa dos objetos da classe %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Você não tem permissão para excluir objeto(s) da classe %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'Você não tem permissão para ler objeto(s) da classe %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Você não tem permissão de executar atualização em massa dos objetos da classe %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Erro: o objeto já foi clonado',
	'UI:Error:ObjectAlreadyCreated' => 'Erro: o objeto já foi criado',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Erro: estímulo inválido "%1$s" no objeto %2$s com status "%3$s"',
	'UI:Error:InvalidDashboardFile' => 'Erro: arquivo de painel inválido',
	'UI:Error:InvalidDashboard' => 'Erro: painel inválido',
	'UI:Error:MaintenanceMode' => 'A aplicação está em manutenção',
	'UI:Error:MaintenanceTitle' => 'Manutenção',
	'UI:Error:InvalidToken' => 'Erro: A operação solicitada já foi executada (token CSRF não encontrado)',

	'UI:Error:SMTP:UnknownVendor' => 'OAuth SMTP provider %1$s does not exist  (email_transport_smtp.oauth.provider)~~',

	'UI:GroupBy:Count' => 'Número',
	'UI:GroupBy:Count+' => 'Número de elementos',
	'UI:CountOfObjects' => '%1$d objeto(s) correspondem aos critérios',
	'UI_CountOfObjectsShort' => '%1$d objeto(s)',
	'UI:NoObject_Class_ToDisplay' => 'Nenhum %1$s para exibir',
	'UI:History:LastModified_On_By' => 'Última modificação em %1$s por %2$s',
	'UI:HistoryTab' => 'Histórico',
	'UI:NotificationsTab' => 'Notificações',
	'UI:History:BulkImports' => 'Histórico',
	'UI:History:BulkImports+' => 'Lista de importação CSV',
	'UI:History:BulkImportDetails' => 'Alterações resultantes da importação CSV realizado em %1$s (por %2$s)',
	'UI:History:Date' => 'Data',
	'UI:History:Date+' => 'Data da alteração',
	'UI:History:User' => 'Usuário',
	'UI:History:User+' => 'Usuário que realizou a alteração',
	'UI:History:Changes' => 'Alteração',
	'UI:History:Changes+' => 'Alteração feita no objeto',
	'UI:History:StatsCreations' => 'Criado',
	'UI:History:StatsCreations+' => 'Número de objetos criados',
	'UI:History:StatsModifs' => 'Modificado',
	'UI:History:StatsModifs+' => 'Número de objetos criados',
	'UI:History:StatsDeletes' => 'Excluído',
	'UI:History:StatsDeletes+' => 'Número de objetos excluídos',
	'UI:Loading' => 'Carregando...',
	'UI:Menu:Actions' => 'Ações',
	'UI:Menu:OtherActions' => 'Outras ações',
	'UI:Menu:Transitions' => 'Transições',
	'UI:Menu:OtherTransitions' => 'Outras Transições',
	'UI:Menu:New' => 'Novo...',
	'UI:Menu:Add' => 'Adicionar...',
	'UI:Menu:Manage' => 'Gerenciar...',
	'UI:Menu:EMail' => 'Enviar via e-mail',
	'UI:Menu:CSVExport' => 'Exportar para CSV...',
	'UI:Menu:Modify' => 'Editar...',
	'UI:Menu:Delete' => 'Excluir...',
	'UI:Menu:BulkDelete' => 'Exclução em massa...',
	'UI:UndefinedObject' => '(n/a)',
	'UI:Document:OpenInNewWindow:Download' => 'Abrir em uma nova janela: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'data',
	'UI:SplitDateTime-Time' => 'hora',
	'UI:TruncatedResults' => '%1$d objeto(s) de %2$d',
	'UI:DisplayAll' => 'Exibir todos',
	'UI:CollapseList' => 'Recolher lista',
	'UI:CountOfResults' => '%1$d objeto(s)',
	'UI:ChangesLogTitle' => 'Log de alteração(ões) (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Log de alteração(ões) está limpo',
	'UI:SearchFor_Class_Objects' => 'Pesquisa de objeto(s) de %1$s ',
	'UI:OQLQueryBuilderTitle' => 'Construir consulta OQL',
	'UI:OQLQueryTab' => 'Consulta OQL',
	'UI:SimpleSearchTab' => 'Pesquisa simples',
	'UI:Details+' => 'Detalhes',
	'UI:SearchValue:Any' => '* qualquer *',
	'UI:SearchValue:Mixed' => '* misturado *',
	'UI:SearchValue:NbSelected' => '# selecionado',
	'UI:SearchValue:CheckAll' => 'Marcar todos',
	'UI:SearchValue:UncheckAll' => 'Desmarcar todos',
	'UI:SelectOne' => '-- selecione um --',
	'UI:Login:Welcome' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Usuário e/ou senha inválido(s), tente novamente',
	'UI:Login:IdentifyYourself' => 'Identifique-se antes continuar',
	'UI:Login:UserNamePrompt' => 'Usuário',
	'UI:Login:PasswordPrompt' => 'Senha',
	'UI:Login:ForgotPwd' => 'Esqueceu sua senha?',
	'UI:Login:ForgotPwdForm' => 'Esqueceu sua senha',
	'UI:Login:ForgotPwdForm+' => 'O '.ITOP_APPLICATION_SHORT.' pode enviar um e-mail em que você vai encontrar instruções para seguir para redefinir sua conta',
	'UI:Login:ResetPassword' => 'Enviar agora',
	'UI:Login:ResetPwdFailed' => 'Falha ao enviar e-mail: %1$s',
	'UI:Login:SeparatorOr' => 'Ou',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' não é um login válido',
	'UI:ResetPwd-Error-NotPossible' => 'Não é permitida alteração de senha de contas externas',
	'UI:ResetPwd-Error-FixedPwd' => 'A conta não permite alteração de senha',
	'UI:ResetPwd-Error-NoContact' => 'A conta não está associada a uma pessoa',
	'UI:ResetPwd-Error-NoEmailAtt' => 'A conta não está associada a uma pessoa que contém um endereço de e-mail no '.ITOP_APPLICATION_SHORT.'. Por favor, contate o administrador',
	'UI:ResetPwd-Error-NoEmail' => 'A conta não contém um endereço de e-mail. Por favor, contate o administrador',
	'UI:ResetPwd-Error-Send' => 'Houve um problema técnico de transporte de e-mail. Por favor, contate o administrador',
	'UI:ResetPwd-EmailSent' => 'Verifique sua caixa de e-mail e siga as instruções. Se você não receber nenhum e-mail, verifique a caixa de SPAM e o login que você digitou',
	'UI:ResetPwd-EmailSubject' => 'Alterar a senha',
	'UI:ResetPwd-EmailBody' => '<body><p>Você solicitou a alteração da senha do '.ITOP_APPLICATION_SHORT.'.</p><p>Por favor, siga este link (passo simples) para <a href="%1$s">digitar a nova senha</a></p>.',

	'UI:ResetPwd-Title' => 'Alterar senha',
	'UI:ResetPwd-Error-InvalidToken' => 'Desculpe, a senha já foi alterada, ou você deve ter recebido múltiplos e-mails. Por favor, certifique-se que você acessou o link fornecido no último e-mail recebido',
	'UI:ResetPwd-Error-EnterPassword' => 'Digite a nova senha para a conta \'%1$s\'',
	'UI:ResetPwd-Ready' => 'A senha foi alterada com sucesso',
	'UI:ResetPwd-Login' => 'Clique para entrar...',

	'UI:Login:About'                               => '',
	'UI:Login:ChangeYourPassword'                  => 'Alterar sua senha',
	'UI:Login:OldPasswordPrompt'                   => 'Senha antiga',
	'UI:Login:NewPasswordPrompt'                   => 'Nova senha',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Repetir nova senha',
	'UI:Login:IncorrectOldPassword'                => 'Erro: senha antiga incorreta',
	'UI:LogOffMenu'                                => 'Sair',
	'UI:LogOff:ThankYou'                           => 'Obrigado por usar o sistema',
	'UI:LogOff:ClickHereToLoginAgain'              => 'Clique aqui para entrar novamente...',
	'UI:ChangePwdMenu'                             => 'Alterar senha...',
	'UI:Login:PasswordChanged'                     => 'Senha alterada com sucesso',
	'UI:AccessRO-All'                              => 'Somente-leitura',
	'UI:AccessRO-Users'                            => ITOP_APPLICATION.' é somente leitura para usuários finais',
	'UI:ApplicationEnvironment'                    => 'Ambiente da aplicação: %1$s',
	'UI:Login:RetypePwdDoesNotMatch'               => '"Nova senha" e "Repetir nova senha" são diferentes. Tente novamente!',
	'UI:Button:Login'                              => 'Login',
	'UI:Login:Error:AccessRestricted'              => 'Acesso restrito. Por favor, contacte o administrador',
	'UI:Login:Error:AccessAdmin'                   => 'Acesso restrito somente para usuários com privilégios administrativos. Por favor, contacte o administrador',
	'UI:Login:Error:WrongOrganizationName'         => 'Organização não encontrada',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Vários contatos têm o mesmo e-mail',
	'UI:Login:Error:NoValidProfiles'               => 'Nenhum perfil válido fornecido',
	'UI:CSVImport:MappingSelectOne'                => '-- selecione um --',
	'UI:CSVImport:MappingNotApplicable'            => '-- ignorar este campo --',
	'UI:CSVImport:NoData'                          => 'Nenhum dado configurado. Por favor, providencie alguns dados!',
	'UI:Title:DataPreview'                         => 'Visualizar dados',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Erro: Os dados contêm apenas uma coluna. Você selecionou o caractere separador apropriado?',
	'UI:CSVImport:FieldName'                       => 'Campo %1$d',
	'UI:CSVImport:DataLine1'                       => 'Dados da linha 1',
	'UI:CSVImport:DataLine2'                       => 'Dados da linha 2',
	'UI:CSVImport:idField'                         => 'ID (Chave primária)',
	'UI:Title:BulkImport'                          => 'Importação em massa',
	'UI:Title:BulkImport+'                         => 'Assistente de Importação CSV',
	'UI:Title:BulkSynchro_nbItem_ofClass_class'    => 'Sincronização de %1$d objetos da classe %2$s',
	'UI:CSVImport:ClassesSelectOne'                => '-- selecione um --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Erro interno: "%1$s" é um código incorreto porque "%2$s" não é uma chave externa da classe "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objetos permanecerão inalterados',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objetos serão modificados',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objetos serão adicionados',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objetos terão erros',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objetos manteve-se inalterados',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objetos foram modificados',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objetos foram adicionados',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objetos apresentaram erros',
	'UI:Title:CSVImportStep2' => 'Passo 2 de 5: Opções de importação CSV',
	'UI:Title:CSVImportStep3' => 'Passo 3 de 5: Mapeamento de dados',
	'UI:Title:CSVImportStep4' => 'Passo 4 de 5: Simulação da importação',
	'UI:Title:CSVImportStep5' => 'Passo 5 de 5: Importação concluída',
	'UI:CSVImport:LinesNotImported' => 'Linhas que não podem ser carregadas:',
	'UI:CSVImport:LinesNotImported+' => 'As linhas a seguir não foram importadas, porque elas contêm erros',
	'UI:CSVImport:SeparatorComma+' => ', (vírgula)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (ponto e vírgula)',
	'UI:CSVImport:SeparatorTab+' => '	(tabulação)',
	'UI:CSVImport:SeparatorOther' => 'outro:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (aspas duplas)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (aspas simples)',
	'UI:CSVImport:QualifierOther' => 'outro:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Tratar a primeira linha como um cabeçalho (nomes de colunas)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Pular %1$s linha(s) no início do arquivo',
	'UI:CSVImport:CSVDataPreview' => 'Visualizar dados CSV',
	'UI:CSVImport:SelectFile' => 'Selecione o arquivo a importar:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Carregar de um arquivo',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiar e colar dados',
	'UI:CSVImport:Tab:Templates' => 'Modelos de CSV',
	'UI:CSVImport:PasteData' => 'Cole os dados para importar:',
	'UI:CSVImport:PickClassForTemplate' => 'Escolha o modelo CSV para baixar: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caracter separador de texto:',
	'UI:CSVImport:TextQualifierCharacter' => 'Caracter qualificador de texto:',
	'UI:CSVImport:CommentsAndHeader' => 'Comentários e cabeçalho',
	'UI:CSVImport:SelectClass' => 'Selecione a classe para importar:',
	'UI:CSVImport:AdvancedMode' => 'Modo avançado',
	'UI:CSVImport:AdvancedMode+' => 'No modo avançado o "ID" (chave primária) dos objetos pode ser usado para atualizar e renomear objetos. No entanto, a coluna "ID" (se houver) só pode ser usado como um critério de pesquisa e não pode ser combinado com qualquer outro critério de busca',
	'UI:CSVImport:SelectAClassFirst' => 'Para configurar o mapeamento, selecione uma classe primeiro',
	'UI:CSVImport:HeaderFields' => 'Campos',
	'UI:CSVImport:HeaderMappings' => 'Mapeamentos',
	'UI:CSVImport:HeaderSearch' => 'Pesquisar?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Por favor, selecione um mapeamento para cada campo',
	'UI:CSVImport:AlertMultipleMapping' => 'Por favor, certifique-se que um campo de destino é mapeado apenas uma vez',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Selecione ao menos um critério de busca',
	'UI:CSVImport:Encoding' => 'Codificação de caracteres:',
	'UI:UniversalSearchTitle' => 'Pesquisa Universal',
	'UI:UniversalSearch:Error' => 'Erro: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selecione a classe para pesquisar: ',

	'UI:CSVReport-Value-Modified' => 'Modificado',
	'UI:CSVReport-Value-SetIssue' => 'invalid value for attribute~~',
	'UI:CSVReport-Value-ChangeIssue' => '\'%1$s\' is an invalid value~~',
	'UI:CSVReport-Value-NoMatch' => 'No match for value \'%1$s\'~~',
	'UI:CSVReport-Value-Missing' => 'Faltando valor obrigatório',
	'UI:CSVReport-Value-Ambiguous' => 'Ambíguo: encontrado %1$s objeto(s)',
	'UI:CSVReport-Row-Unchanged' => 'inalterado',
	'UI:CSVReport-Row-Created' => 'criado',
	'UI:CSVReport-Row-Updated' => 'atualizado %1$d colunas',
	'UI:CSVReport-Row-Disappeared' => 'desapareceu, alterado %1$d coluna(s)',
	'UI:CSVReport-Row-Issue' => 'Problema: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Nulo não permitido',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objeto não encontrado',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Encontrado %1$d combinações',
	'UI:CSVReport-Value-Issue-Readonly' => 'O atributo \'%1$s\' é somente-leitura e não pode ser modificado (valor atual: %2$s, valor proposto: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Falha ao processar a entrada: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Valor inesperado para o atributo \'%1$s\': nenhuma correspondência encontrada, verifique a ortografia',
	'UI:CSVReport-Value-Issue-Unknown' => 'Valor inesperado para o atributo \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Atributos não consistentes uns com os outros: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Valor(es) de atributo inesperado(s)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Não foi possível criar devido à(s) chave(s) externa(s) ausente(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'formato de data inválido',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'não conseguiu reconciliar',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'reconciliação ambígua',
	'UI:CSVReport-Row-Issue-Internal' => 'Erro interno: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Não modificado',
	'UI:CSVReport-Icon-Modified' => 'Modificado',
	'UI:CSVReport-Icon-Missing' => 'Ausente',
	'UI:CSVReport-Object-MissingToUpdate' => 'Objeto ausente: será atualizado',
	'UI:CSVReport-Object-MissingUpdated' => 'Objeto ausente: atualizado',
	'UI:CSVReport-Icon-Created' => 'Criado',
	'UI:CSVReport-Object-ToCreate' => 'Objeto acaba ser criado',
	'UI:CSVReport-Object-Created' => 'Objeto criado',
	'UI:CSVReport-Icon-Error' => 'Erro',
	'UI:CSVReport-Object-Error' => 'ERRO: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'AMBÍGUO: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% dos objetos carregados têm erros e serão ignorados',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% dos objetos carregados serão criados',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% dos objetos carregados serão modificados',

	'UI:CSVExport:AdvancedMode' => 'Modo avançado',
	'UI:CSVExport:AdvancedMode+' => 'No modo avançado, várias colunas são adicionadas à exportação: o ID do objeto, o ID das chaves externas e seus atributos de reconciliação',
	'UI:CSVExport:LostChars' => 'Problema de codificação',
	'UI:CSVExport:LostChars+' => 'O arquivo baixado será codificado em %1$s. O '.ITOP_APPLICATION_SHORT.' detectou alguns caracteres que não são compatíveis com este formato. Esses caracteres serão substituídos por um substituto (por exemplo, caracteres acentuados perdendo o acento) ou serão descartados. Você pode copiar/colar os dados do seu navegador da web. Como alternativa, você pode entrar em contato com seu administrador para alterar a codificação (consulte o parâmetro \'csv_file_default_charset\' do arquivo de configuração do '.ITOP_APPLICATION_SHORT.')',

	'UI:Audit:Title' => 'Auditoria do CMDB',
	'UI:Audit:InteractiveAudit' => 'Auditoria Interativa',
	'UI:Audit:HeaderAuditRule' => 'Regra de Auditoria',
	'UI:Audit:HeaderNbObjects' => '# Objetos',
	'UI:Audit:HeaderNbErrors' => '# Erros',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:OqlError' => 'Erro OQL',
	'UI:Audit:Error:ValueNA' => 'n/a',
	'UI:Audit:ErrorIn_Rule' => 'Erro na Regra',
	'UI:Audit:ErrorIn_Rule_Reason' => 'Erro OQL na Regra %1$s: %2$s',
	'UI:Audit:ErrorIn_Category' => 'Erro na Categoria',
	'UI:Audit:ErrorIn_Category_Reason' => 'Erro OQL na Categoria %1$s: %2$s',
	'UI:Audit:AuditErrors' => 'Erros de auditoria',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objetos auditados',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objetos com erros',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objetos validados',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s erros de %2$s - %3$s%%',


	'UI:RunQuery:Title' => 'Avaliar consultas OQL',
	'UI:RunQuery:QueryExamples' => 'Exemplos de consultas',
	'UI:RunQuery:QueryResults' => 'Resultado da consulta',
	'UI:RunQuery:HeaderPurpose' => 'Propósito',
	'UI:RunQuery:HeaderPurpose+' => 'Explicação sobre a consulta',
	'UI:RunQuery:HeaderOQLExpression' => 'A consulta na sintaxe OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'A consulta na sintaxe OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expressão para avaliar: ',
	'UI:RunQuery:QueryArguments' => 'Argumentos da consulta',
	'UI:RunQuery:MoreInfo' => 'Mais informações sobre a consulta: ',
	'UI:RunQuery:DevelopedQuery' => 'Redevelopped query expression: ',
	'UI:RunQuery:SerializedFilter' => 'Filtro serializado: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Contagem de SQL Resultante',
	'UI:RunQuery:ResultSQL' => 'SQL Resultante',
	'UI:RunQuery:Error' => 'Ocorreu um erro ao executar a consulta',
	'UI:Query:UrlForExcel' => 'URL a ser usada para consultas web MS-Excel',
	'UI:Query:UrlV1' => 'A lista de campos não foi especificada. A página <em>export-V2.php</em> não pode ser chamada sem essa informação. Portanto, o URL sugerido abaixo aponta para a página herdada: <em>export.php</em>. Essa versão herdada da exportação tem a seguinte limitação: a lista de campos exportados pode variar dependendo do formato de saída e do modelo de dados do '.ITOP_APPLICATION_SHORT.'. Se você quiser garantir que a lista de colunas exportadas permaneça estável a longo prazo, então você deve especificar um valor para o atributo "Fields" e usar a página <em>export-V2.php</em>',
	'UI:Schema:Title' => 'Esquema de objetos',
	'UI:Schema:TitleForClass' => 'Esquema de %1$s',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relações',
	'UI:Schema:AbstractClass' => 'Classe abstrata: nenhum objeto desta classe pode ser instanciado',
	'UI:Schema:NonAbstractClass' => 'Classe não-abstrata: os objetos desta classe pode ser instanciado',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarquia de classes',
	'UI:Schema:AllClasses' => 'Todas classes',
	'UI:Schema:ExternalKey_To' => 'Chave externa para %1$s',
	'UI:Schema:Columns_Description' => 'Colunas: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Padrão: "%1$s"',
	'UI:Schema:NullAllowed' => 'Nulo permitido',
	'UI:Schema:NullNotAllowed' => 'Nulo não permitido',
	'UI:Schema:Attributes' => 'Atributos',
	'UI:Schema:AttributeCode' => 'Código do atributo',
	'UI:Schema:AttributeCode+' => 'Código interno do atributo',
	'UI:Schema:Label' => 'Rótulo',
	'UI:Schema:Label+' => 'Rótulo do atributo',
	'UI:Schema:Type' => 'Tipo',

	'UI:Schema:Type+' => 'Tipo de dado do atributo',
	'UI:Schema:Origin' => 'Origem',
	'UI:Schema:Origin+' => 'A classe base na qual este atributo é definido',
	'UI:Schema:Description' => 'Descrição',
	'UI:Schema:Description+' => 'Descrição do atributo',
	'UI:Schema:AllowedValues' => 'Valores permitidos',
	'UI:Schema:AllowedValues+' => 'Restrições sobre os valores possíveis para este atributo',
	'UI:Schema:MoreInfo' => 'Mais informações',
	'UI:Schema:MoreInfo+' => 'Mais informações sobre o campo definido no banco de dados',
	'UI:Schema:SearchCriteria' => 'Search criteria',
	'UI:Schema:FilterCode' => 'Código de filtro',
	'UI:Schema:FilterCode+' => 'Código deste critério de pesquisa',
	'UI:Schema:FilterDescription' => 'Descrição',
	'UI:Schema:FilterDescription+' => 'Descrição do critério de pesquisa',
	'UI:Schema:AvailOperators' => 'Operadores disponíveis',
	'UI:Schema:AvailOperators+' => 'Operadores possíveis para estes critérios de pesquisa',
	'UI:Schema:ChildClasses' => 'Classes filhas',
	'UI:Schema:ReferencingClasses' => 'Classes de referência',
	'UI:Schema:RelatedClasses' => 'Classes relacionadas',
	'UI:Schema:LifeCycle' => 'Ciclo de vida',
	'UI:Schema:Triggers' => 'Gatilho',
	'UI:Schema:Relation_Code_Description' => 'Relação <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Down: %1$s',
	'UI:Schema:RelationUp_Description' => 'Up: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagar para %2$d níveis, consulta: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: não propagar para (%2$d levels), consulta: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s é referenciado pela classe %2$s via compo %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s está ligada à %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Classes apontando para %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Classes apontando para %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Gráfico de todas as classes relacionadas',
	'UI:Schema:NoLifeCyle' => 'Não há ciclo de vida definido para esta classe',
	'UI:Schema:LifeCycleTransitions' => 'Transições',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opções de atributo',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Oculto',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Somente-leitura',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obrigatório',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Deve alterar',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Usuário será solicitado a alterar o valor',
	'UI:Schema:LifeCycleEmptyList' => 'Lista vazia',
	'UI:Schema:ClassFilter' => 'Classe:',
	'UI:Schema:DisplayLabel' => 'Exibir:',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Rótulo e código',
	'UI:Schema:DisplaySelector/Label' => 'Rótulo',
	'UI:Schema:DisplaySelector/Code' => 'Código',
	'UI:Schema:Attribute/Filter' => 'Filtro',
	'UI:Schema:DefaultNullValue' => 'Padrão nulo : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Digite os três caracteres iniciais...',
	'UI:Edit:SearchQuery' => 'Selecionar uma consulta pré-definida',
	'UI:Edit:TestQuery' => 'Testar consulta',
	'UI:Combo:SelectValue' => '--- selecione um valor ---',
	'UI:Label:SelectedObjects' => 'Objetos selecionados: ',
	'UI:Label:AvailableObjects' => 'Objetos disponíveis: ',
	'UI:Link_Class_Attributes' => '%1$s atributos',
	'UI:SelectAllToggle+' => 'Marcar todas / Desmarcar todas',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Associar objetos de %1$s com %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Associar objetos de %1$s com %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Gerenciar vínculo de objetos de %1$s com %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Associar %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Desassociar objeto(s) selecionado(s)',
	'UI:Message:EmptyList:UseAdd' => 'A lista está vazia, use o botão "Associar..." para adicionar elementos',
	'UI:Message:EmptyList:UseSearchForm' => 'Use o formulário de busca acima para procurar objeto(s) a ser(em) adicionado(s)',
	'UI:Wizard:FinalStepTitle' => 'Passo final: confirmação',
	'UI:Title:DeletionOf_Object' => 'Excluindo de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Exclusão em massa de %1$d objetos da classe %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Você não tem permissão para excluir este objeto',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Você não tem permissão para atualizar o(s) seguinte(s) campo(s): %1$s',
	'UI:Error:ActionNotAllowed' => 'Você não tem permissão para fazer essa ação',
	'UI:Error:NotEnoughRightsToDelete' => 'Este objeto não pode ser apagado porque o usuário atual não tem direitos suficientes',
	'UI:Error:CannotDeleteBecause' => 'Este objeto não pode ser excluído porque: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Este objeto não pode ser excluído porque algumas operações manuais devem ser realizadas antes',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Este objeto não pode ser excluído porque algumas operações manuais devem ser realizadas antes',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s em nome de %2$s',
	'UI:Delete:Deleted' => 'excluído',
	'UI:Delete:AutomaticallyDeleted' => 'excluído automaticamente',
	'UI:Delete:AutomaticResetOf_Fields' => 'reposição automática de campo(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Limpeza de todas as referências a %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Limpeza de todas as referências a %1$d objetos da classe %2$s...',
	'UI:Delete:Done+' => 'O que foi feito...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s excluído',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Excluir "%1$s"',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Exclusão de %1$d objetos da classe %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Não pode ser excluído: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Deve ser excluído automaticamente, mas isso não é possível: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Devem ser excluído manualmente, mas isso não é possível: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Será automaticamente excluído',
	'UI:Delete:MustBeDeletedManually' => 'Será manualmente excluído',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Devem ser atualizados automaticamente, porém: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Será automaticamente atualizado (redefinir: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objetos/links fazem referência a(o) "%2$s"',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objetos/links fazem referências a alguns dos objetos a serem excluídos',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Para garantir a integridade do banco de dados, todas as referências a este objeto devem ser eliminadas',
	'UI:Delete:Consequence+' => 'O que será feito',
	'UI:Delete:SorryDeletionNotAllowed' => 'Por favor, realize as operações manuais listadas acima antes de solicitar a exclusão do referido objeto',
	'UI:Delete:PleaseDoTheManualOperations' => 'Por favor, realize as operações manuais listadas acima antes de solicitar a exclusão do referido objeto',
	'UI:Delect:Confirm_Object' => 'Por favor, confirme se você deseja excluir "%1$s"',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Por favor, confirme que você deseja excluir o(s) seguinte(s) %1$d objeto(s) da classe "%2$s"',
	'UI:WelcomeToITop' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'UI:DetailsPageTitle' => '%1$s - Detalhes do(a) %2$s',
	'UI:ErrorPageTitle' => 'Erro',
	'UI:ObjectDoesNotExist' => 'Desculpe, este objeto não existe (ou você não tem permissão para vê-lo)',
	'UI:ObjectArchived' => 'Este objeto foi arquivado. Por favor, habilite o modo de arquivamento ou entre em contato com o seu administrador',
	'Tag:Archived' => 'Arquivado',
	'Tag:Archived+' => 'Pode ser acessado apenas no modo de arquivamento',
	'Tag:Obsolete' => 'Obsoleto',
	'Tag:Obsolete+' => 'Excluído da análise de impacto e resultados de pesquisa',
	'Tag:Synchronized' => 'Sincronizado',
	'ObjectRef:Archived' => 'Arquivado',
	'ObjectRef:Obsolete' => 'Obsoleto',
	'UI:SearchResultsPageTitle' => 'Resultados da pesquisa',
	'UI:SearchResultsTitle' => 'Resultados da pesquisa',
	'UI:SearchResultsTitle+' => 'Resultados da pesquisa de texto completo',
	'UI:Search:NoSearch' => 'Nada a pesquisar',
	'UI:Search:NeedleTooShort' => 'A string de pesquisa \"%1$s\" é muito curta. Por favor, digite pelo menos %2$d caracteres.',
	'UI:Search:Ongoing' => 'Procurando por \"%1$s\"',
	'UI:Search:Enlarge' => 'Amplie a pesquisa',
	'UI:FullTextSearchTitle_Text' => 'Resultados da pesquisa para "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objeto(s) da classe %2$s encontrado(s).',
	'UI:Search:NoObjectFound' => 'Nenhum objeto encontrado',
	'UI:ModificationPageTitle_Object_Class' => '%1$s - Modificação de(a) %2$s',
	'UI:ModificationTitle_Class_Object' => 'Modificação de(a) %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'Clonagem de %1$s - Modificação de %2$s',
	'UI:CloneTitle_Class_Object' => 'Clonagem de %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'Criar um(a) novo(a) %1$s ',
	'UI:CreationTitle_Class' => 'Criar um(a) novo(a) %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Selecione o tipo de %1$s para criar:',
	'UI:Class_Object_NotUpdated' => 'Nenhuma modificação detectada, %1$s (%2$s) <strong>não</strong> foi modificado(a)',
	'UI:Class_Object_Updated' => '%1$s (%2$s) atualizado(a)',
	'UI:BulkDeletePageTitle' => 'Exclusão em massa',
	'UI:BulkDeleteTitle' => 'Selecione o(s) objeto(s) que você deseja excluir:',
	'UI:PageTitle:ObjectCreated' => 'Objeto criado',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s criado(a)',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Aplicando %1$s no objeto: %2$s com status %3$s para o status alvo: %4$s',
	'UI:ObjectCouldNotBeWritten' => 'O objeto não pode ser gravado: %1$s',
	'UI:PageTitle:FatalError' => 'Erro fatal',
	'UI:SystemIntrusion' => 'Acesso negado. Você está tentando realizar uma operação que não é permitida para você',
	'UI:FatalErrorMessage' => 'Erro fatal, o sistema não pode continuar',
	'UI:Error_Details' => 'Erro: %1$s',

	'UI:PageTitle:ProfileProjections' => 'Gerenciamento de Usuários - Mapeamento de Perfis',
	'UI:UserManagement:Class' => 'Classe',
	'UI:UserManagement:Class+' => 'Classe de objetos',
	'UI:UserManagement:ProjectedObject' => 'Objeto',
	'UI:UserManagement:ProjectedObject+' => 'Objetos mapeados',
	'UI:UserManagement:AnyObject' => '* qualquer *',
	'UI:UserManagement:User' => 'Usuário',
	'UI:UserManagement:User+' => 'Usuário(s) envolvido(s) no mapeamento',
	'UI:UserManagement:Action:Read' => 'Ler',
	'UI:UserManagement:Action:Read+' => 'Ler/Exibir objetos',
	'UI:UserManagement:Action:Modify' => 'Editar',
	'UI:UserManagement:Action:Modify+' => 'Criar e editar objetos',
	'UI:UserManagement:Action:Delete' => 'Excluir',
	'UI:UserManagement:Action:Delete+' => 'Excluir objetos',
	'UI:UserManagement:Action:BulkRead' => 'Exibir/Exportar objetos em massa',
	'UI:UserManagement:Action:BulkRead+' => 'Exibir objetos ou exportar em massa',
	'UI:UserManagement:Action:BulkModify' => 'Edição em massa',
	'UI:UserManagement:Action:BulkModify+' => 'Criar/editar em massa (importar CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Exclusão em massa',
	'UI:UserManagement:Action:BulkDelete+' => 'Excluir objeto(s) em massa',
	'UI:UserManagement:Action:Stimuli' => 'Estímulo',
	'UI:UserManagement:Action:Stimuli+' => 'Ações permitidas (composta)',
	'UI:UserManagement:Action' => 'Ações',
	'UI:UserManagement:Action+' => 'Ações realizadas pelo usuário',
	'UI:UserManagement:TitleActions' => 'Ações',
	'UI:UserManagement:Permission' => 'Permissões',
	'UI:UserManagement:Permission+' => 'Permissões de usuários',
	'UI:UserManagement:Attributes' => 'Atributos',
	'UI:UserManagement:ActionAllowed:Yes' => 'Sim',
	'UI:UserManagement:ActionAllowed:No' => 'Não',
	'UI:UserManagement:AdminProfile+' => 'Administradores tem total acesso leitura/gravação para todos os objetos no banco de dados',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Ciclo de vida não foi definido para esta classe',
	'UI:UserManagement:GrantMatrix' => 'Permissões de acesso',

	'Menu:AdminTools' => 'Ferramentas Administrativas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Ferramentas Administrativas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Ferramentas acessíveis apenas para usuários com o perfil de administrador',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'Sistema',

	'UI:ChangeManagementMenu' => 'Gerenciamento de Mudanças',
	'UI:ChangeManagementMenu+' => 'Gerenciamento de Mudanças',
	'UI:ChangeManagementMenu:Title' => 'Visão geral',
	'UI-ChangeManagementMenu-ChangesByType' => 'Mudanças por tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Mudanças por status',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Mudanças não atribuídas',

	'UI:ConfigurationManagementMenu' => 'Gerenciamento Configuração',
	'UI:ConfigurationManagementMenu+' => 'Gerenciamento de Configuração',
	'UI:ConfigurationManagementMenu:Title' => 'Visão geral',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objetos de infraestrutura por tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objetos de infraestrutura por status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Configuração',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Itens de configuração por status',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Itens de configuração por tipo',

	'UI:RequestMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Solicitações',
	'UI-RequestManagementOverview-RequestByService' => 'Solicitações de usuários por serviço',
	'UI-RequestManagementOverview-RequestByPriority' => 'Solicitações de usuários por prioridade',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Solicitações de usuários não atribuídas a um agente',

	'UI:IncidentMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Incidentes',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidentes por serviço',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidentes por prioridade',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidentes não atribuídos a um agente',

	'UI:ChangeMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Mudanças',
	'UI-ChangeManagementOverview-ChangeByType' => 'Mudanças por tipo',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Mudanças não atribuídas a um agente',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interrupções devido a mudanças',

	'UI:ServiceMgmtMenuOverview:Title' => 'Painel de Gerenciamento de Serviços',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratos de clientes a serem renovados em 30 dias',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contratos de provedores a serem renovados em 30 dias',

	'UI:ContactsMenu' => 'Contatos',
	'UI:ContactsMenu+' => 'Contatos',
	'UI:ContactsMenu:Title' => 'Visão geral',
	'UI-ContactsMenu-ContactsByLocation' => 'Contatos por localização',
	'UI-ContactsMenu-ContactsByType' => 'Contatos por tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contatos por status',

	'Menu:CSVImportMenu' => 'Importar CSV',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Criação ou atualização em massa',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Modelo Dados',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Visão geral do Modelo de Dados',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Exportar',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Exportar o resultado de qualquer consulta em HTML, CSV ou XML',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notificações',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuração de Notificações',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuração de Notificações',
	'UI:NotificationsMenu:Help' => 'Ajuda',
	'UI:NotificationsMenu:HelpContent' => '<p>As Notificações são totalmente personalizáveis​​. Elas são baseadas em dois conjuntos de objetos: <i>Gatilhos e Ações</i>.</p>
<p><i><b>Gatilhos</b></i> definem quando uma notificação será executada. Existem diferentes gatilhos como parte do núcleo do iTop, mas outros podem ser trazidos por extensões:
<ol>
	<li>Alguns gatilhos são acionados quando um objeto de uma determinada classe é <b>criado</b>, <b>atualizado</b> ou <b>excluído</b>.</li>
	<li>Alguns gatilhos são acionados quando um objeto de uma determinada classe <b>entra</b> ou <b>sai</b> de um </b>status</b> específico.</li>
	<li>Alguns gatilhos são acionados quando um <b>limite de TTO ou TTR</b> for <b>alcançado</b>.</li>
</ol>
</p>
<p>
<i><b>Ações</b></i> definem as ações a serem executadas quando os gatilhos forem acionados. Por enquanto, existem apenas dois tipos de ações:
<ol>
	<li>Envio de uma mensagem de e-mail: Tais ações também definem o modelo a ser usado para enviar o e-mail, bem como os demais parâmetros da mensagem, como destinatário(s), prioridade, etc.<br />
	Uma página especial: <a href="../setup/email.test.php" target="_blank">email.test.php</a> está disponível para testar e solucionar problemas de configuração de e-mail PHP.</li>
	<li>Webhooks de saída: permite a integração com um aplicativo de terceiros enviando dados estruturados para um URL definido.</li>
</ol>
</p>
<p>Para serem executadas, as ações devem estar associadas a gatilhos.
Quando associada a um gatilho, cada ação recebe um número de "ordem", especificando em qual ordem as ações devem ser executadas.</p>',
	'UI:NotificationsMenu:Triggers' => 'Gatilhos',
	'UI:NotificationsMenu:AvailableTriggers' => 'Gatilhos disponíveis',
	'UI:NotificationsMenu:OnCreate' => 'Quando um objeto é criado',
	'UI:NotificationsMenu:OnStateEnter' => 'Quando um objeto entra em um determinado status',
	'UI:NotificationsMenu:OnStateLeave' => 'Quando um objeto sai um determinado status',
	'UI:NotificationsMenu:Actions' => 'Ações',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Ações de e-mail',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Ações do Webhook (integrações de saída)',
	'UI:NotificationsMenu:Actions:Action' => 'Outras ações',
	'UI:NotificationsMenu:AvailableActions' => 'Ações disponíveis',

	'Menu:TagAdminMenu' => 'Configuração de Tags',
	'Menu:TagAdminMenu+' => 'Gerenciamento de valores de tags',
	'UI:TagAdminMenu:Title' => 'Configuração de Tags',
	'UI:TagAdminMenu:NoTags' => 'Nenhum campo Tag configurado',
	'UI:TagSetFieldData:Error' => 'Erro: %1$s',

	'Menu:AuditCategories' => 'Categorias de Auditoria',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Categorias de Auditoria',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Categorias de Auditoria',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Executar Consultas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Executar qualquer consulta',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Livro de Consultas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Livro de Consultas',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Administração de Dados',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Administração de Dados',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Pesquisa Universal',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Pesquisar em todo o '.ITOP_APPLICATION_SHORT.'...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Gerenciamento de Usuários',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Gerenciamento de Usuários',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Perfis',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Perfis',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Perfis',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Contas usuários',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Contas usuários',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Contas usuários',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s versão %2$s',
	'UI:iTopVersion:Long' => '%1$s versão %2$s-%3$s compilação %4$s',
	'UI:PropertiesTab' => 'Propriedades',

	'UI:OpenDocumentInNewWindow_' => 'Abrir',
	'UI:DownloadDocument_' => 'Baixar',
	'UI:Document:NoPreview' => 'Nenhuma visualização está disponível para este documento',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Perdida por %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Ajuda',
	'UI:PasswordConfirm' => 'Repetir senha',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Antes de adicionar mais %1$s objetos, salve este objeto',
	'UI:DisplayThisMessageAtStartup' => 'Exibir esta mensagem na inicialização',
	'UI:RelationshipGraph' => 'Visualizar gráfico',
	'UI:RelationshipList' => 'Exibir',
	'UI:RelationGroups' => 'Grupos',
	'UI:OperationCancelled' => 'Operação cancelada',
	'UI:ElementsDisplayed' => 'Filtrando',
	'UI:RelationGroupNumber_N' => 'Grupo #%1$d',
	'UI:Relation:ExportAsPDF' => 'Exportar como PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Limite de agrupamento',
	'UI:Relation:AdditionalContextInfo' => 'Informações de contexto adicionais',
	'UI:Relation:NoneSelected' => 'Nenhum',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Exportar como Anexo...',
	'UI:Relation:DrillDown' => 'Detalhes...',
	'UI:Relation:PDFExportOptions' => 'Opções de exportação de PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Opções de anexo para %1$s',
	'UI:RelationOption:Untitled' => 'Sem título',
	'UI:Relation:Key' => 'Chave',
	'UI:Relation:Comments' => 'Comentários',
	'UI:RelationOption:Title' => 'Título',
	'UI:RelationOption:IncludeList' => 'Incluir a lista de objetos',
	'UI:RelationOption:Comments' => 'Comentários',
	'UI:Button:Export' => 'Exportar',
	'UI:Relation:PDFExportPageFormat' => 'Formato da página',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Carta',
	'UI:Relation:PDFExportPageOrientation' => 'Orientação da página',
	'UI:PageOrientation_Portrait' => 'Retrato',
	'UI:PageOrientation_Landscape' => 'Paisagem',
	'UI:RelationTooltip:Redundancy' => 'Redundância',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# de itens impactados: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Limite crítico: %1$d / %2$d',
	'Portal:Title' => 'Portal do usuário',
	'Portal:NoRequestMgmt' => 'Caro %1$ s, você foi redirecionado para esta página porque a sua conta é configurado com o perfil de \'usuário do Portal\'. Infelizmente, iTop não foi instalado com a função \'Gerenciamento de Solicitações\'. Por favor, contate o administrador.',
	'Portal:Refresh' => 'Atualizar',
	'Portal:Back' => 'Voltar',
	'Portal:WelcomeUserOrg' => 'Bem-vindo %1$s, de %2$s',
	'Portal:TitleDetailsFor_Request' => 'Detalhes da solicitação',
	'Portal:ShowOngoing' => 'Exibir solicitações abertas',
	'Portal:ShowClosed' => 'Exibir solicitações fechadas',
	'Portal:CreateNewRequest' => 'Criar uma nova Solicitação',
	'Portal:CreateNewRequestItil' => 'Criar uma nova Solicitação',
	'Portal:CreateNewIncidentItil' => 'Criar um novo Relatório de Incidente',
	'Portal:ChangeMyPassword' => 'Alterar minha senha',
	'Portal:Disconnect' => 'Sair',
	'Portal:OpenRequests' => 'Minhas solicitações abertas',
	'Portal:ClosedRequests' => 'Minhas solicitações fechadas',
	'Portal:ResolvedRequests' => 'Minhas solicitações resolvidas',
	'Portal:SelectService' => 'Selecione um serviço do catálogo:',
	'Portal:PleaseSelectOneService' => 'Selecione um serviço',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecione um subserviço do serviço %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Selecione uma subcategoria',
	'Portal:DescriptionOfTheRequest' => 'Digite a descrição da sua solicitação:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detalhes da solicitação %1$s:',
	'Portal:NoOpenRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:NoClosedRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:Button:ReopenTicket' => 'Re-abrir esta solicitação',
	'Portal:Button:CloseTicket' => 'Fechar esta solicitação',
	'Portal:Button:UpdateRequest' => 'Atualizar a solicitação',
	'Portal:EnterYourCommentsOnTicket' => 'Digite seu comentário referente a solução da sua solicitação:',
	'Portal:ErrorNoContactForThisUser' => 'Erro: o usuário atual não esta associado com um contato/pessoa. Por favor, contacte o administrador.',
	'Portal:Attachments' => 'Anexos',
	'Portal:AddAttachment' => ' Adicionar anexo ',
	'Portal:RemoveAttachment' => ' Remover anexo ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Anexo #%1$d para %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Selecione um modelo para %1$s',
	'Enum:Undefined' => '(n/a)',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s dias %2$s horas %3$s minutos %4$s segundos',
	'UI:ModifyAllPageTitle' => 'Modificar todos',
	'UI:Modify_N_ObjectsOf_Class' => 'Editando objeto %1$d da classe %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Editando objeto %1$d da classe %2$s de %3$d',
	'UI:Menu:ModifyAll' => 'Edição em massa...',
	'UI:Button:ModifyAll' => 'Modificar todos',
	'UI:Button:PreviewModifications' => 'Visualizar modificações >>',
	'UI:ModifiedObject' => 'Objeto modificado',
	'UI:BulkModifyStatus' => 'Operação',
	'UI:BulkModifyStatus+' => 'Status da operação',
	'UI:BulkModifyErrors' => 'Erros (se houver)',
	'UI:BulkModifyErrors+' => 'Erros que impedem a modificação',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Erro',
	'UI:BulkModifyStatusModified' => 'Modificado',
	'UI:BulkModifyStatusSkipped' => 'Skipped',
	'UI:BulkModify_Count_DistinctValues' => '%1$d valores distintos:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d tempo(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d mais valores...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Tentativa de definir o campo como somente-leitura: %1$s',
	'UI:FailedToApplyStimuli' => 'A ação falhou',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: modificando %2$d objetos da classe %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Digite seu texto aqui:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Valor inicial:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'O campo %1$s não é editável, porque é originado pela sincronização de dados. Valor não definido',
	'UI:ActionNotAllowed' => 'Você não tem permissão para executar esta ação nesses objetos',
	'UI:BulkAction:NoObjectSelected' => 'Por favor, selecione pelo menos um objeto para realizar esta operação',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'O campo %1$s não é editável, porque é originado pela sincronização de dados. Valor não definido',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objeto(s) (%2$s objeto(s) selecionado(s))',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objeto(s)',
	'UI:Pagination:PageSize' => '%1$s objeto(s) por página',
	'UI:Pagination:PagesLabel' => 'Páginas:',
	'UI:Pagination:All' => 'Tudo',
	'UI:HierarchyOf_Class' => 'Hierarquia de %1$s',
	'UI:Preferences' => 'Preferências...',
	'UI:ArchiveModeOn' => 'Ativar o modo de arquivamento',
	'UI:ArchiveModeOff' => 'Desativar modo de arquivamento',
	'UI:ArchiveMode:Banner' => 'Modo de arquivamento',
	'UI:ArchiveMode:Banner+' => 'Objetos arquivados são visíveis e nenhuma modificação é permitida',
	'UI:FavoriteOrganizations' => 'Organizações favoritas',
	'UI:FavoriteOrganizations+' => 'Confira na lista abaixo as organizações que você deseja ver no menu suspenso para acesso rápido. Note que esta não é uma configuração de segurança, objetos de qualquer organização ainda são visíveis e podem ser acessados ao selecionar "Todas as Organizações" no menu suspenso.',
	'UI:FavoriteLanguage' => 'Idioma do painel do usuário',
	'UI:Favorites:SelectYourLanguage' => 'Selecione seu idioma preferido',
	'UI:FavoriteOtherSettings' => 'Outras configurações',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Quantidade padrão para listas %1$s item(ns) por página',
	'UI:Favorites:ShowObsoleteData' => 'Exibir dados obsoletos',
	'UI:Favorites:ShowObsoleteData+' => 'Exibir dados obsoletos nos resultados de pesquisa e listas de itens para selecionar',
	'UI:NavigateAwayConfirmationMessage' => 'Quaisquer modificações serão descartadas',
	'UI:CancelConfirmationMessage' => 'Você irá perder as suas alterações. Continuar mesmo assim?',
	'UI:AutoApplyConfirmationMessage' => 'Algumas alterações ainda não foram aplicadas. Você quer que o '.ITOP_APPLICATION_SHORT.' os leve em consideração?',
	'UI:Create_Class_InState' => 'Criar o status %1$s: ',
	'UI:OrderByHint_Values' => 'Classificar por: %1$s',
	'UI:Menu:AddToDashboard' => 'Adicionar ao painel...',
	'UI:Button:Refresh' => 'Atualizar',
	'UI:Button:GoPrint' => 'Imprimir ...',
	'UI:ExplainPrintable' => 'Clique no ícone %1$s para ocultar itens da impressão.<br/>Use o recurso de "pré-visualização de impressão" do seu navegador para visualizar antes de imprimir.<br/>Nota: este cabeçalho e outros controles de ajuste não serão impressos',
	'UI:PrintResolution:FullSize' => 'Tamanho total',
	'UI:PrintResolution:A4Portrait' => 'Retrato A4',
	'UI:PrintResolution:A4Landscape' => 'Paisagem A4',
	'UI:PrintResolution:LetterPortrait' => 'Carta Retrato',
	'UI:PrintResolution:LetterLandscape' => 'Carta Retrato',
	'UI:Toggle:SwitchToStandardDashboard' => 'Alternar para o painel padrão',
	'UI:Toggle:SwitchToCustomDashboard' => 'Alternar para o painel personalizado',

	'UI:ConfigureThisList' => 'Configurar esta lista...',
	'UI:ListConfigurationTitle' => 'Configurações de exibição',
	'UI:ColumnsAndSortOrder' => 'Colunas e ordem de classificação:',
	'UI:UseDefaultSettings' => 'Usar a configuração padrão',
	'UI:UseSpecificSettings' => 'Usar as seguintes configurações:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Exibir',
	'UI:Display_X_ItemsPerPage_suffix' => 'itens por página',
	'UI:UseSavetheSettings' => 'Salvar configurações:',
	'UI:OnlyForThisList' => 'Somente para esta lista',
	'UI:ForAllLists' => 'Para todas as listas',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (nome amigável)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Mover acima',
	'UI:Button:MoveDown' => 'Mover abaixo',

	'UI:OQL:UnknownClassAndFix' => 'Classe desconhecida "%1$s". Ao invés disso, você pode tentar a classe "%2$s"',
	'UI:OQL:UnknownClassNoFix' => 'Classe desconhecida: "%1$s"',

	'UI:Dashboard:EditCustom' => 'Editar visão personalizada...',
	'UI:Dashboard:CreateCustom' => 'Criar uma visão personalizada...',
	'UI:Dashboard:DeleteCustom' => 'Excluir visão personalizada...',
	'UI:Dashboard:RevertConfirm' => 'As alterações realizadas na visão original serão perdidas. Por favor, confirme que você quer fazer isso',
	'UI:ExportDashBoard' => 'Exportar visão para um arquivo',
	'UI:ImportDashBoard' => 'Importar visão de um arquivo...',
	'UI:ImportDashboardTitle' => 'Importar visão de um arquivo',
	'UI:ImportDashboardText' => 'Selecione um arquivo do Painel para importar:',
	'UI:Dashboard:Actions' => 'Ações do Painel',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'Este painel exibe informações que não incluem as alterações em andamento',


	'UI:DashletCreation:Title' => 'Criar um novo Painel',
	'UI:DashletCreation:Dashboard' => 'Painel',
	'UI:DashletCreation:DashletType' => 'Tipo de painel',
	'UI:DashletCreation:EditNow' => 'Editar o painel',

	'UI:DashboardEdit:Title' => 'Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Título',
	'UI:DashboardEdit:AutoReload' => 'Atualizar automaticamente',
	'UI:DashboardEdit:AutoReloadSec' => 'Intervalo de atualização automática (segundos)',
	'UI:DashboardEdit:AutoReloadSec+' => 'O intervalo mínimo permitido é %1$d segundos',
	'UI:DashboardEdit:Revert' => 'Reverter',
	'UI:DashboardEdit:Apply' => 'Salvar',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Propriedades',
	'UI:DashboardEdit:Dashlets' => 'Painel disponível',
	'UI:DashboardEdit:DashletProperties' => 'Propriedades',

	'UI:Form:Property' => 'Propriedade',
	'UI:Form:Value' => 'Valor',

	'UI:DashletUnknown:Label' => 'Desconhecido',
	'UI:DashletUnknown:Description' => 'Dashlet desconhecido (pode ter sido desinstalado)',
	'UI:DashletUnknown:RenderText:View' => 'Não é possível renderizar este dashlet',
	'UI:DashletUnknown:RenderText:Edit' => 'Não é possível renderizar este dashlet (classe "%1$s"). Verifique com seu administrador se este dashlet ainda está disponível',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Não há visualização disponível para este dashlet (classe "%1$s")',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuração (mostrada como XML bruta)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Nenhuma pré-visualização disponível para este dashlet de terceiros (classe "%1$s")',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuração (mostrada como XML bruta)',

	'UI:DashletPlainText:Label' => 'Texto',
	'UI:DashletPlainText:Description' => 'Texto puro (não formatado)',
	'UI:DashletPlainText:Prop-Text' => 'Texto',
	'UI:DashletPlainText:Prop-Text:Default' => 'Por favor, insira algum texto aqui...',

	'UI:DashletObjectList:Label' => 'Lista de objetos',
	'UI:DashletObjectList:Description' => 'Lista objeto no painel',
	'UI:DashletObjectList:Prop-Title' => 'Título',
	'UI:DashletObjectList:Prop-Query' => 'Questão',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Título',
	'UI:DashletGroupBy:Prop-Query' => 'Questão',
	'UI:DashletGroupBy:Prop-Style' => 'Estilo',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Agrupar por...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hora de %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Mês de %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Dia da semana para %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Dia do mês para %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hora)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (mês)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (dia da semana)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (dia do mês)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Por favor, selecione o campo no qual os objetos serão agrupados',

	'UI:DashletGroupByPie:Label' => 'Gráfico de Pizza',
	'UI:DashletGroupByPie:Description' => 'Gráfico de Pizza',
	'UI:DashletGroupByBars:Label' => 'Gráfico de Barras',
	'UI:DashletGroupByBars:Description' => 'Gráfico de Barras',
	'UI:DashletGroupByTable:Label' => 'Grupo por (tabela)',
	'UI:DashletGroupByTable:Description' => 'Exibir (Agrupado por um campo)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Função de agregação',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Atributo de função',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direção',
	'UI:DashletGroupBy:Prop-OrderField' => 'Ordenar por',
	'UI:DashletGroupBy:Prop-Limit' => 'Limite',

	'UI:DashletGroupBy:Order:asc' => 'Ascendente',
	'UI:DashletGroupBy:Order:desc' => 'Descendente',

	'UI:GroupBy:count' => 'Total',
	'UI:GroupBy:count+' => 'Número de elementos',
	'UI:GroupBy:sum' => 'Soma',
	'UI:GroupBy:sum+' => 'Soma de %1$s',
	'UI:GroupBy:avg' => 'Média',
	'UI:GroupBy:avg+' => 'Média de %1$s',
	'UI:GroupBy:min' => 'Mínimo',
	'UI:GroupBy:min+' => 'Mínimo de %1$s',
	'UI:GroupBy:max' => 'Máximo',
	'UI:GroupBy:max+' => 'Máximo de %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Cabeçalho',
	'UI:DashletHeaderStatic:Description' => 'Exibe um separador horizontal',
	'UI:DashletHeaderStatic:Prop-Title' => 'Título',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contatos',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Ícone',

	'UI:DashletHeaderDynamic:Label' => 'Cabeçalho com estatísticas',
	'UI:DashletHeaderDynamic:Description' => 'Cabeçalho com estatística (agrupado por...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Título',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contatos',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Ícone',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtítulo',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contatos',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Consulta',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Grupo por',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Valores',

	'UI:DashletBadge:Label' => 'Ícone',
	'UI:DashletBadge:Description' => 'Ícone representando uma classe de objetos, bem como links para criar/pesquisar',
	'UI:DashletBadge:Prop-Class' => 'Classe',

	'DayOfWeek-Sunday' => 'Domingo',
	'DayOfWeek-Monday' => 'Segunda',
	'DayOfWeek-Tuesday' => 'Terça',
	'DayOfWeek-Wednesday' => 'Quarta',
	'DayOfWeek-Thursday' => 'Quinta',
	'DayOfWeek-Friday' => 'Sexta',
	'DayOfWeek-Saturday' => 'Sábado',
	'Month-01' => 'Janeiro',
	'Month-02' => 'Fevereiro',
	'Month-03' => 'Março',
	'Month-04' => 'Abril',
	'Month-05' => 'Maio',
	'Month-06' => 'Junho',
	'Month-07' => 'Julho',
	'Month-08' => 'Agosto',
	'Month-09' => 'Setembro',
	'Month-10' => 'Outubro',
	'Month-11' => 'Novembro',
	'Month-12' => 'Dezembro',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'D',
	'DayOfWeek-Monday-Min' => 'S',
	'DayOfWeek-Tuesday-Min' => 'T',
	'DayOfWeek-Wednesday-Min' => 'Q',
	'DayOfWeek-Thursday-Min' => 'Q',
	'DayOfWeek-Friday-Min' => 'S',
	'DayOfWeek-Saturday-Min' => 'S',
	'Month-01-Short' => 'Jan',
	'Month-02-Short' => 'Fev',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Abr',
	'Month-05-Short' => 'Mai',
	'Month-06-Short' => 'Jun',
	'Month-07-Short' => 'Jul',
	'Month-08-Short' => 'Ago',
	'Month-09-Short' => 'Set',
	'Month-10-Short' => 'Out',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Dez',
	'Calendar-FirstDayOfWeek' => '0',// 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Criar um atalho...',
	'UI:ShortcutRenameDlg:Title' => 'Renomear o atalho',
	'UI:ShortcutListDlg:Title' => 'Criar um atalho para a lista',
	'UI:ShortcutDelete:Confirm' => 'Por favor, confirme que você deseja excluir o(s) atalho(s)',
	'Menu:MyShortcuts' => 'Meus atalhos',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Atalhos',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Nome',
	'Class:Shortcut/Attribute:name+' => 'Nome exibido no menu e título da página',
	'Class:ShortcutOQL' => 'Atalho para resultados da pesquisa',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Consulta',
	'Class:ShortcutOQL/Attribute:oql+' => 'Definição OQL da lista de objetos para procurar',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Atualizar automaticamente',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Desabilitado',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Avaliar',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Intervalo atualização automática (segundos)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'O mínimo permitido é %1$d sgundos',

	'UI:FillAllMandatoryFields' => 'Por favor, preencha todos os campos obrigatórios',
	'UI:ValueMustBeSet' => 'Por favor, especifique um valor',
	'UI:ValueMustBeChanged' => 'Por favor, altere o valor',
	'UI:ValueInvalidFormat' => 'Formato inválido',

	'UI:CSVImportConfirmTitle' => 'Por favor, confirme a operação',
	'UI:CSVImportConfirmMessage' => 'Tem certeza que deseja confirmar?',
	'UI:CSVImportError_items' => 'Erros: %1$d',
	'UI:CSVImportCreated_items' => 'Criado: %1$d',
	'UI:CSVImportModified_items' => 'Alterado: %1$d',
	'UI:CSVImportUnchanged_items' => 'Não alterado: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Formato de data e hora',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Formato padrão: %1$s (por exemplo, %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Formato personalizado: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Placeholders disponíveis:<table>
<tr><td>Y</td><td>ano (4 dígitos, ex. 2016)</td></tr>
<tr><td>y</td><td>ano (2 dígitos, ex. 16 para 2016)</td></tr>
<tr><td>m</td><td>mês (2 dígitos, ex. 01..12)</td></tr>
<tr><td>n</td><td>mês (1 ou 2 dígitos sem zeros a esquerda, ex. 1..12)</td></tr>
<tr><td>d</td><td>dia (2 dígitos, ex. 01..31)</td></tr>
<tr><td>j</td><td>dia (1 ou 2 dígitos sem zeros a esquerda, ex. 1..31)</td></tr>
<tr><td>H</td><td>hora (24 hour, 2 dígitos, ex. 00..23)</td></tr>
<tr><td>h</td><td>hora (12 hour, 2 dígitos, ex. 01..12)</td></tr>
<tr><td>G</td><td>hora (24 hour, 1 ou 2 dígitos sem zeros a esquerda, ex. 0..23)</td></tr>
<tr><td>g</td><td>hora (12 hour, 1 ou 2 dígitos sem zeros a esquerda, ex. 1..12)</td></tr>
<tr><td>a</td><td>hora, am ou pm (minúsculo)</td></tr>
<tr><td>A</td><td>hora, AM ou PM (maiúsculo)</td></tr>
<tr><td>i</td><td>minutos (2 dígitos, ex. 00..59)</td></tr>
<tr><td>s</td><td>segundos (2 dígitos, ex. 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Excluir',
	'UI:AddAnExisting_Class' => 'Associar objetos do tipo %1$s...',
	'UI:SelectionOf_Class' => 'Selecionar objetos do tipo %1$s',

	'UI:AboutBox' => 'Sobre o '.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'Sobre o '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Modelo de dados',
	'UI:About:Support' => 'Informações de suporte',
	'UI:About:Licenses' => 'Licenças',
	'UI:About:InstallationOptions' => 'Opções de instalação',
	'UI:About:ManualExtensionSource' => 'Extensão',
	'UI:About:Extension_Version' => 'Versão: %1$s',
	'UI:About:RemoteExtensionSource' => 'iTop Hub',

	'UI:DisconnectedDlgMessage' => 'Você foi desconectado. Você deve se identificar novamente para continuar usando o aplicativo.',
	'UI:DisconnectedDlgTitle' => 'Atenção!',
	'UI:LoginAgain' => 'Entrar novamente',
	'UI:StayOnThePage' => 'Permanecer nessa página',

	'ExcelExporter:ExportMenu' => 'Exportar para Excel...',
	'ExcelExporter:ExportDialogTitle' => 'Exportar para Excel',
	'ExcelExporter:ExportButton' => 'Exportar',
	'ExcelExporter:DownloadButton' => 'Baixar %1$s',
	'ExcelExporter:RetrievingData' => 'Recuperando dados...',
	'ExcelExporter:BuildingExcelFile' => 'Construindo o arquivo do Excel...',
	'ExcelExporter:Done' => 'Feito.',
	'ExcelExport:AutoDownload' => 'Inicie o download automaticamente quando a exportação estiver pronta',
	'ExcelExport:PreparingExport' => 'Preparando a exportação...',
	'ExcelExport:Statistics' => 'Estatísticas',
	'portal:legacy_portal' => 'Portal do usuário (legado) do '.ITOP_APPLICATION_SHORT,
	'portal:backoffice' => 'Interface de usuário back-office do '.ITOP_APPLICATION_SHORT,

	'UI:CurrentObjectIsLockedBy_User' => 'O objeto está bloqueado, pois está sendo modificado por %1$s',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'O objeto está sendo modificado por %1$s. Suas modificações não podem ser enviadas, pois seriam sobrescritas',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'O objeto está sendo modificado por %1$s. Você será capaz de enviar suas modificações quando terminarem',
	'UI:CurrentObjectLockExpired' => 'O bloqueio para impedir modificações simultâneas do objeto expirou',
	'UI:CurrentObjectLockExpired_Explanation' => 'O bloqueio para impedir modificações simultâneas do objeto expirou. Você não pode mais enviar sua modificação, pois outros usuários agora podem modificar este objeto',
	'UI:ConcurrentLockKilled' => 'O bloqueio impedindo modificações no objeto atual foi removido',
	'UI:Menu:KillConcurrentLock' => 'Matar o bloqueio de modificação simultânea!',

	'UI:Menu:ExportPDF' => 'Exportar como PDF...',
	'UI:Menu:PrintableVersion' => 'Versão para impressão',

	'UI:BrowseInlineImages' => 'Navegue pelas imagens...',
	'UI:UploadInlineImageLegend' => 'Carregar uma nova imagem',
	'UI:SelectInlineImageToUpload' => 'Selecione a imagem para enviar',
	'UI:AvailableInlineImagesLegend' => 'Imagens disponíveis',
	'UI:NoInlineImage' => 'Não há imagem disponível no servidor. Use o botão "Escolher arquivo" acima para selecionar uma imagem do seu computador e fazer o upload para o servidor',

	'UI:ToggleFullScreen' => 'Alternancia Maximizar / Minimizar',
	'UI:Button:ResetImage' => 'Recupere a imagem anterior',
	'UI:Button:RemoveImage' => 'Remover a imagem',
	'UI:Button:UploadImage' => 'Carregar uma imagem do disco',
	'UI:UploadNotSupportedInThisMode' => 'A modificação de imagens ou arquivos não é suportada neste modo',

	'UI:Button:RemoveDocument' => 'Remover o documento',

	// Search form
	'UI:Search:Toggle' => 'Minimizar / Expandir',
	'UI:Search:AutoSubmit:DisabledHint' => 'O envio automático foi desativado para esta classe',
	'UI:Search:Obsolescence:DisabledHint' => 'Baseado nas suas preferências de usuário, dados obsoletos não são exibidos',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Adicione algum critério na caixa de pesquisa ou clique no botão de pesquisa para visualizar os objetos',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Adicionar novos critérios',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Usado recentemente',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Mais popular',
	'UI:Search:AddCriteria:List:Others:Title' => 'Outros',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Nenhum ainda',

	// - Criteria header actions
	'UI:Search:Criteria:Toggle' => 'Minimizar / Expandir',
	'UI:Search:Criteria:Remove' => 'Remover',
	'UI:Search:Criteria:Locked' => 'Bloqueado',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: qualquer',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s está vazio',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s não está vazio',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s é igual a %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contém %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s inicia com %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s termina com %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s corresponde a %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s entre [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: qualquer',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s de %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s até %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: qualquer',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s de %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s até %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s e %3$s outros',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: qualquer',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s está definido',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s não está definido',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s = %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s e %3$s outros',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: qualquer',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s está definido',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s não está definido',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s = %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s e %3$s outros',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: qualquer',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Está vazio',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Não está vazio',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Igual',
	'UI:Search:Criteria:Operator:Default:Between' => 'Entre',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contém',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Inicia com',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Termina com',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Exp. Regular ',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Igual',// => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Maior',// => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Maior',// > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Menor',// => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Menor / igual a',// > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Diferente de',// => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Correspondências',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filtrar...',
	'UI:Search:Value:Search:Placeholder' => 'Pesquisar...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Comece a digitar os valores possíveis',
	'UI:Search:Value:Autocomplete:Wait' => 'Aguarde...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Sem resultados',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Marcar todos / nenhum',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Marcar todos / Nenhum visíveis',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'De',
	'UI:Search:Criteria:Numeric:Until' => 'Para',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Qualquer',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Qualquer',
	'UI:Search:Criteria:DateTime:From' => 'De',
	'UI:Search:Criteria:DateTime:FromTime' => 'De',
	'UI:Search:Criteria:DateTime:Until' => 'até',
	'UI:Search:Criteria:DateTime:UntilTime' => 'até',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Qualquer data',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Qualquer data',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Qualquer data',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Qualquer data',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Os objetos filhos dos objetos selecionados serão incluídos',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtrado',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtrado em %1$s',

	'UI:StateChanged' => 'Status alterado',
));

//
// Expression to Natural language
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Expression:Operator:AND' => ' E ',
	'Expression:Operator:OR' => ' OU ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',

	'Expression:Unit:Long:DAY' => 'dia(s)',
	'Expression:Unit:Long:HOUR' => 'hora(s)',
	'Expression:Unit:Long:MINUTE' => 'minuto(s)',

	'Expression:Verb:NOW' => 'agora',
	'Expression:Verb:ISNULL' => ': indefinido',
));

//
// iTop Newsroom menu
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'UI:Newsroom:NoNewMessage' => 'Nenhuma mensagem nova',
	'UI:Newsroom:XNewMessage' => '%1$s nova(s) mensagem(ns)',
	'UI:Newsroom:MarkAllAsRead' => 'Marcar todas as mensagens como lidas',
	'UI:Newsroom:ViewAllMessages' => 'Ver todas as mensagens',
	'UI:Newsroom:Preferences' => 'Preferências da sala de notícias',
	'UI:Newsroom:ConfigurationLink' => 'Configuração',
	'UI:Newsroom:ResetCache' => 'Redefinir cache',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Exibir mensagens do(a) %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Exibir até %1$s mensagem(ns) no menu %2$s',
));


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:DataSources' => 'Fontes de Sincronização de Dados',
	'Menu:DataSources+' => 'Lista de Fontes de Sincronização de Dados',
	'Menu:WelcomeMenu' => 'Página inicial do '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenu+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Página inicial do '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Ferramentas Administrativas',
	'Menu:AdminTools+' => 'Ferramentas Administrativas',
	'Menu:AdminTools?' => 'Ferramentas acessíveis apenas para usuários com perfil de administrador',
	'Menu:DataModelMenu' => 'Modelo de Dados',
	'Menu:DataModelMenu+' => 'Visão geral do Modelo de Dados',
	'Menu:ExportMenu' => 'Exportar Consulta',
	'Menu:ExportMenu+' => 'Exportar o resultado de qualquer consulta em HTML, CSV ou XML',
	'Menu:NotificationsMenu' => 'Notificações',
	'Menu:NotificationsMenu+' => 'Configuração de Notificações',
	'Menu:AuditCategories' => 'Categorias de Auditoria',
	'Menu:AuditCategories+' => 'Lista de Categorias de Auditoria',
	'Menu:Notifications:Title' => 'Categorias de Auditoria',
	'Menu:RunQueriesMenu' => 'Executar Consultas',
	'Menu:RunQueriesMenu+' => 'Executar qualquer consulta',
	'Menu:QueryMenu' => 'Livro de Consultas',
	'Menu:QueryMenu+' => 'Lista de Livro de Consultas',
	'Menu:UniversalSearchMenu' => 'Pesquisa Universal',
	'Menu:UniversalSearchMenu+' => 'Pesquisar por todo o aplicativo...',
	'Menu:UserManagementMenu' => 'Gerenciamento de Usuários',
	'Menu:UserManagementMenu+' => '',
	'Menu:ProfilesMenu' => 'Perfis de Usuário',
	'Menu:ProfilesMenu+' => 'Lista de Perfis de Usuário',
	'Menu:ProfilesMenu:Title' => 'Perfis de Usuário',
	'Menu:UserAccountsMenu' => 'Contas de Usuários',
	'Menu:UserAccountsMenu+' => 'Lista de Contas de Usuário',
	'Menu:UserAccountsMenu:Title' => 'Contas de Usuários',
	'Menu:MyShortcuts' => 'Meus atalhos',
	'Menu:UserManagement' => 'Gerenciamento de Usuários',
	'Menu:Queries' => 'Consultas',
	'Menu:ConfigurationTools' => 'Configurações',
));

// Additional language entries not present in English dict
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'UI:Toggle:StandardDashboard' => 'Padrão',
	'UI:Toggle:CustomDashboard'   => 'Customizado',
	'UI:Dashboard:Edit'           => 'Editar esta página...',
	'UI:Dashboard:Revert'         => 'Reverter para versão original...',
));

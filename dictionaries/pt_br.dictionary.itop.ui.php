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
	'Class:AuditCategory' => 'Categoria Auditoria',
	'Class:AuditCategory+' => 'Uma seção dentro da auditoria',
	'Class:AuditCategory/Attribute:name' => 'Nome',
	'Class:AuditCategory/Attribute:name+' => 'Nome curto para esta categoria',
	'Class:AuditCategory/Attribute:description' => 'Descrição',
	'Class:AuditCategory/Attribute:description+' => 'Longa descrição para esta categoria de auditoria',
	'Class:AuditCategory/Attribute:definition_set' => 'Definir Regra',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expressão OQL que define o conjunto de objetos para auditoria',
	'Class:AuditCategory/Attribute:rules_list' => 'Regras Auditoria',
	'Class:AuditCategory/Attribute:rules_list+' => 'Regra auditoria para essa categoria',
));

//
// Class: AuditRule
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AuditRule' => 'Regra de auditoria',
	'Class:AuditRule+' => 'Uma regra para verificar se uma determinada categoria de Auditoria',
	'Class:AuditRule/Attribute:name' => 'Nome',
	'Class:AuditRule/Attribute:name+' => 'Nome curto para esta regra',
	'Class:AuditRule/Attribute:description' => 'Descrição',
	'Class:AuditRule/Attribute:description+' => 'Descrição longa para essa regra',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe de objeto',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Código de campo',
	'Class:AuditRule/Attribute:query' => 'Executar consulta',
	'Class:AuditRule/Attribute:query+' => 'Executar a expressão OQL',
	'Class:AuditRule/Attribute:valid_flag' => 'Objetos válidos?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Verdadeiro se a regra retornar o objeto válido, falso caso contrário',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Verdadeiro',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Verdadeiro',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Falso',
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
	'Class:Query/Attribute:name+' => 'Identificar a consulta',
	'Class:Query/Attribute:description' => 'Descrição',
	'Class:Query/Attribute:description+' => 'Descrição longa para a consulta (finalidade, uso, etc)',
	'Class:Query/Attribute:is_template' => 'Template for OQL fields~~',
	'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications~~',
	'Class:Query/Attribute:is_template/Value:yes' => 'Yes~~',
	'Class:Query/Attribute:is_template/Value:no' => 'No~~',
	'Class:QueryOQL/Attribute:fields' => 'Campos',
	'Class:QueryOQL/Attribute:fields+' => 'Vírgula separando a lista de atributos (ou alias.attribute) para exportar.',
	'Class:QueryOQL' => 'Consulta OQL',
	'Class:QueryOQL+' => 'Uma consulta baseada no Object Query Language OQL',
	'Class:QueryOQL/Attribute:oql' => 'Expressão',
	'Class:QueryOQL/Attribute:oql+' => 'Expressão OQL',
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
	'Class:User+' => 'Login',
	'Class:User/Attribute:finalclass' => 'Tipo de conta',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Contato (pessoa)',
	'Class:User/Attribute:contactid+' => 'Dados pessoais a partir dos dados do negócio',
	'Class:User/Attribute:org_id' => 'Organização',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => 'Último nome',
	'Class:User/Attribute:last_name+' => 'Nome do contato correspondente',
	'Class:User/Attribute:first_name' => 'Primeiro nome',
	'Class:User/Attribute:first_name+' => 'Primeiro nome do contato correspondente',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email do contato correspondente',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'String de identificação do usuário',
	'Class:User/Attribute:language' => 'Linguagem',
	'Class:User/Attribute:language+' => 'Linguagem usuário',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Perfil',
	'Class:User/Attribute:profile_list+' => 'Regras, permissões de direito para essa pessoa',
	'Class:User/Attribute:allowed_org_list' => 'Organizações permitidas',
	'Class:User/Attribute:allowed_org_list+' => 'O usuário está permitido ver as informações para a(s) organização(ões) abaixo. Se nenhuma organização for especificada, não há restrição.',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'Se a conta de usuário está habilitada ou desabilitada.',
	'Class:User/Attribute:status/Value:enabled' => 'Ativado',
	'Class:User/Attribute:status/Value:disabled' => 'Desativado',

	'Class:User/Error:LoginMustBeUnique' => 'Login é único - "%1s" já está ativo',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Pelo menos um perfil deve ser atribuído a esse usuário.',
	'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice~~',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User~~',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization~~',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)~~',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Pelo menos uma organização deve ser atribuída a este usuário.',
	'Class:User/Error:OrganizationNotAllowed' => 'Organização não permitida.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'A conta de usuário não pertence às suas organizações permitidas.',
	'Class:User/Error:PersonIsMandatory' => 'O contato é obrigatório.',
	'Class:UserInternal' => 'Usuário Interno',
	'Class:UserInternal+' => 'Usuário definido dentro do '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Profiles' => 'Perfis',
	'Class:URP_Profiles+' => 'Perfil do usuário',
	'Class:URP_Profiles/Attribute:name' => 'Nome',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Descrição',
	'Class:URP_Profiles/Attribute:description+' => 'uma linha descrição',
	'Class:URP_Profiles/Attribute:user_list' => 'Usuários',
	'Class:URP_Profiles/Attribute:user_list+' => 'Pessoas que possuem esse perfil',
));

//
// Class: URP_Dimensions
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Dimensions' => 'dimensão',
	'Class:URP_Dimensions+' => 'dimensão de aplicação (definição silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nome',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Descrição',
	'Class:URP_Dimensions/Attribute:description+' => 'uma linha descrição',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'nome classe ou tipo dado (unidade projeção)',
));

//
// Class: URP_UserProfile
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserProfile' => 'Usuário para perfil',
	'Class:URP_UserProfile+' => 'Perfil usuário',
	'Class:URP_UserProfile/Name' => 'Link entre %1$s e %2$s',
	'Class:URP_UserProfile/Attribute:userid' => 'Usuário',
	'Class:URP_UserProfile/Attribute:userid+' => 'Conta usuário',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_UserProfile/Attribute:profile' => 'Perfil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nome perfil',
	'Class:URP_UserProfile/Attribute:reason' => 'Função',
	'Class:URP_UserProfile/Attribute:reason+' => 'Explicação por que esta pessoa teve ter esse perfil',
));

//
// Class: URP_UserOrg
//


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserOrg' => 'Organização usuário',
	'Class:URP_UserOrg+' => 'Organizações permitidas',
	'Class:URP_UserOrg/Name' => 'Link entre %1$s e %2$s',
	'Class:URP_UserOrg/Attribute:userid' => 'Usário',
	'Class:URP_UserOrg/Attribute:userid+' => 'Conta usuário',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:reason' => 'Função',
	'Class:URP_UserOrg/Attribute:reason+' => 'explicação por que essa pessoa é permitida ver as informações da organização abaixo',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensão',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Dimensão aplicação',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensão',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Dimensão aplicação',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Perfil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nome perfil',
	'Class:URP_ProfileProjection/Attribute:value' => 'Valor de expressão',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Expressão OQL (usando $ user) | constante | | + código de atributo',
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
	'Class:URP_ClassProjection/Attribute:class+' => 'Classe alvoTarget class',
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
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'Sim',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Não',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'Não',
	'Class:URP_ActionGrant/Attribute:action' => 'Ação',
	'Class:URP_ActionGrant/Attribute:action+' => 'operações a realizar em determinada classe',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissões de Incentivo do ciclo de vida do objeto',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Perfil utilizado',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Perfil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Perfil utilizado',
	'Class:URP_StimulusGrant/Attribute:class' => 'Classe',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Classe alvo',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permissão',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Permitido ou não permitido?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Sim',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'Sim',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Não',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'Não',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Incentivo',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Código incentivo',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'Permissões no nível de atributos',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Ação permissão',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'Ação permissão',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Atributo',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Código atributo',
));

//
// Class: UserDashboard
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:UserDashboard' => 'Painel do usuário',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Usuário',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Código de menu',
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
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'Menu:WelcomeMenu' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop é um completo, OpenSource, portal de operação IT.</p>
<ul>Inclui:
<li>completo CMDB (Configuration management database) documentar e gerenciar inventários IT</li>
<li>módulo Gerenciador Incidentes para monitorar e comunicar sobre todas as questões que ocorrem na TI</li>
<li>módulo Gerenciador Mudanças/Alterações para planejar e monitorar mudanças/alterações na TI</li>
<li>base de dados com erros conhecidos para agilizar a solução de incidentes</li>
<li>módulo Problemas para documentar todas as interrupções e notificar os contatos adequados</li>
<li>painéis para obter rapidamente uma visão geral de TI</li>
</ul>
<p>Todos os módulos podem ser configurados, passo a passo, cada um, independente dos outros.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>Um provedor de serviço, que permite gerenciar facilmente múltiplos clientes ou organizações.
<ul>Oferece um conjunto rico em recursos de processos de negócios que:
<li>melhora a eficácia da gestão de TI</li> 
<li>melhora operações de TI</li> 
<li>melhora a satisfação do cliente e fornece aos gestores uma visão sobre o desempenho dos negócios.</li>
</ul>
</p>
<p>É completamente aberto para ser integrado com as infra-estruturas de IT atuais.</p>
<p>
<ul>Este portal em gestão de TI vai ajudar você a:
<li>gerenciar melhor o complexo ambiente de TI</li>
<li>implementar processos ITIL no seu próprio rítmo</li>
<li>gerenciar o ativo mais importante de sua TI: Documentação</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>This version features a brand new modern and accessible backoffice design.</div>

<div>We kept '.ITOP_APPLICATION.' core functions that you liked and modernized them to make you love them.
We hope you’ll enjoy this version as much as we enjoyed imagining and creating it.</div>

<div>Customize your '.ITOP_APPLICATION.' preferences for a personalized experience.</div>~~',
	'UI:WelcomeMenu:AllOpenRequests' => 'Solicitações abertas: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Minhas solicitações',
	'UI:WelcomeMenu:OpenIncidents' => 'Incidentes abertos: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Itens de Configuração: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidentes atribuídos a mim',
	'UI:AllOrganizations' => ' Todas organizações ',
	'UI:YourSearch' => 'Sua pesquisa',
	'UI:LoggedAsMessage' => 'Logado como %1$s (%2$s)~~',
	'UI:LoggedAsMessage+Admin' => 'Logado como %1$s (%2$s, Administrador)~~',
	'UI:Button:Logoff' => 'Sair',
	'UI:Button:GlobalSearch' => 'Pesquisar',
	'UI:Button:Search' => ' Pesquisar ',
	'UI:Button:Clear' => ' Clear ~~',
	'UI:Button:SearchInHierarchy' => 'Search in hierarchy~~',
	'UI:Button:Query' => ' Consultar ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Salvar',
	'UI:Button:SaveAnd' => 'Save and %1$s~~',
	'UI:Button:Cancel' => 'Cancelar',
	'UI:Button:Close' => 'Fechar',
	'UI:Button:Apply' => 'Aplicar',
	'UI:Button:Send' => 'Send~~',
	'UI:Button:SendAnd' => 'Send and %1$s~~',
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
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',
	'UI:UserPref:DoNotShowAgain' => 'Do not show again~~',
	'UI:InputFile:NoFileSelected' => 'No File Selected~~',
	'UI:InputFile:SelectFile' => 'Select a file~~',

	'UI:SearchToggle' => 'Pesquisar',
	'UI:ClickToCreateNew' => 'Criar um(a) %1$s',
	'UI:SearchFor_Class' => 'Pesquisar por objeto(s) %1$s',
	'UI:NoObjectToDisplay' => 'Nenhum objeto encontrado.',
	'UI:Error:SaveFailed' => 'O objeto não pode ser salvo:',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parâmetro Object_id é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parâmetro Target_attr é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parâmetro Group_by é obrigatório. Verifique a definição do modelo de exibição.',
	'UI:Error:InvalidGroupByFields' => 'Lista inválida dos campos para agrupar por: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erro: o estilo não suportada do bloco: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Definição de ligação incorreta: a classe de objetos para gerenciar: %1$s não foi encontrado como uma chave externa na classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objeto: %1$s:%2$d não encontrado.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Erro: Referência circular nas dependências entre os campos, verifique o modelo de dados.',
	'UI:Error:UploadedFileTooBig' => 'O arquivo a ser carregado é muito grande. (Tamanho máximo permitido é de %1$s). Para modificar esse limite, contate o administrador do '.ITOP_APPLICATION_SHORT.'. (Verifique a configuração do PHP para upload_max_filesize e post_max_size no servidor).',
	'UI:Error:UploadedFileTruncated.' => 'Arquivo enviado tem sido truncado!',
	'UI:Error:NoTmpDir' => 'Diretório temporário não está definido.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Não foi possível gravar o arquivo temporário para o disco. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload parou por extensão. (Nome do arquivo original = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Arquivo carregado falhou, causa desconhecida. (Código erro = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Erro: o parâmetro a seguir deve ser especificado para esta operação: %1$s.',
	'UI:Error:2ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s e %2$s.',
	'UI:Error:3ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s, %2$s e %3$s.',
	'UI:Error:4ParametersMissing' => 'Erro: os seguintes parâmetros devem ser especificados para esta operação: %1$s, %2$s, %3$s e %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Erro: incorreta consulta OQL: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Ocorreu um erro ao executar a consulta: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Erro: o objeto já foi atualizado.',
	'UI:Error:ObjectCannotBeUpdated' => 'Erro: objecto não pode ser atualizado.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Erro: objetos já foram apagados',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Você não tem permissão de executar exclusão em massa dos objetos da classe %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Você não tem permissão para excluir objeto(s) da classe %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'You are not allowed to view objects of class %1$s~~',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Você não tem permissão de executar atualização em massa dos objetos da classe %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Erro: o objeto já foi clonado.',
	'UI:Error:ObjectAlreadyCreated' => 'Erro: o objeto já foi criado.',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Erro: invalid stimulus "%1$s" on object %2$s in state "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Erro: arquivo de painel inválido',
	'UI:Error:InvalidDashboard' => 'Erro: painel inválido',
	'UI:Error:MaintenanceMode' => 'A aplicação está em manutenção',
	'UI:Error:MaintenanceTitle' => 'Manutenção',
	'UI:Error:InvalidToken' => 'Error: the requested operation has already been performed (CSRF token not found)~~',

	'UI:GroupBy:Count' => 'Número',
	'UI:GroupBy:Count+' => 'Número de elementos',
	'UI:CountOfObjects' => '%1$d objetos correspondem aos critérios.',
	'UI_CountOfObjectsShort' => '%1$d objetos.',
	'UI:NoObject_Class_ToDisplay' => 'Nenhum %1$s para mostrar',
	'UI:History:LastModified_On_By' => 'Última modificação em %1$s por %2$s.',
	'UI:HistoryTab' => 'Histórico',
	'UI:NotificationsTab' => 'Notificação',
	'UI:History:BulkImports' => 'Histórico',
	'UI:History:BulkImports+' => 'Lista de importação CSV',
	'UI:History:BulkImportDetails' => 'Alterações resultantes da importação CSV realizado em %1$s (por %2$s)',
	'UI:History:Date' => 'Data',
	'UI:History:Date+' => 'Data da alteração',
	'UI:History:User' => 'Usuário',
	'UI:History:User+' => 'Usuário que fez a alteração',
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
	'UI:Menu:Transitions' => 'Transitions~~',
	'UI:Menu:OtherTransitions' => 'Other Transitions~~',
	'UI:Menu:New' => 'Novo...',
	'UI:Menu:Add' => 'Adicionar...',
	'UI:Menu:Manage' => 'Gerenciar...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'Exportar CSV...',
	'UI:Menu:Modify' => 'Modificar...',
	'UI:Menu:Delete' => 'Excluir...',
	'UI:Menu:BulkDelete' => 'Excluir...',
	'UI:UndefinedObject' => 'indefinido',
	'UI:Document:OpenInNewWindow:Download' => 'Abrir em uma nova janela: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'data',
	'UI:SplitDateTime-Time' => 'hora',
	'UI:TruncatedResults' => '%1$d objetos apresentado fora do %2$d',
	'UI:DisplayAll' => 'Mostrar todos',
	'UI:CollapseList' => 'Colapso',
	'UI:CountOfResults' => '%1$d objeto(s)',
	'UI:ChangesLogTitle' => 'Alteração log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Alteração log está limpo',
	'UI:SearchFor_Class_Objects' => 'Pesquisa para objeto %1$s ',
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
	'UI:Login:IncorrectLoginPassword' => 'Usuário/senha incorreto, tente novamente.',
	'UI:Login:IdentifyYourself' => 'Identifique-se antes continuar',
	'UI:Login:UserNamePrompt' => 'Usuário',
	'UI:Login:PasswordPrompt' => 'Senha',
	'UI:Login:ForgotPwd' => 'Esqueceu sua senha?',
	'UI:Login:ForgotPwdForm' => 'Esqueceu sua senha',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' pode enviar um e-mail em que você vai encontrar instruções para seguir para redefinir sua conta.',
	'UI:Login:ResetPassword' => 'Enviar agora',
	'UI:Login:ResetPwdFailed' => 'Falha para enviar email: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' não é um login válido',
	'UI:ResetPwd-Error-NotPossible' => 'conta externa não é permitida alteração de senha.',
	'UI:ResetPwd-Error-FixedPwd' => 'a conta não permite alterar senha.',
	'UI:ResetPwd-Error-NoContact' => 'a conta não está associada a uma pessoa.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'a conta não está associada a uma pessoa que contenha um endereço de e-mail. Por favor, contate o administrador.',
	'UI:ResetPwd-Error-NoEmail' => 'faltando um endereço de e-mail. Por favor, contate o administrador.',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Alterar a senha',
	'UI:ResetPwd-EmailBody' => '<body><p>Você solicitou a alteração da senha do '.ITOP_APPLICATION_SHORT.'.</p><p>Por favor, siga este link (passo simples) para <a href="%1$s">digitar a nova senha</a></p>.',

	'UI:ResetPwd-Title' => 'Alterar senha',
	'UI:ResetPwd-Error-InvalidToken' => 'Desculpe, a senha já foi alterada, ou deve ter recebido vários e-mails. Por favor, certifique-se que você use o link fornecido no último e-mail recebido.',
	'UI:ResetPwd-Error-EnterPassword' => 'Digite a nova senha para a conta \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'A senha foi alterada com sucesso.',
	'UI:ResetPwd-Login' => 'Clique para entrar...',

	'UI:Login:About'                               => '',
	'UI:Login:ChangeYourPassword'                  => 'Altere sua senha',
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
	'UI:AccessRO-Users'                            => 'Somente leitura para usuário final',
	'UI:ApplicationEnvironment'                    => 'Ambiente da aplicação: %1$s',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Nova senha e Repetir nova senha são diferentes. Tente novamente!',
	'UI:Button:Login' => 'Entrar '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => 'Acesso restrito. Por favor, contacte o administrador.',
	'UI:Login:Error:AccessAdmin'                   => 'Acesso restrito somente para privilégios administrativo. Por favor, contacte o administrador.',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne'                => '-- selecione um --',
	'UI:CSVImport:MappingNotApplicable'            => '-- ignore este campo --',
	'UI:CSVImport:NoData'                          => 'Nenhum data configurado..., por favor providencie alguns dados!',
	'UI:Title:DataPreview'                         => 'Visualizar dados',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Error: The data contains only one column. Did you select the appropriate separator character?',
	'UI:CSVImport:FieldName'                       => 'Campo %1$d',
	'UI:CSVImport:DataLine1'                       => 'Dados linha 1',
	'UI:CSVImport:DataLine2'                       => 'Dados linha 2',
	'UI:CSVImport:idField'                         => 'id (Chave primária)',
	'UI:Title:BulkImport'                          => 'Importar em massa',
	'UI:Title:BulkImport+'                         => 'CSV Ajuda Importação',
	'UI:Title:BulkSynchro_nbItem_ofClass_class'    => 'Sincronização de %1$d objetos da classe %2$s',
	'UI:CSVImport:ClassesSelectOne'                => '-- selecione um --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Erro interno: "%1$s" é um código incorreto porque "%2$s" não é uma chave externa da classe"%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objetos permanecerão inalteradas.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objetos serão modificados.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objetos serão adicionados.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objetos terão erros.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objetos manteve-se inalteradas.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objetos foram modificados.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objetos foram adicionados.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objetos tinham erros.',
	'UI:Title:CSVImportStep2' => 'Passo 2 de 5: Opções dados CSV',
	'UI:Title:CSVImportStep3' => 'Passo 3 de 5: Mapeamento de dados',
	'UI:Title:CSVImportStep4' => 'Passo 4 de 5: Simulação Importação',
	'UI:Title:CSVImportStep5' => 'Passo 5 de 5: Importação completada',
	'UI:CSVImport:LinesNotImported' => 'Linhas que não podem ser carregadas:',
	'UI:CSVImport:LinesNotImported+' => 'As linhas a seguir não foram importadas, porque elas contêm erros',
	'UI:CSVImport:SeparatorComma+' => ', (vírgula)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (ponto e vírgula)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'outro:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (aspas duplas)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (aspas simples)',
	'UI:CSVImport:QualifierOther' => 'outro:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Tratar a primeira linha como um cabeçalho (nomes de colunas)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Pular %1$s linha(s) no início do arquivo',
	'UI:CSVImport:CSVDataPreview' => 'Visualizar dados CSV',
	'UI:CSVImport:SelectFile' => 'Selecione o arquivo a importar:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Carregar por um arquivo',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiar e colar dados',
	'UI:CSVImport:Tab:Templates' => 'Modelos',
	'UI:CSVImport:PasteData' => 'Colar os dados para importar:',
	'UI:CSVImport:PickClassForTemplate' => 'Escolha o modelo para baixar: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caracter separador:',
	'UI:CSVImport:TextQualifierCharacter' => 'Caracter qualificador de texto',
	'UI:CSVImport:CommentsAndHeader' => 'Comentários e cabeçalho',
	'UI:CSVImport:SelectClass' => 'Selecione a classe para importar:',
	'UI:CSVImport:AdvancedMode' => 'Modo avançado',
	'UI:CSVImport:AdvancedMode+' => 'No modo avançado o "id" (chave primária) dos objetos pode ser usado para atualizar e renomear objetos.No entanto, a coluna "id" (se houver) só pode ser usado como um critério de pesquisa e não pode ser combinado com qualquer outro critério de busca.',
	'UI:CSVImport:SelectAClassFirst' => 'Para configurar o mapeamento, selecione uma classe primeira.',
	'UI:CSVImport:HeaderFields' => 'Campos',
	'UI:CSVImport:HeaderMappings' => 'Mapeamentos',
	'UI:CSVImport:HeaderSearch' => 'Pesquisar?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Por favor, selecione um mapeamento para cada campo.',
	'UI:CSVImport:AlertMultipleMapping' => 'Por favor, certifique-se que um campo de destino é mapeado apenas uma vez.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Selecione ao menos um critério de busca',
	'UI:CSVImport:Encoding' => 'Codificação de caracteres',
	'UI:UniversalSearchTitle' => 'Pesquisa Universal',
	'UI:UniversalSearch:Error' => 'Erro: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selecione a classe para pesquisar: ',

	'UI:CSVReport-Value-Modified' => 'Modificado',
	'UI:CSVReport-Value-SetIssue' => 'Não pode ser modificado - razão: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Não pode ser modificado para %1$s - razão: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Não combina',
	'UI:CSVReport-Value-Missing' => 'Faltando valor obrigatório',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: found %1$s objects',
	'UI:CSVReport-Row-Unchanged' => 'unchanged',
	'UI:CSVReport-Row-Created' => 'criado',
	'UI:CSVReport-Row-Updated' => 'atualizado colunas %1$d',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Nulo não permitido',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objeto não encontrado',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Encontrado %1$d combinações',
	'UI:CSVReport-Value-Issue-Readonly' => 'The attribute \'%1$s\' is read-only and cannot be modified (current value: %2$s, proposed value: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Failed to process input: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': no match found, check spelling',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributes not consistent with each others: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unexpected attribute value(s)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Could not be created, due to missing external key(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'wrong date format',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'failed to reconcile',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation',
	'UI:CSVReport-Row-Issue-Internal' => 'Internal error: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Não modificado',
	'UI:CSVReport-Icon-Modified' => 'Modificado',
	'UI:CSVReport-Icon-Missing' => 'Missing',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated',
	'UI:CSVReport-Icon-Created' => 'Criado',
	'UI:CSVReport-Object-ToCreate' => 'Objeto acaba ser criado',
	'UI:CSVReport-Object-Created' => 'Objeto criado',
	'UI:CSVReport-Icon-Error' => 'Erro',
	'UI:CSVReport-Object-Error' => 'ERRO: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% of the loaded objects have errors and will be ignored.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% of the loaded objects will be created.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% of the loaded objects will be modified.',

	'UI:CSVExport:AdvancedMode' => 'Modo avançado',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.',
	'UI:CSVExport:LostChars' => 'Encoding issue',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => 'CMDB Auditoria',
	'UI:Audit:InteractiveAudit' => 'Auditoria Interativa',
	'UI:Audit:HeaderAuditRule' => 'Regra de auditoria',
	'UI:Audit:HeaderNbObjects' => '# Objetos',
	'UI:Audit:HeaderNbErrors' => '# Erros',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:OqlError' => 'OQL Error~~',
	'UI:Audit:Error:ValueNA' => 'n/a~~',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule~~',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL erro na regra %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category' => 'Error in Category~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL erro na categoria %1$s: %2$s.',
	'UI:Audit:AuditErrors' => 'Audit Errors~~',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited~~',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors~~',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated~~',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%~~',


	'UI:RunQuery:Title' => 'Avaliar consultas OQL',
	'UI:RunQuery:QueryExamples' => 'Exemplos de consultas',
	'UI:RunQuery:QueryResults' => 'Query Results~~',
	'UI:RunQuery:HeaderPurpose' => 'Propósito',
	'UI:RunQuery:HeaderPurpose+' => 'Explicação sobre a consulta',
	'UI:RunQuery:HeaderOQLExpression' => 'A consulta na sintaxe OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'A consulta na sintaxe OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expressão para avaliar: ',
	'UI:RunQuery:QueryArguments' => 'Query Arguments~~',
	'UI:RunQuery:MoreInfo' => 'Mais informações sobre a consulta: ',
	'UI:RunQuery:DevelopedQuery' => 'Redevelopped query expression: ',
	'UI:RunQuery:SerializedFilter' => 'Filtro serializado: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Ocorreu um erro ao executar a consulta',
	'UI:Query:UrlForExcel' => 'URL a ser usada para consultas web MS-Excel',
	'UI:Query:UrlV1' => 'A lista de campos não foi especificada. A página <em>export-V2.php</em> não pode ser chamada sem essa informação. Portanto, o URL sugerido abaixo aponta para a página herdada: <em>export.php</em>. Essa versão herdada da exportação tem a seguinte limitação: a lista de campos exportados pode variar dependendo do formato de saída e do modelo de dados do '.ITOP_APPLICATION_SHORT.'. Se você quiser garantir que a lista de colunas exportadas permaneça estável a longo prazo, então você deve especificar um valor para o atributo "Fields" e usar a página <em>export-V2.php</em>.',
	'UI:Schema:Title' => 'Esquema de objetos',
	'UI:Schema:TitleForClass' => 'Esquema de %1$s~~',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relações',
	'UI:Schema:AbstractClass' => 'Classe abstrata: nenhum objeto desta classe pode ser instanciada.',
	'UI:Schema:NonAbstractClass' => 'Classe não-abstrata: os objetos desta classe pode ser instanciada.',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarquia de classes',
	'UI:Schema:AllClasses' => 'Todas classes',
	'UI:Schema:ExternalKey_To' => 'Chave externa para %1$s',
	'UI:Schema:Columns_Description' => 'Colunas: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Padrão: "%1$s"',
	'UI:Schema:NullAllowed' => 'Nulo permitido',
	'UI:Schema:NullNotAllowed' => 'Nulo não permitido',
	'UI:Schema:Attributes' => 'Atributos',
	'UI:Schema:AttributeCode' => 'Código atributo',
	'UI:Schema:AttributeCode+' => 'Código interno do atributo',
	'UI:Schema:Label' => 'Rótulo',
	'UI:Schema:Label+' => 'Rótulo do atributo',
	'UI:Schema:Type' => 'Tipo',

	'UI:Schema:Type+' => 'Tipo dado do atributo',
	'UI:Schema:Origin' => 'Origem',
	'UI:Schema:Origin+' => 'The base class in which this attribute is defined',
	'UI:Schema:Description' => 'Descrição',
	'UI:Schema:Description+' => 'Descrição do atributo',
	'UI:Schema:AllowedValues' => 'Permitido valores',
	'UI:Schema:AllowedValues+' => 'Restrições sobre os valores possíveis para este atributo',
	'UI:Schema:MoreInfo' => 'Mais informações',
	'UI:Schema:MoreInfo+' => 'Mais informações sobre o campo definido no banco de dados',
	'UI:Schema:SearchCriteria' => 'Search criteria',
	'UI:Schema:FilterCode' => 'Código filtro',
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
	'UI:Schema:NoLifeCyle' => 'Não há ciclo de vida definido para esta classe.',
	'UI:Schema:LifeCycleTransitions' => 'Transições',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opções de atributo',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Oculto',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Somente leitura',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obrigatório',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Tem que mudar',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Usuário será solicitado para alterar o valor',
	'UI:Schema:LifeCycleEmptyList' => 'Lista vazia',
	'UI:Schema:ClassFilter' => 'Classe:',
	'UI:Schema:DisplayLabel' => 'Exibir:',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Etiqueta e código',
	'UI:Schema:DisplaySelector/Label' => 'Rótulo',
	'UI:Schema:DisplaySelector/Code' => 'Código',
	'UI:Schema:Attribute/Filter' => 'Filtro',
	'UI:Schema:DefaultNullValue' => 'Padrão nulo : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Tipo os 3 primeiro caracteres...',
	'UI:Edit:SearchQuery' => 'Select a predefined query~~',
	'UI:Edit:TestQuery' => 'Testar consulta',
	'UI:Combo:SelectValue' => '--- selecione um valor ---',
	'UI:Label:SelectedObjects' => 'Selected objects: ',
	'UI:Label:AvailableObjects' => 'Available objects: ',
	'UI:Link_Class_Attributes' => '%1$s atributos',
	'UI:SelectAllToggle+' => 'Marcar todas / Desmarcar todas',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Adicionar %1$s objetos vinculados com %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Adicionar %1$s objetos vinculados com o %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Gerenciar %1$s objetos vinculados com %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Adicionar %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Excluir objetos selecionados',
	'UI:Message:EmptyList:UseAdd' => 'A lista está vazia, use o botão "Adicionar..." para adicionar elementos.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use o formulário de busca acima para procurar objetos a ser adicionado.',
	'UI:Wizard:FinalStepTitle' => 'Passo final: confirmação',
	'UI:Title:DeletionOf_Object' => 'Excluindo de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Exclusão em massa de %1$d objetos da classe %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Você não tem permissão para excluir este objeto.',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Você não tem permissão para atualizar o(s) seguinte(s) campo(s): %1$s',
	'UI:Error:ActionNotAllowed' => 'Você não tem permissão para fazer essa ação',
	'UI:Error:NotEnoughRightsToDelete' => 'Este objeto não pode ser apagado porque o usuário atual não tem direitos suficientes',
	'UI:Error:CannotDeleteBecause' => 'Este objeto não pode ser excluído porque: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Este objeto não pode ser excluído porque algumas operações manuais devem ser realizadas antes de que',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Este objeto não pode ser excluído porque algumas operações manuais devem ser realizadas antes de que',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s em nome de %2$s',
	'UI:Delete:Deleted' => 'excluído',
	'UI:Delete:AutomaticallyDeleted' => 'excluído automaticamente',
	'UI:Delete:AutomaticResetOf_Fields' => 'reposição automática de campo(s): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Limpeza de todas as referências a %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Limpeza de todas as referências a %1$d objetos da classe %2$s...',
	'UI:Delete:Done+' => 'O que foi feito...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s excluído.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Exclusão de %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Exclusão de %1$d objetos da classe %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Não pode ser excluído: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Deve ser excluído automaticamente, mas isso não é viável: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Devem ser excluído manualmente, mas isso não é viável: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Será automaticamente excluído',
	'UI:Delete:MustBeDeletedManually' => 'Será manualmente excluído',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Devem ser atualizados automaticamente, mas: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'será automaticamente atualizada (redefinir: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objetos/links são referências %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objetos/links fazem referências a alguns dos objetos a serem excluídos',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Para garantir a integridade do banco de dados, qualquer referência deve ser eliminada',
	'UI:Delete:Consequence+' => 'O que será feito',
	'UI:Delete:SorryDeletionNotAllowed' => 'Por favor, realize as operações manuais listados acima antes de solicitar a exclusão do referido objeto',
	'UI:Delete:PleaseDoTheManualOperations' => 'Por favor, realize as operações manuais listados acima antes de solicitar a exclusão do referido objeto',
	'UI:Delect:Confirm_Object' => 'Por favor, confirme se você deseja excluir %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Por favor, confirme que você deseja excluir o seguinte %1$d objetos da classe %2$s.',
	'UI:WelcomeToITop' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'UI:DetailsPageTitle' => '%1$s - %2$s detalhes',
	'UI:ErrorPageTitle' => 'Erro',
	'UI:ObjectDoesNotExist' => 'Desculpe, este objeto não existe (ou você não tem permissão para vê-lo).',
	'UI:ObjectArchived' => 'Este objeto foi arquivado. Por favor habilite o modo de arquivamento ou entre em contato com o seu administrador.',
	'Tag:Archived' => 'Arquivado',
	'Tag:Archived+' => 'Pode ser acessado apenas no modo de arquivo',
	'Tag:Obsolete' => 'Obsoleto',
	'Tag:Obsolete+' => 'Excluído da análise de impacto e resultados de pesquisa',
	'Tag:Synchronized' => 'Sincronizado',
	'ObjectRef:Archived' => 'Arquivado',
	'ObjectRef:Obsolete' => 'Obsoleto',
	'UI:SearchResultsPageTitle' => 'Resultado da pesquisa',
	'UI:SearchResultsTitle' => 'Resultado da pesquisa',
	'UI:SearchResultsTitle+' => 'Resultados de pesquisa de texto completo',
	'UI:Search:NoSearch' => 'Nada a pesquisar de',
	'UI:Search:NeedleTooShort' => 'A string de pesquisa \\"%1$s\\" é muito curta. Por favor digite pelo menos %2$d caracteres.',
	'UI:Search:Ongoing' => 'Procurando por \\"%1$s\\"',
	'UI:Search:Enlarge' => 'Amplie a pesquisa',
	'UI:FullTextSearchTitle_Text' => 'Resultado para "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objeto(s) da classe %2$s encontrado(s).',
	'UI:Search:NoObjectFound' => 'Nenhum objeto encontrado.',
	'UI:ModificationPageTitle_Object_Class' => '%1$s - %2$s modificados',
	'UI:ModificationTitle_Class_Object' => 'Modificação de %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'Clone %1$s - %2$s modificação',
	'UI:CloneTitle_Class_Object' => 'Clone de %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'Criação de um(a) novo(a) %1$s ',
	'UI:CreationTitle_Class' => 'Criação de um(a) novo(a) %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Selecione o tipo de %1$s para criar:',
	'UI:Class_Object_NotUpdated' => 'Nenhuma alteração detectado, %1$s (%2$s) <strong>não</strong> tenha sido modificado.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) atualizado.',
	'UI:BulkDeletePageTitle' => 'Exclusão em massa',
	'UI:BulkDeleteTitle' => 'Selecione os objetos que você deseja excluir:',
	'UI:PageTitle:ObjectCreated' => 'Objeto criado.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s criado.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Aplicando %1$s em objeto: %2$s em estado %3$s a meta do estado: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'O objeto não pode ser gravado: %1$s',
	'UI:PageTitle:FatalError' => 'Erro fatal',
	'UI:SystemIntrusion' => 'Acesso negado. Você tem tentando realizar uma operação que não é permitido para você.',
	'UI:FatalErrorMessage' => 'Erro fatal, o sistema não pode continuar.',
	'UI:Error_Details' => 'Erro: %1$s.',

	'UI:PageTitle:ProfileProjections' => 'Gerenciamento Usuários - projeções de classe',
	'UI:UserManagement:Class' => 'Classe',
	'UI:UserManagement:Class+' => 'Classe de objetos',
	'UI:UserManagement:ProjectedObject' => 'Objeto',
	'UI:UserManagement:ProjectedObject+' => 'Objetos projetados',
	'UI:UserManagement:AnyObject' => '* qualquer *',
	'UI:UserManagement:User' => 'Usuário',
	'UI:UserManagement:User+' => 'Usuário(s) envolvido(s) na projeção',
	'UI:UserManagement:Action:Read' => 'Leitura',
	'UI:UserManagement:Action:Read+' => 'Leitura/mostrar objetos',
	'UI:UserManagement:Action:Modify' => 'Modificação',
	'UI:UserManagement:Action:Modify+' => 'Criar e editar (modificar) objetos',
	'UI:UserManagement:Action:Delete' => 'Excluir',
	'UI:UserManagement:Action:Delete+' => 'Excluir objetos',
	'UI:UserManagement:Action:BulkRead' => 'Leitura em massa (Exportar)',
	'UI:UserManagement:Action:BulkRead+' => 'Listar objetos ou exportar em massa',
	'UI:UserManagement:Action:BulkModify' => 'Modificar em massa',
	'UI:UserManagement:Action:BulkModify+' => 'Criar/editar em massa (importar CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Excluir em massa',
	'UI:UserManagement:Action:BulkDelete+' => 'Excluir objeto(s) em massa',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Permitido ações (composta)',
	'UI:UserManagement:Action' => 'Ação',
	'UI:UserManagement:Action+' => 'Ação realizada pelo usuário',
	'UI:UserManagement:TitleActions' => 'Ações',
	'UI:UserManagement:Permission' => 'Permissão',
	'UI:UserManagement:Permission+' => 'Permissões usuários',
	'UI:UserManagement:Attributes' => 'Atributos',
	'UI:UserManagement:ActionAllowed:Yes' => 'Sim',
	'UI:UserManagement:ActionAllowed:No' => 'Não',
	'UI:UserManagement:AdminProfile+' => 'Administradores tem total acesso leitura/gravação para todos os objetos no banco de dados.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Ciclo de vida não tem sido definida para esta classe',
	'UI:UserManagement:GrantMatrix' => 'Permissões concedidas',

	'Menu:AdminTools' => 'Ferramentas Administrativas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Ferramentas Administrativas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Ferramentas acessíveis apenas para usuários com o perfil do administrador',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Gerenciamento Mudanças',
	'UI:ChangeManagementMenu+' => 'Gerenciamento Mudanças',
	'UI:ChangeManagementMenu:Title' => 'Visão geral',
	'UI-ChangeManagementMenu-ChangesByType' => 'Mudanças por tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Mudanças por status',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Mudanças ainda não atribuídas',

	'UI:ConfigurationManagementMenu' => 'Gerenciamento Configurações',
	'UI:ConfigurationManagementMenu+' => 'Gerenciamento Configurações',
	'UI:ConfigurationManagementMenu:Title' => 'Visão geral',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objetos Infra-estrutura por tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objetos Infra-estrutura por status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Painel para Gerenciamento Configurações',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Itens de configuração por status',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Itens de configuração por tipo',

	'UI:RequestMgmtMenuOverview:Title' => 'Painel para Gerenciamento Solicitações',
	'UI-RequestManagementOverview-RequestByService' => 'Solicitações usuários por serviço',
	'UI-RequestManagementOverview-RequestByPriority' => 'Solicitações usuários por prioridade',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Solicitações usuários não atribuídos a um agente',

	'UI:IncidentMgmtMenuOverview:Title' => 'Painel para Gerenciamento Incidentes',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidentes por serviço',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidentes por prioridade',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidentes por ainda atribuído a um agente',

	'UI:ChangeMgmtMenuOverview:Title' => 'Painel para Gerenciamento Mudanças',
	'UI-ChangeManagementOverview-ChangeByType' => 'Mudanças por tipo',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Mudanças ainda não atribuído a um agente',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interrupções devido a alterações',

	'UI:ServiceMgmtMenuOverview:Title' => 'Painel para Gerenciamento Serviços',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratos clientes a serem renovados em 30 dias',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contratos provedores a serem renovados em 30 dias',

	'UI:ContactsMenu' => 'Contatos',
	'UI:ContactsMenu+' => 'Contatos',
	'UI:ContactsMenu:Title' => 'Visão geral',
	'UI-ContactsMenu-ContactsByLocation' => 'Contatos por localidade',
	'UI-ContactsMenu-ContactsByType' => 'Contatos por tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contatos por status',

	'Menu:CSVImportMenu' => 'Importar CSV',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Criação ou atualização em massa',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Modelo Dados',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Visão geral do Modelo Dados',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Exportar',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Exportar o resultado de qualquer consulta em HTML, CSV ou XML',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notificações',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuração de Notificações',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuração de Notificações',
	'UI:NotificationsMenu:Help' => 'Ajuda',
	'UI:NotificationsMenu:HelpContent' => '<p>As Notificações são totalmente personalizáveis​​. Elas são baseadas em dois conjuntos de objetos: <i>Gatilhos e Ações</i>.</p>
<p><i><b>Gatilhos</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Ações</b></i> define the actions to be performed when the triggers execute. For now there are only two kind of actions:
<ol>
	<li>Sending an email message: Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.<br />
	A special page: <a href="../setup/email.test.php" target="_blank">email.test.php</a> is available for testing and troubleshooting your PHP mail configuration.</li>
	<li>Outgoing webhooks: Allow integration with a third-party application by sending structured data to a defined URL.</li>
</ol>
</p>
<p>To be executed, actions must be associated to triggers.
When associated with a trigger, each action is given an "order" number, specifying in which order the actions are to be executed.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Gatilhos',
	'UI:NotificationsMenu:AvailableTriggers' => 'Available triggers',
	'UI:NotificationsMenu:OnCreate' => 'When an object is created',
	'UI:NotificationsMenu:OnStateEnter' => 'When an object enters a given state',
	'UI:NotificationsMenu:OnStateLeave' => 'When an object leaves a given state',
	'UI:NotificationsMenu:Actions' => 'Ações',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Email actions~~',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook actions (outgoing integrations)~~',
	'UI:NotificationsMenu:Actions:Action' => 'Other actions~~',
	'UI:NotificationsMenu:AvailableActions' => 'Available actions',

	'Menu:TagAdminMenu' => 'Configuração de tags',
	'Menu:TagAdminMenu+' => 'Gerenciamento de valores de tags',
	'UI:TagAdminMenu:Title' => 'Configuração de tags',
	'UI:TagAdminMenu:NoTags' => 'Nenhum campo Tag configurado',
	'UI:TagSetFieldData:Error' => 'Erro: %1$s',

	'Menu:AuditCategories' => 'Categoria Auditorias',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Categoria Auditorias',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Categoria Auditorias',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Executar consultas',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Executar qualquer consulta',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Consulta  definida',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Consulta  definida',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Administração Dados',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Administração Dados',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Pesquisa Universal',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Pesquisar por nada...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Gerenciamento Usuários',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Gerenciamento Usuários',// Duplicated into itop-welcome-itil (will be removed from here...)

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
	'UI:iTopVersion:Long' => '%1$s versão %2$s-%3$s construído %4$s',
	'UI:PropertiesTab' => 'Propriedades',

	'UI:OpenDocumentInNewWindow_' => 'Abrir~~',
	'UI:DownloadDocument_' => 'Baixar~~',
	'UI:Document:NoPreview' => 'Nenhuma visualização está disponível para este documento',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Perdida por %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Ajuda',
	'UI:PasswordConfirm' => 'Confirmar',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Antes de adicionar mais %1$s objetos, salvar este objeto.',
	'UI:DisplayThisMessageAtStartup' => 'Exibir esta mensagem na inicialização',
	'UI:RelationshipGraph' => 'Visualizar gráfico',
	'UI:RelationshipList' => 'Listar',
	'UI:RelationGroups' => 'Grupos',
	'UI:OperationCancelled' => 'Operação cancelada',
	'UI:ElementsDisplayed' => 'Filtrando',
	'UI:RelationGroupNumber_N' => 'Grupo #%1$d',
	'UI:Relation:ExportAsPDF' => 'Exportar como PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Limite de agrupamento',
	'UI:Relation:AdditionalContextInfo' => 'Informações de contexto adicionais',
	'UI:Relation:NoneSelected' => 'Nenhum',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Exportar como Anexo ...',
	'UI:Relation:DrillDown' => 'Detalhes ...',
	'UI:Relation:PDFExportOptions' => 'Opções de exportação de PDF',
	'UI:Relation:AttachmentExportOptions_Name' => 'Opções de anexo para %1$s',
	'UI:RelationOption:Untitled' => 'Sem título',
	'UI:Relation:Key' => 'Key~~',
	'UI:Relation:Comments' => 'Comentários',
	'UI:RelationOption:Title' => 'Title~~',
	'UI:RelationOption:IncludeList' => 'Incluir a lista de objetos',
	'UI:RelationOption:Comments' => 'Comentários',
	'UI:Button:Export' => 'Export~~',
	'UI:Relation:PDFExportPageFormat' => 'Formato da página',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Carta',
	'UI:Relation:PDFExportPageOrientation' => 'Orientação da página',
	'UI:PageOrientation_Portrait' => 'Portrait~~',
	'UI:PageOrientation_Landscape' => 'Landscape~~',
	'UI:RelationTooltip:Redundancy' => 'Redundância',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# de itens impactados: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Limite crítico: %1$d / %2$d',
	'Portal:Title' => 'Portal do usuário',
	'Portal:NoRequestMgmt' => 'Caro %1$ s, você foi redirecionado para esta página porque a sua conta é configurado com o perfil de \'usuário do Portal\'. Infelizmente, iTop não foi instalado com a função \'Gerenciamento de Solicitações\'. Por favor, contate o administrador.',
	'Portal:Refresh' => 'Atualizar',
	'Portal:Back' => 'Voltar',
	'Portal:WelcomeUserOrg' => 'Bem-vindo %1$s, de %2$s',
	'Portal:TitleDetailsFor_Request' => 'Detalhes para solicitação',
	'Portal:ShowOngoing' => 'Mostrar solicitações abertas',
	'Portal:ShowClosed' => 'Mostrar solicitações fechadas',
	'Portal:CreateNewRequest' => 'Criar uma nova solicitação',
	'Portal:CreateNewRequestItil' => 'Criar uma nova solicitação',
	'Portal:CreateNewIncidentItil' => 'Criar um novo relatório de incidente',
	'Portal:ChangeMyPassword' => 'Alterar minha senha',
	'Portal:Disconnect' => 'Sair',
	'Portal:OpenRequests' => 'Minhas solicitações abertas',
	'Portal:ClosedRequests' => 'Minhas solicitações fechadas',
	'Portal:ResolvedRequests' => 'Minhas solicitações resolvidas',
	'Portal:SelectService' => 'Selecione um serviço de um catálogo:',
	'Portal:PleaseSelectOneService' => 'Selecione um serviço',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecione um sub-serviço do serviço %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Selecione uma sub-categoria',
	'Portal:DescriptionOfTheRequest' => 'Digite a descrição de sua solicitação:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detalhe da solicitação %1$s:',
	'Portal:NoOpenRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:NoClosedRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:Button:ReopenTicket' => 'Re-abrir esta solicitação',
	'Portal:Button:CloseTicket' => 'Fechar esta solicitação',
	'Portal:Button:UpdateRequest' => 'Atualizar a solicitação',
	'Portal:EnterYourCommentsOnTicket' => 'Digite seu comentário sobre a resolução/solução de sua solicitação:',
	'Portal:ErrorNoContactForThisUser' => 'Erro: o usuário atual não esta associado com um contato/pessoa. Por favor, contacte o administrador.',
	'Portal:Attachments' => 'Anexos',
	'Portal:AddAttachment' => ' Adicionar anexo ',
	'Portal:RemoveAttachment' => ' Remover anexo ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Anexo #%1$d para %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Selecione um modelo para %1$s',
	'Enum:Undefined' => 'Indefinido',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s dias %2$s horas %3$s minutos %4$s segundos',
	'UI:ModifyAllPageTitle' => 'Modificar todos',
	'UI:Modify_N_ObjectsOf_Class' => 'Modificando objeto %1$d da classe %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modificando objeto %1$d da classe %2$s fora de %3$d',
	'UI:Menu:ModifyAll' => 'Modificar...',
	'UI:Button:ModifyAll' => 'Modificar todos',
	'UI:Button:PreviewModifications' => 'Visualizar modificações >>',
	'UI:ModifiedObject' => 'Objeto modificado',
	'UI:BulkModifyStatus' => 'Operação',
	'UI:BulkModifyStatus+' => 'Status da operação',
	'UI:BulkModifyErrors' => 'Erros (se qualquer)',
	'UI:BulkModifyErrors+' => 'Erros que impedem a modificação',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Erro',
	'UI:BulkModifyStatusModified' => 'Modificado',
	'UI:BulkModifyStatusSkipped' => 'Skipped',
	'UI:BulkModify_Count_DistinctValues' => '%1$d valores distintos:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d tempo(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d mais valores...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Tentativa de definir o campo somente leitura: %1$s',
	'UI:FailedToApplyStimuli' => 'A ação falhou.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: modificando objetos %2$d da classe %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Digite seu texto aqui:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Valor inicial:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'O campo %1$s não é editável, porque é originado pela sincronização de dados. Valor não definido.',
	'UI:ActionNotAllowed' => 'Você não tem permissão para executar esta ação nesses objetos.',
	'UI:BulkAction:NoObjectSelected' => 'Por favor, selecione pelo menos um objeto para realizar esta operação.',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'O campo %1$s não é editável, porque é originado pela sincronização de dados. Valor não definido.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objetos (%2$s objetos selecionados).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objetos.',
	'UI:Pagination:PageSize' => '%1$s objetos por página',
	'UI:Pagination:PagesLabel' => 'Páginas:',
	'UI:Pagination:All' => 'Todos',
	'UI:HierarchyOf_Class' => 'Hierarquia de %1$s',
	'UI:Preferences' => 'Preferências...',
	'UI:ArchiveModeOn' => 'Ativar o modo de arquivo',
	'UI:ArchiveModeOff' => 'Desativar modo de arquivo',
	'UI:ArchiveMode:Banner' => 'Modo de arquivo',
	'UI:ArchiveMode:Banner+' => 'Objetos arquivados são visíveis e nenhuma modificação é permitida',
	'UI:FavoriteOrganizations' => 'Organizações favoritas',
	'UI:FavoriteOrganizations+' => 'Confira na lista abaixo as organizações que você deseja ver no menu drop-down para um acesso rápido.Note-se que esta não é uma configuração de segurança, objetos de qualquer organização ainda são visíveis e podem ser acessadas ao selecionar \\"Todos Organizações\\" na lista drop-down.',
	'UI:FavoriteLanguage' => 'Idioma do painel do Usuário~~',
	'UI:Favorites:SelectYourLanguage' => 'Selecione sua linguagem preferida',
	'UI:FavoriteOtherSettings' => 'Outras configurações',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Quantidade padrão para listas:  %1$s itens por página~~',
	'UI:Favorites:ShowObsoleteData' => 'Mostrar dados obsoletos',
	'UI:Favorites:ShowObsoleteData+' => 'Mostrar dados obsoletos nos resultados de pesquisa e listas de itens para selecionar',
	'UI:NavigateAwayConfirmationMessage' => 'Qualquer modificações serão descartados.',
	'UI:CancelConfirmationMessage' => 'Você vai perder as suas alterações. Continuar mesmo assim?',
	'UI:AutoApplyConfirmationMessage' => 'Algumas mudanças ainda não foram aplicadas. Você quer levá-los em conta?',
	'UI:Create_Class_InState' => 'Criar o estado %1$s: ',
	'UI:OrderByHint_Values' => 'Classificar por: %1$s',
	'UI:Menu:AddToDashboard' => 'Adicionar ao painel...',
	'UI:Button:Refresh' => 'Atualizar',
	'UI:Button:GoPrint' => 'Imprimir ...',
	'UI:ExplainPrintable' => 'Clique no ícone %1$s para ocultar itens da impressão.<br/>Use o recurso "pré-visualização de impressão" do seu navegador para visualizar antes de imprimir.<br/>Nota: este cabeçalho e outros controles de ajuste não ser impresso.',
	'UI:PrintResolution:FullSize' => 'Tamanho grande',
	'UI:PrintResolution:A4Portrait' => 'Retrato A4',
	'UI:PrintResolution:A4Landscape' => 'Paisagem A4',
	'UI:PrintResolution:LetterPortrait' => 'Carta Retrato',
	'UI:PrintResolution:LetterLandscape' => 'Carta Retrato',
	'UI:Toggle:SwitchToStandardDashboard' => 'Switch to standard dashboard~~',
	'UI:Toggle:SwitchToCustomDashboard' => 'Switch to custom dashboard~~',

	'UI:ConfigureThisList' => 'Configurar esta lista...',
	'UI:ListConfigurationTitle' => 'Listar configuração',
	'UI:ColumnsAndSortOrder' => 'Colunas e ordem de classificação:',
	'UI:UseDefaultSettings' => 'Use a configuração padrão',
	'UI:UseSpecificSettings' => 'Use as seguintes configurações:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Mostrar',
	'UI:Display_X_ItemsPerPage_suffix' => 'itens por página',
	'UI:UseSavetheSettings' => 'Salvar configurações',
	'UI:OnlyForThisList' => 'Somente para esta lista',
	'UI:ForAllLists' => 'Para todas as listas',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (nome amigável)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Mover acima',
	'UI:Button:MoveDown' => 'Mover abaixo',

	'UI:OQL:UnknownClassAndFix' => 'Classe desconhecida "%1$s". Você pode tentar "%2$s" em vez.',
	'UI:OQL:UnknownClassNoFix' => 'Classe desconhecida "%1$s"',

	'UI:Dashboard:EditCustom' => 'Edit custom version...~~',
	'UI:Dashboard:CreateCustom' => 'Create a custom version...~~',
	'UI:Dashboard:DeleteCustom' => 'Delete custom version...~~',
	'UI:Dashboard:RevertConfirm' => 'Cada alterações feitas na versão original será perdido. Por favor, confirme que você quer fazer isso.',
	'UI:ExportDashBoard' => 'Exportar para um arquivo',
	'UI:ImportDashBoard' => 'Importar pelo arquivo...',
	'UI:ImportDashboardTitle' => 'Importar por um arquivo',
	'UI:ImportDashboardText' => 'Selecione um arquivo do painel para importar:',
	'UI:Dashboard:Actions' => 'Dashboard actions~~',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'This dashboard displays information that does not include the on-going changes.~~',


	'UI:DashletCreation:Title' => 'Criar um novo Painel',
	'UI:DashletCreation:Dashboard' => 'Painel',
	'UI:DashletCreation:DashletType' => 'Tipo de painel',
	'UI:DashletCreation:EditNow' => 'Editar o painel',

	'UI:DashboardEdit:Title' => 'Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Título',
	'UI:DashboardEdit:AutoReload' => 'Atualizar automaticamente',
	'UI:DashboardEdit:AutoReloadSec' => 'Intervalo atualização automática (segundos)',
	'UI:DashboardEdit:AutoReloadSec+' => 'O mínimo permitido é %1$d segundos',
	'UI:DashboardEdit:Revert' => 'Revert~~',
	'UI:DashboardEdit:Apply' => 'Apply~~',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Propriedades',
	'UI:DashboardEdit:Dashlets' => 'Painel disponível',
	'UI:DashboardEdit:DashletProperties' => 'Propriedades',

	'UI:Form:Property' => 'Propriedade',
	'UI:Form:Value' => 'Valor',

	'UI:DashletUnknown:Label' => 'Desconhecido',
	'UI:DashletUnknown:Description' => 'Dashlet desconhecido (pode ter sido desinstalado)',
	'UI:DashletUnknown:RenderText:View' => 'Não é possível renderizar este dashlet.',
	'UI:DashletUnknown:RenderText:Edit' => 'Não é possível renderizar este dashlet (classe "%1$s"). Verifique com seu administrador se ainda está disponível. ',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Não há visualização disponível para este dashlet (classe "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuração (mostrada como XML bruta)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Nenhuma pré-visualização disponível para este dashlet de terceiros (classe "%1$s").',
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
	'UI:DashletGroupBy:Prop-GroupBy' => 'Grupo por...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hora de %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Mês de %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Dia da semana para %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Dia do mês para %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hora)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (mês)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (dia da semana)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (dia do mês)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Por favor, selecione o campo no qual os objetos serão agrupados',

	'UI:DashletGroupByPie:Label' => 'Pie Chart',
	'UI:DashletGroupByPie:Description' => 'Pie Chart',
	'UI:DashletGroupByBars:Label' => 'Bar Chart',
	'UI:DashletGroupByBars:Description' => 'Bar Chart',
	'UI:DashletGroupByTable:Label' => 'Grupo por (tabela)',
	'UI:DashletGroupByTable:Description' => 'Listar (Agrupado por um campo)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Função de agregação',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Atributo de função',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direção',
	'UI:DashletGroupBy:Prop-OrderField' => 'Ordenar por',
	'UI:DashletGroupBy:Prop-Limit' => 'Limite',

	'UI:DashletGroupBy:Order:asc' => 'Ascendente',
	'UI:DashletGroupBy:Order:desc' => 'Descendente',

	'UI:GroupBy:count' => 'Contagem',
	'UI:GroupBy:count+' => 'Número de elementos',
	'UI:GroupBy:sum' => 'Sum~~',
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
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Sub-título',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contatos',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Consulta',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Grupo por',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Valores',

	'UI:DashletBadge:Label' => 'Divisa',
	'UI:DashletBadge:Description' => 'Ícone objeto com novo/pesquisa',
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
	'UI:ShortcutDelete:Confirm' => 'Por favor, confirme que você deseja excluir o(s) atalho(s).',
	'Menu:MyShortcuts' => 'Meus atalhos',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Atalho',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Nome',
	'Class:Shortcut/Attribute:name+' => 'Nome usado no menu e título da página',
	'Class:ShortcutOQL' => 'Resultado pesquisa atalho',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Consulta',
	'Class:ShortcutOQL/Attribute:oql+' => 'definição da lista de objetos para procurar',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Atualizar automaticamente',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Desabilitado',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Avaliar',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Intervalo atualização automática (segundos)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'O mínimo permitido é %1$d sgundos',

	'UI:FillAllMandatoryFields' => 'Por favor, preencha todos os campos obrigatórios.',
	'UI:ValueMustBeSet' => 'Por favor especifique um valor',
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
	'UI:AddAnExisting_Class' => 'Adicionar objetos do tipo %1$s...',
	'UI:SelectionOf_Class' => 'Selecionar objetos do tipo %1$s',

	'UI:AboutBox' => 'Sobre o iTop ...',
	'UI:About:Title' => 'Sobre o '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Modelo de dados',
	'UI:About:Support' => 'Informações de suporte',
	'UI:About:Licenses' => 'Licenças',
	'UI:About:InstallationOptions' => 'Opções de instalação',
	'UI:About:ManualExtensionSource' => 'Extensão',
	'UI:About:Extension_Version' => 'Versão: %1$s',
	'UI:About:RemoteExtensionSource' => 'Dado',

	'UI:DisconnectedDlgMessage' => 'Você está desconectado. Você deve se identificar para continuar usando o aplicativo.',
	'UI:DisconnectedDlgTitle' => 'Atenção!',
	'UI:LoginAgain' => 'Login novamente',
	'UI:StayOnThePage' => 'Fique nessa página',

	'ExcelExporter:ExportMenu' => 'Exportar para Excel...',
	'ExcelExporter:ExportDialogTitle' => 'Exportar para Excel',
	'ExcelExporter:ExportButton' => 'Exportar',
	'ExcelExporter:DownloadButton' => 'Download %1$s',
	'ExcelExporter:RetrievingData' => 'Recuperando dados...',
	'ExcelExporter:BuildingExcelFile' => 'Construindo o arquivo do Excel...',
	'ExcelExporter:Done' => 'Feito.',
	'ExcelExport:AutoDownload' => 'Inicie o download automaticamente quando a exportação estiver pronta',
	'ExcelExport:PreparingExport' => 'Preparando a exportação ...',
	'ExcelExport:Statistics' => 'Estatísticas',
	'portal:legacy_portal' => 'Portal do usuário final',
	'portal:backoffice' => 'Interface de usuário back-office do '.ITOP_APPLICATION_SHORT,

	'UI:CurrentObjectIsLockedBy_User' => 'O objeto está bloqueado, pois está sendo modificado por %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'O objeto está sendo modificado por %1$s. Suas modificações não podem ser enviadas, pois seriam sobrescritas.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'The object is currently being modified by %1$s. You\'ll be able to submit your modifications once they have finished.~~',
	'UI:CurrentObjectLockExpired' => 'O bloqueio para impedir modificações simultâneas do objeto expirou.',
	'UI:CurrentObjectLockExpired_Explanation' => 'O bloqueio para impedir modificações simultâneas do objeto expirou. Você não pode mais enviar sua modificação, pois outros usuários agora podem modificar este objeto.',
	'UI:ConcurrentLockKilled' => 'O bloqueio impedindo modificações no objeto atual foi deletado.',
	'UI:Menu:KillConcurrentLock' => 'Matar o bloqueio de modificação simultânea!',

	'UI:Menu:ExportPDF' => 'Exportar como PDF...',
	'UI:Menu:PrintableVersion' => 'Versão para impressão',

	'UI:BrowseInlineImages' => 'Navegue pelas imagens...',
	'UI:UploadInlineImageLegend' => 'Carregar uma nova imagem',
	'UI:SelectInlineImageToUpload' => 'Selecione a imagem para enviar',
	'UI:AvailableInlineImagesLegend' => 'Imagens disponíveis',
	'UI:NoInlineImage' => 'Não há imagem disponível no servidor. Use o botão "Browse" acima para selecionar uma imagem do seu computador e fazer o upload para o servidor. ',

	'UI:ToggleFullScreen' => 'Alternancia Maximizar / Minimizar',
	'UI:Button:ResetImage' => 'Recupere a imagem anterior',
	'UI:Button:RemoveImage' => 'Remover a imagem',
	'UI:Button:UploadImage' => 'Upload an image from the disk~~',
	'UI:UploadNotSupportedInThisMode' => 'A modificação de imagens ou arquivos não é suportada neste modo.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Minimizar / Expandir',
	'UI:Search:AutoSubmit:DisabledHint' => 'O envio automático foi desativado para esta classe',
	'UI:Search:Obsolescence:DisabledHint' => 'Baseado nas suas preferências, dados obsoletos estão escondidos',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Adicione algum critério na caixa de pesquisa ou clique no botão de pesquisa para visualizar os objetos.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Adicionar novos critérios',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recentemente usado',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Mais popular',
	'UI:Search:AddCriteria:List:Others:Title' => 'Outros',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Nenhum ainda.',

	// - Criteria header actions
	'UI:Search:Criteria:Toggle' => 'Minimize / Expand~~',
	'UI:Search:Criteria:Remove' => 'Remove~~',
	'UI:Search:Criteria:Locked' => 'Locked~~',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: qualquer',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s está vazio',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s não está vazio',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s é igual a %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contém %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s começa com %2$s',
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
	'UI:Search:Criteria:Operator:Default:Equals' => 'Iguais',
	'UI:Search:Criteria:Operator:Default:Between' => 'Entre',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contém',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Começa com',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Termina com',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Exp. Regular ',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Iguais',// => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Maior',// => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Maior',// > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Menor',// => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Menor / igual a',// > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Diferente',// => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filtrar...',
	'UI:Search:Value:Search:Placeholder' => 'Buscar...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Comece a digitar valores possíveis.',
	'UI:Search:Value:Autocomplete:Wait' => 'Aguarde...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Sem resultados.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Marcar todos / nenhum',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Marcar todos / nenhum visiveis',

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
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Os filhos dos objetos selecionados serão incluídos.',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtered',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtrado em %1$s',

	'UI:StateChanged' => 'State changed~~',
));

//
// Expression to Natural language
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Expression:Operator:AND' => ' AND ',
	'Expression:Operator:OR' => ' OR ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 's',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'a',

	'Expression:Unit:Long:DAY' => 'dia(s)',
	'Expression:Unit:Long:HOUR' => 'hora(s)~~',
	'Expression:Unit:Long:MINUTE' => 'minuto(s)',

	'Expression:Verb:NOW' => 'agora',
	'Expression:Verb:ISNULL' => ': indefinido',
));

//
// iTop Newsroom menu
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'UI:Newsroom:NoNewMessage' => 'Nenhuma mensagem nova',
	'UI:Newsroom:XNewMessage' => '%1$s new message(s)~~',
	'UI:Newsroom:MarkAllAsRead' => 'Marcar todas as mensagens como lidas',
	'UI:Newsroom:ViewAllMessages' => 'Ver todas as mensagens',
	'UI:Newsroom:Preferences' => 'Preferências de sala de notícias',
	'UI:Newsroom:ConfigurationLink' => 'Configuração',
	'UI:Newsroom:ResetCache' => 'Redefinir cache',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Mostrar mensagens de %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Exibir até %1$s mensagens no menu %2$s.',
));


Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:DataSources' => 'Fontes de dados de sincronização',
	'Menu:DataSources+' => 'Todas fontes de dados de sincronização',
	'Menu:WelcomeMenu' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenu+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage+' => 'Bem-vindo ao '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Ferramentas Administrativas',
	'Menu:AdminTools+' => 'Ferramentas Administrativas',
	'Menu:AdminTools?' => 'Ferramentas acessíveis apenas para usuários com o perfil do administrador',
	'Menu:DataModelMenu' => 'Modelo Dados',
	'Menu:DataModelMenu+' => 'Visão geral do Modelo Dados',
	'Menu:ExportMenu' => 'Exportar',
	'Menu:ExportMenu+' => 'Exportar o resultado de qualquer consulta em HTML, CSV ou XML',
	'Menu:NotificationsMenu' => 'Notificações',
	'Menu:NotificationsMenu+' => 'Configuração de Notificações',
	'Menu:AuditCategories' => 'Categoria Auditorias',
	'Menu:AuditCategories+' => 'Categoria Auditorias',
	'Menu:Notifications:Title' => 'Categoria Auditorias',
	'Menu:RunQueriesMenu' => 'Executar consultas',
	'Menu:RunQueriesMenu+' => 'Executar qualquer consulta',
	'Menu:QueryMenu' => 'Consulta  definida',
	'Menu:QueryMenu+' => 'Consulta  definida',
	'Menu:UniversalSearchMenu' => 'Pesquisa Universal',
	'Menu:UniversalSearchMenu+' => 'Pesquisar por nada...',
	'Menu:UserManagementMenu' => 'Gerenciamento Usuários',
	'Menu:UserManagementMenu+' => 'Gerenciamento Usuários',
	'Menu:ProfilesMenu' => 'Perfis',
	'Menu:ProfilesMenu+' => 'Perfis',
	'Menu:ProfilesMenu:Title' => 'Perfis',
	'Menu:UserAccountsMenu' => 'Contas usuários',
	'Menu:UserAccountsMenu+' => 'Contas usuários',
	'Menu:UserAccountsMenu:Title' => 'Contas usuários',
	'Menu:MyShortcuts' => 'Meus atalhos',
	'Menu:UserManagement' => 'Gerenciamento de usuários',
	'Menu:Queries' => 'Consultas',
	'Menu:ConfigurationTools' => 'Configuração',
));

// Additional language entries not present in English dict
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
 'UI:Toggle:StandardDashboard' => 'Padrão',
 'UI:Toggle:CustomDashboard' => 'Customizado',
 'UI:Dashboard:Edit' => 'Editar esta página...',
 'UI:Dashboard:Revert' => 'Reverter para versão original...',
));

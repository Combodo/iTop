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
	'Class:AuditCategory+' => 'Uma seção dentro da auditoria global',
	'Class:AuditCategory/Attribute:name' => 'Nome Categoria',
	'Class:AuditCategory/Attribute:name+' => 'Nome curto para esta categoria',
	'Class:AuditCategory/Attribute:description' => 'Descrição Auditoria',
	'Class:AuditCategory/Attribute:description+' => 'Longa descrição para esta categoria de auditoria',
	'Class:AuditCategory/Attribute:definition_set' => 'Definir Regra',
	'Class:AuditCategory/Attribute:definition_set+' => 'Expressão OQL que define o conjunto de objetos para auditoria',
	'Class:AuditCategory/Attribute:rules_list' => 'Regras Auditoria',
	'Class:AuditCategory/Attribute:rules_list+' => 'Regra auditoria para esta categoria',
));

//
// Class: AuditRule
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AuditRule' => 'Regra de auditoria',
	'Class:AuditRule+' => 'Uma regra para verificar se há uma dada categoria de Auditoria',
	'Class:AuditRule/Attribute:name' => 'Nome Regra',
	'Class:AuditRule/Attribute:name+' => 'Nome curto para esta regra',
	'Class:AuditRule/Attribute:description' => 'Descrição Regra',
	'Class:AuditRule/Attribute:description+' => 'Descrição longa para esta regra de auditoria',
	'Class:AuditRule/Attribute:query' => 'Executar consulta',
	'Class:AuditRule/Attribute:query+' => 'Executar a expressão OQL',
	'Class:AuditRule/Attribute:valid_flag' => 'Objetos válidos?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Verdadeiro se a regra retornar o objeto válido, falso caso contrário',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'verdadeiro',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'verdadeiro',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'falso',
	'Class:AuditRule/Attribute:category_id' => 'Categoria',
	'Class:AuditRule/Attribute:category_id+' => 'A categoria para esta regra',
	'Class:AuditRule/Attribute:category_name' => 'Categoria',
	'Class:AuditRule/Attribute:category_name+' => 'Nome da categoria para esta regra',
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
	'Class:User/Attribute:contactid+' => 'Dados pessoais a partir dos dados de negócio',
	'Class:User/Attribute:last_name' => 'Último nome',
	'Class:User/Attribute:last_name+' => 'Nome do contato correspondente',
	'Class:User/Attribute:first_name' => 'Primeiro nome',
	'Class:User/Attribute:first_name+' => 'Primeiro nome do contato correspondente',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email do contato correspondente',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'string de identificação do usuário',
	'Class:User/Attribute:language' => 'Linguagem',
	'Class:User/Attribute:language+' => 'linguagem usuário',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profiles',
	'Class:User/Attribute:profile_list+' => 'Regras, permissão de direitos para essa pessoa',
	'Class:User/Attribute:allowed_org_list' => 'Organização permitida',
	'Class:User/Attribute:allowed_org_list+' => 'O usuário está permitido ver os dados para a(s) organização(ões) abaixo. Se nenhum organização for especificado, não há restrição.',

	'Class:User/Error:LoginMustBeUnique' => 'Login é único - "%1s" já está ativo.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Pelo menos um perfil deve ser atribuído a este usuário.',
));

//
// Class: URP_Profiles
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Profiles' => 'Profile',
	'Class:URP_Profiles+' => 'User profile',
	'Class:URP_Profiles/Attribute:name' => 'Nome',
	'Class:URP_Profiles/Attribute:name+' => 'Etiqueta',
	'Class:URP_Profiles/Attribute:description' => 'Descrição',
	'Class:URP_Profiles/Attribute:description+' => 'uma linha descrição',
	'Class:URP_Profiles/Attribute:user_list' => 'Usuários',
	'Class:URP_Profiles/Attribute:user_list+' => 'pessoas que possuem esta profile',
));

//
// Class: URP_Dimensions
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_Dimensions' => 'dimensão',
	'Class:URP_Dimensions+' => 'application dimension (defining silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Nome',
	'Class:URP_Dimensions/Attribute:name+' => 'label',
	'Class:URP_Dimensions/Attribute:description' => 'Descrição',
	'Class:URP_Dimensions/Attribute:description+' => 'uma linha descrição',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'nome classe ou tipo dado (unidade projeção)',
));

//
// Class: URP_UserProfile
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserProfile' => 'Usuário para profile',
	'Class:URP_UserProfile+' => 'Usuário profiles',
	'Class:URP_UserProfile/Attribute:userid' => 'Usuário',
	'Class:URP_UserProfile/Attribute:userid+' => 'Conta usuário',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profile',
	'Class:URP_UserProfile/Attribute:profileid+' => 'profile utilizado',
	'Class:URP_UserProfile/Attribute:profile' => 'Profile',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nome profile',
	'Class:URP_UserProfile/Attribute:reason' => 'Razão',
	'Class:URP_UserProfile/Attribute:reason+' => 'explicação por que esta pessoa teve ter esta profile',
));

//
// Class: URP_UserOrg
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_UserOrg' => 'User organizations',
	'Class:URP_UserOrg+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:userid' => 'Usuário',
	'Class:URP_UserOrg/Attribute:userid+' => 'conta usuário',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organização',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organização permitida',
	'Class:URP_UserOrg/Attribute:reason' => 'Razão',
	'Class:URP_UserOrg/Attribute:reason+' => 'explicação por que esta pessoa é permitida para ver os dados da organização abaixo',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profile name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'permissions on classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:class' => 'Class',
	'Class:URP_ActionGrant/Attribute:class+' => 'Target class',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permission',
	'Class:URP_ActionGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Action',
	'Class:URP_ActionGrant/Attribute:action+' => 'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:class' => 'Class',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Target class',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permission',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'permissions at the attributes level',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Action grant',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'action grant',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribute',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribute code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:WelcomeMenu' => 'Bem-vindo',
	'Menu:WelcomeMenu+' => 'Bem-vindo ao iTop',
	'Menu:WelcomeMenuPage' => 'Bem-vindo',
	'Menu:WelcomeMenuPage+' => 'Bem-vindo ao iTop',
	'UI:WelcomeMenu:Title' => 'Bem-vindo ao iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop is a complete, OpenSource, IT Operational Portal.</p>
<ul>It includes:
<li>A complete CMDB (Configuration management database) to document and manage the IT inventory.</li>
<li>An Incident management module to track and communicate about all issues occurring in the IT.</li>
<li>A change management module to plan and track the changes to the IT environment.</li>
<li>A known error database to speed up the resolution of incidents.</li>
<li>An outage module to document all planned outages and notify the appropriate contacts.</li>
<li>Dashboards to quickly get an overview of your IT.</li>
</ul>
<p>All the modules can be setup, step by step, indepently of each other.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop is service provider oriented, it allows IT engineers to manage easily multiple customers or organizations.
<ul>iTop, delivers a feature-rich set of business processes that:
<li>Enhances IT management effectiveness</li> 
<li>Drives IT operations performance</li> 
<li>Improves customer satisfaction and provides executives with insights into business performance.</li>
</ul>
</p>
<p>iTop is completely opened to be integrated within your current IT Management infrastructure.</p>
<p>
<ul>Adopting this new generation of IT Operational portal will help you to:
<li>Better manage a more and more complex IT environment.</li>
<li>Implement ITIL processes at your own pace.</li>
<li>Manage the most important asset of your IT: Documentation.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Solicitações abertas: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Minhas solicitações',
	'UI:WelcomeMenu:OpenIncidents' => 'Incidentes abertos: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Itens Configuração: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidentes atribuídos a mim',
	'UI:AllOrganizations' => ' Todas organizações ',
	'UI:YourSearch' => 'Sua pesquisa',
	'UI:LoggedAsMessage' => 'Logado como %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logado como %1$s (Administrador)',
	'UI:Button:Logoff' => 'Sair',
	'UI:Button:GlobalSearch' => 'Pesquisa',
	'UI:Button:Search' => ' Pesquisa ',
	'UI:Button:Query' => ' Consulta ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Cancelar',
	'UI:Button:Apply' => 'Aplicar',
	'UI:Button:Back' => ' << Voltar ',
	'UI:Button:Restart' => ' |<< Reiniciar ',
	'UI:Button:Next' => ' Próximo >> ',
	'UI:Button:Finish' => ' Final ',
	'UI:Button:DoImport' => ' Executar a Importação ! ',
	'UI:Button:Done' => ' Concluir ',
	'UI:Button:SimulateImport' => ' Simular a Importação ',
	'UI:Button:Test' => 'Testar!',
	'UI:Button:Evaluate' => ' Avaliar ',
	'UI:Button:AddObject' => ' Adicionar... ',
	'UI:Button:BrowseObjects' => ' Navegar... ',
	'UI:Button:Add' => ' Adicionar ',
	'UI:Button:AddToList' => ' << Adicionar ',
	'UI:Button:RemoveFromList' => ' Remover >> ',
	'UI:Button:FilterList' => ' Filtro... ',
	'UI:Button:	' => ' Criar ',
	'UI:Button:Delete' => ' Apagar ! ',
	'UI:Button:ChangePassword' => ' Alterar senha ',
	'UI:Button:ResetPassword' => ' Redefinir senha ',
	
	'UI:SearchToggle' => 'Pesquisa',
	'UI:ClickToCreateNew' => 'Criar um novo %1$s',
	'UI:SearchFor_Class' => 'Pesquisa para %1$s objetos',
	'UI:NoObjectToDisplay' => 'Nenhum objeto encontrado.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parâmetro Object_id é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parâmetro Target_attr é obrigatório quando link_attr é especificado. Verifique a definição do modelo de exibição.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parâmetro Group_by é obrigatório. Verifique a definição do modelo de exibição.',
	'UI:Error:InvalidGroupByFields' => 'Inválido lista dos campos para agrupar por: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Erro: o estilo não suportada do bloco: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Definição de ligação incorreta: a classe de objetos para gerenciar: %1$s não foi encontrado como uma chave externa na classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objeto: %1$s:%2$d não encontrado.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Erro: Referência circular nas dependências entre os campos, verifique o modelo de dados.',
	'UI:Error:UploadedFileTooBig' => 'O arquivo enviado é muito grande. (Tamanho máximo permitido é de %1$s). Para modificar esse limite, contate o administrador do iTop. (Verifique a configuração do PHP para upload_max_filesize e post_max_size no servidor).',
	'UI:Error:UploadedFileTruncated.' => 'Arquivo enviado tem sido truncado!',
	'UI:Error:NoTmpDir' => 'O diretório temporário não está definido.',
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
	'UI:Error:ObjectCannotBeUpdated' => 'Erro: objeto não pode ser atualizado.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Erro: objetos já tenham sido eliminados!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'You are not allowed to perform a bulk delete of objects of class %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'You are not allowed to delete objects of class %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'You are not allowed to perform a bulk update of objects of class %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Erro: o objeto já foi clonado!',
	'UI:Error:ObjectAlreadyCreated' => 'Erro: o objeto já foi criado!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Erro: estímulo inválido "%1$s" no objeto %2$s em estado "%3$s".',
	
	
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
	'UI:History:StatsModifs+' => 'Número de objetos modificados',
	'UI:History:StatsDeletes' => 'Apagado',
	'UI:History:StatsDeletes+' => 'Número de objetos apagados',
	'UI:Loading' => 'Carregando...',
	'UI:Menu:Actions' => 'Ações',
	'UI:Menu:OtherActions' => 'Outras ações',
	'UI:Menu:New' => 'Novo...',
	'UI:Menu:Add' => 'Adicionar...',
	'UI:Menu:Manage' => 'Gerenciar...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'Exportar CSV',
	'UI:Menu:Modify' => 'Modificar...',
	'UI:Menu:Delete' => 'Apagar...',
	'UI:Menu:Manage' => 'Gerenciar...',
	'UI:Menu:BulkDelete' => 'Apagar...',
	'UI:UndefinedObject' => 'indefinido',
	'UI:Document:OpenInNewWindow:Download' => 'Abrir em uma nova janela: %1$s, Download: %2$s',
	'UI:SelectAllToggle+' => 'Selecionar / Desmarcar todos',
	'UI:SplitDateTime-Date' => 'data',
	'UI:SplitDateTime-Time' => 'hora',
	'UI:TruncatedResults' => '%1$d objetos apresentado fora do %2$d',
	'UI:DisplayAll' => 'Mostrar todos',
	'UI:CollapseList' => 'Collapse',
	'UI:CountOfResults' => '%1$d objeto(s)',
	'UI:ChangesLogTitle' => 'Alteração log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Alteração log está limpo',
	'UI:SearchFor_Class_Objects' => 'Pesquisa para %1$s objetos',
	'UI:OQLQueryBuilderTitle' => 'Construir consulta OQL',
	'UI:OQLQueryTab' => 'Consulta OQL',
	'UI:SimpleSearchTab' => 'Pesquisa simples',
	'UI:Details+' => 'Detalhes',
	'UI:SearchValue:Any' => '* qualquer *',
	'UI:SearchValue:Mixed' => '* misturado *',
	'UI:SelectOne' => '-- selecione um --',
	'UI:Login:Welcome' => 'Bem-vindo ao iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Usuário/senha incorreto, tente novamente.',
	'UI:Login:IdentifyYourself' => 'Identifique-se antes continuar',
	'UI:Login:UserNamePrompt' => 'Usuário',
	'UI:Login:PasswordPrompt' => 'Senha',
	'UI:Login:ChangeYourPassword' => 'Altere sua senha',
	'UI:Login:OldPasswordPrompt' => 'Senha antiga',
	'UI:Login:NewPasswordPrompt' => 'Nova senha',
	'UI:Login:RetypeNewPasswordPrompt' => 'Repetir nova senha',
	'UI:Login:IncorrectOldPassword' => 'Erro: senha antiga incorreta',
	'UI:LogOffMenu' => 'Sair',
	'UI:LogOff:ThankYou' => 'Obrigado por usuar o sistema',
	'UI:LogOff:ClickHereToLoginAgain' => 'Clique aqui para entrar novamente...',
	'UI:ChangePwdMenu' => 'Alterar senha...',
	'UI:Login:PasswordChanged' => 'Senha configurada com sucesso!',
	'UI:AccessRO-All' => 'iTop somente leitura',
	'UI:AccessRO-Users' => 'iTop somente leitura para usuário final',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nova senha e Repetir nova senha são diferentes. Tente novamente!',
	'UI:Button:Login' => 'Entrar iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop acesso é restrito. Por favor, contacte o administrador.',
	'UI:Login:Error:AccessAdmin' => 'Acesso restrito somente para privilégios administrativo. Por favor, contacte o administrador',
	'UI:CSVImport:MappingSelectOne' => '-- selecione um --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignore este campo --',
	'UI:CSVImport:NoData' => 'Nenhum data configurado..., por favor providencie alguns dados!',
	'UI:Title:DataPreview' => 'Pré-visualizar dados',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Error: The data contains only one column. Did you select the appropriate separator character?',
	'UI:CSVImport:FieldName' => 'Campo %1$d',
	'UI:CSVImport:DataLine1' => 'Linha dado 1',
	'UI:CSVImport:DataLine2' => 'Linha dado 2',
	'UI:CSVImport:idField' => 'id (Chave Primária)',
	'UI:Title:BulkImport' => 'iTop - importar em massa',
	'UI:Title:BulkImport+' => 'CSV Ajuda Importação',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Sincronização de %1$d objetos da classe %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- selecione um --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Erro interno: "%1$s" é um código incorreto porque "%2$s" não é uma chave externa da classe"%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objetos permanecerão inalteradas.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objetos serão modificados.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objetos serão adicionados.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objetos terão erros.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objetos manteve-se inalteradas.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objetos(s) were modified.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objetos foram adicionados.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objetos tinham erros.',
	'UI:Title:CSVImportStep2' => 'Passo 2 de 5: Opções dados CSV',
	'UI:Title:CSVImportStep3' => 'Passo 3 de 5: Papeamento de dados',
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
	'UI:CSVImport:CSVDataPreview' => 'Pré-visualiar dados CSV Data',
	'UI:CSVImport:SelectFile' => 'Selecione o arquivo a importar',
	'UI:CSVImport:Tab:LoadFromFile' => 'Carregar por um arquivo',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiar e colar dados',
	'UI:CSVImport:Tab:Templates' => 'Modelo',
	'UI:CSVImport:PasteData' => 'Colar os dados para importar:',
	'UI:CSVImport:PickClassForTemplate' => 'Escolha o modelo para baixar: ',
	'UI:CSVImport:SeparatorCharacter' => 'Caracter separador:',
	'UI:CSVImport:TextQualifierCharacter' => 'Caracter qualificador de texto',
	'UI:CSVImport:CommentsAndHeader' => 'Comentários e cabeçalho',
	'UI:CSVImport:SelectClass' => 'Selecione a classe para importar:',
	'UI:CSVImport:AdvancedMode' => 'Modo avançado',
	'UI:CSVImport:AdvancedMode+' => 'No modo avançado o "id" (chave primária) dos objetos pode ser usado para atualizar e renomear objetos.' .
									'No entanto, a coluna "id" (se houver) só pode ser usado como um critério de pesquisa e não pode ser combinado com qualquer outro critério de busca.',
	'UI:CSVImport:SelectAClassFirst' => 'Para configurar o mapeamento, selecione uma classe primeira.',
	'UI:CSVImport:HeaderFields' => 'Campos',
	'UI:CSVImport:HeaderMappings' => 'Mapeamentos',
	'UI:CSVImport:HeaderSearch' => 'Pesquisar?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Por favor, selecione um mapeamento para cada campo.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Selecione ao menos um critério de busca',
	'UI:CSVImport:Encoding' => 'Codificação de caracteres',	
	'UI:UniversalSearchTitle' => 'iTop - Pesquisa Universal',
	'UI:UniversalSearch:Error' => 'Erro: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selecione a classe a pesquisar: ',
	
	'UI:Audit:Title' => 'iTop - CMDB Auditoria',
	'UI:Audit:InteractiveAudit' => 'Auditoria Interativa',
	'UI:Audit:HeaderAuditRule' => 'Regra de auditoria',
	'UI:Audit:HeaderNbObjects' => '# Objetos',
	'UI:Audit:HeaderNbErrors' => '# Erros',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Erro na regra %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Erro na categoria %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - Avaliar consultas OQL',
	'UI:RunQuery:QueryExamples' => 'Exemplos de consultas',
	'UI:RunQuery:HeaderPurpose' => 'Propósito',
	'UI:RunQuery:HeaderPurpose+' => 'Explicação sobre a consulta',
	'UI:RunQuery:HeaderOQLExpression' => 'Expressão OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'A consulta na sintaxe OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expressão para avaliar: ',
	'UI:RunQuery:MoreInfo' => 'Mais informações sobre a consulta: ',
	'UI:RunQuery:DevelopedQuery' => 'Expressão de consulta re-desenvolvida: ',
	'UI:RunQuery:SerializedFilter' => 'Filtro serializado: ',
	'UI:RunQuery:Error' => 'Ocorreu um erro ao executar a consulta: %1$s',
	'UI:Query:UrlForExcel' => 'URL a ser usada para consultas web MS-Excel',
	'UI:Schema:Title' => 'iTop esquema de objetos',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relações',
	'UI:Schema:AbstractClass' => 'Classe abstrata: nenhum objeto desta classe pode ser instanciada.',
	'UI:Schema:NonAbstractClass' => 'Classe não-abstrata: os objetos desta classe pode ser instanciada',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarquia de classes',
	'UI:Schema:AllClasses' => 'Todas classes',
	'UI:Schema:ExternalKey_To' => 'Chave externa para %1$s',
	'UI:Schema:Columns_Description' => 'Colunas: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Padrão: "%1$s"',
	'UI:Schema:NullAllowed' => 'Permitido nulo',
	'UI:Schema:NullNotAllowed' => 'Nulo não permitido',
	'UI:Schema:Attributes' => 'Atributos',
	'UI:Schema:AttributeCode' => 'Código atributo',
	'UI:Schema:AttributeCode+' => 'Código interno do atributo',
	'UI:Schema:Label' => 'Rótulo',
	'UI:Schema:Label+' => 'Rótulo do atributo',
	'UI:Schema:Type' => 'Tipo',
	
	'UI:Schema:Type+' => 'Tipo dado do atributo',
	'UI:Schema:Origin' => 'Original',
	'UI:Schema:Origin+' => 'A classe base em que este atributo é definido',
	'UI:Schema:Description' => 'Descrição',
	'UI:Schema:Description+' => 'Descrição do atributo',
	'UI:Schema:AllowedValues' => 'Valores permitidos',
	'UI:Schema:AllowedValues+' => 'Restrições sobre os valores possíveis para este atributo',
	'UI:Schema:MoreInfo' => 'Mais info',
	'UI:Schema:MoreInfo+' => 'Mais informações sobre o campo definido no banco de dados',
	'UI:Schema:SearchCriteria' => 'Pesquisa critério',
	'UI:Schema:FilterCode' => 'Código de filtro',
	'UI:Schema:FilterCode+' => 'Código deste critério de pesquisa',
	'UI:Schema:FilterDescription' => 'Descrição',
	'UI:Schema:FilterDescription+' => 'Descrição deste critério de pesquisa',
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
	'UI:Schema:LifeCycleAttributeMustChange' => 'Tem de mudar',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Usuário será solicitado para alterar o valor',
	'UI:Schema:LifeCycleEmptyList' => 'Lista vazia',
	'UI:LinksWidget:Autocomplete+' => 'Tipo os 3 primeiro caracteres...',
	'UI:Edit:TestQuery' => 'Testar consulta',
	'UI:Combo:SelectValue' => '--- selecione um valor ---',
	'UI:Label:SelectedObjects' => 'Selecionados objetos: ',
	'UI:Label:AvailableObjects' => 'Disponíveis objetos: ',
	'UI:Link_Class_Attributes' => '%1$s atributos',
	'UI:SelectAllToggle+' => 'Marcar todas / Desmarcar todas',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Adicionar %1$s objetos vinculados com %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Adicionar %1$s objetos vinculados com o %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Gerenciar %1$s objetos vinculados com %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Adicionar %1$ss...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Remover objetos selecionados',
	'UI:Message:EmptyList:UseAdd' => 'A lista está vazia, use o botão "Adicionar..." para adicionar elementos.',
	'UI:Message:EmptyList:UseSearchForm' => 'Use o formulário de busca acima para procurar objetos a ser adicionado.',
	'UI:Wizard:FinalStepTitle' => 'Passo final: confirmação',
	'UI:Title:DeletionOf_Object' => 'Apagando de %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Eliminação em massa de %1$d objetos da classe %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Você não tem permissão para excluir este objeto.',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Você não tem permissão para atualizar o(s) seguinte(s) campo(s): %1$s',
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
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s excluídos.',
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
	'UI:Delete:SorryDeletionNotAllowed' => 'Desculpe, você não tem permissão para excluir este objeto, veja as explicações detalhadas acima',
	'UI:Delete:PleaseDoTheManualOperations' => 'Por favor, realize as operações manuais listados acima antes de solicitar a exclusão do referido objeto',
	'UI:Delect:Confirm_Object' => 'Por favor, confirme se você deseja excluir %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Por favor, confirme que você deseja excluir o seguinte %1$d objetos da classe %2$s.',
	'UI:WelcomeToITop' => 'Bem-vindo ao iTop',
	'UI:DetailsPageTitle' => '%1$s - %2$s detalhes',
	'UI:ErrorPageTitle' => 'Erro',
	'UI:ObjectDoesNotExist' => 'Desculpe, este objeto não existe (ou você não tem permissão para vê-lo).',
	'UI:SearchResultsPageTitle' => 'Resultado da pesquisa',
	'UI:Search:NoSearch' => 'Nada a pesquisar de',
	'UI:FullTextSearchTitle_Text' => 'Resultado para "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objeto(s) da classe %2$s encontrado(s).',
	'UI:Search:NoObjectFound' => 'Nenhum objeto encontrado.',
	'UI:ModificationPageTitle_Object_Class' => '%1$s - %2$s modificados',
	'UI:ModificationTitle_Class_Object' => 'Modificação de %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'Clone %1$s - %2$s modificação',
	'UI:CloneTitle_Class_Object' => 'Clone de %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'Criação de um novo %1$s ',
	'UI:CreationTitle_Class' => 'Criação de um novo %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Selecione o tipo de %1$s para criar:',
	'UI:Class_Object_NotUpdated' => 'Nenhuma alteração detectado, %1$s (%2$s) <strong>não</strong> tenha sido modificado.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) atualizada.',
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

	'UI:PageTitle:ClassProjections'	=> 'Gerenciamento Usuários - projeções de classe',
	'UI:PageTitle:ProfileProjections' => 'Gerenciamento Usuários - projeções profile',
	'UI:UserManagement:Class' => 'Classe',
	'UI:UserManagement:Class+' => 'Classe de objetos',
	'UI:UserManagement:ProjectedObject' => 'Objeto',
	'UI:UserManagement:ProjectedObject+' => 'Projeções de objeto',
	'UI:UserManagement:AnyObject' => '* qualquer *',
	'UI:UserManagement:User' => 'Usuário',
	'UI:UserManagement:User+' => 'Usuário(s) envolvido(s) na projeção',
	'UI:UserManagement:Profile' => 'Profile',
	'UI:UserManagement:Profile+' => 'Profile em que a projeção é especificado',
	'UI:UserManagement:Action:Read' => 'Leitura',
	'UI:UserManagement:Action:Read+' => 'Leitura/mostrar objetos',
	'UI:UserManagement:Action:Modify' => 'Modificação',
	'UI:UserManagement:Action:Modify+' => 'Criar e editar (modificar) objetos',
	'UI:UserManagement:Action:Delete' => 'Exclusão',
	'UI:UserManagement:Action:Delete+' => 'Excluir objetos',
	'UI:UserManagement:Action:BulkRead' => 'Leitura em massa (Exportar)',
	'UI:UserManagement:Action:BulkRead+' => 'Listar objetos ou exportar em massa',
	'UI:UserManagement:Action:BulkModify' => 'Modificar em massa',
	'UI:UserManagement:Action:BulkModify+' => 'Criar/editar em massa (importar CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Excluir em massa',
	'UI:UserManagement:Action:BulkDelete+' => 'Excluir objeto(s) em massa',
	'UI:UserManagement:Action:Stimuli' => 'Estímulos',
	'UI:UserManagement:Action:Stimuli+' => 'Permitido ações (composta)',
	'UI:UserManagement:Action' => 'Ação',
	'UI:UserManagement:Action+' => 'Ação realizada pelo usuário',
	'UI:UserManagement:TitleActions' => 'Ação',
	'UI:UserManagement:Permission' => 'Permissão',
	'UI:UserManagement:Permission+' => 'Permissão Usuários',
	'UI:UserManagement:Attributes' => 'Atributos',
	'UI:UserManagement:ActionAllowed:Yes' => 'Sim',
	'UI:UserManagement:ActionAllowed:No' => 'Não',
	'UI:UserManagement:AdminProfile+' => 'Os administradores tem total acesso leitura/gravação para todos os objetos no banco de dados.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Ciclo de vida não tem sido definida para esta classe',
	'UI:UserManagement:GrantMatrix' => 'Permissão concedidas',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Ligação %1$s e %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Ligação entre %1$s e %2$s',
	
	'Menu:AdminTools' => 'Ferramentas Administrativas',
	'Menu:AdminTools+' => 'Ferramentas Administrativas',
	'Menu:AdminTools?' => 'Ferramentas acessíveis apenas para usuários com o perfil do administrador',

	'UI:ChangeManagementMenu' => 'Gerenciamento Mudanças',
	'UI:ChangeManagementMenu+' => 'Gerenciamento Mudanças',
	'UI:ChangeManagementMenu:Title' => 'Visão geral',
	'UI-ChangeManagementMenu-ChangesByType' => 'Mudanças por tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Mudanças por status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Mudanças por grupo de trabalho',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Mudanças ainda não atribuídas',

	'UI:ConfigurationItemsMenu'=> 'Configuração Itens',
	'UI:ConfigurationItemsMenu+'=> 'Todos dispositivos',
	'UI:ConfigurationItemsMenu:Title' => 'Configuração Itens visão geral',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Servidores por criticidade',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PCs por criticidade',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Dispositivos de rede por criticidade',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Aplicativos por criticidade',
	
	'UI:ConfigurationManagementMenu' => 'Gerenciamento Configurações',
	'UI:ConfigurationManagementMenu+' => 'Gerenciamento Configurações',
	'UI:ConfigurationManagementMenu:Title' => 'Infrastructure Overview',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastructure objects by type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastructure objects by status',

'UI:ConfigMgmtMenuOverview:Title' => 'Painel para Gerenciamento Configurações',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuração Itens por status',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuração Itens por tipo',

'UI:RequestMgmtMenuOverview:Title' => 'Painel para Gerenciamento Solicitação',
'UI-RequestManagementOverview-RequestByService' => 'User Requests by service',
'UI-RequestManagementOverview-RequestByPriority' => 'User Requests by priority',
'UI-RequestManagementOverview-RequestUnassigned' => 'User Requests not yet assigned to an agent',

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
	'UI:ContactsMenu:Title' => 'Visão global Contatos',
	'UI-ContactsMenu-ContactsByLocation' => 'Contatos por localização',
	'UI-ContactsMenu-ContactsByType' => 'Contatos por tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contatos por status',

	'Menu:CSVImportMenu' => 'Importar CSV',
	'Menu:CSVImportMenu+' => 'Criação ou atualização em massa',
	
	'Menu:DataModelMenu' => 'Modelo Dados',
	'Menu:DataModelMenu+' => 'Visão global do Modelo Dados',
	
	'Menu:ExportMenu' => 'Exportar',
	'Menu:ExportMenu+' => 'Exportar o resultado de qualquer consulta em HTML, CSV ou XML',
	
	'Menu:NotificationsMenu' => 'Notificações',
	'Menu:NotificationsMenu+' => 'Configuração de Notificações',
	'UI:NotificationsMenu:Title' => 'Configuração de <span class="hilite">Notificação</span>',
	'UI:NotificationsMenu:Help' => 'Ajuda',
	'UI:NotificationsMenu:HelpContent' => '<p>Em iTop as Notificações são totalmente personalizáveis​​. Elas são baseadas em dois conjuntos de objetos: <i>Gatilhos e Ações</i>.</p>
<p><i><b>Gatilhos</b></i> define quando uma notificação será executado. Existem 3 tipos de gatilhos para cobrir três fases diferentes de um ciclo de vida do objeto:
<ol>
	<li>o "OnCreate" é executado quando um objeto da classe especificada é criado</li>
	<li>o "OnStateEnter" é executado antes de um objeto de determinada classe entra em um estado especificado (provenientes de outro estado)</li>
	<li>o "OnStateLeave" é executado quando um objeto de determinada classe está deixando um estado especificado</li>
</ol>
</p>
<p>
<i><b>Ações</b></i> define as ações a serem executadas quando os Gatilhos entra em execução. Por enquanto há apenas um tipo de ação que consiste no envio de uma mensagem de e-mail.
Tais Ações também define o modelo a ser usado para enviar o e-mail, assim como os outros parâmetros da mensagem, como os destinatários, importância, etc.
</p>
<p>Uma página especial: <a href="../setup/email.test.php" target="_blank">email.test.php</a> está disponível para testes e resolução de problemas de configuração de correio PHP.</p>
<p>Para executar, ações devem ser associadas aos gatilhos.
Quando associada a um gatilho, cada ação é dada um número "ordem", especificando que ordem em que as ações devem ser executadas.</p>',
	'UI:NotificationsMenu:Triggers' => 'Gatilhos',
	'UI:NotificationsMenu:AvailableTriggers' => 'Gatilhos disponíveis',
	'UI:NotificationsMenu:OnCreate' => 'Quando um objeto é criado',
	'UI:NotificationsMenu:OnStateEnter' => 'Quando um objeto entra em um determinado estado',
	'UI:NotificationsMenu:OnStateLeave' => 'Quando um objeto deixa um determinado estado',
	'UI:NotificationsMenu:Actions' => 'Ações',
	'UI:NotificationsMenu:AvailableActions' => 'Ações disponíveis',
	
	'Menu:AuditCategories' => 'Categoria Auditorias',
	'Menu:AuditCategories+' => 'Categoria Auditorias',
	'Menu:Notifications:Title' => 'Categoria Auditorias',
	
	'Menu:RunQueriesMenu' => 'Executar consultas',
	'Menu:RunQueriesMenu+' => 'Executar qualquer consulta',
	
	'Menu:QueryMenu' => 'Consulta  definida',
	'Menu:QueryMenu+' => 'Consulta definida',
	
	'Menu:DataAdministration' => 'Administração Dados',
	'Menu:DataAdministration+' => 'Administração Dados',
	
	'Menu:UniversalSearchMenu' => 'Pesquisa Universal',
	'Menu:UniversalSearchMenu+' => 'Pesquisa de nada...',
	
	'Menu:ApplicationLogMenu' => 'Log de l\'application',
	'Menu:ApplicationLogMenu+' => 'Log de l\'application',
	'Menu:ApplicationLogMenu:Title' => 'Log de l\'application',

	'Menu:UserManagementMenu' => 'Gerenciamento Usuários',
	'Menu:UserManagementMenu+' => 'Gerenciamento Usuários',

	'Menu:ProfilesMenu' => 'Profiles',
	'Menu:ProfilesMenu+' => 'Profiles',
	'Menu:ProfilesMenu:Title' => 'Profiles',

	'Menu:UserAccountsMenu' => 'Contas usuários',
	'Menu:UserAccountsMenu+' => 'Contas usuários',
	'Menu:UserAccountsMenu:Title' => 'Contas usuários',	

	'UI:iTopVersion:Short' => 'iTop versão %1$s',
	'UI:iTopVersion:Long' => 'iTop versão %1$s-%2$s built on %3$s',
	'UI:PropertiesTab' => 'Propriedade',

	'UI:OpenDocumentInNewWindow_' => 'Abrir este documento em uma nova janela: %1$s',
	'UI:DownloadDocument_' => 'Baixar este documento: %1$s',
	'UI:Document:NoPreview' => 'Nenhum pré-visualização está estabelecida para este documento',

	'UI:DeadlineMissedBy_duration' => 'Perdida por %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',	
	'UI:Deadline_Minutes' => '%1$d min',		
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Ajuda',
	'UI:PasswordConfirm' => '(Confirma)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Antes de adicionar mais %1$s objetos, salvar este objeto.',
	'UI:DisplayThisMessageAtStartup' => 'Exibir esta mensagem na inicialização',
	'UI:RelationshipGraph' => 'Visualização gráfica',
	'UI:RelationshipList' => 'Lista',
	'UI:OperationCancelled' => 'Operação cancelada',
	'UI:ElementsDisplayed' => 'Filtragem',

	'Portal:Title' => 'Portal usuário',
	'Portal:Refresh' => 'Atualizar',
	'Portal:Back' => 'Voltar',
	'Portal:WelcomeUserOrg' => 'Bem-vindo %1$s, de %2$s',
	'Portal:ShowOngoing' => 'Mostrar solicitações abertas',
	'Portal:ShowClosed' => 'Mostrar solicitações fechadas',
	'Portal:CreateNewRequest' => 'Criar uma nova solicitação',
	'Portal:ChangeMyPassword' => 'Alterar minha senha',
	'Portal:Disconnect' => 'Desconectar',
	'Portal:OpenRequests' => 'Minhas solicitações abertas',
	'Portal:ClosedRequests'  => 'Minhas solicitações fechadas',
	'Portal:ResolvedRequests'  => 'Minhas solicitações resolvidas',
	'Portal:SelectService' => 'Selecione um serviço de um catálogo:',
	'Portal:PleaseSelectOneService' => 'Selecione um serviço',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecione um sub-serviço do serviço %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Selecione uma sub-categoria',
	'Portal:DescriptionOfTheRequest' => 'Digite a descrição de sua solicitação:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detalhe da solicitação %1$s:',
	'Portal:NoOpenRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:NoClosedRequest' => 'Nenhuma solicitação nesta categoria',
	'Portal:Button:ReopenTicket' => 'Re-abrir solicitação',
	'Portal:Button:CloseTicket' => 'Fechar esta solicitação',
	'Portal:Button:UpdateRequest' => 'Atualize a solicitação',
	'Portal:EnterYourCommentsOnTicket' => 'Digite seu comentário sobre a resolução/solução de sua solicitação:',
	'Portal:ErrorNoContactForThisUser' => 'Erro: o usuário atual não esta associado com um contato/pessoa. Por favor, contacte o administrador.',
	'Portal:Attachments' => 'Anexos',
	'Portal:AddAttachment' => ' Adicionar Anexo ',
	'Portal:RemoveAttachment' => ' Remover Anexo ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Anexo #%1$d para %2$s (%3$s)',
	'Enum:Undefined' => 'Indefinido',	
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Dias %2$s Horas %3$s Minutos %4$s Segundos',
	'UI:ModifyAllPageTitle' => 'Modificar Tudo',
	'UI:Modify_N_ObjectsOf_Class' => 'Modificando %1$d objetos da classe %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modificando %1$d objetos da classe %2$s fora de %3$d',
	'UI:Menu:ModifyAll' => 'Modificar...',
	'UI:Button:ModifyAll' => 'Modificar Tudo',
	'UI:Button:PreviewModifications' => 'Pré-visualizar modificações >>',
	'UI:ModifiedObject' => 'Objeto modificado',
	'UI:BulkModifyStatus' => 'Operação',
	'UI:BulkModifyStatus+' => 'Status da operação',
	'UI:BulkModifyErrors' => 'Erros (se qualquer)',
	'UI:BulkModifyErrors+' => 'Erros de impedir a modificação',	
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Erro',
	'UI:BulkModifyStatusModified' => 'Modificado',
	'UI:BulkModifyStatusSkipped' => 'Pulado',
	'UI:BulkModify_Count_DistinctValues' => '%1$d valores distintos:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d tempo(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d mais valores...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Tentativa de definir o campo somente leitura: %1$s',
	'UI:FailedToApplyStimuli' => 'A ação falhou.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modificando %2$d objetos da classe %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Digite seu texto aqui:',
	'UI:CaseLog:DateFormat' => 'Y-m-d H:i:s',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Valor inicial:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'O campo %1$s não é editável, porque é originado pela sincronização de dados. Valor não definido.',
	'UI:ActionNotAllowed' => 'Você não tem permissão para executar esta ação nesses objetos.',
	'UI:BulkAction:NoObjectSelected' => 'Por favor, selecione pelo menos um objeto para realizar esta operação',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objetos (%2$s objetos selecionados).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objetos.',
	'UI:Pagination:PageSize' => '%1$s objetos por página',
	'UI:Pagination:PagesLabel' => 'Páginas:',
	'UI:Pagination:All' => 'Todos',
	'UI:HierarchyOf_Class' => 'Hierarquia de %1$s',
	'UI:Preferences' => 'Preferências...',
	'UI:FavoriteOrganizations' => 'Minha(s) Organização(ões) Favoritas',
	'UI:FavoriteOrganizations+' => 'Verifique na lista abaixo as organizações que você deseja ver no menu drop-down para um acesso rápido. '.
								   'Note-se que esta não é uma configuração de segurança, objetos de qualquer organização ainda são visíveis e podem ser acessadas selecionando a opção "Todas as Organizações" na lista drop-down.',
	'UI:NavigateAwayConfirmationMessage' => 'Qualquer modificação será descartada.',
	'UI:Create_Class_InState' => 'Criar o %1$s em estado: ',
	'UI:Button:Refresh' => 'Atualizar',
));
?>

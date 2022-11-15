<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Core:DeletedObjectLabel' => '%1s (excluído)',
	'Core:DeletedObjectTip'   => 'O objeto foi excluído em %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objeto não encontrado (classe: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'O objeto não pode ser encontrado. Ele pode ter sido eliminado há algum tempo e o log foi removido desde então',

	'Core:UniquenessDefaultError' => 'Regra de exclusividade \'%1$s\' com erro',
	'Core:CheckConsistencyError'  => 'Regras de consistência não seguidas: %1$s',
	'Core:CheckValueError'        => 'Valor inesperado para o atributo \'%1$s\' (%2$s) : %3$s~~',

	'Core:AttributeLinkedSet' => 'Array de objetos',
	'Core:AttributeLinkedSet+' => 'Quaisquer tipos de objetos da mesma classe ou subclasses',

	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicatas no campo \'%1$s\' : %2$s',

	'Core:AttributeDashboard' => 'Painel do '.ITOP_APPLICATION_SHORT,
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber'  => 'Número de telefone',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Data de obsolescência',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Lista de tags',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'clique para adicionar',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s de %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s de classes filhas)',

	'Core:AttributeCaseLog' => 'Log',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Enum Computado',
	'Core:AttributeMetaEnum+' => 'Exibir Strings alfanuméricas computadas',

	'Core:AttributeLinkedSetIndirect' => 'Array de objetos (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Qualquer tipo de objetos [subclasse] da mesma classe',

	'Core:AttributeInteger' => 'Inteiro',
	'Core:AttributeInteger+' => 'Valor numérico (não pode ser negativo)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Valor decimal (não pode ser negativo)',

	'Core:AttributeBoolean' => 'Booleano',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Sim',
	'Core:AttributeBoolean/Value:no' => 'Não',

	'Core:AttributeArchiveFlag' => 'Flag de arquivamento',
	'Core:AttributeArchiveFlag/Value:yes' => 'Sim',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Este objeto é visível apenas no modo de arquivamento',
	'Core:AttributeArchiveFlag/Value:no' => 'Não',
	'Core:AttributeArchiveFlag/Label' => 'Arquivado',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Data de arquivamento',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Flag de obsolescência',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Sim',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Este objeto será excluído da análise de impacto e ocultado dos resultados de pesquisa',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Não',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsoleto',
	'Core:AttributeObsolescenceFlag/Label+' => 'Calculado dinamicamente com base em outros atributos do objeto',
	'Core:AttributeObsolescenceDate/Label' => 'Data de obsolescência',
	'Core:AttributeObsolescenceDate/Label+' => 'Data aproximada em que o objeto foi considerado obsoleto',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Sequência alfanumérica',

	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => '',

	'Core:AttributeApplicationLanguage' => 'Idioma do usuário',
	'Core:AttributeApplicationLanguage+' => 'Idioma e país (por exemplo: EN US)',

	'Core:AttributeFinalClass' => 'Classe (automática)',
	'Core:AttributeFinalClass+' => 'Classe real do objeto (criada automaticamente pelo sistema)',

	'Core:AttributePassword' => 'Senha',
	'Core:AttributePassword+' => 'Senha para o dispositivo externo',

	'Core:AttributeEncryptedString' => 'String encriptada',
	'Core:AttributeEncryptedString+' => 'String encriptada com uma chave local',
	'Core:AttributeEncryptUnknownLibrary' => 'Biblioteca de criptografia especificada (%1$s) desconhecida',
	'Core:AttributeEncryptFailedToDecrypt' => '** erro de decriptação **',

	'Core:AttributeText' => 'Texto',
	'Core:AttributeText+' => 'Cadeia de caracteres Multi-linha',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'String HTML',

	'Core:AttributeEmailAddress' => 'Endereço de e-mail',
	'Core:AttributeEmailAddress+' => '',

	'Core:AttributeIPAddress' => 'Endereço IP',
	'Core:AttributeIPAddress+' => '',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Expressão Object Query Language (OQL)',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista de sequências alfanuméricas pré-definidas',

	'Core:AttributeTemplateString' => 'String do modelo',
	'Core:AttributeTemplateString+' => 'String de uma linha, contendo espaços reservados para dados do '.ITOP_APPLICATION_SHORT,

	'Core:AttributeTemplateText' => 'Texto do modelo',
	'Core:AttributeTemplateText+' => 'Texto contendo espaços reservados para dados do '.ITOP_APPLICATION_SHORT,

	'Core:AttributeTemplateHTML' => 'HTML do modelo',
	'Core:AttributeTemplateHTML+' => 'Código HTML contendo espaços reservados para dados do '.ITOP_APPLICATION_SHORT,

	'Core:AttributeDateTime' => 'Data/hora',
	'Core:AttributeDateTime+' => 'Data e hora (ano-mês-dia hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Data formato:<br/>
	<b>%1$s</b><br/>
	Exemplo: %2$s
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>
<p>
Se o tempo for omitido, o padrão é 00:00:00
</p>',

	'Core:AttributeDate' => 'Data',
	'Core:AttributeDate+' => 'Data (ano-mês-dia)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Data formato:<br/>
	<b>%1$s</b><br/>
	Exemplo: %2$s
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Prazo determinado',
	'Core:AttributeDeadline+' => 'Data, apresentada relativamente ao tempo atual',

	'Core:AttributeExternalKey' => 'Chave externa',
	'Core:AttributeExternalKey+' => 'Chave externa (ou foreign)',

	'Core:AttributeHierarchicalKey' => 'Chave hierárquica',
	'Core:AttributeHierarchicalKey+' => 'Chave externa (ou foreign key) para o objeto pai',

	'Core:AttributeExternalField' => 'Campo externo',
	'Core:AttributeExternalField+' => 'Campo mapeado para uma chave externa',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL absoluto ou relativo como texto',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Qualquer conteúdo binário (documento)',

	'Core:AttributeOneWayPassword' => 'Senha criptografada',
	'Core:AttributeOneWayPassword+' => 'Uma senha encriptada de uma só via (one-way)',

	'Core:AttributeTable' => 'Tabela',
	'Core:AttributeTable+' => 'Matriz indexada com duas dimensões',

	'Core:AttributePropertySet' => 'Propriedades',
	'Core:AttributePropertySet+' => 'Lista de propriedades sem categoria (nome e valor)',

	'Core:AttributeFriendlyName' => 'Nome amigável',
	'Core:AttributeFriendlyName+' => 'Atributo criado automaticamente; o nome amigável é baseado nos diferentes atributos do objeto',

	'Core:FriendlyName-Label' => 'Nome amigável',
	'Core:FriendlyName-Description' => '',

	'Core:AttributeTag' => 'Etiquetas',
	'Core:AttributeTag+' => '',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Console',
	'Core:Context=CRON' => 'cron',
	'Core:Context=GUI:Portal' => 'Portal do usuário',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChange' => 'Alterações no CMDB',
	'Class:CMDBChange+' => 'Controle de mudanças no CMDB',
	'Class:CMDBChange/Attribute:date' => 'Data',
	'Class:CMDBChange/Attribute:date+' => 'Data e hora em que as alterações foram registradas',
	'Class:CMDBChange/Attribute:userinfo' => 'Informações adicionais',
	'Class:CMDBChange/Attribute:userinfo+' => 'Informações definidas pelos solicitantes',
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'Interação do usuário (GUI)',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'Script de importação CSV',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'Importação de CSV interativa (GUI)',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Processamento de e-mail',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Origem de dados Synchro',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webservices',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP WebServices',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'Por uma extensão',
));

//
// Class: CMDBChangeOp
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOp' => 'Operações de alteração',
	'Class:CMDBChangeOp+' => 'Operações de controle de alteração',
	'Class:CMDBChangeOp/Attribute:change' => 'Alteração',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'Data',
	'Class:CMDBChangeOp/Attribute:date+' => 'Data e hora da alteração',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Usuário',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Quem fez essa alteração',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Classe do objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'ID do objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpCreate' => 'Criação de objeto',
	'Class:CMDBChangeOpCreate+' => 'Controle de criação do objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpDelete' => 'Exclusão de objeto',
	'Class:CMDBChangeOpDelete+' => 'Controle de exclusão do objeto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttribute' => 'Alteração de propriedades',
	'Class:CMDBChangeOpSetAttribute+' => 'Controle de alteração de propriedades do objeto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Código da propriedade modificada',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Alteração de propriedades escalares',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Controle de alterações de propriedades escalares do objeto',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Valor anterior do atributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Novo valor',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Novo valor do atributo',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Change:ObjectCreated' => 'Objeto criado',
	'Change:ObjectDeleted' => 'Objeto excluído',
	'Change:ObjectModified' => 'Objeto modificado',
	'Change:TwoAttributesChanged' => 'Modificado %1$s e %2$s',
	'Change:ThreeAttributesChanged' => 'Modificado %1$s, %2$s e 1 outro',
	'Change:FourOrMoreAttributesChanged' => 'Modificado %1$s, %2$s e %3$s outros',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s definido para %2$s (valor anterior: %3$s)',
	'Change:AttName_SetTo' => '%1$s definido para %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s anexado a(o) %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificado(a), valor anterior: %2$s',
	'Change:AttName_Changed' => '%1$s modificado(a)',
	'Change:AttName_EntryAdded' => '%1$s modificado(a), nova entrada adicionada: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Modificado de %2$s para %1$s',
	'Change:LinkSet:Added' => 'adicionado %1$s',
	'Change:LinkSet:Removed' => 'excluído %1$s',
	'Change:LinkSet:Modified' => 'modificado %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Alteração de conteúdo (Blob)',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Controle de alterações de conteúdo de dados (Blob)',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Conteúdo anterior do atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Alteração de texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'Controle de alterações de texto do objeto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Conteúdo anterior do atributo',
));

//
// Class: Event
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Event' => 'Registro de evento',
	'Class:Event+' => 'Um evento interno do aplicativo',
	'Class:Event/Attribute:message' => 'Mensagem',
	'Class:Event/Attribute:message+' => 'Descrição curta deste evento',
	'Class:Event/Attribute:date' => 'Data',
	'Class:Event/Attribute:date+' => 'Data e hora em que o evento foi registrado',
	'Class:Event/Attribute:userinfo' => 'Informações do usuário',
	'Class:Event/Attribute:userinfo+' => 'Identificação do usuário que estava executando a ação que desencadeou este evento',
	'Class:Event/Attribute:finalclass' => 'Tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotification' => 'Notificação de evento',
	'Class:EventNotification+' => 'Controle de notificações que foram enviadas',
	'Class:EventNotification/Attribute:trigger_id' => 'Gatilho',
	'Class:EventNotification/Attribute:trigger_id+' => 'Conta de usuário',
	'Class:EventNotification/Attribute:action_id' => 'Usuário',
	'Class:EventNotification/Attribute:action_id+' => 'Conta de usuário',
	'Class:EventNotification/Attribute:object_id' => 'ID do objeto',
	'Class:EventNotification/Attribute:object_id+' => 'ID do objeto (classe definida pelo gatilho?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotificationEmail' => 'Evento de envio de e-mail',
	'Class:EventNotificationEmail+' => 'Controle de e-mails que foram enviados',
	'Class:EventNotificationEmail/Attribute:to' => 'Para',
	'Class:EventNotificationEmail/Attribute:to+' => 'Endereço(s) de e-mail do(s) destinatário(s)',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'Endereço(s) de e-mail do(s) destinaráio(s) com cópia',
	'Class:EventNotificationEmail/Attribute:bcc' => 'CCO',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'Endereço(s) de e-mail do(s) destinatário(s) com cópia oculta',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => 'Remetente do e-mail',
	'Class:EventNotificationEmail/Attribute:subject' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Título do e-mail',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => 'Conteúdo do e-mail',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Anexos',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventIssue' => 'Evento de entrega',
	'Class:EventIssue+' => 'Controle de entrega (aviso, erro, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Entrega',
	'Class:EventIssue/Attribute:issue+' => 'O que aconteceu',
	'Class:EventIssue/Attribute:impact' => 'Impacto',
	'Class:EventIssue/Attribute:impact+' => 'Quais são as consequências',
	'Class:EventIssue/Attribute:page' => 'Página',
	'Class:EventIssue/Attribute:page+' => 'Ponto de entrada HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Argumentos POST',
	'Class:EventIssue/Attribute:arguments_post+' => 'Argumentos HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Argumentos URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Argumentos HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Quantidade de solicitações',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Dados',
	'Class:EventIssue/Attribute:data+' => 'Mais informações',
));

//
// Class: EventWebService
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventWebService' => 'Evento Web service',
	'Class:EventWebService+' => 'Controle de uma solicitação de WebService',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Nome da operação',
	'Class:EventWebService/Attribute:result' => 'Resultado',
	'Class:EventWebService/Attribute:result+' => 'Sucesso/erro geral',
	'Class:EventWebService/Attribute:log_info' => 'Log de resultado',
	'Class:EventWebService/Attribute:log_info+' => '',
	'Class:EventWebService/Attribute:log_warning' => 'Log de alerta',
	'Class:EventWebService/Attribute:log_warning+' => '',
	'Class:EventWebService/Attribute:log_error' => 'Log de erro',
	'Class:EventWebService/Attribute:log_error+' => '',
	'Class:EventWebService/Attribute:data' => 'Dados',
	'Class:EventWebService/Attribute:data+' => 'Mais informações',
));

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventRestService' => 'Chamada REST/JSON',
	'Class:EventRestService+' => 'Controle de uma chamada de serviço REST/JSON',
	'Class:EventRestService/Attribute:operation' => 'Operação',
	'Class:EventRestService/Attribute:operation+' => 'Argumento \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Versão',
	'Class:EventRestService/Attribute:version+' => 'Argumento \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Input',
	'Class:EventRestService/Attribute:json_input+' => 'Argumento \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Código',
	'Class:EventRestService/Attribute:code+' => 'Código de resultado',
	'Class:EventRestService/Attribute:json_output' => 'Resposta',
	'Class:EventRestService/Attribute:json_output+' => 'Resposta HTTP (JSON)',
	'Class:EventRestService/Attribute:provider' => 'Provedor',
	'Class:EventRestService/Attribute:provider+' => 'Classe PHP implementando a operação esperada',
));

//
// Class: EventLoginUsage
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventLoginUsage' => 'Logins',
	'Class:EventLoginUsage+' => 'Conexões com a aplicação',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Nome de usuário',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'E-mail do usuário',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Endereço de e-mail deste usuário',
));

//
// Class: Action
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Action' => 'Ação personalizada',
	'Class:Action+' => 'Ações definidas pelo usuário',
	'Class:Action/Attribute:name' => 'Nome',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descrição',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'Ativo ou ?',
	'Class:Action/Attribute:status/Value:test' => 'Em homologação',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'Ativo',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inativo',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Gatilhos relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Gatilhos associadas à esta ação',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Aviso, nenhum gatilho está associado à ação. Não será ativo até que esta ação tenha pelo menos um gatilho associado',
));

//
// Class: ActionNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ActionNotification' => 'Notificação',
	'Class:ActionNotification+' => 'Notificação (resumo)',
));

//
// Class: ActionEmail
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ActionEmail' => 'Notificação via E-mail',
	'Class:ActionEmail+' => 'Lista de Notificações via E-mail',
	'Class:ActionEmail/Attribute:status+' => 'Esse status especifica quem será notificado: apenas o destinatário do Teste, todos (Para, CC e CCO) ou ninguém',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Apenas o destinatário de teste é notificado',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Todos os endereços de e-mails dos campos Para, CC e CCO são notificados',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'A notificação de e-mail não será enviada',
	'Class:ActionEmail/Attribute:test_recipient' => 'Destinatário de teste',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinatário caso o status esteja definido como "teste"',
	'Class:ActionEmail/Attribute:from' => 'De',
	'Class:ActionEmail/Attribute:from+' => 'Endereço de e-mail do remetente enviado no cabeçalho do e-mail',
	'Class:ActionEmail/Attribute:from_label' => 'De (campo)',
	'Class:ActionEmail/Attribute:from_label+' => 'Nome de exibição enviado no cabeçalho do e-mail',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder para',
	'Class:ActionEmail/Attribute:reply_to+' => 'Endereço de e-mail enviado no cabeçalho do e-mail',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Responder para (campo)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Nome de exibição enviado no cabeçalho do e-mail',
	'Class:ActionEmail/Attribute:to' => 'Para',
	'Class:ActionEmail/Attribute:to+' => 'Endereço(s) de e-mail do(s) destinatário(s)',
	'Class:ActionEmail/Attribute:cc' => 'CC',
	'Class:ActionEmail/Attribute:cc+' => 'Endereço(s) de e-mail do(s) destinaráio(s) com cópia',
	'Class:ActionEmail/Attribute:bcc' => 'CCO',
	'Class:ActionEmail/Attribute:bcc+' => 'Endereço(s) de e-mail do(s) destinatário(s) com cópia oculta',
	'Class:ActionEmail/Attribute:subject' => 'Assunto',
	'Class:ActionEmail/Attribute:subject+' => 'Título do e-mail',
	'Class:ActionEmail/Attribute:body' => 'Corpo',
	'Class:ActionEmail/Attribute:body+' => 'Conteúdo do e-mail',
	'Class:ActionEmail/Attribute:importance' => 'Prioridade',
	'Class:ActionEmail/Attribute:importance+' => '',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Baixa',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Alta',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
));

//
// Class: Trigger
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Trigger' => 'Gatilho',
	'Class:Trigger+' => 'Manipulador de eventos personalizado',
	'Class:Trigger/Attribute:description' => 'Descrição',
	'Class:Trigger/Attribute:description+' => 'Uma descrição curta',
	'Class:Trigger/Attribute:action_list' => 'Ações desencadeadas',
	'Class:Trigger/Attribute:action_list+' => 'Ações executadas quando o gatilho é acionado',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Contexto',
	'Class:Trigger/Attribute:context+' => 'Contexto para permitir o acionamento do gatilho',
));

//
// Class: TriggerOnObject
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObject' => 'Gatilho (classe dependente)',
	'Class:TriggerOnObject+' => 'Gatilho em uma determinada classe de objetos',
	'Class:TriggerOnObject/Attribute:target_class' => 'Classe alvo',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filtro',
	'Class:TriggerOnObject/Attribute:filter+' => 'Limita a lista de objetos (da classe de destino) que irá ativar o gatilho',
	'TriggerOnObject:WrongFilterQuery' => 'Consulta de filtro incorreta: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'A consulta de filtro deve retornar objetos da classe \\"%1$s\\"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnPortalUpdate' => 'Gatilho (quando atualizado a partir do portal do usuário)',
	'Class:TriggerOnPortalUpdate+' => 'Gatilho acionado a partir de uma atualização do usuário final através do portal do usuário',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateChange' => 'Gatilho (na mudança de status)',
	'Class:TriggerOnStateChange+' => 'Gatilho de mudança de status do objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Status',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateEnter' => 'Gatilho (ao entrar em um status)',
	'Class:TriggerOnStateEnter+' => 'Gatilho de mudança de status do objeto - entrada',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateLeave' => 'Gatilho (ao sair de um status)',
	'Class:TriggerOnStateLeave+' => 'Gatilho de mudança de status do objeto - saída',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectCreate' => 'Gatilho (na criação do objeto)',
	'Class:TriggerOnObjectCreate+' => 'Gatilho de criação de objeto de [uma classe filha] de determinada classe',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectDelete' => 'Gatilho (na exclusão de objetos)',
	'Class:TriggerOnObjectDelete+' => 'Gatilho na exclusão de objeto de [uma classe filha] de determinada classe',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectUpdate' => 'Gatilho (na atualização do objeto)',
	'Class:TriggerOnObjectUpdate+' => 'Gatilho na atualização de objeto de [uma classe filha] de uma determinada classe',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Campos de destino',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectMention' => 'Gatilho (na menção do objeto)',
	'Class:TriggerOnObjectMention+' => 'Gatilho em menção (@xxx) de um objeto de [uma classe filha] de uma determinada classe em um atributo de log',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Filtro de menções',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limita a lista de objetos mencionados que ativarão o gatilho. Se vazio, qualquer objeto mencionado (de qualquer classe) irá ativá-lo',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnThresholdReached' => 'Gatilho (no alcance do limite)',
	'Class:TriggerOnThresholdReached+' => 'Gatilho no alcance do limite do cronômetro',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Cronômetro',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Limite',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkTriggerAction' => 'Ação/Gatilho',
	'Class:lnkTriggerAction+' => 'Link Gatilho / Ação',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Ação',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Ação a ser executada',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Ação',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Gatilho',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Gatilho',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Ordem',
	'Class:lnkTriggerAction/Attribute:order+' => 'Ordem de execução das ações',
));

//
// Synchro Data Source
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:SynchroDataSource/Attribute:name' => 'Nome',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Descrição',
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Classe alvo',
	'Class:SynchroDataSource/Attribute:user_id' => 'Usuário',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contato para notificação',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contato para notificar em caso de erro',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Ícone de Hiperlink',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hiperlink de uma pequena imagem representando o aplicativo com o qual o '.ITOP_APPLICATION_SHORT.' é sincronizado',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hiperlink de aplicativo',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperlink para o objeto na aplicação externa com a qual o '.ITOP_APPLICATION_SHORT.' é sincronizado (se aplicável). As substituições possíveis: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Política de reconciliação',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Intervalo de obsolescência programada',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Um objeto é considerado obsoleto se não aparecer nos dados além desse tempo',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Ação sobre zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Medidas tomadas quando a busca retorna nenhum objeto',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Ação em um',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Medidas tomadas quando a busca retorna exatamente um único objeto',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Ação em muitos',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Medidas tomadas quando a busca retorna mais de um objeto',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Usuários permitidos',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Quem tem permissão para excluir objetos sincronizados',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Ninguém',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Somente administradores',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Todos os usuários',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Regras de atualização',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sintaxe: nome_do_campo:valor; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Duração da retenção',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Quanto tempo um objeto obsoleto é mantido antes de ser excluído',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Tabela do banco de dados',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Nome da tabela para armazenar os dados de sincronização. Se for deixado vazio, um nome padrão será computado',
	'SynchroDataSource:Description' => 'Descrição',
	'SynchroDataSource:Reconciliation' => 'Pesquisa &amp; reconciliação',
	'SynchroDataSource:Deletion' => 'Regras de exclusão',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Informação',
	'SynchroDataSource:Definition' => 'Definição',
	'Core:SynchroAttributes' => 'Atributos',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Erros',
	'Core:Synchro:CreatedLabel' => 'Criado',
	'Core:Synchro:ModifiedLabel' => 'Modificado',
	'Core:Synchro:UnchangedLabel' => 'Inalterado',
	'Core:Synchro:ReconciledErrorsLabel' => 'Erros',
	'Core:Synchro:ReconciledLabel' => 'Reconciliados',
	'Core:Synchro:ReconciledNewLabel' => 'Criado',
	'Core:SynchroReconcile:Yes' => 'Sim',
	'Core:SynchroReconcile:No' => 'Não',
	'Core:SynchroUpdate:Yes' => 'Sim',
	'Core:SynchroUpdate:No' => 'Não',
	'Core:Synchro:LastestStatus' => 'Último Status',
	'Core:Synchro:History' => 'Histórico de sincronização',
	'Core:Synchro:NeverRun' => 'Esta sincronização nunca foi executada. Sem registro ainda',
	'Core:Synchro:SynchroEndedOn_Date' => 'A última sincronização terminou em %1$s',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'A sincronização iniciou em %1$s ainda está em execução...',
	'Menu:DataSources' => 'Fontes de Sincronização de Dados', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Lista de Fontes de Sincronização de Dados', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignoradas (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Desaparecido (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Saindo (%1$s)',
	'Core:Synchro:label_repl_new' => 'Novo (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Excluído (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleto (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Erros (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Nenhuma ação (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Inalterado (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Atualizado (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Erros (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Inalterado (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Atualizado (%1$s)',
	'Core:Synchro:label_obj_created' => 'Criado (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Erros (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Réplica processada: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Pelo menos uma chave de reconciliação deve ser especificada, ou a política de reconciliação deve ser a de usar a chave primária',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Um período de retenção de exclusão deve ser especificado, já que objetos devem ser excluídos depois de serem marcados como obsoletos',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Objetos obsoletos devem ser atualizados, mas nenhuma política de atualização foi especificada',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'A tabela "%1$s" já existe no banco de dados. Por favor, use um outro nome para a tabela de dados sincronizada',
	'Core:SynchroReplica:PublicData' => 'Dados públicos',
	'Core:SynchroReplica:PrivateDetails' => 'Detalhes privados',
	'Core:SynchroReplica:BackToDataSource' => 'Voltar para a fonte de dados sincronização: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista de réplica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'ID (chave primária)',
	'Core:SynchroAtt:attcode' => 'Atributo',
	'Core:SynchroAtt:attcode+' => 'Campo do objeto',
	'Core:SynchroAtt:reconciliation' => 'Reconciliação?',
	'Core:SynchroAtt:reconciliation+' => 'Usado para pesquisa',
	'Core:SynchroAtt:update' => 'Atualizar?',
	'Core:SynchroAtt:update+' => 'Usado para atualizar o objeto',
	'Core:SynchroAtt:update_policy' => 'Política de atualização',
	'Core:SynchroAtt:update_policy+' => 'Comportamento do campo atualizado',
	'Core:SynchroAtt:reconciliation_attcode' => 'Chave de reconciliação',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Código de atributo para a reconciliação de chave externa',
	'Core:SyncDataExchangeComment' => '(Sincronização dado)',
	'Core:Synchro:ListOfDataSources' => 'Lista de fontes de dados:',
	'Core:Synchro:LastSynchro' => 'Última sincronização:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Este objeto é sincronizado com uma fonte de dados externa',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'O objeto foi <b>criado</b> pela fonte de dados externa "%1$s"',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'O objeto <b>não pode ser excluído</b> pela fonte de dados externa "%1$s"',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Você <b>não pode excluir o objeto</b> porque é propriedade de uma fonte de dados externa "%1$s"',
	'TitleSynchroExecution' => 'Execução da sincronização',
	'Class:SynchroDataSource:DataTable' => 'Tabela do banco de dados: "%1$s"',
	'Core:SyncDataSourceObsolete' => 'A fonte de dados está marcada como obsoleta. Operação cancelada',
	'Core:SyncDataSourceAccessRestriction' => 'Adminstradores ou apenas o usuário especificado na fonte de dados pode executar esta operação. Operação cancelada',
	'Core:SyncTooManyMissingReplicas' => 'Todos os registros estão intocados a algum tempo (todos os objetos podem ser apagados). Verifique se o processo que grava na tabela de sincronização ainda está em execução. Operação cancelada',
	'Core:SyncSplitModeCLIOnly' => 'A sincronização pode ser executada em pedaços só se for executada em modo CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s réplica(s), %2$s erro(s), %3$s alerta(s)',
	'Core:SynchroReplica:TargetObject' => 'Objeto sincronizado: %1$s',
	'Class:AsyncSendEmail' => 'E-mail (assíncrono)',
	'Class:AsyncSendEmail/Attribute:to' => 'Para',
	'Class:AsyncSendEmail/Attribute:subject' => 'Assunto',
	'Class:AsyncSendEmail/Attribute:body' => 'Corpo',
	'Class:AsyncSendEmail/Attribute:header' => 'Cabeçalho',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Senha criptografada',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Campo criptografado',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log~~',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Última entrada',
	'Class:SynchroDataSource' => 'Fonte de Sincronização de Dados',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Em homologação',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Em produção',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Restrição de escopo',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use os atributos',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use o campo primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Criar',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Atualizar',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Criar',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Pegue o primeiro (ao acaso?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Política de exclusão',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Excluir',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Atualizar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Atualizar então Excluir',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Exibir atributos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Somente administradores',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Todos os usuários',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ninguém',
	'Class:SynchroAttribute' => 'Atributo de sincronização',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Fonte de dados',
	'Class:SynchroAttribute/Attribute:attcode' => 'Código do atributo',
	'Class:SynchroAttribute/Attribute:update' => 'Atualizar',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconciliar',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Política de atualização',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Slave',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Master',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Atualizar se vazio',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Classe',
	'Class:SynchroAttExtKey' => 'Atributo de sincronização (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atributo de reconciliação',
	'Class:SynchroAttLinkSet' => 'Atributo de sincronização (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separador de linhas',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Separador de atributos',
	'Class:SynchroLog' => 'Log de sincronização',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Fonte de sincronização de dados',
	'Class:SynchroLog/Attribute:start_date' => 'Data de início',
	'Class:SynchroLog/Attribute:end_date' => 'Data final',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Finalizado',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Erro',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Em execução',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Número réplica vista~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Número réplica total~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Número de objeto(s) excluído(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Número de erros durante a exclusão',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Número de objeto(s) obsoleto(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Número de erros durante a obsolescência',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Número de objeto(s) criado(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Número de erros durante a criação',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Número de objeto(s) atualizado(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Número de erros durante a atualização',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Número de erros durante a reconciliação',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Número de réplicas desaparecidas',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Número de objetos atualizados',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Número de objetos inalterados',
	'Class:SynchroLog/Attribute:last_error' => 'Últimos erros',
	'Class:SynchroLog/Attribute:traces' => 'Rastrear',
	'Class:SynchroReplica' => 'Sincronização de réplica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Fonte de dados',
	'Class:SynchroReplica/Attribute:dest_id' => 'Objeto de destino (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Tipo de destino',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Visto pela última vez',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modificado',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Novo',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Órfão',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Sincronizado',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objeto criado?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Último Erro',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Alertas',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data de criação',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Última data modificação',
	'Class:appUserPreferences' => 'Preferências de usuário',
	'Class:appUserPreferences/Attribute:userid' => 'Usuário',
	'Class:appUserPreferences/Attribute:preferences' => 'Preferências',
	'Core:ExecProcess:Code1' => 'Comando incorreto ou comando terminou com erros (por exemplo, nome do script errado)',
	'Core:ExecProcess:Code255' => 'Erro PHP (parsing, ou runtime)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Tempo decorrido (armazenado como \\"%1$s\\")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Tempo gasto para \\"%1$s\\"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Prazo para \\"%1$s\\" em %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Parâmetro ausente \\"%1$s\\"',
	'Core:BulkExport:InvalidParameter_Query' => 'Valor inválido para o parâmetro \\"query\\". Não há nenhum entrada no livro de consultas correspondente ao ID: \\"%1$s\\"',
	'Core:BulkExport:ExportFormatPrompt' => 'Formato de exportação:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Clique aqui para baixar %1$s',
	'Core:BulkExport:ExportResult' => 'Resultado da exportação:',
	'Core:BulkExport:RetrievingData' => 'Recuperando dados ...',
	'Core:BulkExport:HTMLFormat' => 'Página da Web (*.html)',
	'Core:BulkExport:CSVFormat' => 'Valores separados por vírgula (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 ou mais recente (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Documento PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Segure e arraste os cabeçalhos das colunas para organizar as colunas. Visualização de %1$s linha(s). Número total de linhas para exportar: %2$s',
	'Core:BulkExport:EmptyPreview' => 'Selecione as colunas a serem exportadas da lista acima',
	'Core:BulkExport:ColumnsOrder' => 'Ordem das colunas',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Colunas disponíveis de(a) %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Selecione pelo menos uma coluna para exportar',
	'Core:BulkExport:CheckAll' => 'Marcar todos',
	'Core:BulkExport:UncheckAll' => 'Desmarcar todos',
	'Core:BulkExport:ExportCancelledByUser' => 'Exportação cancelada pelo usuário',
	'Core:BulkExport:CSVOptions' => 'Opções de exportação CSV',
	'Core:BulkExport:CSVLocalization' => 'Codificação de caracteres:',
	'Core:BulkExport:PDFOptions' => 'Opções de PDF',
	'Core:BulkExport:PDFPageFormat' => 'Formato da página',
	'Core:BulkExport:PDFPageSize' => 'Tamanho da página',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Carta',
	'Core:BulkExport:PDFPageOrientation' => 'Orientação da Página',
	'Core:BulkExport:PageOrientation-L' => 'Paisagem',
	'Core:BulkExport:PageOrientation-P' => 'Retrato',
	'Core:BulkExport:XMLFormat' => 'Arquivo XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Opções XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Formato HTML de planilha (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Opções de planilha',
	'Core:BulkExport:OptionNoLocalize' => 'Usar Código de exportação ao invés do Título dos objetos',
	'Core:BulkExport:OptionLinkSets' => 'Incluir objetos associados',
	'Core:BulkExport:OptionFormattedText' => 'Preservar formatação de texto',
	'Core:BulkExport:ScopeDefinition' => 'Definição dos objetos a exportar',
	'Core:BulkExportLabelOQLExpression' => 'Consulta OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Entrada do livro de consultas:',
	'Core:BulkExportMessageEmptyOQL' => 'Por favor, insira uma consulta OQL válida',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Por favor, selecione uma entrada válida do livro de consultas',
	'Core:BulkExportQueryPlaceholder' => 'Digite uma consulta OQL aqui ...',
	'Core:BulkExportCanRunNonInteractive' => 'Clique aqui para executar a exportação no modo não interativo',
	'Core:BulkExportLegacyExport' => 'Clique aqui para acessar a exportação legada',
	'Core:BulkExport:XLSXOptions' => 'Opções do Excel',
	'Core:BulkExport:TextFormat' => 'Campos de texto contendo códigos HTML',
	'Core:BulkExport:DateTimeFormat' => 'Formato de data e hora',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Formato padrão (%1$s), por exemplo: %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Formato personalizado: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Página %1$s',
	'Core:DateTime:Placeholder_d' => 'DD', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Formato inválido',
	'Core:Validator:Mandatory' => 'Por favor, preencha este campo',
	'Core:Validator:MustBeInteger' => 'Deve ser um número inteiro',
	'Core:Validator:MustSelectOne' => 'Por favor, selecione um',
));

//
// Class: TagSetFieldData
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TagSetFieldData' => '%2$s para classe %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Código',
	'Class:TagSetFieldData/Attribute:code+' => 'Código interno. Deve conter pelo menos 3 caracteres alfanuméricos',
	'Class:TagSetFieldData/Attribute:label' => 'Rótulo',
	'Class:TagSetFieldData/Attribute:label+' => 'Rótulo exibido',
	'Class:TagSetFieldData/Attribute:description' => 'Descrição',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Classe da etiqueta',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe de objeto',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Código de atributo',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Tags em uso não podem ser deletadas',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'O código de tag ou rótulo devem ser únicos',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'O código de tag deve conter entre 3 e %1$d caracteres alfanuméricos',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'O código de tag escolhido é uma palavra reservada',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'O rótulo da tag não deve conter \'%1$s\' nem estar vazio',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags de código não podem ser alteradas quando em uso',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags de "Classe de Objeto" não podem ser alteradas',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags de "Código do atributo" não podem ser alteradas',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Uso de tags (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nenhuma entrada encontrada para esta tag',
));

//
// Class: DBProperty
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DBProperty' => 'Propriedades do DB',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Nome',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Descrição',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Valor',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Data de alteração',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Editar comentário',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:BackgroundTask' => 'Tarefas de fundo',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Nome da classe',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Data da primeira execução',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Data da execução mais recente',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Data da próxima execução',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Número total de execuções',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Duração da execução mais recente',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Duração mín. de execução',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Duração máx. de execução',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Duração média de execução',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Em execução',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AsyncTask' => 'Tarefa assíncrona',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Criada',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Iniciada',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Planejada',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Evento',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Classe final',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Status',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Tentativas restantes',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Último código de erro',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Último erro',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Última tentativa',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Formato inválido para a configuração de "async_task_retries[%1$s]". Esperando um array com as seguintes chaves: %2$s',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Formato inválido para a configuração de "async_task_retries[%1$s]": chave inesperada "%2$s". Esperando somente as seguintes chaves: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AbstractResource' => 'Recurso Abstrato',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ResourceAdminMenu' => 'Recurso Menu de Administração',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ResourceRunQueriesMenu' => 'Recurso Livro de Consultas',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ResourceSystemMenu' => 'Menu de Recursos do Sistema',
	'Class:ResourceSystemMenu+' => '',
));




// Additional language entries not present in English dict
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
 'INTERNAL:JQuery-DatePicker:LangCode' => 'pt-BR',
));

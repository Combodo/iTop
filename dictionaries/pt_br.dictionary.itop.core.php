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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Core:DeletedObjectLabel' => '%1s (excluído)',
	'Core:DeletedObjectTip' => 'O objeto foi excluído em %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objeto não encontrado (classe: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'O objeto não pode ser encontrado. Ele pode ter sido eliminado há algum tempo e o log foi removido desde então.',

	'Core:AttributeLinkedSet' => 'Array de objetos',
	'Core:AttributeLinkedSet+' => 'Qualquer tipo de objetos da mesma classe ou subclasses',

	'Core:AttributeLinkedSetIndirect' => 'Array de objetos (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Qualquer tipo de objetos [sub-classe] da mesma classe',

	'Core:AttributeInteger' => 'Inteiro',
	'Core:AttributeInteger+' => 'Valor numérico (não pode ser negativo)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Valor decimal (não pode ser negativo)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Seqüência alfanumérica',

	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => 'Classe',

	'Core:AttributeApplicationLanguage' => 'Linguagem usuário',
	'Core:AttributeApplicationLanguage+' => 'Linguagem e país (EN US)',

	'Core:AttributeFinalClass' => 'Classe (auto)',
	'Core:AttributeFinalClass+' => 'Classe real do objeto (criada automaticamente pelo sistema)',

	'Core:AttributePassword' => 'Senha',
	'Core:AttributePassword+' => 'Senha para o dispositivo externo',

 	'Core:AttributeEncryptedString' => 'String encriptada',
	'Core:AttributeEncryptedString+' => 'String encriptada com uma chave local',

	'Core:AttributeText' => 'Texto',
	'Core:AttributeText+' => 'Cadeia de caracteres Multi-linha',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'Endereço email',
	'Core:AttributeEmailAddress+' => 'Endereço email',

	'Core:AttributeIPAddress' => 'Endereço IP',
	'Core:AttributeIPAddress+' => 'Endereço IP',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Expressão Object Query Langage',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista de pré-definida seqüências alfanuméricas',

	'Core:AttributeTemplateString' => 'Modelo string',
	'Core:AttributeTemplateString+' => 'Espaço reservado contendo string',

	'Core:AttributeTemplateText' => 'Template text',
	'Core:AttributeTemplateText+' => 'Texto contendo espaços reservados',

	'Core:AttributeTemplateHTML' => 'Modelo HTML',
	'Core:AttributeTemplateHTML+' => 'HTML contendo espaços reservados',

	'Core:AttributeDateTime' => 'Data/hora',
	'Core:AttributeDateTime+' => 'Data e hora (ano-mês-dia hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Data formato:<br/>
	<b>yyyy-mm-dd hh:mm:ss</b><br/>
	Exemplo: 2011-07-19 18:40:00
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
	<b>yyyy-mm-dd</b><br/>
	Exemplo: 2011-07-19
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Tempo determinado',
	'Core:AttributeDeadline+' => 'Data, apresentada relativamente ao tempo atual',

	'Core:AttributeExternalKey' => 'Chave externa',
	'Core:AttributeExternalKey+' => 'Chave externa (ou foreign)',

	'Core:AttributeHierarchicalKey' => 'Chave hierárquica',
	'Core:AttributeHierarchicalKey+' => 'Chave externa (ou foreign) para o principal',

	'Core:AttributeExternalField' => 'Campo externo',
	'Core:AttributeExternalField+' => 'Campo mapeado para uma chave externa',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL absoluto ou relativo como um texto',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Qualquer conteúdo binário (documento)',

	'Core:AttributeOneWayPassword' => 'Uma forma de senha',
	'Core:AttributeOneWayPassword+' => 'Uma forma de senha encriptado',

	'Core:AttributeTable' => 'Tabela',
	'Core:AttributeTable+' => 'Matriz indexada tem duas dimensões',

	'Core:AttributePropertySet' => 'Propriedades',
	'Core:AttributePropertySet+' => 'Lista de propriedades sem categoria (nome e valor)',

	'Core:AttributeFriendlyName' => 'Nome amigável',
	'Core:AttributeFriendlyName+' => 'Atributo criado automaticamente; o nome amigável é gerado depois de vários atributos',

	'Core:FriendlyName-Label' => 'Nome amigável',
	'Core:FriendlyName-Description' => 'Nome amigável',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChange' => 'Mudanças',
	'Class:CMDBChange+' => 'Rastreamento de mudanças',
	'Class:CMDBChange/Attribute:date' => 'Data',
	'Class:CMDBChange/Attribute:date+' => 'Data e hora em que as mudanças foram registrados',
	'Class:CMDBChange/Attribute:userinfo' => 'Mais Informações',
	'Class:CMDBChange/Attribute:userinfo+' => 'Informações solicitantes definidos',
));

//
// Class: CMDBChangeOp
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOp' => 'Operações de mudanças',
	'Class:CMDBChangeOp+' => 'Operações de controle de mudança',
	'Class:CMDBChangeOp/Attribute:change' => 'Mudança',
	'Class:CMDBChangeOp/Attribute:change+' => 'Mudança',
	'Class:CMDBChangeOp/Attribute:date' => 'Data',
	'Class:CMDBChangeOp/Attribute:date+' => 'Data e hora da mudança',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Usuário',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Quem fez essa mudança',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Classe objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'Classe objeto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'ID objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'ID objeto',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpCreate' => 'Criação do objeto',
	'Class:CMDBChangeOpCreate+' => 'Rastreamento de criação do objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpDelete' => 'Objeto excluído',
	'Class:CMDBChangeOpDelete+' => 'Rastreamento de exclusão do objeto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttribute' => 'Objeto alterado',
	'Class:CMDBChangeOpSetAttribute+' => 'Rastreamento alteração propriedade Objeto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Código da propriedade modificado',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Propriedade alterado',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Propriedades escalares objeto de controle de alterações',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'valor anterior do atributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Novo valor',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'novo valor do atributo',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Change:ObjectCreated' => 'Objeto criado',
	'Change:ObjectDeleted' => 'Objeto excluído',
	'Change:ObjectModified' => 'Objeto modificado',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s configurado para %2$s (valor anterior: %3$s)',
	'Change:AttName_SetTo' => '%1$s configurado para %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s anexado ao %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificado, valor anterior: %2$s',
	'Change:AttName_Changed' => '%1$s modificado',
	'Change:AttName_EntryAdded' => '%1$s modificado, nova entrada adicionada.',
	'Change:LinkSet:Added' => 'adicionado %1$s',
	'Change:LinkSet:Removed' => 'excluído %1$s',
	'Change:LinkSet:Modified' => 'modificado %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'data mudança',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'controle de alterações de dados',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'conteúdo anterior do atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeText' => 'mudança texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'controle de alterações de texto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'conteúdo anterior do atributo',
));

//
// Class: Event
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Event' => 'Evento registros',
	'Class:Event+' => 'Um evento interno do aplicativo',
	'Class:Event/Attribute:message' => 'Mensagens',
	'Class:Event/Attribute:message+' => 'pequena descrição deste evento',
	'Class:Event/Attribute:date' => 'Data',
	'Class:Event/Attribute:date+' => 'data e hora em que as mudanças foram registradas',
	'Class:Event/Attribute:userinfo' => 'Informações usuário',
	'Class:Event/Attribute:userinfo+' => 'identificação do usuário que estava fazendo a ação que desencadeou este evento',
	'Class:Event/Attribute:finalclass' => 'Tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotification' => 'Evento notificação',
	'Class:EventNotification+' => 'Rastreamento de uma notificação que foi enviada',
	'Class:EventNotification/Attribute:trigger_id' => 'Gatilho',
	'Class:EventNotification/Attribute:trigger_id+' => 'conta usuário',
	'Class:EventNotification/Attribute:action_id' => 'usuário',
	'Class:EventNotification/Attribute:action_id+' => 'conta usuário',
	'Class:EventNotification/Attribute:object_id' => 'Id objeto',
	'Class:EventNotification/Attribute:object_id+' => 'Id objeto (classe definida pelo gatilho?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotificationEmail' => 'Evento envio email',
	'Class:EventNotificationEmail+' => 'Rastreamento de um e-mail que foi enviado',
	'Class:EventNotificationEmail/Attribute:to' => 'Para',
	'Class:EventNotificationEmail/Attribute:to+' => 'Para',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'CCO',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'CCO',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => 'Remetente da mensagem',
	'Class:EventNotificationEmail/Attribute:subject' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Anexos',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventIssue' => 'Evento entrega',
	'Class:EventIssue+' => 'Rastreamento de entrega (aviso, erro, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Entrega',
	'Class:EventIssue/Attribute:issue+' => 'O que aconteceu',
	'Class:EventIssue/Attribute:impact' => 'Impacto',
	'Class:EventIssue/Attribute:impact+' => 'Quais são as consequências',
	'Class:EventIssue/Attribute:page' => 'Página',
	'Class:EventIssue/Attribute:page+' => 'HTTP ponto de entrada',
	'Class:EventIssue/Attribute:arguments_post' => 'Argumentos postados',
	'Class:EventIssue/Attribute:arguments_post+' => 'Argumentos HTTP POST',
	'Class:EventIssue/Attribute:arguments_get' => 'Argumentos URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Argumentos HTTP GET',
	'Class:EventIssue/Attribute:callstack' => 'Quantidade solicitações',
	'Class:EventIssue/Attribute:callstack+' => 'Quantidade de solicitações',
	'Class:EventIssue/Attribute:data' => 'Dado',
	'Class:EventIssue/Attribute:data+' => 'Mais informações',
));

//
// Class: EventWebService
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventWebService' => 'Evento Web service',
	'Class:EventWebService+' => 'Rastreamento de uma solicitação de serviço web',
	'Class:EventWebService/Attribute:verb' => 'Verbo',
	'Class:EventWebService/Attribute:verb+' => 'Nome da operação',
	'Class:EventWebService/Attribute:result' => 'Resultado',
	'Class:EventWebService/Attribute:result+' => 'Sucesso/fracasso geral',
	'Class:EventWebService/Attribute:log_info' => 'Log informação',
	'Class:EventWebService/Attribute:log_info+' => 'Resultado log informação',
	'Class:EventWebService/Attribute:log_warning' => 'Log de alerta',
	'Class:EventWebService/Attribute:log_warning+' => 'Resultado log de alerta',
	'Class:EventWebService/Attribute:log_error' => 'Log de erro',
	'Class:EventWebService/Attribute:log_error+' => 'Resultado log de erro',
	'Class:EventWebService/Attribute:data' => 'Dado',
	'Class:EventWebService/Attribute:data+' => 'Resultado dado',
));

//
// Class: EventLoginUsage
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventLoginUsage' => 'Login utilizado',
	'Class:EventLoginUsage+' => 'Conexão com a aplicação',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Nome usuário',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Nome usuário',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Email usuário',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Endereço email deste usuário',
));

//
// Class: Action
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Action' => 'Ação personalizada',
	'Class:Action+' => 'Ação definida pelo usuário',
	'Class:Action/Attribute:name' => 'Nome',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descrição',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'Em produção ou ?',
	'Class:Action/Attribute:status/Value:test' => 'sendo testado',
	'Class:Action/Attribute:status/Value:test+' => 'sendo testado',
	'Class:Action/Attribute:status/Value:enabled' => 'Em produção',
	'Class:Action/Attribute:status/Value:enabled+' => 'Em produção',
	'Class:Action/Attribute:status/Value:disabled' => 'Inativo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inativo',
	'Class:Action/Attribute:trigger_list' => 'Gatilhos relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Gatilhos ligados a esta ação',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
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
	'Class:ActionEmail' => 'Notificação email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Testar destinatário',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinatário em caso o estado está definido como "Teste"',
	'Class:ActionEmail/Attribute:from' => 'De',
	'Class:ActionEmail/Attribute:from+' => 'Será enviado para o cabeçalho de email',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder para',
	'Class:ActionEmail/Attribute:reply_to+' => 'Será enviado para o cabeçalho de email',
	'Class:ActionEmail/Attribute:to' => 'Para',
	'Class:ActionEmail/Attribute:to+' => 'Destinatário para o email',
	'Class:ActionEmail/Attribute:cc' => 'CC',
	'Class:ActionEmail/Attribute:cc+' => 'CC',
	'Class:ActionEmail/Attribute:bcc' => 'CCO',
	'Class:ActionEmail/Attribute:bcc+' => 'CCO',
	'Class:ActionEmail/Attribute:subject' => 'assunto',
	'Class:ActionEmail/Attribute:subject+' => 'Título do email',
	'Class:ActionEmail/Attribute:body' => 'corpo',
	'Class:ActionEmail/Attribute:body+' => 'Conteúdo do email',
	'Class:ActionEmail/Attribute:importance' => 'importância',
	'Class:ActionEmail/Attribute:importance+' => 'Flag importância',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'baixo',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'baixo',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'alto',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'alto',
));

//
// Class: Trigger
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Trigger' => 'Gatilho',
	'Class:Trigger+' => 'Manipulador de eventos personalizado',
	'Class:Trigger/Attribute:description' => 'Descrição',
	'Class:Trigger/Attribute:description+' => 'uma linha descrição',
	'Class:Trigger/Attribute:action_list' => 'Ações desencadeadas',
	'Class:Trigger/Attribute:action_list+' => 'Ações executadas quando o gatilho é ativado',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObject' => 'Gatilho (classe dependente)',
	'Class:TriggerOnObject+' => 'Gatilho em uma determinada classe de objetos',
	'Class:TriggerOnObject/Attribute:target_class' => 'Classe alvo',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnPortalUpdate' => 'Gatilho (quando atualizado a partir do portal)',
	'Class:TriggerOnPortalUpdate+' => 'Gatilho de uma atualização do usuário final a partir do portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateChange' => 'Gatilho (na mudança de estado)',
	'Class:TriggerOnStateChange+' => 'Gatilho de mudança do estado do objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'State',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateEnter' => 'Gatilho (ao entrar em um estado)',
	'Class:TriggerOnStateEnter+' => 'Gatilho de mudança do estado do objeto - entrando',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateLeave' => 'Gatilho (para sair de um estado)',
	'Class:TriggerOnStateLeave+' => 'Gatilho de mudança do estado do objeto - saindo',
));

//
// Class: TriggerOnObjectCreates
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectCreate' => 'Gatilho (na criação do objeto)',
	'Class:TriggerOnObjectCreate+' => 'Gatilho de criação do objeto de [a classe filha] determinada classe',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnThresholdReached' => 'Gatilho (na entrada)',
	'Class:TriggerOnThresholdReached+' => 'Gatilho no cronômetro limite atingido',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Cronômetro',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Entrada',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkTriggerAction' => 'Ações/Gatilho',
	'Class:lnkTriggerAction+' => 'Ligação entre um Gatilho e uma Ação',
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
	'Class:SynchroDataSource/Attribute:name+' => 'Nome',
	'Class:SynchroDataSource/Attribute:description' => 'Descrição',
	'Class:SynchroDataSource/Attribute:status' => 'Status', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Classe alvo',
	'Class:SynchroDataSource/Attribute:user_id' => 'Usuário',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contato para notificação',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contato para notificar em caso de erro',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icones hiperlink',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hiperlink de uma pequena imagem representando o aplicativo com o qual o iTop é sincronizado',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hiperlink aplicativo',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperlink para o objeto na aplicação externa com a qual o iTop é sincronizado (se aplicável). As substituições possíveis: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Política reconciliação', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Intervalo da carga plena',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A recarga completa de todos os dados devem ocorrer pelo menos tão frequentemente como especificado aqui',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Ação sobre zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Medidas tomadas quando a busca retorna nenhum objeto',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Ação em um',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Medidas tomadas quando a busca retorna exatamente um objeto',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Ação em muitos',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Medidas tomadas quando a busca retorna mais de um objeto',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Usuários permitidos',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Quem tem permissão para excluir objetos sincronizados',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Usuários permitidos',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Ninguém',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Somente Administradores',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Todos os usuários permitidos',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Regras de atualização',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sintaxe: nome_do_campo:valor; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Duração de retenção',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Quanto tempo um objeto obsoleto é mantida antes de ser excluído',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Tabela de dados',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Nome da tabela para armazenar os dados de sincronização. Se for deixado vazio, um nome padrão será computado.',
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
	'Core:Synchro:History' => 'Histórico sincronização',
	'Core:Synchro:NeverRun' => 'Este sincronismo nunca foi executado. Sem registo ainda.',
	'Core:Synchro:SynchroEndedOn_Date' => 'A última sincronização terminou em %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'A sincronização começou em %1$s ainda está em execução...',
	'Menu:DataSources' => 'Fontes de dados de sincronização', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Todas fontes de dados de sincronização', // Duplicated into itop-welcome-itil (will be removed from here...)
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
	'Core:Synchro:History' => 'Histórico sincronização',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica processado: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Pelo menos uma chave de reconciliação deve ser especificado, ou a política de reconciliação deve ser a de usar a chave primária.',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Um período de retenção excluir deve ser especificado, já que objetos devem ser excluídos depois de ser marcado como obsoleto.',			
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Objetos obsoletos devem ser atualizados, mas nenhuma atualização é especificado.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'A tabela %1$s já existe na base de dados. Por favor, use um outro nome para a tabela de dados sincronizada.',
	'Core:SynchroReplica:PublicData' => 'Dados públicos',
	'Core:SynchroReplica:PrivateDetails' => 'Detalhes privado',
	'Core:SynchroReplica:BackToDataSource' => 'Voltar para a fonte de dados sincronização: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista de replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'Id (chave primária)',
	'Core:SynchroAtt:attcode' => 'Atributo',
	'Core:SynchroAtt:attcode+' => 'Campo do objeto',
	'Core:SynchroAtt:reconciliation' => 'Reconciliação?',
	'Core:SynchroAtt:reconciliation+' => 'Usado para pesquisa',
	'Core:SynchroAtt:update' => 'Atualizar?',
	'Core:SynchroAtt:update+' => 'Usado para atualizar o objeto',
	'Core:SynchroAtt:update_policy' => 'Política de atualização',
	'Core:SynchroAtt:update_policy+' => 'Comportamento do campo atualizado',
	'Core:SynchroAtt:reconciliation_attcode' => 'Chave reconciliação',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Código atributo para a reconciliação chave externa',
	'Core:SyncDataExchangeComment' => '(Sincronização dado)',
	'Core:Synchro:ListOfDataSources' => 'Lista de fontes de dados:',
	'Core:Synchro:LastSynchro' => 'Última sincronização:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Este objeto é sincronizado com uma fonte de dados externa',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'O objeto foi <b>criado</b> pela fonte de dados externa %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'O objeto <b>não pode ser excluído</b> pela fonte de dados externa %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Você <b>não pode excluir o objeto</b> porque é propriedade de uma fonte de dados externa %1$s',
	'TitleSynchroExecution' => 'Execução da sincronização',
	'Class:SynchroDataSource:DataTable' => 'Tabela base de dados: %1$s',
	'Core:SyncDataSourceObsolete' => 'A fonte de dados é marcado como obsoleto. Operação cancelada.',
	'Core:SyncDataSourceAccessRestriction' => 'Adminstradores ou apenas o usuário especificado na fonte de dados pode executar esta operação. Operação cancelada.',
	'Core:SyncTooManyMissingReplicas' => 'Todos os registros foram intocado por algum tempo (todos os objetos podem ser apagados). Verifique se o processo que grava na tabela de sincronização ainda está em execução. Operação cancelada.',
	'Core:SyncSplitModeCLIOnly' => 'A sincronização pode ser executado em pedaços só se for executado em modo CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s erro(s), %3$s alerta(s).',
	'Core:SynchroReplica:TargetObject' => 'Objeto(s) sincronizado(s): %1$s',
	'Class:AsyncSendEmail' => 'Email (assíncrono)',
	'Class:AsyncSendEmail/Attribute:to' => 'Para',
	'Class:AsyncSendEmail/Attribute:subject' => 'Assunto',
	'Class:AsyncSendEmail/Attribute:body' => 'Corpo',
	'Class:AsyncSendEmail/Attribute:header' => 'Cabeçalho',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Senha criptograda',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Campo criptografado',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Caso Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Última entrada',
	'Class:SynchroDataSource' => 'Fonte de dados sincronização',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementação',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Produção',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Restrição de escopo',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use os atributos',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use o campo primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Criar',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Atualizar',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Criar',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Erro',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'TPegue o primeiro (acaso?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Política exclusão',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Excluir',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Atualizar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Atualize quando excluído',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Listar atributos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Somente administradores',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Permissão total para excluir esses objetos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ninguém',
	'Class:SynchroAttribute' => 'Atributo sincronização',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Fonte de dados',
	'Class:SynchroAttribute/Attribute:attcode' => 'Código atributo',
	'Class:SynchroAttribute/Attribute:update' => 'Atualizar',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconciliar',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Política atualizar',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Trancado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Destrancado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Iniciando se vazio',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Classe',
	'Class:SynchroAttExtKey' => 'Atributo sincronização (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atributo reconciliação',
	'Class:SynchroAttLinkSet' => 'Atributo sincronização (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separador de linhas',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Separador atributos',
	'Class:SynchroLog' => 'Log sincronização',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Fonte de dados sincronização',
	'Class:SynchroLog/Attribute:start_date' => 'Data início',
	'Class:SynchroLog/Attribute:end_date' => 'Data final',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completado',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Erro',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Ainda está em execução',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Número réplica vista',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Número réplica total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Número objeto(s) excluído(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Número de erros enquanto excluindo',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Número objeto(s) obsoleto(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Número de erros enquanto obsoletos',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Número objeto(s) criado(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Número de erros enquanto criando',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Número objeto(s) atualizado(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Número de erros enquanto atualizando',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Número de erros durante reconciliação',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Número réplica desaparecida',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Número objetos atualizados',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Número objetos inalterados',
	'Class:SynchroLog/Attribute:last_error' => 'Últimos erros',
	'Class:SynchroLog/Attribute:traces' => 'Rastrear',
	'Class:SynchroReplica' => 'Réplica sincronização',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Fonte de dados',
	'Class:SynchroReplica/Attribute:dest_id' => 'Objeto destino (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Tipo destino',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Visto pela última vez',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modificado',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Novo',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orfão',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Sincronizado',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objeto criado?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Último Erro',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Alertas',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data criação',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Última data modificação',
	'Class:appUserPreferences' => 'Preferência de usuário',
	'Class:appUserPreferences/Attribute:userid' => 'Usuário',
	'Class:appUserPreferences/Attribute:preferences' => 'Preferências',
	'Core:ExecProcess:Code1' => 'Comando errado ou comando terminou com erros (por exemplo, nome do script errado)',
	'Core:ExecProcess:Code255' => 'PHP erro (parsing, or runtime)',
));

//
// Attribute Duration
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Core:Duration_Seconds'	=> '%1$ds',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',	
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',		
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',		
));

?>

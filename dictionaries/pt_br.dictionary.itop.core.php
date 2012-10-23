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
	'Core:AttributeLinkedSet' => 'Array de objetos',
	'Core:AttributeLinkedSet+' => 'Qualquer tipo de objetos da mesma classe ou subclasse',

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

	'Core:AttributeApplicationLanguage' => 'Linguagem Usuário',
	'Core:AttributeApplicationLanguage+' => 'Linguagem e país (EN US)',

	'Core:AttributeFinalClass' => 'Classe (auto)',
	'Core:AttributeFinalClass+' => 'Classe real do objeto (criada automaticamente pelo core)',

	'Core:AttributePassword' => 'Senha',
	'Core:AttributePassword+' => 'Senha para o dispositivo externo',

 	'Core:AttributeEncryptedString' => 'String encriptada',
	'Core:AttributeEncryptedString+' => 'String encriptada com uma chave local',

	'Core:AttributeText' => 'Texto',
	'Core:AttributeText+' => 'Cadeia de caracteres Multi-linha',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'Endereço Email',
	'Core:AttributeEmailAddress+' => 'Endereço Email',

	'Core:AttributeIPAddress' => 'Endereço IP',
	'Core:AttributeIPAddress+' => 'Endereço IP',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Expressão Object Query Langage',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista de pré-definida seqüências alfanuméricas',

	'Core:AttributeTemplateString' => 'Modelo string',
	'Core:AttributeTemplateString+' => 'Espaço reservado contendo string',

	'Core:AttributeTemplateText' => 'Modelo texto',
	'Core:AttributeTemplateText+' => 'Texto contendo espaços reservados',

	'Core:AttributeTemplateHTML' => 'Modelo HTML',
	'Core:AttributeTemplateHTML+' => 'HTML contendo espaços reservados',

	'Core:AttributeDateTime' => 'Data/hora',
	'Core:AttributeDateTime+' => 'Data e hora (ano-mês-dia hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Formato Data:<br/>
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
	Formato Data:<br/>
	<b>yyyy-mm-dd</b><br/>
	Exemplo: 2011-07-19
</p>
<p>
Operadores:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Prazo determinado',
	'Core:AttributeDeadline+' => 'Data, apresentado relativamente ao tempo atual',

	'Core:AttributeExternalKey' => 'Chave externa',
	'Core:AttributeExternalKey+' => 'Chave externa (ou estrangeira)',

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

	'Core:AttributePropertySet' => 'Propriedade',
	'Core:AttributePropertySet+' => 'Lista de propriedades sem categoria (nome e valor)',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChange' => 'Alteração',
	'Class:CMDBChange+' => 'Rastreamento de alterações',
	'Class:CMDBChange/Attribute:date' => 'data',
	'Class:CMDBChange/Attribute:date+' => 'data e hora no qual a alteração foi registrada',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'informações definidas pelo solicitante',
));

//
// Class: CMDBChangeOp
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOp' => 'Operação de mudança',
	'Class:CMDBChangeOp+' => 'Operações de controle de alterações',
	'Class:CMDBChangeOp/Attribute:change' => 'alterar',
	'Class:CMDBChangeOp/Attribute:change+' => 'alterar',
	'Class:CMDBChangeOp/Attribute:date' => 'data',
	'Class:CMDBChangeOp/Attribute:date+' => 'data e hora da alteração',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'usuário',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'que fez esta mudança',
	'Class:CMDBChangeOp/Attribute:objclass' => 'classe objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'classe objeto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'id objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'id objeto',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpCreate' => 'criação do objeto',
	'Class:CMDBChangeOpCreate+' => 'Rastreamento de criação do objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpDelete' => 'objeto excluído',
	'Class:CMDBChangeOpDelete+' => 'Rastreamento de exclusão do objeto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttribute' => 'objeto alterado',
	'Class:CMDBChangeOpSetAttribute+' => 'Rastreamento alteração propriedade Objeto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Código da propriedade modificado',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'propriedade alterado',
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
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'alteração dados',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Rastreamento alteração dados',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Dados anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'conteúdo anterior do atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeText' => 'alteração texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'Rastreamento alteração texto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Dado anterior',
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
	'Class:Event/Attribute:userinfo' => 'Usuário info',
	'Class:Event/Attribute:userinfo+' => 'identificação do usuário que estava fazendo a ação que desencadeou este evento',
	'Class:Event/Attribute:finalclass' => 'Tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotification' => 'Evento Notificação',
	'Class:EventNotification+' => 'Trace of a notification that has been sent',
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
	'Class:EventNotificationEmail' => 'Evento envio Email',
	'Class:EventNotificationEmail+' => 'Rastreamento de um e-mail que foi enviado',
	'Class:EventNotificationEmail/Attribute:to' => 'Para',
	'Class:EventNotificationEmail/Attribute:to+' => 'Para',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => 'Remetente da mensagem',
	'Class:EventNotificationEmail/Attribute:subject' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => 'Corpo',
));

//
// Class: EventIssue
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventIssue' => 'Issue event',
	'Class:EventIssue+' => 'Trace of an issue (warning, error, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Issue',
	'Class:EventIssue/Attribute:issue+' => 'O que aconteceu',
	'Class:EventIssue/Attribute:impact' => 'Impacto',
	'Class:EventIssue/Attribute:impact+' => 'Quais são as conseqüências',
	'Class:EventIssue/Attribute:page' => 'Página',
	'Class:EventIssue/Attribute:page+' => 'Ponto de entrada HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Postado argumentos',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST arguments',
	'Class:EventIssue/Attribute:arguments_get' => 'URL argumentos',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET argumentos',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Dado',
	'Class:EventIssue/Attribute:data+' => 'Mais informação',
));

//
// Class: EventWebService
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventWebService' => 'Event serviço Web',
	'Class:EventWebService+' => 'Rastreamento de uma chamada de serviço web',
	'Class:EventWebService/Attribute:verb' => 'Verbo',
	'Class:EventWebService/Attribute:verb+' => 'Nome da operação',
	'Class:EventWebService/Attribute:result' => 'Resultado',
	'Class:EventWebService/Attribute:result+' => 'Sucesso global / insucesso',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Resultado info log',
	'Class:EventWebService/Attribute:log_warning' => 'Warning log',
	'Class:EventWebService/Attribute:log_warning+' => 'Resultado warning log',
	'Class:EventWebService/Attribute:log_error' => 'Erro log',
	'Class:EventWebService/Attribute:log_error+' => 'Resultado erro log',
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
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Endereço Email deste usuário',
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
	'Class:Action/Attribute:status/Value:test' => 'que está sendo testado',
	'Class:Action/Attribute:status/Value:test+' => 'que está sendo testado',
	'Class:Action/Attribute:status/Value:enabled' => 'em produção',
	'Class:Action/Attribute:status/Value:enabled+' => 'em produção',
	'Class:Action/Attribute:status/Value:disabled' => 'Inativo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inativo',
	'Class:Action/Attribute:trigger_list' => 'Gatilhos relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Gatilho ligado para esta ação',
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
	'Class:ActionEmail' => 'Notificação Email',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Testar destinatário',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinatário em caso estado está definido como "Teste"',
	'Class:ActionEmail/Attribute:from' => 'De',
	'Class:ActionEmail/Attribute:from+' => 'Será enviado para o cabeçalho de e-mail',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder para',
	'Class:ActionEmail/Attribute:reply_to+' => 'Será enviado para o cabeçalho de e-mail',
	'Class:ActionEmail/Attribute:to' => 'Para',
	'Class:ActionEmail/Attribute:to+' => 'Destinatário para o email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Cc',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Bcc',
	'Class:ActionEmail/Attribute:subject' => 'assunto',
	'Class:ActionEmail/Attribute:subject+' => 'Título do email',
	'Class:ActionEmail/Attribute:body' => 'Corpo',
	'Class:ActionEmail/Attribute:body+' => 'Conteúdo do email',
	'Class:ActionEmail/Attribute:importance' => 'importância',
	'Class:ActionEmail/Attribute:importance+' => 'Emblema importância',
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
	'Class:TriggerOnPortalUpdate+' => 'Gatilho em uma atualização de usuários finais a partir do portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateChange' => 'Gatilho (na mudança de estado)',
	'Class:TriggerOnStateChange+' => 'Gatilho sobre a mudança de estado do objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Estado',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateEnter' => 'Gatilho (ao entrar em um estado)',
	'Class:TriggerOnStateEnter+' => 'Gatilho sobre a mudança de estado de objeto - de entrar',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateLeave' => 'Gatilho (para sair de um estado)',
	'Class:TriggerOnStateLeave+' => 'Gatilho sobre a mudança de estado do objeto - deixando',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectCreate' => 'Gatilho (na criação do objeto)',
	'Class:TriggerOnObjectCreate+' => 'Gatilho na criação do objeto de [classe filha] determinada classe',
));

//
// Class: lnkTriggerAction
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkTriggerAction' => 'Ações/Gatilho',
	'Class:lnkTriggerAction+' => 'Ligação entre um Gatilho e uma Ação',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Ação',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'A ação a ser executada',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Ação',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Gatilho',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Gatilho',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Ordem',
	'Class:lnkTriggerAction/Attribute:order+' => 'Ordem de execução das ações ',
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
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperlink para o objeto iTop na aplicação externa com a qual iTop é sincronizado (se aplicável). As substituições possíveis: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Política Reconciliação', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Intervalo da carga plena',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A recarga completa de todos os dados devem ocorrer pelo menos tão frequentemente como especificado aqui',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Ação do zero',
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
	'Core:Synchro:ReconciledLabel' => 'reconciliados',
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
	'Menu:DataSources' => 'Fontes de dados de sincronização',
	'Menu:DataSources+' => 'Todas Fontes de dados de sincronização',
	'Core:Synchro:label_repl_ignored' => 'Ignoradas (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Desaparecido (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Existente (%1$s)',
	'Core:Synchro:label_repl_new' => 'Novo (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Excluído (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoletos (%1$s)',
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
	'Core:Synchro:Nb_Replica' => 'Replica processados: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Pelo menos uma chave de reconciliação deve ser especificado, ou a política de reconciliação deve ser a de usar a chave primária.',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Um período de retenção excluir deve ser especificado, já que objetos devem ser excluídos depois de ser marcado como obsoleto.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Objetos obsoletos devem ser atualizados, mas nenhuma atualização é especificado.',
	'Core:SynchroReplica:PublicData' => 'Dado público',
	'Core:SynchroReplica:PrivateDetails' => 'Detalhe privado',
	'Core:SynchroReplica:BackToDataSource' => 'Voltar para a Fonte de dados Sincro: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista de Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Chave Primária)',
	'Core:SynchroAtt:attcode' => 'Atributo',
	'Core:SynchroAtt:attcode+' => 'Campo do objeto',
	'Core:SynchroAtt:reconciliation' => 'Reconciliação ?',
	'Core:SynchroAtt:reconciliation+' => 'Usado para pesquisa',
	'Core:SynchroAtt:update' => 'Atualizar ?',
	'Core:SynchroAtt:update+' => 'Usado para atualizar o objeto',
	'Core:SynchroAtt:update_policy' => 'Política de atualização',
	'Core:SynchroAtt:update_policy+' => 'Comportamento do campo atualizado',
	'Core:SynchroAtt:reconciliation_attcode' => 'Chave reconciliação',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Código atributo para a Reconciliação chave externa',
	'Core:SyncDataExchangeComment' => '(Sincro dado)',
	'Core:Synchro:ListOfDataSources' => 'Lista de fontes de dados:',
	'Core:Synchro:LastSynchro' => 'Última sincronização:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Este objeto é sincronizado com uma fonte de dados externa',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'O objeto foi <b>criado</b> pela fonte de dados externa %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'O objeto <b>não pode ser excluído</b> pela fonte de dados externa %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Você <b>não pode excluir o objeto</b> porque é propriedade de uma fonte de dados externa %1$s',
	'TitleSynchroExecution' => 'Execução da sincronização',
	'Class:SynchroDataSource:DataTable' => 'Tabela Database: %1$s',
	'Core:SyncDataSourceObsolete' => 'A fonte de dados é marcado como obsoleto. Operação cancelada.',
	'Core:SyncDataSourceAccessRestriction' => 'Adminstradores ou apenas o usuário especificado na fonte de dados pode executar esta operação. Operação cancelada.',
	'Core:SyncTooManyMissingReplicas' => 'Todos os registros foram intocado por algum tempo (todos os objetos podem ser apagados). Verifique se o processo que grava na tabela de sincronização ainda está em execução. Operação cancelada.',
	'Core:SyncSplitModeCLIOnly' => 'A sincronização pode ser executado em pedaços só se for executado em modo CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s erro(s), %3$s atenção(s).',
	'Core:SynchroReplica:TargetObject' => 'Objeto(s) sincronizado(s): %1$s',
	'Class:AsyncSendEmail' => 'Email (assíncrono)',
	'Class:AsyncSendEmail/Attribute:to' => 'Para',
	'Class:AsyncSendEmail/Attribute:subject' => 'Assunto',
	'Class:AsyncSendEmail/Attribute:body' => 'Corpo',
	'Class:AsyncSendEmail/Attribute:header' => 'Cabeçalho',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Senha Criptograda',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Campo Criptograda',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Caso Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Última entrada',
	'Class:SynchroDataSource' => 'Fonte de dados',
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
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Pegue o primeiro (acaso?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Política exclusão',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Excuir',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Atualizar',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Atualize quando excluído',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lista atributos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Somente Administradores',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Permissão total para excluir esses objetos',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ninguém',
	'Class:SynchroAttribute' => 'Atributo sincro',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Fonte de dados',
	'Class:SynchroAttribute/Attribute:attcode' => 'Código atributo',
	'Class:SynchroAttribute/Attribute:update' => 'Atualizar',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconciliar',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Política atualizar',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Trancado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Destrancado',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Iniciando se vazio',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Classe',
	'Class:SynchroAttExtKey' => 'Atributo sincro (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atributo Reconciliação',
	'Class:SynchroAttLinkSet' => 'Atributo sincro (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separador de linhas',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Separador atributos',
	'Class:SynchroLog' => 'Sincro Log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Fonte de dados',
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
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Número de erros enquanto criava',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Número objeto(s) atualizado(s)',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Número de erros enquanto atualizava',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Número de erros durante reconciliação',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Número réplica desaparecida',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Número objetos atualizados',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Número objetos inalterados',
	'Class:SynchroLog/Attribute:last_error' => 'Últimos erros',
	'Class:SynchroLog/Attribute:traces' => 'Traços',
	'Class:SynchroReplica' => 'Sincro Réplica',
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
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objeto criado ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Último Erro',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Atenção',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data criação',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Última data modificação',
	'Class:appUserPreferences' => 'Preferência de usuário',
	'Class:appUserPreferences/Attribute:userid' => 'Usuário',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs',
	'Core:ExecProcess:Code1' => 'Comando errado ou comando terminou com erros (por exemplo, nome do script errado)',
	'Core:ExecProcess:Code255' => 'PHP Erro (parsing, or runtime)',
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

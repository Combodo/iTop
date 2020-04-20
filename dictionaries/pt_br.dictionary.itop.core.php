<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Core:DeletedObjectLabel' => '%1s (excluído)',
	'Core:DeletedObjectTip' => 'O objeto foi excluído em %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objeto não encontrado (classe: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'O objeto não pode ser encontrado. Ele pode ter sido eliminado há algum tempo e o log foi removido desde então.',

	'Core:UniquenessDefaultError' => 'Regra de exclusividade \'%1$s\' com erro',

	'Core:AttributeLinkedSet' => 'Array de objetos',
	'Core:AttributeLinkedSet+' => 'Qualquer tipo de objetos da mesma classe ou subclasses',

	'Core:AttributeDashboard' => 'Painel de controle',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Número de telefone',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Obsolescência data',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Lista de etiquetas',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'clique para adicionar',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s de %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s de classes filhas)',

	'Core:AttributeCaseLog' => 'Log',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Computed enum~~',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => 'Array de objetos (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Qualquer tipo de objetos [sub-classe] da mesma classe',

	'Core:AttributeInteger' => 'Inteiro',
	'Core:AttributeInteger+' => 'Valor numérico (não pode ser negativo)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Valor decimal (não pode ser negativo)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Sim',
	'Core:AttributeBoolean/Value:no' => 'Não',

	'Core:AttributeArchiveFlag' => 'Arquivar bandeira',
	'Core:AttributeArchiveFlag/Value:yes' => 'Sim',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Este objeto é visível apenas no modo de arquivo',
	'Core:AttributeArchiveFlag/Value:no' => 'Não',
	'Core:AttributeArchiveFlag/Label' => 'Arquivado',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Data de arquivamento',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag~~',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Sim',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Este objeto é excluído da análise de impacto e oculto dos resultados de pesquisa',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Não',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsoleto',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computado dinamicamente em outros atributos',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescência data',
	'Core:AttributeObsolescenceDate/Label+' => 'Data aproximada em que o objeto foi considerado obsoleto',

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
	'Core:AttributeEncryptUnknownLibrary' => 'Biblioteca de criptografia especificada (%1$s) desconhecida',
	'Core:AttributeEncryptFailedToDecrypt' => '** erro de decriptação **',

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

	'Core:AttributeTag' => 'Etiquetas',
	'Core:AttributeTag+' => 'Etiquetas',
	
	'Core:Context=REST/JSON' => 'REST~~',
	'Core:Context=Synchro' => 'Synchro~~',
	'Core:Context=Setup' => 'Setup~~',
	'Core:Context=GUI:Console' => 'Console~~',
	'Core:Context=CRON' => 'cron~~',
	'Core:Context=GUI:Portal' => 'Portal~~',
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
	'Change:AttName_EntryAdded' => '%1$s modificado, nova entrada adicionada: %2$s',
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

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventRestService' => 'Chamada REST/JSON',
	'Class:EventRestService+' => 'Rastreio de uma chamada de serviço REST/JSON',
	'Class:EventRestService/Attribute:operation' => 'Operation',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Versão',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Input',
	'Class:EventRestService/Attribute:json_input+' => 'Argumento \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Code',
	'Class:EventRestService/Attribute:code+' => 'Código de resultado',
	'Class:EventRestService/Attribute:json_output' => 'Response',
	'Class:EventRestService/Attribute:json_output+' => 'Resposta HTTP (json)',
	'Class:EventRestService/Attribute:provider' => 'Provedor',
	'Class:EventRestService/Attribute:provider+' => 'Classe PHP implementando a operação esperada',
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
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
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
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Consulta de filtro incorreta: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'A consulta de filtro deve retornar objetos da classe \\"%1$s\\"',
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
// Class: TriggerOnObjectCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectCreate' => 'Gatilho (na criação do objeto)',
	'Class:TriggerOnObjectCreate+' => 'Gatilho de criação do objeto de [a classe filha] determinada classe',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (na exclusão de objetos)',
	'Class:TriggerOnObjectDelete+' => 'Trigger na exclusão de objeto de [uma classe filho] da classe dada',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (na atualização do objeto)',
	'Class:TriggerOnObjectUpdate+' => 'Trigger na atualização de objeto de [uma classe filho] da classe dada',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Campos de destino',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
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
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Classe alvo',
	'Class:SynchroDataSource/Attribute:user_id' => 'Usuário',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contato para notificação',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contato para notificar em caso de erro',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icones hiperlink',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hiperlink de uma pequena imagem representando o aplicativo com o qual o iTop é sincronizado',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hiperlink aplicativo',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperlink para o objeto na aplicação externa com a qual o iTop é sincronizado (se aplicável). As substituições possíveis: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Política reconciliação',
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
	'Core:BulkExport:InvalidParameter_Query' => 'Valor inválido para o parâmetro \\"query\\". Não há nenhum Phrasebook de consulta correspondente ao id: \\"%1$s\\".',
	'Core:BulkExport:ExportFormatPrompt' => 'Formato de exportação:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Clique aqui para baixar %1$s',
	'Core:BulkExport:ExportResult' => 'Resultado da exportação:',
	'Core:BulkExport:RetrievingData' => 'Recuperando dados ...',
	'Core:BulkExport:HTMLFormat' => 'Página da Web (*.html)',
	'Core:BulkExport:CSVFormat' => 'Valores separados por vírgula (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 ou mais recente (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Documento PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Arrastar e soltar os cabeçalhos das colunas para organizar as colunas. Visualização de %1$s linhas. Número total de linhas para exportar: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Selecione as colunas a serem exportadas da lista acima',
	'Core:BulkExport:ColumnsOrder' => 'Ordem das colunas',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Colunas disponíveis de %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Selecione pelo menos uma coluna para ser exportada',
	'Core:BulkExport:CheckAll' => 'Check All~~',
	'Core:BulkExport:UncheckAll' => 'Desmarque tudo',
	'Core:BulkExport:ExportCancelledByUser' => 'Exportação cancelada pelo usuário',
	'Core:BulkExport:CSVOptions' => 'CSV Options',
	'Core:BulkExport:CSVLocalization' => 'Localização',
	'Core:BulkExport:PDFOptions' => 'Opções de PDF',
	'Core:BulkExport:PDFPageFormat' => 'Formato da página',
	'Core:BulkExport:PDFPageSize' => 'Tamanho da página:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Carta',
	'Core:BulkExport:PDFPageOrientation' => 'Orientação de Página:',
	'Core:BulkExport:PageOrientation-L' => 'Landscape~~',
	'Core:BulkExport:PageOrientation-P' => 'Portrait~~',
	'Core:BulkExport:XMLFormat' => 'Arquivo XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Opções XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Formato HTML de planilha (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Opções de planilha',
	'Core:BulkExport:OptionNoLocalize' => 'Código de exportação em vez de Label',
	'Core:BulkExport:OptionLinkSets' => 'Incluir objetos vinculados',
	'Core:BulkExport:OptionFormattedText' => 'Preservar formatação de texto',
	'Core:BulkExport:ScopeDefinition' => 'Definição dos objetos a exportar',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:~~',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
	'Core:BulkExportMessageEmptyOQL' => 'Por favor insira uma consulta OQL válida.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Por favor, selecione uma entrada de livro de frases válida.',
	'Core:BulkExportQueryPlaceholder' => 'Digite uma consulta OQL aqui ...',
	'Core:BulkExportCanRunNonInteractive' => 'Clique aqui para executar a exportação no modo não interativo.',
	'Core:BulkExportLegacyExport' => 'Clique aqui para acessar a exportação legada.',
	'Core:BulkExport:XLSXOptions' => 'Opções do Excel',
	'Core:BulkExport:TextFormat' => 'Campos de texto contendo alguma marcação HTML',
	'Core:BulkExport:DateTimeFormat' => 'Formato de data e hora',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Formato padrão (%1$s), por ex. %2$s',
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
	'Core:Validator:Default' => 'Formato errado',
	'Core:Validator:Mandatory' => 'Por favor, preencha este campo',
	'Core:Validator:MustBeInteger' => 'Deve ser um inteiro',
	'Core:Validator:MustSelectOne' => 'Por favor, selecione um',
));

//
// Class: TagSetFieldData
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TagSetFieldData' => '%2$s for class %1$s~~',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Code~~',
	'Class:TagSetFieldData/Attribute:code+' => 'Código interno. Deve conter pelo menos 3 caracteres alfanuméricos',
	'Class:TagSetFieldData/Attribute:label' => 'Label~~',
	'Class:TagSetFieldData/Attribute:label+' => 'Rótulo exibido',
	'Class:TagSetFieldData/Attribute:description' => 'Descrição',
	'Class:TagSetFieldData/Attribute:description+' => 'Descrição',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe de objeto',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Código de campo',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Tags usadas não podem ser deletadas',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tags códigos ou rótulos devem ser únicos',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'O código de tags deve conter entre 3 e %1$d caracteres alfanuméricos',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'O código de tag escolhido é uma palavra reservada',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'O rótulo de etiquetas não deve conter \'%1$s\'nem estar vazio',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags Código não pode ser alterado quando usado',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" não pode ser alterado',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Código do atributo" não pode ser alterado',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Uso de tags (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nenhuma entrada encontrada para esta tag',
));

//
// Class: DBProperty
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:DBProperty' => 'Propriedade do DB',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Nome',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Descrição',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Valor',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Data de mudança',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Alterar comentário',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:BackgroundTask' => 'Tarefa de fundo',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Nome da turma',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Primeira data de execução',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Data de execução mais recente',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Próxima data de execução',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. conte',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Duração de execução mais recente',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. duração de execução',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. duração de execução',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Duração média de execução',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Correndo',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:AsyncTask' => 'Assíncrono. tarefa',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Criado',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Iniciado',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Planejado',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Evento',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Aula final',
	'Class:AsyncTask/Attribute:finalclass+' => '',
));

// Additional language entries not present in English dict
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
 'INTERNAL:JQuery-DatePicker:LangCode' => 'pt-BR',
));

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


Dict::Add('EN US', 'English', 'English', array(
	'Core:AttributeLinkedSet' => 'Array of objects',
	'Core:AttributeLinkedSet+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => 'Class',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => 'User language',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)',

	'Core:AttributeFinalClass' => 'Class (auto)',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password of an external device',

 	'Core:AttributeEncryptedString' => 'Encrypted string',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key',

	'Core:AttributeText' => 'Text',
	'Core:AttributeText+' => 'Multiline character string',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'Email address',
	'Core:AttributeEmailAddress+' => 'Email address',

	'Core:AttributeIPAddress' => 'IP address',
	'Core:AttributeIPAddress+' => 'IP address',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings',

	'Core:AttributeTemplateString' => 'Template string',
	'Core:AttributeTemplateString+' => 'String containing placeholders',

	'Core:AttributeTemplateText' => 'Template text',
	'Core:AttributeTemplateText+' => 'Text containing placeholders',

	'Core:AttributeTemplateHTML' => 'Template HTML',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders',

	'Core:AttributeWikiText' => 'Wiki article',
	'Core:AttributeWikiText+' => 'Wiki formatted text',

	'Core:AttributeDateTime' => 'Date/time',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)',

	'Core:AttributeDate' => 'Date',
	'Core:AttributeDate+' => 'Date (year-month-day)',

	'Core:AttributeDeadline' => 'Deadline',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time',

	'Core:AttributeExternalKey' => 'External key',
	'Core:AttributeExternalKey+' => 'External (or foreign) key',

	'Core:AttributeExternalField' => 'External field',
	'Core:AttributeExternalField+' => 'Field mapped from an external key',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Any binary content (document)',

	'Core:AttributeOneWayPassword' => 'One way password',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password',

	'Core:AttributeTable' => 'Table',
	'Core:AttributeTable+' => 'Indexed array having two dimensions',

	'Core:AttributePropertySet' => 'Properties',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChange' => 'Mudan&ccedil;as',
	'Class:CMDBChange+' => 'Monitoramento Mudan&ccedil;as',
	'Class:CMDBChange/Attribute:date' => 'data',
	'Class:CMDBChange/Attribute:date+' => 'data e hora que as mudan&ccedil;as tenham sido registradas.',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'Solicitante definido informa&ccedil;&atilde;o',
));

//
// Class: CMDBChangeOp
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOp' => 'Opera&ccedil;&atilde;o Mudan&ccedil;a',
	'Class:CMDBChangeOp+' => 'Controle Opera&ccedil;&atilde;o Mudan&ccedil;a',
	'Class:CMDBChangeOp/Attribute:change' => 'mudan&ccedil;as',
	'Class:CMDBChangeOp/Attribute:change+' => 'mudan&ccedil;as',
	'Class:CMDBChangeOp/Attribute:date' => 'data',
	'Class:CMDBChangeOp/Attribute:date+' => 'data e hora das mudan&ccedil;as',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'usu&aacute;rio',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'quem fez esta mudan&ccedil;as',
	'Class:CMDBChangeOp/Attribute:objclass' => 'classe objeto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'classe objeto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'id objeto',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'objeto',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpCreate' => 'objeto criado',
	'Class:CMDBChangeOpCreate+' => 'Controle cria&ccedil;&atilde;o objeto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpDelete' => 'objeto apagado',
	'Class:CMDBChangeOpDelete+' => 'Controle objeto eliminado',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttribute' => 'objeto alterado',
	'Class:CMDBChangeOpSetAttribute+' => 'Controle do objeto alterado',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'c&oacute;digo da modifica&ccedil;&atilde;o',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'mudan&ccedil;a propriedade',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'controle da mudan&ccedil;a propriedade do objeto',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valor anterior',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'valores anteriores do atributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Novo valor',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'novo valor do atributo',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'mudan&ccedil;a dado',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'controle mudan&ccedil;a dado',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Dado anterior',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'conte&uacute;do anterior do atributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:CMDBChangeOpSetAttributeText' => 'mudan&ccedil;a texto',
	'Class:CMDBChangeOpSetAttributeText+' => 'controle mudan&ccedil;a texto',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Dado anterior',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'conte&uacute;do anterior do atributo',
));

//
// Class: Event
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Event' => 'Log Eventos',
	'Class:Event+' => 'An application internal event',
	'Class:Event/Attribute:message' => 'mensagem',
	'Class:Event/Attribute:message+' => 'descri&ccedil;&atilde;o curta do evento',
	'Class:Event/Attribute:date' => 'data',
	'Class:Event/Attribute:date+' => 'data e hora em que as mudan&ccedil;as tenham sido registadas',
	'Class:Event/Attribute:userinfo' => 'info usu&acute;rio',
	'Class:Event/Attribute:userinfo+' => 'identifica&ccedil;&atilde;o do usu&aacute;rio queestava fazendo a a&ccedil;&atilde;o e desencadeou este evento',
	'Class:Event/Attribute:finalclass' => 'tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotification' => 'Notifica&ccedil;&atilde;o evento',
	'Class:EventNotification+' => 'Trace of a notification that has been sent',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => 'conta usu&aacute;rio',
	'Class:EventNotification/Attribute:action_id' => 'usu&aacute;rio',
	'Class:EventNotification/Attribute:action_id+' => 'conta usu&aacute;rio',
	'Class:EventNotification/Attribute:object_id' => 'Objeto id',
	'Class:EventNotification/Attribute:object_id+' => 'objeto id (classe definida pela trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventNotificationEmail' => 'Enviando evento Email',
	'Class:EventNotificationEmail+' => 'Controle de um email que foi enviado',
	'Class:EventNotificationEmail/Attribute:to' => 'Para',
	'Class:EventNotificationEmail/Attribute:to+' => 'Para',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'De',
	'Class:EventNotificationEmail/Attribute:from+' => 'Rementente da mensagem',
	'Class:EventNotificationEmail/Attribute:subject' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Assunto',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => 'Corpo',
));

//
// Class: EventIssue
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventIssue' => 'Emiss&atilde;o de evento',
	'Class:EventIssue+' => 'Controle da emiss&atilde;o (aten&ccedil;&atilde;o, erros, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Emiss&atilde;o',
	'Class:EventIssue/Attribute:issue+' => 'O que aconteceu?',
	'Class:EventIssue/Attribute:impact' => 'Impacto',
	'Class:EventIssue/Attribute:impact+' => 'Quais s&atilde;o as consequ&ecirc;ncias?',
	'Class:EventIssue/Attribute:page' => 'P&aacute;gina',
	'Class:EventIssue/Attribute:page+' => 'HTTP ponto de entrada',
	'Class:EventIssue/Attribute:arguments_post' => 'Postados argumentos',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST argumentos',
	'Class:EventIssue/Attribute:arguments_get' => 'URL argumentos',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET argumentos',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Dado',
	'Class:EventIssue/Attribute:data+' => 'Mais informa&ccedil;&otilde;es',
));

//
// Class: EventWebService
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:EventWebService' => 'Evento Web service',
	'Class:EventWebService+' => 'Controle chamado do web service',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Nome da opera&ccedil;&atilde;o',
	'Class:EventWebService/Attribute:result' => 'Resultado',
	'Class:EventWebService/Attribute:result+' => 'Vis&atilde;o geral successos/falhas',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Resultado info log',
	'Class:EventWebService/Attribute:log_warning' => 'Log aten&ccedil;&atilde;o',
	'Class:EventWebService/Attribute:log_warning+' => 'Resultado Log aten&ccedil;&atilde;o',
	'Class:EventWebService/Attribute:log_error' => 'Log erro',
	'Class:EventWebService/Attribute:log_error+' => 'Resultado log erro',
	'Class:EventWebService/Attribute:data' => 'Dado',
	'Class:EventWebService/Attribute:data+' => 'Resultado dado',
));

//
// Class: Action
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Action' => 'Personalizar A&ccedil;&atilde;o',
	'Class:Action+' => 'A&ccedil;&atilde;o definida usu&aacute;rio',
	'Class:Action/Attribute:name' => 'Nome',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'Em produ&ccedil;&atilde;o ou ?',
	'Class:Action/Attribute:status/Value:test' => 'Que est&aacute; sendo testado',
	'Class:Action/Attribute:status/Value:test+' => 'Que est&aacute; sendo testado',
	'Class:Action/Attribute:status/Value:enabled' => 'Em produ&ccedil;&atilde;o',
	'Class:Action/Attribute:status/Value:enabled+' => 'Em produ&ccedil;&atilde;o',
	'Class:Action/Attribute:status/Value:disabled' => 'Inativo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inativo',
	'Class:Action/Attribute:trigger_list' => 'Triggers relacionados',
	'Class:Action/Attribute:trigger_list+' => 'Triggers ligado a esta a&ccedil;&atilde;o',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ActionNotification' => 'Notifica&ccedil;&atilde;o',
	'Class:ActionNotification+' => 'Notifica&ccedil;&atilde;o (abstrato)',
));

//
// Class: ActionEmail
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:ActionEmail' => 'Email notifica&ccedil;&atilde;o',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Teste destino',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Destinat&aacute;rio o status est&aacute; como "Teste"',
	'Class:ActionEmail/Attribute:from' => 'De',
	'Class:ActionEmail/Attribute:from+' => 'Ser&aacute; enviado dentro do cabe&ccedil;alho do email',
	'Class:ActionEmail/Attribute:reply_to' => 'Responder para',
	'Class:ActionEmail/Attribute:reply_to+' => 'Ser&aacute; enviado dentro do cabe&ccedil;alho do email',
	'Class:ActionEmail/Attribute:to' => 'Para',
	'Class:ActionEmail/Attribute:to+' => 'Destinat&aacute;rio para o email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Com c&oacute;pia',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Com c&oacute;pia oculta',
	'Class:ActionEmail/Attribute:subject' => 'assunto',
	'Class:ActionEmail/Attribute:subject+' => 'T&iacute;tulo do email',
	'Class:ActionEmail/Attribute:body' => 'corpo',
	'Class:ActionEmail/Attribute:body+' => 'Conte&uacute;do do email',
	'Class:ActionEmail/Attribute:importance' => 'import&acirc;ncia',
	'Class:ActionEmail/Attribute:importance+' => 'Flag import&acirc;ncia',
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
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Personalizar manipulador de eventos',
	'Class:Trigger/Attribute:description' => 'Descri&ccedil;&atilde;o',
	'Class:Trigger/Attribute:description+' => 'uma linha descri&ccedil;&atilde;o',
	'Class:Trigger/Attribute:action_list' => 'A&ccedil;&otilde;es desencadeadas',
	'Class:Trigger/Attribute:action_list+' => 'A&ccedil;&otilde;es executadas quando a Trigger é ativado',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObject' => 'Trigger (classe dependente)',
	'Class:TriggerOnObject+' => 'Trigger em uma determinada classe de objetos',
	'Class:TriggerOnObject/Attribute:target_class' => 'Alvo classe',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateChange' => 'Trigger (em estato mudan&ccedil;a)',
	'Class:TriggerOnStateChange+' => 'Trigger sobre a mudan&ccedil;a de estado do objeto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Estado',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateEnter' => 'Trigger (ao entrar em um estado)',
	'Class:TriggerOnStateEnter+' => 'Trigger sobre a mudan&ccedil;a de estado do objeto - entrar',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnStateLeave' => 'Trigger (na saída de um estado)',
	'Class:TriggerOnStateLeave+' => 'Trigger sobre a mudan&ccedil;a de estado do objeto - deixando',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (sobre a cria&ccedil;&atilde;o do objeto)',
	'Class:TriggerOnObjectCreate+' => 'Trigger sobre a cria&ccedil;&atilde;o do objeto de [uma classe filha de] uma determinada classe',
));

//
// Class: lnkTriggerAction
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkTriggerAction' => 'A&ccedil;&atilde;o/Trigger',
	'Class:lnkTriggerAction+' => 'Liga&ccedil;&atilde;o entre uma trigger e uma a&ccedil;&atilde;o',
	'Class:lnkTriggerAction/Attribute:action_id' => 'A&ccedil;&atilde;o',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'A&ccedil;&atilde;o a ser executada',
	'Class:lnkTriggerAction/Attribute:action_name' => 'A&ccedil;&atilde;o',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Ordem',
	'Class:lnkTriggerAction/Attribute:order+' => 'AA&ccedil;&otilde;es executadas ordem',
));


?>

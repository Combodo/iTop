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

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Core:AttributeLinkedSet' => 'Array di oggetti',
	'Core:AttributeLinkedSet+' => 'Qualsiasi tipo di oggetti della stessa classe o sottoclasse',

	'Core:AttributeLinkedSetIndirect' => 'Array di oggetti (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Qualsiasi tipo di oggetti [sottoclasse] della stessa classe',

	'Core:AttributeInteger' => 'Intero',
	'Core:AttributeInteger+' => 'Valore numerico (potrebbe essere negativo)',

	'Core:AttributeDecimal' => 'Decimale',
	'Core:AttributeDecimal+' => 'Valore decimale (potrebbe essere negativo)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'Stringa',
	'Core:AttributeString+' => 'Stringa alfanumerica',

	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => 'Classe',

	'Core:AttributeApplicationLanguage' => 'Lingua utente',
	'Core:AttributeApplicationLanguage+' => 'Lingua e nazione (IT IT)',

	'Core:AttributeFinalClass' => 'Classe (auto)',
	'Core:AttributeFinalClass+' => 'Classe reale dell\'oggetto (creato automaticamente dal nucleo)',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password di un dispositivo esterno',

 	'Core:AttributeEncryptedString' => 'Stringa crittografata',
	'Core:AttributeEncryptedString+' => 'Stringa crittografata con chiave locale',

	'Core:AttributeText' => 'Testo',
	'Core:AttributeText+' => 'Stringa di caratteri multiline',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Stringa HTML',

	'Core:AttributeEmailAddress' => 'Indirizzo email',
	'Core:AttributeEmailAddress+' => 'Indirizzo email',

	'Core:AttributeIPAddress' => 'Indirizzo IP',
	'Core:AttributeIPAddress+' => 'Indirizzo IP',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista di stringhe alfanumeriche predefinite',

	'Core:AttributeTemplateString' => 'Template stringa',
	'Core:AttributeTemplateString+' => 'Stringa contenente segnaposti',

	'Core:AttributeTemplateText' => 'Template testo',
	'Core:AttributeTemplateText+' => 'Testo contenente segnaposti',

	'Core:AttributeTemplateHTML' => 'Template HTML',
	'Core:AttributeTemplateHTML+' => 'HTML contenente segnaposti',

	'Core:AttributeWikiText' => 'Articolo wiki',
	'Core:AttributeWikiText+' => 'Testo formattato wiki',

	'Core:AttributeDateTime' => 'Data/ora',
	'Core:AttributeDateTime+' => 'Data e ora (anno-mese-giorno hh:mm:ss)',

	'Core:AttributeDate' => 'Data',
	'Core:AttributeDate+' => 'Data (anno-mese-giorno)',

	'Core:AttributeDeadline' => 'Scadenza',
	'Core:AttributeDeadline+' => 'Data, visualizzata in relazione al tempo corrente',

	'Core:AttributeExternalKey' => 'Chiave esterna',
	'Core:AttributeExternalKey+' => 'Chiave esterna (o straniera)',

	'Core:AttributeExternalField' => 'Campo esterno',
	'Core:AttributeExternalField+' => 'Campo mappato a una chiave esterna',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL assoluto o relativo come stringa di testo',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Qualsiasi contenuto binario (documento)',

	'Core:AttributeOneWayPassword' => 'Password "one way"',
	'Core:AttributeOneWayPassword+' => 'Password "one way" criptata (hashed)',

	'Core:AttributeTable' => 'Tabella',
	'Core:AttributeTable+' => 'Array indicizzato avente due dimensioni',

	'Core:AttributePropertySet' => 'Proprietà',
	'Core:AttributePropertySet+' => 'Elenco delle proprietà non tipizzati (nome e valore)',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChange' => 'Change',
	'Class:CMDBChange+' => 'Changes tracking',
	'Class:CMDBChange/Attribute:date' => 'date',
	'Class:CMDBChange/Attribute:date+' => 'date and time at which the changes have been recorded',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'caller\'s defined information',
));

//
// Class: CMDBChangeOp
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOp' => 'Change Operation',
	'Class:CMDBChangeOp+' => 'Change operations tracking',
	'Class:CMDBChangeOp/Attribute:change' => 'change',
	'Class:CMDBChangeOp/Attribute:change+' => 'change',
	'Class:CMDBChangeOp/Attribute:date' => 'date',
	'Class:CMDBChangeOp/Attribute:date+' => 'date and time of the change',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'user',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'who made this change',
	'Class:CMDBChangeOp/Attribute:objclass' => 'object class',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'object class',
	'Class:CMDBChangeOp/Attribute:objkey' => 'object id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'object id',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'type',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpCreate' => 'object creation',
	'Class:CMDBChangeOpCreate+' => 'Object creation tracking',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpDelete' => 'object deletion',
	'Class:CMDBChangeOpDelete+' => 'Object deletion tracking',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpSetAttribute' => 'object change',
	'Class:CMDBChangeOpSetAttribute+' => 'Object properties change tracking',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribute',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code of the modified property',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'property change',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Object scalar properties change tracking',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Previous value',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'previous value of the attribute',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'New value',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'new value of the attribute',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Change:ObjectCreated' => 'Oggetto creato',
	'Change:ObjectDeleted' => 'Oggetto creato',
	'Change:ObjectModified' => 'Oggetto modificato',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s impostato su %2$s (valore precedente: %3$s)',
	'Change:Text_AppendedTo_AttName' => '%1$s allegata alla %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificato, valore precedente: %2$s',
	'Change:AttName_Changed' => '%1$s modificato',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'data change',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'data change tracking',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Previous data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:CMDBChangeOpSetAttributeText' => 'text change',
	'Class:CMDBChangeOpSetAttributeText+' => 'text change tracking',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Previous data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: Event
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:Event' => 'Log Event',
	'Class:Event+' => 'An application internal event',
	'Class:Event/Attribute:message' => 'message',
	'Class:Event/Attribute:message+' => 'short description of the event',
	'Class:Event/Attribute:date' => 'date',
	'Class:Event/Attribute:date+' => 'date and time at which the changes have been recorded',
	'Class:Event/Attribute:userinfo' => 'user info',
	'Class:Event/Attribute:userinfo+' => 'identification of the user that was doing the action that triggered this event',
	'Class:Event/Attribute:finalclass' => 'type',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:EventNotification' => 'Notification event',
	'Class:EventNotification+' => 'Trace of a notification that has been sent',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => 'user account',
	'Class:EventNotification/Attribute:action_id' => 'user',
	'Class:EventNotification/Attribute:action_id+' => 'user account',
	'Class:EventNotification/Attribute:object_id' => 'Object id',
	'Class:EventNotification/Attribute:object_id+' => 'object id (class defined by the trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:EventNotificationEmail' => 'Email emission event',
	'Class:EventNotificationEmail+' => 'Trace of an email that has been sent',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => 'TO',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'From',
	'Class:EventNotificationEmail/Attribute:from+' => 'Sender of the message',
	'Class:EventNotificationEmail/Attribute:subject' => 'Subject',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Subject',
	'Class:EventNotificationEmail/Attribute:body' => 'Body',
	'Class:EventNotificationEmail/Attribute:body+' => 'Body',
));

//
// Class: EventIssue
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:EventIssue' => 'Issue event',
	'Class:EventIssue+' => 'Trace of an issue (warning, error, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Issue',
	'Class:EventIssue/Attribute:issue+' => 'What happened',
	'Class:EventIssue/Attribute:impact' => 'Impact',
	'Class:EventIssue/Attribute:impact+' => 'What are the consequences',
	'Class:EventIssue/Attribute:page' => 'Page',
	'Class:EventIssue/Attribute:page+' => 'HTTP entry point',
	'Class:EventIssue/Attribute:arguments_post' => 'Posted arguments',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST arguments',
	'Class:EventIssue/Attribute:arguments_get' => 'URL arguments',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET arguments',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Data',
	'Class:EventIssue/Attribute:data+' => 'More information',
));

//
// Class: EventWebService
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:EventWebService' => 'Web service event',
	'Class:EventWebService+' => 'Trace of an web service call',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Name of the operation',
	'Class:EventWebService/Attribute:result' => 'Result',
	'Class:EventWebService/Attribute:result+' => 'Overall success/failure',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Result info log',
	'Class:EventWebService/Attribute:log_warning' => 'Warning log',
	'Class:EventWebService/Attribute:log_warning+' => 'Result warning log',
	'Class:EventWebService/Attribute:log_error' => 'Error log',
	'Class:EventWebService/Attribute:log_error+' => 'Result error log',
	'Class:EventWebService/Attribute:data' => 'Data',
	'Class:EventWebService/Attribute:data+' => 'Result data',
));

//
// Class: Action
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:Action' => 'Custom Action',
	'Class:Action+' => 'User defined action',
	'Class:Action/Attribute:name' => 'Name',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Description',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'In production or ?',
	'Class:Action/Attribute:status/Value:test' => 'Being tested',
	'Class:Action/Attribute:status/Value:test+' => 'Being tested',
	'Class:Action/Attribute:status/Value:enabled' => 'In production',
	'Class:Action/Attribute:status/Value:enabled+' => 'In production',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactive',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inactive',
	'Class:Action/Attribute:trigger_list' => 'Related Triggers',
	'Class:Action/Attribute:trigger_list+' => 'Triggers linked to this action',
	'Class:Action/Attribute:finalclass' => 'Type',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:ActionNotification' => 'Notification',
	'Class:ActionNotification+' => 'Notification (abstract)',
));

//
// Class: ActionEmail
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:ActionEmail' => 'Email notification',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Test recipient',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Detination in case status is set to "Test"',
	'Class:ActionEmail/Attribute:from' => 'From',
	'Class:ActionEmail/Attribute:from+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:reply_to' => 'Reply to',
	'Class:ActionEmail/Attribute:reply_to+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:to' => 'To',
	'Class:ActionEmail/Attribute:to+' => 'Destination of the email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => 'subject',
	'Class:ActionEmail/Attribute:subject+' => 'Title of the email',
	'Class:ActionEmail/Attribute:body' => 'body',
	'Class:ActionEmail/Attribute:body+' => 'Contents of the email',
	'Class:ActionEmail/Attribute:importance' => 'importance',
	'Class:ActionEmail/Attribute:importance+' => 'Importance flag',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'low',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'low',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'high',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'high',
));

//
// Class: Trigger
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => 'Description',
	'Class:Trigger/Attribute:description+' => 'one line description',
	'Class:Trigger/Attribute:action_list' => 'Triggered actions',
	'Class:Trigger/Attribute:action_list+' => 'Actions performed when the trigger is activated',
	'Class:Trigger/Attribute:finalclass' => 'Type',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:TriggerOnObject' => 'Trigger (class dependent)',
	'Class:TriggerOnObject+' => 'Trigger on a given class of objects',
	'Class:TriggerOnObject/Attribute:target_class' => 'Target class',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:TriggerOnStateChange' => 'Trigger (on state change)',
	'Class:TriggerOnStateChange+' => 'Trigger on object state change',
	'Class:TriggerOnStateChange/Attribute:state' => 'State',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:TriggerOnStateEnter' => 'Trigger (on entering a state)',
	'Class:TriggerOnStateEnter+' => 'Trigger on object state change - entering',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:TriggerOnStateLeave' => 'Trigger (on leaving a state)',
	'Class:TriggerOnStateLeave+' => 'Trigger on object state change - leaving',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (on object creation)',
	'Class:TriggerOnObjectCreate+' => 'Trigger on object creation of [a child class of] the given class',
));

//
// Class: lnkTriggerAction
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkTriggerAction' => 'Action/Trigger',
	'Class:lnkTriggerAction+' => 'Link between a trigger and an action',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Action',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'The action to be executed',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Action',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Order',
	'Class:lnkTriggerAction/Attribute:order+' => 'Actions execution order',
));


?>

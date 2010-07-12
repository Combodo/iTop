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
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChange' => 'change',
	'Class:CMDBChange+' => 'Changes tracking',
	'Class:CMDBChange/Attribute:date' => 'date',
	'Class:CMDBChange/Attribute:date+' => 'date and time at which the changes have been recorded',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'caller\'s defined information',
));

//
// Class: CMDBChangeOp
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOp' => 'change operation',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpCreate' => 'object creation',
	'Class:CMDBChangeOpCreate+' => 'Object creation tracking',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpDelete' => 'object deletion',
	'Class:CMDBChangeOpDelete+' => 'Object deletion tracking',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpSetAttribute' => 'object change',
	'Class:CMDBChangeOpSetAttribute+' => 'Object properties change tracking',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribute',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code of the modified property',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'property change',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Object scalar properties change tracking',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Previous value',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'previous value of the attribute',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'New value',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'new value of the attribute',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'data change',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'data change tracking',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Previous data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOpSetAttributeText' => 'text change',
	'Class:CMDBChangeOpSetAttributeText+' => 'text change tracking',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Previous data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: Event
//

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:Action' => 'action',
	'Class:Action+' => 'Custom action',
	'Class:Action/Attribute:name' => 'Name',
	'Class:Action/Attribute:name+' => 'label',
	'Class:Action/Attribute:description' => 'Description',
	'Class:Action/Attribute:description+' => 'one line description',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:ActionNotification' => 'notification',
	'Class:ActionNotification+' => 'Notification (abstract)',
));

//
// Class: ActionEmail
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ActionEmail' => 'email notification',
	'Class:ActionEmail+' => 'Action: Email notification',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:Trigger' => 'trigger',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => 'Description',
	'Class:Trigger/Attribute:description+' => 'one line description',
	'Class:Trigger/Attribute:linked_actions' => 'Triggered actions',
	'Class:Trigger/Attribute:linked_actions+' => 'Actions performed when the trigger is activated',
	'Class:Trigger/Attribute:finalclass' => 'Type',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObject' => 'Trigger on a class of objects',
	'Class:TriggerOnObject+' => 'Trigger on a given class of objects',
	'Class:TriggerOnObject/Attribute:target_class' => 'Target class',
	'Class:TriggerOnObject/Attribute:target_class+' => 'label',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateChange' => 'Trigger on object state change',
	'Class:TriggerOnStateChange+' => 'Trigger on object state change',
	'Class:TriggerOnStateChange/Attribute:state' => 'State',
	'Class:TriggerOnStateChange/Attribute:state+' => 'label',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateEnter' => 'Trigger on object entering a state',
	'Class:TriggerOnStateEnter+' => 'Trigger on object state change - entering',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateLeave' => 'Trigger on object leaving a state',
	'Class:TriggerOnStateLeave+' => 'Trigger on object state change - leaving',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObjectCreate' => 'Trigger on object creation',
	'Class:TriggerOnObjectCreate+' => 'Trigger on object creation of [a child class of] the given class',
));

//
// Class: lnkTriggerAction
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkTriggerAction' => 'Actions-Trigger',
	'Class:lnkTriggerAction+' => 'Link between a trigger and an action',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Action',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'The action to be executed',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Action Name',
	'Class:lnkTriggerAction/Attribute:action_name+' => 'Name of the action',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger Name',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => 'Name of the trigger',
	'Class:lnkTriggerAction/Attribute:order' => 'Order',
	'Class:lnkTriggerAction/Attribute:order+' => 'Actions execution order',
));


?>

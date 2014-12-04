<?php
// Copyright (C) 2010-2014 Combodo SARL
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

Dict::Add('EN US', 'English', 'English', array(
	'Core:DeletedObjectLabel' => '%1s (deleted)',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Object not found (class: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.',

	'Core:AttributeLinkedSet' => 'Array of objects',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass',

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

	'Core:AttributeDateTime' => 'Date/time',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Date format:<br/>
	<b>yyyy-mm-dd hh:mm:ss</b><br/>
	Example: 2011-07-19 18:40:00
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
If the time is omitted, it defaults to 00:00:00
</p>',

	'Core:AttributeDate' => 'Date',
	'Core:AttributeDate+' => 'Date (year-month-day)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Date format:<br/>
	<b>yyyy-mm-dd</b><br/>
	Example: 2011-07-19
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Deadline',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time',

	'Core:AttributeExternalKey' => 'External key',
	'Core:AttributeExternalKey+' => 'External (or foreign) key',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Key',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent',

	'Core:AttributeExternalField' => 'External field',
	'Core:AttributeExternalField+' => 'Field mapped to an external key',

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

	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes',

	'Core:FriendlyName-Label' => 'Friendly name',
	'Core:FriendlyName-Description' => 'Friendly name',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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
// Used by CMDBChangeOp... & derived classes
Dict::Add('EN US', 'English', 'English', array(
	'Change:ObjectCreated' => 'Object created',
	'Change:ObjectDeleted' => 'Object deleted',
	'Change:ObjectModified' => 'Object modified',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s set to %2$s (previous value: %3$s)',
	'Change:AttName_SetTo' => '%1$s set to %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s appended to %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modified, previous value: %2$s',
	'Change:AttName_Changed' => '%1$s modified',
	'Change:AttName_EntryAdded' => '%1$s modified, new entry added.',
	'Change:LinkSet:Added' => 'added %1$s',
	'Change:LinkSet:Removed' => 'removed %1$s',
	'Change:LinkSet:Modified' => 'modified %1$s',
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
	'Class:Event/Attribute:message' => 'Message',
	'Class:Event/Attribute:message+' => 'short description of the event',
	'Class:Event/Attribute:date' => 'Date',
	'Class:Event/Attribute:date+' => 'date and time at which the changes have been recorded',
	'Class:Event/Attribute:userinfo' => 'User info',
	'Class:Event/Attribute:userinfo+' => 'identification of the user that was doing the action that triggered this event',
	'Class:Event/Attribute:finalclass' => 'Type',
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
	'Class:EventNotificationEmail/Attribute:attachments' => 'Attachments',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
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
// Class: EventLoginUsage
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventLoginUsage' => 'Login Usage',
	'Class:EventLoginUsage+' => 'Connection to the application',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email Address of the User',
));

//
// Class: Action
//

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:ActionNotification' => 'Notification',
	'Class:ActionNotification+' => 'Notification (abstract)',
));

//
// Class: ActionEmail
//

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObject' => 'Trigger (class dependent)',
	'Class:TriggerOnObject+' => 'Trigger on a given class of objects',
	'Class:TriggerOnObject/Attribute:target_class' => 'Target class',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (when updated from the portal)',
	'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateChange' => 'Trigger (on state change)',
	'Class:TriggerOnStateChange+' => 'Trigger on object state change',
	'Class:TriggerOnStateChange/Attribute:state' => 'State',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateEnter' => 'Trigger (on entering a state)',
	'Class:TriggerOnStateEnter+' => 'Trigger on object state change - entering',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnStateLeave' => 'Trigger (on leaving a state)',
	'Class:TriggerOnStateLeave+' => 'Trigger on object state change - leaving',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (on object creation)',
	'Class:TriggerOnObjectCreate+' => 'Trigger on object creation of [a child class of] the given class',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (on threshold)',
	'Class:TriggerOnThresholdReached+' => 'Trigger on Stop-Watch threshold reached',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Threshold',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('EN US', 'English', 'English', array(
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

//
// Synchro Data Source
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:SynchroDataSource/Attribute:name' => 'Name',
	'Class:SynchroDataSource/Attribute:name+' => 'Name',
	'Class:SynchroDataSource/Attribute:description' => 'Description',
	'Class:SynchroDataSource/Attribute:status' => 'Status', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target class',
	'Class:SynchroDataSource/Attribute:user_id' => 'User',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact to notify',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icon\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink a (small) image representing the application with which iTop is synchronized',
	'Class:SynchroDataSource/Attribute:url_application' => 'Application\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink to the iTop object in the external application with which iTop is synchronized (if applicable). Possible placeholders: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation policy', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A complete reload of all data must occur at least as often as specified here',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Action on zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Action taken when the search returns no object',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Action on one',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action taken when the search returns exactly one object',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Action on many',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Action taken when the search returns more than one object',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Users allowed',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Who is allowed to delete synchronized objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Users allowed',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Administrators only',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'All allowed users',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Update rules',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Retention Duration',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'How much time an obsolete object is kept before being deleted',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.',
	'SynchroDataSource:Description' => 'Description',
	'SynchroDataSource:Reconciliation' => 'Search &amp; reconciliation',
	'SynchroDataSource:Deletion' => 'Deletion rules',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Information',
	'SynchroDataSource:Definition' => 'Definition',
	'Core:SynchroAttributes' => 'Attributes',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Errors',	
	'Core:Synchro:CreatedLabel' => 'Created',
	'Core:Synchro:ModifiedLabel' => 'Modified',
	'Core:Synchro:UnchangedLabel' => 'Unchanged',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errors',
	'Core:Synchro:ReconciledLabel' => 'Reconciled',
	'Core:Synchro:ReconciledNewLabel' => 'Created',
	'Core:SynchroReconcile:Yes' => 'Yes',
	'Core:SynchroReconcile:No' => 'No',
	'Core:SynchroUpdate:Yes' => 'Yes',
	'Core:SynchroUpdate:No' => 'No',
	'Core:Synchro:LastestStatus' => 'Latest Status',
	'Core:Synchro:History' => 'Synchronization History',
	'Core:Synchro:NeverRun' => 'This synchro was never run. No log yet.',
	'Core:Synchro:SynchroEndedOn_Date' => 'The latest synchronization ended on %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'The synchronization started on %1$s is still running...',
	'Menu:DataSources' => 'Synchronization Data Sources', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'All Synchronization Data Sources', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignored (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Disappeared (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Existing (%1$s)',
	'Core:Synchro:label_repl_new' => 'New (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Deleted (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleted (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'No Action (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Unchanged (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Updated (%1$s)', 
	'Core:Synchro:label_obj_updated_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Unchanged (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Updated (%1$s)',
	'Core:Synchro:label_obj_created' => 'Created (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Errors (%1$s)',
	'Core:Synchro:History' => 'Synchronization History',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete',			
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete objects are to be updated, but no update is specified.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'The table %1$s already exists in the database. Please use another name for the synchro data table.',
	'Core:SynchroReplica:PublicData' => 'Public Data',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)',
	'Core:SynchroAtt:attcode' => 'Attribute',
	'Core:SynchroAtt:attcode+' => 'Field of the object',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?',
	'Core:SynchroAtt:reconciliation+' => 'Used for searching',
	'Core:SynchroAtt:update' => 'Update ?',
	'Core:SynchroAtt:update+' => 'Used to update the object',
	'Core:SynchroAtt:update_policy' => 'Update Policy',
	'Core:SynchroAtt:update_policy+' => 'Behavior of the updated field',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribute Code for the External Key Reconciliation',
	'Core:SyncDataExchangeComment' => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources' => 'List of data sources:',
	'Core:Synchro:LastSynchro' => 'Last synchronization:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'This object is synchronized with an external data source',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'The object was <b>created</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'The object <b>can be deleted</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s',
	'TitleSynchroExecution' => 'Execution of the synchronization',
	'Class:SynchroDataSource:DataTable' => 'Database table: %1$s',
	'Core:SyncDataSourceObsolete' => 'The data source is marked as obsolete. Operation cancelled.',
	'Core:SyncDataSourceAccessRestriction' => 'Only adminstrators or the user specified in the data source can execute this operation. Operation cancelled.',
	'Core:SyncTooManyMissingReplicas' => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).',
	'Core:SynchroReplica:TargetObject' => 'Synchronized Object: %1$s',
	'Class:AsyncSendEmail' => 'Email (asynchronous)',
	'Class:AsyncSendEmail/Attribute:to' => 'To',
	'Class:AsyncSendEmail/Attribute:subject' => 'Subject',
	'Class:AsyncSendEmail/Attribute:body' => 'Body',
	'Class:AsyncSendEmail/Attribute:header' => 'Header',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Encrypted Password',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Previous Value',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Encrypted Field',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Previous Value',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Last Entry',
	'Class:SynchroDataSource' => 'Synchro Data Source',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementation',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Production',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope restriction',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use the attributes',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Take the first one (random?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Delete Policy',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Delete',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignore',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update then Delete',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attributes List',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Administrators only',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Everybody allowed to delete such objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nobody',
	'Class:SynchroAttribute' => 'Synchro Attribute',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribute Code',
	'Class:SynchroAttribute/Attribute:update' => 'Update',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Locked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Unlocked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialize if empty',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Class',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation Attribute',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Rows separator',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributes separator',
	'Class:SynchroLog' => 'Synchr Log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroLog/Attribute:start_date' => 'Start Date',
	'Class:SynchroLog/Attribute:end_date' => 'End Date',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completed',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Error',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Still Running',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica seen',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objects deleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb of errors while deleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objects obsoleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb of errors while obsoleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objects created',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb or errors while creating',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objects updated',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb errors while updating',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb of errors during reconciliation',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica disappeared',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objects updated',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objects unchanged',
	'Class:SynchroLog/Attribute:last_error' => 'Last error',
	'Class:SynchroLog/Attribute:traces' => 'Traces',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroReplica/Attribute:dest_id' => 'Destination object (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Destination type',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Last seen',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modified',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'New',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronized',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object Created ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Last Error',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Warnings',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Creation Date',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Last Modified Date',
	'Class:appUserPreferences' => 'User Preferences',
	'Class:appUserPreferences/Attribute:userid' => 'User',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)',

	// Attribute Duration
	'Core:Duration_Seconds'	=> '%1$ds',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',	
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',		
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',		

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%'
));

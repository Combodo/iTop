<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

Dict::Add('EN US', 'English', 'English', array(
	'Core:DeletedObjectLabel' => '%1s (deleted)',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Object not found (class: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.',

	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error',
	'Core:CheckConsistencyError' => 'Consistency rules not followed: %1$s',
	'Core:CheckValueError' => 'Unexpected value for attribute \'%1$s\' (%2$s) : %3$s',

	'Core:AttributeLinkedSet' => 'Array of objects',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass',

	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicates in the \'%1$s\' field : %2$s',

	'Core:AttributeDashboard' => 'Dashboard',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Phone number',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Obsolescence date',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'List of tags',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'click to add',
	'Core:Placeholder:CannotBeResolved' => '(%1$s : cannot be resolved)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)',

	'Core:AttributeCaseLog' => 'Log',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Computed enum',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Yes',
	'Core:AttributeBoolean/Value:no' => 'No',

	'Core:AttributeArchiveFlag' => 'Archive flag',
	'Core:AttributeArchiveFlag/Value:yes' => 'Yes',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode',
	'Core:AttributeArchiveFlag/Value:no' => 'No',
	'Core:AttributeArchiveFlag/Label' => 'Archived',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archive date',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Yes',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolete',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescence date',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => 'Class',
	'Core:AttributeClass+' => '',

	'Core:AttributeApplicationLanguage' => 'User language',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)',

	'Core:AttributeFinalClass' => 'Class (auto)',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password of an external device',

	'Core:AttributeEncryptedString' => 'Encrypted string',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **',

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
	<b>%1$s</b><br/>
	Example: %2$s
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
	<b>%1$s</b><br/>
	Example: %2$s
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

	'Core:FriendlyName-Label' => 'Full name',
	'Core:FriendlyName-Description' => 'Full name',

	'Core:AttributeTag' => 'Tags',
	'Core:AttributeTag+' => '',

	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Console',
	'Core:Context=CRON' => 'cron',
	'Core:Context=GUI:Portal' => 'Portal',
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
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'User interaction in the GUI',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV import script',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'CSV import in the GUI',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email processing',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Synchro. data source',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webservices',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webservices',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'By an extension',
));

//
// Class: CMDBChangeOp
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:CMDBChangeOp' => 'Change Operation',
	'Class:CMDBChangeOp+' => 'Change made by one person, at a single time, on a single object',
	'Class:CMDBChangeOp/Attribute:change' => 'change',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'date',
	'Class:CMDBChangeOp/Attribute:date+' => 'date and time of the change',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'user',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'who made this change',
	'Class:CMDBChangeOp/Attribute:objclass' => 'object class',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'class name of the object on which the change was made',
	'Class:CMDBChangeOp/Attribute:objkey' => 'object id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'id of the object on which the change was made',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'CMDBChangeOp sub-class',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'type of change which was performed',
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
	'Change:TwoAttributesChanged' => 'Edited %1$s and %2$s',
	'Change:ThreeAttributesChanged' => 'Edited %1$s, %2$s and 1 other',
	'Change:FourOrMoreAttributesChanged' => 'Edited %1$s, %2$s and %3$s others',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s set to %2$s (previous value: %3$s)',
	'Change:AttName_SetTo' => '%1$s set to %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s appended to %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modified, previous value: %2$s',
	'Change:AttName_Changed' => '%1$s modified',
	'Change:AttName_EntryAdded' => '%1$s modified, new entry added: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Changed from %2$s to %1$s',
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
	'Class:Event/Attribute:finalclass' => 'Event sub-class',
	'Class:Event/Attribute:finalclass+' => 'Name of the final class: specifies the sort of event which occured',
));

//
// Class: EventNotification
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventNotification' => 'Notification sent',
	'Class:EventNotification+' => 'Trace of a notification that has been sent',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Action',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Object id',
	'Class:EventNotification/Attribute:object_id+' => 'object id (class defined by the trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventNotificationEmail' => 'Email sent',
	'Class:EventNotificationEmail+' => 'Trace of an email that has been sent',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'From',
	'Class:EventNotificationEmail/Attribute:from+' => 'Sender of the message',
	'Class:EventNotificationEmail/Attribute:subject' => 'Subject',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Body',
	'Class:EventNotificationEmail/Attribute:body+' => '',
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
	'Class:EventIssue/Attribute:callstack+' => '',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventRestService' => 'REST/JSON call',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call',
	'Class:EventRestService/Attribute:operation' => 'Operation',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Version',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Input',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Code',
	'Class:EventRestService/Attribute:code+' => 'Result code',
	'Class:EventRestService/Attribute:json_output' => 'Response',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)',
	'Class:EventRestService/Attribute:provider' => 'Provider',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation',
));

//
// Class: EventLoginUsage
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventLoginUsage' => 'Login Usage',
	'Class:EventLoginUsage+' => 'Connection to the application',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email Address of the User',
));

//
// Class: EventNotificationNewsroom
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:EventNotificationNewsroom' => 'News sent',
	'Class:EventNotificationNewsroom+' => '',
	'Class:EventNotificationNewsroom/Attribute:title' => 'Title',
	'Class:EventNotificationNewsroom/Attribute:title+' => '',
	'Class:EventNotificationNewsroom/Attribute:icon' => 'Icon',
	'Class:EventNotificationNewsroom/Attribute:icon+' => '',
	'Class:EventNotificationNewsroom/Attribute:priority' => 'Priority',
	'Class:EventNotificationNewsroom/Attribute:priority+' => '',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1' => 'Critical',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:1+' => 'Critical',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2' => 'Urgent',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:2+' => 'Urgent',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3' => 'Important',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:3+' => 'Important',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4' => 'Standard',
	'Class:EventNotificationNewsroom/Attribute:priority/Value:4+' => 'Standard',
	'Class:EventNotificationNewsroom/Attribute:url' => 'URL',
	'Class:EventNotificationNewsroom/Attribute:url+' => '',
	'Class:EventNotificationNewsroom/Attribute:read' => 'Read',
	'Class:EventNotificationNewsroom/Attribute:read+' => '',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no' => 'No',
	'Class:EventNotificationNewsroom/Attribute:read/Value:no+' => 'No',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes' => 'Yes',
	'Class:EventNotificationNewsroom/Attribute:read/Value:yes+' => 'Yes',
	'Class:EventNotificationNewsroom/Attribute:read_date' => 'Read date',
	'Class:EventNotificationNewsroom/Attribute:read_date+' => '',
	'Class:EventNotificationNewsroom/Attribute:contact_id' => 'Contact',
	'Class:EventNotificationNewsroom/Attribute:contact_id+' => '',
));

//
// Class: Action
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Action'                                  => 'Action',
	'Class:Action+'                                 => 'User defined action',
	'Class:Action/ComplementaryName'                => '%1$s: %2$s',
	'Class:Action/Attribute:name'                   => 'Name',
	'Class:Action/Attribute:name+'                  => 'Any value that is meaningful to distinguish this action from the others',
	'Class:Action/Attribute:description'            => 'Description',
	'Class:Action/Attribute:description+'           => 'A longer explanation about the purpose of this action. For information only.',
	'Class:Action/Attribute:status'                 => 'Status',
	'Class:Action/Attribute:status+'                => 'This status drives the action behavior',
	'Class:Action/Attribute:status/Value:test'      => 'Being tested',
	'Class:Action/Attribute:status/Value:test+'     => '',
	'Class:Action/Attribute:status/Value:enabled'   => 'In production',
	'Class:Action/Attribute:status/Value:enabled+'  => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactive',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Related Triggers',
	'Class:Action/Attribute:trigger_list+' => 'Triggers linked to this action',
	'Class:Action/Attribute:asynchronous' => 'Asynchronous',
	'Class:Action/Attribute:asynchronous+' => 'Whether this action should be executed in background or not',
	'Class:Action/Attribute:asynchronous/Value:use_global_setting' => 'Use global setting',
	'Class:Action/Attribute:asynchronous/Value:yes' => 'Yes',
	'Class:Action/Attribute:asynchronous/Value:no' => 'No',
	'Class:Action/Attribute:finalclass' => 'Action sub-class',
	'Class:Action/Attribute:finalclass+' => 'Name of the final class',
	'Action:WarningNoTriggerLinked' => 'Warning, no trigger is linked to the action. It will not be active until it has at least 1.',
	'Action:last_executions_tab' => 'Last executions',
	'Action:last_executions_tab_panel_title' => 'Executions of this action (%1$s)',
	'Action:last_executions_tab_limit_days' => 'past %1$s days',
	'Action:last_executions_tab_limit_none' => 'no limit',
));

//
// Class: ActionNotification
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ActionNotification' => 'Notification Action',
	'Class:ActionNotification+' => 'Notification Action (abstract)',
	'Class:ActionNotification/Attribute:language' => 'Language',
	'Class:ActionNotification/Attribute:language+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ActionEmail'                                    => 'Notification by Email',
	'Class:ActionEmail+'                                   => '',
	'Class:ActionEmail/Attribute:status+'                  => 'This status drives who will be notified: 
- Being tested: just the Test recipient, 
- In production: all (To, cc and Bcc) 
- Inactive: no-one',
	'Class:ActionEmail/Attribute:status/Value:test+'       => 'Only the Test recipient is notified',
	'Class:ActionEmail/Attribute:status/Value:enabled+'    => 'All To, Cc and Bcc emails are notified',
	'Class:ActionEmail/Attribute:status/Value:disabled+'   => 'The email notification will not be sent',
	'Class:ActionEmail/Attribute:test_recipient'           => 'Test recipient',
	'Class:ActionEmail/Attribute:test_recipient+'          => 'Destination email address used instead of To, Cc and Bcc when notification is being tested',
	'Class:ActionEmail/Attribute:from'                     => 'From (email)',
	'Class:ActionEmail/Attribute:from+'                    => 'Either a static email address or a placeholder like $this->agent_id->email$.
The latest may not be accepted by some email servers.',
	'Class:ActionEmail/Attribute:from_label'               => 'From (label)',
	'Class:ActionEmail/Attribute:from_label+'              => 'Either a static label or a placeholder like $this->agent_id->friendlyname$',
	'Class:ActionEmail/Attribute:reply_to'                 => 'Reply to (email)',
	'Class:ActionEmail/Attribute:reply_to+'                => 'Either a static email address or a placeholder like $this->team_id->email$.
If omitted the From (email) is used.',
	'Class:ActionEmail/Attribute:reply_to_label'           => 'Reply to (label)',
	'Class:ActionEmail/Attribute:reply_to_label+'          => 'Either a static label or a placeholder like $this->team_id->friendlyname$.
If omitted the From (label) is used.',
	'Class:ActionEmail/Attribute:to'                       => 'To',
	'Class:ActionEmail/Attribute:to+'                      => 'To: an OQL query returning objects having an email field.
While editing, click on the magnifier to get pertinent examples.
You can use in the OQL :this->attribute_code with an attribute code of the object which triggered the Notification. Then test your OQL syntax using the play icon.',
	'Class:ActionEmail/Attribute:cc'                       => 'Cc',
	'Class:ActionEmail/Attribute:cc+'                      => 'Carbon Copy: an OQL query returning objects having an email field.
While editing, click on the magnifier to get pertinent examples.
You can use in the OQL :this->attribute_code with an attribute code of the object which triggered the Notification. Then test your OQL syntax using the play icon.',
	'Class:ActionEmail/Attribute:bcc'                      => 'Bcc',
	'Class:ActionEmail/Attribute:bcc+'                     => 'Blind Carbon Copy: an OQL query returning objects having an email field. 
While editing, click on the magnifier to get pertinent examples',
	'Class:ActionEmail/Attribute:subject'                  => 'Subject',
	'Class:ActionEmail/Attribute:subject+'                 => 'Title of the email. Can contain placeholders like $this->attribute_code$',
	'Class:ActionEmail/Attribute:body'                     => 'Body',
	'Class:ActionEmail/Attribute:body+'                    => 'Contents of the email. Can contain placeholders like:
- $this->attribute_code$ any attribute of the object triggering the notification,
- $this->html(attribute_code)$ same as above but displayed in html format,
- $this->hyperlink()$ hyperlink in the backoffice to the object triggering the notification,
- $this->hyperlink(portal)$ hyperlink in the portal to the object triggering the notification,
- $this->head_html(case_log_attribute)$ last reply in html format of a caselog attribute,
- $this->attribute_external_key->attribute$ recursive syntax for any remote attribute,
- $current_contact->attribute$ attribute of the Person who triggered the notification',
	'Class:ActionEmail/Attribute:importance'               => 'importance',
	'Class:ActionEmail/Attribute:importance+'              => 'Importance flag set on the generated email',
	'Class:ActionEmail/Attribute:importance/Value:low'     => 'Low',
	'Class:ActionEmail/Attribute:importance/Value:low+'    => '',
	'Class:ActionEmail/Attribute:importance/Value:normal'  => 'Normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high'    => 'High',
	'Class:ActionEmail/Attribute:importance/Value:high+'   => '',
	'Class:ActionEmail/Attribute:language'                 => 'Language',
	'Class:ActionEmail/Attribute:language+'                => 'Language to use for placeholders ($xxx$) inside the message (state, importance, priority, etc)',
	'Class:ActionEmail/Attribute:html_template'            => 'HTML template',
	'Class:ActionEmail/Attribute:html_template+'           => 'Optional HTML template wrapping around the content of the \'Body\' attribute below, useful for tailored email layouts (in the template, content of the \'Body\' attribute will replace the $content$ placeholder)',
	'Class:ActionEmail/Attribute:ignore_notify'            => 'Ignore the Notify flag',
	'Class:ActionEmail/Attribute:ignore_notify+'           => 'If set to \'Yes\' the \'Notify\' flag on Contacts has no effect.',
	'Class:ActionEmail/Attribute:ignore_notify/Value:no'   => 'No',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes'  => 'Yes',
	'ActionEmail:main'                                     => 'Message',
	'ActionEmail:trigger'                                  => 'Triggers',
	'ActionEmail:recipients'                               => 'Contacts',
	'ActionEmail:preview_tab'                              => 'Preview',
	'ActionEmail:preview_tab+'                             => 'Preview of the eMail template',
	'ActionEmail:preview_warning'                          => 'The actual eMail may look different in the eMail client than this preview in your browser.',
	'ActionEmail:preview_more_info'                        => 'For more information about the CSS features supported by the different eMail clients, refer to %1$s',
	'ActionEmail:content_placeholder_missing'              => 'The placeholder "%1$s" was not found in the HTML template. The content of the field "%2$s" will not be included in the generated emails.',
));


//
// Class: ActionNewsroom
//

Dict::Add('EN US', 'English', 'English', array(
	'ActionNewsroom:trigger' => 'Trigger',
	'ActionNewsroom:content' => 'Message',
	'ActionNewsroom:settings' => 'Settings',
	'Class:ActionNewsroom' => 'Notification by Newsroom',
	'Class:ActionNewsroom+' => '',
	'Class:ActionNewsroom/Attribute:title' => 'Title',
	'Class:ActionNewsroom/Attribute:title+' => 'Title of the news. Can contain placeholders like $this->attribute_code$',
	'Class:ActionNewsroom/Attribute:message' => 'Message',
	'Class:ActionNewsroom/Attribute:message+' => 'Contents of the news, in Markdown format not HTML. Can contain placeholders like:
- $this->attribute_code$ any attribute of the object triggering the notification,
- $this->attribute_external_key->attribute$ recursive syntax for any remote attribute,
- $current_contact->attribute$ attribute of the Person who triggered the notification',
	'Class:ActionNewsroom/Attribute:icon' => 'Icon',
	'Class:ActionNewsroom/Attribute:icon+' => 'Icon to appear next to the news in the newsroom.
- If filled, the custom icon will be used
- Else the icon of the triggering object if there is one (e.g. picture of a Person),
- Else the icon of the triggering object class,
- Otherwise, the application compact logo will be used',
	'Class:ActionNewsroom/Attribute:priority' => 'Priority',
	'Class:ActionNewsroom/Attribute:priority+' => 'News will be ordered by decreasing priority, when displayed in the Newsroom popup',
	'Class:ActionNewsroom/Attribute:priority/Value:1' => 'Critical',
	'Class:ActionNewsroom/Attribute:priority/Value:1+' => 'Critical',
	'Class:ActionNewsroom/Attribute:priority/Value:2' => 'Urgent',
	'Class:ActionNewsroom/Attribute:priority/Value:2+' => 'Urgent',
	'Class:ActionNewsroom/Attribute:priority/Value:3' => 'Important',
	'Class:ActionNewsroom/Attribute:priority/Value:3+' => 'Important',
	'Class:ActionNewsroom/Attribute:priority/Value:4' => 'Standard',
	'Class:ActionNewsroom/Attribute:priority/Value:4+' => 'Standard',
	'Class:ActionNewsroom/Attribute:test_recipient_id' => 'Test recipient',
	'Class:ActionNewsroom/Attribute:test_recipient_id+' => 'Person used instead of Recipients when notification is being tested',
	'Class:ActionNewsroom/Attribute:recipients' => 'Recipients',
	'Class:ActionNewsroom/Attribute:recipients+' => 'An OQL query returning Contact objects',
	'Class:ActionNewsroom/Attribute:url' => 'URL',
	'Class:ActionNewsroom/Attribute:url+' => 'By default, it points to the object triggering the notification. But you can also specify a custom URL.',
));

//
// Class: Trigger
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Trigger'                        => 'Trigger',
	'Class:Trigger+'                       => 'Custom event handler',
	'Class:Trigger/ComplementaryName'      => '%1$s, %2$s',
	'Class:Trigger/Attribute:description'  => 'Description',
	'Class:Trigger/Attribute:description+' => 'Be precise as your users will base their potential unsubscription on this information',
	'Class:Trigger/Attribute:action_list'  => 'Triggered actions',
	'Class:Trigger/Attribute:action_list+' => 'Actions performed when the trigger is activated',
	'Class:Trigger/Attribute:finalclass'   => 'Trigger sub-class',
	'Class:Trigger/Attribute:finalclass+'  => 'Name of the final class',
	'Class:Trigger/Attribute:context'      => 'Context',
	'Class:Trigger/Attribute:context+'     => 'Context to allow the trigger to start',
	'Class:Trigger/Attribute:complement'   => 'Additional information',
	'Class:Trigger/Attribute:complement+'  => 'Computed automatically in english for triggers derived from TriggerOnObject',
	'Class:Trigger/Attribute:subscription_policy'       => 'Subscription policy',
	'Class:Trigger/Attribute:subscription_policy+'      => 'Allows users to unsubscribe from the trigger',
	'Class:Trigger/Attribute:subscription_policy/Value:allow_no_channel' => 'Allow complete unsubscription',
	'Class:Trigger/Attribute:subscription_policy/Value:force_at_least_one_channel' => 'Force at least one channel (News or Email)',
	'Class:Trigger/Attribute:subscription_policy/Value:force_all_channels' => 'Deny unsubscription',
));

//
// Class: TriggerOnObject
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObject'                         => 'Trigger (class dependent)',
	'Class:TriggerOnObject+'                        => 'Trigger on a given class of objects',
	'Class:TriggerOnObject/Attribute:target_class'  => 'Target class',
	'Class:TriggerOnObject/Attribute:target_class+' => 'Objects in this class will activate the trigger',
	'Class:TriggerOnObject/Attribute:filter'        => 'Filter',
	'Class:TriggerOnObject/Attribute:filter+'       => 'Limit the object list (of the target class) which will activate the trigger',
	'TriggerOnObject:WrongFilterQuery'              => 'Wrong filter query: %1$s',
	'TriggerOnObject:WrongFilterClass'              => 'The filter query must return objects of class "%1$s"',
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
// Class: TriggerOnObjectDelete
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (on object deletion)',
	'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (on object update)',
	'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Target fields',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnObjectMention' => 'Trigger (on object mention)',
	'Class:TriggerOnObjectMention+' => 'Trigger on mention (@xxx) of an object of [a child class of] the given class in a log attribute',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Mentioned filter',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limit the list of mentioned objects which will activate the trigger. If empty, any mentioned object (of any class) will activate it.',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Trigger (on object\'s document download)',
	'Class:TriggerOnAttributeBlobDownload+' => 'Trigger on object\'s document field download of [a child class of] the given class',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Target fields',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '',
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
	'Class:SynchroDataSource'                                                       => 'Synchro Data Source',
	'Class:SynchroDataSource/Attribute:name'                                        => 'Name',
	'Class:SynchroDataSource/Attribute:name+'                                       => '',
	'Class:SynchroDataSource/Attribute:description'                                 => 'Description',
	'Class:SynchroDataSource/Attribute:status'                                      => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class'                                 => 'Target class',
	'Class:SynchroDataSource/Attribute:scope_class+'                                => 'A Synchro Data Source can only populate a single '.ITOP_APPLICATION_SHORT.' class',
	'Class:SynchroDataSource/Attribute:user_id'                                     => 'User',
	'Class:SynchroDataSource/Attribute:notify_contact_id'                           => 'Contact to notify',
	'Class:SynchroDataSource/Attribute:notify_contact_id+'                          => 'Contact to notify in case of error',
	'Class:SynchroDataSource/Attribute:url_icon'                                    => 'Icon\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_icon+'                                   => 'Hyperlink a (small) image representing the application with which '.ITOP_APPLICATION_SHORT.' is synchronized.
This icon is shown in the tooltip of the “Lock” symbol on '.ITOP_APPLICATION_SHORT.' synchronized object',
	'Class:SynchroDataSource/Attribute:url_application'                             => 'Application\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_application+'                            => 'Hyperlink to the object in the external application corresponding to a synchronized '.ITOP_APPLICATION_SHORT.' object. 
Possible placeholders: $this->attribute$ and $replica->primary_key$.
The hyperlink is displayed in the tooltip appearing on the “Lock” symbol of any synchronized '.ITOP_APPLICATION_SHORT.' object',
	'Class:SynchroDataSource/Attribute:reconciliation_policy'                       => 'Reconciliation policy',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+'                      => '"Use the attributes": '.ITOP_APPLICATION_SHORT.' object matches replica values for each Synchro attributes flagged for Reconciliation.
"Use primary_key": the column primary_key of the replica is expected to contain the identifier of the '.ITOP_APPLICATION_SHORT.' object',
	'Class:SynchroDataSource/Attribute:full_load_periodicity'                       => 'Full load interval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+'                      => 'A complete reload of all data must occur at least as often as specified here',
	'Class:SynchroDataSource/Attribute:action_on_zero'                              => 'Action on zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+'                             => 'Action taken when the search returns no object',
	'Class:SynchroDataSource/Attribute:action_on_one'                               => 'Action on one',
	'Class:SynchroDataSource/Attribute:action_on_one+'                              => 'Action taken when the search returns exactly one object',
	'Class:SynchroDataSource/Attribute:action_on_multiple'                          => 'Action on many',
	'Class:SynchroDataSource/Attribute:action_on_multiple+'                         => 'Action taken when the search returns more than one object',
	'Class:SynchroDataSource/Attribute:user_delete_policy'                          => 'Users allowed',
	'Class:SynchroDataSource/Attribute:user_delete_policy+'                         => 'Who is allowed to delete synchronized objects',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never'                   => 'Nobody',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends'                 => 'Administrators only',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always'                  => 'All allowed users',
	'Class:SynchroDataSource/Attribute:delete_policy_update'                        => 'Update rules',
	'Class:SynchroDataSource/Attribute:delete_policy_update+'                       => 'A list of "field_name:value;":
"field_name" must be a valid field of the Target class.
"value" must be an authorised value for that field.',
	'Class:SynchroDataSource/Attribute:delete_policy_retention'                     => 'Retention Duration',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+'                    => 'How much time an obsolete object is kept before being deleted',
	'Class:SynchroDataSource/Attribute:database_table_name'                         => 'Data table',
	'Class:SynchroDataSource/Attribute:database_table_name+'                        => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation'                 => 'Implementation',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete'                       => 'Obsolete',
	'Class:SynchroDataSource/Attribute:status/Value:production'                     => 'Production',
	'Class:SynchroDataSource/Attribute:scope_restriction'                           => 'Scope restriction',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes'  => 'Use the attributes',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create'                 => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error'                  => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error'                   => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update'                  => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create'             => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error'              => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first'         => 'Take the first one (random?)',
	'Class:SynchroDataSource/Attribute:delete_policy'                               => 'Delete Policy',
	'Class:SynchroDataSource/Attribute:delete_policy+'                              => 'What to do when a replica becomes obsolete:
"Ignore": do nothing, the associated object remains as is in iTop.
"Delete": Delete the associated object in iTop (and the replica in the data table).
"Update": Update the associated object as specified by the Update rules (see below).
"Update then Delete": apply the "Update rules". When Retention Duration expires, execute a "Delete" ',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete'                  => 'Delete',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore'                  => 'Ignore',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update'                  => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete'      => 'Update then Delete',
	'Class:SynchroDataSource/Attribute:attribute_list'                              => 'Attributes List',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators'     => 'Administrators only',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody'          => 'Everybody allowed to delete such objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody'             => 'Nobody',

	'SynchroDataSource:Description'                                            => 'Description',
	'SynchroDataSource:Reconciliation'                                         => 'Search &amp; reconciliation',
	'SynchroDataSource:Deletion'                                               => 'Deletion rules',
	'SynchroDataSource:Status'                                                 => 'Status',
	'SynchroDataSource:Information'                                            => 'Information',
	'SynchroDataSource:Definition'                                             => 'Definition',
	'Core:SynchroAttributes'                                                   => 'Attributes',
	'Core:SynchroStatus'                                                       => 'Status',
	'Core:Synchro:ErrorsLabel'                                                 => 'Errors',
	'Core:Synchro:CreatedLabel'                                                => 'Created',
	'Core:Synchro:ModifiedLabel'                                               => 'Modified',
	'Core:Synchro:UnchangedLabel'                                              => 'Unchanged',
	'Core:Synchro:ReconciledErrorsLabel'                                       => 'Errors',
	'Core:Synchro:ReconciledLabel'                                             => 'Reconciled',
	'Core:Synchro:ReconciledNewLabel'                                          => 'Created',
	'Core:SynchroReconcile:Yes'                                                => 'Yes',
	'Core:SynchroReconcile:No'                                                 => 'No',
	'Core:SynchroUpdate:Yes'                                                   => 'Yes',
	'Core:SynchroUpdate:No'                                                    => 'No',
	'Core:Synchro:LastestStatus'                                               => 'Latest Status',
	'Core:Synchro:History'                                                     => 'Synchronization History',
	'Core:Synchro:NeverRun'                                                    => 'This synchro was never run. No log yet.',
	'Core:Synchro:SynchroEndedOn_Date'                                         => 'The latest synchronization ended on %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date'                                => 'The synchronization started on %1$s is still running...',
	'Core:Synchro:label_repl_ignored'                                          => 'Ignored (%1$s)',
	'Core:Synchro:label_repl_disappeared'                                      => 'Disappeared (%1$s)',
	'Core:Synchro:label_repl_existing'                                         => 'Existing (%1$s)',
	'Core:Synchro:label_repl_new'                                              => 'New (%1$s)',
	'Core:Synchro:label_obj_deleted'                                           => 'Deleted (%1$s)',
	'Core:Synchro:label_obj_obsoleted'                                         => 'Obsoleted (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors'                                => 'Errors (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action'                             => 'No Action (%1$s)',
	'Core:Synchro:label_obj_unchanged'                                         => 'Unchanged (%1$s)',
	'Core:Synchro:label_obj_updated'                                           => 'Updated (%1$s)',
	'Core:Synchro:label_obj_updated_errors'                                    => 'Errors (%1$s)',
	'Core:Synchro:label_obj_new_unchanged'                                     => 'Unchanged (%1$s)',
	'Core:Synchro:label_obj_new_updated'                                       => 'Updated (%1$s)',
	'Core:Synchro:label_obj_created'                                           => 'Created (%1$s)',
	'Core:Synchro:label_obj_new_errors'                                        => 'Errors (%1$s)',
	'Core:SynchroLogTitle'                                                     => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica'                                                  => 'Replica processed: %1$s',
	'Core:Synchro:Nb_Class:Objects'                                            => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified'     => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified'          => 'Obsolete objects are to be updated, but no update is specified.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists'                     => 'The table %1$s already exists in the database. Please use another name for the synchro data table.',
	'Core:SynchroReplica:PublicData'                                           => 'Public Data',
	'Core:SynchroReplica:PrivateDetails'                                       => 'Private Details',
	'Core:SynchroReplica:BackToDataSource'                                     => 'Go Back to the Synchro Data Source: %1$s',
	'Core:SynchroReplica:ListOfReplicas'                                       => 'List of Replica',
	'Core:SynchroAttExtKey:ReconciliationById'                                 => 'id (Primary Key)',
	'Core:SynchroAtt:attcode'                                                  => 'Attribute',
	'Core:SynchroAtt:attcode+'                                                 => 'Field of the object',
	'Core:SynchroAtt:reconciliation'                                           => 'Reconciliation ?',
	'Core:SynchroAtt:reconciliation+'                                          => 'Used for searching',
	'Core:SynchroAtt:update'                                                   => 'Update ?',
	'Core:SynchroAtt:update+'                                                  => 'Used to update the object',
	'Core:SynchroAtt:update_policy'                                            => 'Update Policy',
	'Core:SynchroAtt:update_policy+'                                           => 'Behavior of the updated field',
	'Core:SynchroAtt:reconciliation_attcode'                                   => 'Reconciliation Key',
	'Core:SynchroAtt:reconciliation_attcode+'                                  => 'Attribute Code for the External Key Reconciliation',
	'Core:SyncDataExchangeComment'                                             => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources'                                           => 'List of data sources:',
	'Core:Synchro:LastSynchro'                                                 => 'Last synchronization:',
	'Core:Synchro:ThisObjectIsSynchronized'                                    => 'This object is synchronized with an external data source',
	'Core:Synchro:TheObjectWasCreatedBy_Source'                                => 'The object was <b>created</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source'                              => 'The object <b>can be deleted</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source'                       => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s',
	'TitleSynchroExecution'                                                    => 'Execution of the synchronization',
	'Class:SynchroDataSource:DataTable'                                        => 'Database table: %1$s',
	'Core:SyncDataSourceObsolete'                                              => 'The data source is marked as obsolete. Operation cancelled.',
	'Core:SyncDataSourceAccessRestriction'                                     => 'Only adminstrators or the user specified in the data source can execute this operation. Operation cancelled.',
	'Core:SyncTooManyMissingReplicas'                                          => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.',
	'Core:SyncSplitModeCLIOnly'                                                => 'The synchronization can be executed in chunks only if run in mode CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings'                    => '%1$s replicas, %2$s error(s), %3$s warning(s).',
	'Core:SynchroReplica:TargetObject'                                         => 'Synchronized Object: %1$s',
	'Class:AsyncSendEmail'                                                     => 'Email (asynchronous)',
	'Class:AsyncSendEmail/Attribute:to'                                        => 'To',
	'Class:AsyncSendEmail/Attribute:subject'                                   => 'Subject',
	'Class:AsyncSendEmail/Attribute:body'                                      => 'Body',
	'Class:AsyncSendEmail/Attribute:header'                                    => 'Header',
	'Class:CMDBChangeOpSetAttributeOneWayPassword'                             => 'Encrypted Password',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd'          => 'Previous Value',
	'Class:CMDBChangeOpSetAttributeEncrypted'                                  => 'Encrypted Field',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring'             => 'Previous Value',
	'Class:CMDBChangeOpSetAttributeCaseLog'                                    => 'Case Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry'                => 'Last Entry',
	'Class:SynchroAttribute'                                                   => 'Synchro Attribute',
	'Class:SynchroAttribute/Attribute:sync_source_id'                          => 'Synchro Data Source',
	'Class:SynchroAttribute/Attribute:attcode'                                 => 'Attribute Code',
	'Class:SynchroAttribute/Attribute:update'                                  => 'Update',
	'Class:SynchroAttribute/Attribute:reconcile'                               => 'Reconcile',
	'Class:SynchroAttribute/Attribute:update_policy'                           => 'Update Policy',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked'       => 'Locked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked'     => 'Unlocked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty'      => 'Initialize if empty',
	'Class:SynchroAttribute/Attribute:finalclass'                              => 'Class',
	'Class:SynchroAttExtKey'                                                   => 'Synchro Attribute (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode'                  => 'Reconciliation Attribute',
	'Class:SynchroAttLinkSet'                                                  => 'Synchro Attribute (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator'                          => 'Rows separator',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator'                    => 'Attributes separator',
	'Class:SynchroLog'                                                         => 'Synchr Log',
	'Class:SynchroLog/Attribute:sync_source_id'                                => 'Synchro Data Source',
	'Class:SynchroLog/Attribute:start_date'                                    => 'Start Date',
	'Class:SynchroLog/Attribute:end_date'                                      => 'End Date',
	'Class:SynchroLog/Attribute:status'                                        => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed'                        => 'Completed',
	'Class:SynchroLog/Attribute:status/Value:error'                            => 'Error',
	'Class:SynchroLog/Attribute:status/Value:running'                          => 'Still Running',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen'                         => 'Nb replica seen',
	'Class:SynchroLog/Attribute:stats_nb_replica_total'                        => 'Nb replica total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted'                          => 'Nb objects deleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors'                   => 'Nb of errors while deleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted'                        => 'Nb objects obsoleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors'                 => 'Nb of errors while obsoleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_created'                          => 'Nb objects created',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors'                   => 'Nb or errors while creating',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated'                          => 'Nb objects updated',
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
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter "query". There is no Query Phrasebook corresponding to the id: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s',
	'Core:BulkExport:ExportResult' => 'Result of the export:',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above',
	'Core:BulkExport:ColumnsOrder' => 'Columns order',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported',
	'Core:BulkExport:CheckAll' => 'Check All',
	'Core:BulkExport:UncheckAll' => 'Uncheck All',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user',
	'Core:BulkExport:CSVOptions' => 'CSV Options',
	'Core:BulkExport:CSVLocalization' => 'Localization',
	'Core:BulkExport:PDFOptions' => 'PDF Options',
	'Core:BulkExport:PDFPageFormat' => 'Page Format',
	'Core:BulkExport:PDFPageSize' => 'Page Size:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:',
	'Core:BulkExport:PageOrientation-L' => 'Landscape',
	'Core:BulkExport:PageOrientation-P' => 'Portrait',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML Options',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options',
	'Core:BulkExport:OptionNoLocalize' => 'Export Code instead of Label',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects',
	'Core:BulkExport:OptionFormattedText' => 'Preserve text formatting',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.',
	'Core:BulkExport:XLSXOptions' => 'Excel Options',
	'Core:BulkExport:TextFormat' => 'Text fields containing some HTML markup',
	'Core:BulkExport:DateTimeFormat' => 'Date and Time format',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Default format (%1$s), e.g. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Custom format: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Page %1$s',
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
	'Core:Validator:Default' => 'Wrong format',
	'Core:Validator:Mandatory' => 'Please, fill this field',
	'Core:Validator:MustBeInteger' => 'Must be an integer',
	'Core:Validator:MustSelectOne' => 'Please, select one',
));

//
// Class: TagSetFieldData
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:TagSetFieldData' => '%2$s for class %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Code',
	'Class:TagSetFieldData/Attribute:code+' => 'Internal code. Must contain at least 3 alphanumeric characters',
	'Class:TagSetFieldData/Attribute:label' => 'Label',
	'Class:TagSetFieldData/Attribute:label+' => 'Displayed label',
	'Class:TagSetFieldData/Attribute:description' => 'Description',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Used tags cannot be deleted',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tags codes or labels must be unique',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Tags code must contain between 3 and %1$d alphanumeric characters, starting with a letter.',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'The chosen tag code is a reserved word',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Tags label must not contain \'%1$s\' nor be empty',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags Code cannot be changed when used',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" cannot be changed',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Attribute Code" cannot be changed',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Tag usage (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'No entry found for this tag',
));

//
// Class: DBProperty
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:DBProperty' => 'DB property',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Name',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Description',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Value',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Change date',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Change comment',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:BackgroundTask' => 'Background task',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Class name',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'First run date',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Latest run date',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Next run date',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. count',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Latest run duration',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. run duration',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. run duration',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Average run duration',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Running',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('EN US', 'English', 'English', array(
	'Class:AsyncTask' => 'Async. task',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Created',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Started',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Planned',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Event',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Final class',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Status',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Remaining retries',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Last error code',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Last error',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Last attempt',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]". Expecting an array with the following keys: %2$s',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]": unexpected key "%2$s". Expecting only the following keys: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:AbstractResource' => 'Abstract Resource',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ResourceAdminMenu' => 'Resource Admin Menu',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ResourceRunQueriesMenu' => 'Resource Run Queries Menu',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ResourceSystemMenu' => 'Resource System Menu',
	'Class:ResourceSystemMenu+' => '',
));




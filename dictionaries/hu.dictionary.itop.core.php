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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Core:DeletedObjectLabel' => '%1s (deleted)~~',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)~~',

	'Core:UnknownObjectLabel' => 'Object not found (class: %1$s, id: %2$d)~~',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.~~',

	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',

	'Core:AttributeLinkedSet' => 'Objektum tömbök',
	'Core:AttributeLinkedSet+' => '',

	'Core:AttributeDashboard' => 'Dashboard~~',
	'Core:AttributeDashboard+' => '~~',

	'Core:AttributePhoneNumber' => 'Phone number~~',
	'Core:AttributePhoneNumber+' => '~~',

	'Core:AttributeObsolescenceDate' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate+' => '~~',

	'Core:AttributeTagSet' => 'List of tags~~',
	'Core:AttributeTagSet+' => '~~',
	'Core:AttributeSet:placeholder' => 'click to add~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)~~',

	'Core:AttributeCaseLog' => 'Log~~',
	'Core:AttributeCaseLog+' => '~~',

	'Core:AttributeMetaEnum' => 'Computed enum~~',
	'Core:AttributeMetaEnum+' => '~~',

	'Core:AttributeLinkedSetIndirect' => 'Objektum tömbök (N-N)',
	'Core:AttributeLinkedSetIndirect+' => '',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => '',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => '',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Yes~~',
	'Core:AttributeBoolean/Value:no' => 'No~~',

	'Core:AttributeArchiveFlag' => 'Archive flag~~',
	'Core:AttributeArchiveFlag/Value:yes' => 'Yes~~',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode~~',
	'Core:AttributeArchiveFlag/Value:no' => 'No~~',
	'Core:AttributeArchiveFlag/Label' => 'Archived~~',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archive date~~',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag~~',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Yes~~',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results~~',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No~~',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolete~~',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes~~',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete~~',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => '',

	'Core:AttributeClass' => 'Class',
	'Core:AttributeClass+' => '',

	'Core:AttributeApplicationLanguage' => 'Alkalmazás nyelve',
	'Core:AttributeApplicationLanguage+' => '',

	'Core:AttributeFinalClass' => 'Class (auto)',
	'Core:AttributeFinalClass+' => '',

	'Core:AttributePassword' => 'Jelszó',
	'Core:AttributePassword+' => '',

	'Core:AttributeEncryptedString' => 'Encrypted string',
	'Core:AttributeEncryptedString+' => '',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown~~',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **~~',

	'Core:AttributeText' => 'Text',
	'Core:AttributeText+' => '',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => '',

	'Core:AttributeEmailAddress' => 'E-mail cím',
	'Core:AttributeEmailAddress+' => '',

	'Core:AttributeIPAddress' => 'IP cím',
	'Core:AttributeIPAddress+' => '',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => '',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => '',

	'Core:AttributeTemplateString' => 'Sablon szöveg',
	'Core:AttributeTemplateString+' => '',

	'Core:AttributeTemplateText' => 'Sablon szöveg',
	'Core:AttributeTemplateText+' => '',

	'Core:AttributeTemplateHTML' => 'Sablon HTML',
	'Core:AttributeTemplateHTML+' => '',

	'Core:AttributeDateTime' => 'Date/time',
	'Core:AttributeDateTime+' => '',
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
</p>~~',

	'Core:AttributeDate' => 'Date',
	'Core:AttributeDate+' => '',
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
</p>~~',

	'Core:AttributeDeadline' => 'Határidő',
	'Core:AttributeDeadline+' => '',

	'Core:AttributeExternalKey' => 'Külső kulcs',
	'Core:AttributeExternalKey+' => '',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent~~',

	'Core:AttributeExternalField' => 'Külső mező',
	'Core:AttributeExternalField+' => '',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '',

	'Core:AttributeOneWayPassword' => 'One way password',
	'Core:AttributeOneWayPassword+' => '',

	'Core:AttributeTable' => 'Table',
	'Core:AttributeTable+' => '',

	'Core:AttributePropertySet' => 'Tulajdonságok',
	'Core:AttributePropertySet+' => '',

	'Core:AttributeFriendlyName' => 'Friendly name~~',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes~~',

	'Core:FriendlyName-Label' => 'Friendly name~~',
	'Core:FriendlyName-Description' => 'Friendly name~~',

	'Core:AttributeTag' => 'Tags~~',
	'Core:AttributeTag+' => 'Tags~~',
	
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

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChange' => 'Változás',
	'Class:CMDBChange+' => '',
	'Class:CMDBChange/Attribute:date' => 'Dátum',
	'Class:CMDBChange/Attribute:date+' => '',
	'Class:CMDBChange/Attribute:userinfo' => 'Egyéb információ',
	'Class:CMDBChange/Attribute:userinfo+' => '',
));

//
// Class: CMDBChangeOp
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOp' => 'Változtatás művelet',
	'Class:CMDBChangeOp+' => '',
	'Class:CMDBChangeOp/Attribute:change' => 'Válktozás',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'Dátum',
	'Class:CMDBChangeOp/Attribute:date+' => '',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Felhasználó',
	'Class:CMDBChangeOp/Attribute:userinfo+' => '',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Objektum osztály',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Objektum azonosító',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Típus',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpCreate' => 'Objektum létrehozás',
	'Class:CMDBChangeOpCreate+' => '',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpDelete' => 'Objektum törlés',
	'Class:CMDBChangeOpDelete+' => '',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttribute' => 'Objektum változtatás',
	'Class:CMDBChangeOpSetAttribute+' => '',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribútum',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Tulajdonság változtatás',
	'Class:CMDBChangeOpSetAttributeScalar+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Új érték',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Change:ObjectCreated' => 'Objektum létrehozva',
	'Change:ObjectDeleted' => 'Objektum törölve',
	'Change:ObjectModified' => 'Objektum módosítva',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s új értéke: %2$s (előző értéke: %3$s)',
	'Change:AttName_SetTo' => '%1$s új értéke %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s hozzáfűzve %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s módosítva, előző érték: %2$s',
	'Change:AttName_Changed' => '%1$s módosítva',
	'Change:AttName_EntryAdded' => '%1$s módosítva, új bejegyzés hozzáadva.',
	'Change:LinkSet:Added' => 'added %1$s~~',
	'Change:LinkSet:Removed' => 'removed %1$s~~',
	'Change:LinkSet:Modified' => 'modified %1$s~~',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Módosítás dátuma',
	'Class:CMDBChangeOpSetAttributeBlob+' => '',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Előző adat',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Szöveg változás',
	'Class:CMDBChangeOpSetAttributeText+' => '',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Előző adat',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '',
));

//
// Class: Event
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Event' => 'Napló esemény',
	'Class:Event+' => '',
	'Class:Event/Attribute:message' => 'Üzenet',
	'Class:Event/Attribute:message+' => '',
	'Class:Event/Attribute:date' => 'Dátum',
	'Class:Event/Attribute:date+' => '',
	'Class:Event/Attribute:userinfo' => 'Felhasználói információ',
	'Class:Event/Attribute:userinfo+' => '',
	'Class:Event/Attribute:finalclass' => 'Típus',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventNotification' => 'Értesítés esemény',
	'Class:EventNotification+' => '',
	'Class:EventNotification/Attribute:trigger_id' => 'Kiváltó ok',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Felhasználó',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Objektum azonosító',
	'Class:EventNotification/Attribute:object_id+' => '',
));

//
// Class: EventNotificationEmail
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventNotificationEmail' => 'E-mail küldés esemény',
	'Class:EventNotificationEmail+' => '',
	'Class:EventNotificationEmail/Attribute:to' => 'Címzett',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'Másolatot kap',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Titkos másolatot kap',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'Feladó',
	'Class:EventNotificationEmail/Attribute:from+' => '',
	'Class:EventNotificationEmail/Attribute:subject' => 'Tárgy',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Szöveg',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Attachments~~',
	'Class:EventNotificationEmail/Attribute:attachments+' => '~~',
));

//
// Class: EventIssue
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventIssue' => 'Kérés esemény',
	'Class:EventIssue+' => '',
	'Class:EventIssue/Attribute:issue' => 'Kérés',
	'Class:EventIssue/Attribute:issue+' => '',
	'Class:EventIssue/Attribute:impact' => 'Hatás',
	'Class:EventIssue/Attribute:impact+' => '',
	'Class:EventIssue/Attribute:page' => 'Oldal',
	'Class:EventIssue/Attribute:page+' => '',
	'Class:EventIssue/Attribute:arguments_post' => 'Kérés részletei',
	'Class:EventIssue/Attribute:arguments_post+' => '',
	'Class:EventIssue/Attribute:arguments_get' => 'URL ',
	'Class:EventIssue/Attribute:arguments_get+' => '',
	'Class:EventIssue/Attribute:callstack' => 'Híváslista',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Dátum',
	'Class:EventIssue/Attribute:data+' => '',
));

//
// Class: EventWebService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventWebService' => 'Web szolgáltatás esemény',
	'Class:EventWebService+' => '',
	'Class:EventWebService/Attribute:verb' => 'Kérés',
	'Class:EventWebService/Attribute:verb+' => '',
	'Class:EventWebService/Attribute:result' => 'Eredmény',
	'Class:EventWebService/Attribute:result+' => '',
	'Class:EventWebService/Attribute:log_info' => 'Info napló',
	'Class:EventWebService/Attribute:log_info+' => '',
	'Class:EventWebService/Attribute:log_warning' => 'Warning napló',
	'Class:EventWebService/Attribute:log_warning+' => '',
	'Class:EventWebService/Attribute:log_error' => 'Error napló',
	'Class:EventWebService/Attribute:log_error+' => '',
	'Class:EventWebService/Attribute:data' => 'Adat',
	'Class:EventWebService/Attribute:data+' => '',
));

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventRestService' => 'REST/JSON call~~',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call~~',
	'Class:EventRestService/Attribute:operation' => 'Operation~~',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'~~',
	'Class:EventRestService/Attribute:version' => 'Version~~',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'~~',
	'Class:EventRestService/Attribute:json_input' => 'Input~~',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'~~',
	'Class:EventRestService/Attribute:code' => 'Code~~',
	'Class:EventRestService/Attribute:code+' => 'Result code~~',
	'Class:EventRestService/Attribute:json_output' => 'Response~~',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)~~',
	'Class:EventRestService/Attribute:provider' => 'Provider~~',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation~~',
));

//
// Class: EventLoginUsage
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventLoginUsage' => 'Belépés esemény',
	'Class:EventLoginUsage+' => '',
	'Class:EventLoginUsage/Attribute:user_id' => 'Felhasználó név',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Felhasználó neve',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Felhasználó e-mail',
	'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Action' => 'Egyedi akciók',
	'Class:Action+' => '',
	'Class:Action/Attribute:name' => 'Neve',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Leírás',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Státusz',
	'Class:Action/Attribute:status+' => '',
	'Class:Action/Attribute:status/Value:test' => 'Tesztelés alatt',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'Éles üzemeben',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inaktív',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Kapcsolódó kiváltó okok',
	'Class:Action/Attribute:trigger_list+' => '',
	'Class:Action/Attribute:finalclass' => 'Típus',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ActionNotification' => 'Értesítés',
	'Class:ActionNotification+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ActionEmail' => 'E-mail értesítés',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Teszt címzett',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'Feladó',
	'Class:ActionEmail/Attribute:from+' => '',
	'Class:ActionEmail/Attribute:reply_to' => 'Válasz',
	'Class:ActionEmail/Attribute:reply_to+' => '',
	'Class:ActionEmail/Attribute:to' => 'Címzett',
	'Class:ActionEmail/Attribute:to+' => '',
	'Class:ActionEmail/Attribute:cc' => 'Másolatot kap',
	'Class:ActionEmail/Attribute:cc+' => '',
	'Class:ActionEmail/Attribute:bcc' => 'Titkos másolatot kap',
	'Class:ActionEmail/Attribute:bcc+' => '',
	'Class:ActionEmail/Attribute:subject' => 'Tárgy',
	'Class:ActionEmail/Attribute:subject+' => '',
	'Class:ActionEmail/Attribute:body' => 'Szöveg',
	'Class:ActionEmail/Attribute:body+' => '',
	'Class:ActionEmail/Attribute:importance' => 'Fontosság',
	'Class:ActionEmail/Attribute:importance+' => '',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Nem fontos',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normál',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Fontos',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
));

//
// Class: Trigger
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Trigger' => 'Kiváltó ok',
	'Class:Trigger+' => '',
	'Class:Trigger/Attribute:description' => 'Leírás',
	'Class:Trigger/Attribute:description+' => '',
	'Class:Trigger/Attribute:action_list' => 'Kiváltott akció',
	'Class:Trigger/Attribute:action_list+' => '',
	'Class:Trigger/Attribute:finalclass' => 'Típus',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObject' => 'Kiváltó ok (osztály függő)',
	'Class:TriggerOnObject+' => '',
	'Class:TriggerOnObject/Attribute:target_class' => 'Cél osztály',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'Class:TriggerOnObject/Attribute:filter+' => '~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \\"%1$s\\"~~',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (when updated from the portal)~~',
	'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal~~',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateChange' => 'Kiváltó ok (állapot változás)',
	'Class:TriggerOnStateChange+' => '',
	'Class:TriggerOnStateChange/Attribute:state' => 'Állapot',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateEnter' => 'Kiváltó ok (állapotba belépés)',
	'Class:TriggerOnStateEnter+' => '',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateLeave' => 'Kiváltó ok (állapot elhagyás)',
	'Class:TriggerOnStateLeave+' => '',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectCreate' => 'Kiváltó ok (objektum létrehozás)',
	'Class:TriggerOnObjectCreate+' => '',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (on object deletion)~~',
	'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class~~',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (on object update)~~',
	'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (on threshold)~~',
	'Class:TriggerOnThresholdReached+' => 'Trigger on Stop-Watch threshold reached~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Threshold~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '~~',
));

//
// Class: lnkTriggerAction
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkTriggerAction' => 'Akció / Kiváltó ok',
	'Class:lnkTriggerAction+' => '',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Akció',
	'Class:lnkTriggerAction/Attribute:action_id+' => '',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Akció',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Kiváltó ok',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Kiváltó ok',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Sorrend',
	'Class:lnkTriggerAction/Attribute:order+' => '',
));

//
// Synchro Data Source
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SynchroDataSource/Attribute:name' => 'Neve',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Leírás',
	'Class:SynchroDataSource/Attribute:status' => 'Státusz',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Cél osztály',
	'Class:SynchroDataSource/Attribute:user_id' => 'Felhasználó',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact to notify~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error~~',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Ikonok URL-je',
	'Class:SynchroDataSource/Attribute:url_icon+' => '',
	'Class:SynchroDataSource/Attribute:url_application' => 'Alkalmazások URL-je',
	'Class:SynchroDataSource/Attribute:url_application+' => '',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Egyeztetési szabály',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Teljesen feltöltött intervallum',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => '',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Action on zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Action on one',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Action on many',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => '',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Engedélyezett felhasználók',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => '',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Senki',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Csak adminisztrátorok',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Tíltott felhasználók',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Szabályok frissítése',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => '',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Késleltetés időtartama',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => '',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table~~',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.~~',
	'SynchroDataSource:Description' => 'Leírás',
	'SynchroDataSource:Reconciliation' => 'Keresés &amp; rekponsziliálás',
	'SynchroDataSource:Deletion' => 'Törlés szabályai',
	'SynchroDataSource:Status' => 'Státusz',
	'SynchroDataSource:Information' => 'Információ',
	'SynchroDataSource:Definition' => 'Meghatározás',
	'Core:SynchroAttributes' => 'Attribútumok',
	'Core:SynchroStatus' => 'Státusz',
	'Core:Synchro:ErrorsLabel' => 'Hibák',
	'Core:Synchro:CreatedLabel' => 'Létrehozva',
	'Core:Synchro:ModifiedLabel' => 'Módosítva',
	'Core:Synchro:UnchangedLabel' => 'Változatlan',
	'Core:Synchro:ReconciledErrorsLabel' => 'Hibák',
	'Core:Synchro:ReconciledLabel' => 'Rekonsziliált',
	'Core:Synchro:ReconciledNewLabel' => 'Létrehozva',
	'Core:SynchroReconcile:Yes' => 'Igen',
	'Core:SynchroReconcile:No' => 'Nem',
	'Core:SynchroUpdate:Yes' => 'Igen',
	'Core:SynchroUpdate:No' => 'Nem',
	'Core:Synchro:LastestStatus' => 'Utolsó státusz',
	'Core:Synchro:History' => 'Szinkronizáció történet',
	'Core:Synchro:NeverRun' => 'Ez a szinkronizáció még soha nem futott. Nincs még napló bejegyzés.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Az utolsó szinkronizáció lefutásának időpontja: %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Az szinkronizáció elindut %1$s, de még fut.',
	'Menu:DataSources' => 'Szinkronizált adatforrások', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Figyelmen kívül hagyott (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Elveszett (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Létező (%1$s)',
	'Core:Synchro:label_repl_new' => 'Új (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Törölt (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Elavult (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Hibák (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Beavatkozás nem szükséges (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Változatan (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Frisített (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Hibák (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Változatlan (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Frissített (%1$s)',
	'Core:Synchro:label_obj_created' => 'Létrehozott (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Hibák (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Másolat elkészítve: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Egyeztetéshez legalább egy kulcsot meg kell adni, egyébként az egyeztetés az elsődleges kulcs alapján történik.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A törlés késleltetésének időtartamát meg kell adni, egyébként az objektum törölve lesz annak elavulttá minősítése után.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Lejárt objektumok frissítése nem tud megtörténni.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'The table %1$s already exists in the database. Please use another name for the synchro data table.~~',
	'Core:SynchroReplica:PublicData' => 'Publikus adatok',
	'Core:SynchroReplica:PrivateDetails' => 'Privát adatok',
	'Core:SynchroReplica:BackToDataSource' => 'Vissza a következő szinkron adatforráshoz: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Másolatok listája',
	'Core:SynchroAttExtKey:ReconciliationById' => 'Azonosító (Elsődleges kulcs)',
	'Core:SynchroAtt:attcode' => 'Attribútum',
	'Core:SynchroAtt:attcode+' => '',
	'Core:SynchroAtt:reconciliation' => 'Egyeztetés?',
	'Core:SynchroAtt:reconciliation+' => '',
	'Core:SynchroAtt:update' => 'Frissített?',
	'Core:SynchroAtt:update+' => '',
	'Core:SynchroAtt:update_policy' => 'Frissítési szabály',
	'Core:SynchroAtt:update_policy+' => '',
	'Core:SynchroAtt:reconciliation_attcode' => 'Egyeztetés kulcsa',
	'Core:SynchroAtt:reconciliation_attcode+' => '',
	'Core:SyncDataExchangeComment' => '(DataExchange)',
	'Core:Synchro:ListOfDataSources' => 'Adatforrások listája',
	'Core:Synchro:LastSynchro' => 'Utolsó szimkronizáció',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Az objektum szinkronizálva a külső adatforrással.',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Objektum <b>létrehozva</b> a következő adatforrásban: %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Objektum <b>törölhető</b> a következő külső adatforrásból: %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => '<b>Objektum nem törölhető</b> mert egy másik adatforrás (%1$s) tulajdona',
	'TitleSynchroExecution' => 'Szinkronizáció végrehajtás',
	'Class:SynchroDataSource:DataTable' => 'Adatbázis tábla: %1$s',
	'Core:SyncDataSourceObsolete' => 'Az adatforrás elvalultnak van jelölve. Művelet visszavonva.',
	'Core:SyncDataSourceAccessRestriction' => 'Csak az adminisztrátor vagy speciális jogokkal rendelkező felhasználó futtathatja a műveletet. Művelet visszavonva.',
	'Core:SyncTooManyMissingReplicas' => 'Import során az összes másolat elveszett. Az import valóban lefutott? Művelet visszavonva.',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI~~',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).~~',
	'Core:SynchroReplica:TargetObject' => 'Synchronized Object: %1$s~~',
	'Class:AsyncSendEmail' => 'E-mail (aszinkron)',
	'Class:AsyncSendEmail/Attribute:to' => 'Címzett',
	'Class:AsyncSendEmail/Attribute:subject' => 'Tárgy',
	'Class:AsyncSendEmail/Attribute:body' => 'Szöveg',
	'Class:AsyncSendEmail/Attribute:header' => 'Fejléc',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Titkosított jelszó',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Titkosított mező',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Esemény napló',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Utolsó bejegyzés',
	'Class:SynchroDataSource' => 'Szinkron adatforrás',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Megvalósított',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Éles üzemben',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Tartalom szűkítés',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'A következő attribútum használata',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Elsődleges kulcs használata',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Létrehozás',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Firssítés',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Létrehozás',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Take the first one (véletlen?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Törlési szabály',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Törlés',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Figyelmen kívül hagyás',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Frissítés',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Frissítés után törlés',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attribútum lista',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Csak rendszergazdák',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Mindenkinek engedélyezett az objektumok törlése',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Senki',
	'Class:SynchroAttribute' => 'Szinkron attribútumok',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Szinkron adatforrás',
	'Class:SynchroAttribute/Attribute:attcode' => 'Kód',
	'Class:SynchroAttribute/Attribute:update' => 'Frissítés',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Egyeztetés',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Frissítési irányelv',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Locked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Unlocked',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Inicializálás ha üres',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Osztály',
	'Class:SynchroAttExtKey' => 'Szinkron attribútum (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Egyeztetés attribútuma',
	'Class:SynchroAttLinkSet' => 'Szinkron attribútum (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Sor elválasztó',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attribútum elválasztó',
	'Class:SynchroLog' => 'Szinkron napló',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Szinkron adatforrás',
	'Class:SynchroLog/Attribute:start_date' => 'Kezdés dátuma',
	'Class:SynchroLog/Attribute:end_date' => 'Befejezés dátuma',
	'Class:SynchroLog/Attribute:status' => 'Státusz',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Hibátlanul lefutott',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Hibás',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Még fut',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replikáció létrejött',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replikáció összesen',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objektumok törölve',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb hibái törlés közben',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objketumok elavultak',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb hibák elavulás közben',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objketumok létrehozva',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb hibák létrehozás közben',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objektum frissítve',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb hibák firssítés közben',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb hibák rekonsziliálás közben',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replikáció eltűnt',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objketumok frissítve',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objketumok változatlanok',
	'Class:SynchroLog/Attribute:last_error' => 'Utolsó hiba',
	'Class:SynchroLog/Attribute:traces' => 'Trace',
	'Class:SynchroReplica' => 'Szinkron másolat',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Szinkron adatforrás',
	'Class:SynchroReplica/Attribute:dest_id' => 'Cél objektum azonosító',
	'Class:SynchroReplica/Attribute:dest_class' => 'Cél típusa',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Utolsó megtekintett',
	'Class:SynchroReplica/Attribute:status' => 'Státusz',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Módosított',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Új',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Árva',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Szinkronizált',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objektum létrehozott?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Utolsó hiba',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Warnings~~',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Létrehozás dátuma',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Utolsó módosítás dátuma',
	'Class:appUserPreferences' => 'Felhasználói beállítások',
	'Class:appUserPreferences/Attribute:userid' => 'Felhasználó',
	'Class:appUserPreferences/Attribute:preferences' => 'Beállítások',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)~~',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)~~',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$sds',
	'Core:Duration_Minutes_Seconds' => '%1$sdmin %2$sds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$sdh %2$sdmin %3$sds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$sdh %3$sdmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as \\"%1$s\\")~~',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for \\"%1$s\\"~~',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for \\"%1$s\\" at %2$d%%~~',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter \\"%1$s\\"~~',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter \\"query\\". There is no Query Phrasebook corresponding to the id: \\"%1$s\\".~~',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:~~',
	'Core:BulkExportOf_Class' => '%1$s Export~~',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s~~',
	'Core:BulkExport:ExportResult' => 'Result of the export:~~',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...~~',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)~~',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)~~',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)~~',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)~~',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.~~',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above~~',
	'Core:BulkExport:ColumnsOrder' => 'Columns order~~',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s~~',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported~~',
	'Core:BulkExport:CheckAll' => 'Check All~~',
	'Core:BulkExport:UncheckAll' => 'Uncheck All~~',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user~~',
	'Core:BulkExport:CSVOptions' => 'CSV Options~~',
	'Core:BulkExport:CSVLocalization' => 'Localization~~',
	'Core:BulkExport:PDFOptions' => 'PDF Options~~',
	'Core:BulkExport:PDFPageFormat' => 'Page Format~~',
	'Core:BulkExport:PDFPageSize' => 'Page Size:~~',
	'Core:BulkExport:PageSize-A4' => 'A4~~',
	'Core:BulkExport:PageSize-A3' => 'A3~~',
	'Core:BulkExport:PageSize-Letter' => 'Letter~~',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:~~',
	'Core:BulkExport:PageOrientation-L' => 'Landscape~~',
	'Core:BulkExport:PageOrientation-P' => 'Portrait~~',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)~~',
	'Core:BulkExport:XMLOptions' => 'XML Options~~',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)~~',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options~~',
	'Core:BulkExport:OptionNoLocalize' => 'Export Code instead of Label~~',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects~~',
	'Core:BulkExport:OptionFormattedText' => 'Preserve text formatting~~',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export~~',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:~~',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.~~',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.~~',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...~~',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.~~',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.~~',
	'Core:BulkExport:XLSXOptions' => 'Excel Options~~',
	'Core:BulkExport:TextFormat' => 'Text fields containing some HTML markup~~',
	'Core:BulkExport:DateTimeFormat' => 'Date and Time format~~',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Default format (%1$s), e.g. %2$s~~',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Custom format: %1$s~~',
	'Core:BulkExport:PDF:PageNumber' => 'Page %1$s~~',
	'Core:DateTime:Placeholder_d' => 'DD~~', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D~~', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM~~', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M~~', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY~~', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY~~', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh~~', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h~~', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh~~', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h~~', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm~~', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM~~', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm~~', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss~~', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Wrong format~~',
	'Core:Validator:Mandatory' => 'Please, fill this field~~',
	'Core:Validator:MustBeInteger' => 'Must be an integer~~',
	'Core:Validator:MustSelectOne' => 'Please, select one~~',
));

//
// Class: TagSetFieldData
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TagSetFieldData' => '%2$s for class %1$s~~',
	'Class:TagSetFieldData+' => '~~',

	'Class:TagSetFieldData/Attribute:code' => 'Code~~',
	'Class:TagSetFieldData/Attribute:code+' => 'Internal code. Must contain at least 3 alphanumeric characters~~',
	'Class:TagSetFieldData/Attribute:label' => 'Label~~',
	'Class:TagSetFieldData/Attribute:label+' => 'Displayed label~~',
	'Class:TagSetFieldData/Attribute:description' => 'Description~~',
	'Class:TagSetFieldData/Attribute:description+' => 'Description~~',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Used tags cannot be deleted~~',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tags codes or labels must be unique~~',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Tags code must contain between 3 and %1$d alphanumeric characters~~',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'The chosen tag code is a reserved word~~',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Tags label must not contain \'%1$s\' nor be empty~~',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags Code cannot be changed when used~~',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" cannot be changed~~',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Attribute Code" cannot be changed~~',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Tag usage (%1$d)~~',
	'Core:TagSetFieldData:NoEntryFound' => 'No entry found for this tag~~',
));

//
// Class: DBProperty
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DBProperty' => 'DB property~~',
	'Class:DBProperty+' => '~~',
	'Class:DBProperty/Attribute:name' => 'Name~~',
	'Class:DBProperty/Attribute:name+' => '~~',
	'Class:DBProperty/Attribute:description' => 'Description~~',
	'Class:DBProperty/Attribute:description+' => '~~',
	'Class:DBProperty/Attribute:value' => 'Value~~',
	'Class:DBProperty/Attribute:value+' => '~~',
	'Class:DBProperty/Attribute:change_date' => 'Change date~~',
	'Class:DBProperty/Attribute:change_date+' => '~~',
	'Class:DBProperty/Attribute:change_comment' => 'Change comment~~',
	'Class:DBProperty/Attribute:change_comment+' => '~~',
));

//
// Class: BackgroundTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:BackgroundTask' => 'Background task~~',
	'Class:BackgroundTask+' => '~~',
	'Class:BackgroundTask/Attribute:class_name' => 'Class name~~',
	'Class:BackgroundTask/Attribute:class_name+' => '~~',
	'Class:BackgroundTask/Attribute:first_run_date' => 'First run date~~',
	'Class:BackgroundTask/Attribute:first_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Latest run date~~',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Next run date~~',
	'Class:BackgroundTask/Attribute:next_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. count~~',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Latest run duration~~',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. run duration~~',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. run duration~~',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Average run duration~~',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:running' => 'Running~~',
	'Class:BackgroundTask/Attribute:running+' => '~~',
	'Class:BackgroundTask/Attribute:status' => 'Status~~',
	'Class:BackgroundTask/Attribute:status+' => '~~',
));

//
// Class: AsyncTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AsyncTask' => 'Async. task~~',
	'Class:AsyncTask+' => '~~',
	'Class:AsyncTask/Attribute:created' => 'Created~~',
	'Class:AsyncTask/Attribute:created+' => '~~',
	'Class:AsyncTask/Attribute:started' => 'Started~~',
	'Class:AsyncTask/Attribute:started+' => '~~',
	'Class:AsyncTask/Attribute:planned' => 'Planned~~',
	'Class:AsyncTask/Attribute:planned+' => '~~',
	'Class:AsyncTask/Attribute:event_id' => 'Event~~',
	'Class:AsyncTask/Attribute:event_id+' => '~~',
	'Class:AsyncTask/Attribute:finalclass' => 'Final class~~',
	'Class:AsyncTask/Attribute:finalclass+' => '~~',
));

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
 * @author Erik Bøg <erik@boegmoeller.dk>
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Core:DeletedObjectLabel' => '%1s (slettet)',
	'Core:DeletedObjectTip' => 'Objektet er slettet på %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objektet ikke fundet (klasse: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Objektet kunne ikke findes. Det kan være slettet, uden at loggen er blevt tømt.',

	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',

	'Core:AttributeLinkedSet' => 'Array af objekter',
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

	'Core:AttributeLinkedSetIndirect' => 'Array af objekter (N-N)',
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

	'Core:AttributeApplicationLanguage' => 'Bruger sprog',
	'Core:AttributeApplicationLanguage+' => '',

	'Core:AttributeFinalClass' => 'Klasse (auto)',
	'Core:AttributeFinalClass+' => '',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => '',

	'Core:AttributeEncryptedString' => 'Krypteret streng',
	'Core:AttributeEncryptedString+' => '',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown~~',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **~~',

	'Core:AttributeText' => 'Tekst',
	'Core:AttributeText+' => '',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => '',

	'Core:AttributeEmailAddress' => 'Email adresse',
	'Core:AttributeEmailAddress+' => '',

	'Core:AttributeIPAddress' => 'IP adresse',
	'Core:AttributeIPAddress+' => '',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => '',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => '',

	'Core:AttributeTemplateString' => 'Template streng',
	'Core:AttributeTemplateString+' => '',

	'Core:AttributeTemplateText' => 'Template tekst',
	'Core:AttributeTemplateText+' => '',

	'Core:AttributeTemplateHTML' => 'Template HTML',
	'Core:AttributeTemplateHTML+' => '',

	'Core:AttributeDateTime' => 'Dato/tid',
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

	'Core:AttributeDate' => 'Dato',
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
</p>',

	'Core:AttributeDeadline' => 'Deadline',
	'Core:AttributeDeadline+' => '',

	'Core:AttributeExternalKey' => 'Ekstern nøgle',
	'Core:AttributeExternalKey+' => '',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Nøgle',
	'Core:AttributeHierarchicalKey+' => '',

	'Core:AttributeExternalField' => 'Eksternt felt',
	'Core:AttributeExternalField+' => '',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '',

	'Core:AttributeOneWayPassword' => 'En vejs password',
	'Core:AttributeOneWayPassword+' => '',

	'Core:AttributeTable' => 'Tabel',
	'Core:AttributeTable+' => '',

	'Core:AttributePropertySet' => 'Egenskaber',
	'Core:AttributePropertySet+' => '',

	'Core:AttributeFriendlyName' => 'Friendly navn',
	'Core:AttributeFriendlyName+' => '',

	'Core:FriendlyName-Label' => 'Friendly navn',
	'Core:FriendlyName-Description' => 'Friendly navn',

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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChange' => 'Change',
	'Class:CMDBChange+' => '',
	'Class:CMDBChange/Attribute:date' => 'Dato',
	'Class:CMDBChange/Attribute:date+' => '',
	'Class:CMDBChange/Attribute:userinfo' => 'Forskellig info',
	'Class:CMDBChange/Attribute:userinfo+' => '',
));

//
// Class: CMDBChangeOp
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOp' => 'Change Operation',
	'Class:CMDBChangeOp+' => '',
	'Class:CMDBChangeOp/Attribute:change' => 'Change',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'dato',
	'Class:CMDBChangeOp/Attribute:date+' => '',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'bruger',
	'Class:CMDBChangeOp/Attribute:userinfo+' => '',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Objekt klasse',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Objekt id',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Type',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpCreate' => 'Object oprettelse',
	'Class:CMDBChangeOpCreate+' => '',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpDelete' => 'Object sletning',
	'Class:CMDBChangeOpDelete+' => '',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpSetAttribute' => 'Object ændring',
	'Class:CMDBChangeOpSetAttribute+' => '',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribut',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Property ændring',
	'Class:CMDBChangeOpSetAttributeScalar+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Tidligere værdi',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Ny værdi',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Change:ObjectCreated' => 'Objekt oprettet',
	'Change:ObjectDeleted' => 'Objekt slettet',
	'Change:ObjectModified' => 'Objekt ændret',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s sat til %2$s (tidligere værdi: %3$s)',
	'Change:AttName_SetTo' => '%1$s sat til %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s tilføjet til %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s ændret, tidligere værdi: %2$s',
	'Change:AttName_Changed' => '%1$s ændret',
	'Change:AttName_EntryAdded' => '%1$s ændret, ny entry tilføjet: %2$s',
	'Change:LinkSet:Added' => 'tilføjet %1$s',
	'Change:LinkSet:Removed' => 'fjernet %1$s',
	'Change:LinkSet:Modified' => 'ændret %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Data ændring',
	'Class:CMDBChangeOpSetAttributeBlob+' => '',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Tidligere data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Tekst ændring',
	'Class:CMDBChangeOpSetAttributeText+' => '',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Tidligere data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '',
));

//
// Class: Event
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Event' => 'Log Hændelse',
	'Class:Event+' => '',
	'Class:Event/Attribute:message' => 'Besked',
	'Class:Event/Attribute:message+' => '',
	'Class:Event/Attribute:date' => 'Dato',
	'Class:Event/Attribute:date+' => '',
	'Class:Event/Attribute:userinfo' => 'Bruger info',
	'Class:Event/Attribute:userinfo+' => '',
	'Class:Event/Attribute:finalclass' => 'Type',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:EventNotification' => 'Notifikation hændelse',
	'Class:EventNotification+' => '',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Bruger',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Object id',
	'Class:EventNotification/Attribute:object_id+' => '',
));

//
// Class: EventNotificationEmail
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:EventNotificationEmail' => 'Email emission hændelse',
	'Class:EventNotificationEmail+' => '',
	'Class:EventNotificationEmail/Attribute:to' => 'Til',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'Fra',
	'Class:EventNotificationEmail/Attribute:from+' => '',
	'Class:EventNotificationEmail/Attribute:subject' => 'Emne',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Indhold',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Vedhæftning(er)',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:EventIssue' => 'Hændelses emne',
	'Class:EventIssue+' => '',
	'Class:EventIssue/Attribute:issue' => 'Emne',
	'Class:EventIssue/Attribute:issue+' => '',
	'Class:EventIssue/Attribute:impact' => 'Påvirkning',
	'Class:EventIssue/Attribute:impact+' => '',
	'Class:EventIssue/Attribute:page' => 'Side',
	'Class:EventIssue/Attribute:page+' => '',
	'Class:EventIssue/Attribute:arguments_post' => 'Postede argumenter',
	'Class:EventIssue/Attribute:arguments_post+' => '',
	'Class:EventIssue/Attribute:arguments_get' => 'URL argumenter',
	'Class:EventIssue/Attribute:arguments_get+' => '',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Data',
	'Class:EventIssue/Attribute:data+' => '',
));

//
// Class: EventWebService
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:EventWebService' => 'Web service hændelse',
	'Class:EventWebService+' => '',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => '',
	'Class:EventWebService/Attribute:result' => 'Resultat',
	'Class:EventWebService/Attribute:result+' => '',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => '',
	'Class:EventWebService/Attribute:log_warning' => 'Advarsels log',
	'Class:EventWebService/Attribute:log_warning+' => '',
	'Class:EventWebService/Attribute:log_error' => 'Fejl log',
	'Class:EventWebService/Attribute:log_error+' => '',
	'Class:EventWebService/Attribute:data' => 'Data',
	'Class:EventWebService/Attribute:data+' => '',
));

Dict::Add('DA DA', 'Danish', 'Dansk', array(
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:EventLoginUsage' => 'Login Usage',
	'Class:EventLoginUsage+' => '',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Bruger navn',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Bruger Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Action' => 'Brugerdefineret handling',
	'Class:Action+' => '',
	'Class:Action/Attribute:name' => 'Navn',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Beskrivelse',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => '',
	'Class:Action/Attribute:status/Value:test' => 'Bliver testet',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'I produktion',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inaktiv',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Relaterede Triggere',
	'Class:Action/Attribute:trigger_list+' => '',
	'Class:Action/Attribute:finalclass' => 'Type',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ActionNotification' => 'Notifikation',
	'Class:ActionNotification+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ActionEmail' => 'Email besked',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Test modtager',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'Fra',
	'Class:ActionEmail/Attribute:from+' => 'Afsender af emailen',
	'Class:ActionEmail/Attribute:reply_to' => 'Svar til',
	'Class:ActionEmail/Attribute:reply_to+' => 'Svar sendes til',
	'Class:ActionEmail/Attribute:to' => 'Til',
	'Class:ActionEmail/Attribute:to+' => 'Modtager af emailen',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Kopi sendes til',
	'Class:ActionEmail/Attribute:bcc' => 'Bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind kopi sendes til',
	'Class:ActionEmail/Attribute:subject' => 'Emne',
	'Class:ActionEmail/Attribute:subject+' => 'Tekst i emne feltet',
	'Class:ActionEmail/Attribute:body' => 'Indhold',
	'Class:ActionEmail/Attribute:body+' => 'Tekst delen af emailen',
	'Class:ActionEmail/Attribute:importance' => 'Vigtighed',
	'Class:ActionEmail/Attribute:importance+' => 'Hvilken prioritet skal emailen sendes med',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Lav',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Høj',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
));

//
// Class: Trigger
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Trigger' => 'Triggere',
	'Class:Trigger+' => '',
	'Class:Trigger/Attribute:description' => 'Beskrivelse',
	'Class:Trigger/Attribute:description+' => '',
	'Class:Trigger/Attribute:action_list' => 'Triggerede handlinger',
	'Class:Trigger/Attribute:action_list+' => '',
	'Class:Trigger/Attribute:finalclass' => 'Type',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnObject' => 'Trigger (klasse afhængig)',
	'Class:TriggerOnObject+' => '',
	'Class:TriggerOnObject/Attribute:target_class' => 'Target klasse',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'Class:TriggerOnObject/Attribute:filter+' => '~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \\"%1$s\\"~~',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (Når opdateret fra portalen)',
	'Class:TriggerOnPortalUpdate+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnStateChange' => 'Trigger (i tilstand ændring)',
	'Class:TriggerOnStateChange+' => '',
	'Class:TriggerOnStateChange/Attribute:state' => 'Tilstand',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnStateEnter' => 'Trigger (ved indtræden i en tilstand)',
	'Class:TriggerOnStateEnter+' => '',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnStateLeave' => 'Trigger (når en tilstand forlades)',
	'Class:TriggerOnStateLeave+' => '',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (ved oprettelse af objekt)',
	'Class:TriggerOnObjectCreate+' => '',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (on object deletion)~~',
	'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class~~',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (on object update)~~',
	'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (grænseværdi)',
	'Class:TriggerOnThresholdReached+' => '',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stopur',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Grænse',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkTriggerAction' => 'Handling/Trigger',
	'Class:lnkTriggerAction+' => '',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Handling',
	'Class:lnkTriggerAction/Attribute:action_id+' => '',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Handling',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Rækkefølge',
	'Class:lnkTriggerAction/Attribute:order+' => '',
));

//
// Synchro Data Source
//
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SynchroDataSource/Attribute:name' => 'Navn',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Beskrivelse',
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target klasse',
	'Class:SynchroDataSource/Attribute:user_id' => 'Bruger',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Kontakt som skal adviseres',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => '',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icon\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_icon+' => '',
	'Class:SynchroDataSource/Attribute:url_application' => 'Applikation\'s hyperlink',
	'Class:SynchroDataSource/Attribute:url_application+' => '',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Afstemnings politik',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => '',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Handling på nul',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Handling på en',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Handling på mange',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => '',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Tilladte brugere',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => '',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Ingen',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Kun Administratorer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Alle tilladte brugere',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Opdater regler',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => '',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Fastholdelses varighed',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => '',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table',
	'Class:SynchroDataSource/Attribute:database_table_name+' => '',
	'SynchroDataSource:Description' => 'Beskrivelse',
	'SynchroDataSource:Reconciliation' => 'Søg &amp; afstemning',
	'SynchroDataSource:Deletion' => 'Slette regler',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Information',
	'SynchroDataSource:Definition' => 'Definition',
	'Core:SynchroAttributes' => 'Attributter',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Fejl',
	'Core:Synchro:CreatedLabel' => 'Oprettet',
	'Core:Synchro:ModifiedLabel' => 'Ændret',
	'Core:Synchro:UnchangedLabel' => 'Uændret',
	'Core:Synchro:ReconciledErrorsLabel' => 'Fejl',
	'Core:Synchro:ReconciledLabel' => 'Afstemt',
	'Core:Synchro:ReconciledNewLabel' => 'Oprettet',
	'Core:SynchroReconcile:Yes' => 'Ja',
	'Core:SynchroReconcile:No' => 'Nej',
	'Core:SynchroUpdate:Yes' => 'Ja',
	'Core:SynchroUpdate:No' => 'Nej',
	'Core:Synchro:LastestStatus' => 'Sidste Status',
	'Core:Synchro:History' => 'Synchronization Historik',
	'Core:Synchro:NeverRun' => 'Denne synkronisering har aldrig været kørt. Endnu ingen log.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Sidste synkronisering sluttede den %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Synkroniseringen der startede den %1$s kører stadig...',
	'Menu:DataSources' => 'Synkroniserings Data Kilder', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignoreret (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Forsvundet (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Eksisterer (%1$s)',
	'Core:Synchro:label_repl_new' => 'Ny (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Slettet (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Forældet (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Fejl (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Ingen handling (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Uændret (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Opdateret (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Fejl (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Uændret (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Opdateret (%1$s)',
	'Core:Synchro:label_obj_created' => 'Oprettet (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Fejl (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica behandlet: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Mindst 1 afstemnings nøgle skal anføres, eller afstemnings politikken skal sættes til at bruge primær nøgle.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'En tilbageholdelses periode efter sletning skal anføres, da objekter slettes efter mærkning som Forældet',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Forældede objekter skal ajourføres, men der er ingen opdateringer specificeret.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Tabellen %1$s eksisterer allerede i databasen. Brug venligst et andet navn for synkroniserings tabellen.',
	'Core:SynchroReplica:PublicData' => 'Offentlige Data',
	'Core:SynchroReplica:PrivateDetails' => 'Private Detaljer',
	'Core:SynchroReplica:BackToDataSource' => 'Gå Tilbage til Synkroniserings Data Kilde: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Liste over Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primær Nøgle)',
	'Core:SynchroAtt:attcode' => 'Attribut',
	'Core:SynchroAtt:attcode+' => '',
	'Core:SynchroAtt:reconciliation' => 'Afstem ?',
	'Core:SynchroAtt:reconciliation+' => '',
	'Core:SynchroAtt:update' => 'Opdater ?',
	'Core:SynchroAtt:update+' => '',
	'Core:SynchroAtt:update_policy' => 'Opdater Politik',
	'Core:SynchroAtt:update_policy+' => '',
	'Core:SynchroAtt:reconciliation_attcode' => 'Afstemnings Nøgle',
	'Core:SynchroAtt:reconciliation_attcode+' => '',
	'Core:SyncDataExchangeComment' => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources' => 'Liste over data kilder:',
	'Core:Synchro:LastSynchro' => 'Sidste synkronisering:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Dette objekt er synkroniseret med en ekstern data kilde',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Objektet blev <b>oprettet</b> af den eksterne data kilde %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Objektet <b>kan slettes</b> af den eksterne data kilde %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Du <b>kan ikke slette dette objekt</b> fordi det er ejet af den eksterne data kilde %1$s',
	'TitleSynchroExecution' => 'Udførelse af synkroniseringen',
	'Class:SynchroDataSource:DataTable' => 'Database tabel: %1$s',
	'Core:SyncDataSourceObsolete' => 'Data kilden er markeret som forældet. Handlingen afbrudt.',
	'Core:SyncDataSourceAccessRestriction' => 'Kun adminstratorer eller brugere specificeret i data kilden kan udføre denne handling. Handlingen afbrudt.',
	'Core:SyncTooManyMissingReplicas' => 'Alle records har været urørt i nogen tid (alle objekterne kan slettes). Venligst kontroller at processen som skriver i synkroniserings tabellen stadig kører. Handlingen afbrudtc.',
	'Core:SyncSplitModeCLIOnly' => 'Synkroniseringen kan kun udføres i etapper hvis den udføres i CLI mode',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s fejl, %3$s advarsler.',
	'Core:SynchroReplica:TargetObject' => 'Synkroniserede Objekter: %1$s',
	'Class:AsyncSendEmail' => 'Email (asynkron)',
	'Class:AsyncSendEmail/Attribute:to' => 'Til',
	'Class:AsyncSendEmail/Attribute:subject' => 'Emne',
	'Class:AsyncSendEmail/Attribute:body' => 'Indhold',
	'Class:AsyncSendEmail/Attribute:header' => 'Header',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Krypteret Password',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Tidligere værdi',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Krypteret Felt',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Tidligere værdi',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Sags Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Sidste Entry',
	'Class:SynchroDataSource' => 'Synchro Data Kilde',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementering',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Produktion',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope restriction',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Brug attributterne',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Brug primær nøgle feltet',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Opret',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Fejl',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Fejl',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Opdater',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Opret',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Fejl',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Tag den første (vilkårlig?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Slet Politik',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Slet',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Opdater',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Opdater derefter Slet',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attribut Liste',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Kun Administratorer',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Enhver har tilladelse til at slette sådanne elementer',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Ingen',
	'Class:SynchroAttribute' => 'Synchro Attribute~~',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Data Kilde',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribut Kode',
	'Class:SynchroAttribute/Attribute:update' => 'Opdater',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Afstem',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Opdater Polik',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Låst',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Låst op',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialiser hvis tom',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Klasse',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)~~',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Afstem Attribut',
	'Class:SynchroAttLinkSet' => 'Synchro Attribut (Linksæt)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Række separator',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attribut separator',
	'Class:SynchroLog' => 'Synchr Log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Data Kilde',
	'Class:SynchroLog/Attribute:start_date' => 'Start Dato',
	'Class:SynchroLog/Attribute:end_date' => 'Slut Dato',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Fuldført',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Fejl',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Stadig Kørende',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nr replica opdaget',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nr replica total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nr objekter slettet',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nr af fejl under sletning',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nr objekter forældede',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nr af fejl under markering af forældet',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nr objekter oprettet',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nr af fejl under oprettelse',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nr objekter opdateret',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nr af fejl under opdatering',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nr af fejl under afstemning',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nr af replica forsvundet',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nr objekter opdateret',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nr objekter uændrede',
	'Class:SynchroLog/Attribute:last_error' => 'Sidste fejl',
	'Class:SynchroLog/Attribute:traces' => 'Spor',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Kilde',
	'Class:SynchroReplica/Attribute:dest_id' => 'Destinations objekt (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Destinations type',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Sidst opdaget',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modificeret',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Ny',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synkroniset',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objekt Oprettet ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Sidste fejl',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Advarsler',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Oprettelses Dato',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Sidste Ændrings Dato',
	'Class:appUserPreferences' => 'Bruger Indstillinger',
	'Class:appUserPreferences/Attribute:userid' => 'Bruger',
	'Class:appUserPreferences/Attribute:preferences' => 'Inst.',
	'Core:ExecProcess:Code1' => 'Forkert kommando eller kommandoen afsluttede med fejl (f.eks. forkert script navn)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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

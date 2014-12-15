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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */



Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Core:DeletedObjectLabel' => '%1s (verwijderd)',
	'Core:DeletedObjectTip' => 'Het object is verwijderd op %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Object niet gevonden (klasse: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Object kon niet worden gevonden. Het zou eerder verwijderd kunnen zijn en de log zou kunnen zijn opgeschoond.',

	'Core:AttributeLinkedSet' => 'Reeks van objecten',
	'Core:AttributeLinkedSet+' => 'Elke soort objecten van dezelfde klasse of subklasse',

	'Core:AttributeLinkedSetIndirect' => 'Reeks van objecten (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Elke soort objecten [subklasse] van dezelfde klasse',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numerieke waarde (kan negatief zijn)',

	'Core:AttributeDecimal' => 'Decimaal',
	'Core:AttributeDecimal+' => 'Decimale waarde (kan negatief zijn)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Alphanumerieke string',

	'Core:AttributeClass' => 'Klasse',
	'Core:AttributeClass+' => 'Klasse',

	'Core:AttributeApplicationLanguage' => 'Taal van de gebruiker',
	'Core:AttributeApplicationLanguage+' => 'Taal en land (EN US)',

	'Core:AttributeFinalClass' => 'Klasse (auto)',
	'Core:AttributeFinalClass+' => 'Echte klasse van het object (automatisch aangemaakt bij de kern)',

	'Core:AttributePassword' => 'Wachtwoord',
	'Core:AttributePassword+' => 'Wachtwoord van een extern apparaat',

 	'Core:AttributeEncryptedString' => 'Gecodeerde string',
	'Core:AttributeEncryptedString+' => 'String gecodeerd met een locale key',

	'Core:AttributeText' => 'Text',
	'Core:AttributeText+' => 'Multiline character string',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => 'E-mailadres',
	'Core:AttributeEmailAddress+' => 'E-mailadres',

	'Core:AttributeIPAddress' => 'IP adres',
	'Core:AttributeIPAddress+' => 'IP adres',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lijst van voorgedefineerde alphanumerieke strings',

	'Core:AttributeTemplateString' => 'Template string',
	'Core:AttributeTemplateString+' => 'String die de procurators bevat',

	'Core:AttributeTemplateText' => 'Template text',
	'Core:AttributeTemplateText+' => 'Text die de procurators bevat',

	'Core:AttributeTemplateHTML' => 'Template HTML',
	'Core:AttributeTemplateHTML+' => 'HTML die de procurators bevat',

	'Core:AttributeDateTime' => 'Datum/tijd',
	'Core:AttributeDateTime+' => 'Datum en tijd (jaar-maand-dag hh:mm:ss)',
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
	'Core:AttributeDeadline+' => 'Datum, relatief weergegeven ten opzichte van het huidige tijdstip',

	'Core:AttributeExternalKey' => 'Externe key',
	'Core:AttributeExternalKey+' => 'Externe (of buitenlandse) key',

	'Core:AttributeHierarchicalKey' => 'Hierarchische Key',
	'Core:AttributeHierarchicalKey+' => 'Externe (of buitenlandse) key tot de parent',

	'Core:AttributeExternalField' => 'Extern veld',
	'Core:AttributeExternalField+' => 'Veld mapped tot een externe key',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '
Absolute of relatieve URL als een text string',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Alle binaire inhoud (document)',

	'Core:AttributeOneWayPassword' => 'One way wachtwoord',
	'Core:AttributeOneWayPassword+' => 'One way gecodeerd (hashed) wachtwoord',

	'Core:AttributeTable' => 'Tabel',
	'Core:AttributeTable+' => 'Geïndexeerde reeks met twee dimensies',

	'Core:AttributePropertySet' => 'Eigenschappen',
	'Core:AttributePropertySet+' => 'Lijst van ongeschreven eigenschappen (naam en waarde)',

	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => 'Automatisch aangemaakt attribuut; de friendly name is na verscheidene attributen verwerkt',

	'Core:FriendlyName-Label' => 'Referentie',
	'Core:FriendlyName-Description' => 'Referentie',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChange' => 'Aanpassing',
	'Class:CMDBChange+' => 'Volgen van aanpassingen',
	'Class:CMDBChange/Attribute:date' => 'datum',
	'Class:CMDBChange/Attribute:date+' => 'De datum en tijd waarop de aanpassingen zijn waargenomen ',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'gedefineerde info van de gebruiker',
));

//
// Class: CMDBChangeOp
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOp' => 'Pas de operatie aan',
	'Class:CMDBChangeOp+' => 'Pas het volgen van de operatie aan',
	'Class:CMDBChangeOp/Attribute:change' => 'Pas aan',
	'Class:CMDBChangeOp/Attribute:change+' => 'Pas aan',
	'Class:CMDBChangeOp/Attribute:date' => 'datum',
	'Class:CMDBChangeOp/Attribute:date+' => 'datum en tijd van de aanpassing',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'gebruiker',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'wie heeft deze aanpassing doorgevoerd',
	'Class:CMDBChangeOp/Attribute:objclass' => 'objectklasse',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'objectklasse',
	'Class:CMDBChangeOp/Attribute:objkey' => 'object id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'object id',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'type',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpCreate' => 'objectcreatie',
	'Class:CMDBChangeOpCreate+' => 'Objectcreatie volgen',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpDelete' => 'verwijderen object',
	'Class:CMDBChangeOpDelete+' => 'volgen van het verwijderen van objecten',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttribute' => 'aanpassing van het object',
	'Class:CMDBChangeOpSetAttribute+' => 'volgen van de aanpassing van de objecteigenschappen',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribuut',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code van de aangepaste eigenschap',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Aanpassing van de eigenschap',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Volgen van de object scalar eigenschappen aanpassing',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Vorige waarde van het attribuut',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'New value',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Nieuwe waarde van het attribuut',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Change:ObjectCreated' => 'Object aangemaakt',
	'Change:ObjectDeleted' => 'Object verwijderd',
	'Change:ObjectModified' => 'Object aangepast',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s veranderd naar %2$s (vorige waarde: %3$s)',
	'Change:AttName_SetTo' => '%1$s veranderd naar %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s toegevoegd aan %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s aangepast, vorige waarde: %2$s',
	'Change:AttName_Changed' => '%1$s aangepast',
	'Change:AttName_EntryAdded' => '%1$s aangepast, nieuwe entry toegevoegd.',
	'Change:LinkSet:Added' => 'toegevoegd %1$s',
	'Change:LinkSet:Removed' => 'verwijderd %1$s',
	'Change:LinkSet:Modified' => 'aangepast %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'dataverandering',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'volgen dataverandering',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Vorige data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'eerdere inhoud van het attribuut',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeText' => 'tekstverandering',
	'Class:CMDBChangeOpSetAttributeText+' => 'volgen tekstverandering',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Vorige data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'eerdere inhoud van het attribuut',
));

//
// Class: Event
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Event' => 'Log Event',
	'Class:Event+' => 'Een intern event van de applicatie',
	'Class:Event/Attribute:message' => 'Bericht',
	'Class:Event/Attribute:message+' => 'Korte beschrijving van het event',
	'Class:Event/Attribute:date' => 'Datum',
	'Class:Event/Attribute:date+' => 'Datum en tijdstip waarop de veranderingen zijn vastgelegd',
	'Class:Event/Attribute:userinfo' => 'Gebruikersinfo',
	'Class:Event/Attribute:userinfo+' => 'Identificatie van de gebruiker die de actie uitvoerde die het event triggerde',
	'Class:Event/Attribute:finalclass' => 'Type',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventNotification' => 'Notificatie van het event',
	'Class:EventNotification+' => 'Spoor van de notificatie die is verstuurd',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => 'gebruikersaccount',
	'Class:EventNotification/Attribute:action_id' => 'gebruiker',
	'Class:EventNotification/Attribute:action_id+' => 'gebruikersaccount',
	'Class:EventNotification/Attribute:object_id' => 'Object id',
	'Class:EventNotification/Attribute:object_id+' => 'object id (klasse gedefineerd door de trigger?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventNotificationEmail' => 'E-mail emission event',
	'Class:EventNotificationEmail+' => 'Spoor van de e-mail die is verstuurd',
	'Class:EventNotificationEmail/Attribute:to' => 'AAN',
	'Class:EventNotificationEmail/Attribute:to+' => 'AAN',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'Van',
	'Class:EventNotificationEmail/Attribute:from+' => 'Afzender van het bericht',
	'Class:EventNotificationEmail/Attribute:subject' => 'Onderwerp',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Onderwerp',
	'Class:EventNotificationEmail/Attribute:body' => 'Body',
	'Class:EventNotificationEmail/Attribute:body+' => 'Body',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Bijlagen',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventIssue' => 'Probleem van het event',
	'Class:EventIssue+' => 'Spoor van een probleem (waarschuwing, error, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Probleem',
	'Class:EventIssue/Attribute:issue+' => 'Wat is er gebeurd',
	'Class:EventIssue/Attribute:impact' => 'Impact',
	'Class:EventIssue/Attribute:impact+' => 'Wat zijn de consequenties',
	'Class:EventIssue/Attribute:page' => 'Pagina',
	'Class:EventIssue/Attribute:page+' => 'HTTP entry point',
	'Class:EventIssue/Attribute:arguments_post' => 'Posted arguments',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST arguments',
	'Class:EventIssue/Attribute:arguments_get' => 'URL arguments',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET arguments',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Data',
	'Class:EventIssue/Attribute:data+' => 'Meer informatie',
));

//
// Class: EventWebService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventWebService' => 'Web service event',
	'Class:EventWebService+' => 'Spor van een web service call',
	'Class:EventWebService/Attribute:verb' => 'Werkwoord',
	'Class:EventWebService/Attribute:verb+' => 'Naam van de operatie',
	'Class:EventWebService/Attribute:result' => 'Resultaat',
	'Class:EventWebService/Attribute:result+' => 'Totaal succes/falen',
	'Class:EventWebService/Attribute:log_info' => 'Infolog',
	'Class:EventWebService/Attribute:log_info+' => 'Resultaat infolog',
	'Class:EventWebService/Attribute:log_warning' => 'Waarschuwingslog',
	'Class:EventWebService/Attribute:log_warning+' => 'Resultaat waarschuwingslog',
	'Class:EventWebService/Attribute:log_error' => 'Errorlog',
	'Class:EventWebService/Attribute:log_error+' => 'Resultaat errorlog',
	'Class:EventWebService/Attribute:data' => 'Data',
	'Class:EventWebService/Attribute:data+' => 'Result data',
));

//
// Class: EventLoginUsage
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventLoginUsage' => 'Login Gebruik',
	'Class:EventLoginUsage+' => 'Verbinding met de applicatie',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Gebruikersnaam',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Gebruikersnaam',
	'Class:EventLoginUsage/Attribute:contact_email' => 'E-mail van de gebruiker',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'E-mailadres van de gebruiker',
));

//
// Class: Action
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Action' => 'Custom Actie',
	'Class:Action+' => 'Gebruiker defineerd actie',
	'Class:Action/Attribute:name' => 'Naam',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Beschrijving',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'In productie of ?',
	'Class:Action/Attribute:status/Value:test' => 'Wordt getest',
	'Class:Action/Attribute:status/Value:test+' => 'Wordt getest',
	'Class:Action/Attribute:status/Value:enabled' => 'In productie',
	'Class:Action/Attribute:status/Value:enabled+' => 'In productie',
	'Class:Action/Attribute:status/Value:disabled' => 'Inactief',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inactief',
	'Class:Action/Attribute:trigger_list' => 'Gerelateerde Triggers',
	'Class:Action/Attribute:trigger_list+' => 'Triggers gelinkt aan deze actie',
	'Class:Action/Attribute:finalclass' => 'Type',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ActionNotification' => 'Notificatie',
	'Class:ActionNotification+' => 'Notificatie (abstract)',
));

//
// Class: ActionEmail
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ActionEmail' => 'E-mail notificatie',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Testontvanger',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Bestemming, in het geval dat de status op "Test" is gezet',
	'Class:ActionEmail/Attribute:from' => 'Van',
	'Class:ActionEmail/Attribute:from+' => 'Zal naar het e-mail kopje gestuurd worden',
	'Class:ActionEmail/Attribute:reply_to' => 'Antwoord',
	'Class:ActionEmail/Attribute:reply_to+' => 'Zal naar het e-mail kopje gestuurd worden',
	'Class:ActionEmail/Attribute:to' => 'Naar',
	'Class:ActionEmail/Attribute:to+' => 'Bestemming van de e-mail',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => 'onderwerp',
	'Class:ActionEmail/Attribute:subject+' => 'Titel van de e-mail',
	'Class:ActionEmail/Attribute:body' => 'body',
	'Class:ActionEmail/Attribute:body+' => 'Inhoud van de e-mail',
	'Class:ActionEmail/Attribute:importance' => 'Prioriteit',
	'Class:ActionEmail/Attribute:importance+' => 'Prioriteitsvlag',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'laag',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'laag',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normaal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normaal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'hoog',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'hoog',
));

//
// Class: Trigger
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => 'Beschrijving',
	'Class:Trigger/Attribute:description+' => 'Beschrijving in één regel',
	'Class:Trigger/Attribute:action_list' => 'Getriggerde acties',
	'Class:Trigger/Attribute:action_list+' => 'Acties uitgevoerd nadat de trigger is geactiveerd',
	'Class:Trigger/Attribute:finalclass' => 'Type',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnObject' => 'Trigger (afhankelijk van klasse)',
	'Class:TriggerOnObject+' => 'Trigger op een bepaald klasse van objecten',
	'Class:TriggerOnObject/Attribute:target_class' => 'Targetklasse',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Verkeerde filter query: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'De filter query moet verwijzen naar objecten van klasse "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (wanneer geüpdatet vanuit het portaal)',
	'Class:TriggerOnPortalUpdate+' => 'Trigger op de update van de eindgebruiker van het portaal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateChange' => 'Trigger (on state change)',
	'Class:TriggerOnStateChange+' => 'Trigger on object state change',
	'Class:TriggerOnStateChange/Attribute:state' => 'State',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateEnter' => 'Trigger (bij het binnenkomen in een state)',
	'Class:TriggerOnStateEnter+' => 'Trigger bij de verandering van de state van het object - binnenkomend',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateLeave' => 'Trigger (bij het verlaten van een state)',
	'Class:TriggerOnStateLeave+' => 'Trigger bij verandering van de state van het object - verlatend',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (bij de aanmaak van een object)',
	'Class:TriggerOnObjectCreate+' => 'Trigger bij de aanmaak van een object van [de child klasse van] de bepaalde klasse',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (op threshold)',
	'Class:TriggerOnThresholdReached+' => 'Trigger op Stop-Watch threshold bereikt',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Threshold',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkTriggerAction' => 'Actie/Trigger',
	'Class:lnkTriggerAction+' => 'Link tussen een trigger en een actie',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Actie',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'De actie die moet worden uitgevoerd',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Actie',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Volgorde',
	'Class:lnkTriggerAction/Attribute:order+' => 'De volgorde in het uitvoeren van de actie',
));

//
// Synchro Data Source
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SynchroDataSource/Attribute:name' => 'Naam',
	'Class:SynchroDataSource/Attribute:name+' => 'Naam',
	'Class:SynchroDataSource/Attribute:description' => 'Beschrijving',
	'Class:SynchroDataSource/Attribute:status' => 'Status', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target klasse',
	'Class:SynchroDataSource/Attribute:user_id' => 'Gebruiker',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact dat moet worden genotificeerd',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact dat moet worden genotificeerd in het geval van een error',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Hyperlink van de Icoon',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink een (kleine) afbeelding die de applicatie waarmee iTop is gesynchroniseerd',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hyperlink van de applicatie',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink naar het iTop object in de externe applicatie waarmee iTop is gesynchroniseerd (indien toepasbaar). Mogelijke procurators: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation policy', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Het volledige herladen van alle data moet tenminste zo vaak gebeuren als hier staat gespecificeerd',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Actie op nul',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Actie die wordt ondernomen wanneer de zoekopdracht geen object geeft',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Actie op één',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action die wordt ondernomen wanneer de zoekopdracht precies één object geeft',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Actie op meerdere',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Actie die wordt ondernomen wanneer de zoekopdracht meerdere objecten geeft',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Gebruikers toegestaan',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Wie is geautoriseerd om gesynchroniseerde objecten te verwijderen',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Gebruikers toegestaan',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Niemand',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Alleen administrators',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Alle geautoriseerde gebruikers',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Updateregels',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Retentietijd',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Hoe lang een overbodig object wordt bewaard voordat deze wordt verwijderd',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Datatabel',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Naam van de tabel waarin de gesynchroniseerde data wordt opgeslagen. Wanneer deze wordt leeggelaten zal een standaard naam worden opgegeven.',
	'SynchroDataSource:Description' => 'Beschrijving',
	'SynchroDataSource:Reconciliation' => 'Search &amp; reconciliation',
	'SynchroDataSource:Deletion' => 'Regels voor het verwijderen',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Informatie',
	'SynchroDataSource:Definition' => 'Definitie',
	'Core:SynchroAttributes' => 'Attributen',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Errors',	
	'Core:Synchro:CreatedLabel' => 'Aangemaakt',
	'Core:Synchro:ModifiedLabel' => 'Aangepast',
	'Core:Synchro:UnchangedLabel' => 'Niet veranderd',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errors',
	'Core:Synchro:ReconciledLabel' => 'Reconciled',
	'Core:Synchro:ReconciledNewLabel' => 'Aangemaakt',
	'Core:SynchroReconcile:Yes' => 'Ja',
	'Core:SynchroReconcile:No' => 'Nee',
	'Core:SynchroUpdate:Yes' => 'Ja',
	'Core:SynchroUpdate:No' => 'Nee',
	'Core:Synchro:LastestStatus' => 'Laatste Status',
	'Core:Synchro:History' => 'Synchronisatiegeschiedenis',
	'Core:Synchro:NeverRun' => 'Deze synchro is nog niet gedaan. Er is nog geen log.',
	'Core:Synchro:SynchroEndedOn_Date' => 'De laatste synchronisatie eindigde op %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'De synchronisatie is gestart op %1$s en is nog bezig...',
	'Menu:DataSources' => 'Synchronisatie Data Sources',
	'Menu:DataSources+' => 'Alle gesynchroniseerde Data Sources',
	'Core:Synchro:label_repl_ignored' => 'Genegeerd (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Verdwenen (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Bestaand (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nieuw (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Verwijderd (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Overbodig (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Geen actie (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Onveranderd (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Geüpdatet (%1$s)', 
	'Core:Synchro:label_obj_updated_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Onveranderd (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Geüpdatet (%1$s)',
	'Core:Synchro:label_obj_created' => 'Aangemaakt (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Errors (%1$s)',
	'Core:Synchro:History' => 'Synchronizatiegeschiedenis',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica verwerkt: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Tenminste één reconciliation key moet worden gespecificeerd, of de reconciliation policy moet zijn dat de primary key wordt gebruikt.',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Een retention period voor het verwijderen moet worden gespecificeerd, omdat alle objecten verwijderd worden nadat ze gemarkeerd zijn als overbodig',			
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Overbodige objecten moeten worden geüpdatet, maar er is geen update gespecificeerd.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'De tabel %1$s bestaat al in de database. Gebruik alstublieft een andere naam voor deze synchro datatabel.',
	'Core:SynchroReplica:PublicData' => 'Publieke Data',
	'Core:SynchroReplica:PrivateDetails' => 'Privé Details',
	'Core:SynchroReplica:BackToDataSource' => 'Ga terug naar de Synchro Data Source: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lijst van Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)',
	'Core:SynchroAtt:attcode' => 'Attribuut',
	'Core:SynchroAtt:attcode+' => 'Veld van het object',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?',
	'Core:SynchroAtt:reconciliation+' => 'Gebruikt voor het zoeken',
	'Core:SynchroAtt:update' => 'Update ?',
	'Core:SynchroAtt:update+' => 'Gebruikt om het object te updaten',
	'Core:SynchroAtt:update_policy' => 'Update Policy',
	'Core:SynchroAtt:update_policy+' => 'Gedrag van het geüpdatete veld',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribuutcode voor de Reconciliation van de externe key',
	'Core:SyncDataExchangeComment' => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources' => 'Lijst van data sources:',
	'Core:Synchro:LastSynchro' => 'Laatste synchronisatie:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Dit object is gesynchroniseerd met een externe data source',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Dit object is <b>aangemaakt</b> door een externe data source %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Dit object <b>kan worden verwijderd</b> door de externe data source %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'U <b>kunt dit object niet verwijderen</b> omdat het eigendom is van de externe data source %1$s',
	'TitleSynchroExecution' => 'Uitvoering van de synchronisatie',
	'Class:SynchroDataSource:DataTable' => 'Database tabel: %1$s',
	'Core:SyncDataSourceObsolete' => 'De data source is gemarkeerd als overbodig. Operatie afgebroken.',
	'Core:SyncDataSourceAccessRestriction' => 'Alleen administrators of de gebruiker gespecificeerd in de data source kan deze operatie uitvoeren. Operatie afgebroken.',
	'Core:SyncTooManyMissingReplicas' => 'Alle records zijn een tijd niet gebruikt (alle objecten kunnen worden verwijderd). Controleer alstublieft of het proces dat in de synchronisatie tabel schrijft nog steeds bezig is. Operatie afgebroken.',
	'Core:SyncSplitModeCLIOnly' => 'De synchronisatie kan alleen in delen uit worden gevoerd als deze wordt uitgevoerd in mode CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).',
	'Core:SynchroReplica:TargetObject' => 'Synchronized Object: %1$s',
	'Class:AsyncSendEmail' => 'E-mail (niet synchroon)',
	'Class:AsyncSendEmail/Attribute:to' => 'Aan',
	'Class:AsyncSendEmail/Attribute:subject' => 'Onderwerp',
	'Class:AsyncSendEmail/Attribute:body' => 'Body',
	'Class:AsyncSendEmail/Attribute:header' => 'Kopje',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Gecodeerd wachtwoord',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Gecodeerd veld',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Laaste Entry',
	'Class:SynchroDataSource' => 'Synchro Data Source',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Overbodig',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Productie',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope restrictie',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Gebruik de attributen',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Gebruik het veld van de primary_key',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Maak aan',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Maak aan',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Neem de eerste (random?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Verwijder Policy',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Verwijder',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Negeer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update then Delete',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lijst van attributen',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Alleen administrators',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Iedereen mag deze objecten verwijderen',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Niemand',
	'Class:SynchroAttribute' => 'Synchro Attribute',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribuutcode',
	'Class:SynchroAttribute/Attribute:update' => 'Update',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Gesloten',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Open',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Begin indien leeg',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Klasse',
	'Class:SynchroAttExtKey' => 'Synchro Attribuut (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation Attribuut',
	'Class:SynchroAttLinkSet' => 'Synchro Attribuut (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Rows separator',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributes separator',
	'Class:SynchroLog' => 'Synchr Log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroLog/Attribute:start_date' => 'Begindatum',
	'Class:SynchroLog/Attribute:end_date' => 'Einddatum',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Compleet',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Error',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Nog bezig',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Aantal replicas gezien',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Totaal aantal replicas',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Aantal objecten verwijderd',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Aantal errors tijdens het verwijderen',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Aantal  objecten overbodig gemaakt',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Aantal errors tijdens het overbodig maken',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Aantal objecten aangemaakt',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Aantal errors tijdens het aanmaken',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Aantal objecten geüpdatet',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Aantal errors tijden het updaten',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Aantal errors tijdens de reconciliation',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Aantal replicas verdwenen',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Aantal objecten geüpdatet',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Aantal onveranderde objecten',
	'Class:SynchroLog/Attribute:last_error' => 'Laaste error',
	'Class:SynchroLog/Attribute:traces' => 'Sporen',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroReplica/Attribute:dest_id' => 'Bestemming van het object (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Type bestemming',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Laatst gezien',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Aangepast',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nieuw',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Overbodig',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Gesynchroniseerd',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object aangemaakt?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Laatste Error',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Waarschuwingen',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Datum van aanmaken',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Datum van de laatste aanpassing',
	'Class:appUserPreferences' => 'Gebruikersvoorkeuren',
	'Class:appUserPreferences/Attribute:userid' => 'Gebruiker',
	'Class:appUserPreferences/Attribute:preferences' => 'Voorkeuren',
	'Core:ExecProcess:Code1' => 'Verkeerde command of command beëindigd met errors (bijvoorbeeld verkeerde scriptnaam)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, of runtime)',
));

//
// Attribute Duration
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Core:Duration_Seconds'	=> '%1$ds',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',	
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',		
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',		
));

?>

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
 * @author	Stephan Rosenke <stephan.rosenke@itomig.de>
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Core:DeletedObjectLabel' => '%1s (gelöscht)',
	'Core:DeletedObjectTip' => 'Das Objekt wurde gelöscht am %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objekt nicht gefunden (Klasse: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Das Objekt konnte nicht gefunden werden. Es könnte bereits vor einiger Zeit gelöscht worden sein und das Log seither bereinigt.',

	'Core:UniquenessDefaultError' => 'Eindeutigkeitsfehler: \'%1$s\'',

	'Core:AttributeLinkedSet' => 'Array von Objekten',
	'Core:AttributeLinkedSet+' => 'Beliebige Art von Objekten der [subclass] der selben Klasse',

	'Core:AttributeDashboard' => 'Dashboard',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => 'Telefonnummer',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Obsolet seit',
	'Core:AttributeObsolescenceDate+' => 'Datum, an dem das Objekt auf "obsolet" gesetzt wurde',

	'Core:AttributeTagSet' => 'Liste von Tags',
	'Core:AttributeTagSet+' => 'List von Tags',
	'Core:AttributeSet:placeholder' => 'Zum Hinzufügen klicken',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s von %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s von Kindklassen)',

	'Core:AttributeCaseLog' => 'Log',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Berechnetes Enum',
	'Core:AttributeMetaEnum+' => 'Liste berechneter alphanumerischer Strings',

	'Core:AttributeLinkedSetIndirect' => 'Array von Objekten (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Beliebige Art von Objekten der [subclass] der selben Klasse',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numerischer Wert (kann negativ sein)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Dezimaler Wert (kann negativ sein)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolscher Wert',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Ja',
	'Core:AttributeBoolean/Value:no' => 'Nein',

	'Core:AttributeArchiveFlag' => 'Archiv Flag',
	'Core:AttributeArchiveFlag/Value:yes' => 'Ja',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Dieses Objekt ist nur im Archiv Modus sichtbar',
	'Core:AttributeArchiveFlag/Value:no' => 'Nein',
	'Core:AttributeArchiveFlag/Label' => 'Archiviert',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archivierungs Datum',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsoleszenz Flag',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Ja',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Dieses Objekt wird aus der Impact Analyse ausgeschlossen und in den Suchergebnissen versteckt',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Nein',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolet',
	'Core:AttributeObsolescenceFlag/Label+' => 'Dynamisch berechnet wegen anderer Attribute',
	'Core:AttributeObsolescenceDate/Label' => 'Obsoleszenz Datum',
	'Core:AttributeObsolescenceDate/Label+' => 'Ungefähres Datum an dem das Objekt als obsolet betrachtet wird',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => 'Alphanumerischer String',

	'Core:AttributeClass' => 'Class',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => 'Benutzersprache',
	'Core:AttributeApplicationLanguage+' => 'Sprache und LAnd (DE DE)',

	'Core:AttributeFinalClass' => 'Class (auto)',
	'Core:AttributeFinalClass+' => 'Echte Klasse des Objekt (automatisch erstellt durch den Core)',

	'Core:AttributePassword' => 'Passwort',
	'Core:AttributePassword+' => 'Passwort eines externen Geräts',

	'Core:AttributeEncryptedString' => 'verschlüsselter String',
	'Core:AttributeEncryptedString+' => 'mit einem lokalen Schüssel verschlüsselter String',
	'Core:AttributeEncryptUnknownLibrary' => 'Angegebene Library zur Verschlüsslung (%1$s) ist unbekannt',
	'Core:AttributeEncryptFailedToDecrypt' => '** Entschlüsslungsfehler **',

	'Core:AttributeText' => 'Text',
	'Core:AttributeText+' => 'Mehrzeiliger String',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML-String',

	'Core:AttributeEmailAddress' => 'Email-Adresse',
	'Core:AttributeEmailAddress+' => 'Email-Adresse',

	'Core:AttributeIPAddress' => 'IP-Adresse',
	'Core:AttributeIPAddress+' => 'IP-Adresse',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object-Query-Langage-Ausdruck',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Liste vordefinierter alphanumerischer Strings',

	'Core:AttributeTemplateString' => 'Vorlagen-String',
	'Core:AttributeTemplateString+' => 'String mit Platzhaltern',

	'Core:AttributeTemplateText' => 'Vorlagen-Text',
	'Core:AttributeTemplateText+' => 'Text mit Platzhaltern',

	'Core:AttributeTemplateHTML' => 'Vorlagen-HTML',
	'Core:AttributeTemplateHTML+' => 'HTML mit Platzhaltern',

	'Core:AttributeDateTime' => 'Datum/Uhrzeit',
	'Core:AttributeDateTime+' => 'Datum und Uhrzeit (Jahr-Monat-Tag hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Datumsformat:<br/>
	<b>%1$s</b><br/>
	Beispiel: %2$s
</p>
<p>
Operatoren:<br/>
	<b>&gt;</b><em>Datum</em><br/>
	<b>&lt;</b><em>Datum</em><br/>
	<b>[</b><em>Datum</em>,<em>Datum</em><b>]</b>
</p>
<p>
Falls der Zeit-Wert weggelassenw ird, ist der Default 00:00:00
</p>',

	'Core:AttributeDate' => 'Datum',
	'Core:AttributeDate+' => 'Datum (Jahr-Monat-Tag)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Datumsformat:<br/>
	<b>%1$s</b><br/>
	Beispiel: %2$s
</p>
<p>
Operatoren:<br/>
	<b>&gt;</b><em>Datum</em><br/>
	<b>&lt;</b><em>Datum</em><br/>
	<b>[</b><em>Datum</em>,<em>Datum</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => 'Frist',
	'Core:AttributeDeadline+' => 'relativ zur aktuellen Zeit angezeigtes Datum',

	'Core:AttributeExternalKey' => 'Externer Schlüssel',
	'Core:AttributeExternalKey+' => 'Externer (oder fremder) Schlüssel',

	'Core:AttributeHierarchicalKey' => 'Hierarischer Key',
	'Core:AttributeHierarchicalKey+' => 'Externer Key oder Foreign Key zum Parent',

	'Core:AttributeExternalField' => 'Externes Feld',
	'Core:AttributeExternalField+' => 'durch einen externen Schlüssel abgebildetes Feld',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Absolute oder relative URL als Text-String',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Beliebiger binärer Inhalt (Dokument)',

	'Core:AttributeOneWayPassword' => 'gehashtes Passwort',
	'Core:AttributeOneWayPassword+' => 'gehashtes Passwort',

	'Core:AttributeTable' => 'Tabelle',
	'Core:AttributeTable+' => 'Indiziertes Array mit zwei Dimensionen',

	'Core:AttributePropertySet' => 'Eigenschaften',
	'Core:AttributePropertySet+' => 'Liste typloser Eigenschaften (Name und Wert)',

	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => '',

	'Core:FriendlyName-Label' => 'Voller Name (Friendly Name)',
	'Core:FriendlyName-Description' => 'Friendly name',

	'Core:AttributeTag' => 'Tags',
	'Core:AttributeTag+' => 'Tags',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Konsole',
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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChange' => 'Change',
	'Class:CMDBChange+' => 'Protokollierung der Changes',
	'Class:CMDBChange/Attribute:date' => 'Datum',
	'Class:CMDBChange/Attribute:date+' => 'Datum und Uhrzeit der Änderungen',
	'Class:CMDBChange/Attribute:userinfo' => 'Sonstige Informationen',
	'Class:CMDBChange/Attribute:userinfo+' => 'Aufruferdefinierte Informationen',
));

//
// Class: CMDBChangeOp
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOp' => 'Change-Operation',
	'Class:CMDBChangeOp+' => 'Protokoll der Change-Operation',
	'Class:CMDBChangeOp/Attribute:change' => 'Change',
	'Class:CMDBChangeOp/Attribute:change+' => 'Change',
	'Class:CMDBChangeOp/Attribute:date' => 'Datum',
	'Class:CMDBChangeOp/Attribute:date+' => 'Datum und Uhrzeit der Änderungen',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Benutzer',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Wer führte diese Änderung durch',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Objektklasse',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'Objektklasse',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Objekt-ID',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'Objekt-ID',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'Typ',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpCreate' => 'Objekterstellung',
	'Class:CMDBChangeOpCreate+' => 'Protokoll der Objekterstellung',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpDelete' => 'Objektlöschung',
	'Class:CMDBChangeOpDelete+' => 'Protokoll der Objektlöschung',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpSetAttribute' => 'Objektänderung',
	'Class:CMDBChangeOpSetAttribute+' => 'Protokoll der Objektänderungen',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribut',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Code der geänderten Eigenschaft',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Eigenschaften ändern',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Aufzeichnen der Änderungen am Objekt',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Vorheriger Wert',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Vorheriger Wert des Attributes',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Neuer Wert',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Neuer Wert des Attributes',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Change:ObjectCreated' => 'Objekt erstellt',
	'Change:ObjectDeleted' => 'Objekt gelöscht',
	'Change:ObjectModified' => 'Objekt geändert',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s geändert zu %2$s (vorheriger Wert: %3$s)',
	'Change:AttName_SetTo' => '%1$s geändert zu %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s zugefügt an %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modifiziert, vorheriger Wert: %2$s',
	'Change:AttName_Changed' => '%1$s modifiziert',
	'Change:AttName_EntryAdded' => '%1$s modifiziert, neuer Eintrag hinzugefügt: %2$s',
	'Change:LinkSet:Added' => 'hinzugefügt: %1$s',
	'Change:LinkSet:Removed' => 'entfernt: %1$s',
	'Change:LinkSet:Modified' => 'modifizert: %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Daten ändern',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Aufzeichnen der Datenänderung',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Vorherige Daten',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Vorherige Inhalte des Attributes',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Text ändern',
	'Class:CMDBChangeOpSetAttributeText+' => 'Aufzeichnen der Textänderung',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Vorherige Daten',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Vorherige Inhalte des Attributes',
));

//
// Class: Event
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Event' => 'Log Event',
	'Class:Event+' => 'Ein anwendungsinternes Event',
	'Class:Event/Attribute:message' => 'Nachricht',
	'Class:Event/Attribute:message+' => 'Kurze Beschreibung des Events',
	'Class:Event/Attribute:date' => 'Datum',
	'Class:Event/Attribute:date+' => 'Datum und Uhrzeit der Änderungen',
	'Class:Event/Attribute:userinfo' => 'Benutzer-Information',
	'Class:Event/Attribute:userinfo+' => 'Identifikation des Benutzers, der die Aktion ausführte, die dieses Event ausgelöst hat',
	'Class:Event/Attribute:finalclass' => 'Typ',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventNotification' => 'Notification Event',
	'Class:EventNotification+' => 'Protokollierung der gesendeten Benachrichtigungen',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Aktion',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Objekt-ID',
	'Class:EventNotification/Attribute:object_id+' => 'Objekt-ID (Klasse, die von Trigger definiert wurde?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventNotificationEmail' => 'Email Emission Event',
	'Class:EventNotificationEmail+' => 'Verfolgung einer Email, die gesendet wurde',
	'Class:EventNotificationEmail/Attribute:to' => 'An',
	'Class:EventNotificationEmail/Attribute:to+' => 'An',
	'Class:EventNotificationEmail/Attribute:cc' => 'Kopie an',
	'Class:EventNotificationEmail/Attribute:cc+' => 'Kopie an',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Blindkopie (BCC)',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'Blindkopie (BCC)',
	'Class:EventNotificationEmail/Attribute:from' => 'Von',
	'Class:EventNotificationEmail/Attribute:from+' => 'Absender der Nachricht',
	'Class:EventNotificationEmail/Attribute:subject' => 'Betreff',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Betreff',
	'Class:EventNotificationEmail/Attribute:body' => 'Inhalt der Nachricht',
	'Class:EventNotificationEmail/Attribute:body+' => 'Inhalt der Nachricht',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Anhänge',
	'Class:EventNotificationEmail/Attribute:attachments+' => 'Anhänge in der Nachricht',
));

//
// Class: EventIssue
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventIssue' => 'Issue Event',
	'Class:EventIssue+' => 'Protokollierung einer Issue (Warnungen, Fehler, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Issue',
	'Class:EventIssue/Attribute:issue+' => 'Was passierte?',
	'Class:EventIssue/Attribute:impact' => 'Auswirkungen',
	'Class:EventIssue/Attribute:impact+' => 'Was waren die Auswirkungen?',
	'Class:EventIssue/Attribute:page' => 'Seite',
	'Class:EventIssue/Attribute:page+' => 'HTTP entry point',
	'Class:EventIssue/Attribute:arguments_post' => 'Eingegebene Arguments',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST-Argumente',
	'Class:EventIssue/Attribute:arguments_get' => 'URL-Argumente',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET-Argumente',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Daten',
	'Class:EventIssue/Attribute:data+' => 'Mehr Informationen',
));

//
// Class: EventWebService
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventWebService' => 'Web Service Event',
	'Class:EventWebService+' => 'Protokollierung eines Web Service Calls',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Name der Operation',
	'Class:EventWebService/Attribute:result' => 'Ergebnis',
	'Class:EventWebService/Attribute:result+' => 'Gesamterfolg/-misserfolg',
	'Class:EventWebService/Attribute:log_info' => 'Informations-Protokollierung',
	'Class:EventWebService/Attribute:log_info+' => 'Ergebnis der Informations-Protokollierung',
	'Class:EventWebService/Attribute:log_warning' => 'Warnungs-Protokollierung',
	'Class:EventWebService/Attribute:log_warning+' => 'Ergebnis der Warnungs-Protokollierung',
	'Class:EventWebService/Attribute:log_error' => 'Fehler-Protokollierung',
	'Class:EventWebService/Attribute:log_error+' => 'Ergebnis der Fehler-Protokollierung',
	'Class:EventWebService/Attribute:data' => 'Daten',
	'Class:EventWebService/Attribute:data+' => 'Ergebnisdaten',
));

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventRestService' => 'REST/JSON Call',
	'Class:EventRestService+' => 'Trace eines REST/JSON-Calls',
	'Class:EventRestService/Attribute:operation' => 'Operation',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Version',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Eingabe',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Ergebniscode',
	'Class:EventRestService/Attribute:code+' => 'Ergebniscode',
	'Class:EventRestService/Attribute:json_output' => 'Antwort',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP Antwort (JSON)',
	'Class:EventRestService/Attribute:provider' => 'Provider',
	'Class:EventRestService/Attribute:provider+' => 'PHP-Klasse die die erwartete Operation implementiert',
));

//
// Class: EventLoginUsage
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:EventLoginUsage' => 'Login Verwendung',
	'Class:EventLoginUsage+' => '',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Benutzername',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Benutzer-Mailadresse',
	'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Action' => 'Benutzerdefinierte Aktion',
	'Class:Action+' => 'Benutzerdefinierte Aktionen',
	'Class:Action/Attribute:name' => 'Name',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Beschreibung',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Status',
	'Class:Action/Attribute:status+' => 'Im Einsatz oder?',
	'Class:Action/Attribute:status/Value:test' => 'Wird getestet',
	'Class:Action/Attribute:status/Value:test+' => 'Wird getestet',
	'Class:Action/Attribute:status/Value:enabled' => 'Im Einsatz',
	'Class:Action/Attribute:status/Value:enabled+' => 'Im Einsatz',
	'Class:Action/Attribute:status/Value:disabled' => 'Inaktiv',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inaktiv',
	'Class:Action/Attribute:trigger_list' => 'Zugehörige Trigger',
	'Class:Action/Attribute:trigger_list+' => 'Trigger, die mit dieser Aktion verknüpft sind',
	'Class:Action/Attribute:finalclass' => 'Typ',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ActionNotification' => 'Benachrichtigung',
	'Class:ActionNotification+' => 'Benachrichtigung (Kurzbeschreibung)',
));

//
// Class: ActionEmail
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ActionEmail' => 'Email-Benachrichtigung',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Testempfänger',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Empfänger im Fall eines "Test"-Status',
	'Class:ActionEmail/Attribute:from' => 'Von',
	'Class:ActionEmail/Attribute:from+' => 'Wird im Email-Header mitgesendet',
	'Class:ActionEmail/Attribute:reply_to' => 'Antworten an',
	'Class:ActionEmail/Attribute:reply_to+' => 'Wird im Email-Header mitgesendet',
	'Class:ActionEmail/Attribute:to' => 'An',
	'Class:ActionEmail/Attribute:to+' => 'Empfänger der Nachricht',
	'Class:ActionEmail/Attribute:cc' => 'Kopie an',
	'Class:ActionEmail/Attribute:cc+' => 'Kopie an',
	'Class:ActionEmail/Attribute:bcc' => 'Blindkopie (BCC)',
	'Class:ActionEmail/Attribute:bcc+' => 'Blindkopie (BCC)',
	'Class:ActionEmail/Attribute:subject' => 'Betreff',
	'Class:ActionEmail/Attribute:subject+' => 'Betreff der Email',
	'Class:ActionEmail/Attribute:body' => 'Inhalt der Nachricht',
	'Class:ActionEmail/Attribute:body+' => 'Inhalt der Nachricht',
	'Class:ActionEmail/Attribute:importance' => 'Priorität',
	'Class:ActionEmail/Attribute:importance+' => 'Prioritätseinstufung',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'niedrig',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'niedrig',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'hoch',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'hoch',
));

//
// Class: Trigger
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => 'Beschreibung',
	'Class:Trigger/Attribute:description+' => 'Kurzbeschreibung',
	'Class:Trigger/Attribute:action_list' => 'Verbundene Trigger-Aktionen',
	'Class:Trigger/Attribute:action_list+' => 'Aktionen, die ausgeführt werden, wenn der Trigger aktiviert ist',
	'Class:Trigger/Attribute:finalclass' => 'Typ',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObject' => 'Trigger (klassenunabhängig)',
	'Class:TriggerOnObject+' => 'Trigger einer gegebenen Klasse an Objekten',
	'Class:TriggerOnObject/Attribute:target_class' => 'Zielklasse',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Fehlerhafter Filter-Query: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Der Filter muss Objekte vom Typ \\"%1$s\\" zurückgeben.',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (bei Update aus dem Portal)',
	'Class:TriggerOnPortalUpdate+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnStateChange' => 'Trigger (bei Statusänderung)',
	'Class:TriggerOnStateChange+' => 'Trigger bei Änderung des Objektstatus',
	'Class:TriggerOnStateChange/Attribute:state' => 'Status',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnStateEnter' => 'Trigger (beim Eintritt eines Status)',
	'Class:TriggerOnStateEnter+' => 'Trigger bei Eintritt einer Objektstatusänderung',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnStateLeave' => 'Trigger (beim Verlassen eines Status)',
	'Class:TriggerOnStateLeave+' => 'Trigger beim Verlassen einer Objektstatusänderung',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (bei Objekterstellung)',
	'Class:TriggerOnObjectCreate+' => 'Trigger bei Objekterstellung (einer Kindklasse) einer gegebenen Klasse',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (bei Objektlöschung)',
	'Class:TriggerOnObjectDelete+' => 'Trigger bei Objektlöschung einer gegebenen Klasse oder Kindklasse',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (bei Objektanpassung)',
	'Class:TriggerOnObjectUpdate+' => 'Trigger bei Objektanpassung einer gegebenen Klasse oder Kindklasse',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Ziel-Felder',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (bei Schwellenwert)',
	'Class:TriggerOnThresholdReached+' => '',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Uhr stoppen',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Schwellenwert',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTriggerAction' => 'Aktion/Trigger',
	'Class:lnkTriggerAction+' => 'Verknüpfung zwischen einem Trigger und einer Aktion',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Aktion',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Die auszuführende Aktion',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Aktion',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Reihenfolge',
	'Class:lnkTriggerAction/Attribute:order+' => 'Reihenfolge der Aktionsausführungen',
));

//
// Synchro Data Source
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SynchroDataSource/Attribute:name' => 'Name',
	'Class:SynchroDataSource/Attribute:name+' => 'Name',
	'Class:SynchroDataSource/Attribute:description' => 'Beschreibung',
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Ziel-Klasse',
	'Class:SynchroDataSource/Attribute:user_id' => 'Benutzer',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'zu benachrichtigender Kontakt',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Kontakt, der im Fehlerfall benachrichtigt werden muß',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Hyperlink zum Icon',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Ein (kleines) Bild verlinken, das die Applikation repräsentiert, mit der iTop synchronisiert wird',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hyperlink zur Applikation',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink zum iTop Objekt in der externen Applikation mit der iTop synchronisiert wird (falls anwendbar). Mögliche Platzhalter: $this->attribute$ und $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Abgleichsvorgehen',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Intervall zwischen zwei vollständigen Reloads',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Ein vollständiger Reload des gesamten Datenbestands muß mindestens in diesem Intervall erfolgen',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Verhalten bei keinen Treffern',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Verhalten, wenn die Suche keine Objekte zurückgibt',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Verhalten bei einem Treffer',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Verhalten, wenn die Suche genau ein Objekt zurückgibt',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Verhalten bei vielen Treffern',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Verhalten, wenn die Suche mehr als ein Objekt zurückgibt',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'zugelassene Benutzer',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Benutzer, die synchronisierte Objekte löschen dürfen',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Niemand',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'nur Administratoren',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Alle zugelassenen Benutzer',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Update-Regeln',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: Feld_Name:Wert; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Zeitraum bis zur endgültigen Löschung',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Zeitraum, nach dem ein obsoletes Objekt endgültig gelöscht wird',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Datenbanktabelle',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name der Tabelle, die Speicherung der Daten aus dieser Datenquelle. Ein Default-Name wird automatisch berechnet, wenn dieses Feld leer gelassen wird.',
	'SynchroDataSource:Description' => 'Beschreibung',
	'SynchroDataSource:Reconciliation' => 'Suche &amp; Abgleich',
	'SynchroDataSource:Deletion' => 'Löschregeln',
	'SynchroDataSource:Status' => 'Status',
	'SynchroDataSource:Information' => 'Information',
	'SynchroDataSource:Definition' => 'Definition',
	'Core:SynchroAttributes' => 'Attribute',
	'Core:SynchroStatus' => 'Status',
	'Core:Synchro:ErrorsLabel' => 'Fehler',
	'Core:Synchro:CreatedLabel' => 'erzeugt',
	'Core:Synchro:ModifiedLabel' => 'modifiziert',
	'Core:Synchro:UnchangedLabel' => 'unverändert',
	'Core:Synchro:ReconciledErrorsLabel' => 'Fehler',
	'Core:Synchro:ReconciledLabel' => 'abgeglichen',
	'Core:Synchro:ReconciledNewLabel' => 'erzeugt',
	'Core:SynchroReconcile:Yes' => 'Ja',
	'Core:SynchroReconcile:No' => 'Nein',
	'Core:SynchroUpdate:Yes' => 'Ja',
	'Core:SynchroUpdate:No' => 'Nein',
	'Core:Synchro:LastestStatus' => 'Neuester Status',
	'Core:Synchro:History' => 'Synchronisations-Verlauf',
	'Core:Synchro:NeverRun' => 'Synchronisation noch nicht erfolgt. Kein Protokoll verfügbar.',
	'Core:Synchro:SynchroEndedOn_Date' => 'Die letzte Synchronisation endete um %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Die Synchronisation, die um %1$s gestartet wurde, läuft noch ...',
	'Menu:DataSources' => 'Datenquellen für die Synchronisation', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Alle Datenquellen für die Synchronisation', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignoriert (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Verschwunden (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Vorhanden (%1$s)',
	'Core:Synchro:label_repl_new' => 'Neu (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'gelöscht (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'obsolet (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Fehler (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Keine Aktion (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'unverändert (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Updated (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Fehler (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'unverändert (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'updated (%1$s)',
	'Core:Synchro:label_obj_created' => 'erzeugt (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Fehler (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica verarbeitet: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Mindestens ein Abgleichsschlüssel muß angegeben werden oder das Abgleichsvorgehen muß den primären Schlüssel verwenden.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Der Zeitraum bis zur endgültigen Löschung muß angegeben werden, da die Objekte nach einer Kennzeichnung als obsolet gelöscht werden.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete Objekte werden aktualisiert, aber es wurde keine Aktualisierung angegeben.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Tabelle %1$s existiert bereits in der Datenbank. Bitte benutzen Sie einen anderen Namen für die Datenbanktabelle aus dieser Datenquelle.',
	'Core:SynchroReplica:PublicData' => 'Öffentliche Daten',
	'Core:SynchroReplica:PrivateDetails' => 'Private Hinweise',
	'Core:SynchroReplica:BackToDataSource' => 'Zurück zur Synchronisations-Datenquelle: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Liste der Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primärschlüssel)',
	'Core:SynchroAtt:attcode' => 'Attribut',
	'Core:SynchroAtt:attcode+' => 'Feld des Objekts',
	'Core:SynchroAtt:reconciliation' => 'Abgleich',
	'Core:SynchroAtt:reconciliation+' => 'Für die Suche genutzt',
	'Core:SynchroAtt:update' => 'Update',
	'Core:SynchroAtt:update+' => 'Für die Aktualisierung des Objekts benutzt',
	'Core:SynchroAtt:update_policy' => 'Update Policy',
	'Core:SynchroAtt:update_policy+' => 'Verhalten des aktualisierten Feld',
	'Core:SynchroAtt:reconciliation_attcode' => 'Abgleichsschlüssel',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attributscode für den Abgleich über einen externen Schlüssel',
	'Core:SyncDataExchangeComment' => '(DataExchange)',
	'Core:Synchro:ListOfDataSources' => 'Liste der Datenquellen:',
	'Core:Synchro:LastSynchro' => 'Letzte Synchronisation:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Dieses Objekt wird mit einer externen Datenquelle synchronisiert',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Das Objekt wurde durch die externe Datenquelle %1$s <b>erzeugt</b>',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Das Objekt kann durch die externe Datenquelle %1$s <b>gelöscht werden</b>.',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Sie <b>können das Objekt nicht löschen</b>, weil es zur externen Datenquelle %1$s gehört',
	'TitleSynchroExecution' => 'Ausführung der Synchronisation',
	'Class:SynchroDataSource:DataTable' => 'Datenbanktabelle: %1$s',
	'Core:SyncDataSourceObsolete' => 'Die Datenquelle ist als obsolet markiert. Operation abgebrochen.',
	'Core:SyncDataSourceAccessRestriction' => 'Nur Administratoren oder die in der Datenquelle angegebenen Benutzer können diese Operation ausführen. Operation abgebrochen.',
	'Core:SyncTooManyMissingReplicas' => 'Alle Einträge wurden seit längerem nicht aktualisiert, alle Objekte könnten gelöscht werden. Bitte überprüfen Sie die Funktionalität der Synchronisation. Operation abgebrochen.',
	'Core:SyncSplitModeCLIOnly' => 'Die Synchronisation kann nur in Chunks ausgeführt werden, wenn sie im CLI-Moduls verwendet wird.',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s Replicas, %2$s Fehler, %3$s Warnung(en).',
	'Core:SynchroReplica:TargetObject' => 'Synchronisiertes Objekt: %1$s',
	'Class:AsyncSendEmail' => 'Email (asynchron)',
	'Class:AsyncSendEmail/Attribute:to' => 'An',
	'Class:AsyncSendEmail/Attribute:subject' => 'Betreff',
	'Class:AsyncSendEmail/Attribute:body' => 'Body',
	'Class:AsyncSendEmail/Attribute:header' => 'Header',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Verschlüsseltes Passwort',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Vorheriger Wert',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Verschlüsseltes Feld',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Vorheriger Wert',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Fall-Protokoll',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'letzter Eintrag',
	'Class:SynchroDataSource' => 'Synchronisations-Datenquelle',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementation',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsolet',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Produktion',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Anwendungsbereich',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Attribute benutzen',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Feld primary_key benutzen',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Erzeugen',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Fehler',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Fehler',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Erzeugen',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Fehler',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'ersten Treffer benutzen',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Löschungs-Policy',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Löschen',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorieren',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update, danach Löschen',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Liste der Attribute',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'nur Administratoren',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Jeder darf solche Objekte löschen',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Niemand',
	'Class:SynchroAttribute' => 'Synchronisations-Attribut',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchronisations-Datenquelle',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attributs-Code',
	'Class:SynchroAttribute/Attribute:update' => 'Update',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Abgleich',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'gesperrt',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'entsperrt',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialisieren falls leer',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Klasse',
	'Class:SynchroAttExtKey' => 'Synchronisations-Attribut (Externer Schlüssel)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Abgleichsattribut',
	'Class:SynchroAttLinkSet' => 'Synchronisations-Attribut (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Reihen-Trenner',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributs-Trenner',
	'Class:SynchroLog' => 'Synchronisations-Protokoll',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchronisations-Datenquelle',
	'Class:SynchroLog/Attribute:start_date' => 'Anfangsdatum',
	'Class:SynchroLog/Attribute:end_date' => 'Enddatum',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'vervollständigt',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Fehler',
	'Class:SynchroLog/Attribute:status/Value:running' => 'noch in Betrieb',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica vorhanden',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica insgesamt',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb Objekte gelöscht',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb Fehler während des Löschvorgangs',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb Objekte obsolet',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb Fehler während des Obsolet-Machens',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb Objekte erzeugt',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb oder Fehler während der Erzeugung',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb Objekte aktualisiert',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb Fehler während der Aktualisierung',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb Fehler während des Abgleichs',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica verschwunden',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb Objekte aktualisiert',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb Objekte nicht verändert',
	'Class:SynchroLog/Attribute:last_error' => 'Letzter Fehler',
	'Class:SynchroLog/Attribute:traces' => 'Traces',
	'Class:SynchroReplica' => 'Synchronisations-Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchronisations-Datenquelle',
	'Class:SynchroReplica/Attribute:dest_id' => 'Ziel-Objekt (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Ziel-Typ',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Zuletzt gesehen',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modifiziert',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Neu',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsolet',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Verwaist',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronisiert',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objekt erzeugt',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Letzter Fehler',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Letzte Warnung',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Erzeugungs-Datum',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Datum der letzten Modifikation',
	'Class:appUserPreferences' => 'Benutzer-Voreinstellungen',
	'Class:appUserPreferences/Attribute:userid' => 'Benutzer',
	'Class:appUserPreferences/Attribute:preferences' => 'Voreinstellungen',
	'Core:ExecProcess:Code1' => 'Falscher Befehl oder Befehl mit Fehler beendet (z.B. falscher Skriptname).',
	'Core:ExecProcess:Code255' => 'PHP-Fehler (Parsing oder Laufzeit)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Abgelaufene Zeit (gespeichert als \\"%1$s\\")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Zeitaufwand für \\"%1$s\\"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline für \\"%1$s\\" um %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Fehlender Parameter "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'ungültiger Wert für den Paramter "query". Es gibt keinen Eintrag in der Query-Bibliothek, der zu der id "%1$s" korrespondiert.',
	'Core:BulkExport:ExportFormatPrompt' => 'Exportformat:',
	'Core:BulkExportOf_Class' => '%1$s-Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Klicken Sie hier um %1$s herunterzuladen',
	'Core:BulkExport:ExportResult' => 'Ergebnis ses Exportvorgangs:',
	'Core:BulkExport:RetrievingData' => 'Lese Daten...',
	'Core:BulkExport:HTMLFormat' => 'Webseite (*.html)',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 oder neuer (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF-Dokument (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Nutzen Sie Drag and Drop für die Spaltenüberschriften um die Spalten zu sortieren. Vorschau %1$s Zeilen. Gesamtzeilenzahl für den Export: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Wählen Sie die Spalten für den Export aus der obenstehenden Liste',
	'Core:BulkExport:ColumnsOrder' => 'Spaltenreihenfolge',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Verfügbare Spalten für %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Wählen Sie mindestens eine Spalte für den Export aus',
	'Core:BulkExport:CheckAll' => 'Alle markieren',
	'Core:BulkExport:UncheckAll' => 'Auswahl aufheben',
	'Core:BulkExport:ExportCancelledByUser' => 'Export durch den Benutzer abgebrochen',
	'Core:BulkExport:CSVOptions' => 'CSV-Optionen',
	'Core:BulkExport:CSVLocalization' => 'Lokaliserung',
	'Core:BulkExport:PDFOptions' => 'PDF-Optionen',
	'Core:BulkExport:PDFPageFormat' => 'Seitenformat',
	'Core:BulkExport:PDFPageSize' => 'Seitengrösse:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Seitenausrichtung:',
	'Core:BulkExport:PageOrientation-L' => 'Querformat',
	'Core:BulkExport:PageOrientation-P' => 'Hochformat',
	'Core:BulkExport:XMLFormat' => 'XML-Datei (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML-Optionen',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet-Optionen',
	'Core:BulkExport:OptionNoLocalize' => 'Werte von Aufzählungsfeldern nicht lokalisieren',
	'Core:BulkExport:OptionLinkSets' => 'Inkludiere verlinkte Objekte',
	'Core:BulkExport:OptionFormattedText' => 'Behalte Textformatierung bei',
	'Core:BulkExport:ScopeDefinition' => 'Definition der zu exportierenden Objekte',
	'Core:BulkExportLabelOQLExpression' => 'OQL-Abfrage',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query-Bibliotheks-Eintrag:',
	'Core:BulkExportMessageEmptyOQL' => 'Bitte geben Sie eine gültige OQL-Abfrage ein.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Bitte wählen Sie einen gültigen Query-Bibliotheks-Eintrag aus. ',
	'Core:BulkExportQueryPlaceholder' => 'Geben Sie eine OQL-Abfrage ein...',
	'Core:BulkExportCanRunNonInteractive' => 'Klicken Sie hier, um den Export im nicht-interaktiven Modus auszuführen',
	'Core:BulkExportLegacyExport' => 'Klicken Sie hier, um auf die Legacy-Version des Exports zuzugreifen',
	'Core:BulkExport:XLSXOptions' => 'Excel-Optionen',
	'Core:BulkExport:TextFormat' => 'Textfelder enthalten HTML-Markup',
	'Core:BulkExport:DateTimeFormat' => 'Datum- und Zeitformat',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Standardformat (%1$s), z.B. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Angepasstes format: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Seite %1$s',
	'Core:DateTime:Placeholder_d' => 'TT', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'T', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'JJJJ', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'JJ', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Falsches Format',
	'Core:Validator:Mandatory' => 'Bitte dieses Feld ausfüllen',
	'Core:Validator:MustBeInteger' => 'Muss ein Integer sein',
	'Core:Validator:MustSelectOne' => 'Min. ein Eintrag muss ausgewählt sein',
));

//
// Class: TagSetFieldData
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TagSetFieldData' => '%2$s für die Klasse %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Code',
	'Class:TagSetFieldData/Attribute:code+' => 'Interner code. Muss mindestens 3 alphanumerische Zeichen enthalten',
	'Class:TagSetFieldData/Attribute:label' => 'Label',
	'Class:TagSetFieldData/Attribute:label+' => 'Anzeigelabel',
	'Class:TagSetFieldData/Attribute:description' => 'Beschreibung',
	'Class:TagSetFieldData/Attribute:description+' => 'Beschreibung',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag-Klasse',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Objekt-Klasse',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Attributscode',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Verwendete tags können nicht gelöscht werden',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tag-Codes oder Labels müssen eindeutig sein',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Tags-Code muss zwischen 3 und %1$d alphanumerische Zeichen enthalten',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'der gewählte Tag-Code ist ein reservierter Begriff',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Tag-Labels dürfen nicht leer sein oder \'%1$s\' enthalten',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tag-Code kann nicht geändert werden, wenn er in Verwendung ist',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => '"Tag-Objektklasse" kann nicht geändert werden',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tag "Attribute Code" kann nicht geändert werden',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Tag Verwendung (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Kein Eintrag für dieses Tag gefunden',
));

//
// Class: DBProperty
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DBProperty' => 'DB Eigenschaft',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Name',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Beschreibung',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Wert',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Änderungsdatum',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Änderungskommentar',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:BackgroundTask' => 'Hintergrund Task',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Klassenname',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Erster Lauf',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Letzter Lauf',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Nächster Lauf',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Anzahl der Läufe',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Dauer des letzten Laufs',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Minimal Laufzeit',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Maximale Laufzeit',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Durchschnittliche Laufzeit',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Laufend',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:AsyncTask' => 'Async. Task',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Erstellt',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Gestartet',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Geplant',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Event',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Final Class',
	'Class:AsyncTask/Attribute:finalclass+' => '',
));

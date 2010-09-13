<?php
// Copyright (C) 2010 Combodo SARL
//
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

/**
 * Localized data
 *
 * @author   Erwan Taloc <erwan.taloc@combodo.com>
 * @author   Romain Quetiez <romain.quetiez@combodo.com>
 * @author   Denis Flaven <denis.flaven@combodo.com>
 * @license   http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChange' => 'Change',
	'Class:CMDBChange+' => 'Changes Tracking',
	'Class:CMDBChange/Attribute:date' => 'Datum',
	'Class:CMDBChange/Attribute:date+' => 'Datum und Uhrzeit der Änderungen',
	'Class:CMDBChange/Attribute:userinfo' => 'Sonstige Informationen',
	'Class:CMDBChange/Attribute:userinfo+' => 'Aufruferdefinierte Informationen',
));

//
// Class: CMDBChangeOp
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOp' => 'Change Operation',
	'Class:CMDBChangeOp+' => 'Change operations tracking',
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

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Daten ändern',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Aufzeichnen der Data Changes',
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
	'Class:Event+' => 'Ein anwendungsinterner Event',
	'Class:Event/Attribute:message' => 'Nachricht',
	'Class:Event/Attribute:message+' => 'Kurze Beschreibung des Events',
	'Class:Event/Attribute:date' => 'Datum',
	'Class:Event/Attribute:date+' => 'Datum und Uhrzeit der Änderungen',
	'Class:Event/Attribute:userinfo' => 'Benutzer-Information',
	'Class:Event/Attribute:userinfo+' => 'Identifikation des Benutzer, der die Aktion ausführte, die diesen Event ausgelöst hat',
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
	'Class:EventNotification/Attribute:trigger_id+' => 'Benutzerkonto',
	'Class:EventNotification/Attribute:action_id' => 'Benutzer',
	'Class:EventNotification/Attribute:action_id+' => 'Benutzerkonto',
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
));

//
// Class: TriggerOnObject
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObject' => 'Trigger (klassenunabhängig)',
	'Class:TriggerOnObject+' => 'Trigger einer gegebenen Klasse an Objekten',
	'Class:TriggerOnObject/Attribute:target_class' => 'Zielklasse',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
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
	'Class:TriggerOnStateEnter+' => 'Trigger bei Eintritt einer Objektstatusänderungg',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnStateLeave' => 'Trigger (beim Verlassen eines Status)',
	'Class:TriggerOnStateLeave+' => 'Trigger beim Verlassen einer Objektstatusänderungg',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (bei Objekterstellung)',
	'Class:TriggerOnObjectCreate+' => 'Trigger bei Objekterstellung (einer Kindklasse) einer gegebenen Klasse',
));

//
// Class: lnkTriggerAction
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTriggerAction' => 'AKtion/Trigger',
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


?>

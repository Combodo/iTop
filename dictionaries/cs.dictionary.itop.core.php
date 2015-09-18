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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Core:DeletedObjectLabel' => '%1s (odstraněn)',
    'Core:DeletedObjectTip' => 'Objekt byl odstraněn %1$s (%2$s)',

    'Core:UnknownObjectLabel' => 'Objekt nenalezen (třída: %1$s, id: %2$d)',
    'Core:UnknownObjectTip' => 'Objekt nemohl být nalezen. Je možné, že byl odstraněn před nějakou dobou a protokol byl mezitím vyčištěn.',

    'Core:AttributeLinkedSet' => 'Pole objektů',
    'Core:AttributeLinkedSet+' => 'Jakékoli objekty stejné třídy, nebo podtřídy',

    'Core:AttributeLinkedSetIndirect' => 'Pole objektů (N-N)',
    'Core:AttributeLinkedSetIndirect+' => 'Jakékoli objekty stejné třídy, nebo podtřídy',

    'Core:AttributeInteger' => 'Celé číslo (integer)',
    'Core:AttributeInteger+' => 'Celé číslo (může být záporné)',

    'Core:AttributeDecimal' => 'Desetinné číslo (decimal)',
    'Core:AttributeDecimal+' => 'Desetinné číslo (může být záporné)',

    'Core:AttributeBoolean' => 'Boolean',
    'Core:AttributeBoolean+' => 'Boolean',

    'Core:AttributeString' => 'Řetězec (string)',
    'Core:AttributeString+' => 'Alfanumerický řetězec',

    'Core:AttributeClass' => 'Třída (class)',
    'Core:AttributeClass+' => 'Třída (class)',

    'Core:AttributeApplicationLanguage' => 'Jazyk',
    'Core:AttributeApplicationLanguage+' => 'Jazyk a země (CS CZ)',

    'Core:AttributeFinalClass' => 'Třída (auto)',
    'Core:AttributeFinalClass+' => 'Skutečná třída objektu (automaticky vytvořeno jádrem)',

    'Core:AttributePassword' => 'Heslo',
    'Core:AttributePassword+' => 'Heslo k externímu zařízení',

    'Core:AttributeEncryptedString' => 'Šifrovaný řetězec',
    'Core:AttributeEncryptedString+' => 'Řetězec šifrovaný lokálním klíčem',

    'Core:AttributeText' => 'Text',
    'Core:AttributeText+' => 'Víceřádkový řetězec znaků',

    'Core:AttributeHTML' => 'HTML',
    'Core:AttributeHTML+' => 'HTML řetězec',

    'Core:AttributeEmailAddress' => 'Emailová addresa',
    'Core:AttributeEmailAddress+' => 'Emailová addresa',

    'Core:AttributeIPAddress' => 'IP adresa',
    'Core:AttributeIPAddress+' => 'IP adresa',

    'Core:AttributeOQL' => 'OQL',
    'Core:AttributeOQL+' => 'Výraz v jazyce OQL',

    'Core:AttributeEnum' => 'Enum',
    'Core:AttributeEnum+' => 'Seznam předdefinovaných alfanumerických řetězců',

    'Core:AttributeTemplateString' => 'Šablona - řetězec',
    'Core:AttributeTemplateString+' => 'Řetězec obsahující zástupné symboly',

    'Core:AttributeTemplateText' => 'Šablona - text',
    'Core:AttributeTemplateText+' => 'Text obsahující zástupné symboly',

    'Core:AttributeTemplateHTML' => 'Šablona - HTML',
    'Core:AttributeTemplateHTML+' => 'HTML obsahující zástupné symboly',

    'Core:AttributeDateTime' => 'Datum a čas',
    'Core:AttributeDateTime+' => 'Datum a čas (rok-měsíc-den hh:mm:ss)',
    'Core:AttributeDateTime?SmartSearch' => '
<p>
	Formát data:<br/>
	<b>yyyy-mm-dd hh:mm:ss</b><br/>
	Například: 2011-07-19 18:40:00
</p>
<p>
Operátory:<br/>
	<b>&gt;</b><em>datum</em><br/>
	<b>&lt;</b><em>datum</em><br/>
	<b>[</b><em>datum</em>,<em>datum</em><b>]</b>
</p>
<p>
Je-li čas vynechán, bude nastaveno 00:00:00
</p>',

    'Core:AttributeDate' => 'Datum',
    'Core:AttributeDate+' => 'Datum (rok-měsíc-den)',
    'Core:AttributeDate?SmartSearch' => '
<p>
	Formát data:<br/>
	<b>yyyy-mm-dd</b><br/>
	Například: 2011-07-19
</p>
<p>
Operátory:<br/>
	<b>&gt;</b><em>datum</em><br/>
	<b>&lt;</b><em>datum</em><br/>
	<b>[</b><em>datum</em>,<em>datum</em><b>]</b>
</p>',

    'Core:AttributeDeadline' => 'Uzávěrka',
    'Core:AttributeDeadline+' => 'Datum, zobrazeno relativně k aktuálnímu času',

    'Core:AttributeExternalKey' => 'Externí klíč',
    'Core:AttributeExternalKey+' => 'Externí (cizí) klíč',

    'Core:AttributeHierarchicalKey' => 'Hierarchický klíč',
    'Core:AttributeHierarchicalKey+' => 'Externí (cizí) klíč nadřízené položky',

    'Core:AttributeExternalField' => 'Externí pole',
    'Core:AttributeExternalField+' => 'Pole mapované na externí klíč',

    'Core:AttributeURL' => 'URL',
    'Core:AttributeURL+' => 'Absolutní nebo relativní URL jako textový řetězec',

    'Core:AttributeBlob' => 'Blob',
    'Core:AttributeBlob+' => 'Jakýkoli binární obsah (dokument)',

    'Core:AttributeOneWayPassword' => 'Jednosměrně šifrované heslo',
    'Core:AttributeOneWayPassword+' => '',

    'Core:AttributeTable' => 'Tabulka',
    'Core:AttributeTable+' => 'Dvourozměrné indexované pole',

    'Core:AttributePropertySet' => 'Vlastnosti',
    'Core:AttributePropertySet+' => 'Seznam vlastností bez typu (název a hodnota)',

    'Core:AttributeFriendlyName' => 'Popisný název',
    'Core:AttributeFriendlyName+' => 'Atribut vyplněný automaticky; popisný název je složen z několika dalších atributů',

    'Core:FriendlyName-Label' => 'Popisný název',
    'Core:FriendlyName-Description' => 'Popisný název',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChange' => 'Změna',
    'Class:CMDBChange+' => 'Tracking změn',
    'Class:CMDBChange/Attribute:date' => 'datum',
    'Class:CMDBChange/Attribute:date+' => 'datum a čas při kterém byly změny zaznamenány',
    'Class:CMDBChange/Attribute:userinfo' => 'informace',
    'Class:CMDBChange/Attribute:userinfo+' => 'informace definované zadavatelem',
));

//
// Class: CMDBChangeOp
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOp' => 'Change Operation',
    'Class:CMDBChangeOp+' => 'Tracking provozu změn',
    'Class:CMDBChangeOp/Attribute:change' => 'změna',
    'Class:CMDBChangeOp/Attribute:change+' => 'změna',
    'Class:CMDBChangeOp/Attribute:date' => 'datum',
    'Class:CMDBChangeOp/Attribute:date+' => 'datum a čas změny',
    'Class:CMDBChangeOp/Attribute:userinfo' => 'uživatel',
    'Class:CMDBChangeOp/Attribute:userinfo+' => 'kdo provedl tuto změnu',
    'Class:CMDBChangeOp/Attribute:objclass' => 'třída objektů',
    'Class:CMDBChangeOp/Attribute:objclass+' => 'třída objektů',
    'Class:CMDBChangeOp/Attribute:objkey' => 'ID objektu',
    'Class:CMDBChangeOp/Attribute:objkey+' => 'ID objektu',
    'Class:CMDBChangeOp/Attribute:finalclass' => 'typ',
    'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpCreate' => 'vytvoření objektu',
    'Class:CMDBChangeOpCreate+' => 'Tracking vytvoření objektu',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpDelete' => 'odstranění objektu',
    'Class:CMDBChangeOpDelete+' => 'Tracking odstranění objektu',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpSetAttribute' => 'změna objektu',
    'Class:CMDBChangeOpSetAttribute+' => 'Tracking úprav objektu',
    'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Atribut',
    'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'kód upravené vlastnosti',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpSetAttributeScalar' => 'změna vlastnosti objektu',
    'Class:CMDBChangeOpSetAttributeScalar+' => 'Záznam změny objektu',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Předchozí hodnota',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nová hodnota',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Change:ObjectCreated' => 'Objekt vytvořen',
    'Change:ObjectDeleted' => 'Objekt odstraněn',
    'Change:ObjectModified' => 'Objekt upraven',
    'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => 'Atribut %1$s nastaven na hodnotu %2$s (předchozí hodnota: %3$s)',
    'Change:AttName_SetTo' => 'Atribut %1$s nastaven na hodnotu %2$s',
    'Change:Text_AppendedTo_AttName' => '%1$s připojen k %2$s',
    'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s upraven, předchozí hodnota: %2$s',
    'Change:AttName_Changed' => '%1$s upraven',
    'Change:AttName_EntryAdded' => '%1$s upraven, přidána nová položka.',
    'Change:LinkSet:Added' => 'přidán %1$s',
    'Change:LinkSet:Removed' => 'odstraněn %1$s',
    'Change:LinkSet:Modified' => 'upraven %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpSetAttributeBlob' => 'změna dat',
    'Class:CMDBChangeOpSetAttributeBlob+' => 'Tracking změny dat',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Předchozí data',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:CMDBChangeOpSetAttributeText' => 'změna textu',
    'Class:CMDBChangeOpSetAttributeText+' => 'Tracking změny textu',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Předchozí data',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '',
));

//
// Class: Event
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Event' => 'Událost',
    'Class:Event+' => '',
    'Class:Event/Attribute:message' => 'Zpráva',
    'Class:Event/Attribute:message+' => 'krátký popis události',
    'Class:Event/Attribute:date' => 'Datum',
    'Class:Event/Attribute:date+' => 'datum a čas při kterém byla událost zaznamenána',
    'Class:Event/Attribute:userinfo' => 'Informace o uživateli',
    'Class:Event/Attribute:userinfo+' => 'identifikace uživatele, který spustil tuto událost',
    'Class:Event/Attribute:finalclass' => 'Typ',
    'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventNotification' => 'Upozornění',
    'Class:EventNotification+' => '',
    'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
    'Class:EventNotification/Attribute:trigger_id+' => '',
    'Class:EventNotification/Attribute:action_id' => 'Akce',
    'Class:EventNotification/Attribute:action_id+' => '',
    'Class:EventNotification/Attribute:object_id' => 'Objekt',
    'Class:EventNotification/Attribute:object_id+' => '',
));

//
// Class: EventNotificationEmail
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventNotificationEmail' => 'Odeslání emailu',
    'Class:EventNotificationEmail+' => '',
    'Class:EventNotificationEmail/Attribute:to' => 'TO',
    'Class:EventNotificationEmail/Attribute:to+' => '',
    'Class:EventNotificationEmail/Attribute:cc' => 'CC',
    'Class:EventNotificationEmail/Attribute:cc+' => '',
    'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
    'Class:EventNotificationEmail/Attribute:bcc+' => '',
    'Class:EventNotificationEmail/Attribute:from' => 'Odesílatel',
    'Class:EventNotificationEmail/Attribute:from+' => '',
    'Class:EventNotificationEmail/Attribute:subject' => 'Předmět',
    'Class:EventNotificationEmail/Attribute:subject+' => '',
    'Class:EventNotificationEmail/Attribute:body' => 'Tělo',
    'Class:EventNotificationEmail/Attribute:body+' => '',
    'Class:EventNotificationEmail/Attribute:attachments' => 'Přílohy',
    'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventIssue' => 'Chyba',
    'Class:EventIssue+' => '',
    'Class:EventIssue/Attribute:issue' => 'Chyba',
    'Class:EventIssue/Attribute:issue+' => '',
    'Class:EventIssue/Attribute:impact' => 'Dopad',
    'Class:EventIssue/Attribute:impact+' => '',
    'Class:EventIssue/Attribute:page' => 'Stránka',
    'Class:EventIssue/Attribute:page+' => '',
    'Class:EventIssue/Attribute:arguments_post' => 'POST argumenty',
    'Class:EventIssue/Attribute:arguments_post+' => '',
    'Class:EventIssue/Attribute:arguments_get' => 'GET argumenty',
    'Class:EventIssue/Attribute:arguments_get+' => '',
    'Class:EventIssue/Attribute:callstack' => 'Zásobník volání',
    'Class:EventIssue/Attribute:callstack+' => '',
    'Class:EventIssue/Attribute:data' => 'Data',
    'Class:EventIssue/Attribute:data+' => '',
));

//
// Class: EventWebService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventWebService' => 'Webservice call',
    'Class:EventWebService+' => '',
    'Class:EventWebService/Attribute:verb' => 'Název operace',
    'Class:EventWebService/Attribute:verb+' => '',
    'Class:EventWebService/Attribute:result' => 'Výsledek',
    'Class:EventWebService/Attribute:result+' => '',
    'Class:EventWebService/Attribute:log_info' => 'Informace',
    'Class:EventWebService/Attribute:log_info+' => '',
    'Class:EventWebService/Attribute:log_warning' => 'Varování',
    'Class:EventWebService/Attribute:log_warning+' => '',
    'Class:EventWebService/Attribute:log_error' => 'Chyby',
    'Class:EventWebService/Attribute:log_error+' => '',
    'Class:EventWebService/Attribute:data' => 'Data',
    'Class:EventWebService/Attribute:data+' => '',
));

//
// Class: EventRestService
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventRestService' => 'Volání REST/JSON',
    'Class:EventRestService+' => 'Stopa REST/JSON volání',
    'Class:EventRestService/Attribute:operation' => 'Operace',
    'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
    'Class:EventRestService/Attribute:version' => 'Verze',
    'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
    'Class:EventRestService/Attribute:json_input' => 'Vstup (data)',
    'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
    'Class:EventRestService/Attribute:code' => 'Kód',
    'Class:EventRestService/Attribute:code+' => 'Result code',
    'Class:EventRestService/Attribute:json_output' => 'Odpověď',
    'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)',
    'Class:EventRestService/Attribute:provider' => 'Provider~~',
    'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation~~',
));

//
// Class: EventLoginUsage
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EventLoginUsage' => 'Použití aplikace',
    'Class:EventLoginUsage+' => '',
    'Class:EventLoginUsage/Attribute:user_id' => 'Login',
    'Class:EventLoginUsage/Attribute:user_id+' => '',
    'Class:EventLoginUsage/Attribute:contact_name' => 'Uživatelské jméno',
    'Class:EventLoginUsage/Attribute:contact_name+' => '',
    'Class:EventLoginUsage/Attribute:contact_email' => 'Emailová adresa uživatele',
    'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Action' => 'Vlastní akce',
    'Class:Action+' => '',
    'Class:Action/Attribute:name' => 'Název',
    'Class:Action/Attribute:name+' => '',
    'Class:Action/Attribute:description' => 'Popis',
    'Class:Action/Attribute:description+' => '',
    'Class:Action/Attribute:status' => 'Stav',
    'Class:Action/Attribute:status+' => '',
    'Class:Action/Attribute:status/Value:test' => 'Testování',
    'Class:Action/Attribute:status/Value:test+' => '',
    'Class:Action/Attribute:status/Value:enabled' => 'V produkci',
    'Class:Action/Attribute:status/Value:enabled+' => '',
    'Class:Action/Attribute:status/Value:disabled' => 'Neaktivní',
    'Class:Action/Attribute:status/Value:disabled+' => '',
    'Class:Action/Attribute:trigger_list' => 'Související triggery',
    'Class:Action/Attribute:trigger_list+' => 'Triggery spojené s touto akcí',
    'Class:Action/Attribute:finalclass' => 'Typ',
    'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ActionNotification' => 'Upozornění',
    'Class:ActionNotification+' => 'Upozornění (abstraktní)',
));

//
// Class: ActionEmail
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ActionEmail' => 'Emailové upozornění',
    'Class:ActionEmail+' => '',
    'Class:ActionEmail/Attribute:test_recipient' => 'Adresát pro test',
    'Class:ActionEmail/Attribute:test_recipient+' => 'Cílová adresa pro případ, kdy je stav nastaven na "Testování"',
    'Class:ActionEmail/Attribute:from' => 'Odesílatel',
    'Class:ActionEmail/Attribute:from+' => '',
    'Class:ActionEmail/Attribute:reply_to' => 'Odpověď na',
    'Class:ActionEmail/Attribute:reply_to+' => '',
    'Class:ActionEmail/Attribute:to' => 'To',
    'Class:ActionEmail/Attribute:to+' => 'Adresát',
    'Class:ActionEmail/Attribute:cc' => 'Cc',
    'Class:ActionEmail/Attribute:cc+' => 'Kopie',
    'Class:ActionEmail/Attribute:bcc' => 'Bcc',
    'Class:ActionEmail/Attribute:bcc+' => 'Slepá kopie',
    'Class:ActionEmail/Attribute:subject' => 'Předmět',
    'Class:ActionEmail/Attribute:subject+' => '',
    'Class:ActionEmail/Attribute:body' => 'Tělo',
    'Class:ActionEmail/Attribute:body+' => 'Obsah zprávy',
    'Class:ActionEmail/Attribute:importance' => 'Důležitost',
    'Class:ActionEmail/Attribute:importance+' => 'Příznak důležitosti',
    'Class:ActionEmail/Attribute:importance/Value:low' => 'nízká',
    'Class:ActionEmail/Attribute:importance/Value:low+' => '',
    'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
    'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
    'Class:ActionEmail/Attribute:importance/Value:high' => 'high',
    'Class:ActionEmail/Attribute:importance/Value:high+' => '',
));

//
// Class: Trigger
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Trigger' => 'Trigger',
    'Class:Trigger+' => '',
    'Class:Trigger/Attribute:description' => 'Popis',
    'Class:Trigger/Attribute:description+' => 'Krátký popis',
    'Class:Trigger/Attribute:action_list' => 'Spouštěné akce',
    'Class:Trigger/Attribute:action_list+' => 'Akce prováděné, když je aktivován trigger',
    'Class:Trigger/Attribute:finalclass' => 'Typ',
    'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnObject' => 'Trigger (závislý na třídě objektů)',
    'Class:TriggerOnObject+' => '',
    'Class:TriggerOnObject/Attribute:target_class' => 'Cílová třída',
    'Class:TriggerOnObject/Attribute:target_class+' => '',
    'Class:TriggerOnObject/Attribute:filter' => 'Filtr',
    'Class:TriggerOnObject/Attribute:filter+' => '',
    'TriggerOnObject:WrongFilterQuery' => 'Špatný filtrační dotaz: %1$s',
    'TriggerOnObject:WrongFilterClass' => 'Filtrační dotaz musí vrátit objekty třídy "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnPortalUpdate' => 'Trigger \'aktualizace přes portál\'',
    'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnStateChange' => 'Trigger \'změna stavu\'',
    'Class:TriggerOnStateChange+' => '',
    'Class:TriggerOnStateChange/Attribute:state' => 'Stav',
    'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnStateEnter' => 'Trigger \'změna stavu na\'',
    'Class:TriggerOnStateEnter+' => '',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnStateLeave' => 'Trigger \'změna stavu z\'',
    'Class:TriggerOnStateLeave+' => '',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnObjectCreate' => 'Trigger \'vytvoření objektu\'',
    'Class:TriggerOnObjectCreate+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TriggerOnThresholdReached' => 'Trigger \'prahová hodnota\'',
    'Class:TriggerOnThresholdReached+' => '',
    'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stopky',
    'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
    'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Prahová hodnota',
    'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkTriggerAction' => 'Spojení (Akce / Trigger)',
    'Class:lnkTriggerAction+' => '',
    'Class:lnkTriggerAction/Attribute:action_id' => 'Akce',
    'Class:lnkTriggerAction/Attribute:action_id+' => '',
    'Class:lnkTriggerAction/Attribute:action_name' => 'Akce',
    'Class:lnkTriggerAction/Attribute:action_name+' => '',
    'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
    'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
    'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
    'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
    'Class:lnkTriggerAction/Attribute:order' => 'Pořadí',
    'Class:lnkTriggerAction/Attribute:order+' => 'Pořadí, v jakém jsou akce vykonány',
));

//
// Synchro Data Source
//
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SynchroDataSource/Attribute:name' => 'Název',
    'Class:SynchroDataSource/Attribute:name+' => '',
    'Class:SynchroDataSource/Attribute:description' => 'Popis',
    'Class:SynchroDataSource/Attribute:status' => 'Stav',
    'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementace',
    'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Zastaralý',
    'Class:SynchroDataSource/Attribute:status/Value:production' => 'V produkci',
    'Class:SynchroDataSource/Attribute:scope_class' => 'Cílová třída',
    'Class:SynchroDataSource/Attribute:user_id' => 'Uživatel',
    'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Kontakt k upozornění',
    'Class:SynchroDataSource/Attribute:notify_contact_id+' => '',
    'Class:SynchroDataSource/Attribute:url_icon' => 'Ikona',
    'Class:SynchroDataSource/Attribute:url_icon+' => 'Hypertextový odkaz na ikonu reprezentující aplikaci, se kterou je iTop synchronizovnán',
    'Class:SynchroDataSource/Attribute:url_application' => 'Aplikace',
    'Class:SynchroDataSource/Attribute:url_application+' => 'Hypertextový odkaz na iTop objekt v externí aplikaci, se kterou je iTop synchronizován (pokud je to relevantní). Možné zástupné symboly: $this->attribute$ a $replica->primary_key$',

    'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Způsob párování',
    'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Použít atributy',
    'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Použít pole primary_key',

    'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Interval mezi dvěma kompletními načteními',
    'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Kompletní načtení všech dat musí proběhnout nejméně tak často, jak je uvedeno zde',

    'Class:SynchroDataSource/Attribute:action_on_zero' => 'Akce pro nula výsledků',
    'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Jakou akci provést, pokud vyhledávání nevrátí žádný objekt?',
    'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Vytvořit nový',
    'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Chyba',
    'Class:SynchroDataSource/Attribute:action_on_one' => 'Akce pro jeden výsledek',
    'Class:SynchroDataSource/Attribute:action_on_one+' => 'Jakou akci provést, pokud vyhledávání vrátí právě jeden objekt?',
    'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Chyba',
    'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Aktualizovat',
    'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Akce pro více výsledků',
    'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Jakou akci provést, pokud vyhledávání vrátí více než jeden objekt?',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Vytvořit nový',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Chyba',
    'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Vzít první (náhodný?)',

    'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Oprávnění uživatelé',
    'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Kdo má oprávnění odstraňovat synchronizované objekty',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Administrátoři',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Kdokoli',
    'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nikdo',

    'Class:SynchroDataSource/Attribute:delete_policy' => 'Pravidla odstraňování',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Odstranit',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignorovat',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Aktualizovat',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Aktualizovat a odstranit',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nikdo',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Pouze administrátoři',
    'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Všichni autorizovaní uživatelé',
    'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Aktualizace',
    'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Formát: field_name:value; ...',
    'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Doba zachování',
    'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'V případě, že je nastaveno pravidlo "Aktualizovat a odstranit", zastaralé objekty budou před smazáním zachovány po tuto dobu',

    'Class:SynchroAttribute/Attribute:update_policy' => 'Pravidla aktualizace',
    'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Uzamčen',
    'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Odemčen',
    'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Zapsat pokud je prázdný',

    'Class:SynchroDataSource/Attribute:database_table_name' => 'Tabulka dat',
    'Class:SynchroDataSource/Attribute:database_table_name+' => 'Název tabulky pro ukládání dat z tohoto zdroje. Bude vytvořen automaticky, pokud necháte pole prázdné.',

    'SynchroDataSource:Description' => 'Popis',
    'SynchroDataSource:Reconciliation' => 'Hledání a párování',
    'SynchroDataSource:Deletion' => 'Pravidla odstraňování',
    'SynchroDataSource:Status' => 'Stav',
    'SynchroDataSource:Information' => 'Informace',
    'SynchroDataSource:Definition' => 'Definice',

    'Core:SynchroAttributes' => 'Atributy',
    'Core:SynchroStatus' => 'Stav',
    'Core:Synchro:ErrorsLabel' => 'Chyby',
    'Core:Synchro:CreatedLabel' => 'Vytvořen',
    'Core:Synchro:ModifiedLabel' => 'Upraven',
    'Core:Synchro:UnchangedLabel' => 'Nezměněn',
    'Core:Synchro:ReconciledErrorsLabel' => 'Chyby',
    'Core:Synchro:ReconciledLabel' => 'Spárovaný',
    'Core:Synchro:ReconciledNewLabel' => 'Vytvořený',
    'Core:SynchroReconcile:Yes' => 'Ano',
    'Core:SynchroReconcile:No' => 'Ne',
    'Core:SynchroUpdate:Yes' => 'Ano',
    'Core:SynchroUpdate:No' => 'Ne',
    'Core:Synchro:LastestStatus' => 'Poslední stav',
    'Core:Synchro:History' => 'Historie synchronizace',
    'Core:Synchro:NeverRun' => 'Tato synchronizace ještě nebyla spuštěna. Žádné záznamy.',
    'Core:Synchro:SynchroEndedOn_Date' => 'Poslední synchronizace skončila %1$s.',
    'Core:Synchro:SynchroRunningStartedOn_Date' => 'Synchronizace která byla spuštěna %1$s stále běží',

    'Menu:DataSources' => 'Zdroje dat pro synchronizaci',
    'Menu:DataSources+' => 'Všechny zdroje dat pro synchronizaci',

    'Core:Synchro:label_repl_ignored' => 'Ignorovaný (%1$s)',
    'Core:Synchro:label_repl_disappeared' => 'Chybí (%1$s)',
    'Core:Synchro:label_repl_existing' => 'Existující (%1$s)',
    'Core:Synchro:label_repl_new' => 'Nový (%1$s)',
    'Core:Synchro:label_obj_deleted' => 'Odstraněný (%1$s)',
    'Core:Synchro:label_obj_obsoleted' => 'Zastaralý (%1$s)',
    'Core:Synchro:label_obj_disappeared_errors' => 'Chyby (%1$s)',
    'Core:Synchro:label_obj_disappeared_no_action' => 'Žádná akce (%1$s)',
    'Core:Synchro:label_obj_unchanged' => 'Nezměněný (%1$s)',
    'Core:Synchro:label_obj_updated' => 'Aktualizovaný (%1$s)',
    'Core:Synchro:label_obj_updated_errors' => 'Chyby (%1$s)',
    'Core:Synchro:label_obj_new_unchanged' => 'Nezměněný (%1$s)',
    'Core:Synchro:label_obj_new_updated' => 'Aktualizovaný (%1$s)',
    'Core:Synchro:label_obj_created' => 'Vytvořený (%1$s)',
    'Core:Synchro:label_obj_new_errors' => 'Chyby (%1$s)',

    'Core:SynchroLogTitle' => '%1$s - %2$s',
    'Core:Synchro:Nb_Replica' => 'Zpracovaných replik: %1$s',
    'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
    'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Musí být uveden alespoň jeden klíč pro párování, nebo musí být vybrán způsob párování "primary_key".',
    'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Musí být nastavena doba uchování, protože objekty mají být odstraněny poté, co budou označeny jako zastaralé',
    'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Zastaralé objekty mají být aktualizovány, ale žádná aktualizace není uvedena.',
    'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Tabulka %1$s již v databázi existuje. Použijte prosím jiný název.',
    'Core:SynchroReplica:PublicData' => 'Veřejná data',
    'Core:SynchroReplica:PrivateDetails' => 'Interní informace',
    'Core:SynchroReplica:BackToDataSource' => 'Zpět na podrobnosti o zdroji dat: %1$s',
    'Core:SynchroReplica:ListOfReplicas' => 'Seznam replik',
    'Core:SynchroAttExtKey:ReconciliationById' => 'id (primární klíč)',
    'Core:SynchroAtt:attcode' => 'Atribut',
    'Core:SynchroAtt:attcode+' => '',
    'Core:SynchroAtt:reconciliation' => 'Párování ?',
    'Core:SynchroAtt:reconciliation+' => '',
    'Core:SynchroAtt:update' => 'Aktualizovat ?',
    'Core:SynchroAtt:update+' => '',
    'Core:SynchroAtt:update_policy' => 'Pravidla aktualizace',
    'Core:SynchroAtt:update_policy+' => '',
    'Core:SynchroAtt:reconciliation_attcode' => 'Klíč pro párování',
    'Core:SynchroAtt:reconciliation_attcode+' => 'Kód atributu pro externí klíč párování',
    'Core:SyncDataExchangeComment' => '(Synchronizace dat)',
    'Core:Synchro:ListOfDataSources' => 'Seznam zdrojů dat:',
    'Core:Synchro:LastSynchro' => 'Poslední synchronizace:',
    'Core:Synchro:ThisObjectIsSynchronized' => 'Tento objekt je synchronizován s externím zdrojem dat',
    'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Tento objekt byl <strong>vytvořen</strong> externím zdrojem dat %1$s',
    'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Tento objekt <strong>může být odstraněn</strong> externím zdrojem dat %1$s',
    'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => '<strong>Nemůžete odstranit tento objekt</strong>, protože je vlastněn externím zdrojem dat %1$s',
    'TitleSynchroExecution' => 'Provádění synchronizace',
    'Class:SynchroDataSource:DataTable' => 'Databázová tabulka: %1$s',
    'Core:SyncDataSourceObsolete' => 'Zdroj dat je označený jako zastaralý. Operace byla zrušena.',
    'Core:SyncDataSourceAccessRestriction' => 'Pouze administrátoři nebo uživatelé uvedení ve zdroji dat mohou provádět tuto operaci. Operace byla zrušena.',
    'Core:SyncTooManyMissingReplicas' => 'Všechny záznamy nebyly nějakou chvíli aktualizovány, všechny objekty mohou být smazány. Zkontrolujte prosím funkčnost synchronizace. Operace byla zrušena.',
    'Core:SyncSplitModeCLIOnly' => 'Synchronizace může být provádena v blocích pouze při použití modulu CLI',
    'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replik, %2$s chyb, %3$s varování.',
    'Core:SynchroReplica:TargetObject' => 'Synchronizované objekty: %1$s',
    'Class:AsyncSendEmail' => 'Email (asynchronní)',
    'Class:AsyncSendEmail/Attribute:to' => 'Adresát',
    'Class:AsyncSendEmail/Attribute:subject' => 'Předmět',
    'Class:AsyncSendEmail/Attribute:body' => 'Tělo',
    'Class:AsyncSendEmail/Attribute:header' => 'Hlavička',
    'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Šifrované heslo',
    'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Předchozí hodnota',
    'Class:CMDBChangeOpSetAttributeEncrypted' => 'Šifrované pole',
    'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Předchozí hodnota',
    'Class:CMDBChangeOpSetAttributeCaseLog' => 'Protokol událostí',
    'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Poslední záznam',
    'Class:SynchroDataSource' => 'Zdroje dat pro synchronizaci',
    'Class:SynchroDataSource/Attribute:scope_restriction' => 'Omezení rozsahu',
    'Class:SynchroDataSource/Attribute:attribute_list' => 'Seznam atributů',
    'Class:SynchroAttribute' => 'Atribut pro synchronizaci',
    'Class:SynchroAttribute/Attribute:sync_source_id' => 'Zdroj dat pro synchronizaci',
    'Class:SynchroAttribute/Attribute:attcode' => 'Kód atributu',
    'Class:SynchroAttribute/Attribute:update' => 'Aktualizace',
    'Class:SynchroAttribute/Attribute:reconcile' => 'Párování',
    'Class:SynchroAttribute/Attribute:finalclass' => 'Třída',
    'Class:SynchroAttExtKey' => 'Atribut synchronizace (externí klíč)',
    'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Atribut pro párování',
    'Class:SynchroAttLinkSet' => 'Atribut synchronizace (Linkset)',
    'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Oddělovač řádků',
    'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Oddělovač atributů',
    'Class:SynchroLog' => 'Protokol synchronizací',
    'Class:SynchroLog/Attribute:sync_source_id' => 'Zdroj dat pro synchronizaci',
    'Class:SynchroLog/Attribute:start_date' => 'Datum zahájení',
    'Class:SynchroLog/Attribute:end_date' => 'Datum ukončení',
    'Class:SynchroLog/Attribute:status' => 'Stav',
    'Class:SynchroLog/Attribute:status/Value:completed' => 'Dokončeno',
    'Class:SynchroLog/Attribute:status/Value:error' => 'Chyba',
    'Class:SynchroLog/Attribute:status/Value:running' => 'Stále běží',
    'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Počet replik dostupných',
    'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Počet replik celkem',
    'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Podčet odstraněných objektů',
    'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Počet chyb při odstraňování',
    'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Počet zastaralých objektů',
    'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Počet chyb při zastarávání',
    'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Počet vytvořených objektů',
    'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Počet chyb při vytváření',
    'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Počet aktualizovaných objektů',
    'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Počet chyb při aktualizaci',
    'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Počet chyb při sladění',
    'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Počet zmizelých replik',
    'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Počet aktualizovaných objektů',
    'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Počet nezměněných objektů',
    'Class:SynchroLog/Attribute:last_error' => 'Poslední chyba',
    'Class:SynchroLog/Attribute:traces' => 'Stopy',
    'Class:SynchroReplica' => 'Synchronizace replik',
    'Class:SynchroReplica/Attribute:sync_source_id' => 'Zdroj dat pro synchronizaci',
    'Class:SynchroReplica/Attribute:dest_id' => 'Cílový objekt (ID)',
    'Class:SynchroReplica/Attribute:dest_class' => 'Cílový typ',
    'Class:SynchroReplica/Attribute:status_last_seen' => 'Naposledy viděn',
    'Class:SynchroReplica/Attribute:status' => 'Stav',
    'Class:SynchroReplica/Attribute:status/Value:modified' => 'Upraven',
    'Class:SynchroReplica/Attribute:status/Value:new' => 'Nový',
    'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Zastaralý',
    'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Sirotek',
    'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronizovaný',
    'Class:SynchroReplica/Attribute:status_dest_creator' => 'Generovaný objekt?',
    'Class:SynchroReplica/Attribute:status_last_error' => 'Poslední chyba',
    'Class:SynchroReplica/Attribute:status_last_warning' => 'Poslední varování',
    'Class:SynchroReplica/Attribute:info_creation_date' => 'Datum vytvoření',
    'Class:SynchroReplica/Attribute:info_last_modified' => 'Datum poslední úpravy',
    'Class:appUserPreferences' => 'Uživatelské předvolby',
    'Class:appUserPreferences/Attribute:userid' => 'Uživatel',
    'Class:appUserPreferences/Attribute:preferences' => 'Předvolby',
    'Core:ExecProcess:Code1' => 'Chybní příkaz, nebo příkaz skončil s chybou (např chybný název skriptu)',
    'Core:ExecProcess:Code255' => 'PHP Error (parsing/runtime)',

    // Attribute Duration
    'Core:Duration_Seconds' => '%1$ds',
    'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
    'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
    'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

    // Explain working time computing
    'Core:ExplainWTC:ElapsedTime' => 'Uplynulý čas ("%1$s")',
    'Core:ExplainWTC:StopWatch-TimeSpent' => 'Čas strávený na "%1$s"',
    'Core:ExplainWTC:StopWatch-Deadline' => 'Uzávěrka pro "%1$s" v %2$d%%',

    // Bulk export
    'Core:BulkExport:MissingParameter_Param' => 'Chybějící parametr "%1$s"',
    'Core:BulkExport:InvalidParameter_Query' => 'Chybná hodnota parametru "query". Neznám žádný záznam odpovídající následujícímu id: "%1$s".',
    'Core:BulkExport:ExportFormatPrompt' => 'Formát exportu:',
    'Core:BulkExportOf_Class' => '%1$s_export',
    'Core:BulkExport:ClickHereToDownload_FileName' => 'Klikněte pro stažení souboru %1$s',
    'Core:BulkExport:ExportResult' => 'Výsledek exportu:',
    'Core:BulkExport:RetrievingData' => 'Zízkávám data...',
    'Core:BulkExport:HTMLFormat' => 'HTML stránka (*.html)',
    'Core:BulkExport:CSVFormat' => 'Hodnoty oddělené čárkami (*.csv)',
    'Core:BulkExport:XLSXFormat' => 'Excel 2007 a novější (*.xlsx)',
    'Core:BulkExport:PDFFormat' => 'PDF dokument (*.pdf)',
    'Core:BulkExport:DragAndDropHelp' => 'Přesuňte sloupce uchopením za jejich hlavičku. Náhled prvních %1$s řádků. Celkový počet řádků k exportu: %2$s.',
    'Core:BulkExport:EmptyPreview' => 'Vyberte sloupce k exportu ze seznamu',
    'Core:BulkExport:ColumnsOrder' => 'Pořadí sloupců',
    'Core:BulkExport:AvailableColumnsFrom_Class' => 'Dostupné sloupce pro třídu %1$s',
    'Core:BulkExport:NoFieldSelected' => 'Označte alespoň jeden sloupec k exportu',
    'Core:BulkExport:CheckAll' => 'Označit vše',
    'Core:BulkExport:UncheckAll' => 'Odznačit vše',
    'Core:BulkExport:ExportCancelledByUser' => 'Export přerušen uživatelem',
    'Core:BulkExport:CSVOptions' => 'Možnosti CSV',
    'Core:BulkExport:CSVLocalization' => 'Lokalizace',
    'Core:BulkExport:PDFOptions' => 'Možnosti PDF',
    'Core:BulkExport:PDFPageSize' => 'Formát stránky:',
    'Core:BulkExport:PageSize-A4' => 'A4',
    'Core:BulkExport:PageSize-A3' => 'A3',
    'Core:BulkExport:PageSize-Letter' => 'Letter',
    'Core:BulkExport:PDFPageOrientation' => 'Orientace stránky:',
    'Core:BulkExport:PageOrientation-L' => 'Na šířku',
    'Core:BulkExport:PageOrientation-P' => 'Na výšku',
    'Core:BulkExport:XMLFormat' => 'XML soubor (*.xml)',
    'Core:BulkExport:XMLOptions' => 'Možnosti XML',
    'Core:BulkExport:SpreadsheetFormat' => 'HTML tabulka (*.html)',
    'Core:BulkExport:SpreadsheetOptions' => 'Možnosti tabulky',
    'Core:BulkExport:OptionNoLocalize' => 'Nepřekládat hodnoty číselníků',
    'Core:BulkExport:OptionLinkSets' => 'Zahrnout odkazované objekty',
    'Core:BulkExport:ScopeDefinition' => 'Definice objektů k exportu',
    'Core:BulkExportLabelOQLExpression' => 'Dotaz OQL:',
    'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
    'Core:BulkExportMessageEmptyOQL' => 'Zadejte platný OQL dotaz.',
    'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.~~',
    'Core:BulkExportQueryPlaceholder' => 'Zde zadejte OQL dotaz...',
    'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.~~',
    'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.~~',
));

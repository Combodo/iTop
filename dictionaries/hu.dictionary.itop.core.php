<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Core:DeletedObjectLabel' => '%1s (törölve)',
	'Core:DeletedObjectTip'   => 'Az objektum törölve %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Objektum nem található. osztály: %1$s, id: %2$d)',
	'Core:UnknownObjectTip'   => 'Az objektum nem található. Korábban törölték, a napló is ürítve lett',

	'Core:UniquenessDefaultError' => 'Egyediség szabály \'%1$s\' hiba',
	'Core:CheckConsistencyError'  => 'A következetességi szabály nincs alkalmazva: %1$s',
	'Core:CheckValueError'        => 'Váratlan érték az attribútumhoz \'%1$s\' (%2$s) : %3$s',

	'Core:AttributeLinkedSet'  => 'Objektum tömbök',
	'Core:AttributeLinkedSet+' => '',

	'Core:AttributeLinkedSetDuplicatesFound' => 'Ismétlődés a \'%1$s\' mezőben : %2$s',

	'Core:AttributeDashboard'  => 'Vezérlőpult',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber'  => 'Telefonszám',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => 'Avulási dátum',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => 'Címkék listája',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'hozzáadás',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s a %3$s -ból)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s gyermekosztályokból)',

	'Core:AttributeCaseLog' => 'Eseménynapló',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => 'Számított enum',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => 'Objektum tömbök (N-N)',
	'Core:AttributeLinkedSetIndirect+' => '',

	'Core:AttributeInteger' => 'Egész szám',
	'Core:AttributeInteger+' => '',

	'Core:AttributeDecimal' => 'Decimális',
	'Core:AttributeDecimal+' => '',

	'Core:AttributeBoolean' => 'Logikai',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Igen',
	'Core:AttributeBoolean/Value:no' => 'Nem',

	'Core:AttributeArchiveFlag' => 'Archív jel',
	'Core:AttributeArchiveFlag/Value:yes' => 'Igen',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Ez az objektum csak archív módban látható',
	'Core:AttributeArchiveFlag/Value:no' => 'Nem',
	'Core:AttributeArchiveFlag/Label' => 'Archivált',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archív dátum',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Avulás jel',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Igen',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Ez az objektum ki van zárva a hatáselemzésből és a keresésből',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Nem',
	'Core:AttributeObsolescenceFlag/Label' => 'Elavult',
	'Core:AttributeObsolescenceFlag/Label+' => 'Egyéb attribútumok alapján dinamikusan kiszámított',
	'Core:AttributeObsolescenceDate/Label' => 'Elavulás dátuma',
	'Core:AttributeObsolescenceDate/Label+' => '',

	'Core:AttributeString' => 'String',
	'Core:AttributeString+' => '',

	'Core:AttributeClass' => 'Osztály',
	'Core:AttributeClass+' => '',

	'Core:AttributeApplicationLanguage' => 'Alkalmazás nyelve',
	'Core:AttributeApplicationLanguage+' => '',

	'Core:AttributeFinalClass' => 'Osztály (auto)',
	'Core:AttributeFinalClass+' => '',

	'Core:AttributePassword' => 'Jelszó',
	'Core:AttributePassword+' => '',

	'Core:AttributeEncryptedString' => 'Titkosított string',
	'Core:AttributeEncryptedString+' => '',
	'Core:AttributeEncryptUnknownLibrary' => 'A megadott(%1$s) titkosító könyvtár ismeretlen',
	'Core:AttributeEncryptFailedToDecrypt' => '** dekódolás hiba **',

	'Core:AttributeText' => 'Szöveg',
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

	'Core:AttributeDateTime' => 'Dátum/idő',
	'Core:AttributeDateTime+' => '',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Dátum formátum:<br/>
	<b>%1$s</b><br/>
	Példa: %2$s
</p>
<p>
Operátorok:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
If the time is omitted, it defaults to 00:00:00
</p>~~',

	'Core:AttributeDate' => 'Dátum',
	'Core:AttributeDate+' => '',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Dátum formátum:<br/>3
	<b>%1$s</b><br/>
	Példa: %2$s
</p>
<p>
Operátorok:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>~~',

	'Core:AttributeDeadline' => 'Határidő',
	'Core:AttributeDeadline+' => '',

	'Core:AttributeExternalKey' => 'Külső kulcs',
	'Core:AttributeExternalKey+' => '',

	'Core:AttributeHierarchicalKey' => 'Hierarchikus kulcs',
	'Core:AttributeHierarchicalKey+' => 'Külső (vagy idegen) kulcs a szülőhöz',

	'Core:AttributeExternalField' => 'Külső mező',
	'Core:AttributeExternalField+' => '',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '',

	'Core:AttributeOneWayPassword' => 'Egyszeri jelszó',
	'Core:AttributeOneWayPassword+' => '',

	'Core:AttributeTable' => 'Tábla',
	'Core:AttributeTable+' => '',

	'Core:AttributePropertySet' => 'Tulajdonságok',
	'Core:AttributePropertySet+' => '',

	'Core:AttributeFriendlyName' => 'Egyszerű név',
	'Core:AttributeFriendlyName+' => '',

	'Core:FriendlyName-Label' => 'Egyszerű név',
	'Core:FriendlyName-Description' => 'Egyszerű név',

	'Core:AttributeTag' => 'Címkék',
	'Core:AttributeTag+' => '',
	
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Telepítés',
	'Core:Context=GUI:Console' => 'Konzol',
	'Core:Context=CRON' => 'cron',
	'Core:Context=GUI:Portal' => 'Portál',
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
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'Felhasználói interakció a grafikus felületen',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV import script',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'CSV import a grafikus felületen',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email feldolgozás',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Synchro. adatforrás',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webszolgáltatás',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webszolgáltatás',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'Bővítmény',
));

//
// Class: CMDBChangeOp
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOp' => 'Változtatás művelet',
	'Class:CMDBChangeOp+' => '',
	'Class:CMDBChangeOp/Attribute:change' => 'Változás',
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
	'Change:TwoAttributesChanged' => '%1$s és %2$s szerkesztve',
	'Change:ThreeAttributesChanged' => '%1$s, %2$s és 1 másik szerkesztve',
	'Change:FourOrMoreAttributesChanged' => '%1$s, %2$s és %3$s egyebek mellett szerkesztve',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s új értéke: %2$s (előző értéke: %3$s)',
	'Change:AttName_SetTo' => '%1$s új értéke %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s hozzáfűzve %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s módosítva, előző érték: %2$s',
	'Change:AttName_Changed' => '%1$s módosítva',
	'Change:AttName_EntryAdded' => '%1$s módosítva, új bejegyzés hozzáadva.',
	'Change:State_Changed_NewValue_OldValue' => '%2$s cserélve 1$s -re',
	'Change:LinkSet:Added' => '%1$s hozzáadva',
	'Change:LinkSet:Removed' => '%1$s eltávolítva',
	'Change:LinkSet:Modified' => '%1$s módosítva',
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
	'Class:EventNotificationEmail/Attribute:attachments' => 'Melléklet',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventIssue' => 'Kérelem esemény',
	'Class:EventIssue+' => '',
	'Class:EventIssue/Attribute:issue' => 'Kérelem',
	'Class:EventIssue/Attribute:issue+' => '',
	'Class:EventIssue/Attribute:impact' => 'Hatás',
	'Class:EventIssue/Attribute:impact+' => '',
	'Class:EventIssue/Attribute:page' => 'Oldal',
	'Class:EventIssue/Attribute:page+' => '',
	'Class:EventIssue/Attribute:arguments_post' => 'Kérelem részletei',
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
	'Class:EventWebService/Attribute:verb' => 'Kérelem',
	'Class:EventWebService/Attribute:verb+' => '',
	'Class:EventWebService/Attribute:result' => 'Eredmény',
	'Class:EventWebService/Attribute:result+' => '',
	'Class:EventWebService/Attribute:log_info' => 'Info napló',
	'Class:EventWebService/Attribute:log_info+' => '',
	'Class:EventWebService/Attribute:log_warning' => 'Figyelmeztetés napló',
	'Class:EventWebService/Attribute:log_warning+' => '',
	'Class:EventWebService/Attribute:log_error' => 'Hiba napló',
	'Class:EventWebService/Attribute:log_error+' => '',
	'Class:EventWebService/Attribute:data' => 'Adat',
	'Class:EventWebService/Attribute:data+' => '',
));

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventRestService' => 'REST/JSON hívás',
	'Class:EventRestService+' => 'Trace of a REST/JSON szolgáltatáshívás',
	'Class:EventRestService/Attribute:operation' => 'Művelet',
	'Class:EventRestService/Attribute:operation+' => 'Argumentum \'művelet\'',
	'Class:EventRestService/Attribute:version' => 'Verzió',
	'Class:EventRestService/Attribute:version+' => 'Argumentum \'verzió\'',
	'Class:EventRestService/Attribute:json_input' => 'Bemenet',
	'Class:EventRestService/Attribute:json_input+' => 'Argumentum \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Kód',
	'Class:EventRestService/Attribute:code+' => 'Eredménykód',
	'Class:EventRestService/Attribute:json_output' => 'Válasz',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP válasz (json)',
	'Class:EventRestService/Attribute:provider' => 'Szolgáltató',
	'Class:EventRestService/Attribute:provider+' => 'A várt műveletet megvalósító PHP-osztály',
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
	'Class:EventLoginUsage/Attribute:contact_email' => 'Felhasználó email',
	'Class:EventLoginUsage/Attribute:contact_email+' => '',
));

//
// Class: Action
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Action' => 'Egyedi intézkedések',
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
	'Class:Action/Attribute:trigger_list' => 'Kapcsolódó triggerek',
	'Class:Action/Attribute:trigger_list+' => '',
	'Class:Action/Attribute:finalclass' => 'Típus',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Figyelmeztetés, nincs a művelethez kapcsolódó trigger. Addig nem lesz aktív, amíg legalább 1 nem lesz.',
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
	'Class:ActionEmail/Attribute:status+' => 'Ez a státusz határozza meg, hogy ki kapjon értesítést: csak a teszt címzettje, mindenki (To, cc és Bcc) vagy senki.',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Csak a teszt címzett kap értesítést',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Minden To, Cc és Bcc e-mailről értesítést kap',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'Az email értesítés nem lesz elküldve',
	'Class:ActionEmail/Attribute:test_recipient' => 'Teszt címzett',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'Feladó',
	'Class:ActionEmail/Attribute:from+' => '',
	'Class:ActionEmail/Attribute:from_label' => 'Feladó (címke)',
	'Class:ActionEmail/Attribute:from_label+' => 'A feladó neve bekerül az e-mail fejlécébe.',
	'Class:ActionEmail/Attribute:reply_to' => 'Válaszcím',
	'Class:ActionEmail/Attribute:reply_to+' => '',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Válaszcím (címke)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'A válaszcím megjelenítendő neve az e-mail fejlécébe kerül.',
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
	'Class:Trigger/Attribute:action_list' => 'Kiváltott intézkedés',
	'Class:Trigger/Attribute:action_list+' => '',
	'Class:Trigger/Attribute:finalclass' => 'Típus',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Kontextus',
	'Class:Trigger/Attribute:context+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObject' => 'Trigger (osztály függő)',
	'Class:TriggerOnObject+' => '',
	'Class:TriggerOnObject/Attribute:target_class' => 'Cél osztály',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Szűrő',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Helytelen szűrő lekérdezés: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'A szűrő lekérdezésnek a \\"%1$s\\" osztály objektumait kell mutatnia',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (amikor a portálról frissül)',
	'Class:TriggerOnPortalUpdate+' => 'Trigger egy végfelhasználó frissítésénél a portálon',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateChange' => 'Trigger (állapot változás)',
	'Class:TriggerOnStateChange+' => '',
	'Class:TriggerOnStateChange/Attribute:state' => 'Állapot',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateEnter' => 'Trigger (állapotba belépés)',
	'Class:TriggerOnStateEnter+' => '',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateLeave' => 'Trigger (állapot elhagyás)',
	'Class:TriggerOnStateLeave+' => '',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (objektum létrehozás)',
	'Class:TriggerOnObjectCreate+' => '',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (objektum törlése)',
	'Class:TriggerOnObjectDelete+' => ''
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (objektum törlése)',
	'Class:TriggerOnObjectUpdate+' => '',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Célmezők',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectMention' => 'Trigger (objektumra hivatkozáskor)',
	'Class:TriggerOnObjectMention+' => '',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Hivatkozott szűró',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (küszöbértéknél)',
	'Class:TriggerOnThresholdReached+' => '',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stopperóra',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Küszöbérték',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkTriggerAction' => 'Intézkedés / Trigger',
	'Class:lnkTriggerAction+' => '',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Intézkedés',
	'Class:lnkTriggerAction/Attribute:action_id+' => '',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Intézkedés',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
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
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Értesítési kapcsolattartó',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => '',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Ikonok URL-je',
	'Class:SynchroDataSource/Attribute:url_icon+' => '',
	'Class:SynchroDataSource/Attribute:url_application' => 'Alkalmazások URL-je',
	'Class:SynchroDataSource/Attribute:url_application+' => '',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Egyeztetési szabály',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Teljesen feltöltött intervallum',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => '',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Intézkedés nulla esetén',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Intézkedés egy esetén',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Intézkedés több esetén',
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
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Adattábla',
	'Class:SynchroDataSource/Attribute:database_table_name+' => '',
	'SynchroDataSource:Description' => 'Leírás',
	'SynchroDataSource:Reconciliation' => 'Keresés &amp; egyeztetés',
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
	'Core:Synchro:ReconciledLabel' => 'Egyeztetett',
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
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'A %1$s tábla már létezik az adatbázisban. Használjon más nevet a synchro adattáblához.',
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
	'Core:Synchro:LastSynchro' => 'Utolsó szinkronizáció',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Az objektum szinkronizálva a külső adatforrással.',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Objektum <b>létrehozva</b> a következő adatforrásban: %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Objektum <b>törölhető</b> a következő külső adatforrásból: %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => '<b>Objektum nem törölhető</b> mert egy másik adatforrás (%1$s) tulajdona',
	'TitleSynchroExecution' => 'Szinkronizáció végrehajtás',
	'Class:SynchroDataSource:DataTable' => 'Adatbázis tábla: %1$s',
	'Core:SyncDataSourceObsolete' => 'Az adatforrás elvalultnak van jelölve. Művelet visszavonva.',
	'Core:SyncDataSourceAccessRestriction' => 'Csak az adminisztrátor vagy speciális jogokkal rendelkező felhasználó futtathatja a műveletet. Művelet visszavonva.',
	'Core:SyncTooManyMissingReplicas' => 'Import során az összes másolat elveszett. Az import valóban lefutott? Művelet visszavonva.',
	'Core:SyncSplitModeCLIOnly' => 'A szinkronizálás csak akkor hajtható végre darabokban, ha CLI üzemmódban fut.',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s másolat, %2$s hiba, %3$s figyelmeztetés.',
	'Core:SynchroReplica:TargetObject' => 'Szinkronizált objektumok: %1$s',
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
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Frissítés',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Létrehozás',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Vegye az elsőt',
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
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Lezárt',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Nyitott',
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
	'Class:SynchroLog/Attribute:traces' => 'Nyomkövetés',
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
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Figyelmeztetés',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Létrehozás dátuma',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Utolsó módosítás dátuma',
	'Class:appUserPreferences' => 'Felhasználói beállítások',
	'Class:appUserPreferences/Attribute:userid' => 'Felhasználó',
	'Class:appUserPreferences/Attribute:preferences' => 'Beállítások',
	'Core:ExecProcess:Code1' => 'Rossz parancs vagy hibásan befejezett parancs (pl. rossz script név)',
	'Core:ExecProcess:Code255' => 'PHP hiba (parsing, vagy runtime)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$sds',
	'Core:Duration_Minutes_Seconds' => '%1$sdmin %2$sds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$sdh %2$sdmin %3$sds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$sdh %3$sdmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Eltelt idő (tárolva mint \\"%1$s\\")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Időráfordítás \\"%1$s\\"',
	'Core:ExplainWTC:StopWatch-Deadline' => '\\"%1$s\\" határideje %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Hiányzó paraméter \\"%1$s\\"',
	'Core:BulkExport:InvalidParameter_Query' => 'Érvénytelen érték a paraméterhez \\"query\\". Nincs a lekérdezéshez kifejezésgyűjtemény: \\"%1$s\\".',
	'Core:BulkExport:ExportFormatPrompt' => 'Export formátum:',
	'Core:BulkExportOf_Class' => '%1$s Exportálás',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Kattints a %1$s letöltéséhez',
	'Core:BulkExport:ExportResult' => 'Az exportálás eredménye:',
	'Core:BulkExport:RetrievingData' => 'Adat fogadása...',
	'Core:BulkExport:HTMLFormat' => 'Weboldal (*.html)',
	'Core:BulkExport:CSVFormat' => 'Vesszővel elválasztott (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 vagy újabb (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF Dokumentum (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Húzza az oszlopok fejléceit az oszlopok elrendezéséhez. Preview of %1$s lines. Az exportálás sorainak száma: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Válassza ki az exportálandó oszlopokat a fenti listából.',
	'Core:BulkExport:ColumnsOrder' => 'Oszlopok sorrendje',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'A %1$s oszlopai',
	'Core:BulkExport:NoFieldSelected' => 'Válasszon ki legalább egy exportálandó oszlopot',
	'Core:BulkExport:CheckAll' => 'Összes kijelölése',
	'Core:BulkExport:UncheckAll' => 'Kijelölés megszűntetése',
	'Core:BulkExport:ExportCancelledByUser' => 'Az exportálást a felhasználó megszakította',
	'Core:BulkExport:CSVOptions' => 'CSV beállítások',
	'Core:BulkExport:CSVLocalization' => 'Lokalizáció',
	'Core:BulkExport:PDFOptions' => 'PDF beállítások',
	'Core:BulkExport:PDFPageFormat' => 'Oldalformátum',
	'Core:BulkExport:PDFPageSize' => 'Oldalméret:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter',
	'Core:BulkExport:PDFPageOrientation' => 'Tájolás:',
	'Core:BulkExport:PageOrientation-L' => 'Vízszintes',
	'Core:BulkExport:PageOrientation-P' => 'Függőleges',
	'Core:BulkExport:XMLFormat' => 'XML fájl (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML beállítások',
	'Core:BulkExport:SpreadsheetFormat' => 'Táblázat HTML formátum (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Táblázat beállítások',
	'Core:BulkExport:OptionNoLocalize' => 'Kód exportálása felirat helyett',
	'Core:BulkExport:OptionLinkSets' => 'Foglalja bele a csatolt objektumokat',
	'Core:BulkExport:OptionFormattedText' => 'Szövegformázás megtartása',
	'Core:BulkExport:ScopeDefinition' => 'Exportálandó objektumok meghatározása',
	'Core:BulkExportLabelOQLExpression' => 'OQL lekérdezés:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Kifejezésgyűjtemény bejegyzés lekérdezés:',
	'Core:BulkExportMessageEmptyOQL' => 'Adjon meg egy érvényes OQL lekérdezést.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Kérjük, válasszon ki egy érvényes kifejezésgyűjtemény bejegyzést',
	'Core:BulkExportQueryPlaceholder' => 'Ide írja a OQL lekérdezést...',
	'Core:BulkExportCanRunNonInteractive' => 'Kattintson ide az exportálás nem interaktív módban történő futtatásához.',
	'Core:BulkExportLegacyExport' => 'Kattintson ide az örökölt exportáláshoz.',
	'Core:BulkExport:XLSXOptions' => 'Excel beállítások',
	'Core:BulkExport:TextFormat' => 'HTML jelölést tartalmazó szöveges mezők',
	'Core:BulkExport:DateTimeFormat' => 'Dátum és idő formátum',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Alapértelmezett formátum (%1$s), pl. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Egyéni formátum: %1$s',
	'Core:BulkExport:PDF:PageNumber' => '%1$s oldal',
	'Core:DateTime:Placeholder_d' => 'NN', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'N', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'HH', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'H', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'ÉÉÉÉ', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'ÉÉ', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh~~', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h~~', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh~~', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h~~', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm~~', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM~~', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm~~', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss~~', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Hibás formátum',
	'Core:Validator:Mandatory' => 'Töltse ki ezt a mezőt',
	'Core:Validator:MustBeInteger' => 'Egész számnak kell lennie',
	'Core:Validator:MustSelectOne' => 'Válasszon egyet',
));

//
// Class: TagSetFieldData
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TagSetFieldData' => '%2$s az %1$s osztályhoz',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Kód',
	'Class:TagSetFieldData/Attribute:code+' => 'Belső kód. Legalább 3 alfanumerikus karaktert kell tartalmaznia.',
	'Class:TagSetFieldData/Attribute:label' => 'Felirat',
	'Class:TagSetFieldData/Attribute:label+' => 'Látható felirat',
	'Class:TagSetFieldData/Attribute:description' => 'Leírás',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Címke osztály',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Objektum osztály',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Mezőkód',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'A felhasznált címkék nem törölhetők',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'A címkéknek, kódoknak, feliratoknak egyedinek kell lennie',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'A címkekódoknak 3 és %1$d közé eső alfanumerikus karaktereket kell tartalmaznia',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'A választott címke kód foglalt szót tartalmaz',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'A címkefelirat nem tartalmazhat \'%1$s\' és üres sem lehet',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'A címkekód nem változtatható ha használatban van',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'A címkék "objektum osztálya" nem változtatható',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'A címkék "Attribútum kódja" nem változtatható',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Címkehasználat (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nincs bejegyzés ehhez a címkéhez',
));

//
// Class: DBProperty
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DBProperty' => 'DB tulajdonságai',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Név',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Leírás',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Érték',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Változtatás dátuma',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Megjegyzés változtatása',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:BackgroundTask' => 'Háttérfolyamat',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Osztály név',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Első futás ideje',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Utolsó futás ideje',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Következő futás ideje',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Összes futtatás',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Utolsó futás időtartama',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Legrövidebb futási idő',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Leghosszabb futási idő',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Átlagos futási idő',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Futtatás',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Státusz',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AsyncTask' => 'Aszink. feladat',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Létrehozva',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Elindítva',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Tervezett',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Esemény',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Végső osztály',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Státusz',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Maradék próbálkozások',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Legutóbbi hibakód',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Legutóbbi hiba',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Legutóbbi próbálkozás',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Érvénytelen formátum az "async_task_retries[%1$s]" konfigurációhoz. A következő kulcsokkal rendelkező tömböt vár: %2$s',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Az "async_task_retries[%1$s]" konfigurációjának érvénytelen formátuma: "%2$s" váratlan kulcs. Csak a következő kulcsokat várja: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AbstractResource' => 'Absztrakt erőforrások',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ResourceAdminMenu' => 'Erőforrás Admin Menü',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ResourceRunQueriesMenu' => 'Erőforrás lekérdezések futtatása menü',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ResourceSystemMenu' => 'Erőforrás rendszer menü',
	'Class:ResourceSystemMenu+' => '',
));




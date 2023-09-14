<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Core:DeletedObjectLabel' => '%1$s (törölve)',
	'Core:DeletedObjectTip' => 'A %1$s objektum törölve (%2$s)',
	'Core:UnknownObjectLabel' => 'Objektum nem található (osztály: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Az objektumot nem sikerült megtalálni. Lehet, hogy már törölték egy ideje, és a naplót azóta törölték.',
	'Core:UniquenessDefaultError' => 'Egyediségi szabály %1$s hibás',
	'Core:CheckConsistencyError' => 'A következetességi szabályok be nem tartása: %1$s',
	'Core:CheckValueError' => 'A %1$s (%2$s) attribútum nem várt értéke : %3$s',
	'Core:AttributeLinkedSet' => 'Objektumtömbök',
	'Core:AttributeLinkedSet+' => 'Az azonos osztályba vagy alosztályba tartozó objektumok bármely fajtája',
	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplikátumok a %1$s mezőben : %2$s',
	'Core:AttributeDashboard' => 'Műszerfal',
	'Core:AttributeDashboard+' => '',
	'Core:AttributePhoneNumber' => 'Telefonszám',
	'Core:AttributePhoneNumber+' => '',
	'Core:AttributeObsolescenceDate' => 'Elavulás dátuma',
	'Core:AttributeObsolescenceDate+' => '',
	'Core:AttributeTagSet' => 'Címkelista',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'Kattintson a hozzáadáshoz',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s a %3$s -ból)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s gyermekosztályokból)',
	'Core:AttributeCaseLog' => 'Napló',
	'Core:AttributeCaseLog+' => '',
	'Core:AttributeMetaEnum' => 'Generált enum',
	'Core:AttributeMetaEnum+' => '',
	'Core:AttributeLinkedSetIndirect' => 'Objektumok tömbjei (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Bármilyen objektum [alosztály] ugyanabból az osztályból',
	'Core:AttributeInteger' => 'Egész szám',
	'Core:AttributeInteger+' => 'Numerikus érték (lehet negatív is)',
	'Core:AttributeDecimal' => 'Decimális',
	'Core:AttributeDecimal+' => 'Decimális érték (lehet negatív is)',
	'Core:AttributeBoolean' => 'Logikai',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Igen',
	'Core:AttributeBoolean/Value:no' => 'Nem',
	'Core:AttributeArchiveFlag' => 'Archív jelölő',
	'Core:AttributeArchiveFlag/Value:yes' => 'Igen',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Ez az objektum csak archív módban látható',
	'Core:AttributeArchiveFlag/Value:no' => 'Nem',
	'Core:AttributeArchiveFlag/Label' => 'Archivált',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archiválás dátuma',
	'Core:AttributeArchiveDate/Label+' => '',
	'Core:AttributeObsolescenceFlag' => 'Elavulás jelölő',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Igen',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Ez az objektum ki van zárva a hatáselemzésből, és el van rejtve a keresési eredményekből.',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Nem',
	'Core:AttributeObsolescenceFlag/Label' => 'Elavult',
	'Core:AttributeObsolescenceFlag/Label+' => 'Egyéb attribútumok alapján dinamikusan kiszámított',
	'Core:AttributeObsolescenceDate/Label' => 'Elavulás dátuma',
	'Core:AttributeObsolescenceDate/Label+' => 'Az objektum elavultnak minősítésének hozzávetőleges dátuma',
	'Core:AttributeString' => 'Karakterlánc',
	'Core:AttributeString+' => 'Alfanumerikus karakterlánc',
	'Core:AttributeClass' => 'Osztály',
	'Core:AttributeClass+' => '',
	'Core:AttributeApplicationLanguage' => 'Felhasználó nyelve',
	'Core:AttributeApplicationLanguage+' => 'Nyelv és országkód (HU HU)',
	'Core:AttributeFinalClass' => 'Osztály (automatikus)',
	'Core:AttributeFinalClass+' => 'Az objektum valódi osztálya (a rendszer automatikusan létrehozza)',
	'Core:AttributePassword' => 'Jelszó',
	'Core:AttributePassword+' => 'A külső eszköz jelszava',
	'Core:AttributeEncryptedString' => 'Titkosított karakterlánc',
	'Core:AttributeEncryptedString+' => 'Helyi kulccsal titkosított karakterlánc',
	'Core:AttributeEncryptUnknownLibrary' => 'A megadott (%1$s) titkosítási könyvtár ismeretlen',
	'Core:AttributeEncryptFailedToDecrypt' => '** Titkosítási hiba **',
	'Core:AttributeText' => 'Szöveg',
	'Core:AttributeText+' => 'Többsoros karakterlánc',
	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML karakterlánc',
	'Core:AttributeEmailAddress' => 'Email cím',
	'Core:AttributeEmailAddress+' => 'Email cím',
	'Core:AttributeIPAddress' => 'IP cím',
	'Core:AttributeIPAddress+' => 'IP cím',
	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Objektum lekérdező nyelvi (OQL) kifejezés',
	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Előre definiált alfanumerikus karakterláncok listája',
	'Core:AttributeTemplateString' => 'Karakterlánc sablon',
	'Core:AttributeTemplateString+' => 'Helyőrzőket tartalmazó karakterlánc',
	'Core:AttributeTemplateText' => 'Szövegsablon',
	'Core:AttributeTemplateText+' => 'Helyőrzőket tartalmazó szöveg',
	'Core:AttributeTemplateHTML' => 'HTML sablon',
	'Core:AttributeTemplateHTML+' => 'Helyőrzőket tartalmazó HTML kód',
	'Core:AttributeDateTime' => 'Dátum/idő',
	'Core:AttributeDateTime+' => 'Dátum és idő (Év-hónap-nap óó:pp:mp)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
    Dátumformátum:<br/>
    <b>%1$s</b><br/>
    Példa: %2$s
</p>
<p>
Operátorok:<br/>
    <b>&gt;</b><em>dátum</em><br/>
    <b>&lt;</b><em>dátum</em><br/>
    <b>[</b><em>dátum</em>,<em>dátum</em><b>]</b>
</p>
<p>
Ha az időpontot nem adja meg, akkor az alapértelmezett értéke 00:00:00
</p>',
	'Core:AttributeDate' => 'Dátum',
	'Core:AttributeDate+' => 'Dátum (év-hónap-nap)',
	'Core:AttributeDate?SmartSearch' => '
<p>
    Dátumformátum:<br/>
    <b>%1$s</b><br/>
    Példa: %2$s
</p>
<p>
Operátorok:<br/>
    <b>&gt;</b><em>dátum</em><br/>
    <b>&lt;</b><em>dátum</em><br/>
    <b>[</b><em>dátum</em>,<em>dátum</em><b>]</b>
</p>',
	'Core:AttributeDeadline' => 'Határidő',
	'Core:AttributeDeadline+' => 'Dátum, ami az aktuális időhöz viszonyítva jelenik meg',
	'Core:AttributeExternalKey' => 'Külső kulcs',
	'Core:AttributeExternalKey+' => 'Külső (vagy idegen) kulcs',
	'Core:AttributeHierarchicalKey' => 'Hierarchikus kulcs',
	'Core:AttributeHierarchicalKey+' => 'Külső (vagy idegen) kulcs a szülőhöz',
	'Core:AttributeExternalField' => 'Külső mező',
	'Core:AttributeExternalField+' => 'Külső kulcshoz rendelt mező',
	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Abszolút vagy relatív URL szöveges karakterláncként',
	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Bármilyen bináris tartalom (dokumentum)',
	'Core:AttributeOneWayPassword' => 'Egyirányú jelszó',
	'Core:AttributeOneWayPassword+' => 'Egyirányú titkosított (hashed) jelszó',
	'Core:AttributeTable' => 'Táblázat',
	'Core:AttributeTable+' => 'Indexelt kétdimenziós tömb',
	'Core:AttributePropertySet' => 'Tulajdonságok',
	'Core:AttributePropertySet+' => 'A nem tipizált tulajdonságok listája (név és érték)',
	'Core:AttributeFriendlyName' => 'Barátságos név',
	'Core:AttributeFriendlyName+' => 'Automatikusan létrehozott attribútum ; a barátságos név több attribútum után kerül kiszámításra.',
	'Core:FriendlyName-Label' => 'Név',
	'Core:FriendlyName-Description' => 'Név',
	'Core:AttributeTag' => 'Címkék',
	'Core:AttributeTag+' => '',
	'Core:Context=REST/JSON' => 'REST',
	'Core:Context=Synchro' => 'Synchro',
	'Core:Context=Setup' => 'Setup',
	'Core:Context=GUI:Console' => 'Console',
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
	'Class:CMDBChange+' => 'Változások nyomonkövetése',
	'Class:CMDBChange/Attribute:date' => 'Dátum',
	'Class:CMDBChange/Attribute:date+' => 'A változások rögzítésének dátuma és időpontja',
	'Class:CMDBChange/Attribute:userinfo' => 'Egyéb infó',
	'Class:CMDBChange/Attribute:userinfo+' => 'Kérelmező által meghatározott információk',
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'Felhasználói interakció a grafikus felületen',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV import szkript',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'CSV import a grafikus felületen',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email feldolgozás',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Szinkron adatforrás',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webszolgáltatás',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webszolgáltatás',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'Bővítmény által',
));

//
// Class: CMDBChangeOp
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOp' => 'Változás művelet',
	'Class:CMDBChangeOp+' => 'Egyetlen személy által, egyetlen időpontban, egyetlen tárgyon végrehajtott változtatás.',
	'Class:CMDBChangeOp/Attribute:change' => 'Változás',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'Dátum',
	'Class:CMDBChangeOp/Attribute:date+' => 'A változás dátuma és ideje',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'Felhasználó',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'Aki a változtatást végbevitte',
	'Class:CMDBChangeOp/Attribute:objclass' => 'Objektum osztály',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'Annak az objektumnak az osztályneve, amelyen a változtatás történt',
	'Class:CMDBChangeOp/Attribute:objkey' => 'Objektum azonosító',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'Azon objektum azonosítója amelyen a változtatás történt',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'CMDBChangeOp al-osztály',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'A végrehajtott változtatás típusa',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpCreate' => 'Objektum létrehozás	',
	'Class:CMDBChangeOpCreate+' => 'Objektum létrehozás nyomonkövetése',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpDelete' => 'Objektum törlés',
	'Class:CMDBChangeOpDelete+' => 'Objektum törlés nyomonkövetése',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttribute' => 'Objektum változás',
	'Class:CMDBChangeOpSetAttribute+' => 'Objektumtulajdonságok változáskövetése',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribútum',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'A módosított tulajdonság kódja',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Tulajdonságváltozás',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Objektum skalár tulajdonságok változáskövetése',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Az attribútum korábbi értéke',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Új érték',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'Az attribútum új értéke',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Change:ObjectCreated' => 'Objektum létrehozva',
	'Change:ObjectDeleted' => 'Objektum törölve',
	'Change:ObjectModified' => 'Objektum módosítva',
	'Change:TwoAttributesChanged' => 'Szerkesztve %1$s és %2$s',
	'Change:ThreeAttributesChanged' => 'Szerkesztve %1$s, %2$s és 1 másik',
	'Change:FourOrMoreAttributesChanged' => 'Szerkesztve %1$s, %2$s és %3$s egyéb',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => 'A %1$s beállítva %2$s -re (előző érték: %3$s)',
	'Change:AttName_SetTo' => 'A %1$s beállítva %2$s -re',
	'Change:Text_AppendedTo_AttName' => 'A %1$s hozzáfűzve %2$s -hez',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s módosítva, előző érték: %2$s',
	'Change:AttName_Changed' => '%1$s módosítva',
	'Change:AttName_EntryAdded' => '%1$s módosítva, új bejegyzés hozzáadva: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Változtatva %2$s -ről %1$s -re',
	'Change:LinkSet:Added' => '%1$s hozzáadva',
	'Change:LinkSet:Removed' => '%1$s eltávolítva',
	'Change:LinkSet:Modified' => '%1$s módosítva',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'Adatváltozás',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'Adatváltozás nyomonkövetése',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Előző adat',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'Az attribútum korábbi tartalma',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CMDBChangeOpSetAttributeText' => 'Szövegváltozás',
	'Class:CMDBChangeOpSetAttributeText+' => 'Szövegváltozás nyomonkövetése',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Előző adat',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'Az attribútum korábbi tartalma',
));

//
// Class: Event
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Event' => 'Naplóesemény',
	'Class:Event+' => 'Egy alkalmazás belső esemény',
	'Class:Event/Attribute:message' => 'Üzenet',
	'Class:Event/Attribute:message+' => 'Az esemény rövid leírása',
	'Class:Event/Attribute:date' => 'Dátum',
	'Class:Event/Attribute:date+' => 'A változások rögzítésének dátuma és időpontja',
	'Class:Event/Attribute:userinfo' => 'Felhasználó infó',
	'Class:Event/Attribute:userinfo+' => 'Annak a felhasználónak az azonosítása, aki az eseményt kiváltó műveletet végrehajtotta.',
	'Class:Event/Attribute:finalclass' => 'Esemény al-osztály',
	'Class:Event/Attribute:finalclass+' => 'A végleges osztály neve: a bekövetkezett esemény fajtáját határozza meg.',
));

//
// Class: EventNotification
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventNotification' => 'Értesítési esemény',
	'Class:EventNotification+' => 'Az elküldött értesítések nyomonkövetése',
	'Class:EventNotification/Attribute:trigger_id' => 'Eseményindító',
	'Class:EventNotification/Attribute:trigger_id+' => '',
	'Class:EventNotification/Attribute:action_id' => 'Művelet',
	'Class:EventNotification/Attribute:action_id+' => '',
	'Class:EventNotification/Attribute:object_id' => 'Objektum azonosító',
	'Class:EventNotification/Attribute:object_id+' => 'Objektum azonosítója (eseményindító határozza meg az osztályt ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventNotificationEmail' => 'Email küldés esemény',
	'Class:EventNotificationEmail+' => 'A kiküldött email-ek nyomonkövetése',
	'Class:EventNotificationEmail/Attribute:to' => 'Címzett',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'Másolatot kap',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Titkos másolatot kap',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'Feladó',
	'Class:EventNotificationEmail/Attribute:from+' => 'Az üzenet küldője',
	'Class:EventNotificationEmail/Attribute:subject' => 'Tárgy',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Levéltörzs',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Mellékletek',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventIssue' => 'Probléma esemény',
	'Class:EventIssue+' => 'Egy probléma (figyelmeztetés, hiba, stb. nyomonkövetése)',
	'Class:EventIssue/Attribute:issue' => 'Probléma',
	'Class:EventIssue/Attribute:issue+' => 'Mi történt',
	'Class:EventIssue/Attribute:impact' => 'Hatása',
	'Class:EventIssue/Attribute:impact+' => 'Mik a következmények',
	'Class:EventIssue/Attribute:page' => 'Oldal',
	'Class:EventIssue/Attribute:page+' => 'HTTP belépési pont',
	'Class:EventIssue/Attribute:arguments_post' => 'Kiküldött bizonyítékok',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST bizonyítékok',
	'Class:EventIssue/Attribute:arguments_get' => 'URL bizonyítékok',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET bizonyítékok',
	'Class:EventIssue/Attribute:callstack' => 'Híváscsomag',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Adat',
	'Class:EventIssue/Attribute:data+' => 'További információ',
));

//
// Class: EventWebService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventWebService' => 'Webszolgáltatás esemény',
	'Class:EventWebService+' => 'Webszolgáltatás hívás nyomonkövetése',
	'Class:EventWebService/Attribute:verb' => 'Művelet',
	'Class:EventWebService/Attribute:verb+' => 'A művelet neve',
	'Class:EventWebService/Attribute:result' => 'Eredmény',
	'Class:EventWebService/Attribute:result+' => 'Általánosságban siker/hiba',
	'Class:EventWebService/Attribute:log_info' => 'Infó napló',
	'Class:EventWebService/Attribute:log_info+' => 'A kapott eredmények naplója',
	'Class:EventWebService/Attribute:log_warning' => 'Figyelmeztetés napló',
	'Class:EventWebService/Attribute:log_warning+' => 'A kapott figyelmeztetések naplója',
	'Class:EventWebService/Attribute:log_error' => 'Hibanapló',
	'Class:EventWebService/Attribute:log_error+' => 'A kapott hibák naplója ',
	'Class:EventWebService/Attribute:data' => 'Adat',
	'Class:EventWebService/Attribute:data+' => 'A kapott adatok',
));

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventRestService' => 'REST/JSON hívás',
	'Class:EventRestService+' => 'REST/JSON szolgáltatáshívás nyomonkövetése',
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
	'Class:EventRestService/Attribute:provider+' => 'A várt műveletet végrehajtó PHP osztály',
));

//
// Class: EventLoginUsage
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:EventLoginUsage' => 'Belépések',
	'Class:EventLoginUsage+' => 'Kapcsolódások az alkalmazáshoz',
	'Class:EventLoginUsage/Attribute:user_id' => 'Felhasználónév',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Kapcsolattartó név',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Kapcsolattartó email cím',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'A felhasználó email címe',
));

//
// Class: Action
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Action' => 'Egyéni művelet',
	'Class:Action+' => 'A felhasználó által meghatározott művelet',
	'Class:Action/ComplementaryName' => '%1$s: %2$s~~',
	'Class:Action/Attribute:name' => 'Név',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Leírás',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Állapot',
	'Class:Action/Attribute:status+' => 'Ez az állapot határozza meg az akció viselkedését',
	'Class:Action/Attribute:status/Value:test' => 'Tesztelés alatt',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'Bevezetve',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inaktív',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Kapcsolódó eseményindítók',
	'Class:Action/Attribute:trigger_list+' => 'Eseményindítók amik ehhez a művelethez vannak rendelve',
	'Class:Action/Attribute:finalclass' => 'Művelet al-osztály',
	'Class:Action/Attribute:finalclass+' => 'A végleges osztály neve',
	'Action:WarningNoTriggerLinked' => 'Figyelmeztetés, nincs a művelethez kapcsolódó eseményindító. Addig nem lesz aktív, amíg legalább 1 nem lesz.',
));

//
// Class: ActionNotification
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ActionNotification' => 'Értesítés',
	'Class:ActionNotification+' => 'Értesítés (absztrakt)',
));

//
// Class: ActionEmail
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ActionEmail' => 'Email értesítés',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => 'Ez az állapot határozza meg, hogy ki kapjon értesítést: csak a teszt címzettje, mindenki (Címzett, cc és Bcc) vagy senki.',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Csak a teszteléshez használt címzett kap értesítést',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Minden Címzett, Cc és Bcc email értesítést fog kapni',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'Az email értesítés nem lesz elküldve',
	'Class:ActionEmail/Attribute:test_recipient' => 'Teszt címzett',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Az értesítés tesztelésekor használt cél email cím',
	'Class:ActionEmail/Attribute:from' => 'Feladó (email)',
	'Class:ActionEmail/Attribute:from+' => 'A feladó email címe ami bekerül az email fejlécébe.',
	'Class:ActionEmail/Attribute:from_label' => 'Feladó (címke)',
	'Class:ActionEmail/Attribute:from_label+' => 'A feladó neve ami bekerül az email fejlécébe.',
	'Class:ActionEmail/Attribute:reply_to' => 'Válaszcím (email)',
	'Class:ActionEmail/Attribute:reply_to+' => 'A válasz az email cím ami bekerül az email fejlécébe.',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Válaszadó (címke)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'A válaszadó a megjelenített neve ami az email fejlécébe kerül.',
	'Class:ActionEmail/Attribute:to' => 'Címzett',
	'Class:ActionEmail/Attribute:to+' => 'Az email címzettje',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Másolat',
	'Class:ActionEmail/Attribute:bcc' => 'Bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Titkos másolat',
	'Class:ActionEmail/Attribute:subject' => 'Tárgy',
	'Class:ActionEmail/Attribute:subject+' => 'Az email tárgya',
	'Class:ActionEmail/Attribute:body' => 'Levéltörzs',
	'Class:ActionEmail/Attribute:body+' => 'Az email tartalma',
	'Class:ActionEmail/Attribute:importance' => 'Fontosság',
	'Class:ActionEmail/Attribute:importance+' => 'Fontosság jelölő',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Nem sürgős',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Átlagos',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Sürgős',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
	'Class:ActionEmail/Attribute:language' => 'Language~~',
	'Class:ActionEmail/Attribute:language+' => 'Language to use for placeholders ($xxx$) inside the message (state, importance, priority, etc)~~',
	'Class:ActionEmail/Attribute:html_template' => 'HTML template~~',
	'Class:ActionEmail/Attribute:html_template+' => 'Optional HTML template wrapping around the content of the \'Body\' attribute below, useful for tailored email layouts (in the template, content of the \'Body\' attribute will replace the $content$ placeholder)~~',
	'Class:ActionEmail/Attribute:ignore_notify' => 'Ignore the Notify flag~~',
	'Class:ActionEmail/Attribute:ignore_notify+' => 'If set to \'Yes\' the \'Notify\' flag on Contacts has no effect.~~',
	'Class:ActionEmail/Attribute:ignore_notify/Value:no' => 'No~~',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes' => 'Yes~~',
	'ActionEmail:main' => 'Message~~',
	'ActionEmail:trigger' => 'Triggers~~',
	'ActionEmail:recipients' => 'Contacts~~',
	'ActionEmail:preview_tab' => 'Preview~~',
	'ActionEmail:preview_tab+' => 'Preview of the eMail template~~',
	'ActionEmail:preview_warning' => 'The actual eMail may look different in the eMail client than this preview in your browser.~~',
	'ActionEmail:preview_more_info' => 'For more information about the CSS features supported by the different eMail clients, refer to %1$s~~',
	'ActionEmail:content_placeholder_missing' => 'The placeholder "%1$s" was not found in the HTML template. The content of the field "%2$s" will not be included in the generated emails.~~',
));

//
// Class: Trigger
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Trigger' => 'Eseményindító',
	'Class:Trigger+' => 'Egyéni eseménykezelés',
	'Class:Trigger/ComplementaryName' => '%1$s, %2$s~~',
	'Class:Trigger/Attribute:description' => 'Leírás',
	'Class:Trigger/Attribute:description+' => 'Egysoros leírás',
	'Class:Trigger/Attribute:action_list' => 'Elindított műveletek',
	'Class:Trigger/Attribute:action_list+' => 'Az eseményindító aktiválásakor végrehajtott műveletek',
	'Class:Trigger/Attribute:finalclass' => 'Eseményindító al-osztály',
	'Class:Trigger/Attribute:finalclass+' => 'A végleges osztály neve',
	'Class:Trigger/Attribute:context' => 'Kontextus',
	'Class:Trigger/Attribute:context+' => 'Kontextus, amely lehetővé teszi az eseményindító elindítását',
	'Class:Trigger/Attribute:complement' => 'Additional information~~',
	'Class:Trigger/Attribute:complement+' => 'Further information as provided in english, by this trigger~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObject' => 'Eseményindító (osztályfüggő)',
	'Class:TriggerOnObject+' => 'Az objektumok egy adott osztályára történő eseményindítás',
	'Class:TriggerOnObject/Attribute:target_class' => 'Cél osztály',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Szűrő',
	'Class:TriggerOnObject/Attribute:filter+' => 'Korlátozza az objektumlistát (a célosztályból), amely aktiválja az eseményindítót.',
	'TriggerOnObject:WrongFilterQuery' => 'Helytelen szűrőkérdés: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'A szűrő lekérdezésnek %1$s osztályú objektumokat kell visszaadnia.',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnPortalUpdate' => 'Eseményindító (amikor a portálról frissül)',
	'Class:TriggerOnPortalUpdate+' => 'Eseményindító egy végfelhasználó által a portálon történő frissítéskor',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateChange' => 'Eseményindító (állapotváltozásnál)',
	'Class:TriggerOnStateChange+' => 'Eseményindító egy objektum állapotának változásakor',
	'Class:TriggerOnStateChange/Attribute:state' => 'Állapot',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateEnter' => 'Eseményindító (állapot felvételekor)',
	'Class:TriggerOnStateEnter+' => 'Az objektum állapotváltozásba lépéskor elinduló eseményindító',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnStateLeave' => 'Eseményindító (állapot elhagyáskor)',
	'Class:TriggerOnStateLeave+' => 'Az objektum állapotváltozás elhagyásakor elinduló eseményindító',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectCreate' => 'Eseményindító (objektum létrehozáskor)',
	'Class:TriggerOnObjectCreate+' => 'Az adott osztály [egy gyermekosztálya] objektumának létrehozásakor elinduló eseményindító.',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectDelete' => 'Eseményindító (objektum törléskor)',
	'Class:TriggerOnObjectDelete+' => 'Az adott osztály [egy gyermekosztálya] objektumának törlésekor elinduló eseményindító.',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectUpdate' => 'Eseményindító (objektum frissítéskor)',
	'Class:TriggerOnObjectUpdate+' => 'Az adott osztály [egy gyermekosztálya] objektumának frissítésekor elinduló eseményindító',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Célmezők',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnObjectMention' => 'Eseményindító (objektumra hivatkozáskor)',
	'Class:TriggerOnObjectMention+' => 'Az adott osztály [egy gyermekosztálya] objektumára (@xxx) hivatkozáskor egy naplóattribútumban',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Hivatkozás szűrő',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'A hivatkozott objektumok listájának korlátozása, amelyek aktiválják az eseményindítót. Ha üres, akkor bármelyik említett objektum (bármely osztályból) aktiválja azt.',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Trigger (on object\'s document download)~~',
	'Class:TriggerOnAttributeBlobDownload+' => 'Trigger on object\'s document field download of [a child class of] the given class~~',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnThresholdReached' => 'Eseményindító (küszöbértéknél)',
	'Class:TriggerOnThresholdReached+' => 'Eseményindító egy időzítő küszöbértékének elérésekor',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Időzítő',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Küszöbérték',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkTriggerAction' => 'Művelet/Eseményindító',
	'Class:lnkTriggerAction+' => 'Kapcsolat egy eseményindító és egy művelet között',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Művelet',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'A végrehajtandó művelet',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Művelet név',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Eseményindító',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Eseményindító név',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Sorrend',
	'Class:lnkTriggerAction/Attribute:order+' => 'A műveletek végrehajtási sorrendje',
));

//
// Synchro Data Source
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SynchroDataSource' => 'Szinkron adatforrás',
	'Class:SynchroDataSource/Attribute:name' => 'Név',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Leírás',
	'Class:SynchroDataSource/Attribute:status' => 'Állapot',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Célosztály',
	'Class:SynchroDataSource/Attribute:scope_class+' => 'A Synchro Data Source can only populate a single '.ITOP_APPLICATION_SHORT.' class~~',
	'Class:SynchroDataSource/Attribute:user_id' => 'Felhasználónév',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Értesítési kapcsolattartó',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Hiba esetén az értesítendő kapcsolattartó',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Ikon URL',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hiperhivatkozás egy (kis) képre, amely azt az alkalmazást ábrázolja, amellyel a '.ITOP_APPLICATION_SHORT.' szinkronizálva van.',
	'Class:SynchroDataSource/Attribute:url_application' => 'Alkalmazás URL',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hiperhivatkozás a '.ITOP_APPLICATION_SHORT.' objektumra abban a külső alkalmazásban, amellyel a '.ITOP_APPLICATION_SHORT.' szinkronizálva van (ha van ilyen). Lehetséges helyörzők: $this->attribute$ és $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Egyeztetési szabály',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+' => '"Use the attributes": '.ITOP_APPLICATION_SHORT.' object matches replica values for each Synchro attributes flagged for Reconciliation.
"Use primary_key": the column primary_key of the replica is expected to contain the identifier of the '.ITOP_APPLICATION_SHORT.' object~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Teljes betöltés időköze',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Az összes adat teljes újratöltésének legalább az itt megadott gyakorisággal kell megtörténnie.',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Művelet nulla esetén',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Művelet, amikor a keresés nem ad vissza objektumot',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Művelet egy esetén',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Művelet, amikor a keresés pontosan egy objektumot ad vissza',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Művelet több esetén',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Művelet, ha a keresés egynél több objektumot ad vissza',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Engedélyezett felhasználók',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Akik törölhetik a szinkronizált objektumokat',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Senki',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Csak rendszergazdák',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Minden engedélyezett felhasználó',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Frissítési szabályok',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Szintaxis: mezo_nev:ertek; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Megtartási idő',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Mennyi ideig tartanak meg egy elavult objektumot törlés előtt.',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Adattábla',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'A szinkronizálási adatok tárolására szolgáló tábla neve. Ha üresen hagyja, akkor egy alapértelmezett név lesz generálva.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Megvalósítás',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Bevezetve',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Tartalomszűkítés',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Használja az attribútumokat',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Használja a primary_key mezőt',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Létrehozás',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Frissítés',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Létrehozás',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Hiba',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Vegye az elsőt (random?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Szabály törlése',
	'Class:SynchroDataSource/Attribute:delete_policy+' => 'What to do when a replica becomes obsolete:
"Ignore": do nothing, the associated object remains as is in iTop.
"Delete": Delete the associated object in iTop (and the replica in the data table).
"Update": Update the associated object as specified by the Update rules (see below).
"Update then Delete": apply the "Update rules". When Retention Duration expires, execute a "Delete" ~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Törlés',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Figyelmen kívül hagyás',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Frissítés',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Frissítés és törlés',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attribútum lista',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Csak rendszergazdák',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Mindenki törölhet ilyen objektumokat',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Senki',
	'SynchroDataSource:Description' => 'Leírás',
	'SynchroDataSource:Reconciliation' => 'Keresés &amp; egyeztetés',
	'SynchroDataSource:Deletion' => 'Törlési szabályok',
	'SynchroDataSource:Status' => 'Állapot',
	'SynchroDataSource:Information' => 'Információ',
	'SynchroDataSource:Definition' => 'Definíció',
	'Core:SynchroAttributes' => 'Attribútumok',
	'Core:SynchroStatus' => 'Állapot',
	'Core:Synchro:ErrorsLabel' => 'Hibák',
	'Core:Synchro:CreatedLabel' => 'Létrehozva',
	'Core:Synchro:ModifiedLabel' => 'Módosítva',
	'Core:Synchro:UnchangedLabel' => 'Változatlan',
	'Core:Synchro:ReconciledErrorsLabel' => 'Hibák',
	'Core:Synchro:ReconciledLabel' => 'Egyeztetve',
	'Core:Synchro:ReconciledNewLabel' => 'Létrehozva',
	'Core:SynchroReconcile:Yes' => 'Igen',
	'Core:SynchroReconcile:No' => 'Nem',
	'Core:SynchroUpdate:Yes' => 'Igen',
	'Core:SynchroUpdate:No' => 'Nem',
	'Core:Synchro:LastestStatus' => 'Utóbbi állapot',
	'Core:Synchro:History' => 'Szinkronizáció előzmények',
	'Core:Synchro:NeverRun' => 'Még nem futott szinkronizálás. Nincs naplóbejegyzés.',
	'Core:Synchro:SynchroEndedOn_Date' => 'A legutóbbi szinkronizáció befejezésének ideje %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'A szinkronizálás %1$s -kor elindult és még fut...',
	'Menu:DataSources' => 'Szinkronizációs adatforrások',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Minden szinkronizációs adatforrás',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Figyelmen kívül hagyott (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Eltűnt (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Létező (%1$s)',
	'Core:Synchro:label_repl_new' => 'Új (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Törölt (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Elavult (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Hibák (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Nincs művelet (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Változatlan (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Frissített (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Hibák (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Változatlan (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Frissített (%1$s)',
	'Core:Synchro:label_obj_created' => 'Létrehozott (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Hibák (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Feldolgozott replika: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Legalább egy egyeztetési kulcsot meg kell adni, vagy az egyeztetési szabálynak az elsődleges kulcsot kell használnia.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Meg kell adni a törlési megőrzési időszakot, mivel az objektumokat az elavultként való megjelölés után törölni kell.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Az elavult objektumokat frissíteni kell, de nincs megadva frissítés.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'A %1$s tábla már létezik az adatbázisban. Kérjük, használjon másik nevet a szinkronizálási adattáblához.',
	'Core:SynchroReplica:PublicData' => 'Nyilvános adat',
	'Core:SynchroReplica:PrivateDetails' => 'Személyi adatok',
	'Core:SynchroReplica:BackToDataSource' => 'Visszatérés a szinkronizációs adatforráshoz: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Replikák listája',
	'Core:SynchroAttExtKey:ReconciliationById' => 'Azonosító (Elsődleges kulcs)',
	'Core:SynchroAtt:attcode' => 'Attribútum',
	'Core:SynchroAtt:attcode+' => 'Az objektum mezője',
	'Core:SynchroAtt:reconciliation' => 'Egyeztetés ?',
	'Core:SynchroAtt:reconciliation+' => 'Kereséshez használható',
	'Core:SynchroAtt:update' => 'Frissítés ?',
	'Core:SynchroAtt:update+' => 'Objektum frissítéshez használható',
	'Core:SynchroAtt:update_policy' => 'Frissítési szabály',
	'Core:SynchroAtt:update_policy+' => 'A frissített mező viselkedése',
	'Core:SynchroAtt:reconciliation_attcode' => 'Egyeztető kulcs',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribútumkód a külső kulcs egyeztetéséhez',
	'Core:SyncDataExchangeComment' => '(Adat szinkron)',
	'Core:Synchro:ListOfDataSources' => 'Adatforrások listája:',
	'Core:Synchro:LastSynchro' => 'Utolsó szinkronizálás:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Ez az objektum egy külső adatforrással van szinkronizálva.',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Az objektumot a %1$s külső adatforrás <b>létrehozta</b>',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Az objektum <b>törölhető</b> a %1$s külső adatforrás által.',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Az objektumot <b>nem lehet törölni</b>, mert az a %1$s külső adatforrás tulajdonában van',
	'TitleSynchroExecution' => 'Szinkronizálás végrehajtása',
	'Class:SynchroDataSource:DataTable' => 'Adatbázis tábla: %1$s',
	'Core:SyncDataSourceObsolete' => 'Az adatforrás elavultnak van jelölve. A művelet törlésre került.',
	'Core:SyncDataSourceAccessRestriction' => 'Ezt a műveletet csak a rendszergazdák vagy az adatforrásban megadott felhasználó végezheti el. A művelet törlésre került.',
	'Core:SyncTooManyMissingReplicas' => 'Az összes rekordot egy ideje nem használta senki (az összes objektumot törölni lehet). Ellenőrizze, hogy a szinkronizációs táblába író folyamat még mindig fut-e. A művelet törlődött.',
	'Core:SyncSplitModeCLIOnly' => 'A szinkronizálás csak akkor hajtható végre darabokban, ha CLI üzemmódban fut.',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replika, %2$s hiba, %3$s figyelmeztetés.',
	'Core:SynchroReplica:TargetObject' => 'Szinkronizált objektum: %1$s',
	'Class:AsyncSendEmail' => 'Email (aszinkron)',
	'Class:AsyncSendEmail/Attribute:to' => 'Címzett',
	'Class:AsyncSendEmail/Attribute:subject' => 'Tárgy',
	'Class:AsyncSendEmail/Attribute:body' => 'Levéltörzs',
	'Class:AsyncSendEmail/Attribute:header' => 'Fejléc',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Titkosított jelszó',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Titkosított mező',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Előző érték',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Esetnapló',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Utolsó bejegyzés',
	'Class:SynchroAttribute' => 'Szinkron attribútum',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Szinkronizációs adatforrás',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribútumkód',
	'Class:SynchroAttribute/Attribute:update' => 'Frissítés',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Egyeztetés',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Frissítési szabály',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Zárolva',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Feloldva',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Inicializálás, ha üres',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Osztály',
	'Class:SynchroAttExtKey' => 'Szinkron attribútum (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Egyeztető attribútum',
	'Class:SynchroAttLinkSet' => 'Szinkron attribútum (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Sorelválasztó',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Elválasztó attribútumok',
	'Class:SynchroLog' => 'Szinkron napló',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Szinkronizációs adatforrás',
	'Class:SynchroLog/Attribute:start_date' => 'Kezdés dátuma',
	'Class:SynchroLog/Attribute:end_date' => 'Befejezés dátuma',
	'Class:SynchroLog/Attribute:status' => 'Állapot',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Befejezett',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Hiba',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Még fut',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb talált replika',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb összes replika',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb törölt objektum',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb hiba törléskor',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb elavult objektum',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb hiba elavuláskor',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb létrehozott objektum',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb hiba létrehozáskor',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objektum létrehozva',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb hiba frissítéskor',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb hiba egyeztetéskor',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replika eltűnt',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objektum frissítve',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objektum változatlan',
	'Class:SynchroLog/Attribute:last_error' => 'Utolsó hiba',
	'Class:SynchroLog/Attribute:traces' => 'Nyomok',
	'Class:SynchroReplica' => 'Szinkron replika',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Szinkronizációs adatforrás',
	'Class:SynchroReplica/Attribute:dest_id' => 'Célobjektum (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Cél típus',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Utolsó előfordulás',
	'Class:SynchroReplica/Attribute:status' => 'Állapot',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Módosítva',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Új',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Árva',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Szinkronizált',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Objektum létrehozva ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Utolsó hiba',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Figyelmeztetések',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Létrehozás dátuma',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Utolsó módosítás dátuma',
	'Class:appUserPreferences' => 'Felhasználói beállítások',
	'Class:appUserPreferences/Attribute:userid' => 'Felhasználónév',
	'Class:appUserPreferences/Attribute:preferences' => 'Beállítások',
	'Core:ExecProcess:Code1' => 'Helytelen parancs vagy hibásan befejezett parancs (pl. helytelen szkriptnév)',
	'Core:ExecProcess:Code255' => 'PHP hiba (parsing, vagy runtime)',
    // Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',
    // Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Eltelt idő (tárolva mint %1$s)',
	'Core:ExplainWTC:StopWatch-TimeSpent' => '%1$s ráfordított ideje',
	'Core:ExplainWTC:StopWatch-Deadline' => '%1$s határideje %2$d%% -kor',
    // Bulk export
	'Core:BulkExport:MissingParameter_Param' => '%1$s paramétere hiányzik',
	'Core:BulkExport:InvalidParameter_Query' => 'A query paraméter értéke érvénytelen. Nincs lekérdezés gyűjtemény ehhez az azonosítóhoz: %1$s.',
	'Core:BulkExport:ExportFormatPrompt' => 'Export formátum:',
	'Core:BulkExportOf_Class' => '%1$s exportálás',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Kattintson a %1$s letöltéséhez',
	'Core:BulkExport:ExportResult' => 'Exportálás eredménye:',
	'Core:BulkExport:RetrievingData' => 'Adatlekérés...',
	'Core:BulkExport:HTMLFormat' => 'Weblap (*.html)',
	'Core:BulkExport:CSVFormat' => 'Vesszővel elválasztott értékek (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 vagy újabb (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF dokumentum (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Húzza az oszlopok fejléceit az oszlopok elrendezéséhez. A %1$s sorok előnézete. Az exportálandó sorok száma: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Válassza ki az exportálandó oszlopokat a fenti listából.',
	'Core:BulkExport:ColumnsOrder' => 'Oszlopsorrend',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Elérhető oszlopok %1$s -tól',
	'Core:BulkExport:NoFieldSelected' => 'Válasszon ki legalább egy exportálandó oszlopot',
	'Core:BulkExport:CheckAll' => 'Összes bejelölése',
	'Core:BulkExport:UncheckAll' => 'Bejelölések megszüntetése',
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
	'Core:BulkExport:SpreadsheetFormat' => 'Táblázat HTML formátumban (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Táblázat beállítások',
	'Core:BulkExport:OptionNoLocalize' => 'Címke helyett kód exportálása',
	'Core:BulkExport:OptionLinkSets' => 'Kapcsolódó objektumok bevonása',
	'Core:BulkExport:OptionFormattedText' => 'Szövegformázás megtartása',
	'Core:BulkExport:ScopeDefinition' => 'Az exportálandó objektumok meghatározása',
	'Core:BulkExportLabelOQLExpression' => 'OQL lekérdezés:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Lekérdezés gyűjtemény bejegyzés:',
	'Core:BulkExportMessageEmptyOQL' => 'Érvényes OQL lekérdezést adjon meg.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Érvényes lekérdezés gyűjtemény bejegyzést adjon meg.',
	'Core:BulkExportQueryPlaceholder' => 'Ide írja az OQL lekérdezést...',
	'Core:BulkExportCanRunNonInteractive' => 'Kattintson ide az exportálás nem interaktív módban történő futtatásához.',
	'Core:BulkExportLegacyExport' => 'Kattintson ide a régebbi típusú exportálás eléréséhez.',
	'Core:BulkExport:XLSXOptions' => 'Excel beállítások',
	'Core:BulkExport:TextFormat' => 'HTML jelölést tartalmazó szöveges mezők',
	'Core:BulkExport:DateTimeFormat' => 'Dátum és időformátum',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Alapértelmezett formátum (%1$s), Pl. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Egyéni formátum: %1$s',
	'Core:BulkExport:PDF:PageNumber' => '%1$s oldal',
	'Core:DateTime:Placeholder_d' => 'DD',
    // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D',
    // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM',
    // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M',
    // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY',
    // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY',
    // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh',
    // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h',
    // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh',
    // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h',
    // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm',
    // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM',
    // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm',
    // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss',
    // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Helytelen formátum',
	'Core:Validator:Mandatory' => 'Töltse ki ezt a mezőt',
	'Core:Validator:MustBeInteger' => 'Egész számnak kell lennie',
	'Core:Validator:MustSelectOne' => 'Egyet válasszon',
));

//
// Class: TagSetFieldData
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TagSetFieldData' => '%2$s a %1$s osztályhoz',
	'Class:TagSetFieldData+' => '',
	'Class:TagSetFieldData/Attribute:code' => 'Kód',
	'Class:TagSetFieldData/Attribute:code+' => 'Belső kód. Legalább 3 alfanumerikus karaktert kell tartalmaznia.',
	'Class:TagSetFieldData/Attribute:label' => 'Címke',
	'Class:TagSetFieldData/Attribute:label+' => 'Megjelenített címke',
	'Class:TagSetFieldData/Attribute:description' => 'Leírás',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Címke típus',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Objektum osztály',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Mezőkód',
	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'A felhasznált címkék nem törölhetők',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'A címkekódoknak egyedinek kell lennie',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'A címkekódnak 3 és %1$d közötti, betűvel kezdődő alfanumerikus karaktereket kell tartalmaznia.',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'A választott címkekód egy foglalt szóval egyezik',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'A címkék felirata nem tartalmazhat %1$s -et és nem lehet üres.',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'A címkekód nem változtatható, ha használatban van',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Az "Object Class" címkék nem módosíthatók',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Az "Attribútumkód" címkék nem módosíthatók',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Címkehasználat (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nincs bejegyzés ehhez a címkéhez',
));

//
// Class: DBProperty
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DBProperty' => 'DB tulajdonságok',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Név',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Leírás',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Érték',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Változás dátuma',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Megjegyzés',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:BackgroundTask' => 'Háttérfeladat',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Osztálynév',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Első futás dátuma',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Utolsó futás dátuma',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Következő futás ideje',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Futások száma',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Utolsó futás időtartama',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Legrövidebb futási idő',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Leghosszabb futási idő',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Átlagos futási idő',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Fut',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Állapot',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AsyncTask' => 'Aszinkron feladat',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Létrehozva',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Elindítva',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Betervezve',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Esemény',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Végleges osztály',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Állapot',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Maradék próbálkozás',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Utolsó hibakód',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Utolsó hiba',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Utolsó próbálkozás',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
	'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Érvénytelen formátum az async_task_retries[%1$s] konfigurációhoz. A következő kulcsokkal rendelkező tömböt vár: %2$s',
	'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Az async_task_retries[%1$s] konfigurációjának érvénytelen formátuma: %2$s váratlan kulcs. Csak a következő kulcsokat várja: %3$s',
));

//
// Class: AbstractResource
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AbstractResource' => 'Absztrakt erőforrás',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ResourceAdminMenu' => 'Erőforrás admin menü',
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




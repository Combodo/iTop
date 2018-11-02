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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author Hipska (2018)
 * @author jbostoen (2018)
 * 
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */



Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Core:DeletedObjectLabel' => '%1s (verwijderd)',
	'Core:DeletedObjectTip' => 'Het object is verwijderd op %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'Object niet gevonden (klasse: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Object kon niet worden gevonden. Het zou eerder verwijderd kunnen zijn en de log zou kunnen zijn opgeschoond.',

	'Core:UniquenessDefaultError' => 'De regel \'%1$s\' die unieke waardes afdwingt, geeft een fout',

	'Core:AttributeLinkedSet' => 'Reeks van objecten',
	'Core:AttributeLinkedSet+' => 'Elke soort objecten van dezelfde klasse of subklasse',
	
	'Core:AttributeTagSet' => 'Lijst van tags',
    'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'klik om toe te voegen~~',
    'Core:AttributeCaseLog' => 'Log',
    'Core:AttributeCaseLog+' => '',
    'Core:AttributeMetaEnum' => 'Berekende oplijsting',
    'Core:AttributeMetaEnum+' => '', 
	
	'Core:AttributeLinkedSetIndirect' => 'Reeks van objecten (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Elke soort objecten [subklasse] van dezelfde klasse',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Numerieke waarde (kan negatief zijn)',

	'Core:AttributeDecimal' => 'Decimaal',
	'Core:AttributeDecimal+' => 'Decimale waarde (kan negatief zijn)',

	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => 'Boolean',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Ja',
	'Core:AttributeBoolean/Value:no' => 'Nee',

	'Core:AttributeArchiveFlag' => 'Gearchiveerd',
	'Core:AttributeArchiveFlag/Value:yes' => 'Ja',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Dit object is enkel zichtbaar in Archief-mode',
	'Core:AttributeArchiveFlag/Value:no' => 'Nee',
	'Core:AttributeArchiveFlag/Label' => 'Gearchiveerd',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Gearchiveerd op',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Buiten dienst',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Ja',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Dit object is uitgesloten uit impact-analyses en verborgen in zoekresultaten.',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Nee',
	'Core:AttributeObsolescenceFlag/Label' => 'Buiten dienst',
	'Core:AttributeObsolescenceFlag/Label+' => 'Automatisch toegepast op andere attributen',
	'Core:AttributeObsolescenceDate/Label' => 'Buiten dienst sinds',
	'Core:AttributeObsolescenceDate/Label+' => 'Datum bij benadering waarop het object als buiten dienst beschouwd werd',

	'Core:AttributeString' => 'Tekstregel',
	'Core:AttributeString+' => 'Alfanumerieke tekstregel',

	'Core:AttributeClass' => 'Klasse',
	'Core:AttributeClass+' => 'Klasse',

	'Core:AttributeApplicationLanguage' => 'Taal van de gebruiker',
	'Core:AttributeApplicationLanguage+' => 'Taal en land (EN US)',

	'Core:AttributeFinalClass' => 'Klasse (auto)',
	'Core:AttributeFinalClass+' => 'Echte klasse van het object (automatisch aangemaakt bij de kern)',

	'Core:AttributePassword' => 'Wachtwoord',
	'Core:AttributePassword+' => 'Wachtwoord van een extern apparaat',

 	'Core:AttributeEncryptedString' => 'Gecodeerde string',
	'Core:AttributeEncryptedString+' => 'String gecodeerd met een lokale sleutel (key)',
	'Core:AttributeEncryptUnknownLibrary' => 'De encryptie-bibliotheek (%1$s) is onbekend',
	'Core:AttributeEncryptFailedToDecrypt' => '** fout bij decryptie **',

	'Core:AttributeText' => 'Tekstvak',
	'Core:AttributeText+' => 'Meerdere regels tekst',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML-string',

	'Core:AttributeEmailAddress' => 'E-mailadres',
	'Core:AttributeEmailAddress+' => 'E-mailadres',

	'Core:AttributeIPAddress' => 'IP-adres',
	'Core:AttributeIPAddress+' => 'IP-adres',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Language-expressie',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lijst van voorgedefineerde alfanumerieke teksten',

	'Core:AttributeTemplateString' => 'Sjabloon tekstregel',
	'Core:AttributeTemplateString+' => 'String die de procurators bevat',

	'Core:AttributeTemplateText' => 'Sjabloon tekstvak',
	'Core:AttributeTemplateText+' => 'Tekst die de procurators bevat',

	'Core:AttributeTemplateHTML' => 'Sjabloon HTML',
	'Core:AttributeTemplateHTML+' => 'HTML die de procurators bevat',

	'Core:AttributeDateTime' => 'Datum/tijd',
	'Core:AttributeDateTime+' => 'Datum en tijd (jaar-maand-dag hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Datum formaat:<br/>
	<b>%1$s</b><br/>
	Voorbeeld: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
Hier wordt standaard \'00:00:00\' van gemaakt als er geen tijd wordt opgegeven.
</p>',

	'Core:AttributeDate' => 'Datum',
	'Core:AttributeDate+' => 'Datum (jaar-maand-dag)',
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
	'Core:AttributeDeadline+' => 'Datum, relatief weergegeven ten opzichte van het huidige tijdstip',

	'Core:AttributeExternalKey' => 'Externe sleutel (key)',
	'Core:AttributeExternalKey+' => 'Externe sleutel (key)',

	'Core:AttributeHierarchicalKey' => 'Hiërarchische sleutel (key)',
	'Core:AttributeHierarchicalKey+' => 'Externe sleutel naar het hoofdobject',

	'Core:AttributeExternalField' => 'Extern veld',
	'Core:AttributeExternalField+' => 'Veld dat verwijst naar een externe sleutel (key)',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Absolute of relatieve URL als een tekstregel',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Alle binaire inhoud (document)',

	'Core:AttributeOneWayPassword' => 'Wachtwoord',
	'Core:AttributeOneWayPassword+' => 'Gehasht wachtwoord (decryptie niet mogelijk)',

	'Core:AttributeTable' => 'Tabel',
	'Core:AttributeTable+' => 'Geïndexeerde reeks met twee dimensies',

	'Core:AttributePropertySet' => 'Eigenschappen',
	'Core:AttributePropertySet+' => 'Lijst van ongeschreven eigenschappen (naam en waarde)',

	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => 'Automatisch aangemaakt attribuut; de friendly name is na verscheidene attributen verwerkt',

	'Core:FriendlyName-Label' => 'Referentie',
	'Core:FriendlyName-Description' => 'Referentie',
	 
	'Core:AttributeTag' => 'Tags',
	'Core:AttributeTag+' => 'Tags',
	
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
	'Class:CMDBChangeOp' => 'Pas de handeling aan',
	'Class:CMDBChangeOp+' => 'Pas het volgen van de handeling aan',
	'Class:CMDBChangeOp/Attribute:change' => 'Pas aan',
	'Class:CMDBChangeOp/Attribute:change+' => 'Pas aan',
	'Class:CMDBChangeOp/Attribute:date' => 'datum',
	'Class:CMDBChangeOp/Attribute:date+' => 'datum en tijd van de aanpassing',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'gebruiker',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'wie heeft deze aanpassing doorgevoerd',
	'Class:CMDBChangeOp/Attribute:objclass' => 'objectklasse',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'objectklasse',
	'Class:CMDBChangeOp/Attribute:objkey' => 'object-id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'object-id',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'type',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpCreate' => 'objectcreatie',
	'Class:CMDBChangeOpCreate+' => 'historiek van objectcreatie',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpDelete' => 'verwijderen object',
	'Class:CMDBChangeOpDelete+' => 'historiek van het verwijderen van objecten',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttribute' => 'aanpassing van het object',
	'Class:CMDBChangeOpSetAttribute+' => 'historiek van de aanpassing van de objecteigenschappen',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attribuut',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code van de aangepaste eigenschap',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'Aanpassing van de eigenschap',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'historiek van gewijzigde eigenschappen',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'Vorige waarde van het attribuut',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nieuwe waarde',
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
	'Change:AttName_EntryAdded' => '%1$s aangepast, nieuwe invoer toegevoegd: %2$s',
	'Change:LinkSet:Added' => 'toegevoegd %1$s',
	'Change:LinkSet:Removed' => 'verwijderd %1$s',
	'Change:LinkSet:Modified' => 'aangepast %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'dataverandering',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'historiek van dataverandering',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Vorige data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'eerdere inhoud van het attribuut',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:CMDBChangeOpSetAttributeText' => 'tekstverandering',
	'Class:CMDBChangeOpSetAttributeText+' => 'historiek van tekstverandering',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Vorige data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'eerdere inhoud van het attribuut',
));

//
// Class: Event
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Event' => 'Log Event',
	'Class:Event+' => 'Een intern event van de applicatie',
	'Class:Event/Attribute:message' => 'Inhoud',
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
	'Class:EventNotificationEmail/Attribute:to' => 'Aan',
	'Class:EventNotificationEmail/Attribute:to+' => 'Aan',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'Van',
	'Class:EventNotificationEmail/Attribute:from+' => 'Afzender van het bericht',
	'Class:EventNotificationEmail/Attribute:subject' => 'Onderwerp',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Onderwerp',
	'Class:EventNotificationEmail/Attribute:body' => 'Inhoud',
	'Class:EventNotificationEmail/Attribute:body+' => 'Inhoud',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Bijlagen',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventIssue' => 'Probleem van het event',
	'Class:EventIssue+' => 'Log van een probleem (waarschuwing, fout, etc.)',
	'Class:EventIssue/Attribute:issue' => 'Probleem',
	'Class:EventIssue/Attribute:issue+' => 'Wat er gebeurde',
	'Class:EventIssue/Attribute:impact' => 'Impact',
	'Class:EventIssue/Attribute:impact+' => 'Wat zijn de gevolgen',
	'Class:EventIssue/Attribute:page' => 'Pagina',
	'Class:EventIssue/Attribute:page+' => 'HTTP entry point',
	'Class:EventIssue/Attribute:arguments_post' => 'POST-argumenten',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST-argumenten',
	'Class:EventIssue/Attribute:arguments_get' => 'URL-argumenten',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET-argumenten',
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
	'Class:EventWebService+' => 'Log van een webservice-aanroep',
	'Class:EventWebService/Attribute:verb' => 'Werkwoord',
	'Class:EventWebService/Attribute:verb+' => 'Naam van de handeling',
	'Class:EventWebService/Attribute:result' => 'Resultaat',
	'Class:EventWebService/Attribute:result+' => 'Totaal succes/falen',
	'Class:EventWebService/Attribute:log_info' => 'Infolog',
	'Class:EventWebService/Attribute:log_info+' => 'Resultaat infolog',
	'Class:EventWebService/Attribute:log_warning' => 'Waarschuwingslog',
	'Class:EventWebService/Attribute:log_warning+' => 'Resultaat waarschuwingslog',
	'Class:EventWebService/Attribute:log_error' => 'Foutenlog',
	'Class:EventWebService/Attribute:log_error+' => 'Resultaat foutenlog',
	'Class:EventWebService/Attribute:data' => 'Data',
	'Class:EventWebService/Attribute:data+' => 'Resulterende data'
));

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventRestService' => 'REST/JSON aanroep',
	'Class:EventRestService+' => 'Log van een aangeroepen REST/JSON-service',
	'Class:EventRestService/Attribute:operation' => 'Handeling',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => 'Versie',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'',
	'Class:EventRestService/Attribute:json_input' => 'Invoer',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Code',
	'Class:EventRestService/Attribute:code+' => 'Resultaatcode',
	'Class:EventRestService/Attribute:json_output' => 'Antwoord',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP-antwoord (JSON)',
	'Class:EventRestService/Attribute:provider' => 'Provider',
	'Class:EventRestService/Attribute:provider+' => 'PHP-klasse die de verwachte handeling gebruikt',
));

//
// Class: EventLoginUsage
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:EventLoginUsage' => 'Gebruik van logins',
	'Class:EventLoginUsage+' => 'Verbinding met de applicatie',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Gebruikersnaam',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'Gebruikersnaam',
	'Class:EventLoginUsage/Attribute:contact_email' => 'E-mailadres van de gebruiker',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'E-mailadres van de gebruiker',
));

//
// Class: Action
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Action' => 'Custom Actie',
	'Class:Action+' => 'Door gebruiker gedefinieerde actie',
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
	'Class:Action/Attribute:trigger_list' => 'Verwante Triggers',
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
	'Class:ActionEmail/Attribute:test_recipient+' => 'Bestemming als de status op "Test" staat',
	'Class:ActionEmail/Attribute:from' => 'Van',
	'Class:ActionEmail/Attribute:from+' => 'Wordt gebruikt in de hoofdtekst van de e-mail (headers)',
	'Class:ActionEmail/Attribute:reply_to' => 'Antwoord',
	'Class:ActionEmail/Attribute:reply_to+' => 'Wordt gebruikt in de hoofdtekst van de e-mail (headers)',
	'Class:ActionEmail/Attribute:to' => 'Aan',
	'Class:ActionEmail/Attribute:to+' => 'Bestemming van de e-mail',
	'Class:ActionEmail/Attribute:cc' => 'CC',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'BCC',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => 'Onderwerp',
	'Class:ActionEmail/Attribute:subject+' => 'Onderwerp van de e-mail',
	'Class:ActionEmail/Attribute:body' => 'Inhoud',
	'Class:ActionEmail/Attribute:body+' => 'Inhoud van de e-mail',
	'Class:ActionEmail/Attribute:importance' => 'Prioriteit',
	'Class:ActionEmail/Attribute:importance+' => 'Prioriteit',
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
	'Class:TriggerOnObject+' => 'Trigger op een bepaalde klasse van objecten',
	'Class:TriggerOnObject/Attribute:target_class' => 'Toegepast op klasse',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Verkeerde filter-query: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'De filter-query moet verwijzen naar objecten van klasse "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (als er vanuit het portaal geüpdatet wordt)',
	'Class:TriggerOnPortalUpdate+' => 'Trigger op de update van de eindgebruiker van het portaal',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateChange' => 'Trigger (als de status verandert)',
	'Class:TriggerOnStateChange+' => 'Trigger als de status van het object verandert',
	'Class:TriggerOnStateChange/Attribute:state' => 'Status',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateEnter' => 'Trigger (als een status van toepassing wordt)',
	'Class:TriggerOnStateEnter+' => 'Trigger als de status van het object naar deze status verandert',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnStateLeave' => 'Trigger (als een status niet meer van toepassing is)',
	'Class:TriggerOnStateLeave+' => 'Trigger als de status van het object niet meer deze status heeft',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (bij het aanmaken van een object)',
	'Class:TriggerOnObjectCreate+' => 'Trigger bij het aanmaken van een object van de opgegeven klasse (of subklasse ervan)',
));

//
// Class: TriggerOnObjectDelete
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (bij het verwijderen van een object)',
	'Class:TriggerOnObjectDelete+' => 'Trigger bij het verwijderen van een object van de opgegeven klasse (of subklasse ervan)',
));
//
// Class: TriggerOnObjectUpdate
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (bij het aanpassen van een object)',
	'Class:TriggerOnObjectUpdate+' => 'Trigger bij het aanpassen van een object van de opgegeven klasse (of subklasse ervan)',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Doelvelden',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (op drempelwaarde)',
	'Class:TriggerOnThresholdReached+' => 'Trigger op Stop-Watch drempelwaarde bereikt',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Drempelwaarde',
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
	'Class:SynchroDataSource/Attribute:status' => 'Status',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target klasse',
	'Class:SynchroDataSource/Attribute:user_id' => 'Gebruiker',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Verwittig dit contact',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Verwittig dit contact',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Hyperlink van de Icoon',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink een (kleine) afbeelding die de applicatie waarmee iTop is gesynchroniseerd',
	'Class:SynchroDataSource/Attribute:url_application' => 'Hyperlink van de applicatie',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink naar het iTop object in de externe applicatie waarmee iTop is gesynchroniseerd (indien van toepassing). Mogelijke procurators: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation-beleid',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Vernieuwingsinterval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Het volledige herladen van alle data moet minstens om deze tijd gebeuren.',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Actie op nul',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Actie die wordt ondernomen wanneer de zoekopdracht geen object geeft',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Actie op één',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action die wordt ondernomen wanneer de zoekopdracht precies één object geeft',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Actie op meerdere',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Actie die wordt ondernomen wanneer de zoekopdracht meerdere objecten geeft',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Gebruikers toegestaan',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Wie is geautoriseerd om gesynchroniseerde objecten te verwijderen', 
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
	'Core:Synchro:ErrorsLabel' => 'Fouten',	
	'Core:Synchro:CreatedLabel' => 'Aangemaakt',
	'Core:Synchro:ModifiedLabel' => 'Aangepast',
	'Core:Synchro:UnchangedLabel' => 'Niet veranderd',
	'Core:Synchro:ReconciledErrorsLabel' => 'Fouten',
	'Core:Synchro:ReconciledLabel' => 'Gematcht',
	'Core:Synchro:ReconciledNewLabel' => 'Aangemaakt',
	'Core:SynchroReconcile:Yes' => 'Ja',
	'Core:SynchroReconcile:No' => 'Nee',
	'Core:SynchroUpdate:Yes' => 'Ja',
	'Core:SynchroUpdate:No' => 'Nee',
	'Core:Synchro:LastestStatus' => 'Laatste Status',
	'Core:Synchro:History' => 'Synchronisatiegeschiedenis',
	'Core:Synchro:NeverRun' => 'Deze synchro heeft nog niet gelopen. Er is nog geen log.',
	'Core:Synchro:SynchroEndedOn_Date' => 'De laatste synchronisatie eindigde op %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'De synchronisatie is gestart op %1$s en is nog bezig...',
	'Menu:DataSources' => 'Synchronisatie Databronnen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Alle gesynchroniseerde Databronnen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Genegeerd (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Verdwenen (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Bestaand (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nieuw (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Verwijderd (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Overbodig (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Fouten (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Geen actie (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Onveranderd (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Geüpdatet (%1$s)', 
	'Core:Synchro:label_obj_updated_errors' => 'Fouten (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Onveranderd (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Geüpdatet (%1$s)',
	'Core:Synchro:label_obj_created' => 'Aangemaakt (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Fouten (%1$s)',
	'Core:Synchro:History' => 'Synchronisatiegeschiedenis',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica verwerkt: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Tenminste één reconciliation-sleutel (key) moet worden opgegeven, of de reconciliation policy moet zijn dat de primaire sleutel (key) wordt gebruikt.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Een retention period voor het verwijderen moet worden opgegeven, omdat alle objecten verwijderd worden nadat ze gemarkeerd zijn als overbodig',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Overbodige objecten moeten worden geüpdatet, maar er is geen update opgegeven.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'De tabel %1$s bestaat al in de database. Gebruik alstublieft een andere naam voor deze synchro-datatabel.',
	'Core:SynchroReplica:PublicData' => 'Publieke Data',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details',
	'Core:SynchroReplica:BackToDataSource' => 'Ga terug naar de Synchro Data Source: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lijst van Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primaire sleutel)',
	'Core:SynchroAtt:attcode' => 'Attribuut',
	'Core:SynchroAtt:attcode+' => 'Veld van het object',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?',
	'Core:SynchroAtt:reconciliation+' => 'Gebruikt voor het zoeken',
	'Core:SynchroAtt:update' => 'Update ?',
	'Core:SynchroAtt:update+' => 'Gebruikt om het object te updaten',
	'Core:SynchroAtt:update_policy' => 'Update Policy',
	'Core:SynchroAtt:update_policy+' => 'Gedrag van het geüpdatete veld',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation-sleutel',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribuutcode voor de Reconciliation van de externe sleutel (key)',
	'Core:SyncDataExchangeComment' => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources' => 'Lijst van databronnen:',
	'Core:Synchro:LastSynchro' => 'Laatste synchronisatie:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Dit object is gesynchroniseerd met een externe databron',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Dit object is <b>aangemaakt</b> door een externe databron %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Dit object <b>kan worden verwijderd</b> door de externe databron %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'U <b>kunt dit object niet verwijderen</b> omdat het eigendom is van de externe databron %1$s',
	'TitleSynchroExecution' => 'Uitvoering van de synchronisatie',
	'Class:SynchroDataSource:DataTable' => 'Database tabel: %1$s',
	'Core:SyncDataSourceObsolete' => 'De databron is gemarkeerd als overbodig. Handeling afgebroken.',
	'Core:SyncDataSourceAccessRestriction' => 'Alleen administrators of de gebruiker gespecificeerd in de databron kan deze handeling uitvoeren. Handeling afgebroken.',
	'Core:SyncTooManyMissingReplicas' => 'Alle records zijn een tijd niet verwerkt (alle objecten kunnen worden verwijderd). Controleer alstublieft of het proces dat in de datatabel schrijft nog steeds bezig is. Handeling afgebroken.',
	'Core:SyncSplitModeCLIOnly' => 'De synchronisatie kan alleen in delen worden uitgevoerd in CLI-mode.',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replica\'s, %2$s fout(en), %3$s waarschuwing(en).',
	'Core:SynchroReplica:TargetObject' => 'Gesynchroniseerd Object: %1$s',
	'Class:AsyncSendEmail' => 'E-mail (niet synchroon)',
	'Class:AsyncSendEmail/Attribute:to' => 'Aan',
	'Class:AsyncSendEmail/Attribute:subject' => 'Onderwerp',
	'Class:AsyncSendEmail/Attribute:body' => 'Inhoud',
	'Class:AsyncSendEmail/Attribute:header' => 'Hoofdtekst (headers)',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Gehasht wachtwoord',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Geëncrypteerd veld',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Vorige waarde',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Meest recente invoer',
	'Class:SynchroDataSource' => 'Synchro Databron',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Overbodig',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Productie',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope-beperking',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Gebruik de attributen',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Gebruik het veld primary_key (primaire sleutel)',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Maak aan',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Fout',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Fout',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Maak aan',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Fout',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Neem de eerste (willekeurig?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Verwijder Policy',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Verwijder',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Negeer',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update en dan verwijderen',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lijst van attributen',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Alleen administrators',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Iedereen mag deze objecten verwijderen',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Niemand',
	'Class:SynchroAttribute' => 'Synchro Attribuut',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Databron',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribuutcode',
	'Class:SynchroAttribute/Attribute:update' => 'Update',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Gesloten',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Open',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Begin indien leeg',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Klasse',
	'Class:SynchroAttExtKey' => 'Synchro Attribuut (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation-attribuut',
	'Class:SynchroAttLinkSet' => 'Synchro Attribuut (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Scheidingsteken rijen',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Scheidingsteken attributen',
	'Class:SynchroLog' => 'Synchronisatielog',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Databron',
	'Class:SynchroLog/Attribute:start_date' => 'Begindatum',
	'Class:SynchroLog/Attribute:end_date' => 'Einddatum',
	'Class:SynchroLog/Attribute:status' => 'Status',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Compleet',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Fout',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Nog bezig',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Aantal replica\'s gezien',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Totaal aantal replica\'s',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Aantal objecten verwijderd',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Aantal fouten tijdens het verwijderen',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Aantal  objecten overbodig gemaakt',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Aantal fouten tijdens het overbodig maken',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Aantal objecten aangemaakt',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Aantal fouten tijdens het aanmaken',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Aantal objecten geüpdatet',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Aantal fouten tijden het updaten',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Aantal fouten tijdens de reconciliation',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Aantal replicas verdwenen',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Aantal objecten geüpdatet',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Aantal onveranderde objecten',
	'Class:SynchroLog/Attribute:last_error' => 'Laatste foutmelding',
	'Class:SynchroLog/Attribute:traces' => 'Logs',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Databron',
	'Class:SynchroReplica/Attribute:dest_id' => 'Bestemming van het object (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Type bestemming',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Laatst gezien',
	'Class:SynchroReplica/Attribute:status' => 'Status',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Aangepast',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nieuw',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Overbodig',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Wees (orphan)',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Gesynchroniseerd',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object aangemaakt?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Laatste fout',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Waarschuwingen',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Datum van aanmaken',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Datum van de laatste aanpassing',
	'Class:appUserPreferences' => 'Gebruikersvoorkeuren',
	'Class:appUserPreferences/Attribute:userid' => 'Gebruiker',
	'Class:appUserPreferences/Attribute:preferences' => 'Voorkeuren',
	'Core:ExecProcess:Code1' => 'Verkeerde commando of commando beëindigd met fouten (bijvoorbeeld verkeerde scriptnaam)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, of runtime)',
));

//
// Attribute Duration
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	// Attribute Duration
	'Core:Duration_Seconds'	=> '%1$ds',	
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',
	
	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Tijd voorbij (opgeslagen als \"%1$s\")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Tijd gespendeerd voor \"%1$s\"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline voor \"%1$s\" at %2$d%%',
	
	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Ontbrekende parameter \"%1$s\"',
	'Core:BulkExport:InvalidParameter_Query' => 'Ongeldige waarde voor de parameter \"query\". Er is geen Query Phrasebook die overeenkomt met id: \"%1$s\".',
	'Core:BulkExport:ExportFormatPrompt' => 'Export-formaat:',
	'Core:BulkExportOf_Class' => '%1$s Export',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Download %1$s',
	'Core:BulkExport:ExportResult' => 'Resultaat van de export:',
	'Core:BulkExport:RetrievingData' => 'Data aan het opvragen...',
	'Core:BulkExport:HTMLFormat' => 'Webpagina (*.html)',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 of nieuwer (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Versleep de kolomkoppen om de kolommen opnieuw te ordenen. Bekijk een voorbeeld van de eerste %1$s regels. Totaal aantal regels: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Selecteer de kolommen die geëxporteerd moeten worden',
	'Core:BulkExport:ColumnsOrder' => 'Volgorde kolommen',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Beschikbare kolommen voor %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Selecteer minstens één kolom die geëxporteerd moet worden',
	'Core:BulkExport:CheckAll' => 'Alles aanvinken',
	'Core:BulkExport:UncheckAll' => 'Alles uitvinken',
	'Core:BulkExport:ExportCancelledByUser' => 'Export geannuleerd door de gebruiker',
	'Core:BulkExport:CSVOptions' => 'Opties voor CSV',
	'Core:BulkExport:CSVLocalization' => 'Vertaling',
	'Core:BulkExport:PDFOptions' => 'Opties voor PDF',
	'Core:BulkExport:PDFPageFormat' => 'Paginaformaat',
	'Core:BulkExport:PDFPageSize' => 'Paginagrootte',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Letter (Amerikaans)',
	'Core:BulkExport:PDFPageOrientation' => 'Pagina-oriëntatie:',
	'Core:BulkExport:PageOrientation-L' => 'Landschap',
	'Core:BulkExport:PageOrientation-P' => 'Portret',
	'Core:BulkExport:XMLFormat' => 'XML-bestand (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Opties voor XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML-formaat (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Opties voor Spreadsheet',
	'Core:BulkExport:OptionNoLocalize' => 'Exporteer code/waarde in plaats van label',
	'Core:BulkExport:OptionLinkSets' => 'Voeg gelinkte objecten toe',
	'Core:BulkExport:OptionFormattedText' => 'Behoud tekstopmaak',
	'Core:BulkExport:ScopeDefinition' => 'Definitie van de te exporteren objecten',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook invoer:',
	'Core:BulkExportMessageEmptyOQL' => 'Gelieve een geldige OQL-query op te geven.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Gelieve een geldige Phrasebook-invoer op te geven',
	'Core:BulkExportQueryPlaceholder' => 'Typ hier een OQL-query...',
	'Core:BulkExportCanRunNonInteractive' => 'Klik hier om de export uit te voeren in non-interactieve mode',
	'Core:BulkExportLegacyExport' => 'Klik hier om de oude export te gebruiken',
	'Core:BulkExport:XLSXOptions' => 'Opties voor Excel',
	'Core:BulkExport:TextFormat' => 'Tekstvelden die HTML-opmaak bevatten',
	'Core:BulkExport:DateTimeFormat' => 'Datum- en tijdformaat',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Standaardformaat (%1$s), bv. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Aangepast formaat: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Pagina %1$s',
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
	'Core:Validator:Default' => 'Verkeerd formaat',
	'Core:Validator:Mandatory' => 'Gelieve dit veld in te vullen',
	'Core:Validator:MustBeInteger' => 'Dit moet een integer (geheel getal) zijn',
	'Core:Validator:MustSelectOne' => 'Gelieve één optie te kiezen',
	
	
));

//
// Class: TagSetFieldData
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TagSetFieldData' => '%2$s voor klasse %1$s',
	'Class:TagSetFieldData+' => '',

	'Class:TagSetFieldData/Attribute:code' => 'Code',
	'Class:TagSetFieldData/Attribute:code+' => 'Interne code. Moet minstens 3 alfanumerieke tekens bevatten.',
	'Class:TagSetFieldData/Attribute:label' => 'Label',
	'Class:TagSetFieldData/Attribute:label+' => 'Label dat getoond wordt',
	'Class:TagSetFieldData/Attribute:description' => 'Beschrijving',
	'Class:TagSetFieldData/Attribute:description+' => 'Beschrijving',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Tags in gebruik kunnen niet verwijderd worden',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Codes en labels voor tags moeten uniek zijn.',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Codes voor tags bestaan uit 3 tot %1$d alfanumerieke tekens',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'De gekozen code is een gereserveerd woord',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Het label mag niet \'%1$s\' bevatten en mag ook niet leeg zijn',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Codes kunnen niet aangepast worden als tags in gebruik zijn',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" kunnen niet aangepast worden',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Attribuut Code" kunnen niet aangepast worden',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Gebruik tags (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Geen invoer gevorden voor deze tag',
));
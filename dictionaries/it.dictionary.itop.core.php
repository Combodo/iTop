<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Core:DeletedObjectLabel' => '%1s (cancellato)',
	'Core:DeletedObjectTip' => 'L\'oggetto è stato cancellato il %1$s (%2$s)',
	'Core:UnknownObjectLabel' => 'Oggetto non trovato (classe: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'L\'oggetto non può essere trovato. Potrebbe essere stato cancellato tempo fa e il registro è stato purgato da allora.',
	'Core:UniquenessDefaultError' => 'Regola di unicità \'%1$s\' in errore',
	'Core:CheckConsistencyError' => 'Regole di coerenza non rispettate: %1$s',
	'Core:CheckValueError' => 'Valore inatteso per l\'attributo \'%1$s\' (%2$s): %3$s',
	'Core:AttributeLinkedSet' => 'Array di oggetti',
	'Core:AttributeLinkedSet+' => 'Ogni tipo di oggetto della stessa classe o sottoclasse',
	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicati nel campo \'%1$s\': %2$s',
	'Core:AttributeDashboard' => 'Dashboard~~',
	'Core:AttributeDashboard+' => '',
	'Core:AttributePhoneNumber' => 'Numero di telefono',
	'Core:AttributePhoneNumber+' => '',
	'Core:AttributeObsolescenceDate' => 'Data di obsolescenza',
	'Core:AttributeObsolescenceDate+' => '',
	'Core:AttributeTagSet' => 'Elenco di tag',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'clicca per aggiungere',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s da %3$s)',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s da classi figlie)',
	'Core:AttributeCaseLog' => 'Registro',
	'Core:AttributeCaseLog+' => '',
	'Core:AttributeMetaEnum' => 'Enum calcolato',
	'Core:AttributeMetaEnum+' => '',
	'Core:AttributeLinkedSetIndirect' => 'Array di oggetti (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'ogni tipo di oggetti [sottoclasse] della stessa classe',
	'Core:AttributeInteger' => 'Interger',
	'Core:AttributeInteger+' => 'Valore numerico (non può essere negativo)',
	'Core:AttributeDecimal' => 'Decimale',
	'Core:AttributeDecimal+' => 'Valore decimale (non può essere negativo)',
	'Core:AttributeBoolean' => 'Booleano',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Sì',
	'Core:AttributeBoolean/Value:no' => 'No',
	'Core:AttributeArchiveFlag' => 'Flag di archiviazione',
	'Core:AttributeArchiveFlag/Value:yes' => 'Sì',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Questo oggetto è visibile solo in modalità archivio',
	'Core:AttributeArchiveFlag/Value:no' => 'No',
	'Core:AttributeArchiveFlag/Label' => 'Archiviato',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Data di archiviazione',
	'Core:AttributeArchiveDate/Label+' => '',
	'Core:AttributeObsolescenceFlag' => 'Flag di obsolescenza',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Sì',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Questo oggetto è escluso dall\'analisi dell\'impatto ed è nascosto dai risultati della ricerca',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsoleto',
	'Core:AttributeObsolescenceFlag/Label+' => 'Calcolato dinamicamente su altri attributi',
	'Core:AttributeObsolescenceDate/Label' => 'Data di obsolescenza',
	'Core:AttributeObsolescenceDate/Label+' => 'Data approssimativa in cui l\'oggetto è stato considerato obsoleto',
	'Core:AttributeString' => 'Stringa',
	'Core:AttributeString+' => 'Stringa alfanumerica',
	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => '',
	'Core:AttributeApplicationLanguage' => 'Lingua dell\'applicazione',
	'Core:AttributeApplicationLanguage+' => 'Lingua e Paese (EN US)',
	'Core:AttributeFinalClass' => 'Classe finale (auto)',
	'Core:AttributeFinalClass+' => 'Classe effettiva dell\'oggetto (creata automaticamente dal core)',
	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password per un dispositivo',
	'Core:AttributeEncryptedString' => 'Stringa criptata',
	'Core:AttributeEncryptedString+' => 'Stringa criptata con una chiave locale',
	'Core:AttributeEncryptUnknownLibrary' => 'Libreria di crittografia specificata (%1$s) sconosciuta',
	'Core:AttributeEncryptFailedToDecrypt' => '** errore nella decrittazione **',
	'Core:AttributeText' => 'Testo',
	'Core:AttributeText+' => 'Stringa di caratteri multilinea',
	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Stringa HTML',
	'Core:AttributeEmailAddress' => 'Indirizzo Email',
	'Core:AttributeEmailAddress+' => '',
	'Core:AttributeIPAddress' => 'Indirizzo IP',
	'Core:AttributeIPAddress+' => '',
	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Espressione Object Query Langage',
	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Lista di stringe alfanumeriche predefinite',
	'Core:AttributeTemplateString' => 'Stringa Template',
	'Core:AttributeTemplateString+' => 'Segnaposto contenente stringhe',
	'Core:AttributeTemplateText' => 'Testo Template',
	'Core:AttributeTemplateText+' => 'Segnaposto contenente testo',
	'Core:AttributeTemplateHTML' => 'HTML Template',
	'Core:AttributeTemplateHTML+' => 'Segnaposto contenente HTML',
	'Core:AttributeDateTime' => 'Data/ora',
	'Core:AttributeDateTime+' => 'Data e ora (anno-mese-giorno hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Formato data:<br/>
	<b>%1$s</b><br/>
	Esempio: %2$s
</p>
<p>
Operatori:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>
<p>
Se \'oraè omessa, di default è 00:00:00
</p>',
	'Core:AttributeDate' => 'Data',
	'Core:AttributeDate+' => 'Data (anno-mese-giorno)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Formato data:<br/>
	<b>%1$s</b><br/>
	Esempio: %2$s
</p>
<p>
Operatori:<br/>
	<b>&gt;</b><em>data</em><br/>
	<b>&lt;</b><em>data</em><br/>
	<b>[</b><em>data</em>,<em>data</em><b>]</b>
</p>',
	'Core:AttributeDeadline' => 'Scadenza',
	'Core:AttributeDeadline+' => 'Data visualizza relativa al tempo attuale',
	'Core:AttributeExternalKey' => 'Chiave esterna',
	'Core:AttributeExternalKey+' => 'Chiave esterna (o straniera)',
	'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent~~',
	'Core:AttributeExternalField' => 'Campo esterno',
	'Core:AttributeExternalField+' => 'Campo mappato con una chiave esterna',
	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'URL assoluto o relativo, come una stringa di testo',
	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Ogni contenuto binario (documento)',
	'Core:AttributeOneWayPassword' => 'Password a senso unico',
	'Core:AttributeOneWayPassword+' => 'Password criptata (hashed) a senso unico',
	'Core:AttributeTable' => 'Tabella',
	'Core:AttributeTable+' => 'Array indicizzato a due dimensioni',
	'Core:AttributePropertySet' => 'Proprietà',
	'Core:AttributePropertySet+' => 'Elenco delle proprietà non tipizzati (nome and valore)',
	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => 'Attributo creato automaticamente, il nome descrittivo viene calcolato dopo diversi attributi',
	'Core:FriendlyName-Label' => 'Nome',
	'Core:FriendlyName-Description' => 'Friendly name',
	'Core:AttributeTag' => 'Tags~~',
	'Core:AttributeTag+' => '',
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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChange' => 'Cambio',
	'Class:CMDBChange+' => 'Rilevamento delle modifiche',
	'Class:CMDBChange/Attribute:date' => 'data',
	'Class:CMDBChange/Attribute:date+' => 'data e l\'ora in cui i cambiamenti sono stati registrati',
	'Class:CMDBChange/Attribute:userinfo' => 'misc. info',
	'Class:CMDBChange/Attribute:userinfo+' => 'informazioni definite dal richiedente',
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'User interaction in the GUI~~',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV import script~~',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'CSV import in the GUI~~',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email processing~~',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Synchro. data source~~',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webservices~~',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webservices~~',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'By an extension~~',
));

//
// Class: CMDBChangeOp
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOp' => 'Operazione di cambio',
	'Class:CMDBChangeOp+' => 'Rilevamento delle operazioni di cambio',
	'Class:CMDBChangeOp/Attribute:change' => 'cambio',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'data',
	'Class:CMDBChangeOp/Attribute:date+' => 'data e ora del cambio',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'utente',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'chi ha fatto questo cambio',
	'Class:CMDBChangeOp/Attribute:objclass' => 'classe oggetto',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'oggetto id',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tipo',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpCreate' => 'creazione oggetto',
	'Class:CMDBChangeOpCreate+' => 'Rilevamento creazione oggetto',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpDelete' => 'cancellazione oggetto',
	'Class:CMDBChangeOpDelete+' => 'Rilevamento cancellazione oggetto',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpSetAttribute' => 'cambio oggetto',
	'Class:CMDBChangeOpSetAttribute+' => 'Rilevamento modifiche delle proprietà dell\'oggetto',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Attributo',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'ccodice della proprietà modificata',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'proprietà cambio',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Rilevamento delle modifiche delle proprietà scalari dell\'oggetto',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Valore precedente',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'valore precedente dell\'attributo',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Nuovo valore',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'nuovo valore dell\'attributo',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Change:ObjectCreated' => 'Oggetto creato',
	'Change:ObjectDeleted' => 'Oggetto cancellato',
	'Change:ObjectModified' => 'Oggetto modificato',
	'Change:TwoAttributesChanged' => 'Edited %1$s and %2$s~~',
	'Change:ThreeAttributesChanged' => 'Edited %1$s, %2$s and 1 other~~',
	'Change:FourOrMoreAttributesChanged' => 'Edited %1$s, %2$s and %3$s others~~',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s settato a %2$s (valore precedente: %3$s)',
	'Change:AttName_SetTo' => '%1$s settato a  %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s allegato a %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificato, valore precedente: %2$s',
	'Change:AttName_Changed' => '%1$s modificato',
	'Change:AttName_EntryAdded' => '%1$s modificato, nuova voce aggiunta: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Changed from %2$s to %1$s~~',
	'Change:LinkSet:Added' => 'added %1$s~~',
	'Change:LinkSet:Removed' => 'removed %1$s~~',
	'Change:LinkSet:Modified' => 'modified %1$s~~',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'dati del cambio',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'rilevamento dati del cambio',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Dati precedente',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'contenuto precedente dell\'attributo',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOpSetAttributeText' => 'cambio	testo',
	'Class:CMDBChangeOpSetAttributeText+' => 'rilevamento cambio testo',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Dati precendenti',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'contenuto precedente dell\'attributo',
));

//
// Class: Event
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Event' => 'Log Evento',
	'Class:Event+' => 'Un\'applicazione evento interno',
	'Class:Event/Attribute:message' => 'Messagio',
	'Class:Event/Attribute:message+' => 'breve descrizione dell\'evento',
	'Class:Event/Attribute:date' => 'Data',
	'Class:Event/Attribute:date+' => 'data e ora a cui in cambio è stato registrato',
	'Class:Event/Attribute:userinfo' => 'Info Utente',
	'Class:Event/Attribute:userinfo+' => 'l\'identificazione dell\'utente che stava facendo l\'azione che ha attivato questo evento',
	'Class:Event/Attribute:finalclass' => 'Tipo',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventNotification' => 'Notifica dell\'evento',
	'Class:EventNotification+' => 'Traccia di una notifica che è stato inviato',
	'Class:EventNotification/Attribute:trigger_id' => 'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => 'account utente',
	'Class:EventNotification/Attribute:action_id' => 'utente',
	'Class:EventNotification/Attribute:action_id+' => 'account utente',
	'Class:EventNotification/Attribute:object_id' => 'Id oggetto',
	'Class:EventNotification/Attribute:object_id+' => 'Id oggetto (classe definita dal trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventNotificationEmail' => 'Emissione evento Email',
	'Class:EventNotificationEmail+' => 'Traccia di una e-mail che è stato inviata',
	'Class:EventNotificationEmail/Attribute:to' => 'A',
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'Da',
	'Class:EventNotificationEmail/Attribute:from+' => 'Mittente del messaggio',
	'Class:EventNotificationEmail/Attribute:subject' => 'Oggetto',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Attachments~~',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventIssue' => 'Evento Problematico',
	'Class:EventIssue+' => 'Traccia di un problema (avviso, errore, etc)',
	'Class:EventIssue/Attribute:issue' => 'Problema',
	'Class:EventIssue/Attribute:issue+' => 'Cosa è successo',
	'Class:EventIssue/Attribute:impact' => 'Impatto',
	'Class:EventIssue/Attribute:impact+' => 'Quali sono le conseguenze',
	'Class:EventIssue/Attribute:page' => 'Pagina',
	'Class:EventIssue/Attribute:page+' => 'Punto di ingresso HTTP',
	'Class:EventIssue/Attribute:arguments_post' => 'Argomenti inviati',
	'Class:EventIssue/Attribute:arguments_post+' => 'Argomenti POST HTTP',
	'Class:EventIssue/Attribute:arguments_get' => 'Argomenti URL',
	'Class:EventIssue/Attribute:arguments_get+' => 'Argomenti GET HTTP',
	'Class:EventIssue/Attribute:callstack' => 'Pila di chiamate',
	'Class:EventIssue/Attribute:callstack+' => '',
	'Class:EventIssue/Attribute:data' => 'Dati',
	'Class:EventIssue/Attribute:data+' => 'Informazioni aggiuntive',
));

//
// Class: EventWebService
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventWebService' => 'Evento di servizio web',
	'Class:EventWebService+' => 'Traccia di una chiamata di servizio web',
	'Class:EventWebService/Attribute:verb' => 'Verbo',
	'Class:EventWebService/Attribute:verb+' => 'Nome dell\'operazione',
	'Class:EventWebService/Attribute:result' => 'Risultato',
	'Class:EventWebService/Attribute:result+' => 'In generale successo/insuccesso',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Risultati info log',
	'Class:EventWebService/Attribute:log_warning' => 'Warning log',
	'Class:EventWebService/Attribute:log_warning+' => 'Risultati warning log',
	'Class:EventWebService/Attribute:log_error' => 'Error log',
	'Class:EventWebService/Attribute:log_error+' => 'Risultati error log',
	'Class:EventWebService/Attribute:data' => 'Dati',
	'Class:EventWebService/Attribute:data+' => 'Risultati dei dati',
));

Dict::Add('IT IT', 'Italian', 'Italiano', array(
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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventLoginUsage' => 'Uso Login',
	'Class:EventLoginUsage+' => 'Connessione all\'applicazione',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Indirizzo email dell\'utente',
));

//
// Class: Action
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Action' => 'Azione personalizzata',
	'Class:Action+' => 'Azione definita dall\'utente',
	'Class:Action/ComplementaryName' => '%1$s: %2$s~~',
	'Class:Action/Attribute:name' => 'Nome',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descrizione',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Stato',
	'Class:Action/Attribute:status+' => 'In produzione o ?',
	'Class:Action/Attribute:status/Value:test' => 'In fase di test',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'In produzione',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Inattivo',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'Triggers correlati',
	'Class:Action/Attribute:trigger_list+' => 'Triggers colleagati a questa azione',
	'Class:Action/Attribute:asynchronous' => 'Asynchronous~~',
	'Class:Action/Attribute:asynchronous+' => 'Whether this action should be executed in background or not~~',
	'Class:Action/Attribute:asynchronous/Value:use_global_setting' => 'Use global setting~~',
	'Class:Action/Attribute:asynchronous/Value:yes' => 'Yes~~',
	'Class:Action/Attribute:asynchronous/Value:no' => 'No~~',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Warning, no trigger is linked to the action. It will not be active until it has at least 1.~~',
));

//
// Class: ActionNotification
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ActionNotification' => 'Notifica',
	'Class:ActionNotification+' => 'Notifica (sommario)',
));

//
// Class: ActionEmail
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ActionEmail' => 'Email di notifica',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => 'Questo stato determina chi verrà notificato: solo il destinatario di prova, tutti (To, Cc e Bcc) o nessuno',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Solo il destinatario di prova verrà notificato',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'Tutte le email To, Cc e Bcc saranno notificate',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'La notifica via email non verrà inviata',
	'Class:ActionEmail/Attribute:test_recipient' => 'Destinatario di prova',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'Da',
	'Class:ActionEmail/Attribute:from+' => '',
	'Class:ActionEmail/Attribute:from_label' => 'Da (etichetta)',
	'Class:ActionEmail/Attribute:from_label+' => 'Il nome visualizzato del mittente verrà inviato nell\'intestazione dell\'email',
	'Class:ActionEmail/Attribute:reply_to' => 'Rispondi A',
	'Class:ActionEmail/Attribute:reply_to+' => '',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Rispondi a (etichetta)',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Il nome visualizzato del mittente di risposta verrà inviato nell\'intestazione dell\'email',
	'Class:ActionEmail/Attribute:to' => 'A',
	'Class:ActionEmail/Attribute:to+' => 'Destinatario dell\'email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Copia Carbone',
	'Class:ActionEmail/Attribute:bcc' => 'BCC',
	'Class:ActionEmail/Attribute:bcc+' => 'Copia Carbone Nascosta',
	'Class:ActionEmail/Attribute:subject' => 'Oggetto',
	'Class:ActionEmail/Attribute:subject+' => 'Titolo dell\'email',
	'Class:ActionEmail/Attribute:body' => 'Corpo',
	'Class:ActionEmail/Attribute:body+' => 'Contenuto dell\'email',
	'Class:ActionEmail/Attribute:importance' => 'Priorità',
	'Class:ActionEmail/Attribute:importance+' => '',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'Bassa',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'Normale',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'Alta',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
	'Class:ActionEmail/Attribute:language' => 'Lingua',
	'Class:ActionEmail/Attribute:language+' => 'Lingua da utilizzare per i segnaposto ($xxx$) all\'interno del messaggio (stato, importanza, priorità, ecc.)',
	'Class:ActionEmail/Attribute:html_template' => 'Template HTML',
	'Class:ActionEmail/Attribute:html_template+' => 'Template HTML opzionale che avvolge il contenuto dell\'attributo \'Corpo\' di seguito, utile per layout email personalizzati (nel template, il contenuto dell\'attributo \'Corpo\' sostituirà il segnaposto $content$)',
	'Class:ActionEmail/Attribute:ignore_notify' => 'Ignora il flag Notifica',
	'Class:ActionEmail/Attribute:ignore_notify+' => 'Se impostato su \'Sì\', il flag \'Notifica\' sui Contatti non avrà alcun effetto.',
	'Class:ActionEmail/Attribute:ignore_notify/Value:no' => 'No',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes' => 'Sì',
	'ActionEmail:main' => 'Messaggio',
	'ActionEmail:trigger' => 'Triggers',
	'ActionEmail:recipients' => 'Contatti',
	'ActionEmail:preview_tab' => 'Anteprima',
	'ActionEmail:preview_tab+' => 'Anteprima del modello di e-mail',
	'ActionEmail:preview_warning' => 'L\'e-mail effettiva potrebbe apparire diversa nel client di posta elettronica rispetto a questa anteprima nel tuo browser.',
	'ActionEmail:preview_more_info' => 'Per ulteriori informazioni sulle funzionalità CSS supportate dai diversi client di posta elettronica, consulta %1$s',
	'ActionEmail:content_placeholder_missing' => 'Il segnaposto "%1$s" non è stato trovato nel template HTML. Il contenuto del campo "%2$s" non verrà incluso nelle email generate.',
));

//
// Class: Trigger
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Gestore di eventi personalizzati',
	'Class:Trigger/ComplementaryName' => '%1$s, %2$s',
	'Class:Trigger/Attribute:description' => 'Descrizione',
	'Class:Trigger/Attribute:description+' => 'Una linea di descrizione',
	'Class:Trigger/Attribute:action_list' => 'Azioni triggerate',
	'Class:Trigger/Attribute:action_list+' => 'Azioni eseguite quando il trigger viene attivato',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Contesto',
	'Class:Trigger/Attribute:context+' => 'Contesto che consente al trigger di essere attivato',
	'Class:Trigger/Attribute:complement' => 'Informazioni aggiuntive',
	'Class:Trigger/Attribute:complement+' => 'Ulteriori informazioni fornite in inglese da questo trigger',

));

//
// Class: TriggerOnObject
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObject' => 'Trigger (classe dipendente)',
	'Class:TriggerOnObject+' => 'Trigger su una determinata classe di oggetti',
	'Class:TriggerOnObject/Attribute:target_class' => 'Classe Bersaglio',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filtro',
	'Class:TriggerOnObject/Attribute:filter+' => 'Limita l\'elenco degli oggetti (della classe bersaglio) che attiveranno il trigger',
	'TriggerOnObject:WrongFilterQuery' => 'Query di filtro errata: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'La query di filtro deve restituire oggetti della classe \\"%1$s\\"',

));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (dopo l\'aggiornamento dal portale )',
	'Class:TriggerOnPortalUpdate+' => 'Trigger sull\'aggiornamento dell\'utente dal portale',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnStateChange' => 'Trigger (su cambio stato)',
	'Class:TriggerOnStateChange+' => 'Trigger su cambio stato di un oggetto',
	'Class:TriggerOnStateChange/Attribute:state' => 'Stato',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnStateEnter' => 'Trigger (all\'entrata di uno stato)',
	'Class:TriggerOnStateEnter+' => 'Trigger su cambio stato di un oggetto - entrata',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnStateLeave' => 'Trigger (all\'uscita di uno stato)',
	'Class:TriggerOnStateLeave+' => 'Trigger su cambio stato di un oggetto - uscita',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObjectCreate' => 'Trigger (sulla creazione)',
	'Class:TriggerOnObjectCreate+' => 'Trigger sulla creazione di un oggetto [una classe figlia di] di una data classe',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (alla cancellazione dell\'oggetto)',
	'Class:TriggerOnObjectDelete+' => 'Trigger alla cancellazione dell\'oggetto di [una classe figlia della] classe specificata',

));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (alla modifica dell\'oggetto)',
	'Class:TriggerOnObjectUpdate+' => 'Trigger alla modifica dell\'oggetto di [una classe figlia della] classe specificata',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Campi di destinazione',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',

));

//
// Class: TriggerOnObjectMention
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObjectMention' => 'Trigger (alla menzione dell\'oggetto)',
	'Class:TriggerOnObjectMention+' => 'Trigger alla menzione (@xxx) di un oggetto di [una classe figlia della] classe specificata in un attributo di log',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Filtro menzionato',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limita l\'elenco degli oggetti menzionati che attiveranno il trigger. Se vuoto, qualsiasi oggetto menzionato (di qualsiasi classe) lo attiverà.',

));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Trigger (al download del documento dell\'oggetto)',
	'Class:TriggerOnAttributeBlobDownload+' => 'Trigger al download del campo documento dell\'oggetto di [una classe figlia della] classe specificata',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Campi di destinazione',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '',

));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (sulla soglia raggiunta)',
	'Class:TriggerOnThresholdReached+' => 'Trigger sulla soglia del cronometro raggiunta',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Cronometro',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Soglia',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',

));

//
// Class: lnkTriggerAction
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkTriggerAction' => 'Azione/Trigger',
	'Class:lnkTriggerAction+' => 'Collegamento tra trigger e azione',
	'Class:lnkTriggerAction/Attribute:action_id' => 'Azione',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Azione da eseguire',
	'Class:lnkTriggerAction/Attribute:action_name' => 'Azione',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Ordine',
	'Class:lnkTriggerAction/Attribute:order+' => 'Ordine di esecuzione delle azioni',
));

//
// Synchro Data Source
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SynchroDataSource' => 'Sorgente sincronizzazione dati',
	'Class:SynchroDataSource/Attribute:name' => 'Nome',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Descrizione',
	'Class:SynchroDataSource/Attribute:status' => 'Stato',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Classe bersaglio',
	'Class:SynchroDataSource/Attribute:scope_class+' => 'Una Fonte dati di sincronizzazione può popolare solo una singola classe '.ITOP_APPLICATION_SHORT.'',
	'Class:SynchroDataSource/Attribute:user_id' => 'Utente',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contatto a cui notificare',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contatto a cui notificare in caso di errore ',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icona del collegamento ipertestuale',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Una (piccola) immagine del collegamento ipertestuale che rappresenta l\'applicazione con cui è sincronizzato '.ITOP_APPLICATION_SHORT,
	'Class:SynchroDataSource/Attribute:url_application' => 'Collegamento ipertestuale all\'applicazione',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Collegamento ipertestuale all\'oggetto ITOP nell\'applicazione esterna con la quale QiTop è sincronizzato (se applicabile). Possibili segnaposto: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Policy di riconciliazione',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+' => '"Utilizza gli attributi": L\'oggetto '.ITOP_APPLICATION_SHORT.' corrisponde ai valori replica per ciascun attributo di sincronizzazione contrassegnato per la Conciliazione.
	"Utilizza la chiave primaria": si prevede che la colonna primary_key della replica contenga l\'identificatore dell\'oggetto '.ITOP_APPLICATION_SHORT.'',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Intervallo di pieno carico',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Una ricarica completa di tutti i dati deve verificarsi almeno come specificato qui',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Azione su zero',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Azione da eseguire quando la ricerca non restituisce alcun oggetto',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Azione su uno',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Azione da eseguire quando la ricerca restituisce esattamente un oggetto',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Azione su molti',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Azione da eseguire quando la ricerca restituisce più di un oggetto',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Utenti autorizati',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Chi è autorizato a cancellare gli oggetti sincronizzati',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nessuno',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Solo l\'amministratore',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Tutti gli utenti sono autorizzati',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Regole per l\'aggiornamento',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sintassi: nome_del_campo:valore; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Durata della conservazione',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Quanto tempo un oggetto obsoleto è tenuto prima di essere eliminato',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Nome della tabella per memorizzare i dati di sincronizzazione. Se lasciato vuoto, verrà calcolato un nome predefinito.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementazione',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Produzione',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Campo di restrizione',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Utilizzare gli attributi',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Utilizzare il campo della chiave primaria',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Crea',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Errore',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Errore',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Aggiornamento',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Crea',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Errore',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Prendi il primo (casualmente?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Policy di cancellazione',
	'Class:SynchroDataSource/Attribute:delete_policy+' => 'Cosa fare quando una replica diventa obsoleta:
	"Ignora": Non fare nulla, l\'oggetto associato rimane invariato in iTop.
	"Cancella": Cancella l\'oggetto associato in iTop (e la replica nella tabella dei dati).
	"Aggiorna": Aggiorna l\'oggetto associato come specificato dalle regole di aggiornamento (vedi sotto).
	"Aggiorna e cancella": applica le "Regole di aggiornamento". Quando scade la Durata di conservazione, esegui una "Cancellazione ',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Cancella',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignora',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Aggiorna',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Aggiorna e poi Cancella',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lista degli attributi',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Solo Amministratore',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Tutti sono autorizzati a cancellare gli oggetti',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nessuno',
	'SynchroDataSource:Description' => 'Descrizione',
	'SynchroDataSource:Reconciliation' => 'Ricerca &amp; reconciliazione',
	'SynchroDataSource:Deletion' => 'Regole di cancellazione',
	'SynchroDataSource:Status' => 'Stato',
	'SynchroDataSource:Information' => 'Informazione',
	'SynchroDataSource:Definition' => 'Definizione',
	'Core:SynchroAttributes' => 'Attributi',
	'Core:SynchroStatus' => 'Stato',
	'Core:Synchro:ErrorsLabel' => 'Errori',
	'Core:Synchro:CreatedLabel' => 'Creato',
	'Core:Synchro:ModifiedLabel' => 'Modificato',
	'Core:Synchro:UnchangedLabel' => 'Non Modificato',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errori',
	'Core:Synchro:ReconciledLabel' => 'Riconciliato',
	'Core:Synchro:ReconciledNewLabel' => 'Creato',
	'Core:SynchroReconcile:Yes' => 'Si',
	'Core:SynchroReconcile:No' => 'No',
	'Core:SynchroUpdate:Yes' => 'Si',
	'Core:SynchroUpdate:No' => 'No',
	'Core:Synchro:LastestStatus' => 'Ultimo stato',
	'Core:Synchro:History' => 'Storia della sincronizzazione',
	'Core:Synchro:NeverRun' => 'Questa sincronizzazione non è mai stata eseguita. Nessun Log ancora...',
	'Core:Synchro:SynchroEndedOn_Date' => 'L\'ultima sincronizzazione si è conclusa il %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'La sincronizzazione è iniziata il %1$s è ancora in esecuzione...',
	'Menu:DataSources' => 'Sorgente di sincronizzazione dei dati',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignorato(%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Scomparso (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Esistente (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nuovo (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Cancellato (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleto (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errori (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Nessuna Azione (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Non modificato(%1$s)',
	'Core:Synchro:label_obj_updated' => 'Aggiornato (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Errori (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Non modificato (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Aggiornato (%1$s)',
	'Core:Synchro:label_obj_created' => 'Creato (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Errori (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica processata: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Almeno una chiave riconciliazione deve essere specificata, o la policy di conciliazione deve essere quella di utilizzare la chiave primaria',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Deve essere specificato un periodo di conservazione di cancellazione , dato che gli oggetti devono essere eliminati dopo essere contrassegnati come obsoleti ',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Oggetti obsoleti devono essere aggiornati, ma nessun aggiornamento è specificato',
    'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'La tabella %1$s esiste già nel database. Si prega di utilizzare un altro nome per la tabella dei dati di sincronizzazione.',
	'Core:SynchroReplica:PublicData' => 'Dati Pubblici',
	'Core:SynchroReplica:PrivateDetails' => 'Dettagli Privati',
	'Core:SynchroReplica:BackToDataSource' => 'Torna indietro alla sorgente di sincronizzazione dei dati: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Lista della Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Chiave Primaria)',
	'Core:SynchroAtt:attcode' => 'Attributo',
	'Core:SynchroAtt:attcode+' => 'Campo dell\'oggetto',
	'Core:SynchroAtt:reconciliation' => 'Riconciliazione ?',
	'Core:SynchroAtt:reconciliation+' => 'Usato per la ricerca',
	'Core:SynchroAtt:update' => 'Aggiornamento ?',
	'Core:SynchroAtt:update+' => 'Usato per aggiornare l\'oggetto',
	'Core:SynchroAtt:update_policy' => 'Policy di aggiornamento',
	'Core:SynchroAtt:update_policy+' => 'Comportamento del campo aggiornato',
	'Core:SynchroAtt:reconciliation_attcode' => 'Chiave di riconciliazione',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Codice attributo per la chiave esterna di riconciliazione',
	'Core:SyncDataExchangeComment' => '(Scambio dati)',
	'Core:Synchro:ListOfDataSources' => 'Lista delle sorgenti di dati:',
	'Core:Synchro:LastSynchro' => 'Ultima sincronizzazione:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Questo oggetto è sincronizzato con una sorgente esterna di dati',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'L\'oggetti è stato <b>creato</b> da una sorgente esterna di dati %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'L\'oggetti <b>può essere cancellato</b> da una sorgente esterna di dati %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Tu <b>non puoi cancellare l\'oggetto</b> perché è di proprietà della sorgente dati esterna %1$s',
	'TitleSynchroExecution' => 'Esecuzione della sincronizzazione',
	'Class:SynchroDataSource:DataTable' => 'Tabella del database: %1$s',
	'Core:SyncDataSourceObsolete' => 'La fonte dei dati è contrassegnata come obsoleta. Operazione annullata',
	'Core:SyncDataSourceAccessRestriction' => 'Solo amministratori o l\'utente specificato nella fonte dei dati può eseguire questa operazione. Operazione annullata',
	'Core:SyncTooManyMissingReplicas' => 'Tutte le repliche sono mancanti dall\'importazione. Hai eseguito realmente l\'importazione? Operazione annullata',
	'Core:SyncSplitModeCLIOnly' => 'La sincronizzazione può essere eseguita in blocchi solo se eseguito in modalità CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s repliche, %2$s errore(i), %3$s warning(s).',
	'Core:SynchroReplica:TargetObject' => 'Oggetto Sincronizzato: %1$s',
	'Class:AsyncSendEmail' => 'Email (asincrono)',
	'Class:AsyncSendEmail/Attribute:to' => 'A',
	'Class:AsyncSendEmail/Attribute:subject' => 'Oggetto',
	'Class:AsyncSendEmail/Attribute:body' => 'Corpo',
	'Class:AsyncSendEmail/Attribute:header' => 'Intestazione',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Password criptata',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Valore Precedente',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Campo criptato',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Valore Precedente',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Log della casistica',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Ultima voce',
	'Class:SynchroAttribute' => 'Attributo di sincronizzazione',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Sorgente dati per la sincronizzazione',
	'Class:SynchroAttribute/Attribute:attcode' => 'Codice Attributo',
	'Class:SynchroAttribute/Attribute:update' => 'Aggiorna',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Riconcilia',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Policy di Aggiornamento',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Bloccato',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Sbloccato',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Inizializza se vuoto',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Classe',
	'Class:SynchroAttExtKey' => 'Attributo di sincronizzazione (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Attributo di riconciliazione',
	'Class:SynchroAttLinkSet' => 'Attributo di sincronizzazione (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Separatore di riga',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributi separatori',
	'Class:SynchroLog' => 'Sincro Log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Sorgente dati per la sincronizzazione',
	'Class:SynchroLog/Attribute:start_date' => 'Data di Inizio',
	'Class:SynchroLog/Attribute:end_date' => 'Data di Fine',
	'Class:SynchroLog/Attribute:status' => 'Stato',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completo',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Errore',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Ancora in esecuzione',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'N. di replica viste',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'N. di replica totali',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'N. di oggetti cancellati',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'N. di errori durante l\'eliminazione',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'N. di oggetti obsoleti',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'N. di errori durante l\'invecchiamento',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'N. di oggetti creati',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'N. di errori durante la creazione',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'N. di oggetti aggiornati',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'N. di errori durante l\'aggiornamento',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'N. di errori durante la riconcilazione',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'N. di repliche scomparse',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'N. di oggetti aggiornati',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'N. di oggetti non modificati',
	'Class:SynchroLog/Attribute:last_error' => 'Ultimo eroore',
	'Class:SynchroLog/Attribute:traces' => 'Tracce',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Sorgente dati per la sincronizzazione',
	'Class:SynchroReplica/Attribute:dest_id' => 'Oggetto di destinazione (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Tipo di destinazione',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Ultimo visto',
	'Class:SynchroReplica/Attribute:status' => 'Stato',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modificato',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Nuovo',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orfano',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Sincronizzato',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Oggetto creato ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Utimo errore',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Avvisi',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data di creazione',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Data di ultima modifica',
	'Class:appUserPreferences' => 'Preferenze utente',
	'Class:appUserPreferences/Attribute:userid' => 'Utente',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs',
	'Core:ExecProcess:Code1' => 'Comando errato o comando finito con errori (es. errato nome dello script)',
    'Core:ExecProcess:Code255' => 'Errore PHP (parsing o runtime)',
    // Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$sec',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sg %2$dh %3$dmin %4$ds',
    // Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Tempo trascorso (memorizzato come \\"%1$s\\")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Tempo trascorso per \\"%1$s\\"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Scadenza per \\"%1$s\\" al %2$d%%',

    // Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Parametro mancante \\"%1$s\\"',
	'Core:BulkExport:InvalidParameter_Query' => 'Valore non valido per il parametro \\"query\\". Non esiste un Query Phrasebook corrispondente all\'ID: \\"%1$s\\".',
	'Core:BulkExport:ExportFormatPrompt' => 'Formato di esportazione:',
	'Core:BulkExportOf_Class' => 'Esporta %1$s',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Clicca qui per scaricare %1$s',
	'Core:BulkExport:ExportResult' => 'Risultato dell\'esportazione:',
	'Core:BulkExport:RetrievingData' => 'Recupero dei dati...',
	'Core:BulkExport:HTMLFormat' => 'Pagina Web (*.html)',
	'Core:BulkExport:CSVFormat' => 'Valori separati da virgola (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 o successivo (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'Documento PDF (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Trascina e rilascia gli header delle colonne per organizzare le colonne. Anteprima di %1$s righe. Numero totale di righe da esportare: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Seleziona almeno una colonna da esportare dalla lista sopra',
	'Core:BulkExport:ColumnsOrder' => 'Ordine delle colonne',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Colonnette disponibili da %1$s',
	'Core:BulkExport:NoFieldSelected' => 'Seleziona almeno una colonna da esportare',
	'Core:BulkExport:CheckAll' => 'Seleziona tutto',
	'Core:BulkExport:UncheckAll' => 'Deseleziona tutto',
	'Core:BulkExport:ExportCancelledByUser' => 'Esportazione annullata dall\'utente',
	'Core:BulkExport:CSVOptions' => 'Opzioni CSV',
	'Core:BulkExport:CSVLocalization' => 'Localizzazione',
	'Core:BulkExport:PDFOptions' => 'Opzioni PDF',
	'Core:BulkExport:PDFPageFormat' => 'Formato Pagina',
	'Core:BulkExport:PDFPageSize' => 'Dimensioni Pagina:',
	'Core:BulkExport:PageSize-A4' => 'A4~~',
	'Core:BulkExport:PageSize-A3' => 'A3~~',
	'Core:BulkExport:PageSize-Letter' => 'Letter~~',
	'Core:BulkExport:PDFPageOrientation' => 'Orientamento Pagina:',
	'Core:BulkExport:PageOrientation-L' => 'Orizzontale',
	'Core:BulkExport:PageOrientation-P' => 'Verticale',
	'Core:BulkExport:XMLFormat' => 'File XML (*.xml)',
	'Core:BulkExport:XMLOptions' => 'Opzioni XML',
	'Core:BulkExport:SpreadsheetFormat' => 'Formato HTML per foglio di calcolo (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Opzioni Foglio di calcolo',
	'Core:BulkExport:OptionNoLocalize' => 'Esporta Codice invece di Etichetta',
	'Core:BulkExport:OptionLinkSets' => 'Includi oggetti collegati',
	'Core:BulkExport:OptionFormattedText' => 'Preserva la formattazione del testo',
	'Core:BulkExport:ScopeDefinition' => 'Definizione degli oggetti da esportare',
	'Core:BulkExportLabelOQLExpression' => 'Query OQL:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Voce del Phrasebook della Query:',
	'Core:BulkExportMessageEmptyOQL' => 'Inserisci una query OQL valida.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Seleziona una voce valida del phrasebook della query.',
	'Core:BulkExportQueryPlaceholder' => 'Digita una query OQL qui...',
	'Core:BulkExportCanRunNonInteractive' => 'Fai clic qui per eseguire l\'esportazione in modalità non interattiva.',
	'Core:BulkExportLegacyExport' => 'Fai clic qui per accedere all\'esportazione legacy.',
	'Core:BulkExport:XLSXOptions' => 'Opzioni Excel',
	'Core:BulkExport:TextFormat' => 'Campi di testo contenenti markup HTML',
	'Core:BulkExport:DateTimeFormat' => 'Formato data e ora',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Formato predefinito (%1$s), ad esempio %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Formato personalizzato: %1$s',
	'Core:BulkExport:PDF:PageNumber' => 'Pagina %1$s',
	'Core:DateTime:Placeholder_d' => 'GG',

    // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'G',
    // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM',
    // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M',
    // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'AAAA',
    // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'AA',
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
	'Core:Validator:Default' => 'Formato errato',
	'Core:Validator:Mandatory' => 'Per favore, compila questo campo',
	'Core:Validator:MustBeInteger' => 'Deve essere un numero intero',
	'Core:Validator:MustSelectOne' => 'Per favore, seleziona uno',

));

//
// Class: TagSetFieldData
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TagSetFieldData' => '%2$s per la classe %1$s',
	'Class:TagSetFieldData+' => '',
	'Class:TagSetFieldData/Attribute:code' => 'Codice',
	'Class:TagSetFieldData/Attribute:code+' => 'Codice interno. Deve contenere almeno 3 caratteri alfanumerici',
	'Class:TagSetFieldData/Attribute:label' => 'Etichetta',
	'Class:TagSetFieldData/Attribute:label+' => 'Etichetta visualizzata',
	'Class:TagSetFieldData/Attribute:description' => 'Descrizione',
	'Class:TagSetFieldData/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Classe tag',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Classe oggetto',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Codice campo',
	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'I tag utilizzati non possono essere eliminati',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'I codici o le etichette dei tag devono essere unici',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Il codice del tag deve contenere tra 3 e %1$d caratteri alfanumerici',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'Il codice del tag scelto è una parola riservata',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'L\'etichetta del tag non deve contenere \'%1$s\' né essere vuota',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Il codice del tag non può essere cambiato quando è in uso',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'La "Classe oggetto" dei tag non può essere cambiata',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Il "Codice attributo" dei tag non può essere cambiato',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Utilizzo tag (%1$d)',
	'Core:TagSetFieldData:NoEntryFound' => 'Nessuna voce trovata per questo tag',

));

//
// Class: DBProperty
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DBProperty' => 'Proprietà DB',
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Nome',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Descrizione',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Valore',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Data di modifica',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Commento modifica',
	'Class:DBProperty/Attribute:change_comment+' => '',

));

//
// Class: BackgroundTask
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:BackgroundTask' => 'Task in background',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Nome della classe',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'Data della prima esecuzione',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Data dell\'ultima esecuzione',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Data della prossima esecuzione',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Totale esecuzioni',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Durata dell\'ultima esecuzione',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Durata minima di esecuzione',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Durata massima di esecuzione',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Durata media di esecuzione',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'In esecuzione',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Stato',
	'Class:BackgroundTask/Attribute:status+' => '',

));

//
// Class: AsyncTask
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:AsyncTask' => 'Attività asincrona',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Creata',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Iniziata',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Pianificata',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Evento',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Classe finale',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Stato',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Tentativi rimanenti',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Ultimo codice di errore',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Ultimo errore',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Ultimo tentativo',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
	'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Formato non valido per la configurazione di "async_task_retries[%1$s]". Ci si aspetta un array con le seguenti chiavi: %2$s',
	'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Formato non valido per la configurazione di "async_task_retries[%1$s]": chiave "%2$s" inaspettata. Ci si aspetta solo le seguenti chiavi: %3$s',

));

//
// Class: AbstractResource
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:AbstractResource' => 'Risorsa Astratta', // o 'Risorsa di Base'
	'Class:AbstractResource+' => '',

));

//
// Class: ResourceAdminMenu
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ResourceAdminMenu' => 'Menu di Amministrazione delle Risorse',
	'Class:ResourceAdminMenu+' => '',
		
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ResourceRunQueriesMenu' => 'Menu Esegui Query Risorse',
	'Class:ResourceRunQueriesMenu+' => '',

));

//
// Class: Action
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ResourceSystemMenu' => 'Menu di Sistema delle Risorse',
	'Class:ResourceSystemMenu+' => '',
		
));




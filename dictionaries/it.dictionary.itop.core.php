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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Core:AttributeLinkedSet' => 'Array di oggetti',
	'Core:AttributeLinkedSet+' => 'Ogni tipo di oggetto della stessa classe o sottoclasse',

	'Core:AttributeLinkedSetIndirect' => 'Array di oggetti (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'ogni tipo di oggetti [sottoclasse] della stessa classe',

	'Core:AttributeInteger' => 'Integer',
	'Core:AttributeInteger+' => 'Valore numerico (non può essere negativo)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'valore decimale (non può essere negativo)',

	'Core:AttributeBoolean' => 'Booleano',
	'Core:AttributeBoolean+' => 'Booleano',

	'Core:AttributeString' => 'Stringa',
	'Core:AttributeString+' => 'Stringa alfanumerica',

	'Core:AttributeClass' => 'Classe',
	'Core:AttributeClass+' => 'Classe',

	'Core:AttributeApplicationLanguage' => 'Lingua Utente',
	'Core:AttributeApplicationLanguage+' => 'Lingua e Paese (EN US)',

	'Core:AttributeFinalClass' => 'Classe (auto)',
	'Core:AttributeFinalClass+' => 'Classe reale dell\'oggetto (automaticamente creata dal core)',

	'Core:AttributePassword' => 'Password',
	'Core:AttributePassword+' => 'Password per un dispositivo',

 	'Core:AttributeEncryptedString' => 'Stringa criptata',
	'Core:AttributeEncryptedString+' => 'Stringa cripta con una chiave locale',

	'Core:AttributeText' => 'Testo',
	'Core:AttributeText+' => 'Stringa di caratteri multilinea',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'Stringa HTML',

	'Core:AttributeEmailAddress' => 'Indirizzo Email',
	'Core:AttributeEmailAddress+' => 'Indirizzo Email',

	'Core:AttributeIPAddress' => 'Indirizzo IP',
	'Core:AttributeIPAddress+' => 'Indirizzo IP',

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
	<b>aaaa-mm-gg hh:mm:ss</b><br/>
	Esempio: 2011-07-19 18:40:00
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
	<b>aaaa-mm-gg</b><br/>
	Esempio: 2011-07-19
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
));

//
// Class: CMDBChangeOp
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:CMDBChangeOp' => 'Operazione di cambio',
	'Class:CMDBChangeOp+' => 'Rilevamento delle operazioni di cambio',
	'Class:CMDBChangeOp/Attribute:change' => 'cambio',
	'Class:CMDBChangeOp/Attribute:change+' => 'cambio',
	'Class:CMDBChangeOp/Attribute:date' => 'data',
	'Class:CMDBChangeOp/Attribute:date+' => 'data e ora del cambio',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'utente',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'chi ha fatto questo cambio',
	'Class:CMDBChangeOp/Attribute:objclass' => 'classe oggetto',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'classe oggetto',
	'Class:CMDBChangeOp/Attribute:objkey' => 'oggetto id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'ooggetto id',
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
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s settato a %2$s (valore precedente: %3$s)',
	'Change:AttName_SetTo' => '%1$s settato a  %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s allegato a %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modificato, valore precedente: %2$s',
	'Change:AttName_Changed' => '%1$s modificato',
	'Change:AttName_EntryAdded' => '%1$s modificato, nuova voce aggiunta.',
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
	'Class:EventNotificationEmail/Attribute:to+' => 'A',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'Da',
	'Class:EventNotificationEmail/Attribute:from+' => 'Mittente del messaggio',
	'Class:EventNotificationEmail/Attribute:subject' => 'Oggetto',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Oggetto',
	'Class:EventNotificationEmail/Attribute:body' => 'Corpo',
	'Class:EventNotificationEmail/Attribute:body+' => 'Corpo',
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
	'Class:EventIssue/Attribute:callstack+' => 'Pila di chiamate',
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

//
// Class: EventLoginUsage
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:EventLoginUsage' => 'Uso Login',
	'Class:EventLoginUsage+' => 'Connessione all\'applicazione',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'User Name',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Indirizzo email dell\'utente',
));

//
// Class: Action
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Action' => 'Azione personalizzata',
	'Class:Action+' => 'Azione definita dall\'utente',
	'Class:Action/Attribute:name' => 'Nome',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Descrizione',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Stato',
	'Class:Action/Attribute:status+' => 'In produzione o ?',
	'Class:Action/Attribute:status/Value:test' => 'In fase di test',
	'Class:Action/Attribute:status/Value:test+' => 'In fase di test',
	'Class:Action/Attribute:status/Value:enabled' => 'In produzione',
	'Class:Action/Attribute:status/Value:enabled+' => 'In produzione',
	'Class:Action/Attribute:status/Value:disabled' => 'Inattivo',
	'Class:Action/Attribute:status/Value:disabled+' => 'Inattivo',
	'Class:Action/Attribute:trigger_list' => 'Triggers correlati',
	'Class:Action/Attribute:trigger_list+' => 'Triggers colleagati a questa azione',
	'Class:Action/Attribute:finalclass' => 'Tipo',
	'Class:Action/Attribute:finalclass+' => '',
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
	'Class:ActionEmail/Attribute:test_recipient' => 'Test destinatario',
	'Class:ActionEmail/Attribute:test_recipient+' => '',
	'Class:ActionEmail/Attribute:from' => 'Da',
	'Class:ActionEmail/Attribute:from+' => '',
	'Class:ActionEmail/Attribute:reply_to' => 'Rispondi A',
	'Class:ActionEmail/Attribute:reply_to+' => '',
	'Class:ActionEmail/Attribute:to' => 'A',
	'Class:ActionEmail/Attribute:to+' => 'Destinatario dell\'email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Copia Carbone',
	'Class:ActionEmail/Attribute:bcc' => 'BCC',
	'Class:ActionEmail/Attribute:bcc+' => 'Copia Carbone Nascosta',
	'Class:ActionEmail/Attribute:subject' => 'Oggetto',
	'Class:ActionEmail/Attribute:subject+' => 'Titolo dell\'email',
	'Class:ActionEmail/Attribute:body' => 'corpo',
	'Class:ActionEmail/Attribute:body+' => 'Contenuto dell\'email',
	'Class:ActionEmail/Attribute:importance' => 'Priorità',
	'Class:ActionEmail/Attribute:importance+' => 'Priorità',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'bassa',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'bassa',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normale',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normale',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'alta',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'alta',
));

//
// Class: Trigger
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Trigger' => 'Trigger',
	'Class:Trigger+' => 'Gestore di eventi personalizzati',
	'Class:Trigger/Attribute:description' => 'Descrizione',
	'Class:Trigger/Attribute:description+' => 'una linea di descrizione',
	'Class:Trigger/Attribute:action_list' => 'Azioni triggerate',
	'Class:Trigger/Attribute:action_list+' => 'Azioni eseguite quando il trigger viene attivato ',
	'Class:Trigger/Attribute:finalclass' => 'Tipo',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnObject' => 'Trigger (classe dipendente)',
	'Class:TriggerOnObject+' => 'Trigger su una determinata classe di oggetti',
	'Class:TriggerOnObject/Attribute:target_class' => 'Classe Bersaglio',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
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
	'Class:SynchroDataSource/Attribute:name' => 'Nome',
	'Class:SynchroDataSource/Attribute:name+' => 'Nome',
	'Class:SynchroDataSource/Attribute:description' => 'Descrizione',
	'Class:SynchroDataSource/Attribute:status' => 'Stato', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => 'Classe bersaglio',
	'Class:SynchroDataSource/Attribute:user_id' => 'Utente',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contatto a cui notificare',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contatto a cui notificare in caso di errore ',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icona del collegamento ipertestuale',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Una (piccola) immagine del collegamento ipertestuale che rappresenta l\'applicazione con cui è sincronizzato QiTop',
	'Class:SynchroDataSource/Attribute:url_application' => 'Collegamento ipertestuale all\'applicazione',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Collegamento ipertestuale all\'oggetto ITOP nell\'applicazione esterna con la quale QiTop è sincronizzato (se applicabile). Possibili segnaposto: $this->attribute$ e $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Policy di riconciliazione', //TODO enum values
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
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Utenti autorizati',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nessuno',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Solo l\'amministratore',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Tutti gli utenti sono autorizzati',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Regole per l\'aggiornamento',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sintassi: nome_del_campo:valore; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Durata della conservazione',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Quanto tempo un oggetto obsoleto è tenuto prima di essere eliminato',
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
	'Core:Synchro:SynchroEndedOn_Date' => 'L\'ultima sincronizzazione si è conclusa il %1$s.~~',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'La sincronizzazione è iniziata il %1$s è ancora in esecuzione...~~',
	'Menu:DataSources' => 'Sorgente di sincronizzazione dei dati', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignorato(%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Scomparso (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Esistente (%1$s)',
	'Core:Synchro:label_repl_new' => 'Nuovo (%1$s)~~',
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
	'Core:Synchro:History' => 'Storia della sincronizzazione',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica processata: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'Almeno una chiave riconciliazione deve essere specificata, o la policy di conciliazione deve essere quella di utilizzare la chiave primaria',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Deve essere specificato un periodo di conservazione di cancellazione , dato che gli oggetti devono essere eliminati dopo essere contrassegnati come obsoleti ',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Oggetti obsoleti devono essere aggiornati, ma nessun aggiornamento è specificato',
	'Core:SynchroReplica:PublicData' => 'Dati Pubblici',
	'Core:SynchroReplica:PrivateDetails' => 'Dettagli Privati',
	'Core:SynchroReplica:BackToDataSource' => 'Torna indietro alla sorgente di sincronizzazione dei dati: %1$s~~',
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
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'L\'oggetti è stato <b>creato</b> da una sorgente esterna di dati %1$s~~',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'L\'oggetti <b>può essere cancellato</b> da una sorgente esterna di dati %1$s~~',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'Tu <b>non puoi cancellare l\'oggetto</b> perché è di proprietà della sorgente dati esterna %1$s~~',
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
	'Class:SynchroDataSource' => 'Sorgente sincronizzazione dati',
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
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Cancella',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignora',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Aggiorna',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Aggiorna e poi Cancella',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Lista degli attributi',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Solo Amministratore',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Tutti sono autorizzati a cancellare gli oggetti',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nessuno',
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
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Warnings',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Data di creazione',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Data di ultima modifica',
	'Class:appUserPreferences' => 'Preferenze utente',
	'Class:appUserPreferences/Attribute:userid' => 'Utente',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs',
	'Core:ExecProcess:Code1' => 'Comando errato o comando finito con errori (es. errato nome dello script)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)',
));

//
// Attribute Duration
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$sec',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sg %2$dh %3$dmin %4$ds',
));

?>

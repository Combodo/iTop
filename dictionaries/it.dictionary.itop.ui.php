<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:AuditCategory' => 'Categoria di Audit',
	'Class:AuditCategory+' => 'Una sezione all\'interno dell\'audit globale',
	'Class:AuditCategory/Attribute:name' => 'Nome Categoria',
	'Class:AuditCategory/Attribute:name+' => 'Nome breve per questa categoria',
	'Class:AuditCategory/Attribute:description' => 'Descrizione Categoria Audit',
	'Class:AuditCategory/Attribute:description+' => 'Descrizione lunga per questa categoria di audit',
	'Class:AuditCategory/Attribute:definition_set' => 'Insieme di definizione',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL espressione che definisce l\'insieme di oggetti da controllare',
	'Class:AuditCategory/Attribute:rules_list' => 'Regole di Audit',
	'Class:AuditCategory/Attribute:rules_list+' => 'Regole di Audit per questa categoria',
));

//
// Class: AuditRule
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:AuditRule' => 'Regole di Audit',
	'Class:AuditRule+' => 'Una regola per verificare una determinata categoria di audit',
	'Class:AuditRule/Attribute:name' => 'Rule Name',
	'Class:AuditRule/Attribute:name+' => 'Nome breve per questa regola',
	'Class:AuditRule/Attribute:description' => 'escrizione della regola di Audit',
	'Class:AuditRule/Attribute:description+' => 'Descrizione lunga per questa regola di audit',
	'Class:AuditRule/Attribute:query' => 'Query to Run',
	'Class:AuditRule/Attribute:query+' => 'L\'espressione OQL da richiedere',
	'Class:AuditRule/Attribute:valid_flag' => 'Oggetti Validi?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Vero se la norma restituisce gli oggetti validi, falso altrimenti',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'vero',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'vero',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'falso',
	'Class:AuditRule/Attribute:category_id' => 'Categoria',
	'Class:AuditRule/Attribute:category_id+' => 'La categoria per questa regola',
	'Class:AuditRule/Attribute:category_name' => 'Categoria',
	'Class:AuditRule/Attribute:category_name+' => 'Nome della categoria per questa regola',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:User' => 'Utente',
	'Class:User+' => 'Login Utente',
	'Class:User/Attribute:finalclass' => 'Tipo di account',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Contatto (persona)',
	'Class:User/Attribute:contactid+' => 'I dati personali sulla base dei dati aziendali',
	'Class:User/Attribute:last_name' => 'Cognome',
	'Class:User/Attribute:last_name+' => 'Nome del contatto corrispondente',
	'Class:User/Attribute:first_name' => 'Nome',
	'Class:User/Attribute:first_name+' => 'Nome del contatto corrispondente',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email del contatto corrispondente',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Stringa di identificazione dell\'utente',
	'Class:User/Attribute:language' => 'Lingua',
	'Class:User/Attribute:language+' => 'Lingua utente',
	'Class:User/Attribute:language/Value:IT IT' => 'Italian',
	'Class:User/Attribute:language/Value:IT IT+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profili',
	'Class:User/Attribute:profile_list+' => 'Ruoli e concessioni dei diritti per la persona',
	'Class:User/Attribute:allowed_org_list' => 'Organizzazioni Consentite',
	'Class:User/Attribute:allowed_org_list+' => 'All\'utente finale è consentito di vedere i dati appartenenti alle seguenti organizzazioni. Se non si specifica organizzazione, non vi è alcuna restrizione.',

	'Class:User/Error:LoginMustBeUnique' => 'Il login deve essere unico - "%1s" è già in uso.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Almeno un profilo deve essere assegnato a questo utente.',
));

//
// Class: URP_Profiles
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_Profiles' => 'Profilo',
	'Class:URP_Profiles+' => 'Profilo Utente',
	'Class:URP_Profiles/Attribute:name' => 'Nome',
	'Class:URP_Profiles/Attribute:name+' => 'etichetta',
	'Class:URP_Profiles/Attribute:description' => 'Descrizione',
	'Class:URP_Profiles/Attribute:description+' => 'Una linea di descrizione',
	'Class:URP_Profiles/Attribute:user_list' => 'Utenti',
	'Class:URP_Profiles/Attribute:user_list+' => 'Persone che hanno questo ruolo',
));

//
// Class: URP_Dimensions
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_Dimensions' => 'Dimensione',
	'Class:URP_Dimensions+' => 'Dimensione dell\'aplicazione(silos definire)',
	'Class:URP_Dimensions/Attribute:name' => 'Nome',
	'Class:URP_Dimensions/Attribute:name+' => 'etichetta',
	'Class:URP_Dimensions/Attribute:description' => 'Descrizione',
	'Class:URP_Dimensions/Attribute:description+' => 'Una linea di descrizione',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'nome della classe o tipo di dati (unità di proiezione)',
));

//
// Class: URP_UserProfile
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_UserProfile' => 'Profilo utente',
	'Class:URP_UserProfile+' => 'Profilo utente',
	'Class:URP_UserProfile/Attribute:userid' => 'Utente',
	'Class:URP_UserProfile/Attribute:userid+' => 'Account utente',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Login Utente',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profilo',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Profilo utente',
	'Class:URP_UserProfile/Attribute:profile' => 'Profilo',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nome profilo',
	'Class:URP_UserProfile/Attribute:reason' => 'Motivo',
	'Class:URP_UserProfile/Attribute:reason+' => 'spiegare perché questa persona può avere questo ruolo',
));

//
// Class: URP_UserOrg
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_UserOrg' => 'Organizzazione dell\'utente',
	'Class:URP_UserOrg+' => 'Organizzazioni Consentite',
	'Class:URP_UserOrg/Attribute:userid' => 'Utente',
	'Class:URP_UserOrg/Attribute:userid+' => 'Account utente',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login utente',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizzazione',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organizzazioni Consentite',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organizzazione',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organizzazioni Consentite',
	'Class:URP_UserOrg/Attribute:reason' => 'Motivo',
	'Class:URP_UserOrg/Attribute:reason+' => 'spiegare perché questa persona è consentito di vedere i dati appartenenti a questa organizzazione',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profile name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'permissions on classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profile',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_ActionGrant/Attribute:class' => 'Class',
	'Class:URP_ActionGrant/Attribute:class+' => 'Target class',
	'Class:URP_ActionGrant/Attribute:permission' => 'Permission',
	'Class:URP_ActionGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Action',
	'Class:URP_ActionGrant/Attribute:action+' => 'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profile',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:class' => 'Class',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Target class',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Permission',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'allowed or not allowed?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'permissions at the attributes level',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Action grant',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'action grant',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribute',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribute code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Menu:WelcomeMenu' => 'Benvenuto',
	'Menu:WelcomeMenu+' => 'Benvenuto su iTop',
	'Menu:WelcomeMenuPage' => 'Benvenuto',
	'Menu:WelcomeMenuPage+' => 'Benvenuto su iTop',
	'UI:WelcomeMenu:Title' => 'Benvenuto su iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop è un completo Portale Funzionale IT, Open Source.</p>
<ul>Esso include:
<li>Un completo CMDB (Configuration management database) per documentare e gestire l\'IT di inventario.</li>
<li>Un modulo di gestione degli incidenti per monitorare e comunicare su tutte le questioni che si verificano nel settore IT.</li>
<li>Un modulo di gestione delle modifiche per pianificare e monitorare i cambiamenti all\'ambiente IT.</li>
<li>Una banca dati errori noti per accelerare la risoluzione di incidenti.</li>
<li>Un modulo di interruzione per documentare tutte le interruzioni pianificate e notificare gli opportuni contatti.</li>
<li>Una dashboard per ottenere rapidamente una panoramica del sistema IT.</li>
</ul>
<p>Tutti i moduli possono essere installati, passo dopo passo, indipendentemente l\'uno dall\'altro.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop è fornitore di servizi di orientamento, che consente ai progettisti di gestire più o organizzazioni o clienti con facilità .
<ul>iTop, offre un set ricco di funzionalità dei processi di business che:
<li>Migliora l\'efficacia di gestione IT</li> 
<li>Guida le prestazione delle operazioni IT</li> 
<li>Migliora la soddisfazione del cliente e fornisce ai dirigenti un idea della performance del business.</li>
</ul>
</p>
<p>iTop è completamente aperto per essere integrato all\'interno della vostra infrastruttura di gestione dell\'IT.</p>
<p>
<ul>L\'adozione di questa nuova generazione di portale funzionale IT vi aiuterà a:
<li>Meglio gestire un ambiente IT sempre più complesso.</li>
<li>Implementare i processi ITIL al proprio ritmo.</li>
<li>Gestire la risorsa più importante della tua IT: Documentazione.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Aprire le richieste: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Le mie richieste',
	'UI:WelcomeMenu:OpenIncidents' => 'Apri gli incidenti: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuration Items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidenti assegnati a me',
	'UI:AllOrganizations' => ' Tutte le Organizzazioni ',
	'UI:YourSearch' => 'La tua ricerca',
	'UI:LoggedAsMessage' => 'Logged come %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logged come %1$s (Administrator)',
	'UI:Button:Logoff' => 'Log off',
	'UI:Button:GlobalSearch' => 'Ricerca',
	'UI:Button:Search' => ' Ricerca ',
	'UI:Button:Query' => ' Domanda ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Cancella',
	'UI:Button:Apply' => 'Applica',
	'UI:Button:Back' => ' << Indietro',
	'UI:Button:Restart' => ' |<< Riavviare ',
	'UI:Button:Next' => ' Prossimo >> ',
	'UI:Button:Finish' => ' Fine ',
	'UI:Button:DoImport' => ' Eseguire le importazioni ! ',
	'UI:Button:Done' => ' Fatto ',
	'UI:Button:SimulateImport' => ' Simulare l\'Importazione ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Valuta ',
	'UI:Button:AddObject' => ' Aggiungi... ',
	'UI:Button:BrowseObjects' => ' Sfoglia... ',
	'UI:Button:Add' => ' Aggiungi ',
	'UI:Button:AddToList' => ' << Aggiungi ',
	'UI:Button:RemoveFromList' => ' Rimuovi>> ',
	'UI:Button:FilterList' => ' Filtra... ',
	'UI:Button:Create' => ' Crea ',
	'UI:Button:Delete' => ' Cancella! ',
	'UI:Button:ChangePassword' => ' Cambia Password ',
	'UI:Button:ResetPassword' => ' Azzera Password ',
	
	'UI:SearchToggle' => 'Cerca',
	'UI:ClickToCreateNew' => 'Crea un nuovo %1$s',
	'UI:SearchFor_Class' => 'Ricerca l\'oggetto %1$s',
	'UI:NoObjectToDisplay' => 'Nessun oggetto da mostrare.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Object_id parametro è obbligatorio quando link_attr è specificato. Verificare la definizione del modello di display..',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Target_attr parametro è obbligatorio quando link_attr è specificato. Verificare la definizione del modello di display.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Il parametro è group_by obbligatoria. Verificare la definizione del modello di display.',
	'UI:Error:InvalidGroupByFields' => 'Elenco di campi non valido per il raggruppamento: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Errore: Stile non supportato di blocco: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Errata definizione di link: la classe di oggetti da gestire: %1$s wnon è stato trovato come chiave esterna nella classe %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Oggetto: %1$s:%2$d non trovato.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Errore: Riferimento circolare nelle dipendenze tra i campi, controllare il modello di dati.',
	'UI:Error:UploadedFileTooBig' => 'Il file caricato è troppo grande. (dimensione massima consentita è di %1$s). Verificare di configurazione di PHP per upload_max_filesize e post_max_size.',
	'UI:Error:UploadedFileTruncated.' => 'Il file caricato è stata troncato !',
	'UI:Error:NoTmpDir' => 'La directory temporanea non è definita.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Impossibile scrivere il file temporaneo sul disco. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Caricamento fermato per estensione. (Nome del file originale = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Il caricamento del file non riuscito, causa sconosciuta. (Codice errore = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Errore: il seguente parametro deve essere specificato per questa operazione: %1$s.',
	'UI:Error:2ParametersMissing' => 'Errore: i seguenti parametri devono essere specificati per questa operazione: %1$s e %2$s.',
	'UI:Error:3ParametersMissing' => 'Errore: i seguenti parametri devono essere specificati per questa operazione: %1$s, %2$s e %3$s.',
	'UI:Error:4ParametersMissing' => 'Errore: i seguenti parametri devono essere specificati per questa operazione: %1$s, %2$s, %3$s e %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Errore: errata OQL query: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Si è verificato un errore durante l\'esecuzione della query: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Errore: l\'oggetto è già stato aggiornato.',
	'UI:Error:ObjectCannotBeUpdated' => 'Errore: oggetto non può essere aggiornato.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Errore: gli oggetti sono già stati eliminati!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Non hai i permessi per eseguire una eliminazione collettiva degli oggetti della classe %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Non ti è permesso di eliminare gli oggetti della classe %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Non hai i permessi per eseguire un aggiornamento collettivo degli oggetti della classe %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Errore: l\'oggetto è già stato clonato!',
	'UI:Error:ObjectAlreadyCreated' => 'Errore: l\'oggetto è già stato creato!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Errore: stimolo non valido "%1$s" su un oggetto %2$s nello stato "%3$s".',
	
	
	'UI:GroupBy:Count' => 'Conteggio',
	'UI:GroupBy:Count+' => 'Numero di elementi',
	'UI:CountOfObjects' => '%1$d oggetti corrispondenti ai criteri.',
	'UI_CountOfObjectsShort' => '%1$d oggetti.',
	'UI:NoObject_Class_ToDisplay' => 'No %1$s da visualizzare',
	'UI:History:LastModified_On_By' => 'Ultima modifica %1$s da %2$s.',
	'UI:HistoryTab' => 'Storia',
	'UI:NotificationsTab' => 'Notifiche',
	'UI:History:BulkImports' => 'Storia',
	'UI:History:BulkImports+' => 'Elenco delle importazioni CSV (primo ultimo)',
	'UI:History:BulkImportDetails' => 'Modifiche derivanti dai importazione CSV eseguita su %1$s (da %2$s)',
	'UI:History:Date' => 'Data',
	'UI:History:Date+' => 'Data del cambiamento',
	'UI:History:User' => 'Utente',
	'UI:History:User+' => 'Utente che ha effettuato la modifica',
	'UI:History:Changes' => 'Cambiamenti',
	'UI:History:Changes+' => 'Cambiamenti apportate all\'oggetto',
	'UI:History:StatsCreations' => 'Creato',
	'UI:History:StatsCreations+' => 'Conteggi degli oggetti creati',
	'UI:History:StatsModifs' => 'Modificato',
	'UI:History:StatsModifs+' => 'Conteggi degli oggetti modificati',
	'UI:History:StatsDeletes' => 'Cancellati',
	'UI:History:StatsDeletes+' => 'Conteggi degli oggetti cancellati',
	'UI:Loading' => 'Caricamento...',
	'UI:Menu:Actions' => 'Azioni',
	'UI:Menu:New' => 'Nuovo...',
	'UI:Menu:Add' => 'Aggiungi...',
	'UI:Menu:Manage' => 'Gestisti...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export',
	'UI:Menu:Modify' => 'Modifica...',
	'UI:Menu:Delete' => 'Cancella...',
	'UI:Menu:Manage' => 'Gestisci...',
	'UI:Menu:BulkDelete' => 'Cancella..',
	'UI:UndefinedObject' => 'indefinito',
	'UI:Document:OpenInNewWindow:Download' => 'Apri in una nuova finestra: %1$s, Scarica: %2$s',
	'UI:SelectAllToggle+' => 'Seleziona / Deseleziona Tutto',
	'UI:TruncatedResults' => '%1$d oggetti visualizzati su %2$d',
	'UI:DisplayAll' => 'Mostra tutto',
	'UI:CollapseList' => 'Collapse',
	'UI:CountOfResults' => '%1$d oggetto(i)',
	'UI:ChangesLogTitle' => 'Log dei cambiamenti (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Log dei cambiamenti è vuoto',
	'UI:SearchFor_Class_Objects' => 'Ricerca per  %1$s Oggetti',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Ricerca semplice',
	'UI:Details+' => 'Dettagli',
	'UI:SearchValue:Any' => '* Qualsiasi *',
	'UI:SearchValue:Mixed' => '* misti *',
	'UI:SelectOne' => '-- selezionare una --',
	'UI:Login:Welcome' => 'Benvenuti su iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Errato login/password, si prega di riprovare.',
	'UI:Login:IdentifyYourself' => 'Identificare te stesso prima di continuare',
	'UI:Login:UserNamePrompt' => 'Nome Utente',
	'UI:Login:PasswordPrompt' => 'Password',
	'UI:Login:ChangeYourPassword' => 'Cambia la tua password',
	'UI:Login:OldPasswordPrompt' => 'Vecchia password',
	'UI:Login:NewPasswordPrompt' => 'Nuova password',
	'UI:Login:RetypeNewPasswordPrompt' => 'Riscrivi la nuova password',
	'UI:Login:IncorrectOldPassword' => 'Errore: la vecchia password non è corretta',
	'UI:LogOffMenu' => 'Log off',
	'UI:LogOff:ThankYou' => 'Grazie per aver scelto iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Clicca qui per effettuare il login di nuovo...',
	'UI:ChangePwdMenu' => 'Cambia Password...',
	'UI:AccessRO-All' => 'iTop è di sola lettura',
	'UI:AccessRO-Users' => 'iTop è di sola lettura per gli utenti finali',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nuova password e la nuova password digitata nuovamente non corrispondono !',
	'UI:Button:Login' => 'Entra iTop',
	'UI:Login:Error:AccessRestricted' => 'L\'accesso iTop è limitato. Si prega di contattare un amministratore iTop.',
	'UI:Login:Error:AccessAdmin' => 'Accesso limitato alle persone che hanno privilegi di amministratore. Si prega di contattare un amministratore iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- selezionare una --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignora questo campo --',
	'UI:CSVImport:NoData' => 'Insieme di dati vuoto ..., si prega di fornire alcuni dati!',
	'UI:Title:DataPreview' => 'Anteprima dati',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Errore: I dati contengono solo una colonna. Avete selezionare il carattere separatore appropriato?',
	'UI:CSVImport:FieldName' => 'Campo %1$d',
	'UI:CSVImport:DataLine1' => 'Dati Linea 1',
	'UI:CSVImport:DataLine2' => 'Dati Linea 2',
	'UI:CSVImport:idField' => 'id (Chiave Primaria)',
	'UI:Title:BulkImport' => 'iTop - importazione collettiva',
	'UI:Title:BulkImport+' => 'CSV Import Wizard',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Sincronizzazione di %1$d oggetti della classe %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- selezionare una --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Errore interno: "%1$s" è un codice errato, perché "%2$s" NON è una chiave esterna della classe "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d oggetto(i) rimarrà invariato.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d oggetto(i) sarà modificato.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d oggetto(i) sarà aggiunto.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d oggetto(i) avranno i errori.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d oggetto(i) è rimasto invariato.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d oggetto(i) sono stati modificati.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d oggetto(i) sono stati aggiunti.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d oggetto(i) hanno avuto errori.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: opzioni dati CVS',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Mappatura dei dati',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Importa simulazione',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Importazione completata',
	'UI:CSVImport:LinesNotImported' => 'Linee che non possono essere caricate:',
	'UI:CSVImport:LinesNotImported+' => 'Le righe che seguono non sono state importate in quanto contengono errori',
	'UI:CSVImport:SeparatorComma+' => ', (comma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (semicolon)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'altri:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (double quote)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (simple quote)',
	'UI:CSVImport:QualifierOther' => 'altri:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Trattare la prima riga come intestazione (nomi di colonna)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Saltare le linee %1$s all\'inzio del file',
	'UI:CSVImport:CSVDataPreview' => 'CSV Anteprima dei dati',
	'UI:CSVImport:SelectFile' => 'Selezionare il file da importare:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Carica da un file',
	'UI:CSVImport:Tab:CopyPaste' => 'Copiare e incollare dati',
	'UI:CSVImport:Tab:Templates' => 'Modelli',
	'UI:CSVImport:PasteData' => 'Incollare i dati da importare:',
	'UI:CSVImport:PickClassForTemplate' => 'Scegli il modello da scaricare: ',
	'UI:CSVImport:SeparatorCharacter' => 'Separatore di carattere:',
	'UI:CSVImport:TextQualifierCharacter' => 'Testo di qualificazione carattere',
	'UI:CSVImport:CommentsAndHeader' => 'Commenti e intestazione',
	'UI:CSVImport:SelectClass' => 'Selezionare la classe da importare:',
	'UI:CSVImport:AdvancedMode' => 'Modalità avanzata',
	'UI:CSVImport:AdvancedMode+' => 'In modalità avanzata l\'"id" (chiave primaria) degli oggetti può essere utilizzata per aggiornare e rinominare gli oggetti.' .
									'Tuttavia il "id" colonna (se presente) può essere usato solo come criterio di ricerca e non può essere combinato con qualsiasi altro criterio di ricerca.',
	'UI:CSVImport:SelectAClassFirst' => 'Per configurare il mapping, selezionare prima una classe.',
	'UI:CSVImport:HeaderFields' => 'Campi',
	'UI:CSVImport:HeaderMappings' => 'Mapping',
	'UI:CSVImport:HeaderSearch' => 'Ricerca?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Per favovore selezionare una mappatura per ogni campo.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Per favore seleziona almeno un criterio di ricerca',
	'UI:CSVImport:Encoding' => 'Codifica dei caratteri',	
	'UI:UniversalSearchTitle' => 'iTop - Ricerca Universale',
	'UI:UniversalSearch:Error' => 'Errore: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selezionare la classe per la ricerca: ',
	
	'UI:Audit:Title' => 'iTop - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Audit Interattivo',
	'UI:Audit:HeaderAuditRule' => 'Regole di Audit',
	'UI:Audit:HeaderNbObjects' => '# Ogetti',
	'UI:Audit:HeaderNbErrors' => '# Errori',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - Valutazione Query OQL',
	'UI:RunQuery:QueryExamples' => 'Esempi di Query',
	'UI:RunQuery:HeaderPurpose' => 'Scopo',
	'UI:RunQuery:HeaderPurpose+' => 'Spiegazione sulla query',
	'UI:RunQuery:HeaderOQLExpression' => 'Espressioni OQL',
	'UI:RunQuery:HeaderOQLExpression+' => 'La query nella sintassi OQL',
	'UI:RunQuery:ExpressionToEvaluate' => 'Espressione da valutare: ',
	'UI:RunQuery:MoreInfo' => 'Maggiori informazioni sulla query: ',
	'UI:RunQuery:DevelopedQuery' => 'Espressione della query riqualificata:',
	'UI:RunQuery:SerializedFilter' => 'Filtro pubblicato: ',
	'UI:RunQuery:Error' => 'Si è verificato un errore durante l\'esecuzione della query: %1$s',
	
	'UI:Schema:Title' => 'iTop schema degli oggetti',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relazioni',
	'UI:Schema:AbstractClass' => 'Classe astratta: nessun oggetto da questa classe può essere istanziato.',
	'UI:Schema:NonAbstractClass' => 'Non classe astratta: oggetti da questa classe possono essere istanziati.',
	'UI:Schema:ClassHierarchyTitle' => 'Gerarchia delle classi',
	'UI:Schema:AllClasses' => 'Tutte le classi',
	'UI:Schema:ExternalKey_To' => 'Chiave esterna  %1$s',
	'UI:Schema:Columns_Description' => 'Colonne: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Default: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null consentito',
	'UI:Schema:NullNotAllowed' => 'Null NON consentito',
	'UI:Schema:Attributes' => 'Attributi',
	'UI:Schema:AttributeCode' => 'Codice attributo',
	'UI:Schema:AttributeCode+' => 'Codice interno di un attributo',
	'UI:Schema:Label' => 'Etichetta',
	'UI:Schema:Label+' => 'Etichetta dell\'attributo',
	'UI:Schema:Type' => 'Tipo',
	
	'UI:Schema:Type+' => 'Il tipo di dati dell\'attributo',
	'UI:Schema:Origin' => 'Origine',
	'UI:Schema:Origin+' => 'La classe base in cui è definito l\'attributo',
	'UI:Schema:Description' => 'Descrizione',
	'UI:Schema:Description+' => 'Descrizione del attributo',
	'UI:Schema:AllowedValues' => 'Valori consentiti',
	'UI:Schema:AllowedValues+' => 'Restrizioni per i valori possibili per questo attributo',
	'UI:Schema:MoreInfo' => 'Maggiori informazioni',
	'UI:Schema:MoreInfo+' => 'Maggiori informazioni sul campo definito nel database',
	'UI:Schema:SearchCriteria' => 'Criteri di ricerca',
	'UI:Schema:FilterCode' => 'Codice di filtro',
	'UI:Schema:FilterCode+' => 'Codice di questi criteri di ricerca',
	'UI:Schema:FilterDescription' => 'Descrizione',
	'UI:Schema:FilterDescription+' => 'Descrizione di questo criterio di ricerca',
	'UI:Schema:AvailOperators' => 'Operatori disponibili',
	'UI:Schema:AvailOperators+' => 'Operatori possibili per questo criterio di ricerca',
	'UI:Schema:ChildClasses' => 'Classi figlio',
	'UI:Schema:ReferencingClasses' => 'Classi di rifermento',
	'UI:Schema:RelatedClasses' => 'Classi correlate',
	'UI:Schema:LifeCycle' => 'Ciclo di vita',
	'UI:Schema:Triggers' => 'Triggers',
	'UI:Schema:Relation_Code_Description' => 'Relazione <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Giù: %1$s',
	'UI:Schema:RelationUp_Description' => 'Su: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagato al livello %2$d, query: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: non si propaga a (%2$d livelli), query: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s fa riferimento la classe %2$s tramite il campo %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s è legata alla %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Classi che puntano a %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Classi legati alla %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Grafico di tutte le classi correlate',
	'UI:Schema:NoLifeCyle' => 'Non vi è alcun ciclo di vita definito per questa classe.',
	'UI:Schema:LifeCycleTransitions' => 'Transizioni',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opzioni per l\'attributo',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Nascosto',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Di sola lettura',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obbigatorio',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Deve cambiare',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'All\'utente verrà richiesto di modificare il valore',
	'UI:Schema:LifeCycleEmptyList' => 'lista vuota',
	
	'UI:LinksWidget:Autocomplete+' => 'Digitare i primi 3 caratteri...',
	'UI:Combo:SelectValue' => '--- selezionare un valore ---',
	'UI:Label:SelectedObjects' => 'oggetti selezionati: ',
	'UI:Label:AvailableObjects' => 'Oggetti disponibili: ',
	'UI:Link_Class_Attributes' => '%1$s attributi',
	'UI:SelectAllToggle+' => 'Seleziona Tutti / Deseleziona Tutti',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Aggiungi l\'oggeto %1$s collegato con %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Aggiungi l\'oggeto %1$s al collegamento con %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Gestisci l\'oggetto %1$s collegato con %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Aggiungi %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Rimuovi gli oggetti selezionati',
	'UI:Message:EmptyList:UseAdd' => 'La lista è vuota, utilizzare il pulsante "Aggiungi ..." per aggiungere elementi.',
	'UI:Message:EmptyList:UseSearchForm' => 'Utilizza il modulo di ricerca qui sopra per cercare oggetti da aggiungere.',
	
	'UI:Wizard:FinalStepTitle' => 'Passo finale: la conferma',
	'UI:Title:DeletionOf_Object' => 'Soppressione di %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Cancellazione collettiva di %1$d oggetti della classe %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Non ti è permesso di eliminare l\'oggetto',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Non hai i permessi per aggiornare il seguente campo(i): %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Questo oggetto non può essere cancellato perché l\'utente corrente non dispone dei diritti necessari',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Questo oggetto non può essere cancellato perché alcune operazioni manuali devono essere effettuate prima di questo',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s a nome di %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'automaticamente eliminato',
	'UI:Delete:AutomaticResetOf_Fields' => 'ripristino automatico del campo(i): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Pulizia di tutti i riferimenti a %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Pulizia tutti i riferimenti a %1$d oggetti di classe %2$s...',
	'UI:Delete:Done+' => 'Cosa è stato fatto...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s cancellato.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Soppressione di %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Soppressione di %1$d oggetti di classe %2$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotAllowed' => 'Dovrebbe essere eliminato automaticamente, ma non sei autorizzato a farlo',
	'UI:Delete:MustBeDeletedManuallyButNotAllowed' => 'Devono essere eliminati manualmente - ma non ti è permesso di eliminare l\'oggetto, si prega di contattare l\'amministratore dell\'applicazione',
	'UI:Delete:WillBeDeletedAutomatically' => 'Verranno automaticamente cancellati',
	'UI:Delete:MustBeDeletedManually' => 'Devono essere eliminati manualmente',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Dovrebbero essere automaticamente aggiornati, ma %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'sarà automaticamente aggiornato (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d oggetti/link fanno riferimento %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d oggetti / link fanno riferimento alcuni degli oggetti da eliminare',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Per garantire l\'integrità del database, ogni riferimento dovrebbe essere ulteriormente eliminato',
	'UI:Delete:Consequence+' => 'Cosa sarà fatto',
	'UI:Delete:SorryDeletionNotAllowed' => 'Spiacenti, non sei autorizzato a cancellare questo oggetto, vedere le spiegazioni di cui sopra',
	'UI:Delete:PleaseDoTheManualOperations' => 'Si prega di eseguire le operazioni manuali di cui sopra prima di richiedere la cancellazione di questo oggetto',
	'UI:Delect:Confirm_Object' => 'Si prega di confermare che si desidera eliminare %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Si prega di confermare che si desidera eliminare i seguenti oggetti %1$d della classe %2$s.',
	'UI:WelcomeToITop' => 'Benvenuto su iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s dettagli',
	'UI:ErrorPageTitle' => 'iTop - Errore',
	'UI:ObjectDoesNotExist' => 'Spiacenti, questo oggetto non esiste (o non si è autorizzati per vederlo).',
	'UI:SearchResultsPageTitle' => 'iTop - Risultati della ricerca',
	'UI:Search:NoSearch' => 'Niente da ricercare',
	'UI:FullTextSearchTitle_Text' => 'Risultati di "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => 'Trovato l\'oggetto(i) %1$d della classe %2$s.',
	'UI:Search:NoObjectFound' => 'Nessun oggetto trovato.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modifica',
	'UI:ModificationTitle_Class_Object' => 'Modifica di %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Clone %1$s - %2$s modifica',
	'UI:CloneTitle_Class_Object' => 'Clone di %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Creazione di un nuovo %1$s ',
	'UI:CreationTitle_Class' => 'Creazione di un nuovo %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Seleziona il tipo di %1$s da creare:',
	'UI:Class_Object_NotUpdated' => 'Nessun cambiamento rilevato, %1$s (%2$s)  <strong>non</strong> è stato modificato.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) aggiornato.',
	'UI:BulkDeletePageTitle' => 'iTop - Eliminazione collettiva',
	'UI:BulkDeleteTitle' => 'Selezionate gli oggetti che si desidera eliminare:',
	'UI:PageTitle:ObjectCreated' => 'iTop Oggetto Creato.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s creato.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Applicazione %1$s all\'oggetto: %2$s nello stato %3$s allo stato target: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'The object could not be written: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Fatal Error',
	'UI:SystemIntrusion' => 'Accesso negato. Devi cercare di eseguire un\'operazione che non è consentita per voi.',
	'UI:FatalErrorMessage' => 'Fatal error, iTop non può continuare.',
	'UI:Error_Details' => 'Errore: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop gestione degli utenti - proiezioni di classe',
	'UI:PageTitle:ProfileProjections' => 'iTop gestione degli utenti - proiezioni profilo',
	'UI:UserManagement:Class' => 'Classe',
	'UI:UserManagement:Class+' => 'Classe di oggetti',
	'UI:UserManagement:ProjectedObject' => 'Oggetti',
	'UI:UserManagement:ProjectedObject+' => 'Oggetto previsto',
	'UI:UserManagement:AnyObject' => '* qualsiasi *',
	'UI:UserManagement:User' => 'Utente',
	'UI:UserManagement:User+' => 'Utenti coinvolti nella proiezione',
	'UI:UserManagement:Profile' => 'Profilo',
	'UI:UserManagement:Profile+' => 'Profilo del quale è specificata la proiezione',
	'UI:UserManagement:Action:Read' => 'Leggi',
	'UI:UserManagement:Action:Read+' => 'Leggi e visualizza oggetti',
	'UI:UserManagement:Action:Modify' => 'Modifica',
	'UI:UserManagement:Action:Modify+' => 'Creare e editare (modificare) degli oggetti',
	'UI:UserManagement:Action:Delete' => '>Cancella',
	'UI:UserManagement:Action:Delete+' => 'Cancella oggetti',
	'UI:UserManagement:Action:BulkRead' => 'Leggi Bulk (Export)',
	'UI:UserManagement:Action:BulkRead+' => 'Elenca gli oggetti o esportali massicciamente',
	'UI:UserManagement:Action:BulkModify' => 'Modifiche Collettive',
	'UI:UserManagement:Action:BulkModify+' => 'Creare / modificare massivamente (import CSV)',
	'UI:UserManagement:Action:BulkDelete' => 'Cancella Bulk ',
	'UI:UserManagement:Action:BulkDelete+' => 'Eliminare oggetti massivamente',
	'UI:UserManagement:Action:Stimuli' => 'Stimoli',
	'UI:UserManagement:Action:Stimuli+' => 'Azioni permesse (composte)',
	'UI:UserManagement:Action' => 'Azione',
	'UI:UserManagement:Action+' => 'Azione eseguita dall\'utente',
	'UI:UserManagement:TitleActions' => 'Azioni',
	'UI:UserManagement:Permission' => 'Autorizzazione',
	'UI:UserManagement:Permission+' => 'Autorizzazione dell\'utente',
	'UI:UserManagement:Attributes' => 'Attributi',
	'UI:UserManagement:ActionAllowed:Yes' => 'Si',
	'UI:UserManagement:ActionAllowed:No' => 'No',
	'UI:UserManagement:AdminProfile+' => 'Gli amministratori hanno accesso completo in lettura / scrittura a tutti gli oggetti del database..',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Nessun ciclo di vita è stato definito per questa classe',
	'UI:UserManagement:GrantMatrix' => 'Grant Matrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Collegamento tra %1$s e %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Collegamento tra %1$s e %2$s',
	
	'Menu:AdminTools' => 'Strumenti di amministrazione',
	'Menu:AdminTools+' => 'Strumenti di amministrazione',
	'Menu:AdminTools?' => 'Strumenti accessibile solo agli utenti con il profilo di amministratore',

	'UI:ChangeManagementMenu' => 'Gestione Cambi',
	'UI:ChangeManagementMenu+' => 'Gestione Cambi',
	'UI:ChangeManagementMenu:Title' => 'Panoramica dei cambi',
	'UI-ChangeManagementMenu-ChangesByType' => 'Cambi per tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Cambi per stato',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Cambi per gruppi di lavoro',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Cambi non ancora assegnati',

	'UI:ConfigurationItemsMenu'=> 'Configuration Items',
	'UI:ConfigurationItemsMenu+'=> 'Tutti i disposotivi',
	'UI:ConfigurationItemsMenu:Title' => 'Configuration Items Panoramica',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Server per criticità',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PCs per criticità',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Dispositivi di rete per criticità',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Applicazioni per criticità',
	
	'UI:ConfigurationManagementMenu' => 'Gesione Configurazione',
	'UI:ConfigurationManagementMenu+' => 'Gesione Configurazione',
	'UI:ConfigurationManagementMenu:Title' => 'Panoramica delle infrastrutture',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Oggetti infrastruttutura per tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Oggetti infrastruttutura per stato',

'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard per Gesione configurazione',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuration Items per stato',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items per tipo',
'UI:RequestMgmtMenuOverview:Title' => 'Dashboard per Gestione Richieste',
'UI-RequestManagementOverview-RequestByService' => 'Richieste degli utenti per servizio',
'UI-RequestManagementOverview-RequestByPriority' => 'Richieste degli utenti per priorità',
'UI-RequestManagementOverview-RequestUnassigned' => 'Richieste degli utenti non ancora assegnate ad un agente',

'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard Gestione degli Incidenti',
'UI-IncidentManagementOverview-IncidentByService' => 'Incidenti per servizio',
'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenti per  priorità',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidenti non ancora assegnati ad un agente',

'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard per Gestione dei Cambi',
'UI-ChangeManagementOverview-ChangeByType' => 'Cambi per tipo',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'Cambi non ancora assegnati ad un agente',
'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interruzioni dovute ai cambi',

'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard per Gestione Servizi',
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratti con i clienti da rinnovarsi in 30 giorni',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contratti con i fornitori da rinnovarsi in 30 giorni',

	'UI:ContactsMenu' => 'Contatti',
	'UI:ContactsMenu+' => 'Contatti',
	'UI:ContactsMenu:Title' => 'Contatti Panoramica',
	'UI-ContactsMenu-ContactsByLocation' => 'Contatti per posizione',
	'UI-ContactsMenu-ContactsByType' => 'Contatti per tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contatti per stato',

	'Menu:CSVImportMenu' => 'Importazione CSV',
	'Menu:CSVImportMenu+' => 'Crea/aggiorna collettivamente',
	
	'Menu:DataModelMenu' => 'Modello Dati',
	'Menu:DataModelMenu+' => 'Panoramica del Modello Dati',
	
	'Menu:ExportMenu' => 'Esporta',
	'Menu:ExportMenu+' => 'Esportare i risultati di una query in formato HTML, CSV o XML',
	
	'Menu:NotificationsMenu' => 'Notifiche',
	'Menu:NotificationsMenu+' => 'Configurazione delle Notifiche',
	'UI:NotificationsMenu:Title' => 'Configurazione delle <span class="hilite">Notifiche</span>',
	'UI:NotificationsMenu:Help' => 'Aiuto',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop le notifiche sono completamente personalizzabili. Essi si basano su due serie di oggetti: <i>trigger e azioni</i>.</p>
<p><i><b>Triggers</b></i> per definire quando una notifica verrà eseguita. Ci sono 3 tipi di trigger per la copertura di 3 fasi differenti del ciclo di vita di un oggetto:
<ol>
	<li>the "OnCreate" trigger vengono eseguiti quando un oggetto della classe specificata viene creata</li>
	<li>the "OnStateEnter" trigger vengono eseguiti prima che un oggetto della classe data entra in uno stato specifico (provenienti da un altro Stato)</li>
	<li>the "OnStateLeave" trigger vengono eseguiti quando un oggetto della classe lascia uno stato specificato</li>
</ol>
</p>
<p>
<i><b>Azioni</b></i> definire le azioni da eseguire quando il trigger vengono eseguiti. Per ora c\'è solo un tipo di azione consiste nel mandare un messaggio email.

Inoltre, tali azioni definiscono il modello da utilizzare per l\'invio della e-mail così come gli altri parametri del messaggio come, l\'importanza dei destinatari, ecc
</p>
<p>Una Pagina speciale: <a href="../setup/email.test.php" target="_blank">email.test.php</a> è disponibile per il testing e la risoluzione dei problemi di configurazione PHP mail.</p>
<p>Per essere eseguito, le azioni devono essere associate ai trigger.
Quando è associata a un trigger, ad ogni azione è assegnato un numero "ordine", che specifica in quale ordine le azioni devono essere eseguite.</p>',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Triggers Disponibili',
	'UI:NotificationsMenu:OnCreate' => 'Quando un oggetto viene creato',
	'UI:NotificationsMenu:OnStateEnter' => 'Quando un oggetto entra in un determinato stato',
	'UI:NotificationsMenu:OnStateLeave' => 'Quando un oggetto lascia un determinato stato',
	'UI:NotificationsMenu:Actions' => 'Azioni',
	'UI:NotificationsMenu:AvailableActions' => 'Azioni disponibili',
	
	'Menu:AuditCategories' => 'Categorie di Audit',
	'Menu:AuditCategories+' => 'Categorie di Audit',
	'Menu:Notifications:Title' => 'Categorie di Audit',
	
	'Menu:RunQueriesMenu' => 'Esegui query',
	'Menu:RunQueriesMenu+' => 'Eseguire una query',
	
	'Menu:DataAdministration' => 'Data di amministrazione',
	'Menu:DataAdministration+' => 'Data di amministrazione',
	
	'Menu:UniversalSearchMenu' => 'Ricerca universale',
	'Menu:UniversalSearchMenu+' => 'Cerca qualsiasi cosa...',
	
	'Menu:ApplicationLogMenu' => 'Log dell\'applicazione',
	'Menu:ApplicationLogMenu+' => 'Log dell\'applicazione',
	'Menu:ApplicationLogMenu:Title' => 'Log dell\'applicazione',

	'Menu:UserManagementMenu' => 'Gestione degli utenti',
	'Menu:UserManagementMenu+' => 'Gestione degli utenti',

	'Menu:ProfilesMenu' => 'Profili',
	'Menu:ProfilesMenu+' => 'Profili',
	'Menu:ProfilesMenu:Title' => 'Profili',

	'Menu:UserAccountsMenu' => 'Account utente',
	'Menu:UserAccountsMenu+' => 'Account utente',
	'Menu:UserAccountsMenu:Title' => 'Account utente',	

	'UI:iTopVersion:Short' => 'Versione iTop %1$s',
	'UI:iTopVersion:Long' => 'Versione iTop %1$s-%2$s costruito il %3$s',
	'UI:PropertiesTab' => 'Proprietà',

	'UI:OpenDocumentInNewWindow_' => 'Apri questo documento in una nuova finestra: %1$s',
	'UI:DownloadDocument_' => 'Scarica questo documento: %1$s',
	'UI:Document:NoPreview' => 'Non è disponibile un\'anteprima per questo tipo di documento',

	'UI:DeadlineMissedBy_duration' => 'Mancati %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',		
	'UI:Deadline_Minutes' => '%1$d min',			
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Help',
	'UI:PasswordConfirm' => '(Conferma)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Prima di aggiungere più %1$s oggetti, salva questo oggetto.',
	'UI:DisplayThisMessageAtStartup' => 'Visualizza questo messaggio in fase di avvio',
	'UI:RelationshipGraph' => 'Visualizzazione grafica',
	'UI:RelationshipList' => 'Lista',
	'UI:OperationCancelled' => 'Operazione Annullata',

	'Portal:Title' => 'Portale Utente iTop',
	'Portal:Refresh' => 'Aggiorna',
	'Portal:Back' => 'Indietro',
	'Portal:CreateNewRequest' => 'Crea una nuova richiesta',
	'Portal:ChangeMyPassword' => 'Cambia la mia password',
	'Portal:Disconnect' => 'Disconnetti',
	'Portal:OpenRequests' => 'Le mie richieste aperte',
	'Portal:ResolvedRequests'  => 'Le mie richieste risolte',
	'Portal:SelectService' => 'Seleziona un servizio dal catalogo:',
	'Portal:PleaseSelectOneService' => 'Si prega di selezionare un servizio',
	'Portal:SelectSubcategoryFrom_Service' => 'Seleziona una sotto-categoria per il servizio %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Si prega di selezionare una delle sottocategorie',
	'Portal:DescriptionOfTheRequest' => 'Inserire la descrizione della tua richiesta:',
	'Portal:TitleRequestDetailsFor_Request' => 'Dettagli per la richiesta %1$s:',
	'Portal:NoOpenRequest' => 'Nessuna richiesta in questa categoria.',
	'Portal:Button:CloseTicket' => 'Chiudi questo ticket',
	'Portal:EnterYourCommentsOnTicket' => 'Inserisci il tuo commento circa la risoluzione di questo ticket:',
	'Portal:ErrorNoContactForThisUser' => 'Errore: l\'utente corrente non è associato ad un Contatto/Persona. Si prega di contattare l\'amministratore.',
	'Portal:Attachments' => 'Allegati',
	'Portal:AddAttachment' => ' Aggiungi allegati ',
	'Portal:RemoveAttachment' => ' Rimuovi allegati ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Allegato #%1$d a %2$s (%3$s)',
	'Enum:Undefined' => 'Non definito',
));



?>

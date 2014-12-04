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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:AuditCategory' => 'Categoria di Audit',
	'Class:AuditCategory+' => 'Una sezione all\'interno del controllo globale',
	'Class:AuditCategory/Attribute:name' => 'Nome della categoria',
	'Class:AuditCategory/Attribute:name+' => 'Abbreviazione per questa categoria',
	'Class:AuditCategory/Attribute:description' => 'Descrizione della categoria di Audit',
	'Class:AuditCategory/Attribute:description+' => 'Descrizione dettagliata della categoria di audit',
	'Class:AuditCategory/Attribute:definition_set' => 'Insieme di definizione',
	'Class:AuditCategory/Attribute:definition_set+' => 'Espressione OQLche definisce l\'insieme di oggetti da controllare',
	'Class:AuditCategory/Attribute:rules_list' => 'Regole di Audit',
	'Class:AuditCategory/Attribute:rules_list+' => 'Regolele di audit per queste categorie',
));

//
// Class: AuditRule
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:AuditRule' => 'Regola di Audit',
	'Class:AuditRule+' => '',
	'Class:AuditRule/Attribute:name' => 'Nome della regola',
	'Class:AuditRule/Attribute:name+' => '',
	'Class:AuditRule/Attribute:description' => 'Descrizione della regola di Audit',
	'Class:AuditRule/Attribute:description+' => 'Descrizione dettagliata per questa regola di audit ',
	'Class:AuditRule/Attribute:query' => 'Query da eseguire',
	'Class:AuditRule/Attribute:query+' => 'Espressio OQL da eseguire',
	'Class:AuditRule/Attribute:valid_flag' => 'Oggetti validi?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Vero se la regola ritorna oggetti validi, falso altrimenti ',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'vero',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'vero',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'falso',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'falso',
	'Class:AuditRule/Attribute:category_id' => 'Categoria',
	'Class:AuditRule/Attribute:category_id+' => 'Categoria per questa regola',
	'Class:AuditRule/Attribute:category_name' => 'Categoria',
	'Class:AuditRule/Attribute:category_name+' => 'Nome della categoria per questa regola',
));

//
// Class: QueryOQL
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Query' => 'Query',
	'Class:Query+' => 'Una query è un insieme di dati definito in modo dinamico',
	'Class:Query/Attribute:name' => 'Nome',
	'Class:Query/Attribute:name+' => 'Identificativi della query',
	'Class:Query/Attribute:description' => 'Descrizione',
	'Class:Query/Attribute:description+' => 'Descrizione dettagliata della query(scopo, usagoetc.)',
	'Class:Query/Attribute:fields' => 'Campi',
	'Class:Query/Attribute:fields+' => 'Lista di attributi separati da virgola (o alias.attributo) per l\'esportazione',

	'Class:QueryOQL' => 'OQL Query',
	'Class:QueryOQL+' => 'Una query basata su Object Query Language',
	'Class:QueryOQL/Attribute:oql' => 'Espressione',
	'Class:QueryOQL/Attribute:oql+' => 'Espressione OQL',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:User' => 'Utente',
	'Class:User+' => 'Login Utente',
	'Class:User/Attribute:finalclass' => 'Tipo di account',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Contatto (persona)',
	'Class:User/Attribute:contactid+' => 'Dettagli personali per dati aziendali',
	'Class:User/Attribute:last_name' => 'Cognome',
	'Class:User/Attribute:last_name+' => 'Cognome del contatto corrispondente',
	'Class:User/Attribute:first_name' => 'Nome',
	'Class:User/Attribute:first_name+' => 'Nome del contatto corrispondente',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email del contatto corrispondente',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Stringa di identificazione dell\'utente',
	'Class:User/Attribute:language' => 'Lingua',
	'Class:User/Attribute:language+' => 'Lingua utente',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:IT IT' => 'Italiano',
	'Class:User/Attribute:language/Value:IT IT+' => 'Italiano (IT)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profili',
	'Class:User/Attribute:profile_list+' => 'Regole per  la concessione dei diritti per quella persona',
	'Class:User/Attribute:allowed_org_list' => 'Organizzazione Consentite',
	'Class:User/Attribute:allowed_org_list+' => 'L\'utente finale è autorizzato a vedere i dati appartenenti alle seguenti organizzazioni. Se non è specificato organizzazione, vi è alcuna restrizione.',

	'Class:User/Error:LoginMustBeUnique' => 'Il Login deve essere unico - "%1s" già usato',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Almeno un profilo deve essere assegnato all\'utente.',
));

//
// Class: URP_Profiles
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_Profiles' => 'Profilo',
	'Class:URP_Profiles+' => '',
	'Class:URP_Profiles/Attribute:name' => 'Nome',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Descrizione',
	'Class:URP_Profiles/Attribute:description+' => 'una linea di descrizione',
	'Class:URP_Profiles/Attribute:user_list' => 'Utenti',
	'Class:URP_Profiles/Attribute:user_list+' => 'Persone che hanno questo ruuolo',
));

//
// Class: URP_Dimensions
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_Dimensions' => 'dimensione',
	'Class:URP_Dimensions+' => 'dimensione dell\'applicazione (definizione di silos))',
	'Class:URP_Dimensions/Attribute:name' => 'Nome',
	'Class:URP_Dimensions/Attribute:name+' => 'etichetta',
	'Class:URP_Dimensions/Attribute:description' => 'Descrizione',
	'Class:URP_Dimensions/Attribute:description+' => 'una linea di descrizione',
	'Class:URP_Dimensions/Attribute:type' => 'Tipo',
	'Class:URP_Dimensions/Attribute:type+' => 'nome della classe o tipo di dato (proiezione dell\'unità)',
));

//
// Class: URP_UserProfile
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_UserProfile' => 'Utente da Profilare',
	'Class:URP_UserProfile+' => '',
	'Class:URP_UserProfile/Attribute:userid' => 'Utente',
	'Class:URP_UserProfile/Attribute:userid+' => '',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'User\'s login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profilo',
	'Class:URP_UserProfile/Attribute:profileid+' => 'utilizzo del profilo',
	'Class:URP_UserProfile/Attribute:profile' => 'Profilo',
	'Class:URP_UserProfile/Attribute:profile+' => 'Nome del profilo',
	'Class:URP_UserProfile/Attribute:reason' => 'Motivo',
	'Class:URP_UserProfile/Attribute:reason+' => 'spiega perchè questo utente dovrebbe avere questo ruolo',
));

//
// Class: URP_UserOrg
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_UserOrg' => 'Organizzazione dell\'utente',
	'Class:URP_UserOrg+' => '',
	'Class:URP_UserOrg/Attribute:userid' => 'Utente',
	'Class:URP_UserOrg/Attribute:userid+' => 'Account Utente',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login Utente',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizazione',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Organizzazione permesse',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organizzazione',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Organizzazione permesse',
	'Class:URP_UserOrg/Attribute:reason' => 'Motivo',
	'Class:URP_UserOrg/Attribute:reason+' => '',

));

//
// Class: URP_ProfileProjection
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'proiezioni di profilo',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensione',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'dimensione applicazione',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensione',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'dimensione applicazione',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profilo',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'utilizzo di profilo',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profilo',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Nome del profilo',
	'Class:URP_ProfileProjection/Attribute:value' => 'Valore dell\'espressione',
	'Class:URP_ProfileProjection/Attribute:value+' => 'Espressione OQL  (uso $user) | constante|  | +codice attributo',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attributo',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Codice attributo bersaglio (opzionale)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'proiezioni di classe',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimensione',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'dimensione dell\'applicazione',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimensione',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'dimensione applicazione',
	'Class:URP_ClassProjection/Attribute:class' => 'Classe',
	'Class:URP_ClassProjection/Attribute:class+' => 'Classe bersaglio',
	'Class:URP_ClassProjection/Attribute:value' => 'Valore dell\'espressione',
	'Class:URP_ClassProjection/Attribute:value+' => 'Espressione OQL (uso $this) | constante|  | +codice attributo',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attributo',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Codice attributo bersaglio (opzionale)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_ActionGrant' => 'azione_autorizzazione',
	'Class:URP_ActionGrant+' => 'permesso su classi',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profilo',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Utilizzo del profilo',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profilo',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Utilizzo del profilo',
	'Class:URP_ActionGrant/Attribute:class' => 'Classe',
	'Class:URP_ActionGrant/Attribute:class+' => 'Classe bersaglio',
	'Class:URP_ActionGrant/Attribute:permission' => 'Autorizzazione',
	'Class:URP_ActionGrant/Attribute:permission+' => 'permesso non permesso',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_ActionGrant/Attribute:action' => 'Azione',
	'Class:URP_ActionGrant/Attribute:action+' => 'operazioni da effettuare sulla data classe',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_ActionGrant/Attribute:action' => 'Azione',
	'Class:URP_ActionGrant/Attribute:action+' => '',
	'Class:URP_StimulusGrant' => 'stimulus_autorizzazione',
	'Class:URP_StimulusGrant+' => '',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profilo',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '',
	'Class:URP_StimulusGrant/Attribute:class' => 'Classe',
	'Class:URP_StimulusGrant/Attribute:class+' => '',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Autorizzazione',
	'Class:URP_StimulusGrant/Attribute:permission+' => '',
'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'si',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Codice per lo Stimolus',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_AttributeGrant' => 'attributo_autorizzazione',
	'Class:URP_AttributeGrant+' => 'autorizzazioni a livello di attributi',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Azione di sovvenzione',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'azione di sovvenzione',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attributo',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'codice attributo',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'BooleanLabel:yes' => 'si',
	'BooleanLabel:no' => 'no',
	'Menu:WelcomeMenu' => 'Benveuto', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Benvenuto', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Benveuto su iTop',
	'UI:WelcomeMenu:LeftBlock' => '<p>iTop è un completo Portale Funzionale IT, Open Source.</p>
<ul>Esso include:
<li>Un completo CMDB (Configuration management database) per documentare e gestire l\'IT di inventario.</li>
<li>Un modulo di gestione degli incidenti per monitorare e comunicare su tutte le problematiche che si verificano nel settore IT.</li>
<li>Un modulo di gestione delle modifiche per pianificare e monitorare i cambiamenti all\'ambiente IT.</li>
<li>Una banca dati errori noti per accelerare la risoluzione di incidenti.</li>
<li>Un modulo di interruzione per documentare tutte le interruzioni pianificate e notificare gli opportuni contatti.</li>
<li>Un cruscotto per ottenere rapidamente una panoramica del sistema IT.</li>
</ul>
<p>Tutti i moduli possono essere installati, passo dopo passo, indipendentemente l\'uno dall\'altro.</p>',
	'UI:WelcomeMenu:RightBlock' => '<p>iTop è fornitore di servizi di orientamento, che consente ai progettisti di gestire più o organizzazioni o clienti con facilità.
<ul>>iTop, offre un set ricco di funzionalità dei processi di business che:
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
	'UI:WelcomeMenu:AllOpenRequests' => 'Apri le richieste: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Le mie richieste',
	'UI:WelcomeMenu:OpenIncidents' => 'Apri gli incidenti: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Elementi di Configurazione (CI): %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidenti assegnati a me',
	'UI:AllOrganizations' => ' Tutte le Organizzazioni ',
	'UI:YourSearch' => 'La tua Cerca',
	'UI:LoggedAsMessage' => 'Logged come %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logged come %1$s (Amministratore)',
	'UI:Button:Logoff' => 'Log off',
	'UI:Button:GlobalSearch' => 'Cerca',
	'UI:Button:Search' => ' Cerca',
	'UI:Button:Query' => ' Domanda',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Cancella',
	'UI:Button:Apply' => 'Applica',
	'UI:Button:Back' => ' << Indietro',
	'UI:Button:Restart' => ' |<< Riavvia',
	'UI:Button:Next' => ' Prossimo >> ',
	'UI:Button:Finish' => ' Fine',
	'UI:Button:DoImport' => ' Esegui le Imporazioni ! ~~',
	'UI:Button:Done' => ' Fatto',
	'UI:Button:SimulateImport' => ' Simula l\'Importazione ~~',
	'UI:Button:Test' => 'Testa!',
	'UI:Button:Evaluate' => ' Valuta',
	'UI:Button:AddObject' => ' Aggiungi... ~~',
	'UI:Button:BrowseObjects' => ' Sfoglia... ~~',
	'UI:Button:Add' => ' Aggiungi ~~',
	'UI:Button:AddToList' => ' << Aggiungi ~~',
	'UI:Button:RemoveFromList' => ' Rimuovi >> ~~',
	'UI:Button:FilterList' => ' Filtra... ~~',
	'UI:Button:Create' => ' Crea ~~',
	'UI:Button:Delete' => ' Cancella ! ~~',
	'UI:Button:ChangePassword' => ' Cambia Password ~~',
	'UI:Button:ResetPassword' => ' Resetta Password ~~',
	'UI:SearchToggle' => 'Cerca',
	'UI:ClickToCreateNew' => 'Crea un nuovo %1$s~~',
	'UI:SearchFor_Class' => 'Cerca l\'oggetto %1$s',
	'UI:NoObjectToDisplay' => 'Nessun oggetto da mostrare.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Object_id parametro è obbligatorio quando link_attr è specificato. Verificare la definizione del modello di display.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Target_attr parametro è obbligatorio quando link_attr è specificato. Verificare la definizione del modello di display.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Il parametro è group_by obbligatoria. Verificare la definizione del modello di display.',
	'UI:Error:InvalidGroupByFields' => 'Elenco di campi non valido per il raggruppamento: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Errore: Stile non supportato di blocco: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Errata definizione di link: la classe di oggetti da gestire: %1$s non è stato trovato come chiave esterna nella classe %2$s',
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
	'UI:GroupBy:Count+' => '',
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
	'UI:History:Date+' => '',
	'UI:History:User' => 'Utente',
	'UI:History:User+' => '',
	'UI:History:Changes' => 'Modifiche',
	'UI:History:Changes+' => '',
	'UI:History:StatsCreations' => 'Creato',
	'UI:History:StatsCreations+' => '',
	'UI:History:StatsModifs' => 'Modificato',
	'UI:History:StatsModifs+' => '',
	'UI:History:StatsDeletes' => 'Cancellato',
	'UI:History:StatsDeletes+' => '',
	'UI:Loading' => 'Caricamento...',
	'UI:Menu:Actions' => 'Azioni',
	'UI:Menu:OtherActions' => 'Altre Azioni',
	'UI:Menu:New' => 'Nuovo...',
	'UI:Menu:Add' => 'Aggiungi...',
	'UI:Menu:Manage' => 'Gestischi...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export',
	'UI:Menu:Modify' => 'Modifica...',
	'UI:Menu:Delete' => 'Cancella...',
	'UI:Menu:Manage' => 'Gestisci...',
	'UI:Menu:BulkDelete' => 'Cancella...',
	'UI:UndefinedObject' => 'non definito',
	'UI:Document:OpenInNewWindow:Download' => 'Apri in una nuova finestra: %1$s, Scarica: %2$s',
	'UI:SelectAllToggle+' => '',
	'UI:TruncatedResults' => '%1$d oggetti visualizzati su %2$d',
	'UI:DisplayAll' => 'Mostra tutto',
	'UI:CollapseList' => 'Collassa',
	'UI:CountOfResults' => '%1$d oggetto(i)',
	'UI:ChangesLogTitle' => 'Log delle modifiche (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Log delle modifiche è vuoto',
	'UI:SearchFor_Class_Objects' => 'Cerca per  %1$s Oggetti',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Ricerca semplice',
	'UI:Details+' => '',
	'UI:SearchValue:Any' => '* Qualsiasi *',
	'UI:SearchValue:Mixed' => '* misti *',
	'UI:SelectOne' => '-- selezionare uno --',
	'UI:Login:Welcome' => 'Benvenuti su iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Errato login/password, si prega di riprovare.',
	'UI:Login:IdentifyYourself' => 'Identifica te stesso prima di continuare',
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
        'UI:Button:Login' => 'Entra in iTop',

	'UI:Login:Error:AccessRestricted' => 'L\'accesso a iTop è limitato. Si prega di contattare un amministratore iTop.',
	'UI:Login:Error:AccessAdmin' => 'Accesso limitato alle persone che hanno privilegi di amministratore. Si prega di contattare un amministratore iTop.',
	'UI:CSVImport:MappingSelectOne' => '-- seleziona uno --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignora questo campo --',
	'UI:CSVImport:NoData' => 'Insieme di dati vuoto ..., si prega di fornire alcuni dati!',
	'UI:Title:DataPreview' => 'Anteprima dati',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Errore: I dati contengono solo una colonna. Avete selezionato il carattere separatore appropriato?',
	'UI:CSVImport:FieldName' => 'Campo %1$d',
	'UI:CSVImport:DataLine1' => 'Dati Linea 1',
	'UI:CSVImport:DataLine2' => 'Dati Linea 2',
	'UI:CSVImport:idField' => 'id (Chiave Primaria)',
	'UI:Title:BulkImport' => 'iTop - importazione collettiva',
	'UI:Title:BulkImport+' => '',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Sincronizzazione di %1$d oggetti della classe %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- seleziona uno --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Errore interno: "%1$s" è un codice errato, perché "%2$s" NON è una chiave esterna della classe "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d oggetto(i) rimarrà invariato.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d oggetto(i) sarà modificato.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d oggetto(i) sarà aggiunto.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d oggetto(i) avranno i errori.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d oggetto(i) è rimasto invariato.',
	'UI:CSVImport:ObjectsWereModified' =>  '%1$d oggetto(i) sono stati modificati.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d oggetto(i) sono stati aggiunti.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d oggetto(i) hanno avuto errori.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: opzioni dati CVS',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Mappatura dei dati',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Importa simulazione',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Importazione completata',
	'UI:CSVImport:LinesNotImported' => 'Linee che non possono essere caricate:',
	'UI:CSVImport:LinesNotImported+' => '',
	'UI:CSVImport:SeparatorComma+' => '',
	'UI:CSVImport:SeparatorSemicolon+' => '',
	'UI:CSVImport:SeparatorTab+' => '',
	'UI:CSVImport:SeparatorOther' => 'altri:',
	'UI:CSVImport:QualifierDoubleQuote+' => '',
	'UI:CSVImport:QualifierSimpleQuote+' => '',
	'UI:CSVImport:QualifierOther' => 'other:~~',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Tratta la prima riga come intestazione (nomi di colonna)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Salta le linee %1$s all\'inzio del file',
	'UI:CSVImport:CSVDataPreview' => 'CSV Anteprima dei dati',
	'UI:CSVImport:SelectFile' => 'Selezionare il file da importare:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Carica da un file',
	'UI:CSVImport:Tab:CopyPaste' => 'Copia e incolla i dati',
	'UI:CSVImport:Tab:Templates' => 'Modelli',
	'UI:CSVImport:PasteData' => 'Incolla i dati da importare:',
	'UI:CSVImport:PickClassForTemplate' =>  'Scegli il modello da scaricare: ',
	'UI:CSVImport:SeparatorCharacter' => 'Separatore di carattere:',
	'UI:CSVImport:TextQualifierCharacter' => 'Testo di qualificazione carattere',
	'UI:CSVImport:CommentsAndHeader' => 'Commenti e intestazione',
	'UI:CSVImport:SelectClass' => 'Selezionare la classe da importare:',
	'UI:CSVImport:AdvancedMode' => 'Modalità avanzata',
	'UI:CSVImport:AdvancedMode+' => '',
	'UI:CSVImport:SelectAClassFirst' => 'Per configurare il mapping, selezionare prima una classe.',
	'UI:CSVImport:HeaderFields' => 'Campi',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Cerca?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Per favore seleziona una mappatura per ogni campo.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Per favore seleziona almeno un criterio di ricerca',
	'UI:CSVImport:Encoding' => 'Codifica dei caratteri',
	'UI:UniversalSearchTitle' => 'iTop - Ricerca Universale',
	'UI:UniversalSearch:Error' => 'Errore: %1$s~~',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Seleziona la classe per la ricerca: ',
	'UI:Audit:Title' => 'iTop - CMDB Audit~~',
	'UI:Audit:InteractiveAudit' => 'Audit Interattivo',
	'UI:Audit:HeaderAuditRule' => 'Regole di Audit',
	'UI:Audit:HeaderNbObjects' => '# Oggetti',
	'UI:Audit:HeaderNbErrors' => '# Errori',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:RunQuery:Title' => 'iTop - Valutazione Query OQL',
	'UI:RunQuery:QueryExamples' => 'Esempi di Query',
	'UI:RunQuery:HeaderPurpose' => 'Scopo',
	'UI:RunQuery:HeaderPurpose+' => '',
	'UI:RunQuery:HeaderOQLExpression' => 'Espressioni OQL',
	'UI:RunQuery:HeaderOQLExpression+' => '',
	'UI:RunQuery:ExpressionToEvaluate' => 'Espressione da valutare: ',
	'UI:RunQuery:MoreInfo' => 'Maggiori informazioni sulla query: ',
	'UI:RunQuery:DevelopedQuery' => 'Espressione della query riqualificata:',
	'UI:RunQuery:SerializedFilter' => 'Filtro pubblicato: ',
	'UI:RunQuery:Error' => 'Si è verificato un errore durante l\'esecuzione della query: %1$s',
	'UI:Schema:Title' => 'iTop schema degli oggetti',
	'UI:Schema:CategoryMenuItem' => 'Categoria <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relazioni',
	'UI:Schema:AbstractClass' => 'Classe astratta: nessun oggetto da questa classe può essere istanziato.',
	'UI:Schema:NonAbstractClass' => 'Classe non-astratta: oggetti da questa classe possono essere istanziati.',
	'UI:Schema:ClassHierarchyTitle' => 'Gerarchia delle classi',
	'UI:Schema:AllClasses' => 'Tutte le classi',
	'UI:Schema:ExternalKey_To' => 'Chiave esterna  %1$s',
	'UI:Schema:Columns_Description' => 'Colonne: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Default: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null consentito',
	'UI:Schema:NullNotAllowed' => 'Null NON consentito',
	'UI:Schema:Attributes' => 'Attributi',
	'UI:Schema:AttributeCode' => 'Codice attributo',
	'UI:Schema:AttributeCode+' => '',
	'UI:Schema:Label' =>  'Etichetta',
	'UI:Schema:Label+' => '',
	'UI:Schema:Type' => 'Tipo',
	'UI:Schema:Type+' => '',
	'UI:Schema:Origin' => 'Origine',
	'UI:Schema:Origin+' => '',
	'UI:Schema:Description' => 'Descrizione',
	'UI:Schema:Description+' => '',
	'UI:Schema:AllowedValues' => 'Valori consentiti',
	'UI:Schema:AllowedValues+' => '',
	'UI:Schema:MoreInfo' => 'Maggiori informazioni',
	'UI:Schema:MoreInfo+' => '',
	'UI:Schema:SearchCriteria' => 'Criteri di ricerca',
	'UI:Schema:FilterCode' => 'Codice di filtro',
	'UI:Schema:FilterCode+' => '',
	'UI:Schema:FilterDescription' => 'Descrizione',
	'UI:Schema:FilterDescription+' => '',
	'UI:Schema:AvailOperators' => 'Operatori disponibili',
	'UI:Schema:AvailOperators+' => '',
	'UI:Schema:ChildClasses' => 'Classi figlio',
	'UI:Schema:ReferencingClasses' => 'Classi di rifermento',
	'UI:Schema:RelatedClasses' => 'Classi correlate',
	'UI:Schema:LifeCycle' => 'Ciclo di vita',
	'UI:Schema:Triggers' => 'Triggers',
	'UI:Schema:Relation_Code_Description' => 'Relazione <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' =>  'Giù: %1$s',
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
	'UI:Schema:LifeCycleAttributeMustPrompt' =>  'All\'utente verrà richiesto di modificare il valore',
	'UI:Schema:LifeCycleEmptyList' => 'lista vuota',
	'UI:LinksWidget:Autocomplete+' => '',
	'UI:Combo:SelectValue' => '--- seleziona un valore ---',
	'UI:Label:SelectedObjects' => 'oggetti selezionati: ',
	'UI:Label:AvailableObjects' => 'Oggetti disponibili: ',
	'UI:Link_Class_Attributes' => '%1$s attributi',
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
	'UI:Delete:NotAllowedToUpdate_Fields' =>  'Non hai i permessi per aggiornare il seguente campo(i): %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Questo oggetto non può essere cancellato perché l\'utente corrente non dispone dei diritti necessari',
	'UI:Error:CannotDeleteBecause' => 'Questo oggetto non può essere cancellato perchè: %1$s~~',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Questo oggetto non può essere cancellato perché alcune operazioni manuali devono essere effettuate prima di questo',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Questo oggetto non può essere cancellato perché alcune operazioni manuali devono essere effettuate prima di questo',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s a nome di %2$s',
	'UI:Delete:Deleted' => 'deleted~~',
	'UI:Delete:AutomaticallyDeleted' => 'automaticamente eliminato',
	'UI:Delete:AutomaticResetOf_Fields' => 'ripristino automatico del campo(i): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Pulizia di tutti i riferimenti a %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Pulizia di tutti i riferimenti a %1$d oggetti di classe %2$s...',
	'UI:Delete:Done+' => '',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s cancellato.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Soppressione di %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Soppressione di %1$d oggetti di classe %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Non può essere cancellato: %1$s~~',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Dovrebbe essere eliminato automaticamente, ma questo non è fattibile: %1$s~~',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Deve essere cancellato manualmente, ma questo non è fattibile: %1$s~~',
	'UI:Delete:WillBeDeletedAutomatically' => 'Sarà cancellato automaticamente',
	'UI:Delete:MustBeDeletedManually' => 'Deve essere cancellato manualmente',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Dovrebbero essere automaticamente aggiornati, ma: %1$s~~',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Sarà automaticamente aggiornato (reset: %1$s)~~',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d oggetti/link fanno riferimento %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d oggetti / link fanno riferimento alcuni degli oggetti da eliminare', 
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Per garantire l\'integrità del database, ogni riferimento dovrebbe essere ulteriormente eliminato',
	'UI:Delete:Consequence+' => '',
	'UI:Delete:SorryDeletionNotAllowed' => 'Spiacenti, non sei autorizzato a cancellare questo oggetto, vedere le spiegazioni di cui sopra',
	'UI:Delete:PleaseDoTheManualOperations' => 'Si prega di eseguire le operazioni manuali di cui sopra prima di richiedere la cancellazione di questo oggetto',
	'UI:Delect:Confirm_Object' =>  'Si prega di confermare che si desidera eliminare %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Si prega di confermare che si desidera eliminare i seguenti oggetti %1$d della classe %2$s.',
	'UI:WelcomeToITop' =>  'Benvenuto su iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s dettagli',
	'UI:ErrorPageTitle' => 'iTop - Errore',
	'UI:ObjectDoesNotExist' => 'Spiacenti, questo oggetto non esiste (o non si è autorizzati per vederlo).',
	'UI:SearchResultsPageTitle' => 'iTop - Risultati della ricerca',
	'UI:Search:NoSearch' => 'Niente da ricercare',
	'UI:FullTextSearchTitle_Text' => 'Risultati per "%1$s":',
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
	'UI:BulkDeleteTitle' => 'Seleziona gli oggetti che si desidera eliminare:',
	'UI:PageTitle:ObjectCreated' => 'iTop Oggetto Creato.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s creato.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Applicazione %1$s all\'oggetto: %2$s nello stato %3$s allo stato target: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'L\'oggetto non può essere scritto: %1$s~~',
	'UI:PageTitle:FatalError' => 'iTop - Fatal Error',
	'UI:SystemIntrusion' => 'Accesso negato. Hai cercato di eseguire un\'operazione che non ti è consentita.',
	'UI:FatalErrorMessage' => 'Fatal error, iTop non può continuare.',
	'UI:Error_Details' => 'Errore: %1$s.',
	'UI:PageTitle:ClassProjections' => 'iTop gestione degli utenti - proiezioni classe',
	'UI:PageTitle:ProfileProjections' => 'iTop gestione degli utenti - proiezioni profilo',
	'UI:UserManagement:Class' => 'Classe',
	'UI:UserManagement:Class+' => '',
	'UI:UserManagement:ProjectedObject' => 'Oggetto',
	'UI:UserManagement:ProjectedObject+' => '',
	'UI:UserManagement:AnyObject' => '* qualsiasi *',
	'UI:UserManagement:User' => 'Utente',
	'UI:UserManagement:User+' => '',
	'UI:UserManagement:Profile' => 'Profilo',
	'UI:UserManagement:Profile+' => '',
	'UI:UserManagement:Action:Read' => 'Leggi',
	'UI:UserManagement:Action:Read+' => '',
	'UI:UserManagement:Action:Modify' => 'Modifica',
	'UI:UserManagement:Action:Modify+' => '',
	'UI:UserManagement:Action:Delete' => 'Cancella',
	'UI:UserManagement:Action:Delete+' => '',
	'UI:UserManagement:Action:BulkRead' => 'Leggi Bulk (Export)',
	'UI:UserManagement:Action:BulkRead+' => '',
	'UI:UserManagement:Action:BulkModify' => 'Modifica Bulk',
	'UI:UserManagement:Action:BulkModify+' => '',
	'UI:UserManagement:Action:BulkDelete' => 'Cancella Bulk ',
	'UI:UserManagement:Action:BulkDelete+' => '',
	'UI:UserManagement:Action:Stimuli' => 'Stimoli',
	'UI:UserManagement:Action:Stimuli+' => '',
	'UI:UserManagement:Action' => 'Azione',
	'UI:UserManagement:Action+' => '',
	'UI:UserManagement:TitleActions' => 'Azioni',
	'UI:UserManagement:Permission' => 'Autorizzazione',
	'UI:UserManagement:Permission+' => '',
	'UI:UserManagement:Attributes' => 'Attributi',
	'UI:UserManagement:ActionAllowed:Yes' => 'Si',
	'UI:UserManagement:ActionAllowed:No' => 'No',
	'UI:UserManagement:AdminProfile+' => '',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '',
	'UI:UserManagement:GrantMatrix' => 'Grant Matrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Collegamento tra %1$s e %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Collegamento tra %1$s e %2$s',
	'Menu:AdminTools' => 'Strumenti di amministrazione', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Strumenti accessibile solo agli utenti con il profilo di amministratore', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:ChangeManagementMenu' => 'Gestione Cambi',
	'UI:ChangeManagementMenu+' => '',
	'UI:ChangeManagementMenu:Title' => 'Panoramica dei cambi',
	'UI-ChangeManagementMenu-ChangesByType' => 'Cambi per tipo',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Cambi per stato',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Cambi per gruppi di lavoro',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Cambi non ancora assegnati',
	'UI:ConfigurationManagementMenu' => 'Gestione Configurazione',
	'UI:ConfigurationManagementMenu+' => '',
	'UI:ConfigurationManagementMenu:Title' => 'Panoramica delle infrastrutture',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Oggetti infrastruttutura per tipo',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Oggetti infrastruttutura per stato',
	'UI:ConfigMgmtMenuOverview:Title' =>  'Cruscotto per Gestione configurazione',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuration Items per stato',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items per tipo',
	'UI:RequestMgmtMenuOverview:Title' => 'Cruscotto per Gestione Richieste',
	'UI-RequestManagementOverview-RequestByService' => 'Richieste degli utenti per servizio',
	'UI-RequestManagementOverview-RequestByPriority' => 'Richieste degli utenti per priorità',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Richieste degli utenti non ancora assegnate ad un agente',
	'UI:IncidentMgmtMenuOverview:Title' => 'Cruscotto Gestione degli Incidenti',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidenti per servizio',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenti per  priorità',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidenti non ancora assegnati ad un agente',
	'UI:ChangeMgmtMenuOverview:Title' => 'Cruscotto per Gestione dei Cambi',
	'UI-ChangeManagementOverview-ChangeByType' => 'Cambi per tipo',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Cambi non ancora assegnati ad un agente',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Interruzioni dovute ai cambi',
	'UI:ServiceMgmtMenuOverview:Title' => 'Cruscotto per Gestione dei Cambi',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Contratti con i clienti da rinnovarsi in 30 giorni',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Contratti con i fornitori da rinnovarsi in 30 giorni',
	'UI:ContactsMenu' => 'Contatti',
	'UI:ContactsMenu+' => '',
	'UI:ContactsMenu:Title' => 'Contatti Panoramica',
	'UI-ContactsMenu-ContactsByLocation' => 'Contatti per localizzazione',
	'UI-ContactsMenu-ContactsByType' => 'Contatti per tipo',
	'UI-ContactsMenu-ContactsByStatus' => 'Contatti per stato',
	'Menu:CSVImportMenu' =>  'Importazione CSV', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu' => 'Modello Dati', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu' => 'Esporta', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu' =>  'Notifiche', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' =>  'Configurazione delle <span class="hilite">Notifiche</span>',
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
Inoltre, tali azioni definiscono il modello da utilizzare per l\'invio della e-mail così come gli altri parametri del messaggio come, l\'importanza dei destinatari, etc
</p>
<p>Una Pagina speciale: <a href="../setup/email.test.php" target="_blank">email.test.php</a> è disponibile per il testing e la risoluzione dei problemi di configurazione PHP mail.</p>
<p>Per essere eseguite, le azioni devono essere associate ai trigger.
Quando è associata a un trigger, ad ogni azione è assegnato un numero "ordine", che specifica in quale ordine le azioni devono essere eseguite.</p>',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Triggers Disponibili',
	'UI:NotificationsMenu:OnCreate' => 'When an object is created~~',
	'UI:NotificationsMenu:OnStateEnter' => 'Quando un oggetto viene creato',
	'UI:NotificationsMenu:OnStateLeave' => 'Quando un oggetto lascia un determinato stato',
	'UI:NotificationsMenu:Actions' => 'Azioni',
	'UI:NotificationsMenu:AvailableActions' => 'Azioni disponibili',
	'Menu:AuditCategories' => 'Categorie di Audit', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Categorie di Audit', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu' => 'Esegui query', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration' => 'Dati di amministrazione', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu' => 'Ricerca universale', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu' => 'Gestione degli utenti', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu' => 'Profili', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profili', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu' => 'Account utente', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Account utente', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:iTopVersion:Short' => 'Versione iTop %1$s',
	'UI:iTopVersion:Long' => 'Versione iTop %1$s-%2$s costruita il %3$s',
	'UI:PropertiesTab' => 'Proprietà',
	'UI:OpenDocumentInNewWindow_' => 'Apri questo documento in una nuova finestra: %1$s',
	'UI:DownloadDocument_' => 'Scarica questo documento: %1$s',
	'UI:Document:NoPreview' => 'Non è disponibile un\'anteprima per questo tipo di documento',
	'UI:DeadlineMissedBy_duration' => 'Mancati %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min', 
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',  
	'UI:Deadline_Days_Hours_Minutes' => '%1$dg %2$dh %3$dmin',
	'UI:Help' => 'Aiuto',
	'UI:PasswordConfirm' => '(Conferma)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Prima di aggiungere più %1$s oggetti, salva questo oggetto.',
	'UI:DisplayThisMessageAtStartup' => 'Visualizza questo messaggio in fase di avvio',
	'UI:RelationshipGraph' => 'Visualizzazione grafica',
	'UI:RelationshipList' => 'Lista',
	'UI:OperationCancelled' => 'Operazione Annullata',
	'Portal:Title' => 'Portale Utente iTop',
	'Portal:Refresh' => 'Ricarica',
	'Portal:Back' => 'Indietro',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Crea una nuova richiesta',
	'Portal:ChangeMyPassword' => 'Cambia la mia password',
	'Portal:Disconnect' => 'Disconnetti',
	'Portal:OpenRequests' => 'Le mie richieste aperte',
	'Portal:ClosedRequests'  => 'My closed requests',
	'Portal:ResolvedRequests' => 'Le mie richieste risolte',
	'Portal:SelectService' => 'Seleziona un servizio dal catalogo:',
	'Portal:PleaseSelectOneService' => 'Si prega di selezionare un servizio',
	'Portal:SelectSubcategoryFrom_Service' => 'Seleziona una sotto-categoria per il servizio %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Si prega di selezionare una delle sottocategorie',
	'Portal:DescriptionOfTheRequest' => 'Inserisci la descrizione della tua richiesta:',
	'Portal:TitleRequestDetailsFor_Request' => 'Dettagli per la richiesta %1$s:',
	'Portal:NoOpenRequest' => 'Nessuna richiesta in questa categoria.',
	'Portal:NoClosedRequest' => 'Nessuna richiesta in questa categoria.',
	'Portal:Button:ReopenTicket' => 'Reopen this ticket',
	'Portal:Button:CloseTicket' => 'Chiudi questo ticket',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => 'Inserisci il tuo commento sulla risoluzione di questo ticket:',
	'Portal:ErrorNoContactForThisUser' =>  'Errore: l\'utente corrente non è associato ad un Contatto/Persona. Si prega di contattare l\'amministratore.',
	'Portal:Attachments' => 'Allegati',
	'Portal:AddAttachment' => ' Aggiungi allegati ',
	'Portal:RemoveAttachment' =>  ' Rimuovi allegati ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Allegato #%1$d a %2$s (%3$s)',
	'Enum:Undefined' => 'Non definito',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Giorni %2$s Ore %3$s Minuti %4$s Secondi',
	'UI:ModifyAllPageTitle' => 'Modifica Tutto',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifica %1$d oggetto della classe %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modifica %1$d oggetto della classe %2$s fuori da %3$d~~',
	'UI:Menu:ModifyAll' => 'Modifica...',
	'UI:Button:ModifyAll' => 'Modifica tutto',
	'UI:Button:PreviewModifications' => 'Anteprima Modifiche >>~~',
	'UI:ModifiedObject' => 'Oggetto Modificato',
	'UI:BulkModifyStatus' => 'Operazioni',
	'UI:BulkModifyStatus+' => '',
	'UI:BulkModifyErrors' => 'Errori (eventuali)',
	'UI:BulkModifyErrors+' => '',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Errore',
	'UI:BulkModifyStatusModified' => 'Modificato',
	'UI:BulkModifyStatusSkipped' => 'Saltato',
	'UI:BulkModify_Count_DistinctValues' => '%1$d valori distinti:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d volta(e)',
	'UI:BulkModify:N_MoreValues' => '%1$d più valori...~~',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Tentativo di impostare il campo di sola lettura: %1$s',
	'UI:FailedToApplyStimuli' => 'L\'azione non è riuscita.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifica %2$d oggetti della classe %3$s~~',
	'UI:CaseLogTypeYourTextHere' => 'Digitare il tuo testo qui:',
	'UI:CaseLog:DateFormat' => 'A-m-g H:m:s',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:~~',
	'UI:CaseLog:InitialValue' => 'Valore iniziale:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Il campo %1$s on è scrivibile, perché è comandato dalla sincronizzazione dei dati. Valore non impostato.',
	'UI:ActionNotAllowed' => 'Non hai i permessi per eseguire questa azione su questi oggetti.',
	'UI:BulkAction:NoObjectSelected' => 'Si prega di selezionare almeno un oggetto per eseguire questa operazione',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Il campo %1$s on è scrivibile, perché è comandato dalla sincronizzazione dei dati. Valore rimane invariato.',
	'UI:Button:Refresh' => 'Ricarica',
));
?>

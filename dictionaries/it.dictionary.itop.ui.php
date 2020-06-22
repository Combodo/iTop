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
 * Localized data
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
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
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
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
	'Class:QueryOQL/Attribute:fields' => 'Campi',
	'Class:QueryOQL/Attribute:fields+' => 'Lista di attributi separati da virgola (o alias.attributo) per l\'esportazione',
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
	'Class:User/Attribute:org_id' => 'Organizzazione',
	'Class:User/Attribute:org_id+' => 'Organizzazione della persona associata',
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
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profili',
	'Class:User/Attribute:profile_list+' => 'Regole per  la concessione dei diritti per quella persona',
	'Class:User/Attribute:allowed_org_list' => 'Organizzazione Consentite',
	'Class:User/Attribute:allowed_org_list+' => 'L\'utente finale è autorizzato a vedere i dati appartenenti alle seguenti organizzazioni. Se non è specificato organizzazione, vi è alcuna restrizione.',
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',

	'Class:User/Error:LoginMustBeUnique' => 'Il Login deve essere unico - "%1s" già usato',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Almeno un profilo deve essere assegnato all\'utente.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'User Internal~~',
	'Class:UserInternal+' => 'User defined within iTop~~',
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
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizzazione',
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
	'Class:URP_ActionGrant/Attribute:action+' => '',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:URP_StimulusGrant' => 'stimulus_autorizzazione',
	'Class:URP_StimulusGrant+' => '',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profilo',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profile~~',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile~~',
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
// Class: UserDashboard
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:UserDashboard' => 'User dashboard~~',
	'Class:UserDashboard+' => '~~',
	'Class:UserDashboard/Attribute:user_id' => 'User~~',
	'Class:UserDashboard/Attribute:user_id+' => '~~',
	'Class:UserDashboard/Attribute:menu_code' => 'Menu code~~',
	'Class:UserDashboard/Attribute:menu_code+' => '~~',
	'Class:UserDashboard/Attribute:contents' => 'Contents~~',
	'Class:UserDashboard/Attribute:contents+' => '~~',
));

//
// Expression to Natural language
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'BooleanLabel:yes' => 'si',
	'BooleanLabel:no' => 'no',
	'UI:Login:Title' => 'iTop login~~',
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
	'UI:Button:Save' => 'Save~~',
	'UI:Button:Cancel' => 'Cancella',
	'UI:Button:Close' => 'Close~~',
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
	'UI:Button:Evaluate:Title' => ' Valuta (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Aggiungi... ~~',
	'UI:Button:BrowseObjects' => ' Sfoglia... ~~',
	'UI:Button:Add' => ' Aggiungi ~~',
	'UI:Button:AddToList' => ' << Aggiungi ~~',
	'UI:Button:RemoveFromList' => ' Rimuovi >> ~~',
	'UI:Button:FilterList' => ' Filtra... ~~',
	'UI:Button:Create' => ' Crea ~~',
	'UI:Button:Delete' => ' Cancella ! ~~',
	'UI:Button:Rename' => ' Rename... ~~',
	'UI:Button:ChangePassword' => ' Cambia Password ~~',
	'UI:Button:ResetPassword' => ' Resetta Password ~~',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Cerca',
	'UI:ClickToCreateNew' => 'Crea un nuovo %1$s~~',
	'UI:SearchFor_Class' => 'Cerca l\'oggetto %1$s',
	'UI:NoObjectToDisplay' => 'Nessun oggetto da mostrare.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
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
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'L\'applicazione è attualmente in manutenzione',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

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
	'UI:Menu:Manage' => 'Gestisci...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export...',
	'UI:Menu:Modify' => 'Modifica...',
	'UI:Menu:Delete' => 'Cancella...',
	'UI:Menu:BulkDelete' => 'Cancella...',
	'UI:UndefinedObject' => 'non definito',
	'UI:Document:OpenInNewWindow:Download' => 'Apri in una nuova finestra: %1$s, Scarica: %2$s',
	'UI:SplitDateTime-Date' => 'date~~',
	'UI:SplitDateTime-Time' => 'time~~',
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
	'UI:SearchValue:NbSelected' => '# selected~~',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- selezionare uno --',
	'UI:Login:Welcome' => 'Benvenuti su iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Errato login/password, si prega di riprovare.',
	'UI:Login:IdentifyYourself' => 'Identifica te stesso prima di continuare',
	'UI:Login:UserNamePrompt' => 'Nome Utente',
	'UI:Login:PasswordPrompt' => 'Password',
	'UI:Login:ForgotPwd' => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm' => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+' => 'iTop can send you an email in which you will find instructions to follow to reset your account.~~',
	'UI:Login:ResetPassword' => 'Send now!~~',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s~~',
	'UI:Login:SeparatorOr' => 'O',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login~~',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.~~',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.~~',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated to a person.~~',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.~~',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Reset your iTop password~~',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your iTop password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',

	'UI:ResetPwd-Title' => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready' => 'The password has been changed.~~',
	'UI:ResetPwd-Login' => 'Click here to login...~~',

	'UI:Login:About' => '~~',
	'UI:Login:ChangeYourPassword' => 'Cambia la tua password',
	'UI:Login:OldPasswordPrompt' => 'Vecchia password',
	'UI:Login:NewPasswordPrompt' => 'Nuova password',
	'UI:Login:RetypeNewPasswordPrompt' => 'Riscrivi la nuova password',
	'UI:Login:IncorrectOldPassword' => 'Errore: la vecchia password non è corretta',
	'UI:LogOffMenu' => 'Log off',
	'UI:LogOff:ThankYou' => 'Grazie per aver scelto iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Clicca qui per effettuare il login di nuovo...',
	'UI:ChangePwdMenu' => 'Cambia Password...',
	'UI:Login:PasswordChanged' => 'Password successfully set!~~',
	'UI:AccessRO-All' => 'iTop è di sola lettura',
	'UI:AccessRO-Users' => 'iTop è di sola lettura per gli utenti finali',
	'UI:ApplicationEnvironment' => 'Application environment: %1$s~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nuova password e la nuova password digitata nuovamente non corrispondono !',
	'UI:Button:Login' => 'Entra in iTop',
	'UI:Login:Error:AccessRestricted' => 'L\'accesso a iTop è limitato. Si prega di contattare un amministratore iTop.',
	'UI:Login:Error:AccessAdmin' => 'Accesso limitato alle persone che hanno privilegi di amministratore. Si prega di contattare un amministratore iTop.',
	'UI:Login:Error:WrongOrganizationName' => 'Organizzazione sconosciuta',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Più contatti hanno la stessa e-mail',
	'UI:Login:Error:NoValidProfiles' => 'Nessun profilo valido fornito',
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
	'UI:CSVImport:ObjectsWereModified' => '%1$d oggetto(i) sono stati modificati.',
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
	'UI:CSVImport:PickClassForTemplate' => 'Scegli il modello da scaricare: ',
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
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Per favore seleziona almeno un criterio di ricerca',
	'UI:CSVImport:Encoding' => 'Codifica dei caratteri',
	'UI:UniversalSearchTitle' => 'iTop - Ricerca Universale',
	'UI:UniversalSearch:Error' => 'Errore: %1$s~~',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Seleziona la classe per la ricerca: ',

	'UI:CSVReport-Value-Modified' => 'Modified~~',
	'UI:CSVReport-Value-SetIssue' => 'Could not be changed - reason: %1$s~~',
	'UI:CSVReport-Value-ChangeIssue' => 'Could not be changed to %1$s - reason: %2$s~~',
	'UI:CSVReport-Value-NoMatch' => 'No match~~',
	'UI:CSVReport-Value-Missing' => 'Missing mandatory value~~',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: found %1$s objects~~',
	'UI:CSVReport-Row-Unchanged' => 'unchanged~~',
	'UI:CSVReport-Row-Created' => 'created~~',
	'UI:CSVReport-Row-Updated' => 'updated %1$d cols~~',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols~~',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s~~',
	'UI:CSVReport-Value-Issue-Null' => 'Null not allowed~~',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object not found~~',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Found %1$d matches~~',
	'UI:CSVReport-Value-Issue-Readonly' => 'The attribute \'%1$s\' is read-only and cannot be modified (current value: %2$s, proposed value: %3$s)~~',
	'UI:CSVReport-Value-Issue-Format' => 'Failed to process input: %1$s~~',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': no match found, check spelling~~',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s~~',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributes not consistent with each others: %1$s~~',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unexpected attribute value(s)~~',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Could not be created, due to missing external key(s): %1$s~~',
	'UI:CSVReport-Row-Issue-DateFormat' => 'wrong date format~~',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'failed to reconcile~~',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation~~',
	'UI:CSVReport-Row-Issue-Internal' => 'Internal error: %1$s, %2$s~~',

	'UI:CSVReport-Icon-Unchanged' => 'Unchanged~~',
	'UI:CSVReport-Icon-Modified' => 'Modified~~',
	'UI:CSVReport-Icon-Missing' => 'Missing~~',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated~~',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated~~',
	'UI:CSVReport-Icon-Created' => 'Created~~',
	'UI:CSVReport-Object-ToCreate' => 'Object will be created~~',
	'UI:CSVReport-Object-Created' => 'Object created~~',
	'UI:CSVReport-Icon-Error' => 'Error~~',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s~~',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s~~',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% of the loaded objects have errors and will be ignored.~~',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% of the loaded objects will be created.~~',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% of the loaded objects will be modified.~~',

	'UI:CSVExport:AdvancedMode' => 'Advanced mode~~',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.~~',
	'UI:CSVExport:LostChars' => 'Encoding issue~~',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').~~',

	'UI:Audit:Title' => 'iTop - CMDB Audit~~',
	'UI:Audit:InteractiveAudit' => 'Audit Interattivo',
	'UI:Audit:HeaderAuditRule' => 'Regole di Audit',
	'UI:Audit:HeaderNbObjects' => '# Oggetti',
	'UI:Audit:HeaderNbErrors' => '# Errori',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.~~',

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
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Si è verificato un errore durante l\'esecuzione della query: %1$s',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries~~',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
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
	'UI:Schema:Label' => 'Etichetta',
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
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => '',
	'UI:Edit:TestQuery' => 'Test query~~',
	'UI:Combo:SelectValue' => '--- seleziona un valore ---',
	'UI:Label:SelectedObjects' => 'oggetti selezionati: ',
	'UI:Label:AvailableObjects' => 'Oggetti disponibili: ',
	'UI:Link_Class_Attributes' => '%1$s attributi',
	'UI:SelectAllToggle+' => '',
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
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
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
	'UI:Delect:Confirm_Object' => 'Si prega di confermare che si desidera eliminare %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Si prega di confermare che si desidera eliminare i seguenti oggetti %1$d della classe %2$s.',
	'UI:WelcomeToITop' => 'Benvenuto su iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s dettagli',
	'UI:ErrorPageTitle' => 'iTop - Errore',
	'UI:ObjectDoesNotExist' => 'Spiacenti, questo oggetto non esiste (o non si è autorizzati per vederlo).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => 'iTop - Risultati della ricerca',
	'UI:SearchResultsTitle' => 'Risultati della ricerca',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => 'Niente da ricercare',
	'UI:Search:NeedleTooShort' => 'The search string \\"%1$s\\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \\"%1$s\\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => 'Risultati per "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => 'Trovato l\'oggetto(i) %1$d della classe %2$s.',
	'UI:Search:NoObjectFound' => 'Nessun oggetto trovato.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modifica',
	'UI:ModificationTitle_Class_Object' => 'Modifica di %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Clone %1$s - %2$s modifica',
	'UI:CloneTitle_Class_Object' => 'Clone di %1$s: <span class=\\"hilite\\">%2$s</span>',
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
	'Menu:SystemTools' => 'Sistema',

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

	'UI:ConfigMgmtMenuOverview:Title' => 'Cruscotto per Gestione configurazione',
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

	'Menu:CSVImportMenu' => 'Importazione CSV', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Modello Dati', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Esporta', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notifiche', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configurazione delle <span class="hilite">Notifiche</span>',
	'UI:NotificationsMenu:Help' => 'Aiuto',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop le notifiche sono completamente personalizzabili. Essi si basano su due serie di oggetti: <i>trigger e azioni</i>.</p>
<p><i>I <b>trigger</b></i> definiscono quando verrà eseguita una notifica. Ci sono diversi trigger come parte del nucleo di iTop, ma altri possono essere portati da estensioni:
<ol>
	 <li>Alcuni trigger sono eseguiti quando un oggetto della classe specificata viene <b>creato</b>, <b>aggiornato</b> o <b>cancellato</b>.</li>
	 <li>Alcuni trigger sono eseguiti quando un oggetto di una data classe <b>entra</b> o <b>lascia</b> uno <b>stato specificato</b>.</li>
	 <li>Alcuni trigger sono eseguiti quando una <b>soglia</b> su <b>TTO</b> o <b>TTR</b> è stata <b>raggiunta</b>.</li>
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

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Categorie di Audit', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Categorie di Audit', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Esegui query', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)

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

	'UI:iTopVersion:Short' => 'Versione %1$s %2$s',
	'UI:iTopVersion:Long' => 'Versione %1$s %2$s-%3$s costruita il %4$s',
	'UI:PropertiesTab' => 'Proprietà',

	'UI:OpenDocumentInNewWindow_' => 'Apri questo documento in una nuova finestra: %1$s',
	'UI:DownloadDocument_' => 'Scarica questo documento: %1$s',
	'UI:Document:NoPreview' => 'Non è disponibile un\'anteprima per questo tipo di documento',
	'UI:Download-CSV' => 'Download %1$s~~',

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
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Operazione Annullata',
	'UI:ElementsDisplayed' => 'Filtering~~',
	'UI:RelationGroupNumber_N' => 'Group #%1$d~~',
	'UI:Relation:ExportAsPDF' => 'Export as PDF...~~',
	'UI:RelationOption:GroupingThreshold' => 'Grouping threshold~~',
	'UI:Relation:AdditionalContextInfo' => 'Additional context info~~',
	'UI:Relation:NoneSelected' => 'None~~',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...~~',
	'UI:Relation:DrillDown' => 'Details...~~',
	'UI:Relation:PDFExportOptions' => 'PDF Export Options~~',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s~~',
	'UI:RelationOption:Untitled' => 'Untitled~~',
	'UI:Relation:Key' => 'Key~~',
	'UI:Relation:Comments' => 'Comments~~',
	'UI:RelationOption:Title' => 'Title~~',
	'UI:RelationOption:IncludeList' => 'Include the list of objects~~',
	'UI:RelationOption:Comments' => 'Comments~~',
	'UI:Button:Export' => 'Export~~',
	'UI:Relation:PDFExportPageFormat' => 'Page format~~',
	'UI:PageFormat_A3' => 'A3~~',
	'UI:PageFormat_A4' => 'A4~~',
	'UI:PageFormat_Letter' => 'Letter~~',
	'UI:Relation:PDFExportPageOrientation' => 'Page orientation~~',
	'UI:PageOrientation_Portrait' => 'Portrait~~',
	'UI:PageOrientation_Landscape' => 'Landscape~~',
	'UI:RelationTooltip:Redundancy' => 'Redundancy~~',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# of impacted items: %1$d / %2$d~~',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Critical threshold: %1$d / %2$d~~',
	'Portal:Title' => 'Portale Utente iTop',
	'Portal:NoRequestMgmt' => 'Dear %1$s, you have been redirected to this page because your account is configured with the profile \'Portal user\'. Unfortunately, iTop has not been installed with the feature \'Request Management\'. Please contact your administrator.~~',
	'Portal:Refresh' => 'Ricarica',
	'Portal:Back' => 'Indietro',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request~~',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Crea una nuova richiesta',
	'Portal:CreateNewRequestItil' => 'Crea una nuova richiesta',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
	'Portal:ChangeMyPassword' => 'Cambia la mia password',
	'Portal:Disconnect' => 'Disconnetti',
	'Portal:OpenRequests' => 'Le mie richieste aperte',
	'Portal:ClosedRequests' => 'My closed requests',
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
	'Portal:ErrorNoContactForThisUser' => 'Errore: l\'utente corrente non è associato ad un Contatto/Persona. Si prega di contattare l\'amministratore.',
	'Portal:Attachments' => 'Allegati',
	'Portal:AddAttachment' => ' Aggiungi allegati ',
	'Portal:RemoveAttachment' => ' Rimuovi allegati ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Allegato #%1$d a %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
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
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:~~',
	'UI:CaseLog:InitialValue' => 'Valore iniziale:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Il campo %1$s on è scrivibile, perché è comandato dalla sincronizzazione dei dati. Valore non impostato.',
	'UI:ActionNotAllowed' => 'Non hai i permessi per eseguire questa azione su questi oggetti.',
	'UI:BulkAction:NoObjectSelected' => 'Si prega di selezionare almeno un oggetto per eseguire questa operazione',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Il campo %1$s on è scrivibile, perché è comandato dalla sincronizzazione dei dati. Valore rimane invariato.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objects (%2$s objects selected).~~',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objects.~~',
	'UI:Pagination:PageSize' => '%1$s objects per page~~',
	'UI:Pagination:PagesLabel' => 'Pages:~~',
	'UI:Pagination:All' => 'All~~',
	'UI:HierarchyOf_Class' => 'Hierarchy of %1$s~~',
	'UI:Preferences' => 'Preferences...~~',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Favorite Organizations~~',
	'UI:FavoriteOrganizations+' => 'Check in the list below the organizations that you want to see in the drop-down menu for a quick access. Note that this is not a security setting, objects from any organization are still visible and can be accessed by selecting \\"All Organizations\\" in the drop-down list.~~',
	'UI:FavoriteLanguage' => 'Language of the User Interface~~',
	'UI:Favorites:SelectYourLanguage' => 'Select your preferred language~~',
	'UI:FavoriteOtherSettings' => 'Other Settings~~',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default length for lists:  %1$s items per page~~',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Any modification will be discarded.~~',
	'UI:CancelConfirmationMessage' => 'You will loose your changes. Continue anyway?~~',
	'UI:AutoApplyConfirmationMessage' => 'Some changes have not been applied yet. Do you want itop to take them into account?~~',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Add To Dashboard...~~',
	'UI:Button:Refresh' => 'Ricarica',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Configure This List...~~',
	'UI:ListConfigurationTitle' => 'List Configuration~~',
	'UI:ColumnsAndSortOrder' => 'Columns and sort order:~~',
	'UI:UseDefaultSettings' => 'Use the Default Settings~~',
	'UI:UseSpecificSettings' => 'Use the Following Settings:~~',
	'UI:Display_X_ItemsPerPage' => 'Display %1$s items per page~~',
	'UI:UseSavetheSettings' => 'Save the Settings~~',
	'UI:OnlyForThisList' => 'Only for this list~~',
	'UI:ForAllLists' => 'Default for all lists~~',
	'UI:ExtKey_AsLink' => '%1$s (Link)~~',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)~~',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)~~',
	'UI:Button:MoveUp' => 'Move Up~~',
	'UI:Button:MoveDown' => 'Move Down~~',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class \\"%1$s\\". You may try \\"%2$s\\" instead.~~',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class \\"%1$s\\"~~',

	'UI:Dashboard:Edit' => 'Edit This Page...~~',
	'UI:Dashboard:Revert' => 'Revert To Original Version...~~',
	'UI:Dashboard:RevertConfirm' => 'Every changes made to the original version will be lost. Please confirm that you want to do this.~~',
	'UI:ExportDashBoard' => 'Export to a file~~',
	'UI:ImportDashBoard' => 'Import from a file...~~',
	'UI:ImportDashboardTitle' => 'Import From a File~~',
	'UI:ImportDashboardText' => 'Select a dashboard file to import:~~',


	'UI:DashletCreation:Title' => 'Create a new Dashlet~~',
	'UI:DashletCreation:Dashboard' => 'Dashboard~~',
	'UI:DashletCreation:DashletType' => 'Dashlet Type~~',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard~~',

	'UI:DashboardEdit:Title' => 'Dashboard Editor~~',
	'UI:DashboardEdit:DashboardTitle' => 'Title~~',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',

	'UI:DashboardEdit:Layout' => 'Layout~~',
	'UI:DashboardEdit:Properties' => 'Dashboard Properties~~',
	'UI:DashboardEdit:Dashlets' => 'Available Dashlets~~',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Properties~~',

	'UI:Form:Property' => 'Property~~',
	'UI:Form:Value' => 'Value~~',

	'UI:DashletUnknown:Label' => 'Unknown~~',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)~~',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.~~',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.~~',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").~~',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletProxy:Label' => 'Proxy~~',
	'UI:DashletProxy:Description' => 'Proxy dashlet~~',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").~~',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletPlainText:Label' => 'Text~~',
	'UI:DashletPlainText:Description' => 'Plain text (no formatting)~~',
	'UI:DashletPlainText:Prop-Text' => 'Text~~',
	'UI:DashletPlainText:Prop-Text:Default' => 'Please enter some text here...~~',

	'UI:DashletObjectList:Label' => 'Object list~~',
	'UI:DashletObjectList:Description' => 'Object list dashlet~~',
	'UI:DashletObjectList:Prop-Title' => 'Title~~',
	'UI:DashletObjectList:Prop-Query' => 'Query~~',
	'UI:DashletObjectList:Prop-Menu' => 'Menu~~',

	'UI:DashletGroupBy:Prop-Title' => 'Title~~',
	'UI:DashletGroupBy:Prop-Query' => 'Query~~',
	'UI:DashletGroupBy:Prop-Style' => 'Style~~',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Group by...~~',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)~~',
	'UI:DashletGroupBy:MissingGroupBy' => 'Please select the field on which the objects will be grouped together~~',

	'UI:DashletGroupByPie:Label' => 'Pie Chart~~',
	'UI:DashletGroupByPie:Description' => 'Pie Chart~~',
	'UI:DashletGroupByBars:Label' => 'Bar Chart~~',
	'UI:DashletGroupByBars:Description' => 'Bar Chart~~',
	'UI:DashletGroupByTable:Label' => 'Group By (table)~~',
	'UI:DashletGroupByTable:Description' => 'List (Grouped by a field)~~',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Aggregation function~~',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Function attribute~~',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direction~~',
	'UI:DashletGroupBy:Prop-OrderField' => 'Order by~~',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit~~',

	'UI:DashletGroupBy:Order:asc' => 'Ascending~~',
	'UI:DashletGroupBy:Order:desc' => 'Descending~~',

	'UI:GroupBy:count' => 'Count~~',
	'UI:GroupBy:count+' => 'Number of elements~~',
	'UI:GroupBy:sum' => 'Sum~~',
	'UI:GroupBy:sum+' => 'Sum of %1$s~~',
	'UI:GroupBy:avg' => 'Average~~',
	'UI:GroupBy:avg+' => 'Average of %1$s~~',
	'UI:GroupBy:min' => 'Minimum~~',
	'UI:GroupBy:min+' => 'Minimum of %1$s~~',
	'UI:GroupBy:max' => 'Maximum~~',
	'UI:GroupBy:max+' => 'Maximum of %1$s~~',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header~~',
	'UI:DashletHeaderStatic:Description' => 'Displays an horizontal separator~~',
	'UI:DashletHeaderStatic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icon~~',

	'UI:DashletHeaderDynamic:Label' => 'Header with statistics~~',
	'UI:DashletHeaderDynamic:Description' => 'Header with stats (grouped by...)~~',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtitle~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query~~',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Group by~~',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Values~~',

	'UI:DashletBadge:Label' => 'Badge~~',
	'UI:DashletBadge:Description' => 'Object Icon with new/search~~',
	'UI:DashletBadge:Prop-Class' => 'Class~~',

	'DayOfWeek-Sunday' => 'Sunday~~',
	'DayOfWeek-Monday' => 'Monday~~',
	'DayOfWeek-Tuesday' => 'Tuesday~~',
	'DayOfWeek-Wednesday' => 'Wednesday~~',
	'DayOfWeek-Thursday' => 'Thursday~~',
	'DayOfWeek-Friday' => 'Friday~~',
	'DayOfWeek-Saturday' => 'Saturday~~',
	'Month-01' => 'January~~',
	'Month-02' => 'February~~',
	'Month-03' => 'March~~',
	'Month-04' => 'April~~',
	'Month-05' => 'May~~',
	'Month-06' => 'June~~',
	'Month-07' => 'July~~',
	'Month-08' => 'August~~',
	'Month-09' => 'September~~',
	'Month-10' => 'October~~',
	'Month-11' => 'November~~',
	'Month-12' => 'December~~',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Su~~',
	'DayOfWeek-Monday-Min' => 'Mo~~',
	'DayOfWeek-Tuesday-Min' => 'Tu~~',
	'DayOfWeek-Wednesday-Min' => 'We~~',
	'DayOfWeek-Thursday-Min' => 'Th~~',
	'DayOfWeek-Friday-Min' => 'Fr~~',
	'DayOfWeek-Saturday-Min' => 'Sa~~',
	'Month-01-Short' => 'Jan~~',
	'Month-02-Short' => 'Feb~~',
	'Month-03-Short' => 'Mar~~',
	'Month-04-Short' => 'Apr~~',
	'Month-05-Short' => 'May~~',
	'Month-06-Short' => 'Jun~~',
	'Month-07-Short' => 'Jul~~',
	'Month-08-Short' => 'Aug~~',
	'Month-09-Short' => 'Sep~~',
	'Month-10-Short' => 'Oct~~',
	'Month-11-Short' => 'Nov~~',
	'Month-12-Short' => 'Dec~~',
	'Calendar-FirstDayOfWeek' => '0~~', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Create a Shortcut...~~',
	'UI:ShortcutRenameDlg:Title' => 'Rename the shortcut~~',
	'UI:ShortcutListDlg:Title' => 'Create a shortcut for the list~~',
	'UI:ShortcutDelete:Confirm' => 'Please confirm that wou wish to delete the shortcut(s).~~',
	'Menu:MyShortcuts' => 'My Shortcuts~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Shortcut~~',
	'Class:Shortcut+' => '~~',
	'Class:Shortcut/Attribute:name' => 'Name~~',
	'Class:Shortcut/Attribute:name+' => 'Label used in the menu and page title~~',
	'Class:ShortcutOQL' => 'Search result shortcut~~',
	'Class:ShortcutOQL+' => '~~',
	'Class:ShortcutOQL/Attribute:oql' => 'Query~~',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for~~',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds~~',

	'UI:FillAllMandatoryFields' => 'Please fill all mandatory fields.~~',
	'UI:ValueMustBeSet' => 'Please specify a value~~',
	'UI:ValueMustBeChanged' => 'Please change the value~~',
	'UI:ValueInvalidFormat' => 'Invalid format~~',

	'UI:CSVImportConfirmTitle' => 'Please confirm the operation~~',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?~~',
	'UI:CSVImportError_items' => 'Errors: %1$d~~',
	'UI:CSVImportCreated_items' => 'Created: %1$d~~',
	'UI:CSVImportModified_items' => 'Modified: %1$d~~',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d~~',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format~~',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)~~',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s~~',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Available placeholders:<table>
<tr><td>Y</td><td>year (4 digits, e.g. 2016)</td></tr>
<tr><td>y</td><td>year (2 digits, e.g. 16 for 2016)</td></tr>
<tr><td>m</td><td>month (2 digits, e.g. 01..12)</td></tr>
<tr><td>n</td><td>month (1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>d</td><td>day (2 digits, e.g. 01..31)</td></tr>
<tr><td>j</td><td>day (1 or 2 digits no leading zero, e.g. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 hour, 2 digits, e.g. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 hour, 2 digits, e.g. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 hour, 1 or 2 digits no leading zero, e.g. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 hour, 1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>a</td><td>hour, am or pm (lowercase)</td></tr>
<tr><td>A</td><td>hour, AM or PM (uppercase)</td></tr>
<tr><td>i</td><td>minutes (2 digits, e.g. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 digits, e.g. 00..59)</td></tr>
</table>~~',

	'UI:Button:Remove' => 'Remove~~',
	'UI:AddAnExisting_Class' => 'Add objects of type %1$s...~~',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s~~',

	'UI:AboutBox' => 'About iTop...~~',
	'UI:About:Title' => 'About iTop~~',
	'UI:About:DataModel' => 'Data model~~',
	'UI:About:Support' => 'Support information~~',
	'UI:About:Licenses' => 'Licenses~~',
	'UI:About:InstallationOptions' => 'Installation options~~',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'You are disconnected. You must identify yourself to continue using the application.~~',
	'UI:DisconnectedDlgTitle' => 'Warning!~~',
	'UI:LoginAgain' => 'Login again~~',
	'UI:StayOnThePage' => 'Stay on this page~~',

	'ExcelExporter:ExportMenu' => 'Excel Export...~~',
	'ExcelExporter:ExportDialogTitle' => 'Excel Export~~',
	'ExcelExporter:ExportButton' => 'Export~~',
	'ExcelExporter:DownloadButton' => 'Download %1$s~~',
	'ExcelExporter:RetrievingData' => 'Retrieving data...~~',
	'ExcelExporter:BuildingExcelFile' => 'Building the Excel file...~~',
	'ExcelExporter:Done' => 'Done.~~',
	'ExcelExport:AutoDownload' => 'Start the download automatically when the export is ready~~',
	'ExcelExport:PreparingExport' => 'Preparing the export...~~',
	'ExcelExport:Statistics' => 'Statistics~~',
	'portal:legacy_portal' => 'End-User Portal~~',
	'portal:backoffice' => 'iTop Back-Office User Interface~~',

	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.~~',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.~~',
	'UI:CurrentObjectLockExpired' => 'The lock to prevent concurrent modifications of the object has expired.~~',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.~~',
	'UI:ConcurrentLockKilled' => 'The lock preventing modifications on the current object has been deleted.~~',
	'UI:Menu:KillConcurrentLock' => 'Kill the Concurrent Modification Lock !~~',

	'UI:Menu:ExportPDF' => 'Export as PDF...~~',
	'UI:Menu:PrintableVersion' => 'Printer friendly version~~',

	'UI:BrowseInlineImages' => 'Browse images...~~',
	'UI:UploadInlineImageLegend' => 'Upload a new image~~',
	'UI:SelectInlineImageToUpload' => 'Select the image to upload~~',
	'UI:AvailableInlineImagesLegend' => 'Available images~~',
	'UI:NoInlineImage' => 'There is no image available on the server. Use the "Browse" button above to select an image from your computer and upload it to the server.~~',

	'UI:ToggleFullScreen' => 'Toggle Maximize / Minimize~~',
	'UI:Button:ResetImage' => 'Recover the previous image~~',
	'UI:Button:RemoveImage' => 'Remove the image~~',
	'UI:UploadNotSupportedInThisMode' => 'The modification of images or files is not supported in this mode.~~',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Minimize / Expand~~',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto submit has been disabled for this class~~',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Based on your preferences, obsolete data are hidden~~',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.~~',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Add new criteria~~',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recently used~~',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Most popular~~',
	'UI:Search:AddCriteria:List:Others:Title' => 'Others~~',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'None yet.~~',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s is empty~~',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s is not empty~~',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s equals %2$s~~',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contains %2$s~~',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s starts with %2$s~~',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s ends with %2$s~~',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s matches %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s~~',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s~~',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s between [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s until %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s up to %2$s~~',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s~~',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Any~~',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s~~',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s is defined~~',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s is not defined~~',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s~~',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Any~~',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s is defined~~',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s is not defined~~',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Any~~',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Is empty~~',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Is not empty~~',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Equals~~',
	'UI:Search:Criteria:Operator:Default:Between' => 'Between~~',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contains~~',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Starts with~~',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Ends with~~',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Regular exp.~~',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Equals~~',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Greater~~',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Greater / equals~~',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Less~~',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Less / equals~~',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Different~~',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...~~',
	'UI:Search:Value:Search:Placeholder' => 'Search...~~',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Start typing for possible values.~~',
	'UI:Search:Value:Autocomplete:Wait' => 'Please wait...~~',
	'UI:Search:Value:Autocomplete:NoResult' => 'No result.~~',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Check all / none~~',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Check all / none visibles~~',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'From~~',
	'UI:Search:Criteria:Numeric:Until' => 'To~~',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Any~~',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Any~~',
	'UI:Search:Criteria:DateTime:From' => 'From~~',
	'UI:Search:Criteria:DateTime:FromTime' => 'From~~',
	'UI:Search:Criteria:DateTime:Until' => 'until~~',
	'UI:Search:Criteria:DateTime:UntilTime' => 'until~~',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Any date~~',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Children of the selected objects will be included.~~',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtered~~',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtered on %1$s~~',
));

//
// Expression to Natural language
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Expression:Operator:AND' => ' AND ~~',
	'Expression:Operator:OR' => ' OR ~~',
	'Expression:Operator:=' => ': ~~',

	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',

	'Expression:Unit:Long:DAY' => 'day(s)~~',
	'Expression:Unit:Long:HOUR' => 'hour(s)~~',
	'Expression:Unit:Long:MINUTE' => 'minute(s)~~',

	'Expression:Verb:NOW' => 'now~~',
	'Expression:Verb:ISNULL' => ': undefined~~',
));

//
// iTop Newsroom menu
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'UI:Newsroom:NoNewMessage' => 'No new message~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));

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
 * @author   Stephan Rosenke <stephan.rosenke@itomig.de>
 * @license   http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//
// Class: menuNode
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:menuNode' => 'Menü-Punkt',
	'Class:menuNode+' => 'Einstellungen der Hauptmenü-Elemente',
	'Class:menuNode/Attribute:name' => 'Menü-Name',
	'Class:menuNode/Attribute:name+' => 'Kurzname für dieses Menü',
	'Class:menuNode/Attribute:label' => 'Menü-Beschreibung',
	'Class:menuNode/Attribute:label+' => 'Ausführliche Beschreibung für dieses Menü',
	'Class:menuNode/Attribute:hyperlink' => 'Hyperlink',
	'Class:menuNode/Attribute:hyperlink+' => 'Hyperlink zu dieser Seite',
	'Class:menuNode/Attribute:icon_path' => 'Menü-Icon',
	'Class:menuNode/Attribute:icon_path+' => 'Pfad zu dem Menü-Icon',
	'Class:menuNode/Attribute:template' => 'Template',
	'Class:menuNode/Attribute:template+' => 'HTML-Template zur Ansicht',
	'Class:menuNode/Attribute:type' => 'Typ',
	'Class:menuNode/Attribute:type+' => 'Menü-Typ',
	'Class:menuNode/Attribute:type/Value:application' => 'Anwendung',
	'Class:menuNode/Attribute:type/Value:application+' => 'Anwendung',
	'Class:menuNode/Attribute:type/Value:user' => 'Benutzer',
	'Class:menuNode/Attribute:type/Value:user+' => 'Benutzer',
	'Class:menuNode/Attribute:type/Value:administrator' => 'Administrator',
	'Class:menuNode/Attribute:type/Value:administrator+' => 'Administrator',
	'Class:menuNode/Attribute:rank' => 'Zeige den Rang',
	'Class:menuNode/Attribute:rank+' => 'Sortierreihenfolge für das Menü',
	'Class:menuNode/Attribute:parent_id' => 'Übergeordneter Menüeintrag',
	'Class:menuNode/Attribute:parent_id+' => 'Übergeordneter Menüeintrag',
	'Class:menuNode/Attribute:parent_name' => 'Übergeordneter Menüeintrag',
	'Class:menuNode/Attribute:parent_name+' => 'Übergeordneter Menüeintrag',
	'Class:menuNode/Attribute:user_id' => 'Besitzer des Menüs',
	'Class:menuNode/Attribute:user_id+' => 'Benutzer, die dieses Menü besitzen (für benutzerdefinierte Menüs)',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:AuditCategory' => 'Audit-Kategorie',
	'Class:AuditCategory+' => 'Ein Abschnitt aller Audits',
	'Class:AuditCategory/Attribute:name' => 'Kategorienname',
	'Class:AuditCategory/Attribute:name+' => 'Kurzname für diese Kategorie',
	'Class:AuditCategory/Attribute:description' => 'Beschreibung der Audit-Kategorien',
	'Class:AuditCategory/Attribute:description+' => 'Ausführliche Beschreibung dieser Audit-Kategorie',
	'Class:AuditCategory/Attribute:definition_set' => 'Definition Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL-Ausdrücke, die den Umfang der zu auditierenden Objekte festlegen',
	'Class:AuditCategory/Attribute:rules_list' => 'Audit-Regeln',
	'Class:AuditCategory/Attribute:rules_list+' => 'Audit-Regeln für diese Kategorie',
));

//
// Class: AuditRule
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:AuditRule' => 'Audit-Regel',
	'Class:AuditRule+' => 'Eine Regel um eine gegebene Audit-Kategorie zu überprüfen',
	'Class:AuditRule/Attribute:name' => 'Regelname',
	'Class:AuditRule/Attribute:name+' => 'Kurzname für diese Regel',
	'Class:AuditRule/Attribute:description' => 'Beschreibung der Audit-Regel',
	'Class:AuditRule/Attribute:description+' => 'Ausführliche Beschreibung dieser Audit-Regel',
	'Class:AuditRule/Attribute:query' => 'Durchzuführende Abfrage',
	'Class:AuditRule/Attribute:query+' => 'Die auszuführende OQL-Abfrage',
	'Class:AuditRule/Attribute:valid_flag' => 'Gültiges Objekt?',
	'Class:AuditRule/Attribute:valid_flag+' => 'true falls die Regel ein gültiges Objekt zurückgibt, andernfalls false',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => 'Kategorie',
	'Class:AuditRule/Attribute:category_id+' => 'Kategorie für diese Regel',
	'Class:AuditRule/Attribute:category_name' => 'Kategorie',
	'Class:AuditRule/Attribute:category_name+' => 'Kategorienname für diese Regel',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: URP_Users
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:User' => 'Benutzer',
	'Class:User+' => 'Benutzer-Login',
	'Class:User/Attribute:finalclass' => 'Typ des Benutzerkontos',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Kontakt (Person)',
	'Class:User/Attribute:contactid+' => 'Persönliche Details der Geschäftsdaten',
	'Class:User/Attribute:last_name' => 'Nachname',
	'Class:User/Attribute:last_name+' => 'Nachname des Kontaktes',
	'Class:User/Attribute:first_name' => 'Vorname',
	'Class:User/Attribute:first_name+' => 'Vorname des Kontaktes',
	'Class:User/Attribute:email' => 'Email-Adresse',
	'Class:User/Attribute:email+' => 'Email-Adresse des Kontaktes',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Benutzer-Anmeldename',
	'Class:User/Attribute:language' => 'Sprache',
	'Class:User/Attribute:language+' => 'Benutzersprache',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profile',
	'Class:User/Attribute:profile_list+' => 'Rollen, Rechtemanagement für diese Person',
	'Class:User/Attribute:allowed_org_list' => '',
	'Class:User/Attribute:allowed_org_list+' => 'Der Endbenutzer ist berechtigt, die Daten der folgenden Organisationen zu sehen. Wenn keine Organisation zu sehen ist, gibt es keine Beschränkung.',
	'Class:User/Error:LoginMustBeUnique' => 'Login-Namen müssen unterschiedlich sein - "%1s" benutzt diesen Login-Name bereits.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Mindestens ein Profil muss diesem Benutzer zugewiesen sein.',
));

//
// Class: URP_Profiles
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_Profiles' => 'Profile',
	'Class:URP_Profiles+' => 'Benutzerprofile',
	'Class:URP_Profiles/Attribute:name' => 'Name',
	'Class:URP_Profiles/Attribute:name+' => 'Label',
	'Class:URP_Profiles/Attribute:description' => 'Beschreibung',
	'Class:URP_Profiles/Attribute:description+' => 'Kurze Beschreibung',
	'Class:URP_Profiles/Attribute:user_list' => 'Benutzer',
	'Class:URP_Profiles/Attribute:user_list+' => 'Personen, die diese Rolle haben',
));

//
// Class: URP_UserOrg
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_UserOrg' => 'Benutzerorganisationen',
	'Class:URP_UserOrg+' => 'Zulässige Organisationen',
	'Class:URP_UserProfile/Attribute:userid' => 'Benutzer',
	'Class:URP_UserProfile/Attribute:userid+' => 'Benutzerkonto',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Benutzer-Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Logindaten des Benutzers',
	'Class:URP_UserProfile/Attribute:allowed_org_id' => 'Organisation',
	'Class:URP_UserProfile/Attribute:allowed_org_id+' => 'Gestattete Organisation',
	'Class:URP_UserProfile/Attribute:allowed_org_name' => 'Organisation',
	'Class:URP_UserProfile/Attribute:allowed_org_name+' => 'Gestattete Organisation',
	'Class:URP_UserProfile/Attribute:reason' => 'Grund',
	'Class:URP_UserProfile/Attribute:reason+' => 'Erklären Sie, warum diese Person berechtigt ist, Zugriff auf die Daten der Organisation zu haben',
));


//
// Class: URP_Dimensions
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_Dimensions' => 'Dimension',
	'Class:URP_Dimensions+' => 'Anwendungsdimension (Festlegen von Silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Name',
	'Class:URP_Dimensions/Attribute:name+' => 'Label',
	'Class:URP_Dimensions/Attribute:description' => 'Beschreibung',
	'Class:URP_Dimensions/Attribute:description+' => 'Kurzbeschreibung',
	'Class:URP_Dimensions/Attribute:type' => 'Typ',
	'Class:URP_Dimensions/Attribute:type+' => 'Klassenname oder Datentyp (Abbildungseinheit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_UserProfile' => 'Benutzerprofil',
	'Class:URP_UserProfile+' => 'Benutzerprofil',
	'Class:URP_UserProfile/Attribute:userid' => 'Benutzer',
	'Class:URP_UserProfile/Attribute:userid+' => 'Benutzerkonto',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Benutzer-Login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profile',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Verwende Profil',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Profil-Name',
	'Class:URP_UserProfile/Attribute:reason' => 'Begründung',
	'Class:URP_UserProfile/Attribute:reason+' => 'Erklären Sie, warum diese Person diese Rolle haben soll',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_ProfileProjection' => 'Profilabbildung',
	'Class:URP_ProfileProjection+' => 'Profilabbildungen',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Anwendungsdimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Anwendungsdimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Nutzungsprofil',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profil-Name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Werteausdruck',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL-Ausdruck (Benutzung von $user) | konstant | | + Attribut-Code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Ziel des Attribut-Codes (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_ClassProjection' => 'Klassenabbildung',
	'Class:URP_ClassProjection+' => 'Klassenabbildungen',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'Anwendungsdimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'Anwendungsdimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Klasse',
	'Class:URP_ClassProjection/Attribute:class+' => 'Zielklasse',
	'Class:URP_ClassProjection/Attribute:value' => 'Wertausdruck',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL-Audsdruck (Benutzung von $this) | konstant | | + Attribut-Code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Ziel des Attribut-Codes (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_ActionGrant' => 'Autorisierungen von Aktionen',
	'Class:URP_ActionGrant+' => 'Autorisierungen auf Klassen',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Nutzungsprofil',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Nutzungsprofil',
	'Class:URP_ActionGrant/Attribute:class' => 'Klasse',
	'Class:URP_ActionGrant/Attribute:class+' => 'Zielklasse',
	'Class:URP_ActionGrant/Attribute:permission' => 'Autorisierung',
	'Class:URP_ActionGrant/Attribute:permission+' => 'Zugelassen oder untersagt?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nein',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'Nein',
	'Class:URP_ActionGrant/Attribute:action' => 'Aktion',
	'Class:URP_ActionGrant/Attribute:action+' => 'Operationen, die auf die gegebene Klasse ausgeführt werden sollen',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_StimulusGrant' => 'Autorisierung des Stimulus',
	'Class:URP_StimulusGrant+' => 'Autorisierungen auf den Stimulus des Lebenszyklus des Objektes',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Nutzungsprofil',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Nutzungsprofil',
	'Class:URP_StimulusGrant/Attribute:class' => 'Klasse',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Zielklasse',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Autorisierungen',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Zugelassen oder untersagt?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nein',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'Nein',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Stimulus-Code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_AttributeGrant' => 'Autorisierung des Attribute',
	'Class:URP_AttributeGrant+' => 'Autorisierungen auf Attributebene',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Aktion gewähren',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'Aktion gewähren',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribut',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Attribut-Code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:WelcomeMenu' => 'Willkommen',
	'Menu:WelcomeMenu+' => 'Willkommen bei iTop',
	'Menu:WelcomeMenuPage' => 'Willkommen',
	'Menu:WelcomeMenuPage+' => 'Willkommen bei iTop',
	'UI:WelcomeMenu:Title' => 'Willkommen bei iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop ist ein ein vollständiges, ITIL- und webbasiertes IT-Service-Management-Tool (ITSM)</p>
<ul>Es umfasst...
<li>eine vollständige CMDB (Configuration Management Database), um das IT-Portfolio zu dokumentieren und zu managen,</li>
<li>ein Incident Management-Modul, um alle Störfälle in der IT-Landschaft zu beobachten und diese zu kommunizieren,</li>
<li>ein Change Management-Modul, um Änderungen der IT-Landschaft zu planen und zu beobachten,</li>
<li>eine Datenbank mit bekannten Fehlern, um Zwischenfälle schneller anhand bekannter Problemlösungen zu beseitigen,</li>
<li>ein Ausfall-Modul, um geplante Ausfälle zu dokumentieren und die betreffenden Kontakte zu informieren,</li>
<li>unterschiedliche Dashboards, um sich einen schnellen Überblick über Ihre IT zu verschaffen.</li>
</ul>
<p>Alle Module können nacheinander und vollständig unabhängig voneinander eingerichtet werden.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop ist mandantenfähig, es erlaubt IT-Technikern, auf einfache Art eine Vielzahl an Kunden und Firmen zu verwalten.
<ul>iTop bietet ein umfangreiches Set an Business-Prozessen, die
<li>die Effizienz des IT-Managements steigern,</li> 
<li>die die Performance des IT-Betriebs steuern,</li> 
<li>die Kundenzufriedenheit verbessern und Führungskräften Einsicht in die Business Performance ermöglichen.</li>
</ul>
</p>
<p>iTop ist komplett offen, damit es sich bestmöglich in Ihre derzeitige IT-Management-Infrastruktur integriert.</p>
<p>
<ul>Die neue Generation des IT Operational Portals ermöglicht Ihnen
<li>ein besseres Management in einer immer komplexeren IT-Landschaft,</li>
<li>die ITIL-Prozesse gemäß dem Rhythmus Ihres Unternehmens einzuführen,</li>
<li>und ein besseres Verwalten des wichtigsten Bestandteiles Ihrer IT: der Dokumentation.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Offene Requests: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'An mich gestellte Benutzeranfragen',
	'UI:WelcomeMenu:OpenIncidents' => 'Offene Incidents: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuration Items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'An mich zugewiesene Incidents',
	'UI:AllOrganizations' => ' Alle Organisationen ',
	'UI:YourSearch' => 'Ihre Suche',
	'UI:LoggedAsMessage' => 'Angemeldet als %1$s',
	'UI:LoggedAsMessage+Admin' => 'Angemeldet als %1$s (Administrator)',
	'UI:Button:Logoff' => 'Abmelden',
	'UI:Button:GlobalSearch' => 'Suche',
	'UI:Button:Search' => ' Suche ',
	'UI:Button:Query' => ' Abfrage ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Cancel' => 'Abbrechen',
	'UI:Button:Apply' => 'Anwenden',
	'UI:Button:Back' => ' << Zurück ',
	'UI:Button:Next' => ' Weiter >> ',
	'UI:Button:Finish' => ' Abschließen ',
	'UI:Button:DoImport' => ' Führe den Import durch! ',
	'UI:Button:Done' => ' Fertig ',
	'UI:Button:SimulateImport' => ' Simuliere den Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Auswerten ',
	'UI:Button:AddObject' => ' Hinzufügen... ',
	'UI:Button:BrowseObjects' => ' Durchsuchen... ',
	'UI:Button:Add' => ' Hinzufügen ',
	'UI:Button:AddToList' => ' << Hinzufügen ',
	'UI:Button:RemoveFromList' => ' Entfernen >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Erstellen ',
	'UI:Button:Delete' => ' Löschen! ',
	'UI:Button:ChangePassword' => ' Passwort ändern ',
	'UI:Button:ResetPassword' => ' Passwort zurücksetzen ',

	'UI:SearchToggle' => 'Suche',
	'UI:ClickToCreateNew' => 'Klicken Sie hier, um eine neues Objekt vom Typ %1$s zu erstellen',
	'UI:SearchFor_Class' => 'Suche nach Objekten vom Typ "%1$s"',
	'UI:NoObjectToDisplay' => 'Kein Objekt zur Anzeige vorhanden.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter object_id ist erforderlich, wenn link_attr verwendet wird. Überprüfen Sie die Defintion des Display-Templates.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr ist erforderlich, wenn link_attr verwendet wird. Überprüfen Sie die Defintion des Display-Templates.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by ist erforderlich. Überprüfen Sie die Defintion des Display-Templates.',
	'UI:Error:InvalidGroupByFields' => 'Ungültige Felder-Liste, um diese zu gruppieren von: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Fehler: nicht unterstützter Blockform: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Ungültige Link-Defintion: die Klasse der zu managenden Objekte: %1$s wurde nicht als externer Schlüssel in der Klasse %2$s gefunden.',
	'UI:Error:Object_Class_Id_NotFound' => 'Objekt: %1$s:%2$d wurde nicht gefunden.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Fehler: gegenseitige Beziehung in den Abhängigkeiten zwischen den Feldern, überprüfen Sie das Datenmodell.',
	'UI:Error:UploadedFileTooBig' => 'Die hochgeladene Datei ist zu groß. (Maximal erlaubte Dateigröße ist %1$s. Überprüfen Sie upload_max_filesize und post_max_size in der PHP-Konfiguration.',
	'UI:Error:UploadedFileTruncated.' => 'Hochgeladene Datei wurde beschränkt!',
	'UI:Error:NoTmpDir' => 'Der temporäre Ordner ist nicht definiert.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Nicht möglich, die tempöräre Datei auf die Festplatte zu speicher: upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Der Upload wurde von der Erweiterung gestoppt. (urspünglicher Dateiname = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Dateiupload fehlgeschStandortn, unbekannte Ursache (Fehlercode = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Fehler: der folgende Parameter muss für diese Operation spezifiziert sein: %1$s.',
	'UI:Error:2ParametersMissing' => 'Fehler: die folgenden Parameter müssen für diese Operation spezifiziert sein: %1$s und %2$s.',
	'UI:Error:3ParametersMissing' => 'Fehler: die folgenden Parameter müssen für diese Operation spezifiziert sein: %1$s, %2$s und %3$s.',
	'UI:Error:4ParametersMissing' => 'Fehler: die folgenden Parameter müssen für diese Operation spezifiziert sein: %1$s, %2$s, %3$s und %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Fehler: ungültige OQL-Abfrage: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Ein Fehler trat während der Abfrage auf: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Fehler: das Objekt wurde bereits aktualisiert.',
	'UI:Error:ObjectCannotBeUpdated' => 'Fehler: das Objekt konnte nicht aktualisiert werden.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Fehler: die Objekte wurden bereits gelöscht!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Sie sind nicht berechtigt, mehrere Objekte der Klasse %1$s zu löschen',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Sie sind nicht berechtigt, Objekte der Klasse zu löschen %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Sie sind nicht berechtigt, diese Massenaktualisierung der Objekte der Klasse "%1$s" durchzuführen.',
	'UI:Error:ObjectAlreadyCloned' => 'Fehler: das Objekt wurde bereits dupliziert!',
	'UI:Error:ObjectAlreadyCreated' => 'Fehler: das Objekt wurde bereits erstellt!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Fehler: ungültiger Operation "%1$s" auf Objekt %2$s in Zustand "%3$s".',
	
	
	'UI:GroupBy:Count' => 'Anzahl',
	'UI:GroupBy:Count+' => 'Anzahl der Elemente',
	'UI:CountOfObjects' => '%1$d Objekte, die das Kriterium erfüllen.',
	'UI_CountOfObjectsShort' => '%1$d Objekte.',
	'UI:NoObject_Class_ToDisplay' => 'Kein Objekt vom Typ "%1$s" zur Anzeige vorhanden',
	'UI:History:LastModified_On_By' => 'Zuletzt verändert am %1$s von %2$s.',
	'UI:HistoryTab' => 'Verlauf',
	'UI:NotificationsTab' => 'Benachrichtigungen',
	'UI:History:Date' => 'Datum',
	'UI:History:Date+' => 'Datum der Änderung',
	'UI:History:User' => 'Benutzer',
	'UI:History:User+' => 'Benutzer, der die Änderung durchführte',
	'UI:History:Changes' => 'Änderungen',
	'UI:History:Changes+' => 'Änderungen, die am Objekt durchgeführt wurden',
	'UI:History:StatsCreations' => 'Created',
	'UI:History:StatsCreations+' => 'Count of objects created',
	'UI:History:StatsModifs' => 'Modified',
	'UI:History:StatsModifs+' => 'Count of objects modified',
	'UI:History:StatsDeletes' => 'Deleted',
	'UI:History:StatsDeletes+' => 'Count of objects deleted',
	'UI:Loading' => 'Laden...',
	'UI:Menu:Actions' => 'Aktionen', 
	'UI:Menu:OtherActions' => 'Andere Aktionen', 
	'UI:Menu:New' => 'Neu...',
	'UI:Menu:Add' => 'Hinzufügen...',
	'UI:Menu:Manage' => 'Verwalten...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV-Export',
	'UI:Menu:Modify' => 'Modifizieren...',
	'UI:Menu:Delete' => 'Löschen...',
	'UI:Menu:Manage' => 'Verwalten...',
	'UI:Menu:BulkDelete' => 'Löschen...',
	'UI:UndefinedObject' => 'nicht definiert',
	'UI:Document:OpenInNewWindow:Download' => 'In neuem Fenster öffnen: %1$s, Download: %2$s',
	'UI:SelectAllToggle+' => 'Alle auswählen/deselektieren',
	'UI:TruncatedResults' => '%1$d angezeigte Objekte von %2$d',
	'UI:DisplayAll' => 'Alle anzeigen',
	'UI:CollapseList' => 'Ausklappen',
	'UI:CountOfResults' => '%1$d Objekt(e)',
	'UI:ChangesLogTitle' => 'Änderungsprotokoll (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Änderungsprotokoll ist leer',
	'UI:SearchFor_Class_Objects' => 'Suche nach Objekten vom Typ "%1$s"',
	'UI:OQLQueryBuilderTitle' => 'OQL-Abfragen-Ersteller',
	'UI:OQLQueryTab' => 'OQL-Abfrage',
	'UI:SimpleSearchTab' => 'Einfache Suche',
	'UI:Details+' => 'Details',
	'UI:SearchValue:Any' => '*beliebig*',
	'UI:SearchValue:Mixed' => '*gemischt*',
	'UI:SelectOne' => 'bitte wählen',
	'UI:Login:Welcome' => 'Willkommen bei iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Ungültiges Passwort oder Login-Daten. Bitte versuchen Sie es erneut.',
	'UI:Login:IdentifyYourself' => 'Bitte identifizieren Sie sich, bevor Sie fortfahren.',
	'UI:Login:UserNamePrompt' => 'Benutzername',
	'UI:Login:PasswordPrompt' => 'Passwort',
	'UI:Login:ChangeYourPassword' => 'Ändern Sie Ihr Passwort',
	'UI:Login:OldPasswordPrompt' => 'Altes Passwort',
	'UI:Login:NewPasswordPrompt' => 'Neues Passwort',
	'UI:Login:RetypeNewPasswordPrompt' => 'Wiederholen Sie Ihr neues Passwort',
	'UI:Login:IncorrectOldPassword' => 'Fehler: das alte Passwort ist ungültig',
	'UI:LogOffMenu' => 'Abmelden',
	'UI:LogOff:ThankYou' => 'Vielen Dank dafür, dass Sie iTop benutzen!',
	'UI:LogOff:ClickHereToLoginAgain' => 'Klicken Sie hier, um sich wieder anzumelden...',
	'UI:ChangePwdMenu' => 'Passwort ändern...',
	'UI:AccessRO-All' => 'iTop ist nur lesbar',
	'UI:AccessRO-Users' => 'iTop ist nur lesbar für Endnutzer',
	'UI:Login:RetypePwdDoesNotMatch' => 'Neues Passwort und das wiederholte Passwort stimmen nicht überein!',
	'UI:Button:Login' => 'in iTop anmelden',
	'UI:Login:Error:AccessRestricted' => 'Der iTop-Zugang ist gesperrt. Bitte kontaktieren Sie einen iTop-Administrator.',
	'UI:Login:Error:AccessAdmin' => 'Zugang nur für Personen mit Administratorrechten. Bitte kontaktieren Sie Ihren iTop-Administrator.',
	'UI:CSVImport:MappingSelectOne' => 'Bitte wählen',
	'UI:CSVImport:MappingNotApplicable' => '-- Dieses Feld ignorieren --',
	'UI:CSVImport:NoData' => 'Keine Daten eingegeben ... bitte geben Sie Daten ein!',
	'UI:Title:DataPreview' => 'Datenvorschau',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Fehler: die Daten behinhalten nur eine Spalte. Haben Sie das dazugehörige Trennzeichen ausgewählt?',
	'UI:CSVImport:FieldName' => 'Feld %1$d',
	'UI:CSVImport:DataLine1' => 'Daten-Zeile 1',
	'UI:CSVImport:DataLine2' => 'Daten-Zeile 2',
	'UI:CSVImport:idField' => 'ID (Primärer Schlüssel)',
	'UI:Title:BulkImport' => 'iTop - Massenimport',
	'UI:Title:BulkImport+' => 'CSV-Import-Assistent',
	'UI:CSVImport:ClassesSelectOne' => 'Bitte wählen',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Interner Fehler: "%1$s" ist ungültiger Code. Begründung "%2$s" ist NICHT ein externer Schlüssel der Klasse "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d Objekte bleiben unverändert.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d Objekte werden verändert.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d Objekte werden hinzugefügt.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d Objekte werden fehlerhaft sein.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d Objekte blieben unverändert.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d Objekte wurden verändert.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d Objekte wurden hinzugefügt.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d Objekte hatten Fehler.',
	'UI:Title:CSVImportStep2' => 'Schritt 2 von 5: CSV-Daten: Optionen',
	'UI:Title:CSVImportStep3' => 'Schritt 3 von 5: Daten-Mapping',
	'UI:Title:CSVImportStep4' => 'Schritt 4 von 5: Import-Simulation',
	'UI:Title:CSVImportStep5' => 'Schritt 5 von 5: Import abgeschlossen',
	'UI:CSVImport:LinesNotImported' => 'Zeilen, die nicht geladen werden konnten:',
	'UI:CSVImport:LinesNotImported+' => 'Die folgenden Zeilen wurden nicht importiert, weil sie Fehler enthalten',
	'UI:CSVImport:SeparatorComma+' => ', (Komma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (Semikolon)',
	'UI:CSVImport:SeparatorTab+' => 'Tabulator',
	'UI:CSVImport:SeparatorOther' => 'Andere:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (Anführungszeichen)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (Auslassungszeichen)',
	'UI:CSVImport:QualifierOther' => 'Andere:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Behandle die erste Zeile als Kopf (Spaltennamen)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Überspringe %1$s Zeile(n) am Anfang der Datei',
	'UI:CSVImport:CSVDataPreview' => 'Vorschau der CSV-Daten',
	'UI:CSVImport:SelectFile' => 'Bitte wählen Sie die zu importierende Datei aus:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Aus Datei laden',
	'UI:CSVImport:Tab:CopyPaste' => 'Kopieren und Einfügen von Daten',
	'UI:CSVImport:Tab:Templates' => 'Templates',
	'UI:CSVImport:PasteData' => 'Fügen Sie die zu importierenden Daten ein:',
	'UI:CSVImport:PickClassForTemplate' => 'Wählen Sie ein Template zum Download aus: ',
	'UI:CSVImport:SeparatorCharacter' => 'Trennzeichen:',
	'UI:CSVImport:TextQualifierCharacter' => 'Textkennzeichnungsbuchstabe',
	'UI:CSVImport:CommentsAndHeader' => 'Kommentare und Kopfzeile',
	'UI:CSVImport:SelectClass' => 'Wählen Sie die Klasse zum Import:',
	'UI:CSVImport:AdvancedMode' => 'Fortgeschrittener Modus',
	'UI:CSVImport:AdvancedMode+' => 'Im fortgeschrittenen Modus kann die "ID" (primärer Schlüssel) der Objekte benutzt werden, um Ojekte zu aktualisieren oder umzubenennen.' .
	'Allerdings kann die Spalte "ID" (sofern vorhanden) nur als Suchkriterium verwendet werden und nicht mit anderen Suchkriterien kombiniert werden.',
	'UI:CSVImport:SelectAClassFirst' => 'Wählen Sie bitte zuerst eine Klasse aus, bevor Sie das Mapping erstellen',
	'UI:CSVImport:HeaderFields' => 'Felder',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Suchen?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Bitte wählen Sie ein Mapping für jedes Feld aus.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Bitte wählen Sie mindestens ein Suchkriterium aus.',
	'UI:CSVImport:Encoding' => 'Buchstaben-Codierung',	

	'UI:UniversalSearchTitle' => 'iTop - universelle Suche',
	'UI:UniversalSearch:Error' => 'Fehler: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Wählen Sie für die Suche die Klasse aus: ',
	
	'UI:Audit:Title' => 'iTop - CMDB-Audit',
	'UI:Audit:InteractiveAudit' => 'Interaktives Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit-Regel',
	'UI:Audit:HeaderNbObjects' => '# Objekte',
	'UI:Audit:HeaderNbErrors' => '# Fehler',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - OQL-Abfrage-Auswertung',
	'UI:RunQuery:QueryExamples' => 'Abfragebeispiele',
	'UI:RunQuery:HeaderPurpose' => 'Verwendungszweck',
	'UI:RunQuery:HeaderPurpose+' => 'Beschreibung der Abfrage',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL-Ausdruck',
	'UI:RunQuery:HeaderOQLExpression+' => 'Die Abfrage in OQL-Syntax',
	'UI:RunQuery:ExpressionToEvaluate' => 'Auszuwertender Ausdruck: ',
	'UI:RunQuery:MoreInfo' => 'Mehr Informationen zur Abfrage: ',
	'UI:RunQuery:DevelopedQuery' => 'Überarbeiteter Abfrageausdruck: ',
	'UI:RunQuery:SerializedFilter' => 'Serieller Filter: ',
	'UI:RunQuery:Error' => 'Ein Fehler trat während der Abfrage auf: %1$s auf.',
	
	'UI:Schema:Title' => 'iTop Objekte-Schema',
	'UI:Schema:CategoryMenuItem' => 'Kategorie <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Wechselseite Beziehungen',
	'UI:Schema:AbstractClass' => 'Abstrakte Klasse: ein Objekt dieser Klasse kann nicht instanziiert werden.',
	'UI:Schema:NonAbstractClass' => 'Keine abstrakte Klasse: Objekte dieser Klasse können instanziiert werden.',
	'UI:Schema:ClassHierarchyTitle' => 'Klassenhierarchie',
	'UI:Schema:AllClasses' => 'Alle Klassen',
	'UI:Schema:ExternalKey_To' => 'Externer Schlüssel zu %1$s',
	'UI:Schema:Columns_Description' => 'Spalten: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Standard: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null zugelassen',
	'UI:Schema:NullNotAllowed' => 'Null NICHT zugelassen',
	'UI:Schema:Attributes' => 'Attribute',
	'UI:Schema:AttributeCode' => 'Attribut-Code',
	'UI:Schema:AttributeCode+' => 'Interner Code des Attributes',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label des Attributes',
	'UI:Schema:Type' => 'Typ',
	
	'UI:Schema:Type+' => 'Datentyp des Attributes',
	'UI:Schema:Origin' => 'Ursprung',
	'UI:Schema:Origin+' => 'Die Basisklasse, in welcher dieses Attribut definiert ist.',
	'UI:Schema:Description' => 'Beschreibung',
	'UI:Schema:Description+' => 'Beschreibung des Attributes',
	'UI:Schema:AllowedValues' => 'Zugelassene Werte',
	'UI:Schema:AllowedValues+' => 'Einschränkungen an zugelassenen Werten dieses Attributes',
	'UI:Schema:MoreInfo' => 'Mehr Informationen',
	'UI:Schema:MoreInfo+' => 'Mehr Informationen zu dem Feld aus der Datenbank',
	'UI:Schema:SearchCriteria' => 'Suchkriterium',
	'UI:Schema:FilterCode' => 'Code filtern',
	'UI:Schema:FilterCode+' => 'Code dieses Suchkriterium',
	'UI:Schema:FilterDescription' => 'Beschreibung',
	'UI:Schema:FilterDescription+' => 'Beschreibung dieses Suchkriterium',
	'UI:Schema:AvailOperators' => 'Verfügbare Operatoren',
	'UI:Schema:AvailOperators+' => 'Mögliche Operatoren für dieses Suchkriterium',
	'UI:Schema:ChildClasses' => 'Kind-Klassen',
	'UI:Schema:ReferencingClasses' => 'Referenzierende Klassen',
	'UI:Schema:RelatedClasses' => 'Zugehörige Klassen',
	'UI:Schema:LifeCycle' => 'Lebenszyklus',
	'UI:Schema:Triggers' => 'Trigger',
	'UI:Schema:Relation_Code_Description' => 'Beziehung <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Unten: %1$s',
	'UI:Schema:RelationUp_Description' => 'Oben: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: verbreitet sich zu %2$d Ebenen, Abfrage: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: verbreitet sich nicht (%2$d Ebenen), Abfrage: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s wird von Klasse %2$s referenziert über das Feld %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s ist mit %2$s verbunden über %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Klassen verweisen zu %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Klassen verbunden zu %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Grafik aller zugehörigen Klassen',
	'UI:Schema:NoLifeCyle' => 'Für diese Klasse ist kein Lebenszyklus definiert.',
	'UI:Schema:LifeCycleTransitions' => 'Übergänge',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribut-Optionen',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Versteckt',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Nur lesen',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Erforderlich',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Muss ändern',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Benutzer wird zur Änderung des Wertes aufgefordert werden',
	'UI:Schema:LifeCycleEmptyList' => 'Leere Liste',
	
	'UI:LinksWidget:Autocomplete+' => 'Geben Sie die ersten 3 Buchstaben ein...',
	'UI:Combo:SelectValue' => '--- wählen Sie einen Wert ---',
	'UI:Label:SelectedObjects' => 'Ausgewählte Objekte: ',
	'UI:Label:AvailableObjects' => 'Verfügbare Objekte: ',
	'UI:Link_Class_Attributes' => '%1$s kennzeichnet',
	'UI:SelectAllToggle+' => 'Alle auswählen/deselektieren',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Fügen Sie %1$s Objekte verbunden mit %2$s hinzu: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Fügen Sie %1$s Objekte verbunden mit %2$s hinzu',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Verwalten Sie %1$s Objekte verbunden mit %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => ' %1$s hinzufügen...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Entferne ausgewählte Objekte',
	'UI:Message:EmptyList:UseAdd' => 'Die Liste ist leer, benutzten Sie "Hinzufügen..." um Elemente hinzuzufügen.',
	'UI:Message:EmptyList:UseSearchForm' => 'Benutzen Sie das Suchformular oben, um nach hinzufügbaren Objekten zu suchen.',
	
	'UI:Wizard:FinalStepTitle' => 'Letzter Schritt: Bestätigung',
	'UI:Title:DeletionOf_Object' => 'Löschung von %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Massenlöschung von %1$d Objekten der %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Sie sind nicht berechtigt, dieses Objekt zu löschen.',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Sie sind nicht berechtigt, die folgenden Felder zu aktualisieren: %1$s',
	'UI:Error:CannotDeleteBecause' => 'Dieses Objekt konnte aus folgendem Grunund nicht gelöscht werden: %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Dieses Objekt konnte nicht gelöscht werden, da der derzeitige Benutzer nicht die notwendigen Rechte dazu besitzt.',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Dieses Objekt konnte nicht gelöscht werden, da zuerst dazu einige manuelle Operationen durchgeführt werden müssen.',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s im Auftrag von %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'Automatisch gelöscht',
	'UI:Delete:AutomaticResetOf_Fields' => 'Automatischer Reset der Felder: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Aufräumen aller Referenzen zu %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Aufräumen aller Referenzen zu %1$d Objekten der Klasse %2$s...',
	'UI:Delete:Done+' => 'Was getan wurde...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s gelöscht.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Löschung von %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Löschung von %1$d Objekten der Klasse %2$s',
//	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Sollte automatisch gelöscht werden, aber Sie sind nicht berechtigt, dies zu tun',
//	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Muss automatisch gelöscht werden, aber Sie sind nicht berechtigt, dieses Objekt zu löschen. Bitte kontaktieren Sie Ihren Anwendungs-Administrator',
	'UI:Delete:WillBeDeletedAutomatically' => 'Wird automatisch gelöscht',
	'UI:Delete:MustBeDeletedManually' => 'Muss manuell gelöscht werden',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Sollte automatisch aktualisiert werden, aber: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Wird automatisch aktualisiert (Reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d Objekte/Links referenzieren %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d Objekte/Links referenzieren einige der zu löschenden Objekte',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Um Datenbankintegrität sicherzustellen sollten alle weiteren Referenzen entfernt werden.',
	'UI:Delete:Consequence+' => 'Was getan wird',
	'UI:Delete:SorryDeletionNotAllowed' => 'Leider ist Ihnen nicht gestattet, dieses Objekt zu löschen. Eine ausführliche Erklärung dazu finden Sie oben',
	'UI:Delete:PleaseDoTheManualOperations' => 'Bitte führen Sie die oben aufgelisteten manuellen Operationen zuerst durch, bevor Sie dieses Objekt löschen.',
	'UI:Delect:Confirm_Object' => 'Bitte bestätigen Sie, dass Sie %1$s löschen möchten.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Bitte bestätigen Sie, dasss Sie die folgenden %1$d Objekte der Klasse %2$s löschen möchten.',
	'UI:WelcomeToITop' => 'Willkommen bei iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s Details',
	'UI:ErrorPageTitle' => 'iTop - Fehler',
	'UI:ObjectDoesNotExist' => 'Leider existiert dieses Objekt nicht oder Sie sind nicht berechtigt es einzusehen.',
	'UI:SearchResultsPageTitle' => 'iTop - Suchergebnisse',
	'UI:Search:NoSearch' => 'Kein Suchbegriff eingegeben',
	'UI:FullTextSearchTitle_Text' => 'Ergebnisse für "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d Objekt(e) der Klasse %2$s gefunden.',
	'UI:Search:NoObjectFound' => 'Kein Objekt gefunden',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s Änderungen',
	'UI:ModificationTitle_Class_Object' => 'Änderungen von %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Dupliziere %1$s - %2$s Änderung',
	'UI:CloneTitle_Class_Object' => 'Duplizieren von %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Erstellung eines neuen Objekts vom Typ "%1$s" ',
	'UI:CreationTitle_Class' => 'Erstellung eines neuen Objekts vom Typ "%1$s"',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Wählen Sie den Typ vom Objekt "%1$s" aus, den Sie erstellen möchten:',
	'UI:Class_Object_NotUpdated' => 'Keine Änderung festgestellt, %1$s (%2$s) wurde <strong>nicht</strong> modifiziert.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) aktualisiert.',
	'UI:BulkDeletePageTitle' => 'iTop - Massenlöschung von Objekten',
	'UI:BulkDeleteTitle' => 'Wählen Sie die Objekte aus, die Sie löschen möchten:',
	'UI:PageTitle:ObjectCreated' => 'iTop-Objekt wurde erstellt.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s erstellt.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Anwenden von %1$s auf Objekt: %2$s in Status %3$s zu Zielstatus: %4$s.',
	'UI:PageTitle:FatalError' => 'iTop - Fataler Fehler',
	'UI:FatalErrorMessage' => 'Fataler Fehler! iTop kann leider nicht fortfahren.',
	'UI:Error_Details' => 'Fehler: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop Benutzerverwaltung - Klassenabbildung',
	'UI:PageTitle:ProfileProjections' => 'iTop Benutzerverwaltung - Profilabbildung',
	'UI:UserManagement:Class' => 'Klasse',
	'UI:UserManagement:Class+' => 'Klasse von Objekten',
	'UI:UserManagement:ProjectedObject' => 'Objekt',
	'UI:UserManagement:ProjectedObject+' => 'Geschütztes Objekt',
	'UI:UserManagement:AnyObject' => '* beliebig *',
	'UI:UserManagement:User' => 'Benutzer',
	'UI:UserManagement:User+' => 'Benutzer, der in Abbildung beteiligt ist.',
	'UI:UserManagement:Profile' => 'Profil',
	'UI:UserManagement:Profile+' => 'Profil, in welchem die Abbildung spezifiziert wird.',
	'UI:UserManagement:Action:Read' => 'Lesen',
	'UI:UserManagement:Action:Read+' => 'Lesen/Anzeigen von Objekten',
	'UI:UserManagement:Action:Modify' => 'Verändern',
	'UI:UserManagement:Action:Modify+' => 'Erstellen und editieren (modifizieren) von Objekten',
	'UI:UserManagement:Action:Delete' => 'Löschen',
	'UI:UserManagement:Action:Delete+' => 'Objekte löschen',
	'UI:UserManagement:Action:BulkRead' => 'Masseneinlesen (Export)',
	'UI:UserManagement:Action:BulkRead+' => 'Objekte massenhaft auflisten oder exportieren',
	'UI:UserManagement:Action:BulkModify' => 'Massenmodifikation',
	'UI:UserManagement:Action:BulkModify+' => 'Massenerstellung/-bearbeitung (CSV-Import)',
	'UI:UserManagement:Action:BulkDelete' => 'Massenlöschung',
	'UI:UserManagement:Action:BulkDelete+' => 'Massenhaft Objekte löschen',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Zugelassene (verbundene) Aktionen',
	'UI:UserManagement:Action' => 'Aktion',
	'UI:UserManagement:Action+' => 'Von Benutzer durchgeführte Aktion',
	'UI:UserManagement:TitleActions' => 'Aktionen',
	'UI:UserManagement:Permission' => 'Befugnisse',
	'UI:UserManagement:Permission+' => 'Benutzerbefugnisse',
	'UI:UserManagement:Attributes' => 'Attribute',
	'UI:UserManagement:ActionAllowed:Yes' => 'Ja',
	'UI:UserManagement:ActionAllowed:No' => 'Nein',
	'UI:UserManagement:AdminProfile+' => 'Administratoren haben vollständigen Lese/-Schreibzugriff auf alle Objekte in der Datenbank.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'Nicht verfügbar',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Kein Lebenszyklus wurde für diese Klasse definiert.',
	'UI:UserManagement:GrantMatrix' => 'Zugriffsmatrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Verbindung zwischen %1$s und %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Verbindung zwischen %1$s und %2$s',
	'Menu:AdminTools' => 'Admin-Tools',
	'Menu:AdminTools+' => 'Administrationswerkzeuge',
	'Menu:AdminTools?' => 'Werkzeuge, die nur für Benutzer mit Adminstratorprofil zugänglich sind',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Übersicht an Changes',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes nach Typ',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes nach Status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes nach Arbeitsgruppen',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changes, die noch nicht zugeordnet wurden',

	'UI:ConfigurationItemsMenu'=> 'Configuration Items',
	'UI:ConfigurationItemsMenu+'=> 'Alle Geräte',
	'UI:ConfigurationItemsMenu:Title' => 'Übersicht der Configuration Items',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'Server nach Business criticity',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'Rechner (PC) nach Business criticity',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'Netzwerkgeräte nach Business criticity',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'Anwendungen nach Business criticity',
	
	'UI:ConfigurationManagementMenu' => 'Configuration Management',
	'UI:ConfigurationManagementMenu+' => 'Configuration Management',
	'UI:ConfigurationManagementMenu:Title' => 'Übersicht der Infrastruktur',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastrukturbestandteile nach Typ',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastrukturbestandteile nach Status',

'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard für das Configuration Management',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuration Items nach Status',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items nach Typ',

'UI:RequestMgmtMenuOverview:Title' => 'Dashboard für das Request Management',
'UI-RequestManagementOverview-RequestByService' => 'Benutzeranfragen nach Service gegliedert',
'UI-RequestManagementOverview-RequestByPriority' => 'Benutzeranfragen nach Priorität gegliedert',
'UI-RequestManagementOverview-RequestUnassigned' => 'Benutzeranfragen, die noch nicht an einen Bearbeiter zugeteilt wurden',

'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard für Incident Management',
'UI-IncidentManagementOverview-IncidentByService' => 'Incidents nach Service',
'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidents nach Priorität',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidents, die noch nicht an einen Bearbeiter zugeteilt wurden',

'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard für das Change Management',
'UI-ChangeManagementOverview-ChangeByType' => 'Changes nach Typ',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'Changes, die noch nicht an einen Bearbeiter zugeteilt wurden',
'UI-ChangeManagementOverview-ChangeWithOutage' => 'Ausfälle bedingt durch Changes',

'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard für das Service Management',
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Kundenverträge, die in weniger als 30 Tagen erneuert werden müssen',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Provider-Verträge, die in weniger als 30 Tagen erneuert werden müssen',

	'UI:ContactsMenu' => 'Kontakte',
	'UI:ContactsMenu+' => 'Kontakte',
	'UI:ContactsMenu:Title' => 'Kontaktübersicht',
	'UI-ContactsMenu-ContactsByLocation' => 'Kontakte nach Standort',
	'UI-ContactsMenu-ContactsByType' => 'Kontakte nach Typ',
	'UI-ContactsMenu-ContactsByStatus' => 'Kontakte nach Status',

	'Menu:CSVImportMenu' => 'CSV-Import',
	'Menu:CSVImportMenu+' => 'Massenerstellung oder -aktualisierung',
	
	'Menu:DataModelMenu' => 'Datenmodell',
	'Menu:DataModelMenu+' => 'Übersicht des Datenmodells',
	
	'Menu:ExportMenu' => 'Export',
	'Menu:ExportMenu+' => 'Export einer beliebigen Abfrage in HTML, CSV oder XML',
	
	'Menu:NotificationsMenu' => 'Benachrichtigungen',
	'Menu:NotificationsMenu+' => 'Einstellungen der Benachrichtigungen',
	'UI:NotificationsMenu:Title' => 'Einstellungen der <span class="hilite">Benachrichtigungen</span>',
	'UI:NotificationsMenu:Help' => 'Hilfe',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop sind Benachrichtigungen vollständig anpassbar. Sie basieren auf zwei Gruppen an Objekten: <i>Trigger und Aktionen</i>.</p>
<p><i><b>Trigger</b></i> legen fest, wann eine Benachrichtigung erfolgen soll. Es gibt drei Typen von Trigger um drei verscheidene Phasen eines Objekt-Lebenszyklus abzubilden:
<ol>
	<li>Der "OnCreate" Trigger wird ausgeführt, wenn ein Objekt der spezifizierten Klasse erstellt wird.</li>
	<li>Der "OnStateEnter" Trigger wird ausgeführt, bevor ein Objekt einer gegebenen Klasse einen spezifizierten Status erlangt (aus einem anderen Status kommend)</li>
	<li>Der "OnStateLeave" Trigger wird ausgeführt, sobald ein Objekt einer gegebenen Klasse einen spezifizierten Status verlässt</li>
</ol>
</p>
<p>
<i><b>Aktionen</b></i> definieren Aktionen, die ausgeführt werden sollen, wenn ein Trigger ausgeführt wird. Derzeit steht nur eine Art an Aktion zur Verfügung: Das Senden einer Email-Nachricht.
Derartige Aktionen definieren auch das Template, welches für das Versenden der Email, aber auch für anderen Parameter der Nachricht wie Empfänger, Priorität usw. zuständig ist.</p>
<p>Eine spezielle Seite: <a href="../setup/email.test.php" target="_blank">email.test.php</a> steht zum Testen und zur Fehlerbehebung Ihrer PHP-Mailkonfiguration bereit.</p>
<p>Um Aktionen auszuführen, müssen diese mit Trigger verknüpft sein.
Wenn Aktionen mit Trigger verknüpft sind, bekommt jede Aktion eine Auftragsnummer, die die Reihenfolge der auszuführenden Aktionen festlegt.</p>',
	'UI:NotificationsMenu:Triggers' => 'Trigger',
	'UI:NotificationsMenu:AvailableTriggers' => 'Verfügbare Trigger',
	'UI:NotificationsMenu:OnCreate' => 'Wenn ein Objekt erstellt wird',
	'UI:NotificationsMenu:OnStateEnter' => 'Wenn ein Objekt einen gegebenen Status erlangt',
	'UI:NotificationsMenu:OnStateLeave' => 'Wenn ein Objekt einen gegebenen Status verlässt',
	'UI:NotificationsMenu:Actions' => 'Aktionen',
	'UI:NotificationsMenu:AvailableActions' => 'Verfügbare Aktionen',
	
	'Menu:AuditCategories' => 'Audit-Kategorien',
	'Menu:AuditCategories+' => 'Audit-Kategorien',
	'Menu:Notifications:Title' => 'Audit-Kategorien',
		
	'Menu:RunQueriesMenu' => 'Abfrage ausführen',
	'Menu:RunQueriesMenu+' => 'Eine beliebige Abfrage ausführen',
	
	'Menu:DataAdministration' => 'Data Management',
	'Menu:DataAdministration+' => 'Data Management',
	
	'Menu:UniversalSearchMenu' => 'Universelle Suche',
	'Menu:UniversalSearchMenu+' => 'Suchen Sie nach beliebigen Inhalt...',
	
	'Menu:ApplicationLogMenu' => 'Protokoll der Anwendung',
	'Menu:ApplicationLogMenu+' => 'Protokoll der Anwendung',
	'Menu:ApplicationLogMenu:Title' => 'Protokoll der Anwendung',

	'Menu:UserManagementMenu' => 'User-Management',
	'Menu:UserManagementMenu+' => 'User-Management',

	'Menu:ProfilesMenu' => 'Profile',
	'Menu:ProfilesMenu+' => 'Profile',
	'Menu:ProfilesMenu:Title' => 'Profile',

	'Menu:UserAccountsMenu' => 'Benutzerkonten',
	'Menu:UserAccountsMenu+' => 'Benutzerkonten',
	'Menu:UserAccountsMenu:Title' => 'Benutzerkonten',	

	'UI:iTopVersion:Short' => 'iTop Version %1$s',
	'UI:iTopVersion:Long' => 'iTop Version %1$s-%2$s compiliert am %3$s',
	'UI:PropertiesTab' => 'Eigenschaften',

	'UI:OpenDocumentInNewWindow_' => 'Dieses Dokument in einem neuen Fenster öffnen: %1$s',
	'UI:DownloadDocument_' => 'Dieses Dokument herunterladen: %1$s',
	'UI:Document:NoPreview' => 'Für diesen Typ Dokument ist keine Vorschau vorhanden',

	'UI:DeadlineMissedBy_duration' => 'Verpasst um %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 Minute',		
	'UI:Deadline_Minutes' => '%1$d Minuten',			
	'UI:Deadline_Hours_Minutes' => '%1$dStunden %2$dMinuten',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dTage %2$dStunden %3$dMinuten',
	'UI:Help' => 'Hilfe',
	'UI:PasswordConfirm' => '(Bestätigen)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Bevor weitere Objekte vom Typ "%1$s" hinzugefügt werden können, speichern Sie bitte dieses Objekt.',
	'UI:DisplayThisMessageAtStartup' => 'Diese Meldung beim Start immer anzeigen',
	'UI:RelationshipGraph' => 'Grafische Ansicht',
	'UI:RelationshipList' => 'Liste',

	'Portal:Title' => 'iTop-Benutzerportal',
	'Portal:Refresh' => 'Neu laden',
	'Portal:Back' => 'Zurück',
	'Portal:CreateNewRequest' => 'Einen neuen Request erstellen',
	'Portal:ChangeMyPassword' => 'Mein Passwort ändern',
	'Portal:Disconnect' => 'Disconnect',
	'Portal:OpenRequests' => 'Meine offenen Requests',
	'Portal:ResolvedRequests'  => 'Meine gelösten Requests',
	'Portal:SelectService' => 'Wählen Sie einen Service aus dem Katalog:',
	'Portal:PleaseSelectOneService' => 'Bitte wählen Sie einen Service',
	'Portal:SelectSubcategoryFrom_Service' => 'Wählen Sie einen Unterkategorie für diesen Service vom Typ "%1$s":',
	'Portal:PleaseSelectAServiceSubCategory' => 'Bitte wählen Sie eine Unterkategorie',
	'Portal:DescriptionOfTheRequest' => 'Geben Sie die Beschreibung Ihres Requests ein:',
	'Portal:TitleRequestDetailsFor_Request' => 'Details für Request %1$s:',
	'Portal:NoOpenRequest' => 'Keinen Request in dieser Kategorie',
	'Portal:Button:CloseTicket' => 'Dieses Ticket schließen',
	'Portal:EnterYourCommentsOnTicket' => 'Geben Sie einen Kommentar zur Lösung dieses Tickets ein:',
	'Portal:ErrorNoContactForThisUser' => 'Fehler: der derzeitige Benutzer wurde nicht einem Kontakt oder einer Person zugewiesen. Bitte kontaktieren Sie Ihren Administrator.',

	'Enum:Undefined' => 'Nicht definiert',
));



?>

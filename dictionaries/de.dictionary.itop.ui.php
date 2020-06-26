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
 * @author	Stephan Rosenke <stephan.rosenke@itomig.de>
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:AuditCategory' => 'Audit-Kategorie',
	'Class:AuditCategory+' => 'Definition einer Objektgruppe, die durch Regeln überprüft werden soll.',
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
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag Klasse',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Objektklasse',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Feld-Code',
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

//
// Class: QueryOQL
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Query' => 'Query',
	'Class:Query+' => '',
	'Class:Query/Attribute:name' => 'Name',
	'Class:Query/Attribute:name+' => '',
	'Class:Query/Attribute:description' => 'Beschreibung',
	'Class:Query/Attribute:description+' => '',
	'Class:QueryOQL/Attribute:fields' => 'Felder',
	'Class:QueryOQL/Attribute:fields+' => '',
	'Class:QueryOQL' => 'OQL Abfrage',
	'Class:QueryOQL+' => '',
	'Class:QueryOQL/Attribute:oql' => 'Ausdruck',
	'Class:QueryOQL/Attribute:oql+' => '',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:User' => 'Benutzer',
	'Class:User+' => 'Benutzer-Login',
	'Class:User/Attribute:finalclass' => 'Typ des Benutzerkontos',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Kontakt (Person)',
	'Class:User/Attribute:contactid+' => 'Persönliche Details der Geschäftsdaten',
	'Class:User/Attribute:org_id' => 'Organisation',
	'Class:User/Attribute:org_id+' => 'Organisation der verknüpften Person',
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
	'Class:User/Attribute:allowed_org_list' => 'Erlaubte Organisationen',
	'Class:User/Attribute:allowed_org_list+' => 'Der Endbenutzer ist berechtigt, die Daten der folgenden Organisationen zu sehen. Wenn keine Organisation zu sehen ist, gibt es keine Beschränkung.',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'Ist das Benutzer aktiviert oder deaktiviert ?',
	'Class:User/Attribute:status/Value:enabled' => 'Aktiv',
	'Class:User/Attribute:status/Value:disabled' => 'Inaktiv',

	'Class:User/Error:LoginMustBeUnique' => 'Login-Namen müssen unterschiedlich sein - "%1s" benutzt diesen Login-Name bereits.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Mindestens ein Profil muss diesem Benutzer zugewiesen sein.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Mindestens eine Organisation muss diesem Benutzer zugewiesen sein.',
	'Class:User/Error:OrganizationNotAllowed' => 'Diese Organisation ist nicht erlaubt.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'Das Benutzerkonto gehört nicht zu den für Sie freigegebenen Organisationen',
	'Class:User/Error:PersonIsMandatory' => 'Der Kontakt muss angegeben werden.',
	'Class:UserInternal' => 'Interner Benutzer',
	'Class:UserInternal+' => 'Benutzer, der innerhalb iTop definiert wird',
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
// Class: URP_UserOrg
//


Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:URP_UserOrg' => 'Benutzerorganisationen',
	'Class:URP_UserOrg+' => 'Zulässige Organisationen',
	'Class:URP_UserOrg/Attribute:userid' => 'User',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
	'Class:URP_UserOrg/Attribute:reason' => 'Begründung',
	'Class:URP_UserOrg/Attribute:reason+' => '',
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
// Class: UserDashboard
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:UserDashboard' => 'Benutzer Dashboard',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Benutzer',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Menü-Code',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Inhalt',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Expression:Unit:Short:DAY' => 't',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'j',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'BooleanLabel:yes' => 'Ja',
	'BooleanLabel:no' => 'Nein',
	'UI:Login:Title' => 'iTop Login',
	'Menu:WelcomeMenu' => 'Willkommen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Willkommen bei iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Willkommen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Willkommen bei iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
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
	'UI:Button:Save' => 'Speichern',
	'UI:Button:Cancel' => 'Abbrechen',
	'UI:Button:Close' => 'Schließen',
	'UI:Button:Apply' => 'Anwenden',
	'UI:Button:Back' => ' << Zurück ',
	'UI:Button:Restart' => ' |<< Neustart ',
	'UI:Button:Next' => ' Weiter >> ',
	'UI:Button:Finish' => ' Abschließen ',
	'UI:Button:DoImport' => ' Führe den Import durch! ',
	'UI:Button:Done' => ' Fertig ',
	'UI:Button:SimulateImport' => ' Simuliere den Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Auswerten ',
	'UI:Button:Evaluate:Title' => ' Auswerten (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Hinzufügen... ',
	'UI:Button:BrowseObjects' => ' Durchsuchen... ',
	'UI:Button:Add' => ' Hinzufügen ',
	'UI:Button:AddToList' => ' << Hinzufügen ',
	'UI:Button:RemoveFromList' => ' Entfernen >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Erstellen ',
	'UI:Button:Delete' => ' Löschen! ',
	'UI:Button:Rename' => 'Umbenennen... ',
	'UI:Button:ChangePassword' => ' Passwort ändern ',
	'UI:Button:ResetPassword' => ' Passwort zurücksetzen ',
	'UI:Button:Insert' => 'Einfügen',
	'UI:Button:More' => 'Mehr',
	'UI:Button:Less' => 'Weniger',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Suche',
	'UI:ClickToCreateNew' => 'Klicken Sie hier, um eine neues Objekt vom Typ %1$s zu erstellen',
	'UI:SearchFor_Class' => 'Suche nach Objekten vom Typ "%1$s"',
	'UI:NoObjectToDisplay' => 'Kein Objekt zur Anzeige vorhanden.',
	'UI:Error:SaveFailed' => 'Objekt kann nicht gespeichert werden:',
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
	'UI:Error:CannotWriteToTmp_Dir' => 'Nicht möglich, die tempöräre Datei auf die Festplatte zu speichern: upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Der Upload wurde von der Erweiterung gestoppt. (urspünglicher Dateiname = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Dateiupload fehlgeschlagen, unbekannte Ursache (Fehlercode = "%1$s").',

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
	'UI:Error:InvalidDashboardFile' => 'Fehler: Ungültige Dashboard-Datei',
	'UI:Error:InvalidDashboard' => 'Fehler: Ungültiges Dashboard',
	'UI:Error:MaintenanceMode' => 'Die Anwendung befindet sich derzeit im Wartungsmodus.',
	'UI:Error:MaintenanceTitle' => 'Wartung',

	'UI:GroupBy:Count' => 'Anzahl',
	'UI:GroupBy:Count+' => 'Anzahl der Elemente',
	'UI:CountOfObjects' => '%1$d Objekte, die das Kriterium erfüllen.',
	'UI_CountOfObjectsShort' => '%1$d Objekte.',
	'UI:NoObject_Class_ToDisplay' => 'Kein Objekt vom Typ "%1$s" zur Anzeige vorhanden',
	'UI:History:LastModified_On_By' => 'Zuletzt verändert am %1$s von %2$s.',
	'UI:HistoryTab' => 'Verlauf',
	'UI:NotificationsTab' => 'Benachrichtigungen',
	'UI:History:BulkImports' => 'Verlauf',
	'UI:History:BulkImports+' => '',
	'UI:History:BulkImportDetails' => 'Veränderungen durch den CSV-Import durchgeführt am %1$s (durch %2$s)',
	'UI:History:Date' => 'Datum',
	'UI:History:Date+' => 'Datum der Änderung',
	'UI:History:User' => 'Benutzer',
	'UI:History:User+' => 'Benutzer, der die Änderung durchführte',
	'UI:History:Changes' => 'Änderungen',
	'UI:History:Changes+' => 'Änderungen, die am Objekt durchgeführt wurden',
	'UI:History:StatsCreations' => 'Erstellt',
	'UI:History:StatsCreations+' => 'Anzahl der erstellten Objekte',
	'UI:History:StatsModifs' => 'Modifiziert',
	'UI:History:StatsModifs+' => 'Anzahl der modifizierten Objekte',
	'UI:History:StatsDeletes' => 'Gelöscht',
	'UI:History:StatsDeletes+' => 'Anzahl der gelöschten Objekte',
	'UI:Loading' => 'Laden...',
	'UI:Menu:Actions' => 'Aktionen',
	'UI:Menu:OtherActions' => 'Andere Aktionen',
	'UI:Menu:New' => 'Neu...',
	'UI:Menu:Add' => 'Hinzufügen...',
	'UI:Menu:Manage' => 'Verwalten...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV-Export...',
	'UI:Menu:Modify' => 'Modifizieren...',
	'UI:Menu:Delete' => 'Löschen...',
	'UI:Menu:BulkDelete' => 'Löschen...',
	'UI:UndefinedObject' => 'nicht definiert',
	'UI:Document:OpenInNewWindow:Download' => 'In neuem Fenster öffnen: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'Datum',
	'UI:SplitDateTime-Time' => 'Zeit',
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
	'UI:SearchValue:NbSelected' => '# ausgewählt',
	'UI:SearchValue:CheckAll' => 'Alle auswählen',
	'UI:SearchValue:UncheckAll' => 'Auswahl aufheben',
	'UI:SelectOne' => 'bitte wählen',
	'UI:Login:Welcome' => 'Willkommen bei iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Ungültiges Passwort oder Login-Daten. Bitte versuchen Sie es erneut.',
	'UI:Login:IdentifyYourself' => 'Bitte identifizieren Sie sich, bevor Sie fortfahren.',
	'UI:Login:UserNamePrompt' => 'Benutzername',
	'UI:Login:PasswordPrompt' => 'Passwort',
	'UI:Login:ForgotPwd' => 'Neues Passwort zusenden',
	'UI:Login:ForgotPwdForm' => 'Neues Passwort zusenden',
	'UI:Login:ForgotPwdForm+' => 'iTop kann Ihnen eine Mail senden mit Anweisungen, wie Sie Ihren Account/Passwort zurücksetzen können',
	'UI:Login:ResetPassword' => 'Jetzt senden!',
	'UI:Login:ResetPwdFailed' => 'Konnte keine Email versenden: %1$s',
	'UI:Login:SeparatorOr' => 'oder',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' ist kein gültiger Login',
	'UI:ResetPwd-Error-NotPossible' => 'Passwort-Reset bei externem Benutzerkonto nicht möglich',
	'UI:ResetPwd-Error-FixedPwd' => 'das Benutzerkonto erlaubt keinen Passwort-Reset. ',
	'UI:ResetPwd-Error-NoContact' => 'das Benutzerkonto ist nicht mit einer Person verknüpft. ',
	'UI:ResetPwd-Error-NoEmailAtt' => 'das Benutzerkonto ist nicht mit einer Person verknüpft, die eine Mailadresse besitzt. Bitte wenden Sie sich an Ihren Administrator. ',
	'UI:ResetPwd-Error-NoEmail' => 'die email Adresse dieses Accounts fehlt. Bitte kontaktieren Sie Ihren Administrator.',
	'UI:ResetPwd-Error-Send' => 'Beim Versenden der Email trat ein technisches Problem auf. Bitte kontaktieren Sie Ihren Administrator.',
	'UI:ResetPwd-EmailSent' => 'Bitte schauen Sie in Ihre Mailbox und folgen Sie den Anweisungen.',
	'UI:ResetPwd-EmailSubject' => 'Zurücksetzen Ihres iTop-Passworts',
	'UI:ResetPwd-EmailBody' => '<body><p>Sie haben das Zurücksetzen Ihres iTop Passworts angefordert.</p><p>Bitte folgen Sie diesem Link (funktioniert nur einmalig) : <a href="%1$s">neues Passwort eingeben</a></p>.',

	'UI:ResetPwd-Title' => 'Passwort zurücksetzen',
	'UI:ResetPwd-Error-InvalidToken' => 'Entschuldigung, aber entweder das passwort wurde bereits zurückgesetzt, oder Sie haben mehrere eMails für das Zurücksetzen erhalten. Bitte nutzen Sie den link in der letzten Mail, die Sie erhalten haben.',
	'UI:ResetPwd-Error-EnterPassword' => 'Geben Sie ein neues Passwort für das Konto \'%1$s\' ein.',
	'UI:ResetPwd-Ready' => 'Das Passwort wurde geändert. ',
	'UI:ResetPwd-Login' => 'Klicken Sie hier um sich einzuloggen...',

	'UI:Login:About' => 'Über',
	'UI:Login:ChangeYourPassword' => 'Ändern Sie Ihr Passwort',
	'UI:Login:OldPasswordPrompt' => 'Altes Passwort',
	'UI:Login:NewPasswordPrompt' => 'Neues Passwort',
	'UI:Login:RetypeNewPasswordPrompt' => 'Wiederholen Sie Ihr neues Passwort',
	'UI:Login:IncorrectOldPassword' => 'Fehler: das alte Passwort ist ungültig',
	'UI:LogOffMenu' => 'Abmelden',
	'UI:LogOff:ThankYou' => 'Vielen Dank dafür, dass Sie iTop benutzen!',
	'UI:LogOff:ClickHereToLoginAgain' => 'Klicken Sie hier, um sich wieder anzumelden...',
	'UI:ChangePwdMenu' => 'Passwort ändern...',
	'UI:Login:PasswordChanged' => 'Passwort erfolgreich gesetzt!',
	'UI:AccessRO-All' => 'iTop ist nur lesbar',
	'UI:AccessRO-Users' => 'iTop ist nur lesbar für Endnutzer',
	'UI:ApplicationEnvironment' => 'Applikationsumgebung: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Neues Passwort und das wiederholte Passwort stimmen nicht überein!',
	'UI:Button:Login' => 'in iTop anmelden',
	'UI:Login:Error:AccessRestricted' => 'Der iTop-Zugang ist gesperrt. Bitte kontaktieren Sie Ihren iTop-Administrator.',
	'UI:Login:Error:AccessAdmin' => 'Zugang nur für Personen mit Administratorrechten. Bitte kontaktieren Sie Ihren iTop-Administrator.',
	'UI:Login:Error:WrongOrganizationName' => 'Unbekannte Organisation',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Mehrere Kontakte mit gleicher EMail-Adresse',
	'UI:Login:Error:NoValidProfiles' => 'Kein gültiges Profil ausgewählt',
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
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronisation von %1$d Objekten der Klasse %2$s',
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
	'UI:CSVImport:AdvancedMode+' => 'Im fortgeschrittenen Modus kann die "ID" (primärer Schlüssel) der Objekte benutzt werden, um Ojekte zu aktualisieren oder umzubenennen.Allerdings kann die Spalte "ID" (sofern vorhanden) nur als Suchkriterium verwendet werden und nicht mit anderen Suchkriterien kombiniert werden.',
	'UI:CSVImport:SelectAClassFirst' => 'Wählen Sie bitte zuerst eine Klasse aus, bevor Sie das Mapping erstellen',
	'UI:CSVImport:HeaderFields' => 'Felder',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Suchen?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Bitte wählen Sie ein Mapping für jedes Feld aus.',
	'UI:CSVImport:AlertMultipleMapping' => 'Bitte stellen Sie sicher, dass jedes Zielfeld nur einmal gemapped wird. ',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Bitte wählen Sie mindestens ein Suchkriterium aus.',
	'UI:CSVImport:Encoding' => 'Buchstaben-Codierung',
	'UI:UniversalSearchTitle' => 'iTop - universelle Suche',
	'UI:UniversalSearch:Error' => 'Fehler: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Wählen Sie für die Suche die Klasse aus: ',

	'UI:CSVReport-Value-Modified' => 'Modifiziert',
	'UI:CSVReport-Value-SetIssue' => 'Konnte nicht geändert werden - Grund: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Konnte nicht zu %1$s  geändert werden - Grund: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Kein Treffer',
	'UI:CSVReport-Value-Missing' => 'Pflichtfeld fehlt',
	'UI:CSVReport-Value-Ambiguous' => 'Doppeldeutig: %1$s Objekte gefunden',
	'UI:CSVReport-Row-Unchanged' => 'Unverändert',
	'UI:CSVReport-Row-Created' => 'Erzeugt',
	'UI:CSVReport-Row-Updated' => '%1$d cols aktualisiert',
	'UI:CSVReport-Row-Disappeared' => 'verschwunden, %1$d cols geändert',
	'UI:CSVReport-Row-Issue' => 'Problem: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null nicht erlaubt',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objekt nicht gefunden',
	'UI:CSVReport-Value-Issue-FoundMany' => '%1$d Treffer gefunden',
	'UI:CSVReport-Value-Issue-Readonly' => 'Das Attribut \'%1$s\' ist Read-Only und kann nicht modifiziert werden (derzeitiger Wert: %2$s, vorgeschlagener Wert: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Fehler beim Verarbeiten des Inputs: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unerwarteter Wert für Attribut \'%1$s\': kein Treffer gefunden, Rechtschreibung überprüfen',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unerwarteter Wert für Attribut \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attribute nicht konsistent miteinander: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unerwartete(r) Attributwert(e)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Konnte nicht erzeugt werden, wegen fehlendem/n externen Key(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'falsches Datumsformat',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Abgleich fehlgeschlagen',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'doppeldeutiger Abgleich (Reconcilation)',
	'UI:CSVReport-Row-Issue-Internal' => 'Interner Fehler: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Unverändert',
	'UI:CSVReport-Icon-Modified' => 'Modifiziert',
	'UI:CSVReport-Icon-Missing' => 'Fehlend',
	'UI:CSVReport-Object-MissingToUpdate' => 'Fehlendes Objekt: wird aktualisiert',
	'UI:CSVReport-Object-MissingUpdated' => 'Fehlendes Objekt: aktualisiert',
	'UI:CSVReport-Icon-Created' => 'Erzeugt',
	'UI:CSVReport-Object-ToCreate' => 'Objekt wird erzeugt',
	'UI:CSVReport-Object-Created' => 'Objekt erzeugt',
	'UI:CSVReport-Icon-Error' => 'Fehler',
	'UI:CSVReport-Object-Error' => 'FEHLER: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'DOPPELDEUTIG: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% der geladenen Objekte haben Fehler und werden ignoriert werden.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% der geladenen Objekte werden erzeugt werden.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% der geladenen Objekte werden modifiziert,',

	'UI:CSVExport:AdvancedMode' => 'Erweiterter Modus',
	'UI:CSVExport:AdvancedMode+' => '',
	'UI:CSVExport:LostChars' => 'Kodierungsproblem',
	'UI:CSVExport:LostChars+' => '',

	'UI:Audit:Title' => 'iTop - CMDB-Audit',
	'UI:Audit:InteractiveAudit' => 'Interaktives Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit-Regel',
	'UI:Audit:HeaderNbObjects' => '# Objekte',
	'UI:Audit:HeaderNbErrors' => '# Fehler',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Fehler in der Regel %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Fehler in der Kategorie %1$s: %2$s.',

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
	'UI:RunQuery:DevelopedOQL' => 'Generierte OQL',
	'UI:RunQuery:DevelopedOQLCount' => 'Generierte OQL für Zählung',
	'UI:RunQuery:ResultSQLCount' => 'Resultierendes SQL für Zählung',
	'UI:RunQuery:ResultSQL' => 'Resultierendes SQL',
	'UI:RunQuery:Error' => 'Ein Fehler trat während der Abfrage auf: %1$s auf.',
	'UI:Query:UrlForExcel' => 'URL für MS Excel Web Queries',
	'UI:Query:UrlV1' => 'Die Liste der Felder wurde nicht spezifiziert. Die Seite <em>export-V2.php</em> kann ohne diese Angabe nicht verarbeitet werden. Deswegen, zeigt die nachstehende URL zu der Legacy-Page: <em>export.php</em>. Diese Legacy-Version des Exports hat folgende Limitierungen: Die Liste exportierter Felder kann, abhängig vom Output-Format und vom Datenmodell von iTop, variieren. Möchten Sie garantieren, dass die Liste aller exportierten Spalten stabil bleibt, müssen Sie einen Wert für das Attribut Feld angeben und die Seite <em>export-V2.php</em> nutzen.',
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
	'UI:Schema:FilterCode+' => 'Code für dieses Suchkriterium',
	'UI:Schema:FilterDescription' => 'Beschreibung',
	'UI:Schema:FilterDescription+' => 'Beschreibung dieses Suchkriteriums',
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
	'UI:Schema:ClassFilter' => 'Klasse:',
	'UI:Schema:DisplayLabel' => 'Label:',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label und Code',
	'UI:Schema:DisplaySelector/Label' => 'Label',
	'UI:Schema:DisplaySelector/Code' => 'Code',
	'UI:Schema:Attribute/Filter' => 'Filter',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Geben Sie die ersten 3 Buchstaben ein...',
	'UI:Edit:TestQuery' => 'Query testen',
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
	'UI:Error:ActionNotAllowed' => 'Sie dürfen diese Aktion nicht durchführen',
	'UI:Error:NotEnoughRightsToDelete' => 'Dieses Objekt konnte nicht gelöscht werden, da der derzeitige Benutzer nicht die notwendigen Rechte dazu besitzt.',
	'UI:Error:CannotDeleteBecause' => 'Dieses Objekt konnte aus folgendem Grund nicht gelöscht werden: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Dieses Objekt konnte nicht gelöscht werden, da zuerst einige Manuelle Operationen ausgeführt werden müssen (bzgl. Abhängigkeiten des Objekts).',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Dieses Objekt konnte nicht gelöscht werden, da zuerst dazu einige manuelle Operationen durchgeführt werden müssen.',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s im Auftrag von %2$s',
	'UI:Delete:Deleted' => 'gelöscht',
	'UI:Delete:AutomaticallyDeleted' => 'Automatisch gelöscht',
	'UI:Delete:AutomaticResetOf_Fields' => 'Automatischer Reset der Felder: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Aufräumen aller Referenzen zu %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Aufräumen aller Referenzen zu %1$d Objekten der Klasse %2$s...',
	'UI:Delete:Done+' => 'Was getan wurde...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s gelöscht.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Löschung von %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Löschung von %1$d Objekten der Klasse %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Konnte nicht gelöscht werden: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Sollte automatisch gelöscht werden, was aber nicht durchführbar ist: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Muss manuell gelöscht werden, was aber nicht durchführbar ist: %1$s',
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
	'UI:ObjectArchived' => 'Dieses Objekt wurde archiviert. Bitte aktivieren Sie die Archiv-Modus oder kontaktieren Sie Ihren iTop-Administrator.',
	'Tag:Archived' => 'Archiviert',
	'Tag:Archived+' => 'Auf dieses Objekt kann nur im Archiv-Modus zugegriffen werden',
	'Tag:Obsolete' => 'Obsolet (Veraltet)',
	'Tag:Obsolete+' => 'Von der Impact-Analyse und den Suchresultaten ausgeschlossen',
	'Tag:Synchronized' => 'Synchronisiert',
	'ObjectRef:Archived' => 'Archiviert',
	'ObjectRef:Obsolete' => 'Obsolet (Veraltet)',
	'UI:SearchResultsPageTitle' => 'iTop - Suchergebnisse',
	'UI:SearchResultsTitle' => 'Suchergebnisse',
	'UI:SearchResultsTitle+' => 'Volltext-Suchresultate',
	'UI:Search:NoSearch' => 'Kein Suchbegriff eingegeben',
	'UI:Search:NeedleTooShort' => 'Der Such-String \\"%1$s\\" ist zu kurz. Bitte geben Sie mindestens %2$d Zeichen ein.',
	'UI:Search:Ongoing' => 'Suche nach \\"%1$s\\"',
	'UI:Search:Enlarge' => 'Suche ausweiten',
	'UI:FullTextSearchTitle_Text' => 'Ergebnisse für "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d Objekt(e) der Klasse %2$s gefunden.',
	'UI:Search:NoObjectFound' => 'Kein Objekt gefunden',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s Änderungen',
	'UI:ModificationTitle_Class_Object' => 'Änderungen von %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Dupliziere %1$s - %2$s Änderung',
	'UI:CloneTitle_Class_Object' => 'Duplizieren von %1$s: <span class=\\"hilite\\">%2$s</span>',
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
	'UI:ObjectCouldNotBeWritten' => 'Das Objekt konnte nicht geschrieben werden: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Fataler Fehler',
	'UI:SystemIntrusion' => 'Zugriff verweigert. Sie haben versucht, eine Aktion auszuführen, für die Sie keine ausreichende Berechtigungen besitzen.',
	'UI:FatalErrorMessage' => 'Fataler Fehler! iTop kann leider nicht fortfahren.',
	'UI:Error_Details' => 'Fehler: %1$s.',

	'UI:PageTitle:ClassProjections' => 'iTop Benutzerverwaltung - Klassenabbildung',
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

	'Menu:AdminTools' => 'Admin-Tools', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Administrationswerkzeuge', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Werkzeuge, die nur für Benutzer mit Adminstratorprofil zugänglich sind', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Übersicht an Changes',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes ~nach Typ',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes nach Status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes nach Arbeitsgruppen',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changes, die noch nicht zugeordnet wurden',

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

	'Menu:CSVImportMenu' => 'CSV-Import', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Massenerstellung oder -aktualisierung', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Datenmodell', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Übersicht des Datenmodells', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Export einer beliebigen Abfrage in HTML, CSV oder XML', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Benachrichtigungen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Einstellungen der Benachrichtigungen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Einstellungen der <span class="hilite">Benachrichtigungen</span>',
	'UI:NotificationsMenu:Help' => 'Hilfe',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop sind Benachrichtigungen vollständig anpassbar. Sie basieren auf zwei Gruppen an Objekten: <i>Trigger und Aktionen</i>.</p>
<p><i><b>Trigger</b></i> definieren, wann eine Benachrichtigung ausgeführt wird. Es gibt verschiedene Auslöser als Teil des iTop-Kerns, aber andere können durch Erweiterungen eingebracht werden:
<ol>
	<li>Einige Trigger werden ausgeführt, wenn ein Objekt der angegebenen Klasse <b>angelegt</b>, <b>aktualisiert</b> oder <b>gelöscht wird</b>.</li>
	<li>Einige Trigger werden ausgeführt, wenn ein Objekt einer bestimmten Klasse einen bestimmten <b>Zustand</b> <b>erreicht</b> oder <b>verlässt</b>.</li>
	<li>Einige Trigger werden ausgeführt, wenn ein <b>Schwellenwert</b> auf <b>TTO</b> oder <b>TTR</b> <b>erreicht</b> ist.</li>
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

	'Menu:TagAdminMenu' => 'Tag-Konfiguration',
	'Menu:TagAdminMenu+' => 'Verwaltung der Tag-Werte',
	'UI:TagAdminMenu:Title' => 'Tag-Konfiguration',
	'UI:TagAdminMenu:NoTags' => 'Kein tag konfiguriert',
	'UI:TagSetFieldData:Error' => 'Fehler: %1$s',

	'Menu:AuditCategories' => 'Audit-Kategorien', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Audit-Kategorien', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Audit-Kategorien', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Abfrage ausführen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Eine beliebige Abfrage ausführen', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query-Bibliothek', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Data Management', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Data Management', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Universelle Suche', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Suchen Sie nach beliebigen Inhalt...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'User-Management', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'User-Management', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profile', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profile', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profile', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Benutzerkonten', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Benutzerkonten', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Benutzerkonten', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s Version %2$s',
	'UI:iTopVersion:Long' => '%1$s Version %2$s-%3$s compiliert am %4$s',
	'UI:PropertiesTab' => 'Eigenschaften',

	'UI:OpenDocumentInNewWindow_' => 'Dieses Dokument in einem neuen Fenster öffnen: %1$s',
	'UI:DownloadDocument_' => 'Dieses Dokument herunterladen: %1$s',
	'UI:Document:NoPreview' => 'Für diesen Typ Dokument ist keine Vorschau vorhanden',
	'UI:Download-CSV' => '%1$s herunterladen',

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
	'UI:RelationGroups' => 'Gruppen',
	'UI:OperationCancelled' => 'Operation abgebrochen',
	'UI:ElementsDisplayed' => 'Filtere',
	'UI:RelationGroupNumber_N' => 'Gruppe #%1$d',
	'UI:Relation:ExportAsPDF' => 'Als PDF exportieren...',
	'UI:RelationOption:GroupingThreshold' => 'Schwellwert der Gruppierung',
	'UI:Relation:AdditionalContextInfo' => 'Zusätzliche Kontextinformation',
	'UI:Relation:NoneSelected' => 'Nichts ausgewählt',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Als Attachment exportieren... ',
	'UI:Relation:DrillDown' => 'Details...',
	'UI:Relation:PDFExportOptions' => 'PDF Export Optionen',
	'UI:Relation:AttachmentExportOptions_Name' => 'Optionen für Anhänge zu %1$s',
	'UI:RelationOption:Untitled' => 'Unbezeichnet',
	'UI:Relation:Key' => 'Schlüssel',
	'UI:Relation:Comments' => 'Kommentare',
	'UI:RelationOption:Title' => 'Titel',
	'UI:RelationOption:IncludeList' => 'Inkludiere die Liste der Objekte',
	'UI:RelationOption:Comments' => 'Kommentare',
	'UI:Button:Export' => 'Export',
	'UI:Relation:PDFExportPageFormat' => 'Seitenformat',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Seitenorientierung',
	'UI:PageOrientation_Portrait' => 'Portrait',
	'UI:PageOrientation_Landscape' => 'Landscape',
	'UI:RelationTooltip:Redundancy' => 'Redundanz',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# der betroffenen Items: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Kritischer Schwellwert: %1$d / %2$d',
	'Portal:Title' => 'iTop-Benutzerportal',
	'Portal:NoRequestMgmt' => 'Lieber %1$s, Sie wurden hierher umgeleitet, weil Ihr Account mit dem Profil \'Portal user\' konfiguriert wurde. Leider wurde in iTop aber das \'Request Management\'-Feature nicht installiert. Bitte kontaktieren Sie Ihren Administrator.',
	'Portal:Refresh' => 'Neu laden',
	'Portal:Back' => 'Zurück',
	'Portal:WelcomeUserOrg' => 'Wilkommen %1$s, von %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details für Benutzeranfrage',
	'Portal:ShowOngoing' => 'Zeige offene Requests',
	'Portal:ShowClosed' => 'Zeige geschlossene Requests',
	'Portal:CreateNewRequest' => 'Einen neuen Request erstellen',
	'Portal:CreateNewRequestItil' => 'Einen neuen Request erstellen',
	'Portal:CreateNewIncidentItil' => 'Neuen Incident-Report generieren',
	'Portal:ChangeMyPassword' => 'Mein Passwort ändern',
	'Portal:Disconnect' => 'Abmelden',
	'Portal:OpenRequests' => 'Meine offenen Requests',
	'Portal:ClosedRequests' => 'Meine geschlossenen Requests',
	'Portal:ResolvedRequests' => 'Meine gelösten Requests',
	'Portal:SelectService' => 'Wählen Sie einen Service aus dem Katalog:',
	'Portal:PleaseSelectOneService' => 'Bitte wählen Sie einen Service',
	'Portal:SelectSubcategoryFrom_Service' => 'Wählen Sie einen Unterkategorie für diesen Service vom Typ "%1$s":',
	'Portal:PleaseSelectAServiceSubCategory' => 'Bitte wählen Sie eine Unterkategorie',
	'Portal:DescriptionOfTheRequest' => 'Geben Sie die Beschreibung Ihres Requests ein:',
	'Portal:TitleRequestDetailsFor_Request' => 'Details für Request %1$s:',
	'Portal:NoOpenRequest' => 'Keinen Request in dieser Kategorie',
	'Portal:NoClosedRequest' => 'Keinen Request in dieser Kategorie',
	'Portal:Button:ReopenTicket' => 'Dieses Ticket wiedereröffnen',
	'Portal:Button:CloseTicket' => 'Dieses Ticket schließen',
	'Portal:Button:UpdateRequest' => 'Request aktualisieren',
	'Portal:EnterYourCommentsOnTicket' => 'Geben Sie einen Kommentar zur Lösung dieses Tickets ein:',
	'Portal:ErrorNoContactForThisUser' => 'Fehler: der derzeitige Benutzer wurde nicht einem Kontakt oder einer Person zugewiesen. Bitte kontaktieren Sie Ihren Administrator.',
	'Portal:Attachments' => 'Attachments',
	'Portal:AddAttachment' => ' Attachment hinzufügen',
	'Portal:RemoveAttachment' => 'Attachment entfernen',
	'Portal:Attachment_No_To_Ticket_Name' => 'Attachment #%1$d an %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Wählen Sie eine Template für %1$s',
	'Enum:Undefined' => 'Nicht definiert',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Tage %2$s Stunden %3$s Minuten %4$s Sekunden',
	'UI:ModifyAllPageTitle' => 'Alle modifizieren',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifiziere %1$d Objekte der Klasse %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modifiziere %1$d Objekte der Klasse %2$s von insgesamt %3$d',
	'UI:Menu:ModifyAll' => 'Modifizieren...',
	'UI:Button:ModifyAll' => 'Alle modifizieren',
	'UI:Button:PreviewModifications' => 'Vorschau auf Modifikationen >>',
	'UI:ModifiedObject' => 'Objekt modifiziert',
	'UI:BulkModifyStatus' => 'Operation',
	'UI:BulkModifyStatus+' => '',
	'UI:BulkModifyErrors' => 'Fehler (falls vorhanden)',
	'UI:BulkModifyErrors+' => '',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Fehler',
	'UI:BulkModifyStatusModified' => 'Modifiziert',
	'UI:BulkModifyStatusSkipped' => 'Übersprungen',
	'UI:BulkModify_Count_DistinctValues' => '%1$d unterschiedliche Werte:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d mal',
	'UI:BulkModify:N_MoreValues' => '%1$d weitere Werte...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Versuche, Read-Only-Feld zu setzen: %1$s',
	'UI:FailedToApplyStimuli' => 'Der Vorgang ist fehlgeschlagen.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifiziere %2$d Objekte der Klasse %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Geben Sie Ihren Text hier ein:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Anfangswert:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Das Feld %1$s ist nicht schreibbar, weil es durch die Datensynchronisation geführt wird. Wert nicht gesetzt.',
	'UI:ActionNotAllowed' => 'Sie haben nicht die Berechtigung, diese Aktion auf diesen Objekten auszuführen.',
	'UI:BulkAction:NoObjectSelected' => 'Bitte wählen Sie mindestens ein Objekt, um diese Aktion auszuführen.',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Das Feld %1$s ist nicht schreibbar, weil es durch die Datensynchronisation geführt wird. Wert bleibt unverändert.',
	'UI:Pagination:HeaderSelection' => 'Gesamt: %1$s Objekte (%2$s Objekte ausgewählt).',
	'UI:Pagination:HeaderNoSelection' => 'Gesamt: %1$s Objekte.',
	'UI:Pagination:PageSize' => '%1$s Objekte pro Seite',
	'UI:Pagination:PagesLabel' => 'Seiten:',
	'UI:Pagination:All' => 'Alles',
	'UI:HierarchyOf_Class' => 'Hierarchie von %1$s',
	'UI:Preferences' => 'Einstellungen...',
	'UI:ArchiveModeOn' => 'Archivmodus aktivieren',
	'UI:ArchiveModeOff' => 'Archivmodus deaktivieren',
	'UI:ArchiveMode:Banner' => 'Archivmodus',
	'UI:ArchiveMode:Banner+' => 'Archivierte Objekte sind sichtbar, aber Veränderung ist nicht erlaubt',
	'UI:FavoriteOrganizations' => 'Bevorzugte Organisationen',
	'UI:FavoriteOrganizations+' => '',
	'UI:FavoriteLanguage' => 'Sprache des Benutzerinterfaces',
	'UI:Favorites:SelectYourLanguage' => 'Wählen Sie Ihre bevorzugte Sprache aus',
	'UI:FavoriteOtherSettings' => 'Andere Einstellungen',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default-Länge für Listen:  %1$s Elemente pro Seite',
	'UI:Favorites:ShowObsoleteData' => 'Zeige obsolete (veraltete) Daten',
	'UI:Favorites:ShowObsoleteData+' => 'Zeige obsolete (veraltete) Daten in Suchresultaten und Auswahllisten von Objekten',
	'UI:NavigateAwayConfirmationMessage' => 'Jedwede Veränderung wird verworfen.',
	'UI:CancelConfirmationMessage' => 'Sie werden Ihre Änderungen verlieren. Dennoch fortfahren?',
	'UI:AutoApplyConfirmationMessage' => 'Einige Änderungen wurden noch nicht angewandt. Möchten Sie, daß iTop diese berüchsichtigt?',
	'UI:Create_Class_InState' => 'Erzeuge die/das %1$s in Status: ',
	'UI:OrderByHint_Values' => 'Sortierreihenfolge: %1$s',
	'UI:Menu:AddToDashboard' => 'Zu Dashboard hinzufügen...',
	'UI:Button:Refresh' => 'Neu laden',
	'UI:Button:GoPrint' => 'Drucken...',
	'UI:ExplainPrintable' => 'Klicken Sie auf das %1$s icon, um Teile für der Druck auszublenden.<br/>Benutzen Sie die Druckvorschau-Funktion Ihres Browsers, um sich eine Vorschau anzeigen zu lassen.<br/>Hinweis: Dieser Header und die anderen Steuerungsflächen werden nicht gedruckt.',
	'UI:PrintResolution:FullSize' => 'Gesamte Fläche',
	'UI:PrintResolution:A4Portrait' => 'A4 Hochformat',
	'UI:PrintResolution:A4Landscape' => 'A4 Querformat',
	'UI:PrintResolution:LetterPortrait' => 'Letter Hochformat',
	'UI:PrintResolution:LetterLandscape' => 'Letter Querformat',
	'UI:Toggle:StandardDashboard' => 'Standard',
	'UI:Toggle:CustomDashboard' => 'Angepasst',

	'UI:ConfigureThisList' => 'Liste konfigurieren...',
	'UI:ListConfigurationTitle' => 'Listenkonfiguration',
	'UI:ColumnsAndSortOrder' => 'Spalten und Sortierrheienfolge:',
	'UI:UseDefaultSettings' => 'Verwende Default-Einstellungen',
	'UI:UseSpecificSettings' => 'Verwende folgende Einstellungen:',
	'UI:Display_X_ItemsPerPage' => '%1$s Elemente pro Seite anzeigen',
	'UI:UseSavetheSettings' => 'Einstellungen speichern',
	'UI:OnlyForThisList' => 'Nur für diese Liste',
	'UI:ForAllLists' => 'Standard für alle Listen',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Nach oben',
	'UI:Button:MoveDown' => 'Nach unten',

	'UI:OQL:UnknownClassAndFix' => 'Unbekannte Klasse "%1$s". Sie könnten stattdessen "%2$s" versuchen.',
	'UI:OQL:UnknownClassNoFix' => 'Unbekannte Klasse "%1$s"',

	'UI:Dashboard:Edit' => 'Diese Seite bearbeiten...',
	'UI:Dashboard:Revert' => 'Auf Originalversion zurücksetzen...',
	'UI:Dashboard:RevertConfirm' => 'Alle gemachten Änderungen gehen verloren. Bitte bestätigen Sie, daß Sie dies so wünschen.',
	'UI:ExportDashBoard' => 'In Datei exportieren',
	'UI:ImportDashBoard' => 'Aus Datei importieren...',
	'UI:ImportDashboardTitle' => 'Import aus einer Datei',
	'UI:ImportDashboardText' => 'Wählen Sie eine Dashboard-Datei zum Import:',


	'UI:DashletCreation:Title' => 'Neues ashlet erzeugen',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Dashlet-Typ',
	'UI:DashletCreation:EditNow' => 'Dashboard bearbeiten',

	'UI:DashboardEdit:Title' => 'Dashboard-Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Titel',
	'UI:DashboardEdit:AutoReload' => 'Automatischer Reload',
	'UI:DashboardEdit:AutoReloadSec' => 'Intervall für automatischen Reload (Sekunden)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Der Mindestwert beträgt %1$d Sekunden',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Dashboard-Einstellungen',
	'UI:DashboardEdit:Dashlets' => 'Verfügbare Dashlets',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet-Einstellungen',

	'UI:Form:Property' => 'Einstellung',
	'UI:Form:Value' => 'Wert',

	'UI:DashletUnknown:Label' => 'Unbekannt',
	'UI:DashletUnknown:Description' => 'Unbekanntes Dashlet (ggf. wurde es deinstalliert)',
	'UI:DashletUnknown:RenderText:View' => 'Dieses Dashlet kann nicht dargestellt werden.',
	'UI:DashletUnknown:RenderText:Edit' => 'Dieses Dashlet kann nicht dargestellt werden (Klasse "%1$s"). Bitte kontaktieren Sie Ihren Administrator, ob es noch verfügbar ist.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Keine Vorschau für dieses Dashlet verfügbar (Klasse "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Konfiguration (Anzeige des XML als einfacher Text)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy Dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Keine Vorschau für dieses externe Dashlet verfügbar (Klasse "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Konfiguration (Anzeige des XML als einfacher Text)',

	'UI:DashletPlainText:Label' => 'Text',
	'UI:DashletPlainText:Description' => 'Reiner Text (ohne Formatierung)',
	'UI:DashletPlainText:Prop-Text' => 'Text',
	'UI:DashletPlainText:Prop-Text:Default' => 'Bitte Text hier eingeben...',

	'UI:DashletObjectList:Label' => 'Objektliste',
	'UI:DashletObjectList:Description' => 'Objektlisten-Dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Titel',
	'UI:DashletObjectList:Prop-Query' => 'Query',
	'UI:DashletObjectList:Prop-Menu' => 'Menü',

	'UI:DashletGroupBy:Prop-Title' => 'Titel',
	'UI:DashletGroupBy:Prop-Query' => 'Query',
	'UI:DashletGroupBy:Prop-Style' => 'Stil',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Gruppieren nach...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Stunde von %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Monat von %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Wochentag für %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Tag im Monat für %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (Stunde)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (Monat)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (Wochentag)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (Wochentag)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Bitte wählen Sie das Feld, nach dem die Objekte gruppiert werden',

	'UI:DashletGroupByPie:Label' => 'Tortendiagramm',
	'UI:DashletGroupByPie:Description' => 'Tortendiagramm',
	'UI:DashletGroupByBars:Label' => 'Balkendiagramm',
	'UI:DashletGroupByBars:Description' => 'Balkendiagramm',
	'UI:DashletGroupByTable:Label' => 'Gruppieren nach (Tabelle)',
	'UI:DashletGroupByTable:Description' => 'Liste (gruppiert nach einem Feld)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Aggregatfunktion',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Funktionsattribut',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Richtung',
	'UI:DashletGroupBy:Prop-OrderField' => 'Sortieren nach',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit',

	'UI:DashletGroupBy:Order:asc' => 'Aufsteigend',
	'UI:DashletGroupBy:Order:desc' => 'Absteigend',

	'UI:GroupBy:count' => 'Anzahl',
	'UI:GroupBy:count+' => 'Anzahl der Elemente',
	'UI:GroupBy:sum' => 'Summe',
	'UI:GroupBy:sum+' => 'Summe von %1$s',
	'UI:GroupBy:avg' => 'Durchschnitt',
	'UI:GroupBy:avg+' => 'Durchschnitt von %1$s',
	'UI:GroupBy:min' => 'Minimum',
	'UI:GroupBy:min+' => 'Minimum von %1$s',
	'UI:GroupBy:max' => 'Maximum',
	'UI:GroupBy:max+' => 'Maximum von %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header',
	'UI:DashletHeaderStatic:Description' => 'Zeigt einen horizontalen Trenner',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titel',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kontakte',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icon',

	'UI:DashletHeaderDynamic:Label' => 'Header mit Statistiken',
	'UI:DashletHeaderDynamic:Description' => 'Header mit Statistiken (gruppiert nach...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Titel',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kontakte',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Untertitel',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kontakte',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Gruppieren nach',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Werte',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Objekt-Icon bei \'Neu/Suche\'',
	'UI:DashletBadge:Prop-Class' => 'Klasse',

	'DayOfWeek-Sunday' => 'Sonntag',
	'DayOfWeek-Monday' => 'Montag',
	'DayOfWeek-Tuesday' => 'Dienstag',
	'DayOfWeek-Wednesday' => 'Mittwoch',
	'DayOfWeek-Thursday' => 'Donnerstag',
	'DayOfWeek-Friday' => 'Freitag',
	'DayOfWeek-Saturday' => 'Samstag',
	'Month-01' => 'Januar',
	'Month-02' => 'Februar',
	'Month-03' => 'März',
	'Month-04' => 'April',
	'Month-05' => 'Mai',
	'Month-06' => 'Juni',
	'Month-07' => 'Juli',
	'Month-08' => 'August',
	'Month-09' => 'September',
	'Month-10' => 'Oktober',
	'Month-11' => 'November',
	'Month-12' => 'Dezember',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'So',
	'DayOfWeek-Monday-Min' => 'Mo',
	'DayOfWeek-Tuesday-Min' => 'Di',
	'DayOfWeek-Wednesday-Min' => 'Mi',
	'DayOfWeek-Thursday-Min' => 'Do',
	'DayOfWeek-Friday-Min' => 'Fr',
	'DayOfWeek-Saturday-Min' => 'Sa',
	'Month-01-Short' => 'Jan',
	'Month-02-Short' => 'Feb',
	'Month-03-Short' => 'Mär',
	'Month-04-Short' => 'Apr',
	'Month-05-Short' => 'Mai',
	'Month-06-Short' => 'Juni',
	'Month-07-Short' => 'Juli',
	'Month-08-Short' => 'Aug',
	'Month-09-Short' => 'Sept',
	'Month-10-Short' => 'Okt',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Dez',
	'Calendar-FirstDayOfWeek' => '1', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Shortcut anlegen',
	'UI:ShortcutRenameDlg:Title' => 'Shortcut umbenennen',
	'UI:ShortcutListDlg:Title' => 'Shortcut für die Liste anlegen',
	'UI:ShortcutDelete:Confirm' => 'Bitte bestätigen Sie, dass Sie den/die Shortcut(s) löschen möchten. ',
	'Menu:MyShortcuts' => 'Meine Shortcuts', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Shortcut',
	'Class:Shortcut+' => 'Schnellzugriff auf Objekte',
	'Class:Shortcut/Attribute:name' => 'Name',
	'Class:Shortcut/Attribute:name+' => 'Label, das im Menü und im Seitentitel verwendet wird',
	'Class:ShortcutOQL' => 'Suchergebnis-Shortcut',
	'Class:ShortcutOQL+' => 'Short für eine OQL-Abfrage',
	'Class:ShortcutOQL/Attribute:oql' => 'Query',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL-Query, der die zu Suchenden Objekte beschreibt',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatischer Reload',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Deaktiviert',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Eigene Einstellung',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Intervall für automatischen Reload (Sekunden)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'Der Mindestwert beträgt %1$d Sekunden',

	'UI:FillAllMandatoryFields' => 'Bitte füllen Sie alle Pflichtfelder',
	'UI:ValueMustBeSet' => 'Bitte geben Sie einen Wert an',
	'UI:ValueMustBeChanged' => 'Bitte ändern Sie den Wert',
	'UI:ValueInvalidFormat' => 'Ungültiges Format',

	'UI:CSVImportConfirmTitle' => 'Bitte bestätigen Sie die Operation',
	'UI:CSVImportConfirmMessage' => 'Sind Sie sicher, dass Sie dies durchführen möchten?',
	'UI:CSVImportError_items' => 'Fehler: %1$d',
	'UI:CSVImportCreated_items' => 'Angelegt: %1$d',
	'UI:CSVImportModified_items' => 'Geändert: %1$d',
	'UI:CSVImportUnchanged_items' => 'Unverändert: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Datum- und Zeitformat',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Standardformat: %1$s (z.B. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Angepasstes Format: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'verfügbare Platzhalter:<table>
<tr><td>Y</td><td>Jahr (4 Ziffern, z.B. 2016)</td></tr>
<tr><td>y</td><td>Jahr (2 Ziffern, z.B. 16 für 2016)</td></tr>
<tr><td>m</td><td>Monat (2 Ziffern, z.B. 01..12)</td></tr>
<tr><td>n</td><td>Monat (1 oder 2 Ziffern ohne führende Null, z.B. 1..12)</td></tr>
<tr><td>d</td><td>Tag (2 Ziffern, z.B. 01..31)</td></tr>
<tr><td>j</td><td>day (1 oder 2 Ziffern ohne führende Null, z.B. 1..31)</td></tr>
<tr><td>H</td><td>Stunden (24 Stunden, 2 Ziffern, z.B. 00..23)</td></tr>
<tr><td>h</td><td>Stunden (12 Stunden, 2 Ziffern, z.B. 01..12)</td></tr>
<tr><td>G</td><td>Stunden (24 Stunden, 1 or 2 Ziffern ohne führende Null, z.B. 0..23)</td></tr>
<tr><td>g</td><td>Stunden (12 Stunden, 1 or 2 Ziffern ohne führende Null, z.B. 1..12)</td></tr>
<tr><td>a</td><td>Stunden, am oder pm (lowercase)</td></tr>
<tr><td>A</td><td>Stunden, AM oder PM (uppercase)</td></tr>
<tr><td>i</td><td>Minuten (2 Ziffern, z.B. 00..59)</td></tr>
<tr><td>s</td><td>Sekunden (2 Ziffern, z.B. 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => 'Entfernen',
	'UI:AddAnExisting_Class' => 'Objekte des Typs %1$s hinzufügen...',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s',

	'UI:AboutBox' => 'Über iTop...',
	'UI:About:Title' => 'Über iTop',
	'UI:About:DataModel' => 'Datenmodell',
	'UI:About:Support' => 'Support-Information',
	'UI:About:Licenses' => 'Lizenzen',
	'UI:About:InstallationOptions' => 'Installationsoptionen',
	'UI:About:ManualExtensionSource' => 'Erweiterungen',
	'UI:About:Extension_Version' => 'Version: %1$s',
	'UI:About:RemoteExtensionSource' => 'Data',

	'UI:DisconnectedDlgMessage' => 'Sie sind abgemeldet. Sie müssen sich identifizeren, um die Anwendung weiter zu benutzen.',
	'UI:DisconnectedDlgTitle' => 'Warnung!',
	'UI:LoginAgain' => 'Erneut einloggen',
	'UI:StayOnThePage' => 'Auf dieser Seite bleiben',

	'ExcelExporter:ExportMenu' => 'Excel-Export...',
	'ExcelExporter:ExportDialogTitle' => 'Excel-Export',
	'ExcelExporter:ExportButton' => 'Export',
	'ExcelExporter:DownloadButton' => 'Download %1$s',
	'ExcelExporter:RetrievingData' => 'Lese Daten...',
	'ExcelExporter:BuildingExcelFile' => 'Erstelle Excel-Datei...',
	'ExcelExporter:Done' => 'Fertig.',
	'ExcelExport:AutoDownload' => 'Den Download automatisch starten, sobald der Exportvorgang abgeschlossen ist',
	'ExcelExport:PreparingExport' => 'Bereite Export vor...',
	'ExcelExport:Statistics' => 'Statistik',
	'portal:legacy_portal' => 'Endbenutzer-Portal',
	'portal:backoffice' => 'iTop Backend',

	'UI:CurrentObjectIsLockedBy_User' => 'Das Objekt ist gesperrt, da es derzeit durch %1$s bearbeitet wird.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Das Objekt wird derzeit durch %1$s bearbeitet. Ihre Änderungen können nicht abgesendet werden, da sie überschrieben würden.',
	'UI:CurrentObjectLockExpired' => 'Die Sperre um simultane Änderungen des Objekts zu verhindern ist abgelaufen.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Die Sperre um simultane Änderungen des Objekts zu verhindern ist abgelaufen. Sie können Ihre Änderungen nicht mehr absenden, da andere User jetzt das Objekt verändern können. ',
	'UI:ConcurrentLockKilled' => 'Die Sperre um simultane Änderungen an dem Objekt zu verhindern ist gelöscht worden. ',
	'UI:Menu:KillConcurrentLock' => 'Sperre für simultane Änderungen löschen! ',

	'UI:Menu:ExportPDF' => 'Als PDF exportieren... ',
	'UI:Menu:PrintableVersion' => 'Druckversion',

	'UI:BrowseInlineImages' => 'Bilder durchsuchen...',
	'UI:UploadInlineImageLegend' => 'Neues Bild hochladen',
	'UI:SelectInlineImageToUpload' => 'Wähle das Bild für den Upload aus',
	'UI:AvailableInlineImagesLegend' => 'Verfügbare Bilder',
	'UI:NoInlineImage' => 'Es sind keine Bilder auf dem Server verfügbar. Nutze den "Durchsuchen" Button oben, um ein Bild vom Computer hochzuladen.',

	'UI:ToggleFullScreen' => 'Maximieren / Minimieren',
	'UI:Button:ResetImage' => 'Vorheriges Bild wiederherstellen',
	'UI:Button:RemoveImage' => 'Bild löschen',
	'UI:UploadNotSupportedInThisMode' => 'Die Modifizierung von Bildern oder Dateien wird in diesem Modus nicht unterstützt.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Ein-/Ausklappen',
	'UI:Search:AutoSubmit:DisabledHint' => 'Automatische Eingabe für diese Klasse deaktiviert',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Obsolete Daten werden wegen ihrer Einstellung nicht angezeigt',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Fügen Sie ein Kriterium in das Suchfeld ein oder klicken Sie auf die Suchschaltfläche, um die Objekte anzuzeigen.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Kriterium hinzufügen',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Kürzlich verwendet',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Am beliebtesten',
	'UI:Search:AddCriteria:List:Others:Title' => 'Andere',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Noch keine',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Beliebig',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s ist leer',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s ist nicht leer',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s is gleich %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s enthält %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s startet mit %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s endet mit %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s passt zu %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s zwischen [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Beliebig',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s von %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s bis %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Beliebig',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s von %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s bis %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s und %3$s andere',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Beliebig',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s ist definiert',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s ist nicht definiert',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s und %3$s andere',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Beliebig',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s ist definiert',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s ist nicht definiert',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s und %3$s andere',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Beliebig',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Ist leer',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Ist nicht leer',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Ist gleich',
	'UI:Search:Criteria:Operator:Default:Between' => 'Ist zwischen',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Enthält',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Startet mit',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Endet mit',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Reg. Ausdruck',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Ist gleich',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Ist größer',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Ist größer / gleich',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Ist kleiner',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Ist kleiner / gleich',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Ist ungleich',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Entspricht',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...',
	'UI:Search:Value:Search:Placeholder' => 'Suche...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Geben Sie mögliche Werte ein.',
	'UI:Search:Value:Autocomplete:Wait' => 'Bitte warten...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Kein Ergebnis',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Alles aus- / abwählen',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Alle Sichtbaren aus- / abwählen',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'Von',
	'UI:Search:Criteria:Numeric:Until' => 'Bis',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Beliebig',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Beliebig',
	'UI:Search:Criteria:DateTime:From' => 'Von',
	'UI:Search:Criteria:DateTime:FromTime' => 'Von',
	'UI:Search:Criteria:DateTime:Until' => 'Bis',
	'UI:Search:Criteria:DateTime:UntilTime' => 'Bis',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Beliebig',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Beliebig',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Beliebig',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Beliebig',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Kinderelemente ausgewählter Objekte werden berücksichtigt.',

	'UI:Search:Criteria:Raw:Filtered' => 'Gefiltert',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Gefiltert über %1$s',
));

//
// Expression to Natural language
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Expression:Operator:AND' => ' UND ',
	'Expression:Operator:OR' => ' ODER ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 't',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'j',

	'Expression:Unit:Long:DAY' => 'Tag(e)',
	'Expression:Unit:Long:HOUR' => 'Stunde(n)',
	'Expression:Unit:Long:MINUTE' => 'Minute(n)',

	'Expression:Verb:NOW' => 'jetzt',
	'Expression:Verb:ISNULL' => ': nicht definiert',
));

//
// iTop Newsroom menu
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'UI:Newsroom:NoNewMessage' => 'Keine neue Nachricht',
	'UI:Newsroom:MarkAllAsRead' => 'Alle Nachrichten als gelesen markieren',
	'UI:Newsroom:ViewAllMessages' => 'Alle Nachrichten anzeigen',
	'UI:Newsroom:Preferences' => 'Newsroom-Einstellungen',
	'UI:Newsroom:ConfigurationLink' => 'Konfiguration',
	'UI:Newsroom:ResetCache' => 'Cache zurücksetzen',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Nachrichten von  %1$s anzeigen',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Zeigen Sie höchstens %1$s Beiträge im Menü (%2$s) an.',
));

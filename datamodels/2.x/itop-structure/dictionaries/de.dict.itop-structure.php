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
//   along with iTop. If not, see <http://www.gnu.org/licenses
/**
* @author Benjamin Planque <benjamin.planque@combodo.com>
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2021 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*
*/
//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
//
// Class: Organization
//
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Name',
	'Class:Organization/Attribute:name+' => 'Gemeinsamer Name',
	'Class:Organization/Attribute:code' => 'Kennziffer',
	'Class:Organization/Attribute:code+' => 'Organisationskennziffer (D-U-N-S, Siret)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Organization/Attribute:parent_id' => 'Mutterfirma',
	'Class:Organization/Attribute:parent_id+' => 'Dachorganisation',
	'Class:Organization/Attribute:parent_name' => 'Name der Mutterfirma',
	'Class:Organization/Attribute:parent_name+' => 'Name der Mutterfirma',
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery-Modell',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery-Modell-Name',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Parent',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Überblick',
	'Organization:Overview:FunctionalCIs' => 'CIs dieser Organisation',
	'Organization:Overview:FunctionalCIs:subtitle' => 'nach Typ',
	'Organization:Overview:Users' => 'iTop Benutzer innerhalb dieser Organisation',
));

//
// Class: Location
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Location' => 'Standort',
	'Class:Location+' => 'Jeder Typ von Standort: Region, Land, Stadt, Seite, Gebäude, Flur, Raum, Rack,...',
	'Class:Location/Attribute:name' => 'Name',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktiv',
	'Class:Location/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Location/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Location/Attribute:org_id' => 'Organisation',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Name der Organisation',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresse',
	'Class:Location/Attribute:address+' => 'Postanschrift',
	'Class:Location/Attribute:postal_code' => 'Postleitzahl',
	'Class:Location/Attribute:postal_code+' => 'Postleitzahl',
	'Class:Location/Attribute:city' => 'Stadt',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Geräte',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => 'Kontakte',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Name',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktiv',
	'Class:Contact/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Contact/Attribute:org_id' => 'Organisation',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organisation',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefonnummer',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Benachrichtigung',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Nein',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'Ja',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Funktion',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => 'Typ',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Name',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Vorname',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Personalnummer',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobiltelefon',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Standort',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Standortname',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Manager-Name',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Teams',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager Friendly Name',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Bild',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Die Personalnummer muss innerhalb dieser Organisation eindeutig sein.',
	'Class:Person/UniquenessRule:employee_number' => 'In der Organisation \'$this->org_name$\' existiert bereits eine Person mit der gleichen Personalnummer',
	'Class:Person/UniquenessRule:name+' => 'Innerhalb einer Organisation muss der Name einer Person eindeutig sein',
	'Class:Person/UniquenessRule:name' => 'In der Organisation \'$this->org_name$\' existiert bereits eine Person mit dem gleichen Namen',
));

//
// Class: Team
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Mitglieder',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisation',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Organisationsname',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Dokumenttyp',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Dokumenttypname',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Beschreibung',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Entwurf',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Veröffentlicht',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Dokumenttyp',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentFile' => 'Dokument (Datei)',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Datei',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentNote' => 'Dokument (Notiz)',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Text',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentWeb' => 'Dokument (Web)',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Name',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Typ',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentType' => 'Dokumentyp',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ContactType' => 'Kontakttyp',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkPersonToTeam' => 'Verknüpfung Person/Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Teamname',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Personenname',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rolle',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Rollenname',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:DataAdministration' => 'Data Management',
	'Menu:DataAdministration+' => 'Data Management',
	'Menu:Catalogs' => 'Kataloge',
	'Menu:Catalogs+' => 'Datentypen',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV-Import',
	'Menu:CSVImport+' => 'Massenerstellung oder -aktualisierung',
	'Menu:Organization' => 'Organisationen',
	'Menu:Organization+' => 'Alle Organisationen',
	'Menu:ConfigManagement' => 'Configuration Management',
	'Menu:ConfigManagement+' => 'Configuration Management',
	'Menu:ConfigManagementCI' => 'Configuration Items',
	'Menu:ConfigManagementCI+' => 'Configuration Items',
	'Menu:ConfigManagementOverview' => 'Übersicht',
	'Menu:ConfigManagementOverview+' => 'Übersicht',
	'Menu:Contact' => 'Kontakte',
	'Menu:Contact+' => 'Kontakte',
	'Menu:Contact:Count' => '%1$d Kontakte',
	'Menu:Person' => 'Personen',
	'Menu:Person+' => 'Alle Personen',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'Alle Teams',
	'Menu:Document' => 'Dokumente',
	'Menu:Document+' => 'Alle Dokumente',
	'Menu:Location' => 'Standorte',
	'Menu:Location+' => 'Alle Standorte',
	'Menu:NewContact' => 'Neuer Kontakt',
	'Menu:NewContact+' => 'Neuer Kontakt',
	'Menu:SearchContacts' => 'Nach Kontakten suchen',
	'Menu:SearchContacts+' => 'Nach Kontakten suchen',
	'Menu:ConfigManagement:Shortcuts' => 'Shortcuts',
	'Menu:ConfigManagement:AllContacts' => 'Alle Kontakte: %1$d',
	'Menu:Typology' => 'Typologie-Konfiguration',
	'Menu:Typology+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Zusammenfassung',
	'Menu:ConfigManagement:Typology' => 'Typologie-Konfiguration',
));

// Add translation for Fieldsets

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Person:info' => 'Allgemeine Informationen',
	'UserLocal:info' => 'Allgemeine Informationen',
	'Person:personal_info' => 'Persönliche Informationen',
	'Person:notifiy' => 'Benachrichtigungen',
));

// Themes
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'theme:fullmoon' => 'Full Moon',
	'theme:test-red' => 'Test Red (Testinstanz)',
));

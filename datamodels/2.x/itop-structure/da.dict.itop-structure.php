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
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @author Benjamin Planque <benjamin.planque@combodo.com>
 * @author	Erik Bøg <erik@boegmoeller.dk>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
//
// Class: Organization
//
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Navn',
	'Class:Organization/Attribute:name+' => 'Almindeligt navn',
	'Class:Organization/Attribute:code' => 'Kodenummer',
	'Class:Organization/Attribute:code+' => '',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Organization/Attribute:parent_id' => 'Parent id',
	'Class:Organization/Attribute:parent_id+' => '',
	'Class:Organization/Attribute:parent_name' => 'Parent name',
	'Class:Organization/Attribute:parent_name+' => 'Parent name',
	'Class:Organization/Attribute:deliverymodel_id' => 'Leverings Model Id',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Leveringsmodel navn',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Parent',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Location' => 'Placering',
	'Class:Location+' => 'Enhver type af placering: Region, land, by, bygning, rum rack, ...',
	'Class:Location/Attribute:name' => 'Navn',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktiv',
	'Class:Location/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Location/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Location/Attribute:org_id' => 'Organisation',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Organisationsnavn',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresse',
	'Class:Location/Attribute:address+' => 'Postadresse',
	'Class:Location/Attribute:postal_code' => 'Postnummer',
	'Class:Location/Attribute:postal_code+' => 'Postnummer',
	'Class:Location/Attribute:city' => 'By',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'List Fysiske Enheder',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => 'List Kontakter',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Navn',
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
	'Class:Contact/Attribute:notify' => 'Underretning',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Nej',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'Ja',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Funktion',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => 'Type',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Efternavn',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => 'Fornavn',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Personalenummer',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobiltelefon',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Placering',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Placering',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Manager-Navn',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'List Teams',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'List Tickets',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager Friendly Name',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Picture~~',
	'Class:Person/Attribute:picture+' => '~~',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization~~',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number~~',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization~~',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name~~',
));

//
// Class: Team
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'List Medlemmer',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'List Tickets',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Navn',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisation',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Organisationsnavn',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Dokumenttype',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Dokumenttypnavn',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version~~',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => 'Beskrivelse',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Udkast',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Offentlig',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Dokumenttype',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DocumentFile' => 'Dokument (Data)',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Data',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DocumentNote' => 'Dokument (Noter)',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Tekst',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DocumentWeb' => 'Dokument (Web)',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Typology' => 'Typologi',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Navn',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Type',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DocumentType' => 'Dokumentype',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ContactType' => 'Kontakttype',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkPersonToTeam' => 'Sammenhæng Person/Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Team navn',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Person navn',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rolle',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Rollen navn',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Menu:DataAdministration' => 'Data administration',
	'Menu:DataAdministration+' => '',
	'Menu:Catalogs' => 'Katalog',
	'Menu:Catalogs+' => 'Datatyper',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV import~~',
	'Menu:CSVImport+' => 'Bulk creation or update~~',
	'Menu:Organization' => 'Organisation',
	'Menu:Organization+' => 'Alle Organisationer',
	'Menu:ConfigManagement' => 'Configuration Management',
	'Menu:ConfigManagement+' => 'Configuration Management',
	'Menu:ConfigManagementCI' => 'Configuration Items',
	'Menu:ConfigManagementCI+' => 'Configuration Items',
	'Menu:ConfigManagementOverview' => 'Oversigt',
	'Menu:ConfigManagementOverview+' => 'Oversigt',
	'Menu:Contact' => 'Kontakt',
	'Menu:Contact+' => 'Kontakt',
	'Menu:Contact:Count' => '%1$d kontakter',
	'Menu:Person' => 'Person',
	'Menu:Person+' => 'Alle Personer',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'Alle Teams',
	'Menu:Document' => 'Dokument',
	'Menu:Document+' => 'Alle Dokumenter',
	'Menu:Location' => 'Placering',
	'Menu:Location+' => 'Alle Placeringer',
	'Menu:NewContact' => 'Ny Kontakt',
	'Menu:NewContact+' => 'Ny Kontakt',
	'Menu:SearchContacts' => 'Søg efter kontakter',
	'Menu:SearchContacts+' => 'Søg efter kontakter',
	'Menu:ConfigManagement:Shortcuts' => 'Genveje',
	'Menu:ConfigManagement:AllContacts' => 'Alle Kontakter: %1$d',
	'Menu:Typology' => 'Typologi-Konfiguration',
	'Menu:Typology+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Sammenfatning',
	'Menu:ConfigManagement:Typology' => 'Typologi Konfiguration',
));

// Add translation for Fieldsets

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Person:info' => 'Almindelig Information',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Underretning',
));

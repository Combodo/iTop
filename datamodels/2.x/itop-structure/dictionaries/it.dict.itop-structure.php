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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Organization' => 'Organizzazione',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Cognome',
	'Class:Organization/Attribute:name+' => 'Nome',
	'Class:Organization/Attribute:code' => 'Codice',
	'Class:Organization/Attribute:code+' => 'Codice dell\'organizzazione (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Stato',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Attivo',
	'Class:Organization/Attribute:status/Value:active+' => 'Attivo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Organization/Attribute:parent_id' => 'Parent',
	'Class:Organization/Attribute:parent_id+' => 'Parent organization',
	'Class:Organization/Attribute:parent_name' => 'Parent name',
	'Class:Organization/Attribute:parent_name+' => 'Name of the parent organization',
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model~~',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name~~',
	'Class:Organization/Attribute:deliverymodel_name+' => '~~',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Parent~~',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Parent organization~~',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Location' => 'Localizzazione',
	'Class:Location+' => 'Qualsiasi tipo di localizzazione: Regione, Paese, Città, Sito, Edificio, Piano, Stanza, Rack,,...',
	'Class:Location/Attribute:name' => 'Nome',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Stato',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Attivo',
	'Class:Location/Attribute:status/Value:active+' => 'Attivo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Location/Attribute:org_id' => 'Organizzazione proprietaria',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nome dell\'organizzazione',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Indirizzo',
	'Class:Location/Attribute:address+' => 'Indirizzo postale',
	'Class:Location/Attribute:postal_code' => 'Codice avviamento postale',
	'Class:Location/Attribute:postal_code+' => 'CAP/codice avviamento postale',
	'Class:Location/Attribute:city' => 'Città',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Paese',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Devices~~',
	'Class:Location/Attribute:physicaldevice_list+' => 'All the devices in this location~~',
	'Class:Location/Attribute:person_list' => 'Contacts~~',
	'Class:Location/Attribute:person_list+' => 'All the contacts located on this location~~',
));

//
// Class: Contact
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Contact' => 'Contatto',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nome',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Stato',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Attivo',
	'Class:Contact/Attribute:status/Value:active+' => 'Attivo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Contact/Attribute:org_id' => 'Organizzazione',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organizzazione',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefono',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notification~~',
	'Class:Contact/Attribute:notify+' => '~~',
	'Class:Contact/Attribute:notify/Value:no' => 'no~~',
	'Class:Contact/Attribute:notify/Value:no+' => 'no~~',
	'Class:Contact/Attribute:notify/Value:yes' => 'yes~~',
	'Class:Contact/Attribute:notify/Value:yes+' => 'yes~~',
	'Class:Contact/Attribute:function' => 'Function~~',
	'Class:Contact/Attribute:function+' => '~~',
	'Class:Contact/Attribute:cis_list' => 'CIs~~',
	'Class:Contact/Attribute:cis_list+' => 'All the configuration items linked to this contact~~',
	'Class:Contact/Attribute:finalclass' => 'Tipo',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Person' => 'Persona',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Last Name~~',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => 'Nome',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Employee number~~',
	'Class:Person/Attribute:employee_number+' => '~~',
	'Class:Person/Attribute:mobile_phone' => 'Mobile phone~~',
	'Class:Person/Attribute:mobile_phone+' => '~~',
	'Class:Person/Attribute:location_id' => 'Location~~',
	'Class:Person/Attribute:location_id+' => '~~',
	'Class:Person/Attribute:location_name' => 'Location name~~',
	'Class:Person/Attribute:location_name+' => '~~',
	'Class:Person/Attribute:manager_id' => 'Manager~~',
	'Class:Person/Attribute:manager_id+' => '~~',
	'Class:Person/Attribute:manager_name' => 'Manager name~~',
	'Class:Person/Attribute:manager_name+' => '~~',
	'Class:Person/Attribute:team_list' => 'Teams~~',
	'Class:Person/Attribute:team_list+' => 'All the teams this person belongs to~~',
	'Class:Person/Attribute:tickets_list' => 'Tickets~~',
	'Class:Person/Attribute:tickets_list+' => 'All the tickets this person is the caller~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager friendly name~~',
	'Class:Person/Attribute:manager_id_friendlyname+' => '~~',
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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Team' => 'Squadra',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Members~~',
	'Class:Team/Attribute:persons_list+' => 'All the people belonging to this team~~',
	'Class:Team/Attribute:tickets_list' => 'Tickets~~',
	'Class:Team/Attribute:tickets_list+' => 'All the tickets assigned to this team~~',
));

//
// Class: Document
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nome',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organizzazione',
	'Class:Document/Attribute:org_id+' => '~~',
	'Class:Document/Attribute:org_name' => 'Nome dell\'organizzazione',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Document type~~',
	'Class:Document/Attribute:documenttype_id+' => '~~',
	'Class:Document/Attribute:documenttype_name' => 'Document type name~~',
	'Class:Document/Attribute:documenttype_name+' => '~~',
	'Class:Document/Attribute:version' => 'Version~~',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => 'Descrizione',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Stato',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Draft',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Pubblicato',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs~~',
	'Class:Document/Attribute:cis_list+' => 'All the configuration items linked to this document~~',
	'Class:Document/Attribute:finalclass' => 'Document Type~~',
	'Class:Document/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: DocumentFile
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DocumentFile' => 'Document File~~',
	'Class:DocumentFile+' => '~~',
	'Class:DocumentFile/Attribute:file' => 'File~~',
	'Class:DocumentFile/Attribute:file+' => '~~',
));

//
// Class: DocumentNote
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DocumentNote' => 'Document Note~~',
	'Class:DocumentNote+' => '~~',
	'Class:DocumentNote/Attribute:text' => 'Text~~',
	'Class:DocumentNote/Attribute:text+' => '~~',
));

//
// Class: DocumentWeb
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DocumentWeb' => 'Document Web~~',
	'Class:DocumentWeb+' => '~~',
	'Class:DocumentWeb/Attribute:url' => 'URL~~',
	'Class:DocumentWeb/Attribute:url+' => '~~',
));

//
// Class: Typology
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Typology' => 'Typology~~',
	'Class:Typology+' => '~~',
	'Class:Typology/Attribute:name' => 'Name~~',
	'Class:Typology/Attribute:name+' => '~~',
	'Class:Typology/Attribute:finalclass' => 'Type~~',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: DocumentType
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DocumentType' => 'Document Type~~',
	'Class:DocumentType+' => '~~',
));

//
// Class: ContactType
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ContactType' => 'Contact Type~~',
	'Class:ContactType+' => '~~',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkPersonToTeam' => 'Link Person / Team~~',
	'Class:lnkPersonToTeam+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team~~',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Team name~~',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person~~',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Person name~~',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Role~~',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Role name~~',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '~~',
));

//
// Application Menu
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:DataAdministration' => 'Dati di amministrazione',
	'Menu:DataAdministration+' => '',
	'Menu:Catalogs' => 'Cataloghi',
	'Menu:Catalogs+' => 'Tipi di dato',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'Importazione CSV',
	'Menu:CSVImport+' => '',
	'Menu:Organization' => 'Organizzazioni',
	'Menu:Organization+' => 'Tutte le organizzazioni',
	'Menu:ConfigManagement' => 'Gestione delle Configurazioni',
	'Menu:ConfigManagement+' => 'Gestione delle Configurazioni',
	'Menu:ConfigManagementCI' => 'Elementi di Configurazione (CI)',
	'Menu:ConfigManagementCI+' => 'Elementi di Configurazione (CI)',
	'Menu:ConfigManagementOverview' => 'Panoramica',
	'Menu:ConfigManagementOverview+' => 'Panoramica',
	'Menu:Contact' => 'Contatti',
	'Menu:Contact+' => 'Contatti',
	'Menu:Contact:Count' => '%1$d contatti',
	'Menu:Person' => 'Persone',
	'Menu:Person+' => 'Tutte le persone',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'Tutti i Teams',
	'Menu:Document' => 'Documenti',
	'Menu:Document+' => 'Tutti i Documenti',
	'Menu:Location' => 'Posizioni',
	'Menu:Location+' => 'Tutte le pozisioni',
	'Menu:NewContact' => 'Nuovo Contatto',
	'Menu:NewContact+' => 'Nuovo Contatto',
	'Menu:SearchContacts' => 'Ricerca contatti',
	'Menu:SearchContacts+' => 'Ricerca contatti',
	'Menu:ConfigManagement:Shortcuts' => 'Scorciatoie',
	'Menu:ConfigManagement:AllContacts' => 'Tutti i contatti: %1$d',
	'Menu:Typology' => 'Typology configuration~~',
	'Menu:Typology+' => 'Typology configuration~~',
	'UI_WelcomeMenu_AllConfigItems' => 'Summary~~',
	'Menu:ConfigManagement:Typology' => 'Typology configuration~~',
));

// Add translation for Fieldsets

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Person:info' => 'General information~~',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Notification~~',
));

// Themes
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'theme:fullmoon' => 'Full moon~~',
	'theme:test-red' => 'Test instance (Red)~~',
));

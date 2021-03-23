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
 * @author  Benjamin Planque <benjamin.planque@combodo.com>
 * @author	LinProfs <info@linprofs.com>
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author Hipska (2018, 2019)
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Organization' => 'Organisatie',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Naam',
	'Class:Organization/Attribute:name+' => 'Gekende naam voor de organisatie',
	'Class:Organization/Attribute:code' => 'Code',
	'Class:Organization/Attribute:code+' => 'Code voor de organisatie',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Actief',
	'Class:Organization/Attribute:status/Value:active+' => 'Actief',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactief',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactief',
	'Class:Organization/Attribute:parent_id' => 'Hoofdorganisatie',
	'Class:Organization/Attribute:parent_id+' => 'Hoofdorganisatie',
	'Class:Organization/Attribute:parent_name' => 'Naam hoofdorganisatie',
	'Class:Organization/Attribute:parent_name+' => 'Naam van de hoofdorganisatie',
	'Class:Organization/Attribute:deliverymodel_id' => 'Leveringsmodel',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Naam leveringsmodel',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Hoofdorganisatie',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Hoofdorganisatie',
	'Class:Organization/Attribute:overview' => 'Overzicht',
	'Organization:Overview:FunctionalCIs' => 'Configuratie-items van deze organisatie',
	'Organization:Overview:FunctionalCIs:subtitle' => 'per type',
	'Organization:Overview:Users' => 'iTop-gebruikers in deze organisatie',
));

//
// Class: Location
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Location' => 'Locatie',
	'Class:Location+' => 'Een locatie zoals: land, regio, gemeente/stad, gebouw, verdieping, kamer, ...',
	'Class:Location/Attribute:name' => 'Naam',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Actief',
	'Class:Location/Attribute:status/Value:active+' => 'Actief',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactief',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inactief',
	'Class:Location/Attribute:org_id' => 'Organisatie',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Naam organisatie',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adres',
	'Class:Location/Attribute:address+' => 'Adres van de organisatie',
	'Class:Location/Attribute:postal_code' => 'Postcode',
	'Class:Location/Attribute:postal_code+' => 'Postcode van de organisatie',
	'Class:Location/Attribute:city' => 'Gemeente',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Apparaten',
	'Class:Location/Attribute:physicaldevice_list+' => 'Alle apparaten die zich op deze locatie bevinden',
	'Class:Location/Attribute:person_list' => 'Contacten',
	'Class:Location/Attribute:person_list+' => 'Alle contacten die zich op deze locatie bevinden',
));

//
// Class: Contact
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Contact' => 'Contact',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Naam',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Actief',
	'Class:Contact/Attribute:status/Value:active+' => 'Actief',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactief',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inactief',
	'Class:Contact/Attribute:org_id' => 'Organisatie',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Naam organisatie',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'E-mailadres',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefoon',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Melding',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Nee',
	'Class:Contact/Attribute:notify/Value:no+' => 'Nee',
	'Class:Contact/Attribute:notify/Value:yes' => 'Ja',
	'Class:Contact/Attribute:notify/Value:yes+' => 'Ja',
	'Class:Contact/Attribute:function' => 'Functie',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CI\'s',
	'Class:Contact/Attribute:cis_list+' => 'Alle configuratie-items die gerelateerd zijn aan dit team',
	'Class:Contact/Attribute:finalclass' => 'Subklasse contact',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Person' => 'Persoon',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Achternaam',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Voornaam',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Identificatienummer',
	'Class:Person/Attribute:employee_number+' => 'Een uniek nummer om de persoon te identificeren (bv. rijksregister, burgerservicenummer, werknemernummer, ...)',
	'Class:Person/Attribute:mobile_phone' => 'Mobiele telefoon',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Locatie',
	'Class:Person/Attribute:location_id+' => 'Locatie waar de persoon gecontacteerd kan worden',
	'Class:Person/Attribute:location_name' => 'Naam locatie',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Naam manager',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Teams',
	'Class:Person/Attribute:team_list+' => 'Alle teams waarvan deze persoon lid is',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'Alle tickets waarvan deze persoon de aanvrager is',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Herkenbare naam manager',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Foto',
	'Class:Person/Attribute:picture+' => 'Foto van de contactpersoon',
	'Class:Person/UniquenessRule:employee_number+' => 'Het identificatienummer moet uniek zijn binnen de organisatie',
	'Class:Person/UniquenessRule:employee_number' => 'Er is al een persoon in de organisatie \'$this->org_name$\' met hetzelfde identificatienummer',
	'Class:Person/UniquenessRule:name+' => 'De naam moet uniek zijn binnen een organisatie',
	'Class:Person/UniquenessRule:name' => 'Er is al een persoon in de organisatie \'$this->org_name$\' met dezelfde naam',
));

//
// Class: Team
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Leden',
	'Class:Team/Attribute:persons_list+' => 'Alle personen die lid zijn van dit team',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => 'Alle tickets die toegewezen zijn aan dit team',
));

//
// Class: Document
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Naam',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisatie',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Naam organisatie',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Soort document',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Naam van het soort document',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Versie',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Omschrijving',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Concept',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Verouderd',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Gepubliceerd',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CI\'s',
	'Class:Document/Attribute:cis_list+' => 'Alle configuratie-items gerelateerd aan dit document',
	'Class:Document/Attribute:finalclass' => 'Subklasse document',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentFile' => 'Document: Bestand',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Bestand',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentNote' => 'Document: Notitie',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Tekst',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentWeb' => 'Document: Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'Link (URL)',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Naam',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Subklasse typologie',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentType' => 'Soort Document',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ContactType' => 'Soort Contact',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkPersonToTeam' => 'Link Persoon / Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Naam team',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Persoon',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Naam persoon',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Naam rol',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:DataAdministration' => 'Databeheer',
	'Menu:DataAdministration+' => 'Databeheer',
	'Menu:Catalogs' => 'Catalogus',
	'Menu:Catalogs+' => 'Soorten data',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV-import',
	'Menu:CSVImport+' => 'Grootschalige aanmaak of update',
	'Menu:Organization' => 'Organisaties',
	'Menu:Organization+' => 'Alle organisaties',
	'Menu:ConfigManagement' => 'Configuratiebeheer',
	'Menu:ConfigManagement+' => 'Configuratiebeheer',
	'Menu:ConfigManagementCI' => 'Configuratie-items',
	'Menu:ConfigManagementCI+' => 'Configuratie-items',
	'Menu:ConfigManagementOverview' => 'Overzicht',
	'Menu:ConfigManagementOverview+' => 'Overzicht',
	'Menu:Contact' => 'Contacten',
	'Menu:Contact+' => 'Contacten',
	'Menu:Contact:Count' => '%1$d contacten',
	'Menu:Person' => 'Personen',
	'Menu:Person+' => 'Alle personen',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'Alle teams',
	'Menu:Document' => 'Documenten',
	'Menu:Document+' => 'Alle documenten',
	'Menu:Location' => 'Locaties',
	'Menu:Location+' => 'Alle locaties',
	'Menu:NewContact' => 'Nieuw contact',
	'Menu:NewContact+' => 'Maak een nieuw contact aan',
	'Menu:SearchContacts' => 'Zoek naar contacten',
	'Menu:SearchContacts+' => 'Zoek naar contacten',
	'Menu:ConfigManagement:Shortcuts' => 'Snelkoppelingen',
	'Menu:ConfigManagement:AllContacts' => 'Alle contacten: %1$d',
	'Menu:Typology' => 'Configuratie typologie',
	'Menu:Typology+' => 'Configuratie van de typologie',
	'UI_WelcomeMenu_AllConfigItems' => 'Samenvatting',
	'Menu:ConfigManagement:Typology' => 'Configuratie typologie',
));

// Add translation for Fieldsets

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Person:info' => 'Globale informatie',
	'UserLocal:info' => 'Globale informatie',
	'Person:personal_info' => 'Persoonlijke informatie',
	'Person:notifiy' => 'Notificeer',
));

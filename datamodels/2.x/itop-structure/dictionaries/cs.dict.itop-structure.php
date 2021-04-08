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
 * Localized data.
 *
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Organization' => 'Organizace',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Název',
	'Class:Organization/Attribute:name+' => '',
	'Class:Organization/Attribute:code' => 'Kód',
	'Class:Organization/Attribute:code+' => 'Kód organizace (IČO, DIČO,...)',
	'Class:Organization/Attribute:status' => 'Stav',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktivní',
	'Class:Organization/Attribute:status/Value:active+' => '',
	'Class:Organization/Attribute:status/Value:inactive' => 'Neaktivní',
	'Class:Organization/Attribute:status/Value:inactive+' => '',
	'Class:Organization/Attribute:parent_id' => 'Mateřská organizace',
	'Class:Organization/Attribute:parent_id+' => '',
	'Class:Organization/Attribute:parent_name' => 'Název mateřské organizace',
	'Class:Organization/Attribute:parent_name+' => '',
	'Class:Organization/Attribute:deliverymodel_id' => 'Model poskytování služeb',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Název modelu poskytování služeb',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Mateřská organizace',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Location' => 'Umístění',
	'Class:Location+' => 'Jakékoli umístění: země, okres, město, čtvrť, budova, patro, místnost, rack,...',
	'Class:Location/Attribute:name' => 'Název',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Stav',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktivní',
	'Class:Location/Attribute:status/Value:active+' => '',
	'Class:Location/Attribute:status/Value:inactive' => 'Neaktivní',
	'Class:Location/Attribute:status/Value:inactive+' => '',
	'Class:Location/Attribute:org_id' => 'Vlastník (Organizace)',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Vlastník (Organizace)',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresa',
	'Class:Location/Attribute:address+' => '',
	'Class:Location/Attribute:postal_code' => 'PSČ',
	'Class:Location/Attribute:postal_code+' => 'Poštovní směrovací číslo',
	'Class:Location/Attribute:city' => 'Město',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Země',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Zařízení',
	'Class:Location/Attribute:physicaldevice_list+' => 'Všechna zařízení v tomto umístění',
	'Class:Location/Attribute:person_list' => 'Kontakty',
	'Class:Location/Attribute:person_list+' => 'Všechny kontakty v tomto umístění',
));

//
// Class: Contact
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Název',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Stav',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktivní',
	'Class:Contact/Attribute:status/Value:active+' => '',
	'Class:Contact/Attribute:status/Value:inactive' => 'Neaktivní',
	'Class:Contact/Attribute:status/Value:inactive+' => '',
	'Class:Contact/Attribute:org_id' => 'Organizace',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Název organizace',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefon',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Upozornění',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'ne',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'ano',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Funkce',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'Konfigurační položky',
	'Class:Contact/Attribute:cis_list+' => 'Všechny konfigurační položky spojené s tímto kontaktem',
	'Class:Contact/Attribute:finalclass' => 'Typ kontaktu',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Person' => 'Osoba',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Příjmení',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Jméno',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Osobní číslo',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobilní telefon',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Umístění',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Umístění',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Vedoucí',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Vedoucí',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Týmy',
	'Class:Person/Attribute:team_list+' => 'Všechny týmy, kterých je tato osoba členem',
	'Class:Person/Attribute:tickets_list' => 'Tikety',
	'Class:Person/Attribute:tickets_list+' => 'Všechny tikety, které tato osoba zadala',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Popisný název vedoucího',
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

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Team' => 'Tým',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Členové',
	'Class:Team/Attribute:persons_list+' => 'Všichni členové týmu',
	'Class:Team/Attribute:tickets_list' => 'Tikety',
	'Class:Team/Attribute:tickets_list+' => 'Všechny tikety přidělené tomuto týmu',
));

//
// Class: Document
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Název',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organizace',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Název organizace',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Typ dokumentu',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Název typu dokumentu',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Verze',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Popis',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Stav',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Návrh',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Zastaralý',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publikovaný',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'Konfigurační položky',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Typ dokumentu',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:DocumentFile' => 'Dokument (soubor)',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Soubor',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:DocumentNote' => 'Dokument (poznámka)',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Poznámka',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:DocumentWeb' => 'Dokument (web)',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Název',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Typ',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:DocumentType' => 'Typ dokumentu',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:ContactType' => 'Typ kontaktu',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:lnkPersonToTeam' => 'Spojení (Osoba / Tým)',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Tým',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Název týmu',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Osoba',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Název osoby',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Role',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Název role',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Menu:DataAdministration' => 'Správa dat',
	'Menu:DataAdministration+' => 'Správa dat',
	'Menu:Catalogs' => 'Katalogy',
	'Menu:Catalogs+' => 'Datové typy',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV import',
	'Menu:CSVImport+' => 'Hromadné vytvoření nebo aktualizace',
	'Menu:Organization' => 'Organizace',
	'Menu:Organization+' => 'Všechny organizace',
	'Menu:ConfigManagement' => 'Správa konfigurací',
	'Menu:ConfigManagement+' => 'Správa konfigurací',
	'Menu:ConfigManagementCI' => 'Konfigurační položky',
	'Menu:ConfigManagementCI+' => 'Konfigurační položky',
	'Menu:ConfigManagementOverview' => 'Přehled',
	'Menu:ConfigManagementOverview+' => 'Přehled',
	'Menu:Contact' => 'Kontakty',
	'Menu:Contact+' => 'Kontakty',
	'Menu:Contact:Count' => '%1$d kontaktů',
	'Menu:Person' => 'Osoby',
	'Menu:Person+' => 'Všechny osoby',
	'Menu:Team' => 'Týmy',
	'Menu:Team+' => 'Všechny týmy',
	'Menu:Document' => 'Dokumenty',
	'Menu:Document+' => 'Všechny dokumenty',
	'Menu:Location' => 'Umístění',
	'Menu:Location+' => 'Všechna umístění',
	'Menu:NewContact' => 'Nový kontakt',
	'Menu:NewContact+' => 'Nový kontakt',
	'Menu:SearchContacts' => 'Hledat kontakty',
	'Menu:SearchContacts+' => 'Hledat kontakty',
	'Menu:ConfigManagement:Shortcuts' => 'Odkazy',
	'Menu:ConfigManagement:AllContacts' => 'Všechny kontakty: %1$d',
	'Menu:Typology' => 'Typologie',
	'Menu:Typology+' => 'Konfigurace typologie',
	'UI_WelcomeMenu_AllConfigItems' => 'Souhrn',
	'Menu:ConfigManagement:Typology' => 'Konfigurace typologie',
));

// Add translation for Fieldsets

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Person:info' => 'Obecné informace',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Upozornění',
));

// Themes
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'theme:fullmoon' => 'Full moon 🌕~~',
	'theme:test-red' => 'Test instance (Red)~~',
));

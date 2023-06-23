<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Organization' => 'Szevezeti egység',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Név',
	'Class:Organization/Attribute:name+' => '',
	'Class:Organization/Attribute:code' => 'Azonosító',
	'Class:Organization/Attribute:code+' => '',
	'Class:Organization/Attribute:status' => 'Állapot',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktív',
	'Class:Organization/Attribute:status/Value:active+' => '',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:Organization/Attribute:status/Value:inactive+' => '',
	'Class:Organization/Attribute:parent_id' => 'Fölérendelt szervezeti egység',
	'Class:Organization/Attribute:parent_id+' => '',
	'Class:Organization/Attribute:parent_name' => 'Fölérendelt szervezeti egység név',
	'Class:Organization/Attribute:parent_name+' => '',
	'Class:Organization/Attribute:deliverymodel_id' => 'Teljesítési modell',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Teljesítési modell név',
	'Class:Organization/Attribute:deliverymodel_name+' => '~~',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Szülő',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Szülő szervezeti egység',
	'Class:Organization/Attribute:overview' => 'Áttekintő',
	'Organization:Overview:FunctionalCIs' => 'A szervezet konfigurációs elemei',
	'Organization:Overview:FunctionalCIs:subtitle' => 'típus szerint',
	'Organization:Overview:Users' => ITOP_APPLICATION_SHORT.' szervezeten belüli felhasználók',
));

//
// Class: Location
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Location' => 'Helyszín',
	'Class:Location+' => '',
	'Class:Location/Attribute:name' => 'Név',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Állapot',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktív',
	'Class:Location/Attribute:status/Value:active+' => '',
	'Class:Location/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:Location/Attribute:status/Value:inactive+' => '',
	'Class:Location/Attribute:org_id' => 'Szervezeti egység',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Szervezeti egység név',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Cím',
	'Class:Location/Attribute:address+' => '',
	'Class:Location/Attribute:postal_code' => 'Irányítószám',
	'Class:Location/Attribute:postal_code+' => '',
	'Class:Location/Attribute:city' => 'Város',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Ország',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Eszközök',
	'Class:Location/Attribute:physicaldevice_list+' => 'A helyszín összes eszköze',
	'Class:Location/Attribute:person_list' => 'Kapcsolattartók',
	'Class:Location/Attribute:person_list+' => 'A helyszín összes kapcsolattartója',
));

//
// Class: Contact
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Contact' => 'Kapcsolattartó',
	'Class:Contact+' => '',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Contact/Attribute:name' => 'Név',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Állapot',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktív',
	'Class:Contact/Attribute:status/Value:active+' => '',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:Contact/Attribute:status/Value:inactive+' => '',
	'Class:Contact/Attribute:org_id' => 'Szervezeti egység',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Szervezeti egység név',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefonszám',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Értesítés',
	'Class:Contact/Attribute:notify+' => '~~',
	'Class:Contact/Attribute:notify/Value:no' => 'Nem',
	'Class:Contact/Attribute:notify/Value:no+' => 'nem',
	'Class:Contact/Attribute:notify/Value:yes' => 'Igen',
	'Class:Contact/Attribute:notify/Value:yes+' => 'igen',
	'Class:Contact/Attribute:function' => 'Funkció',
	'Class:Contact/Attribute:function+' => '~~',
	'Class:Contact/Attribute:cis_list' => 'CI-k',
	'Class:Contact/Attribute:cis_list+' => 'A kapcsolattartóhoz tartozó összes konfigurációs elem',
	'Class:Contact/Attribute:finalclass' => 'Típus',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Person' => 'Kapcsolattartó',
	'Class:Person+' => '',
	'Class:Person/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Person/Attribute:name' => 'Vezetéknév',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => 'Keresztnév',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Alkalmazotti szám',
	'Class:Person/Attribute:employee_number+' => '~~',
	'Class:Person/Attribute:mobile_phone' => 'Mobiltelefon',
	'Class:Person/Attribute:mobile_phone+' => '~~',
	'Class:Person/Attribute:location_id' => 'Helyszín',
	'Class:Person/Attribute:location_id+' => '~~',
	'Class:Person/Attribute:location_name' => 'Helyszín név',
	'Class:Person/Attribute:location_name+' => '~~',
	'Class:Person/Attribute:manager_id' => 'Felettes',
	'Class:Person/Attribute:manager_id+' => '~~',
	'Class:Person/Attribute:manager_name' => 'Felettes neve',
	'Class:Person/Attribute:manager_name+' => '~~',
	'Class:Person/Attribute:team_list' => 'Csapatok',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'Hibajegyek',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:user_list' => 'Users~~',
	'Class:Person/Attribute:user_list+' => 'All the Users associated to this person~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Felettes rövid neve',
	'Class:Person/Attribute:manager_id_friendlyname+' => '~~',
	'Class:Person/Attribute:picture' => 'Kép',
	'Class:Person/Attribute:picture+' => '~~',
	'Class:Person/UniquenessRule:employee_number+' => 'A szervezeten belül az alkalmazotti számnak egyedinek kell lennie',
	'Class:Person/UniquenessRule:employee_number' => 'már van egy személy a \'$this->this->org_name$\' szervezetben ugyanezzel az alkalmazotti számmal',
	'Class:Person/UniquenessRule:name+' => 'Az alkalmazott nevének egyedinek kell lennie a szervezeten belül',
	'Class:Person/UniquenessRule:name' => 'A \'$this->org_name$\' szervezetben már van egy ugyanilyen nevű személy.',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible to move this person under organization \'%1$s\' as it would break his access to the User Portal, his associated user not being allowed on this organization~~',
));

//
// Class: Team
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Team' => 'Csapat',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Team/Attribute:persons_list' => 'Tagok',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'Hibajegyek',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Document' => 'Dokumentum',
	'Class:Document+' => '',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Document/Attribute:name' => 'Név',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Szervezeti egység',
	'Class:Document/Attribute:org_id+' => '~~',
	'Class:Document/Attribute:org_name' => 'Szervezeti egység név',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Dokumentum típus',
	'Class:Document/Attribute:documenttype_id+' => '~~',
	'Class:Document/Attribute:documenttype_name' => 'Dokumentum típus név',
	'Class:Document/Attribute:documenttype_name+' => '~~',
	'Class:Document/Attribute:version' => 'Verzió',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => 'Leírás',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Állapot',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Munkapéldány',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Érvényes',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CI-k',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Dokumentum típus',
	'Class:Document/Attribute:finalclass+' => 'A végső osztály neve',
));

//
// Class: DocumentFile
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DocumentFile' => 'Dokumentum fájl',
	'Class:DocumentFile+' => '~~',
	'Class:DocumentFile/Attribute:file' => 'Fájl',
	'Class:DocumentFile/Attribute:file+' => '~~',
));

//
// Class: DocumentNote
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DocumentNote' => 'Dokumentum jegyzet',
	'Class:DocumentNote+' => '~~',
	'Class:DocumentNote/Attribute:text' => 'Szöveg',
	'Class:DocumentNote/Attribute:text+' => '~~',
));

//
// Class: DocumentWeb
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DocumentWeb' => 'Webdokumentum',
	'Class:DocumentWeb+' => '~~',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Typology' => 'Tipológia',
	'Class:Typology+' => '~~',
	'Class:Typology/Attribute:name' => 'Név',
	'Class:Typology/Attribute:name+' => '~~',
	'Class:Typology/Attribute:finalclass' => 'Típus',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DocumentType' => 'Dokumentum típus',
	'Class:DocumentType+' => '~~',
));

//
// Class: ContactType
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ContactType' => 'Kapcsolattartó típus',
	'Class:ContactType+' => '~~',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkPersonToTeam' => 'Kapcsolattartó / Csapat',
	'Class:lnkPersonToTeam+' => '~~',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s~~',
	'Class:lnkPersonToTeam/Name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Csapat',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Csapat név',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Kapcsolattartó',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Kapcsolattartó név',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Beosztás',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Beosztás név',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '~~',
));

//
// Application Menu
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:DataAdministration' => 'Adat adminisztráció',
	'Menu:DataAdministration+' => '',
	'Menu:Catalogs' => 'Katalógusok',
	'Menu:Catalogs+' => '',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => '',
	'Menu:CSVImport' => 'CSV import',
	'Menu:CSVImport+' => '',
	'Menu:Organization' => 'Szervezeti egység',
	'Menu:Organization+' => '',
	'Menu:ConfigManagement' => 'Konfigurációkezelés',
	'Menu:ConfigManagement+' => '',
	'Menu:ConfigManagementCI' => 'Konfigurációs elemek (CI)',
	'Menu:ConfigManagementCI+' => '',
	'Menu:ConfigManagementOverview' => 'Áttekintő',
	'Menu:ConfigManagementOverview+' => '',
	'Menu:Contact' => 'Kapcsolattartók',
	'Menu:Contact+' => '',
	'Menu:Contact:Count' => '%1$d',
	'Menu:Person' => 'Kapcsolattartók',
	'Menu:Person+' => '',
	'Menu:Team' => 'Csapatok',
	'Menu:Team+' => '',
	'Menu:Document' => 'Dokumentumok',
	'Menu:Document+' => '',
	'Menu:Location' => 'Helyszínek',
	'Menu:Location+' => '',
	'Menu:NewContact' => 'Új kapcsolattartó',
	'Menu:NewContact+' => '',
	'Menu:SearchContacts' => 'Kapcsolattartó keresés',
	'Menu:SearchContacts+' => '',
	'Menu:ConfigManagement:Shortcuts' => 'Gyorsgombok',
	'Menu:ConfigManagement:AllContacts' => 'Összes kapcsolattartó: %1$d',
	'Menu:Typology' => 'Tipológia konfiguráció',
	'Menu:Typology+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Összegzés',
	'Menu:ConfigManagement:Typology' => 'Tipológia konfiguráció',
));

// Add translation for Fieldsets

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Person:info' => 'Általános információk',
	'User:info' => 'Általános információk',
	'User:profiles' => 'Profiles (minimum one)~~',
	'Person:personal_info' => 'Személyes információk',
	'Person:notifiy' => 'Értesítés',
));

// Themes
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'theme:fullmoon' => 'Full moon',
	'theme:test-red' => 'Tesztpéldány (Red)',
));

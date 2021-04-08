<?php
/**
 * Localized data
 * @author Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Organization' => 'Organizácia',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Názov',
	'Class:Organization/Attribute:name+' => '',
	'Class:Organization/Attribute:code' => 'Kód',
	'Class:Organization/Attribute:code+' => '',
	'Class:Organization/Attribute:status' => 'Stav',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktívna',
	'Class:Organization/Attribute:status/Value:active+' => '',
	'Class:Organization/Attribute:status/Value:inactive' => 'Neaktívna',
	'Class:Organization/Attribute:status/Value:inactive+' => '',
	'Class:Organization/Attribute:parent_id' => 'Nadradená organizácia',
	'Class:Organization/Attribute:parent_id+' => '',
	'Class:Organization/Attribute:parent_name' => 'Nadradená organizácia',
	'Class:Organization/Attribute:parent_name+' => '',
	'Class:Organization/Attribute:deliverymodel_id' => 'Model dodávky',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Názov modelu dodávky',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Nadradená organizácia',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Location' => 'Poloha',
	'Class:Location+' => '',
	'Class:Location/Attribute:name' => 'Názov',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Stav',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktívna',
	'Class:Location/Attribute:status/Value:active+' => '',
	'Class:Location/Attribute:status/Value:inactive' => 'Neaktívna',
	'Class:Location/Attribute:status/Value:inactive+' => '',
	'Class:Location/Attribute:org_id' => 'Organizácia vlastníka',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Názov organizácie vlastníka',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresa',
	'Class:Location/Attribute:address+' => '',
	'Class:Location/Attribute:postal_code' => 'PSČ',
	'Class:Location/Attribute:postal_code+' => '',
	'Class:Location/Attribute:city' => 'Mesto',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Štát',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Zariadenia',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => 'Kontakty',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Meno',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Stav',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktívny',
	'Class:Contact/Attribute:status/Value:active+' => '',
	'Class:Contact/Attribute:status/Value:inactive' => 'Neaktívny',
	'Class:Contact/Attribute:status/Value:inactive+' => '',
	'Class:Contact/Attribute:org_id' => 'Organizácia',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Názov Organizácie',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefón',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Upozornenie',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'nie',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'áno',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Funkcia',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'Zariadenia',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => 'Typ kontaktu',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Person' => 'Osoba',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Priezvisko',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Krstné meno',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Číslo zamestnanca',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Telefónne číslo',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Poloha',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Názov lokality',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manažér',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Meno manažéra',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Tímy',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'Tickety',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Ľahko čitateľné meno manažéra',
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

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Team' => 'Tím',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Osoby',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'Tickety',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Názov',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organizácia',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Názov Organizácie',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Typ dokumentu',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Názov typu dokumentu',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version~~',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => 'Popis',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Stav',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Návrh',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Vyradený',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publikovaný',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'Komponenty',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Typ dokumentu',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DocumentFile' => 'Dokumentový súbor',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Súbor',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DocumentNote' => 'Poznámka dokumentu',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Text',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DocumentWeb' => 'Web stránka dokumentu',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Typology' => 'Typológia',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Názov',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Typ',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DocumentType' => 'Typ dokumentu',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ContactType' => 'Typ kontaktu',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkPersonToTeam' => 'väzba - Osoba / Tím',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Tím',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Názov tímu',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Osoba',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Meno osoby',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rola',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Názov role',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Menu:DataAdministration' => 'Dátová administrácia',
	'Menu:DataAdministration+' => '',
	'Menu:Catalogs' => 'Katalógy',
	'Menu:Catalogs+' => '',
	'Menu:Audit' => 'Audity',
	'Menu:Audit+' => '',
	'Menu:CSVImport' => 'CSV import~~',
	'Menu:CSVImport+' => 'Bulk creation or update~~',
	'Menu:Organization' => 'Organizácia',
	'Menu:Organization+' => '',
	'Menu:ConfigManagement' => 'Manažment konfigurácie',
	'Menu:ConfigManagement+' => '',
	'Menu:ConfigManagementCI' => 'Konfiguračné položky',
	'Menu:ConfigManagementCI+' => '',
	'Menu:ConfigManagementOverview' => 'Prehľad',
	'Menu:ConfigManagementOverview+' => '',
	'Menu:Contact' => 'Kontakty',
	'Menu:Contact+' => '',
	'Menu:Contact:Count' => '%1$d kontakt/y/ov',
	'Menu:Person' => 'Osoby',
	'Menu:Person+' => '',
	'Menu:Team' => 'Tímy',
	'Menu:Team+' => '',
	'Menu:Document' => 'Dokumenty',
	'Menu:Document+' => '',
	'Menu:Location' => 'Poloha',
	'Menu:Location+' => '',
	'Menu:NewContact' => 'Nový kontakt',
	'Menu:NewContact+' => '',
	'Menu:SearchContacts' => 'Vyhľadať kontakty',
	'Menu:SearchContacts+' => '',
	'Menu:ConfigManagement:Shortcuts' => 'Skratky',
	'Menu:ConfigManagement:AllContacts' => 'Všetky kontakty: %1$d',
	'Menu:Typology' => 'Konfiguračná typológia',
	'Menu:Typology+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Zhrnutie',
	'Menu:ConfigManagement:Typology' => 'Konfiguračná typológia',
));

// Add translation for Fieldsets

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Person:info' => 'Všeobecné informácie',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Upozornenie',
));

// Themes
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'theme:fullmoon' => 'Full moon 🌕~~',
	'theme:test-red' => 'Test instance (Red)~~',
));

<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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
// Dictionnary conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+
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
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Organization' => 'Organizacja',
	'Class:Organization+' => '~~',
	'Class:Organization/Attribute:name' => 'Nazwa',
	'Class:Organization/Attribute:name+' => 'Nazwa zwyczajowa',
	'Class:Organization/Attribute:code' => 'Kod',
	'Class:Organization/Attribute:code+' => 'Kod organizacji (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktywna',
	'Class:Organization/Attribute:status/Value:active+' => 'Aktywna',
	'Class:Organization/Attribute:status/Value:inactive' => 'Nieaktywna',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Nieaktywna',
	'Class:Organization/Attribute:parent_id' => 'Macierzysta',
	'Class:Organization/Attribute:parent_id+' => 'Organizacja macierzysta',
	'Class:Organization/Attribute:parent_name' => 'Nazwa o.mac.',
	'Class:Organization/Attribute:parent_name+' => 'Nazwa organizacji macierzystej',
	'Class:Organization/Attribute:deliverymodel_id' => 'Model obsługi',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nazwa modelu obsługi',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Macierzysta',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Organizacja macierzysta',
	'Class:Organization/Attribute:overview' => 'Przegląd',
	'Organization:Overview:FunctionalCIs' => 'Pozycje konfiguracji tej organizacji',
	'Organization:Overview:FunctionalCIs:subtitle' => 'według rodzaju',
	'Organization:Overview:Users' => '',
));

//
// Class: Location
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Location' => 'Lokalizacja',
	'Class:Location+' => 'Dowolny typ lokalizacji: region, kraj, miasto, teren, budynek, piętro, pokój, stojak,...',
	'Class:Location/Attribute:name' => 'Nazwa',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktywna',
	'Class:Location/Attribute:status/Value:active+' => 'Aktywna',
	'Class:Location/Attribute:status/Value:inactive' => 'Nieaktywna',
	'Class:Location/Attribute:status/Value:inactive+' => 'Nieaktywna',
	'Class:Location/Attribute:org_id' => 'Organizacja właścicielska',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nazwa organizacji właścicielskiej',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adres',
	'Class:Location/Attribute:address+' => 'Adres pocztowy',
	'Class:Location/Attribute:postal_code' => 'Kod pocztowy',
	'Class:Location/Attribute:postal_code+' => 'Kod pocztowy',
	'Class:Location/Attribute:city' => 'Miasto',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Kraj',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Urządzenia',
	'Class:Location/Attribute:physicaldevice_list+' => 'Wszystkie urządzenia w tej lokalizacji',
	'Class:Location/Attribute:person_list' => 'Kontakty',
	'Class:Location/Attribute:person_list+' => 'Wszystkie kontakty znajdujące się w tej lokalizacji',
));

//
// Class: Contact
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Contact/Attribute:name' => 'Nazwa',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktywny',
	'Class:Contact/Attribute:status/Value:active+' => 'Aktywny',
	'Class:Contact/Attribute:status/Value:inactive' => 'Nieaktywny',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Nieaktywny',
	'Class:Contact/Attribute:org_id' => 'Organizacja',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Nazwa organizacji',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'E-mail',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefon',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Powiadomienie',
	'Class:Contact/Attribute:notify+' => 'Flaga, której może używać każde powiadomienie',
	'Class:Contact/Attribute:notify/Value:no' => 'nie',
	'Class:Contact/Attribute:notify/Value:no+' => 'nie',
	'Class:Contact/Attribute:notify/Value:yes' => 'tak',
	'Class:Contact/Attribute:notify/Value:yes+' => 'tak',
	'Class:Contact/Attribute:function' => 'Funkcja',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'Konfiguracje',
	'Class:Contact/Attribute:cis_list+' => 'Wszystkie elementy konfiguracji powiązane z tym kontaktem',
	'Class:Contact/Attribute:finalclass' => 'podklasa kontaktu',
	'Class:Contact/Attribute:finalclass+' => 'Nazwa klasy głównej',
));

//
// Class: Person
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Person' => 'Osoba',
	'Class:Person+' => '',
	'Class:Person/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Person/Attribute:name' => 'Nazwisko',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Imię',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Numer pracownika',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Tel. komórkowy',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Lokalizacja',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Nazwa lokalizacji',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Menedżer',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Nazwa menedżera',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Zespoły',
	'Class:Person/Attribute:team_list+' => 'Wszystkie zespoły, do których należy ta osoba',
	'Class:Person/Attribute:tickets_list' => 'Zgłoszenia',
	'Class:Person/Attribute:tickets_list+' => 'Wszystkie zgłoszenia, które ta osoba założyła',
	'Class:Person/Attribute:user_list' => 'Users~~',
	'Class:Person/Attribute:user_list+' => 'All the Users associated to this person~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Przyjazna nazwa menedżera',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Zdjęcie',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Numer pracownika musi być unikalny w organizacji',
	'Class:Person/UniquenessRule:employee_number' => 'W organizacji \'$this->org_name$\' istnieje już osoba o tym samym numerze pracownika',
	'Class:Person/UniquenessRule:name+' => 'Imię i nazwisko pracownika powinno być unikalne w jego organizacji',
	'Class:Person/UniquenessRule:name' => 'W organizacji \'$this->org_name$\' istnieje już osoba o takiej samej nazwie',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible to move this person under organization \'%1$s\' as it would break his access to the User Portal, his associated user not being allowed on this organization~~',
));

//
// Class: Team
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Team' => 'Zespół',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Team/Attribute:persons_list' => 'Członkowie',
	'Class:Team/Attribute:persons_list+' => 'Wszystkie osoby należące do tego zespołu',
	'Class:Team/Attribute:tickets_list' => 'Zgłoszenia',
	'Class:Team/Attribute:tickets_list+' => 'Wszystkie zgłoszenia przypisane do tego zespołu',
));

//
// Class: Document
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Document/Attribute:name' => 'Nazwa',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organizacja',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Nazwa organizacji',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Typ dokumentu',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Nazwa typu dokumentu',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Wersja',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Opis',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Szkic',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Wycofany',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Opublikowany',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'Konfiguracje',
	'Class:Document/Attribute:cis_list+' => 'Wszystkie elementy konfiguracji powiązane z tym dokumentem',
	'Class:Document/Attribute:finalclass' => 'Podklasa dokumentu',
	'Class:Document/Attribute:finalclass+' => 'Klasa główna dokumentu',
));

//
// Class: DocumentFile
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DocumentFile' => 'Plik dokumentu',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Plik',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DocumentNote' => 'Notatka do dokumentu',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Tekst',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DocumentWeb' => 'Dokument www',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Typology' => 'Typologia',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Nazwa',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Podklasa typologii',
	'Class:Typology/Attribute:finalclass+' => 'Klasa główna typologii',
));

//
// Class: DocumentType
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DocumentType' => 'Typ dokumentu',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ContactType' => 'Typ kontaktu',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkPersonToTeam' => 'Połączenie osoba / zespół',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s~~',
	'Class:lnkPersonToTeam/Name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Zespół',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Nazwa zespołu',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Osoba',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Nazwa osoby',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rola',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Nazwa roli',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:DataAdministration' => 'Administracja danymi',
	'Menu:DataAdministration+' => 'Administracja danymi',
	'Menu:Catalogs' => 'Katalogi',
	'Menu:Catalogs+' => 'Typy danych',
	'Menu:Audit' => 'Audyt',
	'Menu:Audit+' => 'Audyt',
	'Menu:CSVImport' => 'Import CSV',
	'Menu:CSVImport+' => 'Tworzenie lub aktualizacja zbiorcza',
	'Menu:Organization' => 'Organizacja',
	'Menu:Organization+' => 'Wszystkie organizacje',
	'Menu:ConfigManagement' => 'Zarządzanie konfiguracjami',
	'Menu:ConfigManagement+' => 'Zarządzanie konfiguracjami',
	'Menu:ConfigManagementCI' => 'Elementy konfiguracji',
	'Menu:ConfigManagementCI+' => 'Elementy konfiguracji',
	'Menu:ConfigManagementOverview' => 'Przegląd',
	'Menu:ConfigManagementOverview+' => 'Przegląd',
	'Menu:Contact' => 'Kontakty',
	'Menu:Contact+' => 'Kontakty',
	'Menu:Contact:Count' => 'Kontakty %1$d',
	'Menu:Person' => 'Osoby',
	'Menu:Person+' => 'Wszystkie osoby',
	'Menu:Team' => 'Zespoły',
	'Menu:Team+' => 'Wszystkie zespoły',
	'Menu:Document' => 'Dokumenty',
	'Menu:Document+' => 'Wszystkie dokumenty',
	'Menu:Location' => 'Lokalizacje',
	'Menu:Location+' => 'Wszystkie lokalizacje',
	'Menu:NewContact' => 'Nowy kontakt',
	'Menu:NewContact+' => 'Nowy kontakt',
	'Menu:SearchContacts' => 'Szukaj kontaktów',
	'Menu:SearchContacts+' => 'Szukaj kontaktów',
	'Menu:ConfigManagement:Shortcuts' => 'Skróty',
	'Menu:ConfigManagement:AllContacts' => 'Wszystkie kontakty: %1$d',
	'Menu:Typology' => 'Konfiguracja typologii',
	'Menu:Typology+' => 'Konfiguracja typologii',
	'UI_WelcomeMenu_AllConfigItems' => 'Podsumowanie',
	'Menu:ConfigManagement:Typology' => 'Konfiguracja typologii',
));

// Add translation for Fieldsets

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Person:info' => 'Informacje ogólne',
	'User:info' => 'Informacje ogólne',
	'User:profiles' => 'Profiles (minimum one)~~',
	'Person:personal_info' => 'Informacje osobiste',
	'Person:notifiy' => 'Powiadomienie',
));

// Themes
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'theme:fullmoon' => 'Pełnia księżyca',
	'theme:test-red' => 'Instancja testowa (czerwona)',
));

<?php

// Copyright (C) 2010-2012 Combodo SARL
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
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Relation:impacts/Description' => 'Prvky ovlivněné objektem',
    'Relation:impacts/DownStream' => 'Dopad na...',
    'Relation:impacts/UpStream' => 'Prvky ovlivněné objektem...',
    'Relation:depends on/Description' => 'Prvky na kterých závisí objekt',
    'Relation:depends on/DownStream' => 'Závislost na...',
    'Relation:depends on/UpStream' => 'Prvky na kterých tento objekt závisí...',
));

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

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
    'Class:Organization/Attribute:status/Value:inactive' => 'Pasivní',
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
    'Class:Location/Attribute:status/Value:inactive' => 'Pasivní',
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
    'Class:Contact/Attribute:status/Value:inactive' => 'Pasivní',
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
    'Class:Document/Attribute:contracts_list' => 'Smlouvy',
    'Class:Document/Attribute:contracts_list+' => '',
    'Class:Document/Attribute:services_list' => 'Služby',
    'Class:Document/Attribute:services_list+' => '',
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
// Class: FunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:FunctionalCI' => 'Funkční konfigurační položka',
    'Class:FunctionalCI+' => '',
    'Class:FunctionalCI/Attribute:name' => 'Název',
    'Class:FunctionalCI/Attribute:name+' => '',
    'Class:FunctionalCI/Attribute:description' => 'Popis',
    'Class:FunctionalCI/Attribute:description+' => '',
    'Class:FunctionalCI/Attribute:org_id' => 'Organizace',
    'Class:FunctionalCI/Attribute:org_id+' => '',
    'Class:FunctionalCI/Attribute:organization_name' => 'Název organizace',
    'Class:FunctionalCI/Attribute:organization_name+' => '',
    'Class:FunctionalCI/Attribute:business_criticity' => 'Dopad na obchod',
    'Class:FunctionalCI/Attribute:business_criticity+' => '',
    'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'vysoký',
    'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '',
    'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'nízký',
    'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '',
    'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'střední',
    'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '',
    'Class:FunctionalCI/Attribute:move2production' => 'Datum uvedení do produkce',
    'Class:FunctionalCI/Attribute:move2production+' => '',
    'Class:FunctionalCI/Attribute:contacts_list' => 'Kontakty',
    'Class:FunctionalCI/Attribute:contacts_list+' => '',
    'Class:FunctionalCI/Attribute:documents_list' => 'Dokumenty',
    'Class:FunctionalCI/Attribute:documents_list+' => '',
    'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Aplikační řešení',
    'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Všechna aplikační řešení závisející na této konfigurační položce',
    'Class:FunctionalCI/Attribute:providercontracts_list' => 'Smlouvy s poskytovateli',
    'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
    'Class:FunctionalCI/Attribute:services_list' => 'Služby',
    'Class:FunctionalCI/Attribute:services_list+' => '',
    'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
    'Class:FunctionalCI/Attribute:softwares_list+' => '',
    'Class:FunctionalCI/Attribute:tickets_list' => 'Tikety',
    'Class:FunctionalCI/Attribute:tickets_list+' => '',
    'Class:FunctionalCI/Attribute:finalclass' => 'Typ konfigurační položky',
    'Class:FunctionalCI/Attribute:finalclass+' => '',
    'Class:FunctionalCI/Tab:OpenedTickets' => 'Aktivní tikety',
));

//
// Class: PhysicalDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PhysicalDevice' => 'Fyzické zařízení',
    'Class:PhysicalDevice+' => '',
    'Class:PhysicalDevice/Attribute:serialnumber' => 'Sériové číslo',
    'Class:PhysicalDevice/Attribute:serialnumber+' => '',
    'Class:PhysicalDevice/Attribute:location_id' => 'Umístění',
    'Class:PhysicalDevice/Attribute:location_id+' => '',
    'Class:PhysicalDevice/Attribute:location_name' => 'Název umístění',
    'Class:PhysicalDevice/Attribute:location_name+' => '',
    'Class:PhysicalDevice/Attribute:status' => 'Stav',
    'Class:PhysicalDevice/Attribute:status+' => '',
    'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'implementace',
    'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
    'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'zastaralé',
    'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
    'Class:PhysicalDevice/Attribute:status/Value:production' => 'v produkci',
    'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
    'Class:PhysicalDevice/Attribute:status/Value:stock' => 'skladem/rezerva',
    'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
    'Class:PhysicalDevice/Attribute:brand_id' => 'Výrobce',
    'Class:PhysicalDevice/Attribute:brand_id+' => '',
    'Class:PhysicalDevice/Attribute:brand_name' => 'Název výrobce',
    'Class:PhysicalDevice/Attribute:brand_name+' => '',
    'Class:PhysicalDevice/Attribute:model_id' => 'Model',
    'Class:PhysicalDevice/Attribute:model_id+' => '',
    'Class:PhysicalDevice/Attribute:model_name' => 'Název modelu',
    'Class:PhysicalDevice/Attribute:model_name+' => '',
    'Class:PhysicalDevice/Attribute:asset_number' => 'Inventární číslo',
    'Class:PhysicalDevice/Attribute:asset_number+' => '',
    'Class:PhysicalDevice/Attribute:purchase_date' => 'Datum pořízení',
    'Class:PhysicalDevice/Attribute:purchase_date+' => '',
    'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Konec záruky',
    'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Rack' => 'Rack',
    'Class:Rack+' => '',
    'Class:Rack/Attribute:nb_u' => 'Velikost (U)',
    'Class:Rack/Attribute:nb_u+' => '',
    'Class:Rack/Attribute:device_list' => 'Zařízení',
    'Class:Rack/Attribute:device_list+' => '',
    'Class:Rack/Attribute:enclosure_list' => 'Šasi',
    'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TelephonyCI' => 'Konfigurační položka Telefonie',
    'Class:TelephonyCI+' => '',
    'Class:TelephonyCI/Attribute:phonenumber' => 'Telefonní číslo',
    'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Phone' => 'Telefon',
    'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:MobilePhone' => 'Mobilní telefon',
    'Class:MobilePhone+' => '',
    'Class:MobilePhone/Attribute:imei' => 'IMEI',
    'Class:MobilePhone/Attribute:imei+' => '',
    'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
    'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:IPPhone' => 'IP telefon',
    'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Tablet' => 'Tablet',
    'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ConnectableCI' => 'Připojitelná konfigurační položka',
    'Class:ConnectableCI+' => '',
    'Class:ConnectableCI/Attribute:networkdevice_list' => 'Síťové prvky',
    'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
    'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Síťová rozhraní',
    'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:DatacenterDevice' => 'Zařízení datového centra',
    'Class:DatacenterDevice+' => '',
    'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
    'Class:DatacenterDevice/Attribute:rack_id+' => '',
    'Class:DatacenterDevice/Attribute:rack_name' => 'Název racku',
    'Class:DatacenterDevice/Attribute:rack_name+' => '',
    'Class:DatacenterDevice/Attribute:enclosure_id' => 'Šasi',
    'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
    'Class:DatacenterDevice/Attribute:enclosure_name' => 'Název šasi',
    'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
    'Class:DatacenterDevice/Attribute:nb_u' => 'Velikost (U)',
    'Class:DatacenterDevice/Attribute:nb_u+' => 'Velikost/výška v jednotkách U',
    'Class:DatacenterDevice/Attribute:managementip' => 'IP pro správu',
    'Class:DatacenterDevice/Attribute:managementip+' => '',
    'Class:DatacenterDevice/Attribute:powerA_id' => 'Napájecí zdroj A',
    'Class:DatacenterDevice/Attribute:powerA_id+' => '',
    'Class:DatacenterDevice/Attribute:powerA_name' => 'Název napájecího zdroje A',
    'Class:DatacenterDevice/Attribute:powerA_name+' => '',
    'Class:DatacenterDevice/Attribute:powerB_id' => 'Napájecí zdroj B',
    'Class:DatacenterDevice/Attribute:powerB_id+' => '',
    'Class:DatacenterDevice/Attribute:powerB_name' => 'Název napájecího zdroje B',
    'Class:DatacenterDevice/Attribute:powerB_name+' => '',
    'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC porty',
    'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
    'Class:DatacenterDevice/Attribute:san_list' => 'SAN switche',
    'Class:DatacenterDevice/Attribute:san_list+' => '',
    'Class:DatacenterDevice/Attribute:redundancy' => 'Redundance',
    'Class:DatacenterDevice/Attribute:redundancy/count' => 'Zařízení je v provozu, pokud je funkční alespoň jeden zdroj',
    // Unused yet
    'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Zařízení je v provozu, pouze pokud jsou funknčí všechny zdroje',
    'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Zařízení je v provozu, pokud je alespoň %1$s %% zdrojů funkčních',
));

//
// Class: NetworkDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NetworkDevice' => 'Síťový prvek',
    'Class:NetworkDevice+' => '',
    'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Typ zařízení',
    'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
    'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Název typu zařízení',
    'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
    'Class:NetworkDevice/Attribute:connectablecis_list' => 'Zařízení',
    'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
    'Class:NetworkDevice/Attribute:iosversion_id' => 'Verze IOS',
    'Class:NetworkDevice/Attribute:iosversion_id+' => '',
    'Class:NetworkDevice/Attribute:iosversion_name' => 'Název verze IOS',
    'Class:NetworkDevice/Attribute:iosversion_name+' => '',
    'Class:NetworkDevice/Attribute:ram' => 'RAM',
    'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Server' => 'Server',
    'Class:Server+' => '',
    'Class:Server/Attribute:osfamily_id' => 'Rodina OS',
    'Class:Server/Attribute:osfamily_id+' => '',
    'Class:Server/Attribute:osfamily_name' => 'Název rodiny OS',
    'Class:Server/Attribute:osfamily_name+' => '',
    'Class:Server/Attribute:osversion_id' => 'Verze OS',
    'Class:Server/Attribute:osversion_id+' => '',
    'Class:Server/Attribute:osversion_name' => 'Název verze OS',
    'Class:Server/Attribute:osversion_name+' => '',
    'Class:Server/Attribute:oslicence_id' => 'Licence OS',
    'Class:Server/Attribute:oslicence_id+' => '',
    'Class:Server/Attribute:oslicence_name' => 'Název licence OS',
    'Class:Server/Attribute:oslicence_name+' => '',
    'Class:Server/Attribute:cpu' => 'CPU',
    'Class:Server/Attribute:cpu+' => '',
    'Class:Server/Attribute:ram' => 'RAM',
    'Class:Server/Attribute:ram+' => '',
    'Class:Server/Attribute:logicalvolumes_list' => 'Logické svazky',
    'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:StorageSystem' => 'Úložný systém',
    'Class:StorageSystem+' => '',
    'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logické svazky',
    'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Všechny logické svazky připojené k tomuto úložnému systému',
));

//
// Class: SANSwitch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SANSwitch' => 'SAN Switch',
    'Class:SANSwitch+' => '',
    'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Zařízení',
    'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Všechna zařízení připojená k tomuto SAN switchi',
));

//
// Class: TapeLibrary
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:TapeLibrary' => 'Pásková knihovna',
    'Class:TapeLibrary+' => '',
    'Class:TapeLibrary/Attribute:tapes_list' => 'Pásky',
    'Class:TapeLibrary/Attribute:tapes_list+' => 'Všechny pásky v této páskové knihovně',
));

//
// Class: NAS
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NAS' => 'NAS',
    'Class:NAS+' => '',
    'Class:NAS/Attribute:nasfilesystem_list' => 'Souborové systémy',
    'Class:NAS/Attribute:nasfilesystem_list+' => 'Všechny souborové systémy na tomto NASu',
));

//
// Class: PC
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PC' => 'PC',
    'Class:PC+' => '',
    'Class:PC/Attribute:osfamily_id' => 'Rodina OS',
    'Class:PC/Attribute:osfamily_id+' => '',
    'Class:PC/Attribute:osfamily_name' => 'Název rodiny OS',
    'Class:PC/Attribute:osfamily_name+' => '',
    'Class:PC/Attribute:osversion_id' => 'Verze OS',
    'Class:PC/Attribute:osversion_id+' => '',
    'Class:PC/Attribute:osversion_name' => 'Název verze OS',
    'Class:PC/Attribute:osversion_name+' => '',
    'Class:PC/Attribute:cpu' => 'CPU',
    'Class:PC/Attribute:cpu+' => '',
    'Class:PC/Attribute:ram' => 'RAM',
    'Class:PC/Attribute:ram+' => '',
    'Class:PC/Attribute:type' => 'Typ',
    'Class:PC/Attribute:type+' => '',
    'Class:PC/Attribute:type/Value:desktop' => 'desktop',
    'Class:PC/Attribute:type/Value:desktop+' => 'desktop',
    'Class:PC/Attribute:type/Value:laptop' => 'notebook',
    'Class:PC/Attribute:type/Value:laptop+' => 'notebook',
));

//
// Class: Printer
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Printer' => 'Tiskárna',
    'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PowerConnection' => 'Připojení k napájení',
    'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PowerSource' => 'Zdroj napájení',
    'Class:PowerSource+' => '',
    'Class:PowerSource/Attribute:pdus_list' => 'PDU',
    'Class:PowerSource/Attribute:pdus_list+' => 'Všechny jednotky pro rozvod energie využívající tento zdroj napájení',
));

//
// Class: PDU
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PDU' => 'PDU',
    'Class:PDU+' => '',
    'Class:PDU/Attribute:rack_id' => 'Rack',
    'Class:PDU/Attribute:rack_id+' => '',
    'Class:PDU/Attribute:rack_name' => 'Název racku',
    'Class:PDU/Attribute:rack_name+' => '',
    'Class:PDU/Attribute:powerstart_id' => 'Zdroj energie',
    'Class:PDU/Attribute:powerstart_id+' => '',
    'Class:PDU/Attribute:powerstart_name' => 'Název zdroje energie',
    'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Peripheral' => 'Periferie',
    'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Enclosure' => 'Šasi',
    'Class:Enclosure+' => '',
    'Class:Enclosure/Attribute:rack_id' => 'Rack',
    'Class:Enclosure/Attribute:rack_id+' => '',
    'Class:Enclosure/Attribute:rack_name' => 'Název racku',
    'Class:Enclosure/Attribute:rack_name+' => '',
    'Class:Enclosure/Attribute:nb_u' => 'Velikost (U)',
    'Class:Enclosure/Attribute:nb_u+' => '',
    'Class:Enclosure/Attribute:device_list' => 'Zařízení',
    'Class:Enclosure/Attribute:device_list+' => 'Všechna zařízení v tom to šasi',
));

//
// Class: ApplicationSolution
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ApplicationSolution' => 'Aplikační řešení',
    'Class:ApplicationSolution+' => '',
    'Class:ApplicationSolution/Attribute:functionalcis_list' => 'Konfigurační položky',
    'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Všechny konfigurační položky, které tvoří toto aplikační řešení',
    'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Obchodní procesy',
    'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Všechny obchodní procesy závisející na tomto aplikačním řešení',
    'Class:ApplicationSolution/Attribute:status' => 'Stav',
    'Class:ApplicationSolution/Attribute:status+' => '',
    'Class:ApplicationSolution/Attribute:status/Value:active' => 'aktivní',
    'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
    'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'pasivní',
    'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
    'Class:ApplicationSolution/Attribute:redundancy' => 'Redundance',
    'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Řešení je v provozu, pokud jsou funkční všechny konfigurační položky',
    'Class:ApplicationSolution/Attribute:redundancy/count' => 'Řešení je v provozu, pokud je funkčních alespoň %1$s konfiguračních položek',
    'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Řešení je v provozu, pokud je funkčních alespoň %1$s %% konfiguračních položek',
));

//
// Class: BusinessProcess
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:BusinessProcess' => 'Obchodní proces',
    'Class:BusinessProcess+' => '',
    'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Aplikační řešení',
    'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Všechna aplikační řešení, která ovlivňují tento proces',
    'Class:BusinessProcess/Attribute:status' => 'Stav',
    'Class:BusinessProcess/Attribute:status+' => '',
    'Class:BusinessProcess/Attribute:status/Value:active' => 'aktivní',
    'Class:BusinessProcess/Attribute:status/Value:active+' => '',
    'Class:BusinessProcess/Attribute:status/Value:inactive' => 'pasivní',
    'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SoftwareInstance' => 'Instance softwaru',
    'Class:SoftwareInstance+' => '',
    'Class:SoftwareInstance/Attribute:system_id' => 'Systém',
    'Class:SoftwareInstance/Attribute:system_id+' => '',
    'Class:SoftwareInstance/Attribute:system_name' => 'Název systému',
    'Class:SoftwareInstance/Attribute:system_name+' => '',
    'Class:SoftwareInstance/Attribute:software_id' => 'Software',
    'Class:SoftwareInstance/Attribute:software_id+' => '',
    'Class:SoftwareInstance/Attribute:software_name' => 'Název softwaru',
    'Class:SoftwareInstance/Attribute:software_name+' => '',
    'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licence softwaru',
    'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
    'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Název licence softwaru',
    'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
    'Class:SoftwareInstance/Attribute:path' => 'Cesta',
    'Class:SoftwareInstance/Attribute:path+' => '',
    'Class:SoftwareInstance/Attribute:status' => 'Stav',
    'Class:SoftwareInstance/Attribute:status+' => '',
    'Class:SoftwareInstance/Attribute:status/Value:active' => 'aktivní',
    'Class:SoftwareInstance/Attribute:status/Value:active+' => '',
    'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'pasivní',
    'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '',
));

//
// Class: Middleware
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Middleware' => 'Middleware',
    'Class:Middleware+' => '',
    'Class:Middleware/Attribute:middlewareinstance_list' => 'Instance middlewaru',
    'Class:Middleware/Attribute:middlewareinstance_list+' => 'Všechny instance tohoto middlewaru',
));

//
// Class: DBServer
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:DBServer' => 'DB Server',
    'Class:DBServer+' => '',
    'Class:DBServer/Attribute:dbschema_list' => 'DB schémata',
    'Class:DBServer/Attribute:dbschema_list+' => 'Všechna DB schémata pro tento DB server',
));

//
// Class: WebServer
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:WebServer' => 'Web server',
    'Class:WebServer+' => '',
    'Class:WebServer/Attribute:webapp_list' => 'Web aplikace',
    'Class:WebServer/Attribute:webapp_list+' => 'Všechny webové aplikace dostupné na tomto web serveru',
));

//
// Class: PCSoftware
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PCSoftware' => 'PC Software',
    'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:OtherSoftware' => 'Ostatní Software',
    'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:MiddlewareInstance' => 'Instance middlewaru',
    'Class:MiddlewareInstance+' => '',
    'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
    'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
    'Class:MiddlewareInstance/Attribute:middleware_name' => 'Název middlewaru',
    'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:DatabaseSchema' => 'DB Schéma',
    'Class:DatabaseSchema+' => '',
    'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB server',
    'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
    'Class:DatabaseSchema/Attribute:dbserver_name' => 'Název DB serveru',
    'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:WebApplication' => 'Web aplikace',
    'Class:WebApplication+' => '',
    'Class:WebApplication/Attribute:webserver_id' => 'Web server',
    'Class:WebApplication/Attribute:webserver_id+' => '',
    'Class:WebApplication/Attribute:webserver_name' => 'Název web serveru',
    'Class:WebApplication/Attribute:webserver_name+' => '',
    'Class:WebApplication/Attribute:url' => 'URL',
    'Class:WebApplication/Attribute:url+' => '',
));

//
// Class: VirtualDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:VirtualDevice' => 'Virtuální zařízení',
    'Class:VirtualDevice+' => '',
    'Class:VirtualDevice/Attribute:status' => 'Stav',
    'Class:VirtualDevice/Attribute:status+' => '',
    'Class:VirtualDevice/Attribute:status/Value:implementation' => 'implementace',
    'Class:VirtualDevice/Attribute:status/Value:implementation+' => '',
    'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'zastaralý',
    'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '',
    'Class:VirtualDevice/Attribute:status/Value:production' => 'v produkci',
    'Class:VirtualDevice/Attribute:status/Value:production+' => '',
    'Class:VirtualDevice/Attribute:status/Value:stock' => 'skladem/rezerva',
    'Class:VirtualDevice/Attribute:status/Value:stock+' => '',
    'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logické svazky',
    'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Všechny logické svazky používané tímto zařízením',
));

//
// Class: VirtualHost
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:VirtualHost' => 'Virtual host',
    'Class:VirtualHost+' => '',
    'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtuální stroje (VM)',
    'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Všechny virtuální stroje hostované na tomto virtual hostu',
));

//
// Class: Hypervisor
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Hypervisor' => 'Hypervisor',
    'Class:Hypervisor+' => '',
    'Class:Hypervisor/Attribute:farm_id' => 'Farma',
    'Class:Hypervisor/Attribute:farm_id+' => '',
    'Class:Hypervisor/Attribute:farm_name' => 'Název farmy',
    'Class:Hypervisor/Attribute:farm_name+' => '',
    'Class:Hypervisor/Attribute:server_id' => 'Server',
    'Class:Hypervisor/Attribute:server_id+' => '',
    'Class:Hypervisor/Attribute:server_name' => 'Název serveru',
    'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Farm' => 'Farma',
    'Class:Farm+' => '',
    'Class:Farm/Attribute:hypervisor_list' => 'Hypervisory',
    'Class:Farm/Attribute:hypervisor_list+' => 'Všechny hypervisory, které tvoří tuto farmu',
    'Class:Farm/Attribute:redundancy' => 'Vysoká dostupnost (HA)',
    'Class:Farm/Attribute:redundancy/disabled' => 'Farma je v provozu, pouze pokud jsou funknční všechny Hypervisory',
    'Class:Farm/Attribute:redundancy/count' => 'Farma je v provozu, pokud je funkčních alespoň %1$s Hypervisorů',
    'Class:Farm/Attribute:redundancy/percent' => 'Farma je v provozu, pokud je funkčních alespoň %1$s %% Hypervisorů',
));

//
// Class: VirtualMachine
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:VirtualMachine' => 'Virtuální stroj (VM)',
    'Class:VirtualMachine+' => '',
    'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtual host',
    'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
    'Class:VirtualMachine/Attribute:virtualhost_name' => 'Název virtual hosta',
    'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
    'Class:VirtualMachine/Attribute:osfamily_id' => 'Rodina OS',
    'Class:VirtualMachine/Attribute:osfamily_id+' => '',
    'Class:VirtualMachine/Attribute:osfamily_name' => 'Název rodiny OS',
    'Class:VirtualMachine/Attribute:osfamily_name+' => '',
    'Class:VirtualMachine/Attribute:osversion_id' => 'Verze OS',
    'Class:VirtualMachine/Attribute:osversion_id+' => '',
    'Class:VirtualMachine/Attribute:osversion_name' => 'Název verze OS',
    'Class:VirtualMachine/Attribute:osversion_name+' => '',
    'Class:VirtualMachine/Attribute:oslicence_id' => 'Licence OS',
    'Class:VirtualMachine/Attribute:oslicence_id+' => '',
    'Class:VirtualMachine/Attribute:oslicence_name' => 'Název licence OS',
    'Class:VirtualMachine/Attribute:oslicence_name+' => '',
    'Class:VirtualMachine/Attribute:cpu' => 'CPU',
    'Class:VirtualMachine/Attribute:cpu+' => '',
    'Class:VirtualMachine/Attribute:ram' => 'RAM',
    'Class:VirtualMachine/Attribute:ram+' => '',
    'Class:VirtualMachine/Attribute:managementip' => 'IP',
    'Class:VirtualMachine/Attribute:managementip+' => '',
    'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Síťová rozhraní',
    'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Všechna logická síťová rozhraní',
));

//
// Class: LogicalVolume
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:LogicalVolume' => 'Logický svazek',
    'Class:LogicalVolume+' => '',
    'Class:LogicalVolume/Attribute:name' => 'Název',
    'Class:LogicalVolume/Attribute:name+' => '',
    'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
    'Class:LogicalVolume/Attribute:lun_id+' => '',
    'Class:LogicalVolume/Attribute:description' => 'Popis',
    'Class:LogicalVolume/Attribute:description+' => '',
    'Class:LogicalVolume/Attribute:raid_level' => 'typ RAID',
    'Class:LogicalVolume/Attribute:raid_level+' => '',
    'Class:LogicalVolume/Attribute:size' => 'Velikost',
    'Class:LogicalVolume/Attribute:size+' => '',
    'Class:LogicalVolume/Attribute:storagesystem_id' => 'Úložný systém',
    'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
    'Class:LogicalVolume/Attribute:storagesystem_name' => 'Název úložného systému',
    'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
    'Class:LogicalVolume/Attribute:servers_list' => 'Servery',
    'Class:LogicalVolume/Attribute:servers_list+' => 'Všechny servery užívající tento svazek',
    'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuální zařízení',
    'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Všechna virtuální zařízení užívající tento svazek',
));

//
// Class: lnkServerToVolume
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkServerToVolume' => 'Spojení (Server / Svazek)',
    'Class:lnkServerToVolume+' => '',
    'Class:lnkServerToVolume/Attribute:volume_id' => 'Svazek',
    'Class:lnkServerToVolume/Attribute:volume_id+' => '',
    'Class:lnkServerToVolume/Attribute:volume_name' => 'Název svazku',
    'Class:lnkServerToVolume/Attribute:volume_name+' => '',
    'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
    'Class:lnkServerToVolume/Attribute:server_id+' => '',
    'Class:lnkServerToVolume/Attribute:server_name' => 'Název serveru',
    'Class:lnkServerToVolume/Attribute:server_name+' => '',
    'Class:lnkServerToVolume/Attribute:size_used' => 'Využitá velikost',
    'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkVirtualDeviceToVolume' => 'Spojení (Virtuální zařízení / Svazek)',
    'Class:lnkVirtualDeviceToVolume+' => '',
    'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Svazek',
    'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
    'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Název svazku',
    'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
    'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuální zařízení',
    'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
    'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Název virtuálního zařízení',
    'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
    'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Využitá velikost',
    'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkSanToDatacenterDevice' => 'Spojení (SAN / Zařízení datového centra)',
    'Class:lnkSanToDatacenterDevice+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN switch',
    'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'název SAN switche',
    'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Zařízení',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Název zařízení',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC',
    'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'FC zařízení',
    'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Tape' => 'Páska',
    'Class:Tape+' => '',
    'Class:Tape/Attribute:name' => 'Název',
    'Class:Tape/Attribute:name+' => '',
    'Class:Tape/Attribute:description' => 'Popis',
    'Class:Tape/Attribute:description+' => '',
    'Class:Tape/Attribute:size' => 'Velikost',
    'Class:Tape/Attribute:size+' => '',
    'Class:Tape/Attribute:tapelibrary_id' => 'Pásková knihovna',
    'Class:Tape/Attribute:tapelibrary_id+' => '',
    'Class:Tape/Attribute:tapelibrary_name' => 'Název páskové knihovny',
    'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NASFileSystem' => 'Souborový systém nas',
    'Class:NASFileSystem+' => '',
    'Class:NASFileSystem/Attribute:name' => 'Název',
    'Class:NASFileSystem/Attribute:name+' => '',
    'Class:NASFileSystem/Attribute:description' => 'Popis',
    'Class:NASFileSystem/Attribute:description+' => '',
    'Class:NASFileSystem/Attribute:raid_level' => 'Typ RAID',
    'Class:NASFileSystem/Attribute:raid_level+' => '',
    'Class:NASFileSystem/Attribute:size' => 'Velikost',
    'Class:NASFileSystem/Attribute:size+' => '',
    'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
    'Class:NASFileSystem/Attribute:nas_id+' => '',
    'Class:NASFileSystem/Attribute:nas_name' => 'Název NAS',
    'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Software' => 'Software',
    'Class:Software+' => '',
    'Class:Software/Attribute:name' => 'Název',
    'Class:Software/Attribute:name+' => '',
    'Class:Software/Attribute:vendor' => 'Dodavatel',
    'Class:Software/Attribute:vendor+' => '',
    'Class:Software/Attribute:version' => 'Verze',
    'Class:Software/Attribute:version+' => '',
    'Class:Software/Attribute:documents_list' => 'Dokumenty',
    'Class:Software/Attribute:documents_list+' => 'Všechny dokumenty spojené s tímto software',
    'Class:Software/Attribute:type' => 'Typ',
    'Class:Software/Attribute:type+' => '',
    'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
    'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server',
    'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
    'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
    'Class:Software/Attribute:type/Value:OtherSoftware' => 'Ostatní Software',
    'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Ostatní Software',
    'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software',
    'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software',
    'Class:Software/Attribute:type/Value:WebServer' => 'Web Server',
    'Class:Software/Attribute:type/Value:WebServer+' => 'Web Server',
    'Class:Software/Attribute:softwareinstance_list' => 'Instance softwaru',
    'Class:Software/Attribute:softwareinstance_list+' => 'Všechny instance tohoto softwaru',
    'Class:Software/Attribute:softwarepatch_list' => 'Softwarové záplaty (patche)',
    'Class:Software/Attribute:softwarepatch_list+' => 'Všechny záplaty (patche) pro tento software',
    'Class:Software/Attribute:softwarelicence_list' => 'Licence softwaru',
    'Class:Software/Attribute:softwarelicence_list+' => 'Všechny licence pro tento software',
));

//
// Class: Patch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Patch' => 'Záplata (patch)',
    'Class:Patch+' => '',
    'Class:Patch/Attribute:name' => 'Název',
    'Class:Patch/Attribute:name+' => '',
    'Class:Patch/Attribute:documents_list' => 'Dokumenty',
    'Class:Patch/Attribute:documents_list+' => 'Všechny dokumenty spojené s touto záplatou',
    'Class:Patch/Attribute:description' => 'Popis',
    'Class:Patch/Attribute:description+' => '',
    'Class:Patch/Attribute:finalclass' => 'Typ',
    'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:OSPatch' => 'Záplata (patch) OS',
    'Class:OSPatch+' => '',
    'Class:OSPatch/Attribute:functionalcis_list' => 'Zařízení',
    'Class:OSPatch/Attribute:functionalcis_list+' => 'Všechna zařízení, kde je tato záplata (patch) instalována',
    'Class:OSPatch/Attribute:osversion_id' => 'Verze OS',
    'Class:OSPatch/Attribute:osversion_id+' => '',
    'Class:OSPatch/Attribute:osversion_name' => 'Název verze OS',
    'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SoftwarePatch' => 'Záplata (patch) softwaru',
    'Class:SoftwarePatch+' => '',
    'Class:SoftwarePatch/Attribute:software_id' => 'Software',
    'Class:SoftwarePatch/Attribute:software_id+' => '',
    'Class:SoftwarePatch/Attribute:software_name' => 'Název softwaru',
    'Class:SoftwarePatch/Attribute:software_name+' => '',
    'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instance softwaru',
    'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Všechny systémy, kde je tato záplata (patch) softwaru instalována',
));

//
// Class: Licence
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Licence' => 'Licence',
    'Class:Licence+' => '',
    'Class:Licence/Attribute:name' => 'Název',
    'Class:Licence/Attribute:name+' => '',
    'Class:Licence/Attribute:documents_list' => 'Dokumenty',
    'Class:Licence/Attribute:documents_list+' => 'Všechny dokumenty spojené s touto licencí',
    'Class:Licence/Attribute:org_id' => 'Organizace',
    'Class:Licence/Attribute:org_id+' => '',
    'Class:Licence/Attribute:organization_name' => 'Název organizace',
    'Class:Licence/Attribute:organization_name+' => '',
    'Class:Licence/Attribute:usage_limit' => 'Omezení použití',
    'Class:Licence/Attribute:usage_limit+' => '',
    'Class:Licence/Attribute:description' => 'Popis',
    'Class:Licence/Attribute:description+' => '',
    'Class:Licence/Attribute:start_date' => 'Počátek platnosti',
    'Class:Licence/Attribute:start_date+' => '',
    'Class:Licence/Attribute:end_date' => 'Konec platnosti',
    'Class:Licence/Attribute:end_date+' => '',
    'Class:Licence/Attribute:licence_key' => 'Klíč',
    'Class:Licence/Attribute:licence_key+' => '',
    'Class:Licence/Attribute:perpetual' => 'Trvalá',
    'Class:Licence/Attribute:perpetual+' => '',
    'Class:Licence/Attribute:perpetual/Value:no' => 'Ne',
    'Class:Licence/Attribute:perpetual/Value:no+' => '',
    'Class:Licence/Attribute:perpetual/Value:yes' => 'Ano',
    'Class:Licence/Attribute:perpetual/Value:yes+' => '',
    'Class:Licence/Attribute:finalclass' => 'Typ',
    'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:OSLicence' => 'Licence OS',
    'Class:OSLicence+' => '',
    'Class:OSLicence/Attribute:osversion_id' => 'Verze OS',
    'Class:OSLicence/Attribute:osversion_id+' => '',
    'Class:OSLicence/Attribute:osversion_name' => 'Název verze OS',
    'Class:OSLicence/Attribute:osversion_name+' => '',
    'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuální stroje (VM)',
    'Class:OSLicence/Attribute:virtualmachines_list+' => 'Všechny virtuální stroje (VM), kde je tato licence použita',
    'Class:OSLicence/Attribute:servers_list' => 'Servery',
    'Class:OSLicence/Attribute:servers_list+' => 'Všechny servery, kde je tato licence použita',
));

//
// Class: SoftwareLicence
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:SoftwareLicence' => 'Licence softwaru',
    'Class:SoftwareLicence+' => '',
    'Class:SoftwareLicence/Attribute:software_id' => 'Software',
    'Class:SoftwareLicence/Attribute:software_id+' => '',
    'Class:SoftwareLicence/Attribute:software_name' => 'Název softwaru',
    'Class:SoftwareLicence/Attribute:software_name+' => '',
    'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instance softwaru',
    'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Všechny systémy, kde je tato licence použita',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToLicence' => 'Spojení (Dokument / Licence)',
    'Class:lnkDocumentToLicence+' => '',
    'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licence',
    'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
    'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Název licence',
    'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
    'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
    'Class:lnkDocumentToLicence/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
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
// Class: OSVersion
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:OSVersion' => 'Verze OS',
    'Class:OSVersion+' => '',
    'Class:OSVersion/Attribute:osfamily_id' => 'Rodina OS',
    'Class:OSVersion/Attribute:osfamily_id+' => '',
    'Class:OSVersion/Attribute:osfamily_name' => 'Název rodiny OS',
    'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:OSFamily' => 'Rodina OS',
    'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Brand' => 'Výrobce',
    'Class:Brand+' => '',
    'Class:Brand/Attribute:physicaldevices_list' => 'Fyzická zařízení',
    'Class:Brand/Attribute:physicaldevices_list+' => 'Všechna fyzická zařízení odpovídající této značce',
));

//
// Class: Model
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Model' => 'Model',
    'Class:Model+' => '',
    'Class:Model/Attribute:brand_id' => 'Výrobce',
    'Class:Model/Attribute:brand_id+' => '',
    'Class:Model/Attribute:brand_name' => 'Název výrobce',
    'Class:Model/Attribute:brand_name+' => '',
    'Class:Model/Attribute:type' => 'Typ zařízení',
    'Class:Model/Attribute:type+' => '',
    'Class:Model/Attribute:type/Value:PowerSource' => 'Zdroj napájení',
    'Class:Model/Attribute:type/Value:PowerSource+' => '',
    'Class:Model/Attribute:type/Value:DiskArray' => 'Diskové pole',
    'Class:Model/Attribute:type/Value:DiskArray+' => '',
    'Class:Model/Attribute:type/Value:Enclosure' => 'Šasi',
    'Class:Model/Attribute:type/Value:Enclosure+' => '',
    'Class:Model/Attribute:type/Value:IPPhone' => 'IP Telefon',
    'Class:Model/Attribute:type/Value:IPPhone+' => '',
    'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobilní telefon',
    'Class:Model/Attribute:type/Value:MobilePhone+' => '',
    'Class:Model/Attribute:type/Value:NAS' => 'NAS',
    'Class:Model/Attribute:type/Value:NAS+' => '',
    'Class:Model/Attribute:type/Value:NetworkDevice' => 'Síťový prvek',
    'Class:Model/Attribute:type/Value:NetworkDevice+' => '',
    'Class:Model/Attribute:type/Value:PC' => 'PC',
    'Class:Model/Attribute:type/Value:PC+' => '',
    'Class:Model/Attribute:type/Value:PDU' => 'PDU',
    'Class:Model/Attribute:type/Value:PDU+' => '',
    'Class:Model/Attribute:type/Value:Peripheral' => 'Periferie',
    'Class:Model/Attribute:type/Value:Peripheral+' => '',
    'Class:Model/Attribute:type/Value:Printer' => 'Tiskárna',
    'Class:Model/Attribute:type/Value:Printer+' => '',
    'Class:Model/Attribute:type/Value:Rack' => 'Rack',
    'Class:Model/Attribute:type/Value:Rack+' => '',
    'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN switch',
    'Class:Model/Attribute:type/Value:SANSwitch+' => '',
    'Class:Model/Attribute:type/Value:Server' => 'Server',
    'Class:Model/Attribute:type/Value:Server+' => '',
    'Class:Model/Attribute:type/Value:StorageSystem' => 'Úložný systém',
    'Class:Model/Attribute:type/Value:StorageSystem+' => '',
    'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
    'Class:Model/Attribute:type/Value:Tablet+' => '',
    'Class:Model/Attribute:type/Value:TapeLibrary' => 'Pásková knihovna',
    'Class:Model/Attribute:type/Value:TapeLibrary+' => '',
    'Class:Model/Attribute:type/Value:Phone' => 'Telefon',
    'Class:Model/Attribute:type/Value:Phone+' => '',
    'Class:Model/Attribute:physicaldevices_list' => 'Fyzická zařízení',
    'Class:Model/Attribute:physicaldevices_list+' => 'Všechna fyzická zařízení odpovídající tomuto modelu',
));

//
// Class: NetworkDeviceType
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NetworkDeviceType' => 'Typ síťového zařízení',
    'Class:NetworkDeviceType+' => '',
    'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Síťová zařízení',
    'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Všechna síťová zařízení odpovídající tomuto typu',
));

//
// Class: IOSVersion
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:IOSVersion' => 'Verze IOS',
    'Class:IOSVersion+' => '',
    'Class:IOSVersion/Attribute:brand_id' => 'Výrobce',
    'Class:IOSVersion/Attribute:brand_id+' => '',
    'Class:IOSVersion/Attribute:brand_name' => 'Název výrobce',
    'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToPatch' => 'Spojení (Dokument / Záplata (patch))',
    'Class:lnkDocumentToPatch+' => '',
    'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Záplata (patch)',
    'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
    'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Název záplaty (patche)',
    'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
    'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
    'Class:lnkDocumentToPatch/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Spojení (Instance softwaru / Záplata (patch) softwaru)',
    'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Záplata (patch) softwaru',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Název záplaty (patche) softwaru',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instance softwaru',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Název instance softwaru',
    'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkFunctionalCIToOSPatch' => 'Spojení (Funkční konfigurační položka / Záplata (patch) OS)',
    'Class:lnkFunctionalCIToOSPatch+' => '',
    'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Záplata (patch) OS',
    'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
    'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Název záplaty (patche) OS',
    'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
    'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Funkční konfigurační položka',
    'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Název funkční konfigurační položky',
    'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToSoftware' => 'Spojení (Dokument / Software)',
    'Class:lnkDocumentToSoftware+' => '',
    'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
    'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
    'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Název software',
    'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
    'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
    'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkContactToFunctionalCI' => 'Spojení (Kontakt / Funkční konfigurační položka)',
    'Class:lnkContactToFunctionalCI+' => '',
    'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Funkční konfigurační položka',
    'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
    'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Název funkční konfigurační položky',
    'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
    'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
    'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
    'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Název kontaktu',
    'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToFunctionalCI' => 'Spojení (Dokument / Funkční konfigurační položka)',
    'Class:lnkDocumentToFunctionalCI+' => '',
    'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Funkční konfigurační položka',
    'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
    'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Název funkční konfigurační položky',
    'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
    'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
    'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Subnet' => 'Podsíť (subnet)',
    'Class:Subnet+' => '',
    'Class:Subnet/Attribute:description' => 'Popis',
    'Class:Subnet/Attribute:description+' => '',
    'Class:Subnet/Attribute:subnet_name' => 'Název podsítě (subnetu)',
    'Class:Subnet/Attribute:subnet_name+' => '',
    'Class:Subnet/Attribute:org_id' => 'Vlastník',
    'Class:Subnet/Attribute:org_id+' => '',
    'Class:Subnet/Attribute:org_name' => 'Název',
    'Class:Subnet/Attribute:org_name+' => '',
    'Class:Subnet/Attribute:ip' => 'IP',
    'Class:Subnet/Attribute:ip+' => '',
    'Class:Subnet/Attribute:ip_mask' => 'IP maska',
    'Class:Subnet/Attribute:ip_mask+' => '',
    'Class:Subnet/Attribute:vlans_list' => 'VLAN',
    'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:VLAN' => 'VLAN',
    'Class:VLAN+' => '',
    'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag',
    'Class:VLAN/Attribute:vlan_tag+' => '',
    'Class:VLAN/Attribute:description' => 'Popis',
    'Class:VLAN/Attribute:description+' => '',
    'Class:VLAN/Attribute:org_id' => 'Organizace',
    'Class:VLAN/Attribute:org_id+' => '',
    'Class:VLAN/Attribute:org_name' => 'Název organizace',
    'Class:VLAN/Attribute:org_name+' => '',
    'Class:VLAN/Attribute:subnets_list' => 'Podsítě (subnety)',
    'Class:VLAN/Attribute:subnets_list+' => '',
    'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fyzická síťová rozhraní',
    'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkSubnetToVLAN' => 'Spojení (Podsíť (subnet) / VLAN)',
    'Class:lnkSubnetToVLAN+' => '',
    'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Podsíť (subnet)',
    'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
    'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP Podsítě (subnetu)',
    'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
    'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Název podsítě (subnetu)',
    'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
    'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
    'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
    'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
    'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NetworkInterface' => 'Síťové rozhraní',
    'Class:NetworkInterface+' => '',
    'Class:NetworkInterface/Attribute:name' => 'Název',
    'Class:NetworkInterface/Attribute:name+' => '',
    'Class:NetworkInterface/Attribute:finalclass' => 'Typ',
    'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:IPInterface' => 'IP rozhraní',
    'Class:IPInterface+' => '',
    'Class:IPInterface/Attribute:ipaddress' => 'IP adresa',
    'Class:IPInterface/Attribute:ipaddress+' => '',

    'Class:IPInterface/Attribute:macaddress' => 'MAC adresa',
    'Class:IPInterface/Attribute:macaddress+' => '',
    'Class:IPInterface/Attribute:comment' => 'Komentář',
    'Class:IPInterface/Attribute:coment+' => '',
    'Class:IPInterface/Attribute:ipgateway' => 'IP brána',
    'Class:IPInterface/Attribute:ipgateway+' => '',
    'Class:IPInterface/Attribute:ipmask' => 'IP maska',
    'Class:IPInterface/Attribute:ipmask+' => '',
    'Class:IPInterface/Attribute:speed' => 'Rychlost',
    'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:PhysicalInterface' => 'Fyzické rozhraní',
    'Class:PhysicalInterface+' => '',
    'Class:PhysicalInterface/Attribute:connectableci_id' => 'Zařízení',
    'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
    'Class:PhysicalInterface/Attribute:connectableci_name' => 'Název zařízení',
    'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
    'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN',
    'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkPhysicalInterfaceToVLAN' => 'Spojení (Fyzické rozhraní / VLAN)',
    'Class:lnkPhysicalInterfaceToVLAN+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Fyzické rozhraní',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Název fyzického rozhraní',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Zařízení',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Název zařízení',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
    'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: LogicalInterface
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:LogicalInterface' => 'Logické rozhraní',
    'Class:LogicalInterface+' => '',
    'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuální stroj (VM)',
    'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
    'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Název virtuálního stroje (VM)',
    'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:FiberChannelInterface' => 'FC rozhraní',
    'Class:FiberChannelInterface+' => '',
    'Class:FiberChannelInterface/Attribute:speed' => 'Rychlost',
    'Class:FiberChannelInterface/Attribute:speed+' => '',
    'Class:FiberChannelInterface/Attribute:topology' => 'Topologie',
    'Class:FiberChannelInterface/Attribute:topology+' => '',
    'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
    'Class:FiberChannelInterface/Attribute:wwn+' => '',
    'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Zařízení',
    'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
    'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Název zařízení',
    'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkConnectableCIToNetworkDevice' => 'Spojení (Připojitelná konfigurační položka / Síťový prvek)',
    'Class:lnkConnectableCIToNetworkDevice+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Síťový prvek',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Název síťového prvku',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Připojené zařízení',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Název připojeného zařízení',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Port síťového prvku',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Port zařízení',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Typ propojení',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'down link',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'up link',
    'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkApplicationSolutionToFunctionalCI' => 'Spojení (Aplikační řešení / Funkční konfigurační položka)',
    'Class:lnkApplicationSolutionToFunctionalCI+' => '',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Aplikační řešení',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Název aplikačního řešení',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Funkční konfigurační položka',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Název funkční konfigurační položky',
    'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkApplicationSolutionToBusinessProcess' => 'Spojení (Aplikační řešení / Obchodní proces)',
    'Class:lnkApplicationSolutionToBusinessProcess+' => '',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Obchodní proces',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Název obchodního procesu',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Aplikační řešení',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Název aplikačního řešení',
    'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
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
// Class: Group
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Group' => 'Skupina',
    'Class:Group+' => '',
    'Class:Group/Attribute:name' => 'Název',
    'Class:Group/Attribute:name+' => '',
    'Class:Group/Attribute:status' => 'Stav',
    'Class:Group/Attribute:status+' => '',
    'Class:Group/Attribute:status/Value:implementation' => 'Implementace',
    'Class:Group/Attribute:status/Value:implementation+' => '',
    'Class:Group/Attribute:status/Value:obsolete' => 'Zastaralé',
    'Class:Group/Attribute:status/Value:obsolete+' => '',
    'Class:Group/Attribute:status/Value:production' => 'V produkci',
    'Class:Group/Attribute:status/Value:production+' => '',
    'Class:Group/Attribute:org_id' => 'Organizace',
    'Class:Group/Attribute:org_id+' => '',
    'Class:Group/Attribute:owner_name' => 'Název',
    'Class:Group/Attribute:owner_name+' => '',
    'Class:Group/Attribute:description' => 'Popis',
    'Class:Group/Attribute:description+' => '',
    'Class:Group/Attribute:type' => 'Typ',
    'Class:Group/Attribute:type+' => '',
    'Class:Group/Attribute:parent_id' => 'Nadřazená skupina',

    'Class:Group/Attribute:parent_id+' => '',
    'Class:Group/Attribute:parent_name' => 'Název',
    'Class:Group/Attribute:parent_name+' => '',
    'Class:Group/Attribute:ci_list' => 'Konfigurační položky',
    'Class:Group/Attribute:ci_list+' => 'Všechny konfigurační položky spojené s touto skupinou',
    'Class:Group/Attribute:parent_id_friendlyname' => 'Nadřazená skupina',
    'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkGroupToCI' => 'Spojení (Skupina / Konfigurační položka)',
    'Class:lnkGroupToCI+' => '',
    'Class:lnkGroupToCI/Attribute:group_id' => 'Skupina',
    'Class:lnkGroupToCI/Attribute:group_id+' => '',
    'Class:lnkGroupToCI/Attribute:group_name' => 'Název',
    'Class:lnkGroupToCI/Attribute:group_name+' => '',
    'Class:lnkGroupToCI/Attribute:ci_id' => 'Konfigurační položka',
    'Class:lnkGroupToCI/Attribute:ci_id+' => '',
    'Class:lnkGroupToCI/Attribute:ci_name' => 'Název',
    'Class:lnkGroupToCI/Attribute:ci_name+' => '',
    'Class:lnkGroupToCI/Attribute:reason' => 'Důvod',
    'Class:lnkGroupToCI/Attribute:reason+' => '',
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
    'Menu:Application' => 'Aplikace',
    'Menu:Application+' => 'Všechny aplikace',
    'Menu:DBServer' => 'Databázové servery',
    'Menu:DBServer+' => 'Databázové servery',
    'Menu:Audit' => 'Audit',
    'Menu:ConfigManagement' => 'Správa konfigurací',
    'Menu:ConfigManagement+' => 'Správa konfigurací',
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
    'Menu:ConfigManagementCI' => 'Konfigurační položky',
    'Menu:ConfigManagementCI+' => 'Konfigurační položky',
    'Menu:BusinessProcess' => 'Obchodní procesy',
    'Menu:BusinessProcess+' => 'Všechny obchodní procesy',
    'Menu:ApplicationSolution' => 'Aplikační řešení',
    'Menu:ApplicationSolution+' => 'Všechna aplikační řešení',
    'Menu:ConfigManagementSoftware' => 'Správa aplikací',
    'Menu:Licence' => 'Licence',
    'Menu:Licence+' => 'Všechny licence',
    'Menu:Patch' => 'Záplaty (patche)',
    'Menu:Patch+' => 'Všechny záplaty (patche)',
    'Menu:ApplicationInstance' => 'Instalovaný software',
    'Menu:ApplicationInstance+' => 'Aplikace a databázové servery',
    'Menu:ConfigManagementHardware' => 'Správa infrastruktury',
    'Menu:Subnet' => 'Podsítě (subnety)',
    'Menu:Subnet+' => 'Všechny podsítě (subnety)',
    'Menu:NetworkDevice' => 'Síťová zařízení',
    'Menu:NetworkDevice+' => 'Všechna síťová zařízení',
    'Menu:Server' => 'Servery',
    'Menu:Server+' => 'Všechny servery',
    'Menu:Printer' => 'Tiskárny',
    'Menu:Printer+' => 'Všechny tiskárny',
    'Menu:MobilePhone' => 'Mobilní telefony',
    'Menu:MobilePhone+' => 'Všechny mobilní telefony',
    'Menu:PC' => 'Osobní počítače',
    'Menu:PC+' => 'Všechny osobní počítače',
    'Menu:NewContact' => 'Nový kontakt',
    'Menu:NewContact+' => 'Nový kontakt',
    'Menu:SearchContacts' => 'Hledat kontakty',
    'Menu:SearchContacts+' => 'Hledat kontakty',
    'Menu:NewCI' => 'Nová konfigurační položka',
    'Menu:NewCI+' => 'Nová konfigurační položka',
    'Menu:SearchCIs' => 'Hledat konfigurační položky',
    'Menu:SearchCIs+' => 'Hledat konfigurační položky',
    'Menu:ConfigManagement:Devices' => 'Zařízení',
    'Menu:ConfigManagement:AllDevices' => 'Infrastruktura',
    'Menu:ConfigManagement:virtualization' => 'Virtualizace',
    'Menu:ConfigManagement:EndUsers' => 'Koncová zařízení',
    'Menu:ConfigManagement:SWAndApps' => 'Software a aplikace',
    'Menu:ConfigManagement:Misc' => 'Ostatní',
    'Menu:Group' => 'Skupiny konfiguračních položek',
    'Menu:Group+' => 'Skupiny konfiguračních položek',
    'Menu:ConfigManagement:Shortcuts' => 'Odkazy',
    'Menu:ConfigManagement:AllContacts' => 'Všechny kontakty: %1$d',
    'Menu:Typology' => 'Typologie',
    'Menu:Typology+' => 'Konfigurace typologie',
    'Menu:OSVersion' => 'Verze OS',
    'Menu:OSVersion+' => '',
    'Menu:Software' => 'Katalog softwaru',
    'Menu:Software+' => 'Katalog softwaru',
    'UI_WelcomeMenu_AllConfigItems' => 'Souhrn',
    'Menu:ConfigManagement:Typology' => 'Konfigurace typologie',
));

// Add translation for Fieldsets

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Server:baseinfo' => 'Obecné informace',
    'Server:Date' => 'Data',
    'Server:moreinfo' => 'Více informací',
    'Server:otherinfo' => 'Další informace',
    'Server:power' => 'Napájení',
    'Person:info' => 'Obecné informace',
    'Person:notifiy' => 'Upozornění',
    'Class:Subnet/Tab:IPUsage' => 'Využití IP',
    'Class:Subnet/Tab:IPUsage-explain' => 'Rozhraní, která mají IP adresu v rozsahu: <em>%1$s</em>-<em>%2$s</em>',
    'Class:Subnet/Tab:FreeIPs' => 'Volné IP adresy',
    'Class:Subnet/Tab:FreeIPs-count' => 'Počet volných adres: %1$s',
    'Class:Subnet/Tab:FreeIPs-explain' => 'Tady je výčet volných IP adres (10)',
    'Class:Document:PreviewTab' => 'Náhled',
));

<?php
/**
 * Localized data
 *
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
// Relations
//////////////////////////////////////////////////////////////////////
//
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Relation:impacts/Description' => 'Prvky zasiahnuté',
	'Relation:impacts/DownStream' => 'Impacts...~~',
	'Relation:impacts/DownStream+' => 'Elements impacted by~~',
	'Relation:impacts/UpStream' => 'Depends on......~~',
	'Relation:impacts/UpStream+' => 'Elements impacting~~',
	// Legacy entries
	'Relation:depends on/Description' => 'Prvky, od ktorých závisí tento prvok',
	'Relation:depends on/DownStream' => 'Depends on...~~',
	'Relation:depends on/UpStream' => 'Impacts...~~',
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
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

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
	'Class:Document/Attribute:contracts_list' => 'Zmluvy',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Služby',
	'Class:Document/Attribute:services_list+' => '',
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
// Class: FunctionalCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:FunctionalCI' => 'Komponent',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Názov',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Popis',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organizácia',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Názov Organizácie',
	'Class:FunctionalCI/Attribute:organization_name+' => '',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Dôležitosť pre biznis',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'vysoká',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'nízka',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'stredná',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:move2production' => 'Dátum presunu do produkcie',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kontakty',
	'Class:FunctionalCI/Attribute:contacts_list+' => '',
	'Class:FunctionalCI/Attribute:documents_list' => 'Zoznam dokumentov',
	'Class:FunctionalCI/Attribute:documents_list+' => '',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Zoznam aplikačných riešení',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Poskytovateľské zmluvy',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
	'Class:FunctionalCI/Attribute:services_list' => 'Služby',
	'Class:FunctionalCI/Attribute:services_list+' => '',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Softvér',
	'Class:FunctionalCI/Attribute:softwares_list+' => '',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickety',
	'Class:FunctionalCI/Attribute:tickets_list+' => '',
	'Class:FunctionalCI/Attribute:finalclass' => 'Typ komponentu',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Active Tickets~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PhysicalDevice' => 'Fyzické zariadenie',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Sériové číslo',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Lokalita',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Názov lokality',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Stav',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'vyradené',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'v produkcií',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'na sklade',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Značka',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Názov značky',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Názov modelu',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Číslo položky',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Dátum zakúpenia',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Koniec záruky',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Rack' => 'stojan (Rack)',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'NB U',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Zariadenia',
	'Class:Rack/Attribute:device_list+' => '',
	'Class:Rack/Attribute:enclosure_list' => 'Kryt',
	'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:TelephonyCI' => 'Telefónne zariadenie',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefónne číslo',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Phone' => 'Telefón',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:MobilePhone' => 'Mobilný telefón',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'HW PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:IPPhone' => 'IP telefón',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ConnectableCI' => 'Pripojiteľné zariadenie',
	'Class:ConnectableCI+' => '',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Sieťové zariadenia',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Sieťové rozhrania',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DatacenterDevice' => 'Zariadenie dátového centra',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'stojan (Rack)',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Názov stojanu',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Kryt',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Názov krytu',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'NB U',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Menežmentová IP adresa',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Zdroj napájania A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Názov zdroja napájania A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Zdroj napájania B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Názov zdroja napájania B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Zoznam optických rozhraní',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
	'Class:DatacenterDevice/Attribute:san_list' => 'Úložiská (SAN)',
	'Class:DatacenterDevice/Attribute:san_list+' => '',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundancy~~',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'The device is up if at least one power connection (A or B) is up~~',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'The device is up if all its power connections are up~~',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'The device is up if at least %1$s %% of its power connections are up~~',
));

//
// Class: NetworkDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NetworkDevice' => 'Sieťové zariadenie',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Typ sieťového zariadenia',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Názov typu sieťového zariadenia',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Zariadenia',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IVerzia OS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Názov IOS verzie',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'Operačná pamäť',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'Kategória OS',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Názov kategórie OS',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Verzia OS',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Názov verzie OS',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licencia OS',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Názov licence OS',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'Procesor',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'Operačna pamäť',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logické disky',
	'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:StorageSystem' => 'Úložiskový systém',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logické disky',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '',
));

//
// Class: SANSwitch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SANSwitch' => 'SAN prepínač',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Zariadenia',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '',
));

//
// Class: TapeLibrary
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:TapeLibrary' => 'Pásková knižnica',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Pásky',
	'Class:TapeLibrary/Attribute:tapes_list+' => '',
));

//
// Class: NAS
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Súborové systémy',
	'Class:NAS/Attribute:nasfilesystem_list+' => '',
));

//
// Class: PC
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'Kategória OS',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Názov kategórie OS',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Verzia OS',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Názov verzie OS',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'Procesor',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'Operačná pamäť',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Typ',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Stolový počítač',
	'Class:PC/Attribute:type/Value:desktop+' => '',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => '',
));

//
// Class: Printer
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Printer' => 'Tlačiareň',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PowerConnection' => 'Elektrická prípojka',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PowerSource' => 'Napájací zdroj',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'Napäťové distribučné jednotky (PDU)',
	'Class:PowerSource/Attribute:pdus_list+' => '',
));

//
// Class: PDU
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PDU' => 'Napäťová distribučná jednotka (PDU)',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'stojan (Rack)',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Názov stojanu (rack)',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Power start',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Názov Power start-u',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Peripheral' => 'Periférie',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Enclosure' => 'Kryt (enclosure)',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'stojan (Rack)',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Názov stojanu (rack)',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'NB U',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Zariadenia',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ApplicationSolution' => 'Aplikačné riešenie',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'Komponenty',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Biznis procesy',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '',
	'Class:ApplicationSolution/Attribute:status' => 'Stav',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Aktívne',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Neaktívne',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Impact analysis: configuration of the redundancy~~',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up~~',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up~~',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up~~',
));

//
// Class: BusinessProcess
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:BusinessProcess' => 'Biznis proces',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Aplikačné riešenia',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '',
	'Class:BusinessProcess/Attribute:status' => 'Stav',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Aktívny',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Neaktívny',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SoftwareInstance' => 'Softvérová inštancia',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Systém',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Názov systému',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Softvér',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Názov softvéru',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Softvérová licencia',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Názov softvérovej licencie',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Cesta',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Stav',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'aktívna',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'neaktívna',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '',
));

//
// Class: Middleware
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware inštancie',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '',
));

//
// Class: DBServer
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DBServer' => 'DB Server',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB schémy',
	'Class:DBServer/Attribute:dbschema_list+' => '',
));

//
// Class: WebServer
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:WebServer' => 'Web server',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Webové aplikácie',
	'Class:WebServer/Attribute:webapp_list+' => '',
));

//
// Class: PCSoftware
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PCSoftware' => 'PC softvér',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:OtherSoftware' => 'Iný softvér',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:MiddlewareInstance' => 'Middleware inštancia',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Názov Middleware-u',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:DatabaseSchema' => 'Databázová schéma',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB server',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Názov DB serveru',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:WebApplication' => 'Webová Aplikácia',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Web server',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Názov Web serveru',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:VirtualDevice' => 'Virtuálne zariadenie',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Stav',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Vyradené',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Produkcia',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Zásoby',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Zoznam logických dielov',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '',
));

//
// Class: VirtualHost
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:VirtualHost' => 'Virtuálny host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Zoznam virtuálnych strojov',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '',
));

//
// Class: Hypervisor
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farma',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Názov farmy',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Názov serveru',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Farm' => 'Farma',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisori',
	'Class:Farm/Attribute:hypervisor_list+' => '',
	'Class:Farm/Attribute:redundancy' => 'High availability~~',
	'Class:Farm/Attribute:redundancy/disabled' => 'The farm is up if all the hypervisors are up~~',
	'Class:Farm/Attribute:redundancy/count' => 'The farm is up if at least %1$s hypervisor(s) is(are) up~~',
	'Class:Farm/Attribute:redundancy/percent' => 'The farm is up if at least %1$s %% of the hypervisors are up~~',
));

//
// Class: VirtualMachine
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:VirtualMachine' => 'Virtuálne zariadenie',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtuálny host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Názov virtuálneho hosta',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Kategória OS',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Názov kategórie OS',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Verzia OS',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Názov OS verzie',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licencia OS',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS licence Názov',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'Procesor',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'Operačná pamäť',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP~~',
	'Class:VirtualMachine/Attribute:managementip+' => '~~',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Zoznam sieťových rozhraní',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '',
));

//
// Class: LogicalVolume
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:LogicalVolume' => 'Logické disky',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Názov',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Popis',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid úroveň',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Veľkosť',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Úložiskový systém',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Názov úložného systému',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servery',
	'Class:LogicalVolume/Attribute:servers_list+' => '',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuálne zariadenia',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '',
));

//
// Class: lnkServerToVolume
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkServerToVolume' => 'väzba - Server / Logický Disk',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Logický Disk',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Názov dielu',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Názov serveru',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Použité miesto',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkVirtualDeviceToVolume' => 'väzba Virtuálne zariadenie / Logický disk',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Logický disk',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Názov dielu',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuálne zariadenie',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Názov virtuálneho zariadenia',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Použité miesto',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkSanToDatacenterDevice' => 'väzba - SAN / Zariadenie',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN prepínač',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Názov SAN prepínaču',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Zariadenie',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Názov zariadenia',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN port',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => '(FC) Port zariadenia',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Tape' => 'Páska',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Názov',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Popis',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Veľkosť',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Pásková knižnica',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Názov knižnice pásiek',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NASFileSystem' => 'NAS Súborový systém',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Názov',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Popis',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid úroveň',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Veľkosť',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Názov NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Software' => 'Softvér',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Názov',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Dodávateľ',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Verzia',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Dokumenty',
	'Class:Software/Attribute:documents_list+' => '',
	'Class:Software/Attribute:type' => 'Typ',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
	'Class:Software/Attribute:type/Value:DBServer+' => '',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => '',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Iný softvér',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC softvér',
	'Class:Software/Attribute:type/Value:PCSoftware+' => '',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web Server',
	'Class:Software/Attribute:type/Value:WebServer+' => '',
	'Class:Software/Attribute:softwareinstance_list' => 'Softvérové inštancie',
	'Class:Software/Attribute:softwareinstance_list+' => '',
	'Class:Software/Attribute:softwarepatch_list' => 'Softvérové záplaty',
	'Class:Software/Attribute:softwarepatch_list+' => '',
	'Class:Software/Attribute:softwarelicence_list' => 'Softvérové licencie',
	'Class:Software/Attribute:softwarelicence_list+' => '',
));

//
// Class: Patch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Patch' => 'Záplata',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Názov',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Zoznam dokumentov',
	'Class:Patch/Attribute:documents_list+' => '',
	'Class:Patch/Attribute:description' => 'Popis',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Typ',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:OSPatch' => 'Záplata OS',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Zariadenia',
	'Class:OSPatch/Attribute:functionalcis_list+' => '',
	'Class:OSPatch/Attribute:osversion_id' => 'Verzia OS',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Názov OS verzie',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SoftwarePatch' => 'Softvérová záplata',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Softvér',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Názov softvéru',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Inštancie softvéru',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '',
));

//
// Class: Licence
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Licence' => 'Licencia',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Názov',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Zoznam dokumentov',
	'Class:Licence/Attribute:documents_list+' => '',
	'Class:Licence/Attribute:org_id' => 'Organizácia',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Názov organizácie',
	'Class:Licence/Attribute:organization_name+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Limit používania',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Popis',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Dátum začiatku',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Dátum ukončenia',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Licenčný kľúč',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Pretrvávajúci',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Nie',
	'Class:Licence/Attribute:perpetual/Value:no+' => '',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Áno',
	'Class:Licence/Attribute:perpetual/Value:yes+' => '',
	'Class:Licence/Attribute:finalclass' => 'Typ',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:OSLicence' => 'Licencia OS',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Verzia OS',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Názov OS verzie',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuálne zariadenia',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '',
	'Class:OSLicence/Attribute:servers_list' => 'Servery',
	'Class:OSLicence/Attribute:servers_list+' => '',
));

//
// Class: SoftwareLicence
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:SoftwareLicence' => 'Softvérová licencia',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Softvér',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Názov softvéru',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Inštancie softvéru',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDocumentToLicence' => 'väzba Dokument/Licencia',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licencia',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Názov licence',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
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
// Class: OSVersion
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:OSVersion' => 'Verzia OS',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Kategória OS',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Názov kategórie OS',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:OSFamily' => 'Kategória OS',
	'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Brand' => 'Značka',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Zariadenia',
	'Class:Brand/Attribute:physicaldevices_list+' => '',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique~~',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists~~',
));

//
// Class: Model
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Značka',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Názov značky',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Typ zariadena',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Zdroj napájania',
	'Class:Model/Attribute:type/Value:PowerSource+' => '',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Diskové pole',
	'Class:Model/Attribute:type/Value:DiskArray+' => '',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Kryt',
	'Class:Model/Attribute:type/Value:Enclosure+' => '',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP Telefón',
	'Class:Model/Attribute:type/Value:IPPhone+' => '',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobilný telefón',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => '',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Sieťové zariadenie',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => '',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => '',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => '',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Periférie',
	'Class:Model/Attribute:type/Value:Peripheral+' => '',
	'Class:Model/Attribute:type/Value:Printer' => 'Tlačiareň',
	'Class:Model/Attribute:type/Value:Printer+' => '',
	'Class:Model/Attribute:type/Value:Rack' => 'stojan (Rack)',
	'Class:Model/Attribute:type/Value:Rack+' => '',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN prepínač',
	'Class:Model/Attribute:type/Value:SANSwitch+' => '',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => '',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Úložiskový systém',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => '',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Pásková knižnica',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '',
	'Class:Model/Attribute:type/Value:Phone' => 'Telephone~~',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telephone~~',
	'Class:Model/Attribute:physicaldevices_list' => 'Zariadenia',
	'Class:Model/Attribute:physicaldevices_list+' => '',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand~~',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand~~',
));

//
// Class: NetworkDeviceType
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NetworkDeviceType' => 'Typ sieťového zariadenia',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Sieťové zariadenia',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '',
));

//
// Class: IOSVersion
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:IOSVersion' => 'Verzia IOSu',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Značka',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Názov značky',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDocumentToPatch' => 'väzba - Dokument / Záplata',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Záplata',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Názov záplaty',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'väzba - Softvérová inštancia / Softvérová záplata',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Softvérová záplata',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Názov softvérovej záplaty',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Softvérová inštancia',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Názov softvérovej inštancie',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkFunctionalCIToOSPatch' => 'väzba - Komponent / Záplata OS',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Záplata OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Názov OS záplaty',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Názov funkčných CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDocumentToSoftware' => 'väzba Dokument / Softvér',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Softvér',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Názov softvéru',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContactToFunctionalCI' => 'väzba - Kontakt / Komponent',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Názov funkčných CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Názov kontaktu',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkDocumentToFunctionalCI' => 'väzba - Dokument / Komponent',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Názov funkčných CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Názov dokumentu',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Subnet' => 'Podsieť',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Popis',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnet name~~',
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => 'Organizácia',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Názov organizácie',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP Adresa',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Maska IP adresy',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs~~',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:VLAN' => 'VLAN~~',
	'Class:VLAN+' => '~~',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:VLAN/Attribute:vlan_tag+' => '~~',
	'Class:VLAN/Attribute:description' => 'Description~~',
	'Class:VLAN/Attribute:description+' => '~~',
	'Class:VLAN/Attribute:org_id' => 'Organization~~',
	'Class:VLAN/Attribute:org_id+' => '~~',
	'Class:VLAN/Attribute:org_name' => 'Organization name~~',
	'Class:VLAN/Attribute:org_name+' => 'Common name~~',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets~~',
	'Class:VLAN/Attribute:subnets_list+' => '~~',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physical network interfaces~~',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '~~',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkSubnetToVLAN' => 'Link Subnet / VLAN~~',
	'Class:lnkSubnetToVLAN+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnet name~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '~~',
));

//
// Class: NetworkInterface
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NetworkInterface' => 'Sieťové rozhranie',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Názov',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Typ',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:IPInterface' => 'IP rozhranie',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP Adresa',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC Adresa',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Komentár',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'Východzia brána IP',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'Maska IP adresy',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Rýchlosť',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:PhysicalInterface' => 'Fyzické rozhranie',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Pripojitelné Zariadenie',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Názov zariadenia',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs~~',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link PhysicalInterface / VLAN~~',
	'Class:lnkPhysicalInterfaceToVLAN+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Physical Interface~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Physical Interface Name~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Device~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Device name~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '~~',
));


//
// Class: LogicalInterface
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:LogicalInterface' => 'Logické rozhranie',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuálne zariadenie',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Názov virtuálneho stroja',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:FiberChannelInterface' => 'Optické rozhranie',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Rýchlosť',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topológia',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Zariadenie',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Názov zariadenia',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'väzba - Komponent / Sieťové zariadenie',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Sieťové zariadenie',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Názov sieťového zariadenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Pripojiteľné zariadenie',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Názov pripojeného zariadenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Sieťový port',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Port zariadenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Typ pripojenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => '',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'väzba - Aplikačné riešenie / Komponent',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Aplikačné riešenie',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Názov aplikačného riešenia',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Názov funkčných CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'väzba - Aplikačné riešenie / Biznis proces',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Biznis proces',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Názov biznisových procesov',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Aplikačné riešenie',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Názov aplikačného riešenia',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
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
// Class: Group
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Group' => 'Skupina',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Názov',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Stav',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementácia',
	'Class:Group/Attribute:status/Value:implementation+' => '',
	'Class:Group/Attribute:status/Value:obsolete' => 'Vyradená',
	'Class:Group/Attribute:status/Value:obsolete+' => '',
	'Class:Group/Attribute:status/Value:production' => 'Produkcia',
	'Class:Group/Attribute:status/Value:production+' => '',
	'Class:Group/Attribute:org_id' => 'Organizácia',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Meno vlastníka',
	'Class:Group/Attribute:owner_name+' => '',
	'Class:Group/Attribute:description' => 'Popis',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Typ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Nadradená skupina',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Meno rodiča',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Prislúchajúce zariadenia',
	'Class:Group/Attribute:ci_list+' => '',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Priateľské meno rodičovskej skupiny',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkGroupToCI' => 'väzba - Skupina / Zariadenie',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Skupina',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Názov',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'Zariadenie',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Názov',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Dôvod',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
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
	'Menu:Application' => 'Aplikácie',
	'Menu:Application+' => '',
	'Menu:DBServer' => 'Databázové servery',
	'Menu:DBServer+' => '',
	'Menu:ConfigManagement' => 'Manažment konfigurácie',
	'Menu:ConfigManagement+' => '',
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
	'Menu:ConfigManagementCI' => 'Konfiguračné položky',
	'Menu:ConfigManagementCI+' => '',
	'Menu:BusinessProcess' => 'Biznisové procesy',
	'Menu:BusinessProcess+' => '',
	'Menu:ApplicationSolution' => 'Aplikačné riešenia',
	'Menu:ApplicationSolution+' => '',
	'Menu:ConfigManagementSoftware' => 'Aplikačný manažment',
	'Menu:Licence' => 'Licencie',
	'Menu:Licence+' => '',
	'Menu:Patch' => 'Záplaty',
	'Menu:Patch+' => '',
	'Menu:ApplicationInstance' => 'Nainštalovaný softvér',
	'Menu:ApplicationInstance+' => '',
	'Menu:ConfigManagementHardware' => 'Manažment infraštruktúry',
	'Menu:Subnet' => 'Podsiete',
	'Menu:Subnet+' => '',
	'Menu:NetworkDevice' => 'Sieťové zariadenia',
	'Menu:NetworkDevice+' => '',
	'Menu:Server' => 'Servery',
	'Menu:Server+' => '',
	'Menu:Printer' => 'Tlačiarne',
	'Menu:Printer+' => '',
	'Menu:MobilePhone' => 'Mobilné telefóny',
	'Menu:MobilePhone+' => '',
	'Menu:PC' => 'Osobné počítače',
	'Menu:PC+' => '',
	'Menu:NewContact' => 'Nový kontakt',
	'Menu:NewContact+' => '',
	'Menu:SearchContacts' => 'Vyhľadať kontakty',
	'Menu:SearchContacts+' => '',
	'Menu:NewCI' => 'Nové CI',
	'Menu:NewCI+' => '',
	'Menu:SearchCIs' => 'Vyhľadať CIs',
	'Menu:SearchCIs+' => '',
	'Menu:ConfigManagement:Devices' => 'Zariadenia',
	'Menu:ConfigManagement:AllDevices' => 'Infraštruktúra',
	'Menu:ConfigManagement:virtualization' => 'Virtualizácia',
	'Menu:ConfigManagement:EndUsers' => 'Koncové užívateľské zariadenia',
	'Menu:ConfigManagement:SWAndApps' => 'Softvér a aplikácie',
	'Menu:ConfigManagement:Misc' => 'Rôzne',
	'Menu:Group' => 'Skupiny CI',
	'Menu:Group+' => '',
	'Menu:ConfigManagement:Shortcuts' => 'Skratky',
	'Menu:ConfigManagement:AllContacts' => 'Všetky kontakty: %1$d',
	'Menu:Typology' => 'Konfiguračná typológia',
	'Menu:Typology+' => '',
	'Menu:OSVersion' => 'OS verzie',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Katalóg softvéru',
	'Menu:Software+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Zhrnutie',
	'Menu:ConfigManagement:Typology' => 'Konfiguračná typológia',

));


// Add translation for Fieldsets

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Server:baseinfo' => 'Všeobecné informácie',
	'Server:Date' => 'Dátum',
	'Server:moreinfo' => 'Viac informácií',
	'Server:otherinfo' => 'Iné informácie',
	'Server:power' => 'Power supply~~',
	'Person:info' => 'Všeobecné informácie',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Upozornenie',
	'Class:Subnet/Tab:IPUsage' => 'Využívanosť IP adries',
	'Class:Subnet/Tab:IPUsage-explain' => 'Rozhrania majúce IP adresu v rozsahu: <em>%1$s</em> do <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Voľné IP adresy',
	'Class:Subnet/Tab:FreeIPs-count' => 'Voľných IP adries: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Tu je extrakt 10 voľných IP adries',
	'Class:Document:PreviewTab' => 'Preview~~',
));

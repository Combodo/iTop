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
 * @author	Erik Bøg <erik@boegmoeller.dk>
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Relation:impacts/Description' => 'Elementer berørt af ...',
	'Relation:impacts/DownStream' => 'Påvrikning ...',
	'Relation:impacts/DownStream+' => 'Elements impacted by~~',
	'Relation:impacts/UpStream' => 'Afhænger af ...',
	'Relation:impacts/UpStream+' => 'Elements impacting~~',
	// Legacy entries
	'Relation:depends on/Description' => 'Elementer, som afhænger af dette element',
	'Relation:depends on/DownStream' => 'Afhænger af ...',
	'Relation:depends on/UpStream' => 'Påvirker ...',
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
	'Class:Document/Attribute:contracts_list' => 'Kontrakter',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Ydelser',
	'Class:Document/Attribute:services_list+' => '',
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
// Class: FunctionalCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:FunctionalCI' => 'Funktionelle CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Navn',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Beskrivelse',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisation',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Organisationsnavn',
	'Class:FunctionalCI/Attribute:organization_name+' => '',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Forretnings kritikalitet',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Høj',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Lav',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Middel',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:move2production' => 'Overgået til produktions dato',
	'Class:FunctionalCI/Attribute:move2production+' => 'Dato for overgang til produktion',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kontakter',
	'Class:FunctionalCI/Attribute:contacts_list+' => '',
	'Class:FunctionalCI/Attribute:documents_list' => 'Dokumenter',
	'Class:FunctionalCI/Attribute:documents_list+' => '',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Applikations løsning',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leverandør kontrakter',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
	'Class:FunctionalCI/Attribute:services_list' => 'Ydelser',
	'Class:FunctionalCI/Attribute:services_list+' => '',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
	'Class:FunctionalCI/Attribute:softwares_list+' => '',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => '',
	'Class:FunctionalCI/Attribute:finalclass' => 'Type',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Active Tickets~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PhysicalDevice' => 'Fysiske enheder',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Seriennummer',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Placering',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Placeringsnavn',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implementering',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Produktion',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Lager',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Mærke',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Mærkennavn',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Modelnavn',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Asset nummer',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Indkøbsdato',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Udløb garanti',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'NB U',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Enheder',
	'Class:Rack/Attribute:device_list+' => '',
	'Class:Rack/Attribute:enclosure_list' => 'Enclosures',
	'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TelephonyCI' => 'Telefoni CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefonnummer',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Phone' => 'Telefon',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:MobilePhone' => 'Mobiltelefon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:IPPhone' => 'IP-Telefon',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ConnectableCI' => 'Forbindbare CI',
	'Class:ConnectableCI+' => 'Fysiske CI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Netværks enheder',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Netværks interfaces',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DatacenterDevice' => 'Datacenterenhed',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Racknavn',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Enclosure navn',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'NB U',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Management IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA kilde',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA kildenavn',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB kilde',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB kildenavn',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC Porte',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:NetworkDevice' => 'Netværks enhed',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Netværks type',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Netværktypenavn',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Enhed',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS Version',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS versionsnavn',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'OS Familie',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OS familienavn',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OS Version',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OS versionsnavn',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'OS Licens',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OS licensnavn',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logical Volumes',
	'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:StorageSystem' => 'Storage-System',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logical Volumes',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '',
));

//
// Class: SANSwitch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SANSwitch' => 'SAN-Switch',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Enhed',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '',
));

//
// Class: TapeLibrary
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:TapeLibrary' => 'Tape-Library',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Bånd',
	'Class:TapeLibrary/Attribute:tapes_list+' => '',
));

//
// Class: NAS
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'NAS filsystem liste',
	'Class:NAS/Attribute:nasfilesystem_list+' => '',
));

//
// Class: PC
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'OS-Familie',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OS familienavn',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OS-Version',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OS versionsnavn',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Type',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => '',
	'Class:PC/Attribute:type/Value:laptop' => 'Bærbar',
	'Class:PC/Attribute:type/Value:laptop+' => '',
));

//
// Class: Printer
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Printer' => 'Printer',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PowerConnection' => 'Strømtilslutning',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PowerSource' => 'Strømkilde',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => '',
));

//
// Class: PDU
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Racknavn',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Power start',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Power start navn',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Peripheral' => 'Perifer enhed',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Enclosure' => 'Enclosure',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Racknavn',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'NB U',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Enhed',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:ApplicationSolution' => 'Anvendelsområde',
	'Class:ApplicationSolution+' => 'Hvilken applikations løsning anvendes den i?',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Forretningsprocesser',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Aktiv',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Impact analysis: configuration of the redundancy~~',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up~~',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up~~',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up~~',
));

//
// Class: BusinessProcess
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:BusinessProcess' => 'Forretningsproces',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Anvendelsområder',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Liste over applikations løsninger',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Aktiv',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SoftwareInstance' => 'Software instans',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'System',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Systemnavn',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Software-Licens',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Software-Licensnavn',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Path',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Aktiv',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '',
));

//
// Class: Middleware
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware-Instans(er)',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '',
));

//
// Class: DBServer
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DBServer' => 'DB Server',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB Schema',
	'Class:DBServer/Attribute:dbschema_list+' => '',
));

//
// Class: WebServer
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:WebServer' => 'Web Server',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Web Applikationer',
	'Class:WebServer/Attribute:webapp_list+' => '',
));

//
// Class: PCSoftware
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PCSoftware' => 'PC-Software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:OtherSoftware' => 'Andet Software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:MiddlewareInstance' => 'Middleware instans',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware navn',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:DatabaseSchema' => 'Datenbase schema',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB-Server',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB servernavn',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:WebApplication' => 'Webapplikation',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Web server',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Web servernavn',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:VirtualDevice' => 'Virtuel enhed',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implementering',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsolet',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Produktiv',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Lager',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logiske Volumes',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '',
));

//
// Class: VirtualHost
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:VirtualHost' => 'Host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtuelle Maskiner',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '',
));

//
// Class: Hypervisor
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Farmnavn',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Servernavn',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Farm' => 'Farm',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisorer',
	'Class:Farm/Attribute:hypervisor_list+' => '',
	'Class:Farm/Attribute:redundancy' => 'High availability~~',
	'Class:Farm/Attribute:redundancy/disabled' => 'The farm is up if all the hypervisors are up~~',
	'Class:Farm/Attribute:redundancy/count' => 'The farm is up if at least %1$s hypervisor(s) is(are) up~~',
	'Class:Farm/Attribute:redundancy/percent' => 'The farm is up if at least %1$s %% of the hypervisors are up~~',
));

//
// Class: VirtualMachine
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:VirtualMachine' => 'Virtuel Maskine',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Hostnavn',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS Familie',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS familiennavn',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS Version',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS versionsnavn',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS Licens',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS licensnavn',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP~~',
	'Class:VirtualMachine/Attribute:managementip+' => '~~',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Netværks interface',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '',
));

//
// Class: LogicalVolume
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:LogicalVolume' => 'Logical Volume',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Navn',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Beskrivelse',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid Level',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Størrelse',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Storage System',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Storage systemnavn',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Server',
	'Class:LogicalVolume/Attribute:servers_list+' => '',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuelle enheder',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '',
));

//
// Class: lnkServerToVolume
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkServerToVolume' => 'Sammenhæng Server/Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume navn',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Server navn',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Størrelse anvendt',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkVirtualDeviceToVolume' => 'Sammenhæng virtuel enhed/volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volume navn',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuel enhed',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Virtuel enhedsnavn',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Størrelse anvendt',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkSanToDatacenterDevice' => 'Sammenhæng SAN/Datacenterenhed',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN Switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN Switchnavn',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Enhed',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Enhedsnavn',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Enhed FC',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Tape' => 'Bånd',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Navn',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Beskrivelse',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Størrelse',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape Library',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tape Library navn',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:NASFileSystem' => 'NAS filsystem',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Navn',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Beskrivelse',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid Level',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Størrelse',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS navn',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Navn',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Producent',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Version',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Dokument',
	'Class:Software/Attribute:documents_list+' => '',
	'Class:Software/Attribute:type' => 'Type',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
	'Class:Software/Attribute:type/Value:DBServer+' => '',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => '',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Andet Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => '',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web server',
	'Class:Software/Attribute:type/Value:WebServer+' => '',
	'Class:Software/Attribute:softwareinstance_list' => 'Software Instanser',
	'Class:Software/Attribute:softwareinstance_list+' => '',
	'Class:Software/Attribute:softwarepatch_list' => 'Software Patches',
	'Class:Software/Attribute:softwarepatch_list+' => '',
	'Class:Software/Attribute:softwarelicence_list' => 'Software Licenser',
	'Class:Software/Attribute:softwarelicence_list+' => '',
));

//
// Class: Patch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Navn',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Dokumenter',
	'Class:Patch/Attribute:documents_list+' => '',
	'Class:Patch/Attribute:description' => 'Beskrivelse',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Type',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:OSPatch' => 'OS-Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Enhed',
	'Class:OSPatch/Attribute:functionalcis_list+' => '',
	'Class:OSPatch/Attribute:osversion_id' => 'OS Version',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'OS versionsnavn',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SoftwarePatch' => 'Software-Patch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software navn',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software Instanser',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '',
));

//
// Class: Licence
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Licence' => 'Licens',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Navn',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Dokumenter',
	'Class:Licence/Attribute:documents_list+' => '',
	'Class:Licence/Attribute:org_id' => 'Ejer',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organisationsnavn',
	'Class:Licence/Attribute:organization_name+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Anvendelses indskrænkninger',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Beskrivelse',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Startdato',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Slutdato',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Licensnøgle',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Fortløbende',
	'Class:Licence/Attribute:perpetual+' => '~~',
	'Class:Licence/Attribute:perpetual/Value:no' => 'nej',
	'Class:Licence/Attribute:perpetual/Value:no+' => '',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'ja',
	'Class:Licence/Attribute:perpetual/Value:yes+' => '',
	'Class:Licence/Attribute:finalclass' => 'Type',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:OSLicence' => 'OS-Licens',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'OS-Version',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'OS versionsnavn',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuelle Maskiner',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '',
	'Class:OSLicence/Attribute:servers_list' => 'Server',
	'Class:OSLicence/Attribute:servers_list+' => '',
));

//
// Class: SoftwareLicence
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:SoftwareLicence' => 'Software-Licens',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software navn',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software Instanser',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDocumentToLicence' => 'Sammenhæng Dokument/Licens',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licens',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Licensnavn',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Dokumentnavn',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
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
// Class: OSVersion
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:OSVersion' => 'OS-Version',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS-Familie',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS familienavn',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:OSFamily' => 'OS-Familie',
	'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Brand' => 'Mærke',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Fysisk enhed',
	'Class:Brand/Attribute:physicaldevices_list+' => '',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique~~',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists~~',
));

//
// Class: Model
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Mærke',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Mærkenavn',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Enhedstype',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Strømforsyning',
	'Class:Model/Attribute:type/Value:PowerSource+' => '',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Diskarray',
	'Class:Model/Attribute:type/Value:DiskArray+' => '',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => '',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP Telefon',
	'Class:Model/Attribute:type/Value:IPPhone+' => '',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobiltelefon',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => '',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Netværks enhed',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => '',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => '',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => '',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Peripher enhed',
	'Class:Model/Attribute:type/Value:Peripheral+' => '',
	'Class:Model/Attribute:type/Value:Printer' => 'Printer',
	'Class:Model/Attribute:type/Value:Printer+' => '',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => '',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN Switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => '',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => '',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Storage System',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => '',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tape Library',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '',
	'Class:Model/Attribute:type/Value:Phone' => 'Telephone~~',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telephone~~',
	'Class:Model/Attribute:physicaldevices_list' => 'Fyisk enhed',
	'Class:Model/Attribute:physicaldevices_list+' => '',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand~~',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand~~',
));

//
// Class: NetworkDeviceType
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:NetworkDeviceType' => 'Netværksenhed type',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Netværks enheder',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '',
));

//
// Class: IOSVersion
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:IOSVersion' => 'IOS-Version',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Mærke',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Mærkenavn',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDocumentToPatch' => 'Sammenhæng Dokument/Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Patch navn',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Dokumentnavn',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Sammenhæng Software-Instans/Softeware-Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Software Patch navn',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Software Instans',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Software Instans navn',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Sammenhæng FunctionalCI/OS-Patch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS Patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS-Patch-Navn',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'FunctionalCI navn',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDocumentToSoftware' => 'Sammenhæng Dokument/Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software navn',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Dokumentnavn',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkContactToFunctionalCI' => 'Sammenhæng Kontakt/FunctionalCI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI navn',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Kontakt navn',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkDocumentToFunctionalCI' => 'Sammenhæng Dokument/FunctionalCI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI navn',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Dokument navn',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Beskrivelse',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnet name~~',
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => 'Organisation',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Navn',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Subnetmaske',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs~~',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:NetworkInterface' => 'Netværks interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Navn',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Type',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:IPInterface' => 'IP-Interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP Adresse',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC Adresse',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Kommentar',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP Gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP Maske',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Hastighed',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:PhysicalInterface' => 'Fysiske interface',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Enhed',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Enhedsnavn',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs~~',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:LogicalInterface' => 'Logiske interface',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuel maskine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtuel maskinnavn',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:FiberChannelInterface' => 'Fiber Channel Interface',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Hastighed',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologi',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Enhed',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Enhedsnavn',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Sammenhæng ConnectableCI/NetworkDevice',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Netværks enhed',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Netværksenhed navn',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Forbunden enhed',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Forbundne enheder navn',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Netværksport',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Enhedsport',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Forbindelsestype',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => '',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Sammenhæng Applikationssuite/FunctionalCI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Applikations løsning',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Applikations løsning navn',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI navn',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Sammenhæng Applikationssuite/Forretningsproces',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Forretningsproces',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Forretningsproces navn',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Applikations løsning',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Applikations løsning navn',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
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
// Class: Group
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Group' => 'Gruppe',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Navn',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementering',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementering',
	'Class:Group/Attribute:status/Value:obsolete' => 'Forældet',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Forældet',
	'Class:Group/Attribute:status/Value:production' => 'Produktion',
	'Class:Group/Attribute:status/Value:production+' => 'Produktion',
	'Class:Group/Attribute:org_id' => 'Organisation',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Navn',
	'Class:Group/Attribute:owner_name+' => 'Ejer navn',
	'Class:Group/Attribute:description' => 'Beskrivelse',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Typ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Parent id',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Navn',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Forbundne CIs',
	'Class:Group/Attribute:ci_list+' => '',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Parent Gruppe',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkGroupToCI' => 'Gruppe/CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Gruppe',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Navn',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Navn',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Årsag',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
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
	'Menu:Application' => 'Anvendelse',
	'Menu:Application+' => 'Alle Anvendelser',
	'Menu:DBServer' => 'Database server',
	'Menu:DBServer+' => 'Database server',
	'Menu:ConfigManagement' => 'Configuration Management',
	'Menu:ConfigManagement+' => 'Configuration Management',
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
	'Menu:ConfigManagementCI' => 'Configuration Items',
	'Menu:ConfigManagementCI+' => 'Configuration Items',
	'Menu:BusinessProcess' => 'Forretnings proces',
	'Menu:BusinessProcess+' => 'Alle forretnings processer',
	'Menu:ApplicationSolution' => 'Applikations løsning',
	'Menu:ApplicationSolution+' => 'Alle Applikations løsninger',
	'Menu:ConfigManagementSoftware' => 'Anvendelses Management',
	'Menu:Licence' => 'Licenser',
	'Menu:Licence+' => 'Alle Licenser',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'Alle Patches',
	'Menu:ApplicationInstance' => 'Installeret Software',
	'Menu:ApplicationInstance+' => 'Applikations- og database server',
	'Menu:ConfigManagementHardware' => 'Infrastruktur Management',
	'Menu:Subnet' => 'Subnet',
	'Menu:Subnet+' => 'Alle Subnet',
	'Menu:NetworkDevice' => 'Netværksenhed',
	'Menu:NetworkDevice+' => 'Alle Netværksenheder',
	'Menu:Server' => 'Server',
	'Menu:Server+' => 'Alle Servere',
	'Menu:Printer' => 'Printer',
	'Menu:Printer+' => 'Alle Printere',
	'Menu:MobilePhone' => 'Mobiltelefon',
	'Menu:MobilePhone+' => 'Alle Mobiltelefoner',
	'Menu:PC' => 'PC',
	'Menu:PC+' => 'Alle PCer',
	'Menu:NewContact' => 'Ny Kontakt',
	'Menu:NewContact+' => 'Ny Kontakt',
	'Menu:SearchContacts' => 'Søg efter kontakter',
	'Menu:SearchContacts+' => 'Søg efter kontakter',
	'Menu:NewCI' => 'Ny CI',
	'Menu:NewCI+' => 'Ny CI',
	'Menu:SearchCIs' => 'Søg efter CIs',
	'Menu:SearchCIs+' => 'Søg efter Content Items',
	'Menu:ConfigManagement:Devices' => 'Enheder',
	'Menu:ConfigManagement:AllDevices' => 'Alle Enheder',
	'Menu:ConfigManagement:virtualization' => 'Virtualisering',
	'Menu:ConfigManagement:EndUsers' => 'Slutbruger enheder',
	'Menu:ConfigManagement:SWAndApps' => 'Software og anvendelse',
	'Menu:ConfigManagement:Misc' => 'Diverse',
	'Menu:Group' => 'Gruppe af CIs',
	'Menu:Group+' => 'Gruppe af CIs',
	'Menu:ConfigManagement:Shortcuts' => 'Genveje',
	'Menu:ConfigManagement:AllContacts' => 'Alle Kontakter: %1$d',
	'Menu:Typology' => 'Typologi-Konfiguration',
	'Menu:Typology+' => '',
	'Menu:OSVersion' => 'OS versioner',
	'Menu:OSVersion+' => '~~',
	'Menu:Software' => 'Software Katalog',
	'Menu:Software+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Sammenfatning',
	'Menu:ConfigManagement:Typology' => 'Typologi Konfiguration',

));


// Add translation for Fieldsets

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Server:baseinfo' => 'Almindelig Informationen',
	'Server:Date' => 'Dato',
	'Server:moreinfo' => 'Yderligere Information',
	'Server:otherinfo' => 'Øvrig Information',
	'Server:power' => 'Power supply~~',
	'Person:info' => 'Almindelig Information',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Underretning',
	'Class:Subnet/Tab:IPUsage' => 'IP Brug',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces der har en IP i området: <em>%1$s</em> til <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Ledige IP',
	'Class:Subnet/Tab:FreeIPs-count' => 'Ledige IP: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Her er et udtræk af 10 ledige IP adresser',
	'Class:Document:PreviewTab' => 'Preview~~',
));

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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Relation:impacts/Description' => 'Elementen hebben impact op',
	'Relation:impacts/VerbUp' => 'Impact...',
	'Relation:impacts/VerbDown' => 'Elementen...',
	'Relation:depends on/Description' => 'Elementen waarvan dit element afhankelijk van is',
	'Relation:depends on/VerbUp' => 'Is afhankelijk van...',
	'Relation:depends on/VerbDown' => 'Impacts...',
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Organization' => 'Organisatie',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Naam',
	'Class:Organization/Attribute:name+' => 'Gemeenschappelijke naam',
	'Class:Organization/Attribute:code' => 'Code',
	'Class:Organization/Attribute:code+' => 'Organisatie code (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Actief',
	'Class:Organization/Attribute:status/Value:active+' => 'Actief',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactief',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactief',
	'Class:Organization/Attribute:parent_id' => 'Moeder organisatie',
	'Class:Organization/Attribute:parent_id+' => 'Moeder organisatie',
	'Class:Organization/Attribute:parent_name' => 'Moeder naam',
	'Class:Organization/Attribute:parent_name+' => 'Naam van de moeder organisatie',
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Moeder',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Moeder organisatie',
));

//
// Class: Location
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Location' => 'Locatie',
	'Class:Location+' => 'Een type locatie zoals: Regio, Land, Stad, Gebouw, Verdieping, Kamer,  ,...',
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
	'Class:Location/Attribute:org_name' => 'Naam van de organisatie',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adres',
	'Class:Location/Attribute:address+' => 'Locatie van de organisatie',
	'Class:Location/Attribute:postal_code' => 'Postcode',
	'Class:Location/Attribute:postal_code+' => 'Postcode van de organisatie',
	'Class:Location/Attribute:city' => 'Stad',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Apparaten',
	'Class:Location/Attribute:physicaldevice_list+' => 'Alle apparaten die zich bevinden op deze locatie',
	'Class:Location/Attribute:person_list' => 'Contacten',
	'Class:Location/Attribute:person_list+' => 'Alle contacten die zich bevinden op deze locatie',
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
	'Class:Contact/Attribute:org_name' => 'Naam van de organisatie',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefoon',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notificatie',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'nee',
	'Class:Contact/Attribute:notify/Value:no+' => 'nee',
	'Class:Contact/Attribute:notify/Value:yes' => 'ja',
	'Class:Contact/Attribute:notify/Value:yes+' => 'ja',
	'Class:Contact/Attribute:function' => 'Functie',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => 'Alle configuratie items die gelinkt aan dit team',
	'Class:Contact/Attribute:finalclass' => 'Contact Type',
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
	'Class:Person/Attribute:employee_number' => 'Burgerservicenummer',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobiele telefoon',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Locatie',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Locatie waarbij de persoon werkzaam is',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Naam van de manager',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Teams',
	'Class:Person/Attribute:team_list+' => 'Alle teams waarvan deze persoon lid is',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'Alle tickets waarvan deze persoon de aanvrager is',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Volledige naam van de manager',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
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
	'Class:Team/Attribute:tickets_list+' => 'Alle tickets die gelinkt zijn aan dit team',
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
	'Class:Document/Attribute:org_name' => 'Naam van de organisatie',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Document type',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Naam van het document type',
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
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => 'Alle configuratie items gelinkt aan dit document',
	'Class:Document/Attribute:contracts_list' => 'Contracten',
	'Class:Document/Attribute:contracts_list+' => 'Alle contracten gelinkt aan dit document',
	'Class:Document/Attribute:services_list' => 'Diensten',
	'Class:Document/Attribute:services_list+' => 'Alle diensten gelinkt aan dit document.',
	'Class:Document/Attribute:finalclass' => 'Document Type',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentFile' => 'Document Bestand',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Bestand',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentNote' => 'Document Notitie',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Tekst',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentWeb' => 'Document Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FunctionalCI' => 'Functionele CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Naam',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Omschrijving',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisatie',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Naam van de organisatie',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Gemeenschappelijke naam',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Bedrijfskritisch',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'hoog',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'hoog',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'laag',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'laag',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'normaal',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'normaal',
	'Class:FunctionalCI/Attribute:move2production' => 'Verplaats naar productie datum',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contacten',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Alle contacten voor dit configuratie item',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documenten',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Alle documenten gelinkt aan dit configuratie item.',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Applicatie oplossingen',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Alle applicatie oplossingen die afhankelijk zijn van dit configuratie item',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leveranciers contracten',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Alle leveranciers contracten voor dit configuratie item',
	'Class:FunctionalCI/Attribute:services_list' => 'Diensten',
	'Class:FunctionalCI/Attribute:services_list+' => 'Alle diensten die impact hebben op dit configuratie item',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Alle software geïnstalleerd op dit configuratie item',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Alle tickets voor dit configuratie item',
	'Class:FunctionalCI/Attribute:finalclass' => 'CI Type',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: PhysicalDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PhysicalDevice' => 'Fysieke Apparaat',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Serie nummer',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Locatie',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Naam van de locatie',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'implementatie',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'implementatie',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'overbodig',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'overbodig',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'productie',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'productie',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'voorraad',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'voorraad',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Merk',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Merk naam',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Model naam',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Asset nummer',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Aankoop datum',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Einde van garantieperiode',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Rack eenheden',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Apparaten',
	'Class:Rack/Attribute:device_list+' => 'Alle fysieke apparaten die zich bevinden in dit rack',
	'Class:Rack/Attribute:enclosure_list' => 'Enclosures',
	'Class:Rack/Attribute:enclosure_list+' => 'Alle enclosures in dit rack',
));

//
// Class: TelephonyCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TelephonyCI' => 'Telefonie CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefoon nummer',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Phone' => 'Telefoon',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:MobilePhone' => 'Mobiele Telefoon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IPPhone' => 'IP Telefoon',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ConnectableCI' => 'Aansluitbaar CI',
	'Class:ConnectableCI+' => 'Fysieke CI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Netwerk apparaten',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Alle netwerk apparaten die zijn verbonden met dit apparaat',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Netwerk interfaces',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Alle fysieke netwerk interfaces',
));

//
// Class: DatacenterDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DatacenterDevice' => 'Datacenter Apparaat',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Rack naam',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Enclosure naam',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Rack eenheden',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Management ip',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA bron',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA bron naam',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB bron',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB bron naam',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC ports',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Alle fiber channel interfaces voor dit apparaat',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Alle SAN switches die verbonden zijn met dit apparaat',
));

//
// Class: NetworkDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkDevice' => 'Netwerk Apparaat',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Netwerk type',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Naam van het Netwerk type ',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Apparaten',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Alle apparaten die verbonden zijn met dit netwerk apparaat',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS versie',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS versie naam',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'OS familie',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OS familie naam',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OS versie',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OS versie naam',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'OS ',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OS licentie name',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logische volumes',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Alle logische volumes die verbonden zijn met deze server',
));

//
// Class: StorageSystem
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:StorageSystem' => 'Opslag Systeem',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logische volumes',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Alle logische volumes in dit opslag systeem',
));

//
// Class: SANSwitch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SANSwitch' => 'SAN Switch',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Apparaten',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'All the devices connected to this SAN switch',
));

//
// Class: TapeLibrary
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TapeLibrary' => 'Tape Bibliotheek',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Tapes',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Alle tapes in de tape library',
));

//
// Class: NAS
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Bestandssysteem',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Alle bestandsystemen in deze NAS',
));

//
// Class: PC
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'OS familie',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OS familie naam',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OS versie',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OS versie naam',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Type',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'desktop',
	'Class:PC/Attribute:type/Value:laptop' => 'laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'laptop',
));

//
// Class: Printer
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Printer' => 'Printer',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PowerConnection' => 'Stroom Connectie',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PowerSource' => 'Stroom bron',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'Alle PDUs die gebruikt worden door deze stroom bron',
));

//
// Class: PDU
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Rack naam',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Power start',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Power start naam',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Peripheral' => 'Randapparatuur',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Enclosure' => 'Enclosure',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Rack naam',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Rack eenheden',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Apparaten',
	'Class:Enclosure/Attribute:device_list+' => 'Alle apparaten in deze enclosure',
));

//
// Class: ApplicationSolution
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ApplicationSolution' => 'Applicatie Oplossing',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Alle configuratie items dat deze applicatie oplossing tot stand brengt',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Bedrijfsprocessen',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Alle bedrijfsprocessen die afhankelijk zijn van deze applicatie oplossing',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'actief',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'actief',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'inactief',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'inactief',
));

//
// Class: BusinessProcess
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:BusinessProcess' => 'Bedrijfsproces',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Applicatie oplossing',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'All the application solutions that impact this business process',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'actief',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'actief',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'inactief',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'inactief',
));

//
// Class: SoftwareInstance
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwareInstance' => 'Software Instantie',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Systeem',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Systeem naam',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software naam',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Software licentie',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Software licentie naam',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Pad',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'actief',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'actief',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'inactief',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'inactief',
));

//
// Class: Middleware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware instanties',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Alle middleware instanties die geleverd worden door deze middleware',
));

//
// Class: DBServer
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DBServer' => 'DB Server',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB schemas',
	'Class:DBServer/Attribute:dbschema_list+' => 'Alle  database schemas voor deze DB server',
));

//
// Class: WebServer
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:WebServer' => 'Web server',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Web applicaties',
	'Class:WebServer/Attribute:webapp_list+' => 'Alle  web applicaties die beschikbaar zijn voor deze web server',
));

//
// Class: PCSoftware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PCSoftware' => 'PC Software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OtherSoftware' => 'Overige Software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:MiddlewareInstance' => 'Middleware Instantie',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware naam',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DatabaseSchema' => 'Database Schema',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB server',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB server naam',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:WebApplication' => 'Web Applicatie',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Web server',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Web server naam',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualDevice' => 'Virtuele Apparaat',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'implementatie',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'implementatie',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'overbodig',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'overbodig',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'productie',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'productie',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'stock',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'stock',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logical volumes',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'All the logical volumes used by this device',
));

//
// Class: VirtualHost
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualHost' => 'Virtual Host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtual machines',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'All the virtual machines hosted by this host',
));

//
// Class: Hypervisor
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Farm name',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Server name',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Farm' => 'Farm',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'All the hypervisors that compose this farm',
));

//
// Class: VirtualMachine
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualMachine' => 'Virtuele Machine',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtual host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Virtual host name',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS familie',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS familie naam',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS version',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS version name',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS licence',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS licence name',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Network Interfaces',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'All the logical network interfaces',
));

//
// Class: LogicalVolume
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:LogicalVolume' => 'Logisch Volume',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Naam',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Omschrijving',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid niveau',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Grootte',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Opslag systeem',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Naam van de opslag systeem',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servers',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Alle  servers die dit volume gebruiken',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuele apparaten',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Alle virtuele apparaten die dit volume gebruiken',
));

//
// Class: lnkServerToVolume
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkServerToVolume' => 'Link Server / Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume naam',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Server naam',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Grootte gebruikt',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Virtual Device / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volume naam',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuele apparaat',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Naam van het virtuele apparaat',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Grootte gebruikt',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Datacenter Device',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN switch naam',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Apparaat',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Apparaat naam',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN fc',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Apparaat fc',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Tape' => 'Tape',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Naam',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Omschrijving',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Grootte',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape Bibliotheek',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tape Bibliotheek naam',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NASFileSystem' => 'NAS Bestands Systeem',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Naam',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Omschrijving',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid niveau',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Grootte',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS naam',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Naam',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'verkoper',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Versie',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documenten',
	'Class:Software/Attribute:documents_list+' => 'Alle  documenten gelinkt aan deze software',
	'Class:Software/Attribute:type' => 'Type',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Overige Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Overige Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web Server',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web Server',
	'Class:Software/Attribute:softwareinstance_list' => 'Software Instanties',
	'Class:Software/Attribute:softwareinstance_list+' => 'Alle  software instanties voor deze sofware',
	'Class:Software/Attribute:softwarepatch_list' => 'Software Patches',
	'Class:Software/Attribute:softwarepatch_list+' => 'Alle  patches voor deze software',
	'Class:Software/Attribute:softwarelicence_list' => 'Software Licenties',
	'Class:Software/Attribute:softwarelicence_list+' => 'Alle licenties voor deze software',
));

//
// Class: Patch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Naam',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documenten',
	'Class:Patch/Attribute:documents_list+' => 'Alle documenten gelinkt aan deze patch',
	'Class:Patch/Attribute:description' => 'Omschrijving',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Type',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSPatch' => 'OS Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Apparaten',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Alle systemen waar deze patch is geïnstalleerd',
	'Class:OSPatch/Attribute:osversion_id' => 'OS versie',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'OS versie naam',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwarePatch' => 'Software Patch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software naam',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software instanties',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Alle systemen waar deze patch is geïnstalleerd',
));

//
// Class: Licence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Licence' => 'Licentie',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Naam',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Documenten',
	'Class:Licence/Attribute:documents_list+' => 'All documenten gelinkt aan deze licentie',
	'Class:Licence/Attribute:org_id' => 'Organization',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organisatie naam',
	'Class:Licence/Attribute:organization_name+' => 'Gemeenschappelijke naam',
	'Class:Licence/Attribute:usage_limit' => 'Gebruikslimiet',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Omschrijving',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Start datum',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Eind datum',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Code',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Lifetime',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'nee',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'nee',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'ja',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'ja',
	'Class:Licence/Attribute:finalclass' => 'Type',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSLicence' => 'OS Licentie',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'OS versie',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'OS version naam',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuele machines',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Alle  virtual machines die gebruik maken van deze licentie',
	'Class:OSLicence/Attribute:servers_list' => 'servers',
	'Class:OSLicence/Attribute:servers_list+' => 'Alle servers die gebruik maken van deze licentie',
));

//
// Class: SoftwareLicence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwareLicence' => 'Software Licentie',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software naam',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software instanties',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Alle systemen die gebruik maken van deze licentie',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToLicence' => 'Link Document / Licentie',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licentie',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Licentie naam',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Document naam',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Typology' => 'Typology',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Naam',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Type',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: OSVersion
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
		'Class:OSVersion' => 'OS Versie',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS familie',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS familie naam',
	'Class:OSVersion/Attribute:osfamily_name+' => '',

));

//
// Class: OSFamily
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSFamily' => 'OS Familie',
	'Class:OSFamily+' => '',
));

//
// Class: DocumentType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DocumentType' => 'Document Type',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ContactType' => 'Contact Type',
	'Class:ContactType+' => '',
));

//
// Class: Brand
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Brand' => 'Merk',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Fysieke apparaten',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Alle fysieke apparaten die corresponderen met dit merk',
));

//
// Class: Model
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Merk',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Merk naam',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Apparaat type',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Stroom Bron',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Stroom Bron',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Schijven Set',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Schijven Set',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP Telefoon',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP Telefoon',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobiele Telefoon',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Mobiele Telefoon',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Netwerk Apparaat',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Netwerk Apparaat',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Randapparatuur',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Randapparatuur',
	'Class:Model/Attribute:type/Value:Printer' => 'Printer',
	'Class:Model/Attribute:type/Value:Printer+' => 'Printer',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN switch',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => 'Server',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Opslag Systeem',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Opslag Systeem',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tape Bibliotheek',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tape Bibliotheek',
	'Class:Model/Attribute:type/Value:Telephone' => 'Telefoon',
	'Class:Model/Attribute:type/Value:Telephone+' => 'Telefoon',
	'Class:Model/Attribute:physicaldevices_list' => 'Physical devices',
	'Class:Model/Attribute:physicaldevices_list+' => 'Alle fysieke apparaten die corresponderen met dit model',
));

//
// Class: NetworkDeviceType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkDeviceType' => 'Network Device Type',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Netwerk apparaten',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Alle netwerk apparaten die corresponderen met dit type',
));

//
// Class: IOSVersion
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IOSVersion' => 'IOS Versie',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Merk',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Merk naam',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToPatch' => 'Link Document / Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Patch naam',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Document naam',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Software Instance / Software Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Software patch naam',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Software instantie',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Software instantie naam',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Link FunctionalCI / OS patch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS patch naam',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Functionalci naam',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToSoftware' => 'Link Document / Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software naam',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Document naam',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contact / FunctionalCI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Functionalci naam',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Contact naam',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link Document / FunctionalCI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Functionalci naam',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Document naam',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Omschrijving',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnet naam',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organisatie',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Naam',
	'Class:Subnet/Attribute:org_name+' => 'Gemeenschappelijke naam',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Label',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Omschrijving',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organisatie',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Organisatie naam',
	'Class:VLAN/Attribute:org_name+' => 'Gemeenschappelijke naam',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fysieke netwerk interfaces',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSubnetToVLAN' => 'Link Subnet / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnet naam',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Label',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkInterface' => 'Netwerk Interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Naam',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Type',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IPInterface' => 'IP Interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP address',
	'Class:IPInterface/Attribute:ipaddress+' => '',
	'Class:IPInterface/Attribute:macaddress' => 'MAC address',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Commentaar',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP mask',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Snelheid',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PhysicalInterface' => 'Fysieke Interface',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Apparaat',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Apparaat naam',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link PhysicalInterface / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Fysieke Interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Fysieke Interface Naam',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Apparaat',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Apparaat naam',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Label',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:LogicalInterface' => 'Logical Interface',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtual machine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtual machine name',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FiberChannelInterface' => 'Fiber Channel Interface',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Snelheid',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologie',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Apparaat',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Apparaat naam',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / NetwerkApparaat',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Netwerk apparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Netwerk apparaat naam',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Verbonden apparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Verbonden apparaat naam',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Netwerk poort',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Apparaat poort',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Connectie type',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'up link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link ApplicatieOplossing / FunctioneleCI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Applicatie oplossing',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Applicatie oplossing naam',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Functioneleci',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Functioneleci name',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Link ApplicationSolution / Bedrijfsproces',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Bedrijfsproces',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Bedrijfsproces naam',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Applicatie oplossing',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Applicatie oplossing naam',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkPersonToTeam' => 'Link Persoon / Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Team naam',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Persoon',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Persoon naam',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Role naam',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Class: Group
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Group' => 'Groep',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Naam',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:Group/Attribute:status/Value:obsolete' => 'Verouderd',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Verouderd',
	'Class:Group/Attribute:status/Value:production' => 'Productie',
	'Class:Group/Attribute:status/Value:production+' => 'Productie',
	'Class:Group/Attribute:org_id' => 'Organisatie',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Naam',
	'Class:Group/Attribute:owner_name+' => 'Gemeenschappelijke naam',
	'Class:Group/Attribute:description' => 'Omschrijving',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Type',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Hoofd Groep',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Naam',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Gelinkte CIs',
	'Class:Group/Attribute:ci_list+' => 'Alle configuratie items gelinkt aan deze groep',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Moeder Groep',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkGroupToCI' => 'Link Groep / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Groep',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Naam',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Naam',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Reden',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Application Menu
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:DataAdministration' => 'Data administratie',
	'Menu:DataAdministration+' => 'Data administratie',
	'Menu:Catalogs' => 'Catalogus',
	'Menu:Catalogs+' => 'Data typen',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV import',
	'Menu:CSVImport+' => 'Grootschalige aanmaak of update',
	'Menu:Organization' => 'Organisaties',
	'Menu:Organization+' => 'Alle organisaties',
	'Menu:Application' => 'Applicaties',
	'Menu:Application+' => 'Alle Applicaties',
	'Menu:DBServer' => 'Database servers',
	'Menu:DBServer+' => 'Database servers',
	'Menu:ConfigManagement' => 'Configuratie Management',
	'Menu:ConfigManagement+' => 'Configuratie Management',
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
	'Menu:ConfigManagementCI' => 'Configuratie items',
	'Menu:ConfigManagementCI+' => 'Configuratie items',
	'Menu:BusinessProcess' => 'Bedrijfsprocessen',
	'Menu:BusinessProcess+' => 'Alle bedrijfsprocessen',
	'Menu:ApplicationSolution' => 'Applicatie oplossing',
	'Menu:ApplicationSolution+' => 'Alle applicatie oplossingen',
	'Menu:ConfigManagementSoftware' => 'Applicatie management',
	'Menu:Licence' => 'Licenties',
	'Menu:Licence+' => 'Alle licenties',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'Alle patches',
	'Menu:ApplicationInstance' => 'Geïnstalleerde software',
	'Menu:ApplicationInstance+' => 'Applicaties en database servers',
	'Menu:ConfigManagementHardware' => 'Infrastructuur management',
	'Menu:Subnet' => 'Subnets',
	'Menu:Subnet+' => 'Alle subnets',
	'Menu:NetworkDevice' => 'Netwerk apparaten',
	'Menu:NetworkDevice+' => 'Alle network apparaten',
	'Menu:Server' => 'Servers',
	'Menu:Server+' => 'Alle servers',
	'Menu:Printer' => 'Printers',
	'Menu:Printer+' => 'Alle printers',
	'Menu:MobilePhone' => 'Mobiele telefoons',
	'Menu:MobilePhone+' => 'Alle mobiele telefoons',
	'Menu:PC' => 'Personal computers',
	'Menu:PC+' => 'Alle Personal computers',
	'Menu:NewContact' => 'Nieuw contact',
	'Menu:NewContact+' => 'Nieuw contact',
	'Menu:SearchContacts' => 'Zoeken naar contacten',
	'Menu:SearchContacts+' => 'Zoeken naar contacten',
	'Menu:NewCI' => 'Nieuw CI',
	'Menu:NewCI+' => 'Nieuw CI',
	'Menu:SearchCIs' => 'Zoek naar CIs',
	'Menu:SearchCIs+' => 'Zoek naar CIs',
	'Menu:ConfigManagement:Devices' => 'Apparaten',
	'Menu:ConfigManagement:AllDevices' => 'Infrastructuur',
	'Menu:ConfigManagement:virtualization' => 'Virtualisatie',
	'Menu:ConfigManagement:EndUsers' => 'Eindgebruiker apparaten',
	'Menu:ConfigManagement:SWAndApps' => 'Software en applicaties',
	'Menu:ConfigManagement:Misc' => 'Diversen',
	'Menu:Group' => 'Groepen van CIs',
	'Menu:Group+' => 'Groepen van CIs',
	'Menu:ConfigManagement:Shortcuts' => 'Snelkoppelingen',
	'Menu:ConfigManagement:AllContacts' => 'Alle contacten: %1$d',
	'Menu:Typology' => 'Typologie configuratie',
	'Menu:Typology+' => 'Typologie configuratie',
	'Menu:OSVersion' => 'OS versies',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Software catalogus',
	'Menu:Software+' => 'Software catalogus',
	'UI_WelcomeMenu_AllConfigItems' => 'Samenvatting',
	'Menu:ConfigManagement:Typology' => 'Typologie configuratie',

));


// Add translation for Fieldsets

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Server:baseinfo' => 'Globale informatie',
	'Server:Date' => 'Datum',
	'Server:moreinfo' => 'Meer informatie',
	'Server:otherinfo' => 'Andere informatie',
	'Person:info' => 'Globale informatie',
	'Person:notifiy' => 'Notificatie',
	'Class:Subnet/Tab:IPUsage' => 'IP Usage',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Free IPs',
	'Class:Subnet/Tab:FreeIPs-count' => 'Free IPs: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Here is an extract of 10 free IP addresses',
	'Class:Document:PreviewTab' => 'Preview',
));
?>

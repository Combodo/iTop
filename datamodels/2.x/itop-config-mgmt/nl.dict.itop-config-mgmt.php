<?php
// Copyright (C) 2010-2019 Combodo SARL
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
 * @author Hipska (2018, 2019)
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Relation:impacts/Description' => 'Elementen hebben impact op',
	'Relation:impacts/DownStream' => 'Impact op...',
	'Relation:impacts/DownStream+' => 'Elementen hebben impact op',
	'Relation:impacts/UpStream' => 'Is afhankelijk van...',
	'Relation:impacts/UpStream+' => 'Elementen waar dit object impact op heeft',
	// Legacy entries
	'Relation:depends on/Description' => 'Elementen waarvan dit object afhankelijk van is',
	'Relation:depends on/DownStream' => 'Is afhankelijk van...',
	'Relation:depends on/UpStream' => 'Impact op...',
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
	'Class:Document/Attribute:contracts_list' => 'Contracten',
	'Class:Document/Attribute:contracts_list+' => 'Alle contracten gerelateerd aan dit document',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Alle services gerelateerd aan dit document.',
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
// Class: FunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FunctionalCI' => 'Functioneel CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Naam',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Omschrijving',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisatie',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Naam organisatie',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Naam organisatie',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Bedrijfskritisch',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'Hoog',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'Hoog',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'Laag',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'Laag',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'Normaal',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'Normaal',
	'Class:FunctionalCI/Attribute:move2production' => 'Datum ingebruikname',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contacten',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Alle contacten gelinkt aan dit configuratie-item',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documenten',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Alle documenten gelinkt aan dit configuratie-item.',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Applicatieoplossingen',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Alle applicatieoplossingen die afhankelijk zijn van dit configuratie-item',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leverancierscontracten',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Alle leverancierscontracten voor dit configuratie-item',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => 'Alle services die impact hebben op dit configuratie-item',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Alle software geïnstalleerd op dit configuratie-item',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Alle tickets voor dit configuratie-item',
	'Class:FunctionalCI/Attribute:finalclass' => 'Subklasse CI',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Actieve tickets',
));

//
// Class: PhysicalDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PhysicalDevice' => 'Fysieke Apparaat',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Serienummer',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Locatie',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Naam locatie',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Buiten dienst',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'Buiten dienst',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Productie',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'Productie',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Voorraad',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'Voorraad',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Merk',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Naam merk',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Naam model',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Assetnummer',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Aankoopdatum',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Einde garantieperiode',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Rackeenheden',
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
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefoonnummer',
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
	'Class:MobilePhone' => 'Mobiele telefoon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN-code',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IPPhone' => 'IP-telefoon',
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
	'Class:ConnectableCI+' => 'Fysiek CI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Netwerkapparaten',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Alle netwerkapparaten die verbonden zijn met dit apparaat',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Netwerkinterfaces',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Alle fysieke netwerkinterfaces',
));

//
// Class: DatacenterDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DatacenterDevice' => 'Datacenterapparaat',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Naam rack',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Naam enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Rackeenheden',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Management IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Stroombron A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Naam stroombron A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Stroombron B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Naam stroombron B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC-poorten',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Alle fiber channel-interfaces voor dit apparaat',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Alle SAN-switches die verbonden zijn met dit apparaat',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundantie',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Het toestel werkt zodra stroombron A of B beschikbaar is',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Het toestel werkt zodra alle stroomverbindingen beschikbaar zijn',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Het toestel werkt zodra minstens %1$s %% van de stroomverbindingen beschikbaar is',
));

//
// Class: NetworkDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkDevice' => 'Netwerkapparaat',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Soort netwerkapparaat',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Soort van dit netwerkapparaat',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Apparaten',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Alle apparaten die verbonden zijn met dit netwerkapparaat',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Versie IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Naam versie IOS',
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
	'Class:Server/Attribute:osfamily_id' => 'Soort besturingssysteem',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Naam soort besturingssysteem',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Versie besturingssysteem',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Naam versie besturingssysteem',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licentie besturingssysteem',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Naam licentie besturingssysteem',
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
	'Class:StorageSystem' => 'Opslagsysteem',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logische volumes',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Alle logische volumes in dit opslagsysteem',
));

//
// Class: SANSwitch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SANSwitch' => 'SAN-switch',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Apparaten',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Alle apparaten verbonden met deze SAN-switch',
));

//
// Class: TapeLibrary
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:TapeLibrary' => 'Tapebibliotheek',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Tapes',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Alle tapes in de tapebibliotheek',
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
	'Class:PC/Attribute:osfamily_id' => 'Soort besturingssysteem',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Naam soort besturingssysteem',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Versie besturingssysteem',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Naam versie besturingssysteem',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Type',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'Desktop',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'Laptop',
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
	'Class:PowerConnection' => 'Stroomverbinding',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PowerSource' => 'Stroombron',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU\'s',
	'Class:PowerSource/Attribute:pdus_list+' => 'Alle PDU\'s die gebruik maken van deze stroombron',
));

//
// Class: PDU
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Naam rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Power start',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Naam Power start',
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
	'Class:Enclosure/Attribute:rack_name' => 'Naam rack',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Rackeenheden',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Apparaten',
	'Class:Enclosure/Attribute:device_list+' => 'Alle apparaten in deze enclosure',
));

//
// Class: ApplicationSolution
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:ApplicationSolution' => 'Applicatie-oplossing',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CI\'s',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Alle configuratie-items die deze applicatie-oplossing tot stand brengen',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Bedrijfsprocessen',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Alle bedrijfsprocessen die afhankelijk zijn van deze applicatie-oplossing',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Actief',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Actief',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inactief',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Inactief',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Impactanalyse: configuratie van de redundantie',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'De oplossing werkt als alle configuratie-items actief zijn',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'De oplossing werkt als minstens %1$s configuratie-item(s) actief is/zijn',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'De oplossing werkt als minstens %1$s %% van de configuratie-items actief zijn',
));

//
// Class: BusinessProcess
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:BusinessProcess' => 'Bedrijfsproces',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Applicatie-oplossing',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Alle applicatie-oplossingen die impact hebben op dit bedrijfsproces',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Actief',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Actief',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inactief',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Inactief',
));

//
// Class: SoftwareInstance
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwareInstance' => 'Software-instantie',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Systeem',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Naam systeem',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Naam software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licentie software',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Naam licentie software',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Pad',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Actief',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Actief',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inactief',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Inactief',
));

//
// Class: Middleware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware-instanties',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Alle middleware-instanties die geleverd worden door deze middleware',
));

//
// Class: DBServer
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DBServer' => 'Databaseserver',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Databaseschema\'s',
	'Class:DBServer/Attribute:dbschema_list+' => 'Alle databaseschema\'s voor deze databaseserver',
));

//
// Class: WebServer
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:WebServer' => 'Webserver',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Webapplicaties',
	'Class:WebServer/Attribute:webapp_list+' => 'Alle webapplicaties die beschikbaar zijn voor deze webserver',
));

//
// Class: PCSoftware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PCSoftware' => 'PC-software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OtherSoftware' => 'Overige software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:MiddlewareInstance' => 'Middleware-instantie',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Naam middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:DatabaseSchema' => 'Databaseschema',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Databaseserver',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Naam databaseserver',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:WebApplication' => 'Webapplicatie',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Webserver',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Naam webserver',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'Link (URL)',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualDevice' => 'Virtueel apparaat',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implementatie',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Implementatie',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Buiten gebruik',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'Buiten gebruik',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Productie',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'Productie',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Voorraad',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'Voorraad',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logical volumes',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Alle logische volumes die door dit apparaat gebruikt worden',
));

//
// Class: VirtualHost
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualHost' => 'Virtuele host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtuele machines',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Alle virtuele machines die op deze host draaien',
));

//
// Class: Hypervisor
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Naam farm',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Naam server',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Farm' => 'Farm',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'Alle hypervisors die samen deze farm vormen',
	'Class:Farm/Attribute:redundancy' => 'Hoge beschikbaarheid',
	'Class:Farm/Attribute:redundancy/disabled' => 'De farm is beschikbaar als alle hypervisors beschikbaar zijn.',
	'Class:Farm/Attribute:redundancy/count' => 'De farm is beschikbaar als minstens %1$s hypervisor(s) actief is/zijn',
	'Class:Farm/Attribute:redundancy/percent' => 'De farm is beschikbaar als minstens %1$s %% hypervisors beschikbaar zijn',
));

//
// Class: VirtualMachine
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VirtualMachine' => 'Virtuele machine',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtuele host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Naam virtuele host',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Besturingssysteem',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Naam besturingssysteem',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Versie besturingssysteem',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Naam versie besturingssysteem',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licentie besturingssysteem',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Naam licentie besturingssysteem',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Netwerkinterfaces',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Alle logische netwerkinterfaces',
));

//
// Class: LogicalVolume
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:LogicalVolume' => 'Logisch volume',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Naam',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Omschrijving',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'RAID-niveau',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Grootte',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Opslagsysteem',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Naam opslagsysteem',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servers',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Alle servers die dit volume gebruiken',
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
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Naam volume',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Naam server',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Gebruikte grootte',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Virtueel apparaat / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Naam volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtueel apparaat',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Naam van het virtueel apparaat',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Gebruikte grootte',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Datacenterapparaat',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN-switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Naam SAN-switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Apparaat',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Naam apparaat',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'FC-poort SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'FC-poort apparaat',
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
	'Class:Tape/Attribute:tapelibrary_id' => 'Tapebibliotheek',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tapebibliotheek naam',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NASFileSystem' => 'NAS-bestandssysteem',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Naam',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Omschrijving',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'RAID-niveau',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Grootte',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Naam NAS',
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
	'Class:Software/Attribute:vendor' => 'Leverancier',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Versie',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documenten',
	'Class:Software/Attribute:documents_list+' => 'Alle documenten gelinkt aan deze software',
	'Class:Software/Attribute:type' => 'Type',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Databaseserver',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Databaseserver',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Overige software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Overige software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC-software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC-software',
	'Class:Software/Attribute:type/Value:WebServer' => 'Webserver',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Webserver',
	'Class:Software/Attribute:softwareinstance_list' => 'Software-instanties',
	'Class:Software/Attribute:softwareinstance_list+' => 'Alle software-instanties van deze software',
	'Class:Software/Attribute:softwarepatch_list' => 'Softwarepatches',
	'Class:Software/Attribute:softwarepatch_list+' => 'Alle patches voor deze software',
	'Class:Software/Attribute:softwarelicence_list' => 'Softwarelicenties',
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
	'Class:Patch/Attribute:finalclass' => 'Subklasse patch',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSPatch' => 'Besturingssysteempatch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Apparaten',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Alle systemen waarop deze patch is geïnstalleerd',
	'Class:OSPatch/Attribute:osversion_id' => 'Versie besturingssysteem',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Naam versie besturingssysteem',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwarePatch' => 'Softwarepatch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Naam software',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software-instanties',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Alle systemen waarop deze patch is geïnstalleerd',
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
	'Class:Licence/Attribute:documents_list+' => 'Alle documenten gelinkt aan deze licentie',
	'Class:Licence/Attribute:org_id' => 'Organisatie',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Naam organisatie',
	'Class:Licence/Attribute:organization_name+' => 'Naam van de organisatie',
	'Class:Licence/Attribute:usage_limit' => 'Gebruikslimiet',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Omschrijving',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Startdatum',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Einddatum',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Code',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Permanente licentie',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'Nee',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'Nee',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'Ja',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'Ja',
	'Class:Licence/Attribute:finalclass' => 'Subklasse licentie',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSLicence' => 'Besturingssysteemlicentie',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Versie besturingssysteem',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Naam versie bestandssysteem',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuele machines',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Alle virtuele machines die gebruik maken van deze licentie',
	'Class:OSLicence/Attribute:servers_list' => 'Servers',
	'Class:OSLicence/Attribute:servers_list+' => 'Alle servers die gebruik maken van deze licentie',
));

//
// Class: SoftwareLicence
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:SoftwareLicence' => 'Softwarelicentie',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Naam software',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software-instanties',
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
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Naam licentie',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Naam document',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
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
// Class: OSVersion
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSVersion' => 'Versie Besturingssysteem',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Soort besturingssysteem',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Naam soort besturingssysteem',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:OSFamily' => 'Soort Besturingssysteem',
	'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Brand' => 'Merk',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Fysieke apparaten',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Alle fysieke apparaten van dit merk',
	'Class:Brand/UniquenessRule:name+' => 'De naam van het merk moet uniek zijn',
	'Class:Brand/UniquenessRule:name' => 'De naam van het merk bestaat al',
));

//
// Class: Model
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Merk',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Naam merk',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Soort apparaat',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Stroombron',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Stroombron',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Schijvenset',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Schijvenset',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP-telefoon',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP-telefoon',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobiele telefoon',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Mobiele telefoon',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Netwerkapparaat',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Netwerkapparaat',
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
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN-switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN-switch',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => 'Server',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Opslagsysteem',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Opslagsysteem',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tapebibliotheek',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tapebibliotheek',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefoon',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telefoon',
	'Class:Model/Attribute:physicaldevices_list' => 'Fysieke apparaten',
	'Class:Model/Attribute:physicaldevices_list+' => 'Alle fysieke apparaten van dit model',
	'Class:Model/UniquenessRule:name_brand+' => 'De naam van het merk moet uniek zijn',
	'Class:Model/UniquenessRule:name_brand' => 'De naam van dit model bestaat al voor dit merk',
));

//
// Class: NetworkDeviceType
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkDeviceType' => 'Soort netwerkapparaat',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Netwerkapparaten',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Alle netwerkapparaten van deze soort',
));

//
// Class: IOSVersion
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IOSVersion' => 'Versie IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Merk',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Naam merk',
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
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Naam patch',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Naam document',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Software-instantie / Softwarepatch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Softwarepatch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Naam softwarepatch',
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
	'Class:lnkFunctionalCIToOSPatch' => 'Link Functioneel CI / Besturingssysteempatch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Besturingssysteempatch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Naam besturingssysteempatch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Functioneel CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Naam functioneel CI',
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
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Naam software',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Naam document',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contact / Functioneel CI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Functioneel CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Naam functioneel CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Naam contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link Document / Functioneel CI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Functioneel CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Naam Functioneel CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Naam document',
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
	'Class:Subnet/Attribute:subnet_name' => 'Naam subnet',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organisatie',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Naam',
	'Class:Subnet/Attribute:org_name+' => 'Naam van het subnet',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN\'s',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN-tag',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Omschrijving',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organisatie',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Naam organisatie',
	'Class:VLAN/Attribute:org_name+' => 'Naam van de organisatie',
	'Class:VLAN/Attribute:subnets_list' => 'Subnetten',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fysieke netwerkinterfaces',
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
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Naam subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Tag VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:NetworkInterface' => 'Netwerkinterface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Naam',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Subklasse netwerkinterface',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:IPInterface' => 'IP-interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP-adres',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC-adres',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Commentaar',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP-gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP-mask',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Snelheid',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:PhysicalInterface' => 'Fysieke interface',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Apparaat',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Naam apparaat',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN\'s',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link Fysieke interface / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Fysieke interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Naam fysieke interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Apparaat',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Naam apparaat',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Tag VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:LogicalInterface' => 'Logische interface',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuele machine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Naam virtuele machine',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FiberChannelInterface' => 'Fiber Channel-interface',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Snelheid',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologie',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Apparaat',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Naam apparaat',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / Netwerkapparaat',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Netwerkapparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Naam netwerkapparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Verbonden apparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Naam verbonden apparaat',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Netwerkpoort',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Apparaatpoort',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Soort connectie',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'uplink',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link Applicatie-oplossing / Functioneel CI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Applicatie-oplossing',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Naam applicatie-oplossing',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Functioneel CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Naam functioneel CI',
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
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Naam bedrijfsproces',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Applicatie-oplossing',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Naam applicatie-oplossing',
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
	'Class:Group/Attribute:status/Value:obsolete' => 'Buiten gebruik',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Buiten gebruik',
	'Class:Group/Attribute:status/Value:production' => 'Productie',
	'Class:Group/Attribute:status/Value:production+' => 'Productie',
	'Class:Group/Attribute:org_id' => 'Organisatie',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Naam',
	'Class:Group/Attribute:owner_name+' => 'Naam van de eigenaar',
	'Class:Group/Attribute:description' => 'Omschrijving',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Soort',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Hoofdgroep',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Naam',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Gelinkte CI\'s',
	'Class:Group/Attribute:ci_list+' => 'Alle configuratie-items gelinkt aan deze groep',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Hoofdgroep',
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
	'Menu:Application' => 'Applicaties',
	'Menu:Application+' => 'Alle applicaties',
	'Menu:DBServer' => 'Databaseservers',
	'Menu:DBServer+' => 'Databaseservers',
	'Menu:ConfigManagement' => 'Configuratiebeheer',
	'Menu:ConfigManagement+' => 'Configuratiebeheer',
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
	'Menu:ConfigManagementCI' => 'Configuratie-items',
	'Menu:ConfigManagementCI+' => 'Configuratie-items',
	'Menu:BusinessProcess' => 'Bedrijfsprocessen',
	'Menu:BusinessProcess+' => 'Alle bedrijfsprocessen',
	'Menu:ApplicationSolution' => 'Applicatie-oplossing',
	'Menu:ApplicationSolution+' => 'Alle applicatie-oplossingen',
	'Menu:ConfigManagementSoftware' => 'Applicatiebeheer',
	'Menu:Licence' => 'Licenties',
	'Menu:Licence+' => 'Alle licenties',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'Alle patches',
	'Menu:ApplicationInstance' => 'Geïnstalleerde software',
	'Menu:ApplicationInstance+' => 'Applicaties en databaseservers',
	'Menu:ConfigManagementHardware' => 'Infrastructuurbeheer',
	'Menu:Subnet' => 'Subnetten',
	'Menu:Subnet+' => 'Alle subnetten',
	'Menu:NetworkDevice' => 'Netwerkapparaten',
	'Menu:NetworkDevice+' => 'Alle netwerkapparaten',
	'Menu:Server' => 'Servers',
	'Menu:Server+' => 'Alle servers',
	'Menu:Printer' => 'Printers',
	'Menu:Printer+' => 'Alle printers',
	'Menu:MobilePhone' => 'Mobiele telefoons',
	'Menu:MobilePhone+' => 'Alle mobiele telefoons',
	'Menu:PC' => 'PC\'s',
	'Menu:PC+' => 'Alle PC\'s',
	'Menu:NewContact' => 'Nieuw contact',
	'Menu:NewContact+' => 'Maak een nieuw contact aan',
	'Menu:SearchContacts' => 'Zoek naar contacten',
	'Menu:SearchContacts+' => 'Zoek naar contacten',
	'Menu:NewCI' => 'Nieuw configuratie-item',
	'Menu:NewCI+' => 'Maak een nieuw configuratie-item aan',
	'Menu:SearchCIs' => 'Zoek naar CI\'s',
	'Menu:SearchCIs+' => 'Zoek naar configuratie-items',
	'Menu:ConfigManagement:Devices' => 'Apparaten',
	'Menu:ConfigManagement:AllDevices' => 'Infrastructuur',
	'Menu:ConfigManagement:virtualization' => 'Virtualisatie',
	'Menu:ConfigManagement:EndUsers' => 'Apparaten van eindgebruikers',
	'Menu:ConfigManagement:SWAndApps' => 'Software en applicaties',
	'Menu:ConfigManagement:Misc' => 'Diversen',
	'Menu:Group' => 'Groepen van CI\'s',
	'Menu:Group+' => 'Groepen van CI\'s',
	'Menu:ConfigManagement:Shortcuts' => 'Snelkoppelingen',
	'Menu:ConfigManagement:AllContacts' => 'Alle contacten: %1$d',
	'Menu:Typology' => 'Configuratie typologie',
	'Menu:Typology+' => 'Configuratie van de typologie',
	'Menu:OSVersion' => 'Versies besturingssysteem',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Softwarecatalogus',
	'Menu:Software+' => 'Softwarecatalogus',
	'UI_WelcomeMenu_AllConfigItems' => 'Samenvatting',
	'Menu:ConfigManagement:Typology' => 'Configuratie typologie',

));


// Add translation for Fieldsets

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Server:baseinfo' => 'Globale informatie',
	'Server:Date' => 'Datum',
	'Server:moreinfo' => 'Meer informatie',
	'Server:otherinfo' => 'Andere informatie',
	'Server:power' => 'Stroomtoevoer',
	'Person:info' => 'Globale informatie',
	'UserLocal:info' => 'Globale informatie',
	'Person:personal_info' => 'Persoonlijke informatie',
	'Person:notifiy' => 'Notificeer',
	'Class:Subnet/Tab:IPUsage' => 'IP-gebruik',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces met een IP-adres in de reeks: <em>%1$s</em> tot en met <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Beschikbare IP-adressen',
	'Class:Subnet/Tab:FreeIPs-count' => 'Beschikbare IP-adressen: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Dit zijn 10 beschikbare IP-adressen',
	'Class:Document:PreviewTab' => 'Voorbeeld',
));

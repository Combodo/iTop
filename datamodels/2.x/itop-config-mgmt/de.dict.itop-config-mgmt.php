<?php
// Copyright (C) 2010-2014 Combodo SARL
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
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2017 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*		
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Relation:impacts/Description' => 'Elemente betroffen von',
	'Relation:impacts/DownStream' => 'Auswirkung ...',
	'Relation:impacts/DownStream+' => 'Elemente betroffen von',
	'Relation:impacts/UpStream' => 'Hängt ab von ...',
	'Relation:impacts/UpStream+' => 'Betroffene Elemente',
	// Legacy entries
	'Relation:depends on/Description' => 'Elemente, von denen dieses Element abhängt.',
	'Relation:depends on/DownStream' => 'Hängt ab von ...',
	'Relation:depends on/UpStream' => 'Wirkt auf ...',
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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Name',
	'Class:Organization/Attribute:name+' => 'Gemeinsamer Name',
	'Class:Organization/Attribute:code' => 'Kennziffer',
	'Class:Organization/Attribute:code+' => 'Organisationskennziffer (D-U-N-S, Siret)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Organization/Attribute:parent_id' => 'Mutterfirma',
	'Class:Organization/Attribute:parent_id+' => 'Dachorganisation',
	'Class:Organization/Attribute:parent_name' => 'Name der Mutterfirma',
	'Class:Organization/Attribute:parent_name+' => 'Name der Mutterfirma',
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery-Modell',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery-Modell-Name',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Parent',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Überblick',
	'Organization:Overview:FunctionalCIs' => 'CIs dieser Organisation',
	'Organization:Overview:FunctionalCIs:subtitle' => 'nach Typ',
	'Organization:Overview:Users' => 'iTop Benutzer innerhalb dieser Organisation',
));

//
// Class: Location
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Location' => 'Standort',
	'Class:Location+' => 'Jeder Typ von Standort: Region, Land, Stadt, Seite, Gebäude, Flur, Raum, Rack,...',
	'Class:Location/Attribute:name' => 'Name',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktiv',
	'Class:Location/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Location/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Location/Attribute:org_id' => 'Organisation',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Name der Organisation',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresse',
	'Class:Location/Attribute:address+' => 'Postanschrift',
	'Class:Location/Attribute:postal_code' => 'Postleitzahl',
	'Class:Location/Attribute:postal_code+' => 'Postleitzahl',
	'Class:Location/Attribute:city' => 'Stadt',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Geräte',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => 'Kontakte',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Name',
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
	'Class:Contact/Attribute:notify' => 'Benachrichtigung',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'Nein',
	'Class:Contact/Attribute:notify/Value:no+' => '',
	'Class:Contact/Attribute:notify/Value:yes' => 'Ja',
	'Class:Contact/Attribute:notify/Value:yes+' => '',
	'Class:Contact/Attribute:function' => 'Funktion',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => 'Typ',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Name',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'Vorname',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Personalnummer',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobiltelefone',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Standort',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Standortname',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Manager-Name',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Teams',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager Friendly Name',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Bild',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Die Personalnummer muss innerhalb dieser Organisation eindeutig sein.',
	'Class:Person/UniquenessRule:employee_number' => 'In der Organisation \'$this->org_name$\' existiert bereits eine Person mit der gleichen Personalnummer',
	'Class:Person/UniquenessRule:name+' => 'Innerhalb einer Organisation muss der Name einer Person eindeutig sein',
	'Class:Person/UniquenessRule:name' => 'In der Organisation \'$this->org_name$\' existiert bereits eine Person mit dem gleichen Namen',
));

//
// Class: Team
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Mitglieder',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisation',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Organisationsname',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Dokumenttyp',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Dokumenttypname',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Beschreibung',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Entwurf',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Veröffentlicht',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:contracts_list' => 'Verträge',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Dokumenttyp',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentFile' => 'Dokument (Datei)',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Datei',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentNote' => 'Dokument (Notiz)',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Text',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentWeb' => 'Dokument (Web)',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FunctionalCI' => 'Funktionales CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Name',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Beschreibung',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisation',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Organisationsname',
	'Class:FunctionalCI/Attribute:organization_name+' => '',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Business-Kritikalität',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'hoch',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'niedrig',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'mittel',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:move2production' => 'Go-Live-Datum',
	'Class:FunctionalCI/Attribute:move2production+' => 'Datum, an dem in Produktivbetrieb gegangen wird/wurde',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kontakte',
	'Class:FunctionalCI/Attribute:contacts_list+' => '',
	'Class:FunctionalCI/Attribute:documents_list' => 'Dokumente',
	'Class:FunctionalCI/Attribute:documents_list+' => '',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Anwendungslösungen',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Provider-Verträge',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => '',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Software',
	'Class:FunctionalCI/Attribute:softwares_list+' => '',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => '',
	'Class:FunctionalCI/Attribute:finalclass' => 'Typ',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Offene Tickets',
));

//
// Class: PhysicalDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PhysicalDevice' => 'Physisches Gerät',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Seriennummer',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Standort',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Standortname',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implementierung',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Obsolet',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Produktiv',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Lager',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Marke',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Markenname',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modell',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Modellname',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Asset-Nummer',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Kaufdatum',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Garantieende',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Höheneinheiten',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Devices',
	'Class:Rack/Attribute:device_list+' => '',
	'Class:Rack/Attribute:enclosure_list' => 'Enclosures',
	'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TelephonyCI' => 'Telefonie-CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefonnummer',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Phone' => 'Telefon',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:MobilePhone' => 'Mobiltelefon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware-PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:IPPhone' => 'IP-Telefon',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ConnectableCI' => 'Verknüpfbares CI',
	'Class:ConnectableCI+' => 'Physisches CI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Netzwerkgeräte',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Netzwerkinterfaces',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DatacenterDevice' => 'Datacenter-Gerät',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Rack-Name',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Enclosure-Name',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Höheneinheiten',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Management-IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA-Quelle',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA-Quellenname',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB-Quelle',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB-Quellenname',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC-Ports',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => '',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundanz',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Das Gerät läuft, wenn mindestens eine der Stromversorgungen (A oder B) läuft.',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Das Gerät läuft wenn alle seine Stromversorgungen laufen.',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Das Gerät läuft wenn mindestens %1$s %% seiner Stromversorgungen laufen.',
));

//
// Class: NetworkDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NetworkDevice' => 'Netzwerk-Gerät',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Netzwerktyp',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Netzwerk-Typname',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Geräte',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS Version',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS-Versionsname',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'OS Familie',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OS-Famillenname',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OS Version',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OS-Versionsname',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'OS Lizenz',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OS-Lizenzname',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logische Volumen',
	'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:StorageSystem' => 'Storage-System',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logische Volumes',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '',
));

//
// Class: SANSwitch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SANSwitch' => 'SAN-Switch',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Geräte',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '',
));

//
// Class: TapeLibrary
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:TapeLibrary' => 'Tape-Library',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Tapes',
	'Class:TapeLibrary/Attribute:tapes_list+' => '',
));

//
// Class: NAS
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Dateisysteme',
	'Class:NAS/Attribute:nasfilesystem_list+' => '',
));

//
// Class: PC
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'OS-Familie',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OS-Familienname',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OS-Version',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OS-Versionsname',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Typ',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => '',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => '',
));

//
// Class: Printer
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Printer' => 'Drucker',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PowerConnection' => 'Stromverbindung',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PowerSource' => 'Stromquelle',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => '',
));

//
// Class: PDU
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Rack-Name',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Eingangs-Stromverbindung',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Eingangs-Stromverbindung',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Peripheral' => 'Peripheriegerät',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Enclosure' => 'Enclosure',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Rack-Name',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Höheneinheiten',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Geräte',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ApplicationSolution' => 'Anwendungslösung',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Business-Prozesse',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'aktiv',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'inaktiv',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Auswirkungsanalyse: Redundanz-Einstellungen',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Die Lösung läuft wenn alle ihre CIs laufen.',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Die Lösung läuft wenn mindestens %1$s CI(s) laufen.',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Die Lösung läuft wemm mindestens %1$s %% der CIs laufen.',
));

//
// Class: BusinessProcess
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:BusinessProcess' => 'Business-Prozess',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Anwendungslösungen',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'aktiv',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'inaktiv',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SoftwareInstance' => 'Software-Instanz',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'System',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Systemname',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Software-Lizenz',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Software-Lizenzname',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Pfad',
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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware-Instanzen',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '',
));

//
// Class: DBServer
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DBServer' => 'DB Server',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB Schemata',
	'Class:DBServer/Attribute:dbschema_list+' => '',
));

//
// Class: WebServer
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:WebServer' => 'Web Server',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Webapplikationen',
	'Class:WebServer/Attribute:webapp_list+' => '',
));

//
// Class: PCSoftware
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PCSoftware' => 'PC-Software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OtherSoftware' => 'Andere Software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:MiddlewareInstance' => 'Middleware-Instanz',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware-Name',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DatabaseSchema' => 'Datenbank-Schema',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB-Server',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB-Servername',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:WebApplication' => 'Webapplikation',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Webserver',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Webservername',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:VirtualDevice' => 'Virtuelles Gerät',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implementierung',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsolet',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Produktiv',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Lager',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logical Volumes',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '',
));

//
// Class: VirtualHost
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:VirtualHost' => 'Host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtuelle Maschinen',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '',
));

//
// Class: Hypervisor
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Farmname',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Servername',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Farm' => 'Farm',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisoren',
	'Class:Farm/Attribute:hypervisor_list+' => '',
	'Class:Farm/Attribute:redundancy' => 'Hochverfügbarkeit',
	'Class:Farm/Attribute:redundancy/disabled' => 'Die Farm läuft wenn alle Hypervisoren laufen.',
	'Class:Farm/Attribute:redundancy/count' => 'Die Farm läuft wenn mindestens %1$s Hypervisor(en) läuft/laufen.',
	'Class:Farm/Attribute:redundancy/percent' => 'Die Farm läuft wenn mindestens %1$s %% der Hypervisoren laufen.',
));

//
// Class: VirtualMachine
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:VirtualMachine' => 'Virtuelle Maschine',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Hostname',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS-Familie',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS-Familienname',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS-Version',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS-Versionsname',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS-Lizenz',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS-Lizenzname',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'Management-IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Netzwerk-Interfaces',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '',
));

//
// Class: LogicalVolume
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:LogicalVolume' => 'Logisches Volume',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Name',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Beschreibung',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid-Level',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Größe',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Storage-System',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Storage-Systemname',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Server',
	'Class:LogicalVolume/Attribute:servers_list+' => '',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuelle Geräte',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '',
));

//
// Class: lnkServerToVolume
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkServerToVolume' => 'Verknüpfung Server/Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume-Name',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Server-Name',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Größe verwendet',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkVirtualDeviceToVolume' => 'Verknüpfung Virtuelles Gerät/Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volume-Name',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuelles Gerät',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Virtuelles Gerät-Name',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Größe verwendet',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkSanToDatacenterDevice' => 'Verknüpfung SAN/Datacenter-Gerät',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN-Switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN-Switch-Name',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Gerät',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Gerät-Name',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Gerät FC',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Tape' => 'Tape',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Name',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Beschreibung',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Größe',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape-Library',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tape-Library-Name',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NASFileSystem' => 'NAS-Dateisystem',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Name',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Beschreibung',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid-Level',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Größe',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS-Name',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Name',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Hersteller',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Version',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Dokumente',
	'Class:Software/Attribute:documents_list+' => '',
	'Class:Software/Attribute:type' => 'Typ',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB-Server',
	'Class:Software/Attribute:type/Value:DBServer+' => '',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => '',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Andere Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC-Software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => '',
	'Class:Software/Attribute:type/Value:WebServer' => 'Webserver',
	'Class:Software/Attribute:type/Value:WebServer+' => '',
	'Class:Software/Attribute:softwareinstance_list' => 'Software-Instanzen',
	'Class:Software/Attribute:softwareinstance_list+' => '',
	'Class:Software/Attribute:softwarepatch_list' => 'Software-Patches',
	'Class:Software/Attribute:softwarepatch_list+' => '',
	'Class:Software/Attribute:softwarelicence_list' => 'Softwarelizenzen',
	'Class:Software/Attribute:softwarelicence_list+' => '',
));

//
// Class: Patch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Name',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Dokumente',
	'Class:Patch/Attribute:documents_list+' => '',
	'Class:Patch/Attribute:description' => 'Beschreibung',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Typ',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OSPatch' => 'OS-Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Geräte',
	'Class:OSPatch/Attribute:functionalcis_list+' => '',
	'Class:OSPatch/Attribute:osversion_id' => 'OS Version',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'OS-Versionsname',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SoftwarePatch' => 'Software-Patch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software-Name',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software-Instanzen',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '',
));

//
// Class: Licence
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Licence' => 'Lizenz',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Name',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Dokumente',
	'Class:Licence/Attribute:documents_list+' => '',
	'Class:Licence/Attribute:org_id' => 'Besitzer',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organisationsname',
	'Class:Licence/Attribute:organization_name+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Nutzungseinschränkungen',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Beschreibung',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Startdatum',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Enddatum',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Schlüssel',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'unbefristet',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'nein',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'nein',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'ja',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'ja',
	'Class:Licence/Attribute:finalclass' => 'Typ',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OSLicence' => 'OS-Lizenz',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'OS-Version',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'OS-Versionsname',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuelle Maschinen',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '',
	'Class:OSLicence/Attribute:servers_list' => 'Server',
	'Class:OSLicence/Attribute:servers_list+' => '',
));

//
// Class: SoftwareLicence
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SoftwareLicence' => 'Software-Lizenz',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software-Name',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software-Instanzen',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDocumentToLicence' => 'Verknüpfung Dokument/Lizenz',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Lizenz',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Lizenz-Name',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Dokumenten-Name',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Name',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Typ',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: OSVersion
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OSVersion' => 'OS-Version',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS-Familie',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS-Familienname',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:OSFamily' => 'OS-Familie',
	'Class:OSFamily+' => '',
));

//
// Class: DocumentType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DocumentType' => 'Dokumentyp',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ContactType' => 'Kontakttyp',
	'Class:ContactType+' => '',
));

//
// Class: Brand
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Brand' => 'Marke',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Physische Geräte',
	'Class:Brand/Attribute:physicaldevices_list+' => '',
	'Class:Brand/UniquenessRule:name+' => 'Der Name muss eindeutig sein',
	'Class:Brand/UniquenessRule:name' => 'Diese Marke existiert bereits',
));

//
// Class: Model
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Model' => 'Modell',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Marke',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Markenname',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Gerätetyp',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Stromquelle',
	'Class:Model/Attribute:type/Value:PowerSource+' => '',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Plattenarray',
	'Class:Model/Attribute:type/Value:DiskArray+' => '',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => '',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP-Telefon',
	'Class:Model/Attribute:type/Value:IPPhone+' => '',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobiltelefon',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => '',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Netzwerkgerät',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => '',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => '',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => '',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Peripheriegeräte',
	'Class:Model/Attribute:type/Value:Peripheral+' => '',
	'Class:Model/Attribute:type/Value:Printer' => 'Drucker',
	'Class:Model/Attribute:type/Value:Printer+' => '',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => '',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN-Switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => '',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => '',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Storage-System',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => '',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tape-Library',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefon',
	'Class:Model/Attribute:type/Value:Phone+' => '',
	'Class:Model/Attribute:physicaldevices_list' => 'Phyische Geräte',
	'Class:Model/Attribute:physicaldevices_list+' => '',
	'Class:Model/UniquenessRule:name_brand+' => 'Der Modellname der für eine Marke muss eindeutig sein',
	'Class:Model/UniquenessRule:name_brand' => 'Es existiert bereits ein Modell mit diesem Namen für diese Marke',
));

//
// Class: NetworkDeviceType
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NetworkDeviceType' => 'Netzwerkgerätetyp',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Netzwerkgeräte',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '',
));

//
// Class: IOSVersion
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:IOSVersion' => 'IOS-Version',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Marke',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Markenname',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDocumentToPatch' => 'Verknüpfung Dokument/Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Patch-Name',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Dokument-Name',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Verknüpfung Software-Instanz/Softeware-Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software-Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Software-Patch-Name',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Software-Instanz',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Software-Instanz-Name',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Verknüpfung FunctionalCI/OS-Patch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS-Patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS-Patch-Name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'FunctionalCI-Name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDocumentToSoftware' => 'Verknüpfung Dokument/Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software-Name',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Dokument-Name',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContactToFunctionalCI' => 'Verknüpfung Kontakt/FunctionalCI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI-Name',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Kontakt-Name',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkDocumentToFunctionalCI' => 'Verknüpfung Dokument/FunctionalCI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI-Name',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Dokument-Name',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Subnet' => 'Subnetz',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Beschreibung',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnetzname',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organisation',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Name',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Subnetz-Maske',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN-Tag',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Beschreibung',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organisation',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Organisationsname',
	'Class:VLAN/Attribute:org_name+' => 'Allgemeiner Name (Common name)',
	'Class:VLAN/Attribute:subnets_list' => 'Subnetze',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physische Interfaces',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkSubnetToVLAN' => 'Verknüpfung Subnetz/VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnetz',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnetz-IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnetz-Name',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NetworkInterface' => 'Netzwerk-Interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Name',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Typ',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:IPInterface' => 'IP-Interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP-Adresse',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC-Adresse',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Kommentar',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP-Gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP-Maske',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Geschwindigkeit',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PhysicalInterface' => 'Physisches Interface',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Gerät',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Gerätename',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Verknüpfung Physisches Interface / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Physisches Interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Physisches Interface Name',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Gerät',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Gerätename',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:LogicalInterface' => 'Logisches Interface',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuelle Maschine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtuelle Maschine-Name',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FiberChannelInterface' => 'Fiber Channel Interface',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Geschwindigkeit',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologie',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Gerät',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Gerätename',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Verknüpfung ConnectableCI/NetworkDevice',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Netzwerkgerät',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Netzwerkgerät-Name',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Verbundenes Gerät',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Verbundenes Gerät-Name',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Netzwerkport',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Geräteport',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Verbindungstyp',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'Downlink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'Uplink',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => '',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Verknüpfung Anwendungslösung/FunctionalCI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Applikationslösung',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Anwendungslösungs-Name',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI-Name',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Verknüpfung Anwendungslösung/Business-Prozess',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Business-Prozess',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Business-Prozess-Name',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Anwendungslösung',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Applikationslösungs-Name',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkPersonToTeam' => 'Verknüpfung Person/Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Teamname',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Personenname',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rolle',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Rollenname',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Class: Group
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Group' => 'Gruppe',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Name',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementation',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementation',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsolet (Veraltet)',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsolet (Veraltet)',
	'Class:Group/Attribute:status/Value:production' => 'Produktion',
	'Class:Group/Attribute:status/Value:production+' => 'Produktion',
	'Class:Group/Attribute:org_id' => 'Organisation',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Name',
	'Class:Group/Attribute:owner_name+' => 'Allgemeiner Name',
	'Class:Group/Attribute:description' => 'Beschreibung',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Typ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Muttergruppe',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Name',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Verbundene CIs',
	'Class:Group/Attribute:ci_list+' => '',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Parent-Gruppe',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkGroupToCI' => 'Gruppe/CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Gruppe',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Grund',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Application Menu
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:DataAdministration' => 'Data Management',
	'Menu:DataAdministration+' => 'Data Management',
	'Menu:Catalogs' => 'Kataloge',
	'Menu:Catalogs+' => 'Datentypen',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV-Import',
	'Menu:CSVImport+' => 'Massenerstellung oder -aktualisierung',
	'Menu:Organization' => 'Organisationen',
	'Menu:Organization+' => 'Alle Organisationen',
	'Menu:Application' => 'Anwendungen',
	'Menu:Application+' => 'Alle Anwendungen',
	'Menu:DBServer' => 'Datenbank-Server',
	'Menu:DBServer+' => 'Datenbank-Server',
	'Menu:ConfigManagement' => 'Configuration Management',
	'Menu:ConfigManagement+' => 'Configuration Management',
	'Menu:ConfigManagementOverview' => 'Übersicht',
	'Menu:ConfigManagementOverview+' => 'Übersicht',
	'Menu:Contact' => 'Kontakte',
	'Menu:Contact+' => 'Kontakte',
	'Menu:Contact:Count' => '%1$d Kontakte',
	'Menu:Person' => 'Personen',
	'Menu:Person+' => 'Alle Personen',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'Alle Teams',
	'Menu:Document' => 'Dokumente',
	'Menu:Document+' => 'Alle Dokumente',
	'Menu:Location' => 'Standorte',

	'Menu:Location+' => 'Alle Standorte',
	'Menu:ConfigManagementCI' => 'Configuration Items',
	'Menu:ConfigManagementCI+' => 'Configuration Items',
	'Menu:BusinessProcess' => 'Business-Prozesse',
	'Menu:BusinessProcess+' => 'Alle Business-Prozesse',
	'Menu:ApplicationSolution' => 'Anwendungslösungen',
	'Menu:ApplicationSolution+' => 'Alle Anwendungslösungen',
	'Menu:ConfigManagementSoftware' => 'Anwendungs-Management',
	'Menu:Licence' => 'Lizenzen',
	'Menu:Licence+' => 'Alle Lizenzen',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'Alle Patches',
	'Menu:ApplicationInstance' => 'Installierte Software',
	'Menu:ApplicationInstance+' => 'Anwendungen und Datenbank-Server',
	'Menu:ConfigManagementHardware' => 'Infrastruktur-Management',
	'Menu:Subnet' => 'Subnetze',
	'Menu:Subnet+' => 'Alle Subnetze',
	'Menu:NetworkDevice' => 'Netzwerkgeräte',
	'Menu:NetworkDevice+' => 'Alle Netzwerkgeräte',
	'Menu:Server' => 'Server',
	'Menu:Server+' => 'Alle Server',
	'Menu:Printer' => 'Drucker',
	'Menu:Printer+' => 'Alle Drucker',
	'Menu:MobilePhone' => 'Mobiltelefone',
	'Menu:MobilePhone+' => 'Alle Mobiltelefone',
	'Menu:PC' => 'Rechner (PC)',
	'Menu:PC+' => 'Alle Rechner (PC)',
	'Menu:NewContact' => 'Neuer Kontakt',
	'Menu:NewContact+' => 'Neuer Kontakt',
	'Menu:SearchContacts' => 'Nach Kontakten suchen',
	'Menu:SearchContacts+' => 'Nach Kontakten suchen',
	'Menu:NewCI' => 'Neues CI',
	'Menu:NewCI+' => 'Neues CI',
	'Menu:SearchCIs' => 'Nach CIs suchen',
	'Menu:SearchCIs+' => 'Nach CIs suchen',
	'Menu:ConfigManagement:Devices' => 'Geräte',
	'Menu:ConfigManagement:AllDevices' => 'Infrastruktur',
	'Menu:ConfigManagement:virtualization' => 'Virtualisierung',
	'Menu:ConfigManagement:EndUsers' => 'Endbenutzer-Geräte',
	'Menu:ConfigManagement:SWAndApps' => 'Software und Anwendungen',
	'Menu:ConfigManagement:Misc' => 'Diverses',
	'Menu:Group' => 'Gruppen von CIs',
	'Menu:Group+' => 'Gruppen von CIs',
	'Menu:ConfigManagement:Shortcuts' => 'Shortcuts',
	'Menu:ConfigManagement:AllContacts' => 'Alle Kontakte: %1$d',
	'Menu:Typology' => 'Typologie-Konfiguration',
	'Menu:Typology+' => '',
	'Menu:OSVersion' => 'OS-Versionen',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Software-Katalog',
	'Menu:Software+' => '',
	'UI_WelcomeMenu_AllConfigItems' => 'Zusammenfassung',
	'Menu:ConfigManagement:Typology' => 'Typologie-Konfiguration',

));


// Add translation for Fieldsets

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Server:baseinfo' => 'Allgemeine Informationen',
	'Server:Date' => 'Datum',
	'Server:moreinfo' => 'Weitere Informationen',
	'Server:otherinfo' => 'Sonstige Informationen',
	'Server:power' => 'Stromversorgung',
	'Person:info' => 'Allgemeine Informationen',
	'UserLocal:info' => 'Allgemeine Informationen',
	'Person:personal_info' => 'Persönliche Informationen',
	'Person:notifiy' => 'Benachrichtigungen',
	'Class:Subnet/Tab:IPUsage' => 'IP-Nutzung',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces mit einer IP in der Range: <em>%1$s</em> bis <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Freie IPs',
	'Class:Subnet/Tab:FreeIPs-count' => 'Freie IPs: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Hier ist eine Aufstellung von 10 freien IP Adressen',
	'Class:Document:PreviewTab' => 'Vorschau',
));

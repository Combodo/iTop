<?php
// Copyright (C) 2010-2015 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */




//
// Class: Organization
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Nom organisation',
	'Class:Organization/Attribute:name+' => 'Nom commun',
	'Class:Organization/Attribute:code' => 'Code',
	'Class:Organization/Attribute:code+' => 'Organisation code (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Statut',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'active',
	'Class:Organization/Attribute:status/Value:active+' => 'active',
	'Class:Organization/Attribute:status/Value:inactive' => 'inactive',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactive',
	'Class:Organization/Attribute:parent_id' => 'Organisation Parent',
	'Class:Organization/Attribute:parent_id+' => 'Organisation parent',
	'Class:Organization/Attribute:parent_name' => 'Nom du parent',
	'Class:Organization/Attribute:parent_name+' => 'Nom de l\'organisation parente',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Nom commun',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: Location
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Location' => 'Lieu',
	'Class:Location+' => 'Tout type de lieu: Région, Pays, Ville, Site, batiment, Bureau,...',
	'Class:Location/Attribute:name' => 'Nom',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Statut',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Actif',
	'Class:Location/Attribute:status/Value:active+' => 'Actif',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactif',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inactif',
	'Class:Location/Attribute:org_id' => 'Organisation',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nom organisation',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresse',
	'Class:Location/Attribute:address+' => 'Adresse postale',
	'Class:Location/Attribute:postal_code' => 'Code postal',
	'Class:Location/Attribute:postal_code+' => 'Code postal',
	'Class:Location/Attribute:city' => 'Ville',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Pays',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'Matériels',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => 'Contacts',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Contact' => 'Contact',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nom',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Statut',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Actif',
	'Class:Contact/Attribute:status/Value:active+' => 'Actif',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactif',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inactif',
	'Class:Contact/Attribute:org_id' => 'Organisation',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Nom organisation',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Téléphone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notification',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'non',
	'Class:Contact/Attribute:notify/Value:no+' => 'non',
	'Class:Contact/Attribute:notify/Value:yes' => 'oui',
	'Class:Contact/Attribute:notify/Value:yes+' => 'oui',
	'Class:Contact/Attribute:function' => 'Fonction',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => 'Type de contact',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Person' => 'Personne',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Prénom',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Numéro d\'employé',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Téléphone mobile',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Site',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Nom site',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Nom Manager',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Equipes',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager friendly name',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
));

//
// Class: Team
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Team' => 'Equipe',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'Membres',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nom',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisation',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Nom organisation',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Type de document',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Nom type de document',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Description',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Statut',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Brouillon',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publié',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:contracts_list' => 'Contrats',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => '',
	'Class:Document/Attribute:finalclass' => 'Type de document',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DocumentFile' => 'Document Fichier',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'Fichier',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DocumentNote' => 'Document Note',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Texte',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DocumentWeb' => 'Document Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:FunctionalCI' => 'CI fonctionnel',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nom',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Description',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisation',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Nom organisation',
	'Class:FunctionalCI/Attribute:organization_name+' => '',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Criticité',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'haute',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'haute',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'basse',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'basse',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'moyenne',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'moyenne',
	'Class:FunctionalCI/Attribute:move2production' => 'Date de mise en production',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contacts',
	'Class:FunctionalCI/Attribute:contacts_list+' => '',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documents',
	'Class:FunctionalCI/Attribute:documents_list+' => '',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Solutions applicatives',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '',
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contrats fournisseur',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => '',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Logiciels',
	'Class:FunctionalCI/Attribute:softwares_list+' => '',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => '',
	'Class:FunctionalCI/Attribute:finalclass' => 'Type de CI',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Tickets en cours',
));

//
// Class: PhysicalDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PhysicalDevice' => 'Matériel physique',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Numéro de série',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Site',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nom site',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Statut',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'implémentation',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'implémentation',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'obsolète',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'obsolète',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'production',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'production',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'stock',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'stock',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Marque',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Nom Marque',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modèle',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Nom Modèle',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Numéro Asset',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Date d\'achat',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Date de fin de garantie',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'NB Unité',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Matériels',
	'Class:Rack/Attribute:device_list+' => '',
	'Class:Rack/Attribute:enclosure_list' => 'Chassis',
	'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TelephonyCI' => 'CI Téléphonie',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Numéro',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Phone' => 'Téléphone',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:MobilePhone' => 'Téléphone mobile',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPPhone' => 'Téléphone IP',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Tablet' => 'Tablette',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ConnectableCI' => 'CI connecté',
	'Class:ConnectableCI+' => '',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Equipements réseaux',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interfaces réseaux',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DatacenterDevice' => 'Matériel Datacenter',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Nom Rack',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Chassis',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Nom Chassis',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'NB Unité',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Source électrique A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Nom Source électrique A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Source électrique B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Nom Source électrique B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC ports',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => '',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redondance',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Le %2$s est alimenté si au moins une source électrique (A ou B) est opérationnelle',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Le %2$s est alimenté si toutes ses sources électriques sont opérationnelles',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Le %2$s est alimenté si au moins %1$s %% de ses sources électriques sont opérationnelles',
));

//
// Class: NetworkDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:NetworkDevice' => 'Equipement réseau',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Type',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Nom Type',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Matériel connectés',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Version IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Nom Version IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Server' => 'Serveur',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'Famille OS',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Nom Famille OS',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Version OS',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Nom Version OS',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licence OS',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Nom Licence OS',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Volumes logiques',
	'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:StorageSystem' => 'Système de stockage',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Volumes logiques',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '',
));

//
// Class: SANSwitch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SANSwitch' => 'Switch SAN',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Matériels connectés',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '',
));

//
// Class: TapeLibrary
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:TapeLibrary' => 'Bandothèque',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Bandes',
	'Class:TapeLibrary/Attribute:tapes_list+' => '',
));

//
// Class: NAS
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Systèmes de fichier NAS',
	'Class:NAS/Attribute:nasfilesystem_list+' => '',
));

//
// Class: PC
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'Famille OS',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Nom Famille OS',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Version OS',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Nom Version OS',
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

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Printer' => 'Imprimante',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PowerConnection' => 'Connection Electrique',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PowerSource' => 'Arrivée électrique',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => '',
));

//
// Class: PDU
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nom rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Arrivée électrique',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nom Arrivée électrique',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Peripheral' => 'Périphérique',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Enclosure' => 'Chassis',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Nom rack',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'NB Unité',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Devices',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ApplicationSolution' => 'Solution applicative',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Processus métiers',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '',
	'Class:ApplicationSolution/Attribute:status' => 'Statut',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'active',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'active',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'inactive',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'inactive',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Analyse d\'impact : configuration de la redondance',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'La solution est opérationelle si tous les CIs qui la composent sont opérationnels',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Nombre minimal de CIs pour que la solution soit opérationnelle : %1$s',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Pourcentage minimal de CIs pour que la solution soit opérationnelle : %1$s %%',
));

//
// Class: BusinessProcess
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:BusinessProcess' => 'Processus métier',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Solutions applicatives',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '',
	'Class:BusinessProcess/Attribute:status' => 'Statut',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'actif',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'actif',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'inactif',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'inactif',
));

//
// Class: SoftwareInstance
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SoftwareInstance' => 'Instance logiciel',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Système',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Nom du système',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Logiciel',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Nom du logiciel',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licence logiciel',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Nom Licence logiciel',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Chemin d`installation',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Statut',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'actif',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'inactif',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '',
));

//
// Class: Middleware
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Instance Middleware',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '',
));

//
// Class: DBServer
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DBServer' => 'Serveur de base de données',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Instances de base de données',
	'Class:DBServer/Attribute:dbschema_list+' => '',
));

//
// Class: WebServer
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:WebServer' => 'Serveur Web',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Application Web',
	'Class:WebServer/Attribute:webapp_list+' => '',
));

//
// Class: PCSoftware
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PCSoftware' => 'Logiciel PC',
	'Class:PCsoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OtherSoftware' => 'Autre logiciel',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:MiddlewareInstance' => 'Instance Middleware',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Nom Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DatabaseSchema' => 'Instance de base de données',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Serveur de base de données',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Nom Serveur de base de données',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:WebApplication' => 'Application Web',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Serveur Web',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Nom Serveur Web',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));

//
// Class: VirtualDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:VirtualDevice' => 'Equipement Virtuel',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Statut',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'implémentation',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'implémentation',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'obsolète',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'obsolète',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'production',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'production',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'stock',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'stock',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Volumes logiques',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '',
));

//
// Class: VirtualHost
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:VirtualHost' => 'Hôte Virtuel',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Machines virtuelles',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '',
));

//
// Class: Hypervisor
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Hypervisor' => 'Hyperviseur',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'vCluster',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Nom vCluster',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Serveur',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Nom serveur',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Farm' => 'vCluster',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hyperviseurs',
	'Class:Farm/Attribute:hypervisor_list+' => '',
	'Class:Farm/Attribute:redundancy' => 'Haute disponibilité',
	'Class:Farm/Attribute:redundancy/disabled' => 'Le vCluster est opérationnel si tous les hyperviseurs qui le composent sont opérationnels',
	'Class:Farm/Attribute:redundancy/count' => 'Nombre minimal d\'hyperviseurs pour que le vCluster soit opérationnel : %1$s',
	'Class:Farm/Attribute:redundancy/percent' => 'Pourcentage minimal d\'hyperviseurs pour que le vCluster soit opérationnel : %1$s %%',
));

//
// Class: VirtualMachine
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:VirtualMachine' => 'Machine virtuelle',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'vCluster / Hyperviseur',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Nom Host',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Famille OS',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Nom Famille OS',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Version OS',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Nom Version OS',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licence OS',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Nom Licence OS',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Interfaces réseaux',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '',
));

//
// Class: LogicalVolume
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:LogicalVolume' => 'Volume logique',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Nom',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Description',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Niveau RAID',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Taille',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Système de stockage',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Nom Système de stockage',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servers',
	'Class:LogicalVolume/Attribute:servers_list+' => '',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Machines virtuelles',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '',
));

//
// Class: lnkServerToVolume
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkServerToVolume' => 'Lien Serveur / Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume logique',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Nom Volume logique',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Serveur',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Nom Serveur',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Taille utilisée',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkVirtualDeviceToVolume' => 'Lien Device virtuel / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume logique',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Nom Volume logique',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Device virtuel',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Nom Device virtuel',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Taille utilisée',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkSanToDatacenterDevice' => 'Lien San / Device',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'Switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Nom Switch SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Device',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Nom Device',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Device FC',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Tape' => 'Bande',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Nom',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Description',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Taille',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Bandothèque',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Nom Bandothèque',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:NASFileSystem' => 'Système de fichier NAS',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Nom',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Description',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Niveau RAID',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Taille',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Nom NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Software' => 'Logiciel',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nom',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Vendeur',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Version',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documents',
	'Class:Software/Attribute:documents_list+' => '',
	'Class:Software/Attribute:type' => 'Type',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Serveur de base de données',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Serveur de base de données',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Autre logiciel',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Autre logiciel',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'Logiciel PC',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'Logiciel PC',
	'Class:Software/Attribute:type/Value:WebServer' => 'Serveur Web',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Serveur Web',
	'Class:Software/Attribute:softwareinstance_list' => 'Instances logiciels',
	'Class:Software/Attribute:softwareinstance_list+' => '',
	'Class:Software/Attribute:softwarepatch_list' => 'Patchs logiciels',
	'Class:Software/Attribute:softwarepatch_list+' => '',
	'Class:Software/Attribute:softwarelicence_list' => 'Software licences',
	'Class:Software/Attribute:softwarelicence_list+' => '',
));

//
// Class: Patch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nom',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documents',
	'Class:Patch/Attribute:documents_list+' => '',
	'Class:Patch/Attribute:description' => 'Description',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Type',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OSPatch' => 'Patch OS',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Systèmes',
	'Class:OSPatch/Attribute:functionalcis_list+' => '',
	'Class:OSPatch/Attribute:osversion_id' => 'Version OS',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Nom Version OS',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SoftwarePatch' => 'Patch Logiciel',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Logiciel',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Nom logiciel',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instances logiciels',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '',
));

//
// Class: Licence
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Licence' => 'License',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Nom',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Documents',
	'Class:Licence/Attribute:documents_list+' => '',
	'Class:Licence/Attribute:org_id' => 'Organisation',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Nom organisation',
	'Class:Licence/Attribute:organization_name+' => 'Common name',
	'Class:Licence/Attribute:usage_limit' => 'Limite d\'utilisation',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Description',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Date de début de validité',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Date de fin de validité',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Clé',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Perpetuelle',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'non',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'non',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'oui',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'oui',
	'Class:Licence/Attribute:finalclass' => 'Type',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OSLicence' => 'Licence OS',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Version OS',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Nom Version OS',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Machines virtuelles',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '',
	'Class:OSLicence/Attribute:servers_list' => 'Serveurs',
	'Class:OSLicence/Attribute:servers_list+' => '',
));

//
// Class: SoftwareLicence
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:SoftwareLicence' => 'Licence Logiciel',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Logiciel',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Nom Logiciel',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instances logiciels',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToLicence' => 'Lien Document / Licence',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licence',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Nom Licence',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Nom Document',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Nom',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Type',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: OSVersion
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OSVersion' => 'Version OS',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Famille OS',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Nom Famille OS',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:OSFamily' => 'Famille OS',
	'Class:OSFamily+' => '',
));

//
// Class: DocumentType
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:DocumentType' => 'Type de document',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:ContactType' => 'Type de contact',
	'Class:ContactType+' => '',
));

//
// Class: Brand
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Brand' => 'Marque',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Matériels',
	'Class:Brand/Attribute:physicaldevices_list+' => '',
));

//
// Class: Model
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Model' => 'Modèle',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Marque',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Nom marque',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Type de matériel',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Arrivée électrique',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Arrivée électrique',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Chassis',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Chassis',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Téléphone IP',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'Téléphone IP',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Téléphone mobile',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Téléphone mobile',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Equipement réseau',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Equipement réseau',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Périphérique',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Périphérique',
	'Class:Model/Attribute:type/Value:Printer' => 'Imprimante',
	'Class:Model/Attribute:type/Value:Printer+' => 'Imprimante',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'Switch SAN',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'Switch SAN',
	'Class:Model/Attribute:type/Value:Server' => 'Serveur',
	'Class:Model/Attribute:type/Value:Server+' => 'Serveur',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Système de stockage',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'SSystème de stockage',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablette',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablette',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Bandothèque',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Bandothèque',
	'Class:Model/Attribute:type/Value:Phone' => 'Téléphone',
	'Class:Model/Attribute:type/Value:Phone+' => 'Téléphone',
	'Class:Model/Attribute:physicaldevices_list' => 'Matériels',
	'Class:Model/Attribute:physicaldevices_list+' => '',
));

//
// Class: NetworkDeviceType
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:NetworkDeviceType' => 'Type d\'équipement réseau',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Equipements réseaux',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '',
));

//
// Class: IOSVersion
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IOSVersion' => 'Version IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Marque',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Nom Marque',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToPatch' => 'Lien Document / Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Nom patch',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Nom document',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Lien Instance logiciel / Patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Nom patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instance logicielle',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Nom instance logicielle',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Lien CI Fonctionel / Patch OS',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Patch OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Nom Patch OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToSoftware' => 'Lien Document / Logiciel',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Logiciel',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Nom logiciel',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Nom document',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkContactToFunctionalCI' => 'Lien Contact / CI Fonctionel',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Nom contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkDocumentToFunctionalCI' => 'Lien Document / CI Fonctionel',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Nom Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Description',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organisation',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Nom organisation',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Masque IP',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Description',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organisation',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Nom organisation',
	'Class:VLAN/Attribute:org_name+' => 'Common name',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Interfaces réseaux physiques',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkSubnetToVLAN' => 'Lien Subnet / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Nom Subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));
//
// Class: NetworkInterface
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:NetworkInterface' => 'Interface Réseau',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Nom',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Type',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:IPInterface' => 'Interface IP',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'Adresse IP',
	'Class:IPInterface/Attribute:ipaddress+' => '',
	'Class:IPInterface/Attribute:macaddress' => 'Adresse MAC',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:coment' => 'Commentaire',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'Passerelle',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'Masque de sous réseau',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Vitesse',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:PhysicalInterface' => 'Interface physique',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Matériel',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Nom matériel',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Lien Interface réseau / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Interface réseau',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Nom interface réseau',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Equipement',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Nom équipement',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: LogicalInterface
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:LogicalInterface' => 'Interface logique',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Machine virtuelle',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Nom Machine virtuelle',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:FiberChannelInterface' => 'Interface Fibre',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Vitesse',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topologie',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Matériel',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Nom Matériel',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Lien Device / Equipement réseau',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Equipement réseau',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Nom Equipement réseau',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Matériel connecté',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Nom Matériel connecté',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Port réseau',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Port matériel',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Type de connection',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'lien descendant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'lien descendant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'lien montant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'lien montant',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Lien Solution Applicative / CI Fonctionel',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Solution applicative',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Nom Solution applicative',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Lien ApplicationSolutionToSolution Applicative / Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Nom Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Solution applicative',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Nom Solution applicative',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkPersonToTeam' => 'Lien Personne / Equipe',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Equipe',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Nom Equipe',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Personne',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Nom Personne',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rôle',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Nom Role',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Class: Group
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Group' => 'Groupe',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Nom',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Statut',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implémentation',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implémentation',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsolète',
	'Class:Group/Attribute:status/Value:production' => 'Production',
	'Class:Group/Attribute:status/Value:production+' => 'Production',
	'Class:Group/Attribute:org_id' => 'Organisation',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Nom organisation',
	'Class:Group/Attribute:owner_name+' => '',
	'Class:Group/Attribute:description' => 'Description',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Type',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Groupe parent',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Nom groupe parent',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'CIs liés',
	'Class:Group/Attribute:ci_list+' => '',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Nom usuel du parent',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkGroupToCI' => 'Lien Groupe / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Groupe',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nom du groupe',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nom du CI',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Raison',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));





//
// Class extensions
//

Dict::Add('FR FR', 'French', 'Français', array(
'Class:Subnet/Tab:IPUsage' => 'IP Utilisées',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces ayant une IP dans l\'interval: <em>%1$s</em> à <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IPs libres',
'Class:Subnet/Tab:FreeIPs-count' => 'IPs libres: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Extrait des 10 premières IPs libres',
));


Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:DataAdministration' => 'Administration des données',
	'Menu:DataAdministration+' => 'Administration des données',
	'Menu:Catalogs' => 'Catalogues',
	'Menu:Catalogs+' => 'Types de données',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'Import CSV',
	'Menu:CSVImport+' => 'Import ou mise à jour en masse',
	'Menu:Organization' => 'Organisations',
	'Menu:Organization+' => 'Toutes les organisations',
	'Menu:Application' => 'Logiciels',
	'Menu:Application+' => 'Tous les logiciels',
	'Menu:DBServer' => 'Serveur de base de données',
	'Menu:DBServer+' => '',
	'Menu:ConfigManagement' => 'Gestion des configurations',
	'Menu:ConfigManagement+' => 'Gestion des configurations',
	'Menu:ConfigManagementOverview' => 'Tableaux de bord',
	'Menu:ConfigManagementOverview+' => 'Tableaux de bord',
	'Menu:Contact' => 'Contacts',
	'Menu:Contact+' => 'Contacts',
	'Menu:Contact:Count' => '%1$d contacts',
	'Menu:Person' => 'Personnes',
	'Menu:Person+' => 'Toutes les personnes',
	'Menu:Team' => 'Equipes',
	'Menu:Team+' => 'Toutes les équipes',
	'Menu:Document' => 'Documents',
	'Menu:Document+' => 'Tous les documents',
	'Menu:Location' => 'Lieux',
	'Menu:Location+' => 'Tous les lieux',
	'Menu:ConfigManagementCI' => 'CIs',
	'Menu:ConfigManagementCI+' => 'CIs',
	'Menu:BusinessProcess' => 'Processus métier',
	'Menu:BusinessProcess+' => 'Tous les processus métiers',
	'Menu:ApplicationSolution' => 'Solutions applicatives',
	'Menu:ApplicationSolution+' => 'Toutes les solutions applicatives',
	'Menu:ConfigManagementSoftware' => 'Gestion des logiciels',
	'Menu:Licence' => 'Licences',
	'Menu:Licence+' => 'Toutes les licences',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'Tous les patches',
	'Menu:ApplicationInstance' => 'Instances logiciels',
	'Menu:ApplicationInstance+' => 'Logiciels installés',
	'Menu:ConfigManagementHardware' => 'Gestion des infrastructures',
	'Menu:Subnet' => 'Sous réseaux',
	'Menu:Subnet+' => '',
	'Menu:NetworkDevice' => 'Equipements réseaux',
	'Menu:NetworkDevice+' => 'Tous les équipements réseaux',
	'Menu:System' => 'Systèmes',
	'Menu:System+' => '',
	'Menu:Server' => 'Serveurs',
	'Menu:Server+' => '',
	'Menu:Printer' => 'Imprimantes',
	'Menu:Printer+' => 'Toutes les imprimantes',
	'Menu:MobilePhone' => 'Téléphones portables',
	'Menu:MobilePhone+' => 'Tous les téléphones portables',
	'Menu:PC' => 'PCs',
	'Menu:PC+' => 'Tous les PCs',
	'Menu:NewContact' => 'Nouveau contact',
	'Menu:NewContact+' => 'Nouveau contact',
	'Menu:SearchContacts' => 'Rechercher des contacts',
	'Menu:SearchContacts+' => 'Rechercher des contacts',
	'Menu:NewCI' => 'Nouveau CI',
	'Menu:NewCI+' => 'Nouveau CI',
	'Menu:SearchCIs' => 'Rechercher des CIs',
	'Menu:SearchCIs+' => 'Rechercher des CIs',
	'Menu:ConfigManagement:Devices' => 'Equipements',
	'Menu:ConfigManagement:AllDevices' => 'Infrastructures',
	'Menu:ConfigManagementInfrastructure' => 'Infrastructures',
	'Menu:ConfigManagement:SWAndApps' => 'Logiciels et applications',
	'Menu:ConfigManagement:Misc' => 'Divers',
	'Menu:Group' => 'Groupe de CIs',
	'Menu:Group+' => 'Groupe de CIs',
	'Menu:ConfigManagement:Shortcuts' => 'Raccourcis',
	'Menu:ConfigManagement:AllContacts' => 'Tous les contacts: %1$d',
	'Menu:DocumentType' => 'Types de documents',
	'Menu:DocumentType+' => '',
	'Menu:Software' => 'Catalogue des logiciels de références',
	'Menu:Software+' => 'Catalogue des logiciels de références',
	'Menu:Model' => 'Modèles',
	'Menu:Model+' => 'Modèles',
	'Menu:Brand+' => 'Marques',
	'Menu:Brand' => 'Marques',
	'Menu:NetworkType' => 'Types réseau',
	'Menu:NetworkType+' => '',
	'Menu:Typology' => 'Typologie configuration',
	'Menu:Typology+' => 'Typologie configuration',
	'Menu:OSVersion' => 'Versions d\'OS',
	'Menu:OSVersion+' => '',
	'Menu:ContactType' => 'Types de contact',
	'Menu:ContactType+' => '',
	'Menu:LicenceType' => 'Types de licence',
	'Menu:LicenceType+' => '',
	'Menu:Environment' => 'Environnements',
	'Menu:Environment+' => '',
	'Menu:PeripheralType' => 'Type de périphérique',
	'Menu:PeripheralType+' => 'Tous les types de périphérique',
	'UI-ConfigMgmtMenuOverview-DeviceBySite' => 'Equipements par site',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByTypeStock' => 'Equipements en stock par type',
	'UI-ConfigMgmtMenuOverview-DeviceByBrand' => 'Equipements par marque',
	'UI-ConfigMgmtMenuOverview-DeviceToRenew' => 'Equipements à remplacer dans 6 mois',
	'Menu:UI_WelcomeMenu_AllConfigItems' => 'Résumé',
	'Relation:impacts/Description' => 'Eléments impactés par',
	'Relation:impacts/DownStream' => 'Impacte...',
	'Relation:impacts/UpStream' => 'Dépend de...',
	'Relation:depends on/Description' => 'Eléments dont dépend',
	'Relation:depends on/DownStream' => 'Dépend de...',
	'Relation:depends on/UpStream' => 'Impacte...',
	'Menu:ConfigManagement:Typology' => 'Configuration des typologies',
));

// Add translation for Fieldsets

Dict::Add('FR FR', 'French', 'Français', array(
'Server:baseinfo' => 'Informations générales',
'Server:Date' => 'Dates',
'Server:moreinfo' => 'Informations complémentaires',
'Server:otherinfo' => 'Autres informations',
'Server:power' => 'Alimentation électrique',
'Person:info' => 'Informations générales',
'Person:notifiy' => 'Notification',
'Class:Subnet/Tab:IPUsage' => 'IP utilisées',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces ayant une IP dans la plage: <em>%1$s</em> à <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IP disponibles',
'Class:Subnet/Tab:FreeIPs-count' => 'IP disponibles: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Voici un échantillon de dix addresses IP disponibles',
'Class:Document:PreviewTab' => 'Aperçu',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modèle de support',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nom modèle de support',
	'Class:Person/Attribute:name' => 'Nom',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Baie de disques',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Baie de disques',
	'Class:Subnet/Attribute:subnet_name' => 'Nom de subnet',
	'Class:IPInterface/Attribute:comment' => 'Commentaire',
	'Menu:ConfigManagement:virtualization' => 'Virtualisation',
	'Menu:ConfigManagement:EndUsers' => 'Périphériques utilisateurs',
	'UI_WelcomeMenu_AllConfigItems' => 'Résumé',
));
?>

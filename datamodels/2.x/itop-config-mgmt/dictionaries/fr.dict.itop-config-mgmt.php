<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Relation:impacts/Description' => 'Eléments impactés par',
	'Relation:impacts/DownStream' => 'Impacte...',
	'Relation:impacts/DownStream+' => 'Eléments impactés par',
	'Relation:impacts/UpStream' => 'Dépend de...',
	'Relation:impacts/UpStream+' => 'Eléments dont dépend',
	'Relation:depends on/Description' => 'Eléments dont dépend',
	'Relation:depends on/DownStream' => 'Dépend de...',
	'Relation:depends on/UpStream' => 'Impacte...',
	'Relation:impacts/LoadData' => 'Charger les données',
	'Relation:impacts/NoFilteredData' => 'Veuillez sélectionner des objets et lancer le chargement des données',
	'Relation:impacts/FilteredData' => 'Données filtrées',
]);

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
// Class: lnkContactToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkContactToFunctionalCI' => 'Lien Contact / CI fonctionnel',
	'Class:lnkContactToFunctionalCI+' => 'Gère les Contacts liés à des CI Fonctionnels. Ca peut être une équipe responsable de l\'équipment, de façon à lui affecter les Tickets liés à cet équipment ou la personne à laquelle un équipment individuel comme un PC ou un téléphone est affecté, de façon à gérer le parc.',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Nom contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
]);

//
// Class: FunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:FunctionalCI' => 'CI fonctionnel',
	'Class:FunctionalCI+' => 'Classe abstraite regroupant la plupart des types d’éléments de configuration de la CMDB.',
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
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Tous les contacts de cet élément de configuration',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documents',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Tous les documents liés à cet élément de configuration',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Solutions applicatives',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Toutes les solutions applicatives dépendantes de cet élément de configuration',
	'Class:FunctionalCI/Attribute:applicationsolution_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:FunctionalCI/Attribute:applicationsolution_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:FunctionalCI/Attribute:applicationsolution_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:FunctionalCI/Attribute:applicationsolution_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Logiciels',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Tous les logiciels installés sur cet élément de configuration',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:FunctionalCI/Attribute:softwares_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:FunctionalCI/Attribute:finalclass' => 'Sous-classe de CI',
	'Class:FunctionalCI/Attribute:finalclass+' => 'Nom de la classe instanciable',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Tickets en cours',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Tickets ouverts impactant cet élément de configuration',
]);

//
// Class: PhysicalDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PhysicalDevice' => 'Matériel physique',
	'Class:PhysicalDevice+' => 'Classe abstraite regroupant les types physiques d’éléments de configuration. Un Matériel physique peut être localisé. Il possède généralement une Marque et un Modèle.',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Numéro de série',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Site',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nom site',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Etat',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Implémentation',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Production',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Stock',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '',
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
]);

//
// Class: Rack
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Rack' => 'Baie',
	'Class:Rack+' => 'Conteneur physique pour Matériel de datacenter et Châssis.',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s',
	'Class:Rack/Attribute:nb_u' => 'NB Unité',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Matériels',
	'Class:Rack/Attribute:device_list+' => 'Tous les matériels rackés dans ce rack',
	'Class:Rack/Attribute:device_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Rack/Attribute:device_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Rack/Attribute:device_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Rack/Attribute:device_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Rack/Attribute:device_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Rack/Attribute:device_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Rack/Attribute:enclosure_list' => 'Chassis',
	'Class:Rack/Attribute:enclosure_list+' => 'Tous les chassis dans ce rack',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Rack/Attribute:enclosure_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: TelephonyCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:TelephonyCI' => 'Équipement de téléphonie',
	'Class:TelephonyCI+' => 'Classe abstraite regroupant les équipements de téléphonie.',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Numéro',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
]);

//
// Class: Phone
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Phone' => 'Téléphone',
	'Class:Phone+' => 'Équipement individuel. Téléphone filaire classique.',
]);

//
// Class: MobilePhone
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:MobilePhone' => 'Téléphone mobile',
	'Class:MobilePhone+' => 'Équipement individuel. Téléphone portable.',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
]);

//
// Class: IPPhone
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:IPPhone' => 'Téléphone IP',
	'Class:IPPhone+' => 'Équipement individuel. Équipement physique dédié aux appels téléphoniques, connecté à un réseau.',
]);

//
// Class: Tablet
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Tablet' => 'Tablette',
	'Class:Tablet+' => 'Équipement individuel. Par exemple iPad, Galaxy Note/Tab Nexus, Kindle...',
]);

//
// Class: ConnectableCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:ConnectableCI' => 'Matériel connecté',
	'Class:ConnectableCI+' => 'Matériel physique pouvant être connecté à un réseau.',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Equipements réseaux',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Tous les équipements réseaux connectés à ce matériel',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interfaces réseaux',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Toutes les interfaces réseaux physiques',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:ConnectableCI/Attribute:physicalinterface_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: DatacenterDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DatacenterDevice' => 'Matériel de datacenter',
	'Class:DatacenterDevice+' => 'Un équipement physique, connecté au réseau et installé dans un datacenter, généralement dans une Baie ou un Châssis. Il peut s’agir de Serveurs, d\'Équipement réseau, de Systèmes de Stockage, de Switchs SAN, de Bandothèques, de NAS…',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s',
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
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Toutes les interfaces fibre optique de ce matériel',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Tous les switchs SAN connectés à ce matériel',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redondance',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Le %2$s est alimenté si au moins une source électrique (A ou B) est opérationnelle',
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Le %2$s est alimenté si toutes ses sources électriques sont opérationnelles',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Le %2$s est alimenté si au moins %1$s %% de ses sources électriques sont opérationnelles',
]);

//
// Class: NetworkDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:NetworkDevice' => 'Équipement réseau',
	'Class:NetworkDevice+' => 'Tout type d’équipement réseau : routeur, switch, hub, load balancer, firewall…',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Type',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Nom Type',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Matériel connecté',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Tous les équipements connectés à cet appareil réseau',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Version IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Nom Version IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
]);

//
// Class: Server
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Server' => 'Serveur',
	'Class:Server+' => 'Matériel de Datacenter qui fournit des ressources de calcul, de stockage ou de connectivité. Il tourne sous une Version d\'OS et héberge des Applications Logicielles.',
	'Class:Server/ComplementaryName' => '%1$s - %2$s',
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
	'Class:Server/Attribute:logicalvolumes_list+' => 'Tous les volumes logiques connectés à ce serveur',
]);

//
// Class: StorageSystem
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:StorageSystem' => 'Système de stockage',
	'Class:StorageSystem+' => 'Système de stockage pouvant être connecté à un SAN ou à un réseau Ethernet. L\'unité logique de stockage gérée par un Système de stockage est un Volume logique.',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Volumes logiques',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Tous les volumes logiques dans ce système de stockage',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:StorageSystem/Attribute:logicalvolume_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: SANSwitch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:SANSwitch' => 'Switch SAN',
	'Class:SANSwitch+' => 'Matériel de Datacenter. C\'est un switch utilisé par les réseaux de stockage (Storage Area Network). Il supporte le protocole Fibre Channel.',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Matériels connectés',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Tous les matériels connectés à ce switch SAN',
]);

//
// Class: TapeLibrary
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:TapeLibrary' => 'Bandothèque',
	'Class:TapeLibrary+' => 'Matériel connecté et rackable hébergeant plusieurs bandes magnétiques (ou cartouches). Utilisé pour la sauvegarde ou l’archivage.',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Bandes',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Toutes les bandes dans cette bandothèque',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:TapeLibrary/Attribute:tapes_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de sa %1$s',
]);

//
// Class: NAS
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:NAS' => 'NAS',
	'Class:NAS+' => 'Matériel connecté et rackable fournissant un stockage de haute capacité. Dans '.ITOP_APPLICATION_SHORT.', un NAS (Network-attached storage) contient des Systèmes de fichiers NAS.',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Systèmes de fichier NAS',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Tous les systèmes de fichier dans ce NAS',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:NAS/Attribute:nasfilesystem_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: PC
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PC' => 'PC',
	'Class:PC+' => 'Elément de configuration (CI), un ordinateur personnel (PC) est un matériel physique, de bureau ou portable, tournant avec une version d\'OS et conçu pour exécuter des instances logicielles.',
	'Class:PC/ComplementaryName' => '%1$s - %2$s',
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
]);

//
// Class: Printer
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Printer' => 'Imprimante',
	'Class:Printer+' => 'Elément de configuration (CI) connectable. Matériel physique connecté au réseau ou à un PC.',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: PowerConnection
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PowerConnection' => 'Connexion électrique',
	'Class:PowerConnection+' => 'Classe abstraite regroupant les équipements physiques utilisés pour l\'alimentation électrique.',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: PowerSource
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PowerSource' => 'Arrivée électrique',
	'Class:PowerSource+' => 'Connexion électrique physique. Utilisée dans un datacenter pour documenter toute source d\'alimentation (arrivée principale, disjoncteur…) qui n\'est pas une PDU.',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'Toutes les PDUs de cette arrivée électrique',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:PowerSource/Attribute:pdus_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: PDU
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PDU' => 'PDU',
	'Class:PDU+' => 'Connexion électrique. Une PDU (Power Distribution Unit) est un équipement doté de multiples sorties conçu pour distribuer l\'alimentation électrique, notamment vers les racks d\'ordinateurs et équipements réseau d\'un datacenter.',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nom rack',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Arrivée électrique',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nom Arrivée électrique',
	'Class:PDU/Attribute:powerstart_name+' => '',
]);

//
// Class: Peripheral
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Peripheral' => 'Périphérique',
	'Class:Peripheral+' => 'Périphérique physique, utilisé pour documenter tout type de périphérique informatique.
Par exemple : disques durs externes, scanners, dispositifs d\'entrée (trackballs, lecteurs de codes-barres), etc…',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: Enclosure
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Enclosure' => 'Châssis',
	'Class:Enclosure+' => 'Une armoire permettant d\'installer des équipements informatiques garantissant un flux d\'air optimisé et une alimentaion sécurisée. Dans '.ITOP_APPLICATION_SHORT.', un châssis peut être montée à l\'intérieur d\'une Baie ou fixée directement au mur d\'un centre de données.',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Nom rack',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'NB Unité',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Devices',
	'Class:Enclosure/Attribute:device_list+' => 'Tous les matériels dans ce chassis',
	'Class:Enclosure/Attribute:device_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Enclosure/Attribute:device_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Enclosure/Attribute:device_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Enclosure/Attribute:device_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Enclosure/Attribute:device_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Enclosure/Attribute:device_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: ApplicationSolution
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:ApplicationSolution' => 'Solution applicative',
	'Class:ApplicationSolution+' => 'Les solutions applicatives décrivent des applications complexes composées de plusieurs composants de base. L’information principale est la liste de ses relations. Elle peut aussi être utilisée pour modéliser la relation entre un ou plusieurs controlleurs et les équipements qu\'il(s) gérent (par exemple des applicatifs de supervision, de gestion de configuration ou d\'analyse de performance).',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Tous les éléments de configuration qui composent cette solution applicative',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Processus métiers',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Tous les processus métiers dépendants de cette solution applicative',
	'Class:ApplicationSolution/Attribute:logo' => 'Logo',
	'Class:ApplicationSolution/Attribute:logo+' => 'Utilisé comme icône de l\'objet dans les graphes d\'analyse d\'impact',
	'Class:ApplicationSolution/Attribute:status' => 'Etat',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Actif',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inactif',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Analyse d\'impact : configuration de la redondance',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'La solution est opérationnelle si tous les CIs qui la composent sont opérationnels',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Nombre minimal de CIs pour que la solution soit opérationnelle : %1$s',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Pourcentage minimal de CIs pour que la solution soit opérationnelle : %1$s %%',
]);

//
// Class: BusinessProcess
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:BusinessProcess' => 'Processus métier',
	'Class:BusinessProcess+' => 'Un processus métier sert à documenter un processus de haut niveau ou une application importante pour les opérations. Similaire à une solution applicative mais pour des applications ou processus d’organisation de plus haut niveau.',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Solutions applicatives',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Toutes les solutions applicatives qui impactent ce processus métier',
	'Class:BusinessProcess/Attribute:applicationsolutions_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:BusinessProcess/Attribute:applicationsolutions_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:BusinessProcess/Attribute:applicationsolutions_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:BusinessProcess/Attribute:applicationsolutions_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
	'Class:BusinessProcess/Attribute:logo' => 'Logo',
	'Class:BusinessProcess/Attribute:logo+' => 'Utilisé comme icône de l\'objet dans les graphes d\'analyse d\'impact',
	'Class:BusinessProcess/Attribute:status' => 'Etat',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Actif',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inactif',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '',
]);

//
// Class: SoftwareInstance
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:SoftwareInstance' => 'Instance logiciel',
	'Class:SoftwareInstance+' => 'Classe abstraite représentant le déploiement d’un Logiciel sur un équipement (Serveur, PC, Machine virtuelle). Dans '.ITOP_APPLICATION_SHORT.', il existe différents types d’instances logicielles : Serveur de base de données, Middleware, Logiciel PC, Serveur web ou Autre logiciel.',
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
	'Class:SoftwareInstance/Attribute:path' => 'Chemin d\'installation',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Etat',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Actif',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inactif',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '',
]);

//
// Class: Middleware
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => 'Instance logicielle offrant des services à d\'autres logiciels (ex : Tomcat, JBoss, Talend, Microsoft BizTalk, IBM Websphere ou Lotus Domino) installée sur un système spécifique (PC, Serveur ou Machine virtuelle).',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Instance Middleware',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Toutes les instances de middleware fournies par ce middleware',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Middleware/Attribute:middlewareinstance_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: DBServer
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DBServer' => 'Serveur de base de données',
	'Class:DBServer+' => 'Instance logicielle offrant des services de base de données (comme MySQL 8.0, Oracle, SQL Server, DB2…) installée sur un système spécifique (PC, Serveur ou Machine virtuelle).',
	'Class:DBServer/Attribute:dbschema_list' => 'Instances de base de données',
	'Class:DBServer/Attribute:dbschema_list+' => 'Toutes les instances de base de données pour ce serveur',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:DBServer/Attribute:dbschema_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: WebServer
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:WebServer' => 'Serveur web',
	'Class:WebServer+' => 'Instance logicielle offrant des services Web (comme Apache 2.4, Nginx 1.29.4, IIS 7.0) installée sur un système spécifique (PC, Serveur ou Machine virtuelle).',
	'Class:WebServer/Attribute:webapp_list' => 'Application Web',
	'Class:WebServer/Attribute:webapp_list+' => 'Toutes les applications Web disponibles sur ce serveur',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:WebServer/Attribute:webapp_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: PCSoftware
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PCSoftware' => 'Logiciel PC',
	'Class:PCSoftware+' => 'Instance logicielle pour des logiciels (ex : MS Office, Photoshop, Filezilla) installés sur un PC.',
]);

//
// Class: OtherSoftware
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OtherSoftware' => 'Autre logiciel',
	'Class:OtherSoftware+' => 'Tout type d\'instance logicielle qui ne rentre pas dans les autres catégories : Logiciel PC, Middleware, Serveur de base de données ou Serveur Web.',
]);

//
// Class: MiddlewareInstance
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:MiddlewareInstance' => 'Instance de middleware',
	'Class:MiddlewareInstance+' => 'CI fonctionnel représentant un service délivré par un Middleware.',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s',
	'Class:MiddlewareInstance/Attribute:logo' => 'Logo',
	'Class:MiddlewareInstance/Attribute:logo+' => 'Utilisé comme icône de l\'objet dans les graphes d\'analyse d\'impact',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Nom Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
]);

//
// Class: DatabaseSchema
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DatabaseSchema' => 'Instance de base de données',
	'Class:DatabaseSchema+' => 'Instance de base de données géré par un Serveur de base de données.',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Serveur de base de données',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Nom Serveur de base de données',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
]);

//
// Class: WebApplication
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:WebApplication' => 'Application web',
	'Class:WebApplication+' => 'Instance d’une application accessible via un navigateur web et s’exécutant sur un Serveur web donné. Par exemple cet iTop.',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s',
	'Class:WebApplication/Attribute:webserver_id' => 'Serveur Web',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Nom Serveur Web',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:logo' => 'Logo',
	'Class:WebApplication/Attribute:logo+' => 'Utilisé comme icône de l\'objet dans les graphes d\'analyse d\'impact',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
]);

//
// Class: VirtualDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:VirtualDevice' => 'Équipement virtuel',
	'Class:VirtualDevice+' => 'Classe abstraite utilisée pour la virtualisation de serveurs (Hôte virtuel et Machine virtuelle).',
	'Class:VirtualDevice/Attribute:status' => 'Etat',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Implémentation',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Production',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Stock',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Volumes logiques',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Tous les volumes logiques utilisés par ce matériel',
]);

//
// Class: VirtualHost
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:VirtualHost' => 'Hôte virtuel',
	'Class:VirtualHost+' => 'Classe abstraite pour les Équipements virtuels (Hyperviseur, vCluster,...) hébergeant des Machines virtuelles.',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Machines virtuelles',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Toutes les machiens virtuelles hébergées par cet hôte',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:VirtualHost/Attribute:virtualmachine_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: Hypervisor
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Hypervisor' => 'Hyperviseur',
	'Class:Hypervisor+' => 'Hôte virtuel. Logiciel de virtualisation (MS Hyper-V, VMWare ESX, Xen, etc.) s\'exécutant sur un serveur physique et permettant la création de machines virtuelles.',
	'Class:Hypervisor/Attribute:farm_id' => 'vCluster',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Nom vCluster',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Serveur',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Nom serveur',
	'Class:Hypervisor/Attribute:server_name+' => '',
]);

//
// Class: Farm
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Farm' => 'vCluster',
	'Class:Farm+' => 'Hôte virtuel. Une ferme (ou vCluster) est un groupe d\'hyperviseurs mutualisés partageant des ressources de stockage afin de fournir un système tolérant aux pannes pour héberger des Machines virtuelles.',
	'Class:Farm/Attribute:hypervisor_list' => 'Hyperviseurs',
	'Class:Farm/Attribute:hypervisor_list+' => 'Tous les hyperviseurs qui composent ce vCluster',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Farm/Attribute:hypervisor_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Farm/Attribute:redundancy' => 'Haute disponibilité',
	'Class:Farm/Attribute:redundancy/disabled' => 'Le vCluster est opérationnel si tous les hyperviseurs qui le composent sont opérationnels',
	'Class:Farm/Attribute:redundancy/count' => 'Nombre minimal d\'hyperviseurs pour que le vCluster soit opérationnel : %1$s',
	'Class:Farm/Attribute:redundancy/percent' => 'Pourcentage minimal d\'hyperviseurs pour que le vCluster soit opérationnel : %1$s %%',
]);

//
// Class: VirtualMachine
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:VirtualMachine' => 'Machine virtuelle',
	'Class:VirtualMachine+' => 'Équivalent virtuel d\'un serveur, hébergé soit sur un Hyperviseur soit sur une ferme (ou vCluster).',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s',
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
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Toutes les interfaces réseaux logiques',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:VirtualMachine/Attribute:logicalinterface_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: LogicalVolume
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:LogicalVolume' => 'Volume logique',
	'Class:LogicalVolume+' => 'Unité de stockage gérée à l’intérieur d’un Système de stockage. Elle peut être utilisée par plusieurs Serveurs et Équipement virtuels.',
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
	'Class:LogicalVolume/Attribute:servers_list' => 'Serveurs',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Tous les serveurs utilisant ce volume',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Machines virtuelles',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Toutes les machines virtuelles utilisant ce volume',
]);

//
// Class: lnkServerToVolume
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkServerToVolume' => 'Lien Serveur / Volume  logique',
	'Class:lnkServerToVolume+' => 'Ce lien n:n indique qu\'un Serveur utilise un Volume logique (une unité de stockage gérée à l’intérieur d’un Système de stockage). Plusieurs Serveurs peuvent utiliser le même Volume logique.',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkVirtualDeviceToVolume' => 'Lien Équipement virtuel / Volume logique',
	'Class:lnkVirtualDeviceToVolume+' => 'Ce lien n:n indique qu\'un Équipement virtuel utilise un Volume logique (une unité de stockage gérée à l’intérieur d’un Système de stockage). Plusieurs Équipements virtuels peuvent utiliser le même Volume logique.',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkSanToDatacenterDevice' => 'Lien Switch SAN / Matériel de datacenter',
	'Class:lnkSanToDatacenterDevice+' => 'Ce lien n:n modélise la connection réseau entre un Switch SAN et un Matériel de datacenter (un Serveur, un Équipement réseau, etc..).',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s',
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
]);

//
// Class: Tape
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Tape' => 'Bande',
	'Class:Tape+' => 'Une Bande (ou cartouche) dans '.ITOP_APPLICATION_SHORT.' est un élément de stockage amovible au sein d\'une Bandothèque.',
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
]);

//
// Class: NASFileSystem
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:NASFileSystem' => 'Système de fichier NAS',
	'Class:NASFileSystem+' => 'Représente un système de fichiers partagé hébergé dans un NAS donné (Network Attached Storage).',
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
]);

//
// Class: Software
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Software' => 'Logiciel',
	'Class:Software+' => 'Un logiciel est un élément générique du catalogue logiciel. Il possède une version particulière. Dans '.ITOP_APPLICATION_SHORT.', un logiciel appartient à une catégorie : Serveur de BDD, Middleware, Logiciel PC, Serveur web ou autre.',
	'Class:Software/ComplementaryName' => '%1$s - %2$s',
	'Class:Software/Attribute:name' => 'Nom',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Vendeur',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Version',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documents',
	'Class:Software/Attribute:documents_list+' => 'Tous les documents liés à ce logiciel',
	'Class:Software/Attribute:logo' => 'Logo',
	'Class:Software/Attribute:logo+' => 'Utilisé comme icône des Instances qui utilisent ce Logiciel, lors de leur affichage dans les graphes d\'analyse d\'impact',
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
	'Class:Software/Attribute:softwareinstance_list+' => 'Toutes les instances de ce logiciel',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Software/Attribute:softwareinstance_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:Software/Attribute:softwarepatch_list' => 'Patchs logiciels',
	'Class:Software/Attribute:softwarepatch_list+' => 'Tous les patchs de ce logiciel',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Software/Attribute:softwarepatch_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Software/Attribute:softwarelicence_list' => 'Software licences',
	'Class:Software/Attribute:softwarelicence_list+' => 'Toutes les licences de ce logiciel',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Software/Attribute:softwarelicence_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: Patch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Patch' => 'Patch',
	'Class:Patch+' => 'Classe abstraite pour les patchs, hotfixes, correctifs de sécurité ou service packs pour un OS ou un logiciel.',
	'Class:Patch/Attribute:name' => 'Nom',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documents',
	'Class:Patch/Attribute:documents_list+' => 'Tous les documents liés à ce patch',
	'Class:Patch/Attribute:description' => 'Description',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Sous-classe de Patch',
	'Class:Patch/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: OSPatch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OSPatch' => 'Patch OS',
	'Class:OSPatch+' => 'Patch, hotfix, correctif de sécurité ou pack de services pour un système d\'exploitation donné.',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Systèmes',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Tous les systèmes où ce patch est installé',
	'Class:OSPatch/Attribute:osversion_id' => 'Version OS',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Nom Version OS',
	'Class:OSPatch/Attribute:osversion_name+' => '',
]);

//
// Class: SoftwarePatch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:SoftwarePatch' => 'Patch logiciel',
	'Class:SoftwarePatch+' => 'Patch, hotfix, correctif de sécurité ou pack de services pour un logiciel donné.',
	'Class:SoftwarePatch/Attribute:software_id' => 'Logiciel',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Nom logiciel',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instances logiciels',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Tous les systèmes où ce logiciel est installé',
	'Class:SoftwarePatch/Attribute:softwareinstances_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:SoftwarePatch/Attribute:softwareinstances_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:SoftwarePatch/Attribute:softwareinstances_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:SoftwarePatch/Attribute:softwareinstances_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
]);

//
// Class: Licence
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Licence' => 'Licence',
	'Class:Licence+' => 'Classe abstraite. Contrat de licence pour une version d\'OS ou un logiciel particulier.',
	'Class:Licence/Attribute:name' => 'Nom',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Documents',
	'Class:Licence/Attribute:documents_list+' => 'Tous les documents liés à cette licence',
	'Class:Licence/Attribute:org_id' => 'Organisation',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Nom organisation',
	'Class:Licence/Attribute:organization_name+' => 'Common name',
	'Class:Licence/Attribute:usage_limit' => 'Limite d\'utilisation',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Location/Attribute:physicaldevice_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
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
	'Class:Licence/Attribute:finalclass' => 'Sous-classe de Licence',
	'Class:Licence/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: OSLicence
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OSLicence' => 'Licence OS',
	'Class:OSLicence+' => 'Contrat de licence pour un système d’exploitation particulier. Le contrat peut couvrir le système d\'exploitation de plusieurs serveurs et machines virtuelles.',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:OSLicence/Attribute:osversion_id' => 'Version OS',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Nom Version OS',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Machines virtuelles',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Toutes les machines virtuelles où cette licence est utilisée',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:OSLicence/Attribute:virtualmachines_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de sa %1$s',
	'Class:OSLicence/Attribute:servers_list' => 'Serveurs',
	'Class:OSLicence/Attribute:servers_list+' => 'Tous les serveurs où cette licence est utilisée',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:OSLicence/Attribute:servers_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de sa %1$s',
]);

//
// Class: SoftwareLicence
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:SoftwareLicence' => 'Licence logiciel',
	'Class:SoftwareLicence+' => 'Contrat de licence pour un logiciel particulier. La licence est liée à un logiciel (par exemple MS Office 2010) et peut être associée à plusieurs instances de ce logiciel.',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:SoftwareLicence/Attribute:software_id' => 'Logiciel',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Nom Logiciel',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instances logiciels',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Tous les systèmes où cette licence est utilisée',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:SoftwareLicence/Attribute:softwareinstance_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de sa %1$s',
]);

//
// Class: lnkDocumentToLicence
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkDocumentToLicence' => 'Lien Document / Licence',
	'Class:lnkDocumentToLicence+' => 'Lien utilisé lorsqu\'un Document est applicable à une Licence.',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licence',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Nom Licence',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Nom Document',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
]);

//
// Class: OSVersion
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OSVersion' => 'Version d\'OS',
	'Class:OSVersion+' => 'Typologie. Liste des valeurs possibles pour la « Version d\'OS » d\'un ordinateur (serveur, machine virtuelle ou PC). Les versions d\'OS sont organisées par famille d\'OS.',
	'Class:OSVersion/Attribute:osfamily_id' => 'Famille OS',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Nom Famille OS',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
	'Class:OSVersion/UniquenessRule:name_osfamily+' => 'Le nom doit être unique au sein de cette famille d\'OS',
	'Class:OSVersion/UniquenessRule:name_osfamily' => 'cette version d\'OS existe déjà dans cette famille',
	'Class:OSVersion/Attribute:ospatches_list' => 'Patchs OS',
	'Class:OSVersion/Attribute:ospatches_list+' => 'Tous les patchs de cette version OS',
]);

//
// Class: OSFamily
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:OSFamily' => 'Famille d\'OS',
	'Class:OSFamily+' => 'Typologie. Liste des valeurs possibles pour l\'attribut "Famille d\'OS" des serveurs, machines virtuelles et PC.',
	'Class:OSFamily/Attribute:osversions_list' => 'Versions OS',
	'Class:OSFamily/Attribute:osversions_list+' => 'Toutes les versions OS pour cette famille',
	'Class:OSFamily/UniquenessRule:name+' => 'Le nom doit être unique',
	'Class:OSFamily/UniquenessRule:name' => 'cette famille d\'OS existe déjà',
]);

//
// Class: Brand
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Brand' => 'Marque',
	'Class:Brand+' => 'Typologie. Liste des valeurs possibles pour l\'attribut « Marque » d\'un matériel physique.',
	'Class:Brand/Attribute:iosversions_list' => 'Versions IOS',
	'Class:Brand/Attribute:iosversions_list+' => 'Toutes les versions IOS pour cette marque',
	'Class:Brand/Attribute:logo' => 'Logo',
	'Class:Brand/Attribute:logo+' => '',
	'Class:Brand/Attribute:models_list' => 'Modèles',
	'Class:Brand/Attribute:models_list+' => 'Tous les modèles pour cette marque',
	'Class:Brand/Attribute:physicaldevices_list' => 'Matériels',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Tous les matériels correspondant à cette marque',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Brand/Attribute:physicaldevices_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de sa %1$s',
	'Class:Brand/UniquenessRule:name+' => 'Le nom doit être unique',
	'Class:Brand/UniquenessRule:name' => 'cette marque existe déjà',
]);

//
// Class: Model
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Model' => 'Modèle de matériel',
	'Class:Model+' => 'Typologie. Liste des valeurs possibles pour le modèle d\'un matériel physique. Chaque Modèle appartient à une seule Marque et s\'applique généralement à un seul type de Matériel physique.',
	'Class:Model/ComplementaryName' => '%1$s - %2$s',
	'Class:Model/Attribute:brand_id' => 'Marque',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Nom marque',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:picture' => 'Image',
	'Class:Model/Attribute:picture+' => '',
	'Class:Model/Attribute:type' => 'Type de matériel',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Arrivée électrique',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Arrivée électrique',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Baie de disques',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Baie de disques',
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
	'Class:Model/Attribute:physicaldevices_list+' => 'Tous les matériels correspondant à ce modèle',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Model/Attribute:physicaldevices_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Model/UniquenessRule:name_brand+' => 'Le nom doit être unique dans une marque',
	'Class:Model/UniquenessRule:name_brand' => 'ce modèle existe déjà dans cette marque',
]);

//
// Class: NetworkDeviceType
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:NetworkDeviceType' => 'Type d\'équipement réseau',
	'Class:NetworkDeviceType+' => 'Typologie. Valeurs possibles pour le type d’un équipement réseau (ex : Routeur, Switch, Firewall, etc.).',
	'Class:NetworkDeviceType/Attribute:logo' => 'Logo',
	'Class:NetworkDeviceType/Attribute:logo+' => 'Utilisé comme icône pour les équipement réseau de ce Type lors de leur affichage (détails, aperçu et graphe d\'analyse d\'impact)',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Equipements réseaux',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Tous les équipements réseaux correspondant à ce type',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
]);

//
// Class: IOSVersion
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:IOSVersion' => 'Version d\'IOS',
	'Class:IOSVersion+' => 'Typologie. Valeurs possibles des versions de systèmes d’exploitation pour équipements réseau.',
	'Class:IOSVersion/Attribute:brand_id' => 'Marque',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Nom Marque',
	'Class:IOSVersion/Attribute:brand_name+' => '',
	'Class:IOSVersion/UniquenessRule:name_brand+' => 'Le nom doit être unique pour cette marque',
	'Class:IOSVersion/UniquenessRule:name_brand' => 'cette version d\'IOS existe déja sur cette marque',
	'Class:IOSVersion/Attribute:networkdevices_list' => 'Equipements réseaux',
	'Class:IOSVersion/Attribute:networkdevices_list+' => 'Tous les équipements réseaux utilisant cette version IOS',
]);

//
// Class: lnkDocumentToPatch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkDocumentToPatch' => 'Lien Document / Patch',
	'Class:lnkDocumentToPatch+' => 'Lien utilisé lorsqu\'un Document est applicable à un Patch',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Nom patch',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Nom document',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
]);

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Lien Instance logiciel / Patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => 'Ce lien indique qu\'un Patch logiciel a été appliqué sur une Instance logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Nom patch logiciel',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instance logicielle',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Nom instance logicielle',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
]);

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkFunctionalCIToOSPatch' => 'Lien CI fonctionnel / Patch OS',
	'Class:lnkFunctionalCIToOSPatch+' => 'Modélise le déploiement d\'un Patch d\'OS sur un équipment',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Patch OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Nom Patch OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
]);

//
// Class: lnkDocumentToSoftware
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkDocumentToSoftware' => 'Lien Document / Logiciel',
	'Class:lnkDocumentToSoftware+' => 'Lien utilisé lorsqu\'un Document est applicable à un Logiciel.',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Logiciel',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Nom logiciel',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Nom document',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
]);

//
// Class: Subnet
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => 'Segment d\'un réseau IP, défini par une adresse IP et un masque.',
	'Class:Subnet/Name' => '%1$s/%2$s',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s',
	'Class:Subnet/Attribute:description' => 'Description',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Nom de subnet',
	'Class:Subnet/Attribute:subnet_name+' => '',
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
]);

//
// Class: VLAN
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => 'Un VLAN est utilisé pour regrouper de manière logique des réseaux, sous-réseaux et interfaces physiques participant au même VLAN.',
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
	'Class:VLAN/Attribute:physicalinterfaces_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:VLAN/Attribute:physicalinterfaces_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:VLAN/Attribute:physicalinterfaces_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:VLAN/Attribute:physicalinterfaces_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
]);

//
// Class: lnkSubnetToVLAN
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkSubnetToVLAN' => 'Lien Subnet / VLAN',
	'Class:lnkSubnetToVLAN+' => 'Ce lien n:n indique qu\'un VLAN est présent sur un Subnet. Plusieurs VLAN peuvent être présents sur le même Subnet et un VLAN peut s\'étendre sur plusieurs Subnets.',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s',
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
]);

//
// Class: NetworkInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:NetworkInterface' => 'Interface réseau',
	'Class:NetworkInterface+' => 'Classe abstraite pour tous les types d\'interfaces réseau.',
	'Class:NetworkInterface/Attribute:name' => 'Nom',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Sous-classe d\'Interface Réseau',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: IPInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:IPInterface' => 'Interface IP',
	'Class:IPInterface+' => 'Classe abstraite. Type d’interface réseau avec une adresse IP.',
	'Class:IPInterface/Attribute:ipaddress' => 'Adresse IP',
	'Class:IPInterface/Attribute:ipaddress+' => '',
	'Class:IPInterface/Attribute:macaddress' => 'Adresse MAC',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Commentaire',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'Passerelle',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'Masque de sous réseau',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Vitesse',
	'Class:IPInterface/Attribute:speed+' => '',
]);

//
// Class: PhysicalInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:PhysicalInterface' => 'Interface physique',
	'Class:PhysicalInterface+' => 'Type d’interface IP représentant une interface réseau physique (ex : carte Ethernet).',
	'Class:PhysicalInterface/Name' => '%2$s %1$s',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Matériel',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Nom matériel',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:org_id' => 'Organisation',
	'Class:PhysicalInterface/Attribute:org_id+' => '',
	'Class:PhysicalInterface/Attribute:location_id' => 'Site',
	'Class:PhysicalInterface/Attribute:location_id+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
]);

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkPhysicalInterfaceToVLAN' => 'Lien Interface physique / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => 'Ce lien indique lorsqu\'une Interface réseau fait partie d\'un VLAN (Virtual Local Area Network).',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s',
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
]);

//
// Class: LogicalInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:LogicalInterface' => 'Interface logique',
	'Class:LogicalInterface+' => 'Interface IP qui n\'est pas associée de façon permanente à un port physique, l\'association est dynamique. Elle peut être utilisée pour une machine virtuelle.',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Machine virtuelle',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Nom Machine virtuelle',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
]);

//
// Class: FiberChannelInterface
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:FiberChannelInterface' => 'Interface fibre',
	'Class:FiberChannelInterface+' => 'Interface réseau vers une technologie haut débit principalement utilisée pour connecter des systèmes de stockage.',
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
]);

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkConnectableCIToNetworkDevice' => 'Lien Device / Équipement réseau',
	'Class:lnkConnectableCIToNetworkDevice+' => 'Définit sur quel équipment réseau un matériel est connecté. ',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s',
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
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Type de connexion',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'lien descendant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'lien descendant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'lien montant',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'lien montant',
]);

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Lien Solution applicative / CI fonctionnel',
	'Class:lnkApplicationSolutionToFunctionalCI+' => 'Modélise l\'appartenance d\'un équipment à une Solution Applicative. La signification de cette relation varie suivant les types de Solution applicative.',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Solution applicative',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Nom Solution applicative',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
]);

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Lien Solution applicative / Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess+' => 'Modélise la relation entre une Solution applicative et un Processus Métier.',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Nom Processus métier',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Solution applicative',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Nom Solution applicative',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
]);

//
// Class: Group
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Group' => 'Groupe',
	'Class:Group+' => 'Le groupe est conçu pour définir des ensembles explicites d\'éléments de configuration pour tout projet. Contrairement à une solution applicative, un groupe n\'est impacté par aucun de ses composants et ne les impacte pas. Par exemple, lors d\'une migration d\'OS, un groupe peut être pratique pour rassembler les « serveurs à migrer ». Les serveurs migrés sont retirés du groupe au fur et à mesure de la migration.',
	'Class:Group/ComplementaryName' => '%1$s - %2$s',
	'Class:Group/Attribute:name' => 'Nom',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Etat',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implémentation',
	'Class:Group/Attribute:status/Value:implementation+' => '',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:Group/Attribute:status/Value:obsolete+' => '',
	'Class:Group/Attribute:status/Value:production' => 'Production',
	'Class:Group/Attribute:status/Value:production+' => '',
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
	'Class:Group/Attribute:ci_list+' => 'Tous les éléments de configuration liés à ce groupe',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Nom usuel du parent',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
]);

//
// Class: lnkGroupToCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkGroupToCI' => 'Lien Groupe / CI fonctionnel',
	'Class:lnkGroupToCI+' => 'Ce lien indique lorsqu\'un équipment (CI fonctionnel) fait partie d\'un Groupe.',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkDocumentToFunctionalCI' => 'Lien Document / CI fonctionnel',
	'Class:lnkDocumentToFunctionalCI+' => 'Lien utilisé lorsqu\'un Document est applicable à un CI fonctionnel.',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Nom CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Nom Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
]);

// Add translation for Fieldsets

Dict::Add('FR FR', 'French', 'Français', [
	'ConfigMgmt:baseinfo' => 'Informations générales',
	'Server:baseinfo' => 'Informations générales',
	'ConfigMgmt:moreinfo' => 'Item spécifique',
	'Server:moreinfo' => 'Matériel spécifique',
	'Storage:moreinfo' => 'Stockage spécifique',
	'Software:moreinfo' => 'Logiciel spécifique',
	'Phone:moreinfo' => 'Téléphone spécifique',
	'ConfigMgmt:otherinfo' => 'Dates et description',
	'Server:Date' => 'Dates',
	'Server:otherinfo' => 'Description',
	'Server:power' => 'Alimentation électrique',
	'Class:Subnet/Tab:IPUsage' => 'IP utilisées',
	'Class:Subnet/Tab:IPUsage+' => 'Utilisation des IPs de ce subnet',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces ayant une IP dans la plage: <em>%1$s</em> à <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'IP disponibles',
	'Class:Subnet/Tab:FreeIPs-count' => 'IP disponibles: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Voici un échantillon de dix addresses IP disponibles',
	'Class:Document:PreviewTab' => 'Aperçu',
]);

//
// Application Menu
//

Dict::Add('FR FR', 'French', 'Français', [
	'Menu:Application' => 'Logiciels',
	'Menu:Application+' => 'Tous les logiciels',
	'Menu:DBServer' => 'Serveur de base de données',
	'Menu:DBServer+' => '',
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
	'Menu:Server' => 'Serveurs',
	'Menu:Server+' => '',
	'Menu:Printer' => 'Imprimantes',
	'Menu:Printer+' => 'Toutes les imprimantes',
	'Menu:MobilePhone' => 'Téléphones portables',
	'Menu:MobilePhone+' => 'Tous les téléphones portables',
	'Menu:PC' => 'PCs',
	'Menu:PC+' => 'Tous les PCs',
	'Menu:NewCI' => 'Nouveau CI',
	'Menu:NewCI+' => 'Nouveau CI',
	'Menu:SearchCIs' => 'Rechercher des CIs',
	'Menu:SearchCIs+' => 'Rechercher des CIs',
	'Menu:ConfigManagement:Devices' => 'Equipements',
	'Menu:ConfigManagement:AllDevices' => 'Infrastructures',
	'Menu:ConfigManagement:virtualization' => 'Virtualisation',
	'Menu:ConfigManagement:EndUsers' => 'Périphériques utilisateurs',
	'Menu:ConfigManagement:SWAndApps' => 'Logiciels et applications',
	'Menu:ConfigManagement:Misc' => 'Divers',
	'Menu:Group' => 'Groupe de CIs',
	'Menu:Group+' => 'Groupe de CIs',
	'Menu:OSVersion' => 'Versions d\'OS',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Catalogue des logiciels de références',
	'Menu:Software+' => 'Catalogue des logiciels de références',
]);

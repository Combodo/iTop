<?php

/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013-2026 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Fieldsets for Container classes
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Container:baseinfo' => 'Allgemein',
	'Container:moreinfo' => 'Containerspezifische Angaben',
	'Container:otherinfo' => 'Daten und Beschreibung',
]);

//
// Class Container Image
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerImage/Name' => '%1$s %2$s',
	'Class:ContainerImage/ComplementaryName' => '%1$s - %2$s',
	'Class:ContainerImage' => 'Container-Image',
	'Class:ContainerImage+' => 'Das Image einer Software, die als Container gestartet werden kann',
	'Class:ContainerImage/Attribute:name' => 'Name',
	'Class:ContainerImage/Attribute:name+' => '',
	'Class:ContainerImage/Attribute:version' => 'Version',
	'Class:ContainerImage/Attribute:version+' => '',
	'Class:ContainerImage/Attribute:description' => 'Beschreibung',
	'Class:ContainerImage/Attribute:description+' => '',
	'Class:ContainerImage/Attribute:publisher' => 'Herausgeber',
	'Class:ContainerImage/Attribute:publisher+' => 'Herausgeber des Images, z. B. php, nginx, ...',
	'Class:ContainerImage/Attribute:image' => 'Image',
	'Class:ContainerImage/Attribute:image+' => 'Detailangaben, um das Image auf der jeweiligen Hosting-Plattform zu finden',
	'Class:ContainerImage/Attribute:type_id' => 'Typ',
	'Class:ContainerImage/Attribute:type_id+' => 'Typ des Images',
	'Class:ContainerImage/Attribute:software_id' => 'Software',
	'Class:ContainerImage/Attribute:software_id+' => '',
	'Class:ContainerImage/Attribute:containerapplications_list' => 'Containerisierte Anwendungen',
	'Class:ContainerImage/Attribute:containerapplications_list+' => 'Anwendungen, zu denen dieses Image beiträgt',
]);

//
// Class Container Application
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerApplication/Name' => '%1$s',
	'Class:ContainerApplication/ComplementaryName' => '%1$s',
	'Class:ContainerApplication' => 'Containerisierte Anwendung',
	'Class:ContainerApplication+' => 'Eine Anwendung, die auf einer Container-Plattform betrieben wird',
	'Class:ContainerApplication/Attribute:descriptor' => 'Deployment-Datei',
	'Class:ContainerApplication/Attribute:descriptor+' => 'Datei, die beschreibt, wie die Anwendung auf der Container-Plattform ausgerollt wird (z. B. Docker Compose, Helm Chart usw.)',
	'Class:ContainerApplication/Attribute:containervirtualhost_id' => 'Container-Host',
	'Class:ContainerApplication/Attribute:containervirtualhost_id+' => 'Container-Plattform, auf der die Anwendung läuft',
	'Class:ContainerApplication/Attribute:logo' => 'Logo',
	'Class:ContainerApplication/Attribute:logo+' => 'Wird als Objektsymbol verwendet, wenn diese containerisierte Anwendung in Impact-Analyse-Graphen dargestellt wird',
	'Class:ContainerApplication/Attribute:containertype_id' => 'Container-Typ',
	'Class:ContainerApplication/Attribute:containertype_id+' => 'Technologie, die für die Containerisierung eingesetzt wird',
	'Class:ContainerApplication/Attribute:containerimages_list' => 'Container-Images',
	'Class:ContainerApplication/Attribute:containerimages_list+' => 'Software-Images, aus denen die containerisierte Anwendung aufgebaut wird',

]);

//
// Class: lnkContainerApplicationToImage
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:lnkContainerApplicationToImage' => 'Verknüpfung Containerisierte Anwendung/Image',
	'Class:lnkContainerApplicationToImage+' => '',
	'Class:lnkContainerApplicationToImage/Name' => '%1$s / %2$s',
	'Class:lnkContainerApplicationToImage/Name+' => '',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id' => 'Containerisierte Anwendung',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id+' => 'Anwendung, die dieses Image verwendet',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id' => 'Container-Image',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id+' => 'Software-Image, aus dem die containerisierte Anwendung aufgebaut wird',
]);

//
// Class Container Virtual Host
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerVirtualHost/Name' => '%1$s',
	'Class:ContainerVirtualHost/ComplementaryName' => '',
	'Class:ContainerVirtualHost' => 'Container-Plattform',
	'Class:ContainerVirtualHost+' => 'Plattform, auf der Anwendungen als Container laufen',
	'Class:ContainerVirtualHost/Attribute:containertype_id' => 'Container-Typ',
	'Class:ContainerVirtualHost/Attribute:containertype_id+' => 'Technologie, mit der die Containerisierung bereitgestellt wird',
	'Class:ContainerVirtualHost/Attribute:status' => 'Status',
	'Class:ContainerVirtualHost/Attribute:status+' => 'Status der Container-Plattform',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list' => 'Anwendungen',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list+' => 'Anwendungen, die in dieser Container-Umgebung laufen',
]);

//
// Class Container Host
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerHost/Name' => '%1$s',
	'Class:ContainerHost/ComplementaryName' => '%1$s-%2$s',
	'Class:ContainerHost' => 'Container-Host',
	'Class:ContainerHost+' => 'Host, der für Container vorgesehen ist. Er ist der Grundbaustein einer Container-Plattform',
	'Class:ContainerHost/Attribute:containercluster_id' => 'Container-Cluster',
	'Class:ContainerHost/Attribute:containercluster_id+' => '',
	'Class:ContainerHost/Attribute:role' => 'Rolle',
	'Class:ContainerHost/Attribute:role+' => 'Rolle des Hosts innerhalb seines Clusters: Master oder Worker. Standalone, wenn er zu keinem Cluster gehört.',
	'Class:ContainerHost/Attribute:system_id' => 'System',
	'Class:ContainerHost/Attribute:system_id+' => 'Das System kann ein Server, eine virtuelle Maschine, eine Cloud usw. sein',
	'Class:ContainerHost/Attribute:role/Value:master' => 'Master',
	'Class:ContainerHost/Attribute:role/Value:worker' => 'Worker',
	'Class:ContainerHost/Attribute:role/Value:standalone' => 'Standalone',
]);

//
// Class Container Cluster
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerCluster/Name' => '%1$s',
	'Class:ContainerCluster/ComplementaryName' => '',
	'Class:ContainerCluster' => 'Container-Cluster',
	'Class:ContainerCluster+' => 'Eine Container-Plattform, die aus einem Cluster von Container-Hosts besteht',
	'Class:ContainerCluster/Attribute:redundancy' => 'Konfiguration der Redundanz',
	'Class:ContainerCluster/Attribute:redundancy/disabled' => 'Das Cluster ist verfügbar, wenn alle seine Hosts verfügbar sind',
	'Class:ContainerCluster/Attribute:redundancy/count' => 'Das Cluster ist verfügbar, wenn mindestens %1$s Hosts verfügbar sind',
	'Class:ContainerCluster/Attribute:redundancy/percent' => 'Das Cluster ist verfügbar, wenn mindestens %1$s %% der Hosts verfügbar sind',
	'Class:ContainerCluster/Attribute:containerhosts_list' => 'Container-Hosts',
	'Class:ContainerCluster/Attribute:containerhosts_list+' => 'Hosts, die zu diesem Cluster gehören',
]);

//
// Class Container Type
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerType/Name' => '%1$s',
	'Class:ContainerType/ComplementaryName' => '',
	'Class:ContainerType' => 'Container-Typ',
	'Class:ContainerType+' => 'Technologie, mit der die Containerisierung bereitgestellt wird',
]);

//
// Class Container Type
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:ContainerImageType/Name' => '%1$s',
	'Class:ContainerImageType/ComplementaryName' => '',
	'Class:ContainerImageType' => 'Container-Image-Typ',
	'Class:ContainerImageType+' => 'Typologie von Container-Images',
]);

//
// Class Cloud, Server and Virtual Machine
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:Cloud/Attribute:containerhosts_list' => 'Container-Hosts',
	'Class:Cloud/Attribute:containerhosts_list+' => 'Liste der Container-Hosts, die in dieser Cloud laufen',
	'Class:Server/Attribute:containerhosts_list' => 'Container-Hosts',
	'Class:Server/Attribute:containerhosts_list+' => 'Liste der Container-Hosts, die auf diesem Server laufen',
	'Class:VirtualMachine/Attribute:containerhosts_list' => 'Container-Hosts',
	'Class:VirtualMachine/Attribute:containerhosts_list+' => 'Liste der Container-Hosts, die auf dieser virtuellen Maschine laufen',
	'Class:Software/Attribute:containerimages_list' => 'Container-Images',
	'Class:Software/Attribute:containerimages_list+' => 'Liste der Container-Images, in denen diese Software läuft',
]);

<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//
// Fieldsets for Container classes
//

Dict::Add('EN US', 'English', 'English', [
	'Container:baseinfo' => 'General',
	'Container:moreinfo' => 'Container specifics',
	'Container:otherinfo' => 'Dates and description',
]);

//
// Class Container Image
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerImage/Name' => '%1$s %2$s',
	'Class:ContainerImage/ComplementaryName' => '%1$s - %2$s',
	'Class:ContainerImage' => 'Container Image',
	'Class:ContainerImage+' => 'The image of a software ready to be launched as a container',
	'Class:ContainerImage/Attribute:name' => 'Name',
	'Class:ContainerImage/Attribute:name+' => '',
	'Class:ContainerImage/Attribute:version' => 'Version',
	'Class:ContainerImage/Attribute:version+' => '',
	'Class:ContainerImage/Attribute:description' => 'Description',
	'Class:ContainerImage/Attribute:description+' => '',
	'Class:ContainerImage/Attribute:publisher' => 'Publisher',
	'Class:ContainerImage/Attribute:publisher+' => 'Publisher of the image. Eg. php, nginx, ...',
	'Class:ContainerImage/Attribute:image' => 'Image',
	'Class:ContainerImage/Attribute:image+' => 'Detailed information to retrieve the image on the appropriate hosting platform',
	'Class:ContainerImage/Attribute:type_id' => 'Type',
	'Class:ContainerImage/Attribute:type_id+' => 'Type d\image',
	'Class:ContainerImage/Attribute:software_id' => 'Software',
	'Class:ContainerImage/Attribute:software_id+' => '',
	'Class:ContainerImage/Attribute:containerapplications_list' => 'Containerized Applications',
	'Class:ContainerImage/Attribute:containerapplications_list+' => 'Applications to which this image contributes',
]);

//
// Class Container Application
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerApplication/Name' => '%1$s',
	'Class:ContainerApplication/ComplementaryName' => '%1$s',
	'Class:ContainerApplication' => 'Containerized Application',
	'Class:ContainerApplication+' => 'An application deployed on a Container Platform',
	'Class:ContainerApplication/Attribute:descriptor' => 'Deployment file',
	'Class:ContainerApplication/Attribute:descriptor+' => 'File describing how to deploy the application on the container platform (e.g., Docker Compose, Helm Chart, etc.)',
	'Class:ContainerApplication/Attribute:containervirtualhost_id' => 'Container Host',
	'Class:ContainerApplication/Attribute:containervirtualhost_id+' => 'Container Platform on which the application is running',
	'Class:ContainerApplication/Attribute:containertype_id' => 'Container type',
	'Class:ContainerApplication/Attribute:containertype_id+' => 'Technology used for containerization',
	'Class:ContainerApplication/Attribute:containerimages_list' => 'Container images',
	'Class:ContainerApplication/Attribute:containerimages_list+' => 'Software images used to build the containerized application',

]);

//
// Class: lnkContainerApplicationToImage
//

Dict::Add('EN US', 'English', 'English', [
	'Class:lnkContainerApplicationToImage' => 'Link Container Application / Image',
	'Class:lnkContainerApplicationToImage+' => '',
	'Class:lnkContainerApplicationToImage/Name' => '%1$s / %2$s',
	'Class:lnkContainerApplicationToImage/Name+' => '',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id' => 'Containerized Application',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id+' => 'Application which uses this image',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id' => 'Container Image',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id+' => 'Software image used to build the containerized application',
]);

//
// Class Container Virtual Host
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerVirtualHost/Name' => '%1$s',
	'Class:ContainerVirtualHost/ComplementaryName' => '',
	'Class:ContainerVirtualHost' => 'Container Platform',
	'Class:ContainerVirtualHost+' => 'Platform on which applications run as containers',
	'Class:ContainerVirtualHost/Attribute:containertype_id' => 'Container Type',
	'Class:ContainerVirtualHost/Attribute:containertype_id+' => 'Technology used to deliver containerization',
	'Class:ContainerVirtualHost/Attribute:status' => 'Status',
	'Class:ContainerVirtualHost/Attribute:status+' => 'Status of the container platform',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list' => 'Applications',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list+' => 'Applications running on this container environment',


]);

//
// Class Container Host
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerHost/Name' => '%1$s',
	'Class:ContainerHost/ComplementaryName' => '%1$s-%2$s',
	'Class:ContainerHost' => 'Container Host',
	'Class:ContainerHost+' => 'Host dedicated to containers. It is the basic element of a Container Platform',
	'Class:ContainerHost/Attribute:containercluster_id' => 'Container Cluster',
	'Class:ContainerHost/Attribute:containercluster_id+' => '',
	'Class:ContainerHost/Attribute:role' => 'Role',
	'Class:ContainerHost/Attribute:role+' => 'Role of the host within its cluster: Master or Worker. Standalone when not part of a cluster.',
	'Class:ContainerHost/Attribute:system_id' => 'System',
	'Class:ContainerHost/Attribute:system_id+' => 'The system can be a Server, a Virtual Machine, a Cloud, ...',
	'Class:ContainerHost/Attribute:role/Value:master' => 'Master',
	'Class:ContainerHost/Attribute:role/Value:worker' => 'Worker',
	'Class:ContainerHost/Attribute:role/Value:standalone' => 'Standalone',
]);

//
// Class Container Cluster
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerCluster/Name' => '%1$s',
	'Class:ContainerCluster/ComplementaryName' => '',
	'Class:ContainerCluster' => 'Container Cluster',
	'Class:ContainerCluster+' => 'A Container Platform made of a cluster of Container Hosts',
	'Class:ContainerCluster/Attribute:redundancy' => 'Configuration of the redundancy',
	'Class:ContainerCluster/Attribute:redundancy/disabled' => 'The cluster is up if all its hosts are up',
	'Class:ContainerCluster/Attribute:redundancy/count' => 'The cluster is up if at least %1$s hosts are up',
	'Class:ContainerCluster/Attribute:redundancy/percent' => 'The cluster is up if at least %1$s %% of the hosts are up',
	'Class:ContainerCluster/Attribute:containerhosts_list' => 'Container Hosts',
	'Class:ContainerCluster/Attribute:containerhosts_list+' => 'Hosts part of this cluster',
]);

//
// Class Container Type
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerType/Name' => '%1$s',
	'Class:ContainerType/ComplementaryName' => '',
	'Class:ContainerType' => 'Container Type',
	'Class:ContainerType+' => 'Technology used to deliver containerization',
]);

//
// Class Container Type
//

Dict::Add('EN US', 'English', 'English', [
	'Class:ContainerImageType/Name' => '%1$s',
	'Class:ContainerImageType/ComplementaryName' => '',
	'Class:ContainerImageType' => 'Container Image Type',
	'Class:ContainerImageType+' => 'Typology of container images',
]);


?>

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

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Container:baseinfo' => '基本信息',
	'Container:moreinfo' => '容器详情',
	'Container:otherinfo' => '日期和描述',
]);

//
// Class Container Image
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerImage/Name' => '%1$s %2$s',
	'Class:ContainerImage/ComplementaryName' => '%1$s - %2$s',
	'Class:ContainerImage' => '容器镜像',
	'Class:ContainerImage+' => '准备作为容器启动的软件镜像',
	'Class:ContainerImage/Attribute:name' => '名称',
	'Class:ContainerImage/Attribute:name+' => '',
	'Class:ContainerImage/Attribute:version' => '版本',
	'Class:ContainerImage/Attribute:version+' => '',
	'Class:ContainerImage/Attribute:description' => '描述',
	'Class:ContainerImage/Attribute:description+' => '',
	'Class:ContainerImage/Attribute:publisher' => '发布者',
	'Class:ContainerImage/Attribute:publisher+' => '镜像的发布者. 例如: php, nginx, ...',
	'Class:ContainerImage/Attribute:image' => '镜像',
	'Class:ContainerImage/Attribute:image+' => '在对应的托管平台上检索镜像的详细信息',
	'Class:ContainerImage/Attribute:type_id' => '类型',
	'Class:ContainerImage/Attribute:type_id+' => 'Type d\image~~',
	'Class:ContainerImage/Attribute:software_id' => '软件',
	'Class:ContainerImage/Attribute:software_id+' => '',
	'Class:ContainerImage/Attribute:containerapplications_list' => '容器化应用',
	'Class:ContainerImage/Attribute:containerapplications_list+' => '此镜像所对应的应用程序',
]);

//
// Class Container Application
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerApplication/Name' => '%1$s',
	'Class:ContainerApplication/ComplementaryName' => '%1$s',
	'Class:ContainerApplication' => '容器化应用程序',
	'Class:ContainerApplication+' => '部署在容器平台上的应用程序',
	'Class:ContainerApplication/Attribute:descriptor' => 'Deployment file',
	'Class:ContainerApplication/Attribute:descriptor+' => 'File describing how to deploy the application on the container platform (e.g., Docker Compose, Helm Chart, etc.)',
	'Class:ContainerApplication/Attribute:containervirtualhost_id' => 'Container Host',
	'Class:ContainerApplication/Attribute:containervirtualhost_id+' => 'Container Platform on which the application is running',
	'Class:ContainerApplication/Attribute:logo' => 'Logo',
	'Class:ContainerApplication/Attribute:logo+' => 'Used as object icon when this ContainerApplication is displayed within impact analysis graphs',
	'Class:ContainerApplication/Attribute:containertype_id' => '容器类型',
	'Class:ContainerApplication/Attribute:containertype_id+' => '容器化使用的技术',
	'Class:ContainerApplication/Attribute:containerimages_list' => '容器镜像',
	'Class:ContainerApplication/Attribute:containerimages_list+' => 'Software images used to build the containerized application',

]);

//
// Class: lnkContainerApplicationToImage
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContainerApplicationToImage' => '链接 容器应用/容器镜像',
	'Class:lnkContainerApplicationToImage+' => '',
	'Class:lnkContainerApplicationToImage/Name' => '%1$s / %2$s',
	'Class:lnkContainerApplicationToImage/Name+' => '',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id' => '容器化应用程序',
	'Class:lnkContainerApplicationToImage/Attribute:containerapplication_id+' => '使用此镜像的应用程序',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id' => '容器镜像',
	'Class:lnkContainerApplicationToImage/Attribute:containerimage_id+' => '用于构建容器化应用程序的软件镜像',
]);

//
// Class Container Virtual Host
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerVirtualHost/Name' => '%1$s',
	'Class:ContainerVirtualHost/ComplementaryName' => '',
	'Class:ContainerVirtualHost' => '容器平台',
	'Class:ContainerVirtualHost+' => 'Platform on which applications run as containers',
	'Class:ContainerVirtualHost/Attribute:containertype_id' => '容器类型',
	'Class:ContainerVirtualHost/Attribute:containertype_id+' => '用于交付容器的技术',
	'Class:ContainerVirtualHost/Attribute:status' => '状态',
	'Class:ContainerVirtualHost/Attribute:status+' => '容器平台的状态',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list' => '应用程序',
	'Class:ContainerVirtualHost/Attribute:containerapplications_list+' => '在此容器环境中运行的应用程序',
]);

//
// Class Container Host
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerHost/Name' => '%1$s',
	'Class:ContainerHost/ComplementaryName' => '%1$s-%2$s',
	'Class:ContainerHost' => '容器宿主机',
	'Class:ContainerHost+' => '托管容器的宿主机. 它是容器平台的基本元素',
	'Class:ContainerHost/Attribute:containercluster_id' => '容器集群',
	'Class:ContainerHost/Attribute:containercluster_id+' => '',
	'Class:ContainerHost/Attribute:role' => '角色',
	'Class:ContainerHost/Attribute:role+' => '主机在集群中的角色：主节点或工作节点. 独立运行时不在集群中.',
	'Class:ContainerHost/Attribute:system_id' => '系统',
	'Class:ContainerHost/Attribute:system_id+' => '系统可以是物理机, 虚拟机, 云平台, ...',
	'Class:ContainerHost/Attribute:role/Value:master' => '主节点',
	'Class:ContainerHost/Attribute:role/Value:worker' => '工作节点',
	'Class:ContainerHost/Attribute:role/Value:standalone' => '独立运行',
]);

//
// Class Container Cluster
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerCluster/Name' => '%1$s',
	'Class:ContainerCluster/ComplementaryName' => '',
	'Class:ContainerCluster' => '容器集群',
	'Class:ContainerCluster+' => '由一组容器宿主机组成的容器平台',
	'Class:ContainerCluster/Attribute:redundancy' => '冗余配置',
	'Class:ContainerCluster/Attribute:redundancy/disabled' => '当所有主机都在运行时, 集群才是正常的',
	'Class:ContainerCluster/Attribute:redundancy/count' => '当至少 %1$s 个主机在运行时, 集群才是正常的',
	'Class:ContainerCluster/Attribute:redundancy/percent' => '当至少 %1$s %% 的在主机运行时，集群才是正常的',
	'Class:ContainerCluster/Attribute:containerhosts_list' => '容器宿主机',
	'Class:ContainerCluster/Attribute:containerhosts_list+' => '此集群的主机',
]);

//
// Class Container Type
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerType/Name' => '%1$s',
	'Class:ContainerType/ComplementaryName' => '',
	'Class:ContainerType' => '容器类型',
	'Class:ContainerType+' => '用于交付容器的技术',
]);

//
// Class Container Type
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContainerImageType/Name' => '%1$s',
	'Class:ContainerImageType/ComplementaryName' => '',
	'Class:ContainerImageType' => '容器镜像类型',
	'Class:ContainerImageType+' => '容器镜像的分类',
]);

//
// Class Cloud, Server and Virtual Machine
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Cloud/Attribute:containerhosts_list' => '容器宿主机',
	'Class:Cloud/Attribute:containerhosts_list+' => '运行在此云平台上的容器宿主机列表',
	'Class:Server/Attribute:containerhosts_list' => '容器宿主机',
	'Class:Server/Attribute:containerhosts_list+' => '运行在此物理机上的容器宿主机列表',
	'Class:VirtualMachine/Attribute:containerhosts_list' => '容器宿主机',
	'Class:VirtualMachine/Attribute:containerhosts_list+' => '运行在此虚拟机上的容器宿主机列表',
	'Class:Software/Attribute:containerimages_list' => '容器镜像',
	'Class:Software/Attribute:containerimages_list+' => '运行此软件的容器镜像列表',
]);

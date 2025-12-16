<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('FR FR', 'French', 'Français', array(

    // Class Container Image
    'Class:ContainerImage/Name' => '%1$s',
    'Class:ContainerImage/ComplementaryName' => '%1$s',
    'Class:ContainerImage' => 'Image pour conteneur',
    'Class:ContainerImage+' => 'Image d\'un logiciel, constituant d\'une application conteneurisée',
    'Class:ContainerImage/Attribute:software_id' => 'Logiciel',
    'Class:ContainerImage/Attribute:software_id+' => '',
    'Class:ContainerImage/Attribute:image_url' => 'URL de l\'image',
    'Class:ContainerImage/Attribute:image_url+' => '',
    'Class:ContainerImage/Attribute:containerapplication_id' => 'Application conteneurisée',
    'Class:ContainerImage/Attribute:containerapplication_id+' => 'L\'application à laquelle cette image contribue',
    'Class:ContainerImage/Attribute:containervirtualhost_id' => 'Plateforme de l\'application',
    'Class:ContainerImage/Attribute:containervirtualhost_id+' => 'Plateforme où cette image est déployée en conteneur',
    'ContainerImage:baseinfo' => 'Informations générales',
    'ContainerImage:moreinfo' => 'Spécificités de la conteneurisation',

    // Class Container Application
    'Class:ContainerApplication/Name' => '%1$s',
    'Class:ContainerApplication/ComplementaryName' => '%1$s',
    'Class:ContainerApplication' => 'Application conteneurisée',
    'Class:ContainerApplication+' => 'Application déployée sur une plateforme de conteneurisation',
    'Class:ContainerApplication/Attribute:status' => 'État',
    'Class:ContainerApplication/Attribute:status+' => 'État de l\'application',
    'Class:ContainerApplication/Attribute:containervirtualhost_id' => 'Hôte',
    'Class:ContainerApplication/Attribute:containervirtualhost_id+' => 'Plateforme de conteneurisation sur laquelle cette application est déployée',
    'Class:ContainerApplication/Attribute:containertype_id' => 'Type de conteneur',
    'Class:ContainerApplication/Attribute:containertype_id+' => '',
    'Class:ContainerApplication/Attribute:containerimages_list' => 'Images',
    'Class:ContainerApplication/Attribute:containerimages_list+' => 'Images des conteneurs constitutifs de cette application',
    'ContainerApplication:baseinfo' => 'Informations générales',
    'ContainerApplication:moreinfo' => 'Spécificités de la conteneurisation',

    // Class Container Virtual Host
    'Class:ContainerVirtualHost/Name' => '%1$s',
    'Class:ContainerVirtualHost/ComplementaryName' => '',
    'Class:ContainerVirtualHost' => 'Plateforme de conteneurisation',
    'Class:ContainerVirtualHost+' => 'Plateforme sur laquelle des applications s\'exécutent dans des conteneurs',
    'Class:ContainerVirtualHost/Attribute:containertype_id' => 'Type de plateforme',
    'Class:ContainerVirtualHost/Attribute:containertype_id+' => 'Technologie de conteneurisation utilisée',
    'Class:ContainerVirtualHost/Attribute:status' => 'État',
    'Class:ContainerVirtualHost/Attribute:status+' => 'État de la plateforme de conteneurisation',
    'Class:ContainerVirtualHost/Attribute:containerapplications_list' => 'Applications',
    'Class:ContainerVirtualHost/Attribute:containerapplications_list+' => 'Applications qui sont déployées sur cette plateforme',
    'ContainerVirtualHost:baseinfo' => 'Informations générales',
    'ContainerVirtualHost:moreinfo' => 'Spécificités de la conteneurisation',

    // Class Container Host
    'Class:ContainerHost/Name' => '%1$s',
    'Class:ContainerHost/ComplementaryName' => '%1$s-%2$s',
    'Class:ContainerHost' => 'Hôte pour conteneurs',
    'Class:ContainerHost+' => 'Système hôte dédié à l\'exécution de conteneurs',
    'Class:ContainerHost/Attribute:containercluster_id' => 'Grappe pour conteneurs',
    'Class:ContainerHost/Attribute:containercluster_id+' => 'Grappe d\'hôtes pour conteneurs',
    'Class:ContainerHost/Attribute:role' => 'Rôle',
    'Class:ContainerHost/Attribute:role+' => 'Rôle de cet hôte au sein de la grappe : Maître ou Esclave. Autonome en l\'absence de grappe',
    'Class:ContainerHost/Attribute:role/Value:master' => 'Maître',
    'Class:ContainerHost/Attribute:role/Value:worker' => 'Esclave',
    'Class:ContainerHost/Attribute:role/Value:standalone' => 'Autonome',
    'Class:ContainerHost/Attribute:system_id' => 'Système',
    'Class:ContainerHost/Attribute:system_id+' => 'Le système sur lequel cet hôte tourne. Cela peut être un Serveur, une Machine Virtuelle ou un Nuage, ...',

    // Class Container Cluster
    'Class:ContainerCluster/Name' => '%1$s',
    'Class:ContainerCluster/ComplementaryName' => '',
    'Class:ContainerCluster' => 'Grappe pour conteneurs',
    'Class:ContainerCluster+' => 'Grappe d\'hôtes de conteneurs',
    'Class:ContainerCluster/Attribute:redundancy' => 'Configuration de la redondance',
    'Class:ContainerCluster/Attribute:redundancy/disabled' => 'La grappe est opérationnelle si tous les hôtes qui la composent sont opérationnels',
    'Class:ContainerCluster/Attribute:redundancy/count' => 'Nombre minimal d\'hôtes pour que la grappe soit opérationnelle : %1$s',
    'Class:ContainerCluster/Attribute:redundancy/percent' => 'Pourcentage minimal d\'hôtes pour que la grappe soit opérationnelle : %1$s %%',
    'Class:ContainerCluster/Attribute:containerhosts_list' => 'Hôtes pour conteneurs',
    'Class:ContainerCluster/Attribute:containerhosts_list+' => 'Hôtes composant cette grappe',

    // Class Container Type
    'Class:ContainerType/Name' => '%1$s',
    'Class:ContainerType/ComplementaryName' => '',
    'Class:ContainerType' => 'Type de conteneurisation',
    'Class:ContainerType+' => 'Technologie de conteneurisation',

    // Class Cloud
    'Class:Cloud/Name' => '%1$s',
    'Class:Cloud/ComplementaryName' => '%1$s-%2$s',
    'Class:Cloud' => 'Nuage',
    'Class:Cloud+' => '',
    'Class:Cloud/Attribute:provider_id' => 'Fournisseur',
    'Class:Cloud/Attribute:provider_id+' => 'Organisation fournissant le nuage',
    'Class:Cloud/Attribute:location_id' => 'Site',
    'Class:Cloud/Attribute:location_id+' => 'Site du fournisseur, hébergeant le nuage',
    'Class:Cloud/Attribute:containerhosts_list' => 'Hôtes pour conteneurs',
    'Class:Cloud/Attribute:containerhosts_list+' => 'Liste des hôtes hébergés dans ce nuage',
    'Cloud:baseinfo' => 'Informations générales',
    'Cloud:moreinfo' => 'Informations propres au nuage',

));
?>

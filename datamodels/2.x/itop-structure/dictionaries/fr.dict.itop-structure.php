<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Benjamin Planque <benjamin.planque@combodo.com>
 *
 */
Dict::Add('FR FR', 'French', 'Français', [
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => 'Un client, un fournisseur, votre entreprise ou des départements de votre entreprise. Les organisations peuvent être structurées hiérarchiquement. Les utilisateurs peuvent être limités aux objets appartenant à certaines organisations.',
	'Class:Organization/Attribute:name' => 'Nom organisation',
	'Class:Organization/Attribute:name+' => 'Nom commun',
	'Class:Organization/Attribute:code' => 'Code',
	'Class:Organization/Attribute:code+' => 'Organisation code (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Etat',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Actif',
	'Class:Organization/Attribute:status/Value:active+' => '',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactif',
	'Class:Organization/Attribute:status/Value:inactive+' => '',
	'Class:Organization/Attribute:parent_id' => 'Organisation Parent',
	'Class:Organization/Attribute:parent_id+' => 'Organisation parent',
	'Class:Organization/Attribute:parent_name' => 'Nom du parent',
	'Class:Organization/Attribute:parent_name+' => 'Nom de l\'organisation parente',
	'Class:Organization/Attribute:deliverymodel_id' => 'Modèle de support',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Nom modèle de support',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Nom commun',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '',
	'Class:Organization/Attribute:overview' => 'Tableau de bord',
	'Organization:Overview:FunctionalCIs' => 'Infrastructure de cette organisation',
	'Organization:Overview:FunctionalCIs:subtitle' => 'par type',
	'Organization:Overview:Users' => 'Utilisateurs '.ITOP_APPLICATION_SHORT.' dans cette organisation',
]);

//
// Class: Location
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Location' => 'Lieu',
	'Class:Location+' => 'Tout type de lieu: Région, Pays, Ville, Site, Bâtiment, Étage, Bureau,...',
	'Class:Location/Attribute:name' => 'Nom',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Etat',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Actif',
	'Class:Location/Attribute:status/Value:active+' => '',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactif',
	'Class:Location/Attribute:status/Value:inactive+' => '',
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
	'Class:Location/Attribute:physicaldevice_list+' => 'Tous les matériels dans ce lieu',
	'Class:Location/Attribute:person_list' => 'Contacts',
	'Class:Location/Attribute:person_list+' => 'Tous les contacts situés dans ce lieu',
	'Class:Location/Attribute:person_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Location/Attribute:person_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
]);

//
// Class: Contact
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Contact' => 'Contact',
	'Class:Contact+' => 'Classe abstraite. Un contact peut être lié à des Tickets et des CI fonctionnels pour divers usages, par exemple l\'affectation des Tickets ou les notifications.',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s',
	'Class:Contact/Attribute:name' => 'Nom',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Etat',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Actif',
	'Class:Contact/Attribute:status/Value:active+' => '',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactif',
	'Class:Contact/Attribute:status/Value:inactive+' => '',
	'Class:Contact/Attribute:org_id' => 'Organisation',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Nom organisation',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Téléphone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notification',
	'Class:Contact/Attribute:notify+' => 'Champ utilisable dans la recherche des destinataires de Notifications',
	'Class:Contact/Attribute:notify/Value:no' => 'non',
	'Class:Contact/Attribute:notify/Value:no+' => 'non',
	'Class:Contact/Attribute:notify/Value:yes' => 'oui',
	'Class:Contact/Attribute:notify/Value:yes+' => 'oui',
	'Class:Contact/Attribute:function' => 'Fonction',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => 'Tous les éléments de configuration liés à ce contact',
	'Class:Contact/Attribute:finalclass' => 'Sous-classe de Contact',
	'Class:Contact/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: Person
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Person' => 'Personne',
	'Class:Person+' => 'Type de contact utilisé pour décrire des personnes physiques. Les personnes peuvent être regroupées en équipes. Elles peuvent être liées à d\'autres éléments de configuration (p. ex. pour décrire qui contacter en cas d\'incident sur une application).
Autre usage : l\'appelant d\'une demande utilisateur est une personne, tout comme l\'agent assigné pour la résoudre.',
	'Class:Person/ComplementaryName' => '%1$s - %2$s',
	'Class:Person/Attribute:name' => 'Nom',
	'Class:Person/Attribute:name+' => '',
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
	'Class:Person/Attribute:team_list+' => 'Toutes les équipes dont fait partie cette personne',
	'Class:Person/Attribute:team_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:Person/Attribute:team_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Person/Attribute:team_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Person/Attribute:team_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'Tous les tickets dont cette personne est le bénéficiaire',
	'Class:Person/Attribute:user_list' => 'Utilisateurs',
	'Class:Person/Attribute:user_list+' => 'Les comptes utilisateurs associés à cette personne',
	'Class:Person/Attribute:user_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Person/Attribute:user_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de sa %1$s',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Nom du manager',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Photo',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'Le numéro d\'employé doit être unique dans l\'organisation',
	'Class:Person/UniquenessRule:employee_number' => 'il y a déjà une personne avec ce numéro d\'employé dans l\'organisation 
	\'$this->org_name$\'',
	'Class:Person/UniquenessRule:name+' => 'Le nom de l\'employé devrait être unique dans l\'organisation',
	'Class:Person/UniquenessRule:name' => 'Il y a déjà une personne avec ce nom dans l\'organisation \'$this->org_name$\'',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible de déplacer cette personne sous l\'organisation \'%1$s\', cela casserait son accès au portail utilisateur, car il n\'a pas le droit de voir cette organisation',
]);

//
// Class: Team
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Team' => 'Équipe',
	'Class:Team+' => 'Type de contact. Souvent utilisé pour regrouper des personnes, mais pas seulement. Les équipes sont censées suivre les tickets qui leur sont affectés et les assigner à un agent, généralement membre de cette équipe.',
	'Class:Team/ComplementaryName' => '%1$s - %2$s',
	'Class:Team/Attribute:persons_list' => 'Membres',
	'Class:Team/Attribute:persons_list+' => 'Toutes les personnes appartenant à cette équipe',
	'Class:Team/Attribute:persons_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:Team/Attribute:persons_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Team/Attribute:persons_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Team/Attribute:persons_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
	'Class:Team/Attribute:overview' => 'Tableau de bord',
	'Team:Overview' => 'Tickets et équipements gérés par cette équipe',
	'Team:Overview:ActiveTickets' => 'Tickets ouverts',
	'Team:Overview:FunctionalCIs-ByType' => 'CI Fonctionnels',
	'Team:Overview:UserRequest-ByStatus' => 'Demandes utilisateur par état',
	'Team:Overview:UserRequest-ClosedByMonth' => 'Demandes utilisateur fermées par mois (12 derniers mois)',
	'Team:Overview:UserRequest-ClosedByAgent' => 'Demandes utilisateur fermées par agent (12 derniers mois)',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => 'Tous les tickets assignés à cette équipe',
]);

//
// Class: Document
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Document' => 'Document',
	'Class:Document+' => 'Classe abstraite. Document pouvant être partagé entre plusieurs objets, ce qui le rend facile et rapide à retrouver depuis différents points de vue.',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s',
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
	'Class:Document/Attribute:status' => 'Etat',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Brouillon',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolète',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Publié',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => 'Tous les éléments de configuration liés à ce document',
	'Class:Document/Attribute:finalclass' => 'Sous-classe de Document',
	'Class:Document/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: DocumentFile
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DocumentFile' => 'Document Fichier',
	'Class:DocumentFile+' => 'Type de document qui inclut un fichier téléchargé (tout format : Word, PDF, tableur, etc.).',
	'Class:DocumentFile/Attribute:file' => 'Fichier',
	'Class:DocumentFile/Attribute:file+' => '',
]);

//
// Class: DocumentNote
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DocumentNote' => 'Document Note',
	'Class:DocumentNote+' => 'Utilisé pour stocker un document texte. Le formatage HTML est pris en charge via l\'éditeur WYSIWYG. Une recherche peut être effectuer sur son contenu.',
	'Class:DocumentNote/Attribute:text' => 'Texte',
	'Class:DocumentNote/Attribute:text+' => '',
]);

//
// Class: DocumentWeb
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DocumentWeb' => 'Document Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
]);

//
// Class: Typology
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => 'Classe abstraite. Les attributs ExternalKey vers une sous-classe de Typology sont utilisés à la place d\'un EnumAttribute pour disposer de valeurs plus dynamiques.',
	'Class:Typology/Attribute:name' => 'Nom',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Sous-classe de Typologie',
	'Class:Typology/Attribute:finalclass+' => 'Nom de la classe instanciable',
]);

//
// Class: DocumentType
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:DocumentType' => 'Type de document',
	'Class:DocumentType+' => 'Typologie. Système de classification utilisé pour organiser et typer logiquement les documents.',
]);

//
// Class: ContactType
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:ContactType' => 'Type de contact',
	'Class:ContactType+' => 'Typologie pour organiser vos contacts et les regrouper logiquement.',
]);

//
// Class: lnkPersonToTeam
//

Dict::Add('FR FR', 'French', 'Français', [
	'Class:lnkPersonToTeam' => 'Lien Personne / Équipe',
	'Class:lnkPersonToTeam+' => 'Ce lien indique lorsqu\'une Personne est membre d\'une Équipe.',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s',
	'Class:lnkPersonToTeam/Name+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Equipe',
	'Class:lnkPersonToTeam/Attribute:team_id+' => 'Une équipe à laquelle appartient la personne',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Nom Equipe',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Personne',
	'Class:lnkPersonToTeam/Attribute:person_id+' => 'Un membre de l\'équipe',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Nom Personne',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rôle',
	'Class:lnkPersonToTeam/Attribute:role_id+' => 'Un rôle parmi une typologie de rôles possibles',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Nom Role',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
]);

//
// Application Menu
//

Dict::Add('FR FR', 'French', 'Français', [
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
	'Menu:ConfigManagement' => 'Gestion des configurations',
	'Menu:ConfigManagement+' => 'Gestion des configurations',
	'Menu:ConfigManagementCI' => 'CIs',
	'Menu:ConfigManagementCI+' => 'CIs',
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
	'Menu:NewContact' => 'Nouveau contact',
	'Menu:NewContact+' => 'Nouveau contact',
	'Menu:SearchContacts' => 'Rechercher des contacts',
	'Menu:SearchContacts+' => 'Rechercher des contacts',
	'Menu:ConfigManagement:Shortcuts' => 'Raccourcis',
	'Menu:ConfigManagement:AllContacts' => 'Tous les contacts: %1$d',
	'Menu:Typology' => 'Typologie configuration',
	'Menu:Typology+' => 'Typologie configuration',
	'UI_WelcomeMenu_AllConfigItems' => 'Résumé',
	'Menu:ConfigManagement:Typology' => 'Configuration des typologies',
]);

// Add translation for Fieldsets

Dict::Add('FR FR', 'French', 'Français', [
	'Person:info' => 'Informations générales',
	'User:info' => 'Informations générales',
	'User:profiles' => 'Profils (minimum un)',
	'Person:personal_info' => 'Informations personnelles',
	'Person:notifiy' => 'Notification',
]);

// Themes
Dict::Add('FR FR', 'French', 'Français', [
	'theme:fullmoon' => 'Full moon',
	'theme:test-red' => 'Instance de test (Rouge)',
]);

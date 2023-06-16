<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: Organization
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
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
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
));

//
// Class: Location
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Location' => 'Lieu',
	'Class:Location+' => 'Tout type de lieu: Région, Pays, Ville, Site, batiment, Bureau,...',
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
));

//
// Class: Contact
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Contact' => 'Contact',
	'Class:Contact+' => '',
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
));

//
// Class: Person
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Person' => 'Personne',
	'Class:Person+' => '',
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
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'Tous les tickets dont cette personne est le bénéficiaire',
	'Class:Person/Attribute:user_list' => 'Utilisateurs',
	'Class:Person/Attribute:user_list+' => 'Les comptes utilisateurs associés à cette personne',
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
));

//
// Class: Team
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Team' => 'Equipe',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s',
	'Class:Team/Attribute:persons_list' => 'Membres',
	'Class:Team/Attribute:persons_list+' => 'Toutes les personnes appartenant à cette équipe',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => 'Tous les tickets assignés à cette équipe',
));

//
// Class: Document
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
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
// Class: Typology
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Typology' => 'Typologie',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Nom',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Sous-classe de Typologie',
	'Class:Typology/Attribute:finalclass+' => 'Nom de la classe instanciable',
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
// Class: lnkPersonToTeam
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkPersonToTeam' => 'Lien Personne / Equipe',
	'Class:lnkPersonToTeam+' => '',
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
));

//
// Application Menu
//

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
));

// Add translation for Fieldsets

Dict::Add('FR FR', 'French', 'Français', array(
	'Person:info' => 'Informations générales',
	'User:info' => 'Informations générales',
	'User:profiles' => 'Profils (minimum un)',
	'Person:personal_info' => 'Informations personnelles',
	'Person:notifiy' => 'Notification',
));

// Themes
Dict::Add('FR FR', 'French', 'Français', array(
	'theme:fullmoon' => 'Full moon',
	'theme:test-red' => 'Instance de test (Rouge)',
));
//
// n:n relations custom labels : nom de classe féminin
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Person/Attribute:team_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:Person/Attribute:team_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Person/Attribute:team_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Person/Attribute:team_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s',
	'Class:Team/Attribute:persons_list/UI:Links:Add:Button+' => 'Ajouter une %4$s',
	'Class:Team/Attribute:persons_list/UI:Links:Add:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Team/Attribute:persons_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Team/Attribute:persons_list/UI:Links:Remove:Modal:Title' => 'Retirer une %4$s'
));
// 1:n relations custom labels for tooltip and pop-up title
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Location/Attribute:person_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Location/Attribute:person_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:Location/Attribute:person_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Location/Attribute:person_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Person/Attribute:user_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de sa %1$s',
	'Class:Person/Attribute:user_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Person/Attribute:user_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s'
));

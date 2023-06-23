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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: Change
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:ChangeManagement' => 'Gestion des changements',
	'Menu:Change:Overview' => 'Vue d\'ensemble',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nouveau changement',
	'Menu:NewChange+' => 'Créer un nouveau ticket de changement',
	'Menu:SearchChanges' => 'Rechercher des changements',
	'Menu:SearchChanges+' => 'Rechercher parmi les tickets de changement',
	'Menu:Change:Shortcuts' => 'Raccourcis',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changements en attente d\'acceptance',
	'Menu:WaitingAcceptance+' => 'Changements en attente d\'acceptance',
	'Menu:WaitingApproval' => 'Changement en attente d\'approbation',
	'Menu:WaitingApproval+' => 'Changement en attente d\'approbation',
	'Menu:Changes' => 'Changements ouverts',
	'Menu:Changes+' => 'Tickets de changement ouverts',
	'Menu:MyChanges' => 'Mes tickets de changement',
	'Menu:MyChanges+' => 'Tickets de changement qui me sont assignés',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changements par catégorie',
	'UI-ChangeManagementOverview-Last-7-days' => 'Changements par jour',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changements par domaine',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changements par statut',
	'Tickets:Related:OpenChanges' => 'Changements en cours',
	'Tickets:Related:RecentChanges' => 'Changements récents (72h)',
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


//
// Class: Change
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Change' => 'Ticket de Changement',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Etat',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nouveau',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Assigné',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Planifié',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejeté',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Approuvé',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Fermé',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Catégorie',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Application',
	'Class:Change/Attribute:category/Value:application+' => 'Application',
	'Class:Change/Attribute:category/Value:hardware' => 'Matériel',
	'Class:Change/Attribute:category/Value:hardware+' => 'Matériel',
	'Class:Change/Attribute:category/Value:network' => 'Réseau',
	'Class:Change/Attribute:category/Value:network+' => 'Réseau',
	'Class:Change/Attribute:category/Value:other' => 'Autre',
	'Class:Change/Attribute:category/Value:other+' => 'Autre',
	'Class:Change/Attribute:category/Value:software' => 'Logiciel',
	'Class:Change/Attribute:category/Value:software+' => 'Logiciel',
	'Class:Change/Attribute:category/Value:system' => 'Système',
	'Class:Change/Attribute:category/Value:system+' => 'Système',
	'Class:Change/Attribute:reject_reason' => 'Raison du rejet',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Responsable du changement',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'Email Responsable du changement',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Changement parent',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Référence changement parent',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Date de création',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Date d\'approbation',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Plan de secours',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Demandes liées',
	'Class:Change/Attribute:related_request_list+' => 'Toutes les demandes utilisateurs liées à ce changement',
	'Class:Change/Attribute:related_incident_list' => 'Incidents liés',
	'Class:Change/Attribute:related_incident_list+' => 'Tous les incidents liés à ce changement',
	'Class:Change/Attribute:related_problems_list' => 'Problèmes liés',
	'Class:Change/Attribute:related_problems_list+' => 'Tous les problèmes liés à ce changement',
	'Class:Change/Attribute:child_changes_list' => 'Changements fils',
	'Class:Change/Attribute:child_changes_list+' => 'Tous les sous-changements liés à ce changement',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nom usuel du changement parent',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Assigner',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planifier',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Rejeter',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Réouvrir',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Approuver',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Fermer',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Interruption de service',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Non',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Oui',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));
// 1:n relations custom labels for tooltip and pop-up title
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Change/Attribute:child_changes_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Change/Attribute:child_changes_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Change/Attribute:child_changes_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Change/Attribute:child_changes_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Change/Attribute:child_changes_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Change/Attribute:child_changes_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Change/Attribute:related_incident_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Create:Button+' => 'Créer un %4$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Create:Modal:Title' => 'Ajouter un %4$s à %2$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Remove:Button+' => 'Retirer ce %4$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Remove:Modal:Title' => 'Retirer ce %4$s de son %1$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Delete:Button+' => 'Supprimer ce %4$s',
	'Class:Change/Attribute:related_problems_list/UI:Links:Delete:Modal:Title' => 'Supprimer un %4$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Create:Button+' => 'Créer une %4$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Create:Modal:Title' => 'Ajouter une %4$s à %2$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Remove:Button+' => 'Retirer cette %4$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Remove:Modal:Title' => 'Retirer cette %4$s de son %1$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Delete:Button+' => 'Supprimer cette %4$s',
	'Class:Change/Attribute:related_request_list/UI:Links:Delete:Modal:Title' => 'Supprimer une %4$s'
));

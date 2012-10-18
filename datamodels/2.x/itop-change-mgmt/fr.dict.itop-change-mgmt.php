<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * @author	Erwan Taloc <erwan.taloc@combodo.com>
 * @author	Romain Quetiez <romain.quetiez@combodo.com>
 * @author	Denis Flaven <denis.flaven@combodo.com>
 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//
// Class: Change
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Change' => 'Ticket de changement',
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
	'Class:Change/Attribute:category' => 'Categorie',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'application',
	'Class:Change/Attribute:category/Value:application+' => 'application',
	'Class:Change/Attribute:category/Value:hardware' => 'Matériel',
	'Class:Change/Attribute:category/Value:hardware+' => 'Matériel',
	'Class:Change/Attribute:category/Value:network' => 'Réseau',
	'Class:Change/Attribute:category/Value:network+' => 'Réseau',
	'Class:Change/Attribute:category/Value:other' => 'Autre',
	'Class:Change/Attribute:category/Value:other+' => 'Autre',
	'Class:Change/Attribute:category/Value:software' => 'Logiciel',
	'Class:Change/Attribute:category/Value:software+' => 'Logiciel',
	'Class:Change/Attribute:category/Value:system' => 'système',
	'Class:Change/Attribute:category/Value:system+' => 'système',
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
	'Class:Change/Attribute:related_request_list' => 'Requêtes liées',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Incidents liés',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Changements fils',
	'Class:Change/Attribute:child_changes_list+' => '',
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
));


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
	'Menu:WaitingApproval' => 'Changement en attente d\'approbation',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Changements ouverts',
	'Menu:Changes+' => 'Tickets de changement ouverts',
	'Menu:MyChanges' => 'Mes tickets de changement',
	'Menu:MyChanges+' => 'Tickets de changement qui me sont assignés',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changements par catégorie',
	'UI-ChangeManagementOverview-Last-7-days' => 'Changements par jour',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changements par domaine',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changements par statut',
	'UI:ChangeMgmtMenuOverview:Title' => 'Tableau de bord des changements pour les 7 derniers jours',



));
?>

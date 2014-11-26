<?php
// Copyright (C) 2010-2012 Combodo SARL
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
// Class: Problem
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Problem' => 'Problème',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Etat',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Nouveau',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Assigné',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Résolu',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Fermé',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Nom du service',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Sous catégorie de service',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Nom sous catégorie de service',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Produit',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impacte',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Un département',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Un service',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Une personne',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgence',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'critique',
	'Class:Problem/Attribute:urgency/Value:1+' => 'critique',
	'Class:Problem/Attribute:urgency/Value:2' => 'haute',
	'Class:Problem/Attribute:urgency/Value:2+' => 'haute',
	'Class:Problem/Attribute:urgency/Value:3' => 'moyenne',
	'Class:Problem/Attribute:urgency/Value:3+' => 'moyenne',
	'Class:Problem/Attribute:urgency/Value:4' => 'basse',
	'Class:Problem/Attribute:urgency/Value:4+' => 'basse',
	'Class:Problem/Attribute:priority' => 'Priorité',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'critique',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'haute',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'moyenne',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:priority/Value:4' => 'basse',
	'Class:Problem/Attribute:priority/Value:4+' => 'Low',
	'Class:Problem/Attribute:related_change_id' => 'Changement relatif',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref Changement relatif',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Date d\'assignation',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Date de résolution',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Erreurs connues',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Attribute:related_request_list' => 'Requêtes liées',
	'Class:Problem/Attribute:related_request_list+' => '',
	'Class:Problem/Attribute:related_incident_list' => 'Incidents liés',
	'Class:Problem/Attribute:related_incident_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Assigner',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Réaassigner',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Résoudre',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Fermer',
	'Class:Problem/Stimulus:ev_close+' => '',
));

Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:Problem:KnownErrors' => 'Erreurs connues',
	'Menu:Problem:KnownErrors+' => 'Erreurs connues',
	'Menu:ProblemManagement' => 'Gestion des problèmes',
	'Menu:ProblemManagement+' => 'Gestion des problèmes',
	'Menu:Problem:Overview' => 'Vue d\'ensemble',
	'Menu:Problem:Overview+' => 'Vue d\'ensemble',
	'Menu:NewProblem' => 'Nouveau problème',
	'Menu:NewProblem+' => 'Nouveau problème',
	'Menu:SearchProblems' => 'Rechercher des problèmes',
	'Menu:SearchProblems+' => 'Rechercher des problèmes',
	'Menu:Problem:Shortcuts' => 'Raccourcis',
	'Menu:Problem:MyProblems' => 'Mes problèmes',
	'Menu:Problem:MyProblems+' => 'Mes problèmes',
	'Menu:Problem:OpenProblems' => 'Problèmes ouverts',
	'Menu:Problem:OpenProblems+' => 'Problèmes ouverts',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problèmes par service',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problèmes par service',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problèmes par priorité',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problèmes par priorité',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Problèmes non affectés à un agent',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Problèmes non affectés à un agent',
	'UI:ProblemMgmtMenuOverview:Title' => 'Tableau de bord de la Gestion des Problèmes',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Tableau de bord de la Gestion des Problèmes',
));
?>

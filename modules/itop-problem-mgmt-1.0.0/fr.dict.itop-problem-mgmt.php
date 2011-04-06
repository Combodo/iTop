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
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

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




Dict::Add('FR FR', 'French', 'Français', array(
        'Menu:ProblemManagement' => 'Gestion des problèmes',
        'Menu:ProblemManagement+' => 'Gestion des problèmes',
    	'Menu:Problem:Overview' => 'Vue d\'ensemble',
    	'Menu:Problem:Overview+' => 'Vue d\'ensemble',
    	'Menu:NewProblem' => 'Nouveau Problème',
    	'Menu:NewProblem+' => 'Nouveau Problème',
    	'Menu:SearchProblems' => 'Rechercer des Problèmes',
    	'Menu:SearchProblems+' => 'Rechercher des Problèmes',
    	'Menu:Problem:KnownErrors' => 'Erreurs connues',
    	'Menu:Problem:KnownErrors+' => 'Erreurs connues',
    	'Menu:Problem:Shortcuts' => 'Raccourcis',
        'Menu:Problem:MyProblems' => 'Mes Problèmes',
        'Menu:Problem:MyProblems+' => 'Mes Problèmes',
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



//
// Class: Problem
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Problem' => 'Problème',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Nouveau',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Assigné',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Résolu',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Fermé',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Client',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => 'Nom',
	'Class:Problem/Attribute:org_name+' => 'Nom commun',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Nom du service',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Catégorie de service',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Nom',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Produit',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impacte',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Une personne',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Un Service',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Un Département',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgence',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Basse',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Basse',
	'Class:Problem/Attribute:urgency/Value:2' => 'Moyenne',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Moyenne',
	'Class:Problem/Attribute:urgency/Value:3' => 'Haute',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Haute',
	'Class:Problem/Attribute:priority' => 'Priorité',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Basse',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Moyenne',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Haute',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'Groupe de travail',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Nom',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => 'Agent',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => 'Nom',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'Email de l\'agent',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Changement relatif',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => 'Date de cloture',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Dernière mise à jour',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Date d\'assignation',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Date de résolution',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Erreurs connues',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Assigner',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Réaassigner',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Résoudre',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Fermer',
	'Class:Problem/Stimulus:ev_close+' => '',
));

?>

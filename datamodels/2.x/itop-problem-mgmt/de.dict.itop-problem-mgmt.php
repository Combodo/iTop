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
 * @author	Stephan Rosenke <stephan.rosenke@itomig.de>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Problem' => 'Problem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Neu',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Zugewiesen',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Gelöst',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Geschlossen',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Service-Kategorie',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:product' => 'Produkt',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Auswirkung',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Eine Person',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Einen Service',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Eine Abteilung',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Dringlichkeit',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Kritisch',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Kritisch',
	'Class:Problem/Attribute:urgency/Value:2' => 'Hoch',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Hoch',
	'Class:Problem/Attribute:urgency/Value:3' => 'Mittel',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Mittel',
	'Class:Problem/Attribute:urgency/Value:4' => 'Niedrig',
	'Class:Problem/Attribute:urgency/Value:4+' => 'Niedrig',
	'Class:Problem/Attribute:priority' => 'Priorität',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Kritisch',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Hoch',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Mittel',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:priority/Value:4' => 'Niedrig',
	'Class:Problem/Attribute:priority/Value:4+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Zusammenhängender Change',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Datum der Zuordnung',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Datum der Lösung',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Known Errors',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Attribute:related_request_list' => 'Verwandte Requests',
	'Class:Problem/Attribute:related_request_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Zuweisen',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Neu zuweisen',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Lösen',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Schließen',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Overview' => 'Übersicht',
	'Menu:Problem:Overview+' => 'Übersicht',
	'Menu:NewProblem' => 'Neues Problem',
	'Menu:NewProblem+' => 'Neues Problem',
	'Menu:SearchProblems' => 'Nach Problemen suchen',
	'Menu:SearchProblems+' => 'Nach Problemen suchen',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:Problem:MyProblems' => 'Meine Probleme',
	'Menu:Problem:MyProblems+' => 'Meine Probleme',
	'Menu:Problem:OpenProblems' => 'Alle offenen Probleme',
	'Menu:Problem:OpenProblems+' => 'Alle offenen (noch nicht geschlossenen) Probleme',
	'UI-ProblemManagementOverview-ProblemByService' => 'Probleme nach Service',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Probleme nach Service',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Probleme nach Priorität',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Probleme nach Priorität',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Nicht zugewiesene Probleme',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Nicht zugewiesene Probleme',
	'UI:ProblemMgmtMenuOverview:Title' => 'Dashboard für das Problem Management',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Dashboard für das Problem Management',
	'Class:Problem/Attribute:service_name' => 'Name',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Name',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
));
?>

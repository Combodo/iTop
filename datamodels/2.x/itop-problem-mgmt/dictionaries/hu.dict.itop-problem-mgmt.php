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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:ProblemManagement' => 'Probléma menedzsment',
	'Menu:ProblemManagement+' => '',
	'Menu:Problem:Overview' => 'Áttekintő',
	'Menu:Problem:Overview+' => '',
	'Menu:NewProblem' => 'Új probléma',
	'Menu:NewProblem+' => '',
	'Menu:SearchProblems' => 'Probléma keresés',
	'Menu:SearchProblems+' => '',
	'Menu:Problem:Shortcuts' => 'Gyorsmenü',
	'Menu:Problem:MyProblems' => 'Saját problémák',
	'Menu:Problem:MyProblems+' => '',
	'Menu:Problem:OpenProblems' => 'Összes nyitott probléma',
	'Menu:Problem:OpenProblems+' => '',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problémák szolgáltatásonként',
	'UI-ProblemManagementOverview-ProblemByService+' => '',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problémák prioritás szerint',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Nem kiosztott problémák',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '',
	'UI:ProblemMgmtMenuOverview:Title' => 'Probléma menedzsment dashboard',
	'UI:ProblemMgmtMenuOverview:Title+' => '',

));
//
// Class: Problem
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Problem' => 'Probléma',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Státusz',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Új',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Kiosztott',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Megoldott',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Lezárt',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Szolgáltatás',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Neve',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Szolgáltatás kategória',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Neve',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Termék',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Hatás',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Felhasználókra',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Szolgáltatásokra',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Szervezeti egységre',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Sűrgősség',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Nem fontos',
	'Class:Problem/Attribute:urgency/Value:1+' => '',
	'Class:Problem/Attribute:urgency/Value:2' => 'Fontos',
	'Class:Problem/Attribute:urgency/Value:2+' => '',
	'Class:Problem/Attribute:urgency/Value:3' => 'Nagyon fontos',
	'Class:Problem/Attribute:urgency/Value:3+' => '',
	'Class:Problem/Attribute:urgency/Value:4' => 'low~~',
	'Class:Problem/Attribute:urgency/Value:4+' => 'low~~',
	'Class:Problem/Attribute:priority' => 'Prioritás',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Alacsony',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Közepes',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Magas',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:priority/Value:4' => 'Low~~',
	'Class:Problem/Attribute:priority/Value:4+' => 'Low~~',
	'Class:Problem/Attribute:related_change_id' => 'Kapcsolódó változások',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Referencia',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Felelőshöz rendelés',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Megoldás dátuma',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Ismert hibák',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Attribute:related_request_list' => 'Related requests~~',
	'Class:Problem/Attribute:related_request_list+' => 'All the requests that are related to this problem~~',
	'Class:Problem/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Problem/Attribute:related_incident_list+' => 'All the incidents that are related to this problem~~',
	'Class:Problem/Stimulus:ev_assign' => 'Hozzárendelés',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Átrendelés',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Megoldás',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Lezárás',
	'Class:Problem/Stimulus:ev_close+' => '',
));

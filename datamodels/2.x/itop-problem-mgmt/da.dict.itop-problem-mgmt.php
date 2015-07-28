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
 * @author	Erik Bøg <erik@boegmoeller.dk>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Problem' => 'Problem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Ny',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Tildelt',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Løst',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Lukket',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Service Kategori',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:product' => 'Produkt',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Påvirkning',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Afdeling',
	'Class:Problem/Attribute:impact/Value:1+' => 'En afdeling er påvirket',
	'Class:Problem/Attribute:impact/Value:2' => 'Service',
	'Class:Problem/Attribute:impact/Value:2+' => 'En service er påvirket',
	'Class:Problem/Attribute:impact/Value:3' => 'Person',
	'Class:Problem/Attribute:impact/Value:3+' => 'En person er påvirket',
	'Class:Problem/Attribute:urgency' => 'Vigtighed',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Lav',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Lav',
	'Class:Problem/Attribute:urgency/Value:2' => 'Middel',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Middel',
	'Class:Problem/Attribute:urgency/Value:3' => 'Høj',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Høj',
	'Class:Problem/Attribute:urgency/Value:4' => '',
	'Class:Problem/Attribute:urgency/Value:4+' => '',
	'Class:Problem/Attribute:priority' => 'Prioritet',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Lav',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Middel',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Høj',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:priority/Value:4' => '',
	'Class:Problem/Attribute:priority/Value:4+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Relateret Change',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Dato tildelt',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Dato løst',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Known Errors',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Attribute:related_request_list' => 'Relateret Requests',
	'Class:Problem/Attribute:related_request_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Tildel',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Forny tildeling',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Løs',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Luk',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Overview' => 'Oversigt',
	'Menu:Problem:Overview+' => 'Oversigt',
	'Menu:NewProblem' => 'Nyt Problem',
	'Menu:NewProblem+' => 'Nyt Problem',
	'Menu:SearchProblems' => 'Søg efter problem',
	'Menu:SearchProblems+' => 'Søg efter problem',
	'Menu:Problem:Shortcuts' => 'Genvej',
	'Menu:Problem:MyProblems' => 'Mine Problemer',
	'Menu:Problem:MyProblems+' => 'Mine Problemer',
	'Menu:Problem:OpenProblems' => 'Alle uløste problemer',
	'Menu:Problem:OpenProblems+' => 'Alle uløste problemer',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemer efter ydelse',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemer efter ydelse',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemer efter prioritet',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemer efter prioritet',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Ikke tildelte problemer',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Ikke tildelte problemer',
	'UI:ProblemMgmtMenuOverview:Title' => 'Dashboard for problem Management',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Dashboard for problem Management',
	'Class:Problem/Attribute:service_name' => 'Navn',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Navn',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Problem/Attribute:related_incident_list+' => 'All the incidents that are related to this problem~~',
));
?>
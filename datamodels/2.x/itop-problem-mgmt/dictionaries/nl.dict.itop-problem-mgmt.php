<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author Hipska (2018)
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 * 
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ProblemManagement' => 'Probleem Management',
	'Menu:ProblemManagement+' => 'Probleem Management',
	'Menu:Problem:Overview' => 'Overzicht',
	'Menu:Problem:Overview+' => 'Overzicht',
	'Menu:NewProblem' => 'Nieuw probleem',
	'Menu:NewProblem+' => 'Maak nieuw probleem aan',
	'Menu:SearchProblems' => 'Zoek naar problemen',
	'Menu:SearchProblems+' => 'Zoek naar problemen',
	'Menu:Problem:Shortcuts' => 'Snelkoppelingen',
	'Menu:Problem:MyProblems' => 'Mijn problemen',
	'Menu:Problem:MyProblems+' => 'Mijn problemen',
	'Menu:Problem:OpenProblems' => 'Alle open problemen',
	'Menu:Problem:OpenProblems+' => 'Alle open problemen',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemen per service',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemen per service',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemen per prioriteit',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemen per prioriteit',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Niet toegewezen problemen',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Niet toegewezen problemen',
	'UI:ProblemMgmtMenuOverview:Title' => 'Dashboard voor Probleem Management',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Dashboard voor Probleem Management',

));
//
// Class: Problem
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Problem' => 'Probleem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Nieuw',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Toegewezen',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Opgelost',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Gesloten',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Service',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Naam service',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Subcategorie service',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Naam subcategorie service',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Product',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impact',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Afdeling',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Service',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Persoon',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgentie',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Kritiek',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Kritiek',
	'Class:Problem/Attribute:urgency/Value:2' => 'Hoog',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Hoog',
	'Class:Problem/Attribute:urgency/Value:3' => 'Normaal',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Normaal',
	'Class:Problem/Attribute:urgency/Value:4' => 'Laag',
	'Class:Problem/Attribute:urgency/Value:4+' => 'Laag',
	'Class:Problem/Attribute:priority' => 'Prioriteit',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Kritisch',
	'Class:Problem/Attribute:priority/Value:1+' => 'Kritisch',
	'Class:Problem/Attribute:priority/Value:2' => 'Hoog',
	'Class:Problem/Attribute:priority/Value:2+' => 'Hoog',
	'Class:Problem/Attribute:priority/Value:3' => 'Normaal',
	'Class:Problem/Attribute:priority/Value:3+' => 'Normaal',
	'Class:Problem/Attribute:priority/Value:4' => 'Laag',
	'Class:Problem/Attribute:priority/Value:4+' => 'Laag',
	'Class:Problem/Attribute:related_change_id' => 'Gerelateerde change',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref. gerelateerde change',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Toegewezen op',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Opgelost sinds',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Gekende fouten',
	'Class:Problem/Attribute:knownerrors_list+' => 'Alle gekende fouten gerelateerd aan dit probleem',
	'Class:Problem/Attribute:related_request_list' => 'Gelinkte verzoeken',
	'Class:Problem/Attribute:related_request_list+' => 'Alle verzoeken gerelateerd aan dit probleem',
	'Class:Problem/Attribute:related_incident_list' => 'Gelinkte incidenten',
	'Class:Problem/Attribute:related_incident_list+' => 'Alle incidenten gerelateerd aan dit probleem',
	'Class:Problem/Stimulus:ev_assign' => 'Wijs toe',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Wijs opnieuw toe',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Los het op',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Sluit',
	'Class:Problem/Stimulus:ev_close+' => '',
));

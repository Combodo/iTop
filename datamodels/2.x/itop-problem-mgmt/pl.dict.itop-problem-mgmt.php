<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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


Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:ProblemManagement' => 'Zarządzanie problemami',
	'Menu:ProblemManagement+' => 'Zarządzanie problemami',
	'Menu:Problem:Overview' => 'Przegląd',
	'Menu:Problem:Overview+' => 'Przegląd',
	'Menu:NewProblem' => 'Nowy problem',
	'Menu:NewProblem+' => 'Nowy problem',
	'Menu:SearchProblems' => 'Wyszukaj problemy',
	'Menu:SearchProblems+' => 'Wyszukaj problemy',
	'Menu:Problem:Shortcuts' => 'Skróty',
	'Menu:Problem:MyProblems' => 'Moje problemy',
	'Menu:Problem:MyProblems+' => 'Moje problemy',
	'Menu:Problem:OpenProblems' => 'Wszystkie otwarte problemy',
	'Menu:Problem:OpenProblems+' => 'Wszystkie otwarte problemy',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemy według usług',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemy według usług',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemy według priorytetu',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemy według priorytetu',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Nieprzypisane problemy',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Nieprzypisane problemy',
	'UI:ProblemMgmtMenuOverview:Title' => 'Pulpit do zarządzania problemami',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Pulpit do zarządzania problemami',

));
//
// Class: Problem
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Problem' => 'Problem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Status',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Nowy',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Przydzielony',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Rozwiązany',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Zamknięty',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'Usługa',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Nazwa usługi',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Podkategoria usług',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Podkategoria usług',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Produkt',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Wpływ',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Wydział',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Usługa',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Osoba',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Pilność',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'krytyczna',
	'Class:Problem/Attribute:urgency/Value:1+' => 'krytyczna',
	'Class:Problem/Attribute:urgency/Value:2' => 'wysoka',
	'Class:Problem/Attribute:urgency/Value:2+' => 'wysoka',
	'Class:Problem/Attribute:urgency/Value:3' => 'średnia',
	'Class:Problem/Attribute:urgency/Value:3+' => 'średnia',
	'Class:Problem/Attribute:urgency/Value:4' => 'niska',
	'Class:Problem/Attribute:urgency/Value:4+' => 'niska',
	'Class:Problem/Attribute:priority' => 'Priorytet',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'krytyczny',
	'Class:Problem/Attribute:priority/Value:1+' => 'krytyczny',
	'Class:Problem/Attribute:priority/Value:2' => 'wysoki',
	'Class:Problem/Attribute:priority/Value:2+' => 'wysoki',
	'Class:Problem/Attribute:priority/Value:3' => 'średni',
	'Class:Problem/Attribute:priority/Value:3+' => 'średni',
	'Class:Problem/Attribute:priority/Value:4' => 'niski',
	'Class:Problem/Attribute:priority/Value:4+' => 'niski',
	'Class:Problem/Attribute:related_change_id' => 'Powiązana zmiana',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Odniesienie do powiązanej zmiany',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Data przydziału',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Data rozwiązania',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Znane błędy',
	'Class:Problem/Attribute:knownerrors_list+' => 'Wszystkie znane błędy związane z tym problemem',
	'Class:Problem/Attribute:related_request_list' => 'Powiązane zgłoszenia',
	'Class:Problem/Attribute:related_request_list+' => 'Wszystkie zgłoszenia, które są związane z tym problemem',
	'Class:Problem/Attribute:related_incident_list' => 'Powiązane incydenty',
	'Class:Problem/Attribute:related_incident_list+' => 'Wszystkie incydenty związane z tym problemem',
	'Class:Problem/Stimulus:ev_assign' => 'Przydziel',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Przydziel ponownie',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Rozwiąż',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Zamknij',
	'Class:Problem/Stimulus:ev_close+' => '',
));

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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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


Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Menu:ProblemManagement' => 'Správa problémů',
    'Menu:ProblemManagement+' => 'Správa problémů',
    'Menu:Problem:Overview' => 'Přehled',
    'Menu:Problem:Overview+' => 'Přehled',
    'Menu:NewProblem' => 'Nový problém',
    'Menu:NewProblem+' => 'Nový problém',
    'Menu:SearchProblems' => 'Hledat problémy',
    'Menu:SearchProblems+' => 'Hledat problémy',
    'Menu:Problem:Shortcuts' => 'Odkazy',
    'Menu:Problem:MyProblems' => 'Mé problémy',
    'Menu:Problem:MyProblems+' => 'Mé problémy',
    'Menu:Problem:OpenProblems' => 'Všechny otevřené problémy',
    'Menu:Problem:OpenProblems+' => 'Všechny otevřené problémy',
    'UI-ProblemManagementOverview-ProblemByService' => 'Problémy podle služby',
    'UI-ProblemManagementOverview-ProblemByService+' => 'Problémy podle služby',
    'UI-ProblemManagementOverview-ProblemByPriority' => 'Problémy podle priority',
    'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problémy podle priority',
    'UI-ProblemManagementOverview-ProblemUnassigned' => 'Nepřidělené problémy',
    'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Nepřidělené problémy',
    'UI:ProblemMgmtMenuOverview:Title' => 'Dashboard pro správu problémů (Problem management)',
    'UI:ProblemMgmtMenuOverview:Title+' => 'Dashboard pro správu problémů (Problem management)',
));
//
// Class: Problem
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Problem' => 'Problém',
    'Class:Problem+' => '',
    'Class:Problem/Attribute:status' => 'Stav',
    'Class:Problem/Attribute:status+' => '',
    'Class:Problem/Attribute:status/Value:new' => 'Nový',
    'Class:Problem/Attribute:status/Value:new+' => '',
    'Class:Problem/Attribute:status/Value:assigned' => 'Přidělený',
    'Class:Problem/Attribute:status/Value:assigned+' => '',
    'Class:Problem/Attribute:status/Value:resolved' => 'Vyřešený',
    'Class:Problem/Attribute:status/Value:resolved+' => '',
    'Class:Problem/Attribute:status/Value:closed' => 'Uzavřený',
    'Class:Problem/Attribute:status/Value:closed+' => '',
    'Class:Problem/Attribute:service_id' => 'Služba',
    'Class:Problem/Attribute:service_id+' => '',
    'Class:Problem/Attribute:service_name' => 'Název služby',
    'Class:Problem/Attribute:service_name+' => '',
    'Class:Problem/Attribute:servicesubcategory_id' => 'Podkategorie služeb',
    'Class:Problem/Attribute:servicesubcategory_id+' => '',
    'Class:Problem/Attribute:servicesubcategory_name' => 'Podkategorie služeb',
    'Class:Problem/Attribute:servicesubcategory_name+' => '',
    'Class:Problem/Attribute:product' => 'Produkt',
    'Class:Problem/Attribute:product+' => '',
    'Class:Problem/Attribute:impact' => 'Dopad',
    'Class:Problem/Attribute:impact+' => '',
    'Class:Problem/Attribute:impact/Value:1' => 'Oddělení',
    'Class:Problem/Attribute:impact/Value:1+' => '',
    'Class:Problem/Attribute:impact/Value:2' => 'Služba',
    'Class:Problem/Attribute:impact/Value:2+' => '',
    'Class:Problem/Attribute:impact/Value:3' => 'Osoba',
    'Class:Problem/Attribute:impact/Value:3+' => '',
    'Class:Problem/Attribute:urgency' => 'Naléhavost',
    'Class:Problem/Attribute:urgency+' => '',
    'Class:Problem/Attribute:urgency/Value:1' => 'kritická',
    'Class:Problem/Attribute:urgency/Value:1+' => '',
    'Class:Problem/Attribute:urgency/Value:2' => 'vysoká',
    'Class:Problem/Attribute:urgency/Value:2+' => '',
    'Class:Problem/Attribute:urgency/Value:3' => 'střední',
    'Class:Problem/Attribute:urgency/Value:3+' => '',
    'Class:Problem/Attribute:urgency/Value:4' => 'nízká',
    'Class:Problem/Attribute:urgency/Value:4+' => '',
    'Class:Problem/Attribute:priority' => 'Priorita',
    'Class:Problem/Attribute:priority+' => '',
    'Class:Problem/Attribute:priority/Value:1' => 'Kritická',
    'Class:Problem/Attribute:priority/Value:1+' => '',
    'Class:Problem/Attribute:priority/Value:2' => 'Vysoká',
    'Class:Problem/Attribute:priority/Value:2+' => '',
    'Class:Problem/Attribute:priority/Value:3' => 'Střední',
    'Class:Problem/Attribute:priority/Value:3+' => '',
    'Class:Problem/Attribute:priority/Value:4' => 'Nízká',
    'Class:Problem/Attribute:priority/Value:4+' => '',
    'Class:Problem/Attribute:related_change_id' => 'Související změna',
    'Class:Problem/Attribute:related_change_id+' => '',
    'Class:Problem/Attribute:related_change_ref' => 'ID Související změny',
    'Class:Problem/Attribute:related_change_ref+' => '',
    'Class:Problem/Attribute:assignment_date' => 'Datum přidělení',
    'Class:Problem/Attribute:assignment_date+' => '',
    'Class:Problem/Attribute:resolution_date' => 'Datum vyřešení',
    'Class:Problem/Attribute:resolution_date+' => '',
    'Class:Problem/Attribute:knownerrors_list' => 'Známé chyby',
    'Class:Problem/Attribute:knownerrors_list+' => 'Všechny známé chyby spojené s tímto problémem',
    'Class:Problem/Attribute:related_request_list' => 'Související požadavky',
    'Class:Problem/Attribute:related_request_list+' => 'Všechny požadavky související s tímto problémem',
    'Class:Problem/Attribute:related_incident_list' => 'Související incidenty',
    'Class:Problem/Attribute:related_incident_list+' => 'Všechny incidenty související s tímto problémem',
    'Class:Problem/Stimulus:ev_assign' => 'Přidělit',
    'Class:Problem/Stimulus:ev_assign+' => '',
    'Class:Problem/Stimulus:ev_reassign' => 'Přidělit znovu',
    'Class:Problem/Stimulus:ev_reassign+' => '',
    'Class:Problem/Stimulus:ev_resolve' => 'Vyřešit',
    'Class:Problem/Stimulus:ev_resolve+' => '',
    'Class:Problem/Stimulus:ev_close' => 'Uzavřít',
    'Class:Problem/Stimulus:ev_close+' => '',
));

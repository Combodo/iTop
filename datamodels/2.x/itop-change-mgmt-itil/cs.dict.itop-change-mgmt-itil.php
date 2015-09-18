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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Menu:ChangeManagement' => 'Řízení změn',
    'Menu:Change:Overview' => 'Přehled',
    'Menu:Change:Overview+' => '',
    'Menu:NewChange' => 'Nová změna',
    'Menu:NewChange+' => 'Vytvořit nový změnový tiket',
    'Menu:SearchChanges' => 'Hledat změny',
    'Menu:SearchChanges+' => 'Hledat změnové tikety',
    'Menu:Change:Shortcuts' => 'Odkazy',
    'Menu:Change:Shortcuts+' => '',
    'Menu:WaitingAcceptance' => 'Změny čekající na přijetí',
    'Menu:WaitingAcceptance+' => '',
    'Menu:WaitingApproval' => 'Změny čekající na schválení',
    'Menu:WaitingApproval+' => '',
    'Menu:Changes' => 'Otevřené změny',
    'Menu:Changes+' => 'Všechny otevřené změny',
    'Menu:MyChanges' => 'Změny přidělené mně',
    'Menu:MyChanges+' => 'Změny přidělené mně (jako řešiteli)',
    'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Změny v posledních 7 dnech podle kategorie',
    'UI-ChangeManagementOverview-Last-7-days' => 'Počet změn za posledních 7 dní',
    'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Změny v posledních 7 dnech podle oblasti',
    'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Změny v posledních 7 dnech podle stavu',
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

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Change' => 'Změna',
    'Class:Change+' => '',
    'Class:Change/Attribute:status' => 'Stav',
    'Class:Change/Attribute:status+' => '',
    'Class:Change/Attribute:status/Value:new' => 'Nová',
    'Class:Change/Attribute:status/Value:new+' => '',
    'Class:Change/Attribute:status/Value:validated' => 'Potvrzena',
    'Class:Change/Attribute:status/Value:validated+' => '',
    'Class:Change/Attribute:status/Value:rejected' => 'Zamítnuta',
    'Class:Change/Attribute:status/Value:rejected+' => '',
    'Class:Change/Attribute:status/Value:assigned' => 'Přidělená',
    'Class:Change/Attribute:status/Value:assigned+' => '',
    'Class:Change/Attribute:status/Value:plannedscheduled' => 'Naplánovaná',
    'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
    'Class:Change/Attribute:status/Value:approved' => 'Schválena',
    'Class:Change/Attribute:status/Value:approved+' => '',
    'Class:Change/Attribute:status/Value:notapproved' => 'Neschválena',
    'Class:Change/Attribute:status/Value:notapproved+' => '',
    'Class:Change/Attribute:status/Value:implemented' => 'Implementována',
    'Class:Change/Attribute:status/Value:implemented+' => '',
    'Class:Change/Attribute:status/Value:monitored' => 'Monitorována',
    'Class:Change/Attribute:status/Value:monitored+' => '',
    'Class:Change/Attribute:status/Value:closed' => 'Uzavřena',
    'Class:Change/Attribute:status/Value:closed+' => '',
    'Class:Change/Attribute:reason' => 'Důvod zamítnutí',
    'Class:Change/Attribute:reason+' => '',
    'Class:Change/Attribute:requestor_id' => 'Žadatel',
    'Class:Change/Attribute:requestor_id+' => '',
    'Class:Change/Attribute:requestor_email' => 'Email žadatele',
    'Class:Change/Attribute:requestor_email+' => '',
    'Class:Change/Attribute:creation_date' => 'Datum vytvoření',
    'Class:Change/Attribute:creation_date+' => '',
    'Class:Change/Attribute:impact' => 'Dopad',
    'Class:Change/Attribute:impact+' => '',
    'Class:Change/Attribute:supervisor_group_id' => 'Kontrolní tým',
    'Class:Change/Attribute:supervisor_group_id+' => '',
    'Class:Change/Attribute:supervisor_group_name' => 'Název kontrolního týmu',
    'Class:Change/Attribute:supervisor_group_name+' => '',
    'Class:Change/Attribute:supervisor_id' => 'Kontrolor',
    'Class:Change/Attribute:supervisor_id+' => '',
    'Class:Change/Attribute:supervisor_email' => 'Email kontrolora',
    'Class:Change/Attribute:supervisor_email+' => '',
    'Class:Change/Attribute:manager_group_id' => 'Vedoucí tým',
    'Class:Change/Attribute:manager_group_id+' => '',
    'Class:Change/Attribute:manager_group_name' => 'Název vedoucího týmu',
    'Class:Change/Attribute:manager_group_name+' => '',
    'Class:Change/Attribute:manager_id' => 'Vedoucí',
    'Class:Change/Attribute:manager_id+' => '',
    'Class:Change/Attribute:manager_email' => 'Email Vedoucího',
    'Class:Change/Attribute:manager_email+' => '',
    'Class:Change/Attribute:outage' => 'Výpadek',
    'Class:Change/Attribute:outage+' => '',
    'Class:Change/Attribute:outage/Value:no' => 'Ne',
    'Class:Change/Attribute:outage/Value:no+' => '',
    'Class:Change/Attribute:outage/Value:yes' => 'Ano',
    'Class:Change/Attribute:outage/Value:yes+' => '',
    'Class:Change/Attribute:fallback' => 'Nouzový plán',
    'Class:Change/Attribute:fallback+' => '',
    'Class:Change/Attribute:parent_id' => 'Nadřazená změna',
    'Class:Change/Attribute:parent_id+' => '',
    'Class:Change/Attribute:parent_name' => 'ID nadřazené změny',
    'Class:Change/Attribute:parent_name+' => '',
    'Class:Change/Attribute:related_request_list' => 'Související požadavky',
    'Class:Change/Attribute:related_request_list+' => 'Všechny uživatelské požadavky provázané s touto změnou',
    'Class:Change/Attribute:related_problems_list' => 'Související problémy',
    'Class:Change/Attribute:related_problems_list+' => 'Všechny problémy provázané s touto změnou',
    'Class:Change/Attribute:related_incident_list' => 'Související incidenty',
    'Class:Change/Attribute:related_incident_list+' => 'Všechny incidenty provázané s touto změnou',
    'Class:Change/Attribute:child_changes_list' => 'Podřízené změny',
    'Class:Change/Attribute:child_changes_list+' => 'Všechny podřízené změny provázané s touto změnou',
    'Class:Change/Attribute:parent_id_friendlyname' => 'Popisný název nadřazené změny',
    'Class:Change/Attribute:parent_id_friendlyname+' => '',
    'Class:Change/Attribute:parent_id_finalclass_recall' => 'Typ změny',
    'Class:Change/Attribute:parent_id_finalclass_recall+' => '',
    'Class:Change/Stimulus:ev_validate' => 'Potvrdit',
    'Class:Change/Stimulus:ev_validate+' => '',
    'Class:Change/Stimulus:ev_reject' => 'Zamítnout',
    'Class:Change/Stimulus:ev_reject+' => '',
    'Class:Change/Stimulus:ev_assign' => 'Přidělit',
    'Class:Change/Stimulus:ev_assign+' => '',
    'Class:Change/Stimulus:ev_reopen' => 'Znovu otevřít',
    'Class:Change/Stimulus:ev_reopen+' => '',
    'Class:Change/Stimulus:ev_plan' => 'Naplánovat',
    'Class:Change/Stimulus:ev_plan+' => '',
    'Class:Change/Stimulus:ev_approve' => 'Schválit',
    'Class:Change/Stimulus:ev_approve+' => '',
    'Class:Change/Stimulus:ev_replan' => 'Přeplánovat',
    'Class:Change/Stimulus:ev_replan+' => '',
    'Class:Change/Stimulus:ev_notapprove' => 'Neschválit',
    'Class:Change/Stimulus:ev_notapprove+' => '',
    'Class:Change/Stimulus:ev_implement' => 'Implementovat',
    'Class:Change/Stimulus:ev_implement+' => '',
    'Class:Change/Stimulus:ev_monitor' => 'Monitorovat',
    'Class:Change/Stimulus:ev_monitor+' => '',
    'Class:Change/Stimulus:ev_finish' => 'Ukončit',
    'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:RoutineChange' => 'Standardní změna',
    'Class:RoutineChange+' => '',
    'Class:RoutineChange/Stimulus:ev_validate' => 'Potvrdit',
    'Class:RoutineChange/Stimulus:ev_validate+' => '',
    'Class:RoutineChange/Stimulus:ev_reject' => 'Zamítnout',
    'Class:RoutineChange/Stimulus:ev_reject+' => '',
    'Class:RoutineChange/Stimulus:ev_assign' => 'Přidělit',
    'Class:RoutineChange/Stimulus:ev_assign+' => '',
    'Class:RoutineChange/Stimulus:ev_reopen' => 'Znovu otevřít',
    'Class:RoutineChange/Stimulus:ev_reopen+' => '',
    'Class:RoutineChange/Stimulus:ev_plan' => 'Naplánovat',
    'Class:RoutineChange/Stimulus:ev_plan+' => '',
    'Class:RoutineChange/Stimulus:ev_approve' => 'Schválit',
    'Class:RoutineChange/Stimulus:ev_approve+' => '',
    'Class:RoutineChange/Stimulus:ev_replan' => 'Přeplánovat',
    'Class:RoutineChange/Stimulus:ev_replan+' => '',
    'Class:RoutineChange/Stimulus:ev_notapprove' => 'Neschválit',
    'Class:RoutineChange/Stimulus:ev_notapprove+' => '',
    'Class:RoutineChange/Stimulus:ev_implement' => 'Implementovat',
    'Class:RoutineChange/Stimulus:ev_implement+' => '',
    'Class:RoutineChange/Stimulus:ev_monitor' => 'Monitorovat',
    'Class:RoutineChange/Stimulus:ev_monitor+' => '',
    'Class:RoutineChange/Stimulus:ev_finish' => 'Ukončit',
    'Class:RoutineChange/Stimulus:ev_finish+' => '',
));

//
// Class: ApprovedChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:ApprovedChange' => 'Schválená změna',
    'Class:ApprovedChange+' => '',
    'Class:ApprovedChange/Attribute:approval_date' => 'Datum schválení',
    'Class:ApprovedChange/Attribute:approval_date+' => '',
    'Class:ApprovedChange/Attribute:approval_comment' => 'Komentář ke schválení',
    'Class:ApprovedChange/Attribute:approval_comment+' => '',
    'Class:ApprovedChange/Stimulus:ev_validate' => 'Potvrdit',
    'Class:ApprovedChange/Stimulus:ev_validate+' => '',
    'Class:ApprovedChange/Stimulus:ev_reject' => 'Zamítnout',
    'Class:ApprovedChange/Stimulus:ev_reject+' => '',
    'Class:ApprovedChange/Stimulus:ev_assign' => 'Přidělit',
    'Class:ApprovedChange/Stimulus:ev_assign+' => '',
    'Class:ApprovedChange/Stimulus:ev_reopen' => 'Znovu otevřít',
    'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
    'Class:ApprovedChange/Stimulus:ev_plan' => 'Naplánovat',
    'Class:ApprovedChange/Stimulus:ev_plan+' => '',
    'Class:ApprovedChange/Stimulus:ev_approve' => 'Schválit',
    'Class:ApprovedChange/Stimulus:ev_approve+' => '',
    'Class:ApprovedChange/Stimulus:ev_replan' => 'Přeplánovat',
    'Class:ApprovedChange/Stimulus:ev_replan+' => '',
    'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Neschválit',
    'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
    'Class:ApprovedChange/Stimulus:ev_implement' => 'Implementovat',
    'Class:ApprovedChange/Stimulus:ev_implement+' => '',
    'Class:ApprovedChange/Stimulus:ev_monitor' => 'Monitorovat',
    'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
    'Class:ApprovedChange/Stimulus:ev_finish' => 'Ukončit',
    'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));

//
// Class: NormalChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:NormalChange' => 'Normální změna',
    'Class:NormalChange+' => '',
    'Class:NormalChange/Attribute:acceptance_date' => 'Datum přijetí',
    'Class:NormalChange/Attribute:acceptance_date+' => '',
    'Class:NormalChange/Attribute:acceptance_comment' => 'Komentář k přijetí',
    'Class:NormalChange/Attribute:acceptance_comment+' => '',
    'Class:NormalChange/Stimulus:ev_validate' => 'Potvrdit',
    'Class:NormalChange/Stimulus:ev_validate+' => '',
    'Class:NormalChange/Stimulus:ev_reject' => 'Zamítnout',
    'Class:NormalChange/Stimulus:ev_reject+' => '',
    'Class:NormalChange/Stimulus:ev_assign' => 'Přidělit',
    'Class:NormalChange/Stimulus:ev_assign+' => '',
    'Class:NormalChange/Stimulus:ev_reopen' => 'Znovu otevřít',
    'Class:NormalChange/Stimulus:ev_reopen+' => '',
    'Class:NormalChange/Stimulus:ev_plan' => 'Naplánovat',
    'Class:NormalChange/Stimulus:ev_plan+' => '',
    'Class:NormalChange/Stimulus:ev_approve' => 'Schválit',
    'Class:NormalChange/Stimulus:ev_approve+' => '',
    'Class:NormalChange/Stimulus:ev_replan' => 'Přeplánovat',
    'Class:NormalChange/Stimulus:ev_replan+' => '',
    'Class:NormalChange/Stimulus:ev_notapprove' => 'Neschválit',
    'Class:NormalChange/Stimulus:ev_notapprove+' => '',
    'Class:NormalChange/Stimulus:ev_implement' => 'Implementovat',
    'Class:NormalChange/Stimulus:ev_implement+' => '',
    'Class:NormalChange/Stimulus:ev_monitor' => 'Monitorovat',
    'Class:NormalChange/Stimulus:ev_monitor+' => '',
    'Class:NormalChange/Stimulus:ev_finish' => 'Ukončit',
    'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:EmergencyChange' => 'Naléhavá změna',
    'Class:EmergencyChange+' => '',
    'Class:EmergencyChange/Stimulus:ev_validate' => 'Potvrdit',
    'Class:EmergencyChange/Stimulus:ev_validate+' => '',
    'Class:EmergencyChange/Stimulus:ev_reject' => 'Zamítnout',
    'Class:EmergencyChange/Stimulus:ev_reject+' => '',
    'Class:EmergencyChange/Stimulus:ev_assign' => 'Přidělit',
    'Class:EmergencyChange/Stimulus:ev_assign+' => '',
    'Class:EmergencyChange/Stimulus:ev_reopen' => 'Znovu otevřít',
    'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
    'Class:EmergencyChange/Stimulus:ev_plan' => 'Naplánovat',
    'Class:EmergencyChange/Stimulus:ev_plan+' => '',
    'Class:EmergencyChange/Stimulus:ev_approve' => 'Schválit',
    'Class:EmergencyChange/Stimulus:ev_approve+' => '',
    'Class:EmergencyChange/Stimulus:ev_replan' => 'Přeplánovat',
    'Class:EmergencyChange/Stimulus:ev_replan+' => '',
    'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Neschválit',
    'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
    'Class:EmergencyChange/Stimulus:ev_implement' => 'Implementovat',
    'Class:EmergencyChange/Stimulus:ev_implement+' => '',
    'Class:EmergencyChange/Stimulus:ev_monitor' => 'Monitorovat',
    'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
    'Class:EmergencyChange/Stimulus:ev_finish' => 'Ukončit',
    'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

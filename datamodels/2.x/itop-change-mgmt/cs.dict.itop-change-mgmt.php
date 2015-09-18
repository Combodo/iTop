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
    'Tickets:Related:OpenChanges' => 'Otevřené změny',
    'Tickets:Related:RecentChanges' => 'Nedávné změny (72h)',
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
    'Class:Change/Attribute:status/Value:rejected' => 'Zamítnuta',
    'Class:Change/Attribute:status/Value:rejected+' => '',
    'Class:Change/Attribute:status/Value:assigned' => 'Přidělená',
    'Class:Change/Attribute:status/Value:assigned+' => '',
    'Class:Change/Attribute:status/Value:planned' => 'Naplánovaná',
    'Class:Change/Attribute:status/Value:planned+' => '',
    'Class:Change/Attribute:status/Value:approved' => 'Schválena',
    'Class:Change/Attribute:status/Value:approved+' => '',
    'Class:Change/Attribute:status/Value:closed' => 'Uzavřena',
    'Class:Change/Attribute:status/Value:closed+' => '',
    'Class:Change/Attribute:reject_reason' => 'Důvod zamítnutí',
    'Class:Change/Attribute:reject_reason+' => '',
    'Class:Change/Attribute:creation_date' => 'Datum vytvoření',
    'Class:Change/Attribute:creation_date+' => '',
    'Class:Change/Attribute:fallback_plan' => 'Nouzový plán',
    'Class:Change/Attribute:fallback_plan+' => '',
    'Class:Change/Attribute:approval_date' => 'Datum schválení',
    'Class:Change/Attribute:approval_date+' => '',
    'Class:Change/Attribute:category' => 'Kategorie',
    'Class:Change/Attribute:category+' => '',
    'Class:Change/Attribute:category/Value:application' => 'Aplikace',
    'Class:Change/Attribute:category/Value:application+' => '',
    'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
    'Class:Change/Attribute:category/Value:hardware+' => '',
    'Class:Change/Attribute:category/Value:network' => 'Síť',
    'Class:Change/Attribute:category/Value:network+' => '',
    'Class:Change/Attribute:category/Value:other' => 'Jiná',
    'Class:Change/Attribute:category/Value:other+' => '',
    'Class:Change/Attribute:category/Value:software' => 'Software',
    'Class:Change/Attribute:category/Value:software+' => '',
    'Class:Change/Attribute:category/Value:system' => 'Systém',
    'Class:Change/Attribute:category/Value:system+' => '',
    'Class:Change/Attribute:changemanager_id' => 'Manažer změny',
    'Class:Change/Attribute:changemanager_id+' => '',
    'Class:Change/Attribute:changemanager_email' => 'Email manažera změny',
    'Class:Change/Attribute:changemanager_email+' => '',
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
    'Class:Change/Stimulus:ev_finish' => 'Ukončit',
    'Class:Change/Stimulus:ev_finish+' => '',
    'Class:Change/Attribute:outage' => 'Výpadek',
    'Class:Change/Attribute:outage+' => '',
    'Class:Change/Attribute:outage/Value:no' => 'Ne',
    'Class:Change/Attribute:outage/Value:no+' => '',
    'Class:Change/Attribute:outage/Value:yes' => 'Ano',
    'Class:Change/Attribute:outage/Value:yes+' => '',
));

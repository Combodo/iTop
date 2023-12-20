<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Menu:ChangeManagement' => 'Változáskezelés',
    'Menu:Change:Overview' => 'Áttekintő',
    'Menu:Change:Overview+' => 'Áttekintő oldal',
    'Menu:NewChange' => 'Új változás',
    'Menu:NewChange+' => 'Új változásjegy létrehozása',
    'Menu:SearchChanges' => 'Változás keresés',
    'Menu:SearchChanges+' => 'Változásjegy keresés',
    'Menu:Change:Shortcuts' => 'Gyorsgombok',
    'Menu:Change:Shortcuts+' => 'Gyorselérés gombok',
    'Menu:WaitingAcceptance' => 'Elfogadásra váró változások',
    'Menu:WaitingAcceptance+' => 'Elfogadásra váró változások',
    'Menu:WaitingApproval' => 'Jóváhagyásra váró változások',
    'Menu:WaitingApproval+' => 'Jóváhagyásra váró változások',
    'Menu:Changes' => 'Nyitott változási kérelmek',
    'Menu:Changes+' => 'Nyitott változási kérelmek összesítése',
    'Menu:MyChanges' => 'Hozzám rendelt változások',
    'Menu:MyChanges+' => 'Ügyintézőként hozzám rendelt változások',
    'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Változások kategóriánként az elmúlt 7 napban',
    'UI-ChangeManagementOverview-Last-7-days' => 'A változások száma az elmúlt 7 napban',
    'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Változások tartományonként az elmúlt 7 napban',
    'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Változások állapotuk szerint az elmúlt 7 napban',
    'Tickets:Related:OpenChanges' => 'Nyitott változások',
    'Tickets:Related:RecentChanges' => 'Legutóbbi változások (72h)',
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

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:Change' => 'Változás',
    'Class:Change+' => '~~',
    'Class:Change/Attribute:status' => 'Állapot',
    'Class:Change/Attribute:status+' => '~~',
    'Class:Change/Attribute:status/Value:new' => 'Új',
    'Class:Change/Attribute:status/Value:new+' => '~~',
    'Class:Change/Attribute:status/Value:assigned' => 'Hozzárendelt',
    'Class:Change/Attribute:status/Value:assigned+' => '~~',
    'Class:Change/Attribute:status/Value:planned' => 'Tervezett',
    'Class:Change/Attribute:status/Value:planned+' => '~~',
    'Class:Change/Attribute:status/Value:rejected' => 'Elutasított',
    'Class:Change/Attribute:status/Value:rejected+' => '~~',
    'Class:Change/Attribute:status/Value:approved' => 'Jóváhagyott',
    'Class:Change/Attribute:status/Value:approved+' => '~~',
    'Class:Change/Attribute:status/Value:closed' => 'Lezárt',
    'Class:Change/Attribute:status/Value:closed+' => '~~',
    'Class:Change/Attribute:category' => 'Kategória',
    'Class:Change/Attribute:category+' => '~~',
    'Class:Change/Attribute:category/Value:application' => 'Alkalmazás',
    'Class:Change/Attribute:category/Value:application+' => '',
    'Class:Change/Attribute:category/Value:hardware' => 'Hardver',
    'Class:Change/Attribute:category/Value:hardware+' => '',
    'Class:Change/Attribute:category/Value:network' => 'Hálózat',
    'Class:Change/Attribute:category/Value:network+' => '',
    'Class:Change/Attribute:category/Value:other' => 'Egyéb',
    'Class:Change/Attribute:category/Value:other+' => '',
    'Class:Change/Attribute:category/Value:software' => 'Szoftver',
    'Class:Change/Attribute:category/Value:software+' => '',
    'Class:Change/Attribute:category/Value:system' => 'Rendszer',
    'Class:Change/Attribute:category/Value:system+' => '',
    'Class:Change/Attribute:reject_reason' => 'Elutasítás oka',
    'Class:Change/Attribute:reject_reason+' => '~~',
    'Class:Change/Attribute:changemanager_id' => 'Változás menedzser',
    'Class:Change/Attribute:changemanager_id+' => '~~',
    'Class:Change/Attribute:changemanager_email' => 'Változás menedzser email címe',
    'Class:Change/Attribute:changemanager_email+' => '~~',
    'Class:Change/Attribute:parent_id' => 'Fölérendelt változás',
    'Class:Change/Attribute:parent_id+' => '~~',
    'Class:Change/Attribute:parent_name' => 'Referenciaszám',
    'Class:Change/Attribute:parent_name+' => '~~',
    'Class:Change/Attribute:creation_date' => 'Létrehozás dátuma',
    'Class:Change/Attribute:creation_date+' => '~~',
    'Class:Change/Attribute:approval_date' => 'Jóváhagyás dátuma',
    'Class:Change/Attribute:approval_date+' => '~~',
    'Class:Change/Attribute:fallback_plan' => 'Tartalék terv',
    'Class:Change/Attribute:fallback_plan+' => '~~',
    'Class:Change/Attribute:related_request_list' => 'Kapcsolódó kérelmek',
    'Class:Change/Attribute:related_request_list+' => 'Ehhez a változáshoz kapcsolódó felhasználói kérelmek',
    'Class:Change/Attribute:related_incident_list' => 'Kapcsolódó incidensek',
    'Class:Change/Attribute:related_incident_list+' => 'Ehhez a változáshoz kapcsolódó incidensek',
    'Class:Change/Attribute:related_problems_list' => 'Kapcsolódó problémák',
    'Class:Change/Attribute:related_problems_list+' => 'Ehhez a változáshoz kapcsolódó problémák',
    'Class:Change/Attribute:child_changes_list' => 'Kapcsolódó változások',
    'Class:Change/Attribute:child_changes_list+' => 'Ehhez a változáshoz kapcsolódó változások',
    'Class:Change/Attribute:parent_id_friendlyname' => 'Fölérendelt változás rövid név',
    'Class:Change/Attribute:parent_id_friendlyname+' => '~~',
    'Class:Change/Stimulus:ev_assign' => 'Hozzárendelés',
    'Class:Change/Stimulus:ev_assign+' => '~~',
    'Class:Change/Stimulus:ev_plan' => 'Tervezés',
    'Class:Change/Stimulus:ev_plan+' => '~~',
    'Class:Change/Stimulus:ev_reject' => 'Elutasítás',
    'Class:Change/Stimulus:ev_reject+' => '~~',
    'Class:Change/Stimulus:ev_reopen' => 'Újranyitás',
    'Class:Change/Stimulus:ev_reopen+' => '~~',
    'Class:Change/Stimulus:ev_approve' => 'Jóváhagyás',
    'Class:Change/Stimulus:ev_approve+' => '~~',
    'Class:Change/Stimulus:ev_finish' => 'Befejezés',
    'Class:Change/Stimulus:ev_finish+' => '~~',
    'Class:Change/Attribute:outage' => 'Üzemszünet',
    'Class:Change/Attribute:outage+' => '~~',
    'Class:Change/Attribute:outage/Value:no' => 'Nem',
    'Class:Change/Attribute:outage/Value:no+' => '~~',
    'Class:Change/Attribute:outage/Value:yes' => 'Igen',
    'Class:Change/Attribute:outage/Value:yes+' => '~~',
));

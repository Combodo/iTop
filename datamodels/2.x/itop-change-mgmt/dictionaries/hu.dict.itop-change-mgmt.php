<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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
	'Menu:ChangeManagement' => 'Változás menedzsment',
	'Menu:Change:Overview' => 'Áttekintő',
	'Menu:Change:Overview+' => '~~',
	'Menu:NewChange' => 'Új változás',
	'Menu:NewChange+' => 'Create a new change ticket~~',
	'Menu:SearchChanges' => 'Változás keresés',
	'Menu:SearchChanges+' => 'Search for change tickets~~',
	'Menu:Change:Shortcuts' => 'Gyorsmenü',
	'Menu:Change:Shortcuts+' => '~~',
	'Menu:WaitingAcceptance' => 'Elfogadásra váró változások',
	'Menu:WaitingAcceptance+' => '~~',
	'Menu:WaitingApproval' => 'Jóváhagyásra váró változások',
	'Menu:WaitingApproval+' => '~~',
	'Menu:Changes' => 'Nyitott változási igények',
	'Menu:Changes+' => 'All open changes~~',
	'Menu:MyChanges' => 'Hozzám rendelt változások',
	'Menu:MyChanges+' => 'Changes assigned to me (as Agent)~~',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes by category for the last 7 days~~',
	'UI-ChangeManagementOverview-Last-7-days' => 'Number of changes for the last 7 days~~',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes by domain for the last 7 days~~',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes by status for the last 7 days~~',
	'Tickets:Related:OpenChanges' => 'Open changes~~',
	'Tickets:Related:RecentChanges' => 'Recent changes (72h)~~',
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
	'Class:Change/Attribute:status' => 'Státusz',
	'Class:Change/Attribute:status+' => '~~',
	'Class:Change/Attribute:status/Value:new' => 'Új',
	'Class:Change/Attribute:status/Value:new+' => '~~',
	'Class:Change/Attribute:status/Value:assigned' => 'Hozzárendelt',
	'Class:Change/Attribute:status/Value:assigned+' => '~~',
	'Class:Change/Attribute:status/Value:planned' => 'Planned~~',
	'Class:Change/Attribute:status/Value:planned+' => '~~',
	'Class:Change/Attribute:status/Value:rejected' => 'Visszautasított',
	'Class:Change/Attribute:status/Value:rejected+' => '~~',
	'Class:Change/Attribute:status/Value:approved' => 'Jóváhagyott',
	'Class:Change/Attribute:status/Value:approved+' => '~~',
	'Class:Change/Attribute:status/Value:closed' => 'Lezárt',
	'Class:Change/Attribute:status/Value:closed+' => '~~',
	'Class:Change/Attribute:category' => 'Category~~',
	'Class:Change/Attribute:category+' => '~~',
	'Class:Change/Attribute:category/Value:application' => 'application~~',
	'Class:Change/Attribute:category/Value:application+' => 'application~~',
	'Class:Change/Attribute:category/Value:hardware' => 'hardware~~',
	'Class:Change/Attribute:category/Value:hardware+' => 'hardware~~',
	'Class:Change/Attribute:category/Value:network' => 'network~~',
	'Class:Change/Attribute:category/Value:network+' => 'network~~',
	'Class:Change/Attribute:category/Value:other' => 'other~~',
	'Class:Change/Attribute:category/Value:other+' => 'other~~',
	'Class:Change/Attribute:category/Value:software' => 'software~~',
	'Class:Change/Attribute:category/Value:software+' => 'software~~',
	'Class:Change/Attribute:category/Value:system' => 'system~~',
	'Class:Change/Attribute:category/Value:system+' => 'system~~',
	'Class:Change/Attribute:reject_reason' => 'Reject reason~~',
	'Class:Change/Attribute:reject_reason+' => '~~',
	'Class:Change/Attribute:changemanager_id' => 'Change manager~~',
	'Class:Change/Attribute:changemanager_id+' => '~~',
	'Class:Change/Attribute:changemanager_email' => 'Change manager email~~',
	'Class:Change/Attribute:changemanager_email+' => '~~',
	'Class:Change/Attribute:parent_id' => 'Parent change~~',
	'Class:Change/Attribute:parent_id+' => '~~',
	'Class:Change/Attribute:parent_name' => 'Parent change ref~~',
	'Class:Change/Attribute:parent_name+' => '~~',
	'Class:Change/Attribute:creation_date' => 'Létrehozás dátuma',
	'Class:Change/Attribute:creation_date+' => '~~',
	'Class:Change/Attribute:approval_date' => 'Approval date~~',
	'Class:Change/Attribute:approval_date+' => '~~',
	'Class:Change/Attribute:fallback_plan' => 'Fallback plan~~',
	'Class:Change/Attribute:fallback_plan+' => '~~',
	'Class:Change/Attribute:related_request_list' => 'Related requests~~',
	'Class:Change/Attribute:related_request_list+' => 'All the user requests linked to this change~~',
	'Class:Change/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Change/Attribute:related_incident_list+' => 'All the incidents linked to this change~~',
	'Class:Change/Attribute:related_problems_list' => 'Related problems~~',
	'Class:Change/Attribute:related_problems_list+' => 'All the problems linked to this change~~',
	'Class:Change/Attribute:child_changes_list' => 'Child changes~~',
	'Class:Change/Attribute:child_changes_list+' => 'All the sub changes linked to this change~~',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Parent change friendly name~~',
	'Class:Change/Attribute:parent_id_friendlyname+' => '~~',
	'Class:Change/Stimulus:ev_assign' => 'Hozzárenedelés',
	'Class:Change/Stimulus:ev_assign+' => '~~',
	'Class:Change/Stimulus:ev_plan' => 'Tervezés',
	'Class:Change/Stimulus:ev_plan+' => '~~',
	'Class:Change/Stimulus:ev_reject' => 'Visszautasítás',
	'Class:Change/Stimulus:ev_reject+' => '~~',
	'Class:Change/Stimulus:ev_reopen' => 'Újranyitás',
	'Class:Change/Stimulus:ev_reopen+' => '~~',
	'Class:Change/Stimulus:ev_approve' => 'Jóváhagyás',
	'Class:Change/Stimulus:ev_approve+' => '~~',
	'Class:Change/Stimulus:ev_finish' => 'Befejezés',
	'Class:Change/Stimulus:ev_finish+' => '~~',
	'Class:Change/Attribute:outage' => 'Leállás',
	'Class:Change/Attribute:outage+' => '~~',
	'Class:Change/Attribute:outage/Value:no' => 'Nem',
	'Class:Change/Attribute:outage/Value:no+' => '~~',
	'Class:Change/Attribute:outage/Value:yes' => 'Igen',
	'Class:Change/Attribute:outage/Value:yes+' => '~~',
));

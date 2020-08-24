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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:ChangeManagement' => 'Değişiklik Yönetimi',
	'Menu:Change:Overview' => 'Özet',
	'Menu:Change:Overview+' => '~~',
	'Menu:NewChange' => 'Yeni değişiklik',
	'Menu:NewChange+' => 'Yeni değişiklik isteği yarat',
	'Menu:SearchChanges' => 'Değişiklik ara',
	'Menu:SearchChanges+' => 'Değişiklik isteği ara',
	'Menu:Change:Shortcuts' => 'Kısayollar',
	'Menu:Change:Shortcuts+' => '~~',
	'Menu:WaitingAcceptance' => 'Kabul bekleyen değişiklik talepleri',
	'Menu:WaitingAcceptance+' => '~~',
	'Menu:WaitingApproval' => 'Onay bekleyen değişiklik talepleri',
	'Menu:WaitingApproval+' => '~~',
	'Menu:Changes' => 'Açık değişiklikler',
	'Menu:Changes+' => 'All open changes~~',
	'Menu:MyChanges' => 'Bana atanan değişiklik istekleri',
	'Menu:MyChanges+' => 'Bana atanan değişiklik istekleri',
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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Change' => 'Değişiklik',
	'Class:Change+' => '~~',
	'Class:Change/Attribute:status' => 'Durumu',
	'Class:Change/Attribute:status+' => '~~',
	'Class:Change/Attribute:status/Value:new' => 'Yeni',
	'Class:Change/Attribute:status/Value:new+' => '~~',
	'Class:Change/Attribute:status/Value:assigned' => 'Atanmış',
	'Class:Change/Attribute:status/Value:assigned+' => '~~',
	'Class:Change/Attribute:status/Value:planned' => 'Planned~~',
	'Class:Change/Attribute:status/Value:planned+' => '~~',
	'Class:Change/Attribute:status/Value:rejected' => 'Reddedilen',
	'Class:Change/Attribute:status/Value:rejected+' => '~~',
	'Class:Change/Attribute:status/Value:approved' => 'Onaylanan',
	'Class:Change/Attribute:status/Value:approved+' => '~~',
	'Class:Change/Attribute:status/Value:closed' => 'Kapanan',
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
	'Class:Change/Attribute:creation_date' => 'Yaratıldı',
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
	'Class:Change/Stimulus:ev_assign' => 'Ata',
	'Class:Change/Stimulus:ev_assign+' => '~~',
	'Class:Change/Stimulus:ev_plan' => 'Planla',
	'Class:Change/Stimulus:ev_plan+' => '~~',
	'Class:Change/Stimulus:ev_reject' => 'Ret',
	'Class:Change/Stimulus:ev_reject+' => '~~',
	'Class:Change/Stimulus:ev_reopen' => 'Tekrar aç',
	'Class:Change/Stimulus:ev_reopen+' => '~~',
	'Class:Change/Stimulus:ev_approve' => 'Onayla',
	'Class:Change/Stimulus:ev_approve+' => '~~',
	'Class:Change/Stimulus:ev_finish' => 'Bitir',
	'Class:Change/Stimulus:ev_finish+' => '~~',
	'Class:Change/Attribute:outage' => 'Servis kesilmesi',
	'Class:Change/Attribute:outage+' => '~~',
	'Class:Change/Attribute:outage/Value:no' => 'Hayır',
	'Class:Change/Attribute:outage/Value:no+' => '~~',
	'Class:Change/Attribute:outage/Value:yes' => 'Evet',
	'Class:Change/Attribute:outage/Value:yes+' => '~~',
));

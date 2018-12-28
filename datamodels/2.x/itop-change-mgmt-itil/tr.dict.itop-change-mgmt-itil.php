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
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:ChangeManagement' => 'Değişiklik Yönetimi',
	'Menu:Change:Overview' => 'Özet',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Yeni değişiklik',
	'Menu:NewChange+' => 'Yeni değişiklik isteği yarat',
	'Menu:SearchChanges' => 'Değişiklik ara',
	'Menu:SearchChanges+' => 'Değişiklik isteği ara',
	'Menu:Change:Shortcuts' => 'Kısayollar',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Kabul bekleyen değişiklik talepleri',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Onay bekleyen değişiklik talepleri',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Açık değişiklikler',
	'Menu:Changes+' => '',
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
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Durumu',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Yeni',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Kontrol edilen',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Reddedilen',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Atanmış',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Planlanan',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Onaylanan',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Onaylanmayan',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => 'Uygulanan',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => 'İzlenen',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Kapanan',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Sebep',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => 'İstek sahibi',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => 'İstek sahibi',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:creation_date' => 'Yaratıldı',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:impact' => 'Etkisi',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:supervisor_group_id' => 'Supervizör ekip',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => 'Supervizör ekip',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => 'Supervizör',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => 'Supervizör',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'Yönetici ekibi',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'Yönetici ekibi',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'Yönetici',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'Yönetici',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => 'Servis kesilmesi',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Hayır',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Evet',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:fallback' => 'Geridönüş planı',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Attribute:parent_id' => 'Parent change~~',
	'Class:Change/Attribute:parent_id+' => '~~',
	'Class:Change/Attribute:parent_name' => 'Parent change Ref~~',
	'Class:Change/Attribute:parent_name+' => '~~',
	'Class:Change/Attribute:related_request_list' => 'Related requests~~',
	'Class:Change/Attribute:related_request_list+' => 'All the user requests linked to this change~~',
	'Class:Change/Attribute:related_problems_list' => 'Related problems~~',
	'Class:Change/Attribute:related_problems_list+' => 'All the problems linked to this change~~',
	'Class:Change/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Change/Attribute:related_incident_list+' => 'All the incidents linked to this change~~',
	'Class:Change/Attribute:child_changes_list' => 'Child changes~~',
	'Class:Change/Attribute:child_changes_list+' => 'All the sub changes linked to this change~~',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Parent friendly name~~',
	'Class:Change/Attribute:parent_id_friendlyname+' => '~~',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Change type~~',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => '~~',
	'Class:Change/Stimulus:ev_validate' => 'Doğrula',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Ret',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Ata',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Tekrar aç',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planla',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Onayla',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Tekrar planla',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => 'Ret',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => 'Uygula',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => 'İzle',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Bitir',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:RoutineChange' => 'Sıradan değişiklik',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Doğrulanan',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Reject~~',
	'Class:RoutineChange/Stimulus:ev_reject+' => '~~',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Atanan',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Tekrar açılan',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Planlanan',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Approve~~',
	'Class:RoutineChange/Stimulus:ev_approve+' => '~~',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Tekrar planlanan',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'Do Not Approve~~',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => '~~',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Uygula',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'İzle',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Bitir',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
));

//
// Class: ApprovedChange
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ApprovedChange' => 'Onaylanan değişiklik',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => 'Onay tarihi',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Onay yorumu',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Onaylı',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Reddedilen',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Ata',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Tekrar aç',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Planla',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Onayla',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Tekrar planla',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Onayı reddet',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Uygula',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'İzle',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Bitir',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));

//
// Class: NormalChange
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NormalChange' => 'Normal değişiklik',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => 'Kabul tarihi',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Kabul yorumu',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => 'Doğrula',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => 'Reddet',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => 'Ata',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Tekrar aç',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => 'Planla',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => 'Onayla',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => 'Tekrar planla',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Onayı reddet',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => 'Uygula',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => 'İzle',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => 'Bitir',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EmergencyChange' => 'Acil değişiklik',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Doğrula',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Reddet',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Ata',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Tekrar aç',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Planla',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Onayla',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Tekrar planla',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Onayı reddet',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Uygula',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'İzle',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Bitir',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

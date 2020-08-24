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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Menu:ChangeManagement' => 'Manažment zmien',
	'Menu:Change:Overview' => 'Prehľad',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nová zmena',
	'Menu:NewChange+' => '',
	'Menu:SearchChanges' => 'Vyhľadať zmeny',
	'Menu:SearchChanges+' => '',
	'Menu:Change:Shortcuts' => 'Skratky',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Zmeny očakávajúce prijatie',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Zmeny očakávajúce schválenie',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Otvorené zmeny',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Zmeny pridelené mne',
	'Menu:MyChanges+' => '',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Zmeny podľa kategórie za posledných 7 dní',
	'UI-ChangeManagementOverview-Last-7-days' => 'Počet zmien za posledných 7 dní',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Zmeny podľa domény za posledných 7 dní',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Zmeny podľa stavu za posledných 7 dní',
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

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Change' => 'Zmena',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Stav',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nová',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Validated~~',
	'Class:Change/Attribute:status/Value:validated+' => '~~',
	'Class:Change/Attribute:status/Value:rejected' => 'Zamietnutá',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Pridelená',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Planned and scheduled~~',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '~~',
	'Class:Change/Attribute:status/Value:approved' => 'Schválená',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Not approved~~',
	'Class:Change/Attribute:status/Value:notapproved+' => '~~',
	'Class:Change/Attribute:status/Value:implemented' => 'Implemented~~',
	'Class:Change/Attribute:status/Value:implemented+' => '~~',
	'Class:Change/Attribute:status/Value:monitored' => 'Monitored~~',
	'Class:Change/Attribute:status/Value:monitored+' => '~~',
	'Class:Change/Attribute:status/Value:closed' => 'Zatvorená',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Reject reason~~',
	'Class:Change/Attribute:reason+' => '~~',
	'Class:Change/Attribute:requestor_id' => 'Requestor~~',
	'Class:Change/Attribute:requestor_id+' => '~~',
	'Class:Change/Attribute:requestor_email' => 'Requestor email~~',
	'Class:Change/Attribute:requestor_email+' => '~~',
	'Class:Change/Attribute:creation_date' => 'Dátum vytvorenia',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:impact' => 'Impact~~',
	'Class:Change/Attribute:impact+' => '~~',
	'Class:Change/Attribute:supervisor_group_id' => 'Supervisor team~~',
	'Class:Change/Attribute:supervisor_group_id+' => '~~',
	'Class:Change/Attribute:supervisor_group_name' => 'Supervisor team name~~',
	'Class:Change/Attribute:supervisor_group_name+' => '~~',
	'Class:Change/Attribute:supervisor_id' => 'Supervisor~~',
	'Class:Change/Attribute:supervisor_id+' => '~~',
	'Class:Change/Attribute:supervisor_email' => 'Supervisor email~~',
	'Class:Change/Attribute:supervisor_email+' => '~~',
	'Class:Change/Attribute:manager_group_id' => 'Manager team~~',
	'Class:Change/Attribute:manager_group_id+' => '~~',
	'Class:Change/Attribute:manager_group_name' => 'Manager team name~~',
	'Class:Change/Attribute:manager_group_name+' => '~~',
	'Class:Change/Attribute:manager_id' => 'Manager~~',
	'Class:Change/Attribute:manager_id+' => '~~',
	'Class:Change/Attribute:manager_email' => 'Manager email~~',
	'Class:Change/Attribute:manager_email+' => '~~',
	'Class:Change/Attribute:outage' => 'Outage~~',
	'Class:Change/Attribute:outage+' => '~~',
	'Class:Change/Attribute:outage/Value:no' => 'No~~',
	'Class:Change/Attribute:outage/Value:no+' => '~~',
	'Class:Change/Attribute:outage/Value:yes' => 'Yes~~',
	'Class:Change/Attribute:outage/Value:yes+' => '~~',
	'Class:Change/Attribute:fallback' => 'Fallback plan~~',
	'Class:Change/Attribute:fallback+' => '~~',
	'Class:Change/Attribute:parent_id' => 'Nadradená zmena',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Referencia na rodičovskú zmenu',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_request_list' => 'Súvisiace požiadavky',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:related_problems_list' => 'Súvisiace problémy',
	'Class:Change/Attribute:related_problems_list+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Súvisiace incidenty',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Podriadené zmeny',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Priateľské meno rodičovskej zmeny',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Change type~~',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => '~~',
	'Class:Change/Stimulus:ev_validate' => 'Validate~~',
	'Class:Change/Stimulus:ev_validate+' => '~~',
	'Class:Change/Stimulus:ev_reject' => 'Zamietnúť',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Prideiť',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Znova otvoriť',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Naplánuj',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Schváliť',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Replan~~',
	'Class:Change/Stimulus:ev_replan+' => '~~',
	'Class:Change/Stimulus:ev_notapprove' => 'Reject~~',
	'Class:Change/Stimulus:ev_notapprove+' => '~~',
	'Class:Change/Stimulus:ev_implement' => 'Implement~~',
	'Class:Change/Stimulus:ev_implement+' => '~~',
	'Class:Change/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:Change/Stimulus:ev_monitor+' => '~~',
	'Class:Change/Stimulus:ev_finish' => 'Zatvoriť',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:RoutineChange' => 'Routine Change~~',
	'Class:RoutineChange+' => '~~',
	'Class:RoutineChange/Stimulus:ev_validate' => 'Validate~~',
	'Class:RoutineChange/Stimulus:ev_validate+' => '~~',
	'Class:RoutineChange/Stimulus:ev_reject' => 'Reject~~',
	'Class:RoutineChange/Stimulus:ev_reject+' => '~~',
	'Class:RoutineChange/Stimulus:ev_assign' => 'Assign~~',
	'Class:RoutineChange/Stimulus:ev_assign+' => '~~',
	'Class:RoutineChange/Stimulus:ev_reopen' => 'Reopen~~',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '~~',
	'Class:RoutineChange/Stimulus:ev_plan' => 'Plan~~',
	'Class:RoutineChange/Stimulus:ev_plan+' => '~~',
	'Class:RoutineChange/Stimulus:ev_approve' => 'Approve~~',
	'Class:RoutineChange/Stimulus:ev_approve+' => '~~',
	'Class:RoutineChange/Stimulus:ev_replan' => 'Replan~~',
	'Class:RoutineChange/Stimulus:ev_replan+' => '~~',
	'Class:RoutineChange/Stimulus:ev_notapprove' => 'Do Not Approve~~',
	'Class:RoutineChange/Stimulus:ev_notapprove+' => '~~',
	'Class:RoutineChange/Stimulus:ev_implement' => 'Implement~~',
	'Class:RoutineChange/Stimulus:ev_implement+' => '~~',
	'Class:RoutineChange/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '~~',
	'Class:RoutineChange/Stimulus:ev_finish' => 'Finish~~',
	'Class:RoutineChange/Stimulus:ev_finish+' => '~~',
));

//
// Class: ApprovedChange
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:ApprovedChange' => 'Approved Changes~~',
	'Class:ApprovedChange+' => '~~',
	'Class:ApprovedChange/Attribute:approval_date' => 'Approval Date~~',
	'Class:ApprovedChange/Attribute:approval_date+' => '~~',
	'Class:ApprovedChange/Attribute:approval_comment' => 'Approval comment~~',
	'Class:ApprovedChange/Attribute:approval_comment+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_validate' => 'Validate~~',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_reject' => 'Reject~~',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_assign' => 'Assign~~',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_reopen' => 'Reopen~~',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_plan' => 'Plan~~',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_approve' => 'Approve~~',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_replan' => 'Replan~~',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => 'Reject approval~~',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_implement' => 'Implement~~',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '~~',
	'Class:ApprovedChange/Stimulus:ev_finish' => 'Finish~~',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '~~',
));

//
// Class: NormalChange
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:NormalChange' => 'Normal Change~~',
	'Class:NormalChange+' => '~~',
	'Class:NormalChange/Attribute:acceptance_date' => 'Acceptance date~~',
	'Class:NormalChange/Attribute:acceptance_date+' => '~~',
	'Class:NormalChange/Attribute:acceptance_comment' => 'Acceptance comment~~',
	'Class:NormalChange/Attribute:acceptance_comment+' => '~~',
	'Class:NormalChange/Stimulus:ev_validate' => 'Validate~~',
	'Class:NormalChange/Stimulus:ev_validate+' => '~~',
	'Class:NormalChange/Stimulus:ev_reject' => 'Reject~~',
	'Class:NormalChange/Stimulus:ev_reject+' => '~~',
	'Class:NormalChange/Stimulus:ev_assign' => 'Assign~~',
	'Class:NormalChange/Stimulus:ev_assign+' => '~~',
	'Class:NormalChange/Stimulus:ev_reopen' => 'Reopen~~',
	'Class:NormalChange/Stimulus:ev_reopen+' => '~~',
	'Class:NormalChange/Stimulus:ev_plan' => 'Plan~~',
	'Class:NormalChange/Stimulus:ev_plan+' => '~~',
	'Class:NormalChange/Stimulus:ev_approve' => 'Approve~~',
	'Class:NormalChange/Stimulus:ev_approve+' => '~~',
	'Class:NormalChange/Stimulus:ev_replan' => 'Replan~~',
	'Class:NormalChange/Stimulus:ev_replan+' => '~~',
	'Class:NormalChange/Stimulus:ev_notapprove' => 'Reject approval~~',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '~~',
	'Class:NormalChange/Stimulus:ev_implement' => 'Implement~~',
	'Class:NormalChange/Stimulus:ev_implement+' => '~~',
	'Class:NormalChange/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:NormalChange/Stimulus:ev_monitor+' => '~~',
	'Class:NormalChange/Stimulus:ev_finish' => 'Finish~~',
	'Class:NormalChange/Stimulus:ev_finish+' => '~~',
));

//
// Class: EmergencyChange
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:EmergencyChange' => 'Emergency Change~~',
	'Class:EmergencyChange+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_validate' => 'Validate~~',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_reject' => 'Reject~~',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_assign' => 'Assign~~',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_reopen' => 'Reopen~~',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_plan' => 'Plan~~',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_approve' => 'Approve~~',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_replan' => 'Replan~~',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => 'Reject approval~~',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_implement' => 'Implement~~',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '~~',
	'Class:EmergencyChange/Stimulus:ev_finish' => 'Finish~~',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '~~',
));

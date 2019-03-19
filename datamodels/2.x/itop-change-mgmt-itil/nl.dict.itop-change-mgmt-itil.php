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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ChangeManagement' => 'Change management',
	'Menu:Change:Overview' => 'Overzicht',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nieuwe change',
	'Menu:NewChange+' => 'Maak een nieuwe change ticket aan',
	'Menu:SearchChanges' => 'Zoek voor changes',
	'Menu:SearchChanges+' => 'Zoek voor change tickets',
	'Menu:Change:Shortcuts' => 'Snelkoppelingen',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes die nog acceptatie vereisen',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes die nog goedkeuring vereisen',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Open changes',
	'Menu:Changes+' => 'Alle open changes',
	'Menu:MyChanges' => 'Changes toegewezen aan mij',
	'Menu:MyChanges+' => 'Changes toegewezen door mij (als Agent)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes per categorie van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-Last-7-days' => 'Aantal changes van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes per domein van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes per status van de afgelopen 7 dagen',
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Change' => 'Change',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nieuw',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => 'Validated~~',
	'Class:Change/Attribute:status/Value:validated+' => '~~',
	'Class:Change/Attribute:status/Value:rejected' => 'Rejected',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Toegewezen',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => 'Planned and scheduled~~',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '~~',
	'Class:Change/Attribute:status/Value:approved' => 'Goedgekeurd',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => 'Not approved~~',
	'Class:Change/Attribute:status/Value:notapproved+' => '~~',
	'Class:Change/Attribute:status/Value:implemented' => 'Implemented~~',
	'Class:Change/Attribute:status/Value:implemented+' => '~~',
	'Class:Change/Attribute:status/Value:monitored' => 'Monitored~~',
	'Class:Change/Attribute:status/Value:monitored+' => '~~',
	'Class:Change/Attribute:status/Value:closed' => 'Gesloten',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => 'Reject reason~~',
	'Class:Change/Attribute:reason+' => '~~',
	'Class:Change/Attribute:requestor_id' => 'Requestor~~',
	'Class:Change/Attribute:requestor_id+' => '~~',
	'Class:Change/Attribute:requestor_email' => 'Requestor email~~',
	'Class:Change/Attribute:requestor_email+' => '~~',
	'Class:Change/Attribute:creation_date' => 'Creatie datum',
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
	'Class:Change/Attribute:outage' => 'Storing',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Nee',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Yes',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:fallback' => 'Fallback plan~~',
	'Class:Change/Attribute:fallback+' => '~~',
	'Class:Change/Attribute:parent_id' => 'Hoofd change',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Hoofd change ref',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_request_list' => 'Gerelateerde verzoeken',
	'Class:Change/Attribute:related_request_list+' => 'Alle gebruikersverzoeken gelinkt aan deze change',
	'Class:Change/Attribute:related_problems_list' => 'Gerelateerde problemen',
	'Class:Change/Attribute:related_problems_list+' => 'Alle problemen gelinkt aan deze change',
	'Class:Change/Attribute:related_incident_list' => 'Gerelateerde incidenten',
	'Class:Change/Attribute:related_incident_list+' => 'Alle incidenten die gelinkt zijn aan deze change',
	'Class:Change/Attribute:child_changes_list' => 'Sub changes',
	'Class:Change/Attribute:child_changes_list+' => 'Alle sub changes gelinkt aan deze change',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Hoofd change friendly name',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Attribute:parent_id_finalclass_recall' => 'Change type~~',
	'Class:Change/Attribute:parent_id_finalclass_recall+' => '~~',
	'Class:Change/Stimulus:ev_validate' => 'Validate~~',
	'Class:Change/Stimulus:ev_validate+' => '~~',
	'Class:Change/Stimulus:ev_reject' => 'Wijs af',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Wijs toe',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Heropen',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Plan',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Keur goed',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => 'Replan~~',
	'Class:Change/Stimulus:ev_replan+' => '~~',
	'Class:Change/Stimulus:ev_notapprove' => 'Reject~~',
	'Class:Change/Stimulus:ev_notapprove+' => '~~',
	'Class:Change/Stimulus:ev_implement' => 'Implement~~',
	'Class:Change/Stimulus:ev_implement+' => '~~',
	'Class:Change/Stimulus:ev_monitor' => 'Monitor~~',
	'Class:Change/Stimulus:ev_monitor+' => '~~',
	'Class:Change/Stimulus:ev_finish' => 'Sluit',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
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

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
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

?>

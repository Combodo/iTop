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
 * @author      Hirofumi Kosaka <kosaka@rworks.jp>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:ChangeManagement' => '変更管理', # 'Change management',
	'Menu:Change:Overview' => '概要', # 'Overview',
	'Menu:Change:Overview+' => '', # '',
	'Menu:NewChange' => '新規変更', # 'New Change',
	'Menu:NewChange+' => '新規変更のチケット作成', # 'Create a new Change ticket',
	'Menu:SearchChanges' => '変更検索', # 'Search for Changes',
	'Menu:SearchChanges+' => '変更チケット検索', # 'Search for Change tickets',
	'Menu:Change:Shortcuts' => 'ショートカット', # 'Shortcuts',
	'Menu:Change:Shortcuts+' => '', # '',
	'Menu:WaitingAcceptance' => '受理待ちの変更', # 'Changes awaiting acceptance',
	'Menu:WaitingAcceptance+' => '', # '',
	'Menu:WaitingApproval' => '承認待ちの変更', # 'Changes awaiting approval',
	'Menu:WaitingApproval+' => '', # '',
	'Menu:Changes' => '担当のいない変更', # 'Opened changes',
	'Menu:Changes+' => '', # '',
	'Menu:MyChanges' => '担当している変更', # 'Changes assigned to me',
	'Menu:MyChanges+' => '担当している変更(エージェント)', # 'Changes assigned to me (as Agent)',
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

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Change' => '変更', # 'Change',
	'Class:Change+' => '', # '',
	'Class:Change/Attribute:start_date' => '開始計画日', # 'Planned startup',
	'Class:Change/Attribute:start_date+' => '', # '',
	'Class:Change/Attribute:status' => 'ステータス', # 'Status',
	'Class:Change/Attribute:status+' => '', # '',
	'Class:Change/Attribute:status/Value:new' => '新規', # 'New',
	'Class:Change/Attribute:status/Value:new+' => '', # '',
	'Class:Change/Attribute:status/Value:validated' => '受付済', # 'Validated',
	'Class:Change/Attribute:status/Value:validated+' => '', # '',
	'Class:Change/Attribute:status/Value:rejected' => '却下済', # 'Rejected',
	'Class:Change/Attribute:status/Value:rejected+' => '', # '',
	'Class:Change/Attribute:status/Value:assigned' => '割当済', # 'Assigned',
	'Class:Change/Attribute:status/Value:assigned+' => '', # '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => '計画・予定された', # 'Planned and scheduled',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '', # '',
	'Class:Change/Attribute:status/Value:approved' => '承認済', # 'Approved',
	'Class:Change/Attribute:status/Value:approved+' => '', # '',
	'Class:Change/Attribute:status/Value:notapproved' => '未承認', # 'Not approved',
	'Class:Change/Attribute:status/Value:notapproved+' => '', # '',
	'Class:Change/Attribute:status/Value:implemented' => '実施済み', # 'Implemented',
	'Class:Change/Attribute:status/Value:implemented+' => '', # '',
	'Class:Change/Attribute:status/Value:monitored' => '経過観察', # 'Monitored',
	'Class:Change/Attribute:status/Value:monitored+' => '', # '',
	'Class:Change/Attribute:status/Value:closed' => '完了', # 'Closed',
	'Class:Change/Attribute:status/Value:closed+' => '', # '',
	'Class:Change/Attribute:reason' => '理由', # 'Reason',
	'Class:Change/Attribute:reason+' => '', # '',
	'Class:Change/Attribute:requestor_id' => '依頼者', # 'Requestor',
	'Class:Change/Attribute:requestor_id+' => '', # '',
	'Class:Change/Attribute:requestor_email' => '依頼者', # 'Requestor',
	'Class:Change/Attribute:requestor_email+' => '', # '',
	'Class:Change/Attribute:org_id' => '顧客', # 'Customer',
	'Class:Change/Attribute:org_id+' => '', # '',
	'Class:Change/Attribute:org_name' => '顧客', # 'Customer',
	'Class:Change/Attribute:org_name+' => '', # '',
	'Class:Change/Attribute:workgroup_id' => '作業グループ', # 'Workgroup',
	'Class:Change/Attribute:workgroup_id+' => '', # '',
	'Class:Change/Attribute:workgroup_name' => '作業グループ', # 'Workgroup',
	'Class:Change/Attribute:workgroup_name+' => '', # '',
	'Class:Change/Attribute:creation_date' => '作成', # 'Created',
	'Class:Change/Attribute:creation_date+' => '', # '',
	'Class:Change/Attribute:last_update' => '最終更新', # 'Last update',
	'Class:Change/Attribute:last_update+' => '', # '',
	'Class:Change/Attribute:end_date' => '作業終了', # 'End date',
	'Class:Change/Attribute:end_date+' => '', # '',
	'Class:Change/Attribute:close_date' => '完了', # 'Closed',
	'Class:Change/Attribute:close_date+' => '', # '',
	'Class:Change/Attribute:impact' => '影響', # 'Impact',
	'Class:Change/Attribute:impact+' => '', # '',
	'Class:Change/Attribute:agent_id' => 'エージェント', # 'Agent',
	'Class:Change/Attribute:agent_id+' => '', # '',
	'Class:Change/Attribute:agent_name' => 'エージェント', # 'Agent',
	'Class:Change/Attribute:agent_name+' => '', # '',
	'Class:Change/Attribute:agent_email' => 'エージェント', # 'Agent','Agent', # 'Agent',
	'Class:Change/Attribute:agent_email+' => '', # '',
	'Class:Change/Attribute:supervisor_group_id' => '監督者チーム', # 'Supervisor team',
	'Class:Change/Attribute:supervisor_group_id+' => '', # '',
	'Class:Change/Attribute:supervisor_group_name' => '監督者チーム', # 'Supervisor team',
	'Class:Change/Attribute:supervisor_group_name+' => '', # '',
	'Class:Change/Attribute:supervisor_id' => '監督者', # 'Supervisor',
	'Class:Change/Attribute:supervisor_id+' => '', # '',
	'Class:Change/Attribute:supervisor_email' => '監督者', # 'Supervisor',
	'Class:Change/Attribute:supervisor_email+' => '', # '',
	'Class:Change/Attribute:manager_group_id' => 'マネジャーチーム', # 'Manager team',
	'Class:Change/Attribute:manager_group_id+' => '', # '',
	'Class:Change/Attribute:manager_group_name' => 'マネジャーチーム', # 'Manager team',
	'Class:Change/Attribute:manager_group_name+' => '', # '',
	'Class:Change/Attribute:manager_id' => 'マネジャー', # 'Manager',
	'Class:Change/Attribute:manager_id+' => '', # '',
	'Class:Change/Attribute:manager_email' => 'マネジャー', # 'Manager',
	'Class:Change/Attribute:manager_email+' => '', # '',
	'Class:Change/Attribute:outage' => '停止', # 'Outage',
	'Class:Change/Attribute:outage+' => '', # '',
	'Class:Change/Attribute:outage/Value:yes' => 'はい', # 'Yes',
	'Class:Change/Attribute:outage/Value:yes+' => '', # '',
	'Class:Change/Attribute:outage/Value:no' => 'いいえ', # 'No',
	'Class:Change/Attribute:outage/Value:no+' => '', # '',
	'Class:Change/Attribute:change_request' => 'リクエスト', # 'Request',
	'Class:Change/Attribute:change_request+' => '', # '',
	'Class:Change/Attribute:fallback' => '代替計画', # 'Fallback plan',
	'Class:Change/Attribute:fallback+' => '', # '',
	'Class:Change/Stimulus:ev_validate' => '受付', # 'Validate',
	'Class:Change/Stimulus:ev_validate+' => '', # '',
	'Class:Change/Stimulus:ev_reject' => '却下', # 'Reject',
	'Class:Change/Stimulus:ev_reject+' => '', # '',
	'Class:Change/Stimulus:ev_assign' => '担当割当', # 'Assign',
	'Class:Change/Stimulus:ev_assign+' => '', # '',
	'Class:Change/Stimulus:ev_reopen' => '再開', # 'Reopen',
	'Class:Change/Stimulus:ev_reopen+' => '', # '',
	'Class:Change/Stimulus:ev_plan' => '計画', # 'Plan',
	'Class:Change/Stimulus:ev_plan+' => '', # '',
	'Class:Change/Stimulus:ev_approve' => '承認', # 'Approve',
	'Class:Change/Stimulus:ev_approve+' => '', # '',
	'Class:Change/Stimulus:ev_replan' => '再計画', # 'Replan',
	'Class:Change/Stimulus:ev_replan+' => '', # '',
	'Class:Change/Stimulus:ev_notapprove' => '却下', # 'Reject',
	'Class:Change/Stimulus:ev_notapprove+' => '', # '',
	'Class:Change/Stimulus:ev_implement' => '実施', # 'Implement',
	'Class:Change/Stimulus:ev_implement+' => '', # '',
	'Class:Change/Stimulus:ev_monitor' => '経過観察', # 'Monitor',
	'Class:Change/Stimulus:ev_monitor+' => '', # '',
	'Class:Change/Stimulus:ev_finish' => '作業終了', # 'Finish',
	'Class:Change/Stimulus:ev_finish+' => '', # '',
));

//
// Class: RoutineChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:RoutineChange' => '定期変更', # 'Routine Change',
	'Class:RoutineChange+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:new' => '新規', # 'New',
	'Class:RoutineChange/Attribute:status/Value:new+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:assigned' => '割当済', # 'Assigned',
	'Class:RoutineChange/Attribute:status/Value:assigned+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled' => '計画・予定された', # 'Planned and scheduled',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:approved' => '承認済', # 'Approved',
	'Class:RoutineChange/Attribute:status/Value:approved+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:implemented' => '実施済', # 'Implemented',
	'Class:RoutineChange/Attribute:status/Value:implemented+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:monitored' => '経過観察中', # 'Monitored',
	'Class:RoutineChange/Attribute:status/Value:monitored+' => '', # '',
	'Class:RoutineChange/Attribute:status/Value:closed' => '完了', # 'Closed',
	'Class:RoutineChange/Attribute:status/Value:closed+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_validate' => '受付', # 'Validate',
	'Class:RoutineChange/Stimulus:ev_validate+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_assign' => '担当割当', # 'Assign',
	'Class:RoutineChange/Stimulus:ev_assign+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_reopen' => '再開', # 'Reopen',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_plan' => '計画', # 'Plan',
	'Class:RoutineChange/Stimulus:ev_plan+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_replan' => '再計画', # 'Replan',
	'Class:RoutineChange/Stimulus:ev_replan+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_implement' => '実施', # 'Implement',
	'Class:RoutineChange/Stimulus:ev_implement+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_monitor' => '経過観察', # 'Monitor',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '', # '',
	'Class:RoutineChange/Stimulus:ev_finish' => '作業終了', # 'Finish',
	'Class:RoutineChange/Stimulus:ev_finish+' => '', # '',
));

//
// Class: ApprovedChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApprovedChange' => '承認済の変更', # 'Approved Changes',
	'Class:ApprovedChange+' => '', # '',
	'Class:ApprovedChange/Attribute:approval_date' => '承認日', # 'Approval Date',
	'Class:ApprovedChange/Attribute:approval_date+' => '', # '',
	'Class:ApprovedChange/Attribute:approval_comment' => '承認時のコメント', # 'Approval comment',
	'Class:ApprovedChange/Attribute:approval_comment+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_validate' => '受付', # 'Validate',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_reject' => '却下', # 'Reject',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_assign' => '担当割当', # 'Assign',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => '再開', # 'Reopen',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_plan' => '計画', # 'Plan',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_approve' => '承認', # 'Approve',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_replan' => '再計画', # 'Replan',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => '承認の不同意', # 'Reject approval',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_implement' => '実施', # 'Implement',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => '経過観察', # 'Monitor',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '', # '',
	'Class:ApprovedChange/Stimulus:ev_finish' => '作業終了', # 'Finish',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '', # '',
));
//
// Class: NormalChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NormalChange' => '通常変更', # 'Normal Change',
	'Class:NormalChange+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:new' => '新規', # 'New',
	'Class:NormalChange/Attribute:status/Value:new+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:validated' => '受付済', # 'Validated',
	'Class:NormalChange/Attribute:status/Value:validated+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:rejected' => '却下済', # 'Rejected',
	'Class:NormalChange/Attribute:status/Value:rejected+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:assigned' => '割当済', # 'Assigned',
	'Class:NormalChange/Attribute:status/Value:assigned+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled' => '計画・予定された', # 'Planned and scheduled',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:approved' => '承認済', # 'Approved',
	'Class:NormalChange/Attribute:status/Value:approved+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:notapproved' => '未承認', # 'Not approved',
	'Class:NormalChange/Attribute:status/Value:notapproved+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:implemented' => '実施済', # 'Implemented',
	'Class:NormalChange/Attribute:status/Value:implemented+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:monitored' => '経過観察中', # 'Monitored',
	'Class:NormalChange/Attribute:status/Value:monitored+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:closed' => '完了', # 'Closed',
	'Class:NormalChange/Attribute:status/Value:closed+' => '', # '',
	'Class:NormalChange/Attribute:acceptance_date' => '受理日', # 'Acceptance date',
	'Class:NormalChange/Attribute:acceptance_date+' => '', # '',
	'Class:NormalChange/Attribute:acceptance_comment' => '受理コメント', # 'Acceptance comment',
	'Class:NormalChange/Attribute:acceptance_comment+' => '', # '',
	'Class:NormalChange/Stimulus:ev_validate' => '受付', # 'Validate',
	'Class:NormalChange/Stimulus:ev_validate+' => '', # '',
	'Class:NormalChange/Stimulus:ev_reject' => '却下', # 'Reject',
	'Class:NormalChange/Stimulus:ev_reject+' => '', # '',
	'Class:NormalChange/Stimulus:ev_assign' => '担当割当', # 'Assign',
	'Class:NormalChange/Stimulus:ev_assign+' => '', # '',
	'Class:NormalChange/Stimulus:ev_reopen' => '再開', # 'Reopen',
	'Class:NormalChange/Stimulus:ev_reopen+' => '', # '',
	'Class:NormalChange/Stimulus:ev_plan' => '計画', # 'Plan',
	'Class:NormalChange/Stimulus:ev_plan+' => '', # '',
	'Class:NormalChange/Stimulus:ev_approve' => '承認', # 'Approve',
	'Class:NormalChange/Stimulus:ev_approve+' => '', # '',
	'Class:NormalChange/Stimulus:ev_replan' => '再計画', # 'Replan',
	'Class:NormalChange/Stimulus:ev_replan+' => '', # '',
	'Class:NormalChange/Stimulus:ev_notapprove' => '承認の不同意', # 'Reject approval',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '', # '',
	'Class:NormalChange/Stimulus:ev_implement' => '実施', # 'Implement',
	'Class:NormalChange/Stimulus:ev_implement+' => '', # '',
	'Class:NormalChange/Stimulus:ev_monitor' => '経過観察', # 'Monitor',
	'Class:NormalChange/Stimulus:ev_monitor+' => '', # '',
	'Class:NormalChange/Stimulus:ev_finish' => '作業終了', # 'Finish',
	'Class:NormalChange/Stimulus:ev_finish+' => '', # '',
));

//
// Class: EmergencyChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:EmergencyChange' => '緊急変更', # 'Emergency Change',
	'Class:EmergencyChange+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:new' => '新規', # 'New',
	'Class:EmergencyChange/Attribute:status/Value:new+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:validated' => '受付済', # 'Validated',
	'Class:EmergencyChange/Attribute:status/Value:validated+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:rejected' => '却下', # 'Rejected',
	'Class:EmergencyChange/Attribute:status/Value:rejected+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:assigned' => '割当済', # 'Assigned',
	'Class:EmergencyChange/Attribute:status/Value:assigned+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled' => '計画・予定された', # 'Planned and scheduled',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:approved' => '承認済み', # 'Approved',
	'Class:EmergencyChange/Attribute:status/Value:approved+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:notapproved' => '未承認', # 'Not approved',
	'Class:EmergencyChange/Attribute:status/Value:notapproved+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:implemented' => '実施済', # 'Implemented',
	'Class:EmergencyChange/Attribute:status/Value:implemented+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:monitored' => '経過観察中', # 'Monitored',
	'Class:EmergencyChange/Attribute:status/Value:monitored+' => '', # '',
	'Class:EmergencyChange/Attribute:status/Value:closed' => '完了', # 'Closed',
	'Class:EmergencyChange/Attribute:status/Value:closed+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_validate' => '受付', # 'Validate',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_reject' => '却下', # 'Reject',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_assign' => '担当割当', # 'Assign',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => '再開', # 'Reopen',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_plan' => '計画', # 'Plan',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_approve' => '承認', # 'Approve',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_replan' => '再計画', # 'Replan',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => '承認の不同意', # 'Reject approval',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_implement' => '実施', # 'Implement',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => '経過観察', # 'Monitor',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '', # '',
	'Class:EmergencyChange/Stimulus:ev_finish' => '作業終了', # 'Finish',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '', # '',
));

?>

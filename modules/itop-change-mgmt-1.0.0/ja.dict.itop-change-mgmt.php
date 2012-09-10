<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:ChangeManagement' => '変更管理',
	'Menu:Change:Overview' => '概要',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => '新規変更',
	'Menu:NewChange+' => '新規変更のチケット作成',
	'Menu:SearchChanges' => '変更検索',
	'Menu:SearchChanges+' => '変更チケット検索',
	'Menu:Change:Shortcuts' => 'ショートカット',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => '受理待ちの変更',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => '承認待ちの変更',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'オープン状態の変更',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => '担当している変更',
	'Menu:MyChanges+' => '担当している変更(エージェントとして)',
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
	'Class:Change' => '変更',
	'Class:Change+' => '',
	'Class:Change/Attribute:start_date' => '開始計画日',
	'Class:Change/Attribute:start_date+' => '',
	'Class:Change/Attribute:status' => '状態',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => '新規',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:validated' => '受け付け済み',
	'Class:Change/Attribute:status/Value:validated+' => '',
	'Class:Change/Attribute:status/Value:rejected' => '却下済み',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:plannedscheduled' => '計画・予定された',
	'Class:Change/Attribute:status/Value:plannedscheduled+' => '',
	'Class:Change/Attribute:status/Value:approved' => '承認済み',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:notapproved' => '未承認',
	'Class:Change/Attribute:status/Value:notapproved+' => '',
	'Class:Change/Attribute:status/Value:implemented' => '実施済み',
	'Class:Change/Attribute:status/Value:implemented+' => '',
	'Class:Change/Attribute:status/Value:monitored' => '経過観察',
	'Class:Change/Attribute:status/Value:monitored+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'クローズ',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:reason' => '理由',
	'Class:Change/Attribute:reason+' => '',
	'Class:Change/Attribute:requestor_id' => '依頼者',
	'Class:Change/Attribute:requestor_id+' => '',
	'Class:Change/Attribute:requestor_email' => '依頼者',
	'Class:Change/Attribute:requestor_email+' => '',
	'Class:Change/Attribute:org_id' => '顧客',
	'Class:Change/Attribute:org_id+' => '',
	'Class:Change/Attribute:org_name' => '顧客',
	'Class:Change/Attribute:org_name+' => '',
	'Class:Change/Attribute:workgroup_id' => '作業グループ',
	'Class:Change/Attribute:workgroup_id+' => '',
	'Class:Change/Attribute:workgroup_name' => '作業グループ',
	'Class:Change/Attribute:workgroup_name+' => '',
	'Class:Change/Attribute:creation_date' => '作成日',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:last_update' => '最終更新',
	'Class:Change/Attribute:last_update+' => '',
	'Class:Change/Attribute:end_date' => '終了日',
	'Class:Change/Attribute:end_date+' => '',
	'Class:Change/Attribute:close_date' => 'クローズ',
	'Class:Change/Attribute:close_date+' => '',
	'Class:Change/Attribute:impact' => 'インパクト',
	'Class:Change/Attribute:impact+' => '',
	'Class:Change/Attribute:agent_id' => 'エージェント',
	'Class:Change/Attribute:agent_id+' => '',
	'Class:Change/Attribute:agent_name' => 'エージェント',
	'Class:Change/Attribute:agent_name+' => '',
	'Class:Change/Attribute:agent_email' => 'エージェント',
	'Class:Change/Attribute:agent_email+' => '',
	'Class:Change/Attribute:supervisor_group_id' => '監督者チーム',
	'Class:Change/Attribute:supervisor_group_id+' => '',
	'Class:Change/Attribute:supervisor_group_name' => '監督者チーム',
	'Class:Change/Attribute:supervisor_group_name+' => '',
	'Class:Change/Attribute:supervisor_id' => '監督者',
	'Class:Change/Attribute:supervisor_id+' => '',
	'Class:Change/Attribute:supervisor_email' => '監督者',
	'Class:Change/Attribute:supervisor_email+' => '',
	'Class:Change/Attribute:manager_group_id' => 'マネジャーチーム',
	'Class:Change/Attribute:manager_group_id+' => '',
	'Class:Change/Attribute:manager_group_name' => 'マネジャーチーム',
	'Class:Change/Attribute:manager_group_name+' => '',
	'Class:Change/Attribute:manager_id' => 'マネジャー',
	'Class:Change/Attribute:manager_id+' => '',
	'Class:Change/Attribute:manager_email' => 'マネジャー',
	'Class:Change/Attribute:manager_email+' => '',
	'Class:Change/Attribute:outage' => '停止',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'はい',
	'Class:Change/Attribute:outage/Value:yes+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'いいえ',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:change_request' => '要求',
	'Class:Change/Attribute:change_request+' => '',
	'Class:Change/Attribute:fallback' => '代替計画',
	'Class:Change/Attribute:fallback+' => '',
	'Class:Change/Stimulus:ev_validate' => '受け付け',
	'Class:Change/Stimulus:ev_validate+' => '',
	'Class:Change/Stimulus:ev_reject' => '却下',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_assign' => '担当割り当て',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_reopen' => '再開',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_plan' => '計画',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_approve' => '承認',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_replan' => '再計画',
	'Class:Change/Stimulus:ev_replan+' => '',
	'Class:Change/Stimulus:ev_notapprove' => '却下',
	'Class:Change/Stimulus:ev_notapprove+' => '',
	'Class:Change/Stimulus:ev_implement' => '実施',
	'Class:Change/Stimulus:ev_implement+' => '',
	'Class:Change/Stimulus:ev_monitor' => '経過観察',
	'Class:Change/Stimulus:ev_monitor+' => '',
	'Class:Change/Stimulus:ev_finish' => '終了',
	'Class:Change/Stimulus:ev_finish+' => '',
));

//
// Class: RoutineChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:RoutineChange' => 'ルーチン変更',
	'Class:RoutineChange+' => '',
	'Class:RoutineChange/Attribute:status/Value:new' => '新規',
	'Class:RoutineChange/Attribute:status/Value:new+' => '',
	'Class:RoutineChange/Attribute:status/Value:assigned' => '割当済み',
	'Class:RoutineChange/Attribute:status/Value:assigned+' => '',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled' => '計画・予定された',
	'Class:RoutineChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:RoutineChange/Attribute:status/Value:approved' => '承認済み',
	'Class:RoutineChange/Attribute:status/Value:approved+' => '',
	'Class:RoutineChange/Attribute:status/Value:implemented' => '実施済み',
	'Class:RoutineChange/Attribute:status/Value:implemented+' => '',
	'Class:RoutineChange/Attribute:status/Value:monitored' => '経過観察中',
	'Class:RoutineChange/Attribute:status/Value:monitored+' => '',
	'Class:RoutineChange/Attribute:status/Value:closed' => 'クローズ',
	'Class:RoutineChange/Attribute:status/Value:closed+' => '',
	'Class:RoutineChange/Stimulus:ev_validate' => '受け付け',
	'Class:RoutineChange/Stimulus:ev_validate+' => '',
	'Class:RoutineChange/Stimulus:ev_assign' => '割り当て',
	'Class:RoutineChange/Stimulus:ev_assign+' => '',
	'Class:RoutineChange/Stimulus:ev_reopen' => '再オープン',
	'Class:RoutineChange/Stimulus:ev_reopen+' => '',
	'Class:RoutineChange/Stimulus:ev_plan' => '計画',
	'Class:RoutineChange/Stimulus:ev_plan+' => '',
	'Class:RoutineChange/Stimulus:ev_replan' => '再計画',
	'Class:RoutineChange/Stimulus:ev_replan+' => '',
	'Class:RoutineChange/Stimulus:ev_implement' => '実施',
	'Class:RoutineChange/Stimulus:ev_implement+' => '',
	'Class:RoutineChange/Stimulus:ev_monitor' => '経過観察',
	'Class:RoutineChange/Stimulus:ev_monitor+' => '',
	'Class:RoutineChange/Stimulus:ev_finish' => '終了',
	'Class:RoutineChange/Stimulus:ev_finish+' => '',
	'Class:RoutineChange/Stimulus:ev_reject' => '否認',
	'Class:RoutineChange/Stimulus:ev_approve' => '承認',
	'Class:RoutineChange/Stimulus:ev_notapprove' => '承認しない'
));

//
// Class: ApprovedChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApprovedChange' => '承認済の変更',
	'Class:ApprovedChange+' => '',
	'Class:ApprovedChange/Attribute:approval_date' => '承認日',
	'Class:ApprovedChange/Attribute:approval_date+' => '',
	'Class:ApprovedChange/Attribute:approval_comment' => '承認時のコメント',
	'Class:ApprovedChange/Attribute:approval_comment+' => '',
	'Class:ApprovedChange/Stimulus:ev_validate' => '受け付け',
	'Class:ApprovedChange/Stimulus:ev_validate+' => '',
	'Class:ApprovedChange/Stimulus:ev_reject' => '却下',
	'Class:ApprovedChange/Stimulus:ev_reject+' => '',
	'Class:ApprovedChange/Stimulus:ev_assign' => '割り当て',
	'Class:ApprovedChange/Stimulus:ev_assign+' => '',
	'Class:ApprovedChange/Stimulus:ev_reopen' => '再オープン',
	'Class:ApprovedChange/Stimulus:ev_reopen+' => '',
	'Class:ApprovedChange/Stimulus:ev_plan' => '計画',
	'Class:ApprovedChange/Stimulus:ev_plan+' => '',
	'Class:ApprovedChange/Stimulus:ev_approve' => '承認',
	'Class:ApprovedChange/Stimulus:ev_approve+' => '',
	'Class:ApprovedChange/Stimulus:ev_replan' => '再計画',
	'Class:ApprovedChange/Stimulus:ev_replan+' => '',
	'Class:ApprovedChange/Stimulus:ev_notapprove' => '承認を拒否',
	'Class:ApprovedChange/Stimulus:ev_notapprove+' => '',
	'Class:ApprovedChange/Stimulus:ev_implement' => '実施',
	'Class:ApprovedChange/Stimulus:ev_implement+' => '',
	'Class:ApprovedChange/Stimulus:ev_monitor' => '経過観察',
	'Class:ApprovedChange/Stimulus:ev_monitor+' => '',
	'Class:ApprovedChange/Stimulus:ev_finish' => '終了',
	'Class:ApprovedChange/Stimulus:ev_finish+' => '',
));
//
// Class: NormalChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NormalChange' => '通常変更',
	'Class:NormalChange+' => '',
	'Class:NormalChange/Attribute:status/Value:new' => '新規',
	'Class:NormalChange/Attribute:status/Value:new+' => '',
	'Class:NormalChange/Attribute:status/Value:validated' => '受け付け済み',
	'Class:NormalChange/Attribute:status/Value:validated+' => '',
	'Class:NormalChange/Attribute:status/Value:rejected' => '却下済み',
	'Class:NormalChange/Attribute:status/Value:rejected+' => '',
	'Class:NormalChange/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:NormalChange/Attribute:status/Value:assigned+' => '',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled' => '計画・予定された',
	'Class:NormalChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:NormalChange/Attribute:status/Value:approved' => '承認済み',
	'Class:NormalChange/Attribute:status/Value:approved+' => '',
	'Class:NormalChange/Attribute:status/Value:notapproved' => '未承認',
	'Class:NormalChange/Attribute:status/Value:notapproved+' => '', # '',
	'Class:NormalChange/Attribute:status/Value:implemented' => '実施済み',
	'Class:NormalChange/Attribute:status/Value:implemented+' => '',
	'Class:NormalChange/Attribute:status/Value:monitored' => '経過観察中',
	'Class:NormalChange/Attribute:status/Value:monitored+' => '',
	'Class:NormalChange/Attribute:status/Value:closed' => 'クローズ',
	'Class:NormalChange/Attribute:status/Value:closed+' => '',
	'Class:NormalChange/Attribute:acceptance_date' => '受理日',
	'Class:NormalChange/Attribute:acceptance_date+' => '',
	'Class:NormalChange/Attribute:acceptance_comment' => '受理コメント',
	'Class:NormalChange/Attribute:acceptance_comment+' => '',
	'Class:NormalChange/Stimulus:ev_validate' => '受け付け',
	'Class:NormalChange/Stimulus:ev_validate+' => '',
	'Class:NormalChange/Stimulus:ev_reject' => '却下',
	'Class:NormalChange/Stimulus:ev_reject+' => '',
	'Class:NormalChange/Stimulus:ev_assign' => '割り当て',
	'Class:NormalChange/Stimulus:ev_assign+' => '',
	'Class:NormalChange/Stimulus:ev_reopen' => '再オープン',
	'Class:NormalChange/Stimulus:ev_reopen+' => '',
	'Class:NormalChange/Stimulus:ev_plan' => '計画',
	'Class:NormalChange/Stimulus:ev_plan+' => '',
	'Class:NormalChange/Stimulus:ev_approve' => '承認',
	'Class:NormalChange/Stimulus:ev_approve+' => '',
	'Class:NormalChange/Stimulus:ev_replan' => '再計画',
	'Class:NormalChange/Stimulus:ev_replan+' => '',
	'Class:NormalChange/Stimulus:ev_notapprove' => '承認を拒否',
	'Class:NormalChange/Stimulus:ev_notapprove+' => '',
	'Class:NormalChange/Stimulus:ev_implement' => '実施',
	'Class:NormalChange/Stimulus:ev_implement+' => '',
	'Class:NormalChange/Stimulus:ev_monitor' => '経過観察',
	'Class:NormalChange/Stimulus:ev_monitor+' => '',
	'Class:NormalChange/Stimulus:ev_finish' => '終了',
	'Class:NormalChange/Stimulus:ev_finish+' => '',
));

//
// Class: EmergencyChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:EmergencyChange' => '緊急変更',
	'Class:EmergencyChange+' => '',
	'Class:EmergencyChange/Attribute:status/Value:new' => '新規',
	'Class:EmergencyChange/Attribute:status/Value:new+' => '',
	'Class:EmergencyChange/Attribute:status/Value:validated' => '受け付け済み',
	'Class:EmergencyChange/Attribute:status/Value:validated+' => '',
	'Class:EmergencyChange/Attribute:status/Value:rejected' => '却下済み',
	'Class:EmergencyChange/Attribute:status/Value:rejected+' => '',
	'Class:EmergencyChange/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:EmergencyChange/Attribute:status/Value:assigned+' => '',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled' => '計画・予定された',
	'Class:EmergencyChange/Attribute:status/Value:plannedscheduled+' => '',
	'Class:EmergencyChange/Attribute:status/Value:approved' => '承認済み',
	'Class:EmergencyChange/Attribute:status/Value:approved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:notapproved' => '未承認',
	'Class:EmergencyChange/Attribute:status/Value:notapproved+' => '',
	'Class:EmergencyChange/Attribute:status/Value:implemented' => '実施済み',
	'Class:EmergencyChange/Attribute:status/Value:implemented+' => '',
	'Class:EmergencyChange/Attribute:status/Value:monitored' => '経過観察中',
	'Class:EmergencyChange/Attribute:status/Value:monitored+' => '',
	'Class:EmergencyChange/Attribute:status/Value:closed' => 'クローズ',
	'Class:EmergencyChange/Attribute:status/Value:closed+' => '',
	'Class:EmergencyChange/Stimulus:ev_validate' => '受け付け',
	'Class:EmergencyChange/Stimulus:ev_validate+' => '',
	'Class:EmergencyChange/Stimulus:ev_reject' => '却下',
	'Class:EmergencyChange/Stimulus:ev_reject+' => '',
	'Class:EmergencyChange/Stimulus:ev_assign' => '割り当て',
	'Class:EmergencyChange/Stimulus:ev_assign+' => '',
	'Class:EmergencyChange/Stimulus:ev_reopen' => '再オープン',
	'Class:EmergencyChange/Stimulus:ev_reopen+' => '',
	'Class:EmergencyChange/Stimulus:ev_plan' => '計画',
	'Class:EmergencyChange/Stimulus:ev_plan+' => '',
	'Class:EmergencyChange/Stimulus:ev_approve' => '承認',
	'Class:EmergencyChange/Stimulus:ev_approve+' => '',
	'Class:EmergencyChange/Stimulus:ev_replan' => '再計画',
	'Class:EmergencyChange/Stimulus:ev_replan+' => '',
	'Class:EmergencyChange/Stimulus:ev_notapprove' => '承認を拒否',
	'Class:EmergencyChange/Stimulus:ev_notapprove+' => '',
	'Class:EmergencyChange/Stimulus:ev_implement' => '実施',
	'Class:EmergencyChange/Stimulus:ev_implement+' => '',
	'Class:EmergencyChange/Stimulus:ev_monitor' => '経過観察',
	'Class:EmergencyChange/Stimulus:ev_monitor+' => '',
	'Class:EmergencyChange/Stimulus:ev_finish' => '終了',
	'Class:EmergencyChange/Stimulus:ev_finish+' => '',
));

?>
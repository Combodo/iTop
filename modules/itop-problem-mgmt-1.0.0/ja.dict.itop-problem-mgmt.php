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

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+




Dict::Add('JA JP', 'Japanese', '日本語', array (
        'Menu:ProblemManagement' => '問題管理',
        'Menu:ProblemManagement+' => '問題管理',
    	'Menu:Problem:Overview' => '概要',
    	'Menu:Problem:Overview+' => '概要',
    	'Menu:NewProblem' => '新規登録',
    	'Menu:NewProblem+' => '新規登録',
    	'Menu:SearchProblems' => '検索',
    	'Menu:SearchProblems+' => '検索',
    	'Menu:Problem:Shortcuts' => 'ショートカット',
        'Menu:Problem:MyProblems' => '私の担当問題',
        'Menu:Problem:MyProblems+' => '私の担当問題',
        'Menu:Problem:OpenProblems' => 'オープンな問題',
        'Menu:Problem:OpenProblems+' => 'オープンな問題',
	'UI-ProblemManagementOverview-ProblemByService' => 'サービス別問題',
	'UI-ProblemManagementOverview-ProblemByService+' => 'サービス別問題',
	'UI-ProblemManagementOverview-ProblemByPriority' => '優先度別問題',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '優先度別問題',
	'UI-ProblemManagementOverview-ProblemUnassigned' => '未割当問題',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '未割当問題',
	'UI:ProblemMgmtMenuOverview:Title' => '問題管理ダッシュボード',
	'UI:ProblemMgmtMenuOverview:Title+' => '問題管理ダッシュボード',

));
//
// Class: Problem
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Problem' => '問題',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => '状態',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => '新規',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => '割当済み',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => '解決済み',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'クローズ',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => '顧客',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => '名前',
	'Class:Problem/Attribute:org_name+' => '共通名',
	'Class:Problem/Attribute:service_id' => 'サービス',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => '名前',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'サービスカテゴリー',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => '名前',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => '製品',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'インパクト',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => '人',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'サービス',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => '部署',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => '緊急度',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => '低',
	'Class:Problem/Attribute:urgency/Value:1+' => '低',
	'Class:Problem/Attribute:urgency/Value:2' => '中',
	'Class:Problem/Attribute:urgency/Value:2+' => '中',
	'Class:Problem/Attribute:urgency/Value:3' => '高',
	'Class:Problem/Attribute:urgency/Value:3+' => '高',
	'Class:Problem/Attribute:priority' => '優先度',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => '低',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => '中',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => '高',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'ワークグループ',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => '名前',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => 'エージェント',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => 'エージェント名',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'エージェントEメール',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => '関連する変更',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => '参照',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => 'クローズ日',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => '最終更新日',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => '割り当て日',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => '解決日',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => '既知のエラー',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => '割り当て',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => '再割り当て',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => '解決',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'クローズ',
	'Class:Problem/Stimulus:ev_close+' => '',
));

?>

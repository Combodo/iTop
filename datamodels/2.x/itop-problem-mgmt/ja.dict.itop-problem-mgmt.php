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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Problem' => '問題',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => '状態',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => '新規',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => '解決済み',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'クローズ',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => 'サービス',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'サービスサブカテゴリ',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:product' => '製品',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'インパクト',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => '部門',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'サービス',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => '人',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => '緊急度',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => '至急',
	'Class:Problem/Attribute:urgency/Value:1+' => '至急',
	'Class:Problem/Attribute:urgency/Value:2' => '高',
	'Class:Problem/Attribute:urgency/Value:2+' => '高',
	'Class:Problem/Attribute:urgency/Value:3' => '中',
	'Class:Problem/Attribute:urgency/Value:3+' => '中',
	'Class:Problem/Attribute:urgency/Value:4' => '低',
	'Class:Problem/Attribute:urgency/Value:4+' => '低',
	'Class:Problem/Attribute:priority' => '優先度',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => '最優先',
	'Class:Problem/Attribute:priority/Value:1+' => '最優先',
	'Class:Problem/Attribute:priority/Value:2' => '高',
	'Class:Problem/Attribute:priority/Value:2+' => '高',
	'Class:Problem/Attribute:priority/Value:3' => '中',
	'Class:Problem/Attribute:priority/Value:3+' => '中',
	'Class:Problem/Attribute:priority/Value:4' => '低',
	'Class:Problem/Attribute:priority/Value:4+' => '低',
	'Class:Problem/Attribute:related_change_id' => '関連する変更',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:assignment_date' => '割り当て日',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => '解決日',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => '既知のエラー',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Attribute:related_request_list' => '関連する要求',
	'Class:Problem/Attribute:related_request_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => '割り当て',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => '再割り当て',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => '解決',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'クローズ',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Menu:ProblemManagement' => '問題管理',
	'Menu:ProblemManagement+' => '問題管理',
	'Menu:Problem:Overview' => '概要',
	'Menu:Problem:Overview+' => '概要',
	'Menu:NewProblem' => '新規問題',
	'Menu:NewProblem+' => '新規問題',
	'Menu:SearchProblems' => '問題検索',
	'Menu:SearchProblems+' => '問題検索',
	'Menu:Problem:Shortcuts' => 'ショートカット',
	'Menu:Problem:MyProblems' => '担当してる問題',
	'Menu:Problem:MyProblems+' => '担当している問題',
	'Menu:Problem:OpenProblems' => '全オープン問題',
	'Menu:Problem:OpenProblems+' => '全オープン問題',
	'UI-ProblemManagementOverview-ProblemByService' => 'サービス別問題',
	'UI-ProblemManagementOverview-ProblemByService+' => 'サービス別問題',
	'UI-ProblemManagementOverview-ProblemByPriority' => '優先度別問題',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '優先度別問題',
	'UI-ProblemManagementOverview-ProblemUnassigned' => '未割り当て問題',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '未割り当て問題',
	'UI:ProblemMgmtMenuOverview:Title' => '問題管理ダッシュボード',
	'UI:ProblemMgmtMenuOverview:Title+' => '問題管理ダッシュボード',
	'Class:Problem/Attribute:service_name' => 'サービス名',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'サービスサブカテゴリ',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:related_change_ref' => '関連する変更参照',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Problem/Attribute:related_incident_list+' => 'All the incidents that are related to this problem~~',
));
?>
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
        'Menu:ProblemManagement' => 'プロブレム管理', // 'Problem Management',	# 'Problem Management'
        'Menu:ProblemManagement+' => 'プロブレム管理', // 'Problem Management',	# 'Problem Management'
    	'Menu:Problem:Overview' => '概要',			# 'Overview'
    	'Menu:Problem:Overview+' => '概要',			# 'Overview'
    	'Menu:NewProblem' => '新規プロブレム', // 'New Problem',			# 'New Problem'
    	'Menu:NewProblem+' => '新規プロブレム', // 'New Problem',			# 'New Problem'
    	'Menu:SearchProblems' => 'プロブレムを検索', // 'Search for Problems',		# 'Search for Problems'
    	'Menu:SearchProblems+' => 'プロブレムを検索', // 'Search for Problems',	# 'Search for Problems'
    	'Menu:Problem:Shortcuts' => 'ショートカット',		# 'Shortcuts'
        'Menu:Problem:MyProblems' => 'マイプロブレム', // 'My Problems',		# 'My Problems'
        'Menu:Problem:MyProblems+' => 'マイプロブレム', // 'My Problems',		# 'My Problems'
        'Menu:Problem:OpenProblems' => '担当のいない problems',	# 'All Open problems'
        'Menu:Problem:OpenProblems+' => '担当のいない problems',	# 'All Open problems'
	'UI-ProblemManagementOverview-ProblemByService' => 'サービス別プロブレム', // 'Problems by Service',	# 'Problems by Service'
	'UI-ProblemManagementOverview-ProblemByService+' => 'サービス別プロブレム', // 'Problems by Service',	# 'Problems by Service'
	'UI-ProblemManagementOverview-ProblemByPriority' => 'プライオリティ別プロブレム', // 'Problems by Priority',	# 'Problems by Priority'
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'プライオリティ別プロブレム', // 'Problems by Priority',	# 'Problems by Priority'
	'UI-ProblemManagementOverview-ProblemUnassigned' => '未アサインプロブレム', // 'Unassigned Problems',	# 'Unassigned Problems'
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '未アサインプロブレム', // 'Unassigned Problems',	# 'Unassigned Problems'
	'UI:ProblemMgmtMenuOverview:Title' => 'プロブレム管理用ダッシュボード', // 'Dashboard for Problem Management',	# 'Dashboard for Problem Management'
	'UI:ProblemMgmtMenuOverview:Title+' => 'プロブレム管理用ダッシュボード', // 'Dashboard for Problem Management',	# 'Dashboard for Problem Management'

));
//
// Class: Problem
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Problem' => 'プロブレム', // 'Problem',	# 'Problem'
	'Class:Problem+' => '',		# ''
	'Class:Problem/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:Problem/Attribute:status+' => '',	# ''
	'Class:Problem/Attribute:status/Value:new' => '新規',	# 'New'
	'Class:Problem/Attribute:status/Value:new+' => '',	# ''
	'Class:Problem/Attribute:status/Value:assigned' => '割当済',	# 'Assigned'
	'Class:Problem/Attribute:status/Value:assigned+' => '',		# ''
	'Class:Problem/Attribute:status/Value:resolved' => '解決済み', // 'Resolved',	# 'Resolved'
	'Class:Problem/Attribute:status/Value:resolved+' => '',		# ''
	'Class:Problem/Attribute:status/Value:closed' => '完了',	# 'Closed'
	'Class:Problem/Attribute:status/Value:closed+' => '',		# ''
	'Class:Problem/Attribute:org_id' => 'カスタマー', // 'Customer',	  # 'Customer'
	'Class:Problem/Attribute:org_id+' => '',	  # ''
	'Class:Problem/Attribute:org_name' => '名前', // 'Name',	  # 'Name'
	'Class:Problem/Attribute:org_name+' => '共通名', // 'Common name',	# 'Common name'
	'Class:Problem/Attribute:service_id' => 'サービス', // 'Service',	# 'Service'
	'Class:Problem/Attribute:service_id+' => '',		# ''
	'Class:Problem/Attribute:service_name' => '名前', // 'Name',	# 'Name'
	'Class:Problem/Attribute:service_name+' => '',		# ''
	'Class:Problem/Attribute:servicesubcategory_id' => 'サービスカテゴリ', // 'Service Category',	# 'Service Category'
	'Class:Problem/Attribute:servicesubcategory_id+' => '',	    # ''
	'Class:Problem/Attribute:servicesubcategory_name' => '名前', // 'Name',  # 'Name'
	'Class:Problem/Attribute:servicesubcategory_name+' => '',     # ''
	'Class:Problem/Attribute:product' => 'プロダクト', // 'Product',	   # 'Product'
	'Class:Problem/Attribute:product+' => '',	   # ''
	'Class:Problem/Attribute:impact' => '影響', // 'Impact',	   # 'Impact'
	'Class:Problem/Attribute:impact+' => '',	   # ''
	'Class:Problem/Attribute:impact/Value:1' => 'パーソン', // 'A Person',	# 'A Person'
	'Class:Problem/Attribute:impact/Value:1+' => '',	# ''
	'Class:Problem/Attribute:impact/Value:2' => 'サービス', // 'A Service',  # 'A Service'
	'Class:Problem/Attribute:impact/Value:2+' => '',	  # ''
	'Class:Problem/Attribute:impact/Value:3' => '部署', // 'A Department', # 'A Department'
	'Class:Problem/Attribute:impact/Value:3+' => '',	    # ''
	'Class:Problem/Attribute:urgency' => '緊急', // 'Urgency',		    # 'Urgency'
	'Class:Problem/Attribute:urgency+' => '',		    # ''
	'Class:Problem/Attribute:urgency/Value:1' => '低', // 'Low',	    # 'Low'
	'Class:Problem/Attribute:urgency/Value:1+' => '低', // 'Low',	    # 'Low'
	'Class:Problem/Attribute:urgency/Value:2' => '中', // 'Medium',	    # 'Medium'
	'Class:Problem/Attribute:urgency/Value:2+' => '中', // 'Medium',	    # 'Medium'
	'Class:Problem/Attribute:urgency/Value:3' => '高', // 'High',	    # 'High'
	'Class:Problem/Attribute:urgency/Value:3+' => '高', // 'High',	    # 'High'
	'Class:Problem/Attribute:priority' => 'プライオリティ', // 'Priority',	    # 'Priority'
	'Class:Problem/Attribute:priority+' => '',		    # ''
	'Class:Problem/Attribute:priority/Value:1' => '低', // 'Low',	    # 'Low'
	'Class:Problem/Attribute:priority/Value:1+' => '',	    # ''
	'Class:Problem/Attribute:priority/Value:2' => '中', // 'Medium',	    # 'Medium'
	'Class:Problem/Attribute:priority/Value:2+' => '',	    # ''
	'Class:Problem/Attribute:priority/Value:3' => '高', // 'High',	    # 'High'
	'Class:Problem/Attribute:priority/Value:3+' => '',	    # ''
	'Class:Problem/Attribute:workgroup_id' => 'ワークグループ', // 'WorkGroup',	    # 'WorkGroup'
	'Class:Problem/Attribute:workgroup_id+' => '',		    # ''
	'Class:Problem/Attribute:workgroup_name' => '名前', // 'Name',	    # 'Name'
	'Class:Problem/Attribute:workgroup_name+' => '',	    # ''
	'Class:Problem/Attribute:agent_id' => 'エージェント', // 'Agent',		    # 'Agent'
	'Class:Problem/Attribute:agent_id+' => '',		    # ''
	'Class:Problem/Attribute:agent_name' => 'エージェント名', // 'Agent Name',	    # 'Agent Name'
	'Class:Problem/Attribute:agent_name+' => '',   		    # ''
	'Class:Problem/Attribute:agent_email' => 'エージェントEメール', // 'Agent Email',	# 'Agent Email'
	'Class:Problem/Attribute:agent_email+' => '',		# ''
	'Class:Problem/Attribute:related_change_id' => '関連する変更', // 'Related Change',	# 'Related Change'
	'Class:Problem/Attribute:related_change_id+' => '',			# ''
	'Class:Problem/Attribute:related_change_ref' => '参照', // 'Ref',	# 'Ref'
	'Class:Problem/Attribute:related_change_ref+' => '',	# ''
	'Class:Problem/Attribute:close_date' => 'クローズ日付', // 'Close Date',	# 'Close Date'
	'Class:Problem/Attribute:close_date+' => '',      	# ''
	'Class:Problem/Attribute:last_update' => '最終更新日', // 'Last Update',	# 'Last Update'
	'Class:Problem/Attribute:last_update+' => '',  		# ''
	'Class:Problem/Attribute:assignment_date' => 'アサイン日付', // 'Assignment Date',	# 'Assignment Date'
	'Class:Problem/Attribute:assignment_date+' => '',	 	# ''
	'Class:Problem/Attribute:resolution_date' => '解決日付', // 'Resolution Date',	# 'Resolution Date'
	'Class:Problem/Attribute:resolution_date+' => '',	 	# ''
	'Class:Problem/Attribute:knownerrors_list' => '既知のエラー', // 'Known Errors',	# 'Known Errors'
	'Class:Problem/Attribute:knownerrors_list+' => '',   # ''
	'Class:Problem/Stimulus:ev_assign' => '割当',	     # 'Assign'
	'Class:Problem/Stimulus:ev_assign+' => '',	     # ''
	'Class:Problem/Stimulus:ev_reassign' => '再割当', // 'Reaassign', # 'Reaassign'
	'Class:Problem/Stimulus:ev_reassign+' => '',	     # ''
	'Class:Problem/Stimulus:ev_resolve' => '解決', // 'Resolve',    # 'Resolve'
	'Class:Problem/Stimulus:ev_resolve+' => '',	     # ''
	'Class:Problem/Stimulus:ev_close' => '完了',	     # 'Close'
	'Class:Problem/Stimulus:ev_close+' => '',	     # ''
));

?>

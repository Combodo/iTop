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
 * @author      Robert Deng <denglx@gmail.com>
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




Dict::Add('ZH CN', 'Chinese', '简体中文', array(
        'Menu:ProblemManagement' => '问题管理',
        'Menu:ProblemManagement+' => '问题管理',
    	'Menu:Problem:Overview' => '概览',
    	'Menu:Problem:Overview+' => '概览',
    	'Menu:NewProblem' => '新问题',
    	'Menu:NewProblem+' => '新问题',
    	'Menu:SearchProblems' => '搜索问题',
    	'Menu:SearchProblems+' => '搜索问题',
    	'Menu:Problem:Shortcuts' => '快捷方式',
        'Menu:Problem:MyProblems' => '我的问题',
        'Menu:Problem:MyProblems+' => '我的问题',
        'Menu:Problem:OpenProblems' => '所有待解决的问题',
        'Menu:Problem:OpenProblems+' => '所有待解决的问题',
	'UI-ProblemManagementOverview-ProblemByService' => '按服务划分的问题',
	'UI-ProblemManagementOverview-ProblemByService+' => '按服务划分的问题',
	'UI-ProblemManagementOverview-ProblemByPriority' => '按优先级划分的问题',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '按优先级划分的问题',
	'UI-ProblemManagementOverview-ProblemUnassigned' => '未指派的问题',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '未指派的问题',
	'UI:ProblemMgmtMenuOverview:Title' => '问题管理仪表板',
	'UI:ProblemMgmtMenuOverview:Title+' => '问题管理仪表板',

));
//
// Class: Problem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Problem' => '问题',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => '状态',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => '新',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => '已指派',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => '已解决',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => '已关闭',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => '客户',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => '名称',
	'Class:Problem/Attribute:org_name+' => '常用名称',
	'Class:Problem/Attribute:service_id' => '服务',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => '名称',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => '服务类目',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => '名称',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => '生产',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => '影响',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => '个人',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => '服务',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => '部门',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => '紧急',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => '低',
	'Class:Problem/Attribute:urgency/Value:1+' => '低',
	'Class:Problem/Attribute:urgency/Value:2' => '中',
	'Class:Problem/Attribute:urgency/Value:2+' => '中',
	'Class:Problem/Attribute:urgency/Value:3' => '高',
	'Class:Problem/Attribute:urgency/Value:3+' => '高',
	'Class:Problem/Attribute:priority' => '优先级',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => '低',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => '中',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => '高',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => '工作组',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => '名称',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => '办理人',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => '办理人名称',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => '办理人 Email',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => '关联变更',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => '参考',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => '关闭日期',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => '最后的更新',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => '指派日期',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => '解决日期',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => '已知错误',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => '指派',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => '重新指派',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => '解决',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => '关闭',
	'Class:Problem/Stimulus:ev_close+' => '',
));

?>

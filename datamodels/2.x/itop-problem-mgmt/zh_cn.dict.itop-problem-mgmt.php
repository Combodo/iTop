<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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
	'Menu:Problem:Overview' => '概况',
	'Menu:Problem:Overview+' => '概况',
	'Menu:NewProblem' => '新建问题',
	'Menu:NewProblem+' => '新建问题',
	'Menu:SearchProblems' => '搜索问题',
	'Menu:SearchProblems+' => '搜索问题',
	'Menu:Problem:Shortcuts' => '快捷方式',
	'Menu:Problem:MyProblems' => '我的问题',
	'Menu:Problem:MyProblems+' => '我的问题',
	'Menu:Problem:OpenProblems' => '所有打开的问题',
	'Menu:Problem:OpenProblems+' => '所有打开的问题',
	'UI-ProblemManagementOverview-ProblemByService' => '按服务划分的问题',
	'UI-ProblemManagementOverview-ProblemByService+' => '按服务划分的问题',
	'UI-ProblemManagementOverview-ProblemByPriority' => '按优先级划分的问题',
	'UI-ProblemManagementOverview-ProblemByPriority+' => '按优先级划分的问题',
	'UI-ProblemManagementOverview-ProblemUnassigned' => '未分配的问题',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => '未分配的问题',
	'UI:ProblemMgmtMenuOverview:Title' => '问题管理仪表盘',
	'UI:ProblemMgmtMenuOverview:Title+' => '问题管理仪表盘',

));
//
// Class: Problem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Problem' => '问题',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => '状态',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => '新建',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => '已分配',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => '已解决',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => '已关闭',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:service_id' => '服务',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => '服务名称',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => '子服务',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => '子服务名称',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => '产品',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => '影响范围',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => '部门',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => '服务',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => '个体',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => '紧急度',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => '非常高',
	'Class:Problem/Attribute:urgency/Value:1+' => '非常高',
	'Class:Problem/Attribute:urgency/Value:2' => '高',
	'Class:Problem/Attribute:urgency/Value:2+' => '高',
	'Class:Problem/Attribute:urgency/Value:3' => '中',
	'Class:Problem/Attribute:urgency/Value:3+' => '中',
	'Class:Problem/Attribute:urgency/Value:4' => '低',
	'Class:Problem/Attribute:urgency/Value:4+' => '低',
	'Class:Problem/Attribute:priority' => '优先级',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => '非常高',
	'Class:Problem/Attribute:priority/Value:1+' => '非常高',
	'Class:Problem/Attribute:priority/Value:2' => '高',
	'Class:Problem/Attribute:priority/Value:2+' => '高',
	'Class:Problem/Attribute:priority/Value:3' => '中',
	'Class:Problem/Attribute:priority/Value:3+' => '中',
	'Class:Problem/Attribute:priority/Value:4' => '低',
	'Class:Problem/Attribute:priority/Value:4+' => '低',
	'Class:Problem/Attribute:related_change_id' => '相关变更',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => '变更编号',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:assignment_date' => '分配日期',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => '解决日期',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => '已知问题',
	'Class:Problem/Attribute:knownerrors_list+' => '该问题相关的所有已知问题',
	'Class:Problem/Attribute:related_request_list' => '相关需求',
	'Class:Problem/Attribute:related_request_list+' => '该问题相关的所有需求',
	'Class:Problem/Attribute:related_incident_list' => '相关事件',
	'Class:Problem/Attribute:related_incident_list+' => '该问题相关的所有事件',
	'Class:Problem/Stimulus:ev_assign' => '分配',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => '重新分配',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => '解决',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => '关闭',
	'Class:Problem/Stimulus:ev_close+' => '',
));

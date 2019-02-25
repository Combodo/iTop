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
// Class: Ticket
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Ticket' => '工单',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => '编号',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => '组织',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => '组织名称',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => '发起人',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => '发起人名称',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => '执行团队',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => '团队名称',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => '办理人',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => '办理人名称',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => '标题',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => '描述',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => '开始日期',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => '结束日期',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => '最后更新',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => '关闭日期',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => '私信',
	'Class:Ticket/Attribute:private_log+' => '',
    'Class:Ticket/Attribute:contacts_list' => '联系人',
	'Class:Ticket/Attribute:contacts_list+' => '该工单相关的所有联系人',
	'Class:Ticket/Attribute:functionalcis_list' => '配置项',
	'Class:Ticket/Attribute:functionalcis_list+' => '该工单相关的所有配置项.',
	'Class:Ticket/Attribute:workorders_list' => '工作任务',
	'Class:Ticket/Attribute:workorders_list+' => '该工单相关的所有工作任务',
	'Class:Ticket/Attribute:finalclass' => '类型',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => '操作状态',
	'Class:Ticket/Attribute:operational_status+' => '按具体状态',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => '进行中',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => '进行中',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => '已解决',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => '已关闭',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => '影响分析',
));


//
// Class: lnkContactToTicket
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContactToTicket' => '关联 联系人/工单',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => '工单',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => '工单编号',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => '联系人',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => '邮箱',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => '角色 (文本)',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => '角色',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => '手动添加',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => '自动添加',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => '不通知',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToTicket' => '关联 功能配置项/工单',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => '工单',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => '工单编号',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => '配置项',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => '配置项名称',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => '影响 (文本)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => '影响',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => '手动添加',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => '自动添加',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => '不通知',
));


//
// Class: WorkOrder
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WorkOrder' => '工作任务',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => '名称',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => '状态',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => '打开',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => '已关闭',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => '描述',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => '工单',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => '工单编号',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => '执行团队',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => '团队名称',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => '办理人',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => '邮箱',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => '开始日期',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => '结束日期',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => '日志',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => '关闭',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('ZH CN', 'Chinese', '简体中文', array(

	'Ticket:baseinfo' => '基本信息',
	'Ticket:date' => '日期信息',
	'Ticket:contact' => '联系人',
	'Ticket:moreinfo' => '更多信息',
	'Ticket:relation' => '相关信息',
	'Ticket:log' => '日志',
	'Ticket:Type' => '风险评估',
	'Ticket:support' => '支持信息',
	'Ticket:resolution' => '解决方案',
	'Ticket:SLA' => 'SLA 报告',
	'WorkOrder:Details' => '详情',
	'WorkOrder:Moreinfo' => '更多信息',
	'Tickets:ResolvedFrom' => '从 %1$s 自动解决',

	'Class:cmdbAbstractObject/Method:Set' => '设置',
	'Class:cmdbAbstractObject/Method:Set+' => '填写固定值',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => '值',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => '要设置的值',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => '设置为当前日期',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => '填写当前日期和时间',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => '设置为当前用户',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => '填写当前登录用户',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => '设置为当前个体',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Set a field with the currently logged in person (the "person" attached to the logged in "user").',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => '填写当前对象,如果填写字符串则是昵称.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => '设置已过时间',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Set a field with the time (seconds) elapsed since a date given by another field',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'The field to set, in the current object',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => '参考字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'The field from which to get the reference date',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => '工作时间',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Leave empty to rely on the standard working hours scheme, or set to "DefaultWorkingTimeComputer" to force a 24x7 scheme',
	'Class:cmdbAbstractObject/Method:Reset' => '重置',
	'Class:cmdbAbstractObject/Method:Reset+' => '重置为默认值',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:Copy' => '复制',
	'Class:cmdbAbstractObject/Method:Copy+' => '复制当前值到另外一个地方',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => '源字段',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => '该字段从当前对象获取值',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'ApplyStimulus',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Apply the specified stimulus to the current object',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimulus code',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'A valid stimulus code for the current class',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => '响应时间',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'SLT 的响应时间',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => '解决时间',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'SLT 的解决时间',

	'portal:itop-portal' => '标准门户', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => 'iTop - 用户门户',
	'Brick:Portal:UserProfile:Title' => '我的资料',
	'Brick:Portal:NewRequest:Title' => '新建工单',
	'Brick:Portal:NewRequest:Title+' => '<p>需要帮助?</p><p>选择子服务，然后提交工单给我们的支持团队.</p>',
	'Brick:Portal:OngoingRequests:Title' => '正在处理的工单',
	'Brick:Portal:OngoingRequests:Title+' => '<p>跟踪正在处理的工单.</p><p>查询进度, 添加留言, 添加附件, 确认解决方案.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => '正在处理',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => '已解决',
	'Brick:Portal:ClosedRequests:Title' => '已关闭的工单',
));

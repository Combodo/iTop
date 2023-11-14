<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
	'Class:Ticket/Attribute:contacts_list+' => '此工单相关的所有联系人',
	'Class:Ticket/Attribute:functionalcis_list' => '配置项',
	'Class:Ticket/Attribute:functionalcis_list+' => '此工单相关的所有配置项.',
	'Class:Ticket/Attribute:workorders_list' => '工作任务',
	'Class:Ticket/Attribute:workorders_list+' => '此工单相关的所有工作任务',
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
	'Class:lnkContactToTicket' => '关联联系人/工单',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToTicket/Attribute:ticket_id' => '工单',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => '工单编号',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => '联系人',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_name' => '联系人姓名',
	'Class:lnkContactToTicket/Attribute:contact_name+' => '~~',
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
	'Tickets:ResolvedFrom' => '由%1$s自动解决',
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
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull' => '为空则设置为当前日期',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+' => '设置空字段为当前日期和时间',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => '当前对象中要设置的字段',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => '设置为当前用户',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => '填写当前登录用户',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => '当前对象中要设置的字段. 如果此字段为字符串则使用显示名称, 否则将使用标识符. 显示名称为关联到用户的人员的姓名, 如果没有关联人员则为登录名.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => '设置为当前人员',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => '设置字段为当前登录的人员 (此 "人员" 关联到当前登录的 "用户").',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => '填写当前对象, 如果填写字符串则是昵称.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => '设置已过时间',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => '设置字段为当前时间针对另一个字段设置的日期所用时长 (秒)',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => '当前对象中要设置的字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => '参考字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => '此字段来自获取相关日期的字段',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => '工作时间',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => '若留空则取决于标准工作时间场景, 或者设置为 "DefaultWorkingTimeComputer" 来强制要求24x7场景',
	'Class:cmdbAbstractObject/Method:SetIfNull' => '为空时设置',
	'Class:cmdbAbstractObject/Method:SetIfNull+' => '仅当字段为空时设置, 使用此固定值',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+' => '当前对象里要设置的字段',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2' => '值',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+' => '要设置的值',
	'Class:cmdbAbstractObject/Method:AddValue' => '加上值',
	'Class:cmdbAbstractObject/Method:AddValue+' => '给字段加上一个固定值',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+' => '当前对象里要修改的字段',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2' => '值',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+' => '要加上的数值, 可以为负',
	'Class:cmdbAbstractObject/Method:SetComputedDate' => '设置计算的日期',
	'Class:cmdbAbstractObject/Method:SetComputedDate+' => '设置字段为按规则根据另一个字段计算的日期',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+' => '当前对象里要设置的字段',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2' => '修饰符',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+' => '要修改源日期的文本修饰符, 例如 "+3 days"',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3' => '源字段',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+' => '作为源值应用修饰符逻辑的字段',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull' => '若空则设置计算的日期',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+' => '为空时设置字段为按规则根据另一个字段计算的日期',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => '当前对象中要设置的字段',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2' => '修饰符',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => '要修改源日期的文本修饰符, 例如 "+3 days"',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3' => '源字段',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => '作为源值应用修饰符逻辑的字段',
	'Class:cmdbAbstractObject/Method:Reset' => '重置',
	'Class:cmdbAbstractObject/Method:Reset+' => '重置为默认值',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:Copy' => '复制',
	'Class:cmdbAbstractObject/Method:Copy+' => '复制当前值到另外一个地方',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => '目标字段',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => '填写当前对象',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => '源字段',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => '此字段从当前对象获取值',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => '使用激发',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => '当前对象中要应用的指定激发',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => '激发编码',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => '当前对象的合法激发编码',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'TTO',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => '响应时限',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'TTR',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => '解决时限',
));


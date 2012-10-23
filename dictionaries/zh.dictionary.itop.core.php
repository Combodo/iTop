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


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChange' => '变更',
	'Class:CMDBChange+' => '变更跟踪',
	'Class:CMDBChange/Attribute:date' => '日期',
	'Class:CMDBChange/Attribute:date+' => '变更被记录的日期和时间',
	'Class:CMDBChange/Attribute:userinfo' => '杂项. 信息',
	'Class:CMDBChange/Attribute:userinfo+' => '呼叫者已定义的信息',
));

//
// Class: CMDBChangeOp
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOp' => '变更操作',
	'Class:CMDBChangeOp+' => '变更操作跟踪',
	'Class:CMDBChangeOp/Attribute:change' => '变更',
	'Class:CMDBChangeOp/Attribute:change+' => '变更',
	'Class:CMDBChangeOp/Attribute:date' => '日期',
	'Class:CMDBChangeOp/Attribute:date+' => '变更的日期和时间',
	'Class:CMDBChangeOp/Attribute:userinfo' => '用户',
	'Class:CMDBChangeOp/Attribute:userinfo+' => '变更造成者',
	'Class:CMDBChangeOp/Attribute:objclass' => '对象类',
	'Class:CMDBChangeOp/Attribute:objclass+' => '对象类',
	'Class:CMDBChangeOp/Attribute:objkey' => '对象 id',
	'Class:CMDBChangeOp/Attribute:objkey+' => '对象 id',
	'Class:CMDBChangeOp/Attribute:finalclass' => '类别',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpCreate' => '对象创建',
	'Class:CMDBChangeOpCreate+' => '对象创建跟踪',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpDelete' => '对象删除',
	'Class:CMDBChangeOpDelete+' => '对象删除跟踪',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttribute' => '对象变更',
	'Class:CMDBChangeOpSetAttribute+' => '对象属性变更跟踪',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => '属性',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '被修改属性的编码',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeScalar' => '属性变更',
	'Class:CMDBChangeOpSetAttributeScalar+' => '对象标量属性变更跟踪',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => '原值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '属性原值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => '新值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '属性新值',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Change:ObjectCreated' => 'Object created',
	'Change:ObjectDeleted' => 'Object deleted',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s set to %2$s (previous value: %3$s)',
	'Change:AttName_SetTo' => '%1$s set to %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s appended to %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s modified, previous value: %2$s',
	'Change:AttName_Changed' => '%1$s modified',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeBlob' => '数据变更',
	'Class:CMDBChangeOpSetAttributeBlob+' => '数据变更跟踪',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '原数据',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '属性原来的内容',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeText' => '文本变更',
	'Class:CMDBChangeOpSetAttributeText+' => '文本变更跟踪',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => '原数据',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '属性原来的内容',
));

//
// Class: Event
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Event' => '记录事项',
	'Class:Event+' => '应用程序的内部事项',
	'Class:Event/Attribute:message' => '消息',
	'Class:Event/Attribute:message+' => '事项简述',
	'Class:Event/Attribute:date' => '日期',
	'Class:Event/Attribute:date+' => '变更被记录的日期和时间',
	'Class:Event/Attribute:userinfo' => '用户信息',
	'Class:Event/Attribute:userinfo+' => '用户标识, 该用户的活动触发了该事项',
	'Class:Event/Attribute:finalclass' => '类别',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventNotification' => '通知事项',
	'Class:EventNotification+' => '被发送通知的踪迹',
	'Class:EventNotification/Attribute:trigger_id' => '触发器',
	'Class:EventNotification/Attribute:trigger_id+' => '用户帐户',
	'Class:EventNotification/Attribute:action_id' => '用户',
	'Class:EventNotification/Attribute:action_id+' => '用户帐户',
	'Class:EventNotification/Attribute:object_id' => '对象 id',
	'Class:EventNotification/Attribute:object_id+' => '对象 id (由触发器定义的类 ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventNotificationEmail' => 'Email 发出事项',
	'Class:EventNotificationEmail+' => '被发送的Email的踪迹',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => 'TO',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'From',
	'Class:EventNotificationEmail/Attribute:from+' => '消息发送者',
	'Class:EventNotificationEmail/Attribute:subject' => '主题',
	'Class:EventNotificationEmail/Attribute:subject+' => '主题',
	'Class:EventNotificationEmail/Attribute:body' => '邮件体',
	'Class:EventNotificationEmail/Attribute:body+' => '邮件体',
));

//
// Class: EventIssue
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventIssue' => '议题事项',
	'Class:EventIssue+' => '议题踪迹 (警告, 错误, 等等.)',
	'Class:EventIssue/Attribute:issue' => '议题',
	'Class:EventIssue/Attribute:issue+' => '发生了什么',
	'Class:EventIssue/Attribute:impact' => '影响',
	'Class:EventIssue/Attribute:impact+' => '结果是什么',
	'Class:EventIssue/Attribute:page' => '页面',
	'Class:EventIssue/Attribute:page+' => 'HTTP 入口',
	'Class:EventIssue/Attribute:arguments_post' => 'Posted arguments',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST arguments',
	'Class:EventIssue/Attribute:arguments_get' => 'URL arguments',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET arguments',
	'Class:EventIssue/Attribute:callstack' => 'Callstack',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Data',
	'Class:EventIssue/Attribute:data+' => 'More information',
));

//
// Class: EventWebService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventWebService' => 'Web 服务事项',
	'Class:EventWebService+' => 'Web 服务调用的踪迹',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => '操作的名称',
	'Class:EventWebService/Attribute:result' => '结果',
	'Class:EventWebService/Attribute:result+' => '概览 成功/失败',
	'Class:EventWebService/Attribute:log_info' => '信息记录',
	'Class:EventWebService/Attribute:log_info+' => '结果信息记录',
	'Class:EventWebService/Attribute:log_warning' => '警告记录',
	'Class:EventWebService/Attribute:log_warning+' => '结果警告记录',
	'Class:EventWebService/Attribute:log_error' => '错误记录',
	'Class:EventWebService/Attribute:log_error+' => '结果错误记录',
	'Class:EventWebService/Attribute:data' => '数据',
	'Class:EventWebService/Attribute:data+' => '结果数据',
));

//
// Class: Action
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Action' => '客户化动作',
	'Class:Action+' => '用户定义的动作',
	'Class:Action/Attribute:name' => '名称',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => '描述',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => '状态',
	'Class:Action/Attribute:status+' => '生产中 或 ?',
	'Class:Action/Attribute:status/Value:test' => '测试中',
	'Class:Action/Attribute:status/Value:test+' => '测试中',
	'Class:Action/Attribute:status/Value:enabled' => '生产中',
	'Class:Action/Attribute:status/Value:enabled+' => '生产中',
	'Class:Action/Attribute:status/Value:disabled' => '非活动',
	'Class:Action/Attribute:status/Value:disabled+' => '非活动',
	'Class:Action/Attribute:trigger_list' => '相关触发器',
	'Class:Action/Attribute:trigger_list+' => '关联到该动作的触发器',
	'Class:Action/Attribute:finalclass' => '类别',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ActionNotification' => '通知',
	'Class:ActionNotification+' => '通知 (摘要)',
));

//
// Class: ActionEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ActionEmail' => 'Email 通知',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => '测试收件人',
	'Class:ActionEmail/Attribute:test_recipient+' => '状态被设置为"测试"时的目的地',
	'Class:ActionEmail/Attribute:from' => '来自',
	'Class:ActionEmail/Attribute:from+' => '将发送到邮件头',
	'Class:ActionEmail/Attribute:reply_to' => '回复到',
	'Class:ActionEmail/Attribute:reply_to+' => '将发送到邮件头',
	'Class:ActionEmail/Attribute:to' => 'To',
	'Class:ActionEmail/Attribute:to+' => '邮件的目的地',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => '主题',
	'Class:ActionEmail/Attribute:subject+' => '邮件标题',
	'Class:ActionEmail/Attribute:body' => '邮件体',
	'Class:ActionEmail/Attribute:body+' => '邮件内容',
	'Class:ActionEmail/Attribute:importance' => '重要',
	'Class:ActionEmail/Attribute:importance+' => '重要标志',
	'Class:ActionEmail/Attribute:importance/Value:low' => '低',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '低',
	'Class:ActionEmail/Attribute:importance/Value:normal' => '一般',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '一般',
	'Class:ActionEmail/Attribute:importance/Value:high' => '高',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '高',
));

//
// Class: Trigger
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Trigger' => '触发器',
	'Class:Trigger+' => '客户化事项句柄',
	'Class:Trigger/Attribute:description' => '描述',
	'Class:Trigger/Attribute:description+' => '单行描述',
	'Class:Trigger/Attribute:action_list' => '被触发的动作',
	'Class:Trigger/Attribute:action_list+' => '触发器击发时执行的动作',
	'Class:Trigger/Attribute:finalclass' => '类别',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObject' => '触发器 (类依赖的)',
	'Class:TriggerOnObject+' => '在一个给定类对象上的触发器',
	'Class:TriggerOnObject/Attribute:target_class' => '目标类',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateChange' => '触发器 (状态变化时)',
	'Class:TriggerOnStateChange+' => '对象状态变化时的触发器',
	'Class:TriggerOnStateChange/Attribute:state' => '状态',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateEnter' => '触发器 (进入一个状态时)',
	'Class:TriggerOnStateEnter+' => '对象状态变化时触发器 - 进入时',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateLeave' => '触发器 (离开一个状态时)',
	'Class:TriggerOnStateLeave+' => '对象状态变化时触发器 - 离开时',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectCreate' => '触发器 (对象创建时)',
	'Class:TriggerOnObjectCreate+' => '一个给定类[子类]对象创建时触发器',
));

//
// Class: lnkTriggerAction
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkTriggerAction' => '动作/触发器',
	'Class:lnkTriggerAction+' => '触发器和动作间的链接',
	'Class:lnkTriggerAction/Attribute:action_id' => '动作',
	'Class:lnkTriggerAction/Attribute:action_id+' => '要执行的动作',
	'Class:lnkTriggerAction/Attribute:action_name' => '动作',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => '触发器',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => '触发器',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => '顺序',
	'Class:lnkTriggerAction/Attribute:order+' => '动作执行顺序',
));


?>

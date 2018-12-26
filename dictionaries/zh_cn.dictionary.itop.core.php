<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Core:DeletedObjectLabel' => '%1s (已删除)',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)',

	'Core:UnknownObjectLabel' => '对象找不到 (class: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.',

	'Core:AttributeLinkedSet' => '对象数组',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass',

	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class',

	'Core:AttributeInteger' => '整数',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)',

	'Core:AttributeDecimal' => 'Decimal',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)',

	'Core:AttributeBoolean' => '布尔',
	'Core:AttributeBoolean+' => 'Boolean',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => '是',
	'Core:AttributeBoolean/Value:no' => '否',

	'Core:AttributeArchiveFlag' => '是否归档',
	'Core:AttributeArchiveFlag/Value:yes' => '是',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode',
	'Core:AttributeArchiveFlag/Value:no' => '否',
	'Core:AttributeArchiveFlag/Label' => '已归档',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => '归档日期',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => '是否废弃',
	'Core:AttributeObsolescenceFlag/Value:yes' => '是',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results',
	'Core:AttributeObsolescenceFlag/Value:no' => '否',
	'Core:AttributeObsolescenceFlag/Label' => '是否废弃',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes',
	'Core:AttributeObsolescenceDate/Label' => '废弃时间',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete',

	'Core:AttributeString' => '字符串',
	'Core:AttributeString+' => 'Alphanumeric string',

	'Core:AttributeClass' => '类',
	'Core:AttributeClass+' => 'Class',

	'Core:AttributeApplicationLanguage' => '用户语言',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)',

	'Core:AttributeFinalClass' => '类 (auto)',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

	'Core:AttributePassword' => '密码',
	'Core:AttributePassword+' => 'Password of an external device',

 	'Core:AttributeEncryptedString' => '加密字符串',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key',

	'Core:AttributeText' => '文本',
	'Core:AttributeText+' => '多行字符串',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML string',

	'Core:AttributeEmailAddress' => '邮箱地址',
	'Core:AttributeEmailAddress+' => '邮箱地址',

	'Core:AttributeIPAddress' => 'IP 地址',
	'Core:AttributeIPAddress+' => 'IP 地址',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Object Query Langage expression',

	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings',

	'Core:AttributeTemplateString' => 'Template string',
	'Core:AttributeTemplateString+' => 'String containing placeholders',

	'Core:AttributeTemplateText' => '文字模板',
	'Core:AttributeTemplateText+' => 'Text containing placeholders',

	'Core:AttributeTemplateHTML' => 'HTML 模板',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders',

	'Core:AttributeDateTime' => '日期/时间',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	日期格式:<br/>
	<b>%1$s</b><br/>
	例如: %2$s
</p>
<p>
运算符:<br/>
	<b>&gt;</b><em>日期</em><br/>
	<b>&lt;</b><em>日期</em><br/>
	<b>[</b><em>日期</em>,<em>日期</em><b>]</b>
</p>
<p>
如果不写具体时间,则默认00:00:00
</p>',

	'Core:AttributeDate' => '日期',
	'Core:AttributeDate+' => '日期 (年-月-日)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	日期格式:<br/>
	<b>%1$s</b><br/>
	例如: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>日期</em><br/>
	<b>&lt;</b><em>日期</em><br/>
	<b>[</b><em>日期</em>,<em>日期</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => '截止日期',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time',

	'Core:AttributeExternalKey' => '外键',
	'Core:AttributeExternalKey+' => 'External (or foreign) key',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Key',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent',

	'Core:AttributeExternalField' => 'External field',
	'Core:AttributeExternalField+' => 'Field mapped to an external key',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Any binary content (document)',

	'Core:AttributeOneWayPassword' => 'One way password',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password',

	'Core:AttributeTable' => 'Table',
	'Core:AttributeTable+' => 'Indexed array having two dimensions',

	'Core:AttributePropertySet' => '属性',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)',

	'Core:AttributeFriendlyName' => '通用名称',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes',

	'Core:FriendlyName-Label' => '全称',
	'Core:FriendlyName-Description' => 'Full name',
));


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
	'Class:CMDBChangeOp/Attribute:userinfo+' => '变更的实施者',
	'Class:CMDBChangeOp/Attribute:objclass' => '对象的类别',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'object class',
	'Class:CMDBChangeOp/Attribute:objkey' => '对象id',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'object id',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'CMDBChangeOp sub-class',
	'Class:CMDBChangeOp/Attribute:finalclass+' => 'Name of the final class',
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
	'Class:CMDBChangeOpSetAttribute' => '对象变化',
	'Class:CMDBChangeOpSetAttribute+' => '对象属性变化跟踪',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => '属性',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code of the modified property',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeScalar' => '属性变更',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Object scalar properties change tracking',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => '旧值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'previous value of the attribute',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => '新值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'new value of the attribute',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Change:ObjectCreated' => '对象已创建',
	'Change:ObjectDeleted' => '对象已删除',
	'Change:ObjectModified' => '对象已修改',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s 设置成 %2$s (原来的值: %3$s)',
	'Change:AttName_SetTo' => '%1$s 设置成 %2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s 追加到 %2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s 已修改, 原来的值: %2$s',
	'Change:AttName_Changed' => '%1$s 已修改',
	'Change:AttName_EntryAdded' => '%1$s 已修改, 新条目已添加: %2$s',
	'Change:LinkSet:Added' => '已添加 %1$s',
	'Change:LinkSet:Removed' => '已移除 %1$s',
	'Change:LinkSet:Modified' => '已修改 %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeBlob' => '数据修改',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'data change tracking',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '之前的值',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeText' => '文本修改',
	'Class:CMDBChangeOpSetAttributeText+' => '跟踪文本修改',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Previous data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'previous contents of the attribute',
));

//
// Class: Event
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Event' => '日志事件',
	'Class:Event+' => 'An application internal event',
	'Class:Event/Attribute:message' => '消息',
	'Class:Event/Attribute:message+' => 'short description of the event',
	'Class:Event/Attribute:date' => '日期',
	'Class:Event/Attribute:date+' => 'date and time at which the changes have been recorded',
	'Class:Event/Attribute:userinfo' => '用户信息',
	'Class:Event/Attribute:userinfo+' => 'identification of the user that was doing the action that triggered this event',
	'Class:Event/Attribute:finalclass' => 'Event sub-class',
	'Class:Event/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: EventNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventNotification' => '通知事件',
	'Class:EventNotification+' => 'Trace of a notification that has been sent',
	'Class:EventNotification/Attribute:trigger_id' => '触发器',
	'Class:EventNotification/Attribute:trigger_id+' => '用户帐户',
	'Class:EventNotification/Attribute:action_id' => '用户',
	'Class:EventNotification/Attribute:action_id+' => '用户帐户',
	'Class:EventNotification/Attribute:object_id' => '对象id',
	'Class:EventNotification/Attribute:object_id+' => 'object id (class defined by the trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventNotificationEmail' => '邮件发送事件',
	'Class:EventNotificationEmail+' => '跟踪每封已发送的邮件',
	'Class:EventNotificationEmail/Attribute:to' => '收件人',
	'Class:EventNotificationEmail/Attribute:to+' => '收件人',
	'Class:EventNotificationEmail/Attribute:cc' => '抄送',
	'Class:EventNotificationEmail/Attribute:cc+' => '抄送',
	'Class:EventNotificationEmail/Attribute:bcc' => '密抄',
	'Class:EventNotificationEmail/Attribute:bcc+' => '密抄',
	'Class:EventNotificationEmail/Attribute:from' => '发件人',
	'Class:EventNotificationEmail/Attribute:from+' => '消息发送者',
	'Class:EventNotificationEmail/Attribute:subject' => '主题',
	'Class:EventNotificationEmail/Attribute:subject+' => '主题',
	'Class:EventNotificationEmail/Attribute:body' => '内容',
	'Class:EventNotificationEmail/Attribute:body+' => '内容',
	'Class:EventNotificationEmail/Attribute:attachments' => '附件',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventIssue' => 'Issue event',
	'Class:EventIssue+' => 'Trace of an issue (warning, error, etc.)',
	'Class:EventIssue/Attribute:issue' => '事件',
	'Class:EventIssue/Attribute:issue+' => '发生了什么',
	'Class:EventIssue/Attribute:impact' => '影响',
	'Class:EventIssue/Attribute:impact+' => '重要性如何',
	'Class:EventIssue/Attribute:page' => 'Page',
	'Class:EventIssue/Attribute:page+' => 'HTTP entry point',
	'Class:EventIssue/Attribute:arguments_post' => 'Posted arguments',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST arguments',
	'Class:EventIssue/Attribute:arguments_get' => 'URL arguments',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET arguments',
	'Class:EventIssue/Attribute:callstack' => '调用栈',
	'Class:EventIssue/Attribute:callstack+' => 'Call stack',
	'Class:EventIssue/Attribute:data' => 'Data',
	'Class:EventIssue/Attribute:data+' => '更多信息',
));

//
// Class: EventWebService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventWebService' => 'Web service event',
	'Class:EventWebService+' => 'Trace of an web service call',
	'Class:EventWebService/Attribute:verb' => 'Verb',
	'Class:EventWebService/Attribute:verb+' => 'Name of the operation',
	'Class:EventWebService/Attribute:result' => 'Result',
	'Class:EventWebService/Attribute:result+' => 'Overall success/failure',
	'Class:EventWebService/Attribute:log_info' => 'Info log',
	'Class:EventWebService/Attribute:log_info+' => 'Result info log',
	'Class:EventWebService/Attribute:log_warning' => 'Warning log',
	'Class:EventWebService/Attribute:log_warning+' => 'Result warning log',
	'Class:EventWebService/Attribute:log_error' => 'Error log',
	'Class:EventWebService/Attribute:log_error+' => 'Result error log',
	'Class:EventWebService/Attribute:data' => 'Data',
	'Class:EventWebService/Attribute:data+' => 'Result data',
));

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventRestService' => 'REST/JSON 调用',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call',
	'Class:EventRestService/Attribute:operation' => '操作',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'',
	'Class:EventRestService/Attribute:version' => '版本',
	'Class:EventRestService/Attribute:version+' => '参数 \'版本\'',
	'Class:EventRestService/Attribute:json_input' => '输入',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'',
	'Class:EventRestService/Attribute:code' => '代码',
	'Class:EventRestService/Attribute:code+' => '返回代码',
	'Class:EventRestService/Attribute:json_output' => '响应',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)',
	'Class:EventRestService/Attribute:provider' => 'Provider',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation',
));

//
// Class: EventLoginUsage
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventLoginUsage' => '登录频率',
	'Class:EventLoginUsage+' => 'Connection to the application',
	'Class:EventLoginUsage/Attribute:user_id' => '登录',
	'Class:EventLoginUsage/Attribute:user_id+' => '登录',
	'Class:EventLoginUsage/Attribute:contact_name' => '用户名',
	'Class:EventLoginUsage/Attribute:contact_name+' => '用户名',
	'Class:EventLoginUsage/Attribute:contact_email' => '用户邮箱',
	'Class:EventLoginUsage/Attribute:contact_email+' => '用户的邮箱地址',
));

//
// Class: Action
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Action' => '自定义操作',
	'Class:Action+' => '用户定义的操作',
	'Class:Action/Attribute:name' => '名称',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => '描述',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => '状态',
	'Class:Action/Attribute:status+' => '是否正式环境?',
	'Class:Action/Attribute:status/Value:test' => '测试',
	'Class:Action/Attribute:status/Value:test+' => '测试',
	'Class:Action/Attribute:status/Value:enabled' => '正式',
	'Class:Action/Attribute:status/Value:enabled+' => '正式生产',
	'Class:Action/Attribute:status/Value:disabled' => '停用',
	'Class:Action/Attribute:status/Value:disabled+' => '停用',
	'Class:Action/Attribute:trigger_list' => '相关的触发器',
	'Class:Action/Attribute:trigger_list+' => '该操作关联的触发器',
	'Class:Action/Attribute:finalclass' => 'Action sub-class',
	'Class:Action/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: ActionNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ActionNotification' => '通知',
	'Class:ActionNotification+' => '通知 (抽象)',
));

//
// Class: ActionEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ActionEmail' => '邮件通知',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Test recipient',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Detination in case status is set to "Test"',
	'Class:ActionEmail/Attribute:from' => '发件人',
	'Class:ActionEmail/Attribute:from+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:reply_to' => '回复到',
	'Class:ActionEmail/Attribute:reply_to+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:to' => '收件人',
	'Class:ActionEmail/Attribute:to+' => 'Destination of the email',
	'Class:ActionEmail/Attribute:cc' => '抄送',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => '密抄',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => '主题',
	'Class:ActionEmail/Attribute:subject+' => 'Title of the email',
	'Class:ActionEmail/Attribute:body' => '正文',
	'Class:ActionEmail/Attribute:body+' => 'Contents of the email',
	'Class:ActionEmail/Attribute:importance' => '重要性',
	'Class:ActionEmail/Attribute:importance+' => 'Importance flag',
	'Class:ActionEmail/Attribute:importance/Value:low' => '低',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '低',
	'Class:ActionEmail/Attribute:importance/Value:normal' => '普通',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '普通',
	'Class:ActionEmail/Attribute:importance/Value:high' => '高',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '高',
));

//
// Class: Trigger
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Trigger' => '触发器',
	'Class:Trigger+' => 'Custom event handler',
	'Class:Trigger/Attribute:description' => '描述',
	'Class:Trigger/Attribute:description+' => 'one line description',
	'Class:Trigger/Attribute:action_list' => '触发的行为',
	'Class:Trigger/Attribute:action_list+' => 'Actions performed when the trigger is activated',
	'Class:Trigger/Attribute:finalclass' => 'Trigger sub-class',
	'Class:Trigger/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: TriggerOnObject
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObject' => '触发器 (class dependent)',
	'Class:TriggerOnObject+' => 'Trigger on a given class of objects',
	'Class:TriggerOnObject/Attribute:target_class' => '目标类',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => '过滤器',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class "%1$s"',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnPortalUpdate' => '触发器 (工单更新时)',
	'Class:TriggerOnPortalUpdate+' => '终端用户更新工单时触发',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateChange' => '触发器 (当状态变化时)',
	'Class:TriggerOnStateChange+' => '当对象状态变化时触发',
	'Class:TriggerOnStateChange/Attribute:state' => '状态',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateEnter' => '触发器 (进入指定状态)',
	'Class:TriggerOnStateEnter+' => '对象进入指定状态时触发',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateLeave' => '触发器 (离开指定状态时)',
	'Class:TriggerOnStateLeave+' => '对象离开指定状态时触发',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectCreate' => '触发器 (对象创建时)',
	'Class:TriggerOnObjectCreate+' => '对象创建时触发',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnThresholdReached' => '触发器 (基于阀值)',
	'Class:TriggerOnThresholdReached+' => '当达到某个阀值时触发',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => '阀值',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkTriggerAction' => '操作/触发器',
	'Class:lnkTriggerAction+' => 'Link between a trigger and an action',
	'Class:lnkTriggerAction/Attribute:action_id' => '操作',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'The action to be executed',
	'Class:lnkTriggerAction/Attribute:action_name' => '操作',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => '触发器',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => '触发器',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => '顺序',
	'Class:lnkTriggerAction/Attribute:order+' => '操作的执行顺序',
));

//
// Synchro Data Source
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SynchroDataSource/Attribute:name' => '名称',
	'Class:SynchroDataSource/Attribute:name+' => '名称',
	'Class:SynchroDataSource/Attribute:description' => '描述',
	'Class:SynchroDataSource/Attribute:status' => '状态', //TODO: enum values
	'Class:SynchroDataSource/Attribute:scope_class' => '目标类',
	'Class:SynchroDataSource/Attribute:user_id' => 'User',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => '要通知的人',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error',
	'Class:SynchroDataSource/Attribute:url_icon' => '图标的超链接',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink a (small) image representing the application with which iTop is synchronized',
	'Class:SynchroDataSource/Attribute:url_application' => '应用的超链接',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink to the iTop object in the external application with which iTop is synchronized (if applicable). Possible placeholders: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation policy', //TODO enum values
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A complete reload of all data must occur at least as often as specified here',
	'Class:SynchroDataSource/Attribute:action_on_zero' => '执行结果成功时',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Action taken when the search returns no object',
	'Class:SynchroDataSource/Attribute:action_on_one' => '执行结果失败时',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action taken when the search returns exactly one object',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => '执行结果未知时',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Action taken when the search returns more than one object',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => '授权用户',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Who is allowed to delete synchronized objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => '授权用户',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => '仅限管理员',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => '所有授权用户',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => '更新规则',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => '语法: field_name:value; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => '保留期限',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'How much time an obsolete object is kept before being deleted',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.',
	'SynchroDataSource:Description' => '描述',
	'SynchroDataSource:Reconciliation' => 'Search &amp; reconciliation',
	'SynchroDataSource:Deletion' => '删除规则',
	'SynchroDataSource:Status' => '状态',
	'SynchroDataSource:Information' => '信息',
	'SynchroDataSource:Definition' => '定义',
	'Core:SynchroAttributes' => '属性',
	'Core:SynchroStatus' => '状态',
	'Core:Synchro:ErrorsLabel' => 'Errors',
	'Core:Synchro:CreatedLabel' => '已创建',
	'Core:Synchro:ModifiedLabel' => '已修改',
	'Core:Synchro:UnchangedLabel' => '保持不变',
	'Core:Synchro:ReconciledErrorsLabel' => '错误',
	'Core:Synchro:ReconciledLabel' => 'Reconciled',
	'Core:Synchro:ReconciledNewLabel' => '已创建',
	'Core:SynchroReconcile:Yes' => '是',
	'Core:SynchroReconcile:No' => '否',
	'Core:SynchroUpdate:Yes' => '是',
	'Core:SynchroUpdate:No' => '否',
	'Core:Synchro:LastestStatus' => '最新状态',
	'Core:Synchro:History' => '同步历史',
	'Core:Synchro:NeverRun' => 'This synchro was never run. No log yet.',
	'Core:Synchro:SynchroEndedOn_Date' => '最近的同步发生在 %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => ' %1$s 开始的同步还在进行中...',
	'Menu:DataSources' => '同步数据源', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '所有同步数据源', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => '已忽略 (%1$s)',
	'Core:Synchro:label_repl_disappeared' => '已消失 (%1$s)',
	'Core:Synchro:label_repl_existing' => '已存在 (%1$s)',
	'Core:Synchro:label_repl_new' => '新增 (%1$s)',
	'Core:Synchro:label_obj_deleted' => '已删除 (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => '已废弃 (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => '错误 (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'No Action (%1$s)',
	'Core:Synchro:label_obj_unchanged' => '保持不变 (%1$s)',
	'Core:Synchro:label_obj_updated' => '已更新 (%1$s)', 
	'Core:Synchro:label_obj_updated_errors' => 'Errors (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => '保持不变 (%1$s)',
	'Core:Synchro:label_obj_new_updated' => '已更新 (%1$s)',
	'Core:Synchro:label_obj_created' => '已创建 (%1$s)',
	'Core:Synchro:label_obj_new_errors' => '错误 (%1$s)',
	'Core:Synchro:History' => '同步历史',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.',			
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete',			
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete objects are to be updated, but no update is specified.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'The table %1$s already exists in the database. Please use another name for the synchro data table.',
	'Core:SynchroReplica:PublicData' => 'Public Data',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details',
	'Core:SynchroReplica:BackToDataSource' => '返回同步数据源: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)',
	'Core:SynchroAtt:attcode' => '属性',
	'Core:SynchroAtt:attcode+' => 'Field of the object',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?',
	'Core:SynchroAtt:reconciliation+' => 'Used for searching',
	'Core:SynchroAtt:update' => '更新 ?',
	'Core:SynchroAtt:update+' => 'Used to update the object',
	'Core:SynchroAtt:update_policy' => '更新策略',
	'Core:SynchroAtt:update_policy+' => 'Behavior of the updated field',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribute Code for the External Key Reconciliation',
	'Core:SyncDataExchangeComment' => '(Data Synchro)',
	'Core:Synchro:ListOfDataSources' => '数据源列表:',
	'Core:Synchro:LastSynchro' => '上次同步:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'This object is synchronized with an external data source',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'The object was <b>created</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'The object <b>can be deleted</b> by the external data source %1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s',
	'TitleSynchroExecution' => 'Execution of the synchronization',
	'Class:SynchroDataSource:DataTable' => 'Database table: %1$s',
	'Core:SyncDataSourceObsolete' => '数据源已被废弃. 操作已取消.',
	'Core:SyncDataSourceAccessRestriction' => '仅数据源中指定的用户才能执行此操作. 操作已取消.',
	'Core:SyncTooManyMissingReplicas' => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).',
	'Core:SynchroReplica:TargetObject' => '已同步的对象: %1$s',
	'Class:AsyncSendEmail' => 'Email (异步)',
	'Class:AsyncSendEmail/Attribute:to' => '收件人',
	'Class:AsyncSendEmail/Attribute:subject' => '主题',
	'Class:AsyncSendEmail/Attribute:body' => '正文',
	'Class:AsyncSendEmail/Attribute:header' => 'Header',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => '加密密码',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => '之前的值',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Encrypted Field',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => '之前的值',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Last Entry',
	'Class:SynchroDataSource' => '同步数据源',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementation',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => '废弃',
	'Class:SynchroDataSource/Attribute:status/Value:production' => '生产',
	'Class:SynchroDataSource/Attribute:scope_restriction' => '范围限制',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use the attributes',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => '已创建',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => '创建',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => '选择第一项(随机?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => '删除策略',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => '删除',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => '忽略',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => '先更新再删除',
	'Class:SynchroDataSource/Attribute:attribute_list' => '属性列表',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => '仅限管理员',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Everybody allowed to delete such objects',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nobody',
	'Class:SynchroAttribute' => '同步属性',
	'Class:SynchroAttribute/Attribute:sync_source_id' => '同步数据源',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribute Code',
	'Class:SynchroAttribute/Attribute:update' => '更新',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile',
	'Class:SynchroAttribute/Attribute:update_policy' => '更新策略',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => '加锁',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => '解锁',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialize if empty',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Class',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation Attribute',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => '列的分隔符',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => '属性的分隔符',
	'Class:SynchroLog' => '同步日志',
	'Class:SynchroLog/Attribute:sync_source_id' => '同步数据源',
	'Class:SynchroLog/Attribute:start_date' => '开始日期',
	'Class:SynchroLog/Attribute:end_date' => '结束日期',
	'Class:SynchroLog/Attribute:status' => '状态',
	'Class:SynchroLog/Attribute:status/Value:completed' => '已完成',
	'Class:SynchroLog/Attribute:status/Value:error' => '错误',
	'Class:SynchroLog/Attribute:status/Value:running' => '正在运行',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica seen',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica total',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objects deleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb of errors while deleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objects obsoleted',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb of errors while obsoleting',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objects created',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb or errors while creating',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objects updated',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb errors while updating',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb of errors during reconciliation',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica disappeared',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objects updated',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objects unchanged',
	'Class:SynchroLog/Attribute:last_error' => 'Last error',
	'Class:SynchroLog/Attribute:traces' => 'Traces',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Source',
	'Class:SynchroReplica/Attribute:dest_id' => 'Destination object (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Destination type',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Last seen',
	'Class:SynchroReplica/Attribute:status' => '状态',
	'Class:SynchroReplica/Attribute:status/Value:modified' => '已修改',
	'Class:SynchroReplica/Attribute:status/Value:new' => '新建',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => '废弃',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => '已同步',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object Created ?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Last Error',
	'Class:SynchroReplica/Attribute:status_last_warning' => '警告',
	'Class:SynchroReplica/Attribute:info_creation_date' => '创建日期',
	'Class:SynchroReplica/Attribute:info_last_modified' => '最后修改日期',
	'Class:appUserPreferences' => '用户资料',
	'Class:appUserPreferences/Attribute:userid' => '用户',
	'Class:appUserPreferences/Attribute:preferences' => '首选项',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)',

	// Attribute Duration
	'Core:Duration_Seconds'	=> '%1$ds',
	'Core:Duration_Minutes_Seconds'	=>'%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as "%1$s")',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => '缺少参数 "%1$s"',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter "query". There is no Query Phrasebook corresponding to the id: "%1$s".',
	'Core:BulkExport:ExportFormatPrompt' => '导出格式:',
	'Core:BulkExportOf_Class' => '%1$s 导出',
	'Core:BulkExport:ClickHereToDownload_FileName' => '点击这里下载 %1$s',
	'Core:BulkExport:ExportResult' => '导出结果:',
	'Core:BulkExport:RetrievingData' => '正在检索数据...',
	'Core:BulkExport:HTMLFormat' => '网页 (*.html)',
	'Core:BulkExport:CSVFormat' => 'CSV (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007+ (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF 文档 (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => '可拖动或删除列头进行排序. 正在预览 %1$s 行. 一共需要导出: %2$s 行.',
	'Core:BulkExport:EmptyPreview' => '请选择要导出的列',
	'Core:BulkExport:ColumnsOrder' => '列顺序',
	'Core:BulkExport:AvailableColumnsFrom_Class' => '%1$s 属性中可用的列',
	'Core:BulkExport:NoFieldSelected' => '至少选择导出一列',
	'Core:BulkExport:CheckAll' => '全选',
	'Core:BulkExport:UncheckAll' => '反选',
	'Core:BulkExport:ExportCancelledByUser' => '导出被用户取消',
	'Core:BulkExport:CSVOptions' => 'CSV 选项',
	'Core:BulkExport:CSVLocalization' => '本地化',
	'Core:BulkExport:PDFOptions' => 'PDF 选项',
	'Core:BulkExport:PDFPageFormat' => '页面格式',
	'Core:BulkExport:PDFPageSize' => '页面大小:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => '信纸',
	'Core:BulkExport:PDFPageOrientation' => '页面方向:',
	'Core:BulkExport:PageOrientation-L' => '横向',
	'Core:BulkExport:PageOrientation-P' => '纵向',
	'Core:BulkExport:XMLFormat' => 'XML文件 (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML选项',
	'Core:BulkExport:SpreadsheetFormat' => 'HTML表单(*.html)',
	'Core:BulkExport:SpreadsheetOptions' => '表单选项',
	'Core:BulkExport:OptionNoLocalize' => '不要本地化这些值 (举的例子)',
	'Core:BulkExport:OptionLinkSets' => '包含外链的对象',
	'Core:BulkExport:OptionFormattedText' => '保持文本格式',
	'Core:BulkExport:ScopeDefinition' => '定义要导出的对象',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...',
	'Core:BulkExportCanRunNonInteractive' => '点击这里运行非交互式导出.',
	'Core:BulkExportLegacyExport' => '点击这里进入旧版导出.',
	'Core:BulkExport:XLSXOptions' => 'Excel 选项',
	'Core:BulkExport:TextFormat' => '文本中包含一些HTML 标记',
	'Core:BulkExport:DateTimeFormat' => '日期和时间格式',
	'Core:BulkExport:DateTimeFormatDefault_Example' => '默认格式 (%1$s), e.g. %2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => '自定义格式: %1$s',
	'Core:DateTime:Placeholder_d' => 'DD', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss', // seconds, 2 digits 00..59
	'Core:Validator:Default' => '格式错误',
	'Core:Validator:Mandatory' => '这里必填',
	'Core:Validator:MustBeInteger' => '必须是整数',
	'Core:Validator:MustSelectOne' => '请选择一个',
));

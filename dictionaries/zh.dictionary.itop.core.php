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
 * @author      Robert Deng <denglx@gmail.com>
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
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
// Classes: EventWebService and EventRestService
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
	'Class:EventRestService' => 'REST/JSON call~~',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call~~',
	'Class:EventRestService/Attribute:operation' => 'Operation~~',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'~~',
	'Class:EventRestService/Attribute:version' => 'Version~~',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'~~',
	'Class:EventRestService/Attribute:json_input' => 'Input~~',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'~~',
	'Class:EventRestService/Attribute:code' => 'Code~~',
	'Class:EventRestService/Attribute:code+' => 'Result code~~',
	'Class:EventRestService/Attribute:json_output' => 'Response~~',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)~~',
	'Class:EventRestService/Attribute:provider' => 'Provider~~',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation~~',
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
	'Core:DeletedObjectLabel' => '%1s (deleted)~~',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)~~',
	'Core:UnknownObjectLabel' => 'Object not found (class: %1$s, id: %2$d)~~',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.~~',
	'Core:AttributeLinkedSet' => 'Array of objects~~',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass~~',
	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)~~',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class~~',
	'Core:AttributeInteger' => 'Integer~~',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)~~',
	'Core:AttributeDecimal' => 'Decimal~~',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)~~',
	'Core:AttributeBoolean' => 'Boolean~~',
	'Core:AttributeBoolean+' => 'Boolean~~',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Yes~~',
	'Core:AttributeBoolean/Value:no' => 'No~~',
	'Core:AttributeArchiveFlag' => 'Archive flag~~',
	'Core:AttributeArchiveFlag/Value:yes' => 'Yes~~',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode~~',
	'Core:AttributeArchiveFlag/Value:no' => 'No~~',
	'Core:AttributeArchiveFlag/Label' => 'Archived~~',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archive date~~',
	'Core:AttributeArchiveDate/Label+' => '',
	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag~~',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Yes~~',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results~~',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No~~',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolete~~',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes~~',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete~~',
	'Core:AttributeString' => 'String~~',
	'Core:AttributeString+' => 'Alphanumeric string~~',
	'Core:AttributeClass' => 'Class~~',
	'Core:AttributeClass+' => 'Class~~',
	'Core:AttributeApplicationLanguage' => 'User language~~',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)~~',
	'Core:AttributeFinalClass' => 'Class (auto)~~',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)~~',
	'Core:AttributePassword' => 'Password~~',
	'Core:AttributePassword+' => 'Password of an external device~~',
	'Core:AttributeEncryptedString' => 'Encrypted string~~',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key~~',
	'Core:AttributeText' => 'Text~~',
	'Core:AttributeText+' => 'Multiline character string~~',
	'Core:AttributeHTML' => 'HTML~~',
	'Core:AttributeHTML+' => 'HTML string~~',
	'Core:AttributeEmailAddress' => 'Email address~~',
	'Core:AttributeEmailAddress+' => 'Email address~~',
	'Core:AttributeIPAddress' => 'IP address~~',
	'Core:AttributeIPAddress+' => 'IP address~~',
	'Core:AttributeOQL' => 'OQL~~',
	'Core:AttributeOQL+' => 'Object Query Langage expression~~',
	'Core:AttributeEnum' => 'Enum~~',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings~~',
	'Core:AttributeTemplateString' => 'Template string~~',
	'Core:AttributeTemplateString+' => 'String containing placeholders~~',
	'Core:AttributeTemplateText' => 'Template text~~',
	'Core:AttributeTemplateText+' => 'Text containing placeholders~~',
	'Core:AttributeTemplateHTML' => 'Template HTML~~',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders~~',
	'Core:AttributeDateTime' => 'Date/time~~',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)~~',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Date format:<br/>
	<b>%1$s</b><br/>
	Example: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
If the time is omitted, it defaults to 00:00:00
</p>~~',
	'Core:AttributeDate' => 'Date~~',
	'Core:AttributeDate+' => 'Date (year-month-day)~~',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Date format:<br/>
	<b>%1$s</b><br/>
	Example: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>~~',
	'Core:AttributeDeadline' => 'Deadline~~',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time~~',
	'Core:AttributeExternalKey' => 'External key~~',
	'Core:AttributeExternalKey+' => 'External (or foreign) key~~',
	'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent~~',
	'Core:AttributeExternalField' => 'External field~~',
	'Core:AttributeExternalField+' => 'Field mapped to an external key~~',
	'Core:AttributeURL' => 'URL~~',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string~~',
	'Core:AttributeBlob' => 'Blob~~',
	'Core:AttributeBlob+' => 'Any binary content (document)~~',
	'Core:AttributeOneWayPassword' => 'One way password~~',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password~~',
	'Core:AttributeTable' => 'Table~~',
	'Core:AttributeTable+' => 'Indexed array having two dimensions~~',
	'Core:AttributePropertySet' => 'Properties~~',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)~~',
	'Core:AttributeFriendlyName' => 'Friendly name~~',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes~~',
	'Core:FriendlyName-Label' => 'Friendly name~~',
	'Core:FriendlyName-Description' => 'Friendly name~~',
	'Change:ObjectModified' => 'Object modified~~',
	'Change:AttName_EntryAdded' => '%1$s modified, new entry added.~~',
	'Change:LinkSet:Added' => 'added %1$s~~',
	'Change:LinkSet:Removed' => 'removed %1$s~~',
	'Change:LinkSet:Modified' => 'modified %1$s~~',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Attachments~~',
	'Class:EventLoginUsage' => 'Login Usage~~',
	'Class:EventLoginUsage+' => 'Connection to the application~~',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login~~',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login~~',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name~~',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'User Name~~',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email~~',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email Address of the User~~',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \"%1$s\"~~',
	'Class:TriggerOnPortalUpdate' => 'Trigger (when updated from the portal)~~',
	'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal~~',
	'Class:TriggerOnThresholdReached' => 'Trigger (on threshold)~~',
	'Class:TriggerOnThresholdReached+' => 'Trigger on Stop-Watch threshold reached~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Threshold~~',
	'Class:SynchroDataSource/Attribute:name' => 'Name~~',
	'Class:SynchroDataSource/Attribute:name+' => 'Name~~',
	'Class:SynchroDataSource/Attribute:description' => 'Description~~',
	'Class:SynchroDataSource/Attribute:status' => 'Status~~',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target class~~',
	'Class:SynchroDataSource/Attribute:user_id' => 'User~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact to notify~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error~~',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icon\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink a (small) image representing the application with which iTop is synchronized~~',
	'Class:SynchroDataSource/Attribute:url_application' => 'Application\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink to the iTop object in the external application with which iTop is synchronized (if applicable). Possible placeholders: $this->attribute$ and $replica->primary_key$~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation policy~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A complete reload of all data must occur at least as often as specified here~~',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Action on zero~~',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Action taken when the search returns no object~~',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Action on one~~',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action taken when the search returns exactly one object~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Action on many~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Action taken when the search returns more than one object~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Users allowed~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Who is allowed to delete synchronized objects~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Administrators only~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'All allowed users~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Update rules~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: field_name:value; ...~~',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Retention Duration~~',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'How much time an obsolete object is kept before being deleted~~',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table~~',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.~~',
	'SynchroDataSource:Description' => 'Description~~',
	'SynchroDataSource:Reconciliation' => 'Search &amp; reconciliation~~',
	'SynchroDataSource:Deletion' => 'Deletion rules~~',
	'SynchroDataSource:Status' => 'Status~~',
	'SynchroDataSource:Information' => 'Information~~',
	'SynchroDataSource:Definition' => 'Definition~~',
	'Core:SynchroAttributes' => 'Attributes~~',
	'Core:SynchroStatus' => 'Status~~',
	'Core:Synchro:ErrorsLabel' => 'Errors~~',
	'Core:Synchro:CreatedLabel' => 'Created~~',
	'Core:Synchro:ModifiedLabel' => 'Modified~~',
	'Core:Synchro:UnchangedLabel' => 'Unchanged~~',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errors~~',
	'Core:Synchro:ReconciledLabel' => 'Reconciled~~',
	'Core:Synchro:ReconciledNewLabel' => 'Created~~',
	'Core:SynchroReconcile:Yes' => 'Yes~~',
	'Core:SynchroReconcile:No' => 'No~~',
	'Core:SynchroUpdate:Yes' => 'Yes~~',
	'Core:SynchroUpdate:No' => 'No~~',
	'Core:Synchro:LastestStatus' => 'Latest Status~~',
	'Core:Synchro:History' => 'Synchronization History~~',
	'Core:Synchro:NeverRun' => 'This synchro was never run. No log yet.~~',
	'Core:Synchro:SynchroEndedOn_Date' => 'The latest synchronization ended on %1$s.~~',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'The synchronization started on %1$s is still running...~~',
	'Menu:DataSources' => 'Synchronization Data Sources~~',
	'Menu:DataSources+' => 'All Synchronization Data Sources~~',
	'Core:Synchro:label_repl_ignored' => 'Ignored (%1$s)~~',
	'Core:Synchro:label_repl_disappeared' => 'Disappeared (%1$s)~~',
	'Core:Synchro:label_repl_existing' => 'Existing (%1$s)~~',
	'Core:Synchro:label_repl_new' => 'New (%1$s)~~',
	'Core:Synchro:label_obj_deleted' => 'Deleted (%1$s)~~',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleted (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errors (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_no_action' => 'No Action (%1$s)~~',
	'Core:Synchro:label_obj_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_updated' => 'Updated (%1$s)~~',
	'Core:Synchro:label_obj_updated_errors' => 'Errors (%1$s)~~',
	'Core:Synchro:label_obj_new_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_new_updated' => 'Updated (%1$s)~~',
	'Core:Synchro:label_obj_created' => 'Created (%1$s)~~',
	'Core:Synchro:label_obj_new_errors' => 'Errors (%1$s)~~',
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.~~',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete~~',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete objects are to be updated, but no update is specified.~~',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'The table %1$s already exists in the database. Please use another name for the synchro data table.~~',
	'Core:SynchroReplica:PublicData' => 'Public Data~~',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details~~',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s~~',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica~~',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)~~',
	'Core:SynchroAtt:attcode' => 'Attribute~~',
	'Core:SynchroAtt:attcode+' => 'Field of the object~~',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?~~',
	'Core:SynchroAtt:reconciliation+' => 'Used for searching~~',
	'Core:SynchroAtt:update' => 'Update ?~~',
	'Core:SynchroAtt:update+' => 'Used to update the object~~',
	'Core:SynchroAtt:update_policy' => 'Update Policy~~',
	'Core:SynchroAtt:update_policy+' => 'Behavior of the updated field~~',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key~~',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribute Code for the External Key Reconciliation~~',
	'Core:SyncDataExchangeComment' => '(Data Synchro)~~',
	'Core:Synchro:ListOfDataSources' => 'List of data sources:~~',
	'Core:Synchro:LastSynchro' => 'Last synchronization:~~',
	'Core:Synchro:ThisObjectIsSynchronized' => 'This object is synchronized with an external data source~~',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'The object was <b>created</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'The object <b>can be deleted</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s~~',
	'TitleSynchroExecution' => 'Execution of the synchronization~~',
	'Class:SynchroDataSource:DataTable' => 'Database table: %1$s~~',
	'Core:SyncDataSourceObsolete' => 'The data source is marked as obsolete. Operation cancelled.~~',
	'Core:SyncDataSourceAccessRestriction' => 'Only adminstrators or the user specified in the data source can execute this operation. Operation cancelled.~~',
	'Core:SyncTooManyMissingReplicas' => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.~~',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI~~',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).~~',
	'Core:SynchroReplica:TargetObject' => 'Synchronized Object: %1$s~~',
	'Class:AsyncSendEmail' => 'Email (asynchronous)~~',
	'Class:AsyncSendEmail/Attribute:to' => 'To~~',
	'Class:AsyncSendEmail/Attribute:subject' => 'Subject~~',
	'Class:AsyncSendEmail/Attribute:body' => 'Body~~',
	'Class:AsyncSendEmail/Attribute:header' => 'Header~~',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Encrypted Password~~',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Previous Value~~',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Encrypted Field~~',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Previous Value~~',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log~~',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Last Entry~~',
	'Class:SynchroDataSource' => 'Synchro Data Source~~',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementation~~',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsolete~~',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Production~~',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope restriction~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use the attributes~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Create~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Create~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Take the first one (random?)~~',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Delete Policy~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Delete~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignore~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update then Delete~~',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attributes List~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Administrators only~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Everybody allowed to delete such objects~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nobody~~',
	'Class:SynchroAttribute' => 'Synchro Attribute~~',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribute Code~~',
	'Class:SynchroAttribute/Attribute:update' => 'Update~~',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile~~',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Locked~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Unlocked~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialize if empty~~',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Class~~',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)~~',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation Attribute~~',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)~~',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Rows separator~~',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributes separator~~',
	'Class:SynchroLog' => 'Synchr Log~~',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroLog/Attribute:start_date' => 'Start Date~~',
	'Class:SynchroLog/Attribute:end_date' => 'End Date~~',
	'Class:SynchroLog/Attribute:status' => 'Status~~',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completed~~',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Error~~',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Still Running~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica seen~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica total~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objects deleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb of errors while deleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objects obsoleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb of errors while obsoleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objects created~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb or errors while creating~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb errors while updating~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb of errors during reconciliation~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica disappeared~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objects unchanged~~',
	'Class:SynchroLog/Attribute:last_error' => 'Last error~~',
	'Class:SynchroLog/Attribute:traces' => 'Traces~~',
	'Class:SynchroReplica' => 'Synchro Replica~~',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroReplica/Attribute:dest_id' => 'Destination object (ID)~~',
	'Class:SynchroReplica/Attribute:dest_class' => 'Destination type~~',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Last seen~~',
	'Class:SynchroReplica/Attribute:status' => 'Status~~',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modified~~',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'New~~',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsolete~~',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan~~',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronized~~',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object Created ?~~',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Last Error~~',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Warnings~~',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Creation Date~~',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Last Modified Date~~',
	'Class:appUserPreferences' => 'User Preferences~~',
	'Class:appUserPreferences/Attribute:userid' => 'User~~',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs~~',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)~~',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)~~',
	'Core:Duration_Seconds' => '%1$ds~~',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds~~',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds~~',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds~~',
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as \"%1$s\")~~',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for \"%1$s\"~~',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for \"%1$s\" at %2$d%%~~',
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter \"%1$s\"~~',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter \"query\". There is no Query Phrasebook corresponding to the id: \"%1$s\".~~',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:~~',
	'Core:BulkExportOf_Class' => '%1$s Export~~',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s~~',
	'Core:BulkExport:ExportResult' => 'Result of the export:~~',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...~~',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)~~',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)~~',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)~~',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)~~',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.~~',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above~~',
	'Core:BulkExport:ColumnsOrder' => 'Columns order~~',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s~~',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported~~',
	'Core:BulkExport:CheckAll' => 'Check All~~',
	'Core:BulkExport:UncheckAll' => 'Uncheck All~~',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user~~',
	'Core:BulkExport:CSVOptions' => 'CSV Options~~',
	'Core:BulkExport:CSVLocalization' => 'Localization~~',
	'Core:BulkExport:PDFOptions' => 'PDF Options~~',
	'Core:BulkExport:PDFPageFormat' => 'Page Format~~',
	'Core:BulkExport:PDFPageSize' => 'Page Size:~~',
	'Core:BulkExport:PageSize-A4' => 'A4~~',
	'Core:BulkExport:PageSize-A3' => 'A3~~',
	'Core:BulkExport:PageSize-Letter' => 'Letter~~',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:~~',
	'Core:BulkExport:PageOrientation-L' => 'Landscape~~',
	'Core:BulkExport:PageOrientation-P' => 'Portrait~~',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)~~',
	'Core:BulkExport:XMLOptions' => 'XML Options~~',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)~~',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options~~',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects~~',
	'Core:BulkExport:OptionNoLocalize' => 'Do not localize the values (for Enumerated fields)~~',
	'Core:BulkExport:OptionFormattedText' => 'Preserve text formatting~~',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export~~',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:~~',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.~~',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.~~',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...~~',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.~~',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.~~',
	'Core:BulkExport:XLSXOptions' => 'Excel Options~~',
	'Core:BulkExport:TextFormat' => 'Text fields containing some HTML markup~~',
	'Core:Validator:Default' => 'Wrong format~~',
	'Core:Validator:Mandatory' => 'Please, fill this field~~',
	'Core:Validator:MustBeInteger' => 'Must be an integer~~',
	'Core:Validator:MustSelectOne' => 'Please, select one~~',
));
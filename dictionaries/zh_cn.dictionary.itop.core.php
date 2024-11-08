<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
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

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Core:DeletedObjectLabel' => '%1s (已删除)',
    'Core:DeletedObjectTip' => '对象已被删除于 %1$s (%2$s)',

    'Core:UnknownObjectLabel' => '找不到对象 (class: %1$s, id: %2$d)',
    'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.~~',

    'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',
    'Core:CheckConsistencyError' => 'Consistency rules not followed: %1$s~~',
    'Core:CheckValueError' => 'Unexpected value for attribute \'%1$s\' (%2$s) : %3$s~~',

    'Core:AttributeLinkedSet' => '对象数组',
    'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass~~',

    'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicates in the \'%1$s\' field : %2$s~~',

    'Core:AttributeDashboard' => '仪表盘',
    'Core:AttributeDashboard+' => '',

    'Core:AttributePhoneNumber' => '电话号码',
    'Core:AttributePhoneNumber+' => '',

    'Core:AttributeObsolescenceDate' => '报废日期',
    'Core:AttributeObsolescenceDate+' => '',

    'Core:AttributeTagSet' => '清单',
    'Core:AttributeTagSet+' => '',
    'Core:AttributeSet:placeholder' => '请点击这里添加',
    'Core:Placeholder:CannotBeResolved' => '(%1$s : 无法解析)',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)~~',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)~~',
    'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)~~',

    'Core:AttributeCaseLog' => '日志',
    'Core:AttributeCaseLog+' => '',

    'Core:AttributeMetaEnum' => 'Computed enum~~',
    'Core:AttributeMetaEnum+' => '',

    'Core:AttributeLinkedSetIndirect' => '对象数组(N-N)',
    'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class~~',

    'Core:AttributeInteger' => '整数',
    'Core:AttributeInteger+' => '整数(可以为负)',

    'Core:AttributeDecimal' => '小数',
    'Core:AttributeDecimal+' => '小数(可以为负)',

    'Core:AttributeBoolean' => '布尔',
    'Core:AttributeBoolean+' => '',
    'Core:AttributeBoolean/Value:null' => '',
    'Core:AttributeBoolean/Value:yes' => '是',
    'Core:AttributeBoolean/Value:no' => '否',

    'Core:AttributeArchiveFlag' => '是否归档',
    'Core:AttributeArchiveFlag/Value:yes' => '是',
    'Core:AttributeArchiveFlag/Value:yes+' => '该对象仅在归档模式可见',
    'Core:AttributeArchiveFlag/Value:no' => '否',
    'Core:AttributeArchiveFlag/Label' => '已归档',
    'Core:AttributeArchiveFlag/Label+' => '',
    'Core:AttributeArchiveDate/Label' => '归档日期',
    'Core:AttributeArchiveDate/Label+' => '',

    'Core:AttributeObsolescenceFlag' => '是否废弃',
    'Core:AttributeObsolescenceFlag/Value:yes' => '是',
    'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results~~',
    'Core:AttributeObsolescenceFlag/Value:no' => '否',
    'Core:AttributeObsolescenceFlag/Label' => '是否废弃',
    'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes~~',
    'Core:AttributeObsolescenceDate/Label' => '废弃时间',
    'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete~~',

    'Core:AttributeString' => '字符串',
    'Core:AttributeString+' => '字符串',

    'Core:AttributeClass' => '类',
    'Core:AttributeClass+' => '',

    'Core:AttributeApplicationLanguage' => '用户语言',
    'Core:AttributeApplicationLanguage+' => '语言和国家/地区(EN US)',

    'Core:AttributeFinalClass' => '类 (自动)',
    'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)',

    'Core:AttributePassword' => '密码',
    'Core:AttributePassword+' => '外部设备的密码',

    'Core:AttributeEncryptedString' => '加密字符串',
    'Core:AttributeEncryptedString+' => 'String encrypted with a local key~~',
    'Core:AttributeEncryptUnknownLibrary' => '未知的加密库 (%1$s)',
    'Core:AttributeEncryptFailedToDecrypt' => '** 解密错误 **',

    'Core:AttributeText' => '文本',
    'Core:AttributeText+' => '多行字符串',

    'Core:AttributeHTML' => 'HTML',
    'Core:AttributeHTML+' => 'HTML 字符串',

    'Core:AttributeEmailAddress' => '邮箱地址',
    'Core:AttributeEmailAddress+' => 'Email 地址',

    'Core:AttributeIPAddress' => 'IP 地址',
    'Core:AttributeIPAddress+' => 'IP 地址',

    'Core:AttributeOQL' => 'OQL',
    'Core:AttributeOQL+' => 'Object Query Langage expression~~',

    'Core:AttributeEnum' => 'Enum~~',
    'Core:AttributeEnum+' => 'List of predefined alphanumeric strings~~',

    'Core:AttributeTemplateString' => '字符模板',
    'Core:AttributeTemplateString+' => '包含占位符的字符串',

    'Core:AttributeTemplateText' => '文本模板',
    'Core:AttributeTemplateText+' => '包含占位符的文本',

    'Core:AttributeTemplateHTML' => 'HTML 模板',
    'Core:AttributeTemplateHTML+' => 'HTML containing placeholders~~',

    'Core:AttributeDateTime' => '日期/时间',
    'Core:AttributeDateTime+' => 'Date and time (年-月-日 时:分:秒)',
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
    'Core:AttributeDeadline+' => '日期, 显示与当前的相对时间',

    'Core:AttributeExternalKey' => '外键',
    'Core:AttributeExternalKey+' => 'External (or foreign) key~~',

    'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
    'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent~~',

    'Core:AttributeExternalField' => '外部字段',
    'Core:AttributeExternalField+' => 'Field mapped to an external key~~',

    'Core:AttributeURL' => 'URL',
    'Core:AttributeURL+' => 'Absolute or relative URL as a text string~~',

    'Core:AttributeBlob' => 'Blob',
    'Core:AttributeBlob+' => '任何二进制内容(文档)',

    'Core:AttributeOneWayPassword' => '单向密码',
    'Core:AttributeOneWayPassword+' => '单向加密(哈希)的密码',

    'Core:AttributeTable' => '表',
    'Core:AttributeTable+' => '带索引的二维数组',

    'Core:AttributePropertySet' => '属性',
    'Core:AttributePropertySet+' => 'List of untyped properties (name and value)~~',

    'Core:AttributeFriendlyName' => '通用名称',
    'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes~~',

    'Core:FriendlyName-Label' => '全称',
    'Core:FriendlyName-Description' => '全称',

    'Core:AttributeTag' => '标签',
    'Core:AttributeTag+' => '标签',

    'Core:Context=REST/JSON' => 'REST',
    'Core:Context=Synchro' => '同步',
    'Core:Context=Setup' => '安装向导',
    'Core:Context=GUI:Console' => '命令行',
    'Core:Context=CRON' => '定时任务',
    'Core:Context=GUI:Portal' => '门户',
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
    'Class:CMDBChange/Attribute:userinfo' => '杂项.信息',
    'Class:CMDBChange/Attribute:userinfo+' => '发起人定义的信息',
    'Class:CMDBChange/Attribute:origin/Value:interactive' => '图形界面交互',
    'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => '使用脚本导入CSV',
    'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => '使用图形界面导入CSV',
    'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email processing~~',
    'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Synchro. data source~~',
    'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webservices~~',
    'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webservices~~',
    'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'By an extension~~',
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
    'Class:CMDBChangeOp/Attribute:objclass' => '对象的类型',
    'Class:CMDBChangeOp/Attribute:objclass+' => 'object class',
    'Class:CMDBChangeOp/Attribute:objkey' => '对象id',
    'Class:CMDBChangeOp/Attribute:objkey+' => '对象id',
    'Class:CMDBChangeOp/Attribute:finalclass' => 'CMDB 操作类型',
    'Class:CMDBChangeOp/Attribute:finalclass+' => '根本属性的名称',
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
    'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'code of the modified property~~',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:CMDBChangeOpSetAttributeScalar' => '属性更改跟踪',
    'Class:CMDBChangeOpSetAttributeScalar+' => '对象属性更改跟踪',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => '旧值',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '以前该属性的值',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => '新值',
    'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '属性的新值',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Change:ObjectCreated' => '对象已创建',
    'Change:ObjectDeleted' => '对象已删除',
    'Change:ObjectModified' => '对象已修改',
    'Change:TwoAttributesChanged' => '已编辑 %1$s 和 %2$s',
    'Change:ThreeAttributesChanged' => '已编辑 %1$s, %2$s 以及额外的1个',
    'Change:FourOrMoreAttributesChanged' => '已编辑 %1$s, %2$s 以及额外的 %3$s 个',
    'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s 设置成 %2$s (原来的值: %3$s)',
    'Change:AttName_SetTo' => '%1$s 设置成 %2$s',
    'Change:Text_AppendedTo_AttName' => '%1$s 追加到 %2$s',
    'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s 已修改, 原来的值: %2$s',
    'Change:AttName_Changed' => '%1$s 已修改',
    'Change:AttName_EntryAdded' => '%1$s 已修改, 新条目已添加: %2$s',
    'Change:State_Changed_NewValue_OldValue' => '从 %2$s 变为 %1$s',
    'Change:LinkSet:Added' => '已添加 %1$s',
    'Change:LinkSet:Removed' => '已移除 %1$s',
    'Change:LinkSet:Modified' => '已修改 %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:CMDBChangeOpSetAttributeBlob' => '数据变更跟踪',
    'Class:CMDBChangeOpSetAttributeBlob+' => '数据变更跟踪',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '之前的值',
    'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '该数据之前的内容',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:CMDBChangeOpSetAttributeText' => '文本变更跟踪',
    'Class:CMDBChangeOpSetAttributeText+' => '文本变更跟踪',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => '旧值',
    'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '该文本之前的内容',
));

//
// Class: Event
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:Event' => '日志事件',
    'Class:Event+' => '应用程序的内部事件',
    'Class:Event/Attribute:message' => '消息',
    'Class:Event/Attribute:message+' => 'short description of the event~~',
    'Class:Event/Attribute:date' => '日期',
    'Class:Event/Attribute:date+' => '记录的日期和时间',
    'Class:Event/Attribute:userinfo' => '用户信息',
    'Class:Event/Attribute:userinfo+' => 'identification of the user that was doing the action that triggered this event',
    'Class:Event/Attribute:finalclass' => '事件类型',
    'Class:Event/Attribute:finalclass+' => '根本属性的名称',
));

//
// Class: EventNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventNotification' => '通知事件',
    'Class:EventNotification+' => 'Trace of a notification that has been sent~~',
    'Class:EventNotification/Attribute:trigger_id' => '触发器',
    'Class:EventNotification/Attribute:trigger_id+' => '',
    'Class:EventNotification/Attribute:action_id' => '用户',
    'Class:EventNotification/Attribute:action_id+' => '',
    'Class:EventNotification/Attribute:object_id' => '对象id',
    'Class:EventNotification/Attribute:object_id+' => 'object id (class defined by the trigger ?)~~',
));

//
// Class: EventNotificationEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventNotificationEmail' => '邮件发送',
    'Class:EventNotificationEmail+' => '跟踪每封已发送的邮件',
    'Class:EventNotificationEmail/Attribute:to' => '收件人',
    'Class:EventNotificationEmail/Attribute:to+' => '',
    'Class:EventNotificationEmail/Attribute:cc' => '抄送',
    'Class:EventNotificationEmail/Attribute:cc+' => '',
    'Class:EventNotificationEmail/Attribute:bcc' => '密抄',
    'Class:EventNotificationEmail/Attribute:bcc+' => '',
    'Class:EventNotificationEmail/Attribute:from' => '发件人',
    'Class:EventNotificationEmail/Attribute:from+' => '消息发送者',
    'Class:EventNotificationEmail/Attribute:subject' => '主题',
    'Class:EventNotificationEmail/Attribute:subject+' => '',
    'Class:EventNotificationEmail/Attribute:body' => '正文',
    'Class:EventNotificationEmail/Attribute:body+' => '',
    'Class:EventNotificationEmail/Attribute:attachments' => '附件',
    'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventIssue' => 'Issue event~~',
    'Class:EventIssue+' => 'Trace of an issue (warning, error, etc.)~~',
    'Class:EventIssue/Attribute:issue' => '事件',
    'Class:EventIssue/Attribute:issue+' => '发生了什么',
    'Class:EventIssue/Attribute:impact' => '影响',
    'Class:EventIssue/Attribute:impact+' => '重要性如何',
    'Class:EventIssue/Attribute:page' => 'Page',
    'Class:EventIssue/Attribute:page+' => 'HTTP entry point~~',
    'Class:EventIssue/Attribute:arguments_post' => 'POST 参数',
    'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST 参数',
    'Class:EventIssue/Attribute:arguments_get' => 'URL 参数',
    'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET 参数',
    'Class:EventIssue/Attribute:callstack' => '调用栈',
    'Class:EventIssue/Attribute:callstack+' => '',
    'Class:EventIssue/Attribute:data' => '数据',
    'Class:EventIssue/Attribute:data+' => '更多信息',
));

//
// Class: EventWebService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventWebService' => 'Web service event~~',
    'Class:EventWebService+' => 'Trace of an web service call~~',
    'Class:EventWebService/Attribute:verb' => 'Verb~~',
    'Class:EventWebService/Attribute:verb+' => 'Name of the operation~~',
    'Class:EventWebService/Attribute:result' => '结果',
    'Class:EventWebService/Attribute:result+' => 'Overall success/failure~~',
    'Class:EventWebService/Attribute:log_info' => 'Info log~~',
    'Class:EventWebService/Attribute:log_info+' => 'Result info log~~',
    'Class:EventWebService/Attribute:log_warning' => 'Warning log~~',
    'Class:EventWebService/Attribute:log_warning+' => 'Result warning log~~',
    'Class:EventWebService/Attribute:log_error' => 'Error log~~',
    'Class:EventWebService/Attribute:log_error+' => 'Result error log~~',
    'Class:EventWebService/Attribute:data' => '数据',
    'Class:EventWebService/Attribute:data+' => 'Result data~~',
));

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventRestService' => 'REST/JSON 调用',
    'Class:EventRestService+' => 'Trace of a REST/JSON service call~~',
    'Class:EventRestService/Attribute:operation' => '操作',
    'Class:EventRestService/Attribute:operation+' => '参数 \'操作\'',
    'Class:EventRestService/Attribute:version' => '版本',
    'Class:EventRestService/Attribute:version+' => '参数 \'版本\'',
    'Class:EventRestService/Attribute:json_input' => '输入',
    'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'~~',
    'Class:EventRestService/Attribute:code' => '代码',
    'Class:EventRestService/Attribute:code+' => '返回代码',
    'Class:EventRestService/Attribute:json_output' => '响应',
    'Class:EventRestService/Attribute:json_output+' => 'HTTP 响应 (json)',
    'Class:EventRestService/Attribute:provider' => '提供者',
    'Class:EventRestService/Attribute:provider+' => '实现该功能的PHP类',
));

//
// Class: EventLoginUsage
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventLoginUsage' => '登录频率',
    'Class:EventLoginUsage+' => 'Connection to the application~~',
    'Class:EventLoginUsage/Attribute:user_id' => '登录',
    'Class:EventLoginUsage/Attribute:user_id+' => '登录',
    'Class:EventLoginUsage/Attribute:contact_name' => '用户名',
    'Class:EventLoginUsage/Attribute:contact_name+' => '用户名',
    'Class:EventLoginUsage/Attribute:contact_email' => '用户邮箱',
    'Class:EventLoginUsage/Attribute:contact_email+' => '用户的邮箱地址',
));

//
// Class: EventNotificationNewsroom
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:EventNotificationNewsroom' => 'News sent~~',
    'Class:EventNotificationNewsroom+' => '',
    'Class:EventNotificationNewsroom/Attribute:title' => '标题',
    'Class:EventNotificationNewsroom/Attribute:title+' => '',
    'Class:EventNotificationNewsroom/Attribute:icon' => '图标',
    'Class:EventNotificationNewsroom/Attribute:icon+' => '',
    'Class:EventNotificationNewsroom/Attribute:priority' => '优先级',
    'Class:EventNotificationNewsroom/Attribute:priority+' => '',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:1' => '严峻',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:1+' => '严峻',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:2' => '紧急',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:2+' => '紧急',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:3' => '重要',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:3+' => '重要',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:4' => '标准',
    'Class:EventNotificationNewsroom/Attribute:priority/Value:4+' => '标准',
    'Class:EventNotificationNewsroom/Attribute:url' => 'URL',
    'Class:EventNotificationNewsroom/Attribute:url+' => '',
    'Class:EventNotificationNewsroom/Attribute:read' => '已读',
    'Class:EventNotificationNewsroom/Attribute:read+' => '',
    'Class:EventNotificationNewsroom/Attribute:read/Value:no' => '否',
    'Class:EventNotificationNewsroom/Attribute:read/Value:no+' => '否',
    'Class:EventNotificationNewsroom/Attribute:read/Value:yes' => '是',
    'Class:EventNotificationNewsroom/Attribute:read/Value:yes+' => '是',
    'Class:EventNotificationNewsroom/Attribute:read_date' => 'Read date~~',
    'Class:EventNotificationNewsroom/Attribute:read_date+' => '',
    'Class:EventNotificationNewsroom/Attribute:contact_id' => 'Contact~~',
    'Class:EventNotificationNewsroom/Attribute:contact_id+' => '',
));

//
// Class: Action
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:Action'                                  => '自定义操作',
    'Class:Action+'                                 => '用户定义的操作',
    'Class:Action/ComplementaryName'                => '%1$s: %2$s',
    'Class:Action/Attribute:name'                   => '名称',
    'Class:Action/Attribute:name+'                  => 'Any value that is meaningful to distinguish this action from the others',
    'Class:Action/Attribute:description'            => '描述',
    'Class:Action/Attribute:description+'           => 'A longer explanation about the purpose of this action. For information only.',
    'Class:Action/Attribute:status'                 => '状态',
    'Class:Action/Attribute:status+'                => 'This status drives the action behavior',
    'Class:Action/Attribute:status/Value:test'      => '正在测试',
    'Class:Action/Attribute:status/Value:test+'     => '',
    'Class:Action/Attribute:status/Value:enabled'   => '正式',
    'Class:Action/Attribute:status/Value:enabled+'  => '',
    'Class:Action/Attribute:status/Value:disabled'  => '停用',
    'Class:Action/Attribute:status/Value:disabled+' => '',
    'Class:Action/Attribute:trigger_list' => '相关的触发器',
    'Class:Action/Attribute:trigger_list+' => 'Triggers linked to this action~~',
    'Class:Action/Attribute:asynchronous' => 'Asynchronous~~',
    'Class:Action/Attribute:asynchronous+' => 'Whether this action should be executed in background or not~~',
    'Class:Action/Attribute:asynchronous/Value:use_global_setting' => '使用全局设置',
    'Class:Action/Attribute:asynchronous/Value:yes' => '是',
    'Class:Action/Attribute:asynchronous/Value:no' => '否',
    'Class:Action/Attribute:finalclass' => 'Action sub-class~~',
    'Class:Action/Attribute:finalclass+' => 'Name of the final class~~',
    'Action:WarningNoTriggerLinked' => 'Warning, no trigger is linked to the action. It will not be active until it has at least 1.~~',
    'Action:last_executions_tab' => 'Last executions~~',
    'Action:last_executions_tab_panel_title' => 'Executions of this action (%1$s)~~',
    'Action:last_executions_tab_limit_days' => '过去 %1$s 天',
    'Action:last_executions_tab_limit_none' => '无限制',
));

//
// Class: ActionNotification
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:ActionNotification' => 'Notification Action~~',
    'Class:ActionNotification+' => 'Notification Action (abstract)~~',
    'Class:ActionNotification/Attribute:language' => '语言',
    'Class:ActionNotification/Attribute:language+' => '',
));

//
// Class: ActionEmail
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:ActionEmail'                                     => '邮件通知',
    'Class:ActionEmail+'                                    => '',
    'Class:ActionEmail/Attribute:status+'                   => 'This status drives who will be notified: 
- Being tested: just the Test recipient, 
- In production: all (To, cc and Bcc) 
- Inactive: no-one~~',
    'Class:ActionEmail/Attribute:status/Value:test+'        => '仅测试收件人会被通知',
    'Class:ActionEmail/Attribute:status/Value:enabled+'     => '通知所有人, 包含抄送和秘抄',
    'Class:ActionEmail/Attribute:status/Value:disabled+'    => 'The email notification will not be sent~~',
    'Class:ActionEmail/Attribute:test_recipient'            => '测试收件人',
    'Class:ActionEmail/Attribute:test_recipient+'           => 'Destination email address used instead of To, Cc and Bcc when notification is being tested~~',
    'Class:ActionEmail/Attribute:from'                      => 'From (email)~~',
    'Class:ActionEmail/Attribute:from+'                     => 'Either a static email address or a placeholder like $this->agent_id->email$.
The latest may not be accepted by some email servers.~~',
    'Class:ActionEmail/Attribute:from_label'                => 'From (label)~~',
    'Class:ActionEmail/Attribute:from_label+'               => 'Either a static label or a placeholder like $this->agent_id->friendlyname$',
    'Class:ActionEmail/Attribute:reply_to'                  => 'Reply to (email)~~',
    'Class:ActionEmail/Attribute:reply_to+'                 => 'Either a static email address or a placeholder like $this->team_id->email$.
If omitted the From (email) is used.~~',
    'Class:ActionEmail/Attribute:reply_to_label'            => 'Reply to (label)~~',
    'Class:ActionEmail/Attribute:reply_to_label+'           => 'Either a static label or a placeholder like $this->team_id->friendlyname$.
If omitted the From (label) is used.~~',
    'Class:ActionEmail/Attribute:to'                        => '收件人',
    'Class:ActionEmail/Attribute:to+'                       => 'To: an OQL query returning objects having an email field.
While editing, click on the magnifier to get pertinent examples.
You can use in the OQL :this->attribute_code with an attribute code of the object which triggered the Notification. Then test your OQL syntax using the play icon.',
    'Class:ActionEmail/Attribute:cc'                        => '抄送',
    'Class:ActionEmail/Attribute:cc+'                       => 'Carbon Copy: an OQL query returning objects having an email field.
While editing, click on the magnifier to get pertinent examples.
You can use in the OQL :this->attribute_code with an attribute code of the object which triggered the Notification. Then test your OQL syntax using the play icon.',
    'Class:ActionEmail/Attribute:bcc'                       => '密抄',
    'Class:ActionEmail/Attribute:bcc+'                      => 'Blind Carbon Copy: an OQL query returning objects having an email field. 
While editing, click on the magnifier to get pertinent examples',
    'Class:ActionEmail/Attribute:subject'                   => '主题',
	'Class:ActionEmail/Attribute:subject+'                 => 'Title of the email. Can contain placeholders like $this->attribute_code$',
	'Class:ActionEmail/Attribute:body'                     => '正文',
	'Class:ActionEmail/Attribute:body+'                    => 'Contents of the email. Can contain placeholders like:
- $this->attribute_code$ any attribute of the object triggering the notification,
- $this->html(attribute_code)$ same as above but displayed in html format,
- $this->hyperlink()$ hyperlink in the backoffice to the object triggering the notification,
- $this->hyperlink(portal)$ hyperlink in the portal to the object triggering the notification,
- $this->head_html(case_log_attribute)$ last reply in html format of a caselog attribute,
- $this->attribute_external_key->attribute$ recursive syntax for any remote attribute,
- $current_contact->attribute$ attribute of the Person who triggered the notification~~',
    'Class:ActionEmail/Attribute:importance'                => '重要性',
    'Class:ActionEmail/Attribute:importance+'               => 'Importance flag set on the generated email~~',
    'Class:ActionEmail/Attribute:importance/Value:low'      => '低',
    'Class:ActionEmail/Attribute:importance/Value:low+'     => '',
    'Class:ActionEmail/Attribute:importance/Value:normal'   => '普通',
    'Class:ActionEmail/Attribute:importance/Value:normal+'  => '',
    'Class:ActionEmail/Attribute:importance/Value:high'     => '高',
    'Class:ActionEmail/Attribute:importance/Value:high+'    => '',
    'Class:ActionEmail/Attribute:language'                  => '语言',
    'Class:ActionEmail/Attribute:language+'                 => 'Language to use for placeholders ($xxx$) inside the message (state, importance, priority, etc)~~',
    'Class:ActionEmail/Attribute:html_template'             => 'HTML template~~',
    'Class:ActionEmail/Attribute:html_template+'            => 'Optional HTML template wrapping around the content of the \'Body\' attribute below, useful for tailored email layouts (in the template, content of the \'Body\' attribute will replace the $content$ placeholder)~~',
    'Class:ActionEmail/Attribute:ignore_notify'             => 'Ignore the Notify flag~~',
    'Class:ActionEmail/Attribute:ignore_notify+'            => 'If set to \'Yes\' the \'Notify\' flag on Contacts has no effect.~~',
    'Class:ActionEmail/Attribute:ignore_notify/Value:no'    => '否',
    'Class:ActionEmail/Attribute:ignore_notify/Value:yes'   => '是',
    'ActionEmail:main'                                      => '消息',
    'ActionEmail:trigger'                                   => '触发器',
    'ActionEmail:recipients'                                => '联系人',
    'ActionEmail:preview_tab'                               => '预览',
    'ActionEmail:preview_tab+'                              => '预览邮件模板',
    'ActionEmail:preview_warning'                           => 'The actual eMail may look different in the eMail client than this preview in your browser.~~',
    'ActionEmail:preview_more_info'                         => 'For more information about the CSS features supported by the different eMail clients, refer to %1$s~~',
    'ActionEmail:content_placeholder_missing'               => 'The placeholder "%1$s" was not found in the HTML template. The content of the field "%2$s" will not be included in the generated emails.~~',
));


//
// Class: ActionNewsroom
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'ActionNewsroom:trigger' => '触发器',
	'ActionNewsroom:content' => '消息',
	'ActionNewsroom:settings' => '设置',
	'Class:ActionNewsroom' => 'Notification by Newsroom~~',
	'Class:ActionNewsroom+' => '',
	'Class:ActionNewsroom/Attribute:title' => '标题',
	'Class:ActionNewsroom/Attribute:title+' => 'Title of the news. Can contain placeholders like $this->attribute_code$~~',
	'Class:ActionNewsroom/Attribute:message' => '消息',
	'Class:ActionNewsroom/Attribute:message+' => 'Contents of the news, in Markdown format not HTML. Can contain placeholders like:
- $this->attribute_code$ any attribute of the object triggering the notification,
- $this->attribute_external_key->attribute$ recursive syntax for any remote attribute,
- $current_contact->attribute$ attribute of the Person who triggered the notification~~',
	'Class:ActionNewsroom/Attribute:icon' => '图标',
	'Class:ActionNewsroom/Attribute:icon+' => 'Icon to appear next to the news in the newsroom.
- If filled, the custom icon will be used
- Else the icon of the triggering object if there is one (e.g. picture of a Person),
- Else the icon of the triggering object class,
- Otherwise, the application compact logo will be used~~',
	'Class:ActionNewsroom/Attribute:priority' => '优先级',
	'Class:ActionNewsroom/Attribute:priority+' => 'News will be ordered by decreasing priority, when displayed in the Newsroom popup',
	'Class:ActionNewsroom/Attribute:priority/Value:1' => '严峻',
	'Class:ActionNewsroom/Attribute:priority/Value:1+' => '严峻',
	'Class:ActionNewsroom/Attribute:priority/Value:2' => '紧急',
	'Class:ActionNewsroom/Attribute:priority/Value:2+' => '紧急',
	'Class:ActionNewsroom/Attribute:priority/Value:3' => '重要',
	'Class:ActionNewsroom/Attribute:priority/Value:3+' => '重要',
	'Class:ActionNewsroom/Attribute:priority/Value:4' => '标准',
	'Class:ActionNewsroom/Attribute:priority/Value:4+' => '标准',
	'Class:ActionNewsroom/Attribute:test_recipient_id' => '测试收件人',
	'Class:ActionNewsroom/Attribute:test_recipient_id+' => 'Person used instead of Recipients when notification is being tested~~',
	'Class:ActionNewsroom/Attribute:recipients' => '收件人',
	'Class:ActionNewsroom/Attribute:recipients+' => 'An OQL query returning Contact objects~~',
	'Class:ActionNewsroom/Attribute:url' => 'URL',
	'Class:ActionNewsroom/Attribute:url+' => 'By default, it points to the object triggering the notification. But you can also specify a custom URL.~~',
));

//
// Class: Trigger
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Trigger'                        => '触发器',
	'Class:Trigger+'                       => 'Custom event handler~~',
	'Class:Trigger/ComplementaryName'      => '%1$s, %2$s',
	'Class:Trigger/Attribute:description'  => '描述',
	'Class:Trigger/Attribute:description+' => 'Be precise as your users will base their potential unsubscription on this information~~',
	'Class:Trigger/Attribute:action_list'  => 'Triggered actions~~',
	'Class:Trigger/Attribute:action_list+' => 'Actions performed when the trigger is activated~~',
	'Class:Trigger/Attribute:finalclass'   => 'Trigger sub-class~~',
	'Class:Trigger/Attribute:finalclass+'  => 'Name of the final class~~',
	'Class:Trigger/Attribute:context'      => '上下文',
	'Class:Trigger/Attribute:context+'     => 'Context to allow the trigger to start~~',
	'Class:Trigger/Attribute:complement'   => '其它信息',
	'Class:Trigger/Attribute:complement+'  => 'Computed automatically in english for triggers derived from TriggerOnObject~~',
	'Class:Trigger/Attribute:subscription_policy'       => 'Subscription policy~~',
	'Class:Trigger/Attribute:subscription_policy+'      => 'Allows users to unsubscribe from the trigger~~',
	'Class:Trigger/Attribute:subscription_policy/Value:allow_no_channel' => 'Allow complete unsubscription~~',
	'Class:Trigger/Attribute:subscription_policy/Value:force_at_least_one_channel' => 'Force at least one channel (News or Email)~~',
	'Class:Trigger/Attribute:subscription_policy/Value:force_all_channels' => 'Deny unsubscription~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnObject'                         => '触发器 (class dependent)',
    'Class:TriggerOnObject+'                        => 'Trigger on a given class of objects~~',
    'Class:TriggerOnObject/Attribute:target_class'  => '目标类',
    'Class:TriggerOnObject/Attribute:target_class+' => '',
    'Class:TriggerOnObject/Attribute:filter'        => '筛选器',
    'Class:TriggerOnObject/Attribute:filter+'       => 'Limit the object list (of the target class) which will activate the trigger~~',
    'TriggerOnObject:WrongFilterQuery'              => 'Wrong filter query: %1$s~~',
    'TriggerOnObject:WrongFilterClass'              => 'The filter query must return objects of class "%1$s"~~',
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
// Class: TriggerOnObjectDelete
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnObjectDelete' => '触发器 (对象删除时)',
    'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class~~',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnObjectUpdate' => '触发器 (对象更新时)',
    'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class~~',
    'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => '目标字段',
    'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnObjectMention' => 'Trigger (on object mention)~~',
    'Class:TriggerOnObjectMention+' => 'Trigger on mention (@xxx) of an object of [a child class of] the given class in a log attribute~~',
    'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Mentioned filter~~',
    'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limit the list of mentioned objects which will activate the trigger. If empty, any mentioned object (of any class) will activate it.~~',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnAttributeBlobDownload' => 'Trigger (on object\'s document download)~~',
    'Class:TriggerOnAttributeBlobDownload+' => 'Trigger on object\'s document field download of [a child class of] the given class~~',
    'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => '目标字段',
    'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TriggerOnThresholdReached' => '触发器 (基于阈值)',
    'Class:TriggerOnThresholdReached+' => '当达到某个阈值时触发',
    'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => '秒表',
    'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
    'Class:TriggerOnThresholdReached/Attribute:threshold_index' => '阈值',
    'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:lnkTriggerAction' => '操作/触发器',
    'Class:lnkTriggerAction+' => 'Link between a trigger and an action~~',
    'Class:lnkTriggerAction/Attribute:action_id' => '操作',
    'Class:lnkTriggerAction/Attribute:action_id+' => 'The action to be executed~~',
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
	'Class:SynchroDataSource'                                                       => 'Synchro Data Source~~',
	'Class:SynchroDataSource/Attribute:name'                                        => '名称',
	'Class:SynchroDataSource/Attribute:name+'                                       => '',
	'Class:SynchroDataSource/Attribute:description'                                 => '描述',
	'Class:SynchroDataSource/Attribute:status'                                      => '状态',
	'Class:SynchroDataSource/Attribute:scope_class'                                 => 'Target class~~',
	'Class:SynchroDataSource/Attribute:scope_class+'                                => 'A Synchro Data Source can only populate a single '.ITOP_APPLICATION_SHORT.' class~~',
	'Class:SynchroDataSource/Attribute:user_id'                                     => '用户',
	'Class:SynchroDataSource/Attribute:notify_contact_id'                           => 'Contact to notify~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id+'                          => 'Contact to notify in case of error~~',
	'Class:SynchroDataSource/Attribute:url_icon'                                    => 'Icon\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_icon+'                                   => 'Hyperlink a (small) image representing the application with which '.ITOP_APPLICATION_SHORT.' is synchronized.
This icon is shown in the tooltip of the “Lock” symbol on '.ITOP_APPLICATION_SHORT.' synchronized object~~',
	'Class:SynchroDataSource/Attribute:url_application'                             => 'Application\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_application+'                            => 'Hyperlink to the object in the external application corresponding to a synchronized '.ITOP_APPLICATION_SHORT.' object. 
Possible placeholders: $this->attribute$ and $replica->primary_key$.
The hyperlink is displayed in the tooltip appearing on the “Lock” symbol of any synchronized '.ITOP_APPLICATION_SHORT.' object~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy'                       => 'Reconciliation policy~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+'                      => '"Use the attributes": '.ITOP_APPLICATION_SHORT.' object matches replica values for each Synchro attributes flagged for Reconciliation.
"Use primary_key": the column primary_key of the replica is expected to contain the identifier of the '.ITOP_APPLICATION_SHORT.' object~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity'                       => 'Full load interval~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+'                      => 'A complete reload of all data must occur at least as often as specified here~~',
	'Class:SynchroDataSource/Attribute:action_on_zero'                              => 'Action on zero~~',
	'Class:SynchroDataSource/Attribute:action_on_zero+'                             => 'Action taken when the search returns no object~~',
	'Class:SynchroDataSource/Attribute:action_on_one'                               => 'Action on one~~',
	'Class:SynchroDataSource/Attribute:action_on_one+'                              => 'Action taken when the search returns exactly one object~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple'                          => 'Action on many~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple+'                         => 'Action taken when the search returns more than one object~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy'                          => 'Users allowed~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy+'                         => 'Who is allowed to delete synchronized objects~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never'                   => 'Nobody',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends'                 => 'Administrators only',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always'                  => 'All allowed users',
	'Class:SynchroDataSource/Attribute:delete_policy_update'                        => 'Update rules',
	'Class:SynchroDataSource/Attribute:delete_policy_update+'                       => 'A list of "field_name:value;":
"field_name" must be a valid field of the Target class.
"value" must be an authorised value for that field.',
	'Class:SynchroDataSource/Attribute:delete_policy_retention'                     => 'Retention Duration~~',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+'                    => 'How much time an obsolete object is kept before being deleted~~',
	'Class:SynchroDataSource/Attribute:database_table_name'                         => 'Data table~~',
	'Class:SynchroDataSource/Attribute:database_table_name+'                        => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.~~',
	'Class:SynchroDataSource/Attribute:status/Value:implementation'                 => 'Implementation~~',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete'                       => 'Obsolete~~',
	'Class:SynchroDataSource/Attribute:status/Value:production'                     => 'Production~~',
	'Class:SynchroDataSource/Attribute:scope_restriction'                           => 'Scope restriction~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes'  => 'Use the attributes~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create'                 => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error'                  => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error'                   => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update'                  => 'Update',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create'             => 'Create',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error'              => 'Error',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first'         => 'Take the first one (random?)~~',
	'Class:SynchroDataSource/Attribute:delete_policy'                               => 'Delete Policy~~',
	'Class:SynchroDataSource/Attribute:delete_policy+'                              => 'What to do when a replica becomes obsolete:
"Ignore": do nothing, the associated object remains as is in iTop.
"Delete": Delete the associated object in iTop (and the replica in the data table).
"Update": Update the associated object as specified by the Update rules (see below).
"Update then Delete": apply the "Update rules". When Retention Duration expires, execute a "Delete" ~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete'                  => 'Delete',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore'                  => 'Ignore',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update'                  => 'Update',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete'      => 'Update then Delete',
	'Class:SynchroDataSource/Attribute:attribute_list'                              => 'Attributes List~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators'     => 'Administrators only',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody'          => 'Everybody allowed to delete such objects~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody'             => 'Nobody',

	'SynchroDataSource:Description'                                         => '描述',
    'SynchroDataSource:Reconciliation'                                      => 'Search &amp; reconciliation~~',
    'SynchroDataSource:Deletion'                                            => 'Deletion rules~~',
    'SynchroDataSource:Status'                                              => '状态',
    'SynchroDataSource:Information'                                         => '基本信息',
    'SynchroDataSource:Definition'                                          => 'Definition',
    'Core:SynchroAttributes'                                                => '属性',
    'Core:SynchroStatus'                                                    => '状态',
    'Core:Synchro:ErrorsLabel'                                              => 'Errors~~',
    'Core:Synchro:CreatedLabel'                                             => 'Created~~',
    'Core:Synchro:ModifiedLabel'                                            => 'Modified~~',
    'Core:Synchro:UnchangedLabel'                                           => 'Unchanged~~',
    'Core:Synchro:ReconciledErrorsLabel'                                    => 'Errors~~',
    'Core:Synchro:ReconciledLabel'                                          => 'Reconciled~~',
    'Core:Synchro:ReconciledNewLabel'                                       => 'Created~~',
    'Core:SynchroReconcile:Yes'                                             => 'Yes',
    'Core:SynchroReconcile:No'                                              => 'No',
    'Core:SynchroUpdate:Yes'                                                => 'Yes',
    'Core:SynchroUpdate:No'                                                 => 'No',
    'Core:Synchro:LastestStatus'                                            => '最新状态',
    'Core:Synchro:History'                                                  => 'Synchronization History~~',
    'Core:Synchro:NeverRun'                                                 => 'This synchro was never run. No log yet.~~',
    'Core:Synchro:SynchroEndedOn_Date'                                      => 'The latest synchronization ended on %1$s.~~',
    'Core:Synchro:SynchroRunningStartedOn_Date'                             => 'The synchronization started on %1$s is still running...~~',
    'Core:Synchro:label_repl_ignored'                                       => 'Ignored (%1$s)',
    'Core:Synchro:label_repl_disappeared'                                   => 'Disappeared (%1$s)',
    'Core:Synchro:label_repl_existing'                                      => 'Existing (%1$s)',
    'Core:Synchro:label_repl_new'                                           => '新建 (%1$s)',
    'Core:Synchro:label_obj_deleted'                                        => '已删除 (%1$s)',
    'Core:Synchro:label_obj_obsoleted'                                      => '已废弃 (%1$s)',
    'Core:Synchro:label_obj_disappeared_errors'                             => 'Errors (%1$s)',
    'Core:Synchro:label_obj_disappeared_no_action'                          => 'No Action (%1$s)',
    'Core:Synchro:label_obj_unchanged'                                      => 'Unchanged (%1$s)',
    'Core:Synchro:label_obj_updated'                                        => 'Updated (%1$s)',
    'Core:Synchro:label_obj_updated_errors'                                 => 'Errors (%1$s)',
    'Core:Synchro:label_obj_new_unchanged'                                  => 'Unchanged (%1$s)',
    'Core:Synchro:label_obj_new_updated'                                    => 'Updated (%1$s)',
    'Core:Synchro:label_obj_created'                                        => 'Created (%1$s)',
    'Core:Synchro:label_obj_new_errors'                                     => 'Errors (%1$s)',
    'Core:SynchroLogTitle'                                                  => '%1$s - %2$s',
    'Core:Synchro:Nb_Replica'                                               => 'Replica processed: %1$s',
    'Core:Synchro:Nb_Class:Objects'                                         => '%1$s: %2$s',
    'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified'  => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.~~',
    'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified'      => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete~~',
    'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified'           => 'Obsolete objects are to be updated, but no update is specified.~~',
    'Class:SynchroDataSource/Error:DataTableAlreadyExists'                      => 'The table %1$s already exists in the database. Please use another name for the synchro data table.~~',
    'Core:SynchroReplica:PublicData'                                    => 'Public Data~~',
    'Core:SynchroReplica:PrivateDetails'                                => 'Private Details~~',
    'Core:SynchroReplica:BackToDataSource'                              => 'Go Back to the Synchro Data Source: %1$s~~',
    'Core:SynchroReplica:ListOfReplicas'                                => 'List of Replica~~',
    'Core:SynchroAttExtKey:ReconciliationById'                          => 'id (Primary Key)~~',
    'Core:SynchroAtt:attcode'                                           => '属性',
    'Core:SynchroAtt:attcode+'                                          => 'Field of the object~~',
    'Core:SynchroAtt:reconciliation'                                    => 'Reconciliation ?~~',
    'Core:SynchroAtt:reconciliation+'                                   => 'Used for searching~~',
    'Core:SynchroAtt:update'                                            => 'Update ?~~',
    'Core:SynchroAtt:update+'                                           => 'Used to update the object~~',
    'Core:SynchroAtt:update_policy'                                     => 'Update Policy~~',
    'Core:SynchroAtt:update_policy+'                                    => 'Behavior of the updated field~~',
    'Core:SynchroAtt:reconciliation_attcode'                            => 'Reconciliation Key~~',
    'Core:SynchroAtt:reconciliation_attcode+'                           => 'Attribute Code for the External Key Reconciliation~~',
    'Core:SyncDataExchangeComment'                                      => '(Data Synchro)~~',
    'Core:Synchro:ListOfDataSources'                                    => 'List of data sources:~~',
    'Core:Synchro:LastSynchro'                                          => 'Last synchronization:~~',
    'Core:Synchro:ThisObjectIsSynchronized'                             => 'This object is synchronized with an external data source~~',
    'Core:Synchro:TheObjectWasCreatedBy_Source'                         => 'The object was <b>created</b> by the external data source %1$s~~',
    'Core:Synchro:TheObjectCanBeDeletedBy_Source'                       => 'The object <b>can be deleted</b> by the external data source %1$s~~',
    'Core:Synchro:TheObjectCannotBeDeletedByUser_Source'                => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s~~',
    'TitleSynchroExecution'                                             => 'Execution of the synchronization~~',
    'Class:SynchroDataSource:DataTable'                                 => 'Database table: %1$s~~',
    'Core:SyncDataSourceObsolete'                                       => 'The data source is marked as obsolete. Operation cancelled.~~',
    'Core:SyncDataSourceAccessRestriction'                              => '仅管理员或数据源中指定的用户才能执行此操作. 操作已取消.',
    'Core:SyncTooManyMissingReplicas'                                   => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.',
    'Core:SyncSplitModeCLIOnly'                                         => 'The synchronization can be executed in chunks only if run in mode CLI~~',
    'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings'             => '%1$s replicas, %2$s error(s), %3$s warning(s).~~',
    'Core:SynchroReplica:TargetObject'                                  => 'Synchronized Object: %1$s~~',
    'Class:AsyncSendEmail'                                              => 'Email (asynchronous)~~',
    'Class:AsyncSendEmail/Attribute:to'                                 => '收件人',
    'Class:AsyncSendEmail/Attribute:subject'                            => '主题',
    'Class:AsyncSendEmail/Attribute:body'                               => '正文',
    'Class:AsyncSendEmail/Attribute:header'                             => 'Header~~',
    'Class:CMDBChangeOpSetAttributeOneWayPassword'                      => 'Encrypted Password~~',
    'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd'   => 'Previous Value~~',
    'Class:CMDBChangeOpSetAttributeEncrypted'                           => 'Encrypted Field~~',
    'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring'      => 'Previous Value~~',
    'Class:CMDBChangeOpSetAttributeCaseLog'                             => 'Case Log~~',
    'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry'         => 'Last Entry~~',
    'Class:SynchroAttribute'                                            => 'Synchro Attribute~~',
    'Class:SynchroAttribute/Attribute:sync_source_id'                   => 'Synchro Data Source~~',
    'Class:SynchroAttribute/Attribute:attcode'                          => 'Attribute Code~~',
    'Class:SynchroAttribute/Attribute:update'                           => 'Update',
    'Class:SynchroAttribute/Attribute:reconcile'                        => 'Reconcile',
    'Class:SynchroAttribute/Attribute:update_policy'                    => 'Update Policy',
    'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked'    => 'Locked',
    'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked'  => 'Unlocked',
    'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty'   => 'Initialize if empty',
    'Class:SynchroAttribute/Attribute:finalclass'                           => 'Class',
    'Class:SynchroAttExtKey'                                                => 'Synchro Attribute (ExtKey)',
    'Class:SynchroAttExtKey/Attribute:reconciliation_attcode'               => 'Reconciliation Attribute',
    'Class:SynchroAttLinkSet'                                               => 'Synchro Attribute (Linkset)',
    'Class:SynchroAttLinkSet/Attribute:row_separator'                       => 'Rows separator',
    'Class:SynchroAttLinkSet/Attribute:attribute_separator'                 => 'Attributes separator',
    'Class:SynchroLog'                                                      => 'Synchr Log',
    'Class:SynchroLog/Attribute:sync_source_id'                             => 'Synchro Data Source',
    'Class:SynchroLog/Attribute:start_date'                                 => '开始日期',
    'Class:SynchroLog/Attribute:end_date'                                   => '结束日期',
    'Class:SynchroLog/Attribute:status'                                     => '状态',
    'Class:SynchroLog/Attribute:status/Value:completed'                     => '已完成',
    'Class:SynchroLog/Attribute:status/Value:error'                         => 'Error',
    'Class:SynchroLog/Attribute:status/Value:running'                       => 'Still Running',
    'Class:SynchroLog/Attribute:stats_nb_replica_seen'                      => 'Nb replica seen',
    'Class:SynchroLog/Attribute:stats_nb_replica_total'                     => 'Nb replica total',
    'Class:SynchroLog/Attribute:stats_nb_obj_deleted'                       => 'Nb objects deleted',
    'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors'                => 'Nb of errors while deleting',
    'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted'                     => 'Nb objects obsoleted',
    'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors'              => 'Nb of errors while obsoleting',
    'Class:SynchroLog/Attribute:stats_nb_obj_created'                       => 'Nb objects created',
    'Class:SynchroLog/Attribute:stats_nb_obj_created_errors'                => 'Nb or errors while creating',
    'Class:SynchroLog/Attribute:stats_nb_obj_updated'                       => 'Nb objects updated',
    'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors'                => 'Nb errors while updating~~',
    'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors'         => 'Nb of errors during reconciliation~~',
    'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action'     => 'Nb replica disappeared~~',
    'Class:SynchroLog/Attribute:stats_nb_obj_new_updated'                   => 'Nb objects updated~~',
    'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged'                 => 'Nb objects unchanged~~',
    'Class:SynchroLog/Attribute:last_error'                 => 'Last error~~',
    'Class:SynchroLog/Attribute:traces'                     => 'Traces~~',
    'Class:SynchroReplica'                                  => 'Synchro Replica~~',
    'Class:SynchroReplica/Attribute:sync_source_id'         => 'Synchro Data Source~~',
    'Class:SynchroReplica/Attribute:dest_id'                => 'Destination object (ID)',
    'Class:SynchroReplica/Attribute:dest_class'             => 'Destination type~~',
    'Class:SynchroReplica/Attribute:status_last_seen'       => 'Last seen~~',
    'Class:SynchroReplica/Attribute:status'                 => '状态',
    'Class:SynchroReplica/Attribute:status/Value:modified'  => '已修改',
    'Class:SynchroReplica/Attribute:status/Value:new'       => '新建',
    'Class:SynchroReplica/Attribute:status/Value:obsolete'  => '废弃',
    'Class:SynchroReplica/Attribute:status/Value:orphan'    => 'Orphan',
    'Class:SynchroReplica/Attribute:status/Value:synchronized'  => '已同步',
    'Class:SynchroReplica/Attribute:status_dest_creator'        => 'Object Created ?',
    'Class:SynchroReplica/Attribute:status_last_error'          => 'Last Error',
    'Class:SynchroReplica/Attribute:status_last_warning'        => 'Warnings',
    'Class:SynchroReplica/Attribute:info_creation_date'         => '创建日期',
    'Class:SynchroReplica/Attribute:info_last_modified'         => '最后修改日期',
    'Class:appUserPreferences'                              => '用户偏好',
    'Class:appUserPreferences/Attribute:userid'             => '用户',
    'Class:appUserPreferences/Attribute:preferences'        => '首选项',
    'Core:ExecProcess:Code1'        => 'Wrong command or command finished with errors (e.g. wrong script name)~~',
    'Core:ExecProcess:Code255'      => 'PHP Error (parsing, or runtime)~~',

    // Attribute Duration
    'Core:Duration_Seconds' => '%1$ds',
    'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
    'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
    'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',

    // Explain working time computing
    'Core:ExplainWTC:ElapsedTime' => '耗时 (stored as "%1$s")',
    'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for "%1$s"~~',
    'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for "%1$s" at %2$d%%~~',

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
    'Core:BulkExport:PDFFormat' => 'PDF文档 (*.pdf)',
    'Core:BulkExport:DragAndDropHelp' => '可拖动或删除列头进行排序. 正在预览 %1$s 行. 一共需要导出: %2$s 行.',
    'Core:BulkExport:EmptyPreview' => '请选择要导出的列',
    'Core:BulkExport:ColumnsOrder' => '列顺序',
    'Core:BulkExport:AvailableColumnsFrom_Class' => '%1$s 属性中可用的列',
    'Core:BulkExport:NoFieldSelected' => '请至少选择导出一列',
    'Core:BulkExport:CheckAll' => '全选',
    'Core:BulkExport:UncheckAll' => '反选',
    'Core:BulkExport:ExportCancelledByUser' => '导出被用户取消',
    'Core:BulkExport:CSVOptions' => 'CSV选项',
    'Core:BulkExport:CSVLocalization' => '本地化',
    'Core:BulkExport:PDFOptions' => 'PDF选项',
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
    'Core:BulkExportLabelOQLExpression' => 'OQL查询:',
    'Core:BulkExportLabelPhrasebookEntry' => '来自预定义查询:',
    'Core:BulkExportMessageEmptyOQL' => '请输入有效的预定义查询.',
    'Core:BulkExportMessageEmptyPhrasebookEntry' => '请选择有效的预定义查询.',
    'Core:BulkExportQueryPlaceholder' => '请在这里输入OQL查询...',
    'Core:BulkExportCanRunNonInteractive' => '点击这里运行非交互式导出.',
    'Core:BulkExportLegacyExport' => '点击这里进入旧版导出.',
    'Core:BulkExport:XLSXOptions' => 'Excel 选项',
    'Core:BulkExport:TextFormat' => '文本中包含一些HTML 标记',
    'Core:BulkExport:DateTimeFormat' => '日期和时间格式',
    'Core:BulkExport:DateTimeFormatDefault_Example' => '默认格式 (%1$s), e.g. %2$s',
    'Core:BulkExport:DateTimeFormatCustom_Format' => '自定义格式: %1$s',
    'Core:BulkExport:PDF:PageNumber' => '第 %1$s 页',
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
    'Core:Validator:MustSelectOne' => '请选择',
));

//
// Class: TagSetFieldData
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:TagSetFieldData' => '%2$s for class %1$s~~',
    'Class:TagSetFieldData+' => '~~',

    'Class:TagSetFieldData/Attribute:code' => '代码',
    'Class:TagSetFieldData/Attribute:code+' => '内部代码. 必须至少包含3个数字或字母',
    'Class:TagSetFieldData/Attribute:label' => '标签',
    'Class:TagSetFieldData/Attribute:label+' => '显示的标签',
    'Class:TagSetFieldData/Attribute:description' => '描述',
    'Class:TagSetFieldData/Attribute:description+' => '',
    'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
    'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
    'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',

    'Core:TagSetFieldData:ErrorDeleteUsedTag' => '已使用的标签无法被删除',
    'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tags codes or labels must be unique~~',
    'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Tags code must contain between 3 and %1$d alphanumeric characters, starting with a letter.~~',
    'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'The chosen tag code is a reserved word~~',
    'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Tags label must not contain \'%1$s\' nor be empty~~',
    'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags Code cannot be changed when used~~',
    'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" cannot be changed~~',
    'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Attribute Code" cannot be changed~~',
    'Core:TagSetFieldData:WhereIsThisTagTab' => 'Tag usage (%1$d)~~',
    'Core:TagSetFieldData:NoEntryFound' => 'No entry found for this tag~~',
));

//
// Class: DBProperty
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:DBProperty' => '数据库属性',
    'Class:DBProperty+' => '~~',
    'Class:DBProperty/Attribute:name' => '名称',
    'Class:DBProperty/Attribute:name+' => '',
    'Class:DBProperty/Attribute:description' => '描述',
    'Class:DBProperty/Attribute:description+' => '',
    'Class:DBProperty/Attribute:value' => '值',
    'Class:DBProperty/Attribute:value+' => '',
    'Class:DBProperty/Attribute:change_date' => '修改日期',
    'Class:DBProperty/Attribute:change_date+' => '',
    'Class:DBProperty/Attribute:change_comment' => '备注',
    'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:BackgroundTask' => '后台任务',
    'Class:BackgroundTask+' => '',
    'Class:BackgroundTask/Attribute:class_name' => 'Class name~~',
    'Class:BackgroundTask/Attribute:class_name+' => '~~',
    'Class:BackgroundTask/Attribute:first_run_date' => '首次运行时间',
    'Class:BackgroundTask/Attribute:first_run_date+' => '',
    'Class:BackgroundTask/Attribute:latest_run_date' => '最近运行时间',
    'Class:BackgroundTask/Attribute:latest_run_date+' => '',
    'Class:BackgroundTask/Attribute:next_run_date' => '下次运行时间',
    'Class:BackgroundTask/Attribute:next_run_date+' => '',
    'Class:BackgroundTask/Attribute:total_exec_count' => '总共执行的次数',
    'Class:BackgroundTask/Attribute:total_exec_count+' => '',
    'Class:BackgroundTask/Attribute:latest_run_duration' => '最近运行时长',
    'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
    'Class:BackgroundTask/Attribute:min_run_duration' => '最少运行时长',
    'Class:BackgroundTask/Attribute:min_run_duration+' => '',
    'Class:BackgroundTask/Attribute:max_run_duration' => '最多运行时长',
    'Class:BackgroundTask/Attribute:max_run_duration+' => '',
    'Class:BackgroundTask/Attribute:average_run_duration' => '平均运行时长',
    'Class:BackgroundTask/Attribute:average_run_duration+' => '',
    'Class:BackgroundTask/Attribute:running' => '运行中',
    'Class:BackgroundTask/Attribute:running+' => '',
    'Class:BackgroundTask/Attribute:status' => '状态',
    'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:AsyncTask' => '异步任务',
    'Class:AsyncTask+' => '',
    'Class:AsyncTask/Attribute:created' => '已创建',
    'Class:AsyncTask/Attribute:created+' => '',
    'Class:AsyncTask/Attribute:started' => '已开始',
    'Class:AsyncTask/Attribute:started+' => '',
    'Class:AsyncTask/Attribute:planned' => '已计划',
    'Class:AsyncTask/Attribute:planned+' => '',
    'Class:AsyncTask/Attribute:event_id' => '事件',
    'Class:AsyncTask/Attribute:event_id+' => '',
    'Class:AsyncTask/Attribute:finalclass' => '类型',
    'Class:AsyncTask/Attribute:finalclass+' => '',
    'Class:AsyncTask/Attribute:status' => '状态',
    'Class:AsyncTask/Attribute:status+' => '~~',
    'Class:AsyncTask/Attribute:remaining_retries' => '剩余重试次数',
    'Class:AsyncTask/Attribute:remaining_retries+' => '~~',
    'Class:AsyncTask/Attribute:last_error_code' => 'Last error code~~',
    'Class:AsyncTask/Attribute:last_error_code+' => '~~',
    'Class:AsyncTask/Attribute:last_error' => 'Last error~~',
    'Class:AsyncTask/Attribute:last_error+' => '~~',
    'Class:AsyncTask/Attribute:last_attempt' => 'Last attempt~~',
    'Class:AsyncTask/Attribute:last_attempt+' => '~~',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]". Expecting an array with the following keys: %2$s~~',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]": unexpected key "%2$s". Expecting only the following keys: %3$s~~',
));

//
// Class: AbstractResource
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:AbstractResource' => '抽象资源',
    'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:ResourceAdminMenu' => '资源管理菜单',
    'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:ResourceRunQueriesMenu' => 'Resource Run Queries Menu~~',
    'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'Class:ResourceSystemMenu' => 'Resource System Menu~~',
    'Class:ResourceSystemMenu+' => '',
));




// Additional language entries not present in English dict
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
    'INTERNAL:JQuery-DatePicker:LangCode' => 'zh-CN'
));

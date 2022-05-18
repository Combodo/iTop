<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
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
//////////////////////////////////////////////////////////////////////
// general ruls followed for Chinese translation
// 1. use Chinese punctuation
//		for example "this is english." -> "这是中文。", "again, and again" -> "一次，又一次"
//		"\"%1$s\" is not allowed" -> "“%1$s”是不允许的" (no space between %1$s and the following word)
// 1. no space between words
//		for example "this is english" -> "这是中文"
//		"%1$s is not allowed" -> "%1$s是不允许的" (no space between %1$s and the following word)
// 2. use double quotation marks (") to quote word instead of single quotation marks ('), whenever possible
//		for example "this is 'english'" -> "这是\"中文\""
//		"'%1$s' is not allowed" -> "\"%1$s\"是不允许的" (" to quote instead of ')
// 9. follow Microsoft conversion, whenever possible
//		for example "New" -> "新建",  "Create" -> "创建"
//		"Edit" -> "编辑", "Update" -> "更新", "Modify" -> "修改"
//////////////////////////////////////////////////////////////////////
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Core:DeletedObjectLabel' => '%1s（已删除）',
	'Core:DeletedObjectTip' => '此对象已经被删除位于%1$s（%2$s）',

	'Core:UnknownObjectLabel' => '找不到对象（类：%1$s，id：%2$d）',
	'Core:UnknownObjectTip' => '找不到对象。其可能已经被删除了一段时间了并且日志已经被随之清楚了。',

	'Core:UniquenessDefaultError' => '唯一性规则“%1$s"错误',

	'Core:AttributeLinkedSet' => '对象数组',
	'Core:AttributeLinkedSet+' => '同一类或子类的任何对象',

	'Core:AttributeLinkedSetDuplicatesFound' => '在“%1$s”字段中有重复：%2$s',

	'Core:AttributeDashboard' => '仪表盘',
	'Core:AttributeDashboard+' => '',

	'Core:AttributePhoneNumber' => '电话号码',
	'Core:AttributePhoneNumber+' => '',

	'Core:AttributeObsolescenceDate' => '报废日期',
	'Core:AttributeObsolescenceDate+' => '',

	'Core:AttributeTagSet' => '清单',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => '请点击这里添加',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s（%2$s）',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s（%2$s来自%3$s）',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s（%2$s来自子类）',

	'Core:AttributeCaseLog' => '日志',
	'Core:AttributeCaseLog+' => '',

	'Core:AttributeMetaEnum' => '计算的枚举',
	'Core:AttributeMetaEnum+' => '',

	'Core:AttributeLinkedSetIndirect' => '对象数组（N-N）',
	'Core:AttributeLinkedSetIndirect+' => '任何同类的子类对象',

	'Core:AttributeInteger' => '整数',
	'Core:AttributeInteger+' => '整数值（可以为负）',

	'Core:AttributeDecimal' => '小数',
	'Core:AttributeDecimal+' => '小数（可以为负）',

	'Core:AttributeBoolean' => '布尔',
	'Core:AttributeBoolean+' => '布尔',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => '是',
	'Core:AttributeBoolean/Value:no' => '否',

	'Core:AttributeArchiveFlag' => '是否归档',
	'Core:AttributeArchiveFlag/Value:yes' => '是',
	'Core:AttributeArchiveFlag/Value:yes+' => '此对象仅在归档模式可见',
	'Core:AttributeArchiveFlag/Value:no' => '否',
	'Core:AttributeArchiveFlag/Label' => '已归档',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => '归档日期',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => '是否废弃',
	'Core:AttributeObsolescenceFlag/Value:yes' => '是',
	'Core:AttributeObsolescenceFlag/Value:yes+' => '此对象排除在影响度分析之外，也不在搜索结果中显示。',
	'Core:AttributeObsolescenceFlag/Value:no' => '否',
	'Core:AttributeObsolescenceFlag/Label' => '是否废弃',
	'Core:AttributeObsolescenceFlag/Label+' => '基于其他属性动态计算',
	'Core:AttributeObsolescenceDate/Label' => '废弃时间',
	'Core:AttributeObsolescenceDate/Label+' => '此对象被认为废弃的大致时间',

	'Core:AttributeString' => '字符串',
	'Core:AttributeString+' => '字符串',

	'Core:AttributeClass' => '类',
	'Core:AttributeClass+' => '类别',

	'Core:AttributeApplicationLanguage' => '用户语言',
	'Core:AttributeApplicationLanguage+' => '语言和国家地区(EN US)',

	'Core:AttributeFinalClass' => '类（自动）',
	'Core:AttributeFinalClass+' => '对象的真正类（核心自动创建）',

	'Core:AttributePassword' => '密码',
	'Core:AttributePassword+' => '外部设备的密码',

	'Core:AttributeEncryptedString' => '加密字符串',
	'Core:AttributeEncryptedString+' => '使用本地key加密的字符串',
	'Core:AttributeEncryptUnknownLibrary' => '未知的加密库（%1$s）',
	'Core:AttributeEncryptFailedToDecrypt' => '**解密错误**',

	'Core:AttributeText' => '文本',
	'Core:AttributeText+' => '多行字符串',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML字符串',

	'Core:AttributeEmailAddress' => '邮箱',
	'Core:AttributeEmailAddress+' => '邮箱',

	'Core:AttributeIPAddress' => 'IP地址',
	'Core:AttributeIPAddress+' => 'IP地址',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => '对象查询语言表达式',

	'Core:AttributeEnum' => '枚举',
	'Core:AttributeEnum+' => '预定义的字符串列表',

	'Core:AttributeTemplateString' => '字符模板',
	'Core:AttributeTemplateString+' => '包含占位符的字符串',

	'Core:AttributeTemplateText' => '文字模板',
	'Core:AttributeTemplateText+' => '包含占位符的文本',

	'Core:AttributeTemplateHTML' => 'HTML模板',
	'Core:AttributeTemplateHTML+' => 'HTML包含占位符',

	'Core:AttributeDateTime' => '日期/时间',
	'Core:AttributeDateTime+' => '日期和时间（年-月-日 时:分:秒）',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	日期格式：<br/>
	<b>%1$s</b><br/>
	例如：%2$s
</p>
<p>
运算符：<br/>
	<b>&gt;</b><em>日期</em><br/>
	<b>&lt;</b><em>日期</em><br/>
	<b>[</b><em>日期</em>，<em>日期</em><b>]</b>
</p>
<p>
如果不写具体时间,则默认00:00:00
</p>',

	'Core:AttributeDate' => '日期',
	'Core:AttributeDate+' => '日期（年-月-日）',
	'Core:AttributeDate?SmartSearch' => '
<p>
	日期格式：<br/>
	<b>%1$s</b><br/>
	例如：%2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>日期</em><br/>
	<b>&lt;</b><em>日期</em><br/>
	<b>[</b><em>日期</em>，<em>日期</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => '截止日期',
	'Core:AttributeDeadline+' => '日期，显示与当前的相对时间',

	'Core:AttributeExternalKey' => '外键',
	'Core:AttributeExternalKey+' => '外键',

	'Core:AttributeHierarchicalKey' => '层级键',
	'Core:AttributeHierarchicalKey+' => '指向父级的外键',

	'Core:AttributeExternalField' => '外部字段',
	'Core:AttributeExternalField+' => '映射道外键的字段',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '文本字符串的相对或绝对URL',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '任何二进制内容（文档）',

	'Core:AttributeOneWayPassword' => '单向密码',
	'Core:AttributeOneWayPassword+' => '单向加密（或哈希）的密码',

	'Core:AttributeTable' => '表',
	'Core:AttributeTable+' => '有索引的两维数组',

	'Core:AttributePropertySet' => '属性',
	'Core:AttributePropertySet+' => '无类属性列表（名称和值）',

	'Core:AttributeFriendlyName' => '通用名称',
	'Core:AttributeFriendlyName+' => '自动创建的属性；通用名称基于多个属性自动计算',

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
	'Class:CMDBChange/Attribute:userinfo' => '杂项信息',
	'Class:CMDBChange/Attribute:userinfo+' => '已定义的报告人信息',
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'GUI中的用户交互',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV导入脚本',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'GUI中的CSV导入',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => '电子邮件处理',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => '同步数据源',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON网络服务',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP网络服务',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => '外部扩展',
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
	'Class:CMDBChangeOp/Attribute:objclass' => '对象的类',
	'Class:CMDBChangeOp/Attribute:objclass+' => '对象的类',
	'Class:CMDBChangeOp/Attribute:objkey' => '对象id',
	'Class:CMDBChangeOp/Attribute:objkey+' => '对象id',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'CMDBChangeOp子类',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '类名称',
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
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '修改的属性编码',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeScalar' => '属性更改跟踪',
	'Class:CMDBChangeOpSetAttributeScalar+' => '对象属性更改跟踪',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => '旧值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '此属性的旧值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => '新值',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '属性的新值',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Change:ObjectCreated' => '对象已创建',
	'Change:ObjectDeleted' => '对象已删除',
	'Change:ObjectModified' => '对象已修改',
	'Change:TwoAttributesChanged' => '已编辑%1$s和%2$s',
	'Change:ThreeAttributesChanged' => '已编辑%1$s，%2$s以及额外的1个',
	'Change:FourOrMoreAttributesChanged' => '已编辑%1$s，%2$s以及额外的%3$s个',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s设置成%2$s（原来的值：%3$s）',
	'Change:AttName_SetTo' => '%1$s设置成%2$s',
	'Change:Text_AppendedTo_AttName' => '%1$s追加到%2$s',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s已修改，原来的值：%2$s',
	'Change:AttName_Changed' => '%1$s已修改',
	'Change:AttName_EntryAdded' => '%1$s已修改，新条目已添加：%2$s',
	'Change:State_Changed_NewValue_OldValue' => '从%2$s变为%1$s',
	'Change:LinkSet:Added' => '已添加%1$s',
	'Change:LinkSet:Removed' => '已移除%1$s',
	'Change:LinkSet:Modified' => '已修改%1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeBlob' => '数据变更跟踪',
	'Class:CMDBChangeOpSetAttributeBlob+' => '数据变更跟踪',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '之前的值',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '此数据之前的内容',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:CMDBChangeOpSetAttributeText' => '文本变更跟踪',
	'Class:CMDBChangeOpSetAttributeText+' => '文本变更跟踪',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => '旧值',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => '此文本之前的内容',
));

//
// Class: Event
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Event' => '日志事件',
	'Class:Event+' => '应用程序的内部事件',
	'Class:Event/Attribute:message' => '消息',
	'Class:Event/Attribute:message+' => '事件的简短描述',
	'Class:Event/Attribute:date' => '日期',
	'Class:Event/Attribute:date+' => '记录的日期和时间',
	'Class:Event/Attribute:userinfo' => '用户信息',
	'Class:Event/Attribute:userinfo+' => '进行操作或触发事件的用户身份',
	'Class:Event/Attribute:finalclass' => '事件子类',
	'Class:Event/Attribute:finalclass+' => '类名称',
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
	'Class:EventNotification/Attribute:object_id+' => '对象id（触发器定义的类？）',
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
	'Class:EventIssue' => '问题事件',
	'Class:EventIssue+' => '问题的追踪（告警，错误等）',
	'Class:EventIssue/Attribute:issue' => '事件',
	'Class:EventIssue/Attribute:issue+' => '发生了什么',
	'Class:EventIssue/Attribute:impact' => '影响',
	'Class:EventIssue/Attribute:impact+' => '重要性如何',
	'Class:EventIssue/Attribute:page' => '页面',
	'Class:EventIssue/Attribute:page+' => 'HTTP入口',
	'Class:EventIssue/Attribute:arguments_post' => 'POST参数',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST参数',
	'Class:EventIssue/Attribute:arguments_get' => 'URL参数',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET参数',
	'Class:EventIssue/Attribute:callstack' => '调用栈',
	'Class:EventIssue/Attribute:callstack+' => '调用栈',
	'Class:EventIssue/Attribute:data' => '数据',
	'Class:EventIssue/Attribute:data+' => '更多信息',
));

//
// Class: EventWebService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventWebService' => '网络服务事件',
	'Class:EventWebService+' => '网络服务调用的追踪',
	'Class:EventWebService/Attribute:verb' => '操作',
	'Class:EventWebService/Attribute:verb+' => '操作的名称',
	'Class:EventWebService/Attribute:result' => '结果',
	'Class:EventWebService/Attribute:result+' => '总体成功/失败',
	'Class:EventWebService/Attribute:log_info' => '信息日志',
	'Class:EventWebService/Attribute:log_info+' => '结果信息日志',
	'Class:EventWebService/Attribute:log_warning' => '告警日志',
	'Class:EventWebService/Attribute:log_warning+' => '结果告警日志',
	'Class:EventWebService/Attribute:log_error' => '错误日志',
	'Class:EventWebService/Attribute:log_error+' => '结果错误日志',
	'Class:EventWebService/Attribute:data' => '数据',
	'Class:EventWebService/Attribute:data+' => '结果数据',
));

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventRestService' => 'REST/JSON调用',
	'Class:EventRestService+' => 'REST/JSON服务调用的追踪',
	'Class:EventRestService/Attribute:operation' => '操作',
	'Class:EventRestService/Attribute:operation+' => '参数“操作”',
	'Class:EventRestService/Attribute:version' => '版本',
	'Class:EventRestService/Attribute:version+' => '参数“版本”',
	'Class:EventRestService/Attribute:json_input' => '输入',
	'Class:EventRestService/Attribute:json_input+' => '参数“json_data”',
	'Class:EventRestService/Attribute:code' => '代码',
	'Class:EventRestService/Attribute:code+' => '返回代码',
	'Class:EventRestService/Attribute:json_output' => '响应',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP响应（json）',
	'Class:EventRestService/Attribute:provider' => '提供者',
	'Class:EventRestService/Attribute:provider+' => '实现此预期操作的PHP类',
));

//
// Class: EventLoginUsage
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:EventLoginUsage' => '登录频率',
	'Class:EventLoginUsage+' => '应用连接',
	'Class:EventLoginUsage/Attribute:user_id' => '登录',
	'Class:EventLoginUsage/Attribute:user_id+' => '登录',
	'Class:EventLoginUsage/Attribute:contact_name' => '用户名',
	'Class:EventLoginUsage/Attribute:contact_name+' => '用户名',
	'Class:EventLoginUsage/Attribute:contact_email' => '邮箱',
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
	'Class:Action/Attribute:status+' => '是否正式环境？',
	'Class:Action/Attribute:status/Value:test' => '测试',
	'Class:Action/Attribute:status/Value:test+' => '测试',
	'Class:Action/Attribute:status/Value:enabled' => '正式',
	'Class:Action/Attribute:status/Value:enabled+' => '正式生产',
	'Class:Action/Attribute:status/Value:disabled' => '停用',
	'Class:Action/Attribute:status/Value:disabled+' => '停用',
	'Class:Action/Attribute:trigger_list' => '相关的触发器',
	'Class:Action/Attribute:trigger_list+' => '此操作关联的触发器',
	'Class:Action/Attribute:finalclass' => '操作子类',
	'Class:Action/Attribute:finalclass+' => '类名称',
	'Action:WarningNoTriggerLinked' => '警告，此操作没有关联任何触发器。在至少关联1个触发器前，其不会被激活。',
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
	'Class:ActionEmail/Attribute:status+' => '此状态决定通知发送给谁：仅测试收件人，所有人（收件人，抄送人和密送人)，或者均不',
	'Class:ActionEmail/Attribute:status/Value:test+' => '邮件仅发送给测试收件人',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => '邮件将发送给所有收件人，抄送人和密送人',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => '邮件通知将不发送',
	'Class:ActionEmail/Attribute:test_recipient' => '测试收件人',
	'Class:ActionEmail/Attribute:test_recipient+' => '状态设置为“测试”时的收件人',
	'Class:ActionEmail/Attribute:from' => '发件人',
	'Class:ActionEmail/Attribute:from+' => '将加入邮件头部',
	'Class:ActionEmail/Attribute:from_label' => '发件人显示名',
	'Class:ActionEmail/Attribute:from_label+' => '发件人显示名将加入邮件头部',
	'Class:ActionEmail/Attribute:reply_to' => '回复到',
	'Class:ActionEmail/Attribute:reply_to+' => '将加入邮件头部',
	'Class:ActionEmail/Attribute:reply_to_label' => '收件人显示名',
	'Class:ActionEmail/Attribute:reply_to_label+' => '收件人显示名将加入邮件头部',
	'Class:ActionEmail/Attribute:to' => '收件人',
	'Class:ActionEmail/Attribute:to+' => '邮件的收件人',
	'Class:ActionEmail/Attribute:cc' => '抄送',
	'Class:ActionEmail/Attribute:cc+' => '明文抄送',
	'Class:ActionEmail/Attribute:bcc' => '密抄',
	'Class:ActionEmail/Attribute:bcc+' => '加密抄送',
	'Class:ActionEmail/Attribute:subject' => '主题',
	'Class:ActionEmail/Attribute:subject+' => '邮件主题',
	'Class:ActionEmail/Attribute:body' => '正文',
	'Class:ActionEmail/Attribute:body+' => '邮件内容',
	'Class:ActionEmail/Attribute:importance' => '重要性',
	'Class:ActionEmail/Attribute:importance+' => '重要性标记',
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
	'Class:Trigger/Attribute:description+' => '简短描述',
	'Class:Trigger/Attribute:action_list' => '触发的行为',
	'Class:Trigger/Attribute:action_list+' => '触发器激活后所执行的操作',
	'Class:Trigger/Attribute:finalclass' => '触发器子类',
	'Class:Trigger/Attribute:finalclass+' => '类名称',
	'Class:Trigger/Attribute:context' => '上下文',
	'Class:Trigger/Attribute:context+' => '触发场景',
));

//
// Class: TriggerOnObject
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObject' => '触发器（类无关）',
	'Class:TriggerOnObject+' => '在给定类对象的触发器',
	'Class:TriggerOnObject/Attribute:target_class' => '目标类',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => '过滤器',
	'Class:TriggerOnObject/Attribute:filter+' => '限制能激活触发器的对象列表（目标类的）',
	'TriggerOnObject:WrongFilterQuery' => '错误的筛选查询：%1$s',
	'TriggerOnObject:WrongFilterClass' => '筛选查询必须返回类“%1$s”的对象',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnPortalUpdate' => '触发器（工单更新时）',
	'Class:TriggerOnPortalUpdate+' => '终端用户更新工单时触发',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateChange' => '触发器（当状态变化时）',
	'Class:TriggerOnStateChange+' => '当对象状态变化时触发',
	'Class:TriggerOnStateChange/Attribute:state' => '状态',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateEnter' => '触发器（进入指定状态）',
	'Class:TriggerOnStateEnter+' => '对象进入指定状态时触发',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnStateLeave' => '触发器（离开指定状态时）',
	'Class:TriggerOnStateLeave+' => '指定类或子类对象离开指定状态时触发',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectCreate' => '触发器（对象创建时）',
	'Class:TriggerOnObjectCreate+' => '指定类或子类对象创建时触发',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectDelete' => '触发器（对象删除时）',
	'Class:TriggerOnObjectDelete+' => '指定类或子类的对象删除时触发',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectUpdate' => '触发器 (对象更新时)',
	'Class:TriggerOnObjectUpdate+' => '指定类或子类更新时触发',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => '目标字段',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TriggerOnObjectMention' => '触发器 (对象被提及时)',
	'Class:TriggerOnObjectMention+' => '指定类或子类在日志属性中被提及时（@xxx）触发',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => '被提及筛选',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => '限制会激活触发器的被提及对象列表。若留空，所有被提及对象（任何类的）将激活触发器。',
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
	'Class:lnkTriggerAction+' => '关联触发器和操作',
	'Class:lnkTriggerAction/Attribute:action_id' => '操作',
	'Class:lnkTriggerAction/Attribute:action_id+' => '要执行的操作',
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
	'Class:SynchroDataSource/Attribute:status' => '状态',
	'Class:SynchroDataSource/Attribute:scope_class' => '目标类',
	'Class:SynchroDataSource/Attribute:user_id' => '用户',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => '要通知的人',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => '发生错误时要通知的联系人',
	'Class:SynchroDataSource/Attribute:url_icon' => '图标的超链接',
	'Class:SynchroDataSource/Attribute:url_icon+' => '一个（小）图像的链接，此图像代表与'.ITOP_APPLICATION_SHORT.'进行同步的程序',
	'Class:SynchroDataSource/Attribute:url_application' => '应用的超链接',
	'Class:SynchroDataSource/Attribute:url_application+' => '外部程序的超链，存储'.ITOP_APPLICATION_SHORT.'对象并与'.ITOP_APPLICATION_SHORT.'同步（如适用）。可能的占位符：$this->attribute$，$replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => '对账策略',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => '全量载入间隔',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => '在此设定的时间内至少进行一次全量数据载入',
	'Class:SynchroDataSource/Attribute:action_on_zero' => '执行结果成功时',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '搜索没有返回对象时要执行的操作',
	'Class:SynchroDataSource/Attribute:action_on_one' => '执行结果失败时',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '搜索精确返回1个对象时要执行的操作',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => '执行结果未知时',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => '搜索返回多个对象时要执行的操作',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => '授权用户',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => '谁可以删除同步的对象',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => '仅限管理员',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => '所有授权用户',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => '更新规则',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => '语法: 字段名:值; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => '保留期限',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => '废弃对象在被删除前要保留的时长',
	'Class:SynchroDataSource/Attribute:database_table_name' => '数据表',
	'Class:SynchroDataSource/Attribute:database_table_name+' => '存储同步数据的表的名称。若留空则计算一个默认名称。',
	'SynchroDataSource:Description' => '描述',
	'SynchroDataSource:Reconciliation' => '搜索&amp;对账',
	'SynchroDataSource:Deletion' => '删除规则',
	'SynchroDataSource:Status' => '状态',
	'SynchroDataSource:Information' => '信息',
	'SynchroDataSource:Definition' => '定义',
	'Core:SynchroAttributes' => '属性',
	'Core:SynchroStatus' => '状态',
	'Core:Synchro:ErrorsLabel' => '错误',
	'Core:Synchro:CreatedLabel' => '已创建',
	'Core:Synchro:ModifiedLabel' => '已修改',
	'Core:Synchro:UnchangedLabel' => '保持不变',
	'Core:Synchro:ReconciledErrorsLabel' => '错误',
	'Core:Synchro:ReconciledLabel' => '已对账',
	'Core:Synchro:ReconciledNewLabel' => '已创建',
	'Core:SynchroReconcile:Yes' => '是',
	'Core:SynchroReconcile:No' => '否',
	'Core:SynchroUpdate:Yes' => '是',
	'Core:SynchroUpdate:No' => '否',
	'Core:Synchro:LastestStatus' => '最新状态',
	'Core:Synchro:History' => '同步历史',
	'Core:Synchro:NeverRun' => '此同步从未运行。无日志。',
	'Core:Synchro:SynchroEndedOn_Date' => '最近的同步发生在%1$s。',
	'Core:Synchro:SynchroRunningStartedOn_Date' => ' %1$s开始的同步还在进行中...',
	'Menu:DataSources' => '同步数据源', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '所有同步数据源', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => '已忽略（%1$s）',
	'Core:Synchro:label_repl_disappeared' => '已消失（%1$s）',
	'Core:Synchro:label_repl_existing' => '已存在（%1$s）',
	'Core:Synchro:label_repl_new' => '新增（%1$s）',
	'Core:Synchro:label_obj_deleted' => '已删除（%1$s）',
	'Core:Synchro:label_obj_obsoleted' => '已废弃（%1$s）',
	'Core:Synchro:label_obj_disappeared_errors' => '错误（%1$s）',
	'Core:Synchro:label_obj_disappeared_no_action' => '无操作（%1$s）',
	'Core:Synchro:label_obj_unchanged' => '保持不变（%1$s）',
	'Core:Synchro:label_obj_updated' => '已更新（%1$s）',
	'Core:Synchro:label_obj_updated_errors' => 'Errors（%1$s）',
	'Core:Synchro:label_obj_new_unchanged' => '保持不变（%1$s）',
	'Core:Synchro:label_obj_new_updated' => '已更新（%1$s）',
	'Core:Synchro:label_obj_created' => '已创建（%1$s）',
	'Core:Synchro:label_obj_new_errors' => '错误（%1$s）',
	'Core:SynchroLogTitle' => '%1$s——%2$s',
	'Core:Synchro:Nb_Replica' => '对账已处理：%1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s：%2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => '至少需要指定一个对账键，或者对账策略必须是使用主键。',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => '必须指定删除保留期限，自对象被标记为废除至被删除的时长。',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => '废弃对象将要被更新，但是没有指定更新。',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => '表%1$s在数据库中已存在。请为同步数据表另外指定一个名称。',
	'Core:SynchroReplica:PublicData' => '公共数据',
	'Core:SynchroReplica:PrivateDetails' => '专属详情',
	'Core:SynchroReplica:BackToDataSource' => '返回同步数据源: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => '对账列表',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id（主键）',
	'Core:SynchroAtt:attcode' => '属性',
	'Core:SynchroAtt:attcode+' => '对象字段',
	'Core:SynchroAtt:reconciliation' => '对账？',
	'Core:SynchroAtt:reconciliation+' => '搜索时使用',
	'Core:SynchroAtt:update' => '更新？',
	'Core:SynchroAtt:update+' => '更新对象时使用',
	'Core:SynchroAtt:update_policy' => '更新策略',
	'Core:SynchroAtt:update_policy+' => '被更新字段的行为',
	'Core:SynchroAtt:reconciliation_attcode' => '对账键',
	'Core:SynchroAtt:reconciliation_attcode+' => '外键对账的属性编码',
	'Core:SyncDataExchangeComment' => '（数据同步）',
	'Core:Synchro:ListOfDataSources' => '数据源列表：',
	'Core:Synchro:LastSynchro' => '上次同步：',
	'Core:Synchro:ThisObjectIsSynchronized' => '对象已与外部数据源同步',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => '对象<b>创建</b>于外部数数据源%1$s',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => '此对象<b>可以删除</b>于外部数据源%1$s',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => '您<b>无法删除此对象</b>因为其管理于外部数据源%1$s',
	'TitleSynchroExecution' => '同步的执行',
	'Class:SynchroDataSource:DataTable' => '数据库表：%1$s',
	'Core:SyncDataSourceObsolete' => '数据源已被废弃。操作被取消。',
	'Core:SyncDataSourceAccessRestriction' => '仅数据源中指定的用户才能执行此操作。操作被取消。',
	'Core:SyncTooManyMissingReplicas' => '所有记录有一段时间未动了（所有的对象可能已经被删除）。请检查写入同步表的程序仍然在运行中。操作被取消。',
	'Core:SyncSplitModeCLIOnly' => '通知仅在CLI模式下运行才能批量执行',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s个复制，%2$s个错误，%3$s个告警。',
	'Core:SynchroReplica:TargetObject' => '已同步的对象: %1$s',
	'Class:AsyncSendEmail' => '电子邮件 (异步)',
	'Class:AsyncSendEmail/Attribute:to' => '收件人',
	'Class:AsyncSendEmail/Attribute:subject' => '主题',
	'Class:AsyncSendEmail/Attribute:body' => '正文',
	'Class:AsyncSendEmail/Attribute:header' => '抬头',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => '加密密码',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => '之前的值',
	'Class:CMDBChangeOpSetAttributeEncrypted' => '加密字段',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => '之前的值',
	'Class:CMDBChangeOpSetAttributeCaseLog' => '实例日志',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => '最后条目',
	'Class:SynchroDataSource' => '同步数据源',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => '上线中',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => '废弃的',
	'Class:SynchroDataSource/Attribute:status/Value:production' => '生产的',
	'Class:SynchroDataSource/Attribute:scope_restriction' => '范围限制',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => '使用此属性',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => '使用primary_key字段',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => '创建',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => '创建',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => '错误',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => '选择第一项（随机？）',
	'Class:SynchroDataSource/Attribute:delete_policy' => '删除策略',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => '删除',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => '忽略',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => '先更新再删除',
	'Class:SynchroDataSource/Attribute:attribute_list' => '属性列表',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => '仅限管理员',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => '允许所有人删除此些对象',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => '没有人',
	'Class:SynchroAttribute' => '同步属性',
	'Class:SynchroAttribute/Attribute:sync_source_id' => '同步数据源',
	'Class:SynchroAttribute/Attribute:attcode' => '属性代码',
	'Class:SynchroAttribute/Attribute:update' => '更新',
	'Class:SynchroAttribute/Attribute:reconcile' => '对账',
	'Class:SynchroAttribute/Attribute:update_policy' => '更新策略',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => '加锁',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => '解锁',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => '如果为空则初始化',
	'Class:SynchroAttribute/Attribute:finalclass' => '类',
	'Class:SynchroAttExtKey' => '同步属性（外键）',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => '对账属性',
	'Class:SynchroAttLinkSet' => '同步属性（外联对象）',
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
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => '被发现的副本数',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => '副本总数',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => '已删除对象数',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => '删除时错误数',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => '已废弃对象数',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => '废弃时错误数',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => '已创建对象数',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => '创建时错误数',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => '已更新对象数',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => '更新时错误数',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => '对账时错误数',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => '已消失副本数',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => '已更新对象数',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => '未变的对象数',
	'Class:SynchroLog/Attribute:last_error' => '最后错误',
	'Class:SynchroLog/Attribute:traces' => '轨迹',
	'Class:SynchroReplica' => '同步副本',
	'Class:SynchroReplica/Attribute:sync_source_id' => '同步数据源',
	'Class:SynchroReplica/Attribute:dest_id' => '目标对象（ID）',
	'Class:SynchroReplica/Attribute:dest_class' => '目标类',
	'Class:SynchroReplica/Attribute:status_last_seen' => '最后看到',
	'Class:SynchroReplica/Attribute:status' => '状态',
	'Class:SynchroReplica/Attribute:status/Value:modified' => '已修改',
	'Class:SynchroReplica/Attribute:status/Value:new' => '新建',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => '废弃',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => '孤立',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => '已同步',
	'Class:SynchroReplica/Attribute:status_dest_creator' => '对象已创建？',
	'Class:SynchroReplica/Attribute:status_last_error' => '最后错误',
	'Class:SynchroReplica/Attribute:status_last_warning' => '警告',
	'Class:SynchroReplica/Attribute:info_creation_date' => '创建日期',
	'Class:SynchroReplica/Attribute:info_last_modified' => '最后修改日期',
	'Class:appUserPreferences' => '用户资料',
	'Class:appUserPreferences/Attribute:userid' => '用户',
	'Class:appUserPreferences/Attribute:preferences' => '首选项',
	'Core:ExecProcess:Code1' => '错误的命令，或者命令运行出错（例如错误的脚本名）',
	'Core:ExecProcess:Code255' => 'PHP错误（解析或运行时）',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$d秒',
	'Core:Duration_Minutes_Seconds' => '%1$d分%2$d秒',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$d时%2$d分%3$d秒',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$s天%2$d时%3$d分%4$d秒',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => '已过时长（存储为“%1$s”）',
	'Core:ExplainWTC:StopWatch-TimeSpent' => '“%1$s”所用时长',
	'Core:ExplainWTC:StopWatch-Deadline' => '“%1$s”截止于%2$d%%',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => '缺少参数“%1$s”',
	'Core:BulkExport:InvalidParameter_Query' => '参数“query”的值不合法。没有查询手册对应此id：“"%1$s”。',
	'Core:BulkExport:ExportFormatPrompt' => '导出格式：',
	'Core:BulkExportOf_Class' => '%1$s导出',
	'Core:BulkExport:ClickHereToDownload_FileName' => '点击这里下载%1$s',
	'Core:BulkExport:ExportResult' => '导出结果：',
	'Core:BulkExport:RetrievingData' => '正在检索数据...',
	'Core:BulkExport:HTMLFormat' => '网页（*.html）',
	'Core:BulkExport:CSVFormat' => 'CSV（*.csv）',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007+（*.xlsx）',
	'Core:BulkExport:PDFFormat' => 'PDF文档（*.pdf）',
	'Core:BulkExport:DragAndDropHelp' => '可拖动或删除列头进行排序。正在预览%1$s行。一共需要导出：%2$s行。',
	'Core:BulkExport:EmptyPreview' => '请选择要导出的列',
	'Core:BulkExport:ColumnsOrder' => '列顺序',
	'Core:BulkExport:AvailableColumnsFrom_Class' => '%1$s属性中可用的列',
	'Core:BulkExport:NoFieldSelected' => '至少选择导出一列',
	'Core:BulkExport:CheckAll' => '全选',
	'Core:BulkExport:UncheckAll' => '反选',
	'Core:BulkExport:ExportCancelledByUser' => '导出被用户取消',
	'Core:BulkExport:CSVOptions' => 'CSV选项',
	'Core:BulkExport:CSVLocalization' => '本地化',
	'Core:BulkExport:PDFOptions' => 'PDF选项',
	'Core:BulkExport:PDFPageFormat' => '页面格式',
	'Core:BulkExport:PDFPageSize' => '页面大小：',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => '信纸',
	'Core:BulkExport:PDFPageOrientation' => '页面方向：',
	'Core:BulkExport:PageOrientation-L' => '横向',
	'Core:BulkExport:PageOrientation-P' => '纵向',
	'Core:BulkExport:XMLFormat' => 'XML文件（*.xml）',
	'Core:BulkExport:XMLOptions' => 'XML选项',
	'Core:BulkExport:SpreadsheetFormat' => '表格HTML（*.html）',
	'Core:BulkExport:SpreadsheetOptions' => '表格选项',
	'Core:BulkExport:OptionNoLocalize' => '导出编码而非显示名',
	'Core:BulkExport:OptionLinkSets' => '包含外链的对象',
	'Core:BulkExport:OptionFormattedText' => '保持文本格式',
	'Core:BulkExport:ScopeDefinition' => '定义要导出的对象',
	'Core:BulkExportLabelOQLExpression' => 'OQL查询：',
	'Core:BulkExportLabelPhrasebookEntry' => '查询手册条目：',
	'Core:BulkExportMessageEmptyOQL' => '请输入有效的OQL查询。',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => '请选择一个合法的OQL手册条目。',
	'Core:BulkExportQueryPlaceholder' => '请在这里输入OQL查询...',
	'Core:BulkExportCanRunNonInteractive' => '点击这里运行非交互式导出。',
	'Core:BulkExportLegacyExport' => '点击这里进入旧版导出。',
	'Core:BulkExport:XLSXOptions' => 'Excel选项',
	'Core:BulkExport:TextFormat' => '文本中包含一些HTML标记',
	'Core:BulkExport:DateTimeFormat' => '日期和时间格式',
	'Core:BulkExport:DateTimeFormatDefault_Example' => '默认格式（%1$s），例如%2$s',
	'Core:BulkExport:DateTimeFormatCustom_Format' => '自定义格式：%1$s',
	'Core:BulkExport:PDF:PageNumber' => '第%1$s页',
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

//
// Class: TagSetFieldData
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TagSetFieldData' => '类%1$s的%2$s',
	'Class:TagSetFieldData+' => '~~',

	'Class:TagSetFieldData/Attribute:code' => '编码',
	'Class:TagSetFieldData/Attribute:code+' => '内部编码。必须包含至少3个字符',
	'Class:TagSetFieldData/Attribute:label' => '标签',
	'Class:TagSetFieldData/Attribute:label+' => '显示的标签',
	'Class:TagSetFieldData/Attribute:description' => '描述',
	'Class:TagSetFieldData/Attribute:description+' => '描述',
	'Class:TagSetFieldData/Attribute:finalclass' => '标签类',
	'Class:TagSetFieldData/Attribute:obj_class' => '对象类',
	'Class:TagSetFieldData/Attribute:obj_attcode' => '字段编码',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => '已使用的标签无法删除',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => '标签的编码或者显示名必须是唯一的',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => '标签编码包含3到%1$d个字符，并且以字母开头。',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => '所输入的标签编码为保留字',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => '标签显示名不能包含“%1$s”也不能为空',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => '标签编码使用时无法被修改',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => '标签“对象类”无法被修改',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => '标签“属性编码”无法被修改',
	'Core:TagSetFieldData:WhereIsThisTagTab' => '标签使用（%1$d）',
	'Core:TagSetFieldData:NoEntryFound' => '此标签下无条目',
));

//
// Class: DBProperty
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DBProperty' => 'DB属性',
	'Class:DBProperty+' => '数据库属性',
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
	'Class:BackgroundTask/Attribute:class_name' => '类名称',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => '首次运行时间',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => '最近运行时间',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => '下次运行时间',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => '一共执行的次数',
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
	'Class:AsyncTask/Attribute:finalclass' => '类',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => '状态',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => '剩余重试次数',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => '最后错误代码',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => '最后错误',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => '最后尝试',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => '配置“async_task_retries[%1$s]”的格式错误。应该是包含以下键的数组：%2$s',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => '配置“async_task_retries[%1$s]”的格式错误：错误的键“%2$s”。应该只包含以下键： %3$s',
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
	'Class:ResourceRunQueriesMenu' => '资源运行查询菜单',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ResourceSystemMenu' => '资源系统菜单',
	'Class:ResourceSystemMenu+' => '',
));




// Additional language entries not present in English dict
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
 'INTERNAL:JQuery-DatePicker:LangCode' => 'zh-CN',
));

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
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:AuditCategory' => '审计类目',
	'Class:AuditCategory+' => '全部审计中的一个区段',
	'Class:AuditCategory/Attribute:name' => '类目名称',
	'Class:AuditCategory/Attribute:name+' => '类目简称',
	'Class:AuditCategory/Attribute:description' => '审计类目描述',
	'Class:AuditCategory/Attribute:description+' => '该审计类目的详细描述',
	'Class:AuditCategory/Attribute:definition_set' => '定义',
	'Class:AuditCategory/Attribute:definition_set+' => '定义用于审计的对象的OQL表达式',
	'Class:AuditCategory/Attribute:rules_list' => '审计规则',
	'Class:AuditCategory/Attribute:rules_list+' => '该类目的审计规则',
));

//
// Class: AuditRule
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:AuditRule' => '审计规则',
	'Class:AuditRule+' => '用于检查给定审计类目的规则',
	'Class:AuditRule/Attribute:name' => '规则名称',
	'Class:AuditRule/Attribute:name+' => '规则简称',
	'Class:AuditRule/Attribute:description' => '审计规则描述',
	'Class:AuditRule/Attribute:description+' => '审计规则详细描述',
	'Class:AuditRule/Attribute:query' => '要运行的查询',
	'Class:AuditRule/Attribute:query+' => '要运行的OQL表达式',
	'Class:AuditRule/Attribute:valid_flag' => '有效对象?',
	'Class:AuditRule/Attribute:valid_flag+' => '若规则返回有效对象则True,否则False',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => '类目',
	'Class:AuditRule/Attribute:category_id+' => '该规则对应的类目',
	'Class:AuditRule/Attribute:category_name' => '类目',
	'Class:AuditRule/Attribute:category_name+' => '该规则对应类目的名称',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:User' => '用户',
	'Class:User+' => '用户登录名',
	'Class:User/Attribute:finalclass' => '帐户类别',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => '联系人 (个人)',
	'Class:User/Attribute:contactid+' => '来自业务数据的个人明细信息',
	'Class:User/Attribute:last_name' => '名',
	'Class:User/Attribute:last_name+' => '对应联系人的名字',
	'Class:User/Attribute:first_name' => '姓',
	'Class:User/Attribute:first_name+' => '对应联系人的姓氏',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => '对应联系人的Email',
	'Class:User/Attribute:login' => '登录名',
	'Class:User/Attribute:login+' => '用户标识字符串',
	'Class:User/Attribute:language' => '语言',
	'Class:User/Attribute:language+' => '用户语言',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => '简档',
	'Class:User/Attribute:profile_list+' => '角色, 为该人员授权',
	'Class:User/Attribute:allowed_org_list' => '被许可的组织',
	'Class:User/Attribute:allowed_org_list+' => '终端用户被许可看到下述组织的数据. 如果没有指定的组织,则无限制.',

	'Class:User/Error:LoginMustBeUnique' => '登录名必须唯一 - "%1s" 已经被使用.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => '至少一个简档必须指定给该用户.',
));

//
// Class: URP_Profiles
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_Profiles' => '简档',
	'Class:URP_Profiles+' => '用户简档',
	'Class:URP_Profiles/Attribute:name' => '名称',
	'Class:URP_Profiles/Attribute:name+' => '标签',
	'Class:URP_Profiles/Attribute:description' => '描述',
	'Class:URP_Profiles/Attribute:description+' => '单行描述',
	'Class:URP_Profiles/Attribute:user_list' => '用户',
	'Class:URP_Profiles/Attribute:user_list+' => '拥有该角色的人员',
));

//
// Class: URP_Dimensions
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_Dimensions' => '维度',
	'Class:URP_Dimensions+' => '应用维度 (定义纵深)',
	'Class:URP_Dimensions/Attribute:name' => '名称',
	'Class:URP_Dimensions/Attribute:name+' => '标签',
	'Class:URP_Dimensions/Attribute:description' => '描述',
	'Class:URP_Dimensions/Attribute:description+' => '单行描述',
	'Class:URP_Dimensions/Attribute:type' => '类别',
	'Class:URP_Dimensions/Attribute:type+' => '类名称或数据类别 (投影单位)',
));

//
// Class: URP_UserProfile
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_UserProfile' => '简档目标用户',
	'Class:URP_UserProfile+' => '用户的简档',
	'Class:URP_UserProfile/Attribute:userid' => '用户',
	'Class:URP_UserProfile/Attribute:userid+' => '用户帐户',
	'Class:URP_UserProfile/Attribute:userlogin' => '登录名',
	'Class:URP_UserProfile/Attribute:userlogin+' => '用户的登录名',
	'Class:URP_UserProfile/Attribute:profileid' => '简档',
	'Class:URP_UserProfile/Attribute:profileid+' => '使用简档',
	'Class:URP_UserProfile/Attribute:profile' => '简档',
	'Class:URP_UserProfile/Attribute:profile+' => '简档名称',
	'Class:URP_UserProfile/Attribute:reason' => '原因',
	'Class:URP_UserProfile/Attribute:reason+' => '解释为什么该人员需要拥有该角色',
));

//
// Class: URP_UserOrg
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_UserOrg' => '用户组织',
	'Class:URP_UserOrg+' => '被许可的组织',
	'Class:URP_UserOrg/Attribute:userid' => '用户',
	'Class:URP_UserOrg/Attribute:userid+' => '用户帐户',
	'Class:URP_UserOrg/Attribute:userlogin' => '登录名',
	'Class:URP_UserOrg/Attribute:userlogin+' => '用户的登录名',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => '组织',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '被许可的组织',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => '组织',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '被许可的组织',
	'Class:URP_UserOrg/Attribute:reason' => '原因',
	'Class:URP_UserOrg/Attribute:reason+' => '解释为什么该人员被许可查阅该组织的数据',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ProfileProjection' => '简档投射',
	'Class:URP_ProfileProjection+' => '简档投射',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => '维度',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => '应用维度',
	'Class:URP_ProfileProjection/Attribute:dimension' => '维度',
	'Class:URP_ProfileProjection/Attribute:dimension+' => '应用维度',
	'Class:URP_ProfileProjection/Attribute:profileid' => '简档',
	'Class:URP_ProfileProjection/Attribute:profileid+' => '使用简档',
	'Class:URP_ProfileProjection/Attribute:profile' => '简档',
	'Class:URP_ProfileProjection/Attribute:profile+' => '简档名称',
	'Class:URP_ProfileProjection/Attribute:value' => '值表达式',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => '属性',
	'Class:URP_ProfileProjection/Attribute:attribute+' => '目标属性编码 (可选)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ClassProjection' => '类投射',
	'Class:URP_ClassProjection+' => '类投射',
	'Class:URP_ClassProjection/Attribute:dimensionid' => '维度',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => '应用维度',
	'Class:URP_ClassProjection/Attribute:dimension' => '维度',
	'Class:URP_ClassProjection/Attribute:dimension+' => '应用维度',
	'Class:URP_ClassProjection/Attribute:class' => '类',
	'Class:URP_ClassProjection/Attribute:class+' => '目标类',
	'Class:URP_ClassProjection/Attribute:value' => '值表达式',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => '属性',
	'Class:URP_ClassProjection/Attribute:attribute+' => '目标属性编码 (可选)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ActionGrant' => '动作许可',
	'Class:URP_ActionGrant+' => '类上的许可',
	'Class:URP_ActionGrant/Attribute:profileid' => '简档',
	'Class:URP_ActionGrant/Attribute:profileid+' => '使用简档',
	'Class:URP_ActionGrant/Attribute:profile' => '简档',
	'Class:URP_ActionGrant/Attribute:profile+' => '使用简档',
	'Class:URP_ActionGrant/Attribute:class' => '类',
	'Class:URP_ActionGrant/Attribute:class+' => '目标类',
	'Class:URP_ActionGrant/Attribute:permission' => '许可',
	'Class:URP_ActionGrant/Attribute:permission+' => '允许或不允许?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => '是',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '是',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => '否',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '否',
	'Class:URP_ActionGrant/Attribute:action' => '动作',
	'Class:URP_ActionGrant/Attribute:action+' => '可用于给定类上的操作',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_StimulusGrant' => '刺激许可',
	'Class:URP_StimulusGrant+' => '对象生命周期中刺激的许可',
	'Class:URP_StimulusGrant/Attribute:profileid' => '简档',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '使用简档',
	'Class:URP_StimulusGrant/Attribute:profile' => '简档',
	'Class:URP_StimulusGrant/Attribute:profile+' => '使用简档',
	'Class:URP_StimulusGrant/Attribute:class' => '类',
	'Class:URP_StimulusGrant/Attribute:class+' => '目标类',
	'Class:URP_StimulusGrant/Attribute:permission' => '许可',
	'Class:URP_StimulusGrant/Attribute:permission+' => '允许或不允许?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => '是',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => '是',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => '否',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => '否',
	'Class:URP_StimulusGrant/Attribute:stimulus' => '刺激',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => '刺激编码',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_AttributeGrant' => '属性许可',
	'Class:URP_AttributeGrant+' => '属性层次上的许可',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => '动作准许',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '动作准许',
	'Class:URP_AttributeGrant/Attribute:attcode' => '属性',
	'Class:URP_AttributeGrant/Attribute:attcode+' => '属性编码',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'BooleanLabel:yes' => '是',
	'BooleanLabel:no' => '否',
	'Menu:WelcomeMenu' => '欢迎', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => '欢迎来到iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => '欢迎', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => '欢迎来到iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => '欢迎来到iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop 是完全的, 开发源码的, IT 操作门户.</p>
<ul>系统包括:
<li>完全的 CMDB (Configuration management database) 记录和管理 IT 资产清册.</li>
<li>事件管理模块跟踪和传递所有发生在 IT 系统中的事件.</li>
<li>变更管理模块规划和跟踪 IT 环境中发生的变化.</li>
<li>已知的错误数据库加速事件的处理.</li>
<li>停损模块记录所有计划的停机并通知对应的联系人.</li>
<li>通过仪表板迅速获得 IT 的概览.</li>
</ul>
<p>所有模块可以各自独立地、一步一步地搭建.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop 是面向服务提供商的, 它使得 IT 工程师方便地管理多客户和多组织.
<ul>iTop, 提供功能丰富的业务处理流程:
<li>提高 IT 管理效率</li> 
<li>驱动 IT 操作能力</li> 
<li>提高用户满意度,从业务能力方面提供执行力.</li>
</ul>
</p>
<p>iTop 是完全开放的,可被集成到您当前的IT管理架构中.</p>
<p>
<ul>利用这个新一代的 IT 操作门户, 可以帮助您:
<li>更好地管理越来越复杂的 IT 环境.</li>
<li>按照您的步骤实现 ITIL 流程.</li>
<li>管理您的 IT 中最重要的设施: 文档化.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => '待处理的请求: %1$d',
	'UI:WelcomeMenu:MyCalls' => '我的请求',
	'UI:WelcomeMenu:OpenIncidents' => '待处理的事件: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => '配置项: %1$d',
	'UI:WelcomeMenu:MyIncidents' => '指派给我的事件',
	'UI:AllOrganizations' => ' 所有组织 ',
	'UI:YourSearch' => '您的搜索',
	'UI:LoggedAsMessage' => '以 %1$s 登录',
	'UI:LoggedAsMessage+Admin' => '以 %1$s 登录(Administrator)',
	'UI:Button:Logoff' => '注销',
	'UI:Button:GlobalSearch' => '搜索',
	'UI:Button:Search' => '搜索',
	'UI:Button:Query' => ' 查询 ',
	'UI:Button:Ok' => '确认',
	'UI:Button:Cancel' => '取消',
	'UI:Button:Apply' => '应用',
	'UI:Button:Back' => ' << Back ',
	'UI:Button:Next' => ' Next >> ',
	'UI:Button:Finish' => ' 结束 ',
	'UI:Button:DoImport' => ' 运行导入 ! ',
	'UI:Button:Done' => ' 完成 ',
	'UI:Button:SimulateImport' => ' 激活导入 ',
	'UI:Button:Test' => '测试!',
	'UI:Button:Evaluate' => ' 评价 ',
	'UI:Button:AddObject' => ' 添加... ',
	'UI:Button:BrowseObjects' => ' 浏览... ',
	'UI:Button:Add' => ' 添加 ',
	'UI:Button:AddToList' => ' << 添加 ',
	'UI:Button:RemoveFromList' => ' 移除 >> ',
	'UI:Button:FilterList' => ' 过滤... ',
	'UI:Button:Create' => ' 创建 ',
	'UI:Button:Delete' => ' 删除 ! ',
	'UI:Button:ChangePassword' => ' 改变密码 ',
	'UI:Button:ResetPassword' => ' 重置密码 ',
	
	'UI:SearchToggle' => '搜索',
	'UI:ClickToCreateNew' => '创建一个新的 %1$s',
	'UI:SearchFor_Class' => '搜索 %1$s 对象',
	'UI:NoObjectToDisplay' => '没有对象可显示.',
	'UI:Error:MandatoryTemplateParameter_object_id' => '当link_attr被指定时,参数 object_id 是必须的. 检查显示模板的定义.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => '当 link_attr被指定时, 参数 target_attr 是必须的. 检查显示模板的定义.',
	'UI:Error:MandatoryTemplateParameter_group_by' => '参数 group_by 是必须的. 检查显示模板的定义.',
	'UI:Error:InvalidGroupByFields' => 'group by 的栏目列表是无效的: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => '错误: 不被支持的 block 格式: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => '错误的链接定义: the class of objects to manage: %1$s was not found as an external key in the class %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Object: %1$s:%2$d not found.',
	'UI:Error:WizardCircularReferenceInDependencies' => '错误: 栏目之间的依赖性出现循环引用, 检查数据模型.',
	'UI:Error:UploadedFileTooBig' => '上传文件太大. (允许的最大限制是 %1$s). 检查您的 PHP configuration 中 upload_max_filesize 和 post_max_size.',
	'UI:Error:UploadedFileTruncated.' => '上传的文件被截断 !',
	'UI:Error:NoTmpDir' => '临时目录未定义.',
	'UI:Error:CannotWriteToTmp_Dir' => '无法向硬盘写入临时文件. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => '上传因为扩展名被停止. (Original file name = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => '文件上传失败, 未知原因. (Error code = "%1$s").',
	
	'UI:Error:1ParametersMissing' => '错误: 必须为该操作指定下述参数: %1$s.',
	'UI:Error:2ParametersMissing' => '错误: 必须为该操作指定下述参数: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => '错误: 必须为该操作指定下述参数: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => '错误: 必须为该操作指定下述参数: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => '错误: 错误的 OQL 查询: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => '运行该查询时产生了一个错误: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => '错误: 该对象已被更新.',
	'UI:Error:ObjectCannotBeUpdated' => '错误: 对象不能被更新.',
	'UI:Error:ObjectsAlreadyDeleted' => '错误: 对象已被删除!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => '您未被允许进行 %1$s 类对象的批量删除',
	'UI:Error:DeleteNotAllowedOn_Class' => '您未被允许删除 %1$s 类的对象',
	'UI:Error:BulkModifyNotAllowedOn_Class' => '您未被允许进行 %1$s 类对象的批量更新',
	'UI:Error:ObjectAlreadyCloned' => '错误: 该对象已被克隆!',
	'UI:Error:ObjectAlreadyCreated' => '错误: 该对象已被创建!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => '错误: 在对象 %2$s 的 "%3$s" 状态上的无效刺激 "%1$s" .',
	
	
	'UI:GroupBy:Count' => '计数',
	'UI:GroupBy:Count+' => '元素数量',
	'UI:CountOfObjects' => '%1$d 个对象匹配给定的条件.',
	'UI_CountOfObjectsShort' => '%1$d 个对象.',
	'UI:NoObject_Class_ToDisplay' => '没有 %1$s 可以显示',
	'UI:History:LastModified_On_By' => '最后修改 %1$s 被 %2$s.',
	'UI:HistoryTab' => '历史',
	'UI:NotificationsTab' => '通知',
	'UI:History:Date' => '日期',
	'UI:History:Date+' => '变更日期',
	'UI:History:User' => '用户',
	'UI:History:User+' => '造成变更的用户',
	'UI:History:Changes' => '变更',
	'UI:History:Changes+' => '对该对象所做的变更',
	'UI:Loading' => '载入...',
	'UI:Menu:Actions' => '动作',
	'UI:Menu:OtherActions' => '其他操作',
	'UI:Menu:New' => '新建...',
	'UI:Menu:Add' => '添加...',
	'UI:Menu:Manage' => '管理...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV 导出',
	'UI:Menu:Modify' => '修改...',
	'UI:Menu:Delete' => '删除...',
	'UI:Menu:Manage' => '管理...',
	'UI:Menu:BulkDelete' => '删除...',
	'UI:UndefinedObject' => '未定义',
	'UI:Document:OpenInNewWindow:Download' => '在新窗口打开: %1$s, 下载: %2$s',
	'UI:SelectAllToggle+' => '选择 / 清除选择 全部',
	'UI:TruncatedResults' => '%1$d objects displayed out of %2$d',
	'UI:DisplayAll' => '显示全部',
	'UI:CollapseList' => '收缩',
	'UI:CountOfResults' => '%1$d 个对象',
	'UI:ChangesLogTitle' => '变更记录 (%1$d):',
	'UI:EmptyChangesLogTitle' => '变更记录为空',
	'UI:SearchFor_Class_Objects' => '搜索 %1$s 对象',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL 查询',
	'UI:SimpleSearchTab' => '简单搜索',
	'UI:Details+' => '明细',
	'UI:SearchValue:Any' => '* 任何 *',
	'UI:SearchValue:Mixed' => '* 混合 *',
	'UI:SelectOne' => '-- 选择一个 --',
	'UI:Login:Welcome' => '欢迎来到 iTop!',
	'UI:Login:IncorrectLoginPassword' => '错误的登录名/密码, 请重试.',
	'UI:Login:IdentifyYourself' => '在继续之前, 确定您自己的身份',
	'UI:Login:UserNamePrompt' => '用户名称',
	'UI:Login:PasswordPrompt' => '密码',
	'UI:Login:ChangeYourPassword' => '改变您的密码',
	'UI:Login:OldPasswordPrompt' => '旧密码',
	'UI:Login:NewPasswordPrompt' => '新密码',
	'UI:Login:RetypeNewPasswordPrompt' => '重复新密码',
	'UI:Login:IncorrectOldPassword' => '错误: 旧密码错误',
	'UI:LogOffMenu' => '注销',
	'UI:LogOff:ThankYou' => '谢谢使用iTop',
	'UI:LogOff:ClickHereToLoginAgain' => '点击这里再次登录...',
	'UI:ChangePwdMenu' => '改变密码...',
	'UI:Login:RetypePwdDoesNotMatch' => '新密码和重录的新密码不符!',
	'UI:Button:Login' => '进入 iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop 访问被限制. 请联系iTop系统管理员.',
	'UI:Login:Error:AccessAdmin' => '有系统管理员权限才能访问. 请联系iTop系统管理员.',
	'UI:CSVImport:MappingSelectOne' => '-- 选择一个 --',
	'UI:CSVImport:MappingNotApplicable' => '-- 忽略该栏 --',
	'UI:CSVImport:NoData' => '空的数据..., 请提供数据!',
	'UI:Title:DataPreview' => '数据预览',
	'UI:CSVImport:ErrorOnlyOneColumn' => '错误: 数据仅包含一列. 您选择了合适的分隔字符了吗?',
	'UI:CSVImport:FieldName' => '栏 %1$d',
	'UI:CSVImport:DataLine1' => '数据行 1',
	'UI:CSVImport:DataLine2' => '数据行 2',
	'UI:CSVImport:idField' => 'id (主键)',
	'UI:Title:BulkImport' => 'iTop - 大批量导入',
	'UI:Title:BulkImport+' => 'CSV 导入 Wizard',
	'UI:CSVImport:ClassesSelectOne' => '-- 选择一个 --',
	'UI:CSVImport:ErrorExtendedAttCode' => '内部错误: "%1$s" 是错误的编码, 因为 "%2$s" 不是类 "%3$s" 的外部健',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d 对象将保持不变.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d 对象将被修改.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d 对象将被添加.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d 对象将有错误.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d 对象保持不变.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d 对象已被修改.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d 对象已被添加.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d 对象已有错误.',
	'UI:Title:CSVImportStep2' => '步骤 2 of 5: CSV 数据选项',
	'UI:Title:CSVImportStep3' => '步骤 3 of 5: 数据映射',
	'UI:Title:CSVImportStep4' => '步骤 4 of 5: 导入模拟',
	'UI:Title:CSVImportStep5' => '步骤 5 of 5: 导入完成',
	'UI:CSVImport:LinesNotImported' => '无法装载的行:',
	'UI:CSVImport:LinesNotImported+' => '以下行无法导入因为其中包含错误',
	'UI:CSVImport:SeparatorComma+' => ', (逗号)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (分号)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => '其他:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (双引号)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (单引号)',
	'UI:CSVImport:QualifierOther' => '其他:',
	'UI:CSVImport:TreatFirstLineAsHeader' => '将第一行视做标题头(列名称)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => '跳过文件开始的 %1$s 行',
	'UI:CSVImport:CSVDataPreview' => 'CSV 数据预览',
	'UI:CSVImport:SelectFile' => '选择导入的文件:',
	'UI:CSVImport:Tab:LoadFromFile' => '从文件装载',
	'UI:CSVImport:Tab:CopyPaste' => '复制和粘贴数据',
	'UI:CSVImport:Tab:Templates' => '模板',
	'UI:CSVImport:PasteData' => '粘贴数据以导入:',
	'UI:CSVImport:PickClassForTemplate' => '选择下载的模板: ',
	'UI:CSVImport:SeparatorCharacter' => '分隔字符:',
	'UI:CSVImport:TextQualifierCharacter' => '文本限定字符',
	'UI:CSVImport:CommentsAndHeader' => '注释和头',
	'UI:CSVImport:SelectClass' => '选择类以导入:',
	'UI:CSVImport:AdvancedMode' => '高级模式',
	'UI:CSVImport:AdvancedMode+' => '在高级模式中,对象的"id" (主键) 可以被用来修改和重命名对象.' .
									'不管怎样,列 "id" (如果存在) 只能被用做一个搜索条件,不能与其它搜索条件混用.',
	'UI:CSVImport:SelectAClassFirst' => '首先选择一个类以配置映射.',
	'UI:CSVImport:HeaderFields' => '栏目',
	'UI:CSVImport:HeaderMappings' => '映射',
	'UI:CSVImport:HeaderSearch' => '搜索?',
	'UI:CSVImport:AlertIncompleteMapping' => '请为每个栏选择一个映射.',
	'UI:CSVImport:AlertNoSearchCriteria' => '请选择至少一个搜索条件',
	'UI:CSVImport:Encoding' => '字符编码',	
	'UI:UniversalSearchTitle' => 'iTop - 通用搜索',
	'UI:UniversalSearch:Error' => '错误: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => '选择类以搜索: ',
	
	'UI:Audit:Title' => 'iTop - CMDB 审计',
	'UI:Audit:InteractiveAudit' => '交互审计',
	'UI:Audit:HeaderAuditRule' => '设计规则',
	'UI:Audit:HeaderNbObjects' => '# 对象',
	'UI:Audit:HeaderNbErrors' => '# 错误',
	'UI:Audit:PercentageOk' => '% Ok',
	
	'UI:RunQuery:Title' => 'iTop - OQL 查询评估',
	'UI:RunQuery:QueryExamples' => '查询样例',
	'UI:RunQuery:HeaderPurpose' => '目的',
	'UI:RunQuery:HeaderPurpose+' => '该查询的解释',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL 表达式',
	'UI:RunQuery:HeaderOQLExpression+' => 'OQL 语法表示的查询',
	'UI:RunQuery:ExpressionToEvaluate' => '待评估的表达式: ',
	'UI:RunQuery:MoreInfo' => '该查询的更多信息: ',
	'UI:RunQuery:DevelopedQuery' => '重新开发的查询表达式: ',
	'UI:RunQuery:SerializedFilter' => '序列化的过滤器: ',
	'UI:RunQuery:Error' => '运行该查询时产生了一个错误: %1$s',
	
	'UI:Schema:Title' => 'iTop 对象 schema',
	'UI:Schema:CategoryMenuItem' => '类目 <b>%1$s</b>',
	'UI:Schema:Relationships' => '关联',
	'UI:Schema:AbstractClass' => '抽象类: 该类不能实例化对象.',
	'UI:Schema:NonAbstractClass' => '非抽象类: 该类可以实例化对象.',
	'UI:Schema:ClassHierarchyTitle' => '类层级',
	'UI:Schema:AllClasses' => '所有类',
	'UI:Schema:ExternalKey_To' => '%1$s的外部键',
	'UI:Schema:Columns_Description' => '列: <em>%1$s</em>',
	'UI:Schema:Default_Description' => '缺省: "%1$s"',
	'UI:Schema:NullAllowed' => '允许空',
	'UI:Schema:NullNotAllowed' => '不允许空',
	'UI:Schema:Attributes' => '属性',
	'UI:Schema:AttributeCode' => '属性编码',
	'UI:Schema:AttributeCode+' => '属性的内部编码',
	'UI:Schema:Label' => '标签',
	'UI:Schema:Label+' => '属性标签',
	'UI:Schema:Type' => '类别',
	
	'UI:Schema:Type+' => '属性的数据类型',
	'UI:Schema:Origin' => '起源',
	'UI:Schema:Origin+' => '该属性被定义的基类',
	'UI:Schema:Description' => '描述',
	'UI:Schema:Description+' => '属性的描述',
	'UI:Schema:AllowedValues' => '允许值',
	'UI:Schema:AllowedValues+' => '该属性取值的限制',
	'UI:Schema:MoreInfo' => '更多信息',
	'UI:Schema:MoreInfo+' => '该栏目在数据库中被定义的更多信息',
	'UI:Schema:SearchCriteria' => '搜索条件',
	'UI:Schema:FilterCode' => '过滤器编码',
	'UI:Schema:FilterCode+' => '该搜索条件的编码',
	'UI:Schema:FilterDescription' => '描述',
	'UI:Schema:FilterDescription+' => '该搜索条件的描述',
	'UI:Schema:AvailOperators' => '可用的算子',
	'UI:Schema:AvailOperators+' => '该搜索条件可能的算子',
	'UI:Schema:ChildClasses' => '子类',
	'UI:Schema:ReferencingClasses' => '参考类',
	'UI:Schema:RelatedClasses' => '关联类',
	'UI:Schema:LifeCycle' => '生命周期',
	'UI:Schema:Triggers' => '触发器',
	'UI:Schema:Relation_Code_Description' => '关联 <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Down: %1$s',
	'UI:Schema:RelationUp_Description' => 'Up: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: 繁殖到 %2$d 个层级, 查询: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: 没有繁殖 (%2$d 层级), 查询: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s 被类 %2$s 参照, 通过栏目 %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s 被链接到 %2$s 通过 %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => '类指向 %1$s (1:n 链接):',
	'UI:Schema:Links:n-n' => '类链接到 %1$s (n:n 链接):',
	'UI:Schema:Links:All' => '全部相关类的图',
	'UI:Schema:NoLifeCyle' => '该类没有生命周期的定义.',
	'UI:Schema:LifeCycleTransitions' => '转换',
	'UI:Schema:LifeCyleAttributeOptions' => '属性选项',
	'UI:Schema:LifeCycleHiddenAttribute' => '隐藏',
	'UI:Schema:LifeCycleReadOnlyAttribute' => '只读',
	'UI:Schema:LifeCycleMandatoryAttribute' => '必须',
	'UI:Schema:LifeCycleAttributeMustChange' => '必须变更',
	'UI:Schema:LifeCycleAttributeMustPrompt' => '用户将被提示改变值',
	'UI:Schema:LifeCycleEmptyList' => '空列表',
	
	'UI:LinksWidget:Autocomplete+' => '输入前3个字符...',
	'UI:Combo:SelectValue' => '--- 选择一个值 ---',
	'UI:Label:SelectedObjects' => '被选对象: ',
	'UI:Label:AvailableObjects' => '可用对象: ',
	'UI:Link_Class_Attributes' => '%1$s 属性',
	'UI:SelectAllToggle+' => '选择全部 / 清楚全部选择',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => '添加 %1$s 个对象, 链接 %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => '添加 %1$s 个对象与 %2$s 链接',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => '管理 %1$s 个对象, 链接 %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => '添加 %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => '移除选择的对象',
	'UI:Message:EmptyList:UseAdd' => '列表是空的, 使用 "添加..." 按扭以添加元素.',
	'UI:Message:EmptyList:UseSearchForm' => '使用上面的搜索表单, 以搜索要添加的对象.',
	
	'UI:Wizard:FinalStepTitle' => '最后步骤: 确认',
	'UI:Title:DeletionOf_Object' => '删除 %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '批量删除 %1$d 个 %2$s 类的对象',
	'UI:Delete:NotAllowedToDelete' => '您未被允许删除该对象',
	'UI:Delete:NotAllowedToUpdate_Fields' => '您未被允许更新下述栏目: %1$s',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:NotEnoughRightsToDelete' => '该对象不能被删除, 因为当前用户没有足够权限',
	'UI:Error:CannotDeleteBecauseOfDepencies' => '该对象不能被删除, 因为一些手工操作必须事先完成',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:AutomaticallyDeleted' => '自动删除了',
	'UI:Delete:AutomaticResetOf_Fields' => '自动重置栏目: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => '清除所有对 %1$s 的参照...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '清除所有对 %2$s 类的 %1$d 个对象的参照...',
	'UI:Delete:Done+' => '做了什么...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s 删除了.',
	'UI:Delete:ConfirmDeletionOf_Name' => '删除 %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '删除 %2$s 类的 %1$d 个对象',
//	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => '应该自动删除, 但您未被允许这样做',
//	'UI:Delete:MustBeDeletedManuallyButNotPossible' => '必须手工删除 - 但您未被允许删除该对象, 请联系您的应用程序系统管理员',
	'UI:Delete:WillBeDeletedAutomatically' => '将被自动删除',
	'UI:Delete:MustBeDeletedManually' => '必须手工删除',
	'UI:Delete:CannotUpdateBecause_Issue' => '应该被自动更新, 但是: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => '将被自动更新 (重置: %1$s)',	
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d 个对象/链接参照了 %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d 个对象/链接参照了一些将删除的对象',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => '为确保数据库的完整性, 任何参照应该更进一步清除',
	'UI:Delete:Consequence+' => '将做什么',
	'UI:Delete:SorryDeletionNotAllowed' => '抱歉, 您未被允许删除该对象, 请看上述详细解释',
	'UI:Delete:PleaseDoTheManualOperations' => '在要求删除该对象之前, 请先手工完成上述列出的操作',
	'UI:Delect:Confirm_Object' => '请确认您要删除 %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => '请确认您要删除下列 %2$s 类的 %1$d 个对象.',
	'UI:WelcomeToITop' => '欢迎来到 iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s 详细内容',
	'UI:ErrorPageTitle' => 'iTop - 错误',
	'UI:ObjectDoesNotExist' => '抱歉, 该对象不存在 (或您未被允许浏览该对象).',
	'UI:SearchResultsPageTitle' => 'iTop - 搜索结果',
	'UI:Search:NoSearch' => '没有可搜索的内容',
	'UI:FullTextSearchTitle_Text' => '"%1$s" 的结果:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '发现 %2$s 类的 %1$d 个对象.',
	'UI:Search:NoObjectFound' => '未发现对象.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s 修改',
	'UI:ModificationTitle_Class_Object' => '修改 %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - 克隆 %1$s - %2$s 修改',
	'UI:CloneTitle_Class_Object' => '克隆 %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - 创建一个新的 %1$s ',
	'UI:CreationTitle_Class' => '创建一个新的 %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => '选择要创建的 %1$s 的类别:',
	'UI:Class_Object_NotUpdated' => '未发现变化, %1$s (%2$s) <strong>没有</strong> 被更新.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) 更新了.',
	'UI:BulkDeletePageTitle' => 'iTop - 批量删除',
	'UI:BulkDeleteTitle' => '选择您要删除的对象:',
	'UI:PageTitle:ObjectCreated' => 'iTop 对象创建了.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s 创建了.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '应用 %1$s 在对象: %2$s 上, 从 %3$s 状态到目标状态: %4$s.',
	'UI:ObjectCouldNotBeWritten' => '对象不能写入: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - 致命错误',
	'UI:SystemIntrusion' => '访问被禁止. 您正尝试未被许可的操作.',
	'UI:FatalErrorMessage' => '致命错误, iTop 无法继续.',
	'UI:Error_Details' => '错误: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop 用户管理 - 类投射',
	'UI:PageTitle:ProfileProjections' => 'iTop 用户管理 - 简档投射',
	'UI:UserManagement:Class' => '类',
	'UI:UserManagement:Class+' => '对象的类',
	'UI:UserManagement:ProjectedObject' => '对象',
	'UI:UserManagement:ProjectedObject+' => '被投射的对象',
	'UI:UserManagement:AnyObject' => '* 任何 *',
	'UI:UserManagement:User' => '用户',
	'UI:UserManagement:User+' => '与该投射相关的用户',
	'UI:UserManagement:Profile' => '简档',
	'UI:UserManagement:Profile+' => '投射被指定的简档',
	'UI:UserManagement:Action:Read' => '读',
	'UI:UserManagement:Action:Read+' => '读/显示 对象',
	'UI:UserManagement:Action:Modify' => '修改',
	'UI:UserManagement:Action:Modify+' => '创建和编辑(修改)对象',
	'UI:UserManagement:Action:Delete' => '删除',
	'UI:UserManagement:Action:Delete+' => '删除对象',
	'UI:UserManagement:Action:BulkRead' => '大批量读 (导出)',
	'UI:UserManagement:Action:BulkRead+' => '列出对象或批量导出',
	'UI:UserManagement:Action:BulkModify' => '批量修改',
	'UI:UserManagement:Action:BulkModify+' => '批量创建/编辑 (CSV 导入)',
	'UI:UserManagement:Action:BulkDelete' => '批量删除',
	'UI:UserManagement:Action:BulkDelete+' => '批量删除对象',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => '许可的 (复合的) 动作',
	'UI:UserManagement:Action' => '动作',
	'UI:UserManagement:Action+' => '该用户进行的动作',
	'UI:UserManagement:TitleActions' => '动作',
	'UI:UserManagement:Permission' => '许可',
	'UI:UserManagement:Permission+' => '用户的许可',
	'UI:UserManagement:Attributes' => '属性',
	'UI:UserManagement:ActionAllowed:Yes' => '是',
	'UI:UserManagement:ActionAllowed:No' => '否',
	'UI:UserManagement:AdminProfile+' => '系统管理员拥有数据库中所有对象的完全读/写访问权限.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '该类未定义生命周期',
	'UI:UserManagement:GrantMatrix' => '授权矩阵',
	'UI:UserManagement:LinkBetween_User_And_Profile' => '链接 %1$s 和 %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => '链接 %1$s 和 %2$s',
	
	'Menu:AdminTools' => '管理工具', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '管理工具', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => '具有系统管理员简档的用户才能获得的工具', // Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:ChangeManagementMenu' => '变更管理',
	'UI:ChangeManagementMenu+' => '变更管理',
	'UI:ChangeManagementMenu:Title' => '变更概览',
	'UI-ChangeManagementMenu-ChangesByType' => '按类别划分的变更',
	'UI-ChangeManagementMenu-ChangesByStatus' => '按状态划分的变更',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => '按工作组划分的变更',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => '尚未指派的变更',

	'UI:ConfigurationManagementMenu' => '配置管理',
	'UI:ConfigurationManagementMenu+' => '配置管理',
	'UI:ConfigurationManagementMenu:Title' => '基础架构概览',
	'UI-ConfigurationManagementMenu-InfraByType' => '按类别划分基础架构对象',
	'UI-ConfigurationManagementMenu-InfraByStatus' => '按状态划分基础架构对象',

'UI:ConfigMgmtMenuOverview:Title' => '配置管理仪表板',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => '按状态配置项目',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => '按类别配置项目',

'UI:RequestMgmtMenuOverview:Title' => '请求管理仪表板',
'UI-RequestManagementOverview-RequestByService' => '按服务划分用户请求',
'UI-RequestManagementOverview-RequestByPriority' => '按优先级划分用户请求',
'UI-RequestManagementOverview-RequestUnassigned' => '尚未指派办理人的用户请求',

'UI:IncidentMgmtMenuOverview:Title' => '事件管理仪表板',
'UI-IncidentManagementOverview-IncidentByService' => '按服务级划分事件',
'UI-IncidentManagementOverview-IncidentByPriority' => '按优先级划分事件',
'UI-IncidentManagementOverview-IncidentUnassigned' => '尚未指派办理人的事件',

'UI:ChangeMgmtMenuOverview:Title' => '变更管理仪表板',
'UI-ChangeManagementOverview-ChangeByType' => '按类别划分变更',
'UI-ChangeManagementOverview-ChangeUnassigned' => '尚未指派办理人的变更',
'UI-ChangeManagementOverview-ChangeWithOutage' => '变更引起的停机',

'UI:ServiceMgmtMenuOverview:Title' => '服务管理仪表板',
'UI-ServiceManagementOverview-CustomerContractToRenew' => '客户联系人需在30日内更新',
'UI-ServiceManagementOverview-ProviderContractToRenew' => '供应商联系人需在30日内更新',

	'UI:ContactsMenu' => '联系人',
	'UI:ContactsMenu+' => '联系人',
	'UI:ContactsMenu:Title' => '联系人概览',
	'UI-ContactsMenu-ContactsByLocation' => '按地域划分联系人',
	'UI-ContactsMenu-ContactsByType' => '按类别划分联系人',
	'UI-ContactsMenu-ContactsByStatus' => '按状态划分联系人',

	'Menu:CSVImportMenu' => 'CSV 导入', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '大批量创建或修改', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:DataModelMenu' => '数据模型', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '数据模型概览', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:ExportMenu' => '导出', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '以HTML, CSV or XML格式导出任何查询的结果', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:NotificationsMenu' => '通知', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '通知的配置', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => '配置 <span class="hilite">通知</span>',
	'UI:NotificationsMenu:Help' => '帮助',
	'UI:NotificationsMenu:HelpContent' => '<p>在 iTop 中, 通知可以被完全客户化定制. 它们是基于两个对象集: <i>触发器和动作</i>.</p>
<p><i><b>触发器</b></i> 定义了什么时候一个通知将被执行. 有3种触发器, 覆盖了一个对象生命周期的3个阶段:
<ol>
	<li>"OnCreate" 触发器, 当某个特定类的对象创建时将触发</li>
	<li>"OnStateEnter" 触发器, 在某个给定类的对象进入某个特定状态前将触发(从另外一个状态而来)</li>
	<li>"OnStateLeave" 触发器, 在某个给定类的对象离开某个特定状态时将触发</li>
</ol>
</p>
<p>
<i><b>动作</b></i> 定义了触发器触发时要执行的动作. 目前, 仅有一种动作存在于发送邮件过程中.
这些动作还定义了用于发送邮件及收件人,重要性等的模板.
</p>
<p>一个专门页面: <a href="../setup/email.test.php" target="_blank">email.test.php</a> 可用于测试和调试您的 PHP mail 配置.</p>
<p>若要执行, 动作必须和触发器相关联.
当与一个触发器关联时, 每个动作都被赋予一个顺序号, 规定了按什么样的顺序执行这些动作.</p>',
	'UI:NotificationsMenu:Triggers' => '触发器',
	'UI:NotificationsMenu:AvailableTriggers' => '可用的触发器',
	'UI:NotificationsMenu:OnCreate' => '当一个对象被创建',
	'UI:NotificationsMenu:OnStateEnter' => '当一个对象进入给定状态',
	'UI:NotificationsMenu:OnStateLeave' => '当一个对象离开给定状态',
	'UI:NotificationsMenu:Actions' => '动作',
	'UI:NotificationsMenu:AvailableActions' => '有效的动作',
	
	'Menu:AuditCategories' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:RunQueriesMenu' => '运行查询', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '运行任何查询', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:DataAdministration' => '数据管理', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '数据管理', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:UniversalSearchMenu' => '通用搜索', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '搜索所有...', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:UserManagementMenu' => '用户管理', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => '用户管理', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => '简档', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => '简档', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => '简档', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => '用户帐户',	 // Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => 'iTop version %1$s',
	'UI:iTopVersion:Long' => 'iTop version %1$s-%2$s built on %3$s',
	'UI:PropertiesTab' => '属性',

	'UI:OpenDocumentInNewWindow_' => '在新窗口打开文档: %1$s',
	'UI:DownloadDocument_' => '下载该文档: %1$s',
	'UI:Document:NoPreview' => '该类文档无法预览',

	'UI:DeadlineMissedBy_duration' => 'Missed  by %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',		
	'UI:Deadline_Minutes' => '%1$d min',			
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => '帮助',
	'UI:PasswordConfirm' => '(确认)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => '在添加更多 %1$s 对象前, 保存该对象.',
	'UI:DisplayThisMessageAtStartup' => '在启动时显示该消息',
	'UI:RelationshipGraph' => '图览',
	'UI:RelationshipList' => '列表',

	'Portal:Title' => 'iTop 用户门户',
	'Portal:Refresh' => '刷新',
	'Portal:Back' => '返回',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => '创建一个新的请求',
	'Portal:ChangeMyPassword' => '改变我的密码',
	'Portal:Disconnect' => '断开',
	'Portal:OpenRequests' => '我的待解决的请求',
	'Portal:ClosedRequests'  => 'My closed requests',
	'Portal:ResolvedRequests'  => '我的已解决的请求',
	'Portal:SelectService' => '从类目中选择一个服务:',
	'Portal:PleaseSelectOneService' => '请选择一个服务',
	'Portal:SelectSubcategoryFrom_Service' => '从服务中选择一个子类 %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => '请选择一个子类',
	'Portal:DescriptionOfTheRequest' => '输入您的请求描述:',
	'Portal:TitleRequestDetailsFor_Request' => '请求明细内容 %1$s:',
	'Portal:NoOpenRequest' => '该类目中没有请求.',
	'Portal:NoClosedRequest' => 'No request in this category',
	'Portal:Button:CloseTicket' => '关闭这个单据',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => '输入您对于该单据解决情况的评述:',
	'Portal:ErrorNoContactForThisUser' => '错误: 当前用户没有和一个联系人或人员关联. 请联系您的系统管理员.',
	
	'Enum:Undefined' => '未定义',
	'UI:Button:Refresh' => '刷新',
));



?>

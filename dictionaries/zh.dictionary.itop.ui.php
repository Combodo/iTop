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
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
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

//
// Class: QueryOQL
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Query' => 'Query~~',
	'Class:Query+' => 'A query is a data set defined in a dynamic way~~',
	'Class:Query/Attribute:name' => 'Name~~',
	'Class:Query/Attribute:name+' => 'Identifies the query~~',
	'Class:Query/Attribute:description' => 'Description~~',
	'Class:Query/Attribute:description+' => 'Long description for the query (purpose, usage, etc.)~~',
	'Class:QueryOQL/Attribute:fields' => 'Fields~~',
	'Class:QueryOQL/Attribute:fields+' => 'Coma separated list of attributes (or alias.attribute) to export~~',
	'Class:QueryOQL' => 'OQL Query~~',
	'Class:QueryOQL+' => 'A query based on the Object Query Language~~',
	'Class:QueryOQL/Attribute:oql' => 'Expression~~',
	'Class:QueryOQL/Attribute:oql+' => 'OQL Expression~~',
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
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',
		
	'Class:User/Error:LoginMustBeUnique' => '登录名必须唯一 - "%1s" 已经被使用.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => '至少一个简档必须指定给该用户.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'User Internal~~',
	'Class:UserInternal+' => 'User defined within iTop~~',
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
// Expression to Natural language
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'BooleanLabel:yes' => '是',
	'BooleanLabel:no' => '否',
    'UI:Login:Title' => 'iTop login~~',
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
	'UI:Button:Save' => 'Save~~',
	'UI:Button:Cancel' => '取消',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => '应用',
	'UI:Button:Back' => ' << Back ',
	'UI:Button:Restart' => ' |<< Restart ~~',
	'UI:Button:Next' => ' Next >> ',
	'UI:Button:Finish' => ' 结束 ',
	'UI:Button:DoImport' => ' 运行导入 ! ',
	'UI:Button:Done' => ' 完成 ',
	'UI:Button:SimulateImport' => ' 激活导入 ',
	'UI:Button:Test' => '测试!',
	'UI:Button:Evaluate' => ' 评价 ',
	'UI:Button:Evaluate:Title' => ' 评价 (Ctrl+Enter)',
	'UI:Button:AddObject' => ' 添加... ',
	'UI:Button:BrowseObjects' => ' 浏览... ',
	'UI:Button:Add' => ' 添加 ',
	'UI:Button:AddToList' => ' << 添加 ',
	'UI:Button:RemoveFromList' => ' 移除 >> ',
	'UI:Button:FilterList' => ' 过滤... ',
	'UI:Button:Create' => ' 创建 ',
	'UI:Button:Delete' => ' 删除 ! ',
	'UI:Button:Rename' => ' Rename... ~~',
	'UI:Button:ChangePassword' => ' 改变密码 ',
	'UI:Button:ResetPassword' => ' 重置密码 ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	
	'UI:SearchToggle' => '搜索',
	'UI:ClickToCreateNew' => '创建一个新的 %1$s',
	'UI:SearchFor_Class' => '搜索 %1$s 对象',
	'UI:NoObjectToDisplay' => '没有对象可显示.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
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
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',

	'UI:GroupBy:Count' => '计数',
	'UI:GroupBy:Count+' => '元素数量',
	'UI:CountOfObjects' => '%1$d 个对象匹配给定的条件.',
	'UI_CountOfObjectsShort' => '%1$d 个对象.',
	'UI:NoObject_Class_ToDisplay' => '没有 %1$s 可以显示',
	'UI:History:LastModified_On_By' => '最后修改 %1$s 被 %2$s.',
	'UI:HistoryTab' => '历史',
	'UI:NotificationsTab' => '通知',
	'UI:History:BulkImports' => 'History~~',
	'UI:History:BulkImports+' => 'List of CSV imports (latest import first)~~',
	'UI:History:BulkImportDetails' => 'Changes resulting from the CSV import performed on %1$s (by %2$s)~~',
	'UI:History:Date' => '日期',
	'UI:History:Date+' => '变更日期',
	'UI:History:User' => '用户',
	'UI:History:User+' => '造成变更的用户',
	'UI:History:Changes' => '变更',
	'UI:History:Changes+' => '对该对象所做的变更',
	'UI:History:StatsCreations' => 'Created~~',
	'UI:History:StatsCreations+' => 'Count of objects created~~',
	'UI:History:StatsModifs' => 'Modified~~',
	'UI:History:StatsModifs+' => 'Count of objects modified~~',
	'UI:History:StatsDeletes' => 'Deleted~~',
	'UI:History:StatsDeletes+' => 'Count of objects deleted~~',
	'UI:Loading' => '载入...',
	'UI:Menu:Actions' => '动作',
	'UI:Menu:OtherActions' => '其他操作',
	'UI:Menu:New' => '新建...',
	'UI:Menu:Add' => '添加...',
	'UI:Menu:Manage' => '管理...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV 导出...',
	'UI:Menu:Modify' => '修改...',
	'UI:Menu:Delete' => '删除...',
	'UI:Menu:BulkDelete' => '删除...',
	'UI:UndefinedObject' => '未定义',
	'UI:Document:OpenInNewWindow:Download' => '在新窗口打开: %1$s, 下载: %2$s',
	'UI:SplitDateTime-Date' => 'date~~',
	'UI:SplitDateTime-Time' => 'time~~',
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
	'UI:SearchValue:NbSelected' => '# selected~~',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- 选择一个 --',
	'UI:Login:Welcome' => '欢迎来到 iTop!',
	'UI:Login:IncorrectLoginPassword' => '错误的登录名/密码, 请重试.',
	'UI:Login:IdentifyYourself' => '在继续之前, 确定您自己的身份',
	'UI:Login:UserNamePrompt' => '用户名称',
	'UI:Login:PasswordPrompt' => '密码',
	'UI:Login:ForgotPwd' => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm' => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+' => 'iTop can send you an email in which you will find instructions to follow to reset your account.~~',
	'UI:Login:ResetPassword' => 'Send now!~~',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login~~',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.~~',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.~~',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated to a person.~~',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.~~',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions...~~',
	'UI:ResetPwd-EmailSubject' => 'Reset your iTop password~~',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your iTop password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',

	'UI:ResetPwd-Title' => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready' => 'The password has been changed.~~',
	'UI:ResetPwd-Login' => 'Click here to login...~~',

	'UI:Login:About' => '~~',
	'UI:Login:ChangeYourPassword' => '改变您的密码',
	'UI:Login:OldPasswordPrompt' => '旧密码',
	'UI:Login:NewPasswordPrompt' => '新密码',
	'UI:Login:RetypeNewPasswordPrompt' => '重复新密码',
	'UI:Login:IncorrectOldPassword' => '错误: 旧密码错误',
	'UI:LogOffMenu' => '注销',
	'UI:LogOff:ThankYou' => '谢谢使用iTop',
	'UI:LogOff:ClickHereToLoginAgain' => '点击这里再次登录...',
	'UI:ChangePwdMenu' => '改变密码...',
	'UI:Login:PasswordChanged' => 'Password successfully set!~~',
	'UI:AccessRO-All' => 'iTop is read-only~~',
	'UI:AccessRO-Users' => 'iTop is read-only for end-users~~',
	'UI:ApplicationEnvironment' => 'Application environment: %1$s~~',
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
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronization of %1$d objects of class %2$s~~',
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
	'UI:CSVImport:AdvancedMode+' => '在高级模式中,对象的"id" (主键) 可以被用来修改和重命名对象.不管怎样,列 "id" (如果存在) 只能被用做一个搜索条件,不能与其它搜索条件混用.',
	'UI:CSVImport:SelectAClassFirst' => '首先选择一个类以配置映射.',
	'UI:CSVImport:HeaderFields' => '栏目',
	'UI:CSVImport:HeaderMappings' => '映射',
	'UI:CSVImport:HeaderSearch' => '搜索?',
	'UI:CSVImport:AlertIncompleteMapping' => '请为每个栏选择一个映射.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => '请选择至少一个搜索条件',
	'UI:CSVImport:Encoding' => '字符编码',	
	'UI:UniversalSearchTitle' => 'iTop - 通用搜索',
	'UI:UniversalSearch:Error' => '错误: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => '选择类以搜索: ',

	'UI:CSVReport-Value-Modified' => 'Modified~~',
	'UI:CSVReport-Value-SetIssue' => 'Could not be changed - reason: %1$s~~',
	'UI:CSVReport-Value-ChangeIssue' => 'Could not be changed to %1$s - reason: %2$s~~',
	'UI:CSVReport-Value-NoMatch' => 'No match~~',
	'UI:CSVReport-Value-Missing' => 'Missing mandatory value~~',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: found %1$s objects~~',
	'UI:CSVReport-Row-Unchanged' => 'unchanged~~',
	'UI:CSVReport-Row-Created' => 'created~~',
	'UI:CSVReport-Row-Updated' => 'updated %1$d cols~~',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols~~',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s~~',
	'UI:CSVReport-Value-Issue-Null' => 'Null not allowed~~',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object not found~~',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Found %1$d matches~~',
	'UI:CSVReport-Value-Issue-Readonly' => 'The attribute \'%1$s\' is read-only and cannot be modified (current value: %2$s, proposed value: %3$s)~~',
	'UI:CSVReport-Value-Issue-Format' => 'Failed to process input: %1$s~~',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': no match found, check spelling~~',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s~~',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributes not consistent with each others: %1$s~~',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unexpected attribute value(s)~~',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Could not be created, due to missing external key(s): %1$s~~',
	'UI:CSVReport-Row-Issue-DateFormat' => 'wrong date format~~',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'failed to reconcile~~',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation~~',
	'UI:CSVReport-Row-Issue-Internal' => 'Internal error: %1$s, %2$s~~',

	'UI:CSVReport-Icon-Unchanged' => 'Unchanged~~',
	'UI:CSVReport-Icon-Modified' => 'Modified~~',
	'UI:CSVReport-Icon-Missing' => 'Missing~~',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated~~',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated~~',
	'UI:CSVReport-Icon-Created' => 'Created~~',
	'UI:CSVReport-Object-ToCreate' => 'Object will be created~~',
	'UI:CSVReport-Object-Created' => 'Object created~~',
	'UI:CSVReport-Icon-Error' => 'Error~~',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s~~',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s~~',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% of the loaded objects have errors and will be ignored.~~',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% of the loaded objects will be created.~~',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% of the loaded objects will be modified.~~',

	'UI:CSVExport:AdvancedMode' => 'Advanced mode~~',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.~~',
	'UI:CSVExport:LostChars' => 'Encoding issue~~',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').~~',

	'UI:Audit:Title' => 'iTop - CMDB 审计',
	'UI:Audit:InteractiveAudit' => '交互审计',
	'UI:Audit:HeaderAuditRule' => '设计规则',
	'UI:Audit:HeaderNbObjects' => '# 对象',
	'UI:Audit:HeaderNbErrors' => '# 错误',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.~~',

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
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries~~',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
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
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => '输入前3个字符...',
	'UI:Edit:TestQuery' => 'Test query~~',
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
	'UI:Error:NotEnoughRightsToDelete' => '该对象不能被删除, 因为当前用户没有足够权限',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => '该对象不能被删除, 因为一些手工操作必须事先完成',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'This object could not be deleted because some manual operations must be performed prior to that~~',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:Deleted' => 'deleted~~',
	'UI:Delete:AutomaticallyDeleted' => '自动删除了',
	'UI:Delete:AutomaticResetOf_Fields' => '自动重置栏目: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => '清除所有对 %1$s 的参照...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '清除所有对 %2$s 类的 %1$d 个对象的参照...',
	'UI:Delete:Done+' => '做了什么...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s 删除了.',
	'UI:Delete:ConfirmDeletionOf_Name' => '删除 %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '删除 %2$s 类的 %1$d 个对象',
	'UI:Delete:CannotDeleteBecause' => 'Could not be deleted: %1$s~~',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Should be automaticaly deleted, but this is not feasible: %1$s~~',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Must be deleted manually, but this is not feasible: %1$s~~',
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
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => 'iTop - 搜索结果',
	'UI:SearchResultsTitle' => '搜索结果',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => '没有可搜索的内容',
	'UI:Search:NeedleTooShort' => 'The search string \\"%1$s\\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \\"%1$s\\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => '"%1$s" 的结果:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '发现 %2$s 类的 %1$d 个对象.',
	'UI:Search:NoObjectFound' => '未发现对象.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s 修改',
	'UI:ModificationTitle_Class_Object' => '修改 %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - 克隆 %1$s - %2$s 修改',
	'UI:CloneTitle_Class_Object' => '克隆 %1$s: <span class=\\"hilite\\">%2$s</span>',
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
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>动作</b></i> 定义了触发器触发时要执行的动作. 目前, 仅有一种动作存在于发送邮件过程中.
这些动作还定义了用于发送邮件及收件人,重要性等的模板.
</p>
<p>一个专门页面: <a href="../setup/email.test.php" target="_blank">email.test.php</a> 可用于测试和调试您的 PHP mail 配置.</p>
<p>若要执行, 动作必须和触发器相关联.
当与一个触发器关联时, 每个动作都被赋予一个顺序号, 规定了按什么样的顺序执行这些动作.</p>~~',
	'UI:NotificationsMenu:Triggers' => '触发器',
	'UI:NotificationsMenu:AvailableTriggers' => '可用的触发器',
	'UI:NotificationsMenu:OnCreate' => '当一个对象被创建',
	'UI:NotificationsMenu:OnStateEnter' => '当一个对象进入给定状态',
	'UI:NotificationsMenu:OnStateLeave' => '当一个对象离开给定状态',
	'UI:NotificationsMenu:Actions' => '动作',
	'UI:NotificationsMenu:AvailableActions' => '有效的动作',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => '审计类目', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:RunQueriesMenu' => '运行查询', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '运行任何查询', // Duplicated into itop-welcome-itil (will be removed from here...)
	
	'Menu:QueryMenu' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	
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
	'Menu:UserAccountsMenu:Title' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s version %2$s',
	'UI:iTopVersion:Long' => '%1$s version %2$s-%3$s built on %4$s',
	'UI:PropertiesTab' => '属性',

	'UI:OpenDocumentInNewWindow_' => '在新窗口打开文档: %1$s',
	'UI:DownloadDocument_' => '下载该文档: %1$s',
	'UI:Document:NoPreview' => '该类文档无法预览',
	'UI:Download-CSV' => 'Download %1$s~~',

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
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Operation Cancelled~~',
	'UI:ElementsDisplayed' => 'Filtering~~',
	'UI:RelationGroupNumber_N' => 'Group #%1$d~~',
	'UI:Relation:ExportAsPDF' => 'Export as PDF...~~',
	'UI:RelationOption:GroupingThreshold' => 'Grouping threshold~~',
	'UI:Relation:AdditionalContextInfo' => 'Additional context info~~',
	'UI:Relation:NoneSelected' => 'None~~',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...~~',
	'UI:Relation:DrillDown' => 'Details...~~',
	'UI:Relation:PDFExportOptions' => 'PDF Export Options~~',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s~~',
	'UI:RelationOption:Untitled' => 'Untitled~~',
	'UI:Relation:Key' => 'Key~~',
	'UI:Relation:Comments' => 'Comments~~',
	'UI:RelationOption:Title' => 'Title~~',
	'UI:RelationOption:IncludeList' => 'Include the list of objects~~',
	'UI:RelationOption:Comments' => 'Comments~~',
	'UI:Button:Export' => 'Export~~',
	'UI:Relation:PDFExportPageFormat' => 'Page format~~',
	'UI:PageFormat_A3' => 'A3~~',
	'UI:PageFormat_A4' => 'A4~~',
	'UI:PageFormat_Letter' => 'Letter~~',
	'UI:Relation:PDFExportPageOrientation' => 'Page orientation~~',
	'UI:PageOrientation_Portrait' => 'Portrait~~',
	'UI:PageOrientation_Landscape' => 'Landscape~~',
	'UI:RelationTooltip:Redundancy' => 'Redundancy~~',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# of impacted items: %1$d / %2$d~~',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Critical threshold: %1$d / %2$d~~',
	'Portal:Title' => 'iTop 用户门户',
	'Portal:NoRequestMgmt' => 'Dear %1$s, you have been redirected to this page because your account is configured with the profile \'Portal user\'. Unfortunately, iTop has not been installed with the feature \'Request Management\'. Please contact your administrator.~~',
	'Portal:Refresh' => '刷新',
	'Portal:Back' => '返回',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request~~',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => '创建一个新的请求',
	'Portal:CreateNewRequestItil' => '创建一个新的请求',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
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
	'Portal:Button:ReopenTicket' => 'Reopen this ticket~~',
	'Portal:Button:CloseTicket' => '关闭这个单据',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => '输入您对于该单据解决情况的评述:',
	'Portal:ErrorNoContactForThisUser' => '错误: 当前用户没有和一个联系人或人员关联. 请联系您的系统管理员.',
	'Portal:Attachments' => 'Attachments~~',
	'Portal:AddAttachment' => ' Add Attachment ~~',
	'Portal:RemoveAttachment' => ' Remove Attachment ~~',
	'Portal:Attachment_No_To_Ticket_Name' => 'Attachment #%1$d to %2$s (%3$s)~~',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
	'Enum:Undefined' => '未定义',	
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Days %2$s Hours %3$s Minutes %4$s Seconds~~',
	'UI:ModifyAllPageTitle' => 'Modify All~~',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifying %1$d objects of class %2$s~~',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modifying %1$d objects of class %2$s out of %3$d~~',
	'UI:Menu:ModifyAll' => 'Modify...~~',
	'UI:Button:ModifyAll' => 'Modify All~~',
	'UI:Button:PreviewModifications' => 'Preview Modifications >>~~',
	'UI:ModifiedObject' => 'Object Modified~~',
	'UI:BulkModifyStatus' => 'Operation~~',
	'UI:BulkModifyStatus+' => 'Status of the operation~~',
	'UI:BulkModifyErrors' => 'Errors (if any)~~',
	'UI:BulkModifyErrors+' => 'Errors preventing the modification~~',	
	'UI:BulkModifyStatusOk' => 'Ok~~',
	'UI:BulkModifyStatusError' => 'Error~~',
	'UI:BulkModifyStatusModified' => 'Modified~~',
	'UI:BulkModifyStatusSkipped' => 'Skipped~~',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:~~',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)~~',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...~~',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Attempting to set the read-only field: %1$s~~',
	'UI:FailedToApplyStimuli' => 'The action has failed.~~',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifying %2$d objects of class %3$s~~',
	'UI:CaseLogTypeYourTextHere' => 'Type your text here:~~',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:~~',
	'UI:CaseLog:InitialValue' => 'Initial value:~~',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value not set.~~',
	'UI:ActionNotAllowed' => 'You are not allowed to perform this action on these objects.~~',
	'UI:BulkAction:NoObjectSelected' => 'Please select at least one object to perform this operation~~',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.~~',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objects (%2$s objects selected).~~',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objects.~~',
	'UI:Pagination:PageSize' => '%1$s objects per page~~',
	'UI:Pagination:PagesLabel' => 'Pages:~~',
	'UI:Pagination:All' => 'All~~',
	'UI:HierarchyOf_Class' => 'Hierarchy of %1$s~~',
	'UI:Preferences' => 'Preferences...~~',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Favorite Organizations~~',
	'UI:FavoriteOrganizations+' => 'Check in the list below the organizations that you want to see in the drop-down menu for a quick access. Note that this is not a security setting, objects from any organization are still visible and can be accessed by selecting \\"All Organizations\\" in the drop-down list.~~',
	'UI:FavoriteLanguage' => 'Language of the User Interface~~',
	'UI:Favorites:SelectYourLanguage' => 'Select your preferred language~~',
	'UI:FavoriteOtherSettings' => 'Other Settings~~',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default length for lists:  %1$s items per page~~',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Any modification will be discarded.~~',
	'UI:CancelConfirmationMessage' => 'You will loose your changes. Continue anyway?~~',
	'UI:AutoApplyConfirmationMessage' => 'Some changes have not been applied yet. Do you want itop to take them into account?~~',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Add To Dashboard...~~',
	'UI:Button:Refresh' => '刷新',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Configure This List...~~',
	'UI:ListConfigurationTitle' => 'List Configuration~~',
	'UI:ColumnsAndSortOrder' => 'Columns and sort order:~~',
	'UI:UseDefaultSettings' => 'Use the Default Settings~~',
	'UI:UseSpecificSettings' => 'Use the Following Settings:~~',
	'UI:Display_X_ItemsPerPage' => 'Display %1$s items per page~~',
	'UI:UseSavetheSettings' => 'Save the Settings~~',
	'UI:OnlyForThisList' => 'Only for this list~~',
	'UI:ForAllLists' => 'Default for all lists~~',
	'UI:ExtKey_AsLink' => '%1$s (Link)~~',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)~~',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)~~',
	'UI:Button:MoveUp' => 'Move Up~~',
	'UI:Button:MoveDown' => 'Move Down~~',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class \\"%1$s\\". You may try \\"%2$s\\" instead.~~',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class \\"%1$s\\"~~',

	'UI:Dashboard:Edit' => 'Edit This Page...~~',
	'UI:Dashboard:Revert' => 'Revert To Original Version...~~',
	'UI:Dashboard:RevertConfirm' => 'Every changes made to the original version will be lost. Please confirm that you want to do this.~~',
	'UI:ExportDashBoard' => 'Export to a file~~',
	'UI:ImportDashBoard' => 'Import from a file...~~',
	'UI:ImportDashboardTitle' => 'Import From a File~~',
	'UI:ImportDashboardText' => 'Select a dashboard file to import:~~',


	'UI:DashletCreation:Title' => 'Create a new Dashlet~~',
	'UI:DashletCreation:Dashboard' => 'Dashboard~~',
	'UI:DashletCreation:DashletType' => 'Dashlet Type~~',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard~~',

	'UI:DashboardEdit:Title' => 'Dashboard Editor~~',
	'UI:DashboardEdit:DashboardTitle' => 'Title~~',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',

	'UI:DashboardEdit:Layout' => 'Layout~~',
	'UI:DashboardEdit:Properties' => 'Dashboard Properties~~',
	'UI:DashboardEdit:Dashlets' => 'Available Dashlets~~',	
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Properties~~',	

	'UI:Form:Property' => 'Property~~',
	'UI:Form:Value' => 'Value~~',

	'UI:DashletUnknown:Label' => 'Unknown~~',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)~~',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.~~',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.~~',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").~~',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletProxy:Label' => 'Proxy~~',
	'UI:DashletProxy:Description' => 'Proxy dashlet~~',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").~~',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletPlainText:Label' => 'Text~~',
	'UI:DashletPlainText:Description' => 'Plain text (no formatting)~~',
	'UI:DashletPlainText:Prop-Text' => 'Text~~',
	'UI:DashletPlainText:Prop-Text:Default' => 'Please enter some text here...~~',

	'UI:DashletObjectList:Label' => 'Object list~~',
	'UI:DashletObjectList:Description' => 'Object list dashlet~~',
	'UI:DashletObjectList:Prop-Title' => 'Title~~',
	'UI:DashletObjectList:Prop-Query' => 'Query~~',
	'UI:DashletObjectList:Prop-Menu' => 'Menu~~',

	'UI:DashletGroupBy:Prop-Title' => 'Title~~',
	'UI:DashletGroupBy:Prop-Query' => 'Query~~',
	'UI:DashletGroupBy:Prop-Style' => 'Style~~',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Group by...~~',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)~~',
	'UI:DashletGroupBy:MissingGroupBy' => 'Please select the field on which the objects will be grouped together~~',

	'UI:DashletGroupByPie:Label' => 'Pie Chart~~',
	'UI:DashletGroupByPie:Description' => 'Pie Chart~~',
	'UI:DashletGroupByBars:Label' => 'Bar Chart~~',
	'UI:DashletGroupByBars:Description' => 'Bar Chart~~',
	'UI:DashletGroupByTable:Label' => 'Group By (table)~~',
	'UI:DashletGroupByTable:Description' => 'List (Grouped by a field)~~',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Aggregation function~~',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Function attribute~~',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direction~~',
	'UI:DashletGroupBy:Prop-OrderField' => 'Order by~~',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit~~',

	'UI:DashletGroupBy:Order:asc' => 'Ascending~~',
	'UI:DashletGroupBy:Order:desc' => 'Descending~~',

	'UI:GroupBy:count' => 'Count~~',
	'UI:GroupBy:count+' => 'Number of elements~~',
	'UI:GroupBy:sum' => 'Sum~~',
	'UI:GroupBy:sum+' => 'Sum of %1$s~~',
	'UI:GroupBy:avg' => 'Average~~',
	'UI:GroupBy:avg+' => 'Average of %1$s~~',
	'UI:GroupBy:min' => 'Minimum~~',
	'UI:GroupBy:min+' => 'Minimum of %1$s~~',
	'UI:GroupBy:max' => 'Maximum~~',
	'UI:GroupBy:max+' => 'Maximum of %1$s~~',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header~~',
	'UI:DashletHeaderStatic:Description' => 'Displays an horizontal separator~~',
	'UI:DashletHeaderStatic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icon~~',

	'UI:DashletHeaderDynamic:Label' => 'Header with statistics~~',
	'UI:DashletHeaderDynamic:Description' => 'Header with stats (grouped by...)~~',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtitle~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query~~',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Group by~~',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Values~~',

	'UI:DashletBadge:Label' => 'Badge~~',
	'UI:DashletBadge:Description' => 'Object Icon with new/search~~',
	'UI:DashletBadge:Prop-Class' => 'Class~~',

	'DayOfWeek-Sunday' => 'Sunday~~',
	'DayOfWeek-Monday' => 'Monday~~',
	'DayOfWeek-Tuesday' => 'Tuesday~~',
	'DayOfWeek-Wednesday' => 'Wednesday~~',
	'DayOfWeek-Thursday' => 'Thursday~~',
	'DayOfWeek-Friday' => 'Friday~~',
	'DayOfWeek-Saturday' => 'Saturday~~',
	'Month-01' => 'January~~',
	'Month-02' => 'February~~',
	'Month-03' => 'March~~',
	'Month-04' => 'April~~',
	'Month-05' => 'May~~',
	'Month-06' => 'June~~',
	'Month-07' => 'July~~',
	'Month-08' => 'August~~',
	'Month-09' => 'September~~',
	'Month-10' => 'October~~',
	'Month-11' => 'November~~',
	'Month-12' => 'December~~',
	
	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Su~~',
	'DayOfWeek-Monday-Min' => 'Mo~~',
	'DayOfWeek-Tuesday-Min' => 'Tu~~',
	'DayOfWeek-Wednesday-Min' => 'We~~',
	'DayOfWeek-Thursday-Min' => 'Th~~',
	'DayOfWeek-Friday-Min' => 'Fr~~',
	'DayOfWeek-Saturday-Min' => 'Sa~~',
	'Month-01-Short' => 'Jan~~',
	'Month-02-Short' => 'Feb~~',
	'Month-03-Short' => 'Mar~~',
	'Month-04-Short' => 'Apr~~',
	'Month-05-Short' => 'May~~',
	'Month-06-Short' => 'Jun~~',
	'Month-07-Short' => 'Jul~~',
	'Month-08-Short' => 'Aug~~',
	'Month-09-Short' => 'Sep~~',
	'Month-10-Short' => 'Oct~~',
	'Month-11-Short' => 'Nov~~',
	'Month-12-Short' => 'Dec~~',
	'Calendar-FirstDayOfWeek' => '0~~', // 0 = Sunday, 1 = Monday, etc...
	
	'UI:Menu:ShortcutList' => 'Create a Shortcut...~~',
	'UI:ShortcutRenameDlg:Title' => 'Rename the shortcut~~',
	'UI:ShortcutListDlg:Title' => 'Create a shortcut for the list~~',
	'UI:ShortcutDelete:Confirm' => 'Please confirm that wou wish to delete the shortcut(s).~~',
	'Menu:MyShortcuts' => 'My Shortcuts~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Shortcut~~',
	'Class:Shortcut+' => '~~',
	'Class:Shortcut/Attribute:name' => 'Name~~',
	'Class:Shortcut/Attribute:name+' => 'Label used in the menu and page title~~',
	'Class:ShortcutOQL' => 'Search result shortcut~~',
	'Class:ShortcutOQL+' => '~~',
	'Class:ShortcutOQL/Attribute:oql' => 'Query~~',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for~~',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds~~',

	'UI:FillAllMandatoryFields' => 'Please fill all mandatory fields.~~',
	'UI:ValueMustBeSet' => 'Please specify a value~~',
	'UI:ValueMustBeChanged' => 'Please change the value~~',
	'UI:ValueInvalidFormat' => 'Invalid format~~',

	'UI:CSVImportConfirmTitle' => 'Please confirm the operation~~',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?~~',
	'UI:CSVImportError_items' => 'Errors: %1$d~~',
	'UI:CSVImportCreated_items' => 'Created: %1$d~~',
	'UI:CSVImportModified_items' => 'Modified: %1$d~~',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d~~',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format~~',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)~~',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s~~',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Available placeholders:<table>
<tr><td>Y</td><td>year (4 digits, e.g. 2016)</td></tr>
<tr><td>y</td><td>year (2 digits, e.g. 16 for 2016)</td></tr>
<tr><td>m</td><td>month (2 digits, e.g. 01..12)</td></tr>
<tr><td>n</td><td>month (1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>d</td><td>day (2 digits, e.g. 01..31)</td></tr>
<tr><td>j</td><td>day (1 or 2 digits no leading zero, e.g. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 hour, 2 digits, e.g. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 hour, 2 digits, e.g. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 hour, 1 or 2 digits no leading zero, e.g. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 hour, 1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>a</td><td>hour, am or pm (lowercase)</td></tr>
<tr><td>A</td><td>hour, AM or PM (uppercase)</td></tr>
<tr><td>i</td><td>minutes (2 digits, e.g. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 digits, e.g. 00..59)</td></tr>
</table>~~',
		
	'UI:Button:Remove' => 'Remove~~',
	'UI:AddAnExisting_Class' => 'Add objects of type %1$s...~~',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s~~',

	'UI:AboutBox' => 'About iTop...~~',
	'UI:About:Title' => 'About iTop~~',
	'UI:About:DataModel' => 'Data model~~',
	'UI:About:Support' => 'Support information~~',
	'UI:About:Licenses' => 'Licenses~~',
	'UI:About:InstallationOptions' => 'Installation options~~',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',	
	
	'UI:DisconnectedDlgMessage' => 'You are disconnected. You must identify yourself to continue using the application.~~',
	'UI:DisconnectedDlgTitle' => 'Warning!~~',
	'UI:LoginAgain' => 'Login again~~',
	'UI:StayOnThePage' => 'Stay on this page~~',
	
	'ExcelExporter:ExportMenu' => 'Excel Export...~~',
	'ExcelExporter:ExportDialogTitle' => 'Excel Export~~',
	'ExcelExporter:ExportButton' => 'Export~~',
	'ExcelExporter:DownloadButton' => 'Download %1$s~~',
	'ExcelExporter:RetrievingData' => 'Retrieving data...~~',
	'ExcelExporter:BuildingExcelFile' => 'Building the Excel file...~~',
	'ExcelExporter:Done' => 'Done.~~',
	'ExcelExport:AutoDownload' => 'Start the download automatically when the export is ready~~',
	'ExcelExport:PreparingExport' => 'Preparing the export...~~',
	'ExcelExport:Statistics' => 'Statistics~~',
	'portal:legacy_portal' => 'End-User Portal~~',
	'portal:backoffice' => 'iTop Back-Office User Interface~~',

	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.~~',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.~~',
	'UI:CurrentObjectLockExpired' => 'The lock to prevent concurrent modifications of the object has expired.~~',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.~~',
	'UI:ConcurrentLockKilled' => 'The lock preventing modifications on the current object has been deleted.~~',
	'UI:Menu:KillConcurrentLock' => 'Kill the Concurrent Modification Lock !~~',
	
	'UI:Menu:ExportPDF' => 'Export as PDF...~~',
	'UI:Menu:PrintableVersion' => 'Printer friendly version~~',
	
	'UI:BrowseInlineImages' => 'Browse images...~~',
	'UI:UploadInlineImageLegend' => 'Upload a new image~~',
	'UI:SelectInlineImageToUpload' => 'Select the image to upload~~',
	'UI:AvailableInlineImagesLegend' => 'Available images~~',
	'UI:NoInlineImage' => 'There is no image available on the server. Use the "Browse" button above to select an image from your computer and upload it to the server.~~',
	
	'UI:ToggleFullScreen' => 'Toggle Maximize / Minimize~~',
	'UI:Button:ResetImage' => 'Recover the previous image~~',
	'UI:Button:RemoveImage' => 'Remove the image~~',
	'UI:UploadNotSupportedInThisMode' => 'The modification of images or files is not supported in this mode.~~',

	// Search form
	'UI:Search:Toggle' => 'Minimize / Expand~~',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto submit has been disabled for this class~~',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.~~',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Add new criteria~~',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recently used~~',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Most popular~~',
	'UI:Search:AddCriteria:List:Others:Title' => 'Others~~',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'None yet.~~',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s is empty~~',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s is not empty~~',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s equals %2$s~~',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contains %2$s~~',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s starts with %2$s~~',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s ends with %2$s~~',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s matches %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s~~',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s~~',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s between [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s until %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s up to %2$s~~',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s~~',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Any~~',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s~~',
    //   - External key widget
    'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s is defined~~',
    'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s is not defined~~',
    'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s~~',
    'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s~~',
    'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
    'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Any~~',
    //   - Hierarchical key widget
    'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s is defined~~',
    'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s is not defined~~',
    'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s~~',
    'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s~~',
    'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
    'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Any~~',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Is empty~~',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Is not empty~~',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Equals~~',
	'UI:Search:Criteria:Operator:Default:Between' => 'Between~~',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contains~~',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Starts with~~',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Ends with~~',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Regular exp.~~',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Equals~~',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Greater~~',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Greater / equals~~',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Less~~',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Less / equals~~',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Different~~',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...~~',
	'UI:Search:Value:Search:Placeholder' => 'Search...~~',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Start typing for possible values.~~',
	'UI:Search:Value:Autocomplete:Wait' => 'Please wait...~~',
	'UI:Search:Value:Autocomplete:NoResult' => 'No result.~~',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Check all / none~~',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Check all / none visibles~~',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'From~~',
	'UI:Search:Criteria:Numeric:Until' => 'To~~',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Any~~',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Any~~',
	'UI:Search:Criteria:DateTime:From' => 'From~~',
	'UI:Search:Criteria:DateTime:FromTime' => 'From~~',
	'UI:Search:Criteria:DateTime:Until' => 'until~~',
	'UI:Search:Criteria:DateTime:UntilTime' => 'until~~',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Any date~~',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Children of the selected objects will be included.~~',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtered~~',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtered on %1$s~~',
));

//
// Expression to Natural language
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Expression:Operator:AND' => ' AND ~~',
	'Expression:Operator:OR' => ' OR ~~',
	'Expression:Operator:=' => ': ~~',

	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',

	'Expression:Unit:Long:DAY' => 'day(s)~~',
	'Expression:Unit:Long:HOUR' => 'hour(s)~~',
	'Expression:Unit:Long:MINUTE' => 'minute(s)~~',

	'Expression:Verb:NOW' => 'now~~',
	'Expression:Verb:ISNULL' => ': undefined~~',
));

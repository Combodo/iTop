<?php
/**
 * Localized data
 *
 * @author    Robert Deng <denglx@gmail.com>
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
	'Class:AuditCategory' => '审计类别',
	'Class:AuditCategory+' => '全部审计中的一个区段',
	'Class:AuditCategory/Attribute:name' => '类别名称',
	'Class:AuditCategory/Attribute:name+' => '类别简称',
	'Class:AuditCategory/Attribute:description' => '审计类别描述',
	'Class:AuditCategory/Attribute:description+' => '该审计类别的详细描述',
	'Class:AuditCategory/Attribute:definition_set' => '定义',
	'Class:AuditCategory/Attribute:definition_set+' => '定义用于审计的对象的OQL表达式',
	'Class:AuditCategory/Attribute:rules_list' => '审计规则',
	'Class:AuditCategory/Attribute:rules_list+' => '该类别的审计规则',
));

//
// Class: AuditRule
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:AuditRule' => '审计规则',
	'Class:AuditRule+' => '用于检查指定审计类别的规则',
	'Class:AuditRule/Attribute:name' => '名称',
	'Class:AuditRule/Attribute:name+' => '规则名称',
	'Class:AuditRule/Attribute:description' => '审计规则描述',
	'Class:AuditRule/Attribute:description+' => '审计规则详细描述',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => '要运行的查询',
	'Class:AuditRule/Attribute:query+' => '要运行的OQL 表达式',
	'Class:AuditRule/Attribute:valid_flag' => '是否有效?',
	'Class:AuditRule/Attribute:valid_flag+' => '若规则返回有效对象则True,否则False',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => '类别',
	'Class:AuditRule/Attribute:category_id+' => '该规则对应的类别',
	'Class:AuditRule/Attribute:category_name' => '类别',
	'Class:AuditRule/Attribute:category_name+' => '该规则对应类别的名称',
));

//
// Class: QueryOQL
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Query' => '查询',
	'Class:Query+' => '查询是一种动态的数据集',
	'Class:Query/Attribute:name' => '名称',
	'Class:Query/Attribute:name+' => '查询的名称',
	'Class:Query/Attribute:description' => '描述',
	'Class:Query/Attribute:description+' => '请描述本查询 (目的、用法等等.)',
	'Class:QueryOQL/Attribute:fields' => '区域',
	'Class:QueryOQL/Attribute:fields+' => '属性之间使用逗号分隔 (or alias.attribute) to export~~',
	'Class:QueryOQL' => 'OQL 查询',
	'Class:QueryOQL+' => 'A query based on the Object Query Language',
	'Class:QueryOQL/Attribute:oql' => '表达式',
	'Class:QueryOQL/Attribute:oql+' => 'OQL 表达式',
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
	'Class:User/Attribute:org_id' => '组织',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => '姓',
	'Class:User/Attribute:last_name+' => '对应联系人的姓氏',
	'Class:User/Attribute:first_name' => '名',
	'Class:User/Attribute:first_name+' => '对应联系人的名字',
	'Class:User/Attribute:email' => '邮箱',
	'Class:User/Attribute:email+' => '对应联系人的邮箱',
	'Class:User/Attribute:login' => '登录名',
	'Class:User/Attribute:login+' => '用户标识字符串',
	'Class:User/Attribute:language' => '语言',
	'Class:User/Attribute:language+' => '用户语言',
	'Class:User/Attribute:language/Value:EN US' => '英语',
	'Class:User/Attribute:language/Value:EN US+' => '英语 (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => '法语',
	'Class:User/Attribute:language/Value:FR FR+' => '法语 (France)',
	'Class:User/Attribute:profile_list' => '角色',
	'Class:User/Attribute:profile_list+' => '授予该用户的角色',
	'Class:User/Attribute:allowed_org_list' => '可访问的组织',
	'Class:User/Attribute:allowed_org_list+' => '目标用户可以看到以下组织的数据. 如果没有指定,则无限制.',
	'Class:User/Attribute:status' => '状态',
	'Class:User/Attribute:status+' => '账户是否启用.',
	'Class:User/Attribute:status/Value:enabled' => '启用',
	'Class:User/Attribute:status/Value:disabled' => '停用',

	'Class:User/Error:LoginMustBeUnique' => '登录名必须唯一 - "%1s" 已经被使用.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => '必须指定至少一个角色给该用户.',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => '必须为该用户指定一个组织.',
	'Class:User/Error:OrganizationNotAllowed' => '该组织不被允许.',
	'Class:User/Error:UserOrganizationNotAllowed' => '该用户账户不属于那个组织.',
	'Class:User/Error:PersonIsMandatory' => '联系人必填.',
	'Class:UserInternal' => '内部用户',
	'Class:UserInternal+' => 'iTop 内部定义的用户',
));

//
// Class: URP_Profiles
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_Profiles' => '角色',
	'Class:URP_Profiles+' => '用户角色',
	'Class:URP_Profiles/Attribute:name' => '名称',
	'Class:URP_Profiles/Attribute:name+' => '标签',
	'Class:URP_Profiles/Attribute:description' => '描述',
	'Class:URP_Profiles/Attribute:description+' => '单行描述',
	'Class:URP_Profiles/Attribute:user_list' => '用户',
	'Class:URP_Profiles/Attribute:user_list+' => '拥有该角色的用户',
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
	'Class:URP_Dimensions/Attribute:type' => '类型',
	'Class:URP_Dimensions/Attribute:type+' => '类型名称或数据类型 (投影单位)',
));

//
// Class: URP_UserProfile
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_UserProfile' => '角色目标用户',
	'Class:URP_UserProfile+' => '用户的角色',
	'Class:URP_UserProfile/Attribute:userid' => '用户',
	'Class:URP_UserProfile/Attribute:userid+' => '用户帐户',
	'Class:URP_UserProfile/Attribute:userlogin' => '登录名',
	'Class:URP_UserProfile/Attribute:userlogin+' => '用户的登录名',
	'Class:URP_UserProfile/Attribute:profileid' => '角色',
	'Class:URP_UserProfile/Attribute:profileid+' => '使用角色',
	'Class:URP_UserProfile/Attribute:profile' => '角色',
	'Class:URP_UserProfile/Attribute:profile+' => '角色名称',
	'Class:URP_UserProfile/Attribute:reason' => '原因',
	'Class:URP_UserProfile/Attribute:reason+' => '解释为什么此用户需要拥有该角色',
));

//
// Class: URP_UserOrg
//


Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_UserOrg' => '用户组织',
	'Class:URP_UserOrg+' => '可以访问的组织',
	'Class:URP_UserOrg/Attribute:userid' => '用户',
	'Class:URP_UserOrg/Attribute:userid+' => '用户帐户',
	'Class:URP_UserOrg/Attribute:userlogin' => '登录名',
	'Class:URP_UserOrg/Attribute:userlogin+' => '用户的登录名',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => '组织',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '可以访问的组织',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => '组织',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '可以访问的组织',
	'Class:URP_UserOrg/Attribute:reason' => '原因',
	'Class:URP_UserOrg/Attribute:reason+' => '解释为什么此用户可以访问该组织的数据',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ProfileProjection' => '角色映射',
	'Class:URP_ProfileProjection+' => '角色映射',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => '维度',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => '应用维度',
	'Class:URP_ProfileProjection/Attribute:dimension' => '维度',
	'Class:URP_ProfileProjection/Attribute:dimension+' => '应用维度',
	'Class:URP_ProfileProjection/Attribute:profileid' => '角色',
	'Class:URP_ProfileProjection/Attribute:profileid+' => '使用角色',
	'Class:URP_ProfileProjection/Attribute:profile' => '角色',
	'Class:URP_ProfileProjection/Attribute:profile+' => '角色名称',
	'Class:URP_ProfileProjection/Attribute:value' => '值表达式',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL 表达式 (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => '属性',
	'Class:URP_ProfileProjection/Attribute:attribute+' => '目标属性编码 (可选)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ClassProjection' => '类映射',
	'Class:URP_ClassProjection+' => '类映射',
	'Class:URP_ClassProjection/Attribute:dimensionid' => '维度',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => '应用维度',
	'Class:URP_ClassProjection/Attribute:dimension' => '维度',
	'Class:URP_ClassProjection/Attribute:dimension+' => '应用维度',
	'Class:URP_ClassProjection/Attribute:class' => '类',
	'Class:URP_ClassProjection/Attribute:class+' => '目标类',
	'Class:URP_ClassProjection/Attribute:value' => '值表达式',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL 表达式 (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => '属性',
	'Class:URP_ClassProjection/Attribute:attribute+' => '目标属性编码 (可选)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_ActionGrant' => '操作许可',
	'Class:URP_ActionGrant+' => '类上的许可',
	'Class:URP_ActionGrant/Attribute:profileid' => '角色',
	'Class:URP_ActionGrant/Attribute:profileid+' => '使用角色',
	'Class:URP_ActionGrant/Attribute:profile' => '角色',
	'Class:URP_ActionGrant/Attribute:profile+' => '使用角色',
	'Class:URP_ActionGrant/Attribute:class' => '类',
	'Class:URP_ActionGrant/Attribute:class+' => '目标类',
	'Class:URP_ActionGrant/Attribute:permission' => '许可',
	'Class:URP_ActionGrant/Attribute:permission+' => '允许或不允许?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => '是',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '是',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => '否',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '否',
	'Class:URP_ActionGrant/Attribute:action' => '操作',
	'Class:URP_ActionGrant/Attribute:action+' => '可用于指定类上的操作',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:URP_StimulusGrant' => '刺激许可',
	'Class:URP_StimulusGrant+' => '对象生命周期中刺激的许可',
	'Class:URP_StimulusGrant/Attribute:profileid' => '角色',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '使用角色',
	'Class:URP_StimulusGrant/Attribute:profile' => '角色',
	'Class:URP_StimulusGrant/Attribute:profile+' => '使用角色',
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
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => '操作准许',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '操作准许',
	'Class:URP_AttributeGrant/Attribute:attcode' => '属性',
	'Class:URP_AttributeGrant/Attribute:attcode+' => '属性编码',
));

//
// Class: UserDashboard
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:UserDashboard' => '用户面板',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => '用户',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => '菜单代码',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => '内容',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Expression:Unit:Short:DAY' => '日',
	'Expression:Unit:Short:WEEK' => '周',
	'Expression:Unit:Short:MONTH' => '月',
	'Expression:Unit:Short:YEAR' => '年',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'BooleanLabel:yes' => '是',
	'BooleanLabel:no' => '否',
	'UI:Login:Title' => 'iTop 登录',
	'Menu:WelcomeMenu' => '欢迎', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => '欢迎使用iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => '欢迎', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => '欢迎使用iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => '欢迎使用iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop 是完全开源的IT 操作门户.</p>
<ul>它包括:
<li>完整的CMDB(Configuration management database)，用于登记和管理您的IT 资产.</li>
<li>事件管理模块用于跟踪和传递所有发生在IT 系统中的事件.</li>
<li>变更管理模块用于规划和跟踪IT 环境中发生的变化.</li>
<li>已知错误数据库可加速事件的处理.</li>
<li>停机模块记录所有计划内的停机并通知对应的联系人.</li>
<li>通过仪表盘迅速获得IT 状态的概况.</li>
</ul>
<p>所有模块可以各自独立地、一步步地部署.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop 是面向服务供应商的, 它使得IT 工程师能够更方便地管理多客户和多组织.
<ul>iTop 提供功能丰富的业务处理流程:
<li>提高IT 管理效率</li> 
<li>提升IT 可操作能力</li> 
<li>提高用户满意度,提升业务能力.</li>
</ul>
</p>
<p>iTop 是完全开放的,可被集成到现有的IT 管理架构之中.</p>
<p>
<ul>利用这个新一代的IT 操作门户, 可以帮助您:
<li>更好地管理越来越复杂的IT 环境.</li>
<li>逐步实现ITIL 流程.</li>
<li>管理IT 中最重要的资产: 文档.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => '所有打开的需求: %1$d',
	'UI:WelcomeMenu:MyCalls' => '我办理的需求',
	'UI:WelcomeMenu:OpenIncidents' => '所有打开的事件: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => '配置项: %1$d',
	'UI:WelcomeMenu:MyIncidents' => '分配给我的事件',
	'UI:AllOrganizations' => ' 所有组织 ',
	'UI:YourSearch' => '搜索',
	'UI:LoggedAsMessage' => '以 %1$s 登录',
	'UI:LoggedAsMessage+Admin' => '以 %1$s 登录(Administrator)',
	'UI:Button:Logoff' => '注销',
	'UI:Button:GlobalSearch' => '搜索',
	'UI:Button:Search' => '搜索',
	'UI:Button:Query' => ' 查询 ',
	'UI:Button:Ok' => '确认',
	'UI:Button:Save' => '保存',
	'UI:Button:Cancel' => '取消',
	'UI:Button:Close' => '关闭',
	'UI:Button:Apply' => '应用',
	'UI:Button:Back' => ' << 上一步 ',
	'UI:Button:Restart' => ' |<< 重来 ',
	'UI:Button:Next' => ' 下一步 >> ',
	'UI:Button:Finish' => ' 结束 ',
	'UI:Button:DoImport' => ' 执行导入 ! ',
	'UI:Button:Done' => ' 完成 ',
	'UI:Button:SimulateImport' => ' 开始导入 ',
	'UI:Button:Test' => '测试!',
	'UI:Button:Evaluate' => ' 测试 ',
	'UI:Button:Evaluate:Title' => ' 评估 (Ctrl+Enter)',
	'UI:Button:AddObject' => ' 添加... ',
	'UI:Button:BrowseObjects' => ' 浏览... ',
	'UI:Button:Add' => ' 添加 ',
	'UI:Button:AddToList' => ' << 添加 ',
	'UI:Button:RemoveFromList' => ' 移除 >> ',
	'UI:Button:FilterList' => ' 过滤... ',
	'UI:Button:Create' => ' 创建 ',
	'UI:Button:Delete' => ' 删除 ! ',
	'UI:Button:Rename' => ' 重命名... ',
	'UI:Button:ChangePassword' => ' 修改密码 ',
	'UI:Button:ResetPassword' => ' 重置密码 ',
	'UI:Button:Insert' => '插入',
	'UI:Button:More' => '更多',
	'UI:Button:Less' => '更少',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => '搜索',
	'UI:ClickToCreateNew' => '新建 %1$s',
	'UI:SearchFor_Class' => '搜索 %1$s ',
	'UI:NoObjectToDisplay' => '没有可显示的对象.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => '当link_attr 被指定时,参数 object_id 是必须的. 检查显示模板的定义.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => '当link_attr 被指定时, 参数 target_attr 是必须的. 检查显示模板的定义.',
	'UI:Error:MandatoryTemplateParameter_group_by' => '参数 group_by 是必须的. 检查显示模板的定义.',
	'UI:Error:InvalidGroupByFields' => 'group by 的栏目列表是无效的: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => '错误: 不被支持的 block 格式: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => '关联错误: 关联的对象: %1$s 不是 %2$s 的外键',
	'UI:Error:Object_Class_Id_NotFound' => '对象: %1$s:%2$d 找不到.',
	'UI:Error:WizardCircularReferenceInDependencies' => '错误: 栏目之间的依赖性出现循环引用, 请检查数据模型.',
	'UI:Error:UploadedFileTooBig' => '上传文件太大. (允许的最大限制是 %1$s). 请检查 PHP 配置文件中的 upload_max_filesize 和 post_max_size.',
	'UI:Error:UploadedFileTruncated.' => '上传的文件被截断 !',
	'UI:Error:NoTmpDir' => '未定义临时目录.',
	'UI:Error:CannotWriteToTmp_Dir' => '无法向硬盘写入临时文件. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => '上传因为扩展名被停止. (Original file name = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => '文件上传失败, 原因未知. (Error code = "%1$s").',

	'UI:Error:1ParametersMissing' => '错误: 必须为该操作指定以下参数: %1$s.',
	'UI:Error:2ParametersMissing' => '错误: 必须为该操作指定以下参数: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => '错误: 必须为该操作指定以下参数: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => '错误: 必须为该操作指定以下参数: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => '错误: 错误的 OQL 查询: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => '运行该查询时发生了一个错误: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => '错误: 该对象已更新.',
	'UI:Error:ObjectCannotBeUpdated' => '错误: 对象无法更新.',
	'UI:Error:ObjectsAlreadyDeleted' => '错误: 对象已被删除!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => '您无权进行 %1$s 类对象的批量删除',
	'UI:Error:DeleteNotAllowedOn_Class' => '您无权删除 %1$s 类的对象',
	'UI:Error:BulkModifyNotAllowedOn_Class' => '您无权进行 %1$s 类对象的批量更新',
	'UI:Error:ObjectAlreadyCloned' => '错误: 该对象已被克隆!',
	'UI:Error:ObjectAlreadyCreated' => '错误: 该对象已被创建!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => '错误: 在对象 %2$s 的 "%3$s" 状态上的无效刺激 "%1$s" .',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

	'UI:GroupBy:Count' => '个数',
	'UI:GroupBy:Count+' => '元素数量',
	'UI:CountOfObjects' => '%1$d 个对象符合指定的条件.',
	'UI_CountOfObjectsShort' => '%1$d 个对象.',
	'UI:NoObject_Class_ToDisplay' => '没有 %1$s 可以显示',
	'UI:History:LastModified_On_By' => '最后修改 %1$s 被 %2$s.',
	'UI:HistoryTab' => '历史',
	'UI:NotificationsTab' => '通知',
	'UI:History:BulkImports' => '历史',
	'UI:History:BulkImports+' => 'List of CSV imports (latest import first)',
	'UI:History:BulkImportDetails' => 'Changes resulting from the CSV import performed on %1$s (by %2$s)',
	'UI:History:Date' => '日期',
	'UI:History:Date+' => '变更日期',
	'UI:History:User' => '用户',
	'UI:History:User+' => '造成变更的用户',
	'UI:History:Changes' => '变更',
	'UI:History:Changes+' => '对该对象所做的变更',
	'UI:History:StatsCreations' => '已创建',
	'UI:History:StatsCreations+' => '已创建的对象个数',
	'UI:History:StatsModifs' => '已修改',
	'UI:History:StatsModifs+' => '已修改的对象个数',
	'UI:History:StatsDeletes' => '已删除',
	'UI:History:StatsDeletes+' => '已删除的对象个数',
	'UI:Loading' => '载入...',
	'UI:Menu:Actions' => '操作',
	'UI:Menu:OtherActions' => '其他操作',
	'UI:Menu:New' => '新建...',
	'UI:Menu:Add' => '添加...',
	'UI:Menu:Manage' => '管理...',
	'UI:Menu:EMail' => '邮件',
	'UI:Menu:CSVExport' => 'CSV 导出...',
	'UI:Menu:Modify' => '修改...',
	'UI:Menu:Delete' => '删除...',
	'UI:Menu:BulkDelete' => '删除...',
	'UI:UndefinedObject' => '未定义',
	'UI:Document:OpenInNewWindow:Download' => '在新窗口打开: %1$s, 下载: %2$s',
	'UI:SplitDateTime-Date' => '日期',
	'UI:SplitDateTime-Time' => '时间',
	'UI:TruncatedResults' => '显示 %1$d 个对象，共 %2$d 个',
	'UI:DisplayAll' => '全部显示',
	'UI:CollapseList' => '收起',
	'UI:CountOfResults' => '%1$d 个对象',
	'UI:ChangesLogTitle' => '变更记录 (%1$d):',
	'UI:EmptyChangesLogTitle' => '变更记录为空',
	'UI:SearchFor_Class_Objects' => '搜索 %1$s ',
	'UI:OQLQueryBuilderTitle' => 'OQL 查询构建器',
	'UI:OQLQueryTab' => 'OQL 查询',
	'UI:SimpleSearchTab' => '简单搜索',
	'UI:Details+' => '详情',
	'UI:SearchValue:Any' => '* 任何 *',
	'UI:SearchValue:Mixed' => '* 混合 *',
	'UI:SearchValue:NbSelected' => '# 已选择',
	'UI:SearchValue:CheckAll' => '全选',
	'UI:SearchValue:UncheckAll' => '反选',
	'UI:SelectOne' => '-- 请选择 --',
	'UI:Login:Welcome' => '欢迎使用iTop!',
	'UI:Login:IncorrectLoginPassword' => '用户名或密码错误, 请重试.',
	'UI:Login:IdentifyYourself' => '请完成身份认证',
	'UI:Login:UserNamePrompt' => '用户名',
	'UI:Login:PasswordPrompt' => '密码',
	'UI:Login:ForgotPwd' => '忘记密码?',
	'UI:Login:ForgotPwdForm' => '忘记密码',
	'UI:Login:ForgotPwdForm+' => 'iTop 将会给您发送一封密码重置邮件.',
	'UI:Login:ResetPassword' => '立即发送!',
	'UI:Login:ResetPwdFailed' => '邮件发送失败: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' 用户名无效',
	'UI:ResetPwd-Error-NotPossible' => '外部账户不允许重置密码.',
	'UI:ResetPwd-Error-FixedPwd' => '该账户不允许重置密码.',
	'UI:ResetPwd-Error-NoContact' => '该账户没有关联到个人.',
	'UI:ResetPwd-Error-NoEmailAtt' => '该账户未关联邮箱地址，请联系管理员.',
	'UI:ResetPwd-Error-NoEmail' => '缺少邮箱地址. 请联系管理员.',
	'UI:ResetPwd-Error-Send' => '邮件传输存在技术原因. 请联系管理员.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => '重置iTop 密码',
	'UI:ResetPwd-EmailBody' => '<body><p>您已请求重置iTop 密码.</p><p>请点击这个链接 (一次性) <a href="%1$s">来输入新的密码</a></p>.',

	'UI:ResetPwd-Title' => '重置密码',
	'UI:ResetPwd-Error-InvalidToken' => '对不起, 密码已经被重置, 请检查是否收到了多封密码重置邮件. 请点击最新邮件里的链接.',
	'UI:ResetPwd-Error-EnterPassword' => '请输入 \'%1$s\' 的新密码.',
	'UI:ResetPwd-Ready' => '密码已修改成功.',
	'UI:ResetPwd-Login' => '点击这里登录...',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => '修改您的密码',
	'UI:Login:OldPasswordPrompt' => '旧密码',
	'UI:Login:NewPasswordPrompt' => '新密码',
	'UI:Login:RetypeNewPasswordPrompt' => '重复新密码',
	'UI:Login:IncorrectOldPassword' => '错误: 旧密码错误',
	'UI:LogOffMenu' => '注销',
	'UI:LogOff:ThankYou' => '感谢使用iTop',
	'UI:LogOff:ClickHereToLoginAgain' => '点击这里再次登录...',
	'UI:ChangePwdMenu' => '修改密码...',
	'UI:Login:PasswordChanged' => '密码已成功设置!',
	'UI:AccessRO-All' => 'iTop 是只读的',
	'UI:AccessRO-Users' => 'iTop 对于终端用户是只读的',
	'UI:ApplicationEnvironment' => '应用环境: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => '新密码输入不一致!',
	'UI:Button:Login' => '登录iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop 访问被限制. 请联系管理员.',
	'UI:Login:Error:AccessAdmin' => '只有具有管理员权限的人才能访问. 请联系管理员.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne' => '-- 请选择 --',
	'UI:CSVImport:MappingNotApplicable' => '-- 忽略该栏 --',
	'UI:CSVImport:NoData' => '数据为空..., 请提供数据!',
	'UI:Title:DataPreview' => '数据预览',
	'UI:CSVImport:ErrorOnlyOneColumn' => '错误: 数据仅包含一列. 您选择了合适的分隔符了吗?',
	'UI:CSVImport:FieldName' => '栏 %1$d',
	'UI:CSVImport:DataLine1' => '数据行 1',
	'UI:CSVImport:DataLine2' => '数据行 2',
	'UI:CSVImport:idField' => 'id (主键)',
	'UI:Title:BulkImport' => 'iTop - 批量导入',
	'UI:Title:BulkImport+' => 'CSV 导入向导',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => '同步 %2$s 个对象中的 %1$d',
	'UI:CSVImport:ClassesSelectOne' => '-- 请选择 --',
	'UI:CSVImport:ErrorExtendedAttCode' => '内部错误: "%1$s" 是错误的编码, 因为 "%2$s" 不是类 "%3$s" 的外键',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d 个对象保持不变.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d 个对象将被修改.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d 个对象将被添加.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d 个对象将发生错误.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d 个对象保持不变.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d 个对象已被修改.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d 个对象已被添加.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d 个对象发生错误.',
	'UI:Title:CSVImportStep2' => '步骤 2 of 5: CSV 数据选项',
	'UI:Title:CSVImportStep3' => '步骤 3 of 5: 数据映射',
	'UI:Title:CSVImportStep4' => '步骤 4 of 5: 模拟导入',
	'UI:Title:CSVImportStep5' => '步骤 5 of 5: 完成导入',
	'UI:CSVImport:LinesNotImported' => '无法导入的行:',
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
	'UI:CSVImport:SelectFile' => '选择要导入的文件:',
	'UI:CSVImport:Tab:LoadFromFile' => '从文件导入',
	'UI:CSVImport:Tab:CopyPaste' => '复制和粘贴的数据',
	'UI:CSVImport:Tab:Templates' => '模板',
	'UI:CSVImport:PasteData' => '粘贴数据以导入:',
	'UI:CSVImport:PickClassForTemplate' => '选择模板: ',
	'UI:CSVImport:SeparatorCharacter' => '分隔符:',
	'UI:CSVImport:TextQualifierCharacter' => '文本限定字符',
	'UI:CSVImport:CommentsAndHeader' => '注释和头',
	'UI:CSVImport:SelectClass' => '选择要导入的类别:',
	'UI:CSVImport:AdvancedMode' => '高级模式',
	'UI:CSVImport:AdvancedMode+' => '在高级模式中,对象的"id" (主键) 可以被用来修改和重命名对象.不管怎样,列 "id" (如果存在) 只能被用做一个搜索条件,不能与其它搜索条件混用.',
	'UI:CSVImport:SelectAClassFirst' => '要配置映射，请先选择一个类.',
	'UI:CSVImport:HeaderFields' => '栏目',
	'UI:CSVImport:HeaderMappings' => '映射',
	'UI:CSVImport:HeaderSearch' => '搜索?',
	'UI:CSVImport:AlertIncompleteMapping' => '请为每个栏选择一个映射.',
	'UI:CSVImport:AlertMultipleMapping' => '请确保目标区域仅被映射一次.',
	'UI:CSVImport:AlertNoSearchCriteria' => '请选择至少一个搜索条件',
	'UI:CSVImport:Encoding' => '字符编码',
	'UI:UniversalSearchTitle' => 'iTop - 全局搜索',
	'UI:UniversalSearch:Error' => '错误: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => '选择要搜索的类别: ',

	'UI:CSVReport-Value-Modified' => '已修改',
	'UI:CSVReport-Value-SetIssue' => '无法修改 - 原因: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => '无法修改成 %1$s - 原因: %2$s',
	'UI:CSVReport-Value-NoMatch' => '不匹配',
	'UI:CSVReport-Value-Missing' => '缺少必填项',
	'UI:CSVReport-Value-Ambiguous' => '模糊匹配: 找到 %1$s 个对象',
	'UI:CSVReport-Row-Unchanged' => '保持不变',
	'UI:CSVReport-Row-Created' => '新建',
	'UI:CSVReport-Row-Updated' => '已更新 %1$d 列',
	'UI:CSVReport-Row-Disappeared' => '已消失, %1$d 列发生变化',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s',
	'UI:CSVReport-Value-Issue-Null' => '不允许留空',
	'UI:CSVReport-Value-Issue-NotFound' => '对象找不到',
	'UI:CSVReport-Value-Issue-FoundMany' => '找到 %1$d 个匹配项',
	'UI:CSVReport-Value-Issue-Readonly' => '\'%1$s\' 的属性是只读的,不能修改 (当前值: %2$s, 建议值: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => '输入处理失败: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': 无法匹配, 请检查拼写',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => '属性不一致: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => '错误的属性',
	'UI:CSVReport-Row-Issue-MissingExtKey' => '创建失败, 因为缺少外键: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => '日期格式错误',
	'UI:CSVReport-Row-Issue-Reconciliation' => '无法调和',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation',
	'UI:CSVReport-Row-Issue-Internal' => '内部错误: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => '保持不变',
	'UI:CSVReport-Icon-Modified' => '修改',
	'UI:CSVReport-Icon-Missing' => '丢失',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated',
	'UI:CSVReport-Icon-Created' => '创建',
	'UI:CSVReport-Object-ToCreate' => '对象将被创建',
	'UI:CSVReport-Object-Created' => '对象已创建',
	'UI:CSVReport-Icon-Error' => '错误',
	'UI:CSVReport-Object-Error' => '错误: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% 已加载的对象包含错误，它们将会被忽略.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% 已加载的对象将会被创建.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% 已加载的对象将会被修改.',

	'UI:CSVExport:AdvancedMode' => '高级模式',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.',
	'UI:CSVExport:LostChars' => '编码问题',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => 'iTop - CMDB 审计',
	'UI:Audit:InteractiveAudit' => '交互审计',
	'UI:Audit:HeaderAuditRule' => '审计规则',
	'UI:Audit:HeaderNbObjects' => '# 对象',
	'UI:Audit:HeaderNbErrors' => '# 错误',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - OQL 查询评估',
	'UI:RunQuery:QueryExamples' => '示例查询',
	'UI:RunQuery:HeaderPurpose' => '目的',
	'UI:RunQuery:HeaderPurpose+' => '该查询的解释',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL 表达式',
	'UI:RunQuery:HeaderOQLExpression+' => 'OQL 语法表示的查询',
	'UI:RunQuery:ExpressionToEvaluate' => '请输入表达式: ',
	'UI:RunQuery:MoreInfo' => '该查询的更多信息: ',
	'UI:RunQuery:DevelopedQuery' => '重新开发的查询表达式: ',
	'UI:RunQuery:SerializedFilter' => '序列化的过滤器: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => '运行该查询时发生了一个错误: %1$s',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested here below points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. <br/>Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.',
	'UI:Schema:Title' => 'iTop 对象模型',
	'UI:Schema:CategoryMenuItem' => '类别 <b>%1$s</b>',
	'UI:Schema:Relationships' => '关联',
	'UI:Schema:AbstractClass' => '抽象类: 该类不能实例化对象.',
	'UI:Schema:NonAbstractClass' => '非抽象类: 该类可以实例化对象.',
	'UI:Schema:ClassHierarchyTitle' => '类层级',
	'UI:Schema:AllClasses' => '所有类',
	'UI:Schema:ExternalKey_To' => '%1$s的外键',
	'UI:Schema:Columns_Description' => '列: <em>%1$s</em>',
	'UI:Schema:Default_Description' => '缺省: "%1$s"',
	'UI:Schema:NullAllowed' => '允许留空',
	'UI:Schema:NullNotAllowed' => '不允许留空',
	'UI:Schema:Attributes' => '属性',
	'UI:Schema:AttributeCode' => '属性编码',
	'UI:Schema:AttributeCode+' => '属性的内部编码',
	'UI:Schema:Label' => '标签',
	'UI:Schema:Label+' => '属性标签',
	'UI:Schema:Type' => '类别',

	'UI:Schema:Type+' => '属性的数据类型',
	'UI:Schema:Origin' => '来自',
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
	'UI:Schema:AvailOperators' => '可用的运算符',
	'UI:Schema:AvailOperators+' => '该搜索条件可能的运算符',
	'UI:Schema:ChildClasses' => '子类',
	'UI:Schema:ReferencingClasses' => '相关类',
	'UI:Schema:RelatedClasses' => '相关类',
	'UI:Schema:LifeCycle' => '生命周期',
	'UI:Schema:Triggers' => '触发器',
	'UI:Schema:Relation_Code_Description' => '关联 <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => '向下: %1$s',
	'UI:Schema:RelationUp_Description' => '向上: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: 繁殖到 %2$d 个层级, 查询: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: 没有繁殖 (%2$d 层级), 查询: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s 被类 %2$s 参照, 通过栏目 %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s 被链接到 %2$s 通过 %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => '类指向 %1$s (1:n 链接):',
	'UI:Schema:Links:n-n' => '类链接到 %1$s (n:n 链接):',
	'UI:Schema:Links:All' => '全部相关类的图',
	'UI:Schema:NoLifeCyle' => '该类没有生命周期的定义.',
	'UI:Schema:LifeCycleTransitions' => '状态和转换',
	'UI:Schema:LifeCyleAttributeOptions' => '属性选项',
	'UI:Schema:LifeCycleHiddenAttribute' => '隐藏',
	'UI:Schema:LifeCycleReadOnlyAttribute' => '只读',
	'UI:Schema:LifeCycleMandatoryAttribute' => '必须',
	'UI:Schema:LifeCycleAttributeMustChange' => '必须变更',
	'UI:Schema:LifeCycleAttributeMustPrompt' => '用户将被提示改变值',
	'UI:Schema:LifeCycleEmptyList' => '空列表',
	'UI:Schema:ClassFilter' => '类别:',
	'UI:Schema:DisplayLabel' => '显示:',
	'UI:Schema:DisplaySelector/LabelAndCode' => '标记和代码',
	'UI:Schema:DisplaySelector/Label' => '标记',
	'UI:Schema:DisplaySelector/Code' => '代码',
	'UI:Schema:Attribute/Filter' => '过滤器',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => '输入前3个字符...',
	'UI:Edit:TestQuery' => '测试查询',
	'UI:Combo:SelectValue' => '--- 请选择 ---',
	'UI:Label:SelectedObjects' => '被选的对象: ',
	'UI:Label:AvailableObjects' => '可用的对象: ',
	'UI:Link_Class_Attributes' => '%1$s 属性',
	'UI:SelectAllToggle+' => '全选 / 反选',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => '添加 %1$s 个对象, 链接 %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => ' %1$s ',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => '管理 %1$s 个对象, 链接 %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => '添加 %1$s...',
	'UI:RemoveLinkedObjectsOf_Class' => '移除对象',
	'UI:Message:EmptyList:UseAdd' => '列表为空, 请使用 "添加..." 按扭来添加元素.',
	'UI:Message:EmptyList:UseSearchForm' => '使用上面的搜索表单, 以搜索要添加的对象.',
	'UI:Wizard:FinalStepTitle' => '最后一步: 确认',
	'UI:Title:DeletionOf_Object' => '删除 %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '批量删除 %1$d 个 %2$s 类的对象',
	'UI:Delete:NotAllowedToDelete' => '您无权删除该对象',
	'UI:Delete:NotAllowedToUpdate_Fields' => '您无权更新以下栏目: %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => '无法删除该对象, 因为当前用户没有足够的权限',
	'UI:Error:CannotDeleteBecause' => '无法删除该对象，因为: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => '无法删除该对象, 因为必须事先完成一些手动操作',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => '无法删除该对象，必须事先完成一些手动操作',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:Deleted' => '已删除',
	'UI:Delete:AutomaticallyDeleted' => '已自动删除',
	'UI:Delete:AutomaticResetOf_Fields' => '自动重置栏目: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => '删除所有对 %1$s 的引用...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '删除所有对 %2$s 类的 %1$d 个对象的引用...',
	'UI:Delete:Done+' => '做了什么...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s 删除了.',
	'UI:Delete:ConfirmDeletionOf_Name' => '删除 %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '删除 %2$s 类的 %1$d 个对象',
	'UI:Delete:CannotDeleteBecause' => '无法删除: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => '应该自动删除, 但您无权这样做',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => '必须手动删除 - 但您无权删除该对象, 请联系管理员',
	'UI:Delete:WillBeDeletedAutomatically' => '将被自动删除',
	'UI:Delete:MustBeDeletedManually' => '必须手动删除',
	'UI:Delete:CannotUpdateBecause_Issue' => '应该被自动更新, 但是: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => '将被自动更新 (重置: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '一共 %1$d 个对象/链接 关联了 %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d 个对象/链接 关联了一些即将要删除的对象',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => '为了确保数据库的完整性, 任何与之关联的项目也会被删除',
	'UI:Delete:Consequence+' => '要做什么',
	'UI:Delete:SorryDeletionNotAllowed' => '抱歉, 您无权删除该对象, 请看上述详细解释',
	'UI:Delete:PleaseDoTheManualOperations' => '在删除该对象之前, 请先手工完成上述列出的操作',
	'UI:Delect:Confirm_Object' => '请确认要删除 %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => '请确认要删除下列 %2$s 类的 %1$d 个对象.',
	'UI:WelcomeToITop' => '欢迎使用iTop ',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s 详细内容',
	'UI:ErrorPageTitle' => 'iTop - 错误',
	'UI:ObjectDoesNotExist' => '抱歉, 该对象不存在 (或无权浏览该对象).',
	'UI:ObjectArchived' => '对象已被归档. 请启用归档模式或联系管理员.',
	'Tag:Archived' => '已归档',
	'Tag:Archived+' => '仅能在归档模式下访问',
	'Tag:Obsolete' => '已废弃',
	'Tag:Obsolete+' => '从影响分析和搜索结果中排除',
	'Tag:Synchronized' => '已同步',
	'ObjectRef:Archived' => '已归档',
	'ObjectRef:Obsolete' => '已废弃',
	'UI:SearchResultsPageTitle' => 'iTop - 搜索结果',
	'UI:SearchResultsTitle' => '搜索结果',
	'UI:SearchResultsTitle+' => '全文搜索结果',
	'UI:Search:NoSearch' => '没有可搜索的内容',
	'UI:Search:NeedleTooShort' => '字符串 "%1$s" 太短. 请至少输入 %2$d 个字符.',
	'UI:Search:Ongoing' => '正在搜索 "%1$s"',
	'UI:Search:Enlarge' => 'Broaden the search',
	'UI:FullTextSearchTitle_Text' => '"%1$s" 的结果:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '发现 %2$s 类的 %1$d 个对象.',
	'UI:Search:NoObjectFound' => '未发现对象.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s 修改',
	'UI:ModificationTitle_Class_Object' => '修改 %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - 克隆 %1$s - %2$s 修改',
	'UI:CloneTitle_Class_Object' => '克隆 %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - 新建 %1$s ',
	'UI:CreationTitle_Class' => '新建 %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => '选择 %1$s 的类别:',
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

	'UI:PageTitle:ClassProjections' => 'iTop 用户管理 - 类映射',
	'UI:PageTitle:ProfileProjections' => 'iTop 用户管理 - 角色映射',
	'UI:UserManagement:Class' => '类',
	'UI:UserManagement:Class+' => '对象的类',
	'UI:UserManagement:ProjectedObject' => '对象',
	'UI:UserManagement:ProjectedObject+' => '被映射的对象',
	'UI:UserManagement:AnyObject' => '* 任何 *',
	'UI:UserManagement:User' => '用户',
	'UI:UserManagement:User+' => '与该映射相关的用户',
	'UI:UserManagement:Profile' => '角色',
	'UI:UserManagement:Profile+' => '映射被指定的角色',
	'UI:UserManagement:Action:Read' => '读',
	'UI:UserManagement:Action:Read+' => '读/显示 对象',
	'UI:UserManagement:Action:Modify' => '修改',
	'UI:UserManagement:Action:Modify+' => '创建和编辑(修改)对象',
	'UI:UserManagement:Action:Delete' => '删除',
	'UI:UserManagement:Action:Delete+' => '删除对象',
	'UI:UserManagement:Action:BulkRead' => '批量读取(导出)',
	'UI:UserManagement:Action:BulkRead+' => '列出对象或批量导出',
	'UI:UserManagement:Action:BulkModify' => '批量修改',
	'UI:UserManagement:Action:BulkModify+' => '批量创建/编辑 (CSV 导入)',
	'UI:UserManagement:Action:BulkDelete' => '批量删除',
	'UI:UserManagement:Action:BulkDelete+' => '批量删除对象',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => '许可的 (复合的) 操作',
	'UI:UserManagement:Action' => '操作',
	'UI:UserManagement:Action+' => '该用户进行的操作',
	'UI:UserManagement:TitleActions' => '操作',
	'UI:UserManagement:Permission' => '许可',
	'UI:UserManagement:Permission+' => '用户的许可',
	'UI:UserManagement:Attributes' => '属性',
	'UI:UserManagement:ActionAllowed:Yes' => '是',
	'UI:UserManagement:ActionAllowed:No' => '否',
	'UI:UserManagement:AdminProfile+' => '管理员拥有数据库中所有对象完整的读/写/访问权限.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '该类未定义生命周期',
	'UI:UserManagement:GrantMatrix' => '授权矩阵',
	'UI:UserManagement:LinkBetween_User_And_Profile' => '链接 %1$s 和 %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => '链接 %1$s 和 %2$s',

	'Menu:AdminTools' => '管理工具', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '管理工具', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => '具有管理员角色的用户才能获得的工具', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => '变更管理',
	'UI:ChangeManagementMenu+' => '变更管理',
	'UI:ChangeManagementMenu:Title' => '变更概况',
	'UI-ChangeManagementMenu-ChangesByType' => '按类别划分的变更',
	'UI-ChangeManagementMenu-ChangesByStatus' => '按状态划分的变更',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => '按执行团队划分的变更',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => '尚未分配的变更',

	'UI:ConfigurationManagementMenu' => '配置管理',
	'UI:ConfigurationManagementMenu+' => '配置管理',
	'UI:ConfigurationManagementMenu:Title' => '基础架构概况',
	'UI-ConfigurationManagementMenu-InfraByType' => '按类别划分基础架构对象',
	'UI-ConfigurationManagementMenu-InfraByStatus' => '按状态划分基础架构对象',

	'UI:ConfigMgmtMenuOverview:Title' => '配置管理仪表盘',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => '按状态配置项目',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => '按类别配置项目',

	'UI:RequestMgmtMenuOverview:Title' => '需求管理仪表盘',
	'UI-RequestManagementOverview-RequestByService' => '按服务划分用户需求',
	'UI-RequestManagementOverview-RequestByPriority' => '按优先级划分用户需求',
	'UI-RequestManagementOverview-RequestUnassigned' => '尚未分配给办理人的用户需求',

	'UI:IncidentMgmtMenuOverview:Title' => '事件管理仪表盘',
	'UI-IncidentManagementOverview-IncidentByService' => '按服务级划分事件',
	'UI-IncidentManagementOverview-IncidentByPriority' => '按优先级划分事件',
	'UI-IncidentManagementOverview-IncidentUnassigned' => '尚未分配给办理人的事件',

	'UI:ChangeMgmtMenuOverview:Title' => '变更管理仪表盘',
	'UI-ChangeManagementOverview-ChangeByType' => '按类别划分变更',
	'UI-ChangeManagementOverview-ChangeUnassigned' => '尚未分配给办理人的变更',
	'UI-ChangeManagementOverview-ChangeWithOutage' => '变更引起的停机',

	'UI:ServiceMgmtMenuOverview:Title' => '服务管理仪表盘',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => '客户合同需在30日内更新',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => '供应商合同需在30日内更新',

	'UI:ContactsMenu' => '联系人',
	'UI:ContactsMenu+' => '联系人',
	'UI:ContactsMenu:Title' => '联系人概况',
	'UI-ContactsMenu-ContactsByLocation' => '按地理位置划分联系人',
	'UI-ContactsMenu-ContactsByType' => '按类别划分联系人',
	'UI-ContactsMenu-ContactsByStatus' => '按状态划分联系人',

	'Menu:CSVImportMenu' => 'CSV 导入', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '批量创建或修改', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => '数据模型', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '数据模型概况', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => '导出', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '以HTML, CSV or XML格式导出任何查询的结果', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => '通知', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '通知的配置', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => '配置 <span class="hilite">通知</span>',
	'UI:NotificationsMenu:Help' => '帮助',
	'UI:NotificationsMenu:HelpContent' => '<p>在iTop 中, 通知可以被自定义. 它们是基于两个对象集: <i>触发器和操作</i>.</p>
<p><i><b>触发器</b></i> 定义了什么时候发送通知. iTop core 自带一些触发器, 另一些触发器可由扩展提供:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>操作</b></i> 定义了触发时要执行的操作. 目前, 仅有的一种操作就是外发邮件.
包含邮件模板（定义发件人、收件人、重要性等）.
</p>
<p>这里有一个专用的页面: <a href="../setup/email.test.php" target="_blank">email.test.php</a> 可用于测试和调试PHP 的邮件配置.</p>
<p>若要执行, 操作必须和触发器相关联.
当与一个触发器关联时, 每个操作都被赋予一个顺序号, 规定了按什么样的顺序执行这些操作.</p>~~',
	'UI:NotificationsMenu:Triggers' => '触发器',
	'UI:NotificationsMenu:AvailableTriggers' => '可用的触发器',
	'UI:NotificationsMenu:OnCreate' => '当对象被创建',
	'UI:NotificationsMenu:OnStateEnter' => '当对象进入指定状态',
	'UI:NotificationsMenu:OnStateLeave' => '当对象离开指定状态',
	'UI:NotificationsMenu:Actions' => '操作',
	'UI:NotificationsMenu:AvailableActions' => '有效的操作',

	'Menu:TagAdminMenu' => '标签配置',
	'Menu:TagAdminMenu+' => '标签值管理',
	'UI:TagAdminMenu:Title' => '标签配置',
	'UI:TagAdminMenu:NoTags' => '未配置标签',
	'UI:TagSetFieldData:Error' => '错误: %1$s',

	'Menu:AuditCategories' => '审计类别', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '审计类别', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => '审计类别', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => '运行查询', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '运行任何查询', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => '查询手册', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => '查询手册', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => '数据管理', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '数据管理', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => '全局搜索', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '搜索所有...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => '用户管理', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => '用户管理', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => '角色', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => '角色', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => '角色', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => '用户帐户', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s version %2$s',
	'UI:iTopVersion:Long' => '%1$s version %2$s-%3$s built on %4$s',
	'UI:PropertiesTab' => '属性',

	'UI:OpenDocumentInNewWindow_' => '在新窗口打开文档: %1$s',
	'UI:DownloadDocument_' => '下载该文档: %1$s',
	'UI:Document:NoPreview' => '该类文档无法预览',
	'UI:Download-CSV' => '下载 %1$s',

	'UI:DeadlineMissedBy_duration' => '超过 %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 分钟',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => '帮助',
	'UI:PasswordConfirm' => '(确认)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => '在添加更多 %1$s 之前, 保存该对象.',
	'UI:DisplayThisMessageAtStartup' => '在启动时显示此消息',
	'UI:RelationshipGraph' => '图览',
	'UI:RelationshipList' => '列表',
	'UI:RelationGroups' => '组',
	'UI:OperationCancelled' => '操作已取消',
	'UI:ElementsDisplayed' => '过滤',
	'UI:RelationGroupNumber_N' => 'Group #%1$d',
	'UI:Relation:ExportAsPDF' => '导出PDF...',
	'UI:RelationOption:GroupingThreshold' => '分组阀值',
	'UI:Relation:AdditionalContextInfo' => '其他信息',
	'UI:Relation:NoneSelected' => '无',
	'UI:Relation:Zoom' => '放大',
	'UI:Relation:ExportAsAttachment' => '导出为附件...',
	'UI:Relation:DrillDown' => '详情...',
	'UI:Relation:PDFExportOptions' => 'PDF 导出选项',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s',
	'UI:RelationOption:Untitled' => '无标题',
	'UI:Relation:Key' => 'Key',
	'UI:Relation:Comments' => '备注',
	'UI:RelationOption:Title' => '标题',
	'UI:RelationOption:IncludeList' => '包含的对象列表',
	'UI:RelationOption:Comments' => '备注',
	'UI:Button:Export' => '导出',
	'UI:Relation:PDFExportPageFormat' => '页面格式',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => '信纸',
	'UI:Relation:PDFExportPageOrientation' => '页面方向',
	'UI:PageOrientation_Portrait' => '纵向',
	'UI:PageOrientation_Landscape' => '横向',
	'UI:RelationTooltip:Redundancy' => '冗余',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# 受影响的项目: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => '阀值: %1$d / %2$d',
	'Portal:Title' => 'iTop 用户门户',
	'Portal:NoRequestMgmt' => '亲爱的 %1$s, 您被重定向到这个页面,因为您的账户已被设置成角色 \'Portal user\'. 并且, iTop 没有安装 \'需求管理\' 功能. 请联系管理员.',
	'Portal:Refresh' => '刷新',
	'Portal:Back' => '返回',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => '需求详情',
	'Portal:ShowOngoing' => '显示打开的需求',
	'Portal:ShowClosed' => '显示已关闭的需求',
	'Portal:CreateNewRequest' => '新建需求',
	'Portal:CreateNewRequestItil' => '新建需求',
	'Portal:CreateNewIncidentItil' => '新建事件报告',
	'Portal:ChangeMyPassword' => '修改密码',
	'Portal:Disconnect' => '断开',
	'Portal:OpenRequests' => '我打开的需求',
	'Portal:ClosedRequests' => '我已关闭的需求',
	'Portal:ResolvedRequests' => '已解决的需求',
	'Portal:SelectService' => '从类别中选择一项服务:',
	'Portal:PleaseSelectOneService' => '请选择一项服务',
	'Portal:SelectSubcategoryFrom_Service' => '从服务中选择子类 %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => '请选择子类',
	'Portal:DescriptionOfTheRequest' => '请输入描述:',
	'Portal:TitleRequestDetailsFor_Request' => '需求详情 %1$s:',
	'Portal:NoOpenRequest' => '该类别中没有打开的需求.',
	'Portal:NoClosedRequest' => '该分类中没有需求',
	'Portal:Button:ReopenTicket' => '重新打开这个工单',
	'Portal:Button:CloseTicket' => '关闭这个工单',
	'Portal:Button:UpdateRequest' => '更新需求',
	'Portal:EnterYourCommentsOnTicket' => '请点评该工单的解决方案:',
	'Portal:ErrorNoContactForThisUser' => '错误: 当前用户没有与任何联系人关联. 请联系管理员.',
	'Portal:Attachments' => '附件',
	'Portal:AddAttachment' => ' 添加附件 ',
	'Portal:RemoveAttachment' => ' 移除附件 ',
	'Portal:Attachment_No_To_Ticket_Name' => '添加 #%1$d 到 %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => '请为 %1$s 选择一个模板',
	'Enum:Undefined' => '未定义',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s 天 %2$s 小时 %3$s 分 %4$s 秒',
	'UI:ModifyAllPageTitle' => '修改所有',
	'UI:Modify_N_ObjectsOf_Class' => '正在修改 %1$d 个 %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => '正在修改 %1$d 个 %2$s ,一共 %3$d 个',
	'UI:Menu:ModifyAll' => '修改...',
	'UI:Button:ModifyAll' => '全部修改',
	'UI:Button:PreviewModifications' => '修改预览 >>',
	'UI:ModifiedObject' => '对象已修改',
	'UI:BulkModifyStatus' => '操作',
	'UI:BulkModifyStatus+' => '操作状态',
	'UI:BulkModifyErrors' => 'Errors (if any)',
	'UI:BulkModifyErrors+' => '阻止修改时报错',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => '错误',
	'UI:BulkModifyStatusModified' => '已修改',
	'UI:BulkModifyStatusSkipped' => '跳过',
	'UI:BulkModify_Count_DistinctValues' => '%1$d 不同的值:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => '尝试修改只读字段: %1$s',
	'UI:FailedToApplyStimuli' => '操作失败.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: 正在修改 %2$d 个 %3$s',
	'UI:CaseLogTypeYourTextHere' => '请在这里输入内容...',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => '初始值:',
	'UI:AttemptingToSetASlaveAttribute_Name' => '字段 %1$s 不可写，因为它由数据同步管理. 值未设置.',
	'UI:ActionNotAllowed' => '您无权操作这些对象.',
	'UI:BulkAction:NoObjectSelected' => '请至少选择一个对象进行操作',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.',
	'UI:Pagination:HeaderSelection' => '一共: %1$s 个对象 ( 已选择 %2$s 个).',
	'UI:Pagination:HeaderNoSelection' => '一共: %1$s 个对象.',
	'UI:Pagination:PageSize' => '每页 %1$s 个对象',
	'UI:Pagination:PagesLabel' => '页:',
	'UI:Pagination:All' => '全部',
	'UI:HierarchyOf_Class' => '%1$s 层级',
	'UI:Preferences' => '首选项...',
	'UI:ArchiveModeOn' => '激活归档模式',
	'UI:ArchiveModeOff' => '关闭归档模式',
	'UI:ArchiveMode:Banner' => '归档模式',
	'UI:ArchiveMode:Banner+' => '已归档的对象可见但不允许修改',
	'UI:FavoriteOrganizations' => '快速访问',
	'UI:FavoriteOrganizations+' => '进入组织下的列表，可实现通过下拉菜单快速访问.请注意，这并不是一个安全设置, 其他组织的对象依然可以通过选择 "所有组织" 下拉列表看到.',
	'UI:FavoriteLanguage' => '用户界面',
	'UI:Favorites:SelectYourLanguage' => '选择语言',
	'UI:FavoriteOtherSettings' => '其他设置',
	'UI:Favorites:Default_X_ItemsPerPage' => '默认列表: 每页 %1$s 个项目',
	'UI:Favorites:ShowObsoleteData' => '显示废弃的数据',
	'UI:Favorites:ShowObsoleteData+' => '在搜索结果中显示已废弃的数据',
	'UI:NavigateAwayConfirmationMessage' => '所有修改都将丢失.',
	'UI:CancelConfirmationMessage' => '您将丢失所有修改. 是否继续?',
	'UI:AutoApplyConfirmationMessage' => '有些修改尚未生效. Do you want itop to take them into account?',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ',
	'UI:OrderByHint_Values' => '排列顺序: %1$s',
	'UI:Menu:AddToDashboard' => '添加到仪表盘...',
	'UI:Button:Refresh' => '刷新',
	'UI:Button:GoPrint' => '打印...',
	'UI:ExplainPrintable' => '点击 %1$s 图标可隐藏打印内容.<br/>在打印之前可使用浏览器的 "打印预览" 功能.<br/>注: 这个页首和其他控制面板不会被打印.',
	'UI:PrintResolution:FullSize' => '全尺寸',
	'UI:PrintResolution:A4Portrait' => 'A4 纵向',
	'UI:PrintResolution:A4Landscape' => 'A4 横向',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => '标准',
	'UI:Toggle:CustomDashboard' => '自定义',

	'UI:ConfigureThisList' => '配置这个列表...',
	'UI:ListConfigurationTitle' => '列表配置',
	'UI:ColumnsAndSortOrder' => '列和排序顺序:',
	'UI:UseDefaultSettings' => '使用默认配置',
	'UI:UseSpecificSettings' => '使用下面的配置:',
	'UI:Display_X_ItemsPerPage' => '每页显示 %1$s 个项目',
	'UI:UseSavetheSettings' => '保存设置',
	'UI:OnlyForThisList' => '仅这个列表',
	'UI:ForAllLists' => '默认所有列表',
	'UI:ExtKey_AsLink' => '%1$s (超链接)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (昵称)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => '上移',
	'UI:Button:MoveDown' => '下移',

	'UI:OQL:UnknownClassAndFix' => '未知类别 "%1$s". 您可以试试 "%2$s" .',
	'UI:OQL:UnknownClassNoFix' => '未知类别 "%1$s"',

	'UI:Dashboard:Edit' => '编辑这个页面...',
	'UI:Dashboard:Revert' => '还原到初始版本...',
	'UI:Dashboard:RevertConfirm' => '每个地方都会恢复到初始版本. 请确认您要这样做.',
	'UI:ExportDashBoard' => '导出到文件',
	'UI:ImportDashBoard' => '从文件导入...',
	'UI:ImportDashboardTitle' => '从文件导入',
	'UI:ImportDashboardText' => '选择要导入的仪表盘文件:',


	'UI:DashletCreation:Title' => '创建新组件',
	'UI:DashletCreation:Dashboard' => '仪表盘',
	'UI:DashletCreation:DashletType' => '组件类型',
	'UI:DashletCreation:EditNow' => '编辑仪表盘',

	'UI:DashboardEdit:Title' => '仪表盘编辑器',
	'UI:DashboardEdit:DashboardTitle' => '标题',
	'UI:DashboardEdit:AutoReload' => '自动刷新',
	'UI:DashboardEdit:AutoReloadSec' => '自动刷新间隔(秒)',
	'UI:DashboardEdit:AutoReloadSec+' => '最小值是 %1$d 秒',

	'UI:DashboardEdit:Layout' => '布局',
	'UI:DashboardEdit:Properties' => '仪表盘属性',
	'UI:DashboardEdit:Dashlets' => '可用的组件',
	'UI:DashboardEdit:DashletProperties' => '组件属性',

	'UI:Form:Property' => '属性',
	'UI:Form:Value' => '值',

	'UI:DashletUnknown:Label' => '未知',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => '配置 (显示为纯 XML)',

	'UI:DashletProxy:Label' => '代理',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => '第三方组件无法预览(class "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => '配置 (显示为纯 XML)',

	'UI:DashletPlainText:Label' => '文本',
	'UI:DashletPlainText:Description' => '纯文本(无格式)',
	'UI:DashletPlainText:Prop-Text' => '内容',
	'UI:DashletPlainText:Prop-Text:Default' => '请在这里输入内容...',

	'UI:DashletObjectList:Label' => '对象列表',
	'UI:DashletObjectList:Description' => 'Object list dashlet',
	'UI:DashletObjectList:Prop-Title' => '标题',
	'UI:DashletObjectList:Prop-Query' => '查询',
	'UI:DashletObjectList:Prop-Menu' => '菜单',

	'UI:DashletGroupBy:Prop-Title' => '标题',
	'UI:DashletGroupBy:Prop-Query' => '查询',
	'UI:DashletGroupBy:Prop-Style' => '样式',
	'UI:DashletGroupBy:Prop-GroupBy' => '分组...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Please select the field on which the objects will be grouped together',

	'UI:DashletGroupByPie:Label' => '饼图',
	'UI:DashletGroupByPie:Description' => '饼图',
	'UI:DashletGroupByBars:Label' => '柱状图',
	'UI:DashletGroupByBars:Description' => '柱状图',
	'UI:DashletGroupByTable:Label' => '分组 (表)',
	'UI:DashletGroupByTable:Description' => '列表 (Grouped by a field)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => '聚合函数',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => '函数属性',
	'UI:DashletGroupBy:Prop-OrderDirection' => '方向',
	'UI:DashletGroupBy:Prop-OrderField' => '排序',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit',

	'UI:DashletGroupBy:Order:asc' => '升序',
	'UI:DashletGroupBy:Order:desc' => '降序',

	'UI:GroupBy:count' => '个数',
	'UI:GroupBy:count+' => '元件的个数',
	'UI:GroupBy:sum' => '总数',
	'UI:GroupBy:sum+' => 'Sum of %1$s',
	'UI:GroupBy:avg' => '平均',
	'UI:GroupBy:avg+' => 'Average of %1$s',
	'UI:GroupBy:min' => '最小',
	'UI:GroupBy:min+' => 'Minimum of %1$s',
	'UI:GroupBy:max' => '最大',
	'UI:GroupBy:max+' => 'Maximum of %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header',
	'UI:DashletHeaderStatic:Description' => '显示水平分隔符',
	'UI:DashletHeaderStatic:Prop-Title' => '标题',
	'UI:DashletHeaderStatic:Prop-Title:Default' => '联系人',
	'UI:DashletHeaderStatic:Prop-Icon' => '图标',

	'UI:DashletHeaderDynamic:Label' => 'Header with statistics',
	'UI:DashletHeaderDynamic:Description' => 'Header with stats (grouped by...)',
	'UI:DashletHeaderDynamic:Prop-Title' => '标题',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => '联系人',
	'UI:DashletHeaderDynamic:Prop-Icon' => '图标',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => '副标题',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => '联系人',
	'UI:DashletHeaderDynamic:Prop-Query' => '查询',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Group by',
	'UI:DashletHeaderDynamic:Prop-Values' => '值',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Object Icon with new/search',
	'UI:DashletBadge:Prop-Class' => 'Class',

	'DayOfWeek-Sunday' => '周日',
	'DayOfWeek-Monday' => '周一',
	'DayOfWeek-Tuesday' => '周二',
	'DayOfWeek-Wednesday' => '周三',
	'DayOfWeek-Thursday' => '周四',
	'DayOfWeek-Friday' => '周五',
	'DayOfWeek-Saturday' => '周六',
	'Month-01' => '一月',
	'Month-02' => '二月',
	'Month-03' => '三月',
	'Month-04' => '四月',
	'Month-05' => '五月',
	'Month-06' => '六月',
	'Month-07' => '七月',
	'Month-08' => '八月',
	'Month-09' => '九月',
	'Month-10' => '十月',
	'Month-11' => '十一月',
	'Month-12' => '十二月',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => '日',
	'DayOfWeek-Monday-Min' => '一',
	'DayOfWeek-Tuesday-Min' => '二',
	'DayOfWeek-Wednesday-Min' => '三',
	'DayOfWeek-Thursday-Min' => '四',
	'DayOfWeek-Friday-Min' => '五',
	'DayOfWeek-Saturday-Min' => '六',
	'Month-01-Short' => '1月',
	'Month-02-Short' => '2月',
	'Month-03-Short' => '3月',
	'Month-04-Short' => '4月',
	'Month-05-Short' => '5月',
	'Month-06-Short' => '6月',
	'Month-07-Short' => '7月',
	'Month-08-Short' => '8月',
	'Month-09-Short' => '9月',
	'Month-10-Short' => '10月',
	'Month-11-Short' => '11月',
	'Month-12-Short' => '12月',
	'Calendar-FirstDayOfWeek' => '0', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => '创建快捷方式...',
	'UI:ShortcutRenameDlg:Title' => '重命名快捷方式',
	'UI:ShortcutListDlg:Title' => '为该列表创建快捷方式',
	'UI:ShortcutDelete:Confirm' => '请确认是否删除这个(些)快捷方式.',
	'Menu:MyShortcuts' => '我的快捷方式', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => '快捷方式',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => '名称',
	'Class:Shortcut/Attribute:name+' => '用于菜单和页面的标记',
	'Class:ShortcutOQL' => '搜索结果的快捷方式',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => '查询',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for',
	'Class:ShortcutOQL/Attribute:auto_reload' => '自动刷新',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => '禁用',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => '自定义频率',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => '自动刷新间隔(秒)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => '最小值是 %1$d 秒',

	'UI:FillAllMandatoryFields' => '请填写所有的必填项.',
	'UI:ValueMustBeSet' => '必填',
	'UI:ValueMustBeChanged' => '必须修改这个值',
	'UI:ValueInvalidFormat' => '格式无效',

	'UI:CSVImportConfirmTitle' => '请确认本次操作',
	'UI:CSVImportConfirmMessage' => '请确认是否继续 ?',
	'UI:CSVImportError_items' => '错误: %1$d',
	'UI:CSVImportCreated_items' => '创建: %1$d',
	'UI:CSVImportModified_items' => '修改: %1$d',
	'UI:CSVImportUnchanged_items' => '保持不变: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => '日期和时间格式',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => '默认格式: %1$s (比如 %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => '自定义格式: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => '可用的值:<table>
<tr><td>Y</td><td>年 (4位数, 比如 2016)</td></tr>
<tr><td>y</td><td>年 (2位数, 比如 16 代表 2016)</td></tr>
<tr><td>m</td><td>月 (2位数, 比如 01..12)</td></tr>
<tr><td>n</td><td>月 (1位数或2位数, 比如 1..12)</td></tr>
<tr><td>d</td><td>日 (2位数, 比如 01..31)</td></tr>
<tr><td>j</td><td>日 (1位数或2位数, 比如 1..31)</td></tr>
<tr><td>H</td><td>时 (24小时, 2位数, 比如 00..23)</td></tr>
<tr><td>h</td><td>时 (12小时, 2位数, 比如 01..12)</td></tr>
<tr><td>G</td><td>时 (24小时, 1位数或2位数, 比如 0..23)</td></tr>
<tr><td>g</td><td>时 (12小时, 1位数或2位数, 比如 1..12)</td></tr>
<tr><td>a</td><td>时, am or pm (小写)</td></tr>
<tr><td>A</td><td>时, AM or PM (大写)</td></tr>
<tr><td>i</td><td>分 (2位数, 比如 00..59)</td></tr>
<tr><td>s</td><td>秒 (2位数, 比如 00..59)</td></tr>
</table>',

	'UI:Button:Remove' => '移除',
	'UI:AddAnExisting_Class' => '添加 %1$s...',
	'UI:SelectionOf_Class' => '选择 %1$s',

	'UI:AboutBox' => '关于iTop...',
	'UI:About:Title' => '关于iTop',
	'UI:About:DataModel' => '数据模型',
	'UI:About:Support' => '支持信息',
	'UI:About:Licenses' => '许可证',
	'UI:About:InstallationOptions' => '已安装的模块',
	'UI:About:ManualExtensionSource' => '扩展',
	'UI:About:Extension_Version' => '版本: %1$s',
	'UI:About:RemoteExtensionSource' => '数据',

	'UI:DisconnectedDlgMessage' => '您已断开. 要继续使用，需要重新验证您的用户名和密码.',
	'UI:DisconnectedDlgTitle' => '警告!',
	'UI:LoginAgain' => '再次登录',
	'UI:StayOnThePage' => '保持在当前页面',

	'ExcelExporter:ExportMenu' => 'Excel 导出...',
	'ExcelExporter:ExportDialogTitle' => 'Excel 导出',
	'ExcelExporter:ExportButton' => '导出',
	'ExcelExporter:DownloadButton' => '下载 %1$s',
	'ExcelExporter:RetrievingData' => '正在检索数据...',
	'ExcelExporter:BuildingExcelFile' => '正在创建Excel 文件...',
	'ExcelExporter:Done' => '完成.',
	'ExcelExport:AutoDownload' => '导出准备好之后自动开始下载',
	'ExcelExport:PreparingExport' => '正在准备导出...',
	'ExcelExport:Statistics' => '统计',
	'portal:legacy_portal' => '终端用户门户',
	'portal:backoffice' => 'iTop 后台用户界面',

	'UI:CurrentObjectIsLockedBy_User' => '对象被锁住,因为正在修改 %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => '该对象正在被 %1$s 修改. 您的修改无法提交因为它们会冲突.',
	'UI:CurrentObjectLockExpired' => '并发修改的锁定期已过.',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.',
	'UI:ConcurrentLockKilled' => '阻止并发修改当前对象的锁已被删除.',
	'UI:Menu:KillConcurrentLock' => '消除并发修改锁定!',

	'UI:Menu:ExportPDF' => '导出PDF...',
	'UI:Menu:PrintableVersion' => '打印',

	'UI:BrowseInlineImages' => '浏览图片...',
	'UI:UploadInlineImageLegend' => '上传新图片',
	'UI:SelectInlineImageToUpload' => '选择要上传的图片',
	'UI:AvailableInlineImagesLegend' => '可用的图片',
	'UI:NoInlineImage' => '服务器上没有图片. 使用上面的 "浏览" 按钮，从您的电脑上选择并上传到服务器.',

	'UI:ToggleFullScreen' => '切换 最大化 / 最小化',
	'UI:Button:ResetImage' => '恢复之前的图片',
	'UI:Button:RemoveImage' => '移除图片',
	'UI:UploadNotSupportedInThisMode' => '本模式下不支持修改文件或图片.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => '折叠 / 展开',
	'UI:Search:AutoSubmit:DisabledHint' => '该类别已禁用自动提交',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Based on your preferences, obsolete data are hidden~~',
	'UI:Search:NoAutoSubmit:ExplainText' => '在搜索框中添加规则，或者单击对象按钮查看对象.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => '添加条件',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => '最近使用',
	'UI:Search:AddCriteria:List:MostPopular:Title' => '最常用',
	'UI:Search:AddCriteria:List:Others:Title' => '其它',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => '还没有.',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: 任何',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s 为空',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s 不为空',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s 等于 %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s 包含 %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s 起始于 %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s 结尾是 %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s 匹配 %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s between [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: 任何',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s 从 %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s 到 %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: 任何',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s 从 %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s 到 %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s 和 %3$s others',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: 任何',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s~~',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s 已定义',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s 未被定义',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s 和 %3$s others',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: 任何',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s 已定义',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s 未被定义',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s 和 %3$s others',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: 任何',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => '为空',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => '非空',
	'UI:Search:Criteria:Operator:Default:Equals' => '等于',
	'UI:Search:Criteria:Operator:Default:Between' => '之间',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => '包含',
	'UI:Search:Criteria:Operator:String:StartsWith' => '起始为',
	'UI:Search:Criteria:Operator:String:EndsWith' => '结尾是',
	'UI:Search:Criteria:Operator:String:RegExp' => '正则表达式.',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => '等于',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => '大于',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => '大于 / 等于',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => '小于',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => '小于 / 等于',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => '不同',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => '过滤器...',
	'UI:Search:Value:Search:Placeholder' => '搜索...',
	'UI:Search:Value:Autocomplete:StartTyping' => '开始输入值.',
	'UI:Search:Value:Autocomplete:Wait' => '请稍后...',
	'UI:Search:Value:Autocomplete:NoResult' => '没有找到结果.',
	'UI:Search:Value:Toggler:CheckAllNone' => '全选 / 不选',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => '全选 / 不选',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => '从',
	'UI:Search:Criteria:Numeric:Until' => '到',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => '任何',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => '任何',
	'UI:Search:Criteria:DateTime:From' => '从',
	'UI:Search:Criteria:DateTime:FromTime' => '从',
	'UI:Search:Criteria:DateTime:Until' => '到',
	'UI:Search:Criteria:DateTime:UntilTime' => '到',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => '任何日期',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => '任何日期',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => '任何日期',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => '任何日期',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => '将会包含选中对象的子集.',

	'UI:Search:Criteria:Raw:Filtered' => '已过滤',
	'UI:Search:Criteria:Raw:FilteredOn' => '基于 %1$s 过滤',
));

//
// Expression to Natural language
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Expression:Operator:AND' => ' 与 ',
	'Expression:Operator:OR' => ' 或 ',
	'Expression:Operator:=' => ': ~~',

	'Expression:Unit:Short:DAY' => '日',
	'Expression:Unit:Short:WEEK' => '周',
	'Expression:Unit:Short:MONTH' => '月',
	'Expression:Unit:Short:YEAR' => '年',

	'Expression:Unit:Long:DAY' => '日',
	'Expression:Unit:Long:HOUR' => '小时',
	'Expression:Unit:Long:MINUTE' => '分钟',

	'Expression:Verb:NOW' => '现在',
	'Expression:Verb:ISNULL' => ': 未定义',
));

//
// iTop Newsroom menu
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'UI:Newsroom:NoNewMessage' => '没有新消息',
	'UI:Newsroom:MarkAllAsRead' => '标记所有消息为已读',
	'UI:Newsroom:ViewAllMessages' => '查看全部消息',
	'UI:Newsroom:Preferences' => '消息选项',
	'UI:Newsroom:ConfigurationLink' => '配置',
	'UI:Newsroom:ResetCache' => '重置缓存',
	'UI:Newsroom:DisplayMessagesFor_Provider' => '显示来自 %1$s 的消息',
	'UI:Newsroom:DisplayAtMost_X_Messages' => '在 %2$s 菜单中最多显示 %1$s 条消息.',
));

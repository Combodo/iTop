<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2021 Combodo SARL
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
// Dictionnary conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
//
// Class: Organization
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Organization' => '组织',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => '名称',
	'Class:Organization/Attribute:name+' => '常用名称',
	'Class:Organization/Attribute:code' => '编码',
	'Class:Organization/Attribute:code+' => '组织编码(Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => '状态',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => '启用',
	'Class:Organization/Attribute:status/Value:active+' => '启用',
	'Class:Organization/Attribute:status/Value:inactive' => '停用',
	'Class:Organization/Attribute:status/Value:inactive+' => '停用',
	'Class:Organization/Attribute:parent_id' => '父级',
	'Class:Organization/Attribute:parent_id+' => '父级组织',
	'Class:Organization/Attribute:parent_name' => '父级名称',
	'Class:Organization/Attribute:parent_name+' => '父级组织名称',
	'Class:Organization/Attribute:deliverymodel_id' => '交付模式',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => '交付模式名称',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => '上级组织',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '上级组织',
	'Class:Organization/Attribute:overview' => '概览',
	'Organization:Overview:FunctionalCIs' => '该组织的所有配置项',
	'Organization:Overview:FunctionalCIs:subtitle' => '按类型',
	'Organization:Overview:Users' => '该组织里所有的iTop 用户',
));

//
// Class: Location
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Location' => '地理位置',
	'Class:Location+' => '任何类型的地理位置: 区域, 国家, 城市, 位置, 建筑, 楼层, 房间, 机架,...',
	'Class:Location/Attribute:name' => '名称',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => '状态',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => '启用',
	'Class:Location/Attribute:status/Value:active+' => '启用',
	'Class:Location/Attribute:status/Value:inactive' => '停用',
	'Class:Location/Attribute:status/Value:inactive+' => '停用',
	'Class:Location/Attribute:org_id' => '拥有者组织',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => '拥有者组织名称',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => '地址',
	'Class:Location/Attribute:address+' => '门户地址',
	'Class:Location/Attribute:postal_code' => '邮编',
	'Class:Location/Attribute:postal_code+' => 'ZIP/邮政编码',
	'Class:Location/Attribute:city' => '城市',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => '国家',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => '设备',
	'Class:Location/Attribute:physicaldevice_list+' => '该位置的所有设备',
	'Class:Location/Attribute:person_list' => '联系人',
	'Class:Location/Attribute:person_list+' => '该位置的所有联系人',
));

//
// Class: Contact
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Contact' => '联系人',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => '名称',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => '状态',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => '启用',
	'Class:Contact/Attribute:status/Value:active+' => '启用',
	'Class:Contact/Attribute:status/Value:inactive' => '停用',
	'Class:Contact/Attribute:status/Value:inactive+' => '停用',
	'Class:Contact/Attribute:org_id' => '组织',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => '组织',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => '电话',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => '通知',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => '否',
	'Class:Contact/Attribute:notify/Value:no+' => '否',
	'Class:Contact/Attribute:notify/Value:yes' => '是',
	'Class:Contact/Attribute:notify/Value:yes+' => '是',
	'Class:Contact/Attribute:function' => '职责',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => '配置项',
	'Class:Contact/Attribute:cis_list+' => '该联系人关联的所有配置项',
	'Class:Contact/Attribute:finalclass' => '联系人子类别',
	'Class:Contact/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: Person
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Person' => '个人',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => '姓',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => '名',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => '员工编号',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => '移动电话',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => '地理位置',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => '名称',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => '经理',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => '名称',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => '团队',
	'Class:Person/Attribute:team_list+' => '这个人归属的所有团队',
	'Class:Person/Attribute:tickets_list' => '工单',
	'Class:Person/Attribute:tickets_list+' => '这个人发起的所有工单',
	'Class:Person/Attribute:manager_id_friendlyname' => '经理姓名',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => '头像',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => '同一组织内的员工号必须唯一',
	'Class:Person/UniquenessRule:employee_number' => '\'$this->org_name$\' 内已经有人占用了这个员工号',
	'Class:Person/UniquenessRule:name+' => '同一组织内的员工姓名必须唯一',
	'Class:Person/UniquenessRule:name' => '\'$this->org_name$\' 内已经有人叫这个名字',
));

//
// Class: Team
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Team' => '团队',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => '成员',
	'Class:Team/Attribute:persons_list+' => '该团队包含的所有成员',
	'Class:Team/Attribute:tickets_list' => '工单',
	'Class:Team/Attribute:tickets_list+' => '该团队的所有工单',
));

//
// Class: Document
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Document' => '文档',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => '名称',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => '组织',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => '组织名称',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => '文档类型',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => '文档类型名称',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => '版本',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => '描述',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => '状态',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => '草稿',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => '废弃',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => '已发布',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => '配置项',
	'Class:Document/Attribute:cis_list+' => '该文档关联的所有配置项',
	'Class:Document/Attribute:finalclass' => '文档子类别',
	'Class:Document/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: DocumentFile
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentFile' => '文档文件',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => '文件',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentNote' => '文档笔记',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => '文本',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentWeb' => '文档网页',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Typology' => '类型',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => '名称',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => '类型',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: DocumentType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentType' => '文档类型',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ContactType' => '联系人类型',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkPersonToTeam' => '链接 个体 / 团队',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => '团队',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => '团队名称',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => '个体',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => '姓名',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => '角色',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => '角色名称',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Menu:DataAdministration' => '数据管理',
	'Menu:DataAdministration+' => '数据管理',
	'Menu:Catalogs' => '类别',
	'Menu:Catalogs+' => '数据类型',
	'Menu:Audit' => '审计',
	'Menu:Audit+' => '审计',
	'Menu:CSVImport' => 'CSV 导入',
	'Menu:CSVImport+' => '批量创建或更新',
	'Menu:Organization' => '组织',
	'Menu:Organization+' => '所有组织',
	'Menu:ConfigManagement' => '配置管理',
	'Menu:ConfigManagement+' => '配置管理',
	'Menu:ConfigManagementCI' => '配置项',
	'Menu:ConfigManagementCI+' => '配置项',
	'Menu:ConfigManagementOverview' => '概览',
	'Menu:ConfigManagementOverview+' => '概览',
	'Menu:Contact' => '联系人',
	'Menu:Contact+' => '联系人',
	'Menu:Contact:Count' => '%1$d 个联系人',
	'Menu:Person' => '个体',
	'Menu:Person+' => '所有个体',
	'Menu:Team' => '团队',
	'Menu:Team+' => '所有团队',
	'Menu:Document' => '文档',
	'Menu:Document+' => '所有文档',
	'Menu:Location' => '地理位置',
	'Menu:Location+' => '所有位置',
	'Menu:NewContact' => '新建联系人',
	'Menu:NewContact+' => '新建联系人',
	'Menu:SearchContacts' => '搜索联系人',
	'Menu:SearchContacts+' => '搜索联系人',
	'Menu:ConfigManagement:Shortcuts' => '快捷方式',
	'Menu:ConfigManagement:AllContacts' => '所有联系人: %1$d',
	'Menu:Typology' => '类型配置',
	'Menu:Typology+' => '类型配置',
	'UI_WelcomeMenu_AllConfigItems' => '摘要',
	'Menu:ConfigManagement:Typology' => '类型配置',
));

// Add translation for Fieldsets

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Person:info' => '基本信息',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => '个人信息',
	'Person:notifiy' => '通知',
));

// Themes
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'theme:fullmoon' => 'Full moon 🌕~~',
	'theme:test-red' => 'Test instance (Red)~~',
));

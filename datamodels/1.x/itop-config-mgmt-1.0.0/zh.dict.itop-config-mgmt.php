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
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Relation:impacts/Description' => '被影响的元素',
	'Relation:impacts/VerbUp' => '影响...',
	'Relation:impacts/VerbDown' => '被影响的元素...',
	'Relation:depends on/Description' => '该元素依赖的元素...',
	'Relation:depends on/VerbUp' => '依赖于...',
	'Relation:depends on/VerbDown' => '影响...',
));


// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

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
	'Class:Organization/Attribute:status/Value:active' => '活动',
	'Class:Organization/Attribute:status/Value:active+' => '活动',
	'Class:Organization/Attribute:status/Value:inactive' => '非活动',
	'Class:Organization/Attribute:status/Value:inactive+' => '非活动',
	'Class:Organization/Attribute:parent_id' => '父级',
	'Class:Organization/Attribute:parent_id+' => '父级组织',
	'Class:Organization/Attribute:parent_name' => '父级名称',
	'Class:Organization/Attribute:parent_name+' => '父级组织名称',
));


//
// Class: Location
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Location' => '位置',
	'Class:Location+' => '任何类型的地理位置: 区域, 国家, 城市, 位置, 建筑, 楼层, 房间, 机架,...',
	'Class:Location/Attribute:name' => '名称',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => '状态',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => '活动',
	'Class:Location/Attribute:status/Value:active+' => '活动',
	'Class:Location/Attribute:status/Value:inactive' => '非活动',
	'Class:Location/Attribute:status/Value:inactive+' => '非活动',
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
	'Class:Location/Attribute:parent_id' => '父级位置',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => '父级名称',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => '联系人',
	'Class:Location/Attribute:contact_list+' => '该场点的联系人',
	'Class:Location/Attribute:infra_list' => '基础架构',
	'Class:Location/Attribute:infra_list+' => '该场点的CI',
));
//
// Class: Group
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Group' => '组',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => '名称',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => '状态',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => '实施',
	'Class:Group/Attribute:status/Value:implementation+' => '实施',
	'Class:Group/Attribute:status/Value:obsolete' => '荒废',
	'Class:Group/Attribute:status/Value:obsolete+' => '荒废',
	'Class:Group/Attribute:status/Value:production' => '生产',
	'Class:Group/Attribute:status/Value:production+' => '生产',
	'Class:Group/Attribute:org_id' => '组织',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => '名称',
	'Class:Group/Attribute:owner_name+' => '常用名称',
	'Class:Group/Attribute:description' => '描述',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => '种类',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => '父级组别',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => '名称',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => '连接的CI',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkGroupToCI' => '组 / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => '组',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => '名称',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => '名称',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CI状态',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => '原因',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
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
	'Class:Contact/Attribute:status/Value:active' => '活动',
	'Class:Contact/Attribute:status/Value:active+' => '活动',
	'Class:Contact/Attribute:status/Value:inactive' => '非活动',
	'Class:Contact/Attribute:status/Value:inactive+' => '非活动',
	'Class:Contact/Attribute:org_id' => '组织',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => '组织',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => '电话',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => '位置',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => '位置',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CI',
	'Class:Contact/Attribute:ci_list+' => '该联系人相关CI',
	'Class:Contact/Attribute:contract_list' => '合同',
	'Class:Contact/Attribute:contract_list+' => '该联系人相关合同',
	'Class:Contact/Attribute:service_list' => '服务',
	'Class:Contact/Attribute:service_list+' => '该联系人相关服务',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => '该联系人相关',
	'Class:Contact/Attribute:team_list' => '团队',
	'Class:Contact/Attribute:team_list+' => '该联系人所属团队',
	'Class:Contact/Attribute:finalclass' => '类别',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Person' => '人员',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => '姓',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => '员工ID',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Team' => '团队',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => '成员',
	'Class:Team/Attribute:member_list+' => '团队所辖成员',
));

//
// Class: lnkTeamToContact
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkTeamToContact' => '团队成员',
	'Class:lnkTeamToContact+' => '团队中的成员',
	'Class:lnkTeamToContact/Attribute:team_id' => '团队',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => '成员',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => '位置',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => '电话',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => '角色',
	'Class:lnkTeamToContact/Attribute:role+' => '',
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
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => '组织名称',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => '描述',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => '类别',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => '合同',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => '网络图',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => '展现',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => '培训',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => '白皮书',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => '工作指南',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:type/Value:design' => '设计',
	'Class:Document/Attribute:type/Value:design+' => '',
	'Class:Document/Attribute:status' => '状态',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => '草案',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => '荒废',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => '已发布',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CI',
	'Class:Document/Attribute:ci_list+' => '参照该文档的CI',
	'Class:Document/Attribute:contract_list' => '合同',
	'Class:Document/Attribute:contract_list+' => '参照该文档的合同',
	'Class:Document/Attribute:service_list' => '服务',
	'Class:Document/Attribute:service_list+' => '参照该文档的服务',
	'Class:Document/Attribute:ticket_list' => '单据',
	'Class:Document/Attribute:ticket_list+' => '引用该文档的单据',
	'Class:Document:PreviewTab' => '预览',
));

//
// Class: WebDoc
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WebDoc' => 'Web 文档',
	'Class:WebDoc+' => '其他web服务器上可获得的文档',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Note' => '备注',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => '文本',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FileDoc' => '文档',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => '内容',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Licence' => 'Licence',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => '提供商',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => '所有者',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => '名称',
	'Class:Licence/Attribute:org_name+' => '常用名称',
	'Class:Licence/Attribute:product' => '产品',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => '名称',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => '启始日期',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => '终止日期',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Key',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => '范围',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => '使用限制',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => '用途',
	'Class:Licence/Attribute:usage_list+' => '使用该License的应用程序实例',
));


//
// Class: Subnet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Subnet' => '子网',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Name',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => '拥有者组织',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => '描述',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Patch' => '补丁',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => '名称',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => '描述',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => '应用程序范围',
	'Class:Patch/Attribute:target_sw+' => '目标软件 (OS 或应用程序)',
	'Class:Patch/Attribute:version' => '版本',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => '类别',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => '应用程序',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => '安全',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => '服务包',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => '设备',
	'Class:Patch/Attribute:ci_list+' => '安装该补丁的设备',
));

//
// Class: Software
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Software' => '软件',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => '名称',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => '描述',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => '安装',
	'Class:Software/Attribute:instance_list+' => '该软件的实例',
	'Class:Software/Attribute:finalclass' => '类别',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Application' => '应用程序',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => '名称',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => '描述',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => '安装',
	'Class:Application/Attribute:instance_list+' => '应用程序实例',
));

//
// Class: DBServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DBServer' => '数据库',
	'Class:DBServer+' => '数据库服务器SW',
	'Class:DBServer/Attribute:instance_list' => '安装',
	'Class:DBServer/Attribute:instance_list+' => '数据库服务器实例',
));

//
// Class: lnkPatchToCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkPatchToCI' => '补丁使用范围',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => '补丁',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => '补丁',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CI状态',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FunctionalCI' => '功能 CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => '名称',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => '状态',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => '实施',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => '废弃',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => '生产',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => '拥有者组织',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => '拥有者组织',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => '业务关键性',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => '高',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => '低',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => '联系人',
	'Class:FunctionalCI/Attribute:contact_list+' => '该 CI 的联系人',
	'Class:FunctionalCI/Attribute:document_list' => '文档',
	'Class:FunctionalCI/Attribute:document_list+' => '该 CI 的文档',
	'Class:FunctionalCI/Attribute:solution_list' => '应用方案',
	'Class:FunctionalCI/Attribute:solution_list+' => '使用该 CI 的应用方案',
	'Class:FunctionalCI/Attribute:contract_list' => '合同',
	'Class:FunctionalCI/Attribute:contract_list+' => '支持该 CI 合同',
	'Class:FunctionalCI/Attribute:ticket_list' => '单据',
	'Class:FunctionalCI/Attribute:ticket_list+' => '与该 CI 相关的单据',
	'Class:FunctionalCI/Attribute:finalclass' => '类别',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwareInstance' => '软件实例',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => '设备',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => '设备',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Licence',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Licence',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => '软件',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => '版本',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => '描述',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ApplicationInstance' => '应用实例',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => '软件',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => '名称',
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DBServerInstance' => 'DB Server 实例',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => '软件',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => '名称',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => '数据库',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => '数据库源',
));


//
// Class: DatabaseInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DatabaseInstance' => 'Database 实例',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => '数据库服务器',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => '数据库版本',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => '描述',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ApplicationSolution' => '应用方案',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => '描述',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CI',
	'Class:ApplicationSolution/Attribute:ci_list+' => '构成该方案的 CI',
	'Class:ApplicationSolution/Attribute:process_list' => '业务流程',
	'Class:ApplicationSolution/Attribute:process_list+' => '依赖于该方案的业务流程',
));

//
// Class: BusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:BusinessProcess' => '业务流程',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => '描述',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => '应用方案',
	'Class:BusinessProcess/Attribute:used_solution_list+' => '业务流程所依赖的应用方案',
));

//
// Class: ConnectableCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ConnectableCI' => '可连接的 CI',
	'Class:ConnectableCI+' => '物理 CI',
	'Class:ConnectableCI/Attribute:brand' => '品牌',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => '型号',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => '序列号',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => '资产参考资料',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkInterface' => '网络接口',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => '设备',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => '设备',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => '逻辑类别',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => '备份',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => '逻辑的',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => '端口',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primary',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secondary',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => '物理类别',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'IP 地址',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'IP 掩码',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'MAC 地址',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => '速率',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => '未知',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => '连接到',
	'Class:NetworkInterface/Attribute:connected_if+' => '连接的接口',
	'Class:NetworkInterface/Attribute:connected_name' => '连接到',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => '连接的设备',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => '设备',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => '连接类别',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Device' => '设备',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => '网络接口',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => '内存',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => '硬盘',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OS 族',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OS 版本',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => '应用程序',
	'Class:PC/Attribute:application_list+' => '安装在该 PC 上的应用程序',
	'Class:PC/Attribute:patch_list' => '补丁',
	'Class:PC/Attribute:patch_list+' => '安装在该 PC 上的补丁',
));

//
// Class: MobileCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:MobileCI' => '移动 CI',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:MobilePhone' => '移动电话',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => '电话号码',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => '硬件 PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:InfrastructureCI' => '基础架构 CI',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => '描述',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => '位置',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => '位置',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => '位置明细',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => '管理 IP',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => '缺省网关',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkDevice' => '网络设备',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => '类别',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN 加速器',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => '防火墙',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => '集线器',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => '负载均衡',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => '路由器',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => '交换机',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'IOS 版本',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => '内存',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP 读',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP 写',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Server' => '服务器',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => '内存',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => '硬盘',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'OS族',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'OS版本 ',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => '应用程序',
	'Class:Server/Attribute:application_list+' => '服务器上安装的应用程序',
	'Class:Server/Attribute:patch_list' => '补丁',
	'Class:Server/Attribute:patch_list+' => '服务器上安装的补丁',
));

//
// Class: Printer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Printer' => '打印机',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => '类别',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => '打印机',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Technology',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => '喷墨',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => '激光',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => '绘图',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkCIToDoc' => 'Doc/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CI 状态',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => '文档',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => '文档',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => '文档类别',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => '文档状态',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkCIToContact' => 'CI/联系人',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CI 状态',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => '联系人',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => '联系人',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => '联系人 Email',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => '角色',
	'Class:lnkCIToContact/Attribute:role+' => '和该 CI 相关的联系人的角色',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSolutionToCI' => 'CI/方案',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => '应用方案',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => '应用方案',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CI 状态',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => '用途',
	'Class:lnkSolutionToCI/Attribute:utility+' => '在方案中该 CI 的用途',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkProcessToSolution' => '业务流程/方案',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => '应用方案',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => '应用方案',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => '流程',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => '流程',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => '原因',
	'Class:lnkProcessToSolution/Attribute:reason+' => '联系流程和方案的更多信息',
));



//
// Class extensions
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
'Class:Subnet/Tab:IPUsage' => 'IP 使用情况',
'Class:Subnet/Tab:IPUsage-explain' => '接口拥有一个下述范围内的IP: <em>%1$s</em> 到 <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => '未用的 IP',
'Class:Subnet/Tab:FreeIPs-count' => '未用的 IP: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => '这里有10个抽取的未用的 IP 地址',
));

//
// Application Menu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
'Menu:Catalogs' => '目录',
'Menu:Catalogs+' => '数据类别',
'Menu:Audit' => '审计',
'Menu:Audit+' => '审计',
'Menu:Organization' => '组织',
'Menu:Organization+' => '所有组织',
'Menu:Application' => '应用程序',
'Menu:Application+' => '所有应用程序',
'Menu:DBServer' => '数据库服务器',
'Menu:DBServer+' => '数据库服务器',
'Menu:Audit' => '审计',
'Menu:ConfigManagement' => '配置管理',
'Menu:ConfigManagement+' => '配置管理',
'Menu:ConfigManagementOverview' => '总览',
'Menu:ConfigManagementOverview+' => '总览',
'Menu:Contact' => '联系人',
'Menu:Contact+' => '联系人',
'Menu:Person' => '人员',
'Menu:Person+' => '所有人员',
'Menu:Team' => '团队',
'Menu:Team+' => '所有团队',
'Menu:Document' => '文档',
'Menu:Document+' => '所有文档',
'Menu:Location' => '位置',
'Menu:Location+' => '所有位置',
'Menu:ConfigManagementCI' => '配置项',
'Menu:ConfigManagementCI+' => '配置项',
'Menu:BusinessProcess' => '业务过程',
'Menu:BusinessProcess+' => '所有业务过程',
'Menu:ApplicationSolution' => '应用方案',
'Menu:ApplicationSolution+' => '所有应用方案',
'Menu:ConfigManagementSoftware' => '应用管理',
'Menu:Licence' => 'Licences',
'Menu:Licence+' => '所有Licences',
'Menu:Patch' => '补丁',
'Menu:Patch+' => '所有补丁',
'Menu:ApplicationInstance' => '已安装的软件',
'Menu:ApplicationInstance+' => '应用程序和数据库服务器',
'Menu:ConfigManagementHardware' => '基础架构管理',
'Menu:Subnet' => '子网',
'Menu:Subnet+' => '所有子网',
'Menu:NetworkDevice' => '网络设备',
'Menu:NetworkDevice+' => '所有网络设备',
'Menu:Server' => '服务器',
'Menu:Server+' => '所有服务器',
'Menu:Printer' => '打印机',
'Menu:Printer+' => '所有打印机',
'Menu:MobilePhone' => '手机',
'Menu:MobilePhone+' => '所有手机',
'Menu:PC' => '个人电脑',
'Menu:PC+' => '所有个人电脑',
'Menu:NewContact' => '新联系人',
'Menu:NewContact+' => '新联系人',
'Menu:SearchContacts' => '查找联系人',
'Menu:SearchContacts+' => '查找联系人',
'Menu:NewCI' => '新CI',
'Menu:NewCI+' => '新CI',
'Menu:SearchCIs' => '查找CI',
'Menu:SearchCIs+' => '查找CI',
'Menu:ConfigManagement:Devices' => '设备',
'Menu:ConfigManagement:AllDevices' => '设备数量: %1$d',
'Menu:ConfigManagement:SWAndApps' => '软件和应用程序',
'Menu:ConfigManagement:Misc' => '杂项',
'Menu:Group' => 'CI族',
'Menu:Group+' => 'CI族',
'Menu:ConfigManagement:Shortcuts' => '快捷方式',
'Menu:ConfigManagement:AllContacts' => '所有联系人: %1$d',

));
?>

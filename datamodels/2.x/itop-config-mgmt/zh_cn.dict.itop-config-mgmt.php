<?php
/**
 * Localized data
 *
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
// Relations
//////////////////////////////////////////////////////////////////////
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Relation:impacts/Description' => '被影响的元素',
	'Relation:impacts/DownStream' => '影响...',
	'Relation:impacts/DownStream+' => '被影响的元素',
	'Relation:impacts/UpStream' => '依赖于...',
	'Relation:impacts/UpStream+' => '该元素依赖的元素...',
	// Legacy entries
	'Relation:depends on/Description' => '该元素依赖的元素...',
	'Relation:depends on/DownStream' => '依赖于...',
	'Relation:depends on/UpStream' => '影响...',
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
// Class:<class_name>/UniquenessRule:<rule_code>
// Class:<class_name>/UniquenessRule:<rule_code>+

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
	'Class:Document/Attribute:contracts_list' => '合同',
	'Class:Document/Attribute:contracts_list+' => '该文档关联的所有合同',
	'Class:Document/Attribute:services_list' => '服务',
	'Class:Document/Attribute:services_list+' => '该文档关联的所有服务',
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
// Class: FunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FunctionalCI' => '功能配置项',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => '名称',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => '描述',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => '组织',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => '组织名称',
	'Class:FunctionalCI/Attribute:organization_name+' => '通用名',
	'Class:FunctionalCI/Attribute:business_criticity' => '业务重要性',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '中',
	'Class:FunctionalCI/Attribute:move2production' => '投产日期',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => '联系人',
	'Class:FunctionalCI/Attribute:contacts_list+' => '该配置项的所有联系人',
	'Class:FunctionalCI/Attribute:documents_list' => '文档',
	'Class:FunctionalCI/Attribute:documents_list+' => '该配置项关联的所有文档',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => '应用方案',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '该配置项依赖的所有应用方案',
	'Class:FunctionalCI/Attribute:providercontracts_list' => '供应商合同',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '该配置项的所有供应商合同',
	'Class:FunctionalCI/Attribute:services_list' => '服务',
	'Class:FunctionalCI/Attribute:services_list+' => '该配置项影响的所有服务',
	'Class:FunctionalCI/Attribute:softwares_list' => '软件',
	'Class:FunctionalCI/Attribute:softwares_list+' => '该配置项上已安装的所有软件',
	'Class:FunctionalCI/Attribute:tickets_list' => '工单',
	'Class:FunctionalCI/Attribute:tickets_list+' => '该配置项包含的所有工单',
	'Class:FunctionalCI/Attribute:finalclass' => '二级配置项',
	'Class:FunctionalCI/Attribute:finalclass+' => 'Name of the final class',
	'Class:FunctionalCI/Tab:OpenedTickets' => '活跃的工单',
));

//
// Class: PhysicalDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PhysicalDevice' => '物理设备',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => '序列号',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => '地理位置',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => '名称',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => '状态',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => '上线',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '上线',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:production' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => '闲置',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '闲置',
	'Class:PhysicalDevice/Attribute:brand_id' => '品牌',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => '品牌名称',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => '型号',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => '型号名称',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => '资产编号',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => '采购日期',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => '过保日期',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Rack' => '机柜',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => '机柜高度',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => '设备',
	'Class:Rack/Attribute:device_list+' => '该机柜托管的所有物理设备',
	'Class:Rack/Attribute:enclosure_list' => '机位',
	'Class:Rack/Attribute:enclosure_list+' => '该机柜上的所有机位',
));

//
// Class: TelephonyCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TelephonyCI' => 'Telephony CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => '电话号码',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Phone' => '电话',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:MobilePhone' => '移动电话',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => '硬件PIN 码',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:IPPhone' => 'IP 电话',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Tablet' => '平板',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ConnectableCI' => '可连接的配置项',
	'Class:ConnectableCI+' => '物理配置项',
	'Class:ConnectableCI/Attribute:networkdevice_list' => '网络设备',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '所有连接到这台设备的网络设备',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => '网卡',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '所有物理网卡',
));

//
// Class: DatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DatacenterDevice' => '数据中心设备',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => '机柜',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => '机柜名称',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => '机位',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => '机位名称',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => '高度',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => '管理IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => '电源A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA source name',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => '电源B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB source name',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => '光纤端口',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '该设备的所有光纤端口',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => '所有连接到这台设备的SAN 交换机',
	'Class:DatacenterDevice/Attribute:redundancy' => '冗余',
	'Class:DatacenterDevice/Attribute:redundancy/count' => '该设备运行正常至少需要一路电源 (A 或 B)',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => '所有电源正常,该设备才正常',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => '至少 %1$s %% 路电源正常,设备才正常',
));

//
// Class: NetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkDevice' => '网络设备',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => '网络设备类型',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => '网络设备类型名称',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => '设备',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '连接到该网络设备的所有设备',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS 版本',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS 版本名称',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => '内存',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Server' => '服务器',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => '操作系统家族',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => '操作系统家族名称',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => '操作系统版本',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => '操作系统版本名称',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => '操作系统许可证',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => '操作系统许可证名称',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => '内存',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:Server/Attribute:logicalvolumes_list+' => '连接到该服务器的所有逻辑卷',
));

//
// Class: StorageSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:StorageSystem' => '存储系统',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => '逻辑卷',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '该存储系统包含的所有逻辑卷',
));

//
// Class: SANSwitch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SANSwitch' => 'SAN 交换机',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => '设备',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '连接到该SAN 交换机的所有设备',
));

//
// Class: TapeLibrary
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TapeLibrary' => '磁带库',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => '磁带',
	'Class:TapeLibrary/Attribute:tapes_list+' => '该磁带库里的所有磁带',
));

//
// Class: NAS
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => '文件系统',
	'Class:NAS/Attribute:nasfilesystem_list+' => '该NAS 里的所有文件系统',
));

//
// Class: PC
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => '操作系统家族',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => '操作系统家族名称',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => '操作系统版本',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => '操作系统版本名称',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => '内存',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => '类型',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => '桌面型',
	'Class:PC/Attribute:type/Value:desktop+' => '桌面型',
	'Class:PC/Attribute:type/Value:laptop' => '笔记本',
	'Class:PC/Attribute:type/Value:laptop+' => '笔记本',
));

//
// Class: Printer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Printer' => '打印机',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PowerConnection' => '电源连接',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PowerSource' => '电源',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU',
	'Class:PowerSource/Attribute:pdus_list+' => '使用该电源的所有PDU',
));

//
// Class: PDU
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => '机柜',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => '机柜名称',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => '上级电源',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => '上级电源名称',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Peripheral' => '配件',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Enclosure' => '机位',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => '机柜',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => '机柜名称',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => '高度',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => '设备',
	'Class:Enclosure/Attribute:device_list+' => '该机位的所有设备',
));

//
// Class: ApplicationSolution
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ApplicationSolution' => '应用方案',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => '配置项',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '该应用方案包含的所有配置项',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => '业务流程',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '所有依赖该应用方案的业务流程',
	'Class:ApplicationSolution/Attribute:status' => '状态',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => '停用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '停用',
	'Class:ApplicationSolution/Attribute:redundancy' => '影响分析: 冗余配置',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up',
));

//
// Class: BusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:BusinessProcess' => '业务流程',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => '应用方案',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '所有影响该业务流程的应用方案',
	'Class:BusinessProcess/Attribute:status' => '状态',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => '启用',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '启用',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => '停用',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '停用',
));

//
// Class: SoftwareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwareInstance' => '软件实例',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => '系统',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => '系统名称',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => '软件',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => '软件名称',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => '软件许可证',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => '许可证名称',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => '路径',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => '状态',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => '启用',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => '启用',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => '停用',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '停用',
));

//
// Class: Middleware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Middleware' => '中间件',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => '中间件实例',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '该中间件的所有实例',
));

//
// Class: DBServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DBServer' => '数据库服务器',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => '数据库',
	'Class:DBServer/Attribute:dbschema_list+' => '该数据库服务器上的所有数据库架构',
));

//
// Class: WebServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WebServer' => 'Web 服务器',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Web 应用',
	'Class:WebServer/Attribute:webapp_list+' => '该web 服务器上的所有web 应用',
));

//
// Class: PCSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PCSoftware' => 'PC 软件',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OtherSoftware' => '其它软件',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:MiddlewareInstance' => '中间件实例',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => '中间件',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => '名称',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DatabaseSchema' => '数据库',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => '数据库服务器',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => '名称',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WebApplication' => 'Web 应用',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Web 服务器',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => '名称',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualDevice' => '虚拟设备',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => '状态',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => '上线',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '上线',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:production' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:stock' => '闲置',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '闲置',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '该设备使用的所有逻辑卷',
));

//
// Class: VirtualHost
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualHost' => '宿主机',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => '虚拟机',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '该宿主机托管的所有虚拟机',
));

//
// Class: Hypervisor
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => '集群',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => '名称',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => '物理机',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => '名称',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Farm' => '集群',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisor',
	'Class:Farm/Attribute:hypervisor_list+' => '集群由哪些Hypervisor 组成',
	'Class:Farm/Attribute:redundancy' => '高可用性',
	'Class:Farm/Attribute:redundancy/disabled' => '所有Hypervisor 正常,集群才正常',
	'Class:Farm/Attribute:redundancy/count' => '至少 %1$s 个Hypervisor 是正常的,集群才是正常的',
	'Class:Farm/Attribute:redundancy/percent' => '至少 %1$s %% 的Hypervisor 是正常的,集群才正常',
));

//
// Class: VirtualMachine
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualMachine' => '虚拟机',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => '宿主机',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => '名称',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => '操作系统家族',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => '名称',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => '操作系统版本',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => '名称',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => '操作系统许可证',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => '名称',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => '内存',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => '管理IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => '网卡',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '所有逻辑网卡',
));

//
// Class: LogicalVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:LogicalVolume' => '逻辑卷',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => '名称',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => '描述',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => '阵列级别',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => '容量',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => '存储系统',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => '名称',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => '服务器',
	'Class:LogicalVolume/Attribute:servers_list+' => '使用该逻辑卷的服务器',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => '虚拟设备',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '使用该逻辑卷的所有虚拟设备',
));

//
// Class: lnkServerToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkServerToVolume' => '链接 服务器 / 逻辑卷',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => '逻辑卷',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => '逻辑卷名称',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => '服务器',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => '服务器名称',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => '已用容量',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkVirtualDeviceToVolume' => '链接 虚拟设备 / 逻辑卷',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => '逻辑卷',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => '名称',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => '虚拟设备',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => '名称',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => '已用容量',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSanToDatacenterDevice' => '链接 SAN / 数据中心设备',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN 交换机',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => '名称',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => '设备',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => '名称',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN fc',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Device fc',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Tape' => '磁带',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => '名称',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => '描述',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => '容量',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => '磁带库',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => '名称',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NASFileSystem' => 'NAS 文件系统',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => '名称',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => '描述',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => '阵列级别',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => '容量',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS 名称',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Software' => '软件',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => '名称',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => '厂商',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => '版本',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => '文档',
	'Class:Software/Attribute:documents_list+' => '该软件的所有文档',
	'Class:Software/Attribute:type' => '类型',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => '数据库服务器',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server',
	'Class:Software/Attribute:type/Value:Middleware' => '中间件',
	'Class:Software/Attribute:type/Value:Middleware+' => '中间件',
	'Class:Software/Attribute:type/Value:OtherSoftware' => '其它软件',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '其它软件',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC 软件',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC 软件',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web 服务器',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web 服务器',
	'Class:Software/Attribute:softwareinstance_list' => '软件实例',
	'Class:Software/Attribute:softwareinstance_list+' => '该软件的所有实例',
	'Class:Software/Attribute:softwarepatch_list' => '软件补丁',
	'Class:Software/Attribute:softwarepatch_list+' => '该软件的所有补丁',
	'Class:Software/Attribute:softwarelicence_list' => '软件许可证',
	'Class:Software/Attribute:softwarelicence_list+' => '该软件的所有许可证',
));

//
// Class: Patch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Patch' => '补丁',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => '名称',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => '文档',
	'Class:Patch/Attribute:documents_list+' => '该补丁关联的所有文档',
	'Class:Patch/Attribute:description' => '描述',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => '补丁子类别',
	'Class:Patch/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: OSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSPatch' => '操作系统补丁',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => '设备',
	'Class:OSPatch/Attribute:functionalcis_list+' => '已安装该补丁的所有系统',
	'Class:OSPatch/Attribute:osversion_id' => '操作系统版本',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => '名称',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwarePatch' => '软件补丁',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => '软件',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => '名称',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => '软件实例',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '已安装该软件补丁的所有系统',
));

//
// Class: Licence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Licence' => '许可证',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => '名称',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => '文档',
	'Class:Licence/Attribute:documents_list+' => '该许可证关联的所有文档',
	'Class:Licence/Attribute:org_id' => '组织',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => '组织名称',
	'Class:Licence/Attribute:organization_name+' => '通用名称',
	'Class:Licence/Attribute:usage_limit' => '使用限制',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => '描述',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => '开始日期',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => '结束日期',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => '密钥',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => '永久有效',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => '否',
	'Class:Licence/Attribute:perpetual/Value:no+' => '否',
	'Class:Licence/Attribute:perpetual/Value:yes' => '是',
	'Class:Licence/Attribute:perpetual/Value:yes+' => '是',
	'Class:Licence/Attribute:finalclass' => '许可证子类别',
	'Class:Licence/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: OSLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSLicence' => '操作系统许可证',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => '操作系统版本',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => '名称',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => '虚拟机',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '使用该许可证的所有虚拟机',
	'Class:OSLicence/Attribute:servers_list' => '服务器',
	'Class:OSLicence/Attribute:servers_list+' => '使用该许可证的所有服务器',
));

//
// Class: SoftwareLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwareLicence' => '软件许可证',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => '软件',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => '名称',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => '软件实例',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '使用该许可证的所有系统',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToLicence' => '链接 文档 / 许可证',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => '许可证',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => '名称',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => '文档',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: Typology
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Typology' => '拓扑',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => '名称',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => '拓扑子类别',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: OSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSVersion' => '操作系统版本',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => '操作系统家族',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => '名称',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSFamily' => '操作系统家族',
	'Class:OSFamily+' => '',
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
// Class: Brand
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Brand' => '品牌',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => '物理设备',
	'Class:Brand/Attribute:physicaldevices_list+' => '该品牌的所有物理设备',
	'Class:Brand/UniquenessRule:name+' => '名称必须唯一',
	'Class:Brand/UniquenessRule:name' => '该品牌已存在',
));

//
// Class: Model
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Model' => '型号',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => '品牌',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => '品牌名称',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => '设备类型',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => '电源',
	'Class:Model/Attribute:type/Value:PowerSource+' => '电源',
	'Class:Model/Attribute:type/Value:DiskArray' => '磁盘阵列',
	'Class:Model/Attribute:type/Value:DiskArray+' => '磁盘阵列',
	'Class:Model/Attribute:type/Value:Enclosure' => '机位',
	'Class:Model/Attribute:type/Value:Enclosure+' => '机位',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP 电话',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP 电话',
	'Class:Model/Attribute:type/Value:MobilePhone' => '移动电话',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '移动电话',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => '网络设备',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => '网络设备',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => '配件',
	'Class:Model/Attribute:type/Value:Peripheral+' => '配件',
	'Class:Model/Attribute:type/Value:Printer' => '打印机',
	'Class:Model/Attribute:type/Value:Printer+' => '打印机',
	'Class:Model/Attribute:type/Value:Rack' => '机柜',
	'Class:Model/Attribute:type/Value:Rack+' => '机柜',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN 交换机',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN 交换机',
	'Class:Model/Attribute:type/Value:Server' => '服务器',
	'Class:Model/Attribute:type/Value:Server+' => 'Server',
	'Class:Model/Attribute:type/Value:StorageSystem' => '存储系统',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '存储系统',
	'Class:Model/Attribute:type/Value:Tablet' => '平板',
	'Class:Model/Attribute:type/Value:Tablet+' => '平板',
	'Class:Model/Attribute:type/Value:TapeLibrary' => '磁带库',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '磁带库',
	'Class:Model/Attribute:type/Value:Phone' => '电话',
	'Class:Model/Attribute:type/Value:Phone+' => '电话',
	'Class:Model/Attribute:physicaldevices_list' => '物理设备',
	'Class:Model/Attribute:physicaldevices_list+' => '该型号的所有物理设备',
	'Class:Model/UniquenessRule:name_brand+' => '名称必须唯一',
	'Class:Model/UniquenessRule:name_brand' => '该型号已存在',
));

//
// Class: NetworkDeviceType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkDeviceType' => '网络设备类型',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => '网络设备',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '该类型的所有网络设备',
));

//
// Class: IOSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:IOSVersion' => 'IOS 版本',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => '品牌',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => '名称',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToPatch' => '链接 文档 / 补丁',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => '补丁',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => '补丁名称',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => '文档',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => '链接 软件实例 / 软件补丁',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => '软件补丁',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => '软件补丁名称',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => '软件实例',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => '软件实例名称',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToOSPatch' => '链接 功能项 / 操作系统补丁',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => '操作系统补丁',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => '操作系统补丁名称',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => '功能项',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => '功能项名称',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToSoftware' => '链接 文档 / 软件',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => '软件',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => '软件名称',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => '文档',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContactToFunctionalCI' => '链接 联系人 / 功能项',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => '功能项',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => '功能项名称',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => '联系人',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => '联系人名称',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToFunctionalCI' => '链接 文档 / 功能项',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => '功能项',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => '功能项名称',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => '文档',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Subnet' => '子网',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => '描述',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => '子网名称',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => '所属组织',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => '名称',
	'Class:Subnet/Attribute:org_name+' => '名称',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => '掩码',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN 标记',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => '描述',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => '组织',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => '组织名称',
	'Class:VLAN/Attribute:org_name+' => '通用名称',
	'Class:VLAN/Attribute:subnets_list' => '子网',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => '物理网卡',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSubnetToVLAN' => '链接 子网 / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => '子网',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => '子网IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => '子网名称',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN 标记',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkInterface' => '网卡',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => '名称',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => '网卡子类别',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: IPInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:IPInterface' => 'IP Interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP 地址',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC 地址',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => '注释',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => '网关',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => '掩码',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => '速率',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PhysicalInterface' => '物理网卡',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => '设备',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => '设备名称',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkPhysicalInterfaceToVLAN' => '链接 物理网卡 / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => '物理网卡',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => '物理网卡名称',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => '设备',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => '设备名称',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN 标记',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:LogicalInterface' => '逻辑网卡',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => '虚拟机',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => '虚拟机名称',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FiberChannelInterface' => '光纤通道接口',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => '速率',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => '拓扑',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => '设备',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => '设备名称',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkConnectableCIToNetworkDevice' => '链接 可连接项 / 网络设备',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => '网络设备',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => '网络设备名称',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => '可连接的设备',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => '已连接的设备',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => '网络端口',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => '设备端口',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => '连接类型',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => '下联',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => '下联',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => '上联',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => '上联',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => '链接 应用方案 / 功能项',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => '应用方案',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => '应用方案名称',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => '功能项',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => '功能项名称',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => '链接 应用方案 / 业务流程',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => '业务流程',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => '业务流程名称',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => '应用方案',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => '应用方案名称',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
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
// Class: Group
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Group' => '配置组',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => '名称',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => '状态',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => '上线',
	'Class:Group/Attribute:status/Value:implementation+' => '上线',
	'Class:Group/Attribute:status/Value:obsolete' => '废弃',
	'Class:Group/Attribute:status/Value:obsolete+' => '废弃',
	'Class:Group/Attribute:status/Value:production' => '生产',
	'Class:Group/Attribute:status/Value:production+' => '生产',
	'Class:Group/Attribute:org_id' => '组织',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => '名称',
	'Class:Group/Attribute:owner_name+' => '通用名称',
	'Class:Group/Attribute:description' => '描述',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => '类型',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => '上级组',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => '名称',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => '链接的配置项',
	'Class:Group/Attribute:ci_list+' => '该组关联的所有配置项',
	'Class:Group/Attribute:parent_id_friendlyname' => '上级配置组',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkGroupToCI' => '链接 配置组 / 配置项',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => '组',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => '名称',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => '配置项',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => '名称',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => '原因',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
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
	'Menu:Application' => '应用',
	'Menu:Application+' => '所有应用',
	'Menu:DBServer' => '数据库服务器',
	'Menu:DBServer+' => '数据库服务器',
	'Menu:ConfigManagement' => '配置管理',
	'Menu:ConfigManagement+' => '配置管理',
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
	'Menu:ConfigManagementCI' => '配置项',
	'Menu:ConfigManagementCI+' => '配置项',
	'Menu:BusinessProcess' => '业务流程',
	'Menu:BusinessProcess+' => '所有业务流程',
	'Menu:ApplicationSolution' => '应用方案',
	'Menu:ApplicationSolution+' => '所有应用方案',
	'Menu:ConfigManagementSoftware' => '应用管理',
	'Menu:Licence' => '许可证',
	'Menu:Licence+' => '所有许可证',
	'Menu:Patch' => '补丁',
	'Menu:Patch+' => '所有补丁',
	'Menu:ApplicationInstance' => '已安装的软件',
	'Menu:ApplicationInstance+' => '应用和数据库服务器',
	'Menu:ConfigManagementHardware' => '基础设施管理',
	'Menu:Subnet' => '子网',
	'Menu:Subnet+' => '所有子网',
	'Menu:NetworkDevice' => '网络设备',
	'Menu:NetworkDevice+' => '所有网络设备',
	'Menu:Server' => '服务器',
	'Menu:Server+' => '所有服务器',
	'Menu:Printer' => '打印机',
	'Menu:Printer+' => '所有打印机',
	'Menu:MobilePhone' => '移动电话',
	'Menu:MobilePhone+' => '所有移动电话',
	'Menu:PC' => '个人电脑',
	'Menu:PC+' => '所有个人电脑',
	'Menu:NewContact' => '新建联系人',
	'Menu:NewContact+' => '新建联系人',
	'Menu:SearchContacts' => '搜索联系人',
	'Menu:SearchContacts+' => '搜索联系人',
	'Menu:NewCI' => '新建配置项',
	'Menu:NewCI+' => '新建配置项',
	'Menu:SearchCIs' => '搜索配置项',
	'Menu:SearchCIs+' => '搜索配置项',
	'Menu:ConfigManagement:Devices' => '设备',
	'Menu:ConfigManagement:AllDevices' => '基础设施',
	'Menu:ConfigManagement:virtualization' => '虚拟化',
	'Menu:ConfigManagement:EndUsers' => '终端设备',
	'Menu:ConfigManagement:SWAndApps' => '软件和应用',
	'Menu:ConfigManagement:Misc' => '杂项',
	'Menu:Group' => '配置组',
	'Menu:Group+' => '配置组',
	'Menu:ConfigManagement:Shortcuts' => '快捷方式',
	'Menu:ConfigManagement:AllContacts' => '所有联系人: %1$d',
	'Menu:Typology' => '配置管理',
	'Menu:Typology+' => '配置管理',
	'Menu:OSVersion' => 'OS 版本',
	'Menu:OSVersion+' => '',
	'Menu:Software' => '软件清单',
	'Menu:Software+' => '软件清单',
	'UI_WelcomeMenu_AllConfigItems' => '摘要',
	'Menu:ConfigManagement:Typology' => '配置管理',

));


// Add translation for Fieldsets

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Server:baseinfo' => '基本信息',
	'Server:Date' => '日期',
	'Server:moreinfo' => '更多信息',
	'Server:otherinfo' => '其它信息',
	'Server:power' => '电力供应',
	'Person:info' => '基本信息',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => '个人信息',
	'Person:notifiy' => '通知',
	'Class:Subnet/Tab:IPUsage' => 'IP 使用率',
	'Class:Subnet/Tab:IPUsage-explain' => '网卡IP范围: <em>%1$s</em> 到 <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => '空闲 IP',
	'Class:Subnet/Tab:FreeIPs-count' => '空闲 IP: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => '以下是抽取的10个空闲IP',
	'Class:Document:PreviewTab' => '预览',
));

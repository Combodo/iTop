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
	'Class:Organization/Attribute:status/Value:active' => '活动',
	'Class:Organization/Attribute:status/Value:active+' => '活动',
	'Class:Organization/Attribute:status/Value:inactive' => '非活动',
	'Class:Organization/Attribute:status/Value:inactive+' => '非活动',
	'Class:Organization/Attribute:parent_id' => '父级',
	'Class:Organization/Attribute:parent_id+' => '父级组织',
	'Class:Organization/Attribute:parent_name' => '父级名称',
	'Class:Organization/Attribute:parent_name+' => '父级组织名称',
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model~~',
	'Class:Organization/Attribute:deliverymodel_id+' => '交付模式',
	'Class:Organization/Attribute:deliverymodel_name' => '交付模式名称',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => '上级组织',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '上级组织',
	'Class:Organization/Attribute:overview' => '预览',
	'Organization:Overview:FunctionalCIs' => '该组织的配置项',
	'Organization:Overview:FunctionalCIs:subtitle' => '按照类型',
	'Organization:Overview:Users' => '该组织内的iTop 用户',
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
	'Class:Contact/Attribute:notify' => '通知',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => '否',
	'Class:Contact/Attribute:notify/Value:no+' => '否',
	'Class:Contact/Attribute:notify/Value:yes' => '是',
	'Class:Contact/Attribute:notify/Value:yes+' => '是',
	'Class:Contact/Attribute:function' => '功能',
	'Class:Contact/Attribute:function+' => '~~',
	'Class:Contact/Attribute:cis_list' => '配置项',
	'Class:Contact/Attribute:cis_list+' => '该联系人的所有关联配置项',
	'Class:Contact/Attribute:finalclass' => '类别',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Person' => '人员',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Last Name~~',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => '姓',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => '员工编号',
	'Class:Person/Attribute:employee_number+' => '~~',
	'Class:Person/Attribute:mobile_phone' => '移动电话',
	'Class:Person/Attribute:mobile_phone+' => '~~',
	'Class:Person/Attribute:location_id' => '位置',
	'Class:Person/Attribute:location_id+' => '~~',
	'Class:Person/Attribute:location_name' => '位置名称',
	'Class:Person/Attribute:location_name+' => '~~',
	'Class:Person/Attribute:manager_id' => '经理',
	'Class:Person/Attribute:manager_id+' => '~~',
	'Class:Person/Attribute:manager_name' => '经理的名字',
	'Class:Person/Attribute:manager_name+' => '~~',
	'Class:Person/Attribute:team_list' => '团队',
	'Class:Person/Attribute:team_list+' => 'All the teams this person belongs to~~',
	'Class:Person/Attribute:tickets_list' => '工单',
	'Class:Person/Attribute:tickets_list+' => 'All the tickets this person is the caller~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager friendly name~~',
	'Class:Person/Attribute:manager_id_friendlyname+' => '~~',
	'Class:Person/Attribute:picture' => '图片',
	'Class:Person/Attribute:picture+' => '~~',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization~~',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number~~',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization~~',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name~~',
));

//
// Class: Team
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Team' => '团队',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => '成员',
	'Class:Team/Attribute:persons_list+' => 'All the people belonging to this team~~',
	'Class:Team/Attribute:tickets_list' => '工单',
	'Class:Team/Attribute:tickets_list+' => 'All the tickets assigned to this team~~',
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
	'Class:Document/Attribute:org_id+' => '~~',
	'Class:Document/Attribute:org_name' => '组织名称',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => '文档类别',
	'Class:Document/Attribute:documenttype_id+' => '~~',
	'Class:Document/Attribute:documenttype_name' => 'Document type name~~',
	'Class:Document/Attribute:documenttype_name+' => '~~',
	'Class:Document/Attribute:version' => '版本',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => '描述',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => '状态',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => '草稿',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => '报废',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => '已发布',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => '配置项',
	'Class:Document/Attribute:cis_list+' => 'All the configuration items linked to this document~~',
	'Class:Document/Attribute:contracts_list' => 'Contracts~~',
	'Class:Document/Attribute:contracts_list+' => 'All the contracts linked to this document~~',
	'Class:Document/Attribute:services_list' => '服务',
	'Class:Document/Attribute:services_list+' => 'All the services linked to this document~~',
	'Class:Document/Attribute:finalclass' => '文档类别',
	'Class:Document/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: DocumentFile
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentFile' => '文档文件',
	'Class:DocumentFile+' => '~~',
	'Class:DocumentFile/Attribute:file' => '文件',
	'Class:DocumentFile/Attribute:file+' => '~~',
));

//
// Class: DocumentNote
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentNote' => '文档笔记',
	'Class:DocumentNote+' => '~~',
	'Class:DocumentNote/Attribute:text' => '文本',
	'Class:DocumentNote/Attribute:text+' => '~~',
));

//
// Class: DocumentWeb
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentWeb' => '网页文档',
	'Class:DocumentWeb+' => '~~',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '~~',
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
	'Class:FunctionalCI/Attribute:description+' => '~~',
	'Class:FunctionalCI/Attribute:org_id' => '拥有者组织',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => '组织名称',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Common name~~',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Business criticity~~',
	'Class:FunctionalCI/Attribute:business_criticity+' => '~~',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '中',
	'Class:FunctionalCI/Attribute:move2production' => '投产时间',
	'Class:FunctionalCI/Attribute:move2production+' => '~~',
	'Class:FunctionalCI/Attribute:contacts_list' => '联系人',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'All the contacts for this configuration item~~',
	'Class:FunctionalCI/Attribute:documents_list' => '文档',
	'Class:FunctionalCI/Attribute:documents_list+' => 'All the documents linked to this configuration item~~',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => '应用方案',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'All the application solutions depending on this configuration item~~',
	'Class:FunctionalCI/Attribute:providercontracts_list' => '供应商合同',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'All the provider contracts for this configuration item~~',
	'Class:FunctionalCI/Attribute:services_list' => '服务',
	'Class:FunctionalCI/Attribute:services_list+' => 'All the services impacted by this configuration item~~',
	'Class:FunctionalCI/Attribute:softwares_list' => '软件',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'All the softwares installed on this configuration item~~',
	'Class:FunctionalCI/Attribute:tickets_list' => '工单',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'All the tickets for this configuration item~~',
	'Class:FunctionalCI/Attribute:finalclass' => '类别',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => '活跃的工单',
));

//
// Class: PhysicalDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PhysicalDevice' => '物理设备',
	'Class:PhysicalDevice+' => '~~',
	'Class:PhysicalDevice/Attribute:serialnumber' => '序列号',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '~~',
	'Class:PhysicalDevice/Attribute:location_id' => '位置',
	'Class:PhysicalDevice/Attribute:location_id+' => '~~',
	'Class:PhysicalDevice/Attribute:location_name' => '位置名称',
	'Class:PhysicalDevice/Attribute:location_name+' => '~~',
	'Class:PhysicalDevice/Attribute:status' => '状态',
	'Class:PhysicalDevice/Attribute:status+' => '~~',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => '实施',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '实施',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:production' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => '空闲',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '空闲',
	'Class:PhysicalDevice/Attribute:brand_id' => '品牌',
	'Class:PhysicalDevice/Attribute:brand_id+' => '~~',
	'Class:PhysicalDevice/Attribute:brand_name' => '品牌名称',
	'Class:PhysicalDevice/Attribute:brand_name+' => '~~',
	'Class:PhysicalDevice/Attribute:model_id' => '型号',
	'Class:PhysicalDevice/Attribute:model_id+' => '~~',
	'Class:PhysicalDevice/Attribute:model_name' => '型号名称',
	'Class:PhysicalDevice/Attribute:model_name+' => '~~',
	'Class:PhysicalDevice/Attribute:asset_number' => '资产编号',
	'Class:PhysicalDevice/Attribute:asset_number+' => '~~',
	'Class:PhysicalDevice/Attribute:purchase_date' => '购买日期',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '~~',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => '过保日期',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '~~',
));

//
// Class: Rack
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Rack' => 'Rack~~',
	'Class:Rack+' => '~~',
	'Class:Rack/Attribute:nb_u' => 'Rack units~~',
	'Class:Rack/Attribute:nb_u+' => '~~',
	'Class:Rack/Attribute:device_list' => '设备',
	'Class:Rack/Attribute:device_list+' => 'All the physical devices racked into this rack~~',
	'Class:Rack/Attribute:enclosure_list' => '机位',
	'Class:Rack/Attribute:enclosure_list+' => 'All the enclosures in this rack~~',
));

//
// Class: TelephonyCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TelephonyCI' => 'Telephony CI~~',
	'Class:TelephonyCI+' => '~~',
	'Class:TelephonyCI/Attribute:phonenumber' => '电话号码',
	'Class:TelephonyCI/Attribute:phonenumber+' => '~~',
));

//
// Class: Phone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Phone' => '电话',
	'Class:Phone+' => '~~',
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
	'Class:IPPhone+' => '~~',
));

//
// Class: Tablet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Tablet' => 'Tablet~~',
	'Class:Tablet+' => '~~',
));

//
// Class: ConnectableCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ConnectableCI' => '可连接的 CI',
	'Class:ConnectableCI+' => '物理 CI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Network devices~~',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'All network devices connected to this device~~',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Network interfaces~~',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'All the physical network interfaces~~',
));

//
// Class: DatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DatacenterDevice' => '数据中心设备',
	'Class:DatacenterDevice+' => '~~',
	'Class:DatacenterDevice/Attribute:rack_id' => '机柜',
	'Class:DatacenterDevice/Attribute:rack_id+' => '~~',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Rack name~~',
	'Class:DatacenterDevice/Attribute:rack_name+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure~~',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Enclosure name~~',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '~~',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Rack units~~',
	'Class:DatacenterDevice/Attribute:nb_u+' => '~~',
	'Class:DatacenterDevice/Attribute:managementip' => '管理ip',
	'Class:DatacenterDevice/Attribute:managementip+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA source~~',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA source name~~',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB source~~',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB source name~~',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '~~',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC ports~~',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'All the fiber channel interfaces for this device~~',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs~~',
	'Class:DatacenterDevice/Attribute:san_list+' => 'All the SAN switches connected to this device~~',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundancy~~',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'The device is up if at least one power connection (A or B) is up~~',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'The device is up if all its power connections are up~~',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'The device is up if at least %1$s %% of its power connections are up~~',
));

//
// Class: NetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkDevice' => '网络设备',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Network type~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Network type name~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '~~',
	'Class:NetworkDevice/Attribute:connectablecis_list' => '设备',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'All the devices connected to this network device~~',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS 版本',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '~~',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS version name~~',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '~~',
	'Class:NetworkDevice/Attribute:ram' => '内存',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Server' => '服务器',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'OS 家族',
	'Class:Server/Attribute:osfamily_id+' => '~~',
	'Class:Server/Attribute:osfamily_name' => 'OS 家族名称',
	'Class:Server/Attribute:osfamily_name+' => '~~',
	'Class:Server/Attribute:osversion_id' => 'OS 版本',
	'Class:Server/Attribute:osversion_id+' => '~~',
	'Class:Server/Attribute:osversion_name' => 'OS 版本名称',
	'Class:Server/Attribute:osversion_name+' => '~~',
	'Class:Server/Attribute:oslicence_id' => 'OS 许可证',
	'Class:Server/Attribute:oslicence_id+' => '~~',
	'Class:Server/Attribute:oslicence_name' => 'OS 许可证名称',
	'Class:Server/Attribute:oslicence_name+' => '~~',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => '内存',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:Server/Attribute:logicalvolumes_list+' => 'All the logical volumes connected to this server~~',
));

//
// Class: StorageSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:StorageSystem' => '存储系统',
	'Class:StorageSystem+' => '~~',
	'Class:StorageSystem/Attribute:logicalvolume_list' => '逻辑卷',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'All the logical volumes in this storage system~~',
));

//
// Class: SANSwitch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SANSwitch' => 'SAN 交换机',
	'Class:SANSwitch+' => '~~',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => '设备',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'All the devices connected to this SAN switch~~',
));

//
// Class: TapeLibrary
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:TapeLibrary' => '磁带库',
	'Class:TapeLibrary+' => '~~',
	'Class:TapeLibrary/Attribute:tapes_list' => '磁带',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'All the tapes in the tape library~~',
));

//
// Class: NAS
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '~~',
	'Class:NAS/Attribute:nasfilesystem_list' => '文件系统',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'All the file systems in this NAS~~',
));

//
// Class: PC
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'OS 家族',
	'Class:PC/Attribute:osfamily_id+' => '~~',
	'Class:PC/Attribute:osfamily_name' => 'OS 家族名称',
	'Class:PC/Attribute:osfamily_name+' => '~~',
	'Class:PC/Attribute:osversion_id' => 'OS 版本',
	'Class:PC/Attribute:osversion_id+' => '~~',
	'Class:PC/Attribute:osversion_name' => 'OS 版本名称',
	'Class:PC/Attribute:osversion_name+' => '~~',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => '内存',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => '类型',
	'Class:PC/Attribute:type+' => '~~',
	'Class:PC/Attribute:type/Value:desktop' => '桌面式',
	'Class:PC/Attribute:type/Value:desktop+' => '桌面式',
	'Class:PC/Attribute:type/Value:laptop' => '便携式',
	'Class:PC/Attribute:type/Value:laptop+' => '便携式',
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
	'Class:PowerConnection' => 'Power Connection~~',
	'Class:PowerConnection+' => '~~',
));

//
// Class: PowerSource
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PowerSource' => '电源',
	'Class:PowerSource+' => '~~',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU',
	'Class:PowerSource/Attribute:pdus_list+' => 'All the PDUs using this power source~~',
));

//
// Class: PDU
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '~~',
	'Class:PDU/Attribute:rack_id' => '机柜',
	'Class:PDU/Attribute:rack_id+' => '~~',
	'Class:PDU/Attribute:rack_name' => 'Rack name~~',
	'Class:PDU/Attribute:rack_name+' => '~~',
	'Class:PDU/Attribute:powerstart_id' => 'Power start~~',
	'Class:PDU/Attribute:powerstart_id+' => '~~',
	'Class:PDU/Attribute:powerstart_name' => 'Power start name~~',
	'Class:PDU/Attribute:powerstart_name+' => '~~',
));

//
// Class: Peripheral
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Peripheral' => '配件',
	'Class:Peripheral+' => '~~',
));

//
// Class: Enclosure
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Enclosure' => '机位',
	'Class:Enclosure+' => '~~',
	'Class:Enclosure/Attribute:rack_id' => 'Rack~~',
	'Class:Enclosure/Attribute:rack_id+' => '~~',
	'Class:Enclosure/Attribute:rack_name' => 'Rack name~~',
	'Class:Enclosure/Attribute:rack_name+' => '~~',
	'Class:Enclosure/Attribute:nb_u' => 'Rack units~~',
	'Class:Enclosure/Attribute:nb_u+' => '~~',
	'Class:Enclosure/Attribute:device_list' => '设备',
	'Class:Enclosure/Attribute:device_list+' => 'All the devices in this enclosure~~',
));

//
// Class: ApplicationSolution
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ApplicationSolution' => '应用方案',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => '配置项',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'All the configuration items that compose this application solution~~',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => '业务流程',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'All the business processes depending on this application solution~~',
	'Class:ApplicationSolution/Attribute:status' => '状态',
	'Class:ApplicationSolution/Attribute:status+' => '~~',
	'Class:ApplicationSolution/Attribute:status/Value:active' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => '停用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '停用',
	'Class:ApplicationSolution/Attribute:redundancy' => '影响分析: 冗余配置',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up~~',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up~~',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up~~',
));

//
// Class: BusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:BusinessProcess' => '业务流程',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Application solutions~~',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'All the application solutions that impact this business process~~',
	'Class:BusinessProcess/Attribute:status' => '状态',
	'Class:BusinessProcess/Attribute:status+' => '~~',
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
	'Class:SoftwareInstance/Attribute:system_id+' => '~~',
	'Class:SoftwareInstance/Attribute:system_name' => 'System name~~',
	'Class:SoftwareInstance/Attribute:system_name+' => '~~',
	'Class:SoftwareInstance/Attribute:software_id' => '软件',
	'Class:SoftwareInstance/Attribute:software_id+' => '~~',
	'Class:SoftwareInstance/Attribute:software_name' => '软件',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => '软件许可证',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '~~',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Software licence name~~',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '~~',
	'Class:SoftwareInstance/Attribute:path' => '路径',
	'Class:SoftwareInstance/Attribute:path+' => '~~',
	'Class:SoftwareInstance/Attribute:status' => '状态',
	'Class:SoftwareInstance/Attribute:status+' => '~~',
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
	'Class:Middleware+' => '~~',
	'Class:Middleware/Attribute:middlewareinstance_list' => '中间件实例',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'All the middleware instances provided by this middleware~~',
));

//
// Class: DBServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DBServer' => '数据库',
	'Class:DBServer+' => '数据库服务器SW',
	'Class:DBServer/Attribute:dbschema_list' => 'DB schemas~~',
	'Class:DBServer/Attribute:dbschema_list+' => 'All the database schemas for this DB server~~',
));

//
// Class: WebServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WebServer' => 'Web 服务器',
	'Class:WebServer+' => '~~',
	'Class:WebServer/Attribute:webapp_list' => 'Web 应用',
	'Class:WebServer/Attribute:webapp_list+' => 'All the web applications available on this web server~~',
));

//
// Class: PCSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PCSoftware' => 'PC 软件',
	'Class:PCSoftware+' => '~~',
));

//
// Class: OtherSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OtherSoftware' => '其它软件',
	'Class:OtherSoftware+' => '~~',
));

//
// Class: MiddlewareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:MiddlewareInstance' => 'Middleware Instance~~',
	'Class:MiddlewareInstance+' => '~~',
	'Class:MiddlewareInstance/Attribute:middleware_id' => '中间件',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '~~',
	'Class:MiddlewareInstance/Attribute:middleware_name' => '中间件名称',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '~~',
));

//
// Class: DatabaseSchema
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DatabaseSchema' => 'Database Schema~~',
	'Class:DatabaseSchema+' => '~~',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB server~~',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '~~',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB server name~~',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '~~',
));

//
// Class: WebApplication
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:WebApplication' => 'Web 应用',
	'Class:WebApplication+' => '~~',
	'Class:WebApplication/Attribute:webserver_id' => 'Web 服务器',
	'Class:WebApplication/Attribute:webserver_id+' => '~~',
	'Class:WebApplication/Attribute:webserver_name' => 'Web 服务器名称',
	'Class:WebApplication/Attribute:webserver_name+' => '~~',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '~~',
));


//
// Class: VirtualDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualDevice' => '虚拟设备',
	'Class:VirtualDevice+' => '~~',
	'Class:VirtualDevice/Attribute:status' => '状态',
	'Class:VirtualDevice/Attribute:status+' => '~~',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'implementation~~',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:production' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:stock' => '空闲',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '空闲',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'All the logical volumes used by this device~~',
));

//
// Class: VirtualHost
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualHost' => '虚拟化宿主机',
	'Class:VirtualHost+' => '~~',
	'Class:VirtualHost/Attribute:virtualmachine_list' => '虚拟机',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'All the virtual machines hosted by this host~~',
));

//
// Class: Hypervisor
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '~~',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm~~',
	'Class:Hypervisor/Attribute:farm_id+' => '~~',
	'Class:Hypervisor/Attribute:farm_name' => 'Farm name~~',
	'Class:Hypervisor/Attribute:farm_name+' => '~~',
	'Class:Hypervisor/Attribute:server_id' => 'Server~~',
	'Class:Hypervisor/Attribute:server_id+' => '~~',
	'Class:Hypervisor/Attribute:server_name' => 'Server name~~',
	'Class:Hypervisor/Attribute:server_name+' => '~~',
));

//
// Class: Farm
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Farm' => 'Farm~~',
	'Class:Farm+' => '~~',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'All the hypervisors that compose this farm~~',
	'Class:Farm/Attribute:redundancy' => '高可用',
	'Class:Farm/Attribute:redundancy/disabled' => 'The farm is up if all the hypervisors are up~~',
	'Class:Farm/Attribute:redundancy/count' => 'The farm is up if at least %1$s hypervisor(s) is(are) up~~',
	'Class:Farm/Attribute:redundancy/percent' => 'The farm is up if at least %1$s %% of the hypervisors are up~~',
));

//
// Class: VirtualMachine
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VirtualMachine' => '虚拟机',
	'Class:VirtualMachine+' => '~~',
	'Class:VirtualMachine/Attribute:virtualhost_id' => '宿主机',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '~~',
	'Class:VirtualMachine/Attribute:virtualhost_name' => '宿主机名称',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS 家族',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS family name~~',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS 版本',
	'Class:VirtualMachine/Attribute:osversion_id+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS version name~~',
	'Class:VirtualMachine/Attribute:osversion_name+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS licence~~',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS licence name~~',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '~~',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '~~',
	'Class:VirtualMachine/Attribute:ram' => '内存',
	'Class:VirtualMachine/Attribute:ram+' => '~~',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '~~',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => '网卡',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'All the logical network interfaces~~',
));

//
// Class: LogicalVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:LogicalVolume' => '逻辑卷',
	'Class:LogicalVolume+' => '~~',
	'Class:LogicalVolume/Attribute:name' => '名称',
	'Class:LogicalVolume/Attribute:name+' => '~~',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '~~',
	'Class:LogicalVolume/Attribute:description' => '描述',
	'Class:LogicalVolume/Attribute:description+' => '~~',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid level~~',
	'Class:LogicalVolume/Attribute:raid_level+' => '~~',
	'Class:LogicalVolume/Attribute:size' => '容量',
	'Class:LogicalVolume/Attribute:size+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_id' => '存储系统',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Storage system name~~',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '~~',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servers~~',
	'Class:LogicalVolume/Attribute:servers_list+' => 'All the servers using this volume~~',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => '虚拟设备',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'All the virtual devices using this volume~~',
));

//
// Class: lnkServerToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkServerToVolume' => 'Link Server / Volume~~',
	'Class:lnkServerToVolume+' => '~~',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume~~',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume name~~',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server~~',
	'Class:lnkServerToVolume/Attribute:server_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Server name~~',
	'Class:lnkServerToVolume/Attribute:server_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Size used~~',
	'Class:lnkServerToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Virtual Device / Volume~~',
	'Class:lnkVirtualDeviceToVolume+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volume name~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtual device~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Virtual device name~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Size used~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Datacenter Device~~',
	'Class:lnkSanToDatacenterDevice+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN switch~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN switch name~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Device~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Device name~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN fc~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Device fc~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '~~',
));

//
// Class: Tape
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Tape' => 'Tape~~',
	'Class:Tape+' => '~~',
	'Class:Tape/Attribute:name' => 'Name~~',
	'Class:Tape/Attribute:name+' => '~~',
	'Class:Tape/Attribute:description' => 'Description~~',
	'Class:Tape/Attribute:description+' => '~~',
	'Class:Tape/Attribute:size' => 'Size~~',
	'Class:Tape/Attribute:size+' => '~~',
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape library~~',
	'Class:Tape/Attribute:tapelibrary_id+' => '~~',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tape library name~~',
	'Class:Tape/Attribute:tapelibrary_name+' => '~~',
));

//
// Class: NASFileSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NASFileSystem' => 'NAS File System~~',
	'Class:NASFileSystem+' => '~~',
	'Class:NASFileSystem/Attribute:name' => 'Name~~',
	'Class:NASFileSystem/Attribute:name+' => '~~',
	'Class:NASFileSystem/Attribute:description' => 'Description~~',
	'Class:NASFileSystem/Attribute:description+' => '~~',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid level~~',
	'Class:NASFileSystem/Attribute:raid_level+' => '~~',
	'Class:NASFileSystem/Attribute:size' => 'Size~~',
	'Class:NASFileSystem/Attribute:size+' => '~~',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS~~',
	'Class:NASFileSystem/Attribute:nas_id+' => '~~',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS name~~',
	'Class:NASFileSystem/Attribute:nas_name+' => '~~',
));

//
// Class: Software
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Software' => '软件',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => '名称',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => '作者',
	'Class:Software/Attribute:vendor+' => '~~',
	'Class:Software/Attribute:version' => '版本',
	'Class:Software/Attribute:version+' => '~~',
	'Class:Software/Attribute:documents_list' => '文档',
	'Class:Software/Attribute:documents_list+' => 'All the documents linked to this software~~',
	'Class:Software/Attribute:type' => '类别',
	'Class:Software/Attribute:type+' => '~~',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server~~',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server~~',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware~~',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware~~',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Other Software~~',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Other Software~~',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software~~',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software~~',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web Server~~',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web Server~~',
	'Class:Software/Attribute:softwareinstance_list' => 'Software Instances~~',
	'Class:Software/Attribute:softwareinstance_list+' => 'All the software instances for this software~~',
	'Class:Software/Attribute:softwarepatch_list' => 'Software Patches~~',
	'Class:Software/Attribute:softwarepatch_list+' => 'All the patchs for this software~~',
	'Class:Software/Attribute:softwarelicence_list' => 'Software Licences~~',
	'Class:Software/Attribute:softwarelicence_list+' => 'All the licences for this software~~',
));

//
// Class: Patch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Patch' => '补丁',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => '名称',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documents~~',
	'Class:Patch/Attribute:documents_list+' => 'All the documents linked to this patch~~',
	'Class:Patch/Attribute:description' => '描述',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Type~~',
	'Class:Patch/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: OSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSPatch' => 'OS 补丁',
	'Class:OSPatch+' => '~~',
	'Class:OSPatch/Attribute:functionalcis_list' => '设备',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'All the systems where this patch is installed~~',
	'Class:OSPatch/Attribute:osversion_id' => 'OS version~~',
	'Class:OSPatch/Attribute:osversion_id+' => '~~',
	'Class:OSPatch/Attribute:osversion_name' => 'OS version name~~',
	'Class:OSPatch/Attribute:osversion_name+' => '~~',
));

//
// Class: SoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwarePatch' => 'Software Patch~~',
	'Class:SoftwarePatch+' => '~~',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software~~',
	'Class:SoftwarePatch/Attribute:software_id+' => '~~',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software name~~',
	'Class:SoftwarePatch/Attribute:software_name+' => '~~',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software instances~~',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'All the systems where this software patch is installed~~',
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
	'Class:Licence/Attribute:documents_list+' => 'All the documents linked to this licence~~',
	'Class:Licence/Attribute:org_id' => '所有者',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organization name~~',
	'Class:Licence/Attribute:organization_name+' => 'Common name~~',
	'Class:Licence/Attribute:usage_limit' => '使用限制',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => '描述',
	'Class:Licence/Attribute:description+' => '~~',
	'Class:Licence/Attribute:start_date' => '开始日期',
	'Class:Licence/Attribute:start_date+' => '~~',
	'Class:Licence/Attribute:end_date' => '结束日期',
	'Class:Licence/Attribute:end_date+' => '~~',
	'Class:Licence/Attribute:licence_key' => 'Key',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => '配件',
	'Class:Licence/Attribute:perpetual+' => '~~',
	'Class:Licence/Attribute:perpetual/Value:no' => '否',
	'Class:Licence/Attribute:perpetual/Value:no+' => '否',
	'Class:Licence/Attribute:perpetual/Value:yes' => '是',
	'Class:Licence/Attribute:perpetual/Value:yes+' => '是',
	'Class:Licence/Attribute:finalclass' => '类型',
	'Class:Licence/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: OSLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSLicence' => 'OS Licence~~',
	'Class:OSLicence+' => '~~',
	'Class:OSLicence/Attribute:osversion_id' => 'OS version~~',
	'Class:OSLicence/Attribute:osversion_id+' => '~~',
	'Class:OSLicence/Attribute:osversion_name' => 'OS version name~~',
	'Class:OSLicence/Attribute:osversion_name+' => '~~',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtual machines~~',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'All the virtual machines where this licence is used~~',
	'Class:OSLicence/Attribute:servers_list' => 'servers~~',
	'Class:OSLicence/Attribute:servers_list+' => 'All the servers where this licence is used~~',
));

//
// Class: SoftwareLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:SoftwareLicence' => 'Software Licence~~',
	'Class:SoftwareLicence+' => '~~',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software~~',
	'Class:SoftwareLicence/Attribute:software_id+' => '~~',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software name~~',
	'Class:SoftwareLicence/Attribute:software_name+' => '~~',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software instances~~',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'All the systems where this licence is used~~',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToLicence' => 'Link Document / Licence~~',
	'Class:lnkDocumentToLicence+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licence~~',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Licence name~~',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Document name~~',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '~~',
));

//
// Class: Typology
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Typology' => 'Typology~~',
	'Class:Typology+' => '~~',
	'Class:Typology/Attribute:name' => 'Name~~',
	'Class:Typology/Attribute:name+' => '~~',
	'Class:Typology/Attribute:finalclass' => 'Type~~',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: OSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSVersion' => 'OS 版本',
	'Class:OSVersion+' => '~~',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS 家族',
	'Class:OSVersion/Attribute:osfamily_id+' => '~~',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS family name~~',
	'Class:OSVersion/Attribute:osfamily_name+' => '~~',
));

//
// Class: OSFamily
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OSFamily' => 'OS 家族',
	'Class:OSFamily+' => '~~',
));

//
// Class: DocumentType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:DocumentType' => '文档类别',
	'Class:DocumentType+' => '~~',
));

//
// Class: ContactType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:ContactType' => 'Contact Type~~',
	'Class:ContactType+' => '~~',
));

//
// Class: Brand
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Brand' => '品牌',
	'Class:Brand+' => '~~',
	'Class:Brand/Attribute:physicaldevices_list' => 'Physical devices~~',
	'Class:Brand/Attribute:physicaldevices_list+' => 'All the physical devices corresponding to this brand~~',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique~~',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists~~',
));

//
// Class: Model
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Model' => '型号',
	'Class:Model+' => '~~',
	'Class:Model/Attribute:brand_id' => '品牌',
	'Class:Model/Attribute:brand_id+' => '~~',
	'Class:Model/Attribute:brand_name' => 'Brand name~~',
	'Class:Model/Attribute:brand_name+' => '~~',
	'Class:Model/Attribute:type' => 'Device type~~',
	'Class:Model/Attribute:type+' => '~~',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Power Source~~',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Power Source~~',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Disk Array~~',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Disk Array~~',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure~~',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure~~',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP Phone~~',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP Phone~~',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobile Phone~~',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Mobile Phone~~',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS~~',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS~~',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Network Device~~',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Network Device~~',
	'Class:Model/Attribute:type/Value:PC' => 'PC~~',
	'Class:Model/Attribute:type/Value:PC+' => 'PC~~',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU~~',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU~~',
	'Class:Model/Attribute:type/Value:Peripheral' => '配件',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Peripheral~~',
	'Class:Model/Attribute:type/Value:Printer' => '打印机~~',
	'Class:Model/Attribute:type/Value:Printer+' => 'Printer~~',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack~~',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack~~',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN 交换机',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN switch~~',
	'Class:Model/Attribute:type/Value:Server' => '服务器',
	'Class:Model/Attribute:type/Value:Server+' => 'Server~~',
	'Class:Model/Attribute:type/Value:StorageSystem' => '存储系统',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Storage System~~',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet~~',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet~~',
	'Class:Model/Attribute:type/Value:TapeLibrary' => '磁带库',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tape Library~~',
	'Class:Model/Attribute:type/Value:Phone' => 'Telephone~~',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telephone~~',
	'Class:Model/Attribute:physicaldevices_list' => 'Physical devices~~',
	'Class:Model/Attribute:physicaldevices_list+' => 'All the physical devices corresponding to this model~~',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand~~',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand~~',
));

//
// Class: NetworkDeviceType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkDeviceType' => 'Network Device Type~~',
	'Class:NetworkDeviceType+' => '~~',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Network devices~~',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'All the network devices corresponding to this type~~',
));

//
// Class: IOSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:IOSVersion' => 'IOS 版本',
	'Class:IOSVersion+' => '~~',
	'Class:IOSVersion/Attribute:brand_id' => '品牌',
	'Class:IOSVersion/Attribute:brand_id+' => '~~',
	'Class:IOSVersion/Attribute:brand_name' => 'Brand name~~',
	'Class:IOSVersion/Attribute:brand_name+' => '~~',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToPatch' => 'Link Document / Patch~~',
	'Class:lnkDocumentToPatch+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch~~',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Patch name~~',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Document name~~',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '~~',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Software Instance / Software Patch~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software patch~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Software patch name~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Software instance~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Software instance name~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '~~',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Link FunctionalCI / OS patch~~',
	'Class:lnkFunctionalCIToOSPatch+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS patch~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS patch name~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Functionalci~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Functionalci name~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToSoftware' => 'Link Document / Software~~',
	'Class:lnkDocumentToSoftware+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software~~',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software name~~',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Document name~~',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '~~',
));

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contact / FunctionalCI~~',
	'Class:lnkContactToFunctionalCI+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Functionalci~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Contact name~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '~~',
));

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link Document / FunctionalCI~~',
	'Class:lnkDocumentToFunctionalCI+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Functionalci~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Document name~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '~~',
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
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => '拥有者组织',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => '名称',
	'Class:Subnet/Attribute:org_name+' => 'Common name~~',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => '掩码',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '~~',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:VLAN/Attribute:vlan_tag+' => '~~',
	'Class:VLAN/Attribute:description' => '描述',
	'Class:VLAN/Attribute:description+' => '~~',
	'Class:VLAN/Attribute:org_id' => '组织',
	'Class:VLAN/Attribute:org_id+' => '~~',
	'Class:VLAN/Attribute:org_name' => 'Organization name~~',
	'Class:VLAN/Attribute:org_name+' => 'Common name~~',
	'Class:VLAN/Attribute:subnets_list' => '子网',
	'Class:VLAN/Attribute:subnets_list+' => '~~',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physical network interfaces~~',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '~~',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkSubnetToVLAN' => 'Link Subnet / VLAN~~',
	'Class:lnkSubnetToVLAN+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnet name~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '~~',
));

//
// Class: NetworkInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:NetworkInterface' => '网络接口',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Name~~',
	'Class:NetworkInterface/Attribute:name+' => '~~',
	'Class:NetworkInterface/Attribute:finalclass' => 'Type~~',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: IPInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:IPInterface' => 'IP Interface~~',
	'Class:IPInterface+' => '~~',
	'Class:IPInterface/Attribute:ipaddress' => 'IP 地址',
	'Class:IPInterface/Attribute:ipaddress+' => '~~',


	'Class:IPInterface/Attribute:macaddress' => 'MAC 地址',
	'Class:IPInterface/Attribute:macaddress+' => '~~',
	'Class:IPInterface/Attribute:comment' => '说明',
	'Class:IPInterface/Attribute:coment+' => '~~',
	'Class:IPInterface/Attribute:ipgateway' => '网关',
	'Class:IPInterface/Attribute:ipgateway+' => '~~',
	'Class:IPInterface/Attribute:ipmask' => '掩码',
	'Class:IPInterface/Attribute:ipmask+' => '~~',
	'Class:IPInterface/Attribute:speed' => '速率',
	'Class:IPInterface/Attribute:speed+' => '~~',
));

//
// Class: PhysicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:PhysicalInterface' => 'Physical Interface~~',
	'Class:PhysicalInterface+' => '~~',
	'Class:PhysicalInterface/Attribute:connectableci_id' => '设备',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '~~',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Device name~~',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '~~',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs~~',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link PhysicalInterface / VLAN~~',
	'Class:lnkPhysicalInterfaceToVLAN+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Physical Interface~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Physical Interface Name~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Device~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Device name~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '~~',
));


//
// Class: LogicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:LogicalInterface' => 'Logical Interface~~',
	'Class:LogicalInterface+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => '虚拟机',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtual machine name~~',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '~~',
));

//
// Class: FiberChannelInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FiberChannelInterface' => 'Fiber Channel Interface~~',
	'Class:FiberChannelInterface+' => '~~',
	'Class:FiberChannelInterface/Attribute:speed' => '速率',
	'Class:FiberChannelInterface/Attribute:speed+' => '~~',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topology~~',
	'Class:FiberChannelInterface/Attribute:topology+' => '~~',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN~~',
	'Class:FiberChannelInterface/Attribute:wwn+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => '设备',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Device name~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '~~',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / NetworkDevice~~',
	'Class:lnkConnectableCIToNetworkDevice+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Network device~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Network device name~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Connected device~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Connected device name~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Network port~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Device port~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Connection type~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'down link~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'up link~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link~~',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link ApplicationSolution / FunctionalCI~~',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Application solution~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Application solution name~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Functionalci~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Link ApplicationSolution / BusinessProcess~~',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Business process~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Business process name~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Application solution~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Application solution name~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '~~',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkPersonToTeam' => 'Link Person / Team~~',
	'Class:lnkPersonToTeam+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team~~',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Team name~~',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person~~',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Person name~~',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Role~~',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Role name~~',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '~~',
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
	'Class:Group/Attribute:parent_id_friendlyname' => 'Parent Group~~',
	'Class:Group/Attribute:parent_id_friendlyname+' => '~~',
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
	'Class:lnkGroupToCI/Attribute:reason' => '原因',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Application Menu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
'Menu:DataAdministration' => '数据管理',
'Menu:DataAdministration+' => '数据管理',
'Menu:Catalogs' => '目录',
'Menu:Catalogs+' => '数据类别',
'Menu:Audit' => '审计',
'Menu:Audit+' => '审计',
'Menu:CSVImport' => 'CSV 导入',
'Menu:CSVImport+' => '大批量创建或修改',
'Menu:Organization' => '组织',
'Menu:Organization+' => '所有组织',
'Menu:Application' => '应用程序',
'Menu:Application+' => '所有应用程序',
'Menu:DBServer' => '数据库服务器',
'Menu:DBServer+' => '数据库服务器',
'Menu:ConfigManagement' => '配置管理',
'Menu:ConfigManagement+' => '配置管理',
'Menu:ConfigManagementOverview' => '总览',
'Menu:ConfigManagementOverview+' => '总览',
'Menu:Contact' => '联系人',
'Menu:Contact+' => '联系人',
'Menu:Contact:Count' => '%1$d',
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
'Menu:ConfigManagement:AllDevices' => '基础架构',
'Menu:ConfigManagement:virtualization' => '虚拟化',
'Menu:ConfigManagement:EndUsers' => '终端用户设备',
'Menu:ConfigManagement:SWAndApps' => '软件和应用程序',
'Menu:ConfigManagement:Misc' => '杂项',
'Menu:Group' => 'CI族',
'Menu:Group+' => 'CI族',
'Menu:ConfigManagement:Shortcuts' => '快捷方式',
'Menu:ConfigManagement:AllContacts' => '所有联系人: %1$d',
'Menu:Typology' => 'Typology configuration~~',
'Menu:Typology+' => 'Typology configuration~~',
'Menu:OSVersion' => 'OS 版本~~',
'Menu:OSVersion+' => '~~',
'Menu:Software' => '软件清单',
'Menu:Software+' => 'Software catalog~~',
'UI_WelcomeMenu_AllConfigItems' => 'Summary~~',
'Menu:ConfigManagement:Typology' => 'Typology configuration~~',

));


// Add translation for Fieldsets

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
'Server:baseinfo' => 'General information~~',
'Server:Date' => 'Dates~~',
'Server:moreinfo' => 'More information~~',
'Server:otherinfo' => 'Other information~~',
'Server:power' => 'Power supply~~',
'Person:info' => 'General information~~',
'Person:personal_info' => 'Personal information~~',
'Person:notifiy' => 'Notification~~',
'Class:Subnet/Tab:IPUsage' => 'IP 使用情况',
'Class:Subnet/Tab:IPUsage-explain' => '接口拥有一个下述范围内的IP: <em>%1$s</em> 到 <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => '未用的 IP',
'Class:Subnet/Tab:FreeIPs-count' => '未用的 IP: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => '这里有10个抽取的未用的 IP 地址',
'Class:Document:PreviewTab' => '预览',
));

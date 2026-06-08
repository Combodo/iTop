<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Relation:impacts/Description'    => '被影响的元素',
	'Relation:impacts/DownStream'     => '影响...',
	'Relation:impacts/DownStream+'    => '被影响的元素',
	'Relation:impacts/UpStream'       => '依赖于...',
	'Relation:impacts/UpStream+'      => '此元素依赖的元素...',
	// Legacy entries
	'Relation:depends on/Description' => '此元素依赖的元素...',
	'Relation:depends on/DownStream'  => '依赖于...',
	'Relation:depends on/UpStream'    => '影响...',
	'Relation:impacts/LoadData'       => '加载数据',
	'Relation:impacts/NoFilteredData' => '请选择对象并加载数据',
	'Relation:impacts/FilteredData'   => '已筛选的数据',
]);

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

//
// Class: lnkContactToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContactToFunctionalCI' => '链接 联系人/功能配置项',
	'Class:lnkContactToFunctionalCI+' => '管理联系人与功能配置项的链接. 它可以是一个团队的职责范围, 分配工单, 或者将特定设备(如PC或电话)分配给对应的人员, 以管理资产.',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => '功能配置项',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => '功能配置项名称',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => '联系人',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => '联系人名称',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
]);

//
// Class: FunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:FunctionalCI' => '功能配置项',
	'Class:FunctionalCI+' => '用于CMDB的抽象类, 用于分组大多数配置项类型.',
	'Class:FunctionalCI/Attribute:name' => '名称',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => '描述',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => '组织',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => '组织名称',
	'Class:FunctionalCI/Attribute:organization_name+' => '通用名',
	'Class:FunctionalCI/Attribute:business_criticity' => '业务关键性',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '中',
	'Class:FunctionalCI/Attribute:move2production' => '投产日期',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:groups_list' => '分组',
	'Class:FunctionalCI/Attribute:groups_list+' => '分组可用作标记, 用于记录环境、项目 (迁移、升级、安全) 等等…',
	'Class:FunctionalCI/Attribute:contacts_list' => '联系人',
	'Class:FunctionalCI/Attribute:contacts_list+' => '此配置项的所有联系人',
	'Class:FunctionalCI/Attribute:documents_list' => '文档',
	'Class:FunctionalCI/Attribute:documents_list+' => '此配置项相关的所有文档',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => '应用方案',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '此配置项依赖的所有应用方案',
	'Class:FunctionalCI/Attribute:softwares_list' => '软件',
	'Class:FunctionalCI/Attribute:softwares_list+' => '此配置项上已安装的所有软件',
	'Class:FunctionalCI/Attribute:finalclass' => '类型',
	'Class:FunctionalCI/Attribute:finalclass+' => '根本属性的名称',
	'Class:FunctionalCI/Tab:OpenedTickets' => '活跃的工单',
	'Class:FunctionalCI/Tab:OpenedTickets+' => '影响当前功能配置项的活跃工单',
]);

//
// Class: PhysicalDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PhysicalDevice' => '物理设备',
	'Class:PhysicalDevice+' => '物理型配置项的抽象. 物理设备可以被定位. 它通常有品牌和型号.',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:PhysicalDevice/Attribute:serialnumber' => '序列号',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => '地点',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => '名称',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => '状态',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => '生效',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '生效',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:PhysicalDevice/Attribute:status/Value:production' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '生产',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => '库存',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '库存',
	'Class:PhysicalDevice/Attribute:brand_id' => '品牌',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => '品牌名称',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => '型号',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_end_of_support' => '型号过保日期',
	'Class:PhysicalDevice/Attribute:model_end_of_support+' => '当硬件型号不再被制造商支持时，如果此信息在型号上有所记录.',
	'Class:PhysicalDevice/Attribute:model_name' => '型号名称',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => '资产编号',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => '采购日期',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => '过保日期',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
]);

//
// Class: Rack
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Rack' => '机架',
	'Class:Rack+' => '一种用于安装数据中心设备的开放式框架.',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s',
	'Class:Rack/Attribute:nb_u' => '机架高度',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => '设备',
	'Class:Rack/Attribute:device_list+' => '此机架托管的所有物理设备',
	'Class:Rack/Attribute:enclosure_list' => '机柜',
	'Class:Rack/Attribute:enclosure_list+' => '此机架上的所有机柜',
]);

//
// Class: TelephonyCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:TelephonyCI' => '通讯项',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => '电话号码',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
]);

//
// Class: Phone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Phone' => '电话',
	'Class:Phone+' => '终端用户设备.带线的座机',
]);

//
// Class: MobilePhone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:MobilePhone' => '手机',
	'Class:MobilePhone+' => '终端用户设备.无线电话',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => '硬件 PIN 码',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
]);

//
// Class: IPPhone
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:IPPhone' => 'IP 电话',
	'Class:IPPhone+' => '用于电话的物理设备，连接到网络',
]);

//
// Class: Tablet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Tablet' => '平板',
	'Class:Tablet+' => '终端用户设备.例如 iPad, Galaxy Note/Tab Nexus, Kindle...',
]);

//
// Class: ConnectableCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ConnectableCI' => '可连接的配置项',
	'Class:ConnectableCI+' => '可联网的物理设备',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s',
	'Class:ConnectableCI/Attribute:networkdevice_list' => '网络设备',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '连接到这台设备的所有网络设备',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => '网卡',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '所有物理网卡',
]);

//
// Class: DatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:DatacenterDevice' => '数据中心设备',
	'Class:DatacenterDevice+' => '安装在数据中心的联网物理设备, 通常安装在机架或机柜中. 它包括物理机, 网络设备, 存储系统, SAN 交换机, 磁带库, NAS 设备, 等等.',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:DatacenterDevice/Attribute:rack_id' => '机架',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => '机架名称',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => '机柜',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => '机柜名称',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => '高度',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => '管理IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => '主电源',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => '主电源名称',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => '备电源',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => '备电源名称',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => '光口',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '此设备的所有光纤接口',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN',
	'Class:DatacenterDevice/Attribute:san_list+' => '连接到这台设备的所有SAN交换机',
	'Class:DatacenterDevice/Attribute:redundancy' => '冗余',
	'Class:DatacenterDevice/Attribute:redundancy/count' => '此设备运行正常至少需要一路电源 (主或备)',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => '所有电源正常, 此设备才正常',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => '至少%1$s %%路电源正常, 设备才正常',
]);

//
// Class: NetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:NetworkDevice' => '网络设备',
	'Class:NetworkDevice+' => '任何类型的网络设备: 路由器, 交换机, 集线器, 负载均衡器, 防火墙…',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => '网络设备类型',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => '网络设备类型名称',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => '设备',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '连接到此网络设备的所有设备',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS版本',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS版本名称',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ios_end_of_support' => 'IOS过保时间',
	'Class:NetworkDevice/Attribute:ios_end_of_support+' => '厂家不再为该IOS版本提供修复的时间.',
	'Class:NetworkDevice/Attribute:ram' => '内存',
	'Class:NetworkDevice/Attribute:ram+' => '',
]);

//
// Class: Server
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Server' => '物理机',
	'Class:Server+' => '用于管理集中式物理资源或服务的数据中心设备. 它通常安装了特定的 OS 版本并运行各类软件.',
	'Class:Server/ComplementaryName' => '%1$s - %2$s',
	'Class:Server/Attribute:osfamily_id' => 'OS 家族',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OS 家族名称',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OS 版本',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OS 版本名称',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:os_end_of_support' => 'OS 过保时间',
	'Class:Server/Attribute:os_end_of_support+' => '厂商不再为该操作系统版本提供补丁的时间.',
	'Class:Server/Attribute:oslicence_id' => 'OS 许可证',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OS 许可证名称',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => '内存',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:Server/Attribute:logicalvolumes_list+' => '连接到此服务器的所有逻辑卷',
]);

//
// Class: StorageSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:StorageSystem' => '存储系统',
	'Class:StorageSystem+' => '存储系统可以使用光纤或以太网连接. 存储系统以逻辑卷为单位进行管理.',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s',
	'Class:StorageSystem/Attribute:logicalvolume_list' => '逻辑卷',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '此存储系统包含的所有逻辑卷',
]);

//
// Class: SANSwitch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SANSwitch' => 'SAN交换机',
	'Class:SANSwitch+' => 'SAN交换机是指兼容光纤通道协议的网络交换机, 通常用于存储网络. 它是一个数据中心设备.',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => '设备',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '连接到此SAN交换机的所有设备',
]);

//
// Class: TapeLibrary
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:TapeLibrary' => '磁带库',
	'Class:TapeLibrary+' => '托管了多个磁带(含盒式磁带)的数据中心设备. 磁带库通常用于数据备份或归档.',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s',
	'Class:TapeLibrary/Attribute:tapes_list' => '磁带',
	'Class:TapeLibrary/Attribute:tapes_list+' => '此磁带库里的所有磁带',
]);

//
// Class: NAS
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '数据中心里联网的大容量存储设备. 在 '.ITOP_APPLICATION_SHORT.' 中 NAS (Network-attached storage) 用于托管 NAS 文件系统.',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s',
	'Class:NAS/Attribute:nasfilesystem_list' => '文件系统',
	'Class:NAS/Attribute:nasfilesystem_list+' => '此NAS里的所有文件系统',
]);

//
// Class: PC
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PC' => 'PC',
	'Class:PC+' => '可连接的配置项. 个人计算机 (PC) 是一种物理设备，可以是台式机或笔记本电脑，安装了操作系统并设计用于运行软件实例.',
	'Class:PC/ComplementaryName' => '%1$s - %2$s',
	'Class:PC/Attribute:osfamily_id' => 'OS 家族',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OS 家族名称',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OS 版本',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OS 版本名称',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:os_end_of_support' => 'OS 过保时间',
	'Class:PC/Attribute:os_end_of_support+' => '厂商不再为该操作系统版本提供补丁的时间.',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => '内存',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => '类型',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => '台式机',
	'Class:PC/Attribute:type/Value:desktop+' => '台式机',
	'Class:PC/Attribute:type/Value:laptop' => '笔记本',
	'Class:PC/Attribute:type/Value:laptop+' => '笔记本',
]);

//
// Class: Printer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Printer' => '打印机',
	'Class:Printer+' => '可连接的配置项. 可以联网或连接到PC的物理设备.',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: PowerConnection
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PowerConnection' => '供电线路',
	'Class:PowerConnection+' => '用于电力供应的物理设备的抽象.',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: PowerSource
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PowerSource' => '电源',
	'Class:PowerSource+' => '物理电源连接. 用于记录数据中心的任何类型的电源 (主电源入口, 断路器…) ，但不是 PDU.',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU',
	'Class:PowerSource/Attribute:pdus_list+' => '使用此电源的所有 PDU',
]);

//
// Class: PDU
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '电力供应连接. PDU (Power Distribution Unit) 是一种配备了多个输出的电力分配设备，特别是为数据中心内的服务器机架和网络设备机架供电.',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s',
	'Class:PDU/Attribute:rack_id' => '机架',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => '机架名称',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => '上级电源',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => '上级电源名称',
	'Class:PDU/Attribute:powerstart_name+' => '',
]);

//
// Class: Peripheral
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Peripheral' => '配件',
	'Class:Peripheral+' => '物理设备, 用于记录任何类型的计算机外设.
例如: 外部硬盘, 扫描仪, 输入设备 (轨迹球, 条码扫描仪), 等等…',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s',
]);

//
// Class: Enclosure
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Enclosure' => '机柜',
	'Class:Enclosure+' => '一种安装在机架内的封闭式柜子, 用于托管IT设备,如刀片服务器、网络设备...',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s',
	'Class:Enclosure/Attribute:rack_id' => '机架',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => '机架名称',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => '高度',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => '设备',
	'Class:Enclosure/Attribute:device_list+' => '此机柜的所有设备',
]);

//
// Class: ApplicationSolution
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ApplicationSolution' => '应用方案',
	'Class:ApplicationSolution+' => '应用方案描述了复杂应用是如何由多个基本组件之间组装(或依赖)的. 应用方案的主要信息是其关系列表.',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => '配置项',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '此应用方案包含的所有配置项',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => '业务流程',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '依赖此应用方案的所有业务流程',
	'Class:ApplicationSolution/Attribute:logo' => 'Logo',
	'Class:ApplicationSolution/Attribute:logo+' => '用于在显示影响分析图表时作为对象图标',
	'Class:ApplicationSolution/Attribute:status' => '状态',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => '启用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => '停用',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '停用',
	'Class:ApplicationSolution/Attribute:redundancy' => '影响分析: 冗余配置',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => '所有配置项正常, 此应用方案才正常',
	'Class:ApplicationSolution/Attribute:redundancy/count' => '至少%1$s个配置项正常时此应用方案才正常',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => '至少%1$s %%的配置项正常, 此应用方案才正常',
]);

//
// Class: BusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:BusinessProcess' => '业务流程',
	'Class:BusinessProcess+' => '业务流程用于描述运营过程中的高级流程或重要应用. 它与应用方案非常类似, 但是为了描述更高层次的应用或整个组织的流程.',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => '应用方案',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '影响此业务流程的所有应用方案',
	'Class:BusinessProcess/Attribute:logo' => 'Logo',
	'Class:BusinessProcess/Attribute:logo+' => '用于在显示影响分析图表时作为对象图标',
	'Class:BusinessProcess/Attribute:status' => '状态',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => '启用',
	'Class:BusinessProcess/Attribute:status/Value:active+' => '启用',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => '停用',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '停用',
]);

//
// Class: Software
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Software' => '软件',
	'Class:Software+' => '软件是软件清单中的一个基本项目. 它有一个特定的版本. 在 '.ITOP_APPLICATION_SHORT.' 中, 软件分为: DB服务器、中间件、PC软件、Web服务器和其它软件.',
	'Class:Software/ComplementaryName' => '%1$s - %2$s',
	'Class:Software/Attribute:name' => '名称',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => '厂商',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => '版本',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:end_of_support' => '过保日期',
	'Class:Software/Attribute:end_of_support+' => '厂家提供的最后一个支持日期，此后不再提供此软件版本的补丁.',
	'Class:Software/Attribute:documents_list' => '文档',
	'Class:Software/Attribute:documents_list+' => '此软件相关的所有文档',
  'Class:Software/Attribute:logo' => 'Logo',
	'Class:Software/Attribute:logo+' => '用于在显示影响分析图表时作为所有使用了此软件的软件实例对象的图标',
	'Class:Software/Attribute:type' => '类型',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB 服务器',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB 服务器',
	'Class:Software/Attribute:type/Value:Middleware' => '中间件',
	'Class:Software/Attribute:type/Value:Middleware+' => '中间件',
	'Class:Software/Attribute:type/Value:OtherSoftware' => '其它软件',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '其它软件',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC 软件',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC 软件',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web 服务器',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web 服务器',
	'Class:Software/Attribute:softwareinstance_list' => '软件实例',
	'Class:Software/Attribute:softwareinstance_list+' => '此软件的所有实例',
	'Class:Software/Attribute:softwarepatch_list' => '软件补丁',
	'Class:Software/Attribute:softwarepatch_list+' => '此软件的所有补丁',
	'Class:Software/Attribute:softwarelicence_list' => '软件许可证',
	'Class:Software/Attribute:softwarelicence_list+' => '此软件的所有许可证',
]);

//
// Class: SoftwareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SoftwareInstance' => '软件实例',
	'Class:SoftwareInstance+' => '在设备(物理机, PC, 虚拟机)上部署软件的抽象. 在 '.ITOP_APPLICATION_SHORT.' 中, 软件实例分为: DB服务器, 中间件, PC软件, Web服务器和其它软件.',
	'Class:SoftwareInstance/Attribute:system_id' => '系统',
	'Class:SoftwareInstance/Attribute:system_id+' => '系统可以是物理机, 虚拟机, PC, ...',
	'Class:SoftwareInstance/Attribute:system_name' => '系统名称',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => '软件',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => '软件名称',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:software_end_of_support' => '软件过保时间',
	'Class:SoftwareInstance/Attribute:software_end_of_support+' => '厂商为此软件版本提供补丁的最后日期.',
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
]);

//
// Class: Middleware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Middleware' => '中间件',
	'Class:Middleware+' => '向其它软件提供服务的软件实例 (例如: Tomcat, JBoss, Talend, Microsoft BizTalk, IBM Websphere 或 Lotus Domino), 通常安装在特定系统(PC, 物理机或虚拟机)上.',
	'Class:Middleware/Attribute:middlewareinstance_list' => '中间件实例',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '此中间件的所有实例',
]);

//
// Class: DBServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:DBServer' => 'DB 服务器',
	'Class:DBServer+' => '提供数据库服务的软件实例 (例如: MySQL 8.0, Oracle, SQL Server, DB2…), 通常安装在特定系统(PC, 物理机或虚拟机)上.',
	'Class:DBServer/Attribute:dbschema_list' => '数据库架构',
	'Class:DBServer/Attribute:dbschema_list+' => '此数据库服务器上的所有数据库架构',
]);

//
// Class: WebServer
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:WebServer' => 'Web 服务器',
	'Class:WebServer+' => '提供网页服务的软件实例 (例如: Apache 2.4, Nginx 1.29.4, IIS 7.0), 通常安装在特定系统(PC, 物理机或虚拟机)上.',
	'Class:WebServer/Attribute:webapp_list' => 'Web应用',
	'Class:WebServer/Attribute:webapp_list+' => '此web服务器上的所有web应用',
]);

//
// Class: PCSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PCSoftware' => 'PC 软件',
	'Class:PCSoftware+' => '安装在PC上的软件实例 (例如: MS Office, Adobe Photoshop 或 Filezilla).',
]);

//
// Class: OtherSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OtherSoftware' => '其它软件',
	'Class:OtherSoftware+' => '不包含在清单之内的任何类型的软件实例: PC软件, 中间件, DB服务器或 Web服务器.',
]);

//
// Class: MiddlewareInstance
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:MiddlewareInstance' => '中间件实例',
	'Class:MiddlewareInstance+' => '由中间件提供服务的功能配置项.',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s',
	'Class:MiddlewareInstance/Attribute:logo' => 'Logo',
	'Class:MiddlewareInstance/Attribute:logo+' => '用于在影响分析图表中显示为对象图标',
	'Class:MiddlewareInstance/Attribute:middleware_id' => '中间件',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => '名称',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
]);

//
// Class: DatabaseSchema
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:DatabaseSchema' => '数据库架构',
	'Class:DatabaseSchema+' => 'DB 服务器上运行的逻辑数据库实例.',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB 服务器',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => '名称',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
]);

//
// Class: WebApplication
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:WebApplication' => 'Web 应用',
	'Class:WebApplication+' => '可使用网页浏览器访问的 web 应用实例, 它运行在特定的 Web 服务器实例之上. 例如, 您正在使用的这个iTop.',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s',
	'Class:WebApplication/Attribute:webserver_id' => 'Web服务器',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => '名称',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:logo' => 'Logo',
	'Class:WebApplication/Attribute:logo+' => '用于在影响分析图表中显示为对象图标',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
]);

//
// Class: VirtualDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:VirtualDevice' => '虚拟设备',
	'Class:VirtualDevice+' => '用于服务器虚拟化的抽象类 (宿主机和虚拟机).',
	'Class:VirtualDevice/Attribute:status' => '状态',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => '生效',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '生效',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '废弃',
	'Class:VirtualDevice/Attribute:status/Value:production' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '生产',
	'Class:VirtualDevice/Attribute:status/Value:stock' => '库存',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '库存',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => '逻辑卷',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '此设备使用的所有逻辑卷',
]);

//
// Class: VirtualHost
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:VirtualHost' => '宿主机',
	'Class:VirtualHost+' => '对虚拟设备(虚拟机监视器, 集群,...)的抽象, 用于托管虚拟机.',
	'Class:VirtualHost/Attribute:virtualmachine_list' => '虚拟机',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '此宿主机托管的所有虚拟机',
]);

//
// Class: Hypervisor
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '虚拟化主机. 运行虚拟化软件(MS Hyper-V, VMWare ESX, Xen, 等.), 运行在物理服务器上并支持创建虚拟机.',
	'Class:Hypervisor/Attribute:farm_id' => '集群',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => '名称',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => '物理机',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => '名称',
	'Class:Hypervisor/Attribute:server_name+' => '',
]);

//
// Class: Farm
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Farm' => '集群',
	'Class:Farm+' => '虚拟化的主机. 集群由一组虚拟化监视器组成, 它们通过共享存储资源为托管的虚拟机提供容错能力.',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisor',
	'Class:Farm/Attribute:hypervisor_list+' => '集群由哪些 Hypervisor 组成',
	'Class:Farm/Attribute:redundancy' => '高可用性',
	'Class:Farm/Attribute:redundancy/disabled' => '所有 Hypervisor 正常, 集群才正常',
	'Class:Farm/Attribute:redundancy/count' => '至少%1$s个 Hypervisor 是正常的, 集群才是正常的',
	'Class:Farm/Attribute:redundancy/percent' => '至少%1$s %%的 Hypervisor 是正常的, 集群才正常',
]);

//
// Class: VirtualMachine
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:VirtualMachine' => '虚拟机',
	'Class:VirtualMachine+' => '与物理机类似的虚拟设备，它既可以托管在 Hypervisor 上，也可以托管在集群上.',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s',
	'Class:VirtualMachine/Attribute:virtualhost_id' => '宿主机',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => '名称',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS 家族',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => '名称',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS 版本',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => '名称',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:os_end_of_support' => 'OS 过保日期',
	'Class:VirtualMachine/Attribute:os_end_of_support+' => '厂商不再支持该操作系统版本时的日期.',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS 许可证',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => '名称',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => '内存',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => '管理 IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => '网卡',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '所有逻辑网卡',
]);

//
// Class: LogicalVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:LogicalVolume' => '逻辑卷',
	'Class:LogicalVolume+' => '存储系统里的基本存储单元. 它可以被多个服务器和虚拟设备访问.',
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
	'Class:LogicalVolume/Attribute:servers_list+' => '使用此逻辑卷的服务器',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => '虚拟设备',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '使用此逻辑卷的所有虚拟设备',
]);

//
// Class: lnkServerToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkServerToVolume' => '链接 服务器/逻辑卷',
	'Class:lnkServerToVolume+' => '这是多对多的关系, 某台服务器使用了一个存储卷(存储系统中的一个存储单元). 其它服务器也可以使用相同的逻辑卷.',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkVirtualDeviceToVolume' => '链接 虚拟设备/逻辑卷',
	'Class:lnkVirtualDeviceToVolume+' => '这是多对多的关系, 某个虚拟设备使用了一个逻辑卷(存储系统中的一个存储单元). 其它虚拟设备也可以使用相同的逻辑卷.',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkSanToDatacenterDevice' => '链接 SAN交换机/数据中心设备',
	'Class:lnkSanToDatacenterDevice+' => '这是多对多的关系, SAN交换机与数据中心设备(服务器、网络设备等)之间存在的网络连接.',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN 交换机',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => '名称',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => '设备',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => '名称',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN 光口',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => '设备光口',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
]);

//
// Class: Tape
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Tape' => '磁带',
	'Class:Tape+' => '磁带(或盒式磁带) 在 '.ITOP_APPLICATION_SHORT.' 中是磁带库的一部分，可移除的存储介质.',
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
]);

//
// Class: NASFileSystem
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:NASFileSystem' => 'NAS 文件系统',
	'Class:NASFileSystem+' => '表示托管在特定 NAS (Network Attached Storage) 内的共享文件系统.',
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
]);

//
// Class: Patch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Patch' => '补丁',
	'Class:Patch+' => '抽象类，用于对系统或软件提供的补丁、热修复、安全修复或软件服务包.',
	'Class:Patch/Attribute:name' => '名称',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => '文档',
	'Class:Patch/Attribute:documents_list+' => '此补丁相关的所有文档',
	'Class:Patch/Attribute:description' => '描述',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => '补丁类型',
	'Class:Patch/Attribute:finalclass+' => '根本属性的名称',
]);

//
// Class: OSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OSPatch' => 'OS 补丁',
	'Class:OSPatch+' => '针对特定操作系统的补丁、热修复、安全修复或服务包.',
	'Class:OSPatch/Attribute:functionalcis_list' => '设备',
	'Class:OSPatch/Attribute:functionalcis_list+' => '已安装此补丁的所有系统',
	'Class:OSPatch/Attribute:osversion_id' => 'OS 版本',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osfamily_id' => 'OS 家族',
	'Class:OSPatch/Attribute:osfamily_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => '名称',
	'Class:OSPatch/Attribute:osversion_name+' => '',
]);

//
// Class: SoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SoftwarePatch' => '软件补丁',
	'Class:SoftwarePatch+' => '针对特定软件的补丁、热修复、安全修复或服务包.',
	'Class:SoftwarePatch/Attribute:software_id' => '软件',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => '名称',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => '软件实例',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '已安装此软件补丁的所有系统',
]);

//
// Class: Licence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Licence' => '许可证',
	'Class:Licence+' => '抽象类.针对特定操作系统版本或软件版本的许可证合同',
	'Class:Licence/Attribute:name' => '名称',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => '文档',
	'Class:Licence/Attribute:documents_list+' => '此许可证相关的所有文档',
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
	'Class:Licence/Attribute:finalclass' => '许可证类型',
	'Class:Licence/Attribute:finalclass+' => '根本属性的名称',
]);

//
// Class: OSLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OSLicence' => 'OS 许可证',
	'Class:OSLicence+' => '针对特定操作系统的许可证合同. 该许可证与操作系统相关联 (例如 Windows 2008 R2) 并可以与多个物理机或虚拟机关联.',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:OSLicence/Attribute:osversion_id' => 'OS版本',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osfamily_id' => 'OS家族',
	'Class:OSLicence/Attribute:osfamily_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => '名称',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => '虚拟机',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '使用此许可证的所有虚拟机',
	'Class:OSLicence/Attribute:servers_list' => '服务器',
	'Class:OSLicence/Attribute:servers_list+' => '使用此许可证的所有服务器',
]);

//
// Class: SoftwareLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SoftwareLicence' => '软件许可证',
	'Class:SoftwareLicence+' => '针对特定软件的许可证合同. 该许可证与某个软件相关联 (例如 MS Office 2010) 并可以与该软件的多个实例关联.',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:SoftwareLicence/Attribute:software_id' => '软件',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => '名称',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => '软件实例',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '使用此许可证的所有系统',
]);

//
// Class: lnkDocumentToLicence
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDocumentToLicence' => '链接 文档/许可证',
	'Class:lnkDocumentToLicence+' => 'Link used when a Document is applicable to a License.~~',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => '许可证',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => '名称',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => '文档',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
]);

//
// Class: OSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OSVersion' => 'OS 版本',
	'Class:OSVersion+' => '分类. 计算机 (物理机, 虚拟机 或 PC) 的 "OS 版本" 对应的可能的值. OS 版本以 OS 家族为单位进行组织.',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS 家族',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => '名称',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
	'Class:OSVersion/Attribute:end_of_support' => '过保日期',
	'Class:OSVersion/Attribute:end_of_support+' => 'The date after which the editor ceases to provide patches for this OS version.~~',
	'Class:OSVersion/Attribute:ospatches_list' => 'OS 补丁',
	'Class:OSVersion/Attribute:ospatches_list+' => 'All the OS patches for this OS version~~',
	'Class:OSVersion/UniquenessRule:name_osfamily+' => 'OS 家族的名称必须唯一',
	'Class:OSVersion/UniquenessRule:name_osfamily' => '此 OS 版本已在 OS 家族中存在',
]);

//
// Class: OSFamily
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OSFamily' => 'OS 家族',
	'Class:OSFamily+' => '分类. 物理机、虚拟机、PC 的 "OS 家族" 属性可能的值列表.',
	'Class:OSFamily/Attribute:osversions_list' => 'OS 版本',
	'Class:OSFamily/Attribute:osversions_list+' => '此 OS 家族的所有 OS 版本',
	'Class:OSFamily/UniquenessRule:name+' => '名称必须唯一',
	'Class:OSFamily/UniquenessRule:name' => '此 OS 家族已存在',
]);

//
// Class: Brand
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Brand' => '品牌',
	'Class:Brand+' => '分类. 物理设备 "品牌" 的可能的值列表.',
	'Class:Brand/Attribute:iosversions_list' => 'IOS 版本',
	'Class:Brand/Attribute:iosversions_list+' => '此品牌的所有 IOS 版本',
	'Class:Brand/Attribute:logo' => 'Logo',
	'Class:Brand/Attribute:logo+' => '',
	'Class:Brand/Attribute:models_list' => '型号',
	'Class:Brand/Attribute:models_list+' => '此品牌的所有型号',
	'Class:Brand/Attribute:physicaldevices_list' => '物理设备',
	'Class:Brand/Attribute:physicaldevices_list+' => '此品牌的所有物理设备',
	'Class:Brand/UniquenessRule:name+' => '名称必须唯一',
	'Class:Brand/UniquenessRule:name' => '此品牌已存在',
]);

//
// Class: Model
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Model' => '设备型号',
	'Class:Model+' => '分类. 物理设备型号属性可能的值列表. 每个型号只属于一个品牌，通常适用于一种单一类别的物理设备.',
	'Class:Model/ComplementaryName' => '%1$s - %2$s',
	'Class:Model/Attribute:brand_id' => '品牌',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => '品牌名称',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:picture' => '相片',
	'Class:Model/Attribute:picture+' => '',
	'Class:Model/Attribute:type' => '设备类型',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:end_of_support' => '过保日期',
	'Class:Model/Attribute:end_of_support+' => '厂商提供补丁和支持的最后时间.',
	'Class:Model/Attribute:type/Value:PowerSource' => '电源',
	'Class:Model/Attribute:type/Value:PowerSource+' => '电源',
	'Class:Model/Attribute:type/Value:DiskArray' => '磁盘阵列',
	'Class:Model/Attribute:type/Value:DiskArray+' => '磁盘阵列',
	'Class:Model/Attribute:type/Value:Enclosure' => '机柜',
	'Class:Model/Attribute:type/Value:Enclosure+' => '机柜',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP 电话',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP 电话',
	'Class:Model/Attribute:type/Value:MobilePhone' => '手机',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '手机',
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
	'Class:Model/Attribute:type/Value:Rack' => '机架',
	'Class:Model/Attribute:type/Value:Rack+' => '机架',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN 交换机',
	'Class:Model/Attribute:type/Value:SANSwitch+' => '光纤交换机',
	'Class:Model/Attribute:type/Value:Server' => '物理机',
	'Class:Model/Attribute:type/Value:Server+' => '物理机',
	'Class:Model/Attribute:type/Value:StorageSystem' => '存储系统',
	'Class:Model/Attribute:type/Value:StorageSystem+' => '存储系统',
	'Class:Model/Attribute:type/Value:Tablet' => '平板',
	'Class:Model/Attribute:type/Value:Tablet+' => '平板',
	'Class:Model/Attribute:type/Value:TapeLibrary' => '磁带库',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => '磁带库',
	'Class:Model/Attribute:type/Value:Phone' => '电话',
	'Class:Model/Attribute:type/Value:Phone+' => '电话',
	'Class:Model/Attribute:physicaldevices_list' => '物理设备',
	'Class:Model/Attribute:physicaldevices_list+' => '此型号的所有物理设备',
	'Class:Model/UniquenessRule:name_brand+' => '名称必须唯一',
	'Class:Model/UniquenessRule:name_brand' => '此型号已存在',
]);

//
// Class: NetworkDeviceType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:NetworkDeviceType' => '网络设备类型',
	'Class:NetworkDeviceType+' => '分类. 网络设备 "类型" 的可能的值 (例如：路由器、交换机、防火墙等).',
	'Class:NetworkDeviceType/Attribute:logo' => 'Logo',
	'Class:NetworkDeviceType/Attribute:logo+' => '用于此类型网络设备的图标，当在控制台中显示时 (详情、摘要卡片和影响分析图表)',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => '网络设备',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '此类型的所有网络设备',
]);

//
// Class: IOSVersion
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:IOSVersion' => 'IOS 版本',
	'Class:IOSVersion+' => '分类. 网络设备操作系统的版本可能的值 (IOS 来自 Cisco 的 Internetwork Operating System).',
	'Class:IOSVersion/Attribute:brand_id' => '品牌',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => '名称',
	'Class:IOSVersion/Attribute:brand_name+' => '',
	'Class:IOSVersion/Attribute:end_of_support' => '过保日期',
	'Class:IOSVersion/Attribute:end_of_support+' => '厂商提供补丁的最后时间.',
	'Class:IOSVersion/Attribute:networkdevices_list' => '网络设备',
	'Class:IOSVersion/Attribute:networkdevices_list+' => '运行此 IOS 版本的所有网络设备',
	'Class:IOSVersion/UniquenessRule:name_brand+' => '名称在品牌中必须唯一',
	'Class:IOSVersion/UniquenessRule:name_brand' => '此 IOS 版本已存在于此品牌',
]);

//
// Class: lnkDocumentToPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDocumentToPatch' => '链接 文档/补丁',
	'Class:lnkDocumentToPatch+' => 'Link used when a Document is applicable to a Patch.~~',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => '补丁',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => '补丁名称',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => '文档',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
]);

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkSoftwareInstanceToSoftwarePatch' => '链接 软件实例/软件补丁',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => 'This link indicates that a software patch has been applied to a software instance.~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => '软件补丁',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => '软件补丁名称',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => '软件实例',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => '软件实例名称',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
]);

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkFunctionalCIToOSPatch' => '链接 功能配置项/OS 补丁',
	'Class:lnkFunctionalCIToOSPatch+' => 'Models the deployment of an OS Patch on a device.~~',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS 补丁',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS 补丁名称',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => '功能配置项',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => '功能配置项名称',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
]);

//
// Class: lnkDocumentToSoftware
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDocumentToSoftware' => '链接 文档/软件',
	'Class:lnkDocumentToSoftware+' => 'Link used when a Document is applicable to Software.~~',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => '软件',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => '软件名称',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => '文档',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
]);

//
// Class: Subnet
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Subnet' => '子网',
	'Class:Subnet+' => 'IP 网段, 由 IP 地址和掩码定义',
	'Class:Subnet/Name' => '%1$s/%2$s',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s',
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
]);

//
// Class: VLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => 'VLAN 即虚拟局域网，用于以逻辑方式对局域网内的网络、子网和物理接口进行分组.',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN 标签',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => '描述',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => '组织',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => '组织名称',
	'Class:VLAN/Attribute:org_name+' => '',
	'Class:VLAN/Attribute:subnets_list' => '子网',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => '物理网卡',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
]);

//
// Class: lnkSubnetToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkSubnetToVLAN' => '链接 子网/VLAN',
	'Class:lnkSubnetToVLAN+' => '这是多对多的关系, 某个 VLAN 包含一个子网. 其它 VLAN 也可以包含相同的子网. 并且一个 VLAN 可以跨越多个子网.',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => '子网',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => '子网IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => '子网名称',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN 标签',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
]);

//
// Class: NetworkInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:NetworkInterface' => '网卡',
	'Class:NetworkInterface+' => '对所有网络接口的抽象.',
	'Class:NetworkInterface/Attribute:name' => '名称',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => '网卡类型',
	'Class:NetworkInterface/Attribute:finalclass+' => '根本属性的名称',
]);

//
// Class: IPInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:IPInterface' => 'IP 接口',
	'Class:IPInterface+' => '抽象类. 一种具有 IP 地址的网络接口类型',
	'Class:IPInterface/Attribute:ipaddress' => 'IP 地址',
	'Class:IPInterface/Attribute:ipaddress+' => '',

	'Class:IPInterface/Attribute:macaddress' => 'MAC地址',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => '备注',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => '网关',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => '掩码',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => '速率',
	'Class:IPInterface/Attribute:speed+' => '',
]);

//
// Class: PhysicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:PhysicalInterface' => '物理网卡',
	'Class:PhysicalInterface+' => '基于物理网络接口的IP接口类型 (例如，以太网卡).',
	'Class:PhysicalInterface/Name' => '%2$s %1$s',
	'Class:PhysicalInterface/Attribute:connectableci_id' => '设备',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => '设备名称',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:org_id' => '组织',
	'Class:PhysicalInterface/Attribute:org_id+' => '',
	'Class:PhysicalInterface/Attribute:location_id' => '位置',
	'Class:PhysicalInterface/Attribute:location_id+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
]);

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkPhysicalInterfaceToVLAN' => '链接 物理网卡/VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => 'This link indicates when a network interface is part of a VLAN (虚拟局域网).~~',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s',
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
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN 标签',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
]);

//
// Class: LogicalInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:LogicalInterface' => '逻辑网卡',
	'Class:LogicalInterface+' => '不与物理网卡直接对应的IP接口,其关联是动态的,通常用于虚拟机.',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => '虚拟机',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => '虚拟机名称',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
]);

//
// Class: FiberChannelInterface
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:FiberChannelInterface' => '光口',
	'Class:FiberChannelInterface+' => '主要用于存储系统的一种高速网络接口.',
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
]);

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkConnectableCIToNetworkDevice' => '链接 可连接项/网络设备',
	'Class:lnkConnectableCIToNetworkDevice+' => 'Defines on which network equipment a device is connected.~~',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkApplicationSolutionToFunctionalCI' => '链接 应用方案/功能配置项',
	'Class:lnkApplicationSolutionToFunctionalCI+' => 'Models the membership of a device to an Application Solution. The meaning of this relationship varies depending on the types of Application Solution.~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => '应用方案',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => '应用方案名称',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => '功能配置项',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => '功能配置项名称',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
]);

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkApplicationSolutionToBusinessProcess' => '链接 应用方案/业务流程',
	'Class:lnkApplicationSolutionToBusinessProcess+' => 'Models the relationship between an Application Solution and a Business Process.~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => '业务流程',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => '业务流程名称',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => '应用方案',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => '应用方案名称',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
]);

//
// Class: Group
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Group' => '配置组',
	'Class:Group+' => '配置组旨在为任何项目定义明确的配置项集合. 与应用方案不同，配置组不会受到其组件的影响，也不会影响它的组件. 例如，在进行操作系统迁移时，配置组可以方便地收集"待迁移的服务器". 随着迁移的进行，迁移完成的服务器将从配置组中移除.',
	'Class:Group/ComplementaryName' => '%1$s - %2$s',
	'Class:Group/Attribute:name' => '名称',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => '状态',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => '生效',
	'Class:Group/Attribute:status/Value:implementation+' => '生效',
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
	'Class:Group/Attribute:ci_list' => '关联的配置项',
	'Class:Group/Attribute:ci_list+' => '此配置组相关的所有配置项',
	'Class:Group/Attribute:parent_id_friendlyname' => '上级配置组',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
]);

//
// Class: lnkGroupToCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkGroupToCI' => '链接 配置组/配置项',
	'Class:lnkGroupToCI+' => 'This link indicates when a Functional CI is part of a Group.~~',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDocumentToFunctionalCI' => '链接 文档/功能配置项',
	'Class:lnkDocumentToFunctionalCI+' => 'Link used when a Document is applicable to a Functional CI.~~',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => '功能配置项',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => '功能配置项名称',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => '文档',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
]);

// Add translation for Fieldsets

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'ConfigMgmt:baseinfo' => '概况',
	'ConfigMgmt:moreinfo' => '配置项详情',
	'ConfigMgmt:otherinfo' => '描述',
	'ConfigMgmt:dates' => '日期',
	'Storage:moreinfo' => '存储详情',
	'Software:moreinfo' => '软件详情',
	'Phone:moreinfo' => '电话详情',
	'Server:baseinfo' => '基本信息',
	'Server:moreinfo' => '设备详情',
	'Server:Date' => '日期',
	'Server:otherinfo' => '描述',
	'Server:power' => '电力供应',
	'Class:Subnet/Tab:IPUsage' => 'IP 使用率',
	'Class:Subnet/Tab:IPUsage+' => '子网中哪些 IP 在使用或可用~',
	'Class:Subnet/Tab:IPUsage-explain' => '网卡 IP 范围: <em>%1$s</em>到<em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => '空闲 IP',
	'Class:Subnet/Tab:FreeIPs-count' => '空闲 IP: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => '以下是抽取的10个空闲 IP',
	'Class:Document:PreviewTab' => '预览',
]);

//
// Application Menu
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Menu:Application' => '应用',
	'Menu:Application+' => '所有应用',
	'Menu:DBServer' => '数据库服务器',
	'Menu:DBServer+' => '数据库服务器',
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
	'Menu:MobilePhone' => '手机',
	'Menu:MobilePhone+' => '所有手机',
	'Menu:PC' => '个人电脑',
	'Menu:PC+' => '所有个人电脑',
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
	'Menu:OSVersion' => 'OS 版本',
	'Menu:OSVersion+' => '',
	'Menu:Software' => '软件清单',
	'Menu:Software+' => '软件清单',
]);

<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Relation:impacts/Description' => 'インパクトを受ける要素',
	'Relation:impacts/DownStream' => 'インパクト...',
	'Relation:impacts/DownStream+' => 'インパクトを受ける要素',
	'Relation:impacts/UpStream' => '依存...',
	'Relation:impacts/UpStream+' => 'この要素が依存している要素',
	// Legacy entries
	'Relation:depends on/Description' => 'この要素が依存している要素',
	'Relation:depends on/DownStream' => '依存...',
	'Relation:depends on/UpStream' => 'インパクト...',
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
// Class: lnkContactToFunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkContactToFunctionalCI' => 'リンク 連絡先/機能的CI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => '機能的ci',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => '機能的ci名',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => '連絡先',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => '連絡先名',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:FunctionalCI' => '機能的CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => '名前',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => '説明',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => '組織',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => '組織名',
	'Class:FunctionalCI/Attribute:organization_name+' => '共通名',
	'Class:FunctionalCI/Attribute:business_criticity' => 'ビジネス上の重要性',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => '高',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => '低',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => '中',
	'Class:FunctionalCI/Attribute:move2production' => '本稼働開始日',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => '連絡先',
	'Class:FunctionalCI/Attribute:contacts_list+' => '',
	'Class:FunctionalCI/Attribute:documents_list' => '文書',
	'Class:FunctionalCI/Attribute:documents_list+' => '',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'アプリケーションソリューション',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => '',
	'Class:FunctionalCI/Attribute:softwares_list' => 'ソフトウエア',
	'Class:FunctionalCI/Attribute:softwares_list+' => '',
	'Class:FunctionalCI/Attribute:finalclass' => 'CIタイプ',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Active Tickets~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PhysicalDevice' => '物理的デバイス',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'シリアル番号',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => '場所',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => '場所名',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => '状態',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => '実装',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => '実装',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => '廃止',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => '廃止',
	'Class:PhysicalDevice/Attribute:status/Value:production' => '稼働',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => '稼働',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => '保存',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => '保存',
	'Class:PhysicalDevice/Attribute:brand_id' => 'ブランド',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'ブランド名',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'モデル',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'モデル名',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => '資産番号',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => '購入日',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => '保障終了日',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Rack' => 'ラック',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'ユニット数',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'デバイス',
	'Class:Rack/Attribute:device_list+' => '',
	'Class:Rack/Attribute:enclosure_list' => 'エンクロージャ',
	'Class:Rack/Attribute:enclosure_list+' => '',
));

//
// Class: TelephonyCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TelephonyCI' => '電話 CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => '電話番号',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Phone' => '電話',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:MobilePhone' => '携帯電話',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'ハードウエアPIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:IPPhone' => 'IP電話',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Tablet' => 'タブレット',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ConnectableCI' => '接続可能なCI',
	'Class:ConnectableCI+' => '物理的なCI',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'ネットワークデバイス',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => '',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'ネットワークインターフェース',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => '',
));

//
// Class: DatacenterDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DatacenterDevice' => 'データセンターデバイス',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'ラック',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'ラック名',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'エンクロージャ',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'エンクロージャ名',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'ユニット数',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => '管理ip',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => '電源A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => '電源A名',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => '電源B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => '電源B名',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FCポート',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => '',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN',
	'Class:DatacenterDevice/Attribute:san_list+' => '',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundancy~~',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'The device is up if at least one power connection (A or B) is up~~',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'The device is up if all its power connections are up~~',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'The device is up if at least %1$s %% of its power connections are up~~',
));

//
// Class: NetworkDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:NetworkDevice' => 'ネットワークデバイス',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'ネットワークタイプ',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'ネットワークタイプ名',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'デバイス',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => '',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOSバージョン',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOSバージョン名',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Server' => 'サーバ',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'OSファミリ',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OSファミリ名',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OSバージョン',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OSバージョン名',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'OSライセンス',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OSライセンス名',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => '論理ボリューム',
	'Class:Server/Attribute:logicalvolumes_list+' => '',
));

//
// Class: StorageSystem
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:StorageSystem' => 'ストレージシステム',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => '論理ボリューム',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => '',
));

//
// Class: SANSwitch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:SANSwitch' => 'SANスイッチ',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'デバイス',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => '',
));

//
// Class: TapeLibrary
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TapeLibrary' => 'テープライブラリ',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'テープ',
	'Class:TapeLibrary/Attribute:tapes_list+' => '',
));

//
// Class: NAS
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'ファイルシステム',
	'Class:NAS/Attribute:nasfilesystem_list+' => '',
));

//
// Class: PC
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'OSファミリ',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OSファミリ名',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OSバージョン',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OSバージョン名',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'タイプ',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'デスクトップ',
	'Class:PC/Attribute:type/Value:desktop+' => 'デスクトップ',
	'Class:PC/Attribute:type/Value:laptop' => 'ラップトップ',
	'Class:PC/Attribute:type/Value:laptop+' => 'ラップトップ',
));

//
// Class: Printer
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Printer' => 'プリンター',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PowerConnection' => '電源接続',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PowerSource' => '電源',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU',
	'Class:PowerSource/Attribute:pdus_list+' => '',
));

//
// Class: PDU
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'ラック',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'ラック名',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'パワースタート',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'パワースタート名',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Peripheral' => '周辺',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Enclosure' => 'エンクロージャ',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'ラック',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'ラック名',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'ユニット数',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'デバイス',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ApplicationSolution' => 'アプリケーションソリューション',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CI',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => '',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'ビジネスプロセス',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => '',
	'Class:ApplicationSolution/Attribute:status' => '状態',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'アクティブ',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'アクティブ',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => '非アクティブ',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Impact analysis: configuration of the redundancy~~',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up~~',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up~~',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up~~',
));

//
// Class: BusinessProcess
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:BusinessProcess' => 'ビジネスプロセス',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'アプリケーションソリューション',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => '',
	'Class:BusinessProcess/Attribute:status' => '状態',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'アクティブ',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'アクティブ',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => '非アクティブ',
));

//
// Class: SoftwareInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:SoftwareInstance' => 'ソフトウエアインスタンス',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'システム',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'システム名',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'ソフトウエア',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'ソフトウエア名',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'ソフトウエアライセンス',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'ソフトウエアライセンス名',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'パス(Path)',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => '状態',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'アクティブ',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'アクティブ',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => '非アクティブ',
));

//
// Class: Middleware
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Middleware' => 'ミドルウエア',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'ミドルウエアインスタンス',
	'Class:Middleware/Attribute:middlewareinstance_list+' => '',
));

//
// Class: DBServer
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DBServer' => 'DBサーバ',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DBスキーマ',
	'Class:DBServer/Attribute:dbschema_list+' => '',
));

//
// Class: WebServer
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:WebServer' => 'Webサーバ',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Webアプリケーション',
	'Class:WebServer/Attribute:webapp_list+' => '',
));

//
// Class: PCSoftware
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PCSoftware' => 'PCソフトウエア',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:OtherSoftware' => '他のソフトウエア',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:MiddlewareInstance' => 'ミドルウエアインスタンス',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'ミドルウエア',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'ミドルウエア名',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DatabaseSchema' => 'DBスキーマ',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DBサーバ',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DBサーバ名',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:WebApplication' => 'Webアプリケーション',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Webサーバ',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Webサーバ名',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:VirtualDevice' => 'バーチャルデバイス',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => '状態',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => '実装',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => '実装',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => '廃止',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => '廃止',
	'Class:VirtualDevice/Attribute:status/Value:production' => '稼働',
	'Class:VirtualDevice/Attribute:status/Value:production+' => '稼働',
	'Class:VirtualDevice/Attribute:status/Value:stock' => '保存',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => '保存',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => '論理ボリューム',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => '',
));

//
// Class: VirtualHost
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:VirtualHost' => '仮想ホスト',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => '仮想マシン',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => '',
));

//
// Class: Hypervisor
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Hypervisor' => 'ハイパーバイザー',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'ファーム',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'ファーム名',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'サーバ',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'サーバ名',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Farm' => 'ファーム',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'ハイパーバイザー',
	'Class:Farm/Attribute:hypervisor_list+' => '',
	'Class:Farm/Attribute:redundancy' => 'High availability~~',
	'Class:Farm/Attribute:redundancy/disabled' => 'The farm is up if all the hypervisors are up~~',
	'Class:Farm/Attribute:redundancy/count' => 'The farm is up if at least %1$s hypervisor(s) is(are) up~~',
	'Class:Farm/Attribute:redundancy/percent' => 'The farm is up if at least %1$s %% of the hypervisors are up~~',
));

//
// Class: VirtualMachine
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:VirtualMachine' => '仮想マシン',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => '仮想ホスト',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => '仮想ホスト名',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OSファミリ',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OSファミリ名',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OSバージョン',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OSバージョン名',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OSライセンス',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OSライセンス名',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => '管理ip',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'ネットワークインターフェース',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => '',
));

//
// Class: LogicalVolume
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:LogicalVolume' => '論理ボリューム',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => '名前',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => '説明',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raidレベル',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'サイズ',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'ストレージシステム',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'ストレージシステム名',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'サーバ',
	'Class:LogicalVolume/Attribute:servers_list+' => '',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => '仮想デバイス',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => '',
));

//
// Class: lnkServerToVolume
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkServerToVolume' => 'リンクサーバ/ボリューム',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'ボリューム',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'ボリューム名',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'サーバ',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'サーバ名',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => '使用サイズ',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkVirtualDeviceToVolume' => 'リンク 仮想デバイス/ボリューム',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'ボリューム',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'ボリューム名',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => '仮想デバイス',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => '仮想デバイス名',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => '使用サイズ',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkSanToDatacenterDevice' => 'リンク San/データセンターデバイス',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SANスイッチ',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SANスイッチ名',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'デバイス',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'デバイス名',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN fc',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'デバイスfc',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Tape' => 'テープ',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => '名前',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => '説明',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'サイズ',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'テープライブラリ',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'テープライブラリ名',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:NASFileSystem' => 'NASファイルシステム',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => '名前',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => '説明',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raidレベル',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'サイズ',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS名',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Software' => 'ソフトウエア',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => '名前',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'ベンダー',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'バージョン',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => '文書',
	'Class:Software/Attribute:documents_list+' => '',
	'Class:Software/Attribute:type' => 'タイプ',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DBサーバ',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DBサーバ',
	'Class:Software/Attribute:type/Value:Middleware' => 'ミドルウエア',
	'Class:Software/Attribute:type/Value:Middleware+' => 'ミドルウエア',
	'Class:Software/Attribute:type/Value:OtherSoftware' => '他のソフトウエア',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => '他のソフトウエア',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PCソフトウエア',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PCソフトウエア',
	'Class:Software/Attribute:type/Value:WebServer' => 'Webサーバ',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Webサーバ',
	'Class:Software/Attribute:softwareinstance_list' => 'ソフトウエアインスタンス',
	'Class:Software/Attribute:softwareinstance_list+' => '',
	'Class:Software/Attribute:softwarepatch_list' => 'ソフトウエアパッチ',
	'Class:Software/Attribute:softwarepatch_list+' => '',
	'Class:Software/Attribute:softwarelicence_list' => 'ソフトウエアライセンス',
	'Class:Software/Attribute:softwarelicence_list+' => '',
));

//
// Class: Patch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Patch' => 'パッチ',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => '名前',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => '文書',
	'Class:Patch/Attribute:documents_list+' => '',
	'Class:Patch/Attribute:description' => '説明',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'タイプ',
	'Class:Patch/Attribute:finalclass+' => '',
));

//
// Class: OSPatch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:OSPatch' => 'OSパッチ',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'デバイス',
	'Class:OSPatch/Attribute:functionalcis_list+' => '',
	'Class:OSPatch/Attribute:osversion_id' => 'OSバージョン',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'OSバージョン名',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:SoftwarePatch' => 'ソフトウエアパッチ',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'ソフトウエア',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'ソフトウエア名',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'ソフトウエアインスタンス',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => '',
));

//
// Class: Licence
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Licence' => 'ライセンス',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => '名前',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => '文書',
	'Class:Licence/Attribute:documents_list+' => '',
	'Class:Licence/Attribute:org_id' => '組織',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => '組織名',
	'Class:Licence/Attribute:organization_name+' => '共通名',
	'Class:Licence/Attribute:usage_limit' => '使用制限',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => '説明',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => '開始日',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => '終了日',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'キー',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => '永続的',
	'Class:Licence/Attribute:perpetual+' => '~~',
	'Class:Licence/Attribute:perpetual/Value:no' => 'いいえ',
	'Class:Licence/Attribute:perpetual/Value:no+' => '',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'はい',
	'Class:Licence/Attribute:perpetual/Value:yes+' => '',
	'Class:Licence/Attribute:finalclass' => 'タイプ',
	'Class:Licence/Attribute:finalclass+' => '',
));

//
// Class: OSLicence
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:OSLicence' => 'OSライセンス',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'OSバージョン',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'OSバージョン名',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => '仮想マシン',
	'Class:OSLicence/Attribute:virtualmachines_list+' => '',
	'Class:OSLicence/Attribute:servers_list' => 'サーバ',
	'Class:OSLicence/Attribute:servers_list+' => '',
));

//
// Class: SoftwareLicence
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:SoftwareLicence' => 'ソフトウエアライセンス',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'ソフトウエア',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'ソフトウエア名',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'ソフトウエアインスタンス',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => '',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkDocumentToLicence' => 'リンク 文書/ライセンス',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'ライセンス',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'ライセンス名',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => '文書',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: OSVersion
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:OSVersion' => 'OSバージョン',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'OSファミリ',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'OSファミリ名',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:OSFamily' => 'OSファミリ',
	'Class:OSFamily+' => '',
));

//
// Class: Brand
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Brand' => 'ブランド',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => '物理デバイス',
	'Class:Brand/Attribute:physicaldevices_list+' => '',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique~~',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists~~',
));

//
// Class: Model
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Model' => 'モデル',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'ブランド',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'ブランド名',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'デバイスタイプ',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => '電源',
	'Class:Model/Attribute:type/Value:PowerSource+' => '電源',
	'Class:Model/Attribute:type/Value:DiskArray' => 'ディスクアレー',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'ディスクアレー',
	'Class:Model/Attribute:type/Value:Enclosure' => 'エンクロージャ',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'エンクロージャ',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Ip電話',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'Ip電話',
	'Class:Model/Attribute:type/Value:MobilePhone' => '携帯電話',
	'Class:Model/Attribute:type/Value:MobilePhone+' => '携帯電話',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'ネットワークデバイス',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'ネットワークデバイス',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => '周辺',
	'Class:Model/Attribute:type/Value:Peripheral+' => '周辺',
	'Class:Model/Attribute:type/Value:Printer' => 'プリンタ',
	'Class:Model/Attribute:type/Value:Printer+' => 'プリンタ',
	'Class:Model/Attribute:type/Value:Rack' => 'ラック',
	'Class:Model/Attribute:type/Value:Rack+' => 'ラック',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SANスイッチ',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SANスイッチ',
	'Class:Model/Attribute:type/Value:Server' => 'サーバ',
	'Class:Model/Attribute:type/Value:Server+' => 'サーバ',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'ストレージシステム',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'ストレージシステム',
	'Class:Model/Attribute:type/Value:Tablet' => 'タブレット',
	'Class:Model/Attribute:type/Value:Tablet+' => 'タブレット',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'テープライブラリ',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'テープライブラリ',
	'Class:Model/Attribute:type/Value:Phone' => '電話',
	'Class:Model/Attribute:type/Value:Phone+' => '電話',
	'Class:Model/Attribute:physicaldevices_list' => '物理デバイス',
	'Class:Model/Attribute:physicaldevices_list+' => '',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand~~',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand~~',
));

//
// Class: NetworkDeviceType
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:NetworkDeviceType' => 'ネットワークデバイスタイプ',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'ネットワークデバイス',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => '',
));

//
// Class: IOSVersion
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:IOSVersion' => 'IOSバージョン',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'ブランド',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'ブランド名',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkDocumentToPatch' => 'リンク 文書/パッチ',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'パッチ',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'パッチ名',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => '文書',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'リンク ソフトウエアインスタンス/ソフトウエアパッチ',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'ソフトウエアパッチ',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'ソフトウエアパッチ名',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'ソフトウエアインスタンス',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'ソフトウエアインスタンス名',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkFunctionalCIToOSPatch' => 'リンク 機能的CI/OSパッチ',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OSパッチ',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OSパッチ名',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => '機能的ci',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => '機能的ci名',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkDocumentToSoftware' => 'リンク 文書/ソフトウエア',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'ソフトウエア',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'ソフトウエア名',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => '文書',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Subnet' => 'サブネット',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => '説明',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnet name~~',
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => 'オーナー組織',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => '名前',
	'Class:Subnet/Attribute:org_name+' => 'Common name',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'マスク',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs~~',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:VLAN' => 'VLAN~~',
	'Class:VLAN+' => '~~',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag~~',
	'Class:VLAN/Attribute:vlan_tag+' => '~~',
	'Class:VLAN/Attribute:description' => 'Description~~',
	'Class:VLAN/Attribute:description+' => '~~',
	'Class:VLAN/Attribute:org_id' => 'Organization~~',
	'Class:VLAN/Attribute:org_id+' => '~~',
	'Class:VLAN/Attribute:org_name' => 'Organization name~~',
	'Class:VLAN/Attribute:org_name+' => 'Common name~~',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets~~',
	'Class:VLAN/Attribute:subnets_list+' => '~~',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physical network interfaces~~',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '~~',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
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

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:NetworkInterface' => 'ネットワークインターフェース',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => '名前',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'タイプ',
	'Class:NetworkInterface/Attribute:finalclass+' => '',
));

//
// Class: IPInterface
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:IPInterface' => 'IPインターフェース',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IPアドレス',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MACアドレス',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'コメント',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IPゲートウエイ',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IPマスク',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => '速度',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:PhysicalInterface' => '物理インターフェース',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'デバイス',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'デバイス名',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs~~',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
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

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:LogicalInterface' => '論理インターフェース',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => '仮想マシン',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => '仮想マシン名',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:FiberChannelInterface' => 'ファイバーチャネルインターフェース',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => '速度',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'トポロジー',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'デバイス',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'デバイス名',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'リンク 接続可能CI/ネットワークデバイス',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'ネットァークデバイス',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'ネットァークデバイス名',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => '接続されたデバイス',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => '接続されたデバイス名',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'ネットァークポート',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'デバイスポート',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => '接続タイプ',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'ダウンリンク',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'ダウンリンク',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'アップリンク',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'アップリンク',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'リンク アプリケーションソリューション/機能的CI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'アプリケーションソリューション',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'アプリケーションソリューション名',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => '機能的ci',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => '機能的ci名',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'リンク アプリケーション/ビジネスプロセス',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'ビジネスプロセス',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'ビジネスプロセス名',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'アプリケーションソリューション',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'アプリケーションソリューション名',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: Group
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Group' => 'グループ',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => '名前',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => '状態',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => '実装',
	'Class:Group/Attribute:status/Value:implementation+' => '実装',
	'Class:Group/Attribute:status/Value:obsolete' => '廃止',
	'Class:Group/Attribute:status/Value:obsolete+' => '廃止',
	'Class:Group/Attribute:status/Value:production' => '稼働',
	'Class:Group/Attribute:status/Value:production+' => '稼働',
	'Class:Group/Attribute:org_id' => '組織',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => '名前',
	'Class:Group/Attribute:owner_name+' => '共通名',
	'Class:Group/Attribute:description' => '説明',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'タイプ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => '親グループ',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => '名前',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'リンクされたCI',
	'Class:Group/Attribute:ci_list+' => '',
	'Class:Group/Attribute:parent_id_friendlyname' => '親グループ',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkGroupToCI' => 'リンク グループ/CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'グループ',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => '名前名',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => '名前',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => '理由',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Server:baseinfo' => '基本情報',
	'Server:Date' => '日付',
	'Server:moreinfo' => '追加情報',
	'Server:otherinfo' => '他の情報',
	'Server:power' => 'Power supply~~',
	'Class:Subnet/Tab:IPUsage' => 'IP 利用',
	'Class:Subnet/Tab:IPUsage-explain' => 'インターフェースは、レンジ: <em>%1$s</em> から <em>%2$s</em>の中のIPを持っています。',
	'Class:Subnet/Tab:FreeIPs' => 'フリーなIP',
	'Class:Subnet/Tab:FreeIPs-count' => 'フリーIP: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => '10個のフリーなIPアドレス',
	'Class:Document:PreviewTab' => 'プレビュー',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkDocumentToFunctionalCI' => 'リンク 文書/機能的CI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => '機能的ci',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => '機能的ci名',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => '文書',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Application Menu
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Menu:Application' => 'アプリケーション',
	'Menu:Application+' => '全アプリケーション',
	'Menu:DBServer' => 'DBサーバ',
	'Menu:DBServer+' => 'DBサーバ',
	'Menu:BusinessProcess' => 'ビジネスプロセス',
	'Menu:BusinessProcess+' => '全ビジネスプロセス',
	'Menu:ApplicationSolution' => 'アプリケーションソリューション',
	'Menu:ApplicationSolution+' => '全アプリケーションソリューション',
	'Menu:ConfigManagementSoftware' => 'ソフトウエア管理',
	'Menu:Licence' => 'ライセンス',
	'Menu:Licence+' => '全ライセンス',
	'Menu:Patch' => 'パッチ',
	'Menu:Patch+' => '全パッチ',
	'Menu:ApplicationInstance' => 'インストールされたソフトウエア',
	'Menu:ApplicationInstance+' => 'アプリケーションとデータベースサーバ',
	'Menu:ConfigManagementHardware' => 'インフラ管理',
	'Menu:Subnet' => 'サブネット',
	'Menu:Subnet+' => '全サブネット',
	'Menu:NetworkDevice' => 'ネットワークデバイス',
	'Menu:NetworkDevice+' => '全ネットワークデバイス',
	'Menu:Server' => 'サーバ',
	'Menu:Server+' => '全サーバ',
	'Menu:Printer' => 'プリンタ',
	'Menu:Printer+' => '全プリンタ',
	'Menu:MobilePhone' => '携帯電話',
	'Menu:MobilePhone+' => '全携帯電話',
	'Menu:PC' => 'パーソナルコンピュタ',
	'Menu:PC+' => 'All Personal computers~~',
	'Menu:NewCI' => '新規CI',
	'Menu:NewCI+' => '新規CI',
	'Menu:SearchCIs' => 'CI検索',
	'Menu:SearchCIs+' => 'CI検索',
	'Menu:ConfigManagement:Devices' => 'デバイス',
	'Menu:ConfigManagement:AllDevices' => 'インフラ',
	'Menu:ConfigManagement:virtualization' => '仮想化',
	'Menu:ConfigManagement:EndUsers' => 'エンドユーザデバイス',
	'Menu:ConfigManagement:SWAndApps' => 'ソフトウエアとアプリケーション',
	'Menu:ConfigManagement:Misc' => 'その他',
	'Menu:Group' => 'CIグループ',
	'Menu:Group+' => 'Groups of CIs~~',
	'Menu:OSVersion' => 'OS バージョン',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'ソフトウエアカタログ',
	'Menu:Software+' => 'ソフトウエアカタログ',
));
?>

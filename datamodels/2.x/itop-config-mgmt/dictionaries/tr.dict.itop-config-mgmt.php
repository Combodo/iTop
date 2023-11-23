<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Relation:impacts/Description'    => 'Etkilenen kalemler',
	'Relation:impacts/DownStream'     => 'Etkiler...',
	'Relation:impacts/DownStream+'    => 'Etkilenen kalemler',
	'Relation:impacts/UpStream'       => 'Bağımlı olanlar...',
	'Relation:impacts/UpStream+'      => 'Bu kaleme bağımlı olan kalemler',
	// Legacy entries
	'Relation:depends on/Description' => 'Bu kaleme bağımlı olan kalemler',
	'Relation:depends on/DownStream'  => 'Bağımlı olanlar...',
	'Relation:depends on/UpStream'    => 'Etkiledikleri...',
	'Relation:impacts/LoadData'       => 'Load data~~',
	'Relation:impacts/NoFilteredData' => 'please select objects and load data~~',
	'Relation:impacts/FilteredData'   => 'Filtered data~~',
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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContactToFunctionalCI' => 'Kişi / İşlevsel CI bağla',
	'Class:lnkContactToFunctionalCI+' => '~~',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'İşlevsel CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'İşlevsel CI Adı',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kişi',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Kişi Adı',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '~~',
));

//
// Class: FunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FunctionalCI' => 'Fonksiyonel KK',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Adı',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Açıklama',
	'Class:FunctionalCI/Attribute:description+' => '~~',
	'Class:FunctionalCI/Attribute:org_id' => 'Sahip kurum',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Organizasyon Adı',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Ortak Adı',
	'Class:FunctionalCI/Attribute:business_criticity' => 'İşin önemi',
	'Class:FunctionalCI/Attribute:business_criticity+' => '~~',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'yüksek',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'yüksek',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'düşük',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'düşük',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'orta',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'orta',
	'Class:FunctionalCI/Attribute:move2production' => 'Üretim tarihine geç',
	'Class:FunctionalCI/Attribute:move2production+' => '~~',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kişiler',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Bu yapılandırma öğesi için tüm kişiler',
	'Class:FunctionalCI/Attribute:documents_list' => 'Belgeler',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Bu yapılandırma öğesine bağlı tüm belgeler',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Uygulama sistemleri',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Bu yapılandırma öğesine bağlı olan tüm uygulama sistemleri',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Yazılımlar',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Bu yapılandırma öğesinde yüklü tüm yazılımlar',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tip',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Aktif Çağrı Kayıtları',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Active Tickets which are impacting this functional CI~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PhysicalDevice' => 'Fiziksel cihaz',
	'Class:PhysicalDevice+' => '~~',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Seri numarası',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '~~',
	'Class:PhysicalDevice/Attribute:location_id' => 'Konum',
	'Class:PhysicalDevice/Attribute:location_id+' => '~~',
	'Class:PhysicalDevice/Attribute:location_name' => 'Konum adı',
	'Class:PhysicalDevice/Attribute:location_name+' => '~~',
	'Class:PhysicalDevice/Attribute:status' => 'Durum',
	'Class:PhysicalDevice/Attribute:status+' => '~~',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'uygulama',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'uygulama',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'kullanım dışı',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'kullanım dışı',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'kullanımda',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'kullanımda',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'stok',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'stok',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Marka',
	'Class:PhysicalDevice/Attribute:brand_id+' => '~~',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Marka Adı',
	'Class:PhysicalDevice/Attribute:brand_name+' => '~~',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '~~',
	'Class:PhysicalDevice/Attribute:model_name' => 'Model Adı',
	'Class:PhysicalDevice/Attribute:model_name+' => '~~',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Demirbaş numarası',
	'Class:PhysicalDevice/Attribute:asset_number+' => '~~',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Satın alma tarihi',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '~~',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Garantinin sonu',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '~~',
));

//
// Class: Rack
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Rack' => 'Raf',
	'Class:Rack+' => '~~',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Rack/Attribute:nb_u' => 'Raf birimleri',
	'Class:Rack/Attribute:nb_u+' => '~~',
	'Class:Rack/Attribute:device_list' => 'Cihazlar',
	'Class:Rack/Attribute:device_list+' => 'Bu rafa yerleştirilmiş tüm fiziksel cihazlar',
	'Class:Rack/Attribute:enclosure_list' => 'Muhafazalar',
	'Class:Rack/Attribute:enclosure_list+' => 'Bu raftaki tüm muhafazalar',
));

//
// Class: TelephonyCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TelephonyCI' => 'Telefon CI',
	'Class:TelephonyCI+' => '~~',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefon numarası',
	'Class:TelephonyCI/Attribute:phonenumber+' => '~~',
));

//
// Class: Phone
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Phone' => 'Telefon',
	'Class:Phone+' => '~~',
));

//
// Class: MobilePhone
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:MobilePhone' => 'Cep telefonu',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:IPPhone' => 'IP telefonu',
	'Class:IPPhone+' => '~~',
));

//
// Class: Tablet
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '~~',
));

//
// Class: ConnectableCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ConnectableCI' => 'Bağlanabilir KK',
	'Class:ConnectableCI+' => 'Fiziksel KK',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s~~',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Ağ Aygıtları',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Bu cihaza bağlı tüm ağ cihazları',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Ağ arayüzleri',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Tüm fiziksel ağ arayüzleri',
));

//
// Class: DatacenterDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DatacenterDevice' => 'Veri merkezi cihazı',
	'Class:DatacenterDevice+' => '~~',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Raf',
	'Class:DatacenterDevice/Attribute:rack_id+' => '~~',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Raf Adı',
	'Class:DatacenterDevice/Attribute:rack_name+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Muhafaza',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Muhafaza adı',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '~~',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Raf birimleri',
	'Class:DatacenterDevice/Attribute:nb_u+' => '~~',
	'Class:DatacenterDevice/Attribute:managementip' => 'Yönetim IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'A Güç kaynağı',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'A Güç kaynağı adı',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'B Güç kaynağı',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'B Güç  Kaynağı adı',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '~~',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC Portları',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Bu cihaz için tüm fiber kanal arayüzleri',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN\'lar',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Bu cihaza bağlı tüm SAN anahtarları',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Yedeklilik',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'En az bir güç bağlantısı (A veya B) ayakta ise, cihaz ayaktadır',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Tüm güç bağlantıları ayakta ise, cihaz ayaktadır',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'En az %1$s %% güç bağlantısı ayakta ise cihaz ayaktadır',
));

//
// Class: NetworkDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NetworkDevice' => 'Ağ Cihazı',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Ağ tipi',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Ağ tipi adı',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '~~',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Cihazlar',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Bu ağ cihazına bağlı tüm cihazlar',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS sürümü',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '~~',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS Sürüm Adı',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '~~',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Server' => 'Sunucu',
	'Class:Server+' => '',
	'Class:Server/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Server/Attribute:osfamily_id' => 'OS ailesi',
	'Class:Server/Attribute:osfamily_id+' => '~~',
	'Class:Server/Attribute:osfamily_name' => 'OS Aile Adı',
	'Class:Server/Attribute:osfamily_name+' => '~~',
	'Class:Server/Attribute:osversion_id' => 'OS Sürümü',
	'Class:Server/Attribute:osversion_id+' => '~~',
	'Class:Server/Attribute:osversion_name' => 'OS Sürüm Adı',
	'Class:Server/Attribute:osversion_name+' => '~~',
	'Class:Server/Attribute:oslicence_id' => 'OS Lisansı',
	'Class:Server/Attribute:oslicence_id+' => '~~',
	'Class:Server/Attribute:oslicence_name' => 'OS Lisans Adı',
	'Class:Server/Attribute:oslicence_name+' => '~~',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Mantıksal depolama alanları',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Bu sunucuya bağlı tüm mantıksal depolama alanları',
));

//
// Class: StorageSystem
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:StorageSystem' => 'Depolama sistemi',
	'Class:StorageSystem+' => '~~',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s~~',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Mantıksal depolama alanları',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Bu depolama sistemindeki tüm mantıksal depolama alanları',
));

//
// Class: SANSwitch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SANSwitch' => 'SAN Anahtarı',
	'Class:SANSwitch+' => '~~',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Cihazlar',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Bu SAN anahtarına bağlı tüm cihazlar',
));

//
// Class: TapeLibrary
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TapeLibrary' => 'Teyp Kütüphanesi',
	'Class:TapeLibrary+' => '~~',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s~~',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Teypler',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Teyp kitaplığındaki tüm teypler',
));

//
// Class: NAS
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '~~',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Dosya sistemleri',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Bu NAS\'daki tüm dosya sistemleri',
));

//
// Class: PC
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PC/Attribute:osfamily_id' => 'OS ailesi',
	'Class:PC/Attribute:osfamily_id+' => '~~',
	'Class:PC/Attribute:osfamily_name' => 'OS Aile Adı',
	'Class:PC/Attribute:osfamily_name+' => '~~',
	'Class:PC/Attribute:osversion_id' => 'OS Sürümü',
	'Class:PC/Attribute:osversion_id+' => '~~',
	'Class:PC/Attribute:osversion_name' => 'OS Sürüm Adı',
	'Class:PC/Attribute:osversion_name+' => '~~',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Tip',
	'Class:PC/Attribute:type+' => '~~',
	'Class:PC/Attribute:type/Value:desktop' => 'masaüstü',
	'Class:PC/Attribute:type/Value:desktop+' => 'masaüstü',
	'Class:PC/Attribute:type/Value:laptop' => 'dizüstü',
	'Class:PC/Attribute:type/Value:laptop+' => 'dizüstü',
));

//
// Class: Printer
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Printer' => 'Yazıcı',
	'Class:Printer+' => '',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerConnection
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PowerConnection' => 'Güç Bağlantısı',
	'Class:PowerConnection+' => '~~',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerSource
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PowerSource' => 'Güç Kaynağı',
	'Class:PowerSource+' => '~~',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU\'lar',
	'Class:PowerSource/Attribute:pdus_list+' => 'Bu güç kaynağını kullanan tüm PDU\'lar',
));

//
// Class: PDU
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '~~',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s~~',
	'Class:PDU/Attribute:rack_id' => 'Raf',
	'Class:PDU/Attribute:rack_id+' => '~~',
	'Class:PDU/Attribute:rack_name' => 'Raf Adı',
	'Class:PDU/Attribute:rack_name+' => '~~',
	'Class:PDU/Attribute:powerstart_id' => 'Güç başlatıcı',
	'Class:PDU/Attribute:powerstart_id+' => '~~',
	'Class:PDU/Attribute:powerstart_name' => 'Güç başlatıcı adı',
	'Class:PDU/Attribute:powerstart_name+' => '~~',
));

//
// Class: Peripheral
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Peripheral' => 'Çevresel Birim',
	'Class:Peripheral+' => '~~',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: Enclosure
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Enclosure' => 'Muhafaza',
	'Class:Enclosure+' => '~~',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Enclosure/Attribute:rack_id' => 'Raf',
	'Class:Enclosure/Attribute:rack_id+' => '~~',
	'Class:Enclosure/Attribute:rack_name' => 'Raf Adı',
	'Class:Enclosure/Attribute:rack_name+' => '~~',
	'Class:Enclosure/Attribute:nb_u' => 'Raf birimleri',
	'Class:Enclosure/Attribute:nb_u+' => '~~',
	'Class:Enclosure/Attribute:device_list' => 'Cihazlar',
	'Class:Enclosure/Attribute:device_list+' => 'Bu muhafazadaki tüm cihazlar',
));

//
// Class: ApplicationSolution
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ApplicationSolution' => 'Uygulama çözümleri',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Bu uygulama sistemü oluşturan tüm yapılandırma öğeleri',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'İş Süreçleri',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Bu uygulama sistemüne bağlı tüm iş süreçleri',
	'Class:ApplicationSolution/Attribute:status' => 'Durum',
	'Class:ApplicationSolution/Attribute:status+' => '~~',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Aktif',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'Aktif',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Aktif değil',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'Aktif değil',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Etki Analizi: Yedekliliğin Yapılandırılması',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Tüm CI\'ler hazır ise sistem hazırdır',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'CI\'lerin en az %1$s \'i hazır ise sistem hazırdır',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'CI\'lerin en az %1$s %% \'i hazır ise sistem hazırdır.',
));

//
// Class: BusinessProcess
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:BusinessProcess' => 'İş süreci',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Uygulama sistemleri',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Bu iş sürecini etkileyen tüm uygulama çözümleri',
	'Class:BusinessProcess/Attribute:status' => 'Durum',
	'Class:BusinessProcess/Attribute:status+' => '~~',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Aktif',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'Aktif',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Aktif değil',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'Aktif değil',
));

//
// Class: SoftwareInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SoftwareInstance' => 'Yazılım Kurulumu',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Sistem',
	'Class:SoftwareInstance/Attribute:system_id+' => '~~',
	'Class:SoftwareInstance/Attribute:system_name' => 'Sistem Adı',
	'Class:SoftwareInstance/Attribute:system_name+' => '~~',
	'Class:SoftwareInstance/Attribute:software_id' => 'Yazılım',
	'Class:SoftwareInstance/Attribute:software_id+' => '~~',
	'Class:SoftwareInstance/Attribute:software_name' => 'Yazılım Adı',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Yazılım Lisansı',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '~~',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Yazılım Lisans Adı',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '~~',
	'Class:SoftwareInstance/Attribute:path' => 'Yol',
	'Class:SoftwareInstance/Attribute:path+' => '~~',
	'Class:SoftwareInstance/Attribute:status' => 'Durum',
	'Class:SoftwareInstance/Attribute:status+' => '~~',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Aktif',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'Aktif',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Aktif değil',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'Aktif değil',
));

//
// Class: Middleware
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Middleware' => 'Ara katman yazılımı',
	'Class:Middleware+' => '~~',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Ara katman yazılımı olayları',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Bu ara katman yazılımı tarafından sağlanan tüm ara katman yazılımı olayları',
));

//
// Class: DBServer
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DBServer' => 'Veritabanı',
	'Class:DBServer+' => 'Veritabanı yazılımı',
	'Class:DBServer/Attribute:dbschema_list' => 'Veritabanı şemaları',
	'Class:DBServer/Attribute:dbschema_list+' => 'Bu veritabanı sunucusu için tüm veritabanı şemaları',
));

//
// Class: WebServer
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:WebServer' => 'Web Sunucusu',
	'Class:WebServer+' => '~~',
	'Class:WebServer/Attribute:webapp_list' => 'Web Uygulamaları',
	'Class:WebServer/Attribute:webapp_list+' => 'Bu web sunucusunda mevcut tüm web uygulamaları',
));

//
// Class: PCSoftware
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PCSoftware' => 'PC yazılımı',
	'Class:PCSoftware+' => '~~',
));

//
// Class: OtherSoftware
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:OtherSoftware' => 'Diğer yazılım',
	'Class:OtherSoftware+' => '~~',
));

//
// Class: MiddlewareInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:MiddlewareInstance' => 'Ara katman yazılımı olayı',
	'Class:MiddlewareInstance+' => '~~',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s~~',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Ara katman yazılımı',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '~~',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Ara katman yazılımı adı',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '~~',
));

//
// Class: DatabaseSchema
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DatabaseSchema' => 'Veritabanı Şeması',
	'Class:DatabaseSchema+' => '~~',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Veritabanı Sunucusu',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '~~',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Veritabanı sunucu adı',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '~~',
));

//
// Class: WebApplication
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:WebApplication' => 'Web Uygulaması',
	'Class:WebApplication+' => '~~',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s~~',
	'Class:WebApplication/Attribute:webserver_id' => 'Web Sunucusu',
	'Class:WebApplication/Attribute:webserver_id+' => '~~',
	'Class:WebApplication/Attribute:webserver_name' => 'Web Sunucusu Adı',
	'Class:WebApplication/Attribute:webserver_name+' => '~~',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '~~',
));


//
// Class: VirtualDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:VirtualDevice' => 'Sanal cihaz',
	'Class:VirtualDevice+' => '~~',
	'Class:VirtualDevice/Attribute:status' => 'Durum',
	'Class:VirtualDevice/Attribute:status+' => '~~',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'kullanım dışı',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'kullanım dışı',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'kullanımda',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'kullanımda',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'stok',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'stok',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Mantıksal depolama alanları',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Bu cihaz tarafından kullanılan tüm mantıksal depolama alanları',
));

//
// Class: VirtualHost
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:VirtualHost' => 'Sanal Ana Makine',
	'Class:VirtualHost+' => '~~',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Sanal Makineler',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Bu ana makine üzerinde çalışan tüm sanal makineler',
));

//
// Class: Hypervisor
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Hypervisor' => 'Hipervizör',
	'Class:Hypervisor+' => '~~',
	'Class:Hypervisor/Attribute:farm_id' => 'Çiftlik',
	'Class:Hypervisor/Attribute:farm_id+' => '~~',
	'Class:Hypervisor/Attribute:farm_name' => 'Çiftlik Adı',
	'Class:Hypervisor/Attribute:farm_name+' => '~~',
	'Class:Hypervisor/Attribute:server_id' => 'Sunucu',
	'Class:Hypervisor/Attribute:server_id+' => '~~',
	'Class:Hypervisor/Attribute:server_name' => 'Sunucu adı',
	'Class:Hypervisor/Attribute:server_name+' => '~~',
));

//
// Class: Farm
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Farm' => 'Çiftlik',
	'Class:Farm+' => '~~',
	'Class:Farm/Attribute:hypervisor_list' => 'Hipervizörler',
	'Class:Farm/Attribute:hypervisor_list+' => 'Bu çiftliği oluşturan tüm hipervizörler',
	'Class:Farm/Attribute:redundancy' => 'Yüksek Kullanılabilirlik',
	'Class:Farm/Attribute:redundancy/disabled' => 'Tüm hipervizörler ayakta ise çiftlik ayaktadır',
	'Class:Farm/Attribute:redundancy/count' => 'Hipervizörlerin en az %1$s  \'i ayakta ise çiftlik ayaktadır',
	'Class:Farm/Attribute:redundancy/percent' => 'Hipervizörlerin en az %1$s %% \'i ayakta ise çiftlik ayaktadır.',
));

//
// Class: VirtualMachine
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:VirtualMachine' => 'Sanal makine',
	'Class:VirtualMachine+' => '~~',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s~~',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Sanal Ana Makine',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '~~',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Sanal Ana Makine Adı',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS ailesi',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS Aile Adı',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS Sürümü',
	'Class:VirtualMachine/Attribute:osversion_id+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS Sürüm Adı',
	'Class:VirtualMachine/Attribute:osversion_name+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS Lisansı',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS Lisans Adı',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '~~',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '~~',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '~~',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '~~',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Ağ arayüzleri',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Tüm mantıksal ağ arayüzleri',
));

//
// Class: LogicalVolume
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:LogicalVolume' => 'Mantıksal Depolama Alanları',
	'Class:LogicalVolume+' => '~~',
	'Class:LogicalVolume/Attribute:name' => 'İsim',
	'Class:LogicalVolume/Attribute:name+' => '~~',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '~~',
	'Class:LogicalVolume/Attribute:description' => 'Açıklama',
	'Class:LogicalVolume/Attribute:description+' => '~~',
	'Class:LogicalVolume/Attribute:raid_level' => 'RAID Seviyesi',
	'Class:LogicalVolume/Attribute:raid_level+' => '~~',
	'Class:LogicalVolume/Attribute:size' => 'Boyutu',
	'Class:LogicalVolume/Attribute:size+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Depolama sistemi',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Depolama Sistemi Adı',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '~~',
	'Class:LogicalVolume/Attribute:servers_list' => 'Sunucular',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Bu depolama alanını kullanan tüm sunucular',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Sanal Cihazlar',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Bu depolama alanını kullanan tüm sanal cihazlar',
));

//
// Class: lnkServerToVolume
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkServerToVolume' => 'Bağlantılı sunucu / Depolama alanı',
	'Class:lnkServerToVolume+' => '~~',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Depolama alanı',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Depolama alanı adı',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Sunucu',
	'Class:lnkServerToVolume/Attribute:server_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Sunucu adı',
	'Class:lnkServerToVolume/Attribute:server_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Kullanılan boyut',
	'Class:lnkServerToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkVirtualDeviceToVolume' => 'Sanal cihaz / Depolama alanı bağla',
	'Class:lnkVirtualDeviceToVolume+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Depolama alanı',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Depolama alanı adı',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Depolama alanı adı',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Sanal Cihaz Adı',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Kullanılan boyut',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkSanToDatacenterDevice' => 'SAN/ Veri merkezi cihazı bağla',
	'Class:lnkSanToDatacenterDevice+' => '~~',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN Anahtarı',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN Anahtarı Adı',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Cihaz',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Cihaz adı',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Cihaz FC',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '~~',
));

//
// Class: Tape
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Tape' => 'Teyp',
	'Class:Tape+' => '~~',
	'Class:Tape/Attribute:name' => 'İsim',
	'Class:Tape/Attribute:name+' => '~~',
	'Class:Tape/Attribute:description' => 'Açıklama',
	'Class:Tape/Attribute:description+' => '~~',
	'Class:Tape/Attribute:size' => 'Boyutu',
	'Class:Tape/Attribute:size+' => '~~',
	'Class:Tape/Attribute:tapelibrary_id' => 'Teyp Kütüphanesi',
	'Class:Tape/Attribute:tapelibrary_id+' => '~~',
	'Class:Tape/Attribute:tapelibrary_name' => 'Teyp Kütüphanesi Adı',
	'Class:Tape/Attribute:tapelibrary_name+' => '~~',
));

//
// Class: NASFileSystem
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NASFileSystem' => 'NAS dosya sistemi',
	'Class:NASFileSystem+' => '~~',
	'Class:NASFileSystem/Attribute:name' => 'İsim',
	'Class:NASFileSystem/Attribute:name+' => '~~',
	'Class:NASFileSystem/Attribute:description' => 'Açıklama',
	'Class:NASFileSystem/Attribute:description+' => '~~',
	'Class:NASFileSystem/Attribute:raid_level' => 'RAID Seviyesi',
	'Class:NASFileSystem/Attribute:raid_level+' => '~~',
	'Class:NASFileSystem/Attribute:size' => 'Boyutu',
	'Class:NASFileSystem/Attribute:size+' => '~~',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '~~',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS Adı',
	'Class:NASFileSystem/Attribute:nas_name+' => '~~',
));

//
// Class: Software
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Software' => 'Yazılım',
	'Class:Software+' => '',
	'Class:Software/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Software/Attribute:name' => 'Adı',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'satıcı',
	'Class:Software/Attribute:vendor+' => '~~',
	'Class:Software/Attribute:version' => 'Sürüm',
	'Class:Software/Attribute:version+' => '~~',
	'Class:Software/Attribute:documents_list' => 'Belgeler',
	'Class:Software/Attribute:documents_list+' => 'Bu yazılımla bağlantılı tüm belgeler',
	'Class:Software/Attribute:type' => 'Tip',
	'Class:Software/Attribute:type+' => '~~',
	'Class:Software/Attribute:type/Value:DBServer' => 'Veritabanı Sunucusu',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Veritabanı Sunucusu',
	'Class:Software/Attribute:type/Value:Middleware' => 'Ara katman yazılımı',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Ara katman yazılımı',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Diğer yazılım',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Diğer yazılım',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC yazılımı',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC yazılımı',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web Sunucusu',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web Sunucusu',
	'Class:Software/Attribute:softwareinstance_list' => 'Yazılım olayları',
	'Class:Software/Attribute:softwareinstance_list+' => 'Bu yazılım için tüm yazılım olayları',
	'Class:Software/Attribute:softwarepatch_list' => 'Yazılım yamaları',
	'Class:Software/Attribute:softwarepatch_list+' => 'Bu yazılım için tüm yamalar',
	'Class:Software/Attribute:softwarelicence_list' => 'Yazılım Lisansları',
	'Class:Software/Attribute:softwarelicence_list+' => 'Bu yazılımın tüm lisansları',
));

//
// Class: Patch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Patch' => 'Yama',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Adı',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Belgeler',
	'Class:Patch/Attribute:documents_list+' => 'Bu yama ile bağlantılı tüm belgeler',
	'Class:Patch/Attribute:description' => 'Tanımlama',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Tip',
	'Class:Patch/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: OSPatch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:OSPatch' => 'İşletim sistemi yaması',
	'Class:OSPatch+' => '~~',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Cihazlar',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Bu yamanın kurulduğu tüm sistemler',
	'Class:OSPatch/Attribute:osversion_id' => 'OS Sürümü',
	'Class:OSPatch/Attribute:osversion_id+' => '~~',
	'Class:OSPatch/Attribute:osversion_name' => 'OS Sürüm Adı',
	'Class:OSPatch/Attribute:osversion_name+' => '~~',
));

//
// Class: SoftwarePatch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SoftwarePatch' => 'Yazılım yaması',
	'Class:SoftwarePatch+' => '~~',
	'Class:SoftwarePatch/Attribute:software_id' => 'Yazılım',
	'Class:SoftwarePatch/Attribute:software_id+' => '~~',
	'Class:SoftwarePatch/Attribute:software_name' => 'Yazılım adı',
	'Class:SoftwarePatch/Attribute:software_name+' => '~~',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Yazılım olayları',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Bu yazılım yamasının kurulduğu tüm sistemler',
));

//
// Class: Licence
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Licence' => 'Lisans',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Adı',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Belgeler',
	'Class:Licence/Attribute:documents_list+' => 'Bu lisansla bağlantılı tüm belgeler',
	'Class:Licence/Attribute:org_id' => 'Sahibi',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organizasyon Adı',
	'Class:Licence/Attribute:organization_name+' => 'Ortak Adı',
	'Class:Licence/Attribute:usage_limit' => 'Kullanım limit',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Açıklama',
	'Class:Licence/Attribute:description+' => '~~',
	'Class:Licence/Attribute:start_date' => 'Başlangıç tarihi',
	'Class:Licence/Attribute:start_date+' => '~~',
	'Class:Licence/Attribute:end_date' => 'Son Tarihi',
	'Class:Licence/Attribute:end_date+' => '~~',
	'Class:Licence/Attribute:licence_key' => 'Lisans',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Sürekli',
	'Class:Licence/Attribute:perpetual+' => '~~',
	'Class:Licence/Attribute:perpetual/Value:no' => 'hayır',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'hayır',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'evet',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'evet',
	'Class:Licence/Attribute:finalclass' => 'Tip',
	'Class:Licence/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: OSLicence
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:OSLicence' => 'OS Lisansı',
	'Class:OSLicence+' => '~~',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:OSLicence/Attribute:osversion_id' => 'OS Sürümü',
	'Class:OSLicence/Attribute:osversion_id+' => '~~',
	'Class:OSLicence/Attribute:osversion_name' => 'OS Sürüm Adı',
	'Class:OSLicence/Attribute:osversion_name+' => '~~',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Sanal Makineler',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Bu lisansın kullanıldığı tüm sanal makineler',
	'Class:OSLicence/Attribute:servers_list' => 'Sunucular',
	'Class:OSLicence/Attribute:servers_list+' => 'Bu lisansın kullanıldığı tüm sunucular',
));

//
// Class: SoftwareLicence
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SoftwareLicence' => 'Yazılım Lisansı',
	'Class:SoftwareLicence+' => '~~',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SoftwareLicence/Attribute:software_id' => 'Yazılım',
	'Class:SoftwareLicence/Attribute:software_id+' => '~~',
	'Class:SoftwareLicence/Attribute:software_name' => 'Yazılım adı',
	'Class:SoftwareLicence/Attribute:software_name+' => '~~',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Yazılım olayları',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Bu lisansın kullanıldığı tüm sistemler',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToLicence' => 'Belge / lisans bağla',
	'Class:lnkDocumentToLicence+' => '~~',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Lisans',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Lisans adı',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '~~',
));

//
// Class: OSVersion
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:OSVersion' => 'OS Sürümü',
	'Class:OSVersion+' => '~~',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS ailesi',
	'Class:OSVersion/Attribute:osfamily_id+' => '~~',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS Aile Adı',
	'Class:OSVersion/Attribute:osfamily_name+' => '~~',
));

//
// Class: OSFamily
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:OSFamily' => 'OS ailesi',
	'Class:OSFamily+' => '~~',
));

//
// Class: Brand
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Brand' => 'Marka',
	'Class:Brand+' => '~~',
	'Class:Brand/Attribute:physicaldevices_list' => 'Fiziksel cihazlar',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Bu markaya karşılık gelen tüm fiziksel cihazlar',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique~~',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists~~',
));

//
// Class: Model
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '~~',
	'Class:Model/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Model/Attribute:brand_id' => 'Marka',
	'Class:Model/Attribute:brand_id+' => '~~',
	'Class:Model/Attribute:brand_name' => 'Marka Adı',
	'Class:Model/Attribute:brand_name+' => '~~',
	'Class:Model/Attribute:type' => 'Cihaz tipi',
	'Class:Model/Attribute:type+' => '~~',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Güç Kaynağı',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Güç Kaynağı',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Disk dizisi',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Disk dizisi',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Muhafaza',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Muhafaza',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP telefonu',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP telefonu',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Cep telefonu',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Cep telefonu',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Ağ cihazı',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Ağ cihazı',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Çevresel cihaz',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Çevresel cihaz',
	'Class:Model/Attribute:type/Value:Printer' => 'Yazıcı',
	'Class:Model/Attribute:type/Value:Printer+' => 'Yazıcı',
	'Class:Model/Attribute:type/Value:Rack' => 'Raf',
	'Class:Model/Attribute:type/Value:Rack+' => 'Raf',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN Anahtarı',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN Anahtarı',
	'Class:Model/Attribute:type/Value:Server' => 'Sunucu',
	'Class:Model/Attribute:type/Value:Server+' => 'Sunucu',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Depolama sistemi',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Depolama sistemi',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Teyp Kütüphanesi',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Teyp Kütüphanesi',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefon',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telefon',
	'Class:Model/Attribute:physicaldevices_list' => 'Fiziksel cihazlar',
	'Class:Model/Attribute:physicaldevices_list+' => 'Bu modele karşılık gelen tüm fiziksel cihazlar',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand~~',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand~~',
));

//
// Class: NetworkDeviceType
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NetworkDeviceType' => 'Ağ Cihazı Tipi',
	'Class:NetworkDeviceType+' => '~~',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Ağ Aygıtları',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Bu türde karşılık gelen tüm ağ aygıtları',
));

//
// Class: IOSVersion
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:IOSVersion' => 'IOS sürümü',
	'Class:IOSVersion+' => '~~',
	'Class:IOSVersion/Attribute:brand_id' => 'Marka',
	'Class:IOSVersion/Attribute:brand_id+' => '~~',
	'Class:IOSVersion/Attribute:brand_name' => 'Marka Adı',
	'Class:IOSVersion/Attribute:brand_name+' => '~~',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToPatch' => 'Bağlantılı belge / yama',
	'Class:lnkDocumentToPatch+' => '~~',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Yama',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Yama adı',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '~~',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Yazılımı olayı / Yazılım yaması bağla',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Yazılım yaması',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Yazılım yama adı',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Yazılım olayı',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Yazılım olayı adı',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '~~',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkFunctionalCIToOSPatch' => 'İşlevsel CI / OS Yaması bağla',
	'Class:lnkFunctionalCIToOSPatch+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'İşletim sistemi yaması',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'İşletim sistemi yama adı',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'İşlevsel CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'İşlevsel CI Adı',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToSoftware' => 'Belge / yazılım bağla',
	'Class:lnkDocumentToSoftware+' => '~~',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Yazılım',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Yazılım adı',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '~~',
));

//
// Class: Subnet
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s/%2$s~~',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Subnet/Attribute:description' => 'Tanımlama',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Alt ağ adı',
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => 'Kurum',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'İsim',
	'Class:Subnet/Attribute:org_name+' => 'Ortak Adı',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN \'lar',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:VLAN' => 'Vlan',
	'Class:VLAN+' => '~~',
	'Class:VLAN/Attribute:vlan_tag' => 'Vlan etiketi',
	'Class:VLAN/Attribute:vlan_tag+' => '~~',
	'Class:VLAN/Attribute:description' => 'Açıklama',
	'Class:VLAN/Attribute:description+' => '~~',
	'Class:VLAN/Attribute:org_id' => 'Organizasyon',
	'Class:VLAN/Attribute:org_id+' => '~~',
	'Class:VLAN/Attribute:org_name' => 'Organizasyon Adı',
	'Class:VLAN/Attribute:org_name+' => 'Ortak Adı',
	'Class:VLAN/Attribute:subnets_list' => 'Alt Ağları',
	'Class:VLAN/Attribute:subnets_list+' => '~~',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fiziksel Ağ Arayüzleri',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '~~',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkSubnetToVLAN' => 'Alt Ağ / VLAN  bağla',
	'Class:lnkSubnetToVLAN+' => '~~',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Alt Ağ',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Alt Ağ IP \'si',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Alt ağ adı',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Etiketi',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '~~',
));

//
// Class: NetworkInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NetworkInterface' => 'Network arayüzü',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'İsim',
	'Class:NetworkInterface/Attribute:name+' => '~~',
	'Class:NetworkInterface/Attribute:finalclass' => 'Tip',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: IPInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:IPInterface' => 'IP arayüzü',
	'Class:IPInterface+' => '~~',
	'Class:IPInterface/Attribute:ipaddress' => 'IP adresi',
	'Class:IPInterface/Attribute:ipaddress+' => '~~',
	'Class:IPInterface/Attribute:macaddress' => 'MAC adresi',
	'Class:IPInterface/Attribute:macaddress+' => '~~',
	'Class:IPInterface/Attribute:comment' => 'Yorum',
	'Class:IPInterface/Attribute:coment+' => '~~',
	'Class:IPInterface/Attribute:ipgateway' => 'IP Ağ Geçidi',
	'Class:IPInterface/Attribute:ipgateway+' => '~~',
	'Class:IPInterface/Attribute:ipmask' => 'IP maskesi',
	'Class:IPInterface/Attribute:ipmask+' => '~~',
	'Class:IPInterface/Attribute:speed' => 'Hız',
	'Class:IPInterface/Attribute:speed+' => '~~',
));

//
// Class: PhysicalInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PhysicalInterface' => 'Fiziksel arayüz',
	'Class:PhysicalInterface+' => '~~',
	'Class:PhysicalInterface/Name' => '%2$s %1$s~~',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Cihaz',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '~~',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Cihaz adı',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '~~',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN\'lar',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Fiziksel Arabirim / VLAN bağla',
	'Class:lnkPhysicalInterfaceToVLAN+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Fiziksel arabirim',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Fiziksel Arabirim Adı',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Cihaz',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Cihaz adı',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN etiketi',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '~~',
));


//
// Class: LogicalInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:LogicalInterface' => 'Mantıksal arabirim',
	'Class:LogicalInterface+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Sanal makine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Sanal Makine Adı',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '~~',
));

//
// Class: FiberChannelInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FiberChannelInterface' => 'Fiber Kanal Arabirimi',
	'Class:FiberChannelInterface+' => '~~',
	'Class:FiberChannelInterface/Attribute:speed' => 'Hız',
	'Class:FiberChannelInterface/Attribute:speed+' => '~~',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topoloji',
	'Class:FiberChannelInterface/Attribute:topology+' => '~~',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Cihaz',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Cihaz adı',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '~~',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'İlişkilendirilebilir CI / Ağ cihazı bağla',
	'Class:lnkConnectableCIToNetworkDevice+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Ağ cihazı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Ağ Aygıtı Adı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Bağlı cihaz',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Bağlı cihaz adı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Ağ portu',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Cihaz portu',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Bağlantı tipi',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'aşağı bağlantı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'aşağı bağlantı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'yukarı bağlantı',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'yukarı bağlantı',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Uygulama Çözümü / İşlevsel CI bağla',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Uygulama çözümü',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Uygulama çözümü Adı',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'İşlevsel CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'İşlevsel CI Adı',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Uygulama Çözümü / İş Süreci bağla',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'İş süreci',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'İş Süreci Adı',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Uygulama çözümü',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Uygulama Çözümü Adı',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '~~',
));

//
// Class: Group
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Group' => 'Grup',
	'Class:Group+' => '',
	'Class:Group/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Group/Attribute:name' => 'Adı',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Surumu',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:Group/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:Group/Attribute:status/Value:obsolete' => 'Üretimden kalkan',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Üretimden kalkan',
	'Class:Group/Attribute:status/Value:production' => 'Kullanımda',
	'Class:Group/Attribute:status/Value:production+' => 'Kullanımda',
	'Class:Group/Attribute:org_id' => 'Kurum',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Adı',
	'Class:Group/Attribute:owner_name+' => 'Kullanılan Adı',
	'Class:Group/Attribute:description' => 'Tanımlama',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tip',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Bağlı olduğu grup',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Adı',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Bağlantılı Konfigürasyon Kalemleri (KK)',
	'Class:Group/Attribute:ci_list+' => 'All the configuration items linked to this group~~',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Ana Grup',
	'Class:Group/Attribute:parent_id_friendlyname+' => '~~',
));

//
// Class: lnkGroupToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkGroupToCI' => 'Grup / KK',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s~~',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grup',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Adı',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'KK',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Adı',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Sebep',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Server:baseinfo' => 'Genel Bilgi',
	'Server:Date' => 'Tarihler',
	'Server:moreinfo' => 'Daha fazla bilgi',
	'Server:otherinfo' => 'Diğer bilgiler',
	'Server:power' => 'Güç kaynağı',
	'Class:Subnet/Tab:IPUsage' => 'IP Kullanımı',
	'Class:Subnet/Tab:IPUsage+' => 'Which IP within this Subnet are used or not~~',
	'Class:Subnet/Tab:IPUsage-explain' => '<em>%1$s</em> - <em>%2$s</em> aralığındaki IPye sahip arayüzler',
	'Class:Subnet/Tab:FreeIPs' => 'Boş IPler',
	'Class:Subnet/Tab:FreeIPs-count' => 'Boş IPler: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Boş IP adresleri',
	'Class:Document:PreviewTab' => 'Ön görünüm',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToFunctionalCI' => 'Belge / İşlevsel CI bağla',
	'Class:lnkDocumentToFunctionalCI+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'İşlevsel CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'İşlevsel CI Adı',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '~~',
));

//
// Application Menu
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:Application' => 'Uygulamalar',
	'Menu:Application+' => 'Tüm Uygulamalar',
	'Menu:DBServer' => 'Veritabanı sunucuları',
	'Menu:DBServer+' => 'Veritabanı sunucuları',
	'Menu:BusinessProcess' => 'İş süreçleri',
	'Menu:BusinessProcess+' => 'Tüm İş süreçleri',
	'Menu:ApplicationSolution' => 'Uygulama çözümleri',
	'Menu:ApplicationSolution+' => 'Tüm Uygulama çözümleri',
	'Menu:ConfigManagementSoftware' => 'Uygulama Yönetimi',
	'Menu:Licence' => 'Lisanslar',
	'Menu:Licence+' => 'Tüm Lisanslar',
	'Menu:Patch' => 'Yamalar',
	'Menu:Patch+' => 'Tüm Yamalar',
	'Menu:ApplicationInstance' => 'Yüklenen yazılımlar',
	'Menu:ApplicationInstance+' => 'Uygulama ve Veritabanı sunucuları',
	'Menu:ConfigManagementHardware' => 'Altyapı Yönetimi',
	'Menu:Subnet' => 'Subnets',
	'Menu:Subnet+' => 'All Subnets',
	'Menu:NetworkDevice' => 'Network cihazları',
	'Menu:NetworkDevice+' => 'Tüm Network cihazları',
	'Menu:Server' => 'Sunucular',
	'Menu:Server+' => 'Tüm Sunucular',
	'Menu:Printer' => 'Yazıcılar',
	'Menu:Printer+' => 'Tüm Yazıcılar',
	'Menu:MobilePhone' => 'Cep Telefonları',
	'Menu:MobilePhone+' => 'Tüm Cep Telefonları',
	'Menu:PC' => 'Kişisel Bilgisayarlar',
	'Menu:PC+' => 'Tüm Kişisel Bilgisayarlar',
	'Menu:NewCI' => 'Yeni KK',
	'Menu:NewCI+' => 'Yeni KK',
	'Menu:SearchCIs' => 'KK ara',
	'Menu:SearchCIs+' => 'KK ara',
	'Menu:ConfigManagement:Devices' => 'Cihazlar',
	'Menu:ConfigManagement:AllDevices' => 'Altyapı',
	'Menu:ConfigManagement:virtualization' => 'Sanallaştırma',
	'Menu:ConfigManagement:EndUsers' => 'Son Kullanıcı Aygıtları',
	'Menu:ConfigManagement:SWAndApps' => 'Yazılım ve uygulamalar',
	'Menu:ConfigManagement:Misc' => 'Diğer',
	'Menu:Group' => 'KK Grupları',
	'Menu:Group+' => 'KK Grupları',
	'Menu:OSVersion' => 'OS sürümleri',
	'Menu:OSVersion+' => '~~',
	'Menu:Software' => 'Yazılım Kataloğu',
	'Menu:Software+' => 'Yazılım Kataloğu',
));
?>

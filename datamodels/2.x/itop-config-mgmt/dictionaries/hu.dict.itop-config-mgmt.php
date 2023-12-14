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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Relation:impacts/Description'    => 'Konfigurációs elem működését befolyásolják',
	'Relation:impacts/DownStream'     => 'Hatás',
	'Relation:impacts/DownStream+'    => 'Konfigurációs elem működését befolyásolják',
	'Relation:impacts/UpStream'       => 'Függőségek',
	'Relation:impacts/UpStream+'      => 'Konfigurációs elemtől függnek',
	// Legacy entries
	'Relation:depends on/Description' => 'Konfigurációs elemtől függnek',
	'Relation:depends on/DownStream'  => 'Függőségek',
	'Relation:depends on/UpStream'    => 'Hatások',
	'Relation:impacts/LoadData'       => 'Adat betöltés',
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

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkContactToFunctionalCI' => 'Kapcsolattartó / Funkcionális CI',
	'Class:lnkContactToFunctionalCI+' => '~~',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Funkcionális CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Funkcionális CI név',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kapcsolattartó',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '~~',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Kapcsolattartó név',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '~~',
));

//
// Class: FunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:FunctionalCI' => 'Funkcionális CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Név',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Leírás',
	'Class:FunctionalCI/Attribute:description+' => '~~',
	'Class:FunctionalCI/Attribute:org_id' => 'Tulajdonos szevezeti egység',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Szervezeti egység név',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Általános név',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Üzlet kritikusság',
	'Class:FunctionalCI/Attribute:business_criticity+' => '~~',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'magas',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'high~~',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'alacsony',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'low~~',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'közepes',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'medium~~',
	'Class:FunctionalCI/Attribute:move2production' => 'Használatba vétel dátuma',
	'Class:FunctionalCI/Attribute:move2production+' => '~~',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kapcsolattartók',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Kapcsolattartók ehhez a konfigurációs elemhez',
	'Class:FunctionalCI/Attribute:documents_list' => 'Dokumentumok',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Dokumentumok ehhez a konfigurációs elemhez',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Alkalmazásmegoldások',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Alkalmazásmegoldások, melyek ettől a konfigurációs elemtől függenek',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Szoftverek',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Szoftverek, melyek erre a konfigurációs elemre vannak telepítve',
	'Class:FunctionalCI/Attribute:finalclass' => 'Típus',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Aktív hibajegyek',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Active Tickets which are impacting this functional CI~~',
));

//
// Class: PhysicalDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PhysicalDevice' => 'Fizikai eszköz',
	'Class:PhysicalDevice+' => '~~',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Sorozatszám',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '~~',
	'Class:PhysicalDevice/Attribute:location_id' => 'Helyszín',
	'Class:PhysicalDevice/Attribute:location_id+' => '~~',
	'Class:PhysicalDevice/Attribute:location_name' => 'Helyszín név',
	'Class:PhysicalDevice/Attribute:location_name+' => '~~',
	'Class:PhysicalDevice/Attribute:status' => 'Állapot',
	'Class:PhysicalDevice/Attribute:status+' => '~~',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'implementáció',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'Használatban',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'production~~',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'Készleten',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'stock~~',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Gyártó',
	'Class:PhysicalDevice/Attribute:brand_id+' => '~~',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Gyártó neve',
	'Class:PhysicalDevice/Attribute:brand_name+' => '~~',
	'Class:PhysicalDevice/Attribute:model_id' => 'Modell',
	'Class:PhysicalDevice/Attribute:model_id+' => '~~',
	'Class:PhysicalDevice/Attribute:model_name' => 'Modellnév',
	'Class:PhysicalDevice/Attribute:model_name+' => '~~',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Leltári szám',
	'Class:PhysicalDevice/Attribute:asset_number+' => '~~',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Beszerzési dátum',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '~~',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Garanciaidő vége',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '~~',
));

//
// Class: Rack
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '~~',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Rack/Attribute:nb_u' => 'Unit magasság',
	'Class:Rack/Attribute:nb_u+' => '~~',
	'Class:Rack/Attribute:device_list' => 'Eszközök',
	'Class:Rack/Attribute:device_list+' => 'Minden fizikai eszköz amely ebbe a rack-be lett beszerelve',
	'Class:Rack/Attribute:enclosure_list' => 'Készülékházak',
	'Class:Rack/Attribute:enclosure_list+' => 'Minden készülékház ebben a rack-ben',
));

//
// Class: TelephonyCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TelephonyCI' => 'Telefónia CI',
	'Class:TelephonyCI+' => '~~',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Telefonszám',
	'Class:TelephonyCI/Attribute:phonenumber+' => '~~',
));

//
// Class: Phone
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Phone' => 'Telefon',
	'Class:Phone+' => '~~',
));

//
// Class: MobilePhone
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:MobilePhone' => 'Mobiltelefon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:IPPhone' => 'Egyéb eszköz',
	'Class:IPPhone+' => '~~',
));

//
// Class: Tablet
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Tablet' => 'Táblagép',
	'Class:Tablet+' => '~~',
));

//
// Class: ConnectableCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ConnectableCI' => 'Kapcsolható CI',
	'Class:ConnectableCI+' => '',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s~~',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Hálózati eszközök',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Hálózati eszközök, melyek ehhez a konfigurációs elemhez kapcsolódnak',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Hálózati csatolók',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'A konfigurációs elem fizikai hálózati csatolói',
));

//
// Class: DatacenterDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DatacenterDevice' => 'Adatközpont eszköz',
	'Class:DatacenterDevice+' => '~~',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '~~',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Rack név',
	'Class:DatacenterDevice/Attribute:rack_name+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Készülékház',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '~~',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Készülékház név',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '~~',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Unit magasság',
	'Class:DatacenterDevice/Attribute:nb_u+' => '~~',
	'Class:DatacenterDevice/Attribute:managementip' => 'Menedzsment IP cím',
	'Class:DatacenterDevice/Attribute:managementip+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA áramforrás',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA áramforrás név',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB áramforrás',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '~~',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB áramforrás név',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '~~',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC portok',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'A konfigurációs elem FC csatolói',
	'Class:DatacenterDevice/Attribute:san_list' => 'SAN switch-ek',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Ehhez a konfigurációs elemhez csatlakozó SAN switch-ek',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundancia',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'A készülék akkor működik, ha legalább az egyik tápcsatlakozás (A vagy B) működik.',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'A készülék akkor működik, ha az összes tápcsatlakozása működik.',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Az eszköz akkor működik, ha legalább %1$s a %%-ből tápcsatlakozása működik.',
));

//
// Class: NetworkDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:NetworkDevice' => 'Hálózati eszköz',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Hálózati eszköz típus',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '~~',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Hálózati eszköz típus név',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '~~',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Kapcsolódó eszközök',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Az összes eszköz, amely ehhez a hálózati eszközhöz csatlakozik',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS verzió',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '~~',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS verzió név',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '~~',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Server' => 'Szerver',
	'Class:Server+' => '',
	'Class:Server/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Server/Attribute:osfamily_id' => 'OS család',
	'Class:Server/Attribute:osfamily_id+' => '~~',
	'Class:Server/Attribute:osfamily_name' => 'OS család név',
	'Class:Server/Attribute:osfamily_name+' => '~~',
	'Class:Server/Attribute:osversion_id' => 'OS verzió',
	'Class:Server/Attribute:osversion_id+' => '~~',
	'Class:Server/Attribute:osversion_name' => 'OS verzió név',
	'Class:Server/Attribute:osversion_name+' => '~~',
	'Class:Server/Attribute:oslicence_id' => 'OS licenc',
	'Class:Server/Attribute:oslicence_id+' => '~~',
	'Class:Server/Attribute:oslicence_name' => 'OS licenc név',
	'Class:Server/Attribute:oslicence_name+' => '~~',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logikai kötetek',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Logikai kötetek, melyek ehhez a szerverhez kapcsolódnak',
));

//
// Class: StorageSystem
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:StorageSystem' => 'Tárolórendszer',
	'Class:StorageSystem+' => '~~',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s~~',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logikai kötetek',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Logikai kötetek ebben a tárolórendszerben',
));

//
// Class: SANSwitch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SANSwitch' => 'SAN Switch',
	'Class:SANSwitch+' => '~~',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Kapcsolódó eszközök',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Ehhez a SAN-switch-hez csatlakozó konfigurációs elemek',
));

//
// Class: TapeLibrary
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TapeLibrary' => 'Szalagos tároló',
	'Class:TapeLibrary+' => '~~',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s~~',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Szalagok',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Szalagok a szalagos tárolóhoz',
));

//
// Class: NAS
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '~~',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s~~',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Fájlrendszerek',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'A NAS fájlrendszerei',
));

//
// Class: PC
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PC/Attribute:osfamily_id' => 'OS család',
	'Class:PC/Attribute:osfamily_id+' => '~~',
	'Class:PC/Attribute:osfamily_name' => 'OS család név',
	'Class:PC/Attribute:osfamily_name+' => '~~',
	'Class:PC/Attribute:osversion_id' => 'OS verzió',
	'Class:PC/Attribute:osversion_id+' => '~~',
	'Class:PC/Attribute:osversion_name' => 'OS verzió név',
	'Class:PC/Attribute:osversion_name+' => '~~',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Típus',
	'Class:PC/Attribute:type+' => '~~',
	'Class:PC/Attribute:type/Value:desktop' => 'Desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'desktop~~',
	'Class:PC/Attribute:type/Value:laptop' => 'Laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'laptop~~',
));

//
// Class: Printer
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Printer' => 'Nyomtató',
	'Class:Printer+' => '',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerConnection
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PowerConnection' => 'Tápellátás',
	'Class:PowerConnection+' => '~~',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: PowerSource
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PowerSource' => 'Áramforrás',
	'Class:PowerSource+' => '~~',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s~~',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU-k',
	'Class:PowerSource/Attribute:pdus_list+' => 'Tápelosztók amik ezt az áramforrást használják',
));

//
// Class: PDU
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '~~',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s~~',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '~~',
	'Class:PDU/Attribute:rack_name' => 'Rack név',
	'Class:PDU/Attribute:rack_name+' => '~~',
	'Class:PDU/Attribute:powerstart_id' => 'Betápláló',
	'Class:PDU/Attribute:powerstart_id+' => '~~',
	'Class:PDU/Attribute:powerstart_name' => 'Betápláló név',
	'Class:PDU/Attribute:powerstart_name+' => '~~',
));

//
// Class: Peripheral
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Peripheral' => 'Periféria',
	'Class:Peripheral+' => '~~',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s~~',
));

//
// Class: Enclosure
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Enclosure' => 'Készülékház',
	'Class:Enclosure+' => '~~',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '~~',
	'Class:Enclosure/Attribute:rack_name' => 'Rack név',
	'Class:Enclosure/Attribute:rack_name+' => '~~',
	'Class:Enclosure/Attribute:nb_u' => 'Unit magasság',
	'Class:Enclosure/Attribute:nb_u+' => '~~',
	'Class:Enclosure/Attribute:device_list' => 'Eszközök',
	'Class:Enclosure/Attribute:device_list+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ApplicationSolution' => 'Egyedi alkalmazás',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CI-k',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Konfigurációs elemek, melyek ezt az alkalmazásmegoldást alkotják',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Üzleti folyamatok',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Üzleti folyamatok, melyek ettől az alkalmazásmegoldástól függenek',
	'Class:ApplicationSolution/Attribute:status' => 'Állapot',
	'Class:ApplicationSolution/Attribute:status+' => '~~',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'Aktív',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'active~~',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'inactive~~',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Hatáselemzés: a redundancia konfigurációja',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'A megoldás akkor működik, ha minden CI működik.',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'A megoldás akkor működik, ha legalább %1$s CI működik',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'A megoldás akkor működik, ha legalább %1$s a %%-ből CI működik',
));

//
// Class: BusinessProcess
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:BusinessProcess' => 'Üzleti folyamat',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Alkalmazásmegoldások',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Alkalmazásmegoldások melyek hatással vannak erre az üzleti folyamatra',
	'Class:BusinessProcess/Attribute:status' => 'Állapot',
	'Class:BusinessProcess/Attribute:status+' => '~~',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'Aktív',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'active~~',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'inactive~~',
));

//
// Class: SoftwareInstance
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SoftwareInstance' => 'Szoftverpéldány',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'Rendszer',
	'Class:SoftwareInstance/Attribute:system_id+' => '~~',
	'Class:SoftwareInstance/Attribute:system_name' => 'Rendszer név',
	'Class:SoftwareInstance/Attribute:system_name+' => '~~',
	'Class:SoftwareInstance/Attribute:software_id' => 'Szoftver',
	'Class:SoftwareInstance/Attribute:software_id+' => '~~',
	'Class:SoftwareInstance/Attribute:software_name' => 'Szoftvernév',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Szoftver licenc',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '~~',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Szoftver licenc név',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '~~',
	'Class:SoftwareInstance/Attribute:path' => 'Elérési út',
	'Class:SoftwareInstance/Attribute:path+' => '~~',
	'Class:SoftwareInstance/Attribute:status' => 'Állapot',
	'Class:SoftwareInstance/Attribute:status+' => '~~',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'Aktív',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'active~~',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'Inaktív',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'inactive~~',
));

//
// Class: Middleware
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => 'Köztes szoftver',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware példány',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Middleware példányok melyeket ez a middlaware biztosít',
));

//
// Class: DBServer
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DBServer' => 'Adatbázis',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB sémák',
	'Class:DBServer/Attribute:dbschema_list+' => 'A DB szerver adatbázis sémái',
));

//
// Class: WebServer
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:WebServer' => 'Webszerver',
	'Class:WebServer+' => '~~',
	'Class:WebServer/Attribute:webapp_list' => 'Webalkalmazások',
	'Class:WebServer/Attribute:webapp_list+' => 'Webalkalmazások, melyek elérhetőek ezen a webszerveren',
));

//
// Class: PCSoftware
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PCSoftware' => 'PC Szoftver',
	'Class:PCSoftware+' => '~~',
));

//
// Class: OtherSoftware
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OtherSoftware' => 'Egyéb szoftver',
	'Class:OtherSoftware+' => '~~',
));

//
// Class: MiddlewareInstance
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:MiddlewareInstance' => 'Middleware Példány',
	'Class:MiddlewareInstance+' => '~~',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s~~',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '~~',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware név',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '~~',
));

//
// Class: DatabaseSchema
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DatabaseSchema' => 'Adatbázis séma',
	'Class:DatabaseSchema+' => '~~',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s~~',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB szerver',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '~~',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB szerver név',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '~~',
));

//
// Class: WebApplication
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:WebApplication' => 'Webalkalmazás',
	'Class:WebApplication+' => '~~',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s~~',
	'Class:WebApplication/Attribute:webserver_id' => 'Webszerver',
	'Class:WebApplication/Attribute:webserver_id+' => '~~',
	'Class:WebApplication/Attribute:webserver_name' => 'Webszerver név',
	'Class:WebApplication/Attribute:webserver_name+' => '~~',
	'Class:WebApplication/Attribute:url' => 'URL~~',
	'Class:WebApplication/Attribute:url+' => '~~',
));


//
// Class: VirtualDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:VirtualDevice' => 'Virtuális eszköz',
	'Class:VirtualDevice+' => '~~',
	'Class:VirtualDevice/Attribute:status' => 'Állapot',
	'Class:VirtualDevice/Attribute:status+' => '~~',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'Használatban',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'production~~',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'Készleten',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'stock~~',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logikai kötetek',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Logikai kötetek, amiket ez az eszköz használ',
));

//
// Class: VirtualHost
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:VirtualHost' => 'Virtuális gazdagép',
	'Class:VirtualHost+' => '~~',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtuális gépek',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Virtuális gépek, amelyeket ez a gazdagép futtat',
));

//
// Class: Hypervisor
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '~~',
	'Class:Hypervisor/Attribute:farm_id' => 'Szerverfarm',
	'Class:Hypervisor/Attribute:farm_id+' => '~~',
	'Class:Hypervisor/Attribute:farm_name' => 'Szerverfarm név',
	'Class:Hypervisor/Attribute:farm_name+' => '~~',
	'Class:Hypervisor/Attribute:server_id' => 'Szerver',
	'Class:Hypervisor/Attribute:server_id+' => '~~',
	'Class:Hypervisor/Attribute:server_name' => 'Szerver név',
	'Class:Hypervisor/Attribute:server_name+' => '~~',
));

//
// Class: Farm
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Farm' => 'Szerverfarm',
	'Class:Farm+' => '~~',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisor-ok',
	'Class:Farm/Attribute:hypervisor_list+' => 'All the hypervisors that compose this farm~~',
	'Class:Farm/Attribute:redundancy' => 'Magas rendelkezésre állás',
	'Class:Farm/Attribute:redundancy/disabled' => 'A farm működik, ha az összes hypervisor működik.',
	'Class:Farm/Attribute:redundancy/count' => 'A farm működik, ha legalább %1$s hypervisor működik',
	'Class:Farm/Attribute:redundancy/percent' => 'A farm működik, ha legalább %1$s a %%-ből hypervisor működik.',
));

//
// Class: VirtualMachine
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:VirtualMachine' => 'Virtuális gép',
	'Class:VirtualMachine+' => '~~',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s~~',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtuális gazdagép',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '~~',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Virtuális gazdagép név',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS család',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '~~',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS család név',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS verzió',
	'Class:VirtualMachine/Attribute:osversion_id+' => '~~',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS verzió név',
	'Class:VirtualMachine/Attribute:osversion_name+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS licenc',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '~~',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS licenc név',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '~~',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '~~',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '~~',
	'Class:VirtualMachine/Attribute:managementip' => 'IP cím',
	'Class:VirtualMachine/Attribute:managementip+' => '~~',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Hálózati csatolók',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Logikai hálózati csatolók',
));

//
// Class: LogicalVolume
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:LogicalVolume' => 'Logikai kötet',
	'Class:LogicalVolume+' => '~~',
	'Class:LogicalVolume/Attribute:name' => 'Név',
	'Class:LogicalVolume/Attribute:name+' => '~~',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '~~',
	'Class:LogicalVolume/Attribute:description' => 'Leírás',
	'Class:LogicalVolume/Attribute:description+' => '~~',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid szint',
	'Class:LogicalVolume/Attribute:raid_level+' => '~~',
	'Class:LogicalVolume/Attribute:size' => 'Méret',
	'Class:LogicalVolume/Attribute:size+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Tárolórendszer',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '~~',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Tárolórendszer név',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '~~',
	'Class:LogicalVolume/Attribute:servers_list' => 'Szerverek',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Szerverek, melyek ezt a kötetet használják',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtuális eszközök',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Virtuális eszközök, melyek ezt a kötetet használják',
));

//
// Class: lnkServerToVolume
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkServerToVolume' => 'Szerver / Kötet',
	'Class:lnkServerToVolume+' => '~~',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Kötet',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Kötet név',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Szerver',
	'Class:lnkServerToVolume/Attribute:server_id+' => '~~',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Szerver név',
	'Class:lnkServerToVolume/Attribute:server_name+' => '~~',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Felhasznált méret',
	'Class:lnkServerToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkVirtualDeviceToVolume' => 'Virtuális eszköz / Kötet',
	'Class:lnkVirtualDeviceToVolume+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Kötet',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Kötet név',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtuális eszköz',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Virtuális eszköz név',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '~~',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Felhasznált méret',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '~~',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkSanToDatacenterDevice' => 'SAN / Adatközpont eszköz',
	'Class:lnkSanToDatacenterDevice+' => '~~',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN switch név',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Eszköz',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Eszköz név',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN FC port',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '~~',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Eszköz FC port',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '~~',
));

//
// Class: Tape
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Tape' => 'Szalag',
	'Class:Tape+' => '~~',
	'Class:Tape/Attribute:name' => 'Név',
	'Class:Tape/Attribute:name+' => '~~',
	'Class:Tape/Attribute:description' => 'Leírás',
	'Class:Tape/Attribute:description+' => '~~',
	'Class:Tape/Attribute:size' => 'Méret',
	'Class:Tape/Attribute:size+' => '~~',
	'Class:Tape/Attribute:tapelibrary_id' => 'Szalagos tároló',
	'Class:Tape/Attribute:tapelibrary_id+' => '~~',
	'Class:Tape/Attribute:tapelibrary_name' => 'Szalagos tároló név',
	'Class:Tape/Attribute:tapelibrary_name+' => '~~',
));

//
// Class: NASFileSystem
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:NASFileSystem' => 'NAS Fájlrendszer',
	'Class:NASFileSystem+' => '~~',
	'Class:NASFileSystem/Attribute:name' => 'Név',
	'Class:NASFileSystem/Attribute:name+' => '~~',
	'Class:NASFileSystem/Attribute:description' => 'Leírás',
	'Class:NASFileSystem/Attribute:description+' => '~~',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid szint',
	'Class:NASFileSystem/Attribute:raid_level+' => '~~',
	'Class:NASFileSystem/Attribute:size' => 'Méret',
	'Class:NASFileSystem/Attribute:size+' => '~~',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '~~',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS név',
	'Class:NASFileSystem/Attribute:nas_name+' => '~~',
));

//
// Class: Software
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Software' => 'Szoftver',
	'Class:Software+' => '',
	'Class:Software/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Software/Attribute:name' => 'Név',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'gyártó',
	'Class:Software/Attribute:vendor+' => '~~',
	'Class:Software/Attribute:version' => 'Verzió',
	'Class:Software/Attribute:version+' => '~~',
	'Class:Software/Attribute:documents_list' => 'Dokumentumok',
	'Class:Software/Attribute:documents_list+' => 'A szoftver dokumentumai',
	'Class:Software/Attribute:type' => 'Típus',
	'Class:Software/Attribute:type+' => '~~',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Szerver',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server~~',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware~~',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Egyéb szoftver',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Other Software~~',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Szoftver',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software~~',
	'Class:Software/Attribute:type/Value:WebServer' => 'Webszerver',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web Server~~',
	'Class:Software/Attribute:softwareinstance_list' => 'Szoftverpéldányok',
	'Class:Software/Attribute:softwareinstance_list+' => 'A szoftver telepített példányai',
	'Class:Software/Attribute:softwarepatch_list' => 'Javítócsomagok',
	'Class:Software/Attribute:softwarepatch_list+' => 'A szoftver javítócsomagjai',
	'Class:Software/Attribute:softwarelicence_list' => 'Szoftver licencek',
	'Class:Software/Attribute:softwarelicence_list+' => 'A szoftver licencei',
));

//
// Class: Patch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Patch' => 'Javítócsomag',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Név',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Dokumentumok',
	'Class:Patch/Attribute:documents_list+' => 'A javítócsomag dokumentumai',
	'Class:Patch/Attribute:description' => 'Leírás',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Típus',
	'Class:Patch/Attribute:finalclass+' => 'A végső osztály neve',
));

//
// Class: OSPatch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OSPatch' => 'OS javítócsomag',
	'Class:OSPatch+' => '~~',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Eszközök',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Rendszerek, amelyekre telepítve lett ez az OS javítócsomag',
	'Class:OSPatch/Attribute:osversion_id' => 'OS verzió',
	'Class:OSPatch/Attribute:osversion_id+' => '~~',
	'Class:OSPatch/Attribute:osversion_name' => 'OS verzió név',
	'Class:OSPatch/Attribute:osversion_name+' => '~~',
));

//
// Class: SoftwarePatch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SoftwarePatch' => 'Szoftver javítócsomag',
	'Class:SoftwarePatch+' => '~~',
	'Class:SoftwarePatch/Attribute:software_id' => 'Szoftver',
	'Class:SoftwarePatch/Attribute:software_id+' => '~~',
	'Class:SoftwarePatch/Attribute:software_name' => 'Szoftver név',
	'Class:SoftwarePatch/Attribute:software_name+' => '~~',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Szoftverpéldányok',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Rendszerek, amelyekre telepítve van ez a szoftverjavítás',
));

//
// Class: Licence
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Licence' => 'Licensz',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Név',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Dokumentumok',
	'Class:Licence/Attribute:documents_list+' => 'A licenc dokumentumai',
	'Class:Licence/Attribute:org_id' => 'Tulajdonos szevezeti egység',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Szervezeti egység név',
	'Class:Licence/Attribute:organization_name+' => 'Általános név',
	'Class:Licence/Attribute:usage_limit' => 'Felhasználási korlátok',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Leírás',
	'Class:Licence/Attribute:description+' => '~~',
	'Class:Licence/Attribute:start_date' => 'Kezdő dátum',
	'Class:Licence/Attribute:start_date+' => '~~',
	'Class:Licence/Attribute:end_date' => 'Befejező dátum',
	'Class:Licence/Attribute:end_date+' => '~~',
	'Class:Licence/Attribute:licence_key' => 'Licenckulcs',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Öröklicenc',
	'Class:Licence/Attribute:perpetual+' => '~~',
	'Class:Licence/Attribute:perpetual/Value:no' => 'nem',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'no~~',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'igen',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'yes~~',
	'Class:Licence/Attribute:finalclass' => 'Típus',
	'Class:Licence/Attribute:finalclass+' => 'A végső osztály neve',
));

//
// Class: OSLicence
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OSLicence' => 'OS Licenc',
	'Class:OSLicence+' => '~~',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:OSLicence/Attribute:osversion_id' => 'OS verzió',
	'Class:OSLicence/Attribute:osversion_id+' => '~~',
	'Class:OSLicence/Attribute:osversion_name' => 'OS verzió név',
	'Class:OSLicence/Attribute:osversion_name+' => '~~',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtuális gépek',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Virtuális gépek, melyek ezt a licencet használják',
	'Class:OSLicence/Attribute:servers_list' => 'Szerverek',
	'Class:OSLicence/Attribute:servers_list+' => 'Szerverek, melyek ezt a licencet használják',
));

//
// Class: SoftwareLicence
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SoftwareLicence' => 'Szoftver licenc',
	'Class:SoftwareLicence+' => '~~',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s~~',
	'Class:SoftwareLicence/Attribute:software_id' => 'Szoftver',
	'Class:SoftwareLicence/Attribute:software_id+' => '~~',
	'Class:SoftwareLicence/Attribute:software_name' => 'Szoftver név',
	'Class:SoftwareLicence/Attribute:software_name+' => '~~',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Szoftverpéldányok',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Rendszerek, melyek ezt a licencet használják',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToLicence' => 'Dokumentum / Licenc',
	'Class:lnkDocumentToLicence+' => '~~',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licenc',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Licenc név',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '~~',
));

//
// Class: OSVersion
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OSVersion' => 'OS Verzió',
	'Class:OSVersion+' => '~~',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS család',
	'Class:OSVersion/Attribute:osfamily_id+' => '~~',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS család név',
	'Class:OSVersion/Attribute:osfamily_name+' => '~~',
));

//
// Class: OSFamily
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:OSFamily' => 'OS család',
	'Class:OSFamily+' => '~~',
));

//
// Class: Brand
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Brand' => 'Gyártó',
	'Class:Brand+' => '~~',
	'Class:Brand/Attribute:physicaldevices_list' => 'Fizikai eszközök',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Ennek a gyártónak a fizikai eszközei',
	'Class:Brand/UniquenessRule:name+' => '',
	'Class:Brand/UniquenessRule:name' => 'Ez a gyártó már létezik',
));

//
// Class: Model
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Model' => 'Modell',
	'Class:Model+' => '~~',
	'Class:Model/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Model/Attribute:brand_id' => 'Gyártó',
	'Class:Model/Attribute:brand_id+' => '~~',
	'Class:Model/Attribute:brand_name' => 'Gyártó név',
	'Class:Model/Attribute:brand_name+' => '~~',
	'Class:Model/Attribute:type' => 'Eszköz típus',
	'Class:Model/Attribute:type+' => '~~',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Áramforrás',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Áramforrás',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Lemez tömb',
	'Class:Model/Attribute:type/Value:DiskArray+' => '',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Készülékház',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure~~',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Egyéb eszköz',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP Phone~~',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobiltelefon',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Mobile Phone~~',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS~~',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Hálózati eszköz',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Network Device~~',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC~~',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU~~',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Periféria',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Peripheral~~',
	'Class:Model/Attribute:type/Value:Printer' => 'Nyomtató',
	'Class:Model/Attribute:type/Value:Printer+' => 'Printer~~',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack~~',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN switch~~',
	'Class:Model/Attribute:type/Value:Server' => 'Szerver',
	'Class:Model/Attribute:type/Value:Server+' => 'Server~~',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Tárolórendszer',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Storage System~~',
	'Class:Model/Attribute:type/Value:Tablet' => 'Táblagép',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet~~',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Szalagos tároló',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tape Library~~',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefon',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telephone~~',
	'Class:Model/Attribute:physicaldevices_list' => 'Fizikai eszközök',
	'Class:Model/Attribute:physicaldevices_list+' => 'Fizikai eszközök melyek ehhez a modellszámhoz tartoznak',
	'Class:Model/UniquenessRule:name_brand+' => 'A névnek egyedinek kell lennie a gyártón belül',
	'Class:Model/UniquenessRule:name_brand' => 'a gyártó ezen modellje már létezik',
));

//
// Class: NetworkDeviceType
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:NetworkDeviceType' => 'Hálózati eszköz típus',
	'Class:NetworkDeviceType+' => '~~',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Hálózati eszközök',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Ilyen típusú hálózati eszközök',
));

//
// Class: IOSVersion
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:IOSVersion' => 'IOS Verzió',
	'Class:IOSVersion+' => '~~',
	'Class:IOSVersion/Attribute:brand_id' => 'Gyártó',
	'Class:IOSVersion/Attribute:brand_id+' => '~~',
	'Class:IOSVersion/Attribute:brand_name' => 'Gyártó név',
	'Class:IOSVersion/Attribute:brand_name+' => '~~',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToPatch' => 'Dokumentum / Javítócsomag',
	'Class:lnkDocumentToPatch+' => '~~',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Javítócsomag',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Javítócsomag név',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '~~',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Szoftverpéldány / Szoftver javítócsomag',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Szoftver javítócsomag',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Szoftver javítócsomag név',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Szoftverpéldány',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '~~',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Szoftverpéldány név',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '~~',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Funkcionális CI / OS javítócsomag',
	'Class:lnkFunctionalCIToOSPatch+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS javítócsomag',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS javítócsomag név',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Funkcionális CI',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Funkcionális CI név',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToSoftware' => 'Dokumentum / Szoftver',
	'Class:lnkDocumentToSoftware+' => '~~',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Szoftver',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Szoftver név',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '~~',
));

//
// Class: Subnet
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Subnet' => 'Alhálózat',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s/%2$s~~',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Subnet/Attribute:description' => 'Leírás',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Alhálózat név',
	'Class:Subnet/Attribute:subnet_name+' => '~~',
	'Class:Subnet/Attribute:org_id' => 'Tulajdonos szevezeti egység',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Név',
	'Class:Subnet/Attribute:org_name+' => 'Általános név',
	'Class:Subnet/Attribute:ip' => 'IP cím',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP netmaszk',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLAN-ok',
	'Class:Subnet/Attribute:vlans_list+' => '~~',
));

//
// Class: VLAN
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '~~',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:VLAN/Attribute:vlan_tag+' => '~~',
	'Class:VLAN/Attribute:description' => 'Leírás',
	'Class:VLAN/Attribute:description+' => '~~',
	'Class:VLAN/Attribute:org_id' => 'Szervezeti egység',
	'Class:VLAN/Attribute:org_id+' => '~~',
	'Class:VLAN/Attribute:org_name' => 'Szervezeti egység név',
	'Class:VLAN/Attribute:org_name+' => 'Általános név',
	'Class:VLAN/Attribute:subnets_list' => 'Alhálozatok',
	'Class:VLAN/Attribute:subnets_list+' => '~~',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fizikai hálózati csatolók',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '~~',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkSubnetToVLAN' => 'Alhálózat / VLAN',
	'Class:lnkSubnetToVLAN+' => '~~',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Alhálózat',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Alhálózat IP cím',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Alhálózat név',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '~~',
));

//
// Class: NetworkInterface
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:NetworkInterface' => 'Hálózati csatoló',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Név',
	'Class:NetworkInterface/Attribute:name+' => '~~',
	'Class:NetworkInterface/Attribute:finalclass' => 'Típus',
	'Class:NetworkInterface/Attribute:finalclass+' => 'A végső osztály neve',
));

//
// Class: IPInterface
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:IPInterface' => 'IP csatoló',
	'Class:IPInterface+' => '~~',
	'Class:IPInterface/Attribute:ipaddress' => 'IP cím',
	'Class:IPInterface/Attribute:ipaddress+' => '~~',
	'Class:IPInterface/Attribute:macaddress' => 'MAC cím',
	'Class:IPInterface/Attribute:macaddress+' => '~~',
	'Class:IPInterface/Attribute:comment' => 'Megjegyzés',
	'Class:IPInterface/Attribute:coment+' => '~~',
	'Class:IPInterface/Attribute:ipgateway' => 'IP átjáró',
	'Class:IPInterface/Attribute:ipgateway+' => '~~',
	'Class:IPInterface/Attribute:ipmask' => 'IP netmaszk',
	'Class:IPInterface/Attribute:ipmask+' => '~~',
	'Class:IPInterface/Attribute:speed' => 'Sebesség',
	'Class:IPInterface/Attribute:speed+' => '~~',
));

//
// Class: PhysicalInterface
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:PhysicalInterface' => 'Fizikai csatoló',
	'Class:PhysicalInterface+' => '~~',
	'Class:PhysicalInterface/Name' => '%2$s %1$s~~',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Eszköz',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '~~',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Eszköz név',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '~~',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLAN-ok',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '~~',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Fizikai csatoló / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Fizikai csatoló',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Fizikai csatoló név',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Eszköz',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Eszköz név',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '~~',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '~~',
));


//
// Class: LogicalInterface
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:LogicalInterface' => 'Logikai csatoló',
	'Class:LogicalInterface+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtuális gép',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '~~',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtuális gép név',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '~~',
));

//
// Class: FiberChannelInterface
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:FiberChannelInterface' => 'FC csatoló',
	'Class:FiberChannelInterface+' => '~~',
	'Class:FiberChannelInterface/Attribute:speed' => 'Sebesség',
	'Class:FiberChannelInterface/Attribute:speed+' => '~~',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topológia',
	'Class:FiberChannelInterface/Attribute:topology+' => '~~',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Eszköz',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '~~',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Eszköz név',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '~~',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Kapcsolható CI / Hálózati eszköz',
	'Class:lnkConnectableCIToNetworkDevice+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Hálózati eszköz',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Hálózati eszköz név',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Csatlakoztatott eszköz',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Csatlakoztatott eszköz név',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Hálózati port',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Eszköz port',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Csatlakozás típus',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link~~',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'up link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link~~',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Alkalmazásmegoldás / Funkcionális CI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Alkalmazásmegoldás',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Alkalmazásmegoldás név',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Funkcionális CI',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Funkcionális CI név',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '~~',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Alkalmazásmegoldás / Üzleti folyamat',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Üzleti folyamat',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Üzleti folyamat név',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Alkalmazásmegoldás',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '~~',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Alkalmazásmegoldás név',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '~~',
));

//
// Class: Group
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Group' => 'Csoport',
	'Class:Group+' => '',
	'Class:Group/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Group/Attribute:name' => 'Név',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Állapot',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:Group/Attribute:status/Value:implementation+' => '',
	'Class:Group/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:Group/Attribute:status/Value:obsolete+' => '',
	'Class:Group/Attribute:status/Value:production' => 'Használatban',
	'Class:Group/Attribute:status/Value:production+' => '',
	'Class:Group/Attribute:org_id' => 'Szevezeti egység',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Név',
	'Class:Group/Attribute:owner_name+' => '',
	'Class:Group/Attribute:description' => 'Leírás',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Típus',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Fölérendelt csoport',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Név',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Kapcsolódó CI-k',
	'Class:Group/Attribute:ci_list+' => 'All the configuration items linked to this group~~',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Szülő csoport',
	'Class:Group/Attribute:parent_id_friendlyname+' => '~~',
));

//
// Class: lnkGroupToCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkGroupToCI' => 'Csoport / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s~~',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Csoport',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Név',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Név',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Indoklás',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Server:baseinfo' => 'Általános információ',
	'Server:Date' => 'Dátumok',
	'Server:moreinfo' => 'További információ',
	'Server:otherinfo' => 'Other information~~',
	'Server:power' => 'Áramforrás',
	'Class:Subnet/Tab:IPUsage' => 'IP felhasználás',
	'Class:Subnet/Tab:IPUsage+' => 'Which IP within this Subnet are used or not~~',
	'Class:Subnet/Tab:IPUsage-explain' => 'A hálózati csatolók a következő tartományba esnek: <em>%1$s</em> - <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Szabad IP címek',
	'Class:Subnet/Tab:FreeIPs-count' => 'Szabad IP címek: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => '10 szabad IP cím kivonata',
	'Class:Document:PreviewTab' => 'Előnézet',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToFunctionalCI' => 'Dokumentum / Funkcionális CI',
	'Class:lnkDocumentToFunctionalCI+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Funkcionális CI',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Funkcionális CI név',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '~~',
));

//
// Application Menu
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:Application' => 'Alkalmazások',
	'Menu:Application+' => '',
	'Menu:DBServer' => 'Adatbázis szerverek',
	'Menu:DBServer+' => '',
	'Menu:BusinessProcess' => 'Üzleti folyamatok',
	'Menu:BusinessProcess+' => '',
	'Menu:ApplicationSolution' => 'Egyedi alkalmazások',
	'Menu:ApplicationSolution+' => '',
	'Menu:ConfigManagementSoftware' => 'Alkalmazás kezelés',
	'Menu:Licence' => 'Licencek',
	'Menu:Licence+' => '',
	'Menu:Patch' => 'Frissítések',
	'Menu:Patch+' => '',
	'Menu:ApplicationInstance' => 'Telepített szoftverek',
	'Menu:ApplicationInstance+' => '',
	'Menu:ConfigManagementHardware' => 'Infrastruktúra kezelés',
	'Menu:Subnet' => 'Alhálózatok',
	'Menu:Subnet+' => '',
	'Menu:NetworkDevice' => 'Hálózati eszközök',
	'Menu:NetworkDevice+' => '',
	'Menu:Server' => 'Szerverek',
	'Menu:Server+' => '',
	'Menu:Printer' => 'Nyomtatók',
	'Menu:Printer+' => '',
	'Menu:MobilePhone' => 'Mobiltelefonok',
	'Menu:MobilePhone+' => '',
	'Menu:PC' => 'PC-k',
	'Menu:PC+' => '',
	'Menu:NewCI' => 'Új CI',
	'Menu:NewCI+' => '',
	'Menu:SearchCIs' => 'CI keresés',
	'Menu:SearchCIs+' => '',
	'Menu:ConfigManagement:Devices' => 'Eszközök',
	'Menu:ConfigManagement:AllDevices' => 'Infrastruktúra',
	'Menu:ConfigManagement:virtualization' => 'Virtualizáció',
	'Menu:ConfigManagement:EndUsers' => 'Végfelhasználói eszközök',
	'Menu:ConfigManagement:SWAndApps' => 'Szoftverek és egyedi alkalmazások',
	'Menu:ConfigManagement:Misc' => 'Egyéb',
	'Menu:Group' => 'CI csoportok',
	'Menu:Group+' => '',
	'Menu:OSVersion' => 'OS verziók',
	'Menu:OSVersion+' => '~~',
	'Menu:Software' => 'Szoftverkatalógus',
	'Menu:Software+' => 'Software catalog~~',
));
?>

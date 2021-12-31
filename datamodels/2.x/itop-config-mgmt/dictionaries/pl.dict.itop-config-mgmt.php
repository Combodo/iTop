<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Relation:impacts/Description' => 'Elementy, na które ma wpływ',
	'Relation:impacts/DownStream' => 'Wpływa na...',
	'Relation:impacts/DownStream+' => 'Elementy, na które ma wpływ',
	'Relation:impacts/UpStream' => 'Zależy od......',
	'Relation:impacts/UpStream+' => 'Elementy wpływające',
	// Legacy entries
	'Relation:depends on/Description' => 'Elementy wpływające',
	'Relation:depends on/DownStream' => 'Zależy od...',
	'Relation:depends on/UpStream' => 'Wpływa na...',
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkContactToFunctionalCI' => 'Połączenie Kontakt / Konfiguracja',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Nazwa kontaktu',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:FunctionalCI' => 'Konfiguracje',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nazwa',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Opis',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organizacja',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Nazwa organizacji',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Nazwa zwyczajowa',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Krytyczność dla biznesu',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'wysoka',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'wysoka',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'niska',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'niska',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'średnia',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'średnia',
	'Class:FunctionalCI/Attribute:move2production' => 'Przenieś do użytkowanych',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Kontakty',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'Wszystkie kontakty dla tego elementu konfiguracji',
	'Class:FunctionalCI/Attribute:documents_list' => 'Dokumenty',
	'Class:FunctionalCI/Attribute:documents_list+' => 'Wszystkie dokumenty powiązane z tym elementem konfiguracji',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Rozwiązania aplikacyjne',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'Wszystkie rozwiązania aplikacyjne zależne od tego elementu konfiguracji',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Oprogramowania',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'Całe oprogramowanie zainstalowane w tym elemencie konfiguracji',
	'Class:FunctionalCI/Attribute:finalclass' => 'Podklasa konfiguracji',
	'Class:FunctionalCI/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Aktywne zgłoszenia',
));

//
// Class: PhysicalDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PhysicalDevice' => 'Urządzenie fizyczne',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Numer seryjny',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Lokalizacja',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Nazwa lokalizacji',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'wdrażane',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'wdrażane',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'wycofane',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'wycowane',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'użytkowane',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'użytkowane',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'na zapasie',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'na zapasie',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Marka',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Nazwa marki',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Nazwa modelu',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Numer zasobu',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Data zakupu',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'Koniec gwarancji',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Rack' => 'Szafa',
	'Class:Rack+' => '',
	'Class:Rack/Attribute:nb_u' => 'Półki szafy',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Urządzenia',
	'Class:Rack/Attribute:device_list+' => 'Wszystkie fizyczne urządzenia zamontowane w tej szafie',
	'Class:Rack/Attribute:enclosure_list' => 'Obudowy',
	'Class:Rack/Attribute:enclosure_list+' => 'Wszystkie obudowy w tej szafie',
));

//
// Class: TelephonyCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TelephonyCI' => 'Konfiguracje telefoniczne',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Numer telefonu',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Phone' => 'Telefon',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:MobilePhone' => 'Telefon komórkowy',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'PIN sprzętu',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:IPPhone' => 'Telefon IP',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ConnectableCI' => 'Konfiguracje podłączeń',
	'Class:ConnectableCI+' => 'Konfiguracje fizyczne',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Urządzenia sieciowe',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'Wszystkie urządzenia sieciowe podłączone do tego urządzenia',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Interfejsy sieciowe',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'Wszystkie fizyczne interfejsy sieciowe',
));

//
// Class: DatacenterDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DatacenterDevice' => 'Urządzenie Datacenter',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Szafa',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Nazwa szafy',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Obudowa',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Nazwa obudowy',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Półki szafy',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Zarządzanie IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'Źródło zasilania A',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'Nazwa źródła zasilania A',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'Źródło zasilania B',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'Nazwa źródła zasilania B',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'Porty FC',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'Wszystkie interfejsy Fibre Channel dla tego urządzenia',
	'Class:DatacenterDevice/Attribute:san_list' => 'Przełączniki SAN',
	'Class:DatacenterDevice/Attribute:san_list+' => 'Wszystkie przełączniki SAN podłączone do tego urządzenia',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Nadmiarowość zasilania',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'Urządzenie działa, jeśli co najmniej jedno złącze zasilania (A lub B) jest włączone',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'Urządzenie działa, jeśli wszystkie jego połączenia zasilania są włączone',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'Urządzenie działa, jeśli przynajmniej %1$s %% z jego połączeń zasilania są wyłączone',
));

//
// Class: NetworkDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:NetworkDevice' => 'Urządzenie sieciowe',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Typ urządzenia sieciowego',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Nazwa typu urządzenia sieciowego',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Urządzenia',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'Wszystkie urządzenia podłączone do tego urządzenia sieciowego',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'Wersja IOS',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'Nazwa wersji IOS',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Server' => 'Serwer',
	'Class:Server+' => '',
	'Class:Server/Attribute:osfamily_id' => 'Rodzina OS',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'Nazwa rodziny OS',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'Wersja OS',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'Nazwa wersji OS',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'Licencja OS',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'Nazwa licencji OS',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Woluminy logiczne',
	'Class:Server/Attribute:logicalvolumes_list+' => 'Wszystkie woluminy logiczne podłączone do tego serwera',
));

//
// Class: StorageSystem
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:StorageSystem' => 'System pamięci masowej',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Woluminy logiczne',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'Wszystkie woluminy logiczne w tym systemie pamięci masowej',
));

//
// Class: SANSwitch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:SANSwitch' => 'Przełącznik SAN',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Urządzenia Datacenter',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'Wszystkie urządzenia Datacenter podłączone do tego przełącznika SAN',
));

//
// Class: TapeLibrary
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:TapeLibrary' => 'Biblioteka taśm',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Taśmy',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'Wszystkie taśmy w bibliotece taśm',
));

//
// Class: NAS
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Systemy plików',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'Wszystkie systemy plików na tym serwerze NAS',
));

//
// Class: PC
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PC' => 'Komputer PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:osfamily_id' => 'Rodzina OS',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'Nazwa rodziny OS',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'Wersja OS',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'Nazwa wersji OS',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Typ',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'desktop',
	'Class:PC/Attribute:type/Value:laptop' => 'laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'laptop',
));

//
// Class: Printer
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Printer' => 'Drukarka',
	'Class:Printer+' => '',
));

//
// Class: PowerConnection
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PowerConnection' => 'Podłączenie zasilania',
	'Class:PowerConnection+' => '',
));

//
// Class: PowerSource
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PowerSource' => 'Źródło zasilania',
	'Class:PowerSource+' => '',
	'Class:PowerSource/Attribute:pdus_list' => 'PDU',
	'Class:PowerSource/Attribute:pdus_list+' => 'Wszystkie PDU korzystające z tego źródła zasilania',
));

//
// Class: PDU
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/Attribute:rack_id' => 'Szafa',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Nazwa szafy',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Dystrybucja zasilania',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Nazwa dystrybucji zasilania',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Peripheral' => 'Peryferia',
	'Class:Peripheral+' => '',
));

//
// Class: Enclosure
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Enclosure' => 'Obudowa',
	'Class:Enclosure+' => '',
	'Class:Enclosure/Attribute:rack_id' => 'Szafa',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Nazwa szafy',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Półki',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Urządzenia',
	'Class:Enclosure/Attribute:device_list+' => 'Wszystkie urządzenia w tej obudowie',
));

//
// Class: ApplicationSolution
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:ApplicationSolution' => 'Zastosowane rozwiązanie',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'Konfiguracje',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'Wszystkie elementy konfiguracji, które składają się na to rozwiązanie',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Procesy biznesowe',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'Wszystkie procesy biznesowe w zależności od tego rozwiązania',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'aktywne',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'aktywne',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'nieaktywne',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'nieaktywne',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Analiza wpływu: konfiguracja redundancji',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'Rozwiązanie działa, jeśli wszystkie elementy konfiguracji działają',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'Rozwiązanie jest gotowe, jeśli przynajmniej %1$s element(y) konfiguracji jest(są) włączony(e)',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'Rozwiązanie jest gotowe, jeśli przynajmniej %1$s %% elementów konfiguracji jest włączonych',
));

//
// Class: BusinessProcess
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:BusinessProcess' => 'Proces biznesowy',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Rozwiązania aplikacyjne',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'Wszystkie rozwiązania aplikacyjne, które mają wpływ na ten proces biznesowy',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'aktywny',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'aktywny',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'nieaktywny',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'nieaktywny',
));

//
// Class: SoftwareInstance
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:SoftwareInstance' => 'Instancja oprogramowania',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'System',
	'Class:SoftwareInstance/Attribute:system_id+' => '',
	'Class:SoftwareInstance/Attribute:system_name' => 'Nazwa systemu',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Oprogramowanie',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Nazwa oprogramowania',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Licencja oprogramowania',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Nazwa licencji na oprogramowanie',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Poprawka',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'aktywna',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'aktywna',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'nieaktywna',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'nieaktywna',
));

//
// Class: Middleware
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Middleware' => 'Oprogramowanie pośredniczące',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Instancje oprogramowania pośredniczące',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'Wszystkie wystąpienia oprogramowania pośredniczącego zapewniane przez to oprogramowanie pośredniczące',
));

//
// Class: DBServer
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DBServer' => 'Serwer bazy danych',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'Schematy bazy danych',
	'Class:DBServer/Attribute:dbschema_list+' => 'Wszystkie schematy bazy danych dla tego serwera bazy danych',
));

//
// Class: WebServer
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:WebServer' => 'Serwer WWW',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Aplikacje WWW',
	'Class:WebServer/Attribute:webapp_list+' => 'Wszystkie aplikacje WWW dostępne na tym serwerze WWW',
));

//
// Class: PCSoftware
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PCSoftware' => 'Oprogramowanie komputerowe',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:OtherSoftware' => 'Inne oprogramowanie',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:MiddlewareInstance' => 'Instancja oprogramowania pośredniczącego',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Oprogramowanie pośredniczące',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Nazwa oprogramowania pośredniczącego',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:DatabaseSchema' => 'Schemat bazy danych',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'Serwer bazy danych',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'Nazwa serwera bazy danych',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:WebApplication' => 'Aplikacja WWW',
	'Class:WebApplication+' => '',
	'Class:WebApplication/Attribute:webserver_id' => 'Serwer WWW',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Nazwa serwera WWW',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:VirtualDevice' => 'Urządzenie wirtualne',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'wdrażane',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'wdrażane',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'wycofane',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'wycofane',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'użytkowane',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'użytkowane',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'na zapasie',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'na zapasie',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Woluminy logiczne',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'Wszystkie woluminy logiczne używane przez to urządzenie',
));

//
// Class: VirtualHost
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:VirtualHost' => 'Host wirtualny',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Maszyny wirtualne',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'Wszystkie maszyny wirtualne hostowane przez tego hosta',
));

//
// Class: Hypervisor
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Hypervisor' => 'Nadzorca (Hiperwizor)',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farma',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Nazwa farmy',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Serwer',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Nazwa serwera',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Farm' => 'Farma',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Nadzorcy (Hiperwizory)',
	'Class:Farm/Attribute:hypervisor_list+' => 'Wszystkie hiperwizory, które tworzą tę farmę',
	'Class:Farm/Attribute:redundancy' => 'Duża dostępność',
	'Class:Farm/Attribute:redundancy/disabled' => 'Farma działa, jeśli wszystkie hiperwizory działają',
	'Class:Farm/Attribute:redundancy/count' => 'Farma działa, jeśli co najmniej %1$s hiperwizor(y) działa(ją)',
	'Class:Farm/Attribute:redundancy/percent' => 'Farma działa, jeśli co najmniej %1$s %% hiperwizorów działa',
));

//
// Class: VirtualMachine
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:VirtualMachine' => 'Maszyna wirtualna',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Host wirtualny',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Nazwa hosta wirtualnego',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'Rodzina OS',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'Nazwa rodziny OS',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'Wersja OS',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'Nazwa wersji OS',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'Licencja OS',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'Nazwa licencji OS',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Interfejsy sieciowe',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'Wszystkie logiczne interfejsy sieciowe',
));

//
// Class: LogicalVolume
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:LogicalVolume' => 'Wolumin logiczny',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Nazwa',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Opis',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Poziom raid',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Rozmiar',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'System magazynowania',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Nazwa systemu pamięci masowej',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Serwery',
	'Class:LogicalVolume/Attribute:servers_list+' => 'Wszystkie serwery korzystające z tego woluminu',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Urządzenia wirtualne',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'Wszystkie urządzenia wirtualne korzystające z tego woluminu',
));

//
// Class: lnkServerToVolume
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkServerToVolume' => 'Połączenie serwer / wolumin',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Wolumin',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Nazwa woluminu',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Serwer',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Nazwa serwera',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Używany rozmiar',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkVirtualDeviceToVolume' => 'Połączenie urządzenie wirtualne / wolumin',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Wolumin',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Nazwa woluminu',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Urządzenie wirtualne',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Nazwa urządzenia wirtualnego',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Używany rozmiar',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkSanToDatacenterDevice' => 'Połączenie przełącznik SAN / Datacenter',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'Przełącznik SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'Nazwa przełącznika SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Urządzenie Datacenter',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Nazwa urządzenia Datacenter',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'Port SAN',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Urządzenie fc (FibreChannel)',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Tape' => 'Taśma',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Nazwa',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Opis',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Rozmiar',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Biblioteka taśm',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Nazwa biblioteki taśm',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:NASFileSystem' => 'System plików NAS',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Nazwa',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Opis',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Poziom Raid',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Rozmiar',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'Nazwa NAS',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Software' => 'Oprogramowanie',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nazwa',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Wydawca',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Wersja',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Dokumenty',
	'Class:Software/Attribute:documents_list+' => 'Wszystkie dokumenty powiązane z tym oprogramowaniem',
	'Class:Software/Attribute:type' => 'Typ',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'Serwer bazy danych',
	'Class:Software/Attribute:type/Value:DBServer+' => 'Serwer bazy danych',
	'Class:Software/Attribute:type/Value:Middleware' => 'Oprogramowanie pośredniczące',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Oprogramowanie pośredniczące',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Inne oprogramowanie',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Inne oprogramowanie',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'Oprogramowanie komputerowe',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'Oprogramowanie komputerowe',
	'Class:Software/Attribute:type/Value:WebServer' => 'Serwer WWW',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Serwer WWW',
	'Class:Software/Attribute:softwareinstance_list' => 'Instancje oprogramowania',
	'Class:Software/Attribute:softwareinstance_list+' => 'Wszystkie wystąpienia dla tego oprogramowania',
	'Class:Software/Attribute:softwarepatch_list' => 'Poprawki do oprogramowania',
	'Class:Software/Attribute:softwarepatch_list+' => 'Wszystkie poprawki do tego oprogramowania',
	'Class:Software/Attribute:softwarelicence_list' => 'Licencje na oprogramowanie',
	'Class:Software/Attribute:softwarelicence_list+' => 'Wszystkie licencje na to oprogramowanie',
));

//
// Class: Patch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Patch' => 'Poprawka',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nazwa',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Dokumenty',
	'Class:Patch/Attribute:documents_list+' => 'Wszystkie dokumenty powiązane z tą poprawką',
	'Class:Patch/Attribute:description' => 'Opis',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Podklasa poprawki',
	'Class:Patch/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
));

//
// Class: OSPatch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:OSPatch' => 'Poprawka OS',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Urządzenia',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'Wszystkie systemy, w których jest zainstalowana ta poprawka',
	'Class:OSPatch/Attribute:osversion_id' => 'Wersja OS',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'Nazwa wersji OS',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:SoftwarePatch' => 'Poprawka oprogramowania',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Oprogramowanie',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Nazwa oprogramowania',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Instancje oprogramowania',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'Wszystkie systemy, w których jest zainstalowana ta poprawka oprogramowania',
));

//
// Class: Licence
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Licence' => 'Licencja',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:name' => 'Nazwa',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Dokumenty',
	'Class:Licence/Attribute:documents_list+' => 'Wszystkie dokumenty powiązane z tą licencją',
	'Class:Licence/Attribute:org_id' => 'Organizacja',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Nazwa organizacji',
	'Class:Licence/Attribute:organization_name+' => 'Nazwa zwyczajowa',
	'Class:Licence/Attribute:usage_limit' => 'Limit wykorzystania',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Opis',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Data rozpoczęcia',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'Data zakończenia',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Klucz',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Wieczysty',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'nie',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'nie',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'tak',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'tak',
	'Class:Licence/Attribute:finalclass' => 'Podklasa licencji',
	'Class:Licence/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
));

//
// Class: OSLicence
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:OSLicence' => 'Licencja OS',
	'Class:OSLicence+' => '',
	'Class:OSLicence/Attribute:osversion_id' => 'Wersja OS',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'Nazwa wersji OS',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Maszyny wirtualne',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'Wszystkie maszyny wirtualne, na których jest używana ta licencja',
	'Class:OSLicence/Attribute:servers_list' => 'Serwery',
	'Class:OSLicence/Attribute:servers_list+' => 'Wszystkie serwery, na których jest używana ta licencja',
));

//
// Class: SoftwareLicence
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:SoftwareLicence' => 'Licencja oprogramowania',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/Attribute:software_id' => 'Oprogramowanie',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Nazwa oprogramowania',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Instancje oprogramowania',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'Wszystkie systemy, na których używana jest ta licencja',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkDocumentToLicence' => 'Połączenie dokument / licencjia',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'Licencja',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'Nazwa licencji',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Nazwa dokumentu',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: OSVersion
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:OSVersion' => 'Wersja OS',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'Rodzina OS',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'Nazwa rodziny OS',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:OSFamily' => 'Rodzina OS',
	'Class:OSFamily+' => '',
));

//
// Class: Brand
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Brand' => 'Marka',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Urządzenia fizyczne',
	'Class:Brand/Attribute:physicaldevices_list+' => 'Wszystkie fizyczne urządzenia tej marki',
	'Class:Brand/UniquenessRule:name+' => 'Nazwa musi być niepowtarzalna',
	'Class:Brand/UniquenessRule:name' => 'Ta marka już istnieje',
));

//
// Class: Model
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/Attribute:brand_id' => 'Marka',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Nazwa marki',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:type' => 'Typ urządzenia',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Źródło zasilania',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Źródło zasilania',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Macierz dysków',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Macierz dysków',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Obudowa',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Obudowa',
	'Class:Model/Attribute:type/Value:IPPhone' => 'Telefon IP',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'Telefon IP',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Telefon komórkowy',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Telefon komórkowy',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Urządzenie sieciowe',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Urządzenie sieciowe',
	'Class:Model/Attribute:type/Value:PC' => 'Komputer PC',
	'Class:Model/Attribute:type/Value:PC+' => 'Komputer PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Peryferyja',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Peryferyja',
	'Class:Model/Attribute:type/Value:Printer' => 'Drukarka',
	'Class:Model/Attribute:type/Value:Printer+' => 'Drukarka',
	'Class:Model/Attribute:type/Value:Rack' => 'Szafa',
	'Class:Model/Attribute:type/Value:Rack+' => 'Szafa',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'Przełącznik SAN',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'Przełącznik SAN',
	'Class:Model/Attribute:type/Value:Server' => 'Serwer',
	'Class:Model/Attribute:type/Value:Server+' => 'Serwer',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'System magazynowania',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'System magazynowania',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Biblioteka taśm',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Biblioteka taśm',
	'Class:Model/Attribute:type/Value:Phone' => 'Telefon',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telefon',
	'Class:Model/Attribute:physicaldevices_list' => 'Urządzenia fizyczne',
	'Class:Model/Attribute:physicaldevices_list+' => 'Wszystkie fizyczne urządzenia odpowiadające temu modelowi',
	'Class:Model/UniquenessRule:name_brand+' => 'Nazwa musi być niepowtarzalna w ramach marki',
	'Class:Model/UniquenessRule:name_brand' => 'ten model już istnieje dla tej marki',
));

//
// Class: NetworkDeviceType
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:NetworkDeviceType' => 'Typ urządzenia sieciowego',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Urządzenia sieciowe',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'Wszystkie urządzenia sieciowe odpowiadające temu typowi',
));

//
// Class: IOSVersion
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:IOSVersion' => 'Wersja IOS',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Marka',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Nazwa marki',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkDocumentToPatch' => 'Połączenie dokument / poprawka',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Poprawka',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Nazwa poprawki',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Nazwa dokumentu',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Połączenie instancja oprogramowania / poprawkę oprogramowania',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Poprawka oprogramowania',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Nazwa poprawki oprogramowania',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Instancja oprogramowania',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Nazwa instancji oprogramowania',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Połączenie Konfiguracja / Poprawka OS',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'Poprawka OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'Nazwa poprawki OS',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkDocumentToSoftware' => 'Połączenie dokument / oprogramowanie',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Oprogramowanie',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Nazwa oprogramowania',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Nazwa dokumentu',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Subnet' => 'Podsieć',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:description' => 'Opis',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Nazwa podsieci',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organizacja właścicielska',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Nazwa',
	'Class:Subnet/Attribute:org_name+' => 'Nazwa zwyczajowa',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Maska IP',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'Sieci VLAN',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:VLAN' => 'Sieć VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'Tag sieci VLAN',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Opis',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organizacja',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Nazwa organizacji',
	'Class:VLAN/Attribute:org_name+' => 'Nazwa zwyczajowa',
	'Class:VLAN/Attribute:subnets_list' => 'Podsieci',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Fizyczne interfejsy sieciowe',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkSubnetToVLAN' => 'Połączenie podsieć / sieć VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Podsieć',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'IP podsieci',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Nazwa podsieci',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'Sieć VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'Tag sieci VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:NetworkInterface' => 'Interfejs sieciowy',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Nazwa',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'Podklasa interfejsu sieciowego',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
));

//
// Class: IPInterface
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:IPInterface' => 'Interfejs IP',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'Adres IP',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'Adres MAC',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Komentarz',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'Brama IP',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'Maska IP',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Prędkość',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:PhysicalInterface' => 'Interfejs fizyczny',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Urządzenie',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Nazwa urządzenia',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'Sieci VLAN',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Połączenie interfejs fizyczny / sieć VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Interfejs fizyczny',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Nazwa interfejsu fizycznego',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Urządzenie',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Nazwa urządzenia',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'Sieć VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'Tag sieci VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:LogicalInterface' => 'Interfejs logiczny',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Maszyna wirtualna',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Nazwa maszyny wirtualnej',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:FiberChannelInterface' => 'Interfejs Fibre Channel',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Prędkość',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Typologia',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Urządzenie',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Nazwa urządzenia',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Połączenie Konfiguracja podłączeń / Urządzenie sieciowe',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Urządzenie sieciowe',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Nazwa urządzenia sieciowego',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Podłączone urządzenie',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Nazwa podłączonego urządzenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Port sieciowy',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Port urządzenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Rodzaj połączenia',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'rozłączone',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'rozłączone',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'połączone',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'połączone',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Połączenie Zastosowane rozwiązanie / Konfiguracja',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Zastosowane rozwiązanie',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Nazwa zastosowanego rozwiązania',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Połączenie Zastosowane rozwiązanie / proces biznesowy',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Proces biznesowy',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Nazwa procesu biznesowego',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Zastosowane rozwiązanie',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Nazwa zastosowanego rozwiązania',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: Group
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Group' => 'Grupa',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Nazwa',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'wdrażane',
	'Class:Group/Attribute:status/Value:implementation+' => 'wdrożene',
	'Class:Group/Attribute:status/Value:obsolete' => 'wycofane',
	'Class:Group/Attribute:status/Value:obsolete+' => 'wycofane',
	'Class:Group/Attribute:status/Value:production' => 'użytkowane',
	'Class:Group/Attribute:status/Value:production+' => 'użytkowane',
	'Class:Group/Attribute:org_id' => 'Organizacja',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Nazwa',
	'Class:Group/Attribute:owner_name+' => 'Nazwa zwyczajowa',
	'Class:Group/Attribute:description' => 'Opis',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Typ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Grupa nadrzędna',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Nazwa',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Połączone konfiguracje',
	'Class:Group/Attribute:ci_list+' => 'Wszystkie elementy konfiguracji połączone z tą grupą',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Grupa nadrzędna',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkGroupToCI' => 'Połączenie Grupa / Konfiguracja',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grupa',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nazwa',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'Konfiguracja',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nazwa',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Powód',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Server:baseinfo' => 'Informacje ogólne',
	'Server:Date' => 'Daty',
	'Server:moreinfo' => 'Więcej informacji',
	'Server:otherinfo' => 'Inne informacje',
	'Server:power' => 'Zasilanie',
	'Class:Subnet/Tab:IPUsage' => 'Wykorzystanie adresu IP',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfejsy z adresem IP w zakresie: <em>%1$s</em> do <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Wolne adresy IP',
	'Class:Subnet/Tab:FreeIPs-count' => 'Wolne adresy IP: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Oto fragment 10 bezpłatnych adresów IP',
	'Class:Document:PreviewTab' => 'Podgląd',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkDocumentToFunctionalCI' => 'Połączenie Dokument / Konfiguracja',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Nazwa dokumentu',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Application Menu
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:Application' => 'Aplikacje',
	'Menu:Application+' => 'Wszystkie aplikacje',
	'Menu:DBServer' => 'Serwery baz danych',
	'Menu:DBServer+' => 'Serwery baz danych',
	'Menu:BusinessProcess' => 'Procesy biznesowe',
	'Menu:BusinessProcess+' => 'Wszystkie procesy biznesowe',
	'Menu:ApplicationSolution' => 'Rozwiązania aplikacyjne',
	'Menu:ApplicationSolution+' => 'Wszystkie rozwiązania aplikacyjne',
	'Menu:ConfigManagementSoftware' => 'Zarządzanie aplikacją',
	'Menu:Licence' => 'Licencje',
	'Menu:Licence+' => 'Wszystkie licencje',
	'Menu:Patch' => 'Poprawki',
	'Menu:Patch+' => 'Wszystkie poprawki',
	'Menu:ApplicationInstance' => 'Zainstalowane oprogramowanie',
	'Menu:ApplicationInstance+' => 'Aplikacje i serwery baz danych',
	'Menu:ConfigManagementHardware' => 'Zarządzanie infrastrukturą',
	'Menu:Subnet' => 'Podsieci',
	'Menu:Subnet+' => 'Wszystkie podsieci',
	'Menu:NetworkDevice' => 'Urządzenia sieciowe',
	'Menu:NetworkDevice+' => 'Wszystkie urządzenia sieciowe',
	'Menu:Server' => 'Serwery',
	'Menu:Server+' => 'Wszystkie serwery',
	'Menu:Printer' => 'Drukarki',
	'Menu:Printer+' => 'Wszystkie drukarki',
	'Menu:MobilePhone' => 'Telefony komórkowe',
	'Menu:MobilePhone+' => 'Wszystkie telefony komórkowe',
	'Menu:PC' => 'Komputery osobiste',
	'Menu:PC+' => 'Wszystkie komputery osobiste',
	'Menu:NewCI' => 'Nowa konfiguracja',
	'Menu:NewCI+' => 'Nowa konfiguracja',
	'Menu:SearchCIs' => 'Wyszukaj konfiguracje',
	'Menu:SearchCIs+' => 'Wyszukaj konfiguracje',
	'Menu:ConfigManagement:Devices' => 'Urządzenia',
	'Menu:ConfigManagement:AllDevices' => 'Infrastruktura',
	'Menu:ConfigManagement:virtualization' => 'Wirtualizacja',
	'Menu:ConfigManagement:EndUsers' => 'Urządzenia użytkownika końcowego',
	'Menu:ConfigManagement:SWAndApps' => 'Oprogramowanie i aplikacje',
	'Menu:ConfigManagement:Misc' => 'Różne',
	'Menu:Group' => 'Grupy konfiguracji',
	'Menu:Group+' => 'Grupy konfiguracji',
	'Menu:OSVersion' => 'Wersje OS',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Katalog oprogramowania',
	'Menu:Software+' => 'Katalog oprogramowania',
));
?>

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

Dict::Add('EN US', 'English', 'English', array(
	'Relation:impacts/Description'    => 'Elements impacted by',
	'Relation:impacts/DownStream'     => 'Impacts...',
	'Relation:impacts/DownStream+'    => 'Elements impacted by',
	'Relation:impacts/UpStream'       => 'Depends on......',
	'Relation:impacts/UpStream+'      => 'Elements impacting',
	// Legacy entries
	'Relation:depends on/Description' => 'Elements impacting',
	'Relation:depends on/DownStream'  => 'Depends on...',
	'Relation:depends on/UpStream'    => 'Impacts...',
	'Relation:impacts/LoadData'       => 'Load data',
	'Relation:impacts/NoFilteredData' => 'please select objects and load data',
	'Relation:impacts/FilteredData'   => 'Filtered data',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactToFunctionalCI' => 'Link Contact / FunctionalCI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Contact name',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:FunctionalCI' => 'Functional CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Name',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:description' => 'Description',
	'Class:FunctionalCI/Attribute:description+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organization',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:organization_name' => 'Organization name',
	'Class:FunctionalCI/Attribute:organization_name+' => 'Common name',
	'Class:FunctionalCI/Attribute:business_criticity' => 'Business criticality',
	'Class:FunctionalCI/Attribute:business_criticity+' => '',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high' => 'high',
	'Class:FunctionalCI/Attribute:business_criticity/Value:high+' => 'high',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low' => 'low',
	'Class:FunctionalCI/Attribute:business_criticity/Value:low+' => 'low',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium' => 'medium',
	'Class:FunctionalCI/Attribute:business_criticity/Value:medium+' => 'medium',
	'Class:FunctionalCI/Attribute:move2production' => 'Move to production date',
	'Class:FunctionalCI/Attribute:move2production+' => '',
	'Class:FunctionalCI/Attribute:contacts_list' => 'Contacts',
	'Class:FunctionalCI/Attribute:contacts_list+' => 'All the contacts for this configuration item',
	'Class:FunctionalCI/Attribute:documents_list' => 'Documents',
	'Class:FunctionalCI/Attribute:documents_list+' => 'All the documents linked to this configuration item',
	'Class:FunctionalCI/Attribute:applicationsolution_list' => 'Application solutions',
	'Class:FunctionalCI/Attribute:applicationsolution_list+' => 'All the application solutions depending on this configuration item',
	'Class:FunctionalCI/Attribute:softwares_list' => 'Softwares',
	'Class:FunctionalCI/Attribute:softwares_list+' => 'All the softwares installed on this configuration item',
	'Class:FunctionalCI/Attribute:finalclass' => 'CI sub-class',
	'Class:FunctionalCI/Attribute:finalclass+' => 'Name of the final class',
	'Class:FunctionalCI/Tab:OpenedTickets' => 'Active Tickets',
	'Class:FunctionalCI/Tab:OpenedTickets+' => 'Active Tickets which are impacting this functional CI',
));

//
// Class: PhysicalDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PhysicalDevice' => 'Physical Device',
	'Class:PhysicalDevice+' => '',
	'Class:PhysicalDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:PhysicalDevice/Attribute:serialnumber' => 'Serial number',
	'Class:PhysicalDevice/Attribute:serialnumber+' => '',
	'Class:PhysicalDevice/Attribute:location_id' => 'Location',
	'Class:PhysicalDevice/Attribute:location_id+' => '',
	'Class:PhysicalDevice/Attribute:location_name' => 'Location name',
	'Class:PhysicalDevice/Attribute:location_name+' => '',
	'Class:PhysicalDevice/Attribute:status' => 'Status',
	'Class:PhysicalDevice/Attribute:status+' => '',
	'Class:PhysicalDevice/Attribute:status/Value:implementation' => 'implementation',
	'Class:PhysicalDevice/Attribute:status/Value:implementation+' => 'implementation',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:PhysicalDevice/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:PhysicalDevice/Attribute:status/Value:production' => 'production',
	'Class:PhysicalDevice/Attribute:status/Value:production+' => 'production',
	'Class:PhysicalDevice/Attribute:status/Value:stock' => 'stock',
	'Class:PhysicalDevice/Attribute:status/Value:stock+' => 'stock',
	'Class:PhysicalDevice/Attribute:brand_id' => 'Brand',
	'Class:PhysicalDevice/Attribute:brand_id+' => '',
	'Class:PhysicalDevice/Attribute:brand_name' => 'Brand name',
	'Class:PhysicalDevice/Attribute:brand_name+' => '',
	'Class:PhysicalDevice/Attribute:model_id' => 'Model',
	'Class:PhysicalDevice/Attribute:model_id+' => '',
	'Class:PhysicalDevice/Attribute:model_name' => 'Model name',
	'Class:PhysicalDevice/Attribute:model_name+' => '',
	'Class:PhysicalDevice/Attribute:asset_number' => 'Asset number',
	'Class:PhysicalDevice/Attribute:asset_number+' => '',
	'Class:PhysicalDevice/Attribute:purchase_date' => 'Purchase date',
	'Class:PhysicalDevice/Attribute:purchase_date+' => '',
	'Class:PhysicalDevice/Attribute:end_of_warranty' => 'End of warranty',
	'Class:PhysicalDevice/Attribute:end_of_warranty+' => '',
));

//
// Class: Rack
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Rack' => 'Rack',
	'Class:Rack+' => '',
	'Class:Rack/ComplementaryName' => '%1$s - %2$s',
	'Class:Rack/Attribute:nb_u' => 'Rack units',
	'Class:Rack/Attribute:nb_u+' => '',
	'Class:Rack/Attribute:device_list' => 'Devices',
	'Class:Rack/Attribute:device_list+' => 'All the physical devices racked into this rack',
	'Class:Rack/Attribute:enclosure_list' => 'Enclosures',
	'Class:Rack/Attribute:enclosure_list+' => 'All the enclosures in this rack',
));

//
// Class: TelephonyCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TelephonyCI' => 'Telephony CI',
	'Class:TelephonyCI+' => '',
	'Class:TelephonyCI/Attribute:phonenumber' => 'Phone number',
	'Class:TelephonyCI/Attribute:phonenumber+' => '',
));

//
// Class: Phone
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Phone' => 'Phone',
	'Class:Phone+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:MobilePhone' => 'Mobile Phone',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: IPPhone
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:IPPhone' => 'IP Phone',
	'Class:IPPhone+' => '',
));

//
// Class: Tablet
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Tablet' => 'Tablet',
	'Class:Tablet+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ConnectableCI' => 'Connectable CI',
	'Class:ConnectableCI+' => 'Physical CI',
	'Class:ConnectableCI/ComplementaryName' => '%1$s - %2$s',
	'Class:ConnectableCI/Attribute:networkdevice_list' => 'Network devices',
	'Class:ConnectableCI/Attribute:networkdevice_list+' => 'All network devices connected to this device',
	'Class:ConnectableCI/Attribute:physicalinterface_list' => 'Network interfaces',
	'Class:ConnectableCI/Attribute:physicalinterface_list+' => 'All the physical network interfaces',
));

//
// Class: DatacenterDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DatacenterDevice' => 'Datacenter Device',
	'Class:DatacenterDevice+' => '',
	'Class:DatacenterDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:DatacenterDevice/Attribute:rack_id' => 'Rack',
	'Class:DatacenterDevice/Attribute:rack_id+' => '',
	'Class:DatacenterDevice/Attribute:rack_name' => 'Rack name',
	'Class:DatacenterDevice/Attribute:rack_name+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_id' => 'Enclosure',
	'Class:DatacenterDevice/Attribute:enclosure_id+' => '',
	'Class:DatacenterDevice/Attribute:enclosure_name' => 'Enclosure name',
	'Class:DatacenterDevice/Attribute:enclosure_name+' => '',
	'Class:DatacenterDevice/Attribute:nb_u' => 'Rack units',
	'Class:DatacenterDevice/Attribute:nb_u+' => '',
	'Class:DatacenterDevice/Attribute:managementip' => 'Management IP',
	'Class:DatacenterDevice/Attribute:managementip+' => '',
	'Class:DatacenterDevice/Attribute:powerA_id' => 'PowerA source',
	'Class:DatacenterDevice/Attribute:powerA_id+' => '',
	'Class:DatacenterDevice/Attribute:powerA_name' => 'PowerA source name',
	'Class:DatacenterDevice/Attribute:powerA_name+' => '',
	'Class:DatacenterDevice/Attribute:powerB_id' => 'PowerB source',
	'Class:DatacenterDevice/Attribute:powerB_id+' => '',
	'Class:DatacenterDevice/Attribute:powerB_name' => 'PowerB source name',
	'Class:DatacenterDevice/Attribute:powerB_name+' => '',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list' => 'FC ports',
	'Class:DatacenterDevice/Attribute:fiberinterfacelist_list+' => 'All the fiber channel interfaces for this device',
	'Class:DatacenterDevice/Attribute:san_list' => 'SANs',
	'Class:DatacenterDevice/Attribute:san_list+' => 'All the SAN switches connected to this device',
	'Class:DatacenterDevice/Attribute:redundancy' => 'Redundancy',
	'Class:DatacenterDevice/Attribute:redundancy/count' => 'The device %2$s is up if at least one power connection (A or B) is up',
	// Unused yet
	'Class:DatacenterDevice/Attribute:redundancy/disabled' => 'The device %2$s is up if all its power connections are up',
	'Class:DatacenterDevice/Attribute:redundancy/percent' => 'The device %2$s is up if at least %1$s %% of its power connections are up',
));

//
// Class: NetworkDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NetworkDevice' => 'Network Device',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/ComplementaryName' => '%1$s - %2$s',
	'Class:NetworkDevice/Attribute:networkdevicetype_id' => 'Network type',
	'Class:NetworkDevice/Attribute:networkdevicetype_id+' => '',
	'Class:NetworkDevice/Attribute:networkdevicetype_name' => 'Network type name',
	'Class:NetworkDevice/Attribute:networkdevicetype_name+' => '',
	'Class:NetworkDevice/Attribute:connectablecis_list' => 'Devices',
	'Class:NetworkDevice/Attribute:connectablecis_list+' => 'All the devices connected to this network device',
	'Class:NetworkDevice/Attribute:iosversion_id' => 'IOS version',
	'Class:NetworkDevice/Attribute:iosversion_id+' => '',
	'Class:NetworkDevice/Attribute:iosversion_name' => 'IOS version name',
	'Class:NetworkDevice/Attribute:iosversion_name+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
));

//
// Class: Server
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/ComplementaryName' => '%1$s - %2$s',
	'Class:Server/Attribute:osfamily_id' => 'OS family',
	'Class:Server/Attribute:osfamily_id+' => '',
	'Class:Server/Attribute:osfamily_name' => 'OS family name',
	'Class:Server/Attribute:osfamily_name+' => '',
	'Class:Server/Attribute:osversion_id' => 'OS version',
	'Class:Server/Attribute:osversion_id+' => '',
	'Class:Server/Attribute:osversion_name' => 'OS version name',
	'Class:Server/Attribute:osversion_name+' => '',
	'Class:Server/Attribute:oslicence_id' => 'OS license',
	'Class:Server/Attribute:oslicence_id+' => '',
	'Class:Server/Attribute:oslicence_name' => 'OS license name',
	'Class:Server/Attribute:oslicence_name+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:logicalvolumes_list' => 'Logical volumes',
	'Class:Server/Attribute:logicalvolumes_list+' => 'All the logical volumes connected to this server',
));

//
// Class: StorageSystem
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:StorageSystem' => 'Storage System',
	'Class:StorageSystem+' => '',
	'Class:StorageSystem/ComplementaryName' => '%1$s - %2$s',
	'Class:StorageSystem/Attribute:logicalvolume_list' => 'Logical volumes',
	'Class:StorageSystem/Attribute:logicalvolume_list+' => 'All the logical volumes in this storage system',
));

//
// Class: SANSwitch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:SANSwitch' => 'SAN Switch',
	'Class:SANSwitch+' => '',
	'Class:SANSwitch/ComplementaryName' => '%1$s - %2$s',
	'Class:SANSwitch/Attribute:datacenterdevice_list' => 'Devices',
	'Class:SANSwitch/Attribute:datacenterdevice_list+' => 'All the devices connected to this SAN switch',
));

//
// Class: TapeLibrary
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:TapeLibrary' => 'Tape Library',
	'Class:TapeLibrary+' => '',
	'Class:TapeLibrary/ComplementaryName' => '%1$s - %2$s',
	'Class:TapeLibrary/Attribute:tapes_list' => 'Tapes',
	'Class:TapeLibrary/Attribute:tapes_list+' => 'All the tapes in the tape library',
));

//
// Class: NAS
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NAS' => 'NAS',
	'Class:NAS+' => '',
	'Class:NAS/ComplementaryName' => '%1$s - %2$s',
	'Class:NAS/Attribute:nasfilesystem_list' => 'Filesystems',
	'Class:NAS/Attribute:nasfilesystem_list+' => 'All the file systems in this NAS',
));

//
// Class: PC
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/ComplementaryName' => '%1$s - %2$s',
	'Class:PC/Attribute:osfamily_id' => 'OS family',
	'Class:PC/Attribute:osfamily_id+' => '',
	'Class:PC/Attribute:osfamily_name' => 'OS family name',
	'Class:PC/Attribute:osfamily_name+' => '',
	'Class:PC/Attribute:osversion_id' => 'OS version',
	'Class:PC/Attribute:osversion_id+' => '',
	'Class:PC/Attribute:osversion_name' => 'OS version name',
	'Class:PC/Attribute:osversion_name+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:type' => 'Type',
	'Class:PC/Attribute:type+' => '',
	'Class:PC/Attribute:type/Value:desktop' => 'desktop',
	'Class:PC/Attribute:type/Value:desktop+' => 'desktop',
	'Class:PC/Attribute:type/Value:laptop' => 'laptop',
	'Class:PC/Attribute:type/Value:laptop+' => 'laptop',
));

//
// Class: Printer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Printer' => 'Printer',
	'Class:Printer+' => '',
	'Class:Printer/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: PowerConnection
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PowerConnection' => 'Power Connection',
	'Class:PowerConnection+' => '',
	'Class:PowerConnection/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: PowerSource
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PowerSource' => 'Power Source',
	'Class:PowerSource+' => '',
	'Class:PowerSource/ComplementaryName' => '%1$s - %2$s',
	'Class:PowerSource/Attribute:pdus_list' => 'PDUs',
	'Class:PowerSource/Attribute:pdus_list+' => 'All the PDUs using this power source',
));

//
// Class: PDU
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PDU' => 'PDU',
	'Class:PDU+' => '',
	'Class:PDU/ComplementaryName' => '%1$s - %2$s - %3$s - %4$s',
	'Class:PDU/Attribute:rack_id' => 'Rack',
	'Class:PDU/Attribute:rack_id+' => '',
	'Class:PDU/Attribute:rack_name' => 'Rack name',
	'Class:PDU/Attribute:rack_name+' => '',
	'Class:PDU/Attribute:powerstart_id' => 'Power start',
	'Class:PDU/Attribute:powerstart_id+' => '',
	'Class:PDU/Attribute:powerstart_name' => 'Power start name',
	'Class:PDU/Attribute:powerstart_name+' => '',
));

//
// Class: Peripheral
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Peripheral' => 'Peripheral',
	'Class:Peripheral+' => '',
	'Class:Peripheral/ComplementaryName' => '%1$s - %2$s',
));

//
// Class: Enclosure
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Enclosure' => 'Enclosure',
	'Class:Enclosure+' => '',
	'Class:Enclosure/ComplementaryName' => '%1$s - %2$s - %3$s',
	'Class:Enclosure/Attribute:rack_id' => 'Rack',
	'Class:Enclosure/Attribute:rack_id+' => '',
	'Class:Enclosure/Attribute:rack_name' => 'Rack name',
	'Class:Enclosure/Attribute:rack_name+' => '',
	'Class:Enclosure/Attribute:nb_u' => 'Rack units',
	'Class:Enclosure/Attribute:nb_u+' => '',
	'Class:Enclosure/Attribute:device_list' => 'Devices',
	'Class:Enclosure/Attribute:device_list+' => 'All the devices in this enclosure',
));

//
// Class: ApplicationSolution
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ApplicationSolution' => 'Application Solution',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:functionalcis_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:functionalcis_list+' => 'All the configuration items that compose this application solution',
	'Class:ApplicationSolution/Attribute:businessprocess_list' => 'Business processes',
	'Class:ApplicationSolution/Attribute:businessprocess_list+' => 'All the business processes depending on this application solution',
	'Class:ApplicationSolution/Attribute:status' => 'Status',
	'Class:ApplicationSolution/Attribute:status+' => '',
	'Class:ApplicationSolution/Attribute:status/Value:active' => 'active',
	'Class:ApplicationSolution/Attribute:status/Value:active+' => 'active',
	'Class:ApplicationSolution/Attribute:status/Value:inactive' => 'inactive',
	'Class:ApplicationSolution/Attribute:status/Value:inactive+' => 'inactive',
	'Class:ApplicationSolution/Attribute:redundancy' => 'Impact analysis: configuration of the redundancy',
	'Class:ApplicationSolution/Attribute:redundancy/disabled' => 'The solution is up if all CIs are up',
	'Class:ApplicationSolution/Attribute:redundancy/count' => 'The solution is up if at least %1$s CI(s) is(are) up',
	'Class:ApplicationSolution/Attribute:redundancy/percent' => 'The solution is up if at least %1$s %% of the CIs are up',
));

//
// Class: BusinessProcess
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:BusinessProcess' => 'Business Process',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:applicationsolutions_list' => 'Application solutions',
	'Class:BusinessProcess/Attribute:applicationsolutions_list+' => 'All the application solutions that impact this business process',
	'Class:BusinessProcess/Attribute:status' => 'Status',
	'Class:BusinessProcess/Attribute:status+' => '',
	'Class:BusinessProcess/Attribute:status/Value:active' => 'active',
	'Class:BusinessProcess/Attribute:status/Value:active+' => 'active',
	'Class:BusinessProcess/Attribute:status/Value:inactive' => 'inactive',
	'Class:BusinessProcess/Attribute:status/Value:inactive+' => 'inactive',
));

//
// Class: SoftwareInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:SoftwareInstance' => 'Software Instance',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:system_id' => 'System',
	'Class:SoftwareInstance/Attribute:system_id+' => 'The system can be a Server, a Virtual Machine, a PC, ...',
	'Class:SoftwareInstance/Attribute:system_name' => 'System name',
	'Class:SoftwareInstance/Attribute:system_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software name',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_id' => 'Software license',
	'Class:SoftwareInstance/Attribute:softwarelicence_id+' => '',
	'Class:SoftwareInstance/Attribute:softwarelicence_name' => 'Software license name',
	'Class:SoftwareInstance/Attribute:softwarelicence_name+' => '',
	'Class:SoftwareInstance/Attribute:path' => 'Path',
	'Class:SoftwareInstance/Attribute:path+' => '',
	'Class:SoftwareInstance/Attribute:status' => 'Status',
	'Class:SoftwareInstance/Attribute:status+' => '',
	'Class:SoftwareInstance/Attribute:status/Value:active' => 'active',
	'Class:SoftwareInstance/Attribute:status/Value:active+' => 'active',
	'Class:SoftwareInstance/Attribute:status/Value:inactive' => 'inactive',
	'Class:SoftwareInstance/Attribute:status/Value:inactive+' => 'inactive',
));

//
// Class: Middleware
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Middleware' => 'Middleware',
	'Class:Middleware+' => '',
	'Class:Middleware/Attribute:middlewareinstance_list' => 'Middleware instances',
	'Class:Middleware/Attribute:middlewareinstance_list+' => 'All the middleware instances provided by this middleware',
));

//
// Class: DBServer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DBServer' => 'DB Server',
	'Class:DBServer+' => '',
	'Class:DBServer/Attribute:dbschema_list' => 'DB schemas',
	'Class:DBServer/Attribute:dbschema_list+' => 'All the database schemas for this DB server',
));

//
// Class: WebServer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:WebServer' => 'Web server',
	'Class:WebServer+' => '',
	'Class:WebServer/Attribute:webapp_list' => 'Web applications',
	'Class:WebServer/Attribute:webapp_list+' => 'All the web applications available on this web server',
));

//
// Class: PCSoftware
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PCSoftware' => 'PC Software',
	'Class:PCSoftware+' => '',
));

//
// Class: OtherSoftware
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:OtherSoftware' => 'Other Software',
	'Class:OtherSoftware+' => '',
));

//
// Class: MiddlewareInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:MiddlewareInstance' => 'Middleware Instance',
	'Class:MiddlewareInstance+' => '',
	'Class:MiddlewareInstance/ComplementaryName' => '%1$s - %2$s',
	'Class:MiddlewareInstance/Attribute:middleware_id' => 'Middleware',
	'Class:MiddlewareInstance/Attribute:middleware_id+' => '',
	'Class:MiddlewareInstance/Attribute:middleware_name' => 'Middleware name',
	'Class:MiddlewareInstance/Attribute:middleware_name+' => '',
));

//
// Class: DatabaseSchema
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DatabaseSchema' => 'Database Schema',
	'Class:DatabaseSchema+' => '',
	'Class:DatabaseSchema/ComplementaryName' => '%1$s - %2$s',
	'Class:DatabaseSchema/Attribute:dbserver_id' => 'DB server',
	'Class:DatabaseSchema/Attribute:dbserver_id+' => '',
	'Class:DatabaseSchema/Attribute:dbserver_name' => 'DB server name',
	'Class:DatabaseSchema/Attribute:dbserver_name+' => '',
));

//
// Class: WebApplication
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:WebApplication' => 'Web Application',
	'Class:WebApplication+' => '',
	'Class:WebApplication/ComplementaryName' => '%1$s - %2$s',
	'Class:WebApplication/Attribute:webserver_id' => 'Web server',
	'Class:WebApplication/Attribute:webserver_id+' => '',
	'Class:WebApplication/Attribute:webserver_name' => 'Web server name',
	'Class:WebApplication/Attribute:webserver_name+' => '',
	'Class:WebApplication/Attribute:url' => 'URL',
	'Class:WebApplication/Attribute:url+' => '',
));


//
// Class: VirtualDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:VirtualDevice' => 'Virtual Device',
	'Class:VirtualDevice+' => '',
	'Class:VirtualDevice/Attribute:status' => 'Status',
	'Class:VirtualDevice/Attribute:status+' => '',
	'Class:VirtualDevice/Attribute:status/Value:implementation' => 'implementation',
	'Class:VirtualDevice/Attribute:status/Value:implementation+' => 'implementation',
	'Class:VirtualDevice/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:VirtualDevice/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:VirtualDevice/Attribute:status/Value:production' => 'production',
	'Class:VirtualDevice/Attribute:status/Value:production+' => 'production',
	'Class:VirtualDevice/Attribute:status/Value:stock' => 'stock',
	'Class:VirtualDevice/Attribute:status/Value:stock+' => 'stock',
	'Class:VirtualDevice/Attribute:logicalvolumes_list' => 'Logical volumes',
	'Class:VirtualDevice/Attribute:logicalvolumes_list+' => 'All the logical volumes used by this device',
));

//
// Class: VirtualHost
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:VirtualHost' => 'Virtual Host',
	'Class:VirtualHost+' => '',
	'Class:VirtualHost/Attribute:virtualmachine_list' => 'Virtual machines',
	'Class:VirtualHost/Attribute:virtualmachine_list+' => 'All the virtual machines hosted by this host',
));

//
// Class: Hypervisor
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Hypervisor' => 'Hypervisor',
	'Class:Hypervisor+' => '',
	'Class:Hypervisor/Attribute:farm_id' => 'Farm',
	'Class:Hypervisor/Attribute:farm_id+' => '',
	'Class:Hypervisor/Attribute:farm_name' => 'Farm name',
	'Class:Hypervisor/Attribute:farm_name+' => '',
	'Class:Hypervisor/Attribute:server_id' => 'Server',
	'Class:Hypervisor/Attribute:server_id+' => '',
	'Class:Hypervisor/Attribute:server_name' => 'Server name',
	'Class:Hypervisor/Attribute:server_name+' => '',
));

//
// Class: Farm
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Farm' => 'Farm',
	'Class:Farm+' => '',
	'Class:Farm/Attribute:hypervisor_list' => 'Hypervisors',
	'Class:Farm/Attribute:hypervisor_list+' => 'All the hypervisors that compose this farm',
	'Class:Farm/Attribute:redundancy' => 'High availability',
	'Class:Farm/Attribute:redundancy/disabled' => 'The farm is up if all the hypervisors are up',
	'Class:Farm/Attribute:redundancy/count' => 'The farm is up if at least %1$s hypervisor(s) is(are) up',
	'Class:Farm/Attribute:redundancy/percent' => 'The farm is up if at least %1$s %% of the hypervisors are up',
));

//
// Class: VirtualMachine
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:VirtualMachine' => 'Virtual Machine',
	'Class:VirtualMachine+' => '',
	'Class:VirtualMachine/ComplementaryName' => '%1$s - %2$s',
	'Class:VirtualMachine/Attribute:virtualhost_id' => 'Virtual host',
	'Class:VirtualMachine/Attribute:virtualhost_id+' => '',
	'Class:VirtualMachine/Attribute:virtualhost_name' => 'Virtual host name',
	'Class:VirtualMachine/Attribute:virtualhost_name+' => '',
	'Class:VirtualMachine/Attribute:osfamily_id' => 'OS family',
	'Class:VirtualMachine/Attribute:osfamily_id+' => '',
	'Class:VirtualMachine/Attribute:osfamily_name' => 'OS family name',
	'Class:VirtualMachine/Attribute:osfamily_name+' => '',
	'Class:VirtualMachine/Attribute:osversion_id' => 'OS version',
	'Class:VirtualMachine/Attribute:osversion_id+' => '',
	'Class:VirtualMachine/Attribute:osversion_name' => 'OS version name',
	'Class:VirtualMachine/Attribute:osversion_name+' => '',
	'Class:VirtualMachine/Attribute:oslicence_id' => 'OS license',
	'Class:VirtualMachine/Attribute:oslicence_id+' => '',
	'Class:VirtualMachine/Attribute:oslicence_name' => 'OS license name',
	'Class:VirtualMachine/Attribute:oslicence_name+' => '',
	'Class:VirtualMachine/Attribute:cpu' => 'CPU',
	'Class:VirtualMachine/Attribute:cpu+' => '',
	'Class:VirtualMachine/Attribute:ram' => 'RAM',
	'Class:VirtualMachine/Attribute:ram+' => '',
	'Class:VirtualMachine/Attribute:managementip' => 'IP',
	'Class:VirtualMachine/Attribute:managementip+' => '',
	'Class:VirtualMachine/Attribute:logicalinterface_list' => 'Network Interfaces',
	'Class:VirtualMachine/Attribute:logicalinterface_list+' => 'All the logical network interfaces',
));

//
// Class: LogicalVolume
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:LogicalVolume' => 'Logical Volume',
	'Class:LogicalVolume+' => '',
	'Class:LogicalVolume/Attribute:name' => 'Name',
	'Class:LogicalVolume/Attribute:name+' => '',
	'Class:LogicalVolume/Attribute:lun_id' => 'LUN ID',
	'Class:LogicalVolume/Attribute:lun_id+' => '',
	'Class:LogicalVolume/Attribute:description' => 'Description',
	'Class:LogicalVolume/Attribute:description+' => '',
	'Class:LogicalVolume/Attribute:raid_level' => 'Raid level',
	'Class:LogicalVolume/Attribute:raid_level+' => '',
	'Class:LogicalVolume/Attribute:size' => 'Size',
	'Class:LogicalVolume/Attribute:size+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_id' => 'Storage system',
	'Class:LogicalVolume/Attribute:storagesystem_id+' => '',
	'Class:LogicalVolume/Attribute:storagesystem_name' => 'Storage system name',
	'Class:LogicalVolume/Attribute:storagesystem_name+' => '',
	'Class:LogicalVolume/Attribute:servers_list' => 'Servers',
	'Class:LogicalVolume/Attribute:servers_list+' => 'All the servers using this volume',
	'Class:LogicalVolume/Attribute:virtualdevices_list' => 'Virtual devices',
	'Class:LogicalVolume/Attribute:virtualdevices_list+' => 'All the virtual devices using this volume',
));

//
// Class: lnkServerToVolume
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkServerToVolume' => 'Link Server / Volume',
	'Class:lnkServerToVolume+' => '',
	'Class:lnkServerToVolume/Name' => '%1$s / %2$s',
	'Class:lnkServerToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkServerToVolume/Attribute:volume_id+' => '',
	'Class:lnkServerToVolume/Attribute:volume_name' => 'Volume name',
	'Class:lnkServerToVolume/Attribute:volume_name+' => '',
	'Class:lnkServerToVolume/Attribute:server_id' => 'Server',
	'Class:lnkServerToVolume/Attribute:server_id+' => '',
	'Class:lnkServerToVolume/Attribute:server_name' => 'Server name',
	'Class:lnkServerToVolume/Attribute:server_name+' => '',
	'Class:lnkServerToVolume/Attribute:size_used' => 'Size used',
	'Class:lnkServerToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkVirtualDeviceToVolume
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkVirtualDeviceToVolume' => 'Link Virtual Device / Volume',
	'Class:lnkVirtualDeviceToVolume+' => '',
	'Class:lnkVirtualDeviceToVolume/Name' => '%1$s / %2$s',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id' => 'Volume',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name' => 'Volume name',
	'Class:lnkVirtualDeviceToVolume/Attribute:volume_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id' => 'Virtual device',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_id+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name' => 'Virtual device name',
	'Class:lnkVirtualDeviceToVolume/Attribute:virtualdevice_name+' => '',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used' => 'Size used',
	'Class:lnkVirtualDeviceToVolume/Attribute:size_used+' => '',
));

//
// Class: lnkSanToDatacenterDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkSanToDatacenterDevice' => 'Link SAN / Datacenter Device',
	'Class:lnkSanToDatacenterDevice+' => '',
	'Class:lnkSanToDatacenterDevice/Name' => '%1$s / %2$s',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id' => 'SAN switch',
	'Class:lnkSanToDatacenterDevice/Attribute:san_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name' => 'SAN switch name',
	'Class:lnkSanToDatacenterDevice/Attribute:san_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id' => 'Device',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_id+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name' => 'Device name',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_name+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port' => 'SAN fc',
	'Class:lnkSanToDatacenterDevice/Attribute:san_port+' => '',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port' => 'Device fc',
	'Class:lnkSanToDatacenterDevice/Attribute:datacenterdevice_port+' => '',
));

//
// Class: Tape
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Tape' => 'Tape',
	'Class:Tape+' => '',
	'Class:Tape/Attribute:name' => 'Name',
	'Class:Tape/Attribute:name+' => '',
	'Class:Tape/Attribute:description' => 'Description',
	'Class:Tape/Attribute:description+' => '',
	'Class:Tape/Attribute:size' => 'Size',
	'Class:Tape/Attribute:size+' => '',
	'Class:Tape/Attribute:tapelibrary_id' => 'Tape library',
	'Class:Tape/Attribute:tapelibrary_id+' => '',
	'Class:Tape/Attribute:tapelibrary_name' => 'Tape library name',
	'Class:Tape/Attribute:tapelibrary_name+' => '',
));

//
// Class: NASFileSystem
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NASFileSystem' => 'NAS File System',
	'Class:NASFileSystem+' => '',
	'Class:NASFileSystem/Attribute:name' => 'Name',
	'Class:NASFileSystem/Attribute:name+' => '',
	'Class:NASFileSystem/Attribute:description' => 'Description',
	'Class:NASFileSystem/Attribute:description+' => '',
	'Class:NASFileSystem/Attribute:raid_level' => 'Raid level',
	'Class:NASFileSystem/Attribute:raid_level+' => '',
	'Class:NASFileSystem/Attribute:size' => 'Size',
	'Class:NASFileSystem/Attribute:size+' => '',
	'Class:NASFileSystem/Attribute:nas_id' => 'NAS',
	'Class:NASFileSystem/Attribute:nas_id+' => '',
	'Class:NASFileSystem/Attribute:nas_name' => 'NAS name',
	'Class:NASFileSystem/Attribute:nas_name+' => '',
));

//
// Class: Software
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/ComplementaryName' => '%1$s - %2$s',
	'Class:Software/Attribute:name' => 'Name',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:vendor' => 'Vendor',
	'Class:Software/Attribute:vendor+' => '',
	'Class:Software/Attribute:version' => 'Version',
	'Class:Software/Attribute:version+' => '',
	'Class:Software/Attribute:documents_list' => 'Documents',
	'Class:Software/Attribute:documents_list+' => 'All the documents linked to this software',
	'Class:Software/Attribute:type' => 'Type',
	'Class:Software/Attribute:type+' => '',
	'Class:Software/Attribute:type/Value:DBServer' => 'DB Server',
	'Class:Software/Attribute:type/Value:DBServer+' => 'DB Server',
	'Class:Software/Attribute:type/Value:Middleware' => 'Middleware',
	'Class:Software/Attribute:type/Value:Middleware+' => 'Middleware',
	'Class:Software/Attribute:type/Value:OtherSoftware' => 'Other Software',
	'Class:Software/Attribute:type/Value:OtherSoftware+' => 'Other Software',
	'Class:Software/Attribute:type/Value:PCSoftware' => 'PC Software',
	'Class:Software/Attribute:type/Value:PCSoftware+' => 'PC Software',
	'Class:Software/Attribute:type/Value:WebServer' => 'Web Server',
	'Class:Software/Attribute:type/Value:WebServer+' => 'Web Server',
	'Class:Software/Attribute:softwareinstance_list' => 'Software Instances',
	'Class:Software/Attribute:softwareinstance_list+' => 'All the software instances for this software',
	'Class:Software/Attribute:softwarepatch_list' => 'Software Patches',
	'Class:Software/Attribute:softwarepatch_list+' => 'All the patchs for this software',
	'Class:Software/Attribute:softwarelicence_list' => 'Software Licenses',
	'Class:Software/Attribute:softwarelicence_list+' => 'All the licenses for this software',
));

//
// Class: Patch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Name',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:documents_list' => 'Documents',
	'Class:Patch/Attribute:documents_list+' => 'All the documents linked to this patch',
	'Class:Patch/Attribute:description' => 'Description',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:finalclass' => 'Patch sub-class',
	'Class:Patch/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: OSPatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:OSPatch' => 'OS Patch',
	'Class:OSPatch+' => '',
	'Class:OSPatch/Attribute:functionalcis_list' => 'Devices',
	'Class:OSPatch/Attribute:functionalcis_list+' => 'All the systems where this patch is installed',
	'Class:OSPatch/Attribute:osversion_id' => 'OS version',
	'Class:OSPatch/Attribute:osversion_id+' => '',
	'Class:OSPatch/Attribute:osversion_name' => 'OS version name',
	'Class:OSPatch/Attribute:osversion_name+' => '',
));

//
// Class: SoftwarePatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:SoftwarePatch' => 'Software Patch',
	'Class:SoftwarePatch+' => '',
	'Class:SoftwarePatch/Attribute:software_id' => 'Software',
	'Class:SoftwarePatch/Attribute:software_id+' => '',
	'Class:SoftwarePatch/Attribute:software_name' => 'Software name',
	'Class:SoftwarePatch/Attribute:software_name+' => '',
	'Class:SoftwarePatch/Attribute:softwareinstances_list' => 'Software instances',
	'Class:SoftwarePatch/Attribute:softwareinstances_list+' => 'All the systems where this software patch is installed',
));

//
// Class: Licence
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Licence' => 'License',
	'Class:Licence+' => '',

	'Class:Licence/Attribute:name' => 'Name',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:documents_list' => 'Documents',
	'Class:Licence/Attribute:documents_list+' => 'All the documents linked to this license',
	'Class:Licence/Attribute:org_id' => 'Organization',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:organization_name' => 'Organization name',
	'Class:Licence/Attribute:organization_name+' => 'Common name',
	'Class:Licence/Attribute:usage_limit' => 'Usage limit',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:description' => 'Description',
	'Class:Licence/Attribute:description+' => '',
	'Class:Licence/Attribute:start_date' => 'Start date',
	'Class:Licence/Attribute:start_date+' => '',
	'Class:Licence/Attribute:end_date' => 'End date',
	'Class:Licence/Attribute:end_date+' => '',
	'Class:Licence/Attribute:licence_key' => 'Key',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:perpetual' => 'Perpetual',
	'Class:Licence/Attribute:perpetual+' => '',
	'Class:Licence/Attribute:perpetual/Value:no' => 'no',
	'Class:Licence/Attribute:perpetual/Value:no+' => 'no',
	'Class:Licence/Attribute:perpetual/Value:yes' => 'yes',
	'Class:Licence/Attribute:perpetual/Value:yes+' => 'yes',
	'Class:Licence/Attribute:finalclass' => 'License sub-class',
	'Class:Licence/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: OSLicence
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:OSLicence' => 'OS License',
	'Class:OSLicence+' => '',
	'Class:OSLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:OSLicence/Attribute:osversion_id' => 'OS version',
	'Class:OSLicence/Attribute:osversion_id+' => '',
	'Class:OSLicence/Attribute:osversion_name' => 'OS version name',
	'Class:OSLicence/Attribute:osversion_name+' => '',
	'Class:OSLicence/Attribute:virtualmachines_list' => 'Virtual machines',
	'Class:OSLicence/Attribute:virtualmachines_list+' => 'All the virtual machines where this license is used',
	'Class:OSLicence/Attribute:servers_list' => 'Servers',
	'Class:OSLicence/Attribute:servers_list+' => 'All the servers where this license is used',
));

//
// Class: SoftwareLicence
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:SoftwareLicence' => 'Software License',
	'Class:SoftwareLicence+' => '',
	'Class:SoftwareLicence/ComplementaryName' => '%1$s - %2$s',
	'Class:SoftwareLicence/Attribute:software_id' => 'Software',
	'Class:SoftwareLicence/Attribute:software_id+' => '',
	'Class:SoftwareLicence/Attribute:software_name' => 'Software name',
	'Class:SoftwareLicence/Attribute:software_name+' => '',
	'Class:SoftwareLicence/Attribute:softwareinstance_list' => 'Software instances',
	'Class:SoftwareLicence/Attribute:softwareinstance_list+' => 'All the systems where this license is used',
));

//
// Class: lnkDocumentToLicence
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentToLicence' => 'Link Document / License',
	'Class:lnkDocumentToLicence+' => '',
	'Class:lnkDocumentToLicence/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToLicence/Attribute:licence_id' => 'License',
	'Class:lnkDocumentToLicence/Attribute:licence_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:licence_name' => 'License name',
	'Class:lnkDocumentToLicence/Attribute:licence_name+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToLicence/Attribute:document_id+' => '',
	'Class:lnkDocumentToLicence/Attribute:document_name' => 'Document name',
	'Class:lnkDocumentToLicence/Attribute:document_name+' => '',
));

//
// Class: OSVersion
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:OSVersion' => 'OS Version',
	'Class:OSVersion+' => '',
	'Class:OSVersion/Attribute:osfamily_id' => 'OS family',
	'Class:OSVersion/Attribute:osfamily_id+' => '',
	'Class:OSVersion/Attribute:osfamily_name' => 'OS family name',
	'Class:OSVersion/Attribute:osfamily_name+' => '',
));

//
// Class: OSFamily
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:OSFamily' => 'OS Family',
	'Class:OSFamily+' => '',
));

//
// Class: Brand
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Brand' => 'Brand',
	'Class:Brand+' => '',
	'Class:Brand/Attribute:logo' => 'Logo',
	'Class:Brand/Attribute:logo+' => '',
	'Class:Brand/Attribute:physicaldevices_list' => 'Physical devices',
	'Class:Brand/Attribute:physicaldevices_list+' => 'All the physical devices corresponding to this brand',
	'Class:Brand/UniquenessRule:name+' => 'The name must be unique',
	'Class:Brand/UniquenessRule:name' => 'This brand already exists',
));

//
// Class: Model
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Model' => 'Model',
	'Class:Model+' => '',
	'Class:Model/ComplementaryName' => '%1$s - %2$s',
	'Class:Model/Attribute:brand_id' => 'Brand',
	'Class:Model/Attribute:brand_id+' => '',
	'Class:Model/Attribute:brand_name' => 'Brand name',
	'Class:Model/Attribute:brand_name+' => '',
	'Class:Model/Attribute:picture' => 'Picture',
	'Class:Model/Attribute:picture+' => '',
	'Class:Model/Attribute:type' => 'Device type',
	'Class:Model/Attribute:type+' => '',
	'Class:Model/Attribute:type/Value:PowerSource' => 'Power Source',
	'Class:Model/Attribute:type/Value:PowerSource+' => 'Power Source',
	'Class:Model/Attribute:type/Value:DiskArray' => 'Disk Array',
	'Class:Model/Attribute:type/Value:DiskArray+' => 'Disk Array',
	'Class:Model/Attribute:type/Value:Enclosure' => 'Enclosure',
	'Class:Model/Attribute:type/Value:Enclosure+' => 'Enclosure',
	'Class:Model/Attribute:type/Value:IPPhone' => 'IP Phone',
	'Class:Model/Attribute:type/Value:IPPhone+' => 'IP Phone',
	'Class:Model/Attribute:type/Value:MobilePhone' => 'Mobile Phone',
	'Class:Model/Attribute:type/Value:MobilePhone+' => 'Mobile Phone',
	'Class:Model/Attribute:type/Value:NAS' => 'NAS',
	'Class:Model/Attribute:type/Value:NAS+' => 'NAS',
	'Class:Model/Attribute:type/Value:NetworkDevice' => 'Network Device',
	'Class:Model/Attribute:type/Value:NetworkDevice+' => 'Network Device',
	'Class:Model/Attribute:type/Value:PC' => 'PC',
	'Class:Model/Attribute:type/Value:PC+' => 'PC',
	'Class:Model/Attribute:type/Value:PDU' => 'PDU',
	'Class:Model/Attribute:type/Value:PDU+' => 'PDU',
	'Class:Model/Attribute:type/Value:Peripheral' => 'Peripheral',
	'Class:Model/Attribute:type/Value:Peripheral+' => 'Peripheral',
	'Class:Model/Attribute:type/Value:Printer' => 'Printer',
	'Class:Model/Attribute:type/Value:Printer+' => 'Printer',
	'Class:Model/Attribute:type/Value:Rack' => 'Rack',
	'Class:Model/Attribute:type/Value:Rack+' => 'Rack',
	'Class:Model/Attribute:type/Value:SANSwitch' => 'SAN switch',
	'Class:Model/Attribute:type/Value:SANSwitch+' => 'SAN switch',
	'Class:Model/Attribute:type/Value:Server' => 'Server',
	'Class:Model/Attribute:type/Value:Server+' => 'Server',
	'Class:Model/Attribute:type/Value:StorageSystem' => 'Storage System',
	'Class:Model/Attribute:type/Value:StorageSystem+' => 'Storage System',
	'Class:Model/Attribute:type/Value:Tablet' => 'Tablet',
	'Class:Model/Attribute:type/Value:Tablet+' => 'Tablet',
	'Class:Model/Attribute:type/Value:TapeLibrary' => 'Tape Library',
	'Class:Model/Attribute:type/Value:TapeLibrary+' => 'Tape Library',
	'Class:Model/Attribute:type/Value:Phone' => 'Telephone',
	'Class:Model/Attribute:type/Value:Phone+' => 'Telephone',
	'Class:Model/Attribute:physicaldevices_list' => 'Physical devices',
	'Class:Model/Attribute:physicaldevices_list+' => 'All the physical devices corresponding to this model',
	'Class:Model/UniquenessRule:name_brand+' => 'Name must be unique in the brand',
	'Class:Model/UniquenessRule:name_brand' => 'this model already exists for this brand',
));

//
// Class: NetworkDeviceType
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NetworkDeviceType' => 'Network Device Type',
	'Class:NetworkDeviceType+' => '',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list' => 'Network devices',
	'Class:NetworkDeviceType/Attribute:networkdevicesdevices_list+' => 'All the network devices corresponding to this type',
));

//
// Class: IOSVersion
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:IOSVersion' => 'IOS Version',
	'Class:IOSVersion+' => '',
	'Class:IOSVersion/Attribute:brand_id' => 'Brand',
	'Class:IOSVersion/Attribute:brand_id+' => '',
	'Class:IOSVersion/Attribute:brand_name' => 'Brand name',
	'Class:IOSVersion/Attribute:brand_name+' => '',
));

//
// Class: lnkDocumentToPatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentToPatch' => 'Link Document / Patch',
	'Class:lnkDocumentToPatch+' => '',
	'Class:lnkDocumentToPatch/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToPatch/Attribute:patch_id' => 'Patch',
	'Class:lnkDocumentToPatch/Attribute:patch_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:patch_name' => 'Patch name',
	'Class:lnkDocumentToPatch/Attribute:patch_name+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToPatch/Attribute:document_id+' => '',
	'Class:lnkDocumentToPatch/Attribute:document_name' => 'Document name',
	'Class:lnkDocumentToPatch/Attribute:document_name+' => '',
));

//
// Class: lnkSoftwareInstanceToSoftwarePatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkSoftwareInstanceToSoftwarePatch' => 'Link Software Instance / Software Patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Name' => '%1$s / %2$s',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id' => 'Software patch',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name' => 'Software patch name',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwarepatch_name+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id' => 'Software instance',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_id+' => '',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name' => 'Software instance name',
	'Class:lnkSoftwareInstanceToSoftwarePatch/Attribute:softwareinstance_name+' => '',
));

//
// Class: lnkFunctionalCIToOSPatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkFunctionalCIToOSPatch' => 'Link FunctionalCI / OS patch',
	'Class:lnkFunctionalCIToOSPatch+' => '',
	'Class:lnkFunctionalCIToOSPatch/Name' => '%1$s / %2$s',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id' => 'OS patch',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name' => 'OS patch name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:ospatch_name+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name' => 'Functionalci name',
	'Class:lnkFunctionalCIToOSPatch/Attribute:functionalci_name+' => '',
));

//
// Class: lnkDocumentToSoftware
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentToSoftware' => 'Link Document / Software',
	'Class:lnkDocumentToSoftware+' => '',
	'Class:lnkDocumentToSoftware/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToSoftware/Attribute:software_id' => 'Software',
	'Class:lnkDocumentToSoftware/Attribute:software_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:software_name' => 'Software name',
	'Class:lnkDocumentToSoftware/Attribute:software_name+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToSoftware/Attribute:document_id+' => '',
	'Class:lnkDocumentToSoftware/Attribute:document_name' => 'Document name',
	'Class:lnkDocumentToSoftware/Attribute:document_name+' => '',
));

//
// Class: Subnet
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s/%2$s',
	'Class:Subnet/ComplementaryName' => '%1$s - %2$s',
	'Class:Subnet/Attribute:description' => 'Description',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:subnet_name' => 'Subnet name',
	'Class:Subnet/Attribute:subnet_name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Owner organization',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:org_name' => 'Organization name',
	'Class:Subnet/Attribute:org_name+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
	'Class:Subnet/Attribute:vlans_list' => 'VLANs',
	'Class:Subnet/Attribute:vlans_list+' => '',
));

//
// Class: VLAN
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:VLAN' => 'VLAN',
	'Class:VLAN+' => '',
	'Class:VLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:VLAN/Attribute:vlan_tag+' => '',
	'Class:VLAN/Attribute:description' => 'Description',
	'Class:VLAN/Attribute:description+' => '',
	'Class:VLAN/Attribute:org_id' => 'Organization',
	'Class:VLAN/Attribute:org_id+' => '',
	'Class:VLAN/Attribute:org_name' => 'Organization name',
	'Class:VLAN/Attribute:org_name+' => '',
	'Class:VLAN/Attribute:subnets_list' => 'Subnets',
	'Class:VLAN/Attribute:subnets_list+' => '',
	'Class:VLAN/Attribute:physicalinterfaces_list' => 'Physical network interfaces',
	'Class:VLAN/Attribute:physicalinterfaces_list+' => '',
));

//
// Class: lnkSubnetToVLAN
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkSubnetToVLAN' => 'Link Subnet / VLAN',
	'Class:lnkSubnetToVLAN+' => '',
	'Class:lnkSubnetToVLAN/Name' => '%1$s / %2$s',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id' => 'Subnet',
	'Class:lnkSubnetToVLAN/Attribute:subnet_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip' => 'Subnet IP',
	'Class:lnkSubnetToVLAN/Attribute:subnet_ip+' => '',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name' => 'Subnet name',
	'Class:lnkSubnetToVLAN/Attribute:subnet_name+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkSubnetToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkSubnetToVLAN/Attribute:vlan_tag+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NetworkInterface' => 'Network Interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:name' => 'Name',
	'Class:NetworkInterface/Attribute:name+' => '',
	'Class:NetworkInterface/Attribute:finalclass' => 'NetworkInterface sub-class',
	'Class:NetworkInterface/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: IPInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:IPInterface' => 'IP Interface',
	'Class:IPInterface+' => '',
	'Class:IPInterface/Attribute:ipaddress' => 'IP address',
	'Class:IPInterface/Attribute:ipaddress+' => '',


	'Class:IPInterface/Attribute:macaddress' => 'MAC address',
	'Class:IPInterface/Attribute:macaddress+' => '',
	'Class:IPInterface/Attribute:comment' => 'Comment',
	'Class:IPInterface/Attribute:coment+' => '',
	'Class:IPInterface/Attribute:ipgateway' => 'IP gateway',
	'Class:IPInterface/Attribute:ipgateway+' => '',
	'Class:IPInterface/Attribute:ipmask' => 'IP mask',
	'Class:IPInterface/Attribute:ipmask+' => '',
	'Class:IPInterface/Attribute:speed' => 'Speed',
	'Class:IPInterface/Attribute:speed+' => '',
));

//
// Class: PhysicalInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PhysicalInterface' => 'Physical Interface',
	'Class:PhysicalInterface+' => '',
	'Class:PhysicalInterface/Name' => '%2$s %1$s',
	'Class:PhysicalInterface/Attribute:connectableci_id' => 'Device',
	'Class:PhysicalInterface/Attribute:connectableci_id+' => '',
	'Class:PhysicalInterface/Attribute:connectableci_name' => 'Device name',
	'Class:PhysicalInterface/Attribute:connectableci_name+' => '',
	'Class:PhysicalInterface/Attribute:vlans_list' => 'VLANs',
	'Class:PhysicalInterface/Attribute:vlans_list+' => '',
));

//
// Class: lnkPhysicalInterfaceToVLAN
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkPhysicalInterfaceToVLAN' => 'Link PhysicalInterface / VLAN',
	'Class:lnkPhysicalInterfaceToVLAN+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Name' => '%1$s %2$s / %3$s',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id' => 'Physical Interface',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name' => 'Physical Interface Name',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id' => 'Device',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name' => 'Device name',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:physicalinterface_device_name+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id' => 'VLAN',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_id+' => '',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag' => 'VLAN Tag',
	'Class:lnkPhysicalInterfaceToVLAN/Attribute:vlan_tag+' => '',
));


//
// Class: LogicalInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:LogicalInterface' => 'Logical Interface',
	'Class:LogicalInterface+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_id' => 'Virtual machine',
	'Class:LogicalInterface/Attribute:virtualmachine_id+' => '',
	'Class:LogicalInterface/Attribute:virtualmachine_name' => 'Virtual machine name',
	'Class:LogicalInterface/Attribute:virtualmachine_name+' => '',
));

//
// Class: FiberChannelInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:FiberChannelInterface' => 'Fiber Channel Interface',
	'Class:FiberChannelInterface+' => '',
	'Class:FiberChannelInterface/Attribute:speed' => 'Speed',
	'Class:FiberChannelInterface/Attribute:speed+' => '',
	'Class:FiberChannelInterface/Attribute:topology' => 'Topology',
	'Class:FiberChannelInterface/Attribute:topology+' => '',
	'Class:FiberChannelInterface/Attribute:wwn' => 'WWN',
	'Class:FiberChannelInterface/Attribute:wwn+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id' => 'Device',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_id+' => '',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name' => 'Device name',
	'Class:FiberChannelInterface/Attribute:datacenterdevice_name+' => '',
));

//
// Class: lnkConnectableCIToNetworkDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkConnectableCIToNetworkDevice' => 'Link ConnectableCI / NetworkDevice',
	'Class:lnkConnectableCIToNetworkDevice+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Name' => '%1$s / %2$s',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id' => 'Network device',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name' => 'Network device name',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:networkdevice_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id' => 'Connected device',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_id+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name' => 'Connected device name',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connectableci_name+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port' => 'Network port',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:network_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port' => 'Device port',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:device_port+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type' => 'Connection type',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type+' => '',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:downlink+' => 'down link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink' => 'up link',
	'Class:lnkConnectableCIToNetworkDevice/Attribute:connection_type/Value:uplink+' => 'up link',
));

//
// Class: lnkApplicationSolutionToFunctionalCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkApplicationSolutionToFunctionalCI' => 'Link ApplicationSolution / FunctionalCI',
	'Class:lnkApplicationSolutionToFunctionalCI+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id' => 'Application solution',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name' => 'Application solution name',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:applicationsolution_name+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name',
	'Class:lnkApplicationSolutionToFunctionalCI/Attribute:functionalci_name+' => '',
));

//
// Class: lnkApplicationSolutionToBusinessProcess
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkApplicationSolutionToBusinessProcess' => 'Link ApplicationSolution / BusinessProcess',
	'Class:lnkApplicationSolutionToBusinessProcess+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Name' => '%1$s / %2$s',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id' => 'Business process',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name' => 'Business process name',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:businessprocess_name+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id' => 'Application solution',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_id+' => '',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name' => 'Application solution name',
	'Class:lnkApplicationSolutionToBusinessProcess/Attribute:applicationsolution_name+' => '',
));

//
// Class: Group
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Group' => 'Group',
	'Class:Group+' => '',
	'Class:Group/ComplementaryName' => '%1$s - %2$s',
	'Class:Group/Attribute:name' => 'Name',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementation',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementation',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsolete',
	'Class:Group/Attribute:status/Value:production' => 'Production',
	'Class:Group/Attribute:status/Value:production+' => 'Production',
	'Class:Group/Attribute:org_id' => 'Organization',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Name',
	'Class:Group/Attribute:owner_name+' => 'Common name',
	'Class:Group/Attribute:description' => 'Description',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Type',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Parent Group',

	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Name',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Linked CIs',
	'Class:Group/Attribute:ci_list+' => 'All the configuration items linked to this group',
	'Class:Group/Attribute:parent_id_friendlyname' => 'Parent Group',
	'Class:Group/Attribute:parent_id_friendlyname+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkGroupToCI' => 'Link Group / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Name' => '%1$s / %2$s',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Group',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Reason',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

// Add translation for Fieldsets

Dict::Add('EN US', 'English', 'English', array(
	'Server:baseinfo' => 'General information',
	'Server:Date' => 'Dates',
	'Server:moreinfo' => 'More information',
	'Server:otherinfo' => 'Other information',
	'Server:power' => 'Power supply',
	'Class:Subnet/Tab:IPUsage' => 'IP Usage',
	'Class:Subnet/Tab:IPUsage+' => 'Which IP within this Subnet are used or not',
	'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>',
	'Class:Subnet/Tab:FreeIPs' => 'Free IPs',
	'Class:Subnet/Tab:FreeIPs-count' => 'Free IPs: %1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'Here is an extract of 10 free IP addresses',
	'Class:Document:PreviewTab' => 'Preview',
));


//
// Class: lnkDocumentToFunctionalCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentToFunctionalCI' => 'Link Document / FunctionalCI',
	'Class:lnkDocumentToFunctionalCI+' => '',
	'Class:lnkDocumentToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id' => 'Functionalci',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name' => 'Functionalci name',
	'Class:lnkDocumentToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_id+' => '',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name' => 'Document name',
	'Class:lnkDocumentToFunctionalCI/Attribute:document_name+' => '',
));

//
// Application Menu
//

Dict::Add('EN US', 'English', 'English', array(
	'Menu:Application' => 'Applications',
	'Menu:Application+' => 'All applications',
	'Menu:DBServer' => 'Database servers',
	'Menu:DBServer+' => 'Database servers',
	'Menu:BusinessProcess' => 'Business processes',
	'Menu:BusinessProcess+' => 'All business processes',
	'Menu:ApplicationSolution' => 'Application solutions',
	'Menu:ApplicationSolution+' => 'All application solutions',
	'Menu:ConfigManagementSoftware' => 'Application management',
	'Menu:Licence' => 'Licenses',
	'Menu:Licence+' => 'All licenses',
	'Menu:Patch' => 'Patches',
	'Menu:Patch+' => 'All patches',
	'Menu:ApplicationInstance' => 'Installed software',
	'Menu:ApplicationInstance+' => 'Applications and database servers',
	'Menu:ConfigManagementHardware' => 'Infrastructure management',
	'Menu:Subnet' => 'Subnets',
	'Menu:Subnet+' => 'All subnets',
	'Menu:NetworkDevice' => 'Network devices',
	'Menu:NetworkDevice+' => 'All network devices',
	'Menu:Server' => 'Servers',
	'Menu:Server+' => 'All servers',
	'Menu:Printer' => 'Printers',
	'Menu:Printer+' => 'All printers',
	'Menu:MobilePhone' => 'Mobile phones',
	'Menu:MobilePhone+' => 'All mobile phones',
	'Menu:PC' => 'Personal computers',
	'Menu:PC+' => 'All Personal computers',
	'Menu:NewCI' => 'New CI',
	'Menu:NewCI+' => 'New CI',
	'Menu:SearchCIs' => 'Search for CIs',
	'Menu:SearchCIs+' => 'Search for CIs',
	'Menu:ConfigManagement:Devices' => 'Devices',
	'Menu:ConfigManagement:AllDevices' => 'Infrastructure',
	'Menu:ConfigManagement:virtualization' => 'Virtualization',
	'Menu:ConfigManagement:EndUsers' => 'End user devices',
	'Menu:ConfigManagement:SWAndApps' => 'Software and applications',
	'Menu:ConfigManagement:Misc' => 'Miscellaneous',
	'Menu:Group' => 'Groups of CIs',
	'Menu:Group+' => 'Groups of CIs',
	'Menu:OSVersion' => 'OS versions',
	'Menu:OSVersion+' => '',
	'Menu:Software' => 'Software catalog',
	'Menu:Software+' => 'Software catalog',
));
?>

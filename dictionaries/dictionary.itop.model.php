<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('EN US', 'English', 'English', array(
	'Relation:impacts/Description' => 'Elements impacted by',
	'Relation:impacts/VerbUp' => 'Impact...',
	'Relation:impacts/VerbDown' => 'Elements impacted by...',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:Organization' => 'Organization',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Name',
	'Class:Organization/Attribute:name+' => 'Common name',
	'Class:Organization/Attribute:code' => 'Code',
	'Class:Organization/Attribute:code+' => 'Organization code (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Active',
	'Class:Organization/Attribute:status/Value:active+' => 'Active',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inactive',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inactive',
	'Class:Organization/Attribute:parent_id' => 'Parent',
	'Class:Organization/Attribute:parent_id+' => 'Parent organization',
	'Class:Organization/Attribute:parent_name' => 'Parent name',
	'Class:Organization/Attribute:parent_name+' => 'Name of the parent organization',
));


//
// Class: Location
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Location' => 'Location',
	'Class:Location+' => 'Any type of location: Region, Country, City, Site, Building, Floor, Room, Rack,...',
	'Class:Location/Attribute:name' => 'Name',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Active',
	'Class:Location/Attribute:status/Value:active+' => 'Active',
	'Class:Location/Attribute:status/Value:inactive' => 'Inactive',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inactive',
	'Class:Location/Attribute:org_id' => 'Owner organization',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Name of the owner organization',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Address',
	'Class:Location/Attribute:address+' => 'Postal address',
	'Class:Location/Attribute:country' => 'Country',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Parent location',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Parent name',
	'Class:Location/Attribute:parent_name+' => '',
));

//
// Class: Contact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Contact' => 'Contact',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Name',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Active',
	'Class:Contact/Attribute:status/Value:active+' => 'Active',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inactive',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inactive',
	'Class:Contact/Attribute:org_id' => 'Organization',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organization',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Phone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Location',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Location',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CIs',
	'Class:Contact/Attribute:ci_list+' => 'CIs related to the contact',
	'Class:Contact/Attribute:contract_list' => 'Contracts',
	'Class:Contact/Attribute:ontract_list+' => 'Contracts related to the contact',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => 'Tickets related to the contact',
));

//
// Class: Person
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'First Name',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'Employee ID',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
));

//
// Class: Document
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:description' => 'Description',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Type',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Contract',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Network Map',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Presentation',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Training',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'White Paper',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Working Instructions',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Draft',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Published',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CIs',
	'Class:Document/Attribute:ci_list+' => 'CI refering to this document',
	'Class:Document/Attribute:contract_list' => 'Contracts',
	'Class:Document/Attribute:contract_list+' => 'Contracts refering to this document',
	'Class:Document/Attribute:ticket_list' => 'Tickets',
	'Class:Document/Attribute:ticket_list+' => 'Tickets refering to this document',
));

//
// Class: ExternalDoc
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ExternalDoc' => 'External Document',
	'Class:ExternalDoc+' => 'Document available on another web server',
	'Class:ExternalDoc/Attribute:url' => 'Url',
	'Class:ExternalDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Note' => 'Note',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Text',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:FileDoc' => 'Document (file)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Contents',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Licence' => 'Licence',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Provider',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:product' => 'Product',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Name',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Start date',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'End date',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:key' => 'Key',
	'Class:Licence/Attribute:key+' => '',
	'Class:Licence/Attribute:scope' => 'Scope',
	'Class:Licence/Attribute:scope+' => '',
));

//
// Class: Subnet
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Attribute:name' => 'Name',
	'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:description' => 'Description',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Name',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:target_sw' => 'Application scope',
	'Class:Patch/Attribute:target_sw+' => 'Target software (OS or application)',
	'Class:Patch/Attribute:version' => 'Version',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Type',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:fix' => 'Fix',
	'Class:Patch/Attribute:type/Value:fix+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Security',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Service Pack',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
));

//
// Class: Application
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Application' => 'Application',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Name',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Description',
	'Class:Application/Attribute:description+' => '',
));

//
// Class: lnkPatchToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkPatchToCI' => 'lnkPatchToCI',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:FunctionalCI' => 'FunctionalCI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Name',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Status',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Implementation',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Production',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:owner_id' => 'Owner organization',
	'Class:FunctionalCI/Attribute:owner_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Owner organization',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Importance',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'High',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Low',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Medium',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:solution_list' => 'Solutions',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Solutions using this CI',
	'Class:FunctionalCI/Attribute:finalclass' => 'finalclass',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ApplicationInstance' => 'Application Instance',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Attribute:device_id' => 'Device',
	'Class:ApplicationInstance/Attribute:device_id+' => '',
	'Class:ApplicationInstance/Attribute:device_name' => 'Device',
	'Class:ApplicationInstance/Attribute:device_name+' => '',
	'Class:ApplicationInstance/Attribute:licence_id' => 'Licence',
	'Class:ApplicationInstance/Attribute:licence_id+' => '',
	'Class:ApplicationInstance/Attribute:licence_name' => 'Licence',
	'Class:ApplicationInstance/Attribute:licence_name+' => '',
	'Class:ApplicationInstance/Attribute:application_id' => 'Application',
	'Class:ApplicationInstance/Attribute:application_id+' => '',
	'Class:ApplicationInstance/Attribute:application_name' => 'Application',
	'Class:ApplicationInstance/Attribute:application_name+' => '',
	'Class:ApplicationInstance/Attribute:version' => 'Version',
	'Class:ApplicationInstance/Attribute:version+' => '',
	'Class:ApplicationInstance/Attribute:description' => 'Description',
	'Class:ApplicationInstance/Attribute:description+' => '',
));

//
// Class: DatabaseInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DatabaseInstance' => 'Database instance',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Attribute:application_id' => 'Database software',
	'Class:DatabaseInstance/Attribute:application_id+' => '',
	'Class:DatabaseInstance/Attribute:application_name' => 'Database software',
	'Class:DatabaseInstance/Attribute:application_name+' => '',
	'Class:DatabaseInstance/Attribute:admin_login' => 'Admin login',
	'Class:DatabaseInstance/Attribute:admin_login+' => '',
	'Class:DatabaseInstance/Attribute:admin_password' => 'Admin password',
	'Class:DatabaseInstance/Attribute:admin_password+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Description',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ApplicationSolution' => 'Application Solution',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Description',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'CIs composing the solution',
	'Class:ApplicationSolution/Attribute:process_list' => 'Processes',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Business processes relying on the solution',
));

//
// Class: BusinessProcess
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:BusinessProcess' => 'Business Process',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:solution_list' => 'Solutions',
	'Class:BusinessProcess/Attribute:solution_list+' => 'Solutions the process is relying on',
	'Class:BusinessProcess/Attribute:description' => 'Description',
	'Class:BusinessProcess/Attribute:description+' => '',
));

//
// Class: ConnectableCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ConnectableCI' => 'Connectable CI',
	'Class:ConnectableCI+' => 'Physical CI',
	'Class:ConnectableCI/Attribute:brand' => 'Brand',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Model',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Serial  Number',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Asset Reference',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NetworkInterface' => 'Network Interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:device_id' => 'Device',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Device',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Logical Type',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Backup',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Logical',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Port',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primary',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secondary',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Physical Type',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'IP Address',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'IP Mask',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'MAC Address',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Speed',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Unknown',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Connected to',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Connected interface',
	'Class:NetworkInterface/Attribute:connected_name' => 'Connected to',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Connected device',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
));

//
// Class: Device
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Device' => 'Device',
	'Class:Device+' => '',
));

//
// Class: PC
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Hard disk',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OS Family',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OS Version',
	'Class:PC/Attribute:os_version+' => '',
));

//
// Class: MobileCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:MobileCI' => 'Mobile CI',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:MobilePhone' => 'Mobile Phone',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Phone number',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:InfrastructureCI' => 'Infrastructure CI',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Description',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Location',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Location',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Location details',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'Management IP',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Default Gateway',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:NetworkDevice' => 'Network Device',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Type',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:WANaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:WANaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Firewall',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Hub',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Load Balancer',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Router',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Switch',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'IOS Version',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP Read',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP Write',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Hard Disk',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'OS Family',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'OS Version',
	'Class:Server/Attribute:os_version+' => '',
));

//
// Class: Printer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Printer' => 'Printer',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Type',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:Mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:Mopier+' => '',
	'Class:Printer/Attribute:type/Value:Printer' => 'Printer',
	'Class:Printer/Attribute:type/Value:Printer+' => '',
	'Class:Printer/Attribute:technology' => 'technology',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:Inkjet' => 'Inkjet',
	'Class:Printer/Attribute:technology/Value:Inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:Laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:Laser+' => '',
	'Class:Printer/Attribute:technology/Value:Tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:Tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkCIToDoc' => 'lnkCItoDoc',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Document',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Document',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkCIToContact' => 'lnkCIToContact',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Contact',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Role',
	'Class:lnkCIToContact/Attribute:role+' => 'Role of the contact regarding the CI',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkSolutionToCI' => 'lnkSolutionToCI',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Solution',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Solution',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utility',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utility of the CI in the solution',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkProcessToSolution' => 'lnkProcessToSolution',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Solution',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Solution',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Process',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Process',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Reason',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'More information on the link between the process and the solution',
));


//
// Application Menu
//

Dict::Add('EN US', 'English', 'English', array(
'Menu:Class:Organization/Name' => 'Organizations',
'Menu:Class:Organization/Title' => 'ALl Organizations',
'Menu:Class:Application/Name' => 'Applications',
'Menu:Class:Application/Title' => 'All Applications',
'Menu:Audit' => 'Audit',
'Menu:ConfigManagement' => 'Configuration Management',
'Menu:ConfigManagement/Overview' => 'Overview',
'Menu:Class:Contact/Name' => 'Contacts',
'Menu:Class:Person/Name' => 'Persons',
'Menu:Class:Person/Title' => 'All Persons',
'Menu:Class:Team/Name' => 'Teams',
'Menu:Class:Team/Title' => 'All Teams',
'Menu:Class:FileDoc/Name' => 'Documents',
'Menu:Class:FileDoc/Title' => 'All Documents',
'Menu:Class:Location/Name' => 'Locations',
'Menu:Class:Location/Title' => 'All Locations',
'Menu:ConfigManagement:CI' => 'Configuration Items',
'Menu:Class:BusinessProcess/Name' => 'Business Processes',
'Menu:Class:BusinessProcess/Title' => 'All Business Processes',
'Menu:Class:ApplicationSolution/Name' => 'Application Solutions',
'Menu:Class:ApplicationSolution/Title' => 'All Application Solutions',
'Menu:ConfigManagement:Software' => 'Software',
'Menu:Class:Licence/Name' => 'Licences',
'Menu:Class:Licence/Title' => 'All Licences',
'Menu:Class:Patch/Name' => 'Patches',
'Menu:Class:Patch/Title' => 'ALl Patches',
'Menu:Class:ApplicationInstance/Name' => 'Installed Applications',
'Menu:Class:ApplicationInstance/Title' => 'All Installed Applications',
'Menu:Class:DatabaseInstance/Name' => 'Database Instances',
'Menu:Class:DatabaseInstance/Title' => 'All Database Instances',
'Menu:ConfigManagement:Hardware' => 'Hardware',
'Menu:Class:Subnet/Name' => 'Subnets',
'Menu:Class:Subnet/Title' => 'All Subnets',
'Menu:Class:NetworkDevice/Name' => 'Network Devices',
'Menu:Class:NetworkDevice/Title' => 'All Network Devices',
'Menu:Class:Server/Name' => 'Servers',
'Menu:Class:Server/Title' => 'All Servers',
'Menu:Class:Printer/Name' => 'Printers',
'Menu:Class:Printer/Title' => 'All Printers',
'Menu:Class:MobilePhone/Name' => 'Mobile Phones',
'Menu:Class:MobilePhone/Title' => 'All Mobile Phones',
'Menu:Class:PC/Name' => 'Personal Computers',
'Menu:Class:PC/Title' => 'All Personal Computers',
));
?>

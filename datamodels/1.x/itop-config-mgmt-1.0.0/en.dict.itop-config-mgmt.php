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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('EN US', 'English', 'English', array(
	'Relation:impacts/Description' => 'Elements impacted by',
	'Relation:impacts/VerbUp' => 'Impact...',
	'Relation:impacts/VerbDown' => 'Elements impacted by...',
	'Relation:depends on/Description' => 'Elements this element depends on',
	'Relation:depends on/VerbUp' => 'Depends on...',
	'Relation:depends on/VerbDown' => 'Impacts...',
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
	'Class:Location/Attribute:postal_code' => 'Postal code',
	'Class:Location/Attribute:postal_code+' => 'ZIP/Postal code',
	'Class:Location/Attribute:city' => 'City',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Country',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Parent location',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Parent name',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Contacts',
	'Class:Location/Attribute:contact_list+' => 'Contacts located on this site',
	'Class:Location/Attribute:infra_list' => 'Infrastructure',
	'Class:Location/Attribute:infra_list+' => 'CIs located on this site',
));
//
// Class: Group
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Group' => 'Group',
	'Class:Group+' => '',
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
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkGroupToCI' => 'Group / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Group',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CI Status',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Reason',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
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
	'Class:Contact/Attribute:contract_list+' => 'Contracts related to the contact',
	'Class:Contact/Attribute:service_list' => 'Services',
	'Class:Contact/Attribute:service_list+' => 'Services related to this contact',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => 'Tickets related to the contact',
	'Class:Contact/Attribute:team_list' => 'Teams',
	'Class:Contact/Attribute:team_list+' => 'Teams this contact belongs to',
	'Class:Contact/Attribute:finalclass' => 'Type',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => 'Last Name',
	'Class:Person/Attribute:name+' => '',
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
	'Class:Team/Attribute:member_list' => 'Members',
	'Class:Team/Attribute:member_list+' => 'Contacts that are part of the team',
));

//
// Class: lnkTeamToContact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkTeamToContact' => 'Team Members',
	'Class:lnkTeamToContact+' => 'Members of a team',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Team',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Member',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Location',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Phone',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Role',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organization',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => 'Organization Name',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
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
	'Class:Document/Attribute:ci_list+' => 'CIs refering to this document',
	'Class:Document/Attribute:contract_list' => 'Contracts',
	'Class:Document/Attribute:contract_list+' => 'Contracts refering to this document',
	'Class:Document/Attribute:service_list' => 'Services',
	'Class:Document/Attribute:service_list+' => 'Services refering to this document',
	'Class:Document/Attribute:ticket_list' => 'Tickets',
	'Class:Document/Attribute:ticket_list+' => 'Tickets refering to this document',
	'Class:Document:PreviewTab' => 'Preview',
));

//
// Class: WebDoc
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:WebDoc' => 'Web Document',
	'Class:WebDoc+' => 'Document available on another web server',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
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
	'Class:Licence' => 'License', // In GB, the noun is "Licence", whereas in US, it is ok with "License" anytime.
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Provider',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'Owner',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => 'Name',
	'Class:Licence/Attribute:org_name+' => 'Common name',
	'Class:Licence/Attribute:product' => 'Product',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Name',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Start date',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'End date',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Key',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Scope',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Usage limit',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Usage',
	'Class:Licence/Attribute:usage_list+' => 'Application instances using this license',
));


//
// Class: Subnet
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Name',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Owner organization',
	'Class:Subnet/Attribute:org_id+' => '',
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
	'Class:Patch/Attribute:description' => 'Description',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Application scope',
	'Class:Patch/Attribute:target_sw+' => 'Target software (OS or application)',
	'Class:Patch/Attribute:version' => 'Version',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Type',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Application',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Security',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Service Pack',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Devices',
	'Class:Patch/Attribute:ci_list+' => 'Devices where the patch is installed',
));

//
// Class: Software
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Name',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Description',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Installations',
	'Class:Software/Attribute:instance_list+' => 'Instances of this software',
	'Class:Software/Attribute:finalclass' => 'Type',
	'Class:Software/Attribute:finalclass+' => '',
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
	'Class:Application/Attribute:instance_list' => 'Installations',
	'Class:Application/Attribute:instance_list+' => 'Instances of this application',
));

//
// Class: DBServer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DBServer' => 'Database',
	'Class:DBServer+' => 'Database server SW',
	'Class:DBServer/Attribute:instance_list' => 'Installations',
	'Class:DBServer/Attribute:instance_list+' => 'Instances of this database server',
));

//
// Class: lnkPatchToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkPatchToCI' => 'Patch Usage',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CI Status',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:FunctionalCI' => 'Functional CI',
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
	'Class:FunctionalCI/Attribute:org_id' => 'Owner organization',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Owner organization',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Business criticality',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'High',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Low',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Medium',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'Contacts',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Contacts for this CI',
	'Class:FunctionalCI/Attribute:document_list' => 'Documents',
	'Class:FunctionalCI/Attribute:document_list+' => 'Documentation for this CI',
	'Class:FunctionalCI/Attribute:solution_list' => 'Application solutions',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Application solutions using this CI',
	'Class:FunctionalCI/Attribute:contract_list' => 'Contracts',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Contracts supporting this CI',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Tickets related to the CI',
	'Class:FunctionalCI/Attribute:finalclass' => 'Type',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:SoftwareInstance' => 'Software Instance',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Device',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Device',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Licence',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Licence',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Version',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Description',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ApplicationInstance' => 'Application Instance',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => 'Software',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => 'Name',
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DBServerInstance' => 'DB Server Instance',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => 'Software',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'Software Name',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Databases',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Database sources',
));


//
// Class: DatabaseInstance
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DatabaseInstance' => 'Database Instance',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Database server',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Database version',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
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
	'Class:ApplicationSolution/Attribute:process_list' => 'Business processes',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Business processes relying on the solution',
));

//
// Class: BusinessProcess
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:BusinessProcess' => 'Business Process',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Description',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Application	solutions',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Application solutions the process is relying on',
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
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
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
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Auto',
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
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'Device',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Link type',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Device' => 'Device',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Network interfaces',
	'Class:Device/Attribute:nwinterface_list+' => '',
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
	'Class:PC/Attribute:application_list' => 'Applications',
	'Class:PC/Attribute:application_list+' => 'Applications installed on this PC',
	'Class:PC/Attribute:patch_list' => 'Patches',
	'Class:PC/Attribute:patch_list+' => 'Patches installed on this PC',
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
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
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
	'Class:Server/Attribute:application_list' => 'Applications',
	'Class:Server/Attribute:application_list+' => 'Applications installed on this server',
	'Class:Server/Attribute:patch_list' => 'Patches',
	'Class:Server/Attribute:patch_list+' => 'Patches installed on this server',
));

//
// Class: Printer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Printer' => 'Printer',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Type',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Printer',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Technology',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Inkjet',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkCIToDoc' => 'Doc/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CI Status',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Document',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Document',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Document Type',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Document Status',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkCIToContact' => 'CI/Contact',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CI Status',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Contact',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Contact',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'Contact Email',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Role',
	'Class:lnkCIToContact/Attribute:role+' => 'Role of the contact regarding the CI',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkSolutionToCI' => 'CI/Solution',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Application solution',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Application solution',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CI Status',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utility',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utility of the CI in the solution',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkProcessToSolution' => 'Business process/Solution',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Application solution',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Application solution',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Process',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Process',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Reason',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'More information on the link between the process and the solution',
));



//
// Class extensions
//

Dict::Add('EN US', 'English', 'English', array(
'Class:Subnet/Tab:IPUsage' => 'IP Usage',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'Free IPs',
'Class:Subnet/Tab:FreeIPs-count' => 'Free IPs: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Here is an extract of 10 free IP addresses',
));

//
// Application Menu
//

Dict::Add('EN US', 'English', 'English', array(
'Menu:Catalogs' => 'Catalogs',
'Menu:Catalogs+' => 'Data types',
'Menu:Audit' => 'Audit',
'Menu:Audit+' => 'Audit',
'Menu:Organization' => 'Organizations',
'Menu:Organization+' => 'All Organizations',
'Menu:Application' => 'Applications',
'Menu:Application+' => 'All Applications',
'Menu:DBServer' => 'Database Servers',
'Menu:DBServer+' => 'Database Servers',
'Menu:Audit' => 'Audit',
'Menu:ConfigManagement' => 'Configuration Management',
'Menu:ConfigManagement+' => 'Configuration Management',
'Menu:ConfigManagementOverview' => 'Overview',
'Menu:ConfigManagementOverview+' => 'Overview',
'Menu:Contact' => 'Contacts',
'Menu:Contact+' => 'Contacts',
'Menu:Person' => 'Persons',
'Menu:Person+' => 'All Persons',
'Menu:Team' => 'Teams',
'Menu:Team+' => 'All Teams',
'Menu:Document' => 'Documents',
'Menu:Document+' => 'All Documents',
'Menu:Location' => 'Locations',
'Menu:Location+' => 'All Locations',
'Menu:ConfigManagementCI' => 'Configuration Items',
'Menu:ConfigManagementCI+' => 'Configuration Items',
'Menu:BusinessProcess' => 'Business Processes',
'Menu:BusinessProcess+' => 'All Business Processes',
'Menu:ApplicationSolution' => 'Application Solutions',
'Menu:ApplicationSolution+' => 'All Application Solutions',
'Menu:ConfigManagementSoftware' => 'Application Management',
'Menu:Licence' => 'Licences',
'Menu:Licence+' => 'All Licences',
'Menu:Patch' => 'Patches',
'Menu:Patch+' => 'All Patches',
'Menu:ApplicationInstance' => 'Installed Software',
'Menu:ApplicationInstance+' => 'Applications and Database servers',
'Menu:ConfigManagementHardware' => 'Infrastructure Management',
'Menu:Subnet' => 'Subnets',
'Menu:Subnet+' => 'All Subnets',
'Menu:NetworkDevice' => 'Network Devices',
'Menu:NetworkDevice+' => 'All Network Devices',
'Menu:Server' => 'Servers',
'Menu:Server+' => 'All Servers',
'Menu:Printer' => 'Printers',
'Menu:Printer+' => 'All Printers',
'Menu:MobilePhone' => 'Mobile Phones',
'Menu:MobilePhone+' => 'All Mobile Phones',
'Menu:PC' => 'Personal Computers',
'Menu:PC+' => 'All Personal Computers',
'Menu:NewContact' => 'New Contact',
'Menu:NewContact+' => 'New Contact',
'Menu:SearchContacts' => 'Search for contacts',
'Menu:SearchContacts+' => 'Search for contacts',
'Menu:NewCI' => 'New CI',
'Menu:NewCI+' => 'New CI',
'Menu:SearchCIs' => 'Search for CIs',
'Menu:SearchCIs+' => 'Search for CIs',
'Menu:ConfigManagement:Devices' => 'Devices',
'Menu:ConfigManagement:AllDevices' => 'Number of devices: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'Software and Applications',
'Menu:ConfigManagement:Misc' => 'Miscellaneous',
'Menu:Group' => 'Groups of CIs',
'Menu:Group+' => 'Groups of CIs',
'Menu:ConfigManagement:Shortcuts' => 'Shortcuts',
'Menu:ConfigManagement:AllContacts' => 'All contacts: %1$d',
));
?>

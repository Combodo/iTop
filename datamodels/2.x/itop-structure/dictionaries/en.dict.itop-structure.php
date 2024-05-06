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




// Dictionnary conventions
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
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Parent',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Parent organization',
	'Class:Organization/Attribute:overview' => 'Overview',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type',
	'Organization:Overview:Users' => ITOP_APPLICATION_SHORT.' Users within this organization',
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
	'Class:Location/Attribute:physicaldevice_list' => 'Devices',
	'Class:Location/Attribute:physicaldevice_list+' => 'All the devices in this location',
	'Class:Location/Attribute:person_list' => 'Contacts',
	'Class:Location/Attribute:person_list+' => 'All the contacts located on this location',
));

//
// Class: Contact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Contact' => 'Contact',
	'Class:Contact+' => '',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s',
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
	'Class:Contact/Attribute:org_name' => 'Organization name',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Phone',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:notify' => 'Notification',
	'Class:Contact/Attribute:notify+' => 'Flag which can be used by each notification',
	'Class:Contact/Attribute:notify/Value:no' => 'no',
	'Class:Contact/Attribute:notify/Value:no+' => 'no',
	'Class:Contact/Attribute:notify/Value:yes' => 'yes',
	'Class:Contact/Attribute:notify/Value:yes+' => 'yes',
	'Class:Contact/Attribute:function' => 'Function',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CIs',
	'Class:Contact/Attribute:cis_list+' => 'All the configuration items linked to this contact',
	'Class:Contact/Attribute:finalclass' => 'Contact sub-class',
	'Class:Contact/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: Person
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/ComplementaryName' => '%1$s - %2$s',
	'Class:Person/Attribute:name' => 'Last Name',
	'Class:Person/Attribute:name+' => '',
	'Class:Person/Attribute:first_name' => 'First Name',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Employee number',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => 'Mobile phone',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => 'Location',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => 'Location name',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'Manager',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'Manager name',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'Teams',
	'Class:Person/Attribute:team_list+' => 'All the teams this person belongs to',
	'Class:Person/Attribute:tickets_list' => 'Tickets',
	'Class:Person/Attribute:tickets_list+' => 'All the tickets this person is the caller',
	'Class:Person/Attribute:user_list' => 'Users',
	'Class:Person/Attribute:user_list+' => 'All the Users associated to this person',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Manager friendly name',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Picture',
	'Class:Person/Attribute:picture+' => '',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible to move this person under organization \'%1$s\' as it would break his access to the User Portal, his associated user not being allowed on this organization',
));

//
// Class: Team
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s',
	'Class:Team/Attribute:persons_list' => 'Members',
	'Class:Team/Attribute:persons_list+' => 'All the people belonging to this team',
	'Class:Team/Attribute:tickets_list' => 'Tickets',
	'Class:Team/Attribute:tickets_list+' => 'All the tickets assigned to this team',
));

//
// Class: Document
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Document' => 'Document',
	'Class:Document+' => '',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organization',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => 'Organization name',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Document type',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => 'Document type name',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version',
	'Class:Document/Attribute:version+' => '',
	'Class:Document/Attribute:description' => 'Description',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Draft',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsolete',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Published',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CIs',
	'Class:Document/Attribute:cis_list+' => 'All the configuration items linked to this document',
	'Class:Document/Attribute:finalclass' => 'Document sub-class',
	'Class:Document/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: DocumentFile
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DocumentFile' => 'Document File',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'File',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DocumentNote' => 'Document Note',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'Text',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DocumentWeb' => 'Document Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Typology' => 'Typology',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => 'Name',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'Typology sub-class',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class',
));

//
// Class: DocumentType
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:DocumentType' => 'Document Type',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:ContactType' => 'Contact Type',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkPersonToTeam' => 'Link Person / Team',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s',
	'Class:lnkPersonToTeam/Name+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Team',
	'Class:lnkPersonToTeam/Attribute:team_id+' => 'A team to which the person belongs',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Team name',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Person',
	'Class:lnkPersonToTeam/Attribute:person_id+' => 'A member of the team',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Person name',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Role',
	'Class:lnkPersonToTeam/Attribute:role_id+' => 'To select within a typology of possible roles',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Role name',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('EN US', 'English', 'English', array(
	'Menu:DataAdministration' => 'Data administration',
	'Menu:DataAdministration+' => 'Data administration',
	'Menu:Catalogs' => 'Catalogs',
	'Menu:Catalogs+' => 'Data types',
	'Menu:Audit' => 'Audit',
	'Menu:Audit+' => 'Audit',
	'Menu:CSVImport' => 'CSV import',
	'Menu:CSVImport+' => 'Bulk creation or update',
	'Menu:Organization' => 'Organizations',
	'Menu:Organization+' => 'All organizations',
	'Menu:ConfigManagement' => 'Configuration management',
	'Menu:ConfigManagement+' => 'Configuration management',
	'Menu:ConfigManagementCI' => 'Configuration items',
	'Menu:ConfigManagementCI+' => 'Configuration items',
	'Menu:ConfigManagementOverview' => 'Overview',
	'Menu:ConfigManagementOverview+' => 'Overview',
	'Menu:Contact' => 'Contacts',
	'Menu:Contact+' => 'Contacts',
	'Menu:Contact:Count' => '%1$d contacts',
	'Menu:Person' => 'Persons',
	'Menu:Person+' => 'All persons',
	'Menu:Team' => 'Teams',
	'Menu:Team+' => 'All teams',
	'Menu:Document' => 'Documents',
	'Menu:Document+' => 'All documents',
	'Menu:Location' => 'Locations',
	'Menu:Location+' => 'All locations',
	'Menu:NewContact' => 'New contact',
	'Menu:NewContact+' => 'New contact',
	'Menu:SearchContacts' => 'Search for contacts',
	'Menu:SearchContacts+' => 'Search for contacts',
	'Menu:ConfigManagement:Shortcuts' => 'Shortcuts',
	'Menu:ConfigManagement:AllContacts' => 'All contacts: %1$d',
	'Menu:Typology' => 'Typology configuration',
	'Menu:Typology+' => 'Typology configuration',
	'UI_WelcomeMenu_AllConfigItems' => 'Summary',
	'Menu:ConfigManagement:Typology' => 'Typology configuration',
));

// Add translation for Fieldsets

Dict::Add('EN US', 'English', 'English', array(
	'Person:info' => 'General information',
	'User:info' => 'General information',
	'User:profiles' => 'Profiles (minimum one)',
	'Person:personal_info' => 'Personal information',
	'Person:notifiy' => 'Notification',
));

// Themes
Dict::Add('EN US', 'English', 'English', array(
	'theme:fullmoon' => 'Full moon',
	'theme:test-red' => 'Test instance (Red)',
));

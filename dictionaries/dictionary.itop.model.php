<?php

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

//
// Class: bizOrganization
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizOrganization' => 'Organization',
	'Class:bizOrganization+' => 'Organizational structure: can be Company and/or Department',
	'Class:bizOrganization/Attribute:name' => 'Name',
	'Class:bizOrganization/Attribute:name+' => 'Common name',
	'Class:bizOrganization/Attribute:code' => 'Code',
	'Class:bizOrganization/Attribute:code+' => 'Organization code (Siret, DUNS,...)',
	'Class:bizOrganization/Attribute:status' => 'Status',
	'Class:bizOrganization/Attribute:status+' => 'Lifecycle status',
	'Class:bizOrganization/Attribute:status/Value:production' => 'production',
	'Class:bizOrganization/Attribute:status/Value:production+' => 'production',
	'Class:bizOrganization/Attribute:status/Value:implementation' => 'implementation',
	'Class:bizOrganization/Attribute:status/Value:implementation+' => 'implementation',
	'Class:bizOrganization/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:bizOrganization/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:bizOrganization/Attribute:parent_id' => 'Parent',
	'Class:bizOrganization/Attribute:parent_id+' => 'Parent organization',
	'Class:bizOrganization/Attribute:parent_name' => 'Parent Name',
	'Class:bizOrganization/Attribute:parent_name+' => 'Name of the parent organization',
));

//
// Class: logRealObject
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:logRealObject' => 'Object',
	'Class:logRealObject+' => 'Any CMDB object',
	'Class:logRealObject/Attribute:name' => 'Name',
	'Class:logRealObject/Attribute:name+' => 'Common name',
	'Class:logRealObject/Attribute:status' => 'Status',
	'Class:logRealObject/Attribute:status+' => 'Lifecycle status',
	'Class:logRealObject/Attribute:status/Value:production' => 'production',
	'Class:logRealObject/Attribute:status/Value:production+' => 'production',
	'Class:logRealObject/Attribute:status/Value:implementation' => 'implementation',
	'Class:logRealObject/Attribute:status/Value:implementation+' => 'implementation',
	'Class:logRealObject/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:logRealObject/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:logRealObject/Attribute:status/Value:off' => 'off',
	'Class:logRealObject/Attribute:status/Value:off+' => 'off',
	'Class:logRealObject/Attribute:status/Value:left company' => 'left company',
	'Class:logRealObject/Attribute:status/Value:left company+' => 'left company',
	'Class:logRealObject/Attribute:status/Value:available' => 'available',
	'Class:logRealObject/Attribute:status/Value:available+' => 'available',
	'Class:logRealObject/Attribute:org_id' => 'Organization',
	'Class:logRealObject/Attribute:org_id+' => 'ID of the object owner organization',
	'Class:logRealObject/Attribute:org_name' => 'Organization',
	'Class:logRealObject/Attribute:org_name+' => 'Company / Department owning this object',
	'Class:logRealObject/Attribute:finalclass' => 'finalclass',
	'Class:logRealObject/Attribute:finalclass+' => '',
));

//
// Class: bizContact
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizContact' => 'Contact',
	'Class:bizContact+' => 'Contact',
	'Class:bizContact/Attribute:status' => 'Status',
	'Class:bizContact/Attribute:status+' => 'Lifecycle status',
	'Class:bizContact/Attribute:status/Value:off' => 'off',
	'Class:bizContact/Attribute:status/Value:off+' => 'off',
	'Class:bizContact/Attribute:status/Value:left company' => 'left company',
	'Class:bizContact/Attribute:status/Value:left company+' => 'left company',
	'Class:bizContact/Attribute:status/Value:available' => 'available',
	'Class:bizContact/Attribute:status/Value:available+' => 'available',
	'Class:bizContact/Attribute:org_name' => 'Organization',
	'Class:bizContact/Attribute:org_name+' => 'Company / Department of the contact',
	'Class:bizContact/Attribute:email' => 'eMail',
	'Class:bizContact/Attribute:email+' => 'Email address',
	'Class:bizContact/Attribute:phone' => 'Phone',
	'Class:bizContact/Attribute:phone+' => 'Telephone',
	'Class:bizContact/Attribute:location_id' => 'Location',
	'Class:bizContact/Attribute:location_id+' => 'Id of the location where the contact is located',
	'Class:bizContact/Attribute:location_name' => 'Location Name',
	'Class:bizContact/Attribute:location_name+' => 'Name of the location where the contact is located',
));

//
// Class: bizPerson
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizPerson' => 'Person',
	'Class:bizPerson+' => 'Person',
	'Class:bizPerson/Attribute:first_name' => 'First Name',
	'Class:bizPerson/Attribute:first_name+' => 'First name',
	'Class:bizPerson/Attribute:employee_number' => 'Employee Number',
	'Class:bizPerson/Attribute:employee_number+' => 'employee number',
));

//
// Class: bizTeam
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizTeam' => 'Team',
	'Class:bizTeam+' => 'A group of contacts',
));

//
// Class: lnkContactTeam
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactTeam' => 'TeamsLinks',
	'Class:lnkContactTeam+' => 'A link between a contact and a Team',
	'Class:lnkContactTeam/Attribute:contact_id' => 'Contact',
	'Class:lnkContactTeam/Attribute:contact_id+' => 'The contact',
	'Class:lnkContactTeam/Attribute:contact_name' => 'Contact Name',
	'Class:lnkContactTeam/Attribute:contact_name+' => 'name of the contact',
	'Class:lnkContactTeam/Attribute:contact_phone' => 'Phone',
	'Class:lnkContactTeam/Attribute:contact_phone+' => 'Phone number of the contact',
	'Class:lnkContactTeam/Attribute:contact_email' => 'eMail',
	'Class:lnkContactTeam/Attribute:contact_email+' => 'eMail address of the contact',
	'Class:lnkContactTeam/Attribute:team_id' => 'Team',
	'Class:lnkContactTeam/Attribute:team_id+' => 'Team linked',
	'Class:lnkContactTeam/Attribute:team_name' => 'Team',
	'Class:lnkContactTeam/Attribute:team_name+' => 'name of the Team',
	'Class:lnkContactTeam/Attribute:role' => 'Role',
	'Class:lnkContactTeam/Attribute:role+' => 'Role of the contact',
));

//
// Class: bizDocument
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizDocument' => 'Document',
	'Class:bizDocument+' => 'Document',
	'Class:bizDocument/Attribute:status' => 'Status',
	'Class:bizDocument/Attribute:status+' => 'Lifecycle status',
	'Class:bizDocument/Attribute:status/Value:production' => 'production',
	'Class:bizDocument/Attribute:status/Value:production+' => 'production',
	'Class:bizDocument/Attribute:status/Value:implementation' => 'implementation',
	'Class:bizDocument/Attribute:status/Value:implementation+' => 'implementation',
	'Class:bizDocument/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:bizDocument/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:bizDocument/Attribute:org_name' => 'Organization',
	'Class:bizDocument/Attribute:org_name+' => 'Company / Department owning the document',
	'Class:bizDocument/Attribute:type' => 'type',
	'Class:bizDocument/Attribute:type+' => 'usage of the document',
	'Class:bizDocument/Attribute:type/Value:documentation' => 'documentation',
	'Class:bizDocument/Attribute:type/Value:documentation+' => 'documentation',
	'Class:bizDocument/Attribute:type/Value:contract' => 'contract',
	'Class:bizDocument/Attribute:type/Value:contract+' => 'contract',
	'Class:bizDocument/Attribute:type/Value:working instructions' => 'working instructions',
	'Class:bizDocument/Attribute:type/Value:working instructions+' => 'working instructions',
	'Class:bizDocument/Attribute:type/Value:network map' => 'network map',
	'Class:bizDocument/Attribute:type/Value:network map+' => 'network map',
	'Class:bizDocument/Attribute:type/Value:white paper' => 'white paper',
	'Class:bizDocument/Attribute:type/Value:white paper+' => 'white paper',
	'Class:bizDocument/Attribute:type/Value:presentation' => 'presentation',
	'Class:bizDocument/Attribute:type/Value:presentation+' => 'presentation',
	'Class:bizDocument/Attribute:type/Value:training' => 'training',
	'Class:bizDocument/Attribute:type/Value:training+' => 'training',
	'Class:bizDocument/Attribute:description' => 'Description',
	'Class:bizDocument/Attribute:description+' => 'Service Description',
	'Class:bizDocument/Attribute:contents' => 'Contents',
	'Class:bizDocument/Attribute:contents+' => 'File content',
));

//
// Class: lnkDocumentRealObject
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentRealObject' => 'DocumentsLinks',
	'Class:lnkDocumentRealObject+' => 'A link between a document and another object',
	'Class:lnkDocumentRealObject/Attribute:doc_id' => 'Document',
	'Class:lnkDocumentRealObject/Attribute:doc_id+' => 'id of the Document',
	'Class:lnkDocumentRealObject/Attribute:doc_name' => 'Document Name',
	'Class:lnkDocumentRealObject/Attribute:doc_name+' => 'name of the document',
	'Class:lnkDocumentRealObject/Attribute:object_id' => 'Object',
	'Class:lnkDocumentRealObject/Attribute:object_id+' => 'Object linked',
	'Class:lnkDocumentRealObject/Attribute:object_name' => 'Object Name',
	'Class:lnkDocumentRealObject/Attribute:object_name+' => 'name of the linked object',
	'Class:lnkDocumentRealObject/Attribute:link_type' => 'Link Type',
	'Class:lnkDocumentRealObject/Attribute:link_type+' => 'More information',
));

//
// Class: lnkContactRealObject
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactRealObject' => 'ContactsLinks',
	'Class:lnkContactRealObject+' => 'A link between a contact and another object',
	'Class:lnkContactRealObject/Attribute:contact_id' => 'Contact',
	'Class:lnkContactRealObject/Attribute:contact_id+' => 'The contact',
	'Class:lnkContactRealObject/Attribute:contact_name' => 'Contact Name',
	'Class:lnkContactRealObject/Attribute:contact_name+' => 'name of the contact',
	'Class:lnkContactRealObject/Attribute:contact_phone' => 'Phone',
	'Class:lnkContactRealObject/Attribute:contact_phone+' => 'Phone number of the contact',
	'Class:lnkContactRealObject/Attribute:contact_email' => 'eMail',
	'Class:lnkContactRealObject/Attribute:contact_email+' => 'eMail address of the contact',
	'Class:lnkContactRealObject/Attribute:object_id' => 'Object',
	'Class:lnkContactRealObject/Attribute:object_id+' => 'Object linked',
	'Class:lnkContactRealObject/Attribute:object_name' => 'Object Name',
	'Class:lnkContactRealObject/Attribute:object_name+' => 'name of the linked object',
	'Class:lnkContactRealObject/Attribute:role' => 'Role',
	'Class:lnkContactRealObject/Attribute:role+' => 'Role of the contact',
));

//
// Class: logInfra
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:logInfra' => 'Infra',
	'Class:logInfra+' => 'Infrastructure real object',
	'Class:logInfra/Attribute:status' => 'Status',
	'Class:logInfra/Attribute:status+' => 'Lifecycle status',
	'Class:logInfra/Attribute:status/Value:production' => 'production',
	'Class:logInfra/Attribute:status/Value:production+' => 'production',
	'Class:logInfra/Attribute:status/Value:implementation' => 'implementation',
	'Class:logInfra/Attribute:status/Value:implementation+' => 'implementation',
	'Class:logInfra/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:logInfra/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:logInfra/Attribute:severity' => 'Business Criticity',
	'Class:logInfra/Attribute:severity+' => 'Severity for this infrastructure',
	'Class:logInfra/Attribute:severity/Value:high' => 'high',
	'Class:logInfra/Attribute:severity/Value:high+' => 'high',
	'Class:logInfra/Attribute:severity/Value:medium' => 'medium',
	'Class:logInfra/Attribute:severity/Value:medium+' => 'medium',
	'Class:logInfra/Attribute:severity/Value:low' => 'low',
	'Class:logInfra/Attribute:severity/Value:low+' => 'low',
));

//
// Class: lnkContactInfra
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactInfra' => 'ContactsInfraLinks',
	'Class:lnkContactInfra+' => 'A link between a contact and an infrastructure',
	'Class:lnkContactInfra/Attribute:contact_id' => 'Contact',
	'Class:lnkContactInfra/Attribute:contact_id+' => 'The contact',
	'Class:lnkContactInfra/Attribute:contact_name' => 'Contact Name',
	'Class:lnkContactInfra/Attribute:contact_name+' => 'name of the contact',
	'Class:lnkContactInfra/Attribute:contact_phone' => 'Phone',
	'Class:lnkContactInfra/Attribute:contact_phone+' => 'Phone number of the contact',
	'Class:lnkContactInfra/Attribute:contact_email' => 'eMail',
	'Class:lnkContactInfra/Attribute:contact_email+' => 'eMail address of the contact',
	'Class:lnkContactInfra/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkContactInfra/Attribute:infra_id+' => 'Infrastructure linked',
	'Class:lnkContactInfra/Attribute:infra_name' => 'Infrastructure',
	'Class:lnkContactInfra/Attribute:infra_name+' => 'name of the linked infrastructure',
	'Class:lnkContactInfra/Attribute:role' => 'Role',
	'Class:lnkContactInfra/Attribute:role+' => 'Role of the contact',
));

//
// Class: bizLocation
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizLocation' => 'Location',
	'Class:bizLocation+' => 'Any type of location: Region, Country, City, Site, Building, Floor, Room, Rack,...',
	'Class:bizLocation/Attribute:address' => 'Address',
	'Class:bizLocation/Attribute:address+' => 'The postal address of the location',
	'Class:bizLocation/Attribute:country' => 'Country',
	'Class:bizLocation/Attribute:country+' => 'Country of the location',
	'Class:bizLocation/Attribute:parent_location_id' => 'Parent Location',
	'Class:bizLocation/Attribute:parent_location_id+' => 'where is the real object physically located',
	'Class:bizLocation/Attribute:parent_location_name' => 'Parent location (Name)',
	'Class:bizLocation/Attribute:parent_location_name+' => 'name of the parent location',
));

//
// Class: bizCircuit
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizCircuit' => 'Circuit',
	'Class:bizCircuit+' => 'Any type of circuit',
	'Class:bizCircuit/Attribute:speed' => 'speed',
	'Class:bizCircuit/Attribute:speed+' => 'speed of the circuit',
	'Class:bizCircuit/Attribute:location1_id' => 'Location 1',
	'Class:bizCircuit/Attribute:location1_id+' => 'Id of the location 1',
	'Class:bizCircuit/Attribute:location1_name' => 'Location 1',
	'Class:bizCircuit/Attribute:location1_name+' => 'Name of the location',
	'Class:bizCircuit/Attribute:location2_id' => 'Location 2',
	'Class:bizCircuit/Attribute:location2_id+' => 'Id of the location 2',
	'Class:bizCircuit/Attribute:location2_name' => 'Location 2',
	'Class:bizCircuit/Attribute:location2_name+' => 'Name of the location',
	'Class:bizCircuit/Attribute:interface1_id' => 'Interface 1',
	'Class:bizCircuit/Attribute:interface1_id+' => 'id of the interface 1',
	'Class:bizCircuit/Attribute:interface1_name' => 'Interface',
	'Class:bizCircuit/Attribute:interface1_name+' => 'Name of the interface 1',
	'Class:bizCircuit/Attribute:device1_name' => 'Device 1',
	'Class:bizCircuit/Attribute:device1_name+' => 'Name of the device 1',
	'Class:bizCircuit/Attribute:interface2_id' => 'Interface 2',
	'Class:bizCircuit/Attribute:interface2_id+' => 'id of the interface 2',
	'Class:bizCircuit/Attribute:interface2_name' => 'Interface',
	'Class:bizCircuit/Attribute:interface2_name+' => 'Name of the interface 2',
	'Class:bizCircuit/Attribute:device2_name' => 'Interface',
	'Class:bizCircuit/Attribute:device2_name+' => 'Name of the device 2',
	'Class:bizCircuit/Attribute:provider_id' => 'Carrier ID',
	'Class:bizCircuit/Attribute:provider_id+' => 'Organization ID of the provider of the Circuit',
	'Class:bizCircuit/Attribute:carrier_name' => 'Carrier',
	'Class:bizCircuit/Attribute:carrier_name+' => 'Name of the carrier',
	'Class:bizCircuit/Attribute:carrier_ref' => 'Carrier reference',
	'Class:bizCircuit/Attribute:carrier_ref+' => 'reference of the circuit used by the carrier',
));

//
// Class: bizInterface
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizInterface' => 'Interface',
	'Class:bizInterface+' => 'Interface',
	'Class:bizInterface/Attribute:device_id' => 'Device',
	'Class:bizInterface/Attribute:device_id+' => 'Device on which the interface is physically located',
	'Class:bizInterface/Attribute:device_name' => 'Device',
	'Class:bizInterface/Attribute:device_name+' => 'name of the device on which the interface is located',
	'Class:bizInterface/Attribute:device_location_id' => 'Device location',
	'Class:bizInterface/Attribute:device_location_id+' => 'location of the device on which the interface is located',
	'Class:bizInterface/Attribute:device_location_name' => 'Device location',
	'Class:bizInterface/Attribute:device_location_name+' => 'name of the location of the device on which the interface is located',
	'Class:bizInterface/Attribute:logical_type' => 'Logical type',
	'Class:bizInterface/Attribute:logical_type+' => 'Logical type of interface',
	'Class:bizInterface/Attribute:logical_type/Value:primary' => 'primary',
	'Class:bizInterface/Attribute:logical_type/Value:primary+' => 'primary',
	'Class:bizInterface/Attribute:logical_type/Value:secondary' => 'secondary',
	'Class:bizInterface/Attribute:logical_type/Value:secondary+' => 'secondary',
	'Class:bizInterface/Attribute:logical_type/Value:backup' => 'backup',
	'Class:bizInterface/Attribute:logical_type/Value:backup+' => 'backup',
	'Class:bizInterface/Attribute:logical_type/Value:port' => 'port',
	'Class:bizInterface/Attribute:logical_type/Value:port+' => 'port',
	'Class:bizInterface/Attribute:logical_type/Value:logical' => 'logical',
	'Class:bizInterface/Attribute:logical_type/Value:logical+' => 'logical',
	'Class:bizInterface/Attribute:physical_type' => 'Physical type',
	'Class:bizInterface/Attribute:physical_type+' => 'Physical type of interface',
	'Class:bizInterface/Attribute:physical_type/Value:ethernet' => 'ethernet',
	'Class:bizInterface/Attribute:physical_type/Value:ethernet+' => 'ethernet',
	'Class:bizInterface/Attribute:physical_type/Value:framerelay' => 'framerelay',
	'Class:bizInterface/Attribute:physical_type/Value:framerelay+' => 'framerelay',
	'Class:bizInterface/Attribute:physical_type/Value:atm' => 'atm',
	'Class:bizInterface/Attribute:physical_type/Value:atm+' => 'atm',
	'Class:bizInterface/Attribute:physical_type/Value:vlan' => 'vlan',
	'Class:bizInterface/Attribute:physical_type/Value:vlan+' => 'vlan',
	'Class:bizInterface/Attribute:ip_address' => 'IP address',
	'Class:bizInterface/Attribute:ip_address+' => 'address IP for this interface',
	'Class:bizInterface/Attribute:mask' => 'Subnet Mask',
	'Class:bizInterface/Attribute:mask+' => 'Subnet mask for this interface',
	'Class:bizInterface/Attribute:mac' => 'MAC address',
	'Class:bizInterface/Attribute:mac+' => 'MAC address for this interface',
	'Class:bizInterface/Attribute:speed' => 'Speed (Kb/s)',
	'Class:bizInterface/Attribute:speed+' => 'speed of this interface',
	'Class:bizInterface/Attribute:duplex' => 'Duplex',
	'Class:bizInterface/Attribute:duplex+' => 'Duplex configured for this interface',
	'Class:bizInterface/Attribute:duplex/Value:half' => 'half',
	'Class:bizInterface/Attribute:duplex/Value:half+' => 'half',
	'Class:bizInterface/Attribute:duplex/Value:full' => 'full',
	'Class:bizInterface/Attribute:duplex/Value:full+' => 'full',
	'Class:bizInterface/Attribute:duplex/Value:unknown' => 'unknown',
	'Class:bizInterface/Attribute:duplex/Value:unknown+' => 'unknown',
	'Class:bizInterface/Attribute:if_connected_id' => 'Connected interface',
	'Class:bizInterface/Attribute:if_connected_id+' => 'interface connected to this one',
	'Class:bizInterface/Attribute:if_connected_name' => 'Connected interface',
	'Class:bizInterface/Attribute:if_connected_name+' => 'name of the interface connected to this one',
	'Class:bizInterface/Attribute:if_connected_device' => 'Connected device',
	'Class:bizInterface/Attribute:if_connected_device+' => 'name of the device connected to this interface',
));

//
// Class: bizSubnet
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizSubnet' => 'Subnet',
	'Class:bizSubnet+' => 'Logical or physical subnet',
	'Class:bizSubnet/Attribute:ip' => 'IP',
	'Class:bizSubnet/Attribute:ip+' => 'IP',
	'Class:bizSubnet/Attribute:mask' => 'IP mask',
	'Class:bizSubnet/Attribute:mask+' => 'IP mask',
));

//
// Class: bizDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizDevice' => 'Device',
	'Class:bizDevice+' => 'Electronic devices',
	'Class:bizDevice/Attribute:location_id' => 'Location',
	'Class:bizDevice/Attribute:location_id+' => 'where is the located object physically located',
	'Class:bizDevice/Attribute:location_name' => 'Location Name',
	'Class:bizDevice/Attribute:location_name+' => 'name of the location',
	'Class:bizDevice/Attribute:country' => 'Country',
	'Class:bizDevice/Attribute:country+' => 'country where the device is located',
	'Class:bizDevice/Attribute:brand' => 'Brand',
	'Class:bizDevice/Attribute:brand+' => 'The manufacturer of the device',
	'Class:bizDevice/Attribute:model' => 'Model',
	'Class:bizDevice/Attribute:model+' => 'The model number of the device',
	'Class:bizDevice/Attribute:serial_number' => 'Serial Number',
	'Class:bizDevice/Attribute:serial_number+' => 'The serial number of the device',
	'Class:bizDevice/Attribute:mgmt_ip' => 'Mgmt IP',
	'Class:bizDevice/Attribute:mgmt_ip+' => 'Management IP',
));

//
// Class: bizPC
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizPC' => 'PC',
	'Class:bizPC+' => 'Personal Computers',
	'Class:bizPC/Attribute:type' => 'Type',
	'Class:bizPC/Attribute:type+' => 'Type of computer',
	'Class:bizPC/Attribute:type/Value:desktop PC' => 'desktop PC',
	'Class:bizPC/Attribute:type/Value:desktop PC+' => 'desktop PC',
	'Class:bizPC/Attribute:type/Value:laptop' => 'laptop',
	'Class:bizPC/Attribute:type/Value:laptop+' => 'laptop',
	'Class:bizPC/Attribute:memory_size' => 'Memory Size',
	'Class:bizPC/Attribute:memory_size+' => 'Size of the memory',
	'Class:bizPC/Attribute:cpu' => 'CPU',
	'Class:bizPC/Attribute:cpu+' => 'CPU type',
	'Class:bizPC/Attribute:hdd_size' => 'HDD Size',
	'Class:bizPC/Attribute:hdd_size+' => 'Size of the hard drive',
	'Class:bizPC/Attribute:os_family' => 'OS Family',
	'Class:bizPC/Attribute:os_family+' => 'Type of operating system',
	'Class:bizPC/Attribute:os_version' => 'OS Version',
	'Class:bizPC/Attribute:os_version+' => 'Detailed version number of the operating system',
	'Class:bizPC/Attribute:shipment_number' => 'Shipment Code',
	'Class:bizPC/Attribute:shipment_number+' => 'Number for tracking shipment',
	'Class:bizPC/Attribute:default_gateway' => 'Default Gateway',
	'Class:bizPC/Attribute:default_gateway+' => 'Default Gateway for this device',
));

//
// Class: bizServer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizServer' => 'Server',
	'Class:bizServer+' => 'Computer Servers',
	'Class:bizServer/Attribute:memory_size' => 'Memory Size',
	'Class:bizServer/Attribute:memory_size+' => 'Size of the memory',
	'Class:bizServer/Attribute:cpu' => 'CPU type',
	'Class:bizServer/Attribute:cpu+' => 'CPU type',
	'Class:bizServer/Attribute:number_of_cpus' => 'Number of CPUs',
	'Class:bizServer/Attribute:number_of_cpus+' => 'Number of CPUs',
	'Class:bizServer/Attribute:hdd_size' => 'HDD Size',
	'Class:bizServer/Attribute:hdd_size+' => 'Size of the hard drive',
	'Class:bizServer/Attribute:hdd_free_size' => 'Free HDD Size',
	'Class:bizServer/Attribute:hdd_free_size+' => 'Size of the free space on the hard drive(s)',
	'Class:bizServer/Attribute:os_family' => 'OS Family',
	'Class:bizServer/Attribute:os_family+' => 'Type of operating system',
	'Class:bizServer/Attribute:os_version' => 'OS Version',
	'Class:bizServer/Attribute:os_version+' => 'Detailed version number of the operating system',
	'Class:bizServer/Attribute:shipment_number' => 'Shipment number',
	'Class:bizServer/Attribute:shipment_number+' => 'Number for tracking shipment',
	'Class:bizServer/Attribute:default_gateway' => 'Default Gateway',
	'Class:bizServer/Attribute:default_gateway+' => 'Default Gateway for this device',
));

//
// Class: bizNetworkDevice
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizNetworkDevice' => 'Network Device',
	'Class:bizNetworkDevice+' => 'A network device',
	'Class:bizNetworkDevice/Attribute:type' => 'Type',
	'Class:bizNetworkDevice/Attribute:type+' => 'Type of device',
	'Class:bizNetworkDevice/Attribute:type/Value:switch' => 'switch',
	'Class:bizNetworkDevice/Attribute:type/Value:switch+' => 'switch',
	'Class:bizNetworkDevice/Attribute:type/Value:router' => 'router',
	'Class:bizNetworkDevice/Attribute:type/Value:router+' => 'router',
	'Class:bizNetworkDevice/Attribute:type/Value:firewall' => 'firewall',
	'Class:bizNetworkDevice/Attribute:type/Value:firewall+' => 'firewall',
	'Class:bizNetworkDevice/Attribute:type/Value:load balancer' => 'load balancer',
	'Class:bizNetworkDevice/Attribute:type/Value:load balancer+' => 'load balancer',
	'Class:bizNetworkDevice/Attribute:type/Value:hub' => 'hub',
	'Class:bizNetworkDevice/Attribute:type/Value:hub+' => 'hub',
	'Class:bizNetworkDevice/Attribute:type/Value:WAN accelerator' => 'WAN accelerator',
	'Class:bizNetworkDevice/Attribute:type/Value:WAN accelerator+' => 'WAN accelerator',
	'Class:bizNetworkDevice/Attribute:default_gateway' => 'Default Gateway',
	'Class:bizNetworkDevice/Attribute:default_gateway+' => 'Default Gateway for this device',
	'Class:bizNetworkDevice/Attribute:ios_version' => 'IOS version',
	'Class:bizNetworkDevice/Attribute:ios_version+' => 'IOS (software) version',
	'Class:bizNetworkDevice/Attribute:memory' => 'Memory',
	'Class:bizNetworkDevice/Attribute:memory+' => 'Memory description',
	'Class:bizNetworkDevice/Attribute:snmp_read' => 'SNMP Community (Read)',
	'Class:bizNetworkDevice/Attribute:snmp_read+' => 'SNMP Read Community String',
	'Class:bizNetworkDevice/Attribute:snmp_write' => 'SNMP Community (Write)',
	'Class:bizNetworkDevice/Attribute:snmp_write+' => 'SNMP Write Community String',
));

//
// Class: bizInfraGroup
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizInfraGroup' => 'Infra Group',
	'Class:bizInfraGroup+' => 'A group of infrastructure elements',
	'Class:bizInfraGroup/Attribute:type' => 'Type',
	'Class:bizInfraGroup/Attribute:type+' => 'Type of groupe',
	'Class:bizInfraGroup/Attribute:type/Value:Monitoring' => 'Monitoring',
	'Class:bizInfraGroup/Attribute:type/Value:Monitoring+' => 'Monitoring',
	'Class:bizInfraGroup/Attribute:type/Value:Reporting' => 'Reporting',
	'Class:bizInfraGroup/Attribute:type/Value:Reporting+' => 'Reporting',
	'Class:bizInfraGroup/Attribute:type/Value:list' => 'list',
	'Class:bizInfraGroup/Attribute:type/Value:list+' => 'list',
	'Class:bizInfraGroup/Attribute:description' => 'Description',
	'Class:bizInfraGroup/Attribute:description+' => 'usage of the Group',
	'Class:bizInfraGroup/Attribute:parent_group_id' => 'Parent Group',
	'Class:bizInfraGroup/Attribute:parent_group_id+' => 'including group',
	'Class:bizInfraGroup/Attribute:parent_group_name' => 'Parent Group (Name)',
	'Class:bizInfraGroup/Attribute:parent_group_name+' => 'name of the parent group',
));

//
// Class: bizApplication
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizApplication' => 'Application',
	'Class:bizApplication+' => 'General application',
	'Class:bizApplication/Attribute:device_id' => 'Hosting device',
	'Class:bizApplication/Attribute:device_id+' => 'The device where application is installed',
	'Class:bizApplication/Attribute:device_name' => 'Hosting device',
	'Class:bizApplication/Attribute:device_name+' => 'Name of the device where application is installed',
	'Class:bizApplication/Attribute:install_date' => 'Installation Date',
	'Class:bizApplication/Attribute:install_date+' => 'Date when application was installed',
	'Class:bizApplication/Attribute:version' => 'Version',
	'Class:bizApplication/Attribute:version+' => 'Application version',
	'Class:bizApplication/Attribute:function' => 'Function',
	'Class:bizApplication/Attribute:function+' => 'Function provided by this application',
));

//
// Class: lnkInfraGrouping
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraGrouping' => 'Infra Grouping',
	'Class:lnkInfraGrouping+' => 'Infra part of an Infra Group',
	'Class:lnkInfraGrouping/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraGrouping/Attribute:infra_id+' => 'Infrastructure part of the group',
	'Class:lnkInfraGrouping/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraGrouping/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraGrouping/Attribute:infra_status' => 'Status',
	'Class:lnkInfraGrouping/Attribute:infra_status+' => 'Status of the impacted infrastructure',
	'Class:lnkInfraGrouping/Attribute:infra_group_id' => 'Group',
	'Class:lnkInfraGrouping/Attribute:infra_group_id+' => 'Name of the group',
	'Class:lnkInfraGrouping/Attribute:group_name' => 'Group Name',
	'Class:lnkInfraGrouping/Attribute:group_name+' => 'Name of the group containing infrastructure',
	'Class:lnkInfraGrouping/Attribute:impact' => 'Relation',
	'Class:lnkInfraGrouping/Attribute:impact+' => 'Relation between this group and infra',
));

//
// Class: lnkClientServer
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkClientServer' => 'ClientServerLinks',
	'Class:lnkClientServer+' => 'Link between client server application',
	'Class:lnkClientServer/Attribute:status' => 'Status',
	'Class:lnkClientServer/Attribute:status+' => 'Lifecycle status',
	'Class:lnkClientServer/Attribute:status/Value:production' => 'production',
	'Class:lnkClientServer/Attribute:status/Value:production+' => 'production',
	'Class:lnkClientServer/Attribute:status/Value:implementation' => 'implementation',
	'Class:lnkClientServer/Attribute:status/Value:implementation+' => 'implementation',
	'Class:lnkClientServer/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:lnkClientServer/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:lnkClientServer/Attribute:client_id' => 'Client',
	'Class:lnkClientServer/Attribute:client_id+' => 'The client part of the link',
	'Class:lnkClientServer/Attribute:client_name' => 'Client',
	'Class:lnkClientServer/Attribute:client_name+' => 'Name of the client',
	'Class:lnkClientServer/Attribute:server_id' => 'Server',
	'Class:lnkClientServer/Attribute:server_id+' => 'the server part of the link',
	'Class:lnkClientServer/Attribute:server_name' => 'Server',
	'Class:lnkClientServer/Attribute:server_name+' => 'Name of the server',
	'Class:lnkClientServer/Attribute:relation' => 'Relation',
	'Class:lnkClientServer/Attribute:relation+' => 'Type of relation between both application',
));

//
// Class: bizPatch
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizPatch' => 'Patch',
	'Class:bizPatch+' => 'Patch installed on infrastucture',
	'Class:bizPatch/Attribute:status' => 'Status',
	'Class:bizPatch/Attribute:status+' => 'Lifecycle status',
	'Class:bizPatch/Attribute:status/Value:production' => 'production',
	'Class:bizPatch/Attribute:status/Value:production+' => 'production',
	'Class:bizPatch/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:bizPatch/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:bizPatch/Attribute:device_id' => 'Device',
	'Class:bizPatch/Attribute:device_id+' => 'The Device where patch is installed',
	'Class:bizPatch/Attribute:device_name' => 'Device Name',
	'Class:bizPatch/Attribute:device_name+' => 'Name of the impacted device',
	'Class:bizPatch/Attribute:install_date' => 'Installation Date',
	'Class:bizPatch/Attribute:install_date+' => 'Date when application was installed',
	'Class:bizPatch/Attribute:description' => 'Description',
	'Class:bizPatch/Attribute:description+' => 'description du patch',
	'Class:bizPatch/Attribute:patch_type' => 'Type',
	'Class:bizPatch/Attribute:patch_type+' => 'type de patch',
));

//
// Class: bizIncidentTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizIncidentTicket' => 'Incident',
	'Class:bizIncidentTicket+' => 'Incident ticket',
	'Class:bizIncidentTicket/Attribute:name' => 'Ticket Ref',
	'Class:bizIncidentTicket/Attribute:name+' => 'Refence number ofr this incident',
	'Class:bizIncidentTicket/Attribute:title' => 'Title',
	'Class:bizIncidentTicket/Attribute:title+' => 'Overview of the Incident',
	'Class:bizIncidentTicket/Attribute:type' => 'Type',
	'Class:bizIncidentTicket/Attribute:type+' => 'Type of the Incident',
	'Class:bizIncidentTicket/Attribute:type/Value:Network' => 'Network',
	'Class:bizIncidentTicket/Attribute:type/Value:Network+' => 'Network',
	'Class:bizIncidentTicket/Attribute:type/Value:Server' => 'Server',
	'Class:bizIncidentTicket/Attribute:type/Value:Server+' => 'Server',
	'Class:bizIncidentTicket/Attribute:type/Value:Desktop' => 'Desktop',
	'Class:bizIncidentTicket/Attribute:type/Value:Desktop+' => 'Desktop',
	'Class:bizIncidentTicket/Attribute:type/Value:Application' => 'Application',
	'Class:bizIncidentTicket/Attribute:type/Value:Application+' => 'Application',
	'Class:bizIncidentTicket/Attribute:org_id' => 'Customer',
	'Class:bizIncidentTicket/Attribute:org_id+' => 'who is impacted by the ticket',
	'Class:bizIncidentTicket/Attribute:customer_name' => 'Customer',
	'Class:bizIncidentTicket/Attribute:customer_name+' => 'Name of the customer impacted by this ticket',
	'Class:bizIncidentTicket/Attribute:ticket_status' => 'Status',
	'Class:bizIncidentTicket/Attribute:ticket_status+' => 'Status of the ticket',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:New' => 'New (Unassigned)',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:New+' => 'Newly created ticket',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Assigned' => 'Assigned',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Assigned+' => 'Ticket is assigned to somebody',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:WorkInProgress' => 'Work In Progress',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:WorkInProgress+' => 'Work is in progress',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Resolved' => 'Resolved',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Resolved+' => 'Ticket is resolved',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Closed' => 'Closed',
	'Class:bizIncidentTicket/Attribute:ticket_status/Value:Closed+' => 'Ticket is closed',
	'Class:bizIncidentTicket/Attribute:initial_situation' => 'Initial Situation',
	'Class:bizIncidentTicket/Attribute:initial_situation+' => 'Initial situation of the Incident',
	'Class:bizIncidentTicket/Attribute:current_situation' => 'Current Situation',
	'Class:bizIncidentTicket/Attribute:current_situation+' => 'Current situation of the Incident',
	'Class:bizIncidentTicket/Attribute:start_date' => 'Starting date',
	'Class:bizIncidentTicket/Attribute:start_date+' => 'Incident starting date',
	'Class:bizIncidentTicket/Attribute:last_update' => 'Last update',
	'Class:bizIncidentTicket/Attribute:last_update+' => 'last time the Ticket was modified',
	'Class:bizIncidentTicket/Attribute:next_update' => 'Next update',
	'Class:bizIncidentTicket/Attribute:next_update+' => 'next time the Ticket is expected to be  modified',
	'Class:bizIncidentTicket/Attribute:end_date' => 'Closure Date',
	'Class:bizIncidentTicket/Attribute:end_date+' => 'Date when the Ticket was closed',
	'Class:bizIncidentTicket/Attribute:caller_id' => 'Caller',
	'Class:bizIncidentTicket/Attribute:caller_id+' => 'person that trigger incident',
	'Class:bizIncidentTicket/Attribute:caller_mail' => 'Caller',
	'Class:bizIncidentTicket/Attribute:caller_mail+' => 'Person that trigger this incident',
	'Class:bizIncidentTicket/Attribute:impact' => 'Impact',
	'Class:bizIncidentTicket/Attribute:impact+' => 'Impact of the Incident',
	'Class:bizIncidentTicket/Attribute:workgroup_id' => 'Workgroup',
	'Class:bizIncidentTicket/Attribute:workgroup_id+' => 'which workgroup is owning ticket',
	'Class:bizIncidentTicket/Attribute:workgroup_name' => 'Workgroup',
	'Class:bizIncidentTicket/Attribute:workgroup_name+' => 'name of workgroup managing the Ticket',
	'Class:bizIncidentTicket/Attribute:agent_id' => 'Agent',
	'Class:bizIncidentTicket/Attribute:agent_id+' => 'who is managing the ticket',
	'Class:bizIncidentTicket/Attribute:agent_mail' => 'Agent',
	'Class:bizIncidentTicket/Attribute:agent_mail+' => 'mail of agent managing the Ticket',
	'Class:bizIncidentTicket/Attribute:action_log' => 'Action Logs',
	'Class:bizIncidentTicket/Attribute:action_log+' => 'List all action performed during the incident',
	'Class:bizIncidentTicket/Attribute:severity' => 'Severity',
	'Class:bizIncidentTicket/Attribute:severity+' => 'Field defining the criticity if the incident',
	'Class:bizIncidentTicket/Attribute:severity/Value:critical' => 'critical',
	'Class:bizIncidentTicket/Attribute:severity/Value:critical+' => 'critical',
	'Class:bizIncidentTicket/Attribute:severity/Value:medium' => 'medium',
	'Class:bizIncidentTicket/Attribute:severity/Value:medium+' => 'medium',
	'Class:bizIncidentTicket/Attribute:severity/Value:low' => 'low',
	'Class:bizIncidentTicket/Attribute:severity/Value:low+' => 'low',
	'Class:bizIncidentTicket/Attribute:assignment_count' => 'Assignment Count',
	'Class:bizIncidentTicket/Attribute:assignment_count+' => 'Number of times this ticket was assigned or reassigned',
	'Class:bizIncidentTicket/Attribute:resolution' => 'Resolution',
	'Class:bizIncidentTicket/Attribute:resolution+' => 'Description of the resolution',
	'Class:bizIncidentTicket/Attribute:impacted_infra_manual' => 'Impacted Infrastructure',
	'Class:bizIncidentTicket/Attribute:impacted_infra_manual+' => 'CIs that are not meeting the SLA',
	'Class:bizIncidentTicket/Attribute:related_tickets' => 'Related Tickets',
	'Class:bizIncidentTicket/Attribute:related_tickets+' => 'Other incident tickets related to this one',
	'Class:bizIncidentTicket/Attribute:contacts_a_notifier' => 'contacts auto',
	'Class:bizIncidentTicket/Attribute:contacts_a_notifier+' => 'blah',
	'Class:bizIncidentTicket/Stimulus:ev_assign' => 'Assign this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_assign+' => 'Assign this ticket to a group and an agent',
	'Class:bizIncidentTicket/Stimulus:ev_reassign' => 'Reassign this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_reassign+' => 'Reassign this ticket to a different group and agent',
	'Class:bizIncidentTicket/Stimulus:ev_start_working' => 'Work on this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_start_working+' => 'Start working on this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_resolve' => 'Resolve this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_resolve+' => 'Resolve this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_close' => 'Close this ticket',
	'Class:bizIncidentTicket/Stimulus:ev_close+' => 'Close this ticket',
));

//
// Class: lnkRelatedTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkRelatedTicket' => 'Related Ticket',
	'Class:lnkRelatedTicket+' => 'Ticket related to a ticket',
	'Class:lnkRelatedTicket/Attribute:rel_ticket_id' => 'Related Ticket',
	'Class:lnkRelatedTicket/Attribute:rel_ticket_id+' => 'The related ticket',
	'Class:lnkRelatedTicket/Attribute:rel_ticket_name' => 'Related ticket',
	'Class:lnkRelatedTicket/Attribute:rel_ticket_name+' => 'Name of the related ticket',
	'Class:lnkRelatedTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkRelatedTicket/Attribute:ticket_id+' => 'Ticket number',
	'Class:lnkRelatedTicket/Attribute:ticket_name' => 'Ticket Name',
	'Class:lnkRelatedTicket/Attribute:ticket_name+' => 'Name of the ticket',
	'Class:lnkRelatedTicket/Attribute:impact' => 'Impact',
	'Class:lnkRelatedTicket/Attribute:impact+' => 'Impact on the related ticket',
));

//
// Class: lnkInfraTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraTicket' => 'Infra Ticket',
	'Class:lnkInfraTicket+' => 'Infra impacted by a ticket',
	'Class:lnkInfraTicket/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraTicket/Attribute:infra_id+' => 'The infrastructure impacted',
	'Class:lnkInfraTicket/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraTicket/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraTicket/Attribute:ticket_id' => 'Ticket #',
	'Class:lnkInfraTicket/Attribute:ticket_id+' => 'Ticket number',
	'Class:lnkInfraTicket/Attribute:ticket_name' => 'Ticket Name',
	'Class:lnkInfraTicket/Attribute:ticket_name+' => 'Name of the ticket',
	'Class:lnkInfraTicket/Attribute:impact' => 'Impact',
	'Class:lnkInfraTicket/Attribute:impact+' => 'Level of impact of the infra by the related ticket',
));

//
// Class: lnkContactTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactTicket' => 'Contact Ticket',
	'Class:lnkContactTicket+' => 'Contacts to be notify for a ticket',
	'Class:lnkContactTicket/Attribute:contact_id' => 'Contact',
	'Class:lnkContactTicket/Attribute:contact_id+' => 'Contact to Notify',
	'Class:lnkContactTicket/Attribute:contact_email' => 'Contact email',
	'Class:lnkContactTicket/Attribute:contact_email+' => 'Mail for the contact',
	'Class:lnkContactTicket/Attribute:ticket_id' => 'Ticket #',
	'Class:lnkContactTicket/Attribute:ticket_id+' => 'Ticket number',
	'Class:lnkContactTicket/Attribute:ticket_name' => 'Ticket Name',
	'Class:lnkContactTicket/Attribute:ticket_name+' => 'Name of the ticket',
	'Class:lnkContactTicket/Attribute:role' => 'Role',
	'Class:lnkContactTicket/Attribute:role+' => 'Role of the contact',
));

//
// Class: bizWorkgroup
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizWorkgroup' => 'Workgroup',
	'Class:bizWorkgroup+' => 'Call tracking workgroup',
	'Class:bizWorkgroup/Attribute:status' => 'Status',
	'Class:bizWorkgroup/Attribute:status+' => 'Lifecycle status',
	'Class:bizWorkgroup/Attribute:status/Value:production' => 'production',
	'Class:bizWorkgroup/Attribute:status/Value:production+' => 'production',
	'Class:bizWorkgroup/Attribute:status/Value:implementation' => 'implementation',
	'Class:bizWorkgroup/Attribute:status/Value:implementation+' => 'implementation',
	'Class:bizWorkgroup/Attribute:status/Value:obsolete' => 'obsolete',
	'Class:bizWorkgroup/Attribute:status/Value:obsolete+' => 'obsolete',
	'Class:bizWorkgroup/Attribute:org_name' => 'Organization',
	'Class:bizWorkgroup/Attribute:org_name+' => 'Company / Department owning this object',
	'Class:bizWorkgroup/Attribute:team_id' => 'Team',
	'Class:bizWorkgroup/Attribute:team_id+' => 'Team owning the workgroup',
	'Class:bizWorkgroup/Attribute:team_name' => 'Team Name',
	'Class:bizWorkgroup/Attribute:team_name+' => 'name of the team',
	'Class:bizWorkgroup/Attribute:role' => 'Role',
	'Class:bizWorkgroup/Attribute:role+' => 'Role of this work group',
));

//
// Class: bizService
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizService' => 'Service',
	'Class:bizService+' => 'Service provided by an organization',
	'Class:bizService/Attribute:name' => 'Name',
	'Class:bizService/Attribute:name+' => 'Name of the service',
	'Class:bizService/Attribute:org_id' => 'Provider',
	'Class:bizService/Attribute:org_id+' => 'Provider for this service',
	'Class:bizService/Attribute:provider_name' => 'Provider',
	'Class:bizService/Attribute:provider_name+' => 'name of the Provider',
	'Class:bizService/Attribute:service_category' => 'Service Category',
	'Class:bizService/Attribute:service_category+' => 'Category for this contract',
	'Class:bizService/Attribute:service_category/Value:Server' => 'Server',
	'Class:bizService/Attribute:service_category/Value:Server+' => 'Server',
	'Class:bizService/Attribute:service_category/Value:Network' => 'Network',
	'Class:bizService/Attribute:service_category/Value:Network+' => 'Network',
	'Class:bizService/Attribute:service_category/Value:End-User' => 'End-User',
	'Class:bizService/Attribute:service_category/Value:End-User+' => 'End-User',
	'Class:bizService/Attribute:service_category/Value:Desktop' => 'Desktop',
	'Class:bizService/Attribute:service_category/Value:Desktop+' => 'Desktop',
	'Class:bizService/Attribute:service_category/Value:Application' => 'Application',
	'Class:bizService/Attribute:service_category/Value:Application+' => 'Application',
	'Class:bizService/Attribute:description' => 'Description',
	'Class:bizService/Attribute:description+' => 'Description of this service',
	'Class:bizService/Attribute:status' => 'Status',
	'Class:bizService/Attribute:status+' => 'Status of the service',
	'Class:bizService/Attribute:status/Value:New' => 'New',
	'Class:bizService/Attribute:status/Value:New+' => 'New',
	'Class:bizService/Attribute:status/Value:Implementation' => 'Implementation',
	'Class:bizService/Attribute:status/Value:Implementation+' => 'Implementation',
	'Class:bizService/Attribute:status/Value:Production' => 'Production',
	'Class:bizService/Attribute:status/Value:Production+' => 'Production',
	'Class:bizService/Attribute:status/Value:Obsolete' => 'Obsolete',
	'Class:bizService/Attribute:status/Value:Obsolete+' => 'Obsolete',
	'Class:bizService/Attribute:type' => 'Type',
	'Class:bizService/Attribute:type+' => 'Type of the service',
	'Class:bizService/Attribute:type/Value:Hardware' => 'Hardware',
	'Class:bizService/Attribute:type/Value:Hardware+' => 'Hardware',
	'Class:bizService/Attribute:type/Value:Software' => 'Software',
	'Class:bizService/Attribute:type/Value:Software+' => 'Software',
	'Class:bizService/Attribute:type/Value:Support' => 'Support',
	'Class:bizService/Attribute:type/Value:Support+' => 'Support',
));

//
// Class: bizContract
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizContract' => 'Contract',
	'Class:bizContract+' => 'Contract signed by an organization',
	'Class:bizContract/Attribute:name' => 'Name',
	'Class:bizContract/Attribute:name+' => 'Name of the contract',
	'Class:bizContract/Attribute:org_id' => 'Customer',
	'Class:bizContract/Attribute:org_id+' => 'Customer for this contract',
	'Class:bizContract/Attribute:customer_name' => 'Customer',
	'Class:bizContract/Attribute:customer_name+' => 'name of the Customer',
	'Class:bizContract/Attribute:service_id' => 'Service',
	'Class:bizContract/Attribute:service_id+' => 'Provider for this contract',
	'Class:bizContract/Attribute:provider_name' => 'Provider',
	'Class:bizContract/Attribute:provider_name+' => 'name of the service provider',
	'Class:bizContract/Attribute:service_name' => 'Service',
	'Class:bizContract/Attribute:service_name+' => 'name of the service',
	'Class:bizContract/Attribute:team_id' => 'Team',
	'Class:bizContract/Attribute:team_id+' => 'Team managing this contract',
	'Class:bizContract/Attribute:team_name' => 'Team',
	'Class:bizContract/Attribute:team_name+' => 'name of the team managing this contract',
	'Class:bizContract/Attribute:service_level' => 'Service Level',
	'Class:bizContract/Attribute:service_level+' => 'Level of service for this contract',
	'Class:bizContract/Attribute:service_level/Value:Gold' => 'Gold',
	'Class:bizContract/Attribute:service_level/Value:Gold+' => 'Gold',
	'Class:bizContract/Attribute:service_level/Value:Silver' => 'Silver',
	'Class:bizContract/Attribute:service_level/Value:Silver+' => 'Silver',
	'Class:bizContract/Attribute:service_level/Value:Bronze' => 'Bronze',
	'Class:bizContract/Attribute:service_level/Value:Bronze+' => 'Bronze',
	'Class:bizContract/Attribute:cost_unit' => 'Cost Unit',
	'Class:bizContract/Attribute:cost_unit+' => 'Cost unit to compute global cost for this contract',
	'Class:bizContract/Attribute:cost_unit/Value:Devices' => 'Devices',
	'Class:bizContract/Attribute:cost_unit/Value:Devices+' => 'Devices',
	'Class:bizContract/Attribute:cost_unit/Value:Persons' => 'Persons',
	'Class:bizContract/Attribute:cost_unit/Value:Persons+' => 'Persons',
	'Class:bizContract/Attribute:cost_unit/Value:Applications' => 'Applications',
	'Class:bizContract/Attribute:cost_unit/Value:Applications+' => 'Applications',
	'Class:bizContract/Attribute:cost_unit/Value:Global' => 'Global',
	'Class:bizContract/Attribute:cost_unit/Value:Global+' => 'Global',
	'Class:bizContract/Attribute:cost_freq' => 'Billing frequency',
	'Class:bizContract/Attribute:cost_freq+' => 'Frequency of cost for this contract',
	'Class:bizContract/Attribute:cost_freq/Value:Monthly' => 'Monthly',
	'Class:bizContract/Attribute:cost_freq/Value:Monthly+' => 'Monthly',
	'Class:bizContract/Attribute:cost_freq/Value:Yearly' => 'Yearly',
	'Class:bizContract/Attribute:cost_freq/Value:Yearly+' => 'Yearly',
	'Class:bizContract/Attribute:cost_freq/Value:Once' => 'Once',
	'Class:bizContract/Attribute:cost_freq/Value:Once+' => 'Once',
	'Class:bizContract/Attribute:cost' => 'Cost',
	'Class:bizContract/Attribute:cost+' => 'Cost of this contract',
	'Class:bizContract/Attribute:currency' => 'Currency',
	'Class:bizContract/Attribute:currency+' => 'Currency of cost for this contract',
	'Class:bizContract/Attribute:currency/Value:Euros' => 'Euros',
	'Class:bizContract/Attribute:currency/Value:Euros+' => 'Euros',
	'Class:bizContract/Attribute:currency/Value:Dollars' => 'Dollars',
	'Class:bizContract/Attribute:currency/Value:Dollars+' => 'Dollars',
	'Class:bizContract/Attribute:description' => 'Description',
	'Class:bizContract/Attribute:description+' => 'Description of this contract',
	'Class:bizContract/Attribute:move2prod_date' => 'Date of Move To Production',
	'Class:bizContract/Attribute:move2prod_date+' => 'Date when the contract is on production',
	'Class:bizContract/Attribute:end_prod' => 'Date of End Of Production',
	'Class:bizContract/Attribute:end_prod+' => 'Date when the contract is stopped',
	'Class:bizContract/Attribute:status' => 'Status',
	'Class:bizContract/Attribute:status+' => 'Status of the contract',
	'Class:bizContract/Attribute:status/Value:New' => 'New',
	'Class:bizContract/Attribute:status/Value:New+' => 'New',
	'Class:bizContract/Attribute:status/Value:Negotiating' => 'Negotiating',
	'Class:bizContract/Attribute:status/Value:Negotiating+' => 'Negotiating',
	'Class:bizContract/Attribute:status/Value:Signed' => 'Signed',
	'Class:bizContract/Attribute:status/Value:Signed+' => 'Signed',
	'Class:bizContract/Attribute:status/Value:Production' => 'Production',
	'Class:bizContract/Attribute:status/Value:Production+' => 'Production',
	'Class:bizContract/Attribute:status/Value:Finished' => 'Finished',
	'Class:bizContract/Attribute:status/Value:Finished+' => 'Finished',
	'Class:bizContract/Attribute:type' => 'Type',
	'Class:bizContract/Attribute:type+' => 'Type of the contract',
	'Class:bizContract/Attribute:type/Value:Hardware' => 'Hardware',
	'Class:bizContract/Attribute:type/Value:Hardware+' => 'Hardware',
	'Class:bizContract/Attribute:type/Value:Software' => 'Software',
	'Class:bizContract/Attribute:type/Value:Software+' => 'Software',
	'Class:bizContract/Attribute:type/Value:Support' => 'Support',
	'Class:bizContract/Attribute:type/Value:Support+' => 'Support',
	'Class:bizContract/Attribute:type/Value:Licence' => 'Licence',
	'Class:bizContract/Attribute:type/Value:Licence+' => 'Licence',
	'Class:bizContract/Attribute:version_number' => 'Version',
	'Class:bizContract/Attribute:version_number+' => 'Revision number for this contract',
));

//
// Class: lnkInfraContract
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraContract' => 'InfraContractLinks',
	'Class:lnkInfraContract+' => 'Infra covered by a contract',
	'Class:lnkInfraContract/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraContract/Attribute:infra_id+' => 'The infrastructure impacted',
	'Class:lnkInfraContract/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraContract/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraContract/Attribute:infra_status' => 'Status',
	'Class:lnkInfraContract/Attribute:infra_status+' => 'Status of the impacted infrastructure',
	'Class:lnkInfraContract/Attribute:contract_id' => 'Contract',
	'Class:lnkInfraContract/Attribute:contract_id+' => 'Contract id',
	'Class:lnkInfraContract/Attribute:contract_name' => 'Contract Name',
	'Class:lnkInfraContract/Attribute:contract_name+' => 'Name of the contract',
	'Class:lnkInfraContract/Attribute:coverage' => 'Coverage',
	'Class:lnkInfraContract/Attribute:coverage+' => 'coverage for the given infra',
	'Class:lnkInfraContract/Attribute:service_level' => 'Service Level',
	'Class:lnkInfraContract/Attribute:service_level+' => 'service level for the given infra',
));

//
// Class: lnkContactContract
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactContract' => 'ContactContractLink',
	'Class:lnkContactContract+' => 'Contact associated to a contract',
	'Class:lnkContactContract/Attribute:contact_id' => 'Contact',
	'Class:lnkContactContract/Attribute:contact_id+' => 'The contact linked to contract',
	'Class:lnkContactContract/Attribute:contact_mail' => 'Contact E-mail',
	'Class:lnkContactContract/Attribute:contact_mail+' => 'Mail for the contact',
	'Class:lnkContactContract/Attribute:contract_id' => 'Contract',
	'Class:lnkContactContract/Attribute:contract_id+' => 'Contract ID',
	'Class:lnkContactContract/Attribute:contract_name' => 'Contract Name',
	'Class:lnkContactContract/Attribute:contract_name+' => 'Name of the contract',
	'Class:lnkContactContract/Attribute:role' => 'Role',
	'Class:lnkContactContract/Attribute:role+' => 'Role of this contact for this contract',
));

//
// Class: lnkDocumentContract
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentContract' => 'DocumentsContractLinks',
	'Class:lnkDocumentContract+' => 'A link between a document and another contract',
	'Class:lnkDocumentContract/Attribute:doc_id' => 'Document',
	'Class:lnkDocumentContract/Attribute:doc_id+' => 'id of the Document',
	'Class:lnkDocumentContract/Attribute:doc_name' => 'Document Name',
	'Class:lnkDocumentContract/Attribute:doc_name+' => 'name of the document',
	'Class:lnkDocumentContract/Attribute:contract_id' => 'Contract',
	'Class:lnkDocumentContract/Attribute:contract_id+' => 'Contract linked to this document',
	'Class:lnkDocumentContract/Attribute:contract_name' => 'Contract Name',
	'Class:lnkDocumentContract/Attribute:contract_name+' => 'name of the linked contract',
	'Class:lnkDocumentContract/Attribute:link_type' => 'Link Type',
	'Class:lnkDocumentContract/Attribute:link_type+' => 'More information',
));

//
// Class: bizChangeTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizChangeTicket' => 'Change',
	'Class:bizChangeTicket+' => 'Change ticket',
	'Class:bizChangeTicket/Attribute:name' => 'Ticket Ref',
	'Class:bizChangeTicket/Attribute:name+' => 'Refence number ofr this change',
	'Class:bizChangeTicket/Attribute:title' => 'Title',
	'Class:bizChangeTicket/Attribute:title+' => 'Overview of the Change',
	'Class:bizChangeTicket/Attribute:type' => 'Change Type',
	'Class:bizChangeTicket/Attribute:type+' => 'Type of the Change',
	'Class:bizChangeTicket/Attribute:domain' => 'Domain',
	'Class:bizChangeTicket/Attribute:domain+' => 'Domain for the Change',
	'Class:bizChangeTicket/Attribute:reason' => 'Reason For Change',
	'Class:bizChangeTicket/Attribute:reason+' => 'Reason for the Change',
	'Class:bizChangeTicket/Attribute:requestor_id' => 'Requestor',
	'Class:bizChangeTicket/Attribute:requestor_id+' => 'who is requesting this change',
	'Class:bizChangeTicket/Attribute:requestor_mail' => 'Requestor',
	'Class:bizChangeTicket/Attribute:requestor_mail+' => 'mail of user requesting this change',
	'Class:bizChangeTicket/Attribute:org_id' => 'Customer',
	'Class:bizChangeTicket/Attribute:org_id+' => 'who is impacted by the ticket',
	'Class:bizChangeTicket/Attribute:customer_name' => 'Customer',
	'Class:bizChangeTicket/Attribute:customer_name+' => 'Name of the customer impacted by this ticket',
	'Class:bizChangeTicket/Attribute:ticket_status' => 'Status',
	'Class:bizChangeTicket/Attribute:ticket_status+' => 'Status of the ticket',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:New' => 'New (Unassigned)',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:New+' => 'Newly created ticket',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Validated' => 'Validated',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Validated+' => 'Ticket is validated',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Rejected' => 'Rejected',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Rejected+' => 'This ticket is not approved',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Assigned' => 'Assigned',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Assigned+' => 'Ticket is assigned',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:PlannedScheduled' => 'Planned&Scheduled',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:PlannedScheduled+' => 'Evaluation is done for this change',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Approved' => 'Approved',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Approved+' => 'Ticket is approved by CAB',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:NotApproved' => 'Not Approved',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:NotApproved+' => 'Ticket has not been approved by CAB',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Implemented' => 'Implementation',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Implemented+' => 'Work is in progress for this ticket',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Monitored' => 'Monitored',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Monitored+' => 'Change performed is now monitored',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Closed' => 'Closed',
	'Class:bizChangeTicket/Attribute:ticket_status/Value:Closed+' => 'Ticket is closed',
	'Class:bizChangeTicket/Attribute:creation_date' => 'Creation Date',
	'Class:bizChangeTicket/Attribute:creation_date+' => 'Change creation date',
	'Class:bizChangeTicket/Attribute:last_update' => 'Last Update',
	'Class:bizChangeTicket/Attribute:last_update+' => 'last time the Ticket was modified',
	'Class:bizChangeTicket/Attribute:start_date' => 'Start Date',
	'Class:bizChangeTicket/Attribute:start_date+' => 'Time the change is expected to start',
	'Class:bizChangeTicket/Attribute:end_date' => 'End Date',
	'Class:bizChangeTicket/Attribute:end_date+' => 'Date when the change is supposed to end',
	'Class:bizChangeTicket/Attribute:close_date' => 'Closure Date',
	'Class:bizChangeTicket/Attribute:close_date+' => 'Date when the Ticket was closed',
	'Class:bizChangeTicket/Attribute:impact' => 'Risk Assessment',
	'Class:bizChangeTicket/Attribute:impact+' => 'Impact of the change',
	'Class:bizChangeTicket/Attribute:workgroup_id' => 'Workgroup',
	'Class:bizChangeTicket/Attribute:workgroup_id+' => 'which workgroup is owning ticket',
	'Class:bizChangeTicket/Attribute:workgroup_name' => 'Workgroup',
	'Class:bizChangeTicket/Attribute:workgroup_name+' => 'name of workgroup managing the Ticket',
	'Class:bizChangeTicket/Attribute:agent_id' => 'Agent',
	'Class:bizChangeTicket/Attribute:agent_id+' => 'who is managing the ticket',
	'Class:bizChangeTicket/Attribute:agent_mail' => 'Agent',
	'Class:bizChangeTicket/Attribute:agent_mail+' => 'name of agent managing the Ticket',
	'Class:bizChangeTicket/Attribute:supervisorgroup_id' => 'Supervisor Group',
	'Class:bizChangeTicket/Attribute:supervisorgroup_id+' => 'which workgroup is supervising ticket',
	'Class:bizChangeTicket/Attribute:supervisorgroup_name' => 'Supervisor Group',
	'Class:bizChangeTicket/Attribute:supervisorgroup_name+' => 'name of the group supervising the Ticket',
	'Class:bizChangeTicket/Attribute:supervisor_id' => 'Supervisor',
	'Class:bizChangeTicket/Attribute:supervisor_id+' => 'who is managing the ticket',
	'Class:bizChangeTicket/Attribute:supervisor_mail' => 'Supervisor',
	'Class:bizChangeTicket/Attribute:supervisor_mail+' => 'name of agent supervising the Ticket',
	'Class:bizChangeTicket/Attribute:managergroup_id' => 'Manager Group',
	'Class:bizChangeTicket/Attribute:managergroup_id+' => 'which workgroup is approving ticket',
	'Class:bizChangeTicket/Attribute:managergroup_name' => 'Manager Group',
	'Class:bizChangeTicket/Attribute:managergroup_name+' => 'name of workgroup approving the Ticket',
	'Class:bizChangeTicket/Attribute:manager_id' => 'Manager',
	'Class:bizChangeTicket/Attribute:manager_id+' => 'who is approving the ticket',
	'Class:bizChangeTicket/Attribute:manager_mail' => 'Manager',
	'Class:bizChangeTicket/Attribute:manager_mail+' => 'name of agent approving the Ticket',
	'Class:bizChangeTicket/Attribute:outage' => 'Planned Outage',
	'Class:bizChangeTicket/Attribute:outage+' => 'Flag to define if there is a planned outage',
	'Class:bizChangeTicket/Attribute:outage/Value:Yes' => 'Yes',
	'Class:bizChangeTicket/Attribute:outage/Value:Yes+' => 'Yes',
	'Class:bizChangeTicket/Attribute:outage/Value:No' => 'No',
	'Class:bizChangeTicket/Attribute:outage/Value:No+' => 'No',
	'Class:bizChangeTicket/Attribute:change_request' => 'Change Request',
	'Class:bizChangeTicket/Attribute:change_request+' => 'Description of Change required',
	'Class:bizChangeTicket/Attribute:change_log' => 'Implementation Log',
	'Class:bizChangeTicket/Attribute:change_log+' => 'List all action performed during the change',
	'Class:bizChangeTicket/Attribute:fallback' => 'Fallback Plan',
	'Class:bizChangeTicket/Attribute:fallback+' => 'Instruction to come back to former situation',
	'Class:bizChangeTicket/Attribute:assignment_count' => 'Assignment Count',
	'Class:bizChangeTicket/Attribute:assignment_count+' => 'Number of times this ticket was assigned or reassigned',
	'Class:bizChangeTicket/Attribute:impacted_infra_manual' => 'Impacted Infrastructure',
	'Class:bizChangeTicket/Attribute:impacted_infra_manual+' => 'CIs that are impacted by this change',
	'Class:bizChangeTicket/Stimulus:ev_validate' => 'Validate this change',
	'Class:bizChangeTicket/Stimulus:ev_validate+' => 'Make sure it is a valid change request',
	'Class:bizChangeTicket/Stimulus:ev_reject' => 'Reject this change',
	'Class:bizChangeTicket/Stimulus:ev_reject+' => 'This change request is rejected because it is a non valid one',
	'Class:bizChangeTicket/Stimulus:ev_assign' => 'Assign this change',
	'Class:bizChangeTicket/Stimulus:ev_assign+' => 'This change request is assigned',
	'Class:bizChangeTicket/Stimulus:ev_reopen' => 'Modify this change',
	'Class:bizChangeTicket/Stimulus:ev_reopen+' => 'Update change request to make it valid',
	'Class:bizChangeTicket/Stimulus:ev_plan' => 'Plan this change',
	'Class:bizChangeTicket/Stimulus:ev_plan+' => 'Plan and Schedule this change for validation',
	'Class:bizChangeTicket/Stimulus:ev_approve' => 'Approve this change',
	'Class:bizChangeTicket/Stimulus:ev_approve+' => 'This change is approved by CAB',
	'Class:bizChangeTicket/Stimulus:ev_replan' => 'Update planning and schedule',
	'Class:bizChangeTicket/Stimulus:ev_replan+' => 'Modify Plan and Schedule in order to have this change re-validated',
	'Class:bizChangeTicket/Stimulus:ev_notapprove' => 'Not approve this change',
	'Class:bizChangeTicket/Stimulus:ev_notapprove+' => 'This change is not approved by CAB',
	'Class:bizChangeTicket/Stimulus:ev_implement' => 'Implement this change',
	'Class:bizChangeTicket/Stimulus:ev_implement+' => 'Implementation pahse for current change',
	'Class:bizChangeTicket/Stimulus:ev_monitor' => 'Monitor this change',
	'Class:bizChangeTicket/Stimulus:ev_monitor+' => 'Starting monitoring period for this change',
	'Class:bizChangeTicket/Stimulus:ev_finish' => 'Close change',
	'Class:bizChangeTicket/Stimulus:ev_finish+' => 'Change is done, and can be closed',
));

//
// Class: lnkInfraChangeTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraChangeTicket' => 'Infra Change Ticket',
	'Class:lnkInfraChangeTicket+' => 'Infra impacted by a Change ticket',
	'Class:lnkInfraChangeTicket/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraChangeTicket/Attribute:infra_id+' => 'The infrastructure impacted',
	'Class:lnkInfraChangeTicket/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraChangeTicket/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraChangeTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkInfraChangeTicket/Attribute:ticket_id+' => 'Ticket number',
	'Class:lnkInfraChangeTicket/Attribute:ticket_name' => 'Ticket Name',
	'Class:lnkInfraChangeTicket/Attribute:ticket_name+' => 'Name of the ticket',
	'Class:lnkInfraChangeTicket/Attribute:impact' => 'Impact',
	'Class:lnkInfraChangeTicket/Attribute:impact+' => 'Level of impact of the infra by the related ticket',
));

//
// Class: lnkContactChange
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkContactChange' => 'ContactChangeLink',
	'Class:lnkContactChange+' => 'Contact associated to a change',
	'Class:lnkContactChange/Attribute:contact_id' => 'Contact',
	'Class:lnkContactChange/Attribute:contact_id+' => 'The contact linked to contract',
	'Class:lnkContactChange/Attribute:contact_mail' => 'Contact E-mail',
	'Class:lnkContactChange/Attribute:contact_mail+' => 'Mail for the contact',
	'Class:lnkContactChange/Attribute:change_id' => 'Change Ticket',
	'Class:lnkContactChange/Attribute:change_id+' => 'Change ticket ID',
	'Class:lnkContactChange/Attribute:change_number' => 'Change Ticket',
	'Class:lnkContactChange/Attribute:change_number+' => 'Ticket number for this change',
	'Class:lnkContactChange/Attribute:role' => 'Role',
	'Class:lnkContactChange/Attribute:role+' => 'Role of this contact for this change',
));

//
// Class: bizKnownError
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizKnownError' => 'Known Error',
	'Class:bizKnownError+' => 'Error documented for a known issue',
	'Class:bizKnownError/Attribute:name' => 'Name',
	'Class:bizKnownError/Attribute:name+' => 'Name to identify this error',
	'Class:bizKnownError/Attribute:org_id' => 'Organization',
	'Class:bizKnownError/Attribute:org_id+' => 'Organization for this known error',
	'Class:bizKnownError/Attribute:cust_name' => 'Organization',
	'Class:bizKnownError/Attribute:cust_name+' => 'Company / Department owning this object',
	'Class:bizKnownError/Attribute:symptom' => 'Symptom',
	'Class:bizKnownError/Attribute:symptom+' => 'Description of this error',
	'Class:bizKnownError/Attribute:root_cause' => 'Root cause',
	'Class:bizKnownError/Attribute:root_cause+' => 'Original cause for this known error',
	'Class:bizKnownError/Attribute:workaround' => 'Workaround',
	'Class:bizKnownError/Attribute:workaround+' => 'Work around to fix this error',
	'Class:bizKnownError/Attribute:solution' => 'Solution',
	'Class:bizKnownError/Attribute:solution+' => 'Description of this contract',
	'Class:bizKnownError/Attribute:error_code' => 'Error Code',
	'Class:bizKnownError/Attribute:error_code+' => 'Key word to identify error',
	'Class:bizKnownError/Attribute:domain' => 'Domain',
	'Class:bizKnownError/Attribute:domain+' => 'Domain for this known error, network, desktop, ...',
	'Class:bizKnownError/Attribute:domain/Value:Network' => 'Network',
	'Class:bizKnownError/Attribute:domain/Value:Network+' => 'Network',
	'Class:bizKnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:bizKnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:bizKnownError/Attribute:domain/Value:Application' => 'Application',
	'Class:bizKnownError/Attribute:domain/Value:Application+' => 'Application',
	'Class:bizKnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:bizKnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:bizKnownError/Attribute:vendor' => 'Vendor',
	'Class:bizKnownError/Attribute:vendor+' => 'Vendor concerned by this known error',
	'Class:bizKnownError/Attribute:model' => 'Model',
	'Class:bizKnownError/Attribute:model+' => 'Model concerned by this known error, it may be an application, a device ...',
	'Class:bizKnownError/Attribute:version' => 'Version',
	'Class:bizKnownError/Attribute:version+' => 'Version related to model impacted by known error',
));

//
// Class: lnkInfraError
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraError' => 'InfraErrorLinks',
	'Class:lnkInfraError+' => 'Infra related to a known error',
	'Class:lnkInfraError/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraError/Attribute:infra_id+' => 'The infrastructure impacted',
	'Class:lnkInfraError/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraError/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraError/Attribute:infra_status' => 'Status',
	'Class:lnkInfraError/Attribute:infra_status+' => 'Status of the impacted infrastructure',
	'Class:lnkInfraError/Attribute:error_id' => 'Error',
	'Class:lnkInfraError/Attribute:error_id+' => 'Error id',
	'Class:lnkInfraError/Attribute:error_name' => 'Error Name',
	'Class:lnkInfraError/Attribute:error_name+' => 'Name of the error',
));

//
// Class: lnkDocumentError
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentError' => 'DocumentsErrorLinks',
	'Class:lnkDocumentError+' => 'A link between a document and a known error',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Document',
	'Class:lnkDocumentError/Attribute:doc_id+' => 'id of the Document',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Document Name',
	'Class:lnkDocumentError/Attribute:doc_name+' => 'name of the document',
	'Class:lnkDocumentError/Attribute:error_id' => 'Error',
	'Class:lnkDocumentError/Attribute:error_id+' => 'Error linked to this document',
	'Class:lnkDocumentError/Attribute:error_name' => 'Error Name',
	'Class:lnkDocumentError/Attribute:error_name+' => 'name of the linked error',
	'Class:lnkDocumentError/Attribute:link_type' => 'Link Type',
	'Class:lnkDocumentError/Attribute:link_type+' => 'More information',
));

//
// Class: bizServiceCall
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:bizServiceCall' => 'ServiceCall',
	'Class:bizServiceCall+' => 'Service Call from customer',
	'Class:bizServiceCall/Attribute:name' => 'Service Call Ref',
	'Class:bizServiceCall/Attribute:name+' => 'Refence identifier for this service call',
	'Class:bizServiceCall/Attribute:title' => 'Title',
	'Class:bizServiceCall/Attribute:title+' => 'Overview of the service call',
	'Class:bizServiceCall/Attribute:type' => 'Type',
	'Class:bizServiceCall/Attribute:type+' => 'Type of the Incident',
	'Class:bizServiceCall/Attribute:type/Value:Network' => 'Network',
	'Class:bizServiceCall/Attribute:type/Value:Network+' => 'Network',
	'Class:bizServiceCall/Attribute:type/Value:Server' => 'Server',
	'Class:bizServiceCall/Attribute:type/Value:Server+' => 'Server',
	'Class:bizServiceCall/Attribute:type/Value:Desktop' => 'Desktop',
	'Class:bizServiceCall/Attribute:type/Value:Desktop+' => 'Desktop',
	'Class:bizServiceCall/Attribute:type/Value:Application' => 'Application',
	'Class:bizServiceCall/Attribute:type/Value:Application+' => 'Application',
	'Class:bizServiceCall/Attribute:org_id' => 'Customer',
	'Class:bizServiceCall/Attribute:org_id+' => 'Customer concerned by this service call',
	'Class:bizServiceCall/Attribute:customer_name' => 'Customer',
	'Class:bizServiceCall/Attribute:customer_name+' => 'Name of the customer raising this service call',
	'Class:bizServiceCall/Attribute:call_status' => 'Status',
	'Class:bizServiceCall/Attribute:call_status+' => 'Status of the ticket',
	'Class:bizServiceCall/Attribute:call_status/Value:New' => 'New (Unassigned)',
	'Class:bizServiceCall/Attribute:call_status/Value:New+' => 'Newly created call',
	'Class:bizServiceCall/Attribute:call_status/Value:Assigned' => 'Assigned',
	'Class:bizServiceCall/Attribute:call_status/Value:Assigned+' => 'Call is assigned to somebody',
	'Class:bizServiceCall/Attribute:call_status/Value:WorkInProgress' => 'Work In Progress',
	'Class:bizServiceCall/Attribute:call_status/Value:WorkInProgress+' => 'Work is in progress',
	'Class:bizServiceCall/Attribute:call_status/Value:Resolved' => 'Resolved',
	'Class:bizServiceCall/Attribute:call_status/Value:Resolved+' => 'Call is resolved',
	'Class:bizServiceCall/Attribute:call_status/Value:Closed' => 'Closed',
	'Class:bizServiceCall/Attribute:call_status/Value:Closed+' => 'Call is closed',
	'Class:bizServiceCall/Attribute:call_description' => 'Description',
	'Class:bizServiceCall/Attribute:call_description+' => 'Description of the call as describe by caller',
	'Class:bizServiceCall/Attribute:creation_date' => 'Creation date',
	'Class:bizServiceCall/Attribute:creation_date+' => 'Call creation date',
	'Class:bizServiceCall/Attribute:last_update' => 'Last update',
	'Class:bizServiceCall/Attribute:last_update+' => 'last time the call was modified',
	'Class:bizServiceCall/Attribute:next_update' => 'Next update',
	'Class:bizServiceCall/Attribute:next_update+' => 'next time the Ticket is expected to be  modified',
	'Class:bizServiceCall/Attribute:end_date' => 'Closure Date',
	'Class:bizServiceCall/Attribute:end_date+' => 'Date when the call was closed',
	'Class:bizServiceCall/Attribute:caller_id' => 'Caller',
	'Class:bizServiceCall/Attribute:caller_id+' => 'person that trigger this call',
	'Class:bizServiceCall/Attribute:caller_mail' => 'Caller',
	'Class:bizServiceCall/Attribute:caller_mail+' => 'Person that trigger this call',
	'Class:bizServiceCall/Attribute:impact' => 'Impact',
	'Class:bizServiceCall/Attribute:impact+' => 'Impact for this call',
	'Class:bizServiceCall/Attribute:workgroup_id' => 'Workgroup',
	'Class:bizServiceCall/Attribute:workgroup_id+' => 'which workgroup is owning call',
	'Class:bizServiceCall/Attribute:workgroup_name' => 'Workgroup',
	'Class:bizServiceCall/Attribute:workgroup_name+' => 'name of workgroup managing the call',
	'Class:bizServiceCall/Attribute:agent_id' => 'Agent',
	'Class:bizServiceCall/Attribute:agent_id+' => 'who is managing the call',
	'Class:bizServiceCall/Attribute:agent_mail' => 'Agent',
	'Class:bizServiceCall/Attribute:agent_mail+' => 'mail of agent managing the call',
	'Class:bizServiceCall/Attribute:action_log' => 'Action Logs',
	'Class:bizServiceCall/Attribute:action_log+' => 'List all action performed during the call',
	'Class:bizServiceCall/Attribute:severity' => 'Severity',
	'Class:bizServiceCall/Attribute:severity+' => 'Field defining the criticity for the call',
	'Class:bizServiceCall/Attribute:severity/Value:critical' => 'critical',
	'Class:bizServiceCall/Attribute:severity/Value:critical+' => 'critical',
	'Class:bizServiceCall/Attribute:severity/Value:medium' => 'medium',
	'Class:bizServiceCall/Attribute:severity/Value:medium+' => 'medium',
	'Class:bizServiceCall/Attribute:severity/Value:low' => 'low',
	'Class:bizServiceCall/Attribute:severity/Value:low+' => 'low',
	'Class:bizServiceCall/Attribute:resolution' => 'Resolution',
	'Class:bizServiceCall/Attribute:resolution+' => 'Description of the resolution',
	'Class:bizServiceCall/Attribute:source' => 'Source',
	'Class:bizServiceCall/Attribute:source+' => 'source type for this call',
	'Class:bizServiceCall/Attribute:source/Value:phone' => 'phone',
	'Class:bizServiceCall/Attribute:source/Value:phone+' => 'phone',
	'Class:bizServiceCall/Attribute:source/Value:E-mail' => 'E-mail',
	'Class:bizServiceCall/Attribute:source/Value:E-mail+' => 'E-mail',
	'Class:bizServiceCall/Attribute:source/Value:Fax' => 'Fax',
	'Class:bizServiceCall/Attribute:source/Value:Fax+' => 'Fax',
	'Class:bizServiceCall/Attribute:impacted_infra_manual' => 'Impacted Infrastructure',
	'Class:bizServiceCall/Attribute:impacted_infra_manual+' => 'CIs that are not meeting the SLA',
	'Class:bizServiceCall/Attribute:related_tickets' => 'Related Incident',
	'Class:bizServiceCall/Attribute:related_tickets+' => 'Other incident tickets related to this call',
	'Class:bizServiceCall/Stimulus:ev_assign' => 'Assign this call',
	'Class:bizServiceCall/Stimulus:ev_assign+' => 'Assign this call to a group and an agent',
	'Class:bizServiceCall/Stimulus:ev_reassign' => 'Reassign this call',
	'Class:bizServiceCall/Stimulus:ev_reassign+' => 'Reassign this call to a different group and agent',
	'Class:bizServiceCall/Stimulus:ev_start_working' => 'Work on this call',
	'Class:bizServiceCall/Stimulus:ev_start_working+' => 'Start working on this call',
	'Class:bizServiceCall/Stimulus:ev_resolve' => 'Resolve this call',
	'Class:bizServiceCall/Stimulus:ev_resolve+' => 'Resolve this call',
	'Class:bizServiceCall/Stimulus:ev_close' => 'Close this call',
	'Class:bizServiceCall/Stimulus:ev_close+' => 'Close this call',
));

//
// Class: lnkCallTicket
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkCallTicket' => 'Call Ticket',
	'Class:lnkCallTicket+' => 'Ticket related to a call',
	'Class:lnkCallTicket/Attribute:ticket_id' => 'Related Ticket',
	'Class:lnkCallTicket/Attribute:ticket_id+' => 'The related ticket',
	'Class:lnkCallTicket/Attribute:ticket_name' => 'Related ticket',
	'Class:lnkCallTicket/Attribute:ticket_name+' => 'Name of the related ticket',
	'Class:lnkCallTicket/Attribute:call_id' => 'Call',
	'Class:lnkCallTicket/Attribute:call_id+' => 'Ticket number',
	'Class:lnkCallTicket/Attribute:call_name' => 'Call name',
	'Class:lnkCallTicket/Attribute:call_name+' => 'Name of the call',
	'Class:lnkCallTicket/Attribute:impact' => 'Impact',
	'Class:lnkCallTicket/Attribute:impact+' => 'Impact on the call',
));

//
// Class: lnkInfraCall
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraCall' => 'Infra Call',
	'Class:lnkInfraCall+' => 'Infra concerned by a call',
	'Class:lnkInfraCall/Attribute:infra_id' => 'Infrastructure',
	'Class:lnkInfraCall/Attribute:infra_id+' => 'The infrastructure impacted',
	'Class:lnkInfraCall/Attribute:infra_name' => 'Infrastructure Name',
	'Class:lnkInfraCall/Attribute:infra_name+' => 'Name of the impacted infrastructure',
	'Class:lnkInfraCall/Attribute:call_id' => 'Call',
	'Class:lnkInfraCall/Attribute:call_id+' => 'Call number',
	'Class:lnkInfraCall/Attribute:call_name' => 'Call name',
	'Class:lnkInfraCall/Attribute:call_name+' => 'Name of the call',
	'Class:lnkInfraCall/Attribute:impact' => 'Impact',
	'Class:lnkInfraCall/Attribute:impact+' => 'Level of impact of the infra by the related ticket',
));


?>

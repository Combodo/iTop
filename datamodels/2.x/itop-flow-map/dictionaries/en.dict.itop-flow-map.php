<?php

/**
 * Module combodo-flow-map
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', [

	'Relation:flow/Description' => 'Flow maps',
	'Relation:flow/DownStream' => 'Sent flow...',
	'Relation:flow/UpStream' => 'Received flow...',

	'Class:DataFlow' => 'Flow',
	'Class:DataFlow+' => 'For application flow for example',
	'Class:DataFlow/Name' => '%1$s from %2$s to %3$s',
	'Class:DataFlow/Attribute:name' => 'Name',
	'Class:DataFlow/Attribute:name_id+' => 'Data that are transferred',
	'Class:DataFlow/Attribute:source_id' => 'Source',
	'Class:DataFlow/Attribute:source_id+' => 'Source Ci of the flow',
	'Class:DataFlow/Attribute:destination_id' => 'Destination',
	'Class:DataFlow/Attribute:destination_id+' => 'Destination Ci for the flow',
	'Class:DataFlow/Attribute:type_id' => 'Flow type',
	'Class:DataFlow/Attribute:type_id+' => 'Typology of Flow.',
	'Class:DataFlow/Attribute:description' => 'Description',
	'Class:DataFlow/Attribute:description+' => '',
	'Class:DataFlow/Attribute:status' => 'Status',
	'Class:DataFlow/Attribute:status+' => '',
	'Class:DataFlow/Attribute:status/Value:active' => 'active',
	'Class:DataFlow/Attribute:status/Value:inactive' => 'inactive',
	'Class:DataFlow/Attribute:org_id' => 'Organization',
	'Class:DataFlow/Attribute:org_id+' => '',
	'Class:DataFlow/Attribute:business_criticity' => 'Business criticality',
	'Class:DataFlow/Attribute:business_criticity+' => '',
	'Class:DataFlow/Attribute:business_criticity/Value:high' => 'high',
	'Class:DataFlow/Attribute:business_criticity/Value:high+' => '',
	'Class:DataFlow/Attribute:business_criticity/Value:low' => 'low',
	'Class:DataFlow/Attribute:business_criticity/Value:low+' => '',
	'Class:DataFlow/Attribute:business_criticity/Value:medium' => 'medium',
	'Class:DataFlow/Attribute:business_criticity/Value:medium+' => '',
	'Class:DataFlow/Attribute:execution_frequency' => 'Execution frequency',
	'Class:DataFlow/Attribute:execution_frequency+' => 'How often the data flow is executed',
	'Class:DataFlow/Attribute:execution_frequency/Value:real_time' => 'real-time',
	'Class:DataFlow/Attribute:execution_frequency/Value:real_time+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly' => 'hourly',
	'Class:DataFlow/Attribute:execution_frequency/Value:hourly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily' => 'daily',
	'Class:DataFlow/Attribute:execution_frequency/Value:daily+'	=> '',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly' => 'weekly',
	'Class:DataFlow/Attribute:execution_frequency/Value:weekly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly' => 'monthly',
	'Class:DataFlow/Attribute:execution_frequency/Value:monthly+' => '',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly'	=> 'yearly',
	'Class:DataFlow/Attribute:execution_frequency/Value:yearly+' => '',

/*
	'Class:DataFlow/Attribute:source_id_friendlyname'             => 'source_id_friendlyname',
	'Class:DataFlow/Attribute:source_id_friendlyname+'            => 'Full name',
	'Class:DataFlow/Attribute:source_id_finalclass_recall'        => 'source_id->CI sub-class',
	'Class:DataFlow/Attribute:source_id_finalclass_recall+'       => 'Name of the final class',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag'        => 'source_id->Obsolete',
	'Class:DataFlow/Attribute:source_id_obsolescence_flag+'       => 'Computed dynamically on other attributes',
	'Class:DataFlow/Attribute:destination_id_friendlyname'        => 'destination_id_friendlyname',
	'Class:DataFlow/Attribute:destination_id_friendlyname+'       => 'Full name',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall'   => 'destination_id->CI sub-class',
	'Class:DataFlow/Attribute:destination_id_finalclass_recall+'  => 'Name of the final class',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag'   => 'destination_id->Obsolete',
	'Class:DataFlow/Attribute:destination_id_obsolescence_flag+'  => 'Computed dynamically on other attributes',
*/
]);
